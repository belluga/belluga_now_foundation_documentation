# TODO (Store Release): Event Multi-Occurrence UX and Authoring Model

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
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
- **Direct-to-TODO rationale:** The public list/card direction, public detail tab model, related-profile merge semantics, occurrence location inheritance/override model, and occurrence-exclusive programação model are settled. The remaining material decisions are selected-occurrence route encoding and programming-title fallback, plus bounded contract extensions for occurrence-scoped related profiles, optional occurrence location overrides, and occurrence programação items.

## Delivery Status Canon

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** Keep ready for dev promotion; rerun `todo_completion_guard.py --require-delivery` before any delivery-stage or promotion claim changes.

## Package-First Assessment

- **Status:** completed before implementation.
- **Queries run:** `event` from the ecosystem root.
- **Relevant packages found:** only unrelated analytics package `event_tracker_handler`.
- **Decision:** local implementation inside existing Events package and Flutter event/admin surfaces.
- **Rationale:** the slice extends the existing `belluga_events` aggregate/read model and the existing tenant-public/tenant-admin event screens; no reusable proprietary package owns multi-occurrence authoring/detail semantics.

## Blocker Notes

- **Blocker:** `n/a`
- **Why blocked now:** `n/a`
- **What unblocks it:** `n/a`
- **Owner / source:** `n/a`
- **Last confirmed truth:** Home/public discovery should remain occurrence-first; list cards remain occurrence-only; other dates/event-level navigation belongs in the detail surface, not in the card. When an event has multiple dates, the public detail shows a `Datas` tab after `Sobre`; this tab renders one card per occurrence and lets the user switch the selected occurrence. No separate event-only screen is needed for Store Release. Related profiles compose additively: an occurrence shows event-level profiles plus its own profiles, while event-level views show event-level profiles plus every occurrence-owned profile. Location is inherited from the event by default, but each occurrence may override its location. Programação is owned exclusively by the occurrence and appears as a public detail tab only when the selected occurrence has programação items.

## Scope

- [ ] Preserve occurrence-first public listing/discovery semantics.
- [ ] Preserve occurrence-only public cards; cards do not become event-grouped and do not need multi-date summaries.
- [ ] Add detail-surface affordances for an occurrence that belongs to an event with additional occurrences.
- [ ] Define how a selected occurrence is represented in route/navigation/hydration when multiple occurrences share one event slug.
- [ ] Implement the approved tenant-admin create/edit UX for adding, editing, and validating multiple occurrences.
- [ ] Treat multiple occurrences as an always-available Events contract, not as a tenant capability or per-event capability.
- [ ] Extend the occurrence contract to support occurrence-scoped related profiles while preserving event-level related profiles as shared/global profiles.
- [ ] Extend the occurrence contract to support optional occurrence-scoped location overrides while preserving event-level location as the default inherited location.
- [ ] Extend the occurrence contract to support occurrence-exclusive programação items.
- [ ] Render effective occurrence related profiles as event-level profiles plus that occurrence's own related profiles, deduplicated deterministically.
- [ ] Render effective event related profiles as event-level profiles plus all occurrence-owned related profiles, deduplicated deterministically.
- [ ] Resolve effective occurrence location as occurrence override when present, otherwise event-level location.
- [ ] Render a `Programação` tab in public event detail only when the selected occurrence has programação items.
- [ ] Render a `Datas` tab after `Sobre` when the event has more than one occurrence.
- [ ] Render one occurrence/date card per occurrence in the `Datas` tab and allow selecting/navigating to another occurrence from those cards.
- [ ] Highlight the currently selected occurrence/date card in the `Datas` tab so the user can identify the active date.
- [ ] Render programação cards with time on the left and, on the right, a column with title followed by a row of linked Account Profiles.
- [ ] Ensure public detail, Home agenda, event search/list, and admin forms all keep event identity and occurrence identity distinct.
- [ ] Add tests for occurrence-first list behavior, selected occurrence detail hydration, other-dates navigation, and multi-occurrence authoring.

## Out of Scope

- [ ] Replacing public Home/Event cards with event-grouped cards.
- [ ] Adding a separate event-only detail/hub screen for Store Release.
- [ ] Changing event aggregate ownership or moving occurrence persistence out of Events.
- [ ] Ticketing/check-in occurrence policy redesign beyond preserving existing occurrence identity.
- [ ] Broad per-occurrence overrides for event title/content/media/type/publication/taxonomies.
- [ ] Event-level programação. Programação belongs to occurrences only.
- [ ] Map POI projection changes unless selected-occurrence routing requires a small adapter update.
- [ ] Text search changes.

## Execution Lane Tracking

- **Local implementation branches:** `orchestrator/store-release-usability-wave` in `belluga_now_docker`, `laravel-app`, and `flutter-app`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Event multi-occurrence UX/model | `orchestrator/store-release-usability-wave` | `n/a - not promoted yet` | `n/a - not promoted yet` | `n/a - not promoted yet` | `Local-Implemented; final runtime acceptance passed` |

## Local Implementation Evidence

- Laravel Events write path accepts occurrence-owned related profiles, optional occurrence location overrides, and occurrence-owned `programming_items` while preserving omitted occurrence-owned fields on update.
- Laravel occurrence projections store own/effective related profiles, effective location, location override state, and programming item summaries for cheap document reads.
- Public event detail accepts optional selected-occurrence query metadata, repairs stale/missing occurrence references to deterministic live/next/first fallback, and exposes `occurrences[]` with `is_selected`.
- Flutter public list/card navigation preserves occurrence identity when available without grouping list cards by event.
- Flutter immersive event detail renders `Datas` after `Sobre` for multi-date events, highlights the current occurrence card, switches dates through `/agenda/evento/:slug?occurrence=<id>`, and shows `Programação` only for the selected occurrence.
- Flutter tenant-admin event form keeps shared event fields first, supports single-to-multi occurrence transition, renders occurrence cards, and lets occurrence editors author occurrence-owned profiles, location override, and Programação.
- Canonical module docs promoted the contract in Events, Flutter Client Experience, Tenant Admin, and Agenda modules.

## Local Validation Evidence

- 2026-04-22 SR-D current guard precheck: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` returned `Overall outcome: go` only because `Current delivery stage` remains `Pending` and no delivery claim is active; final runtime acceptance remains pending below.
- 2026-04-22 SR-D tenant-admin FAB/save regression rerun: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name "adds a second occurrence date"` passed (`2 tests`) and asserts `tenantAdminEventAddOccurrenceButton` is a real `FloatingActionButton` before proving create and edit draft payloads retain two occurrences.
- 2026-04-22 SR-D tenant-admin full form rerun: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed (`17 tests`), covering add-card/FAB flow, occurrence editor save-return-refresh, occurrence-owned profile/location/programming, create/update payload mutation, and baseline single-occurrence behavior.
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
- 2026-04-22 SR-D integration harness attempt: `FLUTTER_INTEGRATION_RUN_TIMEOUT_SECONDS=1200 bash tool/run_integration_test_wsl.sh integration_test/feature_admin_event_occurrence_authoring_test.dart` was blocked before execution because ADB device `192.168.15.5:5555` was unreachable. `fvm flutter test ... -d linux` was also blocked by missing system package `libsecret-1>=0.18.4`. These are invalid harness evidence, not passing delivery evidence.
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
- [x] Event detail can represent and switch the selected occurrence when an event has more than one occurrence.
- [x] Multi-date public event detail renders `Datas` after `Sobre` and uses it as the occurrence navigation surface.
- [x] The selected occurrence is visually highlighted in the `Datas` tab.
- [x] Tenant-admin can create and edit events with multiple occurrences without losing the current "first occurrence created with event" baseline.
- [x] Route/hydration contracts distinguish event identity from selected occurrence identity.
- [x] Occurrence detail/profile sections use effective related profiles from `event.event_parties + occurrence.event_parties`.
- [x] Event-level summaries use effective related profiles from `event.event_parties + all occurrence.event_parties`.
- [x] Occurrence detail, Map/directions affordances, and occurrence cards use effective location from `occurrence.location_override ?? event.location`.
- [x] The selected occurrence can expose programação items ordered by time.
- [x] Public detail shows a `Programação` tab only for selected occurrences with at least one programação item.
- [x] Programação item cards render time, display title, and linked Account Profile row according to the approved card model.
- [x] Tests cover public list cards, detail other-dates navigation, selected-occurrence fallback, admin occurrence validation, and regression for single-occurrence events.

## Validation Steps

- [x] Laravel feature/package tests for multi-occurrence create/update/list/detail payloads and selected-occurrence lookup semantics.
- [x] Laravel tests for event-level and occurrence-level related-profile merge/dedup semantics.
- [x] Laravel tests for occurrence location inheritance, explicit location override, and fallback back to event location.
- [x] Laravel tests for occurrence programação validation, ordering, linked profile resolution, and absence from event-level fields.
- [x] Flutter route/controller tests for event detail hydration with and without selected occurrence.
- [x] Flutter widget/controller tests for conditional `Datas` tab placement after `Sobre`, date-card rendering, and occurrence switching.
- [x] Flutter widget tests for other-dates UI and single/multiple occurrence detail states.
- [x] Flutter widget/controller tests for occurrence effective related profiles and event-level merged related profiles.
- [x] Flutter widget/controller tests for effective occurrence location in detail/cards/directions affordances.
- [x] Flutter widget/controller tests for conditional `Programação` tab, programação card layout, title fallback, linked profile row, and selected-occurrence switching.
- [x] Flutter integration tests from Home occurrence card to Event detail and back.
- [x] Tenant-admin form/navigation tests for single-occurrence fields, transition to occurrence-card list, FAB/add-card flow, occurrence detail edit, save-return-refresh, and chronological validation.

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOD-01 | Definition of Done | Public Home/Event list remains occurrence-first and occurrence-card-only. | Playwright navigation + Flutter widget/repository tests | Final targeted SR-D no-capability Playwright mutation passed `1 passed (1.3m)` after `bash scripts/build_web.sh ../web-app dev`; supporting Flutter focused suite. | Final Web runtime `https://guarappari.belluga.space` + Flutter host | passed | Playwright opens `/agenda`, selects a real occurrence card, verifies detail URL with `occurrence=<id>`, goes back to the list, and asserts `Datas do evento` is absent from the list/card surface. |
| DOD-02 | Definition of Done | Event detail can represent and switch the selected occurrence when an event has more than one occurrence. | Playwright navigation + Laravel/Flutter tests | Final SR-D Playwright mutation spec; Laravel CRUD/detail focused command; Flutter focused suite | Final Web runtime + Laravel `/events/{event}` detail + Flutter immersive detail | passed | Playwright opens `/agenda/evento/:slug?occurrence=<second_id>` and asserts selected occurrence detail state; supporting tests cover fallback and date-card switching. |
| DOD-03 | Definition of Done | Multi-date public event detail renders `Datas` after `Sobre` and uses it as the occurrence navigation surface. | Playwright navigation + Flutter widget tests | Final SR-D Playwright mutation spec; Flutter focused suite | Final Web runtime + Flutter immersive detail | passed | Playwright asserts `Sobre`, `Datas`, and `Datas do evento` on the public detail after the current Web build. |
| DOD-04 | Definition of Done | The selected occurrence is visually highlighted in the `Datas` tab. | Playwright navigation + Flutter widget test | Final SR-D Playwright mutation spec; `fvm flutter test ... immersive_event_detail_screen_test.dart --plain-name "event detail dates tab highlights current occurrence"` | Final Web runtime + Flutter immersive detail | passed | Playwright asserts visible `Atual` selected-occurrence highlight; widget test covers selected date-card badge/key and route switching. |
| DOD-05 | Definition of Done | Tenant-admin can create and edit events with multiple occurrences without losing the current "first occurrence created with event" baseline. | Playwright mutation + Flutter/Laravel tests | Final targeted SR-D no-capability Playwright mutation; Flutter focused suite; Laravel CRUD/detail focused command | Final Web runtime tenant-admin + Laravel Events write path | passed | Playwright uses the real tenant-admin add-occurrence FAB path, saves the second occurrence, submits `PATCH /admin/api/v1/events/:id`, verifies admin API readback, and reopens UI with two occurrence cards without tenant/event multi-occurrence configuration. |
| DOD-06 | Definition of Done | Route/hydration contracts distinguish event identity from selected occurrence identity. | Playwright navigation + route/backend tests | Final SR-D Playwright mutation spec; Flutter route tests; Laravel detail test | Final Web runtime + Flutter route resolver + Laravel detail lookup | passed | Playwright opens event slug with `occurrence=<second_id>` and verifies selected occurrence hydration without changing event identity. |
| DOD-07 | Definition of Done | Occurrence detail/profile sections use effective related profiles from `event.event_parties + occurrence.event_parties`. | Playwright navigation + Laravel/Flutter tests | Final SR-D Playwright programmed-occurrence journey; Laravel CRUD/detail focused command; Flutter focused suite | Final Web runtime + Laravel event detail + Flutter event DTO/detail | passed | Playwright seeds event-level and occurrence-level related profiles, asserts public API effective merge, and verifies the occurrence programming linked-profile row in browser runtime. |
| DOD-08 | Definition of Done | Event-level summaries use effective related profiles from `event.event_parties + all occurrence.event_parties`. | Laravel query tests + Flutter repository tests | Existing clean reconciliation: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Unit/Events/EventQueryServiceTest.php`; Flutter focused suite | Laravel agenda/event summaries + Flutter repositories | passed | Existing clean pass recorded `28 tests, 110 assertions`; current rerun was invalidated only by shared Mongo drop conflicts. |
| DOD-09 | Definition of Done | Occurrence detail, Map/directions affordances, and occurrence cards use effective location from `occurrence.location_override ?? event.location`. | Playwright navigation + Laravel/Flutter tests | Final SR-D Playwright programmed-occurrence journey; `fvm flutter test test/infrastructure/dal/dto/schedule/event_dto_test.dart`; Laravel CRUD/detail focused command; Flutter focused suite | Final Web runtime + Laravel occurrence projection + Flutter detail/date cards | passed | Playwright seeds an online occurrence location override, asserts public API effective location, verifies `Local específico`/`1 item na programação` chips in `Datas`, opens `Como Chegar`, and verifies the effective location label `Transmissao ao vivo` in browser runtime after the DTO online-location fix. |
| DOD-10 | Definition of Done | The selected occurrence can expose programação items ordered by time. | Playwright navigation + Laravel/Flutter tests | Final SR-D Playwright programmed-occurrence journey; Laravel CRUD/detail focused command; Flutter focused suite | Final Web runtime + Laravel occurrence detail + Flutter selected occurrence detail | passed | Playwright asserts selected occurrence `programming_items` through public API and browser rendering of the `17:00` programação card. |
| DOD-11 | Definition of Done | Public detail shows a `Programação` tab only for selected occurrences with at least one programação item. | Playwright navigation + Flutter widget tests | Final SR-D Playwright programmed-occurrence journey; Flutter focused suite | Final Web runtime + Flutter immersive event detail | passed | Playwright asserts the conditional `Programação` tab on the selected programmed occurrence; widget tests cover absence on single/no-programming states. |
| DOD-12 | Definition of Done | Programação item cards render time, display title, and linked Account Profile row according to the approved card model. | Playwright navigation + Flutter/Laravel tests | Final SR-D Playwright programmed-occurrence journey; Flutter focused suite; Laravel CRUD/detail focused command | Final Web runtime + Flutter programming section + Laravel programming item contract | passed | Playwright asserts programming card time, title, and linked Account Profile row in browser runtime; supporting tests cover title fallback and validation. |
| DOD-13 | Definition of Done | Tests cover public list cards, detail other-dates navigation, selected-occurrence fallback, admin occurrence validation, and regression for single-occurrence events. | Playwright mutation suite + local focused tests | Final targeted SR-D no-capability Playwright mutation `1 passed (1.3m)`; current Flutter focused suite `69 tests`; current Laravel focused command `8 tests, 61 assertions`; existing clean agenda/query suite evidence | Final Web runtime + Laravel + Flutter focused surfaces | passed | The final targeted mutation covers public list/card, detail other-dates, selected fallback, admin mutation, programming, and effective-location runtime assertions; supporting Flutter and Laravel suites passed after the no-capability change. |
| VAL-01 | Validation Steps | Laravel feature/package tests for multi-occurrence create/update/list/detail payloads and selected-occurrence lookup semantics. | Laravel feature tests | Laravel CRUD/detail focused command | Laravel Events package/API | passed | `8 tests, 61 assertions passed`; covers create/update payload persistence, selected-occurrence detail lookup, and no tenant/event capability gate. |
| VAL-02 | Validation Steps | Laravel tests for event-level and occurrence-level related-profile merge/dedup semantics. | Laravel feature/query tests | Laravel CRUD/detail focused command; existing clean agenda/query suite evidence | Laravel Events projections | passed | CRUD/detail test covers occurrence-owned profile merge; existing clean agenda/query suite covers event-level summary semantics. |
| VAL-03 | Validation Steps | Laravel tests for occurrence location inheritance, explicit location override, and fallback back to event location. | Laravel feature tests | Laravel CRUD/detail focused command | Laravel event occurrence projections | passed | Create/update test asserts override state and fallback preservation. |
| VAL-04 | Validation Steps | Laravel tests for occurrence programação validation, ordering, linked profile resolution, and absence from event-level fields. | Laravel feature tests | Laravel CRUD/detail focused command | Laravel Events write/read contracts | passed | Programming validation/title rule and occurrence-only persistence are covered by focused EventCrud tests. |
| VAL-05 | Validation Steps | Flutter route/controller tests for event detail hydration with and without selected occurrence. | Flutter route/widget tests | Flutter focused suite | Flutter route resolver + immersive detail | passed | Route test asserts occurrence query exposure; widget/repository tests cover selected and fallback hydration. |
| VAL-06 | Validation Steps | Flutter widget/controller tests for conditional `Datas` tab placement after `Sobre`, date-card rendering, and occurrence switching. | Flutter widget tests | Flutter focused suite | Flutter immersive event detail | passed | Detail widget tests cover `Datas`, date cards, active highlight, and route replacement on switch. |
| VAL-07 | Validation Steps | Flutter widget tests for other-dates UI and single/multiple occurrence detail states. | Flutter widget tests | Flutter focused suite | Flutter immersive event detail | passed | Detail tests cover multi-date UI and single-occurrence states without extra date/programming tabs. |
| VAL-08 | Validation Steps | Flutter widget/controller tests for occurrence effective related profiles and event-level merged related profiles. | Flutter DTO/repository/widget tests | Flutter focused suite | Flutter schedule repository + event detail | passed | DTO/repository tests parse linked profile payloads; detail tests render dynamic account-profile tabs/cards. |
| VAL-09 | Validation Steps | Flutter widget/controller tests for effective occurrence location in detail/cards/directions affordances. | Flutter widget/DTO tests | Flutter focused suite | Flutter event detail + date cards | passed | Date-card widget path shows override state and detail uses effective selected occurrence data for `Como Chegar`. |
| VAL-10 | Validation Steps | Flutter widget/controller tests for conditional `Programação` tab, programação card layout, title fallback, linked profile row, and selected-occurrence switching. | Flutter widget/DTO tests | Flutter focused suite | Flutter programming section + event detail | passed | Programming widget and DTO tests cover conditional tab, item layout, fallback semantics, linked profile row, and selected occurrence switching. |
| VAL-11 | Validation Steps | Flutter integration tests from Home occurrence card to Event detail and back. | Playwright navigation + Flutter navigation source/widget evidence | Final targeted SR-D no-capability Playwright mutation passed; supporting `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart --plain-name "taps My Events card and pushes detail route"` | Final Web runtime + Flutter tenant-public Home/detail navigation source | passed | Playwright opens `/agenda`, clicks a real occurrence card, verifies detail URL with `occurrence=<id>`, and returns with browser back; source test captures route metadata and query params. |
| VAL-12 | Validation Steps | Tenant-admin form/navigation tests for single-occurrence fields, transition to occurrence-card list, FAB/add-card flow, occurrence detail edit, save-return-refresh, and chronological validation. | Playwright mutation + Flutter widget tests | Final targeted SR-D no-capability Playwright mutation passed; supporting `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name "adds a second occurrence date"` and focused Flutter suite | Final Web runtime tenant-admin + Flutter tenant-admin event form | passed | Playwright covers real FAB/add-card path, occurrence editor save, event `PATCH`, admin readback, and UI reopen/refresh without tenant/event multi-occurrence configuration; widget assertions require a real `FloatingActionButton` for key `tenantAdminEventAddOccurrenceButton`. |

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
  - Events occurrence location inheritance/override contract.
  - Events occurrence programação contract.
  - Flutter public event detail selected-occurrence route/hydration contract.
  - Tenant-admin event authoring occurrence-management contract.
- **Module decision consolidation targets:**
  - Events module read/write model and client contract summary.
  - Flutter client route-driven hydration contract for event detail.

## Decisions

- [x] `D-D-01` Public list/discovery remains occurrence-first.
- [x] `D-D-02` Public event cards remain occurrence-only. The card does not need to show "other dates" or group sibling occurrences.
- [x] `D-D-03` Other-dates/event-level navigation belongs inside the detail surface reached from an occurrence.
- [x] `D-D-04` The current create-event UI baseline is intentional: creating an event creates the first occurrence automatically. Multi-occurrence authoring must extend this model, not reinterpret the first version as a bug.
- [x] `D-D-08` Occurrence-level field scope is bounded to agenda/date-time, occurrence-scoped related profiles, optional occurrence-scoped location override, and occurrence-exclusive programação. Event title/content/media/type/publication/taxonomies remain shared event-level fields for Store Release.
- [x] `D-D-10` Effective occurrence related profiles are additive: render event-level related profiles plus that occurrence's own related profiles. Occurrence-owned profiles do not replace event-level profiles.
- [x] `D-D-11` Effective event related profiles are additive: render event-level related profiles plus all occurrence-owned related profiles across every occurrence.
- [x] `D-D-12` Related-profile merges must be deduplicated deterministically. Event-level ordering wins first, then occurrence order, then profile order within each occurrence.
- [x] `D-D-13` Tenant-admin authoring should expose event-level related profiles in the shared event section and occurrence-owned related profiles inside each occurrence editor as additional profiles for that occurrence.
- [x] `D-D-14` Occurrence location is inherited from the event by default. Tenant-admin occurrence editors may enable an override to set that occurrence's own location/place reference/online location.
- [x] `D-D-15` Effective occurrence location resolves to occurrence override when present, otherwise event-level location. Event-level detail/summary may use event-level location as the canonical event location and use occurrence effective locations only when rendering occurrence-specific dates/cards/directions.
- [x] `D-D-16` Programação is occurrence-exclusive. It is authored inside each occurrence and is not an event-level field.
- [x] `D-D-17` Public event detail renders `Programação` as an additional tab only when the selected occurrence has at least one programação item.
- [x] `D-D-18` Programação item contract includes time, optional title, and linked related Account Profiles. If title is absent and exactly one profile is linked, the display title falls back to that profile's display name.
- [x] `D-D-19` Programação card layout is time on the left and a right column containing display title plus a row of related Account Profile chips/cards below it.
- [x] `D-D-20` Store Release does not add a separate event-only detail/hub screen. The existing event detail shell remains the public surface and carries selected-occurrence context.
- [x] `D-D-28` When an event has more than one occurrence/date, public event detail renders a `Datas` tab after `Sobre`.
- [x] `D-D-29` The `Datas` tab renders one card per occurrence/date and is the public UI used to navigate/switch between occurrences.
- [x] `D-D-30` Switching date cards updates the selected occurrence context inside the same detail shell, including effective related profiles, effective location, and conditional `Programação`.
- [x] `D-D-32` In the public `Datas` tab, the current selected occurrence/date card must be visually highlighted so the user can identify which occurrence is active.
- [x] `D-D-21` Tenant-admin event form renders event-level fields and sections first. Occurrence editing appears after the event sections.
- [x] `D-D-22` If an event has exactly one occurrence, the event form renders that occurrence's required/basic fields directly in the occurrence section.
- [x] `D-D-23` The single-occurrence admin form exposes the only path to create the second occurrence through an add-date card/button/FAB. Saving the new occurrence returns to the event form, which then switches to the multi-occurrence card-list layout.
- [x] `D-D-24` If an event has more than one occurrence, the occurrence section renders a vertical list of occurrence cards based on the occurrence list, using a visual language similar to the current event edit/list cards.
- [x] `D-D-25` In the multi-occurrence card-list layout, optional occurrence-specific fields are edited by tapping an occurrence card, opening that occurrence's edit screen. Saving returns to the event form and the card reflects the updated occurrence data.
- [x] `D-D-26` The occurrence list includes a final add-date item/button and also exposes a FAB to add a new date.
- [x] `D-D-27` Tenant-admin occurrence cards must summarize at least date/time, effective location override state when present, occurrence-owned related profiles, and programação presence/count.
- [x] `D-D-05` Selected occurrence uses the existing event detail route identity plus optional selected-occurrence query metadata: `/agenda/evento/:slug?occurrence=<occurrence_id>`. If omitted, detail falls back deterministically to live/next occurrence; if the occurrence reference is stale/missing, the screen repairs to the fallback occurrence without breaking the event detail route.
- [x] `D-D-09` Keep this as one Store Release owner TODO for orchestration. Implementation may be sequenced as backend contract, tenant-admin authoring, and public detail slices inside the execution plan; split into separate TODOs only if planning reveals separate approval/promotion cycles.
- [x] `D-D-31` Programação item with more than one linked Account Profile must provide an explicit title. The profile-name fallback applies only when exactly one profile is linked.
- [x] `D-D-33` Multi-occurrence is canonical Events behavior and must not be gated by tenant settings or per-event capabilities. Tenant-admin should not require hidden settings activation before adding dates, and event write payloads must accept multiple occurrences without `capabilities.multiple_occurrences`.

## Decision Pending

- [x] None. Product/contract decisions are closed for implementation planning.

## Questions To Close

- [x] None before implementation planning.

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-D-*` decisions above are frozen for Store Release orchestration. Implementation must preserve occurrence-first discovery, occurrence-only list cards, event detail with selected-occurrence context, `Datas` after `Sobre` with active occurrence highlight, occurrence-exclusive `Programação`, additive related-profile merges, optional occurrence location overrides, approved tenant-admin occurrence authoring, selected occurrence route query semantics, and always-available multi-occurrence writes without tenant/event capability guards.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** Backend fail-first tests and contract extension for occurrence-scoped related profiles, location overrides, programação items, effective profile merge, effective location resolution, and selected-occurrence detail payload behavior.
- **Sequencing note:** Keep as one Store Release owner TODO. Execute in slices: backend contract first, tenant-admin occurrence authoring second, public detail route/tabs third.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-D-01` | Backend/domain already stores `occurrences[]` and can support multiple occurrences without new aggregate ownership, but occurrence-scoped related profiles, location overrides, and programação items require bounded write/read contract extensions. | Events module write model accepts `occurrences[]`; current UI intentionally materializes the first occurrence; current occurrence payload only carries agenda dates. | Scope expands beyond UI into a larger Events package schema redesign. | `Medium` | `Keep as Assumption` |
| `A-D-02` | Home agenda reads occurrence-first data. | Intake verified Home loads `/agenda` through occurrence-first schedule repository. | Public list behavior may need broader agenda contract work. | `High` | `Keep as Assumption` |
| `A-D-03` | Current event detail route by slug needs selected-occurrence metadata for correct multi-occurrence navigation. | Current Home cards navigate to event slug, but occurrence-first cards can represent distinct occurrences of the same event. | Event detail may keep ambiguous fallback and fail UX fidelity. | `High` | `Promote to Decision` |

## Execution Plan

### Touched Surfaces

- Events package read/write contracts, occurrence related-profile/location/programação payloads, and occurrence detail payloads.
- Flutter public Home/Event list navigation and immersive event detail.
- Flutter tenant-admin event create/edit form.
- Tests across Laravel and Flutter.
- Canonical module docs after decisions are approved.

### Ordered Steps

1. Freeze the resolved decision baseline before implementation approval.
2. Add fail-first route/detail/admin tests for the approved model.
3. Implement backend payload/validation changes needed by the selected route/detail/admin model, including occurrence-scoped related profiles, occurrence location overrides, occurrence programação items, effective merge/dedup projections, and effective location resolution.
4. Implement Flutter tenant-admin occurrence authoring UI.
5. Implement Flutter public detail selected-occurrence routing, `Datas`, and conditional `Programação` tabs.
6. Run integration validation and promote stable decisions into module docs.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** Multi-occurrence behavior can regress silently by collapsing to the event slug fallback.
- **Fail-first targets:** Route hydration with selected occurrence, `Datas` tab after `Sobre`, active date-card highlight, date-card occurrence switching, occurrence/event related-profile merge semantics, occurrence location inheritance/override resolution, programação conditional tab/card rendering, single-to-multi admin transition, occurrence card edit/save/refresh, and admin multi-occurrence validation.

### Runtime / Rollout Notes

- Single-occurrence events must behave exactly like today unless the user enters the new multi-occurrence affordance.
- Existing event links without occurrence metadata must remain valid with deterministic fallback.
- Shared/deep-linked URLs should preserve selected occurrence once the route contract is approved.

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
