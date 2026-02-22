# TODO (V1): Profile Type Registry CRUD (Tenant Admin)
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Active  
**Owners:** Laravel + Flutter Teams  
**Objective:** Define and implement CRUD for the tenant **Profile Type Registry** so admins can manage account profile types via the tenant-admin UI and backend.

---

## References
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-admin-data-layer-rebuild.md`

---

## A) Scope
- Add CRUD endpoints for Profile Type Registry under **tenant-admin** routes.
- Persist registry in a **dedicated tenant collection** named `account_profile_types` (single source of truth), not `TenantSettings`.
- Enforce uniqueness on `type` and validate payload bounds and `capabilities`.
- Update Flutter tenant-admin UI to manage registry entries (list/create/edit/delete).
- Update module docs + roadmap with endpoint definitions and statuses.
- Include a data migration/seeder path from legacy `TenantSettings.profile_type_registry` if present.
- Remove legacy `TenantSettings.profile_type_registry` after migration (no backward-compatibility).

---

## B) Tasks

### B1) API contract (documentation-first)
- [x] ✅ Production-Ready Define request/response schemas in `foundation_documentation/modules/tenant_admin_module.md`.
- [x] ✅ Production-Ready Add endpoint status entries in `foundation_documentation/system_roadmap.md`.
- [x] ✅ Production-Ready Capture enum/value definitions (capabilities, taxonomies) in the module doc.

### B2) Laravel implementation
- [x] ✅ Production-Ready Rename tenant collection to `account_profile_types` for consistency with endpoints/contracts.
- [x] ✅ Production-Ready Update migration + model + services + controller to use `account_profile_types` (indexes on `type` + capabilities retained).
- [x] ✅ Production-Ready Ensure any legacy data migration/seeder targets `account_profile_types` (not `profile_types`).
- [x] ✅ Production-Ready Migration for a dedicated tenant collection with indexes on `type` + capabilities.
- [x] ✅ Production-Ready Routes under `/admin/api/v1/account_profile_types` (tenant domain).
- [x] ✅ Production-Ready Controller + service to read/write the new collection.
- [x] ✅ Production-Ready Validation rules (type/label length, allowed taxonomies, capabilities booleans).
- [x] ✅ Production-Ready Ability enforcement (tenant admin scope; align with `account-users:*` or introduce explicit abilities).
- [x] ✅ Production-Ready Feature tests for CRUD + validation + permissions.
- [x] ✅ Production-Ready Migration/seed: move legacy `TenantSettings.profile_type_registry` into new collection if present.
- [x] ✅ Production-Ready Remove legacy `TenantSettings.profile_type_registry` from tenant settings after migration (no fallback reads).
- [x] ✅ Production-Ready Fix validation rules to use `App\Support\Validation\InputConstraints` (avoid missing class).
- [x] ✅ Production-Ready Expand Laravel feature tests to cover `account_profile_types` collection + CRUD + validation + permissions on this branch.

### B3) Flutter implementation (tenant-admin UI)
- [x] ✅ Production-Ready Repository contract + DTO updates (if needed) for CRUD.
- [x] ✅ Production-Ready Admin screens: list + create/edit form + delete confirmation.
- [x] ✅ Production-Ready Integrate into tenant-admin navigation.
- [x] ✅ Production-Ready `fvm flutter analyze` clean + targeted widget/controller tests if possible.

---

## C) Out of Scope
- Global marketplace defaults or landlord-managed registry templates.
- Profile type import/export or versioning.
- Bulk migration of existing profiles between types.

---

## D) Definition of Done
- [x] ✅ Production-Ready CRUD endpoints are documented and implemented for tenant admins.
- [x] ✅ Production-Ready Registry persists in a dedicated tenant collection with indexes on `type` + capabilities.
- [x] ✅ Production-Ready Flutter admin UI can list/create/update/delete types.
- [ ] ⚪ Tests cover validation and permission checks.

---

## E) Validation Steps
- [x] ✅ Production-Ready Laravel feature tests for CRUD.
- [x] ✅ Production-Ready Full Laravel test suite (docker).
- [x] ✅ Production-Ready `fvm flutter analyze`
