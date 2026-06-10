# TODO: Agnostic Adjust Tooling Ownership Migration

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context

The `agnostic_adjust` analysis confirmed a narrower generic-tooling migration slice that should not stay Belluga-root-owned:

- `tools/flutter/build_web_bundle.sh` is ecosystem-generic under the Delphi Flutter baseline and should converge to the canonical Delphi web build script.
- `tools/submodules/*` is reusable multi-repo topology tooling and should become Delphi-owned by discovering workspace structure from `.gitmodules` rather than hardcoding Belluga paths.

The user also approved the generic/content split rule for recurring project-specific needs: Delphi may own reusable templates/examples/contracts, while downstream projects keep the concrete content.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/agnostic-adjust-tooling-ownership-migration.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** it promotes the clearest generic tooling first without reclassifying all browser specs or touching product behavior.

## Contract Boundary
- This TODO authorizes the first tooling-ownership migration slice across the root checkout and `delphi-ai`.
- The slice is limited to generic tooling promotion plus root-wrapper convergence.
- Product-specific Playwright specs, deep-link payloads, route inventories, and domain assertions stay outside this TODO.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Cross-Stack`, `Tooling-Ownership`, `Agnostic-Adjust`
- **Next exact step:** review the migrated ownership cluster with the user and decide whether the next wave should stop here or continue into generic browser-harness templates/examples without moving product-specific specs.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `review`
- **Why this state now:** the bounded migration slice is implemented and targeted local validation passed, but broader follow-up slicing is still intentionally open.
- **Exit condition:** touched Delphi/root tooling is migrated, wrappers/docs are coherent, and targeted local validation passes.

## Scope
- [x] Converge `tools/flutter/build_web_bundle.sh` to a thin root wrapper over the canonical Delphi Flutter web build script while preserving the root contract used today.
- [x] Promote the reusable `tools/submodules/*` implementation into canonical `delphi-ai` tooling that derives submodule topology from `.gitmodules`.
- [x] Keep backward-compatible thin root wrappers for the migrated submodule commands.
- [x] Update root documentation so canonical ownership is explicit.
- [x] Preserve all product-specific browser specs and business assertions in the downstream project.

## Out of Scope
- [ ] Move `tools/flutter/web_app_tests/**/*.spec.js` into `delphi-ai`.
- [ ] Genericize Belluga/Bóora route paths, hosts, deeplink payloads, or domain assertions.
- [ ] Change product/runtime behavior in `laravel-app`, `flutter-app`, or `web-app`.
- [ ] Reorganize every `tools/flutter/**` surface in this same slice.

## Definition of Done
- [x] `DOD-01` The generic implementation for the touched tooling cluster lives in `delphi-ai/**`.
- [x] `DOD-02` Root entrypoints remain available only as thin wrappers or project-owned convenience surfaces.
- [x] `DOD-03` Submodule tooling no longer hardcodes Belluga submodule paths in its canonical implementation.
- [x] `DOD-04` Root docs describe the migrated tooling ownership coherently.
- [x] `DOD-05` Targeted local validation passes for touched shell/tool surfaces.

## Validation Steps
- [x] Run `bash -n` on every touched shell script in root and `delphi-ai`.
- [x] Run the migrated root wrappers with `--help` or safe read-only arguments when supported.
- [x] Run the canonical Delphi submodule status helper in read-only mode.
- [x] Run the root web build wrapper with `--help` or equivalent non-mutating contract validation.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-devops`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-devops`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the slice crosses root wrappers, Delphi canonical tooling, docs, and validation, but it remains bounded away from product-functional surfaces.

## Approval
- **Approved by:** user, conversation on `2026-06-08`
- **Approval evidence:** "Perfeito. A partir disso você consegue fazer esse processo de migração?"
- **Approval scope:** implement the first bounded tooling-ownership migration slice by promoting clearly generic tooling to `delphi-ai`, preserving thin root wrappers where appropriate, and keeping project-specific content downstream.
- **Renewed-approval trigger:** any attempt to migrate product-specific browser specs, deep-link content, route maps, or domain assertions into Delphi.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Governs Delphi/project authority split and additive stack capability model. | Generic Delphi tooling lives in `delphi-ai`; project content remains downstream. | Smuggling Belluga-specific content into Delphi. | Frames the ownership migration boundary. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | The slice is operational but spans root + Delphi tooling. | `Operational / DevOps` with explicit cross-stack scope. | Silent profile drift. | Justifies touching root wrappers and Delphi scripts/tools. |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | Implementation requires tactical TODO authority. | Approval, bounded scope, and validation evidence. | Expanding scope without authority. | Keeps the migration in a governed execution lane. |
| `delphi-ai/workflows/docker/todo-driven-execution-method.md` | Canonical state machine for this TODO. | Approval -> execution -> validation flow. | Skipping phases. | Governs execution and closeout. |
| `delphi-ai/ecosystem_template_configuration.md` | Delphi capabilities are additive; projects activate only the surfaces they use. | Flutter tooling remains reusable at Delphi level. | Treating project content as global activation truth. | Supports promoting generic tooling out of the project root. |
| `foundation_documentation/project_constitution.md` | The root remains orchestration authority for this project. | Root may keep convenience wrappers while canonical generic logic moves upstream. | Breaking the downstream runtime boundary. | Keeps migration thin at the root boundary. |

## Touched Surfaces
- `foundation_documentation/artifacts/feature-briefs/agnostic-adjust-tooling-ownership-migration.md`
- `foundation_documentation/todos/active/agnostic_adjust/TODO-agnostic-adjust-tooling-ownership-migration.md`
- `tools/flutter/build_web_bundle.sh`
- `tools/submodules/*`
- `README.md`
- `delphi-ai/scripts/flutter/build_web.sh` or wrapper-facing adjacent support as required
- `delphi-ai/tools/**` and `delphi-ai/tools/manifest.md` for new canonical tooling

## Ordered Steps
1. Open the bounded migration TODO and re-run TODO authority guard.
2. Converge the root web build entrypoint to a thin wrapper over the canonical Delphi build surface.
3. Promote the generic submodule workspace helpers into canonical Delphi tooling and leave thin root wrappers.
4. Update root docs to point at the new canonical ownership model.
5. Run targeted shell/read-only validation and record any residual follow-up.
