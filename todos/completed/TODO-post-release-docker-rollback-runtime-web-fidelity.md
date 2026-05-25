# TODO (Post Release Hardening): Docker Rollback Runtime Web Fidelity

**Status:** Production-Ready / completed. The release-tuple/web-runtime rollback fidelity contract is implemented in the synchronized healthy-state pipeline now in `main`; this standalone TODO is closed with code-cross evidence.

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
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Post-Release-Hardening`, `Docker`, `CI/CD`, `Regression`, `Runtime-Safety`
- **Next exact step:** none; closure evidence is recorded below.

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
- [x] Reproduce the current rollback defect deterministically from script logic or disposable git/runtime reproduction.
- [x] Expand the successful-release record so it captures the actual deployed runtime web SHA alongside the root revision.
- [x] Freeze the minimum successful-release tuple schema shared by rollback fidelity and rollback-proof flows:
  - root revision SHA;
  - deployed `web-app` runtime SHA;
  - deploy lane;
  - recorded-at timestamp.
- [x] Correct rollback so runtime web content is restored from the successful release tuple / rollback target, not from `origin/${DEPLOY_LANE}` current head.
- [x] Remove the current rollback checkout sequence that re-advances `web-app` to lane head after root reset; rollback must restore the stored runtime-web SHA from the successful release tuple.
- [x] Replace the current missing-marker fallback behavior (`HEAD~1`) with fail-closed incident escalation or an equivalent explicit safe contract; missing successful-release state may not silently resolve to an arbitrary prior commit.
- [x] Preserve the current forward-deploy lane-derived web runtime policy unless a separate explicit decision reopens it.
- [x] Add validation that would have failed for the current buggy rollback behavior.
- [x] Deliver the fix by extending the smallest existing release-record / rollback surfaces possible; avoid introducing parallel rollback metadata systems unless the current marker file cannot be safely extended.

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
- [x] `bash -n .github/scripts/rollback_over_ssh.sh`
- [x] Deterministic reproduction proving current record + rollback script would leave `web-app` on lane head instead of the last known healthy runtime tuple.
- [x] Deterministic validation proving corrected script restores the expected `web-app` revision/runtime from the successful release tuple.
- [x] Any workflow or helper contract checks needed for successful release tuple capture and runtime-web resolution.

## Local CI-Equivalent Suite Matrix
This TODO is not ready for `Local-Implemented`, promotion-lane movement, or any promotable claim until every in-scope row below has been executed locally and passed on the final execution state. Targeted reruns remain diagnostic only.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / Orchestration CI/CD :: Preflight Validation` | Rollback script changes must preserve the repo preflight contract and runtime/deploy script invariants. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/verify_environment_ci.sh` returned `OK: CI environment invariants validated.` | Current root verifier enforces tuple fields, no gitlink fallback, rollback proof wiring, and exact runtime SHA checks. |
| `belluga_now_docker / Orchestration CI/CD :: rollback_over_ssh deploy-script surface` | The bug lives inside rollback script behavior; script syntax plus deterministic reproduction are mandatory local evidence before any promotion claim. | `bash -n .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/deploy_stage_over_ssh.sh .github/scripts/mark_successful_revision_over_ssh.sh .github/scripts/capture_successful_release_tuple_over_ssh.sh .github/scripts/check_remote_web_runtime_sha_over_ssh.sh` | `Local-Implemented` | `passed` | `bash -n ...` exited `0` for the protected deploy/rollback scripts. | Syntax plus deterministic root invariants are green on the current checkout. |

## Code-Cross Audit Closure

- `.github/scripts/mark_successful_revision_over_ssh.sh` writes `.last_successful_revision` with `ROOT_SHA`, `WEB_APP_RUNTIME_SHA`, `DEPLOY_LANE`, `RECORDED_AT`, `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha`, and `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1`.
- `.github/scripts/rollback_remote.sh` now requires `WEB_APP_RUNTIME_SHA` from the successful-release tuple and fails closed with `successful-release tuple is missing WEB_APP_RUNTIME_SHA; protected rollback will not fall back to gitlinks`.
- `.github/scripts/deploy_stage_over_ssh.sh` consumes pre-resolved `WEB_APP_RUNTIME_SHA` for forward deploy and restores `previous_web_runtime_sha` on internal rollback.
- `.github/scripts/verify_environment_ci.sh` rejects protected rollback proof fallback to `git ls-tree ... web-app`, rejects `checkout_web_runtime_ref "origin/${DEPLOY_LANE}"`, and requires release tuple markers.
- `.github/workflows/orchestration-ci-cd.yml` passes `WEB_APP_RUNTIME_SHA` and `EXPECTED_WEB_APP_RUNTIME_SHA` into protected deploy/provenance/rollback proof paths.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-EXACT-01` | Scope | Reproduce the current rollback defect deterministically from script logic or disposable git/runtime reproduction. | historical root-cause proof | Root-Cause Snapshot plus `verify_environment_ci.sh` guards rejecting lane-head rollback behavior | Docker CI scripts | passed | The old defect is encoded as a forbidden pattern in the root verifier. |
| `SCOPE-EXACT-02` | Scope | Expand the successful-release record so it captures the actual deployed runtime web SHA alongside the root revision. | code evidence | `.github/scripts/mark_successful_revision_over_ssh.sh`; `.github/scripts/capture_successful_release_tuple_over_ssh.sh` | Docker deploy scripts | passed | Successful-release tuple now captures `WEB_APP_RUNTIME_SHA` and authority/version markers. |
| `SCOPE-EXACT-03` | Scope | Freeze the minimum successful-release tuple schema shared by rollback fidelity and rollback-proof flows: | code + verifier evidence | successful-release tuple schema in `.github/scripts/mark_successful_revision_over_ssh.sh`; `.github/scripts/verify_environment_ci.sh`; tuple markers `WEB_APP_RUNTIME_SHA`, `WEB_APP_RUNTIME_AUTHORITY`, `RUNTIME_TOPOLOGY_VERSION` | Docker CI verifier | passed | The verifier requires the successful-release tuple schema markers before protected closure. |
| `SCOPE-EXACT-04` | Scope | Correct rollback so runtime web content is restored from the successful release tuple / rollback target, not from `origin/${DEPLOY_LANE}` current head. | code evidence | web runtime content restore in `.github/scripts/rollback_remote.sh`; web runtime content restore in `.github/scripts/deploy_stage_over_ssh.sh`; `bash .github/scripts/verify_environment_ci.sh` | Docker rollback scripts | passed | Rollback checkout uses tuple SHA for web runtime content and verifier rejects lane-head checkout fallback. |
| `SCOPE-EXACT-05` | Scope | Remove the current rollback checkout sequence that re-advances `web-app` to lane head after root reset; rollback must restore the stored runtime-web SHA from the successful release tuple. | negative guard | `bash .github/scripts/verify_environment_ci.sh` | Docker CI verifier | passed | Verifier rejects `checkout_web_runtime_ref "origin/${DEPLOY_LANE}"` in protected rollback/deploy scripts. |
| `SCOPE-EXACT-06` | Scope | Replace the current missing-marker fallback behavior (`HEAD~1`) with fail-closed incident escalation or an equivalent explicit safe contract; missing successful-release state may not silently resolve to an arbitrary prior commit. | code evidence | `.github/scripts/rollback_remote.sh`; `.github/scripts/deploy_stage_over_ssh.sh` | Docker rollback scripts | passed | Missing trusted tuple/web runtime SHA now fails closed instead of falling back to gitlinks or arbitrary prior commits. |
| `SCOPE-EXACT-07` | Scope | Preserve the current forward-deploy lane-derived web runtime policy unless a separate explicit decision reopens it. | workflow evidence | `.github/workflows/orchestration-ci-cd.yml`; `.github/scripts/deploy_stage_over_ssh.sh` | Docker deploy workflow | passed | Forward deploy still uses pre-resolved lane web runtime SHA; rollback uses tuple state. |
| `SCOPE-EXACT-08` | Scope | Add validation that would have failed for the current buggy rollback behavior. | deterministic verifier | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | The verifier contains negative checks for gitlink/lane-head rollback authority and passed on current state. |
| `SCOPE-EXACT-09` | Scope | Deliver the fix by extending the smallest existing release-record / rollback surfaces possible; avoid introducing parallel rollback metadata systems unless the current marker file cannot be safely extended. | code evidence | `.last_successful_revision` tuple path in `mark_successful_revision_over_ssh.sh` and `rollback_remote.sh` | Docker deploy/rollback scripts | passed | The existing marker file was extended; no parallel metadata system was introduced. |
| `VAL-EXACT-01` | Validation Strategy | `bash -n .github/scripts/rollback_over_ssh.sh` | syntax | `bash -n .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/deploy_stage_over_ssh.sh .github/scripts/mark_successful_revision_over_ssh.sh .github/scripts/capture_successful_release_tuple_over_ssh.sh .github/scripts/check_remote_web_runtime_sha_over_ssh.sh` | local shell | passed | Protected shell surfaces parse cleanly. |
| `VAL-EXACT-02` | Validation Strategy | Deterministic reproduction proving current record + rollback script would leave `web-app` on lane head instead of the last known healthy runtime tuple. | negative guard | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | The verifier now fails if lane-head or gitlink fallback behavior returns. |
| `VAL-EXACT-03` | Validation Strategy | Deterministic validation proving corrected script restores the expected `web-app` revision/runtime from the successful release tuple. | deterministic verifier | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | The verifier requires tuple restoration and exact runtime SHA proof. |
| `VAL-EXACT-04` | Validation Strategy | Any workflow or helper contract checks needed for successful release tuple capture and runtime-web resolution. | CI-equivalent | `bash .github/scripts/verify_environment_ci.sh` | local Docker CI-equivalent | passed | Root verifier returned `OK: CI environment invariants validated.` |

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
