# Store Release Event Rich-Text Public Rendering Orchestration Plan

## Artifact Identity

- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Superseded`
- **Superseded by:** `foundation_documentation/artifacts/execution-plans/store-release-usability-four-todos-orchestration-plan.md`
- **Created:** `2026-04-22`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`

## Authority Boundary

- The governing TODO defines **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the orchestrator will dispatch workers, reconcile worktrees, and validate the reopened SR-C2 Event description public rendering contract.
- The existing SR-C Account Profile evidence remains valid supporting baseline, but it does not close SR-C2 because Account Profile public rendering is not proof of Event `Sobre` rendering.
- If this plan conflicts with the governing TODO, execution stops until the TODO or this plan is updated and re-approved.
- Requirement wording in the governing TODO is literal. Replacing a named artifact, UI control, runtime target, validation lane, or browser evidence requirement requires an approved row in the Spec Deviation Ledger before execution or delivery can proceed.
- The orchestrator does not own feature implementation. Workers own backend, Flutter, and Playwright implementation slices; the orchestrator owns dispatch, reconciliation, conflict resolution when unavoidable, final runtime validation, and evidence consolidation.
- SR-C2 may execute in parallel with SR-D after both plans are approved, but shared immersive Event detail or Playwright files require explicit reconciliation ownership before delivery.

## Governing TODO Set

| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `SR-C2` | `foundation_documentation/todos/completed/TODO-store-release-account-profile-rich-text-fidelity.md` | Reopened Store Release rich-text fidelity contract for Event description public `Sobre` rendering, preserving the completed Account Profile baseline. | Can start only after this plan is approved with `APROVADO`. |

## Acceptance Traceability Matrix

| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SR-C2-DOD-01` | Editing `bio` and `content` in tenant-admin produces public/admin rendering that preserves the approved structure and formatting. | Worker WS-C2C QA Navigation | Account Profile rich-text baseline | Existing SR-C Account Profile implementation remains preserved. | Existing Account Profile Laravel, Flutter, ADB source spec, and Playwright mutation evidence recorded in the TODO. | Existing final Web Playwright mutation evidence recorded for `https://guarappari.belluga.space`; no new SR-C2 runtime required unless regression appears. | passed |
| `SR-C2-DOD-02` | Editing Event description in tenant-admin produces public Event detail `Sobre` rendering that preserves the approved structure and formatting. | Worker WS-C2B Flutter Event Rendering | Tenant-admin Event description editor and public Event `Sobre` | Flutter Event form/detail implementation and, if needed, backend readback alignment. | Fail-first Flutter Event detail rendering tests plus backend readback tests where sanitizer/storage is implicated. | Playwright mutation/navigation after `bash scripts/build_web.sh ../web-app dev` on final tenant domain. | planned |
| `SR-C2-DOD-03` | `bio` and `content` are rendered independently according to capabilities and content presence. | Worker WS-C2C QA Navigation | Account Profile capability-backed rendering baseline | Existing SR-C Account Profile implementation remains preserved. | Existing Account Profile widget and Playwright evidence recorded in the TODO. | Existing final Web runtime evidence recorded in the TODO. | passed |
| `SR-C2-DOD-04` | Legacy plain text with newline breaks renders with faithful line/paragraph structure. | Workers WS-C2B and WS-C2C | Shared safe rich-text canonicalizer baseline | Existing SR-C renderer implementation remains preserved and must not regress while Event rendering is fixed. | Existing rich-text canonicalizer and Account Profile widget tests; add Event regression tests if shared renderer changes. | Existing final Web evidence for Account Profile plus new Event Playwright evidence if shared renderer changes. | passed |
| `SR-C2-DOD-05` | Event description rich text preserves paragraph breaks, explicit line breaks, headings/text styles, bold, italic, strike, lists, blockquotes, and emoji through save, readback, and public rendering. | Workers WS-C2A Backend Events and WS-C2B Flutter Event Rendering | Event sanitizer/readback and public Event `Sobre` rendering | Laravel Event sanitizer/readback changes if needed, Flutter renderer changes if needed. | Positive and negative Laravel/Flutter tests for every approved element and collapsed-formatting regression. | Playwright mutation/navigation proves visible public rendering on final tenant domain after current Web build. | planned |
| `SR-C2-DOD-06` | Backend accepts up to the approved Account Profile long-form limit per field and rejects over-limit values with structured validation. | Worker WS-C2C QA Navigation | Account Profile backend long-form validation baseline | Existing SR-C backend Account Profile implementation remains preserved. | Existing Laravel Account Profile validation evidence recorded in the TODO. | Backend-only baseline; no new browser runtime required unless Account Profile validation code changes. | passed |
| `SR-C2-DOD-07` | Flutter exposes aligned limit guidance before submit and still handles backend `422` errors. | Worker WS-C2C QA Navigation | Account Profile editor guidance baseline | Existing SR-C Flutter Account Profile editor/repository implementation remains preserved. | Existing Flutter editor and repository tests recorded in the TODO. | Existing Account Profile Playwright evidence remains supporting baseline; no new runtime required unless shared editor behavior changes. | passed |
| `SR-C2-DOD-08` | Tests cover Account Profile fields, line breaks, every supported formatting element, long content, over-limit validation, and unsupported markup stripping/rejection. | Worker WS-C2C QA Navigation | Account Profile coverage baseline | Existing SR-C Account Profile test surface remains preserved. | Existing Laravel, Flutter, ADB source spec, and Playwright mutation evidence recorded in the TODO. | Existing final Web mutation evidence recorded in the TODO. | passed |
| `SR-C2-DOD-09` | Tests cover Event description line breaks and every supported formatting element in public rendering, including negative coverage for unsupported markup and collapsed formatting regressions. | Worker WS-C2C QA Navigation | Event description rendering test matrix | Playwright spec and supporting Flutter/Laravel tests added by workers. | Source-owned Flutter/Laravel tests plus Playwright mutation/navigation spec with positive and negative assertions. | Final-domain Playwright after `bash scripts/build_web.sh ../web-app dev`; code lines or widget data inspection cannot substitute. | planned |
| `SR-C2-VAL-01` | Laravel request/validation tests for dedicated Account Profile long-form rich-text constraints. | Worker WS-C2C QA Navigation | Account Profile Laravel baseline | Existing SR-C Laravel validation implementation remains preserved. | Existing Laravel focused suite recorded in the TODO. | Backend-only baseline; no new browser runtime required unless Account Profile backend changes. | passed |
| `SR-C2-VAL-02` | Laravel sanitizer/persistence tests for supported and unsupported markup. | Workers WS-C2A Backend Events and WS-C2C QA Navigation | Rich-text sanitizer/persistence tests | Existing Account Profile sanitizer tests remain preserved; Event sanitizer/readback tests added if backend is implicated. | Laravel safe runner for Account Profile baseline and focused Event tests when changed. | Backend-only for sanitizer; visible Event acceptance covered by Playwright. | passed |
| `SR-C2-VAL-03` | Flutter rich-text editor/model tests for limit guidance and serialized payload behavior. | Worker WS-C2B Flutter Event Rendering | Tenant-admin rich-text editor serialization | Existing Account Profile editor tests remain preserved; add Event form/editor tests if the Event editor path differs. | Focused Flutter editor/model tests. | Supporting evidence only; visible Event acceptance still requires Playwright. | passed |
| `SR-C2-VAL-04` | Flutter widget tests for public Account Profile detail rendering of `bio` and `content`. | Worker WS-C2C QA Navigation | Account Profile public detail baseline | Existing SR-C public Account Profile implementation remains preserved. | Existing Account Profile public detail widget tests recorded in the TODO. | Existing Account Profile final Web evidence remains baseline. | passed |
| `SR-C2-VAL-05` | Flutter tests for tenant-public Event detail rendering of Event description structure, not just serialized HTML data. | Worker WS-C2B Flutter Event Rendering | Tenant-public Event detail rendered structure | Flutter Event detail renderer implementation and stable semantics/keys when browser automation needs them. | Fail-first Flutter tests assert visible structure or semantic markers for line breaks, heading/text style, bold, italic, lists, blockquotes, strike, and absence cases. | Supporting evidence; final visible acceptance still runs through Playwright on final domain. | planned |
| `SR-C2-VAL-06` | Flutter admin preview/readback tests proving whitespace and supported formatting are not collapsed. | Worker WS-C2B Flutter Event Rendering | Tenant-admin Event description readback/preview | Existing Account Profile admin readback remains preserved; Event admin readback tests added if current form/preview can collapse content. | Focused Flutter tenant-admin Event form/readback tests. | Supporting evidence; final visible acceptance still runs through Playwright. | passed |
| `SR-C2-VAL-07` | Playwright mutation/navigation test that edits or creates an Event description through a real tenant-admin/backend mutation path and validates tenant-public Event detail rendering on the final domain after `bash scripts/build_web.sh ../web-app dev`. | Worker WS-C2C QA Navigation | Source-owned Playwright Event rich-text mutation spec | Playwright spec under `tools/flutter/web_app_tests` plus fixture/mutation helpers. | `node --check`, navigation policy guard, Playwright list check, and final mutation lane. | Required final acceptance: `tools/flutter/run_web_navigation_smoke.sh mutation` against `https://belluga.space` and `https://guarappari.belluga.space` after Web build freshness proof. | planned |
| `SR-C2-VAL-08` | Focused analyzer/test gates for touched Flutter surfaces. | Worker WS-C2B Flutter Event Rendering | Official Flutter analyzer and focused suites | Flutter implementation compiles and follows architecture rules. | Focused Flutter tests and `fvm dart analyze --format machine`; reset analyzer state only if required by workspace guidance. | Reconciliation branch test/analyzer evidence before Web build. | planned |
| `SR-C2-MARKER-01` | Guard marker preservation for contextual TODO references to tab, Programacao, map, and empty. | Worker WS-C2C QA Navigation | `Sobre` tab context; no Programacao, map, or empty-state behavior changes in SR-C2 unless directly caused by rich-text rendering. | Workers must keep SR-C2 scoped to Event rich-text public rendering and avoid accidental Programacao/map changes. | Focused tests and Playwright assertions must not rely on unrelated Programacao/map flows; empty rich-text content must not create placeholder gaps. | Final Playwright opens the public Event detail `Sobre` tab only for this TODO; SR-D owns Programacao/map navigation separately. | planned |

## Spec Deviation Ledger

| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | No spec deviations approved. | n/a | n/a | n/a |

## Dependency Graph

- `WS-C2A Backend Events` blocks final public rendering only if investigation proves the Event sanitizer or readback payload is flattening structure before Flutter receives it.
- `WS-C2B Flutter Event Rendering` can begin fail-first tests immediately and owns the public Event `Sobre` renderer even if backend is already correct.
- `WS-C2C QA Navigation` can prepare the Playwright spec in parallel, but final runtime execution waits for backend/Flutter reconciliation, analyzer, and `bash scripts/build_web.sh ../web-app dev`.
- SR-C2 is independent from SR-D product scope, but both touch immersive Event detail and Web navigation tests; reconciliation must prevent worker overlap or stale evidence.

## Orchestration Topology

- **Base branch / commit:** current Store Release checkpoint branch `orchestrator/store-release-usability-wave` at Flutter commit `b6c4e77c0fbb5e0da8f78393dc16870784024a9d`.
- **Orchestrator reconciliation branch:** continue on `orchestrator/store-release-usability-wave` for approved Store Release usability rework; create a fresh branch if preflight finds unrelated scope drift.
- **Principal checkout policy:** the principal checkout stays on the reconciliation branch for analyzer, `bash scripts/build_web.sh ../web-app dev`, and final Playwright validation so served Web evidence maps to reconciled code.
- **Worker branches / worktrees:** create disjoint worker worktrees from the approved checkpoint: `worker/src2-backend-events-rich-text`, `worker/src2-flutter-event-rich-text`, and `worker/src2-navigation-rich-text`.

## Checkpoint / Branch Accumulation Control

- **Checkpoint manifest path:** `foundation_documentation/artifacts/checkpoints/store-release-event-rich-text-public-rendering-2026-04-22.md`
- **Checkpoint policy:** checkpoints are pushed recovery states plus manifests, not indefinite accumulation branches.
- **Allowed checkpoint statuses:** `wip_checkpoint`, `validated_local_checkpoint`, `promotion_ready_checkpoint`, `superseded_checkpoint`.
- **Same-branch continuation rule:** continue on the orchestrator branch only while the work remains inside this approved plan and the checkpoint manifest records the next exact step. After promotion, supersession, or scope drift, start from the promoted target branch or a fresh/rebased orchestrator branch.
- **Build artifact policy:** generated deploy bundles such as `web-app` are excluded unless an explicit deploy-artifact promotion plan owns them.

## Workstreams

| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-C2A Backend Events` | Laravel Events sanitizer, create/update/readback payloads, and Event rich-text tests when backend is implicated. | Governing TODO `SR-C2`; current Event sanitizer/readback discovery. | Worker checkpoint with backend cause ruled out or fixed and focused Laravel tests green. | Safe Laravel runner for focused Event sanitizer/readback tests plus existing Account Profile sanitizer baseline if touched. |
| `WS-C2B Flutter Event Rendering` | Flutter tenant-admin Event description form/readback and tenant-public Event detail `Sobre` renderer/tests. | `WS-C2A` readback findings; Flutter architecture adherence and shared rich-text renderer contract. | Worker checkpoint with fail-first and passing Flutter Event rendering tests. | Focused Flutter Event detail/admin tests and `fvm dart analyze --format machine` for touched surfaces where practical. |
| `WS-C2C QA Navigation` | Playwright mutation/navigation spec, fixture strategy, Web build freshness checks, and final runtime evidence. | Reconciled `WS-C2A` and `WS-C2B`; final Web build. | Worker checkpoint with Playwright spec syntactically valid and listed before final execution. | `node --check`, navigation policy guard, Playwright list check, then final mutation lane after Web build. |

## Execution Ownership Ledger

| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-C2A Backend Events` | Backend worker subagent | `reconciliation-only` | Worker reports changed Laravel files, fail-first tests, implementation tests, and root-cause classification. | Orchestrator reconciles worker checkpoint, reruns focused Laravel tests, and records whether backend contributed to the bug. |
| `WS-C2B Flutter Event Rendering` | Flutter worker subagent | `reconciliation-only` | Worker reports changed Flutter Event/admin/detail files and focused Flutter rendering tests. | Orchestrator reconciles with backend contract, reruns focused Flutter tests and analyzer. |
| `WS-C2C QA Navigation` | QA navigation worker subagent | `reconciliation-only` | Worker reports Playwright spec changes, fixture strategy, policy/list evidence, and expected final-domain assertions. | Orchestrator builds Web, runs final Playwright mutation lane, records Web freshness and visible rendering evidence in the TODO. |

## Execution Waves

Waves are internal orchestration controls, not feedback checkpoints. After this plan is approved, the orchestrator advances autonomously between waves and stops only for a mandatory user decision, scope change, conflict with the governing TODO, real blocker, or explicit validation waiver.

### Wave 0 - Approval And Preflight

- Wait for explicit `APROVADO` before dispatching SR-C2 implementation.
- Re-run readiness, inspect dirty worktrees, and isolate any existing SR-D draft edits from SR-C2 worker ownership.
- Confirm the governing TODO has `Current delivery stage: Pending` and the Account Profile slice is documented as baseline rather than full closure.
- Create worker worktrees/branches with non-overlapping ownership boundaries.
- **Gate to next wave:** plan status is updated to `Approved`, preflight is clean or explicitly recorded, and workers have disjoint scopes.

### Wave 1 - Cause Isolation And Fail-First Tests

- Dispatch `WS-C2A` to verify whether Event sanitizer/storage/readback preserves approved rich-text structure and to add fail-first backend tests if it does not.
- Dispatch `WS-C2B` to add fail-first Flutter Event detail tests proving public `Sobre` structure is rendered visibly, not only serialized as widget data.
- Dispatch `WS-C2C` to draft the Playwright mutation/navigation spec and fixture strategy without claiming runtime success.
- **Gate to next wave:** root cause is assigned to backend, Flutter, or both, and fail-first coverage exists for the visible bug.

### Wave 2 - Implementation

- `WS-C2A` fixes backend sanitizer/readback only if Wave 1 proves backend loss of structure.
- `WS-C2B` fixes Flutter public Event `Sobre` rendering and admin/readback behavior needed for fidelity.
- `WS-C2C` completes Playwright positive and negative assertions for line breaks, heading/text style, bold, italic, strike, lists, blockquotes, emoji, unsupported markup, and collapsed-formatting regression.
- **Gate to next wave:** worker-local focused tests pass, Playwright spec passes syntax/list/policy checks, and no worker claims visible acceptance without final runtime execution.

### Wave 3 - Reconciliation And Final Runtime

- Reconcile worker checkpoints into the orchestrator branch, resolving only merge conflicts or minimal integration glue.
- Run focused Laravel tests for changed Event/Account Profile sanitizer paths.
- Run focused Flutter tests for tenant-admin Event description and tenant-public Event detail, then `fvm dart analyze --format machine`.
- Build Web with `bash scripts/build_web.sh ../web-app dev`.
- Run Playwright mutation/navigation against the final local-domain target. Shared Android/Web behavior can close with Playwright; if behavior diverges, run ADB integration after the required WSL cleanup.
- **Gate to next wave:** all focused tests, analyzer, Web build freshness, and Playwright runtime evidence pass or a blocker/waiver is explicitly recorded.

### Wave 4 - Evidence, Docs, And Guards

- Update the SR-C2 TODO Completion Evidence Matrix with item-specific implementation, test, Web build, and Playwright evidence.
- Update affected module docs for Flutter Client Experience, Events, Tenant Admin, and Account Profile rich-text baseline if shared renderer contracts change.
- Run `todo_completion_guard.py` for SR-C2 and `orchestration_delivery_guard.py` for this plan after approval.
- Create checkpoint manifest if a pushed recovery checkpoint is made.
- **Gate to completion:** TODO guard and delivery guard return `Overall outcome: go`; otherwise the TODO remains active with concrete blockers.

## Consolidated Validation Matrix

| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| Backend Event rich-text contract | Laravel fail-first and passing tests for Event description sanitizer/storage/readback if backend is implicated; preserve Account Profile sanitizer baseline if shared code changes. | Worker then reconciliation Laravel safe runner. | Backend worker, orchestrator validates merged result. |
| Flutter public Event rendering | Flutter tests proving Event `Sobre` renders line breaks, heading/text style, bold, italic, strike, lists, blockquotes, emoji, and absence/unsupported cases as visible structure rather than serialized data only. | Reconciliation branch Flutter test runtime. | Flutter worker, orchestrator validates merged result. |
| Tenant-admin Event description mutation/readback | Flutter admin tests and Playwright mutation path proving Event description can be saved/read back without collapsing approved formatting. | Reconciliation branch and final Web browser/domain. | Flutter worker, QA worker, orchestrator final validation. |
| Final Web visible acceptance | `bash scripts/build_web.sh ../web-app dev`, Web freshness proof, and Playwright mutation/navigation on `https://belluga.space` and `https://guarappari.belluga.space` proving public Event detail `Sobre` visible fidelity. | Final browser/domain served from refreshed `../web-app`. | QA worker prepares; orchestrator executes final validation. |
| Cross-stack gates | Focused Laravel suites, focused Flutter suites, `fvm dart analyze --format machine`, `node --check`, navigation policy guard, Playwright list, Web build freshness hash, and final Playwright run. | Reconciliation branch; ADB only if Android/Web behavior diverges. | Orchestrator executes final gates; workers own fixes for failures in their scopes. |

## Consolidated Delivery Evidence

Fill this section only after execution, before claiming local implementation or delivery completion.

| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |

## Checkpoint Manifest

- **Manifest path:** `foundation_documentation/artifacts/checkpoints/store-release-event-rich-text-public-rendering-2026-04-22.md`
- **Checkpoint status:** not created yet
- **Repositories pushed:** none yet
- **Excluded dirty surfaces:** SR-D paused worker draft edits are excluded from SR-C2 unless explicitly assigned during Wave 0
- **Next branch lifecycle step:** after approval, dispatch SR-C2 workers or execute in parallel with SR-D only through approved plans

## Runtime Freshness Evidence

Runtime freshness evidence will be recorded after execution because final validation depends on the post-reconciliation Web build. Required fields are branch, commit, `../web-app/main.dart.js` hash, served domain hash, Playwright command, and target domain.

## Risk / Conflict Controls

- The manual bug proves current Event description evidence is insufficient; implementation lines, unit tests, and widget constructor data do not prove visible public rendering.
- The final SR-C2 acceptance proof must enter public Event detail and validate visible `Sobre` rendering after `bash scripts/build_web.sh ../web-app dev`.
- Playwright must use a real tenant-admin/backend mutation path for Event description; local-only filtering or fixture-only rendering is not acceptable evidence.
- Account Profile SR-C evidence remains valid baseline but cannot be reused as proof for Event public rendering.
- SR-D and SR-C2 can overlap on immersive Event detail; workers must avoid conflicting edits or declare the conflict before reconciliation.
- If CanvasKit text semantics are insufficient for Playwright locators, the QA worker must add stable semantic markers or corroborating route/API checks without weakening the visible rendering assertion.
- If Android and Web behavior materially diverge, final validation requires both ADB integration and Playwright; if behavior is shared, Playwright can close the visible runtime lane.
- The orchestrator must not implement backend or Flutter feature slices except for merge-conflict resolution or minimal reconciliation glue.

## Approval Request

- **Requested approval:** Reply `APROVADO` to authorize this orchestration plan.
- **Execution authorized by approval:** start Wave 0, dispatch workers for `WS-C2A` through `WS-C2C`, reconcile worker checkpoints, run final Web build and Playwright mutation/navigation validation, update SR-C2 evidence, and run completion/delivery guards.
- **Execution not authorized by approval:** promotion to `dev`, `stage`, or `main`; changing TODO scope; bypassing runtime validation; accepting Account Profile evidence as Event evidence; implementing unrelated TODOs; or treating the orchestrator as a feature implementation owner.
- **Autonomy rule:** once approved, the orchestrator advances through waves without requesting feedback between waves unless a mandatory decision, scope change, governing TODO conflict, real blocker, or validation waiver condition appears.

## Plan Completion Guard

- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-event-rich-text-public-rendering-orchestration-plan.md`
- **Required before approval/execution:** `Overall outcome: go`

## Delivery Guard

- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-event-rich-text-public-rendering-orchestration-plan.md --require-approved`
- **Required before local implementation or delivery completion claim:** `Overall outcome: go`
- **Blocks delivery when:** any traceability row lacks passed implementation/test evidence, a UI/runtime criterion lacks fresh Web/browser/device/navigation evidence, divergent Android/Web behavior lacks either lane, a named artifact was substituted without an approved spec deviation, or any implementation row names the orchestrator as owner.

## Execution Log

- `2026-04-22`: Plan created after manual validation found Event description public `Sobre` rendering flattens tenant-admin rich-text formatting.
