# TODO-v1 Home Favorites Query Contract

## Scope
- Establish the ideal data contract for favorites consumption in Home and Discovery so both surfaces stop using duplicate/legacy fetch composition.
- Define whether favorites should consume a dedicated projection, shared home snapshot, or lazy follow-up query.
- Define the required Flutter adaptation so Home and Discovery Favorites consume the new favorites payload format directly (without agenda-based reordering).

## Out Of Scope
- Implementing the contract in this TODO.
- `my events` query redesign.

## Problem Statement
- `FavoritesSectionController` currently fetches upcoming events directly on Home initialization just to reorder favorites.
- This duplicates agenda traffic and ties favorites ordering to an eager network call.
- Discovery Favorites must align with the same query contract to avoid divergent ordering/data semantics between Home and Discovery.

## Canonical Contract Decision (Proposal)
- Keep a two-collection read model:
  - `favorite_edges` (generic user favorites: owner -> target + registry metadata).
  - registry-owned snapshot collection (collection strategy defined by registry).
- `registry_key` is a required field in the package model and must be indexed.
- Query filter by `registry_key` is optional.
- Registry contract must declare:
  - `registry_key` (required, snake_case),
  - `snapshot_collection` (optional, explicit when provided for visibility).
- Collection convention:
  - when `snapshot_collection` is explicitly provided, it must be exactly `favoritable_{registry_key}_snapshots`.
  - when omitted, registry uses package default snapshot collection `favoritable_snapshots`.
- Shared-collection rule:
  - two registries may point to the same collection only when they share the common envelope fields: `registry_key`, `target_type`, `target_id`, `updated_at`.
- Convention enforcement is centralized in `Guardrail Consolidation (Required)` below (not Pint).
- Scope stays Account Profile only in V1 (`target_type=account_profile`) and is reused across contexts, not Home-only.
- For V1 `registry_key=account_profile`, the derived dedicated collection name resolves to `favoritable_account_profile_snapshots` by convention rule (not hardcoded), and this registry uses dedicated indexes for `event_occurrences` date ordering.
- Endpoint remains `GET /api/v1/favorites` and returns favorites for Home + Discovery based on `favorite_edges` + snapshot join.
- Navigation target on favorite tap is always the account profile (not direct event navigation).
- Ordering policy is fixed in three blocks:
  - Block A: items with `next_event_occurrence_at` (ascending).
  - Block B: items without next event but with `last_event_occurrence_at` (descending).
  - Block C: items with both event dates null (fallback by `favorited_at`, descending).

## Tactical Implementation Path
- Backend:
  - Add `favorite_edges` with `registry_key`, `favorited_at`, and owner query indexes.
  - Add registry contract fields: `registry_key`, optional `snapshot_collection`, concrete snapshot builder, and declared index profile.
  - Allow multiple registries to share one collection only when they keep the common envelope (`registry_key`, `target_type`, `target_id`, `updated_at`) and compatible index profile.
  - For V1 AccountProfile registry (`registry_key=account_profile`), write snapshots into the derived collection name (`favoritable_account_profile_snapshots`) with `next_event_occurrence_id`, `next_event_occurrence_at`, `last_event_occurrence_at`, and display/navigation snapshot fields.
  - Update snapshots asynchronously via job dispatch whenever:
    - account profile data changes, or
    - related occurrence CRUD changes.
  - Do not trigger both event CRUD and occurrence CRUD snapshot jobs when event writes already persist occurrences in the same flow.
  - Keep generic registry contract minimal; concrete AccountProfile registry decides snapshot assembly and event-derived fields.
- Flutter:
  - Remove `ScheduleRepository.fetchUpcomingEvents()` dependency from `FavoritesSectionController`.
  - Ensure Discovery Favorites screen/controller also consumes `/api/v1/favorites` (same repository flow as Home).
  - Consume `/api/v1/favorites` snapshot-driven ordering across both surfaces.
  - Map the new snapshot envelope fields in the shared client model used by Home and Discovery (`registry_key`, `target_type`, `target_id`, `favorited_at`, `next_event_occurrence_at`, `last_event_occurrence_at`, display/navigation snapshot fields).
  - Keep ordering fully backend-driven in Home and Discovery, with no local reorder that depends on agenda fetches.
  - Keep favorite tap behavior routed to account profile detail only.
- Mocks:
  - Mirror the three ordering blocks (`next_event_occurrence_at`, `last_event_occurrence_at`, `favorited_at`) to keep Home and Discovery behavior deterministic.

## Guardrail Consolidation (Required)
- Enforcement source: `laravel-app/scripts/architecture_guardrails.php` executed by strict lint/CI (`composer run lint:strict`); this rule is not enforced by Pint.
- Guardrail must fail when:
  - `registry_key` is missing or not snake_case (`^[a-z][a-z0-9_]*$`).
  - explicit `snapshot_collection` does not match `favoritable_{registry_key}_snapshots`.
  - a registry declares non-default/specific indexes while omitting `snapshot_collection` (default shared collection forbidden in this case).
  - a registry points to a shared collection but does not keep common envelope fields (`registry_key`, `target_type`, `target_id`, `updated_at`).
  - migration collection naming diverges from registry declaration/convention (typo protection for snapshot collections).
  - tenant-aware migration registration is missing in `config/multitenancy.php` (`tenant_migration_paths`) for the package migration path.
- Guardrail success criteria:
  - registry declaration, collection naming, index profile, and migration paths remain 1:1 consistent.

## Contract Notes (V1)
- `tenant_id` is omitted from payload/contracts for this flow because reads happen inside the active tenant database.
- `tenant_id` is not persisted in `favorite_edges` or snapshot collections for this flow because data is tenant-isolated by database.
- `profile_type` is not part of the generic favoritable contract; if needed later, it must come from a concrete registry mapping.
- `registry_key` is part of the package contract and is always stored; request-time filtering by `registry_key` remains optional.
- `snapshot_collection` may be omitted to use `favoritable_snapshots` default collection.
- registries requiring custom/specific indexes must explicitly declare dedicated `snapshot_collection`.
- Event reference in snapshot is occurrence-oriented (`next_event_occurrence_id` / `next_event_occurrence_at`), while navigation stays profile-oriented.

## Migration & Index Notes (V1)
- `favorite_edges`:
  - unique index: `(owner_user_id, registry_key, target_type, target_id)`.
  - read indexes:
    - `(owner_user_id, favorited_at)`,
    - `(registry_key)`.
- `favoritable_snapshots` (package default collection):
  - shared envelope indexes only:
    - unique index: `(registry_key, target_type, target_id)`,
    - read index: `(registry_key, updated_at)`.
  - registries using this default must not require custom index profiles.
- Derived dedicated collection for `registry_key=account_profile` (`favoritable_account_profile_snapshots`):
  - unique index: `(registry_key, target_type, target_id)`.
  - date-order indexes for occurrence-aware ranking:
    - `(next_event_occurrence_at)`,
    - `(last_event_occurrence_at)`.
- Tenant-aware migration registration (Laravel current pattern):
  - create package migrations under `packages/belluga/belluga_favorites/database/migrations`,
  - register the path in `laravel-app/config/multitenancy.php` -> `tenant_migration_paths`,
  - rely on tenant migration execution via the configured multitenancy action (`migrate_tenant`) and tenant connection.

## Documentation Hooks (Required Before Implementation)
- Add the favorites endpoint contract under `foundation_documentation/endpoints_mvp_contracts.md` (Home + Discovery).
- Register the endpoint in `foundation_documentation/system_roadmap.md` with status `Defined`.

## Definition Of Done
- Canonical contract decision documented.
- Tactical implementation path defined.
- Validation strategy defined.
- Frontend consumption adjustments are explicitly defined for the new payload format in Home and Discovery Favorites.

## Validation Steps
- Review Home composition and request graph.
- Review Discovery Favorites composition and request graph.
- Define target backend/client contract and expected tests.
- Validate Home Favorites no longer triggers a duplicate upcoming agenda fetch on initial load.
- Validate Discovery Favorites uses the same `/api/v1/favorites` contract path and does not use legacy parallel composition.
- Validate snapshot refresh jobs run on account profile update + event/occurrence CRUD and update ordering fields.
- Validate Home ordering blocks (`next_event_occurrence_at` -> `last_event_occurrence_at` -> `favorited_at`) in integration tests.
- Validate Flutter mapping and rendering in Home + Discovery using the new `/api/v1/favorites` payload shape (including empty-list behavior) without fallback to agenda-based data composition.
