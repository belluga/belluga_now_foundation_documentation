# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T030556Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Fix the cooldown semantics and user-scope alignment before another audit round, then decide whether the recovery-contract documentation gap should remain as non-blocking debt.`

## Merged Findings
### F-4F783705 [high] Reset-issue cooldown is consumed before issuance success is known
- **Reviewers:** RR-AUTH-04-triple-audit-performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `reset-issue-cooldown-rollback-on-failure`
- **Suggested action:** Ensure cooldown acquisition is scoped to successful issuance or explicitly released on issuance failure.
- **Rationale:** At review time the reset issue path acquired the cooldown before token issuance had definitively succeeded and did not reliably roll it back if issuance failed. That can create a false suppression window and force unnecessary retries under error conditions.

### F-DA6BE5B7 [medium] Cooldown suppression is email-scoped while token invalidation is user-scoped
- **Reviewers:** RR-AUTH-04-triple-audit-performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `user-scoped-reset-cooldown`
- **Suggested action:** Align the reset issuance cooldown with the same user-scoped identity used for token invalidation.
- **Rationale:** The reset lifecycle invalidates older tokens per user, but the cooldown at review time followed the email subject only. That leaves alias-rotation space where one user with multiple email subjects can bypass the intended reset issuance ceiling.

### F-B7E61840 [low] Reissue-required reset recovery contract is not promoted into canonical docs
- **Reviewers:** RR-AUTH-04-triple-audit-performance
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `reset-reissue-recovery-contract-promotion`
- **Suggested action:** Promote the reissue-required recovery contract into the TODO/package/module authority surfaces or accept the documentation gap as low debt.
- **Rationale:** The slice already behaves as burn-before-mutate with fresh reissue required after post-consume mutation failure, but that recovery contract is not yet promoted clearly enough into the canonical authority surfaces.

## Reviewer Summaries
### RR-AUTH-04-triple-audit-performance
- **Assessment:** Round 01 is not performance-clean. Reset-issue cooldowns are consumed before the system knows issuance succeeded, the cooldown is email-scoped while reset invalidation is user-scoped, and the reissue-required recovery contract is not yet promoted into canonical docs.
- **Recommended path:** `Fix the cooldown semantics and user-scope alignment before another audit round, then decide whether the recovery-contract documentation gap should remain as non-blocking debt.`
- **Performance:** `regresses`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-01 Reset-issue cooldown is consumed before issuance success is known: At review time the reset issue path acquired the cooldown before token issuance had definitively succeeded and did not reliably roll it back if issuance failed. That can create a false suppression window and force unnecessary retries under error conditions.
  - [medium] PERF-02 Cooldown suppression is email-scoped while token invalidation is user-scoped: The reset lifecycle invalidates older tokens per user, but the cooldown at review time followed the email subject only. That leaves alias-rotation space where one user with multiple email subjects can bypass the intended reset issuance ceiling.
  - [low] PERF-03 Reissue-required reset recovery contract is not promoted into canonical docs: The slice already behaves as burn-before-mutate with fresh reissue required after post-consume mutation failure, but that recovery contract is not yet promoted clearly enough into the canonical authority surfaces.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

