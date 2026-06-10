# Documentation: Project Constitution
**Version:** 1.3

## 1. Purpose

- **Project purpose:** Establish the launch-ready architecture for the Bóora! project as a multi-tenant, multilateral hyperlocal experience platform spanning tenant public discovery, invite-driven conversion, tenant administration, and landlord operations inside the broader Belluga ecosystem.
- **System boundary:** This project includes the `belluga_now_docker` orchestration root plus the attached `flutter-app`, `laravel-app`, `web-app`, and `foundation_documentation` repositories that together deliver runtime behavior, project authority, and promotion-lane governance. Other downstream ecosystems and Delphi itself are outside this project's canonical boundary.
- **Inherited Delphi stack baseline:** This project inherits the PACED/Delphi `docker` namespace baseline: TODO-driven execution, profile-scoped governance, route/scope discipline, explicit module contracts, and project-specific authority living under `foundation_documentation/`.

## 2. Authority Model

### 2.1 Rule Subscriptions (Cascading Rules)

This project follows the PACED cascading-rule hierarchy and resolves downstream stack wiring through the `docker` namespace.

- **Namespace:** `docker`
- **Rule subscriptions:**
  - [x] **Core Rules:** Universal Delphi governance, workflow, and TODO rules.
  - [x] **Stack Rules:** Docker/cross-stack downstream governance distributed through `delphi-ai/`.
  - [x] **Local Rules:** This constitution, project policies, module docs, and approved local decisions.

### 2.2 Authority Hierarchy

When rules conflict, the following precedence order applies:
1. **Local Rules (`foundation_documentation/` + linked local rule surfaces):** project constitution, policies, module docs, and approved local decisions.
2. **Stack Rules (`delphi-ai` docker stack):** reusable Docker/cross-stack Delphi rules and workflows.
3. **Core Rules (`delphi-ai` core):** universal PACED/Delphi instructions.

- **Delphi-level authority (inherited):**
  - `delphi-ai/main_instructions.md`
  - `delphi-ai/system_architecture_principles.md`
  - Delphi rules, workflows, skills, templates, and deterministic tooling
- **Project-level authority (current project truth):**
  - `foundation_documentation/project_mandate.md`
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/policies/*.md`
  - `foundation_documentation/README.md` for repo-operational notes only
- **Module-level authority:**
  - `foundation_documentation/modules/*.md`
- **Tactical execution authority:**
  - `foundation_documentation/todos/active/*.md`
- **Strategic sequencing authority:**
  - `foundation_documentation/system_roadmap.md`

## 3. Ecosystem Alignment & Reuse Doctrine

This project operates inside the broader Belluga ecosystem under the PACED reuse doctrine. Significant capabilities must be evaluated deliberately for reuse potential, but reuse is a bias rather than a blanket rule.

### 3.1 Abstraction Strategy

- **Ecosystem Bias:** when a capability can serve multiple Belluga-ecosystem projects without leaking Bóora!-specific tenant semantics, it should be designed with a package-capable boundary from the start.
- **Project Sovereignty:** capabilities tied to this project's runtime governance, current tenant posture, documentation topology, brand/tenant-specific behavior, or branch-reconciliation reality remain project-local.
- **Extraction Threshold:** a capability should move from local implementation toward a shared package only after at least one real use proves the abstraction stable enough to survive outside the originating slice.
- **Anti-Pattern:** do not force every feature into package form. If the abstraction is immature, artificial, or mostly encodes project-specific or tenant-specific behavior, keep it local until the boundary is clearer.

### 3.2 Identified Reuse Candidates

| Capability | Reuse Potential | Current Status | Target (Package/Shared) |
| --- | --- | --- | --- |
| `belluga_form_validation` | `high` | `internal package hardening / publish-path planning` | `shared Flutter package boundary` |
| `belluga_connections` | `high` | `planned dedicated Laravel package` | `laravel-app/packages/belluga/belluga_connections` |
| `belluga_ticketing` | `high` | `active package integration / capability split` | `laravel-app/packages/belluga/belluga_ticketing` |
| `belluga_media` canonical image flow | `medium-high` | `package hardening / host-wrapper convergence` | `laravel-app/packages/belluga/belluga_media` |
| `checkout` integration boundary | `medium-high` | `package integration planning` | `shared Laravel package boundary` |
| `missions` capability | `medium` | `package-first planning` | `shared package boundary (target repo pending)` |

## 4. System Topology / Runtime Surfaces

### 4.1 Repositories / Runtime Surfaces

- `belluga_now_docker`: orchestration root, Docker runtime topology, submodule pointers, and cross-repo promotion coordination.
- `foundation_documentation`: canonical project documentation and tactical TODO repository. Its `main` branch is the documentation authority line; the root repository only tracks the submodule pointer after docs merge.
- `flutter-app`: Flutter client surfaces for landlord, tenant public, tenant admin, and account workspace experiences.
- `laravel-app`: canonical API/runtime implementation, tenant/landlord resolution, settings kernel, deep-link well-known endpoints, and package integration.
- `web-app`: derived/compiled web bundle surface. It is runtime-relevant but not the canonical authoring surface for route-governance tests or project authority docs.

### 4.2 Major Modules / Bounded Contexts

- `flutter_client_experience_module`: cross-surface client architecture and presentation/runtime contracts.
- `account_workspace_module`: future authenticated operator workspace for account-managed memberships, assets, and workspace-facing dashboards.
- `tenant_admin_module`: tenant-domain administration, settings, onboarding, domains, organizations, and event operations.
- `events_module`: public/admin event contracts, occurrence/detail behavior, and event-management dependencies.
- `invite_and_social_loop_module`: invite lifecycle, share attribution, contact import, and web-to-app conversion boundaries.
- `agenda_and_action_planner_module`: agenda feed, action planning, and geo-origin behavior.
- `map_poi_module`: map projections, filters, near/lookup surfaces, and POI integration seams.
- `onboarding_flow_module`: identity progression, auth entry, and first-run route behavior.
- `account_profile_catalog_module`: account-profile/static-asset catalog and public profile/static-asset surface contracts, with deferred offer/commercial capability planning.

### 4.3 External Integrations

- `Cloudflare / public edge`: host-resolved public runtime ingress and cache/security headers.
- `Android App Links / iOS Universal Links`: app/open-store continuation and invite attribution surfaces.
- `Firebase / push / telemetry / Resend`: tenant-configured outbound integrations managed through the Laravel settings kernel and consumed by Flutter/web surfaces.

## 5. Cross-Module Rules

- `foundation_documentation` is the canonical source of project docs and tactical TODOs. Code repositories do not become authority surfaces just because they implement behavior first.
- `project_constitution.md` owns project-wide inter-module rules and deviations. Module docs own module-local contracts. TODOs, artifacts, and branch notes may support decisions but must not replace canonical authority.
- Feature planning must include an explicit ecosystem-vs-local reuse judgment. Package extraction is encouraged where the boundary is credibly reusable, but project governance and tenant/product-specific flows must not be abstracted prematurely.
- Not-yet-implemented fronts default to **capability-first** planning rather than automatic standalone module status. Future concerns such as offer/commercial, analytics, missions, and similar named fronts may later be promoted to modules, but only when implementation proves a real bounded context; that promotion is not assumed in advance.
- Cold-start tenant activation is a canonical recurring project posture, not a migration edge case. Tenant-curated supply, tenant-operated seed inventory, and `unmanaged` account profiles must remain supported across tenant admin, public discovery, onboarding, and future claim/self-management flows.
- Route/scope ownership is fixed by `foundation_documentation/policies/scope_subscope_governance.md`: `EnvironmentType` remains binary (`landlord|tenant`), approved main scopes are `site_public`, `landlord_area`, `tenant_public`, and `tenant_admin`, and `account_workspace` is the only approved subscope.
- Laravel owns host-resolved HTTP/runtime contracts, settings persistence, and public edge well-known endpoints. Flutter runtime consumes Laravel-backed adapters only; runtime mock fallback is forbidden outside explicit test injection.
- Tenant admin runs on tenant domains under the `tenant_admin` main scope while still using landlord identity principal in V1. `account_workspace` remains an adjacent tenant subscope and must not be treated as a tenant-admin alias.
- `account_profile_catalog_module.md` is the current authority file for public account-profile catalog/detail contracts. Its deferred `offer` concern is capability-first by default. `account_workspace_module.md` is the canonical planning surface for future `account_workspace`, while `account_profile_analytics_capability.md` is a capability-planning surface rather than a standalone current runtime authority.
- `web-app` is a derived/compiled runtime surface. Route/navigation test sources and governance-changing route artifacts must be authored in source-owned repos/tools, not directly in `web-app`.
- Branch reconciliation and cleanup are repo-specific decisions. `foundation_documentation` is evaluated against `origin/main`; the root repo, `flutter-app`, and `laravel-app` use `origin/dev` for normal preflight/rebaseline decisions.
- First-production capabilities have zero backward-compatibility burden unless their governing tactical TODO explicitly says otherwise. Pre-release data shapes, DTOs, caches, local fixtures, routes, endpoints, and UI behavior are disposable when they conflict with the launch contract; delivery should cut over, reset, reseed, or reject them instead of preserving old behavior.
- When a governing TODO/user decision establishes a **hard cutoff** for a first-production slice, implementation must prefer direct cutover, repair/backfill, or canonical read-model materialization over legacy bridge fields or pseudo-canonical query aliases. Query-time shim fields such as `*_effective`, compatibility mirrors, or fallback-only bridge projections are not acceptable completion states unless the governing TODO names them explicitly as temporary, bounded, and blocking closeout until removal.
- Hard-cut slices require a dedicated `cutover-integrity` reviewer in audit/review/promotion gates. This reviewer exists specifically to reject disguised workaround architecture: pseudo-canonical `*_effective` fields, silent compatibility mirrors, dual-read/dual-write bridges left as the final shape, query-time stitching that hides missing canonical ownership, or any other path that is functionally a temporary workaround pretending to be the new contract. Findings from this reviewer are blocking unless the governing TODO explicitly records the construct as temporary, bounded, and closure-blocking until removal.
- Review, audit, and promotion gates must carry the first-production rule. For those capabilities, requests for backward compatibility are out of scope and non-blocking by default. Promotion may ignore or waive those comments with citation to this constitution and the governing TODO unless they raise an independent security, data-integrity, data-loss, tenant-isolation, or release-regression risk that does not depend on preserving old contracts.
- Copilot-mimic, Copilot real, no-context subagents, and other auditors must keep their normal detection behavior. The decision about what blocks the current release happens **after** findings are collected, in a separate triage step that classifies each item as `release-blocker`, `follow-up-fast-follow`, `follow-up-hardening`, or `by-design/no-action`.
- Only `release-blocker` findings block the current release/package. Non-blocking findings must either be fixed in-scope immediately or split into explicit post-version TODOs under an approved active lane root such as `active/fast_follow_required/followup/` or `active/post_release_hardening/hardening/`, with the originating release/package version recorded in the TODO and routing ledger.

## 6. Systemic Invariants

- Canonical authority files are `project_mandate.md`, `domain_entities.md`, `project_constitution.md`, `system_roadmap.md`, `policies/*.md`, and `modules/*.md`. Completed TODOs and artifacts are historical/supporting evidence only.
- The active tactical lane model is limited to `store_release_android`, `fast_follow_required`, and `vnext`. Historical lane names such as `pre_mvp_*`, `mvp_*`, `cross-stack`, `mvp_slices`, and `mvp_closure` must not be reintroduced into new active authority surfaces.
- Grouping subdirectories like `followup/` or `hardening/` may exist **inside** approved active lane roots for post-version routing, but they do not create new lane names or a new tactical-lane class.
- Stable cross-stack decisions must be promoted into canonical docs before a tactical TODO closes. Project-level rules belong here; module-local rules belong in module docs.
- The ecosystem reuse doctrine is mandatory at planning time, but no feature becomes a package by default. Extraction happens only when the boundary is proven by real use and does not depend on project-only semantics.
- Recurring Laravel scheduler/job runtime is orchestration-only: canonical Application/Domain services own business selection and mutation rules, steady-state recurring full scans are forbidden, and any full-scan repair/backfill path must be explicit/manual plus cursor/chunk based.
- Account ownership semantics are part of the recurring tenant-bootstrap model, not isolated admin metadata. `unmanaged` accounts represent valid seed supply for new or expanding tenants and may later transition into claimed/user-managed states without redefining the core entity model.
- Invite/web-to-app continuation must preserve request intent and invite attribution across tenant web, app-store handoff, and app-entry flows; unresolved continuation may fall back only through explicitly approved product policy.
- Store-release invites, favorites, and friends/contact groups are first-production capabilities. Their launch contracts supersede all pre-release local behavior and require no backward-compatibility path.
- Hard-cut slices must fail closed on architecture shape as well as payload shape: if the clean cutover cannot be delivered in-lane, the slice should block or rescope rather than ship with a durable legacy shim, pseudo-canonical `effective` field, or hidden compatibility bridge.
- A hard-cut slice is not review-complete until a `cutover-integrity` reviewer explicitly checks the chosen path against shim/bridge drift and records whether the final shape is canonical, temporary-but-bounded, or blocked.
- No new scope, subscope, or cross-module ownership boundary may be implied by implementation alone. Policy and canonical docs must be updated before such a change becomes authoritative.

## 7. Approved Project-Specific Deviations From Delphi Baseline

| Deviation ID | Baseline Being Deviated From | Project-Specific Rule | Why It Exists | Evidence / Module Link |
| --- | --- | --- | --- | --- |
| `DEV-01` | Uniform lane progression across all downstream repos | `foundation_documentation` promotes directly on `main`, while code/runtime repositories follow `dev -> stage -> main` promotion lanes. | Documentation is maintained in its own repository and is consumed by the root repo through a submodule pointer instead of sharing the code-repo promotion path. | `foundation_documentation/README.md`, this constitution §4.1 and §5 |
| `DEV-02` | Treat every checked-out repo as a canonical source-authoring surface | `web-app` is a derived/compiled repository; route-governance tests and canonical navigation sources must be authored outside it. | Prevents governance drift between generated web output and source-owned route contracts. | `foundation_documentation/policies/scope_subscope_governance.md`, `modules/flutter_client_experience_module.md` |

## 8. Module Map

| Module Doc | Scope | Why It Exists | Key Dependencies |
| --- | --- | --- | --- |
| `foundation_documentation/modules/flutter_client_experience_module.md` | `landlord_area`, `tenant_public`, `tenant_admin`, `account_workspace` | Defines client architecture, route ownership, auth/product posture, and runtime consumption rules. | `submodule_flutter-app_summary.md`, events, invites, map, tenant admin |
| `foundation_documentation/modules/tenant_admin_module.md` | `tenant_admin` | Defines tenant-domain admin IA, settings, onboarding, domains, organizations, and events/admin boundaries. | Flutter/Laravel summaries, events, account-profile/static-asset catalog, map |
| `foundation_documentation/modules/events_module.md` | `tenant_public`, `tenant_admin` | Defines public event consumption, admin event management, and event-related contracts. | invites, map, tenant admin |
| `foundation_documentation/modules/invite_and_social_loop_module.md` | `tenant_public` + web-to-app boundary | Defines invite lifecycle, share attribution, contact import, and anonymous-first conversion rules. | onboarding, events, Flutter client |
| `foundation_documentation/modules/agenda_and_action_planner_module.md` | `tenant_public` | Defines agenda feed behavior, action planning, and origin policy. | events, home composer, map |
| `foundation_documentation/modules/map_poi_module.md` | `tenant_public` with `tenant_admin` inputs | Defines POI projections, filters, near/lookup contracts, and map governance. | events, account-profile/static-asset catalog, static assets |
| `foundation_documentation/modules/onboarding_flow_module.md` | `tenant_public` + web-to-app boundary | Defines identity progression, auth entry rules, and invite/deep-link onboarding continuity. | invite loop, environment/bootstrap, profile |
| `foundation_documentation/modules/account_workspace_module.md` | `account_workspace` | Defines the future authenticated operator workspace for account memberships, invite metrics, and workspace-facing dashboards. | tenant admin, invites, account-profile catalog |
| `foundation_documentation/modules/account_profile_catalog_module.md` | `tenant_public`, `tenant_admin` | Defines account-profile/static-asset catalogs, public profile/static-asset surface contracts, and registry-driven visual rules. | tenant admin, map, events |

## 9. Strategic Framing

- **Current strategic stage(s):**
  - `store_release_android`: launch-critical release and conversion gate
  - `fast_follow_required`: mandatory post-release deep-link, QR-auth, and continuation work
  - `vnext`: approved deferred backlog and architectural follow-up
  - `authority reconciliation`: current project-level normalization front to keep docs and branch decisions aligned with the current Delphi baseline
- **Strategic tensions / open fronts:**
  - restore and keep top-level authority docs current
  - reconcile relevant unmerged work without discarding valid branch intent
  - keep app/web/auth/deep-link posture coherent across release and fast-follow lanes
  - expand tenant admin and account-workspace capability without scope drift
- **Roadmap relationship:** `system_roadmap.md` tracks active strategic horizons, sequencing, and large cross-stack follow-up. It does not replace this constitution as the current project-level rule snapshot.

## 10. Maintenance Rules

- Update this document when project-level rules, repo boundaries, lane semantics, or cross-module invariants change.
- Keep this file focused on project-level truths; do not duplicate durable module-local contracts here.
- When a change affects only one module, update the module doc instead of broadening this constitution unnecessarily.
- Branch reconciliation artifacts are assistive evidence only. Final merge, cherry-pick, deletion, or rebaseline actions still require repo-specific review against the active branch state.
