# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/invite-sent-status-hydration-accepted-push-preapproval-package-20260523-triple-audit-20260523T135612Z/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Revise the pre-approval TODO test contract before APROVADO to add explicit foreground/background/tap invite_accepted device assertions, production-like recipient identity matching fixtures, and declined/superseded status behavior assertions.`

## Merged Findings
### F-2078A9DC [high] invite_accepted push tests do not explicitly cover Android app-state and tap-routing boundaries
- **Reviewers:** test-quality-lane-auditor
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add explicit test/evidence targets for invite_accepted in foreground, background/resume, and notification tap/cold-start paths, each asserting invite-specific visible UX, state refresh for the occurrence, and no generic Push Handler screen fallback.
- **Rationale:** The package requires Android foreground/background notification path evidence and says generic Push Handler fallback is not acceptable, but the fail-first list only names a foreground invite-specific visibility test and an applyInvitePushPayload repository update. The runtime/device target says to verify the inviter device receives/reacts, but it does not require separate foreground, background, terminated/cold-start, and notification-tap assertions. A regression that restores a generic Push Handler route for background/tap, or only updates state without visible invite-specific presentation, could still pass the named tests.

### F-EF8D66A0 [high] Restart hydration tests can pass with simplified IDs while missing the profile-id versus user-id production risk
- **Reviewers:** test-quality-lane-auditor
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a fail-first Flutter hydration/controller test using production-like distinct account user id and profile id values, proving pending and accepted canonical recipients map to the displayed inviteable recipients and disable duplicate invites after restart.
- **Rationale:** The package correctly identifies identity matching as a known risk, including account profile id versus account user id, but the fail-first targets only require payload identity fields and duplicate-invite disablement after restart. If fixtures use the same identifier on both sides, tests can pass while production still fails to match canonical backend recipients to InviteableRecipient entries, leaving buttons enabled after restart or failing to update accepted status.

### F-A9B46087 [medium] Declared declined and superseded status semantics lack fail-first behavior assertions
- **Reviewers:** test-quality-lane-auditor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add backend payload and Flutter behavior tests for declined and hidden terminal/superseded statuses, including whether each status should appear in summaries and whether it should disable or re-enable inviting that recipient.
- **Rationale:** The planned contract says the sent-invite read model must distinguish pending, accepted, declined, and intentionally hidden terminal/superseded statuses, and the risk list says status semantics must not re-enable invite buttons incorrectly. The concrete fail-first targets only name pending and accepted behavior. The main accepted regression is covered, but the wider declared contract could still ship with declined or superseded statuses mapped incorrectly or treated as inviteable without any named test failing.

## Reviewer Summaries
### test-quality-lane-auditor
- **Assessment:** Mixed. The planned tests are directionally strong for the main local-only hydration regression and profile metric semantics, but the package still leaves production-like push/device-state behavior and identity/status edge cases under-specified enough that important regressions could pass.
- **Recommended path:** `Revise the pre-approval TODO test contract before APROVADO to add explicit foreground/background/tap invite_accepted device assertions, production-like recipient identity matching fixtures, and declined/superseded status behavior assertions.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQ-01 invite_accepted push tests do not explicitly cover Android app-state and tap-routing boundaries: The package requires Android foreground/background notification path evidence and says generic Push Handler fallback is not acceptable, but the fail-first list only names a foreground invite-specific visibility test and an applyInvitePushPayload repository update. The runtime/device target says to verify the inviter device receives/reacts, but it does not require separate foreground, background, terminated/cold-start, and notification-tap assertions. A regression that restores a generic Push Handler route for background/tap, or only updates state without visible invite-specific presentation, could still pass the named tests.
  - [high] TQ-02 Restart hydration tests can pass with simplified IDs while missing the profile-id versus user-id production risk: The package correctly identifies identity matching as a known risk, including account profile id versus account user id, but the fail-first targets only require payload identity fields and duplicate-invite disablement after restart. If fixtures use the same identifier on both sides, tests can pass while production still fails to match canonical backend recipients to InviteableRecipient entries, leaving buttons enabled after restart or failing to update accepted status.
  - [medium] TQ-03 Declared declined and superseded status semantics lack fail-first behavior assertions: The planned contract says the sent-invite read model must distinguish pending, accepted, declined, and intentionally hidden terminal/superseded statuses, and the risk list says status semantics must not re-enable invite buttons incorrectly. The concrete fail-first targets only name pending and accepted behavior. The main accepted regression is covered, but the wider declared contract could still ship with declined or superseded statuses mapped incorrectly or treated as inviteable without any named test failing.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
