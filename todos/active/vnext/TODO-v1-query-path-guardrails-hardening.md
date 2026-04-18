# TODO (V1): Query Path Guardrails Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Audit-Completed`, `Partial-Remediation-Implemented`, `Automated-Validated`
**Next exact step:** Remove the remaining tenant-admin `loadAll*` / page-loop interactive flows and delete the last abstract-contract/default repository fallbacks that still normalize unbounded runtime queries.
**Owners:** Flutter Team, Laravel Team
**Objective:** Establish hard, no-fallback query-path guardrails for production runtime flows and remove the remaining structural anti-patterns that rely on preload scans, local pagination, or in-memory filtering/sorting where direct/index-backed query paths should exist.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`, `docs`

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `shared`
- **Subscope:** `runtime query paths`

---

## Module / Policy Anchors

- **Primary policy:** `../foundation_documentation/policies/query_path_guardrails.md`
- **Primary module:** `../foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary modules:** `../foundation_documentation/modules/account_profile_catalog_module.md`, `../foundation_documentation/modules/events_module.md`

---

## Decision Baseline (Frozen)

- `QPG-D-01`: Production runtime flows must not use `fetchAll*`, `loadAll*`, or page-by-page loops at all; if a direct/index-backed path does not exist, that contract bug must be fixed at the source.
- `QPG-D-02`: Unique-key lookup (`id`, `slug`, `occurrence_id`) must always use direct backend lookup, never local list scanning.
- `QPG-D-03`: Request-path filtering/sorting/deduplication belongs in the database/query layer, not in PHP/Dart after broad collection loads.
- `QPG-D-04`: Abstract repository contracts must not normalize local pagination over full collections.
- `QPG-D-05`: The only approved MVP exception is public text search for `Account Profile`, and it remains backend-only + paginated.
- `QPG-D-06`: If a direct/index-backed path does not exist, that is a contract bug to fix, not a reason to add fallback scans.

---

## Why This TODO Exists

This session surfaced the same architectural failure mode multiple times:

- client-side critical paths preloading large lists before the real UI query,
- slug resolution falling back to list scans,
- page-by-page fetch loops followed by local filtering,
- backend request paths calling `get()` or scanning collections and deciding final results in PHP instead of indexed queries,
- “temporary” fallbacks becoming normalized architecture.

The goal of this TODO is to turn those lessons into durable guardrails and to track the repo surfaces that still violate them.

---

## Audit Outcome Snapshot (`2026-04-03`)

### Analyzer / Tooling

- `bash ./scripts/reset_analyzer_state.sh` did **not** finish cleanly during warmup; the analysis server crashed with `Bus error / Bad state: The analysis server crashed unexpectedly`.
- The canonical gate run immediately after recovery succeeded:
  - `fvm dart analyze --format machine` -> clean, `exit 0`

### Highest-Severity Runtime Findings

- `High`: [user_events_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/user_events_repository.dart#L95) still paginates through the confirmed-events feed to synthesize `fetchMyEvents()` in runtime.
- `High`: [tenant_admin_events_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart#L846) still preloads all physical-host candidates via `fetchAllEventAccountProfileCandidates()` for an interactive form path.
- `Medium`: multiple abstract repository contracts still define page APIs by loading full collections and slicing locally:
  - [tenant_admin_accounts_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_accounts_repository_contract.dart#L106)
  - [tenant_admin_account_profiles_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_account_profiles_repository_contract.dart#L154)
  - [tenant_admin_events_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_events_repository_contract.dart#L97)
  - [tenant_admin_organizations_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_organizations_repository_contract.dart#L86)
  - [tenant_admin_static_assets_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_static_assets_repository_contract.dart#L90)
  - [tenant_admin_taxonomies_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_taxonomies_repository_contract.dart#L102)
- `Medium`: backend public search still uses `like/regex` without a stronger indexed strategy in:
  - [AccountProfileQueryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php#L413)
  - [EventQueryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php#L661)
  - [AccountProfileResolverAdapter.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Integration/Events/AccountProfileResolverAdapter.php#L139)

### Confirmed Good Paths

- direct public slug lookup on account profiles is now correct in [AccountProfileQueryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php#L221)
- direct repository slug lookup on tenant-admin accounts is correct in [tenant_admin_accounts_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart#L137)
- occurrence-first agenda lookup already streams indexed occurrences correctly in [AccountProfileAgendaOccurrencesService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php#L21)
- imported-contact matching now resolves through hash-indexed lookup instead of request-path full-user scans in [InviteIdentityGatewayAdapter.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Integration/Invites/InviteIdentityGatewayAdapter.php#L64)
- public schedule slug/detail lookup is direct-only and no longer falls back to catalog scans in [schedule_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/schedule_repository.dart#L129)
- public account-profile runtime no longer exposes `fetchAllAccountProfiles()` / search-preload paths in [account_profiles_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/account_profiles_repository.dart#L32)

---

## Scope

- codify the no-fallback runtime query rules from `query_path_guardrails.md`
- remove every remaining production-runtime `fetchAll*` / `loadAll*` path and local-pagination default from Flutter contracts/controllers/repositories
- remove request-path full-collection scans in Laravel where direct/index-backed queries are required
- ensure Flutter/Laravel runtime code respects the direct-lookup and indexed-query model
- define the single MVP exception boundary for Account Profile text search

## Out of Scope

- replacing the entire search stack in this TODO
- batch/job/projection rebuild flows
- non-runtime admin/export scripts unless they are incorrectly reused by production runtime paths

---

## Current Inventory of Violations

### Flutter

1. `UserEventsRepository`
   - [user_events_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/user_events_repository.dart#L95)
   - issue: loops through confirmed agenda pages to synthesize a local list.

2. `TenantAdminEventsController`
   - [tenant_admin_events_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart#L846)
   - issue: interactive preload of all physical-host candidates.

3. Abstract repository contracts embedding local pagination fallback:
   - [tenant_admin_accounts_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_accounts_repository_contract.dart#L106)
   - [tenant_admin_account_profiles_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_account_profiles_repository_contract.dart#L154)
   - [tenant_admin_events_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_events_repository_contract.dart#L97)
   - [tenant_admin_organizations_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_organizations_repository_contract.dart#L86)
   - [tenant_admin_static_assets_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_static_assets_repository_contract.dart#L90)
   - [tenant_admin_taxonomies_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/tenant_admin_taxonomies_repository_contract.dart#L102)

4. Interactive admin controllers still issuing full-catalog loads:
   - [tenant_admin_account_create_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart#L125)
   - [tenant_admin_account_profiles_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/account_profiles/controllers/tenant_admin_account_profiles_controller.dart#L252)
   - [tenant_admin_static_assets_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/static_assets/controllers/tenant_admin_static_assets_controller.dart#L242)
   - [tenant_admin_profile_types_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart#L408)
   - [tenant_admin_static_profile_types_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/static_profile_types/controllers/tenant_admin_static_profile_types_controller.dart#L448)
   - [tenant_admin_settings_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart#L471)
   - issue: form/runtime initialization still depends on `loadAll*` catalog hydration.

### Laravel

1. `AccountProfileResolverAdapter`
   - [AccountProfileResolverAdapter.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Integration/Events/AccountProfileResolverAdapter.php#L120)
   - issue: still materializes broad profile lists and relies on `like`-search for candidate discovery; needs review for stricter direct/indexed paths.

2. Public search paths with MVP exception boundary:
   - [AccountProfileQueryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php#L413)
   - [EventQueryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php#L661)
   - issue: regex/like strategies; only Account Profile public text search remains allowed in MVP.

---

## Controller / Surface Scrutiny Summary

### Flutter interactive surfaces

- `tenant_public.discovery`
  - preload bug already fixed in its own lane; now governed by `QPG-02`.
- `tenant_public.schedule`
  - public slug/detail path is corrected; remaining schedule-adjacent runtime violation is now concentrated in [user_events_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/user_events_repository.dart#L95), not the repository itself.
- `tenant_public.user_events`
  - still violates `QPG-02` / `QPG-04` in [user_events_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/user_events_repository.dart#L95).
- `tenant_admin.*` form/catalog controllers
  - multiple screens still violate `QPG-02` by hydrating entire registries/taxonomies/profile-type catalogs at init time instead of using direct/paged contracts.

### Laravel request-path surfaces

- `invites`
  - request-path full-user scan was removed; the path is now hash-indexed.
- `account profile public search`
  - explicitly allowed MVP exception, but must remain backend-only + paginated.
- `event search`
  - current regex path in [EventQueryService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php#L661) violates the new rule set and must be split into its own remediation lane.
- `event candidate resolution`
  - [AccountProfileResolverAdapter.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Integration/Events/AccountProfileResolverAdapter.php#L139) still needs a direct/index-backed contract for interactive candidate lookup.

---

## Ordered Next Steps

1. Remove local-pagination defaults from abstract Flutter repository contracts.
2. Replace the remaining production-runtime `fetchAll*` / `loadAll*` admin consumers with direct paginated/searchable paths.
3. Replace `UserEventsRepository.fetchMyEvents()` page-loop synthesis with a direct canonical contract.
4. Rework `AccountProfileResolverAdapter` to use direct/index-backed candidate lookup.
5. Split the remaining event-search performance work into a dedicated lane if it changes behavior or indexing strategy.

---

## Success Criteria

- no production runtime path depends on `fetchAll*`, `loadAll*`, or local page slicing
- no unique-key lookup depends on list scanning
- no request-path PHP/Dart flow loads broad collections and then decides final filtering/sorting in memory
- the single MVP exception for Account Profile text search remains explicit and bounded
- the repo has a documented inventory of any remaining violations until they are removed
