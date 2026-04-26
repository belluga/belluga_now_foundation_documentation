# TODO (Store Release): Taxonomy Term Display Snapshots

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
**Status:** Promotion Lane
**Owners:** Laravel Team, Flutter Team
**Objective:** Stop public/admin consumers from rendering taxonomy slugs by promoting display-ready taxonomy term snapshots into document/read-model payloads while preserving slug-based filtering/indexes.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** `foundation_documentation/artifacts/tmp/improvement-intake-session-2026-04-20.md` (`C-04`)
- **Dependency role:** TODO A. This TODO blocks `TODO-store-release-typed-discovery-filters-package.md`.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `C-04`
- **Why this is the right current slice:** The problem is one bounded cross-stack contract defect: persisted/read-model taxonomy terms expose machine slugs without display labels, causing Flutter to render slug-like values in lists/details and making future filter UI label contracts unreliable.
- **Direct-to-TODO rationale:** The architecture decision is already settled by the document-database read model: write-time denormalized display snapshots and indexed machine keys. No broader feature brief is needed before implementation.

## Delivery Status Canon

- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Guard-passed on 2026-04-26 after runtime/mutation evidence reconciliation; only promotion follow-through remains.`
- **Next exact step:** Include this TODO in Store Release promotion orchestration; no implementation work remains unless promotion validation finds a new defect.

## Package-First Assessment

- **Status:** completed before implementation.
- **Queries run:** `taxonomy`, `event`, `map`, and full local/ecosystem package scan from the ecosystem root.
- **Result:** no existing local/ecosystem package owns the taxonomy snapshot/display-label contract. The unrelated ecosystem package `event_tracker_handler` does not cover this domain. Implementation stayed inside existing Laravel/Flutter modules instead of creating a new package for SR-A.

## Scope

- [ ] Define one canonical taxonomy term display snapshot contract for public/admin read payloads.
- [ ] Keep filtering/query identity on stable machine keys: `type`, `value`, and flattened `type:value`.
- [ ] Embed display labels into source documents/read models used by public list/detail flows: Account Profiles, Static Assets, Events, Event Occurrences, and Map POIs.
- [ ] Include taxonomy group display metadata needed by grouped UI surfaces and future discovery filters.
- [ ] Add fanout/reprojection when taxonomy or taxonomy-term display names change.
- [ ] Add an idempotent backfill/repair command or job for legacy documents containing only `{type, value}`.
- [ ] Preserve or widen Flutter parsing fallback so `name` wins, `label` remains compatibility, and raw `value` is only the last resort.
- [ ] Cover public detail/list payloads, Map filter labels, and admin readback paths that currently expose slug values.

## Out of Scope

- [ ] Building the reusable discovery-filter package or public filter UI. That belongs to `TODO-store-release-typed-discovery-filters-package.md`.
- [ ] Allowing casual taxonomy term slug renames after use. Slug rename requires an explicit migration/fanout operation outside this TODO.
- [ ] Redesigning taxonomy ownership, `applies_to`, or type-level `allowed_taxonomies` rules beyond the snapshot/display contract.
- [ ] Implementing free-tag display semantics if tags are not backed by structured taxonomy terms.

## Blocker Notes

- **Blocker:** `n/a`
- **Why blocked now:** `n/a`
- **What unblocks it:** `n/a`
- **Owner / source:** `n/a`
- **Last confirmed truth:** Public Account Profile, Static Asset, Event, and Map POI consumers can receive taxonomy terms as `{type, value}` only; Flutter parsers already prefer `name`/`label` when available, so backend payload enrichment is the correct root fix.

## Execution Lane Tracking

- **Local implementation branches:** `orchestrator/store-release-usability-wave` in `belluga_now_docker`, `laravel-app`, and `flutter-app`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Taxonomy display snapshots | `orchestrator/store-release-usability-wave` | `n/a - not promoted yet` | `n/a - not promoted yet` | `n/a - not promoted yet` | `Local-Implemented; final runtime acceptance passed` |

## Local Implementation Evidence

- Laravel canonical snapshot support implemented across Account Profile, Static Asset, Event, Event Occurrence, and Map POI read/projection paths.
- Snapshot shape is `{type, value, name, taxonomy_name, label?}`; `name` is canonical display, `label` is compatibility, and filtering remains on `type`, `value`, and `type:value`.
- Taxonomy/term display-name updates dispatch tenant-scoped fanout repair jobs; normal term slug/value rename is rejected; legacy data can be repaired by an idempotent backfill command/job.
- Flutter parsers/domain models preserve `name`, `taxonomy_name`, and `label`; UI fallback is `name -> label -> value`.
- Canonical module docs promoted the contract in Account Profile Catalog, Events, Map POI, Flutter Client Experience, and Tenant Admin modules.

## Local Validation Evidence

- 2026-04-22 SR-A guard baseline: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-taxonomy-term-display-snapshots.md` returned `Overall outcome: no-go` only because `Completion Evidence Matrix` rows were missing.
- 2026-04-22 SR-A Account Profile API rerun: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php --filter "test_account_profile_create_accepts_allowed_taxonomy"` passed: `1 test, 6 assertions`.
- 2026-04-22 SR-A Static Asset API rerun: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/StaticAssets/StaticAssetsControllerTest.php --filter "test_static_asset_create_and_public_read"` passed: `1 test, 15 assertions`.
- 2026-04-22 SR-A taxonomy backfill/fanout rerun with isolated local Mongo DB names: `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_sra_tax -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_sra_tax_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_sra_tax_tenant -e DB_DATABASE=belluga_sra_tax -e DB_DATABASE_LANDLORD=belluga_sra_tax_landlord -e DB_DATABASE_TENANTS=belluga_sra_tax_tenant app php artisan test tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` passed: `2 tests, 19 assertions`.
- 2026-04-22 SR-A Event/Event Occurrence API rerun with isolated local Mongo DB names: `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_sra_event -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_sra_event_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_sra_event_tenant -e DB_DATABASE=belluga_sra_event -e DB_DATABASE_LANDLORD=belluga_sra_event_landlord -e DB_DATABASE_TENANTS=belluga_sra_event_tenant app php artisan test tests/Feature/Events/EventCrudControllerTest.php --filter "test_event_create_accepts_allowed_taxonomy"` passed: `1 test, 8 assertions`.
- 2026-04-22 SR-A Event query unit rerun with isolated local Mongo DB names: `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_sra_eventquery -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_sra_eventquery_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_sra_eventquery_tenant -e DB_DATABASE=belluga_sra_eventquery -e DB_DATABASE_LANDLORD=belluga_sra_eventquery_landlord -e DB_DATABASE_TENANTS=belluga_sra_eventquery_tenant app php artisan test tests/Unit/Events/EventQueryServiceTest.php` passed: `2 tests, 4 assertions`.
- 2026-04-22 SR-A Map POI/filter rerun with isolated local Mongo DB names: `docker compose exec -T -e APP_ENV=testing -e APP_URL=http://nginx -e APP_HOST=nginx -e DB_URI=mongodb://mongo:27017/belluga_sra_map -e DB_URI_LANDLORD=mongodb://mongo:27017/belluga_sra_map_landlord -e DB_URI_TENANTS=mongodb://mongo:27017/belluga_sra_map_tenant -e DB_DATABASE=belluga_sra_map -e DB_DATABASE_LANDLORD=belluga_sra_map_landlord -e DB_DATABASE_TENANTS=belluga_sra_map_tenant app php artisan test tests/Feature/Map/MapPoisControllerTest.php --filter "(test_map_poi_lookup_returns_poi_by_typed_reference|test_map_near_returns_cards_with_tags_and_taxonomy|test_map_filters_returns_catalogs)"` passed: `3 tests, 50 assertions`.
- 2026-04-22 SR-A Flutter focused rerun: `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/map/map_filter_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/presentation/tenant_admin/static_assets/tenant_admin_static_asset_detail_screen_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/infrastructure/services/http/laravel_map_poi_http_service_test.dart test/infrastructure/dal/dto/schedule/schedule_dto_mapper_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart` passed: `116 tests`.
- 2026-04-22 SR-A invalid harness attempts: combined Laravel class runs against shared default Mongo DB names were discarded as evidence because they failed with `database is in the process of being dropped`; isolated local DB reruns above passed.
- `docker compose exec -T app php -l app/Application/Taxonomies/TaxonomySnapshotBackfillService.php` passed.
- `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventQueryService.php` passed.
- `docker compose exec -T app php -l tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` passed.
- `docker compose exec -T -e APP_ENV=testing ... app ./vendor/bin/phpunit tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` passed: `2 tests, 19 assertions`.
- `docker compose exec -T -e APP_ENV=testing ... app ./vendor/bin/phpunit tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php tests/Feature/StaticAssets/StaticAssetsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Feature/Map/MapPoisControllerTest.php tests/Unit/Events/EventQueryServiceTest.php` passed: `135 tests, 815 assertions`.
- `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/map/map_filter_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/presentation/tenant_admin/static_assets/tenant_admin_static_asset_detail_screen_test.dart` passed: `22 tests`.
- `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart` passed: `63 tests`.
- `fvm flutter test test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/infrastructure/services/http/laravel_map_poi_http_service_test.dart test/infrastructure/dal/dto/schedule/schedule_dto_mapper_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart` passed in reconciliation: `76 tests`.
- `fvm dart analyze --format machine` passed after resolving the related SR-C domain primitive analyzer finding in `partner_profile_config_builder.dart`.
- 2026-04-22 SR-A rerun: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` from `laravel-app` passed: `2 tests, 19 assertions`.
- 2026-04-22 SR-A focused Flutter rerun: `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/map/map_filter_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_admin/static_assets/tenant_admin_static_asset_detail_screen_test.dart` passed: `64 tests`.
- 2026-04-22 SR-A source-owned runtime spec added: `tools/flutter/web_app_tests/taxonomy_display_snapshots.spec.js`. `NAV_WEB_TEST_TYPE=readonly node --check tools/flutter/web_app_tests/taxonomy_display_snapshots.spec.js && NAV_WEB_TEST_TYPE=readonly node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` passed. The spec was not executed against a final web bundle/runtime URL in this worker slice because no `NAV_TENANT_URL` final runtime was provided.
- 2026-04-22 SR-A follow-up classification: orchestrator failure at `account_profiles?per_page=50` was a test/harness defect. Tenant-public list/detail/filter APIs are protected by `auth:sanctum`; Flutter uses `TenantPublicAuthHeaders.build(bootstrapIfEmpty: true)` and obtains an anonymous bearer through `/api/v1/anonymous/identities`. The Playwright spec now follows that same anonymous tenant-public auth path instead of issuing unauthenticated API requests.
- 2026-04-22 SR-A follow-up authenticated API probe: anonymous token POST to `https://guarappari.belluga.space/api/v1/anonymous/identities` succeeded; authenticated `GET /api/v1/account_profiles?per_page=50`, `GET /api/v1/events?per_page=50`, and `GET /api/v1/map/filters` returned `200`.
- 2026-04-22 SR-A follow-up targeted runtime run: `NODE_PATH=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/tools/flutter/web_app_smoke_runner/node_modules NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_TEST_TYPE=readonly npx playwright test --config ./playwright.config.js ../web_app_tests/taxonomy_display_snapshots.spec.js --grep @readonly --reporter=line` failed only at Map filter seed/backfill acceptance. Account Profile and Event visible assertions advanced; Map filter catalog returned `taxonomy_terms: [{"type":"genre","value":"brasilidades","name":"brasilidades","taxonomy_name":"genre","label":"brasilidades","count":1}]`, so no Map taxonomy snapshot with `name/label != value` exists on the dev runtime.
- 2026-04-22 SR-A runtime data repair: `docker compose exec -T app php artisan taxonomies:term-snapshots:repair --all` repaired the dev tenant runtime snapshots, including Map POI taxonomy snapshots needed by `/api/v1/map/filters`.
- 2026-04-22 SR-A final Web freshness: `bash scripts/build_web.sh ../web-app dev` produced the served bundle; `sha256sum ../web-app/main.dart.js` and `curl -k -L 'https://guarappari.belluga.space/main.dart.js?cachebust=runtime-validation-20260422' | sha256sum` both returned `2a022493dff34f9c906c1352b769cd55237f39805c22f5e48dc3c24890060f9b`.
- 2026-04-22 SR-A final Playwright readonly: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` passed `6 passed (1.6m)`, including `tools/flutter/web_app_tests/taxonomy_display_snapshots.spec.js`.
- 2026-04-22 SR-A final completion guard: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-taxonomy-term-display-snapshots.md --require-delivery` returned `Overall outcome: go`.

## Final Runtime Acceptance Reconciliation

- 2026-04-22 PACED correction: Laravel projection/fanout/backfill tests, Flutter parser/widget/repository tests, PHP lint, and analyzer remain valid implementation/supporting evidence. They do not replace final visible acceptance where the criterion is about users no longer seeing slugs.
- 2026-04-22 final runtime acceptance passed after dev runtime Map POI taxonomy snapshot repair and current Web bundle verification.
- Platform parity classification: taxonomy label rendering uses shared Flutter parser/rendering and backend payload contracts across Android/Web. The final Playwright readonly lane is sufficient for visible acceptance; existing Laravel/Flutter tests remain supporting evidence.
- 2026-04-22 SR-A final runtime spec path: `tools/flutter/web_app_tests/taxonomy_display_snapshots.spec.js` executed through `tools/flutter/run_web_navigation_smoke.sh readonly` and passed inside the `6 passed (1.6m)` readonly suite.

| Criterion ID | Current supporting evidence | Final acceptance gap | Required next evidence |
| --- | --- | --- | --- |
| DOD-02 | Laravel payload tests, Flutter parser/widget tests, and source-owned Playwright spec path exist. | Closed. | Final readonly Playwright suite passed `6 passed (1.6m)` against `https://guarappari.belluga.space`, including Account Profile/Event/Map taxonomy display assertions. |
| DOD-06 | Backend propagation and Flutter parser evidence exists. Follow-up Playwright spec authenticates through anonymous tenant-public bootstrap and reaches Map filter assertions. | Closed. | Dev runtime Map POI projections were repaired and final readonly Playwright passed, proving Map filter display labels while requests still use machine keys. |

## Definition of Done

- [x] Account Profile, Static Asset, Event, Event Occurrence, and Map POI payloads expose display-ready taxonomy term snapshots for all structured taxonomy terms.
- [x] Public list/detail/admin readback UI no longer displays raw taxonomy slug values when a taxonomy term display name exists.
- [x] Query/filter paths still use machine keys and indexes, never display labels.
- [x] Taxonomy/term display-name edits dispatch and complete fanout/reprojection for every affected document/read model.
- [x] Backfill repairs existing `{type, value}` documents idempotently and reports repaired/skipped/failing counts.
- [x] Tests prove snapshot propagation across all affected read models and public payloads, including Map filter labels.

## Validation Steps

- [x] Laravel package/service/unit tests for snapshot resolver, normalization, fanout dispatch, fanout execution, and backfill idempotency.
- [x] Laravel feature/API tests for Account Profile, Static Asset, Event, Event Occurrence/Agenda, and Map POI/list/detail payloads.
- [x] Flutter parser/domain tests proving `name -> label -> value` fallback and no slug display when labels exist.
- [x] Targeted Flutter widget tests for taxonomy chip/list/detail rendering where relevant.
- [x] Migration/backfill test with legacy documents containing only `{type, value}`.

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | Definition of Done | Account Profile, Static Asset, Event, Event Occurrence, and Map POI payloads expose display-ready taxonomy term snapshots for all structured taxonomy terms. | Automated tests | Account Profile `run_laravel_tests_safe.sh ... AccountProfilesControllerTest.php --filter "test_account_profile_create_accepts_allowed_taxonomy"` -> `1 test, 6 assertions`; Static Asset `run_laravel_tests_safe.sh ... StaticAssetsControllerTest.php --filter "test_static_asset_create_and_public_read"` -> `1 test, 15 assertions`; Event isolated `php artisan test ... EventCrudControllerTest.php --filter "test_event_create_accepts_allowed_taxonomy"` -> `1 test, 8 assertions`; Map isolated `php artisan test ... MapPoisControllerTest.php` with lookup, near-taxonomy, and filters-catalog filter -> `3 tests, 50 assertions`. | Laravel local Docker / Mongo | passed | Asserts `type`, `value`, `name`, `taxonomy_name`, and compatibility `label` across source/read-model families. |
| `DOD-02` | Definition of Done | Public list/detail/admin readback UI no longer displays raw taxonomy slug values when a taxonomy term display name exists. | Automated tests + Playwright navigation | Supporting Flutter command `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/map/map_filter_taxonomy_term_dto_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_admin/static_assets/tenant_admin_static_asset_detail_screen_test.dart` -> `64 tests`; final runtime command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` passed `6 passed (1.6m)`, including `tools/flutter/web_app_tests/taxonomy_display_snapshots.spec.js`. | Flutter host + final Web runtime `https://guarappari.belluga.space` | passed | Playwright proves public Account Profile/Event/Map taxonomy labels render display names instead of raw slug fallbacks on the served bundle. |
| `DOD-03` | Definition of Done | Query/filter paths still use machine keys and indexes, never display labels. | Automated tests + Playwright navigation/browser evidence | Laravel tests listed in `DOD-01`; `EventQueryServiceTest.php` isolated command -> `2 tests, 4 assertions`; Flutter focused command -> `116 tests`; final runtime command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` passed `6 passed (1.6m)`, including `tools/flutter/web_app_tests/taxonomy_display_snapshots.spec.js`. | Laravel local Docker / Flutter host / final Web runtime `https://guarappari.belluga.space` | passed | Backend filters remain on `taxonomy_terms_flat`, `type:value`, or `type/value`; Flutter account-profile and map HTTP tests send canonical machine-key filter arrays. The Playwright spec clicks the public Map filter and waits for `/api/v1/map/pois` carrying the backend query value while the visible label uses the display snapshot, proving final browser behavior does not query by display labels. |
| `DOD-04` | Definition of Done | Taxonomy/term display-name edits dispatch and complete fanout/reprojection for every affected document/read model. | Automated integration test with backend mutation evidence | Isolated taxonomy integration test command: `php artisan test tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` with local Mongo DB overrides -> `2 tests, 19 assertions`. | Laravel local Docker / isolated Mongo DB names | passed | `test_taxonomy_display_name_updates_dispatch_fanout_and_term_slug_update_is_rejected` performs the taxonomy/term backend mutation path, asserts queued fanout dispatch, synchronous execution, repaired snapshots across document/read models, and slug update rejection. Public browser visibility of the repaired snapshots is separately covered by `DOD-02`, `DOD-03`, and `DOD-06`. |
| `DOD-05` | Definition of Done | Backfill repairs existing `{type, value}` documents idempotently and reports repaired/skipped/failing counts. | Automated test | Isolated taxonomy command: `php artisan test tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` with local Mongo DB overrides -> `2 tests, 19 assertions`. | Laravel local Docker / isolated Mongo DB names | passed | `test_repair_backfills_legacy_snapshots_across_document_read_models_idempotently` seeds legacy `{type,value}` records across Account Profile, Static Asset, Event, Event Occurrence, linked-profile, and Map POI, then reruns repair for idempotency/counts. |
| `DOD-06` | Definition of Done | Tests prove snapshot propagation across all affected read models and public payloads, including Map filter labels. | Automated tests + Playwright navigation | Laravel targeted commands: Account Profile `1 test, 6 assertions`; Static Asset `1 test, 15 assertions`; Taxonomy fanout/backfill latest rerun `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` -> `2 tests, 19 assertions`; Event/Event Occurrence `1 test, 8 assertions`; Event query unit `2 tests, 4 assertions`; Map POI/filter labels `3 tests, 50 assertions`; Flutter focused latest rerun -> `64 tests`; runtime repair `docker compose exec -T app php artisan taxonomies:term-snapshots:repair --all`; final Playwright readonly passed `6 passed (1.6m)` including `taxonomy_display_snapshots.spec.js`. | Laravel local Docker / Flutter host / final Web runtime | passed | Structural propagation and final visible Map filter label acceptance both passed; Map filter requests remain machine-key based while labels use display snapshots. |
| `VAL-01` | Validation Steps | Laravel package/service/unit tests for snapshot resolver, normalization, fanout dispatch, fanout execution, and backfill idempotency. | Automated tests | `php artisan test tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` with isolated DBs -> `2 tests, 19 assertions`; `php artisan test tests/Unit/Events/EventQueryServiceTest.php` with isolated DBs -> `2 tests, 4 assertions`. | Laravel local Docker / isolated Mongo DB names | passed | Taxonomy test exercises resolver, normalization, fanout dispatch/execution, and idempotent backfill across persisted document families. |
| `VAL-02` | Validation Steps | Laravel feature/API tests for Account Profile, Static Asset, Event, Event Occurrence/Agenda, and Map POI/list/detail payloads. | Automated tests | Account Profile targeted command -> `1 test, 6 assertions`; Static Asset targeted command -> `1 test, 15 assertions`; Event/Event Occurrence targeted command -> `1 test, 8 assertions`; Map POI lookup/near/filter targeted command -> `3 tests, 50 assertions`. | Laravel local Docker / Mongo | passed | Covers admin readback, public readback, Event Occurrence persistence/readback, Map lookup/list-like near payloads, and Map filter labels. |
| `VAL-03` | Validation Steps | Flutter parser/domain tests proving `name -> label -> value` fallback and no slug display when labels exist. | Automated tests | Latest focused Flutter command listed in `DOD-02` -> `64 tests`. | Flutter host / FVM | passed | DTO/parser/domain coverage includes tenant-admin taxonomy terms, map filter taxonomy terms, event linked-profile taxonomy terms, and Laravel account-profile backend parsing. |
| `VAL-04` | Validation Steps | Targeted Flutter widget tests for taxonomy chip/list/detail rendering where relevant. | Automated tests | Latest focused Flutter command listed in `DOD-02` -> `64 tests`. | Flutter host / FVM | passed | `tenant_admin_static_asset_detail_screen_test.dart` asserts the displayed taxonomy chip uses `Samba` and not raw slug `samba`; account-profile detail widget coverage is included in the same focused command. |
| `VAL-05` | Validation Steps | Migration/backfill test with legacy documents containing only `{type, value}`. | Automated test | Migration/backfill artifact: `php artisan test tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` with isolated DBs -> `2 tests, 19 assertions`. | Laravel local Docker / isolated Mongo DB names | passed | Legacy `{type,value}` documents are repaired across all affected document/read-model families and rerun to prove idempotency/count reporting. |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Cross-read-model fanout and migration require test-quality scrutiny before production readiness. | Laravel tests, Flutter parser/widget tests | `planned` |

## Complexity

- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** Cross-stack contract change touching source documents, denormalized read models, fanout jobs, backfill, public/admin payloads, and Flutter display fallback.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Planned decision promotion targets (module sections):**
  - Account Profile public/list/detail taxonomy payload contract.
  - Events read model taxonomy snapshot contract.
  - Map POI projection taxonomy snapshot contract.
  - Flutter taxonomy display fallback contract.
- **Module decision consolidation targets:**
  - `account_profile_catalog_module.md` decision baseline.
  - `events_module.md` contract summary/read model section.
  - `map_poi_module.md` materialized POI projection section.
  - `flutter_client_experience_module.md` type/taxonomy visual consumption contract.

## Decisions

- [x] `D-A-01` Canonical taxonomy term display snapshot uses `type`, `value`, `name`, and `taxonomy_name`. `label` may be emitted as a compatibility alias during migration, but `name` is the canonical term display field.
- [x] `D-A-02` `taxonomy_terms_flat` remains the filter/index shape using `type:value`; display labels are never query keys.
- [x] `D-A-03` Account Profile, Static Asset, Event, Event Occurrence, and Map POI read models must converge on the same snapshot shape instead of solving labels per endpoint.
- [x] `D-A-04` Label fanout should be queued after the taxonomy/term write commits. Do not special-case "small tenants" with a separate synchronous path unless implementation proves it is already the repository pattern.
- [x] `D-A-05` Term slug rename is prohibited as a normal edit after use. If slug rename is needed, it is an explicit migration/fanout workflow.
- [x] `D-A-06` Backfill must be an idempotent command/job that batch-resolves missing display snapshots and reports deterministic counts.
- [x] `D-A-07` Batch resolving is acceptable for migration/backfill and defensive fallback, not as the normal per-item public list/detail path.
- [x] `D-A-08` Tests must cover both dispatch contract and final persisted fanout state: fake/assert queued jobs and execute jobs synchronously in targeted tests.
- [x] `D-A-09` Store Release scope includes public details/lists, Map filter labels, and admin readback screens because typed filters depend on this schema and current users should not see raw slugs.
- [x] `D-A-10` Flutter consumers keep defensive fallback ordering `name -> label -> value`, but passing tests must prove valid payloads render `name`, not the slug fallback.

## Decision Pending

- [x] None. All material C-04 gaps were closed by session decisions or implementation-local best practice.

## Questions To Close

- [x] None before implementation.

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-A-*` decisions above are frozen for Store Release orchestration. Implementation must preserve the canonical snapshot shape, machine-key filtering, queued fanout, idempotent backfill, compatibility fallback, and full read-model propagation coverage.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** Laravel fail-first tests for taxonomy snapshot payload shape across Account Profile, Static Asset, Event, Event Occurrence, and Map POI read models.
- **Sequencing note:** This is TODO A and must complete before `TODO-store-release-typed-discovery-filters-package.md` implementation starts.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-A-01` | Existing taxonomy term resolver can be reused or adapted for snapshot enrichment. | Intake found `TaxonomyTermSummaryResolverService` already used by linked-profile metadata. | Implementation may need a new resolver/service but the contract remains unchanged. | `Medium` | `Keep as Assumption` |
| `A-A-02` | Read-heavy/write-rare taxonomy labels justify denormalized snapshots in document/read models. | Document DB posture and user-approved performance direction. | Runtime joins would need explicit approval due list/detail performance risk. | `High` | `Promote to Decision` |
| `A-A-03` | Existing Flutter taxonomy parsers can support additive fields without route redesign. | Current parser fallback already prefers `name`/`label` when present. | Flutter parser/domain tests will expose required local adjustments. | `High` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces

- Laravel taxonomy services/repositories/jobs/backfill.
- Account Profile, Static Asset, Event, Event Occurrence, and Map POI projection/formatting paths.
- Flutter taxonomy/domain parsers and public/admin taxonomy chip rendering tests.
- Canonical module docs for promoted stable decisions.

### Ordered Steps

1. Add fail-first tests for each affected payload/read model showing raw slug rendering today.
2. Implement canonical snapshot normalization and write/read payload enrichment.
3. Add queued fanout/reprojection for taxonomy and taxonomy-term display-name updates.
4. Add idempotent legacy backfill/repair command/job.
5. Update Flutter parsers/tests to preserve canonical fallback behavior.
6. Run focused backend and Flutter validation, then promote stable decisions into module docs.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** The bug is contract-visible and the main risk is partial snapshot propagation.
- **Fail-first targets:** Laravel feature tests for each payload family and Flutter parser/widget tests for display-name rendering.

### Runtime / Rollout Notes

- Backfill must be safe to rerun.
- Fanout jobs should be idempotent and tenant-scoped.
- Rollout should tolerate mixed old/new documents by preserving fallback and backfill repair.

## Audit Trigger Matrix

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `big` | Cross-stack read-model/fanout/backfill change. |
| `blast_radius` | `cross-stack` | Laravel persistence/projections and Flutter renderers. |
| `behavioral_change_or_bugfix` | `yes` | Fixes slug display and read payload contract. |
| `changes_public_contract` | `yes` | Adds display fields to public/admin taxonomy payloads. |
| `touches_auth_or_tenant` | `yes` | Tenant-scoped documents and admin taxonomy edits. |
| `touches_runtime_or_infra` | `yes` | Queue/fanout/backfill. |
| `touches_tests` | `yes` | Backend and Flutter tests required. |
| `critical_user_journey` | `yes` | Public list/detail taxonomy display. |
| `release_or_promotion_critical` | `yes` | Store Release lane. |
| `high_severity_plan_review_issue` | `no` | No plan review issue recorded yet. |
