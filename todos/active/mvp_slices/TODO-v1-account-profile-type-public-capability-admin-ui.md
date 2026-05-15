# TODO (V1): Account Profile Type Public Discoverability Admin UI

**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-05-15

## Objective
Establish tenant-admin support for editing the existing account profile type capability `capabilities.is_publicly_discoverable` so it becomes editable in the profile-type UI and governs `is_favoritable` with the same parent-child interaction pattern already used by `is_poi_enabled -> is_reference_location_enabled`.

## Framing Source
- `Direct-to-TODO`
- Primary story slice: tenant-admin profile type capabilities UI + Flutter contract parity for the existing `is_publicly_discoverable -> is_favoritable` dependency.

## References
- [foundation_documentation/modules/tenant_admin_module.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/modules/tenant_admin_module.md)
- [foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md)
- [flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart)
- [flutter-app/lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart)
- [flutter-app/lib/domain/tenant_admin/tenant_admin_profile_type_capabilities.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/tenant_admin/tenant_admin_profile_type_capabilities.dart)
- [flutter-app/lib/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto.dart)
- [flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_account_profiles_request_encoder.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_account_profiles_request_encoder.dart)
- [laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php)
- [laravel-app/app/Application/AccountProfiles/AccountProfileRegistryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileRegistryService.php)
- [laravel-app/database/migrations/tenants/2026_05_01_000400_backfill_public_discovery_profile_type_capability.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/database/migrations/tenants/2026_05_01_000400_backfill_public_discovery_profile_type_capability.php)

## Canonical Module Anchors
- Primary module: `foundation_documentation/modules/tenant_admin_module.md`
- Secondary module: none
- Decision consolidation target: update `tenant_admin_module.md` so the tenant-admin profile type request/response schemas and field definitions include `capabilities.is_publicly_discoverable` and its dependency on `is_favoritable`.

## Execution Trace
- Primary execution profile: `Operational / Coder`
- Active technical scope: `cross-stack`
- Branch assignment: follow-on lane; not bound to the active map branch by default

## Cross-TODO Orchestration
- Sibling tactical TODO: [TODO-v1-map-initial-origin-bootstrap.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/mvp_slices/TODO-v1-map-initial-origin-bootstrap.md)
- Orchestration role: `Wave 2 / follow-on lane`
- Primary sequencing principle:
  - keep this slice independent from the map bootstrap contract even though both are active in the same implementation cycle
  - default execution order is after the map bootstrap slice closes on its existing lane
- Sequencing decision:
  - serial execution approved on `2026-05-15`
  - this TODO is the approved second slice in the two-TODO sequence
- Dependency map:
  - no functional dependency on the map bootstrap implementation
  - shared `flutter-app` ownership means this slice should not silently piggyback on the map-specific branch
- Branch rule:
  - derive a fresh implementation lane from the updated baseline after the map slice lands, unless the user explicitly approves mixed-lane execution
- Approval rule:
  - this TODO keeps its own `APROVADO` gate; approval for the map slice does not authorize this slice

## Scope
- Expose the existing `capabilities.is_publicly_discoverable` field as an editable switch in the tenant-admin account profile type form.
- Extend Flutter tenant-admin profile type domain/DTO/controller/request-encoding paths so the public-discovery capability round-trips correctly.
- Make `is_favoritable` dependent on `is_publicly_discoverable`, following the same interaction model already used by `is_reference_location_enabled` behind `is_poi_enabled`.
- Ensure disabling public discovery forces `is_favoritable` back to `false`.
- Ensure the `is_favoritable` control is disabled while public discovery is off and shows explanatory copy consistent with the existing POI/reference-origin pattern.
- Keep the implementation bound to the existing backend capability name; do not introduce a parallel `is_public` field or alias in the profile type contract.
- Sync the canonical tenant-admin module docs so the admin API schemas and field definitions reflect the public capability.
- Add focused Flutter regression coverage for DTO normalization, controller capability coupling, and form-screen behavior.

## Out of Scope
- Changing backend public discovery semantics, seeding policy, or migration defaults.
- Changing public discovery or favorites behavior outside the tenant-admin profile type editor.
- Introducing a new profile type capability name for this behavior.
- Any redesign of static profile type capability editing.
- Bundling this slice into the map bootstrap implementation without a separate approval pass.

## Definition of Done
- Tenant-admin profile type create/edit screens expose a public-discovery toggle backed by `capabilities.is_publicly_discoverable`.
- `is_favoritable` cannot remain enabled when public discovery is disabled.
- Re-enabling public discovery does not silently re-enable favorites; favorites remain operator-controlled once the parent toggle is on again.
- Flutter profile type parsing and request encoding preserve `is_publicly_discoverable`.
- No new public-visibility flag is introduced; the UI uses the existing backend-backed capability only.
- The tenant-admin module docs list `is_publicly_discoverable` in the profile type admin request/response schemas and field definitions.
- Focused regression tests cover the capability dependency and disabled-state UI behavior.

## Validation Steps
- `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`
- `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`
- `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`
- `fvm dart analyze --format machine`
- Manual smoke:
  - edit a profile type with public discovery off and confirm favoritable is cleared/disabled
  - turn public discovery on and confirm favoritable becomes selectable again without auto-opting-in
  - save and reload the profile type to confirm the capability persists

## Complexity
- `small`
- Checkpoint policy: one consolidated review before delivery.

## Decision Baseline (Frozen)
- D-01 (`Preserve`): the tenant-admin account profile type form must expose the existing `is_publicly_discoverable` capability; no new public flag may be introduced for this slice.
- D-02 (`Preserve`): `is_favoritable` depends on `is_publicly_discoverable` exactly like `is_reference_location_enabled` depends on `is_poi_enabled`.
- D-03 (`Preserve`): when public discovery is turned off, `is_favoritable` must be forced to `false`.
- D-04 (`Preserve`): when public discovery is off, the `is_favoritable` control must be visibly disabled with dependency copy instead of remaining silently interactive.
- D-05 (`Preserve`): turning public discovery back on must not auto-enable favorites.
- D-06 (`Preserve`): the Flutter admin domain, DTO, and request encoder must round-trip `is_publicly_discoverable`.
- D-07 (`Preserve`): the canonical tenant-admin module docs must be updated to expose the public capability in the admin contract.

## Current Delivery Stage
- `Pending`

## Qualifiers
- `Contract-Defined`
- `UI-Gap-Confirmed`
- `Existing-Backend-Capability`
- `Orchestration-Wave-2`
- `Sequencing-Approved`
- `Awaiting-Approval`

## Next Exact Step
- Wait for the Wave 1 map slice branch to resolve or for explicit approval to multiplex lanes, then refine the implementation/test matrix for this TODO and obtain its own `APROVADO`.
