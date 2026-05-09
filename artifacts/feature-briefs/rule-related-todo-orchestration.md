# Feature Brief: Rule-Related TODO Orchestration

## Artifact Role
- **Why this brief exists now:** the active rule-related work is still initiative-shaped across multiple drift families, existing TODO owners, and missing split TODOs. A single tactical TODO would be too broad and would hide dependency/order decisions inside execution notes.
- **What this brief is not:** canonical module doc, project constitution, system roadmap, tactical TODO, or implementation authority.

## Source Idea / Request
- Conduct a complete orchestration of the project's rule-related TODO family.
- Start with an explicit orchestration plan.
- Then execute TODO by TODO with TODO-driven boundaries, deterministic audit floor, module/doc sync, implementation subagents/worktrees, `triple audit` per TODO, derived bounded subagent review packets for the remaining TODO-local review lanes, and a bounded `Claude CLI` fourth-auditor experiment for later auditor-performance comparison.

## Problem / Desired Outcome
- **Problem:** rule-related drift is split across active TODOs, a large review contract, and missing remediation TODOs. Some drifts already have owners, some need explicit split, and some future guardrails still exist only as recommendations in the drift review.
- **Desired outcome:** every prioritized rule-related drift family has an explicit owning TODO, a clear execution order, dependency visibility, and a per-TODO closure model that freezes the violated rule, replacement rule, strongest objective guardrail, and real drift fixture before remediation.
- **Why now:** the drift-review TODO already recommends first-wave splits and the user explicitly requested full orchestration rather than ad hoc local fixes.

## Constraints / Non-Goals
- **Constraints:** follow TODO-driven execution; do not weaken the deterministic audit floor; use `triple audit` as the principal gate per TODO; implementation belongs to subagents/worktrees; the only accepted CLI deviation is `Claude CLI` as a fourth auditor for comparison; do not silently globalize this local orchestration choice into Delphi.
- **Non-goals:** do not merge unrelated drift families into one mega-TODO; do not bypass existing canonical TODO owners; do not reopen release-lane product decisions that are already frozen unless a security/integrity/runtime drift requires it.

## Canonical Touchpoints
- **Constitution impact:** `none` for the orchestration brief itself. Individual TODOs may later produce project- or module-level contract promotion.
- **Roadmap impact:** `possible` if execution changes sequencing or introduces sustained cross-stack follow-up beyond the current TODO family.
- **Primary module candidates:** `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`, `foundation_documentation/modules/account_profile_catalog_module.md`, `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/map_poi_module.md`

## Evidence / References
- `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-architectural-rule-drift-review.md`
- `foundation_documentation/todos/active/vnext/TODO-v1-query-path-guardrails-hardening.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-landlord-password-credential-source-of-truth-hardening.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-tenant-public-boundary-policy-centralization.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-belluga-media-canonical-image-flow-hardening.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-media-host-agnostic-public-urls-and-tenant-cors-cache.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-event-artists-eradication.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Whether every drift-review candidate must become a new TODO immediately. | Creating every candidate at once can bloat orchestration; creating too few leaves review findings without owners. | The drift review already distinguishes P0/P1 families from later P2 lanes. Existing TODOs already own some families. | `resolve now` |
| `AMB-02` | Whether repository/domain purity hardening belongs to `post_release_hardening` or only to broad VNext normalization. | Wrong lane choice could hide urgent rule drift inside a vague later cleanup. | `ARCH-DRIFT-014`, `ARCH-DRIFT-007`, and `ARCH-DRIFT-012` are current production-code architecture drifts, while `TODO-vnext-flutter-domain-topology-normalization.md` is broader semantic cleanup. | `resolve now` |
| `AMB-03` | Whether the local-public revalidation TODO must wait for landlord credential repair. | Execution order changes if the runtime smoke lane can proceed independently. | The active local-public TODO explicitly records the landlord credential TODO as the current blocker for mutation shards. | `resolve now` |

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Convert the drift review into an executable orchestration authority. | `n/a` | `n/a` | review TODO records created owners, first-wave sequence, and deferred queues clearly | drift review acceptance criteria and candidate queue updated | `create-now` | none | This is the orchestration anchor. |
| `ST-02` | Eliminate hardcoded personal/default account-profile type behavior. | `account_profile_catalog_module.md` | `tenant_admin_module.md`, `invite_and_social_loop_module.md` | one TODO owns linked personal/default type semantics end to end | TODO created with violated rule, replacement rule, guardrail, and fixtures | `create-now` | none | P0 from drift review. |
| `ST-03` | Prevent destructive registry mutation/sync drift for account-profile types. | `account_profile_catalog_module.md` | `tenant_admin_module.md` | one TODO owns rename/delete/sync guardrails and in-use dependency policy | TODO created with fixtures from registry mutation and sync code paths | `create-now` | `ST-02` alignment helpful but not blocking creation | Sibling of `ST-02`, later executed after semantics freeze. |
| `ST-04` | Protect tenant app-domain mutation routes with explicit tenant access + ability controls. | `onboarding_flow_module.md` | `tenant_admin_module.md` | one TODO owns route middleware, ability contract, and app-link integrity tests | TODO created and later covered by Laravel tests | `create-now` | none | P0 security drift. |
| `ST-05` | Prevent cross-account permission bleed in account-scoped tokens. | `flutter_client_experience_module.md` | `onboarding_flow_module.md` | one TODO owns account-bound token/ability semantics and mixed-role tests | TODO created with exact drift fixtures | `create-now` | none | P0 security drift. |
| `ST-06` | Harden public auth/reset lifecycle and risk-matrix coverage around OTP-first policy. | `onboarding_flow_module.md` | `invite_and_social_loop_module.md` | one TODO owns fail-closed auth method config, reset-token lifecycle, and risk-matrix rows | TODO created with drift fixtures from auth resolver, profile services, and risk matrix | `create-now` | overlaps existing OTP/web-to-app TODOs and must stay bounded | P0/P1 security drift. |
| `ST-07` | Move raw transport parsing out of Flutter repositories into typed boundaries. | `flutter_client_experience_module.md` | `events_module.md` | one TODO owns repository transport-boundary hardening for confirmed runtime drifts | TODO created with fixtures from real repositories | `create-now` | none | P1 architecture drift. |
| `ST-08` | Remove service-locator and dynamic payload impurity from the Flutter domain layer. | `flutter_client_experience_module.md` | `account_profile_catalog_module.md` | one TODO owns typed value-object/domain purity corrections without absorbing full topology normalization | TODO created with fixtures from `Tenant` and dynamic map wrappers | `create-now` | none | P1 architecture drift. |
| `ST-09` | Expand static analyzer coverage using the runtime drifts as fixtures. | `flutter_client_experience_module.md` | `n/a` | one TODO owns analyzer/custom-lint follow-through after runtime fixes exist | TODO created with dependencies on `ST-07` and `ST-08` | `create-now` | depends on `ST-07` and `ST-08` for final fixture set | Guardrail-only lane. |
| `ST-10` | Keep route/back-governance and runtime-backend/mock classification as explicit later lanes instead of hidden backlog notes. | `flutter_client_experience_module.md` | `events_module.md` | drift review clearly marks them as deferred second-wave splits, not forgotten debt | drift review sequencing table updated | `defer` | depends on current first-wave families and existing owner TODOs | Do not widen the first wave unnecessarily. |

## Retire This Brief When
- the drift-review TODO records the orchestrated owner map and first-wave sequence clearly; and
- the first-wave split TODOs exist under `foundation_documentation/todos/active/`; and
- future execution can proceed directly from those tactical TODOs instead of this framing artifact.
