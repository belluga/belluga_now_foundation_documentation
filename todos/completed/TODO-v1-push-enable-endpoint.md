# TODO (V1): Push Enable/Disable Endpoint

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Separate push enable/disable from settings updates to improve clarity and avoid sending full configuration payloads, while nesting push delivery policies under `push`.

---

## Scope
- Add a dedicated endpoint to enable push after validating required configuration.
- Add a dedicated endpoint to disable push without requiring full settings payload.
- Keep `/settings/push` focused on configuration (firebase + push settings only).
- Document the activation flow (configure → enable → status).
- Remove `push.types` from `/settings/push` (types are defined by message types).
- Move `max_ttl_days` to `push.max_ttl_days` and reject the top-level field.

## Out of Scope
- Push message types/routes management (handled by dedicated endpoints).
- FCM delivery pipeline changes.
- Flutter UI wiring.

## Definition of Done
- [x] ✅ `POST /api/v1/settings/push/enable` validates required config and sets `push.enabled=true`.
- [x] ✅ `POST /api/v1/settings/push/disable` sets `push.enabled=false`.
- [x] ✅ `GET /api/v1/settings/push/status` behavior unchanged.
- [x] ✅ Tests cover enable/disable flows and validation errors.
- [x] ✅ Package README documents the new endpoints and flow.
- [x] ✅ `/settings/push` no longer accepts `push.types`.
- [x] ✅ Tests updated for removal of `push.types`.
- [x] ✅ README updated to remove `push.types` from settings payloads.
- [x] ✅ `/settings/push` accepts `push.max_ttl_days` and rejects top-level `max_ttl_days`.
- [x] ✅ Tests updated for `push.max_ttl_days` (including default behavior).
- [x] ✅ README updated to show `push.max_ttl_days` in settings payloads.

## Validation Steps
- [x] ✅ Feature tests for enable/disable endpoints.
- [x] ✅ Validation tests for missing firebase config when enabling.
- [x] ✅ Feature tests for `push.max_ttl_days` validation + defaults.

## Decisions
- Enable/disable are separate endpoints (no mixing with `/settings/push` updates).
- Keep `/settings/push` for configuration only.
- Push delivery policies (TTL) live under the `push` object.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/`
- `foundation_documentation/system_roadmap.md`
