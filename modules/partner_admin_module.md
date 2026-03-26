# Documentation: Account Profile Admin & Workspace Module

**Version:** 0.1 (Placeholder)  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

Captures the intent to deliver an Account Profile Admin/Workspace experience where landlords and account operators can manage invites, offers, media, and analytics. This module will be defined once the tenant-facing experience is fully specified so requirements flow from actual consumer workflows.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/partner_catalog_and_offer_module.md`
  - `foundation_documentation/modules/partner_analytics_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/vnext_slices/TODO-vnext-tenant-user-account-profile-area.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`

## 2. Planned Scope (to be detailed later)

1. **Invite Campaign Management:** Create/share invites, monitor quotas, handle suppression lists.
2. **Offer & Media Authoring:** CRUD for account profiles, offers, availability windows, photo/video galleries.
3. **Task Inbox:** View/respond to user tasks (document requests, payment approvals, attendance confirmations).
4. **Analytics Dashboards:** Surface metrics from the Account Profile Analytics module (conversion funnels, attendance, plan usage).
5. **Notification Center:** Configure account-profile alerts (when quotas hit limits, when attendees check-in, etc.).

---

## 2.1 V1 Addendum: Account Profile Workspace Minimum (Event Invites)

Even in V1, we must support operational reality:
- Landlord/admin users create **account profiles** with a minimal free plan (so every event/participant has a stable `account_profile_id` from day one).
- Multiple users can manage an account profile, and a user can manage multiple profiles.
- Account profiles that create/host events need invite metrics per event (who invited, accepted counts, etc.) to power Challenges/Gamification.

### A) Account Profile Memberships (Deferred — post‑MVP)

**Data structure: `account_profile_memberships`**
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "account_profile_id": "ObjectId()",
  "user_id": "ObjectId()",
  "role": "String",
  "permissions": {
    "can_invite": "Boolean",
    "can_manage_events": "Boolean",
    "can_view_metrics": "Boolean",
    "can_manage_members": "Boolean",
    "can_manage_billing": "Boolean"
  },
  "status": "String",
  "invited_at": "Date",
  "accepted_at": "Date",
  "created_at": "Date",
  "updated_at": "Date"
}
```

**Role suggestions (defaults; subject to revision):**
- `owner`: all permissions `true`
- `admin`: all `true` (optionally restrict `can_manage_billing`)
- `manager`: `can_invite`, `can_manage_events`, `can_view_metrics`
- `analyst`: `can_view_metrics`
- `staff`: none by default; enable explicitly

**Status:** `invited`, `active`, `suspended`

**Landlord override:** landlord users can be granted platform-level override permissions, but still record `issued_by_user_id` / audit fields when acting on behalf of account profiles. Memberships remain **deferred post‑MVP**.

### B) Event Invite Metrics (Account Profile Facing)

**Access boundary (agreed):**
- Event invite metrics are visible to account operators once memberships land; in MVP, metrics remain landlord/admin-only.
- Metrics are not visible to “any inviter”; they are scoped to the event host/managing account profile.
- Default analytics surfaces should expose event-level aggregates and inviter-principal breakdowns first; raw invitee identity belongs only to explicit operational/audit lists, not default dashboards.
- `friends_only` users still count toward metrics, but their identity should remain anonymized outside direct operational handling where access is justified.

**Metrics required for Challenges/Gamification (per event):**
- Per inviter principal: `sent`, `viewed`, `accepted (credited)`, `declined`, `closed_duplicate`, plus optional attendance/check-in counts.
- Per issuer user (audit): which user issued invites on behalf of the account profile (`issued_by_user_id`) and their counts.

### C) V1 Screens (Minimum)

1. **Account Profile Workspace Home**
   - List account profiles the user can manage, plus quick metrics (quota remaining, active campaigns).
2. **Account Profile Members**
   - Invite/remove users, set role, toggle key permissions (especially `can_invite`, `can_view_metrics`).
3. **Event Invite Metrics**
   - For a selected event: per-inviter breakdown + drill-down list.
4. **Account Profile Plan / Limits (Read-only initially)**
   - Show current plan, quotas, reset time, and upgrade CTA.

## 3. Next Action

Defer detailed documentation until the tenant modules (Map, Invite, Agenda, Transaction Bridge) stabilize. Account workspace requirements will be inferred directly from the data contracts and events defined there.

## 4. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `PAD-01` | Approved | Workspace is scoped by `account_profile_id` and role/permission maps, not by invite sender alone. | Enforces tenant-safe operational boundaries. | Section `2.1 A` |
| `PAD-02` | Approved | Membership model exists as post-MVP target; MVP keeps landlord/admin-assigned operations. | Avoids fake permissions while preserving auditability. | Sections `2.1 A`, `2.1 C` |
| `PAD-03` | Approved | Invite metrics for workspace are host/account-profile scoped. | Supports gamification/challenges without cross-account leakage. | Section `2.1 B` |
| `PAD-04` | Approved | Default workspace dashboards show aggregates and inviter-principal breakdowns first; raw invitee identity is restricted to explicit operational/audit lists. | Balances business visibility with privacy and tenant-safe handling. | Section `2.1 B` |

## 5. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-vnext-tenant-user-account-profile-area.md` | Workspace scope and ownership model | In progress | `1.1`, `2.1`, `4` | Canonical stream for workspace boundary decisions. |
| `TODO-v1-account-profile-ui.md` | Account profile management UX contract | In progress | `2`, `2.1 C` | UI flow authority for workspace minimum. |
