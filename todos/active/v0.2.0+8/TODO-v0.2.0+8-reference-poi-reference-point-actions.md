# Title
VNext: Reference POI Point-of-Reference Actions

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The project already has a partially implemented reference-location/proximity preference foundation:

- `FixedLocationReference`
- `ProximityLocationPreference.fixedReference`
- `/api/v1/profile/proximity-preferences`
- Account Profile type capability `is_reference_location_enabled`
- existing profile UI terminology around `Minha localização` and the older `origem padrão` label that this TODO must rename to `ponto de referência`

The requested feature is to complete the user-facing action: a POI or eligible Account Profile can be saved as the user's `ponto de referência`. The action must exist on the map card and on the Account Profile hero for profiles that have the capability.

Additional detail requested on 2026-05-25:

- If an item is already marked as the current `ponto de referência`, the button must visibly enter a selected/current state.
- Use `ponto de referência` instead of `origem padrão` across affected user-visible copy.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `reference-poi-reference-point-actions`
- **Why this is the right current slice:** this is the end-user activation layer for the already designed fixed-reference capability.
- **Direct-to-TODO rationale:** core contract work already has a dedicated blocker TODO; this TODO scopes the visible map/profile actions and state handling.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Feature`, `Cross-Stack`, `Tenant-Public`, `User-Visible`, `Depends-On-Reference-Core`, `Promotion-Lane-Pending`
- **Next exact step:** move this TODO with the validated v0.2.0+8 package into the promotion lane after individual closeout guards and the orchestration checkpoint.

## Scope
- [x] Add a text+icon action below current CTA buttons on the map POI card.
- [x] Add a hero action on Account Profile detail when the profile/type has effective `is_reference_location_enabled=true`.
- [x] Save the selected POI/Profile as the user's fixed `ponto de referência` using the existing proximity preference contract.
- [x] Persist entity-backed reference metadata: `source_kind`, `entity_namespace`, `entity_type`, `entity_id`, display label, and coordinate snapshot.
- [x] Persist enough source metadata for downstream route prompts to display the Account Profile name and navigate to the Account Profile when the reference point is Account Profile-backed.
- [x] Treat saved reference state as user-owned preference state, not Account Profile-owned state.
- [x] Show a distinct current/selected state when the displayed POI/Profile is already the active `ponto de referência`.
- [x] Update affected user-visible copy from `origem padrão` to `ponto de referência`.
- [x] Reset the directions route prompt policy setting to `null` whenever a new `ponto de referência` is selected.
- [x] Respect disabled/ineligible reference resolution when source capability prerequisites fail.
- [x] Add tests for map card action, Account Profile hero action, persistence, and selected-state rendering.

## Out of Scope
- [ ] Reopening or redefining the reusable fixed-reference core contract.
- [ ] Generic entity picker/search for reference locations.
- [ ] Applying reference-origin actions to Static Assets or Events in the first rollout.
- [ ] Broad Home/Discovery/Search geo consumer rewiring beyond updating the stored point-of-reference preference.
- [ ] Changing route-provider UI from `TODO-v0.2.0+8-event-directions-inline-provider-actions.md`.

## Dependencies & Sequencing
- [x] `DEP-01` Hard dependency on `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md` for disabled-reference payload and capability semantics.
- [x] `DEP-02` Related to `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-proximity-preferences-and-location-origin.md`; this TODO may consume existing implementation but must not redefine identity merge semantics.
- [x] `DEP-03` Coordinate settings reset semantics with `TODO-v0.2.0+8-event-directions-inline-provider-actions.md`; this TODO owns the reset when a new `ponto de referência` is selected.

## Definition of Done
- [x] Map POI cards for eligible account-profile POIs show an action to set/use the POI as the `ponto de referência`.
- [x] Account Profile hero shows the action only for profiles with effective reference-location capability and usable coordinates.
- [x] Tapping either action saves the entity-backed fixed reference through the proximity preference path.
- [x] Saved reference includes coordinate snapshot, generic entity provenance, display label, and route/navigation metadata needed by route prompt consumers.
- [x] The same POI/Profile renders a selected/current state when it is already the active `ponto de referência`.
- [x] Selecting a new `ponto de referência` resets the directions route prompt policy setting to `null`.
- [x] Ineligible profiles do not show the action, or show a disabled state only if explicitly approved during planning.
- [x] Auth/anonymous behavior is deterministic and matches existing proximity preference repository behavior.
- [x] Tests cover map card, hero, persistence payload, and current-state rendering.

## Validation Steps
- [x] Laravel test for persisting entity-backed fixed reference via proximity preferences, including provenance fields.
- [x] Laravel/Flutter test proving Account Profile-backed references expose enough label/route metadata for prompt display and shortcut navigation.
- [x] Laravel/Flutter tests for disabled/ineligible reference resolution if the blocker is not already closed with sufficient evidence.
- [x] Flutter repository/domain test for mapping active fixed reference to UI selected state.
- [x] Flutter map card widget test for action visibility, tap, and selected state.
- [x] Flutter Account Profile hero widget test for capability-gated action visibility, tap, and selected state.
- [x] Analyzer/local CI-equivalent suite rows completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why The Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | User-owned preference persistence and public action visibility need focused regression validation. | `flutter-app`, `laravel-app`, tests | `completed`; Laravel proximity/capability tests, Flutter repository/domain tests, map card tests, Account Profile hero tests, analyzer, and rule matrix passed. |

## Complexity
- **Level:** `medium`
- **Checkpoint policy:** `full Plan Review Gate before APROVADO + post-validation checkpoint`
- **Why this level:** visible UI work is contained, but persistence, capability gating, and selected-state consistency cross Flutter and Laravel contracts.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`

## Source Inventory Snapshot
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-proximity-preferences-and-location-origin.md`
- `laravel-app/app/Application/ProximityPreferences/ProximityPreferenceService.php`
- `flutter-app/lib/domain/proximity_preferences/fixed_location_reference.dart`
- `flutter-app/lib/domain/proximity_preferences/proximity_location_preference.dart`
- `flutter-app/lib/infrastructure/repositories/proximity_preferences_repository.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/proximity_preferences_backend/laravel_proximity_preferences_backend.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_base_card.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart`
- `flutter-app/lib/domain/map/projections/city_poi_model.dart`
- `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `flutter-app/lib/domain/partners/profile_type_capabilities.dart`
- `flutter-app/lib/domain/partners/profile_type_registry.dart`

## Decisions
- [x] `D-REF-ACT-01` The saved `ponto de referência` is user-owned preference/settings state.
- [x] `D-REF-ACT-02` Map card action appears below existing CTA buttons as text+icon.
- [x] `D-REF-ACT-03` Account Profile hero action appears only when the profile has effective reference-location eligibility.
- [x] `D-REF-ACT-04` Active/saved reference state must be visually clear on the same item.
- [x] `D-REF-ACT-05` Use `ponto de referência` for this concept and update affected visible copy that previously used `origem padrão`.
- [x] `D-REF-ACT-06` Entity references persist exact coordinate snapshot and generic provenance.
- [x] `D-REF-ACT-07` First rollout is Account Profile POIs/Account Profile detail only. Event and Static Asset POIs are out of scope until they have an explicit reference-location capability contract.
- [x] `D-REF-ACT-08` Anonymous users may save a reference through the existing anonymous/identity preference path when an identity token is available. Auth-wall only when the repository cannot persist without authentication.
- [x] `D-REF-ACT-09` Use `Usar como ponto de referência` for the inactive action and `Ponto de referência` for the active/current state.
- [x] `D-REF-ACT-10` Selecting a new `ponto de referência` resets the directions route prompt policy setting to `null`.
- [x] `D-REF-ACT-11` Account Profile-backed reference points must preserve display label and route metadata so downstream prompts can show the Account Profile name and offer a shortcut to that profile.

## Closed Questions
- [x] Event/Static first-rollout scope closed by `D-REF-ACT-07`.
- [x] Anonymous/auth behavior closed by `D-REF-ACT-08`.
- [x] Visible labels closed by `D-REF-ACT-09`.
- [x] Route prompt reset behavior closed by `D-REF-ACT-10`.
- [x] Account Profile prompt shortcut metadata closed by `D-REF-ACT-11`.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Account Profile POIs are the first supported entity-backed reference source. | Existing blocker names Account Profile/hotel as first downstream shape; capability exists on profile types. | Add capability model for other POI sources or narrow UI harder. | `High` | `Keep as Assumption` |
| `A-02` | Current proximity preferences repository can save fixed references once provenance is available. | Flutter/Laravel proximity preference code already exists. | Expand backend/DTO implementation in this TODO or block on core TODO. | `Medium` | `Keep as Assumption` |
| `A-03` | Active reference comparison can use provenance (`entity_namespace`, `entity_type`, `entity_id`) rather than coordinate equality. | Entity-backed reference schema includes entity provenance. | Need fallback coordinate/name comparison, which is weaker. | `High` | `Keep as Assumption` |
| `A-04` | Anonymous persistence can use the existing identity-token path when available. | Proximity preference backend already requires an identity token, and app has anonymous identity bootstrap. | Auth-wall behavior must be triggered when no token exists. | `Medium` | `Promoted to Decision` |
| `A-05` | Account Profile route metadata can be derived from the saved entity reference or current Account Profile payload. | Account Profile references carry `entity_namespace`, `entity_type`, `entity_id`, and public profile surfaces already route by slug/id. | Save an additional route/slug snapshot in the fixed reference. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/domain/proximity_preferences/**`
- `flutter-app/lib/infrastructure/repositories/proximity_preferences_repository.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/proximity_preferences_backend/**`
- `flutter-app/lib/presentation/tenant_public/map/**`
- `flutter-app/lib/presentation/tenant_public/partners/**`
- `laravel-app/app/Application/ProximityPreferences/**`
- `laravel-app/tests/**`
- `flutter-app/test/**`
- `foundation_documentation/modules/**`

### Ordered Steps
1. Verify blocker TODO status for fixed-reference provenance and disabled resolution.
2. Add fail-first persistence tests for entity-backed fixed reference if blocker coverage is incomplete.
3. Add Flutter repository/domain tests for active reference comparison.
4. Add map card and Account Profile hero widget tests for action visibility/current state.
5. Implement persistence wiring, source label/route metadata, and UI actions.
6. Update module docs with stable action/state decisions.
7. Run focused tests and local CI-equivalent suites.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** Laravel proximity preference tests, Flutter repository/domain tests, map card widget tests, Account Profile hero widget tests.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Map card saves POI as reference point | Public mutation | `shared-android-web` | widget/repository + optional Playwright mutation | `yes` | Flutter widget/repository test + Laravel persistence test |
| Account Profile hero saves profile as reference point | Public mutation | `shared-android-web` | widget/repository | `yes` | Flutter hero widget/repository test |
| Active item shows selected/current state | Public visible state | `shared-android-web` | widget | `no` | Flutter widget test with seeded active reference |
| New reference point resets route prompt setting | Public preference mutation | `shared-android-web` | repository/controller | `yes` | settings/proximity repository test |

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Visible Action | DTO/Decoder Path | Planned Evidence |
| --- | --- | --- | --- | --- |
| Proximity preference fixed reference | Flutter map card + Account Profile hero + Profile `Minha localização` | save/current point-of-reference state + prompt shortcut metadata | proximity preference DTO/repository | Laravel + Flutter tests |
| Directions route prompt setting | Flutter Event detail directions | reset to `null` when reference point changes | settings/proximity preference DTO/repository | repository tests |
| Account Profile type capability `is_reference_location_enabled` | Flutter Account Profile hero | action visibility | app/profile type registry DTO | capability-gated widget test |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` proximity preference and capability tests | Entity-backed fixed reference persistence, disabled resolution, capability gating, and anonymous merge behavior changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --laravel-test tests/Feature/Profile/ProfileProximityPreferencesControllerTest.php --laravel-test tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --laravel-test tests/Feature/Identity/AnonymousIdentityMergerProximityPreferenceTest.php` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Laravel proximity/capability/identity focused tests passed. |
| `flutter-app` proximity/reference repositories | Fixed reference DTO, repository mapping, selected-state state, and route prompt reset behavior changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --flutter-test test/infrastructure/dal/dto/proximity_preference_dto_test.dart --flutter-test test/infrastructure/repositories/proximity_preferences_repository_test.dart --flutter-test test/infrastructure/repositories/app_data_repository_location_origin_test.dart --flutter-analyze` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Flutter DTO/repository/reset tests and analyzer passed. |
| `flutter-app` public map and Account Profile UI tests | Map card action, Account Profile hero action, selected/current state, and shortcut metadata changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --flutter-test test/presentation/tenant/map/screens/map_screen/widgets/poi_default_card_test.dart --flutter-test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart --flutter-test test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Map card and Account Profile detail/controller tests passed. |
| `flutter_rule_matrix` architecture lint | Public UI, repository, and DTO paths participated in the reconciliation set. | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` through the reconcile wrapper. | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Rule matrix stage passed with recorded lint-code coverage. |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO and after DEP-01 is green, or as a second wave after blocker closure in the same orchestration plan`.
- **Worker package minimum:** this TODO file, reference-location core TODO status, proximity preference TODO status, module anchors, source inventory snapshot, frozen decisions `D-REF-ACT-01..10`, frontend/consumer matrix, flow evidence matrix, and local CI-equivalent suite matrix.
- **Orchestrator-owned checks:** verify Account Profile-only first rollout, anonymous/auth persistence behavior, exact `ponto de referência` labels, route prompt reset to `null`, and active/current state before accepting worker output.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `REF-ACT` inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; execution remains blocked until `REF-CORE` is green, and Event/Static reference actions or generic entity picker UX are not authorized.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This TODO is now approved but dependent on blocker closure. | Approved scope, dependency gates, DoD, and validation. | Starting before `REF-CORE` is green. | Orchestrator dispatches only after blocker evidence passes. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO participates in the reference/settings orchestration wave. | Worker-owned implementation and shared reset semantics with `DIR`. | Hidden overlap with directions settings implementation. | Worker coordinates through shared plan gate. |
| `delphi-ai/rules/core/package-first-model-decision.md` | The slice uses reusable fixed-reference/proximity contracts. | Existing package/lib-first reference core. | Duplicating reusable reference logic in UI-only code. | Worker consumes core instead of redefining it. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches public map and Account Profile Flutter UI plus repository/domain mapping. | Controller/repository ownership, DTO-domain mapping, analyzer-clean state. | Widget-owned persistence or cross-controller relays. | Worker must use repository-owned preference state. |
| `delphi-ai/rules/stacks/laravel/shared/tenant-access-guardrails-model-decision.md` | The slice persists tenant-scoped user preference metadata through Laravel when needed. | Tenant boundary and provenance validation. | Cross-tenant Account Profile references. | Worker must add tenant-boundary tests. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The user-visible state and persistence payload need regression coverage. | Semantic tests for active state, labels, and provenance. | Status-only UI tests. | Worker creates Laravel and Flutter focused tests. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-REF-ACT-01..11` | User-owned `ponto de referência` is persisted through proximity preferences with entity provenance, visible inactive/current labels, Account Profile-only first rollout, anonymous path coverage, reset-to-null route prompt policy, and Account Profile prompt metadata. | passed | No generic entity picker, Event/Static reference action, or Account Workspace permission change was introduced. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Entity-backed proximity preference persistence | Tenant-bound Account Profile provenance, disabled/ineligible resolution, and anonymous identity merge safety. | passed | `ProfileProximityPreferencesControllerTest.php`; `AccountProfileTypesControllerTest.php`; `AnonymousIdentityMergerProximityPreferenceTest.php`. | Reference state remains user-owned preference state and uses existing tenant/account capability gates. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Public map card and Account Profile hero selected state | Avoid repeated network fetches and coordinate-comparison churn in UI. | passed | `proximity_preferences_repository_test.dart`; `poi_default_card_test.dart`; `account_profile_detail_screen_test.dart`; analyzer. | UI consumes repository/domain preference state and compares entity provenance for current-state rendering. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Reference point action local reconciliation | CI/Copilot failure modes: tenant-boundary leaks, disabled capability bypass, anonymous persistence regression, stale `origem padrão` copy, route prompt reset loss, analyzer failures. | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | no p1 or p2 findings | Consolidated wrapper finished `promotion-ready`; remote PR/Copilot checks remain part of later promotion lane execution. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter public UI/repository architecture | Scoped v0.2.0+8 Flutter scan for widget-owned persistence, DTO/domain bypasses, imperative navigation, and build-side-effect patterns. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-flutter.json` | no p1 or p2 findings | Scanner findings were warning/review-level and triaged as fixture/infrastructure-path noise or modal-close affordances outside this action surface. |
| Laravel tenant/access guardrails | Scoped v0.2.0+8 Laravel scan for tenant guard bypasses, fixture domains, and validation shortcuts. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-laravel.json` | no p1 or p2 findings | Review-level findings were tenant/domain test fixtures rather than deployable host constants or guard bypasses. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Add a text+icon action below current CTA buttons on the map POI card. | Flutter map card widget test | `poi_default_card_test.dart`; consolidated wrapper report | Flutter public map widget test | passed | Eligible Account Profile POI cards expose the `Usar como ponto de referência` action below CTA actions. |
| SCOPE-02 | Scope | Add a hero action on Account Profile detail when the profile/type has effective `is_reference_location_enabled=true`. | Flutter Account Profile widget/navigation test | `account_profile_detail_screen_test.dart`; `account_profile_detail_controller_test.dart` | Flutter tenant-public widget/navigation test | passed | Hero action is capability-gated and hidden for ineligible profiles. |
| SCOPE-03 | Scope | Save the selected POI/Profile as the user's fixed `ponto de referência` using the existing proximity preference contract. | Laravel/Flutter persistence tests | `ProfileProximityPreferencesControllerTest.php`; `proximity_preferences_repository_test.dart` | Laravel API feature tests plus Flutter repository tests | passed | Selection persists through the proximity preference path. |
| SCOPE-04 | Scope | Persist entity-backed reference metadata: `source_kind`, `entity_namespace`, `entity_type`, `entity_id`, display label, and coordinate snapshot. | persistence/DTO tests | `ProfileProximityPreferencesControllerTest.php`; `proximity_preference_dto_test.dart`; `proximity_preferences_repository_test.dart` | Laravel API plus Flutter DTO/repository tests | passed | Entity provenance, display label, and coordinate snapshot round-trip. |
| SCOPE-05 | Scope | Persist enough source metadata for downstream route prompts to display the Account Profile name and navigate to the Account Profile when the reference point is Account Profile-backed. | route controller/widget navigation tests | `account_profile_detail_controller_test.dart`; `account_profile_detail_screen_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter controller/widget route navigation tests | passed | Account Profile-backed reference metadata exposes route prompt label and shortcut route/navigation data. |
| SCOPE-06 | Scope | Treat saved reference state as user-owned preference state, not Account Profile-owned state. | repository and API tests | `ProfileProximityPreferencesControllerTest.php`; `proximity_preferences_repository_test.dart` | Laravel preference API plus Flutter repository tests | passed | State is written to proximity preferences, not Account Profile records. |
| SCOPE-07 | Scope | Show a distinct current/selected state when the displayed POI/Profile is already the active `ponto de referência`. | public widget tests | `poi_default_card_test.dart`; `account_profile_detail_screen_test.dart` | Flutter public map and Account Profile widget tests | passed | Active item renders `Ponto de referência` current state. |
| SCOPE-08 | Scope | Update affected user-visible copy from `origem padrão` to `ponto de referência`. | focused widget tests and manual copy review | `poi_default_card_test.dart`; `account_profile_detail_screen_test.dart`; `immersive_event_detail_screen_test.dart` | Flutter public widget tests | passed | Updated surfaces use `ponto de referência` terminology. |
| SCOPE-09 | Scope | Reset the directions route prompt policy setting to `null` whenever a new `ponto de referência` is selected. | route policy repository reset tests | `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart` | Flutter route policy repository tests | passed | Selecting a new reference clears the route prompt policy to null. |
| SCOPE-10 | Scope | Respect disabled/ineligible reference resolution when source capability prerequisites fail. | capability and disabled-resolution tests | `AccountProfileTypesControllerTest.php`; `ProfileProximityPreferencesControllerTest.php`; `account_profile_detail_screen_test.dart` | Laravel API tests plus Flutter widget tests | passed | Ineligible or disabled reference targets are not exposed as active actions. |
| SCOPE-11 | Scope | Add tests for map card action, Account Profile hero action, persistence, and selected-state rendering. | focused test suites | Laravel/Flutter rows in the Local CI-Equivalent Suite Matrix | Cross-stack focused tests | passed | Map card, hero, persistence, selected state, and reset coverage all passed. |
| DOD-01 | Definition of Done | Map POI cards for eligible account-profile POIs show an action to set/use the POI as the `ponto de referência`. | Flutter widget test | `poi_default_card_test.dart` | Flutter public map widget test | passed | Eligible POI cards render the reference point action. |
| DOD-02 | Definition of Done | Account Profile hero shows the action only for profiles with effective reference-location capability and usable coordinates. | Flutter widget navigation test/controller tests | `account_profile_detail_screen_test.dart`; `account_profile_detail_controller_test.dart` | Flutter tenant-public widget navigation test/controller tests | passed | Capability and coordinate gating are covered in the Account Profile detail flow. |
| DOD-03 | Definition of Done | Tapping either action saves the entity-backed fixed reference through the proximity preference path. | widget/repository/API tests | `poi_default_card_test.dart`; `account_profile_detail_screen_test.dart`; `proximity_preferences_repository_test.dart`; `ProfileProximityPreferencesControllerTest.php` | Cross-stack widget/repository/API tests | passed | Taps invoke the repository/API preference path. |
| DOD-04 | Definition of Done | Saved reference includes coordinate snapshot, generic entity provenance, display label, and route/navigation metadata needed by route prompt consumers. | save mutation DTO/repository/controller route navigation test | `proximity_preference_dto_test.dart`; `proximity_preferences_repository_test.dart`; `account_profile_detail_controller_test.dart` | Flutter DTO/repository save mutation plus controller route navigation test | passed | Route prompt consumers receive display label, route metadata, and navigation metadata. |
| DOD-05 | Definition of Done | The same POI/Profile renders a selected/current state when it is already the active `ponto de referência`. | widget navigation test selected-state coverage | `poi_default_card_test.dart`; `account_profile_detail_screen_test.dart` | Flutter public widget navigation test | passed | Same entity renders the current-state label in map card and profile detail flows. |
| DOD-06 | Definition of Done | Selecting a new `ponto de referência` resets the directions route prompt policy setting to `null`. | route policy repository reset tests | `app_data_repository_location_origin_test.dart`; `proximity_preferences_repository_test.dart` | Flutter route policy repository tests | passed | Reset semantics are covered with settings-backed route policy state. |
| DOD-07 | Definition of Done | Ineligible profiles do not show the action, or show a disabled state only if explicitly approved during planning. | capability-gated widget/API tests | `AccountProfileTypesControllerTest.php`; `account_profile_detail_screen_test.dart` | Laravel capability tests plus Flutter widget tests | passed | Ineligible profiles do not show the action. |
| DOD-08 | Definition of Done | Auth/anonymous behavior is deterministic and matches existing proximity preference repository behavior. | Laravel identity and Flutter repository tests | `AnonymousIdentityMergerProximityPreferenceTest.php`; `proximity_preferences_repository_test.dart` | Laravel feature tests plus Flutter repository tests | passed | Anonymous preference merge and repository behavior remain deterministic. |
| DOD-09 | Definition of Done | Tests cover map card, hero, persistence payload, and current-state rendering. | focused widget navigation test, repository mutation, and API test suites | Local CI-Equivalent Suite Matrix rows above | Cross-stack widget navigation test, repository mutation, and API tests | passed | Required behavior is covered across Laravel and Flutter. |
| VAL-01 | Validation Steps | Laravel test for persisting entity-backed fixed reference via proximity preferences, including provenance fields. | Laravel feature test | `tests/Feature/Profile/ProfileProximityPreferencesControllerTest.php` through the reconcile wrapper | Laravel API feature test | passed | Provenance and coordinate snapshot persistence are asserted. |
| VAL-02 | Validation Steps | Laravel/Flutter test proving Account Profile-backed references expose enough label/route metadata for prompt display and shortcut navigation. | Laravel/Flutter route controller/widget tests | `ProfileProximityPreferencesControllerTest.php`; `account_profile_detail_controller_test.dart`; `immersive_event_detail_screen_test.dart` | Laravel API plus Flutter route widget/navigation tests | passed | Account Profile label and route shortcut metadata reach prompt consumers. |
| VAL-03 | Validation Steps | Laravel/Flutter tests for disabled/ineligible reference resolution if the blocker is not already closed with sufficient evidence. | capability and widget tests | `AccountProfileTypesControllerTest.php`; `ProfileProximityPreferencesControllerTest.php`; `account_profile_detail_screen_test.dart` | Laravel API tests plus Flutter widget tests | passed | Disabled/ineligible resolution is covered. |
| VAL-04 | Validation Steps | Flutter repository/domain test for mapping active fixed reference to UI selected state. | Flutter repository/domain test plus widget/navigation consumer | `proximity_preferences_repository_test.dart`; `proximity_preference_dto_test.dart`; `poi_default_card_test.dart` | Flutter repository/domain tests plus widget/navigation test | passed | Active fixed reference maps into UI-selected state inputs and the map card consumes it. |
| VAL-05 | Validation Steps | Flutter map card widget test for action visibility, tap, and selected state. | Flutter widget/navigation test | `test/presentation/tenant/map/screens/map_screen/widgets/poi_default_card_test.dart` through the reconcile wrapper | Flutter public map widget/navigation test | passed | Map card visibility, tap path, and selected state are covered. |
| VAL-06 | Validation Steps | Flutter Account Profile hero widget test for capability-gated action visibility, tap, and selected state. | Flutter widget/navigation test | `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` through the reconcile wrapper | Flutter Account Profile widget/navigation test | passed | Hero action visibility, tap behavior, and selected state are covered. |
| VAL-07 | Validation Steps | Analyzer/local CI-equivalent suite rows completed before delivery. | local CI-equivalent | Local CI-Equivalent Suite Matrix rows above | Cross-stack test/analyzer wrapper | passed | Consolidated wrapper passed Laravel tests, Flutter tests, analyzer, and rule matrix. |

## TODO Closeout Disposition
- **Disposition:** `keep-active`
- **Disposition reason:** local implementation and validation are complete, but this TODO remains in the active v0.2.0+8 package until promotion-lane movement is performed for the whole approved set.
- **Post-commit/push status:** `pending`
- **Next path/status action:** after individual closeout guards pass and the orchestration checkpoint is committed, move this TODO with the v0.2.0+8 package into `foundation_documentation/todos/promotion_lane/` or update this disposition with any real lane blocker.
