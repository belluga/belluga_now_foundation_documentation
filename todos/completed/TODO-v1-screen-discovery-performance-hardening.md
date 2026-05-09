# TODO (V1): Discovery Performance Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Completed (`active-lane cleanup synced on 2026-04-09 after delivery confirmation`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Automated-Validated`, `Closure-Synced`
**Next exact step:** None. Archived to `todos/completed` on `2026-04-09`.
**Owners:** Flutter Team, Laravel Team
**Objective:** Eliminate the structural causes of slow Discovery startup by removing repository-wide eager profile fetches from the critical path and aligning the public account-profile listing query with index-backed pagination rather than redundant client-side load patterns.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/descobrir` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `../foundation_documentation/modules/account_profile_catalog_module.md`

### Decision Baseline (Frozen)

- `D-01`: Discovery must not preload the entire account-profile catalog during screen init.
- `D-02`: Discovery first paint must depend only on the paged public listing endpoint plus the existing `nearby` and `live now` side sections.
- `D-03`: Repository `init()` must not hide repository-wide scans or multipage fetches on the critical path.
- `D-04`: Public account-profile list queries used by Discovery must have an index-backed default path for `visibility + is_active + profile_type + created_at`.
- `D-05`: Search semantics must not be changed opportunistically in this lane; any search optimization must preserve current UX or be explicitly split out.

### Problem Snapshot

- `DiscoveryScreenController.init()` calls `AccountProfilesRepository.init()`.
- `AccountProfilesRepository.init()` currently executes `fetchAllAccountProfiles()`.
- `fetchAllAccountProfiles()` loops up to `10` pages of `30` items before Discovery performs its real page-1 fetch.
- Discovery then also triggers:
  - paged feed load,
  - nearby sync,
  - live-now refresh.
- Result: redundant startup traffic, delayed first paint, and broad feed rebuild pressure.

### Scope

- Remove eager multi-page account-profile preload from Discovery startup.
- Keep Discovery favorites/bootstrap working without requiring catalog hydration.
- Preserve paged loading, nearby loading, and live-now loading behavior.
- Add/adjust backend indexes for the default public account-profile listing path used by Discovery.
- Add focused Flutter/Laravel automated coverage for the corrected startup/query path.

### Out of Scope

- Redesign of Discovery information architecture.
- Search-behavior redesign.
- New backend feed contracts.
- Image/CDN/media-system redesign.

---

## Rule/Workflow Sources

- `../delphi-ai/main_instructions.md`
- `../foundation_documentation/policies/scope_subscope_governance.md`
- `../foundation_documentation/policies/query_path_guardrails.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-todo-driven-execution-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/flutter-performance-smell-scanner/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md`

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-screen-discovery-performance-hardening.md`
- `lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`
- `lib/infrastructure/repositories/account_profiles_repository.dart`
- `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `test/infrastructure/repositories/account_profiles_repository_test.dart`
- `../laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php`
- `../laravel-app/database/migrations/tenants/*.php` (new index migration only if needed)
- `../laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` (only if backend listing behavior/index safety needs explicit coverage)

### Ordered Steps

1. Add fail-first Flutter coverage proving Discovery init must not depend on repository-wide multipage preload.
2. Refactor `AccountProfilesRepository.init()` to bootstrap only the minimal state needed for Discovery/favorites.
3. Keep Discovery controller behavior paged-first and preserve nearby/live-now side loads.
4. Add the missing compound index for the default public account-profile listing path.
5. Re-run focused Flutter + Laravel gates and record evidence.

### Test Strategy

- `test-first`

### Fail-First Targets

- Repository init no longer performs full catalog fetch.
- Discovery controller init still renders page 1 and side sections correctly without repository-wide preload.
- Public account-profile listing path remains functionally stable after index hardening.

---

## Execution Outcome Snapshot (`2026-04-03`)

- `AccountProfilesRepository.init()` now boots favorites only and no longer performs a multi-page account-profile preload.
- Discovery controller/tests now prove page-1 rendering does not depend on `allAccountProfilesStreamValue` hydration.
- Public account-profile list path received a dedicated compound index for `visibility + is_active + profile_type + deleted_at + created_at + _id`.
- This lane now anchors the broader runtime-query guardrail baseline documented in `../foundation_documentation/policies/query_path_guardrails.md`.
- Remaining work is runtime/manual validation of Discovery responsiveness and UX behavior.
