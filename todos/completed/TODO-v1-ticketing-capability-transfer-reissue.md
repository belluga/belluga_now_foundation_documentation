# TODO (V1): Ticketing Capability - Transfer and Reissue

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (Production-Ready)
**Owners:** Backend Team
**Objective:** Deliver transfer/reissue as an optional ticketing capability with strict entitlement integrity, baseline protection controls, and immutable audit trace.

---

## Scope
- Implement `ticketing.transfer_reissue` as optional capability over core entitlement lifecycle.
- Support manual tenant-admin/account-admin transfer and reissue operations only in MVP.
- Enforce explicit permission/audit restrictions (no automatic policy windows in MVP).
- Keep transfer/reissue operator fee fixed to `0` in MVP; future fee policy belongs to Checkout stream.
- Guarantee atomic invalidation/issuance behavior for reissue paths.
- Preserve immutable ownership and lifecycle audit for every ticket unit.

---

## Out of Scope
- Secondary marketplace pricing.
- External wallet/pass integrations.
- Refund policy definition (handled in master ticketing TODO).

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`

---

## Decisions (Validated)
- [x] ✅ Production‑Ready `XFER-01` Transfer authorization model.
  - Decided:
    - Transfer/reissue operations are tenant-admin/account-admin only.
    - End-user owner-initiated transfer is out of scope for MVP.
    - Explicit override path is permission-gated and audited with mandatory reason code.
  - Validation gate:
    - Non-admin transfer/reissue attempts are rejected deterministically.
- [x] ✅ Production‑Ready `XFER-02` Manual operation policy boundary.
  - Decided:
    - No automatic cutoff/max-transfer window rules in MVP.
    - Transfer/reissue execution is explicit manual action by authorized tenant-admin/account-admin operators.
    - Operator fee is fixed to `0` in MVP; any future fee/routing policy is Checkout-owned.
    - Any future end-user self-service windows/rules are deferred to a later stream.
  - Validation gate:
    - API contract exposes deterministic manual policy boundary (no hidden automatic window behavior).
    - Transfer/reissue operation never introduces mutable fee behavior in MVP.
- [x] ✅ Production‑Ready `XFER-03` Reissue atomicity and old-unit invalidation.
  - Decided:
    - Reissue flow atomically marks old ticket unit invalid and creates new ticket unit identity.
    - Prior QR/token cannot be used after successful reissue.
  - Validation gate:
    - No simultaneous active units exist for the same entitlement after reissue.
- [x] ✅ Production‑Ready `XFER-04` Anti-abuse constraints.
  - Decided:
    - No additional transfer/reissue-specific anti-abuse controls in MVP beyond baseline auth/permission/idempotency controls.
    - Advanced anti-abuse controls remain a future hardening stream.
  - Validation gate:
    - Baseline controls cannot create duplicate valid entitlements or bypass authorization boundary.
- [x] ✅ Production‑Ready `XFER-05` Lifecycle and audit contract.
  - Decided:
    - Persist immutable transfer/reissue events with actor, source, reason, and previous/new unit references.
    - Expose reconstruction path for full entitlement chain.
  - Validation gate:
    - Full ownership history for any ticket unit is reconstructible from audit records.

---

## Tasks
- [x] ✅ Production‑Ready Define transfer/reissue contracts and manual-operator policy payload model.
- [x] ✅ Production‑Ready Implement authorization + manual policy gate checks.
- [x] ✅ Production‑Ready Implement atomic reissue transition (invalidate old / issue new).
- [x] ✅ Production‑Ready Persist immutable transfer/reissue audit events.
- [x] ✅ Production‑Ready Register settings schema for transfer/reissue capability controls (no automatic cutoff windows in MVP).
- [x] ✅ Production‑Ready Add tests for authorization, manual-policy boundary, and atomicity.
- [x] ✅ Production‑Ready Synchronize docs/contracts/README references.

---

## Validation Steps
- [x] ✅ Production‑Ready Authorization tests (tenant-admin/account-admin allowed, non-admin blocked, override audited).
- [x] ✅ Production‑Ready Manual-policy boundary tests (no automatic cutoff/max-transfer enforcement path in MVP).
- [x] ✅ Production‑Ready Atomic reissue tests validating old token invalidation and new unit issuance.
- [x] ✅ Production‑Ready Baseline protection tests for authorization/idempotency on transfer/reissue commands.
- [x] ✅ Production‑Ready Audit reconstruction tests for transfer/reissue history chain.
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite).

---

## Definition of Done
- [x] ✅ Production‑Ready Transfer/reissue capability is optional and policy-driven.
- [x] ✅ Production‑Ready Reissue is atomic and never leaves duplicate active units.
- [x] ✅ Production‑Ready Transfer/reissue baseline protections (auth/permission/idempotency) are active and validated in MVP.
- [x] ✅ Production‑Ready Immutable audit chain is complete and reconstructible.
- [x] ✅ Production‑Ready Documentation/contracts are synchronized with delivered behavior.

---

## Delivery Evidence
- Runtime implementation:
  - `laravel-app/packages/belluga/belluga_ticketing/src/Application/TransferReissue/TicketTransferReissueService.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Models/Tenants/TicketUnitAuditEvent.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Models/Tenants/TicketUnit.php`
- API surface:
  - `laravel-app/packages/belluga/belluga_ticketing/src/Http/Api/v1/Controllers/TicketTransferReissueController.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Http/Api/v1/Requests/TicketTransferRequest.php`
  - `laravel-app/packages/belluga/belluga_ticketing/src/Http/Api/v1/Requests/TicketReissueRequest.php`
  - `laravel-app/packages/belluga/belluga_ticketing/routes/ticketing.php`
- Settings namespace:
  - `ticketing_lifecycle.allow_transfer_reissue` in `laravel-app/packages/belluga/belluga_ticketing/src/TicketingServiceProvider.php`
- Tests:
  - `tests/Feature/Ticketing/TicketingAdmissionFlowTest.php` (`testTransferAndReissueAreRejectedWhenLifecycleCapabilityIsDisabled`, `testTransferCreatesReplacementUnitAndInvalidatesSourceUnit`, `testReissuePropagatesAcrossComboScopeUnitsAndKeepsAtomicAuditChain`, `testTransferEndpointsRequireEventsUpdateAbility`)
  - Full suite: `809 passed`.

---

## Decision Log
- `XFER-00`: Decided. Transfer/reissue is a capability on top of core entitlement lifecycle and cannot alter core transition legality.
- `XFER-01`: Approved with admin boundary `tenant-admin|account-admin` only (no end-user self-service in MVP).
- `XFER-02`: Approved as manual-only operations, no automatic windows, fee fixed at `0` in MVP.
- `XFER-03`: Approved as atomic reissue (`invalidate old + issue new`) with immediate token invalidation.
- `XFER-04`: Approved as no additional transfer-specific anti-abuse controls in MVP beyond baseline protections.
- `XFER-05`: Approved with immutable, reconstructible transfer/reissue audit chain.
