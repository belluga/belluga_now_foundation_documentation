# TODO (V1): Events Radius Button Behavior

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `none`
**Next exact step:** Run final runtime smoke on Home agenda and `/agenda` for the tracked radius-change flow, then commit/push this branch for promotion.
**Owners:** Flutter Team
**Objective:** Improve the tenant-public agenda radius-definition affordance on both Home agenda and the dedicated `/agenda` screen with a two-state button that starts expanded before interaction and compacts into an icon with a distance badge after the user scrolls the events list, and emit frontend tracking whenever the user actually changes the selected radius, without changing current radius/filter/query behavior.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `small`
**Checkpoint Policy:** consolidated review before approval

---

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** The request is one bounded tenant-public UX behavior on the existing `/agenda` radius control with localized Flutter impact and no initiative-level decomposition need.
- **Direct-to-TODO rationale:** The scope is one concrete button-behavior slice with clear out-of-scope boundaries, no backend/API/schema dependency, and one approval conversation.

## Contract Boundary

- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and approval conversation.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update this TODO first and request renewed approval before continuing.

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/agenda_and_action_planner_module.md`, `foundation_documentation/modules/events_module.md`

## References

- `foundation_documentation/todos/completed/TODO-v1-screen-events-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md`
- `foundation_documentation/screens/modulo_agenda.md`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_body.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- `foundation_documentation/artifacts/tmp/events-radius-button-compact-trigger-review-package-20260415.md`
- `foundation_documentation/artifacts/tmp/events-radius-button-compact-trigger-review-dispatch-20260415.md`
- `foundation_documentation/todos/completed/TODO-v1-telemetry-frontend.md`
- `flutter-app/lib/domain/repositories/telemetry_repository_contract.dart`
- `flutter-app/test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/controllers/tenant_home_controller_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`

## Scope

- [ ] Replace the tenant-public agenda radius action from a plain icon into a clearer two-state affordance on the supported agenda surfaces.
- [ ] Expanded state: before meaningful list scroll, show a pill-style radius control with icon plus current distance label.
- [ ] Compact state: after the user scrolls the events list away from the top, animate the control into an icon-first variant with the distance rendered as a badge/counter.
- [ ] Keep both states wired to the existing radius bottom sheet and current radius-selection semantics on Home agenda and `/agenda`.
- [ ] Emit one non-blocking frontend telemetry event when the user changes the selected radius on Home agenda or `/agenda`.
- [ ] Preserve route ownership in `EnvironmentType=tenant`, `main scope=tenant_public`.

## Out of Scope

- [ ] Backend/API/query contract changes.
- [ ] Tenant-admin telemetry settings/catalog changes.
- [ ] Radius persistence changes or cross-surface unification.
- [ ] Discovery/map/profile radius UX changes.
- [ ] New filter/search semantics or a broader `/agenda` header redesign.

## Bounded But Elastic Guardrails

- **May stay inside this TODO:** controller-owned scroll chrome state for Home agenda and `/agenda`, shared `AgendaAppBar` presentation changes needed to support the two states, targeted widget/screen tests, and a lightweight agenda screen doc sync if needed.
- **Must update or split the TODO:** any attempt to unify radius persistence across surfaces, change backend-owned proximity contracts, or expand into broader events-screen polish beyond this radius-button behavior.

## Definition of Done

- [ ] Home agenda and `/agenda` show a clearer radius affordance before user interaction.
- [ ] Scrolling the events list compacts the radius affordance into the icon-plus-distance-badge form without breaking layout continuity.
- [ ] Returning the list to the top restores the expanded pre-interaction state predictably.
- [ ] Both states still open the existing radius selector and preserve current radius behavior.
- [ ] Actual user radius changes emit telemetry exactly once per effective change, with enough properties to distinguish Home vs `/agenda`.
- [ ] Initial seed/restore flows and no-op re-selection do not emit radius-change telemetry.
- [ ] Automated coverage proves the morph behavior on the supported agenda surfaces and preserves existing bottom-sheet interaction.

## Validation Steps

- [x] `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/controllers/tenant_home_controller_test.dart`
- [x] `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart`
- [x] `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- [x] `fvm flutter test test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart`
- [x] `fvm flutter test test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- [x] `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- [x] `fvm flutter test test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- [ ] `fvm dart analyze --format machine`
- [ ] Runtime smoke on the final implementation: Home agenda at rest, compact on the first shell-scroll movement, compact while the inner list scrolls, restored after returning to top, existing radius bottom sheet opening from both expanded and compact states, and radius-change telemetry emitted only on real user changes.

## External Dependency Readiness

| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| none | n/a | `healthy` | `2026-04-15` | local code/doc inspection | n/a |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `n/a` | `n/a` | n/a | n/a | `n/a` |

## Canonical Module Anchors (Required Before `APROVADO`)

- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/screens/modulo_agenda.md` only if the compact/expanded radius affordance needs durable screen-level documentation after implementation
- **Module decision consolidation targets:**
  - No canonical module-doc change expected unless execution reveals a contract ambiguity around radius ownership or tenant-public agenda chrome

## Decision Pending (Resolve Before Freeze)

- [ ] none

## Module Decision Baseline Snapshot

| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `FCX Presentation DI Matrix` | Screens/widgets consume controller-owned state; screen-local UI ownership is constrained. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1.1 Presentation DI Matrix`) |
| `AGD-05` | Backend geo filtering is authoritative; agenda/search must not apply local radius filtering after fetch. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` (`7. Canonical Decision Baseline`) |
| `AGD-06` | Persisted radius preference in V1 is Home-only; Event Search is not automatically aligned. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` (`7. Canonical Decision Baseline`) |
| `EVS-FILTER-01` | Events list/filter semantics remain unchanged in MVP. | `Preserve` | `foundation_documentation/modules/events_module.md` (`4. Canonical Decision Baseline`) |

## Decision Baseline (Frozen Before Implementation)

- [ ] `D-01` This slice is Flutter-only and must not change event query contracts, filter semantics, or radius persistence rules.
- [ ] `D-02` On `/agenda`, the radius action starts as an expanded pill before meaningful list scroll and compacts after the list is scrolled away from the top.
- [ ] `D-03` The compact/expanded state is controller-owned and derived from the events-list scroll position rather than widget-local mutable state. On Home, that ownership stays inside the agenda widget boundary instead of leaking upward into the screen controller.
- [ ] `D-04` Both visual states open the existing radius bottom sheet and keep the current selection/update semantics.
- [ ] `D-05` Home-specific persisted/explicit-confirmation radius behavior remains Home-only in this slice.
- [ ] `D-06` Validation must prove the new affordance in automated Flutter coverage before delivery.
- [ ] `D-07` Radius-change tracking must use the existing frontend telemetry repository path, stay non-blocking for UI actions, and fire only after the effective selected radius actually changes.
- [ ] `D-08` Home agenda and `/agenda` must share one curated telemetry event name (`agenda_radius_changed`) and distinguish the emitting surface through event properties rather than separate event names.

## Questions To Close

- [ ] none

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `EventSearchScreenController` is the correct owner for the new compact/expanded chrome state because it already owns the list `ScrollController` and query refresh lifecycle. | `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart` | We would need a different controller-owned abstraction for scroll chrome state. | `High` | `Keep as Assumption` |
| `A-02` | `AgendaAppBar` is shared by Home and `/agenda`, so the compact behavior must be opt-in or safe-default to avoid Home regression. | `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_app_bar.dart` + `flutter-app/lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart` | A shared always-on compact behavior could unintentionally change Home. | `High` | `Keep as Assumption` |
| `A-03` | The current radius bottom sheet contract is acceptable as-is for `/agenda`; the request is about the trigger affordance, not a selector-flow redesign. | Current `/agenda` implementation + user request scope | The slice would need to be reopened as a broader radius-selection redesign. | `Medium` | `Keep as Assumption` |
| `A-04` | The existing frontend telemetry pipeline can accept one new radius-change event without backend contract work, as long as Flutter uses the canonical telemetry repository path and a curated event name/property envelope. | `foundation_documentation/todos/completed/TODO-v1-telemetry-frontend.md` + existing Flutter telemetry calls (`map_filter_applied`, `favorite_artist_toggled`) | We would need a separate telemetry contract-alignment slice before implementation. | `Medium` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces

- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/models/agenda_app_bar_controller.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_app_bar.dart`
- `flutter-app/test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart`
- `flutter-app/test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- optionally `foundation_documentation/screens/modulo_agenda.md`

### Ordered Steps

1. Add fail-first coverage for Home and `/agenda` radius-change telemetry so only real user changes emit the curated event.
2. Inject the canonical telemetry dependency into the agenda controllers that own radius mutation and emit `agenda_radius_changed` with surface + previous/current radius properties.
3. Preserve the existing compact/expanded button behavior and confirm the telemetry path does not interfere with current refresh/persistence behavior.
4. Run targeted Flutter tests and analyzer, then rerun runtime smoke on the final behavior.
5. Update the agenda screen doc only if the telemetry interaction contract now needs durable screen-level guidance.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** This is behavior-defining UI work on a shared widget/controller surface.
- **Fail-first target(s):**
  - Home radius telemetry only after effective user change
  - `/agenda` radius telemetry only after effective user change

### Runtime / Rollout Notes

- No migrations.
- No backend deploy coupling expected.
- Shared-widget regression risk is limited to Home and Event Search and must be explicitly checked.

## Plan Review Gate (Abbreviated for `small`)

### Issue Card `UI-01` — Shared widget regression risk

- **Severity:** `medium`
- **Why it matters now:** `AgendaAppBar` is shared by Home and `/agenda`, so an unsafe change could regress Home radius UX.
- **Option A (Recommended):** add controller-owned compact-state support with a safe default of `false`, and enable it only where `/agenda` owns the scroll signal.
  - **Effort:** `medium`
  - **Risk:** `low`
  - **Blast radius:** `local-shared`
  - **Maintenance burden:** `low`
- **Option B:** fork a second agenda-app-bar widget just for `/agenda`.
  - **Effort:** `medium`
  - **Risk:** `medium`
  - **Blast radius:** `module`
  - **Maintenance burden:** `medium`
- **Option C:** keep the current icon-only radius action.
  - **Effort:** `low`
  - **Risk:** `high`
  - **Blast radius:** `none`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Issue Card `UI-02` — Wrong ownership for scroll-driven chrome

- **Severity:** `medium`
- **Why it matters now:** widget-local scroll/animation state would violate the current Flutter ownership contract for this screen surface.
- **Option A (Recommended):** derive compact state in `EventSearchScreenController` from its existing `ScrollController`.
  - **Effort:** `low`
  - **Risk:** `low`
  - **Blast radius:** `local`
  - **Maintenance burden:** `low`
- **Option B:** store compact state in `EventSearchScreen` widget-local mutable state.
  - **Effort:** `low`
  - **Risk:** `medium`
  - **Blast radius:** `local`
  - **Maintenance burden:** `medium`
- **Option C:** do not compact on scroll.
  - **Effort:** `low`
  - **Risk:** `high`
  - **Blast radius:** `none`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Failure Modes & Edge Cases

- [ ] The compact control overflows app-bar actions on narrow mobile widths.
- [ ] The compact state oscillates or jitters near the top of the list.
- [ ] Home unintentionally inherits the compact treatment.
- [ ] The compact control opens a different bottom sheet or loses current accessibility semantics.

### Residual Unknowns / Risks

- [ ] The exact expanded-pill width may need one visual iteration after seeing it beside the invite/history actions on smaller devices.

## Additional Architectural Opinions

- **Needed:** `yes`
- **Why ambiguity remains:** Manual Home behavior still failed after a locally passing implementation, and the user explicitly requested external audit on the compact-trigger ownership path under `NestedScrollView`.

## Independent No-Context Critique Gate

- **Critique decision:** `required`
- **Why this decision:** The user explicitly requested external audit after the Home compact trigger still failed in manual use despite green local tests, so a bounded no-context critique was required to validate the correct ownership boundary.

## External Critique Resolution

- **Bounded package:** `foundation_documentation/artifacts/tmp/events-radius-button-compact-trigger-review-package-20260415.md`
- **Dispatch packet:** `foundation_documentation/artifacts/tmp/events-radius-button-compact-trigger-review-dispatch-20260415.md`
- **Finding `EX-01` — earlier screen-controller ownership reading was superseded by runtime validation:** `Integrated`
  Home compact state must follow the real agenda-list scroll path. The current local implementation derives that signal inside the Home agenda subtree through `TenantHomeAgendaController` plus `HomeAgendaBody` scroll notifications; the earlier screen-controller / outer `NestedScrollView` ownership interpretation was rejected.
- **Finding `EX-02` — validation evidence corrected to the real signal path:** `Integrated`
  Automated coverage now freezes the Home agenda compact-state hysteresis at the agenda-controller level and the screen-level propagation from the agenda controller stream into the shared app bar. Real-browser validation on `guarappari.belluga.space` confirmed the expanded label at rest and the compact state after agenda scroll.

## Approval Rule

Implementation must not begin until the user replies with the explicit token: **APROVADO**.

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)

| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `flutter-architecture-adherence` | Flutter presentation + shared widget/controller ownership are in scope | controller-owned state and screen purity | widget-owned controller/scroll state | load after approval |
| `flutter-widget-local-state-heuristics` | scroll-driven chrome state could drift into widget-local state | ephemeral-only local state boundary | `setState`-owned screen chrome behavior | load after approval |
| `rule-flutter-flutter-screen-workflow-glob` | `/agenda` screen file is in scope | tenant-public ownership and doc sync discipline | ambiguous screen ownership or undocumented changes | load after approval |
| `test-creation-standard` | behavior-defining UI coverage will change | fail-first protection on shared widget behavior | retrofit-only weak assertions | load after approval |
