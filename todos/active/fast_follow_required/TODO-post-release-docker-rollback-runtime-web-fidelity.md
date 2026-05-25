# TODO (Post Release Hardening): Docker Rollback Runtime Web Fidelity

**Status:** Provisional. The release-tuple/web-runtime rollback fidelity contract is represented in the synchronized healthy-state implementation now in `main`, but this standalone TODO still needs row-level completion evidence before closure.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production promotion on `2026-05-04` exposed a rollback defect in `belluga_now_docker`.

Observed runtime:
- The production lane reported failure and triggered rollback logic.
- The deployed runtime that remained live still matched the just-promoted web runtime instead of the prior healthy deployment.
- In this incident the failed smoke was later proven false, but the rollback contract is still wrong.
- The current successful-release record stores only the root SHA in `.last_successful_revision`, even though forward deploy detaches `web-app` to lane head; root SHA alone does not identify the live successful web runtime.

Investigation found concrete causes in the current release record + rollback contract:
- `rollback_over_ssh.sh` resets the root repo to the rollback target revision.
- After that, it unconditionally checks out `web-app` at `origin/${DEPLOY_LANE}`.
- `mark_successful_revision_over_ssh.sh` records only the root revision, not the actual deployed `web-app` runtime revision.
- On `main`, this reintroduces the latest lane web runtime even during rollback, so rollback is not revision-faithful.

This TODO exists to restore real rollback semantics for runtime web content.

## Delivery Status Canon
- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Post-Release-Hardening`, `Docker`, `CI/CD`, `Regression`, `Runtime-Safety`
- **Next exact step:** reconcile this TODO with the synchronized healthy-state implementation now in `main`; release tuples now carry exact web runtime SHA and authority/version markers, but this standalone TODO must still receive row-level completion evidence before closure.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `post-release-docker-rollback-runtime-web-fidelity`
- **Direct-to-TODO rationale:** one bounded deploy-hardening slice restores the intended rollback guarantee without broadening promotion policy or normal forward-deploy behavior.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision consolidation targets:**
  - `system_architecture_principles.md`

## Scope
- [ ] Reproduce the current rollback defect deterministically from script logic or disposable git/runtime reproduction.
- [ ] Expand the successful-release record so it captures the actual deployed runtime web SHA alongside the root revision.
- [ ] Freeze the minimum successful-release tuple schema shared by rollback fidelity and rollback-proof flows:
  - root revision SHA;
  - deployed `web-app` runtime SHA;
  - deploy lane;
  - recorded-at timestamp.
- [ ] Correct rollback so runtime web content is restored from the successful release tuple / rollback target, not from `origin/${DEPLOY_LANE}` current head.
- [ ] Remove the current rollback checkout sequence that re-advances `web-app` to lane head after root reset; rollback must restore the stored runtime-web SHA from the successful release tuple.
- [ ] Replace the current missing-marker fallback behavior (`HEAD~1`) with fail-closed incident escalation or an equivalent explicit safe contract; missing successful-release state may not silently resolve to an arbitrary prior commit.
- [ ] Preserve the current forward-deploy lane-derived web runtime policy unless a separate explicit decision reopens it.
- [ ] Add validation that would have failed for the current buggy rollback behavior.
- [ ] Deliver the fix by extending the smallest existing release-record / rollback surfaces possible; avoid introducing parallel rollback metadata systems unless the current marker file cannot be safely extended.

## Preferred Minimal Design

- Keep one release record at the deploy root.
- Make that record shell-readable without adding a second parser stack if possible.
- During migration, allow one backward-compatible read path for legacy single-SHA content, then converge on the tuple format.
- Fix rollback target selection and `web-app` restoration inside the current rollback script path instead of adding a second rollback mechanism.

## Out of Scope
- [ ] Reworking lane-promotion policy.
- [ ] Reworking the normal forward deploy `web-app` lane override.
- [ ] Reopening the smoke that triggered the incident unless a new product failure is found.
- [ ] Broad deploy-script refactors unrelated to rollback fidelity.

## Decision Baseline
- [x] `D-01` Rollback must restore the previously healthy runtime, not a mixed state.
- [x] `D-02` Revision fidelity matters for submodules/runtime web content during rollback, not only for the root repo SHA.
- [x] `D-03` Lane-derived web runtime override is acceptable for forward deploy, but not when it defeats rollback-target fidelity.
- [x] `D-04` When forward deploy uses lane-derived runtime web content, the successful-release record must capture that runtime web SHA or an equivalent release tuple.
- [x] `D-05` Missing successful-release marker state is not permission to fall back to `HEAD~1`; rollback target absence must fail closed or escalate explicitly.
- [x] `D-06` The minimum trusted release tuple fields are root SHA, deployed `web-app` runtime SHA, lane, and recorded-at timestamp.
- [x] `D-07` Prefer extending the current successful-release record and rollback flow over creating a second rollback bookkeeping mechanism.

## Root-Cause Snapshot
- Current rollback path:
  1. capture rollback target revision from `.last_successful_revision` or explicit input
  2. `git reset --hard <rollback-target>`
  3. `git submodule update --init --recursive`
  4. override `web-app` with `origin/${DEPLOY_LANE}`
  5. if successful-release marker state is absent, external rollback falls back to `HEAD~1`
- Failure mode:
  - step 4 discards the rollback target's `web-app` runtime and reapplies the latest lane web runtime.
  - the rollback target record is root-only, so it cannot describe the actual last known healthy `web-app` runtime when forward deploy detached `web-app` to lane head.
  - step 5 does not mean "last known good"; it means "one commit before whatever happens to be checked out now," which is not a safe rollback contract.

## Validation Strategy
- [ ] `bash -n .github/scripts/rollback_over_ssh.sh`
- [ ] Deterministic reproduction proving current record + rollback script would leave `web-app` on lane head instead of the last known healthy runtime tuple.
- [ ] Deterministic validation proving corrected script restores the expected `web-app` revision/runtime from the successful release tuple.
- [ ] Any workflow or helper contract checks needed for successful release tuple capture and runtime-web resolution.

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
  - successful-release capture stores only `git rev-parse HEAD`, which does not encode the lane-derived `web-app` runtime revision

## Follow-up Relationship

- This TODO owns release-tuple fidelity only.
- Shared post-rollback proof and degraded-state escalation are owned by `TODO-post-release-rollback-final-state-verification-and-degraded-state-escalation.md`.
- Immutable artifact restoration remains the structural follow-up in `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`.
- Forward-path success-marker gate hardening must not outrun this TODO; otherwise rollback can be invoked more often while still targeting stale marker / lane-head runtime semantics.

## Store Release Relationship
This is post-release hardening for deploy safety. It is not a product-surface bug, but it is a release-safety regression because rollback currently fails to preserve a coherent previous healthy runtime.
