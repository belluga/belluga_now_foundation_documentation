# TODO (V1): First Release Delivery Plan (Orchestrator)

**Superseded sequencing note (2026-04-16):** this file remains a historical orchestrator for the earlier MVP/Pre-MVP framing, but it is no longer the active release authority. Use `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md` for the current Android-first publication milestone.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owner:** Delphi + Backend Team  
**Goal:** Ship the first tenant-facing version focused on Events + Invites + Favorites (Artists + Venues) + Map (with Beaches), with web invite landing as promotion-only and conversion happening in app via Android-first deferred deep linking + progressive profiling (iOS deferred capture in VNext).

---

## References
- Invites contract + limits: `foundation_documentation/modules/invite_and_social_loop_module.md`
- Account Profile admin/workspace module (draft): `foundation_documentation/modules/account_workspace_module.md`
- Map/POI architecture: `foundation_documentation/modules/map_poi_module.md`
- MVP scope gate (decisions): `foundation_documentation/todos/completed/TODO-mvp-scope-definition.md`
- Roadmap tracking: `foundation_documentation/system_roadmap.md`
- Deferred features: `foundation_documentation/todos/active/TODO-vnext-parking-lot.md`
- Flutter test foundation (baseline): `foundation_documentation/todos/completed/TODO-v1-flutter-test-foundation.md`
- Invites implementation slice: `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`
- Telemetry + push slice: `foundation_documentation/todos/completed/TODO-v1-telemetry-and-push.md`
- Map slice: `foundation_documentation/todos/completed/TODO-v1-map.md`
- Events/Agenda slice: `foundation_documentation/todos/completed/TODO-v1-events-and-agenda.md`
- Account Profile discovery UI slice: `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- Tenant/admin area slice: `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
- Web-to-app conversion gate slice: `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`

## 0) Scope Boundaries (V1)

### In scope
These are scope descriptors (not tasks).
- Events browsing + event detail
- Invites: send, receive, accept/decline, confirm presence
- Invite crediting selection (“Accept invite from…”, no default)
- Web: invite landing is read-only + app promotion CTA; invite acceptance happens in app (anonymous-first progressive profiling)
- Map POIs with categories: `Culture`, `Restaurant`, `Beach`, `Nature`, `Historic` + dynamic `Events`
- StaticAssets (landlord-managed) are POI-enabled sources; Unmanaged accounts are supported for account profile creation.
- Favorites: Artists + Venues (favorites remain surfaced in Home)
- Venue profile pages (reduced profile)
- Push notifications (V1 baseline)
- Tracking / product analytics (Mixpanel) integration (V1 baseline)
- Flutter test foundation (unit + contract + minimal widget/integration + network contract checks)

### Out of scope (tracked in `foundation_documentation/todos/active/TODO-vnext-parking-lot.md`)
- Wallet / purchases / premium
- Persistent favorites (backend later; mock can reset on load)
- Full account profile modules/store for all profile types
- Account profile metrics (deferred; account_profile invites are allowed in MVP via admin-assigned operators)

---

## 0.1) Orchestration (How We Sequence Work)

### Primary sequencing principle
- Freeze behavior with tests first, then implement features: `foundation_documentation/todos/completed/TODO-v1-flutter-test-foundation.md` is the precondition to reduce regressions and avoid “workaround tests”.
- Scope gate: before executing MVP feature slices, finalize decisions in `foundation_documentation/todos/completed/TODO-mvp-scope-definition.md` to prevent churn and rework.

### Dependency map (high level)
- Core loop: Invites + Agenda → unlock Telemetry/Push.
- Web-to-app conversion depends on reliable Android deferred deep-link capture, share-code propagation, tenant-dynamic store/open routing (Android+iOS), and app-side anonymous invite acceptance contract.
- Map depends on stable event detail routing + time-window settings for event POIs.
- Tenant/admin area depends on landlord/admin operators for MVP; memberships are deferred (credited acceptance semantics for invites remain app-side).

---

## 0.2) Milestones (Each Must Be Manually Testable)

### M0 — Flutter test foundation (precondition)
- TODO: `foundation_documentation/todos/completed/TODO-v1-flutter-test-foundation.md`
- Gate: `fvm flutter test` green (including network contract tests per TODO)
- Manual: compile and run app; smoke routes still open without crashes

### M1 — Core loop contracts + mock fidelity
- TODOs: `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`, `foundation_documentation/todos/completed/TODO-v1-events-and-agenda.md` (refinement + mock-first)
- Gate: DTO/fixture contract tests updated; no mock drift from docs
- Manual: browse events → open event detail → start invite flow (mock-backed)

### M2 — Web read-only promotion + app conversion (Android deferred deep link)
- TODOs: `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` + `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`
- Gate: web invite surfaces are read-only, Android deferred deep-link capture works on first open, iOS fallback behavior is explicit (deferred capture in VNext), and app anonymous accept flow is functional with auth wall for trust actions
- Manual: open tenant subdomain → open invite landing → click app CTA → install/open app via link → invite card opens with correct code → anonymous accept succeeds

### M3 — Telemetry + push baseline
- TODO: `foundation_documentation/todos/completed/TODO-v1-telemetry-and-push.md`
- Gate: analytics taxonomy + push routing validated (no double-counting)
- Manual: invite received routes correctly; Mixpanel shows funnel events

### M4 — Map V1
- TODO: `foundation_documentation/todos/completed/TODO-v1-map.md`
- Gate: map payload contract tests; stacking behavior stable
- Manual: beaches/nature visible; event POIs show; stacks show `+N`

### M5 — Artist favorites/profile V1
- TODO: `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- Gate: favorites gating tests (artist-only) remain green
- Manual: favorites strip works; artist profile uses reduced tabs

### M6 — Tenant/Admin area V1 minimum
- TODO: `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
- Gate: tenant/admin authorization enforced
- Manual: tenant admin can manage accounts, assets, events, and branding

---

## 1) Domain/Contract Decisions (Must Hold)

### 1.1 Invites (anti-gaming + monetization-safe)
- Uniqueness: forbid duplicate invite key `(tenant_id, event_id, receiver_user_id, inviter_principal.kind, inviter_principal.id)` → respond `already_invited`.
- Credited acceptance: exactly one `credited_acceptance=true` per `(receiver_user_id, event_id)`; others become `closed_duplicate`.
- No default inviter selection in UI; user must pick who to credit before accepting.
- Inviter principal is union `{kind:user|account_profile, id}`; account_profile invites are allowed in MVP (admin-assigned).

### 1.2 Canonical IDs
- Events and artists always reference stable `account_profile_id` (create profiles upfront with Tiny Free when needed).
- Never rely on name-only references except as display fallbacks.

### 1.3 Metrics access boundary
- Invite metrics **data capture is required in MVP**; dashboards/account-profile-facing surfaces are deferred.

---

## 2) Backend Deliverables

### 2.1 Invite Settings (backend-owned + enforced)
- [ ] ⚪ Implement endpoint: `GET /api/v1/invites/settings`
- [ ] ⚪ Enforce limits on invite creation and return:
  - [ ] ⚪ `429` with structured payload when over quota/rate-limited
  - [ ] ⚪ reset metadata (`resets_at`) and “which limit” identifier
- [ ] ⚪ Make settings tenant-configurable (no app release required to tune)

Suggested defaults (override per tenant + plan):
- `max_invites_per_day_per_user_actor = 100`
- suppression: per-profile blocklist + per-user opt-out
- event/account/receiver invite-send limits are deferred to VNext (`max_invites_per_event_per_inviter`, `max_invites_per_day_per_account_profile`, `max_pending_invites_per_invitee`, `max_invites_to_same_invitee_per_30d`)

### 2.2 Account memberships (Deferred)
- [ ] ⚪ Implement account memberships post‑MVP (draft spec in `foundation_documentation/modules/account_workspace_module.md`)

### 2.3 Event invite metrics (account-profile-facing dashboards, post‑MVP)
- (Deferred to VNext)

### 2.4 Push notifications (baseline)
- [x] ✅ Production‑Ready Implement device registration endpoint (exact naming TBD):
  - [x] ✅ Production‑Ready `POST /api/v1/push/register` with `{ device_id, platform, push_token }`
  - [x] ✅ Production‑Ready Optional `DELETE /api/v1/push/unregister`
- [ ] ⚪ Send notifications (minimum):
  - [ ] ⚪ New invite received
  - [ ] ⚪ Invite status change (accepted/declined) when relevant
  - [ ] ⚪ Event reminder for confirmed attendance (or delegate to Task/Reminder service)
- [x] ✅ Production‑Ready Make notification policies tenant-configurable (no app release required)

### 2.5 Tracking / Analytics (Mixpanel)
- [x] ✅ Production‑Ready Provide a stable event taxonomy and required properties (tenant-aware)
- [x] ✅ Production‑Ready If backend emits events too, align naming/ownership to avoid double-counting
- [x] ✅ Production‑Ready Emit telemetry for implemented auth/profile/account endpoints (Mixpanel + webhook) per `TODO-v1-telemetry-and-push.md`
- [x] ✅ Production‑Ready Update tenant telemetry settings (Mixpanel + webhook) once admin settings UI is ready (deferred)

### 2.6 Runtime / Workers (MVP)
- [x] ✅ Production‑Ready Add a dedicated Docker Compose worker service for Laravel queue processing (uses `php artisan queue:work` with sane retries/timeouts).
- [x] ✅ Production‑Ready Ensure worker uses the same code volume and environment as `app`, and respects host UID/GID ownership.
- [x] ✅ Production‑Ready Document worker usage in README (start/stop notes + expected queue connection).
- [x] ✅ Production‑Ready Add a scheduler service for `php artisan schedule:run` (required for `PublishScheduledEventsJob` in `routes/console.php`).

---

## 3) Flutter Deliverables

### 3.1 Tenant: Invites UX
- [ ] ⚪ Replace “who invited me” modal with:
  - [ ] ⚪ “Escolher convite para aceitar” → opens selector list of inviters
  - [ ] ⚪ no default; CTA disabled until selection is made
  - [ ] ⚪ accept credits selected inviter and updates UI state
- [ ] ⚪ Ensure UI shows “já convidado” when backend returns `already_invited`
- [ ] ⚪ Expose invite metrics counters (sent/accepted/confirmed) in Profile and Menu hero, wired to the correct repositories

### 3.2 Tenant: Favorites (Artists + Venues)
- [ ] ⚪ Keep favorites displayed in Home
- [ ] ⚪ Clicking an artist favorite opens the existing Partner Detail base page (Flutter naming) with reduced tabs (artist config)
- [ ] ⚪ Clicking a venue favorite opens the existing Partner Detail base page (Flutter naming) with reduced tabs (venue config)
- [ ] ⚪ Enforce “favoritable” for artists + venues only in the mock repository path until backend sends capabilities

### 3.3 Tenant: Map
- [ ] ⚪ Keep POI categories coarse; use tags for subcategories
- [ ] ⚪ Ensure Beaches are included and filterable (already present in mock POI DB)
- [ ] ⚪ Ensure dynamic Event POIs are visible and remain distinct from static POIs

### 3.5 Push notifications (baseline)
- [ ] ⚪ Register device token on startup/login and handle token rotation
- [ ] ⚪ Deep link routing (at minimum: open invite/event detail)
- [ ] ⚪ Respect tenant settings for notification categories (best-effort client gating; backend remains authoritative)

### 3.6 Tracking / Analytics (Mixpanel)
- [ ] ⚪ Initialize Mixpanel with tenant/app keys from backend bootstrap (preferred) or environment config
- [ ] ⚪ Track critical funnel events (minimum):
  - [ ] ⚪ `invite_received`, `invite_opened`, `invite_accept_selected_inviter`, `invite_accepted`, `invite_declined`
  - [ ] ⚪ `event_opened`, `event_confirmed_presence`
  - [ ] ⚪ `favorite_artist_toggled`, `map_opened`, `poi_opened`
- [ ] ⚪ Ensure every event includes: `tenant_id`, `event_id` (when applicable), `inviter_kind/id` (when applicable), `account_profile_id` (when applicable)

### 3.4 Tenant/Admin area (V1 minimum pages)
- [ ] ⚪ Tenant/Admin Home
- [ ] ⚪ Accounts management
- [ ] ⚪ Assets management (StaticAssets)
- [ ] ⚪ Events management
- [ ] ⚪ Tenant branding management (About/logo/icon/colors)
- [ ] ⚪ Plan/Limits read-only view (uses invite settings payload + account profile plan payload)

---

## 4) Acceptance Criteria (V1)

- [ ] ⚪ Invites cannot be duplicated by same inviter for same receiver+event (`already_invited`)
- [ ] ⚪ Accepting an invite requires explicit inviter selection; only one credited acceptance per receiver+event
- [ ] ⚪ Map supports the agreed categories and shows beaches + events
- [ ] ⚪ Favorites support artists + venues and route to reduced profiles
- [ ] ⚪ No Wallet/Purchases/Premium surfaces ship in V1 (tracked as deferred)
- [ ] ⚪ Push notifications work end-to-end for invite received at minimum, including deep link routing
- [ ] ⚪ Mixpanel captures the invite funnel and event funnel with consistent identifiers
- [ ] ⚪ Web stays promotion-only while app conversion follows progressive profiling (`android deferred deep link -> anonymous accept -> auth wall -> signup`), with iOS deferred capture explicitly tracked in VNext and deterministic fallback behavior.

---

## 5) Global Validation Checklist (Run Every Milestone)
- Tests: `cd flutter-app && fvm flutter test`
- Manual: `flutter run` (emulator/device) and verify the milestone’s manual checklist
- Web: verify `/environment` on root and tenant subdomain, plus fixed branding paths (`/logo-*.png`, `/icon-*.png`, `/manifest.json`, `/favicon.ico`)
- [ ] ⚪ Tenant-admin MVP gate: ensure controller + endpoint coverage matrix is wired for success + auth/permission failures (landlord, tenant, account scopes), including cross-layer contract checks.

## 6) DevOps Governance (Paid Plan)
- [ ] ⚪ Pending Enable paid GitHub plan and ensure admin access to repository settings for:
  - `belluga_now_docker`
  - `belluga_now_front` (`flutter-app`)
  - `belluga_now_backend` (`laravel-app`)
  - `belluga_now_web` (`web-app`)
- [ ] ⚪ Pending Configure Branch Protection/Rulesets for `stage` and `main` in all repositories:
  - Require PR before merge
  - Require status checks before merge
  - Include administrators
  - Disallow force push and branch deletion
- [ ] ⚪ Pending Mark required checks in each repository:
  - Orchestrator: `Lane Promotion Policy`, `Preflight Validation`
  - Flutter: `Lane Promotion Policy`, `Validate and Build Web`
  - Laravel: `Lane Promotion Policy`, `test`
  - Web: `Lane Promotion Policy`, `navigation`
- [ ] ⚪ Pending After configuration is done, run a full validation round with Delphi:
  - Attempt direct push to `stage` (must be blocked by GitHub)
  - Open invalid promotion PR (must fail lane policy)
  - Open valid promotion PR (must pass required checks)
