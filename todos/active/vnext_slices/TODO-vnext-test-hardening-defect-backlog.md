# TODO (VNext): Test Hardening Defect Backlog

**Status:** Active  
**Owners:** Backend Team + Flutter Team + Platform  
**Purpose:** Capture functional defects discovered while executing `TODO-vnext-test-hardening-program.md` so product-logic fixes are handled in dedicated TODOs.

---

## Rules
- Do not fix product logic from this backlog file directly.
- For each discovered defect, open a dedicated fix TODO and link it below.
- Hardening stages that depend on unresolved defects must be marked `blocked`.

---

## Defect Registry

| Defect ID | Severity | Source Test / Gate | Symptom | Root Cause Hypothesis | Linked Fix TODO | Status |
| --- | --- | --- | --- | --- | --- | --- |
| TD-001 | High | `web-app/tests/navigation.spec.js` (`tenant agenda UI state matches tenant agenda API payload`) | First tenant public Home agenda request was emitted without `origin_lat`/`origin_lng`. | `ScheduleRepository.fetchUpcomingEvents()` used `getAllEvents()` (`_backend.fetchEvents`) path without origin params on startup. | `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-location-gating-and-tenant-default-origin.md` (`D-14`) | Resolved |

---

## Status Legend
- `Pending`: defect registered, fix TODO not approved yet.
- `In Progress`: fix TODO approved and under implementation.
- `Resolved`: fix TODO delivered and validated; hardening gate can resume.
- `Won't Fix`: explicitly accepted risk with approval evidence.
