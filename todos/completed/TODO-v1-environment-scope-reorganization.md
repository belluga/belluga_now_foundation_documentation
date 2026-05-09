# TODO (V1): Environment Scope Reorganization (Binary EnvironmentType + Main Scopes + Subscopes)
**Version:** 4.2
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed
**Owners:** Platform + Flutter + Laravel
**Objective:** Establish a canonical scope model where `EnvironmentType` remains binary (`landlord` | `tenant`) and routing/UI behavior is organized by explicit main scopes + subscopes, reducing route and domain ambiguity across Web/App runtimes.

## Scope
- Define and document the canonical scope matrix (domain, route namespace, auth boundary, and API boundary) for the four main scopes.
- Define and document canonical terminology and ownership:
  - `EnvironmentType` (runtime context): `landlord` or `tenant` only.
  - `Main Scope` (entry area by host + path): `site_public`, `landlord_area`, `tenant_public`, `tenant_admin`.
  - `Subscope` (cross-area workspace transitions inside same environment): `account_workspace`.
- Align foundation docs (`modules/`, `screens/`, `system_roadmap.md`) with the canonical model.
- Implement Flutter route namespace normalization to the domain-driven main scope map:
  - On landlord domain:
    - Site Public: `/`
    - Landlord Area: `/admin`
  - On tenant domain/subdomain:
    - Tenant Public: `/`
    - Tenant Admin: `/admin`
- Resolve historical paths (`/home`, `/landlord`) with deterministic redirects to canonical scopes.
- Keep Laravel domain/middleware boundaries explicit and documented (no cross-scope leakage).
- Define subscope transitions that preserve context and make role switches explicit (preview/edit/workspace flow).
- Establish dual workspace routes on tenant host:
  - `/workspace`: account-user workspace home (cross-account management).
  - `/workspace/{account_slug}`: account-scoped admin area for the selected account.
- Organize `lib/presentation/` by canonical scopes + shared ownership (no intermediate `scopes/` folder).

## Out of Scope
- Visual redesign of tenant/landlord/public pages.
- New business features unrelated to scope separation.
- Breaking API contract changes for already-implemented endpoints.

## Decisions
- [x] ✅ Production‑Ready **Canonical runtime environment type:** `EnvironmentType` is binary and must remain `landlord` or `tenant` only.
- [x] ✅ Production‑Ready **Canonical main scopes by environment:**
  - `landlord` environment: `site_public`, `landlord_area`
  - `tenant` environment: `tenant_public`, `tenant_admin`
- [x] ✅ Production‑Ready **Canonical subscope ownership:** `account_workspace` belongs to the `tenant` environment.
- [x] ✅ Production‑Ready **Workspace modeling rule:** `account_workspace` remains a single subscope; `/workspace` and `/workspace/{account_slug}` are context modes (`root` and `scoped`), not additional subscopes.
- [x] ✅ Production‑Ready **Main scope mapping (domain-driven):**
  - Landlord domain: `/` -> `site_public`, `/admin` -> `landlord_area`
  - Tenant domain/subdomain: `/` -> `tenant_public`, `/admin` -> `tenant_admin`
- [x] ✅ Production‑Ready **Entry route contract per scope:**
  - `site_public`: canonical landlord-host public landing is `/`; entry CTA sends user to `landlord_area` (`/admin`).
  - `landlord_area`: `/admin` initial screen is the existing tenant list (tenants the user can access); selecting a tenant triggers transition to that tenant's `tenant_admin`.
  - `tenant_public`: canonical entry is tenant root (`/`).
  - `tenant_admin`: tenant context is resolved by tenant domain/subdomain host; entry is on tenant `/admin` (direct open or via redirect link from landlord-area tenant selection).
- [x] ✅ Production‑Ready **Account workspace initial home decision:** `account_workspace` starts with a dedicated placeholder home (not menu-first).
  - Initial delivery for `account_workspace` is a minimal placeholder screen/shell.
  - Menu management is entered only by explicit user action from within `account_workspace` (e.g., "edit menu").
  - `account_workspace` is the operational area entry; menu is a feature inside this area, not its landing route.
- [x] ✅ Production‑Ready **Account workspace placeholder UI contract (internal-only):**
  - Render a centered title indicating placeholder state.
  - Render a visible "back" action/button that executes only a simple `pop()` navigation action.
  - No fallback redirect is defined in this phase for the placeholder back action.
  - Placeholder is internal tooling surface (no public marketing/SEO expectation).
- [x] ✅ Production‑Ready **Tenant public entry policy:** canonical entry is tenant root (`/`); `/home` is treated as historical/compatibility path during migration.
- [x] ✅ Production‑Ready **Mobile app entry policy (flavor-driven):**
  - `Mobile Landlord` target boots in `EnvironmentType.landlord` and defaults to main scope `landlord_area`.
  - `Mobile Tenant` target boots in `EnvironmentType.tenant` and defaults to main scope `tenant_public`.
  - On mobile, app target/flavor is the primary context source (no host-domain inference requirement).
- [x] ✅ Production‑Ready **Workspace entry policy (account_workspace):**
  - Canonical web entry path `/workspace` is mandatory and represents the account-user workspace home (cross-account context).
  - Canonical scoped path `/workspace/{account_slug}` is mandatory for account-specific admin context.
  - Child paths under `/workspace/{account_slug}` are allowed for account-scoped features.
  - Mobile tenant app default entry remains `tenant_public`; `account_workspace` is entered via explicit CTA/intent.
  - Landlord environment does not expose `account_workspace`; invalid access must fallback to the canonical root for that environment.
- [x] ✅ Production‑Ready **Workspace account slug fallback policy:**
  - Invalid or unauthorized `{account_slug}` must fallback to `/workspace` with explicit feedback.
- [x] ✅ Production‑Ready **Workspace naming policy:** fixed route namespace is `/workspace` in this rollout.
- [x] ✅ Production‑Ready **Workspace alias policy (this phase):** no alias parameter is supported; route remains fixed at `/workspace`.
- [x] ✅ Production‑Ready **Historical path policy (`/home`, `/landlord`):**
  - Landlord host: `/landlord` -> `/admin` after site public root (`/`) canonical cutover is live.
  - Landlord host: `/home` -> `/admin` (preserve previous landlord entry intent).
  - Tenant host/subdomain: `/home` -> `/` (tenant public canonical).
  - Tenant host/subdomain: `/landlord` -> `/` (landlord scope is invalid in tenant environment).
  - Redirects are web-only URL normalization rules and must not change `EnvironmentType` resolution semantics.
- [x] ✅ Production‑Ready **API policy:** keep existing backend prefixes/domain routing; reorganize is focused on presentation/routing semantics + docs synchronization.
- [x] ✅ Production‑Ready **Web automated validation policy:** use Playwright navigation suite (`web-app`) as mandatory regression layer for canonical scope/subscope home reachability.
- [x] ✅ Production‑Ready **E2E scope policy (current phase):**
  - Current E2E coverage is limited to main-structure validation (scope routing, redirects, guard outcomes).
  - Detailed screen-level assertions are deferred to later implementation phases as subscope screens are consolidated.
- [x] ✅ Production‑Ready **Initial auth validation policy (web):**
  - Initial Playwright coverage may run without authenticated session bootstrap.
  - For protected routes (`/admin`, `/workspace`, `/workspace/{account_slug}`), deterministic auth-guard behavior is sufficient in this initial phase:
    - Landlord-domain `/admin` -> landlord login flow when unauthenticated.
    - Tenant-domain `/admin` -> tenant-domain landlord login flow when unauthenticated (same landlord identity principal).
    - Tenant-domain `/workspace*` -> account/workspace auth flow when unauthenticated.
  - Fully authenticated path assertions are deferred to a later hardening iteration.
- [x] ✅ Production‑Ready **Auth-guard validation symmetry (`/admin` landlord and tenant):**
  - In the initial validation phase, both landlord `/admin` and tenant `/admin` accept deterministic auth-guard redirect outcomes when unauthenticated.
- [x] ✅ Production‑Ready **Landlord->tenant redirect-link behavior (current phase):**
  - From `landlord_area`, clicking a tenant follows a redirect link to the selected tenant's `tenant_admin` entry on the tenant primary domain.
  - Redirect target remains tenant-domain `/admin`, which is the landlord-identity admin surface for that tenant host.
- [x] ✅ Production‑Ready **No cross-domain login policy (current phase):**
  - Cross-domain/subdomain session reuse (SSO-style login propagation) is out of scope for this phase.
  - Tenant-domain auth is independent for `tenant_admin`: if the user already has a valid **tenant-domain landlord-identity session**, access proceeds; otherwise the tenant-domain landlord login flow is required.
- [x] ✅ Production‑Ready **Identity continuity policy (Landlord):**
  - Landlord-domain `/admin`, tenant-domain `/admin`, and the `landlord_area` -> tenant-domain `/admin` redirect-link flow all use the same identity principal (`landlord`).
  - In this phase, host/domain sessions remain independent (same identity principal, separate domain login sessions).
- [x] ✅ Production‑Ready **Terminology policy (English-only):**
  - This TODO uses English-only terminology.
  - The migration activation moment is named `canonical cutover`.
- [x] ✅ Production‑Ready **Subscope policy:** within a main scope, explicit transitions are allowed when role/context permits:
  - `tenant_public` -> `account_workspace` via explicit CTA in tenant app (workspace entry).
  - `tenant_admin` -> `tenant_public` via explicit preview CTA while preserving selected tenant context.
  - `tenant_public` -> `tenant_admin` requires landlord identity (same landlord principal across landlord and tenant domains) and remains guarded.
- [x] ✅ Production‑Ready **Presentation structure policy (scope + shared ownership):**
  - `lib/presentation/` must contain canonical top-level ownership folders only:
    - `lib/presentation/site_public/**`
    - `lib/presentation/landlord_area/**`
    - `lib/presentation/tenant_public/**`
    - `lib/presentation/tenant_admin/**`
    - `lib/presentation/account_workspace/**`
    - `lib/presentation/shared/**`
  - Existing top-level folders (`common`, `landlord`, `tenant`, `tenant_admin`, `prototypes`) must be fully reassigned into one of the scope folders or `shared`.
  - This phase includes route/screen/controller/widget ownership migration (not route-only migration).
- [x] ✅ Production‑Ready **Web regression/pipeline safety policy for Phase 4:**
  - Presentation-path migration must preserve source-owned navigation test stability (`tools/flutter/web_app_tests/navigation.spec.js`) and CI sync into `web-app/tests` before execution.
  - Any web-test break caused by file-path migration must be fixed in the same iteration before marking Phase 4 tasks done.
  - Web artifact publish flow compatibility must be preserved (build script + workflow assumptions).

## Subscope Transition Model (V1)
- `tenant_public` (user mode) may expose a guarded CTA to enter `account_workspace` (when user has workspace permission).
- `tenant_admin` (landlord editing mode) must expose a preview action that navigates to `tenant_public` for the same tenant.
- `EnvironmentType` must not change during subscope transitions (`tenant_public` <-> `tenant_admin` <-> `account_workspace` all remain `tenant`).
- Transitions must preserve tenant identity/context (same tenant target) and never cross tenants implicitly.
- If permissions/session do not satisfy the target subscope, transition falls back to the main scope root with explicit feedback.
- Canonical URL namespace for workspace flows is fixed at `/workspace` (never `/admin`).
- Entering `account_workspace` without selected account lands at `/workspace` (account-user workspace home).
- Selecting an account inside workspace transitions to `/workspace/{account_slug}` (account-specific admin context).
- If `{account_slug}` is invalid/unauthorized, route must fallback to `/workspace` with explicit feedback.

## Historical Baseline (Before Canonical Cutover)
- `canonical cutover` = moment when canonical routing rules become active in runtime (legacy paths stop being primary and redirect to canonical targets).
- Legacy paths prior to cutover included landlord public served on `/landlord` and tenant public served via `/home`.
- Canonical cutover is now active in runtime; legacy notes are retained only for migration traceability.

## Pre-Implementation Deep Dive (Mandatory)
### A. Routing and Context Resolution
- [x] ✅ Production‑Ready Define a single resolver contract for `{host, path, session} -> {EnvironmentType, main_scope, subscope?}`.
  - Web contract: `{host + path}` is canonical input; auth/session only determines guarded-route outcome.
  - Mobile contract: flavor/environment bootstrap is canonical input; host inference is not required.
- [x] ✅ Production‑Ready Decide deterministic precedence between host inference, persisted app context, and current session mode.
  - Web precedence: `host -> canonical path normalization -> guard/session`.
  - Mobile precedence: `flavor/environment bootstrap -> guard/session`.
- [x] ✅ Production‑Ready Define mobile resolver variant where app target/flavor determines default main scope (`Mobile Landlord` -> `landlord_area`, `Mobile Tenant` -> `tenant_public`).
- [x] ✅ Production‑Ready Define host-based tenant resolution contract for tenant-domain `/admin` (tenant resolved from domain/subdomain host, not path parameter).
- [x] ✅ Production‑Ready Define invalid-combination fallback policy (example: tenant host + landlord-only route).
  - Tenant host + landlord-only route => fallback to tenant canonical root (`/`).
  - Landlord host + tenant historical paths (`/home`, `/landlord`) => normalize to landlord-area canonical entry (`/admin`).

### B. Guard and Permission Matrix
- [x] ✅ Production‑Ready Produce an explicit access matrix per scope/subscope (`anonymous`, `tenant user`, `tenant admin`, `landlord admin`).
  - `anonymous`: landlord `/` and tenant `/` reachable; protected scopes redirect to auth.
  - `tenant user`: tenant `/` reachable; workspace guarded by auth; tenant `/admin` remains landlord-identity guarded.
  - `landlord admin`: landlord `/admin` reachable; tenant `/admin` reachable on tenant host; landlord->tenant transition uses redirect link.
- [x] ✅ Production‑Ready Confirm `tenant_public -> tenant_admin` remains landlord-authenticated only (same landlord identity principal as landlord `/admin` and redirect-link flow; no tenant privilege escalation).
- [x] ✅ Production‑Ready Define `tenant_public -> account_workspace` authorization source of truth and denial UX contract.
  - Current phase source of truth: `TenantRouteGuard + AuthRouteGuard`.
  - Denial contract (initial): deterministic auth redirect (no hidden fallback).
- [x] ✅ Production‑Ready Define authorization split between `/workspace` (account-user scope) and `/workspace/{account_slug}` (account-scoped admin scope).
  - `/workspace`: account-user workspace home.
  - `/workspace/{account_slug}`: account-scoped mode; invalid slug policy is fallback to workspace root.

### C. UX Transition Contracts
- [x] ✅ Production‑Ready Define canonical CTAs and labels for transition actions (enter workspace, preview tenant, back to admin).
  - Tenant public -> workspace: app-bar `Workspace` action.
  - Tenant admin -> tenant public: shell `Preview tenant public` action.
  - Workspace placeholder: explicit `Back` action (`pop()` only).
- [x] ✅ Production‑Ready Define context-preservation contract (`tenant_id`, `account_slug`, active filters) across transitions.
  - Host/domain is the tenant-context source for `tenant_admin`.
  - Workspace scoped mode keeps `account_slug` in URL.
  - Selected tenant-domain context is persisted in tenant-admin selected-tenant repository.
- [x] ✅ Production‑Ready Define browser back-button behavior for each transition to avoid loop/stack confusion.
  - Cross-domain landlord->tenant transitions are full redirect-link navigations (`_self`), preserving browser history semantics.
  - Internal placeholder back behavior is stack pop only.
- [x] ✅ Production‑Ready Define account switch UX contract (`/workspace` -> `/workspace/{account_slug}` and back to workspace root).
  - Route contract is established (`/workspace` root + `/workspace/{account_slug}` scoped); account switch UI remains a follow-up feature.
- [x] ✅ Production‑Ready Define landlord->tenant redirect-link UX contract after tenant click (`landlord_area` list -> tenant domain `/admin`, same landlord identity principal with independent tenant-domain login fallback when tenant-domain landlord session is absent).
- [x] ✅ Production‑Ready Implement placeholder UX contract for `account_workspace` home (centered title + explicit `pop()` back action, no fallback).

### D. Runtime and Web Bootstrap
- [x] ✅ Production‑Ready Review and align bootstrap redirects (`web-app/index.html`) with canonical landlord `/admin` target.
- [x] ✅ Production‑Ready Validate ingress/runtime assumptions for host-based routing remain stable after canonical paths.
- [x] ✅ Production‑Ready Implement and verify deterministic redirects for `/home` and `/landlord` using host-aware rules.

### E. Testing and Observability
- [x] ✅ Production‑Ready Define minimal regression suite covering direct-open, refresh, guard denial, and subscope transitions.
- [x] ✅ Production‑Ready Add assertions for resolver output (`EnvironmentType`, scope, fallback reason) in route guard tests.
- [x] ✅ Production‑Ready Define logs/metrics to detect misrouting and failed transitions during rollout.
  - Initial rollout observability uses deterministic route-guard assertions, explicit Playwright landing logs, and structured tenant-admin redirect-link test coverage.
- [x] ✅ Production‑Ready Define Playwright scope-home matrix (`site_public`, `landlord_area`, `tenant_public`, `tenant_admin`, `account_workspace`) for initial unauthenticated probing + expected auth-guard outcomes on protected routes (`workspace_mode=root|scoped`).

## Tasks
### Phase 1 — Documentation Canonicalization
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/tenant_admin_module.md` with explicit scope boundaries and canonical route map.
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_landlord_app.md` with the binary EnvironmentType model + main-scope boundary rules.
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_tenant_admin.md` with canonical tenant-scoped admin paths.
- [x] ✅ Production‑Ready Normalize wording in docs/code references: replace ambiguous "new environment" wording with "main scope" or "subscope" where applicable.
- [x] ✅ Production‑Ready Document `subscope` transitions (tenant_public <-> tenant_admin preview/edit, tenant_public -> account_workspace) in module and screen contracts.
- [x] ✅ Production‑Ready Update `foundation_documentation/system_roadmap.md` with a dedicated milestone for scope reorganization and migration status.
- [x] ✅ Production‑Ready Record known gaps: missing `foundation_documentation/submodule_web-app_summary.md` and stale submodule summary commit hashes.

### Phase 2 — Flutter Route Normalization
- [x] ✅ Production‑Ready Refactor route definitions in `flutter-app/lib/application/router/modular_app/modules/**` to domain-driven canonical paths (`/` and `/admin` by host scope).
- [x] ✅ Production‑Ready Add host/domain-driven context hydration so `/` and `/admin` resolve the correct main scope deterministically.
- [x] ✅ Production‑Ready Keep guard behavior strict (`LandlordRouteGuard` vs tenant routes) with no cross-scope deep-link leakage.
- [x] ✅ Production‑Ready Implement historical path redirects for `/home` and `/landlord` according to the canonical matrix.
- [x] ✅ Production‑Ready Implement fixed dual workspace routes for `account_workspace`: `/workspace` (root) and `/workspace/{account_slug}` (scoped).
- [x] ✅ Production‑Ready Create `account_workspace` workspace-home route/screen at `/workspace` and scoped admin entry route at `/workspace/{account_slug}`.
- [x] ✅ Production‑Ready Implement subscope CTAs/transitions (`tenant_public` -> `account_workspace`, `tenant_admin` -> `tenant_public` preview) with permission-aware guards.

### Phase 3 — Validation and Regression Protection
- [x] ✅ Production‑Ready Add/update route tests for canonical paths and URL-only hydration.
- [x] ✅ Production‑Ready Add/update deep-link refresh tests for tenant public/admin routes.
- [x] ✅ Production‑Ready Add/update tests for subscope transitions and fallback behavior when permission is missing.
- [x] ✅ Production‑Ready Add/update integration tests for landlord tenant-click redirect-link to tenant domain `/admin` (same landlord identity principal; direct access when tenant-domain landlord session exists; auth fallback when tenant-domain landlord session is missing).
- [x] ✅ Production‑Ready Extend `web-app/tests/navigation.spec.js` with canonical home assertions per scope/subscope (host-aware), including `/workspace` root and `/workspace/{account_slug}` scoped behavior (authorized access or deterministic auth-guard redirect).
- [x] ✅ Production‑Ready Validate Laravel route scoping and middleware boundaries remain unchanged and correct.

### Phase 4 — Presentation Scope/Shared Ownership Refactor
- [x] ✅ Production‑Ready Create top-level `lib/presentation/{site_public,landlord_area,tenant_public,tenant_admin,account_workspace,shared}/` structure.
- [x] ✅ Production‑Ready Move current `presentation/common`, `presentation/landlord`, `presentation/tenant`, `presentation/tenant_admin`, and `presentation/prototypes` content into canonical scope/shared ownership folders.
- [x] ✅ Production‑Ready Ensure route/screen/controller/widget files follow the new ownership paths.
- [x] ✅ Production‑Ready Update all imports/usages (modules, generated router, tests, docs references) to new presentation paths.
- [x] ✅ Production‑Ready Regenerate `app_router.gr.dart` after presentation path migration.
- [x] ✅ Production‑Ready Remove obsolete legacy top-level presentation ownership folders after migration.
- [x] ✅ Production‑Ready Update/repair web navigation tests if refactor side effects impact selectors, routes, or bootstrap behavior.
- [x] ✅ Production‑Ready Verify `.github/workflows/web-artifact-publish.yml` assumptions remain valid after presentation path migration.

## Definition of Done
- [x] ✅ Production‑Ready Scope matrix is canonical and documented across modules/screens/roadmap.
- [x] ✅ Production‑Ready Domain-driven main scope routing is canonical (`/` and `/admin` resolved by host scope).
- [x] ✅ Production‑Ready Historical paths (`/home`, `/landlord`) redirect deterministically to canonical paths by host.
- [x] ✅ Production‑Ready Landlord and tenant scopes remain mutually isolated under guards/domain constraints.
- [x] ✅ Production‑Ready `EnvironmentType` remains binary in route guards/context resolvers (no `landlord_public`/`landlord_admin`/`tenant_admin` enum expansions).
- [x] ✅ Production‑Ready Subscope transitions (preview/edit/workspace) are explicit, permission-aware, and tenant-context-safe.
- [x] ✅ Production‑Ready Route/deep-link tests cover direct-open and refresh scenarios.
- [x] ✅ Production‑Ready Dual workspace contract is delivered: `/workspace` (account-user home) and `/workspace/{account_slug}` (account-scoped admin), both with deterministic auth/fallback behavior.
- [x] ✅ Production‑Ready Playwright web suite validates canonical home reachability for each defined scope/subscope under landlord/tenant hosts, including auth-guard redirects for protected routes in the initial phase.
- [x] ✅ Production‑Ready Landlord tenant-click redirect-link reaches tenant-domain `/admin` with host-resolved tenant context, same landlord identity principal, and independent tenant-domain landlord auth (no cross-domain login propagation).
- [x] ✅ Production‑Ready Presentation content is organized under canonical scope/shared top-level folders with no remaining legacy ownership roots.

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/application/router/guards/tenant_route_guard_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/application/router/guards/landlord_route_guard_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/application/router/tenant_admin_route_path_params_test.dart`
- [x] ✅ Production‑Ready Add/run focused tests for host-driven main scope resolution (`/` and `/admin` by domain).
- [x] ✅ Production‑Ready Add/run focused tests for `/home` and `/landlord` host-aware redirect matrix.
- [x] ✅ Production‑Ready Add/run focused tests for `/workspace` root and `/workspace/{account_slug}` scoped routing, including fallback behavior and auth-guard redirect expectations when unauthenticated.
- [x] ✅ Production‑Ready Add/run focused tests for landlord `/admin` tenant-click redirect-link to tenant-domain `/admin` (with and without existing tenant-domain landlord session).
- [x] ✅ Production‑Ready Add/run focused tests for mobile flavor-driven startup scope resolution.
- [x] ✅ Production‑Ready Add/run focused tests for subscope transitions and permission fallback.
- [x] ✅ Production‑Ready `cd web-app && NAV_LANDLORD_URL=https://<landlord-host> NAV_TENANT_URL=https://<tenant-host> npm run test:navigation` (main-structure smoke only in this phase)
- [x] ✅ Production‑Ready `fvm flutter test` targeted presentation-ownership smoke after folder moves (routes + guards + tenant admin shell + representative screen/widget tests).
- [x] ✅ Production‑Ready `cd web-app && npm ci && NAV_LANDLORD_URL=https://<landlord-host> NAV_TENANT_URL=https://<tenant-host> npm run test:navigation` after Phase 4 migration changes.
- [x] ✅ Production‑Ready Validate publish compatibility by running `flutter-app/scripts/build_web.sh` and confirming generated bundle still serves/boots correctly in local tunnel hosts.
- [x] ✅ Production‑Ready If any local web test fails after migration, iterate fixes immediately before progressing to remaining Phase 4 checkboxes.
- [x] ✅ Production‑Ready Manual web validation:
  - Live tunnel validated on landlord and tenant hosts.
  - Canonical path matrix (`/`, `/admin`, `/home`, `/landlord`, `/workspace`, `/workspace/{account_slug}`) validated with direct-open + refresh behavior.
  - Landlord-area tenant selection redirect-link validated by tenant-admin shell widget coverage + tenant-domain `/admin` auth-guard outcome coverage.
- [x] ✅ Production‑Ready Manual mobile validation:
  - Device integrations validated tenant bootstrap, admin route navigation, and shell navigation smoke.
  - Mobile tenant startup resolves tenant environment deterministically.
  - Landlord-admin startup/navigation path validated through admin integration flow.

## Execution Notes (2026-02-23 to 2026-02-24)
- Web bundle build/publish loops executed locally via:
  - `tools/flutter/build_web_bundle.sh`
  - `flutter-app/scripts/build_web.sh` (lane `dev`, with `config/defines/local.override.json`)
- Live tunnel Playwright validation passed:
  - `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space npm run test:navigation`
- Flutter validation:
  - `fvm flutter analyze` => clean (no issues)
  - Route/guard/path/widget tests executed and green, including redirect-link widget coverage in `tenant_admin_shell_screen_test.dart`.
- Device integration runs (WSL-safe one-by-one checklist in `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md`):
  - ✅ `integration_test/feature_environment_tenant_bootstrap_test.dart`
  - ✅ `integration_test/feature_admin_accounts_routes_test.dart`
  - ✅ `integration_test/feature_shell_navigation_smoke_test.dart`
- Phase 4 ownership migration completed:
  - Legacy roots removed: `presentation/common`, `presentation/landlord`, `presentation/tenant`, `presentation/prototypes`.
  - Canonical roots active: `presentation/shared`, `presentation/landlord_area`, `presentation/tenant_public`, `presentation/tenant_admin`, `presentation/account_workspace`, `presentation/site_public`.
  - `site_public` is currently a structural placeholder root; existing landlord landing screen remains under `landlord_area` in this phase to avoid behavior drift.
- Post-migration regression/build loop completed:
  - ✅ `fvm flutter analyze` after route/import migration and integration test import updates.
  - ✅ Targeted Flutter smoke tests (guards/routes/tenant-admin shell/landlord home/tenant home/shared push).
  - ✅ `flutter-app/scripts/build_web.sh` completed and published web bundle to local tunnel target.
  - ✅ Host checks after publish: `https://belluga.space` and `https://guarappari.belluga.space` returned `200` on `/` and `/admin`.
  - ✅ Re-ran `web-app` navigation tests after publish.
  - ✅ One regression iteration executed: `playwright` command missing after publish cleanup; fixed by rerunning `npm ci` before post-build navigation tests.
  - ✅ CI workflow hardened to sync source-owned navigation tests (`tools/flutter/web_app_tests`) into `web-app/tests` before stage/production Playwright runs.
  - ✅ Source-owned Playwright expectation fixed for landlord `/home` normalization (`/home` on landlord host resolves to `/admin`).
- Laravel scope/middleware boundary validation:
  - ✅ `tests/Feature/Tenants/TenantResolutionTest.php`
  - ✅ `tests/Api/v1/Admin/ApiV1AdminTenantTest.php`
  - ✅ `tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`
  - ✅ `php artisan route:list -v --path=admin/api/v1/accounts` confirms tenant admin account routes keep `tenant`, `landlord`, `auth:sanctum`, and `CheckTenantAccess` middleware stack.
  - ℹ️ `tests/Api/v1/Admin/ApiV1AdminAuthTest.php` currently fails on an existing validation-status expectation mismatch (`422` returned where test expects `403`), outside this route-scope reorganization.

## Reopen Note (2026-02-24)
- This TODO was reopened to add the structural requirement requested during review:
  - presentation route files must be explicitly organized by canonical subscope ownership.

## Dependencies
- `foundation_documentation/todos/completed/TODO-v1-route-url-only-hydration-hardening.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-route-paths-refactor.md`
- `foundation_documentation/todos/completed/TODO-vnext-endpoint-scope-separation.md`
