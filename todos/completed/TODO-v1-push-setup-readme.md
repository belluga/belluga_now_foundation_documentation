# TODO (V1): Push Setup README Section

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Document the end-to-end push setup steps in the Laravel push handler README.

---

## Scope
- Add a new “Push Setup” section to the push handler README.
- Include required steps for credentials, firebase settings, push settings, route types, message types, and enable/disable.
- Provide example payloads for each step.

## Out of Scope
- Changing any endpoint behavior.
- Flutter/client UI wiring.
- Infrastructure/FCM delivery configuration beyond API setup.

## Definition of Done
- [x] ✅ Push Setup section added to `laravel-app/packages/belluga/belluga_push_handler/README.md`.
- [x] ✅ Step-by-step sequence documented (credentials → firebase → push → routes → message types → enable).
- [x] ✅ Example payloads provided for each endpoint.

## Validation Steps
- [x] ✅ README reviewed for accuracy against current endpoints.

## Decisions
- None.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/README.md`
