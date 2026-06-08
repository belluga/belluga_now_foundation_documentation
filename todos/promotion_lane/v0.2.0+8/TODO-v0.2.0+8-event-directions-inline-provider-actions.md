# Title
VNext: Como Chegar Inline Directions Provider Actions

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The Event detail `Como Chegar` tab currently renders a dedicated footer CTA `Traçar rota`. The requested behavior removes that tab-specific CTA/footer and keeps the standard event CTA model used by other tabs. Route actions move into the content of `Como Chegar`.

The screen has one main address/map card and related secondary address cards. Under each card, show direct provider buttons. The main card uses larger buttons; secondary cards use more compact buttons.

Architecture correction recorded on 2026-05-30:

- Tenant-public immersive detail screens render tabs conditionally from capability/data, but recurring tabs must not be rebuilt independently by each screen.
- `Sobre` and `Como Chegar` use centralized immersive tab builders; each screen may still decide whether to include the tab and may provide its own data/content.
- `Como Chegar` content uses one shared directions section for Event, Account Profile, and Static Asset detail surfaces, with caller-supplied map canvas, destination target, and route handlers.

Brand correction recorded on 2026-05-30:

- Visible Waze/Uber provider actions use brand assets instead of generic Material route/taxi icons.
- The `Outros` chooser sheet uses the same brand catalog for Waze/Uber rows so the route flow does not mix branded and generic identity for those providers.
- Follow-up visual pass makes the `Outros` chooser sheet compact, removes repeated per-row subtitles, adds Google Maps and 99 brand assets for existing chooser options, and removes the redundant web fallback row `Abrir no navegador` because it duplicated Google Maps behavior.
- Brand assets are stored under `flutter-app/assets/brands/directions/` with source/trademark notes in that folder's `README.md`; no new provider is introduced.

Additional route-launch behavior requested on 2026-05-25:

- Main direct buttons are only `Waze` and `Uber`.
- A third `Outros` button uses the standard three-dots icon and opens the existing bottom sheet with other options.
- Tapping `Waze` or `Uber` launches that provider directly after origin policy resolution; it must not open the provider chooser sheet.
- Before handing off to an external route app when the route reference-point preference is unset, show a modal asking exactly: `Qual PONTO DE PARTIDA quer usar?`
- The modal choices are `Sua localização atual` and `O ponto de referência selecionado`.
- The `O ponto de referência selecionado` option displays the current reference point label: the Account Profile name when the reference is an Account Profile, or `localização personalizada` when the reference was chosen manually.
- When the selected reference point is an Account Profile, the modal must include an affordance/shortcut to open that Account Profile.
- The modal includes a `Não perguntar de novo` persistence path. If checked, persist the selected option. If not checked, leave the setting `null` and keep prompting on future launches.
- In the address/reference-point selection area, expose a nullable setting for whether the selected `ponto de referência` should be used when tracing routes. `null` means show the dialog; `true` means use the selected reference point; `false` means use current live location.
- Whenever the user chooses a new `ponto de referência`, this route prompt setting must reset to `null` so the next route launch asks again.
- If the canonical/resolved route origin is already the current live location, do not show the dialog.
- If the user chooses the selected reference point, the destination app deep link must use the reference point as the route starting point/origin.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `event-directions-inline-provider-actions`
- **Why this is the right current slice:** this is one visible Event detail/directions experience update that can be validated in one approval cycle.
- **Direct-to-TODO rationale:** user supplied exact provider hierarchy, modal behavior, and persistence semantics.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Feature`, `Cross-Stack`, `Tenant-Public`, `User-Visible`, `Persistence`, `Settings`, `Promotion-Lane-Pending`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through authorized lane follow-through; local implementation is complete and the current package-wide mimic loop has not reopened this scope.

## Scope
- [x] Remove the `Como Chegar` tab-specific footer CTA `Traçar rota`.
- [x] Preserve the standard event CTA/footer behavior used by other tabs.
- [x] Render inline provider actions under the main location card: `Waze`, `Uber`, and `Outros`.
- [x] Render inline provider actions under each secondary location card, with compact sizing.
- [x] Make main-card provider actions visually larger than secondary-card actions.
- [x] Route `Waze` and `Uber` buttons directly to their provider launch path without opening the chooser sheet.
- [x] Route `Outros` through the existing directions bottom sheet/provider chooser.
- [x] Add a route reference-point policy modal when the persisted setting is `null`.
- [x] Modal title/question is exactly `Qual PONTO DE PARTIDA quer usar?`.
- [x] Modal options are `Sua localização atual` and `O ponto de referência selecionado`.
- [x] Modal displays the selected reference point label: Account Profile name for Account Profile references, or `localização personalizada` for manual references.
- [x] Modal includes an Account Profile shortcut when the selected reference point is an Account Profile.
- [x] Modal is skipped when the canonical/resolved route origin is the current live location.
- [x] Choosing the selected reference point makes the external app deep link use the reference point as the route origin.
- [x] Modal supports `Não perguntar de novo`.
- [x] Add/update the address/reference-point selection area with a nullable setting for "use selected reference point when tracing routes".
- [x] Persist the route reference-point prompt preference in settings so `true`/`false` stops future prompts and `null` keeps prompting.
- [x] Reset the route reference-point prompt setting to `null` whenever a new `ponto de referência` is selected.
- [x] Reuse existing `DirectionsAppChooser`/launch URI logic where possible; extend it only where direct provider launch requires a contract.
- [x] Centralize recurring immersive `Sobre` and `Como Chegar` tab creation so screens select common tabs instead of duplicating tab identity.
- [x] Extract shared `Como Chegar` content for Event, Account Profile, and Static Asset immersive detail screens while preserving each screen's own data, map target, and route handlers.
- [x] Render Waze/Uber direct actions and chooser rows through a shared provider brand catalog using Waze/Uber brand assets.
- [x] Render a compact `Outros` chooser sheet with Google Maps, Waze, Uber, and 99 branded rows, no repeated row subtitles, and no duplicated web fallback row.

## Out of Scope
- [ ] Changing Event venue/programming location data model.
- [ ] Adding new provider brands beyond `Waze`, `Uber`, and existing chooser options.
- [ ] Reworking Account Profile or Static Asset detail direction UI beyond the shared immersive tab extraction required by the 2026-05-30 architecture correction.
- [ ] Storing this preference only in local/AppData as source of truth.
- [ ] Tenant-admin `map_ui` settings changes; this is a user settings/preference concern.

## Dependencies & Sequencing
- [x] `DEP-01` Must preserve current Event detail confirmation/invite footer behavior.
- [x] `DEP-02` Should coordinate with `TODO-v0.2.0+8-reference-poi-reference-point-actions.md` because choosing a new `ponto de referência` resets this prompt setting to `null`.
- [x] `DEP-03` May consume existing `LocationOriginService` for resolved coordinates, but route prompt persistence belongs to settings, not local AppData.

## Definition of Done
- [x] `Como Chegar` no longer shows the tab-specific `Traçar rota` footer.
- [x] The event screen still shows the standard event CTA/footer correctly on all tabs.
- [x] Main address card shows larger `Waze`, `Uber`, and `Outros` buttons.
- [x] Secondary address cards show compact `Waze`, `Uber`, and `Outros` buttons.
- [x] `Waze` launches Waze directly when available/launchable after origin policy resolution.
- [x] `Uber` launches Uber directly when available/launchable after origin policy resolution.
- [x] `Outros` opens the chooser bottom sheet.
- [x] When route reference-point setting is `null`, launching a provider prompts for `ponto de referência` vs current location.
- [x] The prompt copy and options match the frozen modal contract.
- [x] The prompt is not shown when the canonical/resolved route origin is already the current live location.
- [x] Account Profile references show a shortcut from the prompt to that Account Profile.
- [x] Choosing the reference point sends the reference point as the origin/start point in Waze/Uber/other provider links.
- [x] If `Não perguntar de novo` is checked, the selected route policy is saved in settings.
- [x] If `Não perguntar de novo` is not checked, the setting remains `null` and future launches keep prompting.
- [x] When the setting is `true` or `false`, no prompt is shown and the saved policy is applied.
- [x] Choosing a new `ponto de referência` resets the route prompt setting to `null`.
- [x] Tests cover direct provider launches, chooser launch, prompt/persistence semantics, and absence of the old footer.
- [x] Event, Account Profile, and Static Asset immersive detail screens consume shared `Sobre`/`Como Chegar` tab builders where those tabs are rendered.
- [x] Event, Account Profile, and Static Asset `Como Chegar` content consumes the shared directions section with inline provider actions.
- [x] Waze/Uber visible provider actions and chooser rows render brand assets from a shared catalog, with `Outros` remaining neutral.
- [x] The chooser sheet is visually compact and uses branded Google Maps/Waze/Uber/99 rows; web does not show `Abrir no navegador` as a duplicate Google Maps option.

## Validation Steps
- [x] Flutter widget test for Event detail `Como Chegar` tab without old footer CTA.
- [x] Flutter widget/controller test for main and secondary inline provider buttons.
- [x] Flutter unit/widget test for route reference-point prompt when setting is `null`.
- [x] Flutter widget test for exact modal copy/options and Account Profile shortcut visibility.
- [x] Flutter test proving no modal appears when canonical/resolved route origin is current live location.
- [x] Flutter/service test proving reference-point choice populates provider deep link origin/start point.
- [x] Flutter repository/service test for persisted settings-backed route prompt preference `true|false|null`.
- [x] Laravel/settings or repository test for read/write/reset semantics if settings persistence is backend-backed in the chosen implementation.
- [x] Flutter test proving direct Waze/Uber launch paths do not open the chooser sheet.
- [x] Flutter widget tests proving Account Profile and Static Asset consume inline shared `Como Chegar` actions and do not retain the old `Traçar rota` footer.
- [x] Analyzer/local CI-equivalent suite row completed before delivery.
- [x] Browser-rendered visual QA for Account Profile mobile/desktop `Como Chegar`, Event mobile `Como Chegar`, and `Outros` chooser sheet after web build.
- [x] Browser-rendered visual QA confirms the compact branded chooser sheet after adding Google Maps/99 assets and removing the duplicate browser fallback row.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level:** `medium`
- **Checkpoint policy:** `full Plan Review Gate before APROVADO + post-validation checkpoint`
- **Why this level:** UI/service work is Flutter-heavy, but settings-backed route prompt persistence introduces cross-stack contract and reset semantics.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/events_module.md`

## Source Inventory Snapshot
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/location_section.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/dynamic_footer.dart`
- `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/static_assets/static_asset_detail_screen.dart`
- `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/immersive_common_tabs.dart`
- `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/tabs/immersive_directions_section.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_provider_actions.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_chooser.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_contract.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_sheet.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_choice.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_launch_target.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_provider_brand_asset.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_provider_brand_catalog.dart`
- `flutter-app/assets/brands/directions/README.md`
- `flutter-app/assets/brands/directions/google_maps_icon_2020.svg`
- `flutter-app/assets/brands/directions/waze_logo_2022.png`
- `flutter-app/assets/brands/directions/uber_logotype.svg`
- `flutter-app/assets/brands/directions/99_logo_2023.png`
- `flutter-app/test/presentation/shared/widgets/directions_app_chooser/directions_provider_actions_test.dart`
- `flutter-app/test/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_test.dart`
- `flutter-app/pubspec.yaml`
- `tools/flutter/web_app_tests/directions_brand_visual.spec.js`
- `tools/flutter/web_app_smoke_runner/playwright.config.js`
- `flutter-app/lib/infrastructure/services/location_origin_service.dart`
- `flutter-app/lib/domain/app_data/location_origin_settings.dart`
- `flutter-app/lib/infrastructure/repositories/app_data_repository.dart`
- `flutter-app/lib/domain/proximity_preferences/**`
- `flutter-app/lib/infrastructure/repositories/proximity_preferences_repository.dart`
- `laravel-app/app/Application/ProximityPreferences/**`

## Package-First Assessment
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "directions"`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "icon"`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "maps"`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "brand"`
- Relevant packages found: none.
- READMEs read: none from proprietary package registry; existing Flutter dependencies were inspected and `flutter_svg` was already available.
- Decision: local shared Flutter implementation under `presentation/shared/widgets/directions_app_chooser`, reusing existing `flutter_svg` and adding static brand assets.
- Tier: host-local implementation with external static brand assets.
- Rationale: no proprietary package covered directions-provider branding; the behavior is tenant-public UI-specific and belongs in the existing shared directions chooser/widget surface.

## Decisions
- [x] `D-DIR-01` The only primary inline provider buttons are `Waze` and `Uber`.
- [x] `D-DIR-02` `Outros` uses the standard three-dots icon and opens the chooser bottom sheet.
- [x] `D-DIR-03` Waze/Uber direct buttons skip the chooser sheet.
- [x] `D-DIR-04` Route reference-point prompt is tri-state: `null` prompts, `true` uses the selected `ponto de referência`, `false` uses current live location.
- [x] `D-DIR-05` The prompt must display the current `ponto de referência` before the user chooses.
- [x] `D-DIR-06` Persisted `true`/`false` route prompt choices stop the prompt only when the user marks `Não perguntar de novo`; otherwise the setting remains `null`.
- [x] `D-DIR-07` Use `ponto de referência` terminology. Do not use `origem padrão` in new/updated visible copy for this flow.
- [x] `D-DIR-08` Persist the route prompt policy in settings, not local/AppData. Local storage may mirror/cache settings but is not the source of truth.
- [x] `D-DIR-09` Render Waze and Uber whenever the destination is launchable; direct launch should use native deep link when available and web fallback when needed. If direct launch fails, show a status message and leave `Outros` as the explicit chooser path.
- [x] `D-DIR-10` Choosing a new `ponto de referência` resets the route prompt policy setting to `null`.
- [x] `D-DIR-11` The prompt question is exactly `Qual PONTO DE PARTIDA quer usar?` with options `Sua localização atual` and `O ponto de referência selecionado`.
- [x] `D-DIR-12` The selected reference point option shows the Account Profile name for Account Profile references and `localização personalizada` for manual references.
- [x] `D-DIR-13` Account Profile reference points expose a shortcut from the prompt to the Account Profile detail.
- [x] `D-DIR-14` Skip the prompt when the canonical/resolved route origin is already the current live location.
- [x] `D-DIR-15` If the user chooses the selected reference point, provider deep links must use the reference point as the route origin/start point.
- [x] `D-DIR-16` Recurring immersive tabs such as `Sobre` and `Como Chegar` are created through shared builders; feature screens decide inclusion but do not duplicate common tab identity.
- [x] `D-DIR-17` Account Profile and Static Asset details may be touched only to consume the shared immersive `Como Chegar` extraction and preserve their own data/route targets.
- [x] `D-DIR-18` Waze/Uber visual identity is resolved through a shared provider brand catalog for direct actions and chooser rows; `Outros` remains a neutral three-dots action.
- [x] `D-DIR-19` Existing chooser providers Google Maps and 99 also use the shared provider brand catalog; the web chooser omits `Abrir no navegador` because it is a duplicate Google Maps route path.

## Closed Questions
- [x] Route prompt settings owner closed by `D-DIR-08`.
- [x] Waze/Uber availability/fallback behavior closed by `D-DIR-09`.
- [x] New reference-point reset semantics closed by `D-DIR-10`.
- [x] Prompt copy/options, Account Profile shortcut, skip condition, and deep-link origin behavior closed by `D-DIR-11..15`.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing `DirectionsAppChooser` URI builders can be reused for direct provider launch with a small contract extension. | The chooser already builds Waze/Uber choices and launch handlers. | Extract provider-specific launch service before UI work. | `High` | `Keep as Assumption` |
| `A-02` | Existing resolved-location services can still supply the live/current coordinate while visible copy changes to `ponto de referência`. | `LocationOriginService` already resolves effective/live coordinates; user corrected terminology on 2026-05-25. | Additional copy and settings model work may be needed. | `High` | `Promoted to Decision` |
| `A-03` | Settings-backed route prompt policy belongs with user proximity/reference settings, not tenant-admin `map_ui`. | The setting is user behavior tied to the selected `ponto de referência`; tenant settings are tenant/admin configuration. | A broader settings namespace decision is required before implementation. | `Medium` | `Keep as Assumption` |
| `A-04` | Provider deep-link builders can support an optional origin/start point without rewriting the whole directions chooser. | Current `DirectionsLaunchTarget` already carries destination data; direct providers can extend the target/launch contract. | Extract a route request model before direct provider work. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/**`
- `flutter-app/lib/domain/proximity_preferences/**`
- `flutter-app/lib/infrastructure/repositories/**`
- `laravel-app/app/Application/ProximityPreferences/**`
- `laravel-app/tests/**`
- `flutter-app/test/**`

### Ordered Steps
1. Add fail-first tests for removed footer, inline buttons, direct launch, chooser launch, exact prompt copy/options, prompt skip condition, Account Profile shortcut, provider origin deep links, settings-backed prompt persistence, and reset-on-new-reference semantics.
2. Extend the user settings/proximity preference contract with the tri-state route prompt policy.
3. Extract/reuse direct provider launch capability from `DirectionsAppChooser`.
4. Implement inline buttons in `LocationSection` for main and secondary cards.
5. Implement route reference-point modal, Account Profile shortcut, prompt skip condition, and persisted tri-state setting.
6. Reset the route prompt setting to `null` whenever a new `ponto de referência` is selected.
7. Verify standard event CTA/footer remains unchanged.
8. Run focused tests and analyzer.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** Event detail widget tests, directions chooser/direct provider service tests, prompt copy/shortcut tests, provider deep-link origin tests, settings/proximity preference repository tests.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Inline provider buttons show and launch | Public visible route flow | `shared-android-web` | widget/navigation | `no` | Flutter widget/service tests |
| Route reference-point modal persists choice | Public preference mutation | `shared-android-web` | widget/repository + Laravel/settings test if backend-backed | `yes` | Flutter widget + repository tests |
| New reference point resets route prompt | Public preference mutation | `shared-android-web` | repository/controller | `yes` | settings/proximity repository test |
| Old footer removed without breaking event CTA | Public Event detail flow | `shared-android-web` | widget/navigation | `no` | Event detail widget test |
| Reference point origin is sent to provider | External handoff flow | `shared-android-web` | service/widget | `no` | provider deep-link origin test |
| Account Profile shortcut appears in prompt | Public navigation | `shared-android-web` | widget/navigation | `no` | prompt widget/navigation test |

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Visible Action | DTO/Decoder Path | Planned Evidence |
| --- | --- | --- | --- | --- |
| Route prompt settings | Flutter Event detail directions + reference point selection | provider launch reference-point policy | settings/proximity preference DTO/repository | repository + widget tests |
| Directions provider launch service | Flutter Event detail directions | Waze/Uber direct and Outros sheet | shared directions widget/service | service + widget tests |
| Reference point source metadata | Flutter Event detail directions prompt | prompt label + Account Profile shortcut + provider origin | proximity preference fixed reference DTO/repository | DTO + widget/service tests |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app` Event detail directions tests | `Como Chegar` footer removal, inline provider buttons, prompt behavior, destination aggregation, and Account Profile shortcut changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --flutter-test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart --flutter-test test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart --flutter-test test/presentation/tenant_public/schedule/routes/immersive_event_detail_route_test.dart` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Event detail widget/controller/route tests passed. |
| `flutter-app` directions chooser and settings/repository tests | Direct Waze/Uber launch, `Outros` chooser path, provider origin links, and route prompt policy persistence changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --flutter-test test/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_test.dart --flutter-test test/infrastructure/repositories/app_data_repository_location_origin_test.dart --flutter-test test/infrastructure/repositories/proximity_preferences_repository_test.dart --flutter-analyze` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Directions chooser, settings/repository tests, and analyzer passed. |
| `laravel-app` settings/proximity support tests | Settings-backed route policy and reference-point reset rely on proximity preference backend behavior. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --laravel-test tests/Feature/Profile/ProfileProximityPreferencesControllerTest.php --laravel-test tests/Feature/Identity/AnonymousIdentityMergerProximityPreferenceTest.php` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Backend preference and identity tests passed. |
| `flutter_rule_matrix` architecture lint | Event detail, directions chooser, repository, and route paths participated in the reconciliation set. | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` through the reconcile wrapper. | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Rule matrix stage passed with recorded lint-code coverage. |
| v0.2.0+8 final Atlas-backed reconciliation matrix | This TODO participates in the approved consolidated v0.2.0+8 package and must stay green after web/runtime lanes. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 full CI-equivalent against Atlas-backed dev runtime" ...` | `Promotion-Lane-Pending` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260528_012558_full_ci_equivalent_atlas_runtime.md` | Passed `atlas_runtime_db_target`, `reconcile_laravel_tests`, `reconcile_flutter_tests`, `reconcile_flutter_analyze`, `flutter_rule_matrix`, `flutter_web_build`, `web_navigation_readonly`, and `web_navigation_mutation` where applicable. |
| `flutter-app` shared immersive tab extraction fast-follow | 2026-05-30 correction centralized recurring immersive tabs and shared `Como Chegar` content across Event, Account Profile, and Static Asset details. | `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/schedule/feature_venue_profile_widgets_test.dart`; `fvm dart analyze --format machine`; `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh`; `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev` | `Local-Validated` | passed | 2026-05-30 local execution: focused Flutter suite exited 0 with `00:24 +99: All tests passed!`; analyzer exited 0 with no machine findings; rule matrix exited 0 with configured lint-code coverage for 57 codes; web build exited 0 after `103.1s` and reported `Flutter web bundle available at: web-app (lane: dev)`. | Focused suite passed 99 tests; analyzer exited 0; rule matrix reported configured lint coverage; web build refreshed `web-app` with `__LANDLORD_HOST__=belluga.space` and `__WEB_BUILD_SHA__=969f0825`. |
| `flutter-app` directions provider brand catalog and compact browser visual QA | 2026-05-30 user correction requested Waze/Uber visible route buttons use each company's brand, then requested the `Outros` modal be more compact and branded for Google Maps/99 too. | `fvm flutter test test/presentation/shared/widgets/directions_app_chooser/directions_provider_actions_test.dart test/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/schedule/feature_venue_profile_widgets_test.dart`; `fvm dart analyze --format machine`; `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`; `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev`; source-owned Playwright web browser spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND bash tools/flutter/run_web_navigation_smoke.sh readonly`; executed with runner config `tools/flutter/web_app_smoke_runner/playwright.config.js` and Chrome executable override on WSL. | `Local-Validated` | passed | 2026-05-30 local execution: focused Flutter suite exited 0 with `00:51 +107: All tests passed!`; targeted brand tests exited 0 with `00:01 +8: All tests passed!`; analyzer exited 0 with no machine findings; rule matrix exited 0 with configured lint-code coverage for 57 codes; final web build exited 0 after `91.9s`; source-owned Playwright runner selected 1 test and passed with `1 passed (35.3s)`. | Browser pass verified Google Maps SVG, Waze PNG, Uber SVG, and 99 PNG asset responses as HTTP 200; screenshots captured `/tmp/belluga-directions-brand-compact-final-color-wait/account-mobile-directions.png`, `account-desktop-directions.png`, `event-mobile-directions.png`, and `account-mobile-other-sheet.png`; chooser sheet rendered compact branded Google Maps/Waze/Uber/99 rows with no `Abrir no navegador` duplicate on `https://guarappari.belluga.space`. |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, Event/Agenda/Flutter module anchors, source inventory snapshot, frozen decisions `D-DIR-01..15`, frontend/consumer matrix, flow evidence matrix, and local CI-equivalent suite matrix.
- **Orchestrator-owned checks:** verify direct Waze/Uber actions never open the chooser, `Outros` always does, prompt copy/options are exact, Account Profile shortcut exists for Account Profile references, prompt is skipped when current location is already the canonical origin, route prompt persistence is settings-backed, reference-point choice populates provider origin/start point, and choosing a new `ponto de referência` resets the setting to `null`.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `DIR` inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; execution depends on `REF-CORE` and must keep route prompt persistence settings-backed rather than local/AppData-only.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This TODO is now approved for tactical implementation after dependency gates. | Approved scope, exact modal copy, DoD, and validation. | Changing provider hierarchy or settings owner without renewed approval. | Worker must preserve frozen decisions. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO shares a wave with reference-point actions. | Worker-owned implementation and orchestrator-owned reconciliation. | Conflicting reset or preference models between workers. | Orchestrator merges only one shared policy path. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches Event detail UI, shared directions widgets, services, and repositories. | Controller/service ownership and analyzer-clean state. | Widget-local persistence or ad hoc navigation. | Worker must keep state in repository/settings contracts. |
| `delphi-ai/rules/stacks/flutter/flutter-route-workflow-glob.md` | The prompt may navigate to Account Profile detail. | Canonical route/navigation discipline. | Raw Navigator shortcuts that bypass route policy. | Worker must use approved route/navigation patterns. |
| `delphi-ai/rules/stacks/laravel/shared/settings-kernel-patch-contract-model-decision.md` | Backend settings/proximity persistence may be touched. | User preference ownership outside tenant-admin `map_ui`. | Treating this as tenant configuration. | Worker must test read/write/reset if backend is touched. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | Direct provider launch and tri-state prompt behavior need focused tests. | Exact-copy and semantic service tests. | Status-only UI tests. | Worker creates widget, service, and repository tests. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-DIR-01..15` | Event detail uses Waze/Uber direct actions plus `Outros`, settings-backed tri-state route prompt policy, exact modal copy/options, Account Profile shortcut, skip-current-origin behavior, and reference-point origin deep links. | passed | No tenant-admin `map_ui`, local/AppData-only preference, new provider brand, or venue data model change was introduced. |
| `D-DIR-16..17` | `ImmersiveCommonTabs` centralizes common tab identity and `ImmersiveDirectionsSection` centralizes `Como Chegar` content across Event, Account Profile, and Static Asset details. | passed | Account Profile/Static Asset changes are limited to consuming shared tab/directions builders and preserving caller-owned data/targets. |
| `D-DIR-18` | `DirectionsProviderBrandCatalog` centralizes Waze/Uber asset paths, colors, source URLs, and logo sizing; direct actions and chooser rows consume the catalog. | passed | `Outros` remains a neutral three-dots action and no additional provider was introduced. |
| `D-DIR-19` | `DirectionsProviderBrandCatalog` also centralizes existing Google Maps and 99 chooser brands; the web chooser now returns Google Maps/Waze/Uber/99 without the duplicate `Abrir no navegador` fallback row. | passed | The compact sheet hides repeated row subtitles and keeps native fallback behavior available outside the web duplicate path. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Route prompt policy and external provider launch | User-owned settings persistence, safe reference metadata usage, and external-app handoff scope. | passed | `ProfileProximityPreferencesControllerTest.php`; `proximity_preferences_repository_test.dart`; `directions_app_chooser_test.dart`; Event detail widget tests. | External launch URLs use selected origin/destination data and do not mutate tenant-admin configuration. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Event detail `Como Chegar` route actions | Avoid chooser construction on Waze/Uber direct path and avoid repeated prompt persistence writes. | passed | `immersive_event_detail_screen_test.dart`; `directions_app_chooser_test.dart`; analyzer. | Direct provider path resolves origin policy before launch; `Outros` remains the only chooser path. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Event directions local reconciliation | CI/Copilot failure modes: old footer regression, provider hierarchy mismatch, prompt copy drift, settings persistence regression, reference origin omission, analyzer failures. | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | no p1 or p2 findings | Consolidated wrapper finished `promotion-ready`; remote PR/Copilot checks remain part of later promotion lane execution. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter Event/detail routing architecture | Scoped v0.2.0+8 Flutter scan for widget-owned persistence, imperative navigation, build-side effects, and route policy bypasses. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-flutter.json` | no p1 or p2 findings | Three review-level Navigator findings are local modal close affordances covered by widget tests, not raw route transitions or delivery blockers. |
| Laravel settings/proximity guardrails | Scoped v0.2.0+8 Laravel scan for tenant guard bypasses, fixture domains, and validation shortcuts. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-laravel.json` | no p1 or p2 findings | Review-level findings were tenant/domain test fixtures rather than deployable host constants or guard bypasses. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Remove the `Como Chegar` tab-specific footer CTA `Traçar rota`. | Flutter route widget/navigation test | `immersive_event_detail_screen_test.dart`; consolidated wrapper report | Flutter Event detail route widget/navigation test | passed | `Como Chegar` no longer renders the tab-specific `Traçar rota` footer CTA. |
| SCOPE-02 | Scope | Preserve the standard event CTA/footer behavior used by other tabs. | Flutter widget test | `immersive_event_detail_screen_test.dart`; `immersive_event_detail_route_test.dart` | Flutter Event detail widget/navigation test | passed | Standard Event CTA/footer remains available. |
| SCOPE-03 | Scope | Render inline provider actions under the main location card: `Waze`, `Uber`, and `Outros`. | Flutter widget test | `immersive_event_detail_screen_test.dart` | Flutter Event detail widget test | passed | Main card renders the three inline route actions. |
| SCOPE-04 | Scope | Render inline provider actions under each secondary location card, with compact sizing. | Flutter widget test | `immersive_event_detail_screen_test.dart` | Flutter Event detail widget test | passed | Secondary location cards render compact Waze/Uber/Outros actions. |
| SCOPE-05 | Scope | Make main-card provider actions visually larger than secondary-card actions. | Flutter widget test | `immersive_event_detail_screen_test.dart` | Flutter Event detail widget test | passed | Main actions use the larger action presentation. |
| SCOPE-06 | Scope | Route `Waze` and `Uber` buttons directly to their provider launch path without opening the chooser sheet. | route widget/service tests | `immersive_event_detail_screen_test.dart`; `directions_app_chooser_test.dart` | Flutter route widget/service tests | passed | Direct provider route taps skip the chooser. |
| SCOPE-07 | Scope | Route `Outros` through the existing directions bottom sheet/provider chooser. | route widget test | `immersive_event_detail_screen_test.dart`; `directions_app_chooser_test.dart` | Flutter route widget/service tests | passed | `Outros` opens the route chooser path. |
| SCOPE-08 | Scope | Add a route reference-point policy modal when the persisted setting is `null`. | route widget/repository tests | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart` | Flutter route widget/repository tests | passed | Null route policy triggers the prompt. |
| SCOPE-09 | Scope | Modal title/question is exactly `Qual PONTO DE PARTIDA quer usar?`. | exact-copy widget test | `immersive_event_detail_screen_test.dart` | Flutter widget test | passed | Prompt title matches the frozen string. |
| SCOPE-10 | Scope | Modal options are `Sua localização atual` and `O ponto de referência selecionado`. | exact-copy widget test | `immersive_event_detail_screen_test.dart` | Flutter widget test | passed | Both modal options match the frozen labels. |
| SCOPE-11 | Scope | Modal displays the selected reference point label: Account Profile name for Account Profile references, or `localização personalizada` for manual references. | widget/repository tests | `immersive_event_detail_screen_test.dart`; `proximity_preferences_repository_test.dart` | Flutter widget/repository tests | passed | Account Profile and manual labels are represented for prompt consumers. |
| SCOPE-12 | Scope | Modal includes an Account Profile shortcut when the selected reference point is an Account Profile. | widget/navigation test | `immersive_event_detail_screen_test.dart`; `account_profile_detail_controller_test.dart` | Flutter widget/navigation test | passed | Account Profile-backed reference points expose a detail shortcut. |
| SCOPE-13 | Scope | Modal is skipped when the canonical/resolved route origin is the current live location. | route widget/service test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter route widget/service test | passed | Current-live-location route origin skips the prompt. |
| SCOPE-14 | Scope | Choosing the selected reference point makes the external app deep link use the reference point as the route origin. | route directions service/widget test | `directions_app_chooser_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter route service/widget test | passed | Provider route launch target includes the reference point origin. |
| SCOPE-15 | Scope | Modal supports `Não perguntar de novo`. | widget/repository test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter widget/repository test | passed | Persist checkbox behavior is covered. |
| SCOPE-16 | Scope | Add/update the address/reference-point selection area with a nullable setting for "use selected reference point when tracing routes". | repository/settings tests | `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart` | Flutter repository/settings tests | passed | Route policy supports `true`, `false`, and `null`. |
| SCOPE-17 | Scope | Persist the route reference-point prompt preference in settings so `true`/`false` stops future prompts and `null` keeps prompting. | route policy repository and widget tests | `app_data_repository_location_origin_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter route repository/widget tests | passed | Persisted route policy true/false suppresses future prompts; null keeps prompting. |
| SCOPE-18 | Scope | Reset the route reference-point prompt setting to `null` whenever a new `ponto de referência` is selected. | route policy repository reset tests | `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart` | Flutter route repository tests | passed | New reference selection clears the route policy. |
| SCOPE-19 | Scope | Reuse existing `DirectionsAppChooser`/launch URI logic where possible; extend it only where direct provider launch requires a contract. | directions chooser tests | `directions_app_chooser_test.dart` | Flutter service/widget test | passed | Shared chooser/launch logic remains the provider path, extended for direct launch. |
| SCOPE-20 | Scope | Centralize recurring immersive `Sobre` and `Como Chegar` tab creation so screens select common tabs instead of duplicating tab identity. | Flutter widget tests plus source implementation | `ImmersiveCommonTabs`; `immersive_event_detail_screen_test.dart`; `account_profile_detail_screen_test.dart`; `static_asset_detail_screen_test.dart`; 2026-05-30 focused Flutter suite `00:24 +99: All tests passed!` | Flutter Event + Account Profile + Static Asset detail widget tests | passed | Event, Account Profile, and Static Asset now create common `Sobre` and `Como Chegar` tabs through `ImmersiveCommonTabs`. |
| SCOPE-21 | Scope | Extract shared `Como Chegar` content for Event, Account Profile, and Static Asset immersive detail screens while preserving each screen's own data, map target, and route handlers. | Flutter widget tests plus source implementation | `ImmersiveDirectionsSection`; `LocationSection`; `account_profile_detail_screen_test.dart`; `static_asset_detail_screen_test.dart`; `immersive_event_detail_screen_test.dart`; 2026-05-30 focused Flutter suite `00:24 +99: All tests passed!` | Flutter Event + Account Profile + Static Asset detail widget tests | passed | Shared directions content receives caller-supplied map canvas, destination target, and route handlers for each screen. |
| SCOPE-22 | Scope | Render Waze/Uber direct actions and chooser rows through a shared provider brand catalog using Waze/Uber brand assets. | Flutter widget tests, web asset build, browser visual QA | `directions_provider_actions_test.dart`; `directions_app_chooser_test.dart`; `DirectionsProviderBrandCatalog`; `assets/brands/directions/*`; `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev`; source-owned web browser spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND bash tools/flutter/run_web_navigation_smoke.sh readonly`; screenshots `/tmp/belluga-directions-brand-visual-final-runner/*.png` | Flutter shared directions widgets + web browser runtime `https://guarappari.belluga.space` serving refreshed `web-app` bundle | passed | Direct buttons and chooser rows render Waze/Uber brand assets; browser runtime fetched Waze PNG and Uber SVG with HTTP 200; source-owned Playwright runner passed `1 passed (37.7s)`. |
| SCOPE-23 | Scope | Render a compact `Outros` chooser sheet with Google Maps, Waze, Uber, and 99 branded rows, no repeated row subtitles, and no duplicated web fallback row. | Flutter widget tests, web asset build, browser visual QA | `directions_app_chooser_test.dart`; `DirectionsProviderBrandCatalog`; `assets/brands/directions/google_maps_icon_2020.svg`; `assets/brands/directions/99_logo_2023.png`; `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev`; source-owned web browser spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND bash tools/flutter/run_web_navigation_smoke.sh readonly`; screenshots `/tmp/belluga-directions-brand-compact-final-color-wait/*.png` | Flutter shared directions widgets + web browser runtime `https://guarappari.belluga.space` serving refreshed `web-app` bundle | passed | Compact sheet renders Google Maps/Waze/Uber/99 branded rows without per-row subtitles; web chooser omits `Abrir no navegador`; Playwright runner passed `1 passed (35.3s)`. |
| DOD-01 | Definition of Done | `Como Chegar` no longer shows the tab-specific `Traçar rota` footer. | Flutter route widget/navigation test | `immersive_event_detail_screen_test.dart` | Flutter Event detail route widget/navigation test | passed | Old `Traçar rota` footer CTA is absent. |
| DOD-02 | Definition of Done | The event screen still shows the standard event CTA/footer correctly on all tabs. | Flutter widget/navigation test | `immersive_event_detail_screen_test.dart`; `immersive_event_detail_route_test.dart` | Flutter Event detail widget/navigation test | passed | Standard footer remains intact. |
| DOD-03 | Definition of Done | Main address card shows larger `Waze`, `Uber`, and `Outros` buttons. | Flutter widget/navigation test | `immersive_event_detail_screen_test.dart` | Flutter widget/navigation test | passed | Main card buttons use the larger presentation in the route actions flow. |
| DOD-04 | Definition of Done | Secondary address cards show compact `Waze`, `Uber`, and `Outros` buttons. | Flutter widget/navigation test | `immersive_event_detail_screen_test.dart` | Flutter widget/navigation test | passed | Secondary cards use compact route actions. |
| DOD-05 | Definition of Done | `Waze` launches Waze directly when available/launchable after origin policy resolution. | route directions widget/service test | `directions_app_chooser_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter route widget/service test | passed | Waze direct route path resolves origin policy before launch. |
| DOD-06 | Definition of Done | `Uber` launches Uber directly when available/launchable after origin policy resolution. | route directions widget/service test | `directions_app_chooser_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter route widget/service test | passed | Uber direct route path resolves origin policy before launch. |
| DOD-07 | Definition of Done | `Outros` opens the chooser bottom sheet. | Flutter widget/navigation test | `immersive_event_detail_screen_test.dart`; `directions_app_chooser_test.dart` | Flutter widget/navigation service test | passed | `Outros` remains the route chooser entry point. |
| DOD-08 | Definition of Done | When route reference-point setting is `null`, launching a provider prompts for `ponto de referência` vs current location. | route widget/repository test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter route widget/repository test | passed | Null route policy shows the modal. |
| DOD-09 | Definition of Done | The prompt copy and options match the frozen modal contract. | exact-copy widget/navigation test | `immersive_event_detail_screen_test.dart` | Flutter widget/navigation test | passed | Title and option copy match the frozen modal route contract. |
| DOD-10 | Definition of Done | The prompt is not shown when the canonical/resolved route origin is already the current live location. | route widget/service test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter route widget/service test | passed | Current route origin bypasses prompt. |
| DOD-11 | Definition of Done | Account Profile references show a shortcut from the prompt to that Account Profile. | widget/navigation test | `immersive_event_detail_screen_test.dart` | Flutter widget/navigation test | passed | Account Profile shortcut is visible for Account Profile-backed references. |
| DOD-12 | Definition of Done | Choosing the reference point sends the reference point as the origin/start point in Waze/Uber/other provider links. | directions service/widget test | `directions_app_chooser_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter service/widget test | passed | Provider links receive reference point origin/start coordinates. |
| DOD-13 | Definition of Done | If `Não perguntar de novo` is checked, the selected route policy is saved in settings. | route widget navigation test plus repository save mutation test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter route widget navigation test plus repository save mutation test | passed | Checked selection saves the selected route policy as `true` or `false`. |
| DOD-14 | Definition of Done | If `Não perguntar de novo` is not checked, the setting remains `null` and future launches keep prompting. | widget/repository test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter widget/repository test | passed | Unchecked selection leaves policy null. |
| DOD-15 | Definition of Done | When the setting is `true` or `false`, no prompt is shown and the saved policy is applied. | widget/repository test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter widget/repository test | passed | Persisted policy applies without modal. |
| DOD-16 | Definition of Done | Choosing a new `ponto de referência` resets the route prompt setting to `null`. | route policy repository reset test | `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart` | Flutter route repository tests | passed | Reference update clears the route policy. |
| DOD-17 | Definition of Done | Tests cover direct provider launches, chooser launch, prompt/persistence semantics, and absence of the old footer. | focused test suites | Local CI-Equivalent Suite Matrix rows above | Cross-stack focused tests | passed | Direct launch, chooser, modal persistence, reset, and footer absence are covered. |
| DOD-18 | Definition of Done | Event, Account Profile, and Static Asset immersive detail screens consume shared `Sobre`/`Como Chegar` tab builders where those tabs are rendered. | Flutter widget and navigation test evidence plus source implementation | `ImmersiveCommonTabs`; `immersive_event_detail_screen_test.dart`; `account_profile_detail_screen_test.dart`; `static_asset_detail_screen_test.dart`; 2026-05-30 focused Flutter suite `00:24 +99: All tests passed!` | Flutter Event + Account Profile + Static Asset detail widget/navigation tests | passed | Common tab identity is centralized; screens still decide inclusion from their own capability/data. |
| DOD-19 | Definition of Done | Event, Account Profile, and Static Asset `Como Chegar` content consumes the shared directions section with inline provider actions. | Flutter widget and navigation test evidence plus source implementation | `ImmersiveDirectionsSection`; `directions_provider_actions.dart`; `immersive_event_detail_screen_test.dart`; `account_profile_detail_screen_test.dart`; `static_asset_detail_screen_test.dart`; 2026-05-30 focused Flutter suite `00:24 +99: All tests passed!` | Flutter Event + Account Profile + Static Asset detail widget/navigation tests | passed | Shared `Como Chegar` content renders Waze, Uber, and `Outros` inline while preserving per-screen route targets. |
| DOD-20 | Definition of Done | Waze/Uber visible provider actions and chooser rows render brand assets from a shared catalog, with `Outros` remaining neutral. | Flutter widget tests plus browser visual QA | `directions_provider_actions_test.dart`; `directions_app_chooser_test.dart`; source-owned Playwright spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND bash tools/flutter/run_web_navigation_smoke.sh readonly`; final Playwright/Chrome web browser screenshots in `/tmp/belluga-directions-brand-visual-final-runner/` | Flutter shared directions widgets + web browser runtime `https://guarappari.belluga.space` | passed | Account Profile mobile/desktop, Event mobile, and `Outros` sheet were rendered and reviewed; WSL execution used `tools/flutter/web_app_smoke_runner/playwright.config.js` with `PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH` to target installed Chrome and passed `1 passed (37.7s)`. |
| DOD-21 | Definition of Done | The chooser sheet is visually compact and uses branded Google Maps/Waze/Uber/99 rows; web does not show `Abrir no navegador` as a duplicate Google Maps option. | Flutter widget tests plus browser visual QA | `directions_app_chooser_test.dart`; `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev`; source-owned Playwright spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND bash tools/flutter/run_web_navigation_smoke.sh readonly`; screenshots in `/tmp/belluga-directions-brand-compact-final-color-wait/` | Flutter shared directions widgets + web browser runtime `https://guarappari.belluga.space` serving refreshed `web-app` bundle | passed | Modal rows are denser, per-row subtitles are hidden, Google Maps/99 assets load as HTTP 200, and the rendered sheet shows four branded rows only after web build `91.9s`. |
| VAL-01 | Validation Steps | Flutter widget test for Event detail `Como Chegar` tab without old footer CTA. | Flutter widget test | `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` through the reconcile wrapper | Flutter Event detail widget/navigation test | passed | Old footer CTA absence is covered. |
| VAL-02 | Validation Steps | Flutter widget/controller test for main and secondary inline provider buttons. | Flutter widget/controller test | `immersive_event_detail_screen_test.dart`; `immersive_event_detail_controller_test.dart` | Flutter widget/controller test | passed | Main and secondary provider actions are covered. |
| VAL-03 | Validation Steps | Flutter unit/widget test for route reference-point prompt when setting is `null`. | Flutter route widget/repository test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter route widget/repository test | passed | Null route setting prompt behavior is covered. |
| VAL-04 | Validation Steps | Flutter widget test for exact modal copy/options and Account Profile shortcut visibility. | Flutter widget/navigation test | `immersive_event_detail_screen_test.dart` | Flutter widget/navigation test | passed | Exact copy/options and Account Profile shortcut are covered. |
| VAL-05 | Validation Steps | Flutter test proving no modal appears when canonical/resolved route origin is current live location. | Flutter route widget/service navigation test | `immersive_event_detail_screen_test.dart`; `app_data_repository_location_origin_test.dart` | Flutter route widget/service navigation test | passed | Current route origin skip condition is covered. |
| VAL-06 | Validation Steps | Flutter/service test proving reference-point choice populates provider deep link origin/start point. | Flutter service/widget test | `directions_app_chooser_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter service/widget test | passed | Provider origin/start point is covered. |
| VAL-07 | Validation Steps | Flutter repository/service test for persisted settings-backed route prompt preference true false null. | Flutter route repository save/read test plus widget navigation test | `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter route repository/service save mutation test plus widget navigation test | passed | Tri-state settings-backed route policy is covered. |
| VAL-08 | Validation Steps | Laravel/settings or repository test for read/write/reset semantics if settings persistence is backend-backed in the chosen implementation. | Laravel/Flutter persistence mutation tests plus widget navigation test | `ProfileProximityPreferencesControllerTest.php`; `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart`; `immersive_event_detail_screen_test.dart` | Laravel API plus Flutter repository mutation and widget navigation test | passed | Backend-backed preference path and Flutter repository reset semantics are covered. |
| VAL-09 | Validation Steps | Flutter test proving direct Waze/Uber launch paths do not open the chooser sheet. | Flutter route widget/service navigation test | `directions_app_chooser_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter route widget/service navigation test | passed | Direct provider route launches bypass chooser. |
| VAL-10 | Validation Steps | Flutter widget tests proving Account Profile and Static Asset consume inline shared `Como Chegar` actions and do not retain the old `Traçar rota` footer. | Flutter widget tests | `account_profile_detail_screen_test.dart`; `static_asset_detail_screen_test.dart` | Flutter Account Profile + Static Asset detail widget tests | passed | Account Profile and Static Asset route UI now consumes the shared inline action surface. |
| VAL-11 | Validation Steps | Analyzer/local CI-equivalent suite row completed before delivery. | local CI-equivalent | Local CI-Equivalent Suite Matrix rows above; 2026-05-30 focused extraction commands; `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev` | Cross-stack test/analyzer/build wrapper plus focused Flutter tests | passed | Consolidated wrapper passed earlier; 2026-05-30 focused suite passed 99 tests, analyzer exited 0, rule matrix passed, and web build passed in 103.1s with `__WEB_BUILD_SHA__=969f0825`. |
| VAL-12 | Validation Steps | Browser-rendered visual QA for Account Profile mobile/desktop `Como Chegar`, Event mobile `Como Chegar`, and `Outros` chooser sheet after web build. | Playwright/Chrome browser render pass | Source-owned spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND NAV_TENANT_URL=https://guarappari.belluga.space NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh readonly`; executed through `tools/flutter/web_app_smoke_runner/playwright.config.js`; screenshots `/tmp/belluga-directions-brand-visual-final-runner/account-mobile-directions.png`, `/tmp/belluga-directions-brand-visual-final-runner/account-desktop-directions.png`, `/tmp/belluga-directions-brand-visual-final-runner/event-mobile-directions.png`, `/tmp/belluga-directions-brand-visual-final-runner/account-mobile-other-sheet.png`; build proof `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev` with `__WEB_BUILD_SHA__=969f0825` | web browser runtime `https://guarappari.belluga.space` tenant lane `dev` serving final `web-app` bundle | passed | Visual pass confirmed branded Waze/Uber controls, neutral `Outros`, no old `Traçar rota` footer in `Como Chegar`, and brand assets loading as HTTP 200; source-owned Playwright runner selected 1 test and passed with `1 passed (37.7s)` using installed Chrome. |
| VAL-13 | Validation Steps | Browser-rendered visual QA confirms the compact branded chooser sheet after adding Google Maps/99 assets and removing the duplicate browser fallback row. | Playwright/Chrome browser render pass | Source-owned spec `tools/flutter/web_app_tests/directions_brand_visual.spec.js`; project runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=NAV-DIR-BRAND NAV_TENANT_URL=https://guarappari.belluga.space NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh readonly`; screenshots `/tmp/belluga-directions-brand-compact-final-color-wait/account-mobile-other-sheet.png`; build proof `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev` | web browser runtime `https://guarappari.belluga.space` tenant lane `dev` serving final `web-app` bundle | passed | Visual QA confirmed compact modal, colored Google Maps logo, Waze/Uber/99 branded rows, and no `Abrir no navegador` duplicate; Playwright runner passed `1 passed (35.3s)`. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** local implementation and validation are complete, and the current package-wide mimic loop kept this TODO clean with no reopened findings; only authorized lane follow-through remains.
- **Post-commit/push status:** `ready-for-promotion-lane`
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and carry it through the current v0.2.0+8 package promotion.
