# TODO (V1): Repository-DAO Boundary Lint Enforcement

**Status:** Completed (`Production‑Ready`)  
**Owners:** Flutter Team  
**Objective:** Enforce the architecture contract where repositories consume typed DTOs/services and return domain/projections, while raw payload/maps/dynamic parsing is confined to DAO adapters.

## Scope
1. Add a new custom lint rule for repository files to flag raw transport typing usage (`Map<String, dynamic>`, `dynamic`) at repository boundaries.
2. Keep `repository_json_parsing_forbidden` as-is and complement it with this stronger typing/boundary rule.
3. Update lint documentation (`tool/belluga_custom_lint/docs/rules.md`) with rule intent, examples, and treatment.
4. Run `fvm dart run custom_lint` and record evidence that violations are now surfaced.
5. Remediate at least the repositories currently in active implementation scope for image flows:
   - `lib/infrastructure/repositories/tenant_admin/tenant_admin_static_assets_repository.dart`
   - `lib/infrastructure/repositories/tenant_admin/tenant_admin_account_profiles_repository.dart` (if touched by this slice)

## Out of Scope
- Full repository migration across the entire codebase in one pass.
- Backend/Laravel API contract changes.
- UI/controller behavior changes unrelated to repository transport boundaries.

## Definition of Done
- New lint rule implemented and registered in plugin.
- Lint docs updated with canonical rule catalog entry and fix guidance.
- `custom_lint` output includes repository boundary violations (no longer silent).
- In-scope repository image-flow paths no longer rely on raw `Map<String, dynamic>`/`dynamic` parsing in repository layer.
- Targeted tests/analyze/lint pass for touched files.

## Validation Steps
- `fvm dart run custom_lint`
- `fvm flutter analyze`
- `fvm flutter test test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
- Additional targeted tests for touched repositories as needed.

## Complexity Classification
- **Complexity:** `medium`
- **Checkpoint Policy:** One planning checkpoint before approval, then implementation with validation checkpoint.

## Applicable Rule/Workflow Sources
- `.agent/rules/shared/todo-driven-execution-model-decision.md`
- `.agent/rules/flutter-repository-workflow-glob.md`
- `.agent/rules/flutter-documentation-contracts-always-on.md`
- `.agent/workflows/create-repository-method.md`
- `tool/belluga_custom_lint/docs/rules.md` (executable lint contract)

## Canonical Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/submodule_flutter-app_summary.md`
- **Related Tactical Stream:** `foundation_documentation/todos/completed/TODO-v1-backend-wiring-consolidation.md`

## Decision Consolidation Targets
- `foundation_documentation/modules/flutter_client_experience_module.md`:
  - Section `2.1.1 Presentation DI Matrix (Canonical)` executable guardrails
  - Section `7. Canonical Decision Baseline`
- `foundation_documentation/submodule_flutter-app_summary.md` notes section

## Module Decision Baseline Snapshot
- `FCX-03`: Flutter consumes backend contracts via repositories/adapters; no transport leakage into UI/domain.
- `FCX-04`: Transport-agnostic boundaries are mandatory across reusable flows.
- Section `2.1.1` guardrail: repositories/services cannot parse raw JSON or hydrate DTOs inline; DAO is transport ingress boundary.

## Module-First TODO Scan (Refinement)
### Material Decisions
- `D-01`: Should repository transport-boundary lint be enforced immediately across all repositories or staged?
- `D-02`: What severity level should the new lint use during migration (`warning` now vs immediate hard fail)?
- `D-03`: Is this slice required to remediate only image-flow repositories or also broader unrelated repositories touched by existing debt?

### Implementation Details (Autonomous)
- Rule implementation in `tool/belluga_custom_lint/lib/src/rules/`.
- Rule registration in plugin.
- Rule documentation update in `tool/belluga_custom_lint/docs/rules.md`.
- Evidence capture via custom-lint/analyze/test outputs.

### Redundant / Already Covered
- Re-stating `repository_json_parsing_forbidden` behavior as a replacement: not valid; this slice is complementary.

## Plan Review Gate (Medium)
### Issue Cards
**Issue ID:** `PRG-01`  
**Severity:** High  
**Evidence:** `lib/infrastructure/repositories/**` currently contains broad raw `Map<String, dynamic>` and `dynamic` usage (203 matches from direct scan).  
**Why now:** Architecture contract says DAO ingests raw; current lint misses this class entirely.

**Option A:** Enforce globally as hard-fail immediately  
- Effort: Medium  
- Risk: High (large warning/error flood blocks active work)  
- Blast radius: Whole Flutter repo  
- Maintenance burden: Medium

**Option B (Recommended):** Enforce globally in warning mode now + remediate in-scope repositories in this slice  
- Effort: Medium  
- Risk: Medium  
- Blast radius: Whole repo visibility, limited implementation scope  
- Maintenance burden: Low/Medium

**Option C:** Do nothing (keep current lint set)  
- Effort: Low  
- Risk: High (silent architecture regressions continue)  
- Blast radius: Ongoing across repositories  
- Maintenance burden: High over time

### Failure Modes & Edge Cases
- False positives for intentional payload-building paths if rule is too broad.
- Missed violations when raw typing is hidden behind typedefs/helpers.
- Incomplete remediation can leave mixed patterns in one feature path.

### Uncertainty Register
- **Assumptions:** Existing DAO layer can absorb raw parsing responsibilities for in-scope repositories without API changes.
- **Unknowns:** How many repositories can be remediated safely in one slice without regressions.
- **Confidence:** Medium.

## Decision Baseline (Frozen)
- `D-01`: Adopt staged enforcement (`Option B` from `PRG-01`).
- `D-02`: New rule ships as warning-level initially; no per-file suppression.
- `D-03`: This slice remediates image-flow repositories only; broader debt tracked in follow-up slices.

## Module Coherence Gate (Planned)
| Decision | Module Coherence | Change Intent | Evidence |
|---|---|---|---|
| `D-01` | Aligned | Preserve | FCX section `2.1.1` transport-boundary guardrail |
| `D-02` | Aligned | Preserve | FCX executable guardrail + lint migration model in custom-lint docs |
| `D-03` | Aligned | Preserve | FCX allows phased hardening provided contract direction is preserved |

## Module Decision Consistency Matrix (Planned, 1-1)
| Module Decision | Planned Handling | Evidence |
|---|---|---|
| `FCX-03` | Preserve | Repository contract remains adapter/DTO-driven |
| `FCX-04` | Preserve | Rule pushes transport handling out of repositories |
| `2.1.1 transport guardrail` | Preserve (strengthen enforcement) | New lint closes missing detection class |

## Tasks
- [x] ✅ Production‑Ready Confirm decision baseline (`D-01..D-03`) with user.
- [x] ✅ Production‑Ready Implement repository transport-boundary lint rule.
- [x] ✅ Production‑Ready Register rule and update `rules.md`.
- [x] ✅ Production‑Ready Run custom lint/analyze and capture findings.
- [x] ✅ Production‑Ready Remediate in-scope image-flow repositories.
- [x] ✅ Production‑Ready Run targeted tests and record outcomes.
- [x] ✅ Production‑Ready Consolidate stable decisions into module docs.

## Execution Evidence (Current Pass)
- Implemented rule: `repository_raw_transport_typing_forbidden`
  - `tool/belluga_custom_lint/lib/src/rules/repository_raw_transport_typing_forbidden_rule.dart`
  - plugin registration updated in `tool/belluga_custom_lint/lib/src/plugin.dart`
  - helper added in `tool/belluga_custom_lint/lib/src/type_utils.dart`
- Documentation updated:
  - `tool/belluga_custom_lint/docs/rules.md` (rule catalog + violation/fix sample)
  - `foundation_documentation/modules/flutter_client_experience_module.md` (explicit repository raw typing guardrail)
- Validation outputs:
  - `fvm dart run custom_lint` -> rule surfaced violations (`repository_raw_transport_typing_forbidden` count: `149`)
  - `fvm dart analyze tool/belluga_custom_lint` -> `No issues found!`

## Approval Gate
Implementation may start only after explicit user reply: **`APROVADO`**.
