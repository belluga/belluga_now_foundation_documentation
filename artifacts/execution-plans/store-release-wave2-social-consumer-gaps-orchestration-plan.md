# Store Release Wave 2 Social Consumer Gaps Orchestration Plan

## Artifact Identity

- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Local-Complete-ADB-Blocked-NoDevice`
- **Created:** `2026-04-29`
- **Wave label:** `store-release-wave2-social-consumer-gaps`
- **Governing workflows / skills:** `wf-docker-todo-driven-execution-method`, `wf-docker-subagent-orchestration-method`, `audit-protocol-triple-review`
- **Plan approval evidence:** user accepted the split-wave recommendation on 2026-04-29 with "Plano aceito. Pode seguir assim."
- **Implementation approval boundary:** no source-code implementation starts until the governing child TODO is approval-ready and the execution step is explicitly approved under the TODO-driven gate.
- **Local completion evidence:** all non-ADB implementation, focused suites, analyzer, web build, Claude auxiliary review, and triple-audit gates for the active Wave 2 TODO set are complete as of 2026-04-29.
- **ADB status:** `adb devices` returned no attached device on 2026-04-29, so Wave 2D is blocked until a device is attached/reconnected.

## Authority Boundary

- The existing Store Release checkpoint wave is frozen as the predecessor evidence baseline. This plan does not rewrite that history.
- This plan defines how the newly added social/consumer release blockers and reopened invite-share regressions will be sequenced, reviewed, tested, and reconciled.
- The governing TODOs define **WHAT** must be delivered. This plan defines **HOW** orchestration proceeds across branches, workers, reviews, and validation lanes.
- If this plan conflicts with a governing TODO, implementation stops until the TODO or this plan is updated and re-approved.
- `web-app` is a derived runtime bundle and remains ignored as a source-authoring repository. Flutter web evidence must be generated from source-owned Flutter/tools and published to the derived bundle only as a validation step.
- Docker/root submodule pointers are not committed in this wave. Root changes are allowed only for source-owned orchestration/test harness files if a TODO explicitly requires them; submodule pointer updates belong to the promotion lane.
- Invites, favorites, and friends are first-production capabilities in this wave. No backward-compatibility path is required for pre-release invite/favorite/friend data shapes, caches, DTOs, or local fixtures.
- This first-production rule is mandatory review context. Triple-audit packets, Claude CLI review packets, PR notes, and promotion notes must instruct reviewers not to request backward compatibility for invites, favorites, or friends/contact groups unless a governing TODO explicitly reverses the rule.
- Backward-compatibility comments for these first-production capabilities are non-blocking by default and may be ignored or waived during promotion with citation to `project_constitution.md` and the relevant TODO. Escalate only if the comment identifies an independent security, data-integrity, data-loss, tenant-isolation, or release-regression risk unrelated to preserving pre-release behavior.

## Predecessor Baseline

| Repo | Baseline Branch | Baseline Commit / State | Role |
| --- | --- | --- | --- |
| `foundation_documentation` | `docs/store-release-event-hero-home-agenda-pagination` | latest pushed docs checkpoint before this plan | Previous Store Release documentation checkpoint. |
| `flutter-app` | `orchestration/store-release-code-checkpoint-20260429` | `2ea40468` | Local-implemented checkpoint for OTP/social flows before reopened gaps. |
| `laravel-app` | `orchestration/store-release-code-checkpoint-20260429` | `819d0a2` | Local-implemented backend checkpoint before occurrence-target cutover. |
| `belluga_now_docker` | `orchestration/store-release-code-checkpoint-20260429` | `2238eb4` | Orchestration/test checkpoint; no submodule pointer promotion in this wave. |

## Governing TODO Set

| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `W2-HOME` | `foundation_documentation/todos/active/store_release_android/TODO-store-release-home-favorites-refresh-regression.md` | Fix Home Favorites stale state after app-side favorite/unfavorite mutations. | Can start after fail-first test target and flow evidence matrix are confirmed. |
| `W2-INV-SHARE` | `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md` | Reopened invite-share UX bugs: sharing CTA stuck on `Gerando...` and missing `Atualizar lista de amigos` action. | Can start with `W2-HOME` if write scopes stay disjoint. |
| `W2-INV-OCC` | `foundation_documentation/todos/active/store_release_android/TODO-store-release-invites-occurrence-target-migration.md` | Cut over invite target identity to concrete `occurrence_id`; `event_id` is derived parent context only. | Backend audit/fail-first tests first; Flutter propagation follows stable payload contract. |

## Local Completion Snapshot (2026-04-29)

| TODO | Local Status | Final ADB Status |
| --- | --- | --- |
| `W2-HOME` | Implemented, tested, analyzer/web-build passed, triple audit and Claude review resolved. | Blocked: no attached ADB device. |
| `W2-INV-SHARE` | Implemented, tested, analyzer/web-build passed, reopened invite-share audit resolved, external-contact branch audit resolved, Claude review recorded. | Blocked: no attached ADB device. |
| `W2-INV-OCC` | Implemented, tested, docs updated, triple audit resolved/adjudicated with no local code blockers. | Blocked: no attached ADB device. |

## Execution Order

1. **Wave 0 - Rebaseline and branch setup**
   - Confirm clean working trees in `flutter-app`, `laravel-app`, `foundation_documentation`, and root.
   - Create/push wave branches from the predecessor checkpoint:
     - `foundation_documentation: docs/store-release-wave2-social-consumer-gaps-20260429`
     - `flutter-app: orchestration/store-release-wave2-social-consumer-gaps-20260429`
     - `laravel-app: orchestration/store-release-wave2-social-consumer-gaps-20260429`
     - `belluga_now_docker: orchestration/store-release-wave2-social-consumer-gaps-20260429`
   - Record any pre-existing local drift before implementation; do not absorb unrelated edits.
2. **Wave 2A - Flutter social consumer regressions, non-ADB first**
   - Deliver `W2-HOME` and `W2-INV-SHARE` with fail-first Flutter repository/controller/widget tests.
   - Use source-owned Playwright/browser checks only where the route is browser-visible or where web build parity can catch shared Flutter regressions.
   - Run the official analyzer command after implementation hygiene: `fvm dart analyze --format machine`.
   - Run triple audit for the bounded delivery before mixing it with occurrence cutover.
3. **Wave 2B - Invite occurrence target cutover**
   - Backend first: Laravel invite write/read/share/materialize/accept/feed/duplicate/credited-acceptance tests fail first, then implementation.
   - Flutter second: event detail selected occurrence, invite share payloads, feed/received context, and repository DTOs preserve `occurrence_id`.
   - Update module docs after stable contract decisions are implemented.
   - Run triple audit for this TODO before consolidation.
4. **Wave 2C - Consolidation and final non-device gates**
   - Reconcile Flutter + Laravel branches into the wave branches.
   - Run focused suites, analyzer/Pint, source-owned Playwright matrix where applicable, web build only after source is reconciled.
   - Run Claude CLI review as an additional gate only if it is available and returns a response. Important divergences are escalated for user decision.
5. **Wave 2D - Final ADB/device validation**
   - Defer ADB to the end because the connected-device/WSL environment is resource-sensitive.
   - Before ADB, run analyzer cleanup/hygiene and stop unnecessary local processes.
   - Execute only the final runtime paths that cannot be closed by non-ADB coverage.

## Workstreams

| Workstream | Ownership Boundary | Primary TODO | Required Output |
| --- | --- | --- | --- |
| `WS2-A Home Favorites` | Flutter favorites repositories, Home Favorites consumer/controller/widget, focused tests. | `W2-HOME` | Favorite and unfavorite mutations publish repository-owned state/invalidation consumed by Home. |
| `WS2-B Invite Share UX` | Flutter `/convites/compartilhar` controller/screen/widgets, inviteable refresh intent, share CTA state, focused tests. | `W2-INV-SHARE` | Sharing CTA exits `Gerando...` deterministically, errors are recoverable, and `Atualizar lista de amigos` refetches inviteables. |
| `WS2-C Invite Occurrence Backend` | Laravel invite package/services/controllers/requests/projections/share-code paths. | `W2-INV-OCC` | New release invite writes/read models/share codes require or resolve concrete `occurrence_id`. |
| `WS2-D Invite Occurrence Flutter` | Flutter event detail selected-occurrence propagation, invite repositories/DTOs/controllers/screens, received invite context. | `W2-INV-OCC` | Selected occurrence survives event detail -> invite share -> backend payload -> feed/acceptance context. |
| `WS2-E QA and Evidence` | Source-owned Playwright specs, audit packets, completion evidence, final ADB queue. | all | Per-TODO evidence matrices, clean triple reviews, Claude comparison note when available. |

## Dependency Graph

- `WS2-A` and `WS2-B` may run in parallel if file ownership is kept disjoint. Both are non-ADB-first Flutter regressions.
- `WS2-C` blocks final `WS2-D` payload implementation because occurrence identity must be stable in backend contracts before Flutter closes DTO/repository evidence.
- `WS2-E` can design Playwright/test matrices early, but final runtime execution waits for source reconciliation and web build publication.
- Final ADB waits for `WS2-A`, `WS2-B`, `WS2-C`, and `WS2-D` to be locally reconciled and audited.

## Frontend / Consumer Matrix

| Producer / Contract Surface | Expected Consumer | Visible Route / Action | DTO / Repository Boundary | Required Evidence | Waiver |
| --- | --- | --- | --- | --- | --- |
| Favorite mutation state/invalidation | Flutter Home Favorites | tenant-public Home `/` Favorites strip | Favorites repository stream/invalidation consumed by Home controller | Fail-first repository/controller/widget tests; final ADB favorite/unfavorite smoke | none |
| `/contacts/inviteables` refresh/read path | Flutter invite composer | `/convites/compartilhar`, `Atualizar lista de amigos` | Inviteables repository/controller refresh intent | Widget/controller test for refresh action and request/refetch; optional Playwright if route is web-reachable | none |
| Invite share-code generation | Flutter invite share CTA | `/convites/compartilhar`, CTA `Gerando...` -> `Compartilhar` or recoverable error | Invite share repository/controller state | Race/error/re-entry tests proving bounded loading state; final device share smoke | none |
| External contact share branch | Flutter invite composer | `/convites/compartilhar`, compact `Contatos do telefone` entry -> bottom sheet/share action | Contact import classification + app-local share command | Controller/widget tests for native-only unmatched contacts, web exclusion, fail-closed import failure, normalized WhatsApp URI, and system-share fallback; final device share smoke | none |
| Invite direct create/write payload | Laravel + Flutter invite flow | event detail selected occurrence -> invite send/share | Flutter invite request DTO + Laravel occurrence-target resolution | Laravel fail-first tests; Flutter repository payload tests | none |
| Invite share-code materialization/acceptance | Laravel + Flutter app/web continuation | invite code resolve/accept flow | Share preview/materialize/accept DTOs | Laravel tests for preserved `occurrence_id`; Flutter continuation tests; Playwright web/app handoff only if route/browser surface is applicable | none |
| Invite feed/read projection | Flutter received invite/feed context | invite inbox/feed/received invite cards | Feed DTO/repository/controller | Backend projection tests + Flutter widget/controller tests showing occurrence date/time/context | none |

## Task-Derived Test Matrix

| Task / Behavior | Lowest-Level Fail-First Test | Consumer / Flow Evidence | Playwright / Browser Evidence | ADB / Device Evidence | Status |
| --- | --- | --- | --- | --- | --- |
| Favorite mutation refreshes Home Favorites | Repository stream/invalidation test starts with stale Home state after favorite. | Home Favorites controller/widget re-renders without route restart. | Web build passed; Playwright runner unavailable in source repo. | Favorite in app, return to Home, item appears without restart. | `local-passed / ADB-blocked-no-device` |
| Unfavorite mutation refreshes Home Favorites | Repository stream/invalidation test starts with removed item still visible. | Home Favorites widget removes/updates item from repository-owned state. | Web build passed; Playwright runner unavailable in source repo. | Unfavorite in app, return to Home, item disappears/updates. | `local-passed / ADB-blocked-no-device` |
| Invite share CTA does not stay stuck on `Gerando...` | Controller test simulates share generation success/error/cancel and asserts loading clears. | Widget test verifies CTA label/state moves to `Compartilhar` or recoverable error and route re-entry resets state. | Web build passed; Playwright runner unavailable in source repo. | Open invite share screen and verify CTA is not permanently disabled after generation attempt. | `local-passed / ADB-blocked-no-device` |
| Inviteable refresh action exists and refetches | Controller/repository test asserts explicit refresh triggers new inviteables request. | Widget test verifies `Atualizar lista de amigos` is visible, tappable, loading-bounded, and does not race with send/share. | Web build passed; Playwright runner unavailable in source repo. | Tap refresh on device and verify list/status updates without app restart. | `local-passed / ADB-blocked-no-device` |
| External contact branch is separate and dispatches share | Controller test asserts unmatched native-only contacts, matched-contact exclusion, web exclusion, and fail-closed import classification. | Widget test verifies compact entry, bottom sheet, no extra `Convidar` rows, normalized `wa.me/5527...` launch, invite URL payload, and system-share fallback. | Web runtime exclusion covered by controller test; web build passed. | Tap native external-contact share and verify WhatsApp/system share sheet opens. | `local-passed / ADB-blocked-no-device` |
| Direct invite writes require concrete occurrence | Laravel test fails when invite create omits occurrence; single-occurrence events resolve and persist occurrence. | Flutter repository payload test includes selected `occurrence_id`. | n/a unless direct invite flow is browser-enabled; current release expectation is app-first. | Send invite for selected occurrence on device. | `local-passed / ADB-blocked-no-device` |
| Duplicate prevention and credited acceptance are occurrence-scoped | Laravel tests allow different occurrences and block same occurrence duplicates without using `event_id` as target identity; credited acceptance does not supersede another occurrence. | Flutter received/context tests distinguish two dates of the same event. | Optional web landing/readback if share-code flow is browser-visible. | Accept one occurrence invite and verify other occurrence remains distinct. | `local-passed / ADB-blocked-no-device` |
| Share-code create/materialize/accept preserves occurrence | Laravel share-code tests assert `occurrence_id` survives create, preview/materialize, and accept. | Flutter continuation/repository tests preserve selected occurrence through invite code flow. | Web build passed; no source-owned Playwright runner available. | Share/accept code for one occurrence and verify feed/acceptance context. | `local-passed / ADB-blocked-no-device` |
| Invite feed/read model renders occurrence context | Backend projection test fails when date/time/context is absent. | Flutter widget/controller test renders occurrence date/time identity in feed/received invite. | Web build passed; browser smoke runner unavailable in source repo. | Received invite context shows the selected occurrence. | `local-passed / ADB-blocked-no-device` |

## Audit and Review Cadence

- Each TODO delivery gets its own bounded package before consolidation.
- Triple audit is per TODO/delivery, not only after the combined wave.
- Dispatch packages must include:
  - current TODO,
  - touched diff summary,
  - task-derived test matrix,
  - Frontend / Consumer Matrix,
  - validation evidence,
  - explicit zero-backward-compatibility premise for first-production social capabilities,
  - explicit ADB-deferred or ADB-blocked rows.
- Claude CLI is an auxiliary gate only when available and responsive. If Claude and the triple audit materially diverge, Delphi escalates the divergence before changing direction.
- Review findings that ask for first-production backward compatibility are classified as `out-of-scope/non-blocking` unless they also contain an independent launch-risk argument under the authority boundary above.
- Final wave closure includes a comparison note: which findings from triple audit were more release-relevant than Claude, which Claude findings were unique/relevant, and which were non-blocking or redundant.

## Branch and Checkpoint Policy

- Commit and push after each bounded TODO delivery, not only at final consolidation.
- Branches are consolidation surfaces, not parking lots for untracked local work.
- No source-owned implementation can remain only local after a delivery checkpoint.
- `web-app` generated output is not committed as a source artifact in this wave.
- Root submodule pointer changes are not committed until the promotion lane explicitly owns them.

## Final Device Phase Guard

Before final ADB/device execution:

1. Run focused non-device tests and source-owned browser checks first.
2. Run Flutter analyzer hygiene if analyzer state has drifted.
3. Stop unnecessary dev servers/processes.
4. Execute the smallest ADB matrix that covers unreproducible app-only behavior.
5. If WSL/device fails, record the exact blocked row and preserve all non-ADB completed evidence.

## Final ADB Status (2026-04-29)

- Command: `adb devices`
- Result: no attached devices listed.
- Classification: operational blocker, not a source-code blocker.
- Rows blocked until device reconnect:
  - Home favorite/unfavorite refresh in the running app.
  - Invite share CTA generates a selected-occurrence share link on a real backend session.
  - `Atualizar lista de amigos` imports refreshed device contacts and updates the list without route restart.
  - Unmatched local contact branch opens WhatsApp/system share sheet.
  - Occurrence-scoped invite/presence smoke for one occurrence without collapsing another occurrence.

## Claude vs Triple Audit Comparison

- Claude CLI for the external-contact branch returned no release blockers and only non-blocking notes.
- Triple audit was more release-relevant for this delta: it found three concrete blockers missed by Claude (`ELEGANCE-001` fail-open import classification, `ELEGANCE-002` invalid local-BR `wa.me` normalization, and `TQ-01` label-only share-action coverage).
- The triple-audit findings were valid and were fixed. Round 02 returned zero findings across elegance, performance, and test-quality lanes.
- Claude's unique notes remain non-blocking debt: a pre-existing unused `friendsRepository` constructor parameter and optional coverage for hiding external contacts when `shareUri == null`.

## Exit Criteria

- `W2-HOME`, `W2-INV-SHARE`, and `W2-INV-OCC` have completed evidence rows for every acceptance criterion.
- Focused Laravel/Flutter tests pass for touched surfaces.
- `fvm dart analyze --format machine` passes or unrelated pre-existing diagnostics are isolated with evidence.
- Source-owned Playwright/browser checks are run for browser-visible paths or explicitly marked non-applicable with rationale.
- Triple audit per TODO has no unresolved blocking findings.
- Claude CLI review is recorded when available; important divergence is resolved or escalated.
- Any audit, Claude, PR, or promotion comment requesting backward compatibility for invites, favorites, or friends/contact groups is either absent or explicitly waived as out of scope under the first-production rule.
- Final ADB rows are explicitly blocked because no ADB device is attached; next exact step is reconnecting a device until `adb devices` lists a target, then running the Wave 2D rows above.
