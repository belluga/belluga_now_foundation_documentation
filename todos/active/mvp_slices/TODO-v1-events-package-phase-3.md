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
- Establish universal tenant settings architecture so packages can register settings/capabilities and UI can render from schema.
- Execute Phase 3 in two blocks: foundation first, concrete ticketing capabilities last.

---

## Out of Scope
- Unplanned contract drift outside the approved occurrence-first hard-cutover target.
- Non-Events domain expansion.
- Concrete ticketing capability implementation in the first execution block (inventory, qr_checkin, combo, limits, participant/student binding, pricing fees).

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
- [x] ✅ Production‑Ready `D3-15` Execution sequence: implement foundation block first and stop before concrete capability creation; only then align and execute the final ticketing capability block.

---

## Tasks
- [ ] ⚪ Add structured logs/metrics around event writes, stream deltas, and publication transitions.
- [ ] ⚪ Improve retry/backoff and failure handling for async listeners.
- [ ] ⚪ Evaluate and tune Mongo indexes for agenda/filter/stream queries.
- [ ] ⚪ Add resilience tests for stream reconnect and publication edge cases.
- [ ] ⚪ Final cleanup of migration-era transitional wrappers.
- [ ] ⚪ Introduce capability registry/contracts (`abstract capability` + concrete capability handlers).
- [ ] ⚪ Add tenant capability availability config and event-level capability usage config.
- [ ] ⚪ Enforce runtime gate: `effective_capability = tenant_available && event_enabled`.
- [ ] ⚪ Implement atomic partial capability update flow (`$set` only on payload-present paths; no implicit unset).
- [ ] ⚪ Create `settings` kernel package (contracts + registry + schema validation + merge semantics).
- [ ] ⚪ Implement host-app integration adapters for scope-aware settings routing (`landlord|tenant`) and wire package bindings.
- [ ] ⚪ Register events capability/settings schema through the new settings registry.
- [ ] ⚪ Add schema-driven settings endpoints (`schema`, `values`, partial `patch`) for tenant-admin and landlord on-behalf variants according to kernel contracts.
- [ ] ⚪ Execute settings-foundation tasks in lockstep with `TODO-v1-settings-kernel-package.md` and use it as the authoritative tracker for settings-kernel delivery.
- [ ] ⚪ Stop gate: finish foundation block and pause before concrete ticketing capability implementation for explicit alignment.
- [ ] ⚪ Final block (deferred): implement consolidated ticketing capabilities (inventory, qr_checkin, combo, limits, participant/student binding, pricing fees) after alignment.

---

## Validation Steps
- [ ] ⚪ `php artisan test` (full Laravel suite; mandatory gate for important milestones/phase closure).
- [ ] ⚪ Targeted load/perf sampling for agenda and stream paths.
- [ ] ⚪ Manual smoke for publication transitions and SSE reconnect behavior.
- [ ] ⚪ Capability gate tests: disabled at tenant => non-executable/non-visible capability behavior for all events.
- [ ] ⚪ Capability persistence tests: disable/reenable preserves and restores previously persisted capability config.
- [ ] ⚪ Partial update tests: payload-present keys update atomically without mutating absent keys.
- [ ] ⚪ Settings registry tests: package-registered schema is discoverable and validated.
- [ ] ⚪ Settings API tests: schema endpoint and values endpoint return registry-driven payloads.
- [ ] ⚪ Settings patch tests: partial patch mutates only payload-present keys for a namespace.
- [ ] ⚪ Foundation block gate: full Laravel suite passes before starting final ticketing capability block.
- [ ] ⚪ Final block validation (deferred): tenant-scoped migration/index validation and capability-specific integration tests for each ticketing capability.

---

## Definition of Done
- [ ] ⚪ Events package reliability baseline is documented and measurable.
- [ ] ⚪ Known bottlenecks and failure modes are mitigated or explicitly tracked.
- [ ] ⚪ Architecture docs reflect final post-hardening state.
- [ ] ⚪ Capability governance contract (tenant availability + event usage + effective runtime gating) is implemented and verified.
- [ ] ⚪ Capability disable/reenable behavior is non-destructive and validated.
- [ ] ⚪ Capability update semantics are atomic/partial and protected by tests.
- [ ] ⚪ Universal settings kernel is active and events package consumes tenant settings only through settings contracts/registry.
- [ ] ⚪ Foundation block is delivered and explicitly paused at the capability boundary before final block execution.

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
  - Canonical fields for query: `tenant_id`, `event_id`, `starts_at_utc`, `ends_at_utc`, `venue_geo`, `taxonomy_term_ids`, `is_event_published`, `updated_at`.
  - Canonical indexes:
    - `{ tenant_id, is_event_published, starts_at_utc, _id }` (agenda ordering/pagination)
    - `{ tenant_id, event_id, starts_at_utc }` (event detail timeline)
    - `{ tenant_id, is_event_published, taxonomy_term_ids, starts_at_utc }` (taxonomy filters)
    - `{ tenant_id, updated_at, _id }` (stream delta ordering/live cursor)
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
  - Block 1 (execute now): package/settings/capability foundation only.
  - Mandatory pause: stop before concrete ticketing capability implementation.
  - Block 2 (final): implement concrete ticketing capabilities after explicit alignment on each one.
