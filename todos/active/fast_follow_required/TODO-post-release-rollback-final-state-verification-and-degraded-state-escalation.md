# TODO (Fast Follow): Post-Rollback Final-State Verification + Degraded-State Escalation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Provisional. The shared rollback proof/degraded-state contract is represented in the synchronized healthy-state implementation now in `main`, but this standalone TODO still needs row-level completion evidence and any live rollback-drill/non-applicability decision before closure.
**Current delivery stage:** `Provisional`
**Owners:** Delphi, DevOps/Platform
**Goal:** ensure that after any rollback on a live lane, the pipeline re-proves the restored environment through the same release-safe external contract required by the forward path; if that proof cannot be completed, the workflow fails closed into explicit incident/degraded state.
**Next exact step:** reconcile this standalone TODO with the governing healthy-state evidence matrix and decide whether a live rollback drill is required before closure or explicitly non-applicable for the completed forward-only promotion runs.
**Ownership note:** this TODO owns rollback-path proof acceptance semantics, including the rule that rollback may not treat `initialize=403` or landlord root-health fallback as terminal healthy recovery. Forward-path bootstrap-vs-unexpected-`403` distinction remains owned by `TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md`.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

- The fail-closed pipeline investigation and its triple external audit concluded that rollback exit and local readiness probes are not sufficient evidence of healthy final state.
- Current rollback automation has two executors that can mutate a live environment:
  - internal rollback path inside `.github/scripts/deploy_stage_over_ssh.sh`
  - external rollback path via `.github/scripts/rollback_over_ssh.sh`
- The previous workflow epilogues accepted terminal messages such as `service may remain degraded`, which is not an acceptable release-safety outcome.
- `2026-05-15` stage run `25902136464` failed after mutation smoke, ran external rollback, and then terminated without re-proving the restored environment through provenance + navigation.

## Current Implementation Checkpoint

- Local candidate now computes whether rollback proof must run after:
  - internal rollback success inside `.github/scripts/deploy_stage_over_ssh.sh`
  - external rollback success via `.github/scripts/rollback_over_ssh.sh`
- Local candidate now rejects rollback proof on untrusted targets:
  - no trusted successful-release tuple;
  - missing restored target revision;
  - unresolved restored flutter SHA;
  - internal rollback revision drift relative to the trusted successful-release target.
- Local candidate now re-runs branch-appropriate proof after rollback:
  - `stage`: initialize `200` + public-edge probe + provenance + readonly smoke + mutation smoke;
  - `main`: initialize `200` + public-edge probe + provenance + mutation hard-block + readonly smoke.
- Local candidate now supports provenance verification against the restored target via `EXPECTED_FLUTTER_SHA`, instead of implicitly trusting the current runner checkout.
- Remaining work on this TODO is no longer local contract design; it is:
  - rerun the applicable local deterministic matrix subset on the current candidate;
  - then prove the same contract on the real `stage` lane.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `post-release-rollback-final-state-proof`
- **Direct-to-TODO rationale:** the gap is explicit and bounded: define and enforce one shared post-rollback proof contract plus degraded-state escalation semantics.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision consolidation targets:**
  - `system_architecture_principles.md`

## Scope

- [ ] Define one shared post-rollback proof contract for every rollback executor that can mutate a live lane.
- [ ] Define one canonical proof-acceptance contract shared with the forward-path success-marker gate:
  - `initialize=200` / `initialize=403` / root-health fallback may be boot or bootstrap signals;
  - they are not sufficient healthy-final-state proof by themselves;
  - rollback proof cannot accept `403` as terminal recovery success.
- [ ] Require the restored environment to pass the branch-appropriate external proof surface before the workflow can claim recovery:
  - public-edge environment probe;
  - provenance check against the restored release tuple;
  - readonly navigation smoke on live lanes;
  - mutation navigation smoke on `stage`;
  - mutation remains hard-blocked on `main`.
- [ ] Ensure the same proof contract is enforced after:
  - internal rollback inside `.github/scripts/deploy_stage_over_ssh.sh`
  - external rollback via `.github/scripts/rollback_over_ssh.sh`
- [ ] Replace best-effort degraded epilogues with explicit incident/degraded failure handling when rollback proof fails, is skipped, or cannot be run.
- [ ] Define the explicit incident recovery contract for `CP-01`:
  - stale or missing trusted tuple;
  - prior rollback failure or mixed-state runtime;
  - subsequent rerun cannot trust host state or success-marker state without operator recovery entry point.
- [ ] Capture deterministic regression coverage for:
  - forbidden terminal success on local readiness/root-health fallback alone;
  - forbidden success when post-rollback proof is skipped;
  - fail-closed workflow outcome when restored provenance/navigation proof fails;
  - branch-appropriate smoke expectations (`stage` mutation required, `main` mutation blocked).
- [ ] Prefer one shared proof contract and one degraded-state contract over lane-specific or executor-specific duplication wherever the behavior is semantically the same.

## Preferred Minimal Design

- Implement one reusable proof runner contract parameterized by lane and phase (`forward` or `rollback`) instead of separate proof stacks.
- Keep rollback-path acceptance semantics in one place; do not let local readiness, root-health fallback, and external proof each define success independently.
- Define one operator recovery playbook for `CP-01` and related mixed-state cases rather than multiple executor-specific recovery branches.

## Out of Scope

- [ ] Release-tuple rollback fidelity itself; that remains owned by `TODO-post-release-docker-rollback-runtime-web-fidelity.md`.
- [ ] Immutable artifact design and storage strategy; that remains owned by `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`.
- [ ] Pre-migration backup/snapshot design; that remains owned by `TODO-vnext-deploy-pre-migration-backup.md`.

## Decision Baseline

- [x] `D-01` Rollback success is not equivalent to healthy final state.
- [x] `D-02` Local rollback readiness probes (`/api/v1/initialize`, root-health fallback) are necessary boot signals only; they are not release-safe final-state proof.
- [x] `D-03` Every rollback executor that can mutate a live lane must satisfy the same shared post-rollback proof contract.
- [x] `D-04` If the restored environment cannot pass the shared proof contract, the workflow must fail closed into explicit incident/degraded state.
- [x] `D-05` The proof contract must remain lane-aware: `stage` requires mutation proof, `main` forbids mutation and requires readonly proof only.
- [x] `D-06` The proof-acceptance semantics for `initialize=403` and root-health fallback must stay aligned with the forward-path successful-release gate; neither may be treated as healthy-final-state proof.
- [x] `D-07` Simplicity matters here: use one canonical proof/incident model unless a real lane difference requires divergence.

## Definition of Done

- [ ] The workflow re-runs branch-appropriate post-rollback proof after every rollback path that mutates a live lane.
- [ ] The proof contract is shared between internal and external rollback executors.
- [ ] No rollback path can terminate in a pseudo-success state without post-rollback proof.
- [ ] Degraded-state handling is explicit and deterministic.
- [ ] Deterministic regression coverage exists for skip/failure/mixed-proof cases.

## Validation Steps

- [ ] `bash .github/scripts/verify_environment_ci.sh`
- [ ] Deterministic regression coverage for workflow rollback branches and helper scripts.
- [ ] Local/static proof that post-rollback proof is wired to both rollback executors.
- [ ] Promotion-lane validation on `stage` before any `main` claim.

## Local CI-Equivalent Suite Matrix

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / Orchestration CI/CD :: Preflight Validation` | Workflow and rollback-script changes must preserve deterministic CI invariants. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed on current candidate` | `2026-05-18 local run` | Minimum static contract check for workflow/script surfaces. |
| `belluga_now_docker / Orchestration CI/CD :: rollback proof semantics` | This slice changes rollback terminal semantics and proof wiring. | deterministic local regression coverage in `verify_environment_ci.sh` + shell/YAML validation | `Local-Implemented` | `passed on current candidate (wiring/static)` | `2026-05-18 local run` | Covers tuple markers, internal-rollback outputs, rollback-proof workflow markers, degraded-state wording removal, shell syntax, and workflow YAML parse. Stage runtime proof still pending. |

## Complexity

- **Level (`small|medium|big`):** `medium`
- **Why:** the behavior change is tightly bounded to workflow/script semantics, but it touches release-critical terminal state handling across two rollback executors.

## Investigation Record

- Relevant files:
  - `.github/workflows/orchestration-ci-cd.yml`
  - `.github/scripts/deploy_stage_over_ssh.sh`
  - `.github/scripts/rollback_over_ssh.sh`
  - `.github/scripts/mark_successful_revision_over_ssh.sh`
- Confirmed current gaps:
  - current local candidate now reruns rollback proof after both rollback executors instead of stopping at rollback exit;
  - current local candidate now rejects local readiness/root-health fallback as terminal success because rollback proof requires explicit post-rollback lane proof;
  - current remaining gap is real-lane validation on `stage`, not missing local wiring of the proof contract.

## Related TODO Mapping

- Forward-path success-marker gate / unexpected `initialize=403` ownership:
  - `TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md`
- Release tuple fidelity:
  - `TODO-post-release-docker-rollback-runtime-web-fidelity.md`
- Structural prerequisite:
  - `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`
- Data safety prerequisite:
  - `TODO-vnext-deploy-pre-migration-backup.md`
- Governing invariant:
  - `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`
