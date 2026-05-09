# Orchestration Execution Plan: Invite Push Delivery, Live Reflection, and Share Metadata

## Artifact Identity
- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Pending Approval`
- **Created:** `2026-05-09`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`

## Authority Boundary
- Governing TODOs define **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the orchestrator intends to sequence, parallelize, reconcile, and validate the work.
- If this plan conflicts with a governing TODO, stop and update the TODO or this plan before execution.
- This plan does not create a new backlog authority, tactical TODO, or approval conversation.
- Requirement wording in the governing TODO is literal. Replacing a named endpoint, runtime lane, metadata surface, or validation lane requires an approved row in the Spec Deviation Ledger before execution or delivery can proceed.
- Because this TODO is independent from `RR-AUTH`, it must not reuse the stale `laravel-app/reconcile/post-release-rule-related-auth-identity-20260506` branch as an implementation base. Fresh runtime-facing `reconcile/*` branches cut from the current promotion lane targets are required for authoritative validation.

## Governing TODO Set
| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `FF-INVITE-01` | `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-push-live-reflection-and-share-metadata.md` | `blocker` | `can start after plan approval` |

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `FF-INVITE-D01` | `D-01` Direct invite send must emit recipient-targeted push automatically. | `WS-01` | `POST /invites` -> push authoring bridge | Laravel invite mutation + push bridge code | Laravel feature/service tests for invite send -> push authoring | Android device push delivery proof | `planned` |
| `FF-INVITE-D02` | `D-02` Live reflection must not depend only on full reload; mobile may use push, web must use SSE. | `WS-02` | `GET /invites/stream`, Flutter invite repository wiring | Flutter SSE consumer + repository integration | Flutter repository/controller tests | Browser + Android runtime reflection proof | `planned` |
| `FF-INVITE-D03` | `D-03` `/invite?code=...` must resolve canonical share-safe metadata and no placeholder image. | `WS-03` | `invite` public shell metadata | Laravel public metadata/preview hardening | Laravel/public metadata tests | Browser HTML metadata proof on `belluga.space` | `planned` |
| `FF-INVITE-D04` | `D-04` Firebase config/runtime deps are mandatory but not the sole blocker. | `WS-04` | `tenant admin firebase/push readiness` | Tenant-admin config surfaces + readiness docs | Flutter tenant-admin tests + backend contract tests | Local admin save proof + device token registration proof | `planned` |
| `FF-INVITE-D05` | `D-05` Public invite preview data must be canonical for share + push composition. | `WS-01` | `canonical invite preview owner` | Laravel preview/push composition alignment | Laravel unit/feature tests | Push payload + share metadata consistency proof | `planned` |
| `FF-INVITE-D06` | `D-06` Web remains push-disabled; reflection there must close through SSE. | `WS-02` | `web invite live reflection` | Flutter web/runtime SSE integration | Flutter/web tests | Browser runtime reflection proof | `planned` |
| `FF-INVITE-D07` | `D-07` Firebase app config and FCM credentials stay tenant-dynamic and admin-managed. | `WS-04` | `tenant admin firebase + push credentials UI` | Flutter tenant-admin forms + existing Laravel endpoints | Flutter tenant-admin tests + targeted Laravel credential tests | Local admin save proof using tenant config only | `planned` |
| `FF-INVITE-AC01` | `AC-01` Direct invite send produces `invite_received` push and dispatches delivery when prerequisites are satisfied. | `WS-01` | `invite_received` push message + dispatch path | Backend bridge + queue authoring | Laravel invite/push tests | Android device receives push | `planned` |
| `FF-INVITE-AC02` | `AC-02` Tenant-admin saves Firebase settings against current backend contract without `422` envelope errors. | `WS-04` | `settings/firebase` admin save flow | Flutter tenant-admin payload fix | Flutter repository/UI tests + backend contract tests | Local admin UI save proof | `planned` |
| `FF-INVITE-AC03` | `AC-03` Tenant-admin saves push settings and enable/disable state coherently. | `WS-04` | `settings/push`, `enable/disable` controls | Flutter tenant-admin push settings + toggle flow | Flutter tests + targeted Laravel endpoint tests | Local admin UI enable/disable proof | `planned` |
| `FF-INVITE-AC04` | `AC-04` Tenant-admin can create/update tenant-scoped FCM server credential from current UI. | `WS-04` | `push/credentials` admin surface | Flutter credentials form + existing Laravel endpoint use | Flutter tests + targeted Laravel credential tests | Local admin UI credential save proof | `planned` |
| `FF-INVITE-AC05` | `AC-05` Mobile app open state reflects inbound invite without manual reload. | `WS-02` | `pending invites` runtime state | Flutter push/SSE repository integration | Flutter repository/controller tests | Android runtime proof with app already open | `planned` |
| `FF-INVITE-AC06` | `AC-06` Web and push-unavailable fallback reflect new invites from `/invites/stream`. | `WS-02` | `web invite surfaces` | Flutter web SSE consumer | Flutter/web tests | Browser runtime reflection proof | `planned` |
| `FF-INVITE-AC07` | `AC-07` `/invite?code=...` returns production-safe OG/Twitter metadata with real public image and invite-specific copy. | `WS-03` | `invite public shell` | Laravel metadata hardening | Laravel/public metadata tests | Browser HTML/preview proof | `planned` |
| `FF-INVITE-AC08` | `AC-08` Firebase/admin runtime requirements are documented and validated as prerequisites. | `WS-04` | `tenant admin readiness contract` | TODO/docs/admin readiness updates | Docs + targeted test evidence | Local readiness checklist proof | `planned` |
| `FF-INVITE-AC09` | `AC-09` E2E evidence distinguishes feature gap vs environment misconfiguration. | `WS-01`,`WS-02`,`WS-04` | `runtime evidence pack` | consolidated findings + proofs | cross-stack validation evidence | Browser/device/admin evidence with config known-good | `planned` |
| `FF-INVITE-V01` | `Validation Steps` `Laravel test lane: prove direct invite creation authors/dispatched invite push only when runtime prerequisites are satisfied and stays deterministic when prerequisites are missing.` | `WS-01` | `endpoint + push delivery schema` | targeted Laravel invite/push coverage | Laravel feature/service suite | `n/a` | `planned` |
| `FF-INVITE-V02` | `Validation Steps` `Tenant-admin settings lane: prove Firebase/push saves no longer send stale envelopes and that enable/disable actions work against the live backend endpoints.` | `WS-04` | `tenant admin settings save navigation` | Flutter admin contract alignment | Flutter tests + targeted Laravel endpoint coverage | Local admin save proof | `planned` |
| `FF-INVITE-V03` | `Validation Steps` `Tenant-admin credentials lane: prove the UI can write the tenant FCM server credential through `/push/credentials` and that the stored credential is then consumed by the FCM delivery path.` | `WS-04` | `push/credentials schema + navigation` | Flutter credentials UI + Laravel credential usage | Flutter tests + targeted Laravel credential tests | Device/runtime proof | `planned` |
| `FF-INVITE-V04` | `Validation Steps` `Flutter test lane: prove invite push payload upserts the repository and that invite SSE updates drive the same repository/screen state.` | `WS-02` | `InvitesRepository + invite stream schema` | Flutter repository/controller updates | Flutter unit/widget tests | Browser/android runtime reflection proof | `planned` |
| `FF-INVITE-V05` | `Validation Steps` `Public web metadata lane: assert `/invite?code=...` HTML contains invite-specific OG/Twitter tags with non-placeholder image URLs.` | `WS-03` | `invite public shell HTML navigation` | Metadata hardening | Laravel/public shell tests | Browser HTML proof | `planned` |
| `FF-INVITE-V06` | `Validation Steps` `Runtime lane (mobile): validate device registration + direct invite send + push delivery + app-open reflection on a real Android device or production-equivalent push runtime.` | `WS-01`,`WS-02`,`WS-04` | `Android runtime navigation` | full stack integrated | local CI-equivalent suites + runtime lane | Android device proof | `planned` |
| `FF-INVITE-V07` | `Validation Steps` `Runtime lane (web): validate `/invite?code=...` share page metadata and invite reflection behavior through the browser-facing domain.` | `WS-02`,`WS-03` | `browser-facing invite navigation` | full stack integrated | local CI-equivalent suites + browser lane | Playwright/browser proof | `planned` |
| `FF-INVITE-V08` | `Validation Steps` `Tenant-admin readiness lane: validate `settings/firebase`, `settings/push/credentials`, `settings/push/enable`, and device-token registration against the target tenant.` | `WS-04` | `tenant admin readiness schema + navigation` | admin + backend integration | targeted admin/backend checks | local tenant readiness proof | `planned` |

## Spec Deviation Ledger
| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | `No spec deviations approved.` | `n/a` | `n/a` | `n/a` |

## Dependency Graph
- `WS-01` depends on canonical invite preview ownership because push payload composition and public metadata must not drift.
- `WS-02` depends on `WS-01` only for final payload shape alignment; SSE consumer scaffolding can start independently.
- `WS-03` depends on `WS-01` for the final canonical preview contract but can begin by hardening current metadata fallbacks.
- `WS-04` is independent from `WS-01`/`WS-03` for payload-envelope fixes and admin credential surfaces, but runtime E2E cannot close until `WS-04` provisions valid tenant config.

## Orchestration Topology
- **Base branch / commit:** fresh `reconcile/*` branches cut from the current active promotion lane targets (`origin/dev` for `belluga_now_docker`, `flutter-app`, and `laravel-app`; `main` for `foundation_documentation`)
- **Orchestrator reconciliation branch:** `reconcile/fast-follow-invite-push-live-reflection-share-metadata-20260509`
- **Principal checkout policy:** the principal runtime checkout stays on the orchestrator-owned reconcile branch once browser/device validation begins.
- **Runtime-facing source checkouts:** `belluga_now_docker` root + `laravel-app` + `flutter-app` must all be on the invite/push reconcile branch (or explicit detached checkpoint) before authoritative local validation.
- **Worker branches / worktrees:** one worker branch/worktree per workstream cut from the same fresh base; no worker may implement directly on the orchestrator checkout.
- **Derived artifact repos:** `web-app` remains a derived bundle repo and is rebuilt from the authoritative `flutter-app` reconcile state when browser evidence requires a fresh bundle.

## Checkpoint / Branch Accumulation Control
- **Checkpoint manifest path:** `foundation_documentation/artifacts/checkpoints/fast-follow-invite-push-live-reflection-share-metadata-2026-05-09.md`
- **Checkpoint policy:** checkpoints are pushed recovery states plus manifests, not indefinite accumulation branches.
- **Allowed checkpoint statuses:** `wip_checkpoint`, `validated_local_checkpoint`, `promotion_ready_checkpoint`, `superseded_checkpoint`.
- **Same-branch continuation rule:** continue on the invite/push orchestrator branch only while the work remains inside this approved plan and the checkpoint manifest records the next exact step. Because this TODO is independent from `RR-AUTH`, do not accumulate new work directly on `reconcile/post-release-rule-related-auth-identity-20260506`.
- **Build artifact policy:** generated `web-app` bundles are excluded unless this plan explicitly owns a derived bundle refresh for browser validation.

## Workstreams
| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-01 Laravel Invite Delivery` | `laravel-app/packages/belluga/belluga_invites/**`, `laravel-app/packages/belluga/belluga_push_handler/**`, canonical preview composition | invite domain contracts, push package contracts, TODO D-01/D-05/AC-01 | invite send -> push bridge + canonical payload/preview checkpoint | targeted Laravel feature/service tests + full Laravel CI-equivalent suite |
| `WS-02 Flutter Invite Reflection` | `flutter-app/lib/application/**`, `flutter-app/lib/infrastructure/repositories/**`, `flutter-app/lib/infrastructure/services/sse/**`, invite-facing screens/controllers touched by reflection | invite SSE contract, invite push payload contract, TODO D-02/D-06/AC-05/AC-06 | invite SSE + push reflection checkpoint | `validate_rule_matrix.sh`, `fvm dart analyze --format machine`, focused Flutter tests, full Flutter CI-equivalent suite |
| `WS-03 Public Invite Metadata` | `laravel-app/app/Application/PublicWeb/**`, `laravel-app/app/Http/Controllers/TenantPublicShellController.php`, invite preview adapters/tests | canonical invite preview ownership, TODO D-03/AC-07 | share metadata hardening checkpoint | targeted Laravel/public shell tests + browser metadata proof prep |
| `WS-04 Tenant Admin Push Readiness` | `flutter-app/lib/presentation/tenant_admin/**`, `flutter-app/lib/infrastructure/repositories/tenant_admin/**`, existing Laravel push/firebase endpoints/tests | Firebase public config, FCM service credential source files, TODO D-04/D-07/AC-02/AC-03/AC-04/AC-08 | admin contract-aligned settings/credentials/toggle checkpoint | `validate_rule_matrix.sh`, `fvm dart analyze --format machine`, focused Flutter tests, targeted Laravel endpoint tests |

## Execution Ownership Ledger
| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-01` | `worker-laravel-invite-delivery` | `merge-conflict-only` | checkpoint commit + targeted Laravel tests + full Laravel suite proof | merge/cherry-pick + consolidated Laravel/browser/device evidence |
| `WS-02` | `worker-flutter-invite-reflection` | `merge-conflict-only` | checkpoint commit + analyzer + focused/full Flutter tests | merge/cherry-pick + consolidated browser/device evidence |
| `WS-03` | `worker-laravel-public-metadata` | `merge-conflict-only` | checkpoint commit + targeted Laravel metadata tests | merge/cherry-pick + browser metadata evidence |
| `WS-04` | `worker-flutter-tenant-admin-push-readiness` | `merge-conflict-only` | checkpoint commit + analyzer + focused/full Flutter tests + targeted endpoint proof | merge/cherry-pick + local admin/runtime evidence |

## Execution Waves
Waves are orchestrator-owned internal control checkpoints, not routine feedback gates. After approval, the orchestrator advances autonomously between waves and stops only for a mandatory decision, scope change, governing TODO conflict, real blocker, or explicit validation waiver.

### Wave 0 - Preflight / Approval
- Freeze the governing TODO and publish this orchestration plan.
- Create fresh root / Laravel / Flutter reconcile branches from the current active promotion targets instead of reusing the stale RR-AUTH Laravel reconcile branch.
- Verify local operator-only Firebase input files exist outside the repo (`google-services.json`, `fcm-service-account.json`).
- Record runtime target and branch topology before dispatch.
- **Gate to next wave:** plan approved and fresh branch topology created.

### Wave 1 - Backend Contract and Preview Ownership
- Dispatch `WS-01` for invite -> push bridge and canonical invite preview/payload alignment.
- Dispatch `WS-03` for public invite metadata hardening.
- Keep preview-data ownership aligned between share metadata and push payload composition.
- **Gate to next wave:** backend payload/preview contract frozen with worker-local tests green.

### Wave 2 - Client/Admin Surfaces
- Dispatch `WS-02` for invite SSE consumption and repository reflection.
- Dispatch `WS-04` for tenant-admin payload contract fixes, credentials UI, and push enable/disable controls.
- **Gate to next wave:** Flutter analyzer + worker-local Flutter tests green on both workstreams.

### Wave 3 - Reconciliation and Runtime Closure
- Merge accepted worker checkpoints into the orchestrator reconcile branch.
- Save Firebase public config + FCM server credential through the tenant-admin UI on the local tenant.
- Validate Android device push delivery + app-open reflection.
- Validate browser-facing invite metadata and web invite reflection via SSE on `belluga.space`.
- **Gate to completion:** consolidated CI-equivalent matrix green and runtime/browser/device evidence complete.

## Consolidated Validation Matrix
| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| `Laravel invite delivery` | targeted invite/push tests + full Laravel CI-equivalent suite | `reconciliation` | `worker-laravel-invite-delivery` then `orchestrator` |
| `Flutter invite reflection` | analyzer + focused tests + full Flutter CI-equivalent suite | `reconciliation` | `worker-flutter-invite-reflection` then `orchestrator` |
| `Tenant-admin push readiness` | analyzer + focused tests + targeted endpoint tests + local admin save proof | `reconciliation` then `browser/device` | `worker-flutter-tenant-admin-push-readiness` then `orchestrator` |
| `Public invite metadata` | targeted Laravel metadata tests + browser HTML metadata proof | `reconciliation` then `browser` | `worker-laravel-public-metadata` then `orchestrator` |
| `Android push runtime` | direct invite send -> delivered push -> app-open reflection | `device` | `orchestrator` |
| `Web invite runtime` | `/invite?code=...` OG/Twitter metadata + invite reflection via SSE | `browser` | `orchestrator` |

## CI-Equivalent Local Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Applies To (`worker-local|reconciliation|pre-promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / rule matrix` | Flutter and shared runtime changes are in scope | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `laravel-app / Laravel CI-equivalent` | invite bridge, push runtime, metadata, and admin endpoints touch backend runtime behavior | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | `worker-local,reconciliation` | `planned` | `stdout + checkpoint notes` | `worker/orchestrator` |
| `flutter-app / analyzer` | Flutter tenant-admin and invite reflection are in scope | `fvm dart analyze --format machine` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `flutter-app / Flutter CI-equivalent` | repository/admin/UI/runtime behaviors are in scope | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json` | `worker-local,reconciliation` | `planned` | `stdout` | `worker/orchestrator` |
| `flutter-app / web build` | browser-facing invite metadata/reflection evidence depends on a fresh bundle when Flutter web changes | `bash scripts/build_web.sh ../web-app dev` | `reconciliation` | `planned` | `stdout` | `orchestrator` |
| `belluga_now_docker / browser readonly` | invite metadata + web invite reflection are visible browser behaviors | `./scripts/delphi/run_navigation_reconcile_validation.sh readonly` | `reconciliation` | `planned` | `Playwright report / stdout` | `orchestrator` |
| `belluga_now_docker / browser mutation` | tenant-admin save flows and invite-send runtime flows are mutation behaviors | `./scripts/delphi/run_navigation_reconcile_validation.sh mutation` | `reconciliation` | `planned` | `Playwright report / stdout` | `orchestrator` |

## Consolidated Delivery Evidence
| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |
| `pending` | `Filled after execution.` | `planned` | `n/a` | `orchestrator` |

## Checkpoint Manifest
- **Manifest path:** `foundation_documentation/artifacts/checkpoints/fast-follow-invite-push-live-reflection-share-metadata-2026-05-09.md`
- **Checkpoint status:** `wip_checkpoint`
- **Repositories pushed:** `tbd after execution`
- **Excluded dirty surfaces:** `none yet`
- **Next branch lifecycle step:** `create fresh orchestrator reconcile branches from current promotion targets after APROVADO`

## Runtime Freshness Evidence
- Filled after execution. Runtime/browser/device evidence is required because this TODO includes tenant-admin UI behavior, browser-facing invite metadata, and Android push delivery.

## Runtime Surface Preflight
- **Principal runtime target already in use:** `belluga.space` + `guarappari.belluga.space` + local Android device
- **Bind-mount / served-source proof:** `must be recorded after the fresh reconcile branch topology is created`
- **Navigation env source:** `.env.local.navigation` or already-exported shell vars
- **Auxiliary runtime required?:** `no if the principal target already serves the fresh reconcile state; otherwise blocked until the bind mounts are corrected`

## Risk / Conflict Controls
- The current `laravel-app/reconcile/post-release-rule-related-auth-identity-20260506` branch is stale relative to the promoted lane and must not be reused as the implementation base for this independent TODO.
- Firebase client config and FCM server credentials must remain tenant-dynamic and admin-managed; worker code must not hardcode secrets or persist them in-repo.
- Web push remains disabled; any attempt to satisfy web reflection through push instead of SSE is a spec violation.
- Public invite metadata and invite push payloads must derive from one canonical preview owner; duplicated ad hoc preview builders are blocked.
- The orchestrator owns integration and validation only; implementation stays with worker slices.

## Approval Request
- **Requested approval:** Reply `APROVADO` to authorize this orchestration plan.
- **Execution authorized by approval:** create the fresh reconcile branches/worktrees and dispatch Wave 1 worker slices for `WS-01` through `WS-04`.
- **Execution not authorized by approval:** promotion to `stage`/`main`, schema redesign beyond the TODO boundary, or web push enablement.
- **Autonomy rule:** once approved, the orchestrator advances through waves without requesting feedback between waves unless a mandatory decision, blocker, or waiver condition appears.

## Plan Completion Guard
- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/fast-follow-invite-push-live-reflection-share-metadata-orchestration-plan.md`
- **Required before approval/execution:** `Overall outcome: go`

## Delivery Guard
- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/fast-follow-invite-push-live-reflection-share-metadata-orchestration-plan.md --require-approved`
- **Required before local implementation or delivery completion claim:** `Overall outcome: go`
- **Blocks delivery when:** any traceability row lacks passed implementation/test evidence, a UI/runtime criterion lacks fresh browser/device evidence, the tenant-admin save/credential flows are not validated locally, or a named artifact is substituted without an approved spec deviation.
