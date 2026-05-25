# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/api-decisions/invite-sent-status-contract-triple-audit-20260523/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Adopt Option C as the target architecture: enrich paginated inviteable rows for composer actionability, introduce a separate exact occurrence summary/preview read model, and retain GET /invites/sent-statuses as a narrow targeted hydration/push reconciliation contract. For current promotion, allow Option A only if the release claim is scoped to the already-defined bugfix behavior and does not claim exact full-occurrence summary semantics.`

## Merged Findings
### F-F5F3DA46 [high] Option A is not acceptable as the final event summary/footer contract
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** If Option A is kept for any summary UI, add tests with more than 200 sent invites proving either exact full-occurrence counters independent of returned items or explicit truncated/non-authoritative UI handling.
- **Rationale:** The response is bounded to 200 items and its summary can be computed from returned rows rather than the whole occurrence. Tests could pass for small fixtures while production occurrences above the cap show incorrect pending/accepted counters.

### F-AD167E8E [high] Changing direction to Option C requires fresh tests/evidence before promotion of that changed behavior
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** For Option C, require backend tests for exact occurrence summary counters/preview over more than 200 invites, inviteables enrichment tests, targeted sent-status hydration tests, and Flutter integration/controller tests proving each surface calls the correct read model.
- **Rationale:** Option C introduces multiple contracts and frontend routing decisions. Without new tests, regressions could hide in contract routing: composer rows, summary preview, and push reconciliation could each pass independently while the integrated UI remains stale or inconsistent.

### F-6023C695 [medium] Option B is not sufficient as the sole target contract
- **Reviewers:** test-quality
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** If B is adopted, add paginated inviteables contract tests and also add a separate summary contract or keep targeted sent-status hydration for push refresh.
- **Rationale:** Composer row enrichment does not cover event summary/preview or targeted push reconciliation.

### F-A1858D56 [medium] Option A is acceptable only when constrained to targeted hydration
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep the release claim narrow and require backend contract tests plus Flutter repository/controller tests for restart hydration, accepted-push update, duplicate push idempotency, push-before-hydration, and button disablement.
- **Rationale:** Option A is acceptable for current tactical promotion when constrained to targeted hydration, visible recipient filters, restart recovery, and invite_accepted push reconciliation, with backend and Flutter tests for those semantics.

## Reviewer Summaries
### test-quality
- **Assessment:** Option C is the best target contract. Current Option A is acceptable only as a tactical promotion step for targeted sent-status hydration, push reconciliation, and visible-recipient actionability. It becomes blocking if promoted as the final source for event summary/footer semantics because its unfiltered 200-item bounded response can produce truncated/non-exact summary behavior.
- **Recommended path:** `Adopt Option C as the target architecture: enrich paginated inviteable rows for composer actionability, introduce a separate exact occurrence summary/preview read model, and retain GET /invites/sent-statuses as a narrow targeted hydration/push reconciliation contract. For current promotion, allow Option A only if the release claim is scoped to the already-defined bugfix behavior and does not claim exact full-occurrence summary semantics.`
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQA-001 Option A is not acceptable as the final event summary/footer contract: The response is bounded to 200 items and its summary can be computed from returned rows rather than the whole occurrence. Tests could pass for small fixtures while production occurrences above the cap show incorrect pending/accepted counters.
  - [medium] TQA-002 Option A is acceptable only when constrained to targeted hydration: Option A is acceptable for current tactical promotion when constrained to targeted hydration, visible recipient filters, restart recovery, and invite_accepted push reconciliation, with backend and Flutter tests for those semantics.
  - [medium] TQA-003 Option B is not sufficient as the sole target contract: Composer row enrichment does not cover event summary/preview or targeted push reconciliation.
  - [high] TQA-004 Changing direction to Option C requires fresh tests/evidence before promotion of that changed behavior: Option C introduces multiple contracts and frontend routing decisions. Without new tests, regressions could hide in contract routing: composer rows, summary preview, and push reconciliation could each pass independently while the integrated UI remains stale or inconsistent.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
