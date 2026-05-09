# TODO (VNext): Event Check-in (On-Site Validation)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-03-10

## Objective
Define and implement **Event Check-in** as the on-site proof/validation flow for arrival at an event or occurrence, with explicit separation from **Presence Confirmation**.

Presence Confirmation is reservation-like intent/commitment state and must not be treated as synonymous with Check-in.

**Temporary ownership note:** until the project has a dedicated TODO for `participation/presence confirmation`, this TODO is also the temporary owner for deferred VNext cleanup decisions that sit on the boundary between presence confirmation, reservation, and check-in. The current concrete example is the unresolved `attendance_policy = either` branch referenced by the invite module.

---

## 1. Boundary Clarification

- **Presence Confirmation**: a commitment-style signal that a user intends or is expected to attend.
- **Check-in**: a proof-of-arrival/admission-validation action performed at or near the event.

- [ ] 🟡 Provisional `CHK-01` Check-in and Presence Confirmation are separate concepts and separate contracts.
- [ ] 🟡 Provisional `CHK-02` Check-in does not own reservation/intent semantics.
- [ ] 🟡 Provisional `CHK-03` Presence Confirmation does not imply on-site arrival proof.
- [ ] 🟡 Provisional `CHK-04` Current legacy language that conflates `presence` with `check-in` must be cleaned up before implementation begins.

---

## 2. Scope (Study + Implementation Plan)

- [ ] ⚪ Define check-in methods:
  - `geofence`
  - `qr`
  - `staff_manual`
  - optional ticket/admission-assisted validation where required

- [ ] ⚪ Define endpoint contracts for check-in submission and result lookup.
- [ ] ⚪ Define occurrence-aware scope rules (`event_id`, `occurrence_id`, or both where required).
- [ ] ⚪ Define data model fields:
  - `check_in_id`
  - `event_id`
  - `occurrence_id`
  - `actor_ref`
  - `checkpoint_ref`
  - `method`
  - `proof_ref`
  - `checked_in_at`
  - `status`
  - `idempotency_key`
  - audit metadata/source payload hash

- [ ] ⚪ Define security and abuse guardrails:
  - replay prevention
  - rate limits
  - geofence spoofing minimum controls
  - staff/manual override audit requirements

- [ ] ⚪ Define client UX:
  - eligibility state
  - success/failure states
  - denied/expired/already-checked-in handling
  - offline/retry expectations

- [ ] ⚪ Define telemetry and outbox implications.

---

## 3. Out of Scope

- Presence Confirmation contract and lifecycle.
- Invite acceptance and invite attribution rules.
- Billing/reward logic triggered by check-in.
- Full fraud-detection platform beyond baseline guardrails.
- Generic mission/challenge logic (tracked separately in `TODO-vnext-missions-package.md`).

---

## 4. Canonical Direction

- [ ] 🟡 Provisional `CHK-05` Check-in should converge on a dedicated participation/check-in concern, not an Events-owned lifecycle.
- [ ] 🟡 Provisional `CHK-06` Events provides scope and visibility context only; it does not own check-in transaction state.
- [ ] 🟡 Provisional `CHK-07` Ticketing/admission may gate check-in when entitlement proof is required, but check-in remains a separate concern from ticket lifecycle.
- [ ] 🟡 Provisional `CHK-08` Canonical event topic for successful check-in should be `participation.check_in.recorded`.

Transition note:
- Existing/legacy contracts and package code may still emit `participation.presence.recorded` for consumed admission flows.
- VNext must decide whether that topic is deprecated, aliased, or split into distinct `presence_confirmation` and `check_in` topics.

---

## 5. Pending Decisions (VNext)

- [ ] ⚪ Canonical runtime namespace:
  - `participation.check_in`
  - or another explicitly named participation concern

- [ ] ⚪ Endpoint shape:
  - keep placeholder `POST /api/v1/events/{event_id}/check-in`
  - move to occurrence-aware path
  - or support both event + occurrence refs explicitly

- [ ] ⚪ Whether successful check-in also emits a downstream presence-related projection/event, and under what name.
- [ ] ⚪ For `attendance_policy = either`, whether post-invite-acceptance resolution must always require explicit user choice between direct presence confirmation and reservation, or whether a backend default is ever allowed. This decision is deferred out of store release and should be closed alongside the broader presence-confirmation/check-in boundary.
- [ ] ⚪ Geofence defaults (radius, accuracy, fallback policy).
- [ ] ⚪ Time-window rules (early/late tolerance).
- [ ] ⚪ Re-check-in policy / duplicate behavior.
- [ ] ⚪ Backfill/override rules for tenant admins and staff.
- [ ] ⚪ Whether ticketed and non-ticketed check-in share the exact same API envelope.

---

## 6. Initial Canonical Event Model

- [ ] ⚪ Proposed outbound event for a successful check-in:
  - topic: `participation.check_in.recorded`
  - minimum keys:
    - `tenant_id`
    - `event_id`
    - `occurrence_id`
    - `check_in_id`
    - `actor_ref`
    - `checkpoint_ref`
    - `method`
    - `proof_ref`
    - `occurred_at`
    - `idempotency_key`

- [ ] ⚪ Proposed rejection/error telemetry should remain audit-oriented and not advance downstream mission/progress counters.

---

## 7. Success Criteria

- [ ] ⚪ Clear, auditable check-in flow with deterministic rules.
- [ ] ⚪ Check-in is no longer described as Presence Confirmation in canonical docs.
- [ ] ⚪ API and UI behaviors are defined with testable constraints.
- [ ] ⚪ Idempotent replay-safe check-in handling is specified.
- [ ] ⚪ Downstream consumers can subscribe to a stable canonical check-in topic.
