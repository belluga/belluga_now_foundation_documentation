# Session Memory

## Artifact Role
- **Purpose:** bounded continuity + confirmed preferences/behaviors + dependency references.
- **What it is not:** canonical contract, approval ledger, or authority for mixed-scope execution.
- **Related derived surface:** generated runtime index / session handoff index may summarize this file, but must remain regenerable.

## Update Policy
- **Auto-eligible updates:**
  - latest session continuity summary;
  - dependency statuses touched during the session.
- **Confirmation required before updating:**
  - stable user preferences;
  - learned operational behaviors that should persist across sessions.
- **Never update here instead of canonical docs:**
  - architectural decisions;
  - module/constitution/roadmap truth;
  - tactical TODO approvals or profile handoffs.

## Latest Session Continuity
- **Last updated:** `2026-04-11 21:03 -03`
- **Current active TODO:** `foundation_documentation/todos/active/mvp_slices/TODO-v1-canonical-back-navigation-governance-cutover.md`
- **Current active front:** Production promotion of the canonical back-navigation cutover is complete; the next session should start either from cleanup-only documentation/rule drift around that TODO or from the preserved Docker branch residue that blocked a fully clean rebaseline.
- **Last confirmed truth:** Flutter, web, and docker were promoted through `main` successfully on `2026-04-11`; `belluga_now_front`, `belluga_now_web`, and `belluga_now_docker` production-lane runs finished green; local `flutter-app` and `docker` checkouts were returned to updated `dev`; Docker still intentionally preserves residual non-lane branches plus two local untracked PNG artifacts under `tools/flutter/web_app_smoke_runner/`.
- **Next likely step:** Decide whether the remaining Docker-only branches (`feat/canonical-route-back-policies`, `feature/map-visuals-and-safe-back`, `bot/submodule-sync-stage-20260409-1900-reconcilemain`) should be archived, merged, or discarded, then finish repository hygiene.

## Confirmed User Preferences
- none

## Confirmed Learned Behaviors
- none

## Dependency References
- **Dependency readiness register:** `foundation_documentation/artifacts/dependency-readiness.md`
- **Relevant status carry-over:** `gh` authentication and GitHub promotion pipelines were healthy during `dev -> stage -> main`; deployed browser targets used for validation were healthy during stage and production navigation smoke on `2026-04-11`.
