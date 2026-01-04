# TODO (V1): App Init Order Ensures Auth Before Push

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter app)  
**Objective:** Reorder application initialization so authentication/token state is ready before push handler init, preventing skipped token registration.

---

## Scope
- Ensure `AuthRepository` (or token provider source) is initialized before push handler init.
- Update application initialization flow (ApplicationContract + platform implementations) to enforce order.
- Keep push handler initialization behavior unchanged.

## Out of Scope
- Changing push handler plugin logic.
- Altering backend push registration endpoints.
- Reworking auth storage or token issuance.

## Definition of Done
- [x] ✅ Production‑Ready App init guarantees `tokenProvider` has a value before push init runs.
- [x] ✅ Production‑Ready Push init logs no longer show “Auth token missing; skip register” on first launch with a valid session.
- [x] ✅ Production‑Ready No regression in other init sequences (web/mobile).

## Validation Steps
- [x] ✅ Production‑Ready Fresh install: app logs show token acquired and register fired.
- [x] ✅ Production‑Ready Reinstall: app logs show token acquired and register fired (or explicit auth delay resolved before push init).

## Decisions
- Prefer app-layer ordering over plugin retry for now.

## Questions to Close
- None.
