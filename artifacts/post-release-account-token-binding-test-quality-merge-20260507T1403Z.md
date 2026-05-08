# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-test-quality-dispatch-20260507T1403Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Accept the current targeted tests as useful local implementation evidence, but do not treat the test-quality floor as fully closed until the blocked combined account API auth/middleware batch is either repaired, replaced by a deterministic route-matrix equivalent across account_api_v1.php and project_account_api_v1.php, or explicitly waived by the approval authority.`

## Merged Findings
### F-C264640B [medium] Blocked combined account route/middleware batch leaves route-matrix coverage incomplete
- **Reviewers:** rr-auth-03-test-quality-floor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-scoped-route-matrix-token-binding-coverage`
- **Suggested action:** Repair the blocked combined batch or add a deterministic route-matrix regression that samples each account-scoped route family with valid, missing, and mismatched account_id bearer token states. If the harness is genuinely non-product debt, record an approval-authority waiver with the narrower equivalent coverage accepted.
- **Rationale:** The changed feature test proves the core account token-binding semantics on representative account user routes, and the event candidate regression proves one project account route boundary. However, the referenced route files contain broader account-scoped surfaces under the same account middleware model, including account users, credentials, role templates, and events. The dispatch and TODO both record the legacy combined account API auth/middleware batch as blocked, so the audit cannot confirm every account-scoped route rejects missing or mismatched account_id before ability authorization.

### F-BADA63BB [low] Token issuance coverage asserts current-account stamping but not fallback account selection semantics
- **Reviewers:** rr-auth-03-test-quality-floor
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-login-token-stamping-context-coverage`
- **Suggested action:** Add a focused unit or feature test for login with no current account and multiple account memberships, asserting the selected/stamped account_id and effective abilities are deterministic and documented.
- **Rationale:** AccountAuthenticationServiceTest verifies that login stamps account_id when Account::current() is set. The implementation also contains behavior for the no-current-account path, selecting the first accessible account before issuing the token. That fallback affects token binding for multi-account users but is not directly asserted in the inspected changed unit tests.

### F-DF03E2CD [low] Role or membership revocation after token issue is implied but not directly proven
- **Reviewers:** rr-auth-03-test-quality-floor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-token-next-request-revocation-coverage`
- **Suggested action:** Add a regression that issues a valid account-bound token, then removes or downgrades the current account role, and confirms the next account-scoped request fails without issuing a new token.
- **Rationale:** The stale-ability test demonstrates that a token carrying account-users:create cannot authorize create when the live current-account role only has account-users:view. That is strong evidence for live permission revalidation, but it constructs the denied state before token use rather than mutating permissions or membership after issuing a previously valid token. The TODO audit questions explicitly ask whether role changes or account membership changes revoke effective access on the next request.

## Reviewer Summaries
### rr-auth-03-test-quality-floor
- **Assessment:** The RR-AUTH-03 tests are directionally effective: they use real Laravel route/service surfaces, cover same-account acceptance, mismatched account token rejection, missing account_id rejection, and stale token ability rejection through live current-account permission revalidation. I did not see an obvious pass-the-test-only shortcut in the inspected changed tests. The main quality gap is route-matrix completeness: the dispatch itself records a blocked legacy combined account API auth/middleware batch, while the new hardening tests directly exercise only a narrow subset of account-scoped routes.
- **Recommended path:** `Accept the current targeted tests as useful local implementation evidence, but do not treat the test-quality floor as fully closed until the blocked combined account API auth/middleware batch is either repaired, replaced by a deterministic route-matrix equivalent across account_api_v1.php and project_account_api_v1.php, or explicitly waived by the approval authority.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] TQ-RR-AUTH-03-001 Blocked combined account route/middleware batch leaves route-matrix coverage incomplete: The changed feature test proves the core account token-binding semantics on representative account user routes, and the event candidate regression proves one project account route boundary. However, the referenced route files contain broader account-scoped surfaces under the same account middleware model, including account users, credentials, role templates, and events. The dispatch and TODO both record the legacy combined account API auth/middleware batch as blocked, so the audit cannot confirm every account-scoped route rejects missing or mismatched account_id before ability authorization.
  - [low] TQ-RR-AUTH-03-002 Token issuance coverage asserts current-account stamping but not fallback account selection semantics: AccountAuthenticationServiceTest verifies that login stamps account_id when Account::current() is set. The implementation also contains behavior for the no-current-account path, selecting the first accessible account before issuing the token. That fallback affects token binding for multi-account users but is not directly asserted in the inspected changed unit tests.
  - [low] TQ-RR-AUTH-03-003 Role or membership revocation after token issue is implied but not directly proven: The stale-ability test demonstrates that a token carrying account-users:create cannot authorize create when the live current-account role only has account-users:view. That is strong evidence for live permission revalidation, but it constructs the denied state before token use rather than mutating permissions or membership after issuing a previously valid token. The TODO audit questions explicitly ask whether role changes or account membership changes revoke effective access on the next request.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

