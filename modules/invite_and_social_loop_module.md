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
  - `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`
  - `foundation_documentation/todos/completed/TODO-store-release-android.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-invites-occurrence-target-migration.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-funnel-metrics-validation.md`

### 1.2 Route/Subscope Matrix

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/convites` | tenant host/app | `tenant` | `tenant_public` | n/a | tenant user in app; anonymous web only when acting as code-bound invite landing with valid `code` |
| `/agenda` invite actions | tenant host/app | `tenant` | `tenant_public` | n/a | tenant user |
| `/workspace/{account_slug}` invite metrics/workspace surfaces | tenant host/app | `tenant` | `tenant_public` | `account_workspace` | account membership / landlord override |
| invite landing via share `code` (`/invite?code=...`) | site_public or tenant host web landing | `landlord` or `tenant` | `site_public` or `tenant_public` | n/a | preview-first on anonymous web (read-only CTA to app); app flow allows anonymous preview/session context, while explicit accept/decline requires registered/authenticated identity; missing/invalid `code` falls back to canonical `/` |

---

## 2. Design Principles

1. **Graph-Native Modeling:** Invites, referrals, and friend relationships are stored as a directed multigraph (`invite_edges`). Every edge carries immutable metadata (source account profile, campaign id, channel) so downstream scoring remains deterministic.
2. **Privacy-Respecting Exposure:** Contact/person metadata is normalized into viewer-scoped resumes that only include the data points explicitly allowed by each user (`aggregate_only`, `capped_profile`, `full_profile`). The module never leaks raw address book details to other modules.
3. **Progressive Disclosure:** Invite payloads include `contextual_prompts` describing why an invite matters (e.g., “3 friends are attending this gig”). Context is generated from other modules but cached locally to avoid tight coupling.
4. **Event-Driven Incentives:** Rank changes, streaks, or reward unlocks emit events consumed by the Insights Service and Tenant Home Composer. The module does not compute final leaderboards; it only updates counters and emits domain events.
5. **Quota-Aware Monetization (Post‑MVP):** Invite issuance is tied to account plans. Every invite maps to a `plan_charge_bucket`, allowing us to invoice or enforce limits according to the account’s subscription tier.
6. **Automatic Occurrence-Scoped Security:** Invite codes inherit the lifecycle of the underlying scheduled experience; when the occurrence expires or a receiver suppresses invitations for that occurrence, tokens are invalidated automatically and cannot be reused.

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
- **User invites:** may target users already installed in the app and matched contacts directly. Unmatched local contacts use the external share-code branch from native app rather than a canonical in-app invite edge.
- **Account Profile invites:** may target followers/favorites for broader reach; direct user targeting is also allowed as needed.
- **Share codes:** allowed for both inviter types; eligibility rules still apply (user shares only to their contacts, account profiles can share to followers/favorites audiences).

### B) Uniqueness (No Duplicate Invites From Same Inviter)
We never allow the same inviter to invite the same receiver to the same invite target more than once.

**Canonical target reference:**
`occurrence_id`

- Invites are always issued for one concrete Event Occurrence.
- `event_id` is not a write contract input for invite target identity. The backend derives parent event context from `occurrence_id`; if a pre-release route or payload still carries `event_id`, it is checked only as disposable consistency context and rejected on conflict.
- `occurrence_id = null` is not a valid release write path. Any pre-release/local fixture with a missing occurrence must be reset, reseeded, or rejected before it can participate in release invite flows.

**Uniqueness key:**
`(tenant_id, occurrence_id, receiver_account_profile_id, inviter_principal.kind, inviter_principal.id)`

If a duplicate attempt occurs:
- Backend responds with `already_invited` and no record is created.
- Client surfaces “Já convidado”.

**Launch cutover note (approved breaking change):**
- The canonical recipient surface is `receiver_account_profile_id`, not raw `receiver_user_id`.
- The release lane intentionally cuts over pre-production user-targeted invite contracts, stored invite edges, and share materialization/acceptance flows to `receiver_account_profile_id`.
- Backward compatibility with `receiver_user_id` targeting is not required because invites, favorites, and friends have not been released to production.
- Review, audit, Claude, PR, and promotion gates must treat backward-compatibility requests for this first-production social cutover as out of scope and non-blocking unless they identify an independent launch risk unrelated to preserving pre-release contracts.
- Future memberships must authorize which acting user may send/respond on behalf of the recipient/sender Account Profile, but that actor authorization must not redefine the canonical recipient identity.

### C) Credited Acceptance (One Credited Invite Per Receiver Surface + Target)
When a user accepts via invites, exactly **one** invite becomes the credited acceptance for that `(receiver_account_profile_id, occurrence_id)` target.

- UI must force explicit selection (“Aceitar convite de …” opens a selector dialog); **no default inviter selection** is applied.
- On acceptance, the selected invite transitions to `accepted` with `credited_acceptance=true`. This flag marks which invite edge received attribution for the authenticated attendance confirmation; it is not a second attendance record.
- All other invites for the same `(receiver_account_profile_id, occurrence_id)` target transition to `superseded` with `supersession_reason=other_invite_credited` (still queryable for audit and reporting, but not counted as accepted conversions).
- If the receiver confirms attendance directly for the same target without selecting a pending invite, pending invites for that target transition to `superseded` with `supersession_reason=direct_confirmation`.
- Direct invite issuance and share-code materialization must compute the initial lifecycle state against the receiver's current occurrence participation. If the receiver already has an active attendance confirmation for the target occurrence, any newly persisted invite edge for audit/idempotency is stored as `superseded` with `supersession_reason=direct_confirmation` and must not appear in pending feed projections. If a credited invite winner already exists for that same receiver surface + occurrence, newly persisted competing invite edges are stored as `superseded` with `supersession_reason=other_invite_credited`.
- Event-level entry points must resolve the selected/default occurrence before invite or attendance attribution so an event never collapses unrelated attendance intents into one conversion.
- Generic event confirmation surfaces must not silently choose a winning inviter when more than one pending inviter exists for the same occurrence target; attribution requires explicit selection.
- Any pre-release user-targeted acceptance behavior must be cut over to the same `receiver_account_profile_id`-keyed rule before release closure.

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
   - Invite acceptance is independent from event capacity or later fulfillment availability. Capacity/fulfillment may block downstream reservation or check-in behavior, but it does not redefine the social invite decision. Event expiry may still invalidate acceptance.

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
   - Free attendance confirmation and paid reservation are mutually exclusive for the same participant + `occurrence_id` unless an explicit upgrade/migration rule is introduced. `event_id` is only parent/read context for participation relationships.
   - Acceptance-to-attendance transition rule:
     - `free_confirmation_only`: accepting an invite records the social conversion and the authenticated attendance confirmation unless the user is already confirmed.
     - `paid_reservation_only`: accepting an invite records the social conversion only; `paid_reservation` exists only after the reservation/payment flow succeeds.
     - `either`: accepting an invite records the social conversion first, then the system must resolve direct confirmation vs `paid_reservation` through explicit user choice or a backend default. This path exists only when the resolved event/occurrence policy is `either`. This branch is deferred out of the store-release lane and is currently owned by `foundation_documentation/todos/active/vnext/TODO-vnext-event-checkin.md` until a dedicated participation/presence-confirmation owner exists.

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

- [x] 🟢 `INV-PD-01` Target identity baseline resolves to `occurrence_id`.
  - Approved direction: every invite targets one concrete Event Occurrence. `event_id` is derived parent context only and `occurrence_id = null` is not a valid release write path.

- [x] 🟢 `INV-PD-02` Attendance policy contract is approved as `free_confirmation_only | paid_reservation_only | either`.
  - Approved direction: the event chooses one policy within tenant-owned `settings.events.attendance` boundaries and may allow occurrences to override it while still respecting those boundaries.

- [x] 🟢 `INV-PD-03` Attendance-write ownership boundary belongs to an adjacent Participation/Attendance domain, not to Invites, Events, or Ticketing.
  - Approved direction: Invites owns social conversion and attribution; Participation/Attendance owns attendance confirmation/reservation state; Ticketing may fulfill paid flows without becoming the canonical owner of attendance identity.

- [x] 🟢 `INV-PD-04` Acceptance-to-attendance transition follows attendance policy.
  - Approved direction: acceptance always records social conversion first; policy then decides whether the system records direct attendance confirmation, requires successful `paid_reservation`, or asks the user/default policy to choose in `either` mode.

## 2.4 Resolved Remaining Conceptual Decisions

These decisions are now approved and complete the invite-module baseline. Friends graph evolution remains a separate exploration stream.

- [x] 🟢 `INV-PD-05` Direct native contract is group-first and selection-explicit.
  - Approved direction: native app surfaces use grouped invite cards by target, each exposing stable `inviter_candidates[]` with `invite_id`. Direct native mutations are `POST /invites` (send), `POST /invites/{invite_id}/accept`, `POST /invites/{invite_id}/decline`, and authenticated/promoted share acceptance via `POST /invites/share/{code}/accept`. Explicit inviter selection is mandatory whenever a target has multiple candidates.

- [x] 🟢 `INV-PD-06` Web exception boundary remains narrow and code-bound.
  - Approved direction: anonymous web may open invite landing and re-share only the same target externally, but acceptance in V1 requires app/authenticated continuation through `/invites/share/{code}/accept`. Authenticated web follows the normal authenticated posture established by QR login. Anonymous web does not expose inbox/feed browsing, multi-inviter selection, direct invite send/decline, agenda acceptance, confirmation/reservation-choice UX, presence confirmation, or check-in.

- [x] 🟢 `INV-PD-07` Post-event attendance outcome policy is default-`unconfirmed`, explicit-`no_show`.
  - Approved direction: `unconfirmed` is always the default unresolved post-event outcome without successful check-in. `no_show` may be assigned only by explicit policy or privileged operator action, typically when direct attendance confirmation or paid reservation existed and the event closed without confirmed attendance. `manually_confirmed` is a privileged correction/audit outcome and never inferred automatically.

- [x] 🟢 `INV-PD-08` Social metric semantics preserve the project north star.
  - Approved direction: north-star metric 1 is `credited_invite_acceptances` from canonical `invite.accepted`; north-star metric 2 remains exposed as `presences_confirmed`, but is incremented by successful attendance confirmation or paid reservation activation. `check_ins`, `attendance_outcomes`, `invite_sent`, `share_visits`, and views remain secondary/supporting metrics.

- [x] 🟢 `INV-PD-10` Invite terminal semantics distinguish supersession from suppression.
  - Approved direction: `superseded` is business-outcome closure and must carry explicit `supersession_reason` (`other_invite_credited`, `direct_confirmation`); `suppressed` remains reserved for policy/governance closure such as opt-out, abuse controls, or administrative blocking.

- [x] 🟢 `INV-PD-09` Privacy exposure policy follows viewer-scoped exposure with aggregate fallback.
  - Approved direction: `public` users may appear as `full_profile` in permitted invite/social-proof surfaces. `friends_only` users may appear as `full_profile` only when the target has explicitly approved the viewer by favoriting the viewer's personal Account Profile; reciprocal favorites are the product-level `friend` label, but not a separate visibility primitive. Unilateral contact matches and direct invite counterparties may receive at most `capped_profile` unless a target-owned favorite already grants `full_profile`. `capped_profile` excludes avatar/photo and specific accepted-event details; outside those relationships, users contribute only anonymized/aggregate counts.

- [x] 🟢 `INV-PD-10` Workspace visibility is permissioned and operationally scoped.
  - Approved direction: account workspace may view event-level aggregates for events it manages, inviter-principal metrics for inviter principals it owns, and issuer-user audit only for privileged roles. Raw invitee identity is excluded from default analytics dashboards and may appear only on explicit operational/audit lists where business handling requires it.

- [x] 🟢 `INV-PD-11` Minimum Mongo read-model baseline stays intentionally small.
  - Approved direction: V1 baseline is `invite_feed_projection` plus `principal_social_metrics`. `event_social_projection` and any richer per-event read model are added only when measured hot-query pressure justifies them.

- [x] 🟢 `INV-PD-12` Missions/challenges remain outside invite ownership.
  - Approved direction: invite conversions and attendance-confirmation signals are outbound behavior sources for `belluga_missions`; challenge definition, progress evaluation, and reward unlocks do not live inside the invite module.
  - Delivery boundary: store-release Flutter must not expose hardcoded mission tabs, local reward copy, static progress rules, or locally seeded rewards from event detail or invite flows. Any mission/gamification surface must be dynamic and backed by the future `belluga_missions` contract before it becomes visible runtime UI.

- [x] 🟢 `INV-PD-13` Contact matching is acquisition, not social approval.
  - Approved direction: `phone_hash` identifies whether an imported contact corresponds to an existing person; the resolved person is rendered through that person's personal Account Profile. A successful `contact_match` makes the person visible in `Contatos` and invite-eligible without requiring favorite first.

- [x] 🟢 `INV-PD-14` Contact groups are private, tag-like invite organization only.
  - Approved direction: user-private `contact_groups` organize in-app inviteable recipients like tags, so the same recipient may belong to multiple groups. This includes inviteables reached through `contact_match`, `favorite_by_you`, `favorited_you`, or `friend`. Group membership does not grant richer exposure, favorite state, or friend state. Unmatched local contacts are not groupable. When multiple groups are selected for invite targeting, the effective recipient set is deduplicated by canonical recipient identity before invite creation and before quota counting.

- [x] 🟢 `INV-PD-15` Inviteable type gating belongs to the profile-type registry.
  - Approved direction: invite surfaces are gated by `account_profile_type.capabilities.is_inviteable`. This applies beyond personal profiles and allows `favorite_by_you` / `favorited_you` to become valid inviteable reasons whenever the resolved target type is both favoritable and inviteable. Non-personal favorites still do not derive `friend` by themselves.

- [x] 🟢 `INV-PD-16` Contact discoverability is an instance privacy axis with permissive default.
  - Approved direction: `discoverable_by_contacts` is separate from `privacy_mode`. It controls whether imported contact hashes may materialize `contact_match`, defaults to `true`, and may be persisted before a future privacy-settings UI exists. Restrictive profile visibility does not, by itself, disable contact discovery when this flag remains enabled.

- [x] 🟢 `INV-PD-17` Inviteable list composition is unified, deduplicated, and filterable by relation type.
  - Approved direction: `/convites/compartilhar` renders one deduplicated default list of inviteable entries, while preserving `source_tags` / `inviteable_reasons` so the UI can filter by relation types such as `contact_match`, `favorite_by_you`, `favorited_you`, and `friend` using the same chip/filter interaction pattern adopted by Discovery.

- [x] 🟢 `INV-PD-18` Unmatched local contacts stay on an auxiliary native-share branch.
  - Approved direction: unmatched local contacts are not canonical inviteable recipients. Native app may expose them only as app-local external share targets using the event invite code, preferring WhatsApp direct-share when available and falling back to the system share sheet. This branch is not part of the backend-computed inviteable list, relation filters, or `contact_groups`, and it does not exist on web.

- [x] 🟢 `INV-PD-19` Invite composer interaction is action-first, not selection-first.
  - Approved direction: `/convites/compartilhar` optimizes for one-tap invite actions. Person rows expose immediate invite/share CTAs; group rows expose primary `Convidar grupo` / `Convidar todos` and may optionally allow drill-in for member selection. A home-style horizontal group rail is not part of this screen baseline; richer group browsing may live in a future dedicated contacts/friends management surface.

- [x] 🟢 `INV-PD-20` Identity materialization may reconcile prior contact imports without inventing new inviteable reasons.
  - Approved direction: when a user's canonical phone identity materializes, backend may later reconcile that `phone_hash` against hashes previously imported by other users. This may materialize outbound `contact_match` for those existing viewers and may also feed a future inbound suggestion surface labeled `Talvez você conheça` for the newly identified user. The same reconciliation signal may later drive informational lifecycle notifications such as "a contact entered the app", but that future consumer is advisory only and must not create `Contato`, `inviteable_reason`, or group eligibility by itself. The inbound suggestion is not `Contato`, not an `inviteable_reason`, not groupable, and does not become inviteable until an explicit favorite promotes the relationship into the normal inviteable rules. Delivery ownership for this late-reconciliation/reflection path now lives in `TODO-vnext-onboarding-identity-reconciliation-reflection.md`, not in the release-critical contacts/favorites/friends lane.

- [x] 🟢 `INV-PD-21` Presence, check-in, reservation, and attendance outcome relationships are occurrence-scoped.
  - Approved direction: free confirmation, paid reservation entitlement, check-in, no-show/manual outcome, and invite-driven direct-confirmation supersession all resolve to one concrete `occurrence_id`. `event_id` may appear only as derived parent context in payloads/projections; it must not be the write identity, uniqueness identity, or supersession identity for participation relationships.

- [x] 🟢 `INV-PD-21` Contact-group CRUD is required, but composer is not the management surface.
  - Approved direction: `contact_groups` must support create/rename/delete and membership management in V1, but that CRUD belongs to dedicated group-visualization or friends-management surfaces rather than `/convites/compartilhar`. Exact UX may be explored through Stitch studies without reopening the business contract.

- [x] 🟢 `INV-PD-22` V1 group membership degrades by automatic removal.
  - Approved direction: when an existing grouped recipient ceases to be inviteable, V1 removes that recipient from `contact_groups` automatically instead of retaining a disabled or hidden stale membership.

- [x] 🟢 `INV-PD-23` Store Release share-code preview context is app-session only.
  - Approved direction: when the app resolves a valid share code through `GET /invites/share/{code}`, Flutter may hold the preview context in app/session memory keyed by `share_code + occurrence_id`. Event detail and invite surfaces may consume that projection to show the pending invite effect before explicit acceptance. This does not create a persisted `invite_edge`, does not require local persistent storage, and does not introduce a remote anonymous intent entity. If session context is lost, the app rehydrates from the share code carried by the route/deep link. Explicit authenticated acceptance remains `POST /invites/share/{code}/accept`.

## 2.5 Deferred / Separate Exploration

- Contacts, favorites, friendship semantics, and private contact-group organization are no longer deferred out of the release baseline. Store-release execution now owns the minimal `contact_match -> favorite -> friend` core (`TODO-store-release-minimal-friends-and-favorites-mvp.md`), including reciprocal-friend derivation, user-private contact groups for invite targeting, and viewer-scoped exposure on invite/social-proof surfaces. Broader package extraction/convergence and non-release consumers may still continue separately after the release lane closes.
- Future downstream result attribution beyond direct invite acceptance (for example level-based check-ins, promo requests, purchases, or offer claims generated by an invite tree) is delegated to `TODO-vnext-referral-result-attribution.md`. The approved direction is lineage snapshot + append-only activity facts + indexed aggregate projections, not request-path graph traversal.
- Remote anonymous invite-intent persistence is deferred beyond Store Release. A future design may introduce remote `share_intents`, anonymous identity binding, metrics, multi-session recovery, and promotion to real invite edges after OTP/authentication, but that is not part of the current release addendum.

## 3. Data Model

### 3.1 `invite_edges`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "sender_user_id": "ObjectId()",
  "receiver_account_profile_id": "ObjectId()",
  "event_id": "ObjectId()",
  "occurrence_id": "ObjectId()",
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
`status` ∈ {`pending`, `viewed`, `accepted`, `declined`, `superseded`, `expired`, `suppressed`}. `channel` includes `whatsapp`, `in_app`, `qr`, `link`. `auto_expire_at` is derived from the related event/offer end time so invitations automatically close when the underlying experience has passed. `plan_charge_bucket` ties each invite to the account plan quota bucket used by billing (e.g., `core`, `premium_boost`), enabling per-plan limits.

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
`occurrence_id` is the canonical invite target and is required for every release invite edge. Write paths should derive `event_id` server-side from the occurrence; any pre-release route/payload `event_id` is disposable context and must be rejected on conflict rather than used for identity.
`account_profile_id` is required when `inviter_principal.kind = account_profile` and must match the profile issuing the invite.
`receiver_account_profile_id` is the canonical persisted recipient identity. Pre-production user-targeted invite storage must be reset or cut over to this field; `receiver_user_id` is not part of the release contract.
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
Authoritative viewer-scoped resume objects consumed by Flutter domain models for matched contacts, favorites, and friends. The store-release lane owns the minimum runtime contract; any later package extraction must preserve this contract instead of redefining it.
```json
{
  "_id": "ObjectId()",
  "user_id": "ObjectId()",
  "receiver_account_profile_id": "ObjectId()",
  "profile_type": "String",
  "friend_display_name": "String",
  "avatar_url": "String",
  "match_label": "String",
  "profile_exposure_level": "aggregate_only|capped_profile|full_profile",
  "source_tags": ["contact_match|favorite_by_you|favorited_you|friend"],
  "is_inviteable": "Boolean",
  "inviteable_reasons": ["contact_match|favorite_by_you|favorited_you|friend"],
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
| `/invites/share/{code}/accept` | POST | Canonical authenticated/promoted share acceptance endpoint; rejects anonymous users with `401 auth_required`, resolves/materializes invite edge, and applies acceptance atomically. |
| `/invites/share/{code}/materialize` | POST | Creates or reuses the canonical invite edge for the authenticated user before any accept/decline action. |
| `/contacts/import` | POST | Imports hashed contacts for contact matching and inviteable acquisition. |
| `/contacts/inviteables` | GET | Returns the backend-computed, deduplicated in-app inviteable recipient list with `receiver_account_profile_id`, `inviteable_reasons`, and `profile_exposure_level`. |
| `/contact-groups` | GET | Lists the authenticated user's private contact groups after pruning recipients that are no longer inviteable. |
| `/contact-groups` | POST | Creates a private contact group over in-app inviteable `receiver_account_profile_id` members, deduplicating membership. |
| `/contact-groups/{group_id}` | PATCH | Renames a private contact group and/or replaces its inviteable recipient membership. |
| `/contact-groups/{group_id}` | DELETE | Deletes a private contact group without changing favorite, friend, or privacy state. |
| `/auth/otp/verify` | POST | Adjacent identity endpoint that materializes the verified phone identity and merges anonymous invite history before contact matching becomes canonical. |

**Release implementation ownership note:** `App\Application\Social` is the canonical app-level integration boundary for the store-release `contact_match`, account-profile favorite, reciprocal friend, inviteable-list, and `contact_groups` composition. It may orchestrate app-owned Account Profile/Favorites data with `belluga_invites` package contracts, while route error envelopes must reuse the `belluga_invites` domain exception handling standard so invite/social APIs do not fork response semantics.

**Deferred (post-MVP) endpoints:** `/invites/share/{code}/consume`, `/invites/{inviteCode}/resend`, `/invites/{inviteCode}/suppress-event`, `/invites/{inviteCode}/accept/import-contacts`, `/invites/{inviteCode}/attendance`.

### 4.1 Native App Invite Contract

Native app is the full-fidelity invite client.

- `GET /invites` returns grouped invite cards by canonical target, not a flat one-row-per-edge list.
- Each grouped card must include stable `inviter_candidates[]` entries with `invite_id` so the app can enforce explicit inviter selection when multiple pending inviters exist for the same target.
- `POST /invites` is the native direct-send mutation for existing users or matched contacts.
- Direct invite recipient identity is an approved breaking launch cutover to the recipient Account Profile surface. Pre-production user-targeted direct-invite contracts, persisted invite edges, and share materialization/acceptance paths must cut over to `receiver_account_profile_id`; `receiver_user_id` is not part of the release contract.
- `GET /contacts/inviteables` is the canonical composer source for in-app recipients. It merges `contact_match`, `favorite_by_you`, `favorited_you`, and derived `friend` reasons into one row per `receiver_account_profile_id`, preserving all reasons for filtering and exposure decisions.
- For `contact_match`, `/contacts/inviteables` must query matched contact-directory rows directly for the current viewer rather than fetching an arbitrary first page of all imported hashes and filtering in memory. Old unmatched rows must not be able to hide a later matched real contact.
- When OTP verification merges an anonymous app identity into a registered phone identity, invite/contact ownership migration includes `contact_hash_directory.importing_user_id`. Contact imports performed before login must remain visible to the registered viewer after merge without requiring a full route restart or reimport as the first correctness path.
- `POST /contacts/import` must not expose a `user_id`-only invite target. If a matched installed user does not have an inviteable personal Account Profile, the release path must create/resolve the personal Account Profile before in-app targeting or leave the contact outside the canonical inviteable list.
- `/convites/compartilhar` consumes one unified deduplicated inviteable list by default, not parallel duplicated sections per relation source. Relation/source tags stay attached to each row so Discovery-style filters can narrow the list without changing canonical recipient identity.
- Native app may additionally expose unmatched local contacts as auxiliary `external_contact_share_targets`. These are app-local only, are not part of the canonical inviteable list or relation filters, do not belong to `contact_groups`, and should prefer WhatsApp direct-share when available with system-share fallback.
- Future inbound discovery surfaces derived from identity-materialization reconciliation, such as `Talvez você conheça`, remain outside the canonical inviteable list and outside relation filters until the user creates an explicit favorite that yields a normal inviteable reason.
- The same reconciliation event may later power informational notifications such as "a contact entered the app", but those notifications are discovery-only hints and must not create contact/inviteable/group state by themselves.
- This late-reconciliation/reflection path is follow-up-owned by `TODO-vnext-onboarding-identity-reconciliation-reflection.md`; it is not part of the current release invite-composer delivery slice.
- `/convites/compartilhar` is action-first: person rows should support immediate invite/share, while group rows should support immediate `Convidar grupo` / `Convidar todos` plus optional drill-in for member-level selection. The screen baseline does not include a home-style horizontal group rail.
- `contact_groups` require CRUD in V1, but group creation/rename/delete does not belong to `/convites/compartilhar`; that management belongs to dedicated group/friends-management surfaces and the exact UX may be refined through Stitch studies.
- When an existing grouped recipient ceases to be inviteable, V1 removes that recipient from `contact_groups` automatically instead of keeping a disabled stale entry.
- `POST /invites/{invite_id}/accept` accepts the selected invite edge, supersedes competing pending invites for the same `(receiver_account_profile_id,target_ref)`, and returns the resolved `attendance_policy` plus next-step metadata (`none`, `free_confirmation_created`, `reservation_required`, `commitment_choice_required`, or `open_app_to_continue`).
- `POST /invites/{invite_id}/decline` declines only the selected edge; it does not silently decline other pending inviter candidates for the same target.
- Pending invite feeds are registered user-linked state in the Flutter client. After OTP/login emits a registered identity, the app shell must refresh pending invites through the repository contract before invite inbox/share screens decide empty states. Future invite/social repositories that depend on registered identity must register the same post-auth hydration consumer instead of relying on route re-entry.
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
  "occurrence_id": "ObjectId()",
  "inviter_principal": { "kind": "user|account_profile", "id": "ObjectId()" },
  "issued_by_user_id": "ObjectId() | null",
  "created_at": "Date",
  "expires_at": "Date | null"
}
```

**Key requirements:**
- `code` resolves to a single inviter principal + canonical invite target.
- The canonical invite target is the stored `occurrence_id`; write paths derive `event_id` server-side from the occurrence, and any pre-release route/payload `event_id` is disposable context rejected on conflict.
- Backend records **share visits** and exposes preview payload with canonical invite identity.
- App progressive-profiling flow resolves preview/session context before login, then accepts via `/invites/share/{code}/accept` only after registered/authenticated identity is available.
- Authenticated continuation flows may still materialize attribution through `/invites/share/{code}/materialize` before decision when explicit pre-bind is required.
- When share-code materialization or acceptance creates/reuses an invite edge, the persisted recipient target must be `receiver_account_profile_id`; cutting over any pre-production user-targeted share conversion is part of the release work.
- Backend must prevent duplicate invite issuance to the same receiver+target+inviter principal (see Uniqueness rule); the share code is attribution, not a loophole to spam.

### 4.4 Web Anonymous Promotion + Authenticated Acceptance (V1 Progressive Profiling)

V1 uses anonymous web as read-only promotion and app as preview-first conversion:
- Web invite landing resolves preview context from a single invite/share `code` and remains read-only.
- Web invite landing exposes app-promotion CTA (`Baixe o App para Confirmar`) with invite attribution plus requested-route preservation to app/open-store paths.
- Web handoff target is deterministic: invite landing preserves `/invite?code=...`; direct detail routes and guard-triggered promotions preserve the requested redirect path; canonical `/` remains fallback only when no valid continuation intent is available.
- Store/open targets are resolved dynamically per tenant for Android and iOS (backend contract; no client hardcoded store URLs).
- Anonymous web does not execute invite accept/decline mutations and does not expose grouped invite inbox/send flows. Authenticated web follows the QR-established authenticated posture for the surface.
- Web has no local contacts and therefore does not expose the native-app-only external-contact share branch or contact-group invite actions.
- Any unauthenticated tenant-public hard/auth gate reached on web must hand off to the canonical app-promotion route/screen and then into `/open-app`; anonymous web login continuation is not a V1 conversion path, and route-gated/action-gated boundaries must not diverge.
- Authenticated web is the normal authenticated web posture, but it is allowed only through QR login from an already promoted app identity; web-native email/password/social login is out of scope.
- App entry via `/invite?code=...` must render invite-first preview without forced login, then require registered/authenticated identity before explicit accept/decline.
- Deferred install-path capture in V1 is Android-first; iOS deferred capture is explicitly fast-follow required while installed-app universal links remain supported.
- First-open unresolved capture must emit deferred-capture-failed telemetry and route deterministically to `/`.
- The credited inviter remains the inviter principal bound to that `code` (no web-side multi-inviter selection).
- The anonymous app baseline is explicit: invite preview/session context, feed browsing, map browsing, and favorites may continue without forced login. Explicit invite accept/decline, `send_invite`, identity-owned routes such as `/profile`, and presence/check-in boundaries are intercepted by Auth Wall.
- If a post-accept path requires richer fulfillment, handling remains app-owned; web does not expand to mutation surfaces.

This preserves low-friction viral conversion while keeping trust boundaries explicit.

**Release funnel telemetry baseline:** Android store-release validation treats the web-to-app invite funnel as measurable only when each milestone includes its release attribution properties.

| Event | Required properties | Owner surface |
| --- | --- | --- |
| `web_invite_landing_opened` | `code`, `store_channel=web` | Web invite landing preview |
| `web_open_app_clicked` | `platform_target`, `store_channel=web` | App promotion/open-app CTA |
| `web_install_clicked` | `platform_target`, `store_channel=web` | App promotion/install CTA |
| `app_deferred_deep_link_captured` | `target_path`, `platform=android`, `store_channel` (`unknown` when native resolver does not provide it), plus `code` only for invite captures | Android first-open deferred capture |
| `app_deferred_deep_link_capture_failed` | `platform=android`, `failure_reason`, `store_channel` (`unknown` fallback) | Android first-open deferred capture |
| `app_invite_acceptance_requested` | `occurrence_id`, optional derived `event_id`, `code` when the app entered via share code, `source=invite_flow`, `auth_state` | App invite decision before boundary resolution |
| `app_invite_accepted` | `occurrence_id`, optional derived `event_id`, `code` when the app entered via share code, `source=invite_flow`; backend-equivalent `invite.accepted` may also carry `invite_source` for sink readback | Authenticated invite acceptance terminal, preferably emitted by the backend mutation owner |
| `favorite_artist_toggled` | `account_profile_id`, `is_favorite` | App first social-loop action |

### 4.4.A Runtime Assurance Baseline

Invite confidence is intentionally layered:
- canonical business semantics are enforced in Laravel feature/package tests;
- Flutter controller/widget and UI-flow tests protect the invite state machine, but do not count as real backend integration evidence when they use doubles;
- Flutter repository/decoder tests own payload-shape and malformed/terminal transport coverage;
- deployed runtime behavior is proven only in `stage`, using deterministic invite fixtures plus:
  - Flutter runtime tests for mobile/app-domain resolution;
  - browser tests for host/domain web entry, preview, auth redirect, and fallback behavior.

Testing implications:
- fake `integration_test` coverage must never be described as end-to-end invite safety;
- `stage` may run mutation-backed invite runtime checks because it is the highest lane where mutation is allowed;
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
- App authenticated upgrade is phone-OTP only; `POST /auth/otp/verify` must accept the current anonymous identity id so invite ownership/attribution migrates to the registered phone identity before restricted invite actions continue.
- After that registered identity is emitted, clients must run the Flutter post-auth hydration contract for invite/social user-linked streams, including pending invites. This immediate refresh is distinct from the VNext identity-materialization reflection lane for inbound `Talvez você conheça` style suggestions.
- Web invite landing in V1 must not mint anonymous identity for invite conversion; it is read-only + promotion only.
- Invite share-code materialization (`POST /invites/share/{code}/materialize`) remains authenticated-only; anonymous attempts must return deterministic `401 auth_required`.
- Canonical invite acceptance endpoints (`POST /invites/{invite_id}/accept` for materialized ids and `POST /invites/share/{code}/accept` for share preview) require authenticated identity and must preserve attribution semantics in V1.
- Flutter/web code-bound invite landing remains anchored on `/invite?code=...`; clients must preserve `code` through onboarding/install bootstrap so attribution is not lost.

**Events**
* Outbound: `invite.created`, `invite.accepted`, `invite.declined`, `invite.superseded`, `invite.accepted.contacts-import-triggered`, `invite.fulfillment.step-required`, `invite.fulfillment.step-completed`, `invite.attendance.confirmed`, `invite.attendance.unconfirmed`, `invite.attendance.no-show`, `invite.attendance.geo-confirmed`, `invite.expired`, `invite.reward-unlocked`, `invite.rate-limited`, `invite.plan-limit-reached`, `invite.suppressed`.
* Inbound: `user.profile.updated` (refresh resumes), `agenda.action.completed` (to suggest invites tied to actions), `participation.presence_confirmation.recorded`, `participation.check_in.recorded`, `insights.rank.changed`, `task.completed` (so fulfillment/task projections can refresh deterministically).
* Analytics/CRM Integration: Every fulfillment intent (`invite.fulfillment.step-required`, e.g., pay deposit, upload document) is mirrored to a future account/workspace analytics capability along with contact info so account operators can track outstanding requirements. When tasks complete, that future analytics capability receives `invite.fulfillment.step-completed` events from the relevant producer flow (for example future commerce/checkout handling or Task & Reminder). Attendance-related projections are driven by confirmation/reservation/check-in inputs; `invite.attendance.unconfirmed` is the default unresolved post-event state, while `invite.attendance.no-show` should be explicit/policy-driven rather than automatic. These events tie back to account KPIs and invite reward logic.
* Task & Reminder Integration: As the event time approaches, the invite module emits a `task.intent` with `reminder_type: "invite_checkin"` targeting the invitee to complete the relevant attendance flow. That reminder may deep link to an attendance-confirmation, reservation, or check-in surface depending on policy. When the tenant shares venue coordinates, the participation/check-in flow may request passive location evidence; a successful geo-backed check-in should emit canonical participation events first, after which the invite module may project `invite.attendance.geo-confirmed` for social/account analytics. (Flutter reference: `native_geofence` package can be used during mock/prototype stages to monitor entry/exit events while keeping the invite module decoupled from the specific plugin.) Future enhancement: once we unlock account-profile-to-guest messaging, accepted invitees will be able to opt into push channels—or even lightweight chat rooms—so account profiles and invite trees can coordinate in real time. That capability is deferred beyond v1 and will reuse the Task/Reminder notification rails with additional consent checks.

### 4.5 Metric / Privacy / Workspace Baseline

- North-star metrics remain `credited_invite_acceptances` + `presences_confirmed`.
- `presences_confirmed` is the product/analytics label for successful attendance confirmation or paid reservation activation, regardless of which attendance path produced it.
- `check_ins`, `attendance_outcomes`, `invite_sent`, `share_visits`, and content views are secondary metrics and must not replace the north star in rankings or “Em Alta” logic.
- Future micro-conversions attributable to an invite tree (for example `check_in.recorded`, `promo.requested`, `purchase.completed`) must be modeled as append-only activity facts with bounded lineage snapshots and consumed through indexed projections; they must not require recursive invite-tree reads on hot request paths.
- `phone_hash` is identification-only; it is materialized from backend-normalized verified phone identity during OTP upgrade and never becomes a public people relation by itself.
- Invite recipient identity is Account Profile-scoped: the canonical recipient is `receiver_account_profile_id`, while any acting user who sends or responds on behalf of that profile is separate audit/authorization context.
- `contact_match` is the acquisition layer: it makes a person visible in `Contatos` and invite-eligible when the target remains `discoverable_by_contacts=true` and the resolved target type is `is_inviteable=true`, but it does not grant richer social approval on its own. Release delivery is anchored on explicit contact import. A later onboarding-owned follow-up may additionally reconcile newly canonical identities against hashes previously imported by the viewer.
- `public` users may appear as `full_profile` in allowed social-proof surfaces.
- `friends_only` users are `full_profile`-visible only when the target explicitly approved the viewer by favoriting the viewer's personal Account Profile; reciprocal favorites become the product-level `friend` label.
- unilateral contact matches and direct invite counterparties may receive at most `capped_profile` unless a target-owned favorite already grants `full_profile`; all others contribute only anonymized counts.
- `capped_profile` must not expose avatar/photo or specific accepted-event history; non-approved contexts get only aggregate metrics/social proof.
- Favorites on non-personal account profiles remain bookmark/affinity signals and do not derive `friend` semantics, but they may still become `inviteable` when the target type is `is_inviteable=true` and the viewer-scoped reason is `favorite_by_you` or `favorited_you`.
- `discoverable_by_contacts` is the explicit privacy axis for hash-based discovery, defaults to `true`, and can remain enabled even when public profile visibility is restrictive.
- `contact_groups` are user-private, tag-like organization over in-app inviteable recipients. The same recipient may belong to multiple groups, and multi-group invite selection must deduplicate recipients by canonical recipient identity before invite creation and quota counting. This grouping may include inviteables reached through `contact_match`, `favorite_by_you`, `favorited_you`, or `friend`. Unmatched local contacts are not groupable. Group CRUD is required in V1, but it belongs to dedicated group/friends-management surfaces rather than the invite composer.
- The backend contact-group API enforces membership by the current `GET /contacts/inviteables` set and prunes stale `receiver_account_profile_id` members on read/update, so group state cannot silently retain non-inviteable recipients.
- Inviteable lists must preserve relation tags/reasons and render deduplicated by canonical recipient; default presentation shows all inviteable entries together, while UI filters only change which tags are visible.
- Native app may expose unmatched local contacts as auxiliary local-share targets, but those entries remain outside the canonical inviteable list, outside relation filters, outside `contact_groups`, and absent on web.
- Share-code preview context may be exposed to client event-detail/invite surfaces as an app/session projection only. It is not a new persisted invite lifecycle state; backend source-of-truth still begins at share-code preview and explicit authenticated share-code acceptance.
- When an inviteable recipient ceases to be inviteable, V1 removes the recipient from all `contact_groups` automatically instead of retaining disabled memberships.
- Backend identity-materialization reconciliation may also feed a future inbound suggestion surface labeled `Talvez você conheça`, showing people who had already imported the user's hash. That inbound suggestion is discovery-only by default: it is not `Contato`, not an `inviteable_reason`, not groupable, and only enters the normal inviteable rules after explicit favorite. This future path is owned by `TODO-vnext-onboarding-identity-reconciliation-reflection.md`.
- Workspace analytics are scoped to the managed event/account profile and expose raw invitee identity only on explicit operational/audit surfaces, never on default dashboards.

---

## 5. Gamification Hooks

* **Streak Engine:** Maintains per-user streak documents with counters for consecutive days of invite engagement. Feeds deferred gamification and insights surfaces when those capabilities are promoted.
* **Shareable Badges:** Each accepted invite can mint a badge reference consumed by the Flutter badge component.
* **Leaderboard Source Events:** Emits delta events to the Multidimensional Insights Service with payload `{model_key: "invite_conversion", topic_reference: {type: "user", id: sender_user_id}, metrics: {accepted_invites: 1}}`.

---

## 6. Roadmap Alignment

* Current invite delivery already consumes the backend-owned contract directly; follow-up store-release and fast-follow slices should treat this module as the canonical authority surface rather than phase-era mock scaffolding.
* Native-app external-contact share targets may use WhatsApp deep links as the preferred channel when available, with system-share fallback.
* Account Profile Workspace fast-follow consumes `invite_edges` to expose referral funnels to account operators without duplicating logic. A future account/workspace analytics capability may aggregate invitation performance per plan, quota bucket, and channel to support billing and upsell strategies.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `INV-01` | Approved | Inviter principal is typed (`user|account_profile`) with explicit issuer audit. | Keeps attribution/audit stable across share and direct invites. | Section `2.1 A` + `3.1` |
| `INV-02` | Approved | Duplicate invite prevention is strict by `(tenant,target_ref,receiver,inviter_principal)` key. | Prevents spam/metric inflation. | Section `2.1 B` |
| `INV-03` | Approved | Exactly one credited acceptance exists per `(receiver,target_ref)`; explicit selection is required. | Deterministic conversion and gamification metrics. | Section `2.1 C` |
| `INV-04` | Approved | Quotas/limits are backend-owned and enforceable via `429`. | Client cannot bypass rate/plan controls. | Section `2.1 D` |
| `INV-05` | Approved | Canonical invite target identity is `occurrence_id`. `event_id` is derived parent context only and `occurrence_id = null` is not a valid release write path. | Stabilizes uniqueness, credited acceptance, attendance lookup, mission scope, and Mongo index design around one scheduled experience. | Sections `2.1 B`, `2.1 C`, `3.1`, `4.3` |
| `INV-06` | Approved | Attendance policy enum is `free_confirmation_only | paid_reservation_only | either`; the event chooses one policy and occurrences may override only when the event explicitly allows it. | Gives all invite/attendance flows a single policy vocabulary with a clear event-to-occurrence hierarchy. | Section `2.2` |
| `INV-07` | Approved | Attendance confirmation / reservation state is owned by an adjacent Participation/Attendance domain, not by Invites, Events, or Ticketing. | Keeps social conversion, attendance write ownership, and paid fulfillment cleanly separated. | Sections `2.2`, `3.1` |
| `INV-08` | Approved | Invite acceptance always records social conversion first; attendance confirmation or reservation resolution then follows attendance policy. | Prevents conversion metrics from being coupled to reservation/check-in implementation details. | Section `2.2` |
| `INV-09` | Approved | Attendance policy governance is tenant-owned through `settings.events.attendance`; account-profile event creators may only choose policies inside tenant-approved boundaries. | Keeps event creation aligned with tenant business rules, capabilities, and monetization constraints. | Section `2.2` |
| `INV-10` | Approved | Native app owns grouped invite selection and uses `POST /invites`, `POST /invites/{invite_id}/accept`, and `POST /invites/{invite_id}/decline` as the canonical direct invite mutations. | Stabilizes backend/client contract for direct invite send and explicit inviter selection. | Sections `2.4`, `4`, `4.1` |
| `INV-11` | Approved | Anonymous web invite behavior in V1 is promotion/read-only only; app owns preview-first continuation and authenticated trust-action mutations, while QR-authenticated web follows the normal authenticated posture. | Preserves low-friction growth while preventing anonymous web from becoming a divergent second invite client. | Sections `2.4`, `4.4` |
| `INV-12` | Approved | Default post-event unresolved outcome is `unconfirmed`; `no_show` and `manually_confirmed` are explicit policy/operator outcomes only. | Preserves fairness and analytics integrity. | Sections `2.4`, `4` |
| `INV-13` | Approved | North-star mobilization metrics are `credited_invite_acceptances` + `presences_confirmed`, where `presences_confirmed` normalizes both free confirmations and paid reservations. | Keeps mandate, analytics, rankings, and missions aligned. | Sections `2.4`, `4.5` |
| `INV-14` | Approved | Privacy exposure is viewer-scoped: `friends_only` users reach `full_profile` only when the target explicitly approves the viewer by favoriting the viewer's personal Account Profile, `capped_profile` for unilateral/direct-counterparty contexts, otherwise aggregate/anonymized only. | Aligns social proof with the privacy-with-agency mandate while preserving simple contact-match UX and directional approval. | Sections `2.4`, `4.5` |
| `INV-15` | Approved | Workspace invite visibility is event/account-profile scoped and raw invitee identity is restricted to explicit operational/audit surfaces. | Protects tenant-safe business analytics without overexposing user identity. | Sections `2.4`, `4.5` |
| `INV-16` | Approved | V1 Mongo read-model baseline is `invite_feed_projection` + `principal_social_metrics`; richer projections are evidence-driven additions. | Prevents premature read-model sprawl. | Section `2.4` |
| `INV-17` | Approved | Challenges/missions consume invite and attendance behaviors from outside the invite module via `belluga_missions`. | Keeps reward logic decoupled from invite ownership. | Sections `2.4`, `4.5` |
| `INV-18` | Approved | Future invite-tree result attribution must use bounded lineage snapshots + append-only activity facts + indexed aggregate projections, never request-path graph traversal. | Opens the door for 1st/2nd-level micro-conversion analytics while staying MongoDB-friendly. | Sections `2.5`, `4.5` |
| `INV-19` | Approved | Invite terminal semantics distinguish `superseded` (business-outcome closure) from `suppressed` (policy/governance closure), with explicit `supersession_reason` when superseded. | Prevents attribution ambiguity and keeps policy blocking separate from causal loss. | Sections `2.1 C`, `2.2`, `3.1` |
| `INV-20` | Approved | `contact_match` is acquisition-only: a match resolves the person through the personal Account Profile, makes the person visible in `Contatos`, and allows invite targeting without requiring favorite first. | Separates phone-hash identity resolution from social approval while preserving low-friction invite targeting. | Sections `2.4`, `4.5` |
| `INV-21` | Approved | `contact_groups` are user-private tag-like groupings over in-app inviteable recipients; memberships are many-to-many and multi-group invite selection deduplicates canonical recipients before invite creation/quota counting. | Preserves simple invite organization without conflating groups with privacy or friendship semantics. | Sections `2.4`, `4.5` |
| `INV-22` | Approved | Invite surfaces are gated by `account_profile_type.capabilities.is_inviteable`; `favorite_by_you` and `favorited_you` are valid inviteable reasons whenever the resolved target type is inviteable. | Keeps invite semantics in the registry instead of ad hoc type-specific rules. | Sections `2.4`, `4.5` |
| `INV-23` | Approved | `discoverable_by_contacts` is a separate instance-level privacy axis for hash discovery, defaults to `true`, and may be persisted before the privacy-settings UI exists. | Makes “private but discoverable by contacts” explicit instead of accidental. | Sections `2.4`, `4.5` |
| `INV-24` | Approved | `/convites/compartilhar` default presentation is one deduplicated inviteable list with Discovery-style relation filters built from preserved `source_tags` / `inviteable_reasons`. | Gives the client one coherent invite composer while preserving explainable filtering semantics. | Sections `2.4`, `4.1`, `4.5` |
| `INV-25` | Approved | Unmatched local contacts are native-app-only external share targets, not canonical inviteable rows; they are not groupable, not relation-filtered, and not exposed on web. | Separates in-app relationships from local-only share affordances without losing the external invite path. | Sections `2.4`, `4.1`, `4.4`, `4.5` |
| `INV-26` | Approved | `/convites/compartilhar` is action-first: rows prioritize immediate invite actions, groups support direct invite-all plus optional drill-in, and the screen baseline excludes a home-style horizontal group rail. | Keeps the invite composer lightweight and optimized for fast invite execution instead of selection-heavy browsing. | Sections `2.4`, `4.1` |
| `INV-27` | Approved | Canonical identity materialization may reconcile prior imports into outbound `contact_match`, may feed a future inbound `Talvez você conheça` suggestion, and may later power informational "contact entered the app" notifications, but those inbound/discovery consumers are not `Contato` or inviteable until explicit favorite. | Preserves late-join reconciliation without polluting the inviteable/contact model or inventing a new implicit approval edge. | Sections `2.4`, `4.5` |
| `INV-28` | Approved | `contact_groups` require CRUD in V1, but group management belongs to dedicated group/friends-management surfaces rather than `/convites/compartilhar`; exact UX may be refined via Stitch studies. | Keeps the invite composer lightweight while still freezing the required business capability. | Sections `2.4`, `4.1`, `4.5` |
| `INV-29` | Approved | When a grouped recipient ceases to be inviteable, V1 removes that recipient from `contact_groups` automatically instead of retaining disabled memberships. | Favors the simplest lifecycle policy for the first version and avoids stale bulk-invite targets. | Sections `2.4`, `4.5` |
| `INV-30` | Approved | The canonical invite recipient surface is `Account Profile`, not raw `User`; release delivery performs an explicit breaking launch cutover of direct invites, persisted invite edges, and share materialization/acceptance away from `receiver_user_id`, while future memberships authorize acting users separately from recipient identity. | Makes the launch cutover explicit and prevents pre-production user-targeting from surviving as an accidental contract. Invites, favorites, and friends have not been released to production, so backward compatibility is not required. | Sections `2.1 B`, `2.1 C`, `3.1`, `4.1`, `4.3`, `4.5` |
| `INV-31` | Approved | Invite acceptance is independent from event capacity or later fulfillment availability; those constraints belong to downstream attendance/reservation/check-in flows and do not redefine the social invite decision. | Separates social conversion from fulfillment/capacity concerns and prevents invite semantics from being overloaded by operational availability. | Sections `2.2`, `4.1`, `4.5` |
| `INV-32` | Approved | V1 invite lifecycle does not include `snooze` / `Decide later`; reminder follow-up for that branch is removed until a future contract explicitly reintroduces it. | Removes half-defined terminal states and keeps the release contract aligned with the actually supported lifecycle. | Sections `2.2`, `3.1`, `4`, `4.5` |
| `INV-33` | Approved | First-production social capabilities carry a zero-backward-compatibility review and promotion rule: reviewers must not request compatibility for pre-release invite, favorite, friend, contact-group, or contact-match inviteable behavior unless a governing TODO explicitly reopens that burden. | Keeps audit and promotion gates aligned with launch scope and prevents non-production historical shapes from blocking release. | `project_constitution.md`, Sections `2.1 B`, `2.4`, `4.1`, `4.5` |
| `INV-34` | Approved | Pending invite/social user-linked streams participate in Flutter post-auth hydration after registered identity emission; invite screens must not infer final empty state before repository refresh has run for the current user. | Prevents stale anonymous/pre-login invite state after OTP upgrade while keeping late identity reflection separate from release-critical invite hydration. | Sections `4.1`, `4.4`; `foundation_documentation/modules/flutter_client_experience_module.md` `FCX-12` |
| `INV-35` | Approved | Share-code preview context may be held in Flutter app/session memory keyed by `share_code + occurrence_id` so event detail can show a pending invite effect before explicit acceptance. No persisted local storage, remote intent entity, or invite edge is created before authenticated `POST /invites/share/{code}/accept`. | Preserves invite continuity through preview -> details without adding a new launch backend model or weakening the occurrence-scoped acceptance contract. | Section `2.4`; `TODO-store-release-invites-occurrence-target-migration.md` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-invites-implementation.md` | Invite backend/client flow hardening | Completed (2026-03-12) | `2.1`, `3`, `4` | Canonical stream for invite delivery decisions. |
| `TODO-store-release-web-to-app-conversion-gate.md` | Android release closure for web-to-app conversion path | In progress | `4.3`, `4.4` | Canonical policy is already promoted; this TODO owns the remaining release-gate validation and promotion-boundary readiness. |
| `TODO-vnext-onboarding-identity-reconciliation-reflection.md` | Late identity-materialization reconciliation + advisory reflection surfaces | Pending follow-up | `2.4`, `4.1`, `4.5` | Owns post-onboarding reconciliation timing plus `Talvez você conheça` / informational lifecycle hints. |
| `TODO-store-release-minimal-friends-and-favorites-mvp.md` | Store-release contacts/favorites/friends core | In progress | `2.5`, `3.3`, `4.5` | Promotes contact-match, reciprocal-friend, and viewer-scoped exposure behavior into the release lane without requiring full package convergence first. |
| `TODO-store-release-invites-occurrence-target-migration.md` | Store-release occurrence-scoped invite and participation cutover plus share-code session-context addendum | Reopened addendum | `2.1`, `2.4`, `3.1`, `4.5`, `7` | Owns occurrence target identity and the Store Release app-session share-code context before explicit acceptance. |
| `TODO-store-release-funnel-metrics-validation.md` | Store-release funnel metrics validation | Promotion lane candidate | `4.4` | Freezes release-facing event/property proof for web-to-app conversion, deferred capture, authenticated invite acceptance, and first favorite actions; post-release sink/readback verification moved to the dedicated hardening TODO. |
| `TODO-vnext-referral-result-attribution.md` | Future lineage-based downstream result attribution | In progress | `2.5`, `4.5` | Defines Mongo-safe activity-fact and projection strategy for 1st/2nd-level invite-tree results. |
