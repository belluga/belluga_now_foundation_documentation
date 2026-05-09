# TODO (VNext): Account Profile Types Expansion

**Status:** Completed  
**Owners:** Delphi  
**Date:** `2026-04-18`

## Closure Note
This file no longer represents the correct deferred owner. The profile-type registry is already dynamic in runtime/admin flows, and future evolution is no longer expected to happen through a generic "expand types" program by default. The canonical direction is capability-first.

## Confirmed Evidence
- Backend already exposes dynamic account-profile-type management surfaces:
  - `../laravel-app/routes/api/tenant_api_v1.php`
  - `../laravel-app/app/Models/Tenants/TenantProfileType.php`
  - `../laravel-app/database/migrations/tenants/2026_01_29_000300_create_profile_types_collection.php`
- Flutter already contains tenant-admin routes/controllers/screens for account-profile-type management:
  - `../flutter-app/lib/application/router/modular_app/modules/tenant_admin_module.dart`
  - `../flutter-app/lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart`
  - `../flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart`
- Project authority now treats future growth as capability-first rather than as generic type proliferation:
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/modules/tenant_admin_module.md`

## Residual Note
- Future behavior expansion should open under capability-owned or concrete feature TODOs, not under a generic profile-type expansion umbrella.
- If a truly new type boundary later becomes necessary, it should be justified by a concrete capability/module contract rather than by label growth alone.
