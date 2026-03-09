# TODO (V1): Events + Invites (Index)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team + Flutter Team + Web Team
**Objective:** Coordinate two separate MVP functionalities with explicit ownership boundaries:
- **Events** (catalog/schedule/discovery/admin operations)
- **Invites** (social transaction/attribution/quota/acceptance)

---

## Canonical Split TODOs
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md` (**Events** canonical TODO)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md` (**Invites** canonical TODO)

## Events Package Program (New)
- `foundation_documentation/todos/completed/TODO-v1-events-package-core.md` (**Core program tracking**)
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-1.md` (**Phase 1: migrate to package with controlled dependencies**)
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-2.md` (**Phase 2: true decoupling via contracts/adapters/events**)
- `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md` (**Phase 3: hardening/improvements**)

---

## Baseline Reference
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-backend.md`

---

## Locked Boundary
- Events and Invites are distinct functionalities.
- Invites references Events via `event_id`; Events does not own invite transaction lifecycle.
- `confirmed_only` should be derived from invite acceptance semantics in MVP.

---

## Coordination Status
- Backend event baseline is complete and tracked in the completed backend TODO.
- Events package/frontend streams are completed; remaining open MVP execution is currently in the Invites stream.
