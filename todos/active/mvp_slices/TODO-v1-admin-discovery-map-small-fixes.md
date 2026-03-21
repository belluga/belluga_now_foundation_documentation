# TODO (V1): Admin + Discovery + Map Small Fixes

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Flutter Team, Laravel Team  
**Objective:** Fix small-but-blocking MVP issues across Tenant Admin, Discovery, and Map before release freeze.
**Promotion lane path:** `dev -> stage -> main` (when scope requires production rollout)

---

## Delivery Flow (Local vs Promotion)
- `🟧 Local-Implemented`: delivered in a feature/fix branch with local validation evidence; not yet merged in promotion lane.
- `🟣 Lane-Promoted`: merged through the lane threshold defined for this TODO (minimum: `dev`).
- `✅ Production-Ready`: only after required promotion targets are completed (`stage`/`main` when applicable) and confidence gates are satisfied.
- Never mark items as `✅ Production-Ready` based only on feature-branch status.

## Promotion Evidence (Required)
| Workstream | Local Branch / Commit | PR to `dev` | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| A — Admin Search | `belluga_now_docker/flutter-app@feature/v1-priority-h1-h3-admin-event-host-poi (diagnostic audit local)` | `<pending>` | `<pending>` | `<pending>` | `🟡 Provisional` |
| B — Unmanaged Type Edit | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `⚪ Pending` |
| C — Map Icon/Color Config | `belluga_now_docker/flutter-app@feature/v1-priority-h1-h3-admin-event-host-poi (local C.5 implementation, uncommitted)` | `<pending>` | `<pending>` | `<pending>` | `🟡 Provisional` |
| D — Discovery Truncation | `belluga_now_docker/flutter-app@feature/v1-priority-h1-h3-admin-event-host-poi (diagnostic audit local)` | `<pending>` | `<pending>` | `<pending>` | `🟡 Provisional` |
| E — Visual Improvements | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `⚪ Pending` |
| F — Validation and Tests | `flutter-app local test evidence (H + C.5 + static-assets taxonomy cleanup); laravel-app local test evidence (H + static-assets/map-poi)` | `<pending>` | `<pending>` | `<pending>` | `🟡 Provisional` |
| H — Event Form + Host Eligibility | `flutter-app@feature/v1-priority-h1-h3-admin-event-host-poi: bc75ded (+local H3.2 fallback removal); laravel-app@feature/v1-priority-h1-h3-admin-event-host-poi: daba0bc` | `Flutter PR #139; Laravel PR #99` | `Flutter PR #140; Laravel PR #100` | `<pending>` | `🟣 Lane-Promoted (H1/H2/H3.1/H3.3/H3.4/H3.5) + 🟧 Local-Implemented (H3.2)` |

---

## References
- `delphi-ai/templates/todo_template.md`
- `foundation_documentation/todos/README.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

---

## Scope
- Fix Tenant Admin account/profile search behavior.
- Allow editing account/profile type for unmanaged entities in Tenant Admin.
- Remove hardcoded map icon/color behavior and make visuals configurable (or use generic fallback strategy).
- Fix Discovery list truncation so users can access the full expected dataset.
- Execute a small visual polish pass on affected screens.

## Out of Scope
- New major IA/UX redesign for admin, map, or discovery.
- Net-new MVP capabilities unrelated to these defects.
- Backend schema redesign for map POI core model.

---

## Complexity Triage (Simple vs Decision-Heavy)

### Simple and Objective (can execute directly)
| Ref | Task | Why simple/objective |
| --- | --- | --- |
| A.1 | Reproduce/document failing search paths | Diagnostic and evidence collection only. |
| A.2 | Verify filter/query propagation Flutter -> backend | Contract traceability check with clear expected output. |
| D.1 | Audit discovery limits/truncation source | Technical root-cause audit with measurable result. |
| F.1-F.4 | Tests and targeted regression runs | Execution-focused validation work. |
| H1.1-H1.3 | Type description optional (Flutter/backend/payload) | Clear contract: description is optional. |
| H2.1-H2.3 | Event/occurrence description optional | Clear contract: `content` optional; date rules unchanged. |
| H3.2 | Remove `venues` fallback; use only `physical_hosts` | Explicit compatibility removal decision already made. |
| H3.5 | Update venue wording to physical-host wording | UI terminology alignment with defined contract. |
| C.5 | Allow manual `#RRGGBB` input in Branding edit color picker with live preview | Clear UX/contract requirement with deterministic validation. |
| D.6 | Restrict Discovery chips/categories to favoritable profile types only | Deterministic rule driven by `profile_types.capabilities.is_favoritable`. |

### Needs Decision/Alignment First (before implementation)
| Ref | Task | Decision needed |
| --- | --- | --- |
| A.3-A.4 | Search criteria + refresh/clear lifecycle behavior | Final UX behavior on clear/pagination/reload interactions. |
| B.1-B.4 | Unmanaged type edit flow + guardrails | Exact eligibility semantics and backend enforcement boundaries. |
| C.1-C.4 | Map icon/color hardcoding removal | Source-of-truth config, fallback policy, runtime refresh semantics. |
| D.2-D.4 | Discovery completeness behavior | Canonical contract choice: paging/infinite/complete fetch semantics. |
| D.5 | Favorites mutation for identified users only | Define backend mutation contract and explicit auth/identity gate behavior for anonymous users. |
| D.7 | Enforce non-admin account profile privacy boundary in Discovery/public endpoint | Decide explicit public-visibility contract (field/policy) before enforcing backend filter. |
| E.1-E.3 | Visual polish pass | Subjective UX priorities and acceptance thresholds. |
| Parking Lot | Fallback image policy | Product decision by definition. |

### Suggested Execution Sequence
1. Deliver the objective set first (`H1`, `H2`, `H3.2`, `H3.5`, plus diagnostics `A.1`, `A.2`, `D.1`).
2. Close remaining decision-heavy items (`B/C/D/E`).
3. Execute `F` validation after each decision-heavy batch.

---

## A) Workstream: Admin Search Not Working Properly

### Tasks
- [ ] 🟧 Local-Implemented — Reproduce and document failing search paths (Account list, Account Profile list/detail selectors).
- [ ] 🟧 Local-Implemented — Verify request/query propagation from Flutter filters/search to backend endpoints.
- [ ] ⚪ Fix search criteria application and result refresh lifecycle.
- [ ] ⚪ Ensure clear-search restores baseline dataset correctly.

### Diagnostic Evidence (2026-03-21)
- Flutter account-list search is local-only over already loaded pages (`flutter-app/lib/presentation/tenant_admin/accounts/screens/tenant_admin_accounts_list_screen.dart`, `_filterAccounts`), so unloaded pages are never queried.
- Flutter controller updates only local search state (`flutter-app/lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart`, `updateSearchQuery`) and does not trigger backend fetch by query.
- Accounts repository request does not propagate any text-search filter (`flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart`, `_fetchFilteredAccountsPage` sends `page`, `page_size`, `ownership_state` only).
- Backend query layer supports field-level filters/search (`laravel-app/app/Application/Shared/Query/AbstractQueryService.php`), but Flutter does not currently send those fields for account search.
- Additional pagination mismatch observed: Flutter sends `page_size`, while Laravel account/profile index controllers read `per_page` by default.

### Acceptance Criteria
- [ ] ⚪ Search by name/identifier returns expected matches.
- [ ] ⚪ Search state is consistent after pagination/reload.
- [ ] ⚪ Empty/loading/error states remain correct while searching.

### Search Decision Baseline (2026-03-21)
- Admin search must be backend-first (not local-only over loaded pages) for paginated lists.
- Canonical query parameter for pagination is `per_page` across Flutter requests targeting Laravel index controllers.
- Search interaction contract:
  - debounce text input in Flutter;
  - on query change, reset list to page `1`;
  - clear-search restores baseline dataset by issuing a new backend request (not only local state reset).
- Field strategy:
  - Account list: support text search across `name`, `slug`, and `document.number`.
  - Discovery/account profiles: support text search across `display_name` and `slug`; taxonomy/tag expansion remains explicit scope item when required.

---

## B) Workstream: Edit Type for Unmanaged Accounts/Profiles

### Tasks
- [ ] ⚪ Define exact eligibility rule for editing type (`unmanaged` only).
- [ ] ⚪ Add edit flow in Tenant Admin UI for unmanaged account/profile type.
- [ ] ⚪ Wire update persistence and refresh list/detail projections after save.
- [ ] ⚪ Keep managed/tenant-owned guardrails explicit in UI and backend validation.

### Acceptance Criteria
- [ ] ⚪ Unmanaged entities can update type successfully.
- [ ] ⚪ Ineligible entities are blocked with clear feedback.
- [ ] ⚪ Updated type is reflected consistently in list/detail/filter surfaces.

---

## C) Workstream: Map Icon/Color Hardcoding Removal

### Tasks
- [ ] ⚪ Identify hardcoded icon/color mappings currently applied in map filter/marker UI.
- [ ] ⚪ Replace key-based hardcoding with configurable source (catalog/settings payload).
- [ ] ⚪ Define fallback strategy when icon/color metadata is absent (generic, non-keyed fallback).
- [ ] ⚪ Ensure runtime refresh reflects admin-config changes without code edits.
- [ ] 🟧 Local-Implemented — In Branding/Visual Identity edit flow, allow manual `#RRGGBB` input in color picker modal and keep picker/preview synced with typed value.
- [ ] 🟧 Local-Implemented — Remove preset color chips from Branding color picker modal (keep a single canonical editable hex input).

### Acceptance Criteria
- [ ] ⚪ Map filter/icon visuals are driven by configuration, not hardcoded category keys.
- [ ] ⚪ Color behavior is consistent and does not regress selected-state contrast.
- [ ] ⚪ Fallback visuals are stable and deterministic.
- [ ] 🟧 Local-Implemented — In Branding/Visual Identity edit flow, color picker modal accepts manually typed valid `#RRGGBB` values and updates picker/preview immediately.
- [ ] 🟧 Local-Implemented — Branding color picker modal no longer renders preset chips.

---

## D) Workstream: Discovery Shows Only Few Items

### Tasks
- [ ] 🟧 Local-Implemented — Audit current Discovery fetch/pagination limits and identify truncation source.
- [ ] ⚪ Align list loading with canonical dataset expectations (pagination/infinite scroll or complete fetch by contract).
- [ ] ⚪ Ensure filter/search interactions do not silently drop valid items.
- [ ] ⚪ Validate interaction with favorites state and profile-type registry filtering.
- [ ] ⚪ Establish favorites mutation flow with backend persistence and enforce mutation access for identified users only (anonymous users must be blocked and redirected to auth).
- [ ] ⚪ Restrict Discovery filter chips/categories to profile types where `capabilities.is_favoritable=true`.
- [ ] ⚪ Enforce non-admin/public account-profile listing to return only public profiles (block private profile leakage in Discovery source endpoint).

### Diagnostic Evidence (2026-03-21)
- Discovery loads partners through a single fetch path (`flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`, `_loadPartners -> fetchAllAccountProfiles`).
- Repository delegates to Laravel backend fetch with no pagination override (`flutter-app/lib/infrastructure/repositories/account_profiles_repository.dart` and `flutter-app/lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart`).
- Laravel account-profile endpoints default to `per_page=15` when query param is absent (`laravel-app/app/Http/Api/v1/Controllers/AccountProfilesController.php`), causing first-page-only dataset in Discovery.
- UI sections intentionally cap highlighted carousels (`take(10)`), which amplifies the perception of truncation when source dataset is already capped.
- Discovery chip source currently uses `enabledAccountProfileTypes()` (all enabled types) instead of favoritable-only filter (`flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`, `_updateAvailableTypes`).
- Non-admin/public endpoint (`AccountProfilesController::publicIndex`) currently applies allowed-type filtering only (`publicPaginate`) and does not enforce an explicit public-visibility boundary, allowing private-profile leakage if present (`laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php`).

### Acceptance Criteria
- [ ] ⚪ Discovery displays full expected dataset for the active query/filter context.
- [ ] ⚪ No silent cap at low item count.
- [ ] ⚪ Scrolling/loading behavior is predictable and stable.
- [ ] ⚪ Favorite toggle persists across reloads/sessions and remains consistent with server state.
- [ ] ⚪ Anonymous users cannot mutate favorites; authenticated identified users can.
- [ ] ⚪ Discovery chips/categories only show favoritable account-profile types.
- [ ] ⚪ Non-admin Discovery source endpoint excludes private profiles from returned data.

---

## E) Workstream: Visual Improvements (Targeted)

### Tasks
- [ ] ⚪ Apply targeted polish on affected screens (spacing, hierarchy, contrast, state clarity).
- [ ] ⚪ Review selected/unselected visual states in map/discovery actions.
- [ ] ⚪ Review admin form/list visual consistency after functional fixes.

### Acceptance Criteria
- [ ] ⚪ No visual regressions in affected components.
- [ ] ⚪ Interaction states are visually clear.
- [ ] ⚪ Layout remains stable on common mobile breakpoints.

---

## F) Validation and Test Plan
- [ ] ⚪ Add/adjust unit/widget tests for admin search and unmanaged type edit flows.
- [ ] ⚪ Add/adjust tests for discovery completeness/pagination behavior.
- [ ] ⚪ Add/adjust map UI tests for configurable icon/color behavior and fallback path.
- [ ] ⚪ Run targeted regression suite for Home/Discovery/Map/Admin impacted surfaces.
- [ ] 🟧 Local-Implemented — Add Flutter tests for `H1/H2`: type and event forms submit without description (`description/content` optional).
- [ ] 🟧 Local-Implemented — Add Laravel request/feature tests for `H1/H2`: create/update accepts missing `description/content` and preserves existing validation rules.
- [ ] 🟧 Local-Implemented — Add Flutter + Laravel contract tests for `H3`: host candidates use POI capability + valid location and persist `place_ref.type=account_profile`.
- [ ] ⚪ Add favorites regression tests (Flutter + Laravel): mutation is blocked for anonymous users and allowed only for authenticated identified users.

---

## G) Definition of Done
- [ ] ⚪ Admin search works correctly in all affected Tenant Admin flows.
- [ ] ⚪ Unmanaged type edit is available and guarded correctly.
- [ ] ⚪ Map icon/color rendering is configuration-driven (or generic fallback), without hardcoded category coupling.
- [ ] ⚪ Discovery no longer truncates to a small subset unexpectedly.
- [ ] ⚪ Visual polish pass delivered with no regressions.
- [ ] ⚪ Tests updated and passing for touched areas.


## H) Priority Workstream (Current Delivery): Event Form + Host Eligibility

### Delivery Scope Lock (Current Iteration)
- [ ] 🟡 Provisional — Deliver only this workstream (`H1`, `H2`, `H3`) in the current iteration.
- [ ] 🟡 Provisional — Keep workstreams `A` through `G` pending until these priority items are delivered.

### H1) Type Description Optional (Account Types, Event Types, Any Types)

#### Tasks
- [ ] 🟣 Lane-Promoted — Remove required-description validation from type create/edit forms in Flutter.
- [ ] 🟣 Lane-Promoted — Align backend validation/contracts so type description is optional (store/update paths).
- [ ] 🟣 Lane-Promoted — Ensure payload encoding omits empty description fields (instead of forcing empty-string validation errors).

#### Acceptance Criteria
- [ ] 🟣 Lane-Promoted — Type forms submit successfully with blank description.
- [ ] 🟣 Lane-Promoted — API accepts create/update type payloads without description.
- [ ] 🟣 Lane-Promoted — Existing types with description continue to render without regressions.

### H2) Event/Occurrence Description Optional

#### Tasks
- [ ] 🟣 Lane-Promoted — Remove required-description validation from event creation/edit form (`content` field).
- [ ] 🟣 Lane-Promoted — Align backend event create/update validation so `content` is optional.
- [ ] 🟣 Lane-Promoted — Verify occurrence scheduling and publication rules do not depend on description text.

#### Acceptance Criteria
- [ ] 🟣 Lane-Promoted — Event create/update succeeds with no description.
- [ ] 🟣 Lane-Promoted — Occurrence validation behavior remains unchanged (date/time rules only).
- [ ] 🟣 Lane-Promoted — Event list/detail rendering remains stable when description is missing.

### H3) Physical Host Eligibility by POI Capability (Not Hardcoded Venue)

#### Tasks
- [ ] 🟣 Lane-Promoted — Replace venue-only host candidate criteria with capability criteria: profile type must have `capabilities.is_poi_enabled=true`.
- [ ] 🟧 Local-Implemented — Remove legacy host-candidates fallback from `venues`; consume only canonical `physical_hosts` payload in Flutter.
- [ ] 🟣 Lane-Promoted — Require valid profile location for physical/hybrid host eligibility.
- [ ] 🟣 Lane-Promoted — Update event creation contract to use canonical physical-host reference `place_ref.type=account_profile`.
- [ ] 🟣 Lane-Promoted — Update Flutter labels/UX from venue-only wording to generic physical host wording.

#### Acceptance Criteria
- [ ] 🟣 Lane-Promoted — Non-venue account profiles can be selected as physical host when `is_poi_enabled=true` and location is valid.
- [ ] 🟣 Lane-Promoted — Ineligible profiles (no POI capability or no valid location) do not appear as physical-host candidates.
- [ ] 🟣 Lane-Promoted — Event creation persists canonical `place_ref.type=account_profile` for physical/hybrid flows.
- [ ] 🟧 Local-Implemented — Event form host candidates are sourced only from `physical_hosts` (no `venues` compatibility path).

#### Local Validation Evidence (2026-03-21)
- `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` -> passed (`22` tests).
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventTypesControllerTest.php tests/Feature/Events/EventCrudControllerTest.php` -> passed (`68` tests total: `7` + `61`).
- `flutter test test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart test/presentation/tenant_admin/static_assets/tenant_admin_static_assets_list_screen_test.dart test/presentation/tenant_admin/static_assets/tenant_admin_static_asset_edit_screen_test.dart test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart` -> passed (`20` tests).
- `dart analyze lib test integration_test` -> passed (no issues).
- `docker compose exec -T app php artisan test tests/Feature/StaticAssets/StaticAssetsControllerTest.php tests/Feature/Map/MapPoiRebuildCommandTest.php` (with local `DB_URI*`) -> passed (`8` tests).

---

## Parking Lot (Defer)
- [ ] ⚪ Decide fallback image policy for event cards vs event detail screen.

## Manual edited by the user
When the user finds an issue, list it here. We should evaluate and transform it into tasks. If necessary, ask the user.

- [x] Processed (2026-03-21): "Discovery chips/categories should include only favoritable profile types." -> captured as `D.6`.
- [x] Processed (2026-03-21): "Non-admin discovery/public account-profile endpoint must not leak private profiles." -> captured as `D.7`.
- [x] Processed (2026-03-21): "Static Assets should remove legacy tags/categories and rely only on taxonomy_terms." -> implemented locally in Flutter + Laravel contracts and map-poi source adapter compatibility.
