# TODO (Post Release Hardening): Analyzer Rule Coverage Expansion for Drift Fixtures

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The drift review confirmed that the current analyzer/custom-lint surfaces are clean while still missing real production-code rule violations:

- repository raw-transport parsing after DAO/backend boundaries;
- domain/service-locator access;
- dynamic payload-shaped value objects.

This TODO converts those confirmed runtime drifts into objective static-guard coverage after the runtime remediation TODOs have frozen the correct replacement architecture.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-09`
- **Why this is the right current slice:** it is a guardrail-only follow-through lane. It must not begin by guessing the target rule from scratch; it consumes the corrected runtime boundaries and their fixture files.

## Contract Boundary
- This TODO owns analyzer/custom-lint/static-rule follow-through for the first-wave Flutter architecture drifts.
- It owns fixture coverage and deterministic validation for those exact rule families.
- It does **not** own the runtime repository/domain fixes themselves.

## Drift Guardrail Requirement
- This TODO is itself the PACED guardrail-hardening lane.
- Before implementation is approval-clean, execution must freeze:
  - the violated rule families being codified,
  - the replacement canonical rules already proven by the runtime remediation lanes,
  - the strongest objective guardrail shape that can enforce them with acceptable false-positive risk,
  - and the real fixture files taken from the drift families this TODO protects.

## Violated Canonical Rule
- Static architecture enforcement must cover confirmed high-signal repository/domain purity drifts rather than reporting clean while those exact classes survive in production code.

## Replacement Canonical Rule
- Once a runtime drift family has an approved corrected boundary, the analyzer/custom-lint suite must enforce that family mechanically where false-positive risk is acceptable and the rule can be expressed objectively.

## Strongest Objective PACED Guardrail
- `tool/belluga_analysis_plugin` rule additions or deterministic validation scripts with fixture matrix coverage.
- `validate_rule_matrix.sh` fixture coverage updated with the real drift files or equivalent minimized reproductions derived from them.

## Real Drift Fixtures
- Output fixtures from:
  - `TODO-post-release-flutter-repository-transport-boundary-hardening.md`
  - `TODO-post-release-flutter-domain-purity-and-typed-value-object-hardening.md`
- Original source drifts:
  - `flutter-app/lib/infrastructure/repositories/invites_repository.dart`
  - `flutter-app/lib/infrastructure/repositories/user_events_repository.dart`
  - `flutter-app/lib/domain/tenant/tenant.dart`
  - the current dynamic value-object wrappers listed in the drift review

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Flutter`, `Analyzer`, `Guardrails`, `Depends-On-First-Wave`
- **Next exact step:** wait for the repository/domain runtime lanes to freeze their replacement boundaries, then extract fixture cases and choose the objective static-rule shapes.

## Scope
- [ ] Convert first-wave runtime drift families into objective static-rule candidates.
- [ ] Add or extend analyzer/deterministic coverage where false-positive risk is acceptable.
- [ ] Add fixture matrix coverage using the real confirmed drift families.
- [ ] Document any drift classes that remain judgment-heavy and therefore should stay outside static enforcement.

## Out of Scope
- [ ] Runtime repository/domain remediation itself.
- [ ] Broad analyzer expansion unrelated to the confirmed first-wave drift families.
- [ ] Faux-deterministic rules for judgment-heavy architecture topics without objective signal.

## Definition of Done
- [ ] At least one confirmed first-wave drift family is enforced mechanically with fixture coverage.
- [ ] Every non-enforced first-wave drift family has an explicit rationale for why it remains outside static enforcement.
- [ ] `validate_rule_matrix.sh` or equivalent deterministic surface covers the new fixtures.

## Validation Steps
- [ ] Use the corrected runtime drifts as fixture inputs.
- [ ] Run `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`.
- [ ] Run `fvm dart analyze --format machine` on the final analyzer state.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the guardrail scope is bounded, but it depends on earlier runtime lanes and must avoid high false-positive enforcement.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `tool/belluga_analysis_plugin/docs/rules.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` executable architecture guardrails
  - `tool/belluga_analysis_plugin/docs/rules.md`
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`

## Dependencies & Sequencing
- [ ] `DEP-01` `TODO-post-release-flutter-repository-transport-boundary-hardening.md` must freeze the corrected repository boundary first.
- [ ] `DEP-02` `TODO-post-release-flutter-domain-purity-and-typed-value-object-hardening.md` must freeze the corrected domain/value-object boundary first.
