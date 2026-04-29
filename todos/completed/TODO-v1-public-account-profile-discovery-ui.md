# TODO (V1): Public Account Profile Discovery UI

**Scope authority note (2026-04-17):** this TODO owns only tenant-public Public Account Profile discovery/listing behavior on `/descobrir` and the discovery-side CTA/entrypoint presentation that launches `/parceiro/:slug`. Standalone Public Account Profile detail-screen polish is owned by `foundation_documentation/todos/completed/TODO-v1-screen-public-account-profile-detail-polish.md`.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Objective:** Deliver tenant-public Public Account Profile discovery polish on `/descobrir`, without adding a Discovery Hero textual block or backend/admin contract changes.

**Closeout note (2026-04-17):** residual Discovery polish items were reviewed against the shipped `/descobrir` behavior and explicitly closed by product decision. The current screen is accepted as launch-ready; no additional V1 Discovery work remains in this stream beyond the already-deferred VNext items.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-first-release.md`
- `foundation_documentation/todos/completed/TODO-v1-account-profile-implementation.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-public-account-profile-detail-polish.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-account-profile-media-uploads.md`
- `foundation_documentation/todos/completed/TODO-vnext-account-profile-types.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
- `lib/presentation/tenant/partners/models/partner_profile_config.dart`

---

## Terminology
- `User Profile` means the authenticated self/profile route `/profile`; it is not owned by this TODO.
- `Public Account Profile` means the tenant-public identity surface for account-managed entities, reached from Discovery/Favorites/Map/Event-linked entrypoints and resolved to `/parceiro/:slug`.

## Scope (MVP)
- Polish `Public Account Profile Discovery` in `tenant_public`.
- Improve visual hierarchy, spacing, state clarity, and discovery-side CTA affordance for listing/search/filter flows on `/descobrir`.
- Keep existing registry-driven and endpoint-backed behavior intact (no backend/API contract changes).
- Do not add a Discovery Hero textual block in MVP.
- Use `Tocando agora` + `Perto de você` sections as Discovery top hierarchy.
- Keep discovery-launched entrypoints into `/parceiro/:slug` visually clear and behavior-compatible.

## Decisions to Close
- [x] ✅ Production‑Ready **Scope update:** this TODO now actively covers tenant-public Account Profile Discovery only; dedicated detail polish lives in `TODO-v1-screen-public-account-profile-detail-polish.md`.
- [x] ✅ Production‑Ready **Flutter-only execution:** no backend/API contract changes are allowed in this stream.
- [x] ✅ Production‑Ready **Account Available** is an access concern (not a new route). The existing route must be reachable by Account Users (tenant scope).
- [x] ✅ Production‑Ready **Profile types are registry-driven only**: remove fixed enums and treat `profile_type` as a string sourced from `/api/v1/environment`. Filtering/labels must be derived from the registry, not hardcoded lists.
- [x] ✅ Production‑Ready **Discovery hierarchy decision:** no standalone textual Hero in MVP; use `Tocando agora` + `Perto de você` + feed composition.
- [x] ✅ Production‑Ready **Discovery heading/layout:** section title must be `Descubra` with category chips directly below, single-select.
- [x] ✅ Production‑Ready **Search-mode visibility:** when search mode is active, `Tocando agora`/`Perto de você` and `Descubra`/chips are hidden; with an empty query, keep the base discovery grid visible and start filtering only after the user types.
- [x] ✅ Production‑Ready **Loading quality bar:** search/filter changes must not trigger full-screen flicker/reset; use scoped loading feedback.

## Out of Scope
- Standalone Public Account Profile detail visual polish and detail-screen presentation refinements; these now belong to `TODO-v1-screen-public-account-profile-detail-polish.md`.
- Detail-route loading/empty/error/content behavior after `/parceiro/:slug` opens.
- Account Profile create/manage/admin/workspace flows.
- Backend/API/schema changes.
- Full memberships/roles system.
- Store/commerce modules and external links.
- Account Workspace (post‑MVP).
- Generic additional account-profile-type expansion is no longer tracked as a separate active owner; future evolution should be capability-first and tied to concrete follow-up slices when needed.

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

### B1) Frozen detail-route contract context
- [x] ✅ Production‑Ready Keep the existing base page (Partner Detail) as the canonical Account Profile detail UI, with all remaining screen-level polish delegated to `TODO-v1-screen-public-account-profile-detail-polish.md`.
- [x] ✅ Production‑Ready Discovery launches the canonical detail route; no active detail-screen polish is owned here.

### B2) Type-driven configs (MVP)
- [x] ✅ Artist config (reduced tabs):
  - [x] ✅ Bio/summary + upcoming events (schedule).
- [x] ✅ Venue config (reduced tabs):
  - [x] ✅ `Sobre` (conditional) → `ProfileModuleId.richText`
  - [x] ✅ `Como Chegar` (always) → `ProfileModuleId.locationInfo` (map preview + route CTA)
  - [x] ✅ `Eventos` (always) → `ProfileModuleId.agendaList`
- [x] ✅ Exclude `externalLinks`, `supportedEntities`, commerce/store modules in MVP.

### B3) Post‑MVP type placeholders
- [ ] ⚪ Deferred to VNext: any future profile evolution should open under capability-specific or feature-specific TODOs once module requirements are explicit.

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
- [x] ✅ Production‑Ready Current discovery filter header behavior is accepted as launch-ready for V1; no overflow follow-up remains in this stream.

### B7) Registry-driven types (no fixed enums)
- [x] ✅ Production‑Ready Replace `AccountProfileType` enum with registry-driven string types sourced from `/api/v1/environment`.
- [x] ✅ Production‑Ready Update Account Profile model + repository contracts to accept `profile_type` as string.
- [x] ✅ Production‑Ready Remove static filter chip type lists; hydrate chips from registry + available results only.
- [x] ✅ Production‑Ready Provide UI-safe fallback (generic icon/label) when a registry type lacks UI metadata.
- [x] ✅ Production‑Ready Update tests to use registry-provided type strings.

### B8) Admin profile type error visibility (testable)
- [x] ✅ Production‑Ready Render profile type load errors in a dedicated widget with a stable Key so integration tests can assert auth/tenant failures.

### B9) Public Account Profile discovery polish
- [x] ✅ Production‑Ready Account Profile Discovery visual hierarchy (header, filters, cards, spacing) is accepted as launch-ready for V1.
- [x] ✅ Production‑Ready Keep Discovery top structure with `Tocando agora` + `Perto de você` (without textual Hero block).
- [x] ✅ Production‑Ready Keep Discovery heading text as `Descubra`.
- [x] ✅ Production‑Ready Place category chips directly below `Descubra` using pill styling and single-select behavior.
- [x] ✅ Production‑Ready When `Tocando agora` has multiple entries, render as carousel (reuse tenant_public carousel primitives).
- [x] ✅ Production‑Ready Hide `Tocando agora`/`Perto de você` in search mode, keeping the results grid as the primary content.
- [x] ✅ Production‑Ready Hide `Descubra` heading + chips in search mode; with an empty query, keep the unfiltered discovery grid visible until the user starts typing.
- [x] ✅ Production‑Ready Discovery-side entrypoints are accepted as sufficiently clear for V1 launch continuity.
- [x] ✅ Production‑Ready Discovery layouts and discovery-to-detail launch continuity are accepted as launch-ready for V1.
- [x] ✅ Production‑Ready Refine Discovery loading flow to avoid full-screen jitter during query/filter reloads.

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
- [x] ✅ Production‑Ready Discovery screen has clear, consistent visual hierarchy with stable filters/cards.
- [x] ✅ Production‑Ready Discovery top hierarchy is implemented via `Tocando agora` + `Perto de você`, without textual Hero block.
- [x] ✅ Production‑Ready `Descubra` heading + chip composition is present, with single-select chip behavior.
- [x] ✅ Production‑Ready Search mode hides top sections and keeps the results grid as primary content.
- [x] ✅ Production‑Ready Search mode also hides `Descubra` + chips and keeps the base discovery grid visible until the user starts typing.
- [x] ✅ Production‑Ready Detail-screen polish authority remains isolated in `TODO-v1-screen-public-account-profile-detail-polish.md` with no duplicate pending work left here.
- [x] ✅ Production‑Ready Discovery-side entrypoints remain clear and visually coherent for the accepted V1 launch behavior.
- [x] ✅ Production‑Ready Discovery loading/search/filter transitions are stable and non-jittery in the implemented controller flow.
- [x] ✅ Production‑Ready No backend/API changes are required for these outcomes.
- [x] ✅ Production‑Ready `Tocando agora` remains visible whenever backend returns at least one live event with valid artist data for current discovery context.
- [x] ✅ Production‑Ready `Tocando agora` remains hidden when live-now payload has no artists.
- [x] ✅ Production‑Ready Test suite catches regression where artist-backed live-now data exists but Discovery UI does not render the section.

## D) Definition of Done
- [x] ✅ Production‑Ready B6/B9 discovery/shared-surface tasks are complete for the accepted V1 scope.
- [x] ✅ Production‑Ready Scope alignment is consistent with discovery-only ownership here and detail-only ownership in `TODO-v1-screen-public-account-profile-detail-polish.md`.
- [x] ✅ Production‑Ready Admin/workspace account-profile management remains deferred to VNext TODO streams.
- [x] ✅ Production‑Ready Discovery filters + cards render types strictly from registry (no enum/static type lists).

## E) Validation Steps
- [x] ✅ Production‑Ready Manual acceptance: discovery list/filter/card states are accepted for the V1 launch surface.
- [x] ✅ Production‑Ready Manual acceptance: `Tocando agora` + `Perto de você` hierarchy without textual Hero block remains accepted for V1.
- [x] ✅ Production‑Ready Manual acceptance: `Descubra` heading and chip row positioning/selection behavior remain accepted for V1.
- [x] ✅ Production‑Ready Manual acceptance: search button toggles result-only mode (sections hidden).
- [x] ✅ Production‑Ready Manual acceptance: in search-active with an empty query, `Descubra`/chips are hidden, the base grid remains visible, and filtering begins only after typing.
- [x] ✅ Production‑Ready Manual acceptance: loading behavior during search/filter changes is stable (no full-page flicker).
- [x] ✅ Production‑Ready Manual acceptance: discovery-side entrypoints launch their expected routes without regressions for the accepted V1 scope.
- [x] ✅ Production‑Ready Manual doc review: remaining Account Profile detail-specific polish tasks live only in `TODO-v1-screen-public-account-profile-detail-polish.md`.
- [x] ✅ Production‑Ready Automated: run discovery live-now hardening suites (controller/widget/DTO + repository/DAL contract tests) before promotion.
