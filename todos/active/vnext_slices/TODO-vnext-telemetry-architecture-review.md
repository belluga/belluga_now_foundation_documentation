# TODO (VNext): Telemetry Architecture Review and Consolidation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] Production-Ready`  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Re-establish a canonical telemetry architecture with explicit ownership boundaries, deterministic trigger matrix, and full regression coverage for event emission flows.

---

## Context
- Telemetry currently works, but architecture is mixed: `TelemetryRepository` combines constructor injection with `GetIt` lookups inside methods.
- Event trigger points are partially tested, but coverage is not yet complete across all repository methods and forwarding flows.
- We need a canonical model that keeps ergonomics for callers, without hidden coupling.

## Scope
- Define and document canonical telemetry architecture for Flutter:
  - telemetry API surface (contract + optional façade),
  - dependency ownership (`GetIt` boundaries),
  - event trigger ownership (where each event should be emitted),
  - idempotency rules.
- Establish an explicit telemetry trigger matrix:
  - event name,
  - owner layer (screen/controller/repository/app),
  - mandatory properties,
  - timing semantics (instant vs timed).
- Expand automated tests to cover all critical telemetry paths.

## Tasks
- [ ] ⚪ Pending — Publish architecture decision for telemetry access pattern (`contract-only` vs `contract + static façade`) and freeze the choice.
- [ ] ⚪ Pending — Remove mixed dependency style from `TelemetryRepository` (no hidden `GetIt` resolution inside core methods) or formally justify exceptions in docs.
- [ ] ⚪ Pending — Add missing repository-level tests for `startTimedEvent`, `finishTimedEvent`, `flushTimedEvents`, `buildLifecycleObserver`, and `mergeIdentity`.
- [ ] ⚪ Pending — Add direct regression tests for telemetry callsites not yet asserted (for example favorites toggle and push telemetry forwarding).
- [ ] ⚪ Pending — Consolidate canonical telemetry trigger matrix in foundation docs and align implementation names/properties to that matrix.
- [ ] ⚪ Pending — Add guardrails/checklist to prevent future telemetry trigger drift without test coverage.

## Acceptance Criteria
- [ ] ⚪ Pending — One canonical telemetry architecture is documented and reflected in code.
- [ ] ⚪ Pending — Telemetry repository has complete behavioral coverage for public contract methods.
- [ ] ⚪ Pending — Critical trigger flows have explicit tests validating event name + required properties.
- [ ] ⚪ Pending — Trigger matrix is documented and used as the source of truth for future telemetry additions.

## Out of Scope
- Replacing telemetry providers/platform stack.
- Net-new product analytics strategy beyond architecture consolidation.

