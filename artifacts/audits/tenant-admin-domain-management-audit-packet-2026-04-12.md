# Tenant Admin Domain Management Audit Packet (2026-04-12)

**Non-authoritative audit packet.** Derived for reviewer context only.

## Governing TODO
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-admin-domain-management.md`

## Scope Summary
- Added tenant-admin domain management (list/create/delete/restore) in Flutter settings.
- Added supporting Laravel list endpoint + status fields (already in branch).
- Removed hardcoded event-party type filter in tenant-admin event-management mapping (Flutter only), using `place_ref.id` instead of `party_type == venue`.
- Updated tenant-admin docs for domain settings and system roadmap endpoint tracking.

## Diff Summary
### Flutter (`flutter-app`)
- New domain model/DAO/screen/route and settings controller support.
- Tenant-admin settings hub adds “Domínios” card.
- Tenant-admin events mapping now excludes physical host by `place_ref.id` instead of `party_type == venue`.
- Build_runner output updated in `app_router.gr.dart`.
- Build_runner error removed `tenant_home_screen_test.mocks.dart` (see validation notes).

`git diff --stat` (Flutter):
```
lib/application/router/app_router.gr.dart          |  728 +++---
.../modular_app/modules/tenant_admin_module.dart   |    8 +
.../tenant_admin_settings_repository_contract.dart |   19 +
lib/domain/tenant_admin/tenant_admin_settings.dart |    1 +
.../tenant_admin_events_response_decoder.dart      |    6 +-
.../tenant_admin_settings_repository.dart          |   81 +
.../tenant_admin_events_controller.dart            |    2 +-
.../tenant_admin_settings_controller.dart          |  145 ++
.../screens/tenant_admin_settings_screen.dart      |   22 +
.../settings/tenant_admin_settings_keys.dart       |   16 +
.../shell/tenant_admin_shell_screen.dart           |    1 +
.../tenant_home_screen_test.mocks.dart             | 2700 --------------------
.../tenant_admin_settings_screen_test.dart         |   91 +
13 files changed, 761 insertions(+), 3059 deletions(-)
```

### Laravel (`laravel-app`)
`git diff --stat`:
```
.../Tenants/TenantDomainManagementService.php      | 10 +++
app/Http/Api/v1/Controllers/DomainController.php   | 15 ++++
config/abilities.php                               |  2 +
routes/api/tenant_api_v1.php                       | 15 +++-
.../Feature/Tenants/TenantDomainControllerTest.php | 93 ++++++++++++++++++++++
5 files changed, 131 insertions(+), 4 deletions(-)
```

### Foundation Docs (`foundation_documentation`)
`git diff --stat`:
```
modules/tenant_admin_module.md | 106 +++++++++++++++++++++++++++++++++++++++++
screens/modulo_tenant_admin.md |  26 ++++++----
system_roadmap.md              |   2 +
3 files changed, 126 insertions(+), 8 deletions(-)
```

## Validation / Tests
- `fvm flutter pub run build_runner build --delete-conflicting-outputs` failed:
  - `Bad state: No element` from mockito builder on `test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart`
  - `tenant_home_screen_test.mocks.dart` was deleted by build_runner.
- `bash delphi-ai/tools/laravel_tenant_access_guardrails_audit.sh --path laravel-app/routes/api/tenant_api_v1.php` failed due to pre-existing auth:sanctum routes without `CheckTenantAccess` in that file.
- No other tests executed.

## Key Files (Manual Review Targets)
- `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_domains_screen.dart`
- `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`
- `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
- `flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_domains_response_decoder.dart`
- `laravel-app/app/Http/Api/v1/Controllers/DomainController.php`
- `laravel-app/app/Application/Tenants/TenantDomainManagementService.php`
- `foundation_documentation/modules/tenant_admin_module.md`

