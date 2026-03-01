# Documentation: Invite & Social Loop Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Invite & Social Loop module (MOD-302) governs the tenant app virality engine. It manages invite issuance, referral graph analytics, friend resume projections, and gamified progression that feeds both the tenant app and the account profile workspace. The module is built to operate with mocked persistence today while remaining API-compatible with a future backend microservice.
**MVP scope:** only **Account User** invite issuance is implemented; account-plan quotas/monetization are deferred to post‑MVP.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module contract references:
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/task_and_reminder_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/transaction_bridge_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`

---

## 2. Design Principles

1. **Graph-Native Modeling:** Invites, referrals, and friend relationships are stored as a directed multigraph (`invite_edges`). Every edge carries immutable metadata (source account profile, campaign id, channel) so downstream scoring remains deterministic.
2. **Privacy-Respecting Exposure:** Contact metadata is normalized into `friend_resumes` that only include the data points explicitly allowed by each user (display name, avatar, teaser label). The module never leaks raw address book details to other modules.
3. **Progressive Disclosure:** Invite payloads include `contextual_prompts` describing why an invite matters (e.g., “3 friends are attending this gig”). Context is generated from other modules but cached locally to avoid tight coupling.
4. **Event-Driven Incentives:** Rank changes, streaks, or reward unlocks emit events consumed by the Insights Service and Tenant Home Composer. The module does not compute final leaderboards; it only updates counters and emits domain events.
5. **Quota-Aware Monetization (Post‑MVP):** Invite issuance is tied to account plans. Every invite maps to a `plan_charge_bucket`, allowing us to invoice or enforce limits according to the account’s subscription tier.
6. **Automatic Event-Scoped Security:** Invite codes inherit the lifecycle of the underlying experience; when the event expires or a receiver suppresses invitations for that event, tokens are invalidated automatically and cannot be reused.

---

## 2.1 V1 Structural Decisions (Crediting, Uniqueness, Limits)

These decisions are foundational because they prevent metric inflation, preserve monetization integrity, and unlock account-profile-facing analytics.

### A) Inviter Principal (User vs Account Profile)
Every invite is issued by an `inviter_principal`:

```json
{
  "inviter_principal": { "kind": "user|account_profile", "id": "ObjectId()" },
  "issued_by_user_id": "ObjectId() | null"
}
```

- The **recipient-facing inviter** is always the `inviter_principal`.
- When `inviter_principal.kind == "account_profile"`, `issued_by_user_id` records which user (landlord/admin or account operator) issued the invite on behalf of the profile. This supports auditing, permissions, and abuse investigations; it does not change who gets invite credit.
- **MVP constraint:** account/profile invite issuance is admin-assigned (no memberships yet); account operators are explicitly linked by landlord/tenant admins.

### A1) Audience Eligibility (User vs Account Profile)
- **User invites:** may target only imported contacts (hashed) or users already installed in the app.
- **Account Profile invites:** may target followers/favorites for broader reach; direct user targeting is also allowed as needed.
- **Share codes:** allowed for both inviter types; eligibility rules still apply (user shares only to their contacts, account profiles can share to followers/favorites audiences).

### B) Uniqueness (No Duplicate Invites From Same Inviter)
We never allow the same inviter to invite the same receiver to the same event more than once.

**Uniqueness key:**
`(tenant_id, event_id, receiver_user_id, inviter_principal.kind, inviter_principal.id)`

If a duplicate attempt occurs:
- Backend responds with `already_invited` and no record is created.
- Client surfaces “Já convidado”.

### C) Credited Acceptance (One Credited Invite Per Receiver + Event)
When a user confirms attendance via invites, exactly **one** invite becomes the credited acceptance for that `(receiver_user_id, event_id)` pair.

- UI must force explicit selection (“Aceitar convite de …” opens a selector dialog); **no default inviter selection** is applied.
- On acceptance, the selected invite transitions to `accepted` with `credited_acceptance=true`.
- All other invites for the same `(receiver_user_id, event_id)` transition to `closed_duplicate` (still queryable for audit and reporting, but not counted as accepted conversions).

### D) Backend-Owned Limits (Tenant Settings + Enforcement)
Invite limits are configured and enforced by the backend. Flutter:
- fetches settings when needed (and may cache briefly for UX),
- shows quota/limit messaging,
- relies on backend enforcement as the source of truth.

Backend must return:
- `429` when over quota/rate-limited,
- a structured payload describing which limit was exceeded and when it resets.

**Suggested healthy defaults (backend-owned; per-tenant + per-plan override):**
- `max_invites_per_event_per_inviter`: `300`
- `max_invites_per_day_per_account`: `500` (Tiny Free plan: `50–100`)
- `max_invites_per_day_per_user_actor`: `100`
- `max_pending_invites_per_invitee`: `20`
- `max_invites_to_same_invitee_per_30d` (any events): `10`
- Suppression: per-account blocklist + per-user opt-out

## 3. Data Model

### 3.1 `invite_edges`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "sender_user_id": "ObjectId()",
  "receiver_user_id": "ObjectId()",
  "invite_code": "String",
  "status": "String",
  "attendance_status": "String",
  "source_account_profile_id": "ObjectId()",
  "campaign_id": "String",
  "channel": "String",
  "channel_payload": {},
  "plan_charge_bucket": "String",
  "contextual_prompts": [
    { "type": "String", "text": "String", "cta": "String" }
  ],
  "expires_at": "Date",
  "auto_expire_at": "Date",
  "created_at": "Date",
  "updated_at": "Date"
}
```
`status` ∈ {`pending`, `viewed`, `accepted`, `declined`, `closed_duplicate`, `expired`, `snoozed`, `suppressed`}. `attendance_status` ∈ {`unknown`, `confirmed`, `no_show`}. `unknown` is the default and represents “attendance not yet reported”. `channel` includes `whatsapp`, `in_app`, `qr`, `link`. `auto_expire_at` is derived from the related event/offer end time so invitations automatically close when the underlying experience has passed. `plan_charge_bucket` ties each invite to the account plan quota bucket used by billing (e.g., `core`, `premium_boost`), enabling per-plan limits.

**V1 additional fields (required):**
```json
{
  "event_id": "ObjectId()",
  "inviter_principal": { "kind": "user|account_profile", "id": "ObjectId()" },
  "account_profile_id": "ObjectId() | null",
  "issued_by_user_id": "ObjectId() | null",
  "credited_acceptance": "Boolean"
}
```
`account_profile_id` is required when `inviter_principal.kind = account_profile` and must match the profile issuing the invite.

### 3.2 `invite_actions`
Captures all user actions performed on an invite entry.
```json
{
  "_id": "ObjectId()",
  "invite_id": "ObjectId()",
  "user_id": "ObjectId()",
  "action": "String",
  "metadata": {},
  "occurred_at": "Date"
}
```

### 3.3 `friend_resumes`
Authoritative resume objects consumed by Flutter domain models.
```json
{
  "_id": "ObjectId()",
  "user_id": "ObjectId()",
  "friend_display_name": "String",
  "avatar_url": "String",
  "match_label": "String",
  "highlight_flags": ["String"],
  "updated_at": "Date"
}
```

### 3.4 Quotas & Throttling Snapshots
To enforce both anti-spam policies and account plan limits, the module maintains supporting documents:
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "scope_type": "String",
  "scope_reference": "ObjectId()",
  "window": { "duration_minutes": "Number", "started_at": "Date" },
  "max_allowed": "Number",
  "current_count": "Number",
  "plan_charge_bucket": "String",
  "last_violation_at": "Date"
}
```
`scope_type` ∈ {`user_sender`, `account_plan`}. When `current_count >= max_allowed`, new invites are blocked and the API returns `429` with metadata describing the plan or quota that was exhausted. Violations emit `invite.rate-limited` or `invite.plan-limit-reached` events so Commercial/Account Analytics modules can track upsell opportunities.

---

## 4. APIs & Events

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/invites` | GET | Returns paginated invite feed with friend resumes, contextual prompts, quota status, and suppression flags per event. |
| `/invites/stream` | GET | Streams invite deltas for live updates (created/updated/deleted). |
| `/invites/settings` | GET | Returns backend-owned tenant settings relevant to invite quotas, anti-spam, and client UX messaging. |
| `/invites/share` | POST | Creates (or returns) an external share code that attributes installs/signups to an inviter principal for a specific event. |
| `/invites/share/{code}/accept` | POST | Accepts a share invite for the current user and emits `invite.accepted`. |
| `/contacts/import` | POST | Imports hashed contacts for friend matching and invite discovery. |

**Deferred (post-MVP) endpoints:** `/invites/share/{code}` (resolve), `/invites/share/{code}/consume`, `/invites/{inviteCode}/accept`, `/invites/{inviteCode}/resend`, `/invites/{inviteCode}/snooze`, `/invites/{inviteCode}/suppress-event`, `/invites/{inviteCode}/accept/import-contacts`, `/invites/{inviteCode}/attendance`.

### 4.3 External Share Invites (New Users Attribution)

V1 requires tracking external shares (WhatsApp/Instagram/etc.) for **new users** (install → signup → acceptance) and attributing them to the inviter.

**Eligibility rule (agreed):** anyone who can send invites can generate external share invites.
- For user inviters: authenticated user with invite permission.
- **MVP note:** account_profile invites are admin-assigned; memberships are deferred post‑MVP. When memberships land, `issued_by_user_id` must be validated against the account_profile’s membership/role permissions.

**Share code contract (server-stored, MVP minimum):**
```json
{
  "code": "String",
  "tenant_id": "ObjectId()",
  "event_id": "ObjectId()",
  "inviter_principal": { "kind": "user|account_profile", "id": "ObjectId()" },
  "issued_by_user_id": "ObjectId() | null",
  "created_at": "Date",
  "expires_at": "Date | null"
}
```

**Key requirements:**
- `code` resolves to a single inviter principal + event.
- Backend records **share visits** and **invite acceptance**; acceptance is triggered by `/invites/share/{code}/accept` (source = `share_url`).
- Backend must prevent duplicate invite issuance to the same receiver+event+inviter principal (see Uniqueness rule); the share code is attribution, not a loophole to spam.

### 4.4 Web Acceptance + Same-Event Re-Share (V1 Exception)

V1 supports a narrow web exception for invite acceptance:
- Web can accept an invite only when reached via a single invite/share `code` (invite landing).
- The credited inviter is the inviter principal bound to that code (no multi-inviter selection on web).
- After acceptance, web may allow the new attendee to **re-share only the same event** externally (WhatsApp/Instagram/etc.), subject to strict backend limits.

This enables viral growth while keeping app-only “agenda-first acceptance” as the trusted conversion surface.

#### Sanctum + Anonymous Identity Requirement

Even on web “unauthenticated” landings, the canonical API is Sanctum-validated by default:

- Web must mint or resume an anonymous identity via `POST /anonymous/identities` to obtain a Sanctum token.
- The web client then calls the same invite acceptance / share endpoints using `Authorization: Bearer <token>`.
- The backend controls what anonymous tokens may do via `tenant.anonymous_access_policy.abilities` (and TTL), and must still enforce quotas and uniqueness rules.

**Events**
* Outbound: `invite.created`, `invite.accepted`, `invite.accepted.contacts-import-triggered`, `invite.fulfillment.step-required`, `invite.fulfillment.step-completed`, `invite.attendance.confirmed`, `invite.attendance.no-show`, `invite.attendance.unconfirmed`, `invite.expired`, `invite.reward-unlocked`, `invite.rate-limited`, `invite.plan-limit-reached`, `invite.snoozed`, `invite.suppressed`.
* Inbound: `user.profile.updated` (refresh resumes), `agenda.action.completed` (to suggest invites tied to actions), `insights.rank.changed`, `task.completed` (so we can auto-unsnooze when reminders convert).
* Analytics/CRM Integration: Every fulfillment intent (`invite.fulfillment.step-required`, e.g., pay deposit, upload document) is mirrored to the Account Analytics/CRM module along with contact info so account operators can track outstanding requirements. When tasks complete, the analytics module receives `invite.fulfillment.step-completed` events (emitted by Transaction Bridge or Task & Reminder). Final conversion is measured via attendance events: `invite.attendance.confirmed` (account operator confirms presence or user checks in) and `invite.attendance.no-show`. These events tie back to account KPIs and invite reward logic.
* Task & Reminder Integration: `invite.snoozed` dispatches a `task.intent` payload `{ source_type: "invite", invite_id, reminder_type: "invite_followup" }` so MOD-306 can schedule pushes. When a user selects “Decide later,” remind them before the invite expires. As the event time approaches, the invite module emits a `task.intent` with `reminder_type: "invite_checkin"` targeting the invitee to confirm attendance or mark a no-show. This “check-in” reminder can carry deep links to the `/attendance` endpoint so users can self-report quickly, while account profile confirmations remain authoritative. When the tenant shares venue coordinates, the check-in flow can also request a passive location permission check—if the device reports being within the event geofence at the event time, the module sets `attendance_status = confirmed_geo` and emits `invite.attendance.geo-confirmed`, giving account profiles extra confidence without manual input. (Flutter reference: `native_geofence` package can be used during mock/prototype stages to monitor entry/exit events while keeping the invite module decoupled from the specific plugin.) Future enhancement: once we unlock account-profile-to-guest messaging, accepted invitees will be able to opt into push channels—or even lightweight chat rooms—so account profiles and invite trees can coordinate in real time. That capability is deferred beyond v1 and will reuse the Task/Reminder notification rails with additional consent checks.

---

## 5. Gamification Hooks

* **Streak Engine:** Maintains per-user streak documents with counters for consecutive days of invite engagement. Feeds Phase 8 Gamification Spine.
* **Shareable Badges:** Each accepted invite can mint a badge reference consumed by the Flutter badge component.
* **Leaderboard Source Events:** Emits delta events to the Multidimensional Insights Service with payload `{model_key: "invite_conversion", topic_reference: {type: "user", id: sender_user_id}, metrics: {accepted_invites: 1}}`.

---

## 6. Roadmap Alignment

* FCX-02 wires mocked repositories to this contract.
* Phase 9 extends the module with swipe-style carousels and WhatsApp deep links.
* Account Profile Workspace fast-follow consumes `invite_edges` to expose referral funnels to account operators without duplicating logic. A dedicated Account Analytics module will aggregate invitation performance per plan, quota bucket, and channel to support billing and upsell strategies.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `INV-01` | Approved | Inviter principal is typed (`user|account_profile`) with explicit issuer audit. | Keeps attribution/audit stable across share and direct invites. | Section `2.1 A` + `3.1` |
| `INV-02` | Approved | Duplicate invite prevention is strict by `(tenant,event,receiver,inviter_principal)` key. | Prevents spam/metric inflation. | Section `2.1 B` |
| `INV-03` | Approved | Exactly one credited acceptance per `(receiver,event)`; explicit selection is required. | Deterministic conversion and gamification metrics. | Section `2.1 C` |
| `INV-04` | Approved | Quotas/limits are backend-owned and enforceable via `429`. | Client cannot bypass rate/plan controls. | Section `2.1 D` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-invites-implementation.md` | Invite backend/client flow hardening | In progress | `2.1`, `3`, `4` | Canonical stream for invite delivery decisions. |
| `TODO-v1-web-to-app-policy.md` | Web/share acceptance boundary policy | In progress | `4.3`, `4.4` | Governs web exception and attribution path. |
