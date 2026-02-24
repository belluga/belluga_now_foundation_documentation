# TODO (V1): Invites Delivery (Attribution + Quotas + Acceptance)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team + Flutter Team + Web Team
**Objective:** Deliver Invites as an independent social transaction functionality, with Event as referenced invite object (`event_id`) and backend-owned acceptance attribution semantics.

---

## References
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-and-agenda-frontend.md`

---

## A) Ownership Boundary (Locked)
- [x] ✅ Production‑Ready Invites references Event via `event_id` only.
- [ ] ⚪ Invite lifecycle, attribution, quotas, and acceptance are Invite-domain source-of-truth.
- [ ] ⚪ Events may expose invite-related projections for UX, but must not own invite transaction state.

---

## B) Backend Track (Invites)

### B1) Core endpoints and model
- [ ] ⚪ Implement invite persistence with:
  - `tenant_id`, `event_id`, `receiver_user_id`
  - `inviter_principal {kind:user|account_profile,id}`
  - `issued_by_user_id`, `account_profile_id` (when applicable)
  - `status` incl. `closed_duplicate`, `credited_acceptance`, timestamps
- [ ] ⚪ Implement `GET /api/v1/invites`.
- [ ] ⚪ Implement `GET /api/v1/invites/stream` (SSE deltas).
- [ ] ⚪ Implement `GET /api/v1/invites/settings`.
- [ ] ⚪ Implement `POST /api/v1/contacts/import` (hashed contacts only).

### B2) Share code and web acceptance
- [ ] ⚪ Implement `POST /api/v1/invites/share`.
- [ ] ⚪ Implement `POST /api/v1/invites/share/{code}/accept` using Sanctum token (anonymous identity allowed).
- [ ] ⚪ Enforce same-event re-share constraints and anti-spam limits.
- [ ] ⚪ Ensure share codes do not bypass duplicate invite protections.

### B3) Attribution and anti-gaming transaction
- [ ] ⚪ Enforce uniqueness key `(tenant_id, event_id, receiver_user_id, inviter_principal.kind, inviter_principal.id)`.
- [ ] ⚪ On duplicate invite creation, return `already_invited`.
- [ ] ⚪ On acceptance, set selected invite as `accepted + credited_acceptance=true` and close others as `closed_duplicate` transactionally.

### B4) Limits, permissions, and telemetry
- [ ] ⚪ Enforce quota/suppression limits server-side with structured `429` payload.
- [ ] ⚪ Validate account-profile invite issuance permissions for admin-assigned operators in MVP.
- [ ] ⚪ Emit backend-owned invite telemetry with idempotency keys and canonical identifiers.

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
- [ ] ⚪ `confirmed_only` in Events reads from Invite acceptance source-of-truth.
- [ ] ⚪ Invite acceptance updates are reflected in event projections without duplicating business ownership.
- [ ] ⚪ No local-only confirmation state remains authoritative in Flutter once Invite backend is live.

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
