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
  - `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
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

- Invite lifecycle ownership.
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
| `D3-25` | Approved | Ticket-domain capabilities moved to ticketing package integration stream. | Keeps Events generic and decoupled while enabling dedicated ticketing evolution. | `foundation_documentation/todos/completed/TODO-v1-events-package-phase-3.md` + `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md` |
| `EVS-FILTER-01` | Approved | MVP agenda/events listing does not accept text search (`search` query parameter is prohibited). | Avoids unsupported Atlas/text runtime paths during MVP and keeps filtering deterministic. | `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/AgendaIndexRequest.php`, `.../EventIndexRequest.php` |
| `EVS-FILTER-02` | Approved | Agenda filtering is taxonomy/category/tag + geo only, with taxonomy matching by slug pairs (`type`, `value`). | Aligns query path with tenant taxonomy registry and term slugs. | `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`, `laravel-app/app/Application/Taxonomies/TaxonomyValidationService.php` |
| `EVS-OPS-01` | Approved | Query runtime must not create indexes; required Mongo indexes are provisioned deterministically via tenant migration flow (Spatie `tenant_migration_paths`). | Avoids request-path index creation latency/failures and centralizes ops lifecycle in migrations. | `laravel-app/packages/belluga/belluga_events/database/migrations/2026_02_26_000300_create_event_occurrences_collection.php`, `.../2026_03_06_000500_add_event_occurrences_artists_taxonomy_terms_index.php` |
| `EVS-MGMT-01` | Approved | Event-form candidate discovery uses typed, page-based `account_profile_candidates` queries (`related_account_profile` or `physical_host`) instead of mixed snapshots or artist-shaped discovery contracts. | Keeps admin/account event forms aligned with paginator conventions while preserving dynamic account-profile semantics and avoiding preload truncation in large catalogs. | `foundation_documentation/endpoints_mvp_contracts.md`, Events candidate controller/request/adapter |
| `EVS-MGMT-02` | Approved | Tenant-admin event list filters are server-driven and use canonical venue (`place_ref`) plus non-venue event-party/linked-profile semantics; management payloads must not compute or expose an artist-shaped key. | Prevents dynamic account-profile hardcoding in admin operations while keeping filter composition deterministic. | Sections `5.1`, tenant-admin event list controller/query tests |
| `EVS-ATT-01` | Approved | Attendance policy governance is tenant-owned under `settings.events.attendance`; Events validates create/update payloads against those tenant boundaries and persists the resolved event/occurrence policy. | Keeps account-profile event creation inside tenant business constraints while preserving occurrence-level runtime clarity. | Sections `3.1`, `5.2`, `7` |
| `EVS-VIS-01` | Approved | Event types use canonical `visual` as source of truth, may expose `poi_visual` as compatibility mirror, and embedded `event.type` / `event_occurrence.type` snapshots must carry the canonical visual payload. | Eliminates legacy icon-only drift between event registry CRUD, admin flows, and downstream consumers such as map POI projection. | Sections `5.1`, `5.2`, `7` |
| `EVS-UI-01` | Approved | Event detail read payloads expose additive `linked_account_profiles` projection inputs (slug, profile type, media, taxonomy summaries) so immersive event detail can render dynamic account-profile category tabs without request-time joins. Venue/location semantics remain owned by `Como Chegar`; grouped dynamic tabs replace only the old artist-only lineup surface, while `Sobre` renders the canonical sanitized `event.content` HTML subset. Unsupported tags are not valid persisted content, and media-only / non-text markup does not count as valid `Sobre`. Linked-profile cards remain route-driven by runtime slug and support favorite affordance without replacing direct profile navigation. Missing slug is a payload-contract failure; write paths must persist it correctly rather than relying on read-time repair. | Keeps immersive event detail occurrence-first while making linked account-profile categories first-class UI inputs, avoiding artist-only hardcoding, and preserving a stable event-detail contract for content/location tabs and linked-profile interactions. | Sections `5.1`, `5.2`, `7` |
| `EVS-UI-02` | Approved | Tenant-public immersive event detail uses the shared safe-back policy: when no previous route exists, `/agenda/evento/:slug` falls back to `/`; when history exists, the real previous route still wins. | Keeps direct-open/deep-link event detail resilient while preserving normal in-app return continuity when the user arrived from discovery, home, or another routed surface. | Sections `5.1`, `7` |

## 5. Contract Summary for Clients

### 5.1 Read model

- Agenda/detail contracts are occurrence-first and include `event_id` + `occurrence_id`.
- Event location contract is `location` + `place_ref`; venue projection is resolved from `place_ref` when applicable.
- Event-type registry/read payloads expose canonical `visual` as source of truth and may emit `poi_visual` as compatibility mirror; embedded `event.type` and `event_occurrences[].type` snapshots must carry the canonical visual payload as well.
- `event_parties` are the canonical composition contract for non-location account profiles only. Venue/local ownership remains on `location + place_ref`, and `venue` is a derived read projection rather than an event-party input.
- Detail/read payloads expose ordered `linked_account_profiles[]` entries derived exclusively from `event_parties` metadata. Each entry is an account-profile summary input for UI grouping and contains enough identity data for direct navigation and taxonomy-aware cards (`id`, `slug`, `profile_type`, `display_name`, media fields, taxonomy term summaries).
- Management/event-operations payloads must use `event_parties` plus `linked_account_profiles` for dynamic account-profile administration and must not require an artist-shaped key such as `artists`.
- Some public/read and repair-oriented surfaces may still emit legacy `artists[]` summaries as derived compatibility projections. Those read-only projections are not canonical composition inputs and must not be treated as admin/write contract ownership.
- Tenant-admin event list operations use server-driven `date`, `temporal`, `venue_profile_id`, and `related_account_profile_id` as the canonical current manager filter set; `venue_profile_id` targets canonical venue ownership (`place_ref`), while `related_account_profile_id` targets non-venue `event_parties`/`linked_account_profiles`.
- Direct text search is not part of the current tenant-admin manager surface; `search` is rejected on the admin request contract and must not drive the Flutter admin UX.
- Tenant-admin event pagination is ordered by nearest start first (`date_time_start ASC`, `_id DESC`) so manager lists open with the soonest upcoming/default-visible event first while preserving deterministic tie-breaks across appended pages.
- Public immersive event detail consumes those linked account profiles as grouped category tabs between the stable `Sobre` and `Como Chegar` tabs; `Sobre` renders the canonical sanitized `event.content` HTML subset and is omitted when that sanitized content has no valid textual body, while `Como Chegar` owns venue/map/directions semantics for the event.
- Linked-profile cards inside event detail remain route-driven by `linked_account_profiles[].slug`, may expose favorite affordance in parallel with direct card navigation, and must not rely on client-side lookup fallbacks or request-time repair when a payload is incomplete. Event create/update paths must keep `venue.slug` and `event_parties[].metadata.slug` aligned for new writes so derived read projections can resolve canonical identities.
- Tenant-public event detail back behavior is stack-first: if the route was entered from another in-app surface, back returns to that surface; if it was opened directly with no previous route, the approved fallback is `/`.
- Event-form account-profile candidate discovery is type-driven and page-based: `related_account_profile` returns generic related account profiles without hardcoding one specific dynamic profile type and excludes canonical venue profiles, while `physical_host` returns POI-enabled profiles with valid coordinates.
- Public event-image resolution order is deterministic: `event.thumb` first, then `linked_account_profiles` in canonical array order, then `venue` media. Runtime callers must not fall back to legacy `artists`.

### 5.2 Write model

- Event create/update accepts `occurrences[]` as schedule source.
- `venue_id` is prohibited in write payloads.
- `event_parties[]` write input is minimal and strict: clients may send only `party_ref_id` and optional `permissions.can_edit`. `party_type` and `metadata` are backend-owned, inferred/resolved from the referenced account profile, and rejected when client-supplied.
- When `event_parties` is present on update, it replaces the full related-account set in request order. An omitted `event_parties` field preserves the stored related-account ordering.
- Event description/content is optional (`content` is not required on create/update).
- Event `content` accepts only the approved canonical rich-text/HTML subset. Backend create/update sanitizes unsupported tags/attributes before persistence, and frontend/editor flows must not imply unsupported markup will be preserved.
- Event type description is optional in the event-type registry and in resolved event type payloads.
- Event-type registry writes use canonical `visual` as source of truth; legacy `icon/color/icon_color` may still be emitted for compatibility but are not the authoritative editing contract.
- Event-type `visual.mode=image` accepts only `cover` and `type_asset`; `avatar` is invalid for event types.
- Event create/update must validate requested `attendance_policy` and optional `allow_occurrence_policy_override` against tenant-owned `settings.events.attendance` boundaries.
- If tenant settings disable event override, Events persists the tenant default policy on the event.
- If tenant settings allow event override, the event may choose one policy from tenant `allowed_policies`.
- If the event sets `allow_occurrence_policy_override=true`, occurrence payloads may choose their own policy using only tenant-approved values.
- Otherwise occurrences inherit the event `attendance_policy`.
- If paid reservation capability is unavailable for the tenant/runtime, `paid_reservation_only` and `either` are invalid write values.
- Event-form candidate search is backend-owned and paginated through `GET /events/account_profile_candidates`; client search remains `like`-semantics aligned with backend query fields, not a local-only in-memory filter.
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
- `live_now_only=true` is supported on `/agenda` to return only currently-running occurrences and is used by Discovery "Tocando agora" surfaces.
- Consumer alignment note (Flutter Discovery MVP): the `Tocando agora` section is artist-driven and remains hidden when live-now payload has no artists, even if occurrences are currently live.
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
| `TODO-v1-event-type-canonical-poi-visuals.md` | Canonical event-type visuals across registry CRUD, snapshots, and map POIs | In progress | Sections 4, 5 | Stable contract decisions are promoted; final closure still depends on manual admin/map smoke evidence. |
| `TODO-v1-ticketing-package-integration.md` | Ticketing package integration stream | In progress | Sections 3, 4 | Active; ticket domain boundaries remain external to Events core. |
| `TODO-v1-tenant-public-safe-back-navigation.md` | Shared tenant-public event-detail back/fallback policy | Completed | Sections 4, 5 | Freezes `/agenda/evento/:slug -> /` when root-opened; archived from `active` during the 2026-04-09 MVP TODO cleanup after delivery confirmation. |

## 7. Relationship to Adjacent Modules

- `agenda_and_action_planner_module.md` consumes Events read contracts and must stay aligned with this module for event payload shape.
- `map_poi_module.md` consumes Events projection contracts for POI generation and visibility semantics.
- `invite_and_social_loop_module.md` consumes the resolved event/occurrence attendance policy and must not redefine tenant policy governance.
- Ticketing and participation modules integrate through contracts/events and do not redefine Events core ownership.
