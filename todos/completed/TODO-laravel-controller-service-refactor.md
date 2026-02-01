# Title
Refactor Laravel controllers to services + fix DomainController telemetry bug

## Context
Multiple Laravel controllers contain domain query/formatting logic and direct model access that should live in Application services/query services for consistency and testability. DomainController telemetry uses an undefined variable in destroy paths.

## Scope
- [x] ✅ Production‑Ready — Move AccountProfilesController query/formatting logic into Application services.
- [x] ✅ Production‑Ready — Move EventsController query construction into EventQueryService.
- [x] ✅ Production‑Ready — Move EnvironmentController domain mapping into EnvironmentResolverService (or formatter).
- [x] ✅ Production‑Ready — Move LandlordBrandingController save/merge logic into a service.
- [x] ✅ Production‑Ready — Move AccountController direct lookups into AccountManagementService/AccountQueryService.
- [x] ✅ Production‑Ready — Move AccountRolesTemplatesController index query into a query service.
- [x] ✅ Production‑Ready — Move OrganizationsController direct lookups into query/management services.
- [x] ✅ Production‑Ready — Fix DomainController telemetry to use a defined domain identifier in destroy/forceDestroy.

## Delivery Stages
- [x] ✅ Production‑Ready — Production‑Ready (complete, hardened, ready for release)

## Provisional Notes (Required if Provisional)
- **Missing for production-ready:** N/A (aim for production-ready in one pass)
- **Revisit criteria:** N/A
- **Dependencies unblocked:** N/A

## Out of Scope
- [ ] Changes to API contracts or route structure.
- [ ] New endpoints or abilities.

## Decisions
- [ ] Use Application-layer Query/Management services as the single place for filtering, pagination, and formatting; controllers remain thin.

## Questions To Close
- [x] Any controller-specific logic that must remain in the controller due to framework constraints?

## Definition of Done
- [x] All listed controllers delegate to services/query services; no domain formatting or query construction in controllers.
- [x] DomainController destroy/forceDestroy telemetry uses a defined identifier.
- [x] Tests pass.

## Tests
- **Tests required?** Yes — controller behavior must be verified after refactor.
- **If yes, tiering:** Local‑only (Laravel feature tests).
- **Test plan:**
  - Update/add feature tests for AccountProfiles, Events, Environment, Branding, Account, AccountRolesTemplates, Organizations.
  - Regression test DomainController destroy/forceDestroy telemetry path.

## Commands (Run Locally)
- `php artisan test`

## Files Expected (Optional)
- `laravel-app/app/Http/Api/v1/Controllers/*`
- `laravel-app/app/Application/**`
