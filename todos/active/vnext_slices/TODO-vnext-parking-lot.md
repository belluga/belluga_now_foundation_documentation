# TODO (VNext Parking Lot): Deferred Features / Avoid Losing Functionality

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owner:** Delphi  
**Purpose:** Capture features we intentionally do **not** ship in V1 so they are not lost and can be re-evaluated in future versions.

---

## A) Profile & Utilities (Deferred)

- **Wallet / Guar[APP]ari Pay** (balance + statement + cashbacks)
  - Source reference: `foundation_documentation/screens/modulo_perfil_e_utilidades.md`
  - Notes: defer until payment/ledger contracts are implemented and a stable Transaction Bridge read-model exists.
- **Purchases & Reservations history**
  - Source reference: `foundation_documentation/screens/modulo_perfil_e_utilidades.md`
  - Notes: depends on Transaction Bridge + booking lifecycles and account-profile-side fulfillment.
- **Premium plan management**
  - Source reference: `foundation_documentation/screens/modulo_perfil_e_utilidades.md`
  - Notes: depends on subscription/billing system + entitlements delivery.

---

## B) Account Profiles (Deferred / Simplify in V1)

- **Full account profile modular tabs for all profile types**
  - V1 intent: keep minimal reduced profiles; defer richer modules (store, galleries, curated content) to when Account Profile Blueprints/Capabilities are backend-driven.
- **Account Profile slug update endpoint (study)**
  - Reason: MVP treats slugs as immutable. VNext should evaluate a dedicated endpoint with audit trail, rate limits, and redirect mapping to preserve deep links.
- **Capability evaluator + enforcement (VNext)**
  - Reason: MVP treats capabilities as registry flags only. VNext should add a `CapabilityGate` (or equivalent policy service) to evaluate complex capabilities (e.g., `can_issue_invites`) against profile flags, user abilities, tenant settings, and plan limits.

## F) Account Self-Management (Deferred)

- **Account profile self-management area**
  - Reason: MVP uses tenant/admin area; account profile self-management comes next.
- **Account claim endpoint (attach user + ownership_state transition)**
  - Reason: MVP has no operator attachment. VNext should introduce a transactional claim flow that attaches a user to an unmanaged account and flips `ownership_state` to `user_owned`.
  - Tracking: `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-claim-flow.md`

## E) Invites & Metrics (Deferred)

- **Account profile invite metrics**
  - Reason: defer account_profile-facing metrics dashboards until after MVP invite flows are stable.

---

## G) Push (Deferred)

- **Web push configuration + guardrails**
  - Reason: web push needs Firebase web config, service worker setup, and VAPID key before enabling registration; defer until web stack is ready.
- **Push debug logging flag**
  - Reason: keep logs for validation now, but add a toggle to disable in production.
- **Web registration guard when config is missing**
  - Reason: avoid noisy Firebase failures on web when service worker/VAPID are not configured.

---

## C) Favorites (Deferred Enhancements)

- **Backend-persistent favorites**
  - V1 intent: mock behavior can reset on load; backend becomes source of truth later.

---

## D) Map (Deferred Enhancements)

- **Subcategories taxonomy**
  - V1 intent: keep coarse POI categories and use tags for richer filtering.
  - Defer expanding `CityPoiCategory` unless validated by UX demand.
- **Sponsors POIs**
  - Reason: sponsors may require multi-location entities and/or moving POIs; defer until the model supports this cleanly.
