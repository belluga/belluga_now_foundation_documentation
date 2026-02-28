# TODO (V1): Ticketing Package Integration (Post-Events Foundation)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Establish ticketing as a dedicated Laravel package integrated with Events, with occurrence-first ticket ownership (`occurrence_id`) and template-driven event creation presets, keeping Events focused on core event lifecycle and `map_poi` capability only.

---

## Scope
- Create `belluga_ticketing` package as first-class owner of ticket-domain behavior.
- Establish occurrence-first ticketing model (`occurrence_id` as canonical runtime key for ticket lifecycle operations).
- Migrate ticket-domain slices previously modeled as Events capabilities into Ticketing package scope:
  - inventory
  - pricing_fees
  - combo
  - qr_checkin
  - participant_student_binding
  - ticket limits/quotas
- Define template-driven authoring for event creation, including:
  - reusable templates with ticketing-related predefinitions
  - hidden/predefined fields that are applied server-side and not exposed in create flow UI payload
- Define package contracts/adapters between `belluga_events` and `belluga_ticketing`.
- Keep settings-driven behavior via settings kernel contracts (`tenant` and, if needed, `landlord`) without direct persistence coupling.
- Ensure tenant-scoped migrations/indexes remain compatible with Spatie multitenancy execution flow.

---

## Out of Scope
- Events core lifecycle changes already delivered in Phase 3 foundation.
- `map_poi` capability implementation (remains in Events stream).
- Frontend ticketing UX flows.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md`
- Multitenancy execution must remain aligned with Spatie tenant migration/runtime model.
- Full Laravel suite is mandatory for important milestones and closure.

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `TKT-01` Canonical package boundary and bootstrap strategy (`belluga_ticketing` ownership and service-provider wiring).
- [ ] ⚪ `TKT-02` Canonical ticket aggregate model and mandatory identity keys (`event_id`, `occurrence_id`, ticket identity).
- [ ] ⚪ `TKT-03` Settings namespace strategy for ticketing (single namespace vs split by concern).
- [x] ✅ Production‑Ready `TKT-04` Integration/governance contract between Events and Ticketing.
  - Rule: package integration is contract-based (`Events <-> Ticketing`), not modeled as an Events capability.
  - Rule: tenant availability/event enablement is enforced by integration contracts and ticketing settings, not by a dedicated “integration capability”.
- [ ] ⚪ `TKT-05` Concurrency/atomicity strategy for inventory, limits, and combo under high-write scenarios.
- [ ] ⚪ `TKT-06` Check-in idempotency and audit contract (token/proof model and replay safety).
- [ ] ⚪ `TKT-07` Migration/index plan per ticketing concern with tenant-scoped execution guarantees.
- [ ] ⚪ `TKT-08` Historical traceability policy for superseded Events capability TODOs.
- [x] ✅ Production‑Ready `TKT-09` Integration mechanism is hybrid.
  - Synchronous path: direct contract checks for hard guardrails (must block invalid writes immediately).
  - Asynchronous path: domain events/jobs for projection/reconciliation side effects.
- [x] ✅ Production‑Ready `TKT-10` Ownership boundary is strict.
  - `belluga_events` owns event/occurrence/publication/location lifecycle.
  - `belluga_ticketing` owns inventory/pricing/combo/check-in/participant-binding/limits.
- [x] ✅ Production‑Ready `TKT-11` Integration toggle policy.
  - Optional settings toggle (`ticketing_enabled`) is allowed.
  - This toggle is an operational switch, not a product capability.
- [x] ✅ Production‑Ready `TKT-12` Ticket ownership granularity is occurrence-first.
  - Rule: ticket lifecycle concerns are anchored at occurrence level, not event level.
  - Rule: inventory/pricing/combo/check-in/binding/limits operations require `occurrence_id`.
  - Rule: event-level fields may provide defaults/templates only and are not ticket runtime source-of-truth.
- [x] ✅ Production‑Ready `TKT-13` Template-driven event creation with hidden predefinitions.
  - Rule: templates can predefine ticketing/event fields and hide selected fields from end-user creation UI.
  - Rule: hidden fields are applied server-side from approved template and cannot be overridden by regular user payload.
  - Rule: template application must be auditable (`template_id` + applied version/snapshot reference in creation metadata).
- [x] ✅ Production‑Ready `TKT-14` Template field-state and default precedence policy.
  - Field-state enum: `enabled|disabled|hidden` (where `hidden` matches product term `hide`).
  - Rule: template may override system default and establish a template-specific default value per field.
  - Precedence rule (when field is mutable): `system_default -> template_default -> explicit_payload`.
  - Rule for `disabled`: payload cannot override; effective value comes from `template_default` when present, else `system_default`.
  - Rule for `hidden`: field is not exposed in standard create UI and payload cannot override; effective value comes from `template_default` when present, else `system_default`.
- [x] ✅ Production‑Ready `TKT-15` Bundle semantics split (`combo` vs `passport`).
  - `combo`: scoped to a single occurrence (`occurrence_id`); may include multiple ticket types/quantities from that same occurrence.
  - `passport`: scoped to event (`event_id`); may include entitlements across any occurrence of that event.
  - Invariant: `passport` cannot include entitlements from occurrences of different events.
- [x] ✅ Production‑Ready `TKT-16` Passport inventory strategy for V1.
  - Decided mode: reserve inventory on purchase (allocation antecipada).
  - Rule: selling a passport immediately allocates/reserves capacity for covered occurrences according to configured passport policy.
  - Rule: purchase must fail atomically when required occurrence capacities cannot be reserved.
- [ ] ⚪ `TKT-17` Payments boundary strategy: embed payment integration inside ticketing vs dedicated `payments` package with contracts.
- [ ] ⚪ `TKT-18` Payment provider strategy for V1: single PSP adapter first vs pluggable multi-provider from day one.
- [ ] ⚪ `TKT-19` Financial snapshot contract at purchase time (`gross`, `fees`, `buyer_total`, `merchant_net`, `currency`, policy version).
- [ ] ⚪ `TKT-20` Fee policy model: `absorbed|pass_through|mixed` and override hierarchy (system default, tenant default, template override, runtime lock).
- [ ] ⚪ `TKT-21` Reservation/authorization flow with payments:
  - reserve inventory before payment authorization vs after authorization
  - timeout/release policy for unpaid reservations
  - atomicity guarantees across reservation + payment status transitions.
- [ ] ⚪ `TKT-22` Webhook and idempotency model:
  - canonical event set (`authorized|captured|failed|canceled|refunded|chargeback`)
  - idempotency keys and replay handling
  - source-of-truth precedence between synchronous response and webhook.
- [ ] ⚪ `TKT-23` Refund/cancel policy and side effects:
  - full vs partial refund
  - inventory return policy
  - check-in reversal constraints and audit behavior.
- [ ] ⚪ `TKT-24` Settlement and reconciliation model:
  - asynchronous reconciliation cadence
  - mismatch handling
  - operational alerting thresholds.
- [x] ✅ Production‑Ready `TKT-25` Monetization modes for ticketing.
  - Canonical modes for V1 contract: `free|paid`.
  - `free` does not require checkout/payment authorization.
  - `paid` requires checkout/payment integration path before activation.
- [x] ✅ Production‑Ready `TKT-26` Account-level checkout linkage model.
  - Each account may store its own checkout linkage/configuration (e.g., merchant/account identifiers, routing profile, enablement flags).
  - Ticketing must resolve payment context by account scope when mode is `paid`.
  - Storage and access of sensitive checkout credentials remain outside ticketing domain payloads (adapter/secret boundary).
- [x] ✅ Production‑Ready `TKT-27` Delivery scope for current iteration.
  - Current implementation focus is `free` mode only.
  - `paid` mode remains deferred until checkout integration path is finalized (local package or ecosystem checkout product).
  - Runtime rule for this slice: attempts to activate/execute `paid` mode must fail fast with explicit “integration not available” error contract.
- [x] ✅ Production‑Ready `TKT-28` High-contention inventory protection and anti-oversell invariant.
  - Invariant: under ticket limits, confirmed reservations/purchases must never exceed available inventory.
  - Rule: allocation path must be concurrency-safe and deterministic under heavy contention.
- [x] ✅ Production‑Ready `TKT-29` Explicit purchase queue and hold-window model.
  - Rule: hold window is always-on as default admission control.
  - Rule: hold/queue admission control applies to both monetization modes (`free` and `paid`).
  - Rule: `free` mode cannot bypass hold/queue thresholds or anti-oversell constraints.
  - Rule: if `on_hold >= available` at entry time, user is enqueued (explicit queue order).
  - Rule: user is admitted from queue only when `available > on_hold`.
  - Rule: when user is admitted to purchase slot, a temporary hold window is granted to finalize checkout.
  - Rule: on timeout/abandonment, hold is released and queue advances automatically.
- [x] ✅ Production‑Ready `TKT-30` Hold-window configuration hierarchy.
  - Tenant-level setting defines default hold duration (default value: 10 minutes; tenant-editable).
  - Event-level configuration may override tenant default for that event.
  - Effective hold duration is resolved by precedence: `event_override -> tenant_default`.
- [x] ✅ Production‑Ready `TKT-31` Queue trigger threshold and boundary condition.
  - Trigger condition: enqueue when `on_hold >= available`.
  - Admission condition: allocate hold only when `available > on_hold`.
  - Boundary rule (`on_hold == available`): user must stay in queue (no direct admission).
- [x] ✅ Production‑Ready `TKT-33` Free-mode reservation safety.
  - Rule: `free` mode uses the same reservation/hold/queue engine used for paid inventory protection.
  - Rule: `free` mode is subject to identical anti-oversell invariants.
- [x] ✅ Production‑Ready `TKT-34` Scope separation for participants vs attendees.
  - `event_parties` (`artists`, `venues`) belongs to Events domain (content/ownership context).
  - attendee/student binding belongs to Ticketing domain (eligibility/identity context for access flows).
  - Rule: no schema/payload/contract conflation between these scopes.
- [x] ✅ Production‑Ready `TKT-32` Transaction explicitness matrix (anti-gap gate).
  - Rule: for each critical flow, the TODO must explicitly state `transaction_required: yes|no` with rationale.
  - Rule: every flow marked `transaction_required: yes` must run with transactional guarantees and fail fast when transactions are unavailable.
  - Rule: no critical flow may remain implicit/generic about transaction requirements.

### TKT-32 Critical Flow Transaction Matrix (Must Stay Explicit)
- [ ] ⚪ `TX-01` Reservation allocation/release: `transaction_required = yes` (capacity integrity path).
- [ ] ⚪ `TX-02` Queue slot transition + hold grant/release: `transaction_required = yes` (admission integrity path).
- [ ] ⚪ `TX-03` Purchase confirmation with inventory impact: `transaction_required = yes` (sellable capacity path).
- [ ] ⚪ `TX-04` Passport pre-allocation across occurrences: `transaction_required = yes` (cross-occurrence capacity path).
- [ ] ⚪ `TX-05` Refund/cancel with inventory return: `transaction_required = yes` (capacity restoration path).
- [ ] ⚪ `TX-06` Non-capacity side effects (telemetry/notifications): `transaction_required = no` (outbox/async path).

---

## Tasks
- [ ] ⚪ Create package skeleton (`belluga_ticketing`) and composer wiring.
- [ ] ⚪ Define contracts/adapters for Events <-> Ticketing integration.
- [ ] ⚪ Implement synchronous guard contracts for write-time invariants between Events and Ticketing.
- [ ] ⚪ Implement asynchronous domain-event/job flow for non-blocking side effects and reconciliation.
- [ ] ⚪ Define and approve canonical ticketing payload contracts.
- [ ] ⚪ Add monetization mode contract (`free|paid`) to ticketing payloads and validation.
- [ ] ⚪ Define and approve template model/contracts (`template_id`, visibility policy, hidden/predefined fields, versioning).
- [ ] ⚪ Implement template selection/application flow in event creation pipeline.
- [ ] ⚪ Enforce server-side immutability/override guard for hidden template fields.
- [ ] ⚪ Implement field-state policy (`enabled|disabled|hidden`) and default precedence resolver.
- [ ] ⚪ Implement inventory inside ticketing package.
- [ ] ⚪ Implement pricing/fees inside ticketing package.
- [ ] ⚪ Implement combo rules inside ticketing package.
- [ ] ⚪ Implement passport product model/rules at event scope.
- [ ] ⚪ Implement purchase-time reservation logic for passport with atomic cross-occurrence allocation.
- [ ] ⚪ Implement high-contention reservation engine with anti-oversell guarantees.
- [ ] ⚪ Implement explicit queue lifecycle (enqueue, slot assignment, timeout release, next-user advance).
- [ ] ⚪ Implement queue trigger gate with threshold rule (`on_hold >= available` -> enqueue).
- [ ] ⚪ Implement hold-window resolver with hierarchy (`event_override -> tenant_default`) and tenant default bootstrap (`10m`).
- [ ] ⚪ Enforce that `free` mode uses the same hold/queue reservation path (no bypass branch).
- [ ] ⚪ Implement and maintain the explicit transaction matrix coverage for all critical flows (`TX-01..TX-06`).
- [ ] ⚪ Implement transaction boundaries for all flows marked `transaction_required = yes`.
- [ ] ⚪ Implement fail-fast guard for any `transaction_required = yes` flow when transaction capability is unavailable.
- [ ] ⚪ Implement qr_checkin inside ticketing package.
- [ ] ⚪ Implement participant/student binding inside ticketing package.
- [ ] ⚪ Enforce explicit boundary so attendee/student binding never reuses Events `event_parties` structures.
- [ ] ⚪ Implement ticket limits/quotas inside ticketing package.
- [ ] ⚪ Register ticketing settings schemas/values through settings kernel.
- [ ] ⚪ Add optional `ticketing_enabled` settings toggle and enforce it in integration entrypoints.
- [ ] ⚪ Add account-level checkout linkage schema/contracts (IDs/config references) with secure adapter boundary.
- [ ] ⚪ Add tenant setting for hold-window default duration and event-level override contract.
- [ ] ⚪ Implement `free` mode end-to-end flow as the only active monetization mode in this slice.
- [ ] ⚪ Implement fail-fast behavior for `paid` mode while integration is deferred.
- [ ] ⚪ Add tenant-scoped migrations/indexes required by ticketing runtime paths.
- [ ] ⚪ Add/expand tests (unit + feature) for ticketing behavior and Events integration boundaries.
- [ ] ⚪ Add tests validating strict separation (`event_parties` != attendee/student binding contracts).
- [ ] ⚪ Add tests for template application, hidden-field masking, and forbidden override attempts.
- [ ] ⚪ Synchronize package README/contracts/roadmap documentation.

---

## Validation Steps
- [ ] ⚪ `php artisan test` (full Laravel suite; mandatory).
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ Ticketing package feature tests for create/update/allocation/check-in flows.
- [ ] ⚪ Ticketing package unit tests for limits/preference/rounding/precedence rules.
- [ ] ⚪ Monetization mode tests (`free` active, `paid` blocked/deferred with explicit error contract).
- [ ] ⚪ Account-level checkout context resolution tests for future `paid` path guardrails.
- [ ] ⚪ Bundle tests for `combo` (single occurrence invariant) and `passport` (event scope invariant).
- [ ] ⚪ Passport purchase tests validating atomic pre-allocation and rollback on partial-capacity failure.
- [ ] ⚪ Concurrency tests under high contention validating anti-oversell invariant.
- [ ] ⚪ Queue/hold tests validating timeout release and deterministic queue advancement.
- [ ] ⚪ Boundary tests validating queue trigger/admission threshold (`on_hold >= available` enqueue; `available > on_hold` admit).
- [ ] ⚪ Free-mode contention tests validating identical hold/queue/anti-oversell behavior.
- [ ] ⚪ Configuration tests validating effective hold duration precedence (`event_override -> tenant_default`).
- [ ] ⚪ Transactional integrity tests mapped 1:1 to `TX-01..TX-05` (commit/rollback behavior under contention and failure).
- [ ] ⚪ Explicit non-transaction tests for `TX-06` async/outbox behavior.
- [ ] ⚪ Fail-fast tests for runtimes without transaction support on all `transaction_required = yes` flows.
- [ ] ⚪ Template flow tests for creation from template with hidden/predefined fields applied server-side.
- [ ] ⚪ Security tests ensuring hidden template fields cannot be overridden by regular client payload.
- [ ] ⚪ Tests for field-state matrix (`enabled|disabled|hidden`) and default precedence behavior.
- [ ] ⚪ Tenant-scoped migration/index validation for ticketing collections.

---

## Definition of Done
- [ ] ⚪ Ticketing domain is package-owned and no longer represented as fragmented Events capabilities.
- [ ] ⚪ Events package remains focused on core lifecycle + `map_poi` capability.
- [ ] ⚪ Ticketing runtime behavior is deterministic and validated under tenant-scoped execution.
- [ ] ⚪ Events/Ticketing integration contracts are explicit and test-covered.
- [ ] ⚪ Hybrid integration mechanism is delivered and test-covered (sync guardrails + async side effects).
- [ ] ⚪ Optional integration toggle (`ticketing_enabled`) is implemented as operational switch (non-capability).
- [ ] ⚪ Occurrence-first ticket runtime is fully enforced (`occurrence_id` required in ticket lifecycle operations).
- [ ] ⚪ Template-based event creation is delivered with auditable hidden/predefined field application.
- [ ] ⚪ Template field-state policy (`enabled|disabled|hidden`) and default precedence are deterministic and test-covered.
- [ ] ⚪ Bundle split is delivered and validated: `combo` (occurrence scope) and `passport` (event scope).
- [ ] ⚪ Passport reservation-on-purchase policy is implemented with atomicity guarantees.
- [ ] ⚪ Monetization `free|paid` contract is in place with `free` fully operational and `paid` explicitly deferred/guarded.
- [ ] ⚪ Account-level checkout linkage contract is documented and validated for future paid activation.
- [ ] ⚪ High-contention purchase flow is protected against oversell with queue + hold lifecycle.
- [ ] ⚪ Hold-window default (`10m`, tenant-editable) and event-level override are implemented and validated.
- [ ] ⚪ Queue threshold policy is enforced with deterministic boundary behavior (`on_hold == available` queues).
- [ ] ⚪ Free-mode path enforces hold/queue/anti-oversell invariants with no reservation bypass.
- [ ] ⚪ Participant scopes remain explicit and separated: Events parties vs Ticketing attendee/student binding.
- [ ] ⚪ Transaction matrix is explicit and complete (`TX-01..TX-06`) with no implicit/generic critical-flow gaps.
- [ ] ⚪ All flows marked `transaction_required = yes` are transaction-safe and fail fast when unsupported.
- [ ] ⚪ Documentation is synchronized and legacy capability TODOs are archived with traceability.

---

## Implementation Notes (Latest Iteration)
- Created by architectural decision to avoid overloading Events capability layer with a full ticket-domain bounded context.
- Supersedes these TODOs as execution artifacts (to be archived):
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-limits.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-pricing-fees.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-inventory.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-combo.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-qr-checkin.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-participant-student-binding.md`

---

## Decision Log
- `TKT-00`: Decided. Ticket-domain concerns are implemented as dedicated package integration (not as independent Events capabilities), while `map_poi` remains an Events capability.
- `TKT-04`: Decided. Events/Ticketing relation is integration contract, not Events capability.
  - Governance is enforced by contracts + settings, preserving package boundaries.
- `TKT-09`: Decided. Integration mechanism is hybrid.
  - Synchronous contract calls enforce hard write-time invariants.
  - Asynchronous events/jobs handle projection/reconciliation side effects.
- `TKT-10`: Decided. Ownership boundary is explicit.
  - Events remains source-of-truth for event lifecycle entities.
  - Ticketing remains source-of-truth for ticket lifecycle and commercial constraints.
- `TKT-11`: Decided. Optional `ticketing_enabled` toggle is operational only (not a capability).
- `TKT-12`: Decided. Ticket ownership granularity is occurrence-first.
  - `occurrence_id` is mandatory for ticket runtime concerns.
  - Event-level data may only act as defaults/templates for authoring.
- `TKT-13`: Decided. Event creation supports reusable templates with hidden predefinitions.
  - Template can prefill and hide fields from end-user create flow.
  - Hidden fields are server-applied and protected against regular payload overrides.
- `TKT-14`: Decided. Template controls field state and default precedence.
  - Enum: `enabled|disabled|hidden` (`hidden` = `hide`).
  - Template default can override system default for that template context.
  - Payload override is allowed only for `enabled` fields.
  - `disabled` and `hidden` fields are server-controlled in standard create flows.
- `TKT-15`: Decided. Bundle semantics are split in ticketing.
  - `combo` is occurrence-scoped.
  - `passport` is event-scoped and can cover multiple occurrences of the same event.
- `TKT-16`: Decided. Passport uses reservation on purchase (pre-allocation) in V1.
  - Capacity reservation happens during purchase, not only on use/check-in.
  - Allocation must be atomic across required occurrence reservations.
- `TKT-25`: Decided. Ticketing monetization modes are `free|paid`.
  - `free` is non-payment flow.
  - `paid` depends on checkout/payment integration.
- `TKT-26`: Decided. Checkout linkage is account-scoped.
  - Account can have own checkout IDs/configuration.
  - Ticketing resolves payment context by account when `paid` mode is active.
- `TKT-27`: Decided. Current delivery scope is `free` mode only.
  - `paid` remains deferred until checkout integration is finalized.
  - Requests that require `paid` execution must return explicit integration-unavailable failure.
- `TKT-28`: Decided. Anti-oversell is mandatory under ticket limits.
  - Concurrency path must prevent confirmed allocations above inventory.
- `TKT-29`: Decided. Purchase queue is explicit with hold timeout and automatic release.
  - Timeout release must advance queue deterministically.
- `TKT-30`: Decided. Hold duration is configurable by tenant with event-level override.
  - Default tenant value starts at 10 minutes and can be changed.
- `TKT-31`: Decided. Queue trigger uses hold-vs-availability threshold.
  - Enqueue when `on_hold >= available`.
  - Admit only when `available > on_hold`.
- `TKT-33`: Decided. `free` mode must enforce hold/queue/anti-oversell the same way as paid.
  - No free-mode reservation bypass is allowed.
- `TKT-34`: Decided. `artists/venues` participants and attendee/student binding are different scopes.
  - Events keeps `event_parties`.
  - Ticketing keeps attendee/student eligibility binding.
- `TKT-32`: Decided. Transaction requirements must be explicit per critical flow (matrix gate).
  - No critical flow can remain unspecified regarding transaction requirement.
  - `transaction_required = yes` implies mandatory transactional path + fail-fast without support.
