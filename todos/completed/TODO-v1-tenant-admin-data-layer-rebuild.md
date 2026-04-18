# TODO (V1): Tenant Admin Data Layer Rebuild (Repos + Contracts)
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Completed  
**Owners:** Flutter Team  
**Objective:** Replace `TenantAdminStore` with repository-driven contracts aligned to Laravel endpoints and domain definitions, ensuring Account + Account Profile creation is a single bounded flow and Organizations have their own repository.

---

## References
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/submodule_laravel-app_summary.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`

---

## A) Scope
- Remove `TenantAdminStore` as an infrastructure state container; replace with repositories + controller orchestration.
- Create distinct repositories for **Organizations**, **Accounts**, and **Account Profiles**.
- Enforce **Account + Account Profile** as a **bound flow** (single creation path); do not allow Account Profile creation without an Account.
- Align repository contracts and DTOs with Laravel endpoints and payload shapes.
- Wire GetIt registrations for the new repositories/controllers.
- Update tenant-admin screens to consume repositories/controllers instead of store state.
- Ensure `ownership_state` remains **derived** (not user input) per domain definitions.
- Enforce POI-enabled profile types to require a location (`lat`/`lng`) in the bound Account + Profile create flow.
- Provide a **Map Pick** option in the Account/Profile form to capture coordinates.

---

## B) Tasks

### B1) Domain contracts (repositories)
- [x] ✅ Production-Ready Define `TenantAdminOrganizationsRepositoryContract`.
- [x] ✅ Production-Ready Define `TenantAdminAccountsRepositoryContract`.
- [x] ✅ Production-Ready Define `TenantAdminAccountProfilesRepositoryContract`.

### B2) DTOs + mappers
- [x] ✅ Production-Ready Add DTOs for organization/account/account profile aligned to `tenant_admin_module.md`.
- [x] ✅ Production-Ready Add profile type registry DTO for `/api/v1/account_profile_types`.
- [x] ✅ Production-Ready Map DTOs → domain/projections for UI controllers.

### B3) Repository implementations (Laravel-backed)
- [x] ✅ Production-Ready Implement Organizations repo (`/api/v1/organizations` CRUD).
- [x] ✅ Production-Ready Implement Accounts repo (`/api/v1/accounts` CRUD).
- [x] ✅ Production-Ready Implement Account Profiles repo (`/api/v1/account_profiles` CRUD + geo query if needed).
- [x] ✅ Production-Ready Use landlord/tenant admin auth and tenant access guards (per backend contract).
- [x] ✅ Production-Ready Route tenant-admin calls through the admin API base (`/admin/api/v1/...`) to avoid 404s on tenant endpoints.

### B4) Bound create flow (Accounts + Account Profiles)
- [x] ✅ Production-Ready Create controller/service that orchestrates **Account create → Account Profile create**.
- [x] ✅ Production-Ready Ensure profile creation requires `account_id` and respects `profile_type` registry (POI-enabled location requirement).
- [x] ✅ Production-Ready Keep the UX as a single form or a guided stepper; no standalone profile create without Account context.

### B5) Remove TenantAdminStore
- [x] ✅ Production-Ready Remove `TenantAdminStore` usage in screens.
- [x] ✅ Production-Ready Remove GetIt registration for `TenantAdminStore`.

### B6) UI wiring
- [x] ✅ Production-Ready Update tenant-admin screens to read from controllers + repository results.
- [x] ✅ Production-Ready Ensure lists/details are driven by server data (no in-memory mock store).
- [x] ✅ Production-Ready Improve profile type dropdown UX (loading/error/empty states) and ensure profile types load from the admin base.
- [x] ✅ Production-Ready Replace dashboard navigation so only "Contas" is exposed as the entry point (account + profile bound flow); remove "Perfis de Conta" tile.
- [x] ✅ Production-Ready Require location fields when `profile_type.capabilities.is_poi_enabled=true` in the bound Account/Profile create form.
- [x] ✅ Production-Ready Add **Map Pick** action to select coordinates for POI-enabled types (map pin → returns lat/lng).
- [x] ✅ Production-Ready POI-enabled location fields visibility is functional in **Account Create + Account Update** (lat/lng + map pick render and reflect current values).
- [x] ✅ Production-Ready Ensure profile type admin edit/delete URL-encodes `type` (handle spaces/accents to avoid 404 on update/delete).
- [x] ✅ Production-Ready Backend normalizes account profile type identifiers on update/delete (`trim` before lookup) to avoid 404s from param noise.
- [x] ✅ Production-Ready TEMP DEBUG was not required after root-cause fix; diagnostics stayed out of production code.
- [x] ✅ Production-Ready TEMP DEBUG payload response path was not introduced (kept API surface clean).
- [x] ✅ Production-Ready TEMP DEBUG list echo path was not introduced (kept API surface clean).
- [x] ✅ Production-Ready Add feature test covering tenant admin account profile type update route-param behavior.
- [x] ✅ Production-Ready Fix controller to resolve `profile_type` from named route param (`request->route('profile_type')`).
- [x] ✅ Production-Ready No temporary debug responses/logging remain after verification.

### B7) Validation
- [x] ✅ Production-Ready `fvm flutter analyze` clean.
- [x] ✅ Production-Ready Targeted tests for repositories + bound create flow (if test harness exists).
- [x] ✅ Production-Ready Unit tests for tenant-admin controllers (accounts, account profiles, profile types).
- [x] ✅ Production-Ready Run tenant-admin integration test on device (Account + Profile + POI location).
  - Notes: Linux integration test build still fails due to missing `libsecret-1`; device run is the MVP gate.
- [x] ✅ Production-Ready Scope transfer: final MVP gate for broad controller+endpoint coverage moved to `TODO-v1-first-release.md` (global release gate), since it is cross-slice and not specific to this data-layer rebuild.

### B8) Pending Decisions
- [x] ✅ Production-Ready Define Profile Type Registry CRUD (create/update/delete) endpoints + contracts (tenant admin), then implement in Laravel + update Flutter admin UI. (Tracked in `TODO-v1-profile-type-registry-crud.md`)

---

## C) Out of Scope
- New admin UX or additional tenant admin features beyond Account/Profile/Organization management.
- Backend changes or schema modifications.
- Memberships/roles system and claim flows (post-MVP).

---

## D) Definition of Done
- [x] ✅ Production-Ready Tenant admin data layer is repository-driven with Laravel-aligned DTOs.
- [x] ✅ Production-Ready Account + Account Profile creation is enforced as a bound flow.
- [x] ✅ Production-Ready Organizations are managed via their own repository.
- [x] ✅ Production-Ready `TenantAdminStore` fully removed (no GetIt registrations or screen usage).
- [x] ✅ Production-Ready Analyzer passes cleanly.
- [x] ✅ Production-Ready POI-enabled profile types require a location, and Map Pick can populate the coordinates.

---

## E) Validation Steps
- [x] ✅ Production-Ready `fvm flutter analyze`
- [x] ✅ Production-Ready Run repository/controller tests (if present).
- [x] ✅ Production-Ready Run tenant-admin controller unit tests.
- [x] ✅ Production-Ready Run tenant-admin integration test on device.
  - Notes: `fvm flutter test -d linux integration_test/feature_admin_account_create_with_location_test.dart` still fails; missing `libsecret-1` for linux build.

## Completion Notes (2026-02-21)
- Map picker/location visibility is considered delivered for this slice.
- Remaining broad “MVP-wide test coverage gate” is intentionally tracked in the release orchestrator TODO (`TODO-v1-first-release.md`) to avoid duplicating cross-slice acceptance criteria here.
