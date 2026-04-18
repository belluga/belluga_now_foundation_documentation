# TODO (V1): Home Location Origin Reference Mode

**Status:** Completed
**Primary Module Anchor:** `foundation_documentation/modules/tenant_home_composer_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/agenda_and_action_planner_module.md`
**Complexity:** medium
**Checkpoint Policy:** one review checkpoint before approval

## 1. Context
Home currently resolves its effective geographic origin with a simple rule:
- use fresh live user location when available,
- otherwise fallback to the tenant default origin.

This is insufficient for the current product need.

A user may open the app while physically far away from the tenant city. In that case, using live device location makes the Home agenda effectively unusable, because the runtime radius is bounded by the maximum distance configured for the tenant and the user sits outside the tenant operating area.

For V1 we need a **reference-origin mode** on Home so the experience remains useful even for users who are far from the city and just want to explore/test what is happening there.

This rule affects only Home for now.

## 2. Product Goal
If the live user location is close enough to the tenant default city origin, Home should use live location.
If the live user location is too far from the tenant default city origin, Home should switch to a fixed reference origin using the tenant default coordinates.

This allows:
- local users to see truly nearby Home agenda results,
- out-of-city users to still see relevant city-centered results instead of an empty/irrelevant Home.

## 3. Scope
In scope:
- Home effective-origin decision logic
- local persistence of Home location-origin mode/settings
- anonymous-session compatibility (device-local persistence still valid)
- Home header/status copy below the logo
- explanatory info affordance/dialog for location-origin mode
- Home-only tests covering inside-range vs outside-range behavior

Out of scope:
- backend/DB-backed settings persistence
- manual map-picked location override (still VNext)
- Discovery, Map, generic Event Search, or other geo consumers
- tenant-admin controls for this rule
- changing the tenant-configured default origin contract itself

## 4. Decision Baseline (Frozen)
- `D-01` This slice affects **Home only**.
- `D-02` Home must classify the runtime origin against the tenant default origin using the **tenant-configured maximum Home radius** as the decision boundary.
- `D-03` For V1, if the live user location is farther than `app_data.mapRadiusMaxMeters` from the tenant default origin, Home must switch to fixed-reference mode for Home only.
- `D-04` If the live user location is within that boundary, Home uses:
  - `use_live_location = true`
  - `fixed_location_reference = null`
- `D-05` If the live user location is outside that boundary, Home uses:
  - `use_live_location = false`
  - `fixed_location_reference = tenant default origin`
- `D-06` If no live location can be resolved, Home continues using the tenant default origin as the effective reference, but this is not the same product state as “outside range with explicit fixed-reference mode”; UI copy must stay coherent.
- `D-07` These settings must persist locally/device-side for both authenticated and anonymous users in V1.
- `D-08` The persistence commit owner must be controller/repository runtime settings, never widget-local state.
- `D-09` The Home header area below the logo must explicitly communicate the current mode:
  - `Usando sua localização.` when live mode is active
  - `Usando localização fixa.` when the fixed reference mode is active
- `D-10` That status row must include a small information affordance.
- `D-11` Tapping the status row/info affordance opens a dialog explaining which reference is being used and why.
- `D-12` The location-origin dialog is informational only in this slice; it does not yet offer manual map override.
- `D-13` The outside-range auto-fallback is a product decision, not a temporary debug hack.

## 5. Persistence Model for This Slice
V1 stays local/device-only.

The Home runtime must persist enough information to restore the chosen Home reference mode consistently across re-entry, including anonymous usage.

Minimum shape to support:
- `use_live_location: bool`
- `fixed_location_reference: { lat, lng, label? } | null`

Expected V1 behavior:
- inside range => persist live mode
- outside range => persist fixed-reference mode using the tenant default origin
- neither should require backend sync in this slice

## 6. UI / UX Requirements
The Home header currently shows a location/address row below the logo. This must evolve into a source-of-truth status row for Home origin mode.

Required behavior:
- when live mode is active:
  - show `Usando sua localização.`
  - include a small info icon
- when fixed-reference mode is active:
  - show `Usando localização fixa.`
  - include a small info icon
- tapping that row opens an explanatory dialog

Dialog intent:
- explain whether Home is using the live device location or the fixed city reference
- explain that fixed reference is used when the current location is too far from the city area served by the app
- do **not** introduce manual selection or deep settings navigation in this slice

## 7. Delivery Outcome
- `AppDataRepository` now persists Home-only location-origin settings locally/device-side, including anonymous sessions.
- `TenantHomeAgendaController` now classifies Home origin against the tenant default origin using the tenant-configured maximum Home radius as the boundary:
  - inside range => live location
  - outside range => fixed tenant default origin
  - unavailable live location => fixed tenant default origin with a distinct explanatory reason
- Home Agenda cache hydration now reuses the persisted fixed-reference mode across controller re-entry, avoiding cache misses when the user is physically far away from the tenant city.
- `TenantHomeController` now projects the repository-owned Home origin state into explicit UI status copy below the logo.
- The Home header now shows:
  - `Usando sua localização.`
  - `Usando localização fixa.`
  with a small info affordance and informational dialog.
- Local persistence remains V1-only/device-local; manual editing and backend/profile-backed persistence remain tracked in VNext.

## 8. Risks / Notes
- The current codebase already uses tenant default origin as a generic fallback, but it does not model that fallback as a persisted, explicit Home mode.
- Care is required to avoid leaking this Home-only rule into Event Search, Discovery, or Map before an explicit cross-surface decision.
- Reverse-geocoded address display and origin-mode display are different concerns; this slice should not keep them conflated.

## 9. References
- `foundation_documentation/todos/completed/TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-proximity-preferences-and-location-origin.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/todos/completed/TODO-v1-events-location-gating-and-tenant-default-origin.md`

## 10. Rule / Workflow Sources Used
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
