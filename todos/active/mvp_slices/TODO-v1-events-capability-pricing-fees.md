# TODO (V1): Events Capability - Pricing Fees

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver `pricing_fees` capability to model pricing and fee policies under capability governance without coupling to payment provider internals.

---

## Scope
- Implement `pricing_fees` as a concrete capability in Events registry/contracts.
- Add tenant-scoped and event-scoped pricing/fee configuration.
- Define fee computation contract and runtime evaluation rules.
- Enforce effective gate and deterministic price/fee output in relevant event flows.
- Add dedicated migrations/indexes required for pricing/fee lookups and policy versioning.
- Add integration/unit tests for fee rules, rounding, and persistence semantics.

---

## Out of Scope
- External gateway settlement/reconciliation internals.
- Fiscal/tax authority integrations.
- Frontend checkout UX implementation.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `PRF-01` Canonical price model (base + components + computed totals).
- [ ] ⚪ `PRF-02` Fee ownership policy (absorbed vs passed-through vs mixed).
- [ ] ⚪ `PRF-03` Rounding policy and precision strategy.
- [ ] ⚪ `PRF-04` Currency constraints and multi-currency support for V1.
- [ ] ⚪ `PRF-05` Interaction policy with combo/limits capabilities.
- [ ] ⚪ `PRF-06` Tenant/event payload shape and normalization defaults.
- [ ] ⚪ `PRF-07` Policy versioning and audit requirements.

---

## Tasks
- [ ] ⚪ Define and approve pricing/fee payload contracts.
- [ ] ⚪ Implement capability handler and register in capability registry.
- [ ] ⚪ Extend settings schema/values/patch flow for tenant pricing/fee settings.
- [ ] ⚪ Implement runtime pricing/fee evaluator with deterministic outputs.
- [ ] ⚪ Add/adjust migrations/indexes for policy lookup and versioning.
- [ ] ⚪ Implement/expand tests for rounding, fee ownership, and capability interactions.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ Capability-specific tests for pricing_fees gate, persistence, disable/reenable, and partial updates.
- [ ] ⚪ Tests for rounding correctness and deterministic total outputs.
- [ ] ⚪ Tenant-scoped migration/index validation for pricing/fee paths.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Pricing fees capability is integrated with registry/settings/runtime gate and approved computation rules.
- [ ] ⚪ Fee outputs are deterministic and audited according to approved policy.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Pending.

---

## Decision Log
- Pending.
