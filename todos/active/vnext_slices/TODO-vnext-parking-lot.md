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

- [ ] ⚪ Pending **Pre-migration backup/snapshot on stage/main deploy**
  - Reason: deploy now runs landlord+tenant migrations automatically; we should capture an Atlas snapshot (or `mongodump` when self-hosted) before applying migrations, mainly for `main`.
  - Tracking: `foundation_documentation/todos/active/vnext_slices/TODO-vnext-deploy-pre-migration-backup.md`
- [x] ✅ Production‑Ready **Queue tenant-context hotfix promoted from VNext to immediate V1 lane**
  - Reason: this is an active runtime reliability defect (tenant-aware queue failures in production), so it moved out of VNext parking lot into immediate execution.
  - Tracking: `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-queue-tenant-context-hotfix.md`
- [ ] ⚪ Pending **GitHub Actions migration to Node 24 runtime**
  - Reason: workflow currently emits deprecation warnings for Node 20 JavaScript actions (for example `actions/checkout@v4` runtime transition policy). VNext should update/pin all affected actions and validate runner compatibility.

## I) Test Reliability Hardening (VNext)

- [ ] ⚪ Pending **Full test hardening program (Laravel + Flutter + Web + CI)**
  - Reason: strengthen determinism, remove bypass patterns, and lock compatibility behavior with stricter contract and failure-path assertions.
  - Tracking: `foundation_documentation/todos/active/vnext_slices/TODO-vnext-test-hardening-program.md`

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

- **Authenticated account workspace: event creation + management**
  - Source reference: `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md` (A2).
  - Reason: V1 keeps web unauthenticated surfaces as showcase/read-only and does not expand authenticated workspace scope.
- **Authenticated account workspace: memberships/team management**
  - Source reference: `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md` (A2).
  - Reason: defer operational account-team tooling until post-MVP workspace hardening.
- **Authenticated account workspace: invite metrics dashboards**
  - Source reference: `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md` (A2).
  - Tracking note: already represented in section `E) Invites & Metrics` as **Account profile invite metrics**; keep this entry only as cross-reference from the V1 policy TODO.
- **Physical check-in + `action_type=check_in` auth-wall telemetry**
  - Source references: `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md` (A4, D2) and `foundation_documentation/todos/active/vnext_slices/TODO-vnext-event-checkin.md`.
  - Reason: business rule remains auth-required in V1, but feature delivery and dedicated interception telemetry become VNext scope.
