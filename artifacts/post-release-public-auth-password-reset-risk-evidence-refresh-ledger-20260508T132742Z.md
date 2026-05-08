# RR-AUTH-04 Evidence Refresh Ledger - 20260508T132742Z

## Scope

- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- **Bounded review package refreshed:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-clean-baseline-review-package-20260508T125858Z.md`
- **Reason for refresh:** no-context critique/test-quality/final-review lanes requested more explicit proof for invalid-reset uniformity, risk-matrix authority binding, and behavior-level password-floor provenance.

## Refreshed Proof Added

### Invalid-reset equivalence proof

- Added `tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php::test_missing_user_and_wrong_token_paths_share_the_same_invalid_reset_sequence`.
  - This asserts the missing-user and wrong-token branches traverse the same invalid-reset sequence after identity resolution:
    - `attemptConsumeForUser(...)`
    - `rejectInvalidResetAttempt(...)`
- Added `tests/Feature/Tenants/PasswordRegistrationControllerTest.php::test_password_reset_missing_user_and_wrong_token_share_the_same_invalid_response_contract`.
  - This proves the tenant-public HTTP contract is identical for:
    - existing email + wrong token
    - missing email + wrong token
  - Both cases now prove the same:
    - `422` status
    - `X-Api-Security-Domain: tenant_public_password_reset`
    - identical validation payload with `reset_token`

### Risk-matrix authority binding

- The authoritative risk-matrix surface for RR-AUTH-04 is `laravel-app/config/api_security.php`.
- The explicit in-scope entries are:
  - `tenant_public_password_login`
  - `tenant_public_password_register`
  - `tenant_public_password_reset_token`
  - `tenant_public_password_reset`
  - `landlord_public_password_login`
  - `landlord_public_password_reset_token`
  - `landlord_public_password_reset`
- Each entry ships explicit:
  - route `pattern`
  - `requests_per_minute`
  - `subject_requests_per_minute`
  - `fail_closed_on_backend_error`
- Current proof surfaces bound to that authority:
  - `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php::test_public_auth_routes_have_explicit_risk_matrix_entries`
  - `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php::test_public_auth_risk_matrix_patterns_match_real_post_routes`
  - `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php::test_tenant_public_password_routes_remain_guarded_by_explicit_auth_method_middleware`
  - `docker compose exec -T ... app php scripts/architecture_guardrails.php`

### Behavior-level password-floor provenance

- A controlled partial reverse was executed against:
  - `app/Http/Api/v1/Requests/PasswordRegistrationRequest.php`
  - `app/Http/Api/v1/Requests/ResetPasswordRequestContract.php`
- Red-run command:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php --filter=common_breached_passwords`
- Observed pre-fix failure:
  - `Tests\\Feature\\Tenants\\PasswordRegistrationControllerTest > password registration rejects common breached passwords`
  - expected `422`, received `201`
- Interpretation:
  - without the request-contract password-floor rewire, the tenant-public registration flow accepted `Password123!`
  - this is a behavior-level red failure, not just a missing-symbol failure
- Restore-green confirmation:
  - same command after restore passed `3 passed`, `24 assertions`, `7.09s`

## Targeted Validation Refresh

- Targeted refresh suite:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`
  - result: `42 passed`, `330 assertions`, `10.43s`
- Password-floor restore filter:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php --filter=common_breached_passwords`
  - result: `3 passed`, `24 assertions`, `7.09s`

## Provenance Clarification

- The original reopen ledger already preserved implementation-gap red evidence for the shared reset-flow / rejection-boundary delta.
- This refresh adds:
  - explicit comparative current-baseline proof for invalid-reset equivalence
  - behavior-level red provenance for the canonical password floor
  - explicit authoritative binding for the public-auth risk matrix
- The structural guardrail rewrite is therefore no longer justified only by a summarized pass statement; it is tied to the authoritative `config/api_security.php` surface, real route-pattern assertions, explicit required middleware assertions, and the structural architecture-guardrail command.
