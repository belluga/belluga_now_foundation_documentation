## Title
YCombinator Prompt-Structure Upgrades for Delphi-AI

## Context
Delphi-AI already has strong governance and workflow discipline, but it lacks a first-class, enforceable review structure that consistently outputs issue-level tradeoffs, recommendation economics, uncertainty metadata, and adaptive checkpoint cadence.

This TODO upgrades Delphi-AI prompt structure using the reference prompt as input, while preserving Delphi's architecture-first governance model.

## Scope
- [x] Define and implement a canonical "Plan Review Gate" structure for engineering reviews.
- [x] Enforce per-issue output contract (issue id, evidence, options including do-nothing, recommendation, tradeoff economics).
- [x] Introduce risk-adaptive review depth (`small | medium | big`) with explicit checkpoint behavior.
- [x] Add required uncertainty metadata (assumptions, unknowns, confidence) and failure-mode analysis at planning stage.
- [x] Wire the new review behavior into Delphi-AI methods/rules/skills so it is triggerable and not optional prose.
- [x] Keep the changes project-agnostic and aligned with existing Delphi constitutional principles.

## Delivery Stages
- [ ] ⚪ Pending
- [ ] 🟡 Provisional
- [x] ✅ Production‑Ready

## Provisional Notes (Required if Provisional)
- **Missing for production-ready:** n/a (targeting direct production-ready governance changes)
- **Revisit criteria:** n/a
- **Dependencies unblocked:** n/a

## Out of Scope
- [ ] Rewriting stack-specific Flutter/Laravel domain workflows unrelated to review/planning governance.
- [ ] Changing business/domain contracts in `foundation_documentation/` beyond TODO artifact updates.
- [ ] Non-conceptual cleanups not tied to the prompt-structure objective.

## Decisions
- [x] Use Delphi governance as baseline and import only high-signal concepts (not literal copy) from the reference prompt.
- [x] Keep APROVADO gate and TODO discipline intact; improvements augment planning quality, not bypass controls.
- [x] Implement in `delphi-ai` branch `ycombinator-improvements`.
- [x] Plan Review Gate trigger scope: run by default for `medium|big` implementation/review/refactor work; allow lightweight handling for trivial work and maintenance lane unless risk escalates.

## Questions To Close
- [x] Should the Plan Review Gate run only for review/refactor/complex implementation scopes, or for every implementation task by default? Resolved: complex/default `medium|big`, lightweight for trivial.

## Definition of Done
- [x] New/updated Delphi-AI governance artifacts define a mandatory review output schema with tradeoff economics and recommendation format.
- [x] Governance artifacts define risk-adaptive depth and checkpoint policy.
- [x] Governance artifacts define mandatory assumptions/unknowns/confidence + failure-mode section for planning.
- [x] Rule/workflow/skill linkage is consistent for triggerability.
- [x] `bash delphi-ai/tools/verify_context.sh` passes after updates.

## Commands (Run Locally)
- `bash delphi-ai/tools/verify_context.sh`
- `git -C delphi-ai status --short`

## Files Expected (Optional)
- `delphi-ai/workflows/docker/todo-driven-execution-method.md`
- `delphi-ai/rules/docker/shared/todo-driven-execution-model-decision.md`
- `delphi-ai/templates/todo_template.md`
- `delphi-ai/review_session.md`
- `delphi-ai/skills/**` (only if linkage updates are needed)

## Outcome Notes
- Implemented a risk-adaptive Plan Review Gate in Delphi governance with mandatory `medium|big` coverage.
- Added issue-card economics (`A/B/C` including do-nothing) and required failure-mode + uncertainty sections.
- Updated workflow/rule/skill surfaces and TODO template so the structure is enforceable, not advisory.
