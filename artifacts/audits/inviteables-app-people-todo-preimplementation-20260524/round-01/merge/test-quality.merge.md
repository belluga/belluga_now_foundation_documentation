# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/inviteables-app-people-todo-preimplementation-20260524/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`

## Recommended Paths
- `Update the TODO before implementation to require explicit fail-first tests for projection staleness/no-repair behavior, projection-refresh mutation sources, route-level Flutter cache persistence during contact-import refresh, 1200+ contact request-budget instrumentation, and CI-real-backend/no-mock execution evidence.`

## Merged Findings
### F-93B06381 [high] Request-budget guardrail needed exact instrumentation
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Define the exact measured surface for the 1200+ fixture, forbidden method/endpoint calls, and expected request budget so the current chunk fanout fails before implementation.
- **Rationale:** A pass-the-test repair could use a small fixture, broad mocks, or assertions on rendered data only, failing to catch the high-cardinality request loop that motivated the TODO.

### F-3AC52427 [high] Repeated-entry Flutter route behavior needed explicit coverage
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require a Flutter widget/integration regression test that enters the invite-share app-people pane, has cached inviteables available, simulates contact-import refresh continuing or re-entry, and asserts inviteables remain rendered without empty/loading regression.
- **Rationale:** Repository/unit tests can pass while the route still clears controller-local state, blocks on contact import refresh, or conflates inviteables cache with occurrence status overlay.

### F-8F78C2F6 [high] CI and real-backend execution gates were not explicit
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add mandatory CI evidence requirements for Laravel projection tests against the real local backend and Flutter route/repository tests with production-parity wiring or explicit domain overrides, with no silent mock fallback.
- **Rationale:** Without an execution gate, the TODO can be satisfied by local-only or mock-backed tests that do not prove production backend semantics or CI promotion safety.

### F-A9B48ACA [high] Backend projection contract tests were not explicit enough
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require fail-first Laravel tests that seed stale or empty projection state plus valid intermediate sources and assert GET does not reconstruct, then cover each listed mutation source and assert projection refresh/materialization.
- **Rationale:** An implementation could add a projection while still passing tests that only validate final GET payloads, leaving stale projections or hidden request-time reconstruction unprotected.

## Reviewer Summaries
### test-quality
- **Assessment:** blocking: the planned direction is coherent, but the bounded package does not yet specify enough fail-first, real-behavior test gates for backend projection semantics, Flutter repeated-entry behavior, request-budget enforcement, and CI/real-backend execution.
- **Recommended path:** `Update the TODO before implementation to require explicit fail-first tests for projection staleness/no-repair behavior, projection-refresh mutation sources, route-level Flutter cache persistence during contact-import refresh, 1200+ contact request-budget instrumentation, and CI-real-backend/no-mock execution evidence.`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQ-01 Backend projection contract tests were not explicit enough: An implementation could add a projection while still passing tests that only validate final GET payloads, leaving stale projections or hidden request-time reconstruction unprotected.
  - [high] TQ-02 Repeated-entry Flutter route behavior needed explicit coverage: Repository/unit tests can pass while the route still clears controller-local state, blocks on contact import refresh, or conflates inviteables cache with occurrence status overlay.
  - [high] TQ-03 Request-budget guardrail needed exact instrumentation: A pass-the-test repair could use a small fixture, broad mocks, or assertions on rendered data only, failing to catch the high-cardinality request loop that motivated the TODO.
  - [high] TQ-04 CI and real-backend execution gates were not explicit: Without an execution gate, the TODO can be satisfied by local-only or mock-backed tests that do not prove production backend semantics or CI promotion safety.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

