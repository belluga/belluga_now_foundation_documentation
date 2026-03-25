# TODO (V1): Static Assets Media Parity + Unified Media Processing Package Extraction

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** In Progress  
**Owners:** Backend Team, Flutter Team  
**Objective:** Evolve from "static-assets parity with account-profiles" to a single reusable media-processing package (avatar/cover and future model media) so all media behavior is equal by contract and reused across the ecosystem.

---

## A) Scope
- Keep existing public media contract stable while unifying media processing internals.
- Backend:
  - Preserve canonical media delivery under `/api/v1/media/*`.
  - Preserve legacy aliases (`/account-profiles/*`, `/static-assets/*`, `/map-filters/*`) as compatibility-only routes.
  - Consolidate duplicated media processing logic into a shared media core and extract it as an internal reusable package (same ecosystem package pattern).
  - Migrate `laravel-app` media services to consume the extracted package via model adapters/services.
  - Standardize media removal command semantics across models via explicit flags (`remove_avatar`, `remove_cover`) on update flows.
  - Keep cache validators (`ETag`, `Last-Modified`) and tenant-aware storage behavior unchanged.
- Flutter:
  - Keep current upload-first submit behavior unless a dedicated product decision changes the client contract.
  - Ensure screens keep consuming backend-generated media URLs without introducing fallback masking.
- Testing:
  - Add shared-core unit coverage and migrate/expand model feature tests to prove parity.
  - Update existing tests where refactor changes service boundaries or payload expectations.

## B) Out of Scope
- CDN migrations, image transformations, or provider changes.
- Visual redesign of media UI.
- Breaking API field renames (`avatar_url`, `cover_url`, `image_uri` remain as-is).
- Immediate adoption by every downstream app in the ecosystem (this slice guarantees package extraction + `laravel-app` adoption first).

## C) Current Diagnosis
- `AccountProfileMediaService` and `StaticAssetMediaService` are near-duplicates for upload/replace/remove/path/URL normalization logic.
- Both models already expose canonical and legacy public routes with cache validators, but this parity currently depends on duplicated code paths.
- `remove_avatar` / `remove_cover` semantics are implemented in media services, but request validation parity is inconsistent between models.
- Static-assets coverage is still weaker than account-profiles for media update paths (URL-mode update, multipart update, and explicit removals).
- Account-profile feature coverage already includes media update/replace/remove checks; this suite must be kept as a non-regression guard during shared-core migration.
- Flutter create/edit flows are currently upload-first in practice (URL fields in repositories exist, but submit paths pass `avatarUrl/coverUrl` as `null`).
- Flutter post-save media stability coverage is already present for static-assets list/detail/edit paths; remaining risk is concentrated on backend shared-core extraction + static-assets backend parity tests.

## D) Decision Baseline (Frozen)
- `D-01` `/api/v1/media/*` remains the canonical media contract for all model media surfaces.
- `D-02` Legacy media paths remain alias-only compatibility routes.
- `D-03` Model media updates must support explicit removal commands (`remove_avatar`, `remove_cover`) to avoid PATCH ambiguity.
- `D-04` Media processing internals must converge to one shared core (module/package-ready) with model-specific adapters.
- `D-05` Controllers and route contracts stay model-specific; storage/URL/remove/version logic is shared.
- `D-06` Existing API field names remain unchanged (`avatar_url`, `cover_url`, `image_uri`).
- `D-07` Flutter media contract stays upload-first unless a separate approved decision changes it.
- `D-08` Test gates are mandatory for parity claims: create, update (URL + multipart), remove, and canonical/legacy retrieval per model.
- `D-09` Shared media core extraction to an internal reusable Composer package is mandatory in this slice, with `laravel-app` migrated to consume it.

## E) Tasks
- [x] ✅ Production‑Ready Static-assets public media routes and controller parity delivered (canonical + legacy aliases + cache validators).
- [x] ✅ Production‑Ready Account-profile canonical/legacy media parameter handling aligned.
- [x] ✅ Production‑Ready Map-filter public media delivery parity wired.
- [ ] ⚪ Pending Define package contract for shared media processing (slots, remove flags, URL normalize, storage path resolve, versioning, tenant scoping).
- [ ] ⚪ Pending Create internal reusable package for media processing core following existing ecosystem package pattern.
- [ ] ⚪ Pending Wire package dependency in `laravel-app` and ensure deterministic install in local + CI.
- [ ] ⚪ Pending Refactor `AccountProfileMediaService` to delegate to package adapter/core.
- [ ] ⚪ Pending Refactor `StaticAssetMediaService` to delegate to package adapter/core.
- [ ] ⚪ Pending Migrate map-filter media storage logic to package primitives where applicable (without route contract change).
- [ ] ⚪ Pending Standardize request validation parity for removal flags (`remove_avatar`, `remove_cover`) across media-enabled update requests.
- [ ] ⚪ Pending Add/adjust Laravel feature tests for static-assets media update paths:
  - URL-field update persistence/readback
  - multipart update replacement/readback
  - explicit remove flags clearing media + storage cleanup
- [ ] ⚪ Pending Add package unit tests (path resolution, legacy/canonical URL normalization, remove logic, tenant scoping).
- [ ] ⚪ Pending Add package integration tests (expected adapter behavior for avatar/cover semantics).
- [ ] ⚪ Pending Migrate/update existing account-profile/static-assets feature tests in `laravel-app` to assert unified package-backed behavior (no domain drift).
- [x] ✅ Production‑Ready Add/adjust Flutter-focused tests to validate stable post-save media behavior (list/detail/edit refresh) under the finalized upload-first contract.

## F) Definition of Done
- [ ] ⚪ Pending Account-profile and static-assets media services use the same shared processing core.
- [ ] ⚪ Pending `laravel-app` consumes media processing through the extracted internal Composer package (no duplicated in-app core copy).
- [ ] ⚪ Pending No duplicated model-specific implementations remain for generic media processing concerns.
- [ ] ⚪ Pending `remove_avatar` / `remove_cover` semantics are consistent and validated for every media-enabled update endpoint.
- [x] ✅ Production‑Ready Canonical media URLs are currently retrievable and legacy aliases currently work as compatibility endpoints.
- [ ] ⚪ Pending Canonical + legacy retrieval contract remains green after shared-core refactor (no regressions).
- [ ] ⚪ Pending Static-assets and account-profiles pass equivalent create/update/remove media regression tests.
- [ ] ⚪ Pending Flutter media rendering and refresh behavior remains stable after backend consolidation.

## G) Validation
- [ ] ⚪ Pending Manual: Create/update/remove avatar/cover for static-assets and account-profiles; verify list/detail/edit after reload.
- [ ] ⚪ Pending Manual: Validate canonical and legacy media URL retrieval parity for both models.
- [ ] ⚪ Pending Manual: Validate `laravel-app` package wiring in a clean environment (`composer install` + boot + media endpoints healthy).
- [ ] 🟡 Provisional Automated: Re-run and adjust existing Laravel account-profiles media suite as non-regression coverage after shared-core migration.
- [ ] ⚪ Pending Automated: Laravel targeted feature suite for static-assets media (create/update/remove + canonical/legacy retrieval).
- [ ] ⚪ Pending Automated: Package unit/integration suite for media-processing abstractions.
- [ ] ⚪ Pending Automated: `laravel-app` integration/feature suite proving package-backed media behavior across account-profiles/static-assets/map-filters.
- [x] ✅ Production‑Ready Automated: Flutter tests cover post-save media persistence/render behavior for static-assets (list/detail/edit + upload-first flow stability).

## H) Complexity / Checkpoint Policy
- Complexity: `large`
- Checkpoint policy: package-contract checkpoint + internal-package-setup checkpoint + laravel-adoption checkpoint + test-gate checkpoint

## I) Applicable Rules / Workflows
- `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md`
- `delphi-ai/skills/wf-laravel-create-api-endpoint-method/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md` (only if Flutter edits become necessary)

## J) Approval Gate
- 2026-03-20: Scope updated to prioritize unified media-processing core and parity-by-contract across models.
- 2026-03-20: Scope updated again to require package extraction in this slice (not only package-ready design).
- 2026-03-24: Status audit synchronized with codebase evidence; Flutter post-save media test items promoted to ✅ Production‑Ready.
