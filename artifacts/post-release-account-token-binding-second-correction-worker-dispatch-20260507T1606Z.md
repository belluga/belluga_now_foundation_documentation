# RR-AUTH-03 Second Correction Worker Dispatch - 20260507T1606Z

## Dispatch Identity
- **Artifact kind:** `worker_dispatch`
- **Authoritative:** `false`
- **Edit policy:** `orchestrator_owned_dispatch_packet`
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Bounded package:** `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package.md`
- **Source audit pass:** `20260507T1552Z`
- **Implementation owner:** `Averroes` worker subagent (`019e0333-b583-7822-80d1-eab6c264aee4`)
- **Orchestrator owner:** Delphi principal reconciliation checkout

## Worker Rules
- You are not alone in the codebase. Do not revert, restage, or normalize unrelated edits.
- Do not touch RR-AUTH-01 dirty files or landlord password credential backfill surfaces.
- Do not use Codex CLI or `codex exec`.
- Claude CLI is not part of implementation. It is reserved for later fourth-auditor comparison by the orchestrator.
- Implement code/test fixes only. The orchestrator owns TODO/package/checkpoint updates and audit-result merge.
- Keep changes inside the allowed write set unless you find an objective blocker; if you need to widen, report the reason instead of editing unrelated files.

## Current Blocking Findings
| Finding | Severity | Worker Resolution Target |
| --- | --- | --- |
| `RR-AUTH-03-SEC-POST-001` | `medium` | Remove the public fail-open `TenantScopedAccessTokenService::stampAccountId()` escape hatch. Make the stamp path private to guarded issuance, or replace it with a fail-closed public API that cannot leave an account-scoped token unbound. |
| `FR-RR-AUTH-03-POST-003` | `medium` | Add objective issuer-discipline evidence or guardrail proving production account-scoped `AccountUser` token issuance cannot bypass `TenantScopedAccessTokenService`. A project architecture guard is acceptable if it scans non-test code and fails on direct `AccountUser::createToken()` account-scoped issuer paths. |
| `TQA-RR-AUTH-03-POST-004` / `VDA-POST-003` | `medium` | Add a deterministic request-path regression for stale ambient `Account::current()` outside `account` middleware context. The test should issue an Account A-bound persisted token, set ambient current account to inaccessible Account B without account middleware context, exercise a tenant-public/non-account route that depends on `tokenCan()`, and assert the request still succeeds with a meaningful payload. |
| `RR-AUTH-03-SEC-POST-002` | `low` | Formalize a guardrail or test that fails when account-prefixed route ability resources drift from the token-binding resource catalog. If infeasible inside this worker slice, report the exact reason and residual risk. |
| `VDA-002` / `FR-RR-AUTH-03-POST-001` | `high` | Prefer adding a deterministic narrower equivalent for the legacy account auth/middleware batch: route/middleware or guardrail evidence that account-prefixed routes using account context require `account` middleware and that account-scoped route ability resources remain covered by token binding. If the legacy batch itself is practical to repair, do that instead. |
| `VDA-005` / `FR-RR-AUTH-03-POST-002` | `medium` | Do not solve by reverting unrelated dirty files. Provide any worker-local evidence needed for focused validation; orchestrator will decide clean-baseline or waiver handling after code blockers are resolved. |

## Allowed Write Set
- `laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php`
- `laravel-app/app/Models/Tenants/AccountUser.php`
- `laravel-app/scripts/architecture_guardrails.php`
- `laravel-app/tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`
- `laravel-app/tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php`
- Existing Laravel architecture or guardrail test files only if needed to validate the guardrail change.

## Required Local Validation
Run from `laravel-app` unless a command explicitly says otherwise.

1. `bash ../delphi-ai/verify_context.sh`
2. Focused safe-runner tests covering changed Feature/Unit tests.
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
