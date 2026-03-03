# TODO (V1): Events Location Gating + Tenant Default Origin

**Status:** Active (`Validation`)  
**Owners:** Flutter Team + Laravel Team  
**Created:** 2026-03-03  
**Complexity:** `medium`  
**Checkpoint policy:** one full review checkpoint before approval (Plan Review Gate), then implementation.

---

## Goal
Establish a deterministic, backend-aligned event loading flow where agenda/events requests never run without a resolved origin coordinate: first user location when available, otherwise tenant-configured default location.

**Reopen reason (2026-03-03):** Tenant Admin local-preferences UI still does not expose editing for `settings.map_ui.default_origin`.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

---

## Scope
1. Add tenant setting contract for default map/agenda origin under `settings.map_ui`.
2. Expose that setting in `/api/v1/environment` payload and enforce schema support in settings kernel namespace metadata.
3. Update Flutter `AppData` parsing to capture tenant default origin.
4. Refactor event-loading controllers to resolve origin before first fetch:
   - prefer user location (non-interactive warm-up)
   - fallback to tenant default origin when user location is unavailable.
5. Remove local distance/radius filtering from event list rendering path (server-side geo filtering only). Search remains backend-driven.
6. Add/adjust tests in Flutter and Laravel for the new contract and flow ordering.
7. Adopt Atlas Search index strategy for agenda search performance on `event_occurrences` covering:
   - `artists.display_name`
   - `venue.display_name`
   - `content`
   - (`title` remains included for parity and relevance)
8. Route agenda search through Atlas Search (`$search`) as the only runtime search path for this flow (no regex/text fallback).
9. Validate each frozen decision against canonical module docs before implementation and before TODO closure; any conflict must be explicitly classified as `Preserve` or `Supersede`.
10. Add deterministic backend index provisioning for planned query paths:
   - Mongo query indexes via tenant-aware migration on `event_occurrences` (when new supporting indexes are required).
   - Atlas Search index provisioning for `event_occurrences` as versioned/idempotent app-owned migration/provisioning step executed through Spatie tenant migration flow (`tenant_migration_paths` + tenant context), not ad-hoc infra bootstrap.
11. Add Tenant Admin local-preferences editing flow for `settings.map_ui.default_origin` (`lat`, `lng`, optional `label`) using settings-kernel `map_ui` namespace read/patch path, with controller/repository ownership and tests.
12. Tenant Admin default-origin selection must use the same canonical POI/location-picker interaction pattern already used in tenant-admin account/profile/event flows (`TenantAdminLocationPickerRoute` + `TenantAdminLocationSelectionContract` confirmation stream), not a divergent local implementation.

---

## Out of Scope
- Building a new tenant-admin settings IA/navigation workflow beyond adding `default_origin` editing to the existing local-preferences screen.
- Changing event ranking rules beyond location gating.
- Introducing new geolocation permission UX copy/flows.

---

## Definition of Done
- Agenda/event loading in Flutter no longer fetches before origin is resolved (user or tenant fallback).
- No local distance/radius filter is applied after payload fetch in the touched event-loading controllers/repository paths.
- Search remains backend-filtered and uses Atlas Search in production for performance (`artists.display_name`, `venue.display_name`, `content`, `title`), without regex/text fallback path.
- Required Mongo/Atlas indexes for agenda query/search path are provisioned by app tenant migration flow (Spatie), and validated in backend tests.
- `/api/v1/environment` contract includes tenant default origin in `settings.map_ui`.
- Settings schema exposes tenant default origin fields for `map_ui` namespace.
- Tenant Admin local-preferences screen supports editing and saving `settings.map_ui.default_origin`.
- Regression + contract tests pass in both stacks.
- Analyzer remains clean for touched Flutter scope.
- Every frozen decision has explicit module coherence status with evidence; conflicts are explicitly approved as `Supersede` before implementation.

---

## Validation Steps
- Flutter:
  - `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
  - `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
  - `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
  - `fvm flutter analyze`
- Laravel:
  - `php artisan test tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`
  - `php artisan test tests/Feature/Settings/SettingsKernelControllerTest.php`
  - `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`

---

## Applicable Rules/Workflows (for approval gate)
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-contract-alignment-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-domain-workflow-glob/SKILL.md`
- `delphi-ai/skills/flutter-widget-local-state-heuristics/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`

---

## Module Coherence Gate (Mandatory)

Before requesting **APROVADO** and again before closing this TODO:

1. Compare every `D-xx` decision in this TODO against canonical module docs:
   - `foundation_documentation/modules/agenda_and_action_planner_module.md`
   - `foundation_documentation/modules/events_module.md`
   - `foundation_documentation/modules/tenant_admin_module.md`
2. Record one status per decision:
   - `Aligned`: consistent with current module decisions.
   - `Conflict`: inconsistent and unresolved.
   - `Supersede`: intentionally replaces prior module decision (requires explicit approval + module update).
3. For `Conflict` or `Supersede`, document:
   - exact module reference (`file:section/line`),
   - why this TODO changes or challenges prior decision,
   - whether intent is `Preserve` (TODO must change) or `Supersede` (module must change).
4. Implementation cannot proceed with unresolved `Conflict`.
5. TODO cannot close while any decision remains `Conflict`; `Supersede` must be promoted into module docs first.

---

## Plan Review Gate (Medium)

### Issue Card I-01
- **Severity:** High
- **Category:** Architecture / Contract
- **Evidence:** `foundation_documentation/endpoints_mvp_contracts.md` documents `settings.map_ui.radius` only; no default origin field.
- **Why now:** Flutter cannot deterministically apply tenant fallback if contract is absent.
- **Options:**
  - **A (Recommended):** Add `settings.map_ui.default_origin.lat/lng` (and optional `label`) as canonical setting.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium (environment payload + settings schema + consumers)
    - Maintenance burden: Low
  - **B:** Keep contract implicit and parse ad-hoc dynamic keys in Flutter.
    - Effort: Low
    - Risk: High
    - Blast radius: Low short-term / High long-term
    - Maintenance burden: High
  - **C:** Do nothing.
    - Effort: None
    - Risk: Critical (stalls/empty flows remain nondeterministic)
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-02
- **Severity:** High
- **Category:** Behavior / Data flow
- **Evidence:** Controllers fetch page 1 before location readiness and then locally filter by radius.
- **Why now:** Violates required flow (origin-first) and causes inconsistent empty states.
- **Options:**
  - **A (Recommended):** Resolve effective origin before first fetch and pass only backend filters (`origin_lat/lng`, `max_distance_meters`), removing local radius filter.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium (event controllers/repository tests)
    - Maintenance burden: Low
  - **B:** Keep current fetch-first approach with post-fetch local filtering.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Move fallback decision fully to backend when origin is missing, without frontend gating.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium

### Issue Card I-03
- **Severity:** Medium
- **Category:** Performance / Reliability
- **Evidence:** Local filtering can trigger extra auto-pagination loops when filtered list is empty.
- **Why now:** Wasteful requests and unstable perceived loading.
- **Options:**
  - **A (Recommended):** Remove client-side distance filtering and keep pagination decisions based on server-filtered payload.
    - Effort: Low
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Keep local filter and cap auto-pagination attempts.
    - Effort: Low
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Do nothing.
    - Effort: None
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium

### Issue Card I-04
- **Severity:** Medium
- **Category:** Performance / Search behavior
- **Evidence:** Current agenda search relies on regex filtering; expected fields are `artists.display_name`, `venue.display_name`, and `content` (with `title` parity).
- **Why now:** Regex search does not scale predictably; Atlas Search is the documented high-performance path for multi-field full-text queries.
- **Options:**
  - **A (Recommended):** Adopt Atlas Search (`$search`) with dedicated index on `event_occurrences` for `title/content/artists.display_name/venue.display_name` as the only runtime search path.
    - Effort: Medium
    - Risk: Low to Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **B:** Keep regex search and only tune indexes around non-text filters.
    - Effort: Low
    - Risk: High under growth
    - Blast radius: Medium to High
    - Maintenance burden: High
  - **C:** Do nothing.
    - Effort: None
    - Risk: Critical under growth
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-05
- **Severity:** High
- **Category:** Performance / Operational readiness
- **Evidence:** Current events query service still creates geo index at runtime (`ensureGeoOccurrenceIndex` in query path), and Atlas Search index lifecycle is not yet codified as migration/infra provisioning.
- **Why now:** Planned `$search` + geo queries need deterministic index lifecycle; runtime index creation on request path is operationally unsafe and can cause latency spikes/failures.
- **Options:**
  - **A (Recommended):** Add explicit tenant migration-flow provisioning for required Mongo + Atlas Search indexes and remove runtime index creation from query execution path.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Keep runtime `createIndex` guard and provision Atlas Search manually outside migration flow.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High
  - **C:** Keep current behavior.
    - Effort: None
    - Risk: Critical under load/scale
    - Blast radius: High
    - Maintenance burden: High

---

## Failure Modes & Edge Cases
- Tenant settings missing `default_origin`: flow must fail safely (no infinite loading; explicit empty/error state path preserved).
- Invalid coordinates in settings: fallback ignored and logged; no crash.
- User denies geolocation permission: fallback origin must still drive query.
- SSE reconnect path must keep using current effective origin.
- Radius bounds updates must continue clamping without reintroducing local distance filtering.
- Missing Atlas Search index at runtime must fail fast with explicit backend error/telemetry (no silent regex fallback).

---

## Uncertainty Register
- **Assumptions:**
  - Tenant settings kernel is the canonical place for this configuration in V1.
  - Existing `/api/v1/environment` consumers tolerate additional keys in `settings.map_ui`.
- **Unknowns:**
  - Whether all tenants currently have writable `map_ui` settings pre-seeded.
  - Whether any hidden client flow still depends on local radius filtering semantics.
- **Confidence:** Medium-high.

---

## Decision Baseline (Frozen)
- `D-01`: Canonical tenant fallback origin lives at `settings.map_ui.default_origin` (`lat`, `lng`, optional `label`).
- `D-02`: Flutter must resolve effective origin before first event fetch (`user location` -> `tenant default origin`).
- `D-03`: If neither user location nor tenant default origin is valid, agenda/event search must not fetch and must finalize loading with explicit controlled error state (no infinite spinner).
- `D-04`: Remove local distance/radius filtering from agenda/search paths; controller layer only delegates backend query parameters and must not execute local geo filtering.
- `D-05`: Environment + settings schema contracts must be updated before Flutter code changes.
- `D-06`: Search filtering is backend-owned and must include `artists.display_name`, `venue.display_name`, `content` (plus `title` parity) with test coverage.
- `D-07`: Search performance path is Atlas Search (`$search`) with a dedicated `event_occurrences` index using language analyzer strategy aligned to Portuguese/Brazilian content.
- `D-08`: There is no regex/text fallback runtime path for this feature.
- `D-09`: When combining text search + geo constraints, pipeline must use Atlas Search as first stage (`$search` with `compound` + geo clause) instead of local filtering.
- `D-10`: Agenda search results keep chronological agenda ordering (`starts_at`) after textual/geospatial match resolution.
- `D-11`: Index lifecycle is deterministic and app-owned under Spatie multitenancy: required Mongo query indexes + Atlas Search index must be provisioned via tenant migration flow (`tenant_migration_paths`), and query runtime must not create indexes.
- `D-12`: Tenant Admin local-preferences must expose and persist `settings.map_ui.default_origin` through settings-kernel (`map_ui`) endpoints, with controller-owned state and no widget-level backend access.
- `D-13`: Tenant Admin local-preferences default-origin selection must reuse the same POI/location-picker pattern used by tenant-admin account/profile/event location flows (shared picker route + shared confirmation stream contract).

---

## Decision Adherence Validation
_Post-implementation adherence validation._

| Decision | Status | Module Coherence | Change Intent | Evidence | Notes |
| --- | --- | --- | --- | --- | --- |
| D-01 | Adherent | Aligned | Supersede (promoted) | `laravel-app/packages/belluga/belluga_map_pois/src/MapPoisServiceProvider.php`, `laravel-app/app/Http/Api/v1/Controllers/EnvironmentController.php`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/endpoints_mvp_contracts.md` | `settings.map_ui.default_origin` is now canonical in schema + environment contract + module docs. |
| D-02 | Adherent | Aligned | Preserve | `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`, `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart` | First fetch now waits for effective origin resolution. |
| D-03 | Adherent | Aligned | Preserve | `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`, `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`, `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`, `flutter-app/test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart` | When origin is unavailable, loading is finalized without request loop. |
| D-04 | Adherent | Aligned | Preserve | `flutter-app/lib/infrastructure/repositories/schedule_repository.dart`, `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`, `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`, `foundation_documentation/modules/agenda_and_action_planner_module.md` | Local radius filtering removed from agenda/search paths. |
| D-05 | Adherent | Aligned | Preserve | `foundation_documentation/endpoints_mvp_contracts.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/agenda_and_action_planner_module.md`, `flutter-app/lib/domain/app_data/app_data.dart` | Contracts/modules were promoted and aligned with delivered implementation. |
| D-06 | Adherent | Aligned | Preserve | `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php` | Backend search fields include `title`, `content`, `artists.display_name`, `venue.display_name`. |
| D-07 | Adherent | Aligned | Supersede (promoted) | `laravel-app/packages/belluga/belluga_events/database/migrations/2026_03_03_000400_provision_event_occurrences_atlas_search_index.php`, `foundation_documentation/modules/events_module.md` | Atlas Search index strategy is promoted to canonical module baseline. |
| D-08 | Adherent | Aligned | Preserve | `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`, `foundation_documentation/modules/events_module.md` | Runtime regex/text fallback path is removed. |
| D-09 | Adherent | Aligned | Supersede (promoted) | `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`, `foundation_documentation/modules/events_module.md` | Text+geo query planning is `$search` first stage. |
| D-10 | Adherent | Aligned | Preserve | `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`, `foundation_documentation/modules/agenda_and_action_planner_module.md` | Agenda ordering remains chronological (`starts_at`). |
| D-11 | Adherent | Aligned | Supersede (promoted) | `laravel-app/packages/belluga/belluga_events/database/migrations/2026_03_03_000400_provision_event_occurrences_atlas_search_index.php`, `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`, `foundation_documentation/modules/events_module.md` | Index lifecycle moved to deterministic tenant migration flow; runtime index creation removed. |
| D-12 | Adherent | Aligned | Preserve | `laravel-app/config/belluga_settings.php`, `laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php`, `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`, `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_local_preferences_section.dart`, `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_local_preferences_screen.dart`, `flutter-app/test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`, `flutter-app/test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` | Local-preferences now loads/saves `settings.map_ui.default_origin` through `/admin/api/v1/settings/values/map_ui` via repository/controller flow with explicit tests. |
| D-13 | Adherent | Aligned | Preserve | `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`, `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_local_preferences_screen.dart`, `foundation_documentation/screens/modulo_tenant_admin.md` | Default-origin selection now reuses shared tenant-admin location picker contract (`TenantAdminLocationPickerRoute` + confirmed stream). |

---

## Delivery Confidence Gate
- **Runtime impact:** `medium` (agenda search/index/query path + environment payload contract).
- **Migration/index status:** Atlas Search provisioning migration added for tenant flow (`2026_03_03_000400_provision_event_occurrences_atlas_search_index.php`).
- **Queue/scheduler/worker health:** N/A for this TODO scope (no queue topology or scheduler contract changes introduced).
- **Targeted perf/load sampling:** N/A in current execution lane; baseline safeguarded by Atlas Search-first query plan and deterministic index provisioning.
- **Smoke flow:** validated through targeted controller/repository tests and feature/API tests in Flutter + Laravel.
- **Confidence:** `medium-high`.
- **Residual risks:** environments without Atlas command support can skip specific search assertions in test lanes; production must keep index provisioning active.
- **Readiness outcome:** `ready_with_waiver` (awaiting manual tenant smoke by operator after changing `default_origin` in admin settings).

---

## Module Consolidation Gate
- Promoted superseded/aligned decisions to canonical modules:
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- Promoted endpoint contract updates:
  - `foundation_documentation/endpoints_mvp_contracts.md`
- Result: reopened delta (`D-12` + `D-13`) is now implemented and marked `Adherent`; final close is pending manual tenant smoke in this execution lane.
