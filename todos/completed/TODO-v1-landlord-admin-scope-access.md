# TODO (V1): Landlord-Scope Admin Access (Tenant Lockout + Landlord Entry)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed (`Validated manually across environments and reinforced by web smoke coverage`)
**Owner:** Flutter Team
**Date:** 2026-02-14

## Objective
Establish and preserve a strict access model where the admin area is available only through landlord scope, while tenant scope is hard-blocked from landlord/admin navigation.

## Landlord App Context (Explicit)
- In this V1 architecture, "landlord app" means the landlord runtime context/scope inside Flutter (landlord host or `EnvironmentType.landlord`), not tenant scope.
- Landlord entry surfaces:
  - Landlord auth entry (`Entrar como Admin`) in `flutter-app/lib/presentation/common/auth/screens/auth_login_screen/widgets/auth_login_canva_content.dart`.
  - Landlord home route/module in `flutter-app/lib/application/router/modular_app/modules/landlord_module.dart` (`/landlord`).
  - Admin shell route in landlord context via `TenantAdminShellRoute` (`/admin`) guarded by `LandlordRouteGuard`.
- Tenant app context must not expose or reach these surfaces.

## References
- `foundation_documentation/submodule_flutter-app_summary.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/todos/ephemeral/EPHEMERAL-landlord-admin-scope-separation.md`
- `flutter-app/lib/application/router/guards/landlord_route_guard.dart`
- `flutter-app/lib/presentation/common/auth/screens/auth_login_screen/widgets/auth_login_canva_content.dart`

## Scope
- Document the canonical landlord-scope admin access process.
- Make landlord app entry points explicit in documentation (landlord context, routes, and guard behavior).
- Deliver V1 landlord home experience with:
  - project landing section,
  - current tenant list,
  - login CTA,
  - admin-area CTA after login.
- Keep tenant profile free of landlord/admin mode entry points.
- Ensure shared auth UI shows `Entrar como Admin` only in landlord context.
- Enforce landlord route access by landlord host/environment only.
- Add tenant-selection gate to landlord admin shell before rendering admin menu content.
- Ensure selected tenant scope drives admin repository base URL resolution by tenant domain.

## Out of Scope
- Laravel API/contract changes.
- Tenant-admin feature redesign.
- New landlord dashboard UX beyond access control.

## Decisions
- [x] ✅ Production‑Ready Landlord/admin access is context-driven (`EnvironmentType.landlord` or landlord host), not local persisted mode/session in tenant context.
- [x] ✅ Production‑Ready Tenant scope must never reach landlord routes, including deep links.
- [x] ✅ Production‑Ready Tenant list source for landlord admin tenant picker: primary source `GET /admin/api/v1/tenants` (landlord domain), with bootstrap `AppData.domains` / `AppData.appDomains` as fallback/merge.
- [x] ✅ Production‑Ready Tenant picker renders one card per tenant (not per domain). Card title is tenant `name`; card subtitle is the selected `mainDomain` used for request routing.
- [x] ✅ Production‑Ready Tenant admin shell blocks menu/content until a tenant is explicitly selected by the landlord user.
- [x] ✅ Production‑Ready Selected tenant context switches admin item requests to the selected tenant domain (`https://{tenant-domain}/admin/api/...`).
- [x] ✅ Production‑Ready Tenant-route landlord entry resolves initial route to `TenantAdminShellRoute` when landlord session+mode are already active (without loading landlord home first).
- [x] ✅ Production‑Ready Tenant admin shell change-tenant affordance displays tenant `name` (not domain) for faster identification.
- [x] ✅ Production‑Ready Tenant admin shell auto-selects the single accessible tenant and hides tenant-switch actions when switching is not applicable.
- [x] ✅ Production‑Ready Tenant admin shell shows a neutral loading gate while resolving tenants, avoiding tenant-picker flicker before auto-selection.

## Landlord Scope Access Process
1. Start the app in landlord context:
   - `AppData.type == EnvironmentType.landlord`, or
   - current hostname equals `LANDLORD_DOMAIN` host.
2. Open the auth login screen.
3. Use `Entrar como Admin` (visible only in landlord context).
4. Authenticate via landlord credentials (`showLandlordLoginSheet`).
5. On success, navigate to `TenantAdminShellRoute`.
6. If context is tenant, `LandlordRouteGuard` denies landlord routes and redirects to `TenantHomeRoute`.

## Tasks
- [x] ✅ Production‑Ready Remove tenant-side admin entry from profile screen.
- [x] ✅ Production‑Ready Remove tenant profile auto-redirect behavior to admin shell.
- [x] ✅ Production‑Ready Restrict shared auth `Entrar como Admin` visibility to landlord context.
- [x] ✅ Production‑Ready Harden `LandlordRouteGuard` to landlord host/environment only.
- [x] ✅ Production‑Ready Update profile tests to reflect tenant-only profile behavior.
- [x] ✅ Production‑Ready Landlord home: replace placeholder with a branded landing section that explains Bóora! landlord mission/value.
- [x] ✅ Production‑Ready Landlord home: render current tenants list from bootstrap data with clear empty state.
- [x] ✅ Production‑Ready Landlord home: show `Entrar como Admin` button when there is no valid landlord session.
- [x] ✅ Production‑Ready Landlord home: show `Acessar área admin` button after landlord login/session and route to `TenantAdminShellRoute`.
- [x] ✅ Production‑Ready Landlord home: keep route/screen/controller architecture aligned (`StreamValue`, controller-owned state, pure UI screen).
- [x] ✅ Production‑Ready Landlord home: add/update widget tests covering login CTA visibility and admin CTA visibility.
- [x] ✅ Production‑Ready Tenant admin shell: introduce a tenant-selection gate UI that appears before navigation rail/bottom navigation.
- [x] ✅ Production‑Ready Tenant admin shell: resolve available tenants from bootstrap `AppData` and let landlord choose one target tenant.
- [x] ✅ Production‑Ready Tenant admin shell: enrich tenant list by querying landlord tenants endpoint and merging with bootstrap fallback.
- [x] ✅ Production‑Ready Tenant admin shell: list tenants by tenant identity (single card), showing `name` + `mainDomain`.
- [x] ✅ Production‑Ready Tenant admin shell: expose selected tenant context in controller/service state and allow re-selection flow.
- [x] ✅ Production‑Ready Tenant admin repositories: resolve request base URL from selected tenant domain for admin item requests.
- [x] ✅ Production‑Ready Tenant admin repositories: add automated test proving tenant switch changes subsequent request host/base URL (`tenant A` -> `tenant B`).
- [x] ✅ Production‑Ready Validation: ensure admin screens do not render menu/content while no tenant is selected.
- [x] ✅ Production‑Ready Tenant route guard: in landlord context, choose `TenantAdminShellRoute` as initial landlord destination when session+mode are active.
- [x] ✅ Production‑Ready Tenant admin shell: render change-tenant CTA label with selected tenant `name` instead of selected domain.
- [x] ✅ Production‑Ready Tenant admin shell: when exactly one tenant is available, auto-select it and hide tenant-switch controls.
- [x] ✅ Production‑Ready Tenant admin shell: while tenant scope is unresolved, show loading state (not tenant picker) until backend resolution completes.
- [x] ✅ Production‑Ready Add/refresh foundation module notes (tenant-admin + flutter submodule summary) in the documentation synchronization cycle.

## Definition of Done
- [x] ✅ Production‑Ready Tenant profile does not expose admin mode controls.
- [x] ✅ Production‑Ready Tenant-context auth screen does not expose admin entry.
- [x] ✅ Production‑Ready Landlord-context auth screen still supports admin login transition.
- [x] ✅ Production‑Ready Landlord routes are unreachable from tenant context.
- [x] ✅ Production‑Ready Landlord home exposes the four V1 surfaces (landing, tenants, login, admin-area-after-login).
- [x] ✅ Production‑Ready Landlord users with active session land directly in admin through guard-level initial route selection.
- [x] ✅ Production‑Ready Flutter analyze and landlord home tests pass after this landlord-home update.
- [x] ✅ Production‑Ready Landlord admin shell always requires tenant selection before rendering admin navigation/menu/content.
- [x] ✅ Production‑Ready Selected tenant drives tenant-admin repository domain/base URL for item requests.
- [x] ✅ Production‑Ready Single-tenant landlord users bypass tenant gate via auto-selection and do not see tenant-switch controls.
- [x] ✅ Production‑Ready Tenant-switch CTA shows selected tenant name (while keeping main domain visible in tenant cards).

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze` (tenant lockout pass)
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`
- [x] ✅ Production‑Ready `rg -n "Modo Admin|Acesso landlord|Entrar como Admin" flutter-app/lib/presentation/tenant`
- [x] ✅ Production‑Ready `rg -n "hasLandlordSession|isLandlordMode|hasLandlordSession && isLandlordMode" flutter-app/lib/application/router/guards/landlord_route_guard.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/landlord/home/screens/landlord_home_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/application/router/guards/tenant_route_guard_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shell/controllers/tenant_admin_shell_controller_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter analyze` after tenant-scope gate + tenant-domain request routing.
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart` (assert request URL changes after tenant switch).
- [x] ✅ Production‑Ready Manual verification:
  - entering `/admin` first shows tenant selection gate,
  - selecting a tenant unlocks admin menu,
  - switching/clearing tenant returns to gate,
  - admin item requests target selected tenant domain (`{tenant-domain}/admin/api/...`).
- [x] ✅ Production‑Ready Visual/manual verification on landlord route (`/landlord`) for:
  - landing section visible,
  - tenant list visible,
  - login CTA before session,
  - admin CTA after login.

## Validation Results
- `fvm flutter analyze` returned `No issues found!`.
- Targeted profile test suite passed.
- Landlord home widget tests passed (`landlord_home_screen_test.dart`).
- Tenant presentation grep for admin strings returned no matches.
- Landlord guard grep confirmed no local mode/session bypass condition remains.
- Landlord home contract doc added at `foundation_documentation/screens/modulo_landlord_app.md`.
- Tenant admin shell now enforces tenant selection before rendering admin menu/content.
- Tenant admin repositories now route item requests to `https://{selected-tenant-domain}/admin/api/...`.
- Tenant picker now loads tenants from landlord endpoint (`/admin/api/v1/tenants`) with bootstrap fallback.
- Tenant picker now renders one card per tenant (`name`) and displays `mainDomain` as card subtitle.
- Tenant route guard now chooses landlord initial route (`/landlord` vs `/admin`) based on landlord session+mode, eliminating landlord-home-first navigation when admin is already accessible.
- Tenant admin shell now labels change-tenant actions with selected tenant `name` and hides switch controls when only one tenant is available.
- Tenant admin shell controller now auto-selects tenant scope when exactly one tenant is available.
- Tenant admin shell now keeps tenant picker hidden during backend tenant resolution and displays a loading gate until the final tenant scope decision is ready.
- Targeted repository tests passed:
  - `test/infrastructure/repositories/landlord_tenants_repository_test.dart`
  - `test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart`
  - `test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
- Targeted presentation/controller tests passed:
  - `test/presentation/tenant_admin/shell/controllers/tenant_admin_shell_controller_test.dart`
  - `test/presentation/landlord/home/screens/landlord_home_screen_test.dart`
  - `test/application/router/guards/tenant_route_guard_test.dart`
  - `test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart`

## Completion Note
- `2026-03-07`: Manual validation across environments was completed, and the guarded `/admin` / `/landlord` flows also remained covered by the later web smoke hardening. The landlord-only admin entry contract is now treated as settled baseline behavior.
