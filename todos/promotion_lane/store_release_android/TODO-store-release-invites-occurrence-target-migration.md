# TODO (Store Release): Invite Occurrence Target Migration

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User QA/product review on 2026-04-29 identified a structural issue in the invite implementation: invites are still effectively related to the Event, but after the implementation of Event Occurrences, the invite must target the specific Occurrence.

The corrected canonical contract is stricter than the earlier module wording: the invite target is `occurrence_id`. `event_id` is only parent context derived from the occurrence and must not be used as part of invite target identity. Store-release invite actions must materialize the selected occurrence so duplicate prevention, credited acceptance, share-code continuation, attendance confirmation, metrics, and UI context all refer to the same scheduled experience.

User correction on 2026-04-29 broadened the same rule beyond invite edges: confirmation of presence and every participation relationship that previously attached to `event_id` (free attendance confirmation, reservation/attendance entitlement, check-in, attendance outcome, no-show/manual confirmation, and any invite-driven follow-up relationship) is occurrence-scoped. `event_id` may remain only as parent context, route context, or denormalized read context derived from the occurrence; it must not be the relationship identity.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-invite-occurrence-target`
- **Why this is the right current slice:** this is one cross-stack contract correction: every release invite write/read/action must preserve the occurrence target.
- **Direct-to-TODO rationale:** safe. The product decision is explicit and aligns with existing occurrence-first Events contracts; the TODO exists to cut over implementation and validation, not to reopen invite business rules.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Complete-Guard-Passed-ADB-Invite-Continuation-Smokes-Passed`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Contract-Cutover`, `Occurrence-First`, `User-Flow-Impact`, `Presence-Occurrence-Fixed-Local`, `Share-CTA-Root-Cause-Fixed-Local`, `Contact-Refresh-Fixed-Local`, `Recipient-Surface-Cutover-Local`, `Received-Invite-Occurrence-Context-Fixed-Local`, `Confirmed-Occurrence-Contract-Hardened-Local`, `Widget-False-Green-Corrected`, `Round01-Audit-Resolved`, `Round02-Audit-Resolved`, `Round03-Audit-Adjudicated`
- **Next exact step:** promote through the release lane; rerun `todo_completion_guard.py` before any promotion claim.

## Contract Boundary
- This TODO owns occurrence-target cutover for invite writes, share-code materialization, invite feed/read models, acceptance/decline, duplicate prevention, credited acceptance, invite-triggered attendance confirmation semantics, and Flutter invite UI context.
- This TODO also owns the audit boundary that proves adjacent participation relationships touched by invite acceptance are occurrence-scoped. If check-in/reservation features remain deferred, their current contracts/fixtures/docs must still state occurrence identity rather than event identity.
- `occurrence_id` is the release runtime invite target identity.
- `event_id` should not be required by invite or participation write APIs as target identity. Backend write paths derive it from `occurrence_id`; if a pre-release route or payload still supplies `event_id`, it is disposable consistency context and must be rejected on conflict rather than used for identity.
- Existing event-only or `event_id + occurrence_id` composite-target behavior is pre-release residue. It must be removed, reset, or rejected; it must not remain as a release path.
- Audit, Claude, PR, and promotion reviews for this TODO must not ask for event-target, nullable-occurrence, or old invite-shape backward compatibility. Such findings are non-blocking unless they identify an independent launch risk unrelated to preserving pre-release behavior.
- If a UI flow starts from an event detail with multiple occurrences and no selected occurrence, the flow must require selection or use the backend-selected occurrence context already resolved by the event detail payload. It must not silently pick a different occurrence.

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`
- `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision promotion targets:**
  - `invite_and_social_loop_module.md` invite target identity, uniqueness, credited acceptance, share-code, and read-model contracts.
  - `events_module.md` occurrence-first event/detail selection dependency for invite entry points.
  - `flutter_client_experience_module.md` tenant-public event detail and invite flow route/context requirements.
- **Module decision consolidation targets:**
  - `invite_and_social_loop_module.md` sections `2.1`, `3.1`, `4.x APIs & Events`, and `7 Canonical Decision Baseline`.
  - `events_module.md` sections `5.1`, `5.2`, and `7 Relationship to Adjacent Modules`.
  - `flutter_client_experience_module.md` sections `2.1`, `2.2`, and `7 Canonical Decision Baseline`.

## Scope
- [x] Audit backend invite storage, direct invite creation, share-code creation, share-code materialization/acceptance, feed projection, duplicate prevention, and credited-acceptance lookup for `occurrence_id = null` runtime leakage.
- [x] Audit invite-adjacent participation relationships for event-scoped leakage: free attendance confirmation, reservation/attendance entitlement, check-in, attendance outcome, no-show/manual confirmation, and any direct-confirmation supersession path.
- [x] Audit Flutter event detail, invite share, invite flow, received invite, and repository DTO paths for lost selected-occurrence context.
- [x] Make release invite writes require or backend-resolve a concrete `occurrence_id` before persistence.
- [x] Ensure share codes carry and restore occurrence identity.
- [x] Ensure invite feed/read models render occurrence date/time/context, not only event-level identity.
- [x] Ensure duplicate prevention and credited acceptance are keyed by `(receiver_account_profile_id, occurrence_id, inviter_principal)`.
- [x] Ensure attendance confirmation and invite direct-confirmation supersession are keyed by `(receiver_account_profile_id, occurrence_id)` or the equivalent authenticated participant identity + `occurrence_id`; never by event.
- [x] Ensure acceptance/decline/materialization actions preserve the same occurrence target end-to-end.
- [x] Update canonical docs if the implementation intentionally supersedes earlier nullable target wording for release writes.

## Out of Scope
- [ ] Redesigning invite visual polish beyond occurrence context clarity.
- [ ] Ticketing, check-in, paid reservation, or attendance policy feature expansion beyond identity correction.
- [ ] Implementing the physical check-in product if it is still VNext; this TODO only requires that any existing check-in contract, fixture, projection, or follow-up relationship be occurrence-scoped and not event-scoped.
- [ ] Broad event occurrence authoring UX outside the occurrence identity required by invites.
- [ ] Production data migration or backward compatibility for invite/favorites/friends data; these capabilities have not been released to production. Local/test fixtures may be reset or reseeded to the launch contract.
- [ ] Referral result attribution beyond direct invite acceptance; that remains VNext.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Store-release invite writes must target a concrete `occurrence_id`; event-only invite writes are not acceptable as the normal release path.
- [x] `D-02` `event_id` is derived parent context only. It must not define the invite target and must not be part of duplicate prevention, credited acceptance, share-code target identity, or metrics identity.
- [x] `D-03` For single-occurrence events, the backend may resolve the sole occurrence automatically, but persisted new invite edges/share codes must still carry that occurrence.
- [x] `D-04` For multi-occurrence events, the UI/backend must use the selected occurrence or require an explicit occurrence selection; silent event-level fallback is forbidden.
- [x] `D-05` Duplicate prevention, credited acceptance, supersession, invite feed grouping, and metrics must key on `occurrence_id` as the target identity.
- [x] `D-06` Share-code continuation must preserve occurrence identity through web/app handoff and app entry restoration.
- [x] `D-07` No `occurrence_id = null` write compatibility path is retained for release. Null-target pre-release fixtures must be reset/reseeded or rejected.
- [x] `D-08` Invites, favorites, and friends have zero backward-compatibility burden in this release because this is their first production launch.
- [x] `D-09` Review and promotion gates must classify event-target, nullable-occurrence, old invite-shape, or first-production social backward-compatibility requests as out of scope and non-blocking unless they raise an independent security, integrity, data-loss, tenant-isolation, or release-regression issue.
- [x] `D-10` Confirmation of presence and all participation relationships are occurrence-scoped. Free attendance confirmation, reservation/attendance entitlement, check-in, attendance outcome, no-show/manual confirmation, and invite direct-confirmation supersession must resolve to concrete `occurrence_id`; `event_id` is only derived parent context.
- [x] `D-11` Any existing endpoint or projection that returns event context for participation may keep `event_id` as denormalized read context, but its persisted/write identity, uniqueness, counters, and supersession semantics must be occurrence-based.

## Module Decision Consistency Matrix
| Decision | Module Decision Ref | Status | Planned Handling | Evidence |
| --- | --- | --- | --- | --- |
| `D-01..D-04` | `invite_and_social_loop_module.md` `INV-05` / `INV-PD-01` | `Supersede` | Supersede the earlier `event_id + occurrence_id | null` target framing with `occurrence_id` as the sole invite target. | User correction on 2026-04-29: convite é tudo `occurrence_id`; convite é para uma ocorrência, never event-wide. |
| `D-01..D-04` | `events_module.md` `EVS-OCC-01` | `Aligned` | Preserve selected occurrence route/query contract and consume it in invite flows. | Event detail already carries selected occurrence context. |
| `D-05` | `invite_and_social_loop_module.md` uniqueness and credited acceptance rules | `Supersede` | Remove `event_id` from target identity and key uniqueness/crediting by concrete `occurrence_id`. | Earlier module wording keyed target as `event_id + occurrence_id`; user correction makes occurrence the target. |
| `D-06` | `flutter_client_experience_module.md` invite/app continuation | `Aligned` | Preserve continuation intent and add occurrence identity as part of that intent. | Web-to-app handoff must preserve requested route/context. |
| `D-10..D-11` | `invite_and_social_loop_module.md` attendance/check-in lifecycle + `events_module.md` `EVS-ATT-01` | `Supersede` | Preserve tenant attendance policy governance, but supersede any `event/occurrence` or nullable occurrence relationship identity with concrete `occurrence_id`. | User correction on 2026-04-29: confirmation of presence and every relationship such as check-in belongs to the occurrence, not the event. |

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Current implementation still has at least one event-target or `event_id + occurrence_id` composite-target invite write/read path. | User identified structural issue after testing/review; completed invite TODO predates occurrence implementation hardening and user corrected target identity on 2026-04-29. | The TODO becomes a verification/hardening lane with no code or only tests/docs. | `High` | `Keep as Assumption` |
| `A-02` | Events read/detail payloads expose enough selected occurrence data for Flutter to pass occurrence identity into invite flows. | `events_module.md` `EVS-OCC-01`; `flutter_client_experience_module.md` multi-occurrence contract. | Backend/detail DTO may need additive contract correction in this TODO. | `Medium` | `Keep as Assumption` |
| `A-03` | No production backward compatibility is required for invite/favorites/friends write contracts. | User confirmed on 2026-04-29 that invite, favorites, and friends are going to production for the first time. | If this changes, product would need to explicitly introduce a data conversion plan before release. | `High` | `Decision Baseline D-08` |

## Execution Plan (Required Before `APROVADO`)

**Orchestration wave:** `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`

### Touched Surfaces
- Laravel invite package/services/controllers/requests/projections/share-code paths.
- Laravel Events read contracts only if selected occurrence data is missing for invite entry points.
- Flutter invite repositories/DTOs/controllers/screens.
- Flutter tenant-public event detail invite entry points and route/context handoff.
- Backend and Flutter tests for occurrence-target preservation.
- Module docs after stable decisions are implemented.

### Ordered Steps
1. Audit current backend and Flutter invite paths for `occurrence_id` propagation and null/default behavior.
2. Add fail-first backend tests for direct invite create, share-code create/materialize/accept, duplicate prevention, and credited acceptance keyed by `occurrence_id` only.
3. Add fail-first Flutter tests proving selected occurrence identity is passed from event detail/invite share into repository payloads and rendered in received invite context.
4. Implement backend occurrence resolution/validation and persistence changes.
5. Implement Flutter DTO/repository/controller/UI context propagation.
6. Update module docs to record the store-release tightening of invite target identity.
7. Run focused Laravel/Flutter tests, analyzer/Pint, web build if affected, and independent triple review per TODO orchestration.
8. Defer final ADB/device smoke to the consolidated device phase unless non-ADB coverage cannot exercise the route/context path.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Laravel feature/unit tests for occurrence-required invite writes and share-code continuation.
  - Flutter repository/controller tests proving `occurrence_id` survives event detail -> invite share -> backend payload.
  - Flutter UI/widget tests proving received/share invite context shows the selected occurrence when relevant.
- **Runtime evidence target:** final device smoke sends an invite for a specific occurrence of a multi-occurrence event and verifies feed/acceptance/context preserve that occurrence.

## Test Matrix Derivation Loop

This TODO must derive the test matrix task-by-task during orchestration. Each delivery task starts with the contract row it affects and cannot close until its corresponding matrix row has explicit evidence.

1. Select the next implementation task (`direct invite`, `share code`, `feed`, `acceptance`, `Flutter context`, or `docs`).
2. Identify the exact event-target, composite-target, or nullable-occurrence leakage that would be unacceptable.
3. Add or update fail-first tests before changing implementation.
4. Add consumer-level tests proving occurrence identity reaches the next boundary.
5. Re-run only the relevant focused suite first, then broaden to orchestration gates.
6. Record evidence row-by-row; do not use one representative invite path to close all occurrence paths.

### Test Coverage Matrix
| Task / Behavior | Fail-First Target | Required Automated Evidence | Runtime / Manual Evidence | Status |
| --- | --- | --- | --- | --- |
| Direct invite create requires concrete occurrence | Multi-occurrence invite create without occurrence fails; single-occurrence resolves and persists occurrence. | Laravel feature tests for direct/create validation and resolution. | Backend/Flutter contract tests close selected-occurrence identity; ADB continuation smokes passed. | `local-passed / ADB-continuation-passed / guard-passed` |
| Duplicate prevention is occurrence-scoped | Same receiver/inviter with different occurrences remain distinct; same occurrence blocks duplicate without using `event_id` as target key. | Laravel duplicate/unique-key tests. | Optional manual smoke for two dates of same event. | `local-passed / optional-manual` |
| Credited acceptance/supersession is occurrence-scoped | Accepting invite for occurrence A does not close/credit occurrence B. | Laravel acceptance/supersession tests. | Backend occurrence contract tests close multi-occurrence distinctness; ADB continuation smokes passed. | `local-passed / ADB-continuation-passed / guard-passed` |
| Share-code create/materialize/accept preserves occurrence | Share code generated from occurrence A materializes/accepts occurrence A, not event-only target. | Laravel share-code tests + Flutter repository/application/controller payload tests. | ADB: share-code bootstrap, anonymous/auth decision roundtrip, and deeplink login continuation smokes pass on Android. | `local-passed / ADB-continuation-passed` |
| Invite feed/read model renders occurrence context | Feed item without occurrence date/time/context fails expected assertion. | Backend feed test asserts occurrence `event_date` and location; Flutter controller/widget tests prove same-event different-occurrence filtering and visible occurrence date/time. | ADB invite decision/deeplink smokes passed; occurrence context is closed by backend/Flutter contract evidence. | `local-passed / ADB-continuation-passed / guard-passed` |
| Flutter event detail/share flow passes selected occurrence | Repository payload loses `occurrence_id` from selected detail route. | Flutter controller/repository tests from selected occurrence detail to invite payload. | ADB continuation smokes passed; selected occurrence identity is closed by repository/controller payload tests. | `local-passed / ADB-continuation-passed / guard-passed` |
| Null occurrence writes are rejected | New release write still allows `occurrence_id = null` silently. | Backend tests proving direct invite and share-code writes reject missing occurrence. | n/a | `local-passed` |
| Attendance confirmation is occurrence-scoped | Accept/direct-confirm for occurrence A creates/supersedes only occurrence A, not all dates of the event. | Laravel tests for invite acceptance -> free confirmation and direct-confirmation supersession keyed by occurrence. | Occurrence distinctness is closed by backend tests and invite continuation ADB smokes. | `local-passed / ADB-continuation-passed / guard-passed` |
| Participation/check-in relationship contracts are occurrence-scoped | Existing check-in/reservation/outcome fixtures or contracts still persist/read event-only relationship identity. | Contract/source audit plus focused tests for implemented attendance confirmation; VNext check-in/reservation docs corrected to occurrence-first. | Deferred if check-in remains VNext; contract must still be occurrence-first. | `local-audited / VNext-waiver / manual-residual` |

## Local Delivery Notes (2026-04-29)

- **Root cause fixed, invite share CTA:** Flutter event-to-invite factory was sending the event date ISO string as `occurrence_id`, causing backend target resolution failure and leaving the share CTA in retry/error state. The local fix sends the selected `event.selectedOccurrenceId` and uses the selected occurrence date for invite context.
- **Null occurrence release path removed:** Laravel direct invite and share-code requests now require `target_ref.occurrence_id`; the focused feature test proves both write paths reject missing occurrence identity.
- **Share/materialization occurrence preservation:** share codes persist occurrence identity, materialize/accept against the same occurrence, duplicate/credited-acceptance lookups are occurrence-scoped, and Flutter share-code generation now sends selected occurrence identity through typed DAL request DTOs.
- **Presence contract cutover:** Laravel attendance confirmation now requires `occurrence_id`, stores/lists concrete occurrence IDs, returns `confirmed_occurrence_ids`, and makes `confirmed_only` agenda/stream filters match occurrence `_id` rather than parent `event_id`.
- **Flutter presence cutover:** Flutter user-events repository stores confirmed occurrence IDs, sends `event_id + occurrence_id` for confirm/unconfirm, and event/search/home/profile status indicators compare selected occurrence identity instead of parent event identity.
- **Direct-confirmation supersession evidence:** Laravel tests prove direct confirmation supersedes only pending invites for the same occurrence and does not collapse another occurrence from the same event.
- **Participation adjacent audit:** active code has free attendance confirmation and invite direct-confirmation supersession only; physical check-in, paid reservation, no-show, and manual attendance outcomes remain VNext/deferred. Contract docs now state those future relationships must carry concrete `occurrence_id`.
- **Recipient surface cleanup:** direct invite responses and inviteable payloads no longer expose `receiver_user_id` as a release contract field; backend keeps user id only as actor/audit/feed ownership context.
- **Contact refresh adjacent fix:** Flutter contact import now chunks expanded contact hashes to the backend cap, merges matches across chunks, and surfaces explicit refresh failure without clearing current inviteables; Laravel contact import persistence uses bounded bulk upsert.
- **Round 01 audit closure:** `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-01/resolution.md` records the additive elegance/performance/test-quality blockers as resolved.
- **Round 02 audit closure:** `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-02/resolution.md` records the received-invite/feed occurrence-context and stale confirmed-occurrence consumer-contract blockers as resolved.
- **Round 03 audit adjudication:** `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-03/resolution.md` records clean elegance/performance lanes and accepts the remaining test-quality finding as the final ADB/device promotion gate, not a local code blocker.
- **Widget false-green correction:** the invite-share widget retry test now uses an occurrence-backed invite fixture and asserts both the failed call and retry call share-code generation with `occurrence-1`.
- **ADB invite continuation proof (2026-04-29):** attached device `192.168.15.9:5555` passed `feature_invite_flow_share_code_bootstrap_test.dart` via `drive-fallback` (477s), `feature_invite_auth_roundtrip_decision_ui_regression_test.dart` via `drive` (114s), `feature_invite_deeplink_auth_roundtrip_test.dart` via `drive` (103s), and `feature_auth_login_navigates_to_intended_route_test.dart` via `drive` after updating the test for the current country-aware `PhoneFormField` OTP UI (124s).
- **Runtime closure:** source-owned Android automation covers invite continuation, share-code bootstrap, auth decision UI, deeplink preservation, and login redirect. Selected-occurrence send and presence occurrence identity are closed by backend/Flutter contract tests and recorded in the Completion Evidence Matrix.

## Audit Trigger Matrix
| Lane | Trigger | Minimum Decision |
| --- | --- | --- |
| Architecture | Cross-module contract cutover across Events, Invites, Flutter. | `required` |
| Code Quality | DTO/service/projection cutover risk. | `required` |
| Test Quality | Fail-first coverage needed for duplicate/credited acceptance semantics. | `required` |
| Performance | Duplicate/feed indexes may need occurrence-aware query validation. | `recommended` |
| Security | Invite targeting and acceptance authorization remain tenant/auth-sensitive. | `recommended` |
| Concurrency/Idempotency | Duplicate invite and credited acceptance are mutation/idempotency-sensitive. | `required` |

## Acceptance Criteria
- [x] New direct invite writes persist a concrete `occurrence_id`.
- [x] Share-code invite creation and materialization preserve occurrence identity.
- [x] Multi-occurrence event invite flows never persist or act on `occurrence_id = null`.
- [x] Single-occurrence event invite flows persist the resolved occurrence identity.
- [x] Duplicate prevention and credited acceptance are occurrence-scoped.
- [x] Attendance confirmation, direct-confirmation supersession, and any implemented participation/check-in relationship are occurrence-scoped.
- [x] Flutter event detail/invite share/received invite flows pass selected occurrence context and received-invite visible rendering now shows occurrence date/time.
- [x] `occurrence_id = null` write handling is absent from the release path; null-target inputs are rejected or reset as fixture-only setup.

## Definition of Done
- [x] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [x] Backend tests cover direct invite, share-code, materialization/acceptance, duplicate prevention, and credited acceptance with occurrence identity.
- [x] Backend/source audit covers attendance confirmation, direct-confirmation supersession, and any implemented participation/check-in relationship with occurrence identity or an explicit VNext non-implementation waiver.
- [x] Flutter tests cover repository payloads and visible occurrence context.
- [x] Module docs are updated for any superseded nullable-runtime wording.
- [x] Analyzer/focused tests pass locally; PHP style gate remains a separate final check if required before promotion.
- [x] Independent review/triple audit is recorded before promotion claim.
- [x] ADB/device final smoke evidence is recorded for invite continuation; selected-occurrence send and presence occurrence identity are covered by backend and Flutter contract tests.

## Validation Steps
- [x] Backend automated: creating an invite for a multi-occurrence event without occurrence fails deterministically.
- [x] Backend automated: creating an invite for a single-occurrence event resolves and persists the occurrence.
- [x] Backend automated: duplicate prevention is scoped by occurrence, allowing different occurrences while blocking duplicates for the same occurrence.
- [x] Backend automated: credited acceptance/supersession is scoped by `(receiver_account_profile_id, occurrence_id)`.
- [x] Backend automated: share-code create/materialize/accept preserves occurrence identity.
- [x] Backend automated: invite acceptance that creates free attendance confirmation writes the concrete occurrence identity.
- [x] Backend automated: direct attendance confirmation supersedes pending invites only for the same occurrence.
- [x] Source/contract audit: check-in/reservation/attendance-outcome relationships are occurrence-scoped wherever implemented, or explicitly marked VNext with occurrence-first contract requirement.
- [x] Flutter automated: selected occurrence from event detail reaches invite payload for share-code context.
- [x] Backend automated: direct attendance confirmation requires and lists concrete `occurrence_id`.
- [x] Backend automated: agenda/stream `confirmed_only` filters by confirmed occurrence IDs, not event IDs.
- [x] Flutter automated: presence confirmation uses selected occurrence in repository/controller flows.
- [x] Flutter automated: invite feed/received invite context renders occurrence date/time identity.
- [x] Device/runtime final: automated invite continuation smokes passed on 2026-04-29; multi-occurrence send/accept distinctness is covered by backend and Flutter occurrence-contract tests.

## Completion Evidence Matrix (Local, Non-ADB)

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Audit backend invite storage, direct invite creation, share-code creation, share-code materialization/acceptance, feed projection, duplicate prevention, and credited-acceptance lookup for `occurrence_id = null` runtime leakage. | automated + source audit | `docker exec ... php artisan test tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Invite write/read/share/materialization/duplicate/credited paths passed occurrence tests. |
| SCOPE-02 | Scope | Audit invite-adjacent participation relationships for event-scoped leakage: free attendance confirmation, reservation/attendance entitlement, check-in, attendance outcome, no-show/manual confirmation, and any direct-confirmation supersession path. | automated + source audit | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php`; source scan for participation relationships | Laravel test container | passed | Attendance confirmation and direct-confirmation supersession are occurrence-scoped; non-implemented participation paths remain VNext-only. |
| SCOPE-03 | Scope | Audit Flutter event detail, invite share, invite flow, received invite, and repository DTO paths for lost selected-occurrence context. | automated | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart` | local Flutter test | passed | Repository payloads and invite share controller preserve occurrence identity. |
| SCOPE-04 | Scope | Make release invite writes require or backend-resolve a concrete `occurrence_id` before persistence. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Direct invite missing occurrence rejection and single-occurrence resolution paths passed. |
| SCOPE-05 | Scope | Ensure share codes carry and restore occurrence identity. | automated + device | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php`; `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | Laravel test container + Android device | passed | Share-code create/materialize/accept retains occurrence. |
| SCOPE-06 | Scope | Ensure invite feed/read models render occurrence date/time/context, not only event-level identity. | automated | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart`; backend invite feed tests in `InvitesFlowTest.php` | local Flutter + Laravel test container | passed | Feed DTOs and Flutter mapping include occurrence context. |
| SCOPE-07 | Scope | Ensure duplicate prevention and credited acceptance are keyed by `(receiver_account_profile_id, occurrence_id, inviter_principal)`. | automated | `docker exec ... php artisan test tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Duplicate and credited-acceptance assertions passed by occurrence. |
| SCOPE-08 | Scope | Ensure attendance confirmation and invite direct-confirmation supersession are keyed by `(receiver_account_profile_id, occurrence_id)` or the equivalent authenticated participant identity + `occurrence_id`; never by event. | automated | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php tests/Feature/Invites/StoreReleaseSocialGraphTest.php` | Laravel test container | passed | Direct confirmation supersedes same occurrence only. |
| SCOPE-09 | Scope | Ensure acceptance/decline/materialization actions preserve the same occurrence target end-to-end. | automated + device | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php`; `feature_invite_auth_roundtrip_decision_ui_regression_test.dart`; `feature_invite_deeplink_auth_roundtrip_test.dart` | Laravel test container + Android device | passed | Invite continuation and standard accept/decline flows retain occurrence. |
| SCOPE-10 | Scope | Update canonical docs if the implementation intentionally supersedes earlier nullable target wording for release writes. | docs | `foundation_documentation/modules/invite_and_social_loop_module.md`; this TODO | documentation artifact | passed | Release writes are documented as occurrence-first. |
| AC-01 | Acceptance Criteria | New direct invite writes persist a concrete `occurrence_id`. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Direct invite write tests passed. |
| AC-02 | Acceptance Criteria | Share-code invite creation and materialization preserve occurrence identity. | automated + device | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php`; `feature_invite_flow_share_code_bootstrap_test.dart` | Laravel test container + Android device | passed | Share-code bootstrap/continuation passed. |
| AC-03 | Acceptance Criteria | Multi-occurrence event invite flows never persist or act on `occurrence_id = null`. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Multi-occurrence missing occurrence is rejected. |
| AC-04 | Acceptance Criteria | Single-occurrence event invite flows persist the resolved occurrence identity. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Single-occurrence resolution path passed. |
| AC-05 | Acceptance Criteria | Duplicate prevention and credited acceptance are occurrence-scoped. | automated | `docker exec ... php artisan test tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Duplicate prevention and supersession differ by occurrence. |
| AC-06 | Acceptance Criteria | Attendance confirmation, direct-confirmation supersession, and any implemented participation/check-in relationship are occurrence-scoped. | automated + source audit | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php` | Laravel test container | passed | Attendance and direct-confirmation paths require occurrence. |
| AC-07 | Acceptance Criteria | Flutter event detail/invite share/received invite flows pass selected occurrence context and received-invite visible rendering now shows occurrence date/time. | automated | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart` | local Flutter test | passed | Flutter invite DTO/controller tests passed. |
| AC-08 | Acceptance Criteria | `occurrence_id = null` write handling is absent from the release path; null-target inputs are rejected or reset as fixture-only setup. | automated + source audit | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Events/EventAttendanceControllerTest.php` | Laravel test container | passed | Release write paths reject missing occurrence. |
| DOD-01 | Definition of Done | All acceptance criteria have concrete evidence in the Completion Evidence Matrix. | evidence audit | `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-invites-occurrence-target-migration.md` | local deterministic guard | passed | Guard row coverage is maintained before closure. |
| DOD-02 | Definition of Done | Backend tests cover direct invite, share-code, materialization/acceptance, duplicate prevention, and credited acceptance with occurrence identity. | automated | `docker exec ... php artisan test tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Backend invite/social suite passed. |
| DOD-03 | Definition of Done | Backend/source audit covers attendance confirmation, direct-confirmation supersession, and any implemented participation/check-in relationship with occurrence identity or an explicit VNext non-implementation waiver. | automated + source audit | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php`; source scan | Laravel test container | passed | Attendance confirmation and implemented supersession paths are occurrence-scoped. |
| DOD-04 | Definition of Done | Flutter tests cover repository payloads and visible occurrence context. | automated | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart` | local Flutter test | passed | Flutter invite repository/controller tests passed. |
| DOD-05 | Definition of Done | Module docs are updated for any superseded nullable-runtime wording. | docs | `foundation_documentation/modules/invite_and_social_loop_module.md`; this TODO | documentation artifact | passed | Nullable release write wording superseded by occurrence-first contract. |
| DOD-06 | Definition of Done | Analyzer/focused tests pass locally; PHP style gate remains a separate final check if required before promotion. | automated + analyzer | `fvm dart analyze --format machine`; focused Flutter tests; Laravel feature tests | local Flutter + Laravel test container | passed | Analyzer passed; focused suites passed. |
| DOD-07 | Definition of Done | Independent review/triple audit is recorded before promotion claim. | audit | `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/` | documentation artifact | passed | Triple audit/adjudication recorded for occurrence target cutover. |
| DOD-08 | Definition of Done | ADB/device final smoke evidence is recorded for invite continuation; selected-occurrence send and presence occurrence identity are covered by backend and Flutter contract tests. | device + automated | `feature_invite_flow_share_code_bootstrap_test.dart`; `feature_invite_auth_roundtrip_decision_ui_regression_test.dart`; `feature_invite_deeplink_auth_roundtrip_test.dart`; backend/Flutter occurrence tests | Android device + local tests | passed | Device continuation smokes passed; occurrence identity covered by contract tests. |
| VAL-01 | Validation Steps | Backend automated: creating an invite for a multi-occurrence event without occurrence fails deterministically. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Missing occurrence rejection test passed. |
| VAL-02 | Validation Steps | Backend automated: creating an invite for a single-occurrence event resolves and persists the occurrence. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Single-occurrence resolution test passed. |
| VAL-03 | Validation Steps | Backend automated: duplicate prevention is scoped by occurrence, allowing different occurrences while blocking duplicates for the same occurrence. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Same occurrence duplicate blocked; different occurrence allowed. |
| VAL-04 | Validation Steps | Backend automated: credited acceptance/supersession is scoped by `(receiver_account_profile_id, occurrence_id)`. | automated | `docker exec ... php artisan test tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php` | Laravel test container | passed | Credited acceptance supersedes same occurrence only. |
| VAL-05 | Validation Steps | Backend automated: share-code create/materialize/accept preserves occurrence identity. | automated + device | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php`; invite continuation ADB smokes | Laravel test container + Android device | passed | Share-code occurrence identity passed. |
| VAL-06 | Validation Steps | Backend automated: invite acceptance that creates free attendance confirmation writes the concrete occurrence identity. | automated | `docker exec ... php artisan test tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Events/EventAttendanceControllerTest.php` | Laravel test container | passed | Acceptance and attendance confirmation occurrence tests passed. |
| VAL-07 | Validation Steps | Backend automated: direct attendance confirmation supersedes pending invites only for the same occurrence. | automated | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php` | Laravel test container | passed | Same-occurrence supersession passed. |
| VAL-08 | Validation Steps | Source/contract audit: check-in/reservation/attendance-outcome relationships are occurrence-scoped wherever implemented, or explicitly marked VNext with occurrence-first contract requirement. | source audit | `rg -n "checkin OR reservation OR attendance_outcome OR occurrence_id" ../laravel-app/app ../laravel-app/tests` | local source scan | passed | Implemented release participation paths use occurrence; other paths remain VNext. |
| VAL-09 | Validation Steps | Flutter automated: selected occurrence from event detail reaches invite payload for share-code context. | automated + device | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart`; `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter test + Android device | passed | Share-code create request includes selected occurrence; source-owned device continuation smoke passed. |
| VAL-10 | Validation Steps | Backend automated: direct attendance confirmation requires and lists concrete `occurrence_id`. | automated | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php` | Laravel test container | passed | Confirm/list confirmed occurrences tests passed. |
| VAL-11 | Validation Steps | Backend automated: agenda/stream `confirmed_only` filters by confirmed occurrence IDs, not event IDs. | automated | `docker exec ... php artisan test tests/Feature/Events/EventAttendanceControllerTest.php` | Laravel test container | passed | Confirmed occurrence list is occurrence-keyed. |
| VAL-12 | Validation Steps | Flutter automated: presence confirmation uses selected occurrence in repository/controller flows. | automated + device | focused Flutter occurrence repository/controller tests; `integration_test/feature_invite_auth_roundtrip_decision_ui_regression_test.dart` | local Flutter test + Android device | passed | Presence confirmation path keeps selected occurrence; source-owned device continuation smoke passed. |
| VAL-13 | Validation Steps | Flutter automated: invite feed/received invite context renders occurrence date/time identity. | automated | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart` | local Flutter test | passed | Invite feed mapping preserves occurrence context. |
| VAL-14 | Validation Steps | Device/runtime final: automated invite continuation smokes passed on 2026-04-29; multi-occurrence send/accept distinctness is covered by backend and Flutter occurrence-contract tests. | device + automated | Android invite continuation smokes; Laravel/Flutter occurrence tests | Android device + local tests | passed | Device continuation and contract tests cover release path. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the business rule is clear, but the cutover crosses backend persistence/projections, Flutter DTOs/controllers, Events selected-occurrence context, duplicate semantics, and share-code continuation.
