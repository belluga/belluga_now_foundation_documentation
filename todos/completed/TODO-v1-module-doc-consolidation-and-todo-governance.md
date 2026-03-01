# TODO (V1): Module-First Documentation Consolidation + TODO Governance Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Architecture/Docs Team
**Objective:** Make module documents the canonical source for conceptual plans and stable decisions, while keeping TODOs tactical/execution-only artifacts with mandatory module consolidation on completion.

---

## Scope
- Update Delphi governance artifacts (templates/workflows/rules/skills) to enforce:
  - module-anchor requirement for tactical TODOs;
  - module consolidation requirement before marking TODO `Completed`.
- Update module template to include canonical sections for:
  - conceptual delivery plan;
  - canonical decisions;
  - TODO linkage and decision promotion trace.
- Consolidate Events/Agenda knowledge from completed TODOs into module documentation.
- Resolve ambiguity/conflict in Events/Agenda docs against runtime/package truth.

---

## Out of Scope
- Runtime code refactors in Laravel/Flutter/Web.
- Rewriting all historical TODO narratives; only canonicalization and cross-linking are required.
- Full domain sweep across unrelated modules in one pass.

---

## Complexity
- **Level (`small|medium|big`):** big
- **Checkpoint policy:** section-by-section
- **Why this level:** touches governance surface (`delphi-ai`) + canonical project docs + conflict resolution against live code truth.

---

## Decisions
- [x] ✅ Production‑Ready `D-01` Module docs become canonical for conceptual architecture and stable decisions; TODOs remain tactical execution + evidence only.
- [x] ✅ Production‑Ready `D-02` Every tactical TODO must declare module anchors (`Canonical Module(s)`) before implementation starts.
- [x] ✅ Production‑Ready `D-03` TODO completion requires module consolidation gate: promoted decisions + architecture updates + conflict resolution record.
- [x] ✅ Production‑Ready `D-04` Events/Agenda canonical truth follows `belluga_events` package contract (occurrence-first, `location/place_ref`, event_parties boundary, capability gating).

## Decision Baseline (Frozen Before Implementation)
- [x] ✅ Production‑Ready `D-01` Canonical source split (Module = architecture, TODO = execution).
- [x] ✅ Production‑Ready `D-02` Module-anchor gate enforced by TODO workflow/template/rule.
- [x] ✅ Production‑Ready `D-03` Completion gate requires module sync evidence.
- [x] ✅ Production‑Ready `D-04` Events/Agenda module reflects current package/runtime truth and supersedes conflicting TODO statements.

---

## Questions To Close
- [x] ✅ Production‑Ready Consolidation for this cycle is restricted to Events/Agenda (first-pass high-value slice); broader rollout remains follow-up work.

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
  - **Evidence:** governance currently allows completed TODO decisions to remain outside module canonical docs
  - **Why it matters now:** creates drift and audit ambiguity
  - **Option A (Recommended):** enforce module-first canonicalization in workflow/rules/templates + consolidate Events/Agenda now
    - **Effort:** medium
    - **Risk:** low
    - **Blast radius:** cross-module documentation process
    - **Maintenance burden:** low
  - **Option B (Alternative):** guidance-only update (README note), no gate
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** local
    - **Maintenance burden:** medium
  - **Option C (Do Nothing):** keep current behavior
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** cross-module (drift grows)
    - **Maintenance burden:** high
  - **Recommendation:** A

### Failure Modes & Edge Cases
- [x] ✅ Production‑Ready Old TODO links break after consolidation/moves.
- [x] ✅ Production‑Ready Module docs duplicate tactical implementation details.
- [x] ✅ Production‑Ready Cline/Codex workflow mismatch if canonical + compatibility surfaces are not synchronized.

### Uncertainty Register
- [x] ✅ Production‑Ready **Assumption:** Events/Agenda module docs are the highest-value first consolidation target.
- [x] ✅ Production‑Ready **Unknown:** residual conflicts in other modules not scanned in this cycle.
- [x] ✅ Production‑Ready **Confidence:** medium-high.

---

## Tasks
- [x] ✅ Production‑Ready Update `delphi-ai/templates/module_template.md` for conceptual-plan + canonical-decisions structure.
- [x] ✅ Production‑Ready Update `delphi-ai/templates/todo_template.md` to require module anchors + module consolidation record at close.
- [x] ✅ Production‑Ready Update TODO-driven workflow/rules/skills (canonical + Cline compatibility surfaces).
- [x] ✅ Production‑Ready Consolidate Events/Agenda module doc with decisions promoted from completed TODOs and package truth.
- [x] ✅ Production‑Ready Resolve identified doc conflicts (`venue_id` legacy statements, location model, scope boundaries).
- [x] ✅ Production‑Ready Sync cross-links between TODOs and module docs.
- [x] ✅ Production‑Ready Run context/adherence verification and capture evidence.

---

## Decision Adherence Validation (Mandatory Before Delivery)
- | Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
- | --- | --- | --- | --- |
- | `D-01` | Adherent | `foundation_documentation/modules/events_module.md`; `foundation_documentation/modules/agenda_and_action_planner_module.md`; `foundation_documentation/modules/map_poi_module.md` | Canonical module docs now hold stable decisions/anchors. |
- | `D-02` | Adherent | `delphi-ai/templates/todo_template.md`; `delphi-ai/workflows/docker/todo-driven-execution-method.md`; `delphi-ai/rules/docker/shared/todo-driven-execution-model-decision.md` | Module-anchor requirement enforced in template + workflow + rule. |
- | `D-03` | Adherent | `delphi-ai/templates/todo_template.md`; `delphi-ai/workflows/docker/todo-driven-execution-method.md`; `delphi-ai/.clinerules/model-decision/shared-todo-driven-execution.md` | Module consolidation gate added to canonical + compatibility surfaces. |
- | `D-04` | Adherent | `laravel-app/packages/belluga/belluga_events/README.md`; `foundation_documentation/system_roadmap.md`; `foundation_documentation/submodule_laravel-app_summary.md`; `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-and-agenda-frontend.md` | Drift fixed for `location + place_ref`, occurrence-first, and boundary wording. |

---

## Delivery Confidence Gate (Required for `✅ Production-Ready`)
- [x] ✅ Production‑Ready **Runtime impact classified:** none (documentation/governance only)
- [x] ✅ Production‑Ready **Operational checks run (if runtime-impacting):** N/A
- [x] ✅ Production‑Ready **Evidence artifacts recorded:** command outputs in session + git diff evidence
- [x] ✅ Production‑Ready **Confidence stated:** medium-high + residual risk: legacy docs outside Events/Agenda may still hold drift
- [x] ✅ Production‑Ready **Release readiness outcome:** ready

---

## Definition of Done
- [x] ✅ Production‑Ready Governance artifacts enforce module-first canonicalization for conceptual plans/decisions.
- [x] ✅ Production‑Ready Events/Agenda module documentation is consistent with delivered runtime/package truth.
- [x] ✅ Production‑Ready TODO process explicitly requires module consolidation before completion.
- [x] ✅ Production‑Ready `verify_context.sh` and `verify_adherence_sync.sh` pass.
