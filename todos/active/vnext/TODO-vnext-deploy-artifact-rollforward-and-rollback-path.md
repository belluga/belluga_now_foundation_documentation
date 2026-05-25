# TODO (VNext): Immutable Deploy Artifacts + Safe Rollback Path

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Provisional (`Promoted prerequisite and merged as IMG/MAN sub-contract for synchronized healthy-final-state closure`). The immutable GHCR image path for protected deploy/rollback reached `main`; this vNext artifact remains open for broader recurring disk-budget/retention hardening and for standalone evidence reconciliation if not fully superseded by the healthy-state TODO.
**Current delivery stage:** `Provisional`
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
- The `2026-05-17` fail-closed pipeline investigation adjudicated immutable artifact restoration as mandatory for full `stage`/`main` healthy-final-state closure; immediate rollback/proof fixes reduce risk but do not remove this requirement.
- The `2026-05-22` synchronized closure decision promotes this track from deferred planning into the active healthy-state closure scope. This TODO remains the detailed artifact-design source, but the governing approval, matrix, and completion claim belong to `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`.

---

## Scope
1. Define artifact-based deploy/rollback policy for `stage` and `main`.
2. Define retention policy for the active release artifact and last-known-good rollback artifact.
3. Define provenance contract for promoted artifacts and runtime metadata.
4. Define operational storage budget rules for host-side runtime data, cache, and image retention.
5. Define rollback semantics that do not depend on a full image rebuild on a disk-constrained host.
6. Favor the simplest artifact topology that closes the invariant; avoid introducing multiple artifact catalogs, parallel provenance ledgers, or unnecessary promotion surfaces if one coherent model is sufficient.

## Preferred Minimal Design

- Prefer one lane-local deploy manifest / release pointer model over multiple artifact ledgers.
- Prefer immutable digests/tags already native to the container platform over custom artifact identity layers unless a gap remains.
- Keep rollback selection to one trusted source of artifact truth per lane.
- Treat additional storage/indexing systems as last resort, not default architecture.

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

## Minimum Blocking Contract

- Track 4 is the structural blocker for full `stage/main` fail-closed closure, not an optional enhancement.
- At minimum this track must define and eventually prove:
  - the immutable artifact identity used for forward deploy;
  - the immutable artifact identity used for rollback;
  - where those artifacts live and how many lane-local last-known-good artifacts are retained;
  - how rollback selects a trusted artifact without consulting mutable host source state;
  - how provenance for Flutter/Laravel/web runtime remains auditable after rollback.
- Track 4 must reduce runtime/recovery complexity, not shift it into a larger operational surface area.
