# TODO (VNext): Laravel Package Guardrails and Skill Convergence

**Status legend:** `- [ ] Pending` - `- [ ] Provisional` - `- [x] Production-Ready`.
**Status:** Active (`Planning`)
**Owners:** Backend Team + Platform
**Objective:** Consolidate deterministic Laravel package architecture enforcement in-repo so decoupling and package communication rules are blocked by objective checks, while Delphi skills and workflows stay synchronized to the same rule set without becoming the only line of defense.
**Complexity:** `medium`
**Checkpoint policy:** full Plan Review Gate before execution approval + one post-validation checkpoint.

---

## Goal
Turn the current package architecture conventions into explicit, auditable rules in `laravel-app`, keeping `pint` focused on formatting and using skills/workflows as aligned guidance rather than prompt-only enforcement.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `laravel-app/scripts/architecture_guardrails.php`
  - `laravel-app/composer.json`
  - `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md`
  - `delphi-ai/.cline/skills/wf-laravel-create-package-method/SKILL.md`
  - `delphi-ai/workflows/laravel/create-package-method.md`
  - `delphi-ai/.clinerules/workflows/laravel-create-package-method.md`

---

## References
- `foundation_documentation/todos/completed/TODO-v1-laravel-architecture-guardrails-custom-rules.md`
- `foundation_documentation/todos/completed/TODO-v1-laravel-packages-multitenancy-readme-skill-sync.md`
- `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md`
- `delphi-ai/skills/wf-docker-update-skill-method/SKILL.md`

---

## Audit Snapshot (Current)

### Positive Baseline
- [x] Production-Ready Existing decoupling assertion already passes for:
  - `belluga_events`
  - `belluga_invites`
  - `belluga_map_pois`
  - `belluga_settings`
  - `belluga_push_handler`
  - `belluga_ticketing`
- [x] Production-Ready No direct package-to-package imports were found in `laravel-app/packages/*/*/src/**`.
- [x] Production-Ready Host-mediated communication patterns already exist:
  - contract -> adapter binding via `laravel-app/app/Integration/**`
  - package domain events -> host listeners/jobs
  - shared-kernel extensibility via `belluga_settings`

### Current Gaps
- [x] Production-Ready Package route files imported host middleware classes directly; all affected route files were removed and replaced by host-owned route files under `laravel-app/routes/api/**`.
- [x] Production-Ready Route ownership was inconsistent across packages; all current Belluga `host-integrated` and `shared-kernel` packages now use `host-owned-routes`.
- [x] Production-Ready Host binding verification was split across runtime fail-fast providers and the workflow Python assertion; `laravel-app/scripts/architecture_guardrails.php` now covers it directly.
- [x] Production-Ready Host integration composition was concentrated in `laravel-app/app/Providers/AppServiceProvider.php`; package composition now lives in dedicated providers under `laravel-app/app/Providers/PackageIntegration/**`.

---

## Scope
1. Extend `laravel-app/scripts/architecture_guardrails.php` with package route and communication boundary rules.
2. Define an explicit package integration classification for enforcement:
   - `self-contained`
   - `host-integrated`
   - `shared-kernel`
3. Define explicit route ownership per package:
   - `host-owned-routes`
   - `package-owned-routes`
4. Keep `composer run lint:strict` as the developer entrypoint, with `pint --test` remaining formatting-only and architecture validation delegated to dedicated guardrails/tests.
5. Add targeted architecture tests only where runtime/composition proof is required and static analysis is insufficient.
6. Update Laravel package-creation skill/workflow surfaces so the same rule IDs and package vocabulary are enforced across Codex, Cline, and Antigravity.
7. Register any required remediation for current package violations inside this TODO before closure.
8. Migrate every current Belluga package route surface to `host-owned-routes`, deleting package route files and removing `loadRoutesFrom(...)` from package providers.
9. Remove obsolete/duplicated package communication seams that are no longer the canonical integration path.
10. Break package composition out of `AppServiceProvider.php` into dedicated host integration providers and guard against re-centralization.

## Out of Scope
- Introducing PHPStan, Larastan, Deptrac, or another analyzer stack in this slice.
- Flutter/Web implementation changes.

---

## Execution Governance (Mandatory)
- **Execution lane:** Tactical TODO lane.
- **Authority rule:** this TODO is the only execution authority for this slice.
- **Approval rule:** no code, guardrail, skill, or workflow edits may start until the user replies `APROVADO`.
- **Exception rule:** if implementation uncovers a necessary package-specific exception, capture it here before closure with owner, rationale, and next action.

---

## Plan Review Gate (Medium)

### Issue Card I-01
- **Severity:** High
- **Category:** Enforcement model
- **Evidence:** `laravel-app/composer.json` already separates `@php ./vendor/bin/pint --test` from `@composer run architecture:guardrails`; `laravel-app/scripts/architecture_guardrails.php` already exists as the static rule engine.
- **Why now:** architecture enforcement is already partially centralized; mixing responsibility back into `pint` would blur concerns and reduce rule clarity.
- **Options:**
  - **A (Recommended):** Keep `pint` formatting-only; use `architecture_guardrails.php` for static architecture rules; use PHPUnit for runtime/composition checks.
    - Effort: low
    - Risk: low
    - Blast radius: low
    - Maintenance burden: low
  - **B:** Force architecture logic into `pint`-adjacent style enforcement.
    - Effort: medium
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: high
  - **C:** Keep relying primarily on skills/reviewer judgment.
    - Effort: none
    - Risk: high
    - Blast radius: high
    - Maintenance burden: high

### Issue Card I-02
- **Severity:** High
- **Category:** Package route boundary
- **Evidence:** host middleware imports inside package route files:
  - `laravel-app/packages/belluga/belluga_invites/routes/invites.php`
  - `laravel-app/packages/belluga/belluga_map_pois/routes/map_pois.php`
  - `laravel-app/packages/belluga/belluga_settings/routes/settings.php`
  - `laravel-app/packages/belluga/belluga_push_handler/routes/push_handler.php`
  - `laravel-app/packages/belluga/belluga_ticketing/routes/ticketing.php`
- **Why now:** package portability and testability degrade when packages know host middleware classes directly.
- **Options:**
  - **A (Recommended):** Add explicit route ownership mode per package and block `App\\Http\\Middleware\\...` imports in package route files through guardrails.
    - Effort: medium
    - Risk: low
    - Blast radius: medium
    - Maintenance burden: low
  - **B:** Keep mixed route ownership and rely on reviewer judgment for future packages.
    - Effort: none
    - Risk: high
    - Blast radius: medium
    - Maintenance burden: high
  - **C:** Migrate every package to host-owned routes immediately in the same slice.
    - Effort: high
    - Risk: medium
    - Blast radius: high
    - Maintenance burden: medium

### Issue Card I-03
- **Severity:** Medium
- **Category:** Host binding verification
- **Evidence:** host-required contracts are fail-fast checked in package service providers and separately asserted by `delphi-ai/skills/wf-laravel-create-package-method/scripts/assert_package_decoupling.py`, but `laravel-app/scripts/architecture_guardrails.php` does not yet fully own that rule.
- **Why now:** critical package integration rules should fail consistently in repo CI even if the workflow script is skipped.
- **Options:**
  - **A (Recommended):** Add deterministic host-binding verification to `architecture_guardrails.php`, keeping the workflow assertion script as a package-refactor helper rather than the only gate.
    - Effort: medium
    - Risk: low
    - Blast radius: medium
    - Maintenance burden: medium
  - **B:** Rely on provider boot-time failures only.
    - Effort: none
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: medium
  - **C:** Rely on workflow script only.
    - Effort: none
    - Risk: high
    - Blast radius: high
    - Maintenance burden: high

### Issue Card I-04
- **Severity:** Medium
- **Category:** Governance surface sync
- **Evidence:** `wf-laravel-create-package-method` and its Cline/workflow counterparts are the right place for authoring guidance, but can drift from repo-enforced rules if rule IDs and terminology are not shared.
- **Why now:** the user explicitly wants skills updated too, but not trusted in isolation.
- **Options:**
  - **A (Recommended):** Mirror the same rule IDs and package mode vocabulary across guardrails, skill, and workflow surfaces; validate sync with `bash delphi-ai/tools/verify_adherence_sync.sh`.
    - Effort: low
    - Risk: low
    - Blast radius: low
    - Maintenance burden: low
  - **B:** Update only the canonical skill and skip mirrored workflow surfaces.
    - Effort: low
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: medium
  - **C:** Do not update skills/workflows in this slice.
    - Effort: none
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: high

---

## Failure Modes & Edge Cases
- A package route file imports a host middleware class and passes review because the package `src/**` remains clean.
- A package is declared `host-owned-routes` but still calls `loadRoutesFrom(...)` in its provider.
- A host-integrated package adds a required contract without a guardrail-visible binding declaration and CI does not fail early enough.
- Two packages communicate through both direct contract calls and host event listeners for the same flow, creating duplicate seams and drift.
- Skills describe a newer rule than CI enforces, or CI enforces a rule that the skill no longer teaches.
- `AppServiceProvider.php` regains package-specific imports/bindings and becomes the only composition hotspot again.

---

## Uncertainty Register
- **Assumptions:**
  - Current package mode classification can be inferred and then frozen explicitly during implementation.
  - Route ownership can be represented in a lightweight registry or manifest without adding unnecessary framework complexity.
- **Unknowns:**
  - Whether any package must remain `package-owned-routes` for operational reasons after the guardrail is introduced.
  - Whether host-binding verification belongs in a static registry, package metadata, or a dedicated test helper.
- **Confidence:** Medium.

---

## Decision Baseline (Frozen)
- `D-PKG-01`: `pint` remains formatting-only; static architecture enforcement belongs in `laravel-app/scripts/architecture_guardrails.php`.
- `D-PKG-02`: Package core source remains isolated: `packages/*/*/src/**` may not reference `App\\...` or another package namespace directly.
- `D-PKG-03`: Cross-package communication must use an explicit seam:
  - host-bound contracts/adapters, or
  - package domain events consumed by host listeners/jobs, or
  - shared-kernel contracts/registries.
- `D-PKG-04`: Each package must declare an integration classification:
  - `self-contained`
  - `host-integrated`
  - `shared-kernel`
- `D-PKG-05`: Each package with routes must declare route ownership:
  - `host-owned-routes`
  - `package-owned-routes`
- `D-PKG-06`: `package-owned-routes` may not import host middleware classes; only approved aliases/config strings or package-local middleware are acceptable.
- `D-PKG-07`: `host-owned-routes` packages should not auto-register host-facing route files from their service providers.
- `D-PKG-08`: Host-required contracts must have fail-fast binding expectations and deterministic validation evidence in repo-controlled enforcement.
- `D-PKG-09`: Skills/workflows and Cline mirrors must use the same rule IDs and terminology as the repo-enforced guardrails.
- `D-PKG-10`: `composer run lint:strict` remains the canonical local entrypoint for formatting + architecture validation.
- `D-PKG-11`: For the current Belluga package set, route ownership is standardized to `host-owned-routes`; package route files and `loadRoutesFrom(...)` are removed from those packages.
- `D-PKG-12`: `AppServiceProvider.php` must remain free of package integration composition; package bindings/listeners/settings registrars belong in dedicated host integration providers.

---

## Module Coherence Gate (Mandatory)

Before requesting **APROVADO** and again before TODO closure:
1. Compare each `D-PKG-xx` decision against the canonical module anchors.
2. Record status per decision: `Aligned`, `Conflict`, or `Supersede`.
3. For every `Conflict` or `Supersede`, capture:
   - module reference,
   - rationale,
   - `Preserve` or `Supersede` intent.
4. Do not execute implementation with unresolved `Conflict`.

---

## Decision Adherence Validation
_Post-implementation adherence validation._

| Decision | Status | Module Coherence | Change Intent | Evidence | Notes |
| --- | --- | --- | --- | --- | --- |
| `D-PKG-01` | Adherent | Aligned | Preserve | `laravel-app/composer.json`, `laravel-app/scripts/architecture_guardrails.php`, `docker exec belluga_now_docker-app-1 sh -lc 'cd /var/www && composer run lint:strict'` | `pint` remained formatting-only; architecture rules stayed in dedicated guardrail script. |
| `D-PKG-02` | Adherent | Aligned | Preserve | `laravel-app/scripts/architecture_guardrails.php`, host-run `assert_package_decoupling.py` for all six packages | Package `src/**` boundary and cross-package import ban remain enforced. |
| `D-PKG-03` | Adherent | Aligned | Preserve | `laravel-app/app/Integration/**`, `laravel-app/app/Listeners/**`, `laravel-app/app/Providers/PackageIntegration/**` | No direct package-to-package imports were introduced; canonical seams are contract/adapters and host listeners/jobs. |
| `D-PKG-04` | Adherent | Aligned | Preserve | `laravel-app/scripts/package_architecture_registry.php` | All current Belluga packages now declare `integration_mode`. |
| `D-PKG-05` | Adherent | Aligned | Preserve | `laravel-app/scripts/package_architecture_registry.php` | All current Belluga packages now declare `route_ownership`. |
| `D-PKG-06` | Adherent | Aligned | Preserve | `laravel-app/routes/api/packages/**`, `find laravel-app/packages/belluga -path '*/routes/*.php'`, `composer run architecture:guardrails` | No package-owned route files remain in the current Belluga package set. |
| `D-PKG-07` | Adherent | Aligned | Preserve | `laravel-app/packages/belluga/belluga_events/src/EventsServiceProvider.php`, `laravel-app/scripts/package_architecture_registry.php`, `laravel-app/scripts/architecture_guardrails.php` | Host-owned route mode is explicitly represented and checked. |
| `D-PKG-08` | Adherent | Aligned | Preserve | `laravel-app/scripts/architecture_guardrails.php`, host-run `assert_package_decoupling.py --check-host-bindings` | Host binding declarations are now covered by repo guardrail and still validated by the helper assertion. |
| `D-PKG-09` | Adherent | Aligned | Preserve | `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md`, `delphi-ai/.cline/skills/wf-laravel-create-package-method/SKILL.md`, `delphi-ai/workflows/laravel/create-package-method.md`, `delphi-ai/.clinerules/workflows/laravel-create-package-method.md`, `bash ../delphi-ai/tools/verify_adherence_sync.sh` | Skill/workflow terminology and rule IDs were synchronized. |
| `D-PKG-10` | Adherent | Aligned | Preserve | `docker exec belluga_now_docker-app-1 sh -lc 'cd /var/www && composer run lint:strict'`, `../laravel-app/scripts/delphi/run_laravel_tests_safe.sh --stop-on-failure` | `lint:strict` remains the canonical local command entrypoint, and the full Laravel suite now passes under the local-safe test runner envelope. |
| `D-PKG-11` | Adherent | Aligned | Preserve | `laravel-app/routes/api/packages/**`, `laravel-app/packages/belluga/*/src/*ServiceProvider.php`, `laravel-app/scripts/package_architecture_registry.php` | Host-owned route migration completed for all current Belluga packages with route surfaces. |
| `D-PKG-12` | Adherent | Aligned | Preserve | `laravel-app/app/Providers/AppServiceProvider.php`, `laravel-app/app/Providers/PackageIntegration/**`, `laravel-app/scripts/architecture_guardrails.php` | `AppServiceProvider` is package-agnostic and guardrailed against re-centralization. |

---

## Workstreams

### WS-00 Baseline Audit and Classification
- [x] Production-Ready Record current package audit findings and package list.
- [x] Production-Ready Classify each current Laravel package by integration mode and route ownership mode.
- [x] Production-Ready Capture any approved exceptions required for current packages to pass the first guardrail rollout.

### WS-01 Guardrail Rule Expansion
- [x] Production-Ready Add route-boundary rules to `laravel-app/scripts/architecture_guardrails.php`.
- [x] Production-Ready Add explicit route ownership validation (`host-owned-routes` vs `package-owned-routes`).
- [x] Production-Ready Add host-binding validation rule or registry mechanism in repo-controlled enforcement.
- [x] Production-Ready Confirm `composer run lint:strict` remains the single local command entrypoint.
- [x] Production-Ready Add `AppServiceProvider` package-composition isolation guardrail.

### WS-02 Skill and Workflow Convergence
- [x] Production-Ready Update `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md` with the frozen package rule IDs and route ownership matrix.
- [x] Production-Ready Mirror the same content in `delphi-ai/.cline/skills/wf-laravel-create-package-method/SKILL.md`.
- [x] Production-Ready Update workflow counterparts if behavior wording changes:
  - `delphi-ai/workflows/laravel/create-package-method.md`
  - `delphi-ai/.clinerules/workflows/laravel-create-package-method.md`
- [x] Production-Ready Run `bash delphi-ai/tools/verify_adherence_sync.sh`.

### WS-03 Validation
- [x] Production-Ready Run targeted package guardrail validation locally.
- [x] Production-Ready Run `composer run architecture:guardrails`.
- [x] Production-Ready Run `composer run lint:strict`.
- [x] Production-Ready Decide whether touched runtime/composition rules need a focused PHPUnit architecture test.
  - Decision: not required in this slice; static guardrails plus the existing decoupling assertion cover the introduced governance changes.
- [x] Production-Ready Run full Laravel suite with local test-runtime envelope.
  - Evidence: `../laravel-app/scripts/delphi/run_laravel_tests_safe.sh --stop-on-failure` -> `866 passed (3465 assertions)`.
  - Additional remediation captured: `laravel-app/tests/TestCase.php` and `laravel-app/tests/TestCaseTenant.php` now honor effective host overrides when normalizing relative test URLs, preventing false 404s against tenant/account domain-scoped routes after the host-owned route migration.

---

## Approval Prompt
- [x] Production-Ready Plan review completed.
- [x] Production-Ready User replied `APROVADO`.
- [x] Production-Ready Implementation may begin.
