# TODO (Store Release): Usability Quality Hardening

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Promotion Lane
**Owners:** Orchestrator, Laravel Team, Flutter Team
**Objective:** Freeze the externally validated Store Release usability behavior with regression/runtime evidence, then refactor the audited implementation to remove promotion-blocking performance, security/integrity, test-quality, and clean-code risks before promotion.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** Triple external audit requested after the Store Release usability recut was manually/runtime validated.

## Framing Source & Story Slice

- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/store-release-usability-quality-hardening.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** The user explicitly separated behavior acceptance from implementation-quality acceptance. This TODO is bounded around quality hardening of the already validated Store Release usability recut, not new product behavior.
- **Direct-to-TODO rationale:** `n/a`

## Contract Boundary

- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: local test additions, backend query-shape refactors, Flutter structure cleanup, and small contract-hardening fixes may stay inside this TODO while they preserve the approved Store Release usability behavior and resolve the same quality gate.
- If execution reveals a new product behavior, new public contract, or independently approvable feature, update or split the TODO before continuing.

## Delivery Status Canon

- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Guard-passed on 2026-04-26 after final no-context triple-audit resolution and verification-matrix reconciliation; only promotion follow-through remains.`
- **Next exact step:** Include this TODO in Store Release promotion orchestration; no implementation work remains unless promotion validation finds a new defect.

## Scope

- [x] Preserve the validated Store Release usability behavior while adding missing regression evidence.
- [x] Fix high-risk admin Event query shape that currently plucks all matching occurrence event IDs before pagination.
- [x] Fix high-risk admin Event formatter N+1 behavior for occurrences and occurrence-owned parties.
- [x] Add/adjust tests proving admin Event occurrence-first list behavior, occurrence-scoped card parties, detail aggregated parties, and programação persistence/readback remain correct.
- [x] Harden Event Type taxonomy integrity so backend Event create/update rejects terms not allowed by the selected Event Type.
- [x] Ensure Event Type `allowed_taxonomies` can only persist taxonomies that exist and apply to `event`.
- [x] Preserve public Discovery/Home filter behavior while proving real click-to-query/result browser paths.
- [x] Reduce discovery filter taxonomy catalog risk by avoiding unbounded eager term payload/rendering where possible without changing approved UX.
- [x] Fix or preserve filter visibility/security rules: Discovery must not expose non-favoritable Account Profile types; public filters must not leak invalid/unavailable types.
- [x] Fix chip accessibility semantics where custom wrappers replaced actionable native semantics.
- [x] Refactor tenant-admin Event form orchestration enough to remove the most fragile screen-owned occurrence/programming mapping hotspots, without changing product behavior.
- [x] Remove or explicitly isolate dead/contradictory occurrence-location override remnants.
- [x] Reclassify declaration-only Playwright matrix checks as metadata evidence, not behavioral proof.
- [ ] Re-run the independent no-context triple audit after fixes, comparing `dev` against the exact branch state frozen at the moment the audit is launched.

## Delivery Status Semantics

- `Pending`: implementation/refactor not yet complete.
- `Local-Implemented`: hardening is implemented and locally validated with required evidence.
- `Lane-Promoted`: merged through `dev`.
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Promotion-Blocked`: current local branch must not be promoted as quality-clean until high/medium findings are resolved or explicitly accepted.

## Blocker Notes

- **Blocker:** Triple audit round 01 found high/medium quality risks.
- **Why blocked now:** External behavior is validated, but internal quality is not clean enough for promotion.
- **What unblocks it:** Resolve high/medium findings with behavior-preserving tests and runtime evidence, then rerun quality gate.
- **Owner / source:** Store Release orchestrator and audit protocol.
- **Last confirmed truth:** Manual/runtime behavior can be treated as product target; refactors must preserve it.

## Audit Round Tracking

| Round | Package | Status | Findings / Adjudication | Resolution State |
| --- | --- | --- | --- | --- |
| `round-01` | `superseded/removed: store-release-usability-quality-final-20260424T180850Z` | `needs_adjudication` | Merge classified a recommended-path conflict because reviewers emphasized different remediation priorities, but the findings are compatible rather than contradictory: untracked deliverable risk, management query-shape risk, eager Flutter row construction, and static-only performance evidence. | Resolved in implementation by adding executable backend instrumentation tests, replacing the management query with a `$facet` page/count aggregate and page-bounded bulk load, removing eager chip prebuild in the horizontal filter row, and preparing the branch for tracked checkpoint before a fresh audit package. Generated package was removed because it captured obsolete credential fallback diffs. |
| `round-02` | `superseded/removed: store-release-usability-quality-final-20260424T184818Z` | `needs_resolution` | Final no-context pass found unresolved risks after the first hardening: taxonomy snapshot backfill materialized full collections, tenant-admin discovery filter term loading reintroduced serial taxonomy requests, Playwright mutation specs contained committed credential fallbacks, and Flutter emitted legacy `selection_mode=multiple`. | Resolved by cursor-based backfill iteration with source guard, batch taxonomy-term loading in tenant-admin filter catalog, canonical `selection_mode=multi` with legacy alias read support, runtime credential-only Playwright auth helper/guard, and removal of exposed credential fallbacks from Playwright/Flutter integration tests and generated audit artifacts. |

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:orchestrator/store-release-usability-wave`, `laravel-app:orchestrator/store-release-usability-wave`, `belluga_now_docker:orchestrator/store-release-usability-wave`, `foundation_documentation:delphi/docs-reconcile-store-release-20260419`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Quality hardening | `pending` | `Not promoted yet` | `Not promoted yet` | `Not promoted yet` | `Pending` |

## Out of Scope

- [ ] New product behavior unrelated to the validated Store Release usability recut.
- [ ] Promoting branches to `dev`, `stage`, or `main`.
- [ ] Reintroducing public Map subfilters.
- [ ] Moving public Home/Discovery filters back to tenant-admin configurable filter settings.
- [ ] Broad deterministic guard redesign beyond tests/refactors needed to close this Store Release quality gate.

## Bounded But Elastic Guardrails

- **May stay inside this TODO:** local refactors, missing regression tests, query-shape changes, DTO/projection adjustments, endpoint validation hardening, Playwright proof additions, and accessibility fixes that preserve approved behavior.
- **Must update or split the TODO:** new feature behavior, new admin IA, new module ownership rule, new public endpoint family unrelated to hardening, or strategy-level deterministic guard program.

## Definition of Done

- [x] `DOD-01` All high audit findings are fixed or backed by explicit load/query-count evidence and accepted residual risk.
- [x] `DOD-02` All medium audit findings are fixed or explicitly accepted with documented residual risk.
- [x] `DOD-03` Admin Event list/query behavior remains occurrence-first and page-bounded under realistic multi-occurrence fixtures.
- [x] `DOD-04` Event formatter no longer performs per-event occurrence queries for a management page.
- [x] `DOD-05` Event Type taxonomy validation is enforced server-side for create/update and cannot be bypassed by direct API payloads.
- [x] `DOD-06` Public Home/Discovery filter runtime evidence proves real UI click selection through backend query/visible result, not only storage restoration.
- [x] `DOD-07` Public filters do not expose non-favoritable Account Profile types or incompatible Event Type taxonomies.
- [x] `DOD-08` User-visible rich text, event occurrence, programação, and profile/card behavior validated in the recut remains green after refactor.
- [x] `DOD-09` Accessibility semantics for interactive filter/taxonomy chips expose an actionable tap path.
- [x] `DOD-10` Dead occurrence-location override remnants are removed or explicitly isolated outside the approved runtime path.
- [x] `DOD-11` Test evidence distinguishes metadata/declaration checks from behavioral navigation proof.
- [x] `DOD-12` A post-fix independent no-context triple audit reports no unresolved high/medium blockers; the audit package must compare `dev` against the exact current branch state at audit launch time.

## Validation Steps

- [x] `VAL-01` Laravel focused tests for Event admin query/list/detail/formatter behavior.
- [x] `VAL-02` Laravel focused tests for Event Type taxonomy persistence and Event create/update validation.
- [x] `VAL-03` Laravel focused tests for public discovery filter catalog/type security and budget behavior.
- [x] `VAL-04` Flutter focused tests for Event admin form, Event Type taxonomy UI, Discovery filter widgets, and chip semantics.
- [x] `VAL-05` Flutter analyzer: `fvm dart analyze --format machine`.
- [x] `VAL-06` Build current web bundle: `bash scripts/build_web.sh ../web-app dev`.
- [x] `VAL-07` Confirm the real browser-facing validation domain serves the refreshed bundle.
- [x] `VAL-08` Playwright mutation tests for Home/Discovery filter click paths and Event admin/public regression paths.
- [x] `VAL-09` Run TODO completion guard with delivery requirement.
- [x] `VAL-10` Rerun independent no-context triple audit using a frozen package generated from `dev...HEAD` at the moment the audit is launched.

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` All high audit findings are fixed or backed by explicit load/query-count evidence and accepted residual risk. | `test+review` | Laravel focused suites + `EventQueryPerformanceGuardrailTest.php`; final triple audit package `foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/package.md` with Round 11 resolution. | `backend+audit` | `passed` | Query-shape all-match pluck and formatter N+1 risks were removed under tests. Round 11 records zero unresolved release blockers after calibrated gate resolution. |
| `DOD-02` | `Definition of Done` | `DOD-02` All medium audit findings are fixed or explicitly accepted with documented residual risk. | `test+runtime+review` | Flutter semantics tests + canonical Playwright mutation runner + `final-round/triple-audit-session/round-11/resolution.md`. | `cross-stack` | `passed` | Runtime proof no longer relies on storage-only selection. Round 11 explicitly classifies remaining elegance/performance observations as accepted non-blocking debt with rationale. |
| `DOD-03` | `Definition of Done` | `DOD-03` Admin Event list/query behavior remains occurrence-first and page-bounded under realistic multi-occurrence fixtures. | `test+Playwright navigation/browser evidence` | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter ...` -> `13 passed (86 assertions)`; later affected suites in final audit package passed up to `339 passed (2037 assertions)`; source-owned Playwright spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js`; canonical runner `tools/flutter/run_web_navigation_smoke.sh mutation` on final tenant domain passed inside the final mutation suite. | `backend + final Web runtime https://guarappari.belluga.space` | `passed` | Includes future/filter and occurrence card/detail behavior plus final browser navigation through the tenant-admin Events list/edit path. |
| `DOD-04` | `Definition of Done` | `DOD-04` Event formatter no longer performs per-event occurrence queries for a management page. | `test+instrumentation guard` | `EventQueryPerformanceGuardrailTest.php` -> `2 passed (14 assertions)` and later final audit package evidence. | `backend` | `passed` | Runtime instrumentation proves one `$facet` management aggregate and one page-bounded bulk occurrence load. |
| `DOD-05` | `Definition of Done` | `DOD-05` Event Type taxonomy validation is enforced server-side for create/update and cannot be bypassed by direct API payloads. | `test+Playwright mutation/navigation` | `EventCrudControllerTest.php`, `EventTypesControllerTest.php`, and affected final-audit Laravel suites; `tools/flutter/web_app_tests/discovery_filters.spec.js` and `tools/flutter/run_web_navigation_smoke.sh mutation` on final tenant domain covered Event Type taxonomy compatibility and admin type-taxonomy preservation. | `backend + final Web runtime https://guarappari.belluga.space` | `passed` | Direct create/update payload with unallowed taxonomy returns `422`; browser mutation validates the compatible Event Type taxonomy path remains usable. |
| `DOD-06` | `Definition of Done` | `DOD-06` Public Home/Discovery filter runtime evidence proves real UI click selection through backend query/visible result, not only storage restoration. | `runtime` | `bash tools/flutter/run_web_navigation_smoke.sh mutation` with final-domain env -> `19 passed (13.3m)`; later final audit mutation shards cover the same paths. | `browser` | `passed` | Real chip clicks assert backend filtered requests/visible results. Mutation run used runtime-only `NAV_ADMIN_*`; committed fallbacks are forbidden. |
| `DOD-07` | `Definition of Done` | `DOD-07` Public filters do not expose non-favoritable Account Profile types or incompatible Event Type taxonomies. | `test+runtime` | Laravel catalog tests -> `2 passed (33 assertions)`; canonical Playwright mutation runner -> `19 passed`; readonly browser suite -> `9 passed`; final audit mutation shards preserve coverage. | `backend+browser` | `passed` | Discovery excludes non-favoritable profile types; Home Event taxonomies follow Event Type; taxonomy labels are validated through Flutter Web semantics/text instead of raw slugs. |
| `DOD-08` | `Definition of Done` | `DOD-08` User-visible rich text, event occurrence, programação, and profile/card behavior validated in the recut remains green after refactor. | `test+runtime` | Focused Laravel, Flutter, analyzer, build, and canonical Playwright evidence in VAL rows and final audit package. | `cross-stack` | `passed` | No product behavior change intended; recut-visible behavior stayed green through final mutation/readonly suites. |
| `DOD-09` | `Definition of Done` | `DOD-09` Accessibility semantics for interactive filter/taxonomy chips expose an actionable tap path. | `test` | `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart` -> `12 passed`; final audit policy rejects coordinate `mouse.click` in release-gating specs. | `local+browser-harness` | `passed` | Taxonomy semantics expose tap action and button flag via `flagsCollection`; Playwright policy ensures tests use semantic/actionable targets. |
| `DOD-10` | `Definition of Done` | `DOD-10` Dead occurrence-location override remnants are removed or explicitly isolated outside the approved runtime path. | `test+code` | Existing occurrence-location negative tests in focused `EventCrud` lane plus SR-D runtime matrix. | `backend+flutter` | `passed` | Runtime authoring path remains programming-item place refs, not occurrence location override. |
| `DOD-11` | `Definition of Done` | `DOD-11` Test evidence distinguishes metadata/declaration checks from behavioral navigation proof. | `doc+runtime browser evidence` | TODO evidence now classifies Playwright runtime separately from JS syntax/source checks; final audit package separates source checks, `bash scripts/build_web.sh ../web-app dev` build proof, served bundle proof, `tools/flutter/web_app_tests/**` specs, and `tools/flutter/run_web_navigation_smoke.sh` browser lanes. | `final Web runtime https://guarappari.belluga.space and https://belluga.space` | `passed` | `node --check` remains supporting evidence only; final acceptance cites Playwright navigation/mutation lanes for visible behavior. |
| `DOD-12` | `Definition of Done` | `DOD-12` A post-fix independent no-context triple audit reports no unresolved high/medium blockers; the audit package must compare `dev` against the exact current branch state at audit launch time. | `review` | `foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/package.md` and `triple-audit-session/round-11/resolution.md`. | `cross-stack` | `passed` | Round 11 records `Open Blockers: none`; `TQ-R11-001` was resolved, and remaining medium observations were accepted as non-blocking debt under the recalibrated gate. |
| `VAL-01` | `Validation Steps` | `VAL-01` Laravel focused tests for Event admin query/list/detail/formatter behavior. | `test+Playwright navigation/browser evidence` | `EventCrudControllerTest.php --filter ...` -> `13 passed (86 assertions)`; final audit package records affected Laravel suites up to `339 passed (2037 assertions)`; source-owned Playwright spec `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` and runner `tools/flutter/run_web_navigation_smoke.sh mutation` validated the final tenant-admin/public Event paths on the served bundle. | `backend + final Web runtime https://guarappari.belluga.space` | `passed` | Safe runner, sequential execution, and browser coverage for the Event admin/public flow that consumes the query/list/detail behavior. |
| `VAL-02` | `Validation Steps` | `VAL-02` Laravel focused tests for Event Type taxonomy persistence and Event create/update validation. | `test+Playwright mutation/navigation` | `EventTypesControllerTest.php` + `EventQueryPerformanceGuardrailTest.php` -> `17 passed (103 assertions)` and final affected Laravel suites; `tools/flutter/web_app_tests/discovery_filters.spec.js` through `tools/flutter/run_web_navigation_smoke.sh mutation` validated Event Type taxonomy compatibility on the final tenant domain. | `backend + final Web runtime https://guarappari.belluga.space` | `passed` | Includes allowed taxonomy persistence, invalid taxonomy rejection, executable query-shape guard, and final browser mutation through the compatible taxonomy path. |
| `VAL-03` | `Validation Steps` | `VAL-03` Laravel focused tests for public discovery filter catalog/type security and budget behavior. | `test+Playwright navigation/browser evidence` | `MapPoisControllerTest.php` focused discovery/home/account-profile catalog filters -> `2 passed (33 assertions)`; `tools/flutter/web_app_tests/discovery_filters.spec.js` and `tools/flutter/run_web_navigation_smoke.sh mutation` validated public Home/Discovery filter click paths against backend-filtered requests. | `backend + final Web runtime https://guarappari.belluga.space` | `passed` | Catalog/security/budget plus browser validation of the public flows that consume the catalogs. |
| `VAL-04` | `Validation Steps` | `VAL-04` Flutter focused tests for Event admin form, Event Type taxonomy UI, Discovery filter widgets, and chip semantics. | `test+Playwright navigation/browser evidence` | `fvm flutter test packages/belluga_discovery_filters/test/discovery_filter_core_test.dart packages/belluga_discovery_filters/test/discovery_filter_bar_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart` -> `98 passed`; final audit package records later affected Flutter suites; `tools/flutter/web_app_tests/discovery_filters.spec.js` and `tools/flutter/run_web_navigation_smoke.sh mutation` validate browser interaction. | `Flutter host + final Web runtime https://guarappari.belluga.space` | `passed` | Widget/domain/repository/semantics coverage plus final browser interaction coverage. |
| `VAL-05` | `Validation Steps` | `VAL-05` Flutter analyzer: `fvm dart analyze --format machine`. | `test` | `fvm dart analyze --format machine` -> exit `0` in the final audit evidence. | `local` | `passed` | Official analyzer gate clean after removing credential fallbacks from integration tests. |
| `VAL-06` | `Validation Steps` | `VAL-06` Build current web bundle: `bash scripts/build_web.sh ../web-app dev`. | `runtime` | `bash scripts/build_web.sh ../web-app dev` -> passed; final audit package records build/publish proof across rounds. | `local->web-app` | `passed` | Built current runtime bundle before Playwright final-domain validation. |
| `VAL-07` | `Validation Steps` | `VAL-07` Confirm the real browser-facing validation domain serves the refreshed bundle. | `runtime` | Local/remote `main.dart.js` hashes recorded in the final audit package, including Round 10/11 bundle freshness. | `browser` | `passed` | `https://guarappari.belluga.space` and `https://belluga.space` served refreshed bundle hashes during validation. |
| `VAL-08` | `Validation Steps` | `VAL-08` Playwright mutation tests for Home/Discovery filter click paths and Event admin/public regression paths. | `runtime Playwright mutation/navigation` | Playwright source-owned specs under `tools/flutter/web_app_tests/**`; build proof `bash scripts/build_web.sh ../web-app dev`; served bundle proof recorded in final audit package with `__WEB_BUILD_SHA__`; `NAV_WEB_TEST_TYPE=mutation` with ephemeral `NAV_ADMIN_*` and canonical runner `tools/flutter/run_web_navigation_smoke.sh mutation` -> `19 passed (13.3m)`; final audit shards report full mutation coverage. | `browser final Web runtime https://guarappari.belluga.space` | `passed` | Covers rich text, Map baseline, Home/Discovery filters, occurrence FAB/persistence, repeated public hydration, type taxonomy preload, tenant agenda UI, account profile detail back stack, and taxonomy display snapshots through the mutation gate. |
| `VAL-09` | `Validation Steps` | `VAL-09` Run TODO completion guard with delivery requirement. | `test` | `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-usability-quality-hardening.md --require-delivery` final reconciliation pass on 2026-04-26 returned `Overall outcome: go`. | `local deterministic guard` | `passed` | Guard was rerun after the evidence matrix quoted each DoD/Validation criterion exactly and connected flow-impacting rows to Playwright/browser evidence. |
| `VAL-10` | `Validation Steps` | `VAL-10` Rerun independent no-context triple audit using a frozen package generated from `dev...HEAD` at the moment the audit is launched. | `review` | `foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/package.md`; `triple-audit-session/round-11/resolution.md`. | `cross-stack` | `passed` | Final audit cycle closed with no unresolved release blocker after `TQ-R11-001` was resolved and non-blocking debt was explicitly accepted. |

## External Dependency Readiness

| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| Local Laravel Docker test runner | Backend tests must run through safe local tenant Mongo topology. | `healthy` | `2026-04-24` | focused safe-runner execution | Safe-runners must run sequentially; parallel executions race on local Mongo database reset. |
| Browser validation domains | Playwright must target refreshed final domain bundle. | `healthy` | `2026-04-24` | build hash + curl | Built with `scripts/build_web.sh`; remote `main.dart.js` hash matches local output. |
| Android/ADB lane | Shared Flutter behavior can close with Playwright when not Android-divergent. | `unknown` | `n/a` | `n/a` | Use only if a divergent Android behavior appears. |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`, `assurance-security-adversarial`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Final test-quality gate after hardening. | Flutter/Laravel/Web tests | `planned` |
| `operational-coder` | `assurance-security-adversarial` | Security/integrity review of public filters, taxonomy validation, rich text, and scheduler/query surfaces. | Laravel endpoints/services | `planned` |

## Complexity

- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `consolidated checkpoint after green focused backend/Flutter tests; final checkpoint after Web build/Playwright/audit`
- **Why this level:** The TODO crosses Laravel packages/controllers, Flutter package/UI/domain code, runtime browser tests, and quality-audit closure.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Planned decision promotion targets (module sections):**
  - Events module: admin list/query, occurrence read models, event type taxonomy validation.
  - Flutter client module: runtime evidence and source-owned web tests.
  - Tenant admin module: Event form authoring structure and validation.
- **Module decision consolidation targets:**
  - Events module API/contracts and event type taxonomy invariants.
  - Flutter client experience validation/evidence notes if stable runtime testing contract changed.

## Decision Pending

- [x] `D-01` Whether to preserve current validated behavior or use hardening to alter product UX.
- [x] `D-02` Whether Playwright storage seeding can remain the primary proof for Home/Discovery filters.
- [x] `D-03` Whether high performance findings can be waived without query/load evidence.

## Decisions

- [x] `D-01` Preserve current validated behavior; hardening must not introduce unrelated product changes.
- [x] `D-02` Playwright storage seeding is supporting evidence only. Final filter proof requires real UI click path through backend query or visible filtered result.
- [x] `D-03` High performance findings are promotion blockers unless fixed or backed by explicit query/load evidence and accepted residual risk.

## Module Decision Baseline Snapshot

| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `events_module` | Events have occurrence-first public/admin list concerns and occurrence-exclusive programação. | `Preserve` | Active multi-occurrence TODO and current code. |
| `account_profile_catalog_module` | Discovery public filters must respect favoritable/public capabilities. | `Preserve` | Existing discovery/account-profile query behavior. |
| `flutter_client_experience_module` | Web browser evidence belongs in source-owned Playwright specs, not generated `web-app`. | `Preserve` | Project constitution and scope policy. |

## Decision Baseline

- [x] `D-01` Preserve approved Store Release usability behavior while changing internals.
- [x] `D-02` Backend query/list refactors must be page-bounded and avoid broad full-scan/in-memory pagination.
- [x] `D-03` Event Type taxonomy restrictions are enforced server-side and mirrored in UI.
- [x] `D-04` Browser-visible filters require Playwright click-path evidence after current bundle build.
- [x] `D-05` User-visible behavior can close on one runtime lane only when Android/Web are not divergent.

## Questions To Close

- [x] No open product decisions. Remaining details are implementation/test decisions inside the approved plan.

## Package-First Assessment

- **Queries executed:**
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "events"`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "discovery filters"`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "taxonomy"`
- **Relevant packages found:** deterministic registry query returned `0 package(s) found)` for each search.
- **READMEs read:** none from query results.
- **Decision:** Implement within existing local app/package surfaces already owning the code (`belluga_events` Laravel package and Flutter `belluga_discovery_filters` local package where applicable); do not create a new package.
- **Tier:** Local implementation / existing local package boundaries.
- **Rationale:** This is a hardening/refactor of existing Store Release surfaces, not a new reusable capability.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Admin Event list performance can be improved without changing visible list behavior. | Audit finding targets query shape, not product behavior. | Would require product decision. | `High` | `Keep as Assumption` |
| `A-02` | Event Type taxonomy validation can be enforced in backend without changing approved UI. | Current UI already filters by Event Type; missing risk is direct API bypass. | UI or data migration may need adjustment. | `High` | `Keep as Assumption` |
| `A-03` | Home/Discovery filter click behavior is shared Flutter code across Web/Android. | Current Flutter screens/packages are shared; no Android-specific filter implementation identified. | ADB integration may be required. | `Medium` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces

- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `laravel-app/app/Application/Taxonomies/TaxonomyValidationService.php`
- `laravel-app/app/Application/Events/EventTypeRegistryManagementService.php`
- `laravel-app/app/Http/Api/v1/Controllers/DiscoveryFiltersController.php`
- `laravel-app/app/Integration/DiscoveryFilters/**`
- `flutter-app/packages/belluga_discovery_filters/**`
- `flutter-app/lib/presentation/tenant_admin/events/**`
- `flutter-app/lib/presentation/tenant_public/**`
- `belluga_now_docker/tools/flutter/web_app_tests/**`

### Ordered Steps

1. Add regression tests for backend query semantics, N+1 prevention, Event Type taxonomy validation, and filter catalog security.
2. Refactor backend Event admin list/read model to bulk-load occurrences and avoid all-match `pluck` before pagination.
3. Refactor backend formatter to accept preloaded occurrence context and eliminate per-row occurrence queries.
4. Enforce Event Type taxonomy validation in backend Event create/update and Event Type registry persistence.
5. Adjust discovery filter catalog behavior/budget only as needed to preserve approved UX and remove eager/unbounded risk.
6. Add/adjust Flutter tests for chip semantics and filter click state.
7. Add Playwright click-path tests for Home/Discovery filters and preserve Event/admin runtime regressions.
8. Run focused validation, build web, verify served bundle, run Playwright, then rerun quality audit/gate.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** The behavior is already externally validated; tests must freeze behavior before structural refactor.
- **Fail-first target(s):**
  - Laravel query-count/page-bounded tests fail on current Event admin query shape.
  - Laravel Event create/update direct payload test fails when taxonomy term is not allowed by selected Event Type.
  - Playwright filter click-path evidence fails if UI click does not propagate to backend query/result.
  - Flutter semantics tests fail where replacement chip semantics lack an actionable tap.

### Flow Evidence Planning Matrix

| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Event admin occurrence list/query | List/detail behavior feeds admin UI. | `shared-android-web` | `Playwright mutation` | `yes` | `yes` | Admin Events mutation/navigation spec. | `n/a` |
| Event formatter parties/programming | Payload feeds public cards/details. | `shared-android-web` | `Playwright readonly/mutation` | `no` unless seeded in spec | `yes` | Existing/new event occurrence spec. | `n/a` |
| Event Type taxonomy validation | CRUD/save behavior. | `shared-android-web` | `Playwright mutation` | `yes` | `yes` | Admin Event/Event Type spec plus Laravel feature test. | `n/a` |
| Home/Discovery filter click path | Interactive public filtering. | `shared-android-web` | `Playwright readonly` | `no` | `yes` | Discovery/Home filters spec with real clicks and request/result assertion. | `n/a` |
| Chip semantics | Accessibility/actionability visible through widget semantics. | `shared-android-web` | `n/a` | `no` | `no` | Flutter semantics widget tests. | Semantics are best proven at widget semantics tree layer. |
| Backend query-shape performance | Non-visual but affects list runtime. | `n/a` | `n/a` | `no` | `yes` | Laravel query-count/instrumentation tests. | Runtime browser cannot reliably prove query shape. |

### Runtime / Rollout Notes

- No data migration expected.
- No production promotion in this TODO.
- If a query refactor requires new indexes, record and add them before final validation.

## Plan Review Gate

### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards

- **Issue ID:** `PERF-01`
  - **Severity:** `high`
  - **Evidence:** Triple audit `PERF-001`, `PERF-002`.
  - **Why it matters now:** Admin Event pages can degrade with tenant data volume.
  - **Option A (Recommended):** Bulk/page-bound occurrence query and formatter context.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Keep current code and add load evidence waiver.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Not acceptable for promotion.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `regresses`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** Option A.

- **Issue ID:** `SEC-01`
  - **Severity:** `medium`
  - **Evidence:** Direct API can currently rely on `applies_to=event` without selected Event Type restriction.
  - **Why it matters now:** UI hiding is insufficient; backend must enforce taxonomy integrity.
  - **Option A (Recommended):** Validate selected Event Type `allowed_taxonomies` in Event write path and validate Event Type persistence against existing `applies_to=event` taxonomies.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** UI-only filtering.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Not acceptable.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** Option A.

### Failure Modes & Edge Cases

- [x] Refactor changes list ordering or occurrence alias behavior. Mitigation: Laravel + Playwright occurrence list/detail regression tests.
- [x] Backend rejects legacy events with empty `allowed_taxonomies`. Mitigation: allow empty taxonomy terms; reject only non-empty unallowed terms.
- [x] Filter lazy/budget changes hide required taxonomies. Mitigation: tests for 0/1/3 taxonomies and selected primary behavior.
- [x] Query-count tests become brittle. Mitigation: assert bounded shape through repository/service instrumentation where possible, not exact unrelated query totals.

### Residual Unknowns / Risks

- [x] Browser tests depend on final-domain served bundle freshness; evidence must include build/hash confirmation.

## Additional Architectural Opinions

- **Needed:** `no`
- **Why ambiguity remains:** The triple audit already provided independent architecture/performance/test-quality opinions and the user approved the plan.
- **Opinion count:** `0`
- **Package mode:** `n/a`
- **Subagent mandate:** `no`
- **Required lenses:** `n/a`

## Approval

- **Status:** `APROVADO`
- **Source:** User approved the detailed two-level hardening plan in-session on 2026-04-24 with “Perfeito. Pode seguir com o plano.”
