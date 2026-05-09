# TODO (VNext): Account Profile Media Gallery

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Decide and implement a gallery/media collection for Account Profiles (multi-image support beyond avatar/cover).

---

## Scope (VNext)
- Define a gallery collection/schema linked to Account Profiles.
- Provide CRUD endpoints for gallery items (upload, list, reorder, delete).
- Establish moderation/visibility rules (public vs private).
- Add UI surfaces to manage gallery media.

## Decisions to Close
- [ ] ⚪ Decide storage layout + retention policy.
- [ ] ⚪ Decide max items, size limits, and ordering semantics.
- [ ] ⚪ Decide public/private flags per gallery item.

## Out of Scope
- Avatar + cover uploads (handled in MVP).

---

## A) Backend Tasks (Laravel)
- [ ] ⚪ Gallery collection + model.
- [ ] ⚪ CRUD endpoints + validation.
- [ ] ⚪ Storage + cleanup on delete.

## B) Flutter Tasks
- [ ] ⚪ Admin UI for gallery management.
- [ ] ⚪ Public profile display of gallery.

## C) Acceptance Criteria
- [ ] ⚪ Gallery items can be uploaded, reordered, and removed.

## D) Definition of Done
- [ ] ⚪ Tests cover CRUD + permissions.
- [ ] ⚪ `fvm flutter analyze` passes.
