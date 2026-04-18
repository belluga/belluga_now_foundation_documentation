# TODO (V1): Pre-MVP Web Small Fixes Intake

**Completed routing note (2026-04-17):** every issue captured in this intake was routed into a dedicated tactical TODO or a completed lane. This file remains a historical routing record only and is no longer an active execution/intake lane.

**Status:** Completed
**Current delivery stage:** `Completed`
**Qualifiers:** `Routed-Out`, `Historical-Reference`
**Next exact step:** Use the routed target TODOs for any remaining implementation. Open a fresh intake TODO only if a new batch of tenant-public web issues needs triage.
**Owners:** Delphi + Web Team + Flutter Team
**Objective:** Maintain one canonical intake ledger for small pre-MVP web fixes so issues can be added one by one without losing alignment with the approved V1 web posture: promotional/read-only tenant-public web with app handoff, tester-waitlist boundary at `/baixe-o-app`, and no web trust-action or auth-continuation expansion.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `small`
**Checkpoint Policy:** lightweight intake review per issue; any issue that becomes independently testable, cross-module, or contract-affecting must be split into its own tactical TODO before implementation.
**Primary execution profile:** `Strategic / CTO-Tech-Lead`
**Active technical scope:** `web`

**Purpose:** issue intake and scoping ledger for pre-MVP web fixes.
**This TODO is not:** blanket implementation authority for unspecified issues, a backlog for new web capabilities, or permission to bypass issue-by-issue approval.
**Code-touch boundary:** no code from this ledger alone. Each concrete issue must be refined before implementation starts.
**Direct-to-TODO rationale:** safe. The user asked for a durable place to collect web issues one by one before execution; this artifact exists to preserve scope and issue history without authorizing unnamed implementation work.
**Last confirmed truth:** `2026-04-08` canonical docs still define pre-MVP web as promotion/read-only, with app handoff for hard/auth gates, tester-waitlist variant active on `/baixe-o-app`, and the broader web-to-app conversion gate already tracked separately in `TODO-store-release-web-to-app-conversion-gate.md`.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route / Surface Family | Host Context | EnvironmentType | Main Scope | Subscope | Current V1 Posture |
| --- | --- | --- | --- | --- | --- |
| `/` tenant-public web | tenant host web | `tenant` | `tenant_public` | `n/a` | promotional/read-only |
| `/invite?code=...` and `/convites?code=...` web landing | tenant host web / compatible public host | `tenant` | `tenant_public` | `n/a` | preview-first, read-only, app handoff |
| `/baixe-o-app` | tenant host web | `tenant` | `tenant_public` | `n/a` | canonical promotion boundary with tester-waitlist variant |
| direct public detail routes (`/parceiro/:slug`, `/agenda/evento/:slug`, `/static/:assetRef`, `/mapa`, `/mapa/poi`) | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted public read surfaces under current V1 rules |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for tenant-public web route allowlist, app-promotion boundary behavior, and current tester-waitlist implementation notes.
- `invite_and_social_loop_module.md`: authoritative for invite landing, read-only web conversion posture, and app-owned invite acceptance semantics.
- `onboarding_flow_module.md`: authoritative where invite/onboarding handoff semantics matter.

### Decision Consolidation Targets

- Keep durable web-posture decisions in `foundation_documentation/modules/flutter_client_experience_module.md` and `foundation_documentation/system_roadmap.md` only if a future issue proves the canonical docs themselves are wrong or incomplete.
- Keep invite-boundary decisions in `foundation_documentation/modules/invite_and_social_loop_module.md` only when invite/web handoff rules materially change.
- This intake TODO should only carry issue tracking, triage decisions, and links to any future split tactical TODOs.

---

## References

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/system_roadmap.md` section `Web-to-App Promotion Policy`
- `foundation_documentation/screens/app_promotion_tester_waitlist.md`
- `foundation_documentation/screens/prototype_data.md` section `2.4.1. Tela de Promoção Web para Beta Tester (/baixe-o-app)`
- `foundation_documentation/policies/scope_subscope_governance.md`

---

## Scope (Issue Intake Only)

- Track small, issue-by-issue fixes for the current pre-MVP web experience.
- Accept issues related to visual polish, copy, layout, deterministic navigation, promotion-boundary continuity, read-only public route behavior, and tester-waitlist UX.
- Preserve the current pre-MVP web posture: promotional/read-only web, app handoff for trust/auth gates, no web-side mutation expansion.
- Record each issue with impact, touched surfaces, and whether it still qualifies as a small fix.

## Out of Scope

- New web capabilities, web-auth continuation, workspace features, or trust-action mutations on web.
- Broad IA redesigns or cross-stack contract changes hidden inside “small fixes.”
- Using this intake TODO as direct implementation authority for multiple unrelated issues at once.

---

## Frozen Baseline

- `W-01`: Pre-MVP web remains promotional/read-only; this intake lane must not be used to expand web into a second full client.
- `W-02`: Hard/auth gates reached on web still hand off to app promotion/open-app flow, not web login continuation.
- `W-03`: `/baixe-o-app` remains the canonical web-to-app promotion boundary and currently hosts the approved tester-waitlist variant.
- `W-04`: Small issues may be accumulated here, but implementation must stay issue-by-issue. If an issue introduces a new independently testable behavior or contract conversation, it must be split into a dedicated tactical TODO before coding.
- `W-05`: This intake lane is allowed to stay no-code until a concrete issue is selected and approved for execution.

---

## Intake Workflow

For each issue the user brings:

1. Record the issue under `Issue Intake Register`.
2. Classify it as:
   - `Small Fix - stays in intake lane`
   - `Needs dedicated tactical TODO`
   - `Out of scope for pre-MVP web posture`
3. If it stays in this lane, refine:
   - scope
   - out of scope
   - affected route/surface
   - expected validation
   - approval need
4. If it needs its own tactical TODO, create/link that TODO and mark the register entry as split out.

---

## Issue Intake Register

| ID | Status | Summary | Route / Surface | Classification | Notes |
| --- | --- | --- | --- | --- | --- |
| `WEB-001` | `Routed` | Hide the `Sobre` tab on event detail when the event has no real `content`, instead of rendering fallback/default text. | `/agenda/evento/:slug` immersive event detail | `Needs dedicated tactical TODO (existing)` | Routed into `TODO-v1-immersive-event-detail-dynamic-profile-category-tabs.md` as an event-detail follow-up: blank/effectively empty `event.content` must omit `Sobre` and must not show fallback prose. |
| `WEB-002` | `Routed` | Changing the Home radius should visibly trigger a repository-backed refresh of Home events, with loading feedback on the radius button while the refresh is in progress. | tenant Home agenda radius sheet/button | `Needs dedicated tactical TODO (existing)` | Routed into `TODO-v1-home-agenda-canonical-stream-ownership-hardening.md`. That lane already owns explicit repository-backed Home refresh semantics and now also carries the radius-button loading-feedback follow-up. |
| `WEB-003` | `Routed` | Account Profile empty-state fallback (`Mais sobre este perfil`) should become a favorite invitation when there is no about content and no events. | `/parceiro/:slug` account-profile detail no-sections fallback | `Needs dedicated tactical TODO (existing)` | Routed into `TODO-v1-screen-public-account-profile-detail-polish.md` with the approved CTA copy: `Favorite para ser avisado das novidades sobre {account profile name}.` |
| `WEB-004` | `Routed` | In map event-filter results, the next event should be listed first by start time instead of distance. | map filter-results dock / event-filter ordering | `Needs dedicated tactical TODO (existing, contract supersede)` | Routed into `TODO-v1-map-visuals.md`. The stale distance-first note in that lane must be superseded for event-filter contexts only; non-event ordering remains unchanged unless explicitly widened later. |
| `WEB-005` | `Routed` | Map POI hydration is still only correct for events. `account_profile` and `static/assets` are not hydrating into the selected POI/card flow with the same parity. | map marker tap -> selected POI + selected card hydration (`account_profile`, `static`) | `Needs dedicated tactical TODO (existing, regression)` | Routed into `TODO-v1-map-visuals.md` as an open regression: restore `account_profile` and `static/assets` hydration so both the promoted selected POI and the rendered card follow the same enriched pattern already working for events. |
| `WEB-006` | `Routed` | Desktop browsers should render the tenant-public web app inside a centered mobile-width frame instead of stretching shared mobile-first screens full width. | shared tenant-public web route shell (`/`, `/descobrir`, `/parceiro/:slug`, `/agenda/evento/:slug`, `/invite`, `/convites`, `/baixe-o-app`, `/mapa`, `/mapa/poi`, `/location/permission`, `/profile`) | `Needs dedicated tactical TODO (new)` | Routed into `TODO-v1-tenant-public-web-desktop-mobile-frame.md` because the change belongs at the shared Flutter route/app-shell boundary and affects multiple shared routes, not one browser-only screen. |
| `WEB-007` | `Routed` | Web bootstrap branding continuity is broken: the HTML splash logo can drift from the current tenant branding, the first Flutter frame swaps to a different asset family, the splash handoff flashes the wrong background color, and the progress bar can disappear against the brand background. | shared tenant-public web bootstrap (`web/index.html`, `flutter_bootstrap.js`, Flutter `InitScreen`) | `Needs dedicated tactical TODO (new)` | Routed into `TODO-v1-web-bootstrap-branding-continuity.md` because the issue spans the shared HTML boot layer, the Flutter init route, and the canonical tenant-branding bootstrap contract. |

---

## Issue Detail Notes

### `WEB-001` - Event detail should not show `Sobre` when content is absent

- **User-reported issue:** the pre-MVP web event screen currently shows the `Sobre` tab with default/fallback content when no proper event description exists.
- **Observed implementation path:** `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart` always inserts the `Sobre` tab, and `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_info_section.dart` renders `Sem descrição disponível.` when `event.content` is empty.
- **Canonical contract touched:** `foundation_documentation/modules/flutter_client_experience_module.md` and `foundation_documentation/modules/events_module.md` currently describe `Sobre` as rendering `event.content` HTML, but they do not currently define the empty-content behavior explicitly.
- **Why this is not safely “web-only” yet:** the immersive event-detail route is shared across platforms, so changing tab presence likely affects both app and web unless the implementation becomes platform-conditional.
- **Recommended classification:** `Needs dedicated tactical TODO`.

#### Proposed scope if split later

- hide the `Sobre` tab when `event.content` is missing or effectively empty;
- preserve the existing order/behavior of the remaining tabs;
- avoid fallback prose like `Sem descrição disponível.` when the tab is absent;
- validate that the tab bar, active-tab selection, swipe behavior, and footer behavior remain stable without `Sobre`.

#### Out of scope if split later

- rewriting event-content authoring or backend payload generation;
- redesigning the immersive detail shell;
- broader dynamic-tab policy changes outside the empty-content case.

#### Expected validation if split later

- targeted widget tests for immersive event detail with and without `event.content`;
- manual smoke on web for `/agenda/evento/:slug` with:
  - populated content;
  - empty/missing content;
  - remaining tabs still navigable and visually stable.

### `WEB-002` - Home radius change should refresh Home events with visible loading feedback

- **User-reported issue:** after changing the radius from the Home bottom sheet, Home events should refresh through the canonical repository path, and the radius button should show a loading state during that refresh.
- **Observed implementation path:** `TenantHomeAgendaController.setRadiusMeters()` already persists the selected radius, updates `radiusMetersStreamValue`, and schedules `_refresh(preserveCurrentResults: true)`. `_refresh()` then calls `_fetchAgenda()` which reads from `_scheduleRepository.loadHomeAgenda(...)`.
- **Canonical contract touched:** `foundation_documentation/modules/agenda_and_action_planner_module.md` decisions `AGD-05` and `AGD-06`, plus `foundation_documentation/modules/tenant_home_composer_module.md` decision `HOM-05`, all of which treat Home radius as a repository-backed Home-only preference with backend-authoritative geo filtering.
- **Why this is not safely “web-only” yet:** the affected controller/widget path is the shared tenant Home agenda implementation, not an isolated browser-only layer.
- **Recommended classification:** `Needs dedicated tactical TODO`.

#### Proposed scope if split later

- confirm the Home radius flow refreshes canonical repository-backed Home agenda data after radius changes;
- if the current refresh path is already correct, harden the UI feedback so the radius action enters a visible loading state until refresh settles;
- preserve the existing Home-only radius semantics and avoid introducing cross-surface radius unification;
- keep refresh behavior controller-owned and repository-backed rather than widget-local.

#### Out of scope if split later

- changing agenda/search/discovery shared radius policy;
- introducing local client-side filtering outside backend/repository rules;
- redesigning the full Home agenda loading model beyond the radius-triggered refresh experience.

#### Expected validation if split later

- targeted tests for radius change -> repository-backed refresh flow;
- widget/state validation for radius action loading feedback;
- manual smoke on Home confirming:
  - radius selection persists;
  - Home events visibly refresh;
  - the radius action shows in-flight feedback without clearing the whole section unnecessarily.

### `WEB-003` - Account Profile no-sections fallback should invite favorite instead

- **User-reported issue:** when an Account Profile has no events and no about content, the screen shows `Mais sobre este perfil`; this should be replaced with an invitation to favorite the profile.
- **Observed implementation path:** `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart` renders `_buildNoSectionsFallback()` with the title `Mais sobre este perfil` plus institutional fallback copy. The same screen already exposes a favorite footer/button path through `_favoriteFooter(...)`.
- **Canonical / tactical context touched:** this is account-profile detail behavior and fits the already active `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-public-account-profile-detail-polish.md` stream better than the pre-MVP web intake lane. That TODO already tracks footer fallback behavior, including decision `D-22`.
- **Why this is not safely “web-only” yet:** `/parceiro/:slug` is a shared tenant-public detail route. This fallback behavior applies across platforms unless explicitly made platform-specific.
- **Recommended classification:** `Needs dedicated tactical TODO (existing)` and should be attached to `TODO-v1-screen-public-account-profile-detail-polish.md`.

#### Proposed scope if linked later

- replace the current no-sections fallback copy/card with a favorite-oriented CTA when the profile is favoritable and not yet favorited;
- preferred CTA copy: `Favorite para ser avisado das novidades sobre {account profile name}.`;
- keep the empty-state deterministic when the profile is already favorited or not favoritable;
- preserve account-profile detail route behavior and existing auth-wall favorite handling.

#### Out of scope if linked later

- redesigning all account-profile detail tabs/modules;
- changing backend payload rules for about/events availability;
- introducing new social actions beyond the existing favorite path.

#### Expected validation if linked later

- targeted widget tests for account-profile detail with:
  - no sections + favoritable + not favorited;
  - no sections + already favorited;
  - no sections + not favoritable;
- manual smoke on `/parceiro/:slug` confirming the empty-state CTA routes through the existing favorite/auth-wall flow correctly.

### `WEB-004` - Map event filters should order by next start time, not distance

- **User-reported issue:** when a map filter produces event results, the list should show the next event first. In this event-only context, ordering should use event start time instead of distance.
- **Observed implementation path:** `lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart` uses `orderedPoisByDistance(...)` for filtered results, and both `_focusLeadingFilteredResult()` and `deckPoisForSelectedPoi(...)` rely on that distance-first ordering.
- **Current documented contract that this would change:** the active map visuals TODO says filtered POIs are listed `ordered by nearest distance first`, and the map module still documents `sort` modes such as `distance` and `time_to_event` without a special event-filter rule.
- **Why this is not safely “web-only” yet:** the map filter-results dock is shared tenant-public map behavior, not a browser-only view layer.
- **Recommended classification:** `Needs dedicated tactical TODO (existing, contract supersede)` and should likely attach to `TODO-v1-map-visuals.md` or a tighter map-filter ordering slice.

#### Proposed scope if linked later

- add/restore event-aware ordering for map filter-result docks when the active filter context is event-specific;
- sort event POIs by the next start time first, not distance, for that event-filter context;
- preserve existing distance-first behavior for non-event POIs unless a broader contract update is explicitly approved;
- ensure focus-leading-result logic and deck order use the same event-aware ordering rule.

#### Out of scope if linked later

- redesigning the whole map filter model;
- changing non-event filter ordering without explicit approval;
- broad backend query redesign unless the current payload lacks the timing fields needed for deterministic client ordering.

#### Expected validation if linked later

- targeted tests proving event-filter results order by nearest upcoming start time;
- regression tests preserving current non-event ordering behavior where applicable;
- manual smoke on the map filter dock confirming:
  - event filters prioritize the next event first;
  - focus preview and card order stay aligned;
  - non-event filters do not regress unintentionally.

### `WEB-005` - Map `account_profile` and `static/assets` are still not hydrating with event parity

- **User-reported issue:** on the map, tapping a POI should open the card and hydrate it with richer details. Events still hydrate correctly, but `account_profile` and `static/assets` are still not following that same runtime pattern.
- **Regression guard clarified:** `event` remains the reference behavior. The fix must restore parity for `account_profile` and `static/assets`, not degrade the already-correct event path.
- **Observed implementation path:** `lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart` routes marker selection through `_selectPoiFromMarkerTap() -> _hydratePoiForSelection() -> _poiRepository.ensurePoiHydrated(poi) -> _resolveHydratedPoi(poi)`.
- **Current map-lane contract already in place:** `foundation_documentation/todos/active/vnext/TODO-v1-map-visuals.md` decision `D-42` says account-profile POIs may hydrate through the existing account-profile repository by slug, and `D-83` says POI hydration ownership is repository-owned and shared across the selected-card/deck/runtime.
- **Concrete runtime mismatch found:** `lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart` already treats `account_profile`, `accountprofile`, and `partner` as partner/account-profile POIs for share/navigation semantics, but `lib/infrastructure/repositories/poi_repository.dart` only allows partner hydration when `poi.refType.trim().toLowerCase() == 'account_profile'`.
- **Clarified expected behavior (`2026-04-09`):** fixing this is not just “make the card prettier.” The canonical selected POI that the controller promotes and the card that renders from it must both receive the same hydrated avatar/cover/content enrichment, mirroring the pattern already observed on event POIs.
- **Canonical contract tension:** `foundation_documentation/modules/map_poi_module.md` and `foundation_documentation/modules/flutter_client_experience_module.md` still define the canonical `ref_type` as `account_profile`, `event`, or `static`, so this currently reads as a shared map regression or alias-drift problem, not a web-only cosmetic issue.
- **Recommended classification:** `Needs dedicated tactical TODO (existing, regression)` and should attach to the active map lane rather than remain in this intake ledger.

#### Proposed scope if linked later

- confirm the exact `account_profile` and `static` `ref_type` values currently reaching the map selection/hydration path;
- restore deterministic selected-card hydration for `account_profile` and `static/assets` without weakening the canonical typed-reference contract;
- ensure the promoted selected POI itself is enriched with hydrated avatar/cover/content fields, not only the rendered card surface;
- align marker tap, selected card, share/navigation helpers, and repository hydration so they do not disagree about which POI types/tokens are accepted;

### `WEB-007` - Web bootstrap branding continuity is broken

- **User-reported issue:** after updating the tenant light/dark logo, the web loading experience still shows the wrong branding and the handoff from the JavaScript loader to the Flutter loader flashes the wrong background. The progress bar can also visually disappear because it uses the same color family as the splash background.
- **Observed implementation path:** `web/index.html` currently hardcodes the splash image markup to `logo-light.png` for both layers, applies only partial branding state to the HTML splash, and removes the splash as soon as Flutter host nodes appear. `lib/presentation/shared/init/screens/init_screen/init_screen.dart` then renders `mainIcon*` instead of `mainLogo*`, which guarantees an asset-family jump even when tenant branding resolves correctly.
- **Expanded continuity issue confirmed during intake:** the background flash is part of the same bug. The HTML splash uses the tenant primary color, but the DOM splash can be removed before the first visibly branded Flutter init frame is on screen. In the same boot UI, the progress track/fill also fail contrast because the fill reuses the same `primary` color as the page background and the track alpha is too faint against bright brand colors.
- **Canonical contract touched:** `foundation_documentation/modules/flutter_client_experience_module.md` already defines environment bootstrap, tenant branding payload ownership, and startup behavior, but it does not yet freeze a bootstrap visual continuity contract across HTML splash + Flutter init.
- **Why this is not safely “web-only” inside the intake lane:** the fix spans the shared HTML bootstrap, Flutter `InitScreen`, and enduring branding/bootstrap behavior. It needs one dedicated tactical TODO rather than a one-line intake note.
- **Recommended classification:** `Needs dedicated tactical TODO (new)`.

#### Proposed scope if split later

- resolve the HTML splash logo from the canonical tenant branding contract instead of hardcoded/stale logo assumptions;
- align the Flutter `InitScreen` to the same logo family so the first Flutter frame does not swap from logo to icon;
- keep the tenant brand background continuous through the HTML-to-Flutter handoff and remove the splash only through a fade-based readiness path;
- make the bootstrap progress indicator contrast-safe against bright and dark tenant brand backgrounds.

#### Out of scope if split later

- backend/API branding contract changes;
- tenant-admin branding editor changes;
- broader redesign of non-bootstrap tenant-public screens;
- landlord-specific UX redesign beyond preserving shared bootstrap compatibility.

#### Expected validation if split later

- targeted Flutter widget coverage for `InitScreen` branding selection;
- local web build and browser smoke for tenant bootstrap continuity;
- analyzer clean after the shared bootstrap/init changes.
- preserve the already-correct event hydration pattern as the behavioral reference.

#### Out of scope if linked later

- redesigning the full map card visual system;
- changing backend map contracts casually if the real issue is client alias drift;
- widening the accepted typed-reference vocabulary without documenting and testing the decision.

#### Expected validation if linked later

- targeted tests for `account_profile` and `static/assets` selection -> repository hydration -> selected-POI enrichment -> selected-card rendering;
- regression coverage for canonical `account_profile` / `static` typed references and any intentionally supported legacy aliases;
- manual smoke on the map confirming:
  - `account_profile` POI tap promotes a hydrated selected POI and opens a hydrated card;
  - `static/assets` POI tap promotes a hydrated selected POI and opens a hydrated card;
  - `event` POI hydration still works as the reference behavior.

### `WEB-006` - Desktop web should keep the mobile app boundary instead of stretching full width

- **User-reported issue:** on desktop browsers, the current tenant-public web experience expands shared mobile-first screens across the full browser width, which makes the app look like an unbounded desktop site instead of the intended mobile-first product.
- **Observed implementation path:** the app root currently builds `MaterialApp.router` without a route-aware desktop-web width wrapper, so tenant-public screens inherit the full browser width unless they already add their own local constraints.
- **Canonical contract touched:** `foundation_documentation/modules/flutter_client_experience_module.md` is authoritative for tenant-public web route allowlist, route ownership, and controller-first presentation rules, but it does not yet freeze a desktop-web mobile-frame policy for the shared public routes.
- **Why this is not safely “web-only” inside one screen:** the visual problem comes from a shared app-shell/layout boundary, not from one isolated page. The safest implementation point is a shared Flutter presentation wrapper that can preserve the same child/widget boundaries across the affected tenant-public routes.
- **Recommended classification:** `Needs dedicated tactical TODO (new)`.

#### Proposed scope if linked later

- add a shared desktop-web frame for tenant-public and promotion-boundary routes so wide browser viewports center the existing mobile-first UI inside a mobile-width container;
- preserve the current widget/controller boundaries by wrapping the existing route child instead of redesigning individual screens;
- keep narrow/mobile web widths unchanged;
- keep admin, landlord, and workspace shells out of scope unless explicitly widened later.

#### Out of scope if linked later

- creating dedicated desktop layouts for tenant-public routes;
- changing backend/API behavior, route ownership, or guard semantics;
- redesigning individual map/detail/auth/promotion screens as standalone desktop pages;
- narrowing tenant-admin or landlord-area shells.

#### Expected validation if linked later

- targeted Flutter tests for the shared route/frame classifier and width-wrapper behavior;
- analyzer validation for the shared presentation change;
- manual browser smoke across representative wide-screen routes:
  - Home/Discovery;
  - public detail;
  - invite/promotion;
  - map/location-permission.

---

## Acceptance Criteria

- There is one canonical active TODO for pre-MVP web small-fix intake.
- The TODO preserves the current read-only/promotional web posture instead of silently widening it.
- Each future issue can be appended and triaged deterministically.
- The ledger makes clear when an issue stays small versus when it must split into its own tactical TODO.

## Routing Outcome (`2026-04-08`)

- `WEB-001` -> `TODO-v1-immersive-event-detail-dynamic-profile-category-tabs.md`
- `WEB-002` -> `TODO-v1-home-agenda-canonical-stream-ownership-hardening.md`
- `WEB-003` -> `TODO-v1-screen-public-account-profile-detail-polish.md`
- `WEB-004` -> `TODO-v1-map-visuals.md`
- `WEB-005` -> `TODO-v1-map-visuals.md`
- `WEB-006` -> `TODO-v1-tenant-public-web-desktop-mobile-frame.md`

## Definition of Done For This Intake Setup

- The intake TODO now lives in `foundation_documentation/todos/completed/` as a historical routing record after the issue batch was fully split into dedicated lanes.
- Canonical module anchors and web-posture guardrails are recorded.
- The issue register is ready to receive issues one by one.
