---

## Verdict: `approve_with_blockers`

## Recommended Option: **A — Transaction-only**

With historical drift out of scope, Option A provides exactly the required property: both the attendance upsert and invite supersession either commit together or neither does. A reconciler (Option B/C) is only justified for eventual repair of past records or for tolerating partial writes — neither applies under the revised scope. Option C's bounded reconciler adds operational weight without a correctness payoff when fail-closed transactions are the contract.

---

## Blocking Findings

### BLK-1 — Concurrent credited-invite race not addressed

The plan specifies that credited invite attribution must never be overwritten by direct confirmation, but it does not specify behavior when a credited invite acceptance (`acceptForUserIdWithoutReplay`) and a direct confirmation race concurrently on the same receiver/event. Both run under `InviteTransactionRunner`, but they target overlapping invite rows with different supersession reasons. The plan must define the winner rule and specify that Option A's supersession query filters out `status=credited` (and `status=superseded`) rows — not just `status=pending/viewed`. Without this, the transaction can commit but still overwrite attributed credit.

### BLK-2 — Fail-closed behavior not formally specified for `confirm()`

Option A's correctness guarantee depends on `confirm()` hard-failing — not silently degrading — when tenant transactions are unavailable. `InviteTransactionRunner` already hard-fails in the credited path, but the plan does not specify that the same runner (or an equivalent fail-closed gate) is the execution path for direct confirmation. If the implementation uses a softer fallback (log + continue), the split-write risk is reintroduced on infrastructure degradation. The plan must explicitly name the transaction executor and assert fail-closed behavior.

### BLK-3 — Supersession idempotency contract absent

If `confirm()` is retried after a transaction abort (before commit), the attendance upsert is naturally idempotent, but the supersession query must also be bounded to only `pending/viewed` status rows. The plan does not specify this filter. Without it, a retry or a concurrent second confirmation could double-process rows already transitioned to `superseded`, risking incorrect status reassignment (e.g., overwriting `other_invite_credited` with `direct_confirmation` on a second pass).

---

## Required Tests

| # | Test | What it proves |
|---|------|----------------|
| T-1 | Direct confirmation atomicity — supersession failure rolls back attendance | Transaction boundary holds; no split-write drift on supersession failure |
| T-2 | Credited invite is NOT superseded by direct confirmation | Filter correctness; BLK-1 |
| T-3 | Concurrent credited acceptance + direct confirmation on same receiver/event | Race winner is deterministic; credited attribution survives |
| T-4 | Retry of `confirm()` after abort is idempotent | No double-supersession; BLK-3 |
| T-5 | `confirm()` hard-fails when tenant transactions are unavailable | Fail-closed contract; BLK-2 |
| T-6 | Multiple pending invites from different inviters are all superseded in one transaction | Domain rule: same-target multi-invite fan-out handled correctly |

---

## Non-Blocking Debt

- **Option C bounded reconciler**: Not required now, but can be added later as an operational safety net if transaction failure rates justify it. Acceptable to defer indefinitely under the revised scope.
- **Observability**: No monitoring specified for transaction aborts in the `confirm()` path. Worth adding a structured log or metric, but not a correctness blocker.
- **Naming alignment**: `supersedePendingInvitesForDirectConfirmation` implicitly implies only `pending` status. If the implementation also targets `viewed`, the method name should reflect that. Non-blocking naming debt.
