# TODO (V1): Push Message Types + Routes Endpoints

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Establish dedicated endpoints for `push_message_types` and `push_message_routes` to improve invite push resolution and authoring UX.

---

## Scope
- Define independent endpoints for managing `push_message_types` and `push_message_routes`.
- Enforce the same permissions as tenant push settings for these endpoints.
- Add association support so a `push_message_type` can expose an allowed subset of `push_message_routes`.
- Update push message creation flow to use a type’s available routes (not the global route list).
- Ensure data contracts align with existing tenant settings schema definitions.
- Remove `push_message_types` and `push_message_routes` from `/settings/push`; require the new endpoints.
- Ensure `route_types` and `message_types` accept arrays of objects for bulk updates (not single-object payloads).
- Confirm `query_params` accepts an array of strings as provided by the client.
- Switch `route_types`/`message_types` endpoints to accept raw array bodies (no root key).
- Use DELETE for removals via `{ "keys": ["..."] }`.

## Out of Scope
- Flutter UI implementation details beyond consuming the new endpoints.
- New push message delivery logic or changes to FCM client behavior.
- Changes to invite business rules outside push routing/resolution.

## Definition of Done
- [x] ✅ Endpoints defined and documented for `push_message_types`.
- [x] ✅ Endpoints defined and documented for `push_message_routes`.
- [x] ✅ Permissions match tenant push settings abilities.
- [x] ✅ `push_message_type` supports allowed routes list and validation.
- [x] ✅ Push message authoring uses type-scoped routes.
- [x] ✅ PATCH endpoints merge by `key` (upsert) without deleting existing entries.
- [x] ✅ Delete-by-key soft-deactivates routes/types and excludes inactive entries from creation (DELETE with `keys`).
- [x] ✅ Tests cover auth, validation, and route filtering behavior.
- [x] ✅ `/settings/push` no longer accepts `push_message_types` or `push_message_routes`.
- [x] ✅ Tests cover rejection of route/type fields on `/settings/push`.
- [x] ✅ Bulk payloads for route/message types accepted as arrays of objects.
- [x] ✅ Provide sample request body for the provided `push_message_routes` and `push_message_types`.

## Validation Steps
- [x] ✅ Feature tests for CRUD of types/routes.
- [x] ✅ Auth tests (401/403) mirror tenant push settings.
- [x] ✅ Validation tests for route association (unknown route keys rejected).
- [x] ✅ Type-scoped route list returned correctly.

## Decisions
- Endpoints live under the existing tenant push settings namespace:
  - `/api/v1/settings/push/route_types`
  - `/api/v1/settings/push/message_types`
- HTTP verbs: `GET` and `PATCH` only.
- Use embedded arrays in tenant settings with validation (no embedMany).
- Enforce unique `key` values within `push_message_types` and `push_message_routes`.
- Enforce uniqueness via application-layer validation (no DB unique index).
- Association field name: `allowed_route_keys`.
- No ordering/grouping; key-based lists only.
- PATCH for route/message types performs keyed upsert (merge by `key`); no deletions.
- Delete behavior: soft delete by setting `active=false` on routes/types; creation uses only active entries.
- `/settings/push` no longer supports route/type fields; use the dedicated endpoints only.
- Payload shape: raw array of objects; no root key.
- Deletion uses DELETE endpoints with `{ "keys": ["..."] }`; PATCH is upsert-only.

## Questions to Close
- None.

## References
- `foundation_documentation/todos/completed/TODO-v1-telemetry-and-push-backend.md`
- `foundation_documentation/system_roadmap.md`
- `laravel-app/packages/belluga/belluga_push_handler/`
