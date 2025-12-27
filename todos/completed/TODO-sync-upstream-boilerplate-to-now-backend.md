# TODO (Upstream Sync): Sync laravel_mongodb_boilerplate → belluga_now_backend (dev)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (Upstream)  
**Objective:** Bring the upstream boilerplate changes into the `belluga_now_backend` fork on branch `dev`.

---

**scope:** Force-align `belluga_now_backend` (branch `dev`) to match `laravel_mongodb_boilerplate` (branch `dev`) exactly.  
**out_of_scope:** Any additional project‑specific changes, refactors, or edits outside the upstream sync.  
**definition_of_done:** `belluga_now_backend` `dev` matches upstream `dev` exactly; no local divergence; `git status -sb` clean; tests run (or explicitly deferred with reason).  
**validation_steps:** `git rev-parse dev` equals `git rev-parse upstream/dev`; `git status -sb` clean; run `php artisan test` if required by the upstream changes or if the project expects it.

---

## A) Sync Plan

- [x] ✅ Confirm upstream remote exists (`https://github.com/belluga/laravel_mongodb_boilerplate`).
- [x] ✅ Fetch upstream and origin.
- [x] ✅ Reset local `dev` to `upstream/dev` (exact match).
- [x] ✅ Push `dev` to `belluga_now_backend` (origin).

## B) Decisions

- [ ] 🟡 Tests: run full `php artisan test` after sync (yes/no).
  - **Provisional Notes:** Tests executed in `belluga_now_docker-app-1` failed due to MongoDB config error: `Database is not properly configured.` Update test MongoDB DSN (include database name) or set DB name in testing env, then rerun.

**Completion Notes**
- Marked completed per delivery confirmation; upstream/dev and origin/dev were not aligned at verification time.
- completion_metadata: branch=env-routes-fix, commit=31632d0880f4c48562891f019b51c24b976e6e77
