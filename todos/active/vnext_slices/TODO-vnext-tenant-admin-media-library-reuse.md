# TODO (VNext): Tenant Admin Media Library Reuse

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Enable tenant-admin users to reuse previously uploaded images across admin flows (for example, reusing a light icon as dark icon) without forcing a new upload from device/web every time.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-static-assets-media-parity-with-account-profiles.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-profile-media-gallery.md`

## Scope (VNext)
- Add a reusable tenant-scoped media library for admin image selection.
- Extend the current image source sheet (`Do dispositivo` / `Da web`) with a third source option for existing uploaded media.
- Support reuse in tenant-admin image slots:
  - Branding: `light_logo`, `dark_logo`, `light_icon`, `dark_icon`, `favicon`, `pwa_icon`.
  - Account Profile / Static Asset: `avatar`, `cover`.
- Preserve current upload-first compatibility and route contracts while adding a new reuse path.

## Decisions to Close
- [ ] ⚪ Decide whether reuse is implemented as file copy on write or shared reference binding (recommended default: copy on write for isolation).
- [ ] ⚪ Define retention policy and cleanup semantics for library items no longer referenced.
- [ ] ⚪ Define tenant-level permissions and visibility boundaries for reusable media entries.
- [ ] ⚪ Decide ordering/search metadata (created_at, source flow, slot type, tags).

## Out of Scope
- Public user-facing media gallery/discovery surfaces.
- AI tagging, semantic search, or automatic moderation.
- CDN/provider migration work.

---

## A) Backend Tasks (Laravel)
- [ ] ⚪ Define media library domain contract (item id, tenant scope, mime, dimensions, source flow, timestamps, preview URL).
- [ ] ⚪ Add endpoint(s) to list/select reusable media entries with tenant/auth constraints.
- [ ] ⚪ Add command/endpoint to apply selected media to a target slot with deterministic storage behavior.
- [ ] ⚪ Ensure canonical URL/legacy alias behavior stays consistent after reuse operations.
- [ ] ⚪ Add tests for listing, authorization, apply-to-slot behavior, and cross-tenant isolation.

## B) Flutter Tasks
- [ ] ⚪ Extend image source sheet with a third option (for example `Da biblioteca`).
- [ ] ⚪ Build reusable picker UI (thumbnail list/grid, selection, empty/error/loading states).
- [ ] ⚪ Integrate picker into Settings, Account Profile, and Static Asset media flows.
- [ ] ⚪ Keep current `Do dispositivo` and `Da web` flows unchanged as fallback paths.
- [ ] ⚪ Add integration/widget coverage for selection + save + post-save render persistence.

## C) Acceptance Criteria
- [ ] ⚪ Admin can select an existing uploaded image and apply it to a supported slot.
- [ ] ⚪ Reused media persists correctly after save and page reload.
- [ ] ⚪ Unauthorized or cross-tenant media selection is blocked.

## D) Definition of Done
- [ ] ⚪ Backend endpoints/contracts and authorization tests are green.
- [ ] ⚪ Flutter source-sheet + picker UX is integrated in scoped admin flows.
- [ ] ⚪ Upload-first behavior remains backward-compatible.
- [ ] ⚪ `fvm flutter analyze` and targeted test suites are green.

