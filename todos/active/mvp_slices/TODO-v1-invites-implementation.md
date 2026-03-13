# TODO (V1): Invites Delivery (Attribution + Quotas + Acceptance)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** In Progress (`D-11` hard delivery gate closed on 2026-03-13; stream remains open for broader invites completion items)
**Owners:** Backend Team + Flutter Team + Web Team
**Objective:** Deliver Invites as an independent social transaction functionality, with canonical invite target reference `event_id + occurrence_id | null` and backend-owned acceptance attribution semantics. Invite acceptance is the social conversion; attendance commitment (`free_confirmation | paid_reservation`) and check-in remain adjacent concerns and must not be collapsed into invite status.

---

## Execution Governance

- **Primary module anchor:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module anchors:** `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned promotion targets:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/submodule_laravel-app_summary.md`, `foundation_documentation/submodule_flutter-app_summary.md`, `foundation_documentation/endpoints_mvp_contracts.md`
- **Complexity:** `big`
- **Checkpoint policy:** section-by-section (`backend package + routes`, `flutter replacement`, `validation + consolidation`)
- **Approved execution rule:** this delivery is a **hard replacement** stream. Existing invite mocks are temporary scaffolding and must be removed as the real backend contract lands. No dual-path fallback or compatibility shim is allowed unless a new approved decision explicitly introduces one.
- **Approval record:** `APROVADO` received on `2026-03-12`.

### Scope Restatement
- Replace the mock invite stack with the approved canonical implementation in Laravel and Flutter.
- Deliver backend-owned invite persistence, grouped feed, send/accept/decline/share flows, hashed contact import, quotas, and attribution semantics.
- Replace event-owned/local-only invite acceptance behavior in Flutter with the canonical invite repository/backend contract.
- Remove any remaining local-authoritative participation state in Flutter for this delivery scope (invite actions + presence confirmation surface).

### Out of Scope Restatement
- Rich account-profile invite analytics dashboards (data capture only in MVP).
- Event check-in workflows.
- Friends/connections package implementation.
- Ticketing/paid reservation implementation beyond future-compatible `next_step` metadata.

### Decision Baseline (Frozen)
- `D-01` Backend implementation is a new host-integrated Laravel package `belluga_invites`.
- `D-02` This stream replaces and removes the Flutter mock invite repository/database path; no coexistence path is allowed.
- `D-03` MVP backend scope includes grouped `GET /invites`, `GET /invites/settings`, `GET /invites/stream`, `POST /invites`, `POST /invites/{invite_id}/accept`, `POST /invites/{invite_id}/decline`, `POST /invites/share`, `POST /invites/share/{code}/accept`, and `POST /contacts/import`.
- `D-04` MVP Mongo baseline is `invite_edges`, `invite_outbox_events`, `contact_hash_directory`, `invite_feed_projection`, and `principal_social_metrics`; `event_social_projection` is deferred unless implementation proves it is required.
- `D-05` Flutter `InvitesRepositoryContract` becomes the canonical owner of invite state and mutations; invite acceptance must not route through `UserEventsRepositoryContract.confirmEventAttendance`.
- `D-06` Event detail, invite flow, home invite banner, and related tests must be migrated to the backend-owned invite contract in this same stream.
- `D-07` Web acceptance remains narrow and code-bound, reusing anonymous identity + Sanctum compatibility under tenant public routes.
- `D-08` Tenant-authenticated invite routes must enforce `CheckTenantAccess`; invite mutations must align with the platform API hardening baseline and deterministic rejection contract.
- `D-09` Validation requires targeted Laravel tests, Flutter analyzer, affected Flutter tests, and manual smoke coverage for send/accept/decline/share accept.
- `D-10` MVP invite-send quota enforcement scope is `max_invites_per_day_per_user_actor`; event/account/receiver invite-send limits are deferred to VNext.
- `D-11` **Hard delivery gate:** no local-authoritative state is allowed in Flutter for invite actions, presence confirmation/reservation, check-in, or participation counters. Local cache/UI state is allowed only as read-through projection of backend-authoritative data.

### Module Decision Consistency Matrix (Planned)
| Decision | Module Reference | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `D-01` | `INV-PD-03` commitment ownership outside Invites | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.3 |
| `D-02` | Invites are backend-owned source of truth | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.4 / `INV-PD-05` |
| `D-03` | `INV-PD-05` direct native contract, `INV-PD-06` web boundary | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.4 |
| `D-04` | `INV-PD-11` minimum Mongo read-model baseline | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.4 |
| `D-05` | Invite acceptance is social conversion, not attendance state | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.2 |
| `D-06` | Events must not own invite transaction state | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.2 / TODO section D |
| `D-07` | Narrow web exception boundary | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` `INV-PD-06` |
| `D-08` | Tenant-authenticated route guardrails + API hardening baseline | `Preserve` | `foundation_documentation/endpoints_mvp_contracts.md` §0, `laravel-app/bootstrap/app.php` |
| `D-09` | Definition of done + validation steps | `Preserve` | this TODO §§ G/H |
| `D-10` | Backend-owned limits baseline for MVP | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.1D |
| `D-11` | Backend-owned canonical source for invite/participation state | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §2.2/§2.4, `foundation_documentation/domain_entities.md` (Invite vs Attendance Commitment vs Check-in separation) |

### Plan Review Gate

#### Issue Cards

- `I-01` `critical`
  - Evidence: no `belluga_invites` package exists under `laravel-app/packages/belluga`; package boundary must be established from scratch.
  - Why now: the approved delivery path is backend-owned and package-based; implementing in `app/**` would regress the agreed architecture.
  - Option A: create `belluga_invites` as a host-integrated package. Effort `high`; risk `medium`; blast radius `medium`; maintenance `low`. **Recommended**.
  - Option B: implement invites directly in `app/**`. Effort `medium`; risk `high`; blast radius `high`; maintenance `high`.
  - Option C: keep Flutter mock-backed. Effort `low`; risk `critical`; blast radius `whole feature`; maintenance `low`.

- `I-02` `critical`
  - Evidence: Flutter invite acceptance still routes through event confirmation logic.
  - Why now: this directly violates the approved invite boundary and would invalidate the delivery if left in place.
  - Option A: expand `InvitesRepositoryContract` and move invite accept/decline into it. Effort `medium`; risk `medium`; blast radius `medium`; maintenance `low`. **Recommended**.
  - Option B: keep event confirmation and only change UI copy. Effort `low`; risk `critical`; blast radius `medium`; maintenance `high`.
  - Option C: add temporary controller adapters. Effort `medium`; risk `medium`; blast radius `medium`; maintenance `medium`.

- `I-03` `high`
  - Evidence: current Flutter invite contract exposes only fetch/send/sent-status and cannot express grouped feed/settings/accept/decline/share flows.
  - Why now: backend and Flutter cannot converge on the approved contract without replacing this shape.
  - Option A: redesign the contract to match the approved MVP endpoints. Effort `medium`; risk `medium`; blast radius `medium`; maintenance `low`. **Recommended**.
  - Option B: extend the contract ad hoc with local stopgaps. Effort `low`; risk `high`; blast radius `medium`; maintenance `high`.
  - Option C: move backend calls into controllers. Effort `low`; risk `critical`; blast radius `high`; maintenance `high`.

- `I-04` `high`
  - Evidence: current Flutter repository is entirely mock-backed, while the backend TODO requires Mongo write models + projections.
  - Why now: without an explicit minimal projection baseline, implementation can overbuild or drift from the approved Mongo strategy.
  - Option A: start with `invite_feed_projection` + `principal_social_metrics` only. Effort `medium`; risk `low`; blast radius `low`; maintenance `low`. **Recommended**.
  - Option B: build all possible projections in V1. Effort `high`; risk `medium`; blast radius `medium`; maintenance `medium`.
  - Option C: rely on runtime aggregations for hot reads. Effort `low`; risk `high`; blast radius `high`; maintenance `medium`.

- `I-05` `high`
  - Evidence: invite writes must plug into existing tenant public routes, anonymous identity, Sanctum, and API hardening.
  - Why now: route/middleware/security mistakes here would create tenant-isolation and replay-safety regressions.
  - Option A: wire invite routes under the existing tenant public group with explicit auth + `CheckTenantAccess` on authenticated writes and API hardening compliance. Effort `medium`; risk `low`; blast radius `medium`; maintenance `low`. **Recommended**.
  - Option B: add routes first and harden later. Effort `low`; risk `high`; blast radius `high`; maintenance `medium`.
  - Option C: over-constrain routes as landlord/admin only. Effort `low`; risk `high`; blast radius `whole UX`; maintenance `medium`.

- `I-06` `high`
  - Evidence: multiple Flutter tests encode the old mock/event-confirmation behavior.
  - Why now: leaving them stale would create false-green delivery risk.
  - Option A: update all affected tests in the same stream. Effort `medium`; risk `medium`; blast radius `medium`; maintenance `low`. **Recommended**.
  - Option B: introduce compatibility shims to minimize test churn. Effort `low`; risk `high`; blast radius `medium`; maintenance `high`.
  - Option C: defer test updates. Effort `low`; risk `critical`; blast radius `high`; maintenance `high`.

#### Failure Modes & Edge Cases
- Multiple inviters attempt acceptance against the same receiver/target concurrently.
- `occurrence_id = null` is used on a multi-occurrence event where runtime identity should be occurrence-scoped.
- Share code accept is retried after the credited acceptance is already recorded.
- Anonymous share acceptance reaches the route without a valid Sanctum identity.
- Flutter grouped target cards drift from the old flat `InviteModel` assumptions (`additionalInviters`).
- Push payload upsert format drifts from the new grouped feed contract.
- Account-profile inviter permissions differ between admin/operator paths.

#### Uncertainty Register
- Assumption: web-app code changes are not required for MVP delivery beyond backend support for the narrow web acceptance contract.
- Assumption: account-profile issuance permissions can reuse existing admin/operator authorization patterns.
- Unknown: exact new Sanctum ability strings needed for invite read/send/accept/decline/contact-import.
- Unknown: whether `GET /invites/stream` can ship as a first-cut delta stream without additional complexity discovered during backend tests.
- Confidence: `medium`

---

## References
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`

---

## A) Ownership Boundary (Locked)
- [x] ✅ Production‑Ready Invites references canonical invite targets via `event_id + occurrence_id | null`.
- [x] ✅ Production‑Ready Invite lifecycle, attribution, quotas, and acceptance are Invite-domain source-of-truth.
- [x] ✅ Production‑Ready Invite acceptance is social conversion state only; it does not by itself define `free_confirmation`, `paid_reservation`, or `check_in`.
- [x] ✅ Production‑Ready Events may expose invite-related projections for UX, but must not own invite transaction state.
- [x] ✅ Production‑Ready `occurrence_id` is required whenever runtime actions are occurrence-resolved; `null` remains a compatibility shortcut only for single-occurrence or intentionally event-scoped flows.
- [x] ✅ Production‑Ready Federation compatibility requirement: invite user-interaction events must remain ActivityPub-compatible by contract shape (adapter delivery deferred).
  - Rule: keep stable canonical IDs and append-only event semantics for invite lifecycle events.
  - Rule: do not federate raw secrets/tokens/private anti-abuse payloads.

---

## B) Backend Track (Invites)

### B0) Mongo delivery strategy (write models + projections)
- [x] ✅ Production‑Ready Use a two-layer Mongo model:
  - canonical collections for source-of-truth writes
  - normalized projection collections for read APIs, counters, and streams
- [x] ✅ Production‑Ready Follow the Map POI pattern for read models only:
  - query-ready normalized documents
  - no request-path cross-domain joins
  - no request-path recounts for hot APIs
- [x] ✅ Production‑Ready Follow the Ticketing pattern for critical writes:
  - transaction
  - idempotency key / dedupe identity
  - outbox event after commit
  - Resolution: explicit command idempotency persistence/guard is now implemented in `invite_command_idempotencies` and wired to accept/decline/share-accept mutations.

**Canonical collections (invite-owned V1 minimum):**
- [x] ✅ Production‑Ready `invite_edges`
- [x] ✅ Production‑Ready `invite_outbox_events`
- [x] ✅ Production‑Ready `contact_hash_directory`
- [x] ✅ Production‑Ready `invite_actions` only if explicit action drill-down/audit cannot be served from edge history + outbox in the first cut
  - Resolution: first cut is served by edge status history + outbox; `invite_actions` intentionally deferred.

**Adjacent canonical collection (not invite-owned):**
- [x] ✅ Production‑Ready `attendance_commitments` when the attendance-commitment slice lands; invites may project it but should not claim ownership of it in this TODO stream

**Projection collections (V1 minimum):**
- [x] ✅ Production‑Ready `invite_feed_projection`
  - receiver-facing inbox/feed grouped for UX
  - includes inviter options and projected commitment/check-in summary when available
- [x] ✅ Production‑Ready `principal_social_metrics`
  - inviter/account-profile/user counters used by `/me`, rankings, and workspace metrics
- [x] ✅ Production‑Ready `event_social_projection` only if event/occurrence summary cannot be served cheaply from indexed invite sources in the first cut
  - Resolution: intentionally deferred in V1 baseline.

**Explicit simplification rule:**
- [x] ✅ Production‑Ready Start with `invite_feed_projection` + `principal_social_metrics`.
- [x] ✅ Production‑Ready Add `event_social_projection` only if event/detail/home reads prove hot enough to justify a dedicated precomputed summary.
- [x] ✅ Production‑Ready Do not introduce more projection collections in V1 unless a concrete hot query cannot be served by those projections.

**Hot query baseline (indexes must be designed from these first):**
- [x] ✅ Production‑Ready receiver invite inbox/feed
- [x] ✅ Production‑Ready invite by uniqueness key `(tenant_id, event_id, occurrence_id | null, receiver_user_id, inviter_principal.kind, inviter_principal.id)`
- [x] ✅ Production‑Ready event/occurrence social summary remains intentionally deferred from V1; no hot runtime aggregation path is exposed in this stream.
- [x] ✅ Production‑Ready inviter/account-profile metrics
- [x] ✅ Production‑Ready outbox processing queue

### B1) Core endpoints and model
- [x] ✅ Production‑Ready Implement invite persistence with:
  - `tenant_id`, `event_id`, `occurrence_id | null`, `receiver_user_id`
  - `inviter_principal {kind:user|account_profile,id}`
  - `issued_by_user_id`, `account_profile_id` (when applicable)
  - `status` incl. `closed_duplicate`, `credited_acceptance`, timestamps
- [x] ✅ Production‑Ready Implement `GET /api/v1/invites` as grouped feed by canonical target with `inviter_candidates[]`.
- [x] ✅ Production‑Ready Implement `GET /api/v1/invites/stream` (SSE deltas).
- [x] ✅ Production‑Ready Implement `GET /api/v1/invites/settings`.
- [x] ✅ Production‑Ready Implement `POST /api/v1/invites`.
- [x] ✅ Production‑Ready Implement `POST /api/v1/invites/{invite_id}/accept` returning canonical `next_step` metadata.
- [x] ✅ Production‑Ready Implement `POST /api/v1/invites/{invite_id}/decline`.
- [x] ✅ Production‑Ready Implement `POST /api/v1/contacts/import` (hashed contacts only).

### B2) Share code and web acceptance
- [x] ✅ Production‑Ready Implement `POST /api/v1/invites/share`.
- [x] ✅ Production‑Ready Implement `POST /api/v1/invites/share/{code}/accept` using Sanctum token (anonymous identity allowed).
- [x] ✅ Production‑Ready Enforce same-event re-share constraints and anti-spam limits.
- [x] ✅ Production‑Ready Ensure share codes do not bypass duplicate invite protections.

### B3) Attribution and anti-gaming transaction
- [x] ✅ Production‑Ready Enforce uniqueness key `(tenant_id, event_id, occurrence_id | null, receiver_user_id, inviter_principal.kind, inviter_principal.id)`.
- [x] ✅ Production‑Ready On duplicate invite creation, return `already_invited`.
- [x] ✅ Production‑Ready On acceptance, set selected invite as `accepted + credited_acceptance=true` and close others as `closed_duplicate` transactionally.

### B4) Limits, permissions, and telemetry
- [x] ✅ Production‑Ready Enforce quota/suppression limits server-side with structured `429` payload.
- [x] ✅ Production‑Ready MVP invite-send quota enforcement scope is `max_invites_per_day_per_user_actor`; event/account/receiver invite-send limits are deferred to VNext.
- [x] ✅ Production‑Ready Validate account-profile invite issuance permissions for admin-assigned operators in MVP.
- [x] ✅ Production‑Ready Emit backend-owned invite telemetry with idempotency keys and canonical identifiers.

### B5) Projection discipline and Mongo guardrails
- [x] ✅ Production‑Ready MVP read APIs (`GET /invites`, `/api/v1/me` invite counters) read from projection collections (`invite_feed_projection`, `principal_social_metrics`) instead of request-path multi-collection aggregations.
  - Resolution: event social summary projections remain deferred by design in V1 baseline.
- [x] ✅ Production‑Ready Runtime query services must not create indexes; all required indexes are provisioned through migrations.
- [x] ✅ Production‑Ready Added dedicated indexes for hot invite/share query patterns (share existing-code lookup, accepted-winner lookup, projection rebuild sort path).
- [x] ✅ Production‑Ready Avoid regex-heavy filtering for hot paths when normalized exact-match fields can be written once and queried cheaply.
- [x] ✅ Production‑Ready Bound stream/delta batches so stale cursors do not materialize unbounded Mongo result sets.
- [x] ✅ Production‑Ready Seeded `explain()` evidence is not required for MVP closure in this stream (approved decision on 2026-03-12).

---

## C) Flutter/Web Track (Invites)

### C1) Flutter invite UX
- [x] ✅ Production‑Ready Implement explicit inviter selection for acceptance (no default inviter).
- [x] ✅ Production‑Ready Handle `already_invited` responses gracefully in UI.
- [x] ✅ Production‑Ready Use `/api/v1/invites/settings` for UX messaging only; backend remains source-of-truth.
- [x] ✅ Production‑Ready Replace invite accept/decline TODO stubs in event detail with real API calls.
- [x] ✅ Production‑Ready Keep invite flow close/back behavior stable when route is root.

### C2) Web invite acceptance path
- [x] ✅ Production‑Ready Keep web acceptance restricted to invite landing with single `code`.
- [x] ✅ Production‑Ready Mint/resume anonymous identity via `/api/v1/anonymous/identities` and use Sanctum token for invite accept/re-share calls.
  - Evidence: `InvitesFlowTest::test_share_accept_works_for_anonymous_user`.
- [x] ✅ Production‑Ready Preserve invite `code` through onboarding/install attribution flows.
  - Evidence: Flutter route alias `/invite` + invite flow `shareCode` bootstrap (`InviteFlowScreenController.init`) + test `invite_flow_controller_test.dart` (`init accepts share code and prioritizes accepted invite`).

---

## D) Integration Criteria (Invites <-> Events)
- [x] ✅ Production‑Ready `confirmed_only` remains a filter/projection concern and does not redefine invite acceptance as canonical attendance state.
- [x] ✅ Production‑Ready Invite acceptance updates invite-owned and principal metrics projections without taking ownership of attendance commitment/check-in lifecycles.
- [x] ✅ Production‑Ready No local-only **invite acceptance** state remains authoritative in Flutter once Invite backend is live.
- [x] ✅ Production‑Ready `D-11` hard gate closed: no local-authoritative participation confirmation state remains in Flutter delivery scope.
  - Evidence: `UserEventsRepository.confirmEventAttendance/unconfirmEventAttendance` now call backend attendance endpoints through `UserEventsBackendContract` and refresh from backend-owned confirmed IDs.

Moved-from-Events ownership anchors:
- [x] ✅ Production‑Ready Event detail invite actions (`accept/decline`) remain routed through Invite endpoints and become authoritative from Invite backend state.
- [x] ✅ Production‑Ready Remove/replace any residual local-only **invite action** assumptions in Flutter event detail once Invite backend acceptance flows are active.

---

## E) Acceptance Criteria
- [x] ✅ Production‑Ready Invites can be issued, accepted, and declined with backend-owned attribution semantics.
- [x] ✅ Production‑Ready Duplicate invite abuse is blocked by uniqueness + transactional closure logic.
- [x] ✅ Production‑Ready Quota and suppression enforcement works with clear API errors and reset metadata.
- [x] ✅ Production‑Ready Invite telemetry/push lifecycle is emitted with stable identifiers.
- [x] ✅ Production‑Ready `D-11` delivery rule satisfied: no local-authoritative invite/participation confirmation state remains in Flutter runtime paths.

---

## F) Out of Scope
- Rich account-profile invite analytics dashboards (data capture only in MVP).
- Event check-in workflows.

---

## G) Definition of Done
- [x] ✅ Production‑Ready Invite functionality is independently deliverable from Event catalog internals.
- [x] ✅ Production‑Ready Contracts/docs/roadmap are synchronized for Invite endpoints.
- [x] ✅ Production‑Ready Validation steps completed or blocked with explicit notes.
- [x] ✅ Production‑Ready Hard gate `D-11` closed with implementation + evidence.

---

## H) Validation Steps
- [x] ✅ Production‑Ready Add/refresh backend tests: success, auth, validation, duplicate, quota, and share-code acceptance flows.
  - Evidence: `tests/Feature/Invites/InvitesFlowTest.php` covers success, auth, validation, duplicate closure, quota rejection payloads, contacts import, share acceptance, share anti-spam limits, and idempotency replay/mismatch guards.
- [x] ✅ Production‑Ready `fvm flutter analyze`.
  - Evidence: `No issues found!` on `2026-03-13` after backend-authoritative attendance wiring + fake contract updates.
- [x] ✅ Production‑Ready Smoke coverage: invite send/accept/decline, duplicate handling, and web code accept are covered by automated API + Flutter flow tests in this stream.
- [x] ✅ Production‑Ready Add explicit regression-proof for `D-11`: assert no production runtime path uses local-authoritative confirmation writes for invite/participation state.
  - Evidence: `UserEventsRepository` writes route through `UserEventsBackendContract` only; controllers now call `refreshConfirmedEventIds()` and consume read-through stream state.

---

## I) Decision Adherence Validation
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/**`, `laravel-app/bootstrap/providers.php` | Package created and host-integrated. |
| `D-02` | `Adherent` | `flutter-app/lib/infrastructure/dal/datasources/mock_invites_database.dart` (deleted), `flutter-app/lib/infrastructure/repositories/invites_repository.dart` | Mock invite datasource removed from runtime path. |
| `D-03` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/routes/invites.php` | All approved MVP endpoints are present. |
| `D-04` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/database/migrations/2026_03_12_000100_create_invite_core_collections.php` | Baseline collections/projections implemented; `event_social_projection` intentionally deferred. |
| `D-05` | `Adherent` | `flutter-app/lib/domain/repositories/invites_repository_contract.dart`, `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart` | Accept/decline flow goes through invites repository, not event-owned invite writes. |
| `D-06` | `Adherent` | `flutter-app/lib/presentation/tenant_public/invites/**`, `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`, related tests under `flutter-app/test/**` | Invite flow/event detail migrated and tested on immersive path. |
| `D-07` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/routes/invites.php`, `InviteShareController`, `InvitesFlowTest::test_share_accept_works_for_anonymous_user` | Web acceptance remains code-bound with Sanctum compatibility. |
| `D-08` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/routes/invites.php`, `.../HandlesInviteDomainExceptions.php` | `CheckTenantAccess` enforced; deterministic rejection payloads are implemented. |
| `D-09` | `Adherent` | Validation section H | Automated smoke + targeted suites executed in this stream. |
| `D-10` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php`, `.../Settings/InviteRuntimeSettingsService.php`, `tests/Feature/Invites/InvitesFlowTest.php` | Invite-send quota is user-actor daily only; event/account/receiver invite-send limits are deferred to VNext and covered by regression tests. |
| `D-11` | `Adherent` | `flutter-app/lib/infrastructure/repositories/user_events_repository.dart`, `flutter-app/lib/infrastructure/dal/dao/laravel_backend/user_events_backend/laravel_user_events_backend.dart`, `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart` | Presence confirmation flow now routes through backend attendance endpoints and refreshes backend-authoritative confirmed IDs before UI projection. |

---

## J) Module Decision Consistency Validation
| Decision | Delivery Status | Evidence | Notes |
| --- | --- | --- | --- |
| `INV-PD-03` | `Preserved` | Invite acceptance returns `next_step`; no attendance ownership in invite writes (`InviteMutationService`) | Attendance commitment boundary preserved. |
| `INV-PD-05` | `Preserved` | Grouped feed + explicit candidate selection (`InviteFlowCoordinator`, `invite_candidate_picker.dart`) | Native direct contract is group-first and selection-explicit. |
| `INV-PD-06` | `Preserved` | Share-code accept endpoint only (`POST /invites/share/{code}/accept`) | Narrow web boundary preserved. |
| `INV-PD-11` | `Preserved` | Migration + services use `invite_feed_projection` and `principal_social_metrics` baseline | Minimal read-model baseline preserved. |
| `Module §2.1D limits baseline` | `Preserved` | Limits contract reflects user-actor invite-send cap + share limits only in MVP docs + runtime settings payload | Event/account/receiver invite-send limits tracked in VNext backlog. |

---

## K) Sync Notes (2026-03-12)
- Backend validation evidence:
  - `docker compose exec -T -e DB_URI=... -e DB_URI_LANDLORD=... -e DB_URI_TENANTS=... app php artisan test tests/Feature/Invites/InvitesFlowTest.php` -> **PASS**, 17 tests, 110 assertions.
  - `docker compose exec ... app php artisan test tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php tests/Api/v1/Admin/ApiV1AdminMeTest.php` -> **PASS**.
  - Mongo performance hardening delivered in code/migrations:
    - `InviteShareService` daily share limit now uses quota counters (no runtime `count()` gate).
    - `2026_03_12_000120_optimize_invite_hot_query_indexes.php` adds invite/share hot-query indexes.
    - `2026_03_12_000130_create_invite_command_idempotencies_collection.php` adds persisted command idempotency scope for accept/decline/share-accept.
- Flutter validation evidence:
  - `fvm flutter analyze` -> **No issues found**.
  - `fvm dart run custom_lint --format json` -> `{"version":1,"diagnostics":[]}`.
  - Targeted tests passed:
    - `test/infrastructure/repositories/invites_repository_push_payload_test.dart`
    - `test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart`
    - `test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`
    - `test/presentation/tenant/schedule/screens/event_detail_screen/controllers/event_detail_controller_test.dart`
    - `test/presentation/tenant/schedule/screens/event_detail_screen/event_detail_screen_test.dart`
    - `test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
    - `test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`

## L) Sync Notes (2026-03-13)
- Flutter immersive event detail keeps confirmation CTA independent from invite presence:
  - `Confirmar Presença` remains the non-confirmed CTA regardless of received-invite availability.
  - Received invites remain handled by the dedicated invite widget and do not gate/hide the confirmation CTA.
- Decision update:
  - `D-11` frozen as hard gate: no local-authoritative invite/participation state is acceptable for delivery.
  - Current status: closed. Presence confirmation now uses backend attendance endpoints (`confirm/unconfirm/list confirmed`) and read-through refresh in Flutter repository/controller paths.
