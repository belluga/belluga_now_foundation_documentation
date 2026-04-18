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
- **Last updated:** `2026-04-18 12:39 -03`
- **Current active session model:** session-memory is now subordinate to the active TODO and stored under `foundation_documentation/sessions/`.
- **Current active session artifact:** `foundation_documentation/sessions/session_20260418-foundation-authority-rebaseline_memory.md`
- **Current active TODO:** `foundation_documentation/todos/active/vnext/TODO-vnext-foundation-authority-and-branch-reconciliation.md`
- **Continuity boundary:** this aggregate file is now pointer-only. Tactical continuity must live in the active TODO or the session-specific memory file, not here.
- **Transposition summary:** durable lane-model continuity already lives in `foundation_documentation/project_constitution.md`; release-lane continuity remains in the specific `store_release_android/` and `fast_follow_required/` TODOs instead of a shared rolling note here.

## Confirmed User Preferences
- none

## Confirmed Learned Behaviors
- none

## Dependency References
- **Dependency readiness register:** `foundation_documentation/artifacts/dependency-readiness.md`
- **Relevant status carry-over:** backlog authority uses the flattened active-lane model `store_release_android` + `fast_follow_required` + `vnext`; canonical authority for that rule now lives in `foundation_documentation/project_constitution.md`, not in this aggregate memory file.
