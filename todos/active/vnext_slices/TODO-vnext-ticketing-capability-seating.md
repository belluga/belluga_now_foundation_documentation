# TODO (VNext): Ticketing Capability - Seating (Seat Map / Assigned Seats)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Define and deliver assigned seating in VNext as a Ticketing capability with occurrence-level control and provider-agnostic contracts, keeping ticketing core decoupled from any specific seating vendor.

> Origin: deferred from MVP (`PACK-B` in ticketing master TODO) and moved to VNext planning.

---

## Scope
- Implement seating as optional ticketing capability (`ticketing.seating`) scoped per occurrence.
- Define provider-neutral seat object contract for reservation/commit/release.
- Integrate seating hold lifecycle with ticketing hold/queue anti-oversell engine.
- Support event-level seating template defaults with occurrence-level override.
- Provide adapter boundary for external providers (e.g., seats.io) without core coupling.
- Define operational reconciliation for seat-lock drift between local state and provider state.

---

## Out of Scope
- Frontend seat selection UX.
- Venue CAD/layout authoring tooling.
- Map POI projection logic (`map_poi` remains Events capability stream).
- Payment capture logic (covered by ticketing master TODO payment decisions).

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md`
- `foundation_documentation/todos/completed/TODO-v1-events-capability-map-poi.md`
- Multitenancy execution remains aligned with Spatie tenant migration/runtime model.

---

## Market Benchmark Inputs (Design Reference)
- `seats.io`:
  - hold-token lifecycle.
  - object status transitions (`free`, `reservedByToken`, `booked`).
  - batch status mutation with transaction semantics.
- `Attendize`:
  - feature parity baseline for paid seating ecosystems (service charges/refunds/waiting list implications).

Design consequence: seat capability must remain adapter-driven and reusable across providers while preserving strict local inventory invariants.

---

## Pending Decisions (Proposed Here, Pending Validation)
- [ ] 🟡 Provisional `SEAT-01` Capability ownership and activation.
  - Proposed decision:
    - Seating is owned by `belluga_ticketing` as optional capability.
    - Capability is toggled by tenant availability + occurrence enablement.
  - Validation gate:
    - Non-seating flows remain unaffected when capability is disabled.
- [ ] 🟡 Provisional `SEAT-02` Canonical seat assignment contract.
  - Proposed decision:
    - Persist provider-neutral structure:
      - `seat_assignment_ref.provider`
      - `seat_assignment_ref.provider_event_key`
      - `seat_assignment_ref.provider_object_id`
      - `seat_assignment_ref.label_snapshot`
      - `seat_assignment_ref.zone_snapshot`
    - Ticketing never stores provider chart internals as source-of-truth.
  - Validation gate:
    - Adapter swap does not require domain schema rewrite.
- [ ] 🟡 Provisional `SEAT-03` Hold synchronization model.
  - Proposed decision:
    - Local hold engine is source-of-truth for sellability.
    - Provider hold token is mirrored and reconciled, never replacing local state machine.
  - Validation gate:
    - Provider timeout/outage cannot confirm orders above local available capacity.
- [ ] 🟡 Provisional `SEAT-04` Scope model (event vs occurrence).
  - Proposed decision:
    - Event can define seating template defaults.
    - Effective seat inventory and seat locks are always occurrence-scoped.
  - Validation gate:
    - Different occurrences of same event can use different seating layouts/policies.
- [ ] 🟡 Provisional `SEAT-05` Reservation conflict strategy.
  - Proposed decision:
    - Conflicts resolved by first committed transactional hold.
    - Conflicting operations return deterministic seat-conflict error contract.
  - Validation gate:
    - Concurrent reservation tests show stable deterministic winner/loser behavior.
- [ ] 🟡 Provisional `SEAT-06` Reconciliation policy.
  - Proposed decision:
    - Scheduled job validates local seat locks vs provider status and repairs drift.
    - Drift classes: `local_only_hold`, `provider_only_hold`, `status_mismatch`.
  - Validation gate:
    - Reconciliation runbook and alert thresholds are documented and executable.

---

## Tasks
- [ ] ⚪ Define seating capability contracts (`SeatMapProviderContract`, `SeatHoldContract`, `SeatCommitContract`, `SeatReleaseContract`).
- [ ] ⚪ Define canonical seat assignment payload and persistence model.
- [ ] ⚪ Implement tenant/occurrence capability gate hooks for seating.
- [ ] ⚪ Implement hold synchronization path (local hold + provider hold token).
- [ ] ⚪ Implement drift reconciliation job and classification.
- [ ] ⚪ Add tenant-scoped migrations/indexes for seating runtime (`ticket_seat_assignments`, reconciliation metadata).
- [ ] ⚪ Add tests for seat reservation conflicts, timeout releases, and reconciliation.
- [ ] ⚪ Synchronize README/contracts/roadmap docs with seating capability contract.

---

## Validation Steps
- [ ] ⚪ Concurrent seat reservation tests under high contention.
- [ ] ⚪ Hold timeout release tests with queue advancement.
- [ ] ⚪ Provider adapter failure tests (timeout/5xx) preserving local invariants.
- [ ] ⚪ Reconciliation tests for each drift class.
- [ ] ⚪ Tenant-scoped migration/index validation for seating collections.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ Seating is fully capability-gated and provider-agnostic.
- [ ] ⚪ Occurrence-scoped seat assignment invariants are deterministic and test-covered.
- [ ] ⚪ Hold synchronization and drift reconciliation are implemented with alertable operations.
- [ ] ⚪ Documentation/contracts are synchronized with delivered behavior.

---

## Decision Log
- `SEAT-00`: Decided. Assigned seating is part of Ticketing bounded context, not Events capability layer.
- `SEAT-01..SEAT-06`: Proposed in this TODO and pending validation before implementation starts.
