# Post-Release OTP and Linked Profile Agenda Hardening Orchestration Plan

## Artifact Identity
- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Pending Approval`
- **Created:** `2026-05-04`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`

## Authority Boundary
- Governing TODOs define **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the orchestrator intends to sequence, parallelize, reconcile, and validate the OTP/reviewer-access hardening plus the capability-driven public-profile agenda restoration slice revealed by the `federacao` production case.
- If this plan conflicts with a governing TODO, stop and update the TODO or this plan before execution.
- This plan does not create a new backlog authority, tactical TODO, or approval conversation.
- Requirement wording in governing TODOs is literal. Replacing a named artifact, UI control, route, endpoint, settings contract, runtime target, or validation lane requires an approved row in the Spec Deviation Ledger before execution or delivery can proceed.
- Workstreams below are derived from the governing TODO contracts, not from broad repo ownership alone.

## Governing TODO Set
| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `PRH-OTP` | `foundation_documentation/todos/active/fast_follow_required/TODO-post-release-phone-otp-concurrency-and-idempotency-hardening.md` | primary auth hardening and Google Play reviewer-access slice | can start after approval |
| `PRH-FEDERACAO` | `foundation_documentation/todos/active/fast_follow_required/TODO-post-release-account-profile-agenda-linked-profile-resolution.md` | public-profile agenda regression slice for capability-enabled account profiles across canonical occurrence relationship modes | can start after approval |

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `PRH-OTP-DOD-01` | `PRH-OTP :: Concurrent successful verify requests cannot mint more than one effective challenge consumption.` | `OTP backend worker` | `TenantPhoneOtpAuthService`, atomic consume path, auth endpoint | fail-first concurrency test + atomic persistence diff | Laravel race-focused feature/integration tests | concurrent probe summary against a safe runtime or deterministic local environment | `planned` |
| `PRH-OTP-DOD-02` | `PRH-OTP :: Concurrent invalid verify requests cannot undercount attempts or skip the intended lock threshold.` | `OTP backend worker` | invalid-attempt counter / lock path, auth endpoint | fail-first lockout test + atomic attempt update diff | Laravel race-focused feature/integration tests | concurrent invalid-code probe summary | `planned` |
| `PRH-OTP-DOD-03` | `PRH-OTP :: Concurrent challenge requests cannot bypass resend cooldown or create more than the allowed active effect for the phone.` | `OTP backend worker` | challenge issuance / cooldown path, challenge endpoint | fail-first challenge-race test + serialized issuance diff | Laravel race-focused feature/integration tests | concurrent challenge probe summary | `planned` |
| `PRH-OTP-DOD-04` | `PRH-OTP :: Google Play review can authenticate through a documented, reusable review path without relying on live OTP delivery.` | `OTP backend worker` | allowlisted review phone + review-phone verify branch inside the normal login route | backend settings/auth diff from `WS-OTP-BE` | Laravel review-access tests from `WS-OTP-BE` | merged-state ADB login flow executed by the reconciliation lane on the frozen runtime target | `planned` |
| `PRH-OTP-DOD-05` | `PRH-OTP :: Reviewer-access settings persist only the hash and never read back or expose the cleartext review code outside the operator-side helper input.` | `OTP admin settings worker` | tenant-admin review-access settings endpoint/UI, `Gerar hash`, hash-only payload | Flutter admin/settings UI diff from `WS-OTP-FE` + backend settings contract diff from `WS-OTP-BE` | Flutter widget/controller tests + Laravel settings contract tests | merged-state Playwright mutation flow executed by the reconciliation lane | `planned` |
| `PRH-OTP-DOD-06` | `PRH-OTP :: Non-allowlisted phones cannot use the review-access path, disabled review users cannot authenticate through it, and the review path cannot authorize the wrong tenant/user identity.` | `OTP backend worker` | negative-path login route, tenant-boundary checks, disabled-user revocation | backend auth/settings diff from `WS-OTP-BE` | Laravel negative-path tests + Flutter/admin tests when needed | merged-state runtime evidence for rejection of non-allowlisted phone, disabled user, and wrong-tenant attempt, executed by the reconciliation lane | `planned` |
| `PRH-OTP-DOD-07` | `PRH-OTP :: The reviewer-access path remains rate-limited and audit-logged under the same security floor frozen by this TODO.` | `OTP backend worker` | reviewer-access rate limit path, audit-log artifact, auth endpoint | backend auth/rate-limit/audit diff from `WS-OTP-BE` | Laravel rate-limit and audit-log tests | merged-state evidence of rate-limit behavior plus audit-log emission for the reviewer credential path | `planned` |
| `PRH-OTP-DOD-08` | `PRH-OTP :: Regression tests prove the protected invariants.` | `OTP backend worker` | Laravel race/serialization regression suite, Flutter admin regression suite when touched | regression test additions in backend and Flutter admin settings when touched | targeted Laravel/Flutter regression test logs | `n/a` | `planned` |
| `PRH-OTP-DOD-09` | `PRH-OTP :: Residual concurrency and reviewer-access risk is explicitly documented.` | `reconciliation validation worker` | TODO evidence notes, checkpoint manifest, risk record | docs/evidence diff | `n/a` | `n/a` | `planned` |
| `PRH-OTP-DEC-10` | `PRH-OTP :: D-10 The review phone must resolve through the normal phone-auth identity flow; no parallel review-only identity path is allowed.` | `OTP backend worker` | normal phone-auth identity resolution path | backend auth/settings diff from `WS-OTP-BE` | Laravel tests proving review phone resolves through the canonical phone-auth identity path | merged-state review-phone login evidence confirming no parallel review-only identity surface | `planned` |
| `PRH-OTP-DEP-01` | `PRH-OTP :: DEP-01 Use the canonical concurrency validation workflow and probe helper against a safe environment.` | `OTP backend worker` | frozen probe workflow + safe runtime target declaration in checkpoint manifest | docs/evidence diff + probe helper invocation recipe | probe helper dry-run or targeted backend command log | `n/a` | `planned` |
| `PRH-OTP-DEP-02` | `PRH-OTP :: DEP-02 Decide whether atomicity is enforced with conditional updates, find-and-update semantics, uniqueness constraints, or a combination.` | `OTP backend worker` | explicit atomicity decision note tied to touched auth persistence path | backend design note in checkpoint manifest + implementation diff | targeted Laravel tests proving the chosen atomicity mechanism holds under race conditions | `n/a` | `planned` |
| `PRH-OTP-DEP-03` | `PRH-OTP :: DEP-03 Freeze the reviewer-access policy before implementation: allowlisted dedicated review phone only, reusable credential available during review, English instructions in Play Console, auditable and revocable by disabling the resolved user for that phone.` | `OTP backend worker` | reviewer-access policy freeze note in checkpoint manifest | docs/evidence diff + settings/auth contract note | `n/a` | `n/a` | `planned` |
| `PRH-OTP-VAL-01` | `PRH-OTP :: Run targeted backend tests for OTP challenge/verify concurrency semantics.` | `OTP backend worker` | targeted auth test commands | targeted Laravel test suite logs | targeted Laravel race test command logs | `n/a` | `planned` |
| `PRH-OTP-VAL-02` | `PRH-OTP :: Run real concurrent probes with the canonical helper at multiple levels (5, 10, 20) and capture status/latency output.` | `OTP backend worker` | probe artifact/log | concurrency helper output + invariant notes | probe command logs | safe runtime probe evidence with frozen pass/fail interpretation: zero invariant violations, zero timeout/5xx, latency captured for observability; p95 latency above 2x single-request baseline escalates to blocker or follow-up note | `planned` |
| `PRH-OTP-VAL-03` | `PRH-OTP :: Validate domain invariants from persisted PhoneOtpChallenge and issued-token outcomes, not only HTTP status codes.` | `OTP backend worker` | `PhoneOtpChallenge`, issued-token outcomes, auth endpoint | invariant assertions in tests/probes | Laravel assertions covering persisted status/attempts/token ownership | probe/result summary referencing stored challenge and issued credentials | `planned` |
| `PRH-OTP-VAL-04` | `PRH-OTP :: Reconfirm that no cross-account or tenant-isolation issue exists after the hardening.` | `OTP backend worker` | auth invariants / issued-token ownership | backend assertions and invariant notes | Laravel tests covering same-phone and tenant-isolation semantics | probe/result summary or explicit non-regression runtime evidence | `planned` |
| `PRH-OTP-VAL-05` | `PRH-OTP :: Validate the reviewer-access flow with the same published app/login surface reviewers will use.` | `OTP backend worker` | login route / OTP screens with the review-phone branch staying inside the normal login surface | supporting code/test diffs from `WS-OTP-BE` and `WS-OTP-FE` | Flutter integration or backend contract tests as applicable | merged-state ADB device flow on the published-style build target; if web becomes materially different, add Playwright too | `planned` |
| `PRH-OTP-VAL-06` | `PRH-OTP :: Validate the admin/settings path that generates and persists the review-code hash without persisting cleartext review code.` | `OTP admin settings worker` | admin settings page / generate hash action / settings endpoint | Flutter + backend settings diffs | Flutter tests + Laravel settings tests | merged-state Playwright admin mutation flow | `planned` |
| `PRH-OTP-VAL-06B` | `PRH-OTP :: Validate negative-path reviewer-access behavior for non-allowlisted phones, disabled review users, and tenant-boundary misuse attempts.` | `OTP backend worker` | negative-path login route / auth endpoint | supporting backend/auth diffs | Laravel negative-path tests | merged-state runtime rejection evidence for each misuse path | `planned` |
| `PRH-OTP-VAL-06C` | `PRH-OTP :: Validate that the review phone still resolves through the normal phone-auth identity path and does not create or rely on a parallel review-only identity contract.` | `OTP backend worker` | canonical phone-auth identity resolution path | supporting backend/auth diffs from `WS-OTP-BE` | Laravel tests proving the review phone resolves through the normal phone-auth identity flow | merged-state reviewer login evidence confirming no parallel review-only identity surface | `planned` |
| `PRH-OTP-VAL-06D` | `PRH-OTP :: Validate reviewer-access rate limiting and audit-log evidence for the reusable reviewer credential path.` | `OTP backend worker` | reviewer-access rate-limit path / audit-log artifact | supporting backend/auth diffs from `WS-OTP-BE` | Laravel rate-limit and audit-log tests | merged-state evidence of rate limiting plus emitted audit-log proof for the reviewer credential path | `planned` |
| `PRH-OTP-VAL-07` | `PRH-OTP :: Record the exact Play Console app-access instructions that will be provided in English.` | `reconciliation validation worker` | reviewer instructions artifact | docs/evidence diff | `n/a` | `n/a` | `planned` |
| `PRH-OTP-SET-SEC-01` | `PRH-OTP :: Review-Access Settings Contract :: Security note: the persisted settings must stay backend-private and must not be serialized into public environment/bootstrap payloads or any client-readable settings endpoint.` | `OTP backend worker` | backend-private settings endpoint / payload contract | backend settings serialization diff | Laravel settings serialization tests | `n/a` | `planned` |
| `PRH-OTP-DEP-04` | `PRH-OTP :: DEP-04 If response semantics change, update the owning Flutter/auth contract docs before closure.` | `OTP backend worker` | module/auth contract documentation sync | docs diff in `foundation_documentation/modules/**` when semantics change | `n/a` | `n/a` | `planned` |
| `PRH-FED-DOD-01` | `PRH-FEDERACAO :: Add fail-first Laravel coverage proving account profiles with agenda capability expose agenda occurrences when canonically related through distinct occurrence relationship modes, including place_ref and linked/event-party participation.` | `Agenda backend worker` | `AccountProfileAgendaOccurrencesService` failing coverage across multiple relationship modes | fail-first feature test diff | Laravel feature test logs | `n/a` | `planned` |
| `PRH-FED-DOD-02` | `PRH-FEDERACAO :: Preserve venue/place-owner agenda behavior for place_ref-backed profiles as one valid relationship mode, not a special-case exception.` | `Agenda backend worker` | venue/place-owner agenda query path | backend service diff | Laravel regression test for `place_ref` path | `n/a` | `planned` |
| `PRH-FED-DOD-03` | `PRH-FEDERACAO :: Correct AccountProfileAgendaOccurrencesService so agenda resolution is driven by profile capabilities plus canonical occurrence relationships, not by hardcoded profile types or an is_poi_enabled=true => place_ref-only assumption.` | `Agenda backend worker` | service query/resolution logic | backend service diff | Laravel feature tests for multiple relationship modes plus unrelated/non-capability preservation | `n/a` | `planned` |
| `PRH-FED-DOD-04` | `PRH-FEDERACAO :: Validate the public account-profile detail payload returns agenda_occurrences for the affected capability-enabled profile class.` | `Agenda backend worker` | public profile detail payload endpoint | backend formatter/query diff | Laravel payload/projection test | runtime payload probe against non-production target or deterministic fixture | `planned` |
| `PRH-FED-DOD-05` | `PRH-FEDERACAO :: Validate Flutter public profile detail renders upcoming events once the payload is corrected.` | `Agenda readback worker` | public profile agenda section/tab | Flutter readback evidence from `WS-FED-READBACK` | Flutter public profile tests + analyzer | merged-state Playwright readonly public profile route or ADB profile route, one lane if behavior is shared | `planned` |
| `PRH-FED-VAL-01` | `PRH-FEDERACAO :: Laravel feature tests covering at least: a capability-enabled profile related through place_ref, a capability-enabled profile related through linked/event-party participation, and preservation against unrelated/non-capability profiles.` | `Agenda backend worker` | targeted feature-test command set | targeted feature-test diff | Laravel targeted feature-test logs | `n/a` | `planned` |
| `PRH-FED-VAL-02` | `PRH-FEDERACAO :: Targeted payload probe against tenant runtime or equivalent deterministic test fixture proving agenda_occurrences are non-empty for the affected capability-enabled profile class.` | `Agenda backend worker` | payload probe artifact | payload probe helper/log | payload assertion command logs | non-production runtime payload proof or deterministic fixture output | `planned` |
| `PRH-FED-VAL-04` | `PRH-FEDERACAO :: Flutter public profile test proving agenda modules/tabs render when corrected payload arrives.` | `Agenda readback worker` | public profile agenda widget/test | Flutter test diff from `WS-FED-READBACK` | Flutter public profile test logs | `n/a` | `planned` |
| `PRH-FED-VAL-05` | `PRH-FEDERACAO :: fvm dart analyze --format machine for Flutter surfaces touched by any follow-up readback/test change.` | `Agenda readback worker` | analyzer cleanliness | Flutter diff if touched | `fvm dart analyze --format machine` | `n/a` | `planned` |
| `PRH-FED-OOS-01` | `PRH-FEDERACAO :: Out of Scope :: Reclassifying this as a discovery/map/filter issue.` | `Agenda backend worker` | `map/filter` boundary | touched-surface audit showing the fix stays in account-profile agenda scope, not discovery/map/filter scope | `n/a` | `n/a` | `planned` |
| `PRH-FED-DOC-01` | `PRH-FEDERACAO :: Decision consolidation targets: account_profile_catalog_module.md and agenda_and_action_planner_module.md.` | `Agenda backend worker` | canonical module documentation sync | docs diff in the named module docs when stable decision text changes | `n/a` | `n/a` | `planned` |

## Spec Deviation Ledger
| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | `No spec deviations approved.` | `n/a` | `n/a` | `n/a` |

## Dependency Graph
- `PRH-OTP` and `PRH-FEDERACAO` are implementation-independent and may run in parallel.
- Both TODOs share `laravel-app` and therefore converge on the same full local `Laravel CI` suite before any local-delivery claim.
- `PRH-OTP` additionally depends on tenant-admin settings/UI work in `flutter-app`; `PRH-FEDERACAO` touches `flutter-app` only if public-profile readback/analyzer coverage changes are required.
- Runtime/browser/device acceptance depends on a reconciliation branch/runtime that serves the merged state of both slices.
- This plan still bundles both slices because the user explicitly requested one combined orchestration lane, both slices are immediate post-release hardening on `origin/main`, both rely on the same downstream docs checkpoint, and both must converge on the same merged-state `Laravel CI` plus final runtime evidence before any combined delivery claim.
- Despite the shared approval wave, each slice keeps separate traceability rows and may be marked `blocked` independently in consolidated delivery evidence if only one slice regresses.

## Orchestration Topology
- **Base branch / commit:** `origin/main`
- **Orchestrator reconciliation branch:** `reconcile/post-release-otp-linked-profile-hardening-20260504`
- **Principal checkout policy:** principal checkout stays on the reconciliation branch because browser/device/runtime evidence depends on the integrated branch state.
- **Worker branches / worktrees:**
  - `worker/post-release-otp-backend-20260504`
  - `worker/post-release-otp-admin-settings-20260504`
  - `worker/post-release-linked-profile-agenda-20260504`
  - `worker/post-release-profile-readback-20260504` only if Flutter public-profile test/readback changes are actually required

## Checkpoint / Branch Accumulation Control
- **Checkpoint manifest path:** `foundation_documentation/artifacts/checkpoints/post-release-otp-linked-profile-hardening-2026-05-04.md`
- **Checkpoint policy:** checkpoints are pushed recovery states plus manifests, not indefinite accumulation branches.
- **Allowed checkpoint statuses:** `wip_checkpoint`, `validated_local_checkpoint`, `promotion_ready_checkpoint`, `superseded_checkpoint`.
- **Same-branch continuation rule:** continue on the orchestrator branch only while the work remains inside this approved plan and the checkpoint manifest records the next exact step. After promotion or scope drift, supersede the branch.
- **Build artifact policy:** generated `web-app` build output is excluded unless a later promotion workflow explicitly owns it.

## Workstreams
| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-OTP-BE` | `laravel-app` auth service, reviewer-access settings enforcement, concurrency probe helpers/tests | `PRH-OTP` decisions `D-01..D-09`; onboarding/auth module anchors | backend checkpoint with fail-first race tests turned green and review-access verify path implemented | targeted Laravel tests, concurrency probes, `composer run architecture:guardrails` |
| `WS-OTP-FE` | `flutter-app` tenant-admin settings surface for review phone/hash generation plus supporting DTO/repository/UI tests | `WS-OTP-BE` settings contract, `PRH-OTP-DOD-05`, `PRH-OTP-VAL-06` | Flutter checkpoint with cleartext helper + `Gerar hash` action + hash-only persistence UX | focused Flutter tests, `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`, `fvm dart analyze --format machine`, web build |
| `WS-FED-BE` | `laravel-app` profile-agenda resolution service, payload projection tests | `PRH-FEDERACAO` decisions `D-01..D-04` | backend checkpoint with capability-driven agenda resolution green across canonical relationship modes, including `place_ref` preservation | targeted Laravel feature tests, `composer run architecture:guardrails` |
| `WS-FED-READBACK` | `flutter-app` public profile agenda readback validation, including test/readback proof even when backend is the main code delta | `WS-FED-BE` payload shape, `PRH-FED-DOD-05`, `PRH-FED-VAL-04`, `PRH-FED-VAL-05` | Flutter checkpoint or explicit validation packet proving agenda render/readback on the corrected payload | focused Flutter tests + analyzer |
| `WS-RECON` | reconciliation branch, docs evidence, runtime/browser/device validation | all worker checkpoints | integrated branch with CI-equivalent suites and runtime evidence | orchestration guards, full CI-equivalent suites, browser/device runs |

## Execution Ownership Ledger
| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-OTP-BE` | `OTP backend worker` | `none` except merge-conflict reconciliation | checkpoint commit + targeted Laravel tests + guardrails + probe outputs | merged checkpoint + full local `Laravel CI` suite + runtime review-login evidence |
| `WS-OTP-FE` | `OTP admin settings worker` | `none` except merge-conflict reconciliation | checkpoint commit + focused Flutter tests + analyzer + web build proof | merged checkpoint + full local `Validate and Build Web` suite + Playwright admin mutation evidence |
| `WS-FED-BE` | `Agenda backend worker` | `none` except merge-conflict reconciliation | checkpoint commit + targeted Laravel tests + guardrails | merged checkpoint + full local `Laravel CI` suite + public payload/runtime proof |
| `WS-FED-READBACK` | `Agenda readback worker` | `none` except merge-conflict reconciliation | focused Flutter tests + analyzer + readback evidence packet | merged checkpoint + full local `Validate and Build Web` suite + readonly public profile runtime evidence |
| `WS-RECON` | `reconciliation validation worker` | `reconciliation-only` | merged worker checkpoints only | guards, CI-equivalent suites, runtime/browser/device evidence, docs evidence |

## Execution Waves
Waves are internal orchestrator control checkpoints, not routine user feedback gates. The orchestrator advances autonomously between waves and stops only for a mandatory decision, scope change, governing-TODO conflict, real blocker, or explicit validation waiver.

### Wave 0 - Preflight / Approval
- Confirm the two governing TODOs remain the sole authority for this slice.
- Freeze the exact worker/reconciliation branch and worktree creation recipe from `origin/main`; actual worktree creation starts only after `APROVADO`.
- Freeze the exact local reproduction commands for `Laravel CI` and `Validate and Build Web` in the checkpoint manifest so execution uses one canonical suite recipe.
- Gate to next wave: user approval (`APROVADO`) and successful branch/worktree preflight recipe freeze.

### Wave 1 - Fail-First / Invariant Freeze
- Freeze `PRH-OTP-DEP-01..03` in the checkpoint manifest before implementation diffs start:
  - exact safe-environment probe workflow,
  - chosen atomicity mechanism,
  - reviewer-access policy freeze.
- `WS-OTP-BE`: reproduce challenge/verify races, add fail-first coverage for atomic consume, invalid-attempt counting, challenge cooldown behavior, and reviewer-access endpoint semantics.
- `WS-FED-BE`: add fail-first feature coverage for capability-driven agenda resolution across canonical relationship modes, using `federacao` only as the repro case.
- `WS-OTP-FE`: confirm the admin settings UI contract against the hash-only settings payload and add fail-first tests for helper/hash behavior.
- Gate to next wave: failing tests/probes exist for the real gaps, `PRH-OTP-DEP-01..03` are frozen with evidence, and no unresolved contract ambiguity remains in reviewer-access settings semantics.

### Wave 2 - Implementation / Worker Checkpoints
- `WS-OTP-BE`: implement atomic consume/count/cooldown behavior and review-phone verify path.
- `WS-OTP-FE`: implement review-access settings UI/helper/hash flow.
- `WS-FED-BE`: correct agenda resolution for capability-enabled account profiles across canonical relationship modes without regressing `place_ref` venues.
- `WS-FED-READBACK`: establish Flutter readback validation for public profile agenda on the corrected payload and record the user-visible evidence explicitly.
- Gate to next wave: each worker checkpoint is architecture-clean and green on its worker-local validation set.

### Wave 3 - Reconciliation / CI-Equivalent / Runtime
- Merge worker checkpoints into the reconciliation branch.
- Run every in-scope row from the `CI-Equivalent Local Suite Matrix` on the merged state.
- Run Playwright mutation for OTP admin settings if Flutter/admin UI changed.
- Run ADB review-login flow on the integrated build for the reviewer-access path.
- Run readonly public profile runtime evidence for the `federacao` agenda route on the integrated state.
- Update TODO evidence and run the delivery guard.
- Gate to close execution: guards green, CI-equivalent matrix passed, runtime evidence collected, no unapproved deviation.

## Consolidated Validation Matrix
| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| `OTP backend invariants` | fail-first Laravel tests, green Laravel tests, probe logs for `5/10/20`, persisted invariant checks, guardrails | worker-local + reconciliation | `OTP backend worker` |
| `OTP admin settings UX` | Flutter focused tests, analyzer, rule matrix, web build, Playwright mutation, hash-only readback proof | worker-local + browser + reconciliation | `reconciliation validation worker` |
| `Reviewer login runtime` | ADB integration flow proving the allowlisted review phone/code enters the normal login surface and issues a real session, plus negative-path rejection for non-allowlisted phone, disabled user, and wrong-tenant misuse | device | `reconciliation validation worker` |
| `Federacao backend resolution` | fail-first Laravel tests, green Laravel tests, public payload projection proof, guardrails, and proof that capability-driven relationship resolution works across the frozen coverage set | worker-local + reconciliation | `Agenda backend worker` |
| `Federacao public readback` | Flutter public-profile tests if touched; readonly runtime proof showing upcoming events render; touched-surface audit proving no discovery/map/filter reclassification | browser or device (shared behavior lane) | `Agenda readback worker` |
| `Integrated repo validation` | full local `Laravel CI` suite plus full local `Validate and Build Web` suite for every touched repo | reconciliation | `reconciliation validation worker` |
| `Deterministic governance` | `orchestration_plan_completion_guard.py` then `orchestration_delivery_guard.py --require-approved` | local deterministic guard | `reconciliation validation worker` |

## Runtime Target Freeze
- **OTP concurrency probe target:** local reconciliation-branch Laravel runtime on `http://127.0.0.1:8000` using the same branch state as the repo-owned `laravel-app/.github/workflows/ci.yml` contract, with a dedicated review-phone fixture/settings payload. If this host changes, the exact replacement must be recorded in the checkpoint manifest before Wave 3 starts.
- **OTP admin/settings browser target:** reconciliation-branch web bundle published locally through the repository-approved web publish path, then exercised on the locally served browser domain used by Playwright (`belluga.space` / tenant-admin host pair frozen in the checkpoint manifest for the run).
- **OTP reviewer-login device target:** `guarappari` Flutter build from the reconciliation branch authenticated against a reconciliation-owned backend target carrying the same merged branch state as the Laravel and Flutter artifacts under test. Preferred order: (1) local/LAN reconciliation runtime, (2) explicitly deployed preview runtime from the reconciliation branch. Published production may be used only as a post-fix sanity check and does not count as acceptance evidence for this plan.
- **Federacao public-profile runtime target:** preferred target is a reconciliation-owned seeded non-production runtime carrying the merged branch state and the slug `confederacao-brasileira-do-desporto-universitario`. If that exact target is unavailable, the fallback must still be a reconciliation-owned local or preview runtime frozen in the checkpoint manifest before Wave 3. Existing production or unrelated mirror hosts may be used only for observational comparison and do not count as acceptance evidence for this plan.

## CI-Equivalent Local Suite Matrix
Every repo-owned CI suite/job that the touched repositories will execute for this wave must be run locally and passed on the reconciliation state before this plan can claim local implementation or readiness for promotion. Targeted reruns remain diagnostic only.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Applies To (`worker-local|reconciliation|pre-promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / Laravel CI` | Both TODOs change or validate backend runtime behavior inside `laravel-app`. | `bash -lc 'cd laravel-app && composer run architecture:guardrails && APP_ENV=testing APP_URL=http://nginx APP_HOST=nginx APP_KEY=base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk= APP_FAKER_LOCALE=pt_BR DB_CONNECTION_LANDLORD=landlord DB_CONNECTION_TENANTS=tenant DB_URI=mongodb://localhost:27017/landlord_test?replicaSet=rs0&directConnection=true DB_URI_LANDLORD=mongodb://localhost:27017/landlord_test?replicaSet=rs0&directConnection=true DB_URI_TENANTS=mongodb://localhost:27017/tenants_test?replicaSet=rs0&directConnection=true DB_DATABASE=landlord_test DB_DATABASE_LANDLORD=landlord_test DB_DATABASE_TENANTS=tenants_test php artisan test --fail-on-warning --display-warnings'` | `reconciliation` | `planned` | `laravel-app/.github/workflows/ci.yml` mirrored locally | Workflow-equivalent suite must be executed on the merged reconciliation branch, not replaced by targeted feature suites alone. |
| `flutter-app / Validate and Build Web` | OTP reviewer-access settings and `federacao` readback validation both freeze Flutter artifacts in the governing TODOs. | `bash -lc 'cd flutter-app && bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build'` | `reconciliation` | `planned` | `flutter-app/scripts/local_validate_and_build_web_ci_equivalent.sh` run log + `/tmp/flutter-web-ci-build` artifact | This row stays mandatory because both approved slices require Flutter validation artifacts, not only backend evidence. |

## Consolidated Delivery Evidence
| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |
| `none yet` | `Execution not started.` | `planned` | `n/a` | `n/a` |

## Checkpoint Manifest
- **Manifest path:** `foundation_documentation/artifacts/checkpoints/post-release-otp-linked-profile-hardening-2026-05-04.md`
- **Checkpoint status:** `wip_checkpoint`
- **Repositories pushed:** `none yet`
- **Excluded dirty surfaces:** `web-app generated output`, unrelated local dirty state outside this approved slice
- **Next branch lifecycle step:** start worker branches/worktrees after `APROVADO`

## Runtime Freshness Evidence
Required because this plan owns admin/browser evidence and device/runtime login evidence. No runtime freshness evidence exists yet because execution has not started.

## Risk / Conflict Controls
- Do not widen review access into a generic production bypass; only the allowlisted review phone path is in scope.
- Do not let the cleartext review-code helper become persisted settings data or client-readable payload.
- Do not let review-access settings leak through any client-readable settings endpoint or public environment/bootstrap payload.
- Do not “fix” `federacao` by broadening Flutter-side heuristics; agenda remains backend-owned canonical payload.
- Do not hardcode `federacao` or any other profile-type name into agenda resolution; the contract is capability-driven.
- Preserve venue `place_ref` agenda semantics while expanding capability-driven resolution across the canonical occurrence relationships frozen by the governing TODO.
- Preserve the out-of-scope boundary: this is not a discovery/map/filter rewrite.
- Do not claim progress from targeted tests alone; the full local `Laravel CI` and `Validate and Build Web` suites are mandatory before any delivery claim.
- The orchestrator remains reconciliation-only. Worker-owned implementation failures return to workers unless the change is pure merge or integration glue.

## Approval Request
- **Requested approval:** Reply `APROVADO` to authorize this orchestration plan.
- **Execution authorized by approval:** create the worker/reconciliation worktrees from `origin/main`, implement the OTP/reviewer-access and `federacao` agenda slices, run the CI-equivalent local suites, collect runtime/browser/device evidence, run the external critique loop again on the updated execution state when needed, and update the governing TODOs with real evidence.
- **Execution not authorized by approval:** promotion to `dev|stage|main`, rollback hardening, unrelated taxonomies/discovery work, or new auth/agenda scope outside these two TODOs.
- **Autonomy rule:** once approved, the orchestrator advances through waves without requesting feedback between waves unless a mandatory decision, blocker, or explicit waiver condition appears.

## Plan Completion Guard
- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/post-release-otp-linked-profile-hardening-orchestration-plan.md`
- **Required before approval/execution:** `Overall outcome: go`

## Delivery Guard
- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/post-release-otp-linked-profile-hardening-orchestration-plan.md --require-approved`
- **Required before local implementation or delivery completion claim:** `Overall outcome: go`
- **Blocks delivery when:** any traceability row lacks passed implementation/test evidence, a browser/device/runtime criterion lacks fresh evidence, the CI-equivalent local suite matrix is not fully passed, a named artifact was substituted without an approved spec deviation, or the orchestrator becomes the implementation owner.
