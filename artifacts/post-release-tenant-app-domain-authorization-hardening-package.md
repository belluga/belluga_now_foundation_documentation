# Post-Release Tenant App-Domain Authorization Hardening Package

## Package Identity

- **Package:** `post-release-tenant-app-domain-authorization-hardening`
- **Execution slice:** `RR-AUTH-02`
- **Scope:** tenant-admin app-domain route authorization and app-link integrity preservation
- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-tenant-app-domain-authorization-and-app-link-integrity-hardening.md`
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Orchestration status:** `implementation-and-final-ci-reconciled-after-audit-follow-up`

## Orchestration Binding

- Implementation belongs to the `Tenant app-domain worker subagent` under the approved subagent-worktree reconciliation plan.
- The orchestrator reconciles the worker checkpoint, validates the principal checkout, merges evidence, and runs the required audit gates.
- `Claude CLI` remains only the approved fourth-auditor comparison experiment. It has no implementation authority and cannot replace subagent/triple-audit reviewers.
- This package is derived and non-authoritative. The TODO remains the governing contract.

## Real Drift Fixture

- `laravel-app/routes/api/tenant_api_v1.php` previously registered `GET|POST|DELETE /admin/api/v1/appdomains` without `auth:sanctum`, `CheckTenantAccess`, or explicit domain-management abilities.
- `TenantAppDomainController` resolved the active tenant and allowed app-domain read/mutation behavior based on route context, not on tenant membership plus explicit ability.
- App-domain identifiers feed Android/iOS app-link trust payloads, so unauthorized mutation could affect public `.well-known` association output and web-to-app promotion behavior.

## Frozen Rule Set

### Violated Rule

- Tenant app-domain admin routes were weaker than equivalent tenant-domain management routes, despite owning a tenant trust-boundary surface.

### Replacement Canonical Rule

- `GET /admin/api/v1/appdomains` requires `auth:sanctum`, `CheckTenantAccess`, Sanctum ability `tenant-domains:read`, and current-tenant role ability `tenant-domains:read`.
- `POST /admin/api/v1/appdomains` requires `auth:sanctum`, `CheckTenantAccess`, Sanctum ability `tenant-domains:update`, and current-tenant role ability `tenant-domains:update`.
- `DELETE /admin/api/v1/appdomains` requires `auth:sanctum`, `CheckTenantAccess`, Sanctum ability `tenant-domains:update`, and current-tenant role ability `tenant-domains:update`.
- Adjacent `/admin/api/v1/domains` read/store/delete/restore/force-delete routes require the same current-tenant role ability check to close the borrowed-token same-ability risk found during audit follow-up.
- The ability strings reuse the existing ability catalog entries in `laravel-app/config/abilities.php`; no new ability string is introduced.
- `tenant-domains:update` intentionally owns app-domain and app-link trust mutation for the current launch contract; a narrower future app-link ability would require a new ability-catalog/domain-policy decision.
- Authorized mutation must preserve the current app-link payload derivation from typed app domains plus `settings.app_links`.

## Changed Surfaces

### Laravel Source

- `laravel-app/app/Http/Middleware/CheckCurrentTenantRoleAbility.php`
- `laravel-app/routes/api/tenant_api_v1.php`

### Laravel Tests

- `laravel-app/tests/Feature/Tenants/TenantAppDomainControllerTest.php`
- `laravel-app/tests/Feature/Tenants/TenantDomainControllerTest.php`

### Documentation

- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- governing TODO above
- this package and worker checkpoint manifest

## Frontend / Consumer Matrix

| Producer Surface | Consumer Status | Evidence / Waiver |
| --- | --- | --- |
| `GET|POST|DELETE /admin/api/v1/appdomains` | `consumer implemented + evidenced` | Existing tenant-admin app-domain/settings consumer contract remains unchanged because response/request schemas are preserved. This TODO changes authorization only; API feature tests prove authenticated login-token read, authorized mutation payloads, denial semantics, and route/domain scoping. |
| `/.well-known/assetlinks.json` | `consumer implemented + evidenced` | External Android App Links consumer continues to receive payloads derived from typed app domains plus `settings.app_links`; feature tests assert authorized Android store/delete updates the payload and denied mutations preserve it. |
| `/.well-known/apple-app-site-association` | `consumer implemented + evidenced` | External iOS Universal Links consumer continues to receive payloads derived from typed app domains plus `settings.app_links`; feature tests assert authorized iOS store/delete updates the payload and denied iOS store/delete preserve the Apple association payload. |
| Flutter/mobile resolver or tenant-admin UI source changes | `consumer intentionally absent + approved waiver` | Approved waiver comes from the governing TODO's out-of-scope boundary: reworking mobile resolver logic and broad tenant-admin IA redesign are excluded because the producer schema is unchanged and the slice is authorization hardening. |

## Worker Checkpoint Evidence

- Worker worktree: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.worktrees/rr-auth-02-tenant-app-domain/laravel-app`
- Worker branch: `worker/rr-auth-02-tenant-app-domain-20260507`
- Worker commits:
  - `38daeeb46c02396e8fb2da91e523515951fc0179` hardens app-domain route middleware and expands authz/app-link tests.
  - `91a3740569c0c4c5b556262c33f9d443ed4f45ac` aligns the login-flow fixture with RR-AUTH-01 canonical password credentials.
  - `09deccefcc55eef73b26a895a0ae762e7f2fc152` binds app-domain abilities to the current tenant role, adds borrowed-token denial, denied non-mutation assertions, iOS app-link integrity coverage, and removes the legacy password fallback test shim.
  - `f00f63d7876d281c5621ce16cd9ef5a08c3f8e25` closes audit follow-up gaps by adding borrowed-read denial, denied Apple association non-mutation, tenant-admin login-token mutation coverage, and adjacent `/domains` current-tenant role ability checks.
- Worker validation:
  - worker-mounted app-domain test after audit follow-up: `17 passed`, `88 assertions`
  - worker-mounted adjacent tenant-domain suite after audit follow-up: `14 passed`, `57 assertions`
  - adjacent admin auth feature suite: `15 passed`, `61 assertions`
  - landlord auth unit suite: `4 passed`, `9 assertions`
  - worker-mounted route-list proof: `GET|POST|DELETE {tenant_domain}/admin/api/v1/appdomains` and `/domains` read/mutation routes include `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update`, and `CheckCurrentTenantRoleAbility:tenant-domains:read|update`
  - worker-mounted architecture guardrails: `[ARCH-GUARDRAILS] PASS`
  - worker-mounted Pint equivalent after audit follow-up: `PASS`, `4 files`
  - `git diff --check`: passed
- Worker blockers / environment notes:
  - `./scripts/delphi/run_laravel_tests_safe.sh` was absent from the worker checkout.
  - Docker compose commands bind the principal checkout, not the isolated worker worktree, so explicit worker-mount Docker commands were used for worker-local evidence.
  - The literal `docker compose run --user` command hit an entrypoint `groupmod` permission issue; the worker used `--entrypoint ""`, host UID/GID, and explicit `vendor`/`.env` mounts to validate the worker checkout without testing the principal checkout by mistake.

## Reconciliation Evidence

- Principal integration used the initial accepted worker commits with `git cherry-pick --no-commit`; the security follow-up commit was reconciled manually by extracting only the RR-AUTH-02 middleware, route, and test files to avoid overwriting RR-AUTH-01 principal changes.
- The initial worker checkpoint was returned once because its login-flow test created `LandlordUser::create([... 'password' => ...])`, which would be invalid after RR-AUTH-01 strips legacy password state.
- The accepted worker follow-up creates canonical password credentials through `LandlordUserAccessService::syncPasswordCredentialsForEmails()` and removes legacy state, so the login-flow test no longer depends on top-level `landlord_users.password`.
- Security finding `SEC-RR-AUTH-02-001` is resolved by `CheckCurrentTenantRoleAbility`, which proves the requested ability against the landlord principal's role for `Tenant::current()` at request time.
- The reconciled route/test/middleware files are byte-identical to the worker checkpoint after manual extraction; RR-AUTH-01 overlapping files from the worker commit were intentionally not applied.
- Audit follow-up findings resolved in `f00f63d7876d281c5621ce16cd9ef5a08c3f8e25`: borrowed-read denial coverage, direct denied iOS Apple association non-mutation coverage, authorized tenant-admin login-token store/delete coverage, and adjacent `/domains` borrowed-token same-ability hardening.
- Promotable staged set after WSL reconnect is frozen to the four RR-AUTH-02 Laravel paths only: `app/Http/Middleware/CheckCurrentTenantRoleAbility.php` (`A`), `routes/api/tenant_api_v1.php` (`M`), `tests/Feature/Tenants/TenantAppDomainControllerTest.php` (`M`), and `tests/Feature/Tenants/TenantDomainControllerTest.php` (`M`). `git diff --name-only -- <those paths>` returned no unstaged diff.
- Reconciliation validation on the principal checkout:
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php`
  - result: `31 passed`, `145 assertions`, duration `21.80s`
  - staged-freeze rerun of the same targeted suite after WSL reconnect
  - result: `31 passed`, `145 assertions`, duration `20.62s`
  - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php tests/Unit/Application/Tenants/TenantAppDomainManagementServiceTest.php tests/Unit/Application/Tenants/TenantAppDomainResolverServiceTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Api/v1/Admin/ApiV1AdminAuthTest.php tests/Unit/Application/Auth/LandlordAuthenticationServiceTest.php`
  - result: `90 passed`, `395 assertions`, duration `48.74s`
  - `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings`
  - result: `1383 passed`, `6552 assertions`, duration `606.44s`
  - `docker compose exec -T app php artisan route:list --path=appdomains -vv`
  - result: all three app-domain routes show `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update`, and `CheckCurrentTenantRoleAbility:tenant-domains:read|update`
  - `docker compose exec -T app php artisan route:list --path=domains -vv`
  - result: appdomain routes plus five tenant domain-management routes show `auth:sanctum`, `CheckTenantAccess`, Sanctum `tenant-domains:read|update`, and `CheckCurrentTenantRoleAbility:tenant-domains:read|update`
  - `docker compose exec -T app php scripts/architecture_guardrails.php`
  - result: `[ARCH-GUARDRAILS] PASS - no architecture violations found.`
  - `docker compose exec -T app ./vendor/bin/pint --test routes/api/tenant_api_v1.php tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php app/Http/Middleware/CheckCurrentTenantRoleAbility.php`
  - result: `PASS`, `4 files`
  - `git diff --cached --check` and `git diff --check`
  - result: passed

## Deterministic Guardrail Notes

- `bash delphi-ai/tools/laravel_tenant_access_guardrails_audit.sh --path laravel-app/routes/api/tenant_api_v1.php` still exits `2`.
- This is classified as an out-of-scope file-level blocker for RR-AUTH-02 because the tool flags existing authenticated identity routes in the same route file that do not carry `CheckTenantAccess`.
- Residual identity-route debt includes `auth/logout`, `auth/token_validate`, and `/me`, not only `auth/*`.
- The in-scope app-domain route matrix is proven by `route:list --path=appdomains -vv` and by the focused feature tests. The adjacent `/domains` route matrix is proven by `route:list --path=domains -vv` and `TenantDomainControllerTest`.
- The unrelated identity route classification remains with `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-architectural-rule-drift-review.md`, which is the broader route-matrix/backlog authority for non-appdomain tenant access classification.
- RR-AUTH-02 must not claim global tenant-route compliance from the appdomain-specific checkpoint.

## Documentation Scope Exclusions

- The same dirty module docs currently contain unrelated or pre-existing auth/OTP and landlord-credential notes.
- Excluded from RR-AUTH-02 closure evidence:
  - Google Play review / phone-OTP review-access material in `foundation_documentation/modules/onboarding_flow_module.md` and `foundation_documentation/modules/tenant_admin_module.md`.
  - Landlord password credential source-of-truth material in `foundation_documentation/modules/tenant_admin_module.md`.
  - Android store-release status normalization in `foundation_documentation/modules/onboarding_flow_module.md`.
- RR-AUTH-02 evidence may use only the app-domain/app-link trust-boundary rows added for `/admin/api/v1/appdomains`, `ONB-10`, and this package/TODO/checkpoint.

## Remaining Gates Before TODO Closure

- TODO-local critique review: complete, merged at `20260507T1217Z`.
- TODO-local security adversarial review: complete, merged at `20260507T1217Z`.
- TODO-local verification-debt audit: complete, merged at `20260507T1217Z`.
- TODO-local test-quality audit: complete, merged at `20260507T1217Z`.
- TODO-local independent final review: complete, merged at `20260507T1217Z`.
- Triple audit loop to convergence: complete. `foundation_documentation/artifacts/post-release-tenant-app-domain-authorization-hardening-package-triple-audit-20260507T1217Z/round-03/round-summary.md` is `clean` with Elegance, Performance, and Test Quality `findings: []`.
- Bounded `Claude CLI` fourth-auditor comparison record: complete. `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-02-tenant-app-domain-claude-comparison-20260507T1252Z.md` records alignment with triple audit and no blocking findings.
- TODO completion guard: complete. `foundation_documentation/artifacts/post-release-tenant-app-domain-todo-completion-guard-20260507T1252Z.json` records `Overall outcome: go`.
- Full orchestration delivery guard: not complete for the whole RR-AUTH plan. `foundation_documentation/artifacts/post-release-rule-related-auth-identity-delivery-guard-20260507T1252Z.json` remains `Overall outcome: no-go` because other RR-AUTH lanes and integrated validation rows are still planned/unproven.

## Audit Questions

1. Do all tenant app-domain read/store/delete routes prove both tenant access and explicit domain-management ability?
2. Can a landlord principal with a valid token but no tenant role, wrong tenant role, or wrong token ability read or mutate app-domain identifiers?
3. Does authorized app-domain mutation preserve `.well-known/assetlinks.json` derivation from typed app-domain identifiers and `settings.app_links`?
4. Does the test fixture remain compatible with RR-AUTH-01 canonical landlord password credentials?
5. Is the file-level tenant-access audit blocker correctly scoped as unrelated `auth/*` route classification rather than app-domain route drift?
