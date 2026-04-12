# Audit Package: Tenant Admin Domain Management + Event Ops (Contract Hardening)

**Status:** Derived, non-authoritative correctness and contract-hardening review packet  
**Related TODO:** `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-admin-domain-management-and-events-ops.md`  
**Audit review log:** `foundation_documentation/artifacts/reviews/tenant-admin-domain-events-final-audit-checkpoint-b.md`  
**Prepared on:** `2026-04-12`

## Scope
- Tenant-admin web-domain management uses the active-domain read contract under `GET /admin/api/v1/domains`.
- Tenant-admin event management list reads use only the approved manager filters: `date`, `temporal`, `venue_profile_id`, and `related_account_profile_id`.
- The venue and related-profile picker UX is backed by paged server-driven discovery under `GET /admin/api/v1/events/account_profile_candidates`; it supports filter selection but is not itself an event-list filter.
- The touched admin event-management path must not depend on hardcoded artist-shaped payload keys.

## Contract Corrections Applied
- Flutter domain-management UI remains active-domain only; deleted-domain restore/force-delete stays out of scope.
- Laravel `GET /admin/api/v1/domains` lists active web domains only, ordered deterministically before pagination.
- Tenant-admin event management formatting no longer routes through the public artist projection path and does not expose `artists` in admin payloads.
- Tenant-admin event list UX groups cards by date and uses a higher-signal manager card shape aligned to the home list, while keeping the admin contract explicit instead of analogy-driven.
- Retired manager direct search is now explicit, not implicit: Flutter does not surface it, the admin repository does not serialize it, and Laravel rejects `search` on `GET /admin/api/v1/events`.

## Derived Domain Contract Snapshot

**Authoritative anchor:** `foundation_documentation/modules/tenant_admin_module.md` section `GET /admin/api/v1/domains`

### `GET /admin/api/v1/domains`
- Query inputs:
  - `page`
  - `per_page`
- Page-size rule:
  - request is bounded to a safe maximum of `100`
- Stable order:
  - `created_at DESC`
  - `_id DESC` tie-breaker
- Query boundary:
  - `tenant->domains()`
  - `where('type', Tenant::DOMAIN_TYPE_WEB)`
  - no `withTrashed()`
  - `paginate(...)`
- Minimal list item contract:
  - `id`
  - `path`
  - `type`
  - `status`
  - `created_at`
  - `updated_at`
  - `deleted_at`
- UI identity / lifecycle semantics:
  - `id` is the delete identity
  - `status=active` is the only lifecycle state surfaced in the current settings UI
  - deleted-domain restore/force-delete remain backend-only lifecycle paths outside this read contract

## Derived Event Manager Contract Snapshot

**Authoritative anchor:** `foundation_documentation/modules/tenant_admin_module.md` section `GET /admin/api/v1/events`

### `GET /admin/api/v1/events`
- Query inputs:
  - `date`
  - `temporal`
  - `venue_profile_id`
  - `related_account_profile_id`
  - `page`
  - `page_size`
- Explicitly rejected / non-canonical:
  - direct manager `search`
  - artist-shaped management payload keys such as `artists`
- Auxiliary selector dependency:
  - searchable venue and related-profile pickers are backed by `GET /admin/api/v1/events/account_profile_candidates`
  - that selector contract is paged and server-driven, but it is not an extra list filter on `GET /admin/api/v1/events`
- Page-size rule:
  - `page_size <= 100`
- Stable order:
  - `date_time_start DESC`
  - `_id DESC` tie-breaker

### Filter Ownership Map
- `date`
  - authoritative backend source: `events.date_time_start`
  - query shape: `where('date_time_start', '>=', dayStart)` + `where('date_time_start', '<', nextDayStart)`
  - manager read field used for operator scanning: `date_time_start` / `occurrences[*].date_time_start`
- `temporal`
  - authoritative backend source: `events.date_time_start` + `events.date_time_end` (with default-duration fallback)
  - query shape: `$expr` over `date_time_start` / effective end
  - manager read field used for operator scanning: `date_time_start`, `date_time_end`
- `venue_profile_id`
  - authoritative backend source: `place_ref.id` / `place_ref._id`
  - query shape: `whereIn('place_ref.id', ...)` or `orWhereIn('place_ref._id', ...)`
  - manager read field used for operator scanning: `venue`, `place_ref`
- `related_account_profile_id`
  - authoritative backend source: `event_parties[*].party_ref_id` with `party_type != venue`
  - query shape: `whereRaw({ event_parties: { $elemMatch: ... } })`
  - manager read field used for operator scanning: `event_parties`, `linked_account_profiles`

### Manager Card Contract
- Minimal manager-facing fields used by the grouped list:
  - `event_id`
  - `title`
  - `thumb`
  - `date_time_start`
  - `date_time_end`
  - `venue.display_name`
  - `linked_account_profiles[*].display_name`
  - `linked_account_profiles[*].profile_type`
  - `publication.status`
  - `updated_at`
- Admin decoding boundary:
  - Flutter consumes `linked_account_profiles` directly
  - Flutter does not synthesize related profiles from legacy `artists`

### Date Grouping Semantics
- Group headers are derived from the ordered server result currently loaded in the controller.
- Pagination is continuity-aware across appended pages because Flutter rebuilds sections from the accumulated ordered list, not page-local fragments.
- Filter changes reset pagination back to page `1` before regrouping, so stale groups are discarded instead of patched in place.

### Auxiliary Candidate Selector Contract
- Endpoint:
  - `GET /admin/api/v1/events/account_profile_candidates`
- Query inputs:
  - `type=physical_host|related_account_profile`
  - `search`
  - `page`
  - `page_size|per_page` (`page_size` preferred, `per_page` compatibility alias)
- Page-size rule:
  - `page_size <= 50`
- Stable order:
  - `display_name ASC`
  - `_id ASC` tie-breaker
- Operational role:
  - powers the searchable venue and related-profile pickers used by the manager filter chips
  - keeps picker search server-driven and paginated instead of preloading a tenant-wide profile snapshot
  - reuses the canonical current `physical_host` contract for venue selection because canonical event writes persist `place_ref` only after `resolvePhysicalHostByProfileId(...)` validates POI capability and coordinates
  - `related_account_profile` excludes canonical venue profiles so the picker cannot surface candidates that violate downstream related-profile semantics by construction
- Scope note:
  - this selector contract pre-existed the final manager list hardening and is documented here because the current UX depends on it
  - server-driven pagination bounds selector payload size only; it does **not** prove query selectivity, predicate efficiency, or scan safety for selector search

## Source-Inspection Evidence

### Domain Read Path
- `app/Application/Tenants/TenantDomainManagementService.php::list()`
  - `where('type', Tenant::DOMAIN_TYPE_WEB)`
  - `orderByDesc('created_at')`
  - `orderByDesc('_id')`
  - `paginate($resolvedPerPage, ...)`
  - active list tests now prove both different-timestamp ordering and same-timestamp `_id` tie-break behavior

### Event Read Path
- `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php::paginateManagement()`
  - starts from `Event::query()`
  - applies `date` before pagination through `applyManagementSpecificDateFilter(...)`
  - applies `temporal` before pagination through `applyManagementTemporalFilter(...)`
  - applies `venue_profile_id` before pagination through `applyManagementVenueFilter(...)`
  - applies `related_account_profile_id` before pagination through `applyManagementRelatedAccountProfileFilter(...)`
  - orders by `date_time_start DESC, _id DESC`
  - paginates only after filter composition
- `packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventIndexRequest.php`
  - `search` is `prohibited`
  - `page_size` is capped at `100`
- `packages/belluga/belluga_events/src/Application/Events/EventManagementService.php::resolveLocationPayload()`
  - persists `place_ref` only after `resolvePhysicalHostByProfileId(...)`
  - rejects physical hosts without POI capability or valid coordinates before canonical event writes can reference them

### Auxiliary Candidate Selector Path
- `packages/belluga/belluga_events/src/Http/Api/v1/Controllers/EventsController.php::accountProfileCandidates()`
  - resolves `type`, `search`, `page`, and `page_size|per_page`
  - delegates to the profile resolver without local snapshot filtering
- `packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventAccountProfileCandidatesRequest.php`
  - `type` is required and bounded to `related_account_profile|physical_host`
  - `page_size|per_page` is capped at `50`
- `app/Integration/Events/AccountProfileResolverAdapter.php::paginateAccountProfileCandidates()`
  - clamps page size to `1..50`
  - orders by `display_name`, `_id`
  - paginates before response mapping
  - excludes canonical `venue` profiles from the `related_account_profile` candidate query

### Partial Index / Constraint Mapping
- Explicitly documented in this packet:
  - stable order keys are `date_time_start` and `_id`
  - slice-specific management index `idx_events_related_profile_management_v1` is defined in `laravel-app/packages/belluga/belluga_events/database/migrations/2026_04_12_000200_add_related_profile_management_index_to_events.php` over `event_parties.party_ref_id + date_time_start + _id`
- Event-list paths explicitly **not** performance-verified in this packet:
  - legacy venue OR branch using `place_ref.id` / `place_ref._id`
  - temporal `$expr` branch
  - non-venue discriminator support inside the related-profile `$elemMatch`
- Supporting endpoints intentionally not performance-signed-off here:
  - `GET /admin/api/v1/domains` is treated as a tenant-scoped bounded admin read; no separate index-plan evidence for `created_at DESC, _id DESC` is bundled
  - `GET /admin/api/v1/events/account_profile_candidates` is treated as an existing paged selector dependency; bounded request/response behavior is evidenced, but no dedicated query-plan artifact is bundled
- Interpretation boundary:
  - this packet treats those paths as structurally query-bound and correctness-tested
  - this packet does **not** claim benchmark-grade or explain-plan proof that every branch or supporting endpoint is index-backed
  - selector pagination/search UX does **not** imply index-backed search or scan-safe selector execution
- Companion endpoint review artifact:
  - `foundation_documentation/artifacts/reviews/tenant-admin-events-endpoint-performance.md` (`informational_only`; not required for checkpoint-B closure of this bounded slice)

## Fail-First Evidence Boundary
- This is a brownfield correction slice executed after the artist-hardcode blocker and tenant-domain path were already in motion.
- Preserved fail-first transcripts are not available in this packet.
- The packet therefore records named regression tests plus decisive assertions, and it does **not** claim preserved fail-first evidence where that history was not captured.
- The governing TODO records an explicit brownfield fail-first-history waiver so this packet does not need to imply full test-first provenance it cannot prove.

## Validation Evidence

### Flutter Focused Suites Passed
- `test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart`
  - covers specific-date forwarding and temporal reset behavior
  - covers backend-driven paginated related-profile picker search and reset-on-query-change behavior
  - covers controller-level error propagation when a backend-owned filtered reload fails
- `test/presentation/tenant_admin/events/tenant_admin_events_screen_test.dart`
  - drives the specific-date filter through the real date-picker widget flow
  - covers grouped-by-date rendering
  - covers venue + related-profile + specific-date filter composition
  - covers cross-page regrouping continuity and filter-reset rebuild behavior
  - asserts no direct text-search field is exposed on the manager list surface
- `test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
  - proves admin list requests serialize `date`, `temporal`, `venue_profile_id`, `related_account_profile_id`
  - proves retired direct search is not serialized
  - proves venue/related-profile picker search uses the dedicated paged `account_profile_candidates` endpoint
- `test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart`
  - proves admin decoding does not synthesize related profiles from legacy `artists`

### Laravel Focused Suites Passed
- filtered `tests/Feature/Events/EventCrudControllerTest.php`
  - `test_event_create_accepts_dynamic_account_profile_party_type_and_keeps_admin_and_public_read_models_separate`
  - `test_event_index_filters_by_venue_profile_id`
  - `test_event_index_filters_by_related_account_profile_id_without_matching_venue_semantics`
  - `test_event_index_filters_by_specific_date`
  - `test_event_index_rejects_legacy_search_query_param`
  - `test_event_index_rejects_page_size_above_safe_maximum`
  - `test_event_index_uses_stable_tie_break_order_for_matching_start_times`
  - `test_event_index_composes_specific_date_temporal_and_profile_filters`
  - `test_event_index_filters_by_temporal_buckets`
  - `test_event_index_temporal_filter_uses_default_duration_when_end_is_missing`
  - `test_event_create_rejects_physical_host_without_location`
  - `test_event_account_profile_candidates_endpoint_allows_read_create_or_update_ability_and_returns_filtered_candidates`
  - `test_event_account_profile_candidates_endpoint_paginates_related_account_profiles_beyond_one_hundred_results`
  - `test_event_account_profile_candidates_endpoint_excludes_canonical_venue_profiles_from_related_results`

### Domain-Management Validation Passed
- `tests/Feature/Tenants/TenantDomainControllerTest.php`
  - `test_store_creates_domain`
  - `test_store_rejects_duplicate_domain_for_same_tenant`
  - `test_destroy_soft_deletes_domain`
  - `test_restore_brings_back_domain`
  - `test_force_delete_removes_domain`
  - `test_index_lists_only_active_web_domains_with_pagination_order`
  - `test_index_uses_stable_id_tie_break_for_matching_created_at`
  - `test_index_clamps_per_page_to_safe_maximum`
- `test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
  - domain page decoding
  - create-domain success
  - duplicate-domain validation message preservation
- `test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
  - widget-driven domain add/delete flow
  - widget-driven duplicate-domain error rendering
  - controller pagination + create + delete
  - duplicate-domain validation error is surfaced without mutating the active-domain list

## Runtime / Database Evidence Boundary
- Captured in this packet:
  - source inspection of query shape
  - focused contract tests
  - deterministic ordering / page-size regression coverage
  - existing candidate-selector paging/authorization coverage needed by the manager filter chips
- Not captured in this bounded slice:
  - explain-plan artifacts
  - benchmark traces
  - production-scale query telemetry
  - a real Flutter-to-Laravel seam run for the tenant-admin domain/events flows
- Residual interpretation:
  - the slice is structurally performance-aware, not performance-verified
  - correctness and bounded-input behavior are evidenced
  - branch-level scan safety for temporal / venue legacy-OR / non-venue discriminator paths is not claimed beyond source-shape inspection
  - tenant-domain list ordering and candidate-selector paging are treated as bounded operational dependencies, not benchmarked performance proofs
  - client assurance is contract-level across separate Laravel + Flutter suites, not end-to-end seam proof
  - the governing TODO records an explicit bounded performance waiver so closure language remains correctness-focused rather than performance-clean

## Audit Posture
- Contract clarity: strong enough for bounded-slice review because the packet anchors each snapshot back to canonical module sections and states the manager-card/grouping rules explicitly.
- Test confidence: bounded brownfield regression confidence is strong for removed `search`, no-`artists` admin decoding, duplicate-domain surfacing, same-timestamp domain ordering, widget-driven specific-date/domain flows, filtered-reload error surfacing, temporal null-end fallback, candidate-selector paging, and cross-page regrouping/reset behavior, while fail-first history is explicitly recorded as unavailable and waived for this slice.
- Compatibility confidence: contract-level only. The packet relies on separate Laravel + Flutter validation rather than a real client-to-server admin seam run, and it does not claim end-to-end proof.
- Performance confidence: limited to query-shape correctness, stable ordering, bounded page size, partial index mapping, and explicit supporting-endpoint scope notes; this packet intentionally stops short of claiming explain-plan or benchmark-grade scan safety and relies on the TODO-level bounded waiver for closure language.
