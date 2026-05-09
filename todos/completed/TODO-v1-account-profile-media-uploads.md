# TODO (V1): Account Profile Media Uploads (Avatar + Cover)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Allow tenant-admin users to upload avatar/cover images for Account Profiles, store them in tenant-scoped storage, and persist public URLs on the Account Profile record.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- `foundation_documentation/domain_entities.md` (Account Profile media fields)
- `laravel-app/app/Http/Api/v1/Requests/AccountProfileStoreRequest.php`
- `laravel-app/app/Http/Api/v1/Requests/AccountProfileUpdateRequest.php`
- `laravel-app/app/Traits/HasLogoFiles.php` (existing storage helper)

---

## Scope (MVP)
- Support **image uploads** for Account Profile **avatar** and **cover** via tenant-admin API.
- Persist **public URLs** on the Account Profile (`avatar_url`, `cover_url`).
- Replace existing images on update and clean up prior stored files.
- Flutter tenant-admin form allows selecting images and uploads them when creating/updating profiles.

## Decisions to Close
- [x] ✅ Production‑Ready Storage path: `tenants/{tenant_slug}/account_profiles/{profile_id}/{avatar|cover}.{ext}`.
- [x] ✅ Production‑Ready File limits: `jpg|jpeg|png|webp`, max **5120 KB**.
- [x] ✅ Production‑Ready Uploads are **tenant-admin only** in MVP.
- [x] ✅ Production‑Ready Gallery support is **VNext** (see `TODO-vnext-account-profile-media-gallery.md`).

## Out of Scope
- User-facing profile media updates (non-admin).
- Image transformations beyond basic storage (resize/crop).
- CDN/invalidation strategy.
- Gallery media (tracked in `foundation_documentation/todos/active/vnext/TODO-vnext-account-profile-media-gallery.md`).

---

## A) Backend Tasks (Laravel)

### A1) Upload handling
- [x] ✅ Production‑Ready Accept multipart uploads for `avatar` and `cover` on Account Profile create/update.
- [x] ✅ Production‑Ready Store files under tenant-scoped paths on `public` disk.
- [x] ✅ Production‑Ready Return/persist public URLs to `avatar_url` + `cover_url`.
- [x] ✅ Production‑Ready Delete previous files when replaced.

### A2) Validation
- [x] ✅ Production‑Ready Update request validation to accept `image` files (png/jpg/webp) with size limits.
- [x] ✅ Production‑Ready Keep existing URL fields for compatibility; if file provided, file wins.

### A3) Tests
- [x] ✅ Production‑Ready Feature tests for create/update with file uploads (Storage::fake).
- [x] ✅ Production‑Ready Ensure update replaces URL and deletes old file.

---

## B) Flutter Tasks (Tenant Admin)

### B1) Form updates
- [x] ✅ Production‑Ready Add image pickers for avatar + cover on Account Profile form.
- [x] ✅ Production‑Ready Show preview + clear/remove option.

### B2) Repository + request
- [x] ✅ Production‑Ready Submit multipart/form-data for avatar/cover when provided.
- [x] ✅ Production‑Ready Preserve existing URLs if no new file selected.

### B3) Tests
- [x] ✅ Production‑Ready Widget test: form shows selected image state + clear.
- [x] ✅ Production‑Ready Repository unit test: multipart request includes file parts when present.

---

## C) Documentation Updates
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/*` for tenant-admin Account Profile form to reflect media upload.
- [x] ✅ Production‑Ready Confirm `domain_entities.md` fields (`avatar_uri`, `cover_uri`) remain URL-based (backend generated).
- [x] ✅ Production‑Ready Note API contract expectations in `system_roadmap.md` if a new upload route is added (no new route required).

---

## D) Acceptance Criteria
- [x] ✅ Production‑Ready Admin can upload avatar/cover on create.
- [x] ✅ Production‑Ready Admin can replace avatar/cover on update.
- [x] ✅ Production‑Ready URLs are saved on Account Profile and returned in API.

## E) Definition of Done
- [x] ✅ Production‑Ready Backend endpoints support multipart upload with validation + storage.
- [x] ✅ Production‑Ready Flutter form supports picking, preview, and upload.
- [x] ✅ Production‑Ready Feature + widget/repository tests pass.
- [x] ✅ Production‑Ready `fvm flutter analyze` is clean.

## F) Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.
- [x] ✅ Production‑Ready Run Laravel feature tests covering uploads.
- [x] ✅ Production‑Ready Run widget test for Account Profile media selection.
- [ ] ⚪ Manual smoke: upload avatar + cover, verify stored URLs and UI previews.
