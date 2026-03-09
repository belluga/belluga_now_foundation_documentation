# TODO (V1): Production Deploy Disk Guard + Rollback Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (`Validated through stage and main`)
**Owners:** Platform
**Objective:** Establish an immediate fail-safe deploy policy for `stage`/`main` that prunes recoverable Docker artifacts before build, enforces disk-space preflight gates, expands incident diagnostics, and prevents blind rollback attempts on saturated hosts.
**Complexity:** `medium`
**Checkpoint policy:** one planning checkpoint before execution approval.

---

## Goal
Prevent another `stage`/`main` deploy from leaving the environment broken due to host disk exhaustion, while preserving the current git-based promotion architecture until the V2 artifact model is delivered.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

---

## References
- `foundation_documentation/todos/completed/TODO-vnext-deploy-cache-image-prune.md`
- Docker production failure run `22797984666`
- `.github/scripts/deploy_stage_over_ssh.sh`
- `.github/scripts/rollback_over_ssh.sh`
- `.github/scripts/collect_remote_deploy_diagnostics.sh`
- `.github/workflows/orchestration-ci-cd.yml`

---

## Scope
1. Add mandatory remote disk-space preflight before `docker compose up -d --build` on `stage` and `main` deploy/rollback paths.
2. Add preventive cleanup before build/rollback build:
   - truncate Laravel disk logs,
   - prune stale builder cache,
   - prune unused images,
   - prune stopped containers if safe.
3. Preserve the currently running stack as the effective rollback anchor by pruning only artifacts not required by running containers.
4. Hard-fail deploy/rollback when free space remains below a defined minimum after cleanup.
5. Expand remote diagnostics collected before rollback to include disk and runtime state:
   - `df -h`,
   - `docker system df`,
   - `docker compose ps`,
   - recent logs for `app`, `nginx`, `worker`, `scheduler`.
6. Remove misleading success assumptions in rollback flow when the host is already saturated.

## Out of Scope
- Artifact-based deploy/rollback redesign (covered by V2 TODO).
- Registry/image promotion redesign.
- Product/business logic changes.
- Promotion beyond `stage`/`main` deploy safety.

---

## Definition of Done
- [x] ✅ Production‑Ready Deploy script performs cleanup before build and measures available disk.
- [x] ✅ Production‑Ready Rollback script performs cleanup before rebuild and measures available disk.
- [x] ✅ Production‑Ready Deploy/rollback hard-fail before mutating runtime if disk budget remains unsafe after cleanup.
- [x] ✅ Production‑Ready Diagnostics artifact captures disk/runtime evidence sufficient to explain `502` caused by host/runtime failure.
- [x] ✅ Production‑Ready Existing deploy, provenance, and navigation-smoke semantics remain intact when the host is healthy.

## Validation Steps
- [x] ✅ Production‑Ready `bash -n .github/scripts/deploy_stage_over_ssh.sh`
- [x] ✅ Production‑Ready `bash -n .github/scripts/rollback_over_ssh.sh`
- [x] ✅ Production‑Ready `bash -n .github/scripts/collect_remote_deploy_diagnostics.sh`
- [x] ✅ Production‑Ready targeted local shell review of cleanup/disk-budget branches
- [x] ✅ Production‑Ready remote run evidence captured from stage and main promotion lanes

---

## Plan Review Gate (Medium)

### Issue Card `I-DISK-01`
- **Severity:** Critical
- **Category:** Runtime / Deployment Safety
- **Evidence:** Production run `22797984666` failed preflight with `502`, then rollback failed with `no space left on device` while extracting layers in `/var/lib/containerd/...`.
- **Why now:** Current deploy model can leave production broken because rollback reconsumes disk on an already saturated host.
- **Options:**
  - **A (Recommended):** Add cleanup-before-build + hard disk budget gate + richer diagnostics.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Keep current post-success prune only and rely on manual host cleanup during incidents.
    - Effort: None
    - Risk: Critical
    - Blast radius: High
    - Maintenance burden: High
  - **C:** Add only diagnostics and no preventive cleanup.
    - Effort: Low
    - Risk: High
    - Blast radius: High
    - Maintenance burden: Medium

### Issue Card `I-DISK-02`
- **Severity:** High
- **Category:** Rollback Semantics
- **Evidence:** Current rollback rebuilds `app/worker/scheduler` from source, which depends on the same scarce disk resources that caused the incident.
- **Why now:** A rollback path that cannot execute under disk pressure is not a rollback path.
- **Options:**
  - **A (Recommended):** In V1, preserve the current running stack and prune only non-running artifacts; hard-fail early if budget remains unsafe.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **B:** Attempt aggressive prune of everything before every rollback.
    - Effort: Low
    - Risk: High
    - Blast radius: High
    - Maintenance burden: Medium
  - **C:** Do nothing and accept rollback fragility until V2.
    - Effort: None
    - Risk: Critical
    - Blast radius: High
    - Maintenance burden: High

### Issue Card `I-DISK-03`
- **Severity:** Medium
- **Category:** Diagnostics
- **Evidence:** Current diagnostics script captures repo/endpoint state but not disk usage, Docker storage usage, container status, or service logs.
- **Why now:** We cannot close root cause quickly during incident response without host-level evidence.
- **Options:**
  - **A (Recommended):** Expand diagnostics to capture disk/runtime evidence before rollback.
    - Effort: Low
    - Risk: Low
    - Blast radius: Low
    - Maintenance burden: Low
  - **B:** Keep endpoint-only diagnostics.
    - Effort: None
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Move all diagnosis to manual SSH playbooks only.
    - Effort: Low
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: High

---

## Failure Modes & Edge Cases
- Cleanup succeeds but free space remains below threshold.
- `docker builder prune` or `image prune` itself fails.
- Running stack is healthy, but build cache has already exhausted the disk before rollback begins.
- Diagnostics collection must not hide the original failure cause.
- `main` and `stage` hosts can have different disk headroom and must still follow the same guard policy.

## Uncertainty Register
- **Assumptions:**
  - `docker image prune -a` preserves images referenced by running containers on the target host.
  - The current running stack is the correct effective rollback anchor in V1.
- **Unknowns:**
  - Exact safe minimum free space threshold for this host family.
  - Whether stopped containers contribute materially to current disk pressure.
- **Confidence:** Medium.

---

## Decision Baseline (Frozen)
- `D-01`: Deploy/rollback must perform preventive cleanup before any rebuild on `stage` or `main`.
- `D-02`: Deploy/rollback must hard-fail before mutating runtime if free disk remains below a defined minimum after cleanup.
- `D-03`: V1 cleanup may remove only artifacts not required by the currently running stack; it must not intentionally invalidate the effective rollback anchor.
- `D-04`: Diagnostics before rollback must capture host disk and Docker runtime evidence, not only HTTP snapshots.
- `D-05`: V1 keeps the current git/source-based promotion model; immutable artifact rollback is deferred to `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`.

---

## Module Coherence Gate (Mandatory)
| Decision | Module Coherence | Change Intent | Evidence | Notes |
| --- | --- | --- | --- | --- |
| D-01 | Aligned | Preserve | `foundation_documentation/modules/system_architecture_principles.md` (operational safety and deterministic delivery intent) | Cleanup-before-build improves deterministic delivery without changing product behavior. |
| D-02 | Aligned | Preserve | `foundation_documentation/todos/completed/TODO-devops-single-gate-lane-promotion.md` (deploy must remain fail-closed) | Disk budget gate strengthens fail-closed semantics. |
| D-03 | Aligned | Preserve | current Docker deploy model + completed prune TODO | Preserve current runtime anchor until V2 artifact model exists. |
| D-04 | Aligned | Preserve | incident run `22797984666` diagnostics gap | Runtime evidence is required for root-cause quality. |
| D-05 | Aligned | Preserve | V2 artifact TODO above | Immediate scope stays within V1 architecture. |

---

## Workstreams
- [x] ✅ Production‑Ready WS-01 Add pre-build cleanup and free-space measurement to deploy script.
- [x] ✅ Production‑Ready WS-02 Add pre-rebuild cleanup and free-space measurement to rollback script.
- [x] ✅ Production‑Ready WS-03 Expand diagnostics artifact with disk/runtime evidence.
- [x] ✅ Production‑Ready WS-04 Validate shell syntax and branch-safe behavior.

---

## Decision Adherence Validation
_Post-implementation only._

| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| D-01 | Adherent | `.github/scripts/deploy_stage_over_ssh.sh`, `.github/scripts/rollback_over_ssh.sh`; validated by stage run `22798685289` and main run `22798763749` | Cleanup-before-build/rebuild is live on both protected lanes. |
| D-02 | Adherent | Both scripts now compute free KiB across `/`, Docker root, and `/var/lib/containerd`, then hard-fail when below `DEPLOY_MIN_FREE_GB`; workflow passes `DEPLOY_MIN_FREE_GB=8` for `stage` and `main` | Verified in successful protected-lane promotions after the shell escaping fix. |
| D-03 | Adherent | Cleanup prunes stopped containers, builder cache, and unused images only; it does not tear down the running stack | Stage and main remained healthy after guarded promotions, preserving the V1 rollback anchor policy. |
| D-04 | Adherent | `.github/scripts/collect_remote_deploy_diagnostics.sh` now collects `df -h`, `docker system df`, `docker compose ps`, and recent service logs before rollback | The main incident run produced the expected disk/runtime evidence used to close root cause. |
| D-05 | Adherent | Workflow semantics remain git/source-based; only guardrails and diagnostics were hardened in V1 | Artifact-based rollback remains deferred to the V2 TODO. |


## Completion Note
- `2026-03-07`: Guardrails were promoted through `dev -> stage -> main` in `belluga_now_docker`. Final protected-lane evidence: stage run `22798685289` and main run `22798763749`, both green with deploy, provenance, and smoke jobs preserved.
