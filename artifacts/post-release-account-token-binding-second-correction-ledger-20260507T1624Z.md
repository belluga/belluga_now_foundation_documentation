# RR-AUTH-03 Second Correction Ledger - 20260507T1624Z

## Ledger Identity
- **Artifact kind:** `correction_ledger`
- **Authoritative:** `false`
- **Edit policy:** `derived_resolution_record`
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Source audit pass:** `20260507T1552Z`
- **Worker:** `Averroes` (`019e0333-b583-7822-80d1-eab6c264aee4`)
- **Worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-second-correction-worker-dispatch-20260507T1606Z.md`

## Reconciled Code / Test Surfaces
- `laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php`
- `laravel-app/scripts/architecture_guardrails.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`

## Resolution Table
| Finding | Status | Resolution Evidence |
| --- | --- | --- |
| `RR-AUTH-03-SEC-POST-001` public `stampAccountId()` fail-open escape hatch | `corrected_pending_fresh_audit` | `stampAccountId()` is private to `TenantScopedAccessTokenService` and throws for blank account context instead of silently preserving unbound account-scoped tokens. |
| `FR-RR-AUTH-03-POST-003` issuer fail-close service-local only | `corrected_pending_fresh_audit` | `scripts/architecture_guardrails.php` now fails production `app` code that directly issues `AccountUser` tokens outside `TenantScopedAccessTokenService`. |
| `TQA-RR-AUTH-03-POST-004` / `VDA-POST-003` stale ambient current-account request-path gap | `corrected_pending_fresh_audit` | `PushMessageFlowTest` adds a request-path regression using an Account A-bound token while ambient `Account::current()` points to inaccessible Account B, then proves the tenant-public push route still accepts the token outside `account` middleware context. |
| `RR-AUTH-03-SEC-POST-002` route ability resource catalog drift | `corrected_pending_fresh_audit` | Architecture guardrails now compare account-prefixed route ability resources against `TenantScopedAccessTokenService::ACCOUNT_SCOPED_ABILITY_RESOURCES`. |
| `VDA-002` / `FR-RR-AUTH-03-POST-001` legacy account auth/middleware batch | `narrower_equivalent_corrected_pending_fresh_audit` | Architecture guardrails now require account-prefixed route ability middleware to include `account` middleware on the route or enclosing group, covering the account auth/middleware boundary without relying on the blocked legacy batch. |
| `VDA-005` / `FR-RR-AUTH-03-POST-002` clean full-suite attribution | `open_verification_debt` | No unrelated dirty files were reverted. Clean bounded full-suite attribution still requires a clean baseline rerun, explicit integrated-baseline acceptance, or approval-authority waiver before final closure. |

## Principal Validation Evidence
- `bash ../delphi-ai/verify_context.sh` -> `Environment Verified: PACED-Ready.`
- `rg -n "stampAccountId\\(|->createToken\\(" app packages routes scripts` -> no external `stampAccountId()` calls; production `createToken()` usage remains outside `AccountUser` issuer paths except `TenantScopedAccessTokenService`.
- `docker compose exec -T app php -l app/Application/Auth/TenantScopedAccessTokenService.php` -> no syntax errors.
- `docker compose exec -T app php -l scripts/architecture_guardrails.php` -> no syntax errors.
- `docker compose exec -T app php -l tests/Feature/Push/PushMessageFlowTest.php` -> no syntax errors.
- `docker compose exec -T app php scripts/architecture_guardrails.php` -> `[ARCH-GUARDRAILS] PASS - no architecture violations found.`
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter=test_tenant_push_route_accepts_account_bound_token_despite_stale_ambient_account` -> `1 passed`, `5 assertions`, `7.58s`.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` -> `154 passed`, `489 assertions`, `101.75s`.
- `docker compose exec -T app ./vendor/bin/pint --test <10 changed php files>` -> `PASS`, `10 files`.
- `git diff --check` and `git diff --cached --check` -> passed.

## Residual Risk
- Package-side direct account-user token issuer bypass is covered indirectly by existing package boundary rules plus the new production `app` issuer guard; the next fresh audit pass must decide whether this is sufficient or requires a package-specific issuer scan.
- Full-suite clean attribution remains open because unrelated RR-AUTH-01 dirty files are still present and must not be reverted by RR-AUTH-03.

## Next Exact Step
Refresh RR-AUTH-03 TODO-local audit-floor dispatch packages and rerun critique, security, verification-debt, test-quality, and final-review lanes. Triple audit and Claude comparison remain blocked until the fresh audit-floor lanes are clean or explicitly classify remaining issues as accepted debt.
