# TODO (V1): Location Permission Screen Minimal Alignment

**Status:** Completed
**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/map_poi_module.md`
**Complexity:** small
**Checkpoint Policy:** consolidated review before approval

## 1. Context
The anonymous-web/public location gate has been reduced to a single canonical surface:
- `/location/permission`

The previous `not-live` variant is now treated as leftover legacy and should not survive as a parallel product state.

Before implementation, the visual direction was validated in Stitch through the screen the product approved as:
- `Permissão de Localização (Minimalista)`

This TODO exists to translate that approved direction faithfully into Flutter while preserving one important runtime rule:
- colors must always come from the active Flutter theme, never from hardcoded Stitch hex values.

## 2. Scope
In scope:
- `lib/presentation/shared/location_permission/screens/location_permission_screen/**`
- `lib/presentation/shared/location_permission/routes/location_permission_route.dart`
- removal/alignment work required by the legacy `location_not_live` presentation route/screen/tests if they are no longer part of the public product surface
- location-permission widget/route tests
- doc sync for the canonical public location gate

Out of scope:
- changing permission-request mechanics in the controller beyond what the approved UI needs
- app-promotion behavior
- login/auth behavior
- Profile-owned location editing
- manual map-picked origin
- backend/DB-backed settings

## 3. Decision Baseline (Frozen)
- `D-01` `/location/permission` is the single canonical public location gate in V1.
- `D-02` `location_not_live` is legacy and should not remain as a second product-level screen.
- `D-03` Flutter implementation must follow the approved Stitch layout direction with high structural fidelity:
  - full-screen presentation
  - simple top back affordance
  - centered location visual
  - strong headline
  - short supporting copy
  - primary CTA
  - secondary CTA
- `D-04` Theme colors are authoritative. Stitch may guide spacing/hierarchy/composition, but no hardcoded design-token hex values should be introduced for this screen.
- `D-05` The approved copy direction is benefit-first, not system-permission-first.
- `D-06` Approved copy baseline:
  - title: `Veja o que está perto de você`
  - subtitle: `Ative sua localização para mostrar eventos e lugares mais relevantes próximos de você.`
  - primary CTA: `Permitir localização`
  - secondary CTA: `Continuar sem localização`
- `D-07` The informational visual block must stay generic. Do not use specific partner/event examples such as `Moqueca do Gaeta`; generic concepts like `Eventos` and `Gastronomia` are acceptable if the layout needs semantic placeholders.
- `D-08` This screen must not look like an OS permission dialog, a login screen, or an app-promotion screen.

## 4. Implementation Shape (Planned)
1. Remove the dual-screen assumption from the location-permission presentation layer.
2. Refactor `LocationPermissionScreen` to match the approved minimal full-screen structure.
3. Replace the current technical/system-oriented copy with the approved benefit-first copy.
4. Ensure the visual hierarchy is Stitch-faithful while all colors remain derived from `Theme.of(context).colorScheme` / text theme.
5. Update route and widget tests to lock:
   - canonical route presence,
   - approved copy,
   - absence of legacy `not-live` expectations where no longer valid,
   - full-screen structure rather than dialog-like composition.

## 5. Risks / Notes
- The old `location_not_live` screen has existing route/widget tests; removing or collapsing it requires intentional test updates rather than ad hoc deletions.
- The screen should remain controller-driven for permission actions, but the visual surface itself is pure UI and must not absorb business logic.
- The approved Stitch direction is aesthetic guidance, not token authority; the Flutter theme remains the source of truth for color.

## 6. Delivery Outcome
- `/location/permission` is now the single canonical public location gate in Flutter.
- Legacy `location_not_live` route/screen/tests were removed.
- The screen copy/layout now follows the approved minimal Stitch direction while deriving colors from the active Flutter theme.
- Route and widget tests were updated to lock the canonical surface and approved copy baseline.

## 7. References
- `foundation_documentation/todos/completed/TODO-v1-web-anon-tenant-public-route-hardening.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/map_poi_module.md`

## 8. Rule / Workflow Sources Used
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
