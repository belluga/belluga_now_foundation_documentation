# TODO (V1): Events Capability - Participant/Student Binding

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver participant/student binding capability to support identity-linked eligibility and audience constraints in events.

---

## Scope
- Implement `participant_student_binding` as a concrete capability in Events registry/contracts.
- Add tenant-scoped and event-scoped binding configuration.
- Define participant/student identity binding model and runtime verification flow.
- Enforce effective gate and binding rules during event participation-related operations.
- Add dedicated migrations/indexes for participant binding lookups.
- Add integration/unit tests for eligibility and validation behavior.

---

## Out of Scope
- Full academic ERP synchronization.
- Long-term identity provider federation.
- Frontend enrollment UX implementation.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `PSB-01` Canonical binding subject model (user, participant profile, external student key, hybrid).
- [ ] ⚪ `PSB-02` Verification source-of-truth and refresh policy.
- [ ] ⚪ `PSB-03` Eligibility behavior on stale or missing binding.
- [ ] ⚪ `PSB-04` Privacy/minimal storage policy for student-related fields.
- [ ] ⚪ `PSB-05` Revocation behavior for previously valid bindings.
- [ ] ⚪ `PSB-06` Tenant/event payload shape and normalization defaults.
- [ ] ⚪ `PSB-07` Required indexes for participant lookup and rule evaluation.

---

## Tasks
- [ ] ⚪ Define and approve participant/student binding payload contracts.
- [ ] ⚪ Implement capability handler and register in capability registry.
- [ ] ⚪ Extend settings schema/values/patch flow for tenant binding settings.
- [ ] ⚪ Implement runtime binding verifier and eligibility gate integration.
- [ ] ⚪ Add/adjust migrations/indexes for binding query paths.
- [ ] ⚪ Implement/expand tests for eligibility, revocation, and stale data behavior.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ Capability-specific tests for binding gate, persistence, disable/reenable, and partial updates.
- [ ] ⚪ Tests for stale/invalid/revoked binding scenarios.
- [ ] ⚪ Tenant-scoped migration/index validation for binding paths.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Participant/student binding capability is integrated with registry/settings/runtime gate.
- [ ] ⚪ Eligibility and revocation behaviors are deterministic and validated.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Pending.

---

## Decision Log
- Pending.
