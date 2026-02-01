# TODO (V1): Account Profile UI (Type-Driven Detail + Favorites)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Deliver a single Account Profile detail experience that adapts to profile types (Artist, Venue in MVP) via configuration, with favorites behavior driven by profile capabilities. “Partner” remains a tenant-facing label; the canonical model is Account Profile (1:1 per Account). Discovery and partner flows must use **Account Profile Types** and a tenant route that exposes **available accounts** for discovery.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/completed/TODO-v1-account-profile-implementation.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-media-uploads.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-profile-types.md`
- `lib/presentation/tenant/partners/models/partner_profile_config.dart`

---

## Scope (MVP)
- Use the existing Account Profile detail base page (Flutter: Partner Detail) with type-driven module configs.
- **MVP profile types:** `personal`, `artist`, `venue` (registry is flat; no `parent_type` inheritance).
- UI modules in this slice apply to `artist` and `venue`; personal profiles are out of scope for this UI.
- Keep favorites in Home; favorites open the Account Profile detail page.
- Favorites visibility and toggling are driven by `capabilities.is_favoritable`.
- **MVP favoritable policy:** `personal` is **not** favoritable; `artist` and `venue` are favoritable.
- **Registry source (MVP):** `GET /api/v1/environment` exposes `profile_types`; tenant app must hydrate from that payload (**no mock fallback**). If registry is missing/empty, favorites are disabled (no default policy).
- **Non‑MVP types:** keep enums/mocks as‑is, but only render types present in the registry (no new type decisions in MVP).
- **Partner types = Account Profile Types.** Any partner-type selection/filtering must read the account profile type registry.
- Discovery lists must use a dedicated **Account Available** endpoint (tenant scope) that exposes account profiles available for discovery.
- Discovery list is **authenticated-only** but does **not** require a specific ability; any authenticated user token can access it.

## Decisions to Close
- [x] ✅ Production‑Ready **Account Available** is an access concern (not a new route). The existing route must be reachable by Account Users (tenant scope).
- [x] ✅ Production‑Ready **Profile types are registry-driven only**: remove fixed enums and treat `profile_type` as a string sourced from `/api/v1/environment`. Filtering/labels must be derived from the registry, not hardcoded lists.

## Out of Scope
- Full memberships/roles system.
- Store/commerce modules and external links.
- Account Workspace (post‑MVP).
- Additional account profile types (post‑MVP; tracked in `TODO-vnext-account-profile-types.md`).

---

## A) Backend Tasks

### A1) Account Profile capabilities (favoritable)
- [x] ✅ Production‑Ready Define an account profile capabilities field that includes `is_favoritable` (backend source of truth).
- [x] ✅ Production‑Ready MVP default policy: `personal` is **not** favoritable; `artist` and `venue` are favoritable. Registry is flat (no `parent_type`).

### A2) Account Available route (discovery list)
- [x] ✅ Production‑Ready Define and implement the **Account Available** tenant endpoint that returns Account Profiles eligible for discovery.
- [x] ✅ Production‑Ready Ensure the endpoint uses Account Profile Types from the registry for filtering and payload type labels.
- [x] ✅ Production‑Ready Add feature tests covering list, filters, and empty-state responses.

### A2) Favorites persistence (backend later)
- [ ] ⚪ (Deferred) Backend‑persistent favorites are VNext; for V1 the mock app may reset on load.

---

## B) Flutter Tasks

### B1) Generic Account Profile detail base
- [ ] ⚪ Keep the existing base page (Partner Detail) as the canonical Account Profile detail UI.
- [ ] ⚪ Ensure header + taxonomy remain above tabs for all types.

### B2) Type-driven configs (MVP)
- [x] ✅ Artist config (reduced tabs):
  - [x] ✅ Bio/summary + upcoming events (schedule).
- [x] ✅ Venue config (reduced tabs):
  - [x] ✅ `Sobre` (conditional) → `ProfileModuleId.richText`
  - [x] ✅ `Como Chegar` (always) → `ProfileModuleId.locationInfo` (map preview + route CTA)
  - [x] ✅ `Eventos` (always) → `ProfileModuleId.agendaList`
- [x] ✅ Exclude `externalLinks`, `supportedEntities`, commerce/store modules in MVP.

### B3) Post‑MVP type placeholders
- [ ] ⚪ Define additional Account Profile types once module requirements are specified (tracked in `TODO-vnext-account-profile-types.md`).

### B4) Favorites behavior (MVP)
- [x] ✅ Keep Favorites strip in Home as the entrypoint.
- [x] ✅ Favorite toggles visible only when `is_favoritable` is true (if registry is missing/empty, favorites are hidden/disabled).
- [x] ✅ Favorite taps open the Account Profile detail page:
  - [ ] ⚪ Primary tenant favorite keeps pinned behavior.
  - [x] ✅ Artist/Venue favorites open the Partner Detail page (Flutter naming).
  - [x] ✅ Any registry-backed favorite with a resolvable slug opens Partner Detail (no type gating).

### B5) Navigation entrypoints
- [x] ✅ Event Detail `O Local` CTA opens the Venue account profile.
- [x] ✅ Venue favorites open the Venue account profile.

### B6) Discovery list contract (Account Available)
- [x] ✅ Production‑Ready Update discovery partner list to call the Account Available endpoint (no legacy partner endpoints).
- [x] ✅ Production‑Ready Partner type filters must use Account Profile Types (registry-backed) and label accordingly.
- [x] ✅ Production‑Ready Handle empty list state using the Account Available response (no registry fallback).
- [ ] ⚪ Fix discovery filter header overflow by adjusting header extent/padding so registry-driven chips render without RenderFlex errors.

### B7) Registry-driven types (no fixed enums)
- [x] ✅ Production‑Ready Replace `AccountProfileType` enum with registry-driven string types sourced from `/api/v1/environment`.
- [x] ✅ Production‑Ready Update Account Profile model + repository contracts to accept `profile_type` as string.
- [x] ✅ Production‑Ready Remove static filter chip type lists; hydrate chips from registry + available results only.
- [x] ✅ Production‑Ready Provide UI-safe fallback (generic icon/label) when a registry type lacks UI metadata.
- [x] ✅ Production‑Ready Update tests to use registry-provided type strings.

### B8) Admin profile type error visibility (testable)
- [x] ✅ Production‑Ready Render profile type load errors in a dedicated widget with a stable Key so integration tests can assert auth/tenant failures.

---

## C) Acceptance Criteria
- [ ] ⚪ Account Profile detail is type‑driven (Artist/Venue in MVP) with reduced tabs.
- [ ] ⚪ Favorites are visible in Home and open the correct Account Profile detail view.
- [ ] ⚪ Venue profile shows `Como Chegar` with map preview + route CTA.
- [ ] ⚪ Discovery surfaces only show profile types present in the registry.
- [ ] ⚪ Discovery partner list shows a clear empty state when no profiles are available.
- [ ] ⚪ Discovery partner list uses Account Available endpoint and Account Profile Types (no legacy partner types).

## D) Definition of Done
- [ ] ⚪ Artist + Venue tabs match the reduced configs (no commerce, no external links).
- [ ] ⚪ Favorites visibility respects `capabilities.is_favoritable`; no fallback defaults when registry is missing.
- [ ] ⚪ Event detail `O Local` CTA routes to venue profile.
- [ ] ⚪ Discovery empty state differentiates “no partners available” vs “no results for filters”.
- [x] ✅ Production‑Ready Discovery filters + cards render types strictly from registry (no enum/static type lists).

## E) Validation Steps
- [x] ✅ `fvm flutter analyze`
- [ ] ⚪ Manual smoke: open artist + venue profiles, verify tabs, and toggle favorites.
