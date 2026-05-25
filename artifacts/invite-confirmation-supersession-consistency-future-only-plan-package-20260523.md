# Invite Confirmation Supersession Consistency - Future-Only Plan Audit Package

## Purpose
Re-evaluate the direct-confirmation supersession plan with a changed constraint:
historical drift / past records are explicitly out of scope. The only required
property is that new direct confirmations cannot create future split-write drift.

This is a planning audit package only. Do not implement code in this audit round.

## Active TODO
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-sent-status-hydration-and-accepted-push-presentation.md`

## Revised Scope Constraint
- Past/historical left-behind pending invites are not relevant for this decision.
- No repair of old production data is required in this slice.
- The system only needs to guarantee that from the fix onward, direct confirmation
  cannot persist active attendance while same-target pending/viewed invites remain
  pending because supersession failed.

## Current Code Evidence
- Credited invite acceptance is transaction-backed:
  - `InviteMutationService::acceptForUserIdWithoutReplay()` wraps accepted invite update
    and competing invite supersession in `InviteTransactionRunner`.
  - `InviteTransactionRunner` hard-fails when tenant MongoDB transactions are unavailable.
  - `CreditedInviteAccepted implements ShouldDispatchAfterCommit`; side effects run after commit.
- Direct attendance confirmation is not currently transaction-backed as one unit:
  - `AttendanceCommitmentService::confirm()` upserts `attendance_commitments` first.
  - It then calls `InviteMutationService::supersedePendingInvitesForDirectConfirmation(...)`.
  - If the second call fails after the upsert, future split-write drift is possible.

## Domain Rules To Preserve
- A receiver may have multiple pending invites for the same event/occurrence, especially from different inviters.
- Being already invited does not make an invite `superseded`.
- `superseded` is reserved for confirmed outcomes:
  - `other_invite_credited`: one invite was accepted/credited;
  - `direct_confirmation`: receiver confirmed attendance outside invite acceptance.
- Accepted credited invite attribution must not be overwritten by direct confirmation.

## Candidate Options Under Future-Only Scope

### Option A: Transaction-only canonical path
Move direct confirmation into one transaction-capable domain path:
- upsert active attendance;
- supersede same-target pending/viewed invites as `superseded/direct_confirmation`;
- commit both together;
- emit side effects only after commit;
- fail closed if tenant transactions are unavailable.

No reconciler/job is required for historical drift because historical drift is out of scope.

### Option B: Job/reconciler-only future consistency
Direct confirmation writes attendance and persists/enqueues a cleanup job.
This allows eventual consistency, but correctness depends on queue durability and worker success.

### Option C: Transaction + bounded reconciler safety net
Same as Option A, plus bounded repair/reconciliation for explicit target tuples.
This is stronger operationally but adds complexity that may not be justified if historical drift is out of scope.

## Auditor Questions
- With historical drift out of scope, is Option A sufficient and preferable?
- Is any job/reconciler still required to satisfy future-only correctness, or is it unnecessary complexity?
- What tests remain mandatory blockers for Option A?
- What concurrency/idempotency rules must still be frozen for direct confirmation versus invite acceptance?
- Should the TODO plan be narrowed to transaction-only and fail-closed behavior?

## Expected Audit Output
Return blocker-focused findings only:
- `verdict`: one of `approve_plan`, `approve_with_blockers`, `reject_plan`.
- `recommended_option`: `A`, `B`, or `C`.
- `blocking_findings`: concrete plan gaps that must be resolved before approval.
- `required_tests`: minimum tests/probes to add to the TODO.
- `non_blocking_debt`: valid but non-blocking follow-ups.
