# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-recut-quality-20260424/store-release-usability-recut-quality-triple-audit-20260424T160027Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not promote until the backend query-shape findings are either corrected or backed by load/query-count evidence. Prioritize fixing event admin occurrence filtering and formatter N+1 behavior, then cap or lazy-load discovery taxonomy catalog terms.`

## Merged Findings
### F-C31EDE26 [high] Occurrence-first admin event filtering plucks all matching occurrence event ids before pagination
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make the occurrence-first list query bounded at the database/page boundary. Either paginate from EventOccurrence as the primary source and hydrate parent events in bulk, maintain indexed next/temporal occurrence projection fields on Event, or use a bounded aggregation/distinct strategy with explicit indexes. Add a query-count or load-shaped test fixture with many events and occurrences.
- **Rationale:** The EventQueryService diff replaces root event temporal filtering with resolveManagementOccurrenceEventIds(), which queries EventOccurrence, orders by starts_at, plucks every matching event_id, uniques them in PHP, then applies a large whereIn('_id', ...) to the Event query. For common admin filters such as future or date buckets, every page now does work proportional to all matching occurrences, not the requested page size. This is a hidden performance regression for Event admin and can create large in-memory arrays and large Mongo $in predicates.

### F-C5A8EFA8 [high] Management event formatting adds per-event occurrence queries
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Bulk-load occurrences for the page of event ids once, group them by event_id, and pass the grouped rows into the formatter for both occurrence summaries and occurrence-owned parties. Add a regression test or instrumentation assertion that event admin pagination does not perform per-row occurrence lookups.
- **Rationale:** formatManagementEvent() now calls resolveOccurrenceOwnedEventParties($event), which performs an EventOccurrence query per event, while resolveEventOccurrences($event) also queries occurrences per formatted event. A normal admin events page can therefore issue multiple occurrence queries per row after the page has already been selected. This is a classic N+1 shape and directly affects the Event admin surfaces called out by the package.

### F-4BF49069 [medium] Discovery filter catalogs fetch and render every term for each allowed taxonomy
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Introduce a catalog size budget: cap initial taxonomy terms, lazy-load terms after primary selection, or add a term-search endpoint per taxonomy/type scope. Add tests or evidence for maximum catalog payload size and widget build behavior under a large taxonomy fixture.
- **Rationale:** DiscoveryFiltersController builds taxonomy_options by loading all TaxonomyTerm rows for every allowed taxonomy with an unbounded get(), and the Flutter DiscoveryFilterBar renders those terms as chips. That is operationally fragile for launch tenants with large taxonomy catalogs: Home and Discovery boot can receive a large catalog payload and build a large widget tree even when the user has only selected one primary type.

## Reviewer Summaries
### performance
- **Assessment:** Mixed. The Flutter event taxonomy recut correctly moves the form away from per-taxonomy term loops into a single batch endpoint, and the validated behavior is directionally sound. However, the backend event/query and discovery-filter catalog changes introduce unbounded query shapes and list-time occurrence lookups that are likely to regress performance under realistic tenant data volume.
- **Recommended path:** `Do not promote until the backend query-shape findings are either corrected or backed by load/query-count evidence. Prioritize fixing event admin occurrence filtering and formatter N+1 behavior, then cap or lazy-load discovery taxonomy catalog terms.`
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-001 Occurrence-first admin event filtering plucks all matching occurrence event ids before pagination: The EventQueryService diff replaces root event temporal filtering with resolveManagementOccurrenceEventIds(), which queries EventOccurrence, orders by starts_at, plucks every matching event_id, uniques them in PHP, then applies a large whereIn('_id', ...) to the Event query. For common admin filters such as future or date buckets, every page now does work proportional to all matching occurrences, not the requested page size. This is a hidden performance regression for Event admin and can create large in-memory arrays and large Mongo $in predicates.
  - [high] PERF-002 Management event formatting adds per-event occurrence queries: formatManagementEvent() now calls resolveOccurrenceOwnedEventParties($event), which performs an EventOccurrence query per event, while resolveEventOccurrences($event) also queries occurrences per formatted event. A normal admin events page can therefore issue multiple occurrence queries per row after the page has already been selected. This is a classic N+1 shape and directly affects the Event admin surfaces called out by the package.
  - [medium] PERF-003 Discovery filter catalogs fetch and render every term for each allowed taxonomy: DiscoveryFiltersController builds taxonomy_options by loading all TaxonomyTerm rows for every allowed taxonomy with an unbounded get(), and the Flutter DiscoveryFilterBar renders those terms as chips. That is operationally fragile for launch tenants with large taxonomy catalogs: Home and Discovery boot can receive a large catalog payload and build a large widget tree even when the user has only selected one primary type.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

