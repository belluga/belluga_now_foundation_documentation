# Flutter Mounted Exceptions Artifact
**Version:** 1.0

## Purpose
Track explicitly approved `mounted`/`context.mounted` exceptions so future scans do not re-litigate the same decisions.

## Status Legend
- `Deferred`: keep current implementation, revisit later.
- `Canceled`: no refactor planned; exception is accepted.
- `Resolved`: refactor completed; no exception needed.

## Exceptions Log
| ID | File | Decision | Rationale | Date | Owner |
| --- | --- | --- | --- | --- | --- |
| MNT-001 | `flutter-app/lib/application/application_contract.dart` | Deferred | Initial route telemetry needs post-frame snapshot; `mounted` guard prevents state access after dispose. | 2026-02-03 | Delphi |
| MNT-002 | `flutter-app/lib/presentation/tenant/map/screens/map_screen/widgets/map_status_message_listener.dart` | Canceled | Effects-only snackbar; `mounted` is a lifecycle safety guard. | 2026-02-03 | Delphi |
| MNT-003 | `flutter-app/lib/presentation/tenant/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart` | Canceled | UI effects (post-frame navigation + snackbar) and image precache; `mounted` is a lifecycle safety guard. | 2026-02-03 | Delphi |
| MNT-004 | `flutter-app/lib/presentation/tenant_admin/taxonomies/screens/tenant_admin_taxonomies_list_screen.dart` | Canceled | Remaining guard protects snackbar-only UI effect from late stream events after widget disposal. No async navigation remains in submit flow. | 2026-02-11 | Delphi |
| MNT-005 | `flutter-app/lib/presentation/tenant_admin/taxonomies/screens/tenant_admin_taxonomy_terms_list_screen.dart` | Canceled | Remaining guard protects snackbar-only UI effect from late stream events after widget disposal. No async navigation remains in submit flow. | 2026-02-11 | Delphi |
| MNT-006 | `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet.dart` | Canceled | Ephemeral bottom-sheet widget performs async byte load + local `setState`; `mounted` guards prevent late UI updates after sheet dismissal. No navigation ownership in controller; sheet uses AutoRoute `context.router.maybePop` only. | 2026-02-17 | Delphi |
| MNT-007 | `flutter-app/lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart` | Deferred | Image pick flows await user-driven sheets (source + URL + crop). `mounted`/`context.mounted` guards prevent late snackbars and UI updates when the screen is dismissed mid-flow. Candidate refactor: move ingestion orchestration into controller via StreamValue-driven effects. | 2026-02-17 | Delphi |
| MNT-008 | `flutter-app/lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_create_screen.dart` | Deferred | Same pattern as MNT-007 for create-profile image flows (device/URL + crop). Guards are lifecycle safety for effects-only UI updates. | 2026-02-17 | Delphi |
| MNT-009 | `flutter-app/lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_edit_screen.dart` | Deferred | Same pattern as MNT-007 for edit-profile image flows (device/URL + crop + autosave). Guards prevent UI effects after dismissal; navigation remains AutoRoute-only. | 2026-02-17 | Delphi |
| MNT-010 | `flutter-app/lib/presentation/tenant_admin/static_assets/screens/tenant_admin_static_asset_create_screen.dart` | Deferred | Same pattern as MNT-007 for static-asset create image flows (device/URL + crop). Guards prevent late effects after dismissal. | 2026-02-17 | Delphi |
| MNT-011 | `flutter-app/lib/presentation/tenant_admin/static_assets/screens/tenant_admin_static_asset_edit_screen.dart` | Deferred | Same pattern as MNT-007 for static-asset edit image flows (device/URL + crop). Guards prevent late effects after dismissal. | 2026-02-17 | Delphi |
| MNT-012 | `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_screen.dart` | Deferred | Branding image flows await source sheet + optional URL fetch + crop sheet. `mounted` checks only guard late UI updates/effects when the settings screen is dismissed mid-flow; no controller navigation ownership was introduced. | 2026-02-19 | Delphi |

## Notes
- Exceptions must be removed or updated once refactors eliminate the `mounted` usage.
- New exceptions should include a concrete rationale and owner.
