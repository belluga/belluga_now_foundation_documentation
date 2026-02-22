# MVP Endpoint Contracts (Response Schemas)

**Status:** Draft  
**Purpose:** Define the MVP endpoints and the minimum response schemas required so Flutter can mock and backend can implement with the same contract.  
**Scope:** MVP only (account-profile invites are allowed; memberships are deferred; invite metrics **data capture is required**, dashboards are deferred; sponsor POIs are deferred).

---

## 0) Conventions
- Base prefix is `/api/v1` (router-mounted). Paths below omit the prefix unless explicitly stated.
- All responses include `tenant_id` when the request is tenant-scoped.
- IDs are stable string IDs (Mongo ObjectId as string).
- Date/times are ISO 8601 (`YYYY-MM-DDTHH:mm:ssZ`).
- Home composition is client-side only. There is **no** aggregated `/home-overview` endpoint; use independent requests (e.g., `/invites`, `/agenda` with `confirmed_only=true` for confirmed events, `/account_profiles/discovery`, `/missions`, `/discover/people`, `/discover/curator-content`, `/map/pois`).
- Event list queries **must** treat “happening now” as part of the “upcoming” bucket. This applies to any list surfaced as “upcoming” or “my events” (confirmed).
- Pagination conventions (MVP): **all lists are page-based**.
  - Request: `page` (int, optional), `page_size` or `per_page` (int, optional).
  - Response: `has_more` (bool) for app feeds, or paginator fields for admin lists.
- Distance fields:
  - `distance_meters` is returned when the backend computes distance from an origin (see Map).
  - For non-map lists (agenda/home), include `distance_meters` only when requested; otherwise omit.
- Taxonomy terms are typed pairs: `{ "type": "string", "value": "string" }` (WordPress-style, multi-taxonomy per account profile).

---

## 1) Identity + Bootstrap

### `POST /anonymous/identities`
**Purpose:** Create or resume anonymous identity for web/app flows.  
**Request (body):**
```json
{
  "device_name": "string",
  "fingerprint": {
    "hash": "string",
    "user_agent": "string?",
    "locale": "string?"
  },
  "metadata": {}
}
```
**Response:**
```json
{
  "data": {
    "user_id": "string",
    "identity_state": "anonymous",
    "token": "string",
    "abilities": ["string"],
    "expires_at": "2025-01-01T00:00:00Z"
  }
}
```
**Field Definitions**
- `identity_state`: `anonymous`.

**Client retry policy (Flutter):** up to 3 attempts with delays of 200ms then 800ms; fail hard after final attempt.

### `POST /auth/register/password`
**Purpose:** Register an authenticated user.  
**Response:** Must include the same payload as `GET /me` (or embed it under a `me` key) so the client does not need a follow‑up request.  
**Side effect:** On first authenticated identification, backend auto‑creates the user’s **personal Account + Account Profile** (user_owned, private by default).

### `GET /auth/token_validate`
**Purpose:** Validate the bearer token and return a minimal user profile.  
**Request (headers):** `Authorization: Bearer {token}`  
**Response:**
```json
{
  "data": {
    "user": {
      "id": "string",
      "name": "string?",
      "emails": ["string"],
      "custom_data": {}
    }
  }
}
```

### `GET /api/v1/environment` (root or tenant subdomain)
**Purpose:** Resolve landlord/tenant context + branding.  
**Request (query):** `app_domain`, `domain`, `subdomain` (all optional).  
**Tenant resolution:** App calls the configured tenant domain directly. Web resolves by the request host domain. Root-level usage is optional for discovery/lookup flows.
**Response (minimum):**
```json
{
  "type": "landlord|tenant",
  "tenant_id": "string?",
  "name": "string",
  "subdomain": "string?",
  "main_domain": "string",
  "domains": ["string"],
  "app_domains": ["string"],
  "theme_data_settings": {
    "primary_seed_color": "#RRGGBB",
    "secondary_seed_color": "#RRGGBB",
    "brightness_default": "light|dark"
  },
  "telemetry": {
    "trackers": [
      {
        "type": "mixpanel|firebase|webhook",
        "token": "string?",
        "url": "string?",
        "track_all": true,
        "events": ["string"]
      }
    ],
    "location_freshness_minutes": 5
  },
  "firebase": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  },
  "push": {
    "enabled": true,
    "types": ["string"],
    "throttles": {}
  },
  "profile_types": [
    {
      "type": "string",
      "label": "string",
      "allowed_taxonomies": ["string"],
      "capabilities": {
        "is_favoritable": true,
        "is_poi_enabled": false
      }
    }
  ],
  "settings": {
    "map_ui": {
      "radius": {
        "min_km": 1,
        "default_km": 5,
        "max_km": 50
      }
    }
  }
}
```
**Field Definitions**
- `type`: `landlord`, `tenant`.
- `theme_data_settings.brightness_default`: `light`, `dark`.
- `telemetry.trackers[].type`: `mixpanel`, `firebase`, `webhook`.
- `telemetry.location_freshness_minutes`: Integer minutes; defaults to 5 when omitted.
- `settings.map_ui.radius.min_km`: Minimum radius bound for map/agenda filters (km).
- `profile_types`: Tenant profile type registry entries used to drive profile UI + favorites.
- `settings.map_ui.radius.default_km`: Default radius for map/agenda filters (km).
- `settings.map_ui.radius.max_km`: Maximum radius bound for map/agenda filters (km).

**Branding assets:** use default paths `GET /logo-light.png`, `/logo-dark.png`, `/icon-light.png`, `/icon-dark.png` (no direct URLs in this payload).

---

## 2) Home + Discovery

### `GET /me`
**Purpose:** Authenticated profile summary + role claims.  
**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "user_id": "string",
    "display_name": "string",
    "avatar_url": "string?",
    "user_level": "basic|verified",
    "privacy_mode": "public|friends_only",
    "social_score": {
      "invites_accepted": 0,
      "presences_confirmed": 0,
      "rank_label": "string?"
    },
    "counters": {
      "pending_invites": 0,
      "confirmed_events": 0,
      "favorites": 0
    },
    "role_claims": {
      "is_account_operator": false,
      "is_curator": false,
      "is_verified": false
    }
  }
}
```
**Field Definitions**
- `user_level`: `basic`, `verified`.
- `privacy_mode`: `public`, `friends_only`.

---

## 3) Invites (User + Account Profile)

### `GET /invites/stream` (SSE)
**Purpose:** Stream invite changes (SSE).  
**Request (query):** same pagination filters as `/invites` (`page`, `page_size`); stream applies to the authenticated user.  
**Event types:**
- `invite.created`
- `invite.updated`
- `invite.deleted`
**Payload (minimum):**
```json
{ "invite_id": "string", "type": "invite.created|invite.updated|invite.deleted", "updated_at": "2025-01-01T00:00:00Z" }
```

### `GET /invites`
**Purpose:** Invites feed and referral context.  
**Request (query):**
- `page` (optional)
- `page_size` (optional)
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "invites": [
    {
      "id": "string",
      "event_id": "string",
      "event_name": "string",
      "event_date": "2025-01-01T00:00:00Z",
      "event_image_url": "string",
      "location": "string",
      "host_name": "string",
      "message": "string",
      "tags": ["string"],
      "inviter_name": "string?",
      "inviter_avatar_url": "string?",
      "additional_inviters": ["string"]
    }
  ],
  "has_more": true
}
```

### `GET /invites/settings`
**Purpose:** Invite quotas + UX messaging limits.  
**Response:**
```json
{
  "tenant_id": "string",
  "limits": {
    "pending_limit_basic": 20,
    "pending_limit_verified": 50,
    "pending_limit_account_paid": 100,
    "per_event_invite_limit": 1
  },
  "cooldowns": {
    "share_code_cooldown_seconds": 0
  },
  "reset_at": "2025-01-01T00:00:00Z?",
  "over_quota_message": "string?"
}
```

### `POST /invites/share`
**Purpose:** Create share code for invite attribution.  
**Request (body):**
```json
{ "event_id": "string", "account_profile_id": "string?" }
```
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "code": "string",
  "event_id": "string",
  "inviter_principal": { "kind": "user|account_profile", "id": "string" }
}
```
**Eligibility Rules (MVP):**
- **User invites:** only to imported contacts (hashed) or existing app users.
- **Account Profile invites:** may target followers/favorites for broader reach; direct user targeting allowed as needed.
- When `inviter_principal.kind = account_profile`, `account_profile_id` is **required** and must belong to the inviter’s account/tenant context.

---

## 9) Push Messages (Tenant)

### `GET /push/messages/{push_message_id}/data`
**Purpose:** Fetch user-specific push payload rendered for the authenticated user.  
**Auth:** Bearer token (tenant user).  
**Request (headers):**
- `Authorization: Bearer <token>`

**Response (success):**
```json
{
  "ok": true,
  "push_message_id": "string",
  "payload": {
    "title": "string",
    "body": "string",
    "layoutType": "MessageLayoutType.popup",
    "allowDismiss": true,
    "steps": [],
    "buttons": []
  }
}
```
**Response (not eligible or missing):**
```json
{ "ok": false, "reason": "not_found" }
```
**Response (inactive):**
```json
{ "ok": false, "reason": "inactive" }
```
**Response (expired):**
```json
{ "ok": false, "reason": "expired" }
```

**Field Definitions**
- `reason`: `not_found`, `inactive`, `expired`.

### `POST /push/messages/{push_message_id}/actions`
**Purpose:** Record delivery/opened/button actions for a push message.  
**Auth:** Bearer token (tenant user).  
**Request (body):**
```json
{
  "action": "opened|delivered|clicked",
  "step_index": 0,
  "button_key": "string?",
  "metadata": {}
}
```
**Field Definitions**
- `action`: `opened`, `delivered`, `clicked`.

**Response (minimum):**
```json
{ "ok": true, "data": { "action": "string" } }
```
**Field Definitions**
- `inviter_principal.kind`: `user`, `account_profile`.
**Implementation Notes (Uniqueness):**
- `code` must be unique per tenant. Enforce a unique index on `(tenant_id, code)` (or global unique if tenant scope is not used).
- Generate URL-safe codes (base62/ULID/short hash). Recommended length ≥ 8–10 chars.
- On insert conflict, retry generation (e.g., up to 3–5 attempts) before failing.

### `POST /invites/share/{code}/accept`
**Purpose:** Accept invite from web landing by code.  
**Request (headers/body):** Auth via Sanctum (anonymous identity). No body required.  
**Response:**
```json
{
  "tenant_id": "string",
  "invite_id": "string",
  "event_id": "string",
  "inviter_principal": { "kind": "user|account_profile", "id": "string" },
  "status": "accepted|declined|already_accepted|expired",
  "attribution_bound": true,
  "accepted_at": "2025-01-01T00:00:00Z?"
}
```
**Field Definitions**
- `inviter_principal.kind`: `user`, `account_profile`.
- `status`: `accepted`, `declined`, `already_accepted`, `expired`.

**Requirement:** Invite share links must carry `code` as a GET parameter.
**Tracking Notes:**
- `share_visit` is tracked separately from invites; it does **not** count as an accepted invite.
- When an acceptance occurs via this endpoint, it **does** count as `invite_accepted` with `source = share_url`.

#### Invite Flow & Friends Management (MVP)
- **Primary invite paths:**
- **Direct invite (existing user):** server creates a per-recipient invite and sends push. Source = `direct_invite`.
  - **Share link:** server issues a `code` via `POST /invites/share`; no per-recipient record until acceptance. Source = `share_url`.
- **Acceptance rules:** only one accepted invite per invitee per event. Acceptances should bind attribution to the inviter principal.
- **Tracking:** `share_visit` is analytics-only. `invite_sent` and `invite_accepted` should include a `source` (`direct_invite`, `share_url`).
- **Friends (MVP):** no social graph. “Friends” are matched contacts from hashed imports only; raw PII is never stored.
- **Delivery fallback:** if a contact hash matches an existing user, send push; if no match, use a share URL (e.g., WhatsApp deep link) with the invite `code`.

### `POST /contacts/import`
**Purpose:** Import hashed contacts for friend suggestions and invite matching.  
**Request (body):**
```json
{
  "contacts": [
    { "type": "phone|email", "hash": "string" }
  ],
  "salt_version": "string?"
}
```
**Response:**
```json
{
  "tenant_id": "string",
  "matches": [
    {
      "contact_hash": "string",
      "type": "phone|email",
      "user_id": "string",
      "display_name": "string?",
      "avatar_url": "string?"
    }
  ],
  "unmatched_count": 0
}
```
**Field Definitions**
- `contacts[].type`: `phone`, `email`.
- `matches[].type`: `phone`, `email`.

---

## 4) Events + Agenda

### `GET /events/stream` (SSE)
**Purpose:** Stream event changes for active app filters (SSE).  
**Request (query):** same filters as `/agenda` (`search`, `categories[]`, `tags[]`, `taxonomy[]`, `confirmed_only`, `origin_lat`, `origin_lng`, `max_distance_meters`).  
**Headers (optional):** `Last-Event-ID` to resume from the last received SSE cursor.  
**Event types:**
- `event.created` (new event matches filters)
- `event.updated` (fields changed)
- `event.deleted` (event removed)
**Payload (minimum):**
```json
{ "event_id": "string", "type": "event.created|event.updated|event.deleted", "updated_at": "2025-01-01T00:00:00Z" }
```
**Resync behavior:** if the client connects without `Last-Event-ID` or the stream reconnects after an error, refresh page 1 from `/agenda` as the source of truth.

### `GET /agenda`
**Purpose:** Paged agenda feed.  
**Request (query):**
```json
{
  "page": 1,
  "page_size": 10,
  "past_only": false,
  "search": "string?",
  "categories": ["string"],
  "tags": ["string"],
  "taxonomy": [{ "type": "string", "value": "string" }],
  "origin_lat": 0.0,
  "origin_lng": 0.0,
  "max_distance_meters": 100000,
  "confirmed_only": false
}
```
**Notes (mock behavior):**
- `past_only=false` returns upcoming events **plus** events happening now.
- `past_only=true` returns events that started before now **and are not happening now**.
- "Happening now" means `date_time_start <= now < date_time_end`. If `date_time_end` is missing, assume `date_time_start + 3h`.
- `confirmed_only=true` returns only events confirmed by the current user (includes “happening now” using the same rule above).
- Sort order: upcoming/now ascending by `date_time_start`, past descending by `date_time_start`.
- Search matches `title`, `content`, any `artists[].display_name`, or `venue.display_name` (case-insensitive).
- Categories filter matches `type.slug` or event categories when available (case-insensitive).
- Tags filter matches any `tags[]` on the event (case-insensitive).
- Taxonomy filter matches any `taxonomy_terms` attached to the venue or artists (case-insensitive).
- If `origin_lat`/`origin_lng` are provided, filter within `max_distance_meters` using **tenant settings defaults** (`map_ui.radius.default_km`, bounded by `min_km`/`max_km`). If no matches, fall back to the unfiltered list.

**Response (minimum):**
```json
{
  "tenant_id": "string",
  "items": [
    {
      "id": "string",
      "slug": "string",
      "type": {
        "id": "string",
        "name": "string",
        "slug": "string",
        "description": "string",
        "icon": "string?",
        "color": "#RRGGBB?"
      },
      "title": "string",
      "content": "string",
      "venue": {
        "id": "string",
        "display_name": "string",
        "tagline": "string?",
        "hero_image_url": "string?",
        "logo_url": "string?",
        "taxonomy_terms": [{ "type": "string", "value": "string" }]
      },
      "latitude": 0.0,
      "longitude": 0.0,
      "thumb": { "type": "image", "data": { "url": "string" } },
      "date_time_start": "2025-01-01T00:00:00Z",
      "date_time_end": "2025-01-01T00:00:00Z?",
      "artists": [
        { "id": "string", "display_name": "string", "avatar_url": "string?", "highlight": false, "genres": ["string"] }
      ],
      "is_confirmed": false,
      "total_confirmed": 0,
      "received_invites": [
        {
          "id": "string",
          "event_id": "string",
          "event_name": "string",
          "event_date": "2025-01-01T00:00:00Z",
          "event_image_url": "string",
          "location": "string",
          "host_name": "string",
          "message": "string",
          "tags": ["string"],
          "inviter_name": "string?",
          "inviter_avatar_url": "string?",
          "additional_inviters": ["string"]
        }
      ],
      "sent_invites": [
        {
          "friend": { "id": "string", "display_name": "string", "avatar_url": "string?" },
          "status": "pending|accepted|declined|viewed",
          "sent_at": "2025-01-01T00:00:00Z",
          "responded_at": "2025-01-01T00:00:00Z?"
        }
      ],
      "friends_going": [
        { "id": "string", "display_name": "string", "avatar_url": "string?" }
      ],
      "tags": ["string"]
    }
  ],
  "has_more": true
}
```
**Field Definitions**
- `thumb.type`: `image`.
- `sent_invites[].status`: `pending`, `accepted`, `declined`, `viewed`.

### `GET /events/{event_id}`
**Purpose:** Event detail.  
**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "id": "string",
    "slug": "string",
    "type": {
      "id": "string",
      "name": "string",
      "slug": "string",
      "description": "string",
      "icon": "string?",
      "color": "#RRGGBB?"
    },
    "title": "string",
    "content": "string",
    "venue": {
      "id": "string",
      "display_name": "string",
      "tagline": "string?",
      "hero_image_url": "string?",
      "logo_url": "string?",
      "taxonomy_terms": [{ "type": "string", "value": "string" }]
    },
    "latitude": 0.0,
    "longitude": 0.0,
    "thumb": { "type": "image", "data": { "url": "string" } },
    "date_time_start": "2025-01-01T00:00:00Z",
    "date_time_end": "2025-01-01T00:00:00Z?",
    "tags": ["string"],
    "artists": [
      { "id": "string", "display_name": "string", "avatar_url": "string?", "highlight": false, "genres": ["string"] }
    ],
    "is_confirmed": false,
    "total_confirmed": 0,
    "received_invites": [
      {
        "id": "string",
        "event_id": "string",
        "event_name": "string",
        "event_date": "2025-01-01T00:00:00Z",
        "event_image_url": "string",
        "location": "string",
        "host_name": "string",
        "message": "string",
        "tags": ["string"],
        "inviter_name": "string?",
        "inviter_avatar_url": "string?",
        "additional_inviters": ["string"]
      }
    ],
    "sent_invites": [
      {
        "friend": { "id": "string", "display_name": "string", "avatar_url": "string?" },
        "status": "pending|accepted|declined|viewed",
        "sent_at": "2025-01-01T00:00:00Z",
        "responded_at": "2025-01-01T00:00:00Z?"
      }
    ],
    "friends_going": [
      { "id": "string", "display_name": "string", "avatar_url": "string?" }
    ]
  }
}
```
**Field Definitions**
- `thumb.type`: `image`.
- `sent_invites[].status`: `pending`, `accepted`, `declined`, `viewed`.

### `POST /events/{event_id}/check-in`
**Purpose:** Presence confirmation.  
**Response:**
```json
{
  "tenant_id": "string",
  "event_id": "string",
  "presence_status": "confirmed|no_show",
  "check_in_method": "geofence|qr|staff_manual",
  "confirmed_at": "2025-01-01T00:00:00Z"
}
```
**Field Definitions**
- `presence_status`: `confirmed`, `no_show`.
- `check_in_method`: `geofence`, `qr`, `staff_manual`.

---

## 5) Map + POIs

### `GET /map/pois/stream` (SSE)
**Purpose:** Stream POI changes for the active map viewport/filters.  
**Request (query):** same as `/map/pois` (`viewport`, `categories[]`, `tags[]`, `taxonomy[]`, `search`, `origin_lat`, `origin_lng`, `max_distance_meters`).  
**Event types:**
- `poi.created` (new POI matches filters)
- `poi.updated` (fields changed)
- `poi.deleted` (POI removed)
**Payload (minimum):**
```json
{ "ref_type": "event|account_profile|static", "ref_id": "string", "type": "poi.created|poi.updated|poi.deleted", "updated_at": "2025-01-01T00:00:00Z" }
```
**MVP note:** SSE is deferred; keep the contract documented but do not implement for V1 unless polling proves insufficient.

### `GET /map/pois`
**Purpose:** Map POIs from projection (`map_pois`).  
**Request (query):**
```json
{
  "ne_lat": 0.0,
  "ne_lng": 0.0,
  "sw_lat": 0.0,
  "sw_lng": 0.0,
  "origin_lat": 0.0,
  "origin_lng": 0.0,
  "max_distance_meters": 100000,
  "categories": ["culture"],
  "tags": ["string"],
  "taxonomy": ["type:value"],
  "search": "string?",
  "sort": "priority|distance|time_to_event",
  "stack_key": "string?"
}
```
**Field Definitions**
- `categories[]`: `culture`, `beach`, `nature`, `historic`, `restaurant`.
- `sort`: `priority`, `distance`, `time_to_event`.
- `taxonomy[]`: `type:value` tokens (e.g., `cuisine:italian`).

**Notes:**
- Pagination is page-based elsewhere; map queries rely on viewport + radius rather than explicit item limits.
- `tags[]` and `taxonomy[]` are **filter-only** and are not returned in the `/map/pois` payload.
- Response includes `server_time` and the echoed `bounds` for client cache + delta logic.
- Day-based event window filtering uses the **user profile timezone**.
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "server_time": "2025-01-01T00:00:00Z",
  "bounds": { "ne_lat": 0.0, "ne_lng": 0.0, "sw_lat": 0.0, "sw_lng": 0.0 },
  "stacks": [
    {
      "stack_key": "string",
      "center": { "lat": 0.0, "lng": 0.0 },
      "stack_count": 1,
      "top_poi": {
        "ref_type": "event|account_profile|static",
        "ref_id": "string",
        "category": "culture|beach|nature|historic|restaurant",
        "location": { "lat": 0.0, "lng": 0.0 },
        "priority": 0,
        "updated_at": "2025-01-01T00:00:00Z",
        "distance_meters": 0
      }
    }
  ]
}
```
**Stack expansion:** When `stack_key` is provided, each stack includes `items[]` (ordered by priority + stable tiebreaker) with the same minimal POI fields as `top_poi`.
**Field Definitions**
- `ref_type`: `event`, `account_profile`, `static`.
- `category`: `culture`, `beach`, `nature`, `historic`, `restaurant`.
- `stack_key`: required for every stack, even when `stack_count = 1`.
- `top_poi.updated_at`: required for polling cache validation.

### `GET /map/near`
**Purpose:** Distance-ordered card list for POIs (paginated, richer payload).  
**Request (query):**
```json
{
  "origin_lat": 0.0,
  "origin_lng": 0.0,
  "max_distance_meters": 100000,
  "categories": ["culture"],
  "tags": ["string"],
  "taxonomy": ["type:value"],
  "search": "string?",
  "page": 1,
  "page_size": 10
}
```
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "page": 1,
  "page_size": 10,
  "has_more": false,
  "items": [
    {
      "ref_type": "event|account_profile|static",
      "ref_id": "string",
      "ref_slug": "string",
      "ref_path": "/event/sample-event",
      "title": "string",
      "subtitle": "string?",
      "category": "culture|beach|nature|historic|restaurant",
      "location": { "lat": 0.0, "lng": 0.0 },
      "distance_meters": 0,
      "updated_at": "2025-01-01T00:00:00Z",
      "time_start": "2025-01-01T00:00:00Z",
      "time_end": "2025-01-01T03:00:00Z",
      "avatar_url": "string?",
      "cover_url": "string?",
      "badge": "string?",
      "tags": ["string"],
      "taxonomy_terms": [{ "type": "string", "value": "string" }]
    }
  ]
}
```
**Field Definitions**
- `ref_path`: `/{ref_type}/{ref_slug}` (slugs are unique per model only).
- `taxonomy_terms[]`: typed pairs `{type, value}` attached to the POI for filtering.

### `GET /map/filters`
**Purpose:** Server-defined filter catalog.  
**Request (query params):** Same scoping filters as `/map/pois` (`ne_lat`, `ne_lng`, `sw_lat`, `sw_lng`, `origin_lat`, `origin_lng`, `max_distance_meters`, `categories[]`, `tags[]`, `taxonomy[]`, `search`).  
**Response:**
```json
{
  "tenant_id": "string",
  "categories": [
    { "key": "culture", "label": "string", "count": 0 }
  ],
  "tags": [
    { "key": "string", "label": "string", "count": 0 }
  ],
  "taxonomy_terms": [
    { "type": "string", "value": "string", "label": "string", "count": 0 }
  ]
}
```
**Field Definitions**
- `categories[].key`: `culture`, `beach`, `nature`, `historic`, `restaurant`.
- `taxonomy_terms[].type`: taxonomy group slug (e.g., `cuisine`, `music_genre`, `vibe`).

---

## 6) Push

### `POST /push/register`
**Purpose:** Register device token.  
**Request:**
```json
{ "device_id": "string", "platform": "ios|android|web", "push_token": "string" }
```
**Field Definitions**
- `platform`: `ios`, `android`, `web`.
**Implementation Notes (Multi-device):**
- A single user may register multiple devices. Store tokens per `device_id` (upsert by `device_id`).
- Do **not** overwrite other devices when a new device registers.
**Notification Targeting (MVP):**
- **Event audience:** use server-side fan-out to all users with confirmed presence for the event.
- **Favorites audience:** use server-side fan-out to users who favorited the account/artist/venue.
- **Invites:** direct device push to recipient users; do not rely on event topics.
**User Preferences (MVP):**
- Users receive all push types by default. Preference filtering is deferred to later versions.
**Future (Account Profile push):**
- Account profiles can target audiences they own (e.g., their followers/favorites, confirmed attendees) via server-side fan-out with permission checks.

**Response:**
```json
{ "tenant_id": "string", "ok": true }
```

---

## 7) Tenant/Admin Area (Authenticated)

**Admin routing:** tenant management endpoints live under **tenant scope** (`/api/v1/*`) and are restricted to landlord users via abilities. Landlord/global routes remain under `/admin/api/v1/*`.

### Account + Account Profile Data Strategy (MVP Decision)
- **Account** stays generic (core boilerplate model) and is the permission boundary.
- **Account Profile** is a first-class domain model, stored separately and linked **1:1** to `account_id`.
- **Organization** is optional and groups accounts belonging to the same real‑world entity (tenant, sponsor, hotel group). Most accounts will have **no org**.
- **Ownership State** is a single conceptual flag on Account: `tenant_owned`, `unmanaged`, `user_owned`.  
  **MVP note:** derived (not required in payload/response); unmanaged accounts are standalone (no org).
- **Discovery** should be served by a MongoDB aggregation that joins Account + Account Profile and returns a **Discovery DTO** (not the core models), enabling filters (tags/type/search) without polluting the base Account.
- **Auth Policy (Upstream):** wildcard (`*`) abilities are prohibited for tenant/app tokens; abilities must be explicit. This must be implemented in the upstream Laravel core and then merged into this project.
- **Project policy:** the `AccountProfile` model is implemented **in this project** (not upstream boilerplate) to avoid coupling unrelated boilerplate consumers. The model remains a generic 1:1 identity unit under Account, but contracts and behavior are owned here.
- **Project routes (explicit instruction):** create project-specific route files (e.g., `routes/api/project_api_v1.php`) and register only those for the project runtime. Do **not** expose boilerplate CRUD routes in the project API surface.

### `GET /api/v1/organizations`
**Purpose:** List organizations (grouping only).  
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "slug": "string?",
      "description": "string?",
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `POST /api/v1/organizations`
**Purpose:** Create organization.  
**Request (body):**
```json
{
  "name": "string",
  "description": "string?"
}
```
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string?",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `GET /api/v1/organizations/{organization_id}`
**Purpose:** Organization detail.  
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string?",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /api/v1/organizations/{organization_id}`
**Purpose:** Update organization (MVP: name/description only).  
**Request (body):**
```json
{
  "name": "string?",
  "description": "string?"
}
```
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string?",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `DELETE /api/v1/organizations/{organization_id}`
**Purpose:** Soft delete organization.  
**Response:**
```json
{}
```

### `POST /api/v1/organizations/{organization_id}/restore`
**Purpose:** Restore organization.  
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string?",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": null
  }
}
```

### `POST /api/v1/organizations/{organization_id}/force_delete`
**Purpose:** Force delete organization.  
**Response:**
```json
{}
```

### `GET /api/v1/accounts`
**Purpose:** List accounts (including unmanaged).  
**Pagination:** Page-based (`page`, `per_page`); no cursor.
**Query Params:** `ownership_state` (optional): `tenant_owned|unmanaged|user_owned`.
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "slug": "string",
      "document": {
        "type": "cpf|cnpj",
        "number": "string"
      },
      "ownership_state": "tenant_owned|unmanaged|user_owned",
      "organization_id": "string?",
      "updated_at": "2025-01-01T00:00:00Z",
      "created_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```
**Notes:** `ownership_state` is derived from account ownership invariants and is required in read responses.

### `POST /api/v1/accounts`
**Purpose:** Create account.  
**Request (body):**
```json
{
  "name": "string",
  "document": {
    "type": "cpf|cnpj",
    "number": "string"
  },
  "ownership_state": "tenant_owned|unmanaged"
}
```
**Field Definitions**
- `document.type`: `cpf`, `cnpj`.
- `ownership_state` (admin create): `tenant_owned`, `unmanaged`.
  - `user_owned` is not accepted in this admin create flow.

**Response:**
```json
{
  "data": {
    "account": {
      "id": "string",
      "name": "string",
      "slug": "string",
      "document": {
        "type": "cpf|cnpj",
        "number": "string"
      },
      "ownership_state": "tenant_owned|unmanaged|user_owned",
      "organization_id": "string?",
      "updated_at": "2025-01-01T00:00:00Z",
      "created_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    },
    "role": {
      "id": "string",
      "name": "Admin",
      "slug": "admin",
      "permissions": ["*"]
    }
  }
}
```
- `document.type`: `cpf`, `cnpj`.

### `GET /api/v1/accounts/{account_slug}`
**Purpose:** Fetch account detail by slug.  
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "document": {
      "type": "cpf|cnpj",
      "number": "string"
    },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "organization_id": "string?",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /api/v1/accounts/{account_slug}`
**Purpose:** Update account details.  
**Request (body):** same fields as create (partial).  
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "document": {
      "type": "cpf|cnpj",
      "number": "string"
    },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "organization_id": "string?",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```
- `document.type`: `cpf`, `cnpj`.

### `DELETE /api/v1/accounts/{account_slug}`
**Purpose:** Soft delete account.  
**Response:**
```json
{}
```

### `POST /api/v1/accounts/{account_slug}/restore`
**Purpose:** Restore account.  
**Response:**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "document": {
      "type": "cpf|cnpj",
      "number": "string"
    },
    "organization_id": "string?",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": null
  }
}
```

### `POST /api/v1/accounts/{account_slug}/force_delete`
**Purpose:** Force delete account.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/account_profile_types`
**Purpose:** List account profile types (registry).  
**Response:**
```json
{
  "data": [
    {
      "type": "string",
      "label": "string",
      "map_category": "string",
      "allowed_taxonomies": ["string"],
      "capabilities": {
        "is_favoritable": true,
        "is_poi_enabled": false
      }
    }
  ]
}
```

### `POST /admin/api/v1/account_profile_types`
**Purpose:** Create account profile type.  
**Request (body):**
```json
{
  "type": "string",
  "label": "string",
  "map_category": "string?",
  "allowed_taxonomies": ["string"],
  "capabilities": {
    "is_favoritable": true,
    "is_poi_enabled": false,
    "has_bio": true,
    "has_taxonomies": true,
    "has_avatar": true,
    "has_cover": true,
    "has_events": true
  }
}
```
**Response:** same as GET item.

### `PATCH /admin/api/v1/account_profile_types/{profile_type}`
**Purpose:** Update account profile type.  
**Request (body):** same as create (partial).  
**Response:** same as GET item.
**Notes:**
- `type` is mutable on edit (slug rename).
- When `type` changes, backend must enforce uniqueness and propagate `profile_type` to dependent `account_profiles`.
- When `type` changes, backend must also update dependent map projection category for `ref_type=account_profile` POIs.

### `DELETE /admin/api/v1/account_profile_types/{profile_type}`
**Purpose:** Delete account profile type.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/static_profile_types`
**Purpose:** List static profile types (registry).  
**Response:**
```json
{
  "data": [
    {
      "type": "string",
      "label": "string",
      "allowed_taxonomies": ["string"],
      "capabilities": {
        "is_poi_enabled": true,
        "has_bio": true,
        "has_taxonomies": true,
        "has_avatar": true,
        "has_cover": true,
        "has_content": true
      }
    }
  ]
}
```

### `POST /admin/api/v1/static_profile_types`
**Purpose:** Create static profile type.  
**Request (body):**
```json
{
  "type": "string",
  "label": "string",
  "allowed_taxonomies": ["string"],
  "capabilities": {
    "is_poi_enabled": true,
    "has_bio": true,
    "has_taxonomies": true,
    "has_avatar": true,
    "has_cover": true,
    "has_content": true
  }
}
```
**Response:** same as GET item.

### `PATCH /admin/api/v1/static_profile_types/{profile_type}`
**Purpose:** Update static profile type.  
**Request (body):** same as create (partial).  
**Response:** same as GET item.
**Notes:**
- `type` is mutable on edit (slug rename).
- When `type` changes, backend must enforce uniqueness and propagate `profile_type` to dependent `static_assets`.
- When `type` changes, backend must update dependent map projection category for `ref_type=static` POIs using the resolved `map_category`.

**Static profile type note:** `map_category` is the source of truth for static asset map projection (`map_pois.category`).

### `DELETE /admin/api/v1/static_profile_types/{profile_type}`
**Purpose:** Delete static profile type.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/taxonomies`
**Purpose:** List taxonomies (Account Profiles + Static Assets + Events).  
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "slug": "string",
      "name": "string",
      "applies_to": ["account_profile", "static_asset", "event"],
      "icon": "mode_subscription",
      "color": "#FFAA00"
    }
  ]
}
```

### `POST /admin/api/v1/taxonomies`
**Purpose:** Create a taxonomy.  
**Request (body):**
```json
{
  "slug": "string",
  "name": "string",
  "applies_to": ["account_profile", "static_asset", "event"],
  "icon": "mode_subscription",
  "color": "#FFAA00"
}
```
**Response:**
```json
{
  "data": {
    "id": "string",
    "slug": "string",
    "name": "string",
    "applies_to": ["account_profile", "static_asset", "event"],
    "icon": "mode_subscription",
    "color": "#FFAA00"
  }
}
```

### `PATCH /admin/api/v1/taxonomies/{taxonomy_id}`
**Purpose:** Update a taxonomy.  
**Request (body):**
```json
{
  "slug": "string?",
  "name": "string?",
  "applies_to": ["account_profile", "static_asset", "event"],
  "icon": "mode_subscription",
  "color": "#FFAA00"
}
```
**Response:**
```json
{
  "data": {
    "id": "string",
    "slug": "string",
    "name": "string",
    "applies_to": ["account_profile", "static_asset", "event"],
    "icon": "mode_subscription",
    "color": "#FFAA00"
  }
}
```

### `DELETE /admin/api/v1/taxonomies/{taxonomy_id}`
**Purpose:** Delete a taxonomy.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/taxonomies/{taxonomy_id}/terms`
**Purpose:** List taxonomy terms.  
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "taxonomy_id": "string",
      "slug": "string",
      "name": "string"
    }
  ]
}
```

### `POST /admin/api/v1/taxonomies/{taxonomy_id}/terms`
**Purpose:** Create a taxonomy term.  
**Request (body):**
```json
{
  "slug": "string",
  "name": "string"
}
```
**Response:**
```json
{
  "data": {
    "id": "string",
    "taxonomy_id": "string",
    "slug": "string",
    "name": "string"
  }
}
```

### `PATCH /admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}`
**Purpose:** Update a taxonomy term.  
**Request (body):**
```json
{
  "slug": "string?",
  "name": "string?"
}
```
**Response:**
```json
{
  "data": {
    "id": "string",
    "taxonomy_id": "string",
    "slug": "string",
    "name": "string"
  }
}
```

### `DELETE /admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}`
**Purpose:** Delete a taxonomy term.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/account_profiles`
**Purpose:** List account profiles (filterable by `account_id`).  
**Query Params:** `account_id` (optional), `ownership_state` (optional): `tenant_owned|unmanaged|user_owned`.
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "bio": "string?",
      "taxonomy_terms": [
        { "type": "string", "value": "string" }
      ],
      "location": { "lat": 0.0, "lng": 0.0 },
      "ownership_state": "tenant_owned|unmanaged|user_owned",
      "updated_at": "2025-01-01T00:00:00Z",
      "created_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `POST /admin/api/v1/account_profiles`
**Purpose:** Create account profile.  
**Request (body):**
```json
{
  "account_id": "string",
  "profile_type": "string",
  "display_name": "string",
  "location": { "lat": 0.0, "lng": 0.0 },
  "taxonomy_terms": [{ "type": "string", "value": "string" }],
  "bio": "string?",
  "avatar_url": "string?",
  "cover_url": "string?"
}
```
**Notes:** `location` is **required** when the registry marks `profile_type` as `is_poi_enabled=true`.
**Response:**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `GET /admin/api/v1/account_profiles/{account_profile_id}`
**Purpose:** Account profile detail.  
**Response:**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /admin/api/v1/account_profiles/{account_profile_id}`
**Purpose:** Update account profile.  
**Request (body):** same as create (partial).  
**Notes:**
- `slug` is mutable on edit and must pass slug validation (`^[a-z0-9]+(?:[-_][a-z0-9]+)*$`).
- Backend enforces slug uniqueness on update and returns field-level validation error (`slug`) on conflicts.
**Response:**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `DELETE /admin/api/v1/account_profiles/{account_profile_id}`
**Purpose:** Soft delete account profile.  
**Response:**
```json
{}
```

### `POST /admin/api/v1/account_profiles/{account_profile_id}/restore`
**Purpose:** Restore account profile.  
**Response:**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z",
    "deleted_at": null
  }
}
```

### `POST /admin/api/v1/account_profiles/{account_profile_id}/force_delete`
**Purpose:** Force delete account profile.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/account_profiles/geo`
**Purpose:** Geo search for POI-enabled profiles.  
**Query Params:** `origin_lat`, `origin_lng`, `max_distance_meters`, `profile_type`, `limit`.  
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "bio": "string?",
      "taxonomy_terms": [
        { "type": "string", "value": "string" }
      ],
      "location": { "lat": 0.0, "lng": 0.0 },
      "distance_meters": 0.0,
      "updated_at": "2025-01-01T00:00:00Z",
      "created_at": "2025-01-01T00:00:00Z"
    }
  ]
}
```

### `GET /admin/api/v1/static_assets`
**Purpose:** List Static Assets (tenant-admin).  
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "bio": "string?",
      "content": "string?",
      "tags": ["string"],
      "categories": ["string"],
      "taxonomy_terms": [{ "type": "string", "value": "string" }],
      "location": { "lat": 0.0, "lng": 0.0 },
      "is_active": true,
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `GET /admin/api/v1/static_assets/{asset_id}`
**Purpose:** Fetch a single Static Asset by id (tenant-admin).  
**Response:**
```json
{
  "data": {
    "id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "content": "string?",
    "tags": ["string"],
    "categories": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "location": { "lat": 0.0, "lng": 0.0 },
    "is_active": true,
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `POST /admin/api/v1/static_assets`
**Purpose:** Create Static Asset (tenant-admin).  
**Request (body):**
```json
{
  "profile_type": "string",
  "display_name": "string",
  "location": { "lat": 0.0, "lng": 0.0 },
  "taxonomy_terms": [{ "type": "string", "value": "string" }],
  "tags": ["string"],
  "bio": "string?",
  "content": "string?",
  "avatar_url": "string?",
  "cover_url": "string?",
  "avatar": "file?",
  "cover": "file?"
}
```
**Notes:** `slug` is backend-generated and should not be sent by clients. `categories` and `is_active` are still accepted by backend validation for backward compatibility, but tenant-admin forms no longer send them.

**Response:**
```json
{
  "data": {
    "id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "content": "string?",
    "tags": ["string"],
    "categories": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "location": { "lat": 0.0, "lng": 0.0 },
    "is_active": true,
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /admin/api/v1/static_assets/{asset_id}`
**Purpose:** Update Static Asset (tenant-admin).  
**Request (body):** same fields as create (partial).  
**Compatibility note:** `categories` and `is_active` remain accepted for compatibility only.
**Response:**
```json
{
  "data": {
    "id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "content": "string?",
    "tags": ["string"],
    "categories": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "location": { "lat": 0.0, "lng": 0.0 },
    "is_active": true,
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `POST /admin/api/v1/static_assets/{asset_id}/restore`
**Purpose:** Restore Static Asset (tenant-admin).  

### `DELETE /admin/api/v1/static_assets/{asset_id}/force_delete`
**Purpose:** Force delete Static Asset (tenant-admin).  

### `GET /api/v1/static_assets/{asset_ref}`
**Purpose:** Public read of a static asset page (tenant scope).  
**Auth:** Requires tenant-authenticated user (account users allowed).  
**Notes:** `asset_ref` accepts either the static asset id or the slug (POI refs use id).  
**Response:** same schema as `GET /admin/api/v1/static_assets/{asset_id}` (without `deleted_at` when not archived).

### `GET /events`
**Purpose:** List events (tenant scope).  
**Note:** Event geo is derived from the venue profile location; events do not carry a standalone `location` field.  
**Response:**
```json
{
  "tenant_id": "string",
  "data": [
    {
      "event_id": "string",
      "title": "string",
      "start_at": "2025-01-01T00:00:00Z",
      "end_at": "2025-01-01T00:00:00Z",
      "venue_account_id": "string",
      "artist_account_ids": ["string"],
      "updated_at": "2025-01-01T00:00:00Z",
      "created_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `POST /events`
**Purpose:** Create event.  
**Request (body):**
```json
{
  "title": "string",
  "start_at": "2025-01-01T00:00:00Z",
  "end_at": "2025-01-01T00:00:00Z",
  "venue_account_id": "string",
  "artist_account_ids": ["string"]
}
```
**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "event_id": "string",
    "title": "string",
    "start_at": "2025-01-01T00:00:00Z",
    "end_at": "2025-01-01T00:00:00Z",
    "venue_account_id": "string",
    "artist_account_ids": ["string"],
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /events/{event_id}`
**Purpose:** Update event.  
**Request (body):** same fields as create (partial).  
**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "event_id": "string",
    "title": "string",
    "start_at": "2025-01-01T00:00:00Z",
    "end_at": "2025-01-01T00:00:00Z",
    "venue_account_id": "string",
    "artist_account_ids": ["string"],
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z"
  }
}
```

### `POST /branding/update`
**Purpose:** Update tenant About/logo/icon/colors.  
**Request (multipart/form-data):**
- `theme_data_settings[brightness_default]` (`light|dark`)
- `theme_data_settings[primary_seed_color]` (`#RRGGBB`)
- `theme_data_settings[secondary_seed_color]` (`#RRGGBB`)
- `logo_settings[light_logo_uri]` (png file, optional)
- `logo_settings[dark_logo_uri]` (png file, optional)
- `logo_settings[light_icon_uri]` (png file, optional)
- `logo_settings[dark_icon_uri]` (png file, optional)
- `logo_settings[favicon_uri]` (ico file, optional)
- `logo_settings[pwa_icon]` (png file, optional; generates variants)

**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "theme_data_settings": {
      "primary_seed_color": "#RRGGBB",
      "secondary_seed_color": "#RRGGBB",
      "brightness_default": "light|dark"
    },
    "logo_settings": {
      "light_logo_uri": "string?",
      "dark_logo_uri": "string?",
      "light_icon_uri": "string?",
      "dark_icon_uri": "string?",
      "favicon_uri": "string?",
      "pwa_icon_uri": "string?"
    }
  }
}
```
**Field Definitions**
- `theme_data_settings.brightness_default`: `light`, `dark`.

---

## 8) Deferred (Do Not Implement in MVP)
- Account-profile invites are allowed in MVP (admin-assigned); invite metrics remain deferred post‑MVP.
- Sponsors POIs endpoints.
- `/initialize` (system bootstrap) is out of MVP scope; `/api/v1/environment` is the MVP entrypoint.

---

## Definition of Done
- [ ] All MVP endpoints are listed.
- [ ] Every endpoint has **response schema** documented.
- [ ] Response schemas match frontend needs (no missing fields).
- [ ] Every endpoint that accepts input has **payload schema** documented.
