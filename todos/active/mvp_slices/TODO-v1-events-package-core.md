# TODO (V1): Events Package Program (Core)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver Events as a first-class Laravel package with phased execution: migration-first, decoupling, then hardening.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-and-agenda-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `laravel-app/packages/belluga/belluga_push_handler`

---

## A) Phase Split (Canonical)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-1.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-2.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`

---

## B) Cross-Phase Invariants (Locked)
- [x] ✅ Production‑Ready API contract evolution is intentional and phase-bound; Phase 3 delivers Laravel occurrence-first contracts with hard cutover and no compatibility bridge.
- [x] ✅ Production‑Ready Event and Invite ownership boundary stays unchanged (`Invites` owns invite transaction lifecycle).
- [x] ✅ Production‑Ready Events remains compatible with current tenant/domain resolution model.
- [x] ✅ Production‑Ready Realtime behavior (`/events/stream` + `/agenda` rehydrate policy) remains unchanged unless explicitly planned.

---

## C) Out of Scope (Program Level)
- Event check-in workflows.
- Invite-domain business ownership changes.
- Unplanned/undocumented API contract changes outside approved phase decisions.

---

## D) Program Definition of Done
- [x] ✅ Production‑Ready Phase 1 complete and stable in production behavior.
- [x] ✅ Production‑Ready Phase 2 complete with package internals decoupled from app-layer implementation details.
- [ ] ⚪ Phase 3 complete with hardening/performance/observability improvements.
- [ ] ⚪ Foundation docs + roadmap + endpoint contracts synchronized with final architecture.

---

## E) Pending Decision Registry
- Phase 2 pending decisions are tracked in:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-2.md` (`D2-01` to `D2-05`).
- Phase 3 pending decisions are tracked in:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (`D3-01` to `D3-05`).

---

## F) Standards Baseline (Locked)
- **Laravel/package conventions**
  - Use PSR-4 package namespace (`Belluga\\Events\\...`) and Laravel container contracts/bindings for integrations.
  - Keep package internals framework-standard: service providers, contracts, events/listeners, queued jobs.
- **Code conventions**
  - Follow PSR-12 and existing repository coding style.
  - Prefer explicit, typed payload/value structures for domain events and public contracts.
  - Validate important milestones with full Laravel suite (`php artisan test`).
- **MongoDB + date/time conventions**
  - Persist date/time values as UTC in MongoDB (`UTCDateTime`/driver equivalent through Laravel casting).
  - Use Carbon (prefer immutable flow where practical) for date handling in domain/application code.
  - Expose timestamps to clients in ISO-8601 UTC; client apps localize only at presentation layer.
- **Consistency conventions**
  - Use MongoDB multi-document transactions for cross-collection consistency when infrastructure supports it.
  - Transactions are mandatory for this program; if unavailable, fail fast with a meaningful runtime error and block rollout.
- **Index/query conventions**
  - Align indexes to canonical filter + sort patterns and keep agenda/stream queries deterministic.
  - Prefer occurrence-first query units when Phase 3 occurrence model is active.

---

## G) Exception Register (Approved Deviations)
- `EX-01` (Phase 2): runtime model remains Event-only (no first-class `EventOccurrence` aggregate yet).
  - Source: `D2-02` in `TODO-v1-events-package-phase-2.md`.
- `EX-02` (Phase 3): no backward-compatibility bridge; clients must adopt occurrence-first contracts.
  - Source: `D3-05` in `TODO-v1-events-package-phase-3.md`.
- `EX-03` (Phase 3): publication source-of-truth is Event-level; occurrence publication flags are mirrored/derived for query performance.
  - Source: `D3-02` in `TODO-v1-events-package-phase-3.md`.

---

## H) Operational Decisions (Locked)
- [x] ✅ Production‑Ready `OD-01` Phase 3 cutover policy:
  - Execute Phases 1-3 as Laravel-only; Flutter integration is deferred to a separate TODO after Phase 3 completion.
- [x] ✅ Production‑Ready `OD-02` Transaction fallback policy:
  - Transactions are mandatory; block Phase 3 rollout and fail fast with meaningful error when unavailable in target runtime.
- [x] ✅ Production‑Ready `OD-03` Stream reconnect policy:
  - No replay buffer/retention is required; login/reconnect must rehydrate current state and start stream from now.
- [x] ✅ Production‑Ready `OD-04` Async side-effect SLO (Strict):
  - Lag target: `p95 <= 15s`, `p99 <= 60s` for async side effects.
  - Queue staleness alert: trigger when max age is `> 60s` for 5 minutes.
  - Retry policy: 5 attempts with exponential backoff; then DLQ.
  - DLQ policy: alert on any new DLQ item.
  - Reconciliation cadence: run consistency reconciliation every 15 minutes.

---

## I) Operational Decision Log
- `OD-01`: Decided. Program execution is Laravel-only through Phase 3; Flutter integration is deferred to a separate post-Phase-3 TODO, and backward compatibility is not required.
- `OD-02`: Decided. Transactions are mandatory; rollout is blocked unless support is confirmed, and runtime must fail fast with meaningful error if unavailable.
- `OD-03`: Decided. Replay is disabled by design; reconnect/login always performs full current-state rehydrate.
- `OD-04`: Decided. Strict async SLO profile is adopted for listeners/jobs.
  - SLO: `p95 <= 15s`, `p99 <= 60s`.
  - Reliability controls: 5 retries (exponential backoff) + DLQ; alert on any new DLQ entry.
  - Operational guardrail: queue max-age alert at `> 60s` for 5 minutes; reconciliation every 15 minutes.
