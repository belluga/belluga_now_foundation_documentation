# Title
v0.2.0+8: Public Taxonomy Canonicalization and Runtime Facet Aggregation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual validation and source inspection converged on one structural problem: taxonomy semantics drift across Events, Account Profiles, and public filters.

Current state is split:
- Account Profiles already use structured `taxonomy_terms` snapshots and machine-key filtering.
- Events still actively accept, persist, read, and query legacy `tags[]` alongside `taxonomy_terms`.
- Home Agenda and public Discovery filters surface type/taxonomy options from static catalog logic instead of the actual current universe, so users can select filters that deterministically return zero results.
- Public event surfaces can miss or mis-propagate event-level taxonomies because some consumers read `taxonomy_terms`, others still read `tags`, and some Flutter paths locally improvise chips/facets.

This TODO moves the deferred taxonomy/filter owner into the current `v0.2.0+8` lane, because the user explicitly requested that the refactor land in the same version being promoted. It supersedes the older backlog owner `foundation_documentation/todos/active/vnext/TODO-vnext-home-and-discovery-taxonomy-aggregation-contract.md`.

The highest-risk surface is Home Agenda: it is one of the most frequent public queries in the app, so the refactor must improve correctness without introducing query-shape regressions, page-walking aggregations, or local filter synthesis.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/public-taxonomy-canonicalization-and-runtime-facets.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** it isolates one cross-stack but coherent objective: canonical taxonomy ownership plus runtime facets for Home Agenda and public Discovery, with explicit compatibility handling for legacy event `tags`.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb local discoveries about compatibility fields, aggregation envelope names, query-service boundaries, index/backfill needs, and PACED guard implementation while the primary objective remains unchanged.
- This TODO does **not** authorize a durable legacy shim as the closure shape. Query-time bridge aliases, pseudo-canonical `*_effective` fields, or fallback-only compatibility mirrors are not acceptable end states for this boundary. If effective taxonomy resolution needs an intermediate owner during implementation, that owner must be explicitly temporary and cannot satisfy closeout by itself.
- If work expands into map facet redesign, taxonomy-registry UX redesign, unrelated search work, or a broad global retirement of all legacy `tags` consumers beyond the touched slice, update or split the TODO before implementation continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Cross-Stack`, `Laravel`, `Flutter`, `Tenant-Public`, `Tenant-Admin`, `Events`, `AccountProfiles`, `Hard-Cutover`, `Promotion-Lane-Pending`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through the remaining package-wide promotion follow-through; the hard-cut taxonomy/runtime-facets slice is locally validated with focused Laravel/Flutter evidence plus deterministic guardrails.

## Active Work State
- **Work state:** `review`
- **Why this state now:** the hard-cut taxonomy cutover, runtime facets, focused Laravel/Flutter coverage, and deterministic PACED guardrails are implemented locally; remaining work is package-wide promotion follow-through and final no-reopen scrutiny.
- **Exit condition:** package-wide promotion follow-through leaves this TODO without reopened taxonomy/filter findings.

## Local Validation Snapshot
- Focused Laravel feature coverage passed for agenda/discovery taxonomy/runtime-facet behavior and event write hard-cut behavior:
  - `tests/Feature/Events/AgendaAndEventsControllerTest.php`
  - `tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`
  - `tests/Feature/Events/EventCrudControllerTest.php`
- Focused Flutter coverage passed for DTO/domain/runtime-facet consumers and label visibility:
  - `test/infrastructure/dal/dto/schedule/event_dto_test.dart`
  - `test/infrastructure/dal/dto/schedule/event_page_dto_test.dart`
  - `test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
  - `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
  - `test/application/sharing/event_invite_share_payload_test.dart`
  - `packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart`
  - `packages/belluga_discovery_filters/test/discovery_filter_core_test.dart`
- Deterministic PACED guardrails now enforce the cutover baseline in CI/local-required validation:
  - `docker compose exec -T app php scripts/public_taxonomy_cutover_guardrails.php`
  - `docker compose exec -T app php scripts/architecture_guardrails.php`
  - `tests/Unit/Guardrails/PublicTaxonomyCutoverGuardrailsTest.php`
- Final browser mutation evidence passed on the published local dev runtime after correcting the runner contract to the browser-facing hosts and valid tenant-admin credentials:
  - `PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH=/opt/google/chrome/chrome NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_NONLOCAL_MUTATION_HOSTS=1 PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_ADMIN_EMAIL=admin@guarappari.test NAV_ADMIN_PASSWORD=Secret!234 NAV_WEB_SHARD=filters bash tools/flutter/run_web_navigation_smoke.sh mutation`
  - Result: `5 passed (2.7m)` for the `filters` shard, including Home, Discovery, Map, and tenant-admin event rich-text mutation coverage.

## Scope
- [ ] Make structured `taxonomy_terms` the canonical public taxonomy source of truth for touched Event and Event Occurrence flows.
- [ ] Stop treating raw persisted Event `tags[]` as the source of truth for new writes, public filter semantics, or touched public chip rendering.
- [ ] Do not emit raw Event `tags[]` in touched backend payloads. If legacy stored data still needs temporary read compatibility, it must be normalized into canonical `taxonomy_terms` before the payload leaves Laravel.
- [ ] Freeze an explicit hard-cut boundary: touched Home Agenda, public Discovery, immersive Event detail, touched share/invite consumers, and touched backend payloads must cut over completely to canonical taxonomy projections inside this TODO.
- [ ] Default approved retained-compatibility set is empty. No consumer is pre-approved to keep raw Event `tags[]` in touched payloads or touched public-consumer logic; if implementation discovers a truly unavoidable non-touched consumer, this TODO must name that consumer and its removal condition before the compatibility is retained.
- [ ] Current named retained-compatibility consumer list at approval time: `none`. Any future retained consumer must be named explicitly in this TODO before implementation or closeout can treat it as approved compatibility instead of drift.
- [ ] Forbid silent compatibility drift: no new touched public consumer may keep or introduce raw/derived `tags[]` dependence without updating this TODO contract and its validation matrix first.
- [ ] Keep Account Profile taxonomy behavior aligned with the existing canonical `taxonomy_terms` + machine-key model; do not invent a second public taxonomy model for Discovery.
- [ ] Add backend-owned runtime facets for Home Agenda and public Discovery that are computed from the full filtered universe before pagination.
- [ ] Ensure Home Agenda and public Discovery no longer surface type/taxonomy options that cannot produce results inside the current universe.
- [ ] Define self-excluding facet semantics precisely: type facets aggregate over the current universe excluding only the active type filter for that surface, while taxonomy facets aggregate over the current universe honoring all other active filters and excluding only the current taxonomy selection for that same dimension/group.
- [ ] Freeze the backend facet-query shape before implementation: runtime facets must be produced in one bounded aggregation path (single aggregation query or equivalent indexed sub-query over the filtered universe), without page walking, fetch-all result hydration, per-event fan-out, or per-occurrence fan-out.
- [ ] Hard cutoff beats shim: this TODO must not close with a durable query-time bridge field such as `taxonomy_terms_effective`; the accepted end state is canonical field ownership or canonical materialized/read-model ownership.
- [ ] Run a dedicated `cutover-integrity` review before any delivery claim and treat shim/bridge findings as blocking until the TODO records an approved temporary boundary or removes the workaround.
- [ ] Ensure touched Home Agenda and public Discovery filter controls render the resolved human label in the default unselected state instead of revealing the label only after selection.
- [ ] Keep current backend-owned universe rules authoritative (tenant scope, visibility, effective origin/radius, time windows, live/future rules, public-catalog/queryability gates, and other existing filters).
- [ ] Preserve occurrence-first taxonomy behavior for public Event queries: occurrence-owned taxonomy overrides remain replacement semantics, not merged additions.
- [ ] Prove occurrence replacement semantics per touched consumer: when an occurrence has taxonomy overrides, touched consumers must receive occurrence terms only; when it does not, they must fall through to event-level terms without merge behavior.
- [ ] Ensure selected public Event detail, agenda cards, and touched share/invite payload builders consume canonical taxonomy projections instead of ad hoc `tags` fallback logic, with no raw Event `tags[]` contract required in Flutter for those touched flows.
- [ ] Ensure public Discovery and related Account Profile chips remain driven by canonical taxonomy snapshots and current public catalog scope.
- [ ] Ensure tenant-admin event authoring/readback for touched flows persists and hydrates canonical taxonomy data for both new events and legacy events, while touched read payloads expose only canonical taxonomy fields.
- [ ] Add deterministic PACED guardrails/scripts so new public query/filter paths cannot silently reintroduce raw-`tags` query logic or local facet synthesis outside the canonical query services.
- [ ] Require those guardrails/scripts to run in the local/CI validation surface for this TODO; documentation-only or optional local checks are insufficient.
- [ ] Guardrails must cover both mutation/query paths and read/projection paths for raw `tags[]` usage inside touched namespaces, and the required enforcement surface is CI-required validation (local mirror allowed but not sufficient by itself).
- [ ] Add a full backend/Flutter/browser/device validation matrix covering legacy data, new data, occurrence overrides, all-pages facet aggregation, and no-empty-result option behavior.
- [ ] Keep shared compatibility consumers such as map/event projections green for the touched slice, without redesigning the Map filter product.

## Delivery Status Semantics
- `Pending`: no implementation has started.
- `Local-Implemented`: implementation exists locally and all required local evidence rows are passed.
- `Lane-Promoted`: the TODO has crossed the active lane threshold.
- `Production-Ready`: final threshold and required confidence gates are satisfied.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `docker:<current v0.2.0+8 lane>`, `laravel-app:<current v0.2.0+8 lane>`, `flutter-app:<current v0.2.0+8 lane>`; confirm with `git status -sb` before implementation.
- **Promotion lane path:** current `v0.2.0+8` promotion lane; no parallel version/branch may be created for this work.
- **Lane-promoted threshold for this TODO:** `dev` unless the active promotion contract for `v0.2.0+8` is stricter.
- **Production-ready threshold for this TODO:** same threshold as the consolidated `v0.2.0+8` package.

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Public taxonomy canonicalization + runtime facets | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] Map facet-contract redesign or map filter UX redesign.
- [ ] Taxonomy registry CRUD/authoring redesign.
- [ ] Agenda/events text-search expansion.
- [ ] Unrelated share/invite messaging changes.
- [ ] Global retirement of every remaining legacy `tags` compatibility field across every downstream consumer if that work exceeds the touched `v0.2.0+8` slice.
- [ ] Replacing occurrence-first event semantics, public catalog/queryability decisions, or backend-owned geo filtering rules.
- [ ] Any parallel backlog owner left active in `foundation_documentation/todos/active/vnext/` for this same boundary.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** exact facet envelope names, compatibility-field names, new helper/query-service names, idempotent repair/backfill details, index additions, static catalog metadata reuse, and PACED guard implementation details.
- **Must update or split the TODO:** map-specific facet redesign, taxonomy-registry UX, unrelated search scope, or a repo-wide legacy-tag retirement that materially exceeds the current user-visible slice.

## Definition of Done
- [ ] `DOD-01` Touched Event/Event Occurrence writes treat `taxonomy_terms` as canonical and no touched write path depends on raw client-supplied `tags[]` as the source of truth or as a shadow write target on the touched mutation paths.
- [ ] `DOD-02` Legacy Event data that still has `tags[]` but lacks canonical taxonomy snapshots remains readable/filterable only through backend-side canonical normalization/backfill behavior until repaired; touched payloads never expose raw `tags[]`, and that temporary normalization path is documented and tested.
- [ ] `DOD-03` Public event chips/taxonomy summaries for touched event surfaces (`Home Agenda` cards, immersive Event detail, and touched share/invite payload builders) are driven from canonical taxonomy projections rather than ad hoc `event.tags` fallback.
- [ ] `DOD-04` Home Agenda returns backend-owned runtime facets for the full filtered universe before pagination and does not expose empty-result type/taxonomy options.
- [ ] `DOD-05` Public Discovery returns backend-owned runtime facets for the full filtered universe before pagination and does not expose empty-result type/taxonomy options.
- [ ] `DOD-06` Facet semantics are self-excluding per dimension, so the user can still see valid alternatives under the current universe instead of a collapsed selected-only list.
- [ ] `DOD-06A` Touched Home Agenda and public Discovery filter controls show the resolved human label before selection and preserve the same label after selection.
- [ ] `DOD-07` Flutter Home Agenda and public Discovery consumers do not synthesize type/taxonomy options from current page items or static local catalogs; they consume the canonical backend facet contract.
- [ ] `DOD-08` Occurrence-owned taxonomy replacement overrides remain correct for filtering, selected detail, and touched public chips.
- [ ] `DOD-09` Tenant-admin event authoring/readback persists and rehydrates canonical taxonomy data for new events and edited legacy events in this slice.
- [ ] `DOD-10` Compatibility consumers touched by this refactor, including shared map/event projection paths, remain functional through explicit derived compatibility or direct cutover with tests, and the retained compatibility set is explicitly bounded.
- [ ] `DOD-11` Query paths are performant: no page walking, no fetch-all/in-memory facet aggregation, no local Flutter post-filtering, no runtime index creation, and no per-occurrence query fan-out for effective taxonomy resolution.
- [ ] `DOD-11A` The final accepted architecture does not depend on a durable query-time bridge alias or pseudo-canonical `*_effective` field to express event taxonomy ownership; if any temporary bridge exists during implementation, closeout is blocked until canonical ownership/materialization replaces it.
- [ ] `DOD-11B` A dedicated `cutover-integrity` reviewer has challenged the final taxonomy path and recorded that the chosen shape is canonical rather than a disguised compatibility bridge or query-time workaround.
- [ ] `DOD-12` Deterministic PACED guardrails fail or report when a new public query/filter path bypasses the canonical taxonomy/facet services or reintroduces raw-`tags` query logic, and those guardrails are exercised by the local/CI validation surface.
- [ ] `DOD-13` Required Laravel, Flutter, Playwright, and device/runtime validation matrices pass and are recorded before promotion.

## Validation Steps
- [ ] `VAL-01` Laravel tests prove touched Event/Event Occurrence writes canonicalize `taxonomy_terms`, stop using raw `tags[]` as the source of truth, and do not keep raw `tags[]` as a shadow write target on the touched mutation paths.
- [ ] `VAL-01A` Laravel feature coverage includes at least one discrete touched-write regression test that is intended to fail when a raw-`tags[]` shadow write is reintroduced, so CI can catch the regression instead of relying on narrative inspection.
- [ ] `VAL-02` Laravel tests prove legacy Events with only `tags[]` still read/filter correctly through backend-side canonical normalization/backfill until repaired, while touched payloads do not emit raw `tags[]`.
- [ ] `VAL-03` Laravel feature tests prove Home Agenda facets are computed over the full filtered universe, not only the current page.
- [ ] `VAL-04` Laravel feature tests prove Home Agenda facets do not offer type/taxonomy options that would return zero results within the current universe.
- [ ] `VAL-05` Laravel feature tests prove public Discovery facets are computed over the full filtered universe, not only the current page.
- [ ] `VAL-06` Laravel feature tests prove public Discovery facets do not offer type/taxonomy options that would return zero results within the current universe.
- [ ] `VAL-07` Laravel tests prove occurrence-owned taxonomy replacement overrides still control effective filtering and selected detail projections with replacement-only semantics, not merged semantics.
- [ ] `VAL-07A` Laravel tests include at least one explicit negative replacement case where event-level and occurrence-level taxonomy values differ, and the touched consumer output is asserted to contain only occurrence terms with zero merged parent-event terms.
- [ ] `VAL-08` Laravel tests prove page-2-only types/taxonomies still appear in facet payloads when they exist in the filtered universe.
- [ ] `VAL-08A` Laravel performance guard tests prove the final facet/query path stays bounded and does not regress into page walking, fetch-all aggregation, per-occurrence query fan-out, or runtime index creation.
- [ ] `VAL-08B` Architecture validation proves the final cutover does not rely on a durable query-time bridge alias/pseudo-canonical `*_effective` field as the ownership boundary; evidence must point to the canonical field or materialized projection that replaces it.
- [ ] `VAL-08C` A no-context `cutover-integrity` review validates that the final taxonomy cutover is not closing on pseudo-canonical fields, silent fallback mirrors, dual-path bridges, or query-time stitching that merely hides missing canonical ownership.
- [ ] `VAL-09` Flutter DTO/domain tests decode the canonical facet payloads and canonical event/account taxonomy projections without relying on raw `tags[]` as primary input.
- [ ] `VAL-10` Flutter controller/widget tests prove Home Agenda and public Discovery no longer synthesize local filter options from current page items and do not substitute local post-filtering for backend-owned facet semantics.
- [ ] `VAL-10A` Flutter controller/widget or screen tests prove touched Home Agenda and public Discovery filter controls render the resolved label in the default unselected state and preserve it after selection.
- [ ] `VAL-10B` Flutter validation includes at least one integration/widget gate per touched Home/Discovery facet surface that uses a real or contract-faithful backend fixture instead of a free-form local stub, so browser/device lanes are not the first real-contract checkpoint.
- [ ] `VAL-11` Admin Playwright mutation proves tenant-admin event authoring/readback for new and legacy events persists canonical taxonomies and public readback reflects them.
- [ ] `VAL-12` Public Playwright navigation proves Home Agenda and public Discovery filter choices only show runtime-valid options and do not produce empty-result false options; the visible option set must be asserted as a subset of the backend facet payload for the current universe.
- [ ] `VAL-13` Public Playwright navigation proves touched Event detail/event card/share/invite surfaces show the expected canonical taxonomy chips for event-level and occurrence-override scenarios, including replacement-only override behavior.
- [ ] `VAL-14` ADB/device validation proves the app Home/Discovery filter flows consume the same canonical runtime facet behavior on device for the final promoted build under test, using source-owned integration tests (`integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart`, `integration_test/feature_agenda_filters_regression_test.dart`, and a dedicated Discovery runtime-facets integration test if no existing device test already covers that flow), with explicit pass criteria for no empty-result options.
- [ ] `VAL-15` Local CI-equivalent suite matrix runs for Laravel, Flutter tests, analyzer, rule matrix, web build, required readonly/mutation web navigation lanes, and the guardrail enforcement surface for raw-`tags` / local-facet regressions.
- [ ] `VAL-15A` Guardrail evidence must include an explicit pass signal: the named guard command exits `0` and reports no violations for the touched raw-`tags[]` / local-facet scope.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | Canonical Event/Event Occurrence writes use `taxonomy_terms` and do not shadow-write raw `tags[]` on touched paths. | `test|code` | `planned` | `backend` | `planned` | Must cover create and update. |
| `DOD-02` | `Definition of Done` | Legacy `tags[]`-only Events remain readable/filterable only through backend-side canonical normalization/backfill, without raw `tags[]` in touched payloads. | `test` | `planned` | `backend` | `planned` | Temporary normalization path must be bounded, documented, and removable. |
| `DOD-03` | `Definition of Done` | Touched public event chips are canonical-taxonomy-driven. | `test|runtime` | `planned` | `flutter|browser` | `planned` | Negative assertion: no touched consumer derives primary chips only from raw `tags`. |
| `DOD-04` | `Definition of Done` | Home Agenda runtime facets are universe-wide pre-pagination. | `test|runtime` | `planned` | `backend|browser|device` | `planned` | Must include page-2-only taxonomy/type proof. |
| `DOD-05` | `Definition of Done` | Public Discovery runtime facets are universe-wide pre-pagination. | `test|runtime` | `planned` | `backend|browser|device` | `planned` | Must include page-2-only taxonomy/type proof. |
| `DOD-06` | `Definition of Done` | Facets are self-excluding per dimension. | `test` | `planned` | `backend|flutter` | `planned` | Must distinguish type self-exclusion from taxonomy-dimension self-exclusion. |
| `DOD-06A` | `Definition of Done` | Touched filter controls show labels before selection. | `test|runtime` | `planned` | `flutter|browser|device` | `planned` | Applies to touched Home Agenda and public Discovery filter UI. |
| `DOD-07` | `Definition of Done` | Flutter Home/Discovery stops local facet synthesis. | `test|guard` | `planned` | `flutter` | `planned` | Controller/widget plus rule/scan evidence. |
| `DOD-08` | `Definition of Done` | Occurrence override taxonomy semantics stay correct. | `test|runtime` | `planned` | `backend|browser` | `planned` | Must cover parent-event vs selected-occurrence behavior. |
| `DOD-09` | `Definition of Done` | Admin authoring/readback persists canonical taxonomies. | `test|runtime` | `planned` | `backend|browser` | `planned` | Needs new-event and edited-legacy-event coverage. |
| `DOD-10` | `Definition of Done` | Compatibility consumers remain functional. | `test` | `planned` | `backend|flutter` | `planned` | Map/event projection compatibility is regression-only, not redesign. |
| `DOD-11` | `Definition of Done` | Query paths are performant and bounded. | `test|review|performance` | `planned` | `backend` | `planned` | Query-shape evidence required; local filtering, page walking, and per-occurrence fan-out are not acceptable. |
| `DOD-11B` | `Definition of Done` | Dedicated cutover-integrity review rejects shim-based closure shapes. | `review` | `planned` | `cross-stack` | `planned` | Reviewer must explicitly assess whether the chosen path is canonical vs workaround architecture. |
| `DOD-12` | `Definition of Done` | PACED guards catch raw-tag/ad hoc facet regressions. | `test|guard` | `planned` | `backend|flutter` | `planned` | Guard output must explain what failed and how to audit exceptions; guardrails must run in the validation surface. |
| `DOD-13` | `Definition of Done` | Full validation matrix passes. | `test|runtime` | `planned` | `backend|browser|device` | `planned` | CI-equivalent plus runtime lanes required. |
| `VAL-01` | `Validation Steps` | Canonical Event/Event Occurrence writes. | `test` | `planned` | `backend` | `planned` | Must assert canonical writes and absence of touched-path shadow writes to raw `tags[]`. |
| `VAL-01A` | `Validation Steps` | Shadow-write regression trap exists in CI. | `test` | `planned` | `backend` | `planned` | At least one discrete touched-write test must fail when raw `tags[]` shadow write returns. |
| `VAL-02` | `Validation Steps` | Legacy `tags[]` backend-side canonical normalization. | `test` | `planned` | `backend` | `planned` | Existing docs/fixtures must prove touched payloads no longer emit raw `tags[]`. |
| `VAL-03` | `Validation Steps` | Home universe-wide facets. | `test` | `planned` | `backend` | `planned` | Includes all-pages aggregation. |
| `VAL-04` | `Validation Steps` | Home no-empty-result options. | `runtime` | `planned` | `browser|device` | `planned` | Public visible behavior. |
| `VAL-05` | `Validation Steps` | Discovery universe-wide facets. | `test` | `planned` | `backend` | `planned` | Includes all-pages aggregation. |
| `VAL-06` | `Validation Steps` | Discovery no-empty-result options. | `runtime` | `planned` | `browser|device` | `planned` | Public visible behavior. |
| `VAL-07` | `Validation Steps` | Occurrence override taxonomy behavior. | `test|runtime` | `planned` | `backend|browser` | `planned` | Covers event + occurrence permutations. |
| `VAL-07A` | `Validation Steps` | Occurrence replacement negative no-merge proof. | `test` | `planned` | `backend` | `planned` | Must assert event-level terms do not leak when occurrence terms exist. |
| `VAL-08` | `Validation Steps` | Page-2-only facet values surface correctly. | `test|runtime` | `planned` | `backend|browser` | `planned` | Explicitly blocks current-page-only false green. |
| `VAL-08A` | `Validation Steps` | Performance guard coverage for the final facet/query path. | `test|performance` | `planned` | `backend` | `planned` | Must cite the concrete performance-guard test file/run, not only broad suite success, and must cover page-walking/fetch-all/per-occurrence-fan-out risks. |
| `VAL-08C` | `Validation Steps` | No-context cutover-integrity review rejects pseudo-canonical bridge closure. | `review` | `planned` | `cross-stack` | `planned` | Must explicitly call out whether any remaining alias, fallback mirror, dual-path bridge, or query-time stitching survives in the accepted architecture. |
| `VAL-09` | `Validation Steps` | Flutter DTO/domain canonical parsing. | `test` | `planned` | `flutter` | `planned` | Includes event + account surfaces. |
| `VAL-10` | `Validation Steps` | Flutter Home/Discovery controller behavior. | `test` | `planned` | `flutter` | `planned` | Must assert no local synthesis and no local post-filter fallback. |
| `VAL-10A` | `Validation Steps` | Filter labels remain visible before and after selection. | `test|runtime` | `planned` | `flutter|browser|device` | `planned` | Guards the user-visible label regression on touched filter UI. |
| `VAL-11` | `Validation Steps` | Admin mutation/readback flow. | `runtime` | `planned` | `browser` | `planned` | Must use the real admin path. |
| `VAL-12` | `Validation Steps` | Public Home/Discovery runtime validation. | `runtime` | `planned` | `browser` | `planned` | Filters must never surface empty-result false options and visible options must align with backend facets. |
| `VAL-13` | `Validation Steps` | Public event chip propagation validation. | `runtime` | `planned` | `browser` | `planned` | Covers event-level and occurrence-level terms, including replacement-only override semantics. |
| `VAL-14` | `Validation Steps` | ADB/device Home/Discovery validation. | `runtime` | `planned` | `device` | `planned` | Must name the concrete source-owned integration tests used for Home and Discovery; a generic manual device pass is insufficient; pass criteria must include no empty-result options. |
| `VAL-15` | `Validation Steps` | Full CI-equivalent suite matrix. | `test` | `planned` | `CI|local` | `planned` | Wrapper report required, including guardrail enforcement. |
| `VAL-15A` | `Validation Steps` | Guardrail pass signal is explicit. | `test|guard` | `planned` | `CI|local` | `planned` | Evidence must show the exact command exited `0` with no violations. |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `guarappari.belluga.space dev tenant runtime` | Required for final readonly/mutation browser validation of public Home/Discovery and admin event authoring. | `unknown` | `pending` | web navigation smoke + build SHA verification | Reflect runtime degradation explicitly before delivery claim. |
| `ADB-connected Android device` | Required for final device evidence on Home/Discovery filter behavior. | `unknown` | `pending` | `adb devices` + resilient integration runner | If unavailable, the TODO must record an explicit waiver request instead of silently dropping device coverage. |
| `Atlas/dev tenant dataset` | Facet aggregation needs enough real data to prove all-pages and no-empty-result behavior. | `unknown` | `pending` | seeded fixture + runtime readback | Seed or repair data before final runtime validation. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | This TODO changes high-frequency public queries and must prove no false-green validation gaps remain. | Laravel tests, Flutter tests, Playwright lanes, ADB/device lane | `planned` |

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** it changes canonical data semantics, public query contracts, compatibility behavior, Flutter controller/domain behavior, and the final runtime validation surface across admin/public/app flows.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `events_module.md` sections `4`, `5.1`, `5.2`, `5.4`
  - `agenda_and_action_planner_module.md` sections `3.4`, `7`
  - `account_profile_catalog_module.md` sections `4`, `4.1`, `7`
  - `map_poi_module.md` sections `4.1`, `6` (compatibility only)
- **Module decision consolidation targets (required):**
  - Event taxonomy canonical-source and compatibility posture
  - Home/Discovery public facet contract
  - Public chip/filter display contract for touched event/account consumers

## Decision Pending (Resolve Before Freeze)
- [ ] `D-TAX-02` Freeze the public facet envelope naming shared across Home Agenda and public Discovery (`facets`, `types`, `taxonomies`, pagination nesting, applied-filter echo).
- [ ] `D-TAX-03` Freeze whether public event chip rendering gets a dedicated backend-calculated field or is fully derived from canonical taxonomy snapshots in the client/domain layer.

## Decisions (Resolved Before Freeze)
- [ ] `D-TAX-01` During `v0.2.0+8`, touched Home Agenda, public Discovery, immersive Event detail, touched share/invite consumers, and touched backend payloads must cut over completely to canonical taxonomy projections. Raw Event `tags[]` is not an approved touched payload or touched public-consumer contract.
- [ ] `D-TAX-04` Structured `taxonomy_terms` is the canonical source of truth for touched public taxonomy semantics; raw Event `tags[]` is not.
- [ ] `D-TAX-05` Home Agenda and public Discovery facets are backend-owned and computed from the full current universe before pagination.
- [ ] `D-TAX-06` Facets are self-excluding per dimension: type facets exclude only the active type filter; taxonomy facets exclude only the active taxonomy selection for that same dimension/group while honoring all other active filters.
- [ ] `D-TAX-07` Occurrence-owned taxonomy override semantics remain replacement-only and must survive the refactor unchanged.
- [ ] `D-TAX-08` Static registries/catalogs remain metadata sources for labels, icons, color, and ordering, but they do not define the candidate universe by themselves.
- [ ] `D-TAX-09` Flutter Home/Discovery must not synthesize filter options locally from current page items or stale catalog-only state.
- [ ] `D-TAX-10` Deterministic PACED guardrails are required for raw-`tags` query usage and ad hoc public facet builders outside the canonical services, and those guardrails must run in the local/CI validation surface for this TODO.
- [ ] `D-TAX-11` Map/event/account compatibility consumers are regression obligations inside this TODO, but map facet redesign remains out of scope.
- [ ] `D-TAX-12` This TODO supersedes the prior `active/vnext` owner for Home/Discovery taxonomy aggregation and becomes the single current-lane owner of this boundary.
- [ ] `D-TAX-13` Hard cutoff is preferred over legacy shim for this slice. A durable query-time bridge alias (for example `taxonomy_terms_effective`) is not an approved final architecture; canonical field ownership or canonical materialized/read-model ownership is required before closeout.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `EVS-FILTER-02` | Agenda filtering is taxonomy/category/tag + geo only. | `Preserve` | `foundation_documentation/modules/events_module.md` |
| `EVS-TAX-01` | Event/Event Occurrence taxonomy summaries are display-ready snapshots while filters remain slug-pair based. | `Preserve` | `foundation_documentation/modules/events_module.md` |
| `EVS-OCC-03` | Occurrence taxonomy overrides are replacement semantics. | `Preserve` | `foundation_documentation/modules/events_module.md` |
| `Agenda 3.4 display rule` | Event-card chips use `event.tags` if present, else artist genres. | `Supersede (Intentional)` | `foundation_documentation/modules/agenda_and_action_planner_module.md` section `3.4` |
| `Discovery 4.1 category/type options` | Discovery chips/options are derived from the centralized public catalog scope. | `Supersede (Intentional)` | `foundation_documentation/modules/account_profile_catalog_module.md` section `4.1` |
| `PCO-12` | Account Profile taxonomy terms are display snapshots; machine keys remain query identity. | `Preserve` | `foundation_documentation/modules/account_profile_catalog_module.md` |
| `AGD-05` | Backend geo filtering is authoritative; clients do not locally filter after fetch. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` |
| `MAP-11` | Map taxonomy projections keep display snapshots and machine-key filtering. | `Preserve` | `foundation_documentation/modules/map_poi_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-TAX-BASE-01` Freeze the canonical ownership of `taxonomy_terms` and the hard-cut posture for raw Event `tags[]` in touched payloads and touched public consumers.
- [ ] `D-TAX-BASE-02` Freeze the runtime facet semantics for Home Agenda and public Discovery.
- [ ] `D-TAX-BASE-03` Freeze the touched public chip-rendering contract for events/accounts.
- [ ] `D-TAX-BASE-04` Freeze the PACED guard boundary for canonical taxonomy/facet services.

## Questions To Close
- [ ] Which explicitly enumerated non-touched compatibility consumers, if any, still require backend-internal normalization from legacy `tags[]` during `v0.2.0+8`, and what is the removal condition for each one? Current approval-time answer: `none`.
- [ ] Is one shared facet envelope for Home Agenda and public Discovery worth the contract churn in this slice, or should only the semantics be shared while envelope names stay surface-specific?
- [ ] Is a dedicated backend-calculated public chip field required for Events, or can canonical taxonomy snapshots plus domain formatting close the current drift cleanly?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-TAX-01` | Account Profile taxonomy behavior is the closest current canonical model and should be reused rather than replaced. | `account_profile_catalog_module.md`; `AccountProfileManagementService`; `AccountProfileQueryService` | The slice would need a wider cross-domain redesign. | `High` | `Keep as Assumption` |
| `A-TAX-02` | Home Agenda universe semantics are already backend-defined enough to support runtime facet aggregation without redefining product behavior. | `AGD-05`; current `/agenda` contract; existing effective-origin/radius rules | The TODO would need product-contract decisions first. | `High` | `Keep as Assumption` |
| `A-TAX-03` | Some current Flutter/share/invite consumers still read Event `tags[]`, but this TODO is allowed to cut those touched consumers over before `Local-Implemented`; backend payloads may simultaneously stop emitting raw `tags[]`. | `flutter-app/lib/domain/schedule/event_model.dart`; `event_dto.dart`; invite/share factories | The TODO would need a broader contract cutover across touched event payload consumers. | `High` | `Promote to Decision` |
| `A-TAX-04` | Mongo/Laravel can compute the required facets with bounded aggregation or indexed read-model support, without page walking or fetch-all logic. | Existing map taxonomy aggregation patterns; current event/account query indices and aggregation services | The performance design would need a different read-model/materialization approach. | `Medium` | `Keep as Assumption` |
| `A-TAX-05` | Map compatibility can remain a regression obligation only; a map facet redesign is not required for this current-lane slice. | User explicitly narrowed the current filter request to Home/Discovery. | The TODO would need scope expansion and fresh approval. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/**`
- `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/**`
- `laravel-app/app/Application/DiscoveryFilters/**`
- `laravel-app/app/Application/AccountProfiles/**`
- `laravel-app/app/Http/Api/v1/Controllers/DiscoveryFiltersController.php`
- `flutter-app/lib/presentation/shared/discovery_filters/**`
- `flutter-app/lib/presentation/tenant_public/home/**`
- `flutter-app/lib/presentation/tenant_public/discovery/**`
- `flutter-app/lib/domain/schedule/event_model.dart`
- `flutter-app/lib/infrastructure/dal/dto/schedule/event_dto.dart`
- `flutter-app/lib/application/sharing/**`
- PACED guard/script surfaces under repo tooling if required by the chosen implementation

### Ordered Steps
1. Freeze the canonical taxonomy/compatibility decisions (`taxonomy_terms` ownership, `tags[]` posture, facet envelope semantics).
2. Add fail-first Laravel coverage for Event canonical writes, absence of touched-path `tags[]` shadow writes, no raw `tags[]` in touched payloads, backend-side legacy normalization/backfill behavior, and Home/Discovery universe-wide facets.
3. Implement the canonical backend taxonomy/query services for touched Event and Discovery/Home paths, preserving occurrence override semantics.
4. Add or adjust indices/read-model support as needed to keep facet aggregation bounded and performant, with one aggregation path over the filtered universe and no per-occurrence fan-out.
5. Add fail-first Flutter DTO/domain/controller tests proving Home/Discovery stop local facet synthesis and touched event/account surfaces stop relying on raw `tags[]` as primary input.
6. Cut touched Flutter consumers to the canonical facet/taxonomy contract and remove raw Event `tags[]` dependence from those touched flows.
7. Add PACED guardrails/source scans for raw-`tags` public query usage and ad hoc public facet builders outside the canonical services, and wire them into the CI-required validation surface for this TODO (local mirror allowed, but CI pass signal is mandatory).
8. Run focused Laravel + Flutter suites, analyzer, and rule matrix.
9. Build web with the project script, run readonly/mutation Playwright validation against the refreshed tenant runtime, then run ADB/device evidence for Home/Discovery app flows.
10. Update module docs and evidence rows before delivery claim.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** this slice is false-green prone. It changes canonical data semantics, public filter behavior, compatibility projections, and high-frequency runtime flows.
- **Fail-first target(s) (when required):**
  - Laravel: Event canonical-write tests, touched-path no-shadow-write tests for raw `tags[]`, no-raw-tags payload tests, backend-side legacy normalization/backfill tests, Home/Discovery all-pages facet tests, empty-result facet suppression tests, occurrence replacement-semantic tests, and facet query-shape/query-count tests.
  - Flutter: DTO/domain parsing tests; Home/Discovery controller tests proving no local synthesis and no local post-filter fallback.
  - Runtime: add source-owned Playwright coverage for Home/Discovery filter behavior, explicit browser pass criteria against backend facets, and admin event taxonomy readback before claiming closure.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Admin creates a new event with event-level taxonomies and public surfaces show canonical chips/facets | Admin mutation + public readback | `shared-android-web` | `Playwright mutation + readonly + ADB detail/home check` | `yes` | `yes` | Admin event authoring browser flow, public Home/Event readonly flow, `feature_home_agenda_eligible_events_query_contract_e2e_test.dart` plus device Event/detail verification | `n/a` |
| Admin edits a legacy event that previously relied on raw `tags[]` | Migration/readback risk | `shared-android-web` | `Playwright mutation + readonly` | `yes` | `yes` | Legacy fixture update + public readback | `n/a` |
| Home Agenda only shows facets that exist in the current universe, including page-2-only values | High-frequency public query surface | `shared-android-web` | `Playwright readonly + ADB` | `no` | `yes` | Browser Home flow with seeded page-2 data + `feature_home_agenda_eligible_events_query_contract_e2e_test.dart` / `feature_agenda_filters_regression_test.dart`; pass only if visible options are a strict subset of backend facets and no empty-result option appears | `n/a` |
| Public Discovery only shows facets that exist in the current universe, including page-2-only values | High-frequency public query surface | `shared-android-web` | `Playwright readonly + ADB` | `no` | `yes` | Browser Discovery flow with seeded page-2 data + source-owned Discovery runtime-facets integration test; pass only if visible options are a strict subset of backend facets and no empty-result option appears | `n/a` |
| Occurrence override taxonomies affect selected detail and filters correctly | Multi-occurrence correctness | `shared-android-web` | `Playwright readonly` | `no` | `yes` | Public event detail/readback with override and fallback occurrences; pass only if override terms replace event terms when present and fall through cleanly when absent | `n/a` |
| Flutter Home/Discovery no longer synthesize local facets | Query/filter correctness can drift silently in app only | `shared-android-web` | `ADB integration/device` | `no` | `yes` | Device runtime flow plus controller tests | `n/a` |
| Map/event compatibility consumer still works after canonical taxonomy cutover | Shared projection regression | `shared-android-web` | `Playwright readonly` or explicit backend-only waiver if no visible delta | `no` | `yes` | Regression row in readonly suite or justified waiver | `Requires explicit waiver only if no user-visible delta in touched map path` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` focused taxonomy/query suites | Canonical write/query/facet behavior changes in backend domain and public contract. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 taxonomy refactor focused validation" --laravel-test tests/Feature/Events/EventCrudControllerTest.php --laravel-test tests/Feature/Events/AgendaAndEventsControllerTest.php --laravel-test tests/Feature/Events/EventQueryPerformanceGuardrailTest.php --laravel-test tests/Unit/Events/EventQueryServiceTest.php --laravel-test tests/Unit/DiscoveryFilters/DiscoveryFilterCoreTest.php --laravel-test tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` | `Local-Implemented` | `planned` | `pending` | Exact file/filter split may tighten during implementation, but wrapper evidence is mandatory. |
| `flutter-app` focused DTO/domain/controller/widget suites | Home/Discovery consumers and event/account taxonomy consumers change in Flutter. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 taxonomy refactor focused validation" --flutter-test test/infrastructure/dal/dto/schedule/event_dto_test.dart --flutter-test test/domain/schedule/event_related_profile_groups_test.dart --flutter-test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart --flutter-test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart --flutter-test test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart --flutter-test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart --flutter-analyze` | `Local-Implemented` | `planned` | `pending` | Add exact new test files as implementation defines them. |
| `flutter_rule_matrix` | Public Flutter query/filter surfaces must remain architecture-clean. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 taxonomy refactor focused validation" --repo-command flutter_rule_matrix flutter-app 'bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh'` | `Local-Implemented` | `planned` | `pending` | Required because filter/controller boundaries are touched. |
| `flutter_web_build` | Browser validation must run against the refreshed final bundle. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 taxonomy refactor web build" --repo-command flutter_web_build flutter-app 'CLEAN_OUTPUT=1 bash scripts/build_web.sh ../web-app dev'` | `promotion` | `planned` | `pending` | Final Playwright runs must target the refreshed build SHA. |
| `web_navigation_readonly` | Public Home/Discovery/Event readonly flows are in scope. | `./scripts/delphi/run_navigation_reconcile_validation.sh readonly` | `promotion` | `planned` | `pending` | Must include source-owned specs for Home/Discovery facets and Event taxonomy display. |
| `web_navigation_mutation` | Admin event authoring/readback is in scope. | `./scripts/delphi/run_navigation_reconcile_validation.sh mutation` | `promotion` | `planned` | `pending` | Must cover new event + edited legacy event taxonomy authoring/readback. |
| `taxonomy_guardrails` | Raw-`tags` / local-facet regressions must fail deterministically. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 taxonomy guardrails" --repo-command taxonomy_guardrails belluga_now_docker '<wire canonical taxonomy guard/script command here>'` | `Local-Implemented` | `planned` | `pending` | Replace placeholder with the concrete guard command during implementation; required pass signal is command exit `0` with no violations reported; guardrails must not remain optional/local-only. |

### Runtime / Rollout Notes
- If canonical taxonomy cutover needs an idempotent repair/backfill for legacy Events, that backfill must be explicit, rerunnable, and tested. It must not rely on ad hoc production-manual edits.
- Retained raw `tags[]` compatibility, if any, must be explicitly enumerated by consumer before implementation proceeds. Current approval-time retained-compatibility list is `none`. Touched Home Agenda, public Discovery, immersive Event detail, touched share/invite consumers, and touched backend payloads are not allowed to stay on raw Event `tags[]` by delivery of this TODO.
- If new indices are required for bounded facet aggregation, they must be delivered via application-owned migration flow; runtime query paths must not create indexes.
- Final browser/device evidence must use seeded or repaired data that proves all-pages facet aggregation and no-empty-result options, not only happy-path single-page data.
- Browser/device evidence must verify backend facet alignment explicitly; a UI-only happy path is insufficient.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
Review the `Assumptions Preview` and `Execution Plan` against architecture, code quality, tests, performance, security, elegance, and structural soundness before approval.

### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

- Triple-audit evidence:
  - Active round 01 summary/adjudication: `foundation_documentation/artifacts/tmp/public-taxonomy-todo-triple-audit-20260604/session/round-01/round-summary.md`
  - Active round 01 resolution artifact: `foundation_documentation/artifacts/tmp/public-taxonomy-todo-triple-audit-20260604/session/round-01/resolution.md`
  - Active Claude no-context lane results: `foundation_documentation/artifacts/tmp/public-taxonomy-todo-triple-audit-20260604/session/round-01/results/`

### Issue Cards
- **Issue ID:** `ARCH-TAX-01`
  - **Severity:** `medium`
  - **Evidence:** current public filter surfaces still rely on static catalog logic while Events keeps active legacy `tags[]` query paths.
  - **Why it matters now:** implementing only one side would leave drift in place: either canonical data with wrong facets, or runtime facets over a still-ambiguous data source.
  - **Option A (Recommended):** canonicalize taxonomy ownership for touched Event flows and runtime facets together in one slice.
    - **Effort:** `high`
    - **Risk:** `medium`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** ship only runtime facets and postpone Event canonicalization.
    - **Effort:** `medium`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** leave the existing vnext owner deferred.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] Legacy Event without `taxonomy_terms` but with `tags[]` stops rendering chips or stops matching filters after cutover.
- [ ] Home/Discovery facet payload accidentally reflects only the current page, not the whole filtered universe.
- [ ] Selected type/taxonomy hides all alternative facet values because the backend does not self-exclude that dimension.
- [ ] Occurrence-owned taxonomy override leaks parent-event terms into override scenarios or hides fallback scenarios incorrectly.
- [ ] Flutter controller rebuilds local filter options from paged items, masking backend facet bugs.
- [ ] Query path introduces unbounded aggregation, page walking, or in-memory filtering under real tenant data volume.
- [ ] Map/event compatibility consumer silently breaks because derived `tags` or projection fields disappeared too early.

### Residual Unknowns / Risks
- [ ] The exact lifetime of any backend-internal legacy normalization/backfill path remains to be frozen before closeout.

## Approval
- **Approved by:** explicit user instruction in the current v0.2.0+8 thread.
- **Approved at:** `2026-06-05`
- **Approval evidence:** the user approved this TODO earlier in-thread and then tightened the execution contract with two explicit constraints: `Sem fallback. É hard cuttoff.` and `E o backend pode elimininar legado de seus payloads.`
- **Approval reference:** thread-local approval plus follow-up hardening instructions on `2026-06-05`.
- **Approval scope:** implement the approved taxonomy cutover in the current v0.2.0+8 lane; remove raw Event `tags[]` from touched backend payloads, touched Flutter/public event consumers, and touched event write semantics; keep runtime facets backend-owned and universe-wide; preserve only backend-internal temporary normalization/backfill for legacy stored data if strictly required and documented.
- **Renewal required:** `yes if a touched consumer needs raw Event tags[] to survive, if a new endpoint is proposed, or if scope expands into map facet redesign`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is a big tactical TODO and implementation must stay inside the approved contract. | Approval scope, DoD, validation matrix, and delivery gates. | Silent contract drift or code edits that preserve legacy touched payloads. | Implementation must remain bounded to the approved hard cut. |
| `delphi-ai/workflows/docker/todo-driven-execution-method.md` | The user explicitly required TODO-driven execution discipline. | Contract-first execution and evidence-backed delivery. | Treating local inference as enough evidence. | Every touched surface needs validation tied back to this TODO. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | Flutter DTO/domain/controller consumers are in scope. | DTO -> domain -> presentation separation and controller-owned query state. | Widget-side taxonomy synthesis or route-side fallback logic. | Flutter cutover must stay architecture-clean. |
| `delphi-ai/rules/stacks/laravel/shared/todo-driven-execution-model-decision.md` | Laravel event write/read/query contracts are in scope. | Canonical mutation/query authority and CI-required gates. | Keeping raw `tags[]` as touched payload/query truth. | Laravel changes must remove raw-tag drift at the source. |
| `foundation_documentation/modules/events_module.md` | It owns the event taxonomy semantics and occurrence override rules. | `EVS-TAX-01`, `EVS-OCC-03`, bounded public contracts. | Merged occurrence taxonomy semantics or payload drift. | Backend and Flutter must keep replacement-only occurrence behavior. |
| `foundation_documentation/modules/account_profile_catalog_module.md` | It is the existing canonical taxonomy snapshot model. | Snapshot fields `{type,value,name,taxonomy_name,label?}` and machine-key identity. | Introducing a second event/public taxonomy display model. | Event cutover should align to this model. |
| `foundation_documentation/modules/flutter_client_experience_module.md` | Flutter public taxonomy rendering and runtime contract are in scope. | Canonical snapshot consumption and backend-owned filter semantics. | Reintroducing slug/raw-tag display or local facet synthesis. | Public Flutter consumers must rely on canonical payloads only. |
- [ ] If current seeded data is too small, new fixture/seed work will be required to prove all-pages facet aggregation honestly.
- [ ] If no existing browser/device spec covers the final Home/Discovery flows, new source-owned runtime specs must be added before delivery can claim closure.

## TODO Closeout Disposition
- **Disposition:** `keep-promotion-lane`
- **Disposition reason:** the hard-cut taxonomy/runtime-facets slice is locally validated with focused Laravel/Flutter evidence, dedicated PACED guardrails, and the authoritative `filters` browser shard rerun on 2026-06-07; the only remaining package-level gate is the consolidated review-branch CI-equivalent matrix before replay/consolidation.
- **Post-commit/push status:** `pending`
- **Next path/status action:** keep this TODO in `foundation_documentation/todos/promotion_lane/v0.2.0+8/`, rerun the full in-scope CI-equivalent matrix from the current review baseline, and reopen this TODO only if that matrix finds a real taxonomy/facet regression.
