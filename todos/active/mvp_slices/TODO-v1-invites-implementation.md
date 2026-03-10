# TODO (V1): Invites Delivery (Attribution + Quotas + Acceptance)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team + Flutter Team + Web Team
**Objective:** Deliver Invites as an independent social transaction functionality, with canonical invite target reference `event_id + occurrence_id | null` and backend-owned acceptance attribution semantics. Invite acceptance is the social conversion; attendance commitment (`free_confirmation | paid_reservation`) and check-in remain adjacent concerns and must not be collapsed into invite status.

---

## References
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`

---

## A) Ownership Boundary (Locked)
- [x] ✅ Production‑Ready Invites references canonical invite targets via `event_id + occurrence_id | null`.
- [ ] ⚪ Invite lifecycle, attribution, quotas, and acceptance are Invite-domain source-of-truth.
- [ ] ⚪ Invite acceptance is social conversion state only; it does not by itself define `free_confirmation`, `paid_reservation`, or `check_in`.
- [ ] ⚪ Events may expose invite-related projections for UX, but must not own invite transaction state.
- [ ] ⚪ `occurrence_id` is required whenever runtime actions are occurrence-resolved; `null` remains a compatibility shortcut only for single-occurrence or intentionally event-scoped flows.
- [x] ✅ Production‑Ready Federation compatibility requirement: invite user-interaction events must remain ActivityPub-compatible by contract shape (adapter delivery deferred).
  - Rule: keep stable canonical IDs and append-only event semantics for invite lifecycle events.
  - Rule: do not federate raw secrets/tokens/private anti-abuse payloads.

---

## B) Backend Track (Invites)

### B0) Mongo delivery strategy (write models + projections)
- [ ] ⚪ Use a two-layer Mongo model:
  - canonical collections for source-of-truth writes
  - normalized projection collections for read APIs, counters, and streams
- [ ] ⚪ Follow the Map POI pattern for read models only:
  - query-ready normalized documents
  - no request-path cross-domain joins
  - no request-path recounts for hot APIs
- [ ] ⚪ Follow the Ticketing pattern for critical writes:
  - transaction
  - idempotency key / dedupe identity
  - outbox event after commit

**Canonical collections (invite-owned V1 minimum):**
- [ ] ⚪ `invite_edges`
- [ ] ⚪ `invite_outbox_events`
- [ ] ⚪ `contact_hash_directory`
- [ ] ⚪ `invite_actions` only if explicit action drill-down/audit cannot be served from edge history + outbox in the first cut

**Adjacent canonical collection (not invite-owned):**
- [ ] ⚪ `attendance_commitments` when the attendance-commitment slice lands; invites may project it but should not claim ownership of it in this TODO stream

**Projection collections (V1 minimum):**
- [ ] ⚪ `invite_feed_projection`
  - receiver-facing inbox/feed grouped for UX
  - includes inviter options and projected commitment/check-in summary when available
- [ ] ⚪ `principal_social_metrics`
  - inviter/account-profile/user counters used by `/me`, rankings, and workspace metrics
- [ ] ⚪ `event_social_projection` only if event/occurrence summary cannot be served cheaply from indexed invite sources in the first cut

**Explicit simplification rule:**
- [ ] ⚪ Start with `invite_feed_projection` + `principal_social_metrics`.
- [ ] ⚪ Add `event_social_projection` only if event/detail/home reads prove hot enough to justify a dedicated precomputed summary.
- [ ] ⚪ Do not introduce more projection collections in V1 unless a concrete hot query cannot be served by those projections.

**Hot query baseline (indexes must be designed from these first):**
- [ ] ⚪ receiver invite inbox/feed
- [ ] ⚪ invite by uniqueness key `(tenant_id, event_id, occurrence_id | null, receiver_user_id, inviter_principal.kind, inviter_principal.id)`
- [ ] ⚪ event/occurrence social summary
- [ ] ⚪ inviter/account-profile metrics
- [ ] ⚪ outbox processing queue

### B1) Core endpoints and model
- [ ] ⚪ Implement invite persistence with:
  - `tenant_id`, `event_id`, `occurrence_id | null`, `receiver_user_id`
  - `inviter_principal {kind:user|account_profile,id}`
  - `issued_by_user_id`, `account_profile_id` (when applicable)
  - `status` incl. `closed_duplicate`, `credited_acceptance`, timestamps
- [ ] ⚪ Implement `GET /api/v1/invites` as grouped feed by canonical target with `inviter_candidates[]`.
- [ ] ⚪ Implement `GET /api/v1/invites/stream` (SSE deltas).
- [ ] ⚪ Implement `GET /api/v1/invites/settings`.
- [ ] ⚪ Implement `POST /api/v1/invites`.
- [ ] ⚪ Implement `POST /api/v1/invites/{invite_id}/accept` returning canonical `next_step` metadata.
- [ ] ⚪ Implement `POST /api/v1/invites/{invite_id}/decline`.
- [ ] ⚪ Implement `POST /api/v1/contacts/import` (hashed contacts only).

### B2) Share code and web acceptance
- [ ] ⚪ Implement `POST /api/v1/invites/share`.
- [ ] ⚪ Implement `POST /api/v1/invites/share/{code}/accept` using Sanctum token (anonymous identity allowed).
- [ ] ⚪ Enforce same-event re-share constraints and anti-spam limits.
- [ ] ⚪ Ensure share codes do not bypass duplicate invite protections.

### B3) Attribution and anti-gaming transaction
- [ ] ⚪ Enforce uniqueness key `(tenant_id, event_id, occurrence_id | null, receiver_user_id, inviter_principal.kind, inviter_principal.id)`.
- [ ] ⚪ On duplicate invite creation, return `already_invited`.
- [ ] ⚪ On acceptance, set selected invite as `accepted + credited_acceptance=true` and close others as `closed_duplicate` transactionally.

### B4) Limits, permissions, and telemetry
- [ ] ⚪ Enforce quota/suppression limits server-side with structured `429` payload.
- [ ] ⚪ Validate account-profile invite issuance permissions for admin-assigned operators in MVP.
- [ ] ⚪ Emit backend-owned invite telemetry with idempotency keys and canonical identifiers.

### B5) Projection discipline and Mongo guardrails
- [ ] ⚪ Read APIs (`GET /invites`, event social counters, `/me` social counters) must read from projection collections, not from multi-collection runtime aggregation.
- [ ] ⚪ Runtime query services must not create indexes; all required indexes are provisioned through migrations.
- [ ] ⚪ Avoid regex-heavy filtering for hot paths when normalized exact-match fields can be written once and queried cheaply.
- [ ] ⚪ Bound stream/delta batches so stale cursors do not materialize unbounded Mongo result sets.
- [ ] ⚪ Validate hot query paths with seeded data and `explain()` before marking production-ready.

---

## C) Flutter/Web Track (Invites)

### C1) Flutter invite UX
- [ ] ⚪ Implement explicit inviter selection for acceptance (no default inviter).
- [ ] ⚪ Handle `already_invited` responses gracefully in UI.
- [ ] ⚪ Use `/api/v1/invites/settings` for UX messaging only; backend remains source-of-truth.
- [ ] ⚪ Replace invite accept/decline TODO stubs in event detail with real API calls.
- [ ] ⚪ Keep invite flow close/back behavior stable when route is root.

### C2) Web invite acceptance path
- [ ] ⚪ Keep web acceptance restricted to invite landing with single `code`.
- [ ] ⚪ Mint/resume anonymous identity via `/api/v1/anonymous/identities` and use Sanctum token for invite accept/re-share calls.
- [ ] ⚪ Preserve invite `code` through onboarding/install attribution flows.

---

## D) Integration Criteria (Invites <-> Events)
- [ ] ⚪ `confirmed_only` in Events is an MVP transitional projection and must not permanently hard-code invite acceptance as the canonical attendance state.
- [ ] ⚪ Invite acceptance updates are reflected in event/social projections without implying ownership of attendance commitment or check-in.
- [ ] ⚪ No local-only confirmation state remains authoritative in Flutter once Invite backend is live.

Moved-from-Events ownership anchors:
- [ ] ⚪ Event detail invite actions (`accept/decline`) remain routed through Invite endpoints and become authoritative from Invite backend state.
- [ ] ⚪ Remove/replace any residual local-only confirmation assumptions in Flutter event detail once Invite backend acceptance flows are active.

---

## E) Acceptance Criteria
- [ ] ⚪ Invites can be issued, accepted, and declined with backend-owned attribution semantics.
- [ ] ⚪ Duplicate invite abuse is blocked by uniqueness + transactional closure logic.
- [ ] ⚪ Quota and suppression enforcement works with clear API errors and reset metadata.
- [ ] ⚪ Invite telemetry/push lifecycle is emitted with stable identifiers.

---

## F) Out of Scope
- Rich account-profile invite analytics dashboards (data capture only in MVP).
- Event check-in workflows.

---

## G) Definition of Done
- [ ] ⚪ Invite functionality is independently deliverable from Event catalog internals.
- [ ] ⚪ Contracts/docs/roadmap are synchronized for Invite endpoints.
- [ ] ⚪ Validation steps completed or blocked with explicit notes.

---

## H) Validation Steps
- [ ] ⚪ Add/refresh backend tests: success, auth, validation, duplicate, quota, and share-code acceptance flows.
- [ ] ⚪ `fvm flutter analyze`.
- [ ] ⚪ Manual smoke: invite send/accept/decline, duplicate handling, web code accept.
