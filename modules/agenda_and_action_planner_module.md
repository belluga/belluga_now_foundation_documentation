# Documentation: Agenda & Action Planner Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Agenda & Action Planner module (MOD-303) tracks every upcoming experience, booking, and social action for tenant app users. It consolidates offers claimed from Map, invites accepted from the social loop, and booking/payment lifecycle events into a chronological stream delivered by `/api/v1/agenda`. Dedicated follow-up tasks and reminder payloads are authored by the Task & Reminder module and surface here only as read-only references when they represent a dated commitment.

### 1.1 Canonical Anchors

- Events canonical decisions/runtime contract:
  - `foundation_documentation/modules/events_module.md`
  - `laravel-app/packages/belluga/belluga_events/README.md`
- Tactical delivery references:
  - `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-package-core.md`

---

## 2. Architectural Tenets

1. **Timeline as the Source of Truth:** Agenda entries are modeled as immutable `timeline_nodes`. Each node references a `context_document` describing the originating entity (POI booking, invite, account profile reminder reference). Mutations append new nodes instead of updating in place so history remains auditable.
2. **Action Contract Standardization:** Every actionable item surfaces the same action schema (`ActionDescriptor`) with verbs (`confirm`, `share`, `navigate`, `chat`) and CTA payloads. Flutter controllers bind directly to these descriptors, eliminating UI branching logic.
3. **State Derivation:** `agenda_states` documents hold derived state (e.g., upcoming_count, overdue_count) for fast reads. They are rebuilt asynchronously from events rather than mutated procedurally.
4. **Calendar-Ready Segmentation:** Entries are partitioned by day and by semantic `channel` (experiences, logistics, social) so we can sync to native calendars without extra mapping later.

---

## 3. Data Model

### 3.1 `timeline_nodes`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "user_id": "ObjectId()",
  "channel": "String",
  "title": "String",
  "subtitle": "String",
  "starts_at": "Date",
  "ends_at": "Date",
  "status": "String",
  "context_document": {
    "type": "String",
    "reference_id": "String",
    "payload": {}
  },
  "actions": [
    {
      "action_type": "String",
      "label": "String",
      "target_payload": {},
      "requires_confirmation": "Boolean"
    }
  ],
  "metadata": {},
  "created_at": "Date"
}
```
`channel` ∈ {`experiences`, `logistics`, `social`, `commerce`}. `status` ∈ {`upcoming`, `in_progress`, `done`, `cancelled`}.

### 3.2 `agenda_states`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "user_id": "ObjectId()",
  "summary`: {
    "upcoming_count": "Number",
    "overdue_count": "Number",
    "actionable_count": "Number"
  },
  "last_refreshed_at": "Date"
}
```

### 3.3 `action_audit_log`
Stores every user action triggered from an agenda card for observability and compliance.

---

## 3.4 Client Event Payload (Agenda API)
Agenda surfaces events as a paged list; Flutter consumes this shape for cards and chips.
Events use canonical `location` + `place_ref`; venue projection is resolved from `place_ref` when `place_ref.type=venue`.

**Request (paged list)**
- Query: `page` (int), `page_size` (int), `past_only` (bool), `categories[]`, `tags[]`, `taxonomy[]`, `origin_lat`, `origin_lng`, `max_distance_meters`.
- Client orchestration rule: resolve effective origin before first fetch (`user location` -> tenant `settings.map_ui.default_origin`).
- Filter execution rule: taxonomy/category/tag + geo filters are backend-owned; agenda clients do not apply local radius filtering after fetch.
- Home Agenda UX rule: the selected Home radius is a persisted user/device preference (including anonymous sessions) carried through the app runtime settings path, but this V1 persistence applies only to the Home Agenda surface until schedule/discovery radius consumers are intentionally unified.
- Home Agenda chrome rule: compact/expanded radius chrome must derive from the same scroll source that moves the rendered agenda list. If an agenda widget controller owns that local chrome state, it remains subtree-private; any shared/persisted radius preference remains repository-owned rather than controller-relayed.
- Home Agenda status/radius chrome rule: the invite-status action and radius action are mutually exclusive in their expanded visual state. The status action is compact by default, expands as `Convites` for received/pending invitation filtering, and expands as `Confirmados` for occurrences where the user confirmed attendance regardless of invite origin.
- Event card rendering rule: compact agenda event cards compress multiple linked Account Profiles as first profile plus `e mais X`, reserve a stable trailing action slot, and render explicit time ranges as `start às end`.

**Response**
```json
{
  "items": [
    {
      "event_id": "string",
      "occurrence_id": "string",
      "slug": "string",
      "type": {
        "id": "string",
        "name": "string",
        "slug": "string",
        "description": "string",
        "icon": "string?",
        "color": "#RRGGBB?"
      },
      "title": "string",
      "content": "string",
      "location": {
        "mode": "physical|online|hybrid",
        "geo": { "type": "Point", "coordinates": [0.0, 0.0] },
        "online": { "provider": "string", "url": "string?" }
      },
      "place_ref": {
        "type": "string",
        "id": "string",
        "metadata": {}
      },
      "venue": {
        "id": "string",
        "display_name": "string",
        "tagline": "string?",
        "hero_image_url": "string?",
        "logo_url": "string?",
        "taxonomy_terms": [{ "type": "string", "value": "string" }]
      },
      "latitude": 0.0,
      "longitude": 0.0,
      "thumb": { "type": "image", "data": { "url": "string" } },
      "date_time_start": "2025-01-01T00:00:00Z",
      "date_time_end": "2025-01-01T00:00:00Z?",
      "occurrences": [
        {
          "occurrence_id": "string",
          "occurrence_slug": "string?",
          "date_time_start": "2025-01-01T00:00:00Z",
          "date_time_end": "2025-01-01T00:00:00Z?",
          "is_selected": false,
          "has_location_override": false,
          "programming_count": 0
        }
      ],
      "artists": [
        { "id": "string", "display_name": "string", "avatar_url": "string (optional)", "highlight": false, "genres": ["string"] }
      ],
      "created_by": {
        "type": "string",
        "id": "string"
      },
      "event_parties": [
        {
          "party_type": "string",
          "party_ref_id": "string",
          "permissions": { "can_edit": true },
          "metadata": {}
        }
      ],
      "capabilities": {
        "map_poi": {
          "enabled": true,
          "discovery_scope": null
        }
      },
      "tags": ["string"],
      "taxonomy_terms": [{ "type": "string", "value": "string" }]
    }
  ],
  "has_more": true
}
```

**Display rule:** chips use `event.tags` if provided; otherwise aggregate all `artists[*].genres`; if both are empty, show no chips. Artists list itself may be empty.

### Field Definitions
- `thumb.type` ∈ {`image`}
- `location.mode` ∈ {`physical`, `online`, `hybrid`}
- `event_parties[].permissions.can_edit` ∈ {`true`, `false`}

**Boundary note:** invite lifecycle fields are intentionally excluded from Events payloads in this module contract.

---

## 4. Interfaces

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/agenda` | GET | Returns grouped timeline nodes plus derived counts. |

**Deferred (post-MVP):** `/api/v1/agenda/{nodeId}/action`, `/api/v1/agenda/sync`.

**Events**
* Inbound: `booking.confirmed`, `booking.cancelled`, `invite.accepted`, `poi.favorite.added`, `task.reminder.scheduled`.
* Outbound: `agenda.node.created`, `agenda.action.completed`, `agenda.state.updated`.

---

## 5. Dependencies

* **Map & POI Module:** Provides event payloads for bookings and location reminders.
* **Invite & Social Loop:** Supplies accepted invites and share follow-ups.
* **External Commercial Engine:** Adds payment confirmation events when bookings convert to paid transactions.
* **Task & Reminder Module:** Publishes reminder references when tasks carry concrete schedule anchors that must appear on the agenda timeline.

---

## 6. Current Strategic Posture

1. **Current posture:** the agenda contract is already consumed through the current repository/controller boundary; this module should no longer frame its authority through mock-phase language.
2. **Deferred continuity:** favorites/personalization may later enrich timeline nodes with saved-POI context when that capability is promoted.
3. **Deferred continuity:** privacy and invite-status halos may reuse the same derived agenda state when those visibility capabilities become current scope.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `AGD-01` | Approved | Agenda consumes occurrence-first Events contract with invite lifecycle excluded from event payloads. | Keeps Agenda read model aligned with Events ownership boundaries. | Sections `1.1`, `3.4`, `4` |
| `AGD-02` | Approved | Event location contract is canonical `location + place_ref` with optional venue projection. | Prevents legacy venue-only coupling in agenda consumers. | Section `3.4` |
| `AGD-03` | Approved | Actions are standardized descriptors, not hardcoded screen logic. | Enables backend-driven card/action evolution. | Section `2` |
| `AGD-04` | Approved | Agenda/search controllers gate first fetch by the canonical effective-origin policy result, not by inline per-screen branching. The shared `LocationOriginService` selects live user vs tenant default origin using the tenant-configured max radius boundary and persists the resulting `mode + reason` locally for MVP. | Removes pre-origin fetch stalls, keeps loading deterministic, and prevents geo-origin drift between Agenda/Search and other tenant-public surfaces. | Section `3.4` + `foundation_documentation/endpoints_mvp_contracts.md` (`GET /agenda`) + `foundation_documentation/todos/completed/TODO-v1-canonical-location-origin-policy-across-app.md` |
| `AGD-05` | Approved | Local distance/radius filtering is forbidden in agenda/search render paths; backend geo filtering is authoritative. | Prevents divergent client/backend filtering behavior. | Section `3.4` + `foundation_documentation/endpoints_mvp_contracts.md` (`GET /agenda`, `GET /events/stream`) |
| `AGD-06` | Approved | Persisted radius preference in V1 is Home-only: Home Agenda stores the selected `max_distance_meters` preference per user/device, but that preference does not automatically retune Event Search or other radius consumers until a dedicated alignment slice promotes a shared rule. When no preference exists yet, Home seeds the initial selected radius from the user-to-tenant-center distance clamped to the tenant-configured bounds. | Makes the temporary product asymmetry explicit and prevents accidental cross-surface behavior drift. | Section `3.4` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md` |
| `AGD-07` | Approved | Repository-owned agenda/home aggregate streams are canonical backend-backed snapshots, not controller projection surfaces. Controllers may combine canonical event streams with invite/confirmation streams to derive local display state, but they must never publish those projections back into repository event streams or reconstruct aggregate truth from shared scratch pagination state. | Preserves single-writer ownership, avoids cross-surface pagination bleed, and keeps invite state as an orthogonal aggregate. | Section `3.4` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` |
| `AGD-08` | Approved | Agenda/search/home event lists remain occurrence-first; each occurrence card may navigate to event detail with both event slug and selected occurrence id. Cards do not group sibling dates or render other-date summaries. | Keeps list performance and scanning behavior stable while allowing multi-date switching inside event detail. | Section `4` + `events_module.md` (`EVS-OCC-01`) |
| `AGD-08` | Approved | Query-scoped page state for schedule/search is repository-owned unless the aggregate itself is explicitly canonical. Shared scratch page streams/counters are forbidden, and backend page-envelope knowledge (`has_more`, page-result wrappers, raw builders), public cache/query snapshot wrappers (`*CacheSnapshot`), delegated pagination state (`hasMore...`), page-addressed contract APIs (`loadNext...Page()`), plus raw pagination controls (`page`, `pageSize`, cursors, limits) must remain repository-internal rather than exported from domain surfaces or repository contracts. Schedule consumers receive materialized domain items and semantic repository intents only. Repository-owned pagination/query helpers are private implementation details and must not be re-exposed through delegated helpers, support abstractions, or test double hooks. | Prevents controller cross-talk, keeps repository aggregates single-writer, and stops pagination-envelope/cache-snapshot fabrication or leakage outside repository ownership. | Section `3.4` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` |
| `AGD-09` | Approved | Home Agenda V1 may replace live device origin with the tenant default origin when the user is farther than the tenant-configured maximum Home radius from the tenant default origin, persisting that Home-only reference mode locally/device-side while keeping backend geo filtering authoritative for the final query. | Preserves useful Home results for out-of-city users without redefining generic agenda/search geo semantics. | Section `3.4` + `foundation_documentation/todos/completed/TODO-v1-home-location-origin-reference-mode.md` |
| `AGD-10` | Approved | Home Agenda scroll-driven chrome must follow the real agenda-list scroll source. Any agenda-specific widget controller used for that chrome remains subtree-private, while shared radius preference continues to be repository-owned. | Prevents false outer-scroll bindings and blocks screen-controller or controller-relay workarounds for agenda-local chrome behavior. | Section `3.4` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-controller-boundary-plugin-rules.md` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-events-and-agenda-frontend.md` | Agenda/event client contract delivery | Completed | `3.4`, `4`, `7` | Canonical stream for agenda payload usage. |
| `TODO-v1-events-package-core.md` | Events package authority for agenda contracts | Completed | `1.1`, `3.4`, `7` | Completed and archived; contract baseline promoted to Events module. |
| `TODO-v1-events-location-gating-and-tenant-default-origin.md` | Origin gating + backend-only geo filtering | Promoted | `3.4`, `7` | Establishes effective-origin-first fetch and no local radius filtering in agenda/search clients. |
| `TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md` | Home radius persistence + bottom-sheet UX polish | Completed | `3.4`, `7`, `8` | Home-only persistence decision and bottom-sheet UX are now promoted while cross-surface radius alignment remains deferred. |
