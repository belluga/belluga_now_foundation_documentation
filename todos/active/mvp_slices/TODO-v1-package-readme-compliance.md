# TODO (V1): Package README Compliance

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Backend Team, Documentation Team  
**Objective:** Ensure every Laravel package under `laravel-app/packages/belluga/*` has a faithful README that explains the package clearly enough for a new engineer or AI to understand its contracts, boundaries, and usage without guessing.

---

## Audit Result

- `belluga_invites`: aligned with code and package surface.
- `belluga_map_pois`: aligned with code and package surface.
- `belluga_favorites`: aligned with code and package surface.
- `belluga_push_handler`: aligned with code and package surface.
- `belluga_settings`: aligned with code and package surface.
- `belluga_events`: aligned with code and package surface.
- `belluga_ticketing`: aligned with code and package surface.

---

## Delivery Rules

- Do not mark this TODO as complete until every package above has a README that covers the required sections and the audit result is fully aligned with code.
- README content must be faithful to implemented code contracts, not aspirational.
- Package code is out of scope for this TODO; only documentation changes are allowed.

## Required README Sections

Each package README must cover:

- Purpose and scope
- Domain concepts and invariants
- Data model and migration scope
- Public contracts such as routes, payloads, events, and commands
- Authentication and authorization boundary, explicitly separating package requirements from host responsibilities
- Host integration steps, including providers, bindings, adapters, listeners, and jobs when relevant
- Validation commands
- Known limitations and non-goals

---

## Follow-Up Tasks

- [x] ✅ Production-Ready `belluga_invites` README is aligned and kept as the baseline reference for this audit.
- [x] ✅ Production-Ready `belluga_map_pois` README is aligned and kept as the baseline reference for this audit.
- [x] ✅ Production-Ready `belluga_favorites` README is aligned with the persisted favorites model and host boundary.
- [x] ✅ Production-Ready `belluga_push_handler` README is aligned with the host-owned implementation.
- [x] ✅ Production-Ready `belluga_settings` README is aligned with the implemented settings surface.
- [x] ✅ Production-Ready `packages/belluga/belluga_events/README.md` includes the full required-section checklist for the current event surface.
- [x] ✅ Production-Ready `packages/belluga/belluga_ticketing/README.md` includes the full required-section checklist for the current ticketing surface.
- [x] ✅ Production-Ready Completion criteria refreshed after the remaining README drift items were resolved and re-verified against code.

---

## Completion Criteria

- [x] ✅ Production-Ready Every package listed in the audit has a README that covers the required sections and matches the implemented package surface.
- [x] ✅ Production-Ready `rg --files laravel-app/packages/belluga -g 'README.md'` returns a README for every package directory.
- [x] ✅ Production-Ready README content matches the implemented package surface and host responsibilities.
