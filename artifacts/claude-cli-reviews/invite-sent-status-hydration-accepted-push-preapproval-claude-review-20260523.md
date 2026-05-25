# Claude CLI Review: Invite Sent Status Hydration and Accepted Push Presentation

- **Artifact kind:** `external_auditor_review`
- **Authoritative:** `false`
- **Created:** `2026-05-23`
- **Related TODO:** `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-sent-status-hydration-and-accepted-push-presentation.md`

## Claude CLI Findings

### Blockers
- `B1` IDOR on inviter scope: inviter identity must be derived from the authenticated token server-side, with no client-controlled override.
- `B2` Event/occurrence ownership/relationship authorization must be explicit; a tenant user must not be able to probe arbitrary event/occurrence invite state.
- `B3` Cross-tenant isolation test is required.
- `B4` Recipient identity contract is ambiguous; the payload must declare a single unambiguous canonical recipient key and optimistic entries must use the same key.
- `B5` Declined/withdrawn/expired statuses must be in the contract and handled on Flutter restart/reinvite paths.
- `B6` The sent-invite list must define pagination or a result cap to avoid unbounded eager-loaded lists.
- `B7` Multi-occurrence status aggregation must be explicit; event-only aggregation must not leak statuses across occurrences.

### Non-Blocking Notes
- Add profile metrics negative test with `sent=2`, `accepted=1`, `received=3` to prove sender-side metrics.
- Define behavior when `invite_accepted` arrives before sent-invite list hydration.
- Ensure duplicate `invite_accepted` push delivery is idempotent.
- Cover background-to-foreground tap path.
- Cover restart without receiving `invite_accepted`; canonical backend hydration must still fix state.
- Include multi-recipient reconciliation with mixed pending, accepted, and optimistic-only recipients.
- Define 404/error/empty response distinction.
- Specify ISO 8601 UTC timestamp format.
