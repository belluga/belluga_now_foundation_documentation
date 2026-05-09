# TODO (Post Release Hardening): Tenant App-Domain Authorization and App-Link Integrity Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The security pass in the architectural drift review found that tenant app-domain admin routes are not protected by the same tenant-access and explicit-ability guardrails used in other tenant-admin/domain-management paths.

Because app-domain identifiers drive Android/iOS app-link trust and web-to-app promotion behavior, this is not a narrow route-style issue. It is a trust-boundary problem across tenant admin mutation, mobile deep-link association, and public promotion flow integrity.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-04`
- **Why this is the right current slice:** this TODO isolates one concrete trust-boundary defect family: tenant app-domain mutation must prove both tenant access and explicit domain-management authorization.

## Contract Boundary
- This TODO owns tenant app-domain read/store/delete authorization semantics.
- It owns the required route middleware, explicit ability contract, and regression coverage.
- It owns the app-link/web-to-app integrity consequences of those controls.
- It does **not** own unrelated environment snapshot optimization or app-domain micro-optimizations.

## Drift Guardrail Requirement
- This TODO belongs to the tenant-boundary / trust-surface drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real drift fixtures from the current route/controller/config path.

## Violated Canonical Rule
- Tenant-domain admin mutation routes must carry the same tenant-access and explicit ability enforcement as equivalent tenant-admin configuration routes.

## Replacement Canonical Rule
- Tenant app-domain routes must require:
  - authenticated tenant-admin context,
  - `CheckTenantAccess` (or equivalent current-tenant proof),
  - and an explicit domain-management ability before read/store/delete mutation succeeds.

## Strongest Objective PACED Guardrail
- Laravel feature tests for:
  - no tenant access,
  - missing ability,
  - allowed read,
  - allowed store,
  - allowed delete,
  - wrong-tenant principal,
  - and app-link payload integrity after authorized mutation.
- Ability catalog sync evidence for the new/confirmed ability string.

## Real Drift Fixtures
- `laravel-app/bootstrap/app.php`
- `laravel-app/routes/api/tenant_api_v1.php`
- `laravel-app/app/Http/Api/v1/Controllers/TenantAppDomainController.php`
- `laravel-app/app/Http/Api/v1/Requests/TenantAppDomainRequest.php`
- Drift-review finding `SEC-DRIFT-001`

## Delivery Status Canon
- **Current delivery stage:** `Implementation and final CI-equivalent reconciled after audit follow-up`
- **Qualifiers:** `Provisional+Audit-Pending`
- **Next exact step:** rerun TODO-local audit-floor follow-up reviews, triple audit, and the Claude fourth-auditor experiment before closure.

## Scope
- [x] Define the explicit current-tenant ability and tenant-access requirements for tenant app-domain routes.
- [x] Harden route registration/middleware accordingly.
- [x] Add regression tests using the current weak route path as fixtures.
- [x] Verify Android and iOS app-link integrity surfaces remain correct after authorized mutation.

## Out of Scope
- [ ] Reworking mobile client resolver logic unrelated to authorization.
- [ ] Environment read-model micro-optimizations.
- [ ] Broad tenant admin IA redesign.

## Definition of Done
- [x] Tenant app-domain routes enforce tenant access and explicit current-tenant domain-management authorization.
- [x] Unauthorized landlord principals cannot read or mutate tenant app-domain identifiers.
- [x] Authorized mutation still preserves expected Android and iOS app-link well-known/runtime behavior.
- [x] Real drift fixtures are covered by regression tests.

## Validation Steps
- [x] Add Laravel regression authz tests for read/store/delete, wrong-tenant access, borrowed cross-tenant ability, and denied-mutation non-occurrence.
- [x] Run targeted app-domain feature/unit suites.
- [x] Run the final Laravel CI-equivalent suite required by the execution plan.
- [x] Revalidate the ability catalog if a new ability string is introduced.

## Local CI-Equivalent Suite Matrix
This TODO is not completion-ready until every in-scope row below has passed on the frozen RR-AUTH-02 Laravel state.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / Targeted appdomain + domain feature suites` | RR-AUTH-02 hardens appdomain and adjacent domain-management authorization behavior. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php` | TODO closure | `passed` | Staged-freeze rerun after WSL reconnect: `31 passed`, `145 assertions`, duration `20.62s`. | Proves appdomain authz, borrowed-token denial, adjacent domain current-tenant role ability coverage, and app-link payload behavior. |
| `laravel-app / Expanded adjacent backend suite` | The slice touches tenant app-domain/domain management plus auth fixture compatibility. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php tests/Unit/Application/Tenants/TenantAppDomainManagementServiceTest.php tests/Unit/Application/Tenants/TenantAppDomainResolverServiceTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Api/v1/Admin/ApiV1AdminAuthTest.php tests/Unit/Application/Auth/LandlordAuthenticationServiceTest.php` | TODO closure | `passed` | `90 passed`, `395 assertions`, duration `48.74s`. | Covers adjacent service/resolver and login-token fixture compatibility. |
| `laravel-app / Laravel CI-equivalent full suite` | RR-AUTH-02 is backend auth hardening and the full local-safe Laravel suite is the repo-owned CI-equivalent confidence gate. | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | TODO closure | `passed` | `1383 passed`, `6552 assertions`, duration `606.44s`. | Final full-suite evidence on the principal checkout. |
| `laravel-app / Architecture guardrails` | The added middleware and route contract must remain architecture-clean. | `docker compose exec -T app php scripts/architecture_guardrails.php` | TODO closure | `passed` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` | Container execution avoids missing host `php`. |
| `laravel-app / Pint changed-file style gate` | The staged RR-AUTH-02 PHP files must remain formatting-clean. | `docker compose exec -T app ./vendor/bin/pint --test routes/api/tenant_api_v1.php tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php app/Http/Middleware/CheckCurrentTenantRoleAbility.php` | TODO closure | `passed` | `PASS`, `4 files`. | Host Pint was unavailable because host `php` is missing; container Pint is the valid local gate. |
| `laravel-app / Diff hygiene` | Promotion set must be coherent and not lose the current-tenant role middleware. | `git diff --cached --check && git diff --check`; staged-set inspection for the four RR-AUTH-02 paths. | TODO closure | `passed` | Diff hygiene passed; staged set is `A app/Http/Middleware/CheckCurrentTenantRoleAbility.php`, `M routes/api/tenant_api_v1.php`, `M tests/Feature/Tenants/TenantAppDomainControllerTest.php`, `M tests/Feature/Tenants/TenantDomainControllerTest.php`; no unstaged diff remains in those paths. | Unrelated RR-AUTH-01 dirty files are intentionally outside this staged set. |

## Completion Evidence Matrix

| ID | Criterion Type | Criterion | Evidence Type | Evidence Artifact / Command | Runtime / Surface | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Define the explicit current-tenant ability and tenant-access requirements for tenant app-domain routes. | Route/contract evidence | Route / Ability Contract section in this TODO; `tenant_admin_module.md` appdomain Auth/Middleware rows; ability catalog sync notes. | Laravel route contract | `passed` | Requires `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update`, and current-tenant role ability `tenant-domains:read|update`. |
| `SCOPE-02` | Scope | Harden route registration/middleware accordingly. | Code and route-list evidence | `routes/api/tenant_api_v1.php`; `app/Http/Middleware/CheckCurrentTenantRoleAbility.php`; `docker compose exec -T app php artisan route:list --path=appdomains -vv`; `docker compose exec -T app php artisan route:list --path=domains -vv`. | Laravel route stack | `passed` | Appdomain and adjacent domain-management routes show the full middleware stack. |
| `SCOPE-03` | Scope | Add regression tests using the current weak route path as fixtures. | Test evidence | `tests/Feature/Tenants/TenantAppDomainControllerTest.php` and `tests/Feature/Tenants/TenantDomainControllerTest.php` exercise route fixtures `/admin/api/v1/appdomains` and `/admin/api/v1/domains`; focused suite rerun `31 passed`, `145 assertions`. | Laravel feature tests | `passed` | Covers route-level unauthenticated, no tenant access, missing ability, wrong tenant, borrowed ability, and non-mutation denial paths. |
| `SCOPE-04` | Scope | Verify Android and iOS app-link integrity surfaces remain correct after authorized mutation. | Command/test evidence | `foundation_documentation/artifacts/post-release-tenant-app-domain-app-link-integrity-evidence-20260507.md` | Public app-link payloads | `passed` | Confirms authorized mutation changes Android/iOS association payloads and denied mutation preserves payloads. |
| `DOD-01` | Definition of Done | Tenant app-domain routes enforce tenant access and explicit current-tenant domain-management authorization. | Route/test evidence | Route-list appdomains output plus borrowed-token and missing-ability denial tests. | Laravel appdomain routes | `passed` | Token ability alone is insufficient; `CheckCurrentTenantRoleAbility` proves ability against `Tenant::current()`. |
| `DOD-02` | Definition of Done | Unauthorized landlord principals cannot read or mutate tenant app-domain identifiers. | Test evidence | `TenantAppDomainControllerTest` no-access, wrong-tenant, missing ability, borrowed-read, borrowed-update, and denied non-mutation tests. | Laravel feature tests | `passed` | Denied paths return `403` or `404` as appropriate and preserve app-domain records/payloads. |
| `DOD-03` | Definition of Done | Authorized mutation still preserves expected Android and iOS app-link well-known/runtime behavior. | Test evidence | `TenantAppDomainControllerTest` authorized Android/iOS store/delete payload coverage. | `/.well-known/assetlinks.json`; `/.well-known/apple-app-site-association` | `passed` | Payloads remain derived from typed app domains plus `settings.app_links`. |
| `DOD-04` | Definition of Done | Real drift fixtures are covered by regression tests. | Test/fixture evidence | Drift fixtures list in this TODO; route and controller tests exercise the formerly weak `/admin/api/v1/appdomains` path. | Laravel route/controller/request path | `passed` | Tests lock the route-level auth gap identified as `SEC-DRIFT-001`. |
| `VAL-01` | Validation Steps | Add Laravel regression authz tests for read/store/delete, wrong-tenant access, borrowed cross-tenant ability, and denied-mutation non-occurrence. | Test evidence | `TenantAppDomainControllerTest.php`; `TenantDomainControllerTest.php`; focused suite `31 passed`, `145 assertions`. | Laravel feature tests | `passed` | Includes read/store/delete, wrong tenant, borrowed read/update, and non-mutation assertions. |
| `VAL-02` | Validation Steps | Run targeted app-domain feature/unit suites. | Command evidence | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php` | Laravel safe runner | `passed` | Staged-freeze rerun after WSL reconnect: `31 passed`, `145 assertions`, duration `20.62s`. |
| `VAL-03` | Validation Steps | Run the final Laravel CI-equivalent suite required by the execution plan. | Command evidence | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | Laravel safe runner | `passed` | Final full suite: `1383 passed`, `6552 assertions`, duration `606.44s`. |
| `VAL-04` | Validation Steps | Revalidate the ability catalog if a new ability string is introduced. | Catalog evidence | `laravel-app/config/abilities.php:27` and `laravel-app/config/abilities.php:28` already define `tenant-domains:read` and `tenant-domains:update`; no new ability string was introduced. | Laravel ability catalog | `passed` | Existing catalog entries are reused for appdomain and adjacent domain-management route authorization. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-security-adversarial`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the code surface is focused, but the route sits on a high-trust cross-stack boundary and needs exact authorization semantics.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/onboarding_flow_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `onboarding_flow_module.md` app-link/app-domain trust contract
  - `tenant_admin_module.md` tenant-domain configuration authorization
- **Module decision consolidation targets (required):**
  - `onboarding_flow_module.md`

## Dependencies & Sequencing
- [x] Coordinate any ability-string addition with current ability catalog authority and tests before implementation starts.

## Implementation Checkpoint Evidence

- **Worker worktree:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.worktrees/rr-auth-02-tenant-app-domain/laravel-app`
- **Worker branch:** `worker/rr-auth-02-tenant-app-domain-20260507`
- **Worker commits:** `38daeeb46c02396e8fb2da91e523515951fc0179`, `91a3740569c0c4c5b556262c33f9d443ed4f45ac`, `09deccefcc55eef73b26a895a0ae762e7f2fc152`, `f00f63d7876d281c5621ce16cd9ef5a08c3f8e25`
- **Package:** `foundation_documentation/artifacts/post-release-tenant-app-domain-authorization-hardening-package.md`
- **Worker checkpoint:** `foundation_documentation/artifacts/checkpoints/post-release-rule-related-auth-identity-rr-auth-02-worker-checkpoint-2026-05-07.md`
- **Checkpoint status:** `reconciled_principal_checkpoint`

### Frontend / Consumer Matrix

| Producer Surface | Consumer Status | Evidence / Waiver |
| --- | --- | --- |
| `GET|POST|DELETE /admin/api/v1/appdomains` | `consumer implemented + evidenced` | Existing tenant-admin app-domain/settings consumer contract remains unchanged because response/request schemas are preserved. This TODO changes authorization only; API feature tests prove authenticated login-token read, authorized mutation payloads, denial semantics, and route/domain scoping. |
| `/.well-known/assetlinks.json` | `consumer implemented + evidenced` | External Android App Links consumer continues to receive payloads derived from typed app domains plus `settings.app_links`; feature tests assert authorized Android store/delete updates the payload and denied mutations preserve it. |
| `/.well-known/apple-app-site-association` | `consumer implemented + evidenced` | External iOS Universal Links consumer continues to receive payloads derived from typed app domains plus `settings.app_links`; feature tests assert authorized iOS store/delete updates the payload and denied iOS store/delete preserve the Apple association payload. |
| Flutter/mobile resolver or tenant-admin UI source changes | `consumer intentionally absent + approved waiver` | Approved waiver comes from this TODO's out-of-scope boundary: reworking mobile resolver logic and broad tenant-admin IA redesign are excluded because the producer schema is unchanged and the slice is authorization hardening. |

### Security Blocker Resolution

- `SEC-RR-AUTH-02-001` was returned to the worker and resolved in commit `09deccefcc55eef73b26a895a0ae762e7f2fc152`.
- Finding: the current route stack checks tenant membership plus Sanctum token ability, but landlord login unions tenant-role permissions from all tenants into the token. A user with `tenant-domains:update` on Tenant A and any membership on Tenant B could present a token carrying the borrowed ability to mutate Tenant B.
- Replacement: `app/Http/Middleware/CheckCurrentTenantRoleAbility.php` now proves the requested ability is present on the landlord principal's role for `Tenant::current()` at request time. Token ability alone is insufficient for tenant-admin authorization.
- Regression: a principal with update ability on another tenant but only read ability on the current tenant receives `403` for store/delete, and the target tenant app-domain records plus well-known payloads remain unchanged.
- Related test-quality blockers were closed: denied store/delete/wrong-tenant paths assert non-mutation, the test helper fails closed when canonical landlord password credential sync is unavailable, and iOS `apple-app-site-association` mutation integrity is covered alongside Android `assetlinks.json`.
- Audit follow-up commit `f00f63d7876d281c5621ce16cd9ef5a08c3f8e25` closes the remaining reviewer gaps by adding borrowed-read denial coverage, denied iOS Apple association non-mutation coverage, authorized tenant-admin login-token store/delete coverage, and current-tenant role ability checks for adjacent `/admin/api/v1/domains` routes.
- Least-privilege classification: `tenant-domains:update` intentionally owns app-domain and app-link trust mutation for this launch contract. No narrower app-link ability was introduced in this slice; if role design later needs separation between ordinary web-domain mutation and mobile app-link trust mutation, that is a new ability-catalog/domain-policy decision rather than an RR-AUTH-02 closure blocker.

### Route / Ability Contract

- `GET /admin/api/v1/appdomains` must require `auth:sanctum`, `CheckTenantAccess`, and current-tenant role ability `tenant-domains:read`.
- `POST /admin/api/v1/appdomains` must require `auth:sanctum`, `CheckTenantAccess`, and current-tenant role ability `tenant-domains:update`.
- `DELETE /admin/api/v1/appdomains` must require `auth:sanctum`, `CheckTenantAccess`, and current-tenant role ability `tenant-domains:update`.
- Adjacent `/admin/api/v1/domains` read/store/delete/restore/force-delete routes now carry the same current-tenant role ability check to close the borrowed-token same-ability risk identified by the audit loop.
- Ability catalog sync evidence: the route uses existing `tenant-domains:read` and `tenant-domains:update` entries in `laravel-app/config/abilities.php`; no new ability string was introduced.

### Reconciliation Validation

- Promotable staged set freeze after WSL reconnect:
  - `git diff --name-only -- app/Http/Middleware/CheckCurrentTenantRoleAbility.php routes/api/tenant_api_v1.php tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php`
  - Result: no unstaged diff remains in the four RR-AUTH-02 Laravel paths.
  - `git diff --cached --name-status -- app/Http/Middleware/CheckCurrentTenantRoleAbility.php routes/api/tenant_api_v1.php tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php`
  - Result: `A app/Http/Middleware/CheckCurrentTenantRoleAbility.php`, `M routes/api/tenant_api_v1.php`, `M tests/Feature/Tenants/TenantAppDomainControllerTest.php`, `M tests/Feature/Tenants/TenantDomainControllerTest.php`.
- Targeted principal suite:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php`
  - Result: `31 passed`, `145 assertions`, duration `21.80s`.
- Targeted principal suite rerun after staged freeze and WSL reconnect:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php`
  - Result: `31 passed`, `145 assertions`, duration `20.62s`.
- Expanded adjacent principal suite:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php tests/Unit/Application/Tenants/TenantAppDomainManagementServiceTest.php tests/Unit/Application/Tenants/TenantAppDomainResolverServiceTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Api/v1/Admin/ApiV1AdminAuthTest.php tests/Unit/Application/Auth/LandlordAuthenticationServiceTest.php`
  - Result: `90 passed`, `395 assertions`, duration `48.74s`.
- Final Laravel CI-equivalent suite:
  - `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings`
  - Result: `1383 passed`, `6552 assertions`, duration `606.44s`.
- Route matrix:
  - `docker compose exec -T app php artisan route:list --path=appdomains -vv`
  - Result: all three tenant app-domain routes include `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update`, and `CheckCurrentTenantRoleAbility:tenant-domains:read|update`.
  - `docker compose exec -T app php artisan route:list --path=domains -vv`
  - Result: appdomain routes plus five tenant domain-management routes include `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update`, and `CheckCurrentTenantRoleAbility:tenant-domains:read|update`.
- Architecture guardrails:
  - `docker compose exec -T app php scripts/architecture_guardrails.php`
  - Result: `[ARCH-GUARDRAILS] PASS - no architecture violations found.`
- Pint:
  - Host `vendor/bin/pint` was blocked because host `php` is unavailable.
  - Container command `docker compose exec -T app ./vendor/bin/pint --test routes/api/tenant_api_v1.php tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php app/Http/Middleware/CheckCurrentTenantRoleAbility.php` passed for `4 files`.
- Diff hygiene:
  - `git diff --cached --check` and `git diff --check` passed.

### Guardrail Audit Classification

- `bash delphi-ai/tools/laravel_tenant_access_guardrails_audit.sh --path laravel-app/routes/api/tenant_api_v1.php` still exits `2`.
- Classification: out-of-scope file-level blocker for RR-AUTH-02, because the tool flags existing authenticated identity routes in the same file without `CheckTenantAccess`.
- Residual identity-route debt includes `auth/logout`, `auth/token_validate`, and `/me`, not only `auth/*`.
- RR-AUTH-02 app-domain route compliance is proven by the path-specific `route:list --path=appdomains -vv` output and the focused feature tests. The adjacent `/domains` borrowed-token same-ability risk identified by critique review was resolved in the same current-tenant role-ability pattern and covered by `TenantDomainControllerTest`.
- Follow-up authority: `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-architectural-rule-drift-review.md` remains the route-matrix/backlog authority for non-appdomain authenticated identity-route classification. RR-AUTH-02 must not claim global tenant-route compliance from this checkpoint.

### Audit Finding Resolution Ledger

| Finding | Resolution | Evidence / Rationale |
| --- | --- | --- |
| `RR-AUTH-02-CRIT-001` / `FR-RR-AUTH-02-1217-001` / `TQA-RR-AUTH-02-1217-001` | Integrated | Promotable staged set is frozen to the four RR-AUTH-02 Laravel paths, and those paths have no unstaged diff after WSL reconnect. |
| `RR-AUTH-02-CRIT-002` / `SEC-RR-AUTH-02-NC-001` | Integrated | Focused principal suite was rerun after staged freeze and WSL reconnect: `31 passed`, `145 assertions`, duration `20.62s`. The earlier MongoDB drop/index failure is not present in the current closure evidence. |
| `VD-RR-AUTH-02-001` | Integrated | `tenant_admin_module.md` and `onboarding_flow_module.md` now state that local implementation and final CI-equivalent validation are reconciled while audit gates remain pending. |
| `VD-RR-AUTH-02-002` / `SEC-RR-AUTH-02-NC-004` / `FR-RR-AUTH-02-1217-003` | Integrated for RR-AUTH-02, deferred for global route compliance | RR-AUTH-02 is explicitly scoped to appdomain and adjacent domain-management routes. The owning route-drift TODO now records the residual identity-route list: `auth/logout`, `auth/token_validate`, and `/me`. The file-level tenant-access guard still exits `2` and must not be reported as globally passed. |
| `SEC-RR-AUTH-02-NC-002` | Integrated | Appdomain endpoint Auth/Middleware rows in `tenant_admin_module.md` explicitly list `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update` abilities, and current-tenant role ability. |
| `SEC-RR-AUTH-02-NC-003` | Accepted launch risk | `tenant-domains:update` intentionally owns typed app-domain and app-link trust mutation for the current launch contract. Splitting mobile app-link trust mutation from ordinary tenant-domain mutation is a future ability-catalog/domain-policy decision, not an RR-AUTH-02 closure blocker. |
| `TQA-RR-AUTH-02-1217-002` | Accepted bounded adjacent-route risk | RR-AUTH-02 primarily owns appdomain authorization. Adjacent `/domains` restore and force-delete are covered by route-list proof showing `CheckCurrentTenantRoleAbility`, while behavior-level borrowed-token denial exists for adjacent `/domains` read/store/delete. If the triple-audit test-quality lane rejects route-list proof for these adjacent restore/force-delete paths, the fix must be delegated to a worker subagent before closure. |
| `FR-RR-AUTH-02-1217-002` | In progress | 12:17Z review results are now merged. Triple audit and Claude fourth-auditor comparison remain open closure gates. |

### Triple Audit Convergence

- **Session:** `foundation_documentation/artifacts/post-release-tenant-app-domain-authorization-hardening-package-triple-audit-20260507T1217Z/session.json`
- **Round 01:** `needs_adjudication`, resolved because all lanes were clean and the only conflict was non-material recommended-path wording.
- **Round 02:** `needs_adjudication`, resolved for the same non-material recommended-path wording conflict.
- **Round 03:** `clean`.
- **Round 03 lane result:** Elegance clean, Performance clean, Test Quality clean, each with `findings: []` and `recommended_path: proceed`.
- **Conclusion:** Triple audit converged with no blocking findings and no accepted non-blocking triple-audit debt.

### Claude Fourth-Auditor Comparison

- **Comparison record:** `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-02-tenant-app-domain-claude-comparison-20260507T1252Z.md`
- **Valid review artifact:** `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-02-tenant-app-domain-claude-review-20260507T1252Z.json`
- **Result:** `clean_with_accepted_debt`.
- **Blocking findings:** none.
- **Alignment:** Claude agrees with the triple-audit Round 03 `clean` result.
- **Accepted non-blocking debt:** bounded package source-format limitation, documented `tenant-domains:update` semantic compression, and residual identity-route guardrail debt already delegated to the architectural rule-drift TODO.
- **Invalid run note:** `RR-AUTH-02-tenant-app-domain-claude-review-20260507T1245Z.json` is retained only as invalid experiment metadata because it was run without filesystem tools or embedded package contents and reviewed the wrong surface.

### Final Deterministic Guard Classification

- **TODO completion guard:** `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/post_release_hardening/TODO-post-release-tenant-app-domain-authorization-and-app-link-integrity-hardening.md --require-delivery --json-output foundation_documentation/artifacts/post-release-tenant-app-domain-todo-completion-guard-20260507T1252Z.json`
- **Result:** `Overall outcome: go`.
- **Delivery guard for full orchestration plan:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/post-release-rule-related-auth-identity-orchestration-plan.md --require-approved --json-output foundation_documentation/artifacts/post-release-rule-related-auth-identity-delivery-guard-20260507T1252Z.json`
- **Result:** `Overall outcome: no-go`.
- **Classification:** expected global-plan blocker, not an RR-AUTH-02 blocker. The plan guard still sees RR-AUTH-01, RR-AUTH-03, RR-AUTH-04, integrated validation areas, and runtime freshness rows as planned or unproven. RR-AUTH-02 local completion evidence is now guard-clean.

## Remaining Closure Gates

- [x] TODO-local critique review.
- [x] TODO-local security adversarial review.
- [x] TODO-local verification-debt audit.
- [x] TODO-local test-quality audit.
- [x] TODO-local independent final review.
- [x] Triple audit loop to convergence.
- [x] Bounded `Claude CLI` fourth-auditor comparison record.
- [x] Final Laravel CI-equivalent suite required by the orchestration plan.
