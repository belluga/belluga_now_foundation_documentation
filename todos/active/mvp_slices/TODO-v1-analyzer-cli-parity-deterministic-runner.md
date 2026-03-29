# TODO (V1): Analyzer CLI Parity + Deterministic Runner

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active (Awaiting Approval)
**Owners:** Flutter Team + Platform Governance
**Objective:** Eliminate false-clean analyzer results in CLI by establishing one deterministic command for local and CI that matches relevant VS Code diagnostics without hardcoded file lists.

---

## References
- `analysis_options.yaml`
- `tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`
- `tool/belluga_analysis_plugin/bin/check_branch_delta_domain_primitive_field.sh`
- `tool/belluga_analysis_plugin/bin/check_branch_delta_raw_payload_map.sh`
- `.github/workflows/web-artifact-publish.yml`
- `foundation_documentation/modules/flutter_client_experience_module.md`

---

## Canonical Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/submodule_flutter-app_summary.md`

## Decision Consolidation Targets
- `foundation_documentation/modules/flutter_client_experience_module.md` (CI analyzer source-of-truth command)
- `tool/belluga_analysis_plugin/docs/rules.md` (operational lint/analyzer command contract)

---

## Scope
- Diagnose and document root cause with command evidence.
- Create deterministic analyzer runner using:
  - dynamic `.dart` discovery (no hardcoded file list),
  - dynamic excludes from `analysis_options.yaml`,
  - explicit file batching,
  - deduplicated diagnostics by `code + file + line`,
  - final summary counts.
- Update CI and branch-delta scripts to use the deterministic runner.
- Update docs to declare one official command for dev and CI.
- Validate with:
  - `fvm flutter clean`
  - `fvm flutter pub get`
  - official command
  - count comparison against `lib/domain/map/filters/poi_filter_category.dart` and a broader sample.

## Out of Scope
- Removing/reducing lint rules to force green.
- Hardcoded file allowlists/deny lists.
- Broad refactor of lint rule implementations.
- Editing global Delphi skill files in `../delphi-ai/` in this execution lane.

---

## Current Evidence Snapshot
- `fvm dart analyze` (repo root) => `No issues found!`
- `fvm dart analyze lib` => `No issues found!`
- `fvm dart analyze lib/domain/map/filters` => `No issues found!`
- `fvm dart analyze lib/domain/map/filters/poi_filter_category.dart` => `14 issues found` (`domain_primitive_field_forbidden`)
- `fvm dart analyze <50 explicit files>` => `48 issues found`
- `fvm dart run custom_lint` => `No issues found!`
- Explicit-file analyze bypasses `analysis_options.yaml` excludes (fixture file in excluded path still reports diagnostics).

Interpretation:
- CLI directory/package analysis is yielding false-clean for plugin diagnostics in this setup.
- Explicit-file analysis reports expected plugin diagnostics, but requires explicit exclude filtering.

---

## Complexity Classification
- **Complexity:** `medium`
- **Checkpoint policy:** one review checkpoint before approval; then implementation; then validation checkpoint.

---

## Plan Review Gate (Medium)

### Architecture
- **Issue ID:** ARC-01
- **Severity:** High
- **Evidence:** `analysis_options.yaml:7`, `tool/belluga_analysis_plugin/bin/check_branch_delta_domain_primitive_field.sh:25`
- **Why now:** Current command path can silently miss architecture violations.
- **Options:**
  - **A (Recommended):** deterministic explicit-file runner with dynamic discovery/exclude filtering.
  - **B:** keep `dart analyze <dir>` and accept known false-clean risk.
  - **C:** revert to `custom_lint` as primary gate.
- **Tradeoff summary:**
  - A effort medium, risk low-medium, blast radius moderate (scripts/CI/docs), maintenance low.
  - B effort low, risk high, blast radius high (silent regressions), maintenance low.
  - C effort high, risk medium (migration rollback churn), blast radius high, maintenance medium-high.

### Code Quality
- **Issue ID:** QLT-01
- **Severity:** Medium
- **Evidence:** existing scripts call raw `fvm dart analyze` with file arrays.
- **Why now:** Need one canonical entrypoint, not fragmented ad-hoc calls.
- **Options:**
  - **A (Recommended):** central script used by all callers.
  - **B:** duplicate logic in each script.
  - **C:** no script, rely on manual command discipline.

### Tests/Validation
- **Issue ID:** TST-01
- **Severity:** Medium
- **Evidence:** mismatch between file-level and directory-level outputs.
- **Why now:** CI must prove the same source of truth as local workflow.
- **Options:**
  - **A (Recommended):** enforce validation sequence with count checks and sample file verification.
  - **B:** only smoke run root analyze.
  - **C:** skip validation if command exits non-zero.

### Performance
- **Issue ID:** PRF-01
- **Severity:** Medium
- **Evidence:** explicit-file analysis can be heavy on large sets.
- **Why now:** full-project execution must remain practical in CI/local.
- **Options:**
  - **A (Recommended):** chunked batches with configurable batch size.
  - **B:** single huge argument list.
  - **C:** file-by-file execution.

### Security
- **Issue ID:** SEC-01
- **Severity:** Low
- **Evidence:** runner will parse local config and invoke analyzer commands.
- **Why now:** avoid unsafe eval/path expansion mistakes.
- **Options:**
  - **A (Recommended):** strict bash (`set -euo pipefail`), quoted arrays, no eval.
  - **B:** dynamic shell string building.
  - **C:** temporary permissive scripting for speed.

---

## Failure Modes & Edge Cases
- Exclude pattern parsing diverges from analyzer semantics.
- Very large file count exceeds command-line argument limits (mitigated by batching).
- Duplicate diagnostics across batches inflate counts (mitigated by dedupe key).
- Nonexistent files passed by branch-delta list cause hard failure.
- Analyzer process timeout in CI.

## Uncertainty Register
- **Assumptions:** explicit-file mode continues to emit plugin diagnostics reliably in this toolchain.
- **Unknowns:** whether upstream analyzer/plugin version alignment can later restore trustworthy directory-mode parity.
- **Confidence:** medium-high for deterministic runner; medium for root-cause certainty at upstream analyzer internals level.

---

## Module Decision Baseline Snapshot
- **M-01:** `flutter_client_experience_module.md` currently states CI runs `flutter analyze`.
- **M-02:** Architecture governance requires analyzer plugin findings to be enforced.
- **M-03:** Custom lint is no longer the primary enforcement path after migration.

## Decision Baseline (Frozen)
- **D-01:** Official analyzer source-of-truth will be a deterministic explicit-file runner.
- **D-02:** Runner must perform dynamic `.dart` discovery with zero hardcoded project file lists.
- **D-03:** Runner must apply exclude patterns dynamically from `analysis_options.yaml` before invoking analyze.
- **D-04:** CI and branch-delta scripts will call the same runner entrypoint.
- **D-05:** Documentation will be updated to a single official command for dev and CI.
- **D-06:** This lane will not edit global Delphi skills outside this repo.

## Module Coherence Gate (Planned)
- **D-01:** `Aligned` with M-02/M-03 (`Preserve`).
- **D-02:** `Aligned` with no-hardcode operational requirement (`Preserve`).
- **D-03:** `Aligned` with analyzer config intent (`Preserve`).
- **D-04:** `Aligned` with governance consistency (`Preserve`).
- **D-05:** `Supersede` M-01 wording (`flutter analyze`) with deterministic runner command (`Supersede`, intentional doc update).
- **D-06:** `Aligned` with current scope and safety boundaries (`Preserve`).

## Module Decision Consistency Matrix (Planned 1-1)
- M-01 -> `Supersede (Intentional)` by D-05, with module doc update.
- M-02 -> `Preserve` by D-01/D-04.
- M-03 -> `Preserve` by D-01.

---

## Validation Steps (Planned)
- `fvm flutter clean`
- `fvm flutter pub get`
- Official command (deterministic runner)
- `fvm dart analyze lib/domain/map/filters/poi_filter_category.dart` (reference sample)
- Broad sample check using explicit-file batch output from official command logs.

## Definition of Done
- One official command is documented and used in CI + local scripts.
- Official command reports real architecture warnings that match sample file expectations.
- Final report includes:
  - confirmed root cause,
  - before vs after counts,
  - real warning examples,
  - changed files summary.

