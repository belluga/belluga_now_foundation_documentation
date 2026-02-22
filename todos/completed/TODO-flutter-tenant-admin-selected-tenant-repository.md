# TODO — Flutter Tenant Admin: SelectedTenant Repository (shared controller access)

## scope
- Establish a dedicated `SelectedTenantRepository` in Flutter tenant-admin scope as the canonical source of truth for selected tenant state.
- Keep compatibility with existing tenant-admin contracts by supporting current domain/base-url access while adding selected-tenant object access for controllers.
- Ensure the tenant-admin shell hydrates tenant list/selection into this repository so downstream controllers read consistent tenant context.
- Expose repository via DI so any tenant-admin controller can resolve and consume it.
- Keep Settings/tenant-scoped consumers aligned with tenant selection state from the shared repository (no controller-local tenant shadow state additions).
- Update foundation docs (`modules` + `screens`) to document this architecture baseline before/with implementation.

## out_of_scope
- No backend/API contract/schema changes.
- No route map changes.
- No redesign of settings visual hierarchy.
- No integration/device test execution (no ADB connected in current environment).

## definition_of_done
- New domain repository contract exists for selected tenant context and is registered in tenant-admin DI.
- Infrastructure repository implementation provides:
  - selected tenant domain stream/getter
  - selected tenant option stream/getter
  - tenant admin base-url derivation
  - selection clear/select operations
  - tenant list hydration/sync support
- Tenant-admin shell writes available tenants/selection into the repository.
- Existing tenant-admin controllers continue working without contract breakage.
- Analyzer is clean and focused tests pass.
- Foundation docs reflect the selected-tenant shared repository strategy.

## execution_checklist
- [x] ✅ Production‑Ready Document architecture update in `foundation_documentation/modules/tenant_admin_module.md` and `foundation_documentation/screens/modulo_tenant_admin.md`.
- [x] ✅ Production‑Ready Add `TenantAdminSelectedTenantRepositoryContract` in `lib/domain/repositories/`.
- [x] ✅ Production‑Ready Implement `TenantAdminSelectedTenantRepository` in `lib/infrastructure/repositories/tenant_admin/`.
- [x] ✅ Production‑Ready Register repository in `TenantAdminModule` DI so controllers can resolve it, preserving compatibility with existing tenant-scope contract consumers.
- [x] ✅ Production‑Ready Update `TenantAdminShellController` to hydrate/sync selected tenant through the repository.
- [x] ✅ Production‑Ready Add/adjust unit tests for repository behavior and shell sync behavior.
- [x] ✅ Production‑Ready Run focused validation (`fvm flutter analyze` + targeted tests) using WSL-safe commands only.

## validation_steps
- `fvm flutter analyze`
- `fvm flutter test test/infrastructure/repositories/tenant_admin_selected_tenant_repository_test.dart`
- `fvm flutter test test/presentation/tenant_admin/shell/controllers/tenant_admin_shell_controller_test.dart`
- If existing tests touched by contract wiring: run additional targeted tenant-admin tests only.

## decisions
- Use repository semantics (domain `repositories/`) instead of service semantics for selected tenant state ownership.
- Preserve existing `TenantAdminTenantScopeContract` compatibility to avoid broad regressions while enabling progressive migration to the selected-tenant repository contract.
- Keep all state ownership in controller/repository layers (no widget-level tenant state).

## validation_run_notes
- WSL-safe cache clean performed before tests:
  - moved `build/test_cache` to `foundation_documentation/artifacts/tmp/manual-test-cache/test_cache_selected_tenant_20260221_090400`
- `timeout 420s fvm flutter test --reporter expanded test/infrastructure/repositories/tenant_admin_selected_tenant_repository_test.dart` ✅
- `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/controllers/tenant_admin_shell_controller_test.dart` ✅
- `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart` ✅
- `timeout 420s fvm flutter analyze` ✅
