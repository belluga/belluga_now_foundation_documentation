# Documentation: System Roadmap
**Version:** 1.0

## 1. Roadmap Overview

This roadmap enumerates the foundational milestones for the Belluga ecosystem. It aligns mocked implementations with the definitive architecture to guarantee a seamless transition toward production services.

## 2. Current Milestones

| Workstream | Milestone | Description | Target | Status | Owner |
|------------|-----------|-------------|--------|--------|-------|
| Flutter Client Experience | FCX-01 | Bootstrap DI container, theming, and StreamValue-based controller scaffolding. | 2025-02-28 | In Progress | Delphi |
| Flutter Client Experience | FCX-02 | Lock endpoint response schemas (contract-first) for MVP flows. | 2025-03-05 | Planned | Delphi |
| Flutter Client Experience | FCX-03 | Wire mocked repositories/services to tenant home, agenda, invites, map, and profiles based on contracts. | 2025-03-12 | Planned | Delphi |
| Flutter Client Experience | FCX-04 | Implement telemetry (Mixpanel) baseline for MVP flows. | 2025-03-19 | Planned | Delphi |
| Flutter Client Experience | FCX-05 | Add location permission guard + permission screen for geo-dependent routes (map/nearby). | 2025-03-26 | Planned | Delphi |
| Flutter Client Experience | FCX-06 | Eliminate Flutter architecture hard‑NO deviations (GetIt in widgets, DTOs in domain, Future/StreamBuilder in UI, direct Navigator usage, multi‑widget files). | 2025-04-01 | Planned | Delphi |
| Platform Realtime | PRX-01 | Add SSE delta streams for app feeds (events, invites, POIs) to complement page-based pagination. | 2025-04-02 | Planned | Delphi |
| Platform Routing & Scope | PRS-01 | Canonical environment scope reorganization (`landlord`/`tenant` + main scopes + `account_workspace` subscope), including host-aware `/home` and `/landlord` normalization and live web validation matrix. | 2026-02-24 | Tested & Ready | Delphi |

## 2.1 Documentation Integrity Gaps
- `submodule_web-app_summary.md` is still missing and must be generated.
- Existing submodule summaries are currently stale versus checked-out commits:
  - `submodule_flutter-app_summary.md` commit hash mismatch.
  - `submodule_laravel-app_summary.md` commit hash mismatch.

## 2.2 Scope/Subscope Governance (Mandatory)
- Mandatory pre-read for route/module/screen work: `foundation_documentation/policies/scope_subscope_governance.md`.
- Any route/module/screen initiative in this roadmap must declare explicit ownership using:
  - `EnvironmentType` (`landlord` or `tenant`),
  - main scope (`site_public`, `landlord_area`, `tenant_public`, `tenant_admin`),
  - subscope (`account_workspace`) when applicable.
- New subscopes are forbidden unless explicitly approved and first documented in the canonical policy.
- Multi-scope deliveries must include an explicit route/scope matrix in their authoritative module/screen docs before implementation is considered complete.

## 3. API Endpoint Tracking

| Endpoint | Module | Description | Current Status | Notes |
|----------|--------|-------------|----------------|-------|
| `/api/v1/anonymous/identities` | MOD-101 | Anonymous identity bootstrap (Sanctum token issuance for web/app guest flows). | Implemented | Unauthenticated route returns `{user_id, identity_state, token, abilities, expires_at?}`; abilities/TTL controlled by `tenant.anonymous_access_policy`. |
| `/api/v1/auth/token_validate` | MOD-101 | Validate bearer token and return minimal user profile. | Implemented | Returns `{ data: { user: { id, name, emails, custom_data } } }` for login check; route registered in `routes/api/public_tenant_maybe_api_v1.php`. |
| `/api/v1/environment` | MOD-101 | Tenant/landlord resolution + branding payload for app/web bootstraps. | Implemented | Returns tenant identity + theme settings + telemetry/firebase/push config + `profile_types` registry + `settings.map_ui.radius` (min/default/max); location freshness lives under `telemetry`; uses host/app domain context. |
| `/api/v1/invites` | MOD-201 | Invite feed and referral graph. | Defined | Enforces 1 invite per person/event; **user invites** limited to contacts/installed users; **account_profile invites** can target favorites/followers (admin-assigned in MVP). No route registered in `routes/api`. |
| `/api/v1/invites/stream` | MOD-201 | Invite delta stream (SSE). | Planned | Emits invite created/updated/deleted events for authenticated user; inviter principal kind = `user|account_profile`. |
| `/api/v1/invites/settings` | MOD-201 | Backend-owned invite quotas, anti-spam limits, and UX messaging settings. | Planned | Backend enforces over-quota responses (`429`) and returns reset metadata; Flutter fetches for messaging/UX. |
| `/api/v1/invites/share` | MOD-201 | External share codes for event invites (new user install/signup attribution). | Planned | Anyone who can invite can generate; resolves to `inviter_principal` (user or account_profile) + `event_id`; requires `account_profile_id` when inviter is account_profile; includes `/consume` to bind attribution post-install. |
| `/api/v1/invites/share/{code}/accept` | MOD-201 | Web landing acceptance for invite share codes. | Planned | Requires Sanctum (anonymous token); binds attribution and returns invite state. |
| `/api/v1/agenda` | MOD-201 | Paged agenda feed with search + past toggle, includes happening-now events. | Tested & Ready | Request: `page`, `page_size`, `past_only`, `search`, `categories`, `tags`, `taxonomy`, `confirmed_only`, `origin_lat/lng`, `max_distance_meters`. Response: event DTO items (type, venue, artists, tags, invite arrays, is_confirmed, total_confirmed), `has_more` flag. Event geo is derived from venue profile location (no standalone event location). Happening-now rule: `date_time_start <= now < date_time_end` (default end = start + 3h). |
| `/api/v1/events/stream` | MOD-201 | Event delta stream (SSE). | Tested & Ready | Emits event created/updated/deleted events for filtered feeds; clients resume with `Last-Event-ID` and reload page 1 on reconnect without it. |
| `/api/v1/events/{event_id}` | MOD-201 | Event detail payload. | Tested & Ready | Event detail contract aligned to agenda cards + map POI references. Event geo comes from venue profile location. |
| `/api/v1/map/pois` | MOD-201 | Map POIs (projection-backed). | Tested & Ready | Minimal stack payload with `stack_key` + `top_poi.updated_at`; filters by `categories`, `tags`, `taxonomy`, `search`; no tags/taxonomy returned. Uses user profile timezone for day-based event window filters. |
| `/api/v1/map/pois/stream` | MOD-201 | Map POI delta stream (SSE). | Defined | Emits POI created/updated/deleted events for active viewport/filters. Deferred for MVP (polling only); no route registered in `routes/api`. |
| `/api/v1/map/filters` | MOD-201 | Map filter discovery (categories/tags). | Tested & Ready | Returns category/tag/taxonomy catalogs from the projection set; removes hardcoded filter catalogs from mocks. |
| `/api/v1/map/near` | MOD-201 | Map POI card list (distance-ordered). | Tested & Ready | Paginated (default 10/page) with rich card fields, tags, and taxonomy terms; includes `ref_slug` + `ref_path`. |
| `/api/v1/me` | MOD-201 | Authenticated profile summary and role claims. | Implemented | Mock payload authoring queued in FCX-02. |
| `/api/v1/account_profiles/discovery` | MOD-201 | Account profile discovery cards with engagement metrics and invite counts. | Defined | Needs DTO/value-object mapping and shared prototype data for Laravel alignment. No route registered; public index exists at `/api/v1/account_profiles`. |
| `/api/v1/events/{event_id}/check-in` | MOD-201 | Presence confirmation with geofence/QR/staff methods. | Planned | Deferred to VNext; MVP uses invite acceptance only for confirmations. |
| `/api/v1/missions` | MOD-201 | Account-profile-created missions with metric targets and rewards. | Defined | Metrics selectable per mission; account workspace must show rankings/progress. |
| `/api/v1/account_profile_links` | MOD-201 | Account profile ↔ curador/pessoa linkage. | Defined | Bidirectional proposals; statuses pending/accepted; monthly proof-of-presence window. |
| `/api/v1/discover/people` | MOD-201 | People/Influencer row ordered by monthly Social Score. | Defined | Prefer verified on ties; respects privacy by anonymizing friends-only profiles. |
| `/api/v1/discover/curator-content` | MOD-201 | Curator-produced content for “Veja isso…” row. | Defined | Ordered by latest published (future: most viewed); links to account profile/event. |
| `/api/v1/contacts/import` | MOD-201 | Hashed contact import for friend suggestions and invite matching. | Planned | Accepts salted hashes only; no raw PII stored. |
| `/api/v1/push/register` | MOD-201 | Register device token for push notifications. | Implemented | Stores per-device tokens; used for invites/reminders. |
| `/api/v1/settings/push` | Tenant Admin | Update tenant push settings (push-only). | Implemented | Split from firebase/telemetry; returns push config payload. |
| `/api/v1/settings/firebase` | Tenant Admin | Update tenant firebase settings. | Implemented | Dedicated endpoint for firebase config. |
| `/api/v1/settings/telemetry` | Tenant Admin | Manage telemetry integrations (list + upsert). | Implemented | Upsert by `type`; unique types enforced. |
| `/api/v1/settings/telemetry/{type}` | Tenant Admin | Remove telemetry integration by type. | Implemented | DELETE removes a single type. |
| `/admin/api/v1/organizations` | Tenant Admin | List organizations (grouping only). | Implemented | Tenant‑scoped; landlord users only. Paged response with org metadata. |
| `/admin/api/v1/organizations` | Tenant Admin | Create organization. | Implemented | Tenant‑scoped; landlord users only. Minimal MVP fields: `name`, optional `description`. |
| `/admin/api/v1/organizations/{organization_id}` | Tenant Admin | Organization detail. | Implemented | Tenant‑scoped; landlord users only. Returns org metadata. |
| `/admin/api/v1/organizations/{organization_id}` | Tenant Admin | Update organization. | Implemented | Tenant‑scoped; landlord users only. Patch name/description. |
| `/admin/api/v1/organizations/{organization_id}` | Tenant Admin | Delete/restore organization. | Implemented | Soft delete + restore + force delete endpoints are live. |
| `/admin/api/v1/accounts` | Tenant Admin | List accounts (tenant-owned, unmanaged, user-owned). | Implemented | Tenant‑scoped; landlord users only. Read payload includes `ownership_state`; supports optional `ownership_state` filter. |
| `/admin/api/v1/accounts` | Tenant Admin | Create account. | Implemented | Tenant‑scoped; landlord users only. Create payload includes explicit `ownership_state` intent (`tenant_owned` or `unmanaged`). |
| `/admin/api/v1/accounts/{account_slug}` | Tenant Admin | Fetch/update/delete account (partial). | Implemented | Uses `account_slug`; soft delete + restore + force delete endpoints are live. |
| `/admin/api/v1/account_profiles` | Tenant Admin | List/create account profiles. | Implemented | Tenant‑scoped; landlord users only. Paged response includes profile metadata + `ownership_state`. |
| `/admin/api/v1/account_profiles/{account_profile_id}` | Tenant Admin | Fetch/update/delete account profile. | Implemented | Tenant‑scoped; landlord users only. Soft delete + restore + force delete endpoints are live. |
| `/admin/api/v1/account_profiles/geo` | Tenant Admin | Geo search for POI-enabled profiles. | Implemented | **Removed** from tenant admin routes; superseded by `/api/v1/map/pois`. |
| `/admin/api/v1/account_profile_types` | Tenant Admin | Profile type registry (tenant settings). | Implemented | Returns registry entries and capabilities. |
| `/admin/api/v1/account_profile_types` | Tenant Admin | Create profile type registry entry. | Implemented | Persists to `account_profile_types`. |
| `/admin/api/v1/account_profile_types/{profile_type}` | Tenant Admin | Update profile type registry entry. | Implemented | `profile_type` is immutable; patch label/capabilities/taxonomies. |
| `/admin/api/v1/account_profile_types/{profile_type}` | Tenant Admin | Delete profile type registry entry. | Implemented | Removes entry from registry; no soft delete in MVP. |
| `/admin/api/v1/taxonomies` | Tenant Admin | List taxonomies (Account Profiles + Static Assets + Events). | Tested & Ready | Returns taxonomy registry (slug/name/applies_to/icon/color). |
| `/admin/api/v1/taxonomies` | Tenant Admin | Create taxonomy. | Tested & Ready | Validates slug uniqueness and applies_to values. |
| `/admin/api/v1/taxonomies/{taxonomy_id}` | Tenant Admin | Update taxonomy. | Tested & Ready | Patch slug/name/applies_to/icon/color. |
| `/admin/api/v1/taxonomies/{taxonomy_id}` | Tenant Admin | Delete taxonomy. | Tested & Ready | Also deletes associated terms. |
| `/admin/api/v1/taxonomies/{taxonomy_id}/terms` | Tenant Admin | List taxonomy terms. | Tested & Ready | Terms are managed only under /terms. |
| `/admin/api/v1/taxonomies/{taxonomy_id}/terms` | Tenant Admin | Create taxonomy term. | Tested & Ready | Term slug unique per taxonomy. |
| `/admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}` | Tenant Admin | Update taxonomy term. | Tested & Ready | Patch slug/name. |
| `/admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}` | Tenant Admin | Delete taxonomy term. | Tested & Ready | Removes term from registry. |
| `/admin/api/v1/static_profile_types` | Tenant Admin | List static profile types. | Tested & Ready | Returns registry entries for static assets. |
| `/admin/api/v1/static_profile_types` | Tenant Admin | Create static profile type. | Tested & Ready | Persists to `static_profile_types`. |
| `/admin/api/v1/static_profile_types/{profile_type}` | Tenant Admin | Update static profile type. | Tested & Ready | `profile_type` is immutable; patch label/capabilities/taxonomies. |
| `/admin/api/v1/static_profile_types/{profile_type}` | Tenant Admin | Delete static profile type. | Tested & Ready | Removes entry from registry. |
| `/admin/api/v1/static_assets` | Tenant Admin | Static Asset CRUD for map POIs + pages. | Tested & Ready | Tenant-admin endpoints for create/update/delete/restore of static assets. |
| `/admin/api/v1/media/external-image` | Tenant Admin | Proxy external image URL to bytes for ingestion (URL import without CORS). | Tested & Ready | Authenticated + `CheckTenantAccess`; SSRF + size limits; returns raw bytes with `Cache-Control: no-store`. |
| `/api/v1/static_assets/{asset_ref}` | Tenant | Static Asset public read (page). | Tested & Ready | Returns the static asset page payload by id or slug. |
| `/admin/api/v1/events` | Tenant Admin | List events (admin). | Tested & Ready | Admin listing, page-based. |
| `/admin/api/v1/events` | Tenant Admin | Create event. | Tested & Ready | Admin/account profile creates event. |
| `/admin/api/v1/events/{event_id}` | Tenant Admin | Update event (partial). | Tested & Ready | Patch event metadata + schedule. |
| `/admin/api/v1/branding/update` | Tenant Admin | Update tenant branding settings. | Implemented | Drives `/environment` payload + asset paths; route registered in `routes/api/tenant_api_v1.php`. Contract scope is `theme_data_settings` + `logo_settings`/`pwa_icon` (tenant name is not persisted by this endpoint). |

## 4. Risk & Mitigation Log

| ID | Risk | Impact | Mitigation |
|----|------|--------|------------|
| R-201-01 | Mock payload drift from backend contract. | UI regressions when real API arrives. | Maintain contract tests and share DTO schemas with backend team. |
| R-201-02 | Controller lifecycle leaks degrade performance. | Memory growth and navigation instability. | Enforce disposal patterns and add integration tests covering scope teardown. |

## 5. Flutter Experience Future Phases

These roadmap phases extend the Flutter persona track and remain aligned with the platform-wide milestones above. Each phase is expressed as a target-state capability so downstream modules can scope their dependencies early.

- **Phase 6 – User Personalization / Favorites:** establish persistent bookmarking across POIs and events, wired to authenticated tenants so saved items hydrate the invite loop, agenda, and account profile insights.
- **Phase 6.1 – Remote Onboarding Experience:** deliver a geo-aware entry path for out-of-city visitors by defaulting map focus to the tenant-configured hub, prioritizing lodging/hosting templates, and emitting “potential visitor” analytics events.
- **Phase 7 – Offline Reliability:** design “forever cache” strategies (tiles, POI snapshots, invite context) to keep the app responsive in low-connectivity regions while respecting data-expiry policies.
- **Phase 8 – Gamification Spine:** standardize ranking schemas (global invites, account-profile-specific ladders, custom rank labels) and UI hooks so each module can project consistent reward states.
- **Phase 9 – Invite Flow Evolution:** finalize the Tinder-style invite carousel, WhatsApp/in-app share contracts, and the analytics wiring that feeds the Phase 8 ranking services.
- **Phase 10 – Tenant Home & Global Aggregations:** converge backend-driven home composition with multi-source data (offers, agenda, social actions) to give account profiles a single payload to target experiments.
- **Phase 11 – Invite Status & Privacy Controls:** attach invite-state halos to schedule cards, surface modal drill-downs, and enforce privacy toggles so invitees govern their exposure.
- **Phase 12 – Account Workspace:** scope the business-facing module where accounts manage account profiles, POIs, campaigns, promotions, and telemetry with the same backend contracts used by the tenant app.
- **Phase 13 – Profile, Global Search, and Notifications:** expand user settings/privacy, add cross-surface search, and align push topics with the backend FCM contract so experiences feel continuous.

## 6. Web-to-App Promotion Policy

**Initial stance (V1):** Web is a promotional/preview surface; the app owns conversion and trust actions.

**Web allowed (V1):**
- Event landing (read-only): title, date/time, venue name, artists names, hero media.
- Invite landing (read-only): “You were invited by …” context + event summary.
- Map browsing (read-only) for discovery; guide users into app for confirmations.
- Install / Open-App CTAs that preserve the invite share code for attribution.
- Invite acceptance is allowed only from invite landing reached via a single `code` (narrow V1 exception); credited to that code’s inviter principal.
- Web “unauthenticated” surfaces may mint a backend-issued anonymous Sanctum token via `/api/v1/anonymous/identities` to call allowed endpoints.

**Web authenticated allowed (V1):**
- Tenant/Admin area: accounts, events, assets, and branding management.

**App-only (V1):**
- Accept/Decline invite from agenda surfaces (credited acceptance selection + anti-gaming + quota enforcement).
- Confirm presence / check-in.
- Send invites (user + account_profile; account_profile issuance is admin-assigned in MVP).
- Favorites and trust actions (check-in, credited acceptance selection).

**Attribution requirement:** External share links carry a single `code` as a GET param that resolves `{ tenant_id, event_id, inviter_principal }`. Web must preserve this `code` through install and the app must call a backend `consume` endpoint post-install/post-signup to bind attribution.

**Tracking mandate (V1):** Instrument web → install → signup → acceptance → presence funnel to validate whether web-only actions should be expanded later. Use Mixpanel (client) and/or server-side events, but align naming to avoid double-counting.

**Future consideration:** Revisit after Phase 8 once viral loops and account profile analytics are stable. At that time we can evaluate a lightweight web confirmation flow for specific tenants or campaigns, balancing lower friction against the need to keep Task & Reminder and map experiences consistent. Any shift will require the invite, onboarding, and task modules to expose parallel web APIs with equivalent security guarantees.
