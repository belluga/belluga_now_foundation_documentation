# TODO (V1): Map + POIs Backend Package (Post-Events)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Establish Map + POIs as a first-class Laravel package, with settings-driven behavior and contract-based projection ingestion from other domains.

---

## Scope
- Create `belluga_map_pois` package as authoritative backend location for:
  - map query/read services
  - map POI projection pipeline
  - map endpoints/contracts
  - map package models/migrations/indexes
- Absorb current Events -> Map POI integration responsibilities (listeners/jobs/signature classification/adapters) into the Map package boundary, keeping Events as contract consumer only.
- Keep settings integration contract-first via `belluga_settings`:
  - consume `map_ui` namespace from settings kernel
  - register any new map-specific namespaces in registry (if needed)
- Replace cross-domain direct dependencies with contracts/adapters:
  - Events, Account Profiles, Static Assets emit lifecycle data to projection contracts/listeners.
- Preserve current tenant/domain security and access guardrails.

---

## Out of Scope
- Flutter map integration changes (handled in `TODO-v1-map-frontend.md`).
- Ticketing package implementation (tracked in `TODO-v1-ticketing-package-integration.md`).
- New map product features not required for package extraction/hardening.

---

## Standards/Exception Reference (Locked)
- Settings kernel is canonical for settings schema/values:
  - `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md`
- Events program is authoritative for Event-side boundaries:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md`
- Multitenancy execution must remain aligned with Spatie tenant migration/runtime model.
- Full Laravel suite is mandatory validation gate at important milestones and phase closure.

---

## Pending Decisions (To Iterate)
- [x] ✅ Production-Ready `M1-01` Package route ownership.
  - Decided: package-owned route loading in S1 (host wrappers removed as target state in this slice).
- [x] ✅ Production-Ready `M1-02` Projection contract granularity.
  - Decided: hybrid model (generic base contract/DTO plus source-specific extensions for `events`, `account_profiles`, `static_assets`).
- [x] ✅ Production-Ready `M1-03` Namespace strategy in settings.
  - Decided: split namespaces in MVP (`map_ui`, `map_ingest`, `map_security`).
- [x] ✅ Production-Ready `M1-04` Rebuild/reconciliation operations.
  - Decided: internal rebuild command only in MVP (no public rebuild API endpoint).

---

## Current Execution Slice (S1: Package Bootstrap)
- Bootstrap `belluga_map_pois` package with contracts/adapters boundary and migration scope classification.
- Keep runtime behavior stable in S1 (no endpoint contract break and no projection semantic change).
- Defer full code migration/refactor to follow-up slices after bootstrap validations are green.

---

## Complexity and Checkpoint Policy
- Complexity: `medium`.
- Checkpoint policy:
  - One planning checkpoint (this TODO refinement + baseline freeze).
  - One implementation checkpoint (post-bootstrap + targeted tests).
  - One closure checkpoint (decision adherence + full suite).

---

## Plan Review Gate (Medium)
### Issue Card `MAP-PRG-01` Package boundary drift risk
- Severity: High
- Why now: Current Map runtime is host-owned under `app/**`, blocking package-first evolution.
- Evidence: `laravel-app/app/Application/MapPois/**`, `laravel-app/app/Jobs/MapPois/**`, `laravel-app/app/Http/Api/v1/Controllers/MapPoisController.php`.
- Option A (recommended): Bootstrap package now with explicit contracts and host adapters, then migrate code by slices.
  - Effort: Medium
  - Risk: Low
  - Blast radius: Low
  - Maintenance burden: Low
- Option B: Move everything in one big-bang refactor.
  - Effort: High
  - Risk: High
  - Blast radius: High
  - Maintenance burden: Medium
- Option C: Keep host-owned map runtime for MVP.
  - Effort: Low
  - Risk: High (architecture drift)
  - Blast radius: Low
  - Maintenance burden: High

### Issue Card `MAP-PRG-02` API contract break risk during package extraction
- Severity: High
- Why now: Public map endpoints are already consumed and tested.
- Evidence: `laravel-app/routes/api/project_tenant_public_api_v1.php`, `laravel-app/tests/Feature/Map/MapPoisControllerTest.php`.
- Option A (recommended): Keep host route/controller wrappers in S1 and wire package services behind them.
  - Effort: Medium
  - Risk: Low
  - Blast radius: Low
  - Maintenance burden: Low
- Option B: Move route loading fully into package in S1.
  - Effort: Medium
  - Risk: Medium
  - Blast radius: Medium
  - Maintenance burden: Medium
- Option C: Freeze map routes and skip package extraction.
  - Effort: Low
  - Risk: High (no unblock)
  - Blast radius: Low
  - Maintenance burden: High

### Issue Card `MAP-PRG-03` Multitenancy migration wiring risk
- Severity: High
- Why now: Package creation without explicit tenant migration wiring causes runtime drift across tenants.
- Evidence: `laravel-app/config/multitenancy.php`, current map migration under `laravel-app/database/migrations/tenants/`.
- Option A (recommended): Classify package data as tenant-scoped and wire package migration path in Spatie tenant paths.
  - Effort: Low
  - Risk: Low
  - Blast radius: Medium
  - Maintenance burden: Low
- Option B: Keep migrations in host and postpone wiring.
  - Effort: Low
  - Risk: Medium
  - Blast radius: Medium
  - Maintenance burden: Medium
- Option C: Create mixed tenant/landlord migrations now.
  - Effort: Medium
  - Risk: Medium
  - Blast radius: Medium
  - Maintenance burden: Medium

---

## Failure Modes and Edge Cases
- Host bindings missing for package-required contracts (must fail fast on boot).
- Package migration path not registered in tenant paths.
- Event/profile/static-asset projection flow still writing directly without contract adapters.
- Route wrapper accidentally diverges from package DTO/validation contract.

---

## Uncertainty Register
- Assumptions:
  - S1 should only unblock package boundary and not change runtime contracts.
  - `map_pois` storage remains tenant-scoped.
- Unknowns:
  - Exact cut line for moving query vs projection internals in S1 vs S2.
  - Whether `map_ingest` namespace is needed in MVP.
- Confidence: Medium-High.

---

## Decision Baseline (Frozen for S1, Pending Approval)
- `MAP-D01` Route ownership in S1:
  - Use package-owned route loading in S1; host wrappers are removed from target runtime path.
- `MAP-D02` Projection contract granularity:
  - Use hybrid ingestion contracts: shared generic base + source-specific extensions (`events`, `account_profiles`, `static_assets`).
- `MAP-D03` Settings namespace:
  - Split in MVP: `map_ui`, `map_ingest`, `map_security`.
- `MAP-D04` Multitenancy classification:
  - `belluga_map_pois` data is `tenant` scope.
  - Tenant migrations live in package path and are wired via `config/multitenancy.php` tenant migration paths.
- `MAP-D05` Migration strategy:
  - S1 delivers package skeleton + contracts + host integration wiring with behavior parity.
  - Full internal code migration proceeds in subsequent slices.
- `MAP-D06` Decoupling rule:
  - No direct `App\\...` references inside package `src/**`; host integration must be adapter-bound.
- `MAP-D07` Rebuild/reconciliation operation:
  - Provide internal rebuild command for projection repair (per-source and full), without public API endpoint in MVP.

---

## Decision Adherence Validation (S1)
- `MAP-D01`: Pending
- `MAP-D02`: Pending
- `MAP-D03`: Pending
- `MAP-D04`: Pending
- `MAP-D05`: Pending
- `MAP-D06`: Pending

---

## Tasks
- [ ] ⚪ Create package skeleton (`belluga_map_pois`) and composer wiring.
- [ ] ⚪ Move map domain/application code into package namespaces.
- [ ] ⚪ Define package contracts for POI projection ingestion and query boundaries.
- [ ] ⚪ Implement host app adapters/bindings for external dependencies.
- [ ] ⚪ Route Event/Profile/StaticAsset POI writes through contract/listener pipeline.
- [ ] ⚪ Move existing Events-related Map POI operational integration from host/Events stream into `belluga_map_pois` (including async job signature ownership), leaving `belluga_events` decoupled from Map implementation details.
- [ ] ⚪ Migrate map models/migrations/index definitions to package ownership.
- [ ] ⚪ Integrate package services with settings kernel namespaces (`map_ui` baseline).
- [ ] ⚪ Keep API contracts stable or document intentional contract changes in foundation docs before implementation.
- [ ] ⚪ Add/refresh tests for package bindings, projection side effects, and map query behavior.
- [ ] ⚪ Update foundation docs (`module` + roadmap + submodule summary) after delivery.

---

## Validation Steps
- [ ] ⚪ `php artisan test` (full Laravel suite; mandatory).
- [ ] ⚪ `php artisan test tests/Feature/Map/MapPoisControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/StaticAssets/StaticAssetsControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`.
- [ ] ⚪ Add/execute targeted tests for projection adapters/listeners and settings consumption.

---

## Definition of Done
- [ ] ⚪ Map + POIs backend runtime is package-owned and decoupled from host app implementation details.
- [ ] ⚪ Cross-domain POI side effects flow through package contracts/listeners (no direct cross-domain writes).
- [ ] ⚪ Settings-driven map behavior is consumed through settings kernel contracts.
- [ ] ⚪ Full Laravel suite passes after migration.
- [ ] ⚪ Foundation documentation is synchronized with final architecture.

---

## Decision Log
- `M1-00`: Created as a dedicated backend stream by explicit product/architecture decision.
- `M1-05`: Decided. Current Events -> Map POI integration is transitional and must be relocated to `belluga_map_pois`; Events remains contract-only for Map projection side effects.
