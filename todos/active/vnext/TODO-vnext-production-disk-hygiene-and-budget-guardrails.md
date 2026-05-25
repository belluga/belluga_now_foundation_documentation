# TODO (VNext): Production Disk Hygiene and Budget Guardrails

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Pending`
**Qualifiers:** `VNext`, `DevOps`, `Runtime-Hardening`, `Production-Operations`, `Deferred`
**Next exact step:** after the current `main` promotion is unblocked by increasing production disk capacity, freeze the recurring disk-hygiene policy and decide whether it belongs in deploy preflight, a scheduled host maintenance unit, or both.
**Owners:** DevOps Team, Delphi
**Objective:** Establish a recurring, auditable disk-hygiene method for production/stage hosts so deploy budget gates remain healthy without relying on ad hoc emergency cleanup during promotion.
**Promotion lane path:** `dev -> stage -> main` if implementation changes repository scripts/workflows; `ops-runbook-only` if the final slice is documentation/manual operating procedure only.
**Complexity:** `small`
**Primary execution profile:** `Operational / DevOps`
**Active technical scope:** `docker`, `runtime`, `ci-cd`

---

## Context

During the `stage -> main` promotion on `2026-05-22`, Docker production deploy run `26317230473` failed before runtime mutation because the production host missed the configured disk budget by roughly `54 MiB`:

- required free space: `4,194,304 KiB`
- available after cleanup: `4,139,112 KiB`
- deploy marker: `DEPLOY_RUNTIME_MUTATED=0`
- rollback marker: `INTERNAL_ROLLBACK_STATUS=not_attempted`

The root cause was structural capacity pressure on an `8.7G` root filesystem, not a product-code or CI regression. The immediate decision for the current promotion is to increase the production server capacity to `20GB` and rerun the deploy, without doing emergency cleanup as part of that promotion.

This TODO records the follow-up: define a recurring hygiene strategy so journal/cache growth and Docker/containerd residue do not repeatedly threaten deploy budget gates.

---

## Scope

- Define a safe recurring disk-hygiene policy for production and stage hosts.
- Decide retention targets for `journald`, including whether to enforce `SystemMaxUse` or equivalent `journalctl --vacuum-size` behavior.
- Define when APT cache cleanup is allowed, including `/var/cache/apt` and stale package list handling.
- Define a Docker/containerd cleanup policy that must not remove active runtime images, active containers, required volumes, or rollback-critical artifacts.
- Add a read-only disk-budget diagnostic/reporting command or script if repository automation is the chosen implementation path.
- Decide whether deploy preflight should emit more actionable disk breakdown evidence when budget fails.
- Ensure the policy is idempotent, auditable, and safe to run repeatedly.

## Out of Scope

- Current `main` promotion unblock.
- Emergency cleanup on the production host during the active promotion.
- Lowering the existing deploy disk-budget threshold.
- Removing active Docker images, active containers, app storage volumes, database data, certbot data, or rollback state.
- Broad server resizing automation.

---

## Evidence Baseline

- SSH read-only analysis confirmed local access using the production CI key.
- Production filesystem before capacity increase:
  - `/dev/vda1`: `8.7G` total, `4.7G` used, `4.0G` available.
  - inode usage healthy at roughly `15%`.
- Largest observed contributors:
  - `/var`: `2.3G`
  - `/usr`: `2.1G`
  - `/srv/belluga_now_docker`: `377M`
  - `/var/lib/containerd`: `1.3G`
  - `/var/log/journal`: `434M`
  - `/var/cache/apt`: `124M`
  - `/var/lib/apt/lists`: `244M`
- `apt-get -s autoremove` reported no removable packages at the time of analysis.

---

## Pending Decisions

- [ ] ⚪ `D-01` Choose the recurring hygiene mechanism:
  - host-level runbook only;
  - repository-managed maintenance script;
  - deploy preflight diagnostics only;
  - scheduled systemd timer/cron;
  - combination of the above.
- [ ] ⚪ `D-02` Freeze `journald` retention target (`100M`, `200M`, or another bounded value).
- [ ] ⚪ `D-03` Define allowed APT cleanup operations and whether package lists should be refreshed after cleanup.
- [ ] ⚪ `D-04` Define Docker/containerd cleanup boundary, including exact commands and forbidden deletion targets.
- [ ] ⚪ `D-05` Decide whether disk-budget failures should emit a top-N usage report automatically in CI logs.

---

## Acceptance Criteria

- [ ] ⚪ The policy explains what can be cleaned safely and what must never be cleaned automatically.
- [ ] ⚪ The chosen method preserves active runtime, rollback safety, app storage, certificates, and production data.
- [ ] ⚪ Disk-budget diagnostics make future failures actionable without requiring manual exploratory SSH first.
- [ ] ⚪ Any automation is dry-run/read-only first or otherwise explicitly fail-safe.
- [ ] ⚪ Validation proves the method on a non-production or controlled host before being eligible for production use.

## Validation Steps

- [ ] ⚪ Run the proposed diagnostic in read-only mode and verify it reports filesystem, Docker, journal, APT cache, and top large files.
- [ ] ⚪ If cleanup automation is implemented, run a dry-run or controlled-host proof before production.
- [ ] ⚪ Verify no active Docker container/image/volume required by the running stack is removed.
- [ ] ⚪ Verify deploy disk-budget gate remains above threshold with meaningful headroom after the policy is applied.

## Current Boundary

This TODO is a VNext record only. It does not authorize cleanup or automation during the active `main` promotion. The current promotion should proceed by increasing the production disk to `20GB`, then rerunning the production deploy and completion guard.
