# TODO (V1): Push Message Fetch With App Bearer Token

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter), Delphi (Laravel)  
**Objective:** Fetch user-specific push message content using the app Bearer token while keeping payloads anonymous.

---

## Scope
- Keep `/api/v1/push/messages/{message_id}` authenticated with Bearer token.
- Validate recipient eligibility using the authenticated `user_id` (Bearer token) against the message audience.
- Flutter: ensure push fetch sends Bearer token when calling `fetchMessageData`.
- Keep push payload limited to `push_message_id` (no user identifiers).
- Document the auth strategy and required headers/body fields in foundation docs.

## Out of Scope
- Opening the push message route publicly.
- Adding signed URLs or nonce exchange flows.
- Replacing the existing push handler layout rendering.

## Definition of Done
- [x] ✅ Production‑Ready Laravel route for `/api/v1/push/messages/{message_id}` remains authenticated and enforces user eligibility checks.
- [x] ✅ Production‑Ready Flutter `PushApiClient.fetchMessageData` sends Bearer token consistently.
- [x] ✅ Production‑Ready Background delivery reporting uses the same auth strategy where applicable.
- [x] ✅ Production‑Ready Documentation updated with auth contract and payload expectations.

## Validation Steps
- [x] ✅ Production‑Ready Authenticated fetch returns message data for eligible user.
- [x] ✅ Production‑Ready Authenticated fetch returns 404 for non-eligible user.
- [x] ✅ Production‑Ready Foreground push renders correctly after fetch.
- [x] ✅ Production‑Ready Background receipt still records `delivered` action.

## Decisions
- Use the app Bearer token for message fetches; payloads remain anonymous (only `push_message_id`).
- Enforce audience eligibility using the authenticated user (no device-id check).
- Respond with `404 Not Found` for non-eligible users to avoid revealing message existence.

## Questions to Close
- None.

## References
- `flutter-app/lib/application/push/push_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/push_api_client.dart`
- `laravel-app/routes/api/*` (push routes)
