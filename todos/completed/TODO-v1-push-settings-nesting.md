# TODO (V1): Nest Push Settings in Document Schema

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (✅ Production‑Ready)  
**Owners:** Backend Team (source of truth)  
**Objective:** Move push-specific fields into the `push` object in the settings document.

---

## Scope
- Nest the following under `push` in the stored settings document:
  - `max_ttl_days` → `push.max_ttl_days`
  - `push_message_routes` → `push.message_routes`
  - `push_message_types` → `push.message_types`
- Update all reads/writes to use the new nested paths.
- Remove legacy reads; no backward compatibility.
- Update tests and README to reflect the new schema.

## Out of Scope
- Changing endpoint behavior or contracts beyond internal storage shape.
- Flutter/client changes.

## Definition of Done
- [x] ✅ Settings document persists push fields under `push`.
- [x] ✅ Legacy reads removed; only nested fields are supported.
- [x] ✅ Tests updated for new storage paths.
- [x] ✅ README updated to describe the stored schema.

## Validation Steps
- [x] ✅ Push feature tests pass. (`docker compose exec app php artisan test --filter=PushMessageFlowTest`)

## Decisions
- Move root fields into `push` and rename nested keys to `message_routes` / `message_types` and `max_ttl_days`.
- Do not support backward compatibility for legacy root fields.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/TenantPushSettings.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Controllers/Tenant/TenantPushRouteTypesController.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Controllers/Tenant/TenantPushMessageTypesController.php`
