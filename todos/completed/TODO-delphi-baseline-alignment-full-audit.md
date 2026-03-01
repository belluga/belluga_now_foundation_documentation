## Title
Delphi Baseline Alignment - Full Individual Audit and Upgrades

## Context
After introducing Plan Review Gate, Decision Baseline, Decision Adherence Gate, and Cline authority boundaries, we need a full file-by-file audit of Skills/Rules/Workflows to identify remaining gaps and apply improvements.

The user explicitly requested individual analysis (one file at a time), then a coherence pass across the full set.

## Complexity
- Level: `big`
- Checkpoint Policy: section-by-section checkpoints (skills -> rules -> workflows -> coherence/sync).
- Plan Review Gate: mandatory and documented below.

## Scope
- [x] Audit every Delphi skill (`delphi-ai/skills/**/SKILL.md`) individually against new baselines.
- [x] Audit every Delphi rule (`delphi-ai/rules/**/*.md`) individually against new baselines.
- [x] Audit every Delphi workflow (`delphi-ai/workflows/**/*.md`) individually against new baselines.
- [x] Audit relevant Cline governance surfaces (`delphi-ai/.clinerules/**`, `delphi-ai/CLINE.md`, `delphi-ai/.cline/MANIFEST.md`) for baseline compliance.
- [x] Improve all files with material gaps found in the individual audit.
- [x] Improve test skills mentioned by user (`test-quality-audit`, `test-creation-standard`, `test-orchestration-suite`) according to new baselines.
- [x] Run final coherence review across updated skills/rules/workflows and enforce cross-surface sync expectations.

## Delivery Stages
- [x] ✅ Production‑Ready

## Applicable Rule/Workflow Sources
- `delphi-ai/skills/rule-docker-shared-self-improvement-manual/SKILL.md`
- `delphi-ai/skills/wf-docker-self-improvement-session-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/wf-docker-update-skill-method/SKILL.md`

## Provisional Notes (Required if Provisional)
- **Missing for production-ready:** n/a
- **Revisit criteria:** n/a
- **Dependencies unblocked:** n/a

## Out of Scope
- [ ] Product feature implementation in Flutter/Laravel modules not related to governance artifacts.
- [ ] Changing strategic business mandates outside governance alignment scope.

## Decisions
- [x] `D-01` New baselines are mandatory reference points: Plan Review Gate, complexity checkpoint policy, Decision Baseline freeze, Decision Adherence Gate, APROVADO gate, and Cline advisory boundary.
- [x] `D-02` Individual audit findings will be recorded file-by-file before the final coherence pass.
- [x] `D-03` Only material gaps will be changed (no cosmetic churn).
- [x] `D-04` Codex/Cline/Antigravity surfaces must remain aligned for changed behaviors.
- [x] `D-05` Test skills cited by user are in scope for baseline alignment improvements.

## Plan Review Gate

### Architecture Review
#### Issue Card `A-01`
- Severity: High
- Evidence: governance standards currently concentrated in TODO workflow + selected rules, but not consistently propagated into all skill/workflow docs.
- Why now: user requested full-system adherence; partial propagation leads to recurrent delivery drift.
- Option A (Recommended): add concise baseline enforcement clauses in each affected skill/rule/workflow family + mirrored Cline surfaces.
  - Effort: High
  - Risk: Medium
  - Blast radius: High (many governance docs)
  - Maintenance burden: Medium
- Option B: keep baselines centralized only in main instructions and TODO workflow.
  - Effort: Low
  - Risk: High (silent drift persists)
  - Blast radius: Medium
  - Maintenance burden: High (repeated reminders needed)
- Option C (Do nothing): no propagation work.
  - Effort: None
  - Risk: Very High
  - Blast radius: High (future sessions inconsistent)
  - Maintenance burden: Very High

### Code Quality Review
#### Issue Card `Q-01`
- Severity: Medium
- Evidence: duplicated guidance text diverges across parallel surfaces (`skills`, `.cline/skills`, `.clinerules`).
- Why now: individual per-file edits can amplify drift if not normalized.
- Option A (Recommended): enforce a deterministic sync pass after edits (`verify_context.sh` + `verify_adherence_sync.sh`) and only edit material sections.
  - Effort: Medium
  - Risk: Low
  - Blast radius: Medium
  - Maintenance burden: Low
- Option B: manually inspect changed files without sync scripts.
  - Effort: Medium
  - Risk: Medium
  - Blast radius: Medium
  - Maintenance burden: Medium
- Option C (Do nothing): skip quality normalization.
  - Effort: None
  - Risk: High
  - Blast radius: High
  - Maintenance burden: High

### Test Review
#### Issue Card `T-01`
- Severity: High
- Evidence: `test-quality-audit`, `test-creation-standard`, `test-orchestration-suite` currently do not fully encode decision-baseline/adherence validation concepts.
- Why now: user explicitly requested these skills be improved under new baselines.
- Option A (Recommended): upgrade all three skills with decision-gate and exception-handling sections.
  - Effort: Medium
  - Risk: Low
  - Blast radius: Medium
  - Maintenance burden: Low
- Option B: update only `test-quality-audit`.
  - Effort: Low
  - Risk: Medium
  - Blast radius: Low
  - Maintenance burden: Medium
- Option C (Do nothing): keep current test skill guidance.
  - Effort: None
  - Risk: High
  - Blast radius: Medium
  - Maintenance burden: High

### Performance Review
#### Issue Card `P-01`
- Severity: Medium
- Evidence: a strict one-by-one audit can become too slow if done with repetitive manual commands.
- Why now: this scope spans >100 files.
- Option A (Recommended): run scripted inventory scans, then apply edits only where baseline terms are absent or conflicting.
  - Effort: Medium
  - Risk: Low
  - Blast radius: Low
  - Maintenance burden: Low
- Option B: purely manual file review.
  - Effort: Very High
  - Risk: Medium
  - Blast radius: Low
  - Maintenance burden: Medium
- Option C (Do nothing): skip audit due size.
  - Effort: None
  - Risk: Very High
  - Blast radius: High
  - Maintenance burden: High

### Security Review
#### Issue Card `S-01`
- Severity: Medium
- Evidence: instruction surfaces can accidentally include project-specific data during wide edits.
- Why now: high-volume documentation touches increase leakage risk.
- Option A (Recommended): run agnosticism/readiness checks and constrain edits to governance language only.
  - Effort: Low
  - Risk: Low
  - Blast radius: Medium
  - Maintenance burden: Low
- Option B: trust reviewer memory without checks.
  - Effort: Low
  - Risk: Medium
  - Blast radius: Medium
  - Maintenance burden: Medium
- Option C (Do nothing): no explicit security/agnosticism check.
  - Effort: None
  - Risk: High
  - Blast radius: High
  - Maintenance burden: High

## Failure Modes & Edge Cases
- Drift mode: `skills/*` updated but `.cline/skills/*` or `.clinerules/*` left stale.
- False completion mode: TODO marked done while one baseline decision has no adherence evidence.
- Over-edit mode: cosmetic churn creates noisy diffs and hides material governance changes.
- Permission edge case: Laravel `.agent/rules/shared/*` write-denied during context sync; must report if it impacts validation confidence.

## Uncertainty Register
- Assumptions:
  - Existing `verify_adherence_sync.sh` coverage is broad enough to catch critical cross-surface drift.
  - Current active TODO scope is acceptable for all instruction-governance edits requested.
- Unknowns:
  - Whether any legacy skill/rule/workflow intentionally deviates from new baselines for historical reasons.
  - Whether external local skill copies under `~/.codex/skills/**` are authoritative for any non-public skill.
- Confidence:
  - Baseline alignment outcome confidence: Medium-High.
  - Full zero-drift guarantee confidence before final sync pass: Medium.

## Decision Baseline (Frozen)
- `D-01`: Apply new baseline gates as mandatory quality criteria.
- `D-02`: Execute one-file-at-a-time analysis before coherence pass.
- `D-03`: Limit edits to material gaps only.
- `D-04`: Keep Codex/Cline/Antigravity surfaces synchronized for changed behavior.
- `D-05`: Upgrade test-related skills with baseline-aware guidance.

## Individual Audit Findings (Pre-Implementation)
### Skills (`delphi-ai/skills/**/SKILL.md`)
- `skills/wf-docker-asdsadasdas/SKILL.md`: placeholder artifact (`asdasdasd`) -> remove or replace.
- `skills/wf-docker-update-skill-method/SKILL.md`: canonical workflow counterpart missing (`workflows/docker/update-skill-method.md`).
- `skills/wf-laravel-create-package-method/SKILL.md`: canonical workflow counterpart missing (`workflows/laravel/create-package-method.md`).
- `skills/rule-docker-shared-workflow-definition-model-decision/SKILL.md`: references non-existent template path (`workflow_template.md`) and lacks explicit governance-baseline requirements for implementation-affecting workflows.
- `skills/rule-laravel-shared-workflow-definition-model-decision/SKILL.md`: same gap as docker counterpart.

### Rules (`delphi-ai/rules/**/*.md`)
- `rules/docker/shared/workflow-definition-model-decision.md`: same template-path + baseline-governance gap.
- `rules/laravel/shared/workflow-definition-model-decision.md`: same template-path + baseline-governance gap.

### Workflows (`delphi-ai/workflows/**/*.md`)
- `workflows/docker/asdsadasdas.md`: placeholder artifact (`asdasdasd`) -> remove or replace.
- Missing canonical workflow docs:
  - `workflows/docker/update-skill-method.md`
  - `workflows/laravel/create-package-method.md`

### Cline Surfaces (`delphi-ai/.clinerules/**`, `delphi-ai/.cline/**`)
- Missing Cline workflow counterparts for existing Cline wf-skills:
  - `.clinerules/workflows/docker-update-skill-method.md` (for `.cline/skills/wf-docker-update-skill-method/SKILL.md`)
  - `.clinerules/workflows/laravel-create-package-method.md` (for `.cline/skills/wf-laravel-create-package-method/SKILL.md`)
- `.clinerules/model-decision/shared-workflow-definition.md`: should be strengthened with baseline controls and explicit coherence checks.

### Cross-Surface Canonical Ownership
- `test-quality-audit`, `test-creation-standard`, `test-orchestration-suite` currently appear only under `/home/elton/.codex/skills/public/` (external-only). Evaluate migration or mirrored canonicalization into `delphi-ai/skills/` + `.cline/skills/` to satisfy long-term Codex/Cline/Antigravity sync.

## Questions To Close
- [x] None.

## Definition of Done
- [x] Individual audit completed for each targeted skill/rule/workflow file.
- [x] Material gaps fixed in corresponding files.
- [x] Final coherence pass completed and inconsistencies resolved.
- [x] Sync checks completed (`verify_context.sh`, `verify_adherence_sync.sh` where applicable).
- [x] Outcome summary includes per-file improvement rationale and residual risks.
- [x] Decision Adherence Validation table completed with evidence for `D-01..D-05`.

## Commands (Run Locally)
- `bash delphi-ai/tools/verify_context.sh`
- `bash delphi-ai/tools/verify_adherence_sync.sh`
- `bash delphi-ai/tools/audit_instruction_baselines.sh --output foundation_documentation/artifacts/tmp/delphi-baseline-audit-2026-02-28.md`
- `git -C delphi-ai status --short`

## Implementation Summary
- Removed placeholder artifacts:
  - `delphi-ai/skills/wf-docker-asdsadasdas/SKILL.md`
  - `delphi-ai/workflows/docker/asdsadasdas.md`
- Added missing canonical workflows:
  - `delphi-ai/workflows/docker/update-skill-method.md`
  - `delphi-ai/workflows/laravel/create-package-method.md`
- Added missing Cline workflow counterparts:
  - `delphi-ai/.clinerules/workflows/docker-update-skill-method.md`
  - `delphi-ai/.clinerules/workflows/laravel-create-package-method.md`
- Strengthened workflow-definition governance across skill/rule/Cline model-decision surfaces with baseline gate requirements and corrected template path (`workflow-template.md`).
- Hardened sync tooling:
  - `delphi-ai/tools/verify_adherence_sync.sh`: counterpart checks, placeholder detection, manifest enforcement, public Codex mirror checks.
  - `delphi-ai/tools/sync_agent_rules.sh`: workflow sync now removes stale files before copy.
- Added reusable individual-audit tool:
  - `delphi-ai/tools/audit_instruction_baselines.sh`
- Canonicalized and synced test skills across all surfaces:
  - Canonical: `delphi-ai/skills/test-quality-audit/SKILL.md`, `.../test-creation-standard/SKILL.md`, `.../test-orchestration-suite/SKILL.md`
  - Cline mirrors: `delphi-ai/.cline/skills/test-*/SKILL.md`
  - Codex public mirrors: `/home/elton/.codex/skills/public/test-*/SKILL.md`
- Updated Cline governance inventory/docs:
  - `delphi-ai/CLINE.md`
  - `delphi-ai/.cline/MANIFEST.md`

## Decision Adherence Validation
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| D-01 | Adherent | `delphi-ai/rules/docker/shared/workflow-definition-model-decision.md:9`, `:19`, `:20`; `delphi-ai/rules/laravel/shared/workflow-definition-model-decision.md:9`, `:19`, `:20`; `delphi-ai/.clinerules/model-decision/shared-workflow-definition.md:8`, `:25`, `:26` | Baseline gates propagated into workflow-definition family (canonical + Cline). |
| D-02 | Adherent | `foundation_documentation/artifacts/tmp/delphi-baseline-audit-2026-02-28.md:1`, `:141`, `:142`, `:143`; `delphi-ai/tools/audit_instruction_baselines.sh:133`, `:163`, `:319`, `:320` | Individual per-file audit executed and archived with explicit summary counts. |
| D-03 | Adherent | Placeholder removals + targeted adds: `delphi-ai/workflows/docker/update-skill-method.md`, `delphi-ai/workflows/laravel/create-package-method.md`, `delphi-ai/.clinerules/workflows/docker-update-skill-method.md`, `delphi-ai/.clinerules/workflows/laravel-create-package-method.md` | Only material gaps addressed (counterparts, governance gates, sync reliability, test-skill baseline upgrades). |
| D-04 | Adherent | `delphi-ai/tools/verify_adherence_sync.sh:287`, `:288`, `:314`, `:315`, `:316`, `:317`, `:318`; `delphi-ai/.cline/MANIFEST.md:19`, `:20`, `:26`, `:27`, `:28`, `:29`, `:30`; `delphi-ai/CLINE.md:44`, `:45`, `:46`, `:47`, `:48` | Codex/Cline/Antigravity sync controls and inventory were aligned and enforced. |
| D-05 | Adherent | `delphi-ai/skills/test-quality-audit/SKILL.md:12`, `:41`; `delphi-ai/skills/test-creation-standard/SKILL.md:12`, `:23`, `:38`; `delphi-ai/skills/test-orchestration-suite/SKILL.md:30`, `:41`; mirrors validated by coherence report | Test skills now encode APROVADO + decision baseline/adherence concepts and cross-surface mirrors. |

## Files Expected (Optional)
- `delphi-ai/skills/**/SKILL.md`
- `delphi-ai/rules/**/*.md`
- `delphi-ai/workflows/**/*.md`
- `delphi-ai/.clinerules/**/*.md`
- `delphi-ai/CLINE.md`
- `delphi-ai/.cline/MANIFEST.md`
- `/home/elton/.codex/skills/public/test-quality-audit/SKILL.md`
- `/home/elton/.codex/skills/public/test-creation-standard/SKILL.md`
- `/home/elton/.codex/skills/public/test-orchestration-suite/SKILL.md`
