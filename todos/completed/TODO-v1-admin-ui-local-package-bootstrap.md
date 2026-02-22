# TODO (V1): Admin UI Local Package Bootstrap

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Create a local reusable Flutter package for Admin UI widgets so the same visual/admin pattern can be reused across multiple projects, starting inside the current repository.

## Scope
- Create local package under `flutter-app/packages/`.
- Package initial target: reusable admin hub widgets already extracted in the app.
- Migrate current in-app hub widgets to the package and update app imports.
- Keep behavior, routes, and contracts unchanged (presentation-only extraction).
- Keep one-widget-per-file discipline in the package.

## Out of scope
- Publishing package externally.
- Large visual redesign pass.
- API/domain/controller behavior changes.

## Decisions
- [x] ✅ Production‑Ready Package is local (path dependency) for now.
- [x] ✅ Production‑Ready Package starts with hub primitives and can expand incrementally.
- [x] ✅ Production‑Ready App remains the integration host; package owns reusable widget source.

## Plan
### Phase 1 — Package Scaffold
- [x] ✅ Production‑Ready Create `packages/belluga_admin_ui/` with `pubspec.yaml` and `lib/` entrypoints.
- [x] ✅ Production‑Ready Define package exports with one widget per file in `lib/src/`.

### Phase 2 — Widget Migration
- [x] ✅ Production‑Ready Move/copy current reusable hub widgets from app into package.
- [x] ✅ Production‑Ready Update app imports to consume `package:belluga_admin_ui/...`.
- [x] ✅ Production‑Ready Remove duplicate local widget definitions no longer needed.

### Phase 3 — Integration Validation
- [x] ✅ Production‑Ready Add path dependency in root `pubspec.yaml`.
- [x] ✅ Production‑Ready Run `fvm flutter pub get`.
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.
- [x] ✅ Production‑Ready Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.

## Definition of done
- [x] ✅ Production‑Ready Local package exists and is consumed by current app.
- [x] ✅ Production‑Ready Reusable admin hub widgets are owned by package, not duplicated in app.
- [x] ✅ Production‑Ready Analyzer/tests pass for touched scope.

## Validation steps
1. Verify package compiles via root app dependency resolution.
2. Verify settings hub renders using package widgets.
3. Validate analyzer and target test suite.

## Outcome notes
- Created local package `packages/belluga_admin_ui` and migrated admin hub primitives into one-widget-per-file exports.
- Root app now consumes the package via `path` dependency in `pubspec.yaml`.
- Removed duplicated in-app hub widget files and kept settings screen wiring on package imports.
- Validation completed: `fvm flutter pub get`, `fvm flutter analyze`, `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.
