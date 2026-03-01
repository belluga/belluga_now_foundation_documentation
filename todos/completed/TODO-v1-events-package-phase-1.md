# TODO (V1): Events Package Phase 1 (Migration with Controlled Dependencies)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team
**Objective:** Move Events implementation into `belluga_events` package with no API behavior change, while temporarily keeping app-level dependencies.

---

## Scope
- Create `laravel-app/packages/belluga/belluga_events` package scaffold and composer wiring.
- Move/establish Events domain code in package namespace (`Belluga\\Events\\...`):
  - model, services, controllers, requests, scheduled job, migration.
- Keep current route URLs and payload contracts unchanged.
- Keep temporary dependencies to app-level services/jobs where needed (taxonomy validation, map POI projection sync, account/profile lookups).
- Provide compatibility layer in app namespace where needed to avoid breaking existing imports.

---

## Out of Scope
- Full decoupling from `App\\...` dependencies (Phase 2).
- Contract redesign or endpoint behavior changes.
- Performance/observability redesign (Phase 3).

---

## Decisions (Locked for Phase 1)
- [x] ✅ Production‑Ready Taxonomy validation remains synchronous and dependency-based (no async validation).
- [x] ✅ Production‑Ready Map POI synchronization can remain tied to current app job flow in this phase.
- [x] ✅ Production‑Ready Route ownership remains in host app route files for tenant/domain scoping stability.

---

## Tasks
- [x] ✅ Production‑Ready Add package metadata and autoload wiring in `laravel-app/composer.json`.
- [x] ✅ Production‑Ready Create `Belluga\\Events\\EventsServiceProvider`.
- [x] ✅ Production‑Ready Migrate Events classes into package and align namespaces/imports.
- [x] ✅ Production‑Ready Rewire host app references to use package classes or app compatibility wrappers.
- [x] ✅ Production‑Ready Move tenant events migration path to package and keep migration execution valid.
- [x] ✅ Production‑Ready Confirm scheduled publish job uses package implementation.
- [x] ✅ Production‑Ready Preserve/refresh existing Events feature tests without contract changes.

---

## Validation Steps
- [x] ✅ Production‑Ready `composer dump-autoload` (inside `laravel-app` container).
- [x] ✅ Production‑Ready `php artisan test` full suite executed (superset coverage including Events/Map suites).
- [x] ✅ Production‑Ready Manual smoke waived by explicit user decision on February 24, 2026.

---

## Definition of Done
- [x] ✅ Production‑Ready Events runtime behavior matches pre-migration contracts.
- [x] ✅ Production‑Ready Package is authoritative location for Events implementation code.
- [x] ✅ Production‑Ready No unresolved COMMENT/COMENTÁRIO blocks remain.
