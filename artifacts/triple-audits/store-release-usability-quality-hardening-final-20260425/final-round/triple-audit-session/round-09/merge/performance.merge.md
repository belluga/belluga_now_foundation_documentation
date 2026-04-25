# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-09/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `needs_resolution`

## Merged Findings
### F-57937834 [high] Event stream delta replay is unbounded and materialized before streaming
- **Reviewers:** round-09-performance-security
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a dedicated bounded stream delta limit, apply it in the Mongo aggregation pipeline, avoid pre-materializing unbounded deltas before streaming, and add regression coverage with more than the cap worth of changed occurrences. Also consider rejecting or clipping excessively old Last-Event-ID cursors.
- **Rationale:** EventStreamRequest inherits AgendaIndexRequest validation, but EventQueryService::buildStreamDeltas ignores page_size/per_page and buildStreamPipeline never applies a limit. EventStreamController also builds the full deltas array before writing the StreamedResponse. A client with a valid but stale Last-Event-ID can force an aggregate over all updated/deleted occurrences since that timestamp, format every row into memory, and emit an unbounded response, defeating the public page-size hardening claimed elsewhere in the package.

### F-524C5380 [medium] Public offset pagination caps page size but not page depth
- **Reviewers:** round-09-performance-security
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Introduce a public page-depth cap or move these public surfaces to cursor/keyset pagination. Enforce the cap in FormRequests and defensive service normalization, then add negative tests for oversized page values on public agenda, events, and account-profile endpoints.
- **Rationale:** The public request validators enforce min:1 for page but no maximum, while the services compute skip as (page - 1) * page_size/perPage. This affects agenda, account-profile near/index pagination, and the occurrence-backed event index aggregation. Very large public page values can still force deep skip work even though page size is capped, so the current hardening does not fully bound query cost.

## Reviewer Summaries
### round-09-performance-security
- **Assessment:** Not clean. The bounded package resolves several prior fanout and query-shape issues, but the public event stream path still has an unbounded replay/memory surface, and public offset pagination remains bounded only by page size rather than by total skipped work.
- **Recommended path:** `needs_resolution`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] R09-PERFSEC-001 Event stream delta replay is unbounded and materialized before streaming: EventStreamRequest inherits AgendaIndexRequest validation, but EventQueryService::buildStreamDeltas ignores page_size/per_page and buildStreamPipeline never applies a limit. EventStreamController also builds the full deltas array before writing the StreamedResponse. A client with a valid but stale Last-Event-ID can force an aggregate over all updated/deleted occurrences since that timestamp, format every row into memory, and emit an unbounded response, defeating the public page-size hardening claimed elsewhere in the package.
  - [medium] R09-PERFSEC-002 Public offset pagination caps page size but not page depth: The public request validators enforce min:1 for page but no maximum, while the services compute skip as (page - 1) * page_size/perPage. This affects agenda, account-profile near/index pagination, and the occurrence-backed event index aggregation. Very large public page values can still force deep skip work even though page size is capped, so the current hardening does not fully bound query cost.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

