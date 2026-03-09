# TODO (V1): Ticketing Capability - Promotions (Discounts/Coupons/Service Charges)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (Production-Ready)
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
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md`

---

## Decisions (Validated)
- [x] ✅ Production‑Ready `PROMO-01` Promotion type model.
  - Decided:
    - Support canonical types: `percent_discount`, `fixed_discount`, `service_charge`, `bundle_price_override`.
    - Value semantics are normalized before price computation.
  - Validation gate:
    - Same input payload always produces same normalized promotion object.
- [x] ✅ Production‑Ready `PROMO-02` Scope and applicability precedence.
  - Decided:
    - Promotion applicability scope: `event`, `occurrence`, `ticket_product`.
    - Precedence: most specific scope wins when conflicts occur (`ticket_product > occurrence > event`).
  - Validation gate:
    - Conflicting scope rules resolve deterministically with test matrix.
- [x] ✅ Production‑Ready `PROMO-03` Stacking and exclusion policy.
  - Decided:
    - Promotion declares one mode: `exclusive`, `stackable`.
    - Resolver enforces a deterministic order and returns explicit rejection reason on conflict.
  - Validation gate:
    - Invalid combinations fail fast with stable error code.
- [x] ✅ Production‑Ready `PROMO-04` Quotas and anti-abuse constraints.
  - Decided:
    - MVP quota model is intentionally simplified:
      - global promotion-level quota: `global_uses_limit`.
      - optional per-principal cap: `max_uses_per_principal`.
    - No layered account/event/occurrence quota hierarchy in MVP.
    - Redemptions consume quota atomically in transaction with order confirmation path.
  - Validation gate:
    - No quota oversubscription under concurrent redemptions.
- [x] ✅ Production‑Ready `PROMO-05` Snapshot contract.
  - Decided:
    - Purchase stores immutable `promotion_snapshot` with applied rules/versions and computed deltas.
    - Snapshot is append-only for later compensations; never rewritten.
  - Validation gate:
    - Recalculation from snapshot reproduces final charged amount.

---

## Tasks
- [x] ✅ Production‑Ready Define promotions contracts and canonical payload model.
- [x] ✅ Production‑Ready Implement promotion resolver with deterministic precedence and stack/exclusion logic.
- [x] ✅ Production‑Ready Implement quota consumption path in transaction with purchase confirmation.
- [x] ✅ Production‑Ready Persist immutable promotion snapshot in order confirmation.
- [x] ✅ Production‑Ready Register settings schema for promotions capability controls.
- [x] ✅ Production‑Ready Add tests for scope precedence, stack conflicts, and quota contention.
- [x] ✅ Production‑Ready Synchronize docs/contracts/README references.

---

## Validation Steps
- [x] ✅ Production‑Ready Resolver deterministic tests for same-input same-output behavior.
- [x] ✅ Production‑Ready Scope precedence tests (`ticket_product > occurrence > event`).
- [x] ✅ Production‑Ready Stack/exclusion conflict tests with explicit error contracts.
- [x] ✅ Production‑Ready Concurrent quota redemption tests under contention.
- [x] ✅ Production‑Ready Snapshot reproducibility tests for charged totals.
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite).

---

## Definition of Done
- [x] ✅ Production‑Ready Promotions capability is optional and fully isolated from core invariants.
- [x] ✅ Production‑Ready Discount/service-charge computation is deterministic and test-covered.
- [x] ✅ Production‑Ready Quota consumption is transaction-safe and anti-oversubscription.
- [x] ✅ Production‑Ready Promotion snapshot is immutable and reconciliation-friendly.
- [x] ✅ Production‑Ready Documentation/contracts are synchronized with delivered behavior.

---

## Delivery Evidence
- Runtime implementation:
  - `laravel-app/packages/belluga/belluga_ticketing/src/Application/Promotions/TicketPromotionResolverService.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Application/Promotions/TicketPromotionQuotaService.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Models/Tenants/TicketPromotion.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Models/Tenants/TicketPromotionRedemption.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Application/Admission/TicketAdmissionService.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Application/Checkout/TicketCheckoutService.php`
- API/admin surface:
  - `laravel-app/packages/belluga/belluga_ticketing/src/Http/Api/v1/Controllers/TicketPromotionAdminController.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Http/Api/v1/Requests/TicketPromotionStoreRequest.php`
  - `laravel-app/packages/belluga/belluga_ticketing/routes/ticketing.php`
- Settings namespace:
  - `ticketing_promotions` in `laravel-app/packages/belluga/belluga_ticketing/src/TicketingServiceProvider.php`
- Tests:
  - `tests/Feature/Ticketing/TicketingAdmissionFlowTest.php` (`testPromotionsApplyScopePrecedenceAndPersistSnapshotOnConfirmation`, `testExclusivePromotionConflictWritesStableRejectionReasonInSnapshot`, `testPromotionQuotaPreventsSecondConfirmationWhenGlobalLimitIsReached`)
  - Full suite: `809 passed`.

---

## Decision Log
- `PROMO-00`: Decided. Promotions are capability-level concerns and cannot bypass ticketing core capacity/payment invariants.
- `PROMO-01`: Approved with canonical types `percent_discount|fixed_discount|service_charge|bundle_price_override`.
- `PROMO-02`: Approved with scope precedence `ticket_product > occurrence > event`.
- `PROMO-03`: Approved with MVP modes `exclusive|stackable` (no `group_exclusive` in MVP).
- `PROMO-04`: Approved with simplified quotas `global_uses_limit` + optional `max_uses_per_principal`.
- `PROMO-05`: Approved with immutable, append-only `promotion_snapshot`.
