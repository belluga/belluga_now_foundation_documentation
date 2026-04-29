# TODO (Store Release): Invite Occurrence Target Migration

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User QA/product review on 2026-04-29 identified a structural issue in the invite implementation: invites are still effectively related to the Event, but after the implementation of Event Occurrences, the invite must target the specific Occurrence.

The corrected canonical contract is stricter than the earlier module wording: the invite target is `occurrence_id`. `event_id` is only parent context derived from the occurrence and must not be used as part of invite target identity. Store-release invite actions must materialize the selected occurrence so duplicate prevention, credited acceptance, share-code continuation, attendance confirmation, metrics, and UI context all refer to the same scheduled experience.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-invite-occurrence-target`
- **Why this is the right current slice:** this is one cross-stack contract correction: every release invite write/read/action must preserve the occurrence target.
- **Direct-to-TODO rationale:** safe. The product decision is explicit and aligns with existing occurrence-first Events contracts; the TODO exists to migrate implementation and validation, not to reopen invite business rules.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Contract-Migration`, `Occurrence-First`, `User-Flow-Impact`
- **Next exact step:** audit current invite create/share/materialize/accept/feed paths for event-target leakage, event+occurrence composite-key leakage, or nullable occurrence leakage, then add fail-first backend and Flutter tests proving `occurrence_id` is the required target and is preserved.

## Contract Boundary
- This TODO owns occurrence-target migration for invite writes, share-code materialization, invite feed/read models, acceptance/decline, duplicate prevention, credited acceptance, and Flutter invite UI context.
- `occurrence_id` is the release runtime invite target identity.
- `event_id` should not be required by invite write APIs. Backend write paths derive it from `occurrence_id`; if a legacy route or payload still supplies `event_id`, it is disposable consistency context and must be rejected on conflict rather than used for identity.
- Existing event-only or `event_id + occurrence_id` composite-target behavior may remain only as explicit historical/data-repair handling; it must not be the normal release write path.
- If a UI flow starts from an event detail with multiple occurrences and no selected occurrence, the flow must require selection or use the backend-selected occurrence context already resolved by the event detail payload. It must not silently pick a different occurrence.

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
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
- [ ] Audit backend invite storage, direct invite creation, share-code creation, share-code materialization/acceptance, feed projection, duplicate prevention, and credited-acceptance lookup for `occurrence_id = null` runtime leakage.
- [ ] Audit Flutter event detail, invite share, invite flow, received invite, and repository DTO paths for lost selected-occurrence context.
- [ ] Make release invite writes require or backend-resolve a concrete `occurrence_id` before persistence.
- [ ] Ensure share codes carry and restore occurrence identity.
- [ ] Ensure invite feed/read models render occurrence date/time/context, not only event-level identity.
- [ ] Ensure duplicate prevention and credited acceptance are keyed by `(receiver_account_profile_id, occurrence_id, inviter_principal)`.
- [ ] Ensure acceptance/decline/materialization actions preserve the same occurrence target end-to-end.
- [ ] Update canonical docs if the implementation intentionally supersedes the earlier nullable-compatibility wording for release writes.

## Out of Scope
- [ ] Redesigning invite visual polish beyond occurrence context clarity.
- [ ] Ticketing, check-in, paid reservation, or attendance policy expansion.
- [ ] Broad event occurrence authoring UX outside the occurrence identity required by invites.
- [ ] Full historical data migration for production users; there are no production compatibility constraints, but local/test fixtures may need deterministic reset or repair.
- [ ] Referral result attribution beyond direct invite acceptance; that remains VNext.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Store-release invite writes must target a concrete `occurrence_id`; event-only invite writes are not acceptable as the normal release path.
- [x] `D-02` `event_id` is derived parent context only. It must not define the invite target and must not be part of duplicate prevention, credited acceptance, share-code target identity, or metrics identity.
- [x] `D-03` For single-occurrence events, the backend may resolve the sole occurrence automatically, but persisted new invite edges/share codes must still carry that occurrence.
- [x] `D-04` For multi-occurrence events, the UI/backend must use the selected occurrence or require an explicit occurrence selection; silent event-level fallback is forbidden.
- [x] `D-05` Duplicate prevention, credited acceptance, supersession, invite feed grouping, and metrics must key on `occurrence_id` as the target identity.
- [x] `D-06` Share-code continuation must preserve occurrence identity through web/app handoff and app entry restoration.
- [x] `D-07` Any retained `occurrence_id = null` handling is compatibility/repair-only and must be named as such in code/tests/docs.

## Module Decision Consistency Matrix
| Decision | Module Decision Ref | Status | Planned Handling | Evidence |
| --- | --- | --- | --- | --- |
| `D-01..D-04` | `invite_and_social_loop_module.md` `INV-05` / `INV-PD-01` | `Supersede` | Supersede the earlier `event_id + occurrence_id | null` target framing with `occurrence_id` as the sole invite target. | User correction on 2026-04-29: convite é tudo `occurrence_id`; convite é para uma ocorrência, never event-wide. |
| `D-01..D-04` | `events_module.md` `EVS-OCC-01` | `Aligned` | Preserve selected occurrence route/query contract and consume it in invite flows. | Event detail already carries selected occurrence context. |
| `D-05` | `invite_and_social_loop_module.md` uniqueness and credited acceptance rules | `Supersede` | Remove `event_id` from target identity and key uniqueness/crediting by concrete `occurrence_id`. | Earlier module wording keyed target as `event_id + occurrence_id`; user correction makes occurrence the target. |
| `D-06` | `flutter_client_experience_module.md` invite/app continuation | `Aligned` | Preserve continuation intent and add occurrence identity as part of that intent. | Web-to-app handoff must preserve requested route/context. |

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Current implementation still has at least one event-target or `event_id + occurrence_id` composite-target invite write/read path. | User identified structural issue after testing/review; completed invite TODO predates occurrence implementation hardening and user corrected target identity on 2026-04-29. | The TODO becomes a verification/hardening lane with no code or only tests/docs. | `High` | `Keep as Assumption` |
| `A-02` | Events read/detail payloads expose enough selected occurrence data for Flutter to pass occurrence identity into invite flows. | `events_module.md` `EVS-OCC-01`; `flutter_client_experience_module.md` multi-occurrence contract. | Backend/detail DTO may need additive contract correction in this TODO. | `Medium` | `Keep as Assumption` |
| `A-03` | No production backward compatibility is required for event-only or nullable-occurrence invite writes. | Delphi mandate: no production users/backward-compat constraints for launch architecture. | Need explicit migration/compat plan before hard rejection. | `High` | `Keep as Assumption` |

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
| Direct invite create requires concrete occurrence | Multi-occurrence invite create without occurrence fails; single-occurrence resolves and persists occurrence. | Laravel feature/unit tests for create validation/resolution. | Final ADB: send invite from selected occurrence. | `planned` |
| Duplicate prevention is occurrence-scoped | Same receiver/inviter with different occurrences remain distinct; same occurrence blocks duplicate without using `event_id` as target key. | Laravel duplicate/unique-key tests. | Optional manual smoke for two dates of same event. | `planned` |
| Credited acceptance/supersession is occurrence-scoped | Accepting invite for occurrence A does not close/credit occurrence B. | Laravel acceptance/supersession tests. | Final ADB: accept one occurrence and verify other remains distinct. | `planned` |
| Share-code create/materialize/accept preserves occurrence | Share code generated from occurrence A materializes/accepts occurrence A, not event-only target. | Laravel share-code tests + Flutter repository payload test. | Web/app continuation smoke when runner/env is available. | `planned` |
| Invite feed/read model renders occurrence context | Feed item without occurrence date/time/context fails expected assertion. | Backend projection/read test + Flutter widget/controller test. | Device smoke for received invite context. | `planned` |
| Flutter event detail/share flow passes selected occurrence | Repository payload loses `occurrence_id` from selected detail route. | Flutter controller/repository tests from selected occurrence detail to invite payload. | Final ADB: invite from selected occurrence in UI. | `planned` |
| Compatibility-only null handling is isolated | New release write still allows `occurrence_id = null` silently. | Backend tests naming null only as repair/legacy path, if retained. | n/a | `planned` |

## Audit Trigger Matrix
| Lane | Trigger | Minimum Decision |
| --- | --- | --- |
| Architecture | Cross-module contract migration across Events, Invites, Flutter. | `required` |
| Code Quality | DTO/service/projection migration risk. | `required` |
| Test Quality | Fail-first coverage needed for duplicate/credited acceptance semantics. | `required` |
| Performance | Duplicate/feed indexes may need occurrence-aware query validation. | `recommended` |
| Security | Invite targeting and acceptance authorization remain tenant/auth-sensitive. | `recommended` |
| Concurrency/Idempotency | Duplicate invite and credited acceptance are mutation/idempotency-sensitive. | `required` |

## Acceptance Criteria
- [ ] New direct invite writes persist a concrete `occurrence_id`.
- [ ] Share-code invite creation and materialization preserve occurrence identity.
- [ ] Multi-occurrence event invite flows never persist or act on `occurrence_id = null`.
- [ ] Single-occurrence event invite flows persist the resolved occurrence identity.
- [ ] Duplicate prevention and credited acceptance are occurrence-scoped.
- [ ] Flutter event detail/invite share/received invite flows pass and render the selected occurrence context.
- [ ] Compatibility-only `occurrence_id = null` handling, if retained, is isolated and tested as non-release write behavior.

## Definition of Done
- [ ] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [ ] Backend tests cover direct invite, share-code, materialization/acceptance, duplicate prevention, and credited acceptance with occurrence identity.
- [ ] Flutter tests cover repository payloads and visible occurrence context.
- [ ] Module docs are updated for any superseded nullable-runtime wording.
- [ ] Analyzer/Pint/focused tests pass.
- [ ] Independent review/triple audit is recorded before promotion claim.
- [ ] ADB/device final smoke is queued for the consolidated device phase.

## Validation Steps
- [ ] Backend automated: creating an invite for a multi-occurrence event without occurrence fails deterministically.
- [ ] Backend automated: creating an invite for a single-occurrence event resolves and persists the occurrence.
- [ ] Backend automated: duplicate prevention is scoped by occurrence, allowing different occurrences while blocking duplicates for the same occurrence.
- [ ] Backend automated: credited acceptance/supersession is scoped by `(receiver_account_profile_id, occurrence_id)`.
- [ ] Backend automated: share-code create/materialize/accept preserves occurrence identity.
- [ ] Flutter automated: selected occurrence from event detail reaches invite payload.
- [ ] Flutter automated: invite feed/received invite context renders occurrence date/time identity.
- [ ] Manual/device final: send and accept invite for one occurrence of a multi-occurrence event and verify another occurrence remains distinct.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the business rule is clear, but the migration crosses backend persistence/projections, Flutter DTOs/controllers, Events selected-occurrence context, duplicate semantics, and share-code continuation.
