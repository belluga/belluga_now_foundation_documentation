# Documentation: Onboarding Flow Module

**Version:** 0.1  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Scope

The Onboarding Flow module (MOD-307) owns the full first-time experience across the app. It orchestrates how a user—often entering through an invite link—progresses from invite context to account creation, preference capture, and location initialization. While the Invite & Social Loop module handles the graph logic, onboarding governs the UI state machine, partial profile storage, and cross-module handoffs.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/task_and_reminder_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-funnel-metrics-validation.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-onboarding-identity-reconciliation-reflection.md`

## 2. Entry Paths

1. **Invite Acceptance Path**
    * Steps:
        * User lands via `inviteCode` deep link → sees invite context (sender, event, incentives).
        * Invite preview is available in app with anonymous identity (progressive profiling), but `Accept`/`Decline` are trust mutations and require a registered/authenticated identity before execution.
        * The anonymous app baseline stays explicit after preview: feed browsing, map browsing, and favorites remain available without forced login; invite accept/decline, `send_invite`, `/profile`, and presence/check-in remain authenticated boundaries.
        * Screen flows continue with:
            1. Minimal pre-auth profile context only when needed; authenticated upgrade, when required, is phone-OTP only via backend-owned `POST /auth/otp/challenge` + `POST /auth/otp/verify`.
            2. Contact import prompt (`import contacts to share with friends`) wired to Invite module’s endpoint.
            3. Optional “Find friends” preview from `friend_resumes` to encourage immediate viral sharing.
        * After contact import (or skip), user transitions to preference capture + location consent steps to personalize home/map.
    * Integration: Canonical share-preview acceptance uses `POST /invites/share/{code}/accept` only after authenticated identity is available; anonymous attempts must reject with `401 auth_required`. Authenticated continuation may still use `/invites/share/{code}/materialize` and `/invites/{invite_id}/accept|decline` when explicitly required. OTP verification must send the current anonymous identity id so backend merge preserves invite attribution/history before issuing the registered phone token. `POST /contacts/import` remains optional when the user opts to import contacts. Invite metadata is stored locally so preference recommendations can reference the same event/account profile.

2. **Invite Decline / No Invite Path**
    * Steps:
        * User declines invite (or arrives without one) → flows into preference capture wizard.
        * Prompts include “What are you looking for today?” categories, location consent, and optional contact import later in the flow.
    * Produced values feed the Map module (initial filters) and local home composition logic.
    * Integration: When user declines an invite for an event, onboarding triggers `invite.declined` for that invite and optionally suggests alternative events via home/map modules.

## 3. Core Responsibilities

1. **Invite Context Transfer**
    * Store `invite_context_snapshot` with sender info, event metadata, account profile incentives.
    * Provide this snapshot to downstream modules to pre-populate CTAs (“Join João at Praia Jam”).

2. **Partial Profile Persistence**
    * `onboarding_sessions` documents track progress (steps completed, deferred tasks, associated invite).
    * Sessions expire after defined inactivity windows; Task & Reminder module receives `task.intent` when users exit mid-way.

3. **Preference Capture**
    * MVP stores preferences locally (categories, tags, location preference, radius default).
    * Backend preference persistence is deferred post-MVP.
    * Push onboarding steps must only enable CTA when validation passes:
        * `question_type=text` uses the configured validator; CTA stays disabled while it returns an error string.
        * `selector` with `selection_ui=inline` uses `selection_mode`; `single` requires one selection, `multi` requires `min_selected`.
        * `selector` with `selection_ui=external` uses an external selector sheet; its "Continuar" CTA stays disabled until the selector selection requirement is satisfied.
    * Each step must provide at least one of `title`, `body`, or `image` so the UI always has content to render.
    * Step bodies accept a **sanitized HTML subset** (auto-detected by tags) and fall back to Markdown/plain text otherwise.
        * Allowed tags: `p`, `br`, `strong`, `em`, `u`, `span` (style: `color`, `font-size`, `font-weight`), `ul`, `ol`, `li`, `img` (`src`, `width`, `height`, `alt`).
        * HTML is stripped on the backend before persistence/response for immediate feedback.
        * Non-HTML bodies are centered in the push UI.
    * Plugin gate handling must remain generic: no app-specific gate names; inline selectors avoid gate auto-skip when selection constraints (e.g., `min_selected`) are present.
    * Dynamic onboarding answers are **callback-driven**: the push flow stores nothing (no disk or in-memory), and gate checks resolve current selections via app-provided callbacks.
    * Option pre-selection is callback-driven using `OptionItem.isSelected` (app-provided), so external selectors can surface already-selected items without plugin persistence.
    * Message-level close behavior uses `closeBehavior` (enum: `after_action`, `close_button`). `after_action` closes the UI after last-step actions; `close_button` keeps the UI open and shows a close (X) on the last step.
    * For gated steps, custom actions always re-check the gate and advance when it passes; `continue_after_action` applies only to non-gated CTA behavior.
    * Inline selector step content scrolls as a single column to prevent overflow on smaller screens.
    * External selector "open" actions are never blocked by gates; gate checks only block continuing after selection.
    * The last-step close (X) respects SafeArea so it does not overlap system bars.

4. **Location Consent & Initialization**
    * Step ensures location permissions are requested once, with tenant-specific privacy copy.
    * On acceptance, onboarding initializes `LocationRepository`/`UserLocationService` so other modules can read immediately.

5. **Task & Reminder Hooks**
    * When user skips contact import, preferences, or location, onboarding emits `task.intent` event specifying which step needs follow-up. The Task module schedules push reminders (“Finish tailoring your experience”).

6. **Gamification / Rewards**
    * Early completion badges (e.g., “Hometown Host”) and invite boosts for users who import contacts during onboarding.

7. **Identity Materialization Reflection (Follow-up)**
    * After a user's canonical phone identity becomes stable through the approved OTP/onboarding path, onboarding owns the follow-up handoff that may trigger late reconciliation against hashes previously imported by other viewers.
    * The immediate post-OTP app handoff is separate from the late reflection lane: once Flutter emits the registered identity, the application shell must run post-auth hydration for release-critical user-linked repositories such as favorites, confirmed occurrences, and pending invites.
    * OTP transport is not an onboarding-owned provider flow: delivery is queued by Laravel jobs through tenant outbound integration webhook settings, with WhatsApp preferred and OTP-specific URL optional. Public app bootstrap exposes only derived delivery flags, such as SMS fallback availability, and must not expose provider webhook URLs.
    * This follow-up may later feed advisory reflection surfaces such as `Talvez você conheça` and informational lifecycle notifications like "contact entered the app".
    * These reflection surfaces must remain discovery-only until explicit favorite promotes the relationship into the normal inviteable rules.

8. **Release Identity Funnel Telemetry**
    * Android store-release validation requires explicit evidence for `app_auth_wall_triggered`, `app_signup_completed`, `otp_challenge_started`, `otp_verified`, and `auth_merge_completed`.
    * `otp_challenge_started` is a pre-auth event and must use a non-user actor such as `{type: phone_otp_challenge, id: challenge_id}` while omitting empty `user_id` metadata.
    * `otp_verified` must include `identity_state`; `auth_merge_completed` must include `source_count` and `source_kind=anonymous` when anonymous ids are merged into the registered phone identity.
    * These events are validation-critical for release judgment, but OTP delivery itself remains job/webhook-owned by Laravel outbound integration settings.

## 4. Integration with Invite Module

* After `invite.accepted`, onboarding listens for `invite.accepted.contacts-import-triggered` to mark the contact-import step as completed automatically.
* When `invite.declined` occurs, onboarding respects that decision and still directs the user to preference capture so they can find other events.
* On finishing onboarding, emit `onboarding.completed` event referencing whether the user entered via invite or organic path. This helps Invites/Gamification calibrate rewards.
* After successful OTP verification, onboarding hands off to Flutter's post-auth identity hydration contract instead of requiring each downstream screen to reload itself. Downstream modules that depend on registered user state must expose repository refresh hooks and register as hydration consumers.
* The follow-up lane `TODO-vnext-onboarding-identity-reconciliation-reflection.md` owns any late reconciliation/reflection behavior that should happen after canonical identity materialization. Invite/social modules remain the source of relationship rules, while onboarding owns the materialization handoff timing.

## 5. Current V1 Constraints

1. Invite acceptance does not encode event capacity or later fulfillment availability. Capacity, reservation availability, and check-in feasibility are downstream concerns and must not redefine the social invite decision.
2. If an invite is already expired when the user reaches the decision point, onboarding falls back to preference/discovery progression without auto-decline or suppression.
3. V1 onboarding remains one tenant-public user flow. Account/promoter/workspace-specific onboarding variants are post-MVP and do not belong to the current release lane.

---

*Next Action:* Flesh out collection schemas (`onboarding_sessions`, `preference_snapshots`), API details, and UI sequence diagrams under the frozen V1 constraints above.

## 6. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `ONB-01` | Approved | Invite-first and organic paths share one onboarding state machine with contextual branching. | Prevents duplicated onboarding implementations. | Sections `2`, `3` |
| `ONB-02` | Approved | Contact import and location consent are optional but produce follow-up task intents when skipped. | Guarantees recoverability without blocking acquisition. | Sections `3`, `4` |
| `ONB-03` | Approved | Dynamic push onboarding answers are callback-driven (no plugin persistence). | Keeps onboarding plugin generic and stateless. | Section `3` |
| `ONB-04` | Approved | Anonymous exploration remains allowed before onboarding completion on the V1 anonymous app baseline; onboarding steps may defer through task intents instead of gating feed/map/favorites. | Aligns onboarding with the web-to-app promotion policy and avoids reintroducing forced-auth/forced-onboarding friction. | Sections `2`, `3` |
| `ONB-05` | Approved | Late identity-materialization reconciliation and its advisory reflection surfaces are onboarding-owned follow-up behavior. They may later trigger outbound reconciliation for prior importers, but inbound surfaces such as `Talvez você conheça` remain discovery-only until explicit favorite. | Keeps post-onboarding identity materialization aligned with onboarding lifecycle ownership without polluting release-critical social scope. | Sections `3`, `4` |
| `ONB-06` | Approved | Normal invite refusal in onboarding maps to `invite.declined`; `invite.suppressed` remains reserved for policy/governance-only closure and is not the default user-decline outcome. | Keeps onboarding aligned with the canonical invite lifecycle semantics. | Sections `2`, `4` |
| `ONB-07` | Approved | Invite acceptance is independent from event capacity/fulfillment availability; expired invite resolution falls back to onboarding progression without auto-decline or suppression. | Prevents onboarding from overloading invite semantics with downstream operational availability. | Sections `2`, `5` |
| `ONB-08` | Approved | V1 onboarding is a shared tenant-public user flow; account/promoter/workspace-specific onboarding variants are post-MVP. | Removes role-specific onboarding ambiguity from the current release lane. | Section `5` |
| `ONB-09` | Approved | Successful OTP/authenticated upgrade hands off to Flutter post-auth hydration for registered user-linked state; late identity-materialization reflection remains a separate follow-up lane. | Prevents login completion from leaving favorites, confirmations, or pending invites stale while preserving the narrower VNext reflection boundary. | Sections `3`, `4`; `foundation_documentation/modules/flutter_client_experience_module.md` `FCX-12` |

## 7. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-invites-implementation.md` | Invite acceptance/contact-import flow contracts | Completed (2026-03-12) | `2`, `4`, `6` | Main authority for invite/onboarding boundary. |
| `TODO-store-release-android.md` | Android release orchestration authority | In progress | `1.1`, `5`, `7` | Replaces the former MVP release orchestrator as the active sequencing authority. |
| `TODO-store-release-phone-otp-auth-and-contact-match.md` | Phone-OTP upgrade and identity baseline | In progress | `2`, `3`, `4`, `6`, `7` | Freezes the authenticated upgrade path that onboarding must hand off into, including the immediate post-auth hydration handoff for registered user-linked repositories. |
| `TODO-store-release-minimal-friends-and-favorites-mvp.md` | Minimal user-level friends/favorites release contract | In progress | `2`, `4` | Owns the release-facing friend preview/social-proof contract referenced by onboarding. |
| `TODO-store-release-funnel-metrics-validation.md` | Release funnel metrics validation | In progress | `3`, `7` | Freezes identity funnel event/property evidence for auth wall, signup, OTP challenge, OTP verification, and anonymous merge. |
| `TODO-vnext-onboarding-identity-reconciliation-reflection.md` | Late identity-materialization reconciliation + advisory reflection surfaces | Pending follow-up | `3`, `4`, `6`, `7` | Owns post-onboarding reflection (`Talvez você conheça`, informational lifecycle hints) after canonical identity materialization. |
