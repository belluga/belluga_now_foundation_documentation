# TODO (V1): Fix Account Profile Cover Upload + 422 Error UI

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
**Owners:** Flutter Team, Backend Team  
**Objective:** Fix cover upload failure on account profile update, ensure create form respects profile type capabilities, and render 422 form errors in a user‑friendly way.

---

## A) Scope
- Diagnose and fix **cover** upload on account profile update (avatar already works).
- Ensure **account profile create** respects profile type capabilities (omit unsupported fields).
- Show 422 validation errors as user‑friendly messages (field‑level + summary) for form submissions.
- Add/extend test coverage for cover update when possible.

## B) Out of Scope
- Account creation bugs unrelated to cover upload.
- New image processing or storage provider changes.

## C) Tasks
- [ ] ⚪ Pending Reproduce the cover upload failure and capture request/response details (422 payload).
- [x] ✅ Production‑Ready Verify backend validation + storage logic for `cover` on update vs create.
- [x] ✅ Production‑Ready Fix backend upload limits to handle larger cover files (PHP upload/post limits).
- [x] ✅ Production‑Ready Improve 422 rendering in Flutter form (readable summary + field message).
- [x] ✅ Production‑Ready Update account profile **create** payload to omit fields not supported by selected profile type capabilities.
- [ ] ⚪ Pending Add/extend test that updates cover and asserts success (or proper error rendering if backend rejects).

## D) Definition of Done
- [ ] ⚪ Pending Cover upload update succeeds consistently (no 422).
- [x] ✅ Production‑Ready 422 errors show clear, user‑friendly messages (no raw JSON dumps).
- [x] ✅ Production‑Ready Create flow no longer sends unsupported fields and passes server validation for all profile types.
- [ ] ⚪ Pending Tests cover the update path (cover).

## E) Validation
- [ ] ⚪ Pending Manual update on device (cover) succeeds.
- [ ] ⚪ Pending Test suite relevant to cover update passes (integration/widget or backend test).
  - Note: PHP upload limits now set to `upload_max_filesize=10M`, `post_max_size=12M` (verified in container).
