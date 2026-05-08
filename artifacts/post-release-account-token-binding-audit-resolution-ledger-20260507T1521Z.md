# Post-Release Account Token Binding Audit Resolution Ledger

## Artifact Identity

- **Artifact type:** `audit_resolution_ledger`
- **Execution slice:** `RR-AUTH-03`
- **Authoritative:** `false`
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Source audit pass:** `20260507T1424Z`
- **Resolution state:** `code_test_resolution_staged_pending_fresh_audit`

## Source Audit Artifacts

- `foundation_documentation/artifacts/post-release-account-token-binding-critique-merge-20260507T1424Z.md`
- `foundation_documentation/artifacts/post-release-account-token-binding-security-merge-20260507T1424Z.md`
- `foundation_documentation/artifacts/post-release-account-token-binding-verification-debt-merge-20260507T1424Z.md`
- `foundation_documentation/artifacts/post-release-account-token-binding-test-quality-merge-20260507T1424Z.md`
- `foundation_documentation/artifacts/post-release-account-token-binding-final-review-merge-20260507T1424Z.md`

## Staged Resolution Summary

- `AccountUser::tokenCan()` now evaluates the Sanctum ceiling with account-workspace wildcard semantics instead of relying only on exact `PersonalAccessToken::can()` behavior.
- `AccountUserAccessService` centralizes wildcard semantics for literal `*`, exact abilities, and `<resource>:*`, while normalizing account IDs and using strict comparisons.
- `TenantScopedAccessTokenService` fails closed when account-scoped abilities or literal `*` require an account binding and no explicit/current/single-access account can be resolved.
- `AccountAuthenticationService` no longer binds no-current-account login tokens to an arbitrary first account; a no-current-account fallback is allowed only for a single accessible account.
- Regression tests now cover resource wildcard and literal wildcard bearer-token behavior, fail-closed account-scoped issuance, push `data/actions` account-route binding, and next-request role downgrade denial.

## Resolution Table

| Finding | Source | Severity | Resolution Classification | Evidence |
| --- | --- | --- | --- | --- |
| `CRIT-001` | critique | `high` | `resolved_pending_fresh_audit` | Wildcard-aware ceiling semantics and account/event/push wildcard tests are staged. |
| `CRIT-002` | critique | `medium` | `partially_resolved_pending_fresh_audit` | Arbitrary first-access fallback removed; single-access fallback remains intentional pending reviewer acceptance. |
| `SEC-RR-AUTH-03-001` | security | `medium` | `resolved_pending_fresh_audit` | Account-scoped token issuance throws without resolvable account context. |
| `SEC-RR-AUTH-03-002` | security | `low` | `resolved_pending_fresh_audit` | Membership and role-account comparisons normalize IDs and use strict comparison / `hash_equals`. |
| `VDA-001` | verification-debt | `high` | `open` | Required audit-floor, triple audit, and Claude comparison remain pending. |
| `VDA-002` | verification-debt | `high` | `open_verification_debt` | Legacy combined account API auth/middleware batch remains blocked until harness repair, narrower equivalent, or approval waiver. |
| `VDA-003` | verification-debt | `medium` | `resolved_pending_fresh_audit` | Unbound account-scoped issuer path now fails closed; no-current-account fallback is constrained to single-access account. |
| `VDA-004` | verification-debt | `medium` | `resolved_pending_fresh_audit` | Role-downgrade regression proves the next account-scoped request is denied after live permission removal. |
| `VDA-005` | verification-debt | `medium` | `open_verification_debt` | Full-suite evidence still includes unrelated RR-AUTH-01 dirty state; closure needs clean bounded rerun, scope record, or waiver. |
| `TQA-RR-AUTH-03-001` | test-quality | `low` | `resolved_pending_fresh_audit` | Literal next-request role downgrade sequence added. |
| `FR-001` | final-review | `high` | `resolved_pending_fresh_audit` | Token ceiling no longer rejects valid account-workspace resource wildcard roles before live account-role revalidation. |

## Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Accounts/AccountUserControllerTest.php` | `passed`: `25 passed`, `55 assertions`, `43.77s`. |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter='account_push_message_data_and_actions|tenant_cross_tenant_data_and_actions_return_not_found'` | `passed`: `4 passed`, `16 assertions`, `14.42s`. |
| `docker compose exec -T app ./vendor/bin/pint --test <9 changed files>` | `passed`: `PASS`, `9 files`. |
| `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| `git diff --check` | `passed`. |
| `git diff --cached --check` | `passed`. |

## Next Exact Step

Run fresh post-resolution RR-AUTH-03 TODO-local audit-floor gates from the refreshed hardening package. Do not treat this ledger as closure evidence by itself.
