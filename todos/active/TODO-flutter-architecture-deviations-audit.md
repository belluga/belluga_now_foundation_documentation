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
  - Re-run full checklist in `.agent/test-run-progress.md` after fixes.
- [x] ✅ Production‑Ready — Run tests after significant changes; re-run if regressions appear.
- [ ] ⚪ Pending — Remove `setState` usage in presentation (replace with controller `StreamValue`):
  - `flutter-app/lib/presentation/common/push/push_option_selector_sheet.dart`
  - Audit for other `setState` in `flutter-app/lib/presentation/**` and eliminate non-UI-only usage.
- [ ] ⚪ Pending — Screens must not dispose controllers (ModuleScope/GetIt owns lifecycle):
  - Audit `flutter-app/lib/presentation/**/screens/**` for `controller.dispose()` or similar.
  - Move disposal to controller `onDispose` and ensure controller implements `Disposable`.
- [ ] ⚪ Pending — Remove **GetIt** usage from widgets/screens (ModuleScope/controller-provided only):
  - All files flagged by `rg -n "GetIt\\.I|getIt\\." flutter-app/lib/presentation | rg -v "/controllers/"`.
- [ ] ⚪ Pending — Remove `FutureBuilder`/`StreamBuilder` from presentation (use StreamValueBuilder + controller state):
  - `flutter-app/lib/presentation/common/widgets/image_palette_theme.dart`
  - `flutter-app/lib/presentation/tenant/schedule/routes/widgets/event_detail_loader.dart`
- [ ] ⚪ Pending — Remove direct `Navigator.*` usage from presentation (router/controller-driven):
  - All files flagged by `rg -n "Navigator\\." flutter-app/lib/presentation`.
- [ ] ⚪ Pending — Split files with multiple widget classes (1 widget per file rule):
  - All files flagged by the multi-widget scan (see Validation Steps).
- [x] ✅ Production‑Ready — Remove DTO usage from **domain** layer (DTOs only in infrastructure):
  - All files flagged by `rg -n "Dto|DTO" flutter-app/lib/domain` are clean; mapping is centralized in infrastructure DTO mappers.

## Acceptance Criteria
- Integration tests run without manual permission prompts.
- Architecture recheck reports **zero deviations**.
- Tests pass after changes.
