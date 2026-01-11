# TODO (V1): Push CTA Continue-After-Action + Remove `noop`

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Flutter App, push_handler Plugin  
**Objective:** Remove the `noop` custom action and introduce an explicit `continue_after_action` flag so CTA buttons default to advancing only when no custom action is defined, while custom actions can opt into auto-advance when desired.

---

## Scope
- Add `continue_after_action` to CTA button schema (payload + DTO + value object).
- Remove `noop` handling from push_handler CTA button flow.
- Remove `noop` handling from Flutter app `PushActionDispatcher`.
- Default behavior:
  - If `custom_action` is empty → use default advance behavior.
  - If `custom_action` is set → only auto-advance when `continue_after_action=true`.
- Add/update tests for CTA behavior and new flag parsing.

## Out of Scope
- Changes to backend payload validation.
- New custom action types beyond `continue_after_action`.

---

## Tasks (Execution Checklist)

### push_handler Plugin
- [x] ✅ Production-Ready Add `continue_after_action` parsing + value object.
- [x] ✅ Production-Ready Remove `noop` branch from CTA custom action handling.
- [x] ✅ Production-Ready Enforce default advance only when `custom_action` is empty.
- [x] ✅ Production-Ready Enforce optional auto-advance when `continue_after_action=true`.
- [x] ✅ Production-Ready Add/update unit/widget tests covering the above behavior.

### Flutter App
- [x] ✅ Production-Ready Remove `noop` branch from `PushActionDispatcher`.
- [x] ✅ Production-Ready Add/update tests for dispatcher behavior if needed.

---

## Definition of Done
- [x] ✅ Production-Ready `continue_after_action` is supported in button config and wired end-to-end.
- [x] ✅ Production-Ready `noop` is no longer treated as a valid custom action.
- [x] ✅ Production-Ready Empty custom action uses default CTA advance.
- [x] ✅ Production-Ready Non-empty custom action auto-advances only when `continue_after_action=true`.
- [x] ✅ Production-Ready Tests cover the behavior change.

## Validation Steps
- Run targeted push_handler tests for CTA handling.
- Run targeted Flutter app tests (dispatcher + wiring as needed).
