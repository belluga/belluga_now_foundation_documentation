# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-02/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Block closure until the account-scoped programming location checks and taxonomy resolver caching have regression tests and passing evidence.`

## Merged Findings
### F-0192EDB0 [high] Account-scoped event writes can attach foreign programming physical hosts
- **Reviewers:** round-02-performance-security
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-scoped-programming-location-ownership`
- **Suggested action:** Pass _account_context_id into account-scoped updates, enforce ownership for every programming place_ref, and add negative create/update tests.
- **Rationale:** Account-scoped event update did not carry the account context through all write paths, and programming item place_ref validation did not enforce that the physical host belongs to the target account.

### F-A542703B [medium] Legacy taxonomy snapshot fallback can repeatedly query the same taxonomy and term labels
- **Reviewers:** round-02-performance-security
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `taxonomy-summary-resolver-cache`
- **Suggested action:** Add a request/per-instance cache or bulk resolver for taxonomy and term fallback lookups, with query-count guardrail coverage.
- **Rationale:** TaxonomyTermSummaryResolverService could perform repeated lookups for identical legacy type/value pairs when payloads lack snapshots, increasing list/detail query cost.

## Reviewer Summaries
### round-02-performance-security
- **Assessment:** The delivery closes major behavior but exposes one account-scoping security gap and one query-amplification risk in legacy taxonomy snapshot resolution.
- **Recommended path:** `Block closure until the account-scoped programming location checks and taxonomy resolver caching have regression tests and passing evidence.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERFSEC-R02-001 Account-scoped event writes can attach foreign programming physical hosts: Account-scoped event update did not carry the account context through all write paths, and programming item place_ref validation did not enforce that the physical host belongs to the target account.
  - [medium] PERFSEC-R02-002 Legacy taxonomy snapshot fallback can repeatedly query the same taxonomy and term labels: TaxonomyTermSummaryResolverService could perform repeated lookups for identical legacy type/value pairs when payloads lack snapshots, increasing list/detail query cost.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

