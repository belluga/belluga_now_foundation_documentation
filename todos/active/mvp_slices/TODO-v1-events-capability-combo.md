# TODO (V1): Events Capability - Combo

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver the `combo` capability to support bundled items/rules in events with strict capability governance and occurrence-aware constraints.

---

## Scope
- Implement `combo` as a concrete capability in Events registry/contracts.
- Add tenant-scoped and event-scoped combo configuration through settings kernel + event payload.
- Define combo composition model and runtime validation rules.
- Enforce effective capability gate in combo creation/use flows.
- Add dedicated migrations/indexes for combo lookup and rule validation performance.
- Add integration tests for combo lifecycle and rule consistency.

---

## Out of Scope
- Payment settlement and invoice behavior.
- Generic promotion engine beyond event combo scope.
- Frontend combo UX implementation.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `CMB-01` Combo composition model (fixed set, selectable set, or hybrid).
- [ ] ⚪ `CMB-02` Occurrence binding policy (single occurrence vs cross-occurrence combos).
- [ ] ⚪ `CMB-03` Inventory coupling policy between combo and standalone items.
- [ ] ⚪ `CMB-04` Price derivation policy (explicit combo price vs computed discount).
- [ ] ⚪ `CMB-05` Conflict policy when combo rules collide with limits capability.
- [ ] ⚪ `CMB-06` Tenant/event payload shape and normalization defaults.
- [ ] ⚪ `CMB-07` Required indexes and consistency rules for combo resolution.

---

## Tasks
- [ ] ⚪ Define and approve combo payload contracts.
- [ ] ⚪ Implement capability handler and register in capability registry.
- [ ] ⚪ Extend settings schema/values/patch flow for combo tenant availability.
- [ ] ⚪ Implement event-level combo config and runtime validators.
- [ ] ⚪ Add/adjust migrations/indexes for combo query and validation paths.
- [ ] ⚪ Implement/expand test coverage (feature + unit) for combo scenarios.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ Capability-specific tests for combo gate, persistence, disable/reenable, and partial update semantics.
- [ ] ⚪ Tests for combo/limits interaction and conflict handling.
- [ ] ⚪ Tenant-scoped migration/index validation for combo paths.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Combo capability is integrated with registry/settings/runtime gate and approved composition rules.
- [ ] ⚪ Combo behavior under inventory/limits interaction is deterministic and tested.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Pending.

---

## Decision Log
- Pending.
