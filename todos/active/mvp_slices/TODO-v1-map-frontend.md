# TODO (V1): Map — Frontend (Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Deliver the Map UX (POIs, filters, stacking, deep links) aligned to backend contracts.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-backend.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`

---

## A) UI + Controller Tasks

### A1) Map rendering + filters
- [ ] ⚪ Render static POIs for categories: `Culture`, `Restaurant`, `Beach`, `Nature`, `Historic`.
- [ ] ⚪ Render dynamic Event POIs distinctly from static POIs.
- [ ] ⚪ Keep categories coarse; use tags for subcategories (no enum expansion in V1).
  - StaticAsset and Event are POI-enabled sources; Account/Account Profile is conditional per MVP scope.
- [ ] ⚪ If taxonomy filters are returned, render them under Map filters (grouped by taxonomy type).
- [ ] ⚪ MVP tiles: use public OpenStreetMap tiles (no key), with explicit limitation to dev + early MVP.
- [ ] ⚪ Migrate map rendering from `free_map` to `flutter_map` to unblock `package_info_plus` upgrades.
- [x] ✅ Expose both map implementations via Menu actions (City map + Prototype) for comparison before removing one.
- [x] ✅ Remove unused City map artifacts (routes/screens/widgets/controllers) after comparison decision.
- [x] ✅ Rename remaining prototype map files/paths to production naming (remove “prototype”).

### A1.1) Endpoint usage notes
- [ ] ⚪ Use `/api/v1/map/pois` with filters: `viewport`, `categories[]`, `tags[]`, `taxonomy[]`, `search`, `origin_lat`, `origin_lng`, `max_distance_meters`.
  - Reference: `foundation_documentation/endpoints_mvp_contracts.md` (Map + POIs section).
  - Reference: `foundation_documentation/modules/map_poi_module.md` (stacking + time window rules).
- [ ] ⚪ Connect SSE to `/api/v1/map/pois/stream`; on reconnect without `Last-Event-ID`, refetch `/map/pois`.
- [ ] ⚪ Use `/api/v1/map/filters` for category/tag/taxonomy discovery (remove hardcoded filter catalogs).
- [ ] ⚪ Requests include `Authorization: Bearer <AuthRepository.userToken>` and `Accept: application/json`.

### A2) Same-spot UX
- [ ] ⚪ Marker shows top POI + `+N` badge when stack has multiple items.
- [ ] ⚪ Tapping opens POI deck/selector listing stack items ordered by priority.
- [ ] ⚪ Selecting an item pins selection until cleared or refreshed.

### A3) Deep links
- [ ] ⚪ For POI with `ref_type=event`, route to event detail.
- [ ] ⚪ For other POIs, route to POI details screen.

---

## B) Acceptance Criteria
- [ ] ⚪ Beaches and Nature POIs appear as static POIs and are filterable.
- [ ] ⚪ Event POIs appear only within backend-defined time windows (via settings).
- [ ] ⚪ Same-spot POIs stack with `+N` badge and open a POI deck with deterministic ordering.

---

## C) Out of Scope
- Admin CRUD UI for Static Assets (tenant-admin workspace).
- VNext clustering beyond exact-key stacks.

---

## D) Definition of Done
- [ ] ⚪ Map screen is wired to `/api/v1/map/pois` + `/api/v1/map/filters` + `/api/v1/map/pois/stream` with auth headers.
- [ ] ⚪ Stacking UX works end-to-end (marker badge + deck list + selection pin).
- [ ] ⚪ Category filters show static + event POIs with taxonomy filters when available.

---

## E) Validation Steps
- [ ] ⚪ `fvm flutter analyze`.
- [ ] ⚪ Manual smoke: open map, toggle filters, open stack deck, deep link to event.
- [ ] ⚪ Manual smoke: disconnect/reconnect SSE and confirm refresh.

---

## Backend constraints to respect
- `distance_meters` is present when `origin_lat/lng` are sent.
- Stack payload uses `stack_key`, `center`, `top_poi`, `stack_count`, `items[]`.
- Endpoint scope is tenant-domain only; account tokens are allowed.
