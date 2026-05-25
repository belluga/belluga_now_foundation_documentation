# TODO (Fast Follow): Query Topology Guardrail Hardening and Existing Surface Audit

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. This TODO owns the transversal hardening required so broad-fetch / in-memory-target-derivation anti-patterns stop escaping both new implementation work and already-existing code paths.
**Owners:** Delphi (Guardrails) + Laravel / Runtime
**Goal:** evolve the current performance guard from exact-lookup scrutiny into a generic query-topology guardrail, and run it against existing code surfaces so “query by chunk + target derivation in memory” is detected and classified across the codebase, not only in newly touched push work.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The push-audience structural review exposed a broader quality gap:

- the current performance guardrails are good at flagging `exact lookup` anti-patterns such as `list-then-filter`, page-walking, and broad fetches for exact entity discovery;
- they do **not** yet treat `broad parent fetch + in-memory target derivation` as a first-class anti-pattern category;
- that gap is not specific to push. It can appear in:
  - push fan-out;
  - follower/profile targeting;
  - projections and jobs;
  - exports;
  - realtime lanes;
  - repository and endpoint query paths generally;
- the problem is architectural and transversal. The guardrail must detect the topology itself, and the codebase must be audited for already-existing occurrences rather than only preventing future diffs.

This TODO exists alongside the current push fast-follow so the learning is consolidated into Delphi guardrails and into a concrete audit of already-existing application surfaces.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-03`
- **Why this is the right current slice:** the missing behavior is already diagnosed clearly: a class of query-topology smell escaped the current review floor and needs both prevention and retrospective audit.
- **Direct-to-TODO rationale:** this is process/runtime hardening, not product discovery.

## Contract Boundary

- This TODO defines **WHAT** must be added to the guardrails and what the existing-surface audit must cover.
- It does not promise to fix every historical finding in the same slice; it must at minimum classify them and open bounded follow-up TODOs/issues where needed.
- It may modify `delphi-ai` skills/tools plus downstream documentation/audit artifacts required to operationalize the guard.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Structural`, `Cross-Repo`, `Guardrail`, `Performance`
- **Next exact step:** define the generalized anti-pattern class, update the Delphi guard/skill/tooling to detect it, then run the first audit pass against existing Laravel/runtime surfaces and record findings with severity and follow-up ownership.

## Complexity / Execution Profile

- **Complexity:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `delphi-ai + laravel + foundation docs`

## Canonical Module Anchors

- **Primary module docs:**
  - `foundation_documentation/modules/system_architecture_principles.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Decision Baseline (Frozen 2026-05-10)

- [x] `D-01` The guardrail must cover query topology generically, not only exact-lookup endpoints.
- [x] `D-02` `Broad parent fetch + in-memory target derivation` is a first-class anti-pattern and must be named explicitly.
- [x] `D-03` The guard must detect suspicious chunk/cursor/list patterns even when the code is functionally correct.
- [x] `D-04` The hardening must include an audit of already-existing code surfaces, not only changed files in a new diff.
- [x] `D-05` Existing findings do not all need to be fixed in this same slice, but they must be surfaced, classified, and routed into bounded follow-up work.
- [x] `D-06` This hardening applies across areas of code generally, not only push-specific paths.
- [x] `D-07` Trigger-source governance is a valid deterministic guard target: when side effects are required to hang off canonical post-commit domain/activity events, direct controller/UI-triggered authoring paths should be detectable and challengeable by guardrail tooling.

## Scope

- Evolve Delphi performance/query-review guardrails so they cover:
  - exact lookup;
  - targeted fan-out;
  - broad fetch / in-memory target derivation;
  - parent-document fetch for child-target extraction;
  - unstable offset pagination where stable cursor materialization is required.
- Add deterministic helper support where needed so the guard can scan not only git-modified code but also broader existing surfaces.
- Add deterministic helper support where needed so the guard can also classify unauthorized trigger-source patterns such as controller/UI-originated side effects that bypass canonical post-commit events.
- Run an initial audit pass against existing Laravel/runtime surfaces and record findings.
- Convert material findings into:
  - immediate fixes when bounded and cheap;
  - or explicit follow-up TODOs / issue cards when larger.
- Record the approved generalized anti-pattern language in the appropriate Delphi skill/docs.

## Out of Scope

- Re-architecting every historical hotspot in one sweep.
- Replacing case-by-case performance reasoning with a fully automatic static proof system.
- Flutter UI-only render performance smells that are unrelated to data/query topology.

## References

- `delphi-ai/skills/endpoint-performance-scrutiny/SKILL.md`
- `delphi-ai/skills/runtime-load-stress-validation/SKILL.md`
- `delphi-ai/skills/test-quality-audit/SKILL.md`
- `delphi-ai/tools/endpoint_performance_review_scaffold.sh`
- `delphi-ai/tools/exact_lookup_anti_pattern_audit.sh`
- `foundation_documentation/todos/active/vnext/TODO-vnext-push-provider-request-budget-hardening.md`

## Implementation Tasks

- [ ] ⚪ Define the generalized anti-pattern taxonomy for query-topology smells, including `broad parent fetch + in-memory target derivation`.
- [ ] ⚪ Update the relevant Delphi guardrail/skill documentation so this category is explicitly reviewed and not treated as an optional optimization.
- [ ] ⚪ Update the relevant Delphi guardrail/skill documentation so canonical event-source trigger rules can also be enforced/challenged deterministically where the source is formalizable.
- [ ] ⚪ Extend or add deterministic audit tooling so the scan can run on already-existing surfaces, not only git-modified files.
- [ ] ⚪ Run the first audit pass against existing Laravel/runtime query-sensitive surfaces.
- [ ] ⚪ Produce a findings ledger with severity, evidence, why-now, and recommended follow-up shape.
- [ ] ⚪ Open bounded follow-up TODOs or issue cards for any material existing hotspots that are not fixed in the same slice.
- [ ] ⚪ Back-link the final guardrail and audit evidence artifacts from this TODO.

## Acceptance Criteria

- [ ] ⚪ Delphi guardrails explicitly recognize `broad parent fetch + in-memory target derivation` as a material anti-pattern.
- [ ] ⚪ The guard can be applied to existing surfaces, not only newly changed code.
- [ ] ⚪ At least one retrospective audit pass is recorded for the current application surfaces in scope.
- [ ] ⚪ Material findings in existing code are surfaced and classified rather than left implicit.
- [ ] ⚪ Future sessions have a discoverable canonical guard to challenge this topology before code ships.
- [ ] ⚪ Future sessions have a discoverable canonical guard to challenge unauthorized trigger-source patterns when canonical event-driven side effects are required.

## Validation Steps

- [ ] Guardrail lane: prove the relevant Delphi skill/tooling now names and checks the generalized query-topology anti-pattern class.
- [ ] Audit lane: run the generalized audit against existing Laravel/runtime surfaces and record the findings ledger.
- [ ] Classification lane: prove each material finding is either fixed or routed into an explicit follow-up artifact.
- [ ] Docs lane: prove the canonical docs point future work to the new generalized guard.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current anti-pattern can be captured heuristically well enough to materially improve review quality without requiring full query-plan automation. | Existing `exact_lookup_anti_pattern_audit.sh` already proves heuristic scanning is acceptable as partial deterministic evidence. | Additional instrumentation or runtime probes would be needed earlier in the workflow. | High | Keep as Assumption |
| `A-02` | Existing Laravel/runtime surfaces are the highest-value first audit target. | Current escaped issue was in Laravel/runtime push path; similar patterns are most likely there first. | The audit might need immediate expansion into Flutter repository paths too. | Medium | Keep as Assumption |

## Execution Plan

### Touched Surfaces

- `delphi-ai/skills/endpoint-performance-scrutiny/**`
- `delphi-ai/tools/**` relevant to query/performance audit
- `foundation_documentation/**` for canonicalized guard usage and findings ledger
- audited Laravel/runtime paths discovered during execution

### Ordered Steps

1. Freeze the generalized anti-pattern vocabulary and guard boundary.
2. Update Delphi skill/tooling to reflect it.
3. Run a retrospective audit against existing surfaces.
4. Classify findings into `fix now` vs `follow-up`.
5. Publish the findings ledger and any downstream TODOs.

### Test Strategy

- **Strategy:** `evidence-first`
- **Why:** this slice is guardrail/audit work; the output must prove better detection and produce actionable findings, not just code churn.

### Runtime / Rollout Notes

- This TODO is guardrail and audit hardening. It is allowed to emit follow-up TODOs for product-code fixes.
- If the audit discovers a severe, user-facing hotspot in an already-active delivery lane, that hotspot may be promoted into the active lane explicitly instead of deferred silently.
