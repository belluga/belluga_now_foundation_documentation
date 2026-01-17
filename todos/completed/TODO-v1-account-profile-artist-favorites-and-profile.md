# TODO (V1): Account Profile (Artist) Favorites + Profile (Reduced Tabs)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team + Delphi (Flutter)  
**Objective:** Deprecated. Merged into `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`.
**Note:** This file is retained for history; all work continues in the unified Account Profile UI slice.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-implementation.md`
- `foundation_documentation/todos/active/TODO-vnext-parking-lot.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`

## Taxonomy Summary (MVP)
- Account profiles (tenant-facing partner label) can carry multiple taxonomies (WordPress‑style). A Venue may have `cuisines` and also `music_genres`.
- Taxonomy terms are **typed** (e.g., `music_genres`, `cuisines`, `experiences`) and can be filtered independently.
- Favorites are driven by **capability** (interim policy: `artist` and `venue` are favoritable until backend sends `is_favoritable`).

---

## A) Backend Tasks

### A1) Account Profile “capabilities” (favoritable)
- [x] ✅ Define an account profile blueprint/capabilities field that includes `is_favoritable` (backend source of truth)
- [x] ✅ V1 default policy: Artist account profiles are favoritable; other account profile types default to not favoritable unless enabled

### A2) Favorites persistence (backend later)
- [x] ✅ (Deferred) Backend-persistent favorites are VNext; for V1 the mock app may reset on load

---

## B) Flutter Tasks

### B1) Favorites list stays in Home
- [x] ✅ Keep Favorites strip in Home as the entrypoint
- [x] ✅ When a favorite is tapped:
  - [x] ✅ If it’s the primary tenant favorite, keep existing pinned behavior
  - [x] ✅ If it’s an Artist account profile, open the existing Partner Detail page (Flutter naming)

### B2) Restrict favorites to Artist account profiles (V1)
- [x] ✅ Enforce “artist-only favoritable” behavior in the mock repository path until backend sends capabilities
- [x] ✅ Ensure non-artist account profiles show favorite disabled/hidden (no toggle)

### B3) Reduce Artist account profile tabs (do not create a new screen)
- [x] ✅ Update the artist `PartnerProfileConfig` to a minimal set of tabs/modules:
  - [x] ✅ Bio/summary + upcoming events (schedule)
- [x] ✅ Avoid store modules in V1 (defer to VNext)

---

## C) Acceptance Criteria

- [x] ✅ Users can favorite/unfavorite Artist account profiles (only)
- [x] ✅ Favorites remain visible in Home and open the artist account profile
- [x] ✅ Artist account profile uses the existing base page with reduced tabs (no new detail screen)
