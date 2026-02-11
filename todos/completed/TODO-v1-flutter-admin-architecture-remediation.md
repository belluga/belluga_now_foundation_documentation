# TODO (V1): Flutter Admin Architecture Remediation
**Version:** 1.0
**Status:** Completed
**Owners:** Flutter
**Objective:** Remove architectural smells and lifecycle risks in tenant-admin flows (shell, static assets, taxonomies, location picker integration), preserving current behavior and unblocking stable integration tests.

---

## References
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/flutter-smell-mounted-checks/SKILL.md`
- `delphi-ai/skills/flutter-smell-async-navigation/SKILL.md`
- `delphi-ai/skills/flutter-smell-build-side-effects/SKILL.md`
- `flutter-app/lib/presentation/tenant_admin/shell/tenant_admin_shell_screen.dart`
- `flutter-app/lib/presentation/tenant_admin/static_assets/controllers/tenant_admin_static_assets_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/account_profiles/controllers/tenant_admin_account_profiles_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart`
- `flutter-app/lib/application/router/modular_app/module_settings.dart`
- `flutter-app/lib/application/router/modular_app/modules/tenant_admin_module.dart`

---

## Problem List (Audit)

- [x] `P1` Side-effect navigation inside `build` in admin shell can trigger route bounce loops.
  - Evidence: `tenant_admin_shell_screen.dart` calls `_handleModeChange(mode)` inside `build`, and it performs `context.router.replaceAll(...)`.
- [x] `P1` Feature controllers depend on another feature controller (`TenantAdminLocationPickerController`), violating controller dependency boundary.
  - Affected: accounts, account_profiles, static_assets controllers.
- [x] `P1` Tenant-admin controllers are registered globally in `module_settings.dart`, conflicting with module ownership and lifecycle.
- [x] `P2` Async dialog flows still rely on `context.mounted` checks after awaits in UI callbacks.
- [x] `P2` UI side-effects (snackbars/navigation) are triggered from `build` through post-frame callbacks in admin screens.
- [x] `P3` `loadProfileTypes()` in static assets controller does not clear stale error state on success.
- [x] `P2` Dialog submit flows still navigate after async work in UI (`await` + `Navigator.pop`), requiring controller/router-safe completion pattern.
- [x] `P2` Taxonomy list/terms screens still keep message streams in nested `StreamValueBuilder` wrappers, causing avoidable rebuild pressure.
- [x] `P3` Admin profile edit media uses `Image.network` without explicit cache strategy (keep placeholders/error handling, add cache strategy for repeated visits).
- [x] `P3` Register/document accepted `mounted/context.mounted` exceptions in project artifact with rationale and owner.

---

## Scope

### S1) Admin shell navigation ownership
- Move mode-change navigation out of `build`.
- Keep shell as pure UI consumer of controller state.
- Ensure route transitions happen once per state transition (no repeated replace calls).

### S2) Controller dependency cleanup
- Replace direct controller-to-controller dependency with a neutral contract/service for location selection handoff.
- Keep feature controllers depending only on repositories/services/contracts.

### S3) DI lifecycle alignment
- Remove tenant-admin feature controller registrations from global `module_settings.dart`.
- Keep registration in module-owned scope (`TenantAdminModule`) with explicit lifecycle expectations.

### S4) Async navigation and side-effects hardening
- Refactor async UI handlers to avoid mounted-check-driven flow where possible.
- Keep UI effects deterministic and not coupled to build execution.

### S5) Error-state correctness
- Ensure controller success paths clear stale error stream values.

### S6) Performance smell follow-up (tenant_admin)
- Remove async dialog navigation smell in taxonomy create/edit flows.
- Simplify message stream rendering in taxonomy screens to avoid extra rebuilds.
- Improve image/media caching behavior in admin profile edit while preserving existing fallbacks.
- Track justified mounted-check exceptions in an explicit artifact.

---

## Out of Scope

- New tenant-admin features.
- Backend route/contract changes.
- Visual redesign.

---

## Definition of Done

- No admin shell navigation side-effects executed from `build`.
- No feature controller directly depending on `TenantAdminLocationPickerController`.
- Tenant-admin feature controllers are not globally registered in `module_settings.dart`.
- Mounted-check usage in admin async flows reduced to justified UI-only exceptions.
- Static assets profile type load path clears stale errors on success.
- Existing admin static assets integration flow remains green.

---

## Validation Plan

- `flutter analyze` in `flutter-app/`.
- Run targeted tests:
  - `flutter test integration_test/feature_admin_static_assets_test.dart -d <device>`
  - `flutter test integration_test/feature_admin_taxonomy_registry_test.dart -d <device>`
- Manual smoke:
  - Enter admin shell, switch between tabs, verify no bounce.
  - Create/edit static asset and verify profile type selection is immediately usable.

---

## Execution Order

- [x] Step 1: Fix admin shell side-effect navigation (`P1`).
- [x] Step 2: Refactor location picker dependency boundary (`P1`).
- [x] Step 3: Align tenant-admin DI registration with module scope (`P1`).
- [x] Step 4: Refactor async UI callbacks and mounted-check usage (`P2`).
- [x] Step 5: Fix stale error clearing on profile type load (`P3`).
- [x] Step 6: Remove async-dialog navigation smell in taxonomy create/edit submit flows (`P2`).
- [x] Step 7: Reduce rebuild pressure in taxonomy screens by removing redundant message stream wrappers (`P2`).
- [x] Step 8: Add image caching strategy in admin profile edit for remote avatar/cover (`P3`).
- [x] Step 9: Create/update mounted-exception artifact with explicit decisions (`P3`).
- [x] Step 10: Run analyzer + integration tests + manual smoke and record results.

---

## Execution Notes (Current Run)

- Completed:
  - Shell mode navigation moved out of `build()` into stream listener lifecycle.
  - Location handoff refactored to `TenantAdminLocationSelectionContract` service.
  - Tenant-admin controller registration moved from global `module_settings.dart` to `TenantAdminModule`.
  - Static assets controller success path now clears stale `errorStreamValue`.
  - Side-effects in `build()` removed for static asset create/edit and taxonomy list/terms screens (handled by stream subscriptions in lifecycle).
  - Taxonomy create/edit dialogs no longer do `await` + `Navigator.pop` inside UI submit callbacks; dialog close now reacts to controller success stream.
  - Taxonomy list/terms message rendering stays subscription-based (no message `StreamValueBuilder` wrappers in `build()`).
  - Account profile edit remote avatar/cover now uses explicit cache strategy (`cacheWidth`/`cacheHeight` + `ResizeImage` preload).
  - Mounted exceptions artifact updated with tenant-admin decisions.
- Validation completed:
  - `flutter analyze` (pass).
  - `flutter test test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_account_profiles_controller_test.dart` (pass).
  - `flutter test integration_test/feature_admin_static_assets_test.dart -d 2412DPC0AG --flavor guarappari` (pass).
  - `flutter test integration_test/feature_admin_taxonomy_registry_test.dart -d 2412DPC0AG --flavor guarappari` (pass).
  - Manual smoke on real admin flow validated during device run.
