# Title
VNext: Nested Account Profile Groups as Custom Public Tabs

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Account Profiles need a nested-account capability. Tenant admins must be able to create custom groups such as `Parceiros`, `Patrocinadores`, `Apoiadores`, or `Equipe`; each group becomes a public Account Profile tab and contains selected linked Accounts/Account Profiles.

The current public Account Profile screen already renders profile tabs from `PartnerProfileConfig`, and there is a skeletal `supportedEntities` module. The new requirement needs a real persisted/admin-managed grouping contract instead of hardcoded type-driven tabs.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `nested-account-profile-groups`
- **Why this is the right current slice:** this is one cohesive product capability: define groups on a parent Account Profile and render each group as a tab with selected linked profiles.
- **Direct-to-TODO rationale:** the user supplied the required authoring model and public rendering semantics.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Feature`, `Cross-Stack`, `Tenant-Admin`, `Tenant-Public`, `User-Visible`
- **Next exact step:** run package-first/context refinement against the frozen embedded group contract, then request `APROVADO`.

## Scope
- [ ] Define a persisted nested group contract on Account Profile or a related Account Profile aggregate.
- [ ] Each group has a stable key/id, display label, order, and selected linked Account Profile ids.
- [ ] Tenant admin can create, rename, reorder, delete groups, and manage selected linked accounts/profiles in each group.
- [ ] Public Account Profile detail renders one tab per non-empty group using the group label as the tab title.
- [ ] Public tabs render selected linked profiles as cards using existing Account Profile visual/identity conventions.
- [ ] Public payloads include enough nested group data for no-extra-query rendering, or explicitly define the repository lookup path.
- [ ] Backend validates tenant scope, active/deleted profiles, duplicate prevention, and bounded list sizes.
- [ ] Tests cover admin persistence/readback, public projection, Flutter DTO/domain parsing, and tab rendering.

## Out of Scope
- [ ] Account Workspace membership/team permissions.
- [ ] User-owned account claim flows.
- [ ] Generic organization hierarchy or arbitrary recursive nesting.
- [ ] Event linked profile category tabs.
- [ ] New public profile module marketplace/editor beyond this nested-group capability.

## Dependencies & Sequencing
- [ ] `DEP-01` Must preserve Account Profile as the public identity surface.
- [ ] `DEP-02` Must not redefine Account Workspace permissions from `TODO-vnext-account-workspace.md`.
- [ ] `DEP-03` Should run after any active Account Profile registry/persistence hardening if those changes touch the same request/formatter paths.

## Definition of Done
- [ ] Admin can add at least two custom groups with different labels and selected profiles.
- [ ] Admin can update and read back group membership/order.
- [ ] Public Account Profile detail exposes the groups in the intended order as tabs.
- [ ] Empty groups do not render public tabs unless product explicitly approves empty-state tabs during planning.
- [ ] Linked profile cards use existing profile identity/visual fallbacks and navigate to the linked profile detail when a route is available.
- [ ] Backend enforces tenant boundary and rejects invalid or cross-tenant profile ids.
- [ ] Tests prove no duplicate profile cards inside one group and deterministic ordering.

## Validation Steps
- [ ] Laravel feature tests for create/update/read Account Profile nested groups.
- [ ] Laravel public Account Profile detail/list projection test for nested groups.
- [ ] Flutter DTO/domain tests for nested group decoding.
- [ ] Flutter tenant-admin form/controller/widget tests for group editing.
- [ ] Flutter public Account Profile detail widget/navigation test for custom tabs.
- [ ] Analyzer/local CI-equivalent suite row completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`, `strategic-cto-tech-lead` if storage model or module decisions change materially.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why The Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Cross-stack persisted/public tab behavior requires regression and flow validation. | `laravel-app`, `flutter-app`, tests | `planned` |

## Complexity
- **Level:** `big`
- **Checkpoint policy:** `section-by-section planning review before APROVADO + post-validation checkpoint`
- **Why this level:** new persisted model, admin authoring, public projection, and public tab rendering are independently risky but still one cohesive capability.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/account_workspace_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`

## Source Inventory Snapshot
- `laravel-app/app/Models/Tenants/AccountProfile.php`
- `laravel-app/app/Application/AccountProfiles/AccountProfileManagementService.php`
- `laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php`
- `laravel-app/app/Http/Api/v1/Requests/AccountProfileStoreRequest.php`
- `laravel-app/app/Http/Api/v1/Requests/AccountProfileUpdateRequest.php`
- `flutter-app/lib/domain/partners/account_profile_model.dart`
- `flutter-app/lib/domain/partners/services/partner_profile_config_builder.dart`
- `flutter-app/lib/domain/partners/projections/partner_profile_config.dart`
- `flutter-app/lib/domain/partners/projections/partner_profile_module_data/partner_supported_entity_view.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart`
- `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_admin/account_profiles/**`

## Decisions
- [x] `D-NEST-01` The public rendered unit is an Account Profile card, even if the admin picker labels the selection as Accounts.
- [x] `D-NEST-02` A nested group label is also the public tab title.
- [x] `D-NEST-03` Nested groups are one-level custom groups, not recursive account hierarchies.
- [x] `D-NEST-04` Group order and member order are persisted and public rendering must preserve them.
- [x] `D-NEST-05` Store the first implementation as bounded embedded `nested_profile_groups` on `account_profiles`; do not introduce a separate relation collection unless limits are later exceeded by a new approved slice.
- [x] `D-NEST-06` The tenant-admin picker may be labeled in operator-facing copy as selecting Accounts, but the persisted selected value is the linked Account Profile id. Raw Account ids are not the public render contract.
- [x] `D-NEST-07` Initial list limits are max `12` groups per parent Account Profile and max `50` linked Account Profiles per group.
- [x] `D-NEST-08` Empty groups are hidden on the public Account Profile detail; they remain editable in tenant-admin.

## Closed Questions
- [x] Admin picker decision closed by `D-NEST-06`.
- [x] First list-size limits closed by `D-NEST-07`.
- [x] Empty public group behavior closed by `D-NEST-08`.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Account Profile remains the canonical public identity and linked render target. | `domain_entities.md`; public detail route/model code. | Raw Account rendering needs a new public identity contract. | `High` | `Keep as Assumption` |
| `A-02` | Existing `supportedEntities` projection code can be reused or replaced for grouped profile cards. | `PartnerSupportedEntityView` and `_supportedEntities` currently exist but are skeletal. | Build a new grouped linked-profile module. | `Medium` | `Keep as Assumption` |
| `A-03` | Admin account profile form is the right authoring surface. | User requested adding nested groups while configuring Account/Profile content. | A separate workspace/editor route would be needed. | `Medium` | `Keep as Assumption` |
| `A-04` | Embedded groups stay document-safe under the frozen first limits. | `D-NEST-07` bounds groups and members; the group payload is summary/id oriented. | Storage may need a separate relation collection in a future slice. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `laravel-app/app/Application/AccountProfiles/**`
- `laravel-app/app/Http/Api/v1/**`
- `laravel-app/app/Models/Tenants/AccountProfile.php`
- `laravel-app/tests/**`
- `flutter-app/lib/domain/partners/**`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/partners_backend/**`
- `flutter-app/lib/presentation/tenant_admin/account_profiles/**`
- `flutter-app/lib/presentation/tenant_public/partners/**`
- `flutter-app/test/**`

### Ordered Steps
1. Close storage owner and size-limit decisions.
2. Add fail-first Laravel tests for admin persistence and public projection.
3. Add fail-first Flutter DTO/domain and public tab rendering tests.
4. Implement backend validation, persistence, formatting, and public projection.
5. Implement Flutter domain/DTO parsing and tenant-admin editor.
6. Render public grouped tabs with profile cards and navigation.
7. Update module docs and run focused + local CI-equivalent suites.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** Laravel feature tests, Flutter DTO tests, Flutter admin/public widget tests.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Admin creates/edits nested groups | Admin mutation | `shared-android-web` | widget + optional Playwright mutation | `yes` | Flutter admin test + Laravel feature test |
| Public custom tabs render | Public visible navigation | `shared-android-web` | widget/navigation | `no` | Flutter public detail widget/navigation test |
| Linked profile navigation works | Public navigation | `shared-android-web` | widget/navigation | `no` | Flutter route/navigation test |

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Visible Action | DTO/Decoder Path | Planned Evidence |
| --- | --- | --- | --- | --- |
| Account Profile admin CRUD nested group payload | Flutter tenant-admin | create/edit nested tabs | tenant-admin account profile encoder/decoder | Laravel + Flutter admin tests |
| Public Account Profile detail nested group projection | Flutter tenant-public | profile detail tabs/cards | partners backend DAO + domain model | DTO + public widget tests |

## Local CI-Equivalent Suite Matrix
| Repo | CI Surface | Local Command | Required Before Delivery |
| --- | --- | --- | --- |
| `laravel-app` | Laravel feature/unit tests | project safe Laravel test runner for Account Profile tests | `yes` |
| `flutter-app` | analyzer + focused tests | `fvm dart analyze --format machine` and focused `fvm flutter test ...` | `yes` |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, Account Profile and Tenant Admin module anchors, source inventory snapshot, frozen decisions `D-NEST-01..08`, frontend/consumer matrix, flow evidence matrix, and local CI-equivalent suite matrix.
- **Orchestrator-owned checks:** verify worker output follows embedded `nested_profile_groups`, persists Account Profile ids, enforces `12/50` limits, and hides empty public tabs.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `NEST` inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; no recursive hierarchy, Account Workspace permission model, or raw Account public rendering contract is authorized.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This big tactical TODO is now approved for implementation in its own wave. | Approved scope, DoD, validation, and delivery gates. | Expanding into workspace permissions or recursive nesting. | Worker must stay bounded to embedded groups. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO is a dedicated late wave in the orchestration plan. | Worker-owned implementation and orchestrator-owned reconciliation. | Mixing with reference/settings implementation files. | Orchestrator dispatches as separate wave. |
| `delphi-ai/rules/core/package-first-model-decision.md` | The feature introduces persisted cross-stack account-profile grouping behavior. | Package/reuse assessment and project-local rationale. | Creating generic hierarchy utilities without package-first check. | Worker records package-first evidence if new reusable helpers appear. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches Flutter tenant-admin editor and public Account Profile tabs. | DTO-domain mapping, controller ownership, route discipline, analyzer-clean state. | Widget-owned persistence or local-only tab models. | Worker must cover DTO, admin, and public rendering. |
| `delphi-ai/rules/stacks/laravel/shared/tenant-access-guardrails-model-decision.md` | Backend must validate tenant scope for linked Account Profiles. | Tenant boundary, validation, and bounded embedded document shape. | Cross-tenant links or unbounded arrays. | Worker must add tenant-boundary and limit tests. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | New persistence/projection/admin/public flows require broad focused coverage. | Semantic assertions for ordering, duplicates, and hidden empty tabs. | Status-only tests. | Worker creates Laravel and Flutter tests. |

## Completion Evidence Matrix
| Criterion | Evidence | Status | Notes |
| --- | --- | --- | --- |
| DoD + validation rows | `pending` | `planned` | Fill before any delivery claim. |
