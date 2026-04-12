# MVP Endpoint Contracts (Response Schemas)

**Status:** Draft  
**Purpose:** Define the MVP endpoints and the minimum response schemas required so Flutter can mock and backend can implement with the same contract.  
**Scope:** MVP only (account-profile invites are allowed; memberships are deferred; invite metrics **data capture is required**, dashboards are deferred; sponsor POIs are deferred).

---

## 0) Conventions
- Base prefix is `/api/v1` (router-mounted). Paths below omit the prefix unless explicitly stated.
- All responses include `tenant_id` when the request is tenant-scoped, except endpoints that explicitly document a tenant-isolated payload without `tenant_id` (for example `/favorites`).
- IDs are stable string IDs (Mongo ObjectId as string).
- Date/times are ISO 8601 (`YYYY-MM-DDTHH:mm:ssZ`).
- Home composition is client-side only. There is **no** aggregated `/home-overview` endpoint; use independent requests (e.g., `/invites`, `/agenda`, `/account_profiles/discovery`, `/missions`, `/discover/people`, `/discover/curator-content`, `/map/pois`).
- Event list queries **must** treat “happening now” as part of the “upcoming” bucket.
- Pagination conventions (MVP): **all lists are page-based**.
  - Request: `page` (int, optional), `page_size` or `per_page` (int, optional).
  - Response: `has_more` (bool) for app feeds, or paginator fields for admin lists.
- Distance fields:
  - `distance_meters` is returned when the backend computes distance from an origin (see Map).
  - For non-map lists (agenda/home), include `distance_meters` only when requested; otherwise omit.
- Taxonomy terms are typed pairs: `{ "type": "string", "value": "string" }` (WordPress-style, multi-taxonomy per account profile).
- PATCH payload convention (MVP): use direct resource-shaped payloads (object/list) with partial-update semantics by field presence.
  - Fields omitted from payload remain unchanged.
  - `null` is explicit clear only for fields documented as nullable; `null` for non-nullable fields returns `422`.
  - Mixed set+clear operations in a single payload must be applied atomically.
  - Do not use envelope wrappers (for example `paths`) unless a specific endpoint contract explicitly documents an exception.
- API security hardening convention (MVP baseline):
  - Endpoints must be classified by protection level in implementation/docs:
    - `L1 Core`: low-risk/public/read-heavy routes.
    - `L2 Balanced`: default for most authenticated APIs and non-financial writes.
    - `L3 High Protection`: critical mutations (`purchase|reservation|check-in|auth recovery|admin-sensitive writes`).
  - Cloudflare is the edge protection layer (DDoS/WAF/bot/challenge/coarse IP throttling); Laravel is the source-of-truth layer for principal/account controls and mutation-safety guarantees.
  - `L3` mutation endpoints require `Idempotency-Key` + replay-window validation.
  - `L2` mutation endpoints require idempotency when duplicate side effects are possible.
  - Security rejections must be machine-readable and deterministic (`rate_limited|soft_blocked|hard_blocked|idempotency_missing|idempotency_replayed|idempotency_expired|idempotency_malformed`).
  - Rejection responses should include `retry_after` (when applicable), `correlation_id`, and `cf_ray_id` when Cloudflare provides one.
  - Response headers should include:
    - `X-Correlation-Id`
    - `X-Api-Security-Level`
    - `X-Api-Security-Label`
    - `X-Api-Security-Observe-Mode`
    - `X-CF-Ray-Id` when available
  - Requests must trust Cloudflare forwarding headers only through configured trusted proxy chain; direct-origin paths must be blocked in production.
  - Rollout control:
    - `observe_mode=true` logs violations without blocking requests.
    - enforce mode blocks according to level policy once observe metrics are acceptable.
  - Route/tenant/endpoint overrides may strengthen controls but must not weaken below global minimum policy.

---

## 1) Identity + Bootstrap

### `POST /anonymous/identities`
**Purpose:** Create or resume anonymous identity for app progressive-profiling flows.  
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
**Channel rule (V1):** web invite landing remains read-only and must not use this endpoint for invite conversion. Tenant-public web hard gates must hand off to app/open-store flows instead of minting web anonymous identity.

### `POST /deep-links/deferred/resolve`
**Purpose:** Resolve deferred deep-link first-open attribution from app-provided install metadata (Android MVP), returning deterministic routing outcome.
**Request (body):**
```json
{
  "platform": "android|ios",
  "install_referrer": "string?",
  "store_channel": "string?"
}
```
**Response:**
```json
{
  "data": {
    "status": "captured|not_captured",
    "code": "string|null",
    "target_path": "/invite?code=...|/",
    "store_channel": "string|null",
    "failure_reason": "string|null"
  }
}
```
**Field Definitions**
- `status`
  - `captured`: invite `code` was resolved and should route to invite flow.
  - `not_captured`: no invite `code` could be resolved; fallback route must be `/`.
- `failure_reason` (when `status=not_captured`)
  - `referrer_unavailable`
  - `code_missing`
  - `unsupported_platform` (iOS in V1 MVP)
  - `resolver_unavailable` (client-side fallback classification when resolver request fails)

**V1 scope note:** Android deferred install capture is required in MVP. iOS deferred install capture remains VNext and returns deterministic `not_captured` in V1.

### `GET /open-app` (web handoff route)
**Purpose:** Canonical web promotion/hard-gate handoff route; backend resolves tenant-dynamic store/open destination while preserving deterministic invite attribution rules.
**Request (query):**
- `path` (`/invite|/convites|/` + arbitrary incoming values normalized by backend policy)
- `code` (`string?`)
- `store_channel` (`string?`)
- `platform_target` (`android|ios?`) optional explicit override for web promotion surfaces that render separate store choices; when absent, backend may fall back to user-agent detection.

**Response:** `302` redirect (`Location` header) to:
- dynamic Android/iOS store URL with attribution payload when store target is configured; or
- deterministic in-domain fallback open target (`/invite?code=...` only for invite-landing context with valid `code`; otherwise `/`).

**Channel rule (V1):** Web tenant-public hard gates (`favorite`, `send_invite`, attendance boundary attempts) must resolve through this handoff route and must not continue through web auth/login conversion.

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
      "default_origin": {
        "lat": -20.0,
        "lng": -40.0,
        "label": "string?"
      },
      "radius": {
        "min_km": 1,
        "default_km": 5,
        "max_km": 50
      },
      "filters": [
        {
          "key": "culture",
          "label": "Cultura",
          "image_uri": "https://tenant.example.com/storage/tenants/tenant-a/map_ui/filters/culture.png"
        }
      ]
    }
  }
}
```
**Field Definitions**
- `type`: `landlord`, `tenant`.
- `theme_data_settings.brightness_default`: `light`, `dark`.
- `telemetry.trackers[].type`: `mixpanel`, `firebase`, `webhook`.
- `telemetry.location_freshness_minutes`: Integer minutes; defaults to 5 when omitted.
- `settings.map_ui.default_origin.lat`: Tenant default origin latitude used when user location is unavailable.
- `settings.map_ui.default_origin.lng`: Tenant default origin longitude used when user location is unavailable.
- `settings.map_ui.default_origin.label`: Optional human-friendly label for the default origin.
- `settings.map_ui.radius.min_km`: Minimum radius bound for map/agenda filters (km).
- `profile_types`: Tenant profile type registry entries used to drive profile UI + favorites.
- `settings.map_ui.radius.default_km`: Default radius for map/agenda filters (km).
- `settings.map_ui.radius.max_km`: Maximum radius bound for map/agenda filters (km).
- `settings.map_ui.filters[]`: Ordered map filter catalog used by tenant-admin and map filter discovery payload decoration.
- `settings.map_ui.filters[].key`: Stable category key aligned with `/map/filters.categories[].key`.
- `settings.map_ui.filters[].label`: Tenant-facing display label override for the filter key.
- `settings.map_ui.filters[].image_uri`: Optional image URL rendered in map filter button surfaces.

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

### `GET /favorites`
**Purpose:** Return user favorites with registry-backed snapshot ordering for Home and other account-profile contexts.  
**Request (query):**
```json
{
  "page": 1,
  "page_size": 20,
  "registry_key": "account_profile",
  "target_type": "account_profile"
}
```
**Response (minimum):**
```json
{
  "items": [
    {
      "favorite_id": "string",
      "registry_key": "account_profile",
      "target_type": "account_profile",
      "target_id": "string",
      "favorited_at": "2025-01-01T00:00:00Z",
      "target": {
        "id": "string",
        "slug": "string",
        "display_name": "string",
        "avatar_url": "string?"
      },
      "snapshot": {
        "next_event_occurrence_id": "string?",
        "next_event_occurrence_at": "2025-01-01T00:00:00Z?",
        "last_event_occurrence_at": "2025-01-01T00:00:00Z?"
      },
      "navigation": {
        "kind": "account_profile",
        "target_slug": "string"
      }
    }
  ],
  "has_more": false
}
```
**Notes:**
- Query filter by `registry_key` is optional.
- Ordering policy:
  - block A: `next_event_occurrence_at` ascending,
  - block B: `last_event_occurrence_at` descending when next is null,
  - block C: `favorited_at` descending when both event dates are null.
- Anonymous identities return `200` with `items=[]` and `has_more=false`.
- Snapshot persistence is tenant-isolated by database; payload intentionally omits `tenant_id`.
- Registry/migration guardrails:
  - `registry_key` is required snake_case.
  - `snapshot_collection` can be omitted (fallback `favoritable_snapshots`) or explicitly provided.
  - when explicitly provided, `snapshot_collection` must match `favoritable_{registry_key}_snapshots`.
  - multiple registries can share one collection only with common envelope fields (`registry_key`, `target_type`, `target_id`, `updated_at`).
  - default shared collection is forbidden when the registry declares non-default/specific indexes.

### `GET /account_profiles`
**Purpose:** Tenant-public discovery list of account profiles (paginated).  
**Request (query):**
```json
{
  "page": 1,
  "per_page": 30,
  "page_size": 30,
  "search": "string?",
  "profile_type": "string?",
  "filter": { "profile_type": "string?" }
}
```
**Response (minimum):**
```json
{
  "current_page": 1,
  "per_page": 30,
  "last_page": 1,
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "taxonomy_terms": [{ "type": "string", "value": "string" }],
      "location": { "lat": 0.0, "lng": 0.0 },
      "ownership_state": "tenant_owned|unmanaged|user_owned|null",
      "created_at": "2025-01-01T00:00:00Z?",
      "updated_at": "2025-01-01T00:00:00Z?"
    }
  ]
}
```
**Notes:**
- Backend always enforces:
  - `is_active = true`;
  - `profile_type` intersected with tenant registry where `capabilities.is_favoritable=true`;
  - visibility boundary (`visibility='public'` only).
- Client query params cannot override those constraints.

### `GET /account_profiles/near`
**Purpose:** Tenant-public distance-ordered list for Discovery `Perto de você`.  
**Request (query):**
```json
{
  "origin_lat": 0.0,
  "origin_lng": 0.0,
  "max_distance_meters": 100000,
  "search": "string?",
  "profile_type": "string?",
  "filter": { "profile_type": "string?" },
  "page": 1,
  "page_size": 10
}
```
**Response (minimum):**
```json
{
  "page": 1,
  "page_size": 10,
  "has_more": false,
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "taxonomy_terms": [{ "type": "string", "value": "string" }],
      "location": { "lat": 0.0, "lng": 0.0 },
      "ownership_state": "tenant_owned|unmanaged|user_owned|null",
      "distance_meters": 0
    }
  ]
}
```
**Notes:**
- Backend always enforces:
  - `profile_type` intersected with tenant registry where `capabilities.is_favoritable=true`;
  - `profile_type` intersected with tenant registry where `capabilities.is_poi_enabled=true`;
  - `visibility='public'` boundary;
  - nearest-first ordering by computed `distance_meters`.
- Client filters are narrowing-only (cannot broaden beyond backend policy).

### `GET /account_profiles/{account_profile_slug}`
**Purpose:** Tenant-public direct detail lookup for account profile consumer routes such as `/parceiro/:slug`.  
**Request (path):**
- `account_profile_slug` (string, exact slug lookup)
**Response (minimum):**
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
    "content": "object|array|string|null",
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "agenda_occurrences": [
      {
        "event_id": "string",
        "occurrence_id": "string",
        "slug": "string",
        "title": "string",
        "date_time_start": "string",
        "date_time_end": "string|null",
        "location": { "name": "string?" },
        "venue": { "id": "string", "display_name": "string" },
        "artists": [{ "id": "string", "display_name": "string" }],
        "thumb": { "data": { "url": "string?" } }
      }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned|null"
  }
}
```
**Notes:**
- Backend performs exact slug lookup; client must not emulate detail by paging/scanning `/account_profiles`.
- `agenda_occurrences` is a query-only projection derived from future/live published event occurrences associated with the profile; it is returned ordered by next occurrence time and may contain repeated `event_id` values when `occurrence_id` differs.
- Backend always enforces:
  - `is_active = true`;
  - `profile_type` intersected with tenant registry where `capabilities.is_favoritable=true`;
  - `visibility='public'` only.
- Unknown, inactive, non-public, or non-favoritable slugs return `404`.

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
      "target_ref": {
        "event_id": "string",
        "occurrence_id": "string?"
      },
      "event_name": "string",
      "event_date": "2025-01-01T00:00:00Z",
      "event_image_url": "string",
      "location": "string",
      "host_name": "string",
      "message": "string",
      "tags": ["string"],
      "attendance_policy": "free_confirmation_only|paid_reservation_only|either",
      "inviter_candidates": [
        {
          "invite_id": "string",
          "inviter_principal": { "kind": "user|account_profile", "id": "string" },
          "display_name": "string?",
          "avatar_url": "string?",
          "status": "pending|accepted|declined|expired"
        }
      ],
      "social_proof": {
        "additional_inviter_count": 0
      }
    }
  ],
  "has_more": true
}
```
**Notes:**
- Feed is grouped by canonical invite target, not flat by invite edge.
- Native clients must use `inviter_candidates[].invite_id` for explicit inviter selection when more than one candidate exists for a target.
- Candidate identity must respect privacy policy; when identity is not allowed, backend should return anonymized summaries/counts instead of raw profile fields.
- `occurrence_id` is required whenever runtime invite/attendance actions are occurrence-resolved; `null` is allowed only for single-occurrence or intentionally event-scoped compatibility flows.
- `message` is optional author input. Feed/share payloads may return `""` when no custom invite message was provided.
- VNext roadmap: add cursor pagination for deep invite feed scrolling while preserving page-based compatibility in MVP clients.

### `POST /invites`
**Purpose:** Create direct invites for one or more recipients from the native app.  
**Request (body):**
```json
{
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "account_profile_id": "string?",
  "recipients": [
    {
      "receiver_user_id": "string?",
      "contact_hash": "string?"
    }
  ],
  "message": "string?"
}
```
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "created": [
    { "invite_id": "string", "receiver_user_id": "string?" }
  ],
  "already_invited": [
    { "receiver_user_id": "string?" }
  ],
  "blocked": [
    { "receiver_user_id": "string?", "reason": "suppressed" }
  ]
}
```
**Notes:**
- `account_profile_id` is required when the sender is acting as `inviter_principal.kind = account_profile`.
- Each recipient must provide either `receiver_user_id` or `contact_hash`.
- Duplicate invite prevention follows the canonical uniqueness key `(tenant_id, event_id, occurrence_id | null, receiver_user_id, inviter_principal.kind, inviter_principal.id)` and returns `already_invited` instead of creating a new edge.
- Sender/global quota rejections must return deterministic `429` payloads:
```json
{
  "status": "rejected",
  "code": "rate_limited",
  "message": "string",
  "payload": {
    "limit_key": "max_invites_per_day_per_user_actor",
    "scope": "user_actor",
    "max_allowed": 100,
    "current_count": 100,
    "window": "day",
    "reset_at": "2025-01-01T00:00:00Z?"
  }
}
```

### `POST /invites/{invite_id}/accept`
**Purpose:** Accept the selected direct invite from native app (authenticated or anonymous device-bound identity).  
**Request (body):**
```json
{
  "idempotency_key": "string?"
}
```
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "invite_id": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "status": "accepted|already_accepted|expired",
  "credited_acceptance": true,
  "attendance_policy": "free_confirmation_only|paid_reservation_only|either",
  "next_step": "none|free_confirmation_created|reservation_required|commitment_choice_required|open_app_to_continue",
  "superseded_invite_ids": ["string"],
  "accepted_at": "2025-01-01T00:00:00Z?"
}
```
**Notes:**
- This endpoint is the canonical app mutation path after explicit inviter selection.
- Anonymous app identities are allowed in V1 progressive profiling and must preserve inviter attribution semantics.
- Backend must supersede competing pending invites for the same `(receiver,target_ref)` when acceptance succeeds.
- Superseded invites must use `status = superseded` with `supersession_reason = other_invite_credited`.
- `next_step` is the canonical contract field for post-acceptance follow-up semantics across native acceptance and future authenticated workspace continuations.

### `POST /invites/{invite_id}/decline`
**Purpose:** Decline the selected direct invite from native app.  
**Request (body):**
```json
{
  "idempotency_key": "string?"
}
```
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "invite_id": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "status": "declined|already_declined|expired",
  "group_has_other_pending": true,
  "declined_at": "2025-01-01T00:00:00Z?"
}
```
**Notes:**
- Declining one invite edge does not implicitly decline other inviter candidates for the same target.

### `GET /invites/settings`
**Purpose:** Invite quotas + UX messaging limits.  
**Response:**
```json
{
  "tenant_id": "string",
  "limits": {
    "max_invites_per_day_per_user_actor": 100,
    "max_share_codes_per_day_per_user_actor": 30
  },
  "cooldowns": {
    "share_code_cooldown_seconds": 60
  },
  "reset_at": "2025-01-01T00:00:00Z?",
  "over_quota_message": "string?"
}
```
**MVP note:** invite-send cap is `max_invites_per_day_per_user_actor`; event/account/receiver invite-send limits are deferred to VNext.

### `POST /invites/share`
**Purpose:** Create share code for invite attribution.  
**Request (body):**
```json
{
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "account_profile_id": "string?"
}
```
**Response (minimum):**
```json
{
  "tenant_id": "string",
  "code": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "inviter_principal": { "kind": "user|account_profile", "id": "string" }
}
```
**Eligibility Rules (MVP):**
- **User invites:** only to imported contacts (hashed) or existing app users.
- **Account Profile invites:** may target followers/favorites for broader reach; direct user targeting allowed as needed.
- When `inviter_principal.kind = account_profile`, `account_profile_id` is **required** and must belong to the inviter’s account/tenant context.
- Anti-spam rejections must return deterministic `429` payloads:
```json
{
  "status": "rejected",
  "code": "rate_limited|share_rate_limited",
  "message": "string",
  "payload": {
    "limit_key": "max_share_codes_per_day_per_user_actor|share_code_cooldown_seconds",
    "scope": "share_user_actor|share_target",
    "max_allowed": 30,
    "current_count": 30,
    "window": "day|cooldown",
    "reset_at": "2025-01-01T00:00:00Z?",
    "retry_after_seconds": 60
  }
}
```

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

### `GET /invites/share/{code}`
**Purpose:** Resolve invite landing preview context for a share code (`/invite?code=...`) before authentication.
**Auth:** Tenant domain resolution required; authenticated identity not required.
**Response:**
```json
{
  "tenant_id": "string",
  "code": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "inviter_principal": { "kind": "user|account_profile", "id": "string" },
  "invite": {
    "id": "string",
    "target_ref": {
      "event_id": "string",
      "occurrence_id": "string?"
    },
    "event_name": "string",
    "event_date": "2025-01-01T00:00:00Z",
    "event_image_url": "https://...",
    "location": "string",
    "host_name": "string",
    "message": "string",
    "tags": ["string"],
    "attendance_policy": "free_confirmation_only|paid_reservation_only|either",
    "inviter_candidates": [
      {
        "invite_id": "string",
        "inviter_principal": { "kind": "user|account_profile", "id": "string" },
        "display_name": "string",
        "avatar_url": "https://...?",
        "status": "pending"
      }
    ],
    "social_proof": { "additional_inviter_count": 0 }
  }
}
```
**Field Definitions**
- `inviter_principal.kind`: `user`, `account_profile`.
- `invite.attendance_policy`: `free_confirmation_only`, `paid_reservation_only`, `either`.
- `invite.inviter_candidates[].status`: `pending`.
- `invite.message` may be an empty string when the share/direct invite was created without a custom message.

**Not Found Contract**
```json
{
  "status": "rejected",
  "code": "invite_share_not_found",
  "message": "invite_share_not_found",
  "payload": {}
}
```
- Returned with `404` for missing, expired, or invalid share-code previews.

### `POST /invites/share/{code}/materialize`
**Purpose:** Optional authenticated continuation/pre-bind step that creates or reuses the canonical invite edge before rendering invite decision UI.  
**Request (headers/body):** Auth via Sanctum (**authenticated identity required**). Optional idempotency payload:
```json
{
  "idempotency_key": "string?"
}
```
**Response:**
```json
{
  "tenant_id": "string",
  "invite_id": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "inviter_principal": { "kind": "user|account_profile", "id": "string" },
  "status": "pending|accepted|declined|superseded|expired",
  "attendance_policy": "free_confirmation_only|paid_reservation_only|either",
  "credited_acceptance": false,
  "accepted_at": "2025-01-01T00:00:00Z?"
}
```
**Field Definitions**
- `inviter_principal.kind`: `user`, `account_profile`.
- `status`: current canonical invite-edge status for the authenticated user after share-code materialization.
- `credited_acceptance`: reflects the materialized edge state; it remains `false` while the invite is pending and only becomes `true` after standard `POST /invites/{invite_id}/accept`.

**Auth Error Contract**
```json
{
  "status": "rejected",
  "code": "auth_required",
  "message": "auth_required",
  "payload": {}
}
```
- Returned with `401` when caller is unauthenticated or authenticated as anonymous identity.

### `POST /invites/share/{code}/accept`
**Purpose:** Canonical anonymous-first share decision endpoint for app progressive profiling; resolves or materializes invite edge bound to `code` and performs acceptance atomically.  
**Auth:** Sanctum token required (registered or anonymous identity).  
**Request (headers/body):**
```json
{
  "idempotency_key": "string?"
}
```
**Response:**
```json
{
  "tenant_id": "string",
  "invite_id": "string",
  "target_ref": {
    "event_id": "string",
    "occurrence_id": "string?"
  },
  "inviter_principal": { "kind": "user|account_profile", "id": "string" },
  "status": "accepted|already_accepted|expired|superseded",
  "attendance_policy": "free_confirmation_only|paid_reservation_only|either",
  "credited_acceptance": true,
  "accepted_at": "2025-01-01T00:00:00Z?",
  "next_step": "none|free_confirmation_created|reservation_required|commitment_choice_required|open_app_to_continue",
  "superseded_invite_ids": ["string"]
}
```
**Field Definitions**
- `inviter_principal.kind`: `user`, `account_profile`.
- `status`: canonical acceptance result status for the resolved invite edge.
- `next_step`: `none`, `free_confirmation_created`, `reservation_required`, `commitment_choice_required`, `open_app_to_continue`.

**Requirement:** Invite share links must carry `code` as a GET parameter.
**Tracking Notes:**
- `share_visit` is tracked separately from invites; it does **not** count as an accepted invite.
- `POST /invites/share/{code}/materialize` only materializes attribution; it does **not** count as `invite_accepted`.
- Invite landing must resolve preview context via `GET /invites/share/{code}` and preserve original deep-link query (`/invite?code=...`) through store/app handoff.
- Store/open handoff targets must be resolved dynamically per tenant for Android+iOS; clients must not hardcode store URLs.
- Handoff target selection is deterministic and context-aware: preserve `/invite?code=...` only when current route context is invite landing (`/invite` or `/convites`) with valid `code`; all other contexts use canonical `/`.
- App anonymous flow accepts from preview using canonical `POST /invites/share/{code}/accept` (no forced pre-materialize step).
- First-open resolver must be deterministic: captured `code` routes to invite flow; unresolved capture routes to `/` and emits `app_deferred_deep_link_capture_failed` (`store_channel` when available).
- Materialized/inbox flows continue through canonical invite mutation endpoints `POST /invites/{invite_id}/accept|decline`.
- Web remains promotion/read-only in V1: no accept/decline mutations, no inbox browsing, no multi-inviter selector, no direct invite send, no presence confirmation, and no check-in.
- Web tenant-public hard/auth gates must not continue via web login; they must promote app handoff with `code` preservation.

### `POST /test-support/invites/bootstrap` (`stage` only, non-product)
**Purpose:** Provision deterministic invite fixtures for deployed compatibility tests against `stage`.  
**Auth:** Tenant resolution required plus dedicated test-support secret header. Must be unavailable outside `stage`.  
**Headers:** `X-Test-Support-Key: <stage-secret>`  
**Request (body):**
```json
{
  "run_id": "string",
  "scenario": "accept_pending|decline_pending|direct_confirmation_superseded|expired_share"
}
```
**Response (minimum):**
```json
{
  "run_id": "string",
  "tenant": {
    "slug": "guarappari",
    "tenant_url": "https://guarappari.belluga.app",
    "landlord_url": "https://belluga.app"
  },
  "mobile": {
    "app_domain_identifier": "com.guarappari.app"
  },
  "inviter": {
    "email": "string",
    "password": "string"
  },
  "invitee": {
    "email": "string",
    "password": "string"
  },
  "signup_candidate": {
    "name": "string",
    "email": "string",
    "password": "string"
  },
  "event_id": "string",
  "share_code": "string",
  "invite_url": "https://guarappari.belluga.app/invite?code=..."
}
```
**Contract Notes**
- Fixtures must be isolated by `run_id`.
- Invite behavior setup must use canonical invite services/contracts; bootstrap must not bypass the invite contract with raw direct-mutation shortcuts.
- This endpoint is internal test support and must never be enabled on `main`/production.

### `GET /test-support/invites/state/{run_id}` (`stage` only, non-product)
**Purpose:** Read deterministic invite fixture state after compatibility execution.  
**Auth:** Tenant resolution required plus dedicated test-support secret header.  
**Headers:** `X-Test-Support-Key: <stage-secret>`  
**Response (minimum):**
```json
{
  "run_id": "string",
  "scenario": "string",
  "event_id": "string",
  "share_code": "string",
  "invites": [
    {
      "invite_id": "string",
      "receiver_user_id": "string",
      "status": "pending|accepted|declined|superseded|expired",
      "credited_acceptance": true,
      "supersession_reason": "string?"
    }
  ]
}
```

### `POST /test-support/invites/cleanup` (`stage` only, non-product)
**Purpose:** Remove deterministic invite fixtures for a prior `run_id`.  
**Auth:** Tenant resolution required plus dedicated test-support secret header.  
**Headers:** `X-Test-Support-Key: <stage-secret>`  
**Request (body):**
```json
{
  "run_id": "string"
}
```
**Response (minimum):**
```json
{
  "run_id": "string",
  "deleted": true
}
```

#### Invite Flow & Friends Management (MVP)
- **Primary invite paths:**
- **Direct invite (existing user):** server creates a per-recipient invite and sends push. Source = `direct_invite`.
  - **Share link:** server issues a `code` via `POST /invites/share`; no per-recipient record until acceptance. Source = `share_url`.
- **Acceptance rules:** only one credited accepted invite per invitee per canonical target. Direct native acceptance requires explicit inviter selection through `invite_id`; share-code acceptance binds to the code’s inviter principal.
- **Tracking:** `share_visit` is analytics-only. `invite_sent` and `invite_accepted` should include a `source` (`direct_invite`, `share_url`). The project north-star metrics are `credited_invite_acceptances` and normalized `presences_confirmed` (successful free confirmations or paid reservations).
- **Contacts/Friends (MVP):** no reciprocal social graph or favorite-based visibility model is implemented on MVP invite flows. Hashed contact matches are the only discovery/targeting surface here; raw PII is never stored.
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
**Purpose:** Stream event occurrence deltas for active app filters (SSE).  
**Request (query):** filter set aligned with `/agenda` (`categories[]`, `tags[]`, `taxonomy[]`, `confirmed_only`, `origin_lat`, `origin_lng`, `max_distance_meters`).  
**Headers (optional):** `Last-Event-ID` to resume from the last received SSE cursor.  
**Event types:**
- `occurrence.created` (new occurrence matches filters)
- `occurrence.updated` (occurrence data changed)
- `occurrence.deleted` (occurrence removed, unpublished, or soft-deleted)
**Payload (minimum):**
```json
{
  "event_id": "string",
  "occurrence_id": "string",
  "type": "occurrence.created|occurrence.updated|occurrence.deleted",
  "updated_at": "2025-01-01T00:00:00Z"
}
```
**Resync behavior:** if the client connects without `Last-Event-ID`, or cursor is invalid/stale, refresh page 1 from `/agenda` as source of truth and continue stream from now.

### `GET /agenda`
**Purpose:** Paged agenda feed.  
**Request (query):**
```json
{
  "page": 1,
  "page_size": 10,
  "past_only": false,
  "live_now_only": false,
  "confirmed_only": false,
  "categories": ["string"],
  "tags": ["string"],
  "taxonomy": [{ "type": "string", "value": "string" }],
  "origin_lat": 0.0,
  "origin_lng": 0.0,
  "max_distance_meters": 100000
}
```
**Notes (mock behavior):**
- `past_only=false` returns upcoming events **plus** events happening now.
- `past_only=true` returns events that started before now **and are not happening now**.
- `live_now_only=true` returns only events happening now (`date_time_start <= now < date_time_end`).
- `live_now_only=true` and `past_only=true` is invalid and must return `422`.
- "Happening now" means `date_time_start <= now < date_time_end`. If `date_time_end` is missing, assume `date_time_start + 3h`.
- Sort order: upcoming/now ascending by `date_time_start`, past descending by `date_time_start`.
- Text search is not supported in MVP (`search` query parameter is rejected).
- Categories filter matches `type.slug` or event categories when available (case-insensitive).
- Tags filter matches any `tags[]` on the event (case-insensitive).
- Taxonomy filter matches slug pairs (`type`, `value`) against event/venue/artist taxonomy terms.
- `confirmed_only=true` returns only attendance-confirmed events for the current identity.
- `confirmed_only=true` + anonymous identity returns `200` with empty list and `has_more=false`.
- Client flows for agenda/search must resolve origin before requesting this endpoint (`user location` -> `settings.map_ui.default_origin`).
- Backend applies geo filtering using `origin_lat`/`origin_lng` + `max_distance_meters` bounded by tenant `map_ui.radius` limits.
- Exception: with `confirmed_only=true`, geo filters do not exclude items (origin may be used only to compute optional `distance_meters`).
- No unfiltered fallback list is applied when geo filters produce zero matches.

**Response (minimum):**
```json
{
  "tenant_id": "string",
  "items": [
    {
      "event_id": "string",
      "occurrence_id": "string?",
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
        "slug": "string?",
        "profile_type": "string?",
        "avatar_url": "string?",
        "tagline": "string?",
        "hero_image_url": "string?",
        "logo_url": "string?",
        "cover_url": "string?",
        "taxonomy_terms": [{ "type": "string", "value": "string", "name": "string?" }]
      },
      "latitude": 0.0,
      "longitude": 0.0,
      "thumb": { "type": "image", "data": { "url": "string" } },
      "date_time_start": "2025-01-01T00:00:00Z",
      "date_time_end": "2025-01-01T00:00:00Z?",
      "occurrences": [
        {
          "date_time_start": "2025-01-01T00:00:00Z",
          "date_time_end": "2025-01-01T00:00:00Z?"
        }
      ],
      "artists": [
        {
          "id": "string",
          "slug": "string?",
          "profile_type": "string?",
          "display_name": "string",
          "avatar_url": "string?",
          "cover_url": "string?",
          "highlight": false,
          "genres": ["string"],
          "taxonomy_terms": [{ "type": "string", "value": "string", "name": "string?" }]
        }
      ],
      "linked_account_profiles": [
        {
          "id": "string",
          "slug": "string?",
          "profile_type": "string",
          "display_name": "string",
          "avatar_url": "string?",
          "cover_url": "string?",
          "taxonomy_terms": [{ "type": "string", "value": "string", "name": "string?" }]
        }
      ],
      "created_by": {
        "type": "string",
        "id": "string"
      },
      "event_parties": [
        {
          "party_type": "string",
          "party_ref_id": "string",
          "permissions": { "can_edit": true },
          "metadata": {}
        }
      ],
      "capabilities": {
        "multiple_occurrences": {
          "enabled": false,
          "allow_multiple": false,
          "max_occurrences": null
        }
      },
      "tags": ["string"],
      "taxonomy_terms": [
        { "type": "string", "value": "string" }
      ]
    }
  ],
  "has_more": true
}
```
**Field Definitions**
- `thumb.type`: `image`.
- `event_parties[].permissions.can_edit`: `true|false`.

**Important boundary:** invite lifecycle fields are intentionally absent from Events payloads.  
Not returned by `/agenda` and `/events/{event_id}`:
- `received_invites`
- `sent_invites`
- `friends_going`
- `is_confirmed`
- `total_confirmed`

### `GET /events/{event_id}`
**Purpose:** Event detail.  
**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "event_id": "string",
    "occurrence_id": null,
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
      "slug": "string?",
      "profile_type": "string?",
      "avatar_url": "string?",
      "tagline": "string?",
      "hero_image_url": "string?",
      "logo_url": "string?",
      "cover_url": "string?",
      "taxonomy_terms": [{ "type": "string", "value": "string", "name": "string?" }]
    },
    "latitude": 0.0,
    "longitude": 0.0,
    "thumb": { "type": "image", "data": { "url": "string" } },
    "date_time_start": "2025-01-01T00:00:00Z",
    "date_time_end": "2025-01-01T00:00:00Z?",
    "occurrences": [
      {
        "date_time_start": "2025-01-01T00:00:00Z",
        "date_time_end": "2025-01-01T00:00:00Z?"
      }
    ],
    "artists": [
      {
        "id": "string",
        "slug": "string?",
        "profile_type": "string?",
        "display_name": "string",
        "avatar_url": "string?",
        "cover_url": "string?",
        "highlight": false,
        "genres": ["string"],
        "taxonomy_terms": [{ "type": "string", "value": "string", "name": "string?" }]
      }
    ],
    "linked_account_profiles": [
      {
        "id": "string",
        "slug": "string?",
        "profile_type": "string",
        "display_name": "string",
        "avatar_url": "string?",
        "cover_url": "string?",
        "taxonomy_terms": [{ "type": "string", "value": "string", "name": "string?" }]
      }
    ],
    "created_by": {
      "type": "string",
      "id": "string"
    },
    "event_parties": [
      {
        "party_type": "string",
        "party_ref_id": "string",
        "permissions": { "can_edit": true },
        "metadata": {}
      }
    ],
    "capabilities": {
      "multiple_occurrences": {
        "enabled": false,
        "allow_multiple": false,
        "max_occurrences": null
      }
    },
    "tags": ["string"],
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ]
  }
}
```
**Field Definitions**
- `thumb.type`: `image`.

### `GET /events/attendance/confirmed`
**Purpose:** List backend-authoritative event confirmations for the current user.  
**Response:**
```json
{
  "tenant_id": "string",
  "data": {
    "confirmed_event_ids": ["string"]
  }
}
```

### `POST /events/{event_id}/attendance/confirm`
**Purpose:** Confirm presence for an event (free attendance confirmation).  
**Request:**
```json
{
  "occurrence_id": "string?"
}
```
**Response:**
```json
{
  "tenant_id": "string",
  "event_id": "string",
  "occurrence_id": "string?",
  "kind": "free_confirmation",
  "status": "active",
  "confirmed_at": "2025-01-01T00:00:00Z"
}
```
**Field Definitions**
- `kind`: `free_confirmation`.
- `status`: `active`.

### `POST /events/{event_id}/attendance/unconfirm`
**Purpose:** Cancel a previously active free attendance confirmation for an event.  
**Request:**
```json
{
  "occurrence_id": "string?"
}
```
**Response:**
```json
{
  "tenant_id": "string",
  "event_id": "string",
  "occurrence_id": "string?",
  "kind": "free_confirmation",
  "status": "canceled",
  "canceled_at": "2025-01-01T00:00:00Z"
}
```
**Field Definitions**
- `kind`: `free_confirmation`.
- `status`: `canceled`.

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
  "source": "event|account_profile|static",
  "types": ["show"],
  "tags": ["string"],
  "taxonomy": ["type:value"],
  "search": "string?",
  "sort": "priority|distance|time_to_event",
  "stack_key": "string?"
}
```
**Field Definitions**
- `categories[]`: `culture`, `beach`, `nature`, `historic`, `restaurant`.
- `source`: `event`, `account_profile`, `static` (aliases accepted by backend: `account`, `asset`, `static_asset`).
- `types[]`: dynamic source-type slugs (backend projection field `source_type`).
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
        "ref_slug": "string?",
        "ref_path": "/event/sample-event",
        "title": "string",
        "subtitle": "string?",
        "name": "string",
        "description": "string?",
        "address": "string?",
        "category": "culture|beach|nature|historic|restaurant",
        "source_type": "string?",
        "location": { "lat": 0.0, "lng": 0.0 },
        "priority": 0,
        "updated_at": "2025-01-01T00:00:00Z",
        "time_start": "2025-01-01T00:00:00Z",
        "time_end": "2025-01-01T03:00:00Z",
        "avatar_url": "string?",
        "cover_url": "string?",
        "badge": "string?",
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
- `top_poi.source_type`: backend-defined type slug used by `types[]` filtering.
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
**Request (query params):** Same scoping filters as `/map/pois` (`ne_lat`, `ne_lng`, `sw_lat`, `sw_lng`, `origin_lat`, `origin_lng`, `max_distance_meters`, `categories[]`, `source`, `types[]`, `tags[]`, `taxonomy[]`, `search`).  
**Response:**
```json
{
  "tenant_id": "string",
  "categories": [
    {
      "key": "culture",
      "label": "Cultura",
      "image_uri": "https://tenant.example.com/storage/tenants/tenant-a/map_ui/filters/culture.png",
      "query": {
        "source": "event",
        "types": ["show"],
        "categories": ["culture"],
        "taxonomy": ["music_genre:rock"],
        "tags": ["live"]
      },
      "count": 0
    }
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
- `categories[].label`: tenant-facing label; when `settings.map_ui.filters` defines the key, the configured label overrides fallback label.
- `categories[].image_uri`: optional tenant-configured image URL for map filter button surfaces.
- `categories[].query`: normalized backend filter payload used when the category is selected (includes `source`, `types[]`, `categories[]`, `taxonomy[]`, `tags[]` as applicable).
- `categories[]` ordering: backend mirrors `settings.map_ui.filters` order and includes configured entries even when `count = 0`.
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

## 6.1) Settings Kernel (Tenant + Landlord)

### `GET /admin/api/v1/settings/schema`
**Purpose:** Discover render-ready settings schema for tenant-admin scope on tenant domains (namespaces, fields, nodes, conditional metadata).  
**Response (minimum):**
```json
{
  "data": {
    "schema_version": "1.0.0",
    "schema_version_policy": {
      "additive_changes": "no_version_bump_required",
      "breaking_changes": "version_bump_required"
    },
    "namespaces": [
      {
        "namespace": "events",
        "scope": "tenant",
        "label": "Events",
        "fields": [],
        "nodes": []
      }
    ]
  }
}
```

### `GET /admin/api/v1/settings/values`
**Purpose:** Fetch current tenant settings values for all authorized namespaces.  
**Response (minimum):**
```json
{
  "data": {
    "events": {
      "default_duration_hours": 3,
      "mode": "basic",
      "attendance": {
        "allowed_policies": [
          "free_confirmation_only",
          "paid_reservation_only",
          "either"
        ],
        "default_policy": "free_confirmation_only",
        "allow_event_override": true
      }
    }
  }
}
```

### `PATCH /admin/api/v1/settings/values/{namespace}`
**Purpose:** Namespace-scoped partial update using canonical field-presence semantics.  
**Request (body):**
```json
{
  "default_duration_hours": 4,
  "attendance": {
    "allowed_policies": [
      "free_confirmation_only",
      "paid_reservation_only"
    ],
    "default_policy": "free_confirmation_only",
    "allow_event_override": true
  }
}
```
**Response (minimum):**
```json
{
  "data": {
    "default_duration_hours": 4,
    "attendance": {
      "allowed_policies": [
        "free_confirmation_only",
        "paid_reservation_only"
      ],
      "default_policy": "free_confirmation_only",
      "allow_event_override": true
    }
  }
}
```

**Field Definitions**
- `namespace`: immutable technical namespace key (e.g., `events`, `map_ui`, `push`).
- PATCH payload: must be a direct object/map (envelopes like `paths` are invalid).
- PATCH merge rule: only payload-present keys mutate; omitted keys remain unchanged.
- Explicit clear: `null` is allowed only for nullable fields; non-nullable `null` returns `422`.
- `events.attendance.allowed_policies`: subset of `free_confirmation_only | paid_reservation_only | either`.
- `events.attendance.default_policy`: must belong to `allowed_policies`.
- `events.attendance.allow_event_override`: when false, event creators inherit tenant default policy and cannot choose another value.
- Capability rule: `paid_reservation_only` and `either` require paid reservation capability in the tenant/runtime; otherwise validation must reject them.

### `PATCH /admin/api/v1/settings/values/app_links`
**Purpose:** Update deep-link credentials only (no duplicated app identifiers).
**Request (body):**
```json
{
  "android": {
    "sha256_cert_fingerprints": ["AA:BB:...:ZZ"]
  },
  "ios": {
    "team_id": "ABCDE12345",
    "paths": ["/invite*", "/convites*"]
  }
}
```
**Response (minimum):**
```json
{
  "data": {
    "android": {
      "sha256_cert_fingerprints": ["AA:BB:...:ZZ"]
    },
    "ios": {
      "team_id": "ABCDE12345",
      "paths": ["/invite*", "/convites*"]
    }
  }
}
```

### `GET /admin/api/v1/appdomains`
**Purpose:** Fetch typed mobile app identifiers (resolver source-of-truth).
**Response (minimum):**
```json
{
  "app_domains": {
    "android": "com.guarappari.app",
    "ios": "com.guarappari.app"
  }
}
```

### `POST /admin/api/v1/appdomains`
**Purpose:** Upsert one typed mobile app identifier.
**Request (body):**
```json
{
  "platform": "android|ios",
  "identifier": "com.guarappari.app"
}
```
**Response (minimum):**
```json
{
  "message": "App domain identifier saved successfully.",
  "app_domains": {
    "android": "com.guarappari.app",
    "ios": "com.guarappari.app"
  }
}
```

### `DELETE /admin/api/v1/appdomains`
**Purpose:** Remove one typed mobile app identifier.
**Request (body):**
```json
{
  "platform": "android|ios"
}
```
**Response (minimum):**
```json
{
  "message": "App domain identifier removed successfully.",
  "app_domains": {
    "android": null,
    "ios": "com.guarappari.app"
  }
}
```

### Landlord equivalents (landlord host context)
- `GET /admin/api/v1/settings/schema`
- `GET /admin/api/v1/settings/values`
- `PATCH /admin/api/v1/settings/values/{namespace}`

### Landlord on-behalf tenant equivalents
- `GET /admin/api/v1/{tenant_slug}/settings/schema`
- `GET /admin/api/v1/{tenant_slug}/settings/values`
- `PATCH /admin/api/v1/{tenant_slug}/settings/values/{namespace}`

---

## 7) Tenant/Admin Area (Authenticated)

**Admin routing:** tenant-admin management endpoints live under `/admin/api/v1/*` on tenant domains and are restricted to landlord users via abilities. Landlord/global routes also live under `/admin/api/v1/*` on the landlord host.

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
      "labels": {
        "singular": "string",
        "plural": "string"
      },
      "map_category": "string",
      "allowed_taxonomies": ["string"],
      "visual": {
        "mode": "icon|image",
        "icon": "string?",
        "color": "#RRGGBB?",
        "icon_color": "#RRGGBB?",
        "image_source": "avatar|cover|type_asset?",
        "image_url": "https://...?"
      },
      "type_asset_url": "https://...?",
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
  "labels": {
    "singular": "string",
    "plural": "string"
  },
  "map_category": "string?",
  "allowed_taxonomies": ["string"],
  "visual": {
    "mode": "icon|image",
    "icon": "string?",
    "color": "#RRGGBB?",
    "icon_color": "#RRGGBB?",
    "image_source": "avatar|cover|type_asset?"
  },
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
**Multipart note:** when `visual.image_source=type_asset`, send file field `type_asset` via multipart; the response returns `visual.image_url` and `type_asset_url` when the canonical asset resolves.

### `PATCH /admin/api/v1/account_profile_types/{profile_type}`
**Purpose:** Update account profile type.  
**Request (body):** same as create (partial).  
**Response:** same as GET item.
**Notes:**
- `type` is mutable on edit (slug rename).
- When `type` changes, backend must enforce uniqueness and propagate `profile_type` to dependent `account_profiles`.
- When `type` changes, backend must also update dependent map projection category for `ref_type=account_profile` POIs.
- Multipart updates may send `_method=PATCH`, file field `type_asset`, and `remove_type_asset=true`.

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
      "visual": {
        "mode": "icon|image",
        "icon": "string?",
        "color": "#RRGGBB?",
        "icon_color": "#RRGGBB?",
        "image_source": "avatar|cover|type_asset?",
        "image_url": "https://...?"
      },
      "type_asset_url": "https://...?",
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
  "visual": {
    "mode": "icon|image",
    "icon": "string?",
    "color": "#RRGGBB?",
    "icon_color": "#RRGGBB?",
    "image_source": "avatar|cover|type_asset?"
  },
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
**Multipart note:** when `visual.image_source=type_asset`, send file field `type_asset` via multipart; the response returns `visual.image_url` and `type_asset_url` when the canonical asset resolves.

### `PATCH /admin/api/v1/static_profile_types/{profile_type}`
**Purpose:** Update static profile type.  
**Request (body):** same as create (partial).  
**Response:** same as GET item.
**Notes:**
- `type` is mutable on edit (slug rename).
- When `type` changes, backend must enforce uniqueness and propagate `profile_type` to dependent `static_assets`.
- When `type` changes, backend must update dependent map projection category for `ref_type=static` POIs using the resolved `map_category`.
- Multipart updates may send `_method=PATCH`, file field `type_asset`, and `remove_type_asset=true`.

**Static profile type note:** `map_category` is the source of truth for static asset map projection (`map_pois.category`).

### `DELETE /admin/api/v1/static_profile_types/{profile_type}`
**Purpose:** Delete static profile type.  
**Response:**
```json
{}
```

### `GET /admin/api/v1/event_types`
**Purpose:** List event type registry entries.  
**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "slug": "string",
      "description": "string?",
      "visual": {
        "mode": "icon|image",
        "icon": "string?",
        "color": "#RRGGBB?",
        "icon_color": "#RRGGBB?",
        "image_source": "cover|type_asset?",
        "image_url": "https://...?"
      },
      "poi_visual": {
        "mode": "icon|image",
        "icon": "string?",
        "color": "#RRGGBB?",
        "icon_color": "#RRGGBB?",
        "image_source": "cover|type_asset?",
        "image_url": "https://...?"
      },
      "type_asset_url": "https://...?"
    }
  ]
}
```

### `POST /admin/api/v1/event_types`
**Purpose:** Create event type.  
**Request (body):**
```json
{
  "name": "string",
  "slug": "string",
  "description": "string?",
  "visual": {
    "mode": "icon|image",
    "icon": "string?",
    "color": "#RRGGBB?",
    "icon_color": "#RRGGBB?",
    "image_source": "cover|type_asset?"
  }
}
```
**Response:** same as GET item.
**Multipart note:** when `visual.image_source=type_asset`, send file field `type_asset` via multipart; the response returns `visual.image_url`, `poi_visual.image_url`, and `type_asset_url` when the canonical asset resolves.

### `PATCH /admin/api/v1/event_types/{event_type}`
**Purpose:** Update event type.  
**Request (body):** same as create (partial).  
**Response:** same as GET item.
**Notes:**
- Multipart updates may send `_method=PATCH`, file field `type_asset`, and `remove_type_asset=true`.
- `visual.image_source` is limited to `cover|type_asset`; `avatar` is invalid for event types.

### `DELETE /admin/api/v1/event_types/{event_type}`
**Purpose:** Delete event type.  
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
**Note:** Event payload uses canonical `location` + typed `place_ref`; `venue` is a projection resolved from `place_ref` when applicable.  
**Response:**
```json
{
  "data": [
    {
      "event_id": "string",
      "occurrence_id": null,
      "slug": "string",
      "title": "string",
      "content": "string",
      "type": {
        "id": "string",
        "name": "string",
        "slug": "string",
        "description": "string?",
        "icon": "string?",
        "color": "#RRGGBB?"
      },
      "location": {
        "mode": "physical|online|hybrid",
        "geo": { "type": "Point", "coordinates": [0.0, 0.0] },
        "online": {
          "url": "string",
          "platform": "string?",
          "label": "string?"
        }
      },
      "place_ref": {
        "type": "string",
        "id": "string",
        "metadata": {}
      },
      "venue": {
        "id": "string",
        "display_name": "string",
        "tagline": "string?",
        "hero_image_url": "string?",
        "logo_url": "string?",
        "taxonomy_terms": [{ "type": "string", "value": "string" }]
      },
      "linked_account_profiles": [
        {
          "id": "string",
          "account_id": "string",
          "profile_type": "string",
          "display_name": "string",
          "slug": "string?",
          "avatar_url": "string?",
          "cover_url": "string?"
        }
      ],
      "latitude": 0.0,
      "longitude": 0.0,
      "date_time_start": "2025-01-01T00:00:00Z",
      "date_time_end": "2025-01-01T00:00:00Z?",
      "occurrences": [
        { "date_time_start": "2025-01-01T00:00:00Z", "date_time_end": "2025-01-01T00:00:00Z?" }
      ],
      "publication": {
        "status": "published|publish_scheduled|draft|ended",
        "publish_at": "2025-01-01T00:00:00Z?"
      },
      "event_parties": [
        {
          "party_type": "string",
          "party_ref_id": "string",
          "permissions": { "can_edit": true },
          "metadata": {}
        }
      ],
      "capabilities": {
        "multiple_occurrences": {
          "enabled": false,
          "allow_multiple": false,
          "max_occurrences": null
        },
        "map_poi": {
          "enabled": true,
          "discovery_scope": null
        }
      },
      "tags": ["string"],
      "taxonomy_terms": [{ "type": "string", "value": "string" }],
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

### `GET /events/account_profile_candidates`
**Purpose:** Page-based account-profile candidate discovery for event forms in management contexts. Tenant-admin uses `/admin/api/v1/events/account_profile_candidates`; account-scoped own-create uses `/api/v1/accounts/{account_slug}/events/account_profile_candidates`.

**Request (query):**
- `type`: `related_account_profile|physical_host` (required)
- `search`: `string?`
- `page`: `int?`
- `page_size` or `per_page`: `int?` (max `50`)

**Response:**
```json
{
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "artist|venue|restaurant|string",
      "display_name": "string",
      "slug": "string?",
      "avatar_url": "string?",
      "cover_url": "string?"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 20,
  "total": 0
}
```

**Rules**
- `type=related_account_profile` returns generic related account profiles and must not encode one specific dynamic profile type in the contract.
- `type=physical_host` returns only POI-enabled account profiles with valid coordinates.
- Search semantics are backend-owned and use canonical `like` matching on candidate fields (`display_name`, `slug`).
- Event-form candidate discovery must remain page-based; clients must not depend on a one-shot fixed preload.

### `POST /events`
**Purpose:** Create event.  
**Request (body):**
```json
{
  "title": "string",
  "content": "string",
  "location": {
    "mode": "physical|online|hybrid",
    "geo": { "type": "Point", "coordinates": [0.0, 0.0] },
    "online": {
      "url": "https://example.com/meet",
      "platform": "string?",
      "label": "string?"
    }
  },
  "place_ref": {
    "type": "string",
    "id": "string",
    "metadata": {}
  },
  "type": {
    "name": "string",
    "slug": "string",
    "description": "string?",
    "icon": "string?",
    "color": "#RRGGBB?"
  },
  "occurrences": [
    { "date_time_start": "2025-01-01T00:00:00Z", "date_time_end": "2025-01-01T00:00:00Z?" }
  ],
  "publication": {
    "status": "published|publish_scheduled|draft|ended",
    "publish_at": "2025-01-01T00:00:00Z?"
  },
  "capabilities": {
    "multiple_occurrences": {
      "enabled": false
    }
  },
  "event_parties": [
    {
      "party_type": "string",
      "party_ref_id": "string",
      "permissions": { "can_edit": true },
      "metadata": {}
    }
  ]
}
```
**Response:**
```json
{
  "data": {
    "event_id": "string",
    "occurrence_id": null,
    "slug": "string",
    "title": "string",
    "content": "string",
    "location": {
      "mode": "physical|online|hybrid",
      "geo": { "type": "Point", "coordinates": [0.0, 0.0] },
      "online": {
        "url": "string",
        "platform": "string?",
        "label": "string?"
      }
    },
    "place_ref": {
      "type": "string",
      "id": "string",
      "metadata": {}
    },
    "venue": {
      "id": "string",
      "display_name": "string",
      "tagline": "string?",
      "hero_image_url": "string?",
      "logo_url": "string?",
      "taxonomy_terms": [{ "type": "string", "value": "string" }]
    },
    "linked_account_profiles": [
      {
        "id": "string",
        "account_id": "string",
        "profile_type": "string",
        "display_name": "string",
        "slug": "string?",
        "avatar_url": "string?",
        "cover_url": "string?"
      }
    ],
    "latitude": 0.0,
    "longitude": 0.0,
    "date_time_start": "2025-01-01T00:00:00Z",
    "date_time_end": "2025-01-01T00:00:00Z?",
    "occurrences": [
      { "date_time_start": "2025-01-01T00:00:00Z", "date_time_end": "2025-01-01T00:00:00Z?" }
    ],
    "publication": {
      "status": "published|publish_scheduled|draft|ended",
      "publish_at": "2025-01-01T00:00:00Z?"
    },
    "event_parties": [
      {
        "party_type": "string",
        "party_ref_id": "string",
        "permissions": { "can_edit": true },
        "metadata": {}
      }
    ],
    "capabilities": {
      "multiple_occurrences": {
        "enabled": false,
        "allow_multiple": false,
        "max_occurrences": null
      },
      "map_poi": {
        "enabled": true,
        "discovery_scope": null
      }
    },
    "tags": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
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
  "data": {
    "event_id": "string",
    "occurrence_id": null,
    "slug": "string",
    "title": "string",
    "content": "string",
    "location": {
      "mode": "physical|online|hybrid",
      "geo": { "type": "Point", "coordinates": [0.0, 0.0] },
      "online": {
        "url": "string",
        "platform": "string?",
        "label": "string?"
      }
    },
    "place_ref": {
      "type": "string",
      "id": "string",
      "metadata": {}
    },
    "venue": {
      "id": "string",
      "display_name": "string",
      "tagline": "string?",
      "hero_image_url": "string?",
      "logo_url": "string?",
      "taxonomy_terms": [{ "type": "string", "value": "string" }]
    },
    "linked_account_profiles": [
      {
        "id": "string",
        "account_id": "string",
        "profile_type": "string",
        "display_name": "string",
        "slug": "string?",
        "avatar_url": "string?",
        "cover_url": "string?"
      }
    ],
    "latitude": 0.0,
    "longitude": 0.0,
    "date_time_start": "2025-01-01T00:00:00Z",
    "date_time_end": "2025-01-01T00:00:00Z?",
    "occurrences": [
      { "date_time_start": "2025-01-01T00:00:00Z", "date_time_end": "2025-01-01T00:00:00Z?" }
    ],
    "publication": {
      "status": "published|publish_scheduled|draft|ended",
      "publish_at": "2025-01-01T00:00:00Z?"
    },
    "event_parties": [
      {
        "party_type": "string",
        "party_ref_id": "string",
        "permissions": { "can_edit": true },
        "metadata": {}
      }
    ],
    "capabilities": {
      "multiple_occurrences": {
        "enabled": false,
        "allow_multiple": false,
        "max_occurrences": null
      },
      "map_poi": {
        "enabled": true,
        "discovery_scope": null
      }
    },
    "tags": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "updated_at": "2025-01-01T00:00:00Z",
    "created_at": "2025-01-01T00:00:00Z"
  }
}
```

**Hard rules for write payloads:**
- `date_time_start` and `date_time_end` are prohibited in write requests.
- schedule must be sent via `occurrences[]`.
- `venue_id` is prohibited; use canonical `location` + typed `place_ref`.
- invite lifecycle fields are not part of Events payload contract.

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
