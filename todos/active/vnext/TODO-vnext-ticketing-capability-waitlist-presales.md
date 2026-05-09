# TODO (VNext): Ticketing Capability - Waitlist and Presales

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Define and deliver sold-out waitlist and presales lifecycles in VNext as Ticketing capabilities, keeping MVP focused on core hold/queue/anti-oversell safety and admission-session hard gate.

> Origin: deferred from MVP (`TKT-38` / `PACK-F` in ticketing master TODO) and expanded to include presales stream.

---

## Scope
- Define persistent sold-out waitlist lifecycle (entry, priority, offer window, expiration, reclaim).
- Define presales lifecycle (window scheduling, eligibility gates, transition to general sale).
- Keep both flows provider-neutral and scoped to ticketing domain contracts.
- Integrate both flows with core hold engine without bypassing inventory invariants.
- Define operational/audit requirements for lifecycle transitions and admin actions.

---

## Out of Scope
- Frontend UX implementation for waitlist/presale pages.
- Payment gateway/provider internals (owned by checkout domain).
- Seating provider coupling (covered by seating capability TODO).

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-checkout-package-integration.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-ticketing-capability-seating.md`

---

## Pending Decisions (Proposed Here, Pending Validation)
- [ ] 🟡 Provisional `WPS-01` Waitlist activation policy and scope.
  - Proposed decision:
    - Waitlist is optional by policy (`waitlist_enabled=true|false`), default `false`.
    - Scope follows purchase intent (`occurrence` for occurrence products, `event` for cross-occurrence bundle/passport intent).
  - Validation gate:
    - Disabled policy never creates waitlist entries.
- [ ] 🟡 Provisional `WPS-02` Waitlist ordering and tie-break contract.
  - Proposed decision:
    - Ordering is deterministic FIFO by `joined_at_utc`.
    - Tie-breaks: `entry_sequence` ascending, then `entry_id` lexical ascending.
  - Validation gate:
    - Recomputed order is stable and reproducible for same dataset.
- [ ] 🟡 Provisional `WPS-03` Waitlist offer SLA.
  - Proposed decision:
    - Offer windows are time-bound (`waitlist_offer_ttl_minutes`) and reclaim slot on expiry.
    - Resend/renew policy is explicit and auditable.
  - Validation gate:
    - Expired offers reclaim capacity deterministically without over-admission.
- [ ] 🟡 Provisional `WPS-04` Presales window model.
  - Proposed decision:
    - Presales is independent from sold-out waitlist.
    - Presales uses scheduled sale windows with explicit precedence and fallback to general sale.
  - Validation gate:
    - Window transitions are deterministic by server UTC and policy.
- [ ] 🟡 Provisional `WPS-05` Presales eligibility controls.
  - Proposed decision:
    - Eligibility can be policy-based (codes/groups/contracts), never bypassing hold/anti-oversell core invariants.
  - Validation gate:
    - Ineligible principals cannot allocate inventory during presale window.
- [ ] 🟡 Provisional `WPS-06` Lifecycle audit and admin override model.
  - Proposed decision:
    - Lifecycle changes are append-only auditable; manual overrides require explicit actor/reason metadata.
  - Validation gate:
    - Full transition history is reconstructible per entry/window.

---

## Tasks
- [ ] ⚪ Define waitlist aggregate/collection schema and indexes.
- [ ] ⚪ Define presales window schema and policy contracts.
- [ ] ⚪ Implement lifecycle state machines for waitlist entries and presale windows.
- [ ] ⚪ Implement reconciliation/expiry jobs for waitlist offers and window transitions.
- [ ] ⚪ Implement policy gates and admin override APIs with immutable audit.
- [ ] ⚪ Add tests for deterministic ordering, expiry reclaim, and window transitions.
- [ ] ⚪ Synchronize README/contracts/roadmap docs for waitlist + presales.

---

## Validation Steps
- [ ] ⚪ Waitlist ordering determinism tests (FIFO + tie-breaks).
- [ ] ⚪ Offer expiry/reclaim tests under contention.
- [ ] ⚪ Presales schedule transition tests (UTC boundary conditions).
- [ ] ⚪ Eligibility gate tests (allowed/blocked flows).
- [ ] ⚪ Audit trail tests for lifecycle + admin overrides.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Waitlist and presales are documented as independent, non-overlapping capabilities.
- [ ] ⚪ Both lifecycles are deterministic, auditable, and test-covered.
- [ ] ⚪ Core hold/queue/anti-oversell invariants remain intact (no capability bypass).
- [ ] ⚪ Documentation/contracts are synchronized with delivered behavior.

---

## Decision Log
- `WPS-00`: Decided. Waitlist and presales are deferred from MVP and tracked in VNext.
- `WPS-01..WPS-06`: Proposed in this TODO and pending validation before implementation.
