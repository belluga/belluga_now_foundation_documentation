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
- Establish capability governance for ticketing-style extensions (tenant-available vs event-enabled).
- Define non-destructive capability disable/reenable semantics.
- Define atomic partial-update semantics for capability payloads.
- Define per-capability migration/index strategy compatible with tenant-scoped migration execution.
- Integrate Events capability foundation with the universal tenant settings architecture (already delivered) so capability settings are registered and resolved via schema.
- Execute Phase 3 in two blocks: foundation first (including one pilot capability), concrete ticketing capabilities last.

---

## Out of Scope
- Unplanned contract drift outside the approved occurrence-first hard-cutover target.
- Non-Events domain expansion.
- Concrete ticketing capability implementation in the first execution block beyond one pilot capability (`multiple_occurrences`).

---

## Standards/Exception Reference (Locked)
- Standards baseline for this phase is defined in:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Section `F`).
- Approved deviations relevant to Phase 3 are defined in:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Section `G`), especially `EX-02` and `EX-03`.
- Dedicated settings-foundation stream for this phase:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-settings-kernel-package.md`.

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
- [x] ✅ Production‑Ready `D3-15` Execution sequence: implement foundation block first including one pilot capability (`multiple_occurrences`); stop before concrete ticketing capability creation and align before final block execution.
- [x] ✅ Production‑Ready `D3-16` Pilot capability payload semantics (`multiple_occurrences`): Tenant Admin defines tenant-scoped settings `allow_multiple` (bool) and `max_occurrences` (int|null, where client `0` is normalized to `null`); event create/update can only opt in/out of using multiple occurrences if tenant settings allow it.

---

## Tasks
- [ ] ⚪ Add structured logs/metrics around event writes, stream deltas, and publication transitions.
- [ ] ⚪ Improve retry/backoff and failure handling for async listeners.
- [ ] ⚪ Evaluate and tune Mongo indexes for agenda/filter/stream queries.
- [ ] ⚪ Add resilience tests for stream reconnect and publication edge cases.
- [ ] ⚪ Final cleanup of migration-era transitional wrappers.
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
- [x] ✅ Production‑Ready Stop gate: foundation block finished and paused before concrete ticketing capability implementation.
- [ ] ⚪ Final block (deferred): implement consolidated ticketing capabilities (inventory, qr_checkin, combo, limits, participant/student binding, pricing fees) after alignment.

---

## Validation Steps
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite; mandatory gate for important milestones/phase closure).
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Unit/Events/EventsPackageBindingsTest.php`.
- [ ] ⚪ Targeted load/perf sampling for agenda and stream paths.
- [ ] ⚪ Manual smoke for publication transitions and SSE reconnect behavior.
- [x] ✅ Production‑Ready Capability gate tests: disabled at tenant => non-executable/non-visible capability behavior for all events.
- [x] ✅ Production‑Ready Capability persistence tests: disable/reenable preserves and restores previously persisted capability config.
- [x] ✅ Production‑Ready Partial update tests: payload-present keys update atomically without mutating absent keys.
- [x] ✅ Production‑Ready Pilot capability tests: `multiple_occurrences` follows tenant availability + event enablement + effective runtime gate.
- [x] ✅ Production‑Ready Pilot payload normalization tests: `max_occurrences=0` persists as `null`; positive integers persist as numeric limit.
- [x] ✅ Production‑Ready Pilot create/update rule tests: tenant disallow OR event disabled => max allowed occurrences is `1`; tenant allow + event enabled + numeric `max_occurrences` => enforce tenant max.
- [x] ✅ Production‑Ready Settings registry tests: package-registered schema is discoverable and validated.
- [x] ✅ Production‑Ready Settings API tests: schema endpoint and values endpoint return registry-driven payloads.
- [x] ✅ Production‑Ready Settings patch tests: partial patch mutates only payload-present keys for a namespace.
- [x] ✅ Production‑Ready Foundation block gate: full Laravel suite passes before starting final ticketing capability block.
- [ ] ⚪ Final block validation (deferred): tenant-scoped migration/index validation and capability-specific integration tests for each ticketing capability.

---

## Definition of Done
- [ ] ⚪ Events package reliability baseline is documented and measurable.
- [ ] ⚪ Known bottlenecks and failure modes are mitigated or explicitly tracked.
- [ ] ⚪ Architecture docs reflect final post-hardening state.
- [x] ✅ Production‑Ready Capability governance contract (tenant availability + event usage + effective runtime gating) is implemented and verified.
- [x] ✅ Production‑Ready Capability disable/reenable behavior is non-destructive and validated.
- [x] ✅ Production‑Ready Capability update semantics are atomic/partial and protected by tests.
- [x] ✅ Production‑Ready Pilot capability `multiple_occurrences` validates the foundation pipeline (registry/config/gating/partial update semantics, including `max_occurrences` normalization).
- [x] ✅ Production‑Ready Universal settings kernel is active and events package consumes tenant settings only through settings contracts/registry.
- [x] ✅ Production‑Ready Foundation block is delivered with pilot `multiple_occurrences` and explicitly paused before final ticketing capability block execution.

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
    - `{ is_event_published, starts_at, _id }` (agenda ordering/pagination)
    - `{ event_id, starts_at }` (event detail timeline)
    - `{ updated_at, _id }` (stream delta ordering/live cursor)
    - `2dsphere(venue_geo)` (radius queries)
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
  - Mandatory pause: stop before concrete ticketing capability implementation.
  - Block 2 (final): implement concrete ticketing capabilities after explicit alignment on each one.
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
