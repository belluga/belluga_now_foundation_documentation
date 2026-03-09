## Title
Enforce Decision Adherence Gate Before Delivery

## Context
Current Delphi planning now captures stronger review structure, but delivery can still complete without a strict proof that implementation adhered to approved TODO decisions.

We need a mandatory gate where each approved decision is validated with explicit evidence before delivery is considered complete.

## Scope
- [x] Add a mandatory Decision Adherence Gate to TODO-driven execution workflow.
- [x] Require decision identifiers and a frozen decision baseline before implementation.
- [x] Require pre-delivery adherence validation table with evidence per decision.
- [x] Define exception policy: non-adherence only via explicit decision challenge/change and renewed approval.
- [x] Update rules/templates/skills to make the gate enforceable across Delphi governance surfaces.
- [x] Reclaim control over Cline planning/delivery: treat Cline plans as advisory unless they pass Delphi TODO + APROVADO + Decision Adherence gates.
- [x] Enforce `.clinerules` governance parity for TODO flow and adherence gates (not only `.agent` and selected `.cline/skills` surfaces).
- [x] Add explicit drift-fail validation for missing or stale Cline TODO-governance artifacts.

## Delivery Stages
- [ ] ⚪ Pending
- [x] ✅ Production‑Ready

## Provisional Notes (Required if Provisional)
- **Missing for production-ready:** n/a
- **Revisit criteria:** n/a
- **Dependencies unblocked:** n/a

## Out of Scope
- [ ] Broad refactors outside TODO governance and decision-adherence enforcement.
- [ ] Changes to stack-specific product workflows unrelated to decision adherence.

## Decisions
- [x] Non-adherent delivery is invalid by default.
- [x] Any deviation from approved decisions requires explicit decision-change logging and renewed approval before continuing.
- [x] Evidence format for adherence is required (`file:line`, test output, or contract/document reference).
- [x] Cline-generated plans and recommendations are advisory by default; implementation authority remains Delphi TODO + APROVADO + adherence validation.
- [x] Missing Cline TODO-governance artifacts must fail validation and block “ready” status.

## Questions To Close
- [x] None.

## Definition of Done
- [x] Workflow includes explicit Decision Adherence Gate before completion.
- [x] Rule blocks delivery when any approved decision is missing adherence evidence.
- [x] TODO template includes decision IDs, baseline freeze, and adherence validation section.
- [x] Exception/challenge path is explicitly documented and requires renewed approval.
- [x] Skill files for touched rule/workflow are synchronized with updated behavior.
- [x] Cline governance artifacts explicitly reflect the same TODO/adherence controls (or Cline is explicitly constrained as advisory-only with blocking guardrails).
- [x] Validation scripts/checks detect Cline governance drift and fail with actionable errors.
- [x] `bash delphi-ai/tools/verify_context.sh` passes after updates.

## Commands (Run Locally)
- `bash delphi-ai/tools/verify_context.sh`
- `git -C delphi-ai status --short`

## Files Expected (Optional)
- `delphi-ai/workflows/docker/todo-driven-execution-method.md`
- `delphi-ai/rules/docker/shared/todo-driven-execution-model-decision.md`
- `delphi-ai/rules/laravel/shared/todo-driven-execution-model-decision.md`
- `delphi-ai/templates/todo_template.md`
- `delphi-ai/main_instructions.md`
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/.clinerules/00-main-instructions.md`
- `delphi-ai/.clinerules/model-decision/*.md`
- `delphi-ai/.clinerules/workflows/*.md`
- `delphi-ai/tools/verify_adherence_sync.sh`
- `delphi-ai/.cline/MANIFEST.md`

## Outcome Notes
- Added Decision Adherence Gate requirements to canonical Delphi TODO workflow/rules/templates and mirrored skill surfaces.
- Added explicit Cline authority boundary (advisory-only) in Cline bootloader/instructions and new Cline TODO governance artifacts.
- Added Cline governance drift checks to `tools/verify_adherence_sync.sh` so missing TODO-control artifacts fail verification.
- `bash delphi-ai/tools/verify_context.sh` passed; existing laravel-app `.agent/rules/shared/*` permission warnings remain environmental and pre-existing.
