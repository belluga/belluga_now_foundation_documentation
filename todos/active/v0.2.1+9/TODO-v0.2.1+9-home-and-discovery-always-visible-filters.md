# TODO (v0.2.1+9): Home and Discovery Always-Visible Filters

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User product direction on 2026-06-08 identified a shared tenant-public UX issue across the two main list surfaces that expose public discovery filters:

1. Home agenda events currently hide filters behind a title-row icon button.
2. Discovery account profiles currently hide filters behind a title-row icon button.
3. Both surfaces also treat filter visibility as a collapsible panel state instead of default surface chrome.

Current repo evidence shows that this is one shared Flutter contract, not two unrelated widgets:

- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_app_bar.dart` currently renders `home-agenda-filter-button` and exposes active-filter state through a badge on that icon button.
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_section_view.dart` currently gates the Home `DiscoveryFilterBar` behind `isDiscoveryFilterPanelVisibleStreamValue`.
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart` currently closes the Home filter panel on scroll through `_hideDiscoveryFilterPanelWhenScrolled(...)`.
- `flutter-app/lib/presentation/tenant_public/discovery/discovery_screen.dart` currently renders `discovery-filter-button` inside the `Descubra` header and only mounts the canonical Discovery filter bar when `showFilterPanel` is true.
- `flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart` currently auto-hides the Discovery filter panel on scroll and drives reveal/open/close behavior through `openDiscoveryFilterPanelForReveal()` and `closeDiscoveryFilterPanel()`.
- `flutter-app/lib/presentation/shared/discovery_filters/public_discovery_filter_controller_mixin.dart` currently exposes shared panel-open semantics (`toggleDiscoveryFilterPanel`, `setDiscoveryFilterPanelVisible`, `updateDiscoveryFilterPanelVisibilityFromScroll`) used by both surfaces.
- Existing Flutter tests explicitly freeze the toggle/collapsible behavior today:
  - `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
  - `flutter-app/test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`

This TODO exists to establish the v0.2.1+9 canonical tenant-public behavior:

- Home events filters are always visible when that surface has canonical filters to show.
- Discovery account-profile filters are always visible when that surface has canonical filters to show.
- Neither surface depends on a toggle button or scroll-driven panel collapse for baseline filter visibility.

Important existing authority that must stay coherent:

- `foundation_documentation/modules/account_profile_catalog_module.md` freezes that Discovery search mode hides the top discovery hierarchy chrome (`PCO-09`).
- This TODO therefore changes the default list/header presentation of filters, not the already-approved rule for Discovery search mode.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-1-plus9-home-and-discovery-always-visible-filters`
- **Why this is the right current slice:** this is one bounded Flutter-only tenant-public filter-visibility slice across the shared Home/Discovery filter-bar contract, without reopening backend filter semantics or admin filter configuration.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the request is already concrete, user-visible, and limited to removal of the title-row toggle plus default visible filter chrome on two existing surfaces that already share the same controller mixin and filter-bar component.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `UX`, `Contract-Change`, `Flutter`, `Tenant-Public`, `Home`, `Discovery`, `User-Visible`
- **Next exact step:** implement the always-visible filter chrome contract in Flutter and freeze focused Home/Discovery validation evidence.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** the TODO contract is now defined, but no Flutter implementation or validation evidence exists yet.
- **Exit condition:** the Flutter surfaces and tests are updated locally and the TODO can move to `review` or `Local-Implemented`.

## Scope
- [ ] Remove the title-row filter toggle button from the Home agenda events surface.
- [ ] Remove the title-row filter toggle button from the Discovery account-profile list surface.
- [ ] Render the existing canonical `DiscoveryFilterBar` by default whenever the relevant surface has a non-empty canonical filter catalog and its base list chrome is present.
- [ ] Eliminate Home/Discovery dependence on a controller-owned open/close panel state for baseline filter visibility.
- [ ] Eliminate scroll-driven auto-hide behavior for the Home/Discovery filter bar on these two surfaces.
- [ ] Preserve current filter selection persistence, runtime catalog loading, and backend-owned filter semantics.
- [ ] Preserve the approved Discovery search-mode contract that hides the top discovery hierarchy chrome while search mode is active.
- [ ] Preserve the empty-catalog behavior: when a surface has no canonical filters configured, it still does not render an empty filter shell or orphan toggle affordance.
- [ ] Add focused Flutter controller/widget/runtime evidence for the new always-visible filter behavior on Home and Discovery.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Active Work State Semantics
- `implementation`: the TODO is still gaining or changing implementation/test evidence.
- `review`: local implementation is materially complete, but the TODO remains in `active/` because package-wide review, CI-equivalent, or runtime validation is still open.
- `blocked`: execution is paused on an explicit blocker; `Blocker Notes` are mandatory.
- `n/a once moved out of active`: use after the TODO moves to `promotion_lane/` or `completed/`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<pending>`, `foundation_documentation:<current>`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `flutter-app: dev; foundation_documentation: main`
- **Production-ready threshold for this TODO:** `flutter-app: stage or main as applicable`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `flutter Home + Discovery always-visible filters` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `foundation documentation / TODO evidence` | `<current>` | `n/a` | `n/a` | `<pending>` | `drafted` |

## Out of Scope
- [ ] Map filter tray behavior.
- [ ] Tenant-admin discovery-filter configuration surfaces.
- [ ] Backend filter semantics, query payload shape, taxonomy pruning, or persistence contracts.
- [ ] Reopening the approved Discovery search-mode rule that hides top discovery hierarchy chrome while searching.
- [ ] Home radius/invite action redesign beyond the filter visibility changes needed here.
- [ ] A broader visual redesign of the filter chips/bar beyond the minimal layout/composition needed to keep them always visible.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** Home/Discovery widget/controller composition changes, shared public filter-mixin simplification, focused key/semantics updates, and the exact test/runtime evidence needed to prove the new visibility rule.
- **Must update or split the TODO:** map/admin/public filter unification, search-mode UX redesign, backend filter-contract changes, or a broader Home/Discovery IA redesign beyond removing the toggle and collapse behavior.

## Definition of Done
- [ ] `DOD-01` Home agenda no longer renders a filter toggle icon button in its header chrome.
- [ ] `DOD-02` Discovery no longer renders a filter toggle icon button in the `Descubra` header chrome.
- [ ] `DOD-03` Home agenda renders its canonical filter bar by default whenever the Home events filter catalog is non-empty.
- [ ] `DOD-04` Discovery renders its canonical filter bar by default whenever the Discovery account-profile filter catalog is non-empty and the default list chrome is present.
- [ ] `DOD-05` Home and Discovery no longer auto-hide their filter bar on scroll through the shared panel-visibility path.
- [ ] `DOD-06` Discovery search mode still hides the top discovery hierarchy chrome as already approved; this TODO does not regress that contract.
- [ ] `DOD-07` Surfaces with an empty canonical filter catalog still avoid rendering empty filter chrome.
- [ ] `DOD-08` Active filters remain visible through the selected chips/bar state itself rather than through a title-row badge on a toggle button.
- [ ] `DOD-09` Focused Flutter automated coverage and final runtime evidence prove the always-visible filter behavior on Home and Discovery.

## Validation Steps
- [ ] Add fail-first Flutter coverage or update existing fail-first expectations that currently freeze the toggle-button/collapsible-panel model on Home and Discovery.
- [ ] Add/update Home agenda tests proving filters render without `home-agenda-filter-button` and stay visible without a panel-open state.
- [ ] Add/update Discovery tests proving filters render without `discovery-filter-button`, stay visible in the default listing surface, and remain hidden in search mode per the existing contract.
- [ ] Add/update shared controller/mixin coverage proving scroll no longer closes the filter bar for these surfaces.
- [ ] Run focused Flutter Home/Discovery filter tests and analyzer.
- [ ] Build the final web bundle used for runtime verification.
- [ ] Run final runtime evidence for Home and Discovery on the approved browser/device lane after the updated bundle/runtime target is published.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` Home agenda no longer renders a filter toggle icon button in its header chrome. | `widget+runtime` | `<planned Home agenda widget test + final runtime proof>` | `local Flutter + browser/device` | `planned` | Must explicitly prove button removal. |
| `DOD-02` | `Definition of Done` | `DOD-02` Discovery no longer renders a filter toggle icon button in the `Descubra` header chrome. | `widget+runtime` | `<planned Discovery widget test + final runtime proof>` | `local Flutter + browser/device` | `planned` | Must explicitly prove button removal. |
| `DOD-03` | `Definition of Done` | `DOD-03` Home agenda renders its canonical filter bar by default whenever the Home events filter catalog is non-empty. | `widget+runtime` | `<planned Home agenda filter-bar test + final runtime proof>` | `local Flutter + browser/device` | `planned` | Focuses on default visible mounting. |
| `DOD-04` | `Definition of Done` | `DOD-04` Discovery renders its canonical filter bar by default whenever the Discovery account-profile filter catalog is non-empty and the default list chrome is present. | `widget+runtime` | `<planned Discovery filter-bar test + final runtime proof>` | `local Flutter + browser/device` | `planned` | Must prove default listing behavior, not search mode. |
| `DOD-05` | `Definition of Done` | `DOD-05` Home and Discovery no longer auto-hide their filter bar on scroll through the shared panel-visibility path. | `test+runtime` | `<planned controller/mixin tests + final runtime proof>` | `local Flutter + browser/device` | `planned` | Supersedes the current scroll-hide model. |
| `DOD-06` | `Definition of Done` | `DOD-06` Discovery search mode still hides the top discovery hierarchy chrome as already approved. | `widget+runtime` | `<planned Discovery search-mode regression test + runtime proof>` | `local Flutter + browser/device` | `planned` | Preserves `PCO-09`. |
| `DOD-07` | `Definition of Done` | `DOD-07` Surfaces with an empty canonical filter catalog still avoid rendering empty filter chrome. | `widget` | `<planned empty-catalog regression tests>` | `local Flutter` | `planned` | Avoids replacing the button with empty layout noise. |
| `DOD-08` | `Definition of Done` | `DOD-08` Active filters remain visible through the selected chips/bar state itself rather than through a title-row badge on a toggle button. | `widget` | `<planned selected-filter state tests>` | `local Flutter` | `planned` | Selected chips become the visible active-state cue. |
| `DOD-09` | `Definition of Done` | `DOD-09` Focused Flutter automated coverage and final runtime evidence prove the always-visible filter behavior on Home and Discovery. | `test+runtime` | `<planned focused suites + runtime evidence>` | `local Flutter + browser/device` | `planned` | Final acceptance requires both surfaces. |
| `VAL-01` | `Validation Steps` | `VAL-01` Add fail-first Flutter coverage or update existing fail-first expectations that currently freeze the toggle-button/collapsible-panel model on Home and Discovery. | `test` | `<planned focused Flutter tests>` | `local Flutter` | `planned` | Must demonstrate the old behavior is intentionally being superseded. |
| `VAL-02` | `Validation Steps` | `VAL-02` Add/update Home agenda tests proving filters render without `home-agenda-filter-button` and stay visible without a panel-open state. | `test` | `<planned tenant_home_agenda_controller_test.dart>` | `local Flutter` | `planned` | Directly protects the Home contract. |
| `VAL-03` | `Validation Steps` | `VAL-03` Add/update Discovery tests proving filters render without `discovery-filter-button`, stay visible in the default listing surface, and remain hidden in search mode per the existing contract. | `test` | `<planned discovery_screen_controller_test.dart>` | `local Flutter` | `planned` | Protects both the new and preserved Discovery rules. |
| `VAL-04` | `Validation Steps` | `VAL-04` Add/update shared controller/mixin coverage proving scroll no longer closes the filter bar for these surfaces. | `test` | `<planned shared filter visibility tests>` | `local Flutter` | `planned` | Guards the shared logic path. |
| `VAL-05` | `Validation Steps` | `VAL-05` Run focused Flutter Home/Discovery filter tests and analyzer. | `test` | `<planned flutter test + analyze commands>` | `local Flutter` | `planned` | CI-equivalent evidence is still required. |
| `VAL-06` | `Validation Steps` | `VAL-06` Build the final web bundle used for runtime verification. | `build` | `<planned bash scripts/build_web.sh ../web-app dev>` | `local/web` | `planned` | Required before browser runtime proof. |
| `VAL-07` | `Validation Steps` | `VAL-07` Run final runtime evidence for Home and Discovery on the approved browser/device lane after the updated bundle/runtime target is published. | `runtime` | `<planned Playwright readonly or ADB integration evidence>` | `browser/device` | `planned` | Final visible-surface proof. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto-tech-lead` | `operational-coder` | This session opened the governing TODO and froze the intended Home/Discovery filter visibility contract for implementation. | `foundation_documentation/todos/active/v0.2.1+9/**` -> `flutter-app/**` | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** the slice is Flutter-only and user-visible, but it stays inside one shared filter-bar/panel contract across two public surfaces without reopening backend or admin behavior.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
- **Planned decision promotion targets (module sections):**
  - `account_profile_catalog_module.md` section `4.1 Tenant-Public Discovery Listing Contract`
  - `agenda_and_action_planner_module.md` section `3.4 Client Event Payload (Agenda API)` for Home agenda chrome notes
  - `flutter_client_experience_module.md` tenant-public filter/list interaction notes if a shared Flutter contract needs explicit promotion
- **Module decision consolidation targets (required):**
  - `account_profile_catalog_module.md` `PCO-09`
  - `tenant_home_composer_module.md` `HOM-07`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Home and Discovery must stop using a title-row toggle button as the baseline way to expose their public filters.
- [x] `D-02` “Always visible” means the canonical `DiscoveryFilterBar` is mounted by default when the relevant catalog is non-empty and the owning surface chrome is present; it is no longer a reveal/collapse panel on these two surfaces.
- [x] `D-03` Discovery search mode remains governed by the existing rule that hides top discovery hierarchy chrome; this TODO does not reopen that product contract.
- [x] `D-04` Empty filter catalogs still produce no filter chrome; the new rule is not “render an empty bar at all costs.”
- [x] `D-05` Scroll must not auto-close the Home/Discovery filter bar through shared panel-visibility helpers; selected chips on the always-visible bar replace the toggle badge as the active-state cue.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `account_profile_catalog_module.md#PCO-09` | Discovery search mode hides the top discovery hierarchy chrome while preserving the unfiltered base grid until query input exists. | `Preserve` | `foundation_documentation/modules/account_profile_catalog_module.md` section `4.1`, decision `PCO-09` |
- | `tenant_home_composer_module.md#HOM-07` | Home Agenda aggregate state is single-writer and repository-owned. | `Preserve` | `foundation_documentation/modules/tenant_home_composer_module.md` decision `HOM-07` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Visibility of Home/Discovery filters is determined by surface composition plus catalog presence, not by an explicit panel-open boolean.
- [x] `D-02` Home/Discovery no longer expose an open/close filter affordance in the title row.
- [x] `D-03` Selected filters remain persistent and backend-owned exactly as today; only the visibility/presentation contract changes.
- [x] `D-04` The narrowest acceptable implementation is the one that removes toggle/collapse behavior without treating this TODO as a broader redesign license.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The existing shared `DiscoveryFilterBar` component remains the canonical filter UI; this slice changes visibility/composition, not chip taxonomy or filter mechanics. | User only requested removing the toggle and keeping filters visible; current Home and Discovery already share `DiscoveryFilterBar`. | A broader UI redesign or new component would expand scope and require renewed approval. | `High` | `Keep as Assumption` |
| `A-02` | Discovery search mode should keep its current hide-chrome rule because the user did not ask to reopen search UX and the module canon already freezes that behavior. | `account_profile_catalog_module.md` `PCO-09`; current Discovery composition already hides header/filter chrome while searching. | The TODO would need a product decision that explicitly supersedes `PCO-09`. | `High` | `Keep as Assumption` |
| `A-03` | “Always visible” should still respect an empty-catalog case and avoid rendering empty filter chrome when no canonical filters exist. | Current surfaces already suppress the button when the catalog is empty; the user asked to remove the toggle, not to invent an empty placeholder bar. | The TODO would need to specify empty-state chrome behavior more explicitly. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `flutter-app/lib/presentation/shared/discovery_filters/public_discovery_filter_controller_mixin.dart`
- `flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`
- `flutter-app/lib/presentation/tenant_public/discovery/discovery_screen.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_app_bar.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_section_view.dart`
- `flutter-app/test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`

### Ordered Steps
1. Update fail-first tests that currently freeze the button-driven reveal/collapse behavior.
2. Simplify the shared Home/Discovery filter-visibility path so default mounting no longer depends on panel-open state or scroll-driven close logic.
3. Remove the Discovery header filter button and render the canonical filter bar inline by default in the default listing chrome.
4. Remove the Home agenda header filter button and render the canonical filter bar inline by default whenever the Home filter catalog is non-empty.
5. Add focused regressions for empty-catalog, selected-filter, and Discovery search-mode behavior.
6. Run focused Flutter validation suites, build the final bundle, and collect final runtime evidence.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the current behavior is already protected by explicit Flutter tests, so the safest way to supersede it is to rewrite those expectations first and then implement the new surface contract.
- **Fail-first target(s) (when required):**
  - `flutter-app/test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
  - `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Home agenda filter chrome | `visible UI` | `shared-android-web` | `ADB integration or Playwright readonly` | `no` | `yes` | Focused Home agenda widget tests + final runtime proof | n/a |
| Discovery filter chrome | `visible UI` | `shared-android-web` | `ADB integration or Playwright readonly` | `no` | `yes` | Focused Discovery widget tests + final runtime proof | n/a |
| Shared filter visibility path | `field/DTO/domain refactor` | `n/a` | `n/a` | `no` | `no` | Shared controller/mixin tests | Structure-only logic path; final UI proof is covered by Home/Discovery runtime rows. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / focused Home+Discovery filter suites` | Home and Discovery Flutter behavior changes are the core slice. | `cd flutter-app && fvm flutter test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart` | `Local-Implemented` | `planned` | `<pending>` | Expand only if implementation touches extra suites. |
| `flutter-app / analyzer` | Shared filter visibility logic and UI composition are Flutter source changes. | `cd flutter-app && fvm dart analyze --format machine` | `Local-Implemented` | `planned` | `<pending>` | Required before any promotable claim. |
| `flutter-app / web build` | Final browser runtime proof requires a fresh published bundle. | `bash scripts/build_web.sh ../web-app dev` | `promotion` | `planned` | `<pending>` | Required before Playwright/browser evidence. |
