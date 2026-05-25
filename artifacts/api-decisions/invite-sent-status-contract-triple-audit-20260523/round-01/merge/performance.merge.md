# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/api-decisions/invite-sent-status-contract-triple-audit-20260523/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Promote current Option A as non-blocking tactical behavior for targeted sent-status hydration and push reconciliation. Establish Option C as vNext: paginated inviteables own composer row actionability, an occurrence summary contract owns exact counters and preview, and /invites/sent-statuses remains narrow for targeted recipient refresh.`

## Merged Findings
### F-7152E8CA [high] Option A summary may be truncated when more than 200 sent invites exist
- **Reviewers:** performance
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Define exact counters in the vNext occurrence summary contract and avoid using Option A as the authoritative summary source.
- **Rationale:** The package states unfiltered summary can be truncated and not represent the full occurrence when more than 200 sent invites exist. If current promotion promises exact event-level counters, this becomes a correctness blocker; otherwise it is vNext debt.

### F-DECB21B6 [medium] Event summary can overfetch up to 200 sent-invite rows
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move event detail/footer to an occurrence summary/preview contract in vNext.
- **Rationale:** Option A returns summary plus up to 200 items and event detail can call occurrence-level status hydration without a recipient filter. Summary/footer UI may load materially more row data than needed for counters plus a small avatar preview, but the package does not establish an unbounded runtime risk.

### F-607DE8F8 [medium] Composer row actionability is better owned by paginated inviteables
- **Reviewers:** performance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Adopt Option C vNext and enrich paginated inviteables with occurrence-scoped row actionability.
- **Rationale:** /contacts/inviteables is the canonical composer source and Option B/C would return sent_invite_status with each paginated inviteable row. The current two-read composer flow is acceptable when hydration is filtered, but is not the cleanest target contract.

## Reviewer Summaries
### performance
- **Assessment:** Option C is the best target contract. Current Option A is acceptable for current promotion as a bounded tactical contract, provided it is not treated as the final event-summary read model.
- **Recommended path:** `Promote current Option A as non-blocking tactical behavior for targeted sent-status hydration and push reconciliation. Establish Option C as vNext: paginated inviteables own composer row actionability, an occurrence summary contract owns exact counters and preview, and /invites/sent-statuses remains narrow for targeted recipient refresh.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] PACED-PERF-001 Event summary can overfetch up to 200 sent-invite rows: Option A returns summary plus up to 200 items and event detail can call occurrence-level status hydration without a recipient filter. Summary/footer UI may load materially more row data than needed for counters plus a small avatar preview, but the package does not establish an unbounded runtime risk.
  - [high] PACED-CORRECTNESS-001 Option A summary may be truncated when more than 200 sent invites exist: The package states unfiltered summary can be truncated and not represent the full occurrence when more than 200 sent invites exist. If current promotion promises exact event-level counters, this becomes a correctness blocker; otherwise it is vNext debt.
  - [medium] PACED-API-001 Composer row actionability is better owned by paginated inviteables: /contacts/inviteables is the canonical composer source and Option B/C would return sent_invite_status with each paginated inviteable row. The current two-read composer flow is acceptable when hydration is filtered, but is not the cleanest target contract.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
