# TODO (Post Release Hardening): Flutter Repository Transport-Boundary Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The architectural drift review confirmed that some production Flutter repositories still parse raw transport maps after DAO/backend calls instead of consuming typed DAO/DTO decoder outputs.

This is not a cosmetic layering preference. It weakens the existing repository/DAO guardrails and allows transport-shape drift to hide inside repository logic.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-07`
- **Why this is the right current slice:** this TODO stays tightly on the repository/DAO transport boundary and does not absorb the broader RAW->DTO parse inventory or Flutter domain-topology normalization lanes.

## Contract Boundary
- This TODO owns production repository paths that still parse raw response maps after DAO/backend calls.
- It owns moving those parse responsibilities to typed DAO/DTO decoder boundaries.
- It owns regression tests and any supporting static-guard follow-up needed for those exact paths.
- It does **not** own the whole parser inventory tracked by `TODO-vnext-raw-dto-domain-parse-hardening.md`.

## Drift Guardrail Requirement
- This TODO belongs to the repository boundary / typed-transport drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real repository drift fixtures from current production code.

## Violated Canonical Rule
- Production repositories must not parse raw transport maps after DAO/backend boundaries; raw transport parsing belongs to DAO/DTO decoder ingress.

## Replacement Canonical Rule
- Repositories consume typed outputs from DAO/DTO boundaries only.
- Any required transport-shape interpretation after the backend call must live in decoder/mapper infrastructure, not repository business flow code.

## Strongest Objective PACED Guardrail
- Focused Flutter tests on the touched repository/DAO paths.
- Reuse and, if needed, extend the existing repository/DAO boundary lint/architecture guard.
- Use the confirmed repository drift files as fixture cases for follow-up analyzer coverage.

## Real Drift Fixtures
- `flutter-app/lib/infrastructure/repositories/invites_repository.dart`
- `flutter-app/lib/infrastructure/repositories/user_events_repository.dart`
- Drift-review finding `ARCH-DRIFT-014`

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Flutter`, `Architecture`, `Transport-Boundary`
- **Next exact step:** freeze the bounded repository inventory and define fail-first tests around the current raw-map parsing paths.

## Scope
- [ ] Inventory and bound the production repositories covered by this lane.
- [ ] Move raw response parsing out of those repositories into decoder/DAO infrastructure.
- [ ] Add regression tests using the current repositories as fixtures.
- [ ] Feed the resulting fixture set into the later analyzer-rule expansion TODO.

## Out of Scope
- [ ] Broad RAW->DTO parse inventory across every Flutter mapper.
- [ ] Flutter domain purity/value-object cleanup outside repository transport boundaries.
- [ ] Query-path changes already owned by `TODO-v1-query-path-guardrails-hardening.md` unless the exact touched repository overlap requires coordinated updates.

## Definition of Done
- [ ] In-scope production repositories no longer parse raw transport maps directly after DAO/backend calls.
- [ ] Touched paths are covered by focused regression tests.
- [ ] Any required static-guard follow-up is recorded for the analyzer-expansion TODO.

## Validation Steps
- [ ] Add fail-first Flutter tests for the current repository drift fixtures.
- [ ] Run targeted Flutter tests for touched repository/decoder paths.
- [ ] Run `fvm dart analyze --format machine` on the final implementation state.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the touched files should be tightly bounded, but the lane must preserve canonical repository/DAO architecture and avoid smuggling broader parse work into the slice.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` repository/DAO boundary rules
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`

## Dependencies & Sequencing
- [ ] Coordinate overlap with `TODO-v1-query-path-guardrails-hardening.md` where `user_events_repository.dart` is both a query-path and transport-boundary offender.
- [ ] Feed confirmed fixtures forward into `TODO-post-release-analyzer-rule-coverage-expansion-for-drift-fixtures.md`.
