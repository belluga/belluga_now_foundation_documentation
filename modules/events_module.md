# Documentation: Events Module

**Version:** 1.0
**Date:** March 1, 2026
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Events module is the canonical backend domain for event lifecycle, occurrence projections, publication state, stream deltas, and extension capability orchestration. It is implemented by `belluga_events` and consumed by host routes, Agenda clients, and integration packages.

This module is the canonical source for stable Events decisions. Tactical TODOs remain execution artifacts and must promote stable decisions here before closure.

## 2. Canonical Anchors

- Runtime/package truth:
  - `laravel-app/packages/belluga/belluga_events/README.md`
- Program streams:
  - `foundation_documentation/todos/completed/TODO-v1-events-package-core.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
- Completed tactical decisions promoted here:
  - `foundation_documentation/todos/completed/TODO-v1-events-package-phase-1.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-package-phase-2.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-location-core-cutover.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-map-poi.md`

## 3. Conceptual Delivery Plan (Canonical)

### 3.1 Core scope

- Event aggregate + occurrence projections (`events` + `event_occurrences`).
- Occurrence-first read/stream contracts.
- Event-level publication source of truth (occurrence mirrors for query performance only).
- Core location model: `location` + typed `place_ref`.
- ACL/event composition: `created_by` + `event_parties` (`can_edit`).
- Event/occurrence attendance policy persistence and validation against tenant-owned `settings.events.attendance` boundaries.

### 3.2 Capability scope

- Native Events capabilities remain in Events package (`multiple_occurrences`, `map_poi`).
- Ticketing domain moved to dedicated package/program (`belluga_ticketing`), integrated by contracts/events.

### 3.3 Out-of-scope boundaries

- Invite transaction lifecycle ownership.
- Attendance entitlement/check-in ownership.
- Checkout/payment gateway internals.

## 4. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `D3-03` | Approved | Occurrence-first stream contract (`occurrence.created|updated|deleted`). | Clients integrate by occurrence unit and use rehydrate-on-reconnect policy. | `belluga_events/README.md` (`GET /events/stream`) |
| `D3-05` | Approved | Hard cutover without compatibility bridge. | Legacy contracts are not supported; clients must adopt current payloads. | `belluga_events/README.md` (`No backward-compatibility bridge`) |
| `D3-17` | Approved | Invite lifecycle fields are excluded from Events payloads. | Invite ownership stays outside Events; avoid payload coupling. | `belluga_events/README.md` (`Invite lifecycle data is out of Events scope`) |
| `LOC-01..LOC-07` | Approved | Core location is mandatory with `location` + typed `place_ref`; `venue_id` write input removed. | Unified location semantics for `physical|online|hybrid`; map projection remains capability-level. | `foundation_documentation/todos/completed/TODO-v1-events-location-core-cutover.md` + package requests/tests |
| `D3-18..D3-23` | Approved | `event_parties` represent event composition + ACL (`can_edit`), not attendees. | Clear separation from participation/ticketing domains. | `belluga_events/README.md` (`Terminology Boundary`) |
| `D3-25` | Approved | Ticket-domain capabilities moved to ticketing package integration stream. | Keeps Events generic and decoupled while enabling dedicated ticketing evolution. | `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md` + `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md` |
| `EVS-FILTER-01` | Approved | MVP agenda/events listing does not accept text search (`search` query parameter is prohibited). | Avoids unsupported Atlas/text runtime paths during MVP and keeps filtering deterministic. | `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/AgendaIndexRequest.php`, `.../EventIndexRequest.php` |
| `EVS-FILTER-02` | Approved | Agenda filtering is taxonomy/category/tag + geo only, with taxonomy matching by slug pairs (`type`, `value`). | Aligns query path with tenant taxonomy registry and term slugs. | `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`, `laravel-app/app/Application/Taxonomies/TaxonomyValidationService.php` |
| `EVS-OPS-01` | Approved | Query runtime must not create indexes; required Mongo indexes are provisioned deterministically via tenant migration flow (Spatie `tenant_migration_paths`). | Avoids request-path index creation latency/failures and centralizes ops lifecycle in migrations. | `laravel-app/packages/belluga/belluga_events/database/migrations/2026_02_26_000300_create_event_occurrences_collection.php`, `.../2026_03_06_000500_add_event_occurrences_artists_taxonomy_terms_index.php` |
| `EVS-ATT-01` | Approved | Attendance policy governance is tenant-owned under `settings.events.attendance`; Events validates create/update payloads against those tenant boundaries and persists the resolved event/occurrence policy. | Keeps account-profile event creation inside tenant business constraints while preserving occurrence-level runtime clarity. | Sections `3.1`, `5.2`, `7` |

## 5. Contract Summary for Clients

### 5.1 Read model

- Agenda/detail contracts are occurrence-first and include `event_id` + `occurrence_id`.
- Event location contract is `location` + `place_ref`; venue projection is resolved from `place_ref` when applicable.
- `event_parties` are event composition principals (artists/hosts/venues/etc.) with payload-driven `can_edit`.
- Event party-candidate payload is capability-driven for physical hosts: profiles eligible for physical host selection come from profile types with `capabilities.is_poi_enabled=true` and valid coordinates.

### 5.2 Write model

- Event create/update accepts `occurrences[]` as schedule source.
- `venue_id` is prohibited in write payloads.
- Event description/content is optional (`content` is not required on create/update).
- Event type description is optional in the event-type registry and in resolved event type payloads.
- Event create/update must validate requested `attendance_policy` and optional `allow_occurrence_policy_override` against tenant-owned `settings.events.attendance` boundaries.
- If tenant settings disable event override, Events persists the tenant default policy on the event.
- If tenant settings allow event override, the event may choose one policy from tenant `allowed_policies`.
- If the event sets `allow_occurrence_policy_override=true`, occurrence payloads may choose their own policy using only tenant-approved values.
- Otherwise occurrences inherit the event `attendance_policy`.
- If paid reservation capability is unavailable for the tenant/runtime, `paid_reservation_only` and `either` are invalid write values.
- `location.mode` drives required fields:
  - `physical`: `place_ref` required with canonical `place_ref.type=account_profile`.
  - `online`: `location.online` required.
  - `hybrid`: both required; physical host uses canonical `place_ref.type=account_profile`.

### 5.3 Stream model

- `GET /events/stream` emits occurrence deltas only.
- On reconnect without valid cursor: rehydrate from `/agenda`, then resume stream from now.

### 5.4 Search and index lifecycle model

- Text search is disabled for MVP agenda/events listing (`search` is prohibited).
- Filtering baseline is categorical + taxonomy + geo (`categories`, `tags`, `taxonomy`, origin/radius).
- Taxonomy filters use typed slug pairs (`taxonomy[].type`, `taxonomy[].value`) across `taxonomy_terms`, `venue.taxonomy_terms`, and `artists.taxonomy_terms`.
- Runtime query services do not create indexes.
- Required Mongo indexes are application-owned and provisioned through tenant migrations in the Spatie multitenancy flow.

## 6. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-events-package-phase-1.md` | Package migration baseline | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-package-phase-2.md` | Decoupling via contracts/adapters | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-package-core.md` | Program closure, invariants, and standards baseline | Promoted | Sections 2, 3, 4, 6 | Completed and archived. |
| `TODO-v1-events-location-core-cutover.md` | Core location cutover | Promoted | Sections 4, 5 | Completed and archived. |
| `TODO-v1-events-capability-map-poi.md` | Map POI capability decisions | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-package-phase-3.md` | Hardening/capability governance finalization | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-location-gating-and-tenant-default-origin.md` | Origin gating + agenda filter contract stabilization | Promoted | Sections 4, 5 | Current baseline: taxonomy/category/tag + geo filtering; text search removed for MVP. |
| `TODO-v1-ticketing-package-integration.md` | Ticketing package integration stream | In progress | Sections 3, 4 | Active; ticket domain boundaries remain external to Events core. |

## 7. Relationship to Adjacent Modules

- `agenda_and_action_planner_module.md` consumes Events read contracts and must stay aligned with this module for event payload shape.
- `map_poi_module.md` consumes Events projection contracts for POI generation and visibility semantics.
- `invite_and_social_loop_module.md` consumes the resolved event/occurrence attendance policy and must not redefine tenant policy governance.
- Ticketing and participation modules integrate through contracts/events and do not redefine Events core ownership.
