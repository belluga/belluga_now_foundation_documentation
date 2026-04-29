# TODO (Store Release): Home Favorites Refresh Regression

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User QA on 2026-04-29 found that favoriting from the app does not refresh the Favorites section on Home. The likely failure mode is a consumer/source-of-truth gap: the favorite mutation path updates the direct favorite surface, but Home is not observing the canonical favorite repository stream/invalidation boundary correctly.

This TODO is a release blocker because Home is the tenant-public entry route and the favorites strip is now part of the social loop confidence surface. The fix must preserve the promoted Home ownership rules: shared mutable state belongs to repositories, not sibling controllers, widget controllers, or local one-off patches.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-home-favorites-refresh`
- **Why this is the right current slice:** this is one bounded regression: after a user favorites or unfavorites a target in app, Home Favorites must reflect the canonical favorite state without requiring app restart or manual full re-entry.
- **Direct-to-TODO rationale:** safe. The issue is a concrete QA finding against already documented Home/Favorites behavior and does not require broader product discovery.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Regression`, `Store-Release-Blocker`, `Flutter`, `Stream-Ownership`, `User-Flow-Impact`
- **Next exact step:** write fail-first Flutter coverage proving that a favorite mutation updates the Home Favorites section through repository-owned state/invalidation, then implement the smallest architecture-compliant fix.

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
| Favorite mutation refreshes Home Favorites | Test starts with Home Favorites stale after favorite mutation. | Repository stream/invalidation test + Home Favorites controller/widget test. | Final ADB: favorite in app, return to Home, item appears/updates without restart. | `planned` |
| Unfavorite mutation refreshes Home Favorites | Test starts with Home Favorites still showing removed favorite. | Repository stream/invalidation test + Home Favorites widget removal/update assertion. | Final ADB: unfavorite in app, return to Home, item disappears/updates without restart. | `planned` |
| Architecture boundary is preserved | Test/review detects controller relay or local screen cache source-of-truth. | Architecture scan + focused tests proving repository-owned state drives render. | n/a | `planned` |
| Existing preview/navigation remains stable | Test catches missing slug/media/type visual regression. | Widget/repository assertions for favorite snapshot preview and route target. | Optional manual smoke if UI changed. | `planned` |

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

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the product symptom is narrow, but the correct fix is architecture-sensitive because it touches shared repository streams and Home state propagation.
