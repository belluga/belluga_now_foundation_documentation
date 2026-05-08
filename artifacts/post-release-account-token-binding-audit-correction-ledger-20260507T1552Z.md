# Post-Release Account Token Binding Audit Correction Ledger

## Artifact Identity

- **Artifact type:** `audit_correction_ledger`
- **Execution slice:** `RR-AUTH-03`
- **Authoritative:** `false`
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Source audit pass:** `20260507T1521Z`
- **Correction worker:** `Poincare` subagent
- **Resolution state:** `code_test_correction_staged_pending_fresh_audit`

## Corrected Surfaces

- `laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php`
- `laravel-app/app/Application/Auth/AccountAuthenticationService.php`
- `laravel-app/routes/api/packages/project_account_api_v1/push_handler.php`
- `laravel-app/tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php`
- `laravel-app/tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`

## Correction Summary

- Account-scoped token issuance now rejects explicit/current account ids that are inaccessible to the account user before token creation/stamping.
- Account login without current account now succeeds only for exactly one accessible account and fails closed for multi-account or no-access users.
- Push message `data` and `actions` account routes now require `abilities:push-messages:read`.
- Persisted-token negative ceiling coverage proves a lower-ability bearer token cannot pass just because the live role has a broader permission.
- Account-scoped issuer fail-close tests cover exact, resource-wildcard, and literal wildcard ability variants.
- Membership removal now has next-request denial coverage.

## Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| Worker `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php` | `passed`: `153 passed`, `484 assertions`, `93.47s`. |
| Orchestrator `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php` | `passed`: `153 passed`, `484 assertions`, `126.39s`. |
| `docker compose exec -T app ./vendor/bin/pint --test <6 changed files>` | `passed`: `PASS`, `6 files`. |
| `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| `git diff --check` and `git diff --cached --check` | `passed`: no output. |

## Next Exact Step

Run fresh post-correction RR-AUTH-03 TODO-local audit-floor gates using the refreshed package. Do not treat this ledger as closure evidence by itself.
