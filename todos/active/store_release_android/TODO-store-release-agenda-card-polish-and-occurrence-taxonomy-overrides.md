# TODO (Store Release): Agenda Card Polish And Occurrence Taxonomy Overrides

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual QA on 2026-05-01 found small but visible tenant-public agenda/card polish gaps and two occurrence-contract gaps that affect Store Release correctness.

The polish gaps are bounded to public Flutter presentation: linked Account Profile chips need the same `e mais X` compression already used by the event detail hero, Home Agenda status/radius actions need mutually exclusive extended state, event cards must avoid right-side overflow under the conditional action icon, and every visible schedule/time range with an explicit end time must render as `15:00 às 18:00` instead of `15:00 - 18:00`.

The contract gaps are not cosmetic. Programming items need an optional end time. Event Occurrences also need taxonomy override semantics: occurrence taxonomy selection is restricted to taxonomies linked to the parent event category; when an occurrence has its own taxonomy terms, filtering and display use only the occurrence taxonomy terms, not an event+occurrence merge. The event category remains event-owned.

During approved execution, the user added one bounded visual/catalog requirement: replace the current `boora_icons` font asset with the new uploaded Boora icon font under `flutter-app/assets/fonts/nova_fonte`, reorganize the source font artifacts as needed, and ensure the tenant-admin icon selection list exposes every icon from the new font.

During the same approved execution, the user added one bounded web-layout requirement: remove the tenant-public web max-width frame only for the Map screen route family so `/mapa` and the POI map route can use all available browser width.

During final TODO review, the user explicitly called out a necessary UI dependency for occurrence taxonomy: taxonomy-by-occurrence only works if the tenant-admin occurrence editor exposes a taxonomy field inside each occurrence editor flow. This is part of this TODO, not a deferred follow-up.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-agenda-card-occurrence-taxonomy-polish`
- **Why this is the right current slice:** all requested behavior is in the agenda/event occurrence surface: cards, schedule chrome, programming item presentation, and occurrence-first filtering.
- **Direct-to-TODO rationale:** safe with approval. The user supplied concrete expected behavior, screenshots, and explicit release priority. This TODO exists to freeze decisions, derive a test matrix, and coordinate the cross-stack implementation without reopening unrelated social, invite, or deep-link behavior.

## Delivery Status Canon
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Store-Release`, `Cross-Stack`, `Flutter`, `Laravel`, `Tenant-Public`, `Tenant-Admin`, `Occurrence-First`, `Visual-Polish`, `Icon-Font-Catalog`, `Map-Web-Full-Width`, `Taxonomy-Filter-Contract`, `User-Flow-Impact`
- **Next exact step:** consolidate this T5 branch/package with the active Store Release branches; do not claim the external contact materialization blocker as solved by this TODO.

## Contract Boundary
- This TODO owns:
  - Account Profile chip compression audit and implementation for approved event-card surfaces.
  - Home Agenda action chrome where invite-status and radius controls cannot both be visually extended.
  - Event card layout correction for the conditional top-right icon overflow.
  - Event and programming time-range formatting using `às` when an explicit end time exists.
  - Optional programming item end time from backend write/read contracts through Flutter domain/DTO/UI.
  - Occurrence taxonomy override write/read/filter semantics, tenant-admin occurrence editor UI authoring, and validation against the parent event category.
  - Replacement of the current `BooraIcons` font asset with the new uploaded Boora font and source artifact organization.
  - Tenant-admin map marker/icon picker catalog coverage for every icon in the new Boora font.
  - Tenant-public web desktop-frame exception for the Map screen route family only.
- This TODO does not own:
  - Discovery public/private profile visibility rules already covered by the public discovery guardrail fix.
  - Invite acceptance, share-code, favorite refresh, or contact materialization behavior, except where final consolidated validation consumes their current state.
  - A new category/taxonomy product model beyond occurrence overrides constrained by existing event category taxonomies.
  - Broad visual redesign of Home, Agenda, Discovery, or Account Profile detail screens.
  - Replacing unrelated Material icons outside the Boora icon font/catalog surfaces.
  - Removing the tenant-public web max-width frame from Home, Discovery, Profile, Events, Invite, Promotion, or other non-map routes.

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-home-favorites-refresh-regression.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-invites-occurrence-target-migration.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`
- `foundation_documentation/artifacts/execution-plans/store-release-four-todos-orchestration-plan.md`
- `foundation_documentation/artifacts/execution-plans/store-release-agenda-card-polish-occurrence-taxonomy-orchestration-plan.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
- **Decision promotion targets:**
  - `events_module.md`: programming item optional end time; occurrence taxonomy override semantics; occurrence-first taxonomy filtering.
  - `flutter_client_experience_module.md`: card/chip compression, time-range display, and agenda action chrome consumer contract.
  - `agenda_and_action_planner_module.md`: Home Agenda chrome mutual-expansion behavior and status filter labels.
- **Module decision consolidation targets:**
  - `events_module.md` sections covering occurrences, programming items, taxonomy filters, write payloads, and public APIs.
  - `flutter_client_experience_module.md` event cards, event detail, and programming presentation contracts.
  - `agenda_and_action_planner_module.md` Home Agenda chrome and `/api/v1/agenda` display contracts.

## Scope
- [x] Perform an exhaustive source inventory of event/card/account-profile/programming surfaces that render multiple linked Account Profiles without compression.
- [x] Implement `e mais X` compression for explicitly approved card surfaces: Home Agenda event card and Account Profile agenda event card.
- [x] Return the inventory of additional candidate surfaces for user approval before applying optional compression outside the explicit card surfaces.
- [x] Update Home Agenda invite-status/radius action chrome so only one action is extended at a time.
- [x] Make the invite-status action default compact and render extended labels `Convites` and `Confirmados` for the corresponding filter states.
- [x] Correct card layout overflow by splitting date/title/action header from the rest of the body and reserving a stable trailing action slot.
- [x] Render explicit event/programming time ranges with `às` on all visible public surfaces that have a start and end time.
- [x] Add optional programming item end time to Laravel write validation, persistence/projection, Flutter DTO/domain, admin authoring, and public rendering.
- [x] Add occurrence-level taxonomy override selection in the tenant-admin occurrence editor UI, constrained to terms allowed by the parent event category.
- [x] Ensure occurrence taxonomy filters query effective occurrence taxonomy: occurrence terms replace event terms when present; empty occurrence terms fall back to event terms.
- [x] Replace the current `BooraIcons` font asset with the uploaded new Boora font and move/remove the uploaded generated class from the asset folder into the canonical Flutter source location.
- [x] Ensure the tenant-admin icon picker lists every icon in the new Boora font while preserving legacy storage-key aliases for already persisted icon values.
- [x] Remove the tenant-public web max-width frame only for the Map screen route family while keeping the frame on the rest of the approved tenant-public routes.
- [x] Update canonical docs and tests before any delivery claim.

## Out of Scope
- [ ] Changing public discovery inclusion/exclusion rules for personal or non-public Account Profiles.
- [ ] Reopening invite lifecycle, share-code, favorite refresh, or contact matching implementation unless a direct regression is exposed by final validation.
- [ ] Supporting programming item overnight/cross-day ranges in this slice. The launch contract treats item `end_time` as same-day and later than `time`.
- [ ] Adding arbitrary occurrence taxonomy terms outside the parent event category's allowed taxonomy set.
- [ ] Replacing the client-composed Home model with a backend Home aggregate endpoint.
- [ ] Hardcoding profile types such as `venue` in new guardrail tests.
- [ ] Redesigning all app iconography or changing persisted icon storage keys beyond backwards-compatible aliases for the new catalog.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Linked Account Profile compression uses the same user-facing pattern as the event detail hero: visible first item plus `e mais X`.
- [x] `D-02` Explicit implementation scope for compression starts with the shared upcoming event card used by Home Agenda and Account Profile agenda, plus the Account Profile detail live/current agenda line.
- [x] `D-03` The Home Agenda status filter cycle becomes `todos` compact -> `Convites` extended -> `Confirmados` extended -> `todos` compact, unless implementation evidence proves the current state machine requires a smaller compatible adjustment.
- [x] `D-04` `Convites` means received/pending invitation filter only; `Confirmados` means occurrence attendance confirmed by the user, regardless of whether the confirmation originated from an invite, direct attendance confirmation, or another valid confirmation path.
- [x] `D-05` Radius and invite-status actions use a shared chrome controller/wrapper so at most one action is extended at a time. Radius can stay compact while status is extended and must collapse when status expands.
- [x] `D-06` The user's wording "os dois não podem ficar comprimidos ao mesmo tempo" is interpreted as "os dois não podem ficar expandidos ao mesmo tempo" because the requested wrapper controls extended action format.
- [x] `D-07` Event card overflow is solved structurally: header row contains date/title plus a stable conditional action slot; body content renders below without flowing under the icon.
- [x] `D-08` Event and programming item time ranges render `start às end` whenever an explicit end time exists, across Home Agenda cards, Account Profile agenda cards, event detail hero/programming, My Events/Live cards, and Map event POI schedule labels.
- [x] `D-09` Programming item transport uses canonical optional `end_time` alongside existing `time`.
- [x] `D-10` Programming item `end_time`, when present, must be a valid `HH:mm` same-day time later than `time`.
- [x] `D-11` Occurrence taxonomy terms are replacement overrides, not merged additions. If an occurrence has taxonomy terms, filtering and display use only those terms. If it has none, effective taxonomy falls back to the parent event taxonomy terms.
- [x] `D-12` Event category remains event-owned. Occurrence overrides can select only taxonomy terms allowed by that event category.
- [x] `D-13` Taxonomy filter tests must use dynamic/synthetic categories and taxonomy terms, not hardcoded profile types such as `venue`.
- [x] `D-14` Final closure of this TODO must be consolidated with the current Store Release orchestration plans instead of promoted as a disconnected branch.
- [x] `D-15` User approved compression for Map event POI detail card and event programming item overflow label. The linked profile category tabs, Discovery Live Now rail, and map marker core remain non-compressed.
- [x] `D-16` User approved replacing the current `BooraIcons` font asset with the newly uploaded Boora font from `flutter-app/assets/fonts/nova_fonte` during this same orchestration round.
- [x] `D-17` The icon picker must expose all 55 icons from the new font. Existing persisted/default storage keys remain resolvable through aliases so current tenant settings do not lose marker visuals.
- [x] `D-18` The web desktop max-width frame is removed only for the Map screen route family (`/mapa` and the POI map route, both rendering `MapScreen`), while non-map tenant-public routes stay constrained.
- [x] `D-19` Occurrence taxonomy overrides require an explicit tenant-admin occurrence editor field; controller/DTO support without the occurrence UI field is not complete.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The compressed label must be exactly `e mais X`, including on card surfaces that currently use other compact labels. | User request and existing event detail hero behavior. | Optional surfaces may need product review before wording is unified. | `High` | Freeze by `D-01` after approval. |
| `A-02` | Current Home Agenda filter semantics should split pending received invites from confirmed occurrences rather than keep the old combined `invitesAndConfirmed` behavior. | User named separate labels: `Convites` and `Confirmados`, and clarified that `Confirmados` means attendance confirmation regardless of invite origin. | Keep backend/repository filter semantics unchanged and only relabel the current combined state. | `High` | Freeze by `D-03/D-04` unless user corrects. |
| `A-03` | Programming item end time is same-day and can be represented as `HH:mm` without date or timezone. | Current programming item has only `time`; occurrence supplies date context. | A broader schedule model is needed before implementation. | `Medium` | Keep as assumption and out-of-scope overnight ranges. |
| `A-04` | The event category/type already has a service/model that defines which taxonomy terms are allowed for selection. | Existing taxonomy-filter and event-category contracts in `events_module.md`. | Backend must add the minimal validator inside this TODO. | `Medium` | Verify during backend preflight. |
| `A-05` | Occurrence taxonomy overrides can be implemented without changing the public filter query shape; the backend maps existing taxonomy slug-pair filters to effective occurrence taxonomy. | Existing public filter contract is slug-pair based. | Flutter filter requests may need additive DTO changes. | `Medium` | Verify in fail-first query tests. |
| `A-06` | The generated `Boora.dart` in the uploaded font folder is a source template, not the canonical app source location. | Current app owns `lib/application/icons/boora_icons.dart` and `pubspec.yaml` already registers `assets/fonts/BooraIcons.ttf`. | A separate generated-source workflow would need to be introduced. | `High` | Move the declarations into the existing app icon class and keep the font family stable. |

## Approved Source Inventory Snapshot
This inventory was returned to the user before implementation and approved on 2026-05-01.

| Surface | Current Finding | Proposed Handling |
| --- | --- | --- |
| Shared upcoming event card | Renders all `counterparts`; used by Home Agenda event cards and Account Profile agenda event cards. | **Recommended now.** Apply `e mais X` compression here and fix the overflow layout. |
| Account Profile detail live/current agenda line | Renders counterpart badges separately from the shared card inside `AccountProfileDetailScreen`. | **Recommended now.** It is an Account Profile agenda/event-card surface and should match the shared card behavior. |
| Map event POI detail card | Renders all `poi.linkedProfiles` inside `EventPoiDetailCard`. | **Recommended for approval.** It is a public event card surface, but it is outside the two explicit examples, so user approval is required before changing it. |
| Event programming item card | Already compacts linked profiles after 4, but label is `+X perfis`, not `e mais X`. | **Recommended for approval.** Keep existing compression count unless user wants a different threshold; align overflow label to `e mais X` for consistency. |
| Event detail hero | Already compresses linked profiles as `e mais X`. | No compression change; only update time-range label to `às`. |
| Linked profile category tab cards | Renders one card per Account Profile in the event detail dynamic profile tabs. | **Do not compress.** This is a listing surface, not a chip stack/card header. |
| Discovery Live Now counterpart rail | Renders one card per live counterpart/profile. | **Do not compress.** The collection itself is the content; compression would hide valid cards. |
| Map marker core | Uses only the primary counterpart for marker visuals. | No compression change. |
| Home My Events carousel card | Does not currently render counterpart chips, but uses time-range formatting. | No compression change; update explicit time ranges to `às`. |
| Event Live Now card | Does not render counterpart chips, but uses time-range formatting. | No compression change; update time range formatting and prefer explicit end time where available. |

## Execution Plan
**Orchestration plan:** `foundation_documentation/artifacts/execution-plans/store-release-agenda-card-polish-occurrence-taxonomy-orchestration-plan.md`

### Touched Surfaces
- Flutter tenant-public Home Agenda card/header widgets and controllers.
- Flutter Account Profile detail agenda widgets.
- Flutter event detail programming widgets, domain models, and DTOs.
- Flutter tenant-admin event occurrence/programming/taxonomy authoring surfaces.
- Flutter icon font asset, `BooraIcons` source declarations, shared map marker icon catalog, and tenant-admin icon picker.
- Flutter tenant-public web desktop frame route allowlist for Map full-width behavior.
- Laravel Events write requests, occurrence persistence/snapshot services, query services, migrations, and API projections.
- Canonical foundation docs for Events, Agenda, Flutter Client Experience, and Account Profile Catalog.
- Focused Flutter, Laravel, Playwright/browser, and optional ADB/runtime tests as required by the matrix.

### Ordered Steps
1. Re-run code inventory for card/chip/time surfaces and record the compression candidate list for user approval before optional surfaces are changed.
2. Add fail-first Flutter widget/controller tests for explicit card compression, overflow layout, agenda action chrome, and time labels.
3. Implement front-only polish for the explicitly approved surfaces.
4. Add fail-first Laravel tests for programming item `end_time` and occurrence taxonomy override validation/filtering.
5. Implement Laravel schema/write/read/query changes and update foundation module docs.
6. Add Flutter DTO/domain/admin/public tests for programming `end_time` and occurrence taxonomy overrides.
7. Implement Flutter admin/public consumers after backend payload shape is stable.
8. Replace the Boora icon font asset, update the canonical icon catalog, and add picker/catalog tests that prove all new font icons are selectable.
9. Remove the web desktop frame from the Map screen route family only and test that map routes stay full-width while other framed routes remain constrained.
10. Run focused suites, official analyzer, Laravel safe runner, web build/Playwright where visible web behavior is affected, and final consolidated guards.
11. Run triple review/audit lanes required by this cross-stack TODO and reconcile findings before delivery claim.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Card with many linked Account Profiles overflows under the conditional action icon and lacks `e mais X`.
  - Home Agenda can display radius and invite-status actions extended at the same time.
  - Existing time range labels show `-` instead of `às`.
  - Programming item payload with `end_time` is ignored or not rendered.
  - Occurrence taxonomy filter incorrectly merges event and occurrence terms or returns the wrong occurrence.
  - Tenant-admin icon picker does not list every icon from the new Boora font.

## Test Coverage Matrix
| Task / Behavior | Fail-First Target | Required Automated Evidence | Runtime / Manual Evidence | Status |
| --- | --- | --- | --- | --- |
| Home Agenda event card compression | More than threshold linked profiles render every chip. | Flutter widget test for shared upcoming event card showing first chip + `e mais X`. | Widget evidence sufficient; no runtime screenshot required. | `passed` |
| Account Profile agenda card compression | Account Profile agenda event cards render every linked profile chip. | Flutter Account Profile detail widget test using the shared card and custom live line. | Widget evidence sufficient. | `passed` |
| Compression inventory | Additional surfaces are changed without user approval. | Source inventory artifact/checkpoint listing all candidate surfaces. | User approved Map POI card and programming overflow label. | `passed` |
| Event card overflow | Conditional action icon overlaps chips/body content on narrow width. | Flutter constrained-size/widget tests and focused suite. | Widget evidence sufficient. | `passed` |
| Home Agenda action chrome | Radius and status can both be extended or status lacks labels. | Flutter widget/controller tests for compact default, `Convites`, `Confirmados`, mutual collapse, and cycle behavior. | Widget/controller evidence sufficient. | `passed` |
| Event time range formatting | Explicit end time renders `15:00 - 18:00`. | Flutter tests for upcoming card/Home Agenda, Account Profile agenda, immersive hero, My Events carousel, Live Now card, and Map event POI labels. | Widget evidence sufficient. | `passed` |
| Programming item optional end time | Backend ignores `programming_items[].end_time` or accepts invalid ranges. | Laravel write/read validation tests plus Flutter DTO/domain/widget tests. | Admin widget authoring path covered; no browser route needed. | `passed` |
| Occurrence taxonomy override validation | Occurrence accepts taxonomy terms outside parent event category. | Laravel feature/unit tests with synthetic category/taxonomy fixtures. | n/a backend contract. | `passed` |
| Occurrence taxonomy authoring UI | Occurrence taxonomy exists only in DTO/controller code and cannot be selected from the occurrence editor. | Flutter tenant-admin occurrence editor widget test `authors occurrence taxonomy overrides from the date editor`. | Widget evidence sufficient. | `passed` |
| Occurrence taxonomy filtering | Filtering by taxonomy returns all occurrences of the event or merges event+occurrence terms. | Laravel query tests: same event, occurrence A/B taxonomy filter returns only matching occurrence. | Backend query evidence sufficient for public contract. | `passed` |
| Flutter occurrence taxonomy consumer | Admin/public clients cannot send/render occurrence override terms from the occurrence editor UI. | Flutter admin DTO/controller/widget tests and public DTO/filter tests. | Widget/controller evidence sufficient. | `passed` |
| Regression against hardcoded type tests | New tests rely on a fixed profile type such as `venue`. | Test-quality audit and test inventory confirm synthetic/dynamic taxonomy fixtures. | n/a | `passed` |
| Boora icon font replacement and picker catalog | Picker still exposes only the old subset or old font codepoints. | Flutter catalog/widget tests proving all 55 new Boora icons are registered/selectable and legacy aliases resolve. | Widget evidence sufficient. | `passed` |
| Map web full-width exception | `/mapa` is still constrained to the tenant-public max-width frame, or the frame is accidentally removed from non-map routes. | Flutter widget tests for `TenantPublicWebDesktopFrame`: Map/Poi routes full-width on wide web; non-map public routes remain constrained. | Playwright runtime evidence in `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`; `/mapa` occupied the full `1200px` browser viewport while Home stayed framed at `430px` on `https://guarappari.belluga.space`. | `passed` |

## Flow Evidence Planning Matrix
| Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| Home Agenda visible cards/header | First screen agenda UX and screenshot-reported overflow. | shared Android/Web Flutter render | widget tests first; browser/ADB screenshot if visual assertion is insufficient | no | no for polish | Focused widget tests plus constrained viewport proof. |
| Account Profile agenda card | Public profile agenda may show many linked profiles. | shared Android/Web Flutter render | widget tests first; runtime only if layout remains ambiguous | no | no | Focused Account Profile widget test. |
| Tenant-admin programming end time | Authoring mutation changes backend contract. | Web admin primary, Flutter shared | Playwright/admin mutation if existing source-owned admin route covers it | yes | yes | Laravel feature tests plus Flutter admin tests; Playwright if route exists. |
| Public programming end time | Visible event detail schedule output. | shared Android/Web Flutter render | widget tests; Playwright after web build if public route data can be seeded | no | yes for end-to-end | Flutter DTO/widget tests and optional browser proof. |
| Occurrence taxonomy filter | Public Home Agenda filtering correctness. | shared Android/Web API-backed behavior | Laravel query tests required; browser/runtime smoke if filter UI path affected | no | yes | Laravel query tests with effective occurrence taxonomy and Flutter filter consumer tests. |
| Tenant-admin icon picker | Admin users select marker/icon visuals from the tenant-admin UI. | shared Flutter render, web-admin primary | widget test first; Playwright only if widget evidence cannot prove list coverage | no | no | Flutter widget/catalog tests for all new Boora icons and alias compatibility. |
| Public Map web width | Browser map usability depends on the map using available horizontal space. | web-only layout wrapper behavior | widget test plus source-owned Playwright readonly runtime | no | no | Flutter web-frame route tests plus `tools/flutter/web_app_tests/map_full_width.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` after fresh web bundle publish. |

## Audit Trigger Matrix
| Lane | Trigger | Minimum Decision |
| --- | --- | --- |
| Architecture | Cross-stack Events/Agenda/Flutter contract and tenant-admin/public consumers. | `required` |
| Code Quality | Shared card/header widgets, DTOs, validators, and query services touched. | `required` |
| Test Quality | User explicitly requires guardrails against regression; occurrence filters need fail-first evidence. | `required` |
| Performance | Occurrence taxonomy filtering must stay query/index friendly; card layout must avoid expensive all-chip rendering. | `required` |
| Security | Taxonomy selection and public filtering are tenant-scoped but not new trust mutations. | `recommended` |
| Concurrency/Idempotency | No new concurrent mutation semantics expected; event update validation still needs normal transactional consistency. | `recommended` |

## Performance & Concurrency Risk Assessment (`pcv-1`)
| Lane | Trigger Result | trigger_reason_code | gate_deadline | min_evidence_rule_id | state | residual_risk | uncertainty_reason_code | Evidence |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `required` | `occurrence_taxonomy_filter_query_path` | `before_delivery` | `pcv-1` | `planned` | `medium` | `index_shape_unknown_until_preflight` | Laravel query tests and query/index review. |
| `FRC` | `required` | `agenda_header_repeated_tap_and_filter_state` | `before_delivery` | `pcv-1` | `planned` | `low` | `local_chrome_state_transition` | Flutter controller/widget rapid toggle tests. |
| `BCI` | `recommended` | `event_update_transactional_write_shape` | `before_delivery` | `pcv-1` | `planned` | `low` | `write_path_existing_transaction_unknown` | Backend event update tests and transaction audit. |
| `RLS` | `not_needed` | `no_runtime_infra_change` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | n/a |

## Acceptance Criteria
- [x] Home Agenda and Account Profile agenda event cards compress multiple linked Account Profiles with `e mais X`.
- [x] Additional chip surfaces are inventoried and only changed after user approval.
- [x] Home Agenda status/radius actions cannot both be visually extended, and status labels render as `Convites` / `Confirmados`.
- [x] Event cards no longer overflow under the conditional icon in constrained width.
- [x] Explicit event and programming item ranges render with `às` on all audited visible surfaces.
- [x] Programming items support optional `end_time` in backend write/read and Flutter admin/public display.
- [x] Occurrence taxonomy overrides are constrained by parent event category taxonomy rules.
- [x] Public taxonomy filters operate on effective occurrence taxonomy and return only matching occurrences.
- [x] The current Boora icon font asset is replaced by the uploaded new Boora font.
- [x] Tenant-admin icon selection lists every icon from the new font and resolves existing/default storage keys through aliases.
- [x] On web, `/mapa` and the POI map route occupy the available viewport width, while non-map tenant-public routes keep the existing max-width frame.
- [x] Tests avoid hardcoded profile-type assumptions and include regression guardrails for the reported failures.

## Definition of Done
- [x] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [x] Focused Flutter tests pass for card compression, overflow, Home Agenda chrome, time labels, programming display, and taxonomy consumers.
- [x] Focused Flutter tests pass for Boora icon catalog coverage, icon picker list coverage, and legacy storage-key aliases.
- [x] Focused Flutter tests pass for Map route web full-width behavior and preservation of the max-width frame on non-map routes.
- [x] Focused Laravel tests pass for programming `end_time`, occurrence taxonomy validation, and effective occurrence taxonomy filtering.
- [x] `fvm dart analyze --format machine` passes or unrelated diagnostics are isolated per the Flutter app analyzer gate.
- [x] Laravel formatter/test runner gates pass for touched backend code.
- [x] Web build/Playwright or ADB evidence is recorded for visible runtime behavior where widget tests are insufficient.
- [x] Canonical module docs are updated for durable contract changes.
- [x] Independent review/triple audit findings are resolved or explicitly adjudicated.
- [x] This TODO is consolidated with the two current Store Release orchestration plans before any delivery-ready claim.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `AC-01` | Acceptance Criteria | Home Agenda and Account Profile agenda cards compress linked Account Profiles with `e mais X`. | Flutter tests | `fvm flutter test ... upcoming_event_card_test.dart ... account_profile_detail_screen_test.dart ... my_events_carousel_card_test.dart` | local Flutter | `passed` | Covered in 237-test focused suite. |
| `AC-02` | Acceptance Criteria | Optional compression surfaces inventoried and changed only after approval. | TODO/plan evidence | Approved inventory snapshot in this TODO and user approval before implementation. | n/a | `passed` | Map POI and programming overflow label were explicitly approved. |
| `AC-03` | Acceptance Criteria | Home Agenda status/radius actions cannot both be extended; labels `Convites`/`Confirmados`. | Flutter tests | `agenda_app_bar_test.dart`, `tenant_home_agenda_controller_test.dart`, `event_search_screen_controller_test.dart` | local Flutter | `passed` | Confirms pending invites and direct attendance confirmations are split. |
| `AC-04` | Acceptance Criteria | Event cards no longer overflow under conditional icon. | Flutter tests | `upcoming_event_card_test.dart`; 237-test focused suite. | local Flutter | `passed` | Stable trailing action slot covered. |
| `AC-05` | Acceptance Criteria | Explicit ranges render with `às`. | Flutter/Laravel tests | Flutter card/detail/programming/map tests plus Laravel programming end-time tests. | local Flutter + Laravel safe runner | `passed` | Includes event and programming ranges. |
| `AC-06` | Acceptance Criteria | Programming items support optional `end_time`. | Flutter/Laravel tests | `tenant_admin_event_form_screen_test.dart`, DTO tests, `EventCrudControllerTest.php`. | local Flutter + Laravel safe runner | `passed` | Includes admin UI authoring, backend validation, persistence, and public payload. |
| `AC-07` | Acceptance Criteria | Occurrence taxonomy overrides constrained by parent event category and selectable in the occurrence editor UI. | Flutter/Laravel tests | `tenant_admin_events_controller_test.dart`, `tenant_admin_event_form_screen_test.dart` (`authors occurrence taxonomy overrides from the date editor`), `EventCrudControllerTest.php`. | local Flutter + Laravel safe runner | `passed` | Synthetic taxonomy/category fixtures, no hardcoded profile-type assumptions; UI field is inside the tenant-admin occurrence editor. |
| `AC-08` | Acceptance Criteria | Public taxonomy filters operate on effective occurrence taxonomy. | Laravel tests | `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_effective_event_taxonomy_terms|agenda_taxonomy_filter_uses_effective_occurrence_taxonomy_overrides|agenda_filters_by_occurrence_ids_without_walking_unrelated_events|...'` | Laravel safe runner | `passed` | Same-event occurrence filter returns only matching occurrence; pending invite filter is bounded by occurrence IDs. |
| `AC-09` | Acceptance Criteria | Boora icon font replaced. | Asset/source diff + Flutter tests | `assets/fonts/BooraIcons.ttf`, `lib/application/icons/boora_icons.dart`, `map_marker_icon_catalog_test.dart`. | local Flutter | `passed` | Uploaded staging folder removed after consolidation into canonical asset/source. |
| `AC-10` | Acceptance Criteria | Tenant-admin icon picker lists every new font icon and aliases legacy keys. | Flutter tests | `tenant_admin_map_marker_icon_picker_field_test.dart`, `map_marker_icon_catalog_test.dart`. | local Flutter | `passed` | Covers all 55 icons. |
| `AC-11` | Acceptance Criteria | Map route family full-width on web; non-map public routes stay framed. | Flutter tests + Playwright runtime | `tenant_public_web_desktop_frame_test.dart`; source-owned Playwright spec `tools/flutter/web_app_tests/map_full_width.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly`; command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_EXPECTED_WEB_BUILD_SHA=b6986302 NAV_EXPECTED_LANDLORD_HOST=belluga.space NAV_WEB_GREP_EXTRA='MAP-WEB-WIDTH-01' NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 bash ../tools/flutter/run_web_navigation_smoke.sh readonly`; build/publish proof `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent) then `docker compose restart nginx`; refreshed real-domain provenance `__WEB_BUILD_SHA__=b6986302`; artifact `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`. | local Flutter + browser-facing Guarappari web route `/mapa` | `passed` | Widget test covers Map/Poi route allowlist; Playwright proves Home `430px` and `/mapa` `1200px` on `https://guarappari.belluga.space` with build provenance `b6986302`. |
| `DOD-01` | Definition of Done | Focused Flutter suites pass. | Flutter test run | 296-test focused suite command in audit resolution artifact. | local Flutter | `passed` | Also ran focused EventSearch/repository/backend contract subsets separately. |
| `DOD-02` | Definition of Done | Focused Laravel suites pass. | Laravel safe runner | 23 tests, 170 assertions. | Laravel container | `passed` | Includes create/update/filter/fanout contracts, occurrence identity reorder preservation, duplicate raw/canonical occurrence identity guards, and pending occurrence-id agenda/stream filtering. |
| `DOD-03` | Definition of Done | Analyzer passes. | Analyzer | `fvm dart analyze --format machine` | local Flutter | `passed` | Exit 0, no diagnostics. |
| `DOD-04` | Definition of Done | Backend formatter/architecture gates pass. | Container commands | `docker compose exec -T app ./vendor/bin/pint --test ...`; `docker compose exec -T app php scripts/architecture_guardrails.php`. | Laravel container | `passed` | Host Composer skipped due missing host PHP; container equivalent used. |
| `DOD-05` | Definition of Done | Runtime evidence where widget tests are insufficient. | Scoped Android run + Playwright runtime | `foundation_documentation/artifacts/tmp/flutter-device-runner/t5-guarappari-adb-results-20260501.md`; `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`; source-owned Playwright spec `tools/flutter/web_app_tests/map_full_width.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly`; both touched integration files passed on `192.168.15.9:5555` with flavor `guarappari` and app id `com.guarappari.app`; `MAP-WEB-WIDTH-01` passed on `https://guarappari.belluga.space`. | Android device + browser-facing Guarappari web route `/mapa` | `passed` | Corrected release runtime lane is `guarappari`; prior `belluga` device evidence is excluded. Web build/publish proof: `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent), `docker compose restart nginx`, and refreshed real-domain `__WEB_BUILD_SHA__=b6986302`. |
| `DOD-06` | Definition of Done | Triple audit. | Audit artifacts | `artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/.../session.json`; round 07 summary. | no-context audit | `passed` | Round 07 clean after round 06 adjudication; zero unresolved findings in elegance, performance, and test-quality lanes. |
| `SCOPE-01` | Scope | Perform an exhaustive source inventory of event/card/account-profile/programming surfaces that render multiple linked Account Profiles without compression. | Source inventory | Approved Source Inventory Snapshot in this TODO. | n/a | `passed` | Inventory returned before implementation and approved by user. |
| `SCOPE-02` | Scope | Implement `e mais X` compression for explicitly approved card surfaces: Home Agenda event card and Account Profile agenda event card. | Flutter tests | Focused Flutter suite, including `upcoming_event_card_test.dart` and `account_profile_detail_screen_test.dart`. | local Flutter + ADB Guarappari runtime | `passed` | Scoped Android agenda runtime also passed. |
| `SCOPE-03` | Scope | Return the inventory of additional candidate surfaces for user approval before applying optional compression outside the explicit card surfaces. | Approval evidence | Approved Source Inventory Snapshot and user approval in this TODO. | n/a | `passed` | Optional Map POI and programming overflow label were approved before implementation. |
| `SCOPE-04` | Scope | Update Home Agenda invite-status/radius action chrome so only one action is extended at a time. | Flutter tests | `agenda_app_bar_test.dart`, `tenant_home_agenda_controller_test.dart`. | local Flutter + ADB Guarappari runtime | `passed` | Device run covered agenda filter/header integration. |
| `SCOPE-05` | Scope | Make the invite-status action default compact and render extended labels `Convites` and `Confirmados` for the corresponding filter states. | Flutter tests | `agenda_app_bar_test.dart`, `event_search_screen_controller_test.dart`. | local Flutter + ADB Guarappari runtime | `passed` | Device run covered agenda invite/confirmed filter flow. |
| `SCOPE-06` | Scope | Correct card layout overflow by splitting date/title/action header from the rest of the body and reserving a stable trailing action slot. | Flutter tests | `upcoming_event_card_test.dart` constrained layout coverage. | local Flutter | `passed` | Screenshot-reported overflow guarded by constrained widget test. |
| `SCOPE-07` | Scope | Render explicit event/programming time ranges with `às` on all visible public surfaces that have a start and end time. | Flutter/Laravel tests | Focused Flutter card/detail/programming/map tests and Laravel programming end-time tests. | local Flutter + Laravel container | `passed` | Includes public event and programming display surfaces. |
| `SCOPE-08` | Scope | Add optional programming item end time to Laravel write validation, persistence/projection, Flutter DTO/domain, admin authoring, and public rendering. | Cross-stack tests | Laravel 23-test suite; Flutter DTO/admin/widget tests. | Laravel container + local Flutter | `passed` | Covers write validation, persistence, projection, DTO/domain, admin authoring, and public rendering. |
| `SCOPE-09` | Scope | Add occurrence-level taxonomy override selection in the tenant-admin occurrence editor UI, constrained to terms allowed by the parent event category. | Flutter/Laravel tests | `tenant_admin_event_form_screen_test.dart`; `EventCrudControllerTest.php`. | local Flutter + Laravel container | `passed` | UI field is inside tenant-admin occurrence editor. |
| `SCOPE-10` | Scope | Ensure occurrence taxonomy filters query effective occurrence taxonomy: occurrence terms replace event terms when present; empty occurrence terms fall back to event terms. | Laravel tests | `agenda_taxonomy_filter_uses_effective_occurrence_taxonomy_overrides` and related Laravel suite. | Laravel container | `passed` | Covers override replacement and fallback semantics. |
| `SCOPE-11` | Scope | Replace the current `BooraIcons` font asset with the uploaded new Boora font and move/remove the uploaded generated class from the asset folder into the canonical Flutter source location. | Asset/source diff + Flutter tests | `assets/fonts/BooraIcons.ttf`, `lib/application/icons/boora_icons.dart`, icon catalog tests. | local Flutter | `passed` | Uploaded staging source consolidated into canonical app source. |
| `SCOPE-12` | Scope | Ensure the tenant-admin icon picker lists every icon in the new Boora font while preserving legacy storage-key aliases for already persisted icon values. | Flutter tests | `tenant_admin_map_marker_icon_picker_field_test.dart`, `map_marker_icon_catalog_test.dart`. | local Flutter | `passed` | Covers all 55 icons and aliases. |
| `SCOPE-13` | Scope | Remove the tenant-public web max-width frame only for the Map screen route family while keeping the frame on the rest of the approved tenant-public routes. | Flutter web route tests + Playwright runtime | `tenant_public_web_desktop_frame_test.dart`; source-owned Playwright spec `tools/flutter/web_app_tests/map_full_width.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly`; build/publish proof `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent) and `docker compose restart nginx`; refreshed real-domain provenance `__WEB_BUILD_SHA__=b6986302`; artifact `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`. | local Flutter + browser-facing Guarappari web route `/mapa` | `passed` | Map/Poi routes full width; non-map routes constrained. Playwright proved `/mapa` full-width and Home framed after fresh web build publish. |
| `SCOPE-14` | Scope | Update canonical docs and tests before any delivery claim. | Docs/tests/guards | Module docs updated; analyzer, Laravel suite, ADB runtime, triple audit, plan/delivery guards passed. | docs + local validation + Android device | `passed` | Guard evidence recorded in plan and audit artifacts. |
| `AC-EXACT-01` | Acceptance Criteria | Home Agenda and Account Profile agenda event cards compress multiple linked Account Profiles with `e mais X`. | Flutter tests | Focused Flutter suite, shared upcoming card and Account Profile detail coverage. | local Flutter + ADB Guarappari runtime | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-02` | Acceptance Criteria | Additional chip surfaces are inventoried and only changed after user approval. | Approval evidence | Approved Source Inventory Snapshot in this TODO. | n/a | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-03` | Acceptance Criteria | Home Agenda status/radius actions cannot both be visually extended, and status labels render as `Convites` / `Confirmados`. | Flutter tests | `agenda_app_bar_test.dart`, controller tests. | local Flutter + ADB Guarappari runtime | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-04` | Acceptance Criteria | Event cards no longer overflow under the conditional icon in constrained width. | Flutter tests | `upcoming_event_card_test.dart`. | local Flutter | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-05` | Acceptance Criteria | Explicit event and programming item ranges render with `às` on all audited visible surfaces. | Flutter/Laravel tests | Focused Flutter visible-surface tests and Laravel `end_time` tests. | local Flutter + Laravel container | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-06` | Acceptance Criteria | Programming items support optional `end_time` in backend write/read and Flutter admin/public display. | Cross-stack tests | Laravel feature tests; Flutter DTO/admin/public widget tests. | Laravel container + local Flutter | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-07` | Acceptance Criteria | Occurrence taxonomy overrides are constrained by parent event category taxonomy rules. | Laravel tests | Synthetic category/taxonomy fixtures in Laravel suite. | Laravel container | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-08` | Acceptance Criteria | Public taxonomy filters operate on effective occurrence taxonomy and return only matching occurrences. | Laravel tests | Effective occurrence taxonomy query tests. | Laravel container | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-09` | Acceptance Criteria | The current Boora icon font asset is replaced by the uploaded new Boora font. | Asset/source diff + Flutter tests | Font asset and `BooraIcons` catalog tests. | local Flutter | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-10` | Acceptance Criteria | Tenant-admin icon selection lists every icon from the new font and resolves existing/default storage keys through aliases. | Flutter tests | Icon picker and catalog tests. | local Flutter | `passed` | Exact acceptance criterion covered. |
| `AC-EXACT-11` | Acceptance Criteria | On web, `/mapa` and the POI map route occupy the available viewport width, while non-map tenant-public routes keep the existing max-width frame. | Flutter web route tests + Playwright runtime | `tenant_public_web_desktop_frame_test.dart`; source-owned Playwright spec `tools/flutter/web_app_tests/map_full_width.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly`; build/publish proof `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent) and `docker compose restart nginx`; refreshed real-domain provenance `__WEB_BUILD_SHA__=b6986302`; artifact `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`. | local Flutter + browser-facing Guarappari web route `/mapa` | `passed` | Exact acceptance criterion covered; Playwright proves `/mapa` `1200px` and Home `430px` on `https://guarappari.belluga.space`. |
| `AC-EXACT-12` | Acceptance Criteria | Tests avoid hardcoded profile-type assumptions and include regression guardrails for the reported failures. | Test/audit evidence | Synthetic taxonomy fixtures; round 07 triple audit clean. | local tests + no-context audit | `passed` | Exact acceptance criterion covered. |
| `DOD-EXACT-01` | Definition of Done | All acceptance criteria have concrete evidence in the Completion Evidence Matrix. | Completion matrix | This matrix includes exact AC rows and evidence artifacts. | n/a | `passed` | Exact DOD covered. |
| `DOD-EXACT-02` | Definition of Done | Focused Flutter tests pass for card compression, overflow, Home Agenda chrome, time labels, programming display, and taxonomy consumers. | Flutter tests + runtime route evidence | 296-test focused Flutter suite; Android integration route evidence `integration_test/feature_agenda_filters_regression_test.dart` and `integration_test/feature_safe_area_and_agenda_appbar_test.dart`; Playwright source-owned spec `tools/flutter/web_app_tests/map_full_width.spec.js` and runner `tools/flutter/run_web_navigation_smoke.sh readonly` after web build/publish `../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent) with refreshed `__WEB_BUILD_SHA__=b6986302`. | local Flutter + Android device + browser-facing Guarappari web route `/mapa` | `passed` | Exact DOD covered; Flutter presentation tests are backed by Guarappari ADB agenda route evidence and web Map Playwright evidence. |
| `DOD-EXACT-03` | Definition of Done | Focused Flutter tests pass for Boora icon catalog coverage, icon picker list coverage, and legacy storage-key aliases. | Flutter route/widget tests | Icon catalog and tenant-admin picker widget route tests for 55 icons and aliases: `tenant_admin_map_marker_icon_picker_field_test.dart`, `map_marker_icon_catalog_test.dart`. | local Flutter tenant-admin picker route/widget harness | `passed` | Exact DOD covered; picker list coverage is route-mounted widget evidence, no browser mutation required. |
| `DOD-EXACT-04` | Definition of Done | Focused Flutter tests pass for Map route web full-width behavior and preservation of the max-width frame on non-map routes. | Flutter tests + Playwright runtime | `tenant_public_web_desktop_frame_test.dart`; source-owned Playwright spec `tools/flutter/web_app_tests/map_full_width.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly`; build/publish proof `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent), `docker compose restart nginx`, refreshed real-domain `__WEB_BUILD_SHA__=b6986302`. | local Flutter + browser-facing Guarappari web route `/mapa` | `passed` | Exact DOD covered with widget route assertions and browser-facing `/mapa` runtime proof. |
| `DOD-EXACT-05` | Definition of Done | Focused Laravel tests pass for programming `end_time`, occurrence taxonomy validation, and effective occurrence taxonomy filtering. | Laravel real-backend integration tests | 23-test Laravel safe runner suite, 170 assertions; `./scripts/delphi/run_laravel_tests_safe.sh ...` executed inside the Laravel container as real-backend integration/feature evidence for programming `end_time`, occurrence taxonomy validation, and effective occurrence taxonomy filtering. | Laravel container real-backend integration target | `passed` | Exact DOD covered. |
| `DOD-EXACT-06` | Definition of Done | `fvm dart analyze --format machine` passes or unrelated diagnostics are isolated per the Flutter app analyzer gate. | Analyzer | `fvm dart analyze --format machine` exit 0 after ADB/test fixture edits. | local Flutter | `passed` | Exact DOD covered. |
| `DOD-EXACT-07` | Definition of Done | Laravel formatter/test runner gates pass for touched backend code. | Laravel gates | Pint, Laravel architecture guard, and safe runner passed. | Laravel container | `passed` | Exact DOD covered. |
| `DOD-EXACT-08` | Definition of Done | Web build/Playwright or ADB evidence is recorded for visible runtime behavior where widget tests are insufficient. | ADB runtime + Playwright runtime | `foundation_documentation/artifacts/tmp/flutter-device-runner/t5-guarappari-adb-results-20260501.md`; `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`; source-owned Playwright spec `tools/flutter/web_app_tests/map_full_width.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly`; web build/publish proof `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app` (repo `build_web.sh` equivalent), `docker compose restart nginx`, refreshed real-domain `__WEB_BUILD_SHA__=b6986302`. | Android device `192.168.15.9:5555` + browser-facing Guarappari web route `/mapa` | `passed` | Exact DOD covered; web build provenance and source-owned Playwright spec recorded. |
| `DOD-EXACT-09` | Definition of Done | Canonical module docs are updated for durable contract changes. | Docs | Events, Agenda, Flutter Client Experience module docs updated. | docs | `passed` | Exact DOD covered. |
| `DOD-EXACT-10` | Definition of Done | Independent review/triple audit findings are resolved or explicitly adjudicated. | Audit artifacts | Triple audit session round 07 clean; prior rounds resolved/adjudicated. | no-context audit | `passed` | Exact DOD covered. |
| `DOD-EXACT-11` | Definition of Done | This TODO is consolidated with the two current Store Release orchestration plans before any delivery-ready claim. | Plan/guard evidence | Plan completion guard and orchestration delivery guard passed; external contact blocker remains outside T5. | docs + guards + ADB evidence | `passed` | Exact DOD covered. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scopes:** `flutter`, `laravel`, `foundation_documentation`
- **Expected supporting profiles:** `assurance` for triple review and test-quality audit; `performance` for query/index scrutiny.

## Package-First Assessment
- **Queries executed from environment root:**
  - `bash delphi-ai/tools/query_packages.sh --project-root . --search agenda`
  - `bash delphi-ai/tools/query_packages.sh --project-root . --search events`
  - `bash delphi-ai/tools/query_packages.sh --project-root . --search taxonomy`
- **Relevant packages found:** none returned by the local package registry query.
- **Decision:** implement in the host Flutter app and Laravel Events package surfaces already owning agenda/event/taxonomy behavior.
- **Tier:** host app / existing domain packages.
- **Rationale:** no reusable Delphi package was registered for these behaviors; canonical module docs and existing source ownership point to local Flutter/Laravel surfaces.

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** approved orchestration plan with disjoint workstreams, final reconciliation, and consolidated guards.
- **Why this level:** the polish items are small, but optional programming end time and occurrence taxonomy override require backend write/read/query, Flutter admin/public consumers, tests, docs, and performance review.
