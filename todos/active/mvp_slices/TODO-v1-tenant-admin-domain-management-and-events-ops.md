## Title
Tenant Admin Domain Management And Event Operations Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant Admin already exposes typed mobile `appdomains` plus app-link credential settings, and the event management area already supports status, archived, and temporal filtering. The missing pieces are a canonical admin surface for active tenant web domains and a higher-signal tenant-admin event list that adds backend-owned search plus explicit venue and related-account-profile filters. That event-management work is blocked until the touched event-management path stops hardcoding dynamic account-profile types and models related profiles through canonical event-party/linked-profile semantics instead.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/tenant-admin-domain-and-events-management.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** It delivers the user-visible tenant-admin improvements that are independently testable today while deferring recycle-bin and deeper event workflow work that would require separate read contracts or broader approvals.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** Request `APROVADO` for this execution contract before touching Flutter/Laravel implementation.

## Scope
- [ ] Document and implement an active tenant web-domain admin contract under `/admin/api/v1/domains` that supports Flutter list/create/delete management without changing the approved mobile `appdomains` contract.
- [ ] Add a tenant-admin settings surface for active web domains using the existing controller-owned settings architecture.
- [ ] Document tenant-admin event list search semantics plus explicit venue and related-account-profile filters.
- [ ] Remove hardcoded dynamic account-profile type references from the touched tenant-admin event-management path so related-profile modeling is expressed through canonical event-party/linked-profile semantics.
- [ ] Implement backend-owned tenant-admin event filters for venue and related account profile using canonical venue/location and `event_parties` / `linked_account_profiles` semantics, not hardcoded type-specific payloads.
- [ ] Expose backend-owned search plus venue and related-account-profile filter inputs on the Flutter tenant-admin events screen after the hardcoded dynamic-type blocker is removed.
- [ ] Improve tenant-admin event list cards so operators can inspect timing/publication/context information without entering the edit form for every item.
- [ ] Cover the new domain-management and event-operations behavior with focused Laravel and Flutter validation.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:dev`, `flutter-app:dev`, `laravel-app:dev`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Foundation docs updates | `belluga_now_docker:dev@pending` | `pending` | `pending` | `n/a` | `not started` |
| Laravel tenant domain contract | `laravel-app:dev@pending` | `pending` | `pending` | `n/a` | `not started` |
| Flutter tenant-admin settings + events UX | `flutter-app:dev@pending` | `pending` | `pending` | `n/a` | `not started` |

## Out of Scope
- [ ] Deleted-domain recycle-bin, restore, or force-delete UI.
- [ ] Public `/agenda` or consumer-facing event search behavior.
- [ ] Event create/edit payload changes, publication workflow changes, or event-type registry work.
- [ ] The broader event write/read canonicalization lane that removes every remaining hardcoded dynamic account-profile type reference across touched CRUD/runtime callers; this TODO only absorbs the touched event-management cleanup required for this slice.
- [ ] Reworking tenant bootstrap/environment snapshot contracts to carry admin management state.
- [ ] Constitutional or roadmap edits beyond any module documentation needed to keep contracts canonical.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** Small contract/supporting-refactor work in tenant-admin settings/events repositories, controllers, widgets, and Laravel request/controller/test surfaces when it is directly required to deliver active domain management or event list operations.
- **Must update or split the TODO:** Deleted-domain lifecycle UI, public event discovery behavior, event-form/editorial workflow changes, or any change that would merge mobile `appdomains` and web-domain ownership.

## Definition of Done
- [ ] `foundation_documentation/modules/tenant_admin_module.md` documents the active tenant web-domain admin contract and keeps mobile `appdomains` separate from web domains.
- [ ] Laravel exposes a documented active-domain read endpoint plus create/delete behavior for tenant web domains, with tests covering list/create/delete and duplicate protection.
- [ ] Flutter Tenant Admin settings can load, create, and delete active web domains with controller/repository/UI test coverage.
- [ ] `foundation_documentation/modules/tenant_admin_module.md` and/or `foundation_documentation/modules/events_module.md` document tenant-admin event list search semantics plus explicit venue and related-account-profile filter semantics alongside the existing status/archived/temporal filter matrix.
- [ ] The touched tenant-admin event-management path no longer models related profiles through hardcoded dynamic account-profile types such as `artists`, `artistIds`, or `artistProfiles`, and instead uses canonical event-party/linked-profile semantics.
- [ ] Laravel tenant-admin events supports explicit venue and related-account-profile filters with deterministic composition against `search`, `status`, `archived`, and `temporal`.
- [ ] Flutter Tenant Admin events exposes backend-owned search plus venue and related-account-profile filters that compose with the current filters and pagination.
- [ ] The touched tenant-admin event backend read payload and Flutter admin decode path no longer require any hardcoded dynamic account-profile type key such as `artists`.
- [ ] Event list cards show materially better operational context than the current slug/type/start/status-only presentation.
- [ ] The touched tenant-admin event list/filter implementation does not depend on hardcoded dynamic account-profile types.
- [ ] A blocker-checkpoint no-context audit loop returns clean findings from three specialized auditors: `Elegance`, `Performance`, and `Test Quality`.
- [ ] A final full-scope no-context audit loop returns clean findings from three specialized auditors: `Elegance`, `Performance`, and `Test Quality`.
- [ ] Required local validation completes without leaving unresolved analyzer or test failures in the touched surfaces.

## Validation Steps
- [ ] Laravel: focused tenant-domain feature coverage (existing `TenantDomainControllerTest` plus any needed additions for active-domain listing).
- [ ] Laravel: focused tenant-admin event list coverage proves hardcoded dynamic-type removal in the touched path plus search/filter composition, including venue and related-account-profile filters (`EventCrudControllerTest`).
- [ ] Flutter: repository/controller/widget tests for tenant-admin settings domain management.
- [ ] Flutter: controller/screen tests for tenant-admin event hardcoded dynamic-type removal, venue/related-profile filter state, touched decode-path canonicalization, and list-card rendering.
- [ ] Flutter analyzer: `fvm dart analyze --format machine`
- [ ] External audit loop checkpoint A: after the hardcoded dynamic-type blocker is removed, run three clean-context auditors (`Elegance`, `Performance`, `Test Quality`), integrate findings, and repeat until their merged assessment is clean.
- [ ] External audit loop checkpoint B: after the full TODO is implemented and locally validated, run the same three clean-context auditors again, integrate findings, and repeat until their merged assessment is clean.
- [ ] Manual smoke: Tenant Admin settings can add/delete an active web domain and reflects duplicate-domain validation cleanly.
- [ ] Manual smoke: Tenant Admin events search plus venue/related-profile filters compose with `status`, `archived`, and `temporal` without stale pagination state.

## External Dependency Readiness (Required When External Systems Matter)
This slice is repo-local for approval purposes. No external service dependency is currently blocking the planning contract.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `operational-coder` | No profile-boundary handoff is expected; the slice remains a single cross-stack implementation lane. | `foundation_documentation`, `flutter-app`, `laravel-app` | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** The slice is bounded, but it crosses docs, Laravel tenant-admin API contracts, and Flutter tenant-admin settings/events surfaces.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant_admin_module.md` tenant-admin endpoint sections for domain/app-link/settings and event-management read contracts
  - `events_module.md` search/filter guidance so tenant-admin event search/filter behavior stays explicitly separated from public MVP search restrictions
- **Module decision consolidation targets (required):**
  - `tenant_admin_module.md` sections documenting `/admin/api/v1/domains`, `/admin/api/v1/appdomains`, and tenant-admin event list semantics
  - `events_module.md` section `5.4 Search and index lifecycle model`

## Decision Pending (Resolve Before Freeze)
- [x] `D-00` No additional blocking contract decision remains before approval; current scope questions were resolved into `Decisions` below.

## Decisions (Resolved Before Freeze)
- [x] `D-01` Tenant web-domain management is a separate tenant-admin capability from typed mobile `appdomains`; this slice adds active web-domain read/list/create/delete under `/admin/api/v1/domains` and preserves `TAD-06`.
- [x] `D-02` Flutter will manage active tenant web domains only in this slice; deleted-domain restore/force-delete flows remain out of scope until a deleted-domain read contract exists.
- [x] `D-03` Event-admin improvement in this slice is blocked on removing hardcoded dynamic account-profile type references from the touched event-management path; only after that blocker is removed may the new list/search/filter UX land.
- [x] `D-04` Tenant-admin event search and explicit filters must remain server-driven and compose with the existing `status`, `archived`, and `temporal` filters; local-only filtering is not acceptable.
- [x] `D-05` Venue filtering must use canonical venue/location ownership (`place_ref` / read-projected `venue`) and related account-profile filtering must use canonical linked non-location profile ownership (`event_parties` / `linked_account_profiles`); the touched admin list/filter path must not depend on hardcoded dynamic account-profile types.
- [x] `D-06` The event-admin filter UI must use server-driven profile selection rather than a fixed preload snapshot; if a supporting selector source is missing, this TODO may add the smallest contract needed to search/select the filter target.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `tenant_admin_module.md#TAD-06` | Deep-link credentials stay in `settings.app_links`, while typed mobile app identifiers are owned by `/admin/api/v1/appdomains`. | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` decision ledger + `GET/POST/DELETE /admin/api/v1/appdomains` |
| `events_module.md#EVS-FILTER-01` | MVP public agenda/events listing does not accept text search. | `Preserve` | `foundation_documentation/modules/events_module.md` decision ledger + section `5.4 Search and index lifecycle model` |
| `events_module.md§4 contract bullets` | `event_parties` own event composition principals and `linked_account_profiles` are additive projections for UI consumption. | `Preserve` | `foundation_documentation/modules/events_module.md` bullets under section `4` |

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-01` Mobile `appdomains` and tenant web domains remain distinct contracts and UI responsibilities.
- [ ] `D-02` This TODO delivers active domain management only; deleted-domain lifecycle flows are excluded.
- [ ] `D-03` Hardcoded dynamic account-profile type references are removed from the touched tenant-admin event-management path before the new event-management improvements land.
- [ ] `D-04` Event-management UI hardening remains within the list surface and does not expand into event-form or registry rework.
- [ ] `D-05` The touched tenant-admin event list/filter path no longer depends on any hardcoded dynamic account-profile type key such as `artists`.

## Questions To Close
- [x] None blocking approval for this slice.

## Assumptions Preview (Required Before Plan Review)
Assumptions here must be evidence-backed inferences from canonical modules, code, docs, tests, or repository state. They are not free guesses.

- Promote an assumption to `Decisions` before planning continues if it changes `Scope`, `Definition of Done`, `Validation Steps`, public contract, or module coherence.
- Promote an assumption to `Decisions` before planning continues if it changes `Scope`, `Definition of Done`, required validation semantics, public contract, or module coherence.
- Mark handling as `Block` when the assumption cannot be supported enough to plan safely.

| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Because `project_constitution.md` is absent, the tenant-admin and events module docs plus scope governance are the current authority for this slice. | File is missing; `foundation_documentation/modules/*.md` and `foundation_documentation/policies/scope_subscope_governance.md` are present. | A strategic handoff would be needed before treating any new constitutional rule as authoritative. | `High` | `Keep as Assumption` |
| `A-02` | Existing backend event-index search semantics already satisfy the free-text part of tenant-admin event list needs, but explicit venue and related-account-profile filters are a bounded contract addition rather than a query-model redesign once the hardcoded dynamic-type blocker is removed. | `EventIndexRequest` accepts `search/status/archived/temporal`; `EventCrudControllerTest` already covers text search and filter composition; no explicit venue/profile filters exist yet. | Laravel event-query behavior would widen further than planned. | `Medium` | `Keep as Assumption` |
| `A-03` | Active tenant web-domain management can be delivered without changing runtime bootstrap/environment payloads such as `appData.domains`. | Current settings/app-links repository is already separate from environment snapshot concerns; Laravel domain writes exist outside environment payload docs. | The slice would widen into runtime/environment contract work and need renewed approval. | `Medium` | `Keep as Assumption` |
| `A-04` | Existing controller-owned tenant-admin settings and events architecture can absorb the new surfaces without a route/scope redesign. | `flutter_client_experience_module.md#FCX-01`; current settings/events flows are controller-driven in Flutter. | The work would require architectural restructuring and a broader TODO. | `High` | `Keep as Assumption` |
| `A-05` | The event-admin venue/related-profile filter selectors can be fed by a small server-driven account-profile selection contract instead of preloading the full tenant profile catalog into the screen. | Tenant-admin account-profile list already exists; current Flutter contracts do not yet expose selector-oriented search, and event candidate search is type-specific. | A dedicated selector endpoint or broader account-profile search lane would be needed. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

- If the plan reveals contract changes, update the TODO contract first and do not continue with stale planning notes.

### Touched Surfaces
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/events_module.md`
- `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
- `lib/infrastructure/repositories/tenant_admin/tenant_admin_account_profiles_repository.dart`
- `lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder.dart`
- `lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`
- `lib/presentation/tenant_admin/settings/widgets/*app_links*` or a new adjacent tenant-domain settings widget
- `lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `lib/presentation/tenant_admin/events/screens/tenant_admin_events_screen.dart`
- `test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
- `test/presentation/tenant_admin/events/...`
- `laravel-app/app/Http/Api/v1/Controllers/AccountProfilesController.php`
- `laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/routes/api/tenant_api_v1.php`
- `laravel-app/app/Http/Api/v1/Controllers/DomainController.php`
- `laravel-app/app/Application/Tenants/TenantDomainManagementService.php`
- `laravel-app/tests/Feature/Tenants/TenantDomainControllerTest.php`

### Ordered Steps
1. Update foundation documentation so active tenant web-domain contracts and tenant-admin event-management semantics explicitly treat hardcoded dynamic account-profile types as a blocker and define the canonical venue/related-profile filter direction.
2. Add the minimal Laravel read contract for active tenant web domains and extend tenant-domain feature tests to cover list/create/delete/duplicate behavior.
3. Extend Flutter tenant-admin settings repository/controller/UI/test coverage to load, create, and delete active web domains separately from mobile `appdomains` and credential settings.
4. Remove hardcoded dynamic account-profile type references from the touched Laravel admin event read payload, Flutter admin decode/model path, and any touched event-management adapter code required for this slice.
5. Run a clean-context external audit loop on the blocker-removal checkpoint with three specialized auditors (`Elegance`, `Performance`, `Test Quality`); integrate or consciously challenge findings, cross-review any contradictions, and repeat until the merged assessment is clean.
6. Add the bounded Laravel event-admin filter contract for venue and related account profile, plus any minimal server-driven selector-source contract needed to choose those profiles canonically.
7. Extend Flutter tenant-admin events controller/screen tests and UI to expose backend-owned search plus venue/related-profile filters that reset/reload the paged list while preserving existing status, archived, and temporal filters.
8. Improve event list cards with operational metadata derived from current payloads and run the focused Laravel/Flutter validation lanes plus analyzer.
9. Run a second clean-context external audit loop on the full TODO with the same three specialized auditors; integrate or consciously challenge findings, cross-review any contradictions, and repeat until the merged assessment is clean before closure.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** The slice introduces or freezes cross-stack admin contracts and list-state behavior that are easy to regress if the UI is implemented first.
- **Fail-first target(s) (when required):** `laravel-app/tests/Feature/Tenants/TenantDomainControllerTest.php` for active-domain listing; Laravel tenant-admin event list tests for hardcoded dynamic-type removal in the touched path plus venue/related-profile filter semantics; Flutter tenant-admin settings repository/controller tests for domain CRUD; Flutter tenant-admin events screen/controller tests for search-triggered reload, venue/related-profile filter composition, and removal of hardcoded dynamic-type references in the touched admin path; audit-loop findings from the blocker and final checkpoints become additional fix targets until both checkpoints are clean.

### Runtime / Rollout Notes
- `n/a` for infra/runtime rollout; this is a tenant-admin contract + UI slice with no planned migrations or feature flags.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
Review the `Assumptions Preview` and `Execution Plan` against architecture, code quality, tests, performance, security, elegance, and structural soundness before approval.
Treat brittle workarounds and structural shortcuts as explicit negative findings: ad hoc patches, layered patches over unresolved defects, contract bypasses, opportunistic duplication, hidden coupling, or other avoidable structural debt.

### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** `high`
  - **Evidence:** `laravel-app/routes/api/tenant_api_v1.php`, `laravel-app/app/Http/Api/v1/Controllers/DomainController.php`, `tenant_admin_module.md` currently documenting only `appdomains`
  - **Why it matters now:** Without an active-domain read contract, Flutter would have to manage deletes using unstable path-only state or runtime bootstrap coupling, which is structurally wrong for admin CRUD.
  - **Option A (Recommended):** Add a canonical `GET /admin/api/v1/domains` endpoint for active tenant web domains and document it beside the existing domain write endpoints.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Reuse runtime/environment snapshot strings and infer delete/manage behavior by path only.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Keep create/delete endpoints undocumented in Flutter and leave tenant domain management incomplete.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A` because admin CRUD needs a stable read contract with IDs and keeps web-domain ownership separate from app/bootstrap payloads.
- **Issue ID:** `ARCH-02`
  - **Severity:** `medium`
  - **Evidence:** `EventIndexRequest` already accepts `search/status/archived/temporal`, but has no explicit venue or related-account-profile filters; `tenant_admin_events_screen.dart` exposes only filter controls and a low-signal event card.
  - **Why it matters now:** A local-only search/filter or purely cosmetic change would ignore the existing backend contract, fail the new venue/profile requirement, and keep operators dependent on manual scanning.
  - **Option A (Recommended):** Extend the tenant-admin event list contract with backend-owned venue and related-profile filters, then improve cards using current payload fields.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Add local in-memory venue/profile filtering over the currently loaded page only.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Leave event management without search and keep the current minimal cards.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `neutral`
  - **Recommendation:** `Option A` because filtering truth belongs in the backend list contract, and the new venue/profile requirement cannot be met safely on the client alone.
- **Issue ID:** `ARCH-03`
  - **Severity:** `high`
  - **Evidence:** The touched backend/admin path still emits/decodes hardcoded dynamic-type references (`artists`, `artistIds`, `artistProfiles`) even though `event_parties` is the intended canonical contract.
  - **Why it matters now:** Adding new event-management behavior on top of hardcoded dynamic account-profile types would deepen the exact abstraction error that `event_parties` was supposed to remove.
  - **Option A (Recommended):** Remove hardcoded dynamic account-profile type references from the touched event-management path as a prerequisite inside this slice, then build the new filter/UI work on canonical event-party/linked-profile semantics.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Keep the artist-shaped payload/model naming temporarily and only avoid adding new uses.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Leave hardcoded dynamic-type references in place and proceed with event-management improvements anyway.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A` because the user explicitly confirmed this is a blocker, and the technical reasoning is sound.
- **Issue ID:** `ARCH-04`
  - **Severity:** `medium`
  - **Evidence:** The generic tenant-admin account-profile catalog exists, but the current Flutter repository contract does not expose selector-oriented server search, and the event candidate endpoint is type-specific.
  - **Why it matters now:** Venue and related-profile filters need a bounded way to choose the target profile without preloading the full catalog or reusing any hardcoded dynamic account-profile type semantics.
  - **Option A (Recommended):** Add the smallest server-driven selector path needed for venue and related-profile filter inputs, reusing the tenant-admin account-profile catalog where possible.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Preload all tenant account profiles into the events screen and filter locally.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `regresses`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Leave venue/related-profile filters out of scope.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `neutral`
  - **Recommendation:** `Option A` because the filter requirement is explicit and needs a bounded server-driven selector path.

### Failure Modes & Edge Cases
- [ ] Domain create should reject empty or whitespace-only paths before or with server validation surfacing.
- [ ] Duplicate or cross-tenant domain conflicts must return actionable validation messages in the settings UI.
- [ ] Event search changes must reset page state and avoid appending stale results from a previous query/filter combination.
- [ ] Event search plus venue/related-profile filters must not silently drop the current `status`, `archived`, or `temporal` selection.
- [ ] Venue filtering must use the canonical venue account-profile reference, not a guess derived from some other hardcoded profile-type projection.
- [ ] Related account-profile filtering must match canonical `event_parties` / `linked_account_profiles` identity and must not depend on hardcoded dynamic account-profile types.
- [ ] Removing the touched hardcoded dynamic-type references must not regress any still-approved admin list field that is currently populated from canonical event-party metadata.

### Residual Unknowns / Risks
- [ ] Deleted-domain restore/force-delete remains intentionally unsupported until a deleted-domain read contract exists.
- [ ] If the landlord domain model carries non-`web` tenant-domain types that are active in production, the docs and UI will need explicit filtering language during implementation.
- [ ] Broader non-admin/runtime cleanup of remaining hardcoded dynamic-type references may still belong to the existing event-parties canonicalization lane; this TODO absorbs the touched event-management cleanup required to unblock the requested feature.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `n/a`

## Independent No-Context Critique Gate (Required for `big`; conditional for `medium/high-impact`)
- **Critique decision:** `required`
- **Why this decision:** The user explicitly required no-context external auditing, and this medium slice has cross-module blast radius, public contract/API changes, a blocker-removal checkpoint, and a full-feature checkpoint that both need objective review before delivery.
- **Impact signals in scope:** `cross-module blast radius|public contract/schema/api|high-severity issue card`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `approved scope boundary + frozen baseline + execution plan + blocker checkpoint summary + changed surfaces + current findings/resolutions`
- **Critique isolation mode:** `fresh no-context auxiliary reviewers`
- **Subagent mandate (when available):** `yes`
- **Critique lenses:** `elegance|performance|test-quality`
- **Critique status:** `not_run`
- **Findings summary:** `pending execution`
- **Phase checkpoints:** `checkpoint-A blocker-removal`, `checkpoint-B full TODO`
- **Loop rule:** After each checkpoint, dispatch three clean-context auditors (`Elegance`, `Performance`, `Test Quality`), integrate/challenge findings, and rerun the checkpoint audit until the merged assessment is clean.
- **Contradiction handling:** When auditors disagree, compare the conflicting arguments explicitly, send a clarifying follow-up packet when needed, and record Delphi's authoritative resolution with rationale.
- **Resolution expectation:** No delivery close-out until both checkpoint loops are clean or an explicit human waiver is recorded.
- **Evidence / reference:** `to be recorded during execution`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
Pending `APROVADO`. Execution has not started, so touched-surface rule ingestion is deferred to the first post-approval step.
