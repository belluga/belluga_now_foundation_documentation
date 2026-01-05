# TODO (V1): Tenant Settings Unification Plan

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Split tenant settings endpoints by domain (push, firebase, telemetry), add telemetry add/remove endpoints with unique type enforcement, and rename the collection to a unified `settings` collection.

---

## Scope
- Define endpoints for:
  - Push settings (separate endpoint).
  - Firebase settings (separate endpoint).
  - Telemetry add/remove endpoints.
- Enforce telemetry `type` uniqueness (no duplicate types in the telemetry array).
- Rename the collection from `tenant_push_settings` to `settings` (with migration plan).
- Update validation and tests to match the new endpoints and behavior.
- Update README/docs for the new endpoints.

## Out of Scope
- Flutter UI changes.

## Definition of Done
- [x] ✅ New endpoints defined for push, firebase, telemetry add/remove.
- [x] ✅ Telemetry type uniqueness enforced (validation + tests).
- [x] ✅ Existing `/settings/push` behavior refactored into domain endpoints.
- [x] ✅ Collection rename implemented (`tenant_push_settings` → `settings`).
- [x] ✅ Tests cover new endpoints and validation errors.
- [x] ✅ README updated with new endpoint contracts.

## Validation Steps
- [x] ✅ Full test suite green (`php artisan test`).

## Decisions
- None yet.

## Questions to Close
- Should we keep `/settings/push` as an alias or perform a hard cutover to the new endpoints?
- Should telemetry add/remove be PATCH + DELETE or POST + DELETE?

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/TenantPushSettings.php`
- `foundation_documentation/system_roadmap.md`
