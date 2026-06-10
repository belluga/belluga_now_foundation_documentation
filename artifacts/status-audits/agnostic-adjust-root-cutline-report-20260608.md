# Root Cutline Report: Agnostic Adjust Boilerplate Separation

## Scope

- **Date:** `2026-06-08`
- **Authoritative purpose:** freeze the audited root cutline for Boilerplate separation before any implementation.
- **In scope:** root `belluga_now_docker` only.
- **Out of scope in this report:** implementation, promotion, and the detailed `laravel_app_refactor` extraction.

## Inputs Reviewed

- Current root checkout `belluga_now_docker`
- Export snapshot `foundation_documentation/todos/active/agnostic_adjust/refactor_project_convention/belluga_now_docker/**`
- Strategic ledger `foundation_documentation/todos/active/agnostic_adjust/TODO-agnostic-adjust-boilerplate-cutline.md`
- Tactical root TODO `foundation_documentation/todos/active/agnostic_adjust/TODO-agnostic-adjust-root-boilerplate-separation.md`

## Executive Assessment

1. The exported root snapshot is directionally useful, but it is not mergeable as-is.
2. The valid idea inside the export is explicit `project overlay` ownership for downstream-specific routes/docs/tests.
3. The current root checkout already has a newer canonical browser/tooling contract in `tools/flutter/**`; reverting to exported `project/tests/**` would be a regression.
4. The root cutline is now clear enough to support a later implementation slice.
5. The `laravel_app_refactor` block must remain outside the first slice because the sampled delta is contaminated by product-functional evolution.

## Recommended Root Cutline

| Surface | Current / Export Signal | Recommended Class | Why |
| --- | --- | --- | --- |
| `README.md` | Current root still contains `belluga.space`, `guarappari.belluga.space`, and `/srv/belluga_now_docker`. | `boilerplate base` | Root onboarding belongs in Boilerplate, but examples/paths must be neutralized. |
| `.env.local.navigation.example` | Current file already leans generic with explicit URL placeholders and `NAV_DEPLOY_LANE=local`. | `boilerplate base` | Keep this as the canonical base example; do not reintroduce Belluga-specific defaults. |
| `docker-compose.yml` | Current root defaults runtime images to `belluga-now-*`. | `boilerplate base` | Compose belongs in the base, but image naming must become neutral/parameterized. |
| `Makefile` | Current root already centralizes root orchestration/test flows that overlap exported `project/scripts/test-laravel-full.sh`. | `boilerplate base` | Preserve the current centralized root workflow rather than re-splitting it into project scripts. |
| `docker/laravel-app/entrypoint.sh` | Current root already absorbed runtime-class validation inline; export still uses `project/scripts/validate_runtime_classes.sh`. | `boilerplate base` | Preserve the current inlined guard shape; do not restore the exported script split. |
| `docker/nginx/local.conf.template` | Current root inlines Belluga public routes such as `/parceiro`, `/agenda/evento`, `/descobrir`, `/convites`, `/baixe-o-app`. | `boilerplate base` | Base NGINX template should exist, but Belluga route inventory must leave the base and move into explicit downstream overlay. |
| `docker/nginx/prod.conf.template` | Same Belluga-specific route leakage as local template. | `boilerplate base` | Same treatment as local template. |
| `.github/scripts/verify_environment_ci.sh` | Current verifier expects `belluga-now-*` image names and Belluga preflight tags. | `boilerplate base` | CI verifier belongs in the base, but naming assumptions must be neutralized. |
| `.github/scripts/preflight_promotion_runtime_builds.sh` | Current script emits `belluga-preflight-*` tags. | `boilerplate base` | Keep the preflight contract, remove Belluga naming. |
| `.github/scripts/resolve_lane_navigation_targets.sh` | Current script explicitly guards `belluga.space` hosts. | `boilerplate base` | The domain-safety contract is reusable, but Belluga host knowledge must become generic/parameter-driven. |
| `.gitmodules` | Current root and export both still point at `belluga_now_*` repositories. | `boilerplate base` | If Boilerplate keeps submodules, onboarding and URLs must become neutral placeholders or documented downstream replacement points. |
| `tools/flutter/run_web_navigation_smoke.sh` | Current canonical runner is newer than export and adds lane/host/mutation guardrails. | `boilerplate base` | Preserve the current root-owned runner as canonical. |
| `tools/flutter/web_app_tests/**` | Current root is the canonical browser/tooling location; export duplicates most of it under `project/tests/web_app_tests/**`. | `boilerplate base` | Keep this as the canonical shared tooling surface. |
| `project/nginx/routes.conf` (export) | Explicit Belluga public route inventory. | `project overlay / Belluga Now-specific` | This is the correct downstream home for Belluga route inventory once the base template supports explicit inclusion. |
| `project/README.md` (export) | Explicit downstream/project-local onboarding. | `project overlay / Belluga Now-specific` | This is useful only as downstream documentation, not Boilerplate base. |
| `project/LANE_PROMOTION_TUTORIAL.md` (export) | Project-local promotion tutorial surface. | `project overlay / Belluga Now-specific` | Downstream operator documentation belongs outside the neutral base. |
| `project/tests/setup_local_navigation_env.sh` (export) | Project-local navigation env bootstrap helper. | `project overlay / Belluga Now-specific` | Acceptable only if it becomes a downstream wrapper over canonical root tooling. |
| `project/tests/web_app_smoke_runner/**` (export) | Project-local smoke-runner package. | `exclude / stale export noise` | Current root already owns the canonical browser runner contract; reintroducing this split would duplicate ownership. |
| `project/tests/web_app_tests/**` (export) | Export overlaps `tools/flutter/web_app_tests/**` on 27 files and misses newer current tests/support. | `exclude / stale export noise` | This is a stale duplicate, not the canonical current source. |
| `project/tests/run_web_navigation_smoke.sh` (export) | Older runner than current `tools/flutter/run_web_navigation_smoke.sh`; missing explicit lane and non-local mutation guardrails. | `exclude / stale export noise` | Current root runner is materially safer and more deterministic. |
| `project/scripts/test-laravel-full.sh` (export) | Overlaps current root orchestration/Makefile behavior. | `exclude / stale export noise` | No need to revive this script split. |
| `project/scripts/validate_runtime_classes.sh` (export) | Logic already absorbed into current `docker/laravel-app/entrypoint.sh`. | `exclude / stale export noise` | Redundant after current root evolution. |
| `scripts` placeholder file (export root) | Export artifact is a placeholder file, not a real script tree. | `exclude / stale export noise` | Pure export noise. |

## Hard Root Problems To Fix In A Later Implementation Slice

1. The base root still leaks Belluga Now identity in docs, image names, host checks, and route inventory.
2. The export proposes the right downstream route split, but its testing/tooling split is stale and must not become canonical.
3. The future implementation should extract downstream route ownership without demoting `tools/flutter/**` from its current root-canonical position.
4. `.gitmodules` cannot be promoted to Boilerplate unchanged.

## Browser / Tooling Contract Decision

- **Decision recommendation:** keep `tools/flutter/**` as the canonical shared root tooling surface.
- **Reason:** the current runner and suite already contain stronger deterministic guardrails than the export:
  - explicit mutation-lane contract,
  - explicit non-local mutation opt-in,
  - deterministic suite selection,
  - newer support/runtime diagnostics.
- **Downstream allowance:** if a project needs local wrappers, they should be thin `project/**` wrappers around `tools/flutter/**`, not a competing duplicated suite.

## Public Route Overlay Decision

- **Decision recommendation:** Belluga route inventory belongs in downstream overlay, not in the Boilerplate base NGINX templates.
- **Belluga-specific route examples confirmed in export/current evidence:**
  - `/parceiro`
  - `/agenda/evento`
  - `/descobrir`
  - `/convites`
  - `/baixe-o-app`
  - `/invite`
  - `/mapa`
  - `/mapa/poi`
  - `/privacy-policy`

## Laravel Export Posture For This Initiative

- **Status:** not approved for first-slice implementation.
- **Reason:** the sampled diff is not a clean agnostic split; it carries functional evolution in environment payloads, proximity preferences, event management, invite preview, and map POI behavior.
- **Recommendation:** keep `laravel_app_refactor` as a second report/front, or explicitly exclude it from this Boilerplate promotion wave.

## Proposed Review Decision With User

1. Confirm that the first implementation slice stays root-only.
2. Confirm that `tools/flutter/**` remains the canonical shared browser/tooling location.
3. Confirm that Belluga public routes move to downstream overlay rather than staying inline in base NGINX templates.
4. Confirm that image names, submodule URLs, docs, and host/domain checks must be neutralized in the base.
5. Confirm that `laravel_app_refactor` is deferred or excluded from this wave.
