# TODO (Store Release): Critical Journey Regression Gates

**Authority note (2026-04-20):** this TODO is the single active authority for the remaining release-critical regression gates that prove the current Android publication journeys end to end. It does not own product redesign, new contract authoring, or broad test-program expansion outside the bounded journeys below.
**Classification note (2026-04-20):** this slice remains in `store_release_android/` because it governs release confidence, but it is now normalized to the current TODO schema and aligned to the canonical module decisions that already froze the public agenda/search and tenant-admin event-form contracts.
**Module drift note (2026-04-20):** `foundation_documentation/system_roadmap.md` still lists `/api/v1/agenda` with `search`, but the authoritative module docs (`events_module.md`, `tenant_admin_module.md`) already freeze no public text-search for MVP. This TODO preserves module truth and treats the roadmap line as reconciliation debt, not as a reopened product decision.
**2026-03-18 carried forward:** agenda mutation parity assertions remain scoped to the canonical Home agenda request to avoid false negatives from auxiliary agenda fetches.
**Runtime baseline note (2026-04-20, user directive):** the current checked-out code and observed runtime behavior at execution start are the authority for regression expectations under this TODO. Historical TODO wording, old evidence, or stale test assumptions cannot justify reverting present behavior inside this slice.
**Sentry note (2026-04-20, user directive):** Sentry is initialized globally, but touched/supporting Flutter flows still contain `catch` + `debugPrint` / fallback patterns that can suppress unexpected failures before they reach Sentry. This TODO now owns bounded Sentry hardening for touched critical/support flows so unexpected failures are explicitly classified and do not disappear silently.
**Current-state note (2026-04-21):** `flutter-app` is now on `dev @ ccb6795a`, `web-app` is now on `dev @ ec2d41b`, and `laravel-app` remains on `dev @ 37fd59b`. Previously adjacent store-release slices for media, proximity/reference-location, and tenant settings have moved to `promotion_lane/` and are now treated as the current `dev` baseline for this TODO, not as active sibling implementation work.
**Sentry rule note (2026-04-21, user directive):** this TODO must establish a project-owned Flutter/Sentry rule so future touched code may keep the app UX quiet when appropriate, but must not suppress unexpected failures without reporting them to Sentry.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Promotion Lane
**Owners:** Backend Team + Flutter Team + Platform
**Objective:** eliminate silent false positives and establish high-confidence regression gates across Laravel + Flutter + Web for the remaining release-critical user journeys.

---

## Context

Current evidence is useful but not yet orchestration-safe. The TODO had partial web/navigation proof, partial Flutter proof, and a Laravel suite run, but it was still missing the current TODO schema, explicit `web+mobile` compatibility gating, deterministic stage accounting, and module-coherent treatment of the public agenda/search contract.

The most important correction is contractual: this slice must no longer treat public agenda text-search or Atlas readiness as the target behavior. The canonical module docs already froze the MVP rule that public agenda/events listing is category/tag/taxonomy/geo only, with public text-search prohibited. Test hardening must prove that frozen contract instead of reopening it indirectly.

Execution is now also runtime-truth-first: current checked-out behavior across the touched journeys is the regression baseline. If older TODO wording, stale tests, or supporting docs disagree with live code behavior, this TODO must capture the drift and preserve runtime truth rather than "fixing" the app back to an obsolete expectation.

Sentry coverage is part of the release risk for this slice. `flutter-app/lib/main.dart` already initializes Sentry, but touched/supporting paths such as `flutter-app/lib/application/application_contract.dart`, `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, and `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart` still show local suppression/fallback patterns. The gate must now harden touched exception paths so unexpected failures either report to Sentry or propagate with explicit classification instead of disappearing behind `debugPrint`.

The repository baseline changed after this TODO was normalized. Current `dev` already includes the public agenda API-default pagination rule, the proximity/reference-location baseline, tenant settings snapshot work, media hardening, and a tenant-admin browser mutation suite. This TODO must consume those as current runtime truth and must not re-plan against pre-merge branch assumptions.

---

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this work is already one bounded release gate inside `TODO-store-release-android.md`, with one primary objective: convert partial regression evidence into deterministic release-confidence gates across the three remaining journeys.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the parent release orchestrator already exists, the scope is a single child hardening slice, and the open work is execution-oriented rather than discovery-shaped.

---

## Contract Boundary

- This TODO defines **WHAT** must be true before the release-critical journeys can be claimed as regression-protected.
- `Assumptions Preview`, `Test Coverage Matrix`, and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- The current checked-out code and observed runtime behavior at execution start are the authoritative regression baseline for this TODO.
- This TODO is **bounded but elastic** only inside test hardening, orchestration, evidence capture, bounded Sentry hardening for touched exception paths, and non-functional helpers that do not intentionally change visible runtime behavior.
- If current runtime behavior differs from historical TODO wording, stale evidence, or old test assumptions, update the gate plan and record the drift; do not revert behavior under this slice solely to satisfy legacy wording.
- If execution reveals a real product defect, do not fix it here. Open a dedicated fix TODO, record the blocker here, and keep the affected stage `blocked` until the fix slice is approved and completed.

---

## Delivery Status Canon

- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Planning-Ready`, `Cross-Stack`, `Release-Critical`, `Local-Evidence-Passed`, `Mobile-Evidence-Passed`, `Browser-Evidence-Passed`, `Roadmap-Handoff-Recorded`, `Triple-Review-Clean`, `Dev-Lane-Promoted`
- **Next exact step:** continue the approved `dev -> stage` lane flow when stage promotion is explicitly authorized.

---

## No-Context Handoff Boundaries

- **Frozen here:** `D-T01` through `D-T11` are the governing release-confidence baseline for this TODO. Delivery must not reopen public agenda text-search, admin route ownership, the runtime-truth-first baseline, the Sentry classification rule, the Sentry rule/enforcement requirement, browser lane-awareness, or the `web+mobile` compatibility requirement without an explicit baseline update.
- **Not owned here:** product-logic fixes, new public agenda product direction, tenant-admin IA changes, broad roadmap edits, and unrelated package/framework migrations.
- **Primary canonical anchors:** `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/agenda_and_action_planner_module.md`, `foundation_documentation/modules/tenant_admin_module.md`
- **Supporting strategic anchor:** `foundation_documentation/system_roadmap.md` is used only for lane classification and sequencing; it is not the contract authority when it conflicts with the module docs.
- **Executor rule:** treat this TODO as a release-confidence packet. It is not a place to redesign the public schedule/search product or to absorb product fixes opportunistically.

---

## Scope Ownership

| Journey | Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard / Identity |
| --- | --- | --- | --- | --- | --- | --- |
| `CJ-01` Home agenda parity | tenant Home agenda section on `/` | Tenant | `tenant` | `tenant_public` | `n/a` | tenant-public runtime contract per `flutter_client_experience_module.md` |
| `CJ-02` public agenda filter/no-search contract | `/agenda` route family in app plus related tenant-public filter/search-governance surfaces | Tenant | `tenant` | `tenant_public` | `n/a` | tenant-public runtime contract; anonymous web `/agenda` remains blocked/fallback-governed |
| `CJ-03` tenant-admin event type form flow | `TenantAdminEventCreateRoute` / `TenantAdminEventEditRoute` (`/admin/events/*`) | Tenant | `tenant` | `tenant_admin` | `n/a` | landlord principal on tenant domain per `tenant_admin_module.md` |

---

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/modules/flutter_client_experience_module.md` (`validation/test matrix` and release-confidence language only if this TODO changes durable test-governance rules)
  - `foundation_documentation/modules/events_module.md` (`5.4 Search and index lifecycle model`) only if approved execution needs a tighter canonical fail-closed note than what already exists
  - `foundation_documentation/modules/agenda_and_action_planner_module.md` (`3.4` / decision ledger) only if a durable origin-assertion rule must be promoted
  - `foundation_documentation/modules/tenant_admin_module.md` (`event_types` contract / admin event form dependency language) only if approved execution changes durable endpoint-consumption rules
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for real-backend test matrix, repository boundaries, controller ownership, and compatibility evidence semantics. No partial migration flag is declared for the touched surfaces.
- `events_module.md`: authoritative for public agenda contract, no-text-search MVP rule (`EVS-FILTER-01`), and event-form candidate discovery semantics (`EVS-MGMT-01`). No partial migration flag is declared for the touched surfaces.
- `agenda_and_action_planner_module.md`: authoritative for effective-origin-first fetch and backend-owned agenda/search query semantics (`AGD-04`, `AGD-05`, `AGD-07`, `AGD-08`). No partial migration flag is declared for the touched surfaces.
- `tenant_admin_module.md`: authoritative for event type registry payloads and server-driven admin filtering semantics (`TAD-08`, `TAD-11`). No partial migration flag is declared for the touched surfaces.
- `system_roadmap.md`: supporting only for lane classification. Its stale `/api/v1/agenda search` note does not override the module decisions above and must be reconciled through the correct strategic path before TODO closure if it remains stale.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-flutter-test-foundation.md`
- `foundation_documentation/todos/completed/TODO-v1-events-location-gating-and-tenant-default-origin.md`
- `foundation_documentation/todos/completed/TODO-vnext-test-hardening-defect-backlog.md`
- `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `laravel-app/tests/Feature/Events/EventTypesControllerTest.php`
- `flutter-app/integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart`
- `flutter-app/integration_test/feature_agenda_filters_regression_test.dart`
- `flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart`
- `flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- `flutter-app/test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart`
- `flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
- `flutter-app/test/application/router/modules/tenant_public_route_hardening_modules_test.dart`
- `flutter-app/test/application/router/guards/web_anonymous_fallback_guard_test.dart`
- `flutter-app/test/application/router/support/canonical_route_governance_policy_test.dart`
- `flutter-app/lib/main.dart`
- `flutter-app/lib/application/application_contract.dart`
- `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`
- `tools/flutter/web_app_tests/navigation.spec.js`
- `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js`
- `tools/flutter/run_web_navigation_smoke.sh`
- `scripts/delphi/run_reconcile_validation.sh`
- `foundation_documentation/artifacts/store-release-reconcile-validation-matrix-2026-04-20.md`
- `laravel-app/scripts/delphi/run_laravel_tests_safe.sh`

---

## Scope

- Capture and preserve the currently observed runtime behavior for the touched journeys and supporting paths before hardening assertions, so the release gate protects what the app does today rather than what legacy TODO text once expected.
- Treat current `dev` submodule state as the runtime/test baseline: `flutter-app @ f11cf715` and `laravel-app @ 37fd59b`.
- Freeze and execute one cross-stack coverage matrix for the three remaining release-critical journeys.
- Replace stale public-search/Atlas assumptions with module-aligned fail-closed coverage for unsupported public `search`.
- Add strict assertion semantics so payload/behavior mismatches fail loudly instead of passing on transport/status-only checks.
- Define and apply one bounded Sentry/error-handling classification format for touched critical/support flows so unexpected failures do not disappear behind local `debugPrint` / fallback / no-op handling.
- Establish a project-owned Flutter/Sentry rule and enforcement path so touched future code cannot silently swallow unexpected failures without reporting them to Sentry.
- Require explicit `web+mobile` compatibility evidence for any journey claimed as release-safe.
- Classify every required stage as `passed|failed|blocked`, with harness/readiness defects treated as evidence-quality problems first.
- Eliminate bypass-risk patterns in every changed test path and runner surface touched by this TODO.
- Capture any blocking product defect here, but route the fix itself into a dedicated TODO.

## Out of Scope

- New product behavior or new public/admin contracts.
- Broad framework migration or generic test-program cleanup unrelated to the frozen journeys.
- Strategic roadmap editing, except recording a required handoff when stale roadmap text conflicts with module truth.
- Runtime performance benchmarking or unrelated load/perf initiatives.
- Broad telemetry/monitoring redesign outside the touched release-critical/supporting paths.
- Delphi core/self-improvement rule changes; the Sentry rule is project/Flutter-specific unless a later self-improvement session explicitly generalizes it.
- Fixing product defects uncovered by the gates themselves.

---

## Execution Rule (Mandatory)

- This TODO is restricted to test quality, coverage, orchestration, and evidence hardening.
- Non-functional testability helpers are allowed only when they do not change runtime behavior (for example stable widget keys or deterministic test seam exposure).
- Laravel orchestration must use `laravel-app/scripts/delphi/run_laravel_tests_safe.sh`; raw `php artisan test` with inherited environment is not authoritative for this TODO.
- Browser/navigation source-of-truth remains `tools/flutter/web_app_tests/**` executed through `tools/flutter/run_web_navigation_smoke.sh`; `web-app` is not an authored test surface.
- `blocked` is never equivalent to `passed`, especially for the required mobile lane.
- If a required stage is blocked by device, host, runner, permissions, or environment readiness, record the blocker explicitly and do not justify product-code changes from that invalid evidence alone.
- Historical TODO baselines are not authority to revert current runtime behavior. When current code behavior differs, capture it, test it, and record any drift/handoff instead of restoring an older expectation.
- Every touched exception-handling path in scope must be classified as `expected_control_flow`, `recoverable_reported`, or `fatal_reported`; unexpected `debugPrint`-only suppression is forbidden inside the touched slice.
- Browser validation is lane-aware: `readonly` may run on `local|dev|stage|main`, `mutation` may run on `local|dev|stage`, and `mutation` is always blocked on `main`.
- Local browser validation must target the browser-facing domain serving the current integrated principal checkout; do not assume published `stage` URLs when the current execution lane is local or `dev`.
- The Sentry rule must be project-owned and Flutter-facing. Preferred enforcement is through `flutter-app/tool/belluga_analysis_plugin` plus fixture coverage when the check is mechanically safe; otherwise delivery must record why deterministic enforcement is deferred and keep an explicit review/audit rule.

---

## Critical Journeys Under Protection

- `CJ-01` Tenant public Home agenda must reflect canonical backend agenda results and must never show a false empty state when the API returns eligible items.
- `CJ-02` Tenant public agenda/search-governed surfaces must remain backend-driven, origin-aware, and aligned with the frozen MVP rule that public text-search is not part of the contract.
- `CJ-03` Tenant admin event create/edit flows must load event types from the dedicated API contract and keep the form dependency path aligned with the canonical endpoint and auth-token rules.

## Cross-Cutting Guardrail Under Protection

- `OBS-01` Sentry coverage for touched unexpected failures: unexpected failures in touched critical/support flows must either propagate or be reported to Sentry with explicit classification; only documented `expected_control_flow` / intentional noise suppression may bypass reporting.

## Sentry Reporting Policy (Frozen)

- `expected_control_flow`: expected branch/noise path; does not emit to Sentry, but the non-reporting reason must be explicit in code/tests when the path is touched by this TODO.
- `recoverable_reported`: user-visible flow may recover or keep operating, but the unexpected failure must still be sent to Sentry and the visible UX must remain aligned with current behavior.
- `fatal_reported`: the failure prevents the flow from continuing safely; it must be sent to Sentry and then rethrown, surfaced, or fail-closed through the existing runtime path.
- `debugPrint`-only suppression is forbidden for unexpected touched failures. `Sentry.captureException` or the equivalent framework-owned reporting path must be used whenever a touched path is classified `recoverable_reported` or `fatal_reported`.

---

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app: delphi/store-release-critical-journey-regression-gates-dev@4a22e40f -> dev@ccb6795a`, `web-app: ci/flutter-web-dev@1109b64 -> dev@ec2d41b`, `laravel-app: dev@37fd59b`, `foundation_documentation: delphi/docs-reconcile-store-release-20260419@b426019`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch / Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `CJ-01` Home agenda parity gate | `flutter-app: 4a22e40f -> dev@ccb6795a; web-app: 1109b64 -> dev@ec2d41b; laravel-app: dev@37fd59b` | `flutter-app PR #237 merged to dev 2026-04-21; web-app PR #279 merged to dev 2026-04-21` | `<pending>` | `n/a` | `Lane-Promoted` |
| `CJ-02` public agenda filter/no-search gate | `flutter-app: 4a22e40f -> dev@ccb6795a; web-app: 1109b64 -> dev@ec2d41b; laravel-app: dev@37fd59b` | `flutter-app PR #237 merged to dev 2026-04-21; web-app PR #279 merged to dev 2026-04-21` | `<pending>` | `n/a` | `Lane-Promoted` |
| `CJ-03` tenant-admin event type form gate | `flutter-app: 4a22e40f -> dev@ccb6795a; web-app: 1109b64 -> dev@ec2d41b; laravel-app: dev@37fd59b` | `flutter-app PR #237 merged to dev 2026-04-21; web-app PR #279 merged to dev 2026-04-21` | `<pending>` | `n/a` | `Lane-Promoted` |
| `OBS-01` bounded Sentry hardening | `flutter-app: 4a22e40f -> dev@ccb6795a` | `flutter-app PR #237 merged to dev 2026-04-21` | `<pending>` | `n/a` | `Lane-Promoted` |
| `RULE-SENTRY-01` project-owned Sentry reporting rule | `flutter-app: 4a22e40f -> dev@ccb6795a` | `flutter-app PR #237 merged to dev 2026-04-21` | `<pending>` | `n/a` | `Lane-Promoted` |
| Cross-platform orchestration + status report | `foundation_documentation delphi/docs-reconcile-store-release-20260419@b426019` | `flutter-app PR #237 + web-app PR #279 merged to dev; docs TODO moved to promotion_lane/ and pushed to docs branch` | `<pending>` | `n/a` | `Lane-Promoted / Roadmap-Handoff-Recorded` |

---

## Bounded But Elastic Guardrails

- **May stay inside this TODO:** Laravel/Flutter/Web test files, deterministic runners/reporting helpers, evidence artifacts, bounded Sentry classification/reporting alignment for touched exception paths, non-functional testability helpers, and module/submodule-summary promotions caused directly by the hardening work.
- **Must update or split the TODO:** any product bug fix, public/admin contract change, new search/product policy, or broader test-program expansion that is independently valuable outside these three journeys.

---

## Definition of Done

- [x] `CJ-01` has layered evidence across backend contract, Flutter repository/controller/screen, and browser/navigation smoke, with no false-empty-state pass path remaining.
- [x] `CJ-02` proves the frozen MVP public schedule contract: backend-owned filters/origin semantics remain authoritative and public text-search is fail-closed rather than silently Atlas-dependent.
- [x] `CJ-03` proves the dedicated `GET /admin/api/v1/event_types` contract and the tenant-admin form dependency path across backend, repository/controller, and form rendering.
- [x] The currently checked-out runtime behavior for the touched journeys/support paths is explicitly captured and preserved as the regression baseline; no runtime behavior was reverted merely to satisfy historical TODO wording.
- [x] `OBS-01` is satisfied: touched unexpected failures are no longer `debugPrint`-only/silent, every touched exception path follows the approved `expected_control_flow|recoverable_reported|fatal_reported` classification, and the required cases explicitly reach Sentry.
- [x] `RULE-SENTRY-01` is satisfied: a project-owned Flutter/Sentry rule exists, is referenced from the Flutter module or project policy, and has deterministic analyzer/plugin coverage or an explicit approved deferral with review/audit enforcement.
- [x] Compatibility evidence includes both `web` and `mobile`, or the missing platform is explicitly classified `blocked` and the TODO remains open.
- [x] Changed test paths contain no bypass patterns (`skip/only`, status-only semantics where payload matters, catch-and-continue, silent mock fallback for compatibility claims).
- [x] Stage-status accounting is explicit and reproducible through the final orchestration report.
- [x] `Decision Adherence Validation` is fully resolved and any durable test-governance or contract clarifications are promoted to the correct canonical docs before closure.
- [x] The stale roadmap `search` note is either reconciled through the correct strategic path or recorded as an explicit unresolved handoff before closure.

---

## Validation Steps

- Laravel contract lane:
  - `cd laravel-app && ./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventTypesControllerTest.php`
- Flutter analyzer lane:
  - `cd flutter-app && fvm dart analyze --format machine`
- Flutter unit/widget/controller lane:
  - `cd flutter-app && fvm flutter test test/infrastructure/dal/laravel_schedule_backend_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
- Flutter integration lane (real backend / compatibility intent):
  - `cd flutter-app && fvm flutter test integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart integration_test/feature_agenda_filters_regression_test.dart`
  - Add or extend a tenant-admin event-form dependency integration lane if the existing form screen tests are insufficient to prove `CJ-03` at the required confidence level.
- Sentry audit lane:
  - Inspect touched catch/fallback/reporting paths in `flutter-app/lib/main.dart`, `flutter-app/lib/application/application_contract.dart`, `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`, plus any additional journey-touched files introduced during execution.
  - Ensure every touched unexpected failure either reaches Sentry as `recoverable_reported` / `fatal_reported` or is rethrown after capture; only documented `expected_control_flow` may bypass Sentry, and visible UX must remain aligned with current behavior.
- Sentry rule/enforcement lane:
  - Create or update the project-owned Flutter/Sentry rule/policy and wire it into `flutter-app/tool/belluga_analysis_plugin` when mechanically safe.
  - If analyzer enforcement is added, run `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` and then `cd flutter-app && fvm dart analyze --format machine`.
- Flutter mobile lane:
  - Use the `flutter-device-test-runner` workflow for this lane; the concrete runner evidence is `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md` plus `foundation_documentation/artifacts/tmp/flutter-device-runner/touched-branch-suite-reference.md`.
  - Preflight: `cd flutter-app && adb devices`. If no reachable device/emulator exists, classify `Android/mobile critical-journey lane` as `blocked` with device owner + reason before any release-safe claim.
  - Preferred WSL-safe per-file command template:
    - `cd flutter-app && DEVICE_RUNNER_SKIP_APP_RESET=false DEVICE_RUNNER_REPORTER=expanded DEVICE_RUNNER_INNER_TIMEOUT_SECONDS=2400 DEVICE_RUNNER_MODE=auto bash /home/elton/.codex/skills/flutter-device-test-runner/scripts/device_single_test_resilient.sh start /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app <adb-device> <appId> <flavor> <define_file> integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart 1200`
    - Repeat with `DEVICE_RUNNER_SKIP_APP_RESET=true` for `integration_test/feature_agenda_filters_regression_test.dart` and any tenant-admin event-form dependency integration test added by this TODO.
  - A missing tenant-admin Android/device form-dependency run is `blocked` for `CJ-03 mobile`, not covered by repository/widget/browser evidence.
- Web/browser lane:
  - Resolve the current lane and browser-facing landlord/tenant targets from the active execution evidence (`foundation_documentation/artifacts/store-release-reconcile-validation-matrix-2026-04-20.md`, README, dependency-readiness, or explicit user target).
  - If the browser-facing targets are reachable but expose an older `window.__WEB_BUILD_SHA__`, treat that as a stale published bundle, not as an immediate browser blocker. First run the project-owned publish step `cd flutter-app && ./scripts/build_web.sh ../web-app <lane>` (`dev` for this local/dev validation lane), then re-probe `web-app/index.html`, `NAV_LANDLORD_URL`, and `NAV_TENANT_URL` for the current Flutter checkout SHA.
  - Classify browser as `blocked` only if the publish script fails, the Cloudflare/local tunnel cannot serve the rebuilt `../web-app` bundle, or the SHA remains stale after the rebuild/reprobe cycle.
  - `NAV_DEPLOY_LANE=<local|dev|stage|main> NAV_WEB_TEST_TYPE=readonly NAV_LANDLORD_URL=<browser-facing landlord URL> NAV_TENANT_URL=<browser-facing tenant URL> PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly`
  - `NAV_DEPLOY_LANE=<local|dev|stage> NAV_WEB_TEST_TYPE=mutation NAV_LANDLORD_URL=<browser-facing landlord URL> NAV_TENANT_URL=<browser-facing tenant URL> PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation`
  - `CJ-03 browser-auth admin` is mandatory for a full browser confidence claim. Include `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js` or a stricter runner-covered tenant-admin event-form dependency proof. If infrastructure/auth/target limits block it, record `CJ-03 browser-auth admin` as `blocked`; repository/widget evidence alone cannot close the browser-auth admin lane.
- Orchestration/status lane:
  - Regenerate or refresh the TODO-derived validation matrix whenever the touched TODO set changes.
  - For local principal-checkout reconciliation, use `scripts/delphi/run_reconcile_validation.sh` only after the principal checkout(s) are on `reconcile/*`; otherwise use lane-appropriate direct stage commands and record the lane state.
  - `bash delphi-ai/tools/test_orchestration_status_report.sh --scope big ...` with the frozen required stages and decision-adherence outcomes for this TODO.
  - Roadmap drift lane: before closure, either complete the strategic handoff for the stale `/api/v1/agenda search` roadmap note or record the unresolved owner/status in the final stage report; do not leave it as implicit post-merge cleanup.

---

## External Dependency Readiness

| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| Browser-facing landlord/tenant targets for the current lane | Required for browser smoke evidence on the canonical public validation surfaces. | `healthy` | `2026-04-21` | `cd flutter-app && ./scripts/build_web.sh ../web-app dev` published the current web bundle; `curl -L` against `https://belluga.space` and `https://guarappari.belluga.space` showed embedded `__WEB_BUILD_SHA__=f11cf715`; `run_web_navigation_smoke.sh readonly` passed `5 passed`; `run_web_navigation_smoke.sh mutation` passed `6 passed`. | If a future SHA probe is stale, rerun `cd flutter-app && ./scripts/build_web.sh ../web-app <lane>` and re-probe before marking browser blocked. Browser is blocked only if rebuild/publish fails or the tunnel/domains still do not serve the rebuilt bundle. |
| Mobile device/emulator availability | Required for the frozen `web+mobile` compatibility matrix. | `healthy` | `2026-04-21` | `adb devices -l` found `192.168.15.9:5555` (`moto e13`, Android 13/API 33), and the TODO-scoped Android/mobile integration checklist passed on that device. | Keep the `flutter-device-test-runner` checklist as the mobile evidence artifact; rerun if Flutter integration files or device target change. |
| Local Mongo replica set / safe Laravel runner | Required for authoritative Laravel contract execution. | `healthy` | `2026-04-21` | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventTypesControllerTest.php` passed with `37 passed (181 assertions)`. | Keep using the safe runner; if later preflight fails, treat it as readiness blocker, not product failure. |
| Writable browser/test artifact paths | Browser runner and status-report evidence must not rely on fallback directories. | `healthy` | `2026-04-21` | `test_orchestration_status_report.sh` wrote `foundation_documentation/artifacts/tmp/store-release-critical-journey-regression-gates/orchestration-status-2026-04-21.md`. | Recheck browser runner artifact paths before Playwright execution. |
| Sentry DSN/runtime reporting path | Required for `OBS-01` and `RULE-SENTRY-01` evidence. | `degraded` | `2026-04-21` | Local unit tests prove the project reporter calls `Sentry.captureException`; real Sentry delivery was not exercised because runtime DSN/remote delivery evidence is outside this local lane. | Keep local capture tests and analyzer enforcement as required evidence; real Sentry delivery can be validated separately when environment secrets/transport are available. |

---

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-devops`, `assurance-tester-quality`, `strategic-cto`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `operational-devops` | Browser/public validation targets, artifact readiness, and device/emulator execution topology may require runtime-lane support. | `tools/flutter/**`, published targets, device lane | `completed locally; browser/mobile evidence recorded` |
| `operational-coder` | `assurance-tester-quality` | This TODO closes only with an explicit test-quality audit of the changed suites and evidence matrix. | `laravel-app/tests/**`, `flutter-app/test/**`, `flutter-app/integration_test/**`, `tools/flutter/web_app_tests/**` | `audit executed; classification recorded in closure evidence` |
| `operational-coder` | `assurance-tester-quality` | Touched error-handling/reporting paths need an explicit Sentry audit so noise suppression is classified rather than guessed. | `flutter-app/lib/main.dart`, `flutter-app/lib/application/application_contract.dart`, touched catch/fallback paths | `completed locally; Sentry reporter + analyzer evidence recorded` |
| `operational-coder` | `strategic-cto` | `system_roadmap.md` contains stale `/api/v1/agenda search` language that conflicts with canonical module truth. Operational execution may preserve module truth but cannot silently canonize the roadmap correction. | `foundation_documentation/system_roadmap.md` | `handoff recorded below; no direct roadmap edit in operational profile` |

### Roadmap Drift Handoff

- **Source of drift:** `foundation_documentation/system_roadmap.md` still lists `/api/v1/agenda` as accepting `search`.
- **Operational authority used by this TODO:** `events_module.md`, `agenda_and_action_planner_module.md`, and `tenant_admin_module.md` preserve the MVP rule that public agenda text-search is not part of the release baseline.
- **Execution decision:** this TODO preserved the current module-owned no-text-search behavior and did not reopen or implement public agenda text-search.
- **Strategic handoff:** `strategic-cto` should reconcile `system_roadmap.md` by removing `search` from the active `/api/v1/agenda` request baseline or explicitly reclassifying it as future/non-MVP scope in the correct strategic lane.
- **Closure status:** explicit unresolved handoff recorded; this satisfies the operational TODO requirement without silently canonizing roadmap text from the wrong profile.

---

## Complexity

- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section checkpoints before approval`
- **Why this level:** this is a release-critical cross-stack hardening slice spanning multiple journeys, multiple repositories, browser/public validation, and a required `web+mobile` compatibility matrix.

---

## Questions To Close

- [x] No additional product decision is currently blocking this slice. The main planning correction was contractual realignment: preserve the module-owned no-text-search baseline instead of reopening public agenda text-search via stale tests or roadmap wording.

---

## Decision Baseline (Frozen Before Implementation)

- [x] `D-T01` Release confidence for these journeys requires layered evidence: Laravel contract/feature tests, Flutter repository/controller/screen coverage, and browser/navigation evidence where that surface is part of the journey.
- [x] `D-T02` Compatibility claims for this TODO require both `web` and `mobile` execution evidence; a missing platform is `blocked`, never `passed`.
- [x] `D-T03` Home agenda parity must assert canonical Home agenda request semantics, including `origin_lat` and `origin_lng` on the inspected canonical Home agenda requests when origin-based fetching is in scope, and the public Flutter client must rely on the API-owned default pagination instead of sending `page_size`.
- [x] `D-T04` Public agenda/search-governed coverage preserves the frozen MVP contract: public text-search is not part of the release baseline, and hardening must prove fail-closed semantics for unsupported `search` instead of Atlas-dependent positive behavior.
- [x] `D-T05` Tenant-admin event create/edit flows must load event types from the dedicated `GET /admin/api/v1/event_types` contract, with correct token resolution, payload mapping, and no fallback to generic event list loads.
- [x] `D-T06` No permissive bypass patterns are allowed in touched test paths or runners: no `skip/only`, no status-only assertions where payload behavior matters, no catch-and-continue without assertion, and no silent mock fallback for compatibility claims.
- [x] `D-T07` Final orchestration evidence is an explicit stage report with `passed|failed|blocked`, and preflight/harness/environment defects are evidence-quality blockers first, not automatic product regressions.
- [x] `D-T08` The current checked-out code and observed runtime behavior at execution start are the authoritative regression baseline for this TODO; stale TODO wording or historical evidence cannot justify reverting present behavior.
- [x] `D-T09` Touched exception paths must follow one bounded Sentry rule: `expected_control_flow`, `recoverable_reported`, or `fatal_reported`. Unexpected `debugPrint`-only suppression is forbidden inside the touched scope, and `recoverable_reported` / `fatal_reported` paths must explicitly reach Sentry.
- [x] `D-T10` A project-owned Flutter/Sentry rule must be created or updated so touched unexpected failures cannot be silently swallowed without reporting to Sentry; deterministic analyzer/plugin enforcement is preferred when mechanically safe, otherwise an explicit approved deferral plus review/audit enforcement is required.
- [x] `D-T11` Browser validation is lane-aware: `readonly` may run on `local|dev|stage|main`, `mutation` may run on `local|dev|stage`, `mutation` is blocked on `main`, and targets must be browser-facing domains serving the current integrated state.

---

## Module Decision Baseline Snapshot

| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `FCX-03` | Flutter consumes backend contracts via domain repositories and contract-tested adapters. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `7` |
| `AGD-04` | Agenda/search controllers gate first fetch by canonical effective-origin policy, not inline branching. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` section `7` |
| `AGD-05` | Local distance/radius filtering is forbidden in agenda/search render paths; backend geo filtering is authoritative. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` section `7` |
| `EVS-FILTER-01` | MVP agenda/events listing does not accept text search (`search` is prohibited). | `Preserve` | `foundation_documentation/modules/events_module.md` section `5.4` |
| `EVS-MGMT-01` | Event-form candidate discovery is typed, page-based, and backend-owned. | `Preserve` | `foundation_documentation/modules/events_module.md` decision baseline |
| `TAD-08` | Event types use canonical `visual` and the dedicated event-type registry contract. | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` decision baseline |
| `TAD-11` | Tenant-admin event list operations are server-driven and do not revive direct text search. | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` decision baseline |

---

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The required journey coverage can remain one cohesive release slice instead of splitting into multiple TODOs. | Existing Laravel, Flutter, and browser test surfaces already cluster around these three journeys. | A new independently valuable behavior slice would need its own TODO. | `High` | `Keep as Assumption` |
| `A-02` | Existing Flutter integration surfaces are sufficient to prove at least `CJ-01` and `CJ-02` on real backend without inventing new product seams. | `integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart` and `integration_test/feature_agenda_filters_regression_test.dart` already exist. | The TODO may need one new integration test, but the contract boundary remains the same. | `Medium` | `Keep as Assumption` |
| `A-03` | Public agenda text-search must stay fail-closed because the module baseline is already authoritative even though `system_roadmap.md` is stale. | `events_module.md` section `5.4`, `tenant_admin_module.md` search retirement notes, and existing Flutter filter tests hide search affordances. | Execution would be blocked on a higher-level contract correction before hardening could proceed safely. | `High` | `Keep as Assumption` |
| `A-04` | Browser validation targets are lane-specific and must be resolved before execution from the current validation matrix, README/dependency readiness, or explicit operator target. | Current web policy is lane-aware and `foundation_documentation/artifacts/store-release-reconcile-validation-matrix-2026-04-20.md` requires browser-facing targets that serve the current integrated state. | Browser evidence would be `blocked` until the correct targets are selected. | `High` | `Promote to Decision` |
| `A-05` | The safe Laravel runner plus local Mongo topology remain available for authoritative contract execution. | `laravel-app/scripts/delphi/run_laravel_tests_safe.sh` exists and the testing workflow requires it. | Laravel evidence would be `blocked`; do not replace it with raw `php artisan test`. | `Medium` | `Keep as Assumption` |
| `A-06` | The currently checked-out runtime behavior for the touched journeys/support flows can be observed with enough fidelity to freeze a regression baseline before hardening begins. | Current code, existing tests, and the identified support-path catch/fallback sites provide direct local inspection points. | Execution pauses until the actual current behavior is captured; do not substitute historical TODO wording. | `Medium` | `Keep as Assumption` |
| `A-07` | The current submodule `dev` heads are the correct baseline for this execution pass. | `flutter-app @ f11cf715`, `laravel-app @ 37fd59b`, and related store-release TODOs are marked `Lane-Promoted`. | The baseline must be refreshed before any test or Sentry hardening assertion is interpreted. | `High` | `Keep as Assumption` |
| `A-08` | The current reconcile validation matrix must be refreshed if the active/promotion TODO set changes again before execution. | The matrix itself says to regenerate when the touched TODO set changes, and several adjacent store-release TODOs have moved to `promotion_lane`. | Suite selection could include stale sibling gates or miss newly active blockers. | `High` | `Keep as Assumption` |
| `A-09` | The Sentry no-silent-swallow rule can be enforced at least for mechanically obvious touched catch/fallback patterns. | `flutter-app/tool/belluga_analysis_plugin` already enforces project architecture rules and validates fixture coverage. | If static enforcement is too noisy, delivery must record an explicit deterministic deferral and keep manual review/audit enforcement. | `Medium` | `Promote to Decision` |

---

## Test Coverage Matrix (Frozen Before `APROVADO`)

### `CJ-01` Home agenda reflects backend agenda payload without false empty state

| Layer | Planned Coverage | Test Surface | Evidence / Notes |
| --- | --- | --- | --- |
| Backend contract / feature | Harden existing suite | `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php` | Must prove canonical Home agenda payload semantics and fail loudly on mismatched item presence. |
| Repository / controller state | Harden existing suite | `flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart`, `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart` | Must preserve origin serialization rules, API-default pagination ownership, repository-owned agenda state, and no transient empty-state publication. |
| Screen integration | Revalidate existing real-backend flow | `flutter-app/integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart` | Must prove that the Home agenda renders eligible items from real backend data. |
| Navigation / entry shell | Preserve + harden | `tools/flutter/web_app_tests/navigation.spec.js` via `tools/flutter/run_web_navigation_smoke.sh` | Must inspect the canonical Home `/api/v1/agenda` request and compare UI state against payload reality. |
| Legacy fixture / compatibility case | Keep explicit parse-failure guard | current web smoke parse/contract assertions + DTO contract tests when touched | Malformed or empty-contract payloads must fail the gate, not degrade into a permissive empty-state pass. |

### `CJ-02` public agenda filter/no-search contract remains backend-driven and origin-aware

| Layer | Planned Coverage | Test Surface | Evidence / Notes |
| --- | --- | --- | --- |
| Backend contract / feature | Replace stale positive-search assumptions with fail-closed coverage | `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php` | Public `search` must no longer be treated as a positive MVP capability for this TODO. Preserve categorical/taxonomy/geo assertions and explicit rejection/fail-closed semantics as needed. |
| Repository / controller state | Preserve no-search serialization + backend-owned filters | `flutter-app/test/infrastructure/dal/laravel_schedule_backend_test.dart`, `flutter-app/test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart` | Flutter must not serialize unsupported public text-search and must preserve backend-owned filter/origin behavior. |
| Screen integration | Revalidate filter behavior on real surfaces | `flutter-app/integration_test/feature_agenda_filters_regression_test.dart` | Home and agenda filter surfaces must keep text-search affordances hidden and preserve the current invite/confirmed filter behavior. |
| Navigation / entry shell | Validate route-gated web behavior instead of public agenda content on web | `flutter-app/test/application/router/modules/tenant_public_route_hardening_modules_test.dart`, `flutter-app/test/application/router/guards/web_anonymous_fallback_guard_test.dart` | Anonymous web `/agenda` is intentionally blocked/fallback-governed; browser evidence for this journey is about preserving that boundary, not rendering public agenda search UI. |
| Legacy fixture / compatibility case | Freeze the roadmap drift as explicit debt | TODO handoff + stale roadmap note | No Atlas-dependent public-search claim may survive execution under this TODO. |

### `CJ-03` tenant-admin event create/edit flows load event types from the canonical API contract

| Layer | Planned Coverage | Test Surface | Evidence / Notes |
| --- | --- | --- | --- |
| Backend contract / feature | Preserve dedicated event-type endpoint semantics | `laravel-app/tests/Feature/Events/EventTypesControllerTest.php` | The event-type registry payload and auth path remain canonical. |
| Repository / controller state | Harden dedicated endpoint + dependency loading | `flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart`, `flutter-app/test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart` | Must prove `fetchEventTypes` token resolution/mapping and that `loadFormDependencies` uses the dedicated endpoint instead of generic event-list loads. |
| Screen integration | Revalidate form behavior | `flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` | Form must render and consume event-type options, and fail loudly when dependencies are missing or malformed. |
| Navigation / entry shell | Preserve admin route semantics + require browser-auth admin evidence or explicit block | `flutter-app/test/application/router/support/canonical_route_governance_policy_test.dart`, `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js` | Create/edit route family must keep deterministic admin route behavior while the form dependency path is hardened. The tenant-admin browser mutation suite passed on the current browser target and provides the required real-browser event-type registry evidence for this lane; direct event-form dependency behavior is covered by the Flutter form/controller suites and Android/mobile integration. |
| Legacy fixture / compatibility case | Keep mixed-snapshot fallback forbidden | controller/repository tests above | Event-type dependency loading must not regress to `fetchEvents`, preloaded snapshots, or artist-shaped legacy discovery. |

### `OBS-01` touched unexpected failures do not disappear before Sentry

| Layer | Planned Coverage | Test Surface | Evidence / Notes |
| --- | --- | --- | --- |
| Bootstrap / global error entry | Preserve + verify reporting path | `flutter-app/lib/main.dart` plus touched tests when execution changes the bootstrap contract | Startup failures already capture to Sentry and render a bounded retry UX; execution must preserve visible behavior while keeping reporting explicit. |
| Touched support-flow catch paths | Audit and classify each touched exception path | `flutter-app/lib/application/application_contract.dart`, `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`, plus any newly touched files | Each touched catch/fallback path must be classified as `expected_control_flow`, `recoverable_reported`, or `fatal_reported`; unexpected `debugPrint`-only suppression is forbidden. |
| Regression coverage / audit evidence | Add or extend targeted tests and review artifacts when touched | touched Flutter unit/widget/integration tests + final Sentry audit notes | Sentry hardening must be evidenced, not assumed from code inspection alone, whenever the touched path meaningfully affects release-critical/support flows. |
| Visible UX preservation | Revalidate current user-facing outcome while changing reporting/classification | same journey tests plus manual evidence when needed | Observability hardening must not silently alter the user-visible path beyond explicit error reporting requirements. |

### `RULE-SENTRY-01` project-owned Sentry rule prevents silent swallowed unexpected failures

| Layer | Planned Coverage | Test Surface | Evidence / Notes |
| --- | --- | --- | --- |
| Canonical policy | Create or update project-owned Flutter/Sentry policy | `foundation_documentation/modules/flutter_client_experience_module.md` or `foundation_documentation/policies/**` | The rule must state that app UX may recover silently, but reportable unexpected failures must still reach Sentry. |
| Deterministic enforcement | Prefer analyzer/plugin rule for mechanically obvious `catch` + `debugPrint` / fallback patterns | `flutter-app/tool/belluga_analysis_plugin/**`, `flutter-app/tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`, `cd flutter-app && fvm dart analyze --format machine` | Fixture coverage proves rule activation, but it is not enough by itself. Delivery must also run analyzer/audit against the real touched `lib/**` files or record an explicit approved deferral with review/audit enforcement. |
| Runtime implementation | Apply the rule to touched support paths | `flutter-app/lib/application/application_contract.dart`, `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart` | Touched unexpected failures must call `Sentry.captureException` or equivalent before recovery/fallback. |
| Review evidence | Sentry audit confirms classification and no silent unexpected suppression | final Sentry audit notes + tests where feasible | Delivery cannot rely only on code comments; each touched catch path must be classified. |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-critical-journey-regression-gates.md`
- `foundation_documentation/modules/**` and submodule summaries only when approved execution reveals durable truth that must be promoted
- `foundation_documentation/policies/**` if the Sentry rule lands as project policy instead of module-only wording
- `flutter-app/lib/main.dart`
- `flutter-app/lib/application/application_contract.dart`
- `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`
- `flutter-app/tool/belluga_analysis_plugin/**` if deterministic Sentry enforcement is implemented
- `laravel-app/tests/Feature/Events/**`
- `flutter-app/test/**`
- `flutter-app/integration_test/**`
- `tools/flutter/web_app_tests/**`
- `tools/flutter/run_web_navigation_smoke.sh`

### Ordered Steps

1. `H1 Contract reconciliation`
   - Capture the current checked-out `dev` runtime behavior for the three journeys and any touched supporting error paths before hardening assertions; this becomes the regression baseline.
   - Treat `flutter-app @ f11cf715` and `laravel-app @ 37fd59b` as the current baseline unless execution explicitly rebaselines again.
   - Replace stale public-search/Atlas assumptions in the test plan and touched test files with the canonical module-owned no-text-search baseline.
   - Preserve the carried-forward canonical Home agenda request-scope rule so web/browser parity inspects the correct request, and record drift instead of restoring any obsolete TODO-era behavior.
2. `H2 Laravel hardening`
   - Harden `AgendaAndEventsControllerTest.php` and `EventTypesControllerTest.php` via the safe runner.
   - Fail loudly on payload/contract mismatches and remove any release-confidence claim that still depends on raw search-positive assumptions.
3. `H3 Flutter hardening`
   - Harden repository/controller/screen tests for Home agenda parity, public no-search/filter behavior, and event-type dependency loading.
   - Audit and harden touched Sentry/reporting paths so unexpected failures are explicitly classified and no longer disappear behind local suppression patterns.
   - Establish `RULE-SENTRY-01` as a project-owned Flutter/Sentry rule and implement analyzer/plugin enforcement if the mechanically obvious cases can be checked without unacceptable false positives.
   - Add or adjust non-functional testability helpers only when strictly necessary.
4. `H4 Web/browser hardening`
   - Keep browser smoke focused on canonical Home agenda request semantics, payload/UI parity, route/fallback boundaries, and the current lane-aware browser validation policy.
   - Use `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js` as adjacent browser-admin evidence for `CJ-03`; if direct event-form create/edit dependency evidence remains unavailable, classify that browser-admin sublane explicitly.
5. `H5 Compatibility execution`
   - Refresh the TODO-derived validation matrix if the active/promotion TODO set changes again.
   - Run the frozen stage order: Laravel -> Flutter unit/widget -> Flutter integration -> Sentry rule/audit lane -> Android/mobile device lane -> web/browser lane -> roadmap drift lane -> final status report.
   - Any missing platform becomes `blocked`, never silently downgraded.
6. `H6 Evidence and promotion`
   - Fill `Decision Adherence Validation`, update the final stage-status report, and promote any stable test-governance truth to the correct canonical docs before closure.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** this TODO is explicitly regression and behavior hardening for release-critical journeys; fail-first coverage is practical and materially reduces retrofit-risk.
- **Fail-first target(s):**
  - a canonical Home agenda payload returns items but the client/web lane still shows a false empty state
  - a public agenda/search-governed surface still exposes or silently serializes unsupported public text-search
  - the tenant-admin event form dependency path resolves event types through the wrong endpoint, wrong token path, or mixed fallback
  - a touched unexpected failure still degrades to local `debugPrint` / fallback / no-op behavior and never reaches Sentry or an explicit classification path
  - future touched Flutter code can introduce a `catch` + silent fallback pattern without satisfying `RULE-SENTRY-01`

### Runtime / Rollout Notes

- Product-logic defects discovered here must be opened as dedicated fix TODOs.
- Browser/public validation must stay on the project-designated public targets, not guessed internal hosts.
- A stale browser bundle on those targets is remediated by the project web build script (`flutter-app/scripts/build_web.sh ../web-app <lane>`) before Playwright; stale SHA alone is not enough to skip the browser lane.
- Mobile compatibility evidence is mandatory for closure of this TODO, even if web/browser evidence is already green.
- Sentry hardening must preserve the current visible UX unless a separate approved fix TODO explicitly changes the user-facing behavior.
- Local browser evidence is valid only when the selected browser-facing domain serves the same integrated state being validated; otherwise classify the stage as `blocked`.

---

## Plan Review Gate

### Issue Card `ARCH-REG-01`

- **Severity:** `high`
- **Category:** `Contract / Tests`
- **Evidence:** `foundation_documentation/modules/events_module.md` section `5.4` freezes no public text-search for MVP, while the previous TODO content and parts of `AgendaAndEventsControllerTest.php` still encoded search/Atlas-positive assumptions.
- **Why now:** hardening the wrong contract would make the release gate look stricter while actually reintroducing drift against canonical module truth.
- **Option A (Recommended):** reframe the journey around fail-closed no-text-search coverage and remove Atlas-dependent public-search confidence from this TODO.
  - **Effort:** `medium`
  - **Risk:** `low`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `low`
- **Option B:** keep the stale search-positive framing and defer the conflict until implementation.
  - **Effort:** `low`
  - **Risk:** `high`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Option C:** do nothing.
  - **Effort:** `none`
  - **Risk:** `critical`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Issue Card `ARCH-REG-02`

- **Severity:** `high`
- **Category:** `Compatibility / Orchestration`
- **Evidence:** the previous TODO content listed targeted suites but did not freeze a deterministic `web+mobile` platform matrix or explicit `passed|failed|blocked` status accounting.
- **Why now:** release compatibility can be overstated when partial web-only or unit/widget-only evidence is allowed to masquerade as full closure.
- **Option A (Recommended):** freeze the required platform matrix and final stage report as part of the baseline.
  - **Effort:** `low`
  - **Risk:** `low`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `low`
- **Option B:** keep the suite list informal and classify gaps ad hoc during execution.
  - **Effort:** `none`
  - **Risk:** `high`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `medium`
- **Option C:** do nothing.
  - **Effort:** `none`
  - **Risk:** `critical`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Issue Card `ARCH-REG-03`

- **Severity:** `medium`
- **Category:** `Admin Contract / Tests`
- **Evidence:** existing Flutter tests already prove the correct seam (`tenant_admin_events_repository_test.dart`, `tenant_admin_events_controller_test.dart`), but the old TODO did not freeze the dedicated `GET /admin/api/v1/event_types` dependency path as a baseline rule for the event form.
- **Why now:** event-type regressions can reappear silently if execution falls back to generic event-list or mixed dependency loads.
- **Option A (Recommended):** make the dedicated event-types endpoint, token resolution, and form dependency path an explicit layered gate (`D-T05`).
  - **Effort:** `low`
  - **Risk:** `low`
  - **Blast radius:** `module`
  - **Maintenance burden:** `low`
- **Option B:** rely only on existing widget coverage without freezing the endpoint contract explicitly in the TODO.
  - **Effort:** `none`
  - **Risk:** `medium`
  - **Blast radius:** `module`
  - **Maintenance burden:** `medium`
- **Option C:** do nothing.
  - **Effort:** `none`
  - **Risk:** `high`
  - **Blast radius:** `module`
  - **Maintenance burden:** `medium`
- **Recommendation:** `Option A`

### Issue Card `ARCH-REG-04`

- **Severity:** `medium`
- **Category:** `Evidence Quality / Harness`
- **Evidence:** the previous validation section used raw `php artisan test` language and assumed published browser targets without a readiness classification path.
- **Why now:** harness or environment defects can otherwise be misread as product regressions or, worse, be silently bypassed.
- **Option A (Recommended):** require the safe Laravel runner, dependency-readiness classification, and explicit `blocked` handling for host/device/permission failures.
  - **Effort:** `low`
  - **Risk:** `low`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `low`
- **Option B:** keep environment handling implicit and resolve issues ad hoc during execution.
  - **Effort:** `none`
  - **Risk:** `medium`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `medium`
- **Option C:** do nothing.
  - **Effort:** `none`
  - **Risk:** `high`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Issue Card `OBS-REG-05`

- **Severity:** `high`
- **Category:** `Sentry / Error Reporting`
- **Evidence:** `flutter-app/lib/main.dart` initializes Sentry and already captures bootstrap failures, but touched/supporting paths still use local suppression patterns such as `PackageInfo.fromPlatform()` ignore-on-failure and push-init `debugPrint` in `flutter-app/lib/application/application_contract.dart`, `_refreshAppDataSnapshot()` `debugPrint`-only suppression in `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, and HTML parse fallback suppression in `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`.
- **Why now:** release-critical regression gates lose diagnostic value when unexpected failures are swallowed locally; the app can "pass" visible flows while the root failure never reaches Sentry or deterministic evidence.
- **Option A (Recommended):** freeze one bounded Sentry classification rule for touched paths: `expected_control_flow`, `recoverable_reported`, `fatal_reported`, and forbid unexpected `debugPrint`-only suppression, with explicit Sentry reporting in the latter two classes.
  - **Effort:** `medium`
  - **Risk:** `low`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `medium`
- **Option B:** keep existing local suppression patterns and rely on ad hoc judgment during execution.
  - **Effort:** `low`
  - **Risk:** `high`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Option C:** do nothing.
  - **Effort:** `none`
  - **Risk:** `critical`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Issue Card `RULE-SENTRY-06`

- **Severity:** `high`
- **Category:** `Sentry / Deterministic Enforcement`
- **Evidence:** current touched/supporting Flutter paths still contain `catch` + `debugPrint` / fallback patterns, while the project already owns architecture analyzer rules under `flutter-app/tool/belluga_analysis_plugin/**`.
- **Why now:** fixing only the currently touched catches does not prevent the same silent-swallow pattern from returning in future Flutter work.
- **Option A (Recommended):** add a project-owned Flutter/Sentry policy and deterministic analyzer/plugin enforcement for mechanically obvious silent swallowed unexpected failures.
  - **Effort:** `medium`
  - **Risk:** `medium`
  - **Blast radius:** `flutter`
  - **Maintenance burden:** `medium`
- **Option B:** add policy documentation only and rely on review/audit discipline.
  - **Effort:** `low`
  - **Risk:** `medium`
  - **Blast radius:** `flutter`
  - **Maintenance burden:** `high`
- **Option C:** do nothing beyond one-off code fixes.
  - **Effort:** `none`
  - **Risk:** `high`
  - **Blast radius:** `flutter`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`, with a fallback to `Option B` only if analyzer enforcement proves too noisy and the deferral is explicitly documented.

### Issue Card `ORCH-REG-07`

- **Severity:** `medium`
- **Category:** `Orchestration / Browser Evidence`
- **Evidence:** the current Flutter module and web navigation policy are lane-aware, but older TODO commands still hardcoded `NAV_DEPLOY_LANE=stage` and published stage domains.
- **Why now:** browser evidence can become invalid if it targets the wrong lane or a domain not serving the current integrated state.
- **Option A (Recommended):** derive browser targets from the validation matrix/current execution lane and classify unresolved targets as `blocked`.
  - **Effort:** `low`
  - **Risk:** `low`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `low`
- **Option B:** keep stage-domain commands as the default for all runs.
  - **Effort:** `none`
  - **Risk:** `medium`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `medium`
- **Option C:** do nothing.
  - **Effort:** `none`
  - **Risk:** `high`
  - **Blast radius:** `cross-stack`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Failure Modes & Edge Cases

- API returns eligible Home agenda items but the UI/browser lane still passes via a false empty-state path.
- Browser smoke inspects auxiliary agenda requests instead of the canonical Home agenda request and produces misleading parity results.
- Public agenda/search-governed surfaces reintroduce text-search affordances or silently serialize `search` even though the contract is frozen fail-closed.
- Tenant-admin event create/edit flows load without event types because the dependency path falls back to the wrong endpoint or token source.
- A touched failure path logs locally and falls back silently, so neither tests nor Sentry receive enough evidence to explain the regression.
- The current Sentry fixes are applied manually, but no durable rule prevents later Flutter code from reintroducing silent unexpected suppression.
- Browser validation targets a stale `stage` or non-current domain and produces confidence for the wrong integrated state.
- Mobile device/emulator is unavailable and compatibility is still reported as complete.
- Safe-runner or public-target preflight fails, but execution starts changing product/test code before the readiness issue is classified.

### Residual Unknowns / Risks

- `system_roadmap.md` still carries stale `/api/v1/agenda search` wording and may confuse future executors until the strategic handoff is completed.
- The published browser targets and device lane were not revalidated during this normalization pass.
- Some existing Laravel search-positive tests may need conversion or split before the final hardening suite is coherent with module truth.
- Some current local fallback/error paths may be intentional low-noise control flow; the bounded Sentry rule must classify them explicitly instead of reporting everything indiscriminately.
- Analyzer enforcement for Sentry silent-swallow patterns may need narrow heuristics to avoid flagging intentional `expected_control_flow`; if too noisy, the deterministic rule must be scoped or deferred explicitly instead of bypassed informally.

---

## Additional Architectural Opinions

- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Auxiliary reviewer used:** `yes`

---

## Audit Trigger Matrix

Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo <todo-path> [--json-output <artifact-path>]`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` => `status: ready`, `Overall outcome: go`, fingerprint `57e5453bd687` (`2026-04-21`)

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `big` | Cross-checks the TODO complexity section. |
| `blast_radius` | `cross-stack` | Laravel + Flutter + browser/public validation are all in scope. |
| `behavioral_change_or_bugfix` | `yes` | This slice hardens behavior-defining release gates and regression expectations. |
| `changes_public_contract` | `no` | The TODO is preserving module-owned contracts rather than inventing new ones. |
| `touches_auth_or_tenant` | `yes` | Tenant-public and tenant-admin auth/token boundaries are part of the gate. |
| `touches_runtime_or_infra` | `yes` | Public browser targets, device lane, and canonical runner/topology are part of orchestration. |
| `touches_tests` | `yes` | The slice is test/runners/evidence hardening. |
| `critical_user_journey` | `yes` | All protected paths are release-critical journeys. |
| `release_or_promotion_critical` | `yes` | This TODO is explicitly in the store-release lane. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-REG-01`, `ARCH-REG-02`, `OBS-REG-05`, and `RULE-SENTRY-06` are high severity. |
| `explicit_three_lane_request` | `no` | Triple external audit has not been explicitly requested. |

### Derived Audit Floor

- `Critique`: `required` before `APROVADO` via `wf-docker-independent-critique-method`.
- `Security review`: `required` before completion via `security-adversarial-review`.
- `Performance/concurrency`: `required` via `wf-docker-performance-concurrency-validation-method`.
- `Verification debt`: `required` before completion via `verification-debt-audit`.
- `Test-quality audit`: `required` before completion via `wf-docker-independent-test-quality-audit-method`.
- `Final review`: `required` before completion via `wf-docker-independent-final-review-method`.
- `Triple review`: `required` before completion via `audit-protocol-triple-review` and additive only; it does not replace critique.

---

## Independent No-Context Critique Gate

- **Critique decision:** `required`
- **Why this decision:** the TEACH audit floor classified this TODO as expanded-risk because it is `big`, `cross-stack`, release-critical, test-heavy, touches auth/tenant and runtime/infra validation surfaces, and still carries high-severity planning findings.
- **Impact signals in scope:** `cross-module blast radius`, `critical journey`, `auth/tenant`, `runtime/infra`, `release-critical`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `assumptions preview`, `test coverage matrix`, `execution plan summary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Auxiliary reviewer used:** `yes`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `foundation_documentation/artifacts/tmp/store-release-critical-journey-regression-gates/triple-review/round-02/round-summary.md` => `round_status: clean`, `findings: 0`
- **Critique lenses:** `correctness`, `structural-soundness`, `risk`, `test-quality`
- **Critique status:** `completed`
- **Findings summary:** `2026-04-21 no-context critique returned two high-severity pre-APROVADO blockers and one medium residual risk. Resolution: mobile/Android lane now has concrete runner evidence and blocked semantics; CJ-03 browser-auth admin is mandatory or explicitly blocked; RULE-SENTRY-01 fixture coverage is explicitly insufficient without analyzer/audit over real touched files. Roadmap drift is now an explicit closure lane.`
- **Evidence / reference:** auxiliary no-context critique agent `019dae11-5e55-78b0-81b7-5f006be2cd17`

### Critique Finding Resolution

| Finding | Severity | Resolution | TODO Evidence |
| --- | --- | --- | --- |
| `CRIT-01` Android/mobile lane was required but not concrete enough in the validation matrix. | `high` | `Integrated` | Flutter mobile lane now names the `flutter-device-test-runner` workflow, ADB preflight, resilient runner command template, and required artifact paths. Missing device/emulator is `blocked`, never `passed`. |
| `CRIT-02` `CJ-03` browser-auth admin evidence was still optional despite the matrix calling it the remaining browser gap. | `high` | `Integrated` | Browser lane and `CJ-03` matrix now require browser-auth admin evidence or explicit `blocked`; repository/widget tests cannot close that lane. |
| `CRIT-03` `RULE-SENTRY-01` fixture-only analyzer validation could pass while real touched files still suppress failures. | `medium` | `Integrated` | Sentry rule matrix now requires fixture coverage plus analyzer/audit over real touched files, or an explicit approved deferral with review/audit enforcement. |
| `CRIT-04` Roadmap search drift lacked an executable closure lane. | `medium` | `Integrated` | Orchestration/status lane now includes roadmap drift handling before closure. |

---

## Workstreams

### `WS-00` Contract reconciliation and defect boundary

- [x] 🟣 Lane-Promoted Remove stale public-search/Atlas assumptions from the execution boundary and preserve module-owned no-text-search truth.
- [x] 🟣 Lane-Promoted No product defect was opened in this pass; uncovered gaps were classified as harness/device/target/roadmap blockers, not product failures.

### `WS-01` Home agenda parity and origin semantics

- [x] 🟣 Lane-Promoted Harden the Laravel + Flutter local matrix for canonical Home agenda payload parity.
- [x] 🟣 Lane-Promoted Historical web smoke evidence remains non-closure evidence only; current browser targets were republished through `flutter-app/scripts/build_web.sh` and the current browser lane passed against `__WEB_BUILD_SHA__=f11cf715`.

### `WS-02` Public agenda filter/no-search fail-closed coverage

- [x] 🟣 Lane-Promoted Replace stale public-search-positive assumptions with module-aligned fail-closed coverage.
- [x] 🟣 Lane-Promoted Preserve route/fallback governance for anonymous web `/agenda` instead of misclassifying it as a browser content-rendering journey.

### `WS-03` Tenant-admin event type dependency path

- [x] 🟣 Lane-Promoted Existing repository/controller/form tests cover the local event-type seam and passed in the Flutter unit/widget/controller lane.
- [x] 🟣 Lane-Promoted Freeze and execute the local layered gate so create/edit form dependencies cannot regress to the wrong endpoint or token path; `CJ-03` browser-auth admin passed through `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js` on the current browser target.

### `WS-04` Cross-platform orchestration and evidence

- [x] 🟣 Lane-Promoted Run the frozen stage order and publish the explicit `passed|failed|blocked` status map.
- [x] 🟣 Lane-Promoted Execute Android/mobile device validation through the `flutter-device-test-runner` artifact flow on `192.168.15.9:5555`; all TODO-scoped mobile integration entries passed.
- [x] 🟣 Lane-Promoted Execute `CJ-03` browser-auth admin on the current browser target; tenant-admin event-type create and type-asset browser flows passed in the mutation suite.
- [x] 🟣 Lane-Promoted Capture local decision-adherence evidence; roadmap drift is recorded as an explicit strategic handoff.

### `WS-05` Bounded Sentry hardening

- [x] 🟣 Lane-Promoted Audit the touched exception-handling paths and classify each one as `expected_control_flow`, `recoverable_reported`, or `fatal_reported`.
- [x] 🟣 Lane-Promoted Ensure touched unexpected failures no longer rely on `debugPrint`-only suppression and that the required cases explicitly reach Sentry before the final evidence pass.

### `WS-06` Project Sentry rule and enforcement

- [x] 🟣 Lane-Promoted Establish the project-owned Flutter/Sentry rule/policy for no silent swallowed unexpected failures.
- [x] 🟣 Lane-Promoted Implement analyzer/plugin enforcement for mechanically obvious cases and validate it through the plugin fixture matrix plus the official analyzer.

---

## Existing Evidence Snapshot (Historical / Revalidate Before Closure)

- Flutter repository test historically passed for `fetchEventTypes` token-resolution and payload-mapping coverage.
- Flutter form test historically passed for the tenant-admin event form surface.
- Web navigation smoke historically passed for tenant agenda UI parity and origin query gating on the published browser targets.
- Laravel agenda suite historically passed in a Docker lane, but that evidence is not authoritative for closure because the old plan still carried stale public-search assumptions and did not freeze the safe-runner/orchestration policy.
- Sentry is already initialized for bootstrap failures, but several touched/supporting local catch/fallback paths still suppress unexpected failures with `debugPrint` or silent fallback semantics.
- Current `dev` baseline (`flutter-app @ f11cf715`, `laravel-app @ 37fd59b`) already includes API-default agenda pagination, store-release proximity/media/settings work promoted to `dev`, and the Laravel safe runner required by this TODO.
- Current browser validation policy is lane-aware, so older stage-only browser commands are no longer authoritative as written.

## Execution Evidence (2026-04-21 Local Orchestration)

- **Context readiness:** `bash delphi-ai/verify_context.sh` => `Environment Verified: PACED-Ready`.
- **Sentry rule matrix:** `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` => passed; `success: expected 54 lint codes were detected. total distinct codes emitted: 54`.
- **Flutter analyzer:** `cd flutter-app && fvm dart analyze --format machine` => exit `0`, no diagnostics emitted before and after the Android/mobile test-harness updates.
- **Targeted Sentry tests:** `cd flutter-app && fvm flutter test test/application/observability/sentry_error_reporter_test.dart test/infrastructure/services/push/push_handler_wiring_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` => `+42 All tests passed!`.
- **Flutter unit/widget/controller lane:** `cd flutter-app && fvm flutter test ...` across Sentry, push wiring, tenant-admin settings, agenda DAL/repository/controller, public schedule controller, tenant-admin events controller, and event form suites => `+167 All tests passed!`.
- **Laravel contract lane:** `cd laravel-app && ./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventTypesControllerTest.php` => `37 passed (181 assertions)`.
- **Anti-bypass scan:** `rg -n "\bskip\s*[:(=]|\.only\b|only\s*[:=]"` across touched test/rule-matrix paths => no matches.
- **Flutter integration lane:** passed for the TODO-required mobile target after ADB became available. The prior Linux/Chrome attempts remain harness-limited (`libsecret-1>=0.18.4` missing on Linux; Chrome unsupported for Flutter integration tests), but the real device lane now supplies valid mobile integration evidence.
- **Android/mobile lane:** passed on `192.168.15.9:5555` (`moto e13`, Android 13/API 33), `com.guarappari.app`, flavor `guarappari`, `config/defines/integration.tenant.json`. Passed files: `feature_home_agenda_eligible_events_query_contract_e2e_test.dart` via `drive-fallback`, `feature_agenda_filters_regression_test.dart` via `drive`, and `feature_admin_event_artist_picker_search_pagination_test.dart` via `drive`.
- **Web build publish:** `cd flutter-app && ./scripts/build_web.sh ../web-app dev` => passed; `web-app/index.html`, `https://belluga.space/`, and `https://guarappari.belluga.space/` expose embedded `__WEB_BUILD_SHA__=f11cf715`.
- **Browser readonly lane:** `NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh readonly` => `5 passed (46.0s)`.
- **Browser mutation / `CJ-03` browser-auth admin lane:** `NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` => `6 passed (2.4m)`, including tenant agenda UI/API parity, tenant-admin account-profile media persistence, tenant-admin event-type create, tenant-admin event-type asset persistence, and branding asset persistence.
- **Roadmap drift lane:** passed by explicit operational handoff. `system_roadmap.md` still needs strategic reconciliation for stale `/api/v1/agenda search` wording, but the unresolved owner/status is recorded in this TODO instead of remaining implicit.
- **Device checklist artifact:** `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md` => all TODO-scoped Android/mobile entries marked passed.
- **Status artifact:** `foundation_documentation/artifacts/tmp/store-release-critical-journey-regression-gates/orchestration-status-2026-04-21.md` => final stage map regenerated after roadmap handoff recording.
- **Closure audit artifact:** `foundation_documentation/artifacts/store-release-critical-journey-regression-gates-closure-audit-2026-04-21.md` => test-quality, verification-debt, security, performance/concurrency, final review, and triple-review classifications recorded.
- **Triple review:** `foundation_documentation/artifacts/tmp/store-release-critical-journey-regression-gates/triple-review/round-02/round-summary.md` => `clean`; `0` findings across `elegance`, `performance`, and `test-quality`.
- **Flutter dev promotion:** `belluga_now_front` PR #237 merged to `dev` at `ccb6795a2649417aad1a456eeeea9c53600d8d40`; post-merge run `24730586748` passed `Lane Promotion Policy`, `Validate and Build Web`, and `Publish PR to web-app`.
- **Web dev follow-through:** `belluga_now_web` PR #279 merged to `dev` at `ec2d41b4b4ea78d3b1d59d47aa0a7bcdf706acd5`; post-merge run `24731150194` passed `Lane Promotion Policy` and `navigation` status. The web CI navigation job skipped live navigation because CI targets were missing; local Cloudflare browser readonly/mutation evidence above remains the browser journey proof for this TODO.

---

## Decision Adherence Validation (Post-Implementation)

Populate after `H5/H6`; unresolved `Exception` blocks closure.

| Decision | Status | Module Coherence | Change Intent | Evidence | Notes |
| --- | --- | --- | --- | --- | --- |
| `D-T01` | `adherent` | `Aligned` | `Preserve` | Local Laravel + Flutter lanes passed, Android/mobile integration passed, and browser readonly/mutation passed on current Cloudflare tunnel targets. | Layered journey evidence is complete for the local/dev execution lane. |
| `D-T02` | `adherent` | `Aligned` | `Preserve` | Android/mobile passed on `moto e13`; browser targets served `__WEB_BUILD_SHA__=f11cf715` and readonly/mutation suites passed. | `web+mobile` compatibility was not downgraded. |
| `D-T03` | `adherent` | `Aligned` | `Preserve` | Laravel + Flutter local evidence passed; Home agenda mobile integration passed; browser mutation tenant agenda UI/API parity passed. | Home agenda parity has layered local/dev evidence. |
| `D-T04` | `adherent` | `Aligned` | `Preserve` | Local contract/controller evidence passed; agenda filter/no-search mobile integration passed; browser readonly/mutation passed without reopening public text-search. | Public no-search/fail-closed posture is preserved across the executed lanes. |
| `D-T05` | `adherent` | `Aligned` | `Preserve` | Backend + Flutter event-type dependency suites passed; tenant-admin event form mobile integration passed; browser-auth admin evidence passed through tenant-admin event-type create and type-asset mutation flows. | Browser-auth admin evidence is no longer blocked. |
| `D-T06` | `adherent` | `Aligned` | `Preserve` | Touched test/rule-matrix scan for `skip`/`only` patterns returned no matches; targeted suites passed. | Bypass patterns remain forbidden. |
| `D-T07` | `adherent` | `Aligned` | `Preserve` | Status report artifact records every required stage as `passed` or `blocked`. | Explicit stage accounting is in place. |
| `D-T08` | `adherent` | `Aligned` | `Preserve` | Delivery preserved the current checked-out behavior and changed reporting/test enforcement only. | Historical TODO wording did not override current runtime truth. |
| `D-T09` | `adherent` | `Aligned` | `Preserve` | `SentryErrorReporter` adapter and targeted tests prove recoverable captures for touched push/settings paths; touched rich-text fallback uses the same reporting adapter and is analyzer-covered. | Touched unexpected failures follow the bounded Sentry classification rule. |
| `D-T10` | `adherent` | `Aligned` | `Preserve` | Analyzer plugin rule `flutter_sentry_unreported_debug_print_catch_forbidden` added, documented, registered, and validated by fixture matrix plus official analyzer. | Project-owned Flutter/Sentry enforcement exists for mechanically obvious cases. |
| `D-T11` | `adherent` | `Aligned` | `Preserve` | Browser targets were checked for current-state SHA, republished through the project web build script, rechecked as `f11cf715`, and then validated through readonly/mutation suites. | Browser validation remains lane-aware and target-derived. |

---

## Delivery Confidence Gate

- **Runtime impact:** `medium` (test/runners/evidence hardening plus bounded Sentry/reporting alignment on touched error paths; no intentional visible product behavior change is intended under this TODO)
- **Confidence target:** `high`
- **Readiness outcome:** `promoted-to-dev`

---

## Applicable Rules / Workflows

- `delphi-ai/skills/wf-docker-profile-selection-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-core-instructions-always-on/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-initialization-readiness-model-decision/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-project-mandate-always-on/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `/home/elton/.codex/skills/public/test-creation-standard/SKILL.md`
- `/home/elton/.codex/skills/public/test-orchestration-suite/SKILL.md`
- `/home/elton/.codex/skills/public/test-quality-audit/SKILL.md`
- `delphi-ai/skills/wf-docker-audit-escalation-method/SKILL.md`

---

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)

| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `foundation_documentation/modules/flutter_client_experience_module.md` | Defines Flutter contract-testing posture, compatibility evidence semantics, and repository/controller boundaries. | Real-backend evidence hierarchy and controller/repository ownership. | Claiming compatibility from unit/widget-only evidence or controller-to-controller shortcuts. | Governs Flutter suite selection and interpretation. |
| `foundation_documentation/modules/events_module.md` | Defines the public agenda/events filter baseline and admin event-form discovery semantics. | No public text-search MVP posture and typed backend-owned query behavior. | Reopening Atlas/search-positive public behavior through tests. | Governs Laravel/Flutter agenda hardening. |
| `foundation_documentation/modules/agenda_and_action_planner_module.md` | Defines effective-origin-first fetch and backend-owned agenda/search behavior. | Canonical origin gating and repository-owned agenda semantics. | Inline filter/origin branching or local-only agenda truth. | Governs Home agenda parity assertions. |
| `foundation_documentation/modules/tenant_admin_module.md` | Defines event-type registry contract and server-driven admin filtering posture. | Dedicated `event_types` endpoint and no direct admin search revival. | Generic fallback to event list loads or mixed snapshot preloads. | Governs admin form dependency coverage. |
| `test-creation-standard` | Defines layered coverage matrix and fail-first requirements. | `web+mobile` compatibility requirement and anti-bypass rules. | Retrofitted coverage without clear fail-first targets when practical. | Governs the execution sequence of new/updated tests. |
| `test-orchestration-suite` | Defines canonical stage order and failure classification. | `passed|failed|blocked` accounting and preflight discipline. | Treating harness defects as product failures or product bugs as harness issues without classification. | Governs final execution/reporting. |
| `test-quality-audit` | Defines the no-bypass test-quality floor for touched suites. | Loud failure on payload/contract drift and no compatibility mock fallback. | `skip/only`, status-only assertions, catch-and-continue, flaky-pass closure. | Governs assurance closure before TODO completion. |
