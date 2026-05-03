# Store Release Agenda Card Polish And Occurrence Taxonomy Orchestration Plan

## Artifact Identity
- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Approved`
- **Execution state:** `T5-MAP-LIVE-NOW Addendum Local-Validated`
- **Created:** `2026-05-01`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`
- **Approval evidence:** user replied "Perfeito. Aprovado dessa forma." on 2026-05-01 after confirming the compression candidates, `Confirmados` attendance semantics, and full `às` time-range coverage.
- **Approved scope expansion:** user added the Boora icon font replacement and full icon-picker catalog coverage on 2026-05-01 and explicitly asked to add it to the same TODO and continue orchestration. User also added the web Map full-width exception during execution and explicitly asked not to stop. During final review, user clarified that occurrence taxonomy-by-occurrence must include the taxonomy field in the tenant-admin occurrence UI.
- **Reopened addendum approval:** on 2026-05-01, QA reported Map event markers not showing happening-now state and timing badges wrapping vertically. Runtime validation for this addendum must create controlled data in the DEV environment only, not production. User approved the addendum with `APROVADO`; fail-first Laravel/Flutter tests, focused validation, and controlled DEV seeded runtime evidence are recorded.

## Authority Boundary
- The governing TODO defines **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the work will be sequenced, tested, audited, reconciled, and consolidated with the active Store Release plans.
- If this plan conflicts with the governing TODO, stop and update the TODO or this plan before execution.
- This plan does not reopen unrelated social, invite, favorite, deep-link, OTP, metrics, or contact matching implementation.
- Requirement wording in the governing TODO is literal. Replacing a named UI control, label, API field, validation lane, or runtime target requires an approved row in the Spec Deviation Ledger before execution or delivery can proceed.
- Workers own implementation slices after approval. The orchestrator owns preflight, dispatch, reconciliation, final validation, and evidence consolidation.

## Governing TODO Set
| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `T5-AGENDA-POLISH` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-agenda-card-polish-and-occurrence-taxonomy-overrides.md` | Store Release slice for agenda/event card polish, programming item end time, occurrence taxonomy override/filtering, Boora icon font/catalog, Map web full-width, and reopened Map live-now/timing-badge validation. | Original scope approved on 2026-05-01; Map live-now/timing-badge addendum approved with `APROVADO` and locally validated. |

## Consolidation Boundary With Existing Plans
| Plan | Current Role | This Plan's Relationship |
| --- | --- | --- |
| `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md` | Existing Wave 2 social/invite/favorites occurrence plan. | Consume current reconciled branch state; final delivery claim must not regress W2 guards. |
| `foundation_documentation/artifacts/execution-plans/store-release-four-todos-orchestration-plan.md` | Existing T1/T2/T3/T4 Store Release completion plan, with contact materialization still blocking delivery readiness. | Final consolidation must run alongside its unresolved rows; this plan does not claim to close T3 contact materialization. |
| `foundation_documentation/artifacts/execution-plans/store-release-agenda-card-polish-occurrence-taxonomy-orchestration-plan.md` | New T5 plan. | Adds the third active orchestration package requested by the user for this round. |

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `T5-DOD-01` | TODO :: Definition of Done :: All acceptance criteria have concrete evidence in the Completion Evidence Matrix. | Reconciliation/evidence worker | Completion Evidence Matrix rows | Filled in TODO after implementation. | TODO completion guard, orchestration plan completion guard, and orchestration delivery guard passed after the Map live-now addendum evidence update. | Runtime evidence is represented in the matrix rows that require it, including the controlled DEV map API route evidence in `foundation_documentation/artifacts/tmp/store-release-map-live-now-marker-evidence-20260501.md`. | `passed` |
| `T5-DOD-02` | TODO :: Definition of Done :: Focused Flutter tests pass for card compression, overflow, Home Agenda chrome, time labels, programming display, and taxonomy consumers. | Flutter validation worker | card compression, overflow, Home Agenda chrome, time labels, programming display, taxonomy consumers | Flutter implementation complete. | 296-test focused Flutter suite passed. | Widget evidence sufficient. | `passed` |
| `T5-DOD-03` | TODO :: Definition of Done :: Focused Laravel tests pass for programming `end_time`, occurrence taxonomy validation, and effective occurrence taxonomy filtering. | Backend validation worker | programming `end_time`, occurrence taxonomy validation, effective occurrence taxonomy filtering, endpoint, schema | Laravel endpoint and schema implementation complete. | 23-test Laravel safe runner passed with 170 assertions proving endpoint and schema contracts. | n/a | `passed` |
| `T5-DOD-04` | TODO :: Definition of Done :: `fvm dart analyze --format machine` passes or unrelated diagnostics are isolated per the Flutter app analyzer gate. | Flutter validation worker | official analyzer gate | Analyzer clean. | `fvm dart analyze --format machine` exit 0. | n/a | `passed` |
| `T5-DOD-05` | TODO :: Definition of Done :: Laravel formatter/test runner gates pass for touched backend code. | Backend validation worker | backend formatter and test runner gates | Formatter and architecture guard clean. | Pint container command and Laravel architecture guard passed. | n/a | `passed` |
| `T5-DOD-06` | TODO :: Definition of Done :: Web build/Playwright or ADB evidence is recorded for visible runtime behavior where widget tests are insufficient. | QA runtime worker | widget sufficiency rationale plus Android scoped run and source-owned Playwright map-width runtime proof | Widget tests proved affected visual/routing surfaces; scoped ADB integration run passed after device reconnection; browser-facing `/mapa` full-width behavior was verified after a fresh web bundle publish. | Android `192.168.15.9:5555`, flavor `guarappari`, app id `com.guarappari.app`; Playwright target `https://guarappari.belluga.space`, build SHA `4372851d`, landlord host `belluga.space`. | `feature_safe_area_and_agenda_appbar_test.dart` and `feature_agenda_filters_regression_test.dart` passed via `flutter drive`; `tools/flutter/web_app_tests/map_full_width.spec.js` passed via `tools/flutter/run_web_navigation_smoke.sh readonly`. | `passed` |
| `T5-DOD-07` | TODO :: Definition of Done :: Canonical module docs are updated for durable contract changes. | Documentation worker | Events, Agenda, Flutter Client Experience module docs | Module docs updated. | Documentation diff review. | n/a | `passed` |
| `T5-DOD-08` | TODO :: Definition of Done :: Independent review/triple audit findings are resolved or explicitly adjudicated. | Assurance worker | triple audit packet | Rounds 01-06 resolved/adjudicated; round 07 clean. | Audit session artifacts. | n/a | `passed` |
| `T5-DOD-09` | TODO :: Definition of Done :: This TODO is consolidated with the two current Store Release orchestration plans before any delivery-ready claim. | Reconciliation/evidence worker | cross-plan consolidation, Wave 2 plan, four-TODO plan, occurrence target migration blocker separation | T5 evidence updated; external contact blocker remains outside this TODO and invite occurrence migration remains governed by its own Store Release plan. | TODO completion guard passed after Guarappari ADB plus Playwright map-width runtime evidence; plan completion guard passed; orchestration delivery guard passed. | Guarappari ADB runtime evidence recorded in `foundation_documentation/artifacts/tmp/flutter-device-runner/t5-guarappari-adb-results-20260501.md`; Playwright map-width evidence recorded in `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`; cross-plan consolidation has no additional navigation mutation. | `passed` |
| `T5-DOD-10` | TODO :: Definition of Done :: Focused Flutter tests pass for Boora icon asset-contract coverage, catalog coverage, icon picker list coverage, and legacy storage-key aliases. | Flutter visual/catalog worker | Boora icon asset-contract coverage, catalog coverage, icon picker list coverage, legacy storage-key aliases | `BooraIcons`, `MapMarkerIconToken`, tenant-admin picker catalog, font-family registration, IcoMoon export + hashed runtime font contract, picker chip glyph rendering contract, and Android glyph rendering validation updated from `assets/fonts/boora_icons_source/icomoon/boora.icomoon.json`. Runtime asset is `assets/fonts/BooraIcons-8880a991.ttf` to avoid immutable cache reuse. | Focused Flutter asset/catalog/widget tests passed for uploaded font family/codepoints, all 60 icons, IcoMoon export/runtime font parity, visible picker chip `IconData` embedding with no checkmark replacement, legacy aliases, runtime filename hash guard, and the canonical invite-status glyph mapping (`invitation`, `invitation_outlined`, `appointment`); `integration_test/feature_boora_icon_font_rendering_test.dart` passed via `flutter drive` on `192.168.15.9:5555` with flavor `guarappari`. | Local Playwright font probe artifact `foundation_documentation/artifacts/tmp/web-navigation/t5-boora-font-runtime-playwright-20260501.md` loaded the hashed Boora runtime font from nginx, verified the current font-family manifest, and confirmed canvas alpha pixels for private-use glyphs. | `passed` |
| `T5-DOD-11` | TODO :: Definition of Done :: Focused Flutter tests pass for Map route web full-width behavior and preservation of the max-width frame on non-map routes. | Flutter visual/catalog worker | Map route web full-width behavior, preservation of max-width frame, web/browser route wrapper | `TenantPublicWebDesktopFrame` route allowlist implemented for Map/Poi only. | `tenant_public_web_desktop_frame_test.dart` passed for Map/Poi full width and non-map constrained frame. | web/browser behavior covered by route wrapper widget tests. | `passed` |
| `T5-DOD-12` | TODO :: Definition of Done :: Focused Laravel tests pass for map live-now read-time freshness on stale projections. | Backend Map worker | map live-now read-time freshness on stale projections | `MapPoiQueryFormatting` read-time live-now recomputation implemented for valid event occurrence facets. | RED/GREEN `test_map_reads_recompute_now_flag_from_active_occurrence_facets`; focused Map backend set passed 3 tests / 14 assertions. | DEV runtime map API route evidence also proved the stale-false shape returns `is_happening_now=true` on `/api/v1/map/pois` and `/api/v1/map/near` for seeded current-event data. | `passed` |
| `T5-DOD-13` | TODO :: Definition of Done :: Focused Flutter tests pass for map marker `AGORA` rendering and timing-badge no-wrap layout. | Flutter visual worker | map marker `AGORA` rendering and timing-badge no-wrap layout | `PoiMarker` badge overlay updated to keep event timing labels single-line and to render backend true state as `AGORA`. | RED/GREEN `keeps event time badge on one line when marker is narrow`; `renders AGORA badge from transport happening-now payload` passed. | Widget evidence sufficient for the Map marker layout defect. | `passed` |
| `T5-DOD-14` | TODO :: Definition of Done :: Runtime evidence is recorded for current-event map behavior on a controlled DEV seeded map route. | QA runtime worker | current-event map behavior on a controlled DEV seeded map route | DEV-only seed `dev-map-live-now-seed` created and removed after validation. | API route assertions recorded in evidence artifact. | `foundation_documentation/artifacts/tmp/store-release-map-live-now-marker-evidence-20260501.md`; controlled DEV seeded map route validation through `/api/v1/map/pois` and `/api/v1/map/near` returned `is_happening_now=true` for the seeded event/facet on `https://guarappari.belluga.space`; this is the runtime route evidence for the current-event map behavior. | `passed` |
| `T5-FLUTTER-CARDS-01` | TODO :: Home Agenda and Account Profile agenda cards compress linked Account Profiles with `e mais X`. | Flutter visual worker | `e mais X`, Home Agenda card, Account Profile agenda card | Shared card compression implemented. | Focused Flutter tests passed. | Widget evidence sufficient. | `passed` |
| `T5-FLUTTER-CARDS-02` | TODO :: additional chip surfaces are inventoried and changed only after approval. | Flutter inventory worker | inventory checkpoint, tab surfaces | Inventory recorded and approved; linked profile category tab cards remained excluded from compression. | Test-quality audit round 01 reviewed optional surfaces. | User approval checkpoint recorded. | `passed` |
| `T5-FLUTTER-CHROME-01` | TODO :: Home Agenda status/radius actions cannot both be extended. | Flutter chrome worker | `Convites`, `Confirmados`, radius action, confirmed attendance independent of invite origin | Shared action behavior implemented. | Flutter controller/widget tests passed. | Widget evidence sufficient. | `passed` |
| `T5-FLUTTER-LAYOUT-01` | TODO :: event card no longer overflows under conditional icon. | Flutter visual worker | stable trailing action slot and body below header | Card layout implemented. | Constrained Flutter tests passed. | Widget evidence sufficient. | `passed` |
| `T5-FLUTTER-TIME-01` | TODO :: explicit ranges render with `às`. | Flutter visual worker | `15:00 às 18:00`, all audited visible time ranges with explicit end time | Audited formatters/surfaces updated. | Focused Flutter tests passed. | n/a | `passed` |
| `T5-BACKEND-PROGRAM-01` | TODO :: programming items support optional `end_time`. | Backend Events worker | `programming_items[].end_time`, endpoint, schema, explicit empty arrays | Laravel endpoint validation/persistence/projection schema implemented. | Laravel feature tests passed for endpoint and schema behavior, including explicit empty arrays clearing owned occurrence fields. | Guarappari ADB runtime evidence recorded in `foundation_documentation/artifacts/tmp/flutter-device-runner/t5-guarappari-adb-results-20260501.md`; admin programming authoring path is widget-covered. | `passed` |
| `T5-FLUTTER-PROGRAM-01` | TODO :: Flutter admin/public handles programming `end_time`. | Flutter contract worker | admin field, DTO, public programming label | Flutter DTO/domain/admin/public implementation complete. | Flutter DTO/widget/admin tests passed. | Widget evidence sufficient. | `passed` |
| `T5-BACKEND-TAX-01` | TODO :: occurrence taxonomy override restricted by event category. | Backend Events worker | synthetic category/taxonomy fixtures, schema | Laravel validation schema implemented. | Laravel positive/negative schema tests passed. | n/a | `passed` |
| `T5-BACKEND-TAX-02` | TODO :: public taxonomy filters operate on effective occurrence taxonomy. | Backend Events worker | occurrence A/B query result, endpoint | Laravel endpoint query implementation and fanout guard complete. | Laravel endpoint query tests passed. | Backend query evidence sufficient. | `passed` |
| `T5-FLUTTER-TAX-01` | TODO :: Flutter admin/public consumers handle occurrence taxonomy override. | Flutter contract worker | occurrence editor UI field plus filter consumer DTOs | Flutter DTO/controller/admin implementation complete, including the tenant-admin occurrence editor taxonomy field. | Flutter DTO/admin/widget/public filter tests passed, including `authors occurrence taxonomy overrides from the date editor`. | Widget evidence sufficient. | `passed` |
| `T5-FLUTTER-ICON-01` | TODO :: current Boora icon font is replaced and tenant-admin picker lists every new font icon. | Flutter visual/catalog worker | `assets/fonts/boora_icons_source/icomoon/boora.icomoon.json`, `assets/fonts/boora_icons_source/icomoon`, `assets/fonts/BooraIcons-8880a991.ttf`, `BooraIcons`, `MapMarkerIconToken`, icon picker | Font IcoMoon export, IcoMoon source directory, hashed runtime asset/config, runtime family registration, catalog, picker chip glyph contract, and device/web glyph proof updated. | Flutter asset-contract/catalog/widget tests passed for uploaded font family/codepoints, 60 icons, IcoMoon export/runtime font parity, aliases, embedded chip icons, and hash-prefixed runtime filename; Android drive test proved glyph rendering differs from missing-font fallback for `invitation`, `invitation_outlined`, and `appointment`. | Local Playwright font probe proved the hashed web font loads and draws for both `BooraIcons` and legacy `Boora` families. | `passed` |
| `T5-FLUTTER-MAP-WEB-01` | TODO :: Map route family skips tenant-public web max-width frame while non-map routes stay constrained. | Flutter visual/catalog worker | `TenantPublicWebDesktopFrame`, `CityMapRoute`, `PoiDetailsRoute`, web/browser route wrapper | Explicit full-width route exemption implemented. | Flutter widget tests passed for Map/Poi and non-map routes; Playwright runtime proved `/mapa` full-width while Home stayed framed. | `tools/flutter/web_app_tests/map_full_width.spec.js` passed on `https://guarappari.belluga.space` after web build SHA `4372851d` was served. | `passed` |
| `T5-MAP-LIVE-NOW-01` | TODO :: Map active occurrences render as happening now/`AGORA` even when stored projection flags are stale. | Backend Map worker + Flutter visual worker | `/api/v1/map/pois`, `/api/v1/map/near`, `is_happening_now`, `occurrence_facets`, `PoiMarker`, `AGORA` | Backend `MapPoiQueryFormatting` recomputes live-now from valid occurrence windows at response formatting time; Flutter marker rendering path already consumes backend true state. | RED/GREEN Laravel test `test_map_reads_recompute_now_flag_from_active_occurrence_facets`; focused Map backend set passed 3 tests / 14 assertions; Flutter `renders AGORA badge from transport happening-now payload` passed. | Controlled DEV seed `dev-map-live-now-seed` on Guarappari tenant returned `is_happening_now=true` on `/api/v1/map/pois` and `/api/v1/map/near`; seed deleted after validation. Evidence: `foundation_documentation/artifacts/tmp/store-release-map-live-now-marker-evidence-20260501.md`. | `passed` |
| `T5-MAP-BADGE-01` | TODO :: Map timing badge stays single-line and grows horizontally instead of wrapping digits vertically. | Flutter visual worker | `PoiMarker`, event timing badge, `19:30`, `AGORA` | Map marker `PoiMarker` timing badge overlay now uses a shared single-line overlay path that can horizontally exceed the marker circle without changing marker identity or clustering. | RED/GREEN Flutter test `keeps event time badge on one line when marker is narrow`; RED reproduced text height `70.0`, GREEN passed. Transport `AGORA` marker test also passed. | Map marker widget evidence is sufficient for the reported constrained-layout defect; no additional runtime screenshot required. | `passed` |

## Spec Deviation Ledger
| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | `No spec deviations approved.` | `n/a` | `n/a` | `n/a` |

## Dependency Graph
- `WS-T5-A Inventory` must run before optional compression beyond Home Agenda and Account Profile agenda cards.
- `WS-T5-B Flutter Visual Polish` can start after fail-first tests and does not block backend work.
- `WS-T5-C Backend Occurrence Contract` blocks final Flutter admin/public contract consumers for programming `end_time` and taxonomy override payload shape.
- `WS-T5-D Flutter Contract Consumers` can write fail-first DTO/admin/public tests in parallel, then finalize after backend shape is stable.
- `WS-T5-B Flutter Visual Polish` also owns the icon-font/catalog expansion and the Map web full-width exception because both are bounded Flutter visual/layout surfaces.
- `WS-T5-E QA Runtime` creates runtime/browser/device checks after implementation branches reconcile and web build is fresh; Map live-now uses controlled seeded data in DEV.
- Final consolidation runs this plan's guard alongside the two existing Store Release plans; this plan cannot mask blockers already recorded in those plans.

## Orchestration Topology
- **Base branch / commit:** after approval, use the current Store Release orchestration branch state, preserving already consolidated deep-link, invite, favorite, OTP, and discovery fixes.
- **Orchestrator reconciliation branch:** create or reuse a branch named `orchestration/store-release-agenda-card-polish-occurrence-taxonomy-20260501` for Flutter/Laravel/root and `docs/store-release-agenda-card-polish-occurrence-taxonomy-20260501` for foundation docs.
- **Principal checkout policy:** principal checkout stays on the reconciliation branch for analyzer, web build, Playwright, ADB, and guard execution.
- **Worker branches / worktrees:** after approval, create disjoint worker branches/worktrees for Flutter visual polish, Flutter contract consumers, Backend Events, and QA/runtime evidence.
- **Build artifact policy:** generated `web-app` remains validation output and is not committed as source unless a promotion plan explicitly owns it.

## Workstreams
| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-T5-A Inventory And Decision Checkpoint` | source inventory and optional compression candidate list only | governing TODO, screenshots, existing hero compression baseline | inventory note plus optional-surface approval request | `rg` inventory; no source implementation except TODO evidence updates |
| `WS-T5-B Flutter Visual Polish` | Home Agenda card/header, shared upcoming card, Account Profile agenda card, time labels, overflow layout, Boora icon font/catalog/picker, Map web full-width exception | `WS-T5-A` for optional surfaces; existing Flutter module docs; uploaded `flutter-app/assets/fonts/boora_icons_source/icomoon/boora.icomoon.json` IcoMoon export | Flutter visual/catalog checkpoint | focused widget/controller/catalog/layout tests and official analyzer for touched Flutter code |
| `WS-T5-C Backend Events Contract` | Laravel Events programming item end time, occurrence taxonomy override validation, persistence/projection, query filtering, migrations/index review | events module docs and approved decisions | backend checkpoint | Laravel fail-first and passing tests through safe runner |
| `WS-T5-D Flutter Contract Consumers` | Flutter DTO/domain/admin/public consumers for `end_time` and occurrence taxonomy override | `WS-T5-C` payload shape | Flutter contract checkpoint | DTO/domain/admin/public focused tests and analyzer |
| `WS-T5-E QA Runtime And Guardrails` | Playwright/browser, optional ADB screenshot/interaction, seeded map runtime validation, test-quality audit, performance/query review | reconciled implementation from B/C/D | validation checkpoint | Playwright list/run after web build, ADB if needed, controlled seeded map event proof, audit artifacts |
| `WS-T5-F Reconciliation And Consolidation` | merge/reconcile, docs evidence, module docs, TODO matrix, guard runs across T5 and existing plans | all workstreams | consolidation checkpoint | TODO guard, orchestration plan/delivery guard, focused reruns |

## Execution Ownership Ledger
| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-T5-A Inventory And Decision Checkpoint` | Inventory worker | `none` | inventory list and approval checkpoint for optional surfaces | TODO updated with approved optional surfaces |
| `WS-T5-B Flutter Visual Polish` | Flutter visual worker | reconciliation-only | changed Flutter files/assets, fail-first tests, passing focused tests | merged branch, focused reruns, analyzer |
| `WS-T5-C Backend Events Contract` | Backend Events worker | reconciliation-only | changed Laravel files, migrations if needed, fail-first tests, passing focused tests | merged branch, Laravel safe runner reruns |
| `WS-T5-D Flutter Contract Consumers` | Flutter contract worker | reconciliation-only | DTO/domain/admin/public changes and tests | merged branch, focused reruns, analyzer |
| `WS-T5-E QA Runtime And Guardrails` | QA/runtime worker | `none` | Playwright/ADB/audit evidence | final runtime/evidence rows in TODO |
| `WS-T5-F Reconciliation And Consolidation` | Reconciliation validation worker | `reconciliation-only` | n/a | guards and final evidence update |

## Execution Waves
Waves are orchestrator-owned control checkpoints. They are not user feedback gates and must not stop execution by default. Stop only for a mandatory user decision, scope change, conflict with the governing TODO set, real blocker, optional-surface approval, or explicit validation waiver.

### Wave 0 - Approval And Preflight
- Wait for explicit `APROVADO`.
- Verify context/readiness again if the branch/session changed.
- Create/reuse reconciliation branches and record clean/dirty worktree state.
- Re-run package-first queries if new package boundaries appear.
- **Gate to next wave:** plan approved, branch state recorded, worker ownership disjoint.

### Wave 1 - Inventory And Fail-First Tests
- Run exhaustive `rg` inventory for card/chip/time/programming/taxonomy surfaces.
- Report optional compression candidates for user approval before changing them.
- Add fail-first tests for explicit UI polish and backend occurrence contracts.
- Add fail-first tests for Map live-now stale projection freshness and narrow marker timing-badge layout before implementing the reopened addendum.
- **Gate to next wave:** fail-first tests exist or any non-testable item has an explicit evidence rationale.

### Wave 2 - Parallel Implementation
- Implement Flutter visual polish for explicitly approved surfaces.
- Replace the Boora icon font asset, move generated declarations into canonical app source, and update the tenant-admin picker catalog to expose every new font icon.
- Remove the tenant-public web max-width frame only from the Map screen route family and keep the frame on non-map tenant-public routes.
- Implement Laravel programming `end_time` and occurrence taxonomy override/filtering contracts.
- Implement Flutter DTO/domain/admin/public consumers against backend payload shape.
- Implement Map live-now read-time freshness and Map marker timing-badge no-wrap layout if the fail-first tests confirm the reported defects.
- **Gate to next wave:** focused worker-local tests pass and no worker introduced hardcoded profile-type assumptions.

### Wave 3 - Reconciliation And Focused Validation
- Merge/reconcile worker checkpoints into the orchestration branch.
- Run focused Flutter suites, Laravel safe runner suites, official analyzer, and formatter gates.
- Update module docs for durable backend/Flutter contracts.
- **Gate to next wave:** local focused validation is green or blockers are explicit.

### Wave 4 - Runtime, Audit, And Consolidated Guards
- Build web if visible web surfaces changed.
- Run Playwright/browser checks for public/admin paths where source-owned tests exist; run ADB screenshot/interaction checks if widget/browser evidence cannot prove the reported visual behavior.
- Create DEV seeded current-event data for Map runtime validation; do not seed production.
- Run architecture, test-quality, performance/query, and triple review lanes.
- Run TODO and orchestration guards for T5, then consolidate results with the two existing Store Release orchestration plans.
- **Gate to completion:** T5 guard is `go`; existing plans' blockers remain accurately represented; no delivery claim hides T3 contact-materialization or other external blockers.

## Consolidated Validation Matrix
| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| Flutter visual polish | Focused widget/controller tests for compression, overflow, Home Agenda chrome, time labels, Boora icon catalog, icon picker coverage, and Map web full-width exception. | local Flutter test; optional screenshot runtime | Flutter visual worker |
| Laravel Events contract | Feature/unit tests for programming `end_time`, occurrence taxonomy validation, effective occurrence taxonomy filters, and query/index review. | Laravel safe runner/container | Backend Events worker |
| Flutter contract consumers | DTO/domain/admin/public tests for `end_time` and occurrence taxonomy override payloads. | local Flutter test | Flutter contract worker |
| Analyzer/formatters | `fvm dart analyze --format machine`; Laravel formatter/test gates for touched backend code. | local toolchain | Reconciliation worker |
| Runtime web/device | Playwright after web build for visible public/admin paths; ADB only when visual Android proof is needed. | browser and/or Android device | QA runtime worker |
| Map live-now runtime | Controlled DEV seeded current-event data proves marker/list `AGORA` behavior. | canonical DEV tenant URL/auth | QA runtime worker |
| Audit/guards | Test-quality audit, architecture audit, performance/query review, TODO completion guard, orchestration delivery guard. | deterministic local guard | Reconciliation worker |
| Cross-plan consolidation | Existing Wave 2 and four-TODO plan blockers remain explicit while T5 closes its own scope. | documentation/guard review | Reconciliation worker |

## Consolidated Delivery Evidence
Fill this section only after approved execution and verification.

| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |
| Flutter visual polish | Focused widget/controller tests for compression, overflow, Home Agenda chrome, time labels, Boora icon catalog, icon picker coverage, Map web full-width exception, occurrence editor taxonomy field, and pending occurrence-id EventSearch filtering. | `passed` | 296-test focused Flutter suite, plus focused EventSearch/repository/backend contract subsets; analyzer exit 0. | Reconciliation worker |
| Laravel Events contract | Feature/unit tests for programming `end_time`, occurrence taxonomy validation, effective occurrence taxonomy filters, pending occurrence-id filter, occurrence identity reorder preservation, duplicate occurrence identity guards, and query/index review. | `passed` | Laravel safe runner filter suite: 23 tests, 170 assertions. | Backend validation worker |
| Flutter contract consumers | DTO/domain/admin/public tests for `end_time` and occurrence taxonomy override payloads. | `passed` | Flutter DTO/admin/controller/widget tests in focused suite. | Flutter contract worker |
| Analyzer/formatters | `fvm dart analyze --format machine`; Laravel formatter/test gates for touched backend code. | `passed` | Analyzer exit 0; Pint container command passed; Laravel architecture guard passed. | Reconciliation worker |
| Runtime web/device | Runtime required where widget tests are insufficient; scoped Android run added after device reconnection and Playwright added for browser-facing Map width. | `passed` | `foundation_documentation/artifacts/tmp/flutter-device-runner/t5-guarappari-adb-results-20260501.md`; `feature_safe_area_and_agenda_appbar_test.dart` and `feature_agenda_filters_regression_test.dart` passed on flavor `guarappari`; `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`; `tools/flutter/web_app_tests/map_full_width.spec.js` passed on `https://guarappari.belluga.space`. | QA/runtime worker |
| Map live-now runtime | Controlled DEV seeded current-event data proves marker/list `AGORA` behavior and the same addendum also fixes the Map marker timing-badge vertical wrap. | `passed` | Backend RED/GREEN `test_map_reads_recompute_now_flag_from_active_occurrence_facets`; focused Map backend set 3 tests / 14 assertions; Flutter focused Map marker tests `keeps event time badge on one line when marker is narrow` and `renders AGORA badge from transport happening-now payload`; DEV runtime evidence `foundation_documentation/artifacts/tmp/store-release-map-live-now-marker-evidence-20260501.md`; formatter, architecture guard, exact lookup audit, and analyzer passed. | Backend Map worker + Flutter visual worker + QA/runtime worker |
| Audit/guards | Test-quality audit, architecture/elegance audit, performance/query review, TODO completion guard, orchestration delivery guard. | `passed` | Triple audit session round 07 clean after prior round resolutions/adjudications; TODO completion guard passed; orchestration plan completion guard passed; orchestration delivery guard passed. | Assurance worker |
| Cross-plan consolidation | Existing Wave 2 and four-TODO plan blockers remain explicit while T5 closes its own scope. | `passed` | T5 evidence updated; external contact materialization blocker remains explicitly outside this TODO and owned by the four-TODO Store Release plan. | Reconciliation worker |

## Runtime Freshness Evidence
- **Recorded at:** 2026-05-01T19:19:58Z after Guarappari ADB rerun and browser-facing Map Playwright proof.
- **Branches and commits:** Flutter `orchestration/store-release-agenda-card-polish-occurrence-taxonomy-20260501` at `4372851d`; Laravel `orchestration/store-release-agenda-card-polish-occurrence-taxonomy-20260501` at `2cec9e9`; Foundation docs `docs/store-release-agenda-card-polish-occurrence-taxonomy-20260501` carries this evidence package in the current docs commit.
- **Device:** `192.168.15.9:5555`, app id `com.guarappari.app`, flavor `guarappari`, define file `config/defines/integration.tenant.json`.
- **Build artifact:** `build/app/outputs/flutter-apk/app-guarappari-debug.apk`; `output-metadata.json` reports `applicationId=com.guarappari.app`, `variantName=guarappariDebug`, output file `app-guarappari-debug.apk`.
- **Served target/provenance:** `flutter drive` built and installed `app-guarappari-debug.apk` on `192.168.15.9:5555`; runtime logs show `getOpPackageName=com.guarappari.app` before test execution.
- **Scoped ADB command:** `DEVICE_RUNNER_MODE=drive ... device_single_test_resilient.sh _run ... com.guarappari.app guarappari ...`.
- **Scoped ADB files:** `integration_test/feature_safe_area_and_agenda_appbar_test.dart` and `integration_test/feature_agenda_filters_regression_test.dart`.
- **Scoped ADB result:** both passed; evidence artifact `foundation_documentation/artifacts/tmp/flutter-device-runner/t5-guarappari-adb-results-20260501.md`.
- **Web build command:** `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app`.
- **Web publish/reload:** `docker compose restart nginx`.
- **Browser-facing web provenance:** `https://guarappari.belluga.space/index.html` and `https://belluga.space/index.html` both exposed `window.__WEB_BUILD_SHA__ = "4372851d"`.
- **Scoped Playwright command:** `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_EXPECTED_WEB_BUILD_SHA=4372851d NAV_EXPECTED_LANDLORD_HOST=belluga.space NAV_WEB_GREP_EXTRA='MAP-WEB-WIDTH-01' NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 bash ../tools/flutter/run_web_navigation_smoke.sh readonly`.
- **Scoped Playwright file:** `tools/flutter/web_app_tests/map_full_width.spec.js`.
- **Scoped Playwright result:** passed; Home stayed framed at `430px`, `/mapa` occupied the full `1200px` viewport, runtime provenance matched `buildSha=4372851d` and `landlordHost=belluga.space`; evidence artifact `foundation_documentation/artifacts/tmp/web-navigation/t5-map-web-full-width-playwright-20260501.md`.
- **Prior local integration attempt:** Linux desktop target was blocked by missing `libsecret-1>=0.18.4`, an environment dependency for `flutter_secure_storage_linux`, not a T5 assertion failure.

## Map Live-Now Addendum Runtime Evidence
- **Approval:** user replied `APROVADO` on 2026-05-01 for the Map live-now/timing-badge addendum.
- **Backend fail-first:** `./scripts/delphi/run_laravel_tests_safe.sh --filter='test_map_reads_recompute_now_flag_from_active_occurrence_facets'` failed before implementation because stale stored `is_happening_now=false` was returned after the occurrence window was active.
- **Backend pass:** the same test passed after read-time live-now recomputation was added; focused Map set passed with 3 tests / 14 assertions.
- **Flutter fail-first:** narrow `PoiMarker` test reproduced the vertical wrap with `19:30` text height `70.0`.
- **Flutter pass:** focused marker tests passed for no-wrap `HH:mm` and transport `is_happening_now=true` -> `AGORA`.
- **DEV runtime seed:** tenant `695c1809fee8b3839804dc85`, ref `dev-map-live-now-seed`, active window `2026-05-01T22:35:20.564831Z` to `2026-05-02T01:05:20.564831Z`.
- **DEV runtime result:** `/api/v1/map/pois` and `/api/v1/map/near` through `https://guarappari.belluga.space` returned the seeded event with `is_happening_now=true`; the near payload also returned the occurrence facet with `is_happening_now=true`.
- **Cleanup:** DEV seed was deleted after validation (`{"deleted": 1}`); no production data was seeded.
- **Evidence artifact:** `foundation_documentation/artifacts/tmp/store-release-map-live-now-marker-evidence-20260501.md`.

## Risk / Conflict Controls
- Do not change optional compression surfaces before the inventory checkpoint and user approval.
- Do not hardcode profile types such as `venue` in new taxonomy/profile guardrail tests.
- Do not infer occurrence taxonomy by merging event and occurrence terms when override terms exist.
- Do not add browser-origin or tenant-host shortcuts that bypass canonical domain/lane settings.
- Do not solve Home Agenda action state with unrelated controller-to-controller relays or persisted preferences; the mutual extension state is local chrome.
- Do not remove or rename persisted/default icon storage keys without a backwards-compatible alias in the icon catalog.
- Do not remove the tenant-public web frame from non-map public routes while implementing the Map full-width exception.
- Do not claim the existing social/contact plan is solved because this plan passes; T3 contact materialization has its own evidence gate.
- If programming item overnight ranges are required, stop for a spec decision instead of silently encoding cross-day semantics into `end_time`.

## Approval Request
- **Requested approval:** Reply `APROVADO` to authorize this T5 orchestration plan.
- **Approval status:** approved on 2026-05-01; user replied "Perfeito. Aprovado dessa forma."
- **Map addendum approval status:** approved on 2026-05-01; user replied `APROVADO` after clarifying that controlled validation data must be created in DEV, not production.
- **Execution authorized by approval:** create/reuse the T5 orchestration branches/worktrees, run inventory, request optional compression approval where needed, implement the explicit T5 scope plus the approved Boora icon-font/catalog expansion, run focused/browser/device/audit guards, and consolidate the evidence with the two current Store Release plans.
- **Execution not authorized by approval:** production promotion, unrelated invite/favorite/contact/OTP/deep-link changes, generated `web-app` source commits, broad taxonomy model redesign, or optional compression changes not approved after inventory.
- **Autonomy rule:** after approval, the orchestrator advances through waves without asking between each wave unless a mandatory decision, optional-surface approval, blocker, waiver, or spec deviation appears.

## Plan Completion Guard
- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-agenda-card-polish-occurrence-taxonomy-orchestration-plan.md`
- **Required before execution:** `Overall outcome: go`

## Delivery Guard
- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-agenda-card-polish-occurrence-taxonomy-orchestration-plan.md --require-approved`
- **Required before delivery completion claim:** `Overall outcome: go`
