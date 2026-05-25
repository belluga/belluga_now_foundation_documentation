# Pre-Approval Bounded Audit Package Round 02: Invite Sent Status Hydration and Accepted Push Presentation

- **Artifact kind:** `bounded_audit_package`
- **Authoritative:** `false`
- **Created:** `2026-05-23`
- **Related TODO:** `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-sent-status-hydration-and-accepted-push-presentation.md`
- **Prior resolution:** `foundation_documentation/artifacts/audits/invite-sent-status-hydration-accepted-push-preapproval-package-20260523-triple-audit-20260523T135612Z/round-01/resolution.md`
- **Audit stage:** pre-implementation / pre-`APROVADO`

## Round 02 Objective
Verify whether Round 01 blockers were fully absorbed into the TODO contract. This is still pre-implementation; do not review code and do not propose implementation details beyond contract/test blockers.

## Round 01 Findings Integrated
- `ELEGANCE-001`: exact endpoint boundary frozen.
- `ELEGANCE-002`: canonical recipient matching key frozen.
- `ELEGANCE-003`: status/actionability matrix added.
- `PERF-001`: bounded direct lookup, no N+1, no page-walking, and in-flight dedupe requirements added.
- `TQ-01`: foreground/background/tap/cold-start/duplicate/push-before-hydration push matrix added.
- `TQ-02`: production-like distinct account user id vs account profile id tests added.
- `TQ-03`: declined/superseded backend and Flutter tests added.
- Claude CLI security/performance/identity blockers were integrated as no client-controlled inviter, tenant isolation, event/occurrence rules, bounded response, terminal statuses, and error/empty semantics.

## Frozen Endpoint Contract
- `GET /invites/sent-statuses`
- Auth: `auth:sanctum` + `CheckTenantAccess`.
- Tenant comes from current tenant context.
- Inviter identity is server-derived from authenticated account user and active inviter principal.
- Client must not supply `inviter_id`, `issued_by_user_id`, `inviter_principal_id`, or tenant override.
- Required query: `occurrence_id`.
- Optional query: `event_id` only as consistency context; mismatch returns `422 occurrence_event_mismatch`.
- `event_id` without `occurrence_id` returns `422 occurrence_id_required`; event-only aggregation is invalid.
- Optional query: `recipient_account_profile_ids[]`, max `200`, for one occurrence-scoped hydration request for currently visible inviteables.
- Empty state: `200` with zeroed summary and empty items.
- Auth/error semantics: `401` unauthenticated, `403` invalid tenant access, `404` occurrence missing/inaccessible, `422` malformed ids/too many recipients/event mismatch/event-only.
- Ordering: `created_at desc`, `_id desc`.
- Timestamps: ISO 8601 UTC.
- Unfiltered response default/max: `200` items; larger future needs require a separate paginated/reporting contract, not Flutter page-walking.

## Response Shape
```json
{
  "data": {
    "event_id": "ObjectId",
    "occurrence_id": "ObjectId",
    "summary": {
      "pending": 1,
      "accepted": 1,
      "declined": 0,
      "terminal_hidden": 0
    },
    "items": [
      {
        "invite_id": "ObjectId",
        "recipient_key": "account_profile:ObjectId",
        "receiver_account_profile_id": "ObjectId",
        "receiver_user_id": "ObjectId|null",
        "display_name": "string",
        "avatar_url": "string|null",
        "status": "pending|accepted|declined|expired|superseded|suppressed",
        "ui_visibility": "visible|hidden",
        "blocks_reinvite": true,
        "counts_bucket": "pending|accepted|declined|none",
        "sent_at": "ISO-8601 UTC",
        "responded_at": "ISO-8601 UTC|null",
        "supersession_reason": "other_invite_credited|direct_confirmation|null"
      }
    ]
  },
  "metadata": {
    "request_id": "string",
    "truncated": false,
    "next_cursor": null
  }
}
```

## Recipient Matching Contract
- Canonical matching key is `receiver_account_profile_id`.
- `recipient_key` is exactly `account_profile:{receiver_account_profile_id}`.
- `receiver_user_id` is informative only.
- Flutter optimistic sent-invite entries must normalize to `recipient_key`.
- `invite_accepted` push payload must normalize account-profile ids to the same `recipient_key`.
- Tests must use distinct `account_user_id` and `account_profile_id`.

## Status / Actionability Matrix
| Status | UI Visibility | Summary Bucket | Blocks Reinvite | `responded_at` Expectation |
| --- | --- | --- | --- | --- |
| `pending` | `visible` | `pending` | `true` | `null` |
| `accepted` | `visible` | `accepted` | `true` | `accepted_at` |
| `declined` | `visible` | `declined` | `true` | `declined_at` |
| `expired` | `hidden` | `none` | `true` | `expired_at|null` |
| `superseded` | `hidden` | `none` | `true` | `superseded_at|updated_at|null` |
| `suppressed` | `hidden` | `none` | `true` | `updated_at|null` |

## Performance / Load Contract
- Laravel must query by tenant + authenticated inviter principal + `occurrence_id` through a direct bounded lookup path.
- No all-event invite scan, all-tenant user scan, or all-participant scan.
- Recipient identity/avatar projection must be eager/bounded; N+1 is unacceptable.
- Backend tests require query-count instrumentation or equivalent repository/service spy.
- Flutter uses one occurrence-scoped backend contract, not invite-feed/event/contact/page walking.
- Flutter dedupes same-key in-flight hydration by `occurrence_id + recipient_filter_hash`.
- Push reconciliation may refresh only the affected `occurrence_id` and must share the dedupe path.

## Push / Device Matrix
| App State | Required Behavior |
| --- | --- |
| Foreground | Invite-specific visible signal and affected occurrence state update. |
| Background/resume | Notification or resume path refreshes affected occurrence and preserves invite-specific context. |
| Notification tap | Opens invite/event-aware destination with refreshed state; if existing ended-event behavior cannot render, use existing event/home fallback. |
| Terminated/cold start | Preserves intent through bootstrap and refreshes canonical sent status before stale buttons are shown. |
| Duplicate push | Idempotent; no duplicate rows or state entries. |
| Push before list hydration | Upsert by `recipient_key` if possible or trigger occurrence-scoped hydration; silent drop forbidden. |

Generic Push Handler fallback is forbidden in every row.

## Required Fail-First Coverage
Laravel:
- Endpoint contract, auth/tenant scoping, no client-controlled inviter, occurrence-only identity, event mismatch, payload shape, terminal statuses, bounded lookup/no N+1, sender-side profile metrics.

Flutter:
- Restart hydration from empty local cache, optimistic reconciliation, production-like user/profile id mismatch, no page-walking, same-key in-flight dedupe, duplicate push idempotency, push-before-hydration, duplicate-invite button disablement, summary counts, declined/superseded actionability, sender-side profile metrics, invite-specific foreground/background/tap/cold-start presentation/routing.

Runtime/device:
- Real device foreground, background/resume, notification tap, cold-start when feasible, restart without relying on fresh push, and existing real FCM acceptance E2E.

## Reviewer Question
Are there any remaining blocking contract/test gaps before `APROVADO`? If yes, report only concrete blockers. If no, return zero findings and explicitly say the TODO is approval-ready at the planning gate.
