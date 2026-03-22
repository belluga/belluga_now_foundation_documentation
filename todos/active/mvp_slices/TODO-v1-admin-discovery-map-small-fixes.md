# TODO (V1): Admin + Discovery + Map Small Fixes

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.  
**Status:** Active (local scope completed; awaiting promotion lane)  
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
| A — Admin Search | `belluga_now_docker/flutter-app@feature/v1-admin-discovery-map-small-fixes-followup (backend-first search + debounce + per_page)` | `<pending>` | `<pending>` | `<pending>` | `🟧 Local-Implemented` |
| B — Unmanaged Type Edit | `belluga_now_docker/flutter-app@feature/v1-admin-discovery-map-small-fixes-followup + laravel-app@feature/v1-admin-discovery-map-small-fixes-followup` | `<pending>` | `<pending>` | `<pending>` | `🟧 Local-Implemented` |
| C — Map Icon/Color Config | `flutter-app@feature/v1-admin-discovery-map-small-fixes-followup (C.5 + C.6 local implementation)` | `<pending>` | `<pending>` | `<pending>` | `🟧 Local-Implemented (Branding scope) + 🟡 Provisional (map refactor moved)` |
| D — Discovery Truncation | `belluga_now_docker/flutter-app@feature/v1-admin-discovery-map-small-fixes-followup + laravel-app@feature/v1-admin-discovery-map-small-fixes-followup` | `<pending>` | `<pending>` | `<pending>` | `🟧 Local-Implemented` |
| E — Visual Improvements | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `🟡 Provisional (tracked in dedicated TODO)` |
| F — Validation and Tests | `flutter-app@feature/v1-admin-discovery-map-small-fixes-followup + laravel-app@feature/v1-admin-discovery-map-small-fixes-followup (analyze + Flutter/Laravel targeted suites + local web build)` | `<pending>` | `<pending>` | `<pending>` | `🟧 Local-Implemented` |
| H — Event Form + Host Eligibility | `flutter-app@feature/v1-priority-h1-h3-admin-event-host-poi: 1ecfc17 (+local H3.2 fallback removal); laravel-app@feature/v1-priority-h1-h3-admin-event-host-poi: 4ae7815` | `Flutter PR #139; Laravel PR #99` | `Flutter PR #140; Laravel PR #100` | `<pending>` | `🟣 Lane-Promoted (H1/H2/H3.1/H3.3/H3.4/H3.5) + 🟧 Local-Implemented (H3.2)` |
| J — Tenant Public Regressions (Discovery/Home/Location/Text) | `flutter-app@feature/v1-admin-discovery-map-small-fixes-followup (J.3/J.4) + laravel-app@feature/v1-admin-discovery-map-small-fixes-followup (J.1/J.5 + token-scope tests)` | `<pending>` | `<pending>` | `<pending>` | `🟧 Local-Implemented (J.1/J.3/J.4/J.5) + 🟡 Provisional (J.2 superseded to VNext)` |

---

## References
- `delphi-ai/templates/todo_template.md`
- `foundation_documentation/todos/README.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-icon-color-config.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-search-performance-hardening.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-tenant-public-resilience-and-error-continuity.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

---

## Scope
- Fix Tenant Admin account/profile search behavior.
- Allow editing account ownership state (`tenant_owned` / `unmanaged`) from Tenant Admin edit flow.
- Add unmanaged-only account delete flow with backend transactional consistency.
- Fix Discovery list truncation so users can access the full expected dataset.
- Fix tenant-public discovery bootstrap/loading regressions in production.
- Fix tenant-public Home agenda returning empty state when backend has eligible events.
- Fix web location permission UX flow (retry behavior and denied-permanent guidance).
- Define and implement canonical contains-search behavior for text filters (`thales` vs `thale`) across affected lists.
- Keep Branding/Visual Identity color-picker improvements within this slice.
- Remove legacy test-only mock backend code from runtime `lib` paths and relocate it to test-only support paths.
- Relocate local app metadata source out of `dao/local` into a clearer infrastructure/platform path (without changing runtime behavior).

## Out of Scope
- New major IA/UX redesign for admin, map, or discovery.
- Net-new MVP capabilities unrelated to these defects.
- Backend schema redesign for map POI core model.
- Map marker/filter icon-color architecture refactor (tracked in `TODO-v1-map-icon-color-config.md`).
- Targeted cross-screen visual polish pass (tracked in `TODO-v1-targeted-visual-polish.md`).

---

## Complexity Triage (Simple vs Decision-Heavy)

### Simple and Objective (can execute directly)
| Ref | Task | Why simple/objective |
| --- | --- | --- |
| A.1 | Reproduce/document failing search paths | Diagnostic and evidence collection only. |
| A.2 | Verify filter/query propagation Flutter -> backend | Contract traceability check with clear expected output. |
| D.1 | Audit discovery limits/truncation source | Technical root-cause audit with measurable result. |
| J.1-J.4 | Discovery/Home/Location regression fixes | Deterministic runtime behavior with explicit contracts and acceptance tests. |
| F.1-F.4 | Tests and targeted regression runs | Execution-focused validation work. |
| H1.1-H1.3 | Type description optional (Flutter/backend/payload) | Clear contract: description is optional. |
| H2.1-H2.3 | Event/occurrence description optional | Clear contract: `content` optional; date rules unchanged. |
| H3.2 | Remove `venues` fallback; use only `physical_hosts` | Explicit compatibility removal decision already made. |
| H3.5 | Update venue wording to physical-host wording | UI terminology alignment with defined contract. |
| C.5 | Allow manual `#RRGGBB` input in Branding edit color picker with live preview | Clear UX/contract requirement with deterministic validation. |
| D.6 | Restrict Discovery chips/categories to favoritable profile types only | Deterministic rule driven by `profile_types.capabilities.is_favoritable`. |
| B.1-B.5 | Ownership edit + unmanaged-only delete guardrails | Contract and eligibility rule already aligned (`tenant_owned/unmanaged`; delete only unmanaged). |

### Needs Decision/Alignment First (before implementation)
| Ref | Task | Decision needed |
| --- | --- | --- |
| A.3-A.4 | Search criteria + refresh/clear lifecycle behavior | Final UX behavior on clear/pagination/reload interactions. |
| D.2-D.4 | Discovery completeness behavior | Canonical contract choice: paging/infinite/complete fetch semantics. |
| D.5 | Favorites mutation for identified users only | Define backend mutation contract and explicit auth/identity gate behavior for anonymous users. |
| D.7 | Enforce non-admin account profile privacy boundary in Discovery/public endpoint | Decide explicit public-visibility contract (field/policy) before enforcing backend filter. |
| J.5 | Contains-search behavior for textual filters | Use regex contains for MVP (`%term%` behavior) and plan indexed optimization in VNext. |
| Parking Lot | Fallback image policy | Product decision by definition. |

### Suggested Execution Sequence
1. Deliver the objective set first (`H1`, `H2`, `H3.2`, `H3.5`, plus diagnostics `A.1`, `A.2`, `D.1`).
2. Close remaining decision-heavy items in this TODO (`B/D`).
3. Execute map and visual dedicated TODOs in parallel (`TODO-v1-map-icon-color-config.md` and `TODO-v1-targeted-visual-polish.md`).
4. Close `J` tenant-public regressions (`Discovery/Home/Location/Text`) with explicit regression tests.
5. Execute `F` validation after each decision-heavy batch.

## Next Delivery Scope Lock (Alignment 2026-03-21)
- [x] Processed (2026-03-21) — Closed `D` items in this TODO with backend-first pagination/search, favoritable chip filtering, and favorite/auth guard behavior.
- [x] Processed (2026-03-21) — Closed `F` validation/test scope with targeted Flutter + Laravel suites and local web build evidence.
- [x] Processed (2026-03-21) — Closed `G` local Definition of Done lines for this slice.
- [x] Processed (2026-03-21) — Included `C.6` (PWA icon rendering consistency) in this delivery scope.

---

## A) Workstream: Admin Search Not Working Properly

### Tasks
- [ ] 🟧 Local-Implemented — Reproduce and document failing search paths (Account list, Account Profile list/detail selectors).
- [ ] 🟧 Local-Implemented — Verify request/query propagation from Flutter filters/search to backend endpoints.
- [ ] 🟧 Local-Implemented — Fix search criteria application and result refresh lifecycle (backend-first with debounce and page reset).
- [ ] 🟧 Local-Implemented — Ensure clear-search restores baseline dataset correctly via backend reload.

### Diagnostic Evidence (2026-03-21)
- Flutter account-list search is local-only over already loaded pages (`flutter-app/lib/presentation/tenant_admin/accounts/screens/tenant_admin_accounts_list_screen.dart`, `_filterAccounts`), so unloaded pages are never queried.
- Flutter controller updates only local search state (`flutter-app/lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart`, `updateSearchQuery`) and does not trigger backend fetch by query.
- Accounts repository request does not propagate any text-search filter (`flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart`, `_fetchFilteredAccountsPage` sends `page`, `page_size`, `ownership_state` only).
- Backend query layer supports field-level filters/search (`laravel-app/app/Application/Shared/Query/AbstractQueryService.php`), but Flutter does not currently send those fields for account search.
- Additional pagination mismatch observed: Flutter sends `page_size`, while Laravel account/profile index controllers read `per_page` by default.

### Acceptance Criteria
- [ ] 🟧 Local-Implemented — Search by name/identifier returns expected matches.
- [ ] 🟧 Local-Implemented — Search state is consistent after pagination/reload.
- [ ] 🟧 Local-Implemented — Empty/loading/error states remain correct while searching.

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

## B) Workstream: Ownership Edit + Unmanaged Delete Guardrails

### Tasks
- [ ] 🟧 Local-Implemented — Add ownership-state edit flow in Tenant Admin profile edit (`tenant_owned` / `unmanaged`, same options as create flow).
- [ ] 🟧 Local-Implemented — Wire ownership-state persistence through backend account update contract and refresh list/detail projections after save.
- [ ] 🟧 Local-Implemented — Enforce unmanaged-only account delete in backend with transactional consistency (account + account profiles + role templates).
- [ ] 🟧 Local-Implemented — Expose delete CTA in account detail UI only when account is unmanaged and route deletion through backend contract.
- [ ] 🟧 Local-Implemented — Keep managed/tenant-owned guardrails explicit in UI and backend validation (delete blocked for non-unmanaged).

### Acceptance Criteria
- [ ] 🟧 Local-Implemented — Admin can update ownership state from edit flow and observe reflected value in account detail/list surfaces.
- [ ] 🟧 Local-Implemented — Delete operation succeeds only for unmanaged accounts.
- [ ] 🟧 Local-Implemented — Ineligible delete attempts are blocked by backend validation with explicit error.

---

## C) Workstream: Branding Color Picker Improvements (Scoped in This TODO)

### Tasks
- [ ] 🟧 Local-Implemented — In Branding/Visual Identity edit flow, allow manual `#RRGGBB` input in color picker modal and keep picker/preview synced with typed value.
- [ ] 🟧 Local-Implemented — Remove preset color chips from Branding color picker modal (keep a single canonical editable hex input).
- [ ] 🟧 Local-Implemented — Ensure PWA icon field behavior is explicit and functional in UI: if independent, persist + render saved image using the same upload/display standards as other branding images.

### Acceptance Criteria
- [ ] 🟧 Local-Implemented — In Branding/Visual Identity edit flow, color picker modal accepts manually typed valid `#RRGGBB` values and updates picker/preview immediately.
- [ ] 🟧 Local-Implemented — Branding color picker modal no longer renders preset chips.
- [ ] 🟧 Local-Implemented — PWA icon preview/render path is consistent with saved data (no silent mismatch between saved asset and displayed UI state).
- [ ] 🟡 Provisional — Map icon/color hardcoding architectural refactor moved to dedicated TODO `TODO-v1-map-icon-color-config.md`.

---

## D) Workstream: Discovery Shows Only Few Items

### Tasks
- [ ] 🟧 Local-Implemented — Audit current Discovery fetch/pagination limits and identify truncation source.
- [ ] 🟧 Local-Implemented — Align list loading with canonical dataset expectations (pagination/infinite scroll or complete fetch by contract).
- [ ] 🟧 Local-Implemented — Ensure filter/search interactions do not silently drop valid items.
- [ ] 🟧 Local-Implemented — Validate interaction with favorites state and profile-type registry filtering.
- [ ] 🟧 Local-Implemented — Establish favorites mutation flow with backend persistence and enforce mutation access for identified users only (anonymous users must be blocked and redirected to auth).
- [ ] 🟧 Local-Implemented — Restrict Discovery filter chips/categories to profile types where `capabilities.is_favoritable=true`.
- [ ] 🟧 Local-Implemented — Enforce non-admin/public account-profile listing to return only public profiles (block private profile leakage in Discovery source endpoint).

### Diagnostic Evidence (2026-03-21)
- Discovery loads partners through a single fetch path (`flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`, `_loadPartners -> fetchAllAccountProfiles`).
- Repository delegates to Laravel backend fetch with no pagination override (`flutter-app/lib/infrastructure/repositories/account_profiles_repository.dart` and `flutter-app/lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart`).
- Laravel account-profile endpoints default to `per_page=15` when query param is absent (`laravel-app/app/Http/Api/v1/Controllers/AccountProfilesController.php`), causing first-page-only dataset in Discovery.
- UI sections intentionally cap highlighted carousels (`take(10)`), which amplifies the perception of truncation when source dataset is already capped.
- Discovery chip source currently uses `enabledAccountProfileTypes()` (all enabled types) instead of favoritable-only filter (`flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`, `_updateAvailableTypes`).
- Non-admin/public endpoint (`AccountProfilesController::publicIndex`) currently applies allowed-type filtering only (`publicPaginate`) and does not enforce an explicit public-visibility boundary, allowing private-profile leakage if present (`laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php`).

### Acceptance Criteria
- [ ] 🟧 Local-Implemented — Discovery displays full expected dataset for the active query/filter context.
- [ ] 🟧 Local-Implemented — No silent cap at low item count.
- [ ] 🟧 Local-Implemented — Scrolling/loading behavior is predictable and stable.
- [ ] 🟧 Local-Implemented — Favorite toggle persists across reloads/sessions and remains consistent with server state.
- [ ] 🟧 Local-Implemented — Anonymous users cannot mutate favorites; authenticated identified users can.
- [ ] 🟧 Local-Implemented — Discovery chips/categories only show favoritable account-profile types.
- [ ] 🟧 Local-Implemented — Non-admin Discovery source endpoint excludes private profiles from returned data.

---

## E) Workstream: Visual Improvements (Targeted)

Moved to dedicated TODO: `TODO-v1-targeted-visual-polish.md`.

---

## F) Validation and Test Plan
- [ ] 🟧 Local-Implemented — Add/adjust unit/widget tests for admin search and unmanaged type edit flows.
- [ ] 🟧 Local-Implemented — Add/adjust tests for discovery completeness/pagination behavior.
- [ ] 🟧 Local-Implemented — Run targeted regression suite for Home/Discovery/Map/Admin impacted surfaces.
- [ ] 🟧 Local-Implemented — Add/adjust tests and analyzer checks after legacy mock/local path cleanup (`mock_backend` relocation + `AppDataLocalInfoSource` relocation).
- [ ] 🟧 Local-Implemented — Add Flutter tests for `H1/H2`: type and event forms submit without description (`description/content` optional).
- [ ] 🟧 Local-Implemented — Add Laravel request/feature tests for `H1/H2`: create/update accepts missing `description/content` and preserves existing validation rules.
- [ ] 🟧 Local-Implemented — Add Flutter + Laravel contract tests for `H3`: host candidates use POI capability + valid location and persist `place_ref.type=account_profile`.
- [ ] 🟧 Local-Implemented — Add/adjust Flutter tests for admin accounts backend-first search and ownership edit flow in account-profile edit screen.
- [ ] 🟧 Local-Implemented — Add Laravel feature tests for accounts search fields, ownership update, unmanaged-only delete guard, and delete cascade consistency.
- [ ] 🟧 Local-Implemented — Add favorites regression tests (Flutter + Laravel): mutation is blocked for anonymous users and allowed only for authenticated identified users.
- [ ] 🟡 Provisional — Add regression coverage for tenant-public discovery bootstrap (`anon auth + first-page load + favoritable chips`), including explicit 401/403 handling. (`2026-03-22`: backend token-scope 200/403 regression added for tenant-public `agenda` + `account_profiles`, plus anonymous token first-page access assertions; discovery first-page loading-state retry coverage superseded to VNext resilience TODO)
- [ ] 🟧 Local-Implemented — Add regression coverage for tenant-public Home agenda parity (`API returns items -> UI must render items`) and filter-origin query contract.
- [ ] 🟧 Local-Implemented — Add widget/controller tests for web location permission denied-permanent UX (explicit step-by-step guidance and deterministic retry behavior).
- [ ] 🟧 Local-Implemented — Add backend + Flutter tests for canonical contains textual filtering behavior on account profiles, assets, and events search endpoints/queries. (Laravel targeted suite executed successfully on 2026-03-22 after Docker restart)

---

## I) Workstream: Infrastructure Cleanup (Mock Backend + Local Adapter Paths)

### Tasks
- [ ] 🟧 Local-Implemented — Remove `lib/infrastructure/dal/dao/mock_backend/**` from runtime code ownership by relocating required fixtures/adapters to test-only support paths.
- [ ] 🟧 Local-Implemented — Update test imports/usages to the new test-only locations and remove remaining `mock_backend` references from production `lib` modules.
- [ ] 🟧 Local-Implemented — Move `AppDataLocalInfoSource` out of `lib/infrastructure/dal/dao/local/**` to a clearer infrastructure/platform location and update imports.
- [ ] 🟧 Local-Implemented — Keep `AppDataLocalInfoSource` behavior intact (no fallback contract change), only path/ownership cleanup.

### Acceptance Criteria
- [ ] 🟧 Local-Implemented — `flutter analyze lib test integration_test` passes after path cleanup.
- [ ] 🟧 Local-Implemented — No runtime module under production `lib` depends on `mock_backend` paths.
- [ ] 🟧 Local-Implemented — `AppDataLocalInfoSource` remains functional and initialization flow behavior is unchanged.

---

## G) Definition of Done
- [ ] 🟧 Local-Implemented — Admin search works correctly in all affected Tenant Admin flows.
- [ ] 🟧 Local-Implemented — Unmanaged type edit is available and guarded correctly.
- [ ] 🟧 Local-Implemented — Discovery no longer truncates to a small subset unexpectedly.
- [ ] 🟧 Local-Implemented — Tests updated and passing for touched areas.
- [ ] 🟧 Local-Implemented — Legacy mock/local path cleanup delivered without runtime regressions.
- [ ] 🟡 Provisional — Tenant-public discovery/home/location regressions are resolved and covered by regression tests. (`J.2` continuity/fault-tolerance scope superseded to VNext TODO.)
- [ ] 🟡 Provisional — Map icon/color DoD tracked in `TODO-v1-map-icon-color-config.md`.
- [ ] 🟡 Provisional — Visual polish DoD tracked in `TODO-v1-targeted-visual-polish.md`.


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
- `fvm flutter test test/domain/venue_event/projections/venue_event_resume_test.dart` -> passed (`2` tests; fallback chain coverage).
- `fvm flutter test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart` -> passed (`5` tests; pagination + auth/favorites behavior).
- `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` -> passed (`2` tests).
- `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart --plain-name "retries first page once and publishes recovered events"` -> passed (`1` test; Home agenda recovery publishes events after transient first-page failure).
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/Favorites/FavoritesControllerTest.php` -> passed (`34` tests).
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php --filter="test_agenda_default_returns_upcoming_and_now|test_agenda_public_endpoint_shows_only_effectively_published_items"` -> passed (`2` tests; backend agenda returns eligible events for Home consumption).
- `bash scripts/build_web.sh ../web-app dev --clean-output` -> passed (Flutter `3.41.5`, with known wasm dry-run warnings only).

---

## J) Workstream: Tenant Public Runtime Regressions (Discovery + Home + Location + Text Filters)

### Tasks
- [ ] 🟧 Local-Implemented — Fix tenant-public discovery bootstrap/auth flow so first page + favoritable chips load with canonical anonymous account auth (no landlord-only tenant-access guard behavior on public tenant endpoints).
- [ ] 🟡 Provisional — Ensure discovery first-page failures do not lock infinite loading state; expose deterministic retry/error path. (**Superseded to** `TODO-vnext-tenant-public-resilience-and-error-continuity.md`.)
- [ ] 🟧 Local-Implemented — Fix tenant-public Home agenda rendering parity: when Home agenda API returns eligible events, Home list must render them (no false empty state).
- [ ] 🟧 Local-Implemented — Fix web location-permission flow: if browser permission is denied-permanent, show explicit step-by-step recovery; retry CTA behavior must be deterministic and non-silent.
- [ ] 🟧 Local-Implemented — Define and implement canonical contains textual filtering behavior (for example `thale` must match `thales`) for account profiles, static assets, and events.

### Acceptance Criteria
- [ ] 🟡 Provisional — Discovery screen no longer gets stuck loading on production with existing favoritable profile types. (Superseded to VNext resilience TODO.)
- [ ] 🟡 Provisional — Discovery chips for favoritable profile types render on first load when backend responds successfully. (Superseded to VNext resilience TODO.)
- [ ] 🟧 Local-Implemented — Home agenda shows events when backend returns events for the active tenant context.
- [ ] 🟧 Local-Implemented — Web location permission UX clearly instructs re-enable flow when browser no longer prompts.
- [ ] 🟧 Local-Implemented — Contains textual search behavior is consistent across targeted surfaces and covered by tests. (Laravel targeted suite executed successfully on 2026-03-22 after Docker restart)

### Diagnostic Evidence (Captured 2026-03-22)
- Production symptom: tenant-public discovery can stay in loading state and fail to render favoritable chips/categories.
- Production symptom: tenant-public Home can show empty agenda while map POIs/events exist for the same tenant.
- Production symptom: web location permission CTA may not trigger browser prompt, causing user confusion.
- Repro symptom: textual filter can return results for `thales` but none for `thale`, indicating missing canonical partial-text strategy.
- Backend root-cause (2026-03-22): tenant-public `CheckTenantAccess` path treated all principals as landlord-style tenant-membership checks; `AccountUser` tenant-public auth now has explicit principal branch and tenant-scoped token verification (`tenant_id` match when present).
- Regression evidence (2026-03-22): tenant-public anonymous token flow (`POST /api/v1/anonymous/identities`) successfully accesses first page of `GET /api/v1/account_profiles` and `GET /api/v1/agenda` in feature tests (`TenantPublicAccountTokenScopeTest`).

---

## Event Image Fallback Policy (Locked 2026-03-21)
- [x] Processed — Fallback order approved by product:
  1) Event uses first artist cover (artist order as provided by payload).
  2) If unavailable, use host/place cover.
  3) If unavailable, use settings `default image placeholder`.
  4) If unavailable, render local placeholder (non-image).
- [x] Processed (2026-03-21) — Fallback policy implemented in canonical projection `VenueEventResume.resolvePreferredImageUri` and consumed by event card/detail flows via `VenueEventResume.fromScheduleEvent` and detail invite builder.
- [x] Processed (2026-03-21) — Regression tests added/validated for fallback chain (`test/domain/venue_event/projections/venue_event_resume_test.dart`) and detail surface integration tests.

## Manual edited by the user
When the user finds an issue, list it here. We should evaluate and transform it into tasks. If necessary, ask the user.

- [x] Processed (2026-03-21): "Discovery chips/categories should include only favoritable profile types." -> captured as `D.6`.
- [x] Processed (2026-03-21): "Non-admin discovery/public account-profile endpoint must not leak private profiles." -> captured as `D.7`.
- [x] Processed (2026-03-21): "Static Assets should remove legacy tags/categories and rely only on taxonomy_terms." -> implemented locally in Flutter + Laravel contracts and map-poi source adapter compatibility.
- [x] Processed (2026-03-21): "Define event image fallback order for cards/detail." -> policy locked in `Event Image Fallback Policy`.
- [x] Processed (2026-03-21): "Web mobile keyboard opens and pushes layout but does not restore on close." -> fixed locally in tenant-admin shell (`resizeToAvoidBottomInset=false`) and project SDK updated via FVM to Flutter `3.41.5`.
- [x] Processed (2026-03-22): "Tenant-public discovery auth/bootstrap fails due tenant-access guard behavior mismatch for `AccountUser`." -> implemented locally as `J.1` (explicit `AccountUser` branch in `CheckTenantAccess` + tenant-scoped token stamping/validation on account auth flows + regression tests).
- [x] Processed (2026-03-22): "Tenant-public discovery can still remain loading after first-page failure; needs deterministic retry/error state path." -> `J.2` superseded to `TODO-vnext-tenant-public-resilience-and-error-continuity.md`.
- [x] Processed (2026-03-22): "Tenant-public Home agenda shows empty state while events exist (map still shows POIs/events)." -> implemented locally as `J.3` (first-page transient failure retry + agenda search propagation).
- [x] Processed (2026-03-22): "Web location permission CTA does not trigger prompt; denied-permanent path needs explicit user guidance." -> implemented locally as `J.4` (controller + UI guidance + regression tests).
- [x] Processed (2026-03-22): "Textual filter inconsistency (`thales` returns, `thale` does not) likely affects other text-filter surfaces." -> `J.5` validated locally in Laravel tests after Docker recovery (accounts/assets/events contains-regex strategy).
- [x] Processed (2026-03-22): "MVP should use regex contains (`%term%`) even with higher cost, and VNext must optimize." -> MVP behavior set in Laravel query services; VNext optimization tracked in `TODO-vnext-search-performance-hardening.md`.


- [x] Processed (2026-03-21): "PWA icon is not showing saved image in UI." -> captured as `C.6` (explicit functional contract + implementation/validation).
