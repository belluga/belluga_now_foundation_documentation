# TODO (V1): Events Capability - Map POI

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team
**Objective:** Deliver `map_poi` capability to control event map projection behavior through capability governance, preserving decoupling via Events lifecycle and projection contracts.

---

## Scope
- Implement `map_poi` as a concrete capability in Events capability registry/contracts.
- Add tenant-scoped capability settings and event-level configuration for map projection behavior.
- Enforce effective capability gate for map projection side effects (`tenant_available && event_enabled`).
- Define projection payload contract for consolidated event POI representation with occurrence facets.
- Expose explicit NOW flag in projection facets (`is_happening_now`) for occurrences matching `date_time_start <= now < effective_end`.
- Add/adjust migrations/indexes needed for POI read/query/update consistency in tenant scope.
- Add end-to-end tests for gate behavior, projection updates, and disable/reenable semantics.

---

## Out of Scope
- Frontend map rendering/UX changes.
- Generic geospatial recommendation engine.
- Non-events POI domains.
- Ticket-domain implementation (tracked in `TODO-v1-ticketing-package-integration.md`).

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [x] ✅ Production‑Ready `MPOI-01` Projection ownership boundary: `map_poi` controls discovery/projection behavior only; event canonical location modeling remains in Events core.
- [x] ✅ Production‑Ready `MPOI-02` Occurrence mapping policy: use consolidated event POI as canonical map projection, carrying occurrence facets/windows for schedule-aware filtering.
- [x] ✅ Production‑Ready `MPOI-03` Publication coupling: POI visibility is occurrence-driven; only published/active occurrence facets make the POI discoverable.
- [x] ✅ Production‑Ready `MPOI-04` Soft-delete and reconciliation behavior for stale POIs: apply soft-hide (`is_active=false`) with reconciliation jobs; do not hard-delete on first stale transition.
- [x] ✅ Production‑Ready `MPOI-05` Tenant/event payload boundary: core location uses `location`/`place_ref`; `map_poi` stores only projection/discovery configuration.
- [x] ✅ Production‑Ready `MPOI-06` Radius/filter index strategy: lean canonical read paths (`/map/pois`, `/map/near`) with minimal hot-path indexes only.
- [x] ✅ Production‑Ready `MPOI-07` Projection idempotency/retry policy: idempotent upsert contract with source version/checkpoint and safe retry behavior.
- [x] ✅ Production‑Ready `MPOI-08` Online regional discovery: online events can be discoverable in map feeds through capability-level `discovery_scope` (e.g., circle/polygon), independent from physical address.
- [x] ✅ Production‑Ready `MPOI-09` NOW projection flag: occurrence facets matching happening-now criteria must expose `is_happening_now=true`.
- [x] ✅ Production‑Ready `MPOI-10` Geometry compatibility strategy:
  - canonical storage supports `point` and `range/circle` via `center + radius_meters`;
  - dispatcher must enforce required fields per geometry type;
  - randomization/jitter is allowed only as non-persisted display output.

---

## Tasks
- [x] ✅ Production‑Ready Define and approve `map_poi` payload contracts.
- [x] ✅ Production‑Ready Implement capability handler and register in capability registry.
- [x] ✅ Production‑Ready Extend settings schema/values/patch flow for tenant map_poi settings.
- [x] ✅ Production‑Ready Implement runtime gate integration in projection side-effect path.
- [x] ✅ Production‑Ready Implement `discovery_scope` projection support for online/regional events (`location.mode=online` without requiring address).
- [x] ✅ Production‑Ready Add `is_happening_now` flag to occurrence facets in map projection payload (`true` only while `date_time_start <= now < effective_end`).
- [x] ✅ Production‑Ready Implement idempotent projection upsert contract (`projection_key`, source version/checkpoint monotonicity, retry-safe writes).
- [x] ✅ Production‑Ready Implement geometry-aware POI builder dispatcher:
  - `point` requires `location` (`Point`).
  - `range`/`circle` requires `center` (`Point`) + `radius_meters` (>0).
  - `polygon` (when enabled) requires valid polygon coordinates.
- [x] ✅ Production‑Ready Add/adjust migrations/indexes for projection/read paths.
- [x] ✅ Production‑Ready Implement/expand tests for projection sync, disable/reenable, and partial updates.
- [x] ✅ Production‑Ready Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [x] ✅ Production‑Ready Capability-specific tests for map_poi gate, persistence, disable/reenable, and partial updates.
- [x] ✅ Production‑Ready Projection consistency tests for create/update/delete/publication transitions.
- [x] ✅ Production‑Ready Happening-now staleness tests: crossing `date_time_start` alone must not stale/hide POI; stale only after `effective_end` (`date_time_end` or `start+3h` fallback).
- [x] ✅ Production‑Ready NOW flag tests: `is_happening_now=true` only during happening-now window and reverts to `false` after `effective_end`.
- [x] ✅ Production‑Ready Idempotency tests: duplicated/out-of-order projection messages do not regress POI state or create duplicates.
- [x] ✅ Production‑Ready Geometry contract tests:
  - dispatcher rejects missing required fields by geometry type;
  - `range`/`circle` reads are evaluated from canonical `center + radius_meters`;
  - jitter/randomized lat/lng is never persisted in projection storage.
- [x] ✅ Production‑Ready Tenant-scoped migration/index validation for map POI paths.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Map/MapPoisControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite).

---

## Definition of Done
- [x] ✅ Production‑Ready `map_poi` capability is integrated with registry/settings/runtime gate.
- [x] ✅ Production‑Ready Projection behavior is deterministic, idempotent, and validated under retries.
- [x] ✅ Production‑Ready Disable/reenable is non-destructive and validated.
- [x] ✅ Production‑Ready Partial update semantics are atomic and validated.
- [x] ✅ Production‑Ready Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Aligned rule: `map_poi` is an optional capability and must not own canonical event location fields.
- Aligned boundary:
  - Events core owns `location` semantics (`physical|online|hybrid`) and `place_ref`.
  - `map_poi` owns discovery/projection behavior and optional `discovery_scope`.
- Consolidated projection policy:
  - One canonical POI per event for map discovery.
  - Occurrence-level details remain available as projection facets (e.g., active windows, next occurrence pointers) instead of independent POI rows.
- Publication coupling policy:
  - Event lifecycle continues to trigger projection synchronization.
  - Discoverability is determined by occurrence facets: POI is visible only while at least one occurrence facet is published/active.
- Radius/filter/index policy (lean MVP):
  - Canonical read paths remain `GET /api/v1/map/pois` and `GET /api/v1/map/near`.
  - Indexes are limited to hot paths:
    - `2dsphere(location)`
    - active/update ordering support (`is_active`, `updated_at`)
    - projection identity lookup (`ref_type`, `ref_id`)
    - selective filter compounds only when proven hot in profiling.
  - Avoid broad combinatorial index matrix in MVP.
- Idempotency/retry policy:
  - Projection writes are idempotent by stable `projection_key` (derived from `ref_type` + `ref_id`).
  - Upsert application uses source checkpoint/version monotonicity to ignore stale/out-of-order retries.
  - Async retries are safe and must not create duplicate POIs or regress a newer projection state.
- Staleness policy:
  - Stale transition is soft-hide first (`is_active=false`) and remains recoverable by reconciliation.
  - Occurrence staleness uses `effective_end` semantics (not `starts_at` crossing):
    - `effective_end = date_time_end` when provided.
    - `effective_end = date_time_start + 3h` when `date_time_end` is missing.
  - "Happening now" occurrences are not stale while `date_time_start <= now < effective_end`.
  - Projection facets must expose `is_happening_now` with the same canonical rule.
- Online regional case is explicitly supported through capability configuration, even when the event has no physical address.
- Geometry compatibility investigation (for `MPOI-10`):
  - MongoDB GeoJSON supports `Point`/`Polygon` (and related GeoJSON geometries), but not a native persisted `Circle` geometry type.
  - Canonical strategy: persist `range/circle` as `center + radius_meters` and execute radius semantics in application query logic.
  - Randomized point generation (jitter) is presentation-only and non-persistent.
  - POI builder dispatcher is responsible for validating geometry type and required fields before projection write.
- Validation execution (2026-03-01):
  - Targeted suites: `php artisan test tests/Feature/Events/EventCrudControllerTest.php tests/Feature/Map/MapPoisControllerTest.php tests/Feature/Settings/SettingsKernelControllerTest.php` => pass.
  - Full suite: `php artisan test` => pass (`811 passed`).

---

## Decision Adherence Validation

| Decision | Status | Evidence |
|---|---|---|
| `MPOI-01` projection boundary only | Adherent | `laravel-app/packages/belluga/belluga_events/src/Capabilities/MapPoiCapabilityHandler.php:43`; `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:275` |
| `MPOI-02` consolidated event POI + facets | Adherent | `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:179`; `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:306` |
| `MPOI-03` occurrence-driven discoverability | Adherent | `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:157`; `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:211` |
| `MPOI-04` soft-hide stale POIs | Adherent | `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:211`; `laravel-app/tests/Feature/Events/EventCrudControllerTest.php` (`testEventMapPoiProjectionSoftHidesWhenOccurrencesBecomeStale`) |
| `MPOI-05` payload boundary core vs capability | Adherent | `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventStoreRequest.php:82`; `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventUpdateRequest.php:82` |
| `MPOI-06` lean index strategy | Adherent | `laravel-app/database/migrations/tenants/2026_02_02_000500_create_map_pois_collection.php:17` |
| `MPOI-07` idempotent checkpointed upsert | Adherent | `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:245`; `laravel-app/tests/Feature/Events/EventCrudControllerTest.php` (`testEventMapPoiProjectionIgnoresStaleCheckpointWrite`) |
| `MPOI-08` online regional discovery support | Adherent | `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:382`; `laravel-app/tests/Feature/Events/EventCrudControllerTest.php` (`testEventCreateOnlineSupportsRangeDiscoveryScopeForMapPoiProjection`) |
| `MPOI-09` explicit NOW flag | Adherent | `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:337`; `laravel-app/app/Application/MapPois/MapPoiQueryService.php:582`; `laravel-app/tests/Feature/Map/MapPoisControllerTest.php` (`testMapNearReturnsNowFlagAndOccurrenceFacets`) |
| `MPOI-10` geometry contract compatibility | Adherent | `laravel-app/packages/belluga/belluga_events/src/Capabilities/MapPoiCapabilityHandler.php:51`; `laravel-app/app/Application/MapPois/MapPoiProjectionService.php:470`; `laravel-app/tests/Feature/Map/MapPoisControllerTest.php` (`testMapPoisBoxIncludesPolygonDiscoveryScopeIntersections`) |

---

## Decision Log
- `MPOI-01`: Decided. `map_poi` is projection/discovery behavior only; canonical location is core Events concern.
- `MPOI-02`: Decided (Option B). Canonical map projection is consolidated per event; occurrences are represented as facets/windows in the projection payload.
- `MPOI-03`: Decided (Option B). Publication coupling is occurrence-driven for discoverability; event lifecycle still triggers projection sync.
- `MPOI-04`: Decided (Option B). Stale POIs are soft-hidden and reconciled asynchronously; staleness evaluation follows happening-now/effective-end rules.
- `MPOI-05`: Decided. Payload boundary is split: core `location` vs capability `map_poi` configuration.
- `MPOI-06`: Decided (Option A). Use lean canonical query paths and minimal hot-path index set; postpone broad combinatorial indexing.
- `MPOI-07`: Decided (Option B). Projection upserts are idempotent and checkpoint-aware to tolerate duplicate/out-of-order async retries.
- `MPOI-08`: Decided. Online events may project to map via `discovery_scope` (circle/polygon) without physical venue/address.
- `MPOI-09`: Decided. Occurrence facets in map projection must expose explicit `is_happening_now` based on canonical happening-now criteria.
- `MPOI-10`: Decided. Geometry type contract is dispatcher-enforced; `range/circle` stores canonical `center + radius_meters`, and jitter/randomized coordinates are never persisted.
