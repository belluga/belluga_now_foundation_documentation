# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/canonical-event-image-resolver-20260525/triple-audit-session/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Do not close this round as clean until AccountProfileAgendaOccurrencesService applies an explicit public bound, pagination contract, or chunk/windowed formatting path before calling formatEvents(). Keep the EventQueryService parent lookup, but only feed it bounded slices or make the formatter enforce a bounded contract.`

## Merged Findings
### F-EFAFF1CF [high] Account profile agenda now fetches and formats an unbounded public occurrence set
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add an explicit bounded contract for account-profile agenda occurrences, such as page/page_size with the same public max used by agenda, or a fixed upcoming-occurrence cap. Fetch only that bounded slice before calling formatEvents(); if all results are truly required, introduce a chunked parent-context formatter and avoid a single unbounded get()/whereIn()/in-memory response path.
- **Rationale:** AccountProfileAgendaOccurrencesService::forProfile builds a public-facing query for published future occurrences, orders it, and calls get() with no limit or pagination before passing the full Collection to EventQueryService::formatEvents(). formatEvents() then materializes the iterable again and performs a whereIn parent Event lookup across all occurrence event_ids. For a venue/profile with high-cardinality future occurrences, one request can load all matching occurrence models, build a large parent-id candidate list, load all parent Events, and format the full response in memory.

## Reviewer Summaries
### performance
- **Assessment:** The main agenda endpoint path is bounded: it slices to the public page-size cap before calling formatEvents(), so the new parent Event lookup stays page-scoped there. However, the account-profile agenda path now materializes every future matching occurrence and then batch-loads parent Events for the full result set, which is a concrete public-runtime fetch-all/resource-amplification risk.
- **Recommended path:** `Do not close this round as clean until AccountProfileAgendaOccurrencesService applies an explicit public bound, pagination contract, or chunk/windowed formatting path before calling formatEvents(). Keep the EventQueryService parent lookup, but only feed it bounded slices or make the formatter enforce a bounded contract.`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] PERF-001 Account profile agenda now fetches and formats an unbounded public occurrence set: AccountProfileAgendaOccurrencesService::forProfile builds a public-facing query for published future occurrences, orders it, and calls get() with no limit or pagination before passing the full Collection to EventQueryService::formatEvents(). formatEvents() then materializes the iterable again and performs a whereIn parent Event lookup across all occurrence event_ids. For a venue/profile with high-cardinality future occurrences, one request can load all matching occurrence models, build a large parent-id candidate list, load all parent Events, and format the full response in memory.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
