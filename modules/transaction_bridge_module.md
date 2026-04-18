# Documentation: Transaction Bridge Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

**Authority note (2026-04-18):** this file is the canonical planning surface for a future checkout/commercial bridge authority. It is not a current code-backed runtime authority. Current ticket-related paid-flow authority lives in the active Ticketing and Checkout program streams, while this document retains the broader future bridge shape for later reconciliation.

## 1. Overview

The Transaction Bridge module (MOD-305) captures the planned future bridge between tenant monetization flows and external checkout/commercial engines. It retains the broader booking/payment/refund/ledger shape for later implementation, but it should not be read as current runtime authority: current code-backed commerce evolution is being driven by the active Ticketing and Checkout package streams.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/task_and_reminder_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-checkout-package-integration.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`

---

## 2. Planned Responsibilities

1. **Booking Lifecycle Management:** Translate future commercial reservations from account-profile/event surfaces into bridge projections, assign status transitions, and keep agenda/task side effects synchronized.
2. **Payment Intent Mediation:** Create payment intents through the Commercial Engine, store the resulting `intent_reference`, and expose simplified status flags to clients (`pending`, `authorized`, `captured`, `failed`).
3. **Transaction Ledger Projection:** Maintain a tenant-scoped ledger summarizing every financial event so UI surfaces (profile balances, booking history) can query a single read model without touching external systems.
4. **Webhooks & Retry Policies:** Handle webhook callbacks (`payment.succeeded`, `refund.created`) with idempotent processors and exponential backoff queues.

---

## 3. Planning Data Structures

The following structures are retained as future-planning sketches, not as current implemented collections or public contracts. Legacy placeholders such as `offer_id` reflect older commerce-era planning and must be reconciled against the canonical Event/Ticketing/Checkout authority before implementation begins.

### 3.1 `booking_reservations`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "user_id": "ObjectId()",
  "offer_id": "ObjectId()",
  "account_profile_id": "ObjectId()",
  "status": "String",
  "party_size": "Number",
  "scheduled_at": "Date",
  "payment_intent_id": "String",
  "pricing_snapshot": {
    "currency": "String",
    "amount": "Number",
    "pricing_model": "String",
    "comps_applied": ["String"]
  },
  "metadata": {},
  "created_at": "Date",
  "updated_at": "Date"
}
```

### 3.2 `transaction_ledger`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "user_id": "ObjectId()",
  "booking_id": "ObjectId()",
  "commercial_event_type": "String",
  "amount": "Number",
  "currency": "String",
  "direction": "String",
  "occurred_at": "Date",
  "external_reference": "String",
  "metadata": {}
}
```

### 3.3 `webhook_events`
Durable log of inbound webhook deliveries with ack status for observability.

---

## 4. Deferred Interface Sketches

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/bookings` | POST | Future bridge endpoint for reservation + payment-intent handoff once the commerce boundary is implemented. |
| `/api/v1/bookings/{bookingId}` | GET | Future bridge status endpoint for booking/payment state readback. |
| `/api/v1/bookings/{bookingId}/cancel` | POST | Future bridge cancellation/refund initiation endpoint. |
| `/api/v1/transactions` | GET | Future ledger read model endpoint for tenant/account-facing transaction history. |
| `/webhooks/commercial-engine` | POST | Future signed webhook ingress for checkout/provider callbacks. |

---

## 5. Deferred Event Contract

* **Outbound:** `booking.created`, `booking.confirmed`, `booking.cancelled`, `payment.intent.updated`, `payment.refund.completed`.
* **Inbound:** future commercial publication/availability signals, `agenda.action.completed` (to mark bookings as consumed), and canonical checkout payment outcomes once that authority is implemented.

---

## 6. Current Strategic Posture

* **Current posture:** this file is planning-only; current ticket-related payment evolution is anchored in `TODO-v1-ticketing-package-integration.md` and `TODO-vnext-checkout-package-integration.md`, not in a live Transaction Bridge runtime.
* **Deferred continuity:** a future bridge may expose booking/payment/ledger abstractions once Ticketing and Checkout contracts stabilize enough to support a dedicated shared authority surface.
* **Deferred continuity:** future account-facing revenue, refund, and loyalty views may consume the same canonical outcomes without forcing provider-specific knowledge into client domains.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `TRX-01` | Approved | This file is the planning surface for a future checkout/commercial bridge authority; it is not a current code-backed runtime surface. | Prevents future commerce planning from being mistaken for current implementation authority. | Sections `1`, `6` + active Ticketing/Checkout program streams |
| `TRX-02` | Approved | Any future bridge-owned booking and ledger surfaces remain projection/read-model oriented with idempotent webhook/reducer handling. | Preserves a clean future boundary without collapsing current Ticketing/Checkout ownership. | Sections `2`, `3`, `5` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-ticketing-package-integration.md` | Ticketing boundary and handoff contracts | In progress | `1.1`, `6`, `7` | Current runtime ticket-domain authority is advancing here; this module remains planning-only. |
| `TODO-vnext-checkout-package-integration.md` | Checkout semantics, webhook ownership, and reconciliation contracts | In progress | `1.1`, `5`, `7` | Checkout owns payment-event semantics; future bridge planning must remain subordinate to that authority. |
