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
- Keep settings integration contract-first via `belluga_settings`:
  - consume `map_ui` namespace from settings kernel
  - register any new map-specific namespaces in registry (if needed)
- Replace cross-domain direct dependencies with contracts/adapters:
  - Events, Account Profiles, Static Assets emit lifecycle data to projection contracts/listeners.
- Preserve current tenant/domain security and access guardrails.

---

## Out of Scope
- Flutter map integration changes (handled in `TODO-v1-map-frontend.md`).
- Ticketing/event capability implementation (belongs to Events Phase 3 stream).
- New map product features not required for package extraction/hardening.

---

## Standards/Exception Reference (Locked)
- Settings kernel is canonical for settings schema/values:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-settings-kernel-package.md`
- Events program is authoritative for Event-side boundaries:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md`
- Multitenancy execution must remain aligned with Spatie tenant migration/runtime model.
- Full Laravel suite is mandatory validation gate at important milestones and phase closure.

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `M1-01` Package route ownership:
  - keep host route files as wrappers vs package-owned route loading for map endpoints.
- [ ] ⚪ `M1-02` Projection contract granularity:
  - single generic POI projection contract vs source-specific contracts (`events`, `account_profiles`, `static_assets`).
- [ ] ⚪ `M1-03` Namespace strategy in settings:
  - continue with `map_ui` only vs split operational settings (`map_ui`, `map_ingest`, etc.).
- [ ] ⚪ `M1-04` Rebuild/reconciliation operations:
  - define if package exposes explicit rebuild command/API for map projection repair.

---

## Tasks
- [ ] ⚪ Create package skeleton (`belluga_map_pois`) and composer wiring.
- [ ] ⚪ Move map domain/application code into package namespaces.
- [ ] ⚪ Define package contracts for POI projection ingestion and query boundaries.
- [ ] ⚪ Implement host app adapters/bindings for external dependencies.
- [ ] ⚪ Route Event/Profile/StaticAsset POI writes through contract/listener pipeline.
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
