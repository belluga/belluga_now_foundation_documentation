# TODO (V1): Web-to-App Policy + Progressive Profiling

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Product/Flutter) + Backend Team + Web Team + Data Team  
**Goal:** Enforce web as promotional read-only and move invite conversion to the app with progressive profiling (anonymous-first + auth hard gate).

---

## References
- `foundation_documentation/system_roadmap.md` (Web-to-App Promotion Policy section)
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md` (historical baseline; contract superseded by this TODO decisions)
- `foundation_documentation/todos/active/TODO-vnext-parking-lot.md` (check-in physical feature delivery)

---

## A) V1 Policy Baseline (Revised)

### A1) Web (Unauthenticated): 100% showcase/read-only
- [x] ✅ Event landing remains read-only (title, date/time, venue, artists, hero media).
- [x] ✅ Invite landing remains read-only preview (`/invite?code=...`).
- [ ] ⚪ Remove invite accept/decline interaction from web surfaces.
- [ ] ⚪ Replace web invite interaction CTA with promotion-only CTA: `Baixe o App para Confirmar`.
- [ ] ⚪ Web must not call `POST /api/v1/anonymous/identities` for invite conversion actions.
- [x] ✅ Map browsing on web remains read-only (no favorites, no trust actions).

### A2) Web (Authenticated): workspace-only
- [ ] ⚪ Account Profile Workspace: event creation + management.
- [ ] ⚪ Account Profile Workspace: memberships/team management (post-MVP).
- [ ] ⚪ Account Profile Workspace: invite metrics dashboards (post-MVP).

### A3) App (Flutter): progressive profiling conversion surface
- [ ] ⚪ Deferred deep link must preserve `code` across install and first open (critical path).
- [ ] ⚪ First open with invite `code` must render the correct invite card directly.
- [ ] ⚪ App creates/resumes anonymous identity (device-bound) for anonymous invite acceptance.
- [ ] ⚪ Anonymous user can accept invite from invite card (`Bora!`) without login/signup.
- [ ] ⚪ After anonymous acceptance, app keeps feed/map navigation available in read-only mode.

### A4) V1 hard-gate actions (Auth Wall required)
- [ ] ⚪ Favorite actions (artists, venues, events).
- [ ] ⚪ Send invites.
- [ ] ⚪ Presence confirmation/check-in action requires authenticated identity.
- [ ] ⚪ Physical check-in feature stays deferred to VNext (rule remains gated in V1).

Rationale: maximize top-of-funnel conversion with lowest possible friction while preserving trust actions behind authenticated identity.

---

## B) Team Implementation Tracks

### B1) Web Team (Showcase + Promotion)
- [ ] ⚪ Remove web invite acceptance flow and any web mutation path tied to invite conversion.
- [ ] ⚪ Keep invite landing preview-only and promote app install/open with `code` propagation.
- [ ] ⚪ Ensure install/open links preserve attribution `code` through store redirection.
- [ ] ⚪ Keep web map in read-only browsing mode.

### B2) App/Flutter Team (Progressive Profiling + Deferred Deep Linking)
- [ ] ⚪ Guarantee deferred deep link capture on first open (App Store/Play Store install path).
- [ ] ⚪ Route first-open user directly to invite flow for the resolved `code`.
- [ ] ⚪ Implement anonymous acceptance UX (`Bora!`) with no immediate auth requirement.
- [ ] ⚪ Keep post-accept anonymous navigation in read-only feed/map mode.
- [ ] ⚪ Implement Auth Wall modal interception for hard-gate actions.
- [ ] ⚪ Persist pending intent after auth wall and resume the intercepted action after signup/login when valid.

### B3) Backend Team (Anonymous Acceptance + Identity Merge)
- [ ] ⚪ Support invite acceptance originating from anonymous app identity (device-bound principal).
- [ ] ⚪ Update materialization/accept flow so app anonymous identity can reach canonical invite acceptance safely.
- [ ] ⚪ Keep anti-spam/rate-limit/fraud controls for anonymous acceptance path.
- [ ] ⚪ Implement anonymous → authenticated identity merge preserving invite attribution/history.
- [ ] ⚪ Ensure merge keeps inviter credit consistency and prevents duplicate acceptance artifacts.

---

## C) Deferred Deep Link Requirements (Critical)

- [ ] ⚪ Every external invite link must carry exactly one `code`.
- [ ] ⚪ Install path must preserve `code` until first open (deferred deep link).
- [ ] ⚪ First open must emit deterministic capture result:
  - [ ] ⚪ Captured: route to invite flow and materialize/resolve invite.
  - [ ] ⚪ Not captured: emit failure telemetry and show deterministic fallback UX (never blank state).
- [ ] ⚪ App links/universal links contract must include invite routes (`/invite*`, `/convites*`) and remain validated by tests.

---

## D) Tracking & KPI (Inverted Funnel)

### D1) Funnel stages (V1)
- [ ] ⚪ Landing
- [ ] ⚪ App Install
- [ ] ⚪ Deferred Deep Link Captured
- [ ] ⚪ Anonymous Accept (Swipe)
- [ ] ⚪ Auth Wall Triggered
- [ ] ⚪ Signup Completed

### D2) Required events
- [ ] ⚪ `web_invite_landing_opened`
- [ ] ⚪ `web_open_app_clicked`
- [ ] ⚪ `web_install_clicked`
- [ ] ⚪ `app_deferred_deep_link_captured` (properties: `code`, `tenant_id`, `event_id?`)
- [ ] ⚪ `app_deferred_deep_link_capture_failed` (properties: `failure_reason`, `store_channel?`)
- [ ] ⚪ `app_anonymous_invite_accepted` (properties: `code`, `event_id`, `inviter_kind`, `inviter_id`)
- [ ] ⚪ `app_auth_wall_triggered` (properties: `action_type=favorite|send_invite|presence`)
- [ ] ⚪ `app_signup_completed` (properties: `source=auth_wall|direct`)

### D3) KPI set
- [ ] ⚪ Landing → Install rate
- [ ] ⚪ Install → Deferred Deep Link Captured rate
- [ ] ⚪ Deferred Deep Link Captured → Anonymous Accept rate
- [ ] ⚪ Anonymous Accept → Auth Wall Triggered rate
- [ ] ⚪ Auth Wall Triggered → Signup Completed rate

---

## E) Guardrails

- [ ] ⚪ Web invite surfaces are strictly read-only in V1 (no web acceptance mutation).
- [ ] ⚪ Web does not mint anonymous identities for invite conversion.
- [ ] ⚪ Anonymous acceptance is app-only and device-bound.
- [ ] ⚪ Trust actions always require authenticated identity via Auth Wall.
- [ ] ⚪ Physical check-in stays in VNext delivery scope.
- [ ] ⚪ Any expansion of web conversion behavior requires explicit contract update and roadmap/TODO sync.

---

## F) Definition of Done

- [ ] ⚪ Web invite landing has promotion-only CTA and no accept path.
- [ ] ⚪ Deferred deep link is captured on first open and resolves to the intended invite flow.
- [ ] ⚪ Anonymous app acceptance works end-to-end with inviter attribution preserved.
- [ ] ⚪ Auth Wall is triggered for all V1 hard-gate actions.
- [ ] ⚪ Identity merge preserves anonymous accepted-invite history after signup/login.
- [ ] ⚪ Tracking events and KPI funnel are emitted without double-counting.

---

## G) Validation

- [ ] ⚪ Web manual: invite landing renders read-only + install/open CTA with `code` propagation.
- [ ] ⚪ App manual: install via invite link -> first open -> invite card -> anonymous accept -> navigate feed/map -> hard-gate action triggers auth wall.
- [ ] ⚪ Backend automated: anonymous accept path, attribution persistence, merge semantics, anti-spam/rate-limit.
- [ ] ⚪ Flutter automated: deferred deep link capture/resume, auth wall interception/resume, invite flow no-blank-state fallback.
- [ ] ⚪ Data validation: event stream integrity for inverted funnel and deduplication checks.
