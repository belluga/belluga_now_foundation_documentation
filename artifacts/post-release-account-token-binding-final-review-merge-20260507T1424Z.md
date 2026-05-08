# PACED Subagent Review Merge: final_review

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-final-review-dispatch-20260507T1424Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not proceed as closure-clean to triple audit/Claude comparison until the token ceiling check and tests are corrected. Implement wildcard-aware token-ceiling evaluation or expand account role wildcards at token issuance, then rerun targeted bearer-token regressions and the relevant validation lanes.`

## Merged Findings
### F-30A386C4 [high] Resource wildcard role abilities can be rejected before live account-role revalidation
- **Reviewers:** codex-no-context-final-review
- **Category:** `adherence`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make the token-ceiling step use the same account ability wildcard semantics as live role permissions, or expand role wildcards into exact account-scoped catalog abilities before token storage. Add bearer-token regression coverage through real issue/login paths for account-users:*, push-messages:*, events:*, and * roles, including same-account allow and wrong-account reject cases.
- **Rationale:** AccountAuthenticationService issues account-user tokens from role permissions without expanding resource wildcards such as account-users:* or push-messages:*. AccountUser::tokenCan then returns false immediately when the stored Sanctum token does not exactly can() the requested route ability, so AccountUserAccessService::tokenAllows never gets to apply its live current-account wildcard logic. The changed tests mostly issue exact abilities or use Sanctum::actingAs with exact abilities, while production-style roles in the referenced push/account fixtures use resource wildcards. This contradicts the documented ceiling-over-live-permissions model and can block valid account-bound bearer tokens on account-scoped routes.

## Reviewer Summaries
### codex-no-context-final-review
- **Assessment:** Blocked for closure. The bounded package establishes account_id route binding and live current-account revalidation, but the bearer-token ability ceiling still has a wildcard semantic gap that can deny real account users whose roles use resource wildcards such as push-messages:* or account-users:*.
- **Recommended path:** `Do not proceed as closure-clean to triple audit/Claude comparison until the token ceiling check and tests are corrected. Implement wildcard-aware token-ceiling evaluation or expand account role wildcards at token issuance, then rerun targeted bearer-token regressions and the relevant validation lanes.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] FR-001 Resource wildcard role abilities can be rejected before live account-role revalidation: AccountAuthenticationService issues account-user tokens from role permissions without expanding resource wildcards such as account-users:* or push-messages:*. AccountUser::tokenCan then returns false immediately when the stored Sanctum token does not exactly can() the requested route ability, so AccountUserAccessService::tokenAllows never gets to apply its live current-account wildcard logic. The changed tests mostly issue exact abilities or use Sanctum::actingAs with exact abilities, while production-style roles in the referenced push/account fixtures use resource wildcards. This contradicts the documented ceiling-over-live-permissions model and can block valid account-bound bearer tokens on account-scoped routes.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

