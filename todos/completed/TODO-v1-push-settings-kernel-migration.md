# TODO (V1): Push Settings -> Settings Kernel Migration

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Completed (Phase #1 + Phase #2 delivered)
**Owners:** Backend Team
**Objective:** Complete Push migration to `belluga_settings` as the canonical settings runtime, validating kernel behavior in a real high-traffic module before Events capabilities depend on it.

---

## Scope
- Migrate Push settings read/write flows from legacy `TenantPushSettings` usage to settings-kernel namespace semantics.
- Preserve current tenant/landlord auth and ability boundaries.
- Keep API behavior stable where possible; any intentional change must be documented before implementation.

---

## Out of Scope
- Push message delivery/business flow redesign.
- Flutter integration changes.
- Events capability implementation.

---

## Standards/Exception Reference (Locked)
- Settings kernel authoritative contract:
  - `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md`
- PATCH payload/clear semantics baseline (already converged):
  - direct payload
  - omitted keys unchanged
  - nullable-only clear with `null`
- Validation gate:
  - full Laravel suite in Docker is mandatory for phase completion.

---

## Phase Split (Canonical)

### Phase #1 (Simple, Execute Now)
- Migrate the core `push` namespace consumption to settings kernel:
  - `push.enabled`
  - `push.throttles`
  - `push.max_ttl_days`
- Keep existing push-specific endpoints as compatibility wrappers while internals persist/load through settings kernel.
- Ensure Push services that currently read legacy model use kernel-backed data path for these fields.
- Add/adjust tests to prove parity and kernel usage for this namespace.

### Phase #2 (Follow-up Migration)
- Migrate remaining push settings surfaces to kernel-backed semantics:
  - `firebase`
  - `telemetry`
  - `push.message_routes`
  - `push.message_types`
  - derived status flows affected by these settings
- Remove remaining legacy model-centric settings dependencies/wrappers after cutover.
- Harden with regression coverage for tenant + landlord on-behalf flows.

---

## Execution Snapshot (2026-02-26)
- Phase #1 delivered with kernel bridge adoption for `push` namespace core fields.
- Implemented `PushSettingsKernelBridge` in push package:
  - reads via `SettingsStoreContract` for `tenant/push` namespace
  - writes via `SettingsKernelService::patchNamespace(...)`
- Migrated Phase #1 endpoints/services to kernel-backed path:
  - `TenantPushSettingsController`
  - `TenantPushSettingsAdminController`
  - `TenantPushEnableController`
  - `TenantPushDisableController`
  - `TenantPushStatusController` (enabled check via kernel push namespace)
  - `PushDeliveryService` (`max_ttl_days` resolved from kernel namespace)
- Added feature validation:
  - `PushMessageFlowTest::testTenantPushSettingsPatchIsVisibleInKernelValuesEndpoint`
- Validation gate executed:
  - targeted tests passed
  - full Laravel suite in Docker passed (`790 passed`, `2869 assertions`).
- Extended decisions applied for telemetry core extraction:
  - telemetry ownership moved out of push package into core services/controllers/routes
  - tenant telemetry settings now persisted under `settings.telemetry` namespace (`location_freshness_minutes` + `trackers[]`)
  - telemetry delivery changed to async job pipeline with canonical federation-ready envelope and provider adapters
  - telemetry defaults + taxonomy moved to `config/telemetry.php` (removed `config/belluga_push_handler.php.telemetry` coupling)
  - push delivery now emits telemetry through core contract instead of direct push-local telemetry service.
- Phase #2 delivery completed (2026-02-27):
  - `settings/firebase` migrated to settings kernel namespace `firebase` (tenant + landlord on-behalf endpoints).
  - `settings/push/route_types` + `settings/push/message_types` migrated to kernel-backed storage under `push.message_routes` and `push.message_types`.
  - runtime readers for route/type/firebase in push package moved to `PushSettingsKernelBridge` (controllers, requests, renderer, status/enable/disable, environment resolver).
  - push settings schema registration updated with `firebase` namespace and push route/type fields.
  - targeted regressions added for kernel persistence and landlord on-behalf firebase compatibility.
  - validation gates completed:
    - targeted suites: pass
    - full Laravel suite in Docker: pass (`795 passed`, `2918 assertions`).

---

## Pending Decisions (To Iterate)
- [x] ✅ Production-Ready `P1-01` Compatibility wrappers policy in Phase #1:
  - keep wrappers returning the same payload shape; internals persist/load via settings kernel.
- [x] ✅ Production-Ready `P1-02` Sequence inside Phase #2:
  - migrate `firebase` first, then `route_types/message_types` and derived readers.
- [x] ✅ Production-Ready `P1-03` Legacy `TenantPushSettings` model fate:
  - remove legacy settings-centric runtime reads/writes from push flows in Phase #2; keep the model only as non-authoritative compatibility surface where still referenced outside migrated runtime paths.
- [x] ✅ Production-Ready `P2-01` Telemetry ownership boundary:
  - telemetry moved to core (independent from push package).
- [x] ✅ Production-Ready `P2-02` Telemetry kernel namespace schema (tenant scope):
  - canonical schema is `telemetry.location_freshness_minutes` + `telemetry.trackers[]`.
- [x] ✅ Production-Ready `P2-03` Supported telemetry trackers in V1:
  - `mixpanel + webhook`; `firebase` remains push-only (`FCM`).
- [x] ✅ Production-Ready `P2-04` Telemetry endpoint ability model:
  - dedicated ability `telemetry-settings:update`.
- [x] ✅ Production-Ready `P2-05` Telemetry API compatibility during migration:
  - preserve `/settings/telemetry` contract while migrating internals to core.
- [x] ✅ Production-Ready `P2-06` Legacy telemetry backfill policy:
  - no backfill from `TenantPushSettings.telemetry` (foundational mode, no production legacy).
- [x] ✅ Production-Ready `P2-07` Delivery execution model for telemetry events:
  - mandatory async pipeline via Jobs (with retry/backoff).
- [x] ✅ Production-Ready `P2-08` Shared telemetry consumption contract:
  - one core emission pipeline shared by Push + Invites + Check-in.
- [x] ✅ Production-Ready `P2-09` Telemetry event envelope strategy:
  - canonical envelope is federation-ready (actor/object/target/visibility/idempotency), with provider adapters mapping to Mixpanel/Webhook.

---

## Tasks

### Phase #1 Tasks
- [x] ✅ Production-Ready Map current usage points of `TenantPushSettings` for `push.enabled|throttles|max_ttl_days`.
- [x] ✅ Production-Ready Implement kernel-backed read path for Phase #1 fields in Push services/controllers.
- [x] ✅ Production-Ready Implement kernel-backed write path for Phase #1 fields in existing push settings update flows.
- [x] ✅ Production-Ready Keep endpoint contracts stable while routing persistence to settings kernel.
- [x] ✅ Production-Ready Add/adjust tests proving:
  - Phase #1 fields persist in `settings` root doc under namespace `push`
  - existing push endpoints remain behavior-compatible
  - kernel and push wrappers remain consistent.

### Phase #2 Tasks
- [x] ✅ Production-Ready Migrate `firebase` settings reads/writes to kernel.
- [x] ✅ Production-Ready Migrate `telemetry` settings reads/writes to kernel.
- [x] ✅ Production-Ready Migrate `message_routes` + `message_types` reads/writes to kernel.
- [x] ✅ Production-Ready Remove/neutralize remaining legacy settings-only paths in push package.
- [x] ✅ Production-Ready Add full regression coverage for all migrated settings endpoints and derived status logic.

---

## Validation Steps
- [x] ✅ Production-Ready Phase #1 targeted tests (push + settings suites) pass.
- [x] ✅ Production-Ready `php artisan test` full Laravel suite in Docker (mandatory at Phase #1 completion).
- [x] ✅ Production-Ready Phase #2 targeted tests (including firebase/telemetry/route_types/message_types) pass.
- [x] ✅ Production-Ready `php artisan test` full Laravel suite in Docker (mandatory at Phase #2 completion).

---

## Definition of Done
- [x] ✅ Production-Ready Phase #1 complete: `push` namespace core fields are kernel-backed with compatibility maintained.
- [x] ✅ Production-Ready Phase #2 complete: all push settings surfaces are kernel-backed and legacy settings coupling removed or explicitly documented.
- [x] ✅ Production-Ready Full Laravel suite passes after each phase gate.
- [x] ✅ Production-Ready Foundation documentation reflects final migration state.

---

## Decision Log
- `P1-00`: Created to execute migration in two phases, prioritizing low-risk/high-signal kernel adoption first.
- `P1-01`: Compatibility wrappers remain payload-compatible while persistence moves to kernel.
- `P1-02`: Phase #2 sequence is `firebase` first, then `route_types/message_types`.
- `P1-03`: Legacy `TenantPushSettings` is removed from migrated runtime paths; kernel becomes authoritative for settings IO.
- `P2-01`: Telemetry ownership moved to core (independent from push package).
- `P2-02`: Telemetry namespace canonicalized as `location_freshness_minutes` + `trackers[]`.
- `P2-03`: Telemetry trackers in V1 limited to `mixpanel` and `webhook`; `firebase` stays push-only.
- `P2-04`: Telemetry authorization uses dedicated ability `telemetry-settings:update`.
- `P2-05`: `/settings/telemetry` API contract remains stable during migration.
- `P2-06`: No legacy backfill from `TenantPushSettings.telemetry` in foundational mode.
- `P2-07`: Telemetry delivery is async-only via queued Jobs with retry/backoff policy.
- `P2-08`: Core telemetry emission contract is shared by Push, Invites, and Check-in domains.
- `P2-09`: Telemetry events use a canonical federation-ready envelope; provider-specific payloads are adapter projections.
