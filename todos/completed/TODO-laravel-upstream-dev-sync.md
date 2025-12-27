# TODO (Laravel): Sync env-routes-fix to upstream/dev

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team  
**Objective:** Make the local `laravel-app` working tree match `upstream/dev` exactly, discarding local branch state.

---

**scope:** Reset local `laravel-app` branch `env-routes-fix` to the exact commit of `upstream/dev`, discarding local history or changes; confirm git status is clean after reset.  
**out_of_scope:** Any Flutter/web changes, documentation updates beyond this TODO, or cherry-picking fixes.  
**definition_of_done:** `laravel-app` `env-routes-fix` points to the same commit hash as `upstream/dev`, working tree is clean, and `git status -sb` shows no divergence.  
**validation_steps:** `git status -sb`; `git rev-parse HEAD`; `git rev-parse upstream/dev`.

---

## Tasks

- [x] ✅ Production‑Ready Fetch latest `upstream/dev` and reset local `env-routes-fix` to match it exactly (hard reset).
- [x] ✅ Production‑Ready Confirm commit hashes match and working tree is clean.

## Validation Results

- `git status -sb`: `## env-routes-fix`
- `git rev-parse HEAD`: `31632d0880f4c48562891f019b51c24b976e6e77`
- `git rev-parse upstream/dev`: `31632d0880f4c48562891f019b51c24b976e6e77`
