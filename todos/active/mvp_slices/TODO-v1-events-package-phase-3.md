# TODO (V1): Events Package Phase 3 (Improvements and Hardening)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Harden `belluga_events` for reliability, observability, and extension readiness after migration and decoupling.

---

## Scope
- Improve publication lifecycle robustness, error surfaces, and idempotency.
- Harden stream delta behavior and reconnect rehydrate guarantees (no replay buffer).
- Add package-focused observability and diagnostics.
- Review indexes/query plans and optimize expensive aggregations.
- Establish capability governance for feature extensions (tenant-available vs event-enabled).
- Define non-destructive capability disable/reenable semantics.
- Define atomic partial-update semantics for capability payloads.
- Define per-capability migration/index strategy compatible with tenant-scoped migration execution.
- Integrate Events capability foundation with the universal tenant settings architecture (already delivered) so capability settings are registered and resolved via schema.
- Execute Phase 3 in two blocks: foundation first (including one pilot capability), concrete capabilities last.

---

## Out of Scope
- Unplanned contract drift outside the approved occurrence-first hard-cutover target.
- Non-Events domain expansion.
- Concrete capability implementation in the first execution block beyond one pilot capability (`multiple_occurrences`).

---

## Final Capability TODO References (Canonical for Block 2)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-limits.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-pricing-fees.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-inventory.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-map-poi.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-qr-checkin.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-combo.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-participant-student-binding.md`

---

## Pre-Capability Core Gate (Canonical)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-location-core-cutover.md`
  - Rule: complete this core location cutover before executing Block 2 capability TODOs.
  - Status: ✅ Completed (LOC-05/06/07 delivered; tests and full suite green).

---

## Standards/Exception Reference (Locked)
- Standards baseline for this phase is defined in:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Section `F`).
- Approved deviations relevant to Phase 3 are defined in:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Section `G`), especially `EX-02` and `EX-03`.
- Dedicated settings-foundation stream for this phase:
  - `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md`.

---

## Pending Decisions (Design Inputs)
- [x] ✅ Production‑Ready `D3-01` Occurrence model shape: use two collections (`events` + `event_occurrences`) for V1 multi-date support.
- [x] ✅ Production‑Ready `D3-02` Publication granularity: Event is the single publication source-of-truth; occurrences mirror publication flags for query optimization only.
- [x] ✅ Production‑Ready `D3-03` Stream/event contract model: occurrence-first with explicit payload types (no ambiguous optional-ID envelope).
- [x] ✅ Production‑Ready `D3-04` Query/index strategy: occurrence-first read model, with occurrence docs shaped as extracted embedded occurrences plus mirrored event query fields.
- [x] ✅ Production‑Ready `D3-05` Backward-compatibility strategy: no compatibility bridge; clients must adopt occurrence-first contracts.
- [x] ✅ Production‑Ready `D3-06` Capability governance model: Tenant controls capability availability; each Event controls capability usage.
- [x] ✅ Production‑Ready `D3-07` Effective capability rule: Event cannot use a capability that is not available in Tenant.
- [x] ✅ Production‑Ready `D3-08` Disable semantics: Tenant capability disable is non-destructive and applies immediately to all Events (including past events) by runtime gating.
- [x] ✅ Production‑Ready `D3-09` Reenable semantics: capability data/config remains persisted; re-enabling restores behavior using stored configuration.
- [x] ✅ Production‑Ready `D3-10` Update semantics: capability updates are atomic partial merges; only payload-present keys mutate, and absent keys must remain unchanged.
- [x] ✅ Production‑Ready `D3-11` Migration/index policy: each concrete capability can own dedicated migrations/indexes; execution must remain tenant-scoped under Spatie multitenancy migration flow.
- [x] ✅ Production‑Ready `D3-12` Settings architecture baseline: introduce a universal `settings` kernel package to host contracts/registry/schema validation for scoped settings (`tenant` and `landlord`).
- [x] ✅ Production‑Ready `D3-13` Settings ownership split: settings kernel package owns canonical settings persistence lifecycle (model/migration), while host app owns scope-aware integration/adapters and package bindings.
- [x] ✅ Production‑Ready `D3-14` Settings API contract: backend must expose schema-driven endpoints so UI is rendered from registered settings definitions, not hardcoded forms.
- [x] ✅ Production‑Ready `D3-15` Execution sequence: implement foundation block first including one pilot capability (`multiple_occurrences`); stop before concrete capability creation and align before final block execution.
- [x] ✅ Production‑Ready `D3-16` Pilot capability payload semantics (`multiple_occurrences`): Tenant Admin defines tenant-scoped settings `allow_multiple` (bool) and `max_occurrences` (int|null, where client `0` is normalized to `null`); event create/update can only opt in/out of using multiple occurrences if tenant settings allow it.
- [x] ✅ Production‑Ready `D3-17` Invite ownership boundary hardening: Events payload/model must not persist or expose invite-lifecycle fields (`confirmed_user_ids`, `received_invites`, `sent_invites`, `friends_going`); those belong to Invites feature work only.
- [x] ✅ Production‑Ready `D3-18` Ownership model hardening: `created_by` (audit) and resource ownership/authorization are distinct concerns; creator identity does not define sole edit authority.
- [x] ✅ Production‑Ready `D3-19` Shared ownership policy: Events use shared ACL-style principals for write authorization; `venue.id`/`artists` are content relations and must not be treated as implicit ownership.
- [x] ✅ Production‑Ready `D3-20` Generic party extension policy: introduce neutral `event_parties` relation shape (project-specific entities map to it through adapters/mappers); do not force host models to implement package contracts.
- [x] ✅ Production‑Ready `D3-21` Per-party authorization granularity: each `event_party` entry can independently allow or deny edit authority by payload (`can_edit`) regardless of party type.
- [x] ✅ Production‑Ready `D3-22` Permission simplification policy (Phase 3): use a single canonical edit flag (`can_edit`) for event-party authorization in this phase.
- [x] ✅ Production‑Ready `D3-23` Type-level default policy: each `event_party` type defines code-level default `can_edit` in mapper/policy (not settings); each event-party row can override by payload.
- [x] ✅ Production‑Ready `D3-24` Core location pre-capability gate: execute hard-cutover to canonical core location (`location` + typed `place_ref`, `physical|online|hybrid`) before Block 2 capabilities.

---

## Pending Decisions (Iteration Queue: ACL/Event Parties Cutover)
- [x] ✅ Production‑Ready `P3-01` Canonical `event_parties` payload shape.
  - Decided shape: `{ party_type, party_ref_id, permissions:{can_edit}, metadata? }`.
- [x] ✅ Production‑Ready `P3-02` Principal identity canonical format for actor resolution (`created_by` + authorization checks).
  - Decided shape: object `{ type, id }` persisted in MongoDB documents.
  - Runtime note: internal normalized key `type:id` may be used in-memory for fast permission checks.
- [x] ✅ Production‑Ready `P3-03` Authorization precedence policy (owner/admin vs event-party permissions).
  - Decided order: `owner/admin` override first; if not matched, evaluate `event_parties`.
  - Decided rule: any matched party with `can_edit=true` grants mutable access; otherwise deny.
  - Decided constraint: no explicit deny policy in this phase.
- [x] ✅ Production‑Ready `P3-04` Exact action surface covered by party permissions in this slice.
  - Decided scope: `can_edit` gates `update`, `delete`, `publish`, `unpublish` in this slice.
  - Decided delete semantics: delete remains soft delete.
- [x] ✅ Production‑Ready `P3-05` Hard-cutover scope for removing `account_id` / `account_profile_id`.
  - Decided scope: remove from migrations/models/requests/query filters/indexes/tests in same slice (no compatibility path).
- [x] ✅ Production‑Ready `P3-06` Mapper boundary for host integrations (`venue`, `artist`, future types).
  - Decided boundary: package contract + registry for mappers; host app provides `venue`/`artist` mappers.
  - Decided behavior: unknown `party_type` is a validation error (no silent pass-through).
- [x] ✅ Production‑Ready `P3-07` API response strategy for permissions.
  - Decided response: return canonical stored party permissions only; do not return actor-specific computed permissions in V1.
  - Decided persistence rule: defaults are materialized on create (and when adding party rows) so `permissions.can_edit` is explicitly persisted.

---

## Tasks
- [x] ✅ Production‑Ready Add structured logs/metrics around event writes, stream deltas, and publication transitions.
- [x] ✅ Production‑Ready Improve retry/backoff and failure handling for async listeners.
- [x] ✅ Production‑Ready Enforce operational guardrails for async side effects (`OD-04`): queue staleness monitor (`>60s` for 5 minutes), DLQ alert hook, and 15-minute occurrence reconciliation cadence.
- [x] ✅ Production‑Ready Evaluate and tune Mongo indexes for agenda/filter/stream queries.
- [x] ✅ Production‑Ready Add resilience tests for stream reconnect and publication edge cases.
- [x] ✅ Production‑Ready Final cleanup of migration-era transitional wrappers.
- [x] ✅ Production‑Ready Deliver two-collection occurrence-first model (`events` + `event_occurrences`) with write-path sync on create/update/delete.
- [x] ✅ Production‑Ready Enforce transaction-first consistency for Event + occurrence mirrors (including scheduled publication transitions).
- [x] ✅ Production‑Ready Switch agenda/stream read model to `event_occurrences` (canonical query unit) and publish occurrence-first deltas (`occurrence_id` + `event_id`).
- [x] ✅ Production‑Ready Introduce capability registry/contracts (`abstract capability` + concrete capability handlers).
- [x] ✅ Production‑Ready Add tenant capability availability config and event-level capability usage config.
- [x] ✅ Production‑Ready Enforce runtime gate: `effective_capability = tenant_available && event_enabled`.
- [x] ✅ Production‑Ready Implement atomic partial capability update flow (`$set` only on payload-present paths; no implicit unset).
- [x] ✅ Production‑Ready Implement pilot capability `multiple_occurrences` with split config: tenant settings payload `{ allow_multiple: bool, max_occurrences: int|null }` (`0` => `null`) plus event-level usage flag (`enabled`), enforcing rules during event create/update.
- [x] ✅ Production‑Ready Integrate Events capability foundation with the existing `settings` kernel package (contracts + registry + schema validation + merge semantics already delivered).
- [x] ✅ Production‑Ready Reuse host-app scope adapters/bindings already delivered by settings kernel (`landlord|tenant`) and consume them from Events capability flow (no duplicate adapter layer).
- [x] ✅ Production‑Ready Register events capability/settings schema through the new settings registry.
- [x] ✅ Production‑Ready Extend existing schema-driven settings endpoints (`schema`, `values`, partial `patch`) by registering `events` capability fields required for Phase 3 foundation.
- [x] ✅ Production‑Ready Treat `TODO-v1-settings-kernel-package.md` as delivered baseline; this phase only adds/consumes Events namespace capability schema and values on top of kernel contracts.
- [x] ✅ Production‑Ready Stop gate: foundation block finished and paused before concrete capability implementation.
- [x] ✅ Production‑Ready Hard-cutover: remove legacy `account_id`/`account_profile_id` from Events payload/model/requests/query filters/indexes/tests and migrate ownership filtering away from account fields.
- [x] ✅ Production‑Ready Implement `event_parties` ACL model with `can_edit` and default-plus-payload-override semantics.
- [x] ✅ Production‑Ready Final consistency review: run decision-by-decision adherence scan (`core`, `phase-1`, `phase-2`, `phase-3`) and sync TODO statuses/notes with delivered code.
- [ ] ⚪ Final block (deferred): execute and close all dedicated capability TODOs listed in `Final Capability TODO References (Canonical for Block 2)`.

---

## Validation Steps
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite; mandatory gate for important milestones/phase closure).
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Unit/Events/EventsPackageBindingsTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Unit/Events/EventsAsyncOperationalPolicyTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Unit/Events/EventAsyncOperationsMonitorServiceTest.php`.
- [x] ✅ Production‑Ready Targeted load/perf sampling for agenda and stream paths.
- [x] ✅ Production‑Ready Manual smoke gate waived for this phase by explicit delivery decision (non-manual closure only); automated reconnect/publication resilience coverage remains the execution gate.
- [x] ✅ Production‑Ready Capability gate tests: disabled at tenant => non-executable/non-visible capability behavior for all events.
- [x] ✅ Production‑Ready Capability persistence tests: disable/reenable preserves and restores previously persisted capability config.
- [x] ✅ Production‑Ready Partial update tests: payload-present keys update atomically without mutating absent keys.
- [x] ✅ Production‑Ready Pilot capability tests: `multiple_occurrences` follows tenant availability + event enablement + effective runtime gate.
- [x] ✅ Production‑Ready Pilot payload normalization tests: `max_occurrences=0` persists as `null`; positive integers persist as numeric limit.
- [x] ✅ Production‑Ready Pilot create/update rule tests: tenant disallow OR event disabled => max allowed occurrences is `1`; tenant allow + event enabled + numeric `max_occurrences` => enforce tenant max.
- [x] ✅ Production‑Ready Settings registry tests: package-registered schema is discoverable and validated.
- [x] ✅ Production‑Ready Settings API tests: schema endpoint and values endpoint return registry-driven payloads.
- [x] ✅ Production‑Ready Settings patch tests: partial patch mutates only payload-present keys for a namespace.
- [x] ✅ Production‑Ready Foundation block gate: full Laravel suite passes before starting final capability block.
- [x] ✅ Production‑Ready Hard-cutover validation: `rg -n "account_id|account_profile_id" laravel-app/packages/belluga/belluga_events` returns no hits, and Events tests contain no request/filter/model assertions depending on legacy `account_*` event fields.
- [ ] ⚪ Final block validation (deferred): validation gates are executed and closed within each dedicated capability TODO listed in `Final Capability TODO References (Canonical for Block 2)`.
- [x] ✅ Production‑Ready Ownership/ACL validation block: shared-principal authorization tests (account/tenant/user principals), creator-vs-owner separation tests, and event-party mapping contract tests.
- [x] ✅ Production‑Ready Event-party permission validation block: per-party `can_edit` authorization tests plus default-plus-payload-override resolution tests.

---

## Definition of Done
- [x] ✅ Production‑Ready Events package reliability baseline is documented and measurable.
- [x] ✅ Production‑Ready Known bottlenecks and failure modes are mitigated or explicitly tracked.
- [x] ✅ Production‑Ready Architecture docs reflect final post-hardening state.
- [x] ✅ Production‑Ready Capability governance contract (tenant availability + event usage + effective runtime gating) is implemented and verified.
- [x] ✅ Production‑Ready Capability disable/reenable behavior is non-destructive and validated.
- [x] ✅ Production‑Ready Capability update semantics are atomic/partial and protected by tests.
- [x] ✅ Production‑Ready Pilot capability `multiple_occurrences` validates the foundation pipeline (registry/config/gating/partial update semantics, including `max_occurrences` normalization).
- [x] ✅ Production‑Ready Universal settings kernel is active and events package consumes tenant settings only through settings contracts/registry.
- [x] ✅ Production‑Ready Foundation block is delivered with pilot `multiple_occurrences` and explicitly paused before final capability block execution.
- [x] ✅ Production‑Ready Events ACL/event-parties foundation is delivered (`created_by` audit principal, mapper registry, canonical `event_parties` payload, `can_edit` gate, owner/admin override path, and coverage tests).

---

## Implementation Notes (Latest Iteration)
- Added structured observability logs for:
  - Event write lifecycle (`events_write_completed`) on create/update/delete.
  - Stream delta construction (`events_stream_deltas_built`) and invalid cursor handling (`events_stream_deltas_skipped_invalid_cursor`).
  - Scheduled publication transitions (`events_publication_transition_applied`).
- Consolidated canonical tenant-scoped index set directly into base package migrations (`events` and `event_occurrences`):
  - Publication transition query path (`publication.status` + `publication.publish_at`).
  - Agenda/filter/stream support paths (publication + schedule + venue filters + taxonomy/tags/categories).
- Added resilience coverage for stream reconnect/publication edge cases in feature tests:
  - reconnect with cursor (no replay),
  - invalid `Last-Event-ID`,
  - scheduled future publication yielding delete delta,
  - scheduled->published transition yielding stream delta.
- Transitional wrapper cleanup revalidated: no migration-era app wrappers remain for Events controllers/requests/jobs; routes/schedule point directly to package classes.
- Delivered ACL/event-parties foundation:
  - Canonical persisted fields: `created_by` and `event_parties`.
  - Mapper-driven host integration (`venue`/`artist`) via package contracts + registry.
  - Authorization uses account route context + `can_edit`, with owner/admin override semantics for mutable operations.
  - Events CRUD test suite extended with owner-override and `can_edit` deny scenarios.
- Automated load/perf baseline sampling completed and documented in:
  - `foundation_documentation/artifacts/events-phase3-reliability-baseline-v1.md`
  - Raw run log: `foundation_documentation/artifacts/tmp/events_phase3_perf_sampling_20260226_221748.log`
- Manual smoke for publication/SSE reconnect intentionally not executed in this closure cycle by explicit scope decision; automated resilience tests remain green and are the accepted gate for this milestone.
- Documentation synchronization completed for occurrence-first hard cutover:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - Package client reference: `laravel-app/packages/belluga/belluga_events/README.md`

---

## Decision Log
- `D3-01`: Decided. Phase 3 adopts two collections (`events`, `event_occurrences`) instead of embedded occurrence arrays.
  - Rationale: agenda/discovery access pattern is date-occurrence-first, so occurrence-level querying/indexing must be first-class.
  - Consequence: Event remains the canonical identity entity; occurrence becomes the canonical scheduling/query entity.
- `D3-02`: Decided. Publication is controlled at Event level only.
  - Rule: occurrence publication fields are mirrored/derived for read/query performance and cannot be managed independently.
  - Consistency rule: any publication transition writes Event + affected occurrences in one transaction to guarantee sync.
- `D3-03`: Decided. Adopt an occurrence-first contract model.
  - Contract rule: occurrence-scoped deltas/endpoints require `occurrence_id`; `event_id` is always present as parent reference.
  - Contract rule: use explicit payload types (e.g., `event_*` vs `occurrence_*`) instead of optional-ID ambiguity.
  - Approval note: this is an approved contract-shape change under Phase 3.
- `D3-04`: Decided. `event_occurrences` is the canonical query unit (agenda/search/filter/stream).
  - Modeling rule: each occurrence document is equivalent to extracting one embedded occurrence item from Event, plus mirrored event fields needed for list/read performance.
  - Storage rule: occurrences are persisted in tenant-scoped databases (Spatie multitenancy tenant migration flow), so occurrence documents do not persist `tenant_id`.
  - Canonical fields for query: `event_id`, `starts_at`, `ends_at`, `venue_geo`, `taxonomy_terms`, `is_event_published`, `updated_at`.
  - Taxonomy filter contract: term-object matching (`type` + `value`) on `taxonomy_terms`, `venue.taxonomy_terms`, and `artists.taxonomy_terms`.
  - Canonical indexes:
    - `{ deleted_at, is_event_published, starts_at, _id }` (agenda ordering/pagination with soft-delete guard)
    - `{ updated_at, _id }` (stream delta ordering/live cursor)
    - `{ deleted_at, _id }` (soft-delete delta path)
    - `{ event_id, starts_at }` (event detail timeline and occurrence sync lookups)
    - `{ venue.id, starts_at, _id }` (venue/profile filtering)
    - `{ categories, starts_at, _id }` and `{ tags, starts_at, _id }` (term filters)
    - `{ taxonomy_terms.type, taxonomy_terms.value, starts_at, _id }` and `{ venue.taxonomy_terms.type, venue.taxonomy_terms.value, starts_at, _id }` (term-object taxonomy filters)
    - `2dsphere(venue_geo)` (radius queries)
    - `unique(event_id, occurrence_index)` (occurrence identity per event)
- `D3-05`: Decided. No backward-compatibility layer is required for single-date event consumers.
  - Delivery rule: client apps must migrate to the occurrence-first contract before/with Phase 3 rollout.
- `D3-06`: Decided. Capability governance is two-level.
  - Tenant decides which capabilities are available.
  - Event decides whether to use each available capability.
- `D3-07`: Decided. Event usage is bounded by tenant availability.
  - Invariant: an Event cannot use a capability that is unavailable in Tenant.
  - Runtime rule: effective state is `tenant_available && event_enabled`.
- `D3-08`: Decided. Tenant capability disable is immediate and non-destructive.
  - Disable applies to all events (including past events) as runtime gating.
  - Disabled capabilities must stop appearing in capability-driven UI payloads and stop executing runtime behavior.
- `D3-09`: Decided. Capability data/config is retained while disabled.
  - Re-enable restores behavior from persisted config, without requiring re-entry.
- `D3-10`: Decided. Capability updates must be atomic partial merges.
  - Only keys present in request payload can change.
  - Keys absent from payload must remain unchanged.
  - No implicit capability-config wipe on partial updates.
- `D3-11`: Decided. Capability schema/index lifecycle is per capability and tenant-scoped.
  - Each concrete capability may define dedicated migrations/indexes for performance.
  - Migration execution must follow Spatie multitenancy tenant-scoped execution patterns (no landlord/global-only migration shortcut).
- `D3-12`: Decided. Scoped settings must be standardized through a universal settings kernel package.
  - Scope: kernel supports scoped settings contexts (`tenant` and `landlord`).
  - Kernel scope: contracts, namespace registry, schema validation, and patch-merge semantics.
  - Goal: make package-level capabilities/settings portable across projects.
- `D3-13`: Decided. Settings architecture is split between kernel persistence lifecycle and host integration/runtime wiring.
  - Kernel package owns canonical settings persistence lifecycle (model/migration).
  - Host app provides scope-aware integration adapters/bindings (`landlord|tenant`) and runtime wiring.
  - Packages register declarative schemas/capabilities and consume settings contracts without direct persistence coupling.
- `D3-14`: Decided. Settings UI must be backend-schema-driven.
  - Required tenant-admin endpoints:
    - `GET .../settings/schema` (discoverable namespaces/fields/capabilities)
    - `GET .../settings/values` (current persisted values)
    - `PATCH .../settings/values/{namespace}` (atomic partial merge; keys not in payload remain unchanged)
  - Landlord on-behalf variants must follow the same generic contract surface.
- `D3-15`: Decided. Phase 3 execution is split into two blocks.
  - Block 1 (execute now): package/settings/capability foundation plus one pilot capability (`multiple_occurrences`).
  - Mandatory pause: stop before concrete capability implementation.
  - Block 2 (final): execute concrete capabilities through dedicated TODOs referenced in `Final Capability TODO References (Canonical for Block 2)`.
- `D3-16`: Decided. Pilot capability `multiple_occurrences` payload and normalization rules.
  - Tenant canonical payload:
    - `allow_multiple: bool`
    - `max_occurrences: int|null`
  - Normalization rule: incoming `max_occurrences=0` is treated as `null` (no numeric cap configured).
  - Event-level usage: Event can opt in/out (`enabled`) only within tenant availability rules.
  - Interpretation rule:
    - if tenant `allow_multiple=false`, Event cannot use multiple occurrences;
    - if tenant `allow_multiple=true` but event usage is disabled, Event cannot use multiple occurrences;
    - if effective usage is enabled and `max_occurrences` is non-null, it defines the maximum number of occurrences permitted per event.
- `D3-17`: Decided. Events contract remains invite-agnostic until Invites feature implementation.
  - Removal rule: Events/EventOccurrences do not persist `confirmed_user_ids`, `received_invites`, `sent_invites`, or `friends_going`.
  - API rule: agenda/event payloads do not expose those fields (including `is_confirmed`/`total_confirmed` derivatives tied to invite lifecycle data).
- `D3-18`: Decided. Creator/audit identity and authorization ownership are separate.
  - Audit rule: persist creator context in `created_by` (typed principal), including tenant-admin/on-behalf variants.
  - Authorization rule: write access is resolved from ACL principals, not from creator identity alone.
- `D3-19`: Decided. Shared ownership is explicit and relation-agnostic.
  - Ownership rule: editable authority is represented by shared principals (ACL-style) and can include account/tenant/user delegates.
  - Boundary rule: relation fields (`venue.id`, `artists[].id`, etc.) remain content links only and must not imply ownership automatically.
- `D3-20`: Decided. Generic event-party extension uses adapter/mappers, not model inheritance/contracts.
  - Package rule: define neutral `event_parties` payload shape and mapper contracts at service boundary.
  - Host rule: project models (Venue, Artist, others) map to `event_parties` via adapters/registries; models are not required to implement package contracts.
  - SOLID rule: avoid LSP/ISP violations by keeping model contracts optional and mapper-driven.
- `D3-21`: Decided. Event-party authorization is payload-driven per party row, not by party type alone.
  - Rule: two parties of the same type may have different effective edit authority in the same event.
  - Rule: authorization for update/delete/publish/unpublish applies owner/admin override first, then checks party-row `can_edit`.
- `D3-22`: Decided. Canonical permission model is simplified for this phase.
  - Canonical flag: `can_edit`.
  - Scope rule: this single flag gates mutable actions in this slice (`update`, `delete`, `publish`, `unpublish`).
  - Delete rule: `delete` in this scope is soft delete only.
- `D3-23`: Decided. Event-party types support code-level defaults with per-row payload override.
  - Default rule: each party type defines default `can_edit` in mapper/policy code.
  - Resolution rule: row-level payload-present `can_edit` overrides default; omitted key keeps default value.
  - Boundary rule: no settings dependency is required for event-party permission defaults.
  - Merge rule: partial updates remain atomic by payload-present keys only.
- `D3-24`: Decided. Core location cutover is a mandatory pre-capability gate.
  - Contract rule: event writes adopt canonical `location` + typed `place_ref`; legacy `venue_id` is removed.
  - Mode rule: `location.mode` supports `physical|online|hybrid` with mode-specific required fields.
  - Query rule: geo filters include only events with valid geographic basis; online-only regional projection is handled by `map_poi.discovery_scope`.
  - Traceability: execution contract is tracked in `TODO-v1-events-location-core-cutover.md`.
