# TODO (V1): DevOps Single-Gate Lane Promotion (Authoritative Flow)
**Version:** 1.2
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed
**Owners:** DevOps + Platform Team

## Objective
Keep one authoritative CI/CD promotion gate in `belluga_now_docker`, with lane-safe (`dev -> stage`, `stage -> main`) and exact-SHA guarantees across source repos.

## Scope
- Repositories:
  - `belluga_now_docker`
  - `belluga_now_front` (Flutter source)
  - `belluga_now_backend` (Laravel source)
  - `belluga_now_web` (runtime-derived web artifact repository)
- Lanes:
  - `dev`
  - `stage`
  - `main`

## Final Contract (Implemented)
1. Docker is the single promotion gate.
2. Promotion mappings are strict:
   - `dev -> stage`
   - `stage -> main`
3. Promotion checks are exact-SHA and AND-based across required source contracts.
4. Source promotion PRs are prepared during docker PR phase and must be merge-ready before docker promotion PR is mergeable.
5. Source PR drift protection is enforced via exact-SHA lock + ancestry validation.
6. Source PR merge strategy is `--merge` (no `--squash`/`--rebase` for promotion path).
7. Already-promoted exact SHA is treated as explicit no-op success.
8. Callback from source repos triggers docker preflight rerun automatically after source PR CI completion.
9. Stage/main deploy remains fail-closed with runtime provenance + navigation smoke + rollback.
10. Web artifact provenance is validated as runtime-derived contract (lane host + flutter SHA compatibility), not as cross-lane promotable gitlink contract.

## Implementation Status
- [x] ✅ Production‑Ready Lane policy and promotion mapping enforcement on docker PRs.
- [x] ✅ Production‑Ready Exact-SHA CI checks with no-op allowance for already-promoted SHAs.
- [x] ✅ Production‑Ready Source promotion PR preparation (`dev->stage`, `stage->main`) without auto-merge in PR phase.
- [x] ✅ Production‑Ready Source PR merge-ready hard gate (`mergeStateStatus == CLEAN`, non-draft).
- [x] ✅ Production‑Ready Source PR callback rerun integration.
- [x] ✅ Production‑Ready Post-merge source promotion merge execution with exact-SHA guardrails.
- [x] ✅ Production‑Ready `--merge`-only promotion merge strategy.
- [x] ✅ Production‑Ready Lane-scoped promotion broker concurrency.
- [x] ✅ Production‑Ready Actionable fail messages for promotion gates.

## Validation Evidence
- Promotion run `dev -> stage` validated successfully in docker orchestration, including real navigation smoke.
- Promotion run `stage -> main` validated successfully in docker orchestration, including production navigation smoke and provenance checks.
- Stage and main smoke suites completed with `2 passed` in recent validated runs.

## Notes
- Any remaining architecture evolution around eliminating `web-app` submodule from docker runtime/pipeline is tracked separately in:
  - `foundation_documentation/todos/active/TODO-devops-remove-web-submodule-runtime-lane.md`

## Closure Decision
This TODO is concluded. The authoritative promotion flow is operating as defined and has successful promotion evidence through `main`.
