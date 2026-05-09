# RR-AUTH-03 Clean Bounded Baseline Rerun Ledger - 20260507T194907Z

## Scope

- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Source blocker:** `RR-AUTH-03-CRIT-002` / `VDA-005` / `RR-AUTH-03-FR-002` full-suite clean attribution.
- **Clean validation tree:** `/home/elton/Dev/repos/belluga-ecosystem/worktrees/rr-auth-03-clean-env-20260507T182803Z/laravel-app`
- **Included-file manifest:** `/home/elton/Dev/repos/belluga-ecosystem/worktrees/rr-auth-03-clean-env-20260507T182803Z/RR_AUTH_03_INCLUDED_FILES.txt`
- **Legacy test/harness reconciliation:** `Kant` reconciled the first-pass legacy test updates in `ApiV1TenantMeTest.php`, `ExternalImageProxyTest.php`, and `ApiV1AccountsMiddlewareTestContract.php`; the orchestrator then finalized the middleware harness so the bounded RR-AUTH-03 baseline stays compatible with both the clean validation tree and the unrelated dirty RR-AUTH-01 principal tree.
- **Closure posture:** clean bounded full-suite attribution is now recorded. Fresh TODO-local audit-floor reruns are still required before triple audit or the Claude fourth-auditor comparison.

## Reconciliation Summary

| Surface | Status | Evidence |
| --- | --- | --- |
| `tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | `corrected_for_validated_issuer_path` | Legacy direct `AccountUser::createToken()` usage was replaced with `TenantScopedAccessTokenService::issueForAccountUser(..., [])` so tenant `/me` and `/profile*` auth coverage uses the same validated issuer path enforced by RR-AUTH-03. |
| `tests/Api/v1/Tenants/Media/ExternalImageProxyTest.php` | `corrected_for_real_account-bound_token_rejection` | The test now creates a real account-bound user/account pair and issues a token through `TenantScopedAccessTokenService`, proving tenant-admin media auth still rejects an account-bound token even when it carries account abilities. |
| `tests/Api/v1/Accounts/Middleware/Contracts/ApiV1AccountsMiddlewareTestContract.php` | `corrected_for_clean-baseline parity` | Tenant-user/account-user seeding now works in the clean RR-AUTH-03 tree and in the dirty principal tree: landlord credentials are synchronized through the credential store, legacy password columns are backfilled for the older auth path, and leaked `Account::current()` state is cleared after fixture setup. |

## Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| `docker compose exec -T app php scripts/architecture_guardrails.php` in the clean validation tree | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Accounts/Middleware/T1A1Test.php tests/Api/v1/Accounts/Middleware/T1A2Test.php tests/Api/v1/Accounts/Middleware/T2A1Test.php tests/Api/v1/Accounts/Middleware/T2A2Test.php` in the clean validation tree | `passed`: `56 passed`, `112 assertions`, `14.57s`. |
| `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` in the clean validation tree | `passed`: `1368 passed`, `6373 assertions`, `712.78s`. |

## Diagnostic History

- The first clean full-suite rerun surfaced real legacy test/harness debt: `ApiV1TenantMeTest` and `ExternalImageProxyTest` still relied on direct account-user token issuance semantics that RR-AUTH-03 now correctly rejects without validated issuer context, and `ApiV1AccountsMiddlewareTestContract` had order-sensitive auth fixture state.
- A second rerun proved the first middleware harness patch had accidentally coupled itself to unrelated RR-AUTH-01 helper changes in the dirty principal tree. The final harness was reduced to dual-compatible fixture setup instead of depending on the newer landlord helper method.
- The final clean bounded rerun passed end-to-end, so RR-AUTH-03 now has bounded full-suite attribution evidence rather than integrated-tree-only evidence for the CI-equivalent lane.

## Raw Log References

- Clean architecture guardrails log: `/tmp/rr-auth-03-clean-architecture-20260507T184625Z.log`
- Clean bounded full-suite rerun log: `/tmp/rr-auth-03-clean-full-suite-rerun-20260507T194907Z.log`

## Next Gate

Rerun fresh TODO-local audit-floor packets for critique, security, verification-debt, test-quality, and final review from the `20260507T194907Z` clean bounded baseline. Do not run triple audit or the Claude fourth-auditor comparison until that fresh audit pass accepts this rerun evidence or records accepted debt.
