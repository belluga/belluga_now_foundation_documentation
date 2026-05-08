# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-critique-dispatch-20260507T1424Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Do not close RR-AUTH-03 until account-user token issuance normalizes wildcard and resource-wildcard permissions into the exact account-workspace ability catalog, and add bearer-token login regressions for wildcard roles against account, events, and push routes. Treat the implicit no-current-account login binding as non-blocking but required follow-up unless an explicit selected-account issuance contract already exists outside this bounded package.`

## Merged Findings
### F-B72307C8 [high] Login-issued account tokens do not expand wildcard role permissions before enforcing the Sanctum ceiling
- **Reviewers:** codex-no-context-paced-critique
- **Category:** `adherence`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `project.account-token-ability-normalization`
- **Suggested action:** Normalize account-token abilities at issue time by expanding both literal '*' and '<resource>:*' permissions into exact AbilityCatalog entries used by account-scoped routes, including events and push message abilities. Add bearer-token login regressions for account-users:*, events:*, push-messages:*, and literal * roles hitting exact account route middleware after login.
- **Rationale:** AccountAuthenticationService issues account-user tokens from live role permissions, but sanitizeAbilities only handles the literal '*' case and otherwise returns permissions such as 'account-users:*', 'events:*', or 'push-messages:*' unchanged. AccountUser::tokenCan() then checks the Sanctum token ceiling before live role revalidation, and Sanctum PersonalAccessToken::can() only accepts literal '*' or an exact ability match. The referenced account routes require exact abilities such as 'account-users:create', 'events:read', and 'push-messages:read'. This means a password-login bearer token for a common wildcard role template can be rejected before AccountUserAccessService::tokenAllows() has a chance to honor the live wildcard permission. The literal '*' branch also filters AbilityCatalog::all() to 'account-' abilities only, excluding referenced account-workspace events and push abilities. Existing evidence does not cover this because the login unit test only asserts token issuance and account_id, while route tests mostly use manually supplied exact abilities or Sanctum::actingAs transient tokens.

### F-91CFFD5A [medium] Account login silently binds tokens to an implicit account when no selected account context exists
- **Reviewers:** codex-no-context-paced-critique
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `project.account-token-selected-context-required`
- **Suggested action:** Either require an explicit selected account context for account-user token issuance or document and test the fallback as an intentional contract. Add a multi-account login regression proving the issued token account_id matches the selected account context and not a first-access or stale Account::current() value.
- **Rationale:** AccountAuthenticationService::login uses Account::current() when present, but if it is absent it selects the first account from the user's access ids and stamps that account_id into the token. The bounded client contract says Flutter/account-workspace consumers must treat bearer tokens as scoped to the selected account context, but the referenced package claims no Flutter request/response schema change and the tests only cover the current-account case. For multi-account users, this can issue a token scoped to an arbitrary first account rather than the account the user intended to enter; that should fail closed on mismatched account routes, but it is still a stale-context and operational-fit regression risk.

## Reviewer Summaries
### codex-no-context-paced-critique
- **Assessment:** The referenced account-prefixed route files now consistently apply the account middleware, including the push data and actions routes. The remaining concern is in the token ceiling path: login-issued bearer tokens can fail legitimate account-workspace authorization for wildcard role templates because token abilities are not normalized to the exact abilities required by Sanctum route middleware before AccountUser::tokenCan() enforces the ceiling.
- **Recommended path:** `Do not close RR-AUTH-03 until account-user token issuance normalizes wildcard and resource-wildcard permissions into the exact account-workspace ability catalog, and add bearer-token login regressions for wildcard roles against account, events, and push routes. Treat the implicit no-current-account login binding as non-blocking but required follow-up unless an explicit selected-account issuance contract already exists outside this bounded package.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] CRIT-001 Login-issued account tokens do not expand wildcard role permissions before enforcing the Sanctum ceiling: AccountAuthenticationService issues account-user tokens from live role permissions, but sanitizeAbilities only handles the literal '*' case and otherwise returns permissions such as 'account-users:*', 'events:*', or 'push-messages:*' unchanged. AccountUser::tokenCan() then checks the Sanctum token ceiling before live role revalidation, and Sanctum PersonalAccessToken::can() only accepts literal '*' or an exact ability match. The referenced account routes require exact abilities such as 'account-users:create', 'events:read', and 'push-messages:read'. This means a password-login bearer token for a common wildcard role template can be rejected before AccountUserAccessService::tokenAllows() has a chance to honor the live wildcard permission. The literal '*' branch also filters AbilityCatalog::all() to 'account-' abilities only, excluding referenced account-workspace events and push abilities. Existing evidence does not cover this because the login unit test only asserts token issuance and account_id, while route tests mostly use manually supplied exact abilities or Sanctum::actingAs transient tokens.
  - [medium] CRIT-002 Account login silently binds tokens to an implicit account when no selected account context exists: AccountAuthenticationService::login uses Account::current() when present, but if it is absent it selects the first account from the user's access ids and stamps that account_id into the token. The bounded client contract says Flutter/account-workspace consumers must treat bearer tokens as scoped to the selected account context, but the referenced package claims no Flutter request/response schema change and the tests only cover the current-account case. For multi-account users, this can issue a token scoped to an arbitrary first account rather than the account the user intended to enter; that should fail closed on mismatched account routes, but it is still a stale-context and operational-fit regression risk.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

