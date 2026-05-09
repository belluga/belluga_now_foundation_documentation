# Title
Location Permission Back Button Fix

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Note
- **Closed on:** `2026-04-18`
- **Closure reason:** the repo already reflects the intended bounded fix; this file remains as the historical record for the regression.

## Resolution Summary
- The visible back button on the location-permission boundary now resolves the cancel flow correctly without creating duplicate dismissals.
- Callback-owned navigation still keeps route ownership on the callback side.
- Guard-owned dismissal still pops the boundary route when dismissal is explicitly enabled.
- Existing `granted`, `continueWithoutLocation`, and `cancelled` semantics were preserved.

## Confirmed Evidence
- `lib/presentation/shared/location_permission/screens/location_permission_screen/location_permission_screen.dart`
- `test/presentation/shared/location_permission/screens/location_permission_screen_test.dart`
  - `back button returns cancelled result without closing when callback owns navigation`
  - `back button pops the route when guarded flow requests dismissal`
  - `granted result stays owned by guarded callback even when boundary dismissal is enabled`

## Residual Note
- General tenant-public back-governance ownership remains in `foundation_documentation/todos/completed/TODO-v1-canonical-back-navigation-governance-cutover.md` and the promoted `flutter_client_experience_module.md` rules. This file does not own app-wide back policy.
