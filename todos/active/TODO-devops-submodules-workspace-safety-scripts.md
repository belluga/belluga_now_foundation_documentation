# TODO (DevOps): Safe Submodule Workspace Scripts (Pin vs Track)

## Context
We use git submodules in `belluga_now_docker` to pin exact versions of:
- `flutter-app` (`belluga_now_front`)
- `laravel-app` (`belluga_now_backend`)
- `web-app` (`belluga_now_web`)
- `foundation_documentation`

This creates two common local workspace modes:
1. **Pinned mode (Reproducible):** submodules checked out to the exact SHAs recorded in the superproject.
2. **Lane tracking mode (Convenient):** submodules checked out to lane branches (`dev`/`stage`/`main`) for browsing/work, without changing the superproject pins.

Confusion happens when developers mix the two (e.g., `git pull` inside submodules) and later assume CI/deploy uses what they see locally. CI/deploy uses **the superproject pins**.

## Goal
Provide safe, non-destructive scripts + documentation so developers can:
- Switch to **Pinned mode** reliably before deploy/debug/CI parity.
- Switch to **Lane tracking mode** for convenience.
- Avoid data loss (no `rm -rf`, no `git clean`, no `reset --hard`, no `git submodule update --force`).
- Fail fast if any submodule has uncommitted changes.

## Deliverables
- [ ] ⚪ Pending Add `tools/submodules/status.sh`
- [ ] ⚪ Pending Add `tools/submodules/pin_to_superproject.sh` (safe pin; refuses on dirty submodules)
- [ ] ⚪ Pending Add `tools/submodules/track_lanes.sh` (safe branch switch; refuses on dirty submodules)
- [ ] ⚪ Pending Update `README.md` (root repo) with a short “Submodule Workspace Rules” section

## Safety Requirements (Non-Negotiable)
- Scripts must **never** delete files or run destructive git operations.
- Scripts must **refuse to run** if:
  - any submodule has a dirty working tree (`git status --porcelain` non-empty), or
  - submodule is missing / not initialized (unless pin script runs `git submodule update --init --recursive` *without* `--force`).
- Output must be explicit about what happened (what mode you are in, how to get back, how to resolve).

## Definition of Done
- `tools/submodules/status.sh` prints:
  - superproject branch + commit
  - `git submodule status`
  - for each submodule: current branch/HEAD + dirty state
- `tools/submodules/pin_to_superproject.sh`:
  - syncs + updates submodules to exact pinned SHAs (non-force)
  - ends with `git submodule status` (no `+` expected if clean)
- `tools/submodules/track_lanes.sh <lane>`:
  - switches each submodule to the lane branch if it exists
  - maps `foundation_documentation` to `main` (lane-agnostic) unless we add stage lanes later
- Root `README.md` clearly states:
  - CI/deploy uses **pins** (superproject)
  - local convenience branch tracking is allowed but must not be confused with pins

