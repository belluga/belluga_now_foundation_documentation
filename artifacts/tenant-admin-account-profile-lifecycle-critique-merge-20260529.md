# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-dispatch-20260529.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Revise the TODO before renewed APROVADO to make the invariant atomic, make repair safety predicates explicit, and define aggregate deletion bypass as a narrow lifecycle boundary.`

## Merged Findings
### F-490BC73E [high] Repair execute mode needs enumerated safe-delete, safe-restore, and skip predicates.
- **Reviewers:** pre-approval-critique
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Define exact execute-mode predicates before approval: tenant match, ownership/source evidence, allowed test prefixes, linked-data handling, profile type existence, single-vs-multiple soft-deleted profile behavior, and residual reporting format.
- **Rationale:** D-04 recommends deleting known test-seed aggregates, restoring safe non-test rows, and skipping ambiguous rows, but the approval decision does not define mandatory safety predicates.

### F-DCAD5063 [high] Last-profile deletion prevention must be atomic under concurrent deletes.
- **Reviewers:** pre-approval-critique
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add an explicit atomicity requirement for the guard, such as locking the account/profile set or an equivalent transactional invariant, and require a concurrency regression test covering simultaneous profile deletion.
- **Rationale:** The package requires service-level guards for delete and forceDelete but does not require transaction or locking semantics. Two direct profile deletes can both observe another active profile and then both delete, leaving a live account with zero active profiles.

### F-CB661DED [medium] Invariant checks and repair scans must be bounded by indexed predicates.
- **Reviewers:** pre-approval-critique
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require bounded indexed lookups for account_id/deleted_at-style predicates and include query/log evidence from focused tests or dry-run repair.
- **Rationale:** The plan does not state query-shape or index expectations for the guard or repair enumeration.

### F-6AEFF9E0 [medium] Aggregate account deletion bypass must be a named lifecycle boundary.
- **Reviewers:** pre-approval-critique
- **Category:** `adherence`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** State that profile deletion may bypass the last-profile guard only inside the account aggregate deletion transaction/service path, and require a regression test proving direct callers cannot use that bypass.
- **Rationale:** The plan preserves aggregate account deletion and may need a bypass context, but an implicit or broadly callable bypass could reintroduce direct profile deletion as an invariant escape hatch.

## Reviewer Summaries
### pre-approval-critique
- **Assessment:** not_ready
- **Recommended path:** `Revise the TODO before renewed APROVADO to make the invariant atomic, make repair safety predicates explicit, and define aggregate deletion bypass as a narrow lifecycle boundary.`
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] CRIT-01 Last-profile deletion prevention must be atomic under concurrent deletes.: The package requires service-level guards for delete and forceDelete but does not require transaction or locking semantics. Two direct profile deletes can both observe another active profile and then both delete, leaving a live account with zero active profiles.
  - [high] CRIT-02 Repair execute mode needs enumerated safe-delete, safe-restore, and skip predicates.: D-04 recommends deleting known test-seed aggregates, restoring safe non-test rows, and skipping ambiguous rows, but the approval decision does not define mandatory safety predicates.
  - [medium] CRIT-03 Aggregate account deletion bypass must be a named lifecycle boundary.: The plan preserves aggregate account deletion and may need a bypass context, but an implicit or broadly callable bypass could reintroduce direct profile deletion as an invariant escape hatch.
  - [medium] CRIT-04 Invariant checks and repair scans must be bounded by indexed predicates.: The plan does not state query-shape or index expectations for the guard or repair enumeration.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
