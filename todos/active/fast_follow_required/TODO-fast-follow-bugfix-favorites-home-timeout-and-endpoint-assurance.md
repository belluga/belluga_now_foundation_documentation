# TODO (Fast Follow Bugfix): Favorites Home Timeout and Endpoint Assurance

## Title
Fast Follow Bugfix: Favorites Home Timeout and Endpoint Assurance

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Sentry captured a tenant-public runtime failure on May 17, 2026 (`2026-05-17T17:05:19.683Z`) while Home-related favorites state was loading for the Guarappari tenant app (`com.guarappari.app`, `0.0.1+4`).

Observed error:
- `StateError`
- `Bad state: Failed to load favorites from backend: Exception: Failed to GET favorites request [status=null] (https://guarappari.com.br/api/v1/favorites?page=1&page_size=30&registry_key=account_profile&target_type=account_profile): The request connection took longer than 0:00:00.000000 and it was aborted.`

Initial read points to a transport-layer failure before any response payload was processed:
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart` currently builds its client with plain `Dio()` and no explicit timeout policy.
- Comparable tenant-public DAL clients such as:
  - `flutter-app/lib/infrastructure/dal/dao/laravel_backend/map/laravel_map_poi_http_service.dart`
  - `flutter-app/lib/infrastructure/dal/dao/laravel_backend/discovery_filters/laravel_discovery_filters_http_service.dart`
  already set explicit `connectTimeout`, `receiveTimeout`, and `sendTimeout`.
- The Laravel `/favorites` route is backed by:
  - `laravel-app/packages/belluga/belluga_favorites/src/Http/Api/v1/Controllers/FavoritesController.php`
  - `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php`
- Current Laravel feature coverage exists in:
  - `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`
- Current Flutter DAL coverage exists in:
  - `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart`

This TODO exists because the right delivery is not “patch Flutter and assume the backend is fine”. The slice must explicitly classify whether the failure is:
- Flutter transport misconfiguration only,
- Laravel endpoint/query-path behavior only,
- or a mixed failure.

It must also explicitly audit whether the current tests are sufficient to prove `/favorites` behaves correctly under the intended contract. If they are not, the missing coverage must be added as part of this same slice.

This TODO supersedes the local-only exploratory artifact:
- `foundation_documentation/todos/ephemeral/EPHEMERAL-favorites-backend-timeout-home-refresh.md`
and incorporates the evidence packet:
- `foundation_documentation/artifacts/tmp/favorites-timeout-evidence.md`

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-bugfix-favorites-home-timeout-endpoint-assurance`
- **Why this is the right current slice:** this is one bounded bug/hardening slice with one primary outcome: restore trustworthy favorites loading for tenant-public Home by classifying the root cause, proving endpoint behavior, and closing test-quality gaps before choosing the smallest correct fix.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the problem is already concrete and evidenced by Sentry, and the affected behavior is narrow enough to stay inside one approval and verification conversation even though it spans Flutter transport and Laravel endpoint assurance.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `scope-check review required due unrelated preexisting repo drift outside this slice; no Laravel production code change was required`
- **Next exact step:** keep the implementation on the current bugfix branch; if `Completed`, `Lane-Promoted`, or `Production-Ready` is requested later, run the remaining derived audit-floor lanes (`security review`, `verification debt`, `final review`, and `triple review`) before changing delivery stage.

## Scope
- [x] Classify the root cause for the Sentry failure as `flutter-transport`, `laravel-endpoint`, or `mixed`, with evidence.
- [x] Audit the current Flutter favorites DAL tests for false-green coverage gaps relative to the observed timeout symptom.
- [x] Audit the current Laravel `/favorites` feature tests for endpoint-behavior sufficiency, including whether they meaningfully cover the runtime contract exercised by Home favorites loading.
- [x] Review the Laravel `/favorites` query/access path for obvious bounded-list/query-shape risks and record residual performance risk explicitly.
- [x] Add fail-first or coverage-hardening tests wherever the current suites are insufficient to prove the intended behavior.
- [x] Implement the smallest correct fix once the evidence identifies the real failure boundary.
- [x] Preserve the current favorites consumer contract unless evidence proves a contract defect.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:fix/sentry-invite-share-too-many-elements`, `laravel-app:dev (validation only; no code changes)`, `foundation_documentation:fix/sentry-invite-share-too-many-elements`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `code repos: dev; foundation_documentation: main`
- **Production-ready threshold for this TODO:** `stage or main as applicable to touched code repos`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `flutter transport + DAL tests` | `fix/sentry-invite-share-too-many-elements (workspace diff)` | `<pending>` | `<pending>` | `<pending>` | `local_implemented` |
| `laravel endpoint tests / query-path hardening` | `dev (validated, no code diff)` | `n/a` | `n/a` | `n/a` | `validated_no_change` |
| `foundation documentation / TODO evidence` | `fix/sentry-invite-share-too-many-elements (workspace diff)` | `n/a` | `n/a` | `<pending>` | `local_implemented` |

## Out of Scope
- [ ] Redesigning the Home favorites UI, navigation, or visual contract.
- [ ] Broad refactors of unrelated `Dio()` consumers outside the favorites path unless the investigation proves the same bug is shared and cannot be responsibly isolated.
- [ ] Reworking the entire social/favorites domain model.
- [ ] General-purpose performance tuning outside the bounded `/favorites` endpoint/query path.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** focused Flutter DAL timeout hardening, focused Laravel `/favorites` behavior/query-path hardening, and the exact tests required to prove the fix and endpoint behavior.
- **Must update or split the TODO:** any new unrelated favorites capability work, broad HTTP client normalization across many endpoints, Home UX redesign, or a wider social-loop contract rewrite.

## Definition of Done
- [x] The failure boundary is classified with concrete evidence as `flutter-transport`, `laravel-endpoint`, or `mixed`.
- [x] Current Flutter favorites DAL tests have been audited for sufficiency, and any false-green gap relevant to this bug has been closed with automated coverage.
- [x] Current Laravel `/favorites` endpoint tests have been audited for sufficiency, and any meaningful endpoint-behavior gap relevant to this bug has been closed with automated coverage.
- [x] The chosen fix preserves the existing favorites consumer contract unless an explicitly documented contract defect is discovered and approved.
- [x] Residual endpoint-performance risk for `/favorites` has been explicitly reviewed and recorded.

## Validation Steps
- [x] Flutter favorites DAL regression suite passes.
- [x] Focused Flutter repository/controller/widget favorites regression suites pass.
- [x] Laravel favorites feature suite passes.
- [x] Flutter analyzer passes for the touched favorites transport surfaces.

## Completion Evidence Matrix (Required Before Delivery Claim)
Every `Definition of Done` item and every `Validation Steps` item must have a concrete evidence row before the TODO can claim `Local-Implemented`, move to `promotion_lane/`, move to `completed/`, or claim `Production-Ready`.

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | `Classify the root cause for the Sentry failure as flutter-transport, laravel-endpoint, or mixed, with evidence.` | `review` | `foundation_documentation/artifacts/tmp/favorites-timeout-evidence.md`; `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart`; `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php` | `local` | `passed` | Classified as `flutter-transport`: Sentry showed `connectTimeout=0ms`; Flutter DAL lacked explicit timeout policy; Laravel suite and query review did not reveal endpoint-contract failure requiring code change. |
| `SCOPE-02` | `Scope` | `Audit the current Flutter favorites DAL tests for false-green coverage gaps relative to the observed timeout symptom.` | `test` | `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | `local flutter test` | `passed` | `laravel_favorite_backend_test.dart` now captures `connectTimeout`, `sendTimeout`, and `receiveTimeout`, closing the false-green gap. |
| `SCOPE-03` | `Scope` | `Audit the current Laravel /favorites feature tests for endpoint-behavior sufficiency, including whether they meaningfully cover the runtime contract exercised by Home favorites loading.` | `test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php` | `local laravel safe runner` | `passed` | Suite passed with `10 tests / 44 assertions`; sufficient for current endpoint contract, ordering, empty state, authenticated/anonymous identity, and mutation readback behavior. |
| `SCOPE-04` | `Scope` | `Review the Laravel /favorites query/access path for obvious bounded-list/query-shape risks and record residual performance risk explicitly.` | `review` | `foundation_documentation/artifacts/tmp/favorites-endpoint-performance-review.md`; `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php`; `laravel-app/packages/belluga/belluga_favorites/database/migrations/2026_03_19_000100_create_favorites_core_collections.php` | `local` | `passed` | Query path is bounded aggregation with existing owner/registry/index support; residual risk recorded as `low` for this slice. |
| `SCOPE-05` | `Scope` | `Add fail-first or coverage-hardening tests wherever the current suites are insufficient to prove the intended behavior.` | `test` | `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart`; `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | `local flutter test` | `passed` | Timeout assertions were added before closure; Laravel suite required no new case after the sufficiency audit. |
| `SCOPE-06` | `Scope` | `Implement the smallest correct fix once the evidence identifies the real failure boundary.` | `code+test` | `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart`; `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | `local` | `passed` | Fix stayed local to the favorites DAL by enforcing explicit per-request `connectTimeout`, `sendTimeout`, and `receiveTimeout`. |
| `SCOPE-07` | `Scope` | `Preserve the current favorites consumer contract unless evidence proves a contract defect.` | `test` | `cd flutter-app && fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | `local flutter test` | `passed` | Repository/controller/widget regressions stayed green after the transport-only fix; no consumer-contract change was introduced. |
| `DOD-01` | `Definition of Done` | `The failure boundary is classified with concrete evidence as flutter-transport, laravel-endpoint, or mixed.` | `review` | `foundation_documentation/artifacts/tmp/favorites-timeout-evidence.md` | `local` | `passed` | Final classification: `flutter-transport`. Laravel endpoint/query review remained contract-adequate for this slice. |
| `DOD-02` | `Definition of Done` | `Current Flutter favorites DAL tests have been audited for sufficiency, and any false-green gap relevant to this bug has been closed with automated coverage.` | `test` | `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | `local flutter test` | `passed` | Suite passed after adding timeout assertions that would fail on the prior buggy implementation. |
| `DOD-03` | `Definition of Done` | `Current Laravel /favorites endpoint tests have been audited for sufficiency, and any meaningful endpoint-behavior gap relevant to this bug has been closed with automated coverage.` | `test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php` | `local laravel safe runner` | `passed` | Audit conclusion: existing suite already proves the relevant endpoint behavior for this bug class; no Laravel test expansion was required. |
| `DOD-04` | `Definition of Done` | `The chosen fix preserves the existing favorites consumer contract unless an explicitly documented contract defect is discovered and approved.` | `test` | `cd flutter-app && fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | `local flutter test` | `passed` | Consumer-facing repository/controller/widget behavior remained green; the patch touched only timeout policy in the DAL. |
| `DOD-05` | `Definition of Done` | `Residual endpoint-performance risk for /favorites has been explicitly reviewed and recorded.` | `review` | `foundation_documentation/artifacts/tmp/favorites-endpoint-performance-review.md` | `local` | `passed` | Residual risk recorded as `low`: bounded aggregation, existing index support, no evidence that backend slowness caused the captured failure. |
| `VAL-01` | `Validation Steps` | `Flutter favorites DAL regression suite passes.` | `test` | `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | `local flutter test` | `passed` | Passed with `4 tests`. |
| `VAL-02` | `Validation Steps` | `Focused Flutter repository/controller/widget favorites regression suites pass.` | `test` | `cd flutter-app && fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | `local flutter test` | `passed` | Passed with `19 tests`. |
| `VAL-03` | `Validation Steps` | `Laravel favorites feature suite passes.` | `test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php` | `local laravel safe runner` | `passed` | Passed with `10 tests / 44 assertions`. |
| `VAL-04` | `Validation Steps` | `Flutter analyzer passes for the touched favorites transport surfaces.` | `analyzer` | `cd flutter-app && fvm dart analyze --format machine` | `local flutter analyzer` | `passed` | Command exited `0` with no findings for this slice. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Independent test-quality review if execution changes test logic materially. | `flutter-app/test/**`, `laravel-app/tests/**` | `completed via test_quality_audit.sh + bounded self-review` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the user-visible symptom is narrow, but the slice crosses Flutter transport, Laravel endpoint assurance, and test-quality proof. It is not a trivial one-file patch.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant_home_composer_module.md` section `7 Canonical Decision Baseline` if durable Home favorites hydration behavior changes.
  - `flutter_client_experience_module.md` section `2.1 Domain Rules` / `Favorites Strip Preview Contract` if durable client/runtime contract wording changes.
  - `invite_and_social_loop_module.md` contract sections for favorites endpoint behavior only if backend contract semantics change.
- **Module decision consolidation targets (required):**
  - `tenant_home_composer_module.md` `HOM-09`
  - `flutter_client_experience_module.md` `FCX-12`
  - `invite_and_social_loop_module.md` `INV-33` if first-production compatibility posture becomes relevant

## Decisions (Resolved Before Freeze)
- [x] `D-01` This slice must evaluate Flutter transport and Laravel endpoint behavior together before choosing a fix.
- [x] `D-02` This slice cannot close on a code patch alone; it must explicitly evaluate whether the current tests are sufficient to prove `/favorites` works as intended.
- [x] `D-03` If current Flutter or Laravel tests are insufficient for this bug class, they must be improved in the same slice instead of being deferred.
- [x] `D-04` The preferred solution is the smallest fix that preserves the existing Home favorites and favorites-endpoint contract.
- [x] `D-05` Exact failure classification and explicit residual performance risk are required outputs; “probably Flutter” is not sufficient closure.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `tenant_home_composer_module.md#HOM-09` | Registered identity-dependent Home state is repository-owned and hydrated centrally; Home must not compensate via route restarts or local reloads. | `Preserve` | `foundation_documentation/modules/tenant_home_composer_module.md` section `7` |
- | `flutter_client_experience_module.md#FCX-12` | App-shell post-auth hydration owns favorite resume refresh for registered identities. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `7` |
- | `flutter_client_experience_module.md#Favorites Strip Preview Contract` | Tenant-home favorites remains snapshot-backed and navigates directly by slug when valid. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1` |
- | `invite_and_social_loop_module.md#INV-33` | First-production social capabilities have zero backward-compatibility burden for pre-release favorites behavior. | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` section `7` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The orchestrator must classify the bug with evidence instead of assuming Flutter or Laravel in advance.
- [x] `D-02` The orchestrator must review and, when needed, strengthen both Flutter and Laravel tests for this slice.
- [x] `D-03` The chosen implementation must preserve Home favorites ownership boundaries and snapshot-backed favorites behavior unless an approved contract defect is discovered.
- [x] `D-04` The fix should remain as local as possible to the real failure boundary.

## Questions To Close
- [x] Does the `/favorites` query path show any material performance/index risk beyond the transport symptom, or is the endpoint contract already adequate once the client timeout policy is corrected?
  Endpoint contract is adequate for this slice once the client timeout policy is corrected; residual query-path risk remains `low` and recorded explicitly in the endpoint review artifact.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The observed Sentry failure is most likely caused by Flutter favorites transport using `Dio()` without explicit timeout settings. | `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart`; Sentry error shows `connectTimeout = 0:00:00.000000`; comparable clients already set explicit timeouts. | Backend slowness or another shared transport path may be the primary failure instead. | `High` | `Keep as Assumption` |
| `A-02` | The current Flutter DAL suite is false-green for this exact bug because it does not assert timeout configuration. | `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart` currently asserts auth/query/payload behavior only. | Another suite already proves timeout behavior and should fail red once checked. | `High` | `Keep as Assumption` |
| `A-03` | The current Laravel feature suite is good evidence for endpoint shape and mutation semantics, but not enough by itself to dismiss endpoint-behavior or query-path concerns raised by a production-like timeout. | `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php` covers ordering, empty payload, default registry, anonymous behavior, visual payload, store/destroy, push membership sync. | The suite already includes all evidence required for this bug class and needs no strengthening. | `Medium` | `Keep as Assumption` |
| `A-04` | The `/favorites` endpoint is a bounded-list aggregation path, not an exact lookup path, so performance scrutiny should focus on query shape and residual risk rather than direct-key lookup anti-patterns. | `FavoritesQueryService::listForOwner()` uses aggregation with `$match`, `$lookup`, `$sort`, `$skip`, `$limit`. | A hidden list-scan or unbounded path may still exist and need direct hardening. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart`
- `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart`
- `flutter-app/test/infrastructure/repositories/account_profiles_repository_test.dart` if repository/home-facing regression proof needs expansion
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/**` only if consumer-proof needs adjustment
- `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php` reviewed for sufficiency only; no backend code change required
- `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`

### Ordered Steps
1. Re-state the exact Sentry symptom in the evidence packet and confirm the current local understanding of the failing path.
2. Audit the Flutter favorites DAL client and its tests for timeout-contract gaps.
3. Audit the Laravel `/favorites` endpoint implementation and current feature tests for behavior sufficiency and query-path residual risk.
4. Add fail-first or coverage-hardening tests first where the current suites are insufficient.
5. Implement the smallest correct fix based on the classified boundary (`flutter-transport`, `laravel-endpoint`, or `mixed`).
6. Re-run the focused Flutter and Laravel validation suites.
7. Record residual risk explicitly, especially if the final diagnosis is “client-only fix with backend risk judged acceptable”.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the current evidence already indicates at least one false-green path in Flutter, and the user explicitly requested endpoint sufficiency verification.
- **Fail-first target(s) (when required):**
  - Flutter DAL test proving explicit non-zero timeout behavior for `/favorites`
  - Laravel feature test additions only if the current suite fails to prove the relevant endpoint behavior or query-path assumptions

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Home favorites load after registered/authorized identity | `payload consumed by UI` | `shared-android-web` | `ADB integration or Playwright readonly` | `no` | `yes` | Existing Home favorites Flutter repository/controller/widget evidence plus runtime lane if final fix crosses visible flow semantics | If the final fix remains transport-local and consumer behavior is unchanged, focused automated consumer tests may remain sufficient for local implementation while final runtime lane is decided at delivery time |
| `/favorites` GET endpoint contract | `field/DTO/domain refactor` | `n/a` | `n/a` | `no` | `yes` | Laravel feature test suite + query-path review | Backend-only contract behavior; no direct separate frontend lane needed for local proof |
| Favorite/unfavorite mutation readback remains coherent | `save/readback` | `shared-android-web` | `ADB integration or Playwright mutation` | `yes` | `yes` | Existing query-contract integration evidence reused if the final fix affects mutation/readback semantics; otherwise focused regression proof + rationale | If the fix does not alter mutation semantics and only hardens initial GET transport, mutation runtime rerun may be waived with explicit rationale |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / favorites DAL regression suite` | The bug root cause and production fix live in the Flutter favorites DAL. | `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | `Local-Implemented` | `passed` | `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart` | Passed with `4 tests`. |
| `flutter-app / favorites repository-controller-widget regression suites` | Consumer-facing favorites readback/order/navigation contracts must stay green after the transport fix. | `cd flutter-app && fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | `Local-Implemented` | `passed` | `cd flutter-app && fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | Passed with `19 tests`. |
| `flutter-app / analyzer` | Flutter architecture and typing for the touched transport/test surfaces must remain clean. | `cd flutter-app && fvm dart analyze --format machine` | `Local-Implemented` | `passed` | `cd flutter-app && fvm dart analyze --format machine` | Exited `0` with no findings. |
| `laravel-app / favorites feature suite` | The user explicitly requested endpoint sufficiency verification. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php` | `Local-Implemented` | `passed` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php` | Passed with `10 tests / 44 assertions`; no Laravel code diff was needed. |

### Runtime / Rollout Notes
- No migration or feature-flag rollout is expected if the final fix remains transport-local.
- If backend query-path hardening requires indexes or persistence-shape changes, update this section before requesting `APROVADO`.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
Review the `Assumptions Preview` and `Execution Plan` against architecture, code quality, tests, performance, security, elegance, and structural soundness before approval.

### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** `high`
  - **Evidence:** Sentry failure at `2026-05-17T17:05:19.683Z`; Flutter favorites client currently uses plain `Dio()` without explicit timeout policy.
  - **Why it matters now:** a Flutter-only patch without endpoint sufficiency review could mask a real backend issue and leave the no-context operator unable to defend the fix.
  - **Option A (Recommended):** classify Flutter and Laravel together, harden missing tests first, then patch the real boundary only.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** patch Flutter timeout behavior immediately and treat backend as out of scope.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** leave the slice as observational only.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** Option A.

- **Issue ID:** `TEST-01`
  - **Severity:** `high`
  - **Evidence:** `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart` currently passes while not asserting timeout behavior; Laravel feature suite does not currently prove timing/query-risk adequacy by itself.
  - **Why it matters now:** the user explicitly asked for endpoint adequacy verification; closing on retrofit-free or incomplete tests would keep the bug class weakly defended.
  - **Option A (Recommended):** make test-sufficiency audit a first-class delivery item and add missing coverage before or with the fix.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** patch code first and decide later whether tests need updates.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** keep current tests as sufficient.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** Option A.

### Failure Modes & Edge Cases
- [ ] Flutter timeout hardening hides a latent backend slow query and the endpoint still degrades under real network/TLS conditions.
- [ ] Laravel tests prove payload semantics but not the exact runtime path that Home favorites loading exercises.
- [ ] A transport-only fix changes request behavior in a way not currently captured by Home-adjacent tests.

### Residual Unknowns / Risks
- [x] Current local evidence does not yet prove whether `/favorites` query performance is fully acceptable under production-like data volume.
  Residual risk is accepted as `low` for local implementation because the path is bounded, indexed, and no backend failure signature was observed in the captured incident.
- [x] The final implementation boundary may remain Flutter-only, but that is not approved until the endpoint/test sufficiency audit is complete.
  The endpoint/test sufficiency audit is complete and the final implementation boundary is `Flutter-only fix; Laravel validated-no-change`.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `n/a`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `n/a`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `n/a` | `n/a` | `n/a` | `n/a` | `n/a` | `n/a` | `n/a` |

## Audit Trigger Matrix
- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-bugfix-favorites-home-timeout-and-endpoint-assurance.md`
- **Latest TEACH evidence / artifact:** `foundation_documentation/artifacts/tmp/favorites-home-timeout-audit-escalation.json`

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Copy from the TODO Complexity section. |
| `blast_radius` | `cross-stack` | Flutter transport + Laravel endpoint assurance. |
| `behavioral_change_or_bugfix` | `yes` | Sentry-reported runtime bug. |
| `changes_public_contract` | `no` | Current plan is contract-preserving unless evidence disproves it. |
| `touches_auth_or_tenant` | `yes` | Tenant-public favorites path uses authenticated/identity-owned behavior. |
| `touches_runtime_or_infra` | `no` | No queue/infra change currently expected. |
| `touches_tests` | `yes` | Test sufficiency review is mandatory in this slice. |
| `critical_user_journey` | `yes` | Home favorites loading is tenant-public entry-surface behavior. |
| `release_or_promotion_critical` | `yes` | Runtime confidence matters for this slice. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-01` and `TEST-01` are high severity. |
| `explicit_three_lane_request` | `no` | Not explicitly requested. |

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)
- **Critique decision:** `required`
- **Why this decision:** `foundation_documentation/artifacts/tmp/favorites-home-timeout-audit-escalation.json` derived `CRITIQUE-BASELINE-ALWAYS` plus `CRITIQUE-EXPANDED-RISK-SIGNALS` for a medium, cross-stack, release-critical bugfix.
- **Impact signals in scope:** `cross-stack blast radius`, `critical user journey`, `high-severity issue card`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `assumptions preview`, `execution plan summary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `no (session policy did not authorize subagent delegation; bounded self-review recorded instead)`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `n/a`
- **Critique lenses:** `correctness|performance|elegance|structural-soundness|risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** `the only material critique finding was the false-green timeout gap in the Flutter DAL suite; it was integrated by adding explicit request-timeout assertions before closure`
- **Evidence / reference:** `foundation_documentation/artifacts/tmp/favorites-home-timeout-audit-escalation.json`; `foundation_documentation/artifacts/tmp/favorites-timeout-evidence.md`

## Commands (Run Locally)
- `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`
- `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-bugfix-favorites-home-timeout-and-endpoint-assurance.md --json-output foundation_documentation/artifacts/tmp/favorites-home-timeout-audit-escalation.json`
- `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart`
- `cd flutter-app && fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php`
- `cd flutter-app && fvm dart analyze --format machine`
- `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-bugfix-favorites-home-timeout-and-endpoint-assurance.md`

## Files Expected (Optional)
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart`
- `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart`
- `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`
- `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php` only if endpoint hardening is evidence-backed

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
- Scope-check result: `review required`
- Scope-check note: the `review required` output came from unrelated preexisting root/tooling drift outside this slice; no conflicting product-surface violation was identified for the touched favorites DAL/test artifacts.

| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/skills/bug-fix-evidence-loop/SKILL.md` | Real Sentry bugfix with false-green suspicion and explicit root-cause classification request. | Evidence-first root-cause statement and fail-first coverage closure. | Patch-first closure without proving why existing tests missed the bug. | Forced the evidence packet, coverage matrix, and timeout-contract test addition before closure. |
| `delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Flutter infrastructure/DAL and tests were modified. | DAL/repository layering discipline and clean analyzer result. | Cross-layer shortcutting or architecture regressions while fixing the transport path. | Added analyzer execution and kept the fix local to `LaravelFavoriteBackend`. |
| `delphi-ai/skills/test-creation-standard/SKILL.md` | The slice required new regression proof. | Test-first bias and behavior-defining assertions over weak status-only proof. | Retrofitting tests that would not fail on the buggy implementation. | Drove explicit timeout assertions in `laravel_favorite_backend_test.dart`. |
| `delphi-ai/skills/test-orchestration-suite/SKILL.md` | Multi-stack validation was required because the user asked for endpoint sufficiency verification too. | Truthful CI-equivalent suite accounting and cross-stack validation evidence. | Treating one narrow representative test as enough for cross-stack closure. | Resulted in separate Flutter DAL, Flutter consumer, Laravel feature, and analyzer lanes. |
| `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md` | The slice is governed by an active tactical TODO. | Delivery claims only after evidence matrix + guard green. | Claiming `Local-Implemented` without criterion-level proof. | Required the completion matrix and both audit/completion guards before final close-out. |

## Decision Adherence Validation (Mandatory Before Delivery)
| Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `foundation_documentation/artifacts/tmp/favorites-timeout-evidence.md`; `foundation_documentation/artifacts/tmp/favorites-endpoint-performance-review.md` | Root cause and endpoint sufficiency were evaluated together before closure. |
| `D-02` | `Adherent` | `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart`; `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php` | Both Flutter and Laravel test surfaces were audited explicitly. |
| `D-03` | `Adherent` | `flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart` | Missing coverage was added in-slice; no test gap was deferred. |
| `D-04` | `Adherent` | `flutter-app/lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart` | Fix stayed local to the confirmed transport boundary. |

## Module Decision Consistency Validation (1-1 Mandatory Before Delivery)
| Module Decision Ref | Planned Handling | Delivery Status (`Preserved|Superseded (Approved)|Regression`) | Evidence | Notes |
| --- | --- | --- | --- | --- |
| `tenant_home_composer_module.md#HOM-09` | `Preserve` | `Preserved` | `flutter-app/test/infrastructure/repositories/account_profiles_repository_test.dart`; `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart` | Home favorites hydration ownership stayed repository/controller-driven. |
| `flutter_client_experience_module.md#FCX-12` | `Preserve` | `Preserved` | `flutter-app/test/infrastructure/repositories/account_profiles_repository_test.dart` | Post-auth hydration still refreshes favorite ids through the repository path. |
| `flutter_client_experience_module.md#Favorites Strip Preview Contract` | `Preserve` | `Preserved` | `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | Snapshot-backed preview contract stayed intact. |
| `invite_and_social_loop_module.md#INV-33` | `Preserve` | `Preserved` | `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php` | No backend contract expansion or compatibility rewrite was introduced. |

## Security Risk Assessment (Mandatory Before Delivery)
- **Risk level:** `low`
- **Why this risk level:** the slice touched an auth/tenant-scoped read path, but it added only client-side timeout policy and test coverage; Laravel auth, tenant resolution, and authorization behavior were not changed.
- **Attack surface in scope:** `authenticated tenant-public favorites GET/POST/DELETE surface; bearer-token transport; tenant-owned favorites reads`
- **Attack simulation decision:** `required before Completed/Production-Ready by derived audit floor; not run for this Local-Implemented claim`
- **Review evidence:** `bounded self-review + unchanged Laravel feature suite`; `foundation_documentation/artifacts/tmp/favorites-home-timeout-audit-escalation.json`
- **Residual security risk:** `none identified for the local transport-only fix`

## Performance & Concurrency Risk Assessment (Mandatory Before Delivery)
- **Policy schema version:** `pcv-1`
- **Global sensitivity level:** `low`
- **Why this level:** the incident touched a user-critical read path and justified endpoint scrutiny, but the implemented code change remained Flutter-only and the backend query path was validated-no-change.
- **Current delivery stage at review time:** `Local-Implemented`

| Lane ID | Lane | Trigger Result | Trigger Severity | Trigger Reason Code | Gate Deadline | Minimum Evidence Rule | State | Residual Risk | Uncertainty Reason Code |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `endpoint-performance-scrutiny` | `recommended` | `low` | `EPS-DATA-PATH-CHANGED` | `before_local_implemented` | `EPS-E1` | `passed` | `low` | `none` |
| `FRC` | `frontend-race-condition-validation` | `not_needed` | `low` | `FRC-LIFECYCLE-ASYNC-EFFECT` | `before_local_implemented` | `FRC-POLICY` | `not_applicable` | `none` | `none` |
| `BCI` | `backend-concurrency-idempotency-validation` | `not_needed` | `low` | `BCI-EXACT-ONCE-SEMANTICS` | `before_local_implemented` | `BCI-POLICY` | `not_applicable` | `none` | `none` |
| `RLS` | `runtime-load-stress-validation` | `not_needed` | `low` | `RLS-CACHE-INDEX-SENSITIVE-PATH-CHANGED` | `before_local_implemented` | `RLS-E1` | `not_applicable` | `none` | `none` |

## Verification Debt Assessment (Required Before `Completed`; mandatory audit for `medium|big` or when debt signals exist)
- **Audit outcome:** `low`
- **Why this outcome:** the active TODO now has a green completion guard, explicit row-level evidence, and no inline code TODO/FIXME markers were introduced in the touched production/test files.
- **Inline code TODO debt:** `none`
- **Evidence / audit artifact:** `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-bugfix-favorites-home-timeout-and-endpoint-assurance.md`
- **Accepted residual debt:** `remaining external audit-floor lanes (security/final/triple) are still pending before any future Completed/Production-Ready claim`

## Independent Test Quality Audit Gate (Deterministic Floor From Audit Escalation)
- **Audit decision:** `required`
- **Why this decision:** derived audit floor marked `TQA-TESTS-TOUCHED`, `TQA-BEHAVIOR-OR-BUGFIX`, `TQA-CRITICAL-JOURNEY`, and `TQA-RELEASE-CRITICAL`.
- **Trigger signals in scope:** `changed test logic|bugfix/regression|critical-user-journey|non-trivial validation risk`
- **Required evidence matrix (when architectural):** `n/a`
- **Package mode:** `bounded-file-set`
- **Package minimum contents:** `bounded implementation diff|bounded test diff|validation evidence|expected behaviors/DoD|residual risks`
- **Canonical method:** `wf-docker-independent-test-quality-audit-method`
- **Audit isolation mode:** `bounded self-review + deterministic static scan`
- **Subagent mandate (when available):** `no (session policy did not authorize subagent delegation for this turn)`
- **Gate-satisfying evidence expectation:** `full applicable test-quality-audit outputs`
- **Audit focus:** `product/test delta alignment|fail-first alignment|bypass detection|assertion efficacy|coverage sufficiency|brittle test-only shortcuts`
- **Required applicable evidence:** `fail-first alignment for the timeout assertion; bypass scan via test_quality_audit.sh; decision-adherence evidence in this TODO`
- **Audit status:** `findings_integrated`
- **Findings summary:** `static audit found no hard bypass markers; medium heuristic signals came from existing Laravel auth shortcuts/status assertions and Flutter DI overrides, all reviewed as known harness patterns rather than blockers for this slice`
- **Evidence / reference:** `bash delphi-ai/tools/test_quality_audit.sh --path flutter-app/test/infrastructure/dal/laravel_favorite_backend_test.dart --path laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`

## Independent No-Context Final Review Gate (Deterministic Floor From Audit Escalation)
- **Final review decision:** `required`
- **Why this decision:** derived audit floor marked `FINAL-BASELINE-ALWAYS` plus `FINAL-EXPANDED-RISK-SIGNALS` for this medium, cross-stack, release-critical bugfix.
- **Impact signals in scope:** `cross-stack blast radius|auth/tenant scope|high-severity issue card`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline|approved scope boundary|bounded touched-surface/diff summary|adherence status|validation evidence index|test-quality-audit evidence|residual risks|verification debt`
- **Review isolation mode:** `bounded self-review`
- **Subagent mandate (when available):** `no (session policy did not authorize subagent delegation for this turn)`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `not_run for Local-Implemented; still required before any future Completed/Production-Ready claim`
- **Review focus:** `adherence|regressions|validation evidence|test-audit evidence|security/performance residuals|elegance residuals|structural regressions|verification debt`
- **Final review status:** `no_material_findings`
- **Findings summary:** `no regression or structural shortcut was found beyond the accepted low endpoint-performance uncertainty already recorded`
- **Evidence / reference:** `bounded self-review over the final diff, validation outputs, and guard artifacts in this TODO`
