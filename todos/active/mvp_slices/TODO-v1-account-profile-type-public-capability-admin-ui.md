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

## Package-First Assessment
- Query executed:
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app --search "profile type"`
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app --search "admin"`
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app --detail "belluga_admin_ui"`
- Relevant packages found:
  - `[Ecosystem][Flutter] belluga_admin_ui` — existing admin UI primitives remain usable, but the slice does not require package changes because the missing behavior is feature-specific capability wiring in the host app
- READMEs read:
  - `belluga_admin_ui` package detail output
- Decision: local implementation over existing tenant-admin form/controller/DTO surfaces
- Tier: `host Flutter implementation using existing ecosystem UI primitives`
- Rationale: the gap is not a reusable widget primitive; it is capability round-trip and dependency logic in a specific tenant-admin feature

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Laravel already accepts and emits `capabilities.is_publicly_discoverable`, so the slice can stay Flutter/docs-only. | Existing capability normalization in `AccountProfileRegistryManagementService`, registry payload in `AccountProfileRegistryService`, request validation in `AccountProfileTypeStoreRequest`/`UpdateRequest`. | The slice would expand into backend semantics and need renewed approval. | `High` | `Keep as Assumption` |
| `A-02` | The missing behavior is entirely in the Flutter tenant-admin domain/DTO/controller/form chain. | Current Flutter admin code omits `is_publicly_discoverable` while already wiring `is_favoritable`, `is_poi_enabled`, and `is_reference_location_enabled`. | The scope would need to widen into a hidden consumer/backend gap. | `High` | `Keep as Assumption` |
| `A-03` | The dependency contract can mirror the existing `is_poi_enabled -> is_reference_location_enabled` pattern without broader architecture change. | Existing form/controller behavior already disables and clears the child toggle behind a parent capability. | The slice would need a new interaction pattern and renewed review. | `High` | `Keep as Assumption` |
| `A-04` | Canonical module drift is limited to `tenant_admin_module.md` request/response schema and field definitions. | Current module doc omits `is_publicly_discoverable` from the profile type capability contract even though backend code supports it. | More canonical docs would need simultaneous promotion. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/domain/tenant_admin/tenant_admin_profile_type_capabilities.dart`
- `flutter-app/lib/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto.dart`
- `flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_account_profiles_request_encoder.dart`
- `flutter-app/lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart`
- `flutter-app/test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`
- `flutter-app/test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`
- `flutter-app/test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`
- `foundation_documentation/modules/tenant_admin_module.md`

### Ordered Steps
1. Add or tighten fail-first coverage for DTO round-trip, controller dependency clearing, and disabled-state form behavior.
2. Wire `is_publicly_discoverable` through the tenant-admin domain/DTO/request encoder/controller/form chain.
3. Implement the UI dependency so `is_favoritable` is disabled and cleared when public discovery is off, without auto-reenabling it later.
4. Update `tenant_admin_module.md` to expose the existing capability in the admin contract.
5. Rerun the targeted Flutter tests and analyzer gate.
6. Reconcile the worker checkpoint into the principal `reconcile/*` checkouts and run final tenant-admin save/readback smoke on the reconciled state.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the slice defines explicit capability behavior in a user-facing admin form and is easily verifiable by targeted tests
- **Fail-first target(s):**
  - `test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`
  - `test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`
  - `test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`

### Flow Evidence Planning Matrix
| ID | Criterion | Flow-impact reason | Platform parity | Required runtime lane | Mutation requirement | Real-backend requirement | Planned evidence | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `FE-01` | Public-discovery toggle is visible and editable in tenant-admin profile type form. | Visible admin mutation surface. | `shared-android-web` | `manual tenant-admin runtime smoke` | `yes` | `yes` | targeted form/controller/DTO tests plus manual admin smoke | Requires a tenant-admin session with profile type edit access. |
| `FE-02` | Turning public discovery off clears and disables favoritable. | Visible admin mutation plus dependency behavior. | `shared-android-web` | `manual tenant-admin runtime smoke` | `yes` | `yes` | targeted controller/form tests plus manual dependency smoke | Must mirror the POI/reference-origin pattern. |
| `FE-03` | Turning public discovery back on does not auto-enable favoritable. | Visible admin mutation persistence behavior. | `shared-android-web` | `manual tenant-admin runtime smoke` | `yes` | `yes` | targeted controller/form tests plus manual dependency smoke | Operator must opt in again explicitly. |
| `FE-04` | Saving and reloading preserves `is_publicly_discoverable`. | Persisted admin capability round-trip. | `shared-android-web` | `manual tenant-admin runtime smoke` | `yes` | `yes` | targeted DTO/controller tests plus manual save/readback smoke | Confirms request encoder and registry readback. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / tenant-admin DTO regression` | Wave 2 changes typed profile capability parsing. | `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart` | `Local-Implemented` | `passed` | `worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d; reconciled re-run passed on 2026-05-15 at flutter-app@b33b7d55` | Reconciled-state rerun completed on the principal checkout. |
| `flutter-app / tenant-admin controller regression` | Wave 2 changes capability dependency logic. | `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart` | `Local-Implemented` | `passed` | `worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d; reconciled re-run passed on 2026-05-15 at flutter-app@b33b7d55` | Confirms forced-clear child capability semantics. |
| `flutter-app / tenant-admin form regression` | Wave 2 changes visible admin form behavior. | `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart` | `Local-Implemented` | `passed` | `worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d; reconciled re-run passed on 2026-05-15 at flutter-app@b33b7d55` | Confirms disabled-state UI behavior in the reconciled checkout. |
| `flutter-app / Flutter architecture analyzer gate` | Wave 2 touches Flutter production and test surfaces. | `fvm dart analyze --format machine` | `Local-Implemented` | `passed` | `worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d; reconciled analyzer passed on 2026-05-15 at flutter-app@b33b7d55` | Shared analyzer rerun executed on the final reconciled Flutter checkout. |
| `foundation_documentation / canonical tenant-admin contract review` | Wave 2 updates the admin module contract. | `manual diff review against Laravel capability contract` | `Local-Implemented` | `passed` | `foundation checkpoint 2c83c4cbd676b512d9c667c08f6ada428d67e14e; reconciled doc commit bcc77d2` | Confirms `is_publicly_discoverable` is documented without introducing a new alias. |

## Execution Evidence
- Worker checkpoints:
  - Flutter: `09e08c9af89c3ae9573a39ef775efd13f63bdb7d` (`✨ feat: wire public discoverability in tenant admin profile types`) on `/home/elton/Dev/repos/belluga-ecosystem/_worktrees/flutter-wave2-public-discoverability-20260515`
  - Foundation: `2c83c4cbd676b512d9c667c08f6ada428d67e14e` (`📝 docs: expose profile type public discoverability`) on `/home/elton/Dev/repos/belluga-ecosystem/_worktrees/foundation-wave2-public-discoverability-20260515`
- Principal reconcile commits:
  - `flutter-app`: `b33b7d55`
  - `foundation_documentation`: `bcc77d2`
- Reconciled automated validation:
  - `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart` ✅
  - `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart` ✅
  - `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart` ✅
  - `fvm dart analyze --format machine` ✅
  - `tenant_admin_module.md` diff review against the Laravel capability contract ✅
- Runtime validation status:
  - `blocked`
  - No approved mutation/browser evidence was recorded yet for editing `is_publicly_discoverable`, forcing `is_favoritable=false`, or save-and-reload persistence on the reconciled branch.
  - Canonical navigation preflight is currently blocked on runtime topology (`app` bind mount does not resolve to the principal `laravel-app` checkout path), so the shared browser lane never reached Playwright execution.

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
- `Blocked on runtime validation evidence`

## Qualifiers
- `Contract-Defined`
- `UI-Gap-Confirmed`
- `Existing-Backend-Capability`
- `Orchestration-Wave-2`
- `Sequencing-Approved`
- `Wave-Authorized`
- `Code-Reconciled`
- `Automated-Gates-Passed`
- `Runtime-Harness-Blocked`

## Next Exact Step
- Restore the canonical reconcile navigation surface and collect mutation/runtime evidence for the tenant-admin public-discovery toggle, the forced favoritable reset/disable behavior, and save-and-reload persistence on the reconciled branch.
