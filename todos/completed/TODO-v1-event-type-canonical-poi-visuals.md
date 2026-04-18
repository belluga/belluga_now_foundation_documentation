# TODO (V1): Event Type Canonical POI Visuals

**Status:** Active
**Current delivery stage:** `Implemented locally`
**Qualifiers:** `TDD-Executed`, `Manual-Smoke-Pending`
**Next exact step:** Run manual tenant-admin + public-map smoke for icon, `cover`, and `type_asset` event-type visuals, then close or reopen on evidence.
**Owners:** Laravel Team, Flutter Team
**Objective:** Establish `event_types` on the same canonical `visual` contract already used by `account_profile` and `static` types, propagate that visual deterministically into `events`, `event_occurrences`, and `map_pois`, and expose the same type-visual editing capability in Flutter tenant-admin with fail-first automated coverage.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `laravel`, `flutter`

**Direct-to-TODO rationale:** safe. This is already one bounded cross-repo slice with one primary objective: eliminate the legacy `event_types` visual contract drift and bring event POI visuals onto the same canonical type-visual model already approved for other POI-enabled types.
**Last confirmed truth:** `2026-04-09` local implementation now persists canonical `event_types.visual`/`poi_visual`, propagates the visual snapshot into `events` + `event_occurrences`, rematerializes `event` `map_pois` from the canonical event-type contract, and exposes the shared visual editor in Flutter tenant-admin. Manual admin/map smoke is still pending.

---

## Scope Ownership

- **Primary environment:** `tenant`
- **Primary main scope:** `tenant_admin`
- **Secondary consuming scope:** `tenant_public`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/admin/events/types` | tenant domain | `tenant` | `tenant_admin` | `n/a` | tenant-admin operator |
| `/admin/events/types/edit` | tenant domain | `tenant` | `tenant_admin` | `n/a` | tenant-admin operator |
| `/mapa` | tenant domain | `tenant` | `tenant_public` | `n/a` | tenant public session |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/events_module.md`
- **Secondary:** `foundation_documentation/modules/map_poi_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/flutter_client_experience_module.md`

### Decision Consolidation Targets

- Promote stable event-type visual contract decisions into `foundation_documentation/modules/events_module.md`.
- Promote stable map projection visual rules for `event` POIs into `foundation_documentation/modules/map_poi_module.md`.
- Promote tenant-admin event-type editor expectations into `foundation_documentation/modules/tenant_admin_module.md` only if this lane changes the enduring admin contract.

---

## References

- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Models/Tenants/EventType.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Events/EventTypeRegistryService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Events/EventTypeRegistryManagementService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Shared/MapPois/PoiVisualNormalizer.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_map_pois/src/Application/MapPoiProjectionService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Events/EventTypesControllerTest.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/tenant_admin/tenant_admin_poi_visual.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/tenant_admin/tenant_admin_event/tenant_admin_event_type.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_events_repository_contract.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_events_repository.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_event_type_form_screen.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_types_list_screen_test.dart`

---

## Scope

- Replace the legacy `event_types` visual contract (`icon`, `color`, `icon_color`) with the canonical type-visual contract already used by `account_profile` and `static` types: `visual` as source of truth and `poi_visual` as compatibility mirror where needed.
- Extend Laravel event-type create/update/read flows to accept and emit canonical visual payloads.
- Propagate the resolved canonical visual into embedded `event.type` and `event_occurrences.type` snapshots on event-type updates.
- Materialize event `map_pois.visual` from the canonical event-type visual contract instead of the hard-coded icon-only resolver.
- Support event-type image-mode semantics for POI projection with deterministic source rules:
  - `cover` uses event-owned cover/thumb media
  - `type_asset` uses the canonical type-owned asset
- Extend Flutter tenant-admin event-type domain/repository/controller/screen flows to use the shared `TenantAdminPoiVisual` model and editor semantics instead of the current legacy-only fields.
- Prove the migration with fail-first Laravel + Flutter tests before implementation.

## Out of Scope

- Redesigning public map deck/card UI.
- Changing event occurrence ordering, publication semantics, or attendance policy behavior.
- Reworking event detail visuals outside whatever additive payload support is strictly required by the canonical visual contract.
- Ticketing, invites, or unrelated event-management editor work.
- Any client-side fallback that bypasses the canonical type visual contract.

---

## Decision Baseline (Frozen)

- `D-01`: `event_types` must join the same canonical type-visual model already approved for `account_profile` and `static` types. New work must treat `visual` as the source of truth, not `icon/color/icon_color`.
- `D-02`: `event_types` read/write payloads may continue to expose legacy top-level `icon/color/icon_color` as compatibility fields for icon-mode snapshots, but Laravel and Flutter admin flows must no longer use them as the authoritative editing contract.
- `D-03`: Event-type `visual.mode=image` is valid and uses the canonical `image_source` model. For events, the allowed sources are `cover` and `type_asset`; `avatar` is invalid for this type family.
- `D-04`: Event `map_pois.visual` must be derived from the canonical event-type visual contract using the same projection rules already implemented for `account_profile` and `static` types, not from a separate event-only visual path.
- `D-05`: Updating an event type must propagate the canonical visual snapshot into `events`, `event_occurrences`, and all affected `event` `map_pois`.
- `D-06`: Flutter tenant-admin event-type editing must reuse the shared `TenantAdminPoiVisual` model and UI composition pattern instead of introducing a third bespoke visual editor.
- `D-07`: Delivery is test-first. Laravel and Flutter fail-first coverage for the canonical visual contract is mandatory before implementation.

---

## Plan Review Gate (Medium)

### Issue Card P-01 — Event types still live on a legacy visual contract
- Severity: `high`
- Evidence:
  - `laravel-app/app/Models/Tenants/EventType.php` only persists `icon`, `color`, `icon_color`
  - `EventTypeRegistryService` and `EventTypeRegistryManagementService` only read/write legacy fields
  - Flutter `TenantAdminEventType` and event-type form expose only `name/slug/description/icon/color`
- Why now: this contract drift prevents event POIs from using the same canonical visuals model already approved for other POI-enabled type systems.
- Option A: keep legacy fields and add more event-only branches in map projection.
- Option B (recommended): migrate event types onto the canonical `visual` contract and treat legacy fields as derived compatibility only.

### Issue Card P-02 — Event POI projection still has an icon-only resolver
- Severity: `high`
- Evidence:
  - `MapPoiProjectionService::resolveProjectionVisual()` already supports canonical `icon|image`
  - `MapPoiProjectionService::resolveEventProjectionVisual()` still reads only `type.icon/color/icon_color`
- Why now: even if admin started saving canonical visuals, the current projection path would silently collapse event POIs back to icon-only behavior.
- Option A: keep the event-specific resolver and patch image behavior ad hoc.
- Option B (recommended): align event projection with the same canonical visual-resolution path used for `account_profile` and `static`.

### Issue Card P-03 — Image-mode semantics for events need one canonical rule
- Severity: `medium`
- Evidence:
  - shared `TenantAdminPoiVisual` supports `avatar|cover|type_asset`
  - events have no avatar-equivalent; they do have cover/thumb media and can also support type-owned assets
- Why now: without freezing allowed sources, the implementation can drift between admin, snapshots, and map projection.
- Option A: restrict event types to icon-only forever.
- Option B (recommended): allow canonical image mode with `cover` and `type_asset`, rejecting `avatar`.

### Issue Card P-04 — Partial migration would be unsafe without TDD
- Severity: `high`
- Evidence:
  - event-type visual state crosses Laravel registry CRUD, snapshot propagation, map projection materialization, Flutter repository decoding, and tenant-admin form behavior
- Why now: the easiest failure mode here is a “looks saved in admin, but POI/runtime still uses stale legacy fields” regression.
- Required answer: fail-first tests must pin the contract across all those layers before implementation begins.

---

## Failure Modes & Edge Cases

- Event types updated to image mode must still rematerialize `map_pois` even when the event already exists and the occurrence projection is warm.
- `image_source=cover` must degrade safely when the event has no cover/thumb; projection should not emit a broken image visual.
- `image_source=type_asset` must degrade safely when no type asset exists; projection should not emit a broken image visual.
- Legacy icon-mode event types must continue to round-trip without losing icon-color semantics.
- Event detail and admin list payloads must not silently diverge on the stored visual snapshot after an event-type update.

## Uncertainty Register

- Assumption: event-type image-mode needs parity with the shared canonical visual model, including `type_asset`, because the user explicitly requested the same pattern already used by `account_profile` and `static` types.
- Confidence: `medium-high`

---

## Touched Surfaces

- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-event-type-canonical-poi-visuals.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Models/Tenants/EventType.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Events/EventTypeRegistryService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Events/EventTypeRegistryManagementService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_map_pois/src/Application/MapPoiProjectionService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Events/EventTypesControllerTest.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/tenant_admin/tenant_admin_event/tenant_admin_event_type.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_events_repository_contract.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_events_repository.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_event_type_form_screen.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_types_list_screen_test.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`

## Ordered Steps

1. Add fail-first Laravel tests for canonical `event_types.visual` create/update/read behavior and for `map_pois` rematerialization under icon mode, `cover`, and `type_asset`. Completed `2026-04-09`.
2. Add fail-first Flutter tests for event-type request encoding, response decoding, repository persistence, controller form state, and tenant-admin event-type UI behavior using `TenantAdminPoiVisual`. Completed `2026-04-09`.
3. Extend Laravel `EventType` persistence and registry payloads to store/emit canonical `visual` and compatibility `poi_visual`. Completed `2026-04-09`.
4. Propagate the resolved canonical visual into `events`, `event_occurrences`, and `event` `map_pois` on event-type updates. Completed `2026-04-09`.
5. Extend Flutter tenant-admin event-type domain/repository/controller/form flows to edit and persist canonical visuals. Completed `2026-04-09`.
6. Run focused Laravel + Flutter suites and `fvm dart analyze --format machine`. Completed `2026-04-09`.
7. Promote stable contract decisions into canonical module docs before closure. Completed `2026-04-09`.

## Implementation Evidence

- `2026-04-09`: fail-first Flutter evidence captured before implementation: missing `TenantAdminEventType.visual`, missing `createEventTypeWithVisual`, and missing `updateEventTypeWithVisual` in the contract/repository path.
- `2026-04-09`: fail-first Laravel evidence captured before implementation: canonical `visual.mode=image` create test returned `data.visual.mode == null` until the backend contract was implemented.
- `2026-04-09`: focused Laravel suites passed locally after implementation:
  - `tests/Feature/Events/EventTypesControllerTest.php`
  - `tests/Feature/Events/EventCrudControllerTest.php`
- `2026-04-09`: focused Flutter suites passed locally after implementation:
  - `test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart`
  - `test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart`
  - `test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
  - `test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart`
  - `test/presentation/tenant_admin/events/tenant_admin_event_type_form_screen_test.dart`
  - `test/presentation/tenant_admin/events/tenant_admin_event_types_list_screen_test.dart`
  - `test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
  - `test/infrastructure/services/http/laravel_map_poi_http_service_test.dart`
- `2026-04-09`: `fvm dart analyze --format machine` passed in the Flutter lane after fixing the event-type form async-context path and the integration fake repository contract drift.

## Test Strategy

- `test-first`

## Fail-First Targets

- Laravel `event_types` endpoints reject or ignore canonical `visual` payloads.
- Laravel `event_types` responses do not expose canonical `visual`/`poi_visual`.
- Updating an event type to image mode does not rematerialize related `event` `map_pois` with the expected image visual.
- Flutter event-type request/response paths drop canonical `visual` data.
- Flutter tenant-admin event-type form cannot compose, display, and save canonical visuals.

## Definition of Done

- Event types accept and return canonical `visual` payloads in Laravel and Flutter tenant-admin.
- Event type updates propagate canonical visual snapshots into `events`, `event_occurrences`, and affected `event` `map_pois`.
- Event POIs can use canonical event-type visuals with `icon`, `cover`, or `type_asset` behavior as approved.
- Fail-first Laravel and Flutter suites prove the end-to-end contract.
- `fvm dart analyze --format machine` passes in the Flutter lane.

## Validation Steps

- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventTypesControllerTest.php tests/Feature/Events/EventCrudControllerTest.php`
- `fvm flutter test test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_types_list_screen_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
- `fvm dart analyze --format machine`
- Manual smoke: tenant-admin create/edit event type in icon mode.
- Manual smoke: tenant-admin create/edit event type in image mode using `cover` and `type_asset`.
- Manual smoke: public map event POIs reflect the saved event-type visual after rematerialization.
