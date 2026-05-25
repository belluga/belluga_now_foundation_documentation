# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/api-decisions/invite-sent-status-contract-triple-audit-20260523/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Promote the current Option A implementation if the promotion scope is the already-green tactical fix for visible-recipient hydration and invite_accepted push refresh. Record Option C as the vNext target: paginated /contacts/inviteables owns composer row actionability, an occurrence summary/preview contract owns exact counters and limited preview, and /invites/sent-statuses remains narrow for targeted refresh/reconciliation.`

## Merged Findings
### F-C88DBBB5 [high] Unfiltered Option A summary can be truncated
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** For current promotion, either avoid presenting unfiltered Option A summary as exact or make truncation semantics explicit. For vNext, deliver the Option C occurrence summary/preview contract with exact aggregate counters.
- **Rationale:** The package states unfiltered responses return up to 200 items and the summary is computed from returned rows. If event detail/footer presents those counters as exact whole-occurrence counts, users can see incorrect summary state once sent invites exceed the cap.

### F-7E798199 [medium] Option A mixes distinct read-model responsibilities
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Adopt Option C as the target contract and document /invites/sent-statuses as narrow targeted hydration/reconciliation, not as the canonical broad summary source.
- **Rationale:** The package states /invites/sent-statuses serves composer actionability, event summary/footer, and invite_accepted push refresh. This creates semantic drift risk because row actionability, targeted reconciliation, and occurrence summary have different volume, aggregation, and frontend-consumer needs.

### F-35462DE6 [medium] Event summary/footer overfetches rows under Option A
- **Reviewers:** elegance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move summary/footer to a dedicated aggregate/preview read model under Option C.
- **Rationale:** The event detail/footer needs counters plus small avatar preview, while Option A can return up to 200 full sent-invite rows. This is inefficient but not an unbounded runtime blocker.

## Reviewer Summaries
### elegance
- **Assessment:** Option C is the best target contract. Current Option A is acceptable for current promotion only as a tactical, targeted sent-status hydration/read-reconciliation contract, especially when called with recipient_account_profile_ids[]. It should not be canonized as the long-term event summary/footer source.
- **Recommended path:** `Promote the current Option A implementation if the promotion scope is the already-green tactical fix for visible-recipient hydration and invite_accepted push refresh. Record Option C as the vNext target: paginated /contacts/inviteables owns composer row actionability, an occurrence summary/preview contract owns exact counters and limited preview, and /invites/sent-statuses remains narrow for targeted refresh/reconciliation.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] ELEGANCE-001 Option A mixes distinct read-model responsibilities: The package states /invites/sent-statuses serves composer actionability, event summary/footer, and invite_accepted push refresh. This creates semantic drift risk because row actionability, targeted reconciliation, and occurrence summary have different volume, aggregation, and frontend-consumer needs.
  - [high] CORRECTNESS-001 Unfiltered Option A summary can be truncated: The package states unfiltered responses return up to 200 items and the summary is computed from returned rows. If event detail/footer presents those counters as exact whole-occurrence counts, users can see incorrect summary state once sent invites exceed the cap.
  - [medium] PERFORMANCE-001 Event summary/footer overfetches rows under Option A: The event detail/footer needs counters plus small avatar preview, while Option A can return up to 200 full sent-invite rows. This is inefficient but not an unbounded runtime blocker.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
