# TODO (V1): Map — Backend (POIs + Projection + Static Assets)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Backend Team  
**Objective:** Deliver the projection-backed Map POIs APIs, SSE deltas, and tenant-admin Static Asset CRUD.

---

## References
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-backend-wiring-consolidation.md`
- `foundation_documentation/todos/active/vnext_slices/About t.md` (Route Paths Migration Guide)

---

## A) Backend Tasks

### A0) Decision Notes (Contracts + Access)
- [x] ✅ Production‑Ready Confirm canonical paths per route migration guide: `/api/v1/map/pois`, `/api/v1/map/filters`, `/api/v1/map/pois/stream` (tenant domain only; no `/api/v1/app/*`).
- [x] ✅ Production‑Ready Define `map_pois` schema contract (fields + enums) in `foundation_documentation/modules/map_poi_module.md` and `endpoints_mvp_contracts.md`.
- [x] ✅ Production‑Ready Document `/map/pois`, `/map/filters`, and `/map/pois/stream` request/response contracts (query params, **taxonomy filters**, response shape, SSE payload).
- [x] ✅ Production‑Ready Remove `movement_radius_meters` from POI contracts/mocks (moving POIs deferred; keep V1 static/event focus).
- [x] ✅ Production‑Ready Define POI source rules by `account_profile.profile_type` (which types are POI-enabled) and where the toggle lives.
- [x] ✅ Production‑Ready Make the projection rule explicit: **no location → no POI projection** for account profiles.
- [x] ✅ Production‑Ready Set priority policy defaults (numeric scale + initial ordering) for POI stacking.
- [x] ✅ Production‑Ready Document access/scoping (tenant vs admin) for map endpoints:
  - [x] ✅ Production‑Ready `/api/v1/map/*` = tenant domain + `auth:sanctum` + account-accessible (tenant-public scope, not main domain).
  - [x] ✅ Production‑Ready Admin CRUD (Static Assets) = tenant-admin scope under `/admin/api/v1/*`.
- [x] ✅ Production‑Ready MVP: map queries **must** filter by `is_active` only (manual toggle in MVP).
- [x] ✅ Production‑Ready Map filters must include **taxonomy terms** (type + values) when the registry exposes them.

### A1) `map_pois` projection persistence (Jobs)
- [x] ✅ Production‑Ready On create/update/delete/restore of Account Profile, StaticAsset, or Event (and POI-enabled custom objects), enqueue Jobs to upsert/remove linked `map_pois` record (no inline projection writes). Account is not a POI source.
- [x] ✅ Production‑Ready Support `time_anchor_at` nullable on `map_pois` (no stored `visible_from/visible_until`).
- [x] ✅ Production‑Ready Implement tenant settings for time-window filtering:
  - [x] ✅ Production‑Ready `settings.map_ui.poi_time_window_hours.future` (default: 720)
  - [x] ✅ Production‑Ready `settings.map_ui.poi_time_window_hours.past` (default: 6)

### A2) Time-window filtering (query-time)
- [x] ✅ Production‑Ready When fetching POIs, include time-anchored POIs only when:
  - [x] ✅ Production‑Ready `time_anchor_at <= now + future_window_hours`
  - [x] ✅ Production‑Ready `time_anchor_at >= now - past_window_hours`
- [x] ✅ Production‑Ready POIs without `time_anchor_at` remain eligible (subject to `is_active`, viewport, and filters).

### A2.1) Realtime deltas (SSE)
- [x] ✅ Production‑Ready Expose `/api/v1/map/pois/stream` with delta events (created/updated/deleted).
- [x] ✅ Production‑Ready Stream filters match `/api/v1/map/pois` (viewport, categories, **taxonomy**, tags, search, geo).

### A3) Same-spot stacking (V1 exact-key)
- [x] ✅ Production‑Ready Normalize coordinates on write (fixed precision, e.g., 6 decimals).
- [x] ✅ Production‑Ready Derive/store an `exact_key` from normalized coordinates (e.g., `"lat,lng"`).
- [x] ✅ Production‑Ready Map endpoint returns stacks grouped by `exact_key`:
  - [x] ✅ Production‑Ready `stack_key`, `center`, `top_poi`, `stack_count`, `items[]`
- [x] ✅ Production‑Ready Ensure deterministic ordering within stack:
  - [x] ✅ Production‑Ready sort by `priority`, then stable tiebreaker (`ref_type` precedence + `ref_id`).

### A4) Performance/indexing
- [x] ✅ Production‑Ready 2dsphere index on `map_pois.location`.
- [x] ✅ Production‑Ready Index strategy supports `is_active`, `category`, and `time_anchor_at` filters used by the map endpoint (tenant DB isolation).

### A5) Static Assets (Admin CRUD)
- [x] ✅ Production‑Ready Add `static_assets` collection + model + migration + indexes (2dsphere on location; category/is_active index).
- [x] ✅ Production‑Ready Admin CRUD endpoints under `/admin/api/v1/static_assets` (tenant domain).
- [x] ✅ Production‑Ready Validation rules: name required; category required; location required; tags/taxonomy_terms optional; priority + is_active defaulted.
- [x] ✅ Production‑Ready Enforce tenant-admin abilities (`assets:*`) for create/update/delete/restore.
- [x] ✅ Production‑Ready Ensure Static Asset CRUD triggers `map_pois` projection Jobs (including `is_active` toggles).
- [x] ✅ Production‑Ready Tests: CRUD, validation, ability denial, projection side effects.

### A6) Map endpoints (implementation)
- [x] ✅ Production‑Ready `GET /api/v1/map/pois`: projection-backed POI feed with viewport/geo filters, time window enforcement, and stack grouping.
- [x] ✅ Production‑Ready `GET /api/v1/map/filters`: return category/tag/taxonomy catalogs using the same filter constraints.
- [x] ✅ Production‑Ready `GET /api/v1/map/pois/stream`: SSE delta stream aligned to `/map/pois` filters.
- [x] ✅ Production‑Ready Validation enforces bounded inputs (P-14) for search, tags, taxonomy, and viewport params.
- [x] ✅ Production‑Ready Ensure tenant access guardrails (`auth:sanctum` + `CheckTenantAccess`) apply to `/api/v1/map/*` routes.

---

## B) Acceptance Criteria
- [x] ✅ Production‑Ready Map POIs return stack groups with deterministic ordering and `+N` stack counts.
- [x] ✅ Production‑Ready Event POIs appear only within backend-defined time windows.
- [x] ✅ Production‑Ready Static Asset CRUD updates `map_pois` (upsert/remove via Jobs).
- [x] ✅ Production‑Ready SSE emits `poi.created`, `poi.updated`, and `poi.deleted` events for active filters.

---

## C) Out of Scope
- Flutter map UI/UX changes.
- VNext clustering (geohash/H3) beyond exact-key stacks.
- Moving POIs / live offers beyond SSE deltas.

---

## D) Definition of Done
- [x] ✅ Production‑Ready `/map/pois`, `/map/filters`, `/map/pois/stream` implemented with Sanctum auth and tenant guardrails.
- [x] ✅ Production‑Ready `map_pois` projection Jobs handle create/update/delete/restore for all POI-enabled sources.
- [x] ✅ Production‑Ready Static Assets CRUD is live under tenant-admin scope and wired to projection jobs.
- [x] ✅ Production‑Ready Time-window filtering uses tenant settings and keeps `is_active` as the only hard visibility toggle.
- [x] ✅ Production‑Ready Tests cover routing, validation, and projection integrity.

---

## E) Validation Steps
- [x] ✅ Production‑Ready `php artisan test --filter=MapPoi` (or targeted map/asset tests once created).
- [ ] ⚪ Manual smoke: fetch `/api/v1/map/pois` with viewport + origin to verify distance/stack payloads.
- [ ] ⚪ Manual smoke: create/update/delete static asset and confirm map POI update.

---

## F) Decisions to Close (Proposals)

### D1) `map_pois` schema minimum (proposed)
- Required: `_id`, `tenant_id`, `ref_type`, `ref_id`, `name`, `category`, `tags[]`, `priority`, `location`, `is_active`.
- Optional: `time_anchor_at`, `distance_meters` (response-only), `exact_key`, `media`, `badge`, `subtitle`.

### D1.1) Radius semantics + “Search this area” (proposed)
- `max_distance_meters` is always anchored to a reference origin (`origin_lat/lng`), not auto-updated as the user pans.
- Panning the map does not change origin until the user taps **Search this area** (sets origin to new center + refetches).
- Tenant settings (nested) define defaults and bounds for radius:
  - `map_ui.radius.min_km` (default: 1)
  - `map_ui.radius.default_km` (default: 5)
  - `map_ui.radius.max_km` (default: 50)
  - Optional `map_ui.default_location` with `{ lat, lng }` for initial origin when user location is unavailable.

### D1.2) `distance_meters` contract (proposed)
- If `origin_lat/lng` is provided, the backend **must** include `distance_meters` on each POI in the response.
- When origin is absent, `distance_meters` may be omitted.

### D1.3) POI registry + taxonomy registry (proposed)
- Define a POI type registry (`poi_types`) and taxonomy/terms registry for normalization and routing.
- Reads **never** run pipelines; they fetch normalized POI records by `ref_type/ref_id` and `slug`.

### D1.4) Map endpoint canonical path (proposed)
- Standardize the map POI endpoint as `/api/v1/map/pois` (deprecate `/api/v1/app/map/pois`).

### D1.5) POI enablement defaults (approved)
- Account Profile types default allowlist for POI projection (MVP): `venue`, `restaurant`, `experience_provider`.
- `artist`, `influencer`, `curator` are POI-disabled by default, toggled via tenant settings or profile capability.

### D2) POI-enabled profile types (approved)
- POI-enabled by default: `venue`, `restaurant`, `experience_provider`.
- POI-disabled by default: `artist`, `influencer`, `curator` (unless explicitly toggled).

### D5) Static Assets remain separate (decision)
- Static Assets are **not** Account Profiles. They project into `map_pois` with `ref_type=static`.
- They are tenant/landlord-managed POIs without operator linkage or invite/favorite semantics.

### D3) Priority defaults (proposed)
- 100: Sponsored/boosted  
- 80: Live event  
- 60: Upcoming event  
- 40: Static POI (venue/restaurant)  
- 20: Landmark/static asset

### D4) Endpoint access (proposed)
- `/map/pois` and `/map/filters`: tenant-authenticated (Sanctum) + account-accessible. Filters must include taxonomy types/values when available.
- Admin endpoints (if any): tenant/admin only.
