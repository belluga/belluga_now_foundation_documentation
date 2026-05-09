# RR-AUTH-03 Worker Checkpoint - 2026-05-07

## Artifact Identity

- **Artifact type:** `worker_checkpoint_manifest`
- **Checkpoint status:** `runtime_invariant_worker_reconciled_fresh_audits_pending`
- **Execution slice:** `RR-AUTH-03`
- **Worker slice:** `WS-AUTH-03 Account Token Binding`
- **Laravel checkpoint commit reconciled in principal:** `cd0da71806f9459ae8daa4b4821d9ec434c643ac`
- **Commit message:** `RR-AUTH-03 bind account tokens to account authorization context`
- **Laravel fix worker commit reconciled in principal:** `41286b0296a8ea375081aba1047f8fe6fe84022b`
- **Fix commit message:** `fix: scope account permission revalidation to account routes`

## Scope

- Included: account-user token `account_id` stamping, current-account token mismatch rejection, wildcard-aware token-ceiling enforcement, fail-closed account-scoped token issuance, direct `AccountUser::createToken()` account-scoped runtime invariant enforcement, strict account ID comparisons, account-middleware-scoped live current-account role permission revalidation, push package route binding, mixed-role multi-account regression coverage, and canonical docs sync.
- Excluded: tenant-public OTP behavior, public auth reset/risk posture, unrelated tenant-admin authorization cleanup, broader ability redesign, TODO-local review gates, triple audit, and Claude fourth-auditor record.

## Canonical Model

- RR-AUTH-03 selected the hybrid account binding model.
- Account-user bearer tokens are stamped with `account_id` at issue/stamp time.
- Account-scoped routes reject bearer tokens with missing or mismatched `account_id` against `Account::current()`.
- Sanctum token abilities are only a ceiling.
- The Sanctum ceiling honors literal `*`, exact abilities, and account-workspace resource wildcards before live current-account role permissions become authoritative.
- `AccountUser::tokenCan()` revalidates live current-account role permissions only when `CheckUserAccess` establishes account-scoped auth context through the `account` middleware.
- Account-scoped token issuance fails closed when account-workspace abilities cannot be bound to a resolvable account context.
- Direct account-scoped `AccountUser::createToken()` issuance fails closed unless `TenantScopedAccessTokenService` opened a validated issuer context.
- Account-prefixed package routes that depend on `Account::current()` must use the `account` middleware or an equivalent fail-closed binding guard; the push message `data` and `actions` routes are reconciled to this rule in this checkpoint.

## Implementation Evidence

- `TenantScopedAccessTokenService` stamps account context onto account-user tokens.
- `CheckUserAccess` rejects missing or mismatched token account context against the current account route context.
- `AccountUser::tokenCan()` revalidates live current-account role permissions inside account-scoped middleware context instead of trusting copied token abilities alone.
- `routes/api/packages/project_account_api_v1/push_handler.php` now routes push message `data` and `actions` through the `account` middleware instead of direct `InitializeAccount`.
- `AccountAuthenticationService` and `AccountUserAccessService` align issued/stamped token behavior with the selected account context.
- `AccountUser::tokenCan()` and `AccountUserAccessService` apply wildcard-aware ceiling/live-permission checks for `*`, exact abilities, and resource wildcards without allowing cross-account reuse.
- `TenantScopedAccessTokenService` rejects account-scoped ability issuance when no explicit/current/single-access account binding can be resolved.
- `AccountUser::createToken()` rejects direct account-scoped issuance outside the validated issuer context, while preserving non-account-scoped direct token creation.
- Regression coverage proves mixed-role multi-account behavior and account auth boundary behavior through current token issuance and request-time access paths.

## Changed Laravel Surfaces

- `laravel-app/app/Application/Accounts/AccountUserAccessService.php`
- `laravel-app/app/Application/Auth/AccountAuthenticationService.php`
- `laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php`
- `laravel-app/app/Http/Middleware/CheckUserAccess.php`
- `laravel-app/app/Models/Tenants/AccountUser.php`
- `laravel-app/routes/api/packages/project_account_api_v1/push_handler.php`
- `laravel-app/tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`
- `laravel-app/tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php`

## Worker Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| Worker tenant token suite | `passed`: `9 passed`. |
| Worker account auth unit suite | `passed`: `4 passed`. |
| Worker `AccountUserController` suite | `passed`: `5 passed`. |
| Worker `EventCrudControllerTest --filter=account_auth_boundary` | `passed`: `1 passed`. |
| Legacy combined account API auth/middleware batch | `blocked`: fixture/harness issues; not classified as product failure by the code worker. |

## Reconciliation Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| `git -C laravel-app show --stat --oneline --no-renames cd0da71806f9459ae8daa4b4821d9ec434c643ac -- <7 RR-AUTH-03 files>` | `passed`: commit exists and touches exactly the documented RR-AUTH-03 Laravel source/test surfaces. |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Accounts/AccountUserControllerTest.php` | `passed`: `19 passed`, `40 assertions`, duration `30.28s`. |
| Post-route-binding-fix serial RR-AUTH-03 targeted suite rerun | `passed`: `19 passed`, `40 assertions`, duration `42.87s`. |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter='account_push_message_data_and_actions_reject|tenant_cross_tenant_data_and_actions_return_not_found'` | `passed`: `3 passed`, `12 assertions`, duration `16.38s`; earlier parallel safe-runner overlap was discarded as harness contamination from concurrent Mongo `dropDatabase` operations. |
| Post-token-ceiling/issuer-fix serial RR-AUTH-03 targeted suite rerun | `passed`: `25 passed`, `55 assertions`, duration `43.77s`. |
| Post-token-ceiling/issuer-fix focused push regression | `passed`: `4 passed`, `16 assertions`, duration `14.42s`. |
| Runtime invariant direct-call regression | `passed`: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php --filter='direct_account_user_create_token|no_current_account_issuance_after_stale_account_context'` -> `3 passed`, `11 assertions`, duration `10.09s`. |
| Post-runtime-invariant focused RR-AUTH-03 suite | `passed`: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` -> `157 passed`, `500 assertions`, duration `141.44s`. |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter=account_auth_boundary` | `passed`: `1 passed`, `5 assertions`, duration `7.81s`. |
| `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| `docker compose exec -T app ./vendor/bin/pint --test <9 changed files>` | `passed`: `9 files`. |
| `git diff --check` | `passed`. |
| `git diff --cached --check` | `passed`. |
| Full Laravel CI-equivalent suite on principal | `passed`: `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` -> `1383 passed`, `6554 assertions`, duration `794.12s`; includes the current dirty Laravel tree with unrelated `RR-AUTH-01` changes present, validating the integrated local Laravel state while RR-AUTH-03 files have no unstaged diff after reconciliation. |

## Documentation Evidence

- `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md` updated from dispatch-prep to implementation checkpoint/reconciliation evidence.
- `foundation_documentation/modules/flutter_client_experience_module.md` promotes the account-bound bearer token contract and adds `FCX-15`.
- `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package.md` records the bounded package, frozen rule set, changed surfaces, evidence, pending audit questions, and residual risks.

## Blockers / Residual Risks

- TODO-local 20260507T1521Z post-resolution critique/security/verification-debt/test-quality/final-review found remaining blockers; correction worker `Poincare` is reconciled in principal for the code/test issues before rerunning audit-floor gates.
- TODO-local 20260507T1552Z post-correction critique is clean, but security/test-quality/verification-debt/final-review still blocked closure. Second correction worker `Averroes` is reconciled in principal; correction ledger: `foundation_documentation/artifacts/post-release-account-token-binding-second-correction-ledger-20260507T1624Z.md`.
- TODO-local 20260507T1624Z fresh audit accepted the test-quality floor and accepted `VDA-002` as resolved by deterministic narrower equivalent, but security required runtime fail-closed enforcement for direct account-scoped `AccountUser::createToken()`. Runtime invariant worker correction is reconciled in principal and recorded in `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-ledger-20260507T1655Z.md`.
- Post-1552Z correction validation: stale ambient request-path regression -> `1 passed`, `5 assertions`, `7.58s`; focused RR-AUTH-03 suite -> `154 passed`, `489 assertions`, `101.75s`; architecture guardrails pass; Pint `10 files`; `git diff --check` and `git diff --cached --check` pass.
- Post-1521Z correction validation: `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php` -> `153 passed`, `484 assertions`, `126.39s`; Pint `6 files`; architecture guardrails pass; `git diff --check` and `git diff --cached --check` pass.
- Full Laravel CI-equivalent suite is passed and recorded for Local-Implemented validation evidence.
- The legacy combined account API auth/middleware batch remains blocked by fixture/harness issues and is not counted as product failure evidence.
- Full-suite attribution remains verification debt because the recorded full suite includes unrelated RR-AUTH-01 dirty state.
- This checkpoint claims RR-AUTH-03 `Local-Implemented` validation evidence only; it does not claim TODO completion, promotion readiness, audit closure, or full RR-AUTH tranche delivery.

## Next Exact Step

Rerun fresh `20260507T1655Z+` TODO-local audit-floor gates. Triple audit and Claude comparison remain blocked until the required audit-floor blockers are resolved or explicitly waived.
