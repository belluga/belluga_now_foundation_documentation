# TODO (V1): Account Profile UI (MVP Scope Freeze + VNext Carryover)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active (Scope Frozen for MVP)  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Enforce the MVP boundary so V1 work is limited to visual polish on `sign in`, `sign up`, and the main `Perfil` screen only. Net-new Account Profile area expansion (detail/discovery/favorites/admin/workspace/event-management surfaces) is deferred to VNext.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/completed/TODO-v1-account-profile-implementation.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-media-uploads.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-profile-types.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-workspace.md`
- `lib/presentation/tenant/partners/models/partner_profile_config.dart`

---

## Scope (MVP)
- Restrict MVP polish to:
  - `sign in` screen
  - `sign up` screen
  - main `Perfil` screen
- Keep current Account Profile behavior stable; do not expand this area during MVP.
- Carry all remaining Account Profile area backlog to VNext planning and implementation.

## Decisions to Close
- [x] ✅ Production‑Ready **MVP scope freeze:** only auth + main profile visual polish (`sign in`, `sign up`, `Perfil`) remains in V1 scope.
- [x] ✅ Production‑Ready **Account Profile area expansion is VNext:** create/manage/event-management/account-workspace flows are deferred.
- [x] ✅ Production‑Ready **Account Available** is an access concern (not a new route). The existing route must be reachable by Account Users (tenant scope).
- [x] ✅ Production‑Ready **Profile types are registry-driven only**: remove fixed enums and treat `profile_type` as a string sourced from `/api/v1/environment`. Filtering/labels must be derived from the registry, not hardcoded lists.

## Out of Scope
- Net-new Account Profile area delivery during MVP.
- Account Profile create/manage/event-management flows.
- Discovery/favorites enhancements beyond what is required for auth + main profile polish.
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
- [ ] ⚪ Deferred to VNext: Keep the existing base page (Partner Detail) as the canonical Account Profile detail UI.
- [ ] ⚪ Deferred to VNext: Ensure header + taxonomy remain above tabs for all types.

### B2) Type-driven configs (MVP)
- [x] ✅ Artist config (reduced tabs):
  - [x] ✅ Bio/summary + upcoming events (schedule).
- [x] ✅ Venue config (reduced tabs):
  - [x] ✅ `Sobre` (conditional) → `ProfileModuleId.richText`
  - [x] ✅ `Como Chegar` (always) → `ProfileModuleId.locationInfo` (map preview + route CTA)
  - [x] ✅ `Eventos` (always) → `ProfileModuleId.agendaList`
- [x] ✅ Exclude `externalLinks`, `supportedEntities`, commerce/store modules in MVP.

### B3) Post‑MVP type placeholders
- [ ] ⚪ Deferred to VNext: Define additional Account Profile types once module requirements are specified (tracked in `TODO-vnext-account-profile-types.md`).

### B4) Favorites behavior (MVP)
- [x] ✅ Keep Favorites strip in Home as the entrypoint.
- [x] ✅ Favorite toggles visible only when `is_favoritable` is true (if registry is missing/empty, favorites are hidden/disabled).
- [x] ✅ Favorite taps open the Account Profile detail page:
  - [ ] ⚪ Deferred to VNext: Primary tenant favorite keeps pinned behavior.
  - [x] ✅ Artist/Venue favorites open the Partner Detail page (Flutter naming).
  - [x] ✅ Any registry-backed favorite with a resolvable slug opens Partner Detail (no type gating).

### B5) Navigation entrypoints
- [x] ✅ Event Detail `O Local` CTA opens the Venue account profile.
- [x] ✅ Venue favorites open the Venue account profile.

### B6) Discovery list contract (Account Available)
- [x] ✅ Production‑Ready Update discovery partner list to call the Account Available endpoint (no legacy partner endpoints).
- [x] ✅ Production‑Ready Partner type filters must use Account Profile Types (registry-backed) and label accordingly.
- [x] ✅ Production‑Ready Handle empty list state using the Account Available response (no registry fallback).
- [ ] ⚪ Deferred to VNext: Fix discovery filter header overflow by adjusting header extent/padding so registry-driven chips render without RenderFlex errors.

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
- [ ] ⚪ Sign in screen has final visual polish with clear hierarchy and state styling.
- [ ] ⚪ Sign up screen has final visual polish with clear hierarchy and state styling.
- [ ] ⚪ Main `Perfil` screen has final visual polish with stable layout/states.
- [ ] ⚪ No new Account Profile area management/event surfaces are introduced in MVP.
- [ ] ⚪ Account Profile area backlog is explicitly deferred to VNext references.

## D) Definition of Done
- [ ] ⚪ Scope freeze is consistent across:
  - `TODO-v1-account-profile-ui.md`
  - `TODO-vnext-tenant-user-account-profile-area.md`
  - `TODO-v1-targeted-visual-polish.md`
- [ ] ⚪ All non-polish Account Profile area pending items are marked as VNext/deferred.
- [x] ✅ Production‑Ready Discovery filters + cards render types strictly from registry (no enum/static type lists).

## E) Validation Steps
- [ ] ⚪ Manual smoke: verify `sign in`, `sign up`, and main `Perfil` polish on mobile breakpoints.
- [ ] ⚪ Manual smoke: verify no new Account Profile area flows were introduced in MVP.
