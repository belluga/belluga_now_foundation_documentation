# TODO (Store Release): Usability Wave Bug Convergence Recut

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Promotion Lane
**Owners:** Orchestrator, Laravel Team, Flutter Team
**Objective:** Reopen the Store Release usability wave after manual validation found visible bugs in delivered areas, classify correlated invisible-risk surfaces, fix the bugs through their owning TODOs, and recut item-specific runtime evidence before any renewed delivery claim.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** Manual validation session on 2026-04-24 after the previous Store Release usability orchestration was claimed complete.
- **Dependency role:** Meta blocker. This TODO blocks renewed delivery claims for:
  - `TODO-store-release-typed-discovery-filters-package.md`
  - `TODO-store-release-account-profile-rich-text-fidelity.md`
  - `TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md`

## Delivery Status Canon

- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Guard-passed on 2026-04-26 after recut evidence, verification-debt audit, and affected owning TODO guard reruns; only promotion follow-through remains.`
- **Next exact step:** Include this TODO in Store Release promotion orchestration; no implementation work remains unless promotion validation finds a new defect.

## Current Bug Intake

| Bug ID | Owning TODO | Surface | Manual finding | Required product behavior | Invisible-risk cluster |
| --- | --- | --- | --- | --- | --- |
| `BUG-SR-RECUT-01` | `TODO-store-release-typed-discovery-filters-package.md` | Tenant-admin Event edit form and Event discovery-filter provider | The Event edit taxonomy section appears to show taxonomy group titles even when those taxonomies are not allowed by the selected Event Type. Non-visible review also found the Event discovery-filter entity provider still deriving every Event Type's allowed taxonomy list from global `applies_to=event`. | Event create/edit taxonomy UI must render only taxonomies listed in the selected Event Type `allowed_taxonomies`; unallowed groups and their terms must be hidden and must not be submitted. Public Event filter catalogs/providers must expose per-Event-Type `allowed_taxonomies`, not global Event taxonomy inheritance. | Type-scoped taxonomy filtering, stale selected taxonomy repair, create/edit parity, type-change cleanup, Event Type admin persistence, backend catalog/provider parity. |
| `BUG-SR-RECUT-02` | `TODO-store-release-account-profile-rich-text-fidelity.md` | Tenant-admin Event description editor | The Event description field can still hit backend `422 validation.max.string`, and the UI does not provide adequate visible size/limit guidance before submit. | Event `content` must share the approved long-form rich-text contract: 100KB backend limit, visible guidance/counter, faithful admin edit/readback, and public `Sobre` rendering. | Field-specific backend constraints, frontend counter/guidance, sanitized-size vs raw-size mismatch, create/edit/reopen/public parity. |
| `BUG-SR-RECUT-03` | `TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` | Tenant-admin Event edit form, single occurrence | A single-occurrence event has programação persisted and visible publicly, but the root-level `Programação` editor opens empty on edit. | When an event has exactly one occurrence, the root-level admin programação editor must hydrate from the first occurrence's programação; after adding a second occurrence, this root section must disappear and programming must be managed only through occurrence editors. | Single-to-multi occurrence transition, first-occurrence hydration, update draft preservation, create/edit parity, reload/reopen persistence. |
| `BUG-SR-RECUT-04` | `TODO-store-release-account-profile-rich-text-fidelity.md` | Tenant-admin Static Asset bio/content | Non-visible contract review found Static Asset rich-text fields still used the old 1000-character backend constraint while the UI was moving to the 100KB guidance contract. | Static Asset `bio` and `content` must share the same sanitized 100KB rich-text contract and visible counter/guidance as Account Profile and Event. | Backend constraint parity, sanitizer reuse, UI/backend contract mismatch, static asset create/edit parity. |

## Recut Rules

- Every bug fix must live in the owning TODO, with this meta TODO tracking convergence only.
- Each bug must have positive and negative evidence. A fix that proves only the happy path is not enough.
- User-visible/admin-visible behavior requires final browser navigation evidence after the current web bundle is built and served on the validation domain, or an explicit approved non-applicability rationale.
- CRUD/edit bugs require mutation evidence that performs the local mutation path on the approved non-main validation lane.
- If a bug exposes a broader cluster, add tests for the correlated invisible-risk cases before renewing the delivery claim.

## Required Validation Matrix

| Case ID | Bug | Required evidence |
| --- | --- | --- |
| `RECUT-FILTER-01` | `BUG-SR-RECUT-01` | Admin Event create/edit with Event Type A that allows taxonomy X: taxonomy X title and terms are visible and selectable. |
| `RECUT-FILTER-02` | `BUG-SR-RECUT-01` | Admin Event create/edit with Event Type B that does not allow taxonomy X: taxonomy X title and terms are not visible. |
| `RECUT-FILTER-03` | `BUG-SR-RECUT-01` | Changing Event Type from one with taxonomy X to one without taxonomy X clears/repairs stale selected terms before submit. |
| `RECUT-FILTER-04` | `BUG-SR-RECUT-01` | Saving unrelated Event fields must not submit hidden/unallowed taxonomy terms. |
| `RECUT-FILTER-05` | `BUG-SR-RECUT-01` | Backend Event discovery-filter provider and public catalogs expose `allowed_taxonomies` per Event Type and return taxonomy options only from selected/compatible Event Types, never from every taxonomy with `applies_to=event`. |
| `RECUT-FILTER-06` | `BUG-SR-RECUT-01` | Admin Event edit with selected Event Type whose `allowed_taxonomies=[]` must not render the `Taxonomias` section at all and must submit no stale taxonomy terms. |
| `RECUT-FILTER-07` | `BUG-SR-RECUT-01` | Admin Event create/edit must load taxonomy terms for the selected Event Type through one batch endpoint request serialized as an array query (`taxonomy_ids[]`); it must not loop per taxonomy, must fetch nothing when the selected type allows zero taxonomies, must reload the compatible term set when the Event Type changes, and must not render empty taxonomy group titles when no term chips are available. |
| `RECUT-RICH-01` | `BUG-SR-RECUT-02` | Event description near the approved long-form limit shows visible size guidance/counter before submit. |
| `RECUT-RICH-02` | `BUG-SR-RECUT-02` | Event description under the approved limit saves, reopens, and renders on public `Sobre` with supported rich-text structure intact. |
| `RECUT-RICH-03` | `BUG-SR-RECUT-02` | Event description over the approved limit is prevented with clear UI/validation feedback, not a surprising generic backend `max.string` failure. |
| `RECUT-RICH-04` | `BUG-SR-RECUT-02` | Account Profile `bio` and `content` remain on the same long-form guidance/limit contract after Event fixes. |
| `RECUT-RICH-05` | `BUG-SR-RECUT-04` | Static Asset `bio` and `content` show 100KB guidance/counter in admin edit and backend accepts/sanitizes content at 100KB while rejecting over-limit content. |
| `RECUT-EVENT-01` | `BUG-SR-RECUT-03` | Existing single-occurrence Event with persisted programação reopens with root-level `Programação` populated from the first occurrence. |
| `RECUT-EVENT-02` | `BUG-SR-RECUT-03` | Editing root-level programação for a single-occurrence Event saves, reloads, and remains visible publicly. |
| `RECUT-EVENT-03` | `BUG-SR-RECUT-03` | After adding a second occurrence, the root-level `Programação` section is not visible and the original programação is available inside the first occurrence editor. |
| `RECUT-EVENT-04` | `BUG-SR-RECUT-03` | Multi-occurrence Event edit never shows root-level `Programação`; each occurrence editor owns its programação independently. |

## Definition of Done

- [x] All recut bugs are recorded in their owning TODOs with the same bug IDs.
- [x] The owning TODOs no longer claim final delivery while their bug rows are open.
- [x] Each bug has fail-first or regression tests at the lowest practical layer.
- [x] Each user-visible/admin-visible bug has final navigation/browser evidence for the exact behavior.
- [x] Correlated invisible-risk cases in the validation matrix are covered, with explicit rationale for any non-applicable item.
- [x] `todo_completion_guard.py --require-delivery` returns `go` for this TODO and each affected owning TODO after the recut evidence is added.

## Validation Steps

- [x] Run focused Laravel/Flutter tests for the changed contracts.
  - `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed.
  - `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --plain-name 'taxonomy'` passed.
  - `fvm flutter test test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart` passed.
  - `fvm flutter test test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart` passed.
  - `fvm flutter test test/presentation/tenant_admin/static_assets/tenant_admin_static_asset_edit_screen_test.dart` passed.
  - `../delphi-ai/scripts/laravel/run_laravel_tests_safe.sh tests/Feature/Taxonomies/TaxonomyRegistryControllerTest.php` passed.
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php --filter 'discovery_filter_registry_resolves_first_slice_entity_providers|discovery_filters_public_catalog_returns_surface_filters_and_type_options|home_events_catalog_derives_type_filters_and_full_taxonomy_options_without_admin_settings'` passed.
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter 'event_content_limit_is_100kb_after_sanitization|management_show_preserves_single_occurrence_programming_and_occurrence_profiles|event_update_multipart_preserves_occurrence_owned_profiles_and_programming'` passed.
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/StaticAssets/StaticAssetsControllerTest.php --filter 'static_asset_rich_text_limit_is_100kb_and_sanitized_per_field'` passed.
  - 2026-04-24 additional proof after manual screenshot: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed `26 tests`; added regression coverage for hiding allowed taxonomy groups that have no terms. `fvm flutter test test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart` passed and asserts the batch request uses Dio `ListFormat.multiCompatible` for Laravel-compatible array query serialization.
- [x] Build and serve the current Web bundle with `scripts/build_web.sh ../web-app dev`.
  - `bash scripts/build_web.sh ../web-app dev` passed on 2026-04-24.
  - `sha256sum ../web-app/main.dart.js` and `curl -k -L 'https://guarappari.belluga.space/main.dart.js?cachebust=taxonomy-batch-20260424' | sha256sum` both returned `6575c73f70abcb1daf81c74f8389114752292f178e851b5ec205c10dc9e169f8`.
  - Additional build after empty-group/query-serialization fix: `bash scripts/build_web.sh ../web-app dev` passed; local and served hashes both returned `f391922b121a498d1d7bfc1d72a275298844a16a04dec3e2cdc930b970c82991`.
- [x] Run Playwright mutation specs for admin edit flows and public readback on the approved non-main validation domain.
  - Focused runtime support for Event Type taxonomy scoping: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_TEST_TYPE=mutation NAV_WEB_WORKERS=1 ... npx playwright test --config ./playwright.config.js discovery_filters.spec.js --grep "Home filters honor Event Type taxonomy compatibility" --workers 1 --reporter=line --output ./test-results` passed `1 passed (45.6s)`.
- [x] Re-run verification debt audit on the affected TODOs before any renewed delivery claim.

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | Definition of Done | All recut bugs are recorded in their owning TODOs with the same bug IDs. | Documentation evidence | This TODO records `BUG-SR-RECUT-01..04`; owning TODOs record the same IDs: `TODO-store-release-typed-discovery-filters-package.md` (`BUG-SR-RECUT-01`), `TODO-store-release-account-profile-rich-text-fidelity.md` (`BUG-SR-RECUT-02`, `BUG-SR-RECUT-04`), and `TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` (`BUG-SR-RECUT-03`). | Foundation docs | passed | Cross-reference integrity is explicit in the owning TODO active-constraint sections. |
| `DOD-02` | Definition of Done | The owning TODOs no longer claim final delivery while their bug rows are open. | Documentation evidence + guard state | The owning TODOs were kept in `active/store_release_android` during recut and each now passes `todo_completion_guard.py --require-delivery` only after the bug evidence rows were added. | Foundation docs + guard | passed | No owning TODO was moved to `promotion_lane` while its recut bug row remained open. |
| `DOD-03` | Definition of Done | Each bug has fail-first or regression tests at the lowest practical layer. | Automated tests | `BUG-SR-RECUT-01`: Flutter event-form tests, taxonomy batch repository tests, Laravel taxonomy registry and public catalog/provider tests. `BUG-SR-RECUT-02`: Flutter rich-text editor/Event form tests and Laravel Event 100KB validation tests. `BUG-SR-RECUT-03`: Flutter event-form/controller tests and Laravel management payload preservation tests. `BUG-SR-RECUT-04`: Flutter Static Asset edit tests and Laravel Static Asset 100KB validation test. | Flutter host + Laravel Docker | passed | Focused commands are listed in this TODO's Validation Steps and in the owning TODO evidence sections. |
| `DOD-04` | Definition of Done | Each user-visible/admin-visible bug has final navigation/browser evidence for the exact behavior. | Playwright navigation/browser evidence | Build proof `bash scripts/build_web.sh ../web-app dev`; served bundle proof recorded in the final audit package with `__WEB_BUILD_SHA__`; Event Type taxonomy compatibility passed focused Playwright mutation `1 passed (45.6s)` through `tools/flutter/web_app_tests/discovery_filters.spec.js`; final quality-hardening mutation lanes covered Home/Discovery filters, Event rich text, tenant-admin event type taxonomy preservation, occurrence FAB/programming authoring, and admin/media/type taxonomy shard coverage (`19 passed (13.3m)` and final shard evidence in `foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/package.md`) through `tools/flutter/run_web_navigation_smoke.sh mutation`. | Final Web browser runtime `https://guarappari.belluga.space` | passed | Shared Flutter behavior has no documented Android/Web divergence for these recut bugs; Playwright final-domain mutation evidence is accepted runtime proof. |
| `DOD-05` | Definition of Done | Correlated invisible-risk cases in the validation matrix are covered, with explicit rationale for any non-applicable item. | Automated tests + Playwright navigation/browser evidence + review evidence | Taxonomy batch endpoint avoids per-taxonomy request loops; Event Type catalog/provider scopes by per-type `allowed_taxonomies`; hidden/unallowed terms are filtered from submit payloads; Event and Static Asset rich-text backend caps enforce 100KB post-sanitization; management Event payload preserves first occurrence programming/profiles; final Playwright mutation lanes under `tools/flutter/web_app_tests/**` and `tools/flutter/run_web_navigation_smoke.sh mutation` exercised the visible consumer paths. | Laravel Docker + Flutter host + final Web runtime `https://guarappari.belluga.space` | passed | Invisible risks were tested at repository/API layers and correlated with browser-visible paths where the risk feeds UI behavior. No non-applicable item remains in this recut matrix. |
| `DOD-06` | Definition of Done | `todo_completion_guard.py --require-delivery` returns `go` for this TODO and each affected owning TODO after the recut evidence is added. | Deterministic guard | Owning TODO guard reruns on 2026-04-26 returned `Overall outcome: go` for Account Profile rich text, Typed Discovery Filters, and Event Multi Occurrence. Final recut command `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-usability-bug-convergence-recut.md --require-delivery` returned `Overall outcome: go`. | Local deterministic guard | passed | Guard proof is recorded after adding criterion-specific evidence rows for every DoD and Validation item. |
| `VAL-01` | Validation Steps | Run focused Laravel/Flutter tests for the changed contracts. | Automated tests | Commands listed above in Validation Steps: Flutter Event form/taxonomy/editor/static-asset tests; Laravel TaxonomyRegistry, MapPois catalog/provider, EventCrud, and StaticAssets controller tests. | Flutter host + Laravel Docker | passed | Includes the 2026-04-24 additional `26 tests` Event form rerun and Laravel-compatible `taxonomy_ids[]` serialization coverage. |
| `VAL-02` | Validation Steps | Build and serve the current Web bundle with `scripts/build_web.sh ../web-app dev`. | Web build + served-bundle proof | `bash scripts/build_web.sh ../web-app dev` passed; local and served `main.dart.js` hashes matched `6575c73f70abcb1daf81c74f8389114752292f178e851b5ec205c10dc9e169f8`, then after additional fix matched `f391922b121a498d1d7bfc1d72a275298844a16a04dec3e2cdc930b970c82991`. | Local build + final Web runtime | passed | Build/publish evidence precedes runtime Playwright validation. |
| `VAL-03` | Validation Steps | Run Playwright mutation specs for admin edit flows and public readback on the approved non-main validation domain. | Playwright mutation/navigation | Focused Event Type taxonomy scoping Playwright mutation passed `1 passed (45.6s)` through `tools/flutter/web_app_tests/discovery_filters.spec.js`; final quality-hardening runtime package records mutation lanes through `tools/flutter/run_web_navigation_smoke.sh mutation`, including Home/Discovery filters, Event rich text, occurrence/programming, admin type-taxonomy, and media/admin shards. | Final Web runtime `https://guarappari.belluga.space` on dev lane | passed | Runtime lane uses approved non-main validation target and real backend mutation/readback where relevant. |
| `VAL-04` | Validation Steps | Re-run verification debt audit on the affected TODOs before any renewed delivery claim. | Verification debt audit | `bash delphi-ai/tools/verification_debt_audit.sh --todo foundation_documentation/todos/active/store_release_android/TODO-store-release-usability-bug-convergence-recut.md` executed during 2026-04-26 reconciliation and returned `Outcome heuristic: none`; inline code TODO debt classification `none`. | Local deterministic helper | passed | The audit was rerun before movement; no actionable hidden verification debt was found in the recut TODO after matrix reconciliation. |

## Recut Implementation Notes

- `BUG-SR-RECUT-01`: Flutter admin Event form now filters taxonomy groups by the selected Event Type `allowed_taxonomies`, prevents disallowed chip toggles, sanitizes stale selections when the selected type is known, filters submit payloads defensively, and hides the taxonomy section entirely for Event Types with no allowed taxonomies. Taxonomy term loading now uses the Laravel batch endpoint (`GET /taxonomies/terms`) for the selected Event Type's allowed taxonomy IDs, does not fetch terms for zero-allowed types, and reloads compatible terms when the selected Event Type changes. Laravel Event discovery-filter provider now matches Account Profile and Static Asset providers by exposing per-type `allowed_taxonomies` and resolving taxonomy options from selected Event Types instead of global `applies_to=event`.
- `BUG-SR-RECUT-02`: Event rich-text editor now shows the 100KB guidance/counter and Laravel Event write validation accepts the 100KB contract instead of the old description limit, with post-sanitization enforcement.
- `BUG-SR-RECUT-03`: Laravel management Event payload now preserves the resolved occurrence document even when there is exactly one occurrence, preventing `programming_items` and occurrence-owned profiles from being replaced by a root synthetic occurrence.
- `BUG-SR-RECUT-04`: Static Asset `bio/content` now uses the same Flutter guidance and backend sanitizer/100KB constraint as the rest of the tenant-admin long-form rich-text surfaces.
