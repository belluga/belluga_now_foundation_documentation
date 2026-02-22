# TODO (V1): Tenant Admin Structure, Navigation, and Settings

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-14

## Objective
Establish a production-grade Tenant Admin information architecture and navigation flow, add the missing Settings area (based on documented admin settings contracts), and only then execute a visual/UI refactor of admin forms under Material 3 standards.

## References
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-landlord-admin-scope-access.md`
- `lib/application/router/modular_app/modules/tenant_admin_module.dart`
- `lib/presentation/tenant_admin/shell/tenant_admin_shell_screen.dart`
- `lib/presentation/tenant_admin/shell/tenant_admin_dashboard_screen.dart`
- `lib/domain/app_data/app_data.dart`

## Scope
- Restructure tenant-admin top-level navigation for mobile and wide layouts.
- Add a first-class admin `Settings` area in Flutter aligned with documented backend endpoints.
- Standardize tenant-switch behavior so admin feature data is refreshed/invalidated correctly.
- Improve error UX in admin list/form screens (friendly message + retry flow + optional technical detail).
- After structure is stable, refactor form visuals for stronger Material 3 quality and consistency.
- Align admin form interaction pattern with Material 3: full-screen routes for CRUD forms as default, avoid popup dialogs for core entity editing.
- Introduce slug UX pattern: auto-generate slug from name/label with manual override option.
- Improve form information hierarchy: media/avatar first when relevant, then identity and metadata fields.
- Rename top-level people menu label from `Pessoas` to `Accounts` in admin shell navigation.
- Standardize admin first-load list UX so loading is explicit (`null` stream state + `onNull`) instead of showing empty-state prematurely.
- Introduce paginated list controllers/repositories for admin large collections, aligned with tenant `Events` pagination pattern.

## Out of Scope (for initial structural phase)
- Laravel API contract expansion beyond endpoints already documented in `tenant_admin_module.md`.
- Full design-system overhaul across non-admin tenant modules.
- New business capabilities unrelated to admin information architecture/settings.

## Decisions
- [x] ✅ Production‑Ready Mobile admin navigation no longer uses 6+ bottom destinations; current shell was reduced to 5 top-level groups to restore usability.
- [x] ✅ Production‑Ready Detail/create/edit routes run in focused mode on mobile (shell chrome hidden for full-screen admin routes).
- [x] ✅ Production‑Ready Settings is now an explicit admin destination and route group.
- [x] ✅ Production‑Ready Form visual refactor completed after structure/navigation/settings stabilization.
- [x] ✅ Production‑Ready Core CRUD forms use full-screen routes; popups are now confirmation-only.
- [x] ✅ Production‑Ready Slugs are auto-generated from source fields with manual override option in slug-based forms.
- [x] ✅ Production‑Ready Media/avatar-first hierarchy applied across account/profile/static-asset creation and edit flows.
- [x] ✅ Production‑Ready Admin shell top-level menu now uses `Accounts` instead of `Pessoas`.

## Phase Plan

### Phase 1 — Structure and Navigation (first)
- [x] ✅ Production‑Ready Refactor admin IA into 5 stable top-level groups (Início, Accounts, Catálogo, Ativos, Config).
- [x] ✅ Production‑Ready Update shell route/title grouping for consolidated admin navigation.
- [x] ✅ Production‑Ready Ensure detail forms do not visually conflict with shell chrome on mobile.
- [x] ✅ Production‑Ready Document final route map in foundation docs after implementation.
- [x] ✅ Production‑Ready Rename top-level group label from `Pessoas` to `Accounts` across shell/bottom navigation and route titles.

### Phase 2 — Settings Area (still structural)
- [x] ✅ Production‑Ready Create controller/screen/route flow for admin settings (local preferences + remote CRUD + environment snapshot).
- [x] ✅ Production‑Ready Implement `GET/PATCH /admin/api/v1/settings/firebase` flow.
- [x] ✅ Production‑Ready Implement `PATCH /admin/api/v1/settings/push` flow.
- [x] ✅ Production‑Ready Implement `GET/POST/DELETE /admin/api/v1/settings/telemetry*` flow.
- [x] ✅ Production‑Ready Add Environment Snapshot section (read-only) sourced from current `AppData`.
- [x] ✅ Production‑Ready Add route integration and dashboard entry for Settings.

### Phase 3 — Tenant Switch Consistency
- [x] ✅ Production‑Ready Define and implement tenant-switch invalidation/reload policy (controllers now subscribe to `TenantAdminTenantScopeContract` and reset/reload scoped data).
- [x] ✅ Production‑Ready Ensure switching tenant refreshes list/form backing data from selected tenant scope in all admin features.
- [x] ✅ Production‑Ready Add integration-oriented widget/unit coverage for tenant switch + request target correctness (repository-level + controller matrix coverage expanded).
- [x] ✅ Production‑Ready Refactor admin list initial state to nullable stream (`StreamValue<List<T>?>`) with `StreamValueBuilder.onNullWidget` across admin list screens (Accounts, Organizations, Profile Types, Static Profile Types, Taxonomies, Taxonomy Terms, Static Assets).
- [x] ✅ Production‑Ready Introduce paginated Accounts flow (`fetchAccountsPage` + `loadNextPage`) and wire infinite scroll semantics in Accounts list screen.
- [x] ✅ Production‑Ready Add controller coverage for first-load-null and pagination behavior (page append + hasMore gate) plus compatibility updates across integration/unit fake repositories.
- [x] ✅ Production‑Ready Expand backend-driven paginated contracts/endpoints to remaining admin list domains (organizations, profile types, static profile types, taxonomies/terms, static assets) and add `loadNextPage` semantics per module.

### Phase 4 — Error UX Hardening
- [x] ✅ Production‑Ready Replace raw exception dumps in admin UI with standardized user-facing error components (lists, account detail, account-profile create/edit).
- [x] ✅ Production‑Ready Keep technical diagnostics available in controlled manner (debug/details affordance in standardized banner).
- [x] ✅ Production‑Ready Standardize retry and loading states across admin list screens.

### Phase 5 — Form Visual Refactor (after structure is done)
- [x] ✅ Production‑Ready Apply consistent Material 3 form layout primitives (section headers, spacing, grouping, action bars) in main admin forms.
- [x] ✅ Production‑Ready Standardize input, helper text, validation, and switch/toggle presentation in migrated forms.
- [x] ✅ Production‑Ready Remove duplicate primary actions per screen (single clear CTA strategy) in admin lists with FAB.
- [x] ✅ Production‑Ready Validate mobile ergonomics (keyboard, insets, touch targets, long forms).
- [x] ✅ Production‑Ready Replace popup-based CRUD editing with full-screen forms for admin entities (Material 3 flow consistency).
- [x] ✅ Production‑Ready Add reusable slug behavior (auto-suggest from primary label + user override lock) to relevant forms.
- [x] ✅ Production‑Ready Reorder form sections to prioritize profile/avatar media blocks where available.

### Phase 6 — Material 3 (10/10 Pursuit)
- [x] ✅ Production‑Ready Replace residual admin `ElevatedButton` usage with M3-consistent `FilledButton` / `FilledButton.tonal` patterns.
- [x] ✅ Production‑Ready Add responsive form width constraints (single-column max width on wide screens) while keeping mobile full-bleed ergonomics.
- [x] ✅ Production‑Ready Standardize confirmation dialogs through shared helper and action hierarchy (`TextButton` cancel + `FilledButton` confirm/destructive).
- [x] ✅ Production‑Ready Normalize empty-state UX across admin lists (message tone, iconography, CTA strategy, spacing tokens).
- [x] ✅ Production‑Ready Standardize admin density/touch targets for segmented controls and primary list actions.
- [x] ✅ Production‑Ready Add visual regression coverage (golden/screenshot baselines) for key admin states: list, empty, error, create/edit form.
- [x] ✅ Production‑Ready Run landlord flavor device QA pass focused on keyboard/insets, long-form scrolling, and tablet/wide breakpoints.

## Definition of Done
- [x] ✅ Production‑Ready Admin top-level navigation is clear, scalable, and stable across mobile/wide breakpoints.
- [x] ✅ Production‑Ready Settings area is available and functional for documented admin settings endpoints.
- [x] ✅ Production‑Ready Tenant switching reliably updates data scope and request destinations across admin features.
- [x] ✅ Production‑Ready Error handling in admin UI is user-friendly and operationally useful.
- [x] ✅ Production‑Ready Form visual refactor is completed after structural phases and follows Material 3 conventions.
- [x] ✅ Production‑Ready Material 3 “10/10” pass is complete (components, spacing, responsiveness, and interaction hierarchy are consistent across admin).
- [x] ✅ Production‑Ready Full-screen forms are used consistently for admin CRUD; popup/dialog forms are limited to confirmation-only actions.
- [x] ✅ Production‑Ready Slug generation UX is available and editable in slug-based entities.
- [x] ✅ Production‑Ready Top-level navigation label `Accounts` is applied in place of `Pessoas`.
- [x] ✅ Production‑Ready Flutter analyze and targeted tests pass for each phase before proceeding.

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze` (changed scope)
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shell/`
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shell/controllers/tenant_admin_shell_controller_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shared/tenant_admin_slug_utils_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin` (full tenant-admin presentation suite)
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_organizations_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_taxonomies_controller_test.dart`
- [x] ✅ Production‑Ready Device check (landlord flavor): tenant switch + settings navigation + core admin CRUD smoke flow.
- [x] ✅ Production‑Ready Device check (landlord flavor): Material 3 polish validation on phone + wide layout (forms, dialogs, lists, empty/error states).
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart` (pagination/null loading coverage)
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/accounts/tenant_admin_accounts_list_screen_test.dart` (onNull + load-more UI behavior)
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart` (includes first-load-null + loadNextPage coverage)
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart` (pagination params + multi-page aggregation)
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_account_profiles_controller_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_organizations_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_taxonomies_controller_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test --update-goldens test/presentation/tenant_admin/shared/tenant_admin_visual_regression_golden_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/shared/tenant_admin_visual_regression_golden_test.dart`
- [x] ✅ Production‑Ready Device integration run completed one-by-one (WSL-safe) for all `integration_test/*_test.dart` via checklist `.agent/test-run-progress.md`.
- [x] ✅ Production‑Ready Full unit test suite executed in chunks:
  - `fvm flutter test test/application test/event_model_test.dart test/widget_test.dart`
  - `fvm flutter test test/infrastructure`
  - `fvm flutter test test/presentation`

## Notes
- This TODO intentionally sequences architecture before cosmetics.
- Form UI improvements are expected in Phase 5 only, after structure/navigation/settings are stable and validated.
- Phase 2 Production Notes:
  - Delivered now: full remote wiring for settings endpoints (`firebase`, `push`, `telemetry`) via `TenantAdminSettingsRepositoryContract` + `TenantAdminSettingsRepository`.
  - Settings screen now includes editable remote sections with load/retry/save flows and telemetry integration management.
- Phase 3/4/6 Delivery Notes:
  - Tenant-scope subscriptions added to admin controllers to reset/reload scoped state on tenant change.
  - Remaining admin list domains now use controller-owned pagination (`loadNextPage`, `hasMore`, `isPageLoading`) with `onNull` first-load UX across Profile Types, Static Profile Types, Taxonomies, Taxonomy Terms, and Static Assets.
  - Confirmation dialogs standardized via shared `showTenantAdminConfirmationDialog`.
  - Empty states standardized via shared `TenantAdminEmptyState`.
  - Route map documented in `foundation_documentation/modules/tenant_admin_module.md`.
  - Device QA executed on Android ADB device with isolated one-by-one integration runs to prevent WSL disconnect losses.
  - Added visual regression baselines in `test/presentation/tenant_admin/shared/tenant_admin_visual_regression_golden_test.dart` covering list/empty/error/form states.
