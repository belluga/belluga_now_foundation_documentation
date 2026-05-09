# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/final-rerun-20260424T233855Z/triple-audit-session/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the tenant-admin taxonomy fanout before release readiness by adding a global catalog budget or demand-scoped term loading, reducing backend batch query fanout, and adding performance guards that assert total taxonomy payload/query ceilings. Add an occurrence aggregate guard for index-friendly temporal predicates or explain-budget coverage.`

## Merged Findings
### F-8FA734CF [medium] Tenant-admin taxonomy catalog remains globally unbounded across client chunks
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Constrain the admin rule catalog to allowed or visible taxonomies, add a total term budget, or switch to demand-loaded taxonomy terms. Add tests that fail when total fetched taxonomy IDs or total returned terms exceed the intended admin catalog budget.
- **Rationale:** The Flutter rule catalog repository chunks taxonomy term requests at 100 IDs, but it loops over every loaded taxonomy and requests up to 200 terms per taxonomy. The included test explicitly accepts 101 taxonomies as two batch calls, which proves request chunking but not a global payload or UI option ceiling. A tenant with many taxonomies can still force very large network payloads and thousands of rule-sheet options.

### F-AAD5D484 [medium] Management future occurrence filtering uses computed $expr instead of an index-friendly starts_at match
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Use direct match predicates for simple temporal buckets such as future, keep computed expressions only where necessary, and extend the guard to assert the aggregate match shape or capture an explain-based budget for the occurrence match stage.
- **Rationale:** The new management occurrence aggregate routes temporal filters through managementOccurrenceTemporalExprClauses. Even the simple future case becomes an $expr comparison against starts_at, while the provided performance test only checks use of a single facet pipeline and indexed event lookup. This leaves the high-volume occurrence match stage without evidence that it can use a starts_at index.

### F-7A8532CC [medium] Batch taxonomy term endpoint still performs one term query per taxonomy ID
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace the per-taxonomy query loop with a single aggregate/query strategy that enforces per-taxonomy limits server-side, or lower the request cap to match an explicit query budget. Add a test or instrumentation guard for maximum backend queries per batch request.
- **Rationale:** The backend batch endpoint validates a maximum of 100 taxonomy IDs per request, but TaxonomyTermManagementService::listBatch then runs a separate TaxonomyTerm query for each ID. At the documented maximum this is still up to 100 term queries for one HTTP request, and the Flutter client can issue multiple chunks.

## Reviewer Summaries
### performance
- **Assessment:** Mixed. The package establishes several useful caps and validates the main public catalog budgets, but the tenant-admin taxonomy catalog still composes large bounded chunks into effectively unbounded aggregate work, and the event occurrence aggregate path has an indexability risk that is not covered by the provided performance guard.
- **Recommended path:** `Resolve the tenant-admin taxonomy fanout before release readiness by adding a global catalog budget or demand-scoped term loading, reducing backend batch query fanout, and adding performance guards that assert total taxonomy payload/query ceilings. Add an occurrence aggregate guard for index-friendly temporal predicates or explain-budget coverage.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] PERF-001 Tenant-admin taxonomy catalog remains globally unbounded across client chunks: The Flutter rule catalog repository chunks taxonomy term requests at 100 IDs, but it loops over every loaded taxonomy and requests up to 200 terms per taxonomy. The included test explicitly accepts 101 taxonomies as two batch calls, which proves request chunking but not a global payload or UI option ceiling. A tenant with many taxonomies can still force very large network payloads and thousands of rule-sheet options.
  - [medium] PERF-002 Batch taxonomy term endpoint still performs one term query per taxonomy ID: The backend batch endpoint validates a maximum of 100 taxonomy IDs per request, but TaxonomyTermManagementService::listBatch then runs a separate TaxonomyTerm query for each ID. At the documented maximum this is still up to 100 term queries for one HTTP request, and the Flutter client can issue multiple chunks.
  - [medium] PERF-003 Management future occurrence filtering uses computed $expr instead of an index-friendly starts_at match: The new management occurrence aggregate routes temporal filters through managementOccurrenceTemporalExprClauses. Even the simple future case becomes an $expr comparison against starts_at, while the provided performance test only checks use of a single facet pipeline and indexed event lookup. This leaves the high-volume occurrence match stage without evidence that it can use a starts_at index.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

