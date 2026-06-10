# TODO: Agnostic Adjust Example Surface Extraction

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context

The current `agnostic_adjust` front already separated the first clearly generic tooling cluster. The next bounded wave is not broad genericization; it is example/template extraction.

This wave exists because several current project surfaces are structurally reusable across the ecosystem, but their active contents are still Belluga Now-specific:

- local operator env examples;
- Flutter lane define files;
- downstream NGINX route overlays;
- deep-link / app-link payload contract examples.

The user approved the core authoring rule for this wave: current project contents may be reused only as comments, placeholder guidance, or operator-facing reference text inside examples. They must not remain active Boilerplate defaults.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/agnostic-adjust-example-surface-extraction.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** it advances the generic/project separation without moving product behavior or Belluga browser specs, and it gives the Boilerplate a safer operator-facing base for future projects.

## Contract Boundary
- This TODO authorizes only the `.example` / template extraction wave planning and later implementation for the bounded surfaces below.
- The wave must preserve the distinction between reusable structure and project-specific content.
- Current Belluga values may appear only as:
  - commented sample values;
  - placeholder examples;
  - migration/reference notes for operators.
- Current Belluga values must not remain as uncommented active defaults in promoted example/template surfaces.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Cross-Stack`, `Boilerplate-Separation`, `Example-Surfaces`
- **Next exact step:** review the implemented example surfaces with the user and decide whether the next wave should extract generic helper/fixture layers from browser/deep-link tooling that still remain downstream.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `review`
- **Why this state now:** the approved example/template slice is implemented and locally validated, but final user review and any follow-up slicing decisions are still open.
- **Exit condition:** the implemented example surfaces are accepted as the validated cut line for this wave, or a follow-up split is opened for any remaining generic helper extraction.

## Scope
- [x] Promote reusable local operator env examples while keeping concrete Belluga values commented only.
- [x] Define reusable Flutter `config/defines/*.example.json` surfaces from the current lane/override structure.
- [x] Extract a reusable downstream NGINX overlay example surface for project-specific public route families.
- [x] Define reusable deep-link / app-link payload example contracts for well-known endpoints.
- [x] Document which current project-specific values survive only as comments/reference inside those example surfaces.

## Out of Scope
- [ ] Move `tools/flutter/web_app_tests/**/*.spec.js` into Delphi or Boilerplate examples.
- [ ] Promote Belluga public route inventory as active base defaults.
- [ ] Promote Belluga domains, tenant labels, or branding values as active env/define defaults.
- [ ] Change product/runtime behavior in `laravel-app`, `flutter-app`, or `web-app`.
- [ ] Genericize every remaining root surface in the same wave.

## Decision Baseline
- [x] `D-01` Current project values may appear inside promoted examples only as comments, placeholders, or migration/reference guidance.
- [x] `D-02` Example/template extraction must preserve generic structure while leaving active project content downstream.
- [x] `D-03` Browser specs and route semantics remain downstream unless only a clearly generic helper/fixture is being extracted.
- [x] `D-04` NGINX route inventory should be treated as downstream overlay content, not Boilerplate base defaults.
- [x] `D-05` Flutter lane define structure is reusable even when the current values are project-specific.

## Candidate Classification Snapshot
| Surface | Current Signal | Planned Handling | Notes |
| --- | --- | --- | --- |
| `.env.local.navigation.example` | Already mostly generic. | `promote as example` | Keep local smoke structure; current values stay commented only. |
| `.env.example` | Generic shape, but still leaks project logging identity. | `promote as example after neutralization` | Remove active Belluga log/database naming from defaults. |
| `flutter-app/config/defines/local.override.example.json` | Reusable structure, concrete local domain today. | `promote as example` | Keep current topology hints only as comments. |
| `flutter-app/config/defines/{dev,stage,main}.json` | Reusable structure, project-owned values. | `split into *.example.json` | Active lane files stay downstream; examples become reusable. |
| `project/nginx/routes.conf` export concept | Correct reusable mechanism, Belluga-specific content. | `promote mechanism as example only` | Example must be placeholder-driven, not route-literal Belluga default. |
| `tools/flutter/web_app_tests/deeplink_contract.spec.js` | Mixed generic contract + Belluga route semantics. | `needs prior generic split` | Extract helper/fixture contracts only, not the current spec wholesale. |

## Definition of Done
- [x] `DOD-01` Reusable example candidates are explicitly bounded and classified.
- [x] `DOD-02` Every promoted example/template surface avoids Belluga-specific active defaults.
- [x] `DOD-03` Any reused current project content survives only as comments/placeholders/reference guidance.
- [x] `DOD-04` The split between Boilerplate example structure and downstream concrete content remains explicit.
- [x] `DOD-05` The first execution slice is small enough to validate without reopening product-functional scope.

## Validation Steps
- [x] Review every touched example/template surface for uncommented project-specific defaults.
- [x] Verify promoted examples still communicate operator intent clearly after neutralization.
- [x] Re-check that no browser spec, route inventory, or domain payload was promoted wholesale by convenience.
- [x] Record the follow-up extraction boundaries when a candidate requires prior generic split instead of direct promotion.

## Profile Scope & Handoffs
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the wave is bounded, but it crosses env, Flutter config, NGINX overlay, and deep-link contract surfaces while requiring precise ownership discipline.

## Approval
- **Approved by:** user, conversation on `2026-06-08`
- **Approval evidence:** "Perfeito. Pode seguir assim. Pode usar os conteúdos atuais como \"comentário\" nos examplos."
- **Approval scope:** prepare and later execute the bounded `.example` / template extraction wave with the explicit rule that current Belluga contents may survive only as comments/reference guidance, never as active defaults.
- **Renewed-approval trigger:** any attempt to migrate product-specific browser specs, route inventories, or active Belluga domains/branding values into shared Boilerplate defaults.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Governs the Delphi/project authority split. | Generic structure upstream; concrete project content downstream. | Quietly canonizing Belluga values into shared examples. | Frames the content-vs-structure separation. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | This wave is strategic before operational extraction. | Explicit profile/scope declaration and later handoff. | Silent profile drift into implementation. | Keeps this artifact planning-scoped for now. |
| `delphi-ai/ecosystem_template_configuration.md` | Boilerplate examples must stay additive and topology-driven. | Reusable structure without forced project activation. | Treating one project's content as ecosystem truth. | Supports `.example`/template promotion only where structure is truly reusable. |
| `foundation_documentation/project_constitution.md` | Root/orchestration remains project authority here. | Downstream project content stays explicit and local. | Smearing project-specific route/domain identity into shared defaults. | Protects the downstream overlay boundary. |

## Ordered Steps
1. Freeze the candidate list for `.example` / template extraction.
2. Decide whether the first execution slice is `env + defines` only or `env + defines + nginx/deeplink examples`.
3. Execute the smallest approved extraction slice without promoting active Belluga defaults.
4. Validate the resulting examples for neutral defaults and clear operator guidance.

## Implementation Notes
- Local operator examples stayed generic in tracked defaults while the downstream project kept concrete runtime values only in project-owned files or comments/reference notes.
- `flutter-app/config/defines/*.example.json` now captures reusable lane structure while the active tracked lane files remain downstream-owned.
- The shared NGINX templates now consume project-specific public route families through `project/nginx/routes.conf`, and `project/nginx/routes.conf.example` documents the reusable mechanism.
- `project/well-known/*.example.json` now documents App Links / Universal Links payload shape while runtime delivery remains backend-owned.

## Local Validation Evidence
- `docker compose config`
- `timeout 120s node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs`
- `python3` JSON parse check for:
  - `flutter-app/config/defines/local.override.example.json`
  - `flutter-app/config/defines/dev.example.json`
  - `flutter-app/config/defines/stage.example.json`
  - `flutter-app/config/defines/main.example.json`
  - `project/well-known/assetlinks.example.json`
  - `project/well-known/apple-app-site-association.example.json`
- Rendered NGINX syntax validation using `nginx:stable-alpine`, dummy cert mounts, and `--add-host app:127.0.0.1` for both `docker/nginx/local.conf.template` and `docker/nginx/prod.conf.template`

## Local CI-Equivalent Suite Matrix
| Suite / Guard | Why It Applies | Command / Evidence | Status | Notes |
| --- | --- | --- | --- | --- |
| `navigation harness policy` | `.env.local.navigation.example` remains a guarded operator contract. | `timeout 120s node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | `passed` | Passed in `117.8s`. |
| `compose topology render` | `docker-compose.yml` now mounts the project-owned NGINX overlay directory. | `docker compose config` | `passed` | Compose rendered successfully with the new bind mount. |
| `example JSON parse` | New `.example.json` payloads/defines must stay machine-parseable. | `python3` JSON parse check listed above | `passed` | All touched example JSON surfaces parsed successfully. |
| `nginx template syntax` | Shared templates now include project overlay routes. | Rendered `nginx -t` validation listed above | `passed` | Local and prod template renders passed syntax validation; prod still emits existing upstream `listen ... http2` deprecation warnings only. |

## Pipeline/Copilot P1/P2 Preflight
| Check | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `P1/P2 pipeline preflight for this wave` | `n/a` | `No repo-owned CI workflow/job contract was changed in this slice; touched surfaces were examples/docs, docker-compose mount wiring, and NGINX template/include wiring only.` | This wave did not alter GitHub workflow semantics or add a delivery path that requires a separate Copilot/pipeline regression pass before local review. |

## Rule-Spirit Anti-Pattern Hunt
| Check | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `project-specific defaults accidentally promoted into shared examples` | `passed` | Manual review of touched `.example` surfaces plus search across touched files | No uncommented Belluga domains/branding values remain as active defaults in the promoted examples. |
| `wholesale promotion of browser specs or route semantics by convenience` | `passed` | Candidate classification snapshot + touched-file review | Browser specs remained downstream; only route overlay mechanism and example contracts were extracted. |
