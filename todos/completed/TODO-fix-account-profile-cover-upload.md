# TODO (V1): Fix Account Profile Cover Upload + 422 Error UI

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Flutter Team, Backend Team  
**Objective:** Consolidate account profile media/upload reliability and 422 UX.  
**Scope transfer note:** Static Assets media parity moved to MVP TODO: `foundation_documentation/todos/active/mvp_slices/TODO-v1-static-assets-media-parity-with-account-profiles.md`.

---

## A) Scope
- Keep account profile media flow stable (already fixed in current baseline).
- Ensure **account profile create** continues to respect profile type capabilities (omit unsupported fields).
- Show 422 validation errors as user‑friendly messages (field‑level + summary) for form submissions.
- Add/extend automated coverage for media update persistence.

## B) Out of Scope
- Account creation bugs unrelated to media payload validation.
- New image processing or storage provider changes.

## C) Tasks
- [x] ✅ Production‑Ready Verify backend validation + storage logic for profile `cover` on update vs create.
- [x] ✅ Production‑Ready Fix backend upload limits to handle larger cover files (PHP upload/post limits).
- [x] ✅ Production‑Ready Improve 422 rendering in Flutter form (readable summary + field message).
- [x] ✅ Production‑Ready Update account profile **create** payload to omit fields not supported by selected profile type capabilities.
- [x] ✅ Production‑Ready Existing backend feature tests cover profile media create/update/remove persistence and URL health checks.

## D) Definition of Done
- [x] ✅ Production‑Ready Profile cover upload/update remains stable (no regression).
- [x] ✅ Production‑Ready 422 errors show clear, user‑friendly messages (no raw JSON dumps).
- [x] ✅ Production‑Ready Create flow no longer sends unsupported fields and passes server validation for all profile types.
- [x] ✅ Production‑Ready Tests cover profile media update path with persistence assertion.

## E) Validation
- [x] ✅ Production‑Ready Manual profile cover update succeeds.
- [x] ✅ Production‑Ready Backend feature suite already includes:
  - `testAccountProfileCreateStoresAvatarAndCoverUploads`
  - `testAccountProfileUpdateReplacesAvatarUpload`
  - `testAccountProfileUpdateReplacesCoverUpload`
  - `testAccountProfileRemoveAvatarAndCoverClearsMedia`
  - Note: PHP upload limits are `upload_max_filesize=10M`, `post_max_size=12M` (verified in container).
