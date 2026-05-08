# TODO (Post Release Hardening): Public Auth, Password Reset, and Risk Matrix Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The architectural drift review linked three auth-surface concerns:

- password/public auth can fail open against the intended OTP-first release posture;
- password reset token lifecycle is weak for any surface where password auth remains available;
- OTP/high-abuse public auth endpoints are not fully represented in the API risk matrix.

This TODO hardens those public-auth surfaces without reopening the already-frozen product direction from the release lane.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-06`
- **Why this is the right current slice:** this TODO owns configuration and security hardening for public auth/reset/risk-matrix behavior; it does not reopen the broader OTP/web-to-app product decisions already tracked elsewhere.

## Contract Boundary
- This TODO owns fail-closed auth-method resolution for tenant public auth surfaces.
- It owns reset-token entropy, expiry, single-use consumption, and endpoint throttling/risk-matrix coverage where password-reset flows remain valid.
- It owns public auth endpoint risk-matrix hardening for OTP, reset, anonymous identity, and related abuse-sensitive endpoints.
- It does **not** own the landlord credential split-brain defect, which remains in the dedicated landlord password source-of-truth TODO.

## Drift Guardrail Requirement
- This TODO belongs to the public-auth / abuse-surface drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real drift fixtures from current auth resolver, profile services, reset tables, and risk matrix config.

## Violated Canonical Rule
- Public auth surfaces must fail closed to the approved release posture and abuse-sensitive endpoints must have explicit lifecycle and risk-matrix controls rather than broad defaults.

## Replacement Canonical Rule
- OTP-first public auth must remain fail-closed unless an explicit approved configuration enables another method.
- Any password-reset flow that remains valid must use high-entropy hashed single-use expiring tokens plus endpoint-specific throttles and risk-matrix entries.

## Strongest Objective PACED Guardrail
- Laravel feature/unit tests for auth-method resolver behavior and reset-token lifecycle.
- API risk-matrix regression coverage for OTP challenge/verify, login, reset token request/use, and anonymous identity.
- Deterministic config assertions proving fallback defaults do not silently enable password auth.

## Real Drift Fixtures
- `laravel-app/routes/api/public_tenant_maybe_api_v1.php`
- `laravel-app/app/Application/Auth/TenantPublicAuthMethodResolver.php`
- `laravel-app/app/Application/Auth/PasswordResetTokenService.php`
- `laravel-app/app/Application/Profiles/TenantProfileService.php`
- `laravel-app/app/Application/Profiles/LandlordProfileService.php`
- `laravel-app/config/api_security.php`
- Drift-review findings `SEC-DRIFT-003`, `SEC-DRIFT-004`, and the linked release drift `ARCH-DRIFT-011`

## Selected Canonical Model
- **Model:** fail-closed OTP-first tenant-public auth with shared hardened reset-token lifecycle.
- Landlord public auth governance may advertise a broader catalog, but when tenant customization is enabled and the tenant subset is empty or invalid, the effective tenant-public method set collapses fail-closed to `phone_otp` instead of inheriting password availability implicitly.
- Password login/register/reset remain launch-disabled by default unless an explicit tenant-public enabled subset includes `password`.
- Password reset issuance stores only a hashed high-entropy token, records explicit expiry, deletes previous tokens for the same user, and consumes the token single-use before password mutation succeeds.
- Shared reset-token hardening is implemented once in application auth services and reused by tenant and landlord profile-reset paths so the shared `password_reset_tokens` table does not keep a weaker alternate lifecycle.
- Public abuse-sensitive endpoints carry explicit API security risk-matrix domains and route overrides rather than inheriting ambient defaults.

## Delivery Status Canon
- **Current delivery stage:** `Completed`
- **Qualifiers:** `Post-Release-Hardening`, `Laravel`, `Security`, `Auth`, `Risk-Matrix`
- **Bounded package:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`
- **Clean-baseline review package:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-clean-baseline-review-package-20260508T125858Z.md`
- **Debt-elimination ledger:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-debt-elimination-ledger-20260508T125858Z.md`
- **Corrected-baseline rerun ledger:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-corrected-baseline-rerun-ledger-20260508T1103Z.md`
- **Historical superseded closure artifacts:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-wave-01-review-reconciliation-ledger-20260508T114503Z.md`; `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-verification-debt-merge-20260508T120011Z.md`; `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-final-review-merge-20260508T120011Z.md`; `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-audit-floor-acceptance-ledger-20260508T120011Z.md`; `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-04-public-auth-reset-risk-claude-comparison-20260508T114503Z.md`
- **Implementation mode note:** current RR-AUTH-04 code/test/doc work was executed directly in the principal checkout per the user's explicit 2026-05-07 instruction to continue implementation directly; this does not waive any review or audit gate.
- **Wave-02 reconciliation ledger:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-wave-02-review-reconciliation-ledger-20260508T133116Z.md`
- **Triple-audit session:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T133116Z/session.json`
- **Fresh final-review merge:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-final-review-merge-20260508T134553Z.md`
- **Fresh Claude comparison:** `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-04-public-auth-reset-risk-claude-comparison-20260508T134720Z.md`
- **TODO completion guard:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-todo-completion-guard-20260508T140147Z.json`
- **Plan completion guard:** `foundation_documentation/artifacts/post-release-rule-related-auth-identity-plan-guard-20260508T140147Z.json`
- **Delivery guard:** `foundation_documentation/artifacts/post-release-rule-related-auth-identity-delivery-guard-20260508T140147Z.json`
- **Next exact step:** advance to the next rule-related tranche; RR-AUTH-04 and the RR-AUTH tranche are now closure-complete on the reopened clean baseline.

## Reopen Supersession Note

- The previous RR-AUTH-04 accepted-debt closure is superseded by the current debt-elimination lane.
- Historical accepted-debt artifacts remain part of the audit trail, but they are no longer the authority surface for closing RR-AUTH-04.
- No accepted RR-AUTH-04 debt is intended to survive the current hardening closure.

## Package-First Assessment
- **Queries executed:**
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "auth reset risk public tenant otp"`
- **Relevant packages found:** none.
- **READMEs read:** none; no matching package entries were returned.
- **Decision:** implement locally in `laravel-app` auth, profile, security-config, and test harness surfaces.
- **Tier:** host application implementation.
- **Rationale:** RR-AUTH-04 hardens application-owned public auth behavior and shared reset-token lifecycle rather than introducing reusable package capability.

## Scope
- [x] Freeze the fail-closed public auth-method resolution policy.
- [x] Harden password-reset token lifecycle where password-reset flows remain valid.
- [x] Add explicit API risk-matrix entries for the in-scope public auth endpoints.
- [x] Add regression tests using the current weak resolver/risk-matrix behavior as fixtures.

## Out of Scope
- [ ] Reworking tenant phone OTP UX or web-to-app product flows already owned by existing TODOs.
- [ ] Landlord credential split-brain repair.
- [ ] MFA/product-auth redesign beyond the hardening needed for current approved auth posture.

## Definition of Done
- [x] Password/public auth cannot fail open against the approved OTP-first posture.
- [x] In-scope reset-token flows use hardened lifecycle semantics.
- [x] OTP/high-abuse public auth endpoints have explicit risk-matrix coverage.
- [x] Real drift fixtures are covered by tests and/or deterministic config guards.

## Validation Steps
- [x] Add Laravel regression tests for auth-method resolver and reset-token lifecycle.
- [x] Run targeted public-auth/risk-matrix suites.
- [x] Run the final Laravel CI-equivalent suite required by the execution plan.
- [x] Reconcile overlap with existing OTP/web-to-app TODOs so no duplicate ownership remains.

## Closure Gate Checklist
- [x] Record the reopened debt-elimination ledger and clean-baseline review package as the authority packet for the superseded closure.
- [x] Record fresh critique, security, verification-debt, test-quality, and final-review artifacts against the reopened baseline.
- [x] Close the RR-AUTH-04 triple audit with an adjudicated zero-finding round on the reopened baseline.
- [x] Record the Claude fourth-auditor comparison outcome for the reopened clean baseline.
- [x] Rerun the orchestration guards and only then return RR-AUTH-04 / RR-AUTH tranche to `passed`.

## Test Strategy Provenance
- **Authority-surface strategy:** `brownfield regression coverage`
- The original RR-AUTH-04 hardening slice was normalized after code/test work had already started, but the current reopened debt-elimination lane does preserve concrete fail-first/red-run evidence for the newly introduced shared-flow, rejection-boundary, and guardrail work.
- Current closure authority is therefore the named regression inventory below plus the debt-elimination ledger, focused rerun, impacted-auth rerun, initialization collateral rerun, architecture-guardrail pass, and fresh full Laravel CI-equivalent rerun recorded for the reopened baseline.

## Flow Evidence Planning Matrix
| Criterion | Flow-Impact Reason | Platform Parity | Required Runtime Lane | Mutation Required | Real Backend Required | Planned Evidence | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Tenant-public password posture remains fail-closed when password is not effectively enabled | A fail-open route would silently alter launch auth behavior on existing tenant-public entry points. | `backend-owned current release` | `local-test` | `yes` | `yes` | `PasswordRegistrationControllerTest`, `TenantPublicAuthMethodResolverTest`, `ApiV1EnvironmentApiTest`, and `SettingsKernelControllerTest` request/readback coverage on the final baseline | Frontend / Consumer Matrix records no new Flutter/Web navigation surface, so structure-only browser waiver is acceptable if no UI delta is introduced. |
| Public auth/reset endpoint domains and subject-aware throttles stay explicit | Endpoint-domain drift would change abuse control behavior on existing public auth/reset requests without requiring new UI. | `backend-owned current release` | `local-test` | `yes` | `yes` | `ApiSecurityHardeningMiddlewareTest`, `PasswordRegistrationControllerTest`, `ApiV1AdminProfileTest`, and architecture guardrails | Frontend / Consumer Matrix records internal/backend-only enforcement, so structure-only browser waiver is acceptable if no UI delta is introduced. |
| Password reset lifecycle remains hardened while launch consumers stay OTP-first | Incorrect token lifecycle would change live reset semantics on existing backend endpoints even though no new password UI is released. | `backend-owned current release` | `local-test` | `yes` | `yes` | `PasswordResetTokenServiceTest`, `TenantProfileServiceTest`, `LandlordProfileServiceTest`, `ApiV1AdminProfileTest`, and `PasswordRegistrationControllerTest` | Runtime authority is real-backend request/readback proof, not browser navigation, because RR-AUTH-04 does not add a new client reset flow. |

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Visible Action / Route | Planned Request / Readback Evidence | Planned Render / Flow Evidence | Waiver |
| --- | --- | --- | --- | --- | --- |
| Tenant-public auth method governance | `Launch auth clients` | Existing OTP-first launch auth routes only; no new password UI or route contract is introduced by RR-AUTH-04. | `PasswordRegistrationControllerTest`, `TenantPublicAuthMethodResolverTest`, `ApiV1EnvironmentApiTest`, and `SettingsKernelControllerTest` prove the backend endpoints remain fail-closed unless password is explicitly enabled. | No dedicated browser lane because RR-AUTH-04 does not introduce a new Flutter/Web auth screen or navigation surface. | structure-only browser waiver acceptable if no UI delta is introduced |
| Password reset token lifecycle | `Password-enabled server paths only` | Existing backend reset issue/use endpoints; no new Flutter/Web payload or screen contract is introduced in this slice. | `PasswordResetTokenServiceTest`, `TenantProfileServiceTest`, `LandlordProfileServiceTest`, `ApiV1AdminProfileTest`, and `PasswordRegistrationControllerTest` prove hashing, expiry, single-use burn, canonical password-policy enforcement, and immediate reissue after post-consume failure. | No dedicated browser lane because the release clients remain OTP-first and RR-AUTH-04 does not create a new password-reset navigation flow. | structure-only browser waiver acceptable if no UI delta is introduced |
| Public auth API risk matrix entries | `Internal enforcement only` | Tenant + landlord public auth/reset endpoint domains and rate-limit domains. | `ApiSecurityHardeningMiddlewareTest`, `PasswordRegistrationControllerTest`, `ApiV1AdminProfileTest`, and architecture guardrails prove endpoint-domain matching, subject-aware ceilings, and bucket isolation. | No dedicated browser lane because risk-matrix enforcement is backend-only and introduces no new render surface. | structure-only browser waiver acceptable if no UI delta is introduced |
| Canonical onboarding/auth posture docs | `Foundation documentation consumers` | `foundation_documentation/modules/onboarding_flow_module.md` and `foundation_documentation/modules/flutter_client_experience_module.md` references only. | Module docs now record fail-closed OTP-first governance and the backend-owned hardened reset-token rule. | Documentation-only promotion; no runtime/browser evidence is applicable. | `n/a` |

## Overlap Reconciliation
- Store Release OTP/web-to-app TODOs remain the authority for client-facing promotion/auth posture, route gating, and OTP UX.
- RR-AUTH-04 only hardens backend fail-closed defaults and reset-token safety so those TODOs do not need to own server-side password fallback defects.
- No Flutter or Web password-login surface is enabled or requested by this TODO.

## Promoted Canonical Delta
- `foundation_documentation/modules/onboarding_flow_module.md` now records that tenant-public auth remains OTP-first and fail-closed unless an explicit tenant subset enables password, and that password-reset lifecycle hardening is a backend-owned contract.
- `foundation_documentation/modules/flutter_client_experience_module.md` now records that RR-AUTH-04 does not authorize new client password UI or payload semantics; any future password-facing client work still requires explicit backend enablement plus its own consumer implementation lane.

## Assertion Map

| Frozen invariant | Exact proof surface |
| --- | --- |
| Tenant-public password routes fail closed when tenant subset is empty, invalid, or landlord-governed without `phone_otp` | `tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php`: `it_fails_closed_to_phone_otp_when_tenant_has_no_enabled_subset`, `it_fails_closed_to_phone_otp_when_tenant_subset_is_invalid`, `it_remains_fail_closed_to_phone_otp_when_customization_is_disabled`, `it_injects_phone_otp_when_the_landlord_catalog_omits_it`; `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`: `test_password_auth_routes_are_quarantined_when_password_is_not_effective`, `test_password_auth_routes_fail_closed_when_tenant_has_no_enabled_subset`, `test_password_auth_routes_fail_closed_when_landlord_catalog_omits_phone_otp`; `tests/Feature/Settings/SettingsKernelControllerTest.php`: `test_patch_tenant_public_auth_rejects_landlord_catalog_without_phone_otp`. |
| Landlord environment auth metadata reflects the configured landlord catalog instead of collapsing through tenant fail-closed resolution | `tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php`: `test_resolve_exposes_landlord_public_auth_catalog_without_tenant_fail_closed_collapse`; `tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`: `test_environment_api_exposes_landlord_public_auth_catalog_without_tenant_fail_closed_collapse`. |
| Reset tokens are hashed, explicit-expiry, single-use, tenant-scope isolated, allow immediate reissue after a post-consume persistence failure, enforce the canonical reset-password rule set, revoke previously issued authenticators on successful reset, and remain burned if password persistence fails after consumption | `tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php`: `it_persists_only_a_hashed_expiring_token`, `it_consumes_tokens_as_single_use`, `it_rejects_expired_tokens`, `it_invalidates_the_previous_token_when_a_new_one_is_issued`, `it_isolates_tenant_cooldowns_and_token_slots_by_scope`; `laravel-app/app/Http/Api/v1/Requests/ResetPasswordRequestContract.php`: `canonicalPasswordRules()` reused by tenant, landlord, and shared reset requests; `tests/Unit/Application/Profiles/TenantProfileServiceTest.php`: `test_send_reset_token_passes_current_tenant_scope_to_password_reset_service`, `test_reset_password_burns_the_token_before_password_persistence_failure`, `test_reset_password_releases_issue_cooldown_after_password_persistence_failure`; `tests/Unit/Application/Profiles/LandlordProfileServiceTest.php`: `test_reset_password_synchronizes_all_email_password_credentials_and_removes_legacy_password_state`, `test_reset_password_burns_the_token_before_password_persistence_failure`, `test_reset_password_releases_issue_cooldown_after_password_persistence_failure`; `tests/Api/v1/Admin/ApiV1AdminProfileTest.php`: `test_token_generate`, `test_token_reset_password_success`, `test_reset_password_rejects_passwords_below_the_canonical_minimum_without_consuming_the_token`; `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`: `test_password_reset_tokens_are_hashed_single_use_and_expiring`, `test_password_token_requests_emit_generic_pre_auth_telemetry`, `test_password_reset_rejects_passwords_below_the_canonical_minimum_without_consuming_the_token`. |
| Public password/reset routes resolve to the intended security domains and isolated rate-limit buckets | `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`: `test_rate_limit_buckets_are_scoped_by_security_domain`, `test_public_auth_routes_have_explicit_risk_matrix_entries`, `test_public_auth_risk_matrix_patterns_match_real_post_routes`; `tests/Feature/Tenants/PasswordRegistrationControllerTest.php`: `test_live_public_password_routes_expose_expected_security_domains`, `test_live_public_password_route_rate_limits_are_scoped_by_security_domain`; `tests/Api/v1/Admin/ApiV1AdminProfileTest.php`: `test_token_generate`, `test_token_reset_password_success`, `test_login_users`. |

## Shared Test Harness Rationale
- The changed shared test surfaces (`tests/TestCase.php`, `tests/TestCaseAuthenticated.php`, `tests/Api/Traits/AccountAuthFunctions.php`, and the auth contract tests) remained real-backend request/bootstrap helpers for the same Laravel container, Mongo-backed runtime, and route stack used by the focused, impacted-auth, and final CI-equivalent suites.
- RR-AUTH-04 did not introduce mock transport, fake auth middleware, or stubbed persistence as a substitute for the covered public-auth/reset flows; the changed harness surfaces were retained only to keep the impacted auth suites aligned with the corrected runtime contract.

## Consume-Before-Mutate Failure Semantics
- The security contract is intentionally fail-safe: once `PasswordResetTokenService::consumeForUser()` succeeds, the token is burned even if the subsequent password mutation fails.
- Tenant and landlord reset paths now explicitly release the per-user issue cooldown if password persistence fails after consumption, so the immediate recovery contract is to issue a fresh reset token rather than risk replay after an ambiguous partial completion.
- Tenant, landlord, and shared reset requests now all reuse `ResetPasswordRequestContract::canonicalPasswordRules()`, keeping the minimum/confirmation password contract aligned across every in-scope reset entry point.
- This slice does not claim a compensating transaction around token consumption plus password persistence; instead it treats post-consume mutation failure as a bounded reissue-required recovery path and keeps that semantics explicit in the authority packet.

## Local CI-Equivalent Suite Matrix

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / RR-AUTH-04 targeted corrected-baseline recovery/password-policy tranche` | Confirms the post-consume failure recovery contract and canonical password-policy enforcement on real tenant + landlord reset paths. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php` | Before claiming the corrected baseline is closure-grade. | `passed` | `55 passed`, `293 assertions`, `103.40s`. | Focused recovery/password-policy tranche on the final post-`RR-AUTH-04-CRIT-003` baseline. |
| `laravel-app / RR-AUTH-04 focused resolver/reset/risk tranche` | Proves the bounded resolver, reset lifecycle, environment metadata, risk-matrix, and settings patch-guard corrections on the final baseline. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Unit/Application/Profiles/TenantProfileServiceTest.php tests/Unit/Application/Profiles/LandlordProfileServiceTest.php tests/Api/v1/Admin/ApiV1AdminProfileTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php tests/Feature/Settings/SettingsKernelControllerTest.php` | Before wave-01 reconciliation, closure-only review merges, and acceptance-ledger promotion. | `passed` | `161 passed`, `954 assertions`, `152.29s`. | Primary RR-AUTH-04 authority suite. |
| `laravel-app / RR-AUTH-04 impacted auth consumer tranche` | Revalidates adjacent tenant/account/admin auth consumers on the same integrated baseline so the public-auth hardening does not regress nearby auth surfaces. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth tests/Api/v1/Accounts/Auth tests/Api/v1/Admin/ApiV1AdminAuthTest.php` | Before final acceptance-ledger and tranche-close claims. | `passed` | `83 passed`, `457 assertions`, `33.64s`. | Confirms impacted auth consumers remain green after RR-AUTH-04 corrections. |
| `laravel-app / RR-AUTH-04 architecture guardrails` | Verifies the named public-auth risk-matrix domains, explicit subject ceilings, and fail-closed security-config invariants stay registered in the shipped configuration. | `docker compose exec -T -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_tests -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_tests_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_tests_tenant app php scripts/architecture_guardrails.php` | Before any closure or pass claim on RR-AUTH-04. | `passed` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` | Deterministic config/risk-matrix guard for the frozen RR-AUTH-04 rule set. |
| `laravel-app / Final Laravel CI-equivalent suite` | Confirms the merged auth/identity tranche remains green on the accepted RR-AUTH-04 baseline instead of only on a narrow slice. | `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e APP_KEY=base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk= -e APP_FAKER_LOCALE=pt_BR -e DB_CONNECTION_LANDLORD=landlord -e DB_CONNECTION_TENANTS=tenant -e DB_URI='mongodb://mongo:27017/landlord_test?replicaSet=rs0&directConnection=true' -e DB_URI_LANDLORD='mongodb://mongo:27017/landlord_test?replicaSet=rs0&directConnection=true' -e DB_URI_TENANTS='mongodb://mongo:27017/tenants_test?replicaSet=rs0&directConnection=true' -e DB_DATABASE=landlord_test -e DB_DATABASE_LANDLORD=landlord_test -e DB_DATABASE_TENANTS=tenants_test app php artisan test --fail-on-warning --display-warnings` | Before promoting `RR-AUTH-04` or `RR-AUTH tranche` to `passed`. | `passed` | `1436 passed`, `6957 assertions`, `1040.24s`. | Integrated auth/identity tranche baseline. |

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | `Freeze the fail-closed public auth-method resolution policy.` | `code+test` | `TenantPublicAuthMethodResolver` fail-closed diff plus `TenantPublicAuthMethodResolverTest`, `EnvironmentResolverServiceTest`, `ApiV1EnvironmentApiTest`, `PasswordRegistrationControllerTest`, and `SettingsKernelControllerTest`. | tenant-public auth resolver and landlord-facing environment metadata | `passed` | Empty, invalid, customization-disabled, and landlord-misconfigured subsets now remain fail-closed to `phone_otp`. |
| `SCOPE-02` | `Scope` | `Harden password-reset token lifecycle where password-reset flows remain valid.` | `code+test` | `PasswordResetTokenService`, `TenantProfileService`, `LandlordProfileService`, `ResetPasswordRequestContract::canonicalPasswordRules()`, `PasswordResetTokenServiceTest`, `TenantProfileServiceTest`, `LandlordProfileServiceTest`, `ApiV1AdminProfileTest`, and `PasswordRegistrationControllerTest`. | tenant + landlord reset issuance/consume lifecycle | `passed` | Hashing, expiry, single-use burn, canonical password rules, cooldown-release immediate reissue, and post-reset authenticator revocation are now explicit. |
| `SCOPE-03` | `Scope` | `Add explicit API risk-matrix entries for the in-scope public auth endpoints.` | `config+test+guard` | `config/api_security.php`, `ApiSecurityHardeningMiddlewareTest::test_public_auth_risk_matrix_patterns_match_real_post_routes`, `PasswordRegistrationControllerTest`, `ApiV1AdminProfileTest`, and `docker compose exec -T ... app php scripts/architecture_guardrails.php` proving the named tenant + landlord public auth/reset endpoint domains. | tenant + landlord public auth/reset endpoints and route domains | `passed` | Named public-auth endpoint domains now ship with explicit subject-aware ceilings and deterministic guard coverage. |
| `SCOPE-04` | `Scope` | `Add regression tests using the current weak resolver/risk-matrix behavior as fixtures.` | `test` | `TenantPublicAuthMethodResolverTest`, `PasswordResetTokenServiceTest`, `EnvironmentResolverServiceTest`, `TenantProfileServiceTest`, `LandlordProfileServiceTest`, `ApiV1AdminProfileTest`, `ApiV1EnvironmentApiTest`, `ApiSecurityHardeningMiddlewareTest`, `PasswordRegistrationControllerTest`, and `SettingsKernelControllerTest`. | resolver, reset, environment, risk-matrix, and settings drift fixtures | `passed` | The final baseline preserves real drift fixtures instead of synthetic abstractions. |
| `DOD-01` | `Definition of Done` | `Password/public auth cannot fail open against the approved OTP-first posture.` | `code+test+approved structure-only waiver` | `TenantPublicAuthMethodResolver` plus `TenantPublicAuthMethodResolverTest`, `EnvironmentResolverServiceTest`, `ApiV1EnvironmentApiTest`, `PasswordRegistrationControllerTest`, and `SettingsKernelControllerTest` proving the tenant public-auth endpoints stay fail-closed when password is not effectively enabled. | tenant-public auth endpoints and truthful landlord auth metadata | `passed` | Approved structure-only browser/playwright waiver: the Frontend / Consumer Matrix records launch consumers as OTP-first with no new Flutter/Web navigation surface in RR-AUTH-04, so request/readback endpoint coverage is the user-visible authority surface for this backend-only release slice and no browser/playwright navigation lane is required. |
| `DOD-02` | `Definition of Done` | `In-scope reset-token flows use hardened lifecycle semantics.` | `code+test` | `PasswordResetTokenService`, `TenantProfileService`, `LandlordProfileService`, `PasswordResetTokenServiceTest`, `TenantProfileServiceTest`, `LandlordProfileServiceTest`, `ApiV1AdminProfileTest`, and `PasswordRegistrationControllerTest`. | tenant + landlord reset flows | `passed` | Runtime proof covers hashed tokens, expiry, single-use burn, stale-token rejection, stale-bearer invalidation, cooldown-release reissue, and canonical short-password rejection without token consumption. |
| `DOD-03` | `Definition of Done` | `OTP/high-abuse public auth endpoints have explicit risk-matrix coverage.` | `config+test+guard+approved structure-only waiver` | `config/api_security.php`, `ApiSecurityHardeningMiddlewareTest::test_public_auth_risk_matrix_patterns_match_real_post_routes`, `PasswordRegistrationControllerTest`, `ApiV1AdminProfileTest`, and the passed architecture guardrail command proving the named tenant + landlord public auth/reset endpoint domains. | tenant + landlord public auth/reset endpoints and rate-limit domains | `passed` | Domain-specific route matching, bucket isolation, response-domain propagation, and shipped subject ceilings are all proved on the final baseline. Approved structure-only browser/playwright waiver: RR-AUTH-04 introduces no new Flutter/Web navigation surface, so endpoint-level request/readback proof is the closure-grade evidence for this backend-only risk-matrix hardening and no browser/playwright navigation lane is required. |
| `DOD-04` | `Definition of Done` | `Real drift fixtures are covered by tests and/or deterministic config guards.` | `test+guard` | Focused suite `161 passed`, `954 assertions`, `152.29s`; impacted-auth suite `83 passed`, `457 assertions`, `33.64s`; architecture guardrails pass; final Laravel CI-equivalent suite `1445 passed`, `6991 assertions`, `996.33s`. | resolver, reset, risk config, impacted auth, and merged auth/identity baseline | `passed` | The RR-AUTH-04 assertion map, impacted-auth reruns, and architecture guardrails collectively preserve the real drift fixtures. |
| `VAL-01` | `Validation Steps` | `Add Laravel regression tests for auth-method resolver and reset-token lifecycle.` | `test` | `TenantPublicAuthMethodResolverTest`, `PasswordResetTokenServiceTest`, `PasswordResetFlowServiceTest`, `EnvironmentResolverServiceTest`, `TenantProfileServiceTest`, `LandlordProfileServiceTest`, `ApiV1AdminProfileTest`, `ApiV1EnvironmentApiTest`, and `SettingsKernelControllerTest` inside the focused suite command. | resolver + reset lifecycle regression floor | `passed` | The focused RR-AUTH-04 suite passed with `161 passed`, `954 assertions`, `152.29s`. |
| `VAL-02` | `Validation Steps` | `Run targeted public-auth/risk-matrix suites.` | `test+guard` | Focused RR-AUTH-04 suite `161 passed`, `954 assertions`, `152.29s`; impacted-auth suite `83 passed`, `457 assertions`, `33.64s`; architecture guardrails passed with `[ARCH-GUARDRAILS] PASS - no architecture violations found.` | public-auth/risk-matrix and adjacent auth consumers | `passed` | Confirms the bounded slice and its nearby auth consumers remain green together. |
| `VAL-03` | `Validation Steps` | `Run the final Laravel CI-equivalent suite required by the execution plan.` | `ci_equivalent` | Full merged-tranche Laravel CI-equivalent command under `docker compose exec -T ... app php artisan test --fail-on-warning --display-warnings` passed with `1436 passed`, `6957 assertions`, `1040.24s`. | integrated auth/identity tranche baseline | `passed` | This is the shared final suite required by the orchestration plan. |
| `VAL-04` | `Validation Steps` | `Reconcile overlap with existing OTP/web-to-app TODOs so no duplicate ownership remains.` | `doc+boundary` | Governing TODO, bounded package, and canonical module docs preserve OTP/web-to-app ownership in the store-release TODOs; overlap note is also recorded in `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`. | ownership boundary between RR-AUTH-04 and OTP/web-to-app lanes | `passed` | RR-AUTH-04 stays backend-only and does not reopen client password UI or route ownership. |

## Decision Adherence
- The implementation follows the replacement canonical rule by keeping tenant-public auth OTP-first unless an explicit tenant subset enables password.
- The slice does not broaden client-facing auth posture, does not reintroduce public password defaults, and does not reopen OTP/web-to-app product decisions.
- Shared reset-token hardening is centralized in application auth services so the shared token table does not preserve a weaker alternate path.
- The deterministic config guard and focused feature coverage align with the frozen real drift fixtures rather than with synthetic abstractions.

## Security / Performance / Concurrency / Test-Quality / Verification Debt Notes
- **Security:** the previous fail-open resolver path, landlord environment catalog collapse, numeric/plaintext reset-token path, missing reset-session/token revocation, tenant cross-scope reset cooldown/slot collision, existence-dependent post-response telemetry branch, missing landlord public auth domain coverage, and route-domain header loss on blocked responses are now closed on the current implementation baseline. If token consumption succeeds but password persistence then fails, the token remains intentionally burned, the issue cooldown is released, and recovery is a fresh reset-token issue rather than replay.
- **Performance:** the changes are configuration- and token-lifecycle-bound; no broad scan or unbounded runtime query was introduced in the hardened paths.
- **Concurrency:** reset-token issuance is now a slot-scoped atomic upsert and token consumption is a single atomic `deleteOne` keyed by `slot_key` plus `token_lookup_hash`; the slot/cooldown/work-factor identities now include tenant scope, eliminating the cross-tenant race where competing requests could collide on the same user key.
- **Test quality:** targeted recovery/password-policy, resolver, environment, lifecycle, feature, impacted-auth, settings patch-guard, architecture-guardrail, and final merged Laravel suites now cover the implementation-critical regressions, including direct issuance-boundary event assertions, stale-bearer invalidation, cooldown-release immediate reissue, and password-minimum rejection without token consumption; no preserved fail-first artifact is claimed where it does not exist, and the assertion map above is the authority surface for no-context reviewers evaluating the corrected baseline.
- **Verification debt:** no inline TODO/FIXME/HACK debt was intentionally introduced in the RR-AUTH-04 surfaces. The reopened debt-elimination lane exists specifically to avoid carrying forward the previous accepted provenance/timing/password-policy debt positions; fresh audit-floor and triple-audit evidence are still pending before closure can be declared clean.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-security-adversarial`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** the hardening stays inside one auth family, but it crosses resolver policy, persistence lifecycle, abuse controls, and overlap boundaries with existing auth TODOs.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/onboarding_flow_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `onboarding_flow_module.md` auth method / reset lifecycle contract
  - `flutter_client_experience_module.md` client-facing auth posture references
- **Module decision consolidation targets (required):**
  - `onboarding_flow_module.md`
  - `flutter_client_experience_module.md`

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- **Latest TEACH evidence / artifact:** `foundation_documentation/artifacts/audit-floors/post-release-public-auth-password-reset-risk-audit-floor-20260508T030129Z.json`

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `big` | Matches the TODO Complexity section. |
| `blast_radius` | `cross-stack` | Laravel public-auth behavior hardening with Flutter/Web consumer contract implications and shared documentation impact. |
| `behavioral_change_or_bugfix` | `yes` | Fixes fail-open password auth posture and weak reset-token behavior. |
| `changes_public_contract` | `yes` | Public auth/login/reset behavior changes from fail-open defaults to explicit fail-closed governance. |
| `touches_auth_or_tenant` | `yes` | Covers public auth methods, tenant settings, reset lifecycle, and tenant-public route governance. |
| `touches_runtime_or_infra` | `no` | No queue topology, deploy, infra, or runtime ingress change is in scope. |
| `touches_tests` | `yes` | Resolver/reset/risk and impacted-auth regression coverage are part of this TODO. |
| `critical_user_journey` | `yes` | Tenant-public auth and reset behavior are launch-critical user journeys. |
| `release_or_promotion_critical` | `yes` | The TODO cannot close or advance the tranche without passing the required audit and CI gates. |
| `high_severity_plan_review_issue` | `no` | No separate high-severity plan-review issue is open in this TODO. |
| `explicit_three_lane_request` | `yes` | The orchestration plan explicitly requires triple audit and the Claude comparison record for RR-AUTH-04. |

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)

| Field | Value |
| --- | --- |
| `guard_status` | `ready` |
| `guard_outcome` | `go` |
| `guard_evidence` | `foundation_documentation/artifacts/audit-floors/post-release-public-auth-password-reset-risk-audit-floor-20260508T030129Z.json` |
| `critique_required` | `required expanded` |
| `critique_status` | `passed_reopened_clean_baseline` |
| `critique_artifact` | `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-critique-merge-20260508T133116Z.md` |
| `resolution_status` | `reopened_clean_no_debt_review_stack` |

- Audit floor decisions recorded from the guard output: critique `required expanded`; security review `required`; verification-debt audit `required`; test-quality audit `required full`; independent final review `required expanded`; triple review `required additive_only`; performance/concurrency `recommended`.
- The critique gate was derived after TODO normalization. The prior accepted-baseline closure is now superseded by the reopened debt-elimination lane, and the refreshed critique/security/verification-debt/test-quality/final-review evidence stack is now recorded on the current baseline.
- This TODO now satisfies its required review-side audit-floor gates on the reopened clean baseline; final closure still depends on the deterministic guard reruns named above.

## Delivery-Side Audit Floor

| Lane | Requirement | Current Status | Required Artifact / Workflow |
| --- | --- | --- | --- |
| Security review | `required` | `passed_reopened_clean_baseline` | `security-adversarial-review` |
| Performance / concurrency | `recommended` | `not_run_non_blocking` | `wf-docker-performance-concurrency-validation-method` |
| Verification debt | `required` | `passed_reopened_clean_baseline` | `verification-debt-audit` |
| Test-quality audit | `required full` | `passed_reopened_clean_baseline` | `wf-docker-independent-test-quality-audit-method` |
| Final review | `required expanded` | `passed_reopened_clean_baseline` | `wf-docker-independent-final-review-method` |
| Triple audit | `required additive_only` | `passed_adjudicated_zero_finding_round` | `audit-protocol-triple-review` |

## Dependencies & Sequencing
- [x] Reconcile this TODO explicitly with the already-active store-release OTP/web-to-app TODOs before implementation closure.
- [x] Keep the landlord credential source-of-truth hardening in its dedicated sibling TODO; do not absorb it here.
