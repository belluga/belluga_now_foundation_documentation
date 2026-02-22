# TODO (V1): Admin UI Package Cleanup and Settings Adoption

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Clean up the obsolete in-repo Admin UI package folder and complete current Settings adoption by moving remaining reusable Settings section widget to the external `belluga_admin_ui` package.

## Scope
- Add `TenantAdminSettingsSection` widget to external package at `/home/elton/Dev/repos/flutter-packages/belluga_admin_ui`.
- Export the widget from package entrypoint.
- Update Settings screen to import/use `TenantAdminSettingsSection` from `package:belluga_admin_ui/belluga_admin_ui.dart`.
- Remove the duplicated in-app widget file `lib/presentation/tenant_admin/shared/widgets/tenant_admin_settings_section.dart`.
- Remove obsolete local package folder `flutter-app/packages/belluga_admin_ui` from repository.
- Run validation (`pub get`, `analyze`, target settings test).

## Out of scope
- Visual redesign or behavior changes in Settings flow.
- Route/controller/domain/API contract changes.
- Moving all tenant-admin shared widgets to package in this iteration.

## Decisions
- [x] ✅ Production‑Ready Keep widget API compatible while moving source ownership to package.
- [x] ✅ Production‑Ready Keep this pass focused on Settings primitives already in use; broader migration can be a follow-up.
- [x] ✅ Production‑Ready External package path remains `../../../flutter-packages/belluga_admin_ui`.

## Plan
### Phase 1 — Package Adoption in Settings
- [x] ✅ Production‑Ready Implement `tenant_admin_settings_section.dart` in external package `lib/src/widgets/`.
- [x] ✅ Production‑Ready Export the widget in package `lib/belluga_admin_ui.dart`.
- [x] ✅ Production‑Ready Update `tenant_admin_settings_screen.dart` to consume package export.
- [x] ✅ Production‑Ready Remove local duplicate widget file from app.

### Phase 2 — Local Package Cleanup
- [x] ✅ Production‑Ready Remove `flutter-app/packages/belluga_admin_ui` files now superseded by external package.

### Phase 3 — Validation
- [x] ✅ Production‑Ready Run `fvm flutter pub get`.
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.
- [x] ✅ Production‑Ready Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.

## Definition of done
- [x] ✅ Production‑Ready Settings screen uses package-provided section widget (no local duplicate).
- [x] ✅ Production‑Ready Local in-repo package folder is cleaned from `flutter-app`.
- [x] ✅ Production‑Ready Analyzer and target settings tests pass.

## Validation steps
1. Confirm package entrypoint exports `TenantAdminSettingsSection`.
2. Confirm app Settings screen builds with package import only for this widget.
3. Confirm `flutter-app/packages/belluga_admin_ui` is removed.
4. Confirm `pub get`, `analyze`, and target test pass.

## Outcome notes
- External package now owns `TenantAdminSettingsSection` and exports it through `belluga_admin_ui.dart`.
- `TenantAdminSettingsScreen` now relies on package export only (local duplicate import removed).
- In-repo folder `flutter-app/packages/belluga_admin_ui` was fully removed.
