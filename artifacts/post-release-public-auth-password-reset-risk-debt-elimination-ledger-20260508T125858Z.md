# RR-AUTH-04 Debt Elimination Ledger - 20260508T125858Z

## Scope

- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- **Bounded package under refresh:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`
- **Reason for reopen:** the user explicitly rejected RR-AUTH-04 closure on accepted debt at hardening time and required elimination of every recorded residual item before orchestration closure.

## Reopened Residual Items

| Item | Prior status | Closure target |
| --- | --- | --- |
| `SEC-RRAUTH04-004` | accepted medium debt | prove uniform invalid-reset handling through a shared rejection boundary and explicit regression coverage |
| `SEC-RRAUTH04-003` | accepted low debt | add canonical common/breached-password screening to password registration/reset/update/create flows |
| `ELEGANCE-RESET-FLOW-SPLIT` | accepted structural debt | remove duplicated reset orchestration from tenant/landlord profile services |
| `STRUCTURE-RISK-MATRIX-GUARD-BRITTLE` | accepted structural debt | replace text-only password-route guardrail with structural router/middleware verification |
| `ELEGANCE-DEAD-RESET-HELPERS` | accepted low debt | remove dead reset-token helpers from the shared token service |
| `RR-AUTH-04-TQ-001` / `TQ-LOW-01` | accepted verification debt | preserve fresh fail-first evidence for the reopened slice and close the provenance gap on the reworked concerns |

## Fresh Fail-First Evidence

- **Red-run command:**
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`
- **Observed pre-fix failures:**
  - `Class "App\\Application\\Auth\\PasswordResetFlowService" not found`
  - `Call to undefined method App\\Application\\Auth\\PasswordResetTokenService::attemptConsumeForUser()`
  - `Call to undefined method App\\Application\\Auth\\PasswordResetTokenService::rejectInvalidResetAttempt()`
- **Behavior-level password-floor reconstruction:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-evidence-refresh-ledger-20260508T132742Z.md` now records a controlled request-contract reverse where tenant-public registration accepted `Password123!` with `201` before the canonical request-floor rewire was restored.
- **Interpretation:** the reopened slice now carries preserved structural fail-first evidence, behavior-level password-floor red evidence, and explicit comparative invalid-reset equivalence proof; this is materially stronger than the prior accepted-baseline provenance position.

## Implemented Corrections

### Shared reset flow normalization

- Added `laravel-app/app/Application/Auth/PasswordResetFlowService.php`.
- Centralized tenant + landlord reset issuance and consumption orchestration behind one application service.
- Forced both missing-user and wrong-token reset paths through:
  - `PasswordResetTokenService::attemptConsumeForUser(...)`
  - `PasswordResetTokenService::rejectInvalidResetAttempt(...)`
- Preserved the intentional "token stays burned after successful consume even if password persistence fails" contract while releasing the issue cooldown for immediate reissue on post-consume persistence failure.
- The shared-flow regression floor now names that branch explicitly through:
  - `tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php::test_reset_releases_the_issue_cooldown_when_password_persistence_fails_after_consume`
  - `tests/Unit/Application/Profiles/TenantProfileServiceTest.php::test_reset_password_burns_the_token_before_password_persistence_failure`
  - `tests/Unit/Application/Profiles/TenantProfileServiceTest.php::test_reset_password_releases_issue_cooldown_after_password_persistence_failure`
  - `tests/Unit/Application/Profiles/LandlordProfileServiceTest.php::test_reset_password_burns_the_token_before_password_persistence_failure`
  - `tests/Unit/Application/Profiles/LandlordProfileServiceTest.php::test_reset_password_releases_issue_cooldown_after_password_persistence_failure`

### Token-service hardening cleanup

- Extended `laravel-app/app/Application/Auth/PasswordResetTokenService.php` with:
  - `attemptConsumeForUser(...)`
  - `rejectInvalidResetAttempt(...)`
- Removed dead helper residue so the service no longer carries unused reset-token utility paths.

### Canonical password policy floor

- Added `laravel-app/app/Rules/CommonBreachedPasswordRule.php`.
- Added `laravel-app/app/Support/Validation/CanonicalPasswordRules.php`.
- The breached/common-password floor is intentionally a curated local denylist expanded manually through hardening review; it does not depend on runtime network lookups.
- Rewired password-bearing request contracts to the canonical shared rules:
  - `PasswordRegistrationRequest`
  - `ResetPasswordRequestContract`
  - `UpdatePasswordRequest`
  - `AccountUserCreateRequest`
  - `LandlordUserCreateRequest`
  - `RegisterUserRequest`
  - `CredentialLinkRequest`
  - `InitializeRequest`
- Adjusted initialization fixtures to use a non-blocklisted password because `InitializeRequest` now inherits the same canonical password floor as the other password-bearing entry points.

### Structural risk-matrix guardrail

- Reworked `laravel-app/scripts/architecture_guardrails.php` so tenant public password routes are checked through bootstrapped Laravel routes plus gathered middleware, instead of source-text regex matching.
- Bound the guardrail to the authoritative `laravel-app/config/api_security.php` risk-matrix surface and explicit route/middleware assertions in `ApiSecurityHardeningMiddlewareTest`.

### Regression coverage added

- New unit coverage:
  - `tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php`
  - `tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php`
- Expanded feature/api coverage:
  - `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`
  - `tests/Api/v1/Admin/ApiV1AdminProfileTest.php`
  - `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`
  - `tests/Unit/Application/Profiles/TenantProfileServiceTest.php`
  - `tests/Unit/Application/Profiles/LandlordProfileServiceTest.php`
- Evidence-refresh additions:
  - `PasswordResetFlowServiceTest::test_missing_user_and_wrong_token_paths_share_the_same_invalid_reset_sequence`
  - `PasswordResetFlowServiceTest::test_reset_releases_the_issue_cooldown_when_password_persistence_fails_after_consume`
  - `PasswordRegistrationControllerTest::test_password_reset_missing_user_and_wrong_token_share_the_same_invalid_response_contract`
- Collateral contract-alignment reruns:
  - `tests/Feature/Initialization/InitializationControllerTest.php`
  - `tests/Unit/Application/Initialization/SystemInitializationServiceTest.php`

## Post-Fix Validation

| Lane | Command | Outcome |
| --- | --- | --- |
| Profile-service recovery rerun | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php` | `passed`: `26 passed`, `71 assertions`, `147.85s` |
| Focused RR-AUTH-04 suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php tests/Feature/Settings/SettingsKernelControllerTest.php` | `passed`: `161 passed`, `954 assertions`, `152.29s` |
| Impacted auth suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth tests/Api/v1/Accounts/Auth tests/Api/v1/Admin/ApiV1AdminAuthTest.php` | `passed`: `83 passed`, `457 assertions`, `33.64s` |
| Initialization collateral rerun | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Initialization/InitializationControllerTest.php tests/Unit/Application/Initialization/SystemInitializationServiceTest.php` | `passed`: `4 passed`, `19 assertions`, `18.77s` |
| Architecture guardrails | `docker compose exec -T -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_tests -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_tests_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_tests_tenant app php scripts/architecture_guardrails.php` | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| Evidence refresh targeted suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php` | `passed`: `42 passed`, `330 assertions`, `10.43s` |
| Password-floor restore filter | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php --filter=common_breached_passwords` | `passed`: `3 passed`, `24 assertions`, `7.09s` |
| Full Laravel CI-equivalent | `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e APP_KEY=base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk= -e APP_FAKER_LOCALE=pt_BR -e DB_CONNECTION_LANDLORD=landlord -e DB_CONNECTION_TENANTS=tenant -e DB_URI='mongodb://mongo:27017/landlord_test?replicaSet=rs0&directConnection=true' -e DB_URI_LANDLORD='mongodb://mongo:27017/landlord_test?replicaSet=rs0&directConnection=true' -e DB_URI_TENANTS='mongodb://mongo:27017/tenants_test?replicaSet=rs0&directConnection=true' -e DB_DATABASE=landlord_test -e DB_DATABASE_LANDLORD=landlord_test -e DB_DATABASE_TENANTS=tenants_test app php artisan test --fail-on-warning --display-warnings` | `passed`: `1445 passed`, `6991 assertions`, `996.33s` |

## Provisional Position Before Fresh Review Lanes

- The previously accepted RR-AUTH-04 debt items are now implemented against code and regression coverage rather than carried as closure exceptions.
- Final closure is still pending:
  - refreshed TODO/package/plan synchronization
  - fresh audit-escalation guard rerun
  - critique / security / verification-debt / test-quality / final-review no-context lanes
  - fresh triple-audit session that closes `clean`
