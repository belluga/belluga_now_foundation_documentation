# TODO (VNext Parking Lot): Deferred Features / Avoid Losing Functionality

**Role note (2026-04-18):** this file is residual idea capture only. It is not a primary owner when a dedicated active TODO already exists for the same deferred program boundary.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owner:** Delphi  
**Purpose:** Capture features we intentionally do **not** ship in V1 so they are not lost and can be re-evaluated in future versions.

**Dedup note (2026-04-17):** once a deferred item receives its own active TODO, this file should stop acting as a second owner. Keep only residual ideas here or short cross-references when a dedicated lane already exists.

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
- Dedicated active owner for claim-flow work: `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`
  - Do not duplicate implementation tracking here.

## E) Invites & Metrics (Deferred)

- Authenticated workspace invite-metrics dashboards now belong to `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`.
  - Keep this parking-lot section only for broader non-workspace invite/metrics ideas if they later diverge from workspace scope.
- **Receiver-side invite-volume limits (`max_pending_invites_per_invitee`, `max_invites_to_same_invitee_per_30d`)**
  - Reason: MVP keeps only `max_invites_per_day_per_user_actor` for invite-send quotas; reintroduce receiver anti-spam limits in VNext with dedicated counter/index strategy.
- **Event/account invite-send caps (`max_invites_per_event_per_inviter`, `max_invites_per_day_per_account`)**
  - Reason: deferred to VNext to keep MVP limit policy minimal and avoid premature quota complexity.
- **Invite feed cursor pagination (replace deep `skip/limit` pages)**
  - Reason: MVP stays page-based by contract; VNext should add cursor pagination for better deep-scroll performance while keeping compatibility.

---

## G) Push (Deferred)

- **Web push configuration + guardrails**
  - Reason: web push needs Firebase web config, service worker setup, and VAPID key before enabling registration; defer until web stack is ready.
- **Push debug logging flag**
  - Reason: keep logs for validation now, but add a toggle to disable in production.
- **Web registration guard when config is missing**
  - Reason: avoid noisy Firebase failures on web when service worker/VAPID are not configured.

---

## H) DevOps / Deploy Safety (VNext)

- Dedicated active owner for deploy backup/snapshot safety: `foundation_documentation/todos/active/vnext/TODO-vnext-deploy-pre-migration-backup.md`
- Dedicated active owner for GitHub Actions runtime upgrade: `foundation_documentation/todos/active/vnext/TODO-vnext-github-actions-runtime-version-upgrade-no-behavior-change.md`
- Historical resolved item: tenant-context queue hotfix moved to `foundation_documentation/todos/completed/TODO-v1-ticketing-queue-tenant-context-hotfix.md`

## I) Test Reliability Hardening (VNext)

- Dedicated active owner for test hardening: `foundation_documentation/todos/active/vnext/TODO-vnext-test-hardening-program.md`

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

---

## J) Web-to-App Policy Carryover (Deferred from V1)

- **Authenticated account workspace delivery (event management, memberships/team management, invite metrics dashboards)**
  - Source reference: `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` (A2).
  - Tracking: `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
  - Reason: V1 keeps web unauthenticated surfaces as showcase/read-only and defers the full authenticated workspace to the dedicated post-MVP workspace lane.
- **Physical check-in + `action_type=check_in` auth-wall telemetry**
  - Source references: `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` (A4, D2) and `foundation_documentation/todos/active/vnext/TODO-vnext-event-checkin.md`.
  - Reason: business rule remains auth-required in V1, but feature delivery and dedicated interception telemetry become VNext scope.
