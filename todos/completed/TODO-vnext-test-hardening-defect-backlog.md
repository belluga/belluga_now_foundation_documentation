# TODO (VNext): Test Hardening Defect Backlog

**Status:** Completed  
**Owners:** Backend Team + Flutter Team + Platform  
**Date:** `2026-04-18`

## Closure Note
This file was a support registry, not a primary delivery owner. The linked defects were resolved through dedicated fix TODOs, so the backlog no longer needs to remain in `active/vnext`.

## Historical Defect Registry

| Defect ID | Severity | Source Test / Gate | Symptom | Linked Fix TODO | Status |
| --- | --- | --- | --- | --- | --- |
| TD-001 | High | `tools/flutter/web_app_tests/navigation.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh mutation` | First tenant public Home agenda request was emitted without `origin_lat`/`origin_lng`. | `foundation_documentation/todos/completed/TODO-v1-events-location-gating-and-tenant-default-origin.md` (`D-14`) | Resolved |
| TD-002 | High | `tools/flutter/web_app_tests/navigation.spec.js` via `bash tools/flutter/run_web_navigation_smoke.sh mutation` | Mutation gate compared Home UI state against non-canonical `/api/v1/agenda` requests instead of the canonical Home agenda request. | `foundation_documentation/todos/completed/TODO-vnext-home-agenda-mutation-query-parity.md` | Resolved |

## Residual Note
- Future test-discovered product defects should open their own dedicated fix TODOs directly.
- This completed file is historical evidence only; it is not a standing owner for future hardening work.
