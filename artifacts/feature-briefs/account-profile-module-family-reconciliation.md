# Artifact Role
- **Why this brief exists now:** the real reconciliation of the current `partner_*` module family is still feature-shaped and materially ambiguous even after the authority baseline and domain-entity cleanup. We need one framing surface that chooses the next bounded story slice instead of jumping straight into renames or partial module rewrites.
- **What this brief is not:** `project_constitution.md`, a canonical module doc, the roadmap, a tactical execution TODO, or implementation authority.

## Source Idea / Request
- Reconcile the documentation after the Delphi/PACED baseline changes without losing relevant prior branch intent.
- Domain entities were intentionally consolidated before modules so the module family can now be reconciled against the canonical business model instead of against legacy Flutter/doc vocabulary.
- The current `partner`-terminology retirement TODO already identifies the module family as a VNext normalization front, but it does not yet decompose that work into execution slices.

## Problem / Desired Outcome
- **Problem:** the current module family around `partner_catalog_and_offer_module.md`, `partner_admin_module.md`, and `partner_analytics_module.md` still carries older vocabulary and unclear boundaries. Even where top-level authority now avoids `partner` as canonical internal language, the module family still encodes legacy names and unresolved ownership between tenant admin, account workspace, public account-profile/static-asset surfaces, and analytics.
- **Desired outcome:** define a coherent future authority topology centered on the canonical model (`Account`, `Account Profile`, `Profile Type`, `Static Asset`, `Event`), separate internal module truth from public/product aliases, and keep not-yet-implemented fronts capability-first unless implementation later proves module promotion.
- **Why now:** `project_constitution.md`, `project_mandate.md`, `system_architecture_principles.md`, and `domain_entities.md` are now sufficiently reconciled that module work can be evaluated against current authority instead of against missing/contradictory top-level docs.

## Constraints / Non-Goals
- **Constraints:** preserve the already-settled canonical business model; keep public route/product-copy alias decisions separate from internal module-topology decisions; do not silently absorb broad file renames, route redesign, or runtime/code migrations into the first module slice; respect that this front is documented as VNext rather than a store-release blocker.
- **Non-goals:** immediate public URL changes; broad code-symbol renaming; Flutter domain-topology normalization; repo-wide terminology purge in one pass; collapsing tenant admin and account workspace into one surface without an explicit decision.

## Canonical Touchpoints
- **Constitution impact:** `yes` — the project constitution module map and module descriptions will eventually need to reflect the settled future module family.
- **Roadmap impact:** `possible` — if the reconciliation changes which module fronts are strategic placeholders versus active authorities, the roadmap/module references may need targeted updates.
- **Primary module candidates:** `foundation_documentation/modules/partner_catalog_and_offer_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/partner_admin_module.md`, `foundation_documentation/modules/partner_analytics_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/map_poi_module.md`, `foundation_documentation/modules/flutter_client_experience_module.md`

## Evidence / References
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/project_constitution.md`
- `foundation_documentation/modules/partner_catalog_and_offer_module.md`
- `foundation_documentation/modules/partner_admin_module.md`
- `foundation_documentation/modules/partner_analytics_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-partner-terminology-retirement-and-account-profile-language-normalization.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-route-paths-refactor.md`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Whether the deferred `offer`/commercial concern should be forced into a separate future module now or kept capability-first until implementation. | Premature module promotion would create fake authority for a not-yet-implemented front. | Current doc mixes account profiles with deferred offer planning, but no equivalent runtime authority exists in the code scan. | `resolve now` |
| `AMB-02` | Whether `partner_admin_module` is really the future `account_workspace` authority. | This changes successor naming and where future operator-facing docs belong. | Current doc already frames itself as future Account Profile Admin/Workspace and the user confirmed `partner admin` is `account_workspace`. | `resolve now` |
| `AMB-03` | Whether `partner_analytics_module` should be defaulted to a future standalone module now or kept capability-first. | Placeholder analytics docs can become fake authority if their boundary is not explicit. | Current doc is placeholder-only and depends on workspace timing. | `resolve now` |
| `AMB-04` | Which `partner` references are internal module-topology debt versus deliberate public/product aliases such as `/parceiro/:slug`. | We must not conflate internal module truth with public continuity choices. | Existing VNext terminology TODO already separates public route/copy evaluation from internal language retirement. | `resolve now` |
| `AMB-05` | Whether the first execution slice should decide topology only, or also apply renamed/restructured module docs in the same TODO. | This determines whether the next tactical TODO stays bounded. | Current authority sweep showed small local wording fixes are safe, but broad module normalization is still materially larger. | `resolve now` |

## Story Decomposition
Treat each row as a candidate delivery slice. A tactical TODO should normally map to one primary story slice, not to the entire table.

| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Decide the canonical future topology and successor naming for the current `partner_*` family under a capability-first rule for not-yet-implemented fronts. | `partner_catalog_and_offer_module.md` | `partner_admin_module.md`, `partner_analytics_module.md`, `tenant_admin_module.md`, `project_constitution.md` | The project has an explicit future authority decision: `partner_admin` -> `account_workspace`; current public account-profile catalog remains real; deferred fronts such as offer/analytics stay capability-first unless later implementation promotes them. | Brief/TODO decision table is internally coherent and maps each current file to one future handling without prematurely forcing extra modules. | `create-now` | Requires domain-entity/top-level authority baseline, now satisfied. | Recommended current slice. |
| `ST-02` | Apply the agreed module-map and module-doc restructuring for the chosen future topology. | `project_constitution.md` | the renamed/split/merged module docs | Module map, cross-references, and module docs reflect the decided future topology without stale duplicate authority. | Targeted `rg` on old module names/references in touched files; doc diffs are coherent. | `defer` | Depends on ST-01 decisions being frozen first. | Could still split further if renames and content moves diverge. |
| `ST-03` | Reconcile public route/copy aliases with the new module topology and internal language. | `flutter_client_experience_module.md` | `partner_catalog_and_offer_module.md`, `endpoints_mvp_contracts.md`, route-path TODOs | Public alias policy is explicit: canonical internal module names no longer depend on `/parceiro/:slug`, and any alias policy is separately documented. | Route/copy decision recorded with permanence policy. | `merge-with-other` | Blocked on `TODO-vnext-route-paths-refactor.md` and explicit product decision. | Keep separate from pure module-family decision when possible. |
| `ST-04` | Normalize implementation/code-symbol vocabulary after the module-family and alias decisions are settled. | `flutter-app`, `laravel-app` | foundation docs | Code/docs vocabulary drift is retired without breaking public continuity or premature package churn. | Targeted repository search on legacy symbols in touched areas. | `defer` | Depends on ST-01 and likely ST-03. | Explicitly not the first slice. |

## Retire This Brief When
- The active tactical TODO for `ST-01` exists and the future module-family decision is no longer feature-shaped.
