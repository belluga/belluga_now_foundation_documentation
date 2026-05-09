# TODO (V1): Events Capability - Limits

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Superseded (migrated to ticketing package stream)
**Owners:** Backend Team
**Objective:** Deliver the `limits` capability to enforce quantity and eligibility constraints with occurrence-first semantics and deterministic runtime behavior.

**Superseded by:** `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
**Reason:** Ticket-domain concerns were moved from Events capabilities to a dedicated package integration stream.

---

## Scope
- Implement `limits` as a concrete capability in Events registry/contracts.
- Add tenant-scoped and event-scoped limits configuration.
- Enforce limits during event operations that allocate participant/ticket capacity.
- Integrate limits enforcement with capability effective gate and partial update semantics.
- Add dedicated migrations/indexes for performant limit checks.
- Add integration/unit tests for core limit rules and edge conditions.

---

## Out of Scope
- Payment risk scoring.
- Full anti-fraud platform.
- Frontend quota UX implementation.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/completed/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `LMT-01` Limit dimensions (per order, per user, per occurrence, per event).
- [ ] ⚪ `LMT-02` Identity resolution for user/device/account in anonymous vs authenticated flows.
- [ ] ⚪ `LMT-03` Enforcement timing (hard-block pre-allocation vs post-validation rollback).
- [ ] ⚪ `LMT-04` Rule precedence when multiple limits apply simultaneously.
- [ ] ⚪ `LMT-05` Grace/override policy for admins/operators.
- [ ] ⚪ `LMT-06` Tenant/event payload shape and normalization defaults.
- [ ] ⚪ `LMT-07` Index and atomicity strategy for high-concurrency limit checks.

---

## Tasks
- [ ] ⚪ Define and approve limits payload contracts and precedence rules.
- [ ] ⚪ Implement capability handler and register in capability registry.
- [ ] ⚪ Extend settings schema/values/patch flow for tenant limits settings.
- [ ] ⚪ Implement runtime limits enforcement service and integration points.
- [ ] ⚪ Add/adjust migrations/indexes for constraint checks.
- [ ] ⚪ Implement/expand tests for positive/negative cases and concurrency-sensitive scenarios.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ Capability-specific tests for limits gate, persistence, disable/reenable, and partial updates.
- [ ] ⚪ Tests for overlapping rules and deterministic precedence.
- [ ] ⚪ Tenant-scoped migration/index validation for limits paths.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Limits capability is integrated with registry/settings/runtime gate and approved precedence model.
- [ ] ⚪ Limits are enforced deterministically under concurrent writes.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Pending.

---

## Decision Log
- Pending.
