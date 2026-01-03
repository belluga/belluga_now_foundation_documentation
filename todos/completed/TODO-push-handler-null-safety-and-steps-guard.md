# TODO (V1): Harden push_handler Null Safety + Step Navigation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter plugin)  
**Objective:** Prevent push_handler crashes when optional fields are missing and guard step navigation for single/empty step payloads.

---

## Scope
- Make `ChatDataDTO.tryFromMap` null-safe when `chat` is missing or null.
- Make `ImageDataDTO.tryFromMap` null-safe when `image` is missing, empty, or missing `path`.
- Ensure MessageData parsing tolerates absent/empty `steps` arrays.
- Guard `PushWidgetController.toNext()` when steps length is 0/1 to avoid invalid TabController index.
- Use the local `push_handler` package via `dependency_overrides` in `pubspec.yaml` for testing.
- Support payload-defined background color on push layouts.
- On the last step, show Actions or a forced dismiss button when no action is provided.
- On the last step, use a close (X) dismiss affordance instead of “pular”.
- Add a routeResolver callback to handle internal navigation externally (AutoRoute).
- Use Wrap for action buttons to prevent overflow on small screens.

## Out of Scope
- Backend payload schema changes.
- Visual layout refactors.

## Definition of Done
- [x] ✅ Production‑Ready Push rendering no longer throws when `chat` is null.
- [x] ✅ Production‑Ready Push rendering no longer throws when `image` is null/empty or missing `path`.
- [x] ✅ Production‑Ready Single/zero-step payloads do not trigger TabController assertion.
- [x] ✅ Production‑Ready Push UI renders for the same payload used in device logs.
- [x] ✅ Production‑Ready App uses local `push_handler` override for testing.
- [x] ✅ Production‑Ready Payload background color renders as specified.
- [x] ✅ Production‑Ready Last step always has an action or forced dismiss.
- [x] ✅ Production‑Ready Last-step dismiss uses close (X) affordance.
- [x] ✅ Production‑Ready Route resolver handles internal navigation without Navigator.pushNamed errors.
- [x] ✅ Production‑Ready Buttons wrap without RenderFlex overflow.

## Validation Steps
- [x] ✅ Production‑Ready Trigger push with `steps: []` → UI renders without crash.
- [x] ✅ Production‑Ready Trigger push without `chat` → UI renders without crash.
- [x] ✅ Production‑Ready Logs confirm local `push_handler` is in use after rebuild.
- [x] ✅ Production‑Ready Push payload with background color renders correctly.
- [x] ✅ Production‑Ready Last-step without action renders forced close (X) dismiss.
- [x] ✅ Production‑Ready Internal route buttons navigate via routeResolver.

## Decisions
- Prefer null-safe DTO parsing and no-op step navigation when there is no next step.
- Payload background color uses hex string (e.g., `#RRGGBB`).

## Questions to Close
- None.

## References
- `flutter-packages/push_handler/lib/src/domain/dto/chat_data_dto.dart`
- `flutter-packages/push_handler/lib/src/domain/dto/message_data_dto.dart`
- `flutter-packages/push_handler/lib/src/presentation/controller/push_widget_controller.dart`
