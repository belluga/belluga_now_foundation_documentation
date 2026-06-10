# TODO (v0.2.0+8): Invite Share Not Found Event Fallback

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** user request on `2026-06-10` to create this active TODO under `v0.2.0+8`.
- **Approval scope:** documentation/TODO creation only. Implementation still requires the normal execution approval gate before code changes.
- **Product requirement captured:** when an invite share code cannot be resolved for any reason, the user must fall back to the related event instead of seeing a broken/gray screen.

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
- if the related event target is also stale, unavailable, or truly unrecoverable, the route must still render/navigate to a deterministic non-crashing public fallback, with no gray screen and no uncaught Flutter exception.

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
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** freeze the event-fallback source-of-truth plan, add fail-first coverage for invite preview failure, then request explicit `APROVADO` before implementation.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** the defect is active in production and this TODO is opened in the current v0.2.0+8 lane, but implementation has not started under this contract.
- **Exit condition:** route/controller/backend behavior is implemented, local/browser evidence proves failed invite resolution falls back to event or deterministic safe fallback, and the TODO can move to `promotion_lane/v0.2.0+8/`.

## Scope
- [ ] `SCOPE-01` Reproduce and preserve the failing production evidence for `/invite?code=7A9SCU6U4H`, including the `404 invite_share_not_found` payload and gray-screen symptom.
- [ ] `SCOPE-02` Define the canonical source of event fallback identity for invite-share links when preview/materialization fails.
- [ ] `SCOPE-03` Ensure generated invite links carry or can recover enough event identity to route to `/agenda/evento/:slug?occurrence=<occurrence_id>` when the share code later becomes unavailable.
- [ ] `SCOPE-04` Handle all failed invite preview/materialization outcomes as route state, not uncaught render exceptions: `404`, `410`, backend rejected status, network failure, malformed payload, stale code, expired code, deleted code, and missing code.
- [ ] `SCOPE-05` When an event fallback target is available, replace/render the public event detail route instead of showing invite empty state, app-promotion fallback, blank UI, or gray screen.
- [ ] `SCOPE-06` When no event fallback target can be recovered, render/navigate to a deterministic non-crashing public fallback and record why event fallback is impossible for that legacy/unrecoverable link.
- [ ] `SCOPE-07` Preserve anonymous web policy: preview/read-only remains allowed; accept/decline/materialized mutations remain authenticated/app-owned.
- [ ] `SCOPE-08` Add source-owned browser coverage for failed invite share-code entry and event fallback on the served web bundle.

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
- [ ] `DOD-01` `/invite?code=<code>` never renders a gray/blank screen or uncaught Flutter exception when invite preview/materialization fails.
- [ ] `DOD-02` For invite links with recoverable event identity, failed invite resolution navigates/renders the public event detail route `/agenda/evento/:slug?occurrence=<occurrence_id>`.
- [ ] `DOD-03` Newly generated invite links include or can recover the related event fallback identity so future expired/not-found codes can still land on the event.
- [ ] `DOD-04` Legacy or malformed invite links with no recoverable event identity still produce a deterministic non-crashing public fallback, with visible user-facing state or safe navigation.
- [ ] `DOD-05` Anonymous web invite policy remains preview/read-only; no accept/decline/share-code mutation is introduced to solve the fallback.
- [ ] `DOD-06` Browser evidence against the served bundle proves the failed invite entry path does not break and uses event fallback when event identity is available.

## Validation Steps
- [ ] `VAL-01` Add fail-first Flutter controller/route coverage for `previewShareCode` or materialization failure with a recoverable event fallback.
- [ ] `VAL-02` Add fail-first Flutter widget coverage for initialized invite flow with empty invite preview and fallback event path, asserting event-route replacement/rendering rather than blank UI or promotion fallback.
- [ ] `VAL-03` Add or update invite link generation tests so future `/invite?code=...` URLs carry/recover event fallback identity.
- [ ] `VAL-04` If backend changes are required, add Laravel feature/unit coverage for share-code unavailable responses exposing or preserving the canonical event fallback target without leaking unauthorized invite mutation capability.
- [ ] `VAL-05` Run focused Flutter/Laravel suites for invite route/controller, invite share payload generation, and any backend share-code preview changes.
- [ ] `VAL-06` Build/publish the web bundle through the canonical project script before browser proof.
- [ ] `VAL-07` Run source-owned Playwright/navigation evidence against the real tenant browser target for failed invite entry and event fallback.
- [ ] `VAL-08` Re-test the live failing code or an equivalent controlled not-found code and record the final user-visible behavior.

## Completion Evidence Matrix (Required Before Delivery Claim)
Every `Definition of Done` item and every `Validation Steps` item must have a concrete evidence row before this TODO can claim `Local-Implemented`, move to `promotion_lane/`, move to `completed/`, or claim `Production-Ready`.

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `/invite?code=<code>` never renders a gray/blank screen or uncaught Flutter exception when invite preview/materialization fails. | `test+runtime` | `<planned Flutter route/widget tests + Playwright console/error assertions>` | `local + browser` | `planned` | Must assert no framework exception and no blank/gray terminal state. |
| `DOD-02` | `Definition of Done` | Recoverable failed invite resolution lands on `/agenda/evento/:slug?occurrence=<occurrence_id>`. | `test+runtime` | `<planned controller/widget route replacement test + Playwright route assertion>` | `local + browser` | `planned` | This is the core product requirement. |
| `DOD-03` | `Definition of Done` | Newly generated invite links include or can recover event fallback identity. | `test+review` | `<planned invite share payload/link-generation tests>` | `local` | `planned` | Prevents future not-found invite links from becoming unrecoverable. |
| `DOD-04` | `Definition of Done` | Truly unrecoverable legacy/malformed links still produce deterministic safe public fallback. | `test+runtime` | `<planned negative-path tests + browser check>` | `local + browser` | `planned` | Event fallback is required when possible; this row covers impossible legacy/random codes. |
| `DOD-05` | `Definition of Done` | Anonymous web invite policy remains preview/read-only. | `test+review` | `<planned guard/action tests and source review>` | `local` | `planned` | No mutation/auth-policy widening may be introduced. |
| `DOD-06` | `Definition of Done` | Browser evidence against served bundle proves failed invite entry does not break and uses event fallback when available. | `runtime` | `<planned tools/flutter/run_web_navigation_smoke.sh ...>` | `browser` | `planned` | Must run against refreshed tenant browser-facing bundle. |
| `VAL-01` | `Validation Steps` | Add fail-first Flutter controller/route coverage for preview/materialization failure with recoverable event fallback. | `test` | `<planned focused Flutter test>` | `local` | `planned` | Red path must fail on current behavior. |
| `VAL-02` | `Validation Steps` | Add fail-first Flutter widget coverage for initialized empty invite preview with fallback event path. | `test` | `<planned invite_flow_screen widget test>` | `local` | `planned` | Must distinguish event fallback from blank/promotion fallback. |
| `VAL-03` | `Validation Steps` | Add/update invite link generation tests for fallback identity. | `test` | `<planned payload/link tests>` | `local` | `planned` | Guards generated links. |
| `VAL-04` | `Validation Steps` | Add backend tests if backend must expose/preserve fallback target. | `test` | `<planned Laravel feature/unit tests or explicit n/a if Flutter URL fallback is sufficient>` | `backend` | `planned` | Required if current link format cannot recover event identity by itself. |
| `VAL-05` | `Validation Steps` | Run focused Flutter/Laravel suites. | `test` | `<planned exact commands>` | `local` | `planned` | Include touched surfaces only after implementation is known. |
| `VAL-06` | `Validation Steps` | Build/publish web bundle through canonical script before browser proof. | `build` | `<planned build command + served build SHA>` | `browser` | `planned` | Prevents stale-bundle false evidence. |
| `VAL-07` | `Validation Steps` | Run source-owned Playwright/navigation evidence against real tenant target. | `runtime` | `<planned Playwright spec + runner>` | `browser` | `planned` | Must inspect route, console errors, and visible state. |
| `VAL-08` | `Validation Steps` | Re-test live failing or equivalent controlled not-found code. | `runtime` | `<planned live/control replay evidence>` | `browser` | `planned` | Captures before/after continuity. |

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
| `operational-coder` | `assurance-tester-quality` | Browser/runtime proof is required to close the public invite entry regression. | `tools/flutter/web_app_tests/**`, published tenant URL, Flutter invite route/controller, Laravel invite preview if touched | `planned` |

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
- [ ] `D-01` Choose the canonical source for event fallback identity when share-code preview fails: URL fallback query, backend retained target/tombstone, generated link metadata, or an approved combination.
- [ ] `D-02` Decide how to handle the exact legacy live link `7A9SCU6U4H` if the backend has no recoverable target for that code.

## Decisions (Resolved Before Freeze)
- [x] `D-03` Failed invite share-code resolution must prefer event continuity over invite empty state, app-promotion fallback, blank UI, or gray screen.
- [x] `D-04` Anonymous web invite preview remains read-only; this TODO must not introduce unauthenticated accept/decline or invite-edge creation.
- [x] `D-05` A truly unrecoverable legacy/random code must still fail visibly and safely, never as an uncaught render/runtime exception.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `FCX-13` | Share-code preview may seed session-only invite context keyed by `share_code + occurrence_id`; event detail can consume that projection before acceptance. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |
| `INV web-to-app / continuation` | Invite landing with valid code preserves `/invite?code=...`; direct detail routes preserve requested redirect path. | `Supersede (Intentional)` | This TODO adds the missing inverse: invalid/unavailable invite code must preserve event continuity when event identity is recoverable. |
| `EVS-OCC-01` | Event detail route identity is `/agenda/evento/:slug` with optional `occurrence=<occurrence_id>`. | `Preserve` | `foundation_documentation/modules/events_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-01` Event fallback identity source is chosen and documented before code changes.
- [x] `D-02` The fallback destination for recoverable failed invite links is the public event detail route, not tenant home or app promotion.
- [x] `D-03` No fallback path may create or mutate invite/attendance state.

## Questions To Close
- [ ] Can the backend recover event `slug + occurrence_id` for expired/deleted/not-found share codes, or must generated invite URLs carry a fallback event path?
- [ ] Does the current production code `7A9SCU6U4H` still have any recoverable server-side target, or is it only usable as a not-found/no-crash regression fixture?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The gray screen is caused after invite preview failure, not by the event route itself. | Playwright reproduced `/invite?code=7A9SCU6U4H`; `GET /api/v1/invites/share/7A9SCU6U4H` returned `404 invite_share_not_found`; event route had rendered in the earlier clean-browser check. | Diagnosis shifts to a broader Flutter bootstrap/render exception. | `High` | `Keep as Assumption` |
| `A-02` | Generated invite links can be changed to carry/recover event fallback identity without changing invite acceptance semantics. | Existing invite links are generated from event/occurrence context and current code already supports a `fallback` query on invite flow. | Backend retained-target lookup may be required instead. | `Medium` | `Promote to Decision` |
| `A-03` | Some legacy/random not-found codes may not contain enough information to route to an event unless the backend retained a target record. | Live 404 payload has empty `payload: []`. | The product requirement can be satisfied for all historical links if backend can recover target by code. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `flutter-app/lib/presentation/tenant_public/invites/routes/invite_entry_route.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/controllers/invite_flow_controller.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/invite_flow_screen.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart`
- `flutter-app/lib/application/sharing/event_invite_share_payload.dart`
- `flutter-app/lib/application/router/support/route_redirect_path.dart`
- `flutter-app/lib/infrastructure/repositories/invites_repository.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/invites_backend/laravel_invites_backend.dart`
- `laravel-app/**` invite share-code preview/service/controller surfaces if the fallback target must be backend-owned
- `tools/flutter/web_app_tests/**` invite entry/fallback Playwright coverage
- `foundation_documentation/modules/invite_and_social_loop_module.md`

### Ordered Steps
1. Freeze the canonical event fallback identity source.
2. Add fail-first Flutter tests for preview failure with event fallback and no gray/blank terminal UI.
3. Add/update link-generation tests so future invite URLs can recover the event target after share-code failure.
4. Add backend support/tests only if URL/client-side fallback cannot satisfy the requirement for expired/deleted codes.
5. Implement the smallest route/controller/UI fallback that navigates/renders event detail when possible and safe public fallback otherwise.
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
| Legacy unrecoverable code fails safely | Visible error/fallback state | `web-only` | `Playwright readonly` | `no` | `yes` | Browser replay of live or controlled unrecoverable code | `n/a` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / analyze` | Flutter route/controller/link-generation surfaces are likely touched. | `fvm dart analyze --format machine` | `Local-Implemented` | `planned` | `<pending>` | Run from `flutter-app`. |
| `flutter-app / focused tests` | Invite route/controller/share payload behavior must be pinned. | `<planned focused fvm flutter test --no-pub ...>` | `Local-Implemented` | `planned` | `<pending>` | Exact files chosen after source-of-truth decision. |
| `laravel-app / focused tests` | Required only if backend must expose/preserve fallback target. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh <focused-filter>` | `Local-Implemented` | `planned` | `<pending>` | Mark `n/a` if final implementation is Flutter/link-only. |
| `web bundle + Playwright` | The defect is browser-visible on the served bundle. | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` plus `tools/flutter/run_web_navigation_smoke.sh <lane>` | `promotion` | `planned` | `<pending>` | Must confirm served build before Playwright. |

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

