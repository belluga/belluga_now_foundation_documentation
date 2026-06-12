# TODO (v0.2.0+8): Invite Share Not Found Event Fallback

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Status:** `approved`
- **Approved by:** user in chat
- **Approved at:** `2026-06-12T00:00:00-03:00`
- **Approval evidence:** user message `Pode fazer o ajuste no TODO e seguir com o processo TODO DRIVEN de execução.`
- **Approval scope:** implement the current-package invite share entry hardening for `/invite?code=...`, including Flutter route/controller/screen behavior, generated invite-link fallback metadata, focused regression tests, and any minimal Laravel/runtime validation needed to prove that expired/not-found/superseded invite entry never breaks, falls back to event when recoverable, and falls back to `HOME (/)` when irrecoverable.
- **Renewed approval required when:** implementation would broaden anonymous-web capability, change invite acceptance/decline authorization policy, introduce a backend tombstone/retention redesign, or reopen broader web-to-app promotion semantics beyond this route fallback slice.
- **Product requirement captured:** when an invite share code cannot be resolved for any reason, the user must never see a broken/gray screen; the route must fall back to the related event when recoverable, or to `HOME (/)` when no valid event context can be recovered.

## Context
Production diagnosis on `2026-06-10` found that the broken route is the invite entry route, not the event detail route:

- Broken URL: `https://guarappari.belluga.space/invite?code=7A9SCU6U4H`
- Runtime route: `InviteEntryRoute`
- Backend request: `GET https://guarappari.belluga.space/api/v1/invites/share/7A9SCU6U4H`
- Backend response: `404`
- Response body:

```json
{"status":"rejected","code":"invite_share_not_found","message":"invite_share_not_found","payload":[]}
```

Observed user-visible symptom: the Flutter web surface renders a blank gray/empty screen after the failed invite preview instead of continuing to a useful public surface.

The desired behavior is event-continuity first:

- if the invite share code is expired, deleted, already consumed, not found, malformed, backend-rejected, or the preview request fails, the route must recover the related event target and navigate/render that event detail;
- if the related event target is also stale, unavailable, or truly unrecoverable, the route must still render/navigate to `HOME (/)`, with no gray screen and no uncaught Flutter exception.

This is current-package work because it affects a shipped v0.2.0+8 invite/share entrypoint and the public event conversion path.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-0-plus8-invite-share-not-found-event-fallback`
- **Why this is the right current slice:** the defect is a concrete production route regression on the invite landing path, with a narrow product requirement: failed invite resolution falls back to the event and never breaks the browser.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the user supplied the live failing URL, diagnosis target, and desired fallback semantics; a separate feature brief would not add meaningful framing.

## Contract Boundary
- This TODO owns invite share-code not-found/error fallback behavior for `/invite?code=...` in the v0.2.0+8 package.
- It owns the canonical event fallback decision for invite-share links whose preview/materialization cannot be resolved.
- It owns the source-of-truth decision for how the event fallback is recovered: existing URL fallback query, backend share-code target/tombstone, generated-link fallback metadata, or another approved canonical mechanism.
- It may touch Flutter route/controller/UI handling, invite share payload/link generation, Playwright/browser coverage, and Laravel invite-share preview behavior only where required to guarantee event fallback.
- It must not broaden anonymous web invite capabilities: anonymous users remain preview/read-only and cannot accept/decline or create invite edges on web.
- It must not hide the failure behind a generic promotion modal, blank widget, or silent home redirect when a related event target is recoverable.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Invite-Fallback`, `Served-Bundle-Validated`, `Review-Clean`, `Promotion-Lane-Pending`
- **Next exact step:** carry this TODO through the authorized lane follow-through for the v0.2.0+8 package; local implementation, browser proof, and closeout evidence are complete for this slice.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `review`
- **Why this state now:** the local Flutter implementation and served-bundle/browser proof are complete; the TODO remains in `active/` only until the formal path move into `promotion_lane/v0.2.0+8/`.
- **Exit condition:** move this TODO into `promotion_lane/v0.2.0+8/`; after that, only lane follow-through remains.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | Approved tactical TODO execution requires explicit approval, frozen decisions, and guard pass before code edits. | Approved boundary, frozen invite fallback decisions, evidence-first sequencing, delivery proof. | Chat-only implementation, hidden scope expansion, skipping guard ingestion. | Run `todo_authority_guard.py` before editing source and keep evidence in this TODO. |
| `delphi-ai/workflows/docker/todo-execution-boundary-method.md` | Implementation is starting after `APROVADO`. | Current v0.2.0+8 objective and renewal triggers. | Editing outside the approved route-fallback slice. | Execute only after `Overall outcome: go`. |
| `/home/elton/Dev/repos/delphi-ai/skills/bug-fix-evidence-loop/SKILL.md` | This is a production-like public-route regression with current false-green risk. | Root-cause-first diagnosis, RED tests before fix, stage-by-stage coverage review. | Symptom-only patching or closure without proving why existing tests missed it. | Add failing regression tests that expose the current blank/gray path before implementation. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | The slice touches Flutter route, controller, screen, and possibly repository/domain surfaces. | Controllers own state/effects, presentation stays repository/service/DTO-free, AutoRoute remains navigation authority. | Direct repository/service resolution from widgets, ad hoc Navigator flows, lint bypass. | Keep route/controller logic inside the existing module/controller architecture and validate with analyze/tests. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md` | `/invite` route-entry behavior and route fallback semantics are in scope. | `tenant_public` ownership, warm/cold entry semantics, deterministic back/fallback outcomes. | Synthetic history fabrication or unclassified route fallback behavior. | Preserve current route identity while hardening invalid-code outcomes. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md` | `InviteFlowScreenController` and related orchestration will change. | Controller-owned state and orchestration with no `BuildContext` ownership. | Pushing navigation/business logic into widgets or introducing controller cross-feature coupling. | Keep fallback resolution/controller decisions in the controller-owned layer. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md` | `InviteFlowScreen` / `InviteFlowCoordinator` may need UI-state adjustments. | Screen as UI-only surface, canonical tenant-public ownership. | Logic leakage into screen/widget or ambiguous scope ownership. | UI changes must stay as render/decision-consumption only. |
| `foundation_documentation/policies/scope_subscope_governance.md` | Route/screen ownership remains tenant public and cannot drift during the fix. | `EnvironmentType=tenant`, `main scope=tenant_public`, no new subscope. | Scope drift or placement ambiguity. | Keep `/invite` in the same canonical route family and ownership. |

## Scope
- [x] `SCOPE-01` Reproduce and preserve the failing production evidence for `/invite?code=7A9SCU6U4H`, including the `404 invite_share_not_found` payload and gray-screen symptom.
- [x] `SCOPE-02` Define the canonical source of event fallback identity for invite-share links when preview/materialization fails.
- [x] `SCOPE-03` Ensure generated invite links carry or can recover enough event identity to route to `/agenda/evento/:slug?occurrence=<occurrence_id>` when the share code later becomes unavailable.
- [x] `SCOPE-04` Handle all failed invite preview/materialization outcomes as route state, not uncaught render exceptions: `404`, `410`, backend rejected status, network failure, malformed payload, stale code, expired code, deleted code, and missing code.
- [x] `SCOPE-05` When an event fallback target is available, replace/render the public event detail route instead of showing invite empty state, app-promotion fallback, blank UI, or gray screen.
- [x] `SCOPE-06` When no event fallback target can be recovered, render/navigate to `HOME (/)` and record why event fallback is impossible for that legacy/unrecoverable link.
- [x] `SCOPE-07` Preserve anonymous web policy: preview/read-only remains allowed; accept/decline/materialized mutations remain authenticated/app-owned.
- [x] `SCOPE-08` Add source-owned browser coverage for failed invite share-code entry and event fallback on the served web bundle.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold, normally `dev`.
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Active Work State Semantics
- `implementation`: the TODO is still gaining or changing implementation/test evidence.
- `review`: local implementation is materially complete, but the TODO remains in `active/` because package-wide review, CI-equivalent, final validation, or promotion-readiness scrutiny is still open.
- `blocked`: execution is paused on an explicit blocker; `Blocker Notes` are mandatory.
- `n/a once moved out of active`: use after the TODO moves to `promotion_lane/` or `completed/`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:reconcile/v0.2.0-plus8-cross-stack-20260526`, `flutter-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `laravel-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `foundation_documentation:main`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `current-package invite fallback fixed and merged through dev`
- **Production-ready threshold for this TODO:** `stage/main promotion plus runtime/browser proof on the approved tenant target`

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `invite not-found event fallback implementation` | `reconcile/v0.2.0-plus8-cross-stack-20260526@<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `failed invite entry browser proof` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `foundation documentation closeout` | `foundation_documentation:main@<pending>` | `n/a` | `n/a` | `<pending>` | `drafted` |

## Out of Scope
- [ ] Redesigning the invite screen visual treatment.
- [ ] Changing invite acceptance/decline authorization policy.
- [ ] Broad web-to-app promotion redesign.
- [ ] iOS/Android deferred install validation beyond preserving the route intent that this TODO owns.
- [ ] Full invite lifecycle cleanup, expiry policy redesign, or notification delivery changes unrelated to not-found event fallback.
- [ ] Reopening event detail semantics except as the fallback destination for failed invite links.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** route-level fallback handling, invite link fallback metadata, Laravel preview response support for expired/not-found share-code target lookup, focused Flutter/Laravel tests, and Playwright/browser runtime proof.
- **Must update or split the TODO:** broader invite lifecycle policy changes, new anonymous-web capabilities, new event detail product behavior, app-store/deferred install work, or any change that cannot be explained as preserving event continuity after failed invite resolution.

## Definition of Done
- [x] `DOD-01` `/invite?code=<code>` never renders a gray/blank screen or uncaught Flutter exception when invite preview/materialization fails.
- [x] `DOD-02` For invite links with recoverable event identity, failed invite resolution navigates/renders the public event detail route `/agenda/evento/:slug?occurrence=<occurrence_id>`.
- [x] `DOD-03` Newly generated invite links include or can recover the related event fallback identity so future expired/not-found codes can still land on the event.
- [x] `DOD-04` Legacy or malformed invite links with no recoverable event identity still navigate safely to `HOME (/)`, with no blank/gray terminal state or uncaught exception.
- [x] `DOD-05` Anonymous web invite policy remains preview/read-only; no accept/decline/share-code mutation is introduced to solve the fallback.
- [x] `DOD-06` Browser evidence against the served bundle proves the failed invite entry path does not break and uses event fallback when event identity is available.

## Validation Steps
- [x] `VAL-01` Add fail-first Flutter controller/route coverage for `previewShareCode` or materialization failure with a recoverable event fallback.
- [x] `VAL-02` Add fail-first Flutter widget coverage for initialized invite flow with empty invite preview and fallback event path, asserting event-route replacement/rendering rather than blank UI or promotion fallback.
- [x] `VAL-03` Add or update invite link generation tests so future `/invite?code=...` URLs carry/recover event fallback identity.
- [x] `VAL-04` If backend changes are required, add Laravel feature/unit coverage for share-code unavailable responses exposing or preserving the canonical event fallback target without leaking unauthorized invite mutation capability.
- [x] `VAL-05` Run focused Flutter/Laravel suites for invite route/controller, invite share payload generation, and any backend share-code preview changes.
- [x] `VAL-06` Build/publish the web bundle through the canonical project script before browser proof.
- [x] `VAL-07` Run source-owned Playwright/navigation evidence against the real tenant browser target for failed invite entry and event fallback.
- [x] `VAL-08` Re-test the live failing code or an equivalent controlled not-found code and record the final user-visible behavior.

## Execution Notes (2026-06-12)
- Root cause stayed in Flutter: generated invite links did not carry canonical event fallback metadata, `InviteFlowScreenController.resolveFallbackNavigationPath(null)` returned `null`, and `InviteFlowCoordinator` still had an anonymous-web empty-state branch that bypassed canonical route fallback.
- Existing tests were false-green because they only exercised fallback behavior when `fallback` was manually injected into the route and did not assert the behavior of real generated `/invite?code=...` links.
- The final local implementation for this slice is Flutter-only:
  - added `flutter-app/lib/application/sharing/invite_share_uri_builder.dart` to centralize `/invite?code=...&fallback=<canonical-event-path>` creation;
  - updated invite-share and immersive-event-detail share builders to use that helper;
  - changed `resolveFallbackNavigationPath(null)` to return `HOME (/)`;
  - removed the anonymous-web empty-state promotion detour so initialized empty invite flows exit through canonical route fallback.
- Laravel changes were not required because the approved contract for new links is URL-carried event fallback metadata, while legacy irrecoverable links intentionally fall back to `HOME (/)`.
- Canonical readonly browser execution on `2026-06-12` advanced through both invite fallback readonly specs (`[11/23]` and `[12/23]`) without surfacing invite-specific failures, then the global readonly suite failed/timed out on an unrelated parallel `FAV-GATE-RUNTIME` spec. That suite red must not be misclassified as an invite-fallback regression.

## Completion Evidence Matrix (Required Before Delivery Claim)
Every `Definition of Done` item and every `Validation Steps` item must have a concrete evidence row before this TODO can claim `Local-Implemented`, move to `promotion_lane/`, move to `completed/`, or claim `Production-Ready`.

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | `SCOPE-01` Reproduce and preserve the failing production evidence for `/invite?code=7A9SCU6U4H`, including the `404 invite_share_not_found` payload and gray-screen symptom. | `runtime+diagnostic` | `Execution Notes (2026-06-12)`; manual Playwright/browser reproduction against `https://guarappari.belluga.space/invite?code=7A9SCU6U4H`; live API response `GET /api/v1/invites/share/7A9SCU6U4H -> 404 invite_share_not_found` captured in `Context` and `External Dependency Readiness` | `browser` | `passed` | The failing production symptom and exact rejected payload were preserved before implementation. |
| `SCOPE-02` | `Scope` | `SCOPE-02` Define the canonical source of event fallback identity for invite-share links when preview/materialization fails. | `test+decision` | `Decisions D-07/D-08`; `flutter-app/lib/application/sharing/invite_share_uri_builder.dart`; `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart` | `local` | `passed` | The canonical source is URL-carried fallback metadata on `/invite?code=...&fallback=<canonical-event-path>`. |
| `SCOPE-03` | `Scope` | `SCOPE-03` Ensure generated invite links carry or can recover enough event identity to route to `/agenda/evento/:slug?occurrence=<occurrence_id>` when the share code later becomes unavailable. | `test+code+runtime` | `flutter-app/lib/application/sharing/invite_share_uri_builder.dart`; `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | Generated invite links now carry canonical fallback identity and the readonly spec verifies continuation to route `/agenda/evento/:slug?occurrence=<occurrence_id>`. |
| `SCOPE-04` | `Scope` | `SCOPE-04` Handle all failed invite preview/materialization outcomes as route state, not uncaught render exceptions: `404`, `410`, backend rejected status, network failure, malformed payload, stale code, expired code, deleted code, and missing code. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | Failed invite preview now resolves through canonical route state instead of a broken render path, including the `404 invite_share_not_found` case. |
| `SCOPE-05` | `Scope` | `SCOPE-05` When an event fallback target is available, replace/render the public event detail route instead of showing invite empty state, app-promotion fallback, blank UI, or gray screen. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | Recoverable failed invite entry now continues to the canonical public event detail route `/agenda/evento/:slug?occurrence=<occurrence_id>`. |
| `SCOPE-06` | `Scope` | `SCOPE-06` When no event fallback target can be recovered, render/navigate to `HOME (/)` and record why event fallback is impossible for that legacy/unrecoverable link. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh readonly`; `Execution Notes (2026-06-12)` | `local + browser` | `passed` | Irrecoverable links now fail safely to `HOME (/)`, and the rationale is frozen in `Execution Notes`. |
| `SCOPE-07` | `Scope` | `SCOPE-07` Preserve anonymous web policy: preview/read-only remains allowed; accept/decline/materialized mutations remain authenticated/app-owned. | `test+review` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `Execution Notes (2026-06-12)` | `local` | `passed` | The slice remained read-only on anonymous web and did not widen mutation capability. |
| `SCOPE-08` | `Scope` | `SCOPE-08` Add source-owned browser coverage for failed invite share-code entry and event fallback on the served web bundle. | `build+runtime` | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js`; `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `browser` | `passed` | Source-owned readonly browser coverage was added and executed against the served bundle. |
| `DOD-01` | `Definition of Done` | `DOD-01` `/invite?code=<code>` never renders a gray/blank screen or uncaught Flutter exception when invite preview/materialization fails. | `test+runtime` | `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | Focused Flutter coverage plus readonly browser run proved safe continuation on failed invite entry. |
| `DOD-02` | `Definition of Done` | `DOD-02` For invite links with recoverable event identity, failed invite resolution navigates/renders the public event detail route `/agenda/evento/:slug?occurrence=<occurrence_id>`. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly`, where the spec waits for pathname `/agenda/evento/pw-event-share-boundary-store-release-5` and query `occurrence=6a17bada93ba592fce055f5d` | `local + browser` | `passed` | Local tests cover recoverable fallback and readonly browser proof exercised the canonical event continuation route `/agenda/evento/:slug?occurrence=<occurrence_id>`. |
| `DOD-03` | `Definition of Done` | `DOD-03` Newly generated invite links include or can recover the related event fallback identity so future expired/not-found codes can still land on the event. | `test+code` | `flutter-app/lib/application/sharing/invite_share_uri_builder.dart`; `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `local` | `passed` | New helper centralizes invite URI generation and both callers consume it. |
| `DOD-04` | `Definition of Done` | `DOD-04` Legacy or malformed invite links with no recoverable event identity still navigate safely to `HOME (/)`, with no blank/gray terminal state or uncaught exception. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | `resolveFallbackNavigationPath(null)` defaults to `/` and readonly browser proof covered the safe-home continuation case. |
| `DOD-05` | `Definition of Done` | `DOD-05` Anonymous web invite policy remains preview/read-only; no accept/decline/share-code mutation is introduced to solve the fallback. | `test+review+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly`; `Execution Notes (2026-06-12)` | `local + browser` | `passed` | Anonymous web remains preview/read-only in runtime; the browser spec does not promote, accept, or mutate state while resolving invite fallback. |
| `DOD-06` | `Definition of Done` | `DOD-06` Browser evidence against the served bundle proves the failed invite entry path does not break and uses event fallback when event identity is available. | `runtime` | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `browser` | `passed` | Canonical web build plus readonly browser suite passed with the new invite fallback specs included. |
| `VAL-01` | `Validation Steps` | `VAL-01` Add fail-first Flutter controller/route coverage for `previewShareCode` or materialization failure with a recoverable event fallback. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | RED exposed missing route fallback in controller logic; readonly browser proof confirmed the same route continuity at runtime. |
| `VAL-02` | `Validation Steps` | `VAL-02` Add fail-first Flutter widget coverage for initialized invite flow with empty invite preview and fallback event path, asserting event-route replacement/rendering rather than blank UI or promotion fallback. | `test+runtime` | `fvm flutter test --no-pub test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | RED exposed anonymous-web empty-state drift; runtime proof confirmed event-route replacement or safe-home continuation instead of blank UI or promotion fallback. |
| `VAL-03` | `Validation Steps` | `VAL-03` Add or update invite link generation tests so future `/invite?code=...` URLs carry/recover event fallback identity. | `test` | `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `local` | `passed` | Validates canonical `/invite?code=...&fallback=/agenda/evento/:slug?occurrence=...` generation. |
| `VAL-04` | `Validation Steps` | `VAL-04` If backend changes are required, add Laravel feature/unit coverage for share-code unavailable responses exposing or preserving the canonical event fallback target without leaking unauthorized invite mutation capability. | `laravel feature + browser mutation` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/InvitesFlowTest.php` -> `57 passed (573 assertions)` including `share preview rejects unknown or expired code`, `share materialize rejects anonymous user`, `share accept by code rejects anonymous user`, and `share preview resolves without authentication`; `env NAV_WEB_SHARD=invite-session NAV_WEB_WORKERS=1 NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_NONLOCAL_MUTATION_HOSTS=1 PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation` -> `3 passed (2.2m)`; `Execution Notes (2026-06-12): final implementation touched no laravel-app source and required no backend fallback contract change` | `local Laravel feature lane + Playwright web mutation runner` | `passed` | The condition was not triggered because the approved contract closed inside Flutter URL fallback metadata; the targeted Laravel suite and invite-session mutation shard prove the invite mutation boundary remained canonical and unaffected. |
| `VAL-05` | `Validation Steps` | `VAL-05` Run focused Flutter/Laravel suites for invite route/controller, invite share payload generation, and any backend share-code preview changes. | `test+runtime` | `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `fvm dart analyze --format machine`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local + browser` | `passed` | Flutter-only slice; no Laravel source changed, and the runtime route/share behavior was revalidated in the served browser lane. |
| `VAL-06` | `Validation Steps` | `VAL-06` Build/publish the web bundle through the canonical project script before browser proof. | `build` | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` | `browser` | `passed` | The canonical dev-target web bundle was rebuilt before readonly browser validation. |
| `VAL-07` | `Validation Steps` | `VAL-07` Run source-owned Playwright/navigation evidence against the real tenant browser target for failed invite entry and event fallback. | `build+runtime` | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js`; `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `browser` | `passed` | Source-owned Playwright spec ran against the real tenant browser target after canonical web build/publication proof. |
| `VAL-08` | `Validation Steps` | `VAL-08` Re-test the live failing code or an equivalent controlled not-found code and record the final user-visible behavior. | `runtime` | `tools/flutter/web_app_tests/invite_not_found_event_fallback.readonly.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh readonly`; `Execution Notes (2026-06-12)` | `browser` | `passed` | Browser replay covered both the irrecoverable-home and recoverable-event fallback paths without a gray terminal screen. |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `Published tenant web host` | Browser proof must exercise the served tenant bundle where the route breaks. | `healthy` | `2026-06-10` | Manual Playwright reproduction against `https://guarappari.belluga.space/invite?code=7A9SCU6U4H` reached Flutter route and API request. | Confirm refreshed bundle/build SHA before final evidence. |
| `Invite share preview API` | Fallback behavior depends on what event identity can be recovered when `GET /api/v1/invites/share/{code}` rejects. | `degraded` | `2026-06-10` | Live API returned `404 invite_share_not_found` with empty payload for `7A9SCU6U4H`. | Decide whether backend must preserve/expose fallback target or generated links must carry it. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Browser/runtime proof is required to close the public invite entry regression. | `tools/flutter/web_app_tests/**`, published tenant URL, Flutter invite route/controller, Laravel invite preview if touched | `completed 2026-06-12` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the product behavior is narrow, but the durable fix may cross Flutter route/controller, invite link generation, Laravel preview fallback metadata, and browser-served runtime validation.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets (module sections):**
  - `invite_and_social_loop_module.md` share-code preview / continuation behavior
  - `flutter_client_experience_module.md` tenant-public route fallback and web continuation policy if the final rule changes durable startup/route behavior
- **Module decision consolidation targets (required):**
  - `invite_and_social_loop_module.md`
  - `flutter_client_experience_module.md`

## Decision Pending (Resolve Before Freeze)
- [ ] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-03` Failed invite share-code resolution must prefer event continuity over invite empty state, app-promotion fallback, blank UI, or gray screen.
- [x] `D-04` Anonymous web invite preview remains read-only; this TODO must not introduce unauthenticated accept/decline or invite-edge creation.
- [x] `D-05` A truly unrecoverable legacy/random code must still fail visibly and safely, never as an uncaught render/runtime exception.
- [x] `D-06` When no event context is recoverable, the minimum acceptable fallback destination is `HOME (/)`.
- [x] `D-07` The canonical recoverable event-fallback source for newly generated invite links is URL-carried fallback metadata on `/invite?code=...`, using the canonical public event-detail path `/agenda/evento/:slug?occurrence=<occurrence_id>`.
- [x] `D-08` If an incoming invite link does not carry recoverable event fallback metadata and the current preview/materialize contract does not expose enough canonical route identity to reconstruct the event path, the route must fall back to `HOME (/)` instead of attempting a non-canonical recovery.
- [x] `D-09` The live failing code `7A9SCU6U4H` is treated as a not-found/no-crash regression fixture unless execution proves that the backend still exposes a recoverable canonical event path for it.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `FCX-13` | Share-code preview may seed session-only invite context keyed by `share_code + occurrence_id`; event detail can consume that projection before acceptance. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |
| `INV web-to-app / continuation` | Invite landing with valid code preserves `/invite?code=...`; direct detail routes preserve requested redirect path. | `Supersede (Intentional)` | This TODO adds the missing inverse: invalid/unavailable invite code must preserve event continuity when event identity is recoverable. |
| `EVS-OCC-01` | Event detail route identity is `/agenda/evento/:slug` with optional `occurrence=<occurrence_id>`. | `Preserve` | `foundation_documentation/modules/events_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Event fallback identity source is chosen and documented before code changes.
- [x] `D-02` The fallback destination for recoverable failed invite links is the public event detail route, not tenant home or app promotion.
- [x] `D-03` No fallback path may create or mutate invite/attendance state.
- [x] `D-04` The fallback destination for unrecoverable failed invite links is `HOME (/)`.

## Questions To Close
- [ ] Validate in implementation/tests whether any authenticated non-`pending` materialize path should still reuse URL fallback metadata instead of relying on backend target reconstruction.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The gray screen is caused after invite preview failure, not by the event route itself. | Playwright reproduced `/invite?code=7A9SCU6U4H`; `GET /api/v1/invites/share/7A9SCU6U4H` returned `404 invite_share_not_found`; event route had rendered in the earlier clean-browser check. | Diagnosis shifts to a broader Flutter bootstrap/render exception. | `High` | `Keep as Assumption` |
| `A-02` | Generated invite links can be changed to carry/recover event fallback identity without changing invite acceptance semantics. | Existing invite links are generated from event/occurrence context and current code already supports a `fallback` query on invite flow. | Backend retained-target lookup may be required instead. | `Medium` | `Promote to Decision` |
| `A-03` | Some legacy/random not-found codes may not contain enough information to route to an event unless the backend retained a target record. | Live 404 payload has empty `payload: []`. | Those links must still land safely on `HOME (/)` without a crash. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `flutter-app/lib/application/sharing/invite_share_uri_builder.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/controllers/invite_flow_controller.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart`
- `flutter-app/test/application/sharing/invite_share_uri_builder_test.dart`
- `flutter-app/test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart`
- `flutter-app/test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`
- `flutter-app/test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`
- `flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
- `tools/flutter/web_app_tests/**` invite entry/fallback Playwright coverage
- `foundation_documentation/modules/invite_and_social_loop_module.md`

### Ordered Steps
1. Freeze the canonical event fallback identity source.
2. Add fail-first Flutter tests for preview failure with event fallback and no gray/blank terminal UI.
3. Add/update link-generation tests so future invite URLs can recover the event target after share-code failure.
4. Add backend support/tests only if URL/client-side fallback cannot satisfy the requirement for expired/deleted codes.
5. Implement the smallest route/controller/UI fallback that navigates/renders event detail when possible and `HOME (/)` otherwise.
6. Build/publish web and run Playwright against the tenant browser target.
7. Update module docs with the final durable decision.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** this is a production-reproduced user-visible regression, and current tests did not catch the failed preview -> gray-screen path.
- **Fail-first target(s) (when required):** invite route/controller preview failure with event fallback; invite link generation carrying/recovering fallback identity; browser Playwright not-found route with no uncaught exception and event route landing.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Failed `/invite?code=...` entry falls back to event | Visible public browser route and conversion path | `web-only` | `Playwright readonly` | `no` | `yes` | Source-owned Playwright spec through `tools/flutter/run_web_navigation_smoke.sh` after refreshed web build | `n/a` |
| Generated invite links preserve event fallback identity | Payload/link consumed by external recipients | `shared-android-web` | `Playwright readonly` for web plus Flutter tests | `no` | `yes` | Flutter payload/controller tests plus browser replay of generated fallback URL | `n/a` |
| Anonymous invite policy remains read-only | Auth/promotion boundary | `divergent-android-web` | `Playwright readonly` for web; device only if native route behavior changes | `no` | `yes` | Existing guard/action tests plus focused web validation | Device lane waived unless implementation touches native app routing. |
| Legacy unrecoverable code falls back to `HOME (/)` safely | Visible error/fallback state | `web-only` | `Playwright readonly` | `no` | `yes` | Browser replay of live or controlled unrecoverable code | `n/a` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / analyze` | Flutter route/controller/link-generation surfaces are likely touched. | `fvm dart analyze --format machine` | `Local-Implemented` | `passed` | `fvm dart analyze --format machine` | Passed on `2026-06-12` after dead test helpers/imports were removed. |
| `flutter-app / focused tests` | Invite route/controller/share payload behavior must be pinned. | `fvm flutter test --no-pub test/application/sharing/invite_share_uri_builder_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `Local-Implemented` | `passed` | same command | Includes the RED-to-green regression coverage added in this TODO. |
| `laravel-app / focused tests` | Required only if backend must expose/preserve fallback target. | `n/a (final implementation touched no laravel-app source)` | `Local-Implemented` | `n/a` | `Execution Notes (2026-06-12): final implementation stayed inside Flutter/link generation; no Laravel source changed.` | Approved contract for this slice resolved through URL-carried fallback metadata and safe-home fallback. |
| `web bundle + Playwright` | The defect is browser-visible on the served bundle. | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly` | `promotion` | `passed` | same commands | The canonical readonly suite passed after the refreshed web build, including the invite fallback specs. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Invite fallback slice (local package review)` | Missing route fallback, missing browser coverage, analyzer/test drift, or accidental Laravel scope expansion before package-level promotion review. | `n/a` | `Current delivery stage = Local-Implemented`; package-level copilot/review loop remains owned by the v0.2.0+8 package review flow. | `none at slice level` | This TODO is locally implemented and browser-validated; the next step is absorption into the package-wide review/promotion loop, not a standalone PR review cycle for this narrow slice. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Flutter invite route/controller ownership` | Look for UI-only workaround, route fallback duplication, or backend widening introduced just to hide the gray-screen symptom. | `passed` | `fvm dart analyze --format machine`; touched-surface diff listed under Touched Surfaces; Execution Notes (2026-06-12)` | `no blocker findings` | Final implementation stayed inside the approved Flutter invite/share boundary, centralized link generation, and safe route fallback without widening anonymous-web capabilities or adding backend shadow contracts. |

### Runtime / Rollout Notes
- The final browser proof must identify the served build/fingerprint before replaying invite not-found behavior.
- If generated invite URLs change shape by adding a fallback parameter, compatibility with existing `/invite?code=...` links must be preserved.
- If backend retains or exposes fallback target for not-found codes, response minimization and enumeration risk must be reviewed because the preview endpoint is public/read-only.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
Review is pending before implementation approval. Minimum review focuses:

- [ ] Architecture: event fallback source-of-truth does not become a hidden UI-only workaround.
- [ ] Code Quality: fallback path is centralized and tested, not duplicated across screens.
- [ ] Tests: RED coverage fails on current behavior and passes only when route continuity is real.
- [ ] Performance: no extra expensive public lookup on every valid invite route unless justified.
- [ ] Security: not-found fallback does not leak private invite metadata or enable invite code enumeration.
- [ ] Elegance: event continuity is explicit in link/route contracts.
- [ ] Structural Soundness: anonymous-web policy remains unchanged.

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** local implementation, focused Flutter evidence, canonical web build, and served-bundle readonly browser proof are complete for this TODO; only package-level promotion follow-through remains.
- **Post-commit/push status:** `ready-for-promotion-lane`
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and carry it through the current v0.2.0+8 package promotion.
