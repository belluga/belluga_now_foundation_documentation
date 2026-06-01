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
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Feature`, `Cross-Stack`, `Tenant-Admin`, `Tenant-Public`, `User-Visible`, `Promotion-Lane-Pending`
- **Next exact step:** move this TODO with the validated v0.2.0+8 package into the promotion lane after individual closeout guards and the orchestration checkpoint.

## Scope
- [x] Define a persisted nested group contract on Account Profile or a related Account Profile aggregate.
- [x] Each group has a stable key/id, display label, order, and selected linked Account Profile ids.
- [x] Tenant admin can create, rename, reorder, delete groups, and manage selected linked accounts/profiles in each group.
- [x] Public Account Profile detail renders one tab per non-empty group using the group label as the tab title.
- [x] Public tabs render selected linked profiles as cards using existing Account Profile visual/identity conventions.
- [x] Public payloads include enough nested group data for no-extra-query rendering, or explicitly define the repository lookup path.
- [x] Backend validates tenant scope, active/deleted profiles, duplicate prevention, and bounded list sizes.
- [x] Profile type capability `has_nested_profile_groups` controls whether tenant-admin Account Profile forms expose nested group authoring and whether backend/public projections accept or expose nested groups for that type.
- [x] Tests cover admin persistence/readback, public projection, Flutter DTO/domain parsing, and tab rendering.

## Out of Scope
- [ ] Account Workspace membership/team permissions.
- [ ] User-owned account claim flows.
- [ ] Generic organization hierarchy or arbitrary recursive nesting.
- [ ] Event linked profile category tabs.
- [ ] New public profile module marketplace/editor beyond this nested-group capability.

## Dependencies & Sequencing
- [x] `DEP-01` Must preserve Account Profile as the public identity surface.
- [x] `DEP-02` Must not redefine Account Workspace permissions from `TODO-vnext-account-workspace.md`.
- [x] `DEP-03` Should run after any active Account Profile registry/persistence hardening if those changes touch the same request/formatter paths.

## Definition of Done
- [x] Admin can add at least two custom groups with different labels and selected profiles.
- [x] Admin can update and read back group membership/order.
- [x] Public Account Profile detail exposes the groups in the intended order as tabs.
- [x] Empty groups do not render public tabs unless product explicitly approves empty-state tabs during planning.
- [x] Linked profile cards use existing profile identity/visual fallbacks and navigate to the linked profile detail when a route is available.
- [x] Backend enforces tenant boundary and rejects invalid or cross-tenant profile ids.
- [x] Tenant-admin profile type configuration exposes the nested-groups capability, and Account Profile create/edit hides/suppresses nested groups when that capability is disabled.
- [x] Tests prove no duplicate profile cards inside one group and deterministic ordering.

## Validation Steps
- [x] Laravel feature tests for create/update/read Account Profile nested groups.
- [x] Laravel public Account Profile detail/list projection test for nested groups.
- [x] Flutter DTO/domain tests for nested group decoding.
- [x] Flutter tenant-admin form/controller/widget tests for group editing.
- [x] Flutter/web navigation test for capability-enabled and capability-disabled Account Profile edit surfaces.
- [x] Flutter public Account Profile detail widget/navigation test for custom tabs.
- [x] Analyzer/local CI-equivalent suite row completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`, `strategic-cto-tech-lead` if storage model or module decisions change materially.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why The Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Cross-stack persisted/public tab behavior requires regression and flow validation. | `laravel-app`, `flutter-app`, tests | `completed`; focused Laravel, Flutter analyzer, Flutter DTO/admin/public widget/navigation tests passed. |

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
- [x] `D-NEST-09` Nested group authoring is not global to all Account Profile types. The profile type capability `has_nested_profile_groups` is the only switch that enables the tenant-admin nested-group editor, permits non-empty backend writes, and allows public detail projection of stored groups.

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
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` Account Profile nested feature/projection tests | Backend persistence, validation, tenant-bound linked profiles, and public projection changed. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=Nested` | `Local-Validated` | passed | `5` tests, `22` assertions passed on 2026-05-27. | Includes admin update persistence, invalid members, group/member limits, public projection, and one existing nested media unit test matched by the filter. |
| `flutter-app` analyzer | Flutter domain, DTO, repository, controller, admin UI, public detail UI, and tests changed. | `fvm dart analyze --format machine` | `Local-Validated` | passed | `cd flutter-app && fvm dart analyze --format machine` exited `0` with no output on 2026-05-27. | Analyzer-clean after value-object refactor and integration-test fake updates. |
| `flutter-app` focused nested group tests | DTO/domain parsing, tenant-admin editing, repository payloads, and public custom tab/navigation behavior changed. | `fvm flutter test --no-pub` with the five focused nested Account Profile test files | `Local-Validated` | passed | `6` tests passed on 2026-05-27. | Covers DTO parse, admin repository payload, controller forwarding, admin group editor candidate selection, public tab rendering/navigation, and content-only regression. |
| v0.2.0+8 consolidated reconciliation wrapper | NEST must remain green with the full approved v0.2.0+8 package after later waves landed. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 consolidated focused validation rerun after WSL disconnect"` with NEST Laravel/Flutter targets plus package targets. | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Consolidated wrapper reported Laravel tests, Flutter tests, Flutter analyzer, and Flutter rule matrix passed. |
| v0.2.0+8 final Atlas-backed reconciliation matrix | This TODO participates in the approved consolidated v0.2.0+8 package and must stay green after web/runtime lanes. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 full CI-equivalent against Atlas-backed dev runtime" ...` | `Promotion-Lane-Pending` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260528_012558_full_ci_equivalent_atlas_runtime.md` | Passed `atlas_runtime_db_target`, `reconcile_laravel_tests`, `reconcile_flutter_tests`, `reconcile_flutter_analyze`, `flutter_rule_matrix`, `flutter_web_build`, `web_navigation_readonly`, and `web_navigation_mutation` where applicable. |
| 2026-06-01 capability-gate correction | Manual validation found the Account Profile editor exposed `Abas de contas vinculadas` for types without a dedicated capability. The approved NEST behavior now requires type-level gating across backend, Flutter, and browser navigation. | Completed commands: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php --filter='nested/profile_type_create/capability_is_disabled/preserves_existing_capabilities'`; `cd flutter-app && fvm flutter test --no-pub test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_profile_create_screen_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_profile_edit_screen_test.dart`; `cd flutter-app && fvm dart analyze --format machine`; `cd flutter-app && bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh`; `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `NAV_WEB_SHARD=admin-final NAV_WEB_WORKERS=1 PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation`. | `Local-Validated` | passed | 2026-06-01 completed evidence: Laravel `15` tests/`74` assertions; Flutter focused `41` tests; analyzer exit `0`; rule matrix detected `57` configured lint codes; web build published `../web-app` in `143.1s` with `__WEB_BUILD_SHA__=2c3eccde`; `https://guarappari.belluga.space/` served `__WEB_BUILD_SHA__=2c3eccde`; Playwright admin-final `9 passed (5.7m)`. | Browser evidence includes `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js` test `@mutation tenant-admin account profile nested tabs obey profile type capability`, which creates disabled/enabled profile types and validates edit-surface hide/show behavior through the project runner. |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, Account Profile and Tenant Admin module anchors, source inventory snapshot, frozen decisions `D-NEST-01..09`, frontend/consumer matrix, flow evidence matrix, and local CI-equivalent suite matrix.
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

## Package-First Assessment
| Search / Surface | Command | Status | Finding | Decision |
| --- | --- | --- | --- | --- |
| Account Profile grouping reuse | `bash delphi-ai/tools/query_packages.sh --project-root . --search "account profile"` | passed | `0` package(s) found. | Local implementation is appropriate because this is host-specific Account Profile public-tab behavior. |
| Nested grouping reuse | `bash delphi-ai/tools/query_packages.sh --project-root . --search "nested"` | passed | `0` package(s) found. | No package-owned generic hierarchy capability matched the bounded embedded `nested_profile_groups` contract. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-NEST-01..09` | Laravel persists bounded embedded `nested_profile_groups`; Flutter admin selects Account Profile ids; public detail renders non-empty groups as custom tabs with linked Account Profile cards only when the parent profile type enables `has_nested_profile_groups`. | passed | No recursive hierarchy, Account Workspace membership, raw Account public rendering, or global nested editor exposure was introduced. |
| Module promotion targets | `modules/account_profile_catalog_module.md` records `PCO-14`; `modules/tenant_admin_module.md` records `TAD-15`. | passed | Stable storage/admin/public contracts were promoted to module docs. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Laravel nested group write/read | Tenant boundary, invalid ObjectId rejection, self-link rejection, active/deleted profile filtering, duplicate prevention, `12/50` bounds. | passed | `AccountProfileNestedGroupService`; `AccountProfilesControllerTest` nested tests. | Linked profiles are resolved in the tenant store and public projection filters unavailable/private profiles before render. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Embedded nested groups | Bounded document growth and public projection lookup cost. | passed | Limits in `InputConstraints`; Laravel tests for `12` groups and `50` members per group. | First implementation is bounded and one-level; no recursive query path or unbounded public expansion was introduced. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Local NEST wave diff across `laravel-app`, `flutter-app`, and foundation docs | CI/Copilot failure modes: analyzer regressions, backend validation gaps, tenant-boundary leaks, public navigation regressions, stale module docs. | passed | Laravel safe runner, Flutter analyzer, focused Flutter tests, package-first queries, scoped Rule-Spirit scans. | no p1 or p2 findings | Local validation and manual diff review found no blocker; remote PR/Copilot checks remain part of later promotion lane execution. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter architecture and TODO delivery rules | Scoped scan over changed Flutter production paths for DTO/domain boundary bypass, presentation-owned persistence, imperative navigation, and build-side-effect patterns. | passed | `bash delphi-ai/tools/rule_spirit_anti_pattern_scan.sh --repo flutter-app --stack flutter --path ... --json-output foundation_documentation/artifacts/tmp/nested-account-profile-groups-rule-spirit-flutter.json`; 2026-06-01 rerun wrote `foundation_documentation/artifacts/tmp/nested-capability-gate-rule-spirit-flutter.json`. | no p1 or p2 findings | Rerun reported one warning in `lib/infrastructure/dal/dto/app_data_dto.dart` for an infrastructure import; triaged as scanner-scope false positive because the path is infrastructure-owned, not presentation/application bypass. |
| Laravel tenant/access and TODO delivery rules | Scoped scan over changed Laravel production/test paths for tenant guard bypass, hard-coded runtime targets, and validation shortcuts. | passed | `bash delphi-ai/tools/rule_spirit_anti_pattern_scan.sh --repo laravel-app --stack laravel --path ... --json-output foundation_documentation/artifacts/tmp/nested-account-profile-groups-rule-spirit-laravel.json`; 2026-06-01 rerun wrote `foundation_documentation/artifacts/tmp/nested-capability-gate-rule-spirit-laravel.json`. | no p1 or p2 findings | Rerun reported `0` findings. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Define a persisted nested group contract on Account Profile or a related Account Profile aggregate. | code and docs | `AccountProfile::nested_profile_groups`; `AccountProfileNestedGroupService`; module docs `PCO-14` and `TAD-15` | Laravel + Foundation docs | passed | Contract is embedded on Account Profile with bounded group/member shape. |
| SCOPE-02 | Scope | Each group has a stable key/id, display label, order, and selected linked Account Profile ids. | code and tests | Laravel request validation, service normalization, Flutter value objects, focused Flutter/Laravel tests | Cross-stack | passed | `id`, `label`, `order`, and ordered Account Profile ids are persisted and decoded. |
| SCOPE-03 | Scope | Tenant admin can create, rename, reorder, delete groups, and manage selected linked accounts/profiles in each group. | widget/controller mutation tests | `tenant_admin_account_profiles_controller_test.dart`; `tenant_admin_account_profile_create_screen_test.dart` | Flutter tenant-admin widget/navigation test | passed | Admin create/edit drafts and controller operations manage groups and member selections. |
| SCOPE-04 | Scope | Public Account Profile detail renders one tab per non-empty group using the group label as the tab title. | public widget/navigation test | `account_profile_detail_screen_test.dart` custom tab test | Flutter tenant-public widget/navigation test | passed | Public detail adds custom group tabs after configured tabs and hides empty groups. |
| SCOPE-05 | Scope | Public tabs render selected linked profiles as cards using existing Account Profile visual/identity conventions. | public widget/navigation test | `account_profile_detail_screen_test.dart`; `AccountProfileIdentityBlock`; resolved profile visuals | Flutter tenant-public widget/navigation test | passed | Linked cards reuse existing identity/visual rendering and profile detail navigation. |
| SCOPE-06 | Scope | Public payloads include enough nested group data for no-extra-query rendering, or explicitly define the repository lookup path. | backend projection and DTO test | `AccountProfileFormatterService`; `LaravelAccountProfilesBackend` nested group parse test | Laravel + Flutter DTO | passed | Public detail projects `nested_profile_groups[].profiles` snapshots for direct rendering. |
| SCOPE-07 | Scope | Backend validates tenant scope, active/deleted profiles, duplicate prevention, and bounded list sizes. | Laravel feature tests | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=Nested` | Laravel | passed | Invalid members, duplicate/self-link handling, active/public filtering, and limits are covered. |
| SCOPE-08 | Scope | Tests cover admin persistence/readback, public projection, Flutter DTO/domain parsing, and tab rendering. | focused test suites | Laravel safe runner plus focused Flutter nested group command | Cross-stack | passed | Backend and Flutter coverage passed after final value-object refactor. |
| SCOPE-09 | Scope | Profile type capability `has_nested_profile_groups` controls whether tenant-admin Account Profile forms expose nested group authoring and whether backend/public projections accept or expose nested groups for that type. | backend, Flutter widget, and browser navigation tests | Laravel safe runner focused on `capability_is_disabled`; Flutter create/edit screen tests; Playwright `@mutation tenant-admin account profile nested tabs obey profile type capability` | Laravel + Flutter + web navigation | passed | Disabled types reject non-empty backend writes, hide/suppress admin nested payloads, and suppress public projection. Enabled types show the editor in real browser navigation. |
| DOD-01 | Definition of Done | Admin can add at least two custom groups with different labels and selected profiles. | admin widget/navigation mutation test and controller test | `tenant_admin_account_profile_create_screen_test.dart`; controller group operation tests | Flutter tenant-admin widget/navigation test | passed | UI can add groups and select Account candidates; controller supports rename/reorder/remove/member toggles. |
| DOD-02 | Definition of Done | Admin can update and read back group membership/order. | backend mutation test and Flutter repository/widget navigation evidence | `test_account_profile_update_persists_nested_profile_groups_in_order`; repository payload test; tenant-admin widget/navigation group editor test | Laravel + Flutter repository/widget navigation test | passed | Persisted group and member order round-trip through API and Flutter payload encoding. |
| DOD-03 | Definition of Done | Public Account Profile detail exposes the groups in the intended order as tabs. | public widget/navigation test | `renders nested account profile groups as custom tabs and navigates linked profile` | Flutter tenant-public widget/navigation test | passed | Groups sort by persisted `order`. |
| DOD-04 | Definition of Done | Empty groups do not render public tabs unless product explicitly approves empty-state tabs during planning. | Laravel projection test and public widget/navigation test | `test_public_account_profile_detail_projects_nested_groups_and_hides_empty_groups`; content-only public widget/navigation regression | Laravel + Flutter public detail widget/navigation test | passed | Empty groups remain admin-editable but are absent from public detail tabs. |
| DOD-05 | Definition of Done | Linked profile cards use existing profile identity/visual fallbacks and navigate to the linked profile detail when a route is available. | public navigation test and widget route test | `account_profile_detail_screen_test.dart` route assertion for `/parceiro/{slug}` | Flutter tenant-public navigation test | passed | Tapping linked card navigates to route `/parceiro/{slug}`. |
| DOD-06 | Definition of Done | Backend enforces tenant boundary and rejects invalid or cross-tenant profile ids. | Laravel validation/service tests | `test_account_profile_update_rejects_invalid_nested_profile_group_members`; tenant-bound service lookup | Laravel | passed | Service validates ObjectIds and tenant-local active profiles before accepting members. |
| DOD-07 | Definition of Done | Tests prove no duplicate profile cards inside one group and deterministic ordering. | Laravel and Flutter focused tests | Nested group update/projection tests and public tab render test | Cross-stack | passed | Service deduplicates/rejects duplicates and rendering follows persisted order. |
| DOD-08 | Definition of Done | Tenant-admin profile type configuration exposes the nested-groups capability, and Account Profile create/edit hides/suppresses nested groups when that capability is disabled. | widget and browser navigation tests | `tenant_admin_profile_type_form_screen_test.dart`; `tenant_admin_account_profile_create_screen_test.dart`; `tenant_admin_account_profile_edit_screen_test.dart`; Playwright admin-final shard | Flutter tenant-admin + browser mutation | passed | The profile-type form toggles the capability; create/edit screens hide disabled nested groups; real browser navigation validates disabled/enabled edit surfaces. |
| VAL-01 | Validation Steps | Laravel feature tests for create/update/read Account Profile nested groups. | Laravel feature tests | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=Nested` | Laravel | passed | Includes update persistence/readback. |
| VAL-02 | Validation Steps | Laravel public Account Profile detail/list projection test for nested groups. | Laravel projection test plus public widget/navigation consumer test | `test_public_account_profile_detail_projects_nested_groups_and_hides_empty_groups`; public widget/navigation custom tabs test | Laravel + Flutter public widget/navigation test | passed | Public detail projection includes visible groups and hides empty/unavailable links, then Flutter consumes the projection as custom tabs. |
| VAL-03 | Validation Steps | Flutter DTO/domain tests for nested group decoding. | Flutter DTO/domain test | `fetchAccountProfileBySlug parses nested account profile groups` | Flutter | passed | Public DAO parses nested group and member value objects. |
| VAL-04 | Validation Steps | Flutter tenant-admin form/controller/widget tests for group editing. | Flutter tenant-admin form/controller/widget navigation tests | Controller update forwarding test plus create-screen group editor candidate widget/navigation test | Flutter tenant-admin widget/controller navigation test | passed | Admin editor and repository payload coverage passed. |
| VAL-07 | Validation Steps | Flutter/web navigation test for capability-enabled and capability-disabled Account Profile edit surfaces. | Playwright browser mutation | Source spec `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js`; project runner `tools/flutter/run_web_navigation_smoke.sh`; build/publish command `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; served bundle provenance `https://guarappari.belluga.space/` exposed `__WEB_BUILD_SHA__=2c3eccde`; navigation command `NAV_WEB_SHARD=admin-final NAV_WEB_WORKERS=1 PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation` -> `9 passed (5.7m)`. | `https://guarappari.belluga.space` dev lane | passed | Shard selected 9 deterministic admin-final tests and passed; includes nested capability hide/show navigation for enabled and disabled profile types. |
| VAL-05 | Validation Steps | Flutter public Account Profile detail widget/navigation test for custom tabs. | Flutter public widget/navigation test | `renders nested account profile groups as custom tabs and navigates linked profile` | Flutter tenant-public widget/navigation test | passed | Custom tab rendering and linked profile navigation are covered. |
| VAL-06 | Validation Steps | Analyzer/local CI-equivalent suite row completed before delivery. | local CI-equivalent | Local CI-Equivalent Suite Matrix rows above | Cross-stack | passed | Laravel safe runner, Flutter analyzer, and focused Flutter tests passed on 2026-05-27. |

## TODO Closeout Disposition
- **Disposition:** `keep-active`
- **Disposition reason:** local NEST implementation and consolidated v0.2.0+8 validation are complete, but this TODO remains in the active package until promotion-lane movement is performed for the whole approved set.
- **Post-commit/push status:** `pending`
- **Next path/status action:** after individual closeout guards pass and the orchestration checkpoint is committed, move this TODO with the v0.2.0+8 package into `foundation_documentation/todos/promotion_lane/` or update this disposition with any real lane blocker.
