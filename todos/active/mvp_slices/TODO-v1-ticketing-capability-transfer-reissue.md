# TODO (V1): Ticketing Capability - Transfer and Reissue

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver transfer/reissue as an optional ticketing capability with strict entitlement integrity, anti-abuse controls, and immutable audit trace.

---

## Scope
- Implement `ticketing.transfer_reissue` as optional capability over core entitlement lifecycle.
- Support manual tenant-admin transfer and reissue operations only in MVP.
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

## Pending Decisions (Proposed Here, Pending Validation)
- [ ] 🟡 Provisional `XFER-01` Transfer authorization model.
  - Proposed decision:
    - Transfer/reissue operations are tenant-admin-only.
    - End-user owner-initiated transfer is out of scope for MVP.
    - Explicit override path is permission-gated and audited with mandatory reason code.
  - Validation gate:
    - Non-tenant-admin transfer/reissue attempts are rejected deterministically.
- [ ] 🟡 Provisional `XFER-02` Manual operation policy boundary.
  - Proposed decision:
    - No automatic cutoff/max-transfer window rules in MVP.
    - Transfer/reissue execution is explicit manual action by authorized tenant-admin operators.
    - Operator fee is fixed to `0` in MVP; any future fee/routing policy is Checkout-owned.
    - Any future end-user self-service windows/rules are deferred to a later stream.
  - Validation gate:
    - API contract exposes deterministic manual policy boundary (no hidden automatic window behavior).
    - Transfer/reissue operation never introduces mutable fee behavior in MVP.
- [ ] 🟡 Provisional `XFER-03` Reissue atomicity and old-unit invalidation.
  - Proposed decision:
    - Reissue flow atomically marks old ticket unit invalid and creates new ticket unit identity.
    - Prior QR/token cannot be used after successful reissue.
  - Validation gate:
    - No simultaneous active units exist for the same entitlement after reissue.
- [ ] 🟡 Provisional `XFER-04` Anti-abuse constraints.
  - Proposed decision:
    - Enforce transfer velocity/rate limits per entitlement/principal and optional cooling period.
    - Repeated transfer/reissue bursts may trigger soft-block with manual review state.
  - Validation gate:
    - Abuse simulation cannot create duplicate valid entitlements.
- [ ] 🟡 Provisional `XFER-05` Lifecycle and audit contract.
  - Proposed decision:
    - Persist immutable transfer/reissue events with actor, source, reason, and previous/new unit references.
    - Expose reconstruction path for full entitlement chain.
  - Validation gate:
    - Full ownership history for any ticket unit is reconstructible from audit records.

---

## Tasks
- [ ] ⚪ Define transfer/reissue contracts and manual-operator policy payload model.
- [ ] ⚪ Implement authorization + manual policy gate checks.
- [ ] ⚪ Implement atomic reissue transition (invalidate old / issue new).
- [ ] ⚪ Implement anti-abuse controls for transfer/reissue commands.
- [ ] ⚪ Persist immutable transfer/reissue audit events.
- [ ] ⚪ Register settings schema for transfer/reissue capability controls (no automatic cutoff windows in MVP).
- [ ] ⚪ Add tests for authorization, manual-policy boundary, atomicity, and abuse controls.
- [ ] ⚪ Synchronize docs/contracts/README references.

---

## Validation Steps
- [ ] ⚪ Authorization tests (tenant-admin allowed, non-tenant-admin blocked, override audited).
- [ ] ⚪ Manual-policy boundary tests (no automatic cutoff/max-transfer enforcement path in MVP).
- [ ] ⚪ Atomic reissue tests validating old token invalidation and new unit issuance.
- [ ] ⚪ Anti-abuse tests for velocity/cooling controls.
- [ ] ⚪ Audit reconstruction tests for transfer/reissue history chain.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Transfer/reissue capability is optional and policy-driven.
- [ ] ⚪ Reissue is atomic and never leaves duplicate active units.
- [ ] ⚪ Transfer/reissue abuse controls are active and validated.
- [ ] ⚪ Immutable audit chain is complete and reconstructible.
- [ ] ⚪ Documentation/contracts are synchronized with delivered behavior.

---

## Decision Log
- `XFER-00`: Decided. Transfer/reissue is a capability on top of core entitlement lifecycle and cannot alter core transition legality.
- `XFER-01..05`: Proposed in this planning cycle and pending validation.
