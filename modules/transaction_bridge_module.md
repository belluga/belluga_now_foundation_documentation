# Documentation: Transaction Bridge Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Transaction Bridge module (MOD-305) connects tenant experiences to the external Commercial Engine. It orchestrates booking intents, deposits, payments, refunds, and loyalty credits while shielding the Flutter app from the complexity of multi-instrument pricing. The bridge persists lightweight booking projections and webhooks so we can continue operating during mock phases without a live payment processor.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/partner_catalog_and_offer_module.md`
  - `foundation_documentation/modules/task_and_reminder_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`

---

## 2. Responsibilities

1. **Booking Lifecycle Management:** Translate offering reservations from the Account Profile Catalog or Map module into `booking_reservations` documents, assign status transitions, and keep agenda nodes synchronized.
2. **Payment Intent Mediation:** Create payment intents through the Commercial Engine, store the resulting `intent_reference`, and expose simplified status flags to clients (`pending`, `authorized`, `captured`, `failed`).
3. **Transaction Ledger Projection:** Maintain a tenant-scoped ledger summarizing every financial event so UI surfaces (profile balances, booking history) can query a single read model without touching external systems.
4. **Webhooks & Retry Policies:** Handle webhook callbacks (`payment.succeeded`, `refund.created`) with idempotent processors and exponential backoff queues.

---

## 3. Data Structures

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

## 4. Interfaces

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/bookings` | POST | Creates a reservation and initializes a payment intent. |
| `/api/v1/bookings/{bookingId}` | GET | Returns booking status, payment state, and action descriptors. |
| `/api/v1/bookings/{bookingId}/cancel` | POST | Cancels a pending booking and issues refunds when applicable. |
| `/api/v1/transactions` | GET | Lists ledger entries filtered by date or account profile. |
| `/webhooks/commercial-engine` | POST | Receives signed webhook callbacks from the external Commercial Engine. |

---

## 5. Event Contract

* **Outbound:** `booking.created`, `booking.confirmed`, `booking.cancelled`, `payment.intent.updated`, `payment.refund.completed`.
* **Inbound:** `offer.published` (to validate pricing), `agenda.action.completed` (to mark bookings as consumed), `commercial.payment.succeeded` (webhook).

---

## 6. Roadmap Considerations

* **Short Term (Mocks):** Use deterministic mock payment responses and store fake intent IDs while still generating ledger entries.
* **Launch:** Integrate with the landlord-hosted Commercial Engine using service-to-service auth; enable multi-currency pricing.
* **Future:** Extend ledger with loyalty credits and offline settlement flows for account profiles without real-time payment capabilities.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `TRX-01` | Approved | Transaction Bridge is the boundary to external checkout/commercial engines. | Isolates payment complexity from tenant app/domain modules. | Sections `1`, `2`, `4` |
| `TRX-02` | Approved | Booking and ledger are projection/read models with idempotent webhook handling. | Supports resilient operations under retries and delayed callbacks. | Sections `2`, `3`, `5` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-ticketing-package-integration.md` | Ticketing/checkout boundary and handoff contracts | In progress | `2`, `4`, `7` | Defines how ticketing builds payloads for checkout/bridge. |
