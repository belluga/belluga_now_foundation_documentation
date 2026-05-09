# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-test-quality-dispatch-20260507T1521Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close the RR-AUTH-03 test-quality gate until focused tests are added for persisted bearer-token ceiling denials, wildcard account-scoped issuer fail-close variants, and an explicit stale or foreign ambient account context false-denial fixture. Targeted reruns are sufficient after those additions.`

## Merged Findings
### F-2B192780 [high] Persisted-token negative ceiling behavior is not directly tested
- **Reviewers:** no-context-test-quality-auditor-rr-auth-03
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `auth_persisted_token_ceiling_negative_required`
- **Suggested action:** Add at least one account-route feature test using TenantScopedAccessTokenService to issue a persisted token with only read/view while the live role has create or wildcard, then assert create/update is 403. Prefer covering one account-users route and one push/events account route.
- **Rationale:** The suite proves positive ceiling cases for exact/resource/literal wildcards and proves live-role denial when the token allows create, but it does not issue a persisted account-bound bearer token whose abilities are lower than the live current-account role and then assert denial. The closest push create ability denial uses Sanctum::actingAs at PushMessageFlowTest.php:643-651, which exercises a transient test token rather than the persisted PersonalAccessToken path in AccountUser::tokenCan(). A regression that makes persisted token abilities fail open while live role permissions allow the action could pass the current RR-AUTH-03 tests.

### F-BA2A4F98 [medium] Wildcard account-scoped issuer fail-close variants are not covered
- **Reviewers:** no-context-test-quality-auditor-rr-auth-03
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `auth_account_scoped_issuer_wildcard_matrix_required`
- **Suggested action:** Convert the issuer fail-close test into a data-provider matrix covering account-users:view, account-users:*, events:*, push-messages:*, and literal * under the same unresolved multi-account context.
- **Rationale:** AccountAuthenticationServiceTest.php:148-184 validates fail-closed issuance only for the exact account-users:view ability in an ambiguous multi-account context. The implementation has separate account-scoped detection branches for literal * and resource wildcards in TenantScopedAccessTokenService.php:151-166, but the tests do not assert that *, account-users:*, events:*, or push-messages:* also fail closed when no explicit/current/single-access account binding can be resolved.

### F-E579FBC7 [medium] Stale account-context false-denial coverage is not deterministic
- **Reviewers:** no-context-test-quality-auditor-rr-auth-03
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `auth_stale_account_context_false_denial_fixture_required`
- **Suggested action:** Add a focused regression that deliberately leaves or sets a different Account::current() outside the account middleware context, then exercises a tenant-public endpoint that requires token authorization and asserts the expected success payload rather than only HTTP 200.
- **Rationale:** TenantPublicAccountTokenScopeTest.php:92-112 only asserts 200 for tenant-public agenda/account_profiles with an account token. The setup seeds an account but does not explicitly create a stale or foreign Account::current() outside account middleware, while the production guard depends on the CheckUserAccess context flag in AccountUser.php:189-193. As written, these tests may not fail if the stale account-context regression returns through a route that does not exercise tokenCan or if no stale account context is actually present.

## Reviewer Summaries
### no-context-test-quality-auditor-rr-auth-03
- **Assessment:** Mixed. The tests cover missing account_id, mismatched account_id, positive literal/resource wildcard paths, live current-account permission revalidation, next-request role downgrade denial, ambiguous multi-account issuance fail-close, and push data/actions route binding. The remaining gaps are negative persisted-token ceiling coverage, wildcard/unbound issuer variants, and deterministic stale account-context false-denial coverage.
- **Recommended path:** `Do not close the RR-AUTH-03 test-quality gate until focused tests are added for persisted bearer-token ceiling denials, wildcard account-scoped issuer fail-close variants, and an explicit stale or foreign ambient account context false-denial fixture. Targeted reruns are sufficient after those additions.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQA-RR-AUTH-03-POST-001 Persisted-token negative ceiling behavior is not directly tested: The suite proves positive ceiling cases for exact/resource/literal wildcards and proves live-role denial when the token allows create, but it does not issue a persisted account-bound bearer token whose abilities are lower than the live current-account role and then assert denial. The closest push create ability denial uses Sanctum::actingAs at PushMessageFlowTest.php:643-651, which exercises a transient test token rather than the persisted PersonalAccessToken path in AccountUser::tokenCan(). A regression that makes persisted token abilities fail open while live role permissions allow the action could pass the current RR-AUTH-03 tests.
  - [medium] TQA-RR-AUTH-03-POST-002 Wildcard account-scoped issuer fail-close variants are not covered: AccountAuthenticationServiceTest.php:148-184 validates fail-closed issuance only for the exact account-users:view ability in an ambiguous multi-account context. The implementation has separate account-scoped detection branches for literal * and resource wildcards in TenantScopedAccessTokenService.php:151-166, but the tests do not assert that *, account-users:*, events:*, or push-messages:* also fail closed when no explicit/current/single-access account binding can be resolved.
  - [medium] TQA-RR-AUTH-03-POST-003 Stale account-context false-denial coverage is not deterministic: TenantPublicAccountTokenScopeTest.php:92-112 only asserts 200 for tenant-public agenda/account_profiles with an account token. The setup seeds an account but does not explicitly create a stale or foreign Account::current() outside account middleware, while the production guard depends on the CheckUserAccess context flag in AccountUser.php:189-193. As written, these tests may not fail if the stale account-context regression returns through a route that does not exercise tokenCan or if no stale account context is actually present.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
