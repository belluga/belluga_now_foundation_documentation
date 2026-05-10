# Orchestration Execution Plan: Laravel Rebase Refresh + Push Audience Foundation + Invite Push Delivery

## Artifact Identity
- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Approved / In Execution`
- **Created:** `2026-05-09`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`

## Authority Boundary
- Governing TODOs define **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the orchestrator sequences the backend refresh TODO, the structural push-audience TODO, and the dependent invite/push/share-metadata TODO.
- If this plan conflicts with either governing TODO, stop and update the TODO or this plan before execution.
- This plan does not create a new backlog authority or a third tactical TODO.
- Requirement wording in governing TODOs is literal. Replacing a named endpoint, runtime lane, schema term, metadata surface, or navigation flow requires an approved row in the Spec Deviation Ledger before execution or delivery can proceed.
- The invite/push TODO may not execute on top of the stale `laravel-app/reconcile/post-release-rule-related-auth-identity-20260506` branch. A fresh backend base from the promoted lane is required first.
- If every governing TODO, validation matrix, CI-equivalent suite, and runtime/browser/device criterion closes green in this orchestration, promotion through `stage` is pre-authorized and must follow the canonical promotion-lane skill rather than being deferred to memory.

## Governing TODO Set
| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `RB-LARAVEL-01` | `foundation_documentation/todos/active/fast_follow_required/TODO-rebase-laravel-auth-hardening-onto-promoted-lane.md` | `blocker` | `can start after plan approval` |
| `STR-PUSH-AUD-01` | `foundation_documentation/todos/active/fast_follow_required/TODO-structural-push-query-based-audience-materialization.md` | `same-promotion blocker` | `blocked by RB-LARAVEL-01 completion` |
| `FF-INVITE-01` | `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-push-live-reflection-and-share-metadata.md` | `dependent` | `can start after RB-LARAVEL-01; must consume STR-PUSH-AUD-01 before invite-push delivery can claim closure or promotion-readiness` |

## Execution Snapshot (2026-05-10)

- Wave 0 approval is consumed.
- Wave 1 backend refresh is complete on `laravel-app/reconcile/fast-follow-laravel-rebase-and-invite-push-20260509`.
- Wave 2 push audience foundation is implemented locally on the same reconcile branch; focused structural suites, exact-lookup/query-topology audit, and architecture guard are green.
- The remaining Wave 2 blocker before commit/push is the isolated Laravel CI-equivalent suite still running on unique Mongo databases.
- Waves 3-5 remain pending and are intentionally out of the current commit scope.

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `RB-D01` | `D-01` The stale `laravel-app/reconcile/post-release-rule-related-auth-identity-20260506` branch must not be reused directly as the implementation base for new invite/push work. | `WS-00` | `laravel reconcile base branch` | fresh branch creation from promoted lane | branch topology audit | `n/a` | `planned` |
| `RB-D02` | `D-02` The fresh backend base must start from the latest promoted Laravel lane and replay only the still-required unpublished RR-AUTH/runtime deltas. | `WS-00` | `replay ledger` | promoted-vs-unpublished delta classification | targeted replay proof | `n/a` | `planned` |
| `RB-D03` | `D-03` Changes that are already promoted in `main`/`stage` must be classified as promoted drift and must not be reauthored locally. | `WS-00` | `promoted drift ledger` | commit/diff classification | audit evidence | `n/a` | `planned` |
| `RB-D04` | `D-04` The refreshed backend base must preserve accepted RR-AUTH hardening plus the Mongo-first cache/runtime compatibility fix needed for truthful local validation. | `WS-00` | `auth/runtime baseline` | replayed backend base | targeted auth/runtime tests | local runtime proof if needed | `planned` |
| `RB-D05` | `D-05` Completion of this TODO is the backend prerequisite for the dependent invite/push/share-metadata TODO. | `WS-00` | `handoff checkpoint` | refreshed base + docs handoff | handoff audit | `n/a` | `planned` |
| `RB-AC01` | `Acceptance Criteria` The Laravel drift between the stale reconcile branch and the promoted lane is explicitly classified into `already-promoted` vs `must-replay`. | `WS-00` | `drift ledger` | written drift inventory | audit proof | `n/a` | `planned` |
| `RB-AC02` | `Acceptance Criteria` A fresh backend branch derived from the current promoted lane contains the still-required RR-AUTH/runtime fixes. | `WS-00` | `fresh backend branch` | refreshed branch with replayed fixes | targeted replay tests | `n/a` | `planned` |
| `RB-AC03` | `Acceptance Criteria` Already-promoted runtime/worker fixes are absorbed by base selection/rebase, not by duplicate local reauthoring. | `WS-00` | `promoted fix absorption` | branch topology + diff proof | audit evidence | `n/a` | `planned` |
| `RB-AC04` | `Acceptance Criteria` The refreshed backend base passes the Laravel CI-equivalent suite without regressing accepted RR-AUTH behavior. | `WS-00` | `Laravel CI-equivalent` | refreshed backend base | full Laravel suite | `n/a` | `planned` |
| `RB-AC05` | `Acceptance Criteria` The dependent invite/push TODO can start from the refreshed backend base instead of the stale reconcile branch. | `WS-00` | `dependency handoff` | refreshed branch recorded in docs/plan | handoff evidence | `n/a` | `planned` |
| `RB-V01` | `Validation Steps` `Audit lane: compare the stale reconcile branch against the current promoted Laravel lane and record the promoted-vs-unpublished drift ledger.` | `WS-00` | `drift ledger` | diff inventory | audit proof | `n/a` | `planned` |
| `RB-V02` | `Validation Steps` `Replay lane: prove the Mongo-first cache/runtime compatibility fix and required RR-AUTH hardening both exist on the refreshed backend base.` | `WS-00` | `runtime compatibility baseline` | replayed branch diff | targeted auth/runtime tests | local runtime probe if needed | `planned` |
| `RB-V03` | `Validation Steps` `Laravel suite lane: run the full Laravel CI-equivalent suite on the refreshed backend base.` | `WS-00` | `Laravel CI-equivalent` | refreshed branch | full Laravel suite | `n/a` | `planned` |
| `RB-V04` | `Validation Steps` `Handoff lane: record the branch/commit that the dependent invite/push TODO must use as its backend source branch.` | `WS-00` | `handoff checkpoint` | docs + checkpoint update | handoff audit | `n/a` | `planned` |
| `STR-D01` | `D-01/D-02` Targeted push audiences must be resolved by query/materialization, and `push_devices` becomes the authoritative push-delivery store instead of embedded `AccountUser.devices`. | `WS-01A` | `push device authority topology` | storage cutover + query-first recipient resolution | targeted push tests | `n/a` | `planned` |
| `STR-D02` | `D-03/D-04` Explicit `users` audiences must resolve directly against `push_devices` via stable keyset pagination and delivery-aligned batches. | `WS-01A` | `explicit-users push target query path + projection-minimal loading` | resolver + query implementation that only loads delivery-required target fields | focused Laravel tests | `n/a` | `planned` |
| `STR-D03` | `D-05/D-06` Future semantic audiences such as `followers of profile` must materialize concrete recipient sets before they enter the generic push-delivery pipeline. | `WS-01A` | `audience materialization seam` | contract + docs hardening | focused tests + docs audit | `n/a` | `planned` |
| `STR-D04` | `D-07` The generic push subsystem should remain responsible for queueing, batching, provider delivery, telemetry, and token invalidation, not for discovering high-level business audiences by scan. | `WS-01A` | `push subsystem boundary` | scoped structural refactor | focused Laravel tests | `n/a` | `planned` |
| `STR-D05` | `D-08/D-09` This TODO does not approve a new `event created -> followers` trigger, and the low installed-base posture permits a clean cutover without heavy legacy token compatibility work. | `WS-01A` | `spec boundary + cutover posture` | no new trigger introduced; cutover policy documented | docs/plan audit | `n/a` | `planned` |
| `STR-AC01` | `Acceptance Criteria` Explicit user audiences no longer require tenant-wide chunk iteration to resolve recipients. | `WS-01A` | `recipient resolution proof` | query-first resolver | focused Laravel tests | `n/a` | `planned` |
| `STR-AC02` | `Acceptance Criteria` Push-device registration, lookup, and invalidation no longer depend on `AccountUser.devices` as runtime authority. | `WS-01A` | `push device authority proof` | storage cutover | focused Laravel tests | `n/a` | `planned` |
| `STR-AC03` | `Acceptance Criteria` The current invite push path still works after the structural change. | `WS-01A` | `invite compatibility` | invite path on new authority | focused Laravel tests | runtime invite proof | `planned` |
| `STR-AC04` | `Acceptance Criteria` The codebase exposes a clear extension point for future semantic audiences such as `followers of profile`, without implementing those triggers yet. | `WS-01A` | `future trigger seam` | docs + resolver contract | focused tests + docs audit | `n/a` | `planned` |
| `STR-V01` | Validation Steps: Query lane: prove `audience.type = users` resolves recipients from `push_devices` via stable keyset pagination rather than `chunkUsers(...)` or offset pagination. | `WS-01A` | `query lane` | structural implementation | focused Laravel tests | `n/a` | `planned` |
| `STR-V02` | `Validation Steps` `Device-authority lane: prove register/invalidate/unregister/token lookup use push_devices as runtime truth.` | `WS-01A` | `device authority lane` | structural implementation | focused Laravel tests | `n/a` | `planned` |
| `STR-V03` | `Validation Steps` `Invite compatibility lane: prove direct invite push still authors and dispatches correctly after the structural change.` | `WS-01A` | `invite compatibility lane` | structural implementation | focused Laravel tests | runtime invite proof | `planned` |
| `STR-V04` | `Validation Steps` `Laravel suite lane: run the relevant push/invite-focused Laravel tests plus the Laravel CI-equivalent suite.` | `WS-01A` | `Laravel CI-equivalent` | structural implementation | focused + full Laravel suite | `n/a` | `planned` |
| `STR-V05` | `Validation Steps` `Docs lane: record the approved extension seam future trigger TODOs must use.` | `WS-01A` | `future trigger seam docs` | TODO/module/plan updates | docs audit | `n/a` | `planned` |
| `FF-D01` | `D-01` Direct invite send must be able to emit a recipient-targeted push automatically; authoring push messages manually in admin is not an acceptable product substitute for the invite path. | `WS-01` | `POST /invites` -> push bridge endpoint` | Laravel invite/push bridge | Laravel invite/push tests | Android device push delivery proof | `planned` |
| `FF-D02` | `D-02` Live reflection for inbound invites must not depend solely on full-screen reload. Mobile may reflect through push payload delivery; web and any non-push surface must reflect through invite SSE. | `WS-02` | `invite stream navigation` | Flutter SSE consumer + repo integration | Flutter repository/controller tests | Browser + Android runtime reflection proof | `planned` |
| `FF-D03` | `D-03` Public invite-share metadata (`/invite?code=...`) must resolve tenant/event/inviter preview data from a canonical backend-owned invite preview context and must not ship placeholder/example image URLs. | `WS-03` | `invite metadata endpoint/schema` | Laravel preview ownership + metadata hardening | Laravel/public metadata tests | Browser HTML metadata proof | `planned` |
| `FF-D04` | `D-04` Firebase settings/credentials and tenant push enablement are mandatory runtime dependencies, but missing config is not the sole blocker; the backend invite bridge and Flutter realtime consumption are separate implementation gaps. | `WS-04` | `tenant admin readiness navigation` | admin surfaces + readiness docs | Flutter tenant-admin tests + backend contract tests | local admin save proof | `planned` |
| `FF-D05` | `D-05` The invite preview title/description/image used for share metadata should be treated as the canonical preview source for invite-related public surfaces and push composition, so web share and push payloads do not drift. | `WS-01` | `canonical preview schema` | push payload + preview alignment | Laravel tests | runtime/browser consistency proof | `planned` |
| `FF-D06` | `D-06` Current web runtime remains push-disabled by design; therefore invite live reflection on web must close through the existing SSE infrastructure instead of waiting for web push. | `WS-02` | `web invite SSE navigation` | Flutter SSE integration | Flutter/web tests | browser runtime proof | `planned` |
| `FF-D07` | `D-07` Both Firebase public app config and FCM server credentials must remain tenant-dynamic and admin-managed. Local JSON files may be used only as operator input sources during this lane, never as a durable runtime configuration mechanism. | `WS-04` | `tenant admin firebase + credentials schema` | admin settings/credentials UI | Flutter tests + targeted Laravel tests | local tenant-admin save proof | `planned` |
| `FF-AC01` | `Acceptance Criteria` Sending a direct invite to an eligible recipient produces a backend-authored `invite_received` push message and dispatches delivery when tenant push is configured and the recipient has a registered token. | `WS-01` | `invite_received` push authoring` | backend bridge | Laravel tests | Android device proof | `planned` |
| `FF-AC02` | `Acceptance Criteria` Tenant-admin can successfully save Firebase settings and push settings against the current backend contract without `422` envelope errors. | `WS-04` | `settings/firebase` + `settings/push` navigation` | Flutter admin payload fixes | Flutter tests + targeted Laravel endpoint tests | local admin proof | `planned` |
| `FF-AC03` | `Acceptance Criteria` Tenant-admin can explicitly enable and disable push from the current UI and the resulting `enabled` state is reflected in environment/status snapshots. | `WS-04` | `enable/disable navigation` | Flutter toggle flow | Flutter tests + targeted Laravel endpoint tests | local admin proof | `planned` |
| `FF-AC04` | `Acceptance Criteria` Tenant-admin can create/update the tenant-scoped FCM server credential from the current UI without relying on ad hoc DB edits or external one-off scripts. | `WS-04` | `push/credentials schema + navigation` | Flutter credentials form | Flutter tests + targeted Laravel credential tests | local admin proof | `planned` |
| `FF-AC05` | `Acceptance Criteria` On mobile, when the app is already open and a compatible invite push arrives, the recipient sees the invite reflected without a manual app reload. | `WS-02` | `pending invites runtime navigation` | Flutter push/SSE integration | Flutter tests | Android runtime proof | `planned` |
| `FF-AC06` | `Acceptance Criteria` On web, and as a general fallback when push is unavailable, the invite list/related surfaces can reflect new inbound invite changes from `/invites/stream`. | `WS-02` | `web invite reflection navigation` | Flutter SSE consumer | Flutter tests | browser runtime proof | `planned` |
| `FF-AC07` | `Acceptance Criteria` `/invite?code=...` returns production-safe OG/Twitter metadata with a real, publicly reachable image and invite-specific copy. | `WS-03` | `invite metadata navigation` | metadata hardening | Laravel/public metadata tests | browser HTML proof | `planned` |
| `FF-AC08` | `Acceptance Criteria` Firebase/tenant-admin configuration requirements are documented and validated as runtime prerequisites rather than left implicit. | `WS-04` | `tenant admin readiness doc/schema` | docs + admin readiness surfaces | docs + targeted tests | local readiness proof | `planned` |
| `FF-AC09` | `Acceptance Criteria` End-to-end evidence clearly distinguishes “feature missing” from “environment misconfigured.” | `WS-01`,`WS-02`,`WS-04` | `runtime evidence pack` | consolidated findings | cross-stack evidence | device + browser + admin proof | `planned` |
| `FF-AC10` | `Acceptance Criteria` The final local E2E proof uses the real Firebase/FCM integration values sourced from the frozen operator input files above, saved through the repaired tenant-admin UI on the local tenant. | `WS-04` | `tenant admin operator-input navigation` | admin save flow populated from frozen files | targeted admin readiness checks | final device + browser proof using saved real values | `planned` |
| `FF-V01` | `Validation Steps` `Laravel test lane: prove direct invite creation authors/dispatched invite push only when runtime prerequisites are satisfied and stays deterministic when prerequisites are missing.` | `WS-01` | `endpoint + push delivery schema` | targeted Laravel coverage | Laravel feature/service suite | `n/a` | `planned` |
| `FF-V01A` | `Validation Steps` `Structural lane: prove invite-targeted explicit-user push now rides the `push_devices`-backed query/materialization-first recipient-resolution path from `TODO-structural-push-query-based-audience-materialization.md`.` | `WS-01A`,`WS-01` | `invite structural compatibility lane + push_devices loading proof` | structural resolver + invite bridge integration | focused Laravel tests | runtime invite proof | `planned` |
| `FF-V02` | `Validation Steps` `Tenant-admin settings lane: prove Firebase/push saves no longer send stale envelopes and that enable/disable actions work against the live backend endpoints.` | `WS-04` | `tenant admin settings navigation` | Flutter admin fixes | Flutter tests + targeted Laravel endpoint coverage | local admin save proof | `planned` |
| `FF-V03` | `Validation Steps` `Tenant-admin credentials lane: prove the UI can write the tenant FCM server credential through `/push/credentials` and that the stored credential is then consumed by the FCM delivery path.` | `WS-04` | `push/credentials schema + navigation` | Flutter credentials UI + Laravel credential usage | Flutter tests + targeted Laravel credential tests | device/runtime proof | `planned` |
| `FF-V04` | `Validation Steps` `Flutter test lane: prove invite push payload upserts the repository and that invite SSE updates drive the same repository/screen state.` | `WS-02` | `InvitesRepository + invite stream schema` | Flutter repository/controller updates | Flutter unit/widget tests | browser/android runtime reflection proof | `planned` |
| `FF-V05` | `Validation Steps` `Public web metadata lane: assert `/invite?code=...` HTML contains invite-specific OG/Twitter tags with non-placeholder image URLs.` | `WS-03` | `invite public shell HTML navigation` | metadata hardening | Laravel/public shell tests | browser HTML proof | `planned` |
| `FF-V06` | `Validation Steps` `Runtime lane (mobile): validate device registration + direct invite send + push delivery + app-open reflection on a real Android device or production-equivalent push runtime.` | `WS-01`,`WS-02`,`WS-04` | `Android runtime navigation` | integrated flow | local CI-equivalent suites + runtime lane | Android device proof | `planned` |
| `FF-V07` | `Validation Steps` `Runtime lane (web): validate `/invite?code=...` share page metadata and invite reflection behavior through the browser-facing domain.` | `WS-02`,`WS-03` | `browser invite navigation` | integrated flow | local CI-equivalent suites + browser lane | Playwright/browser proof | `planned` |
| `FF-V08` | `Validation Steps` `Tenant-admin readiness lane: validate `settings/firebase`, `settings/push/credentials`, `settings/push/enable`, and device-token registration against the target tenant.` | `WS-04` | `tenant admin readiness schema + navigation` | admin + backend integration | targeted admin/backend checks | local readiness proof | `planned` |
| `FF-V09` | `Validation Steps` `Operator-input lane: validate that the frozen local Firebase/FCM input files are the exact values saved through tenant-admin before the final E2E run.` | `WS-04` | `tenant admin operator-input schema + navigation` | compare saved tenant-admin values against frozen files | targeted admin readiness checks | final runtime uses those saved values | `planned` |

## Spec Deviation Ledger
| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | `No spec deviations approved.` | `n/a` | `n/a` | `n/a` |

## Dependency Graph
- `RB-LARAVEL-01` blocks `FF-INVITE-01` because invite/push execution requires a fresh backend base from the current promoted lane.
- `WS-01A` depends on `WS-00` completion for branch topology and replayed backend hardening.
- `WS-01` depends on `WS-01A` for the structural recipient-resolution foundation.
- The current invite-push promotion cannot close without `WS-01A`; it is a same-promotion performance blocker, not a post-delivery enhancement.
- `WS-02`, `WS-03`, and `WS-04` can begin planning in parallel, but implementation dispatch waits for the backend refresh handoff recorded by `RB-LARAVEL-01`.

## Orchestration Topology
- **Base branch / commit:** fresh `reconcile/*` branches cut from the current active promotion targets (`origin/dev` for root/Flutter/Laravel; `main` for foundation docs).
- **Orchestrator reconciliation branch:** `reconcile/fast-follow-laravel-rebase-and-invite-push-20260509`
- **Principal checkout policy:** keep the principal runtime checkout on the orchestrator branch once browser/device validation begins.
- **Runtime-facing source checkouts:** `belluga_now_docker` root + `laravel-app` + `flutter-app` must all be on the fresh invite/push reconcile topology before authoritative validation.
- **Worker branches / worktrees:** one worker worktree per workstream, all cut from the fresh orchestrator base. No worker writes directly on the orchestrator checkout.
- **Derived artifact repos:** `web-app` remains a derived bundle repo only.

## Checkpoint / Branch Accumulation Control
- **Checkpoint manifest path:** `foundation_documentation/artifacts/checkpoints/fast-follow-laravel-rebase-and-invite-push-2026-05-09.md`
- **Checkpoint policy:** checkpoints are pushed recovery states plus manifests, not indefinite accumulation branches.
- **Allowed checkpoint statuses:** `wip_checkpoint`, `validated_local_checkpoint`, `promotion_ready_checkpoint`, `superseded_checkpoint`.
- **Same-branch continuation rule:** continue on the combined orchestrator branch only while the work remains inside this approved two-TODO sequence and the checkpoint manifest records the next exact step.
- **Build artifact policy:** `web-app` output is excluded unless a fresh bundle is explicitly required for browser evidence.

## Workstreams
| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-00 Laravel Base Refresh` | `laravel-app` auth/runtime touched files only | current promoted Laravel lane, stale reconcile drift, RB-LARAVEL-01 | fresh backend base + drift ledger + handoff checkpoint | targeted auth/runtime tests + full Laravel suite |
| `WS-01A Push Audience Foundation` | `laravel-app/packages/belluga/belluga_push_handler/**`, push adapters/models/tests + identity touchpoints required by cutover | `WS-00`, STR-PUSH-AUD-01 | `push_devices` authority checkpoint | focused push/invite tests + full Laravel suite |
| `WS-01 Laravel Invite Delivery` | `laravel-app/packages/belluga/belluga_invites/**`, `belluga_push_handler/**` and aligned adapters/tests | `WS-00`, canonical preview owner | invite -> push bridge checkpoint | targeted Laravel invite/push tests + full Laravel suite |
| `WS-02 Flutter Invite Reflection` | `flutter-app` invite repo/SSE/application surfaces | `WS-00`, invite payload contract | invite SSE + push reflection checkpoint | rule matrix, analyzer, focused tests, full Flutter suite |
| `WS-03 Public Invite Metadata` | `laravel-app/app/Application/PublicWeb/**`, shell controllers/tests | `WS-00`, preview owner contract | OG/share metadata checkpoint | targeted Laravel metadata tests + browser metadata prep |
| `WS-04 Tenant Admin Push Readiness` | `flutter-app` tenant-admin settings/credentials surfaces + targeted Laravel endpoint tests | `WS-00`, operator input sources frozen in push TODO | admin contract-aligned config surfaces checkpoint | rule matrix, analyzer, focused tests, targeted endpoint tests |

## Execution Ownership Ledger
| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-00` | `worker-laravel-base-refresh` | `merge-conflict-only` | drift ledger + refreshed backend branch + Laravel tests | replay verification + handoff proof |
| `WS-01A` | `worker-laravel-push-audience-foundation` | `merge-conflict-only` | `push_devices` cutover checkpoint + focused Laravel tests | merge/cherry-pick + compatibility proof |
| `WS-01` | `worker-laravel-invite-delivery` | `merge-conflict-only` | invite bridge commit + Laravel tests | merge/cherry-pick + device proof |
| `WS-02` | `worker-flutter-invite-reflection` | `merge-conflict-only` | Flutter checkpoint + analyzer/tests | merge/cherry-pick + browser/device proof |
| `WS-03` | `worker-laravel-public-metadata` | `merge-conflict-only` | metadata checkpoint + Laravel tests | merge/cherry-pick + browser proof |
| `WS-04` | `worker-flutter-tenant-admin-push-readiness` | `merge-conflict-only` | Flutter checkpoint + targeted endpoint proof | merge/cherry-pick + admin/device proof |

## Execution Waves
Waves are orchestrator-owned internal control checkpoints, not routine feedback gates. After approval, the orchestrator advances autonomously between waves and stops only for a mandatory decision, scope change, governing TODO conflict, real blocker, or explicit validation waiver.

### Wave 0 - Preflight / Approval
- Publish this combined plan.
- Freeze the two governing TODOs and mark the single-TODO invite plan superseded.
- Confirm the Firebase operator input paths recorded in the push TODO exist.
- **Gate to next wave:** plan approved.

### Wave 1 - Laravel Base Refresh
- Dispatch `WS-00`.
- Produce the promoted-vs-unpublished drift ledger.
- Create the fresh backend base and validate it.
- **Gate to next wave:** `RB-LARAVEL-01` acceptance + validation rows passed.

### Wave 2 - Push Audience Foundation
- Dispatch `WS-01A`.
- Introduce `push_devices` as the authoritative push-delivery store.
- Replace scan-style explicit-user resolution with the approved query/materialization-first path.
- Use stable keyset pagination and projection-minimal delivery targets instead of offset pagination or embedded-device extraction.
- Replace the current per-token raw FCM HTTP v1 fan-out with the approved bounded provider transport for `<= 500` recipients, and add budget tests for both database queries and outbound provider requests.
- Freeze the direct-recipient budget rule explicitly: `<= 500` concrete recipients must produce one provider send batch request for that chunk, plus the required auth-token request, never one provider request per recipient.
- Introduce the server-managed channel/topic membership foundation for stable recurring audiences, using canonical domain events for favorite/profile and occurrence-confirmation membership sync.
- Freeze the extension seam future follower/profile/event triggers must use, including the rule that stable subscribable audiences should prefer topic/channel delivery over per-send recipient materialization, without introducing those triggers yet.
- Freeze the canonical side-effect rule: push, topic-membership churn, telemetry, and social-counter refresh must all subscribe to the same post-commit domain/activity events rather than re-deriving triggers from controllers/UI.
- **Gate to next wave:** `STR-PUSH-AUD-01` acceptance + validation rows passed.

### Wave 3 - Backend Feature Contract
- Dispatch `WS-01` and `WS-03` on top of the refreshed backend base.
- Freeze canonical preview ownership shared by push payloads and public metadata.
- Add the direct-recipient invite lifecycle bridges for both `invite_received` and `invite_accepted`, where `invite_accepted` targets the original sender/inviter and the same canonical acceptance event remains available to telemetry/social-counter consumers.
- **Gate to next wave:** backend contract/metadata worker-local tests green.

### Wave 4 - Client/Admin Surfaces
- Dispatch `WS-02` and `WS-04` on top of the refreshed topology.
- Align tenant-admin settings, push toggles, and credentials surfaces with the live backend contracts.
- **Gate to next wave:** Flutter analyzer + worker-local Flutter tests green.

### Wave 5 - Reconciliation and Runtime Closure
- Merge accepted worker checkpoints into the orchestrator branch.
- Save Firebase public config and FCM server credential via tenant-admin on the local tenant, using the frozen operator input files recorded in `TODO-fast-follow-invite-push-live-reflection-and-share-metadata.md`.
- Validate Android device push delivery + app-open reflection.
- Validate browser-facing invite metadata and web reflection through SSE.
- **Gate to completion:** consolidated CI-equivalent matrix green and runtime/browser/device evidence complete.

### Wave 6 - Stage Promotion Follow-Through
- If Waves 1-5 close green with no unresolved waiver, run the canonical `github-stage-promotion-orchestrator` skill for the touched repos and promote the delivery through `stage`.
- Promotion authority for `stage` is already granted by the current user approval, but `main` remains out of scope unless explicitly requested later.
- **Gate to close:** stage lane green with the canonical promotion evidence recorded.

## Consolidated Validation Matrix
| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| `Laravel base refresh` | drift ledger + targeted auth/runtime tests + full Laravel suite | `reconciliation` | `WS-00` then `orchestrator` |
| `Push audience foundation` | `push_devices` authority proof + query-first recipient-resolution proof + invite compatibility + full Laravel suite | `reconciliation` | `WS-01A` then `orchestrator` |
| `Invite delivery backend` | targeted invite/push tests (`invite_received` + `invite_accepted`) + full Laravel suite | `reconciliation` | `WS-01` then `orchestrator` |
| `Public invite metadata` | targeted Laravel metadata tests + browser HTML proof | `reconciliation` then `browser` | `WS-03` then `orchestrator` |
| `Flutter invite reflection` | analyzer + focused tests + full Flutter suite | `reconciliation` | `WS-02` then `orchestrator` |
| `Tenant-admin push readiness` | analyzer + focused tests + targeted endpoint tests + local admin save proof | `reconciliation` then `browser/device` | `WS-04` then `orchestrator` |
| `Android push runtime` | invite send -> push delivery -> app-open reflection | `device` | `orchestrator` |
| `Web invite runtime` | invite metadata + SSE reflection through browser-facing domain | `browser` | `orchestrator` |
| `Operator-input fidelity` | frozen Firebase/FCM input files match the values saved through tenant-admin before final runtime proof | `reconciliation` then `browser/device` | `WS-04` then `orchestrator` |
| `Stage promotion` | canonical promotion-lane evidence through `stage` after all local/runtime gates are green | `stage lane` | `orchestrator` |

## CI-Equivalent Local Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Applies To (`worker-local|reconciliation|pre-promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / rule matrix` | Flutter and shared runtime work are in scope | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `laravel-app / Laravel CI-equivalent` | backend refresh + invite delivery + metadata are in scope | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `flutter-app / analyzer` | invite reflection and tenant-admin work are in scope | `fvm dart analyze --format machine` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `flutter-app / Flutter CI-equivalent` | repository/admin/UI behaviors are in scope | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `flutter-app / web build` | browser evidence may require a fresh bundle when Flutter web changes | `bash scripts/build_web.sh ../web-app dev` | `reconciliation` | `planned` | `stdout` | `orchestrator` |
| `belluga_now_docker / browser readonly` | invite metadata and web reflection are visible browser behaviors | `./scripts/delphi/run_navigation_reconcile_validation.sh readonly` | `reconciliation` | `planned` | `Playwright report / stdout` | `orchestrator` |
| `belluga_now_docker / browser mutation` | tenant-admin save flows and invite-send runtime flows are mutation behaviors | `./scripts/delphi/run_navigation_reconcile_validation.sh mutation` | `reconciliation` | `planned` | `Playwright report / stdout` | `orchestrator` |
| `promotion / stage lane` | user pre-authorized promotion through `stage` when all prior gates are green | `github-stage-promotion-orchestrator` canonical flow | `pre-promotion` | `planned` | `promotion lane evidence` | `orchestrator` |

## Consolidated Delivery Evidence
| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |
| `Laravel base refresh` | fresh reconcile base from promoted lane + RR-AUTH/runtime replay on top of it | `local-implemented` | `laravel-app/reconcile/fast-follow-laravel-rebase-and-invite-push-20260509` | `orchestrator` |
| `Push audience foundation` | `push_devices` authority + query-first explicit-user resolution + focused Laravel proof | `local-implemented (full suite pending)` | `tests/Feature/Push/PushMessageFlowTest.php`, `tests/Api/v1/Tenants/Auth/ApiV1PushRegisterTest.php`, `tests/Feature/Invites/InvitesFlowTest.php`, `tests/Api/v1/Tenants/Auth/T1PasswordRegistrationTest.php`, `tests/Api/v1/Tenants/Auth/T2PasswordRegistrationTest.php`, `exact_lookup_anti_pattern_audit.sh`, `php scripts/architecture_guardrails.php` | `orchestrator` |

## Checkpoint Manifest
- **Manifest path:** `foundation_documentation/artifacts/checkpoints/fast-follow-laravel-rebase-and-invite-push-2026-05-09.md`
- **Checkpoint status:** `wip_checkpoint`
- **Repositories pushed:** `tbd after execution`
- **Excluded dirty surfaces:** `none yet`
- **Next branch lifecycle step:** `finish isolated Laravel CI-equivalent suite, then commit/push laravel-app reconcile + foundation_documentation main`

## Runtime Freshness Evidence
- Filled after execution. Browser/device/admin evidence is required because the second TODO includes tenant-admin UI behavior, browser-facing invite metadata, and Android push delivery.

## Runtime Surface Preflight
- **Principal runtime target already in use:** `belluga.space` + `guarappari.belluga.space` + local Android device
- **Bind-mount / served-source proof:** `must be recorded after the fresh topology is created`
- **Navigation env source:** `.env.local.navigation` or already-exported shell vars
- **Auxiliary runtime required?:** `no if the principal target already serves the fresh reconcile state; otherwise blocked until bind mounts are corrected`

## Risk / Conflict Controls
- The backend refresh, the push-audience foundation, and the invite/push TODO are intentionally separated so rebase risk does not hide structural push risk or feature risk.
- The push-audience foundation is required in the same promotion as the invite-push work because the current delivery is not accepted while explicit-user audiences still rely on scan-style resolution or embedded `AccountUser.devices` authority.
- The push-audience foundation is also not accepted while the provider layer expands a `<= 500` recipient batch into per-token raw FCM HTTP v1 requests; transport budget is part of the same structural blocker.
- The push-audience foundation is also not accepted unless the budget tests prove both the database-query count and the provider-request count, including the rule that `<= 500` direct recipients produce one provider send batch request instead of one provider request per recipient.
- The push-audience foundation also owns the channel/topic membership layer for stable recurring audiences such as favorite/profile followers and confirmed occurrences. Those memberships must be driven by canonical domain events, while direct invite delivery stays on the direct-recipient path.
- Invite lifecycle side effects must stay event-sourced: `invite_received` and `invite_accepted` pushes, telemetry, and social-counter refreshes must all hang off canonical post-commit invite events rather than controller/UI code.
- Future follower/profile/event push triggers are explicitly out of scope for this plan until the structural audience TODO is complete and a separate trigger-policy TODO is approved.
- Operator input paths are recorded in the push TODO, but runtime source of truth must remain tenant-admin/backend settings after save.
- Final local E2E closure is only valid if the repaired tenant-admin UI was populated from the frozen operator input files and the runtime proof uses that saved real integration state.
- Web push remains disabled; satisfying web reflection via anything other than SSE is a spec violation.
- The orchestrator may not implement worker-owned production code except merge-conflict reconciliation.

## Approval Request
- **Requested approval:** Reply `APROVADO` to authorize this combined orchestration plan.
- **Execution authorized by approval:** Wave 1 backend refresh, then dependent workstreams through runtime closure, and promotion through `stage` via the canonical promotion-lane skill if all plan gates pass.
- **Execution not authorized by approval:** promotion to `main`, web push enablement, or scope beyond the two governing TODOs.
- **Autonomy rule:** once approved, the orchestrator advances through waves without requesting feedback between waves unless a mandatory decision, blocker, or waiver condition appears.

## Plan Completion Guard
- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/fast-follow-laravel-rebase-and-invite-push-orchestration-plan.md`
- **Required before approval/execution:** `Overall outcome: go`

## Delivery Guard
- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/fast-follow-laravel-rebase-and-invite-push-orchestration-plan.md --require-approved`
- **Required before local implementation or delivery completion claim:** `Overall outcome: go`
- **Blocks delivery when:** the backend refresh TODO is not fully satisfied before invite/push implementation, any UI/runtime criterion lacks fresh browser/device evidence, or a named artifact is substituted without an approved spec deviation.
