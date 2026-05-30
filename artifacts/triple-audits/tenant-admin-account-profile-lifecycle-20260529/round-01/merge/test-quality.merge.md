# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Block renewed approval until the TODO/package tightens the test plan for force-delete coverage, repair-command regression coverage, and CI-equivalent real-backend validation gates.`

## Merged Findings
### F-61C38C03 [high] delete and forceDelete need explicit last-profile regression coverage.
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require explicit regression tests for both delete() and forceDelete() last-profile rejection, plus aggregate account deletion proving the intended bypass remains valid.
- **Rationale:** The package states both delete and forceDelete lack last-profile guards, but the execution plan only calls out a failing feature test for direct deletion and aggregate account delete.

### F-3B7B4DEA [high] Repair command/service needs fixture-backed tests for every policy branch.
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require backend tests for dry-run versus execute parity, known test-seed aggregate deletion, safe restore, ambiguous skip/report, missing profile type skip/report, and post-run zero-invalid-row assertion.
- **Rationale:** D-04 defines a nuanced repair policy with delete, restore, and skip branches, but the plan only says to add a command and run dry-run/execute locally.

### F-BCD31D67 [high] CI-equivalent real-backend validation gates are too loose.
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace where-feasible wording with required validation gates: exact Laravel targets, exact Playwright mutation specs or documented blocker, cleanup source-scan criteria, and post-validation backend invariant query proving no live account lacks an active profile.
- **Rationale:** The plan says to run focused Laravel tests and targeted Playwright mutation shard where feasible, but does not define exact commands, pass criteria, or blocked handling if Playwright cannot run.

### F-52AC5F86 [medium] Mutation rejection assertions need persisted-state checks.
- **Reviewers:** test-quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Assert response status/error, profile remains active, account remains live, active-profile count is unchanged, and multi-profile deletion still behaves as intended.
- **Rationale:** The planned direct-delete regression test is described only at behavior level and could assert only an error response.

## Reviewer Summaries
### test-quality
- **Assessment:** not_ready
- **Recommended path:** `Block renewed approval until the TODO/package tightens the test plan for force-delete coverage, repair-command regression coverage, and CI-equivalent real-backend validation gates.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQ-01 delete and forceDelete need explicit last-profile regression coverage.: The package states both delete and forceDelete lack last-profile guards, but the execution plan only calls out a failing feature test for direct deletion and aggregate account delete.
  - [high] TQ-02 Repair command/service needs fixture-backed tests for every policy branch.: D-04 defines a nuanced repair policy with delete, restore, and skip branches, but the plan only says to add a command and run dry-run/execute locally.
  - [high] TQ-03 CI-equivalent real-backend validation gates are too loose.: The plan says to run focused Laravel tests and targeted Playwright mutation shard where feasible, but does not define exact commands, pass criteria, or blocked handling if Playwright cannot run.
  - [medium] TQ-04 Mutation rejection assertions need persisted-state checks.: The planned direct-delete regression test is described only at behavior level and could assert only an error response.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
