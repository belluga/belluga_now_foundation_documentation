# TODO (V1): Map Visuals

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Location-Origin-Feedback-Implemented`, `Belluga-Map-Surface-Implemented`, `Overlay-Split-Implemented`, `Filter-Result-Set-Continuity-Implemented`, `Event-Timing-Contract-Implemented`, `Event-Dominance-Backend-Implemented`, `Event-Details-Media-Hydration-Implemented`, `Static-Asset-Detail-Hydration-Implemented`, `Share-Invite-Semantics-Implemented`, `Public-Web-Metadata-Implemented`, `Build-and-Playwright-Validated-Locally`, `Inside-Range-Live-Burst-Validated`, `Manual-Tenant-Host-Smoke-Partial`
**Next exact step:** Run tenant-host manual smoke for the locally implemented `WEB-004`/`WEB-005` refinements (`Próximos eventos`, date-aware upcoming labels in the event-result dock, and restored `account_profile|static` selected-card hydration parity), then retarget the lane to the remaining selected-card/share follow-ups.
**Owners:** Flutter Team
**Objective:** Deliver the tenant-public map visual experience and interaction-state polish in Flutter, including a Belluga-owned map surface, `free_map -> flutter_map` migration, tray states, selected-POI presentation, and cluster behavior, without changing backend/API contracts.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `big`
**Checkpoint Policy:** section-by-section checkpoints:
1. map surface / plugin seam
2. overlay split (action row + tray + floating card)
3. marker memory + camera offset + cluster interaction
4. analyzer/test/build/playwright + decision adherence

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/map_poi_module.md`
- **Secondary:** `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/screens/modulo_mapa_e_mobilidade.md`

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-location-origin-feedback.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-safe-back-navigation.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

---

## Scope (Visual / IA Only)
- Redefine the tenant-public map interaction architecture around:
  - global bottom navigation,
  - local map action row,
  - adaptive lower tray,
  - independent lower-center selected-item card.
- Consolidate the remaining plugin-migration debt from `TODO-v1-map-frontend.md` into this lane:
  - replace `free_map` usage with `flutter_map`,
  - introduce a Belluga-owned map surface so presentation/controller code does not depend directly on plugin APIs.
- Define the authoritative state machine for:
  - default discovery,
  - filters expanded,
  - selected filter,
  - search mode,
  - selected POI,
  - selected event.
- Polish the selected-marker memory behavior after card close.
- Define the camera offset behavior when opening a selected item.
- Define V1 cluster interaction behavior and its relationship to card opening and marker memory.
- Define the location-origin visual feedback model for the map action row and the transient map notice, driven by the existing location-origin contracts.
- Freeze the selected-card secondary-action semantics by POI type:
  - `account_profile`: public share is allowed for anonymous and authenticated users and uses the same already-hydrated details/content path as the selected card.
  - `static`: public share is allowed for anonymous and authenticated users and uses the same already-hydrated details/content path as the selected card; the immersive detail screen is share-only and cover-only.
  - `event`: generic share is removed from the selected card and replaced by the canonical invite handoff/action.
- Keep route ownership in `EnvironmentType=tenant`, `main scope=tenant_public`.

## Out of Scope
- Backend/API contract changes.
- Tenant-admin map CRUD or settings redesign.
- Route/fallback policy redesign beyond the already-approved safe-back lane.
- New clustering algorithms or backend-side cluster payload redesign.
- New search/filter semantics outside the visual/interaction model.
- A generic market-facing map SDK; the Belluga-owned map surface must stay narrow and Belluga-specific.

---

## Decision Baseline (Frozen)
- `D-01`: `Bottom nav` remains global and fixed: `Home | Mapa | Perfil`.
- `D-02`: Map-local controls are separated from global navigation and live inside a single compact bottom dock plus a top-right location utility.
- `D-03`: The `Você` control lives in the top-right utility position so it does not compete with bottom exploration controls or the back button.
- `D-04`: Filter exploration no longer needs a dedicated action-row entrypoint. In V1 it lives as a compact floating backend-driven chip cluster, with overflow expansion only when the catalog would exceed two rows.
- `D-05`: The selected POI/event card is **not** hosted inside the tray; it opens as an independent `floating lower-center card`.
- `D-06`: Place and event selection reuse the same card architecture; badge/copy/CTA vary by item type only.
- `D-07`: Filter behavior is `single-select` / mutually exclusive; selecting one filter deselects the others and applies immediately.
- `D-08`: V1 does not require an `Aplicar filtros` step; selection is immediate and the active filter becomes the map context.
- `D-09`: The selected marker adopts `avatar/cover` memory for the **last selected individual POI only**.
- `D-10`: Closing the card preserves the selected-marker memory temporarily; any meaningful pan or zoom clears that visual memory and returns all markers to baseline.
- `D-11`: Opening a selected card must reposition the camera so the selected marker sits in an `upper-center safe zone`, not behind the card.
- `D-12`: The marker-to-card connector line is removed; marker highlight + camera offset + card placement are sufficient.
- `D-13`: View/plugin density clusters and exact-key stack entrypoints never open detailed cards directly in V1. Cluster/stack tap opens a local `POI picker popover` near the clicked marker, and only an explicit item choice enters the hydration-first selected-card flow.
- `D-14`: If a previously selected individual POI becomes absorbed into a cluster after pan/zoom, marker memory is cleared.
- `D-15`: Current generated mocks are structural references only; they do not define final visual language.
- `D-16`: The filter catalog remains backend-driven/dynamic in V1. Visual states must not hardcode category assumptions beyond generic selected/unselected/cluster semantics.
- `D-17`: In minimized discovery mode, visible filters render as icon-first compact floating chips; when only a small number of filters are visible, they should be visually centered instead of awkwardly left-aligned.
- `D-18`: Tapping empty map while a selected card is open closes the card but does not immediately clear the last-selected marker memory.
- `D-19`: Map location-origin feedback is a first-class part of `map visuals`, but it is executed through the dedicated sequential slice `TODO-v1-map-location-origin-feedback.md`.
- `D-20`: The map-local `Você` action must ultimately reflect the authoritative resolved origin state, not permission state alone.
- `D-21`: The remaining `free_map -> flutter_map` migration debt from `TODO-v1-map-frontend.md` is executed inside this lane, because camera/marker/interaction work is shared with the visual redesign.
- `D-22`: A Belluga-owned map surface is introduced to shield controller/presentation code from plugin APIs. It must stay narrow and Belluga-specific, not a generic map SDK.
- `D-23`: The Belluga-owned map surface owns only map-host/plugin-facing concerns:
  - camera commands,
  - camera state projection,
  - map interaction events,
  - marker/cluster render primitives.
  Business semantics remain outside this surface.
- `D-24`: Filter/search/discovery state, selected-item state, marker-memory rules, transient location notice semantics, and cluster business behavior remain controller/presentation responsibilities, not plugin-surface responsibilities.
- `D-25`: The first implementation seam is the bottom overlay split:
  - separate `action row + adaptive tray + floating selected card`
  - preserve existing controller-owned state
  - postpone plugin-facing camera/cluster refinements until this composition boundary is stable.
- `D-26`: The initial adapter behind the Belluga-owned map surface is `flutter_map`.
- `D-27`: `free_map` must not remain the source of truth for new map behavior after this lane is complete.
- `D-28`: Existing backend/runtime `exact-key stacks` are not the same as view/plugin density clusters in this lane. Exact-key stacks keep their current selector/list behavior so same-coordinate POIs remain reachable.
- `D-29`: The runtime map must not preserve parallel legacy filter surfaces. `FabMenu`, `main filters`, and any locally-derived expanded-filter affordances are removed from the active map lane so the only visible catalog is `filterOptions` from the backend contract.
- `D-30`: Applying a filter must never auto-select the first POI. The map may reframe to the first result, but the selected-card state remains user-driven.
- `D-31`: Filter-preview focus and selected-card focus are distinct camera behaviors. Filter preview may pan to the leading result without selection, while explicit POI selection uses a stronger upper-viewport anchor so the marker is not hidden behind the card.
- `D-32`: The floating filter cluster shows a handle only when the backend-driven filter catalog would exceed two visible rows. The same handle is the only expand/collapse affordance; there is no explicit `Fechar` CTA.
- `D-33`: Search is secondary to backend-driven filters in the bottom dock. In the resting state, the dock shows filters first and a smaller `Buscar` launcher separated by spacing so the two interaction families remain distinct.
- `D-34`: Entering search mode reuses the same dock surface. The resting `Buscar` launcher disappears and the dock transforms into the search sheet; closing search returns the dock to the resting filter-first layout.
- `D-35`: Transient status/loading feedback that belongs to the map dock must render above the dock, visually adapted to it, instead of overlapping the dock surface/content.
- `D-36`: Selected-card content and search-suggestion cards must be driven only by real `CityPoiModel` data. Invented category copy or placeholder editorial statements are not acceptable for the accepted V1 visual result.
- `D-37`: Search-dock cards are part of the same card-content redesign lane as the selected POI/event card; they are not a separate visual system.
- `D-38`: Cluster count must communicate the total item count directly. A cluster with four items must read as `4`, not `+3`.
- `D-39`: Selected-card/deck layout must remain stable under variable content length; card growth must not anchor incorrectly or break the carousel/runtime layout.
- `D-40`: Current map payload exposes only one `visual` slot (`icon` or `imageUri`); any `avatar + cover` treatment must either derive pragmatically from that single slot in V1 or wait for a future contract expansion.
- `D-41`: When an individual POI needs late hydration to improve factual card content or visual media, the click acknowledgment lives on the marker first. The marker enters a selected-loading state immediately, the map reframes to the upper-center safe zone, and the selected card opens only after hydration resolves or falls back.
- `D-42`: Account-profile POIs may hydrate through the existing account-profile repository by slug; this lane must treat that as an internal client-side enrichment path, not a backend contract change.
- `D-43`: Selected-POI hydration follows explicit race policy: duplicate taps on the same loading POI are dropped, newer POI taps are last-write-wins, and stale responses after clear/pan/new selection are ignored.
- `D-44`: Type labels in selected cards and search-dock cards are strictly factual/dynamic. They must come from runtime payload/hydration data only. There is no accepted fallback to `CityPoiCategory` enum names or technical `refType` values for user-facing type labels.
- `D-45`: Starting a new POI selection must immediately demote the previously selected POI/card state. The old card disappears at once, and the previously promoted marker returns to baseline while the new POI enters `selected-loading`.
- `D-46`: Exact-key stack/cluster entrypoints must not bypass late hydration. Choosing an item from a same-coordinate stack must follow the same hydration-first selection flow as an individual POI.
- `D-47`: `cluster/stack -> local POI picker popover -> POI tap -> loading marker -> hydrated selected card` is the approved V1 interaction path.
- `D-49`: Hydrated `account_profile` cards expose the avatar again in the factual header area, to the left of the profile name, while `cover` remains reserved for the hero area.
- `D-48`: The cluster/stack picker inventory is repository-owned through the existing `stackItems` stream. The controller only governs picker visibility and delegates item selection; it does not own a second canonical model list for the picker.
- `D-50`: When a backend filter is applied and the filter-results dock opens, the dock ordering is the authoritative result ordering for preview focus. Map reframing must target the first item shown in that dock, never an arbitrary/last matching POI.
- `D-51`: Minimizing the filter-results dock must not strand the user in an active-filter state. Tapping the same active filter chip again reopens the filter-results dock; tapping the chip close affordance still clears the filter.
- `D-52`: Filter-context navigation may use a dedicated filtered-result carousel when a user opens a result from an active filter set. This carousel is strictly scoped to the active filter result set and must not alter the approved cluster/stack picker behavior.
- `D-53`: Event POIs must expose usable temporal discovery data directly in the map POI contract. At minimum, Flutter must consume backend-owned `time_start`, `time_end`, and `is_happening_now` instead of requiring a second details fetch just to render event timing.
- `D-54`: Event timing presentation is shared across marker, search/filter-result cards, and selected event card. Backend owns temporal truth; Flutter formats it into user-facing labels such as `AGORA`, `Hoje {HH:mm}`, `Amanhã {HH:mm}`, or an explicit later date/time.
- `D-55`: For event discovery, the preferred visual rule is `AGORA` when `is_happening_now=true`; otherwise upcoming labels must be date-aware: `Hoje {HH:mm}` for same-day upcoming events, `Amanhã {HH:mm}` for next-day upcoming events, and explicit date/time for later upcoming events. The detailed card may show the full start/end range.
- `D-56`: Event-vs-place overlap precedence is backend-owned. When one event overlaps a place/local in the same micro-area, the event may dominate the visible marker/result entrypoint instead of surfacing a same-point stack.
- `D-57`: The approved event-dominance fallback radius is approximately `50m`, used only for `event + non-event local` overlap resolution. This is not a generic all-POI clustering rule.
- `D-58`: If two or more events fall into the same overlap area, event dominance does not collapse them into a single event marker; stack/cluster behavior remains available for multi-event cases.
- `D-59`: Very-close local POIs that overlap each other without an event-dominance case remain a clustering/stacking concern, not an event-dominance concern. Their grouping policy stays separate from the `50m` event rule.
- `D-60`: Event POIs may use the existing event-details repository as a client-side enrichment path for media fallback, applying selected-marker/media memory from the resolved event cover/artist fallback without introducing a new backend map contract.
- `D-61`: Selected event-card hero media must follow the same canonical event-image resolution used by event-detail surfaces; Flutter must not substitute linked-profile cover shortcuts when the canonical event cover/fallback path resolves differently.
- `D-62`: Selected event cards must not spend supporting-space budget on `updatedAt`; the factual block should prioritize sanitized description excerpt plus linked-profile chips that reuse immersive-event-detail duplicate/venue exclusion rules.
- `D-63`: Selected `account_profile` and `event` cards keep factual description excerpts capped to two lines so richer supporting text does not destabilize the lower-center card layout.
- `D-64`: Selected lower-center cards must fit within the deck envelope without raw viewport clipping. If the card needs to be height-bounded, the hero/media area is cropped and the card still closes cleanly with intact rounded edges.
- `D-65`: The current selected POI, and then the remembered last-selected POI when no card is open, temporarily render above other POIs in map draw order. This is a transient visual-priority rule only; it must not mutate canonical POI priority data.
- `D-66`: The selected-card close affordance is anchored to the top-right edge of the visible selected card itself, not to the viewport/right gutter or the raw deck container, so the dismissal target stays coupled to the card in both single-card and carousel layouts.
- `D-67`: Filter-result carousels inside the selected-card deck keep a small controlled peek of the adjacent card as a navigability affordance, but the active card itself must fit the item viewport cleanly without horizontal or vertical brute clipping from the `PageView`.
- `D-68`: In filtered-result carousels, hero/media height may compact dynamically to fit the carousel viewport. The compacting must happen inside the card itself so the card closes smoothly instead of relying on outer viewport clipping.
- `D-69`: Selected-card secondary actions are POI-type-owned, not generic. `account_profile` keeps a public share action; `event` uses the canonical invite action instead of generic share text.
- `D-70`: `account_profile` share payload must reuse the same already-hydrated details content as the selected card, including the resolved cover path, factual bio/content excerpt when present, and canonical public route.
- `D-71`: `account_profile` share copy varies by actor state only:
  - authenticated: `<display_name> está te convidando para conhecer ...`
  - anonymous: `Ei, vi isso e achei que você gostaria...`
  No alternate generic map-share copy is accepted for this card.
- `D-72`: Event selected cards must no longer expose generic public share. Their secondary action opens the canonical event-invite path, preserving the existing app-vs-web promotion/auth semantics already approved for invite flows.
- `D-73`: Secondary-action payload building must not refetch alternate data paths when the selected card already owns hydrated details. Share/invite semantics must integrate with that existing details enrichment result.
- `D-74`: Public link richness for shared account profiles and events comes from canonical route metadata, not attached media payloads. The share action sends `text + canonical URL`; the route itself must expose server-side metadata (`title`, `description`, `og:image`, fallback default) for social unfurls.
- `D-75`: Canonical public partner/event routes used by map share/invite semantics must bypass the SPA catch-all at ingress so server-side metadata can be injected before Flutter boots. The approved public shapes are `/parceiro/{slug}` and `/agenda/evento/{slug}` with tenant/default metadata fallback.
- `D-76`: The `account_profile` immersive/detail screen share button reuses the same canonical public-share payload builder approved for selected map cards, so profile share copy, description precedence, and public-route semantics never drift between surfaces.
- `D-77`: Static assets follow the same public-detail shell pattern as account profiles, but remain non-favoritable. Their immersive detail uses share-only actions, cover-only hero identity, `Sobre` when factual content exists, and `Como Chegar` when location exists.
- `D-78`: Static-asset selected cards and detail/share flows must reuse the same hydrated details path already resolved from the map, replacing generic placeholder copy with factual description/content excerpts and canonical `/static/{assetRef|slug}` public routing.
- `D-79`: Filter-result carousels are a first-class peeking-carousel surface, not a clipped single-card deck. The deck must use the full available selected-card overlay width and show an intentional adjacent-card peek around `10–15%` of the next card so carousel navigability is obvious without relying only on dots.
- `D-80`: The filter-result carousel must not reveal generic gray image placeholders or raw viewport slabs when image cards leave the screen. The deck owns viewport/peek geometry, while the card hero uses the same branded fallback placeholder during loading/error so outgoing and incoming cards remain visually stable.
- `D-81`: Filter-result deck height is resolved against the visible carousel set, not just the currently selected POI. If compact cards still exceed that envelope on smaller viewports, the compact card contract must shrink its own hero/padding/button budget; the surface may not tolerate `RenderFlex` overflow as a layout strategy.
- `D-82`: Filter-result carousel hydration is per-card and lifecycle-driven. The selected card may arrive already hydrated from marker selection, but each deck item must independently request its own details when built if it is still cold, while reusing any cached or in-flight hydration so reopening/swiping does not duplicate requests.
- `D-83`: POI hydration ownership is POI-scoped and repository-owned. The deck, selected-card flow, marker memory, and standalone POI surfaces must all reuse the same canonical hydrated snapshot/in-flight state from `PoiRepository`; deck widgets are only lifecycle triggers and may not own independent hydration caches.
- `D-84`: In event-filter contexts, the filter-results dock title is temporal, not proximity-based. The approved heading is `Próximos eventos`; `Mais próximos de você` remains for non-event result sets only.

---

## IA / State Model (Authoritative)

### State A — Default Discovery
- Map remains the visual stage.
- Global bottom nav stays visible and stable.
- No textual discovery summary is required in the collapsed state.
- Backend-driven filters render as compact floating chips inside the bottom dock in this state.
- Minimized filters are icon-first in this state.
- When only a small number of filters are visible, they should remain visually centered.
- If the chip cluster would exceed two rows, a handle appears and governs expansion.
- A smaller `Buscar` launcher remains visible inside the same dock, visually secondary to the filters.
- No selected card is open.
- No filter is active.

### State B — Filters Expanded
- Only triggered when the floating chip cluster would overflow two rows.
- Expansion is governed exclusively by the handle; there is no explicit close button.
- Available filters are shown in wrapped layout.
- Filters are rendered with icon + label.
- Filter inventory remains backend-driven and scalable.
- Selection remains single-select.
- Choosing one filter applies immediately and exits to `State C`.

### State C — Selected Filter
- The floating cluster returns to minimized mode.
- Exactly one filter is visually active and acts as the discovery context.
- The active filter chip expands to show its label; other visible filters remain compact.
- Map markers and nearby content reflect the active filter immediately.
- Applying the filter may move the map toward the first visible result, but it must not open a selected card or auto-select that POI.

### State D — Search Mode
- Tray transforms into search mode instead of opening a new page.
- Search input, recent searches, suggestions, or lightweight nearby previews live in the same tray.
- Global bottom nav remains stable.
- The same bottom dock surface transforms into the search tray; no duplicate resting `Buscar` launcher remains visible while search is open.

### State E — Selected POI
- Tapping an individual POI:
  - highlights the marker,
  - repositions camera to keep the marker in the upper-center safe zone,
  - opens a lower-center floating card.
- The card includes explicit close affordance.
- The tray does not become the selected card.

### State F — Selected Event
- Same architecture as `State E`.
- Only badge/copy/CTA change to reflect time-sensitive event semantics.

---

## Visual Interaction Rules

### Global vs Local Controls
- Bottom navigation must never switch map-local modes.
- `Você` is a top-right utility control with persistent badge/state feedback.
- Backend-driven filters remain the primary local exploration control near the bottom interaction zone.
- `Buscar` remains available in the same bottom dock as a secondary action.
- The floating filter cluster is always visible when backend filters exist; it is not a third action-row button.
- `Descoberta` remains the default tray/search state, not a separate button.

### Selected Marker Memory
- Marker memory uses `avatar/cover` when available.
- Marker memory applies only to the last selected individual POI.
- Closing the card keeps this memory visible temporarily.
- Memory clears on:
  - map pan,
  - map zoom,
  - cluster absorption after viewport change,
  - new POI selection.

### Camera / Framing
- Opening a selected card must reframe the map with vertical offset.
- Initial target zone: selected marker should sit around `30%` to `38%` from the top of the viewport.
- Goal: the selected marker must not be hidden by the lower-center card.
- Filter-preview focus may use a milder viewport anchor than selected-card focus, because no card is open yet.

### Selected Card
- Lower-center placement only.
- Explicit close button is required.
- No connector line between marker and card.
- Closing the card does not automatically clear the marker memory.
- Tapping empty map closes the card first; memory remains until a clearing trigger occurs.

### Clusters
- Cluster tap = zoom/focus into cluster bounds.
- Cluster does not open detail card.
- Cluster never adopts `avatar/cover` memory.
- If filter is active, cluster may inherit filter semantics visually.
- If no filter is active, cluster remains neutral.
- These rules apply to view/plugin density clusters only, not to existing backend/runtime `exact-key stacks`.

### Exact-Key Stacks
- Existing `stack_key` / `stack_count` runtime semantics remain valid in V1.
- Exact-key stacks keep selector/list behavior so same-coordinate POIs remain reachable.
- This lane must not regress approved stack browsing semantics while introducing new cluster behavior.
- When a user chooses one item from the stack/cluster path, that chosen item must still pass through the same late-hydration selection policy as an individual marker tap.

---

## Plugin / Surface Contract (Authoritative)

### Belluga-Owned Map Surface
- Introduce a Belluga-owned surface between Flutter map presentation/controller code and the underlying plugin.
- The purpose is stability and isolation, not generic map abstraction.
- The Belluga surface must expose only the primitives needed by the approved map IA:
  - map host widget,
  - camera handle/commands,
  - camera state,
  - interaction event stream,
  - marker input model,
  - cluster input model.

### Belluga-Owned Surface Responsibilities
- Translate Belluga camera commands to the plugin adapter.
- Translate plugin interaction callbacks into Belluga map events.
- Render marker/cluster inputs through a Belluga-owned presentation boundary.
- Provide the framing seam needed for:
  - upper-center POI focus,
  - cluster zoom/focus,
  - last-selected marker memory visuals.

### Explicit Non-Responsibilities
- Filter/search state machine.
- Discovery/tray copy and layout decisions.
- Selected-item/card rules.
- POI/event business semantics.
- Location-origin copy/message policy.

### Plugin Adapter Strategy
- Initial adapter target: `flutter_map`.
- Old `free_map` integration is migration debt and must be retired from the active runtime once parity is reached.
- The new visual implementation must be built on the Belluga-owned surface instead of binding directly to `flutter_map` primitives in screen/controller code.

## Routed Follow-up Intake (`2026-04-08`)

- `WEB-004`: event-filter result docks must order event POIs by the next upcoming `time_start`, not by nearest distance. This intentionally supersedes the older distance-first note for event-filter contexts only; non-event contexts stay on the current ordering unless explicitly widened later.
- `WEB-005`: account-profile POIs are no longer hydrating selected-card details while event POIs still do. The fix must restore account-profile hydration without regressing event or static-asset hydration, and any legacy type-alias drift must terminate before repository-owned hydration behavior diverges from the canonical `account_profile|event|static` contract.

## Visual Refinement Follow-up (`2026-04-09`)

- User validated that the `WEB-004` ordering change now works correctly in runtime: event-filter result docks are already honoring `next upcoming first` by `time_start`.
- Remaining `WEB-004` refinement is presentation, not ordering:
  - upcoming event badges must include temporal context instead of bare time-only labels;
  - `AGORA` remains unchanged for live events;
  - same-day upcoming events must render `Hoje {HH:mm}`;
  - next-day upcoming events must render `Amanhã {HH:mm}`;
  - later upcoming events must render an explicit date + time;
  - the event-filter dock heading must change from `Mais próximos de você` to `Próximos eventos`.
- Local implementation now applies that temporal presentation in the event-filter dock/list surfaces:
  - `AGORA` remains unchanged for live events;
  - upcoming event cards now render `Hoje {HH:mm}`, `Amanhã {HH:mm}`, or explicit `dd/MM HH:mm`;
  - the event-filter dock heading now reads `Próximos eventos`.
- No widening was executed in this recut for marker badges or the selected event card; those surfaces remain on their current timing presentation until a separate follow-up explicitly broadens the contract.

## Hydration Regression Clarification (`2026-04-09`)

- Earlier runtime validation after the first local implementation showed `WEB-005` was still open.
- The current issue is broader than the earlier intake wording:
  - `event` POIs still demonstrate the correct selected-card hydration pattern;
  - `account_profile` POIs are still not hydrating correctly;
  - `static/assets` are also not hydrating correctly.
- The expected parity rule is explicit:
  - `account_profile` and `static/assets` must follow the same approved runtime pattern that already works for `event`;
  - the selected POI promoted into controller state must be enriched with the hydrated factual/media fields;
  - the rendered selected card must reflect that enriched selected POI instead of staying on a cold/base POI snapshot.
- This is not only a “card visual” bug. The selected-card surface and the canonical selected-POI state must both converge on the same hydrated result.
- Local implementation restored that repository-owned hydration parity for `account_profile` and `static/assets`, including the promoted selected-POI snapshot and the rendered selected-card media/content path.

---

## Tasks
- [ ] ⚪ Promote this visual IA as the authoritative V1 map interaction model.
- [ ] ⚪ Execute `TODO-v1-map-location-origin-feedback.md` before finalizing bottom-dock semantics and map feedback polish.
- [ ] ⚪ Consolidate the pending `free_map -> flutter_map` migration from `TODO-v1-map-frontend.md` into this lane.
- [ ] ⚪ Introduce the Belluga-owned map surface and keep it narrow/Belluga-specific.
- [ ] ⚪ Migrate active map rendering/composition to the Belluga-owned `flutter_map` adapter.
- [ ] ⚪ Update map screen composition so bottom nav, bottom dock, top-right location utility, and selected card have clearly separated responsibilities.
- [x] ✅ Implement the tray state machine for default discovery, filters expanded, selected filter, filter-results, and search.
- [ ] ⚪ Remove every remaining user-facing fallback from type labels that depends on enum/technical projection values; only payload/hydrated factual labels are acceptable.
- [ ] ⚪ Ensure starting a new POI hydration clears the previous selected-card overlay immediately and demotes the previous marker from its promoted/last-selected visual state.
- [x] ✅ Ensure stack/cluster-driven item selection runs the same hydration pipeline as direct marker taps, including image/media enrichment when available.
- [x] ✅ Replace the current direct cluster/stack detailed-deck opening behavior with the approved local `POI picker popover` flow.
- [x] ✅ Implement single-select filter behavior with immediate apply and minimized selected-filter state.
- [x] ✅ Preserve backend-driven dynamic filter catalog semantics in minimized and expanded visual states.
- [x] ✅ Replace the old `Filtros` action entrypoint with the compact floating filter cluster and overflow-handle behavior.
- [ ] ⚪ Keep selected-card opening deferred until any approved late-hydration path resolves, while giving immediate marker-level loading feedback on tap.
- [x] ✅ Keep hydrated media/details scoped to canonical POI-owned hydration state in `PoiRepository`; deck/selected-card consumers must reuse that cache without mutating the global POI snapshot just to preserve selected-marker memory.
- [ ] ⚪ Validate `selected-loading`, duplicate-tap drop, stale-response ignore, and last-write-wins behavior with targeted controller tests.
- [ ] 🟧 Remove legacy `FabMenu` / `main filters` surfaces and dead contracts so expanded filters render only backend `filterOptions`.
- [ ] ⚪ Implement lower-center floating card for selected place and selected event.
- [x] ✅ Redesign selected-card content so it uses only factual `CityPoiModel` fields, sanitizes HTML-like descriptions, and hides weak/generic fields such as placeholder addresses.
- [x] ✅ Redesign search-mode suggestion cards in the same content system as the selected card, using compact but useful factual previews.
- [ ] ⚪ Implement selected-marker memory with `avatar/cover` and proper clearing triggers.
- [ ] ⚪ Implement camera offset / upper-center framing on POI selection.
- [ ] ⚪ Remove any visual connector line between selected marker and card.
- [ ] ⚪ Implement V1 cluster behavior: zoom/focus only, no direct card open.
- [ ] ⚪ Preserve approved exact-key stack behavior while keeping new cluster behavior limited to view/plugin density clusters.
- [ ] ⚪ Validate that closing the card, tapping empty map, pan, and zoom all respect the frozen interaction rules.
- [x] ✅ Move transient map status/loading notice above the dock and visually adapt it so it no longer interferes with dock controls.
- [x] ✅ Replace cluster `+N` labeling with explicit total-count labeling.
- [x] ✅ Harden the selected-card deck/carousel against variable-height content so cards do not break layout or appear top-anchored/inconsistently stretched.
- [ ] ⚪ Reframe filter apply toward the first visible dock result, using the same ordering shown in the filter-results dock as the canonical source of truth.
- [x] ✅ For event-filter contexts, order the filter-results dock and preview focus by next upcoming start time instead of distance, while preserving the current non-event ordering.
- [ ] ⚪ Allow tapping the same active filter chip to reopen a minimized filter-results dock without clearing the filter.
- [ ] ⚪ Decide and implement the filtered-result navigation mode so filtered marker/card selection can open a result-set carousel without regressing cluster behavior.
- [ ] ⚪ Extend the Flutter map POI contract to preserve backend-owned event timing fields (`time_start`, `time_end`, `is_happening_now`) instead of dropping them in the DTO/domain projection.
- [ ] ⚪ Introduce a shared event-timing presenter/resolver for map markers, filter/search cards, and selected event cards so `AGORA`, `Hoje {HH:mm}`, `Amanhã {HH:mm}`, and later date/time badges come from one contract path.
- [ ] ⚪ Restore event timing badges for event discovery contexts, especially active event filters and unfiltered map discovery where event POIs are visible.
- [ ] 🟧 Rename the event-filter result dock heading to `Próximos eventos` while preserving proximity-oriented copy for non-event result sets.
- [ ] ⚪ Add backend overlap policy so `event + local` in the same micro-area prefers the event as the visible marker/result entrypoint, with a fallback dominance radius around `50m`.
- [ ] ⚪ Keep multi-event overlap and very-close local-only overlap on the existing stack/cluster lane instead of collapsing them under the event-dominance rule.
- [ ] ⚪ Align selected event-card hero media with the canonical event-details cover resolution path instead of any linked-profile cover shortcut.
- [ ] ⚪ Replace the selected event-card `updatedAt` line with a factual supporting block composed of:
  - sanitized description excerpt (up to two lines),
  - linked account-profile chips using the same venue-exclusion and de-duplication semantics as immersive event detail.
- [ ] ⚪ Preserve a two-line factual description clamp for selected `account_profile` and `event` cards.
- [ ] 🟧 Restore selected-card hydration for `account_profile` and `static/assets`, matching the same selected-POI plus selected-card enrichment pattern already working for `event`.
- [ ] ⚪ Replace the selected `account_profile` secondary action with a canonical public-share flow that reuses hydrated details content, resolved cover/media, and factual bio/content excerpt when present.
- [ ] ⚪ Tailor `account_profile` share copy by actor state (`authenticated inviter` vs `anonymous recommendation`) without introducing a second parallel share surface.
- [ ] ⚪ Replace the selected event-card generic share action with the canonical invite action/handoff instead of free-form share text.
- [ ] ⚪ Promote the finalized visual contract into `map_poi_module.md` and `modulo_mapa_e_mobilidade.md` after implementation is accepted.

---

## Acceptance Criteria
- [ ] ⚪ Map hierarchy clearly separates global navigation, local map controls, tray states, and selected-item presentation.
- [ ] ⚪ Filters behave as mutually exclusive single-select controls with immediate apply.
- [ ] ⚪ Minimized filter presentation remains readable for dynamic catalogs, including centered few-item states and icon-first compact states.
- [ ] ⚪ When the backend catalog exceeds two rows, the handle appears and remains the only expand/collapse affordance.
- [ ] ⚪ Selected POI/event card opens as an independent lower-center floating card.
- [ ] ⚪ Opening a selected card does not hide the selected marker behind the card.
- [ ] ⚪ Selected-card content is factual, sanitized, and consistent across items with and without media.
- [ ] ⚪ Closing the card preserves last-selected marker memory until pan/zoom/new selection.
- [ ] ⚪ Pan/zoom clears marker memory deterministically.
- [x] ✅ Cluster interaction never opens a card directly in V1.
- [x] ✅ Cluster labels communicate total count unambiguously.
- [ ] ⚪ Exact-key stacks remain reachable and do not regress into zoom-only dead ends.
- [ ] ⚪ Event and place selection feel like the same system, not two disconnected UX patterns.
- [ ] ⚪ Search-mode suggestion cards feel like compact extensions of the same POI content system, not generic list rows.
- [ ] ⚪ Applying a filter reframes to the first item shown in the filter-results dock, so map preview focus and dock ordering never disagree.
- [x] ✅ Event-filter result docks list the next upcoming event first, while non-event filters preserve the approved ordering rules.
- [ ] ⚪ An active filter can always recover its result list by tapping the same selected chip again after minimization.
- [ ] ⚪ If filtered-result carousel mode is accepted, it stays scoped to active-filter navigation and does not change cluster/stack interaction.
- [ ] ⚪ Event POIs expose enough timing data to render `AGORA` or start-time badges without a separate details fetch.
- [ ] ⚪ Event timing badges are consistent across marker, dock cards, and selected event card, using `AGORA`, `Hoje {HH:mm}`, `Amanhã {HH:mm}`, and later date/time labels as appropriate.
- [ ] 🟧 Event-filter docks use the temporal heading `Próximos eventos` instead of the proximity heading used by non-event result sets.
- [ ] ⚪ Selected event-card hero image matches the canonical event-details image resolution path.
- [ ] ⚪ Selected event-card supporting content uses description + linked-profile chips instead of `updatedAt`, without repeating the venue as a counterpart chip.
- [ ] ⚪ Selected `account_profile` and `event` cards both surface at most two lines of factual description excerpt.
- [ ] 🟧 Selected `account_profile` and `static/assets` hydrate again in the map card flow, and both the promoted selected POI state and the rendered card reflect the hydrated avatar/cover/content fields just as they do for `event`.
- [ ] ⚪ Selected `account_profile` cards share using the same resolved details content already shown in the card, including cover/media path and bio/content excerpt when available.
- [ ] ⚪ Authenticated `account_profile` share copy names the inviter; anonymous `account_profile` share copy uses the approved generic recommendation phrasing.
- [ ] ⚪ Selected event cards no longer expose generic share and instead route into the canonical invite handoff/action.
- [ ] ⚪ A single event overlapping a local POI in the same micro-area surfaces as the event entrypoint instead of an ambiguous event+place stack.
- [ ] ⚪ Multi-event overlap and very-close local-only overlap remain explorable through stack/cluster behavior and do not regress into dead markers.
- [ ] ⚪ Location-origin feedback and the `Você` action behave consistently with the dedicated sequential slice and no longer emit sticky false `unavailable` feedback after permission grant.
- [ ] ⚪ Screen/controller code no longer depends directly on plugin-specific map APIs for camera/interaction orchestration.

---

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Decision adherence is recorded against `D-01..D-28`.
- [ ] ⚪ `fvm dart analyze --format machine` is clean for the touched Flutter result.
- [ ] ⚪ Targeted widget/controller/manual validation covers tray states, selected card, selected-marker memory, and cluster interaction.
- [ ] ⚪ Web build + Playwright validation confirm the migrated adapter/runtime still boots and the approved map flows remain functional.
- [ ] ⚪ Canonical docs are updated after implementation approval.

---

## Validation Steps
- [ ] ⚪ Manual smoke: default discovery state and bottom nav vs bottom-dock separation.
- [ ] ⚪ Manual smoke: expand filters, select one filter, confirm immediate apply and minimized selected-filter state.
- [ ] ⚪ Manual smoke: enter search mode and return to discovery/filter mode without route churn.
- [ ] ⚪ Manual smoke: select an individual POI, confirm camera offset and lower-center card behavior.
- [ ] ⚪ Manual smoke: close the card and confirm last-selected marker memory persists.
- [ ] ⚪ Manual smoke: tap empty map and confirm the card closes without immediately clearing marker memory.
- [ ] ⚪ Manual smoke: pan/zoom after card close and confirm marker memory clears.
- [ ] ⚪ Manual smoke: tap cluster and confirm zoom/focus without opening detail card.
- [ ] ⚪ Manual smoke: cluster badge/count communicates the full item count without `+N` ambiguity.
- [ ] ⚪ Manual smoke: selected event reuses the same lower-center card architecture with event-specific badge/copy/CTA.
- [ ] ⚪ Manual smoke: transient map status/loading notice sits above the dock and does not block dock controls.
- [ ] ⚪ Manual smoke: search-mode suggestion cards show useful factual previews and recover cleanly after clearing search.
- [ ] ⚪ Manual smoke: selected-card deck remains visually stable with long description/address payloads and stacked items of different content heights.
- [ ] ⚪ Manual smoke: applying a filter focuses the first item shown in the dock, not the last matching marker.
- [x] ✅ Manual smoke: event-filter result docks list the next upcoming event first instead of the nearest event.
- [ ] ⚪ Manual smoke: minimizing the filter-results dock and tapping the same active filter chip reopens the dock without clearing the filter.
- [ ] ⚪ Manual smoke: filtered-result navigation uses the accepted result-set behavior (dock-only or carousel) without changing cluster/stack behavior.
- [ ] ⚪ Manual smoke: event POIs show `AGORA` while live, `Hoje {HH:mm}` when upcoming later today, `Amanhã {HH:mm}` when upcoming tomorrow, and explicit date/time for later upcoming cases.
- [ ] ⚪ Manual smoke: event-filter result dock heading reads `Próximos eventos`, while non-event result sets keep the proximity heading.
- [ ] ⚪ Manual smoke: selected event card shows the expected start/end timing details from the same POI contract.
- [ ] ⚪ Manual smoke: selected event card hero image matches the canonical event-details cover/fallback path.
- [ ] ⚪ Manual smoke: selected event card shows description excerpt plus linked-profile chips and no `updatedAt` row.
- [ ] ⚪ Manual smoke: selected `account_profile` and `event` cards clamp factual description excerpts to two lines without layout breakage.
- [ ] ⚪ Manual smoke: `account_profile` and `static/assets` hydrate correctly in the selected card and in the promoted selected-POI state, matching the already-correct `event` behavior.
- [ ] ⚪ Manual smoke: selected `account_profile` share action uses the resolved details cover/bio/content and emits the approved copy for authenticated vs anonymous actors.
- [ ] ⚪ Manual smoke: selected event card exposes invite instead of generic share and follows the canonical app/web invite handoff.
- [ ] ⚪ Manual smoke: a single event overlapping a local POI in the same micro-area is surfaced as the event entrypoint instead of a generic stack.
- [ ] ⚪ Manual smoke: multiple events or local-only near-overlap cases still open the expected picker/cluster behavior.
- [ ] ⚪ Manual smoke: post-permission map entry does not emit false `unavailable`; the `Você` action and transient notice reflect the final resolved origin state.
- [ ] ⚪ Manual smoke: Belluga-owned map surface running on `flutter_map` preserves approved map flows without direct plugin leakage into the screen/controller seam.

---

## Execution Outcome Snapshot (`2026-04-05`)

- The sequential slice `TODO-v1-map-location-origin-feedback.md` is now locally implemented and automatically validated in Flutter.
- The `Você` action semantics approved for map visuals are now backed by controller projection + widget badge rendering instead of raw permission heuristics.
- The Belluga-owned map surface now exists as the plugin seam, with `flutter_map` behind the adapter and screen/controller code no longer orchestrating camera/interaction directly against the plugin API.
- The bottom overlay is now split into:
  - top utility slot for `Você`,
  - bottom dock (`filters + Buscar`),
  - adaptive tray/search surface,
  - compact floating backend-driven filter cluster with overflow handle only when needed,
  - independent selected-item deck.
- Local browser validation against the freshly built bundle confirmed:
  - default discovery tray,
  - compact floating filter cluster,
  - overflow-handle filter expansion in widget tests,
  - search tray state,
  - selected-item overlay on top of the migrated map surface.
- The dock/tray behavior now also includes:
  - search launcher opening a scrollable `Nessa área` tray backed by the same nearby/discovery feed, but re-anchored to the current map center at the moment search opens,
  - clearing typed search restoring the same center-anchored nearby feed instead of dropping back to user/default-origin suggestions,
  - selecting a backend filter opening a dedicated filter-results dock that keeps the same filter-row state on the first line and lists filtered POIs below using the dock ordering as the source of truth; event-filter contexts must now prioritize the next upcoming event first, while non-event contexts preserve the approved distance-first behavior.
- Additional host validation against `guarappari.belluga.space` confirmed:
  - applying a backend filter no longer auto-selects the first POI or opens a selected card,
  - filter apply now keeps selection user-driven while allowing the map to preview/focus the leading result,
  - explicit POI selection uses a stronger upper-viewport anchor than filter preview focus so the selected marker is not intended to sit behind the lower-center card.
- Accepted post-checkpoint follow-up packet on this lane:
  - selected event-card hero must align to canonical event cover resolution from event details,
  - selected event-card supporting block should swap `updatedAt` for description + linked-profile chips,
  - selected event/account-profile description excerpts must stay capped to two lines.
- Selected cards and search-dock cards now share a factual content resolver:
  - `visual.imageUri` is reused as cover/thumb when available,
  - `assetPath` is fallback media,
  - icon/color fallback stays payload-driven,
  - badge/type/meta/description/tags are derived from `CityPoiModel` payload only,
  - visual type labels now prefer payload category semantics instead of leaking technical `ref_type` labels such as `account_profile`,
  - HTML-like descriptions are sanitized before rendering,
  - weak placeholder addresses such as `Mapa` are suppressed.
- Additional local closing work now implemented and validated:
  - transient map status/loading notice renders above the dock instead of overlapping it,
  - cluster badges use total-count labeling (`4` instead of `+3`),
  - selected-card deck height is stabilized against mixed stacked-card payload heights,
  - filtered-result deck height now tracks the tallest visible card and the compact card contract uses a tighter vertical budget on smaller viewports,
  - filtered-result deck items now lazily self-hydrate on build and reuse cached/in-flight hydration instead of depending only on whichever item opened the carousel,
  - `Ver detalhes` now routes account-profile POIs into `PartnerDetailRoute` instead of replacing the map route,
  - CTA ordering is now stable across POI/event cards (`Traçar rota` primary, `Ver detalhes` secondary),
  - transient `/v1/map/pois` failures no longer wipe the last loaded POI snapshot from the map,
  - client map HTTP timeouts were relaxed to reduce false-negative empty-state/error cycles under slower runtime conditions,
  - targeted widget/tests cover the cases above.
- Tenant-host runtime parity is still partially blocked by stale bundle delivery on `guarappari.belluga.space`:
  - local `build_web_bundle.sh` completed successfully and synced `web-app`,
  - Playwright host smoke still showed stale dock/runtime visuals even after service-worker and cache cleanup,
  - so final tenant-host proof for the newest dock/card visuals remains pending environment parity, not Flutter code implementation.
- Remaining visual-lane closure work is now concentrated on:
  - final lower-center selected-card image/hero polish under real tenant-host viewport/runtime conditions,
  - exact-key stack / density-cluster interaction checks,
  - canonical-doc promotion after acceptance.
