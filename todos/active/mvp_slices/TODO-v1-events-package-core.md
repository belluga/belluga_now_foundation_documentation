# TODO (V1): Events Package Program (Core)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver Events as a first-class Laravel package with phased execution: migration-first, decoupling, then hardening.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-and-agenda-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `laravel-app/packages/belluga/belluga_push_handler`

---

## A) Phase Split (Canonical)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-1.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-2.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`

---

## B) Cross-Phase Invariants (Locked)
- [x] ✅ Production‑Ready Public/admin/account Events API contract remains stable while packageization runs.
- [x] ✅ Production‑Ready Event and Invite ownership boundary stays unchanged (`Invites` owns invite transaction lifecycle).
- [x] ✅ Production‑Ready Events remains compatible with current tenant/domain resolution model.
- [x] ✅ Production‑Ready Realtime behavior (`/events/stream` + `/agenda` rehydrate policy) remains unchanged unless explicitly planned.

---

## C) Out of Scope (Program Level)
- Event check-in workflows.
- Invite-domain business ownership changes.
- Breaking API contract changes for existing Flutter/Web clients.

---

## D) Program Definition of Done
- [x] ✅ Production‑Ready Phase 1 complete and stable in production behavior.
- [ ] ⚪ Phase 2 complete with package internals decoupled from app-layer implementation details.
- [ ] ⚪ Phase 3 complete with hardening/performance/observability improvements.
- [ ] ⚪ Foundation docs + roadmap + endpoint contracts synchronized with final architecture.
