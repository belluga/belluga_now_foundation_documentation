# TODO (Fast Follow Bugfix): Main Tenant-Aware Queue Storage Guardrails

## Title
Fast Follow Bugfix: Main Tenant-Aware Queue Storage Guardrails

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
In `main`, invite push messages are still being created with `status = scheduled` and no matching `push_delivery_logs`.

Runtime evidence from production/main diagnosis:
- Push settings and Firebase credentials are configured for the Guarappari tenant.
- The latest backend `main` contains the previously delivered push fixes.
- The deploy workflow started `worker` on the same immutable GHCR Laravel runtime image as `app`.
- The stuck messages remain `scheduled`, which means `SendPushMessageJob` did not complete; if the job had run and failed normally, the push message would move to `failed` or `skipped`.
- The production database had queued `jobs` inside the tenant database instead of the shared/landlord queue database; 56 jobs were observed in the tenant queue collection.
- User-provided export of the 56 tenant-local `jobs` shows all payloads are `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob`, not push jobs.
- Current code defines the Map POI job as `Belluga\\MapPois\\Jobs\\UpsertMapPoiFromAccountProfileJob`; replaying the exported jobs on the current runtime produces `__PHP_Incomplete_Class` because they reference a legacy class name.
- User confirmed that after the manual cleanup/replay attempt, both active tenant `jobs` and landlord/shared `jobs` are empty; there is no `SendPushMessageJob` persisted in either location.
- Code inspection found a symptom-compatible path: `PushMessageService::create()` persists `push_messages` before calling `Bus::dispatch()`, while `InviteLifecycleSideEffectService` catches and swallows any `Throwable` from push side effects. Therefore an enqueue/dispatch exception can leave a `push_message` permanently `scheduled` with no queue job, no `push_delivery_logs`, and no visible API failure.
- Read-only main runtime probe on `2026-05-23`: worker and app are up; effective queue config is `queue.default=mongodb`, `queue.connections.mongodb.connection=mongodb`, shared queue database `landlord`, tenant connection database `tenant_boora`; `shared_jobs_count=0`, `tenant_jobs_count=0`, `failed_jobs_count=56`.
- The 56 `failed_jobs` are all `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob`, failed at `2026-05-23 12:34:08-12:34:10` with `Job is incomplete class`; no failed push jobs were found.
- Tenant push state on the same runtime probe: `push_messages` has `11 scheduled`, `2 failed`, `push_delivery_logs_count=0`; latest scheduled invite messages have `delivery.scheduled_at=null`, so they are not delayed future sends.
- Deployed Docker image `org.opencontainers.image.revision=2b8d9c0832542a1ad93212022c1140769cd0e380` started app at `2026-05-23T01:53:21Z` and worker at `2026-05-23T01:53:28Z`.
- All `scheduled` push messages without terminal metadata were created before that deployment; the newest stale scheduled push was created at `2026-05-22T19:49:48Z`.
- Two invite push messages created after the current `main` deployment (`2026-05-23T02:51:16Z` and `2026-05-23T02:53:03Z`) both left `scheduled` and became `failed` with `delivery.last_terminal_state.reason=delivery_failed`, proving the previously promoted hardening is active in current `main`.
- Targeted production probe for recipient `+5527*****9802` found one registered user with two active Android push devices. The post-deploy direct invite at `2026-05-23T02:51:16Z` created `push_message=6a111624e7123401cf0416eb`, the worker processed `Belluga\\PushHandler\\Jobs\\SendPushMessageJob`, and the message became `failed/delivery_failed` with `requested_units=2` and `accepted_count=0`.
- FCM credential probe on `2026-05-23` found the tenant credential configured for project `guarappari`, but decrypted `private_key` contains literal escaped `\\n` and no actual line breaks. `openssl_pkey_get_private()` returns false, OAuth is never attempted, `FcmHttpV1Client::bootstrapTransport()` returns `[null, null]`, and delivery returns `accepted_count=0,responses=[]`, explaining why no `push_delivery_logs` are created.
- After the operator attempted to copy the dev credential into production, the production `push_credentials.private_key` no longer decrypts with the production `APP_KEY` (`DecryptException: The MAC is invalid`). Raw read shows one credential row with ciphertext-like `private_key` data, but `updated_at` did not change; this is consistent with copying an already-encrypted value from another environment or otherwise bypassing the production upsert/encryption path.
- After the operator pasted the raw JSON/service-account `private_key` value through the production admin text editor, the credential decrypted successfully again, but the stored value still contains literal escaped `\\n` and no actual newline characters. The runtime probe reports `private_key_has_begin=true`, `private_key_has_end=true`, `private_key_has_escaped_newlines=true`, `private_key_has_actual_newlines=false`, `openssl_key_valid=false`, and OAuth remains unattempted.

The confirmed production data issue is broader than push:
- Historical tenant-local queue storage occurred for tenant-aware jobs.
- The 56 exported jobs are stale legacy Map POI projection jobs and cannot prove that current `SendPushMessageJob` dispatch is misrouted.
- Push currently has no persisted queue job in tenant or landlord storage, so its primary diagnosis moves to enqueue/dispatch failure or silent side-effect swallowing rather than queue storage location.

The likely architectural risk is queue connection misrouting:
- `config/queue.php` currently resolves `MONGODB_QUEUE_CONNECTION` with fallback to `DB_CONNECTION`.
- In tenant runtime, the Spatie tenant switch changes the active/default Mongo connection to the tenant connection.
- A queue connection that resolves to the tenant connection stores queue documents in the tenant `jobs` collection.
- The worker process consumes its own configured queue connection and does not scan every tenant database, so tenant-local queue documents become orphaned.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `main-tenant-aware-queue-storage-guardrails`
- **Why this is the right current slice:** this is one bounded production risk: Laravel tenant-aware jobs must never be physically persisted in tenant queue storage. Push delivery remains an in-scope regression proof, but the 56 observed jobs are stale Map POI jobs, not push jobs.
- **Direct-to-TODO rationale:** the failure mode, code surface, and runtime evidence are already concrete; no feature decomposition is needed.

## Contract Boundary
- This TODO defines **WHAT** must be corrected and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and same approval conversation.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `diagnostic-complete`, `current-main-push-pipeline-healthy-for-scheduled-exit`, `historical-backlog`
- **Next exact step:** decide cleanup/reconciliation handling for historical pre-fix `push_messages` still stuck in `scheduled`, then split or keep the broader queue-storage guardrail hardening as a follow-up implementation slice.

## Scope
- [x] Diagnose current `main` runtime queue topology and distinguish historical tenant-local backlog from current job dispatch behavior.
- [x] Determine whether current `main` still reproduces direct invite push messages stuck in `scheduled` after the latest deployment.
- [ ] Define explicit cleanup/reconciliation policy for historical pre-fix `push_messages` still in `scheduled`.
- [x] Validate the operator-normalized production FCM private key with `openssl_pkey_get_private()` and OAuth bootstrap before sending another real invite.
- [x] Re-run the real direct-invite push flow for the approved recipient after credential normalization and record `push_message`, `push_delivery_logs`, and device-arrival evidence.
- [ ] Decide whether to keep the shared-queue storage guardrail in this fast-follow TODO or split it into a separate hardening TODO, because current push failure is no longer reproduced post-deploy.
- [ ] Diagnose and fix the push enqueue failure path where `push_messages` can remain `scheduled` when `Bus::dispatch()` fails before a queue document is persisted, only if new post-deploy evidence reproduces that path.
- [ ] Fix Laravel queue configuration so Mongo queue jobs are stored in a stable shared queue connection, not in the current tenant database.
- [ ] Fail closed when `QUEUE_CONNECTION=mongodb` resolves to a tenant queue connection.
- [ ] Add Laravel guardrails that prohibit queue storage on the tenant database for tenant-aware jobs.
- [ ] Update `.env.example` so the canonical Mongo queue connection points to the shared/landlord queue surface.
- [ ] Add regression coverage proving tenant-current dispatch of representative tenant-aware jobs writes queue documents to the shared queue collection and not the tenant queue collection.
- [ ] Add regression coverage proving direct invite push enqueue either persists `SendPushMessageJob` in shared queue storage or marks the authored `push_message` terminal `failed` with an explicit dispatch/enqueue failure reason.
- [ ] Update the existing invite push queue runtime test so it no longer masks this bug by forcing a non-production-like queue connection.
- [ ] Provide an explicit operational handling plan for already-orphaned tenant `jobs` in `main`; do not replay stale legacy serialized jobs as part of the code fix.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold.
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app:<to be created>`, `foundation_documentation:<current branch>`
- **Promotion lane path:** `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `main`

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `laravel queue topology fix` | `pending` | `pending` | `pending` | `pending` | `pending` |
| `foundation TODO/evidence` | `pending` | `n/a` | `n/a` | `pending` | `pending` |

## Out of Scope
- [ ] Replacing Mongo queue with Redis/SQS.
- [ ] Reworking Spatie tenant-aware job semantics.
- [ ] Changing the push message authoring contract or FCM delivery payload.
- [ ] Restoring legacy `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob` aliases only to process stale queue data.
- [ ] Automatically replaying 56 existing tenant-local jobs without a separate explicit production data decision.
- [ ] Broad queue observability redesign beyond the guardrails needed to prevent this recurrence.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** focused queue config hardening, `.env.example` correction, queue topology tests, and a read-only/approval-gated production cleanup plan for orphaned jobs.
- **Must update or split the TODO:** any queue backend migration, broad worker orchestration redesign, data mutation that replays production pushes, or unrelated push content/routing change.

## Definition of Done
- [ ] Mongo queue configuration has a stable shared queue connection default that cannot follow the current tenant database.
- [ ] `QUEUE_CONNECTION=mongodb` fails closed when `MONGODB_QUEUE_CONNECTION` is `tenant` or the configured tenant database connection name.
- [ ] A Laravel guardrail test proves Mongo queue storage cannot target the tenant connection.
- [ ] A regression test proves tenant-current dispatch of representative tenant-aware jobs writes to landlord/shared queue storage and leaves tenant `jobs` empty.
- [ ] A regression test proves direct invite push enqueue either persists `SendPushMessageJob` in shared queue storage or marks the authored `push_message` terminal `failed` with an explicit dispatch/enqueue failure reason.
- [ ] The invite push queue runtime test processes the job through production-like shared queue topology and materializes `push_delivery_logs`.
- [ ] Existing orphaned production tenant jobs are classified as stale legacy Map POI projection jobs, with a non-replay-by-default handling plan.

## Validation Steps
- [ ] Run autonomous read-only runtime/log diagnostics for queue config, worker state, queued job class distribution, stuck `push_messages` correlation, and swallowed dispatch/enqueue exceptions before changing code.
- [ ] Run the focused Laravel config guardrail tests.
- [ ] Run the focused invite push queue runtime test.
- [ ] Run the Laravel CI-equivalent safe runner before claiming `Local-Implemented`.
- [ ] If production data cleanup is requested, run it only after explicit approval and record before/after counts.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `Mongo queue configuration has a stable shared queue connection default that cannot follow the current tenant database.` | `code+test` | `planned` | `local` | `planned` | Must prove queue connection does not depend on tenant-current default connection. |
| `DOD-02` | `Definition of Done` | `QUEUE_CONNECTION=mongodb fails closed when MONGODB_QUEUE_CONNECTION is tenant or the configured tenant database connection name.` | `test` | `planned` | `local` | `planned` | Prevents recurrence from env drift. |
| `DOD-03` | `Definition of Done` | `A Laravel guardrail test proves Mongo queue storage cannot target the tenant connection.` | `test` | `planned` | `local Laravel` | `planned` | This should fail closed at config/bootstrap level, not rely on production observation. |
| `DOD-04` | `Definition of Done` | `A regression test proves tenant-current dispatch of representative tenant-aware jobs writes to landlord/shared queue storage and leaves tenant jobs empty.` | `test` | `planned` | `local Laravel` | `planned` | General guardrail for all tenant-aware jobs, not only push. |
| `DOD-05` | `Definition of Done` | `A regression test proves direct invite push enqueue either persists SendPushMessageJob in shared queue storage or marks the authored push_message terminal failed with an explicit dispatch/enqueue failure reason.` | `test+diagnostic` | `planned` | `local Laravel/main runtime` | `planned` | Push symptom is no persisted job anywhere, not tenant-local job storage. |
| `DOD-06` | `Definition of Done` | `The invite push queue runtime test processes the job through production-like shared queue topology and materializes push_delivery_logs.` | `test` | `planned` | `local Laravel` | `planned` | Closes any false-green in the previous E2E if push remains queue-topology related. |
| `DOD-07` | `Definition of Done` | `Existing orphaned production tenant jobs are classified as stale legacy Map POI projection jobs, with a non-replay-by-default handling plan.` | `review` | `planned` | `main runtime` | `planned` | Production mutation remains approval-gated; current projection recovery should use current rebuild tooling if needed. |
| `VAL-01` | `Validation Steps` | `Run autonomous read-only runtime/log diagnostics for queue config, worker state, queued job class distribution, stuck push_messages correlation, and swallowed dispatch/enqueue exceptions before changing code.` | `runtime+review` | `planned` | `main runtime/local fallback` | `planned` | Must consume the known empty-jobs fact and move to dispatch/enqueue failure evidence. |
| `VAL-02` | `Validation Steps` | `Run the focused Laravel config guardrail tests.` | `test` | `planned` | `local Laravel safe runner` | `planned` | Targeted diagnostic gate. |
| `VAL-03` | `Validation Steps` | `Run the focused invite push queue runtime test.` | `test` | `planned` | `local Laravel safe runner` | `planned` | Targeted diagnostic gate. |
| `VAL-04` | `Validation Steps` | `Run the Laravel CI-equivalent safe runner before claiming Local-Implemented.` | `test` | `planned` | `local Laravel safe runner` | `planned` | Required full local gate. |
| `VAL-05` | `Validation Steps` | `If production data cleanup is requested, run it only after explicit approval and record before/after counts.` | `runtime` | `planned` | `main runtime` | `planned` | No production data mutation without explicit approval. |

## Main Runtime Diagnostic Evidence
- [x] `2026-05-23` SSH read-only access using local main CI key succeeded; `belluga_now_docker-worker-1` and `belluga_now_docker-app-1` are up.
- [x] Effective queue config: `queue.default=mongodb`; Mongo queue storage resolves to shared database `landlord`; tenant database for current tenant resolves to `tenant_boora`.
- [x] Active queue storage counts: shared/landlord `jobs=0`; tenant `jobs=0`.
- [x] Failed job storage count: `failed_jobs=56`, all legacy `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob`; no push failed jobs.
- [x] Push storage counts: `push_messages scheduled=11 failed=2`; `push_delivery_logs=0`.
- [x] Latest scheduled invite push messages have no `delivery.scheduled_at`, so they are immediate sends stuck in scheduled state.
- [x] Current `main` deployment timestamp: app `2026-05-23T01:53:21Z`, worker `2026-05-23T01:53:28Z`.
- [x] All remaining `scheduled` push messages are pre-deploy historical data; no post-deploy invite push remained scheduled.
- [x] Post-deploy invite pushes now become terminal `failed/delivery_failed` when provider accepts zero targets, which matches the previous direct-invite push hardening TODO.
- [x] Recipient-specific post-deploy proof: user for `+5527*****9802` has two active devices; invite push job ran and failed before FCM request because credential bootstrap produced no access token.
- [x] Current conclusion: the previously promoted direct-invite push hardening reached `main` and is active. The current no-arrival defect is FCM credential private-key newline normalization in the backend/runtime, plus historical scheduled backlog cleanup.
- [x] After manual production edit on `2026-05-23`, the stored key still has mixed formatting: exact `BEGIN/END` lines are present, but the base64 body still contains `25` literal escaped `\\n`; `openssl_pkey_get_private()` remains false.
- [x] Read-only in-memory normalization proof on the same value (`str_replace("\\n", "\n")`) makes the key OpenSSL-valid, leaves `0` escaped newlines, and yields `27` actual newlines. The remaining blocker is the persisted credential value, not the Firebase key material itself.
- [x] After the operator saved the key with real PEM line breaks, production credential probe reported `escaped_newline_count=0`, `actual_newline_count=27`, exact `BEGIN/END` lines, and `openssl_key_valid=true`.
- [x] OAuth bootstrap probe against `https://oauth2.googleapis.com/token` succeeded with HTTP `200`, Bearer token type, and access token present; no token value was logged.
- [x] Real production invite validation created invite `6a11ace5e29637738b077665` for approved recipient `+5527*****9823` and event occurrence `6a0e05da8b1d60418204bec8`; authored push message `6a11ace5e29637738b077668`.
- [x] Worker processed push message `6a11ace5e29637738b077668` to `sent` at `2026-05-23T13:34:31.486000Z`, with `accepted_count=1`, `sent_count=1`, and three delivery logs: two `NOT_FOUND` stale tokens and one FCM `accepted` provider message id `projects/guarappari/messages/0:1779543271267120%fcca9f36fcca9f36`.
- [x] Operator confirmed the push arrived on the device after the accepted FCM response.
- [x] Follow-up real APP invite to recipient `+5527*****9802` created invite `6a11addb26fc614b1d0fc558` at `2026-05-23T13:38:35.241000Z`; authored `invite_received` push `6a11addb26fc614b1d0fc55b`.
- [x] Push `6a11addb26fc614b1d0fc55b` was processed to `sent` at `2026-05-23T13:38:37.034000Z` with `accepted_count=1`; delivery logs show one stale token failed `NOT_FOUND` and one current token accepted by FCM as `projects/guarappari/messages/0:1779543516794887%fcca9f36fcca9f36`.
- [x] Recipient `+5527*****9802` has one current active Android device token last registered at `2026-05-23T13:39:59.090000Z`; the stale Android token was invalidated at `2026-05-23T13:38:37.004000Z` after FCM `NOT_FOUND`.
- [x] No `PushMessageAction` was recorded for recipient push `6a11addb26fc614b1d0fc55b`; therefore backend/FCM accepted the push, but the receiver app did not report `delivered/opened/clicked` for this notification.
- [x] The same invite became `accepted` by `2026-05-23T13:40:37.634000Z` and generated `invite_accepted` push `6a11ae558b82956bd002d32d` to inviter `69fe687a877199ba7d0a095e`; that push was sent, accepted by FCM, and recorded a `delivered` action from device `a2488388-0f5d-4ce0-abc6-f8ef0f66c8a1`.
- [x] Current refined conclusion: production backend queue, credential bootstrap, FCM send, stale-token invalidation, and inviter-side delivery action are healthy. The unresolved symptom for `+5527*****9802` is receiver client/device notification display or receiver-side push callback telemetry after FCM acceptance.

## External Dependency Readiness
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `main SSH/runtime access` | Needed only for live worker/queue diagnostics and any orphaned-job cleanup. | `healthy` | `2026-05-23` | Local SSH to `ubuntu@201.54.0.251` succeeded with `~/.ssh/belluga_main_ci`; read-only Docker/config/log probes completed. | Continue read-only diagnostics locally; production data mutation remains explicit-approval only. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `operational-devops`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `operational-devops` | Production orphaned-job cleanup or deploy/promotion follow-through may require runtime commands. | `main Mongo jobs`, `push_messages`, `worker runtime` | `planned` |
| `operational-coder` | `assurance-tester-quality` | Tests changed and prior tests were false-green for this topology. | `Laravel queue/invite push tests` | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated planning review`
- **Why this level:** the code change should be localized to Laravel queue configuration, `.env.example`, and focused tests; there is runtime risk, but no new feature, API, schema, UI, or queue backend migration.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `invite_and_social_loop_module.md`: push audience topology / shared push pipeline notes.
- **Module decision consolidation targets (required):**
  - Record the stable rule that tenant-aware jobs must use shared queue storage while tenant context lives in job payload metadata.

## Decision Pending
- [x] `D-04` Production orphaned jobs: user performed manual recovery by exporting/importing tenant queue jobs into the landlord/shared queue collection and dropping the tenant queue collection. Imported jobs failed as `__PHP_Incomplete_Class` because they are legacy `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob` payloads. Code fix must still prevent recurrence, but these jobs are not push evidence.

## Decisions
- [x] `D-01` Tenant-aware jobs must be physically stored in shared queue storage; tenant identity belongs in the job payload/context, not in the queue database selection.
- [x] `D-02` `MONGODB_QUEUE_CONNECTION=tenant` is invalid for this app and must fail closed.
- [x] `D-03` The invite push runtime test must verify the physical queue location, not only that `queue:work` eventually processes a job under a test-only connection.
- [x] `D-04` Existing production orphaned jobs were manually moved by the operator from tenant queue storage into landlord/shared queue storage; no automated replay or data migration will be implemented in this code slice.
- [x] `D-05` Laravel must carry an executable guardrail against tenant queue storage, so env drift cannot silently recreate this failure.
- [x] `D-06` The 56 exported tenant-local jobs are stale legacy Map POI projection jobs, not push jobs. They should not be used as proof that current push dispatch writes to tenant storage.
- [x] `D-07` Map POI projection recovery, if needed, should use current projection rebuild tooling rather than replaying legacy serialized queue payloads.
- [x] `D-08` Current push diagnosis must treat empty tenant/shared `jobs` as evidence of missing enqueue/persisted dispatch, not queue-location misrouting.

## Module Decision Baseline Snapshot
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `invite_and_social_loop_module.md#push-audience-topology` | Shared push pipeline owns queueing/batching/provider delivery. | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` |
| `flutter_client_experience_module.md#otp-delivery` | Backend queued dispatch owns outbound provider delivery; clients do not call provider directly. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Tenant-aware jobs must be physically stored in shared queue storage; tenant identity belongs in the job payload/context, not in the queue database selection.
- [x] `D-02` `MONGODB_QUEUE_CONNECTION=tenant` is invalid for this app and must fail closed.
- [x] `D-03` The invite push runtime test must verify the physical queue location, not only that `queue:work` eventually processes a job under a test-only connection.
- [x] `D-04` Production orphaned jobs were recovered manually by the operator through export/import into landlord/shared queue storage and tenant queue collection drop; imported stale legacy Map POI jobs failed on current runtime and implementation must not add automatic production job replay.
- [x] `D-05` Laravel must carry an executable guardrail against tenant queue storage, so env drift cannot silently recreate this failure.
- [x] `D-06` Push scheduled-state diagnosis must be based on current `SendPushMessageJob` evidence, not on the stale Map POI queue export.
- [x] `D-07` A push side-effect failure must not be allowed to leave an authored `push_message` indefinitely `scheduled` without a queued job or terminal error metadata.

## Questions To Close
- [x] Confirm `D-04`: user chose manual queue import into landlord/shared storage and tenant queue collection drop.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The worker is not intended to scan tenant databases for queue jobs. | `docker-compose.yml` has one worker service; `run_queue_worker.sh` runs one `queue:work`; Spatie tenant-aware jobs carry tenant context in payload. | A broader worker-per-tenant architecture would be required. | High | Keep as Assumption |
| `A-02` | The 56 tenant-local jobs are historical/stale Map POI queue data and do not by themselves prove current push misrouting. | Every exported payload references `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob`; current code uses `Belluga\\MapPois\\Jobs\\UpsertMapPoiFromAccountProfileJob`; import into landlord failed as `__PHP_Incomplete_Class`. | If new push jobs are also found tenant-local, push queue misrouting is confirmed independently. | High | Keep as Assumption |
| `A-03` | The existing invite queue test was false-green for this production topology. | Test currently forces `queue.connections.mongodb.connection = mongodb` and counts `mongodb.jobs`, not tenant/landlord separation. | The bug might still be covered elsewhere, but current evidence did not find it. | High | Keep as Assumption |
| `A-04` | Current `main` may still have a queue topology bug even if the 56 exported jobs are stale. | `config/queue.php` fallback can follow `DB_CONNECTION`; existing tests only prove `TenantAware` marker, not physical queue storage. | If runtime config already points to shared storage, implementation may be guardrail-only plus separate push diagnosis. | Medium | Keep as Assumption |
| `A-05` | Current stuck invite push messages were authored, but enqueue failed before a queue document was persisted and the exception was swallowed by invite side-effect handling. | Tenant and landlord `jobs` are empty; `push_messages` remain `scheduled`; `PushMessageService::create()` persists before dispatch; `InviteLifecycleSideEffectService` catches `Throwable` without recording failure. | If request/runtime logs show successful dispatch or immediate worker deletion, worker/runtime diagnosis resumes. | High | Keep as Assumption |

## Execution Plan

### Touched Surfaces
- `laravel-app/config/queue.php`
- `laravel-app/.env.example`
- `laravel-app/tests/Unit/Config/QueueAndLoggingConfigGuardrailTest.php`
- `laravel-app/tests/Feature/Invites/InvitesFlowTest.php`
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-tenant-queue-misrouting-push-jobs.md`

### Ordered Steps
1. Record the already-known runtime fact that shared/landlord and tenant `jobs` are empty and no `SendPushMessageJob` exists for stuck push messages.
2. Inspect request/runtime logs around direct invite creation for swallowed queue dispatch exceptions, using app logs before adding code.
3. Add fail-first tests proving a dispatch/enqueue exception cannot leave an authored direct-invite `push_message` indefinitely `scheduled` without queued job or terminal failure metadata.
4. Add fail-first config tests for Mongo queue default/shared connection and tenant-connection fail-closed behavior.
5. Add a Laravel guardrail assertion that `QUEUE_CONNECTION=mongodb` cannot use `tenant` or `DB_CONNECTION_TENANTS` as its storage connection.
6. Add a generic tenant-aware queue storage regression test using representative jobs such as current Map POI and push jobs.
7. Change `PushMessageService` or the invite push side-effect boundary so dispatch enqueue failure records explicit terminal failure state for the authored message and does not silently masquerade as scheduled delivery.
8. Change `config/queue.php` so Mongo queue uses explicit shared/landlord connection by default and rejects tenant queue connections.
9. Update `.env.example` to advertise the shared/landlord queue connection.
10. Re-run focused tests, then the full Laravel safe CI-equivalent.
11. Produce production handling commands/plan for the stale legacy Map POI jobs and current projection rebuild, without executing mutation unless separately approved.

### Autonomous Diagnostic Checklist
- [x] Inspect effective runtime queue config from the deployed Laravel container: `queue.default`, `queue.connections.mongodb.connection`, DB default connection, landlord connection, tenant connection, and resolved database names only.
- [x] Inspect worker runtime state and recent worker logs to verify the worker is alive, using the same image digest as `app`, and consuming `otp,default`.
- [x] Count and classify `jobs` documents in shared/landlord storage by `payload.displayName`.
- [x] Count and classify `jobs` documents in the active tenant storage by `payload.displayName`.
- [x] Search both shared/landlord and tenant `jobs` for `Belluga\\PushHandler\\Jobs\\SendPushMessageJob`.
- [x] Search both shared/landlord and tenant `jobs` for legacy `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob` and current `Belluga\\MapPois\\Jobs\\UpsertMapPoiFromAccountProfileJob`.
- [x] Correlate stuck `push_messages` by `_id`, `status`, `created_at`, `delivery.scheduled_at`, and any dispatch metadata against matching queued job payloads.
- [x] If no `SendPushMessageJob` exists anywhere for stuck push messages, reclassify push as enqueue/dispatch-path failure before changing queue topology.
- [ ] If `SendPushMessageJob` exists in tenant storage, confirm current push queue misrouting and prioritize shared queue storage fix.
- [ ] If `SendPushMessageJob` exists in shared storage but remains unprocessed, reclassify push as worker/runtime consumption failure.
- [ ] If `SendPushMessageJob` was processed but no delivery logs exist, reclassify push as job handler/provider path failure.
- [x] Record diagnostic evidence in this TODO before implementation so the fix is based on current runtime facts, not stale queue exports.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the bug is deterministic and the current tests were false-green.
- **Fail-first target(s):**
  - `QueueAndLoggingConfigGuardrailTest` must fail before the config change when `MONGODB_QUEUE_CONNECTION=tenant`.
  - `QueueAndLoggingConfigGuardrailTest` must fail before the config change when `MONGODB_QUEUE_CONNECTION` equals `DB_CONNECTION_TENANTS`.
  - A new focused direct-invite push test must fail before the fix by simulating `Bus::dispatch()` failure after `PushMessage::create()` and asserting the message does not remain `scheduled`.
  - A new focused tenant-aware queue storage test must fail before the fix by detecting a tenant-local `jobs` document and no landlord/shared queue document for representative tenant-aware jobs.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `tenant-aware queue storage` | Backend background work can become orphaned if storage follows tenant DB. | `shared-android-web` | `backend integration` | `yes` | `yes, local Mongo tenant data` | Laravel feature/unit tests with real Mongo queue, asserting landlord/shared `jobs` receives queued tenant-aware jobs and tenant `jobs` remains empty. | This covers the broad escaped failure independently of push. |
| `push invite delivery job leaves scheduled state` | Backend mutation affects user-visible push delivery but no UI contract changes. | `shared-android-web` | `backend integration` | `yes` | `yes, local Mongo tenant data` | Laravel feature test simulating dispatch/enqueue failure and proving authored messages become terminal failed instead of remaining scheduled with no job. | Device push E2E already proved FCM path locally; this slice must not infer push cause from stale Map POI jobs. |
| `production orphaned jobs handling` | Production data cleanup can affect projections if stale jobs represented pending Map POI rebuilds. | `n/a` | `main runtime` | `yes` | `yes` | User reported manual import into landlord/shared queue and tenant queue collection drop; imported jobs failed as legacy class names. Recovery path is current `map-pois:rebuild`, not stale queue replay. | Not implemented as automatic code behavior. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / focused config + invite queue tests` | Directly proves root cause and fix. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Config/QueueAndLoggingConfigGuardrailTest.php tests/Feature/Invites/InvitesFlowTest.php --filter 'Queue|send_invite_queue_runtime'` | `Local-Implemented` | `planned` | `planned` | Filter may be adjusted to exact PHPUnit names after implementation. |
| `laravel-app / CI Equivalent` | Required before local delivery/promotion. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | `Local-Implemented` | `planned` | `planned` | Full safe runner. |

### Runtime / Rollout Notes
- Runtime env must use `QUEUE_CONNECTION=mongodb` and `MONGODB_QUEUE_CONNECTION=landlord` or another approved shared queue connection.
- Existing tenant-local queue documents should be treated as orphaned stale legacy Map POI operational data. Replaying them is not viable on current runtime because the serialized class no longer exists; current projection recovery should use `php artisan map-pois:rebuild ...` after an explicit production data decision.

## Plan Review Gate

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
  - **Evidence:** `laravel-app/config/queue.php` resolves `$mongodbQueueConnection = env('MONGODB_QUEUE_CONNECTION', $databaseConnection)`.
  - **Why it matters now:** queue storage can follow tenant context, orphaning jobs.
  - **Option A (Recommended):** default Mongo queue storage to the landlord/shared connection and fail closed for tenant connection.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `runtime`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** teach worker to scan/process every tenant queue.
    - **Effort:** `high`
    - **Risk:** `high`
    - **Blast radius:** `runtime`
    - **Maintenance burden:** `high`
    - **Performance impact:** `regresses`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** only clean current tenant jobs manually.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `runtime`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** choose Option A.

### Failure Modes & Edge Cases
- [x] Production env missing `MONGODB_QUEUE_CONNECTION`: must still default to shared/landlord, not tenant.
- [x] Production env explicitly setting `MONGODB_QUEUE_CONNECTION=tenant`: must fail closed so bad deploy/runtime is visible.
- [x] Existing tenant-local jobs: must not be silently replayed after fix.

### Residual Unknowns / Risks
- [ ] Exact mapping from the 56 tenant-local job payloads to `push_messages` must be inspected before any production cleanup mutation.
- [ ] Local SSH access to `main` is currently unavailable; runtime cleanup may need user-run command or GitHub-mediated execution.
