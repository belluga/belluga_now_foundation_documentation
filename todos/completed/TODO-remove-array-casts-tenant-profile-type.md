# TODO (V1): Remove Array Casts from TenantProfileType

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Backend Team  
**Objective:** Remove Eloquent array casts from the Mongo-backed `TenantProfileType` model to rely on native BSON types.

---

## A) Scope
- Remove `$casts` for `allowed_taxonomies` and `capabilities` in `TenantProfileType`.
- Keep all other behavior unchanged.

## B) Out of Scope
- Data normalization/migration of existing stringified records.
- Any controller/service changes.

## C) Tasks
- [x] ✅ Production‑Ready Remove array casts in `laravel-app/app/Models/Tenants/TenantProfileType.php`.

## D) Definition of Done
- [x] ✅ Production‑Ready `TenantProfileType` no longer defines array casts.
- [x] ✅ Production‑Ready No other model changes.

## E) Validation
- [ ] 🟡 Provisional `php artisan test` (or targeted suite) as needed.
