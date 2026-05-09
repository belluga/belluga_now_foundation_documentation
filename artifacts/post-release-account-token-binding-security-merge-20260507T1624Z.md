# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-security-dispatch-20260507T1624Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `unknown`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Hold RR-AUTH-03 at the audit floor until the TODO records deterministic evidence for runtime issuer enforcement, complete account-route/resource guardrail coverage, stale Account::current() reset semantics, and membership-removal revocation. If any legacy batch remains blocked, record an explicit waiver or acceptance rationale for the narrower equivalent before closure.`

## Merged Findings
### F-FABD6021 [high] Direct AccountUser token issuance is not proven fail-closed at the runtime boundary
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-user-token-issuer-runtime-invariant`
- **Suggested action:** Make account-scoped token creation fail closed at the token creation/stamp boundary, not only through package-side issuer scans. Add a negative production-path regression for direct AccountUser::createToken() with account-scoped abilities and no validated account context.
- **Rationale:** The dispatch explicitly focuses on direct AccountUser createToken production bypass and residual package-side issuer scan risk. The bounded package says Averroes added a production issuer-discipline guardrail and public stampAccountId fail-open coverage, but it does not prove that AccountUser::createToken() itself rejects account-scoped abilities unless a validated account context and account_id stamp are present. If enforcement is only a scan or call-site discipline rule, any missed production path can mint an unbound or wrongly bound account-workspace bearer token.

### F-C7F5D816 [high] Complete account-prefixed route and ability-resource guardrail coverage is asserted but not proven
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-prefixed-route-current-account-guardrail`
- **Suggested action:** Attach a deterministic router/resource inventory test or guardrail that enumerates account-prefixed package routes, identifies routes requiring Account::current(), and fails when account middleware or the required ability resource mapping is absent.
- **Rationale:** The replacement rule requires account-prefixed package routes that depend on Account::current() to use account middleware or an equivalent fail-closed guard. The package identifies push data/actions fixes and later claims account-prefixed route ability resource guardrail coverage, but it does not provide a deterministic route inventory or resource-catalog proof. A single uncovered account-prefixed route can authorize against absent, stale, or mismatched account context and become an account breakout path.

### F-DD4F6DD2 [medium] Stale ambient Account::current() semantics remain under-specified in the package
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `security`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `request-scoped-current-account-reset`
- **Suggested action:** Record the request-scoped Account::current() reset/fail-closed invariant and include sequential negative tests covering account A context followed by no-current-account issuance and account B route access.
- **Rationale:** The package says second-correction evidence covers stale ambient current-account request-path coverage and no-current-account login semantics, but it does not state the invariant that clears or rejects stale account context across public, tenant, private, and account-scoped issuance paths. Because token mismatch rejection depends on Account::current(), stale ambient state can cause false denial and, if combined with issuer/stamp mistakes, wrong-account token binding.

### F-D66812B7 [medium] Membership-removal revocation and mixed-role asymmetry need explicit closure evidence
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `account-token-live-membership-revalidation-matrix`
- **Suggested action:** Attach or add focused next-request regressions for role downgrade, membership removal, wrong-account same ability, and read/write asymmetry before marking the revocation surface closed.
- **Rationale:** The package asks whether role-downgrade evidence is enough or whether membership-removal is residual debt, then later states membership-removal evidence exists without including the decisive matrix. For account-scoped bearer tokens, next-request live permission revalidation must reject deleted, detached, or disabled account memberships even when the token ceiling still contains matching abilities. Mixed-role fixtures also need wrong-account same-ability and read/write asymmetry coverage.

### F-86596F4D [medium] Legacy auth/middleware batch debt still weakens audit-floor closure
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `blocked-security-suite-waiver-or-equivalent-evidence`
- **Suggested action:** Record the explicit acceptance rationale for the narrower equivalent, or repair and rerun the blocked legacy batch. Do not close the security audit floor while this remains ambiguous.
- **Rationale:** The package says the legacy combined account API auth/middleware batch was blocked by fixture or harness issues and remains verification debt unless repaired, accepted through a narrower equivalent, or waived. It also says Averroes supplied a deterministic narrower equivalent, but the package does not record the acceptance decision. Without that decision, the audit cannot distinguish real product authorization drift from harness-only debt.

## Reviewer Summaries
### RR-AUTH-03 no-context security adversarial reviewer
- **Assessment:** Blocked for security closure from the bounded package evidence. The package records meaningful post-Averroes corrections, but it still does not prove the two highest-risk invariants from the package alone: direct AccountUser token creation must be runtime fail-closed, and every account-prefixed package route/resource path that depends on Account::current() must be covered by account binding middleware or an equivalent guard. These are account and tenant breakout surfaces, so package-side assertions are not enough for closure.
- **Recommended path:** `Hold RR-AUTH-03 at the audit floor until the TODO records deterministic evidence for runtime issuer enforcement, complete account-route/resource guardrail coverage, stale Account::current() reset semantics, and membership-removal revocation. If any legacy batch remains blocked, record an explicit waiver or acceptance rationale for the narrower equivalent before closure.`
- **Performance:** `unknown`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] RR-AUTH-03-SEC-001 Direct AccountUser token issuance is not proven fail-closed at the runtime boundary: The dispatch explicitly focuses on direct AccountUser createToken production bypass and residual package-side issuer scan risk. The bounded package says Averroes added a production issuer-discipline guardrail and public stampAccountId fail-open coverage, but it does not prove that AccountUser::createToken() itself rejects account-scoped abilities unless a validated account context and account_id stamp are present. If enforcement is only a scan or call-site discipline rule, any missed production path can mint an unbound or wrongly bound account-workspace bearer token.
  - [high] RR-AUTH-03-SEC-002 Complete account-prefixed route and ability-resource guardrail coverage is asserted but not proven: The replacement rule requires account-prefixed package routes that depend on Account::current() to use account middleware or an equivalent fail-closed guard. The package identifies push data/actions fixes and later claims account-prefixed route ability resource guardrail coverage, but it does not provide a deterministic route inventory or resource-catalog proof. A single uncovered account-prefixed route can authorize against absent, stale, or mismatched account context and become an account breakout path.
  - [medium] RR-AUTH-03-SEC-003 Stale ambient Account::current() semantics remain under-specified in the package: The package says second-correction evidence covers stale ambient current-account request-path coverage and no-current-account login semantics, but it does not state the invariant that clears or rejects stale account context across public, tenant, private, and account-scoped issuance paths. Because token mismatch rejection depends on Account::current(), stale ambient state can cause false denial and, if combined with issuer/stamp mistakes, wrong-account token binding.
  - [medium] RR-AUTH-03-SEC-004 Membership-removal revocation and mixed-role asymmetry need explicit closure evidence: The package asks whether role-downgrade evidence is enough or whether membership-removal is residual debt, then later states membership-removal evidence exists without including the decisive matrix. For account-scoped bearer tokens, next-request live permission revalidation must reject deleted, detached, or disabled account memberships even when the token ceiling still contains matching abilities. Mixed-role fixtures also need wrong-account same-ability and read/write asymmetry coverage.
  - [medium] RR-AUTH-03-SEC-005 Legacy auth/middleware batch debt still weakens audit-floor closure: The package says the legacy combined account API auth/middleware batch was blocked by fixture or harness issues and remains verification debt unless repaired, accepted through a narrower equivalent, or waived. It also says Averroes supplied a deterministic narrower equivalent, but the package does not record the acceptance decision. Without that decision, the audit cannot distinguish real product authorization drift from harness-only debt.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
