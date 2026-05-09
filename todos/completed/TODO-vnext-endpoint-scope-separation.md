# TODO (VNext): Endpoint Scope Separation (Tenant Public vs Tenant Admin vs Account Admin)

**Status:** Completed  
**Owners:** Platform + Laravel Team  
**Date:** `2026-04-18`

## Closure Note
The original objective was materially absorbed by the current route topology and by promoted route/scope governance. This file no longer represents a live delivery owner.

## Confirmed Evidence
- Public/account/admin route ownership is already split in Laravel:
  - `../laravel-app/routes/api/project_tenant_public_api_v1.php`
  - `../laravel-app/routes/api/project_tenant_admin_api_v1.php`
  - `../laravel-app/routes/api/project_account_api_v1.php`
- Route/scope governance is already canonicalized in project authority:
  - `foundation_documentation/policies/scope_subscope_governance.md`
  - `foundation_documentation/system_roadmap.md`
- Public/private account-profile behavior already has Laravel test coverage:
  - `../laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`

## Residual Note
- Future module-specific expansions of public/admin/account surfaces should live in their concrete capability TODOs, not in this retired umbrella.
