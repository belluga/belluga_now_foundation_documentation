# TODO (V1): Screen Polish - Public Account Profile Detail (`/parceiro/:slug`)

**Authority note (2026-04-17):** this TODO is the single active authority for tenant-public Public Account Profile detail-screen polish on `/parceiro/:slug`. Discovery-side contract and launch presentation on `/descobrir` were completed in `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`; no active Discovery companion item remains in this lane.

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Active

## Delivery Status Canon

- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Completed`, `Automated-Validated`, `Rich-Text-Fidelity-Covered`
- **Next exact step:** Archived to `completed/`; no broad Public Account Profile detail polish blocker remains in Store Release. The only immediate release requirement retained from this surface is `Sobre`/`content` editing and public-rendering fidelity, covered by the completed Account Profile rich-text fidelity lane using the same standard applied to Events.

**Owners:** Flutter Team, Laravel Team
**Objective:** Finish the tenant-public Public Account Profile detail polish by aligning the remaining immersive behavior with the approved MVP model: a basic collapsed AppBar, occurrence-first agenda with event-first hierarchy, fixed `Sobre | Agenda | Como Chegar` ordering, no social metrics, swipe-driven tab navigation shared by Public Account Profile and immersive Event detail, a hero type visual rendered as an isolated circular avatar with the type icon, taxonomy chips that display canonical names instead of slug-like values, and a shared `Upcoming Event` card contract between Home and Public Account Profile agendas.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`

**Blocker Notes:** The Laravel/contract blocker, locale drift blocker, Home/UI convergence blocker, and Discovery back-stack regression are resolved locally. Remaining work is manual validation/promotion, not implementation.
**Last confirmed truth:** `2026-04-04` automated validation confirms that Home and Account Profile now share the same `Upcoming Event` card family. `VenueEventResume` carries event-type + venue-title metadata, `DateGroupedEventList` uses the shared card, Account Profile uses the same card for standard agenda items, and the app shell remains locked to `pt_BR`. New intake on `2026-04-08`: when a profile has no about content and no events, the no-sections fallback must switch from `Mais sobre este perfil` to the favorite CTA `Favorite para ser avisado das novidades sobre {account profile name}.` Manual 2026-04-22 validation found a release-facing navigation regression: after navigating from Discovery to an Account Profile detail and returning to Discovery, the Discovery back action becomes inconsistent and reopens the previously visited Account Profile instead of returning to Home.

## Store Release Reclassification Note
- 2026-04-28: User narrowed the remaining immediate Store Release concern for this surface to Account Profile `Sobre`/`content` editing and public-rendering fidelity, matching the standard already applied to Event `Sobre`.
- That fidelity contract is owned and completed by `foundation_documentation/todos/completed/TODO-store-release-account-profile-rich-text-fidelity.md`, which covers independent `bio` and `content`, shared safe rich-text subset parity with Events, public `/parceiro/:slug` `Sobre` rendering, tenant-admin/editor fidelity, and runtime evidence.
- This TODO is therefore archived as completed historical polish evidence; any future Account Profile detail improvements outside the rich-text fidelity requirement should be reclassified into `vnext` or a new bounded Store Release blocker only if they become publication-critical.

---

## Terminology
- `User Profile` means the authenticated self/profile route `/profile`; it is not owned by this TODO.
- `Public Account Profile` means the tenant-public public identity screen for an account-managed entity on `/parceiro/:slug`, also reached from Discovery/Favorites/Map/Event-linked profile entrypoints.

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `None`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `None` | `TenantRouteGuard()` |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `../foundation_documentation/modules/account_profile_catalog_module.md`, `../foundation_documentation/modules/events_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for controller-owned state, route/scope ownership, and Flutter testing discipline. No partial migration flag is declared for the touched surface.
- `account_profile_catalog_module.md`: authoritative for account-profile public detail contracts and consumer-facing identity semantics. No partial migration flag is declared for the touched surface.
- `events_module.md`: authoritative only for event-detail-to-profile continuity; no partial migration flag is declared for the touched entrypoint.

### Decision Consolidation Targets

- Promote stable cross-screen/shared-shell decisions to `../foundation_documentation/modules/flutter_client_experience_module.md` only if this TODO changes durable shell or test-governance behavior.
- Promote stable account-profile detail presentation/CTA decisions to `../foundation_documentation/modules/account_profile_catalog_module.md` only if this TODO changes durable consumer-facing detail semantics beyond tactical polish.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- `../foundation_documentation/policies/scope_subscope_governance.md`
- `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/domain/partners/services/partner_profile_config_builder.dart`
- `lib/application/router/modular_app/modules/discovery_module.dart`

---

## Scope (Single Screen)

- Keep the existing `PartnerDetailRoute` and resolver flow as the canonical Public Account Profile detail entrypoint.
- Keep the approved account-profile composition with title and taxonomy/chips outside the tab card area and above the tab chrome.
- Preserve the existing immersive-shell structure where tabs act as sticky anchors over a single continuous scroll surface rather than swapping tab panels.
- Improve hero hierarchy, CTA prominence, and section spacing without changing the configured tabs/modules.
- Refine the hero type visual so it uses only a circular avatar surface derived from the type visual color, renders the type icon inside that circle, and sits beside the profile name with standard horizontal spacing, without any chip container or type label.
- Wherever a textual profile-type label still exists outside that hero avatar treatment, it must use the canonical display field from the profile-type definition (`label/name`), never the raw `profile_type` token.
- Preserve canonical taxonomy term naming in the account-profile domain/parser flow so category chips render display `name/label` rather than slug-like `value` strings when both exist in the payload.
- Study CTA priority as relationship-state-first, then active-section CTA, then fallback CTA, without moving authentication concerns into visual page state.
- Replace the current no-sections fallback copy with the approved favorite invitation when the profile is favoritable, not yet favorited, and no about/events sections exist.
- Improve loading, empty, error, and content state clarity for detail rendering.
- Establish a shared directions-app chooser flow for `Traçar rota`, using installed-app discovery on native and a curated fallback catalog on mobile web, without hardcoding option rows in individual screens.
- Validate mobile overflow/wrapping behavior for long names, taxonomy chips, and CTA/footer states.
- Normalize the collapsed AppBar title to standard AppBar behavior: no avatar, standard leading spacing, vertically centered title, `maxLines: 2`, and ellipsis on overflow.
- For MVP, lock the app shell locale to Brazilian Portuguese (`pt_BR`) instead of following the user/device language, so public date labels render consistently in Portuguese.
- Ensure `Acontecendo Agora` navigates to its event exactly like the other agenda cards.
- Recompose account-profile agenda cards so the event name is the primary line and supporting content lists only counterpart account profiles, excluding the current host.
- Recompose account-profile agenda cards so the event name is the primary line, counterpart account profiles render as wrapped chips directly below the title, and the venue/local appears on its own line with a location icon beneath the counterpart chips.
- Align `Acontecendo Agora` with the shared `Upcoming Event` family so the top supporting label uses the canonical event-type display label, not location/venue text.
- Expand the live-card time range to include both start day+time and end day+time rather than only `HH:mm - HH:mm`.
- Align venue rendering in both live and standard agenda cards to the shared venue-line contract instead of ad-hoc location chips/meta.
- Rename the directions-tile internal CTA label from `Localização no mapa` to `Ver no mapa`.
- Enforce the fixed MVP tab order `Sobre | Agenda | Como Chegar`.
- Remove social metrics/badges from the Public Account Profile detail MVP surface.
- Add lateral swipe navigation between adjacent anchor tabs in the shared immersive shell for both Account Profile and immersive Event detail.
- Preserve detail-route ingress behavior once discovery, event detail venue CTA, favorites, or invite-related entrypoints launch this route.
- Preserve Discovery back-stack semantics after Account Profile detail ingress: Home -> Discovery -> Account Profile detail -> back to Discovery -> Discovery back must return Home, never reopen the stale Account Profile detail.
- Use the top-right share action as the canonical public `account_profile` share entrypoint, reusing the same payload/copy contract approved for selected partner cards on the map (`text + canonical URL`, authenticated-vs-anonymous copy only).
- Preserve controller-first ownership while extending the public account-profile detail contract only as needed to expose occurrence-first `agenda_occurrences` and remove the wrong `upcoming_event_ids` runtime path.

## Out of Scope

- Broader backend/schema redesign beyond exposing occurrence-first agenda summaries on the existing public account-profile payloads.
- Full multilanguage/i18n architecture. That work moves to a dedicated `vnext` TODO once the MVP is stabilized in `pt_BR`.
- New detail modules or new tabs/capabilities outside approved MVP behavior.
- Route restructuring, resolver contract changes, or scope ownership changes.
- Replacing the current anchor-based immersive tab architecture with panel-swapping tabs.
- Dynamic tab ordering beyond the fixed MVP order `Sobre | Agenda | Como Chegar`.
- Tenant-admin or account-workspace features.
- Event invite/share semantics outside the approved public `account_profile` share action.

---

## Rule/Workflow Sources

- `../delphi-ai/main_instructions.md`
- `../foundation_documentation/policies/scope_subscope_governance.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-todo-driven-execution-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/flutter-widget-local-state-heuristics/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-foundation-docs-sync-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md`
- `../delphi-ai/workflows/flutter/create-screen-method.md`

### Must Preserve

- `FCX-01`: controller-owned state only; screen remains presentation-first.
- `FCX-02`: route ownership remains `tenant / tenant_public`.
- `FCX-03`: no DTO/API contract leakage into the screen, even if the backend payload must be widened.
- `PCO-01`: Account Profile remains the canonical public identity layer.
- `TODO-v1-public-account-profile-discovery-ui.md` decisions `B1`, `B5`, `B9`: preserve current detail route behavior and launch continuity while keeping the execution scoped to the minimum necessary surfaces.

### Must Avoid

- Shared-shell regressions that spill into immersive event detail behavior.
- New local widget state that should remain controller-owned.
- Hardcoded theme deviations or broad backend-driven behavior changes disguised as tactical agenda unblocking.
- Unverified loading/error/empty-state claims without widget/analyzer evidence.

---

## Current Implementation Snapshot

- `AccountProfileDetailScreen` currently renders the profile name and profile-type chips inside the hero overlay while also passing a custom collapsed-title widget into the shared app bar, which is still producing non-standard vertical alignment.
- Taxonomy tags and social metrics are rendered in `betweenHeroAndTabs`; the user has now approved removing social metrics from the MVP surface.
- Loading and null states are currently plain centered indicators/text with no dedicated visual hierarchy.
- The shared immersive shell already renders all tab sections inside one continuous scroll body, scrolls to sections on tab tap, and resolves bottom CTA/footer from the currently active section.
- The shared immersive shell now translates horizontal swipe gestures into adjacent tab-anchor navigation through the same controller path used by direct tab taps.
- The screen is a single large file with inline module renderers and only targeted detail-screen widget coverage.
- The shared `ImmersiveDetailScreen` is also used by immersive event detail, so shared-shell edits increase regression risk if not tightly scoped.
- The current runtime agenda depends on occurrence-first `AccountProfileModel.agendaEvents`, populated from ordered `agenda_occurrences` without schedule-repository indirection or event-level collapsing.
- Agenda cards now emphasize event title first, list only counterpart account profiles, and route `Acontecendo Agora` to the same event surface as standard agenda cards.

---

## Confirmed Page-Model Constraints (`2026-04-03`)

- Tabs remain section anchors over a single continuous detail page; they do not swap tab panels.
- Tapping a tab scrolls the viewport to the corresponding section title/content block.
- The highlighted tab reflects the section currently identified by the shared immersive shell.
- Bottom CTA remains contextual to the currently identified section.
- Event detail may still apply a higher-priority relationship-state action (`Confirmar presença`) before section CTA, but Account Profile Detail does not expose a default relationship CTA; it relies only on section actions.
- Authentication is not part of the visual CTA state matrix; action guards remain responsible for intercepting unauthenticated execution.
- For `Como Chegar`, the section only exists when location/POI data exists; within that section the preferred page model is a map tile with POI emphasis, an address card inside the tile, and distance in km as supporting meta.
- Tapping the `Como Chegar` tile opens the in-app `/mapa` route already focused on the profile POI (`poi=account_profile:<id>`); `Traçar rota` remains the separate directions action.
- Address text is optional in the tile card; if the payload does not expose a canonical address yet, the UI omits the address line instead of rendering placeholder text.
- Collapsed app-bar identity and top action buttons must use contrast-aware surfaces rather than relying on raw theme foreground tokens over dynamic-theme backgrounds.
- Collapsed header is a basic AppBar-like title surface with no avatar or pill, standard leading spacing, actions on the far right, and a title that stays vertically centered while allowing up to 2 lines with ellipsis.
- Bottom CTA shells should read as a distinct action plane, with stronger visual hierarchy than the body content below the active section.
- Agenda keeps one shared visual language across host types; what changes by host type is the content emphasis policy inside the same cards, not the widget family itself.
- For account-profile agenda cards, the event name is always the primary line. Supporting content must list counterpart account profiles only, excluding the current profile host, and may render those counterpart profiles with their type icon/image.
- Home agenda and Account Profile agenda must converge on one shared `Upcoming Event` card family; what varies is the prepared content model, not the behavioral contract or visual state system.
- Shared `Upcoming Event` behavior across Home and Account Profile includes invite-state tinting, top-right invite-status iconography, optional distance rendering, and media that grows vertically with the resolved card height.
- Counterpart exclusion is not a widget concern. Any exclusions (for example current host profile and venue in Account Profile) must be resolved by the caller/resolver before data reaches the shared event-card widget.
- For Account Profile, counterpart chips remain wrapped and prioritized above the venue line; Home may pass all counterparts because it does not have a current-host exclusion requirement.
- For Account Profile, the venue line format is `📍 {VenueName} ({distance}) - {address}` with distance and address each fully conditional so no empty `()` or dangling ` - ` render.
- For the MVP, tab order is fixed as `Sobre | Agenda | Como Chegar`.
- For the MVP, social metrics/badges are removed from the Account Profile detail surface.
- Shared immersive tabs must respond to horizontal swipe by moving to the adjacent tab and performing the same anchor-scroll behavior as a direct tab tap.
- MVP language is fixed to `pt_BR`; multilanguage is outside this slice.
- Native app route-choice UX may reuse `MapLauncher`-based installed-app discovery, but mobile web cannot rely on `MapLauncher` because the current plugin only ships Android/iOS implementations; any future web chooser must be a curated modal with deep links / HTTPS fallbacks.
- `Agenda` remains an approved tab for account profiles when event data exists; the current blocker is contract correctness and runtime consumption, not tab semantics.
- Locale-sensitive agenda strings that still depend on implicit system locale are not acceptable for the MVP; public app-shell locale must be deterministic.
- The hero type visual in the Account Profile hero is an isolated circular avatar only; no adjacent type text or enclosing chip remains in the MVP.
- The live agenda card cannot use venue/location text as its eyebrow/meta label; that slot is reserved for the canonical event-type label.
- Venue rendering inside agenda cards must follow the shared `Upcoming Event` pattern rather than bespoke per-card location text.

### Widget/Reference Baseline Snapshot

| Surface | Preferred V1 Widget Direction | Reference Baseline |
| --- | --- | --- |
| Shell / expanded hero | Hero-driven page with strong cover, identity, chips, continuous sections, and bottom contextual CTA | `image_001.png` |
| Collapsed identity | Basic AppBar-like collapsed title with no avatar/pill, 2 lines max + ellipsis, standard leading spacing, and actions on the far edge | `image_001.png`, live screenshot `Carvoeiro` correction |
| Sticky anchor tabs | Sticky section anchors, never panel-swapping tabs | `image_014.png`, `image_016.png` |
| Directions section | Realistic map tile with POI, internal address card, and distance badge/meta | `image_003.png` + `image_010.png` hybrid |
| Agenda section shell | Section-focused agenda viewport within the same continuous page | `image_016.png` |
| `Acontecendo Agora` | Single highlighted live card style reused across host types | `image_016.png` |
| Agenda standard cards | Single standard event-card style reused across host types | `image_014.png`, `image_015.png` |

### Agenda Counterpart Content Policy (`V1 dynamic by counterpart set / Vnext configurable`)

- Agenda keeps one visual system, but the card content must always prioritize `event title` as the primary line.
- Home and Account Profile share the same `Upcoming Event` card family and behavior contract; caller-prepared data decides what appears in counterpart slots.
- Supporting content in Account Profile must list only counterpart account profiles linked to the occurrence, excluding the current profile host and the venue.
- Counterpart profiles in Account Profile render as wrapped chips with type icon/image + name; they are no longer joined as plain `" - "` subtitle text.
- Venue/local content is rendered on its own line beneath counterpart chips, prefixed by the location icon and optionally enriched by distance/address when those fields exist.
- `V1`: derive counterpart emphasis dynamically from linked profiles/occurrence context in Flutter, never from hardcoded profile-type branching.
- `Vnext`: promote counterpart emphasis and image strategy into configurable tab presentation metadata if needed.

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing route + resolver flow already provides the correct cross-entrypoint continuity for discovery, favorites, and event venue CTA. | `PartnerDetailRoute`, `AccountProfileDetailRouteResolver`, and current event/discovery call sites already target `/parceiro/:slug`. | This TODO would become behavior-affecting and exceed Flutter-only polish scope. | `High` | Keep as assumption. |
| `A-02` | The account-profile detail agenda can stay within this TODO while replacing `upcoming_event_ids` with occurrence-first `agenda_occurrences` and removing the schedule-repository indirection. | Laravel already owns occurrence-first read models and Flutter agenda cards already consume occurrence-shaped fields (`title`, `start/end`, `artists`, `location`, `thumb`). | If false, the work would need a broader schedule/domain refactor TODO. | `Medium` | Keep as assumption. |
| `A-03` | Shared-shell changes, if needed, can stay API-compatible for immersive event detail. | `ImmersiveDetailScreen` accepts composable `heroContent`, `betweenHeroAndTabs`, `tabs`, and `footer`; current detail polish can likely use existing extension points. | We would need a broader shared-shell TODO or explicit approval to widen blast radius. | `Medium` | Keep as assumption; minimize shared edits. |
| `A-04` | Feature + parser coverage can validate the new agenda contract without requiring full mobile integration before implementation. | Laravel feature tests already cover `/account_profiles/{slug}` and Flutter backend tests already cover parser behavior. | Delivery would rely on manual-only runtime evidence and could miss payload regressions. | `Medium` | Keep as assumption. |
| `A-05` | Horizontal swipe can be added to the shared immersive shell without breaking the anchor-tab contract or introducing panel-swapping state. | `ImmersiveDetailScreen` already owns adjacent-tab activation via tab taps, so swipe can delegate to the same controller path. | The shell work would exceed tactical polish scope and need a broader shared-shell refactor. | `Medium` | Keep as assumption. |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-screen-public-account-profile-detail-polish.md`
- `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/presentation/shared/**` or `lib/presentation/tenant_public/map/**` for the shared directions chooser/helper surfaces
- `lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart`
- `test/presentation/tenant_public/...` targeted detail-screen coverage
- `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`

### Ordered Steps

1. Add fail-first Flutter widget/shared-shell coverage for the refreshed MVP contract:
   - basic AppBar-style collapsed title stays vertically centered with `maxLines: 2`,
   - `Acontecendo Agora` navigates on tap,
   - agenda cards emphasize event title first and counterpart profiles second,
   - tabs render in fixed `Sobre | Agenda | Como Chegar` order,
   - social metrics are absent,
   - horizontal swipe activates adjacent tabs in immersive layouts.
2. Simplify the Account Profile collapsed app bar to use standard AppBar title behavior instead of a custom collapsed-identity widget.
3. Adjust agenda card composition so event title is primary, counterpart profiles exclude the current host, and live-highlight cards navigate like standard agenda cards.
4. Enforce the fixed MVP tab order and remove social metrics/badges from Account Profile Detail.
5. Add lateral swipe support to the shared immersive shell so Account Profile and immersive Event detail both move to adjacent tabs with the same anchor-scroll behavior as a tap.
6. Run focused Flutter gates, update canonical docs only if durable shared-shell semantics changed, then resume manual smoke.

### Test Strategy

- `test-first`

### Fail-First Targets

- Collapsed Account Profile AppBar fails widget expectations when the title is one line or two wrapped lines.
- `Acontecendo Agora` fails a tap-navigation assertion relative to the standard agenda cards.
- Account Profile agenda cards fail expectations if they highlight the host profile instead of `event title -> counterpart profiles`.
- Account Profile tabs fail a fixed-order assertion when `Agenda` and `Como Chegar` both exist.
- Social metrics are still discoverable in the Account Profile detail surface.
- Shared immersive detail fails a swipe-to-adjacent-tab behavior assertion.
- Hero type badge fails widget expectations if it does not render a circular type-avatar surface with icon + type label.
- Taxonomy/category chips fail parser/widget expectations if they show slug-like `value` strings when the payload provides a canonical `name/label`.

### Runtime/Rollout Notes

- Expected runtime impact: `low`
- Runtime impact is limited to Flutter presentation/shell behavior; the occurrence-first agenda payload is already live and validated.
- Because the route is reused by discovery and event entrypoints, widget/shared-shell coverage must focus on presentation and navigation regressions.

---

## Plan Review Gate (Medium)

### Issue Card `APD-P1` — Shared-shell regression risk

- **Severity:** `high`
- **Evidence:** `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`, `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- **Why now:** this TODO targets a screen that sits on top of a shared immersive shell already used by event detail.
- **Option A:** patch the shared shell aggressively to fit the new layout.
  Effort: `medium`
  Risk: `high`
  Blast radius: `shared immersive detail surfaces`
  Maintenance burden: `medium-high`
- **Option B (recommended):** keep the shell API stable and solve as much as possible in the account-profile screen, introducing only minimal shared changes if strictly required.
  Effort: `medium`
  Risk: `low-medium`
  Blast radius: `screen-local` with tightly scoped shared fallback
  Maintenance burden: `medium`
- **Option C:** do nothing and accept current hierarchy.
  Effort: `none`
  Risk: `high`
  Blast radius: `current screen quality remains weak`
  Maintenance burden: `low`
- **Recommended:** `B`

### Issue Card `APD-P2` — Current hierarchy conflicts with approved composition

- **Severity:** `high`
- **Evidence:** `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart:79-86`, `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart:145-183`, `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- **Why now:** the current screen duplicates title responsibilities and keeps type chips in the hero overlay instead of clearly separating the header/taxonomy area above tabs.
- **Option A (recommended):** move the semantic header/taxonomy emphasis to the surface between hero and tabs while keeping the hero primarily media/branding/CTA oriented.
  Effort: `medium`
  Risk: `low`
  Blast radius: `screen-local`
  Maintenance burden: `medium`
- **Option B:** keep title/taxonomy in the hero and only restyle colors/spacing.
  Effort: `low`
  Risk: `medium`
  Blast radius: `screen-local`
  Maintenance burden: `low`
- **Option C:** postpone the composition fix and only touch CTA states.
  Effort: `low`
  Risk: `high`
  Blast radius: `screen-local`
  Maintenance burden: `low`
- **Recommended:** `A`

### Issue Card `APD-P3` — State feedback is under-specified and untested

- **Severity:** `medium`
- **Evidence:** `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart:51-64`
- **Why now:** the TODO explicitly requires clearer loading/empty/error/content states, but the current screen only shows plain center widgets and has no dedicated widget-test gate.
- **Option A (recommended):** add targeted widget coverage and redesign the state containers with clear hierarchy.
  Effort: `medium`
  Risk: `low`
  Blast radius: `screen-local + tests`
  Maintenance burden: `medium`
- **Option B:** polish visually without adding automated coverage.
  Effort: `low-medium`
  Risk: `medium-high`
  Blast radius: `screen-local`
  Maintenance burden: `medium-high`
- **Option C:** keep current state rendering.
  Effort: `none`
  Risk: `medium`
  Blast radius: `user-facing quality regression remains`
  Maintenance burden: `low`
- **Recommended:** `A`

### Issue Card `APD-P4` — Large single-file screen increases change risk

- **Severity:** `medium`
- **Evidence:** `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- **Why now:** the file contains hero, CTA, module renderers, and utility functions in one place, which makes visual changes harder to reason about.
- **Option A (recommended):** extract only the new/changed visual sections needed for this TODO if readability materially improves, but avoid full-screen decomposition unrelated to the polish scope.
  Effort: `medium`
  Risk: `low-medium`
  Blast radius: `screen-local`
  Maintenance burden: `medium`
- **Option B:** keep everything inline and layer more conditional UI on top.
  Effort: `low`
  Risk: `medium`
  Blast radius: `screen-local`
  Maintenance burden: `medium-high`
- **Option C:** perform a broad architectural refactor of the entire feature first.
  Effort: `high`
  Risk: `medium`
  Blast radius: `feature-wide`
  Maintenance burden: `medium`
- **Recommended:** `A`

### Failure Modes & Edge Cases

- No cover image: hero still needs a polished fallback.
- Non-favoritable profiles: footer/CTA must stay explicit without showing follow controls that do nothing.
- Long profile names and many taxonomy chips: mobile wrapping must remain readable.
- Profiles without bio or tags: header still needs coherent spacing above tabs.
- Profiles with neither about content nor agenda events: the fallback must invite favorite with the approved copy when the profile can still be favorited.
- Venue/event entrypoints: visual updates must not obscure the existing route continuity.

### Residual Unknowns / Risks

- Shared-shell adjustment may still be needed if tab/header pinning cannot support the approved hierarchy cleanly.
- Module placeholder sections may still look sparse for profile types with limited backend data, even after layout polish.
- Manual smoke is still required for real-device footer/tab behavior and small-screen overflow.

### Uncertainty Register

- **Assumptions:** `A-01..A-05`
- **Unknowns:** exact minimum shared-shell change, if any, after fail-first tests expose the tightest constraint
- **Confidence:** `medium`

---

## Module Decision Baseline Snapshot

| Source | Decision ID | Summary | Relevance Here |
| --- | --- | --- | --- |
| `flutter_client_experience_module.md` | `FCX-01` | Controllers own mutable state; widgets stay presentational. | Blocks logic drift into the screen while polishing UI. |
| `flutter_client_experience_module.md` | `FCX-02` | Scope/subscope ownership must stay canonical. | Preserves `tenant / tenant_public` detail-route ownership. |
| `flutter_client_experience_module.md` | `FCX-03` | Flutter consumes contracts through repositories/adapters. | Allows parser/repository adaptation without leaking transport details into the screen/controller. |
| `account_profile_catalog_module.md` | `PCO-01` | Account Profile is the canonical public identity layer. | Keeps the detail screen centered on account-profile public identity. |
| `events_module.md` | `D3-03` | Events read contracts remain occurrence-first. | Agenda backfill must reuse current event projections instead of inventing a parallel event payload family. |

## Module Decision Consistency Matrix

| Module Decision | Planned Handling | Evidence |
| --- | --- | --- |
| `FCX-01` | `Preserve` | Current screen/controller split already exists; TODO remains presentation-first. |
| `FCX-02` | `Preserve` | Route remains `/parceiro/:slug` under `tenant_public`. |
| `FCX-03` | `Preserve` | Flutter will adapt through DAO/parser/domain boundaries only; screen/controller stay transport-agnostic. |
| `PCO-01` | `Preserve` | Scope keeps Account Profile detail as the canonical public identity surface. |
| `D3-03` | `Preserve` | Agenda unblock will stay occurrence-first and must not reintroduce event-level collapsing; Flutter may adapt through dedicated parser/projection boundaries if needed. |

---

## Decision Baseline (Frozen)

- `D-01`: This TODO may extend the public account-profile detail payload only as needed to expose ordered occurrence-first `agenda_occurrences`; broader route/API/schema redesign remains out of scope.
- `D-02`: Existing route semantics and entry behavior from discovery, events, favorites, and invite-adjacent flows are preserved.
- `D-03`: Title and taxonomy/chip composition remain outside the tab card area and above tabs.
- `D-04`: Social metrics and social-style badges are removed from the Account Profile detail MVP surface.
- `D-05`: Hero, tabs/modules, and footer CTA may be polished visually without changing module semantics.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.
- `D-08`: Shared immersive-shell edits, if any, must remain backward-compatible for immersive event detail.
- `D-09`: Delivery requires targeted widget coverage for hierarchy/state regressions plus `fvm dart analyze --format machine`.
- `D-10`: Tabs remain sticky anchors over a continuous scroll surface; the polish stream must not convert the detail page into panel-swapping tab content.
- `D-11`: CTA resolution order is `relationship-state acquisition CTA -> active-section CTA -> fallback/global CTA`.
- `D-12`: Authentication stays outside the visual CTA state model and remains enforced by action guards/interceptors.
- `D-13`: Agenda uses one shared visual system across host types; only content emphasis varies by host context.
- `D-14`: Route-app selection options remain dynamic; screens consume a shared chooser output rather than hardcoding specific app rows.
- `D-15`: `Agenda` availability in Account Profile Detail is protected by the existing capability semantics plus real runtime materialization of ordered `agenda_occurrences`; tests must prove both the Laravel payload and Flutter parser/controller path.
- `D-16`: No runtime residue of the wrong `upcoming_event_ids -> getAllEvents() -> event dedupe` path may remain after delivery; Laravel, Flutter domain/parsers, and tests must converge on one occurrence-first contract.
- `D-17`: Account Profile collapsed header uses basic AppBar title behavior only: no avatar, no custom pill, standard leading spacing, vertically centered title, `maxLines: 2`, and ellipsis.
- `D-18`: `Acontecendo Agora` is navigable and must route to the same event-detail surface as the standard agenda cards.
- `D-19`: Account Profile agenda cards always prioritize `event title` first and counterpart account profiles second, excluding the current host profile from supporting identity slots.
- `D-20`: MVP tab order on Account Profile Detail is fixed as `Sobre | Agenda | Como Chegar`; dynamic ordering is outside this slice.
- `D-21`: Shared immersive detail tabs support lateral swipe navigation that triggers the same anchor-scroll/tab-selection behavior as tapping the adjacent tab.
- `D-22`: In Account Profile Detail, `Agenda` as an aggregate section does not expose an event-detail CTA in the footer; it falls back to the section/global fallback (`Favoritar` or empty if already favorited).
- `D-23`: The hero type visual must render as an isolated circular avatar using the type visual color and icon only, with no enclosing chip or label and with the same horizontal spacing rhythm as other adjacent hero affordances.
- `D-25`: Any remaining textual type presentation must resolve from the canonical profile-type display field (`label/name`) rather than the raw type token.
- `D-24`: Account Profile taxonomy/category chips must display canonical `name/label` from `taxonomy_terms` whenever provided, falling back to `value` only when no display label exists.
- `D-26`: Agenda cards keep the order `date/meta -> event title -> counterpart chips -> venue line`; counterpart chips exclude both the current host and the venue, while the venue/local always renders on its own line with a location icon and optional future address text.
- `D-27`: Home and Account Profile agendas must share the same `Upcoming Event` card family and state behavior (`invite tint`, `invite status icon`, optional distance, elastic media height); only the caller-prepared content model may differ.
- `D-28`: Counterpart exclusion belongs to the caller/resolver layer, not the shared event-card widget. Account Profile excludes current host + venue before rendering; Home passes all counterparts.
- `D-29`: The Account Profile venue line format is `📍 {VenueName} ({distance}) - {address}`, with distance/address groups rendered only when the corresponding value exists.
- `D-30`: Discovery-to-Account-Profile navigation must not poison the Discovery route/back stack. After returning from `/parceiro/:slug`, the Discovery back action returns to the prior parent route such as Home, not to the stale profile detail; route replacement/push/pop choices must preserve browser and in-app back semantics.

---

## Tasks

- [x] ✅ Keep the existing base page (`PartnerDetailRoute` + `AccountProfileDetailScreen`) as the canonical account-profile detail UI.
- [x] ✅ Ensure header + taxonomy/chip composition remain above tabs for all supported profile types.
- [x] ✅ Polish hero media, header spacing, tabs/modules, and CTA hierarchy.
- [x] ✅ Preserve the anchor-tab architecture while refining the bottom CTA model around relationship-state priority vs active-section CTA.
- [x] ✅ Improve loading/empty/error/content states.
- [x] ✅ Replace `upcoming_event_ids` with ordered `agenda_occurrences` on the public account-profile payload used by detail/runtime consumers.
- [x] ✅ Parse `agenda_occurrences` into occurrence-level account-profile agenda data so real runtime agenda can materialize without schedule repository indirection.
- [x] ✅ Remove the wrong `upcoming_event_ids` contract and all runtime consumption leftovers from Laravel, Flutter, tests, and docs.
- [x] ✅ Replace the custom collapsed identity widget with a basic AppBar-style collapsed title.
- [x] ✅ Make `Acontecendo Agora` navigate to the highlighted event.
- [x] ✅ Recompose agenda cards so event title is primary and counterpart profiles exclude the current host.
- [x] ✅ Enforce fixed MVP tab order `Sobre | Agenda | Como Chegar`.
- [x] ✅ Remove social metrics/badges from Account Profile Detail.
- [x] ✅ Add swipe-to-adjacent-tab navigation in the shared immersive shell.
- [x] ✅ Refine the hero type visual to render only an isolated circular avatar with the type icon.
- [x] ✅ Replace the no-sections fallback copy with the approved favorite CTA when the profile is favoritable and not yet favorited.
- [x] ✅ Prefer taxonomy term `name/label` over slug-like `value` when rendering category chips.
- [x] ✅ Validate mobile breakpoints, text/chip overflow behavior, and live POI rendering on real profiles.
- [x] ✅ Ensure ingress into `/parceiro/:slug` from discovery/events/favorites/invite-adjacent entrypoints remains behavior-compatible.
- [x] ✅ Fix the Discovery -> Account Profile detail -> Discovery -> Home back-stack regression so the Discovery back action does not reopen the previously visited profile.
- [x] ✅ Add targeted widget coverage for hierarchy/state/CTA regressions.
- [x] ✅ Establish the shared `Traçar rota` chooser with native installed-app discovery and mobile-web curated fallback, without hardcoding option rows in the screen.

## Acceptance Criteria

- [x] ✅ Detail hierarchy is clearer while preserving current module semantics.
- [x] ✅ Header/taxonomy placement and CTA affordances remain explicit on all supported profile types.
- [x] ✅ Tabs still behave as sticky section anchors, and CTA resolution follows the approved `relationship-state -> active section -> fallback` precedence.
- [x] ✅ Public account-profile detail payload exposes ordered occurrence-first `agenda_occurrences` for future/live agenda materialization.
- [x] ✅ Flutter runtime materializes `Agenda` from occurrence-level payload data directly, not through event-id indirection or catalog fetches.
- [x] ✅ Collapsed AppBar behaves like a basic AppBar title surface while still supporting 2-line ellipsis.
- [x] ✅ `Acontecendo Agora` navigates like the other agenda cards.
- [x] ✅ Agenda cards emphasize the event title and only counterpart profiles in supporting content.
- [x] ✅ Tabs render in fixed `Sobre | Agenda | Como Chegar` order for the MVP.
- [x] ✅ Social metrics/badges are absent from the MVP surface.
- [x] ✅ Lateral swipe switches immersive tabs with the same effect as tapping the adjacent tab.
- [x] ✅ Hero type visual renders only an isolated circular type-avatar, with no chip container and no label.
- [x] ✅ Profiles with no about content and no agenda events show `Favorite para ser avisado das novidades sobre {account profile name}.` when the favorite action is still available.
- [x] ✅ Category chips display canonical taxonomy names instead of slug-like values when the payload provides them.
- [x] ✅ Entry points from discovery/events/invites remain coherent and regression-free.
- [x] ✅ Loading/empty/error/content states are visually distinguishable and test-backed where practical.
- [x] ✅ No navigation, scope, or unintended backend-contract regressions are introduced.
- [x] ✅ Discovery back-stack behavior remains coherent after visiting an Account Profile detail: returning to Discovery restores the list context, and pressing Discovery back returns Home rather than reopening stale detail.
- [x] ✅ Route-choice UI is shared, theme-driven, and dynamic across native/mobile-web constraints.

## Definition of Done

- [x] ✅ All tasks and acceptance criteria are checked with evidence.
- [x] ✅ Targeted detail-screen widget coverage is green.
- [x] ✅ `fvm dart analyze --format machine` is clean.
- [x] ✅ Laravel feature coverage is green for `agenda_occurrences` payload materialization.
- [x] ✅ Refreshed Flutter/widget/shared-shell coverage is green for the new collapsed header, live-card navigation, agenda hierarchy, fixed tab order, social-meta removal, and swipe navigation behavior.
- [x] ✅ Manual smoke confirms state handling, footer/CTA clarity, and detail-route ingress continuity.
- [x] ✅ Account-profile agenda contract change is reflected in canonical module docs and validated end-to-end through focused automated gates.
- [x] ✅ Final Web navigation evidence proves the Discovery back-stack regression is fixed after `bash scripts/build_web.sh ../web-app dev`.

## Validation Steps

- [x] ✅ Automated: targeted widget tests for hierarchy/state/CTA regressions.
- [x] ✅ Automated: Laravel feature test for `GET /account_profiles/{slug}` returning ordered `agenda_occurrences`, including repeated `event_id` with distinct occurrences.
- [x] ✅ Automated: Flutter parser/controller tests for `agenda_occurrences` materialization without `getAllEvents()` fallback.
- [x] ✅ Automated: `fvm dart analyze --format machine`.
- [x] ✅ Automated: widget/shared-shell tests for collapsed AppBar alignment, live-card navigation, agenda hierarchy, fixed tab order, social-meta removal, and swipe navigation.
- [x] ✅ Automated: widget coverage for the isolated hero type-avatar plus parser/widget coverage for taxonomy chip name/label precedence.
- [x] ✅ Automated: widget coverage for the no-sections favorite CTA across favoritable, already-favorited, and non-favoritable states.
- [x] ✅ Playwright navigation/visual tests for every `NAV-APD-*` row below after `bash scripts/build_web.sh ../web-app dev`; widget/unit/analyzer evidence is supporting only for visible runtime behavior.
- [x] ✅ Manual smoke: detail screen loading/empty/error/content states.
- [x] ✅ Manual smoke: event/favorite/discovery entrypoints into `/parceiro/:slug`.
- [x] ✅ Playwright navigation after `bash scripts/build_web.sh ../web-app dev`: Home -> Discovery -> Account Profile detail -> browser/app back to Discovery -> Discovery back to Home, asserting no stale `/parceiro/:slug` route reopens.
- [x] ✅ Manual smoke: mobile breakpoint validation for long title + taxonomy chips.
- [x] ✅ Manual smoke: header/taxonomy placement across supported profile types.
- [x] ✅ Manual smoke: native/mobile-web `Traçar rota` chooser behavior, including tile in-app map continuity and external app/web fallback behavior.

## Required Runtime Navigation / Visual Matrix

| ID | Decisions | Flow / Surface | Positive validation | Negative / absence validation |
| --- | --- | --- | --- | --- |
| `NAV-APD-01` | `D-02`, `D-30` | Discovery -> Account Profile detail -> Discovery -> Home back stack. | Starting on Home, navigate to Discovery, open an Account Profile detail from a real Discovery card, go back to Discovery, then use Discovery's own back affordance/browser route stack to return Home. The final URL/screen must be Home and the Discovery list state must not be replaced by profile detail. | The stale `/parceiro/:slug` route must not reopen after returning to Discovery; repeated back actions must not loop between Discovery and the previously opened Account Profile; direct reload of Discovery must not inherit stale detail route state. |
| `NAV-APD-02` | `D-17` | Collapsed AppBar behavior on `/parceiro/:slug`. | Open a profile with a long name, scroll until the header collapses, and assert the collapsed title behaves like a standard AppBar title with up to two lines and ellipsis. | Collapsed header must not show avatar, pill, custom identity chip, vertical misalignment, clipped text, or action overlap. |
| `NAV-APD-03` | `D-23`, `D-25` | Hero type visual. | Open a profile with configured profile-type visual and assert the hero renders only an isolated circular type-avatar with the type icon/color. | No enclosing chip, no adjacent type label, and no raw profile-type token may appear in the hero; any textual type fallback must use canonical `label/name`. |
| `NAV-APD-04` | `D-24` | Taxonomy/category chip display labels. | Open a profile whose taxonomy term has a display `name/label` different from the slug and assert the public chip shows the display label. | Slug-like `value` must not render when `name/label` is available; fallback to `value` is allowed only when no display label exists. |
| `NAV-APD-05` | `D-10`, `D-20`, `D-21` | Sticky tabs, anchor scroll, and swipe navigation. | Assert tab order is exactly `Sobre | Agenda | Como Chegar`; tapping tabs scrolls to anchors; lateral swipe moves to adjacent tab through the same anchor-scroll behavior. | The page must not switch to panel-swapping tab content, lose continuous scroll content, reorder tabs dynamically, or desynchronize highlighted tab from visible section. |
| `NAV-APD-06` | `D-11`, `D-22` | No-sections favorite CTA and footer precedence. | Open a favoritable, not-yet-favorited profile with no about content and no agenda; assert the fallback CTA copy is `Favorite para ser avisado das novidades sobre {account profile name}.` | The old `Mais sobre este perfil` fallback must not appear; already-favorited or non-favoritable profiles must not show an invalid favorite invitation. |
| `NAV-APD-07` | `D-15`, `D-16` | Occurrence-first agenda materialization. | Open a profile with ordered `agenda_occurrences`, including repeated `event_id` with distinct occurrences, and assert the agenda renders occurrence cards in order without requiring a broad event catalog fetch. | Distinct occurrences must not collapse into one event card; stale `upcoming_event_ids -> getAllEvents()` behavior or broad event fetch dependency must fail the test. |
| `NAV-APD-08` | `D-18`, `D-19`, `D-26`, `D-27`, `D-28` | Account Profile agenda cards and event navigation. | Assert `Acontecendo Agora` and standard agenda cards both navigate to the same event-detail surface; cards render event title first, counterpart chips second, and venue line separately. | Current host profile and venue must not appear as counterpart chips; venue/local must not be used as the live-card eyebrow or collapsed into ad-hoc subtitle text. |
| `NAV-APD-09` | `D-14`, `D-29` | `Como Chegar`, `Ver no mapa`, and route chooser. | Open a profile with POI/location data, assert `Ver no mapa` navigates to the in-app Map focused on that profile POI, and `Traçar rota` opens the shared chooser/fallback appropriate to runtime. | The screen must not hardcode app rows locally, render dangling `()` or ` - ` in the venue/address line, show placeholder address text, or navigate to an unfocused generic Map route. |
| `NAV-APD-10` | `D-04` | Social metrics removal. | Open profiles that previously could show social counters/badges and assert the MVP surface omits social metrics. | Social counters, social badges, or follower-like metrics must not reappear through profile type variants or loading/restored states. |

Runtime navigation/visual rows require Playwright against the final served Web bundle after `bash scripts/build_web.sh ../web-app dev`. Widget/unit/analyzer evidence is supporting only and cannot close any `NAV-APD-*` row.

---

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| APD-DOD-01 | Definition of Done | ✅ All tasks and acceptance criteria are checked with evidence. | Completion matrix + Playwright navigation + focused tests | `bash scripts/build_web.sh ../web-app dev`; served/local hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)`; `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec `tools/flutter/web_app_tests/account_profile_detail.spec.js`; `fvm dart analyze --format machine` passed; focused Flutter suite passed `48 passed`; Laravel safe-runner passed `158 passed (938 assertions)`. | Final Web browser runtime `https://guarappari.belluga.space` + Flutter host + Laravel local Docker | passed | Matrix rows map each DOD/validation item to concrete evidence; APD visible rows are covered by `NAV-APD-*` Playwright tests. |
| APD-DOD-02 | Definition of Done | ✅ Targeted detail-screen widget coverage is green. | Flutter widget tests + Playwright runtime support | Focused Flutter suite passed `48 passed`; APD widget coverage includes loading/empty/error, hero, CTA, agenda, tabs, swipe, social removal, and directions; final Web readonly passed `9 passed (2.7m)` after `bash scripts/build_web.sh ../web-app dev`. | Flutter host + final Web browser runtime | passed | Widget evidence is supporting; readonly APD runtime covers visible detail-screen rows. |
| APD-DOD-03 | Definition of Done | ✅ `fvm dart analyze --format machine` is clean. | Analyzer | `fvm dart analyze --format machine` exited `0`; no analyzer diagnostics remained after final reconciliation. | Flutter host | passed | Official analyzer command from AGENTS.md used. |
| APD-DOD-04 | Definition of Done | ✅ Laravel feature coverage is green for `agenda_occurrences` payload materialization. | Laravel feature tests + Playwright mutation | Laravel safe-runner command passed `158 passed (938 assertions)`; APD mutation test `@mutation NAV-APD-07..08 agenda is occurrence-first and cards navigate to event detail` passed in `tools/flutter/run_web_navigation_smoke.sh mutation` `16 passed (7.4m)`. | Laravel local Docker + final Web browser runtime | passed | Runtime proves Account Profile detail consumes `agenda_occurrences` without broad event catalog fetch. |
| APD-DOD-05 | Definition of Done | ✅ Refreshed Flutter/widget/shared-shell coverage is green for the new collapsed header, live-card navigation, agenda hierarchy, fixed tab order, social-meta removal, and swipe navigation behavior. | Flutter widget tests + Playwright readonly/mutation | Focused Flutter suite passed `48 passed`; final Web readonly passed `9 passed (2.7m)` for `NAV-APD-02..06`, `NAV-APD-10`, and mobile breakpoint; final Web mutation passed `16 passed (7.4m)` for agenda card navigation. | Flutter host + final Web browser runtime | passed | Browser rows validate collapsed title visibility, tab order, social removal, favorite fallback, and agenda event navigation. |
| APD-DOD-06 | Definition of Done | ✅ Manual smoke confirms state handling, footer/CTA clarity, and detail-route ingress continuity. | Playwright readonly/mutation + widget state tests | `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)` and `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)` after `bash scripts/build_web.sh ../web-app dev`; widget tests cover loading/empty/error/favorite CTA states. | Final Web browser runtime + Flutter host | passed | Automated runtime replaces manual-only smoke: back-stack, hero/taxonomy, tabs, favorite empty state, agenda ingress, and directions route chooser are exercised. |
| APD-DOD-07 | Definition of Done | ✅ Account-profile agenda contract change is reflected in canonical module docs and validated end-to-end through focused automated gates. | Canonical docs + Laravel/Flutter/Playwright tests | Module anchors remain in `account_profile_catalog_module.md`/`events_module.md`; Laravel safe-runner passed `158 passed (938 assertions)`; APD mutation `NAV-APD-07..08` passed in `16 passed (7.4m)`. | Foundation docs + Laravel local Docker + final Web browser runtime | passed | Contract is validated from backend payload through public detail runtime navigation. |
| APD-DOD-08 | Definition of Done | ✅ Final Web navigation evidence proves the Discovery back-stack regression is fixed after `bash scripts/build_web.sh ../web-app dev`. | Web build + Playwright readonly navigation | `bash scripts/build_web.sh ../web-app dev`; served/local hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; Playwright source spec `tools/flutter/web_app_tests/account_profile_detail.spec.js`; canonical runner `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)`, including `@readonly NAV-APD-01 Discovery profile detail back stack does not reopen stale detail`. | Final Web browser runtime `https://guarappari.belluga.space` | passed | Runtime journey starts on Home, opens Discovery, opens Account Profile detail from a real card, returns to Discovery, then returns Home without stale `/parceiro/:slug` reopening. |
| APD-VAL-01 | Validation Steps | ✅ Automated: targeted widget tests for hierarchy/state/CTA regressions. | Flutter widget tests + Playwright readonly support | Focused Flutter suite passed `48 passed`; final Web readonly passed `9 passed (2.7m)` with APD hierarchy/state/CTA visible rows. | Flutter host + final Web browser runtime | passed | Covers loading/empty/error, footer/favorite CTA, tabs, social removal, and hero/taxonomy visible behavior. |
| APD-VAL-02 | Validation Steps | ✅ Automated: Laravel feature test for `GET /account_profiles/{slug}` returning ordered `agenda_occurrences`, including repeated `event_id` with distinct occurrences. | Laravel feature tests + Playwright mutation | Laravel safe-runner passed `158 passed (938 assertions)`; APD mutation `NAV-APD-07..08` passed in `16 passed (7.4m)` and asserts occurrence-first agenda on public detail. | Laravel local Docker + final Web browser runtime | passed | Distinct occurrences are not collapsed into event-level cards. |
| APD-VAL-03 | Validation Steps | ✅ Automated: Flutter parser/controller tests for `agenda_occurrences` materialization without `getAllEvents()` fallback. | Flutter parser/controller tests + Playwright request assertion | Focused Flutter suite passed `48 passed`; APD mutation `NAV-APD-07..08` passed and records no broad `/api/v1/events` catalog fetch while rendering `agenda_occurrences`. | Flutter host + final Web browser runtime | passed | Runtime request assertion prevents hidden broad-fetch fallback. |
| APD-VAL-04 | Validation Steps | ✅ Automated: `fvm dart analyze --format machine`. | Analyzer | `fvm dart analyze --format machine` exited `0`. | Flutter host | passed | Official analyzer gate is clean. |
| APD-VAL-05 | Validation Steps | ✅ Automated: widget/shared-shell tests for collapsed AppBar alignment, live-card navigation, agenda hierarchy, fixed tab order, social-meta removal, and swipe navigation. | Flutter widget/shared-shell tests + Playwright runtime | Focused Flutter suite passed `48 passed`; final Web readonly passed `9 passed (2.7m)` for visual rows; mutation passed `16 passed (7.4m)` for agenda card event navigation. | Flutter host + final Web browser runtime | passed | Shared-shell widget tests are supported by final-domain APD navigation. |
| APD-VAL-06 | Validation Steps | ✅ Automated: widget coverage for the isolated hero type-avatar plus parser/widget coverage for taxonomy chip name/label precedence. | Flutter parser/widget tests + Playwright readonly | Focused Flutter suite passed `48 passed`; readonly APD test `NAV-APD-02..06 and NAV-APD-10` passed inside `9 passed (2.7m)`; readonly taxonomy snapshot spec also passed and validates labels instead of slugs. | Flutter host + final Web browser runtime | passed | Runtime asserts display label/name appears and slug-like value does not appear when label exists. |
| APD-VAL-07 | Validation Steps | ✅ Automated: widget coverage for the no-sections favorite CTA across favoritable, already-favorited, and non-favoritable states. | Flutter widget tests + Playwright readonly | Widget tests cover favoritable/not-favorited, already-favorited, and neutral states; readonly APD test passed in `9 passed (2.7m)` and asserts public favorite invitation replaces `Mais sobre este perfil`. | Flutter host + final Web browser runtime | passed | Browser confirms the visible public fallback copy on a runtime profile; widget tests cover state permutations. |
| APD-VAL-08 | Validation Steps | ✅ Playwright navigation/visual tests for every `NAV-APD-*` row below after `bash scripts/build_web.sh ../web-app dev`; widget/unit/analyzer evidence is supporting only for visible runtime behavior. | Web build + Playwright readonly/mutation | `bash scripts/build_web.sh ../web-app dev`; local/served hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)` and `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`; source spec `tools/flutter/web_app_tests/account_profile_detail.spec.js`. | Final Web browser runtime `https://guarappari.belluga.space` | passed | `NAV-APD-01..10` plus `NAV-APD-12` mobile breakpoint are covered by final-domain Playwright. |
| APD-VAL-09 | Validation Steps | ✅ Manual smoke: detail screen loading/empty/error/content states. | Flutter widget tests + Playwright content runtime | Widget tests `shows loading state`, `shows empty state`, and `shows error state` passed in focused APD coverage; final Web readonly passed `9 passed (2.7m)` and opens real content detail routes. | Flutter host + final Web browser runtime | passed | Empty/loading/error are widget-owned states; public content state is runtime-validated. |
| APD-VAL-10 | Validation Steps | ✅ Manual smoke: event/favorite/discovery entrypoints into `/parceiro/:slug`. | Playwright readonly/mutation + widget tests | `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)` for Discovery entry/back stack; `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)` for agenda/event-related profile detail flows; favorite CTA states covered by widget tests. | Final Web browser runtime + Flutter host | passed | Entry continuity is covered through Discovery and event agenda surfaces; favorite CTA is state-tested. |
| APD-VAL-11 | Validation Steps | ✅ Playwright navigation after `bash scripts/build_web.sh ../web-app dev`: Home -> Discovery -> Account Profile detail -> browser/app back to Discovery -> Discovery back to Home, asserting no stale `/parceiro/:slug` route reopens. | Web build + Playwright readonly route navigation | `bash scripts/build_web.sh ../web-app dev`; hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; Playwright source spec `tools/flutter/web_app_tests/account_profile_detail.spec.js`; canonical runner `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)`, test `@readonly NAV-APD-01 Discovery profile detail back stack does not reopen stale detail`. | Final Web browser runtime `https://guarappari.belluga.space` | passed | This Playwright route proof verifies the browser route returns Home and stale `/parceiro/:slug` does not reopen after manual discovery. |
| APD-VAL-12 | Validation Steps | ✅ Manual smoke: mobile breakpoint validation for long title + taxonomy chips. | Playwright readonly mobile viewport + widget support | Added source test `@readonly NAV-APD-12 mobile breakpoint keeps title and taxonomy chips readable`; `node --check tools/flutter/web_app_tests/account_profile_detail.spec.js` passed; final `tools/flutter/run_web_navigation_smoke.sh readonly` passed `9 passed (2.7m)` after Web build, using viewport `390x844`. | Final Web browser runtime mobile viewport | passed | Runtime opens the longest available public profile at mobile width, verifies title remains visible before/after scroll, and taxonomy display label remains visible without slug fallback. |
| APD-VAL-13 | Validation Steps | ✅ Manual smoke: header/taxonomy placement across supported profile types. | Playwright readonly + taxonomy snapshot test | Readonly APD visual test and taxonomy snapshot test passed in `9 passed (2.7m)`; supporting parser/widget coverage passed in focused Flutter suite. | Final Web browser runtime + Flutter host | passed | Runtime validates profile detail header/taxonomy label placement on public Account Profile routes. |
| APD-VAL-14 | Validation Steps | ✅ Manual smoke: native/mobile-web `Traçar rota` chooser behavior, including tile in-app map continuity and external app/web fallback behavior. | Playwright mutation + shared chooser widget tests | `bash scripts/build_web.sh ../web-app dev`; served/local hash `2dbce056b1f350e1fa7a279025c5d3d82d89dc2f8f9a76c9e589af8d968bf1c4`; Playwright source spec `tools/flutter/web_app_tests/account_profile_detail.spec.js`; canonical runner `tools/flutter/run_web_navigation_smoke.sh mutation` passed `16 passed (7.4m)`, including `@mutation NAV-APD-09 Como Chegar opens focused map and shared route chooser`; widget coverage for shared directions chooser remains green in focused suites. | Final Web browser runtime + Flutter host | passed | Runtime verifies `Ver no mapa` opens focused `/mapa?poi=account_profile...` and `Traçar rota` opens the shared route chooser; no Android/Web divergence was introduced for this web validation slice. |

## Decision Closure

- [x] None for product/design handoff. `D-30` closes the Discovery back-stack regression contract, and `NAV-APD-01..10` define the required runtime proof.

## Questions To Close

- [x] None before implementation. Remaining choices are implementation-local and must preserve the route/back-stack semantics, public detail visual contract, and Playwright evidence requirements above.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** Fail-first route/navigation tests for `NAV-APD-01`, followed by the remaining `NAV-APD-*` visual/runtime rows after `bash scripts/build_web.sh ../web-app dev`.
- **Sequencing note:** The Discovery back-stack regression was the release-facing constraint and is now validated before the renewed delivery-stage claim; hero type-avatar and no-sections favorite CTA remain in the same TODO as visible rows.

---

## Decision Adherence Validation

| Decision ID | Status | Evidence |
| --- | --- | --- |
| `D-01` | `Adherent` | `../laravel-app/app/Application/AccountProfiles/AccountProfileFormatterService.php` now exposes ordered `agenda_occurrences`, and `../laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` proves repeated `event_id` values remain distinct by `occurrence_id`. |
| `D-02` | `Adherent` | No route/resolver changes; focused regression run stayed green in `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`. |
| `D-03` | `Adherent` | Identity cluster stays above tab chrome in the hero gradient, with chips and type badge separated from section tabs in `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`. |
| `D-04` | `Adherent` | Social metrics/badges were removed from `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`, with widget proof in `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`. |
| `D-05` | `Adherent` | Hero, section blocks, CTA, and states were polished without changing configured module ids or route semantics. |
| `D-06` | `Adherent` | New surfaces derive from `Theme.of(context).colorScheme`; no hardcoded alternative theme system was introduced. |
| `D-07` | `Adherent` | Agenda enrichment and screen-level error state remain controller-owned in `lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart`. |
| `D-08` | `Adherent` | Shared-shell edit was limited to `collapsedTitle` support + tab refresh in `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`, with immersive event detail regression green. |
| `D-09` | `Adherent` | `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`, `fvm flutter test test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/schedule/feature_venue_profile_widgets_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`, and `fvm dart analyze --format machine` passed. |
| `D-10` | `Adherent` | Tabs still use the existing anchor-shell architecture; no panel-swapping behavior was introduced. |
| `D-11` | `Adherent` | Footer resolution now prioritizes relationship CTA before active agenda/directions CTA in `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`, with widget proof in `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`. |
| `D-12` | `Adherent` | Authentication handling stayed in action guard/redirect flows; visual state does not branch on auth. |
| `D-13` | `Adherent` | `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart` now keeps one agenda widget family while prioritizing `event title -> counterpart profiles` dynamically from occurrence context. |
| `D-14` | `Adherent` | Route-choice rows are now generated by the shared chooser in `lib/presentation/shared/widgets/directions_app_chooser/**`, with widget coverage in `test/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_test.dart` and screen wiring proof in `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`. |
| `D-15` | `Adherent` | `lib/domain/partners/account_profile_model.dart`, `lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart`, and `test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart` now materialize `Agenda` directly from ordered `agenda_occurrences`. |
| `D-16` | `Adherent` | `lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart` no longer depends on `ScheduleRepository.getAllEvents()`, and `rg` audit plus focused tests show no runtime `upcoming_event_ids` residue remains in Laravel/Flutter code paths. |
| `D-17` | `Adherent` | Collapsed title now uses the shared `SliverAppBar` title path with `maxLines: 2`, ellipsis, and no avatar/pill in `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`. |
| `D-18` | `Adherent` | `Acontecendo Agora` cards now navigate to `/agenda/evento/:slug`, with widget proof in `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`. |
| `D-19` | `Adherent` | Agenda cards now render event title first and counterpart profiles only, excluding the current host and keeping location as separate meta. |
| `D-20` | `Adherent` | `_buildTabsFromConfig` now sorts tabs into fixed MVP order, with widget proof in `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`. |
| `D-21` | `Adherent` | Shared immersive shell now exposes lateral swipe through `onHorizontalDragEnd -> onHorizontalSwipeEnd`, with coverage in `test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_controller_test.dart`, `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`, and `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`. |
| `D-22` | `Adherent` | `Agenda` footer already falls back to `Favoritar` or empty-if-favorited rather than opening a single event CTA. |
| `D-23` | `Adherent` | Hero type affordance is covered by focused widget tests and final Web readonly `@readonly NAV-APD-02..06 and NAV-APD-10 hero, taxonomy, tabs, social removal, and favorite empty state are visible`, which passed in `tools/flutter/run_web_navigation_smoke.sh readonly` `9 passed (2.7m)`. |
| `D-24` | `Adherent` | `lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart` now prefers `taxonomy_terms.name/label` over `value`, with parser proof in `test/infrastructure/dal/laravel_account_profiles_backend_test.dart`. |

## Module Decision Consistency Validation

| Module Decision | Delivery Status | Evidence |
| --- | --- | --- |
| `FCX-01` | `Adherent` | Mutable orchestration moved into `AccountProfileDetailController`; screen remains presentation-first. |
| `FCX-02` | `Adherent` | Delivery kept `/parceiro/:slug` and `tenant_public` ownership untouched. |
| `FCX-03` | `Adherent` | Transport adaptation stays inside `../laravel-app/app/Application/AccountProfiles/AccountProfileFormatterService.php`, `lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart`, and `AccountProfileModel`; screen/controller remain transport-agnostic. |
| `PCO-01` | `Adherent` | Detail surface still centers the account profile public identity and partner-facing modules. |
| `D3-03` | `Adherent` | `../laravel-app/app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php` streams ordered `event_occurrences`, and Flutter consumes them as `PartnerEventView` occurrences without event-level collapsing. |

## Security Risk Assessment

- **Risk level:** `low`
- **Attack surface in scope:** tenant-public account-profile read contract, public slug detail payload, Flutter parser/controller compatibility
- **Attack simulation decision:** `not_needed`
- **Rationale:** scope extends a read-only public payload with no auth or write-path change, but still requires contract/regression coverage.

## Delivery Confidence Gate

- **Runtime impact:** `low-medium`
- **Confidence:** `high`
- **Readiness outcome:** `Local implementation and final Web navigation evidence are green; remaining gate is manual validation/promotion only.`
