# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-security-dispatch-20260507T1403Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not approve final closure until account-prefixed route coverage is made deterministic. Add a route inventory/guard test proving every authenticated account-prefixed route either runs the account middleware before ability checks or is explicitly documented and tested as a public/account-initialization exception. Then resolve the multi-account login/account-selection contract so account-bound tokens are issued for the user-selected account rather than an arbitrary fallback.`

## Merged Findings
### F-74B1EA3B [high] Account-prefixed push data/action routes bypass the new account token binding boundary
- **Reviewers:** rr-auth-03-security
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account_prefixed_authenticated_routes_must_use_account_auth_boundary_or_documented_exception`
- **Suggested action:** Apply account middleware plus the appropriate ability middleware to those routes, or add an explicit documented exception with focused tests proving no account data/action authorization can be abused with a missing or mismatched token account_id. Add a route inventory test that fails when authenticated account-prefixed routes bypass the account boundary.
- **Rationale:** The hardening depends on CheckUserAccess to assert token account_id equality and to set ACCOUNT_SCOPED_AUTH_CONTEXT_KEY before Sanctum ability middleware invokes AccountUser::tokenCan(). In routes/api/packages/project_account_api_v1/push_handler.php, GET /push/messages/{push_message_id}/data and POST /push/messages/{push_message_id}/actions are inside auth:sanctum and the account URL prefix, but they use only InitializeAccount rather than the account middleware. That means a bearer token can reach account-context controllers without the new missing/mismatched account_id rejection, and AccountUser::tokenCan() would also skip live account-role revalidation because the context flag is never set. If these endpoints are intentionally non-account-authorized, that exception is not captured by the RR-AUTH-03 contract or tests.

### F-9A716092 [medium] Verification does not yet prove complete account-route enforcement or membership revocation
- **Reviewers:** rr-auth-03-security
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account_auth_route_inventory_and_revocation_regression_required`
- **Suggested action:** Replace or supplement the blocked combined batch with deterministic inventory coverage for every account-prefixed authenticated route and add at least one post-issuance membership/role-removal regression that proves next-request denial through the live revalidation path.
- **Rationale:** The targeted tests cover core account-user routes and an event boundary, but the package itself records a blocked legacy combined account API auth/middleware batch. Given the route-level bypass found in the push handler package, the blocked batch cannot be treated as only fixture debt without a narrower deterministic substitute. The current tests also cover reduced live permissions versus token abilities, but they do not clearly prove that removing account membership or role access after token issuance revokes access on the next request across the account route inventory.

### F-70FBF6B9 [medium] Password login binds tokens to an arbitrary first account when no current account exists
- **Reviewers:** rr-auth-03-security
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account_token_issuance_requires_explicit_selected_account_for_multi_account_users`
- **Suggested action:** Require an explicit account context or account-selection parameter before issuing an account-bound token for multi-account users, or document the fallback as tenant-login bootstrap behavior and provide a tested account-switch/token-refresh contract.
- **Rationale:** AccountAuthenticationService::login falls back to Account::query()->whereIn('_id', $accessIds)->first() when Account::current() is absent, then stamps that account_id into the token. For a multi-account user this is not tied to an explicit selected account, so the issued token may be valid for Account A while the client intends Account B. The new mismatch rejection makes this fail closed, which is secure, but it creates account-selection ambiguity and likely false denials unless there is a separate documented account-switch/token-issue path. It also weakens the claim that account-user tokens are bound to the selected account context at issue time.

## Reviewer Summaries
### rr-auth-03-security
- **Assessment:** The bounded implementation materially improves RR-AUTH-03 by stamping account_id on account-user tokens, rejecting mismatched account-scoped bearer tokens in CheckUserAccess, and making Sanctum abilities a ceiling when the account middleware context is active. I would not close the security TODO yet: at least one account-prefixed route package bypasses the new CheckUserAccess boundary, and token issuance without an active account still binds to an arbitrary first accessible account, creating operational false-denial and account-selection ambiguity for multi-account users.
- **Recommended path:** `Do not approve final closure until account-prefixed route coverage is made deterministic. Add a route inventory/guard test proving every authenticated account-prefixed route either runs the account middleware before ability checks or is explicitly documented and tested as a public/account-initialization exception. Then resolve the multi-account login/account-selection contract so account-bound tokens are issued for the user-selected account rather than an arbitrary fallback.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] RR-AUTH-03-SEC-001 Account-prefixed push data/action routes bypass the new account token binding boundary: The hardening depends on CheckUserAccess to assert token account_id equality and to set ACCOUNT_SCOPED_AUTH_CONTEXT_KEY before Sanctum ability middleware invokes AccountUser::tokenCan(). In routes/api/packages/project_account_api_v1/push_handler.php, GET /push/messages/{push_message_id}/data and POST /push/messages/{push_message_id}/actions are inside auth:sanctum and the account URL prefix, but they use only InitializeAccount rather than the account middleware. That means a bearer token can reach account-context controllers without the new missing/mismatched account_id rejection, and AccountUser::tokenCan() would also skip live account-role revalidation because the context flag is never set. If these endpoints are intentionally non-account-authorized, that exception is not captured by the RR-AUTH-03 contract or tests.
  - [medium] RR-AUTH-03-SEC-002 Password login binds tokens to an arbitrary first account when no current account exists: AccountAuthenticationService::login falls back to Account::query()->whereIn('_id', $accessIds)->first() when Account::current() is absent, then stamps that account_id into the token. For a multi-account user this is not tied to an explicit selected account, so the issued token may be valid for Account A while the client intends Account B. The new mismatch rejection makes this fail closed, which is secure, but it creates account-selection ambiguity and likely false denials unless there is a separate documented account-switch/token-issue path. It also weakens the claim that account-user tokens are bound to the selected account context at issue time.
  - [medium] RR-AUTH-03-SEC-003 Verification does not yet prove complete account-route enforcement or membership revocation: The targeted tests cover core account-user routes and an event boundary, but the package itself records a blocked legacy combined account API auth/middleware batch. Given the route-level bypass found in the push handler package, the blocked batch cannot be treated as only fixture debt without a narrower deterministic substitute. The current tests also cover reduced live permissions versus token abilities, but they do not clearly prove that removing account membership or role access after token issuance revokes access on the next request across the account route inventory.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

