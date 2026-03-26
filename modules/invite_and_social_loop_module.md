# Documentation: Invite & Social Loop Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Invite & Social Loop module (MOD-302) governs the tenant app virality engine. It manages invite issuance, referral graph analytics, friend resume projections, and gamified progression that feeds both the tenant app and the account profile workspace. The MVP now runs on backend-owned persistence (`belluga_invites` package + Mongo projections) with Flutter consuming the canonical API contract directly.
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

### 1.2 Route/Subscope Matrix

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/convites` | tenant host/app | `tenant` | `tenant_public` | n/a | tenant user |
| `/agenda` invite actions | tenant host/app | `tenant` | `tenant_public` | n/a | tenant user |
| `/workspace/{account_slug}` invite metrics/workspace surfaces | tenant host/app | `tenant` | `tenant_public` | `account_workspace` | account membership / landlord override |
| invite landing via share `code` (`/invite?code=...`) | site_public or tenant host web landing | `landlord` or `tenant` | `site_public` or `tenant_public` | n/a | preview-first on web (read-only CTA to app); app flow allows anonymous accept/decline with device-bound identity |

---

## 2. Design Principles

1. **Graph-Native Modeling:** Invites, referrals, and friend relationships are stored as a directed multigraph (`invite_edges`). Every edge carries immutable metadata (source account profile, campaign id, channel) so downstream scoring remains deterministic.
2. **Privacy-Respecting Exposure:** Contact/person metadata is normalized into viewer-scoped resumes that only include the data points explicitly allowed by each user (`aggregate_only`, `capped_profile`, `full_profile`). The module never leaks raw address book details to other modules.
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
We never allow the same inviter to invite the same receiver to the same invite target more than once.

**Canonical target reference:**
`(event_id, occurrence_id | null)`

- `occurrence_id` is required whenever the experience has multiple actionable occurrences or when a runtime attendance action is occurrence-resolved.
- `occurrence_id = null` is allowed only as a compatibility shortcut for single-occurrence or intentionally event-scoped invite flows.

**Uniqueness key:**
`(tenant_id, event_id, occurrence_id | null, receiver_user_id, inviter_principal.kind, inviter_principal.id)`

If a duplicate attempt occurs:
- Backend responds with `already_invited` and no record is created.
- Client surfaces “Já convidado”.

### C) Credited Acceptance (One Credited Invite Per Receiver + Target)
When a user accepts via invites, exactly **one** invite becomes the credited acceptance for that `(receiver_user_id, event_id, occurrence_id | null)` target.

- UI must force explicit selection (“Aceitar convite de …” opens a selector dialog); **no default inviter selection** is applied.
- On acceptance, the selected invite transitions to `accepted` with `credited_acceptance=true`. This flag marks which invite edge received attribution for the authenticated attendance confirmation; it is not a second attendance record.
- All other invites for the same `(receiver_user_id, event_id, occurrence_id | null)` target transition to `superseded` with `supersession_reason=other_invite_credited` (still queryable for audit and reporting, but not counted as accepted conversions).
- If the receiver confirms attendance directly for the same target without selecting a pending invite, pending invites for that target transition to `superseded` with `supersession_reason=direct_confirmation`.
- Event-scoped compatibility flows must resolve the effective target reference before credit is assigned so multi-occurrence events never collapse unrelated attendance intents into one conversion.
- Generic event confirmation surfaces must not silently choose a winning inviter when more than one pending inviter exists for the same target; attribution requires explicit selection.

### D) Backend-Owned Limits (Tenant Settings + Enforcement)
Invite limits are configured and enforced by the backend. Flutter:
- fetches settings when needed (and may cache briefly for UX),
- shows quota/limit messaging,
- relies on backend enforcement as the source of truth.

Backend must return:
- `429` when over quota/rate-limited,
- a structured payload describing which limit was exceeded and when it resets.

**Suggested healthy defaults (backend-owned; per-tenant + per-plan override):**
- `max_invites_per_day_per_user_actor`: `100`
- Suppression: per-account blocklist + per-user opt-out

**MVP simplification (approved):**
- Invite-send cap enforced in MVP: `max_invites_per_day_per_user_actor`.
- Deferred to VNext for invite-send policy: `max_invites_per_event_per_inviter`, `max_invites_per_day_per_account`, `max_pending_invites_per_invitee`, `max_invites_to_same_invitee_per_30d`.

## 2.2 Lifecycle Baseline (Invite vs Commitment vs Check-in)

The invite stack must be modeled as three separate axes:

1. **Invite lifecycle (social decision)**
   - `invite.created` is the creation event, not a persisted status.
   - A newly created invite starts with `status = pending`.
   - Invite status then evolves independently: `pending -> accepted | declined | expired` (plus audit/control variants such as `superseded`, `suppressed`).

2. **Attendance confirmation / reservation lifecycle**
   - Invite acceptance is a **social conversion**, not the entire attendance state machine.
   - Canonical free attendance write is modeled as attendance confirmation; paid attendance follows reservation lifecycle.
   - Attendance mode is determined by the event/occurrence attendance policy, not simply by whether the event is paid.
   - Approved policy baseline: `free_confirmation_only | paid_reservation_only | either`.
   - Attendance policy governance is tenant-owned under the tenant `events` settings namespace. Account profiles creating events inside a tenant are limited to the tenant-approved policy boundaries.
   - Event write baseline: the event chooses one `attendance_policy` inside the tenant boundaries and may optionally enable `allow_occurrence_policy_override`.
   - Attendance policy resolves from the event `attendance_policy`, with occurrence-level configuration taking effect only when the event explicitly enables occurrence override.
   - Recommended tenant settings baseline:
     - `allowed_policies`: subset of `free_confirmation_only | paid_reservation_only | either`
     - `default_policy`: one value from `allowed_policies`
     - `allow_event_override`: boolean
   - If tenant settings disable event override, event creators inherit the tenant default policy. If override is allowed, the event may choose any policy inside `allowed_policies`.
   - If the event sets `allow_occurrence_policy_override=true`, occurrences may choose their own policy inside tenant `allowed_policies`; otherwise occurrences inherit the event policy.
   - `paid_reservation_only` and `either` require the tenant/runtime to support the paid reservation capability; otherwise those policies are invalid at both settings and event-write time.
   - Canonical attendance-write ownership belongs to an adjacent Participation/Attendance domain. Invites may trigger or project confirmation/reservation state for UX, but they do not own that source-of-truth.
   - Free attendance confirmation and paid reservation are mutually exclusive for the same user + event/occurrence unless an explicit upgrade/migration rule is introduced.
   - Acceptance-to-attendance transition rule:
     - `free_confirmation_only`: accepting an invite records the social conversion and the authenticated attendance confirmation unless the user is already confirmed.
     - `paid_reservation_only`: accepting an invite records the social conversion only; `paid_reservation` exists only after the reservation/payment flow succeeds.
     - `either`: accepting an invite records the social conversion first, then the system must resolve direct confirmation vs `paid_reservation` through explicit user choice or a backend default. This path exists only when the resolved event/occurrence policy is `either`.

3. **Check-in lifecycle (arrival proof)**
   - Check-in is separate from both invite acceptance and attendance confirmation/reservation.
   - It is an on-site proof action (`geofence | qr | staff_manual | admission-assisted`).
   - Successful check-in confirms real attendance; lack of check-in does not automatically prove absence.

Post-event outcome rule:
- default unresolved outcome without check-in is `unconfirmed`.
- `no_show` should be explicit/policy-driven, not the automatic fallback.
- manual/operator confirmation may produce `manually_confirmed` where business flows require it.

## 2.3 Resolved Conceptual Decisions

These decisions are now part of the module baseline and should be treated as canonical unless superseded explicitly.

- [x] 🟢 `INV-PD-01` Target identity baseline resolves to `event_id + occurrence_id | null`.
  - Approved direction: `occurrence_id` is required for multi-occurrence runtime actions; `null` is allowed only for single-occurrence or intentionally event-scoped compatibility flows.

- [x] 🟢 `INV-PD-02` Attendance policy contract is approved as `free_confirmation_only | paid_reservation_only | either`.
  - Approved direction: the event chooses one policy within tenant-owned `settings.events.attendance` boundaries and may allow occurrences to override it while still respecting those boundaries.

- [x] 🟢 `INV-PD-03` Attendance-write ownership boundary belongs to an adjacent Participation/Attendance domain, not to Invites, Events, or Ticketing.
  - Approved direction: Invites owns social conversion and attribution; Participation/Attendance owns attendance confirmation/reservation state; Ticketing may fulfill paid flows without becoming the canonical owner of attendance identity.

- [x] 🟢 `INV-PD-04` Acceptance-to-attendance transition follows attendance policy.
  - Approved direction: acceptance always records social conversion first; policy then decides whether the system records direct attendance confirmation, requires successful `paid_reservation`, or asks the user/default policy to choose in `either` mode.

## 2.4 Resolved Remaining Conceptual Decisions

These decisions are now approved and complete the invite-module baseline. Friends graph evolution remains a separate exploration stream.

- [x] 🟢 `INV-PD-05` Direct native contract is group-first and selection-explicit.
  - Approved direction: native app surfaces use grouped invite cards by target, each exposing stable `inviter_candidates[]` with `invite_id`. Direct native mutations are `POST /invites` (send), `POST /invites/{invite_id}/accept`, and `POST /invites/{invite_id}/decline`. Explicit inviter selection is mandatory whenever a target has multiple candidates.

- [x] 🟢 `INV-PD-06` Web exception boundary remains narrow and code-bound.
  - Approved direction: web may open invite landing, accept by a single `code`, and re-share only the same target externally after acceptance. Web does not expose inbox/feed browsing, multi-inviter selection, direct invite send/decline, agenda acceptance, confirmation/reservation-choice UX, presence confirmation, or check-in. If acceptance requires a richer next step than auto-resolution, web must hand off to the app.

- [x] 🟢 `INV-PD-07` Post-event attendance outcome policy is default-`unconfirmed`, explicit-`no_show`.
  - Approved direction: `unconfirmed` is always the default unresolved post-event outcome without successful check-in. `no_show` may be assigned only by explicit policy or privileged operator action, typically when direct attendance confirmation or paid reservation existed and the event closed without confirmed attendance. `manually_confirmed` is a privileged correction/audit outcome and never inferred automatically.

- [x] 🟢 `INV-PD-08` Social metric semantics preserve the project north star.
  - Approved direction: north-star metric 1 is `credited_invite_acceptances` from canonical `invite.accepted`; north-star metric 2 remains exposed as `presences_confirmed`, but is incremented by successful attendance confirmation or paid reservation activation. `check_ins`, `attendance_outcomes`, `invite_sent`, `share_visits`, and views remain secondary/supporting metrics.

- [x] 🟢 `INV-PD-10` Invite terminal semantics distinguish supersession from suppression.
  - Approved direction: `superseded` is business-outcome closure and must carry explicit `supersession_reason` (`other_invite_credited`, `direct_confirmation`); `suppressed` remains reserved for policy/governance closure such as opt-out, abuse controls, or administrative blocking.

- [x] 🟢 `INV-PD-09` Privacy exposure policy follows viewer-scoped exposure with aggregate fallback.
  - Approved direction: `public` users may appear as `full_profile` in permitted invite/social-proof surfaces. `friends_only` users may appear as `full_profile` only when the target has explicitly approved the viewer through `favorite_edge(target -> viewer)`; reciprocal favorites are the product-level `friend` label, but not a separate visibility primitive. Unilateral contact matches and direct invite counterparties may receive at most `capped_profile` unless a target-owned favorite already grants `full_profile`. `capped_profile` excludes avatar/photo and specific accepted-event details; outside those relationships, users contribute only anonymized/aggregate counts.

- [x] 🟢 `INV-PD-10` Workspace visibility is permissioned and operationally scoped.
  - Approved direction: account workspace may view event-level aggregates for events it manages, inviter-principal metrics for inviter principals it owns, and issuer-user audit only for privileged roles. Raw invitee identity is excluded from default analytics dashboards and may appear only on explicit operational/audit lists where business handling requires it.

- [x] 🟢 `INV-PD-11` Minimum Mongo read-model baseline stays intentionally small.
  - Approved direction: V1 baseline is `invite_feed_projection` plus `principal_social_metrics`. `event_social_projection` and any richer per-event read model are added only when measured hot-query pressure justifies them.

- [x] 🟢 `INV-PD-12` Missions/challenges remain outside invite ownership.
  - Approved direction: invite conversions and attendance-confirmation signals are outbound behavior sources for `belluga_missions`; challenge definition, progress evaluation, and reward unlocks do not live inside the invite module.

## 2.5 Deferred / Separate Exploration

- Contacts, favorites, friendship semantics, and richer people-social-proof products remain intentionally outside this resolved baseline and are delegated to the future `belluga_connections` package (`TODO-vnext-connections-package.md`).
- Future downstream result attribution beyond direct invite acceptance (for example level-based check-ins, promo requests, purchases, or offer claims generated by an invite tree) is delegated to `TODO-vnext-referral-result-attribution.md`. The approved direction is lineage snapshot + append-only activity facts + indexed aggregate projections, not request-path graph traversal.

## 3. Data Model

### 3.1 `invite_edges`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "sender_user_id": "ObjectId()",
  "receiver_user_id": "ObjectId()",
  "event_id": "ObjectId()",
  "occurrence_id": "ObjectId() | null",
  "invite_code": "String",
  "status": "String",
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
`status` ∈ {`pending`, `viewed`, `accepted`, `declined`, `superseded`, `expired`, `snoozed`, `suppressed`}. `channel` includes `whatsapp`, `in_app`, `qr`, `link`. `auto_expire_at` is derived from the related event/offer end time so invitations automatically close when the underlying experience has passed. `plan_charge_bucket` ties each invite to the account plan quota bucket used by billing (e.g., `core`, `premium_boost`), enabling per-plan limits.

Invite domain owns the social decision state only. Canonical attendance confirmation / reservation, check-in, and post-event attendance outcome live outside `invite_edges`. Invite feeds may project confirmation/reservation or attendance summaries for UX, but those projections are not source-of-truth.

**V1 additional fields (required):**
```json
{
  "inviter_principal": { "kind": "user|account_profile", "id": "ObjectId()" },
  "account_profile_id": "ObjectId() | null",
  "issued_by_user_id": "ObjectId() | null",
  "credited_acceptance": "Boolean",
  "supersession_reason": "null|other_invite_credited|direct_confirmation"
}
```
`occurrence_id` is required for multi-occurrence runtime targets and remains nullable only for single-occurrence/event-scoped compatibility flows.
`account_profile_id` is required when `inviter_principal.kind = account_profile` and must match the profile issuing the invite.
`supersession_reason` is set only when `status = superseded`. `suppressed` remains reserved for policy/governance closure.

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
Authoritative viewer-scoped resume objects consumed by Flutter domain models. Long-term ownership should move to `belluga_connections`.
```json
{
  "_id": "ObjectId()",
  "user_id": "ObjectId()",
  "friend_display_name": "String",
  "avatar_url": "String",
  "match_label": "String",
  "profile_exposure_level": "aggregate_only|capped_profile|full_profile",
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
| `/invites` | GET | Returns paginated grouped invite feed by target with stable inviter candidates, contextual prompts, quota status, and suppression flags. |
| `/invites` | POST | Creates direct invites for one or more recipients on a canonical invite target. |
| `/invites/stream` | GET | Streams invite deltas for live updates (created/updated/deleted). |
| `/invites/settings` | GET | Returns backend-owned tenant settings relevant to invite quotas, anti-spam, and client UX messaging. |
| `/invites/{invite_id}/accept` | POST | Accepts the selected invite edge in native app and returns attendance next-step metadata. |
| `/invites/{invite_id}/decline` | POST | Declines the selected invite edge in native app without implicitly declining other inviter candidates. |
| `/invites/share` | POST | Creates (or returns) an external share code that attributes installs/signups to an inviter principal for a specific invite target. |
| `/invites/share/{code}` | GET | Resolves share-code invite preview payload for unauthenticated/authenticated invite landing surfaces. |
| `/invites/share/{code}/materialize` | POST | Creates or reuses the canonical invite edge for the authenticated user before any accept/decline action. |
| `/contacts/import` | POST | Imports hashed contacts for friend matching and invite discovery. |

**Deferred (post-MVP) endpoints:** `/invites/share/{code}/consume`, `/invites/{inviteCode}/resend`, `/invites/{inviteCode}/snooze`, `/invites/{inviteCode}/suppress-event`, `/invites/{inviteCode}/accept/import-contacts`, `/invites/{inviteCode}/attendance`.

### 4.1 Native App Invite Contract

Native app is the full-fidelity invite client.

- `GET /invites` returns grouped invite cards by canonical target, not a flat one-row-per-edge list.
- Each grouped card must include stable `inviter_candidates[]` entries with `invite_id` so the app can enforce explicit inviter selection when multiple pending inviters exist for the same target.
- `POST /invites` is the native direct-send mutation for existing users or matched contacts.
- `POST /invites/{invite_id}/accept` accepts the selected invite edge, supersedes competing pending invites for the same `(receiver,target_ref)`, and returns the resolved `attendance_policy` plus next-step metadata (`none`, `free_confirmation_created`, `reservation_required`, `commitment_choice_required`, or `open_app_to_continue`).
- `POST /invites/{invite_id}/decline` declines only the selected edge; it does not silently decline other pending inviter candidates for the same target.
- Native app remains the trusted surface for grouped invite selection, invite inbox management, and any richer follow-up action beyond narrow web exceptions.

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
  "occurrence_id": "ObjectId() | null",
  "inviter_principal": { "kind": "user|account_profile", "id": "ObjectId()" },
  "issued_by_user_id": "ObjectId() | null",
  "created_at": "Date",
  "expires_at": "Date | null"
}
```

**Key requirements:**
- `code` resolves to a single inviter principal + canonical invite target.
- Backend records **share visits** and exposes preview payload with canonical invite identity.
- App progressive-profiling flow may accept/decline directly from preview using standard `/invites/{invite_id}/accept|decline`.
- Authenticated continuation flows may still materialize attribution through `/invites/share/{code}/materialize` before decision when explicit pre-bind is required.
- Backend must prevent duplicate invite issuance to the same receiver+target+inviter principal (see Uniqueness rule); the share code is attribution, not a loophole to spam.

### 4.4 Web Promotion-Only + App Anonymous Acceptance (V1 Progressive Profiling)

V1 uses web as read-only promotion and app as anonymous-first conversion:
- Web invite landing resolves preview context from a single invite/share `code` and remains read-only.
- Web invite landing exposes promotion CTA (`Baixe o App para Confirmar`) with `code` propagation to app/open-store paths.
- Web does not execute invite accept/decline mutations and does not expose grouped invite inbox/send flows.
- App entry via `/invite?code=...` must render invite-first preview and allow anonymous accept/decline without forced login.
- The credited inviter remains the inviter principal bound to that `code` (no web-side multi-inviter selection).
- After anonymous acceptance, app keeps feed/map navigation available; trust actions (`favorite`, `send_invite`, presence/check-in boundaries) are intercepted by Auth Wall.
- If a post-accept path requires richer fulfillment, handling remains app-owned; web does not expand to mutation surfaces.

This preserves low-friction viral conversion while keeping trust boundaries explicit.

### 4.4.A Compatibility Assurance Baseline

Invite confidence is intentionally layered:
- canonical business semantics are enforced in Laravel feature/package tests;
- Flutter controller/widget and UI-flow tests protect the invite state machine, but do not count as real backend compatibility when they use doubles;
- Flutter repository/decoder tests own payload-shape and malformed/terminal transport coverage;
- deployed compatibility is proven only in `stage`, using deterministic invite fixtures plus:
  - Flutter runtime tests for mobile/app-domain resolution;
  - browser tests for host/domain web entry, preview, auth redirect, and fallback behavior.

Testing implications:
- fake `integration_test` coverage must never be described as end-to-end invite safety;
- `stage` may run mutation-backed invite compatibility because it is the highest lane where mutation is allowed;
- `main` remains read-only smoke only;
- web invite validation must prove host-based tenant resolution;
- Flutter runtime invite validation must prove app/mobile tenant resolution (`X-App-Domain` / package identifier path).

Stage-only invite test support is allowed for this purpose, provided all of the following remain true:
- it is guarded by environment + secret + tenant boundary;
- it is deterministic and run-id isolated;
- it uses canonical invite services/contracts for invite behavior setup rather than ad-hoc direct invite mutations;
- it is unavailable outside the intended `stage` boundary.

#### Sanctum + Identity Requirement (Progressive Profiling)

Canonical invite APIs remain Sanctum-validated, with identity behavior split by channel:

- App may mint or resume an anonymous identity via `POST /anonymous/identities` for device-bound progressive profiling flows.
- Web invite landing in V1 must not mint anonymous identity for invite conversion; it is read-only + promotion only.
- Invite share-code materialization (`POST /invites/share/{code}/materialize`) remains authenticated-only; anonymous attempts must return deterministic `401 auth_required`.
- Canonical invite acceptance endpoint (`POST /invites/{invite_id}/accept`) must accept app-originated anonymous identities in V1 and preserve attribution semantics.
- Flutter/web invite landing compatibility remains anchored on `/invite?code=...`; clients must preserve `code` through onboarding/install bootstrap so attribution is not lost.

**Events**
* Outbound: `invite.created`, `invite.accepted`, `invite.declined`, `invite.superseded`, `invite.accepted.contacts-import-triggered`, `invite.fulfillment.step-required`, `invite.fulfillment.step-completed`, `invite.attendance.confirmed`, `invite.attendance.unconfirmed`, `invite.attendance.no-show`, `invite.attendance.geo-confirmed`, `invite.expired`, `invite.reward-unlocked`, `invite.rate-limited`, `invite.plan-limit-reached`, `invite.snoozed`, `invite.suppressed`.
* Inbound: `user.profile.updated` (refresh resumes), `agenda.action.completed` (to suggest invites tied to actions), `participation.presence_confirmation.recorded`, `participation.check_in.recorded`, `insights.rank.changed`, `task.completed` (so we can auto-unsnooze when reminders convert).
* Analytics/CRM Integration: Every fulfillment intent (`invite.fulfillment.step-required`, e.g., pay deposit, upload document) is mirrored to the Account Analytics/CRM module along with contact info so account operators can track outstanding requirements. When tasks complete, the analytics module receives `invite.fulfillment.step-completed` events (emitted by Transaction Bridge or Task & Reminder). Attendance-related projections are driven by confirmation/reservation/check-in inputs; `invite.attendance.unconfirmed` is the default unresolved post-event state, while `invite.attendance.no-show` should be explicit/policy-driven rather than automatic. These events tie back to account KPIs and invite reward logic.
* Task & Reminder Integration: `invite.snoozed` dispatches a `task.intent` payload `{ source_type: "invite", invite_id, reminder_type: "invite_followup" }` so MOD-306 can schedule pushes. When a user selects “Decide later,” remind them before the invite expires. As the event time approaches, the invite module emits a `task.intent` with `reminder_type: "invite_checkin"` targeting the invitee to complete the relevant attendance flow. That reminder may deep link to an attendance-confirmation, reservation, or check-in surface depending on policy. When the tenant shares venue coordinates, the participation/check-in flow may request passive location evidence; a successful geo-backed check-in should emit canonical participation events first, after which the invite module may project `invite.attendance.geo-confirmed` for social/account analytics. (Flutter reference: `native_geofence` package can be used during mock/prototype stages to monitor entry/exit events while keeping the invite module decoupled from the specific plugin.) Future enhancement: once we unlock account-profile-to-guest messaging, accepted invitees will be able to opt into push channels—or even lightweight chat rooms—so account profiles and invite trees can coordinate in real time. That capability is deferred beyond v1 and will reuse the Task/Reminder notification rails with additional consent checks.

### 4.5 Metric / Privacy / Workspace Baseline

- North-star metrics remain `credited_invite_acceptances` + `presences_confirmed`.
- `presences_confirmed` is the product/analytics label for successful attendance confirmation or paid reservation activation, regardless of which attendance path produced it.
- `check_ins`, `attendance_outcomes`, `invite_sent`, `share_visits`, and content views are secondary metrics and must not replace the north star in rankings or “Em Alta” logic.
- Future micro-conversions attributable to an invite tree (for example `check_in.recorded`, `promo.requested`, `purchase.completed`) must be modeled as append-only activity facts with bounded lineage snapshots and consumed through indexed projections; they must not require recursive invite-tree reads on hot request paths.
- `public` users may appear as `full_profile` in allowed social-proof surfaces.
- `friends_only` users are `full_profile`-visible only when the target explicitly approved the viewer via `favorite_edge(target -> viewer)`; reciprocal favorites become the product-level `friend` label.
- unilateral contact matches and direct invite counterparties may receive at most `capped_profile` unless a target-owned favorite already grants `full_profile`; all others contribute only anonymized counts.
- `capped_profile` must not expose avatar/photo or specific accepted-event history; non-approved contexts get only aggregate metrics/social proof.
- Workspace analytics are scoped to the managed event/account profile and expose raw invitee identity only on explicit operational/audit surfaces, never on default dashboards.

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
| `INV-02` | Approved | Duplicate invite prevention is strict by `(tenant,target_ref,receiver,inviter_principal)` key. | Prevents spam/metric inflation. | Section `2.1 B` |
| `INV-03` | Approved | Exactly one credited acceptance exists per `(receiver,target_ref)`; explicit selection is required. | Deterministic conversion and gamification metrics. | Section `2.1 C` |
| `INV-04` | Approved | Quotas/limits are backend-owned and enforceable via `429`. | Client cannot bypass rate/plan controls. | Section `2.1 D` |
| `INV-05` | Approved | Canonical invite target identity is `event_id + occurrence_id | null`, with `occurrence_id` required for multi-occurrence runtime actions. | Stabilizes uniqueness, credited acceptance, attendance lookup, mission scope, and Mongo index design. | Sections `2.1 B`, `2.1 C`, `3.1`, `4.3` |
| `INV-06` | Approved | Attendance policy enum is `free_confirmation_only | paid_reservation_only | either`; the event chooses one policy and occurrences may override only when the event explicitly allows it. | Gives all invite/attendance flows a single policy vocabulary with a clear event-to-occurrence hierarchy. | Section `2.2` |
| `INV-07` | Approved | Attendance confirmation / reservation state is owned by an adjacent Participation/Attendance domain, not by Invites, Events, or Ticketing. | Keeps social conversion, attendance write ownership, and paid fulfillment cleanly separated. | Sections `2.2`, `3.1` |
| `INV-08` | Approved | Invite acceptance always records social conversion first; attendance confirmation or reservation resolution then follows attendance policy. | Prevents conversion metrics from being coupled to reservation/check-in implementation details. | Section `2.2` |
| `INV-09` | Approved | Attendance policy governance is tenant-owned through `settings.events.attendance`; account-profile event creators may only choose policies inside tenant-approved boundaries. | Keeps event creation aligned with tenant business rules, capabilities, and monetization constraints. | Section `2.2` |
| `INV-10` | Approved | Native app owns grouped invite selection and uses `POST /invites`, `POST /invites/{invite_id}/accept`, and `POST /invites/{invite_id}/decline` as the canonical direct invite mutations. | Stabilizes backend/client contract for direct invite send and explicit inviter selection. | Sections `2.4`, `4`, `4.1` |
| `INV-11` | Approved | Web invite behavior in V1 is promotion/read-only only; app owns anonymous-first invite acceptance and all trust-action mutations. | Preserves low-friction growth while preventing web from becoming a divergent second invite client. | Sections `2.4`, `4.4` |
| `INV-12` | Approved | Default post-event unresolved outcome is `unconfirmed`; `no_show` and `manually_confirmed` are explicit policy/operator outcomes only. | Preserves fairness and analytics integrity. | Sections `2.4`, `4` |
| `INV-13` | Approved | North-star mobilization metrics are `credited_invite_acceptances` + `presences_confirmed`, where `presences_confirmed` normalizes both free confirmations and paid reservations. | Keeps mandate, analytics, rankings, and missions aligned. | Sections `2.4`, `4.5` |
| `INV-14` | Approved | Privacy exposure is viewer-scoped: `friends_only` users reach `full_profile` only when the target explicitly approves the viewer (for example via `favorite_edge(target -> viewer)`), `capped_profile` for unilateral/direct-counterparty contexts, otherwise aggregate/anonymized only. | Aligns social proof with the privacy-with-agency mandate while preserving simple contact-match UX and directional approval. | Sections `2.4`, `4.5` |
| `INV-15` | Approved | Workspace invite visibility is event/account-profile scoped and raw invitee identity is restricted to explicit operational/audit surfaces. | Protects tenant-safe business analytics without overexposing user identity. | Sections `2.4`, `4.5` |
| `INV-16` | Approved | V1 Mongo read-model baseline is `invite_feed_projection` + `principal_social_metrics`; richer projections are evidence-driven additions. | Prevents premature read-model sprawl. | Section `2.4` |
| `INV-17` | Approved | Challenges/missions consume invite and attendance behaviors from outside the invite module via `belluga_missions`. | Keeps reward logic decoupled from invite ownership. | Sections `2.4`, `4.5` |
| `INV-18` | Approved | Future invite-tree result attribution must use bounded lineage snapshots + append-only activity facts + indexed aggregate projections, never request-path graph traversal. | Opens the door for 1st/2nd-level micro-conversion analytics while staying MongoDB-friendly. | Sections `2.5`, `4.5` |
| `INV-19` | Approved | Invite terminal semantics distinguish `superseded` (business-outcome closure) from `suppressed` (policy/governance closure), with explicit `supersession_reason` when superseded. | Prevents attribution ambiguity and keeps policy blocking separate from causal loss. | Sections `2.1 C`, `2.2`, `3.1` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-invites-implementation.md` | Invite backend/client flow hardening | Completed (2026-03-12) | `2.1`, `3`, `4` | Canonical stream for invite delivery decisions. |
| `TODO-v1-web-to-app-policy.md` | Web promotion-only + app progressive profiling policy | In progress | `4.3`, `4.4` | Governs read-only web boundary, anonymous app acceptance, and attribution path. |
| `TODO-vnext-referral-result-attribution.md` | Future lineage-based downstream result attribution | In progress | `2.5`, `4.5` | Defines Mongo-safe activity-fact and projection strategy for 1st/2nd-level invite-tree results. |
