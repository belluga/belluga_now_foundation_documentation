# Bounded Review Package: Tenant Admin Domain Management (Plan)

## Scope
- Add tenant-admin domain list API with status (active + deleted).
- Add tenant-admin settings UI at `/admin/settings/domains` to list/create/delete/restore.
- Update tenant-admin module documentation.

## Decisions (Frozen Candidate)
- `D-01`: Dedicated settings route `/admin/settings/domains`.
- `D-02`: Domain list API includes deleted domains with `status`.

## Assumptions Preview (Key)
- Tenant-admin owns domain management (no landlord-area UX changes).
- Project constitution is missing; any cross-module invariant updates require Strategic handoff.

## Execution Plan Summary
1. Add `GET /admin/api/v1/domains` in Laravel controller/service and tests.
2. Update Laravel tests for list/create/delete/restore + status.
3. Add Flutter domain models/repository + controller state.
4. Add new settings route and screen for domain list + actions.
5. Update tenant-admin module docs.
6. Run targeted tests + analyzer.

## Validation Targets
- `php artisan test --filter TenantDomainControllerTest`
- `fvm dart test test/features/tenant_admin/settings`
- `fvm dart analyze --format machine`

## Boundaries / Non-goals
- No event management filtering in this TODO.
- No tenant resolution or landlord-area changes.
