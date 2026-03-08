# TODO (V1): Map — Frontend (Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Deliver the Map UX (POIs, filters, stacking, deep links) aligned to backend contracts.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-map-backend.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`

## Canonical Module Anchors
- **Primary module:** `foundation_documentation/modules/map_poi_module.md`
- **Secondary modules/contracts:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- **Promotion targets after delivery:**
  - `foundation_documentation/modules/map_poi_module.md` (frontend consumption notes)
  - `foundation_documentation/modules/flutter_client_experience_module.md` (`2.2 API Endpoint Definitions`, tactical ledger)
  - `foundation_documentation/system_roadmap.md` (map frontend status)

## Scope (Current Implementation Slice)
- Remove POI/map/events mock data paths from the active map runtime in Flutter.
- Wire map consumption to tenant-authenticated backend contracts (`/api/v1/map/pois`, `/api/v1/map/filters`).
- Stop client-side synthetic event POI composition from schedule mocks; treat backend `map_pois` as the source of truth for map marker inventory.
- Implement stack-aware parsing (`stacks`, `top_poi`, optional `items`) so map markers and selection logic consume backend payloads deterministically.

## Out of Scope (This Slice)
- Tenant-admin map CRUD work.
- VNext map clustering beyond `exact_key` stack semantics.
- Backend schema redesign for `map_pois` (already package-owned in backend TODO).
- New map visual redesign; this slice is contract wiring + architecture adherence.

## Complexity Classification + Checkpoint Policy
- **Complexity:** `big`
- **Checkpoint policy:** section-by-section checkpoints before production-ready mark:
  1. Contracts + DTO parsing
  2. Repository/controller migration off mocks
  3. Screen/widget stack behavior validation
  4. Analyzer + test evidence + decision adherence validation

## Plan Review Gate (Big)

### Issue Cards

#### Issue ID: MAP-FE-01
- **Severity:** High
- **Evidence:** `lib/infrastructure/services/http/laravel_map_poi_http_service.dart`, `lib/infrastructure/repositories/city_map_repository.dart`
- **Why now:** Current POI runtime still falls back to local mocks and uses stale endpoint/shape assumptions, preventing full backend reliance.
- **Option A:** Keep fallback to mock on backend failure.
  - Effort: low
  - Risk: high (silent contract drift)
  - Blast radius: medium
  - Maintenance burden: high
- **Option B (Recommended):** Remove mock fallback from active runtime and fail fast with clear error state.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Keep fallback gated by debug-only feature flag.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: medium

#### Issue ID: MAP-FE-02
- **Severity:** High
- **Evidence:** `lib/infrastructure/repositories/poi_repository.dart` (`_fetchEventPois`), `lib/presentation/tenant_public/map/screens/map_screen/widgets/*`
- **Why now:** Event POIs are still synthesized client-side via schedule sources, violating backend-owned `map_pois` projection authority.
- **Option A:** Keep client-side event composition.
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high
- **Option B (Recommended):** Remove client event composition and consume event POIs only from `/map/pois`.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Keep both sources with merge/dedupe in client.
  - Effort: high
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high

#### Issue ID: MAP-FE-03
- **Severity:** Medium
- **Evidence:** `lib/infrastructure/dal/dto/map/city_poi_dto.dart`, `lib/presentation/tenant_public/map/screens/map_screen/widgets/map_layers.dart`
- **Why now:** Backend stack contract fields (`stack_key`, `stack_count`, `top_poi`, optional `items`) are not represented in frontend DTO/domain flow.
- **Option A:** Ignore stack fields and flatten top POIs only.
  - Effort: low
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: medium
- **Option B (Recommended):** Add stack metadata support in DTO/domain mapping and marker rendering (`+N` badge, deterministic deck source).
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Block release until backend exposes a separate richer stack-details endpoint.
  - Effort: low (frontend), high (cross-team)
  - Risk: medium
  - Blast radius: high
  - Maintenance burden: medium

#### Issue ID: MAP-FE-04
- **Severity:** Medium
- **Evidence:** `foundation_documentation/endpoints_mvp_contracts.md` (`/map/pois/stream` MVP note deferred), `TODO-v1-map-frontend.md` A1.1
- **Why now:** TODO currently treats SSE as required while canonical MVP contract marks SSE deferred unless polling is insufficient.
- **Option A (Recommended):** Mark SSE integration as deferred for this slice; keep TODO reference but do not block production-ready on SSE implementation.
  - Effort: low
  - Risk: low
  - Blast radius: low
  - Maintenance burden: low
- **Option B:** Implement SSE now.
  - Effort: high
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: medium
- **Option C:** Keep ambiguous requirement.
  - Effort: none
  - Risk: high
  - Blast radius: medium
  - Maintenance burden: high

### Failure Modes & Edge Cases
- Backend returns empty stacks for a valid viewport.
- Backend returns category values outside current client enum.
- Stack with `stack_count > 1` but no `items` in non-expanded response.
- Event POI navigation receives `ref_id` but not slug.
- User token expires mid-session while map requests are in flight.
- Missing origin bounds leads to unexpectedly broad payload and UI overload.

### Uncertainty Register
- **Assumptions:**
  - Backend `/map/pois` + `/map/filters` are available and tenant-authenticated in target environments.
  - `ref_id` is accepted by event detail resolver path used by Flutter.
- **Unknowns:**
  - Whether stack item payload fields are sufficient for current card richness without additional fetches.
  - Whether category set may include `event` consistently across tenants.
- **Confidence:** `medium`

## Decision Baseline (Frozen)
- `D-01` Use backend `/api/v1/map/pois` + `/api/v1/map/filters` as mandatory source; no silent mock fallback in active runtime.
- `D-02` Remove client-side event POI synthesis (`ScheduleRepository` composition) from map inventory.
- `D-03` Implement stack metadata support (`stack_key`, `stack_count`, optional expanded `items`) in frontend model + marker rendering.
- `D-04` Treat `/map/pois/stream` as deferred for this slice (MVP contract note); do not block production-ready on SSE.
- `D-05` Include authenticated headers (`Authorization`, `Accept`) for map endpoints using `AuthRepository.userToken`.

## Module Coherence Gate (Pre-Implementation)
| Decision ID | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | Aligned | Preserve | `foundation_documentation/endpoints_mvp_contracts.md` (`GET /map/pois`, `GET /map/filters`) |
| `D-02` | Aligned | Preserve | `foundation_documentation/modules/map_poi_module.md` section `3.6` (`map_pois` projection as source of truth) |
| `D-03` | Aligned | Preserve | `foundation_documentation/modules/map_poi_module.md` section `3.7` (same-spot stacking) |
| `D-04` | Aligned | Preserve | `foundation_documentation/endpoints_mvp_contracts.md` (`/map/pois/stream` deferred note) |
| `D-05` | Aligned | Preserve | `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md` A1.1 auth header note |

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
- [ ] ⚪ (Deferred in this slice) Connect SSE to `/api/v1/map/pois/stream`; on reconnect without `Last-Event-ID`, refetch `/map/pois`.
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
- [ ] ⚪ Manual smoke: disconnect/reconnect network and confirm map reload path without mock fallback.

---

## Decision Adherence Validation (To Fill Before Delivery)
| Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` |  |  |  |
| `D-02` |  |  |  |
| `D-03` |  |  |  |
| `D-04` |  |  |  |
| `D-05` |  |  |  |

---

## Backend constraints to respect
- `distance_meters` is present when `origin_lat/lng` are sent.
- Stack payload uses `stack_key`, `center`, `top_poi`, `stack_count`, `items[]`.
- Endpoint scope is tenant-domain only; account tokens are allowed.
