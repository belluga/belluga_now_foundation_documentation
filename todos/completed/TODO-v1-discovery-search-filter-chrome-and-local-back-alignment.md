# TODO (V1): Discovery Search/Filter Chrome + Local Back Alignment

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Completed — production-ready.
**Owners:** Delphi (Flutter)
**Objective:** Align Discovery search/filter chrome with the current product intent by moving the search affordance into the `Descubra` section header, restoring Material 3-native chip styling, and making back navigation consume local search/filter state before leaving the screen.
**Complexity:** `medium`
**Checkpoint Policy:** one implementation checkpoint before approval and final decision-adherence validation before delivery.

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`, `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`

## Scope
- Adjust only the Discovery screen chrome/state handling in `EnvironmentType=tenant`, `main scope=tenant_public`.
- Deliver three aligned outcomes:
  - move the idle-state search trigger from App Bar action to the `Descubra` section header action;
  - make Discovery filter chips inherit the app Material 3 chip theme instead of widget-local styling overrides;
  - intercept back navigation with a strict `if/else`: clear active filter state or pop the route, never both in the same back action.
- Preserve existing Discovery content semantics:
  - `Tocando agora` and `Perto de você` still hide during active search and/or category-filtered result mode;
  - `Descubra` remains the section title;
  - category chips remain single-select and registry-driven.
- Add targeted automated coverage for the new chrome/back expectations.

## Out of Scope
- Backend/API/repository/domain changes.
- Discovery IA redesign beyond the three scoped adjustments.
- Any Home/Agenda work already pending in the current worktree.
- New routes or workspace/profile feature work.

## Rule/Workflow Sources
- `delphi-ai/main_instructions.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Relevant Prior Decisions
- `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
  - Discovery heading/layout remains `Descubra` with category chips directly below, single-select.
  - Search mode hides `Tocando agora` / `Perto de você` and keeps result-list mode.
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
  - `D-09`: colors must come from current app theme tokens (`ThemeData`), not fixed design hex values.
  - `D-10`: Discovery feed heading above chips must be `Descubra`; chips stay directly below this heading with single-select behavior.
  - `D-12`: entering search mode hides top editorial sections.
  - `D-14`: search-active mode hides `Descubra` heading + category chips.

## Module Decision Consistency Gate
| Prior Decision Source | Decision | Handling | Evidence / Rationale |
| --- | --- | --- | --- |
| `TODO-v1-public-account-profile-discovery-ui.md` | `Descubra` + chips directly below, single-select | `Preserve` | Search action moves into that same header; heading/chips remain in place when idle |
| `TODO-v1-public-account-profile-discovery-ui.md` | Search/filter mode hides `Tocando agora` / `Perto de você` | `Preserve` | No change to result-mode semantics |
| `TODO-v1-targeted-visual-polish.md` `D-09` | Theme-driven colors only | `Preserve` | Chip styling will stop forcing widget-local visual tokens |
| `TODO-v1-targeted-visual-polish.md` `D-10` | `Descubra` remains heading above chips | `Preserve` | Only an action affordance is added to the heading row |
| `TODO-v1-targeted-visual-polish.md` `D-12` + `D-14` | Search-active mode hides editorial sections and heading/chips | `Preserve` | Local back behavior follows strict `if/else`: active filter clears locally; no active filter pops route |

## Decision Baseline (Frozen)
- `D-01`: In idle Discovery state, the search entrypoint must live in the `Descubra` section header action, not in the App Bar action area.
- `D-02`: Search-active Discovery behavior remains functionally the same: the screen enters result mode, top editorial sections stay hidden, and the user can continue typing in the dedicated search UI.
- `D-03`: Back navigation must follow a strict `if/else` rule with mutually exclusive outcomes. If there is an active Discovery filter (`selected category` and/or non-empty `search text`), that back action only clears filters and must not pop the route. If there is no active filter, that back action pops the route and must not perform any local reset first. `Search mode active` by itself is not a separate back-handling condition; the decision is based only on whether filter state exists.
- `D-04`: Discovery filter chips must inherit the app Material 3 chip theme; widget-local overrides for selected/unselected chip background, outline, or text color are not allowed.
- `D-05`: The implementation scope is limited to Discovery presentation/controller/test files.

## Plan Review Gate (Medium)

### Issue Card P-01 — Search affordance feels attached to global chrome instead of local Discovery context
- Severity: `medium`
- Evidence: `lib/presentation/tenant_public/discovery/discovery_screen.dart:60`
- Why now: product wants search to feel like an action of the `Descubra` section, not a global App Bar command.
- Option A: keep search in App Bar.
- Option B (recommended): move idle-state search trigger to the `Descubra` header action and preserve active search UI.
- Option C: remove explicit search affordance and rely on a separate route/pattern later.
- Effort / Risk / Blast Radius:
  - A: zero effort, preserves current mismatch.
  - B: medium effort, low blast radius, localized to Discovery presentation.
  - C: low effort now, high UX cost.

### Issue Card P-02 — Filter chips look unnatural because widget overrides bypass the app theme
- Severity: `medium`
- Evidence: `lib/presentation/tenant_public/discovery/widgets/discovery_filter_chips.dart:34`
- Why now: selected/unselected chips are visually harsh and inconsistent with the current Material 3 theme.
- Option A (recommended): remove widget-local chip styling overrides and inherit theme styling.
- Option B: replace current overrides with new hardcoded colors.
- Option C: defer visual correction.
- Effort / Risk / Blast Radius:
  - A: low effort, low risk, theme-consistent.
  - B: low-medium effort, medium risk of future drift.
  - C: zero effort, keeps poor UI quality.

### Issue Card P-03 — Back navigation violates local-state expectation in Discovery result mode
- Severity: `high`
- Evidence: current screen has no local pop interception; active search/filter state hides sections but back exits route immediately.
- Why now: once Discovery changes into result mode, user expectation shifts from route-back to state-back.
- Option A: keep route pop behavior.
- Option B (recommended): intercept back with strict `if/else` handling: clear active filter state locally, otherwise pop the route.
- Option C: add a dedicated in-UI close/reset control only.
- Effort / Risk / Blast Radius:
  - A: zero effort, preserves UX mismatch.
  - B: medium effort, localized risk, best UX alignment.
  - C: medium effort, only partial fix because system back still mismatches unless also intercepted.

## Failure Modes & Edge Cases
- Search action disappears entirely when search mode is active and no local exit path remains.
- Back interception clears only part of the local state, leaving Discovery in an inconsistent mixed mode.
- Chip theming fix accidentally changes chip order, labels, or selection behavior.
- App Bar search field and local back interception fight each other and double-trigger reloads.

## Uncertainty Register
- Assumptions: keeping the current active search UI is acceptable as long as the idle trigger moves to the `Descubra` header.
- Unknowns: whether the product wants to keep a close icon visible while search is active in addition to local-back consumption.
- Confidence: `medium`.

## Implementation Tasks
- [x] ✅ Production‑Ready — Add a `Descubra` header action slot and move the idle-state search trigger there.
- [x] ✅ Production‑Ready — Remove widget-local chip styling overrides so Discovery chips inherit Material 3 theme styling.
- [x] ✅ Production‑Ready — Add local back handling based strictly on filter state presence (`selected category` and/or non-empty search text).
- [x] ✅ Production‑Ready — Add targeted widget/controller coverage for search trigger placement, local back behavior, and chip theming regression.
- [x] ✅ Production‑Ready — Run analyzer and targeted Discovery tests.

## Acceptance Criteria
- [x] ✅ Production‑Ready — Idle Discovery App Bar no longer shows the search icon.
- [x] ✅ Production‑Ready — `Descubra` header row shows the search action in idle state.
- [x] ✅ Production‑Ready — When Discovery has an active filter (`selected category` and/or non-empty search text), a back action only clears filters and does not pop the route in the same gesture/action.
- [x] ✅ Production‑Ready — When Discovery has no active filter, a back action pops the route directly and does not perform a local reset first.
- [x] ✅ Production‑Ready — Discovery chips visually inherit the app Material 3 chip theme.
- [x] ✅ Production‑Ready — Existing Discovery search/filter semantics remain unchanged apart from chrome/back behavior.

## Validation Plan
- [x] ✅ Production‑Ready — Automated: targeted Discovery widget tests for header search action and local back behavior.
- [x] ✅ Production‑Ready — Automated: targeted Discovery chip widget regression test.
- [x] ✅ Production‑Ready — Automated: `fvm dart analyze --format machine`.
- [x] ✅ Production‑Ready — Manual: verify idle-state search affordance placement and local back UX on Discovery.

## Decision Adherence Validation
| Decision | Status | Evidence |
| --- | --- | --- |
| `D-01` | `Adherent` | Idle App Bar search removed and header action added in `lib/presentation/tenant_public/discovery/discovery_screen.dart:56-90` and `:187-217`; widget proof in `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart:704-759`. |
| `D-02` | `Adherent` | Active search UI still uses the dedicated App Bar `TextField` while result-mode hiding remains keyed by `isSearching/selectedType/query` in `lib/presentation/tenant_public/discovery/discovery_screen.dart:57-75` and `:121-145`; backend-query preservation regression stays covered in `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart:848-876`. |
| `D-03` | `Adherent` | Strict `if/else` back handling is enforced by `PopScope(canPop: false)` and `_handleBackNavigation()` in `lib/presentation/tenant_public/discovery/discovery_screen.dart`, with controller reset logic in `lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart:310-351`; controller/widget proofs in `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart:309-396` and `:756-891`. |
| `D-04` | `Adherent` | Local chip color/outline overrides were removed from `lib/presentation/tenant_public/discovery/widgets/discovery_filter_chips.dart:25-46`; regression proof in `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart:671-702`. |
| `D-05` | `Adherent` | Chrome/back implementation is confined to Discovery presentation/controller/test surfaces: `lib/presentation/tenant_public/discovery/discovery_screen.dart`, `lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`, `lib/presentation/tenant_public/discovery/widgets/discovery_filter_chips.dart`, `lib/presentation/tenant_public/discovery/widgets/discovery_filter_header_delegate.dart`, and Discovery-targeted tests. |

## Manual Validation Evidence
- 2026-03-31 web smoke on `https://guarappari.belluga.space/descobrir`: idle Discovery rendered `Descubra` with search action in the section header and no idle App Bar search icon.
- 2026-03-31 web smoke on `https://guarappari.belluga.space/descobrir`: selecting `Artist` hid `Perto de você` and kept Discovery in result mode.
- 2026-03-31 web smoke on `https://guarappari.belluga.space/descobrir`: first tap on the in-screen back button cleared the active category filter while staying on `/descobrir`; second tap navigated back to `/`.
