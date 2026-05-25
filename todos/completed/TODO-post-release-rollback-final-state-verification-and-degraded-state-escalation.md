# TODO (Fast Follow): Post-Rollback Final-State Verification + Degraded-State Escalation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Production-Ready / completed. The shared rollback proof/degraded-state contract is implemented in the synchronized healthy-state workflow now in `main`; this standalone TODO is closed with row-level code-cross evidence.
**Current delivery stage:** `Production-Ready`
**Owners:** Delphi, DevOps/Platform
**Goal:** ensure that after any rollback on a live lane, the pipeline re-proves the restored environment through the same release-safe external contract required by the forward path; if that proof cannot be completed, the workflow fails closed into explicit incident/degraded state.
**Next exact step:** none; closure evidence is recorded below.
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

- [x] Define one shared post-rollback proof contract for every rollback executor that can mutate a live lane.
- [x] Define one canonical proof-acceptance contract shared with the forward-path success-marker gate:
  - `initialize=200` / `initialize=403` / root-health fallback may be boot or bootstrap signals;
  - they are not sufficient healthy-final-state proof by themselves;
  - rollback proof cannot accept `403` as terminal recovery success.
- [x] Require the restored environment to pass the branch-appropriate external proof surface before the workflow can claim recovery:
  - public-edge environment probe;
  - provenance check against the restored release tuple;
  - readonly navigation smoke on live lanes;
  - mutation navigation smoke on `stage`;
  - mutation remains hard-blocked on `main`.
- [x] Ensure the same proof contract is enforced after:
  - internal rollback inside `.github/scripts/deploy_stage_over_ssh.sh`
  - external rollback via `.github/scripts/rollback_over_ssh.sh`
- [x] Replace best-effort degraded epilogues with explicit incident/degraded failure handling when rollback proof fails, is skipped, or cannot be run.
- [x] Define the explicit incident recovery contract for `CP-01`:
  - stale or missing trusted tuple;
  - prior rollback failure or mixed-state runtime;
  - subsequent rerun cannot trust host state or success-marker state without operator recovery entry point.
- [x] Capture deterministic regression coverage for:
  - forbidden terminal success on local readiness/root-health fallback alone;
  - forbidden success when post-rollback proof is skipped;
  - fail-closed workflow outcome when restored provenance/navigation proof fails;
  - branch-appropriate smoke expectations (`stage` mutation required, `main` mutation blocked).
- [x] Prefer one shared proof contract and one degraded-state contract over lane-specific or executor-specific duplication wherever the behavior is semantically the same.

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

- [x] The workflow re-runs branch-appropriate post-rollback proof after every rollback path that mutates a live lane.
- [x] The proof contract is shared between internal and external rollback executors.
- [x] No rollback path can terminate in a pseudo-success state without post-rollback proof.
- [x] Degraded-state handling is explicit and deterministic.
- [x] Deterministic regression coverage exists for skip/failure/mixed-proof cases.

## Validation Steps

- [x] `bash .github/scripts/verify_environment_ci.sh`
- [x] Deterministic regression coverage for workflow rollback branches and helper scripts.
- [x] Local/static proof that post-rollback proof is wired to both rollback executors.
- [x] Promotion-lane validation on `stage` before any `main` claim.

## Local CI-Equivalent Suite Matrix

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / Orchestration CI/CD :: Preflight Validation` | Workflow and rollback-script changes must preserve deterministic CI invariants. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/verify_environment_ci.sh` returned `OK: CI environment invariants validated.` | Current verifier enforces rollback proof guards for stage and production. |
| `belluga_now_docker / Orchestration CI/CD :: rollback proof semantics` | This slice changes rollback terminal semantics and proof wiring. | deterministic local regression coverage in `verify_environment_ci.sh` plus shell/YAML validation | `Local-Implemented` | `passed` | `bash .github/scripts/verify_environment_ci.sh`; `bash -n .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/deploy_stage_over_ssh.sh` | Covers tuple markers, internal rollback outputs, rollback-proof workflow markers, degraded/incident terminal handling, shell syntax, and workflow wiring. |

## Code-Cross Audit Closure

- `.github/workflows/orchestration-ci-cd.yml` contains rollback proof plans for both `stage` and `main`, with guards for trusted tuple presence, restored target revision, restored Flutter SHA, and restored web-app runtime SHA.
- The same workflow runs branch-appropriate rollback proof: `stage` includes readonly and mutation navigation smoke; `main` includes readonly proof and mutation hard-block proof.
- Terminal failure messaging now uses explicit `terminal state is explicit degraded/incident` branches for missing proof, failed proof, rollback failure, restored web runtime SHA mismatch, public-edge failure, provenance failure, navigation failure, mutation failure, and unexpected initialize failure.
- `.github/scripts/deploy_stage_over_ssh.sh` emits `INTERNAL_ROLLBACK_STATUS` and `INTERNAL_ROLLBACK_TARGET_WEB_APP_RUNTIME_SHA`, so internal rollback is a first-class proof executor.
- `.github/scripts/rollback_remote.sh` is the external rollback executor and requires tuple-backed runtime web SHA restore.
- `.github/scripts/verify_environment_ci.sh` verifies both stage and production rollback proof guard blocks, trusted tuple checks, restored revision checks, restored Flutter SHA checks, restored web runtime SHA checks, and internal rollback target comparison.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-EXACT-01` | Scope | Define one shared post-rollback proof contract for every rollback executor that can mutate a live lane. | workflow evidence | `.github/workflows/orchestration-ci-cd.yml`; `bash .github/scripts/verify_environment_ci.sh` | Docker CI workflow | passed | Stage and production rollback proof plans share the same trusted-target and proof-gate structure. |
| `SCOPE-EXACT-02` | Scope | Define one canonical proof-acceptance contract shared with the forward-path success-marker gate: | workflow + verifier evidence | `.github/workflows/orchestration-ci-cd.yml`; `.github/scripts/verify_environment_ci.sh` | Docker CI workflow | passed | Rollback success requires external proof, not local initialize/root-health signals alone. |
| `SCOPE-EXACT-03` | Scope | Require the restored environment to pass the branch-appropriate external proof surface before the workflow can claim recovery: | workflow evidence | Stage rollback proof steps around `.github/workflows/orchestration-ci-cd.yml:543`; production rollback proof steps around `.github/workflows/orchestration-ci-cd.yml:1268` | Docker CI workflow | passed | Stage and production proof paths require public-edge, provenance, and lane-specific navigation/mutation contracts. |
| `SCOPE-EXACT-04` | Scope | Ensure the same proof contract is enforced after: | workflow evidence | `.github/workflows/orchestration-ci-cd.yml`; `.github/scripts/deploy_stage_over_ssh.sh`; `.github/scripts/rollback_remote.sh` | Docker CI workflow + scripts | passed | Internal and external rollback executors feed the same rollback-proof decision branches. |
| `SCOPE-EXACT-05` | Scope | Replace best-effort degraded epilogues with explicit incident/degraded failure handling when rollback proof fails, is skipped, or cannot be run. | workflow evidence | `.github/workflows/orchestration-ci-cd.yml` terminal branches containing `terminal state is explicit degraded/incident` | Docker CI workflow | passed | Terminal branches now fail closed with explicit degraded/incident wording. |
| `SCOPE-EXACT-06` | Scope | Define the explicit incident recovery contract for `CP-01`: | workflow + verifier evidence | `.github/workflows/orchestration-ci-cd.yml`; `.github/scripts/verify_environment_ci.sh` | Docker CI workflow | passed | Missing/stale trusted tuple and unresolved restored target are explicit no-trust rollback proof blockers. |
| `SCOPE-EXACT-07` | Scope | Capture deterministic regression coverage for: | deterministic verifier | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | Root verifier hard-blocks rollback-proof guard regressions and tuple/runtime SHA fallback regressions. |
| `SCOPE-EXACT-08` | Scope | Prefer one shared proof contract and one degraded-state contract over lane-specific or executor-specific duplication wherever the behavior is semantically the same. | workflow evidence | `.github/workflows/orchestration-ci-cd.yml`; Code-Cross Audit Closure section | Docker CI workflow | passed | Lane-specific differences are limited to stage mutation proof vs main mutation hard-block proof. |
| `DOD-EXACT-01` | Definition of Done | The workflow re-runs branch-appropriate post-rollback proof after every rollback path that mutates a live lane. | workflow evidence | `.github/workflows/orchestration-ci-cd.yml` rollback proof plan and proof jobs | Docker CI workflow | passed | Stage and production proof jobs run after rollback execution paths. |
| `DOD-EXACT-02` | Definition of Done | The proof contract is shared between internal and external rollback executors. | workflow + script evidence | `.github/scripts/deploy_stage_over_ssh.sh`; `.github/scripts/rollback_remote.sh`; `.github/workflows/orchestration-ci-cd.yml` | Docker CI scripts | passed | Both executors produce state consumed by the shared proof workflow. |
| `DOD-EXACT-03` | Definition of Done | No rollback path can terminate in a pseudo-success state without post-rollback proof. | deterministic verifier | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | Verifier enforces trusted tuple/proof guard blocks before success acceptance. |
| `DOD-EXACT-04` | Definition of Done | Degraded-state handling is explicit and deterministic. | workflow evidence | `.github/workflows/orchestration-ci-cd.yml` degraded/incident terminal branches | Docker CI workflow | passed | Terminal branches report explicit degraded/incident states on proof failure or missing trust. |
| `DOD-EXACT-05` | Definition of Done | Deterministic regression coverage exists for skip/failure/mixed-proof cases. | deterministic verifier | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | Root verifier checks rollback proof guard blocks and failure-path invariants. |
| `VAL-EXACT-01` | Validation Steps | `bash .github/scripts/verify_environment_ci.sh` | CI-equivalent | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | Returned `OK: CI environment invariants validated.` |
| `VAL-EXACT-02` | Validation Steps | Deterministic regression coverage for workflow rollback branches and helper scripts. | deterministic verifier + syntax | `bash .github/scripts/verify_environment_ci.sh`; `bash -n .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/deploy_stage_over_ssh.sh` | local Docker CI-equivalent | passed | Verifier and syntax checks passed on current checkout. |
| `VAL-EXACT-03` | Validation Steps | Local/static proof that post-rollback proof is wired to both rollback executors. | workflow + script evidence | `.github/workflows/orchestration-ci-cd.yml`; `.github/scripts/deploy_stage_over_ssh.sh`; `.github/scripts/rollback_remote.sh` | Docker CI workflow + scripts | passed | Internal and external executors route into stage/main rollback proof plans. |
| `VAL-EXACT-04` | Validation Steps | Promotion-lane validation on `stage` before any `main` claim. | promotion + navigation evidence | Stage run `26311555749` passed deploy, public-edge, provenance, readonly navigation smoke, mutation navigation smoke, and successful-release marking; Docker main run `26320227463` later completed green with the same protected workflow generation | Docker promotion lane + browser navigation smoke | passed | Stage produced browser/navigation smoke evidence before the later main claim; this is structure/runtime evidence for the rollback-proof workflow generation. |

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
