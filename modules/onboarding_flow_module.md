# Documentation: Onboarding Flow Module

**Version:** 0.1  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Scope

The Onboarding Flow module (MOD-307) owns the full first-time experience across the app. It orchestrates how a user—often entering through an invite link—progresses from invite context to account creation, preference capture, and location initialization. While the Invite & Social Loop module handles the graph logic, onboarding governs the UI state machine, partial profile storage, and cross-module handoffs.

## 2. Entry Paths

1. **Invite Acceptance Path**
    * Steps:
        * User lands via `inviteCode` deep link → sees invite context (sender, event, incentives).
        * `Accept` action immediately confirms the invite (even before full account creation) and emits `invite.accepted`.
        * Screen flows continue with:
            1. Minimal identity capture (name + email/phone).
            2. Contact import prompt (`import contacts to share with friends`) wired to Invite module’s endpoint.
            3. Optional “Find friends” preview from `friend_resumes` to encourage immediate viral sharing.
        * After contact import (or skip), user transitions to preference capture + location consent steps to personalize home/map.
    * Integration: Calls `/invites/share/{code}/accept` to confirm the invite, then uses `POST /contacts/import` if the user opts to import contacts. Invite metadata is stored locally so preference recommendations can reference the same event/partner.

2. **Invite Decline / No Invite Path**
    * Steps:
        * User declines invite (or arrives without one) → flows into preference capture wizard.
        * Prompts include “What are you looking for today?” categories, location consent, and optional contact import later in the flow.
    * Produced values feed the Map module (initial filters) and local home composition logic.
    * Integration: When user declines an invite for an event, onboarding triggers `invite.suppressed` for that event and optionally suggests alternative events via home/map modules.

## 3. Core Responsibilities

1. **Invite Context Transfer**
    * Store `invite_context_snapshot` with sender info, event metadata, partner incentives.
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

## 4. Integration with Invite Module

* After `invite.accepted`, onboarding listens for `invite.accepted.contacts-import-triggered` to mark the contact-import step as completed automatically.
* When `invite.suppressed` occurs, onboarding ensures future prompts respect the suppression but still directs the user to preference capture so they can find other events.
* On finishing onboarding, emit `onboarding.completed` event referencing whether the user entered via invite or organic path. This helps Invites/Gamification calibrate rewards.

## 5. Open Questions

1. Should we allow anonymous exploration (limited map access) before completing onboarding, or do we gate all main surfaces until preferences/location are captured?
2. How do we handle invite acceptance when the event is full or expired mid-onboarding? (Fallback suggestions, auto-decline plus onboarding path.)
3. Do we need separate onboarding variants for partner/promoter roles, or do they share the same core flow with role selection prompts?

---

*Next Action:* Flesh out collection schemas (`onboarding_sessions`, `preference_snapshots`), API details, and UI sequence diagrams once we finalize the answers above.
