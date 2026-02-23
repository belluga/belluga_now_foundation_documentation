# TODO (V1): Environment Scope Reorganization (Binary EnvironmentType + Main Scopes + Subscopes)
**Version:** 3.13
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Active
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
  - `site_public`: current baseline uses `/landlord` as public landing; entry CTA sends user to `landlord_area`.
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

## Current Baseline (As-Is, Before Canonical Cutover)
- `canonical cutover` = moment when canonical routing rules become active in runtime (legacy paths stop being primary and redirect to canonical targets).
- `site_public` is temporarily served at `/landlord` on landlord host.
- Current `/landlord` screen is treated as public landing and exposes login CTA that can send user to landlord area (`/admin` flow).
- `tenant_public` currently lands at `/home`; root `/` currently boots init flow and then resolves to tenant home stack.
- `landlord_area` currently can act as tenant-selection entry before moving to tenant admin flows.
- `tenant_admin` screens already live under `/admin` and currently guarded by `LandlordRouteGuard`.
- `account_workspace` is not implemented yet; first delivery is a dedicated placeholder home route/screen.

## Pre-Implementation Deep Dive (Mandatory)
### A. Routing and Context Resolution
- [ ] ⚪ Pending Define a single resolver contract for `{host, path, session} -> {EnvironmentType, main_scope, subscope?}`.
- [ ] ⚪ Pending Decide deterministic precedence between host inference, persisted app context, and current session mode.
- [ ] ⚪ Pending Define mobile resolver variant where app target/flavor determines default main scope (`Mobile Landlord` -> `landlord_area`, `Mobile Tenant` -> `tenant_public`).
- [ ] ⚪ Pending Define host-based tenant resolution contract for tenant-domain `/admin` (tenant resolved from domain/subdomain host, not path parameter).
- [ ] ⚪ Pending Define invalid-combination fallback policy (example: tenant host + landlord-only route).

### B. Guard and Permission Matrix
- [ ] ⚪ Pending Produce an explicit access matrix per scope/subscope (`anonymous`, `tenant user`, `tenant admin`, `landlord admin`).
- [ ] ⚪ Pending Confirm `tenant_public -> tenant_admin` remains landlord-authenticated only (same landlord identity principal as landlord `/admin` and redirect-link flow; no tenant privilege escalation).
- [ ] ⚪ Pending Define `tenant_public -> account_workspace` authorization source of truth and denial UX contract.
- [ ] ⚪ Pending Define authorization split between `/workspace` (account-user scope) and `/workspace/{account_slug}` (account-scoped admin scope).

### C. UX Transition Contracts
- [ ] ⚪ Pending Define canonical CTAs and labels for transition actions (enter workspace, preview tenant, back to admin).
- [ ] ⚪ Pending Define context-preservation contract (`tenant_id`, `account_slug`, active filters) across transitions.
- [ ] ⚪ Pending Define browser back-button behavior for each transition to avoid loop/stack confusion.
- [ ] ⚪ Pending Define account switch UX contract (`/workspace` -> `/workspace/{account_slug}` and back to workspace root).
- [ ] ⚪ Pending Define landlord->tenant redirect-link UX contract after tenant click (`landlord_area` list -> tenant domain `/admin`, same landlord identity principal with independent tenant-domain login fallback when tenant-domain landlord session is absent).
- [ ] ⚪ Pending Implement placeholder UX contract for `account_workspace` home (centered title + explicit `pop()` back action, no fallback) and explicit action to open menu editor.

### D. Runtime and Web Bootstrap
- [ ] ⚪ Pending Review and align bootstrap redirects (`web-app/index.html`) with canonical landlord `/admin` target.
- [ ] ⚪ Pending Validate ingress/runtime assumptions for host-based routing remain stable after canonical paths.
- [ ] ⚪ Pending Implement and verify deterministic redirects for `/home` and `/landlord` using host-aware rules.

### E. Testing and Observability
- [ ] ⚪ Pending Define minimal regression suite covering direct-open, refresh, guard denial, and subscope transitions.
- [ ] ⚪ Pending Add assertions for resolver output (`EnvironmentType`, scope, fallback reason) in route guard tests.
- [ ] ⚪ Pending Define logs/metrics to detect misrouting and failed transitions during rollout.
- [ ] ⚪ Pending Define Playwright scope-home matrix (`site_public`, `landlord_area`, `tenant_public`, `tenant_admin`, `account_workspace`) for initial unauthenticated probing + expected auth-guard outcomes on protected routes (`workspace_mode=root|scoped`).

## Tasks
### Phase 1 — Documentation Canonicalization
- [ ] ⚪ Pending Update `foundation_documentation/modules/tenant_admin_module.md` with explicit scope boundaries and canonical route map.
- [ ] ⚪ Pending Update `foundation_documentation/screens/modulo_landlord_app.md` with the binary EnvironmentType model + main-scope boundary rules.
- [ ] ⚪ Pending Update `foundation_documentation/screens/modulo_tenant_admin.md` with canonical tenant-scoped admin paths.
- [ ] ⚪ Pending Normalize wording in docs/code references: replace ambiguous "new environment" wording with "main scope" or "subscope" where applicable.
- [ ] ⚪ Pending Document `subscope` transitions (tenant_public <-> tenant_admin preview/edit, tenant_public -> account_workspace) in module and screen contracts.
- [ ] ⚪ Pending Update `foundation_documentation/system_roadmap.md` with a dedicated milestone for scope reorganization and migration status.
- [ ] ⚪ Pending Record known gaps: missing `foundation_documentation/submodule_web-app_summary.md` and stale submodule summary commit hashes.

### Phase 2 — Flutter Route Normalization
- [ ] ⚪ Pending Refactor route definitions in `flutter-app/lib/application/router/modular_app/modules/**` to domain-driven canonical paths (`/` and `/admin` by host scope).
- [ ] ⚪ Pending Add host/domain-driven context hydration so `/` and `/admin` resolve the correct main scope deterministically.
- [ ] ⚪ Pending Keep guard behavior strict (`LandlordRouteGuard` vs tenant routes) with no cross-scope deep-link leakage.
- [ ] ⚪ Pending Implement historical path redirects for `/home` and `/landlord` according to the canonical matrix.
- [ ] ⚪ Pending Implement fixed dual workspace routes for `account_workspace`: `/workspace` (root) and `/workspace/{account_slug}` (scoped).
- [ ] ⚪ Pending Create `account_workspace` workspace-home route/screen at `/workspace` and scoped admin entry route at `/workspace/{account_slug}`.
- [ ] ⚪ Pending Implement subscope CTAs/transitions (`tenant_public` -> `account_workspace`, `tenant_admin` -> `tenant_public` preview) with permission-aware guards.

### Phase 3 — Validation and Regression Protection
- [ ] ⚪ Pending Add/update route tests for canonical paths and URL-only hydration.
- [ ] ⚪ Pending Add/update deep-link refresh tests for tenant public/admin routes.
- [ ] ⚪ Pending Add/update tests for subscope transitions and fallback behavior when permission is missing.
- [ ] ⚪ Pending Add/update integration tests for landlord tenant-click redirect-link to tenant domain `/admin` (same landlord identity principal; direct access when tenant-domain landlord session exists; auth fallback when tenant-domain landlord session is missing).
- [ ] ⚪ Pending Extend `web-app/tests/navigation.spec.js` with canonical home assertions per scope/subscope (host-aware), including `/workspace` root and `/workspace/{account_slug}` scoped behavior (authorized access or deterministic auth-guard redirect).
- [ ] ⚪ Pending Validate Laravel route scoping and middleware boundaries remain unchanged and correct.

## Definition of Done
- [ ] ⚪ Pending Scope matrix is canonical and documented across modules/screens/roadmap.
- [ ] ⚪ Pending Domain-driven main scope routing is canonical (`/` and `/admin` resolved by host scope).
- [ ] ⚪ Pending Historical paths (`/home`, `/landlord`) redirect deterministically to canonical paths by host.
- [ ] ⚪ Pending Landlord and tenant scopes remain mutually isolated under guards/domain constraints.
- [ ] ⚪ Pending `EnvironmentType` remains binary in route guards/context resolvers (no `landlord_public`/`landlord_admin`/`tenant_admin` enum expansions).
- [ ] ⚪ Pending Subscope transitions (preview/edit/workspace) are explicit, permission-aware, and tenant-context-safe.
- [ ] ⚪ Pending Route/deep-link tests cover direct-open and refresh scenarios.
- [ ] ⚪ Pending Dual workspace contract is delivered: `/workspace` (account-user home) and `/workspace/{account_slug}` (account-scoped admin), both with deterministic auth/fallback behavior.
- [ ] ⚪ Pending Playwright web suite validates canonical home reachability for each defined scope/subscope under landlord/tenant hosts, including auth-guard redirects for protected routes in the initial phase.
- [ ] ⚪ Pending Landlord tenant-click redirect-link reaches tenant-domain `/admin` with host-resolved tenant context, same landlord identity principal, and independent tenant-domain landlord auth (no cross-domain login propagation).

## Validation Steps
- [ ] ⚪ Pending `fvm flutter analyze`
- [ ] ⚪ Pending `fvm flutter test test/application/router/guards/tenant_route_guard_test.dart`
- [ ] ⚪ Pending `fvm flutter test test/application/router/guards/landlord_route_guard_test.dart`
- [ ] ⚪ Pending `fvm flutter test test/application/router/tenant_admin_route_path_params_test.dart`
- [ ] ⚪ Pending Add/run focused tests for host-driven main scope resolution (`/` and `/admin` by domain).
- [ ] ⚪ Pending Add/run focused tests for `/home` and `/landlord` host-aware redirect matrix.
- [ ] ⚪ Pending Add/run focused tests for `/workspace` root and `/workspace/{account_slug}` scoped routing, including fallback behavior and auth-guard redirect expectations when unauthenticated.
- [ ] ⚪ Pending Add/run focused tests for landlord `/admin` tenant-click redirect-link to tenant-domain `/admin` (with and without existing tenant-domain landlord session).
- [ ] ⚪ Pending Add/run focused tests for mobile flavor-driven startup scope resolution.
- [ ] ⚪ Pending Add/run focused tests for subscope transitions and permission fallback.
- [ ] ⚪ Pending `cd web-app && NAV_LANDLORD_URL=https://<landlord-host> NAV_TENANT_URL=https://<tenant-host> npm run test:navigation` (main-structure smoke only in this phase)
- [ ] ⚪ Pending Manual web validation:
  - Before canonical cutover (current baseline):
    - Landlord host: `/landlord` serves current site public landing behavior.
    - Tenant host: `/home` still resolves through current tenant home bootstrap behavior.
  - After canonical cutover:
    - Landlord host: `/` -> site public, `/admin` -> landlord area (or auth-guard redirect when unauthenticated).
    - Landlord `/admin` initial view renders tenant list for accessible tenants.
    - Clicking a tenant in landlord area follows a redirect link to selected tenant domain `/admin` (direct when tenant-domain landlord session exists, tenant-domain landlord login fallback otherwise; no cross-domain login propagation).
    - Landlord host: `/landlord` -> `/admin`.
    - Landlord host: `/home` -> `/admin`.
    - Tenant host: `/` -> tenant public, `/admin` -> tenant admin (or tenant-domain landlord login redirect when unauthenticated).
    - Tenant host: `/home` -> `/`.
    - Tenant host: `/landlord` -> `/`.
    - Tenant host: `/workspace` -> account-user workspace home when authorized, or auth-guard redirect when unauthenticated.
    - Tenant host: `/workspace/{account_slug}` -> account-scoped admin area when authorized for that account, or auth-guard redirect when unauthenticated.
    - Invalid/unauthorized `{account_slug}` falls back to `/workspace` with explicit feedback.
    - Account workspace home placeholder (while temporary) renders centered title and explicit "back" action (`pop()` only).
    - Tenant admin preview action reaches tenant public for the same tenant.
    - Tenant public workspace CTA reaches account workspace placeholder home when authorized.
    - Canonical URLs survive browser refresh with same host/scope context.
- [ ] ⚪ Pending Manual mobile validation:
  - `Mobile Landlord` opens directly in landlord area as default main scope.
  - `Mobile Tenant` opens directly in tenant public as default main scope.
  - Subscope transitions keep `EnvironmentType` stable on mobile.

## Dependencies
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-canonical-multi-tenant-routing.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-route-paths-refactor.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-endpoint-scope-separation.md`
