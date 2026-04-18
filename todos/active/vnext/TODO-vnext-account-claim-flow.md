# TODO (VNext): Account Claim Flow (Attach User + Ownership Transition)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-01-18

## Objective
Define and implement the **Account Claim Flow** that allows a user to claim an unmanaged account in a **single transactional operation**, attaching the user to the account and transitioning `ownership_state` to `user_owned`.

## Scope
- Define claim endpoint contract (request/response + auth).
- Specify transactional behavior: attach user + change `ownership_state` atomically.
- Define audit fields and event logging.
- Define guardrails (who can claim, when, conflict handling).

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

## Success Criteria
- Claim is atomic: **user attach + ownership_state update** succeed or fail together.
- Clear audit trail (`created_by`, `updated_by`, `*_by_type`).
- No change to MVP flows.
