# TODO — Flutter Admin Accounts Card: show account name instead of slug

## scope
- Update Tenant Admin accounts list card UI to display `account.name` as the primary title text.
- Keep routing/navigation behavior unchanged (continue using `account.slug` for detail route).
- Keep existing subtitle/ownership information unchanged.

## out_of_scope
- No backend/API/contract changes.
- No filtering behavior changes.
- No redesign of card layout.

## definition_of_done
- Accounts list card title no longer displays slug as primary label.
- Card title displays account name (with safe fallback to slug only if name is empty).
- Code compiles without introducing architecture violations in screen/controller boundaries.

## validation_steps
- `fvm flutter analyze lib/presentation/tenant_admin/accounts/screens/tenant_admin_accounts_list_screen.dart`
- Manual check in Tenant Admin Accounts list:
  - Title shows name.
  - Tap still opens detail using slug route.

## decisions
- Use a local display variable in screen rendering only (`displayName`), keeping domain/controller untouched.
- Preserve slug usage for navigation and filtering to avoid side effects.
