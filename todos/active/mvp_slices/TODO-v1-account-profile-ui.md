# TODO (V1): Account Profile UI (Discovery + Detail Polish)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Deliver tenant-public Account Profile UI polish for Discovery and Account Profile detail surfaces, without adding Discovery Hero textual block or backend/admin contract changes.

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
- Polish `Account Profile Discovery` and `Account Profile detail` screens in `tenant_public`.
- Improve visual hierarchy, spacing, state clarity, and CTA affordance for discovery/detail flows.
- Keep existing registry-driven and endpoint-backed behavior intact (no backend/API contract changes).
- Do not add a Discovery Hero textual block in MVP.
- Use `Tocando agora` + `Perto de você` sections as Discovery top hierarchy.
- Align account-profile-specific polish with:
  - Events entrypoints that lead into profile detail.
  - Invite flow entrypoints that resolve profile discovery/detail context.

## Decisions to Close
- [x] ✅ Production‑Ready **Scope update:** this TODO now actively covers tenant-public Account Profile Discovery + Detail visual polish.
- [x] ✅ Production‑Ready **Flutter-only execution:** no backend/API contract changes are allowed in this stream.
- [x] ✅ Production‑Ready **Account Available** is an access concern (not a new route). The existing route must be reachable by Account Users (tenant scope).
- [x] ✅ Production‑Ready **Profile types are registry-driven only**: remove fixed enums and treat `profile_type` as a string sourced from `/api/v1/environment`. Filtering/labels must be derived from the registry, not hardcoded lists.
- [x] ✅ Production‑Ready **Discovery hierarchy decision:** no standalone textual Hero in MVP; use `Tocando agora` + `Perto de você` + feed composition.
- [x] ✅ Production‑Ready **Discovery heading/layout:** section title must be `Descubra` with category chips directly below, single-select.
- [x] ✅ Production‑Ready **Search-mode visibility:** when search mode is active, `Tocando agora`/`Perto de você`/`Curadores` and `Descubra`/chips are hidden; before first query, show dedicated search-empty prompt state.
- [x] ✅ Production‑Ready **Loading quality bar:** search/filter changes must not trigger full-screen flicker/reset; use scoped loading feedback.

## Out of Scope
- Account Profile create/manage/admin/workspace flows.
- Backend/API/schema changes.
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

### A4) Favorites persistence (backend later)
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
- [ ] ⚪ Fix discovery filter header overflow by adjusting header extent/padding so registry-driven chips render without RenderFlex errors.

### B7) Registry-driven types (no fixed enums)
- [x] ✅ Production‑Ready Replace `AccountProfileType` enum with registry-driven string types sourced from `/api/v1/environment`.
- [x] ✅ Production‑Ready Update Account Profile model + repository contracts to accept `profile_type` as string.
- [x] ✅ Production‑Ready Remove static filter chip type lists; hydrate chips from registry + available results only.
- [x] ✅ Production‑Ready Provide UI-safe fallback (generic icon/label) when a registry type lacks UI metadata.
- [x] ✅ Production‑Ready Update tests to use registry-provided type strings.

### B8) Admin profile type error visibility (testable)
- [x] ✅ Production‑Ready Render profile type load errors in a dedicated widget with a stable Key so integration tests can assert auth/tenant failures.

### B9) Tenant Public Account Profile polish
- [ ] ⚪ Polish Account Profile Discovery visual hierarchy (header, filters, cards, spacing).
- [x] ✅ Production‑Ready Keep Discovery top structure with `Tocando agora` + `Perto de você` (without textual Hero block).
- [ ] ⚪ Keep Discovery heading text as `Descubra`.
- [ ] ⚪ Place category chips directly below `Descubra` using pill styling and single-select behavior.
- [x] ✅ Production‑Ready When `Tocando agora` has multiple entries, render as carousel (reuse tenant_public carousel primitives).
- [ ] ⚪ Hide `Tocando agora`/`Perto de você`/`Curadores` in search mode, keeping result list only.
- [ ] ⚪ Hide `Descubra` heading + chips in search mode; pre-query state must be prompt-only (no grid/results).
- [ ] ⚪ Polish Account Profile detail visual hierarchy (hero, tabs/modules, CTAs, empty/error states).
- [ ] ⚪ Polish profile-related invite/events entry CTA states so transitions stay visually clear.
- [ ] ⚪ Validate discovery/detail layouts on common mobile breakpoints.
- [ ] ⚪ Refine Discovery loading flow to avoid full-screen jitter during query/filter reloads.

### B10) Discovery `Tocando agora` reliability (TDD hardening)
- [x] ✅ Production‑Ready Add explicit coverage matrix for `live_now_only` pipeline: DAL query param -> DTO parse -> repository mapping -> controller stream -> screen rendering.
- [x] ✅ Production‑Ready Add controller regression test proving `DiscoveryScreenController` still loads live-now when `ScheduleRepositoryContract` is registered after controller construction (runtime DI order hardening).
- [x] ✅ Production‑Ready Add widget test for `DiscoveryScreen` asserting `Tocando agora` is rendered when live-now stream contains artist-backed live items.
- [x] ✅ Production‑Ready Lock MVP behavior that keeps `Tocando agora` hidden when live-now payload has no artists.
- [x] ✅ Production‑Ready Add DTO contract test using current real `agenda?live_now_only=1` payload shape (including `location` object + `event_id`/`occurrence_id`) and assert domain mapping succeeds.
- [x] ✅ Production‑Ready Keep live-now automated confidence deterministic (fixture + unit/widget pipeline); e2e against real data is optional/manual and must not be the default gate.

#### B10 Coverage Matrix (Deterministic)
- DAL query param forwarding: `test/infrastructure/dal/laravel_schedule_backend_test.dart` (`fetchEventsPage forwards live_now_only query parameter`).
- DTO parse compatibility (event + page): `test/infrastructure/dal/dto/schedule/event_dto_test.dart`, `test/infrastructure/dal/dto/schedule/event_page_dto_test.dart`.
- Repository forwarding/mapping: `test/infrastructure/repositories/schedule_repository_test.dart` (`getEventsPage forwards liveNowOnly to backend`).
- Controller runtime + DI order hardening: `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart` (`registered after controller construction` case).
- Screen rendering gate: `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart` (`DiscoveryScreen renders "Tocando agora"` widget test).

---

## C) Acceptance Criteria
- [ ] ⚪ Discovery screen has clear, consistent visual hierarchy with stable filters/cards.
- [ ] ⚪ Discovery top hierarchy is implemented via `Tocando agora` + `Perto de você`, without textual Hero block.
- [ ] ⚪ `Descubra` heading + chip composition is present, with single-select chip behavior.
- [ ] ⚪ Search mode hides top sections and keeps results list as primary content.
- [ ] ⚪ Search mode also hides `Descubra` + chips and uses a dedicated prompt state before first query.
- [ ] ⚪ Account Profile detail screen has clear module hierarchy and CTA affordance.
- [ ] ⚪ Profile-related event/invite entrypoints remain clear and visually coherent.
- [ ] ⚪ Discovery loading/search/filter transitions are stable and non-jittery.
- [ ] ⚪ No backend/API changes are required for these outcomes.
- [x] ✅ Production‑Ready `Tocando agora` remains visible whenever backend returns at least one live event with valid artist data for current discovery context.
- [x] ✅ Production‑Ready `Tocando agora` remains hidden when live-now payload has no artists.
- [x] ✅ Production‑Ready Test suite catches regression where artist-backed live-now data exists but Discovery UI does not render the section.

## D) Definition of Done
- [ ] ⚪ B1/B6/B9 pending account-profile polish tasks are complete.
- [ ] ⚪ Scope alignment is consistent with `TODO-v1-targeted-visual-polish.md`.
- [ ] ⚪ Admin/workspace account-profile management remains deferred to VNext TODO streams.
- [x] ✅ Production‑Ready Discovery filters + cards render types strictly from registry (no enum/static type lists).

## E) Validation Steps
- [ ] ⚪ Manual smoke: discovery list/filter/card states on mobile breakpoints.
- [ ] ⚪ Manual smoke: `Tocando agora` + `Perto de você` hierarchy without textual Hero block.
- [ ] ⚪ Manual smoke: `Descubra` heading and chip row positioning/selection behavior.
- [ ] ⚪ Manual smoke: search button toggles result-only mode (sections hidden).
- [ ] ⚪ Manual smoke: in search-active and empty query, `Descubra`/chips are not rendered and prompt-only state is visible.
- [ ] ⚪ Manual smoke: loading behavior during search/filter changes is stable (no full-page flicker).
- [ ] ⚪ Manual smoke: account profile detail hero/tabs/CTA states (loading/empty/content/error).
- [ ] ⚪ Manual smoke: event/invite entrypoints into discovery/detail remain intact.
- [x] ✅ Production‑Ready Automated: run discovery live-now hardening suites (controller/widget/DTO + repository/DAL contract tests) before promotion.
