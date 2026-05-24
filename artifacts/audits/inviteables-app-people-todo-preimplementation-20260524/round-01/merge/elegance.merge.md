# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/inviteables-app-people-todo-preimplementation-20260524/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Tighten the TODO before implementation by naming the canonical projection/read-model owner, retiring or converting the current read-time assembler path, and defining a single package-owned refresh/materialization boundary for all producer events.`

## Merged Findings
### F-A5834BFF [high] Projection writes lacked one canonical materializer boundary
- **Reviewers:** elegance-structural-soundness
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require a single package-owned inviteables projector/materializer with idempotent upsert/delete semantics and source-specific adapters that only call or enqueue that boundary.
- **Rationale:** The broad trigger set is structurally risky. If each producer implements its own refresh behavior, the projection can diverge by event source, producing stale inviteables or inconsistent card semantics.

### F-D703FDDE [high] Canonical GET cutover and old assembler fate were not explicit
- **Reviewers:** elegance-structural-soundness
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a required architecture note naming the canonical GET path and the fate of InviteablePeopleService::inviteableItemsFor(), plus tests/guardrails proving GET does not read intermediate sources directly.
- **Rationale:** Without a named cutover for the old assembler, implementation can leave both the materialized projection and read-time assembly path alive, creating duplicate old/new paths likely to drift and reintroduce request-time correctness/performance regressions.

### F-EA18662F [low] Presentation mapping owner was not explicit
- **Reviewers:** elegance-structural-soundness
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a non-blocking implementation note that inviteable card labels should be normalized in one presentation/domain mapping boundary and test that superseded never renders as an exposed internal state.
- **Rationale:** A missing mapping owner can lead to repeated UI conditionals or leaking internal states into app cards, even if it is not a release blocker when behavior is tested.

## Reviewer Summaries
### elegance-structural-soundness
- **Assessment:** needs_revision
- **Recommended path:** `Tighten the TODO before implementation by naming the canonical projection/read-model owner, retiring or converting the current read-time assembler path, and defining a single package-owned refresh/materialization boundary for all producer events.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-001 Canonical GET cutover and old assembler fate were not explicit: Without a named cutover for the old assembler, implementation can leave both the materialized projection and read-time assembly path alive, creating duplicate old/new paths likely to drift and reintroduce request-time correctness/performance regressions.
  - [high] ELEGANCE-002 Projection writes lacked one canonical materializer boundary: The broad trigger set is structurally risky. If each producer implements its own refresh behavior, the projection can diverge by event source, producing stale inviteables or inconsistent card semantics.
  - [low] ELEGANCE-003 Presentation mapping owner was not explicit: A missing mapping owner can lead to repeated UI conditionals or leaking internal states into app cards, even if it is not a release blocker when behavior is tested.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

