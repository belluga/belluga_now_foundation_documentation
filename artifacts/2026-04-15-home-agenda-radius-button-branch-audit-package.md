# Branch Audit Package — `feature/home-agenda-radius-button-behavior`

Derived artifact for external audit only. Not a source of truth.

## Review target

- Branch: `feature/home-agenda-radius-button-behavior`
- Remote: `origin/feature/home-agenda-radius-button-behavior`
- Commits under review:
  - `9229ba10` `Refactor home agenda controller ownership`
  - `fa2386de` `Refine agenda radius compact action layout`
- Governing TODO:
  - `foundation_documentation/todos/active/concluded_but_active/TODO-v1-events-radius-button-behavior.md`
- Related companion TODOs touched by the branch:
  - `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md`
  - `foundation_documentation/todos/active/concluded_but_active/TODO-v1-home-agenda-controller-boundary-plugin-rules.md`

## Why this branch is broader than the button UI

The initial request was the Home radius button behavior:

- expanded state before interaction
- compact state as soon as agenda scrolling starts
- compact state should render icon-only with distance badge below
- keep Material 3 theming and theme-driven colors

During implementation, the root cause was architectural:

- Home screen scroll ownership and agenda widget scroll ownership were split across controller boundaries in a way that prevented reliable compact-state signaling.
- The branch therefore includes controller-boundary hardening and analyzer rules to prevent the same anti-pattern from recurring.

## Expected user-visible result

On tenant Home agenda:

- Before scrolling, the radius control is a normal expanded chip with background.
- As soon as the agenda starts scrolling, the control compacts.
- In compact mode, the control has:
  - location icon
  - distance badge below the icon
  - no filled background
  - header alignment preserved
- Returning to the top restores the expanded state.

## Main implementation areas

### 1. Home agenda controller ownership and scroll signaling

Key files:

- `lib/presentation/tenant_public/home/screens/tenant_home_screen/tenant_home_screen.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/controllers/tenant_home_controller.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_section.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_section_view.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`

Relevant outcomes:

- Home screen owns the screen-level scroll controller.
- Agenda section receives the scroll controller instead of resolving an unrelated controller upward.
- Radius compact state is published from agenda scroll state using the same ownership chain.
- Compact state now triggers on first non-zero scroll movement.

### 2. Radius action layout and animation

Key files:

- `lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- `lib/presentation/tenant_public/schedule/screens/event_search_screen/models/agenda_app_bar_controller.dart`

Relevant outcomes:

- Expanded state remains Material 3, theme-driven, with background.
- Compact state uses icon plus badge only, without background fill.
- Compact geometry was widened enough for `50 km`.
- Badge is rendered below the icon.
- Transition between expanded and compact states is animated through the shared action container.

### 3. Analyzer rules to prevent controller-boundary regressions

Key files:

- `tool/belluga_analysis_plugin/lib/src/rules/controller_controller_dependency_forbidden_rule.dart`
- `tool/belluga_analysis_plugin/lib/src/rules/screen_descendant_widget_controller_resolution_forbidden_rule.dart`
- `tool/belluga_analysis_plugin/lib/src/rules/widget_controller_singleton_registration_forbidden_rule.dart`
- `tool/belluga_analysis_plugin/lib/src/rules/module_scoped_controller_dispose_forbidden_rule.dart`
- `tool/belluga_analysis_plugin/docs/rules.md`

Relevant outcomes:

- Controller-to-controller dependency anti-patterns are flagged.
- Screen descendants resolving widget-local controllers from above are flagged.
- Singleton registration misuse for widget controllers is flagged.
- Screen/widget disposal of module-scoped controllers is flagged more explicitly.

### 4. Supporting presentation cleanups needed by the architectural fix

Key files include:

- favorites/invites builder-to-view splits
- tenant admin account detail controller/test adjustments
- related Home tests and fixtures

These are part of the same branch because they were discovered during the controller-boundary hardening and analyzer-rule rollout.

## Validation already executed

- `fvm dart analyze --format machine`
- `fvm flutter test test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart`
- `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- `bash scripts/build_web.sh`
- Playwright validation against `https://guarappari.belluga.space`

## High-signal tests added or expanded

- `test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart`
  - expanded layout
  - compact layout
  - compact geometry assertions
  - bottom sheet radius flow
- `test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
  - compact-state setter behavior
  - first non-zero scroll compaction
  - outer shell scroll compaction
  - nested inner agenda scroll compaction and restore

## Explicit audit request

Please review this branch with emphasis on likely GitHub automated review findings, especially:

- hidden regressions from the controller-boundary refactor
- state ownership leaks across screen and widget controller layers
- disposal/lifecycle mistakes
- animation or layout edge cases in the compact radius action
- Material 3 / theme hardcoding regressions
- test gaps that could allow UI or scroll regressions through review

## Branch diff summary

- 39 files changed
- 2327 insertions
- 494 deletions

Main changed surfaces:

- Home presentation/controller wiring
- Home agenda section/controller
- Schedule app bar widget
- analyzer plugin rules and fixtures
- related tests
