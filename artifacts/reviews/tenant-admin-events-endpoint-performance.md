# Endpoint Performance Review

- Endpoint: `GET /admin/api/v1/events`
- Access Pattern: `bounded-list`
- Created At: `2026-04-12 19:50:00 UTC`
- Review Scope: event-list endpoint only; the paged `account_profile_candidates` selector is an operational dependency for filter pickers, but it is not the subject of this endpoint note.

## Canonical Lookup Path
- Backend query path: `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php::paginateManagement()`
- Client/repository path: `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_events_repository.dart::fetchEventsPage(...)`
- Exact venue/profile narrowing remains query-param based; the Flutter manager does not perform local post-filtering over a wider preload snapshot.
- Stable order is explicit before pagination: `date_time_start DESC`, `_id DESC`.

## Lookup Keys
- `date`
- `temporal`
- `venue_profile_id`
- `related_account_profile_id`
- page controls (`page`, `page_size` / `per_page`)

## Input Bounds
- `page_size` on `GET /admin/api/v1/events` is capped at `100`
- retired manager `search` is explicitly rejected by request validation

## Partial Index / Constraint Support
- Existing event collection indexes:
  - `date_time_start`
- Slice-specific index support:
  - `idx_events_related_profile_management_v1` over `event_parties.party_ref_id + date_time_start + _id`
- Not treated as supported by this note without stronger evidence:
  - venue filtering through the current `place_ref.id` / `place_ref._id` OR predicate
  - temporal `$expr` filtering with default-duration fallback
  - non-venue discriminator support inside the related-profile `$elemMatch`

## Forbidden Fallback Patterns
- page-walk exact lookup through paginated list endpoint
- broad fetch plus in-memory filter for exact key
- client-side venue/profile matching after multi-page list traversal

## Evidence
- Heuristic audit output:
  - `paginateManagement()` applies `date`, `temporal`, `venue_profile_id`, and `related_account_profile_id` before `paginate($perPage)`
  - `paginateManagement()` orders by `date_time_start DESC, _id DESC`
  - direct text search is not part of the current manager contract and is rejected at request validation
  - management list formatting no longer delegates to the full public formatter and no longer issues per-row `EventOccurrence` lookups
- Explain / query-log / benchmark evidence:
  - not captured in this bounded slice
  - focused feature tests passed for venue filter, related-profile filter, specific-date filter, composed specific-date/temporal/profile filtering, temporal buckets, and null-end temporal fallback
- Residual risk:
  - no explain-plan artifact is bundled here
  - the review relies on source inspection plus focused feature coverage rather than benchmark evidence
  - current venue filtering is structurally correct but remains performance-risky because the query shape does not currently prove a leading-prefix match for the historical `place_ref.type + place_ref.id + date_time_start + _id` index
