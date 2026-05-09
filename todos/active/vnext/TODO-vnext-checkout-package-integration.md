# TODO (VNext): Checkout Package Integration Baseline

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Establish Checkout package decisions that are intentionally deferred from Ticketing, so payment event semantics are decided in Checkout (source of truth) and consumed by Ticketing via contracts.

---

## Scope
- Define canonical Checkout payment event vocabulary and reducer semantics.
- Define webhook authenticity/replay rules and dead-letter/retry policy.
- Define refund/cancel/dispute ownership and side-effect contracts.
- Define reconciliation ownership split and cadence/escalation model.
- Define contract surface consumed by Ticketing (`CheckoutOrchestratorContract` adapters).

## Out of Scope
- Ticketing inventory/hold/queue implementation details.
- Frontend checkout UX.
- PSP-specific SDK implementation details (adapter internals can be planned separately).

---

## Source References
- `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
  - `GATE-CHECKOUT-01` and `PACK-A` (`TKT-22`, `TKT-23`, `TKT-24`, `TKT-43`).

---

## Inherited Constraints (From Ticketing Decisions)
- `TKT-17`: Ticketing prepares checkout payload; Checkout owns provider mechanics.
- `TKT-18`: Gateway switching/routing is Checkout-owned.
- `TKT-19`: Dual immutable snapshots must remain coherent (`checkout_payload_snapshot` + `financial_snapshot`).
- `TKT-20`: Fee policy resolution is frozen into snapshots; Checkout must not mutate frozen values.
- `TKT-27`: Current slice keeps `paid` guarded until integration enablement.

---

## Decision Backlog (Checkout-Owned)
- [x] ✅ Production‑Ready `CKO-01` Canonical payment event vocabulary + state reducer.
  - Maps to Ticketing: `TKT-22`.
  - Decision:
    - Canonical reducer uses two axes:
      - `payment_status`: `initiated -> pending -> authorized -> captured -> partially_refunded -> refunded`
      - failure branch: `initiated|pending|authorized -> failed|canceled`
      - `dispute_status`: `none -> opened -> won|lost`
    - Provider-native events are mapped to canonical reducer transitions.
    - Reducer invariants:
      - no terminal regression,
      - replay/idempotency safety,
      - out-of-order older events cannot overwrite newer terminal outcomes.
  - Validation gate:
    - Out-of-order event replay preserves final canonical state deterministically.
    - Duplicate provider events are no-op after first successful application.
- [x] ✅ Production‑Ready `CKO-02` Webhook authenticity/replay policy.
  - Maps to Ticketing: `TKT-22`.
  - Decision:
    - Authenticate all inbound webhook payloads via provider signature verification + timestamp tolerance window.
    - Persist immutable raw webhook event envelope before reducer processing.
    - Idempotency key: unique (`provider`, `provider_event_id`) across event journal.
    - Replay behavior:
      - duplicate valid event id is accepted and processed as no-op by reducer idempotency.
      - invalid signature or outside allowed timestamp tolerance is rejected and audited.
    - Retry behavior:
      - bounded retry with exponential backoff for transient processing failures.
      - terminal processing failure routes event to dead-letter queue with alert/escalation.
  - Validation gate:
    - Signature/timestamp checks block forged or stale payloads.
    - Duplicate delivery of same provider event does not change final state.
    - Failed events are observable/recoverable through dead-letter workflow.
- [x] ✅ Production‑Ready `CKO-03` Refund/cancel ownership and side-effects contract.
  - Maps to Ticketing: `TKT-23`.
  - Decision:
    - Ownership split:
      - Checkout owns monetary operations (refund request, provider execution, refund final state).
      - Ticketing owns inventory/entitlement side-effects.
    - Communication contract:
      - Checkout emits canonical payment/refund domain events through outbox.
      - Ticketing consumes those events asynchronously via queued jobs/listeners.
      - Ticketing listeners are idempotent by canonical event identity.
    - Cutoff/eligibility authority:
      - Ticketing is source of truth for refund/cancel eligibility policy (event/occurrence/product scope).
      - Checkout must respect ticketing eligibility contract before provider monetary execution.
    - Side-effect trigger:
      - Inventory/entitlement side-effects execute only on canonical confirmed refund/cancel outcomes, never on mere request/initiation.
    - Audit:
      - Both domains persist immutable audit records with shared correlation identity.
  - Validation gate:
    - Duplicate refund/cancel events do not duplicate side-effects.
    - Checkout monetary status and ticketing entitlement/inventory status converge deterministically by event replay.
    - Failed listeners are recoverable without losing event ordering guarantees.
- [x] ✅ Production‑Ready `CKO-04` Dispute/chargeback operational model.
  - Maps to Ticketing: `TKT-43`.
  - Decision:
    - Checkout owns monetary dispute lifecycle and emits canonical events:
      - `dispute_opened`, `dispute_won`, `dispute_lost`.
    - Ticketing consumes dispute events asynchronously and applies entitlement side-effects by policy.
    - Default policy:
      - `dispute_opened` -> `manual_review` (no automatic entitlement revoke).
      - `dispute_won` -> clear dispute risk state.
      - `dispute_lost` -> apply tenant policy (default `manual_review`; optional `auto_revoke_if_not_used`).
    - Safeguard:
      - if entitlement is already consumed/check-in completed, side-effect remains `manual_review` (no automatic destructive rollback).
    - All dispute side-effects are idempotent and append-audited.
  - Validation gate:
    - Replay of dispute events does not duplicate side-effects.
    - Consumed entitlements are never auto-revoked by dispute automation.
    - Checkout monetary dispute state and ticketing entitlement state remain correlated by canonical event identity.
- [x] ✅ Production‑Ready `CKO-05` Reconciliation responsibility model.
  - Maps to Ticketing: `TKT-24`.
  - Decision:
    - Checkout owns reconciliation internals against provider truth.
    - Ticketing consumes only canonical reconciliation outcome events when side-effects are required.
    - Cadence:
      - fast loop every 5 minutes over recent window (24h),
      - deep loop daily over rolling window (30 days, configurable).
    - Canonical mismatch classes:
      - `missing_capture`, `amount_mismatch`, `late_refund`, `orphan_event`, `status_divergence`.
    - Severity model:
      - `critical`: `missing_capture`, terminal `status_divergence`;
      - `high`: `amount_mismatch`, `orphan_event`;
      - `medium`: `late_refund`.
    - Auto-remediation boundary:
      - allow safe idempotent re-fetch/replay actions only;
      - no automatic ambiguous financial mutation; escalate to manual review.
  - Validation gate:
    - Reconciliation loops produce deterministic mismatch classification.
    - Replay/remediation actions remain idempotent and auditable.
    - Outbound canonical outcomes are sufficient for ticketing side-effects without exposing provider internals.

- [ ] 🟡 Provisional `CKO-06` Fiscal/tax document lifecycle ownership.
  - Maps to Ticketing: `TKT-44`.
  - Proposed decision:
    - Checkout owns fiscal trigger timing (`confirm|capture|async settlement`), retry/backoff, and compensation/escalation policy.
    - Ticketing stores only immutable fiscal reference linkage/status pointers required for entitlement/order audit correlation.
    - Fiscal processing must be asynchronous and must not block canonical ticketing confirmation semantics.
  - Validation gate:
    - Fiscal retry/compensation does not mutate ticketing frozen financial snapshots.
    - Canonical fiscal outcomes are emitted for ticketing consumption without provider-coupled semantics.

---

## Ticketing Mapping Contract
- [ ] ⚪ For each `CKO-*` decision, update corresponding Ticketing `TKT-*` entry with final adopted contract.
- [ ] ⚪ Keep one-way ownership clear: Checkout decides payment-event semantics; Ticketing consumes through stable contract.
- [ ] ⚪ Ensure no mutation of frozen snapshot values after confirmation.

## Deferred From MVP Ticketing (Checkout-Owned Validation)
- [ ] ⚪ Validate paid/deferred hold-SLA transitions end-to-end (checkout/webhook/reconciliation driven) and ensure deterministic hold state outcomes.
- [ ] ⚪ Validate idempotent checkout handoff snapshot reuse (`checkout_payload_snapshot` + `snapshot_hash`) under duplicate/out-of-order checkout callbacks.

### Applied Mapping Notes
- `CKO-01 -> TKT-22`:
  - Ticketing must consume Checkout canonical reducer states only.
  - Ticketing must not implement provider-native payment state interpretation.
- `CKO-02 -> TKT-22`:
  - Ticketing assumes Checkout delivers authenticity-verified, idempotent canonical events.
  - Ticketing must not implement provider signature verification logic.
- `CKO-03 -> TKT-23`:
  - Ticketing side-effects are event-driven (async jobs/listeners), not direct synchronous provider callbacks.
  - Checkout remains monetary source-of-truth; ticketing remains inventory/entitlement source-of-truth.
- `CKO-04 -> TKT-43`:
  - Dispute lifecycle is Checkout-owned; ticketing applies policy-driven entitlement side-effects from canonical dispute events.
  - Default dispute-open behavior is manual review; consumed entitlements are not auto-revoked.
- `CKO-05 -> TKT-24`:
  - Reconciliation internals stay Checkout-owned.
  - Ticketing reacts only to canonical reconciliation outcomes, never to provider mismatch internals.
- `CKO-06 -> TKT-44`:
  - Fiscal trigger timing and retry/compensation semantics are Checkout-owned.
  - Ticketing consumes canonical fiscal outcomes and retains immutable fiscal linkage references only.

---

## Validation Steps
- [ ] ⚪ Decision-level consistency check against Ticketing inherited constraints (`TKT-17/18/19/20/27`).
- [ ] ⚪ Contract simulation: webhook replay + out-of-order delivery do not regress terminal states.
- [ ] ⚪ Contract simulation: forged/stale webhook payloads are rejected by authenticity policy.
- [ ] ⚪ Contract simulation: refund/dispute flows preserve snapshot immutability and auditable side-effects.
- [ ] ⚪ Reconciliation simulation: mismatch classes map to deterministic escalation outcomes.

---

## Definition of Done
- [x] ✅ Production‑Ready `CKO-01..05` are approved with explicit contracts and transition rules.
- [ ] ⚪ Ticketing `PACK-A` (`TKT-22/23/24/43`) is unblocked with cross-referenced final decisions.
- [ ] ⚪ Ownership split between Checkout and Ticketing is explicit, testable, and documentation-synchronized.
