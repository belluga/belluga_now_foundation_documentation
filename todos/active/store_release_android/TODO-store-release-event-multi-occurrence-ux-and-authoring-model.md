# TODO (Store Release): Event Multi-Occurrence UX and Authoring Model

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Active
**Owners:** Product, Flutter Team, Laravel Team
**Objective:** Evolve the intentional V1 single-occurrence UI baseline into a clear multi-occurrence event model for public occurrence-first discovery, event detail navigation, tenant-admin event authoring, occurrence-scoped related profiles, and occurrence-exclusive programação.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** `foundation_documentation/artifacts/tmp/improvement-intake-session-2026-04-20.md` (`C-01`)

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo-with-open-decisions`
- **Primary story ID:** `C-01`
- **Why this is the right current slice:** Backend/domain already supports event occurrences, and the UI intentionally shipped a first version where creating an event creates the first occurrence. The next implementation needs one coherent UX contract before code work starts.
- **Direct-to-TODO rationale:** The public list/card direction, public detail tab model, related-profile merge semantics, programação date-selection model, programação item Account Profile location model, and occurrence-exclusive programação model are settled. The prior `Datas` tab and occurrence-level location override design is superseded by the approved programação-centered model below.

## Delivery Status Canon

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** Keep in Store Release active lane for manual validation/promotion after final orchestration guards. Re-run guards if event occurrence/programming behavior, runtime target, or served Web bundle changes.

## Package-First Assessment

- **Status:** completed before implementation.
- **Queries run:** `event`, `map`, and `account profile` from the ecosystem root.
- **Relevant packages found:** only unrelated analytics package `event_tracker_handler`; no proprietary map/account-profile package owns this feature boundary.
- **Decision:** local implementation inside existing Events package and Flutter event/admin surfaces.
- **Rationale:** the slice extends the existing `belluga_events` aggregate/read model and the existing tenant-public/tenant-admin event screens; no reusable proprietary package owns multi-occurrence authoring/detail semantics.

## Constraint Notes

- **Active constraint:** `None`
- **Constraint rationale:** `None`
- **Clearance path:** `None`
- **Owner / source:** Store Release orchestrator final runtime validation.
- **Last confirmed truth:** Home/public discovery remains occurrence-first and list cards remain occurrence-only. The event detail route remains `/agenda/evento/:slug` with optional selected occurrence query metadata. Multi-date switching no longer uses a separate `Datas` tab; it happens inside the public `Programação` tab through a date selector below the section title. Occurrences own date/time and programação grouping, but not their own location. A programação item may optionally reference an Account Profile that owns the Map POI used as that item's location; the displayed text is a snapshot/rendering of that Account Profile/POI, never free text. `Como Chegar` lists all event-related addresses: the default event location plus deduplicated programação item locations. Manual 2026-04-22 admin validation found the tenant-admin Events list still behaves as event-first, ordering/filtering by the event or first occurrence time; if the first occurrence is past, the event card can disappear even when later occurrences remain. The approved admin list behavior is occurrence-first, matching public Home semantics: each occurrence that belongs in the admin list renders as its own card, and clicking an occurrence card opens the Event edit screen with that occurrence treated as an alias/context of the Event.

## Scope

- [x] Preserve occurrence-first public listing/discovery semantics.
- [x] Preserve occurrence-only public cards; cards do not become event-grouped and do not need multi-date summaries.
- [x] Add detail-surface occurrence navigation inside `Programação`, not in a separate `Datas` tab.
- [x] Define how a selected occurrence is represented in route/navigation/hydration when multiple occurrences share one event slug.
- [x] Implement the approved tenant-admin create/edit UX for adding, editing, and validating multiple occurrences.
- [x] Treat multiple occurrences as an always-available Events contract, not as a tenant capability or per-event capability.
- [x] Extend the occurrence contract to support occurrence-scoped related profiles while preserving event-level related profiles as shared/global profiles.
- [x] Remove/supersede occurrence-scoped location overrides from the approved model; occurrences own date/time and programação grouping only.
- [x] Extend the occurrence contract to support occurrence-exclusive programação items.
- [x] Extend programação items with optional location Account Profile references; the referenced Account Profile owns the Map POI and is the origin for displayed location text.
- [x] Render effective occurrence related profiles as event-level profiles plus that occurrence's own related profiles, deduplicated deterministically.
- [x] Render effective event related profiles as event-level profiles plus all occurrence-owned related profiles, deduplicated deterministically.
- [x] Render a `Programação` tab when the event has programação in at least one occurrence; selected occurrences without programação show an empty state inside the tab.
- [x] Remove the public `Datas` tab from event detail.
- [x] Render a date selector inside `Programação` for multi-occurrence events, highlight the selected occurrence/date, and update route query on selection.
- [x] Render enriched programação cards with time, title/fallback, linked Account Profile avatars/names, and optional location Account Profile/POI affordance.
- [x] Make tapping a programação item location navigate to the associated Map POI.
- [x] Make `Como Chegar` list the default event location plus all programação item locations, deduplicated by canonical POI/account-profile identity.
- [x] Ensure programação-linked participant Account Profiles are automatically linked to the event-level related profile set and the admin UI reflects this reactively.
- [x] Ensure public detail, Home agenda, event search/list, and admin forms all keep event identity and occurrence identity distinct.
- [x] Make the tenant-admin Events list occurrence-first: list/sort/filter by eligible occurrences rather than the event's first occurrence, render one card per occurrence when appropriate, keep event identity for editing, and open the Event edit screen with the clicked occurrence as the selected occurrence context/alias.
- [x] Add positive and negative tests for every optional programação/location/profile/dedup/navigation behavior listed in this TODO.

## Out of Scope

- [x] Replacing public Home/Event cards with event-grouped cards remains out of scope.
- [x] Adding a separate event-only detail/hub screen for Store Release remains out of scope.
- [x] Changing event aggregate ownership or moving occurrence persistence out of Events remains out of scope.
- [x] Ticketing/check-in occurrence policy redesign beyond preserving existing occurrence identity remains out of scope.
- [x] Broad per-occurrence overrides for event title/content/media/type/publication/taxonomies remain out of scope.
- [x] Occurrence-level location overrides remain out of scope. Programação item locations are Account Profile/Map POI references instead.
- [x] Free-text programação locations remain out of scope. Display text may be snapshotted, but the source of truth is the referenced Account Profile/Map POI.
- [x] Event-level programação remains out of scope. Programação belongs to occurrences only.
- [x] General Map POI projection redesign beyond the small adapter/navigation work required for programação item location links remains out of scope.
- [x] Text search changes remain out of scope.

## Execution Lane Tracking

- **Local implementation branches:** `orchestrator/store-release-usability-wave` in `belluga_now_docker`, `laravel-app`, and `flutter-app`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Event multi-occurrence UX/model | `orchestrator/store-release-usability-wave` | `Not promoted yet` | `Not promoted yet` | `Not promoted yet` | `Local-Implemented after approved 2026-04-22 contract supersession` |

## Prior Local Implementation Evidence

The entries below remain useful historical/supporting evidence for the earlier implementation pass, but any evidence tied to `Datas`, occurrence-level location overrides, or selected-occurrence `Programação` visibility is superseded by the approved 2026-04-22 contract. Completion must be re-established against the updated Definition of Done, Validation Steps, and `NAV-*` matrix below.

- Laravel Events write path accepts occurrence-owned related profiles, optional occurrence location overrides, and occurrence-owned `programming_items` while preserving omitted occurrence-owned fields on update.
- Laravel occurrence projections store own/effective related profiles, effective location, location override state, and programming item summaries for cheap document reads.
- Public event detail accepts optional selected-occurrence query metadata, repairs stale/missing occurrence references to deterministic live/next/first fallback, and exposes `occurrences[]` with `is_selected`.
- Flutter public list/card navigation preserves occurrence identity when available without grouping list cards by event.
- Flutter immersive event detail renders `Datas` after `Sobre` for multi-date events, highlights the current occurrence card, switches dates through `/agenda/evento/:slug?occurrence=<id>`, and shows `Programação` only for the selected occurrence.
- Flutter tenant-admin event form keeps shared event fields first, supports single-to-multi occurrence transition, renders occurrence cards, and lets occurrence editors author occurrence-owned profiles and Programação with item-level location references.
- Canonical module docs promoted the contract in Events, Flutter Client Experience, Tenant Admin, and Agenda modules.

## Local Validation Evidence

- 2026-04-22 SR-D current guard precheck: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` returned `Overall outcome: go` before the delivery claim because no delivery milestone was active at that time; final runtime acceptance was still open at that point.
- 2026-04-22 SR-D tenant-admin FAB/save regression rerun: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name "adds a second occurrence date"` passed (`2 tests`) and asserts `tenantAdminEventAddOccurrenceButton` is a real `FloatingActionButton` before proving create and edit draft payloads retain two occurrences.
- 2026-04-22 SR-D tenant-admin full form rerun: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed (`17 tests`), covering add-card/FAB flow, occurrence editor save-return-refresh, occurrence-owned profiles/programming, create/update payload mutation, and baseline single-occurrence behavior.
- 2026-04-22 SR-D Home occurrence navigation source test tightened: `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart --plain-name "taps My Events card and pushes detail route"` passed (`1 test`) and captures `ImmersiveEventDetailRoute(eventSlug: event-1, occurrenceId: occ-home-2)` plus `rawQueryParams {'occurrence': 'occ-home-2'}`.
- 2026-04-22 SR-D Home full rerun: `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart` passed (`9 tests`).
- 2026-04-22 SR-D focused Flutter rerun: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/presentation/tenant_public/schedule/routes/immersive_event_detail_route_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart` passed (`89 tests`), covering admin authoring, tenant-admin DTO/repository contracts, selected-occurrence route hydration, `Datas` highlight/order, conditional `Programação`, and Home occurrence-card navigation source.
- 2026-04-22 SR-D no-capability Laravel rerun: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter 'test_event_create_allows_multiple_occurrences_without_tenant_or_event_capability|test_event_create_allows_multiple_occurrences_by_default|test_event_create_persists_occurrence_owned_profiles_location_override_and_programming_items|test_public_event_detail_selects_occurrence_and_returns_all_dates_with_selected_highlight|test_event_create_requires_programming_title_when_more_than_one_profile_is_linked|test_event_create_allows_three_occurrences_without_tenant_max_guard|test_event_update_without_schedule_mutation_keeps_stored_occurrences|test_event_update_with_schedule_mutation_allows_multiple_occurrences_without_capability'` passed (`8 tests`, `61 assertions`). A parallel Laravel attempt was invalidated by shared Mongo drop/migrate concurrency and was rerun sequentially.
- 2026-04-22 SR-D settings schema rerun: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Settings/SettingsKernelControllerTest.php --filter test_schema_exposes_navigation_nodes_and_conditional_metadata` passed (`1 test`, `12 assertions`) and asserts no tenant settings fields exist for `capabilities.multiple_occurrences.allow_multiple` or `max_occurrences`.
- 2026-04-22 SR-D analyzer rerun: `fvm dart analyze --format machine` exited `0`; remaining machine output was unrelated `INFO|HINT|UNNECESSARY_IMPORT` diagnostics in `integration_test/feature_account_profile_rich_text_fidelity_test.dart` (SR-C rich-text scope), with no SR-D occurrence/domain analyzer errors.
- 2026-04-22 SR-D source-owned final-runtime spec added and executed: `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` defines `@mutation tenant-admin event occurrence FAB persists second occurrence and public detail selects it`. The spec logs in to tenant-admin against `NAV_TENANT_URL`, seeds an event type and single-occurrence published event via API without tenant/event multi-occurrence capability setup, opens the real admin Events list, opens the real edit form, chooses the rightmost visible `Adicionar data` affordance (the FAB path), saves a second occurrence, submits the real `PATCH /admin/api/v1/events/:id`, verifies two occurrences in PATCH response and admin API readback, reopens admin UI after navigation for `Datas`/two occurrence cards, opens public `/agenda`/Home card navigation and browser back, opens public `/agenda/evento/:slug?occurrence=<second_id>`, verifies `Sobre`, `Datas`, `Datas do evento`, selected `Atual`, and then validates occurrence-owned effective profiles/location plus conditional `Programação` tab/card/profile row on a programmed occurrence.
- 2026-04-22 SR-D Playwright source checks: `node --check tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` passed; `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=local node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` passed; `cd tools/flutter/web_app_smoke_runner && NODE_PATH="$PWD/node_modules" NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=local npx playwright test --config ./playwright.config.js --grep "tenant-admin event occurrence FAB" --list` discovered `navigation.mutation.event_occurrences.spec.js:630:1` as one test. Direct `npx playwright test --list` without the runner `NODE_PATH` was invalid harness evidence because cross-directory specs could not resolve `@playwright/test`.
- 2026-04-22 SR-D canonical mutation replay classification: the seeded event was valid and appeared in `/admin/api/v1/events`; the `/admin/events` card was visible in the browser screenshot, but Flutter CanvasKit text was not exposed to Playwright text locators, so the original failure was harness/list discovery rather than product list filtering or seed status. The spec now discovers the seeded admin API page, scrolls the real admin list, uses a visible-card click fallback when text semantics are unavailable, verifies the real add-occurrence FAB path, submits `PATCH /admin/api/v1/events/:id`, verifies admin API readback with two occurrences, reopens the UI, and passes on the final served Web bundle.
- 2026-04-22 SR-D online occurrence location regression fix: the final programmed-occurrence runtime journey exposed a real Flutter crash for `location.mode=online` occurrences without venue/place. `EventDTO` now maps online location label/name/title/url (or `Online` fallback) into a non-empty domain `DescriptionValue`; `fvm flutter test test/infrastructure/dal/dto/schedule/event_dto_test.dart` passed `11 tests`, including the new online occurrence parser case.
- 2026-04-22 SR-D final Web rebuild after no-capability contract change: `bash scripts/build_web.sh ../web-app dev` passed and published the current Flutter bundle to `../web-app`.
- 2026-04-22 SR-D final targeted Playwright mutation after no-capability contract change: from `tools/flutter/web_app_smoke_runner`, `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=dev NODE_PATH=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/tools/flutter/web_app_smoke_runner/node_modules npx playwright test --config ./playwright.config.js --grep 'event occurrence FAB' --retries=0 --fail-on-flaky-tests --workers=1 --reporter=line --output /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/test-results-targeted-srd-no-capability` passed `1 passed (1.3m)`.
- 2026-04-22 SR-D final analyzer after no-capability contract change: `fvm dart analyze --format machine` exited `0` with no diagnostics.
- 2026-04-22 SR-D first-occurrence end-date clear regression: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart --plain-name "clearEventEndAt clears the optional first occurrence end date"` passed, and `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name "clears optional end date from the first occurrence form"` passed.
- 2026-04-22 SR-D focused Flutter rerun after clear-date/no-capability regression: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart` passed (`69 tests`).
- 2026-04-22 SR-D final completion guard: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md --require-delivery` returned `Overall outcome: go`.
- 2026-04-22 SR-D public-detail semantics patch: `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart --plain-name "event detail dates tab highlights current occurrence"` passed (`1 test`), and `fvm dart analyze --format machine` exited `0` with no diagnostics. The patch exposes the public detail title, `Sobre`/`Datas` tabs, `Datas do evento`, date-card selection, and `Atual` badge to browser automation after the next web rebuild.
- 2026-04-22 SR-D evidence revalidation: the updated PACED guard invalidated the previous `Local-Implemented` claim because visible public/admin rows were backed by widget/unit/code evidence instead of item-specific integration/navigation evidence.
- 2026-04-22 SR-D admin occurrence regression fix: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name "adds a second occurrence date"` passed (`2 tests`), proving create and edit paths add a second occurrence through a real `FloatingActionButton` and local create/update draft mutation.
- 2026-04-22 SR-D integration harness attempt: `FLUTTER_INTEGRATION_RUN_TIMEOUT_SECONDS=1200 bash tool/run_integration_test_wsl.sh integration_test/feature_admin_event_occurrence_authoring_test.dart` failed preflight before execution because ADB device `192.168.15.5:5555` was unreachable. `fvm flutter test ... -d linux` also failed preflight due missing system package `libsecret-1>=0.18.4`. These are invalid harness evidence, not passing delivery evidence.
- 2026-04-23 SR-D admin programação-location UI fix: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed (`19 tests`), and now explicitly proves `Local: Venue A` renders in the occurrence editor after saving a programação item location, that the same tile reopens in `Editar item de programação`, and that clearing the location removes the summary and persists `placeRef=null`.
- 2026-04-23 SR-D admin DTO cleanup rerun: `fvm flutter test test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart` passed (`12 tests`), proving tenant-admin occurrence encoding/decoding no longer carries occurrence-level location override state while preserving programação item `place_ref`.
- 2026-04-23 SR-D admin device integration rerun on ADB `192.168.15.9:5555`: `ADB_DEVICE=192.168.15.9:5555 FLUTTER_INTEGRATION_RUN_TIMEOUT_SECONDS=1200 FLUTTER_INTEGRATION_FLAVOR=belluga ADB_APP_ID=com.boora.app bash tool/run_integration_test_wsl.sh integration_test/feature_admin_event_occurrence_authoring_test.dart` executed both test bodies but ended in the known harness defect `streamListen: invalid 'streamId' parameter: integration_test.VmServiceProxyGoldenFileComparator`; the canonical fallback `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_admin_event_occurrence_authoring_test.dart -d 192.168.15.9:5555 --flavor belluga --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` then passed with `All tests passed.` on the same device.
- 2026-04-23 SR-D dedicated admin location-ownership Web checkpoint: `bash scripts/build_web.sh ../web-app dev` passed; local `../web-app/main.dart.js` and served `https://guarappari.belluga.space/main.dart.js?cachebust=runtime-validation-20260423-admin-location` both resolved to `7dc104327167ea4f22687da36c827f12fe990543d5ccf82573d59693d1ba12ac`; targeted Playwright command `NODE_PATH="$PWD/node_modules" NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_TEST_TYPE=mutation npx playwright test --config ./playwright.config.js ../web_app_tests/navigation.mutation.event_occurrences.spec.js --grep 'NAV-ADM-LOC-01..06 admin occurrence programming location ownership matrix holds' --retries=0 --fail-on-flaky-tests --workers=1 --reporter=line --output /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/test-results-targeted-srd-admin-location` passed `1 passed (41.0s)`.
- 2026-04-23 SR-D checkpoint acceptance: the user manually validated the admin occurrence/programação location migration and approved this recut as a checkpoint after the dedicated `NAV-ADM-LOC-01..06` final-domain proof.
- 2026-04-22 SR-D analyzer: `fvm dart analyze --format machine` exited `0` with no diagnostics after cleanup/rebuild.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter 'test_event_create_persists_occurrence_owned_profiles_location_override_and_programming_items|test_public_event_detail_selects_occurrence_and_returns_all_dates_with_selected_highlight|test_event_create_requires_programming_title_when_more_than_one_profile_is_linked'` passed: `3 tests, 50 assertions`.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Unit/Events/EventQueryServiceTest.php` passed: `28 tests, 110 assertions`.
- `fvm flutter test test/infrastructure/dal/dto/schedule/event_dto_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/application/router/tenant_admin_route_path_params_test.dart test/presentation/tenant_public/schedule/routes/immersive_event_detail_route_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/schedule/widgets/date_grouped_event_list_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed: `71 tests`.
- `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart --plain-name "event detail dates tab highlights current occurrence"` passed in reconciliation.
- `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` passed in reconciliation: `71 tests`.
- `fvm dart analyze --format machine` passed after removing screen-owned UI controllers from the occurrence/programming sheets.
- 2026-04-22 SR-D guard baseline: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` returned `Overall outcome: no-go` only because `Completion Evidence Matrix` rows were missing.
- 2026-04-22 SR-D implementation check: tenant-admin add-occurrence control now uses a real `FloatingActionButton.extended` with key `tenantAdminEventAddOccurrenceButton`; `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name "adds a second occurrence date before create submit"` passed and asserts `tester.widget<FloatingActionButton>(find.byKey(...))`.
- 2026-04-22 SR-D focused Flutter suite: `fvm flutter test test/infrastructure/dal/dto/schedule/event_dto_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/application/router/tenant_admin_route_path_params_test.dart test/presentation/tenant_public/schedule/routes/immersive_event_detail_route_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/schedule/widgets/date_grouped_event_list_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed: `72 tests`.
- 2026-04-22 SR-D Laravel CRUD/detail focused rerun: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter 'test_event_create_persists_occurrence_owned_profiles_location_override_and_programming_items|test_public_event_detail_selects_occurrence_and_returns_all_dates_with_selected_highlight|test_event_create_requires_programming_title_when_more_than_one_profile_is_linked'` passed: `3 tests, 50 assertions`.
- 2026-04-22 SR-D Laravel agenda/query focused reruns: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Unit/Events/EventQueryServiceTest.php` was attempted sequentially after the invalid parallel run, but the local shared Mongo harness repeatedly returned `database is in the process of being dropped` from `landlord_test`/`tenant_tenant_zeta`; this is classified as invalid environment evidence. Existing reconciliation evidence above remains the last clean focused pass for this suite.
- 2026-04-22 SR-D analyzer: `fvm dart analyze --format machine` exited `0` with no diagnostics.
- 2026-04-22 SR-D completion guard: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` returned `Overall outcome: go` with `dod_count: 13`, `validation_count: 12`, and `evidence_row_count: 25`.

## Final Runtime Acceptance Reconciliation

This reconciliation belongs to the superseded implementation pass and cannot be used as current delivery evidence until the approved `Programação` date-selector, programação item location, and `Como Chegar` aggregation model is implemented and revalidated.

- 2026-04-22 PACED correction: code, analyzer, Laravel feature tests, Flutter unit/widget tests, and repository/DTO tests remain valid implementation/supporting evidence. They do not close final visible acceptance for the orchestrator.
- 2026-04-22 guard after final-acceptance correction: `python3 ../delphi-ai/tools/todo_completion_guard.py ../foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md --require-delivery` returned `Overall outcome: no-go`.
- 2026-04-22 SR-D final runtime acceptance passed through source-owned Playwright journeys in `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` after current Web build/publish.
- 2026-04-22 SR-D final runtime fix loop resolved a real product defect found by navigation: online occurrence detail payloads without venue/place no longer crash Flutter DTO/domain mapping.
- Platform parity classification: SR-D public/admin behavior uses the same Flutter code paths and backend contracts across Android/Web for this slice. Final Playwright mutation evidence is sufficient for visible acceptance; ADB remains supporting/alternate evidence rather than required duplicate evidence unless a future Android-specific divergence is introduced.

| Criterion ID | Current supporting evidence | Final acceptance gap | Required next evidence |
| --- | --- | --- | --- |
| DOD-01 | Flutter repository/widget evidence exists plus final Playwright journey. | Closed. | Final SR-D mutation spec opened `/agenda`, selected a real occurrence card, asserted detail URL with `occurrence=<id>`, returned with browser back, and asserted `Datas do evento` is not exposed on the list/card surface. |
| DOD-02 | Laravel detail and Flutter route/widget evidence exists plus final Playwright journey. | Closed. | Final SR-D mutation spec opened `/agenda/evento/:slug?occurrence=<second_id>` and asserted selected occurrence detail state. |
| DOD-03 | Flutter widget evidence exists plus final Playwright journey. | Closed. | Final SR-D mutation spec asserted `Sobre`, `Datas`, and `Datas do evento` on the public detail after current Web build. |
| DOD-04 | Flutter widget evidence exists plus final Playwright journey. | Closed. | Final SR-D mutation spec asserted the current occurrence `Atual` highlight in the `Datas` tab. |
| DOD-05 | Flutter widget/Laravel write-path evidence exists plus final Playwright mutation. | Closed. | Final SR-D no-capability mutation spec used the real tenant-admin add-occurrence FAB path, saved the second occurrence, submitted `PATCH /admin/api/v1/events/:id`, verified admin API readback, and reopened the UI with two occurrence cards without enabling tenant/event multi-occurrence flags. |
| DOD-06 | Route/widget/backend evidence exists plus final Playwright journey. | Closed. | Final SR-D mutation spec opened the event slug route with `occurrence=<second_id>` and verified selected-occurrence hydration. |
| DOD-07 | Laravel/DTO/widget evidence exists plus final Playwright programmed-occurrence journey. | Closed. | Final SR-D mutation spec seeded event-level and occurrence-level related profiles, asserted public API effective merge, and verified the programmed occurrence linked profile row in the browser. |
| DOD-09 | Laravel/DTO/widget evidence exists plus final Playwright programmed-occurrence journey. | Closed. | Final SR-D mutation spec seeded an occurrence online location override, asserted public API effective location, and verified the browser no longer crashes on selected occurrence detail after the DTO fix. |
| DOD-10 | Laravel/DTO/widget evidence exists plus final Playwright programmed-occurrence journey. | Closed. | Final SR-D mutation spec asserted selected occurrence `programming_items` through public API and browser rendering of the `17:00` programação card. |
| DOD-11 | Flutter widget evidence exists plus final Playwright programmed-occurrence journey. | Closed. | Final SR-D mutation spec asserted the conditional `Programação` tab on the selected occurrence with programação. |
| DOD-12 | Flutter widget/Laravel evidence exists plus final Playwright programmed-occurrence journey. | Closed. | Final SR-D mutation spec asserted programming card time, title, and linked Account Profile row in browser runtime. |
| DOD-13 | Local test-suite evidence exists plus final Playwright mutation suite evidence. | Closed. | Final targeted SR-D no-capability Playwright passed `1 passed (1.3m)` after the current Web build; earlier aggregate mutation suite passed `12 passed (7.4m)` before the no-capability simplification. |
| VAL-11 | Route/widget evidence exists plus final Playwright Home/card/back journey. | Closed. | Final SR-D mutation spec used public `/agenda` card to detail and browser back flow against `https://guarappari.belluga.space`. |
| VAL-12 | Flutter widget evidence exists plus final Playwright tenant-admin mutation. | Closed. | Final SR-D mutation spec covered single-occurrence fields, FAB/add-card path, occurrence editor save, event update mutation, admin readback, and UI reopen/refresh. |

## Definition of Done

- [x] Public Home/Event list remains occurrence-first and occurrence-card-only.
- [x] Event detail route can represent selected occurrence context without changing event identity.
- [x] The public `Datas` tab is removed from event detail.
- [x] Public event detail uses `Programação` as the multi-occurrence navigation surface when the event has programação in at least one occurrence.
- [x] The `Programação` section renders a date selector for multi-occurrence events, highlights the selected occurrence/date, and updates the selected occurrence route query when another date is chosen.
- [x] A selected occurrence without programação renders an empty state inside `Programação` when another occurrence in the event has programação.
- [x] If no occurrence has programação, `Programação` is absent and direct `tab=programming` entry falls back to `Sobre`.
- [x] Tenant-admin can create/edit multiple occurrences while preserving the first-occurrence baseline.
- [x] Occurrences no longer expose or persist location override as an approved field.
- [x] Programação items can optionally reference a location Account Profile that owns a Map POI; free-text location input is not accepted as the source of truth.
- [x] Programação participant Account Profiles automatically link into the event-level related profile set and the admin UI reflects this reactively.
- [x] Programação cards render time, title/profile fallback, linked Account Profile avatars/names, and optional location affordance without empty placeholder space when optional data is absent.
- [x] Tapping a programação item location opens the corresponding Map POI.
- [x] `Como Chegar` lists the default event location plus all programação item Account Profile/POI locations.
- [x] `Como Chegar` deduplicates repeated locations by canonical Account Profile/POI identity.
- [x] Tenant-admin Events list is occurrence-first: future/later occurrences remain visible even when the first occurrence has ended, each visible occurrence can open the Event edit screen, and editing preserves the event aggregate plus selected occurrence context.
- [x] Tests include positive and negative coverage for every optional field/section plus explicit deduplication coverage.

## Validation Steps

- [x] Laravel feature/package tests for multi-occurrence create/update/list/detail payloads and selected-occurrence lookup semantics.
- [x] Laravel positive tests for programação item location Account Profile/Map POI references in create/update/detail projections.
- [x] Laravel negative tests rejecting or ignoring occurrence-level location overrides and rejecting free-text programação locations as source of truth.
- [x] Laravel tests for programação item ordering, participant Account Profile resolution, auto-link into event-level related profiles, and absence from event-level programação fields.
- [x] Laravel tests for default event location plus programação item location aggregation and deduplication.
- [x] Flutter route/controller tests for selected occurrence hydration and direct `tab=programming` fallback behavior.
- [x] Flutter widget/controller tests proving `Datas` tab removal and `Programação` date selector behavior.
- [x] Flutter widget tests for selected occurrence with programação, selected occurrence without programação, and event with no programação.
- [x] Flutter DTO/domain/widget tests for programação card participant avatars/names, title/profile fallback, optional location display, and absence of optional placeholders.
- [x] Flutter widget/controller tests for `Como Chegar` aggregated address list and deduplication.
- [x] Tenant-admin Events list repository/controller/widget tests for occurrence-first list semantics, including an event whose first occurrence is past and later occurrence is future.
- [x] Tenant-admin form/navigation tests for single-occurrence fields, transition to occurrence-card list, FAB/add-card flow, occurrence detail edit, programação item participant/location authoring, auto-linked event profiles, save-return-refresh, and chronological validation.
- [x] Playwright navigation tests for every `NAV-*` row below after `bash scripts/build_web.sh ../web-app dev`; widget/unit/analyzer evidence is supporting only and cannot close visible navigation rows.

## Required Runtime Navigation Matrix

| ID | Decisions | Flow | Positive validation | Negative / absence validation |
| --- | --- | --- | --- | --- |
| `NAV-01` | `D-D-01`, `D-D-02`, `D-D-05`, `D-D-36` | Open public event detail from an occurrence-first list/card. | URL keeps `/agenda/evento/:slug?occurrence=<id>` and the screen hydrates the selected occurrence context. | Stale/missing `occurrence` repairs to canonical fallback without showing another occurrence's data as selected. |
| `NAV-02` | `D-D-30`, `D-D-32`, `D-D-39` | Switch date inside `Programação` using the date selector. | Tapping another date updates selected occurrence, URL/query, selected-date highlight, and programação content. | A selected date without programação shows the empty state for that date without falling back to `Sobre` or selecting another date. |
| `NAV-03` | `D-D-36`, `D-D-38` | Open an event with no programação in any occurrence. | Event detail remains usable with `Sobre`/other tabs. | `Programação` tab and date selector are absent; direct `tab=programming` falls back to `Sobre`. |
| `NAV-04` | `D-D-28`, `D-D-29`, `D-D-37` | Verify the old `Datas` detail tab is gone. | Multi-occurrence navigation is available inside `Programação` when applicable. | Header/tabs never render `Datas` in event detail. |
| `NAV-05` | `D-D-18`, `D-D-19`, `D-D-31`, `D-D-40` | Render programação card with participant Account Profiles. | Card shows time, title or single-profile fallback, resolved avatar/name row, and profile navigation when supported. | With no participants, the card renders no empty participant row, no avatar placeholder, and no dead space. |
| `NAV-06` | `D-D-35`, `D-D-40` | Render programação card with location Account Profile/Map POI. | Card shows location text derived from Account Profile/POI and tapping it navigates to `/mapa?poi=account_profile:<id>` or the approved equivalent. | Without valid location Account Profile/POI, no map CTA is rendered and no invalid navigation happens. |
| `NAV-07` | `D-D-35`, `D-D-40` | Render programação item without own location. | Card still renders time/title/profile content correctly. | No location block, pin icon, map CTA, or reserved whitespace appears. |
| `NAV-08` | `D-D-15`, `D-D-41` | Open `Como Chegar` with only the default event location. | The default event location appears and existing map/route CTA behavior is preserved. | No duplicate default location and no empty programação-location section appears. |
| `NAV-09` | `D-D-35`, `D-D-41` | Open `Como Chegar` with default location plus programação item locations. | The list includes the default event location and all programação item Account Profile/POI locations, each with correct map/route action. | Programação items without location do not appear as address entries. |
| `NAV-10` | `D-D-12`, `D-D-41` | Deduplicate `Como Chegar` locations. | Two programação items using the same Account Profile/POI render one address entry, optionally with multiple associated activities/dates. | Duplicate visual rows or repeated CTAs for the same POI do not appear. |
| `NAV-11` | `D-D-36`, `D-D-38`, `D-D-39` | Direct-open a selected occurrence with programação. | `/agenda/evento/:slug?occurrence=<id>&tab=programming` opens `Programação`, selects the requested date, and renders that occurrence's programação. | If `tab=programming` is requested but no programação exists anywhere, fallback lands on `Sobre`. |
| `NAV-12` | `D-D-38`, `D-D-39` | Direct-open a selected occurrence without programação in an event that has programação elsewhere. | `Programação` opens with the requested date selected and shows the empty state for that date. | The UI does not auto-switch to another occurrence just because it has programação. |
| `NAV-13` | `D-D-43` | Tenant-admin Events list renders by occurrence. | Seed/create an event with first occurrence already ended and a later future occurrence; the admin Events list still shows the future occurrence card, with occurrence date/time visible, and clicking it opens the Event edit screen with that occurrence selected as the edit context. | The list must not hide the event because its first occurrence ended, must not collapse all occurrences into a stale first-occurrence card, and must not navigate to an occurrence-only editor that loses the Event aggregate identity. |

Runtime navigation rows require Playwright against the final served Web bundle after `bash scripts/build_web.sh ../web-app dev`. `NAV-13` must exercise the real tenant-admin list UI, not only repository/controller tests or direct API reads.

## Completion Evidence Matrix

Rows below are the current SR-D2 delivery evidence for the approved 2026-04-22 `Programação`-centered contract. Historical rows tied to the old public `Datas` tab or occurrence-level location override model are superseded and not used for closure.

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOD-01 | Definition of Done | Public Home/Event list remains occurrence-first and occurrence-card-only. | Playwright navigation + Flutter/Laravel supporting tests | `bash scripts/build_web.sh ../web-app dev`; served/local hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`, spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js`, test `@mutation tenant-admin event occurrence FAB persists second occurrence and public detail selects it`; supporting Laravel safe-runner passed `158 passed (938 assertions)`. | Final Web browser runtime `https://guarappari.belluga.space/agenda` | passed | Runtime opens a real occurrence card, verifies route query `occurrence=<id>`, uses browser back to the list, and asserts no public list/card `Datas do evento` surface. |
| DOD-02 | Definition of Done | Event detail route can represent selected occurrence context without changing event identity. | Playwright route navigation + backend selected-occurrence test | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)` after `bash scripts/build_web.sh ../web-app dev`; source spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` opens route `/agenda/evento/:eventSlug?occurrence=seeded-second-occurrence-id` and also verifies occurrence slug alias resolves to the event identity. | Final Web browser runtime + Laravel Events API | passed | Runtime route and API assertions keep `event_id`/slug stable while selecting the requested occurrence. |
| DOD-03 | Definition of Done | The public `Datas` tab is removed from event detail. | Playwright navigation + Flutter widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)` after current Web build; source spec asserts no role/button `Datas` and no `Datas do evento`; supporting Flutter test `event detail programming tab replaces dates tab with selector` passed in focused suite. | Final Web browser runtime + Flutter host | passed | This proves the old public `Datas` tab/section is absent after Programação owns date selection. |
| DOD-04 | Definition of Done | Public event detail uses `Programação` as the multi-occurrence navigation surface when the event has programação in at least one occurrence. | Playwright navigation + Flutter widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec opens `?tab=programming`, verifies `Programação`, date selector, selected occurrence, and empty/no-programming fallback; supporting Flutter focused suite includes Programação detail tests. | Final Web browser runtime + Flutter host | passed | Runtime validates positive programmed event and negative no-programming event behavior. |
| DOD-05 | Definition of Done | The `Programação` section renders a date selector for multi-occurrence events, highlights the selected occurrence/date, and updates the selected occurrence route query when another date is chosen. | Playwright navigation + Flutter widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec clicks a Programação date chip, verifies URL query switches to the first occurrence, selected chip becomes current, and the selected empty state appears; supporting Flutter test `event detail programming selector highlights current occurrence` passed. | Final Web browser runtime + Flutter host | passed | Runtime validates selected highlight and route query update, not just widget data. |
| DOD-06 | Definition of Done | A selected occurrence without programação renders an empty state inside `Programação` when another occurrence in the event has programação. | Playwright navigation + Flutter widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec selects an occurrence without items and asserts `Esta data ainda não tem programação cadastrada.` while the URL keeps that occurrence. | Final Web browser runtime + Flutter host | passed | Negative path proves the UI does not auto-switch to a sibling occurrence with items. |
| DOD-07 | Definition of Done | If no occurrence has programação, `Programação` is absent and direct `tab=programming` entry falls back to `Sobre`. | Playwright navigation + Flutter route/widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec opens direct `?tab=programming` on a no-programming event, verifies `Sobre` content and zero `Programação` tab; supporting Flutter test `event detail tab=programming falls back to Sobre when empty` passed. | Final Web browser runtime + Flutter host | passed | Runtime closes both absence and direct-route fallback behavior. |
| DOD-08 | Definition of Done | Tenant-admin can create/edit multiple occurrences while preserving the first-occurrence baseline. | Playwright mutation + Flutter/Laravel tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec uses the real tenant-admin FAB/add-date path, saves a second occurrence, submits `PATCH /admin/api/v1/events/:id`, verifies admin API readback with two occurrences, and reopens UI; supporting Flutter form tests and Laravel create/update tests passed. | Final Web browser runtime tenant-admin + Laravel Events API | passed | Mutation evidence runs on non-main dev lane and proves no tenant/event capability flag is required. |
| DOD-09 | Definition of Done | Occurrences no longer expose or persist location override as an approved field. | Playwright navigation + Laravel negative test + Flutter admin absence test | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)` and source spec asserts selected occurrences have no location override; Laravel `test_event_create_rejects_occurrence_location_override` passed in focused safe-runner; Flutter admin test asserts `tenantAdminOccurrenceLocationOverrideSwitch` and online URL field are absent. | Final Web browser runtime + Laravel local Docker + Flutter host | passed | Free occurrence-level location override is rejected/absent; selected occurrence inherits event location while Programação item location uses Account Profile/POI. |
| DOD-10 | Definition of Done | Programação items can optionally reference a location Account Profile that owns a Map POI; free-text location input is not accepted as the source of truth. | Playwright navigation + Laravel/Flutter tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` verifies programming item `location_profile.id` comes from Account Profile/Map POI and taps it to `/mapa?poi=account_profile:programming-location-profile-id`; Laravel `test_event_create_persists_occurrence_owned_profiles_and_programming_location_profile` passed. | Final Web browser runtime + Laravel local Docker + Flutter host | passed | Runtime proves real Account Profile/POI source; backend tests reject unsupported occurrence location and validate structured `place_ref`. |
| DOD-11 | Definition of Done | Programação participant Account Profiles automatically link into the event-level related profile set and the admin UI reflects this reactively. | Flutter admin widget mutation + Laravel/Playwright readback | Flutter test `authors occurrence scoped profile and programming` passed and asserts selected event-level related profile chip appears reactively after programming participant selection; Laravel `test_event_create_persists_occurrence_owned_profiles_and_programming_location_profile` passed and asserts participant is in `linked_account_profiles`; Playwright mutation `16 passed (7.4m)` verifies public linked profile row. | Flutter host + Laravel local Docker + final Web browser runtime | passed | Covers both admin reactive UI and persisted/public read model. |
| DOD-12 | Definition of Done | Programação cards render time, title/profile fallback, linked Account Profile avatars/names, and optional location affordance without empty placeholder space when optional data is absent. | Playwright navigation + Flutter widget/DTO tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec verifies `17:00`, participant name, title/profile fallback, location affordance, and no `Local da programação` placeholder between locationless item anchors; supporting Flutter DTO/detail tests passed. | Final Web browser runtime + Flutter host | passed | Positive and negative optional rows are visible in browser runtime. |
| DOD-13 | Definition of Done | Tapping a programação item location opens the corresponding Map POI. | Playwright navigation + Flutter widget test | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` clicks the Programação location Account Profile text and verifies `/mapa` URL includes `poi=account_profile:programming-location-profile-id`; supporting Flutter test `event detail programming location opens map POI route` passed. | Final Web browser runtime + Flutter host | passed | Runtime uses real tap/navigation, not only route construction. |
| DOD-14 | Definition of Done | `Como Chegar` lists the default event location plus all programação item Account Profile/POI locations. | Playwright navigation + Flutter widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec opens `Como Chegar`, sees default host, `Local da programação`, and scrolls to programming Account Profile/POI location; supporting Flutter test `event detail Como Chegar aggregates and dedupes destinations` passed. | Final Web browser runtime + Flutter host | passed | Runtime validates default-only and default-plus-programming location states. |
| DOD-15 | Definition of Done | `Como Chegar` deduplicates repeated locations by canonical Account Profile/POI identity. | Playwright navigation + Flutter widget tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec seeds two programming items with the same `place_ref` and asserts one visible destination and one `Local da programação` subtitle; supporting Flutter dedup test passed. | Final Web browser runtime + Flutter host | passed | Deduplication is validated on visible browser runtime with repeated source data. |
| DOD-16 | Definition of Done | Tenant-admin Events list is occurrence-first: future/later occurrences remain visible even when the first occurrence has ended, each visible occurrence can open the Event edit screen, and editing preserves the event aggregate plus selected occurrence context. | Playwright tenant-admin navigation + Laravel list test | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec seeds an event whose first occurrence ended and later occurrence is future, locates it through admin list API paging, opens it in the real admin UI, and verifies edit route; supporting Laravel `test_event_index_future_filter_keeps_event_visible_when_later_occurrence_is_future` passed in safe-runner suite. | Final Web browser runtime tenant-admin + Laravel local Docker | passed | Runtime proves the admin list no longer hides the event because the first occurrence ended. |
| DOD-17 | Definition of Done | Tests include positive and negative coverage for every optional field/section plus explicit deduplication coverage. | Playwright `NAV-01..NAV-13` matrix + Laravel/Flutter focused tests | `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec includes `@mutation NAV-01..NAV-13 multi-occurrence navigation matrix is declared` and the mutation journey executes positive/negative presence, absence, empty-state, no-override, no-`Datas`, no-placeholder, and dedup paths. Supporting `fvm flutter test` focused suite passed `48 passed`; Laravel safe-runner passed `158 passed (938 assertions)`. | Final Web browser runtime + Flutter host + Laravel local Docker | passed | Itemized matrix prevents representative-only closure. |
| VAL-01 | Validation Steps | Laravel feature/package tests for multi-occurrence create/update/list/detail payloads and selected-occurrence lookup semantics. | Laravel feature tests + Playwright selected-occurrence runtime | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Unit/Events/EventQueryServiceTest.php` passed `158 passed (938 assertions)`; Playwright mutation passed `16 passed (7.4m)` after Web build. | Laravel local Docker + final Web browser runtime | passed | Covers create/update/list/detail and selected occurrence lookup including route/alias runtime. |
| VAL-02 | Validation Steps | Laravel positive tests for programação item location Account Profile/Map POI references in create/update/detail projections. | Laravel feature tests + Playwright runtime | Safe-runner command passed `158 passed (938 assertions)` including `test_event_create_persists_occurrence_owned_profiles_and_programming_location_profile`; Playwright mutation passed `16 passed (7.4m)` and tapped the Programação location to focused Map POI. | Laravel local Docker + final Web browser runtime | passed | Confirms structured Account Profile/POI location reference in backend and visible browser navigation. |
| VAL-03 | Validation Steps | Laravel negative tests rejecting or ignoring occurrence-level location overrides and rejecting free-text programação locations as source of truth. | Laravel negative tests + Playwright/admin absence evidence | Safe-runner command passed `158 passed (938 assertions)` including `test_event_create_rejects_occurrence_location_override`; Playwright mutation passed `16 passed (7.4m)` and Flutter admin test asserts occurrence location override controls are absent. | Laravel local Docker + final Web browser runtime + Flutter host | passed | Free occurrence-level location is rejected/absent; programming location is Account Profile/POI only. |
| VAL-04 | Validation Steps | Laravel tests for programação item ordering, participant Account Profile resolution, auto-link into event-level related profiles, and absence from event-level programação fields. | Laravel feature tests + Playwright runtime | Safe-runner command passed `158 passed (938 assertions)`; Playwright mutation passed `16 passed (7.4m)` and verifies ordered `17:00` item, linked profile row, event/occurrence linked profile merge, and selected occurrence `programming_items`. | Laravel local Docker + final Web browser runtime | passed | Backend and browser both prove occurrence-only programming plus event-level related profile merge. |
| VAL-05 | Validation Steps | Laravel tests for default event location plus programação item location aggregation and deduplication. | Laravel/API supporting tests + Playwright runtime | Safe-runner command passed `158 passed (938 assertions)`; Playwright mutation passed `16 passed (7.4m)` and validates default-only, default-plus-programming, and duplicate programming location states in `Como Chegar`. | Laravel local Docker + final Web browser runtime | passed | Visible dedup evidence closes aggregation behavior. |
| VAL-06 | Validation Steps | Flutter route/controller tests for selected occurrence hydration and direct `tab=programming` fallback behavior. | Flutter route/controller tests + Playwright navigation | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/event_info_section_rich_text_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/presentation/tenant_admin/events/tenant_admin_events_screen_test.dart` passed `48 passed`; Playwright route mutation passed `16 passed (7.4m)`. | Flutter host + final Web browser runtime | passed | Direct `tab=programming` route positive and fallback paths are covered by widget and runtime. |
| VAL-07 | Validation Steps | Flutter widget/controller tests proving `Datas` tab removal and `Programação` date selector behavior. | Flutter widget tests + Playwright navigation | Focused Flutter suite passed `48 passed`; Playwright mutation passed `16 passed (7.4m)` after `bash scripts/build_web.sh ../web-app dev`, asserting no public `Datas` tab and visible `Programação` selector. | Flutter host + final Web browser runtime | passed | Evidence names both removed `Datas` marker and replacement Programação marker. |
| VAL-08 | Validation Steps | Flutter widget tests for selected occurrence with programação, selected occurrence without programação, and event with no programação. | Flutter widget tests + Playwright navigation | Focused Flutter suite passed `48 passed`; Playwright mutation passed `16 passed (7.4m)`, covering programmed occurrence, selected empty occurrence, and no-programming event fallback/absence. | Flutter host + final Web browser runtime | passed | Positive and negative occurrence states are itemized. |
| VAL-09 | Validation Steps | Flutter DTO/domain/widget tests for programação card participant avatars/names, title/profile fallback, optional location display, and absence of optional placeholders. | Flutter DTO/widget tests + Playwright navigation | Focused Flutter suite passed `48 passed`; Playwright mutation passed `16 passed (7.4m)` with Programação card time/title/profile/location/no-placeholder assertions. | Flutter host + final Web browser runtime | passed | Browser assertions cover visible optional presence/absence; DTO tests cover payload parsing. |
| VAL-10 | Validation Steps | Flutter widget/controller tests for `Como Chegar` aggregated address list and deduplication. | Flutter widget tests + Playwright navigation | Focused Flutter suite passed `48 passed`; Playwright mutation passed `16 passed (7.4m)`, validating default event location, programming Account Profile/POI location, and repeated location dedup in `Como Chegar`. | Flutter host + final Web browser runtime | passed | Dedup is asserted on final browser runtime and supporting widget path. |
| VAL-11 | Validation Steps | Tenant-admin Events list repository/controller/widget tests for occurrence-first list semantics, including an event whose first occurrence is past and later occurrence is future. | Laravel/Flutter tests + Playwright tenant-admin navigation | Safe-runner command passed `158 passed (938 assertions)`; focused Flutter admin events screen suite passed in the `48 passed` run; Playwright mutation passed `16 passed (7.4m)` and executes `NAV-13`. | Laravel local Docker + Flutter host + final Web browser runtime tenant-admin | passed | Runtime proves the future later occurrence remains reachable from the real admin list and opens edit context. |
| VAL-12 | Validation Steps | Tenant-admin form/navigation tests for single-occurrence fields, transition to occurrence-card list, FAB/add-card flow, occurrence detail edit, programação item participant/location authoring, auto-linked event profiles, save-return-refresh, and chronological validation. | Playwright mutation + Flutter form tests + ADB integration fallback | Broad Playwright mutation previously passed `16 passed (7.4m)` after `bash scripts/build_web.sh ../web-app dev`, exercising real FAB/add-card, occurrence editor save, event `PATCH`, admin readback, and UI reopen. After the occurrence-location contract correction, the dedicated final-domain Playwright matrix `NAV-ADM-LOC-01..06 admin occurrence programming location ownership matrix holds` passed `1 passed (41.0s)` with positive and negative assertions for location presence, persistence, clearing, and absence of occurrence-level location UI. Flutter form suite passed `19 tests`, including visible `Local: Venue A` summary plus edit/clear of programação item location; device file `integration_test/feature_admin_event_occurrence_authoring_test.dart` passed via `flutter drive` on `192.168.15.9:5555` after the known `streamListen` harness defect invalidated the direct `flutter test` lane. | Final Web browser runtime tenant-admin + Flutter host + Android ADB device `192.168.15.9:5555` | passed | Mutation path performs local non-main save/update, the dedicated final-domain Playwright recut closes the visible admin location-authoring ownership matrix, Flutter tests cover form-state details, and the device lane closes the visible admin location-authoring journey on real runtime. |
| VAL-13 | Validation Steps | Playwright navigation tests for every `NAV-*` row below after `bash scripts/build_web.sh ../web-app dev`; widget/unit/analyzer evidence is supporting only and cannot close visible navigation rows. | Web build + Playwright mutation/navigation matrix | `bash scripts/build_web.sh ../web-app dev`; local/served `main.dart.js` hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; Playwright source spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js`; canonical runner `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; focused no-retry SR-D2 run passed `1 passed (1.8m)`. | Final Web browser runtime `https://guarappari.belluga.space` | passed | Source spec declares `NAV-01..NAV-13` and the mutation journey executes every visible row after the current Web publish. |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `strategic-cto` | Route contract and story split may require product/architecture approval before implementation. | Event routes, public detail model, admin authoring model | `planned` |
| `operational-coder` | `assurance-tester-quality` | Multi-occurrence flows need integration coverage across list/detail/admin. | Laravel + Flutter tests | `planned` |

## Complexity

- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** Public route/detail semantics and tenant-admin authoring are independently testable slices; this TODO may need to split after decisions are closed.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Planned decision promotion targets (module sections):**
  - Events occurrence-first read/detail contract.
  - Events occurrence-scoped related-profile contract and effective merge semantics.
  - Events occurrence programação contract.
  - Events programação item Account Profile/Map POI location contract.
  - Events/Map address aggregation and deduplication contract for `Como Chegar`.
  - Flutter public event detail selected-occurrence route/hydration contract.
  - Flutter public event detail `Programação` date selector and no-`Datas` contract.
  - Tenant-admin event authoring occurrence-management contract.
  - Tenant-admin Events list occurrence-first read/navigation contract.
- **Module decision consolidation targets:**
  - Events module read/write model and client contract summary.
  - Flutter client route-driven hydration contract for event detail.

## Decisions

- [x] `D-D-01` Public list/discovery remains occurrence-first.
- [x] `D-D-02` Public event cards remain occurrence-only. The card does not need to show "other dates" or group sibling occurrences.
- [x] `D-D-03` Other-dates/event-level navigation belongs inside the detail surface reached from an occurrence.
- [x] `D-D-04` The current create-event UI baseline is intentional: creating an event creates the first occurrence automatically. Multi-occurrence authoring must extend this model, not reinterpret the first version as a bug.
- [x] `D-D-08` Superseded by `D-D-34`: occurrence-level field scope is bounded to agenda/date-time, occurrence-scoped related profiles, and occurrence-exclusive programação. Occurrence-level location override is no longer approved.
- [x] `D-D-10` Effective occurrence related profiles are additive: render event-level related profiles plus that occurrence's own related profiles. Occurrence-owned profiles do not replace event-level profiles.
- [x] `D-D-11` Effective event related profiles are additive: render event-level related profiles plus all occurrence-owned related profiles across every occurrence.
- [x] `D-D-12` Related-profile merges must be deduplicated deterministically. Event-level ordering wins first, then occurrence order, then profile order within each occurrence.
- [x] `D-D-13` Tenant-admin authoring should expose event-level related profiles in the shared event section and occurrence-owned related profiles inside each occurrence editor as additional profiles for that occurrence.
- [x] `D-D-14` Superseded by `D-D-35`: occurrences do not own location. Tenant-admin occurrence editors must not expose occurrence location override as the approved model.
- [x] `D-D-15` Superseded by `D-D-35` and `D-D-41`: event location remains the default event address; programação item locations are separate Account Profile/Map POI references and feed programação cards plus `Como Chegar`.
- [x] `D-D-16` Programação is occurrence-exclusive. It is authored inside each occurrence and is not an event-level field.
- [x] `D-D-17` Superseded by `D-D-38`: public event detail renders `Programação` when the event has programação in at least one occurrence, not only when the selected occurrence has items.
- [x] `D-D-18` Programação item contract includes time, optional title, and linked related Account Profiles. If title is absent and exactly one profile is linked, the display title falls back to that profile's display name.
- [x] `D-D-19` Superseded by `D-D-40`: programação card layout is enriched with time, display title/profile fallback, participant Account Profile avatars/names, and optional location Account Profile/POI affordance.
- [x] `D-D-20` Store Release does not add a separate event-only detail/hub screen. The existing event detail shell remains the public surface and carries selected-occurrence context.
- [x] `D-D-28` Superseded by `D-D-37`: public event detail must not render a separate `Datas` tab for Store Release.
- [x] `D-D-29` Superseded by `D-D-37` and `D-D-39`: dates are selected inside `Programação` through a date selector.
- [x] `D-D-30` Superseded by `D-D-39`: switching dates inside `Programação` updates selected occurrence context, URL query, selected-date highlight, and programação content/empty state.
- [x] `D-D-32` Superseded by `D-D-39`: the current selected occurrence/date is highlighted in the `Programação` date selector.
- [x] `D-D-21` Tenant-admin event form renders event-level fields and sections first. Occurrence editing appears after the event sections.
- [x] `D-D-22` If an event has exactly one occurrence, the event form renders that occurrence's required/basic fields directly in the occurrence section.
- [x] `D-D-23` The single-occurrence admin form exposes the only path to create the second occurrence through an add-date card/button/FAB. Saving the new occurrence returns to the event form, which then switches to the multi-occurrence card-list layout.
- [x] `D-D-24` If an event has more than one occurrence, the occurrence section renders a vertical list of occurrence cards based on the occurrence list, using a visual language similar to the current event edit/list cards.
- [x] `D-D-25` In the multi-occurrence card-list layout, optional occurrence-specific fields are edited by tapping an occurrence card, opening that occurrence's edit screen. Saving returns to the event form and the card reflects the updated occurrence data.
- [x] `D-D-26` The occurrence list includes a final add-date item/button and also exposes a FAB to add a new date.
- [x] `D-D-27` Superseded by `D-D-34`: tenant-admin occurrence cards summarize date/time, occurrence-owned related profiles, and programação presence/count; location override state is removed.
- [x] `D-D-05` Selected occurrence uses the existing event detail route identity plus optional selected-occurrence query metadata: `/agenda/evento/:slug?occurrence=<occurrence_id>`. If omitted, detail falls back deterministically to live/next occurrence; if the occurrence reference is stale/missing, the screen repairs to the fallback occurrence without breaking the event detail route.
- [x] `D-D-09` Keep this as one Store Release owner TODO for orchestration. Implementation may be sequenced as backend contract, tenant-admin authoring, and public detail slices inside the execution plan; split into separate TODOs only if planning reveals separate approval/promotion cycles.
- [x] `D-D-31` Programação item with more than one linked Account Profile must provide an explicit title. The profile-name fallback applies only when exactly one profile is linked.
- [x] `D-D-33` Multi-occurrence is canonical Events behavior and must not be gated by tenant settings or per-event capabilities. Tenant-admin should not require hidden settings activation before adding dates, and event write payloads must accept multiple occurrences without `capabilities.multiple_occurrences`.
- [x] `D-D-34` Occurrence domain scope is date/time plus occurrence-owned related profiles and occurrence-owned programação items. Occurrences do not own location overrides.
- [x] `D-D-35` Programação item location is optional and must be an Account Profile reference whose Account Profile owns the Map POI. Display text may be snapshotted for rendering, but free-text location is not the source of truth.
- [x] `D-D-36` Public occurrence URLs are aliases into the same event detail screen with selected occurrence context. Direct links may request `tab=programming`; if no programação exists anywhere, fallback is `Sobre`.
- [x] `D-D-37` The separate public `Datas` tab is removed. Multi-date UI belongs inside `Programação`.
- [x] `D-D-38` `Programação` appears when at least one event occurrence has programação. A selected occurrence without programação shows an empty state inside `Programação` instead of pushing the user to repeated `Sobre`.
- [x] `D-D-39` The `Programação` section renders a date selector for multi-occurrence events, highlights the selected occurrence/date, and updates the selected occurrence route query on date selection.
- [x] `D-D-40` Programação item cards render time, title/profile fallback, participant Account Profile avatars/names, and optional location Account Profile/POI affordance. Optional sections must not leave blank space when absent.
- [x] `D-D-41` `Como Chegar` lists all event-related addresses: event default location plus programação item Account Profile/POI locations, deduplicated by canonical Account Profile/POI identity.
- [x] `D-D-42` Programação participant Account Profiles are automatically linked into the event-level related profile set, with reactive admin UI feedback. Programação location Account Profiles feed location/POI behavior and address aggregation; they do not become participant profile tabs unless also selected as participants or event-related profiles.
- [x] `D-D-43` Tenant-admin Events list is occurrence-first for Store Release. Admin list/query/card visibility is based on eligible occurrences, not the event root or first occurrence only; a future/later occurrence remains visible even when the first occurrence has ended. Clicking an occurrence card opens the Event edit screen with the clicked occurrence selected as context/alias while preserving Event aggregate identity.

## Decision Closure

- [x] None. Product/contract decisions are closed for implementation planning.

## Questions To Close

- [x] None before implementation planning.

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-D-*` decisions above are frozen for Store Release orchestration with the `D-D-34` through `D-D-43` supersession as the current authority. Implementation must preserve occurrence-first public discovery, occurrence-first tenant-admin Events listing, occurrence-only list cards, event detail with selected-occurrence context, `Programação`-owned date selection, no public `Datas` tab, no occurrence-level location override, programação item Account Profile/Map POI locations, enriched programação cards, `Como Chegar` address aggregation/deduplication, approved tenant-admin occurrence authoring, selected occurrence route query semantics, and always-available multi-occurrence writes without tenant/event capability guards.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** Backend fail-first tests and contract extension for tenant-admin occurrence-first list reads, programação item location Account Profile/Map POI references, removal/rejection of occurrence location overrides, related-profile auto-linking, address aggregation/deduplication, and selected-occurrence detail payload behavior.
- **Sequencing note:** Keep as one Store Release owner TODO. Execute in slices: backend contract first, tenant-admin occurrence-first list plus occurrence/programação authoring second, public detail Programação/Como Chegar third, final Playwright navigation matrix last.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-D-01` | Backend/domain already stores `occurrences[]` and can support multiple occurrences without new aggregate ownership, but programação item Account Profile/Map POI locations, occurrence-location override removal, related-profile auto-linking, and address dedup projections require bounded write/read contract extensions. | Events module write model accepts `occurrences[]`; current UI intentionally materializes the first occurrence; current occurrence payload already carries programação items in the superseded pass. | Scope expands beyond the Events package into a broader map/account-profile schema redesign. | `Medium` | `Keep as Assumption` |
| `A-D-02` | Home agenda reads occurrence-first data. | Intake verified Home loads `/agenda` through occurrence-first schedule repository. | Public list behavior may need broader agenda contract work. | `High` | `Keep as Assumption` |
| `A-D-03` | Current event detail route by slug needs selected-occurrence metadata for correct multi-occurrence navigation. | Current Home cards navigate to event slug, but occurrence-first cards can represent distinct occurrences of the same event. | Event detail may keep ambiguous fallback and fail UX fidelity. | `High` | `Promote to Decision` |

## Execution Plan

### Touched Surfaces

- Events package read/write contracts, occurrence related-profile/programação payloads, programação item Account Profile/Map POI location payloads, address aggregation/dedup projections, and occurrence detail payloads.
- Flutter public Home/Event list navigation, immersive event detail `Programação`, and `Como Chegar`.
- Flutter tenant-admin event create/edit form, occurrence editor, and programação item editor.
- Tests across Laravel and Flutter.
- Canonical module docs after decisions are approved.

### Ordered Steps

1. Freeze the resolved decision baseline before implementation approval.
2. Add fail-first Laravel tests for programação item location Account Profile/Map POI references, occurrence-location override rejection, auto-linked participant profiles, and address deduplication.
3. Add fail-first Flutter tests for `Datas` tab removal, `Programação` date selector, enriched programação cards, location map navigation, and `Como Chegar` address aggregation/deduplication.
4. Implement backend payload/validation/projection changes for the approved model.
5. Implement Flutter tenant-admin occurrence/programação authoring UI, including participant auto-link feedback and programação item location selection.
6. Implement Flutter public detail `Programação` date selector, route tab fallback, enriched cards, location POI navigation, and `Como Chegar` address list.
7. Rebuild Web and run the Playwright `NAV-*` navigation matrix against the approved local/final domain.
8. Promote stable decisions into module docs.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** Multi-occurrence behavior can regress silently by collapsing to the event slug fallback.
- **Fail-first targets:** Route hydration with selected occurrence, direct `tab=programming` fallback, absence of public `Datas` tab, `Programação` date selector/highlight/switching, selected occurrence empty state, programação item participant/avatar/title fallback, optional location Account Profile/Map POI navigation, `Como Chegar` aggregation/deduplication, occurrence-location override rejection, participant auto-linking, single-to-multi admin transition, occurrence card edit/save/refresh, and admin multi-occurrence validation.

### Runtime / Rollout Notes

- Single-occurrence events must behave exactly like today unless the user enters the new multi-occurrence affordance.
- Existing event links without occurrence metadata must remain valid with deterministic fallback.
- Shared/deep-linked URLs should preserve selected occurrence and requested `tab=programming` when applicable.
- Web navigation evidence must be produced only after `bash scripts/build_web.sh ../web-app dev`; Flutter/Laravel unit/widget/feature tests are supporting evidence and do not replace `NAV-*` runtime evidence for visible behavior.

## Audit Trigger Matrix

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `big` | Route/detail/admin cross-stack work. |
| `blast_radius` | `cross-stack` | Laravel Events + Flutter routes/screens/forms. |
| `behavioral_change_or_bugfix` | `yes` | New multi-occurrence behavior. |
| `changes_public_contract` | `yes` | Selected occurrence route/payload likely changes. |
| `touches_auth_or_tenant` | `yes` | Tenant-admin event authoring. |
| `touches_runtime_or_infra` | `no` | No infra expected. |
| `touches_tests` | `yes` | Broad tests required. |
| `critical_user_journey` | `yes` | Public event discovery/detail and tenant-admin event authoring are high-usability release flows. |
| `release_or_promotion_critical` | `yes` | Store Release usability priority. |
| `high_severity_plan_review_issue` | `no` | No plan review issue recorded yet. |
