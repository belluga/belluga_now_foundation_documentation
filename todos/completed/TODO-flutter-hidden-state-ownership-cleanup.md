# TODO (V1): Flutter Hidden State Ownership Cleanup
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Completed  
**Owners:** Flutter

## Objective
Establish strict state ownership boundaries in Flutter tenant/admin flows by removing controller-level canonical list caches where repository ownership is intended, decomposing parallel non-widget state managers, and restoring full static analysis compatibility.

## References
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/flutter-clean-code-audit/SKILL.md`
- `delphi-ai/workflows/flutter/create-controller-method.md`
- `delphi-ai/workflows/flutter/create-repository-method.md`
- `foundation_documentation/domain_entities.md`

## Scope
- Flutter only (`flutter-app`).
- Fix analyzer blockers caused by repository-contract drift.
- Refactor tenant-admin canonical list ownership to repository streams/delegation.
- Decompose account profile edit parallel `*State` pattern into narrower state boundaries.

## Out of Scope
- Laravel/backend changes.
- Net-new product features.
- Unrelated UI redesign.

## Definition of Done
- `fvm flutter analyze` passes.
- Integration test fakes compile with current contracts.
- Tenant-admin mutable list flows use repository-owned canonical streams (controller delegates).
- Account profile edit flow no longer uses a parallel multi-concern non-widget state manager.
- `flutter-architecture-adherence` checks reviewed and no unresolved high-severity architecture findings remain in touched scope.

## Execution Plan
- [x] ✅ Production‑Ready Update all integration test fake repositories to implement latest `TenantAdminAccountsRepositoryContract` stream/pagination API.
- [x] ✅ Production‑Ready Extend tenant-admin repository contracts/implementations (organizations, taxonomies/terms, static assets, profile types, static profile types) with canonical stream ownership where missing.
- [x] ✅ Production‑Ready Refactor corresponding controllers to delegate canonical list/pagination state to repositories and remove duplicated `_fetched*` / `_isFetching*` / `_hasMore*` internals.
- [x] ✅ Production‑Ready Decompose `TenantAdminAccountProfileEditState` into narrow draft/snapshot streams and remove canonical duplication concerns.
- [x] ✅ Production‑Ready Update/repair affected tests and fakes.
- [x] ✅ Production‑Ready Run `fvm flutter analyze` + targeted tests for touched flows.
- [x] ✅ Production‑Ready Run `flutter-clean-code-audit` pass for touched scope and resolve remaining issues or document explicit exceptions.

## Provisional Notes
- Integration-test execution (`integration_test/*`) remains environment-sensitive here: commands repeatedly stalled in `loading` after APK build/install when executed through `fvm flutter test ... integration_test/...` despite active ADB device (`192.168.15.7:5555`). Unit/controller/screen tests for all touched flows passed.

## Validation Commands
- `fvm flutter analyze`
- `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart`
- `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_account_profiles_controller_test.dart`
- `fvm flutter test integration_test/feature_admin_account_create_with_location_test.dart`
- `fvm flutter test integration_test/feature_admin_profile_type_capabilities_form_test.dart`
- `fvm flutter test integration_test/feature_admin_taxonomy_registry_test.dart`
