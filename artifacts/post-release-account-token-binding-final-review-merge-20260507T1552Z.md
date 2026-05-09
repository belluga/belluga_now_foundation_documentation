# PACED Subagent Review Merge: final_review

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-final-review-dispatch-20260507T1552Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not proceed to triple audit or Claude comparison yet. Resolve or approval-waive the open verification debts, and add objective issuer-discipline evidence or a guard before treating RR-AUTH-03 as audit-floor clean.`

## Merged Findings
### F-CB7685DD [high] Legacy combined auth/middleware verification debt remains open
- **Reviewers:** codex-independent-final-review
- **Category:** `residual_risk`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `RR-AUTH-03-VDA-002`
- **Suggested action:** Repair and run the legacy batch, execute a documented narrower equivalent that covers the account auth/middleware boundary, or record an approval-authority waiver before proceeding.
- **Rationale:** The dispatch package and TODO still record the legacy combined account API auth/middleware batch as blocked, with no narrower equivalent execution and no approval-authority waiver. Because this slice hardens the account middleware and token authorization boundary, leaving that lane unresolved is a closure blocker under the package's own gate language.

### F-460A0F55 [medium] Issuer fail-close is service-local but not structurally enforced
- **Reviewers:** codex-independent-final-review
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-token-issuer-service-only`
- **Suggested action:** Add objective evidence or an architecture guard that account-scoped account-user token issuance uses TenantScopedAccessTokenService outside tests, or move the fail-closed check behind a model-level issuance wrapper that direct callers cannot bypass silently.
- **Rationale:** TenantScopedAccessTokenService now fails closed for account-scoped abilities, but the bounded tests still demonstrate that AccountUser::createToken can mint an unbound bearer token with account-scoped abilities. Route middleware rejects that token on account routes, but the replacement rule also claims unbound reusable account-workspace bearer tokens are not valid output. The package does not provide a static guard or proof that production issuance cannot bypass the service.

### F-A2523EF5 [medium] Full-suite evidence is not attributable to a clean bounded RR-AUTH-03 state
- **Reviewers:** codex-independent-final-review
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `RR-AUTH-03-VDA-005`
- **Suggested action:** Rerun the full suite on a clean bounded baseline, explicitly accept the integrated dirty-tree baseline, or record a waiver before final closure.
- **Rationale:** The recorded Laravel CI-equivalent suite passed, but the package explicitly says it included unrelated RR-AUTH-01 dirty-tree changes. That validates an integrated local state, not a clean bounded RR-AUTH-03 baseline, and the TODO still carries this as open verification debt.

## Reviewer Summaries
### codex-independent-final-review
- **Assessment:** Post-correction implementation is directionally aligned with account-bound bearer token semantics, and I did not find a direct performance regression in the bounded code. The package is not closure-clean because required verification debt remains open and issuer fail-close is only proven for the service path, not structurally guarded against direct unbound token creation.
- **Recommended path:** `Do not proceed to triple audit or Claude comparison yet. Resolve or approval-waive the open verification debts, and add objective issuer-discipline evidence or a guard before treating RR-AUTH-03 as audit-floor clean.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] FR-RR-AUTH-03-POST-001 Legacy combined auth/middleware verification debt remains open: The dispatch package and TODO still record the legacy combined account API auth/middleware batch as blocked, with no narrower equivalent execution and no approval-authority waiver. Because this slice hardens the account middleware and token authorization boundary, leaving that lane unresolved is a closure blocker under the package's own gate language.
  - [medium] FR-RR-AUTH-03-POST-002 Full-suite evidence is not attributable to a clean bounded RR-AUTH-03 state: The recorded Laravel CI-equivalent suite passed, but the package explicitly says it included unrelated RR-AUTH-01 dirty-tree changes. That validates an integrated local state, not a clean bounded RR-AUTH-03 baseline, and the TODO still carries this as open verification debt.
  - [medium] FR-RR-AUTH-03-POST-003 Issuer fail-close is service-local but not structurally enforced: TenantScopedAccessTokenService now fails closed for account-scoped abilities, but the bounded tests still demonstrate that AccountUser::createToken can mint an unbound bearer token with account-scoped abilities. Route middleware rejects that token on account routes, but the replacement rule also claims unbound reusable account-workspace bearer tokens are not valid output. The package does not provide a static guard or proof that production issuance cannot bypass the service.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
