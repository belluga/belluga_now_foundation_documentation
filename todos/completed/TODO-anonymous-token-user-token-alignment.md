# TODO (V1): Align Anonymous Token With user_token Storage

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Store anonymous auth tokens in `user_token` so all authenticated API calls (including push) use the same Bearer token.

---

## Scope
- When `AnonymousAuthService` obtains a token, persist it in `StorageKeys.userToken`.
- Update `AuthRepository` stream/state to reflect the anonymous token as the active `userToken`.
- Ensure push actions and message fetch use the unified `userToken` without special-case overrides.

## Out of Scope
- Changing backend auth contracts or abilities.
- Introducing separate token types or additional headers.

## Definition of Done
- [x] ✅ Production‑Ready Anonymous token is stored in `user_token` and surfaced via `AuthRepository.userToken`.
- [x] ✅ Production‑Ready Push action reporting and message fetch succeed for anonymous users without 401.
- [x] ✅ Production‑Ready No regressions in login/logout flows (user token still replaces anonymous token).

## Validation Steps
- [x] ✅ Production‑Ready Fresh install: anonymous identity created → `user_token` populated.
- [x] ✅ Production‑Ready Push `delivered/opened` actions return 200 for anonymous user.
- [x] ✅ Production‑Ready User login replaces `user_token` as expected.

## Decisions
- Use a single Bearer token slot (`user_token`) for anonymous and logged-in states.

## Questions to Close
- None.

## References
- `flutter-app/lib/infrastructure/services/auth/anonymous_auth_service.dart`
- `flutter-app/lib/infrastructure/repositories/auth_repository.dart`
- `flutter-app/lib/application/push/push_coordinator.dart`
