# TODO (V1): Home Agenda Controller-Boundary Plugin Rules

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Validated`
**Qualifiers:** `Docs-Frozen`, `Rule-Matrix-Green`, `Analyzer-Green`
**Next exact step:** Close the slice or promote any follow-up debt as a separate TODO; implementation and local validation are complete.
**Owners:** Flutter Team + Platform Governance
**Objective:** Convert the newly frozen Home/Agenda controller-boundary rules into executable analyzer enforcement by implementing the first three plugin rules: forbid controller-to-controller dependencies, forbid screens/parents from resolving descendant widget controllers, and forbid widget-controller singleton registration leakage.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one architecture checkpoint before final validation

---

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** The work is one bounded static-governance slice with a single value objective: turn already approved boundary decisions into executable plugin diagnostics.
- **Direct-to-TODO rationale:** The rule set, canonical decisions, touched plugin surfaces, and expected validation gates are already frozen. No additional feature framing is needed.

## Contract Boundary

- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: local rule-calibration work and fixture updates may stay inside this TODO while they remain within the same enforcement objective.
- If execution reveals a new app-refactor slice, a new policy family beyond these three rules, or any need to change module contracts, split or update the TODO before continuing.

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/agenda_and_action_planner_module.md`

## References

- `foundation_documentation/todos/active/vnext/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md`
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-events-radius-button-behavior.md`
- `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md`
- `flutter-app/tool/belluga_analysis_plugin/lib/main.dart`
- `flutter-app/tool/belluga_analysis_plugin/docs/rules.md`
- `flutter-app/tool/belluga_analysis_plugin/test_fixtures/lint_matrix/**`

## Scope

- [x] Implement `controller_controller_dependency_forbidden` in `belluga_analysis_plugin`.
- [x] Implement `screen_descendant_widget_controller_resolution_forbidden` in `belluga_analysis_plugin`.
- [x] Implement `widget_controller_singleton_registration_forbidden` in `belluga_analysis_plugin`.
- [x] Register the new rules in the plugin entrypoint and document them as enforced rules.
- [x] Extend lint-matrix fixtures so each new rule has positive and negative coverage.
- [x] Run the official analyzer gate and rule-matrix validation after calibration.

## Out of Scope

- [ ] Refactor the Home/Agenda runtime structure itself.
- [ ] Introduce repository-owned shared-settings enforcement in this slice.
- [ ] Introduce scroll-source enforcement in this slice.
- [ ] Change app behavior solely to satisfy new findings unless the finding is a trivial local calibration issue in the fixture package.

## Bounded But Elastic Guardrails

- **May stay inside this TODO:** helper refactors in plugin utility files, fixture-path additions, rule docs sync, and calibration changes required to keep false positives aligned with the frozen boundary decisions.
- **Must update or split the TODO:** any runtime application refactor required by real findings, any new rule outside the three approved IDs, or any module-contract change beyond the already promoted canonical decisions.

## Definition of Done

- [x] The three new rule IDs are implemented and registered in `belluga_analysis_plugin`.
- [x] Each rule has fixture coverage that proves at least one violation path and one non-violation path.
- [x] `tool/belluga_analysis_plugin/docs/rules.md` is updated so the rules move from pending candidates to enforced documented rules.
- [x] `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` passes with the new rule IDs included.
- [x] `fvm dart analyze --format machine` stays clean at the repository root after the new rules are introduced.
- [x] Any new findings in the real app are classified honestly as valid debt or false positive requiring immediate rule calibration.

## Validation Steps

- [x] `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`
- [x] `fvm dart analyze --format machine`

## External Dependency Readiness

| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| local analyzer/plugin toolchain | Required for real static-governance validation | `healthy` | `2026-04-15` | `fvm dart analyze --format machine` + `validate_rule_matrix.sh` | if analyzer drift appears, use `bash ./scripts/reset_analyzer_state.sh` and rerun |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `n/a` | `n/a` | n/a | n/a | `n/a` |

## Canonical Module Anchors (Required Before `APROVADO`)

- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Planned decision promotion targets:**
  - `flutter-app/tool/belluga_analysis_plugin/docs/rules.md`
  - `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md` only if the candidate inventory needs closure notes after implementation
- **Module decision consolidation targets:**
  - `FCX-08`
  - `FCX-09`
  - `FCX-10`
  - `AGD-10`

## Decision Pending (Resolve Before Freeze)

- [ ] none

## Module Decision Baseline Snapshot

| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `FCX-08` | Widget controllers are subtree-private; controller relay upward is forbidden. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` (`7. Canonical Decision Baseline`) |
| `FCX-09` | Shared/persisted UI settings are repository-owned streams, never controller-relayed. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` (`7. Canonical Decision Baseline`) |
| `FCX-10` | Scroll-reactive UI must bind to the real scroll source; borrowed UI controllers remain caller-owned. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` (`7. Canonical Decision Baseline`) |
| `AGD-10` | Home Agenda scroll-driven chrome stays widget-subtree-local while shared radius preference remains repository-owned. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` (`7. Canonical Decision Baseline`) |

## Decision Baseline (Frozen Before Implementation)

- [ ] `D-01` This slice implements only static analyzer enforcement for already approved controller-boundary decisions; it must not change product behavior directly.
- [ ] `D-02` `controller_controller_dependency_forbidden` is the first blocking ownership rule in this slice because controller-to-controller coupling is categorically forbidden by the frozen boundary contract.
- [ ] `D-03` `screen_descendant_widget_controller_resolution_forbidden` must target upward leakage from screens/parents into descendant widget-controller boundaries without reopening the existing same-feature screen-controller resolution rule.
- [ ] `D-04` `widget_controller_singleton_registration_forbidden` must flag module/global singleton registration patterns that leak widget controllers above the widget subtree while preserving factory/widget-scoped registration.
- [ ] `D-05` Each rule must ship with lint-matrix positive and negative fixture coverage before it is considered deliverable.
- [ ] `D-06` Any real-app findings created by these rules must be treated as valid debt unless evidence proves a false positive that should be calibrated immediately.

## Questions To Close

- [ ] none

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing plugin helper utilities plus AST/type resolution are sufficient to implement these three rules without new deterministic tooling. | `tool/belluga_analysis_plugin/lib/src/path_utils.dart`, `getit_utils.dart`, `type_utils.dart`, nearby rule implementations | We may need to add shared helper utilities inside the plugin package. | `High` | `Keep as Assumption` |
| `A-02` | The lint-matrix fixture package is the correct place to prove these new rule IDs instead of building dedicated unit tests first. | `tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` and existing fixture strategy | We would need an additional plugin test harness, increasing scope. | `High` | `Keep as Assumption` |
| `A-03` | The real app may currently contain zero or more violations; the contract only requires honest classification after analyzer execution, not full debt burn-down in this slice. | User request is to implement rules, not refactor the app in the same slice | The TODO would need to expand into app remediation, requiring renewed approval. | `High` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces

- `flutter-app/tool/belluga_analysis_plugin/lib/main.dart`
- `flutter-app/tool/belluga_analysis_plugin/lib/src/path_utils.dart` only if shared helper support is needed
- `flutter-app/tool/belluga_analysis_plugin/lib/src/getit_utils.dart` only if shared helper support is needed
- `flutter-app/tool/belluga_analysis_plugin/lib/src/type_utils.dart` only if shared helper support is needed
- `flutter-app/tool/belluga_analysis_plugin/lib/src/rules/*.dart` for the three new rules
- `flutter-app/tool/belluga_analysis_plugin/docs/rules.md`
- `flutter-app/tool/belluga_analysis_plugin/test_fixtures/lint_matrix/**`

### Ordered Steps

1. Add fail-first fixture expectations for the three new rule IDs in the lint matrix.
2. Implement the three rule classes using the closest existing rule patterns and any minimal shared helper support needed.
3. Register the rules in `lib/main.dart`.
4. Update `docs/rules.md` so the rules move from candidate status to enforced-rule documentation.
5. Run rule-matrix validation, then run the official repository analyzer gate and classify any new real-app findings.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** This is deterministic static-governance work; fixture coverage is the executable contract for the new diagnostics.
- **Fail-first target(s):**
  - missing lint IDs in the matrix before rule implementation
  - at least one positive/negative fixture pair per new rule

### Runtime / Rollout Notes

- No runtime migrations.
- No backend coupling.
- The primary rollout risk is false positives or overlap with existing controller/UI rules.

## Plan Review Gate

### Issue Card `RULE-01` — overlap with existing resolution rules

- **Severity:** `medium`
- **Why it matters now:** `screen_descendant_widget_controller_resolution_forbidden` can accidentally duplicate `ui_cross_feature_controller_resolution_forbidden` or `ui_getit_non_controller_forbidden` if the scope boundary is not precise.
- **Option A (Recommended):** scope the new rule to parent/screen resolution of descendant widget controllers within the same feature tree, using source-path ancestry rather than generic controller naming alone.
  - **Effort:** `medium`
  - **Risk:** `low`
  - **Blast radius:** `plugin-local`
  - **Maintenance burden:** `low`
- **Option B:** make the rule naming-only and broad.
  - **Effort:** `low`
  - **Risk:** `high`
  - **Blast radius:** `repo-wide`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Issue Card `RULE-02` — singleton-registration false positives

- **Severity:** `medium`
- **Why it matters now:** `widget_controller_singleton_registration_forbidden` can incorrectly flag legitimate screen/module controllers if it does not distinguish widget-controller intent from regular feature-controller registration.
- **Option A (Recommended):** limit the rule to controller types whose source path lives under a widget subtree and are registered through global/module singleton APIs.
  - **Effort:** `medium`
  - **Risk:** `low`
  - **Blast radius:** `plugin-local`
  - **Maintenance burden:** `medium`
- **Option B:** flag every singleton registration of any `*Controller`.
  - **Effort:** `low`
  - **Risk:** `high`
  - **Blast radius:** `repo-wide`
  - **Maintenance burden:** `high`
- **Recommendation:** `Option A`

### Failure Modes & Edge Cases

- [ ] The descendant-widget rule flags normal screen-controller resolution.
- [ ] The controller-dependency rule misses constructor fields but flags harmless local variable names.
- [ ] The singleton rule misses `GetIt.instance` variants or async singleton APIs.
- [ ] The fixture matrix proves the rules, but the real app emits no findings because the source-path heuristics are too narrow.

### Residual Unknowns / Risks

- [ ] The current app may have legacy registrations or controller dependencies outside the Home/Agenda lane that surface once the rules go live.

## Additional Architectural Opinions

- **Needed:** `no`
- **Why ambiguity remains or does not remain:** The governing decisions are already frozen in canonical docs; the remaining work is rule calibration, not architectural choice discovery.

## Independent No-Context Critique Gate

- **Critique decision:** `not_needed`
- **Why this decision:** This is a bounded plugin-enforcement slice with existing canonical decisions already frozen; local fixture coverage and analyzer execution provide the main objective validation.

## Approval Rule

Implementation must not begin until the user replies with the explicit token: **APROVADO**.

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)

| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `flutter-architecture-adherence` | Flutter architecture governance is the subject of the rule implementation | canonical controller/widget/repository ownership | codifying workarounds that contradict promoted module decisions | load before plugin edits |
| `rule-docker-shared-todo-driven-execution-model-decision` | this is implementation work touching project artifacts | explicit tactical TODO + approval gate | coding before approval | load before any project change outside TODOs |
| `wf-docker-todo-driven-execution-method` | this TODO is the execution authority for the rule slice | bounded execution against frozen decisions | absorbing app refactors into the same TODO | use through delivery |
| `test-creation-standard` | lint-matrix coverage is the executable contract for the new rule IDs | fail-first fixture coverage and honest gate reporting | shipping unproven diagnostics | load before fixture/rule implementation |
