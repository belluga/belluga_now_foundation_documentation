# Title
Tenant-Public Branding Metadata Fallback

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant-public event, account-profile, and static-asset routes already inject route-specific Open Graph metadata correctly through Laravel. The gap is the tenant-aware fallback for other tenant-public web routes. The new fallback must come from branding, must preserve the existing route-specific behavior exactly as it works today, and must not collapse tenant-aware metadata back into a single static `index.html` fallback.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** The request is one bounded tenant-public web metadata slice: define the fallback branding contract, preserve current special-route metadata, and wire tenant-aware fallback into the server-side shell path.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** The goal is already concrete and implementation-shaped; a separate initiative brief would add ceremony without reducing ambiguity.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** Prepare lane-promotion packaging from the validated local state when requested.

## Scope
- [x] Add a tenant-aware branding fallback contract for public web metadata.
- [x] Preserve current metadata precedence and behavior for `/parceiro/{slug}`, `/agenda/evento/{slug}`, and `/static/{ref}`.
- [x] Use the requested URL as `og:url`/canonical identity for the routes covered by this slice.
- [x] Keep `og:title`, `og:description`, and `og:image` route-specific when available; otherwise resolve them from branding fallback, then from the current default fallback.
- [x] Keep `og:type`, `og:site_name`, canonical, and Twitter mirror tags server-derived.
- [x] Validate the runtime routing path in Docker/Nginx for the routes covered by the slice.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app:<planned>`, `flutter-app:<planned>`, `belluga_now_docker:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Branding metadata contract + Laravel fallback + runtime routing | `local workspace (uncommitted)` | `pending` | `pending` | `pending` | `Local-Implemented` |

## Out of Scope
- [ ] Changing the already-correct metadata selection logic for event, account-profile, or static-asset routes except to preserve it under the new precedence chain.
- [ ] Introducing an automatic OG image derivation pipeline beyond what the backend can truthfully support in this slice.
- [ ] Altering favicon/PWA icon semantics.
- [ ] Broad SEO policy for private/admin/auth routes unless strictly required by the routing allowlist chosen in this TODO.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** branding contract additions, Laravel metadata precedence updates, Docker ingress allowlist updates, and focused Flutter admin wiring needed to edit the new branding fields.
- **Must update or split the TODO:** a full public-web ingress redesign, service-worker strategy changes, or a generalized SEO/indexing program beyond tenant-public metadata fallback.

## Definition of Done
- [x] `branding_data` supports a canonical tenant-aware public metadata fallback node.
- [x] Existing special routes keep their current route-specific metadata behavior.
- [x] Covered non-entity routes use requested URL identity with branding fallback for title/description/image.
- [x] Laravel tests prove precedence: route-specific > branding fallback > current default fallback.
- [x] Runtime/Docker validation proves the targeted routes hit the dynamic shell path instead of the static `index.html`.

## Validation Steps
- [x] Run targeted Laravel unit/feature tests for `PublicWebMetadataService` and the public shell controller.
- [x] Run targeted Flutter tests for tenant-admin branding settings decoding/repository if the client contract changes.
- [x] Run Docker/runtime verification against the targeted tenant-public routes.
- [x] Run `fvm dart analyze --format machine`
- [x] Run the relevant Laravel test lane in the project container/runtime.

## Latest Local Evidence
- `2026-04-15`: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php tests/Feature/Tenants/PublicWebMetadataShellTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php tests/Feature/Tenants/TenantResolutionTest.php` -> `28 passed (143 assertions)`.
- `2026-04-15`: `fvm dart analyze --format machine` -> clean.
- `2026-04-15`: `bash scripts/build_web.sh ../web-app dev --clean-output` -> success; `web-app` published without bundled `favicon.ico`, `manifest.json`, or `icons/`.
- `2026-04-15`: local runtime probes through `https://127.0.0.1:8043` with `Host: guarappari.belluga.space` confirmed:
  - `/` returns server-rendered fallback metadata from branding;
  - `/mapa?origem=home` preserves requested canonical/`og:url`;
  - `/admin` is served by the Flutter bundle, not the public shell;
  - `/admin/api/v1/check` reaches the backend (`401`), not Flutter shell HTML.
- `2026-04-15`: `fvm flutter test` -> `1111 tests passed`.
- `2026-04-15`: runtime audit of the admin-reloaded OG fallback image proved the save path was correct but `/storage/...` serving was broken by the nginx `/storage/` alias block. After removing `try_files $request_filename =404;` from `docker/nginx/local.conf.template` and `docker/nginx/prod.conf.template` and recreating `nginx`, the published asset `https://belluga.space/storage/tenants/guarappari/public-web/default-image.jpg` returned `200 image/jpeg`, while missing files still returned `404`.

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| local Docker runtime | ingress decides whether routes receive dynamic metadata or static `index.html` | `healthy` | `2026-04-15` | nginx template inspection + local runtime probing against `https://127.0.0.1:8043` with tenant host header | runtime verification is mandatory before delivery |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `operational-devops` | nginx/runtime route selection is part of delivery evidence | `docker/nginx/*` | `completed` (`2026-04-15` local probe via `127.0.0.1:8043` + tenant host header) |

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** The change spans Laravel contract/storage, Flutter admin input/output, and Docker ingress/runtime while preserving existing public metadata behavior.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant_admin_module.md` visual identity / branding ownership
  - `flutter_client_experience_module.md` tenant-public web bootstrap/public shell contract
- **Module decision consolidation targets (required):**
  - `tenant_admin_module.md` section owning visual identity/branding fields
  - `flutter_client_experience_module.md` web bootstrap/public shell metadata contract if the fallback policy becomes canonical

## Decision Pending (Resolve Before Freeze)
- [x] `D-01` Freeze the new branding node shape for public metadata defaults.

## Decisions (Resolved Before Freeze)
- [x] `D-01` Add `branding_data.public_web_metadata` with editables for `default_title`, `default_description`, and `default_image`.
- [x] `D-02` Keep `og:url`, `canonical_url`, `og:type`, `og:site_name`, and Twitter mirror tags runtime-derived.
- [x] `D-03` Preserve route-specific precedence for `/parceiro/{slug}`, `/agenda/evento/{slug}`, and `/static/{ref}` exactly as they work today.
- [x] `D-04` For covered fallback routes, use the requested URL as the canonical/OG URL identity while sourcing title/description/image from branding fallback when route-specific metadata is absent.
- [x] `D-05` The initial dynamic-shell allowlist covers tenant-public HTML routes that should expose tenant-aware metadata, while excluding `/admin/*`, `/api/*`, backend-owned branding asset routes, and media/file endpoints.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `tenant_admin visual-identity branding owner` | `/admin/settings/visual-identity` owns tenant runtime branding identity, with favicon and PWA icon kept separate. | `Preserve` | [tenant_admin_module.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/modules/tenant_admin_module.md:197) |
- | `FCX Web Bootstrap Visual Continuity Contract` | Public shell branding and favicon are runtime-owned and must not point to bundled static files. | `Preserve` | [flutter_client_experience_module.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/modules/flutter_client_experience_module.md:70) |
- | `events/public profile/static route metadata behavior` | Existing public routes already provide route-owned metadata and safe fallback behavior. | `Preserve` | [PublicWebMetadataService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php:55) |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The new fallback must be tenant-aware and server-side, not a static shared `index.html` substitution.
- [x] `D-02` Existing event/account-profile/static-asset metadata behavior must remain functionally unchanged.
- [x] `D-03` The branding fallback contract must only promise fields the backend can truthfully store and serve in this slice.

## Questions To Close
- [ ] none

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | A pure static `index.html` cannot satisfy tenant-aware fallback metadata for crawlers. | nginx serves `/var/www/flutter/index.html` directly in catch-all paths; crawlers do not execute Flutter for OG metadata | The tenant-aware requirement would need reinterpretation. | `High` | `Keep as Assumption` |
| `A-02` | Adding `public_web_metadata` requires coordinated updates across branding management/normalization and DTO serialization. | [TenantBrandingManagementService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Tenants/TenantBrandingManagementService.php:46), [LandlordBrandingManagementService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Branding/LandlordBrandingManagementService.php:45), [BrandingData.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/DataObjects/Branding/BrandingData.php:13) | The new node could be silently dropped or returned inconsistently. | `High` | `Keep as Assumption` |
| `A-03` | A dedicated OG image derivative pipeline does not exist today. | existing asset derivation only covers PWA icon variants | We either need a source-only contract in this slice or an explicit asset/derivation addition. | `High` | `Promote to Decision` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php`
- `laravel-app/app/Application/PublicWeb/FlutterWebShellRenderer.php`
- `laravel-app/app/Application/Tenants/TenantBrandingManagementService.php`
- `laravel-app/app/Application/Branding/LandlordBrandingManagementService.php`
- `laravel-app/app/DataObjects/Branding/*`
- `laravel-app/app/Http/Api/v1/Requests/UpdateBrandingRequest.php`
- `laravel-app/app/Http/Controllers/TenantPublicShellController.php`
- `laravel-app/routes/web.php`
- `laravel-app/tests/Feature/Tenants/PublicWebMetadataShellTest.php`
- `flutter-app/lib/domain/tenant_admin/settings/*`
- `flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_settings_response_decoder.dart`
- `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
- `flutter-app/test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
- `docker/nginx/local.conf.template`
- `docker/nginx/prod.conf.template`

### Ordered Steps
1. Freeze the backend contract for `public_web_metadata`, including what image fields are truly supported in this slice.
2. Add fail-first Laravel tests for metadata precedence and for the new fallback fields.
3. Implement Laravel branding storage/normalization and metadata precedence updates.
4. Add/update Flutter tenant-admin contract surfaces for the new branding fields.
5. Implement the initial runtime route allowlist for dynamic shell fallback.
6. Validate focused Laravel, Flutter, and Docker/runtime behavior, then run lane-level local validation.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** This slice changes public runtime behavior and contract precedence across three repositories.
- **Fail-first target(s) (when required):** metadata precedence tests and runtime route-selection tests.

### Runtime / Rollout Notes
- Dynamic-shell routing must be allowlist-based initially.
- Existing special routes must remain untouched semantically.
- Cache behavior for dynamically served HTML must remain conservative.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `META-01`
  - **Severity:** `high`
  - **Evidence:** current branding shape only normalizes `theme_data_settings`, `logo_settings`, and `pwa_icon`
  - **Why it matters now:** A new metadata node will be dropped or returned inconsistently without coordinated storage/DTO updates.
  - **Option A (Recommended):** add a canonical `public_web_metadata` node and normalize it symmetrically in tenant/landlord services and DTOs.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** store metadata fields in an existing node such as `logo_settings`.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** keep only the current default metadata fallback.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

- **Issue ID:** `META-02`
  - **Severity:** `high`
  - **Evidence:** nginx catch-all still serves static `index.html` for non-special routes
  - **Why it matters now:** Tenant-aware fallback metadata will not reach crawlers unless the targeted routes hit the dynamic shell path.
  - **Option A (Recommended):** introduce a narrow allowlist of tenant-public routes that should resolve through Laravel shell rendering.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** broaden the full SPA catch-all through Laravel.
    - **Effort:** `high`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `regresses`
    - **Elegance impact:** `mixed`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** rely on static `index.html` fallback.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] Existing special routes lose route-specific metadata precedence.
- [ ] Dynamic-shell allowlist captures routes that should remain static/SPA-only.
- [ ] The new branding node is persisted on tenant but not landlord, or vice versa.

### Residual Unknowns / Risks
- [ ] If the slice needs a dedicated OG image derivative rather than a source image field, the asset pipeline scope increases.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `yes`
- **Why ambiguity remains:** The branding contract is clear, but the initial runtime route allowlist and image-field promise still carry architectural tradeoffs.
- **Opinion count:** `2`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `yes`
- **Required lenses:** `correctness|risk|structural-soundness`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `Kant` | use `public_web_metadata` defaults and keep runtime-derived tags derived; treat route expansion as a separate ingress concern with an allowlist | `unknown` | `improves` | `improves` | `Integrated` | no-context critique in session on `2026-04-14` |
| `Darwin` | make metadata precedence tests mandatory and validate routing at Docker/runtime, not only controller level | `neutral` | `improves` | `improves` | `Integrated` | no-context critique in session on `2026-04-14` |

## Independent No-Context Critique Gate (Required for `big`; conditional for `medium/high-impact`)
- **Critique decision:** `required`
- **Why this decision:** Big cross-stack change touching public runtime behavior, ingress, and branding contract.
- **Impact signals in scope:** `cross-module blast radius|public contract/schema/api|runtime/queue/realtime/ingress`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `current metadata flow`, `branding shape`, `nginx/static vs dynamic shell split`, `preservation requirement for special routes`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `n/a`
- **Audit session / round evidence (when protocol used):** `n/a`
- **Critique lenses:** `correctness|performance|elegance|structural-soundness|risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** External reviewers confirmed the need for coordinated branding-shape updates, runtime route allowlist validation, and precedence tests.
- **Resolution ledger:** use the machine-checkable table below when findings exist
- | Finding ID | Resolution (`Integrated|Challenged|Deferred`) | Usefulness (`useful|noise|mixed|unknown`) | Formalizable (`yes|partial|no|unknown`) | Candidate Rule Level (`paced|project|none|unknown`) | Candidate Rule ID | Rationale / Evidence |
- | --- | --- | --- | --- | --- | --- | --- |
- | `EXT-META-01` | `Integrated` | `useful` | `yes` | `project` | `n/a` | Confirms new branding node must be normalized symmetrically. |
- | `EXT-META-02` | `Integrated` | `useful` | `yes` | `project` | `n/a` | Confirms tenant-aware fallback needs dynamic-shell routing, not static `index.html`. |
- | `EXT-META-03` | `Integrated` | `useful` | `partial` | `project` | `n/a` | Confirms route-selection validation belongs in Docker/runtime and precedence tests belong in Laravel. |
- **Evidence / reference:** `Kant` and `Darwin` no-context critiques, 2026-04-14
- **Waiver authority / reference (required if waived):** `n/a`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `wf-laravel-create-api-endpoint-method` | Laravel public-web response contract changes are in scope | route/controller/contract alignment | silent contract drift between storage and runtime rendering | load after approval |
| `rule-docker-docker-runtime-ingress-model-decision` | nginx/runtime routing changes are in scope | backend-owned branding and dynamic route correctness | broad catch-all ingress rewrites without targeted validation | load after approval |
| `flutter-architecture-adherence` | tenant-admin Flutter contract surface may change | repository/domain/controller separation | embedding backend shape ad hoc in widgets | load after approval |
| `test-creation-standard` | cross-stack regression coverage is material | fail-first precedence and routing coverage | false-green assertSee-only validation | load after approval |
