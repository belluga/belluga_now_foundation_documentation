# TODO (Store Release): Home Favorites Refresh Regression

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User QA on 2026-04-29 found that favoriting from the app does not refresh the Favorites section on Home. The likely failure mode is a consumer/source-of-truth gap: the favorite mutation path updates the direct favorite surface, but Home is not observing the canonical favorite repository stream/invalidation boundary correctly.

This TODO is a release blocker because Home is the tenant-public entry route and the favorites strip is now part of the social loop confidence surface. The fix must preserve the promoted Home ownership rules: shared mutable state belongs to repositories, not sibling controllers, widget controllers, or local one-off patches.

Favorites are a first-production capability in this release. There is no backward-compatibility requirement for pre-release favorite cache, stream, payload, or persistence shapes when they conflict with the launch contract.
Audit, Claude, PR, and promotion reviews for this TODO must not request favorite backward compatibility. Such findings are non-blocking unless they identify an independent launch risk unrelated to preserving pre-release favorite behavior.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-home-favorites-refresh`
- **Why this is the right current slice:** this is one bounded regression: after a user favorites or unfavorites a target in app, Home Favorites must reflect the canonical favorite state without requiring app restart or manual full re-entry.
- **Direct-to-TODO rationale:** safe. The issue is a concrete QA finding against already documented Home/Favorites behavior and does not require broader product discovery.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented-Audited-ADB-Contract-Smoke-Passed-Route-Visual-Manual`
- **Qualifiers:** `Regression`, `Store-Release-Blocker`, `Flutter`, `Stream-Ownership`, `User-Flow-Impact`
- **Next exact step:** if route-level Android visual proof is required, manually favorite/unfavorite from the app and verify the Home Favorites strip updates without restart; the available source-owned ADB test now covers real backend favorite/unfavorite persistence and readback on Android.

## Contract Boundary
- This TODO owns Home Favorites refresh after app-side favorite/unfavorite mutations.
- It does not own the broader favorites graph, contact/friend semantics, or account-profile favorite backend contract already covered by the social-loop lane.
- It must not solve the bug by controller-to-controller relay, local duplicate caches, manual widget pokes, or screen-specific forced reloads that bypass repository ownership.
- If investigation proves the backend favorite response/stream contract is missing required data, this TODO may absorb the minimum backend/API correction required for Home refresh, but must document that handoff before implementation.

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Decision promotion targets:**
  - `tenant_home_composer_module.md` Home Favorites consumer refresh behavior and repository ownership notes.
  - `flutter_client_experience_module.md` Favorites Strip Preview Contract if the runtime contract needs clarification.
- **Module decision consolidation targets:**
  - `tenant_home_composer_module.md` section `7 Canonical Decision Baseline`
  - `flutter_client_experience_module.md` section `2.1 Domain Rules`

## Scope
- [ ] Reproduce the Home Favorites refresh failure with fail-first Flutter coverage.
- [ ] Identify the authoritative favorite mutation path and Home favorites consumer path.
- [ ] Ensure favorite/unfavorite mutations publish or invalidate the repository-owned state that Home Favorites consumes.
- [ ] Ensure Home Favorites refreshes without app restart, manual route reset, or local screen-only workaround.
- [ ] Preserve existing account-profile favorite navigation and visual preview contract (`avatar > cover > type visuals`, valid slug navigation).
- [ ] Preserve repository-owned stream boundaries promoted by `HOM-07`, `HOM-08`, `FCX-08`, and `FCX-09`.
- [ ] Add regression evidence that covers both favorite and unfavorite transitions.

## Out of Scope
- [ ] Redesigning the Home Favorites visual layout.
- [ ] Changing the favorite business model, reciprocal friend derivation, or contact-match semantics.
- [ ] Replacing the Home client-composed MVP model with a backend Home composer endpoint.
- [ ] Broad account-profile catalog refactors outside the data required by the Home Favorites strip.
- [ ] ADB contact-permission validation; this TODO should use non-ADB coverage first and leave final device proof to the consolidated ADB phase.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Home Favorites must consume canonical repository-owned favorite state or repository-owned invalidation; it must not rely on a sibling controller or widget-local cache as source-of-truth.
- [x] `D-02` A successful app-side favorite mutation must update Home Favorites in the same running app session without requiring restart.
- [x] `D-03` A successful app-side unfavorite mutation must remove or update the item in Home Favorites in the same running app session without requiring restart.
- [x] `D-04` The fix must preserve Home MVP client composition; no aggregated Home endpoint is introduced in this TODO.
- [x] `D-05` If the current repository contract cannot express the refresh, the contract should be corrected at the repository boundary rather than patched inside Home UI.
- [x] `D-06` Favorites have zero backward-compatibility burden for this release because this is the first production launch of the favorite/social loop behavior.
- [x] `D-07` Review and promotion gates must classify favorite backward-compatibility requests as out of scope and non-blocking unless they raise an independent security, integrity, data-loss, tenant-isolation, or release-regression issue.

## Module Decision Consistency Matrix
| Decision | Module Decision Ref | Status | Planned Handling | Evidence |
| --- | --- | --- | --- | --- |
| `D-01` | `tenant_home_composer_module.md` `HOM-07` | `Aligned` | Preserve single-writer repository ownership. | Home agenda stream ownership rule is the closest promoted pattern for Home shared state. |
| `D-01` | `tenant_home_composer_module.md` `HOM-08` | `Aligned` | Preserve repository-internal pagination/query ownership and avoid controller-visible cache leaks. | The bug likely indicates similar ownership leakage risk. |
| `D-02..D-03` | `flutter_client_experience_module.md` Favorites Strip Preview Contract | `Aligned` | Preserve snapshot-backed preview while making refresh deterministic. | Module requires Home Favorites preview to reflect valid favorite snapshots and slugs. |
| `D-04` | `tenant_home_composer_module.md` `HOM-01` | `Aligned` | Preserve no aggregated Home endpoint in MVP. | Current Home remains client-composed from independent sources. |

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The bug is in Flutter repository/stream/invalidation wiring rather than the user action failing to persist. | User observed favorite action works but Home does not update; module has prior Home stream ownership hardening. | Backend mutation or DTO may need correction inside this TODO. | `Medium` | `Keep as Assumption` |
| `A-02` | Home Favorites is a user-flow-impacting release surface and needs runtime-like coverage, not analyzer-only evidence. | Home is tenant-public `/`; favorites strip is documented in `flutter_client_experience_module.md`. | Delivery could repeat the same backend-without-consumer gap pattern. | `High` | `Keep as Assumption` |
| `A-03` | Non-ADB Flutter widget/controller/repository tests can reproduce and guard the refresh before final device proof. | The failure concerns app state propagation; ADB is not needed for the first regression loop. | ADB/device proof may become the only reliable end-to-end signal and must be deferred to final phase. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

**Orchestration wave:** `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`

### Approval & Rules Acknowledgement

- **Approval evidence:** user replied `APROVADO` on 2026-04-29 for Wave 2 execution after the zero-backward-compatibility rule was incorporated.
- **Scope ownership:** `tenant_public` Home `/`, governed by `foundation_documentation/policies/scope_subscope_governance.md`.
- **Rules ingested before implementation:** `flutter-architecture-adherence`, `rule-flutter-flutter-screen-workflow-glob`, `rule-flutter-flutter-controller-workflow-glob`, `rule-flutter-flutter-repository-workflow-glob`, `test-creation-standard`, `bug-fix-evidence-loop`, `frontend-race-condition-validation`, and `test-orchestration-suite`.
- **Execution impact:** shared favorite state must remain repository-owned; Home may consume repository streams only; no controller-to-controller relay, widget-local cache, or UI-owned `StreamValue` may be introduced.
- **Profile scope check:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder` returned `review required` because of pre-existing Delphi/skill workflow changes outside this TODO. This TODO will not touch those Delphi surfaces; planned source changes remain Flutter/lib/test and this governing TODO evidence.
- **Device policy:** ADB/device evidence remains deferred to the final consolidated Wave 2D phase.

### Touched Surfaces
- Flutter favorite repository/DAO/controller surfaces as discovered by fail-first coverage.
- Flutter Home Favorites controller/widget/repository consumer surfaces.
- Focused Flutter tests for repository stream/invalidation and Home Favorites rendering.
- Module documentation only if the implementation clarifies a durable Home/Favorites contract.

### Ordered Steps
1. Inspect current favorite mutation flow and Home Favorites consumer flow.
2. Add fail-first coverage for favorite -> Home Favorites refresh and unfavorite -> Home Favorites removal/update.
3. Fix the repository-owned stream/invalidation boundary.
4. Verify no controller-to-controller relay or widget-local source-of-truth was introduced.
5. Run focused Flutter tests, analyzer, and web build if web bundle can be affected.
6. Run independent review/triple audit for this TODO before consolidation with the broader store-release lane.
7. Defer ADB/device smoke to the final consolidated device phase unless the non-ADB tests cannot exercise the bug.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Home Favorites controller/widget test showing stale state after favorite mutation.
  - Repository test proving favorite mutation emits/invalidates the stream Home consumes.
- **Runtime evidence target:** final ADB smoke should favorite/unfavorite in app and observe Home without restart.

## Test Matrix Derivation Loop

This TODO must derive and refresh the test matrix for each implementation task before that task can be considered delivered. The loop is:

1. Start from one task/acceptance criterion.
2. Define the user-visible or contract-visible failure it can cause.
3. Add the lowest-level fail-first test that proves the failure.
4. Add the consumer/flow test that proves the fixed behavior reaches Home.
5. Mark unsupported runtime lanes as `blocked`, not passed.
6. Update the Completion Evidence Matrix row before moving to the next task.

### Test Coverage Matrix
| Task / Behavior | Fail-First Target | Required Automated Evidence | Runtime / Manual Evidence | Status |
| --- | --- | --- | --- | --- |
| Favorite mutation refreshes Home Favorites | Test starts with Home Favorites stale after favorite mutation. | Repository stream/invalidation test + Home Favorites controller/widget test. | ADB contract smoke: real backend favorite appears in `GET /favorites`; route-level Home strip visual remains manual. | `local-passed / ADB-contract-passed / route-visual-manual` |
| Unfavorite mutation refreshes Home Favorites | Test starts with removed favorite still visible. | Repository stream/invalidation test + Home Favorites widget removal/update assertion. | ADB contract smoke: real backend unfavorite disappears from `GET /favorites`; route-level Home strip visual remains manual. | `local-passed / ADB-contract-passed / route-visual-manual` |
| Architecture boundary is preserved | Test/review detects controller relay or local screen cache source-of-truth. | Architecture scan + focused tests proving repository-owned state drives render. | n/a | `audit-passed` |
| Existing preview/navigation remains stable | Test catches missing slug/media/type visual regression. | Widget/repository assertions for favorite snapshot preview and route target. | Optional manual smoke if UI changed. | `local-passed` |

## Audit Trigger Matrix
| Lane | Trigger | Minimum Decision |
| --- | --- | --- |
| Architecture | Repository stream ownership and Home shared state boundary. | `required` |
| Code Quality | Regression fix may cross repository/controller/widget boundaries. | `required` |
| Test Quality | User-facing stale-state regression requires fail-first proof. | `required` |
| Performance | Favorite refresh should not force full repeated Home scans on every mutation. | `recommended` |
| Security | No new auth/permission surface expected. | `not-required` |

## Acceptance Criteria
- [ ] Favoriting a valid account-profile target updates Home Favorites in the same running app session.
- [ ] Unfavoriting the same target updates/removes it from Home Favorites in the same running app session.
- [ ] Home Favorites consumes repository-owned state/invalidation and does not depend on sibling controller relays or widget-local caches.
- [ ] Existing Home Favorites preview and navigation contracts remain stable.
- [ ] The regression is covered by fail-first automated Flutter tests.

## Definition of Done
- [ ] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [ ] Focused Flutter tests pass.
- [ ] `fvm dart analyze --format machine` passes or any unrelated pre-existing diagnostics are explicitly isolated.
- [ ] Web build is run if touched surfaces affect compiled web output.
- [ ] Independent review/triple audit is recorded before promotion claim.
- [ ] ADB/device smoke is queued for final consolidated device validation.

## Validation Steps
- [ ] Flutter automated: favorite mutation updates the repository state consumed by Home Favorites.
- [ ] Flutter automated: unfavorite mutation updates/removes Home Favorites state.
- [ ] Flutter automated: Home Favorites widget/controller re-renders from repository-owned state without route restart.
- [ ] Architecture scan: no controller-to-controller relay or screen-local duplicate source-of-truth introduced.
- [ ] Manual/device final: favorite from app, return to Home, verify Favorites strip updates; unfavorite and verify removal/update.

## Local Delivery Notes (2026-04-29)

- **Implemented Flutter fix:** `AccountProfilesRepository.toggleFavorite` now refreshes the registered `FavoriteRepositoryContract` after successful favorite/unfavorite persistence, updating the canonical `favoriteResumesStreamValue` consumed by Home Favorites.
- **Claude gate resolution:** Claude CLI found that Home refresh failure was inside the persistence rollback `try/catch`. The implementation now rolls back only backend persistence failure; Home refresh and telemetry failures are isolated and do not undo a persisted favorite mutation.
- **Architecture boundary:** the fix stays at the repository boundary. No Home widget cache, controller-to-controller relay, route restart, or screen-local duplicate source-of-truth was introduced.
- **Fail-first evidence:** the new repository regression test first failed with `Expected: <1> Actual: <0>` for the canonical favorite-resume refresh count, then passed after implementation.
- **Audit-driven test hardening:** Round 01 test-quality review found the original fake too loose because it did not prove post-persistence read-model behavior. The regression test now reads favorite resumes from the same fake favorite backend mutated by favorite/unfavorite persistence, asserts operation order, and covers failed persistence with no Home favorite refresh.
- **Claude-driven test hardening:** Added coverage proving a Home favorite-resume refresh failure after successful persistence does not roll back the local favorite state.
- **ADB policy:** device proof remains deferred to the consolidated Wave 2D ADB phase because this slice is reproducible through repository/controller/widget evidence and ADB is intentionally reserved for the end of the orchestration.
- **ADB contract smoke (2026-04-29):** attached device `192.168.15.9:5555` passed `integration_test/feature_favorites_query_contract_e2e_test.dart` via `drive-fallback` in 470s. The test proves anonymous favorite/unfavorite persistence and real backend `GET /favorites` readback on Android. There is no source-owned route-level ADB test for visually observing the Home Favorites strip after navigating back to Home, so that row remains manual if promotion requires visual device proof.

## Completion Evidence Matrix (Local, Non-ADB)

| Criterion | Evidence | Status |
| --- | --- | --- |
| App-side favorite mutation refreshes the canonical Home Favorites source after persistence | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart --plain-name "toggleFavorite"`; operation order asserts `favorite:<id>` before `fetchFavorites`. | Passed 2026-04-29 |
| App-side unfavorite mutation refreshes/removes the canonical Home Favorites source after persistence | Same test covers the second toggle; operation order asserts `unfavorite:<id>` before the second `fetchFavorites`. | Passed 2026-04-29 |
| Failed favorite persistence does not refresh Home favorite resumes | `toggleFavorite does not refresh Home favorite resumes when persistence fails` proves rollback and `fetchFavoriteResumesCallCount == 0`. | Passed 2026-04-29 |
| Failed Home favorite-resume refresh does not roll back persisted favorite state | `toggleFavorite keeps persisted state when Home favorite resume refresh fails` proves backend favorite persistence remains reflected in local favorite state when refresh throws. | Passed 2026-04-29 |
| Home Favorites consumer remains repository-owned | Existing Home Favorites tests included in the focused suite: `favorites_section_controller_origin_flow_test.dart` and `favorites_section_builder_test.dart`; code diff only wires repository invalidation from the mutation source. | Passed 2026-04-29 |
| Focused Wave 2A Flutter suite | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | Passed 2026-04-29: 28 tests |
| Flutter architecture/analyzer gate | `fvm dart analyze --format machine` | Passed 2026-04-29, no diagnostics after Claude rollback-boundary fix |
| Flutter web build gate | `bash scripts/build_web.sh ../web-app dev` | Passed 2026-04-29 after Claude rollback-boundary fix; `web-app` output is derived and not committed |
| Source-owned Playwright/browser test lane | Repository scan found no source-owned Playwright runner under `flutter-app` (`tools/` absent; no `web_app_tests`/navigation smoke script). Browser validation is therefore not claimed by this TODO; web build evidence is recorded and final runtime smoke remains ADB/manual. | Not applicable / unavailable |
| Independent triple audit | `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/`; Round 01 `TQA-01` resolved with stronger tests; Round 02 returned zero findings; Claude `BLOCK-1` then triggered the rollback-boundary fix; Round 03 returned zero findings across elegance, performance, and test-quality lanes; non-material recommended-path conflicts adjudicated resolved. | Passed / resolved 2026-04-29 |
| Claude CLI auxiliary review | Initial Claude review found `BLOCK-1` on refresh-failure rollback; `W2A-home-favorites-refresh-claude-resolution-20260429.md` records the fix; final Claude re-review approved with no unresolved blocking risks. | Passed / resolved 2026-04-29 |
| Final device/runtime proof | `feature_favorites_query_contract_e2e_test.dart` passed on attached Android device and real backend, covering favorite/unfavorite persistence plus `GET /favorites` readback. Route-level Home Favorites strip visual proof remains manual because no source-owned ADB test drives that exact UI route. | ADB contract smoke passed / route visual manual |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the product symptom is narrow, but the correct fix is architecture-sensitive because it touches shared repository streams and Home state propagation.
