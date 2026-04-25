# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/store-release-usability-quality-final-20260424T202659Z/package-triple-audit-20260424T202815Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `not_evaluated`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the unbounded public catalog payload and event occurrence/programming fanout before marking the performance lane clean.`

## Merged Findings
### F-65D46584 [high] Public discovery catalog materializes every allowed taxonomy term and blocks initial list loading
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Bound the catalog contract: return only taxonomy metadata plus selected/popular terms, add term search/pagination per taxonomy, or enforce a documented max-term cap with tests that fail on unbounded TaxonomyTerm::get() materialization and validate initial navigation request order.
- **Rationale:** The new public catalog service queries all allowed taxonomies and calls get() for every matching TaxonomyTerm without a limit, page, cache validator, or search window. Flutter discovery and home controllers await catalog loading before initial list/agenda loading, causing potential backend full-collection read, network expansion, decode cost, and first-content waterfall.

### F-EF07F862 [medium] Event occurrence and programming payloads are unbounded across write, read, and render paths
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Establish explicit launch limits for occurrences per event and programming items/profile links per occurrence, enforce them in Laravel validation/domain services, and add a performance guard that exercises a max-sized event detail response plus Flutter render path.
- **Rationale:** The branch removes the previous multiple-occurrence capability/max guard and adds occurrence-owned profiles/programming without replacement cardinality limits for occurrences, programming_items, or account_profile_ids. Public detail and management formatters load all occurrences for an event, and detail widgets render occurrence/date/programming collections eagerly.

## Reviewer Summaries
### performance
- **Assessment:** The package includes important performance improvements versus dev, especially cursor backfills, batch taxonomy loading, and management occurrence aggregation, but two bounded scalability risks remain.
- **Recommended path:** `Resolve the unbounded public catalog payload and event occurrence/programming fanout before marking the performance lane clean.`
- **Performance:** `mixed`
- **Elegance:** `not_evaluated`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-001 Public discovery catalog materializes every allowed taxonomy term and blocks initial list loading: The new public catalog service queries all allowed taxonomies and calls get() for every matching TaxonomyTerm without a limit, page, cache validator, or search window. Flutter discovery and home controllers await catalog loading before initial list/agenda loading, causing potential backend full-collection read, network expansion, decode cost, and first-content waterfall.
  - [medium] PERF-002 Event occurrence and programming payloads are unbounded across write, read, and render paths: The branch removes the previous multiple-occurrence capability/max guard and adds occurrence-owned profiles/programming without replacement cardinality limits for occurrences, programming_items, or account_profile_ids. Public detail and management formatters load all occurrences for an event, and detail widgets render occurrence/date/programming collections eagerly.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

