# TODO (V1): Map + POIs Backend Package (Post-Events)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** ✅ Production-Ready (Completed)
**Owners:** Backend Team
**Objective:** Establish Map + POIs as a first-class Laravel package, with settings-driven behavior and contract-based projection ingestion from other domains.

---

## Scope
- Create `belluga_map_pois` package as authoritative backend location for:
  - map query/read services
  - map POI projection pipeline
  - map endpoints/contracts
  - map package models/migrations/indexes
- Absorb current Events -> Map POI integration responsibilities into the Map package boundary, keeping Events contract-only.
- Keep settings integration contract-first via `belluga_settings`.
- Preserve tenant/domain security and access guardrails.

---

## Decision Baseline (Frozen)
- `MAP-D01` Route ownership in S1:
  - package-owned route loading in S1; host wrappers removed.
- `MAP-D02` Projection contract granularity:
  - shared generic base + source-specific contract methods (`events`, `account_profiles`, `static_assets`).
- `MAP-D03` Settings namespace:
  - split in MVP: `map_ui`, `map_ingest`, `map_security`.
- `MAP-D04` Multitenancy classification:
  - tenant-scoped package data + package migration path in Spatie tenant migration paths.
- `MAP-D05` Migration strategy:
  - package skeleton + full map runtime migration (query/projection/jobs/listeners/model/routes) with behavior parity.
- `MAP-D06` Decoupling rule:
  - no `App\\...` references inside package `src/**`; host integration via adapters/bindings.
- `MAP-D07` Rebuild/reconciliation operation:
  - internal rebuild command only (no public rebuild API endpoint).

---

## Decision Adherence Validation
| Decision ID | Status | Evidence |
| --- | --- | --- |
| `MAP-D01` | Adherent | Package routes at `laravel-app/packages/belluga/belluga_map_pois/routes/map_pois.php`; legacy host map routes removed from `laravel-app/routes/api/project_tenant_public_api_v1.php`. |
| `MAP-D02` | Adherent | Source/query/settings contracts in `laravel-app/packages/belluga/belluga_map_pois/src/Contracts/*`; host adapters in `laravel-app/app/Integration/MapPois/*`. |
| `MAP-D03` | Adherent | Namespaces registered in `laravel-app/packages/belluga/belluga_map_pois/src/MapPoisServiceProvider.php` (`map_ui`, `map_ingest`, `map_security`); tenant model supports persisted keys in `laravel-app/app/Models/Tenants/TenantSettings.php`. |
| `MAP-D04` | Adherent | Tenant migration lives in package: `laravel-app/packages/belluga/belluga_map_pois/database/migrations/2026_02_02_000500_create_map_pois_collection.php`; path wired in `laravel-app/config/multitenancy.php`. |
| `MAP-D05` | Adherent | Runtime ownership moved to package (`src/Application`, `src/Jobs`, `src/Listeners`, `src/Http`, `src/Models`); host duplicates removed from `laravel-app/app/Application/MapPois`, `app/Jobs/MapPois`, `app/Listeners/EventsPackage`, `app/Http/Api/v1/Map*`, `app/Models/Tenants/MapPoi.php`. |
| `MAP-D06` | Adherent | Decoupling assertion passed via `assert_package_decoupling.py` (`no App refs in src`, `no wrappers`, `host bindings present`). |
| `MAP-D07` | Adherent | Internal command delivered: `laravel-app/packages/belluga/belluga_map_pois/src/Console/Commands/RebuildMapPoisCommand.php`; coverage in `laravel-app/tests/Feature/Map/MapPoiRebuildCommandTest.php`. |

---

## Tasks
- [x] ✅ Production-Ready Create package skeleton (`belluga_map_pois`) and composer wiring.
- [x] ✅ Production-Ready Move map domain/application code into package namespaces.
- [x] ✅ Production-Ready Define package contracts for POI projection ingestion and query boundaries.
- [x] ✅ Production-Ready Implement host app adapters/bindings for external dependencies.
- [x] ✅ Production-Ready Route Event/Profile/StaticAsset POI writes through contract/listener pipeline.
- [x] ✅ Production-Ready Move Events-related Map POI operational integration into `belluga_map_pois`.
- [x] ✅ Production-Ready Migrate map models/migrations/index definitions to package ownership.
- [x] ✅ Production-Ready Integrate package services with settings kernel namespaces.
- [x] ✅ Production-Ready Keep API contracts stable.
- [x] ✅ Production-Ready Add/refresh tests for package bindings, projection side effects, and map query behavior.
- [x] ✅ Production-Ready Module documentation synchronized.

---

## Validation Steps
- [x] ✅ Production-Ready `python3 .../assert_package_decoupling.py --package-dir .../belluga_map_pois --app-dir .../app --app-provider .../AppServiceProvider.php --check-host-bindings`.
- [x] ✅ Production-Ready `php artisan test tests/Feature/Map/MapPoiRebuildCommandTest.php tests/Feature/Map/MapPoisControllerTest.php tests/Unit/Events/EventsPackageBindingsTest.php tests/Unit/Events/EventsAsyncOperationalPolicyTest.php tests/Feature/Events/EventCrudControllerTest.php`.
- [x] ✅ Production-Ready `php artisan test` full Laravel suite (post-change): **813 passed**, **2985 assertions**, **277.86s**.
- [x] ✅ Production-Ready Stress pass (map suite repeated 3x):
  - Run 1: `Duration 17.87s`
  - Run 2: `Duration 9.96s`
  - Run 3: `Duration 9.20s`
  - Total elapsed: `42s`

---

## Delivery Confidence Gate
- Runtime impact: `medium` (route/model/job/service provider ownership migration).
- Migration/index status: `map_pois` migration owned by package + tenant path wired.
- Queue/worker/scheduler health: docker services healthy during run (`app`, `worker`, `scheduler`, `mongo`, `nginx`).
- Targeted load/perf sampling: completed via repeated map feature stress pass.
- Confidence: `high` for MVP staging/pre-production.
- Release readiness: `ready`.

---

## Definition of Done
- [x] ✅ Production-Ready Map + POIs backend runtime is package-owned and decoupled from host implementation details.
- [x] ✅ Production-Ready Cross-domain POI side effects flow through package contracts/listeners.
- [x] ✅ Production-Ready Settings-driven map behavior is consumed via settings kernel.
- [x] ✅ Production-Ready Full Laravel suite passes after migration.
- [x] ✅ Production-Ready Documentation synchronized with final architecture.

---

## Decision Log
- `M1-00`: Created as dedicated backend stream by product/architecture decision.
- `M1-05`: Events -> Map POI integration relocated to `belluga_map_pois`; Events remains contract-only for projection sync.
