# TODO (VNext): Immutable Deploy Artifacts + Safe Rollback Path

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active (`Planning`)
**Owners:** Platform + Backend + Flutter
**Objective:** Replace host-local rebuild dependency with an immutable deployment/rollback model so `stage`/`main` promotions preserve a deterministic last-known-good artifact path and do not depend on disk-heavy rebuilds during rollback.
**Complexity:** `big`
**Checkpoint policy:** section-by-section checkpoints before execution approval.

---

## Goal
Establish a V2 deployment model where lane promotion deploys versioned, immutable artifacts and rollback restores the last-known-good runtime without rebuilding application images on the target host.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

---

## Context / Evidence
- Production promotion run `22797984666` (`stage -> main`) left `booraagora.com.br` serving static web (`/build_metadata.json` = `200`) while API endpoints returned `502`.
- The rollback then failed with `no space left on device` while extracting container layers under `/var/lib/containerd/io.containerd.snapshotter.v1.overlayfs/...`.
- Current deploy/rollback model rebuilds `app`, `worker`, and `scheduler` on the target host for both forward deploy and rollback.
- Existing cleanup policy is post-success only, best-effort, and therefore cannot guarantee recovery when the host is already saturated.

---

## Scope
1. Define artifact-based deploy/rollback policy for `stage` and `main`.
2. Define retention policy for the active release artifact and last-known-good rollback artifact.
3. Define provenance contract for promoted artifacts and runtime metadata.
4. Define operational storage budget rules for host-side runtime data, cache, and image retention.
5. Define rollback semantics that do not depend on a full image rebuild on a disk-constrained host.

## Out of Scope
- Immediate incident mitigation in current SSH deploy scripts.
- Rewriting product routes, controllers, or business logic.
- Main-lane expansion beyond current deploy topology.

---

## Decision Baseline (Frozen)
- `D-V2-01`: `stage` and `main` rollback must restore immutable promoted artifacts, not rebuild from source on the target host.
- `D-V2-02`: The platform must preserve at least the active runtime artifact and one last-known-good rollback artifact per lane.
- `D-V2-03`: Host-side disk policy must be explicit, measured, and enforced by preflight budget gates.
- `D-V2-04`: Web provenance, Flutter pin, and Laravel pin must remain auditable after promotion and rollback.

---

## Candidate Workstreams
- [ ] ⚪ Pending Define artifact production and storage strategy for Docker app/worker/scheduler images.
- [ ] ⚪ Pending Define immutable tagging/digest promotion policy by lane.
- [ ] ⚪ Pending Define rollback command path that restores last-known-good artifacts without rebuild.
- [ ] ⚪ Pending Define disk budget SLOs and cleanup strategy for runtime hosts.
- [ ] ⚪ Pending Define operational diagnostics package for deployment incidents.

---

## Validation Targets (Future)
- Promotion to `stage` restores a known-good release without source rebuild.
- Promotion to `main` can roll back without pulling/building large layers on a saturated host.
- Artifact provenance remains aligned with promoted Flutter/Laravel SHAs.
