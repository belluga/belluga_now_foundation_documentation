# Store Release Event Programming Navigation Orchestration Plan

## Artifact Identity

- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Superseded`
- **Superseded by:** `foundation_documentation/artifacts/execution-plans/store-release-usability-four-todos-orchestration-plan.md`
- **Created:** `2026-04-22`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`

## Authority Boundary

- The governing TODO defines **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the orchestrator will sequence workers, reconcile worktrees, and validate the reopened SR-D Store Release contract.
- If this plan conflicts with the governing TODO, execution stops until the TODO or this plan is updated and re-approved.
- This plan supersedes only the SR-D execution authority from `store-release-usability-orchestration-plan.md`; it does not reopen SR-A, SR-B, or SR-C unless later validation proves a direct dependency gap.
- Requirement wording in the governing TODO is literal. Replacing a named artifact, UI control, navigation path, runtime target, or validation lane requires an approved row in the Spec Deviation Ledger before execution or delivery can proceed.
- The orchestrator does not own feature implementation. Workers own implementation slices; the orchestrator owns dispatch, reconciliation, conflict resolution when unavoidable, final runtime validation, and evidence consolidation.
- Parallel product/technical alignment may continue while SR-D executes, but new implementation scope must enter through its own tactical TODO and orchestration plan, or through an explicitly approved amendment when it is a direct dependency of SR-D.
- SR-C2 Event description rich-text public rendering is governed separately by `foundation_documentation/artifacts/execution-plans/store-release-event-rich-text-public-rendering-orchestration-plan.md`; it may run in parallel after its own approval, but its evidence cannot substitute for SR-D Programação/navigation evidence.

## Governing TODO Set

| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `SR-D2` | `foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` | Reopened Store Release multi-occurrence event UX, admin authoring, programação location, and public navigation contract. | Can start only after this plan is approved with `APROVADO`. |

## Acceptance Traceability Matrix

| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SR-D2-DOD-01` | Public Home/Event list remains occurrence-first and occurrence-card-only. | Worker WS-D3 Public Flutter | Home/Event occurrence card route behavior | Flutter route/widget implementation and DTO/repository support. | Flutter Home/list route tests plus backend list payload tests. | Playwright `NAV-01` after `bash scripts/build_web.sh ../web-app dev`; shared-android-web behavior, Playwright sufficient unless divergence appears. | planned |
| `SR-D2-DOD-02` | Event detail route can represent selected occurrence context without changing event identity. | Worker WS-D3 Public Flutter | `/agenda/evento/{slug}?occurrence={occurrenceId}` route/query | Flutter route/controller hydration implementation and backend selected occurrence payload support. | Flutter route/controller tests and Laravel selected-occurrence detail tests. | Playwright `NAV-01`, `NAV-11`, and `NAV-12` on final domain. | planned |
| `SR-D2-DOD-03` | The public `Datas` tab is removed from event detail. | Worker WS-D3 Public Flutter | No public `Datas` tab | Flutter tab model removes `Datas` and keeps date navigation inside `Programação`. | Flutter widget tests asserting `Datas` absence. | Playwright `NAV-04` asserts `Datas` never appears in detail tabs. | planned |
| `SR-D2-DOD-04` | Public event detail uses `Programação` as the multi-occurrence navigation surface when the event has programação in at least one occurrence. | Worker WS-D3 Public Flutter | Public `Programação` tab and selector | Flutter detail tab model and selected occurrence state implementation. | Flutter widget/controller tests for tab presence when any occurrence has programação. | Playwright `NAV-02`, `NAV-03`, `NAV-11`, and `NAV-12`. | planned |
| `SR-D2-DOD-05` | The `Programação` section renders a date selector for multi-occurrence events, highlights the selected occurrence/date, and updates the selected occurrence route query when another date is chosen. | Worker WS-D3 Public Flutter | Programação date selector, highlight, route query update | Flutter selector component and route replacement implementation. | Flutter widget/controller tests for selected date highlight and query update. | Playwright `NAV-02` verifies tap, URL, highlight, and content change. | planned |
| `SR-D2-DOD-06` | A selected occurrence without programação renders an empty state inside `Programação` when another occurrence in the event has programação. | Worker WS-D3 Public Flutter | Programação empty state | Flutter selected occurrence empty-state implementation. | Flutter widget tests for selected occurrence with no items while sibling has items. | Playwright `NAV-02` and `NAV-12` negative validations. | planned |
| `SR-D2-DOD-07` | If no occurrence has programação, `Programação` is absent and direct `tab=programming` entry falls back to `Sobre`. | Worker WS-D3 Public Flutter | `tab=programming` fallback to `Sobre` | Flutter tab fallback and route normalization implementation. | Flutter route/widget tests for no-programming fallback. | Playwright `NAV-03` and `NAV-11` negative validations. | planned |
| `SR-D2-DOD-08` | Tenant-admin can create/edit multiple occurrences while preserving the first-occurrence baseline. | Worker WS-D2 Admin Flutter | Tenant-admin occurrence section and add-date FAB/FloatingActionButton | Flutter admin occurrence list/editor implementation. | Flutter admin form tests with local create/update mutation draft. | Playwright admin mutation path creates second occurrence through real UI and verifies backend readback. | planned |
| `SR-D2-DOD-09` | Occurrences no longer expose or persist location override as an approved field. | Worker WS-D1 Backend Events | Removed occurrence location override contract | Laravel request validation/projection update and Flutter admin DTO removal. | Laravel negative tests and Flutter admin tests assert no occurrence location override controls. | Playwright admin mutation verifies no hidden tenant capability or location override path is needed. | planned |
| `SR-D2-DOD-10` | Programação items can optionally reference a location Account Profile that owns a Map POI; free-text location input is not accepted as the source of truth. | Worker WS-D1 Backend Events | Programação item Account Profile/Map POI location ref and schema | Laravel write/read DTOs, projection, and Flutter admin selector support. | Laravel positive and negative tests; Flutter DTO/admin tests. | Playwright `NAV-06` and `NAV-07` verify visible location behavior and absence behavior. | planned |
| `SR-D2-DOD-11` | Programação participant Account Profiles automatically link into the event-level related profile set and the admin UI reflects this reactively. | Worker WS-D2 Admin Flutter | Reactive event related profile chips from programação participants | Laravel auto-link persistence plus Flutter controller/admin UI update. | Laravel auto-link tests and Flutter admin reactive UI tests. | Playwright admin mutation verifies participant selection updates saved event profile set. | planned |
| `SR-D2-DOD-12` | Programação cards render time, title/profile fallback, linked Account Profile avatars/names, and optional location affordance without empty placeholder space when optional data is absent. | Worker WS-D3 Public Flutter | Enriched Programação cards | Flutter card component and DTO parsing implementation. | Flutter widget tests for participants, fallback, location, and absent optional rows. | Playwright `NAV-05`, `NAV-06`, and `NAV-07` positive and negative validations. | planned |
| `SR-D2-DOD-13` | Tapping a programação item location opens the corresponding Map POI. | Worker WS-D3 Public Flutter | Programação location tap to Map POI | Flutter route/navigation implementation to Map POI target. | Flutter widget/navigation tests for location tap payload. | Playwright `NAV-06` taps location and verifies map/POI route. | planned |
| `SR-D2-DOD-14` | `Como Chegar` lists the default event location plus all programação item Account Profile/POI locations. | Worker WS-D3 Public Flutter | `Como Chegar` aggregated address list | Backend address projection or Flutter aggregation from payload, matching approved contract. | Laravel projection tests and Flutter widget/controller tests. | Playwright `NAV-08` and `NAV-09` verify default-only and mixed address lists. | planned |
| `SR-D2-DOD-15` | `Como Chegar` deduplicates repeated locations by canonical Account Profile/POI identity. | Worker WS-D1 Backend Events | Canonical address dedup identity | Laravel dedup projection logic and Flutter rendering of unique rows. | Laravel dedup tests and Flutter `Como Chegar` tests. | Playwright `NAV-10` verifies duplicate POI rows/CTAs do not appear. | planned |
| `SR-D2-DOD-16` | Tests include positive and negative coverage for every optional field/section plus explicit deduplication coverage. | Worker WS-D4 QA Navigation | Complete positive/negative test matrix | Workers add fail-first tests before implementation per slice. | Laravel, Flutter, and Playwright matrix rows explicitly map positive and negative cases. | Final Playwright `NAV-01` through `NAV-12` after current web build. | planned |
| `SR-D2-VAL-01` | Laravel feature/package tests for multi-occurrence create/update/list/detail payloads and selected-occurrence lookup semantics. | Worker WS-D1 Backend Events | Laravel Events feature/package tests | Laravel Events contract implementation. | Safe Laravel runner for focused Events feature/package suite. | n/a backend-only, but public selected occurrence also covered by Playwright `NAV-01`. | planned |
| `SR-D2-VAL-02` | Laravel positive tests for programação item location Account Profile/Map POI references in create/update/detail projections. | Worker WS-D1 Backend Events | Laravel programação location tests | Laravel DTO/projection support for Account Profile/Map POI refs. | Safe Laravel runner focused positive tests. | n/a backend-only; visible behavior covered by Playwright `NAV-06`. | planned |
| `SR-D2-VAL-03` | Laravel negative tests rejecting or ignoring occurrence-level location overrides and rejecting free-text programação locations as source of truth. | Worker WS-D1 Backend Events | Laravel negative validation tests | Laravel request validation and backward-compat handling. | Safe Laravel runner focused negative tests. | Playwright admin path verifies no occurrence location UI path is required. | planned |
| `SR-D2-VAL-04` | Laravel tests for programação item ordering, participant Account Profile resolution, auto-link into event-level related profiles, and absence from event-level programação fields. | Worker WS-D1 Backend Events | Laravel programação ordering and auto-link tests | Laravel write/read contract implementation. | Safe Laravel runner focused ordering/profile tests. | Playwright admin/public journey verifies participant visibility. | planned |
| `SR-D2-VAL-05` | Laravel tests for default event location plus programação item location aggregation and deduplication. | Worker WS-D1 Backend Events | Laravel address aggregation tests | Laravel projection or API payload aggregation implementation. | Safe Laravel runner focused address/dedup tests. | Playwright `NAV-08`, `NAV-09`, and `NAV-10`. | planned |
| `SR-D2-VAL-06` | Flutter route/controller tests for selected occurrence hydration and direct `tab=programming` fallback behavior. | Worker WS-D3 Public Flutter | Flutter route/controller tests | Flutter routing and controller implementation. | Flutter focused route/controller tests. | Playwright `NAV-01`, `NAV-03`, `NAV-11`, and `NAV-12`. | planned |
| `SR-D2-VAL-07` | Flutter widget/controller tests proving `Datas` tab removal and `Programação` date selector behavior. | Worker WS-D3 Public Flutter | Flutter detail widget tests | Flutter tab/selector implementation. | Flutter widget/controller tests. | Playwright `NAV-02` and `NAV-04`. | planned |
| `SR-D2-VAL-08` | Flutter widget tests for selected occurrence with programação, selected occurrence without programação, and event with no programação. | Worker WS-D3 Public Flutter | Flutter Programação state tests | Flutter selected-occurrence state implementation. | Flutter widget tests for all three states. | Playwright `NAV-02`, `NAV-03`, `NAV-11`, and `NAV-12`. | planned |
| `SR-D2-VAL-09` | Flutter DTO/domain/widget tests for programação card participant avatars/names, title/profile fallback, optional location display, and absence of optional placeholders. | Worker WS-D3 Public Flutter | Flutter DTO/domain/card tests | Flutter domain/DTO/card implementation. | Flutter DTO/domain/widget tests. | Playwright `NAV-05`, `NAV-06`, and `NAV-07`. | planned |
| `SR-D2-VAL-10` | Flutter widget/controller tests for `Como Chegar` aggregated address list and deduplication. | Worker WS-D3 Public Flutter | Flutter `Como Chegar` tests | Flutter address aggregation/rendering implementation. | Flutter widget/controller tests for aggregation and dedup. | Playwright `NAV-08`, `NAV-09`, and `NAV-10`. | planned |
| `SR-D2-VAL-11` | Tenant-admin form/navigation tests for single-occurrence fields, transition to occurrence-card list, FAB/add-card flow, occurrence detail edit, programação item participant/location authoring, auto-linked event profiles, save-return-refresh, and chronological validation. | Worker WS-D2 Admin Flutter | Tenant-admin form/navigation tests and FAB | Flutter admin implementation. | Flutter admin widget/navigation tests with create/update mutation draft. | Playwright tenant-admin mutation creates, edits, saves, reopens, and verifies persisted second occurrence. | planned |
| `SR-D2-VAL-12` | Playwright navigation tests for every `NAV-*` row below after `bash scripts/build_web.sh ../web-app dev`. | Worker WS-D4 QA Navigation | Playwright `NAV-01` through `NAV-12` spec | Playwright spec and fixture data implementation. | `node --check`, navigation policy guard, and Playwright list checks before execution. | Final `bash scripts/build_web.sh ../web-app dev` plus Playwright mutation/navigation run against `belluga.space` and tenant domain. | planned |

## Spec Deviation Ledger

| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | No spec deviations approved. | n/a | n/a | n/a |

## Dependency Graph

- `WS-D1 Backend Events` blocks final DTO, repository, and public UI payload work because programação location references, occurrence-location override removal, selected occurrence payloads, and `Como Chegar` aggregation must be stable before Flutter can render the final contract.
- `WS-D2 Admin Flutter` can begin fail-first UI tests in parallel with `WS-D1`, but implementation must reconcile to the backend DTO contract before checkpoint.
- `WS-D3 Public Flutter` can begin fail-first route/widget tests in parallel with `WS-D1`, but final implementation waits for the backend selected-occurrence and programação-location payload shape.
- `WS-D4 QA Navigation` starts Playwright spec design after the TODO matrix is frozen, but final runtime execution waits for backend, admin, public Flutter, analyzer, and web build.
- The previous `store-release-usability-orchestration-plan.md` is superseded for SR-D and cannot be used as delivery authority for the current `Programação`-centered model.

## Orchestration Topology

- **Base branch / commit:** current Store Release checkpoint branch `orchestrator/store-release-usability-wave` at `b6c4e77c0fbb5e0da8f78393dc16870784024a9d`, subject to Wave 0 preflight and dirty-worktree audit.
- **Orchestrator reconciliation branch:** continue on `orchestrator/store-release-usability-wave` only for this approved SR-D supersession; create a fresh branch if preflight finds unrelated scope drift.
- **Principal checkout policy:** the principal checkout stays on the reconciliation branch for analyzer, `bash scripts/build_web.sh ../web-app dev`, and final browser/runtime validation so served Web evidence maps to reconciled code.
- **Worker branches / worktrees:** create disjoint worker worktrees from the approved checkpoint: `worker/srd2-backend-events`, `worker/srd2-admin-flutter`, `worker/srd2-public-flutter`, and `worker/srd2-navigation-qa`.
- **Paused subagent draft policy:** current unapproved local edits from paused subagents are discovery only. Wave 0 must record their touched files and either assign them to the correct worker for ownership or exclude them from reconciliation; the orchestrator must not silently accept them as delivered implementation.

## Checkpoint / Branch Accumulation Control

- **Checkpoint manifest path:** `foundation_documentation/artifacts/checkpoints/store-release-event-programming-navigation-2026-04-22.md`
- **Checkpoint policy:** checkpoints are pushed recovery states plus manifests, not indefinite accumulation branches.
- **Allowed checkpoint statuses:** `wip_checkpoint`, `validated_local_checkpoint`, `promotion_ready_checkpoint`, `superseded_checkpoint`.
- **Same-branch continuation rule:** continue on `orchestrator/store-release-usability-wave` only while the work remains inside this approved plan and the checkpoint manifest records the next exact step. After promotion, supersession, or scope drift, start from the promoted target branch or a fresh/rebased orchestrator branch.
- **Build artifact policy:** generated deploy bundles such as `web-app` are excluded unless an explicit deploy-artifact promotion plan owns them.

## Workstreams

| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-D1 Backend Events` | Laravel Events package requests, services, projections, public/admin DTO payloads, programação location refs, auto-linking, and address dedup. | Governing TODO `SR-D2`; Package-first result says local Events implementation. | Worker checkpoint with Laravel contract implemented and focused tests green. | Safe Laravel runner for create/update/detail, negative validation, auto-link, ordering, location aggregation, and dedup tests. |
| `WS-D2 Admin Flutter` | Tenant-admin event form, occurrence editor, programação item editor, participant auto-link UI, add-date FAB, and admin DTO/request/response mapping. | `WS-D1` DTO contract; Flutter architecture adherence. | Worker checkpoint with admin UI and local mutation tests green. | Focused Flutter admin widget/navigation tests and analyzer for touched files. |
| `WS-D3 Public Flutter` | Public Home/Event navigation, immersive event detail route, Programação tab/date selector/cards, Map POI navigation, and `Como Chegar`. | `WS-D1` payload contract; Flutter route/screen/controller rules. | Worker checkpoint with public route/widget tests green. | Focused Flutter route/controller/widget/DTO tests for `NAV-*` coverage. |
| `WS-D4 QA Navigation` | Playwright mutation/navigation specs, fixture data, final-domain checks, and runtime evidence collection. | `WS-D1`, `WS-D2`, and `WS-D3` reconciled into branch. | Worker checkpoint with Playwright specs syntactically valid and listed before final execution. | `node --check`, web navigation policy guard, Playwright list check, then final Playwright run after web build. |

## Execution Ownership Ledger

| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-D1 Backend Events` | Backend worker subagent | `reconciliation-only` | Worker reports changed Laravel files, fail-first tests, implementation tests, and any migration/backfill note. | Orchestrator merges or cherry-picks, reruns focused Laravel tests, and records evidence in TODO. |
| `WS-D2 Admin Flutter` | Admin Flutter worker subagent | `reconciliation-only` | Worker reports changed admin/domain/DTO files and focused Flutter admin tests. | Orchestrator reconciles with backend contract, reruns focused admin tests and analyzer. |
| `WS-D3 Public Flutter` | Public Flutter worker subagent | `reconciliation-only` | Worker reports changed public route/screen/widget/domain files and focused Flutter public tests. | Orchestrator reconciles with backend contract, reruns focused public tests and analyzer. |
| `WS-D4 QA Navigation` | QA navigation worker subagent | `reconciliation-only` | Worker reports Playwright spec changes, fixture strategy, and list/guard evidence. | Orchestrator builds Web, runs Playwright against final domain, records `NAV-*` evidence, and updates TODO/plan. |

## Execution Waves

Waves are internal orchestration controls, not feedback checkpoints. After this plan is approved, the orchestrator advances autonomously between waves and stops only for a mandatory user decision, scope change, conflict with the governing TODO, real blocker, or explicit validation waiver.

### Wave 0 - Approval And Preflight

- Wait for explicit `APROVADO` before dispatching implementation.
- Re-run readiness, inspect dirty worktrees, and collect paused subagent reports without accepting draft edits as implementation.
- Re-run package-first check only if Wave 0 discovers a new package boundary.
- Create worker worktrees/branches with disjoint ownership boundaries.
- Confirm the governing TODO still has `Current delivery stage: Pending` and no open product/contract gaps.
- Open a non-blocking parallel-intake lane for new points raised by the user. This lane may clarify decisions and draft/update TODOs, but it must not dispatch implementation inside this SR-D plan unless an approved amendment says the new point is a direct SR-D dependency.
- **Gate to next wave:** plan status is updated to `Approved`, preflight is clean or explicitly recorded, and workers have non-overlapping scopes.

### Wave 1 - Backend Contract Foundation

- Dispatch `WS-D1` to write fail-first Laravel tests for selected occurrence, programação location Account Profile/Map POI refs, occurrence-location override rejection, participant auto-linking, and `Como Chegar` address aggregation/dedup.
- Implement backend contract only after failing tests exist.
- **Gate to next wave:** backend worker checkpoint has focused Laravel tests green and no unresolved contract ambiguity.

### Wave 2 - Flutter Admin And Public In Parallel

- Dispatch `WS-D2` for tenant-admin authoring: add-date FAB, occurrence cards/editor, programação participant/location authoring, reactive profile auto-link, save-return-refresh.
- Dispatch `WS-D3` for public navigation: no `Datas` tab, `Programação` date selector, selected occurrence route query, enriched cards, Map POI navigation, `Como Chegar` aggregation/dedup.
- Dispatch `WS-D4` to create/update Playwright specs and fixture strategy against the `NAV-*` matrix without claiming runtime success yet.
- **Gate to next wave:** worker-local Flutter tests pass, analyzer is clean in each worker context where practical, and Playwright specs pass syntax/list/policy checks.

### Wave 3 - Reconciliation And Final Runtime

- Reconcile worker checkpoints into the orchestrator branch, resolving only merge conflicts or minimal integration glue as orchestrator-owned code.
- Run focused Laravel and Flutter suites from the reconciliation branch.
- Run `fvm dart analyze --format machine` as the official Flutter analyzer gate; if analyzer state drifts, follow the documented reset loop.
- Build Web with `bash scripts/build_web.sh ../web-app dev`.
- Run Playwright against the final local-domain target for every `NAV-*` row; if Android/Web behavior diverges, run the ADB integration lane after the required WSL cleanup.
- **Gate to next wave:** all required focused tests, analyzer, Web build, and Playwright `NAV-*` runtime evidence pass or a blocker/waiver is explicitly recorded.

### Wave 4 - Evidence, Docs, And Guards

- Update the SR-D TODO Completion Evidence Matrix with item-specific implementation, test, and runtime evidence.
- Update affected foundation module docs for Events, Flutter Client Experience, Tenant Admin, Agenda, and Map POI contracts.
- Run `todo_completion_guard.py` for SR-D and `orchestration_delivery_guard.py` for this plan after approval.
- Create checkpoint manifest if a pushed recovery checkpoint is made.
- **Gate to completion:** TODO guard and delivery guard return `Overall outcome: go`; otherwise the TODO remains active with concrete blockers.

## Consolidated Validation Matrix

| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| Backend Events contract | Laravel fail-first and passing tests for multi-occurrence create/update/detail, selected occurrence lookup, programação Account Profile/Map POI location refs, occurrence-location override rejection, participant auto-linking, ordering, address aggregation, and dedup. | Worker then reconciliation Laravel safe runner. | Backend worker, orchestrator validates merged result. |
| Tenant-admin visible CRUD and mutation | Flutter admin tests plus Playwright mutation proving real add-date FAB flow, occurrence editor save-return-refresh, programação participant/location authoring, event profile auto-link, and persisted backend readback. | Reconciliation branch and final Web browser/domain. | Admin Flutter worker, QA worker, orchestrator final validation. |
| Public event navigation and tabs | Flutter route/widget tests plus Playwright navigation proving occurrence-first card navigation, no `Datas` tab, `Programação` date selector, selected-date highlight, empty states, and direct `tab=programming` fallback. | Reconciliation branch and final Web browser/domain. | Public Flutter worker, QA worker, orchestrator final validation. |
| Programação cards and Map POI navigation | Flutter DTO/domain/widget tests plus Playwright runtime for participant avatars/names, title fallback, optional location Account Profile/POI display, location absence, and tap to map route. | Reconciliation branch and final Web browser/domain. | Public Flutter worker, QA worker, orchestrator final validation. |
| `Como Chegar` address aggregation | Laravel and Flutter tests plus Playwright `NAV-08`, `NAV-09`, and `NAV-10` for default-only, default plus programação locations, absence filtering, and deduplication. | Reconciliation branch and final Web browser/domain. | Backend/Public workers, QA worker, orchestrator final validation. |
| Cross-stack gates | `fvm dart analyze --format machine`, focused Flutter suites, Laravel safe runner suites, `node --check`, navigation policy guard, Playwright list, Web build freshness hash, and final Playwright run. | Reconciliation branch; Android ADB only if behavior diverges from Web. | Orchestrator executes final gates; workers own fixes for failures in their scopes. |

## Consolidated Delivery Evidence

Fill this section only after execution, before claiming local implementation or delivery completion.

| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |

## Checkpoint Manifest

- **Manifest path:** `foundation_documentation/artifacts/checkpoints/store-release-event-programming-navigation-2026-04-22.md`
- **Checkpoint status:** not created yet
- **Repositories pushed:** none yet
- **Excluded dirty surfaces:** paused subagent draft edits are unaccepted until Wave 0 audit assigns or excludes them
- **Next branch lifecycle step:** after approval, continue same approved SR-D supersession plan or create fresh worker worktrees from the recorded checkpoint

## Runtime Freshness Evidence

Runtime freshness evidence will be recorded after execution because final validation depends on the post-reconciliation Web build. Required fields are branch, commit, `../web-app/main.dart.js` hash, served domain hash, Playwright command, and target domain.

## Risk / Conflict Controls

- The old SR-D implementation and evidence mention `Datas` and occurrence-level location overrides; those are superseded and cannot satisfy current delivery.
- Current unapproved local edits in Flutter tests are treated as paused-worker drafts until Wave 0 assigns ownership or excludes them.
- The orchestrator must not implement backend/admin/public slices except for merge-conflict resolution or minimal reconciliation glue.
- New points discussed during execution are not blockers by default. They become blockers only when they contradict SR-D, change the governing TODO, require a spec deviation, or are necessary for a required validation lane.
- Parallel orchestration of a new point requires a separate approved TODO and plan; otherwise it remains an intake/planning artifact until SR-D delivery reaches its own guard gates.
- The SR-C2 Event rich-text public rendering plan is the approved boundary for the Event description bug once approved; SR-D workers must not absorb that implementation except through explicit reconciliation of shared immersive Event detail files.
- Programação item location is not free text. It must reference an Account Profile whose Map POI supplies the map/address source.
- Backend tests must prevent broad payload acceptance that silently preserves occurrence location overrides.
- Flutter tests must prevent optional UI sections from leaving blank space when participants or location are absent.
- Playwright must run after `bash scripts/build_web.sh ../web-app dev`; code lines, unit tests, widget tests, or old bundle behavior are not sufficient runtime proof for visible behavior.
- If CanvasKit text semantics are insufficient for Playwright locators, the QA worker must add stable semantic keys or route/API corroboration instead of weakening the assertion.
- If Android and Web behavior materially diverge, final validation requires both ADB integration and Playwright; if behavior is shared, Playwright can close the visible runtime lane.

## Approval Request

- **Requested approval:** Reply `APROVADO` to authorize this orchestration plan.
- **Execution authorized by approval:** start Wave 0, dispatch workers for `WS-D1` through `WS-D4`, reconcile worker checkpoints, run final Web build and Playwright `NAV-*` validation, update SR-D evidence, and run completion/delivery guards.
- **Execution not authorized by approval:** promotion to `dev`, `stage`, or `main`; changing TODO scope; bypassing runtime validation; accepting stale `Datas` or occurrence-location evidence; implementing unrelated TODOs; dispatching parallel implementation for new points without their own approved TODO/plan or approved SR-D amendment; or treating the orchestrator as a feature implementation owner.
- **Autonomy rule:** once approved, the orchestrator advances through waves without requesting feedback between waves unless a mandatory decision, scope change, governing TODO conflict, real blocker, or validation waiver condition appears.

## Plan Completion Guard

- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-event-programming-navigation-orchestration-plan.md`
- **Required before approval/execution:** `Overall outcome: go`

## Delivery Guard

- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-event-programming-navigation-orchestration-plan.md --require-approved`
- **Required before local implementation or delivery completion claim:** `Overall outcome: go`
- **Blocks delivery when:** any traceability row lacks passed implementation/test evidence, a UI/runtime criterion lacks fresh Web/browser/device/navigation evidence, divergent Android/Web behavior lacks either lane, a named artifact was substituted without an approved spec deviation, or any implementation row names the orchestrator as owner.

## Execution Log

- `2026-04-22`: Plan created after product approval of the Programação-centered multi-occurrence model and before accepting any paused subagent draft implementation.
