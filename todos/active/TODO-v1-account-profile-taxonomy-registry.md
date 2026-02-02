# TODO (V1): Taxonomy Registry + Terms (Account Profiles, Static Assets, Events)
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Draft  
**Owners:** Laravel + Flutter + Docs  
**Objective:** Establish WP-like taxonomies/terms for Account Profiles, Static Assets, and Events using tenant-admin CRUD, while keeping `taxonomy_terms` as `{type, value}` pairs.

---

## References
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/modules/map_poi_module.md`

---

## Decisions (Confirmed)
- `taxonomy_terms[]` stays `{ type, value }` where `type` = taxonomy slug and `value` = term slug.
- Taxonomy `icon` is a **Material icon name string** (e.g., `mode_subscription`).
- Taxonomy `color` is HEX **`#RRGGBB` only**.
- `applies_to` supports `account_profile`, `static_asset`, `event`.
- Terms are managed only under `/terms` endpoints (no embedding in `/taxonomies` list).
- Term update/delete uses `term_id`.
- Taxonomy CRUD routes live under `/admin/api/v1` and are **tenant-scoped** (tenant domain + tenant/landlord middleware). This does **not** overlap with landlord admin scope even though the path prefix is shared.
- Static Assets remain a **separate tenant-managed entity** (no accounts). We will **not** allow Account Profiles without Accounts.
- Static Assets reuse a **shared profile page schema** (page fields + taxonomy/tags/categories) but remain distinct from Account Profiles in persistence.
- Introduce a **static profile type registry** (e.g., `static_profile_types`) in tenant settings, separate from `account_profile_types`.

## Questions To Close
- None (decisions locked).

---

## A) Scope

### A1) Contracts + Documentation
- Define taxonomy + term schemas and enum values in:
  - `domain_entities.md`
  - `modules/tenant_admin_module.md`
  - `endpoints_mvp_contracts.md`
- Add new endpoints to `system_roadmap.md` with status tracking.
- Ensure taxonomy endpoint paths use `/admin/api/v1` and explicitly note tenant scope vs landlord admin scope.

### A2) Laravel (tenant-admin)
- Add tenant collections: `taxonomies`, `taxonomy_terms`.
- Implement CRUD:
  - `GET/POST /admin/api/v1/taxonomies`
  - `GET/PATCH/DELETE /admin/api/v1/taxonomies/{taxonomy_id}`
  - `GET/POST /admin/api/v1/taxonomies/{taxonomy_id}/terms`
  - `PATCH/DELETE /admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}`
- Validation:
  - `slug` + `name` bounds (InputConstraints)
  - `icon` optional (Material icon name string)
  - `color` optional, regex `^#[0-9A-Fa-f]{6}$`
  - `applies_to` values enforced
- Enforce registry validation for `taxonomy_terms` on:
  - Account Profile create/update
  - Static Asset create/update
  - Event create/update
- Enforce `account_profile_types.allowed_taxonomies` for Account Profile taxonomy terms.
- Add Static Asset module (if missing) with:
  - `static_assets` collection + model + service layer.
  - Tenant-admin CRUD under `/admin/api/v1/static_assets`.
  - Public read endpoint for page consumption (e.g., `/api/v1/static_assets/{slug}`).
  - `static_profile_types` registry management in tenant settings (parallel to `account_profile_types`).
  - Taxonomy validation via `TaxonomyValidationService::assertTermsAllowedForStaticAsset`.

### A3) Flutter (tenant-admin UI)
- Add DTOs/repositories for taxonomies + terms.
- Tenant-admin screens to manage taxonomies and terms.
- Update Account Profile / Static Asset / Event forms to pick terms from registry (no free text).

---

## B) Out of Scope
- Public (tenant) taxonomy browsing UI.
- Auto-tagging or ML-driven taxonomy suggestions.
- Cross-tenant global taxonomy templates.

---

## C) Definition of Done
- CRUD endpoints documented and implemented.
- Registry validation blocks invalid taxonomy terms in Account Profiles, Static Assets, Events.
- Static Assets module live with type registry and page payloads.
- Tenant-admin UI can manage taxonomies + terms.
- Roadmap updated with endpoint statuses.

---

## D) Validation Steps
- `php artisan test` (targeted: taxonomy CRUD + validation + account profile/static asset/event validation).
- `fvm flutter analyze`
- Flutter widget/integration tests for taxonomy CRUD + form selection.

---

## Execution Notes (2026-02-02)
- Laravel: static assets module + static profile type registry implemented, taxonomy validation wired for static assets, public static asset page endpoint added (id or slug).
- Docs: tenant-admin routes standardized to `/admin/api/v1`, static asset schema updated, roadmap updated.
- Tests: ran `php artisan test` in Docker for static assets + static profile types + taxonomy registry (all passing).
- Flutter: pending (tenant-admin UI + form selection updates).
