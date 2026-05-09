# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-security-dispatch-20260507T1521Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close RR-AUTH-03 until the explicit account_id issuer path is either hardened to reject inaccessible account IDs or formally waived as an internal-only invariant. After that, rerun the fresh audit-floor gates and keep the existing legacy combined-batch verification debt open until repaired, replaced by a deterministic route assertion, or waived.`

## Merged Findings
### F-5EF8CAE2 [medium] Explicit account_id issuance can mint account-scoped tokens for accounts the user cannot access
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** For account-scoped abilities, validate the resolved account_id against the normalized AccountUser access IDs before createToken/stampAccountId, and throw when the account is not accessible. Add explicit tests for a supplied foreign account_id and a stale current Account::current() that the user cannot access.
- **Rationale:** TenantScopedAccessTokenService::resolveAccountIdForAbilities returns any non-empty explicit or current account ID before checking the AccountUser access list, and stampAccountId also blindly stamps the supplied account_id. Current account middleware prevents use on inspected account routes when route binding is present, but the issuer still creates durable wrong-account bearer artifacts and relies on every downstream route being perfectly guarded. The PushMessageFlowTest foreign-account regression demonstrates route rejection, but it also demonstrates that the service permits creating the foreign-account token in the first place.

### F-EE328CF6 [low] Route-binding closure still depends on manual inspection plus blocked legacy batch debt
- **Reviewers:** RR-AUTH-03 no-context security adversarial reviewer
- **Category:** `residual_risk`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Repair the blocked combined batch or add a route-list/architecture assertion that every api/v1/accounts/{account_slug} route requiring Account::current() includes account middleware or an equivalent fail-closed binding guard, then record clean bounded evidence or an approval-authority waiver.
- **Rationale:** The bounded route files inspected here are correctly account-bound, including push data/actions, account users/roles, and events. However, the package still records the legacy combined account API auth/middleware batch as blocked and the full-suite attribution as integrated with unrelated dirty state, so closure does not yet have a deterministic guard that future account-prefixed package routes cannot bypass account middleware and fall back to token ceiling only.

## Reviewer Summaries
### RR-AUTH-03 no-context security adversarial reviewer
- **Assessment:** The inspected post-fix route and authorization path materially closes the original Account A token to Account B route abuse: account-prefixed user, role, event, and push routes establish account middleware; bearer tokens missing or mismatching account_id are rejected; persisted token abilities are treated as wildcard-aware ceilings before live current-account permission revalidation. One issuer-side hardening gap remains because explicit account_id inputs can still mint wrong-account account-scoped tokens before route guards reject use.
- **Recommended path:** `Do not close RR-AUTH-03 until the explicit account_id issuer path is either hardened to reject inaccessible account IDs or formally waived as an internal-only invariant. After that, rerun the fresh audit-floor gates and keep the existing legacy combined-batch verification debt open until repaired, replaced by a deterministic route assertion, or waived.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] RR-AUTH-03-SEC-001 Explicit account_id issuance can mint account-scoped tokens for accounts the user cannot access: TenantScopedAccessTokenService::resolveAccountIdForAbilities returns any non-empty explicit or current account ID before checking the AccountUser access list, and stampAccountId also blindly stamps the supplied account_id. Current account middleware prevents use on inspected account routes when route binding is present, but the issuer still creates durable wrong-account bearer artifacts and relies on every downstream route being perfectly guarded. The PushMessageFlowTest foreign-account regression demonstrates route rejection, but it also demonstrates that the service permits creating the foreign-account token in the first place.
  - [low] RR-AUTH-03-SEC-002 Route-binding closure still depends on manual inspection plus blocked legacy batch debt: The bounded route files inspected here are correctly account-bound, including push data/actions, account users/roles, and events. However, the package still records the legacy combined account API auth/middleware batch as blocked and the full-suite attribution as integrated with unrelated dirty state, so closure does not yet have a deterministic guard that future account-prefixed package routes cannot bypass account middleware and fall back to token ceiling only.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
