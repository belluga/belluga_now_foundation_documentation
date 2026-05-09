# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-critique-dispatch-20260507T1521Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close RR-AUTH-03 yet. Resolve or explicitly waive the push data/actions ability-ceiling ambiguity, add direct login-path coverage for the single-access/no-current-account fallback and multi-account no-current-account behavior, and keep the legacy batch/full-suite-attribution verification debt open until repaired, narrowly substituted, or authority-waived.`

## Merged Findings
### F-B461DECD [medium] Verification debt still prevents approval-clean closure
- **Reviewers:** no-context-paced-reviewer-rr-auth-03
- **Category:** `residual_risk`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `auth-hardening-closure-requires-clean-bounded-or-waived-verification-evidence`
- **Suggested action:** Before marking RR-AUTH-03 complete, repair the legacy batch, map and run a narrower equivalent, or record an approval-authority waiver; separately rerun/attribute the full suite on a clean bounded RR-AUTH-03 baseline or waive the attribution gap.
- **Rationale:** The governing TODO still records the legacy combined account API auth/middleware batch as open verification debt and the full-suite evidence as attributed to an integrated dirty tree with unrelated RR-AUTH-01 changes. That is acceptable for Local-Implemented evidence, but not for final closure or promotion readiness under the dispatch goal.

### F-DDE474BE [medium] Push data/actions routes do not prove Sanctum ceiling or live permission revalidation
- **Reviewers:** no-context-paced-reviewer-rr-auth-03
- **Category:** `security`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-prefixed-routes-that-rely-on-token-ceiling-must-apply-ability-or-documented-equivalent`
- **Suggested action:** Either add the intended abilities or an explicit controller-level equivalent for data/actions, or document these as account-bound recipient endpoints where account membership/audience eligibility intentionally replaces push-messages ability checks. In either case add a regression with an account-bound token lacking push-messages ability to prove the chosen contract.
- **Rationale:** The changed push data/actions routes attach only account middleware, unlike the adjacent account push CRUD routes that also attach abilities middleware. In the bounded implementation, AccountUser::tokenCan() only performs wildcard ceiling and live role revalidation when an ability check calls it. The positive push test uses a push-messages:* token, but because these two routes have no abilities middleware, that test does not prove that push-messages:* is required or that a downgraded/non-push account-bound token would be rejected before reaching the controllers. This may be intentional recipient-route behavior, but it is not documented or evidenced in the bounded package.

### F-D846F6F6 [medium] No-current-account login behavior remains under-specified and under-tested
- **Reviewers:** no-context-paced-reviewer-rr-auth-03
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-login-without-current-account-must-have-explicit-single-access-or-selection-contract`
- **Suggested action:** Add direct AccountAuthenticationService::login tests for no-current-account exact-one fallback success and multi-account no-current-account behavior. If an unbound empty-ability token is intentional, document that consumer contract; otherwise fail closed with an account-selection/auth error.
- **Rationale:** AccountAuthenticationService now resolves a fallback account only when exactly one accessible account exists, but if no account is resolved it still calls issueForAccountUser with empty abilities and null account_id. The fail-closed unit coverage exercises TenantScopedAccessTokenService directly with account-scoped abilities, not the password login path. That leaves the user-facing behavior for multi-account login without selected account ambiguous: it avoids arbitrary account binding, but can still mint an unbound no-ability token instead of requiring account selection or returning an explicit failure.

## Reviewer Summaries
### no-context-paced-reviewer-rr-auth-03
- **Assessment:** Mixed. The core account-token binding path is directionally sound: persisted bearer tokens are stamped, account middleware rejects missing or mismatched account_id, wildcard-aware ceilings are centralized, and live role revalidation is scoped to account middleware context. Approval-clean closure is still blocked by bounded evidence gaps around push data/actions ability-ceiling semantics and no-current-account issuance behavior, plus already-recorded verification debt.
- **Recommended path:** `Do not close RR-AUTH-03 yet. Resolve or explicitly waive the push data/actions ability-ceiling ambiguity, add direct login-path coverage for the single-access/no-current-account fallback and multi-account no-current-account behavior, and keep the legacy batch/full-suite-attribution verification debt open until repaired, narrowly substituted, or authority-waived.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] RR-AUTH-03-NC-001 Push data/actions routes do not prove Sanctum ceiling or live permission revalidation: The changed push data/actions routes attach only account middleware, unlike the adjacent account push CRUD routes that also attach abilities middleware. In the bounded implementation, AccountUser::tokenCan() only performs wildcard ceiling and live role revalidation when an ability check calls it. The positive push test uses a push-messages:* token, but because these two routes have no abilities middleware, that test does not prove that push-messages:* is required or that a downgraded/non-push account-bound token would be rejected before reaching the controllers. This may be intentional recipient-route behavior, but it is not documented or evidenced in the bounded package.
  - [medium] RR-AUTH-03-NC-002 No-current-account login behavior remains under-specified and under-tested: AccountAuthenticationService now resolves a fallback account only when exactly one accessible account exists, but if no account is resolved it still calls issueForAccountUser with empty abilities and null account_id. The fail-closed unit coverage exercises TenantScopedAccessTokenService directly with account-scoped abilities, not the password login path. That leaves the user-facing behavior for multi-account login without selected account ambiguous: it avoids arbitrary account binding, but can still mint an unbound no-ability token instead of requiring account selection or returning an explicit failure.
  - [medium] RR-AUTH-03-NC-003 Verification debt still prevents approval-clean closure: The governing TODO still records the legacy combined account API auth/middleware batch as open verification debt and the full-suite evidence as attributed to an integrated dirty tree with unrelated RR-AUTH-01 changes. That is acceptable for Local-Implemented evidence, but not for final closure or promotion readiness under the dispatch goal.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
