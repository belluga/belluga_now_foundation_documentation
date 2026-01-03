# TODO (V1): Flutter Analyzer Cleanup

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Resolve all `fvm flutter analyze` issues introduced or surfaced by recent changes.

---

## Scope
- Fix analyzer errors in `lib/application/router/modular_app/module_settings.dart` and related routing integration.
- Remove implementation_imports warnings by exposing public exports in `push_handler` or adjusting imports.
- Add missing dependencies to `pubspec.yaml` (e.g., `meta`, `package_info_plus`, `geolocator_platform_interface`) as required by imports.
- Re-run `fvm flutter analyze` until zero issues.

## Out of Scope
- Refactoring unrelated code paths.
- Behavioral changes beyond routing/import/dependency fixes.

## Definition of Done
- [x] ✅ Production‑Ready `fvm flutter analyze` returns zero issues.

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter analyze` and confirm clean output.

## Decisions
- Prefer public exports over `src/` imports.

## Questions to Close
- None.

## References
- `flutter-app/pubspec.yaml`
- `flutter-app/lib/application/push/push_message_presenter.dart`
- `flutter-packages/push_handler/lib/push_handler.dart`
