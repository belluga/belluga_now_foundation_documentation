# Flutter Mounted Exceptions Artifact
**Version:** 1.0

## Purpose
Track explicitly approved `mounted`/`context.mounted` exceptions so future scans do not re‑litigate the same decisions.

## Status Legend
- `Deferred`: keep current implementation, revisit later.
- `Canceled`: no refactor planned; exception is accepted.
- `Resolved`: refactor completed; no exception needed.

## Exceptions Log
| ID | File | Decision | Rationale | Date | Owner |
| --- | --- | --- | --- | --- | --- |
| MNT-001 | `flutter-app/lib/application/application_contract.dart` | Deferred | Initial route telemetry needs post‑frame snapshot; `mounted` guard prevents state access after dispose. | 2026-02-03 | Delphi |
| MNT-002 | `flutter-app/lib/presentation/tenant/map/screens/map_screen/widgets/map_status_message_listener.dart` | Canceled | Effects‑only snackbar; `mounted` is a lifecycle safety guard. | 2026-02-03 | Delphi |
| MNT-003 | `flutter-app/lib/presentation/tenant/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart` | Canceled | UI effects (post‑frame navigation + snackbar) and image precache; `mounted` is a lifecycle safety guard. | 2026-02-03 | Delphi |

## Notes
- Exceptions must be removed or updated once refactors eliminate the `mounted` usage.
- New exceptions should include a concrete rationale and owner.
