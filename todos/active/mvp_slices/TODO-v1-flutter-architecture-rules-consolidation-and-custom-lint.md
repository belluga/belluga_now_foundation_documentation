# TODO (V1): Flutter Architecture Rules Consolidation + Custom Lint Enforcement

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Flutter Team + Platform Governance  
**Objective:** Consolidate the Flutter architecture rules into a single, non-contradictory contract and enforce it with `custom_lint` so architectural violations are detected automatically in local dev and CI.

---

## References
- `foundation_documentation/system_roadmap.md` (FCX-06 hard-NO cleanup)
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/rules/docker/flutter-architecture.md`
- `flutter-app/analysis_options.yaml`
- `flutter-app/pubspec.yaml`

---

## Scope
- Define a canonical Flutter DI/state contract for screen/controller/widget boundaries.
- Remove conflicting rule language between skills/rules/docs.
- Introduce `custom_lint`-based architecture checks aligned with the canonical contract.
- Integrate lint execution into Flutter local workflow and CI.

## Out of Scope
- New feature delivery in tenant/admin/public modules.
- Broad state-management migration beyond contract enforcement needs.
- Replacing `GetIt`/`ModuleScope` architecture in V1.

---

## Canonical Contract to Freeze
- [ ] ⚪ **Screen ownership rule:** Each screen resolves its own feature controller via `GetIt`, and this controller instance must be registered by the active `ModuleScope`.
- [ ] ⚪ **Scope-lifetime rule:** Controller is a module-scoped singleton (single instance per scope, disposed with scope teardown), not an app-global singleton.
- [ ] ⚪ **Auxiliary widget rule:** Widgets under the same screen flow may resolve the same scoped controller via `GetIt` when they consume controller-managed state.
- [ ] ⚪ **UI boundary rule:** `screens/widgets` may resolve controllers only; direct `GetIt` resolution of repository/service/DAO/infra types is forbidden.
- [ ] ⚪ **Cross-feature rule:** `screens/widgets` must not resolve controllers from another feature/screen scope.
- [ ] ⚪ **Lifecycle rule:** `ModuleScope` owns controller lifecycle; widgets/screens must not dispose module-scoped controllers.

---

## Candidate Rule Inventory (Custom Lint Backlog)

### P0 (First blocking set)
- [ ] ⚪ `ui_getit_non_controller_forbidden` - In `screens/widgets`, allow `GetIt` resolution for `*Controller` only.
- [ ] ⚪ `ui_direct_repository_service_resolution_forbidden` - Block repository/service/DAO/infra resolution in UI files.
- [ ] ⚪ `ui_cross_feature_controller_resolution_forbidden` - Block UI resolving controllers outside its feature/screen scope.
- [ ] ⚪ `module_scoped_controller_dispose_forbidden` - Block `dispose()`/`onDispose()` calls over module-scoped controllers in UI.
- [ ] ⚪ `ui_streamvalue_ownership_forbidden` - Block `StreamValue`/`StreamController` ownership in `screens/widgets`.
- [ ] ⚪ `ui_dto_import_forbidden` - Block DTO import/usage in presentation layer.
- [ ] ⚪ `domain_dto_dependency_forbidden` - Block DTO dependencies in `lib/domain/**`.

### P1 (Second set, advisory first)
- [ ] ⚪ `ui_navigation_after_await_forbidden` - Flag navigation in UI after async gaps.
- [ ] ⚪ `controller_direct_navigation_forbidden` - Controllers must not call router/navigation directly.
- [ ] ⚪ `ui_navigator_usage_forbidden` - Block direct `Navigator.*` usage where router policy applies.
- [ ] ⚪ `ui_build_side_effects_forbidden` - Block side effects in `build`/`didChangeDependencies`.
- [ ] ⚪ `ui_future_stream_builder_forbidden` - Flag `FutureBuilder/StreamBuilder` where controller `StreamValue` is required.
- [ ] ⚪ `ui_controller_ownership_forbidden` - Block UI ownership of `TextEditingController`, `ScrollController`, `AnimationController`, and `GlobalKey<FormState>`.

### P2 (Adoption hygiene and consistency)
- [ ] ⚪ `screen_controller_resolution_pattern_required` - Require canonical screen controller resolution pattern.
- [ ] ⚪ `multi_widget_file_warning` - Warn when a presentation file contains more than one widget class.
- [ ] ⚪ `controller_buildcontext_dependency_forbidden` - Block `BuildContext` dependency in controller API/signature.

---

## Workstreams

### A) Documentation/Rules Consolidation
- [ ] ⚪ Update rule/skill files to remove contradictory guidance about `GetIt` usage in widgets/screens.
- [ ] ⚪ Align examples to canonical contract (screen + auxiliary widget patterns).
- [ ] ⚪ Record explicit "allowed vs forbidden" DI matrix for presentation layer.
- [ ] ⚪ Sync any affected foundation docs that reference Flutter architecture constraints.

### B) Lint Rule Specification (Custom Lint)
- [ ] ⚪ Define lint IDs, severity, and error messages for each contract rule.
- [ ] ⚪ Define path-based applicability (`presentation/**/screens/**`, `presentation/**/widgets/**`, `presentation/**/controllers/**`).
- [ ] ⚪ Define allowlist patterns for test-only injection cases.
- [ ] ⚪ Define migration handling for legacy files (advisory phase + debt burn-down list).
- [ ] ⚪ Freeze severity policy by phase:
  - [ ] ⚪ P0 starts as `error` once baseline cleanup is complete.
  - [ ] ⚪ P1 starts as `warning`, then upgrades to `error` after agreed burn-down.
  - [ ] ⚪ P2 remains `warning` unless roadmap promotes specific checks.

### C) Implementation (Custom Lint Package)
- [ ] ⚪ Add `custom_lint` to Flutter tooling and wire local command.
- [ ] ⚪ Create architecture lint package/plugin and register rules.
- [ ] ⚪ Implement P0 rule set (blocking target).
- [ ] ⚪ Implement P1 rule set (advisory target).
- [ ] ⚪ Implement P2 rule set (consistency target).
- [ ] ⚪ Add rule docs with violation/fix examples.

### D) CI Adoption Strategy
- [ ] ⚪ Add `custom_lint` execution to Flutter CI as advisory (`continue-on-error`) for initial adoption window.
- [ ] ⚪ Burn down existing violations to zero for targeted rules.
- [ ] ⚪ Flip P0 `custom_lint` gate to blocking on `next-version -> dev`.
- [ ] ⚪ Promote selected P1 rules from warning to blocking after cleanup.
- [ ] ⚪ Keep promotion lanes (`dev -> stage`, `stage -> main`) running blocking custom-lint gate.

### E) Verification
- [ ] ⚪ Run `fvm flutter analyze` clean.
- [ ] ⚪ Run `fvm dart run custom_lint` clean for enforced rules.
- [ ] ⚪ Validate CI fails on intentional architecture-rule violation.

---

## Suggested Execution Order
1. Freeze canonical contract and candidate inventory (this TODO + docs/rules alignment).  
2. Implement P0 rules and run advisory for one stabilization window.  
3. Burn down P0 findings and make P0 blocking in CI.  
4. Implement/promote P1 rules (warning to blocking by decision).  
5. Keep P2 as consistency guardrail and promote selectively when useful.

---

## Definition of Done
- [ ] ⚪ Canonical DI/state contract is documented without contradictions across Delphi rules/skills.
- [ ] ⚪ Candidate lint inventory is prioritized (P0/P1/P2) with explicit severity policy.
- [ ] ⚪ `custom_lint` rules exist for agreed architecture constraints and are documented.
- [ ] ⚪ Flutter CI executes architecture custom lint and blocks merges for P0 enforced rules.
- [ ] ⚪ Local developer workflow includes custom lint command and guidance.
- [ ] ⚪ Existing DI/layer violations for P0 enforced rules are reduced to zero.
