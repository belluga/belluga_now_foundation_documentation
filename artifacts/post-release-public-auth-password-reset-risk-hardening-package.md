# Post-Release Public Auth / Password Reset / Risk Matrix Hardening Package

## Package Identity

- **Package:** `post-release-public-auth-password-reset-risk-hardening`
- **Execution slice:** `RR-AUTH-04`
- **Scope:** fail-closed tenant-public auth governance, hardened password-reset token lifecycle, and explicit public-auth API risk-matrix coverage
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Orchestration status:** `passed`

## Orchestration Binding

- The canonical orchestration plan still governs RR-AUTH-04 evidence requirements.
- Current RR-AUTH-04 implementation was executed directly in the principal checkout because the user explicitly instructed the agent on 2026-05-07 to continue implementation directly rather than pause for separate worker dispatch.
- That execution-mode deviation does **not** waive required TODO-local audit-floor reviews, triple audit, the Claude fourth-auditor comparison record, or the shared final Laravel CI-equivalent suite.
- This package is derived and non-authoritative. The TODO remains the governing contract.
- Deterministic audit-floor anchor: `foundation_documentation/artifacts/audit-floors/post-release-public-auth-password-reset-risk-audit-floor-20260508T030129Z.json`.
- Historical accepted-baseline ledger (superseded): `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-audit-floor-acceptance-ledger-20260508T120011Z.md`.
- Current reopened authority packet: `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-clean-baseline-review-package-20260508T125858Z.md`; `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-debt-elimination-ledger-20260508T125858Z.md`.

## Closure Gate Status

- RR-AUTH-04 was explicitly reopened after the prior accepted-debt closure because hardening closure now requires eliminating the recorded residual debt rather than carrying it forward.
- Local implementation, focused reruns, impacted-auth rerun, initialization collateral rerun, and architecture guardrails are green on the reopened baseline recorded in `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-debt-elimination-ledger-20260508T125858Z.md`.
- The fresh full Laravel CI-equivalent rerun is green, critique/security/verification-debt/test-quality are all clean on the reopened baseline, the fresh final review is clean, the triple-audit session closed with an adjudicated zero-finding round, and the fresh Claude fourth-auditor comparison also returned `clean`.
- Deterministic closure guards are also green:
  - `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-todo-completion-guard-20260508T140147Z.json`
  - `foundation_documentation/artifacts/post-release-rule-related-auth-identity-plan-guard-20260508T140147Z.json`
  - `foundation_documentation/artifacts/post-release-rule-related-auth-identity-delivery-guard-20260508T140147Z.json`

## Bounded Package

- **Included:** tenant-public auth method resolver behavior, password reset token issue/consume lifecycle, explicit public-auth risk-matrix entries, deterministic guardrails, impacted auth-harness stabilization, and canonical module sync.
- **Excluded:** OTP UX redesign, Flutter/Web promotion/auth boundary work, landlord credential source-of-truth repair, broader MFA/product-auth redesign, and unrelated tenant-admin/UI work.

## Real Drift Fixture

- Tenant-public auth customization could fail open to password auth when the tenant subset was empty or invalid.
- Password reset tokens were numeric/plaintext, not explicit-expiry hardened, and not robustly single-use.
- Public auth abuse-sensitive endpoints were not completely represented in the risk matrix as named route domains with explicit limits.

## Frozen Rule Set

### Violated Rule

- Public auth surfaces were allowed to inherit broader behavior from ambient defaults instead of failing closed to the approved OTP-first posture, and password-reset lifecycle/risk controls were weaker than the required security baseline.

### Replacement Canonical Rule

- Tenant-public auth remains OTP-first and fail-closed unless an explicit approved tenant subset enables password.
- Empty or invalid tenant auth subsets collapse to `phone_otp` rather than re-enabling password implicitly.
- Password reset tokens are high-entropy, hashed at rest, explicit-expiry, single-use, and consumed before password mutation succeeds.
- Public abuse-sensitive auth endpoints carry explicit risk-matrix domains and endpoint-specific rate ceilings.

## Changed Surfaces

### Laravel Source

- `laravel-app/app/Application/Auth/TenantPublicAuthMethodResolver.php`
- `laravel-app/app/Application/Auth/PasswordResetTokenService.php`
- `laravel-app/app/Application/Auth/LandlordAuthenticationService.php`
- `laravel-app/app/Events/Auth/PasswordResetTokenIssued.php`
- `laravel-app/app/Application/LandlordUsers/LandlordUserAccessService.php`
- `laravel-app/app/Application/Profiles/TenantProfileService.php`
- `laravel-app/app/Application/Profiles/LandlordProfileService.php`
- `laravel-app/app/Http/Api/v1/Controllers/ProfileControllerTenant.php`
- `laravel-app/app/Http/Api/v1/Requests/ResetPasswordRequest.php`
- `laravel-app/app/Http/Api/v1/Requests/ResetPasswordRequestContract.php`
- `laravel-app/app/Http/Api/v1/Requests/ResetPasswordRequestLandlord.php`
- `laravel-app/app/Http/Api/v1/Requests/ResetPasswordRequestTenant.php`
- `laravel-app/config/api_security.php`
- `laravel-app/scripts/architecture_guardrails.php`

### Laravel Tests / Harness

- `laravel-app/tests/TestCase.php`
- `laravel-app/tests/TestCaseAuthenticated.php`
- `laravel-app/tests/Api/Traits/AccountAuthFunctions.php`
- `laravel-app/tests/Api/v1/Tenants/Auth/Contracts/ApiV1AnonymousIdentityTestContract.php`
- `laravel-app/tests/Api/v1/Tenants/Auth/Contracts/ApiV1PasswordRegistrationTestContract.php`
- `laravel-app/tests/Api/v1/Accounts/Auth/Contracts/ApiV1AccountAuthTestContract.php`
- `laravel-app/tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php`
- `laravel-app/tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php`
- `laravel-app/tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php`
- `laravel-app/tests/Unit/Application/Profiles/TenantProfileServiceTest.php`
- `laravel-app/tests/Unit/Application/Profiles/LandlordProfileServiceTest.php`
- `laravel-app/tests/Api/v1/Admin/ApiV1AdminProfileTest.php`
- `laravel-app/tests/Api/v1/Admin/ApiV1AdminAuthTest.php`
- `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`
- `laravel-app/tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`
- `laravel-app/tests/Feature/Tenants/PasswordRegistrationControllerTest.php`
- `laravel-app/tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`

### Documentation

- `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- this package

## Deterministic External Evidence Surfaces

- `laravel-app/config/api_security.php` now declares explicit public-auth route domains for anonymous identity, OTP challenge/verify, password login/register, and password reset issue/use, including shipped `subject_requests_per_minute` ceilings for every subject-aware password/reset domain.
- `laravel-app/scripts/architecture_guardrails.php` now requires the RR-AUTH-04 public-auth risk-matrix domains to stay registered in the canonical security config with explicit subject-aware throttle ceilings and `fail_closed_on_backend_error=true`.
- `laravel-app/tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php` asserts the in-scope public-auth domains exist and carry the expected subject-aware throttle ceilings.

## Frontend / Consumer Matrix

| Producer Surface | Consumer Status | Evidence / Waiver |
| --- | --- | --- |
| Tenant-public auth governance | `consumer contract clarified` | Launch consumers remain OTP-first. No new password UI or route contract is created in RR-AUTH-04. |
| Password reset lifecycle | `backend_only_current_release` | No new Flutter/Web surface is introduced; the hardening applies to any password-enabled server path. RR-AUTH-04 only hardened server-side reset issuance/consume semantics by adding tenant-scoped cooldown/slot identities plus generic pre-auth telemetry normalization; the existing request/response payload contract for reset issue/use was not widened. |
| Public auth risk matrix | `internal_only` | Enforcement-only backend surface. No frontend/admin consumer required. |
| Canonical auth posture docs | `promoted` | Onboarding and Flutter module docs now carry the fail-closed governance and hardened reset-token rule. |

## Promoted Canonical Delta

- `foundation_documentation/modules/onboarding_flow_module.md` now records fail-closed OTP-first tenant-public auth governance plus the backend-owned hardened reset-token lifecycle rule.
- `foundation_documentation/modules/flutter_client_experience_module.md` now records that RR-AUTH-04 does not introduce new client password UI/payload semantics and remains contract-transparent to launch-facing consumers.

## Validation Summary

- Targeted corrected-baseline recovery/password-policy slice:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php`
  - result: `55 passed`, `293 assertions`, duration `103.40s`
- Focused RR-AUTH-04 resolver/reset/risk suite:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php tests/Feature/Settings/SettingsKernelControllerTest.php`
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php tests/Feature/Settings/SettingsKernelControllerTest.php`
  - result: `161 passed`, `954 assertions`, duration `152.29s`
- Impacted auth consumer suite:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth tests/Api/v1/Accounts/Auth tests/Api/v1/Admin/ApiV1AdminAuthTest.php`
  - result: `83 passed`, `457 assertions`, duration `33.64s`
- Architecture guardrails:
  - `docker compose exec -T -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_tests -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_tests_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_tests_tenant app php scripts/architecture_guardrails.php`
  - result: `[ARCH-GUARDRAILS] PASS - no architecture violations found.`
- Full Laravel CI-equivalent suite:
  - `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e APP_KEY=base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk= -e APP_FAKER_LOCALE=pt_BR -e DB_CONNECTION_LANDLORD=landlord -e DB_CONNECTION_TENANTS=tenant -e DB_URI='mongodb://mongo:27017/landlord_test?replicaSet=rs0&directConnection=true' -e DB_URI_LANDLORD='mongodb://mongo:27017/landlord_test?replicaSet=rs0&directConnection=true' -e DB_URI_TENANTS='mongodb://mongo:27017/tenants_test?replicaSet=rs0&directConnection=true' -e DB_DATABASE=landlord_test -e DB_DATABASE_LANDLORD=landlord_test -e DB_DATABASE_TENANTS=tenants_test app php artisan test --fail-on-warning --display-warnings`
  - result: `1445 passed`, `6991 assertions`, duration `996.33s`
- Post-Claude shared-flow rerun:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php`
  - result: `5 passed`, `9 assertions`, duration `2.28s`

## Assertion / Evidence Map

| Frozen invariant | Exact proof surface |
| --- | --- |
| Empty/invalid tenant-public subsets cannot silently re-enable password | `tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php`: `it_fails_closed_to_phone_otp_when_tenant_has_no_enabled_subset`, `it_fails_closed_to_phone_otp_when_tenant_subset_is_invalid`, `it_remains_fail_closed_to_phone_otp_when_customization_is_disabled`, `it_injects_phone_otp_when_the_landlord_catalog_omits_it`; `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`: `test_password_auth_routes_are_quarantined_when_password_is_not_effective`, `test_password_auth_routes_fail_closed_when_tenant_has_no_enabled_subset`, `test_password_auth_routes_fail_closed_when_landlord_catalog_omits_phone_otp`; `tests/Feature/Settings/SettingsKernelControllerTest.php`: `test_patch_tenant_public_auth_rejects_landlord_catalog_without_phone_otp`. |
| Landlord environment auth metadata stays truthful to the configured landlord catalog | `tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php`: `test_resolve_exposes_landlord_public_auth_catalog_without_tenant_fail_closed_collapse`; `tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`: `test_environment_api_exposes_landlord_public_auth_catalog_without_tenant_fail_closed_collapse`. |
| Reset tokens are hashed, expiring, single-use, tenant-scope isolated, allow immediate reissue after a post-consume persistence failure, enforce the canonical reset-password rule set, revoke previously issued authenticators on successful reset, and remain burned after consumption | `tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php`: `it_persists_only_a_hashed_expiring_token`, `it_consumes_tokens_as_single_use`, `it_rejects_expired_tokens`, `it_invalidates_the_previous_token_when_a_new_one_is_issued`, `it_isolates_tenant_cooldowns_and_token_slots_by_scope`; `tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php`: `test_reset_releases_the_issue_cooldown_when_password_persistence_fails_after_consume`; `laravel-app/app/Http/Api/v1/Requests/ResetPasswordRequestContract.php`: `canonicalPasswordRules()` reused by tenant, landlord, and shared reset requests; `tests/Unit/Application/Profiles/TenantProfileServiceTest.php`: `test_send_reset_token_passes_current_tenant_scope_to_password_reset_service`, `test_reset_password_burns_the_token_before_password_persistence_failure`, `test_reset_password_releases_issue_cooldown_after_password_persistence_failure`; `tests/Unit/Application/Profiles/LandlordProfileServiceTest.php`: `test_reset_password_synchronizes_all_email_password_credentials_and_removes_legacy_password_state`, `test_reset_password_burns_the_token_before_password_persistence_failure`, `test_reset_password_releases_issue_cooldown_after_password_persistence_failure`; `tests/Api/v1/Admin/ApiV1AdminProfileTest.php`: `test_token_generate`, `test_token_reset_password_success`, `test_reset_password_rejects_passwords_below_the_canonical_minimum_without_consuming_the_token`; `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`: `test_password_reset_tokens_are_hashed_single_use_and_expiring`, `test_password_token_requests_emit_generic_pre_auth_telemetry`, `test_password_reset_rejects_passwords_below_the_canonical_minimum_without_consuming_the_token`. |
| Public password/reset routes expose the intended domains and isolated throttling buckets | `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`: `test_rate_limit_buckets_are_scoped_by_security_domain`, `test_public_auth_routes_have_explicit_risk_matrix_entries`, `test_public_auth_risk_matrix_patterns_match_real_post_routes`; `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`: `test_live_public_password_routes_expose_expected_security_domains`, `test_live_public_password_route_rate_limits_are_scoped_by_security_domain`; `tests/Api/v1/Admin/ApiV1AdminProfileTest.php`: `test_token_generate`, `test_token_reset_password_success`, `test_login_users`. |

## Shared Test Harness Rationale

- The changed helper/base-test surfaces (`tests/TestCase.php`, `tests/TestCaseAuthenticated.php`, `tests/Api/Traits/AccountAuthFunctions.php`, and the touched auth contract tests) remained real-backend bootstrap/request helpers for the same Laravel container and Mongo-backed runtime used by the focused, impacted-auth, and full-suite evidence above.
- RR-AUTH-04 did not replace the public-auth/reset request paths with mocks, fake middleware, or stub persistence. The harness changes were limited to keeping existing auth suites aligned with the corrected runtime contract.

## Test Strategy Provenance

- Authority surface: `brownfield regression coverage`.
- The original RR-AUTH-04 slice was normalized after code/test work had already started, but the current reopened debt-elimination lane does preserve concrete fail-first targets for the new shared reset-flow, rejection-boundary, password-policy, and structural-guardrail work.
- The authoritative closure substitute is therefore no longer an accepted provenance debt position; it is the reopened debt-elimination ledger plus the fresh focused, impacted-auth, initialization-collateral, architecture-guardrail, and final Laravel CI-equivalent reruns on the same clean baseline.

## Failure Semantics

- The reset-token safety contract is intentionally fail-safe: once `PasswordResetTokenService::consumeForUser()` succeeds, the token is burned even if the subsequent password mutation fails.
- Tenant and landlord reset paths now explicitly release the per-user issue cooldown if password persistence fails after token consumption, so the recovery path is immediate reissue rather than replay.
- Tenant and landlord reset validation now share `ResetPasswordRequestContract::canonicalPasswordRules()`, keeping the minimum/confirmation password contract aligned across all in-scope reset requests.
- RR-AUTH-04 does not claim a compensating transaction around token consumption and password persistence. It claims an explicit reissue-required recovery contract instead.

## Current Proof Snapshot

| Invariant | Current Baseline Status | Evidence |
| --- | --- | --- |
| Empty/invalid tenant auth subset cannot silently re-enable password auth | `proved_current_baseline` | `TenantPublicAuthMethodResolver` collapses disabled/empty/invalid tenant subsets to `phone_otp`, `SettingsKernelControllerTest` blocks landlord catalog patches that omit `phone_otp`, and `PasswordRegistrationControllerTest` proves live password routes stay fail-closed on those paths. |
| Landlord public auth catalog remains truthful on landlord environment surfaces | `proved_current_baseline` | `TenantPublicAuthMethodResolver` now exposes landlord-facing `available_methods`, `enabled_methods`, `effective_methods`, and `effective_primary_method` directly from the configured landlord catalog, and `EnvironmentResolverServiceTest` plus `ApiV1EnvironmentApiTest` prove that landlord environment payloads no longer collapse through tenant fail-closed resolution. |
| Password reset tokens are hashed, explicit-expiry, single-use, scope-isolated, and recover safely through immediate reissue | `proved_current_baseline` | `PasswordResetTokenService` now uses tenant-scoped slot/cooldown/work-factor identities, slot-scoped atomic upsert/delete semantics with hashed lookup keys, generic pre-auth telemetry normalization, shared profile-service reuse, and shared canonical reset-password rules. Tenant and landlord coverage prove stale-token rejection, one-time consumption, immediate cooldown release after post-consume persistence failure, rejection of short passwords without token consumption, and successful login after the replacement password is set. |
| Public abuse-sensitive auth endpoints have explicit risk-matrix domains | `proved_current_baseline` | `config/api_security.php`, `scripts/architecture_guardrails.php`, `ApiSecurityHardeningMiddlewareTest`, `PasswordRegistrationControllerTest`, and `ApiV1AdminProfileTest` now cover tenant and landlord public-auth domains plus domain-scoped throttling, explicit subject ceilings, and response headers. |
| RR-AUTH-04 does not reopen OTP/web-to-app ownership | `proved_current_baseline` | Governing TODO, this package, and promoted module docs explicitly keep client-facing OTP/web-to-app posture under the store-release TODOs while RR-AUTH-04 hardens backend defaults only. |

## Current Closure Findings

1. RR-AUTH-04 is no longer using the previously accepted-debt closure as authority. The reopened clean-baseline packet is `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-clean-baseline-review-package-20260508T125858Z.md`.
2. The debt-elimination implementation is green across the focused RR-AUTH-04 suite (`161 passed`, `954 assertions`, `152.29s`), the impacted-auth suite (`83 passed`, `457 assertions`, `33.64s`), the initialization collateral rerun (`4 passed`, `19 assertions`, `18.77s`), and architecture guardrails (`[ARCH-GUARDRAILS] PASS - no architecture violations found.`).
3. Deterministic audit escalation still governs the reopened RR-AUTH-04 closure floor: critique `required expanded`, security `required`, verification debt `required`, test-quality `required full`, final review `required expanded`, triple audit `required additive_only`, and performance/concurrency `recommended`.
4. The reopened baseline now also carries a green full Laravel CI-equivalent suite (`1445 passed`, `6991 assertions`, `996.33s`).
5. Fresh critique/security/verification-debt/test-quality merges are clean on the reopened baseline: `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-wave-02-review-reconciliation-ledger-20260508T133116Z.md`.
6. The fresh final-review lane is clean and closure-ready: `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-final-review-merge-20260508T134553Z.md`.
7. The fresh triple-audit session closed with zero findings across all three lanes; the only round classification wrinkle was lexical recommended-path variance, adjudicated explicitly in `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T133116Z/round-01/resolution.md`.
8. The fresh Claude fourth-auditor comparison independently returned `clean` and only low-severity observations already addressed in the code/evidence packet: `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-04-public-auth-reset-risk-claude-comparison-20260508T134720Z.md`.
9. The deterministic TODO/orchestration guards also returned `go`, so RR-AUTH-04 now closes on the reopened clean baseline without accepted debt.

## Blockers / Residual Risks

- No RR-AUTH-04 implementation or governance blocker remains on the reopened clean baseline.
- The direct-principal implementation mode for RR-AUTH-04 remains an approved execution-mode deviation in the orchestration plan; it did not waive any audit-floor or deterministic closure requirement.

## Next Exact Step

Advance to the next rule-related tranche; RR-AUTH-04 is closed on the reopened clean baseline and no accepted debt survives this hardening slice.
