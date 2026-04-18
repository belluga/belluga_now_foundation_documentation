# TODO (VNext): Test Hardening Program (High-Confidence Regression Safety)

**Authority note (2026-04-18):** this TODO is the primary deferred owner for test-hardening delivery. `TODO-vnext-test-hardening-defect-backlog.md` is a support registry for discovered functional defects and must not be treated as a parallel owner of the same program boundary.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active (`Planning`)  
**Owners:** Backend Team + Flutter Team + Platform  
**Objective:** Eliminate silent false positives and establish high-confidence regression gates across Laravel + Flutter + Web for critical user journeys.
**Complexity:** `big`  
**Checkpoint policy:** section-by-section checkpoints before execution approval.
**2026-03-18 update:** agenda mutation parity now scopes assertions to the canonical Home agenda request to avoid false negatives from auxiliary agenda fetches.

---

## Goal
Establish a strict automated test baseline where critical regressions are caught before delivery, with explicit cross-stack evidence (backend contract + Flutter behavior + Web navigation) and no permissive pass conditions.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

---

## References
- `foundation_documentation/todos/completed/TODO-v1-flutter-test-foundation.md`
- `foundation_documentation/todos/completed/TODO-v1-events-location-gating-and-tenant-default-origin.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-test-hardening-defect-backlog.md`
- Skill baseline:
  - `/home/elton/.codex/skills/public/test-creation-standard/SKILL.md`
  - `/home/elton/.codex/skills/public/test-orchestration-suite/SKILL.md`
  - `/home/elton/.codex/skills/public/test-quality-audit/SKILL.md`

---

## Scope
1. Harden critical events journey test coverage for:
   - tenant public Home agenda rendering,
   - tenant public agenda/search behavior,
   - tenant admin event type selection/form flow.
2. Add strict assertion semantics (no status-only confidence where payload behavior matters).
3. Add explicit coverage matrix evidence across layers:
   - Laravel feature/contract tests,
   - Flutter repository/controller/screen tests,
   - Web navigation/e2e tests.
4. Add cross-platform compatibility gate evidence (`web` + `mobile`) for critical compatibility claims.
5. Eliminate test bypass risk patterns in touched suites.
6. Strengthen CI/local orchestration so required suites are explicit and auditable (`passed|failed|blocked`).

## Out of Scope
- New product behavior.
- Broad framework migration.
- Production runtime performance benchmarking.

## Execution Rule (Mandatory)
- This TODO is restricted to test quality, coverage, and orchestration hardening.
- If a test exposes a functional defect in product logic:
  - do not fix product logic under this TODO,
  - register the defect in `TODO-vnext-test-hardening-defect-backlog.md`,
  - open a dedicated fix TODO for the defect (MVP/VNext or ephemeral lane, as eligible),
  - mark the affected hardening stage as `blocked` until the fix TODO is approved and completed.
- Non-functional testability helpers are allowed when they do not change runtime behavior (for example stable widget keys).

---

## Critical Journeys Under Protection
1. Tenant public Home must reflect agenda API results (no false empty state when API returns items).
2. Agenda/search must remain backend-driven and semantically aligned with search fields.
3. Tenant admin event form must load and allow selecting event type options from API contract.

---

## Plan Review Gate (Big)

### Issue Card I-01
- **Severity:** High
- **Category:** Tests / Compatibility
- **Evidence:** `flutter-app/.github/workflows/web-artifact-publish.yml` runs unit/widget tests but does not execute Flutter integration flows for compatibility claims.
- **Why now:** Unit/widget green is insufficient to prevent real journey regressions.
- **Options:**
  - **A (Recommended):** Add critical-journey integration coverage and explicit orchestration outputs (`passed|failed|blocked`) before compatibility claims.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **B:** Keep unit/widget only and rely on manual QA.
    - Effort: Low
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High
  - **C:** Do nothing.
    - Effort: None
    - Risk: Critical
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-02
- **Severity:** High
- **Category:** Tests / Assertion quality
- **Evidence:** `tools/flutter/web_app_tests/navigation.spec.js` validates agenda empty-state parity, but does not assert origin query gating semantics (`origin_lat` + `origin_lng`) for agenda requests.
- **Why now:** Root-cause regressions on location gating can pass without this assertion.
- **Options:**
  - **A (Recommended):** Extend navigation/e2e assertions to enforce agenda request origin parameters and environment `default_origin` contract evidence.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Keep API-count vs empty-state only.
    - Effort: None
    - Risk: High
    - Blast radius: High
    - Maintenance burden: Medium
  - **C:** Do nothing.
    - Effort: None
    - Risk: Critical
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-03
- **Severity:** Medium
- **Category:** Tests / Repository contract
- **Evidence:** `flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart` has no dedicated coverage for `fetchEventTypes` token/header and mapping invariants.
- **Why now:** Event type selection regressions can reappear undetected.
- **Options:**
  - **A (Recommended):** Add repository contract tests for `fetchEventTypes` (token resolution path + payload mapping).
    - Effort: Low
    - Risk: Low
    - Blast radius: Low
    - Maintenance burden: Low
  - **B:** Rely on form widget tests with fake repositories.
    - Effort: None
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Do nothing.
    - Effort: None
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High

### Issue Card I-04
- **Severity:** Medium
- **Category:** Tests / Search reliability
- **Evidence:** `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php` search coverage is conditionally skipped when Atlas commands are unavailable.
- **Why now:** Search-path regressions can be hidden in unsupported environments.
- **Options:**
  - **A (Recommended):** Add deterministic no-fallback behavior assertions for unsupported Atlas environments (fail-fast semantics) and keep supported-environment positive assertions.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **B:** Keep current skip-only behavior.
    - Effort: None
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Do nothing.
    - Effort: None
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: Medium

---

## Failure Modes & Edge Cases
- API returns agenda items but UI shows empty state.
- Agenda request is sent without effective origin while UI appears to load normally.
- Event type payload shape changes (id/name/slug) and silently breaks selection.
- Atlas search unavailable in environment and tests pass without validating fail-fast semantics.
- Platform lane partially executed (`web` only) and reported as full compatibility.

---

## Uncertainty Register
- **Assumptions:**
  - `belluga.space` domain routes to local/stage-like environment suitable for real navigation checks.
  - Critical journeys can be asserted via existing route structure and current test harnesses.
- **Unknowns:**
  - Stable mobile device/emulator availability in all execution lanes.
  - Atlas command support consistency across every developer/CI environment.
- **Confidence:** Medium.

---

## Decision Baseline (Frozen)
- `D-T01`: Compatibility confidence for critical journeys requires layered evidence (Laravel contract + Flutter app tests + Web navigation/e2e).
- `D-T02`: Critical journey compatibility claims require both `web` and `mobile` execution evidence; missing platform is `blocked`, never `passed`.
- `D-T03`: For agenda location-gating journey, tests must assert origin query semantics (`origin_lat` + `origin_lng`) in addition to visible UI state.
- `D-T04`: Event-type selection resilience requires explicit repository contract tests for `fetchEventTypes` headers/token path and payload mapping.
- `D-T05`: Search path has no runtime fallback; tests must validate fail-fast behavior when Atlas search support/index is unavailable.
- `D-T06`: No permissive bypass patterns in changed test paths (`skip/only`, status-only semantics where payload behavior is required, catch-and-continue without assertion).

---

## Module Coherence Gate (Mandatory)

Before requesting **APROVADO** and again before TODO closure:
1. Compare each `D-Txx` decision against canonical module docs.
2. Record status per decision: `Aligned`, `Conflict`, or `Supersede`.
3. For `Conflict`/`Supersede`, capture module reference, rationale, and `Preserve|Supersede` intent.
4. Do not execute implementation with unresolved `Conflict`.

---

## Decision Adherence Validation
_Post-implementation adherence validation._

| Decision | Status | Module Coherence | Change Intent | Evidence | Notes |
| --- | --- | --- | --- | --- | --- |
| D-T01 | Exception | Aligned | Preserve | `tools/flutter/web_app_tests/navigation.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh`, `tests/Feature/Events/AgendaAndEventsControllerTest.php`, `test/infrastructure/repositories/tenant_admin_events_repository_test.dart` | Layered evidence run is in progress; remaining blocker is cross-platform completeness (`D-T02`, mobile evidence). |
| D-T02 | Exception | Aligned | Preserve | No mobile execution evidence captured in this checkpoint. | Compatibility cannot be declared complete without web+mobile (or explicit approved exclusion). |
| D-T03 | Adherent | Aligned | Preserve | `tools/flutter/web_app_tests/navigation.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh mutation` (`tenant agenda UI state matches tenant agenda API payload` passed). | Origin query semantics (`origin_lat`/`origin_lng`) are asserted on the canonical Home query scope (`page_size=10`, `past_only=0`, `confirmed_only=0`) to prevent auxiliary-query false negatives. |
| D-T04 | Adherent | Aligned | Preserve | `flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart` (`fetchEventTypes` token path + mapping assertions). | Repository contract coverage added and passing. |
| D-T05 | Adherent | Aligned | Preserve | `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php::testAgendaSearchFailsFastWhenAtlasSearchIsUnavailable`. | Fail-fast semantics verified in non-Atlas environment. |
| D-T06 | Adherent | Aligned | Preserve | Changed test files audited: no `skip/only` bypasses or status-only-only assertions in touched paths. | Gate remains active for future changes. |

---

## Workstreams

### WS-00 Defect Triage Boundary
- [x] ✅ Production-Ready Register every functional defect found during hardening in the dedicated defect backlog.
- [x] ✅ Production-Ready Create linked fix TODOs for each defect before any product-logic correction starts.
- [x] ✅ Production-Ready Mark blocked hardening gates explicitly until linked fix TODOs are resolved.

### WS-01 Critical Journey Coverage Matrix
- [ ] ⚪ Pending Define matrix by journey and layer (backend/repository/screen/navigation).
- [ ] ⚪ Pending Add/adjust tests for Home agenda rendering parity with backend results.
- [ ] ⚪ Pending Add/adjust tests for admin event-type selection contract path.

### WS-02 Assertion Hardening
- [ ] ⚪ Pending Strengthen semantic assertions (payload + behavior, not transport-only).
- [x] ✅ Production-Ready Add explicit origin query gating assertions in web navigation tests.

### WS-03 Search/Atlas Reliability
- [ ] ⚪ Pending Add deterministic no-fallback/fail-fast assertions for unsupported Atlas environments.
- [ ] ⚪ Pending Keep positive search assertions for supported Atlas environments.

### WS-04 Orchestration and Evidence
- [ ] 🟡 Provisional Run required suites and classify each stage (`passed|failed|blocked`).
- [ ] ⚪ Pending Publish compact hardening evidence artifact with decision adherence summary.
- Provisional notes:
  - Flutter repo test: `passed` (`tenant_admin_events_repository_test.dart`).
  - Flutter form test: `passed` (`tenant_admin_event_form_screen_test.dart`).
  - Laravel events suite: `passed` via Docker (`AgendaAndEventsControllerTest.php`), with expected Atlas-positive test skipped in non-Atlas env.
  - Web navigation suite: `passed` for tenant agenda parity + origin gating (`tenant agenda UI state matches tenant agenda API payload`).

---

## Validation Steps (Phase 1 baseline)
- Flutter:
  - `cd flutter-app && fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
  - `cd flutter-app && fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
- Laravel:
  - `cd laravel-app && php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`
- Web:
  - `NAV_DEPLOY_LANE=stage NAV_WEB_TEST_TYPE=readonly NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly`
  - `NAV_DEPLOY_LANE=stage NAV_WEB_TEST_TYPE=mutation NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation`

---

## Delivery Confidence Gate
- **Runtime impact:** medium (test gates only; no product behavior changes expected).
- **Confidence target:** high (if all required stages pass with no unresolved `blocked`).
- **Readiness outcome:** pending.

---

## Applicable Rules/Workflows (approval gate)
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/test-quality-audit/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`
- `delphi-ai/skills/test-orchestration-suite/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`

---

## Definition of Done
- [ ] ⚪ Pending Critical journeys are covered by layered automated tests with strict assertions.
- [ ] ⚪ Pending No bypass patterns in changed test paths.
- [ ] ⚪ Pending Compatibility evidence includes web and mobile (or explicit approved `blocked` status).
- [ ] ⚪ Pending Decision adherence table fully resolved.
- [ ] ⚪ Pending Module consolidation updates completed.
