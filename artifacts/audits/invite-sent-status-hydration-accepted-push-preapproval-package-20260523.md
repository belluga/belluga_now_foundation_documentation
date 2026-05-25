# Pre-Approval Bounded Audit Package: Invite Sent Status Hydration and Accepted Push Presentation

- **Artifact kind:** `bounded_audit_package`
- **Authoritative:** `false`
- **Created:** `2026-05-23`
- **Related TODO:** `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-sent-status-hydration-and-accepted-push-presentation.md`
- **Audit stage:** pre-implementation / pre-`APROVADO`

## Review Objective
Audit whether the TODO contract is pointing in the correct direction before implementation. The desired outcome is not code review. The desired outcome is identifying missing blockers, missing tests, security/privacy gaps, performance risks, consumer-surface omissions, or scope contradictions that must be corrected in the TODO before implementation starts.

## Frozen Problem Statement
Production evidence on `2026-05-23` shows direct invite push delivery is healthy through backend queue, FCM credential bootstrap, and FCM provider acceptance, but Flutter still presents stale or incorrect sent-invite state.

Key facts:
- Backend invite `6a11addb26fc614b1d0fc558` for event `6a0d1c223d7e704d390d5d28` and occurrence `6a0d1c233d7e704d390d5d29` was accepted at `2026-05-23T13:40:37.428000Z`.
- Backend canonical state for that event/occurrence is `1 pending` and `1 accepted`, not `2 pending`.
- Flutter showed both invitees as pending, and after app restart enabled invite buttons for the same people.
- Code inspection found `InvitesRepository.getSentInvitesForOccurrence()` returns only local in-memory `sentInvitesByOccurrenceStreamValue`; it does not hydrate from backend.
- `invite_accepted` push was authored/sent and FCM accepted; app recorded delivered, but the user did not perceive a visible acceptance push.
- Flutter `InviteAwarePushMessagePresenter` suppresses generic presentation for `Seu convite foi aceito`, and no invite-specific visible fallback has been confirmed.
- Profile social metrics are conceptually wrong: they should be sender-side invite metrics, not received pending invites or own attendance confirmations.

## Approved Direction Already Captured In TODO
- Sent invite status must be canonical backend state, not Flutter session-only state.
- Flutter may optimistically mark sent invites pending after send, but must reconcile from backend.
- `invite_accepted` must update inviter-side sent invite status for the affected occurrence.
- `invite_accepted` foreground presentation must be invite-specific; restoring generic Push Handler presentation is not acceptable if it reintroduces generic screens.
- Profile social metrics represent sender-side invite outcomes:
  - outlined invite icon: invites sent;
  - filled invite icon: sent invites accepted;
  - future check-in metric: out of this slice.

## Planned Contract / Scope
- Add or expose a Laravel authenticated read contract that returns canonical sent invite status for the authenticated inviter scoped by event/occurrence.
- Include recipient profile/user identity, display name, avatar URL, status, sent time, and responded time.
- Distinguish at least `pending`, `accepted`, `declined`, and intentionally hidden terminal/superseded statuses.
- Update Flutter `InvitesRepository.getSentInvitesForOccurrence()` to hydrate from backend instead of relying only on local state.
- Preserve optimistic update after `sendInvites()`, but reconcile with canonical backend status.
- Update Flutter push runtime so `push_type=invite_accepted` updates `sentInvitesByOccurrenceStreamValue`.
- Ensure foreground `invite_accepted` is visible through invite-specific UX without generic Push Handler screen fallback.
- Keep `invite_received` tap routing behavior unchanged.
- Correct Laravel/Flutter profile metrics so they display `invites_sent` and `invites_accepted`.

## Explicit Out Of Scope
- FCM private-key newline normalization.
- Push icon/rich image behavior.
- Historical push replay.
- Invite acceptance business-rule changes.
- Broad realtime/SSE replacement.
- Check-in metric implementation.

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Required Evidence | Waiver |
| --- | --- | --- | --- |
| Laravel sent-invite status read contract | Flutter invite repository/event invite share flow | Backend feature tests plus Flutter repository/controller tests proving restart hydration, summary counts, and duplicate-invite disablement | none |
| Laravel `me`/profile metrics payload | Flutter profile DTO/controller/UI | Backend payload tests plus Flutter DTO/controller tests proving `invites_sent`/`invites_accepted` mapping and no use of `pending_invites`/`confirmed_events` as social metrics | none |
| `invite_accepted` push payload/runtime handling | Flutter push runtime, invite share/event detail state, Android foreground/background notification path | Flutter push/repository tests plus device/manual validation that inviter device receives/reacts and tap routing remains invite/event-aware | none |

## Fail-First Test Targets
Laravel:
- `SentInviteStatusesTest::test_authenticated_inviter_can_fetch_pending_and_accepted_sent_invites_for_occurrence`
- `SentInviteStatusesTest::test_sent_invite_statuses_are_scoped_to_current_tenant_and_authenticated_inviter`
- `SentInviteStatusesTest::test_sent_invite_status_payload_contains_recipient_identity_status_and_timestamps`
- `MeProfileSocialMetricsTest::test_me_profile_exposes_sender_side_invites_sent_and_invites_accepted`
- `MeProfileSocialMetricsTest::test_me_profile_social_metrics_do_not_use_pending_received_invites_or_confirmed_events`

Flutter:
- `getSentInvitesForOccurrence hydrates canonical backend statuses when local sent-invite cache is empty`
- `sendInvites keeps optimistic pending but backend hydration reconciles accepted recipients`
- `applyInvitePushPayload invite_accepted marks matching sent invite accepted for occurrence`
- `canonical pending and accepted sent invites disable inviting the same recipients after restart`
- `summary uses canonical pending and accepted counts instead of local-only pending state`
- `profile social metrics map invites_sent and invites_accepted to outlined and filled invite metrics`
- `profile social metrics ignore pending_invites and confirmed_events for social invite metrics`
- `invite_accepted foreground path is invite-specific and visible without generic Push Handler screen fallback`

Runtime/device:
- Send invite to a real device, accept on receiver, verify inviter receives/reacts to `invite_accepted`.
- Close/reopen inviter app, verify canonical status remains hydrated, counts are correct, and same recipients cannot be invited again.
- Keep direct invite push E2E through real FCM acceptance green.

## Known Risk Areas To Challenge
- Endpoint authorization: authenticated inviter + tenant scoping must prevent leaking invite state for other inviters/tenants.
- Identity matching: Flutter must match backend recipient identity to existing `InviteableRecipient` identity, including account profile id vs account user id.
- Status semantics: pending, accepted, declined, superseded/stale handling must not re-enable invite buttons incorrectly.
- UI flow: `invite_accepted` must become visible without routing through the generic Push Handler screen flow.
- Performance: sent-status hydration must not introduce unbounded event-wide scans, N+1 profile/avatar lookups, or page-walking in Flutter.
- Tests: mocks must not hide the production failure mode of local-only state, backend hydration, and real FCM acceptance.

## Requested Auditor Output
Return JSON compatible with `subagent-review-result-v1`. Findings should be blockers only when they expose a concrete pre-implementation contract/test gap. Non-blocking suggestions should be low severity or omitted.
