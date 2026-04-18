# TODO (VNext): Test Hardening Defect Backlog

**Role note (2026-04-18):** this file is a support registry, not a primary program owner. The owning delivery lane remains `TODO-vnext-test-hardening-program.md`, and any product-logic fix discovered here must live in its own dedicated fix TODO.

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
| TD-001 | High | `tools/flutter/web_app_tests/navigation.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh mutation` (`tenant agenda UI state matches tenant agenda API payload`) | First tenant public Home agenda request was emitted without `origin_lat`/`origin_lng`. | `ScheduleRepository.fetchUpcomingEvents()` used `getAllEvents()` (`_backend.fetchEvents`) path without origin params on startup. | `foundation_documentation/todos/completed/TODO-v1-events-location-gating-and-tenant-default-origin.md` (`D-14`) | Resolved |
| TD-002 | High | `tools/flutter/web_app_tests/navigation.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh mutation` (`tenant agenda UI state matches tenant agenda API payload`) | Mutation gate failed with "Agenda API returned items, but UI still shows empty state" while Home was correctly empty. | The assertion compared Home UI state against non-Home `/api/v1/agenda` requests (for example `page_size=25` auxiliary fetches), instead of the canonical Home agenda request (`page_size=10`, `past_only=0`, `confirmed_only=0`). | `foundation_documentation/todos/completed/TODO-vnext-home-agenda-mutation-query-parity.md` | Resolved |

---

## Status Legend
- `Pending`: defect registered, fix TODO not approved yet.
- `In Progress`: fix TODO approved and under implementation.
- `Resolved`: fix TODO delivered and validated; hardening gate can resume.
- `Won't Fix`: explicitly accepted risk with approval evidence.
