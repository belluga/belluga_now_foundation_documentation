# TODO (V1): Immersive Event Detail Dynamic Profile Category Tabs

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Blocker-Resolved`, `Dynamic-Tabs-Implemented`, `Followup-Implemented`, `Automated-Validated`, `Runtime-Followup-Implemented`, `Contract-Hardened`, `Manual-Smoke-Pending`
**Next exact step:** Implement the empty-content follow-up so blank/effectively empty `event.content` omits `Sobre` entirely, then run manual smoke on `/agenda/evento/:slug` covering the strict linked-profile slug contract, content-present vs content-blank cases, card navigation + favorite action, the confirmed/unconfirmed footer gate, and returning from dynamic tabs to `Sobre`.
**Owners:** Flutter Team, Laravel Team
**Objective:** Replace the static `Line-up` tab in tenant-public immersive event detail with dynamic tabs derived from linked account-profile categories, using plural type display labels and rendering category-specific account-profile cards that include taxonomy chips and preserve route continuity to `/parceiro/:slug`, while also aligning the event hero/content tabs to the approved immersive contract: `Sobre` rendered from HTML event content, `Como Chegar` reusing the account-profile location/directions pattern with event-owned location data, and live/hero summary presentation sharing the same event-first visual language.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `big`
**Checkpoint Policy:** section-by-section review checkpoints before implementation + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`

**Blocker Notes:** Resolved by `TODO-v1-account-profile-type-display-label-metadata.md`; runtime profile-type metadata now exposes additive plural labels suitable for dynamic tab titles.
**Last confirmed truth:** `2026-04-04` immersive event detail no longer hardcodes `Line-up`; dynamic tabs now derive from `linked_account_profiles` grouped by `profile_type`, use plural type labels from the bootstrap registry, keep a dedicated `Como Chegar` tab, and render taxonomy-aware account-profile cards with direct `/parceiro/:slug` navigation plus a top-right favorite affordance. The runtime contract is now strict: linked account-profile cards are route-driven by `slug`, the Flutter DTO throws when `linked_account_profiles[].slug` is absent after alias resolution, and no request-time enrichment fallback remains in the read path. Creation and update paths are covered to ensure new event payloads persist `slug` correctly in `venue`, `artists`, and `event_parties` metadata. `Sobre` now renders `event.content` as HTML, `Como Chegar` reuses the account-profile map/directions language with event-owned location inputs, the hero summary follows the same event-first hierarchy as the live-event card, and tenant-admin event forms now edit `content` through the shared rich-text editor. The immersive shell now gates contextual tab footers at screen level, so `Traçar rota` only replaces the default event CTA after confirmation, and returning to tab index `0` resets both inner and outer scroll stacks back to the true top. New follow-up intake on `2026-04-08`: when `event.content` is blank or effectively empty, the runtime must omit `Sobre` instead of showing fallback prose like `Sem descrição disponível.`.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda/evento/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/events_module.md`
- **Secondary:** `../foundation_documentation/modules/flutter_client_experience_module.md`, `../foundation_documentation/modules/partner_catalog_and_offer_module.md`

### Canonical Coverage Status

- `events_module.md`: authoritative for immersive event-detail event/occurrence semantics and public event payload shape.
- `flutter_client_experience_module.md`: authoritative for immersive shell behavior, shared widget semantics, and route/theme continuity.
- `partner_catalog_and_offer_module.md`: authoritative for account-profile identity/card semantics reused by event-detail category cards.

### Decision Consolidation Targets

- Promote durable event-detail tab/card contract changes to `../foundation_documentation/modules/events_module.md`.
- Promote stable shared-shell/widget reuse decisions to `../foundation_documentation/modules/flutter_client_experience_module.md`.
- Promote only durable account-profile-card reuse rules to `../foundation_documentation/modules/partner_catalog_and_offer_module.md` if this lane materially changes cross-surface semantics.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-account-profile-type-display-label-metadata.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-screen-account-profile-detail-polish.md`
- `../foundation_documentation/modules/events_module.md`
- `lib/presentation/tenant_public/schedule/routes/immersive_event_detail_route.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/lineup_section.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/domain/schedule/event_model.dart`

---

## Scope

- Replace the static `Line-up` tab with one or more dynamic tabs derived from linked account-profile categories present in the event payload/runtime model.
- Use the plural display label from profile-type metadata for each dynamic tab title.
- Render category-specific account-profile cards inside those tabs, including taxonomy chips and the approved shared account-profile identity language.
- Keep account-profile card navigation pointing directly to `/parceiro/:slug`.
- Replace `O Rolê` with `Sobre` and render the event description from `event.content` as HTML using the same rendering pattern already accepted on account-profile/public rich-text surfaces.
- Suppress `Sobre` entirely when `event.content` is blank or effectively empty, instead of showing fallback/default text.
- Replace `O Local` with `Como Chegar`, reusing the same location/map/directions language established on account-profile detail while adapting the source data to the event-owned location/venue contract.
- Align the hero summary block with the approved event-card/live-event format:
  - remove the chip container around the schedule line,
  - reuse the same event-first hierarchy already approved for `Acontecendo Agora`,
  - keep venue/location presentation consistent with the upcoming-event pattern.
- Update tenant-admin event form surfaces to reuse the existing rich-text editor pattern for event description/content instead of plain-text editing.
- Preserve `Ganhe Brindes` semantics and the immersive shell anchor/snap behavior while the event hero/content tabs are updated.
- Keep the immersive shell anchor-tab model and the already-approved lateral swipe behavior.
- Reuse the shared account-profile identity/card primitives where possible instead of creating an event-only parallel card language.
- Add a top-right favorite affordance to linked account-profile cards inside event detail without replacing direct navigation to `/parceiro/:slug`.
- Ensure tab navigation back to `Sobre` resets the immersive scroll stack to the true top of the screen, not only the inner content offset.

## Out of Scope

- Event list/search screen polish (`TODO-v1-screen-events-polish.md`).
- Invite/mission/location behavioral redesign.
- Full multilanguage or user-language switching.
- Reworking event payload composition beyond what is strictly needed to group linked account profiles by category.
- Taxonomy label-materialization work unrelated to the touched event-detail cards.
- New backend APIs unless the current event payload proves insufficient for the approved `Como Chegar` reuse.

---

## Decision Baseline (Frozen)

- `D-01`: The static `Line-up` tab is removed from MVP immersive event detail and replaced by dynamic account-profile-category tabs.
- `D-02`: Each dynamic tab title uses the plural profile-type display label supplied by the blocker lane.
- `D-03`: Dynamic tabs are derived from linked account profiles grouped by category/type; no hardcoded artist-only branching remains.
- `D-04`: The account-profile cards rendered inside dynamic category tabs must include taxonomy chips and preserve direct navigation to the account-profile detail route.
- `D-05`: Dynamic profile-category tabs replace only the old lineup lane between the stable event-detail responsibilities `Sobre` and `Como Chegar`.
- `D-06`: `O Rolê` is superseded by `Sobre`, backed by HTML `event.content`, and the event admin surface must use the same rich-text editing pattern already used in other tenant-admin forms.
- `D-07`: Immersive event detail remains a continuous-scroll/anchor-tab surface, not panel-swapping tabs.
- `D-08`: Shared account-profile card/identity primitives should be reused where possible so event-detail cards do not fork from discovery/account-profile semantics.
- `D-09`: `O Local` is superseded by `Como Chegar` and should reuse the account-profile location section language (`Ver no mapa`, directions chooser, map/location emphasis) with event-owned location data as the source.
- `D-10`: The hero summary and the live-event highlight must share the same event-first visual hierarchy; schedule text is not wrapped in a chip/container.
- `D-11`: The linked account-profile tap path must remain contract-driven (`slug` in runtime model) rather than patched with UI-only tap workarounds. Missing `slug` is a payload-contract failure, not a valid “non-clickable” state.
- `D-12`: The `Como Chegar` footer CTA is not a replacement for event confirmation; it is shown only when the event is already confirmed and should use the same immersive CTA token system as the rest of the screen.
- `D-13`: Linked account-profile event cards must expose both direct profile navigation and a favorite affordance; interaction hardening must not rely on fallback lookups when `slug` is absent from runtime data.
- `D-14`: Returning to tab index `0` (`Sobre`) must reset both inner and outer immersive scroll positions so the first section is fully visible from the top.

---

## Plan Review Gate (Big)

### Section P-01 — Category-tab contract
- Severity: `high`
- Evidence: current event detail only has one artist-only `LineupSection`.
- Why now: the approved direction is to make counterpart/profile categories first-class, not special-case artists.
- Recommended direction: replace `Line-up` with dynamic category tabs keyed by linked account-profile type/category.

### Section P-02 — Label blocker dependency
- Severity: `high`
- Evidence: current runtime types expose only one generic `label`.
- Why now: tab titles need plural category labels.
- Recommended direction: block on `foundation_documentation/todos/completed/TODO-v1-account-profile-type-display-label-metadata.md` instead of improvising plurals in Flutter.

### Section P-03 — Venue duplication guardrail
- Severity: `medium`
- Evidence: immersive event detail already has a dedicated `O Local` tab.
- Why now: dynamic category tabs could otherwise duplicate venue semantics unnecessarily.
- Recommended direction: keep `O Local` as the dedicated place/location tab and let dynamic category tabs cover linked account-profile categories that add profile-card context beyond the dedicated location surface.

### Section P-04 — Shared-card reuse
- Severity: `medium`
- Evidence: account-profile discovery/detail already has approved identity/card patterns, including taxonomy chips.
- Why now: creating a parallel event-only card would reintroduce drift.
- Recommended direction: reuse the shared account-profile identity/card family and adapt only size/context slots needed for immersive event detail.

### Section P-05 — Event content and location tabs still reflect the old copy/presentation contract
- Severity: `high`
- Evidence: event detail still uses `O Rolê`, plain text content rendering, and a placeholder/manual location section instead of the approved immersive contract.
- Why now: the dynamic-category lane is already the active execution front for event detail; leaving the old content/location tabs would keep the screen internally inconsistent after the other immersive updates.
- Recommended direction: finish the event-detail contract in the same lane by moving to `Sobre` + HTML rendering, `Como Chegar` reuse, and hero/live summary parity.

## Failure Modes & Edge Cases

- Events with no linked account-profile categories beyond the core event info/location path must not render empty placeholder tabs.
- Events with multiple linked categories must keep deterministic tab ordering.
- Cards with missing avatar/cover must still obey the shared visual-resolution rules.
- If category labels are missing because the blocker is incomplete, this lane is invalid rather than allowed to improvise slugs/plurals.
- HTML event content must render safely and degrade gracefully when blank.
- Blank/effectively empty `event.content` must not leave behind an empty `Sobre` tab or fallback copy.
- Event location sections must not regress navigation to `/parceiro/:slug`, map opening, or directions chooser semantics when the event has venue/location data.
- Hero/content visual alignment must not duplicate venue or counterpart information in conflicting slots.

## Uncertainty Register

- Assumption: current event payload/runtime model has enough linked account-profile information to group at least artists now, and can be extended if another linked category is needed.
- Assumption: `O Local` should remain the dedicated venue/location surface rather than being replaced by a dynamic venue tab.
- Confidence: `medium`

---

## Touched Surfaces

- `foundation_documentation/todos/active/mvp_slices/TODO-v1-immersive-event-detail-dynamic-profile-category-tabs.md`
- `foundation_documentation/todos/completed/TODO-v1-account-profile-type-display-label-metadata.md`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/lineup_section.dart` (or replacement)
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_info_section.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/location_section.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/immersive_hero.dart`
- `lib/presentation/tenant_admin/events/screens/tenant_admin_event_form_screen.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/**`
- `lib/domain/schedule/**`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/**` if event payloads need additive grouping inputs
- `test/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `test/presentation/common/widgets/immersive_detail_screen/**`

## Ordered Steps

1. Add fail-first Flutter tests for the pending event-detail follow-up: `Sobre` label + HTML rendering, `Como Chegar` section behavior, hero/live summary parity, and rich linked-profile navigation continuity.
2. Replace the old content tab copy/renderer (`O Rolê` + plain text) with `Sobre` + HTML rendering, but omit `Sobre` entirely when the content is blank/effectively empty.
3. Replace the old location tab copy/section (`O Local`) with `Como Chegar` reusing the account-profile pattern against event location data.
4. Align the hero/live summary format with the approved event-first hierarchy and remove the schedule chip/container.
5. Reuse/update the tenant-admin rich-text editor for event description/content.
6. Rerun focused Flutter suites and `fvm dart analyze --format machine`.
7. Promote stable event-detail/tab-contract decisions into canonical module docs.

## Test Strategy

- `test-first`

## Fail-First Targets

- Immersive event detail still hardcodes `Line-up`.
- Dynamic tabs cannot render plural category labels from runtime type metadata.
- Category tabs fail to render account-profile cards with taxonomy chips.
- Category cards fail to navigate to `/parceiro/:slug`.
- Events with no qualifying category still render an empty dynamic tab.
- The immersive shell swipe/anchor behavior regresses after inserting dynamic tabs.
- Event detail still renders `O Rolê` instead of `Sobre`.
- Event detail still strips HTML instead of rendering `event.content`.
- Blank/effectively empty `event.content` still renders `Sobre` with fallback prose instead of omitting the tab.
- Event detail still renders `O Local` / placeholder location content instead of the approved `Como Chegar` pattern.
- Event hero/live summary still wraps schedule text in a chip/container or diverges from the approved event-first layout.

## Definition of Done

- Static `Line-up` is gone from immersive event detail.
- Dynamic category tabs render from linked account-profile categories using plural display labels.
- Category tabs render account-profile cards with taxonomy chips and direct profile navigation.
- `Sobre` renders event HTML content coherently.
- Events with blank/effectively empty content omit `Sobre` entirely and never render fallback prose.
- `Como Chegar` reuses the account-profile location/directions pattern coherently against event data.
- Hero/live summary use the approved shared event-first hierarchy without the schedule chip/container.
- Invite flow, mission flow, and immersive swipe/anchor behavior remain coherent.
- Focused Laravel/Flutter tests and `fvm dart analyze --format machine` pass.

## Validation Steps

- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh <immersive-event-detail focused tests>`
- `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/...`
- `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/...`
- `fvm dart analyze --format machine`
- Manual smoke: verify content-present events still show `Sobre`, while content-blank events omit the tab entirely and never show `Sem descrição disponível.`
