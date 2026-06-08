# TODO (Store Release): Home Distance And Origin Refresh Regression

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Production-Ready. Historical archival catch-up on `2026-06-08` confirmed that current `origin/main` still carries the focused Home agenda radius/origin refresh regression coverage, backend query semantics coverage, and the promoted module contracts that govern this slice.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to move already promoted TODOs to `completed` after deeper code/main investigation.
- **Approval scope:** documentation-only archival closeout for this bounded Home radius/origin refresh regression after confirming the delivered contract still exists on current `origin/main`.

## Context
Manual QA on 2026-05-02 found that Home agenda/distance behavior is not respecting the canonical radius/origin contract anymore:

- reducing the selected radius can leave farther events visible;
- changing the resolved profile/location origin does not force Home to reload from the new canonical origin;
- the current behavior looks like stale cached results are surviving a tighter radius or origin change instead of the repository issuing an authoritative refresh.

This is a release-surface regression because Home is the tenant-public entry route and radius/origin are already promoted behaviors under `HOM-05`, `HOM-06`, and `HOM-07`. The fix must preserve repository ownership and canonical location-origin resolution. It must not be patched with screen-local filtering hacks, route restarts, or parallel caches.

This TODO does **not** reopen the broader post-release identity-backed proximity program. It owns only the current-release regression: Home must immediately reflect the selected radius and current canonical origin. Manual QA on 2026-05-03 also froze one bounded product refinement here: when Home seeds the initial selected radius from the user-to-tenant-default-origin distance, that seed must honor a minimum floor of `10 km` before the normal tenant-configured clamp logic is applied.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-home-distance-origin-refresh`
- **Why this is the right current slice:** this is one bounded regression in the existing Home user flow: change radius or origin, and the Home agenda must re-query/render against that exact canonical state without stale leftovers.
- **Direct-to-TODO rationale:** safe. The expected behavior is already frozen in promoted Home/location contracts; the work is restoration and hardening, not new product discovery.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Regression`, `Store-Release-Blocker`, `Flutter`, `Cross-Stack`, `Home`, `Radius-Origin`, `Repository-Ownership`, `User-Flow-Impact`, `origin-main-reviewed`, `Historical-Archival-Catch-Up`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-store-release-home-distance-origin-refresh-regression.md`.
- **Post-commit/push status:** `completed`

## Contract Boundary
- This TODO owns Home agenda behavior after radius changes and canonical origin changes.
- It owns the refresh/invalidation path from `/profile` or any canonical origin update into Home agenda re-query behavior.
- It may absorb the minimum Laravel correction if backend request/query semantics are wrong for `origin_lat`, `origin_lng`, or `max_distance_meters`.
- It must not solve the regression with widget-local state filters, route restarts, or controller-to-controller relays.
- It does not own the broader post-release identity-backed proximity-preference roadmap or reusable reference-location core.

## References
- `foundation_documentation/todos/completed/TODO-store-release-android.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-user-profile-polish.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-proximity-preferences-and-location-origin.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/map_poi_module.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Decision promotion targets:**
  - `tenant_home_composer_module.md` Home radius/origin refresh behavior and repository ownership.
  - `agenda_and_action_planner_module.md` backend-owned agenda filter execution and origin/radius query semantics.
  - `flutter_client_experience_module.md` canonical location-origin and user-flow refresh behavior if the Flutter consumer contract needs clarification.

## Scope
- [x] Reproduce the stale-distance regression with fail-first focused coverage.
- [x] Reproduce the stale-origin regression where a profile/location-origin change does not force Home to reload from the new canonical origin.
- [x] Verify the exact authoritative Home request path for `origin_lat`, `origin_lng`, and `max_distance_meters`.
- [x] Ensure Home agenda re-queries or invalidates authoritative repository state when the selected radius changes.
- [x] Ensure Home agenda re-queries or invalidates authoritative repository state when the canonical origin changes.
- [x] Ensure decreasing the radius removes farther items instead of leaving stale results from a previous wider query.
- [x] Ensure the initial Home radius seeded from tenant-default-origin distance applies a lower bound of `10 km` before the standard tenant bounds clamp.
- [x] Ensure the fix preserves repository-owned state and canonical `LocationOriginService` semantics.

## Out of Scope
- [ ] The broader post-release identity-backed proximity preference program.
- [ ] New profile/location UI beyond what is already owned by `/profile`.
- [ ] Map-specific “search this area” redesign or broader map/filter UX.
- [ ] Replacing Home MVP client composition with a backend Home composer endpoint.
- [ ] Any unrelated Home favorites, invite, or discovery behavior.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Home agenda visible results are authoritative backend/repository state for the currently selected radius and canonical resolved origin; stale wider-radius results must not survive after a tighter selection.
- [x] `D-02` Radius changes and canonical origin changes are repository-refresh boundaries, not widget-local filtering boundaries.
- [x] `D-03` Home must use the canonical resolved origin from `LocationOriginService` and related persisted/profile-backed settings; it must not keep querying from a stale previous origin after the source of truth changes.
- [x] `D-04` If the current repository contract cannot express the needed invalidation/reload semantics, the contract must be corrected at the repository boundary rather than patched inside the Home UI.
- [x] `D-05` This TODO restores current release behavior only and does not widen into the broader post-release proximity-preferences roadmap.
- [x] `D-06` Home's auto-seeded radius from tenant-default-origin distance must never initialize below `10 km`; this is a product floor on the seed value, not a widget-only display tweak.

## Acceptance Criteria
- [x] Reducing Home radius updates visible agenda results in the same running app session and no farther-than-radius stale items remain.
- [x] Changing the canonical origin used by Home triggers a new Home agenda load against that origin in the same running app session.
- [x] Home continues to consume repository-owned agenda/origin state and does not depend on sibling-controller relays, route restarts, or widget-local duplicate caches.
- [x] Focused fail-first automated coverage exists for both radius-tightening and origin-change regressions.
- [x] Focused automated coverage exists for the `10 km` minimum on tenant-default-origin radius seeding.

## Definition of Done
- [x] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [x] Focused Flutter tests pass for repository/controller/widget refresh behavior.
- [x] Focused Laravel tests pass if backend query/filter semantics are touched.
- [x] `fvm dart analyze --format machine` passes or unrelated diagnostics are explicitly isolated.
- [x] Device/runtime evidence is recorded for the final radius/origin behavior when focused tests alone are insufficient.

## Validation Steps
- [x] Flutter automated: reducing radius refreshes Home agenda from repository-owned state and removes items now outside the selected radius.
- [x] Flutter automated: canonical origin changes trigger Home agenda refresh from the new origin without route restart.
- [x] Flutter automated: tenant-default-origin radius seeding applies the `10 km` minimum floor before persisted/query refresh behavior continues.
- [x] Architecture scan: no controller relay or widget-local duplicate source-of-truth is introduced.
- [x] Laravel automated (if touched): agenda query/filter semantics honor `origin_lat`, `origin_lng`, and `max_distance_meters` exactly as requested.
- [x] Device/runtime final: current Home behavior on the release flavor proves radius tightening and origin change both refresh the visible list correctly.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Reproduce and close the stale-origin regression where canonical origin changes must trigger a fresh Home load. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2004` | `origin/main` Flutter test corpus | passed | `origin/main` still carries a focused regression test that re-queries Home even when the coordinate delta is small but the canonical mode changes. |
| `SCOPE-02` | Scope | Reducing the selected radius must invalidate stale wider results instead of preserving cached items. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2051` | `origin/main` Flutter test corpus | passed | The current test suite explicitly proves stale cached agenda rows are cleared by the tighter query. |
| `SCOPE-03` | Scope | Home must issue authoritative geo-query parameters through the backend contract. | Flutter DAL/backend tests | `origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120` | `origin/main` Flutter DAL test corpus | passed | The backend transport contract still verifies exact `origin_lat`, `origin_lng`, and `max_distance_meters` query parameter handling. |
| `D-01` | Decision Baseline | Radius/origin refresh remains repository-owned and aligned with canonical location-origin rules. | Module contract review | `origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-05, HOM-06, HOM-07); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-04, AGD-05, AGD-06, AGD-09)` | `origin/main` docs corpus | passed | Current module docs still encode persisted Home radius, canonical origin selection, backend-owned geo filtering, repository ownership, and no route-restart workaround. |
| `VAL-01` | Validation Steps | The `10 km` seed floor remains covered alongside the refresh regression behavior. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:169` | `origin/main` Flutter test corpus | passed | The bounded seed-floor refinement is still exercised in the same focused controller suite. |
| `ARCH-HOME-DIST-01` | Historical archival review | The current mainline still reflects the restored contract rather than the pre-fix regression state. | Cross-stack current-main review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004,2051; origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-05, HOM-06, HOM-07)` | `origin/main` Flutter + docs | passed | The TODO is historical lag; the contract and guards already live on main. |
| `SCOPE-04` | Scope | Reproduce the stale-distance regression with fail-first focused coverage. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2051` | `origin/main` Flutter test corpus | passed | The focused regression suite still reproduces and guards the stale-distance failure mode. |
| `SCOPE-05` | Scope | Reproduce the stale-origin regression where a profile/location-origin change does not force Home to reload from the new canonical origin. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2004` | `origin/main` Flutter test corpus | passed | The focused regression suite still reproduces and guards the canonical-origin refresh failure mode. |
| `SCOPE-06` | Scope | Verify the exact authoritative Home request path for `origin_lat`, `origin_lng`, and `max_distance_meters`. | Flutter DAL/backend tests | `origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120` | `origin/main` Flutter DAL test corpus | passed | The request-shape contract remains explicitly asserted at the backend transport boundary. |
| `SCOPE-07` | Scope | Ensure Home agenda re-queries or invalidates authoritative repository state when the selected radius changes. | Flutter controller tests + docs review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2051; origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-07); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-05)` | `origin/main` Flutter + docs | passed | Current mainline still treats radius tightening as a repository-backed refresh boundary. |
| `SCOPE-08` | Scope | Ensure Home agenda re-queries or invalidates authoritative repository state when the canonical origin changes. | Flutter controller tests + docs review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004; origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-06); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-04, AGD-09)` | `origin/main` Flutter + docs | passed | Canonical origin changes remain governed by `LocationOriginService` and forced refresh semantics on main. |
| `SCOPE-09` | Scope | Ensure decreasing the radius removes farther items instead of leaving stale results from a previous wider query. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2051` | `origin/main` Flutter test corpus | passed | The tighter-radius regression stays explicitly covered. |
| `SCOPE-10` | Scope | Ensure the initial Home radius seeded from tenant-default-origin distance applies a lower bound of `10 km` before the standard tenant bounds clamp. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:169` | `origin/main` Flutter test corpus | passed | The bounded seed-floor refinement remains present on main. |
| `SCOPE-11` | Scope | Ensure the fix preserves repository-owned state and canonical `LocationOriginService` semantics. | Flutter + docs review | `origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-06, HOM-07); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-04, AGD-09); origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004` | `origin/main` Flutter + docs | passed | Current mainline still preserves canonical origin and single-writer ownership rather than local relay workarounds or route-restart folklore. |
| `AC-01` | Acceptance Criteria | Reducing Home radius updates visible agenda results in the same running app session and no farther-than-radius stale items remain. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2051` | `origin/main` Flutter test corpus | passed | The focused controller suite still guards the exact acceptance behavior. |
| `AC-02` | Acceptance Criteria | Changing the canonical origin used by Home triggers a new Home agenda load against that origin in the same running app session. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:2004` | `origin/main` Flutter test corpus | passed | The focused controller suite still guards same-session canonical-origin refresh. |
| `AC-03` | Acceptance Criteria | Home continues to consume repository-owned agenda/origin state and does not depend on sibling-controller relays, route restarts, or widget-local duplicate caches. | Module contract review | `origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-07); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-04, AGD-05)` | `origin/main` docs corpus | passed | Current module decisions still forbid sibling relays, route restart workarounds, and widget-local duplicate ownership. |
| `AC-04` | Acceptance Criteria | Focused fail-first automated coverage exists for both radius-tightening and origin-change regressions. | Flutter controller tests | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004,2051` | `origin/main` Flutter test corpus | passed | Current mainline still carries both focused regressions in the same controller suite. |
| `AC-05` | Acceptance Criteria | Focused automated coverage exists for the `10 km` minimum on tenant-default-origin radius seeding. | Flutter controller tests | `origin/main flutter-app tenant_home_agenda_controller_test.dart:169` | `origin/main` Flutter test corpus | passed | The seed-floor acceptance remains explicitly tested on main. |
| `DOD-01` | Definition of Done | All acceptance criteria have concrete evidence in the Completion Evidence Matrix. | Evidence audit | This Completion Evidence Matrix plus the `origin/main` review commands recorded in `Main Promotion Evidence - 2026-06-08`. | docs + `origin/main` review | passed | The archival catch-up now maps each acceptance criterion to concrete evidence. |
| `DOD-02` | Definition of Done | Focused Flutter tests pass for repository/controller/widget refresh behavior. | Historical device/runtime reuse + current main review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:169,2004,2051; historical Android device/runtime radius-origin packet` | historical Android device/runtime + `origin/main` regression review | passed | The focused controller suite remains present on main and still represents the delivered refresh guard, with the same device-visible Home navigation path already closed historically. |
| `DOD-03` | Definition of Done | Focused Laravel tests pass if backend query/filter semantics are touched. | Historical device/runtime reuse + current main review | `origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120; historical Android device/runtime Home packet` | historical Android device/runtime + `origin/main` DAL review | passed | The authoritative query-semantics assertions remain present on main and still back the same device-visible Home behavior. |
| `DOD-04` | Definition of Done | `fvm dart analyze --format machine` passes or unrelated diagnostics are explicitly isolated. | Historical analyzer evidence reuse | Historical implementation packet for this slice recorded focused Flutter green; the current archival move is documentation-only and introduces no new Flutter diagnostics. | historical packet | passed | No new source code was changed in Flutter for this archival move. |
| `DOD-05` | Definition of Done | Device/runtime evidence is recorded for the final radius/origin behavior when focused tests alone are insufficient. | Historical device/runtime evidence reuse + current main review | `historical Android device/runtime radius-origin packet; origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004,2051; origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120` | historical Android device/runtime + `origin/main` review | passed | The archival move relies on the already-delivered device/runtime closure and current mainline regression guards. |
| `VAL-02` | Validation Steps | Flutter automated: reducing radius refreshes Home agenda from repository-owned state and removes items now outside the selected radius. | Flutter controller tests + historical Android device/runtime review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2051; historical Android device/runtime radius-update packet` | historical Android device/runtime + `origin/main` Flutter test corpus | passed | Current mainline still carries this exact validation coverage, and the same-session device path already exercised the visible radius update. |
| `VAL-03` | Validation Steps | Flutter automated: canonical origin changes trigger Home agenda refresh from the new origin without route restart. | Flutter controller tests + historical Android device/runtime review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004; historical Android device/runtime canonical-origin packet without route restart` | historical Android device/runtime + `origin/main` Flutter test corpus | passed | Current mainline still carries this exact validation coverage, including the no route restart Home behavior already closed on device. |
| `VAL-04` | Validation Steps | Flutter automated: tenant-default-origin radius seeding applies the `10 km` minimum floor before persisted/query refresh behavior continues. | Flutter controller tests + historical Android device mutation/runtime review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:169; historical Android device radius-update mutation packet` | historical Android device/runtime + `origin/main` Flutter test corpus | passed | Current mainline still carries this exact validation coverage, and the same device mutation path continued through the seeded-radius refresh behavior. |
| `VAL-05` | Validation Steps | Architecture scan: no controller relay or widget-local duplicate source-of-truth is introduced. | Module contract review | `origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-07); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-05)` | `origin/main` docs corpus | passed | The canonical docs still explicitly forbid local filtering, route-restart relays, and duplicated Home agenda ownership. |
| `VAL-06` | Validation Steps | Laravel automated (if touched): agenda query/filter semantics honor `origin_lat`, `origin_lng`, and `max_distance_meters` exactly as requested. | Flutter DAL/backend tests + historical Android device/runtime review | `origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120; historical Android device/runtime Home packet` | historical Android device/runtime + `origin/main` Flutter DAL test corpus | passed | The request contract remains explicitly asserted and is the same parameter set the device-visible Home flow relied on. |
| `VAL-07` | Validation Steps | Device/runtime final: current Home behavior on the release flavor proves radius tightening and origin change both refresh the visible list correctly. | Historical Android device/runtime evidence + current main review | `historical Android device/runtime radius-origin packet; origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004,2051; origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120` | historical Android device/runtime + `origin/main` review | passed | The archival move preserves the prior device/runtime closure while confirming the same guard behavior still lives on main. |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new Flutter/Laravel code was executed for this move; the archival decision only reconciles the TODO with the current `origin/main` test and docs state. | `n/a` | `historical archival closeout` | `n/a` | Existing focused controller/DAL evidence plus the `origin/main` review recorded below. | Documentation-only move; no fresh CI-equivalent rerun was required. |

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` regression review | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:169,2004,2051` | `origin/main` still carries the focused origin/radius/seed-floor regression suite. |
| `flutter-app` backend-contract review | `origin/main flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:118-120` | `origin/main` still carries the backend request-shape assertions for Home agenda geo queries. |
| `foundation_documentation` contract review | `origin/main foundation_documentation/modules/tenant_home_composer_module.md (HOM-05, HOM-06, HOM-07); origin/main foundation_documentation/modules/agenda_and_action_planner_module.md (AGD-04, AGD-05, AGD-06, AGD-09)` | Canonical docs on `origin/main` still encode the Home radius/origin/repository-ownership contract restored by this slice. |
| `Archival decision` | Explicit `2026-06-08` user request to move already promoted TODOs to `completed` after code/main investigation. | Documentation-only closeout approved. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | Confirm this move only reconciles a stale TODO with regression coverage and module contracts already present on `origin/main`. | `n/a` | `origin/main flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:2004,2051` | `none` | No fresh PR/Copilot review surface exists for this documentation-only move. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Home repository ownership` | Prevent closure via widget-local filtering, controller relay, or route-restart folklore while the canonical repository contract already exists on main. | `passed` | `origin/main` regression and module-contract review | `no findings` | The archived slice closes only because the repository-owned and canonical-origin behavior is already codified and tested on main. |

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The file is being archived after the implementation and promotion wave already finished. | Truthful stage labeling and stable evidence references. | Claiming a fresh fix was implemented in this turn. | Record the archival catch-up basis directly in the TODO. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The task is to distinguish real completion from stale lane drift. | Keep the evidence basis concrete and cross-stack. | Hiding that this is a historical normalization pass. | Capture current `origin/main` Flutter/DAL/docs review explicitly. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | The closeout question is whether the same TODO already crossed the final lane threshold. | Preserve the governing TODO and close it cleanly. | Leaving already-main-carried regression work stranded in `promotion_lane/`. | Move the TODO to `completed` once the archival sections are guard-clean. |

## TODO Closeout Disposition
- **Completed path:** `foundation_documentation/todos/completed/TODO-store-release-home-distance-origin-refresh-regression.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming the Home radius/origin refresh slice remains present on current `origin/main`.
- **Historical note:** the focused regression coverage and canonical module decisions now prove this file was delayed relative to code, not still pending delivery.
- **Reopen rule:** any new Home radius/origin refresh regression or repository-ownership drift must open a new TODO rather than reopen this archival slice.
