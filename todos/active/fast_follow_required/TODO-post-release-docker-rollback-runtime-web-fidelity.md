# TODO (Post Release Hardening): Docker Rollback Runtime Web Fidelity

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production promotion on `2026-05-04` exposed a rollback defect in `belluga_now_docker`.

Observed runtime:
- The production lane reported failure and triggered rollback logic.
- The deployed runtime that remained live still matched the just-promoted web runtime instead of the prior healthy deployment.
- In this incident the failed smoke was later proven false, but the rollback contract is still wrong.

Investigation found a concrete cause in the rollback script:
- `rollback_over_ssh.sh` resets the root repo to the rollback target revision.
- After that, it unconditionally checks out `web-app` at `origin/${DEPLOY_LANE}`.
- On `main`, this reintroduces the latest lane web runtime even during rollback, so rollback is not revision-faithful.

This TODO exists to restore real rollback semantics for runtime web content.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Docker`, `CI/CD`, `Regression`, `Runtime-Safety`
- **Next exact step:** add deterministic rollback reproduction/coverage, then correct rollback runtime-web resolution so the deployed web content matches the rollback target revision.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `post-release-docker-rollback-runtime-web-fidelity`
- **Direct-to-TODO rationale:** one bounded deploy-hardening slice restores the intended rollback guarantee without broadening promotion policy or normal forward-deploy behavior.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/devops_single_gate_lane_promotion.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision consolidation targets:**
  - `devops_single_gate_lane_promotion.md`

## Scope
- [ ] Reproduce the current rollback defect deterministically from script logic or disposable git/runtime reproduction.
- [ ] Correct rollback so runtime web content is restored from the rollback target revision, not from `origin/${DEPLOY_LANE}` current head.
- [ ] Preserve the current forward-deploy lane-derived web runtime policy unless a separate explicit decision reopens it.
- [ ] Add validation that would have failed for the current buggy rollback behavior.

## Out of Scope
- [ ] Reworking lane-promotion policy.
- [ ] Reworking the normal forward deploy `web-app` lane override.
- [ ] Reopening the smoke that triggered the incident unless a new product failure is found.
- [ ] Broad deploy-script refactors unrelated to rollback fidelity.

## Decision Baseline
- [x] `D-01` Rollback must restore the previously healthy runtime, not a mixed state.
- [x] `D-02` Revision fidelity matters for submodules/runtime web content during rollback, not only for the root repo SHA.
- [x] `D-03` Lane-derived web runtime override is acceptable for forward deploy, but not when it defeats rollback-target fidelity.

## Root-Cause Snapshot
- Current rollback path:
  1. capture rollback target revision
  2. `git reset --hard <rollback-target>`
  3. `git submodule update --init --recursive`
  4. override `web-app` with `origin/${DEPLOY_LANE}`
- Failure mode:
  - step 4 discards the rollback target's `web-app` runtime and reapplies the latest lane web runtime.

## Validation Strategy
- [ ] `bash -n .github/scripts/rollback_over_ssh.sh`
- [ ] Deterministic reproduction proving current script would leave `web-app` on lane head instead of rollback-target revision.
- [ ] Deterministic validation proving corrected script restores the expected `web-app` revision/runtime.
- [ ] Any workflow or helper contract checks needed for rollback target capture and runtime-web resolution.

## Local CI-Equivalent Suite Matrix
This TODO is not ready for `Local-Implemented`, promotion-lane movement, or any promotable claim until every in-scope row below has been executed locally and passed on the final execution state. Targeted reruns remain diagnostic only.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / Orchestration CI/CD :: Preflight Validation` | Rollback script changes must preserve the repo preflight contract and runtime/deploy script invariants. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `planned` | `.github/scripts/verify_environment_ci.sh` run log | Minimum local mirror for preflight surfaces touched by deploy/rollback script changes. |
| `belluga_now_docker / Orchestration CI/CD :: rollback_over_ssh deploy-script surface` | The bug lives inside rollback script behavior; script syntax plus deterministic reproduction are mandatory local evidence before any promotion claim. | `bash -lc 'bash -n .github/scripts/rollback_over_ssh.sh && bash -n .github/scripts/deploy_stage_over_ssh.sh'` | `Local-Implemented` | `planned` | shell syntax logs + deterministic reproduction artifact | The reproduction/repair proof named in `Validation Strategy` remains mandatory; syntax alone is not sufficient. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Why:** the logic change is narrowly scoped to deploy/rollback scripts, but the acceptance bar is higher because runtime rollback fidelity has to be proven against the script contract rather than inferred from static diff inspection.

## Investigation Record
- Relevant files:
  - `.github/scripts/rollback_over_ssh.sh`
  - `.github/scripts/deploy_stage_over_ssh.sh`
  - `.github/workflows/orchestration-ci-cd.yml`
- Confirmed problematic block:
  - rollback script calls `sync_web_runtime_lane()` after reset and submodule update
  - that helper checks out `web-app` to `origin/${DEPLOY_LANE}`

## Store Release Relationship
This is post-release hardening for deploy safety. It is not a product-surface bug, but it is a release-safety regression because rollback currently fails to preserve a coherent previous healthy runtime.
