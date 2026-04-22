# TODO (Store Release): Typed Discovery Filters Package

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
**Status:** Active
**Owners:** Laravel Team, Flutter Team
**Objective:** Canonicalize tenant-admin configured discovery filters across Map, Home Events, and Account Profile Discovery with Package-First Laravel/Flutter filter contracts, contextual admin editors, public filter widgets, persisted user selections, and backend-owned result filtering.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** `foundation_documentation/artifacts/tmp/improvement-intake-session-2026-04-20.md` (`C-02`)
- **Dependency role:** TODO B. Blocked by TODO A: `foundation_documentation/todos/active/store_release_android/TODO-store-release-taxonomy-term-display-snapshots.md`.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `C-02`
- **Why this is the right current slice:** The first value slice is one cohesive filter system: tenant admin configures filters, public users consume those configured filters on three concrete surfaces, and all results are filtered by server/read-model adapters.
- **Direct-to-TODO rationale:** Product decisions for package boundary, backend catalog/migration, admin UI, public UI behavior, persisted selections, and test matrix were closed in-session. Remaining choices are implementation-local best-practice decisions after TODO A finalizes taxonomy display snapshots.

## Delivery Status Canon

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** Keep ready for dev promotion; rerun `todo_completion_guard.py --require-delivery` before any delivery-stage or promotion claim changes.

## Blocker Notes

- **Blocker:** `n/a`
- **Why blocked now:** `n/a`
- **What unblocks it:** `n/a`
- **Owner / source:** Store Release orchestrator final runtime validation.
- **Last confirmed truth:** TODO A reached `Local-Implemented` with validated display-ready taxonomy snapshots; TODO D reached `Local-Implemented` with selected-occurrence/EventOccurrence detail semantics; SR-B local package/backend/Flutter validation is current, and final item-specific runtime acceptance remains pending.

## Scope

- [ ] Create local Package-First discovery-filter package boundaries for Laravel and Flutter.
- [ ] Define canonical filter grammar/value objects using `entity`, `type`, and `target`.
- [ ] Define entity registry/provider contracts for type options, taxonomy scope, target compiler availability, visuals, labels, and relevant repository/API hooks.
- [ ] Register concrete entity providers for first-slice entities: `event`, `account_profile`, and `static_asset` where supported by the selected surface.
- [ ] Implement target/read-model adapters for `map_poi`, `event_occurrence`/agenda, and account-profile discovery.
- [ ] Move tenant-admin filter management to a main `Filtros` section with submenus `Mapa`, `Eventos na Tela Principal`, and `Descoberta de Perfis`.
- [ ] Migrate existing `settings.map_ui.filters` into canonical `public_map.primary` definitions while preserving tenant behavior and compatibility `/map/filters` reads.
- [ ] Implement public filter component behavior for primary filters, taxonomy subfilters, selected/clear/loading states, wrap/row layout policies, and canonical payload emission.
- [ ] Persist public user selections per user/surface/context and repair stale selections against the current tenant-admin catalog before querying.
- [ ] Ensure Map, Home Events, and Account Profile Discovery render backend-filtered results from target-owned endpoints/read models, not client-side post-fetch filtering.

## Out of Scope

- [ ] Public ad-hoc facet/filter building. Public users consume tenant-admin configured filters only.
- [ ] Text search changes for agenda/events. Existing `EVS-FILTER-01` remains preserved.
- [ ] A dedicated Static Asset Discovery public surface. Static Assets are included where the first slice needs them as Map source entities and registry parity.
- [ ] Replacing result cards, Map dock behavior, search fields, or screen-specific result rendering. Those remain screen-owned.
- [ ] Moving read-model-specific query execution into the discovery-filter package.

## Execution Lane Tracking

- **Local implementation branches:** `orchestrator/store-release-usability-wave` in `belluga_now_docker`, `laravel-app`, and `flutter-app`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Discovery filters package | `orchestrator/store-release-usability-wave` | `n/a - not promoted yet` | `n/a - not promoted yet` | `n/a - not promoted yet` | `Local-Implemented; final runtime acceptance passed` |

## Local Implementation Evidence

- Laravel discovery-filter package/core contracts are implemented with canonical `entity`, entity-qualified `type`, `target`, taxonomy grouping, stale selection repair, ServiceProvider-style registry/provider resolution, and surface catalog materialization.
- Laravel host adapters expose the first-slice surfaces `public_map.primary`, `home.events`, and `discovery.account_profiles`; Map supports `event`, `account_profile`, and `static_asset` entities, Home Events is constrained to Event/EventOccurrence results, and Discovery is constrained to Account Profiles.
- Existing `settings.map_ui.filters` are backfilled into canonical `discovery_filters.surfaces.public_map.primary` definitions while `/map/filters` remains compatibility-safe.
- Backend query/read-model paths consume canonical filter payloads for Map POIs, Home/Event Occurrences, and Account Profile Discovery without relying on Flutter-side post-fetch filtering.
- Flutter package `packages/belluga_discovery_filters` owns catalog parsing, selection policy, stale repair, query payload emission, primary/subfilter widget behavior, loading affordances, and taxonomy group rendering.
- Flutter tenant-admin filters moved to a main `Filtros` section with contextual surfaces `Mapa`, `Eventos na Tela Principal`, and `Descoberta de Perfis`; local preferences now links to the canonical menu instead of rendering the legacy map filter editor.
- Flutter public Map/Home/Discovery integrations use the package widget/selection payloads, persist per-surface selections through AppData settings snapshots, repair obsolete selections, and preserve the approved Map single-select visual behavior.
- The canonical E2E integration test was migrated from legacy `map_ui.filters` UI assumptions to the new Admin `Filtros > Mapa` surface and `discovery_filters` settings path.

## Local Validation Evidence

- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/Accounts/AccountOnboardingsControllerTest.php tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php tests/Feature/StaticAssets/StaticAssetsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Feature/Map/MapPoisControllerTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php` passed: `203 tests, 1181 assertions`.
- `fvm dart analyze --format machine` passed after migrating admin filter coverage to the canonical `Filtros` surface and removing stale integration-test residues.
- `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_core_test.dart packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart test/infrastructure/dal/dto/discovery_filters/discovery_filter_catalog_dto_test.dart test/infrastructure/services/http/laravel_discovery_filters_http_service_test.dart test/infrastructure/repositories/discovery_filters_repository_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/infrastructure/repositories/app_data_repository_location_origin_test.dart test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` passed: `174 tests`.
- `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart` passed: `39 tests`.
- SR-B rerun 2026-04-22: `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php` passed: `4 tests, 17 assertions`.
- SR-B rerun 2026-04-22: `docker compose exec -T -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_tests_srb_settings_20260421235300 -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_tests_srb_settings_landlord_20260421235300 -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_tests_srb_settings_tenant_20260421235300 -e DB_DATABASE=landlord_srb_settings_20260421235300 -e DB_DATABASE_LANDLORD=landlord_srb_settings_20260421235300 -e DB_DATABASE_TENANTS=tenants_srb_settings_20260421235300 app php artisan test tests/Feature/Settings/SettingsKernelControllerTest.php --filter 'test_settings_schema_endpoint_returns_registered_namespaces|test_patch_discovery_filters_persists_surface_filter_order_and_delete_semantics|test_patch_discovery_filters_requires_discovery_filters_ability'` passed: `3 tests, 21 assertions`.
- SR-B rerun 2026-04-22: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php --filter test_map_pois_supports_source_and_types_filters` passed: `1 test, 8 assertions`.
- SR-B rerun 2026-04-22: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php --filter 'test_public_account_profile_index_filters_by_taxonomy_terms_on_backend|test_public_account_profile_near_filters_by_taxonomy_terms_on_backend|test_public_account_profile_near_accepts_multiple_profile_types'` passed: `3 tests, 9 assertions`.
- SR-B rerun 2026-04-22: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php --filter test_agenda_filters_by_typed_taxonomy_terms` passed: `1 test, 9 assertions`.
- SR-B rerun 2026-04-22: `docker compose exec -T ... app php artisan test tests/Feature/Map/MapPoisControllerTest.php --filter 'test_map_filters_use_canonical_discovery_filter_surface_when_available|test_discovery_filters_backfill_map_ui_filters_is_idempotent|test_discovery_filter_registry_resolves_first_slice_entity_providers|test_discovery_filters_public_catalog_returns_surface_filters_and_type_options'` passed: `4 tests, 37 assertions`.
- SR-B rerun 2026-04-22: `docker compose exec -T ... app php artisan test tests/Feature/Settings/SettingsKernelControllerTest.php --filter test_patch_discovery_filters_persists_surface_filter_order_and_delete_semantics` passed with `image_uri` persistence assertions included: `1 test, 10 assertions`.
- SR-B ability catalog check 2026-04-22: `rg -n "discovery-filters-settings:update" laravel-app/config/abilities.php` returned `laravel-app/config/abilities.php:46`, proving the settings update ability is synchronized.
- SR-B rerun 2026-04-22: `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_core_test.dart packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart test/infrastructure/dal/dto/discovery_filters/discovery_filter_catalog_dto_test.dart test/infrastructure/services/http/laravel_discovery_filters_http_service_test.dart test/infrastructure/repositories/discovery_filters_repository_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/infrastructure/repositories/app_data_repository_location_origin_test.dart test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` passed: `174 tests`.
- SR-B rerun 2026-04-22: `fvm flutter test test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/map_filter_category_icon_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart` passed: `172 tests`.
- SR-B rerun 2026-04-22: `fvm flutter test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart --plain-name "DiscoveryScreen keeps canonical primary filters pinned below Descubra while scrolling"` passed: `1 test`.
- SR-B source-owned Web spec 2026-04-22: added `tools/flutter/web_app_tests/discovery_filters.spec.js` with item-specific Playwright journeys for Admin `Filtros IA`, Map selected/sibling filter behavior, Home title-row filter action with persisted active hint/reopen/sticky behavior, Profile Discovery primitive sticky line, and backend-filtered request assertions; `node --check tools/flutter/web_app_tests/discovery_filters.spec.js` passed. Runtime execution remains orchestrator-owned and was not claimed by this evidence.
- SR-B focused Flutter rerun 2026-04-22: `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_core_test.dart packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart test/infrastructure/dal/dto/discovery_filters/discovery_filter_catalog_dto_test.dart test/infrastructure/services/http/laravel_discovery_filters_http_service_test.dart test/infrastructure/repositories/discovery_filters_repository_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/infrastructure/repositories/app_data_repository_location_origin_test.dart test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/map_filter_category_icon_test.dart` passed: `276 tests`.
- SR-B Android device integration 2026-04-22 after WSL/device cleanup: `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md` marks `[x]` for `feature_map_filter_catalog_admin_to_public_e2e_test.dart`, `feature_home_agenda_eligible_events_query_contract_e2e_test.dart`, `feature_agenda_filters_regression_test.dart`, and `feature_map_event_filter_actions_test.dart` against device `192.168.15.9:5555`, app `com.guarappari.app`, flavor `guarappari`.
- SR-B Web build 2026-04-22: `bash scripts/build_web.sh ../web-app dev` passed; `sha256sum ../web-app/main.dart.js` and `curl -k -L 'https://guarappari.belluga.space/main.dart.js?cachebust=runtime-validation-20260422' | sha256sum` both returned `2a022493dff34f9c906c1352b769cd55237f39805c22f5e48dc3c24890060f9b`.
- SR-B Web navigation 2026-04-22: earlier smoke passed; final rerun after harness hardening and final spec reconciliation passed `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `6 passed (1.6m)` and `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` -> `12 passed (7.4m)`.
- SR-B Web filter assertions 2026-04-22: Playwright command against `https://guarappari.belluga.space/mapa` clicked the `Events` filter and observed backend request `GET /api/v1/map/pois?...&source=event`; Playwright command against `/descobrir` selected `Artist` and observed backend request `GET /api/v1/account_profiles?...&profile_type=artist&filter[profile_type]=artist`.
- SR-B final Web freshness 2026-04-22: `bash scripts/build_web.sh ../web-app dev` produced the served bundle; `sha256sum ../web-app/main.dart.js` and `curl -k -L 'https://guarappari.belluga.space/main.dart.js?cachebust=runtime-validation-20260422' | sha256sum` both returned `2a022493dff34f9c906c1352b769cd55237f39805c22f5e48dc3c24890060f9b`.
- SR-B final completion guard 2026-04-22: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-typed-discovery-filters-package.md --require-delivery` returned `Overall outcome: go`.
- SR-B rerun 2026-04-22: `fvm dart analyze --format machine` exited `0`; output contained only INFO/HINT `unnecessary_import` diagnostics in `integration_test/feature_account_profile_rich_text_fidelity_test.dart`.

## Final Runtime Acceptance Reconciliation

- 2026-04-22 PACED correction: code, package tests, unit/widget tests, Laravel feature tests, and any prior smoke evidence remain valid implementation/supporting evidence. The orchestrator can accept only criteria with item-specific final runtime evidence.
- 2026-04-22 final runtime acceptance passed through source-owned Playwright journeys in `tools/flutter/web_app_tests/discovery_filters.spec.js` after current Web build/publish.
- Platform parity classification: filter catalog/query semantics are backend-owned and Flutter package behavior is shared across Android/Web; public Web Playwright final runtime evidence closes visible shared behavior. Prior ADB integration checklist remains supporting device evidence.

| Criterion ID | Current supporting evidence | Final acceptance gap | Required next evidence |
| --- | --- | --- | --- |
| DOD-02 | Flutter admin widget/route/model evidence exists; source-owned Playwright journey exists in `tools/flutter/web_app_tests/discovery_filters.spec.js`. | Closed. | Final mutation Playwright passed `12 passed (7.4m)` and asserted Admin `Filtros` surfaces `Mapa`, `Eventos na Tela Principal`, and `Descoberta de Perfis` with no placement selector. |
| DOD-04 | Flutter package widget evidence exists; source-owned Playwright journey exists in `tools/flutter/web_app_tests/discovery_filters.spec.js`. | Closed. | Final mutation Playwright passed and asserted Map selected/sibling behavior plus filtered `/api/v1/map/pois?...source=event` backend request. |
| DOD-05 | Flutter controller/widget/backend payload evidence exists; source-owned Playwright journey exists in `tools/flutter/web_app_tests/discovery_filters.spec.js`. | Closed. | Final mutation Playwright passed and asserted Home title-row filter action, persisted active hint/reopen selected state, sticky filter behavior, and filtered `/api/v1/agenda` request. |
| DOD-06 | Flutter widget evidence exists; source-owned Playwright journey exists in `tools/flutter/web_app_tests/discovery_filters.spec.js`. | Closed. | Final mutation Playwright passed and asserted Profile Discovery primitive sticky filter line plus filtered `/api/v1/account_profiles?...profile_type=artist...` request. |
| DOD-08 | Backend and Flutter transport tests prove payload construction; source-owned Playwright journeys assert filtered backend requests. | Closed. | Final mutation Playwright passed Map, Home, and Profile Discovery request assertions against final runtime, proving backend-filtered calls rather than local post-fetch filtering. |
| VAL-07 | Source-owned Playwright spec path exists: `tools/flutter/web_app_tests/discovery_filters.spec.js`; syntax check passed. | Closed. | `bash scripts/build_web.sh ../web-app dev` was run; served bundle hash matched local `2a022493...`; final mutation Playwright passed `12 passed (7.4m)`. |

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOD-01 | Definition of Done | Laravel and Flutter package-level tests prove filter grammar, registry/provider behavior, catalog serialization, cascade validation, stale selection repair, and payload emission. | automated test | Laravel `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php` passed `4 tests, 17 assertions`; Flutter `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_core_test.dart packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart ...` passed in the `174 tests` focused suite; representative assertions at `laravel-app/tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php:17`, `:61`, `:89`, `:120`, `flutter-app/packages/belluga_discovery_filters/test/discovery_filter_core_test.dart:5`, `:141`, `:179`, `:251`. | Laravel Docker app container and Flutter host test runner | passed | Package grammar, registry/provider behavior, catalog parsing, stale repair, and payload emission have local automated evidence. |
| DOD-02 | Definition of Done | Admin `Filtros` section exposes `Mapa`, `Eventos na Tela Principal`, and `Descoberta de Perfis` with contextual policies and no per-filter placement selector. | automated test, code reference, and Playwright mutation | Flutter focused suite passed `276 tests`; admin evidence at `flutter-app/test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart:652`, `:690`, `flutter-app/test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart:11`, `:50`, surfaces at `flutter-app/lib/presentation/tenant_admin/discovery_filters/models/tenant_admin_discovery_filter_surface_definition.dart:22`, route at `flutter-app/lib/application/router/modular_app/modules/tenant_admin_module.dart:488`; final mutation command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` passed `12 passed (7.4m)` including `tenant-admin Filtros IA exposes contextual discovery surfaces`. | Flutter host test runner + final Web runtime | passed | Playwright proves all three Admin `Filtros` contextual surfaces are visible and no placement selector leaks. |
| DOD-03 | Definition of Done | Existing Map filters migrate into `Filtros > Mapa` preserving order, key, label, image, marker override, query constraints, and legacy behavior. | automated test | Laravel focused Map run passed `test_discovery_filters_backfill_map_ui_filters_is_idempotent`; Flutter focused suite passed codec/admin tests at `flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart:11`, `:44` and `flutter-app/test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart:11`. | Laravel Docker app container and Flutter host test runner | passed | Backfill, codec extraction, and canonical admin loading have local evidence. |
| DOD-04 | Definition of Done | Public Map preserves current single-active visual behavior while supporting multi-select contexts without hiding siblings. | automated widget test and Playwright navigation | Flutter focused suite passed package widget coverage at `flutter-app/packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart:6`, `:44`, `:77`, `:123`; final mutation Playwright passed `public Map filters select one primary while siblings stay visible and backend-filtered`. | Flutter host test runner + final Web runtime | passed | Package widget tests cover selected primary, sibling visibility, subfilter groups, hidden title, and loading affordance; Playwright clicked the Map filter and observed filtered backend request. |
| DOD-05 | Definition of Done | Home/Event-list exposes a title-row `Filtro` action left of radius/distance, restores persisted active state with a clear hint, opens with selections selected, and keeps the filter sticky while scrolling. | automated controller/widget test and Playwright mutation | Flutter focused suite passed `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:870`, `:842`, `:2521`; supporting backend payload tests at `flutter-app/test/infrastructure/repositories/schedule_repository_test.dart:116` and `flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart:128`; final mutation Playwright passed `Home event filters expose sticky title-row action and persist selected state`. | Flutter host test runner + final Web runtime | passed | Playwright performs the visible Home filter journey on final runtime, including persisted active state/reopen and filtered `/api/v1/agenda` request. |
| DOD-06 | Definition of Done | Account Profile Discovery shows the primitive primary filter line by default below the title and keeps it sticky while scrolling. | widget test and Playwright navigation | `fvm flutter test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart --plain-name "DiscoveryScreen keeps canonical primary filters pinned below Descubra while scrolling"` passed; final mutation Playwright passed `Profile Discovery keeps primitive filters sticky and backend-filtered`. | Flutter host widget runner + final Web runtime | passed | Widget test proves sticky header/filter behavior; Playwright proves the visible runtime line and backend-filtered Discovery request. |
| DOD-07 | Definition of Done | Backend result tests prove real persisted-data filtering for Map POIs, Home Events/Event Occurrences, and Account Profile Discovery. | backend feature tests + Web request assertions | Map: `run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php --filter test_map_pois_supports_source_and_types_filters` -> `1 test, 8 assertions`; Home/Event Occurrences: `run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php --filter test_agenda_filters_by_typed_taxonomy_terms` -> `1 test, 9 assertions`; Account Profile Discovery: safe-runner command covering public index taxonomy filters, near taxonomy filters, and multiple profile types -> `3 tests, 9 assertions`. | Laravel safe runner / local Docker Mongo + Web browser runtime | passed | Backend tests use persisted false-positive records and assert query-owned filtering for all three surfaces. Web assertions observed filtered backend requests for Map (`source=event`) and Discovery (`profile_type=artist`). |
| DOD-08 | Definition of Done | Integration/Web tests fail if Flutter fetches broad result sets and filters locally. | backend/transport tests and Playwright request assertions | Backend target-adapter tests passed for Map, Home/Agenda, and Account Profiles; Flutter repository/backend tests passed in the `276 tests` focused suite; final mutation Playwright passed request assertions in `tools/flutter/web_app_tests/discovery_filters.spec.js` for `/api/v1/map/pois`, `/api/v1/agenda`, and `/api/v1/account_profiles`. | Laravel safe runner / Flutter host / final Web runtime | passed | Playwright verifies the filtered backend requests on final runtime; broad-fetch/local-post-filtering would fail these assertions. |
| VAL-01 | Validation Steps | Backend package/unit tests for registry, DTO/value objects, provider resolution, taxonomy constraints, target compiler availability, and stale selection repair. | automated test | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php` passed `4 tests, 17 assertions`; representative coverage at `laravel-app/tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php:17`, `:61`, `:89`, `:120`. | Laravel Docker app container | passed | Backend package unit coverage passed. |
| VAL-02 | Validation Steps | Backend feature/API tests for admin CRUD/reorder/delete, public catalogs, user selection persistence, compatibility `/map/filters`, and canonical payload handling. | backend feature tests + ability catalog check | Settings/admin API rerun passed `3 tests, 21 assertions`; settings image URI persistence rerun passed `1 test, 10 assertions`; Map catalog/backfill/provider rerun passed `4 tests, 37 assertions`; `rg -n "discovery-filters-settings:update" laravel-app/config/abilities.php` returned line `46`. | Laravel Docker app container with isolated MongoDB test databases | passed | Covers schema, update ability, patch order/delete semantics, image URI persistence, public catalog, compatibility/backfill, and canonical payload handling. |
| VAL-03 | Validation Steps | Migration tests for `settings.map_ui.filters` backfill and idempotency. | automated test | Laravel focused Map run passed `test_discovery_filters_backfill_map_ui_filters_is_idempotent`; Flutter codec evidence at `flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart:11`, `:44`. | Laravel Docker app container and Flutter host test runner | passed | Migration/backfill idempotency has local evidence. |
| VAL-04 | Validation Steps | Target adapter/read-model tests for Map POI, Home Events/Event Occurrence, and Account Profile Discovery. | backend feature tests + Flutter transport tests | Map source/types safe-runner test passed `1 test, 8 assertions`; Agenda typed taxonomy safe-runner test passed `1 test, 9 assertions`; Account Profile filter safe-runner tests passed `3 tests, 9 assertions`; Flutter focused suite covered repository/query payload behavior for account profiles and schedule. | Laravel safe runner / local Docker Mongo + Flutter host test runner | passed | Validates the target adapters/read-model query paths instead of local post-fetch filtering. |
| VAL-05 | Validation Steps | Flutter package unit and widget/golden tests for selection semantics, layout policies, subfilter groups, loading states, and payload serialization. | automated test | `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_core_test.dart packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart ...` passed in the `174 tests` focused suite; representative package assertions at `flutter-app/packages/belluga_discovery_filters/test/discovery_filter_core_test.dart:251` and `flutter-app/packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart:6`, `:44`, `:77`, `:123`. | Flutter host test runner | passed | Flutter package unit/widget evidence passed. |
| VAL-06 | Validation Steps | Flutter mobile/device integration tests for Map, Home/Event-list, and Profile Discovery against backend data. | Android device checklist + Playwright parity evidence | Device checklist `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md` marks `[x]` for `feature_map_filter_catalog_admin_to_public_e2e_test.dart`, `feature_home_agenda_eligible_events_query_contract_e2e_test.dart`, `feature_agenda_filters_regression_test.dart`, and `feature_map_event_filter_actions_test.dart`; final Web mutation lane passed `12 passed (7.4m)`. | Android device `192.168.15.9:5555` support evidence + final Web runtime | passed | Public filter behavior is shared Flutter package/backend query behavior; final Playwright closes shared visible behavior and prior ADB checklist remains device support evidence. |
| VAL-07 | Validation Steps | Flutter Web integration tests for the same critical flows, responsive layout, sticky filters, active hints, and selected states. | Web build + Playwright navigation/filter assertions | `node --check tools/flutter/web_app_tests/discovery_filters.spec.js` passed; `bash scripts/build_web.sh ../web-app dev` passed; served `main.dart.js` hash matched local `2a022493...`; final mutation command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` passed `12 passed (7.4m)`. | Final Web browser runtime | passed | Project-owned Web validation is Playwright-based; final mutation suite includes Admin, Map, Home, and Discovery filter journeys. |

## Definition of Done

- [x] Laravel and Flutter package-level tests prove filter grammar, registry/provider behavior, catalog serialization, cascade validation, stale selection repair, and payload emission.
- [x] Admin `Filtros` section exposes `Mapa`, `Eventos na Tela Principal`, and `Descoberta de Perfis` with contextual policies and no per-filter placement selector.
- [x] Existing Map filters migrate into `Filtros > Mapa` preserving order, key, label, image, marker override, query constraints, and legacy behavior.
- [x] Public Map preserves current single-active visual behavior while supporting multi-select contexts without hiding siblings.
- [x] Home/Event-list exposes a title-row `Filtro` action left of radius/distance, restores persisted active state with a clear hint, opens with selections selected, and keeps the filter sticky while scrolling.
- [x] Account Profile Discovery shows the primitive primary filter line by default below the title and keeps it sticky while scrolling.
- [x] Backend result tests prove real persisted-data filtering for Map POIs, Home Events/Event Occurrences, and Account Profile Discovery.
- [x] Integration/Web tests fail if Flutter fetches broad result sets and filters locally.

## Validation Steps

- [x] Backend package/unit tests for registry, DTO/value objects, provider resolution, taxonomy constraints, target compiler availability, and stale selection repair.
- [x] Backend feature/API tests for admin CRUD/reorder/delete, public catalogs, user selection persistence, compatibility `/map/filters`, and canonical payload handling.
- [x] Migration tests for `settings.map_ui.filters` backfill and idempotency.
- [x] Target adapter/read-model tests for Map POI, Home Events/Event Occurrence, and Account Profile Discovery.
- [x] Flutter package unit and widget/golden tests for selection semantics, layout policies, subfilter groups, loading states, and payload serialization.
- [x] Flutter mobile/device integration tests for Map, Home/Event-list, and Profile Discovery against backend data.
- [x] Flutter Web integration tests for the same critical flows, responsive layout, sticky filters, active hints, and selected states.

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Package-first behavior and anti-local-filtering requirements need high-coverage verification. | Backend package/API/integration tests, Flutter widget/integration/Web tests | `planned` |

## Complexity

- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** New cross-stack package boundary, admin IA relocation, migration compatibility, public UI component extraction, persisted user settings, and multiple read-model adapters.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Planned decision promotion targets (module sections):**
  - Public filter component contract in Flutter client experience.
  - Map filter compatibility and canonical payload adapter in Map POI module.
  - Agenda/event occurrence filtering contract in Events module.
  - Account Profile Discovery filter contract in Account Profile Catalog module.
  - Tenant-admin `Filtros` IA in Tenant Admin module.
- **Module decision consolidation targets:**
  - Stable filter grammar, entity/target terminology, and first surface keys.
  - Package boundary and target adapter ownership.
  - Public UI and persisted selection behavior.

## Package-First Assessment

- [x] Package query rerun after blockers cleared: `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "filter discovery taxonomy map event account profile"` returned `0 package(s) found`.
- [x] Full package inventory checked: `belluga_admin_ui` remains admin UI primitives only; `belluga_form_validation` covers form validation/422 rendering only; ecosystem packages `stream_value`, `value_object_pattern`, `push_handler`, and `event_tracker_handler` are not discovery-filter packages.
- [x] Decision remains new local Package-First discovery-filter packages, likely named around `belluga_discovery_filters`, with host integrations registering concrete providers/adapters.

## Decisions

- [x] `D-B-01` TODO B must wait for TODO A taxonomy display snapshots before implementation starts.
- [x] `D-B-02` Use local Package-First Laravel and Flutter packages for canonical discovery filters.
- [x] `D-B-03` Package owns grammar, DTO/value objects, placement policies, entity registry contracts, provider contracts, cascade validation, serialization, catalog contracts, and reusable contextual editor/component primitives.
- [x] `D-B-04` Package does not own target/read-model query execution. Map POI, Events/Agenda, Account Profile Discovery, and Static Asset integrations own their compilers/adapters.
- [x] `D-B-05` Canonical axes are `entity`, `type`, and `target`. Reject `entity_type` and `entity_family`.
- [x] `D-B-06` `MapPoi` and `event_occurrence` are targets/read models, not entities. `event`, `account_profile`, and `static_asset` are entities.
- [x] `D-B-07` Existing Map `source` remains a legacy/adapter alias for canonical `entity` during compatibility.
- [x] `D-B-08` Entity and type selections may be multi-select by context policy; type state must be entity-qualified, not a global flat slug list.
- [x] `D-B-09` Type options are derived from selected entities through registry/provider resolution and must expose loading/empty/error/stale states.
- [x] `D-B-10` Secondary taxonomy terms are constrained by selected entity/type pairs and type-level `allowed_taxonomies`; OR within a taxonomy group and AND across groups unless later explicitly changed.
- [x] `D-B-11` Registry/provider implementation in Laravel uses Service Container/ServiceProvider bindings, not a static concrete-module global map.
- [x] `D-B-12` Initial materialized surfaces are `public_map.primary`, `home.events`, and `discovery.account_profiles`.
- [x] `D-B-13` Admin IA moves filters to main `Filtros` with submenus `Mapa`, `Eventos na Tela Principal`, and `Descoberta de Perfis`.
- [x] `D-B-14` Existing Map filter editor/component is the canonical reference to extract/generalize, not something to rebuild from scratch.
- [x] `D-B-15` Public filter package owns filter/subfilter visuals, selected/unselected state, loading/busy feedback, cascade validation, state, and payload. Screens own search, dock/container, result cards/lists, map camera/markers, and surrounding chrome.
- [x] `D-B-16` Single-active mode preserves current Map behavior: selected primary expands with backend label/color/visual, siblings remain visible compact, and tapping another switches/deactivates previous with visual feedback.
- [x] `D-B-17` Multi-select mode keeps siblings visible and toggles selected state without deactivating other selected filters.
- [x] `D-B-18` Subfilters render below a subtle divider as taxonomy-grouped blocks with optional title, title override, no empty title spacing, selectable chips, and policy-configurable single/multi selection.
- [x] `D-B-19` Home/Event-list gets a title-row `Filtro` action left of radius/distance; active restored filters show a clear hint and open with selections selected.
- [x] `D-B-20` Account Profile Discovery shows the primitive primary filter line by default below the title and keeps it sticky while scrolling.
- [x] `D-B-21` Public selections persist in user settings/preferences per surface/context and are validated/repaired against the current catalog before query.
- [x] `D-B-22` Tests must prove real backend/read-model filtering against persisted data; local post-fetch filtering is forbidden as a correctness mechanism.

## Decision Pending

- [x] None for product/design handoff. Remaining naming/API shape choices are implementation-local and must follow existing Laravel/Flutter package best practices.

## Questions To Close

- [x] None before implementation after TODO A completes.

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-B-*` decisions above are frozen for Store Release orchestration. Implementation must preserve the Package-First boundary, `entity/type/target` grammar, ServiceProvider/provider registry model, contextual admin IA, public filter component behavior, persisted selection repair, migration compatibility, and anti-local-filtering verification.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** Package scaffolding and fail-first package tests for canonical grammar, registry/provider behavior, cascade validation, stale selection repair, and payload serialization.
- **Sequencing note:** Code implementation may start now. Event/EventOccurrence adapters must consume the SR-D selected-occurrence/read-model contract and all taxonomy labels must consume SR-A snapshots.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-B-01` | Current Map filter UI can be extracted/generalized without losing current behavior. | User-approved direction and existing Map behavior is the visual baseline. | Implementation may need a compatibility wrapper first, but the visual contract remains. | `Medium` | `Keep as Assumption` |
| `A-B-02` | User settings/preferences can persist per-surface filter selections without a new identity model. | Existing repository/controller settings patterns were approved as sufficient direction. | Persistence shape may need a small backend settings endpoint but not a product decision. | `Medium` | `Keep as Assumption` |
| `A-B-03` | Static Assets need registry/read-model parity but no first-slice dedicated public discovery surface. | Approved first admin surfaces are Map, Home Events, and Profile Discovery. | Add `discovery.static_assets` as a separate TODO/surface if product requires it. | `High` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces

- New/updated local Laravel discovery-filter package and host service providers/adapters.
- New/updated local Flutter discovery-filter package and host screen integrations.
- Tenant-admin filter navigation/pages.
- Map filter migration/compatibility paths.
- Home Events and Account Profile Discovery controllers/repositories/screens.
- Backend target adapters/read-model query services.
- Tests across package, feature/API, migration, integration, and Web lanes.

### Ordered Steps

1. Complete TODO A and freeze taxonomy display snapshot schema.
2. Add fail-first package tests for grammar, registry, provider resolution, cascade validation, and stale selection repair.
3. Scaffold Laravel and Flutter package contracts with no target-specific query execution inside the package.
4. Implement concrete providers/compilers for Map POI, Home Events/Event Occurrence, and Account Profile Discovery.
5. Migrate Map filter storage and keep compatibility `/map/filters` behavior.
6. Build admin `Filtros` IA and contextual editors using the existing Map editor as the base.
7. Extract/generalize public filter component and integrate Map/Home/Profile surfaces.
8. Add persisted user selection read/write/repair and active restored-state UI.
9. Run backend, Flutter device, and Web validation with anti-local-filtering assertions.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** Filter behavior is contract-driven and easy to fake incorrectly through local filtering.
- **Fail-first targets:** Package unit tests, backend adapter tests with false-positive records, Flutter widget tests for selected/subfilter behavior, and integration tests asserting canonical payloads reach result endpoints.

### Runtime / Rollout Notes

- Map migration must be idempotent and preserve existing tenant filter behavior.
- Public catalog responses should carry version/revision to detect stale persisted selections and local caches.
- Cache invalidation must include filter definitions, entity type registries, taxonomies, taxonomy terms, and user selection updates where bundled.

## Audit Trigger Matrix

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `big` | Cross-stack package/system capability. |
| `blast_radius` | `cross-stack` | Laravel package/API/migration and Flutter package/UI. |
| `behavioral_change_or_bugfix` | `yes` | New filtering behavior. |
| `changes_public_contract` | `yes` | New catalogs/payloads/persisted selection shape. |
| `touches_auth_or_tenant` | `yes` | Tenant-admin configs and user settings. |
| `touches_runtime_or_infra` | `yes` | Migration/cache/versioning and read-model queries. |
| `touches_tests` | `yes` | Broad test matrix required. |
| `critical_user_journey` | `yes` | Map/Home/Discovery public flows. |
| `release_or_promotion_critical` | `yes` | Store Release usability priority. |
| `high_severity_plan_review_issue` | `no` | No plan review issue recorded yet. |
