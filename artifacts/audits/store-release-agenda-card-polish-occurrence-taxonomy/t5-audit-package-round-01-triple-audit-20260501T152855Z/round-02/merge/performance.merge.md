# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-02/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close the performance lane cleanly until the Pending-only Event Search path stops auto-paging through unfiltered agenda pages. Prefer a backend-supported pending occurrence filter or an explicit bounded lookup contract; if that contract is out of scope for this slice, remove or tightly cap auto-page behavior for client-only pending filtering and record the UX limitation.`

## Merged Findings
### F-DBB4E9A2 [high] Pending-only Event Search can page-walk unfiltered agenda results
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `agenda-status-filter-no-client-page-walk`
- **Suggested action:** Make Pending-only a bounded server-backed query path, such as passing pending occurrence ids or an invite-owned pending-only filter into the agenda/search contract, and cover it with a test that proves no unfiltered auto-page loop occurs. If the backend contract is intentionally deferred, disable or cap _autoPageToFirstMatch for pendingOnly so the UI cannot walk every page.
- **Rationale:** The new InviteFilter.pendingOnly state filters pending occurrence ids locally in EventSearchScreenController._applyInviteFilter, but repository fetches still send confirmedOnly only for confirmedOnly. When the local pending filter produces no visible rows and the backend says hasMore, _maybeAutoPage starts _autoPageToFirstMatch, which repeatedly calls loadMoreEventSearch until a pending match appears or the agenda pages are exhausted. This is a concrete request-loop risk from a single status action: on tenants with many agenda occurrences and sparse pending invites, the client can issue a sequence of backend /agenda page requests where a server-side pending-occurrence query or bounded targeted lookup is required.

## Reviewer Summaries
### performance
- **Assessment:** Mixed. The round-01 taxonomy fanout guard is present, public taxonomy filtering remains on denormalized occurrence documents, and the hot agenda card layout no longer uses IntrinsicHeight. However, the Event Search Pending-only status path can still trigger automatic page walking over the agenda endpoint because pending invites are filtered only after each fetched page.
- **Recommended path:** `Do not close the performance lane cleanly until the Pending-only Event Search path stops auto-paging through unfiltered agenda pages. Prefer a backend-supported pending occurrence filter or an explicit bounded lookup contract; if that contract is out of scope for this slice, remove or tightly cap auto-page behavior for client-only pending filtering and record the UX limitation.`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-R02-001 Pending-only Event Search can page-walk unfiltered agenda results: The new InviteFilter.pendingOnly state filters pending occurrence ids locally in EventSearchScreenController._applyInviteFilter, but repository fetches still send confirmedOnly only for confirmedOnly. When the local pending filter produces no visible rows and the backend says hasMore, _maybeAutoPage starts _autoPageToFirstMatch, which repeatedly calls loadMoreEventSearch until a pending match appears or the agenda pages are exhausted. This is a concrete request-loop risk from a single status action: on tenants with many agenda occurrences and sparse pending invites, the client can issue a sequence of backend /agenda page requests where a server-side pending-occurrence query or bounded targeted lookup is required.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

