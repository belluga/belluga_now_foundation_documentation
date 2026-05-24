# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/inviteables-app-people-todo-preimplementation-20260524/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Revise the TODO before implementation to define bounded projection materialization semantics, deployment/backfill gates, and backend performance evidence for the new read model.`

## Merged Findings
### F-E0A4AB1C [high] Projection refresh triggers were not bounded
- **Reviewers:** performance-operational-fit
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require tenant/user/contact-bounded refresh semantics per event type, idempotent materialization, explicit batch limits, and a ban on full projection rebuilds outside controlled backfill/maintenance jobs.
- **Rationale:** Without affected-scope resolution, incremental upsert/delete behavior, batching, idempotency, or coalescing, implementation can drift into fetch-all reconciliation, high-cardinality recompute, or write amplification on broad profile/capability/user-state changes.

### F-BE7FDA4A [high] Hard cutoff lacked projection backfill and readiness gate
- **Reviewers:** performance-operational-fit
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a required backfill/bootstrap plan, completion marker or health gate, cutover expectation, and tests proving existing matched contacts are visible from the projection immediately after deployment.
- **Rationale:** No backward compatibility and no GET repair are unsafe unless existing contacts are backfilled and readiness is proven before the endpoint depends exclusively on the projection.

### F-E8858467 [high] Backend read-model performance evidence was underspecified
- **Reviewers:** performance-operational-fit
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extend acceptance criteria with backend performance tests for /contacts/inviteables on large fixtures: bounded query count, projection-first read path, required indexes, no N+1 enrichment, and no fallback reconstruction.
- **Rationale:** The TODO did not require query-count, query-shape, index, or projection-only assertions that would catch accidental read-time reconstruction from contact_hash_directory, favorite_edges, profiles, users, or capability tables.

## Reviewer Summaries
### performance-operational-fit
- **Assessment:** The direction is sound, but the TODO is not ready for implementation because the projection refresh and hard-cutover plan leave concrete runtime and operational failure modes open.
- **Recommended path:** `Revise the TODO before implementation to define bounded projection materialization semantics, deployment/backfill gates, and backend performance evidence for the new read model.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] PERF-001 Projection refresh triggers were not bounded: Without affected-scope resolution, incremental upsert/delete behavior, batching, idempotency, or coalescing, implementation can drift into fetch-all reconciliation, high-cardinality recompute, or write amplification on broad profile/capability/user-state changes.
  - [high] OPS-001 Hard cutoff lacked projection backfill and readiness gate: No backward compatibility and no GET repair are unsafe unless existing contacts are backfilled and readiness is proven before the endpoint depends exclusively on the projection.
  - [high] PERF-002 Backend read-model performance evidence was underspecified: The TODO did not require query-count, query-shape, index, or projection-only assertions that would catch accidental read-time reconstruction from contact_hash_directory, favorite_edges, profiles, users, or capability tables.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

