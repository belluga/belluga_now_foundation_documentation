# TODO (V1): Events Package Phase 3 (Improvements and Hardening)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Harden `belluga_events` for reliability, observability, and extension readiness after migration and decoupling.

---

## Scope
- Improve publication lifecycle robustness, error surfaces, and idempotency.
- Harden stream delta behavior and replay/reconnect guarantees.
- Add package-focused observability and diagnostics.
- Review indexes/query plans and optimize expensive aggregations.

---

## Out of Scope
- Breaking API contract changes unless separately approved.
- Non-Events domain expansion.

---

## Tasks
- [ ] ⚪ Add structured logs/metrics around event writes, stream deltas, and publication transitions.
- [ ] ⚪ Improve retry/backoff and failure handling for async listeners.
- [ ] ⚪ Evaluate and tune Mongo indexes for agenda/filter/stream queries.
- [ ] ⚪ Add resilience tests for stream reconnect and publication edge cases.
- [ ] ⚪ Final cleanup of migration-era compatibility wrappers.

---

## Validation Steps
- [ ] ⚪ Full Events backend test suite.
- [ ] ⚪ Targeted load/perf sampling for agenda and stream paths.
- [ ] ⚪ Manual smoke for publication transitions and SSE reconnect behavior.

---

## Definition of Done
- [ ] ⚪ Events package reliability baseline is documented and measurable.
- [ ] ⚪ Known bottlenecks and failure modes are mitigated or explicitly tracked.
- [ ] ⚪ Architecture docs reflect final post-hardening state.
