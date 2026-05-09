# RR-AUTH-03 Runtime Invariant Correction Ledger - 20260507T1655Z

## Scope

- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Audit source:** `20260507T1624Z` TODO-local critique, security, verification-debt, test-quality, and final-review pass.
- **Worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-worker-dispatch-20260507T1636Z.md`
- **Worker:** `Euclid` subagent (`019e034d-f937-7cf0-8f3a-3938d144bba3`)
- **Closure posture:** code/test correction is reconciled and staged in the principal Laravel checkout; closure still requires a fresh TODO-local audit-floor pass and `VDA-005` clean attribution resolution or approval-authority waiver before triple audit / Claude comparison.

## Correction Summary

| Finding | Status | Evidence |
| --- | --- | --- |
| `RR-AUTH-03-SEC-001` direct `AccountUser::createToken()` runtime boundary | `corrected_pending_fresh_audit` | `AccountUser::createToken()` now rejects account-scoped abilities unless called inside a validated issuer context. `TenantScopedAccessTokenService::issueForAccountUser()` is the service path that opens that context after account-access validation. |
| `RR-AUTH-03-SEC-002` route/resource guardrail proof | `corrected_pending_fresh_audit` | Existing architecture guardrails still pass after the runtime invariant change and enforce production issuer discipline plus account-prefixed route ability/resource alignment. |
| `RR-AUTH-03-SEC-003` stale ambient `Account::current()` semantics | `corrected_pending_fresh_audit` | Sequential stale-context coverage is present in `TenantPublicAccountTokenScopeTest` and passed in the focused suite. |
| `RR-AUTH-03-SEC-004` membership-removal and mixed-role matrix | `corrected_pending_fresh_audit` | Existing focused suite includes role downgrade, membership removal, wrong-account same-ability, missing binding, removed binding, and read/write asymmetry coverage. |
| `RR-AUTH-03-CRIT-001` / `RR-AUTH-03-FR-001` legacy auth/middleware equivalent acceptance | `evidenced_pending_fresh_audit` | Deterministic narrower equivalent remains the accepted closure candidate: account-prefixed route ability middleware must be paired with `account` middleware on the route or enclosing group. |
| `RR-AUTH-03-CRIT-002` / `VDA-005` / `RR-AUTH-03-FR-002` full-suite clean attribution | `open_verification_debt` | Existing full-suite evidence validates the integrated dirty local Laravel state, not a clean bounded RR-AUTH-03-only baseline. This remains blocked until clean bounded rerun, explicit integrated-baseline acceptance, or approval-authority waiver. |
| `RR-AUTH-03-FR-003` route-binding/tokenCan confirmations | `evidenced_pending_fresh_audit` | Route inventory and token ceiling behavior are now represented as guardrail/test evidence instead of pending assumptions. |

## Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| `bash delphi-ai/verify_context.sh` | `passed`: `Environment Verified: PACED-Ready.` |
| `docker compose exec -T app php -l app/Models/Tenants/AccountUser.php` | `passed`. |
| `docker compose exec -T app php -l app/Application/Auth/TenantScopedAccessTokenService.php` | `passed`. |
| `docker compose exec -T app php -l tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php` | `passed`. |
| `docker compose exec -T app php -l tests/Feature/Push/PushMessageFlowTest.php` | `passed`. |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php --filter='direct_account_user_create_token|no_current_account_issuance_after_stale_account_context'` | `passed`: `3 passed`, `11 assertions`, `10.09s`. |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` | `passed`: `157 passed`, `500 assertions`, `141.44s`. |
| `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| `docker compose exec -T app ./vendor/bin/pint --test <10 changed RR-AUTH-03 files>` | `passed`: `10 files`. |
| `git diff --check` | `passed`. |
| `git diff --cached --check` | `passed`. |

## Next Gate

Rerun fresh TODO-local audit-floor packets for critique, security, verification-debt, test-quality, and final review. Do not run triple audit or the Claude fourth-auditor comparison until the fresh audit accepts the runtime invariant correction and `VDA-005` is resolved or explicitly waived.
