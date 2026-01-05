# TODO (V1): Push Route Key Error Message Clarity

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (🟡 Provisional)  
**Owners:** Backend Team (source of truth)  
**Objective:** Improve push message validation errors for invalid route keys so they explain whether the key is missing or disallowed by message type.

---

## Scope
- Update validation errors for `payload_template.buttons.*.action.route_key` to:
  - Distinguish “route key not defined” vs “route key not allowed for message type.”
  - Include allowed route keys when restricted by message type.
- Adjust tests to assert the new error messaging.

## Out of Scope
- Changing validation rules or allowed behavior.
- Client-side error handling changes.

## Definition of Done
- [x] ✅ Error message clarifies missing vs disallowed route key.
- [x] ✅ Allowed route keys are listed when applicable.
- [x] ✅ Tests updated to cover the new messages.

## Validation Steps
- [ ] 🟡 Feature tests covering route key errors pass. (Not run yet.)

## Decisions
- Use validator message override for missing route key and explicit allowed-key message when disallowed.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushMessageStoreRequest.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushMessageUpdateRequest.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`
