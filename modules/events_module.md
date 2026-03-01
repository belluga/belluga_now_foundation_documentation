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
- Active tactical programs:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`
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
| `D3-25` | Approved | Ticket-domain capabilities moved to ticketing package integration stream. | Keeps Events generic and decoupled while enabling dedicated ticketing evolution. | `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` + `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md` |

## 5. Contract Summary for Clients

### 5.1 Read model

- Agenda/detail contracts are occurrence-first and include `event_id` + `occurrence_id`.
- Event location contract is `location` + `place_ref`; venue projection is resolved from `place_ref` when applicable.
- `event_parties` are event composition principals (artists/hosts/venues/etc.) with payload-driven `can_edit`.

### 5.2 Write model

- Event create/update accepts `occurrences[]` as schedule source.
- `venue_id` is prohibited in write payloads.
- `location.mode` drives required fields:
  - `physical`: `place_ref` required.
  - `online`: `location.online` required.
  - `hybrid`: both required.

### 5.3 Stream model

- `GET /events/stream` emits occurrence deltas only.
- On reconnect without valid cursor: rehydrate from `/agenda`, then resume stream from now.

## 6. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-events-package-phase-1.md` | Package migration baseline | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-package-phase-2.md` | Decoupling via contracts/adapters | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-location-core-cutover.md` | Core location cutover | Promoted | Sections 4, 5 | Completed and archived. |
| `TODO-v1-events-capability-map-poi.md` | Map POI capability decisions | Promoted | Sections 3, 4 | Completed and archived. |
| `TODO-v1-events-package-phase-3.md` | Remaining hardening/capability governance | In progress | Sections 3, 4 | Active for final closure checkpoints. |
| `TODO-v1-ticketing-package-integration.md` | Ticketing package integration stream | In progress | Sections 3, 4 | Active; ticket domain boundaries remain external to Events core. |

## 7. Relationship to Adjacent Modules

- `agenda_and_action_planner_module.md` consumes Events read contracts and must stay aligned with this module for event payload shape.
- `map_poi_module.md` consumes Events projection contracts for POI generation and visibility semantics.
- Ticketing and participation modules integrate through contracts/events and do not redefine Events core ownership.
