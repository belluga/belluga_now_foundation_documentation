# TODO (V1): Admin UI Package External Location

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Move the reusable `belluga_admin_ui` package from inside `flutter-app/packages/` to the shared local workspace path `\\wsl.localhost\Ubuntu\home\elton\Dev\repos\flutter-packages`, preserving current behavior and contracts.

## Scope
- Create/update package at `/home/elton/Dev/repos/flutter-packages/belluga_admin_ui`.
- Copy current package sources to external location.
- Update `flutter-app/pubspec.yaml` path dependency to point to the external package path.
- Keep package API surface and widget behavior unchanged.
- Validate app dependency resolution and analyzer/test target.

## Out of scope
- Publishing package to pub.dev or private registry.
- Refactoring widget APIs or changing settings contracts/routes.
- Visual redesign changes.

## Decisions
- [x] ✅ Production‑Ready Keep package name `belluga_admin_ui`.
- [x] ✅ Production‑Ready Use local path dependency to external shared folder.
- [x] ✅ Production‑Ready Preserve one-widget-per-file structure as-is.

## Plan
### Phase 1 — External Package Bootstrap
- [x] ✅ Production‑Ready Ensure `/home/elton/Dev/repos/flutter-packages/belluga_admin_ui` exists.
- [x] ✅ Production‑Ready Copy package source (`pubspec.yaml`, `lib/`) from in-repo package to external path.

### Phase 2 — App Dependency Switch
- [x] ✅ Production‑Ready Update `flutter-app/pubspec.yaml` dependency path to external package location.
- [x] ✅ Production‑Ready Keep in-repo package folder untouched for now (cleanup can be a follow-up TODO).

### Phase 3 — Validation
- [x] ✅ Production‑Ready Run `fvm flutter pub get`.
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.
- [x] ✅ Production‑Ready Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.

## Definition of done
- [x] ✅ Production‑Ready App resolves `belluga_admin_ui` from external path.
- [x] ✅ Production‑Ready Package code in external folder matches the currently integrated widget set.
- [x] ✅ Production‑Ready Analyzer and target settings test pass.

## Validation steps
1. Confirm dependency path in `flutter-app/pubspec.yaml` points to external folder.
2. Confirm external package contains expected files and exports.
3. Validate `pub get`, `analyze`, and the target settings test.

## Outcome notes
- External package created at `/home/elton/Dev/repos/flutter-packages/belluga_admin_ui` with `pubspec.yaml` and `lib/` synced from the in-repo package source.
- Root app dependency switched to `../../../flutter-packages/belluga_admin_ui`.
- Local in-repo package folder was intentionally kept to avoid cleanup side effects in this scope.
