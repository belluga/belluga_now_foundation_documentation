# TODO (V1): Domain Primitives -> Canonical ValueObject Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active (Awaiting Approval)
**Owners:** Flutter Domain + Architecture Governance
**Objective:** Eliminate primitive transport typing from domain contracts and enforce canonical ValueObject-only remediation, including explicit handling rules for `List/Set/Iterable/Map`.

---

## References
- `tool/belluga_analysis_plugin/lib/src/rules/domain_primitive_field_forbidden_rule.dart`
- `tool/belluga_analysis_plugin/lib/src/type_utils.dart`
- `tool/belluga_analysis_plugin/docs/rules.md`
- `tool/belluga_custom_lint/lib/src/rules/domain_primitive_field_forbidden_rule.dart`
- `tool/belluga_custom_lint/lib/src/type_utils.dart`
- `tool/belluga_custom_lint/docs/rules.md`
- `tool/belluga_custom_lint/test_fixtures/lint_matrix/**`
- `lib/domain/**`
- `foundation_documentation/modules/flutter_client_experience_module.md`

---

## Canonical Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/submodule_flutter-app_summary.md`

## Decision Consolidation Targets
- `tool/belluga_analysis_plugin/docs/rules.md` (official analyzer-plugin rule contract)
- `tool/belluga_custom_lint/docs/rules.md` (legacy custom_lint mirror for migration clarity)

---

## Scope
- Finalize domain migration where `domain_primitive_field_forbidden` still flags primitives in domain signatures.
- Enforce and document canonical remediation: **The ONLY acceptable solution is ValueObject**.
- Consolidate collection/container policy in the rule and docs:
  - `List/Set/Iterable` must contain domain ValueObjects/domain-owned types only.
  - `Map` is forbidden in domain signatures; use an auxiliary domain model composed of ValueObjects.
- Improve correction/treatment text to explain exactly how to remediate list/set/map cases.
- Add/adjust lint matrix fixtures for positive/negative coverage of:
  - primitive list/set violations,
  - bare collection violations,
  - map violations,
  - compliant ValueObject collections/model replacements.
- Keep call-site updates limited to compatibility required by domain signature changes.
- Remove primitive convenience getters from domain entities/models when they bypass VO contracts (for example, `Set<String>` / `Map<String, Object?>` projections exposed directly by domain classes).
- Focus remediation specifically on domain typing surface:
  - domain fields/constructors/method signatures,
  - domain getters that leak primitives,
  - parsing/normalization ownership in ValueObjects.
- Complete operational migration from `custom_lint` to analyzer plugin as source of truth:
  - CI checks must run analyzer plugin command(s), not `fvm dart run custom_lint`.
  - Rule matrix fixture and validation scripts must be owned by `tool/belluga_analysis_plugin/**`.
  - Legacy `tool/belluga_custom_lint/**` package must be fully decommissioned once references are migrated.
- Official architecture gate command is analyzer at repository root:
  - `fvm dart analyze --format machine` (from `flutter-app` root).
  - Directory-target mode (`fvm dart analyze lib`) is explicitly non-authoritative due to false-clean behavior.

## Out of Scope
- Any feature behavior changes unrelated to domain primitive remediation.
- Controller flow changes, UI logic changes, or any non-domain behavioral redesign.
- Relaxing or disabling architecture rules to make diagnostics pass.
- Hardcoded file lists as a permanent analyzer strategy.

---

## Complexity Classification
- **Complexity:** `medium`
- **Checkpoint policy:** one review checkpoint before approval, one delivery checkpoint with lint evidence.

---

## Plan Review Gate (Medium)

### Architecture
- **Issue ID:** ARC-01
- **Severity:** High
- **Evidence:** domain signatures currently expose primitive escape hatches and collection/map ambiguity.
- **Why now:** ambiguous remediation allows workaround regressions.
- **Options:**
  - **A (Recommended):** formalize collection/map policy in rule engine + docs and enforce in matrix tests.
  - **B:** document-only clarification without rule hardening.
  - **C:** keep current generic wording.

### Code Quality
- **Issue ID:** QLT-01
- **Severity:** High
- **Evidence:** repeated primitive conversion logic in domain-facing APIs.
- **Why now:** domain contracts must be VO-native and deterministic.
- **Options:**
  - **A (Recommended):** move normalization/validation to ValueObjects and consume VOs in domain contracts.
  - **B:** keep primitive getters in domain for convenience.
  - **C:** allow mixed primitive/VO signatures.

### Tests/Validation
- **Issue ID:** TST-01
- **Severity:** Medium
- **Evidence:** missing explicit matrix proof for list/set/map edge cases.
- **Why now:** rule evolution requires anti-regression fixtures.
- **Options:**
  - **A (Recommended):** extend lint matrix fixtures + run deterministic analyzer command.
  - **B:** rely only on manual sample-file checks.
  - **C:** defer test coverage.

### Performance
- **Issue ID:** PRF-01
- **Severity:** Low
- **Evidence:** broader lint matrix increases analysis surface.
- **Why now:** keep checks deterministic and bounded.
- **Options:**
  - **A (Recommended):** keep batch runner + fixture-focused checks.
  - **B:** full-project repeated scans only.
  - **C:** no automated checks.

### Security
- **Issue ID:** SEC-01
- **Severity:** Low
- **Evidence:** no direct security surface expansion.
- **Why now:** ensure no rule bypasses through `dynamic`/container indirection.
- **Options:**
  - **A (Recommended):** treat bare collections/maps as forbidden in domain typing checks.
  - **B:** accept bare collection types.
  - **C:** ignore this dimension.

---

## Failure Modes & Edge Cases
- False positives on domain-owned non-VO collection members.
- Missed typedef indirections resolving to primitive/container payloads.
- Drift between analyzer plugin and custom_lint mirror rule behavior/docs.
- Call-site compile breaks after domain signature hardening.

## Uncertainty Register
- **Assumptions:** ValueObject package variants (`GenericStringValue`, `IntValue`, etc.) remain stable for wrappers.
- **Unknowns:** total residual primitive debt outside current map slice until full deterministic run completes.
- **Confidence:** medium-high.

---

## Module Decision Baseline Snapshot
- **M-01:** Domain contracts must not expose transport primitives.
- **M-02:** Validation ownership belongs to domain ValueObjects.
- **M-03:** Analyzer plugin is the authoritative enforcement path.

## Decision Baseline (Frozen)
- **D-01:** Canonical remediation text must state ValueObject-only treatment (no typedef workaround).
- **D-02:** `List/Set/Iterable` in domain are valid only when element type is ValueObject/domain-owned type.
- **D-03:** `Map` in domain signatures is always forbidden; replace with auxiliary domain model/value objects.
- **D-04:** Rule behavior + documentation must be aligned in analyzer plugin and custom_lint mirror.
- **D-05:** Lint matrix must include violations and compliant examples for list/set/map paths.
- **D-06:** Domain remediation work in this lane must avoid unrelated feature behavior changes.
- **D-07:** Domain `Map` semantics must be represented by auxiliary domain models with their own ValueObjects (never raw/bare map signatures).
- **D-08:** Domain collection semantics must be represented by `List/Set/Iterable` of ValueObjects/domain-owned types only (never primitive element types).
- **D-09:** Domain classes cannot expose primitive convenience getters that leak transport-like representations from VO-backed state.
- **D-10:** Pipeline and local checks must use analyzer plugin via root command (`fvm dart analyze --format machine`) as the only architecture gate.
- **D-11:** `tool/belluga_custom_lint/**` is migration debt and must be removed after CI/scripts/fixtures are moved to analyzer plugin surfaces.
- **D-12:** Root analyzer invocation is official; directory-target analyzer invocation is forbidden as architecture source-of-truth.
- **D-13:** Deterministic workaround runner is not an official gate command.

## Module Coherence Gate (Planned)
- **D-01:** `Aligned` with M-01/M-02 (`Preserve`).
- **D-02:** `Aligned` with M-01 (`Preserve`).
- **D-03:** `Aligned` with M-01 (`Preserve`).
- **D-04:** `Aligned` with M-03 (`Preserve`).
- **D-05:** `Aligned` with enforcement rigor (`Preserve`).
- **D-06:** `Aligned` with scope discipline (`Preserve`).
- **D-07:** `Aligned` with M-01/M-02 (`Preserve`).
- **D-08:** `Aligned` with M-01/M-02 (`Preserve`).
- **D-09:** `Aligned` with M-01/M-02 (`Preserve`).
- **D-10:** `Aligned` with M-03 (`Preserve`).
- **D-11:** `Aligned` with M-03 (`Preserve`).
- **D-12:** `Aligned` with M-03 (`Preserve`).
- **D-13:** `Aligned` with M-03 (`Preserve`).

## Module Decision Consistency Matrix (Planned 1-1)
- M-01 -> `Preserve` by D-01/D-02/D-03.
- M-02 -> `Preserve` by D-01.
- M-03 -> `Preserve` by D-04/D-05.
- M-01 -> `Preserve` by D-07/D-08/D-09.
- M-02 -> `Preserve` by D-07/D-08/D-09.
- M-03 -> `Preserve` by D-10/D-11.
- M-03 -> `Preserve` by D-12/D-13.

---

## Current Evidence Snapshot (Analyzer Direct Check)
- Validation sequence executed in isolated worktree:
  - `fvm flutter clean`
  - `fvm flutter pub get`
  - `fvm dart analyze --format machine` (repo root)
  - `fvm dart analyze --format machine lib`
  - `fvm dart analyze --format machine lib/domain/map/filters/poi_filter_category.dart`
- Observed outputs:
  - root: `exit 0`, output effectively empty.
  - lib: `exit 0`, output effectively empty.
  - targeted file: `exit 0`, output effectively empty.
- Interpretation:
  - Direct analyzer path is still not yet proven to reflect expected architecture diagnostics.
  - Additional plugin-load/runtime verification is required before declaring script-free official flow.

### Update (after full custom_lint removal + plugin config fix)
- Changes applied in isolated worktree for re-test:
  - removed `tool/belluga_custom_lint/**` entirely,
  - switched plugin config to the correct top-level `plugins:` section in `analysis_options.yaml`:
    - `belluga_analysis_plugin: { path: tool/belluga_analysis_plugin }`.
- Re-test results:
  - `fvm dart analyze --format machine` (root): **issues emitted** (`exit 2`, hundreds of lines, including `DOMAIN_PRIMITIVE_FIELD_FORBIDDEN`).
  - `fvm dart analyze --format machine lib`: **false-clean persists** (`exit 0`, empty output).
  - `fvm dart analyze --format machine lib/domain/map/filters/poi_filter_category.dart`: **issues emitted** (`exit 2`, 14 warnings).
- Conclusion:
  - plugin load/config mismatch was part of the problem,
  - but directory-mode inconsistency (`lib` false-clean) still exists,
  - therefore the official gate is `fvm dart analyze --format machine` at repository root (never `dart analyze lib`).

### Update (official command decision, after cold-run revalidation)
- Cold-run validation sequence:
  - `fvm flutter clean`
  - `fvm flutter pub get`
  - `fvm dart analyze --format machine`
  - `fvm dart analyze --format machine lib`
  - `fvm dart analyze --format machine lib/domain/map/filters/poi_filter_category.dart`
- Observed outputs:
  - root: `exit 2`, `805` machine lines (`804`x `DOMAIN_PRIMITIVE_FIELD_FORBIDDEN`).
  - lib: `exit 0`, `No issues found` (false-clean persists).
  - targeted file: `exit 2`, `14` `DOMAIN_PRIMITIVE_FIELD_FORBIDDEN` warnings.
- Final decision:
  - **Official command for local + CI architecture gate:** `fvm dart analyze --format machine` (root only).
  - `fvm dart analyze lib` is prohibited as architecture gate input.
  - workaround script is not required as official gate.

---

## Tactical Checklist
- [ ] ⚪ Pending Rule hardening in `tool/belluga_analysis_plugin/lib/src/type_utils.dart` for list/set/map policy.
- [ ] ⚪ Pending Update correction/treatment text in `tool/belluga_analysis_plugin/lib/src/rules/domain_primitive_field_forbidden_rule.dart` with explicit list/set/map remediation guidance.
- [ ] ⚪ Pending Add/update analyzer-plugin docs in `tool/belluga_analysis_plugin/docs/rules.md` with explicit list/set/map remediation guidance.
- [ ] ⚪ Pending Migrate rule-matrix fixture from `tool/belluga_custom_lint/test_fixtures/lint_matrix/**` to `tool/belluga_analysis_plugin/test_fixtures/lint_matrix/**`.
- [ ] ⚪ Pending Update `tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` to use analyzer-plugin-owned fixture path.
- [ ] ⚪ Pending Update CI workflow(s) (including `.github/workflows/web-artifact-publish.yml`) to remove `custom_lint` steps and run analyzer plugin gate command(s).
- [ ] ⚪ Pending Remove legacy `tool/belluga_custom_lint/**` package after references are migrated.
- [ ] ⚪ Pending Investigate plugin load/runtime in direct analyzer path (confirm plugin registration is active and diagnostics are emitted).
- [ ] ⚪ Pending Run parity gate for direct analyzer:
  - root + lib + targeted file must all surface expected warnings when violations are present.
- [x] ✅ Production‑Ready Decision checkpoint:
  - official command switched to root analyzer (`fvm dart analyze --format machine`),
  - script workaround removed from official gate path.
- [ ] ⚪ Pending Fix remaining domain primitive violations in active slice using ValueObjects.
- [ ] ⚪ Pending Remove/replace primitive convenience getters in domain classes with VO-safe APIs.
- [x] ✅ Production‑Ready Run root analyzer command + targeted sample checks and capture evidence.
- [ ] ⚪ Pending Validate there are no operational references to `custom_lint`/`belluga_custom_lint` in CI/scripts/pubspec/docs (except explicit migration notes if intentionally kept).

## Validation Steps (Planned)
- `fvm flutter clean`
- `fvm flutter pub get`
- `fvm dart analyze --format machine`
- `fvm dart analyze --format machine lib`
- `fvm dart analyze --format machine lib/domain/map/filters/poi_filter_category.dart`
- `fvm dart analyze lib/domain/map/filters/poi_filter_category.dart`
- `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`
- `rg -n "custom_lint|belluga_custom_lint" .github tool README.md analysis_options.yaml pubspec.yaml pubspec.lock`

## Definition of Done
- Rule + docs explicitly cover ValueObject-only remediation and list/set/map policy.
- Root analyzer command (`fvm dart analyze --format machine`) reports domain primitive diagnostics consistently.
- Domain files touched in this lane no longer expose primitive transport typing.
- Final report includes before/after counts and at least one real warning example under the official command.
