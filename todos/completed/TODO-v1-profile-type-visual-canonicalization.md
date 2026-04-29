# TODO (V1): Profile Type Visual Canonicalization

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Completed
**Current delivery stage:** `Production-Ready`
**Qualifiers:** `Approved`, `Validated`
**Next exact step:** No implementation work pending; use this lane as the canonical baseline for `profile_type.visual` with `avatar|cover|type_asset`.
**Owners:** Flutter Team, Laravel Team
**Objective:** Replace the POI-only `poi_visual` contract with a canonical type-level `visual` contract that is valid across map and non-map surfaces, now including both item-derived image sources and a canonical uploaded type-owned image asset backed by Laravel `belluga_media`, while keeping `map_pois.visual` as a materialized projection snapshot rather than a client-side derivation concern.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `big`
**Checkpoint Policy:** full plan review gate + one checkpoint after contract refinement before approval.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter + laravel + shared-contracts`
**Expected supporting profiles:** `Operational / Coder (laravel)` and `Operational / Coder (flutter)` for paired contract, media, and consumer alignment

**Execution Notes:** The previously preserved Flutter-only baseline still only supports `visual.image_source=avatar|cover`. This approved execution lane now extends that baseline to `type_asset` with Laravel `belluga_media` ownership and paired Flutter admin/public support.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scopes:** `tenant_admin`, `tenant_public`
- **Subscope:** `n/a`

| Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| Profile type editors | tenant | `tenant` | `tenant_admin` | `n/a` | authenticated tenant admin |
| Static profile type editors | tenant | `tenant` | `tenant_admin` | `n/a` | authenticated tenant admin |
| `/mapa` | tenant | `tenant` | `tenant_public` | `n/a` | tenant public session |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/tenant_admin_module.md`
- **Secondary:** `../foundation_documentation/modules/map_poi_module.md`, `../foundation_documentation/modules/account_profile_catalog_module.md`, `../foundation_documentation/modules/flutter_client_experience_module.md`

### Canonical Coverage Status

- `tenant_admin_module.md`: authoritative for type-registry payload shape and admin editing flows; partially updated to canonical `visual`, but still incomplete for `type_asset`.
- `map_poi_module.md`: authoritative for `map_pois.visual` ownership and projection semantics; already aligned to projection-owned `visual`, but still incomplete for `type_asset` source resolution.
- `account_profile_catalog_module.md`: authoritative for account-profile consumer identity semantics; already aligned to canonical type visuals, but still incomplete for `type_asset` image semantics.
- `flutter_client_experience_module.md`: authoritative for shared Flutter consumer/rendering behavior when a type visual must render consistently across map and non-map UI; already aligned to `visual`, but still constrained to `avatar|cover`.

### Decision Consolidation Targets

- Promote stable type-registry contract changes into `../foundation_documentation/modules/tenant_admin_module.md`.
- Promote projection ownership changes into `../foundation_documentation/modules/map_poi_module.md`.
- Promote public-consumer rendering decisions into `../foundation_documentation/modules/account_profile_catalog_module.md` and `../foundation_documentation/modules/flutter_client_experience_module.md` where durable shared behavior is introduced.
- Sync stable payload examples and request/response shapes into `../foundation_documentation/endpoints_mvp_contracts.md`.
- Promote backend media-slot ownership and host-package integration details into the paired Laravel contract/module surfaces once the refreshed contract is approved.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-screen-public-account-profile-detail-polish.md`
- `lib/domain/tenant_admin/tenant_admin_profile_type.dart`
- `lib/domain/tenant_admin/tenant_admin_static_profile_type.dart`
- `lib/domain/tenant_admin/tenant_admin_poi_visual.dart`
- `lib/domain/partners/profile_type_definition.dart`
- `lib/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto.dart`
- `lib/infrastructure/dal/dto/tenant_admin/tenant_admin_static_profile_type_dto.dart`
- `lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart`
- `lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart`
- `lib/presentation/tenant_admin/static_profile_types/controllers/tenant_admin_static_profile_types_controller.dart`
- `lib/presentation/tenant_admin/static_profile_types/screens/tenant_admin_static_profile_type_form_screen.dart`
- `lib/domain/map/projections/city_poi_visual.dart`
- `lib/infrastructure/dal/dto/map/city_poi_visual_dto.dart`
- `lib/presentation/shared/icons/map_marker_visual_resolver.dart`
- `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `../laravel-app/packages/belluga/belluga_media/README.md`
- `../laravel-app/packages/belluga/belluga_media/src/Application/ModelMediaService.php`

---

## Problem Statement

- The current registry stores visual semantics in `poi_visual`, which incorrectly frames type visuals as a POI-only concern.
- Public UI surfaces outside the map already need canonical type visuals, but they currently hardcode local icon mappings or degrade to generic fallbacks.
- The existing contract duplicates concerns:
  - registry visual identity for the type;
  - map-specific projection snapshot for `map_pois.visual`.
- `poi_visual` currently mixes:
  - semantic identity (`icon`);
  - presentation styling (`color`, `icon_color`);
  - media-mode behavior (`mode=image`, `image_source`).
- Because the field is framed as POI-only, non-map surfaces have no canonical source of truth for `icon|image` rendering and drift toward hardcoded behavior.
- The current local canonicalization baseline only supports item-derived image sources (`avatar|cover`), but the approved product direction now requires type-owned uploaded image assets as a first-class option for `mode=image`.
- Without a canonical `type_asset` path, type identity semantics remain incomplete for categories whose visual should stay stable even when individual item avatar/cover media is weak or absent.

---

## Scope

- Canonicalize a type-level `visual` contract for account profile types and static profile types.
- Treat `visual.mode` as a global UI semantic, not a map-only concern.
- Support `visual.image_source=avatar|cover|type_asset` when `visual.mode=image`.
- Use Laravel `belluga_media` as the canonical host integration for `type_asset` upload/remove/URL normalization.
- Preserve `map_pois.visual` as a projection-owned snapshot derived from canonical type visual + item media.
- Define how non-map UI surfaces must render `mode=image` and `mode=icon`, including when the image is derived from a type-owned asset rather than item media.
- Prepare Flutter consumers to stop hardcoding type icons and instead consume canonical type visuals.
- Define the admin editing contract so tenant-admin edits `visual`, not `poi_visual`.

## Out of Scope

- Reworking the full Account Profile detail layout in this lane.
- Removing `map_pois.visual` from projection payloads.
- Client-side runtime derivation of map marker visuals directly from type registry data.
- New visual types beyond `icon|image`.
- Broad map UX redesign unrelated to the type visual contract.
- General-purpose image-management flows outside the specific type-visual slots needed by this contract.

---

## Current Technical Truth

- `TenantAdminProfileTypeDefinition` and `TenantAdminStaticProfileTypeDefinition` now expose canonical `visual` in Flutter, with compatibility reads from legacy `poi_visual`.
- Tenant-admin profile/static type forms now expose type visual editing independent of `capabilities.isPoiEnabled`.
- `CityPoiVisual` already models `icon|image` as a general visual snapshot shape, but it is consumed today through map projection payloads.
- Public Flutter consumers already resolve canonical `visual ?? poi_visual`, but their `mode=image` path is still constrained to item-derived sources.
- Canonical docs are partially migrated to `visual`, but still constrain image-source semantics to `avatar|cover`.
- The current local Flutter implementation for canonical `visual` only handles `image_source=avatar|cover`; no backend or Flutter contract exists yet for type-owned uploaded image assets.
- Laravel already provides a reusable tenant-scoped media slot package (`belluga_media`) that is the correct integration path for type-owned visual assets.

---

## Decision Baseline (Frozen)

- `D-01` (`Supersede`): `profile_type.visual` replaces `profile_type.poi_visual` as the canonical type visual contract.
- `D-02` (`Preserve`): `profile_type.visual.mode` supports `icon|image`, and this mode is valid across both map and non-map surfaces.
- `D-03` (`Preserve`): `capabilities.is_poi_enabled` controls whether a type projects into `map_pois`; it does not own the type visual contract.
- `D-04` (`Preserve`): `map_pois.visual` remains a materialized projection snapshot and must continue to be consumed directly by map clients.
- `D-05` (`Supersede`): when `profile_type.visual.mode=image`, `profile_type.visual.image_source` supports `avatar|cover|type_asset`; non-map surfaces must render the resolved image source rather than silently degrading to icon mode.
- `D-06` (`Preserve`): fallback to generic/default visuals only occurs when the required media asset is absent, invalid, or fails to load.
- `D-07` (`Preserve`): account profile types and static profile types must converge on the same canonical `visual` contract to avoid registry drift.
- `D-08` (`Supersede`): `type_asset` is the canonical uploaded image source for type visuals and must be implemented through Laravel `belluga_media` slots, not ad-hoc upload/storage logic.
- `D-09` (`Preserve`): `map_pois.visual` continues to be derived server-side from canonical `profile_type.visual` plus resolved item/type media; map clients must not re-derive visual source selection at render time.

---

## Initial Module Coherence Snapshot

- `tenant_admin_module.md`
  - Current state: now partially updated to `visual`, but still constrains `image_source` to `avatar|cover`.
  - Planned handling: `Supersede (Intentional)` via `profile_type.visual`.
- `map_poi_module.md`
  - Current state: states marker visuals are derived from canonical `visual`, but does not yet cover `type_asset` as an allowed origin.
  - Planned handling: `Supersede (Intentional)` while preserving projection-owned `map_pois.visual`.
- `account_profile_catalog_module.md`
  - Current state: public consumer contract exists, but `mode=image` semantics are still described as item-derived only.
  - Planned handling: `Supersede (Intentional)` to allow both item-derived and type-owned canonical image sources.
- `flutter_client_experience_module.md`
  - Current state: shared Flutter consumer rule exists, but it still constrains `image_source` to `avatar|cover`.
  - Planned handling: `Supersede (Intentional)` to keep Flutter consumers aligned with `type_asset`.

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `poi_visual` is the only durable registry visual field today, so canonicalization must supersede existing docs and DTOs rather than merely aliasing behavior in the UI. | Type definitions, DTOs, and tenant-admin forms all point to `poi_visual`. | The lane could shrink to a consumer-only adapter, which would not solve redundancy. | `High` | Keep as assumption. |
| `A-02` | `mode=image` is product-valid beyond map surfaces and should not be treated as an implementation artifact of map markers only. | User-approved direction in this session; current `CityPoiVisual` shape already models `image`. | The design would need a second semantic/icon field, reintroducing redundancy. | `High` | Keep as assumption. |
| `A-03` | Map clients should keep consuming projection snapshots instead of deriving marker visuals from type registry payloads at render time. | `map_poi_module.md` already codifies projection-owned `map_pois.visual`. | The lane would expand into a broader map architecture change with higher blast radius. | `High` | Keep as assumption. |
| `A-04` | Public non-map consumers can share a resolver/widget contract for `type.visual` without violating controller-first boundaries. | Existing drift is in presentation-level hardcoding; data flow already reaches consumer widgets through domain models/projections. | We would need per-surface local render logic and lose canonical behavior. | `Medium` | Keep as assumption; validate during plan review. |
| `A-05` | `belluga_media` already provides the reusable tenant-scoped slot orchestration needed for `type_asset`, so no bespoke Laravel storage workflow is required. | Package README and `ModelMediaService` explicitly define slot-based upload/remove/path/URL normalization responsibilities. | The backend lane would require either package extension or a new package, expanding scope. | `High` | Keep as assumption. |
| `A-06` | Type-owned image assets should be consumable on non-map surfaces with the same semantics as item-derived media, differing only in source resolution. | User direction in this session; public consumer contract already treats `mode=image` as global UI semantics. | We would need per-surface branching and possibly a second visual abstraction. | `High` | Keep as assumption. |

---

## Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-profile-type-visual-canonicalization.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-public-account-profile-detail-polish.md`
- `../foundation_documentation/modules/tenant_admin_module.md`
- `../foundation_documentation/modules/map_poi_module.md`
- `../foundation_documentation/modules/account_profile_catalog_module.md`
- `../foundation_documentation/modules/flutter_client_experience_module.md`
- `../foundation_documentation/endpoints_mvp_contracts.md`
- Flutter domain/DTO/form surfaces for type registry definitions
- Flutter public consumers that currently hardcode type icons or type-image fallback behavior
- Laravel media-slot contract surfaces for profile type and static profile type persistence
- Laravel controller/service/upload/remove flows that must bind `type_asset` to `belluga_media`

---

## Execution Plan

1. Refresh the canonical `profile_type.visual` payload shape to allow `image_source=avatar|cover|type_asset` and supersede the current module examples that still stop at `avatar|cover`.
2. Define admin editing/validation behavior for `visual`, including upload/remove semantics and preview behavior when the source is a canonical type-owned asset.
3. Define Laravel persistence/media rules so `type_asset` is backed by `belluga_media` slots for both profile types and static profile types.
4. Define projection rules so `map_pois.visual` is derived from canonical type visual + resolved item/type media, not rendered from registry data in the client.
5. Define Flutter public-consumer behavior for canonical type visual rendering, including a shared resolver/widget path that handles `avatar|cover|type_asset`.
6. After the refreshed contract is re-approved, reconcile the existing partial local Flutter implementation with the expanded contract and implement the paired Laravel lane in lockstep.
7. Run targeted verification and update decision-adherence evidence before promotion.

### Test Strategy

- `test-first` for Flutter contract/consumer changes once implementation starts
- `test-first` for Laravel media-slot contract changes once implementation starts

### Fail-First Targets

- Type DTO/domain decoding must reject drift between legacy `poi_visual` and canonical `visual` expectations, including `type_asset`.
- Public type-visual consumers must render `mode=image` using the resolved image source (`avatar|cover|type_asset`) instead of degrading to icon fallback.
- Map-facing consumers must continue consuming `map_pois.visual` snapshot payloads without runtime registry derivation.
- Laravel media-slot persistence must prove that uploaded type assets are normalized through `belluga_media` rather than ad-hoc storage logic.

---

## Validation Steps

1. Shared contract/docs updated for `profile_type.visual` across tenant-admin, map projection, and public-consumer modules, including `type_asset`
2. Laravel contract/tests for profile-type/static-profile-type media-slot persistence via `belluga_media`
3. Flutter targeted tests for type visual decoding and public consumer rendering behavior across `avatar|cover|type_asset`
4. Flutter targeted tests or coverage for admin type editor/upload behavior if form contract changes in this lane
5. `fvm dart analyze --format machine`
6. Targeted Laravel test gates for the new media-backed visual contract
7. Manual smoke on tenant-admin type editor + at least one public consumer surface after implementation

---

## Partial Baseline Preserved From Prior Local Work

- Flutter domain definitions already expose canonical `visual` on tenant-admin profile/static profile types, with DTO compatibility for legacy `poi_visual` reads.
- Tenant-admin request encoders already emit canonical `visual` and legacy `poi_visual` together while backend rollout remains transitional.
- Tenant-admin type editors already treat visual editing as type-level, not POI-only:
  - the editor stays visible even when `is_poi_enabled=false`;
  - disabling POI projection no longer clears the visual contract.
- Public registry parsing already resolves `visual ?? poi_visual`, and public consumers continue using the shared `ProfileTypeVisualResolver`.
- This preserved baseline is intentionally incomplete under the refreshed contract because it only covers item-derived image sources (`avatar|cover`) and does not yet implement canonical uploaded type-owned image assets.

## Superseded Verification Evidence

- Historical local evidence exists for the narrower `avatar|cover` baseline:
  - `test/infrastructure/dal/dto/app_data_dto_test.dart`
  - `test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`
  - `test/presentation/shared/visuals/profile_type_visual_resolver_test.dart`
  - `test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart`
  - `test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
  - `test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`
  - `test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart`
  - `test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`
  - `test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart`
- `fvm dart analyze --format machine` previously completed cleanly for that narrower baseline.
- This evidence is retained as a partial baseline only; it does not satisfy the refreshed `type_asset` contract and therefore cannot close this TODO.

## Decision Adherence Status After Contract Expansion

- Expanded `type_asset` contract is now implemented across Laravel + Flutter with automated verification green.
- Tenant-admin `type_asset` editing now reuses the canonical image source + crop flow used by avatar/cover instead of an ad-hoc picker path.
- Tenant-admin avatar, cover, and `type_asset` ingestion now share the same high-level Flutter widget orchestration for source-sheet, URL prompt, crop, and handoff to controllers, reducing UX drift across slots.
- Multipart type-visual uploads now normalize nested boolean capability flags for Laravel-compatible `1|0` payload semantics, closing the observed `422 validation.boolean` regression on `capabilities.*`.
- Manual smoke passed for:
  - tenant-admin account profile type editor
  - tenant-admin static profile type editor
  - public validation of the canonical `type_asset` flow
- Automated evidence:
  - Laravel: `tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php`, `tests/Feature/StaticAssets/StaticProfileTypesControllerTest.php`, `tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php`
  - Flutter: `test/infrastructure/dal/dto/app_data_dto_test.dart`
  - Flutter: `test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`
  - Flutter: `test/presentation/shared/visuals/profile_type_visual_resolver_test.dart`
  - Flutter: `test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart`
  - Flutter: `test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
  - Flutter: `test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`
  - Flutter: `test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart`
  - `fvm dart analyze --format machine`
