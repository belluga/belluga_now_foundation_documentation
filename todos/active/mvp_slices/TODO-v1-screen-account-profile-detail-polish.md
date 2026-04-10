# TODO (V1): Screen Polish - Account Profile Detail

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Automated-Validated`, `Manual-Smoke-Pending`
**Next exact step:** Implement the remaining follow-ups on the hero type-avatar and the no-sections favorite empty state, then run the final manual smoke on Home + `/parceiro/:slug`, validating the shared `Upcoming Event` family, locale-fixed date labels, hero/avatar polish, `Acontecendo Agora`, `Ver no mapa`, swipe tabs, and CTA behavior.
**Owners:** Flutter Team, Laravel Team
**Objective:** Finish the tenant-public Account Profile detail polish by aligning the remaining immersive behavior with the approved MVP model: a basic collapsed AppBar, occurrence-first agenda with event-first hierarchy, fixed `Sobre | Agenda | Como Chegar` ordering, no social metrics, swipe-driven tab navigation shared by Account Profile and immersive Event detail, a hero type visual rendered as an isolated circular avatar with the type icon, taxonomy chips that display canonical names instead of slug-like values, and a shared `Upcoming Event` card contract between Home and Account Profile agendas.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`

**Blocker Notes:** The Laravel/contract blocker, locale drift blocker, and Home/UI convergence blocker are all resolved. The only remaining gate is manual runtime validation.
**Last confirmed truth:** `2026-04-04` automated validation confirms that Home and Account Profile now share the same `Upcoming Event` card family. `VenueEventResume` carries event-type + venue-title metadata, `DateGroupedEventList` uses the shared card, Account Profile uses the same card for standard agenda items, and the app shell remains locked to `pt_BR`. New follow-up intake on `2026-04-08`: when a profile has no about content and no events, the no-sections fallback must switch from `Mais sobre este perfil` to the favorite CTA `Favorite para ser avisado das novidades sobre {account profile name}.`

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `../foundation_documentation/modules/partner_catalog_and_offer_module.md`, `../foundation_documentation/modules/events_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for controller-owned state, route/scope ownership, and Flutter testing discipline. No partial migration flag is declared for the touched surface.
- `partner_catalog_and_offer_module.md`: authoritative for account-profile public detail contracts and consumer-facing identity semantics. No partial migration flag is declared for the touched surface.
- `events_module.md`: authoritative only for event-detail-to-profile continuity; no partial migration flag is declared for the touched entrypoint.

### Decision Consolidation Targets

- Promote stable cross-screen/shared-shell decisions to `../foundation_documentation/modules/flutter_client_experience_module.md` only if this TODO changes durable shell or test-governance behavior.
- Promote stable account-profile detail presentation/CTA decisions to `../foundation_documentation/modules/partner_catalog_and_offer_module.md` only if this TODO changes durable consumer-facing detail semantics beyond tactical polish.

---

## References

- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
- `../foundation_documentation/policies/scope_subscope_governance.md`
- `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/domain/partners/services/partner_profile_config_builder.dart`
- `lib/application/router/modular_app/modules/discovery_module.dart`

---

## Scope (Single Screen)

- Keep the existing `PartnerDetailRoute` and resolver flow as the canonical Account Profile detail entrypoint.
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
- Remove social metrics/badges from the Account Profile detail MVP surface.
- Add lateral swipe navigation between adjacent anchor tabs in the shared immersive shell for both Account Profile and immersive Event detail.
- Preserve behavior compatibility for discovery, event detail venue CTA, favorites, and any invite-related entrypoints that already resolve this route.
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
- `TODO-v1-account-profile-ui.md` decisions `B1`, `B5`, `B9`: preserve current detail route behavior and entrypoint continuity while keeping the execution scoped to the minimum necessary surfaces.

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
- MVP language is fixed to `pt_BR`; multilanguage is explicitly deferred.
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

- `foundation_documentation/todos/active/mvp_slices/TODO-v1-screen-account-profile-detail-polish.md`
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
- **Evidence:** `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart:79-86`, `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart:145-183`, `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md:72`
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
- **Option C:** defer the composition fix and only touch CTA states.
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
| `partner_catalog_and_offer_module.md` | `PCO-01` | Account Profile is the canonical public identity layer. | Keeps the detail screen centered on account-profile public identity. |
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
- `D-20`: MVP tab order on Account Profile Detail is fixed as `Sobre | Agenda | Como Chegar`; dynamic ordering is deferred.
- `D-21`: Shared immersive detail tabs support lateral swipe navigation that triggers the same anchor-scroll/tab-selection behavior as tapping the adjacent tab.
- `D-22`: In Account Profile Detail, `Agenda` as an aggregate section does not expose an event-detail CTA in the footer; it falls back to the section/global fallback (`Favoritar` or empty if already favorited).
- `D-23`: The hero type visual must render as an isolated circular avatar using the type visual color and icon only, with no enclosing chip or label and with the same horizontal spacing rhythm as other adjacent hero affordances.
- `D-25`: Any remaining textual type presentation must resolve from the canonical profile-type display field (`label/name`) rather than the raw type token.
- `D-24`: Account Profile taxonomy/category chips must display canonical `name/label` from `taxonomy_terms` whenever provided, falling back to `value` only when no display label exists.
- `D-26`: Agenda cards keep the order `date/meta -> event title -> counterpart chips -> venue line`; counterpart chips exclude both the current host and the venue, while the venue/local always renders on its own line with a location icon and optional future address text.
- `D-27`: Home and Account Profile agendas must share the same `Upcoming Event` card family and state behavior (`invite tint`, `invite status icon`, optional distance, elastic media height); only the caller-prepared content model may differ.
- `D-28`: Counterpart exclusion belongs to the caller/resolver layer, not the shared event-card widget. Account Profile excludes current host + venue before rendering; Home passes all counterparts.
- `D-29`: The Account Profile venue line format is `📍 {VenueName} ({distance}) - {address}`, with distance/address groups rendered only when the corresponding value exists.

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
- [ ] ⚪ Refine the hero type visual to render only an isolated circular avatar with the type icon.
- [ ] ⚪ Replace the no-sections fallback copy with the approved favorite CTA when the profile is favoritable and not yet favorited.
- [x] ✅ Prefer taxonomy term `name/label` over slug-like `value` when rendering category chips.
- [ ] ⚪ Validate mobile breakpoints, text/chip overflow behavior, and live POI rendering on real profiles.
- [ ] ⚪ Ensure discovery/events/favorites/invite-adjacent entrypoints remain behavior-compatible.
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
- [ ] ⚪ Hero type visual renders only an isolated circular type-avatar, with no chip container and no label.
- [ ] ⚪ Profiles with no about content and no agenda events show `Favorite para ser avisado das novidades sobre {account profile name}.` when the favorite action is still available.
- [x] ✅ Category chips display canonical taxonomy names instead of slug-like values when the payload provides them.
- [ ] ⚪ Entry points from discovery/events/invites remain coherent and regression-free.
- [x] ✅ Loading/empty/error/content states are visually distinguishable and test-backed where practical.
- [ ] ⚪ No navigation, scope, or unintended backend-contract regressions are introduced.
- [x] ✅ Route-choice UI is shared, theme-driven, and dynamic across native/mobile-web constraints.

## Definition of Done

- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [x] ✅ Targeted detail-screen widget coverage is green.
- [x] ✅ `fvm dart analyze --format machine` is clean.
- [x] ✅ Laravel feature coverage is green for `agenda_occurrences` payload materialization.
- [x] ✅ Refreshed Flutter/widget/shared-shell coverage is green for the new collapsed header, live-card navigation, agenda hierarchy, fixed tab order, social-meta removal, and swipe navigation behavior.
- [ ] ⚪ Manual smoke confirms state handling, footer/CTA clarity, and cross-entrypoint continuity.
- [x] ✅ Account-profile agenda contract change is reflected in canonical module docs and validated end-to-end through focused automated gates.

## Validation Steps

- [x] ✅ Automated: targeted widget tests for hierarchy/state/CTA regressions.
- [x] ✅ Automated: Laravel feature test for `GET /account_profiles/{slug}` returning ordered `agenda_occurrences`, including repeated `event_id` with distinct occurrences.
- [x] ✅ Automated: Flutter parser/controller tests for `agenda_occurrences` materialization without `getAllEvents()` fallback.
- [x] ✅ Automated: `fvm dart analyze --format machine`.
- [x] ✅ Automated: widget/shared-shell tests for collapsed AppBar alignment, live-card navigation, agenda hierarchy, fixed tab order, social-meta removal, and swipe navigation.
- [ ] ⚪ Automated: widget coverage for the isolated hero type-avatar plus parser/widget coverage for taxonomy chip name/label precedence.
- [ ] ⚪ Automated: widget coverage for the no-sections favorite CTA across favoritable, already-favorited, and non-favoritable states.
- [ ] ⚪ Manual smoke: detail screen loading/empty/error/content states.
- [ ] ⚪ Manual smoke: event/favorite/discovery entrypoints into detail.
- [ ] ⚪ Manual smoke: mobile breakpoint validation for long title + taxonomy chips.
- [ ] ⚪ Manual smoke: header/taxonomy placement across supported profile types.
- [ ] ⚪ Manual smoke: native/mobile-web `Traçar rota` chooser behavior, including tile in-app map continuity and external app/web fallback behavior.

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
| `D-23` | `Exception` | Latest manual feedback supersedes the prior `chip + label` implementation: the hero type affordance must become an isolated circular avatar only. TODO baseline updated; implementation still pending renewed approval. |
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
- **Confidence:** `medium-high`
- **Readiness outcome:** `Automation green; manual smoke still required before closure`
