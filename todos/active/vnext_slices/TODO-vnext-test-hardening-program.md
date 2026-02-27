# TODO (VNext): Test Hardening Program (Full Suite Reliability)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active  
**Owners:** Backend Team + Flutter Team + Platform  
**Objective:** Eliminate silent false positives and flakiness across Laravel, Flutter, and Web test layers, so CI failures are always actionable and compatibility regressions are caught early.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-flutter-test-foundation.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- Skill baseline: `/home/elton/.codex/skills/public/test-quality-audit/SKILL.md`

---

## Scope
- Laravel unit/feature/integration hardening.
- Flutter unit/widget/integration contract hardening.
- Web navigation/boot integrity hardening.
- CI gates for deterministic, strict, and non-bypass test behavior.

## Out of Scope
- New product features.
- Broad test framework migration.
- Performance benchmarking for production runtime (this TODO is test reliability focused).

---

## Workstreams

### A) Determinism and isolation
- [ ] ⚪ Remove or neutralize cross-test shared state where it can leak behavior.
- [ ] ⚪ Enforce per-test cleanup for mutable collections used by feature tests.
- [ ] ⚪ Add random-order test execution to CI for Laravel and fail on order-dependent behavior.
- [ ] ⚪ Add repeated-run sampling for critical suites (same suite N times) to catch intermittent failures.

### B) Assertion strictness and bypass elimination
- [ ] ⚪ Block permissive request assertions that can pass on unrelated requests.
- [ ] ⚪ Block `catch + continue` patterns that do not assert expected failure explicitly.
- [ ] ⚪ Add a CI audit step to fail on known bypass patterns in changed tests.
- [ ] ⚪ Ensure all external call assertions validate target URL + payload invariants explicitly.

### C) Auth and scope matrix coverage
- [ ] ⚪ Add allow/deny matrix tests for critical settings namespaces (`push`, `telemetry`, `events`) by token ability.
- [ ] ⚪ Add tenant/landlord/account boundary tests for routes with similar paths.
- [ ] ⚪ Add negative tests for cross-tenant data access and scope confusion.

### D) External integration failure-path coverage
- [ ] ⚪ Add tests for telemetry delivery on HTTP timeout/5xx and ensure retry/backoff behavior is asserted.
- [ ] ⚪ Add tests for FCM failures that verify no silent success accounting.
- [ ] ⚪ Add idempotency regression tests for reprocessed jobs/events.

### E) Contract and schema hardening
- [ ] ⚪ Add strict JSON contract assertions for critical endpoints (required keys, types, and incompatible payload rejection).
- [ ] ⚪ Add regression tests for route parameter binding mistakes (example: wrong parameter captured in controller signature).
- [ ] ⚪ Add schema-version drift checks where client/server contracts are versioned.

### F) CI hardening gates
- [ ] ⚪ Add mandatory test-quality audit job to PR CI.
- [ ] ⚪ Keep Mongo replica set local requirement explicit in Laravel CI and block Atlas usage in test lane.
- [ ] ⚪ Keep web/flutter metadata compatibility gate strict for stage/main and advisory-only for dev.
- [ ] ⚪ Publish a compact evidence artifact (what was audited + why it passed/failed).

---

## Suggested execution order
1. Bypass elimination and strict assertions (Workstream B).  
2. Isolation and deterministic execution (Workstream A).  
3. Auth/scope matrix and cross-tenant safety (Workstream C).  
4. External failure paths and idempotency (Workstream D).  
5. Contract hardening and CI consolidation (Workstreams E/F).

---

## Validation commands (baseline)
- Laravel full suite: `docker compose exec app php artisan test --compact`
- Laravel random order sample: `docker compose exec app php artisan test --order-by=random --compact`
- Flutter suite: `cd flutter-app && fvm flutter test`
- Web navigation tests: `cd web-app && npx playwright test`

---

## Definition of Done
- [ ] ⚪ No known bypass patterns in changed tests.
- [ ] ⚪ Critical suites are deterministic under repeated and randomized execution.
- [ ] ⚪ Auth/scope matrix is covered for all security-sensitive settings endpoints.
- [ ] ⚪ External integration failure paths are asserted (not only happy paths).
- [ ] ⚪ CI contains an explicit, failing gate for test-quality audit rules.
- [ ] ⚪ Hardening evidence is documented and linked from this TODO.
