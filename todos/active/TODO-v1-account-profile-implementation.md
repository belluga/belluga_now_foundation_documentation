# TODO (V1): Account Profile Implementation (Backend + Contracts)
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-01-17

## Objective
Deliver the Account Profile model and required contracts as the generic, boilerplate-level 1:N identity layer under Account. This enables account-profile-facing flows (invites, map, offers, push) without introducing memberships in MVP.

## Scope
- Define Account Profile data model and contracts (generic, boilerplate-ready).
- Update endpoints/contracts where Account Profile is the required context (invites, push, map POIs, offers, discovery).
- Ensure Account Profile optional `location` rule is explicit (geo index only for profiles with location).
- Define admin-assigned operator linkage for MVP (no memberships yet).
- Capture the user→influencer upgrade flow (Account + AccountProfile + operator link).

## Out of Scope
- Full memberships/roles system (post‑MVP).
- Dashboard/analytics UI (post‑MVP).
- Flutter UI implementation work.

## Definition of Done
- Account Profile schema and required fields documented in foundation docs.
- Required API contracts reference `account_profile_id` where applicable.
- Admin-assigned operator linkage is documented for MVP.
- Optional geo location + geo index behavior is documented.
- User→influencer upgrade path is documented.

## Validation Steps
- Manual doc review: ensure Account Profile references are consistent across modules and contracts.
- Verify invite/push/map/offers contracts reference `account_profile_id`.

## Decisions
- Account Profile is a **boilerplate** model (generic) with project-defined `profile_type` values.
- Account Profile is **1:N** under Account.
- `location` is optional; only geo-enabled profiles are indexed/queryable.
- MVP operators are admin-assigned (memberships deferred).
- User→influencer upgrade creates Account + AccountProfile + operator link.

## Questions to Close
- None.
