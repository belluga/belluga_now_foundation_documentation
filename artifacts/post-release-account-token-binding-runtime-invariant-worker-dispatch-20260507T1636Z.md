# RR-AUTH-03 Runtime Invariant Worker Dispatch - 20260507T1636Z

## Dispatch Identity
- **Artifact kind:** `worker_dispatch`
- **Authoritative:** `false`
- **Edit policy:** `orchestrator_owned_dispatch_packet`
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Bounded package:** `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package.md`
- **Source audit pass:** `20260507T1624Z`
- **Implementation owner:** worker subagent
- **Orchestrator owner:** Delphi principal reconciliation checkout

## Worker Rules
- You are not alone in the codebase. Do not revert, restage, or normalize unrelated edits.
- Do not touch RR-AUTH-01 dirty files or landlord password credential backfill surfaces.
- Do not use Codex CLI or `codex exec`.
- Claude CLI is not part of implementation. It is reserved for later fourth-auditor comparison by the orchestrator.
- Implement code/test fixes only. The orchestrator owns TODO/package/checkpoint updates and audit-result merge.
- Keep changes inside the allowed write set unless an objective blocker requires widening; if widening is needed, report the reason instead of editing unrelated files.

## Current Blocking Findings
| Finding | Severity | Worker Resolution Target |
| --- | --- | --- |
| `RR-AUTH-03-SEC-001` | `high` | Make direct `AccountUser::createToken()` fail closed for account-scoped abilities unless called through a validated issuer context. Add a negative regression proving direct production-path token creation with account-scoped abilities and no validated account context throws and leaves no usable unbound account token. |
| `RR-AUTH-03-SEC-002` | `high` | Strengthen or expose deterministic account-prefixed route/ability-resource inventory evidence. If the existing architecture guardrail is enough, add focused test/guard evidence that would fail on missing `account` middleware or missing `ACCOUNT_SCOPED_ABILITY_RESOURCES` entry. |
| `RR-AUTH-03-SEC-003` | `medium` | Add or identify sequential stale `Account::current()` coverage: account A context followed by no-current-account issuance and account B route access must not produce wrong-account binding or false authorization. If existing tests already cover the invariant, add a focused regression name/assertion that makes it explicit. |
| `RR-AUTH-03-SEC-004` | `medium` | Ensure closure evidence is test-backed for role downgrade, membership removal, wrong-account same ability, and read/write asymmetry. Add missing focused regressions if they are not already present. |

## Allowed Write Set
- `laravel-app/app/Models/Tenants/AccountUser.php`
- `laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php`
- `laravel-app/app/Http/Middleware/CheckUserAccess.php`
- `laravel-app/scripts/architecture_guardrails.php`
- `laravel-app/tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`
- `laravel-app/tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php`
- Existing Laravel architecture or guardrail test files only if required for validating the guardrail change.

## Implementation Guidance
- A likely acceptable pattern is overriding `AccountUser::createToken()` while aliasing Sanctum's trait method, then requiring a private context marker set only by `TenantScopedAccessTokenService` when issuing account-scoped abilities. This is guidance, not a mandate; choose the smallest robust runtime invariant.
- Preserve legitimate tenant-only or anonymous/account-user token creation that does not request account-scoped abilities.
- Preserve `TenantScopedAccessTokenService::issueForAccountUser()` legitimate account-scoped token issuance after it has resolved tenant and account context.
- Do not weaken wildcard handling: literal `*` and account resource wildcards must still be account-scoped.

## Required Local Validation
Run from `laravel-app` unless a command explicitly says otherwise.

1. `bash ../delphi-ai/verify_context.sh`
2. Focused safe-runner tests covering changed tests.
3. `docker compose exec -T app php scripts/architecture_guardrails.php`
4. `docker compose exec -T app ./vendor/bin/pint --test <changed php files>`
5. `git diff --check`
6. `git diff --cached --check`

## Worker Output Contract
Return:
- changed file paths;
- exact blocker IDs resolved;
- exact validation commands and outcomes;
- any residual risk you could not close inside the allowed write set;
- confirmation that unrelated RR-AUTH-01 files were not touched.
