# TODO (V1): Account Profile UI (Type-Driven Detail + Favorites)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Deliver a single Account Profile detail experience that adapts to profile types (Artist, Venue in MVP) via configuration, with favorites behavior driven by profile capabilities. “Partner” remains a tenant-facing label; the canonical model is Account Profile (1:1 per Account).

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-implementation.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`
- `lib/presentation/tenant/partners/models/partner_profile_config.dart`

---

## Scope (MVP)
- Use the existing Account Profile detail base page (Flutter: Partner Detail) with type-driven module configs.
- **MVP profile types only:** Artist and Venue.
- Keep favorites in Home; favorites open the Account Profile detail page.
- Favorites visibility and toggling are driven by an account profile capability (`is_favoritable`), with an interim default policy until backend supports it.

## Out of Scope
- Full memberships/roles system.
- Store/commerce modules and external links.
- Account Workspace (post‑MVP).
- Additional account profile types (post‑MVP).

---

## A) Backend Tasks

### A1) Account Profile capabilities (favoritable)
- [ ] ⚪ Define an account profile capabilities field that includes `is_favoritable` (backend source of truth).
- [ ] ⚪ MVP default policy: Artist and Venue account profiles are favoritable; other types default to not favoritable unless enabled.

### A2) Favorites persistence (backend later)
- [ ] ⚪ (Deferred) Backend‑persistent favorites are VNext; for V1 the mock app may reset on load.

---

## B) Flutter Tasks

### B1) Generic Account Profile detail base
- [ ] ⚪ Keep the existing base page (Partner Detail) as the canonical Account Profile detail UI.
- [ ] ⚪ Ensure header + taxonomy remain above tabs for all types.

### B2) Type-driven configs (MVP)
- [ ] ⚪ Artist config (reduced tabs):
  - [ ] ⚪ Bio/summary + upcoming events (schedule).
- [ ] ⚪ Venue config (reduced tabs):
  - [ ] ⚪ `Sobre` (conditional) → `ProfileModuleId.richText`
  - [ ] ⚪ `Como Chegar` (always) → `ProfileModuleId.locationInfo` (map preview + route CTA)
  - [ ] ⚪ `Eventos` (always) → `ProfileModuleId.agendaList`
- [ ] ⚪ Exclude `externalLinks`, `supportedEntities`, commerce/store modules in MVP.

### B3) Post‑MVP type placeholders
- [ ] ⚪ Define additional Account Profile types (e.g., influencer, guide/experience, curator) once module requirements are specified.

### B3) Favorites behavior (MVP)
- [ ] ⚪ Keep Favorites strip in Home as the entrypoint.
- [ ] ⚪ Favorite toggles visible only when `is_favoritable` is true (fallback to MVP default policy until backend provides capabilities).
- [ ] ⚪ Favorite taps open the Account Profile detail page:
  - [ ] ⚪ Primary tenant favorite keeps pinned behavior.
  - [ ] ⚪ Artist/Venue favorites open the Partner Detail page (Flutter naming).

### B4) Navigation entrypoints
- [ ] ⚪ Event Detail `O Local` CTA opens the Venue account profile.
- [ ] ⚪ Venue favorites open the Venue account profile.

---

## C) Acceptance Criteria
- [ ] ⚪ Account Profile detail is type‑driven (Artist/Venue in MVP) with reduced tabs.
- [ ] ⚪ Favorites are visible in Home and open the correct Account Profile detail view.
- [ ] ⚪ Venue profile shows `Como Chegar` with map preview + route CTA.
