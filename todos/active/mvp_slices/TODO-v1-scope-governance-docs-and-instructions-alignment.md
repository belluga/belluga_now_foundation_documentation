# TODO (V1): Scope Governance Docs + Instructions Alignment
**Version:** 1.2
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed (Production‑Ready)
**Owners:** Platform + Foundation Docs + Delphi-AI Governance

## Context
The environment/scope split is implemented in code, but documentation and Delphi operational instructions still contain mixed route/module language from pre-subscope phases.

This TODO hardens the governance layer so route/module/screen work is always interpreted under the same subscope boundaries, with no room for ambiguous file placement or implicit “new subscope” creation.

## Inventory Snapshot (What Was Scanned)
- **Project docs corpus scanned:** `foundation_documentation/**/*.md` with route/module/scope keywords.
- **Delphi governance corpus scanned:** `delphi-ai/main_instructions.md`, `delphi-ai/system_architecture_principles.md`, `delphi-ai/rules/**/*.md`, `delphi-ai/workflows/**/*.md`, `delphi-ai/skills/**/SKILL.md`, templates/personas/checklists.
- **Resulting pattern:** authoritative docs and governance docs both contain route/screen/module guidance, but not all of them explicitly encode the subscope policy.

## Meaning + Direction Found in Current Material
### 1) Terminology Drift (`environment`, `scope`, `subscope`, `module area`)
- **Found meaning:** multiple docs still use “environment/module area” wording where the current model requires explicit `main scope` + `subscope`.
- **Direction implied:** teams can interpret ownership either by legacy folder names or by current scope terms.
- **Planned normalization:** enforce one vocabulary chain everywhere:
  - `EnvironmentType`: `landlord | tenant` only.
  - `Main scopes`: `site_public`, `landlord_area`, `tenant_public`, `tenant_admin`.
  - `Subscope`: `account_workspace` only (current approved set).

### 2) Route Expectations Spread Across Multiple Sources
- **Found meaning:** canonical routes are documented in more than one place, with historical references still visible.
- **Direction implied:** different sources can be read as equally normative.
- **Planned normalization:** introduce one canonical policy doc and make other docs reference it explicitly instead of redefining route semantics ad hoc.

### 3) Module Ownership Is Not Uniformly Explicit by Subscope
- **Found meaning:** module docs often describe responsibilities without a mandatory “subscope ownership matrix”.
- **Direction implied:** modules spanning more than one subscope can be implemented with unclear boundaries.
- **Planned normalization:** require each authoritative module doc to declare:
  - primary subscope ownership,
  - optional secondary subscope surfaces,
  - explicit route/subscope matrix when spanning multiple subscopes.

### 4) Delphi Route/Screen Instructions Do Not Yet Hard-Block Wrong Placement
- **Found meaning:** route/screen workflows focus on structure/AutoRoute/ModuleScope but not always on subscope boundary validation.
- **Direction implied:** new files can be created in legacy or ambiguous folders unless manually reviewed.
- **Planned normalization:** add hard guidance in rules/workflows/skills:
  - validate target subscope before creating route/screen files,
  - forbid creation of new subscope folders unless explicitly decided,
  - central reference to canonical scope policy.

### 5) Derived `web-app` Boundary Was Not Encoded as Governance Rule
- **Found meaning:** tests in `web-app` can be edited directly, even though repo is derived.
- **Direction implied:** changes risk non-persistence.
- **Planned normalization:** document and enforce that `web-app` test assets are source-owned elsewhere and synced via build tooling.

## Canonical Direction for This Pass (No New Product Decision)
This TODO **does not re-decide** route semantics; it codifies and propagates the already accepted V1 scope model.

Source decisions to enforce:
- `EnvironmentType` binary: `landlord`, `tenant`.
- Fixed scope set for current V1 governance:
  - main scopes: `site_public`, `landlord_area`, `tenant_public`, `tenant_admin`
  - subscope: `account_workspace`
- New subscopes are forbidden unless explicitly approved and documented first.

## Scope
- Establish one canonical **Scope/Subscope Governance Policy** in `foundation_documentation/policies/` as source-of-truth.
- Align authoritative project docs that define routes/modules/screens to that policy.
- Require module docs to state subscope ownership explicitly; multi-subscope modules must include a route/subscope matrix.
- Update Delphi instructions/rules/workflows/skills that govern route/screen/module operations so subscope checks are mandatory.
- Keep Cline/Codex/Antigravity compatibility by syncing equivalent Delphi rule/workflow/skill surfaces.

## Out of Scope
- Rewriting historical details inside `foundation_documentation/todos/completed/**` and `foundation_documentation/todos/ephemeral/**`.
- Functional feature implementation unrelated to route/scope governance.
- Creating any new subscope in this pass.

## Decisions
- [x] ✅ Production‑Ready Canonical runtime environment remains binary: `landlord` and `tenant` only.
- [x] ✅ Production‑Ready Canonical scope/subscope catalog remains fixed for now:
  - main scopes: `site_public`, `landlord_area`, `tenant_public`, `tenant_admin`
  - subscope: `account_workspace`
- [x] ✅ Production‑Ready `account_workspace` is the only tenant subscope currently approved; no new subscopes are allowed without explicit decision.
- [x] ✅ Production‑Ready `web-app` is a derived/compiled repository; route test sources must live in source-owned locations and be synced into `web-app` by build tooling.
- [x] ✅ Production‑Ready When a module owns routes in multiple subscopes, docs must declare this explicitly via a route/subscope matrix.
- [x] ✅ Production‑Ready Authoritative-vs-historical rule: canonical policy + authoritative docs govern current behavior; archived TODOs are historical evidence only.

## Questions To Close
- [x] ✅ Production‑Ready None at draft time.

## Detailed Task Plan
### Phase 1 — Canonical Policy Authoring
- [x] ✅ Production‑Ready Create `foundation_documentation/policies/scope_subscope_governance.md` with:
  - definitions (`EnvironmentType`, main scope, subscope),
  - current approved scope list,
  - canonical ownership boundaries,
  - “no new subscope without explicit decision” rule,
  - route/subscope matrix contract template,
  - authoritative-vs-historical interpretation rule.
- [x] ✅ Production‑Ready Add early-load cross-reference pointers in Delphi source-of-truth entry points:
  - `delphi-ai/main_instructions.md` (Source of Truth + workflow discipline references),
  - `delphi-ai/initialization_checklist.md` (startup loading checklist).

### Phase 2 — Authoritative Project Docs Alignment
- [x] ✅ Production‑Ready Update authoritative foundation entry docs to mark `policies/scope_subscope_governance.md` as mandatory reading before route/module edits:
  - `foundation_documentation/system_roadmap.md`,
  - `foundation_documentation/submodule_flutter-app_summary.md`,
  - `foundation_documentation/submodule_laravel-app_summary.md`.
- [x] ✅ Production‑Ready Update `foundation_documentation/system_roadmap.md` with a governance note that route/module work must declare scope/subscope ownership and cannot introduce new subscopes without explicit decision.
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/flutter_client_experience_module.md` to:
  - reference the canonical policy path,
  - require explicit subscope attribution for client routes/screens,
  - include route/subscope matrix where modules span more than one subscope.
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/tenant_admin_module.md` to:
  - make `tenant_admin` ownership explicit,
  - define explicit touchpoints with `account_workspace`,
  - avoid ambiguous wording such as “admin area” without scope tag.
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/tenant_home_composer_module.md` to:
  - make `tenant_public` ownership explicit,
  - define permitted transitions to `tenant_admin` and `account_workspace`.
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_landlord_app.md` to:
  - explicitly tag each screen/route with `site_public` vs `landlord_area`,
  - remove language that could imply extra landlord subscopes.
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_tenant_admin.md` to:
  - explicitly tag `tenant_admin` surfaces,
  - define boundaries with `tenant_public` and `account_workspace`.
- [x] ✅ Production‑Ready Update `foundation_documentation/submodule_flutter-app_summary.md` to include the canonical presentation ownership model by subscope and forbid legacy folder guidance.
- [x] ✅ Production‑Ready Update `foundation_documentation/submodule_laravel-app_summary.md` to include canonical scope/subscope ownership expectations for route/module contracts exposed to Flutter/web clients.

### Phase 3 — Delphi Core Instruction Alignment
- [x] ✅ Production‑Ready Update `delphi-ai/main_instructions.md`:
  - add canonical policy load requirement in early context-loading steps,
  - state that route/screen/module work is invalid without scope/subscope context,
  - state that undefined subscopes cannot be created without explicit decision.
- [x] ✅ Production‑Ready Update `delphi-ai/system_architecture_principles.md`:
  - add a first-class governance principle for scope/subscope boundaries,
  - bind route/module ownership and transitions to explicit scope contracts.
- [x] ✅ Production‑Ready Update `delphi-ai/initialization_checklist.md`:
  - include a startup verification step that scope/subscope policy reference is present/loaded for route-related sessions.
- [x] ✅ Production‑Ready Update session lifecycle references (rule/workflow side) to ensure this context is treated as “always-on” before implementation:
  - `delphi-ai/rules/docker/shared/session-lifecycle-model-decision.md`
  - `delphi-ai/rules/laravel/shared/session-lifecycle-model-decision.md`
  - `delphi-ai/workflows/docker/session-lifecycle-method.md`

### Phase 4 — Route/Screen/Module Governance Enforcement
- [x] ✅ Production‑Ready Update Flutter route/screen rules to enforce subscope boundary checks:
  - `delphi-ai/rules/flutter/flutter-route-workflow-glob.md`
  - `delphi-ai/rules/flutter/flutter-screen-workflow-glob.md`
  - `delphi-ai/rules/flutter/flutter-architecture-always-on.md`
- [x] ✅ Production‑Ready Update shared route/doc sync rules to reference canonical policy path:
  - `delphi-ai/rules/docker/shared/foundation-docs-sync-model-decision.md`
  - `delphi-ai/rules/laravel/shared/foundation-docs-sync-model-decision.md`
  - explicitly encode `web-app` as derived/compiled and require source-owned tests synced by build tooling.
- [x] ✅ Production‑Ready Update route/screen workflows with mandatory scope-validation step:
  - `delphi-ai/workflows/flutter/create-route-method.md`
  - `delphi-ai/workflows/flutter/create-screen-method.md`
- [x] ✅ Production‑Ready Update docs migration workflow so route/module migration outputs must include scope/subscope ownership:
  - `delphi-ai/workflows/docker/documentation-migration-method.md`
  - include explicit prohibition for direct `web-app` test authoring.
- [x] ✅ Production‑Ready Add explicit text in applicable rules/workflows:
  - no undefined subscope creation,
  - no file placement outside canonical scope/shared boundaries,
  - no ambiguous route ownership statements.

### Phase 5 — Skills Sync (Cline/Codex/Antigravity)
- [x] ✅ Production‑Ready Update skill wrappers that directly govern route/screen/module behavior:
  - `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
  - `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
  - `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
  - `delphi-ai/skills/wf-flutter-create-route-method/SKILL.md`
  - `delphi-ai/skills/wf-flutter-create-screen-method/SKILL.md`
- [x] ✅ Production‑Ready Update shared governance skill wrappers so they explicitly reference the same canonical policy source:
  - `delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`
  - `delphi-ai/skills/rule-laravel-shared-foundation-docs-sync-model-decision/SKILL.md`
  - `delphi-ai/skills/wf-docker-documentation-migration-method/SKILL.md`
- [x] ✅ Production‑Ready Update lifecycle/readiness governance skill wrappers to stay synced with updated lifecycle/load rules:
  - `delphi-ai/skills/rule-docker-shared-session-lifecycle-model-decision/SKILL.md`
  - `delphi-ai/skills/rule-laravel-shared-session-lifecycle-model-decision/SKILL.md`
  - `delphi-ai/skills/wf-docker-session-lifecycle-method/SKILL.md`
  - `delphi-ai/skills/rule-docker-shared-initialization-readiness-model-decision/SKILL.md`
  - `delphi-ai/skills/rule-laravel-shared-initialization-readiness-model-decision/SKILL.md`
- [x] ✅ Production‑Ready Ensure skill text does not contain contradictory legacy placement guidance (e.g., outdated folder roots or implicit new-subscope creation paths).

### Phase 6 — Sync + Validation
- [x] ✅ Production‑Ready Run `bash delphi-ai/tools/verify_context.sh` to sync mirrors in `.agent/**`, `flutter-app/.agent/**`, `laravel-app/.agent/**`.
- [x] ✅ Production‑Ready Run consistency grep checks over authoritative docs/governance to confirm:
  - canonical scope vocabulary is used,
  - no ambiguous/contradictory subscope guidance remains,
  - no instruction path permits implicit new subscopes.
- [x] ✅ Production‑Ready Run targeted grep checks for governance linkage:
  - canonical policy path is referenced in updated rules/workflows/skills,
  - route/screen workflows include explicit scope-validation step,
  - authoritative docs include explicit subscope ownership language.
- [x] ✅ Production‑Ready Produce a brief audit summary in this TODO listing:
  - files touched,
  - ambiguity classes removed,
  - remaining follow-ups (if any).

## Audit Summary
- Files touched:
  - Canonical policy + project docs:
    - `foundation_documentation/policies/scope_subscope_governance.md` (new)
    - `foundation_documentation/system_roadmap.md`
    - `foundation_documentation/modules/flutter_client_experience_module.md`
    - `foundation_documentation/modules/tenant_admin_module.md`
    - `foundation_documentation/modules/tenant_home_composer_module.md`
    - `foundation_documentation/screens/modulo_landlord_app.md`
    - `foundation_documentation/screens/modulo_tenant_admin.md`
    - `foundation_documentation/submodule_flutter-app_summary.md`
    - `foundation_documentation/submodule_laravel-app_summary.md`
  - Delphi core/rules/workflows:
    - `delphi-ai/main_instructions.md`
    - `delphi-ai/system_architecture_principles.md`
    - `delphi-ai/initialization_checklist.md`
    - `delphi-ai/rules/docker/documentation-migration-model-decision.md`
    - `delphi-ai/rules/docker/shared/session-lifecycle-model-decision.md`
    - `delphi-ai/rules/laravel/shared/session-lifecycle-model-decision.md`
    - `delphi-ai/rules/docker/shared/initialization-readiness-model-decision.md`
    - `delphi-ai/rules/laravel/shared/initialization-readiness-model-decision.md`
    - `delphi-ai/rules/flutter/flutter-route-workflow-glob.md`
    - `delphi-ai/rules/flutter/flutter-screen-workflow-glob.md`
    - `delphi-ai/rules/flutter/flutter-architecture-always-on.md`
    - `delphi-ai/rules/docker/shared/foundation-docs-sync-model-decision.md`
    - `delphi-ai/rules/laravel/shared/foundation-docs-sync-model-decision.md`
    - `delphi-ai/workflows/docker/session-lifecycle-method.md`
    - `delphi-ai/workflows/docker/documentation-migration-method.md`
    - `delphi-ai/workflows/flutter/create-route-method.md`
    - `delphi-ai/workflows/flutter/create-screen-method.md`
  - Delphi skills:
    - `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
    - `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
    - `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
    - `delphi-ai/skills/wf-flutter-create-route-method/SKILL.md`
    - `delphi-ai/skills/wf-flutter-create-screen-method/SKILL.md`
    - `delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`
    - `delphi-ai/skills/rule-laravel-shared-foundation-docs-sync-model-decision/SKILL.md`
    - `delphi-ai/skills/wf-docker-documentation-migration-method/SKILL.md`
    - `delphi-ai/skills/rule-docker-documentation-migration-model-decision/SKILL.md`
    - `delphi-ai/skills/rule-docker-shared-session-lifecycle-model-decision/SKILL.md`
    - `delphi-ai/skills/rule-laravel-shared-session-lifecycle-model-decision/SKILL.md`
    - `delphi-ai/skills/wf-docker-session-lifecycle-method/SKILL.md`
    - `delphi-ai/skills/rule-docker-shared-initialization-readiness-model-decision/SKILL.md`
    - `delphi-ai/skills/rule-laravel-shared-initialization-readiness-model-decision/SKILL.md`
    - `delphi-ai/.cline/skills/flutter-architecture-adherence/SKILL.md` (mirror sync)
- Ambiguity classes removed:
  - Source-of-truth ambiguity (`foundation_documentation/README.md` dependency removed).
  - Terminology ambiguity (explicit `EnvironmentType` + main scope + subscope vocabulary across docs and governance).
  - Placement ambiguity (rules/workflows/skills now reject implicit/undefined subscopes and ambiguous legacy placement).
  - Ownership ambiguity (authoritative modules/screens now contain explicit scope/subscope matrices).
  - `web-app` governance ambiguity (derived boundary and source-owned test policy encoded in rules/workflows/skills/policy).
- Remaining follow-ups:
  - Context verification warning remains external to this TODO scope: `foundation_documentation` submodule commit mismatch in current workspace.

## Definition of Done
- [x] ✅ Production‑Ready Canonical scope/subscope policy exists and is referenced by authoritative project docs.
- [x] ✅ Production‑Ready Authoritative module docs explicitly state subscope ownership.
- [x] ✅ Production‑Ready Multi-subscope modules include explicit route/subscope matrix.
- [x] ✅ Production‑Ready Delphi main instructions require early scope-policy context.
- [x] ✅ Production‑Ready Delphi architecture principles include scope-boundary principle.
- [x] ✅ Production‑Ready Route/screen/module related rules/workflows/skills reference and enforce canonical policy.
- [x] ✅ Production‑Ready Skill wrappers are synced for every rule/workflow surface changed in this TODO (including lifecycle/readiness surfaces).
- [x] ✅ Production‑Ready `web-app` derived-repo boundary is explicitly enforced in docs/governance (source-owned tests + sync tooling).
- [x] ✅ Production‑Ready Context sync passes and no authoritative documentation ambiguity remains for scope boundaries.

## Validation Steps
- `bash delphi-ai/tools/verify_context.sh`
- `rg -n "site_public|landlord_area|tenant_public|tenant_admin|account_workspace|subscope|EnvironmentType" foundation_documentation delphi-ai -g '*.md'`
- `rg -n "new subscope|no new subscope|explicit decision" foundation_documentation delphi-ai -g '*.md'`
- `rg -ni "route/subscope matrix|subscope ownership" foundation_documentation/modules -g '*.md'`
- `rg -n "scope_subscope_governance\\.md" foundation_documentation delphi-ai -g '*.md'`
- `rg -n "web-app.*derived|derived.*web-app|source-owned.*web-app|web-app.*source-owned|synced into web-app|direct web-app test authoring" foundation_documentation delphi-ai -g '*.md'`
- `rg -n "session-lifecycle|initialization-readiness|scope_subscope_governance\\.md" delphi-ai/skills -g 'SKILL.md'`

## Files Expected (Targeted)
### Canonical policy + project docs
- `foundation_documentation/policies/scope_subscope_governance.md` (new/authoritative source)
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/screens/modulo_landlord_app.md`
- `foundation_documentation/screens/modulo_tenant_admin.md`
- `foundation_documentation/submodule_flutter-app_summary.md`
- `foundation_documentation/submodule_laravel-app_summary.md`

### Delphi core governance
- `delphi-ai/main_instructions.md`
- `delphi-ai/system_architecture_principles.md`
- `delphi-ai/initialization_checklist.md`
- `delphi-ai/rules/docker/shared/session-lifecycle-model-decision.md`
- `delphi-ai/rules/laravel/shared/session-lifecycle-model-decision.md`
- `delphi-ai/workflows/docker/session-lifecycle-method.md`
- `delphi-ai/rules/flutter/flutter-route-workflow-glob.md`
- `delphi-ai/rules/flutter/flutter-screen-workflow-glob.md`
- `delphi-ai/rules/flutter/flutter-architecture-always-on.md`
- `delphi-ai/rules/docker/shared/foundation-docs-sync-model-decision.md`
- `delphi-ai/rules/laravel/shared/foundation-docs-sync-model-decision.md`
- `delphi-ai/workflows/flutter/create-route-method.md`
- `delphi-ai/workflows/flutter/create-screen-method.md`
- `delphi-ai/workflows/docker/documentation-migration-method.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/wf-flutter-create-route-method/SKILL.md`
- `delphi-ai/skills/wf-flutter-create-screen-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-foundation-docs-sync-model-decision/SKILL.md`
- `delphi-ai/skills/wf-docker-documentation-migration-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-session-lifecycle-model-decision/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-session-lifecycle-model-decision/SKILL.md`
- `delphi-ai/skills/wf-docker-session-lifecycle-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-initialization-readiness-model-decision/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-initialization-readiness-model-decision/SKILL.md`

### Generated mirrors (via sync script)
- `.agent/rules/**`, `.agent/workflows/**`
- `flutter-app/.agent/rules/**`, `flutter-app/.agent/workflows/**`
- `laravel-app/.agent/rules/**`, `laravel-app/.agent/workflows/**`
