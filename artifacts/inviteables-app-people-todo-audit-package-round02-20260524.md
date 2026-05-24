# Inviteables App People TODO Audit Package - Round 02 - 2026-05-24

## Scope
Pre-implementation audit package for:

`foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md`

Round 02 validates whether round 01 blockers were integrated into the TODO contract.

## Round 01 Resolution Reference
`foundation_documentation/artifacts/audits/inviteables-app-people-todo-preimplementation-20260524/round-01/resolution.md`

## Integrated Corrections
- Canonical GET path is now named: `/contacts/inviteables` reads only `inviteable_people_projection`.
- Old read-time assembler fate is explicit: it must be converted to projection reader or moved behind the materialization/write boundary; controllers and GET services cannot call a source assembler.
- Projection writes now have one canonical idempotent materializer/refresher boundary; producer hooks may call/enqueue that boundary but cannot independently mutate projection rows.
- Projection schema/indexing expectations are explicit, including viewer-scoped rows and `(owner_user_id, sort_name, receiver_account_profile_id)` style bounded reads.
- Materialization semantics are bounded by event type. Full rebuild is only an explicit backfill/maintenance command, never GET or normal request handler.
- Synchronous materialization is allowed only when bounded by affected rows/edges. Work proportional to all raw contacts/profiles/users must be durable-job/backfill work.
- Hard cutoff now has bootstrap/backfill/readiness requirements so existing matched contacts/favorites do not disappear.
- GET remains projection-only even when projection is stale/missing; stale projection is materialization/backfill defect.
- Existing `GET /api/v1/invites/sent-statuses` contract is frozen for status overlay: `occurrence_id`, optional `event_id`, optional `recipient_account_profile_ids[]`, keyed by account profile IDs.
- Tests now explicitly require no-repair GET, query-level no `email_hashes`/`phone_hashes` inspection, privacy revocation, concurrency/idempotency, backfill readiness, exact request budgets with `1200+` contacts, repeated-entry Flutter behavior, and real-backend/no-mock CI execution.
- Claude CLI blockers were integrated: bounded sync materialization, privacy revocation, query-level no-hash-match mechanism, and status endpoint contract freeze.

## Review Question
Is the TODO now ready for implementation, or are there remaining blockers in the TODO contract itself?

Classify findings only as:
- `blocking`
- `accepted-debt-candidate`
- `out-of-scope`

Do not reopen implementation details unless the TODO contract still permits a concrete release-blocking failure.
