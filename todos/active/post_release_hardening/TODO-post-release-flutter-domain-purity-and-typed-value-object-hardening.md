# TODO (Post Release Hardening): Flutter Domain Purity and Typed Value-Object Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The architectural drift review confirmed two current production-code purity drifts in the Flutter domain layer:

- a domain model resolves runtime state through `GetIt`;
- several value objects wrap dynamic map/payload shapes in ways that hide transport semantics inside the domain layer.

This is narrower than the broad domain-topology normalization backlog. It is a concrete architecture-hardening lane for currently confirmed production drift.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-08`
- **Why this is the right current slice:** this TODO removes specific production-code purity violations without absorbing the whole `lib/domain/**` classification/normalization program.

## Contract Boundary
- This TODO owns service-locator removal from the in-scope domain model paths.
- It owns replacing dynamic payload wrappers with typed value objects or explicitly bounded opaque contracts where justified.
- It owns regression tests and future analyzer-fixture feed for the exact confirmed drift files.
- It does **not** own the broader domain-topology taxonomy and migration plan tracked by `TODO-vnext-flutter-domain-topology-normalization.md`.

## Drift Guardrail Requirement
- This TODO belongs to the domain purity / typed value-object drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real drift fixtures from the current domain layer.

## Violated Canonical Rule
- The Flutter domain layer must not depend on global service locator state or hide transport payload semantics inside dynamic map wrappers that behave like silent DTOs.

## Replacement Canonical Rule
- Domain behavior receives required app/runtime context through explicit typed boundaries outside the domain layer.
- Value objects are typed and semantically bounded; payload-shaped transport wrappers stay out of the domain layer unless they are explicitly justified as opaque contracts and documented as such.

## Strongest Objective PACED Guardrail
- Focused Flutter unit tests on the touched domain/value-object paths.
- Follow-up analyzer/custom-lint coverage using the exact drift files as fixtures.
- Source review and contract promotion to the Flutter module doc.

## Real Drift Fixtures
- `flutter-app/lib/domain/tenant/tenant.dart`
- `flutter-app/lib/domain/app_data/value_object/push_throttles_value.dart`
- `flutter-app/lib/domain/tenant_admin/value_objects/tenant_admin_dynamic_map_value.dart`
- `flutter-app/lib/domain/schedule/value_objects/event_friend_resume_payload_value.dart`
- `flutter-app/lib/domain/schedule/value_objects/sent_invite_status_payload_value.dart`
- Drift-review findings `ARCH-DRIFT-007` and `ARCH-DRIFT-012`

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Flutter`, `Architecture`, `Domain-Purity`
- **Next exact step:** freeze the exact in-scope drift inventory and define fail-first tests for service-locator usage and payload-wrapper semantics.

## Scope
- [ ] Remove service-locator access from the bounded in-scope domain paths.
- [ ] Replace or explicitly bound the current dynamic payload wrappers identified in the drift review.
- [ ] Add regression coverage using the current drift files as fixtures.
- [ ] Promote any stable replacement rules into the Flutter canonical module doc.

## Out of Scope
- [ ] Full `lib/domain/**` topology normalization.
- [ ] Broad parser inventory work outside the touched value-object/domain paths.
- [ ] Route/back-governance or repository transport-boundary work outside the direct overlap.

## Definition of Done
- [ ] In-scope domain paths no longer depend on `GetIt` or equivalent global runtime state.
- [ ] In-scope value objects no longer hide transport-payload semantics behind dynamic maps without explicit bounded justification.
- [ ] Real drift fixtures are covered by focused tests and documented for later analyzer-rule expansion.

## Validation Steps
- [ ] Add fail-first Flutter tests for the current domain drift fixtures.
- [ ] Run targeted Flutter unit tests for touched domain/value-object paths.
- [ ] Run `fvm dart analyze --format machine` on the final implementation state.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the drift inventory is currently bounded, but the lane must preserve architectural clarity and avoid collapsing into a broad domain-topology rewrite.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/domain_entities.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` domain/value-object boundaries
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`

## Dependencies & Sequencing
- [ ] Coordinate any overlapping static-rule needs with `TODO-post-release-analyzer-rule-coverage-expansion-for-drift-fixtures.md`.
- [ ] Keep broader semantic-topology cleanup in `TODO-vnext-flutter-domain-topology-normalization.md`; do not widen this lane into a full domain tree migration.
