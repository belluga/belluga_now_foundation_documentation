# Documentation: System Roadmap
**Version:** 1.0

## 1. Roadmap Overview

This roadmap enumerates the foundational milestones for the Belluga ecosystem. It aligns Flutter and Laravel implementations around production contracts, with runtime clients consuming live backend adapters only.

## 2. Current Milestones

| Workstream | Milestone | Description | Target | Status | Owner |
|------------|-----------|-------------|--------|--------|-------|
| Flutter Client Experience | FCX-01 | Bootstrap DI container, theming, and StreamValue-based controller scaffolding. | 2025-02-28 | In Progress | Delphi |
| Flutter Client Experience | FCX-02 | Lock endpoint response schemas (contract-first) for MVP flows. | 2025-03-05 | Planned | Delphi |
| Flutter Client Experience | FCX-03 | Wire Laravel-backed repositories/services to tenant home, agenda, invites, map, and account profiles based on contracts. | 2025-03-12 | Planned | Delphi |
| Flutter Client Experience | FCX-04 | Implement telemetry (Mixpanel) baseline for MVP flows. | 2025-03-19 | Planned | Delphi |
| Flutter Client Experience | FCX-05 | Add location permission guard + permission screen for geo-dependent routes (map/nearby). | 2025-03-26 | Planned | Delphi |
| Flutter Client Experience | FCX-06 | Eliminate Flutter architecture hard‑NO deviations (non-controller/cross-feature GetIt resolution in screens/widgets, DTOs in domain, Future/StreamBuilder in UI, direct Navigator usage, multi‑widget files). | 2025-04-01 | Planned | Delphi |
| Platform Realtime | PRX-01 | Add SSE delta streams for app feeds (events, invites, POIs) to complement page-based pagination. | 2025-04-02 | Planned | Delphi |
| Platform Routing & Scope | PRS-01 | Canonical environment scope reorganization (`landlord`/`tenant` + main scopes + `account_workspace` subscope), including host-aware `/home` and `/landlord` normalization and live web validation matrix. | 2026-02-24 | Tested & Ready | Delphi |

## 2.1 Documentation Integrity Gaps
- `submodule_web-app_summary.md` is still missing and must be generated.
- Existing submodule summaries must be kept synchronized with checked-out submodule commits at each documentation consolidation checkpoint.

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
| `/api/v1/anonymous/identities` | MOD-101 | Anonymous identity bootstrap (Sanctum token issuance for app guest flows). | Implemented | Unauthenticated route returns `{user_id, identity_state, token, abilities, expires_at?}`; abilities/TTL controlled by `tenant.anonymous_access_policy`. V1 web invite landing is read-only and must not mint anonymous identities for invite conversion. |
| `/api/v1/auth/token_validate` | MOD-101 | Validate bearer token and return minimal user profile. | Implemented | Returns `{ data: { user: { id, name, emails, custom_data } } }` for login check; route registered in `routes/api/public_tenant_maybe_api_v1.php`. |
| `/api/v1/environment` | MOD-101 | Tenant/landlord resolution + branding payload for app/web bootstraps. | Implemented | Returns tenant identity + theme settings + telemetry/firebase/push config + `profile_types` registry + `settings.map_ui` (`radius` min/default/max + `default_origin` + ordered `filters[]` catalog with `key/label/image_uri`); location freshness lives under `telemetry`; uses host/app domain context. |
| `/.well-known/assetlinks.json` | MOD-101 | Host-resolved Android App Links association payload. | Implemented | Served by Laravel web route under `tenant-maybe`; package identifier comes from typed app domains (`app_android`) and credentials come from `settings.app_links.android` (tenant scope, landlord fallback). Static public file shadowing is intentionally removed. |
| `/.well-known/apple-app-site-association` | MOD-101 | Host-resolved iOS Universal Links association payload. | Implemented | Served by Laravel web route under `tenant-maybe`; bundle identifier comes from typed app domains (`app_ios`) and credentials come from `settings.app_links.ios` (tenant scope, landlord fallback). Returns JSON fallback shape when credentials are missing. |
| `/api/v1/invites` | MOD-201 | Invite feed and referral graph. | Tested & Ready | Implemented in `belluga_invites` package with grouped feed, canonical uniqueness, duplicate protection, and covered by `tests/Feature/Invites/InvitesFlowTest.php`. |
| `/api/v1/invites/stream` | MOD-201 | Invite delta stream (SSE). | Implemented | Emits invite created/updated/deleted deltas from `invite_outbox_events`; tenant-authenticated + `CheckTenantAccess`. |
| `/api/v1/invites/settings` | MOD-201 | Backend-owned invite quotas, anti-spam limits, and UX messaging settings. | Tested & Ready | Returns limits/cooldowns/reset metadata; `429` rejections include structured limit payload metadata. |
| `/api/v1/invites/share` | MOD-201 | External share codes for event invites (new user install/signup attribution). | Tested & Ready | Implemented with same-target reuse, anti-spam cooldown/daily limits, and account-profile issuer permission checks. |
| `/api/v1/invites/share/{code}` | MOD-201 | Share-code invite preview resolution for `/invite?code=...` landing. | Tested & Ready | Public tenant route resolves invite preview context without forced login; missing/expired codes return deterministic `404 invite_share_not_found`. |
| `/api/v1/invites/share/{code}/materialize` | MOD-201 | Share entry materialization for authenticated continuation flows. | Tested & Ready | Requires authenticated Sanctum user; anonymous identity receives deterministic `401 auth_required`. V1 app conversion is anonymous-first and may accept directly from preview using `POST /invites/{invite_id}/accept`; materialization remains available for authenticated continuation and compatibility flows. |
| `/api/v1/agenda` | MOD-201 | Paged agenda feed with search + past toggle, includes happening-now events. | Tested & Ready | Request: `page`, `page_size`, `past_only`, `confirmed_only`, `search`, `categories`, `tags`, `taxonomy`, `origin_lat/lng`, `max_distance_meters`. Response: occurrence-first event DTO (`event_id`, `occurrence_id`, `date_time_start/end`, `occurrences`, `event_parties`, `capabilities`, taxonomy/tags), `has_more` flag. Invite lifecycle fields are not exposed by Events payloads. Event location follows canonical `location + place_ref` (venue projection is resolver output, not a required write input). Happening-now rule: `date_time_start <= now < effective_end` (default end = start + 3h). `confirmed_only=1` returns only attendance-confirmed items; anonymous identity returns `200` empty list; geo filters must not exclude confirmed-only items (origin remains optional for distance decoration). |
| `/api/v1/events/stream` | MOD-201 | Event delta stream (SSE). | Tested & Ready | Emits occurrence-first deltas (`occurrence.created`, `occurrence.updated`, `occurrence.deleted`) with `{event_id, occurrence_id, type, updated_at}`. Clients resume with `Last-Event-ID`; on reconnect without cursor (or invalid cursor), client reloads page 1 from `/agenda` and continues from now. |
| `/api/v1/events/{event_id}` | MOD-201 | Event detail payload. | Tested & Ready | Event detail contract is aligned to occurrence-first agenda cards (`occurrences[]`, `event_parties`, `capabilities`, taxonomy/tags). Invite lifecycle fields are intentionally absent from Events payloads. Event location follows canonical `location + place_ref`. |
| `/api/v1/map/pois` | MOD-201 | Map POIs (projection-backed). | Tested & Ready | Minimal stack payload with `stack_key` + `top_poi.updated_at`; filters by `categories`, `tags`, `taxonomy`, `search`; no tags/taxonomy returned. Uses user profile timezone for day-based event window filters. |
| `/api/v1/map/pois/stream` | MOD-201 | Map POI delta stream (SSE). | Defined | Emits POI created/updated/deleted events for active viewport/filters. Deferred for MVP (polling only); no route registered in `routes/api`. |
| `/api/v1/map/filters` | MOD-201 | Map filter discovery (categories/tags). | Tested & Ready | Returns category/tag/taxonomy catalogs from the projection set; removes hardcoded filter catalogs from mocks. |
| `/api/v1/map/near` | MOD-201 | Map POI card list (distance-ordered). | Tested & Ready | Paginated (default 10/page) with rich card fields, tags, and taxonomy terms; includes `ref_slug` + `ref_path`. |
| `/api/v1/map/pois/lookup` | MOD-201 | Deterministic single-POI lookup by typed reference (`ref_type`, `ref_id`). | Tested & Ready | Implemented in tenant public map routes and covered by `MapPoisControllerTest` (lookup success + deterministic not-found). |
| `/api/v1/me` | MOD-201 | Authenticated profile summary and role claims. | Implemented | Mock payload authoring queued in FCX-02. |
| `/api/v1/favorites` | MOD-201 | Registry-backed favorites feed for Home and account-profile contexts. | Defined | Reads from `favorite_edges` + registry snapshot. Query accepts optional `registry_key`; V1 uses `registry_key=account_profile` with derived dedicated collection `favoritable_account_profile_snapshots` and occurrence ordering (`next_event_occurrence_at` asc, fallback `last_event_occurrence_at` desc, then `favorited_at` desc). Payload omits `tenant_id` (tenant-isolated DB). Guardrail must enforce registry snake_case, explicit collection pattern when provided, and block default shared collection for registries with specific indexes. |
| `/api/v1/account_profiles/discovery` | MOD-201 | Account profile discovery cards with engagement metrics and invite counts. | Defined | Needs DTO/value-object mapping and shared prototype data for Laravel alignment. No route registered; public index exists at `/api/v1/account_profiles`. |
| `/api/v1/events/{event_id}/check-in` | MOD-201 | Presence confirmation with geofence/QR/staff methods. | Planned | Deferred to VNext; MVP uses invite acceptance only for confirmations. |
| `/api/v1/missions` | MOD-201 | Account-profile-created missions with metric targets and rewards. | Defined | Metrics selectable per mission; account workspace must show rankings/progress. |
| `/api/v1/account_profile_links` | MOD-201 | Account profile ↔ curador/pessoa linkage. | Defined | Bidirectional proposals; statuses pending/accepted; monthly proof-of-presence window. |
| `/api/v1/discover/people` | MOD-201 | People/Influencer row ordered by monthly Social Score. | Defined | Prefer verified on ties; respects privacy by anonymizing friends-only profiles. |
| `/api/v1/discover/curator-content` | MOD-201 | Curator-produced content for “Veja isso…” row. | Defined | Ordered by latest published (future: most viewed); links to account profile/event. |
| `/api/v1/contacts/import` | MOD-201 | Hashed contact import for friend suggestions and invite matching. | Tested & Ready | Implemented in invites package; hashed-only matching + invite targeting path covered by invite feature tests. |
| `/api/v1/push/register` | MOD-201 | Register device token for push notifications. | Implemented | Stores per-device tokens; used for invites/reminders. |
| `/admin/api/v1/settings/schema` | Tenant Admin | Discover tenant settings schema (namespace metadata, nodes, conditional metadata). | Tested & Ready | Canonical settings-kernel discovery endpoint for tenant-admin scope; includes `schema_version` + stable node IDs. |
| `/admin/api/v1/settings/values` | Tenant Admin | Fetch tenant settings values for authorized namespaces. | Tested & Ready | Scope-aware values payload filtered by namespace abilities; tenant-admin local-preferences reads `map_ui.default_origin` from this surface. |
| `/admin/api/v1/settings/values/{namespace}` | Tenant Admin | Partial namespace PATCH (canonical contract). | Tested & Ready | Direct object payload only; field-presence merge; nullable-only `null` clear; non-nullable `null` => `422`; atomic mixed set+clear. Tenant-admin uses this contract for `map_ui.default_origin` and `app_links` (Android/iOS association credentials). |
| `/admin/api/v1/settings/{schema|values}` + `/admin/api/v1/settings/values/{namespace}` | Landlord Admin | Landlord-scope settings schema/values/PATCH. | Tested & Ready | Same canonical contract in landlord scope store. |
| `/admin/api/v1/{tenant_slug}/settings/{schema|values}` + `/admin/api/v1/{tenant_slug}/settings/values/{namespace}` | Landlord Admin (on behalf tenant) | Execute settings schema/values/PATCH against a tenant scope from landlord context. | Tested & Ready | Tenant scope resolution through adapter + canonical PATCH semantics. |
| `/api/v1/settings/push` | Tenant Admin | Update tenant push settings (push-only). | Implemented | Split from firebase/telemetry; returns push config payload. |
| `/api/v1/settings/firebase` | Tenant Admin | Update tenant firebase settings. | Implemented | Dedicated endpoint for firebase config. |
| `/api/v1/settings/telemetry` | Tenant Admin | Manage telemetry integrations (list + upsert). | Implemented | Upsert by `type`; unique types enforced. |
| `/api/v1/settings/telemetry/{type}` | Tenant Admin | Remove telemetry integration by type. | Implemented | DELETE removes a single type. |
| `/admin/api/v1/media/map-filter-image` | Tenant Admin | Upload tenant-scoped image used by `settings.map_ui.filters[].image_uri`. | Tested & Ready | Complements local-preferences map filter catalog editor; accepts authenticated multipart (`key`, `image`) and returns canonical `image_uri`. |
| `/api/v1/media/map-filters/{key}` | Tenant | Canonical public delivery for map filter images. | Tested & Ready | Returns tenant-scoped image bytes with `ETag`/`Last-Modified`; legacy `/map-filters/{key}/image` remains compatibility alias. |
| `/admin/api/v1/organizations` | Tenant Admin | List organizations (grouping only). | Implemented | Tenant‑scoped; landlord users only. Paged response with org metadata. |
| `/admin/api/v1/organizations` | Tenant Admin | Create organization. | Implemented | Tenant‑scoped; landlord users only. Minimal MVP fields: `name`, optional `description`. |
| `/admin/api/v1/organizations/{organization_id}` | Tenant Admin | Organization detail. | Implemented | Tenant‑scoped; landlord users only. Returns org metadata. |
| `/admin/api/v1/organizations/{organization_id}` | Tenant Admin | Update organization. | Implemented | Tenant‑scoped; landlord users only. Patch name/description. |
| `/admin/api/v1/organizations/{organization_id}` | Tenant Admin | Delete/restore organization. | Implemented | Soft delete + restore + force delete endpoints are live. |
| `/admin/api/v1/accounts` | Tenant Admin | List accounts (tenant-owned, unmanaged, user-owned). | Implemented | Tenant‑scoped; landlord users only. Read payload includes `ownership_state`; supports optional `ownership_state` filter. |
| `/admin/api/v1/accounts` | Tenant Admin | Manual create account (legacy). | Implemented | Project route override rejects with deterministic `409` and `meta.use_endpoint=/admin/api/v1/account_onboardings`. |
| `/admin/api/v1/account_onboardings` | Tenant Admin | Canonical manual onboarding create (account + role + account_profile). | Implemented | Tenant‑scoped; landlord users only. Required payload: `name`, `ownership_state`, `profile_type`; optional media (`avatar`/`cover`) + profile fields. |
| `/admin/api/v1/accounts/{account_slug}` | Tenant Admin | Fetch/update/delete account (partial). | Implemented | Uses `account_slug`; soft delete + restore + force delete endpoints are live. |
| `/admin/api/v1/account_profiles` | Tenant Admin | List account profiles. | Implemented | Tenant‑scoped; landlord users only. Paged response includes profile metadata + `ownership_state`. |
| `/admin/api/v1/account_profiles` | Tenant Admin | Manual create account profile (legacy). | Implemented | Project route override rejects with deterministic `409` and `meta.use_endpoint=/admin/api/v1/account_onboardings`. |
| `/admin/api/v1/account_profiles/{account_profile_id}` | Tenant Admin | Fetch/update/delete account profile. | Implemented | Tenant‑scoped; landlord users only. Soft delete + restore + force delete endpoints are live. |
| `/api/v1/media/account-profiles/{account_profile_id}/{avatar\|cover}` | Tenant | Canonical public delivery for account profile avatar/cover media. | Tested & Ready | Returns tenant-scoped image bytes with cache validators; legacy `/account-profiles/{account_profile}/avatar|cover` remains compatibility alias. |
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
| `/api/v1/media/static-assets/{static_asset_id}/{avatar\|cover}` | Tenant | Canonical public delivery for static asset avatar/cover media. | Tested & Ready | Returns tenant-scoped image bytes with cache validators; legacy `/static-assets/{static_asset}/avatar|cover` remains compatibility alias. |
| `/admin/api/v1/media/external-image` | Tenant Admin | Proxy external image URL to bytes for ingestion (URL import without CORS). | Tested & Ready | Authenticated + `CheckTenantAccess`; SSRF + size limits; returns raw bytes with `Cache-Control: no-store`. |
| `/api/v1/static_assets/{asset_ref}` | Tenant | Static Asset public read (page). | Tested & Ready | Returns the static asset page payload by id or slug. |
| `/admin/api/v1/events` | Tenant Admin | List events (admin). | Tested & Ready | Admin listing, page-based. |
| `/admin/api/v1/events` | Tenant Admin | Create event. | Tested & Ready | Admin/account profile creates event. |
| `/admin/api/v1/events/{event_id}` | Tenant Admin | Update event (partial). | Tested & Ready | Patch event metadata + schedule. |
| `/admin/api/v1/branding/update` | Tenant Admin | Update tenant branding settings. | Implemented | Drives `/environment` payload + asset paths; route registered in `routes/api/tenant_api_v1.php`. Contract scope is `theme_data_settings` + `logo_settings`/`pwa_icon` (tenant name is not persisted by this endpoint). |

## 4. Risk & Mitigation Log

| ID | Risk | Impact | Mitigation |
|----|------|--------|------------|
| R-201-01 | Flutter contract drift from backend payloads/adapters. | Runtime regressions on live tenant flows. | Maintain contract tests, enforce live-only runtime adapters, and share DTO schemas with backend team. |
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

**Initial stance (V1):** Web is a promotional/read-only surface; the app owns conversion through progressive profiling (anonymous-first, auth-later).

**Web allowed (V1):**
- Event landing (read-only): title, date/time, venue name, artists names, hero media.
- Invite landing (read-only): “You were invited by …” context + event summary.
- Map browsing (read-only) for discovery; guide users into app for confirmations.
- Install/Open-App CTA must preserve invite share code attribution (`code`) and promote app conversion (`Baixe o App para Confirmar`).
- Web invite surfaces cannot accept/decline invites in V1.
- Web “unauthenticated” surfaces must not mint anonymous identities for invite conversion.

**Web authenticated allowed (V1):**
- Tenant/Admin area: accounts, events, assets, and branding management.

**App-only (V1):**
- Deferred deep-link first-open capture for invite `code` (critical funnel step).
- Anonymous identity bootstrap (device-bound) and anonymous invite acceptance via canonical `POST /api/v1/invites/{invite_id}/accept`.
- Feed/map read-only navigation for anonymous users after acceptance.
- Auth Wall hard-gates: favorites, send-invite actions, and presence/check-in boundaries.

**Attribution requirement:** External share links carry a single `code` as a GET param that resolves `{ tenant_id, event_id, inviter_principal }`. Web/store/app flows must preserve this `code` through install and first open. App may accept anonymously directly from share preview; authenticated continuation flows may still materialize through `POST /api/v1/invites/share/{code}/materialize` before decision UI when needed. Anonymous-to-authenticated merge must preserve invite attribution/history.

**Tracking mandate (V1):** Instrument inverted funnel `landing -> install -> deferred deep link captured -> anonymous accept -> auth wall triggered -> signup completed`, with deterministic event naming and deduplication.

**Future consideration:** Revisit after Phase 8 once viral loops and account profile analytics are stable. Any future web mutation expansion requires explicit contract/module/TODO updates with equivalent security and attribution guarantees.
