# TODO: Flutter Architecture Deviations Audit + Permission-Gated Tests

## Goal
1) Remove runtime permission dialogs during integration/device tests so runs are autonomous.
2) Audit and remediate all Flutter architecture deviations (screens/controllers/repositories/state management) until a clean recheck.

## Scope
- Flutter app only (`flutter-app/`).
- Device/integration test workflow to ensure permissions are pre-granted.
- Architecture compliance across screens, controllers, repositories, domain, routing, and state management.

## Out of Scope
- New features beyond architecture adherence.
- Backend changes unless required by documented contract updates.

## Definition of Done
- Integration tests run without permission prompts.
- Architecture deviation recheck returns **zero** findings.
- Post-change tests pass.
- Foundation documentation updated **before** Flutter code changes (screens/modules/roadmap/prototype data as applicable).
- Hard NO adherence is fully clean (no DTOs in domain, no GetIt in widgets/screens, no Future/StreamBuilder, no direct Navigator usage in UI, no multi-widget files in presentation).

## Validation Steps
- Run at least one integration test on device after pre-granting permissions.
- Recheck for deviations after each remediation pass.
- Run `fvm flutter analyze` and relevant tests after significant changes.
- Verify documentation sync (screens/modules/roadmap/prototype data) matches the code changes.
- Re-run adherence scans:
  - `rg -n "\\bsetState\\b" flutter-app/lib/presentation`
  - `rg -n "FutureBuilder|StreamBuilder" flutter-app/lib/presentation`
  - `rg -n "GetIt\\.I|getIt\\." flutter-app/lib/presentation | rg -v "/controllers/"`
  - `rg -n "Navigator\\." flutter-app/lib/presentation`
  - `rg -n "class .*extends (StatelessWidget|StatefulWidget)" flutter-app/lib/presentation | awk -F: '{count[$1]++} END {for (f in count) if (count[f]>1) print count[f] \" \" f}' | sort -nr`
  - `rg -n "Dto|DTO" flutter-app/lib/domain`

## Current Findings (Recheck 2026-02-02)
### setState usage (soft-no unless truly ephemeral)
- `flutter-app/lib/presentation/prototypes/map_debug/map_debug_screen.dart`
- `flutter-app/lib/presentation/common/widgets/swipeable_card/swipeable_card.dart`
- `flutter-app/lib/presentation/tenant/schedule/widgets/agenda_app_bar.dart` (local builder setState)
- `flutter-app/lib/presentation/tenant/widgets/animated_search_button.dart`
- `flutter-app/lib/presentation/tenant/widgets/carousel_card.dart`

### StreamValue in widgets / passed into widgets (hard-no)
- `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/widgets/home_app_bar.dart`
- `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/widgets/home_my_events_carousel.dart`
- `flutter-app/lib/presentation/tenant/widgets/carousel_section.dart`
- `flutter-app/lib/presentation/tenant/discovery/widgets/discovery_filter_chips.dart`
- `flutter-app/lib/presentation/common/widgets/button_loading.dart`
- `flutter-app/lib/presentation/common/widgets/image_palette_theme.dart`
- `flutter-app/lib/presentation/common/location_permission/screens/location_not_live_screen/location_not_live_screen.dart`

### UI controllers / form keys owned by widgets (hard-no)
- `flutter-app/lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart` (GlobalKey<FormState>)
- `flutter-app/lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_edit_screen.dart` (GlobalKey<FormState>)
- `flutter-app/lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_create_screen.dart` (GlobalKey<FormState>)
- `flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart` (GlobalKey<FormState>)
- `flutter-app/lib/presentation/common/auth/screens/auth_login_screen/widgets/auth_login_canva_content.dart` (TextEditingController)
- `flutter-app/lib/presentation/landlord/auth/widgets/landlord_login_sheet.dart` (TextEditingController)
- `flutter-app/lib/presentation/tenant/widgets/date_grouped_event_list.dart` (ScrollController param)
- `flutter-app/lib/presentation/tenant/profile/screens/profile_screen/profile_screen.dart` (local TextEditingController usage)

### Direct navigation in presentation (hard-no)
- `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen.dart` (`SystemNavigator.pop`)

### FutureBuilder/StreamBuilder (hard-no)
- None (clean)

### Multi-widget files (hard-no; split to 1 widget per file)
- None (clean)

## Tasks
- [x] ✅ Production‑Ready — Confirm device test runner grants runtime permissions (location + notifications) **before each test run**.
- [x] ✅ Production‑Ready — Run a single integration test to validate no permission dialogs block execution; capture result in checklist.
- [x] ✅ Production‑Ready — Load relevant Flutter rules/workflows and document the current architecture standards (screens, controllers, repositories, domain, routing, state).
- [x] ✅ Production‑Ready — Update foundation documentation **before code changes** (screens/modules/roadmap/prototype data as impacted).
- [x] ✅ Production‑Ready — Audit current branch changes for deviations; fix and recheck.
- [x] ✅ Production‑Ready — Expand audit to entire Flutter app; fix and recheck until **no deviations remain**.
- [x] ✅ Production‑Ready — Resolve remaining controller-level deviations (infrastructure/DAL/DTO usage):
  - `flutter-app/lib/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller.dart` (PoiQuery/PoiRepository)
  - `flutter-app/lib/presentation/tenant/map/screens/city_map_screen/controllers/city_map_controller.dart` (PoiQuery/AppDataRepository)
  - `flutter-app/lib/presentation/tenant/partners/controllers/partner_detail_controller.dart` (DTOs + mock DAL)
  - `flutter-app/lib/presentation/tenant/schedule/screens/event_detail_screen/controllers/event_detail_controller.dart` (UserEventsRepository)
  - `flutter-app/lib/presentation/tenant/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart` (UserEventsRepository)
  - `flutter-app/lib/presentation/tenant/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart` (AppDataRepository)
  - `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller.dart` (AppDataRepository)
  - `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart` (AppDataRepository)
  - `flutter-app/lib/presentation/tenant/menu/screens/menu_screen/controllers/menu_screen_controller.dart` (AppDataRepository)
  - `flutter-app/lib/presentation/common/init/screens/init_screen/controllers/init_screen_controller.dart` (AppDataRepository/PushPresentationGate)
  - `flutter-app/lib/presentation/tenant/profile/screens/profile_screen/controllers/profile_screen_controller.dart` (AuthRepository/AppDataRepository)
- [x] ✅ Production‑Ready — Integration test failures reported on device (red screen). Re-run & fix until green:
  - `integration_test/feature_shell_navigation_smoke_test.dart` (GetIt registration / profile navigation)
  - Re-run full checklist in `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md` after fixes.
- [x] ✅ Production‑Ready — Run tests after significant changes; re-run if regressions appear.
- [x] ✅ Production‑Ready — Remove **non‑ephemeral** `setState` usage in presentation (controller‑driven where required).
  - Remaining `setState` occurrences are UI‑only/ephemeral per heuristics:
    - `flutter-app/lib/presentation/common/widgets/swipeable_card/swipeable_card.dart`
    - `flutter-app/lib/presentation/tenant/widgets/animated_search_button.dart`
    - `flutter-app/lib/presentation/tenant/widgets/carousel_card.dart`
    - `flutter-app/lib/presentation/prototypes/map_debug/map_debug_screen.dart`
    - `flutter-app/lib/presentation/tenant/schedule/widgets/agenda_app_bar.dart` (local modal slider)
- [x] ✅ Production‑Ready — Screens must not dispose controllers (ModuleScope/GetIt owns lifecycle):
  - Audit `flutter-app/lib/presentation/**/screens/**` for `controller.dispose()` or similar.
  - Move disposal to controller `onDispose` and ensure controller implements `Disposable`.
- [x] ✅ Production‑Ready — Remove **StreamValue** usage from widgets (StreamValue must live in controllers only):
  - `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/widgets/home_app_bar.dart`
  - `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/widgets/home_my_events_carousel.dart`
  - `flutter-app/lib/presentation/tenant/widgets/carousel_section.dart`
  - `flutter-app/lib/presentation/tenant/discovery/widgets/discovery_filter_chips.dart`
  - `flutter-app/lib/presentation/common/widgets/button_loading.dart`
  - `flutter-app/lib/presentation/common/widgets/image_palette_theme.dart`
  - `flutter-app/lib/presentation/common/location_permission/screens/location_not_live_screen/location_not_live_screen.dart`
- [x] ✅ Production‑Ready — Move UI controllers / form keys into controllers (no UI-owned GlobalKey/TextEditingController):
  - `flutter-app/lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`
  - `flutter-app/lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_edit_screen.dart`
  - `flutter-app/lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_create_screen.dart`
  - `flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart`
  - `flutter-app/lib/presentation/common/auth/screens/auth_login_screen/widgets/auth_login_canva_content.dart`
  - `flutter-app/lib/presentation/landlord/auth/widgets/landlord_login_sheet.dart`
  - `flutter-app/lib/presentation/tenant/widgets/date_grouped_event_list.dart`
  - `flutter-app/lib/presentation/tenant/profile/screens/profile_screen/profile_screen.dart`
- [x] ✅ Production‑Ready — Remove direct SystemNavigator usage in presentation (router/flow-driven exit):
  - `flutter-app/lib/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen.dart`
- [x] ✅ Production‑Ready — Remove `FutureBuilder`/`StreamBuilder` from presentation (use StreamValueBuilder + controller state):
  - None remaining after recheck
- [x] ✅ Production‑Ready — Remove direct `Navigator.*` usage from presentation (router/controller-driven):
  - All files flagged by `rg -n "Navigator\\." flutter-app/lib/presentation`.
- [x] ✅ Production‑Ready — Split files with multiple widget classes (1 widget per file rule):
  - Multi-widget scan clean (no files reported).
- [x] ✅ Production‑Ready — Hard‑NO cleanup sweep (must reach **zero**):
  - Repository/domain/DAO access inside screens/widgets.
  - Any state manager other than StreamValue (Provider/Bloc/GetX/ChangeNotifier/ValueNotifier/etc.).
  - Business logic in screens (filters, mapping, validation, formatting).
  - Direct GetIt access in widgets (controllers only).
  - Network calls or side effects inside UI (no async work in widgets).
  - DTOs used directly in UI/controllers (must be domain/projection).
  - UI deciding what to fetch (controller/route owns).
- [x] ✅ Production‑Ready — Remove DTO usage from **domain** layer (DTOs only in infrastructure):
  - All files flagged by `rg -n "Dto|DTO" flutter-app/lib/domain` are clean; mapping is centralized in infrastructure DTO mappers.

## Acceptance Criteria
- Integration tests run without manual permission prompts.
- Architecture recheck reports **zero deviations**.
- Tests pass after changes.
