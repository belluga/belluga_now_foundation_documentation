# TODO (VNext): Telemetry Architecture Review and Consolidation

**Status:** Completed  
**Owners:** Flutter Team  
**Date:** `2026-04-18`

## Closure Note
The original umbrella objective is no longer a live delivery owner. Telemetry runtime is already operating in production, the accepted `GetIt` repository/service resolution pattern is not an architecture defect in this project, and the broad review has been reduced to concrete delivery slices.

## Confirmed Evidence
- Production telemetry runtime is already established on the current Mixpanel-backed stack.
- Repository-level telemetry tests already cover contract methods including timed-event lifecycle, lifecycle observer wiring, and identity merge behavior:
  - `../flutter-app/test/infrastructure/repositories/telemetry_repository_test.dart`
- Direct regression tests already cover live trigger paths such as favorites toggle, push forwarding, route/screen observers, app lifecycle, and auth-wall/signup telemetry:
  - `../flutter-app/test/infrastructure/repositories/account_profiles_repository_test.dart`
  - `../flutter-app/test/infrastructure/services/push/push_telemetry_forwarder_test.dart`
  - `../flutter-app/test/infrastructure/services/telemetry/telemetry_route_observer_test.dart`
  - `../flutter-app/test/application/application_contract_test.dart`
  - `../flutter-app/test/application/router/guards/auth_route_guard_test.dart`
  - `../flutter-app/test/presentation/common/auth/screens/auth_login_screen/auth_login_effects_test.dart`
- Release-critical residual validation is now owned by the dedicated store-release child TODO:
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-funnel-metrics-validation.md`

## Residual Note
- Future telemetry work must stay attached to the concrete capability or release TODO that owns the flow; do not open a dedicated telemetry implementation TODO just to emit an event when the existing tracker service already covers that boundary.
- A separate release-validation TODO is acceptable only when it is auditing cross-flow metrics/readback evidence rather than owning event implementation.
- Do not reopen a generic telemetry umbrella unless a new cross-cutting architecture defect is demonstrated.
