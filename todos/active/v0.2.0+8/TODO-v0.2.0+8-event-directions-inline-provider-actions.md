# Title
VNext: Como Chegar Inline Directions Provider Actions

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The Event detail `Como Chegar` tab currently renders a dedicated footer CTA `Traçar rota`. The requested behavior removes that tab-specific CTA/footer and keeps the standard event CTA model used by other tabs. Route actions move into the content of `Como Chegar`.

The screen has one main address/map card and related secondary address cards. Under each card, show direct provider buttons. The main card uses larger buttons; secondary cards use more compact buttons.

Additional route-launch behavior requested on 2026-05-25:

- Main direct buttons are only `Waze` and `Uber`.
- A third `Outros` button uses the standard three-dots icon and opens the existing bottom sheet with other options.
- Tapping `Waze` or `Uber` launches that provider directly after origin policy resolution; it must not open the provider chooser sheet.
- Before handing off to an external route app when the route reference-point preference is unset, show a modal asking exactly: `Qual PONTO DE PARTIDA quer usar?`
- The modal choices are `Sua localização atual` and `O ponto de referência selecionado`.
- The `O ponto de referência selecionado` option displays the current reference point label: the Account Profile name when the reference is an Account Profile, or `localização personalizada` when the reference was chosen manually.
- When the selected reference point is an Account Profile, the modal must include an affordance/shortcut to open that Account Profile.
- The modal includes a `Não perguntar de novo` persistence path. If checked, persist the selected option. If not checked, leave the setting `null` and keep prompting on future launches.
- In the address/reference-point selection area, expose a nullable setting for whether the selected `ponto de referência` should be used when tracing routes. `null` means show the dialog; `true` means use the selected reference point; `false` means use current live location.
- Whenever the user chooses a new `ponto de referência`, this route prompt setting must reset to `null` so the next route launch asks again.
- If the canonical/resolved route origin is already the current live location, do not show the dialog.
- If the user chooses the selected reference point, the destination app deep link must use the reference point as the route starting point/origin.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `event-directions-inline-provider-actions`
- **Why this is the right current slice:** this is one visible Event detail/directions experience update that can be validated in one approval cycle.
- **Direct-to-TODO rationale:** user supplied exact provider hierarchy, modal behavior, and persistence semantics.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Feature`, `Cross-Stack`, `Tenant-Public`, `User-Visible`, `Persistence`, `Settings`
- **Next exact step:** add fail-first tests for the frozen settings-backed route reference-point prompt policy, then request `APROVADO`.

## Scope
- [ ] Remove the `Como Chegar` tab-specific footer CTA `Traçar rota`.
- [ ] Preserve the standard event CTA/footer behavior used by other tabs.
- [ ] Render inline provider actions under the main location card: `Waze`, `Uber`, and `Outros`.
- [ ] Render inline provider actions under each secondary location card, with compact sizing.
- [ ] Make main-card provider actions visually larger than secondary-card actions.
- [ ] Route `Waze` and `Uber` buttons directly to their provider launch path without opening the chooser sheet.
- [ ] Route `Outros` through the existing directions bottom sheet/provider chooser.
- [ ] Add a route reference-point policy modal when the persisted setting is `null`.
- [ ] Modal title/question is exactly `Qual PONTO DE PARTIDA quer usar?`.
- [ ] Modal options are `Sua localização atual` and `O ponto de referência selecionado`.
- [ ] Modal displays the selected reference point label: Account Profile name for Account Profile references, or `localização personalizada` for manual references.
- [ ] Modal includes an Account Profile shortcut when the selected reference point is an Account Profile.
- [ ] Modal is skipped when the canonical/resolved route origin is the current live location.
- [ ] Choosing the selected reference point makes the external app deep link use the reference point as the route origin.
- [ ] Modal supports `Não perguntar de novo`.
- [ ] Add/update the address/reference-point selection area with a nullable setting for "use selected reference point when tracing routes".
- [ ] Persist the route reference-point prompt preference in settings so `true`/`false` stops future prompts and `null` keeps prompting.
- [ ] Reset the route reference-point prompt setting to `null` whenever a new `ponto de referência` is selected.
- [ ] Reuse existing `DirectionsAppChooser`/launch URI logic where possible; extend it only where direct provider launch requires a contract.

## Out of Scope
- [ ] Changing Event venue/programming location data model.
- [ ] Adding new provider brands beyond `Waze`, `Uber`, and existing chooser options.
- [ ] Reworking Account Profile or Static Asset detail direction UI unless a shared widget extraction is required.
- [ ] Storing this preference only in local/AppData as source of truth.
- [ ] Tenant-admin `map_ui` settings changes; this is a user settings/preference concern.

## Dependencies & Sequencing
- [ ] `DEP-01` Must preserve current Event detail confirmation/invite footer behavior.
- [ ] `DEP-02` Should coordinate with `TODO-v0.2.0+8-reference-poi-reference-point-actions.md` because choosing a new `ponto de referência` resets this prompt setting to `null`.
- [ ] `DEP-03` May consume existing `LocationOriginService` for resolved coordinates, but route prompt persistence belongs to settings, not local AppData.

## Definition of Done
- [ ] `Como Chegar` no longer shows the tab-specific `Traçar rota` footer.
- [ ] The event screen still shows the standard event CTA/footer correctly on all tabs.
- [ ] Main address card shows larger `Waze`, `Uber`, and `Outros` buttons.
- [ ] Secondary address cards show compact `Waze`, `Uber`, and `Outros` buttons.
- [ ] `Waze` launches Waze directly when available/launchable after origin policy resolution.
- [ ] `Uber` launches Uber directly when available/launchable after origin policy resolution.
- [ ] `Outros` opens the chooser bottom sheet.
- [ ] When route reference-point setting is `null`, launching a provider prompts for `ponto de referência` vs current location.
- [ ] The prompt copy and options match the frozen modal contract.
- [ ] The prompt is not shown when the canonical/resolved route origin is already the current live location.
- [ ] Account Profile references show a shortcut from the prompt to that Account Profile.
- [ ] Choosing the reference point sends the reference point as the origin/start point in Waze/Uber/other provider links.
- [ ] If `Não perguntar de novo` is checked, the selected route policy is saved in settings.
- [ ] If `Não perguntar de novo` is not checked, the setting remains `null` and future launches keep prompting.
- [ ] When the setting is `true` or `false`, no prompt is shown and the saved policy is applied.
- [ ] Choosing a new `ponto de referência` resets the route prompt setting to `null`.
- [ ] Tests cover direct provider launches, chooser launch, prompt/persistence semantics, and absence of the old footer.

## Validation Steps
- [ ] Flutter widget test for Event detail `Como Chegar` tab without old footer CTA.
- [ ] Flutter widget/controller test for main and secondary inline provider buttons.
- [ ] Flutter unit/widget test for route reference-point prompt when setting is `null`.
- [ ] Flutter widget test for exact modal copy/options and Account Profile shortcut visibility.
- [ ] Flutter test proving no modal appears when canonical/resolved route origin is current live location.
- [ ] Flutter/service test proving reference-point choice populates provider deep link origin/start point.
- [ ] Flutter repository/service test for persisted settings-backed route prompt preference `true|false|null`.
- [ ] Laravel/settings or repository test for read/write/reset semantics if settings persistence is backend-backed in the chosen implementation.
- [ ] Flutter test proving direct Waze/Uber launch paths do not open the chooser sheet.
- [ ] Analyzer/local CI-equivalent suite row completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level:** `medium`
- **Checkpoint policy:** `full Plan Review Gate before APROVADO + post-validation checkpoint`
- **Why this level:** UI/service work is Flutter-heavy, but settings-backed route prompt persistence introduces cross-stack contract and reset semantics.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/events_module.md`

## Source Inventory Snapshot
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/location_section.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/dynamic_footer.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_chooser.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_chooser_contract.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_app_choice.dart`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/directions_launch_target.dart`
- `flutter-app/lib/infrastructure/services/location_origin_service.dart`
- `flutter-app/lib/domain/app_data/location_origin_settings.dart`
- `flutter-app/lib/infrastructure/repositories/app_data_repository.dart`
- `flutter-app/lib/domain/proximity_preferences/**`
- `flutter-app/lib/infrastructure/repositories/proximity_preferences_repository.dart`
- `laravel-app/app/Application/ProximityPreferences/**`

## Decisions
- [x] `D-DIR-01` The only primary inline provider buttons are `Waze` and `Uber`.
- [x] `D-DIR-02` `Outros` uses the standard three-dots icon and opens the chooser bottom sheet.
- [x] `D-DIR-03` Waze/Uber direct buttons skip the chooser sheet.
- [x] `D-DIR-04` Route reference-point prompt is tri-state: `null` prompts, `true` uses the selected `ponto de referência`, `false` uses current live location.
- [x] `D-DIR-05` The prompt must display the current `ponto de referência` before the user chooses.
- [x] `D-DIR-06` Persisted `true`/`false` route prompt choices stop the prompt only when the user marks `Não perguntar de novo`; otherwise the setting remains `null`.
- [x] `D-DIR-07` Use `ponto de referência` terminology. Do not use `origem padrão` in new/updated visible copy for this flow.
- [x] `D-DIR-08` Persist the route prompt policy in settings, not local/AppData. Local storage may mirror/cache settings but is not the source of truth.
- [x] `D-DIR-09` Render Waze and Uber whenever the destination is launchable; direct launch should use native deep link when available and web fallback when needed. If direct launch fails, show a status message and leave `Outros` as the explicit chooser path.
- [x] `D-DIR-10` Choosing a new `ponto de referência` resets the route prompt policy setting to `null`.
- [x] `D-DIR-11` The prompt question is exactly `Qual PONTO DE PARTIDA quer usar?` with options `Sua localização atual` and `O ponto de referência selecionado`.
- [x] `D-DIR-12` The selected reference point option shows the Account Profile name for Account Profile references and `localização personalizada` for manual references.
- [x] `D-DIR-13` Account Profile reference points expose a shortcut from the prompt to the Account Profile detail.
- [x] `D-DIR-14` Skip the prompt when the canonical/resolved route origin is already the current live location.
- [x] `D-DIR-15` If the user chooses the selected reference point, provider deep links must use the reference point as the route origin/start point.

## Closed Questions
- [x] Route prompt settings owner closed by `D-DIR-08`.
- [x] Waze/Uber availability/fallback behavior closed by `D-DIR-09`.
- [x] New reference-point reset semantics closed by `D-DIR-10`.
- [x] Prompt copy/options, Account Profile shortcut, skip condition, and deep-link origin behavior closed by `D-DIR-11..15`.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing `DirectionsAppChooser` URI builders can be reused for direct provider launch with a small contract extension. | The chooser already builds Waze/Uber choices and launch handlers. | Extract provider-specific launch service before UI work. | `High` | `Keep as Assumption` |
| `A-02` | Existing resolved-location services can still supply the live/current coordinate while visible copy changes to `ponto de referência`. | `LocationOriginService` already resolves effective/live coordinates; user corrected terminology on 2026-05-25. | Additional copy and settings model work may be needed. | `High` | `Promoted to Decision` |
| `A-03` | Settings-backed route prompt policy belongs with user proximity/reference settings, not tenant-admin `map_ui`. | The setting is user behavior tied to the selected `ponto de referência`; tenant settings are tenant/admin configuration. | A broader settings namespace decision is required before implementation. | `Medium` | `Keep as Assumption` |
| `A-04` | Provider deep-link builders can support an optional origin/start point without rewriting the whole directions chooser. | Current `DirectionsLaunchTarget` already carries destination data; direct providers can extend the target/launch contract. | Extract a route request model before direct provider work. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `flutter-app/lib/presentation/shared/widgets/directions_app_chooser/**`
- `flutter-app/lib/domain/proximity_preferences/**`
- `flutter-app/lib/infrastructure/repositories/**`
- `laravel-app/app/Application/ProximityPreferences/**`
- `laravel-app/tests/**`
- `flutter-app/test/**`

### Ordered Steps
1. Add fail-first tests for removed footer, inline buttons, direct launch, chooser launch, exact prompt copy/options, prompt skip condition, Account Profile shortcut, provider origin deep links, settings-backed prompt persistence, and reset-on-new-reference semantics.
2. Extend the user settings/proximity preference contract with the tri-state route prompt policy.
3. Extract/reuse direct provider launch capability from `DirectionsAppChooser`.
4. Implement inline buttons in `LocationSection` for main and secondary cards.
5. Implement route reference-point modal, Account Profile shortcut, prompt skip condition, and persisted tri-state setting.
6. Reset the route prompt setting to `null` whenever a new `ponto de referência` is selected.
7. Verify standard event CTA/footer remains unchanged.
8. Run focused tests and analyzer.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** Event detail widget tests, directions chooser/direct provider service tests, prompt copy/shortcut tests, provider deep-link origin tests, settings/proximity preference repository tests.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Inline provider buttons show and launch | Public visible route flow | `shared-android-web` | widget/navigation | `no` | Flutter widget/service tests |
| Route reference-point modal persists choice | Public preference mutation | `shared-android-web` | widget/repository + Laravel/settings test if backend-backed | `yes` | Flutter widget + repository tests |
| New reference point resets route prompt | Public preference mutation | `shared-android-web` | repository/controller | `yes` | settings/proximity repository test |
| Old footer removed without breaking event CTA | Public Event detail flow | `shared-android-web` | widget/navigation | `no` | Event detail widget test |
| Reference point origin is sent to provider | External handoff flow | `shared-android-web` | service/widget | `no` | provider deep-link origin test |
| Account Profile shortcut appears in prompt | Public navigation | `shared-android-web` | widget/navigation | `no` | prompt widget/navigation test |

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Visible Action | DTO/Decoder Path | Planned Evidence |
| --- | --- | --- | --- | --- |
| Route prompt settings | Flutter Event detail directions + reference point selection | provider launch reference-point policy | settings/proximity preference DTO/repository | repository + widget tests |
| Directions provider launch service | Flutter Event detail directions | Waze/Uber direct and Outros sheet | shared directions widget/service | service + widget tests |
| Reference point source metadata | Flutter Event detail directions prompt | prompt label + Account Profile shortcut + provider origin | proximity preference fixed reference DTO/repository | DTO + widget/service tests |

## Local CI-Equivalent Suite Matrix
| Repo | CI Surface | Local Command | Required Before Delivery |
| --- | --- | --- | --- |
| `flutter-app` | analyzer + focused tests | `fvm dart analyze --format machine` and focused `fvm flutter test ...` | `yes` |
| `laravel-app` | settings/proximity preference tests | project safe Laravel test runner for settings/proximity preference tests if backend-backed | `yes if backend persistence is touched` |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, Event/Agenda/Flutter module anchors, source inventory snapshot, frozen decisions `D-DIR-01..15`, frontend/consumer matrix, flow evidence matrix, and local CI-equivalent suite matrix.
- **Orchestrator-owned checks:** verify direct Waze/Uber actions never open the chooser, `Outros` always does, prompt copy/options are exact, Account Profile shortcut exists for Account Profile references, prompt is skipped when current location is already the canonical origin, route prompt persistence is settings-backed, reference-point choice populates provider origin/start point, and choosing a new `ponto de referência` resets the setting to `null`.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `DIR` inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; execution depends on `REF-CORE` and must keep route prompt persistence settings-backed rather than local/AppData-only.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This TODO is now approved for tactical implementation after dependency gates. | Approved scope, exact modal copy, DoD, and validation. | Changing provider hierarchy or settings owner without renewed approval. | Worker must preserve frozen decisions. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO shares a wave with reference-point actions. | Worker-owned implementation and orchestrator-owned reconciliation. | Conflicting reset or preference models between workers. | Orchestrator merges only one shared policy path. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches Event detail UI, shared directions widgets, services, and repositories. | Controller/service ownership and analyzer-clean state. | Widget-local persistence or ad hoc navigation. | Worker must keep state in repository/settings contracts. |
| `delphi-ai/rules/stacks/flutter/flutter-route-workflow-glob.md` | The prompt may navigate to Account Profile detail. | Canonical route/navigation discipline. | Raw Navigator shortcuts that bypass route policy. | Worker must use approved route/navigation patterns. |
| `delphi-ai/rules/stacks/laravel/shared/settings-kernel-patch-contract-model-decision.md` | Backend settings/proximity persistence may be touched. | User preference ownership outside tenant-admin `map_ui`. | Treating this as tenant configuration. | Worker must test read/write/reset if backend is touched. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | Direct provider launch and tri-state prompt behavior need focused tests. | Exact-copy and semantic service tests. | Status-only UI tests. | Worker creates widget, service, and repository tests. |

## Completion Evidence Matrix
| Criterion | Evidence | Status | Notes |
| --- | --- | --- | --- |
| DoD + validation rows | `pending` | `planned` | Fill before any delivery claim. |
