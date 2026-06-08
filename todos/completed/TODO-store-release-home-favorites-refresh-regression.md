# TODO (Store Release): Home Favorites Refresh Regression

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Production-Ready. Historical archival catch-up on `2026-06-08` confirmed that current `origin/main` still carries the repository-owned Home favorites refresh contract, focused Flutter regression coverage, backend favorites contract coverage, and the supporting module documentation for this slice.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to move already promoted TODOs to `completed` after deeper code/main investigation.
- **Approval scope:** documentation-only archival closeout for this bounded Home favorites refresh regression after confirming the delivered contract still exists on current `origin/main`.

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
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Regression`, `Store-Release-Blocker`, `Flutter`, `Stream-Ownership`, `User-Flow-Impact`, `origin-main-reviewed`, `Historical-Archival-Catch-Up`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-store-release-home-favorites-refresh-regression.md`.
- **Post-commit/push status:** `completed`

## Contract Boundary
- This TODO owns Home Favorites refresh after app-side favorite/unfavorite mutations.
- It does not own the broader favorites graph, contact/friend semantics, or account-profile favorite backend contract already covered by the social-loop lane.
- It must not solve the bug by controller-to-controller relay, local duplicate caches, manual widget pokes, or screen-specific forced reloads that bypass repository ownership.
- If investigation proves the backend favorite response/stream contract is missing required data, this TODO may absorb the minimum backend/API correction required for Home refresh, but must document that handoff before implementation.

## References
- `foundation_documentation/todos/completed/TODO-store-release-android.md`
- `foundation_documentation/todos/completed/TODO-store-release-minimal-friends-and-favorites-mvp.md`
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
- [x] Reproduce the Home Favorites refresh failure with fail-first Flutter coverage.
- [x] Identify the authoritative favorite mutation path and Home favorites consumer path.
- [x] Ensure favorite/unfavorite mutations publish or invalidate the repository-owned state that Home Favorites consumes.
- [x] Ensure Home Favorites refreshes without app restart, manual route reset, or local screen-only workaround.
- [x] Preserve existing account-profile favorite navigation and visual preview contract (`avatar > cover > type visuals`, valid slug navigation).
- [x] Preserve repository-owned stream boundaries promoted by `HOM-07`, `HOM-08`, `FCX-08`, and `FCX-09`.
- [x] Add regression evidence that covers both favorite and unfavorite transitions.

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
| Favorite mutation refreshes Home Favorites | Test starts with Home Favorites stale after favorite mutation. | Repository stream/invalidation test + Home Favorites controller/widget test. | ADB contract smoke: real backend favorite appears in `GET /favorites`; Home route refresh is covered by repository/controller/widget tests without restart. | `local-passed / ADB-contract-passed / guard-passed` |
| Unfavorite mutation refreshes Home Favorites | Test starts with removed favorite still visible. | Repository stream/invalidation test + Home Favorites widget removal/update assertion. | ADB contract smoke: real backend unfavorite disappears from `GET /favorites`; Home route refresh is covered by repository/controller/widget tests without restart. | `local-passed / ADB-contract-passed / guard-passed` |
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
- [x] Favoriting a valid account-profile target updates Home Favorites in the same running app session.
- [x] Unfavoriting the same target updates/removes it from Home Favorites in the same running app session.
- [x] Home Favorites consumes repository-owned state/invalidation and does not depend on sibling controller relays or widget-local caches.
- [x] Existing Home Favorites preview and navigation contracts remain stable.
- [x] The regression is covered by fail-first automated Flutter tests.

## Definition of Done
- [x] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [x] Focused Flutter tests pass.
- [x] `fvm dart analyze --format machine` passes or any unrelated pre-existing diagnostics are explicitly isolated.
- [x] Web build is run if touched surfaces affect compiled web output.
- [x] Independent review/triple audit is recorded before promotion claim.
- [x] ADB/device smoke evidence is recorded for available source-owned Android favorite contract validation.

## Validation Steps
- [x] Flutter automated: favorite mutation updates the repository state consumed by Home Favorites.
- [x] Flutter automated: unfavorite mutation updates/removes Home Favorites state.
- [x] Flutter automated: Home Favorites widget/controller re-renders from repository-owned state without route restart.
- [x] Architecture scan: no controller-to-controller relay or screen-local duplicate source-of-truth introduced.
- [x] Device/runtime final: Android favorite/unfavorite contract smoke passed; Home route update is covered by repository/controller/widget tests without route restart.

## Local Delivery Notes (2026-04-29)

- **Implemented Flutter fix:** `AccountProfilesRepository.toggleFavorite` now refreshes the registered `FavoriteRepositoryContract` after successful favorite/unfavorite persistence, updating the canonical `favoriteResumesStreamValue` consumed by Home Favorites.
- **Claude gate resolution:** Claude CLI found that Home refresh failure was inside the persistence rollback `try/catch`. The implementation now rolls back only backend persistence failure; Home refresh and telemetry failures are isolated and do not undo a persisted favorite mutation.
- **Architecture boundary:** the fix stays at the repository boundary. No Home widget cache, controller-to-controller relay, route restart, or screen-local duplicate source-of-truth was introduced.
- **Fail-first evidence:** the new repository regression test first failed with `Expected: <1> Actual: <0>` for the canonical favorite-resume refresh count, then passed after implementation.
- **Audit-driven test hardening:** Round 01 test-quality review found the original fake too loose because it did not prove post-persistence read-model behavior. The regression test now reads favorite resumes from the same fake favorite backend mutated by favorite/unfavorite persistence, asserts operation order, and covers failed persistence with no Home favorite refresh.
- **Claude-driven test hardening:** Added coverage proving a Home favorite-resume refresh failure after successful persistence does not roll back the local favorite state.
- **ADB policy:** device proof remains deferred to the consolidated Wave 2D ADB phase because this slice is reproducible through repository/controller/widget evidence and ADB is intentionally reserved for the end of the orchestration.
- **ADB contract smoke (2026-04-29):** attached device `192.168.15.9:5555` passed `integration_test/feature_favorites_query_contract_e2e_test.dart` via `drive-fallback` in 470s. The source-owned Android row proves favorite/unfavorite persistence and real backend `GET /favorites` readback; Home route refresh is closed by repository/controller/widget tests that prove no route restart is required.

## Post-Auth Hydration Follow-Up (2026-04-29)

- **Updated QA finding:** after repeated OTP login with the same phone, confirmations and favorites eventually appeared in backend-backed surfaces such as Discovery. The initial "new user every login" hypothesis is not treated as proven. The remaining release bug is Home/startup hydration: identity-owned streams were not deterministically refreshed when the app transitioned from anonymous to registered identity.
- **Implemented Flutter fix:** `PostAuthIdentityHydrationCoordinator` now binds to the global auth stream from `ApplicationContract` and refreshes identity-owned repository state when a registered identity appears: Home favorite resumes, account-profile favorite IDs, confirmed occurrence IDs, and pending invites.
- **Stale-state guard:** `AccountProfilesRepository.refreshFavoriteAccountProfileIds` now clears previous favorite IDs when the backend returns an empty favorite list for the current identity, avoiding ghost favorites when a device switches users or identity state.
- **Architecture boundary:** the fix remains repository-owned and application-orchestrated. Home widgets/controllers still consume repository streams; no sibling-controller relay, manual route restart, or widget-local cache was introduced.
- **Hydration race guard:** coordinator tests now cover all four registered-identity refresh consumers and the logout/anonymous reset while a hydration is still in flight, proving the same registered user is rehydrated after the reset instead of being skipped by the per-user loop guard.
- **Focused evidence:** `fvm flutter test test/application/auth/post_auth_identity_hydration_coordinator_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart test/application/application_contract_test.dart` passed with `20/20` tests after the race guard; `fvm dart analyze --format machine` passed cleanly; `bash scripts/build_web.sh ../web-app dev` passed and refreshed the derived web bundle.
- **Focused rerun evidence (2026-04-30):** `fvm flutter test test/application/auth/post_auth_identity_hydration_coordinator_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart` passed with `15/15`, confirming post-auth hydration and stale favorite clearing remain automated coverage after manual OTP/login validation.

## Completion Evidence Matrix (Local, Non-ADB)

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Reproduce the Home Favorites refresh failure with fail-first Flutter coverage. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart --plain-name "toggleFavorite refreshes canonical favorite resumes"` | local Flutter test | passed | Fail-first covered stale Home favorite-resume refresh after mutation. |
| SCOPE-02 | Scope | Identify the authoritative favorite mutation path and Home favorites consumer path. | source audit | `lib/infrastructure/repositories/account_profiles_repository.dart`; `favorites_section_controller_origin_flow_test.dart` | Flutter repository/controller | passed | Mutation source refreshes the favorite repository consumed by Home. |
| SCOPE-03 | Scope | Ensure favorite/unfavorite mutations publish or invalidate the repository-owned state that Home Favorites consumes. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart` | local Flutter test | passed | Favorite and unfavorite both refresh canonical favorite resumes. |
| SCOPE-04 | Scope | Ensure Home Favorites refreshes without app restart, manual route reset, or local screen-only workaround. | automated | `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`; `integration_test/feature_favorites_query_contract_e2e_test.dart` | local Flutter widget/controller + Android device | passed | Home consumes repository-owned stream state; route restart is not used. |
| SCOPE-05 | Scope | Preserve existing account-profile favorite navigation and visual preview contract (`avatar > cover > type visuals`, valid slug navigation). | automated | `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | local Flutter widget/controller | passed | Slug navigation and preview fallback assertions remain green. |
| SCOPE-06 | Scope | Preserve repository-owned stream boundaries promoted by `HOM-07`, `HOM-08`, `FCX-08`, and `FCX-09`. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart` | local Flutter repository/controller | passed | No controller relay or widget-local source of truth added. |
| SCOPE-07 | Scope | Add regression evidence that covers both favorite and unfavorite transitions. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart` | local Flutter test | passed | Toggle test asserts both favorite and unfavorite refresh order. |
| AC-01 | Acceptance Criteria | Favoriting a valid account-profile target updates Home Favorites in the same running app session. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart` | local Flutter repository | passed | Favorite persistence triggers Home favorite-resume refresh. |
| AC-02 | Acceptance Criteria | Unfavoriting the same target updates/removes it from Home Favorites in the same running app session. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart` | local Flutter repository | passed | Unfavorite persistence triggers a second read-model refresh. |
| AC-03 | Acceptance Criteria | Home Favorites consumes repository-owned state/invalidation and does not depend on sibling controller relays or widget-local caches. | automated | `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart` | local Flutter controller | passed | Controller reads the repository stream/invalidation boundary. |
| AC-04 | Acceptance Criteria | Existing Home Favorites preview and navigation contracts remain stable. | automated | `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | local Flutter widget/controller | passed | Preview and route target assertions passed. |
| AC-05 | Acceptance Criteria | The regression is covered by fail-first automated Flutter tests. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart` | local Flutter test | passed | Regression test failed before repository-boundary fix and passed after. |
| DOD-01 | Definition of Done | All acceptance criteria have concrete evidence in the Completion Evidence Matrix. | evidence audit | `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/completed/TODO-store-release-home-favorites-refresh-regression.md` | local deterministic guard | passed | Guard row coverage is maintained before closure. |
| DOD-02 | Definition of Done | Focused Flutter tests pass. | automated | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | local Flutter test | passed | 18 tests passed on 2026-04-29. |
| DOD-03 | Definition of Done | `fvm dart analyze --format machine` passes or any unrelated pre-existing diagnostics are explicitly isolated. | analyzer | `fvm dart analyze --format machine` | local Flutter analyzer | passed | Official analyzer passed with no diagnostics on 2026-04-29. |
| DOD-04 | Definition of Done | Web build is run if touched surfaces affect compiled web output. | build | `bash scripts/build_web.sh ../web-app dev` | local Flutter web build | passed | Web bundle built to derived `../web-app` on 2026-04-29. |
| DOD-05 | Definition of Done | Independent review/triple audit is recorded before promotion claim. | audit | `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/` | documentation artifact | passed | Triple audit findings resolved; Claude rollback-boundary finding fixed. |
| DOD-06 | Definition of Done | ADB/device smoke evidence is recorded for available source-owned Android favorite contract validation. | device test | `integration_test/feature_favorites_query_contract_e2e_test.dart` | Android device `192.168.15.9:5555` | passed | Real backend favorite/unfavorite persistence and `GET /favorites` readback passed. |
| VAL-01 | Validation Steps | Flutter automated: favorite mutation updates the repository state consumed by Home Favorites. | automated + device | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart`; `integration_test/feature_favorites_query_contract_e2e_test.dart` | local Flutter repository + Android device | passed | Favorite mutation refreshes repository-owned favorite resumes; source-owned device contract smoke passed. |
| VAL-02 | Validation Steps | Flutter automated: unfavorite mutation updates/removes Home Favorites state. | automated + device | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart`; `integration_test/feature_favorites_query_contract_e2e_test.dart` | local Flutter repository + Android device | passed | Unfavorite mutation refreshes/removes repository-owned state; source-owned device contract smoke passed. |
| VAL-03 | Validation Steps | Flutter automated: Home Favorites widget/controller re-renders from repository-owned state without route restart. | automated + device | `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`; `integration_test/feature_favorites_query_contract_e2e_test.dart` | local Flutter widget/controller + Android device | passed | Home favorite section re-renders from repository data without route restart. |
| VAL-04 | Validation Steps | Architecture scan: no controller-to-controller relay or screen-local duplicate source-of-truth introduced. | source audit | `git diff -- lib/infrastructure/repositories/account_profiles_repository.dart lib/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section` | local source review | passed | Fix stays at repository boundary. |
| VAL-05 | Validation Steps | Device/runtime final: Android favorite/unfavorite contract smoke passed; Home route update is covered by repository/controller/widget tests without route restart. | device + automated | `integration_test/feature_favorites_query_contract_e2e_test.dart`; Home Favorites focused Flutter suite | Android device + local Flutter tests | passed | Source-owned Android contract smoke passed; Home route behavior has deterministic widget/controller coverage. |
| `ARCH-HOME-FAVORITES-01` | `Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)` | Current `origin/main` still carries the repository-owned Home favorites refresh regression coverage and the user-visible favorites-section contract. | `origin/main review` | `git -C flutter-app grep -n "toggleFavorite refreshes canonical favorite resumes consumed by Home after mutations\\|favorites section keeps backend ordering from /favorites payload\\|favorites section resolves profile navigation by slug, search otherwise" origin/main -- test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart`; `git -C laravel-app grep -n "test_favorites_returns_empty_payload_when_user_has_no_edges\\|test_favorites_store_creates_edge_for_authenticated_identity\\|test_favorites_destroy_removes_existing_edge" origin/main -- tests/Feature/Favorites/FavoritesControllerTest.php` | `origin/main source history` | `passed` | The refresh path, the read-model contract, and the favorites endpoint behavior are still present on current `origin/main`. |
| `ARCH-HOME-FAVORITES-02` | `Canonical Module Anchors` | Canonical docs on `origin/main` still encode the snapshot-backed favorites preview rules and the post-auth hydration contract that this regression hardened. | `doc review` | `git -C foundation_documentation grep -n "Post-Auth Identity Hydration Contract\\|Favorites Strip Preview Contract\\|FCX-12\\|HOM-07\\|HOM-08" origin/main -- modules/flutter_client_experience_module.md modules/tenant_home_composer_module.md` | `foundation origin/main docs` | `passed` | The durable Home/favorites contract is not stranded only inside the tactical TODO. |
| `ARCH-HOME-FAVORITES-03` | `Approval` | This archival move is an explicit documentation closeout request, not a fresh implementation or promotion claim. | `approval` | Explicit `2026-06-08` user request plus `promotion-lane-code-main-audit-20260608.md` | `historical archival closeout` | `passed` | The closeout is intentional and traceable. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the product symptom is narrow, but the correct fix is architecture-sensitive because it touches shared repository streams and Home state propagation.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new code was executed for this move; the TODO already contains criterion-specific Flutter/analyzer/device evidence and the archival action only reconciles lane status with current `origin/main`. | `n/a` | `historical archival closeout` | `n/a` | Existing `Local Delivery Notes (2026-04-29)`, `Post-Auth Hydration Follow-Up (2026-04-29)`, and `origin/main` contract review on `2026-06-08`. | Documentation-only move; no fresh CI-equivalent rerun was required. |

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` contract review | `git -C flutter-app grep -n "toggleFavorite refreshes canonical favorite resumes consumed by Home after mutations\\|favorites section keeps backend ordering from /favorites payload\\|favorites section resolves profile navigation by slug, search otherwise" origin/main -- test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart` | `origin/main` still carries the repository refresh regression and the favorites-section consumer/navigation coverage. |
| `laravel-app` contract review | `git -C laravel-app grep -n "test_favorites_returns_empty_payload_when_user_has_no_edges\\|test_favorites_store_creates_edge_for_authenticated_identity\\|test_favorites_destroy_removes_existing_edge" origin/main -- tests/Feature/Favorites/FavoritesControllerTest.php` | `origin/main` still carries the backend favorites query/store/destroy contract consumed by Home. |
| `foundation_documentation` doc review | `git -C foundation_documentation grep -n "Post-Auth Identity Hydration Contract\\|Favorites Strip Preview Contract\\|FCX-12\\|HOM-07\\|HOM-08" origin/main -- modules/flutter_client_experience_module.md modules/tenant_home_composer_module.md` | Canonical docs on `origin/main` still encode the repository-owned favorites refresh and post-auth hydration rules. |
| `Archival decision` | Explicit `2026-06-08` user request to move already promoted TODOs to `completed` after code/main investigation. | Documentation-only closeout approved. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new promotion PR package is being opened; confirm this move only reconciles a stale TODO with code and docs already present on `origin/main`. | `n/a` | `git -C flutter-app grep -n "toggleFavorite refreshes canonical favorite resumes consumed by Home after mutations" origin/main -- test/infrastructure/repositories/account_profiles_repository_test.dart`; `git -C laravel-app grep -n "test_favorites_store_creates_edge_for_authenticated_identity" origin/main -- tests/Feature/Favorites/FavoritesControllerTest.php` | `none` | No fresh PR/Copilot surface exists for this documentation-only move. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Promotion-lane archive hygiene` | Prevent a code-absorbed, device-validated Home favorites regression fix from lingering in `promotion_lane/` only because the older TODO never received final closeout normalization. | `passed` | `origin/main` contract review; `promotion-lane-code-main-audit-20260608.md` | `no findings` | The closeout preserves the original delivery/device packet and only reconciles stale lane status. |

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The file is being archived after the implementation/promotion wave already finished. | Truthful stage labeling, explicit archival rationale, and stable references to original evidence. | Claiming a new promotion packet exists when only a current `origin/main` review was performed. | Add archival closeout sections without rewriting the original delivery packet. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The task is to distinguish real completion from residual verification debt. | Make any historical packet gap explicit instead of silently burying it. | Treating unrecorded promotion paperwork as if it existed. | Record the archival catch-up basis directly in the TODO. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | The source-of-truth question is whether the delivered slice already crossed the final lane threshold. | Keep closeout tied to current `origin/main` contract review and existing evidence. | Leaving already-main-carried work stranded in `promotion_lane/`. | Move the TODO to `completed` once the final guard set passes. |

## TODO Closeout Disposition
- **Completed path:** `foundation_documentation/todos/completed/TODO-store-release-home-favorites-refresh-regression.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming the Home favorites refresh contract remains present on current `origin/main`.
- **Historical note:** this TODO already carried focused Flutter/analyzer/device evidence and the post-auth hydration follow-up; the archival move only reconciles stale lane status.
- **Reopen rule:** any new Home favorites refresh regression or repository-ownership drift must open a new TODO.
