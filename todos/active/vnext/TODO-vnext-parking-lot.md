# TODO (VNext Parking Lot): Deferred Features / Avoid Losing Functionality

**Role note (2026-04-18):** this file is residual idea capture only. It is not a primary owner when a dedicated active TODO already exists for the same deferred program boundary.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owner:** Delphi  
**Purpose:** Capture features we intentionally do **not** ship in V1 so they are not lost and can be re-evaluated in future versions.

**Dedup note (2026-04-17):** once a deferred item receives its own active TODO, this file should stop acting as a second owner. Keep only residual ideas here or short cross-references when a dedicated lane already exists.

**Authority boundary note (2026-04-18):** `foundation_documentation/todos/completed/TODO-vnext-foundation-authority-and-branch-reconciliation.md` is now the historical documentation-reconciliation umbrella record. Deferred product-feature ownership must still remain outside that closed reconciliation slice.

**Triage note (2026-04-18):**
- `Absorbed by active owner`: keep only the cross-reference here.
- `Partial fit`: an adjacent TODO covers part of the concern, but no current TODO owns the full boundary yet.
- `Keep residual`: no correct active owner exists yet; keep the item here until a dedicated TODO is opened.

---

## A) Profile & Utilities (Deferred)

- **Wallet / Guar[APP]ari Pay** (balance + statement + cashbacks)
  - Triage: `Keep residual`
  - Notes: defer until payment/ledger contracts are implemented and a stable Transaction Bridge read-model exists. `TODO-vnext-checkout-package-integration.md` is only adjacent dependency context; it does not own wallet/balance/cashback semantics.
- **Purchases & Reservations history**
  - Triage: `Keep residual`
  - Notes: depends on Transaction Bridge + booking lifecycles and account-profile-side fulfillment. No current active TODO owns the user-facing history surface.
- **Premium plan management**
  - Triage: `Keep residual`
  - Notes: depends on subscription/billing system + entitlements delivery. No current subscription/billing owner exists in `vnext`.

---

## B) Account Profiles (Deferred / Simplify in V1)

- **Full account profile modular tabs for all profile types**
  - Triage: `Keep residual`
  - Notes: V1 keeps minimal reduced profiles. Future richer profile surfaces should open as capability-specific or feature-specific TODOs, not under a generic profile-type expansion owner.
- **Account Profile slug update endpoint (study)**
  - Triage: `Partial fit`
  - Adjacent owner: `foundation_documentation/todos/active/vnext/TODO-vnext-account-profile-public-path-strategy.md`
  - Code scrutiny (2026-04-18): a basic slug-mutation path is already implemented in code. Laravel exposes generic `PATCH /account_profiles/{account_profile_id}` update support that accepts `slug`, persists it, and rejects duplicates; Flutter tenant-admin edit UI also exposes the profile slug field.
  - Remaining gap: the write-contract / audit-trail / redirect policy for allowing slug changes still has no dedicated implementation slice, and the unresolved part is now the public-path/permalink strategy rather than a presumed projection-resync mechanism.
- **Capability evaluator + enforcement (VNext)**
  - Triage: `Keep residual`
  - Code scrutiny (2026-04-18): capabilities are already real runtime flags in code, not only documentation. Backend services enforce some capability-dependent behavior such as `is_poi_enabled`, and Flutter uses registry capabilities to gate visible sections and favoritable behavior.
  - Remaining gap: no central runtime `CapabilityGate` / policy service was found, and there is no evidence of capability evaluation against user abilities, tenant settings, or plan limits. The current implementation is still mostly static flag-based gating, but this is no longer owned by a generic profile-type expansion TODO.

## F) Account Self-Management (Deferred)

- Authenticated account/profile self-management area is now **absorbed by active owner**: `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
  - Adjacent transition owner: `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`
  - Do not duplicate implementation tracking here.

## E) Invites & Metrics (Deferred)

- Authenticated workspace invite-metrics dashboards now belong to `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`.
  - Keep this parking-lot section only for broader non-workspace invite/metrics ideas if they later diverge from workspace scope.
- **Receiver-side invite-volume limits (`max_pending_invites_per_invitee`, `max_invites_to_same_invitee_per_30d`)**
  - Triage: `Keep residual`
  - Reason: MVP keeps only `max_invites_per_day_per_user_actor` for invite-send quotas; reintroduce receiver anti-spam limits in VNext with dedicated counter/index strategy. No current TODO owns this invite-policy boundary.
- **Event/account invite-send caps (`max_invites_per_event_per_inviter`, `max_invites_per_day_per_account`)**
  - Triage: `Keep residual`
  - Reason: deferred to VNext to keep MVP limit policy minimal and avoid premature quota complexity. No current TODO owns this quota-policy boundary.
- **Invite feed cursor pagination (replace deep `skip/limit` pages)**
  - Triage: `Keep residual`
  - Reason: MVP stays page-based by contract; VNext should add cursor pagination for better deep-scroll performance while keeping compatibility. No current active TODO owns the invite-feed pagination contract.

---

## G) Push (Deferred)

- **Web push configuration + guardrails**
  - Triage: `Keep residual`
  - Reason: web push needs Firebase web config, service worker setup, and VAPID key before enabling registration; defer until a dedicated web-push delivery slice exists.
- **Push debug logging flag**
  - Triage: `Keep residual`
  - Reason: keep logs for validation now, but add a toggle to disable in production. The completed `TODO-vnext-telemetry-architecture-review.md` remained adjacent only; it never owned push runtime/config delivery.
- **Web registration guard when config is missing**
  - Triage: `Keep residual`
  - Reason: avoid noisy Firebase failures on web when service worker/VAPID are not configured. No current active TODO owns the web-push registration boundary.

---

## H) DevOps / Deploy Safety (VNext)

- Dedicated active owner for deploy backup/snapshot safety: `foundation_documentation/todos/active/vnext/TODO-vnext-deploy-pre-migration-backup.md`
- Dedicated active owner for GitHub Actions runtime upgrade: `foundation_documentation/todos/active/vnext/TODO-vnext-github-actions-runtime-version-upgrade-no-behavior-change.md`
- Historical resolved item: tenant-context queue hotfix moved to `foundation_documentation/todos/completed/TODO-v1-ticketing-queue-tenant-context-hotfix.md`

## I) Test Reliability Hardening

- Dedicated promoted owner for the current release-facing hardening slice: `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-critical-journey-regression-gates.md`

---

## C) Favorites (Deferred Enhancements)

- **Backend-persistent favorites**
  - Triage: `Keep residual`
  - V1 intent: mock behavior can reset on load; backend becomes source of truth later. Current release-facing favorites work is separate, but no post-MVP owner TODO exists here yet for the durable backend source-of-truth.

---

## D) Map (Deferred Enhancements)

- **Subcategories taxonomy**
  - Triage: `Keep residual`
  - V1 intent: keep coarse POI categories and use tags for richer filtering.
  - Defer expanding `CityPoiCategory` unless validated by UX demand. Do not force this into `TODO-v1-map-visuals.md` or `TODO-vnext-map-marker-icon-catalog-expansion.md`.
- **Sponsors POIs**
  - Triage: `Keep residual`
  - Reason: sponsors may require multi-location entities and/or moving POIs; defer until the model supports this cleanly. No active TODO currently owns this richer POI/entity-model boundary.

---

## J) Web-to-App Policy Carryover (Deferred from V1)

- **Authenticated account workspace delivery (event management, memberships/team management, invite metrics dashboards)**
  - Source reference: `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` (A2).
  - Tracking: `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
  - Reason: V1 keeps web unauthenticated surfaces as showcase/read-only and defers the full authenticated workspace to the dedicated post-MVP workspace lane.
- **Physical check-in + `action_type=check_in` auth-wall telemetry**
  - Source references: `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` (A4, D2) and `foundation_documentation/todos/active/vnext/TODO-vnext-event-checkin.md`.
  - Reason: business rule remains auth-required in V1, but feature delivery and dedicated interception telemetry become VNext scope.
