# TODO (V1): Ticketing Capability - Promotions (Discounts/Coupons/Service Charges)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver promotions as an optional ticketing capability with deterministic pricing outcomes, explicit stacking rules, and immutable purchase snapshot compatibility.

---

## Scope
- Implement `ticketing.promotions` capability as optional module over ticketing core.
- Support promotions scoped by event, occurrence, and ticket product.
- Support coupon/discount models with deterministic stack/exclusion behavior.
- Support service-charge policy integration without violating financial snapshot immutability.
- Keep core pricing engine deterministic when capability is disabled.

---

## Out of Scope
- Frontend coupon UX.
- External campaign systems and marketing automation.
- Fiscal/tax lifecycle (Checkout-owned boundary; ticketing keeps immutable fiscal linkage only).

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`

---

## Pending Decisions (Proposed Here, Pending Validation)
- [ ] 🟡 Provisional `PROMO-01` Promotion type model.
  - Proposed decision:
    - Support canonical types: `percent_discount`, `fixed_discount`, `service_charge`, `bundle_price_override`.
    - Value semantics are normalized before price computation.
  - Validation gate:
    - Same input payload always produces same normalized promotion object.
- [ ] 🟡 Provisional `PROMO-02` Scope and applicability precedence.
  - Proposed decision:
    - Promotion applicability scope: `event`, `occurrence`, `ticket_product`.
    - Precedence: most specific scope wins when conflicts occur (`ticket_product > occurrence > event`).
  - Validation gate:
    - Conflicting scope rules resolve deterministically with test matrix.
- [ ] 🟡 Provisional `PROMO-03` Stacking and exclusion policy.
  - Proposed decision:
    - Promotion declares one mode: `exclusive`, `stackable`, `group_exclusive`.
    - Resolver enforces a deterministic order and returns explicit rejection reason on conflict.
  - Validation gate:
    - Invalid combinations fail fast with stable error code.
- [ ] 🟡 Provisional `PROMO-04` Quotas and anti-abuse constraints.
  - Proposed decision:
    - MVP quota model is intentionally simplified:
      - global promotion-level quota: `global_uses_limit`.
      - optional per-principal cap: `max_uses_per_principal`.
    - No layered account/event/occurrence quota hierarchy in MVP.
    - Redemptions consume quota atomically in transaction with order confirmation path.
  - Validation gate:
    - No quota oversubscription under concurrent redemptions.
- [ ] 🟡 Provisional `PROMO-05` Snapshot contract.
  - Proposed decision:
    - Purchase stores immutable `promotion_snapshot` with applied rules/versions and computed deltas.
    - Snapshot is append-only for later compensations; never rewritten.
  - Validation gate:
    - Recalculation from snapshot reproduces final charged amount.

---

## Tasks
- [ ] ⚪ Define promotions contracts and canonical payload model.
- [ ] ⚪ Implement promotion resolver with deterministic precedence and stack/exclusion logic.
- [ ] ⚪ Implement quota consumption path in transaction with purchase confirmation.
- [ ] ⚪ Persist immutable promotion snapshot in order confirmation.
- [ ] ⚪ Register settings schema for promotions capability controls.
- [ ] ⚪ Add tests for scope precedence, stack conflicts, and quota contention.
- [ ] ⚪ Synchronize docs/contracts/README references.

---

## Validation Steps
- [ ] ⚪ Resolver deterministic tests for same-input same-output behavior.
- [ ] ⚪ Scope precedence tests (`ticket_product > occurrence > event`).
- [ ] ⚪ Stack/exclusion conflict tests with explicit error contracts.
- [ ] ⚪ Concurrent quota redemption tests under contention.
- [ ] ⚪ Snapshot reproducibility tests for charged totals.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Promotions capability is optional and fully isolated from core invariants.
- [ ] ⚪ Discount/service-charge computation is deterministic and test-covered.
- [ ] ⚪ Quota consumption is transaction-safe and anti-oversubscription.
- [ ] ⚪ Promotion snapshot is immutable and reconciliation-friendly.
- [ ] ⚪ Documentation/contracts are synchronized with delivered behavior.

---

## Decision Log
- `PROMO-00`: Decided. Promotions are capability-level concerns and cannot bypass ticketing core capacity/payment invariants.
- `PROMO-01..05`: Proposed in this planning cycle and pending validation.
