# TODO (VNext): Generic Missions Package (`belluga_missions`)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-03-10

## Objective
Define and implement `belluga_missions` as a generic Laravel package that powers event/account-profile "Challenges" from registered behavior sources without owning those source domains.

Product language may use **Challenges**, but the canonical backend domain remains **Missions**.

---

## 1. Problem Statement

Event/account managers need reward programs such as:
- `5 accepted invites -> 5% discount`
- `10 accepted invites -> 10% discount`
- `5 check-ins -> 10% discount + first beer`

Those programs should not be implemented inside Invites, Events, Ticketing, or Check-in directly because:
- multiple domains can contribute qualifying behaviors,
- reward rules must stay reusable across event/account-profile flows,
- the evaluation engine must remain generic and auditable,
- the same challenge framework may later consume purchases, bookings, offer claims, or other behaviors.

---

## 2. Foundational Direction

- [ ] 🟡 Provisional `MSN-01` Missions are a dedicated package (`belluga_missions`), not an Events capability and not an Invites submodule.
- [ ] 🟡 Provisional `MSN-02` Missions are **behavior-driven**: source domains emit canonical events; Missions evaluates progress and reward unlocks.
- [ ] 🟡 Provisional `MSN-03` Missions do **not** own source truth for invites, presence confirmation, check-in, purchases, or reservations.
- [ ] 🟡 Provisional `MSN-04` Missions are **registry-driven generic**, not a free-form scripting/rules engine in VNext.
- [ ] 🟡 Provisional `MSN-05` Product label may remain "Challenge", but canonical API/package/entity naming should use `mission`.

---

## 3. Critical Terminology Split

This package must be designed with an explicit distinction between:

- **Presence Confirmation**: a reservation/commitment-style signal that a user intends or is expected to attend.
- **Check-in**: an on-site validation/proof-of-arrival signal (QR, geofence, staff/manual, admission validation).

- [ ] ⚪ Define canonical naming for both concepts before implementation.
- [ ] ⚪ Reconcile current documentation that still conflates check-in and presence confirmation.
- [ ] ⚪ Ensure mission metrics can target either concept independently when business rules require it.

Implication:
- a mission may reward `presence_confirmations`,
- a different mission may reward `check_ins`,
- neither concept should be silently mapped to the other.

---

## 4. Package Boundary

### 4.1 `belluga_missions` owns
- [ ] ⚪ Mission definitions.
- [ ] ⚪ Mission progress documents/projections.
- [ ] ⚪ Mission completion/unlock state.
- [ ] ⚪ Mission reward claim state.
- [ ] ⚪ Mission audit/outbox events.
- [ ] ⚪ Behavior registry and event-ingestion normalization.

### 4.2 `belluga_missions` does not own
- [ ] ⚪ Invite issuance/acceptance lifecycle.
- [ ] ⚪ Presence confirmation lifecycle.
- [ ] ⚪ Check-in/admission validation.
- [ ] ⚪ Event publication or occurrence scheduling.
- [ ] ⚪ Reward inventory/payment/promotion source-of-truth.
- [ ] ⚪ Friendship/social-graph ownership.

### 4.3 Adjacent source domains
- [ ] ⚪ `belluga_events` provides scope references (`event_id`, later `occurrence_id` where required).
- [ ] ⚪ `belluga_invites` provides invite/social behaviors such as credited invite acceptance.
- [ ] ⚪ Participation/check-in domain provides presence confirmation and check-in behaviors.
- [ ] ⚪ Ticketing/checkout/offers provide reward fulfillment targets where applicable.

---

## 5. Generic Behavior Model

Missions should be generic by **registered behavior key**, not by custom code embedded in each mission.

- [ ] ⚪ Define `MissionBehaviorRegistry` contract with, at minimum:
  - stable `behavior_key`
  - source event topic(s)
  - progress principal extraction rule
  - scope support (`event`, `occurrence`, `account_profile`, future global)
  - aggregation mode
  - dedupe identity requirements
  - payload fields required for progress calculation

- [ ] ⚪ Start with a constrained aggregation baseline:
  - `count`
  - `distinct_actor_count` (optional if justified)
  - `sum` deferred unless needed by purchases/revenue missions

- [ ] ⚪ Require idempotent behavior ingestion so replays do not overcount mission progress.

### 5.1 First concrete behavior registrations

- [ ] ⚪ `invites_accepted`
  - canonical source topic: `invite.accepted`
  - emitted by: `belluga_invites`
  - aggregation: `count`
  - default progress principal: `inviter_principal`
  - minimum source payload:
    - `tenant_id`
    - `event_id`
    - `occurrence_id?`
    - `invite_id`
    - `receiver_user_id`
    - `inviter_principal`
    - `credited_acceptance`
    - `occurred_at`
  - counting rule: counts only when `credited_acceptance = true`
  - dedupe baseline: `invite_id`

- [ ] ⚪ `presence_confirmations`
  - canonical source topic: `participation.presence_confirmation.recorded`
  - emitted by: participation/presence-confirmation domain
  - aggregation: `count`
  - default progress principal: `actor_ref`
  - minimum source payload:
    - `tenant_id`
    - `event_id`
    - `occurrence_id?`
    - `presence_confirmation_id`
    - `actor_ref`
    - `source_type`
    - `occurred_at`
  - dedupe baseline: `presence_confirmation_id`

- [ ] ⚪ `check_ins`
  - canonical source topic: `participation.check_in.recorded`
  - emitted by: participation/check-in domain
  - aggregation: `count`
  - default progress principal: `actor_ref`
  - minimum source payload:
    - `tenant_id`
    - `event_id`
    - `occurrence_id`
    - `check_in_id`
    - `actor_ref`
    - `checkpoint_ref`
    - `method`
    - `occurred_at`
    - `idempotency_key`
  - dedupe baseline: `check_in_id`

- [ ] ⚪ `purchases`
  - reserved behavior key in VNext package baseline
  - canonical source topic/provider deferred pending checkout/booking contract cleanup

### 5.2 Transition bridge

- [ ] ⚪ If upstream producers still emit legacy `participation.presence.recorded`, missions must ingest it only through an explicit bridge/alias decision.
- [ ] ⚪ The bridge must not silently erase the distinction between `presence_confirmations` and `check_ins`.
- [ ] ⚪ Canonical VNext target remains two distinct topics:
  - `participation.presence_confirmation.recorded`
  - `participation.check_in.recorded`

---

## 6. Canonical Data Model

### 6.1 `missions`
- [ ] ⚪ Define canonical mission aggregate with:
  - `tenant_id`
  - `account_profile_id`
  - `scope_type`
  - `scope_ref`
  - `title`
  - `description`
  - `behavior_key`
  - `target_value`
  - `window`
  - `status`
  - `visibility`
  - `reward_refs`
  - `created_by_user_id`
  - `updated_by_user_id`

### 6.2 `mission_progress`
- [ ] ⚪ Track per principal and per mission current progress with deterministic recompute/replay support.
- [ ] ⚪ Support privacy-aware participant summaries for user-facing leaderboards and partner-facing drill-downs.

### 6.3 `mission_progress_events`
- [ ] ⚪ Persist append-only normalized behavior hits used to advance mission progress.
- [ ] ⚪ Store dedupe/idempotency identity and source event refs.

### 6.4 `mission_reward_unlocks`
- [ ] ⚪ Track unlock outcome separately from claim/fulfillment.
- [ ] ⚪ Support multiple reward refs per mission tier where applicable.

### 6.5 `mission_claims`
- [ ] ⚪ Track whether a user claimed/redeemed an unlocked reward when manual or downstream fulfillment is required.

---

## 7. Reward Model

Missions should unlock typed reward references, not opaque free-text only.

- [ ] ⚪ Define reward reference baseline:
  - `ticket_promotion_ref`
  - `offer_ref`
  - `manual_benefit_ref` (only if needed as a temporary bridge)

- [ ] ⚪ Reward unlock does not directly mutate ticketing/offers state inside the mission package.
- [ ] ⚪ Fulfillment remains delegated to the owning reward domain through typed refs/contracts/events.
- [ ] ⚪ Free-text reward copy may exist for UI messaging, but it must not be the canonical fulfillment contract.

---

## 8. API and UX Scope

- [ ] ⚪ Define account-workspace/admin APIs for:
  - create mission
  - list missions by scope
  - update mission status/window/reward refs
  - inspect participant progress

- [ ] ⚪ Define tenant-user read APIs for:
  - list active missions for an event/scope
  - read my progress
  - read unlocked rewards
  - claim reward when applicable

- [ ] ⚪ Define whether mission progress should stream via SSE or rely on page refresh + existing source streams in VNext.

---

## 9. Security, Replay, and Audit

- [ ] ⚪ Mission progress updates must be idempotent under repeated source event delivery.
- [ ] ⚪ Mission completion/unlock must be transactionally safe.
- [ ] ⚪ Audit must preserve:
  - source topic
  - source entity ref
  - actor/principal ref
  - scope ref
  - occurred_at
  - dedupe key

- [ ] ⚪ Account/profile authorization must remain external to missions ownership and resolved through the host app/account workspace boundary.

---

## 10. Open Design Decisions

- [ ] ⚪ Final package name: `belluga_missions` vs `belluga_challenges`.
- [ ] ⚪ Canonical scope baseline: `event_id` only vs `event_id + occurrence_id` support from day one.
- [ ] ⚪ Terminology reconciliation: replace current `presences_confirmed` metric vocabulary with explicit `presence_confirmations` and `check_ins`, or preserve both with a migration map.
- [ ] ⚪ Mission shape baseline: single-behavior threshold only in VNext, or allow tiered missions in first release.
- [ ] ⚪ Whether ranking/leaderboard projection belongs in missions or remains an Insights/Home projection consumer.
- [ ] ⚪ Whether reward claim is automatic, manual, or configurable by reward type.
- [ ] ⚪ Whether retroactive recompute/backfill is supported in first package version.
- [ ] ⚪ Whether behavior definitions should support alternate progress-principal strategies (for example, invited-user progress vs inviter-attribution progress) in first release.
- [ ] ⚪ Friends/social proof remains a separate pending decision and must not block mission package architecture.

---

## 11. Delivery Plan

### A) Canonical contract cleanup
- [ ] ⚪ Split presence confirmation from check-in terminology across the relevant docs before implementation starts.
- [ ] ⚪ Reconcile mission metric language in `domain_entities.md` and related placeholders.

### B) Package skeleton
- [ ] ⚪ Create `belluga_missions` package with service provider, routes, migrations, host-binding pattern, and decoupling expectations consistent with existing package architecture.

### C) Behavior registry
- [ ] ⚪ Implement the behavior registration contract and the normalized ingestion pipeline.
- [ ] ⚪ Wire `invite.accepted`, `participation.presence_confirmation.recorded`, and `participation.check_in.recorded` as the first concrete providers.
- [ ] ⚪ Define and document any temporary alias bridge from legacy `participation.presence.recorded`.

### D) Mission lifecycle
- [ ] ⚪ Implement mission create/update/list/read flows and progress tracking.
- [ ] ⚪ Implement completion/unlock transitions and append-only audit events.

### E) Reward fulfillment integration
- [ ] ⚪ Implement typed reward references and downstream fulfillment hooks/contracts.

### F) Workspace/user surfaces
- [ ] ⚪ Expose partner/account workspace management views and tenant-user progress views through canonical APIs first, then Flutter/Web adoption.

---

## 12. Out of Scope

- Arbitrary expression language or user-authored formulas.
- Full analytics/dashboard productization.
- Friendship/social graph design.
- Cross-tenant/global campaigns.
- Billing logic for rewards.
- Reward inventory ownership inside missions.

---

## 13. Success Criteria

- [ ] ⚪ Missions are implemented as a dedicated, generic package boundary.
- [ ] ⚪ Invite, presence confirmation, and check-in remain separate source domains.
- [ ] ⚪ At least two different behavior providers can drive mission progress without bespoke per-mission code.
- [ ] ⚪ Reward unlocks are deterministic, auditable, and replay-safe.
- [ ] ⚪ Account/event-scoped challenge authoring is possible without coupling the logic to Invites or Events internals.

---

## 14. Initial Example Scenarios

- Event mission: `5 invites_accepted -> ticket_promotion_ref(5% discount)`
- Event mission: `10 invites_accepted -> ticket_promotion_ref(10% discount)`
- Event mission: `5 check_ins -> ticket_promotion_ref(10% discount) + offer_ref(first_beer)`
- Future mission: `3 presence_confirmations -> manual_benefit_ref(priority_access)` if business rules treat confirmations as reservation-like signals rather than on-site attendance proof
