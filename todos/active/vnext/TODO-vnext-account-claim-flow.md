# Title
Account Claim Flow (Attach User + Ownership Transition)

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The platform explicitly supports `unmanaged` accounts as part of the recurring cold-start posture. Post-MVP operator self-management therefore needs a deterministic claim flow that attaches a user to an unmanaged account and transitions `ownership_state` to `user_owned` in one transactional operation.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** the deferred claim-flow boundary is already explicit and can be held in one owner TODO without decomposing implementation yet.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** this is a bounded deferred contract-definition lane, not a broad initiative requiring additional feature framing first.

## Contract Boundary
- This TODO defines the future claim-flow contract for attaching a user to an unmanaged account and changing ownership state atomically.
- It does not own full workspace delivery, profile-type expansion, or broader memberships/roles systems.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Deferred-Owner`
- **Next exact step:** freeze the endpoint/auth/conflict policy for account claim before implementation approval is considered.

## Scope
- Define claim endpoint contract (request/response + auth).
- Specify transactional behavior: attach user + change `ownership_state` atomically.
- Define audit fields and event logging.
- Define guardrails (who can claim, when, conflict handling).

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app:<planned>`, `flutter-app:<planned>`, `foundation_documentation:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Account claim flow deferred program owner | `pending` | `pending` | `pending` | `pending` | `Pending` |

**Owner:** Delphi
**Date:** `2026-01-18`

## Out of Scope
- MVP tenant admin UI.
- Membership/role system beyond attaching a user to the account.
- Self‑serve account creation flows (separate feature).

## Pending Decisions (VNext)
- Endpoint path + request shape (e.g., `POST /api/v1/accounts/{id}/claim`).
- Required auth: landlord‑only vs user‑initiated with verification.
- Conflict policy: if already claimed, return 409 vs no‑op.
- Audit requirements (who initiated claim; tenant context).
- Redirects/notifications to stakeholders (optional).

## Definition of Done
- Claim is atomic: **user attach + ownership_state update** succeed or fail together.
- Clear audit trail (`created_by`, `updated_by`, `*_by_type`).
- No change to MVP flows.

## Validation Steps
- Manual doc review confirms the claim-flow contract preserves atomic ownership transition semantics.
- Manual doc review confirms the TODO still stays separate from workspace delivery and broader memberships/roles scope.
