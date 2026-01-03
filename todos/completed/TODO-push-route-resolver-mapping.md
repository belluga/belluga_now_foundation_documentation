# TODO (V1): Push Route Resolver Mapping

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Prevent push action navigation failures by resolving internal routes through an explicit mapping and safe guards.

---

## Scope
- Define a push route resolver strategy that maps incoming push **route keys** (e.g., `event_detail`, `map`) to registered AutoRoute paths or handler functions.
- Guard invalid/unknown routes with a no-op + log (do not throw).
- Update `ModuleSettings` route resolver to use the mapping and pass query parameters (e.g., `itemIDString`) when required.

## Out of Scope
- Adding new application routes or screens.
- Modifying push payload contracts.
- Backend changes.

## Definition of Done
- [x] ✅ Production‑Ready Push action navigation does not throw when route is unknown.
- [x] ✅ Production‑Ready Valid push routes resolve to their correct AutoRoute path.

## Validation Steps
- [x] ✅ Production‑Ready Trigger a push with internal route buttons (`event_detail`, `map`) and confirm navigation succeeds or logs a safe warning when unmapped.

## Decisions
- Map by **route keys** from payload (`route_key`) and translate to AutoRoute paths, passing `path_parameters`.
- Prefer explicit route mapping over direct `pushPath` with raw payload values.

## Questions to Close
- None.

## References
- `flutter-app/lib/application/router/modular_app/module_settings.dart`
- `flutter-app/lib/application/router/app_router.dart`
