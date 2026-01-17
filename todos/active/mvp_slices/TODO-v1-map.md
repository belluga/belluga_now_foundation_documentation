# TODO (V1): Map (POIs + Events + Stacking)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter)  
**Objective:** Ship the V1 map experience with static POIs (beach/nature/etc.), dynamic event POIs, and same-spot stacking (`+N` badge + POI deck).

---

## References
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- Deferred items: `foundation_documentation/todos/active/TODO-vnext-parking-lot.md`

---

## A) Backend Tasks

### A0) Decision Notes
- [ ] ⚪ Map POI endpoint contract: confirm whether the intended path is `/api/v1/map/pois` (roadmap) or `/api/v1/app/map/pois` (current implementation), then align.
- [ ] ⚪ Define `map_pois` schema contract (fields + enums) in `foundation_documentation/modules/map_poi_module.md` and `endpoints_mvp_contracts.md`.
- [ ] ⚪ Document `/map/pois`, `/map/filters`, and `/map/pois/stream` request/response contracts (query params, filters, response shape, SSE payload).
- [ ] ⚪ Remove `movement_radius_meters` from POI contracts/mocks (moving POIs deferred; keep V1 static/event focus).
- [ ] ⚪ Define POI source rules by `account_profile.profile_type` (which types are POI-enabled) and where the toggle lives.
- [ ] ⚪ Make the projection rule explicit: **no location → no POI projection** for account profiles.
- [ ] ⚪ Set priority policy defaults (numeric scale + initial ordering) for POI stacking.
- [ ] ⚪ Document access/scoping (tenant vs admin) for map endpoints.

### A1) `map_pois` projection persistence
- [ ] ⚪ On create/update of Account/Account Profile, StaticAsset, or Event (and POI-enabled custom objects), upsert linked `map_pois` record (transactional / consistent write)
- [ ] ⚪ Support `time_anchor_at` nullable on `map_pois` (no stored `visible_from/visible_until`)
- [ ] ⚪ Implement tenant settings for time-window filtering:
  - [ ] ⚪ `map_poi_future_window_days`
  - [ ] ⚪ `map_poi_past_window_days`

### A2) Time-window filtering (query-time)
- [ ] ⚪ When fetching POIs, include time-anchored POIs only when:
  - [ ] ⚪ `time_anchor_at <= now + future_window_days`
  - [ ] ⚪ `time_anchor_at >= now - past_window_days`
- [ ] ⚪ POIs without `time_anchor_at` remain eligible (subject to `is_active`, viewport, and filters)

### A2.1) Realtime deltas (SSE)
- [ ] ⚪ Expose `/api/v1/map/pois/stream` with delta events (created/updated/deleted)
- [ ] ⚪ Stream filters match `/api/v1/map/pois` (viewport, categories, tags, search, geo)

### A3) Same-spot stacking (V1 exact-key)
- [ ] ⚪ Normalize coordinates on write (fixed precision, e.g., 6 decimals)
- [ ] ⚪ Derive/store an `exact_key` from normalized coordinates (e.g., `"lat,lng"`)
- [ ] ⚪ Map endpoint returns stacks grouped by `exact_key`:
  - [ ] ⚪ `stack_key`, `center`, `top_poi`, `stack_count`, `items[]`
- [ ] ⚪ Ensure deterministic ordering within stack:
  - [ ] ⚪ sort by `priority`, then stable tiebreaker (`ref_type` precedence + `ref_id`)

### A4) Performance/indexing
- [ ] ⚪ 2dsphere index on `map_pois.location`
- [ ] ⚪ Index strategy supports `tenant_id`, `is_active`, `category`, and `time_anchor_at` filters used by the map endpoint

---

## B) Flutter Tasks

### B1) Map rendering + filters
- [ ] ⚪ Render static POIs for categories: `Culture`, `Restaurant`, `Beach`, `Nature`, `Historic`
- [ ] ⚪ Render dynamic Event POIs distinctly from static POIs
- [ ] ⚪ Keep categories coarse; use tags for subcategories (no enum expansion in V1)
  - StaticAsset and Event are POI-enabled sources; Account/Account Profile is conditional per MVP scope.
- [ ] ⚪ MVP tiles: use public OpenStreetMap tiles (no key), with explicit limitation to dev + early MVP.
- [ ] ⚪ Migrate map rendering from `free_map` to `flutter_map` to unblock `package_info_plus` upgrades.
- [x] ✅ Expose both map implementations via Menu actions (City map + Prototype) for comparison before removing one.
- [x] ✅ Remove unused City map artifacts (routes/screens/widgets/controllers) after comparison decision.
- [x] ✅ Rename remaining prototype map files/paths to production naming (remove “prototype”).

### B2) Same-spot UX
- [ ] ⚪ Marker shows top POI + `+N` badge when stack has multiple items
- [ ] ⚪ Tapping opens POI deck/selector listing stack items ordered by priority
- [ ] ⚪ Selecting an item pins selection until cleared or refreshed

### B3) Deep links
- [ ] ⚪ For POI with `ref_type=event`, route to event detail
- [ ] ⚪ For other POIs, route to POI details screen

---

## C) Acceptance Criteria

- [ ] ⚪ Beaches and Nature POIs appear as static POIs and are filterable
- [ ] ⚪ Event POIs appear only within backend-defined time windows (via settings)
- [ ] ⚪ Same-spot POIs stack with `+N` badge and open a POI deck with deterministic ordering

---

## D) Decisions to Close (Proposals)

### D1) `map_pois` schema minimum (proposed)
- Required: `_id`, `tenant_id`, `ref_type`, `ref_id`, `name`, `category`, `tags[]`, `priority`, `location`, `is_active`.
- Optional: `time_anchor_at`, `distance_meters` (response-only), `exact_key`, `media`, `badge`, `subtitle`.

### D2) POI-enabled profile types (proposed)
- POI-enabled by default: `venue`, `restaurant`, `experience_provider`.
- POI-disabled by default: `artist`, `influencer`, `curator` (unless explicitly toggled).

### D3) Priority defaults (proposed)
- 100: Sponsored/boosted  
- 80: Live event  
- 60: Upcoming event  
- 40: Static POI (venue/restaurant)  
- 20: Landmark/static asset

### D4) Endpoint access (proposed)
- `/map/pois` and `/map/filters`: tenant-authenticated (app) + anonymous token allowed for read-only discovery.
- Admin endpoints (if any): tenant/admin only.
