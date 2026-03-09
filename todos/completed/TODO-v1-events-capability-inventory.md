# TODO (V1): Events Capability - Inventory

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Superseded (migrated to ticketing package stream)
**Owners:** Backend Team
**Objective:** Deliver the `inventory` capability on top of Events Phase 3 capability foundation, with tenant availability + event enablement + occurrence-first enforcement.

**Superseded by:** `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
**Reason:** Ticket-domain concerns were moved from Events capabilities to a dedicated package integration stream.

---

## Scope
- Implement `inventory` as a concrete capability registered in Events capability registry/contracts.
- Add tenant-scoped capability settings schema for inventory in settings kernel (`events` namespace).
- Add event-level inventory configuration and enforce effective capability gate.
- Implement runtime inventory enforcement for create/update/read paths that consume occurrence capacities.
- Add dedicated tenant-scoped indexes/migrations required for inventory query and enforcement performance.
- Add full integration tests for capability behavior, persistence, disable/reenable semantics, and atomic partial updates.

---

## Out of Scope
- Payment transaction processing.
- QR validation/check-in logic.
- Combo modeling and pricing fee settlement.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/completed/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `INV-01` Inventory granularity: capacity enforced per occurrence, per event, or both.
- [ ] ⚪ `INV-02` Reservation model: immediate decrement vs reserved/confirmed lifecycle.
- [ ] ⚪ `INV-03` Expiration/release model for stale reservations and retry scenarios.
- [ ] ⚪ `INV-04` Overbooking policy (`strict no-overbook` vs bounded oversell).
- [ ] ⚪ `INV-05` Capability payload shape (tenant + event) and normalization rules.
- [ ] ⚪ `INV-06` Inventory audit trail minimum fields and retention policy.
- [ ] ⚪ `INV-07` Required indexes and atomic update strategy for contention-heavy writes.

---

## Tasks
- [ ] ⚪ Define and approve capability payload contracts and normalization rules.
- [ ] ⚪ Implement `InventoryCapabilityHandler` and register it in package service provider.
- [ ] ⚪ Extend settings schema/values/patch flow for inventory tenant settings.
- [ ] ⚪ Implement event-level inventory config merge + effective gate enforcement.
- [ ] ⚪ Implement runtime inventory enforcement in relevant write/read flows.
- [ ] ⚪ Add/adjust migrations/indexes for inventory query and mutation paths.
- [ ] ⚪ Implement/expand test coverage (feature + unit) for all decision rules.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [ ] ⚪ Capability-specific tests for inventory gate, persistence, disable/reenable, and partial update semantics.
- [ ] ⚪ Tenant-scoped migration/index validation for inventory paths.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Inventory capability is fully integrated with registry/settings kernel/event payload/runtime gate.
- [ ] ⚪ Inventory behavior is deterministic under contention and aligned with approved decisions.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs and contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Pending.

---

## Decision Log
- Pending.
