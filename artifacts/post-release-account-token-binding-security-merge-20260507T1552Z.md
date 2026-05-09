# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-security-dispatch-20260507T1552Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Do not close RR-AUTH-03 until the public account stamp helper is either made fail-closed/private behind the guarded issuer path or explicitly waived with proof that no production caller can use it to create or preserve an unbound account-scoped bearer token. After that, the package is acceptable for the required audit-floor rerun; legacy combined-batch and full-suite attribution debt should remain tracked as verification debt, not as evidence of an active product breakout in the bounded files reviewed.`

## Merged Findings
### F-81B25E93 [medium] Public stampAccountId remains a fail-open token-binding escape hatch
- **Reviewers:** no-context-security-adversarial-reviewer
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make stampAccountId private to the guarded issueForAccountUser flow, or replace it with a guarded method that requires AccountUser plus ability list and throws when account-scoped abilities cannot be bound. Add a regression proving no public service API can create or leave an account-scoped token without account_id.
- **Rationale:** TenantScopedAccessTokenService::issueForAccountUser now validates resolved account_id against normalized user access before creating account-scoped tokens, which closes the direct issuer bug. However, the same service still exposes public stampAccountId(NewAccessToken, ?string $accountId = null), and that method silently returns when no account id can be resolved. That keeps a separate stamp path capable of preserving an unbound token if a legacy or future caller creates a token and relies on this public helper rather than the guarded issuer. The bounded grep showed current app callers use issueForAccountUser, so this is a structural escape hatch rather than a reproduced current route breakout, but it contradicts the package goal that account-scoped token issue/stamp paths fail closed.

### F-4E2C8F20 [low] Account-scoped ability detection is coupled to a hard-coded resource catalog
- **Reviewers:** no-context-security-adversarial-reviewer
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Formalize a guardrail or test that derives account-scoped ability resources from account-prefixed route middleware declarations, or fails when account routes introduce an ability resource missing from the token-binding catalog.
- **Rationale:** The reviewed account route files use account-users, account-roles, events, and push-messages, and those exact resources are present in TenantScopedAccessTokenService::ACCOUNT_SCOPED_ABILITY_RESOURCES. That is clean for the bounded package today. The residual risk is that adding a future account-prefixed route with a new ability resource would not automatically require account binding at token issuance unless this service constant is updated in lockstep. That would reintroduce the same class of unbound account-workspace bearer token issue for the new resource.

## Reviewer Summaries
### no-context-security-adversarial-reviewer
- **Assessment:** The post-correction implementation materially closes the targeted account-user breakout paths in the bounded package: explicit/current inaccessible account_id issuance is rejected for account-scoped abilities, no-current-account login is fail-closed except exact-one account fallback, push data/actions now require account middleware plus read ability, persisted bearer-token ceilings are wildcard-aware and live role permissions are revalidated inside account middleware, and the bounded account route files reviewed have account middleware before ability checks. One medium structural issue remains in the stamp API surface.
- **Recommended path:** `Do not close RR-AUTH-03 until the public account stamp helper is either made fail-closed/private behind the guarded issuer path or explicitly waived with proof that no production caller can use it to create or preserve an unbound account-scoped bearer token. After that, the package is acceptable for the required audit-floor rerun; legacy combined-batch and full-suite attribution debt should remain tracked as verification debt, not as evidence of an active product breakout in the bounded files reviewed.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] RR-AUTH-03-SEC-POST-001 Public stampAccountId remains a fail-open token-binding escape hatch: TenantScopedAccessTokenService::issueForAccountUser now validates resolved account_id against normalized user access before creating account-scoped tokens, which closes the direct issuer bug. However, the same service still exposes public stampAccountId(NewAccessToken, ?string $accountId = null), and that method silently returns when no account id can be resolved. That keeps a separate stamp path capable of preserving an unbound token if a legacy or future caller creates a token and relies on this public helper rather than the guarded issuer. The bounded grep showed current app callers use issueForAccountUser, so this is a structural escape hatch rather than a reproduced current route breakout, but it contradicts the package goal that account-scoped token issue/stamp paths fail closed.
  - [low] RR-AUTH-03-SEC-POST-002 Account-scoped ability detection is coupled to a hard-coded resource catalog: The reviewed account route files use account-users, account-roles, events, and push-messages, and those exact resources are present in TenantScopedAccessTokenService::ACCOUNT_SCOPED_ABILITY_RESOURCES. That is clean for the bounded package today. The residual risk is that adding a future account-prefixed route with a new ability resource would not automatically require account binding at token issuance unless this service constant is updated in lockstep. That would reintroduce the same class of unbound account-workspace bearer token issue for the new resource.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
