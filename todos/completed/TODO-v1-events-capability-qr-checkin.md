# TODO (V1): Events Capability - QR Check-in

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Superseded (migrated to ticketing package stream)
**Owners:** Backend Team
**Objective:** Deliver the `qr_checkin` capability with occurrence-aware check-in flow, idempotency, and audit reliability under tenant/event capability governance.

**Superseded by:** `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
**Reason:** Ticket-domain concerns were moved from Events capabilities to a dedicated package integration stream.

---

## Scope
- Implement `qr_checkin` as a concrete capability in Events registry/contracts.
- Add tenant-scoped settings and event-level configuration for check-in behavior.
- Define and implement occurrence-aware check-in validation flow.
- Enforce effective capability gate in runtime check-in operations.
- Add capability-specific indexes/migrations required for lookup, idempotency, and audit.
- Add end-to-end automated tests for rules, edge cases, and reconciliation consistency.

---

## Out of Scope
- Inventory quantity policy design (covered by inventory capability TODO).
- Pricing/fee settlement logic.
- Frontend scanner UX implementation.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `QRC-01` Check-in identity model: ticket/participant binding key and required proof fields.
- [ ] ⚪ `QRC-02` Idempotency strategy for repeated scans (same principal/occurrence).
- [ ] ⚪ `QRC-03` Reversal policy (uncheck/revoke) and audit constraints.
- [ ] ⚪ `QRC-04` Time-window enforcement policy (early/late check-in tolerances).
- [ ] ⚪ `QRC-05` Offline/late-sync handling policy for eventual reconciliation.
- [ ] ⚪ `QRC-06` Tenant/event payload shape and default behavior normalization.
- [ ] ⚪ `QRC-07` Check-in log retention and query/index design.

---

## Tasks
- [ ] ⚪ Define and approve `qr_checkin` payload contracts.
- [ ] ⚪ Implement capability handler and register in capability registry.
- [ ] ⚪ Extend settings schema/values/patch flow for tenant check-in configuration.
- [ ] ⚪ Implement runtime check-in service with gate + idempotency rules.
- [ ] ⚪ Add/adjust migrations/indexes for check-in lookup and audit paths.
- [ ] ⚪ Implement/expand test coverage for happy path + edge cases + retries.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ Capability-specific feature/unit tests for check-in gate, idempotency, and audit behavior.
- [ ] ⚪ Tenant-scoped migration/index validation for check-in query paths.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ `qr_checkin` capability is fully integrated and governed by tenant/event effective gate.
- [ ] ⚪ Check-in flow is idempotent, auditable, and resilient to retries.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Pending.

---

## Decision Log
- Pending.
