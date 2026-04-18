# TODO (V1): Map — Frontend (Flutter)

**Closure note (2026-04-17):** the contract-wiring frontend slice is complete. The only remaining `free_map -> flutter_map` / Belluga-owned map-surface work was explicitly promoted into `foundation_documentation/todos/active/vnext/TODO-v1-map-visuals.md`.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Completed  
**Owners:** Flutter Team  
**Objective:** Deliver the Map UX (POIs, filters, stacking, deep links) aligned to backend contracts.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-map-backend.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/completed/TODO-v1-first-release.md`

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
- Plugin migration / Belluga-owned map surface work after contract parity is established. That remaining debt is promoted into `TODO-v1-map-visuals.md`.

## Operator-Reported Deviations (Session Log)
- [x] ✅ `MAP-UX-01` Restore filter label behavior from previous UX baseline.
  - Initial report: filter label disappeared after recent map changes.
  - Refinement: keep event visual emphasis, but do not regress label visibility/consistency.
- [x] ✅ `MAP-DATA-01` Event filter returns error while carousel still shows stale items.
  - Initial report: tapping `Eventos` shows `Aplicando filtros...` then `Não foi possível carregar os pontos de interesse`, while carousel still renders stale event cards.
  - Refinement: map markers/deck must stay consistent on fetch failures; no stale inventory after failed filter reload.
- [x] ✅ `MAP-CATALOG-01` Remove implicit/custom runtime filters.
  - Initial report: "custom filter" should not exist.
  - Refinement: filter surfaces must come from tenant-registered filter catalog only.
- [x] ✅ `MAP-CATALOG-02` Admin filter definition must be semantically valid (not only key/label/image).
  - Initial report: added filters are not correctly handled because admin cannot define effective filter semantics.
  - Refinement: admin filter setup must allow valid source/typing/taxonomy definition aligned to backend query mapping.
- [x] ✅ `MAP-MARKER-01` Revalidate zoom scaling parity between event and non-event markers.
  - Initial report: event marker circle seemed not to follow zoom scaling like regular POIs.
  - Refinement: event markers may keep visual prominence, but still must respect proportional zoom behavior.
- [x] ✅ `MAP-CATALOG-03` Do not auto-materialize filters from account/asset data presence.
  - Refinement report: creating a restaurant account must **not** auto-add a restaurant filter; active filters must be strictly derived from `settings.map_ui.filters`.
- [x] ✅ `MAP-CATALOG-04` Two persisted filters exist in admin settings but only one is rendered in map FAB.
  - Refinement: render must mirror configured list order and include entries with `count=0`.
- [x] ✅ `MAP-CATALOG-05` Remove hardcoded icon/color fallback by category key in map FAB.
  - Refinement: icon image must come from configured filter image; visual fallback must be generic and non-keyed.
- [x] ✅ `MAP-CATALOG-06` Map FAB displays filter `key` where `label` should be shown.
  - Refinement: button text must prioritize backend/admin `label` and only fallback to `key` when label is absent.
- [x] ✅ `MAP-CATALOG-07` Filter image now replaces the FAB icon reliably, and image updates propagate when the admin changes the filter card image.
  - Incremental report: changing the configured filter image does not update the rendered image in settings/map as expected.
  - Refinement: filter image must be the single visual source for the FAB icon, and image replacements must invalidate stale rendering so the updated image appears in admin preview and public map flow.
- [x] ✅ `MAP-UX-02` Catalog filter buttons must follow the same condensed/expanded animation pattern used by existing FAB actions.
  - Refinement: maintain expanded label, then condense to icon-only after delay.
- [x] ✅ `MAP-UX-03` Selected filter visual state restored with perceptible contrast against unselected filters.
  - Incremental report: selected and unselected filter buttons now look equal or nearly equal.
  - Refinement: active filter state must have clear visual distinction in both expanded and condensed FAB modes, matching the previous UX baseline.
- [x] ✅ `MAP-DATA-02` `source=event` filter without `types` must return all eligible events and proper card payloads.
  - Refinement: avoid fallback card text pattern (`POI <id>`) when backend textual payload exists.
- [x] ✅ `MAP-DATA-03` Asset-backed catalog filters now constrain map/deck results correctly.
  - Incremental report: the `event` filter is effectively filtering events, but the `assets` filter is returning unfiltered or mismatched results.
  - Refinement: selecting an asset-backed configured filter must restrict both markers and deck/cards to the configured asset query semantics only.
- [x] ✅ `MAP-ASYNC-01` Rapid filter taps create concurrent requests and repeated status messages.
  - Refinement: enforce interaction lock while applying filter + last-request-wins semantics + status message dedupe.
- [x] ✅ `MAP-DYNAMIC-01` Prevent hardcoded category/type assumptions that conflict with dynamic types/taxonomies catalog.
  - Refinement: source/types/taxonomy handling must remain backend-driven and dynamic-safe.
- [x] ✅ `MAP-TEST-01` Add automated coverage (feature/integration/unit) for catalog rendering parity, event filter contract, and async interaction guards.
  - Refinement: include test evidence for two configured filters visible, stable status messaging, and consistent map/deck payload rendering.
- [x] ✅ `MAP-TEST-02` Added E2E coverage for asset-backed filter correctness.
  - Refinement: integration flow must prove that an asset-configured filter changes both map markers and deck/cards, not only event-backed filters.
- [x] ✅ `MAP-TEST-03` Added E2E coverage for filter image replacement/refresh.
  - Refinement: integration flow must prove `upload/change image -> settings preview updates -> public map FAB updates`, including cache-busting on image replacement.
- [x] ✅ `MAP-TEST-04` Added coverage for active-vs-inactive filter visual contrast.
  - Refinement: tests must prove selected filters render with a clearly different visual state from unselected ones in the map FAB.

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
| `D-05` | Aligned | Preserve | `foundation_documentation/todos/active/vnext/TODO-v1-map-frontend.md` A1.1 auth header note |

---

## A) UI + Controller Tasks

### A1) Map rendering + filters
- [x] ✅ Production‑Ready Render static POIs for categories: `Culture`, `Restaurant`, `Beach`, `Nature`, `Historic`.
- [x] ✅ Production‑Ready Render dynamic Event POIs distinctly from static POIs.
- [x] ✅ Production‑Ready Keep categories coarse; use tags for subcategories (no enum expansion in V1).
  - StaticAsset and Event are POI-enabled sources; Account/Account Profile is conditional per MVP scope.
- [x] ✅ Production‑Ready If taxonomy filters are returned, render them under Map filters (grouped by taxonomy type).
- [x] ✅ Production‑Ready MVP tiles: use public OpenStreetMap tiles (no key), with explicit limitation to dev + early MVP.
- [x] ✅ Production‑Ready Remaining `free_map -> flutter_map` migration debt was promoted into `foundation_documentation/todos/active/vnext/TODO-v1-map-visuals.md` because camera/marker/cluster work now shares the same execution seam as the visual redesign and Belluga-owned map surface.
- [x] ✅ Expose both map implementations via Menu actions (City map + Prototype) for comparison before removing one.
- [x] ✅ Remove unused City map artifacts (routes/screens/widgets/controllers) after comparison decision.
- [x] ✅ Rename remaining prototype map files/paths to production naming (remove “prototype”).

### A1.1) Endpoint usage notes
- [x] ✅ Production‑Ready Use `/api/v1/map/pois` with filters: `viewport`, `categories[]`, `tags[]`, `taxonomy[]`, `search`, `origin_lat`, `origin_lng`, `max_distance_meters`.
  - Reference: `foundation_documentation/endpoints_mvp_contracts.md` (Map + POIs section).
  - Reference: `foundation_documentation/modules/map_poi_module.md` (stacking + time window rules).
- [x] ✅ Production‑Ready (Deferred in this slice) Keep `/api/v1/map/pois/stream` out of runtime scope; use polling/reload via `/map/pois` when needed.
- [x] ✅ Production‑Ready Use `/api/v1/map/filters` for category/tag/taxonomy discovery (remove hardcoded filter catalogs).
- [x] ✅ Production‑Ready Requests include `Authorization: Bearer <AuthRepository.userToken>` and `Accept: application/json`.

### A2) Same-spot UX
- [x] ✅ Production‑Ready Marker shows top POI + `+N` badge when stack has multiple items.
- [x] ✅ Production‑Ready Tapping opens POI deck/selector listing stack items ordered by priority.
- [x] ✅ Production‑Ready Selecting an item pins selection until cleared or refreshed.

### A3) Deep links
- [x] ✅ Production‑Ready For POI with `ref_type=event`, route to event detail.
- [x] ✅ Production‑Ready For other POIs, route to POI details screen.

---

## B) Acceptance Criteria
- [x] ✅ Production‑Ready Beaches and Nature POIs appear as static POIs and are filterable.
- [x] ✅ Production‑Ready Event POIs appear only within backend-defined time windows (via settings).
- [x] ✅ Production‑Ready Same-spot POIs stack with `+N` badge and open a POI deck with deterministic ordering.

---

## C) Out of Scope
- Admin CRUD UI for Static Assets (tenant-admin workspace).
- VNext clustering beyond exact-key stacks.

---

## D) Definition of Done
- [x] ✅ Production‑Ready Map screen is wired to `/api/v1/map/pois` + `/api/v1/map/filters` with auth headers (`/api/v1/map/pois/stream` deferred in D-04; polling/reload path retained).
- [x] ✅ Production‑Ready Stacking UX works end-to-end (marker badge + deck list + selection pin).
- [x] ✅ Production‑Ready Category filters show static + event POIs with taxonomy filters when available.

---

## E) Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze`.
- [x] ✅ Production‑Ready Manual smoke: open map, toggle filters, open stack deck, deep link to event.
- [x] ✅ Production‑Ready Manual smoke: disconnect/reconnect network and confirm map reload path without mock fallback.
- [x] ✅ Production‑Ready `fvm dart run custom_lint`.
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`.

---

## Decision Adherence Validation (To Fill Before Delivery)
| Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `lib/infrastructure/services/http/laravel_map_poi_http_service.dart:49-53,77-82`; `lib/infrastructure/repositories/city_map_repository.dart:24-27`; `lib/infrastructure/repositories/poi_repository.dart:42-47`; `lib/application/router/modular_app/modules/map_module.dart:19-27` | Runtime source is `/api/v1/map/pois` + `/api/v1/map/filters` only in active map flow. |
| `D-02` | `Adherent` | `lib/infrastructure/repositories/poi_repository.dart:16-20,42-47`; `test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart:263-265` | `ScheduleRepository` synthesis path removed from map inventory construction. |
| `D-03` | `Adherent` | `lib/infrastructure/dal/dto/map/city_poi_dto.dart:136-165`; `lib/domain/map/city_poi_model.dart:54-63`; `lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:289-344`; `lib/presentation/tenant_public/map/screens/map_screen/widgets/shared/poi_marker.dart:23-25,104-112,157-165`; `lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart:149-195,566-605` | Stacks parsed, rendered (`+N`), expanded, ordered and pinned in deck flow. |
| `D-04` | `Adherent` | `lib/infrastructure/repositories/city_map_repository.dart:21,143-145`; `lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:237-261` | SSE is deferred; repository stream is empty and reload/polling path uses `loadPois`. |
| `D-05` | `Adherent` | `lib/infrastructure/services/http/laravel_map_poi_http_service.dart:175-186` | `Accept: application/json` always set; `Authorization: Bearer <token>` set from `AuthRepository.userToken` when available. |

---

## Module Decision Consistency Validation (Delivery)
| Module Decision ID | Status (`Preserved`/`Superseded (Approved)`/`Regression`) | Evidence | Notes |
| --- | --- | --- | --- |
| `MAP-01` | `Preserved` | `foundation_documentation/modules/map_poi_module.md` section `3.6`; `lib/infrastructure/repositories/city_map_repository.dart:24-27` | Frontend consumes projection-backed payload as source of truth. |
| `MAP-02` | `Preserved` | `foundation_documentation/modules/map_poi_module.md` sections `3.4`, `3.6`; `lib/domain/map/city_poi_model.dart:54-57`; `lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart:225-238,529-546` | Typed `ref_type/ref_id/ref_slug/ref_path` consumed for routing decisions. |
| `MAP-03` | `Preserved` | `foundation_documentation/modules/map_poi_module.md` section `3.7`; `lib/infrastructure/dal/dto/map/city_poi_dto.dart:136-165`; `lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart:149-195,566-605` | Exact-key stack payload is parsed and rendered deterministically. |
| `MAP-04` | `Preserved` | `foundation_documentation/modules/map_poi_module.md` sections `3.6`, `4.1`; `lib/infrastructure/repositories/poi_repository.dart:42-47` | Client no longer synthesizes event windows; visibility remains backend-owned. |

---

## Backend constraints to respect
- `distance_meters` is present when `origin_lat/lng` are sent.
- Stack payload uses `stack_key`, `center`, `top_poi`, `stack_count`, `items[]`.
- Endpoint scope is tenant-domain only; account tokens are allowed.
