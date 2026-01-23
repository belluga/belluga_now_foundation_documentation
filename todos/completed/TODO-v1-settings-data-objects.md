# TODO (V1): Tenant Settings Data Objects (No Model Array Casts)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Backend Team  
**Objective:** Remove array casting from TenantSettings models and move normalization into dedicated Data Objects for tenant settings payloads.

---

## References
- `foundation_documentation/modules/system_architecture_principles.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/submodule_laravel-app_summary.md`

---

## A) Scope
- Eliminate array casts/accessors inside `TenantSettings` (models must expose raw BSON payloads).
- Introduce Data Objects to normalize tenant settings payloads (`map_ui`, `profile_type_registry`).
- Refactor services (EnvironmentResolver, MapPoiQueryService, EventQueryService, AccountProfileRegistryService/Seeder) to use Data Objects.
- Preserve existing response shapes for environment payloads and map/event filters.

---

## B) Tasks

### B1) Data Objects (Settings)
- [x] ✅ Production‑Ready Create `App\DataObjects\Settings` helpers for:
  - map UI radius + time window + default location.
  - profile type registry normalization.

### B2) Service refactor
- [x] ✅ Production‑Ready Replace `TenantSettings` array accessors with Data Objects in:
  - EnvironmentResolverService (profile types + map_ui payload).
  - MapPoiQueryService (radius + time window defaults).
  - EventQueryService (radius defaults).
  - AccountProfileRegistryService/Seeder (registry normalization).

### B3) Model cleanup
- [x] ✅ Production‑Ready Remove `TenantSettings` array-casting accessors/normalizers.

### B4) Validation
- [x] ✅ Production‑Ready Run full suite: `php artisan test` (Docker).

---

## C) Definition of Done
- [x] ✅ Production‑Ready No array casts in models for tenant settings.
- [x] ✅ Production‑Ready Data Objects own settings normalization.
- [x] ✅ Production‑Ready Environment + map/event services behave with unchanged payload contracts.
- [x] ✅ Production‑Ready Full test suite passes.

---

## D) Out of Scope
- Refactoring other models to Data Objects beyond tenant settings.
- Broad DTO/contract changes for non-settings payloads.

---

## E) Validation Steps
- [x] ✅ Production‑Ready `docker compose exec -T app php artisan test`
