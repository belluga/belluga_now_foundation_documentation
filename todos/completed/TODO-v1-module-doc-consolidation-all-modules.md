# TODO (V1): Module-First Documentation Consolidation Across All Modules

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Architecture/Docs Team
**Objective:** Apply the same module-first consolidation process used in Events/Agenda to all module documents, promoting stable decisions/plans to canonical module docs and reducing tactical TODO drift.

---

## Scope
- Audit every file under `foundation_documentation/modules/` for:
  - drift against runtime/package truth;
  - overlap/conflict with active/completed TODO decisions;
  - missing canonical anchors (contracts, package README, owning TODO stream).
- Consolidate stable conceptual plans and decision baselines into each module doc.
- Update or archive conflicting/superseded tactical TODO references when module canonical truth is established.
- Keep cross-links coherent between module docs, roadmap/summary docs, and TODO files.
- Run coherence scans + adherence sync checks before closure.

---

## Out of Scope
- Runtime code refactors in Laravel/Flutter/Web.
- New feature design beyond already decided architecture.
- Full rewrite of all historical TODO narratives (only needed corrections for coherence/traceability).

---

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/account_workspace_module.md`
  - `foundation_documentation/modules/account_profile_analytics_capability.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/task_and_reminder_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/transaction_bridge_module.md`
- **System anchors:**
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`

---

## Complexity
- **Level (`small|medium|big`):** big
- **Checkpoint policy:** section-by-section
- **Why this level:** cross-module canonicalization with architecture impact on all documentation surfaces.

---

## Decisions
- [x] ✅ Production‑Ready `D-01` Module docs are canonical for stable architecture/decisions; TODOs are tactical execution records.
- [x] ✅ Production‑Ready `D-02` Each module doc must include canonical anchors (runtime/package/todo references) and promotion traceability.
- [x] ✅ Production‑Ready `D-03` Conflicting statements in system docs (`system_roadmap`, submodule summaries, module docs) are corrected to the canonical contract truth.
- [x] ✅ Production‑Ready `D-04` Completed/superseded TODOs remain historical but must not be authoritative when conflicting with promoted module truth.
- [x] ✅ Production‑Ready `D-05` Consolidation style is non-destructive normalization (add/align canonical sections and fix conflicts), not full narrative rewrite of each module.

## Decision Baseline (Frozen Before Implementation)
- [x] ✅ Production‑Ready `D-01` Canonical authority resides in module docs post-consolidation.
- [x] ✅ Production‑Ready `D-02` Every module has anchor + traceability sections after this pass.
- [x] ✅ Production‑Ready `D-03` Drift/conflicts are either resolved or explicitly marked with approved exception.
- [x] ✅ Production‑Ready `D-04` TODO/module cross-links are coherent after moves/archives.
- [x] ✅ Production‑Ready `D-05` Module-specific business narratives are preserved; only canonicalization/coherence edits are applied.

---

## Questions To Close
- [x] Proposed: apply non-destructive normalization in this pass (same approach as Events/Agenda), avoiding full narrative rewrite.

---

## Plan Review Gate (Required for `medium|big`; abbreviated for low-risk `small`)

### Review Sections
- [x] ✅ Production‑Ready Architecture
- [x] ✅ Production‑Ready Code Quality
- [x] ✅ Production‑Ready Tests
- [x] ✅ Production‑Ready Performance
- [x] ✅ Production‑Ready Security

### Issue Cards
- **Issue ID:** ARCH-01
  - **Severity:** high
  - **Evidence:** module docs currently have uneven structure and mixed authority with tactical TODOs.
  - **Why it matters now:** increases audit ambiguity and decision drift.
  - **Option A (Recommended):** full module-by-module canonicalization in this pass.
    - **Effort:** high
    - **Risk:** medium
    - **Blast radius:** cross-module docs
    - **Maintenance burden:** low after consolidation
  - **Option B (Alternative):** partial normalization (anchors only), defer deeper consolidation.
    - **Effort:** medium
    - **Risk:** medium-high
    - **Blast radius:** module-local
    - **Maintenance burden:** medium
  - **Option C (Do Nothing):** keep current mixed state.
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** cross-module
    - **Maintenance burden:** high
  - **Recommendation:** A

### Failure Modes & Edge Cases
- [x] ✅ Production‑Ready Broken references after TODO moves/renames.
- [x] ✅ Production‑Ready Over-normalization that rewrites business intent incorrectly.
- [x] ✅ Production‑Ready Legacy notes in completed TODOs still interpreted as canonical.

### Uncertainty Register
- [x] ✅ Production‑Ready **Assumption:** existing module docs are broadly correct but structurally inconsistent.
- [x] ✅ Production‑Ready **Unknown:** hidden conflicts with code truth outside currently active streams.
- [x] ✅ Production‑Ready **Confidence:** medium-high for module surfaces; medium globally.

---

## Tasks
- [x] ✅ Production‑Ready Perform a coherence scan module-by-module (`foundation_documentation/modules/*`).
- [x] ✅ Production‑Ready Add/normalize canonical anchors + decision/promotion sections per module.
- [x] ✅ Production‑Ready Resolve conflicts in `system_roadmap.md` and submodule summaries where module truth changed.
- [x] ✅ Production‑Ready Update TODO cross-links and move delivered/superseded TODOs where needed (module/system surfaces only in this slice).
- [x] ✅ Production‑Ready Validate with `verify_context.sh` and `verify_adherence_sync.sh`.
- [x] ✅ Production‑Ready Complete decision-adherence table with evidence per decision.

---

## Decision Adherence Validation (Mandatory Before Delivery)
- | Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
- | --- | --- | --- | --- |
- | `D-01` | Adherent | all files under `foundation_documentation/modules/*.md` now include canonical anchors | Module docs are now explicit canonical surfaces. |
- | `D-02` | Adherent | module docs include decision baseline + tactical promotion ledger sections | Traceability is explicit in each module. |
- | `D-03` | Adherent | `foundation_documentation/system_roadmap.md`, `submodule_laravel-app_summary.md`, `submodule_flutter-app_summary.md` | System-level drift corrected for this consolidation slice. |
- | `D-04` | Adherent | module/system TODO links scan: `NO_MISSING_TODO_LINKS_IN_MODULE_SURFACES` | Global historical TODO link debt remains outside this slice. |
- | `D-05` | Adherent | module narratives preserved; additive canonical sections added | Non-destructive normalization confirmed. |

---

## Delivery Confidence Gate (Required for `✅ Production-Ready`)
- [x] ✅ Production‑Ready **Runtime impact classified:** none (documentation/governance only)
- [x] ✅ Production‑Ready **Operational checks run (if runtime-impacting):** N/A
- [x] ✅ Production‑Ready **Evidence artifacts recorded:** command outputs + git diff evidence
- [x] ✅ Production‑Ready **Confidence stated:** medium-high for module surfaces; residual risk: historical TODO link debt outside module/system docs.
- [x] ✅ Production‑Ready **Release readiness outcome:** ready

## Module Consolidation Gate (Required Before `Completed`)
- [x] ✅ Production‑Ready Canonical module docs updated with promoted stable decisions/plans for all module files in scope.
- [x] ✅ Production‑Ready Promotion/decision traceability present in module docs.
- [x] ✅ Production‑Ready Superseded tactical notes replaced by canonical module references where applicable.
- [x] ✅ Production‑Ready TODO/module cross-links updated and coherent for module/system surfaces.

---

## Definition of Done
- [x] ✅ Production‑Ready All module docs in `foundation_documentation/modules/` are consolidated under the canonical model.
- [x] ✅ Production‑Ready No unresolved documentation conflict remains between module docs, roadmap, and active TODO authority (within module/system surfaces).
- [x] ✅ Production‑Ready Verification scripts pass.
