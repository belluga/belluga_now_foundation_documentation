# TODO (V1): Map Soft Location Gate and Return Target Preservation

**Status:** Completed
**Primary Module Anchor:** `foundation_documentation/modules/map_poi_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Complexity:** medium
**Checkpoint Policy:** one review checkpoint before approval

## 1. Context
The tenant-public map currently uses a hard location gate through `AnyLocationRouteGuard`.

Current problems:
- entering `/mapa` or `/mapa/poi` without location permission sends the user to the location gate, but the post-permission navigation is not explicitly modeled as “return to the original target”;
- the map does not currently support the approved soft-gate behavior where the user may continue without location and still access the map using the fixed/default reference behavior;
- once inside the map without location permission, the user needs a clear, dismissible informational message explaining that the experience is using the fixed location/reference behavior for that access.

This slice changes the map-entry experience only. It does not redefine Discovery/Home/Event Search location semantics beyond what is already documented.

## 2. Scope
In scope:
- `/mapa` and `/mapa/poi` entry behavior under missing location permission
- `AnyLocationRouteGuard` behavior for map routes
- `LocationPermissionRoute` payload/flow as needed to preserve intended destination
- map runtime behavior when entering without location permission
- per-access informational snackbar/banner on the map
- tests for guard + route target preservation + map soft-gate message behavior
- module/doc sync for map/client experience

Out of scope:
- changing Home location-origin logic
- changing Event Search/Discovery location gating
- new backend/API contracts
- replacing the canonical `/location/permission` surface
- introducing a persistent user setting for fixed/manual location choice (VNext)

## 3. Decision Baseline (Frozen)
- `D-01` `/mapa` and `/mapa/poi` remain public tenant-public routes in web anonymous V1.
- `D-02` Missing location permission on map entry must trigger the canonical `/location/permission` gate before the map screen is shown.
- `D-03` If the user grants location permission from that gate, navigation must proceed to the originally requested destination, not to Home.
- `D-04` Map entry becomes a **soft gate**: if the user chooses `Continuar sem localização`, access to `/mapa` or `/mapa/poi` must still proceed.
- `D-05` In the soft-gate path, the map must behave with the fixed/default location reference path already supported by map/location repositories, instead of blocking map access.
- `D-06` On map entry without location permission, the map must show a dismissible informational top snackbar/message for that access only.
- `D-07` The message content must reuse the same fixed-location explanatory copy already approved for the Home location-origin dialog, adapted only as needed for the map surface.
- `D-08` Dismissing the informational message affects only the current map access; a new map entry without permission must show the message again.
- `D-09` The map soft-gate behavior must work for both `/mapa` and `/mapa/poi` direct URL entry.
- `D-10` Route/guard implementation must stay AutoRoute-native; no ad hoc navigator bypasses.

## 4. Relevant Prior Module Decisions
### `map_poi_module.md`
- Public map routes `/mapa` and `/mapa/poi` are already canonical tenant-public surfaces and must remain accessible in V1.
- Query-param hydration on `/mapa` and `/mapa/poi` is already canonical and must be preserved.

### `flutter_client_experience_module.md`
- Anonymous web allowlist explicitly includes `/mapa` and `/mapa/poi`.
- `/location/permission` is the single canonical public location gate surface in V1.

## 5. Module Decision Consistency Gate
| TODO Decision | Related Module Decision | Handling | Evidence |
| --- | --- | --- | --- |
| `D-01` | Map public-route allowlist in `map_poi_module.md` and `flutter_client_experience_module.md` | Preserve | Existing modules already mark `/mapa` and `/mapa/poi` as public V1 surfaces. |
| `D-02` | Canonical `/location/permission` surface in `flutter_client_experience_module.md` | Preserve | This slice reuses the same gate instead of creating a new location route. |
| `D-03` | Route hydration/AutoRoute governance | Preserve | This slice tightens target preservation; it does not change route ownership or path semantics. |
| `D-04` to `D-08` | No explicit prior module decision | Out of Scope | Existing modules do not yet define hard-vs-soft behavior for map entry under denied location permission. |
| `D-09` | Query-param hydration on `/mapa` and `/mapa/poi` | Preserve | Soft-gate flow must preserve direct URL hydration for both routes. |
| `D-10` | AutoRoute route ownership/governance | Preserve | No navigation bypasses allowed. |

## 6. Plan Review Gate
### Architecture
- Route guard must distinguish two outcomes from the location gate:
  - `granted` => proceed to original target
  - `continueWithoutLocation` => proceed to original target in soft mode
- Map UI should consume a short-lived entry flag/effect, not persist dismissal globally.

### Code Quality
- Avoid widget-owned global state for “show once” behavior.
- Keep guard outcome explicit with typed semantics rather than ambiguous `bool` where possible.

### Tests
- Add RED tests for:
  - `/mapa` denied -> gate -> grant -> target preserved
  - `/mapa/poi?poi=...` denied -> gate -> grant -> target preserved
  - `/mapa` denied -> continue without -> target preserved
  - map screen shows dismissible warning on soft-gate entry only
  - warning reappears on a fresh map access

### Performance
- Snackbar/message must be one-shot per access, not rebuilt repeatedly.

### Security
- No auth/promotion implications; route remains public by approved policy.

## 7. Issue Cards
### Issue `MG-01`
- **Severity:** high
- **Evidence:** `lib/application/router/guards/any_location_route_guard.dart`, `lib/presentation/shared/location_permission/screens/location_permission_screen/location_permission_screen.dart`
- **Why now:** current result handling does not model the original target explicitly, so redirect semantics are brittle.
- **Options:**
  - `A` Keep boolean result contract and infer target from stack state.
    - Effort: low
    - Risk: high
    - Blast radius: route guards + gate screen
    - Maintenance: poor
  - `B` Introduce explicit typed result / route-intent contract for the location gate and preserve original target through the guard.
    - Effort: medium
    - Risk: low
    - Blast radius: guard + route + tests
    - Maintenance: good
  - `C` Do nothing.
    - Effort: none
    - Risk: high
    - Blast radius: user-facing regression remains
    - Maintenance: poor
- **Recommendation:** `B`

### Issue `MG-02`
- **Severity:** medium
- **Evidence:** map routes are public but denied permission currently acts as hard block.
- **Why now:** approved product behavior is soft gate for the map.
- **Options:**
  - `A` Keep hard gate and only improve return target.
  - `B` Allow explicit continue-without-location and surface fixed-location notice in map.
  - `C` Do nothing.
- **Recommendation:** `B`

## 8. Failure Modes & Edge Cases
- Direct URL to `/mapa/poi?poi=...` without permission must still hydrate the intended POI after gate resolution.
- Browser/web denied-forever state must still allow `Continuar sem localização`.
- Granting permission from the gate when there is no previous route to pop back to must still land on the requested map route.
- Soft-gate message must not spam multiple times during the same map session.
- Re-entering the map later without permission must show the message again.

## 9. Uncertainty Register
- **Assumptions:** Map repositories already support fallback/default-center behavior without live location.
- **Unknowns:** Whether the current map screen has any hidden hard dependency on resolved live location for first render/centering.
- **Confidence:** medium-high

## 10. Definition of Done
- Map routes use soft-gate behavior under denied location permission.
- Granting permission returns to the originally requested target.
- Continuing without permission also reaches the originally requested target.
- Map shows the approved dismissible info message once per access when entered without permission.
- Route/widget tests lock the new behavior.
- Module docs are synchronized.

## 11. Delivery Outcome
- `AnyLocationRouteGuard` now preserves the originally requested `/mapa` or `/mapa/poi` target through the canonical `/location/permission` route by using explicit typed gate results instead of ambiguous boolean flow.
- Granting permission resumes the original map target instead of collapsing to Home.
- Choosing `Continuar sem localização` also resumes the original map target and arms a one-access soft-fallback runtime entry for the map.
- Map runtime now uses the tenant default origin/fixed-reference path for that access when entered through the soft gate.
- The map shows a dismissible top informational notice for that access only, reusing the approved fixed-location explanatory copy.
- The notice does not persist globally; a fresh map entry without permission shows it again.
- Guard, location-permission screen, and map-controller tests now lock grant, cancel, continue-without-location, target preservation, and soft-entry notice behavior.
- Module docs now describe map entry as a public soft-gated flow and no longer preserve any separate `not-live` route concept for map.

## 12. Rule / Workflow Sources To Follow
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
