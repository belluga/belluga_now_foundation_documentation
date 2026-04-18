# TODO Landscape Review (2026-04-18)

## Purpose

Record the current active TODO topology after the foundation authority reconciliation so the near-term lanes remain clear and `vnext/` can be normalized without accidentally flattening distinct future programs.

## Active Lane Snapshot

| Lane | Active Files | Current Read |
| --- | ---: | --- |
| `store_release_android` | `10` | Clear near-term Android-release authority with one orchestrator and explicit child slices. |
| `fast_follow_required` | `3` | Clear mandatory post-release lane with one orchestrator and two direct child fronts. |
| `vnext` | `63` | Valid deferred backlog, but currently mixes multiple TODO roles and temporary reconciliation slices. |

Snapshot source:
- `find foundation_documentation/todos/active/<lane> -maxdepth 1 -type f`
- lane inventory regenerated on `2026-04-18`

## Lane Findings

### 1. `store_release_android/` is already structurally coherent

- `TODO-store-release-android.md` is the milestone orchestrator.
- The remaining files act like direct execution slices for that release gate.
- No immediate regrouping is needed here; the lane already communicates "current release authority" clearly.

### 2. `fast_follow_required/` is already structurally coherent

- `TODO-fast-follow-obligatory.md` is the lane orchestrator.
- The lane stays intentionally small and business-defined.
- Its current role is distinct from both `store_release_android/` and generic `vnext/`.

### 3. `vnext/` is not wrong, but it is role-mixed

`vnext/` currently contains several different kinds of artifacts that should not be interpreted as the same kind of owner:

| Role | Description | Representative Files |
| --- | --- | --- |
| `program owner` | Real deferred workstream with its own scope boundary. | `TODO-vnext-account-workspace.md`, `TODO-v1-ticketing-package-integration.md`, `TODO-v1-tenant-admin-domain-management.md`, `TODO-vnext-connections-package.md` |
| `support registry` | Supporting backlog/evidence for another owner TODO. | `TODO-vnext-test-hardening-defect-backlog.md` supports `TODO-vnext-test-hardening-program.md` |
| `reconciliation sub-slice` | Temporary normalization front for documentation or authority cleanup. | `TODO-vnext-current-state-module-authority-alignment.md`, `TODO-vnext-secondary-module-lane-and-phase-cleanup.md`, `TODO-vnext-deferred-commerce-authority-framing.md`, `TODO-vnext-foundation-doc-branch-integration-review.md`, `TODO-vnext-foundation-todo-landscape-reconciliation.md` |
| `parking lot` | Residual idea capture, not a primary program owner. | `TODO-vnext-parking-lot.md` |
| `one-off local fix / isolated doc item` | Narrow item that should still obey naming and ownership rules. | `TODO-vnext-location-permission-back-button-fix.md` |

The core issue is not that `vnext/` exists or that it is large. The issue is that readers can mistake all of these files for equivalent program ownership even when some are only support registries or temporary reconciliation slices.

## Safe Normalization Applied In This Slice

- Renamed `active/vnext/location-permission-back-button-fix.md` to `active/vnext/TODO-vnext-location-permission-back-button-fix.md` so the file now follows the active TODO naming rule.
- Added canonical guide rules in `todos/README.md` for:
  - explicit `vnext` role hygiene,
  - avoiding duplicate ownership,
  - active TODO filename normalization.

## Recommendations

- Preserve `store_release_android/` and `fast_follow_required/` as the authoritative near-term lanes.
- Keep `vnext/` as the deferred backlog lane, but require each touched file to clarify whether it is a `program owner`, `support registry`, `reconciliation sub-slice`, or `parking lot`.
- Do not open a new `vnext` TODO when an existing active file already owns the same deferred program boundary.
- When a support registry exists, keep it explicitly subordinate to the owner TODO rather than letting it drift into a second owner.
- After the current authority-reconciliation fronts are merged and promoted, retire or reclassify reconciliation sub-slices that no longer own active residual work.
- Only merge `vnext` TODOs when the overlap is exact and documentary; do not collapse distinct future programs just to reduce file count.
