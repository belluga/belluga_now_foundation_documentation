# TODO (V1): Tenant User Area — Account + Account Profile Creation
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-01-17

## Objective
Define the MVP tenant user area that allows creation of Accounts and Account Profiles, and links profiles to accounts. This enables admin-assigned operators to bootstrap account profile identities (partner label) without full memberships.

## Scope
- Document the **tenant user area** flows for:
  - Create Account
  - Create Account Profile and attach it to an Account
  - View existing Accounts/Profiles (basic list/detail)
- Define required endpoint contracts and payloads (admin/tenant routes).
- Document MVP access rules (landlord/tenant admins only; no memberships yet).
- Align with Account Profile implementation TODO.

## Out of Scope
- Full memberships/roles system.
- Self‑serve user onboarding for account operators.
- Account workspace dashboards.
- Flutter UI implementation details.

## Definition of Done
- Flows and endpoint contracts for Account + Account Profile creation are documented.
- Access control rules are explicit (admin-assigned in MVP).
- Cross-references to Account Profile implementation are present.

## Validation Steps
- Manual doc review: ensure creation flows are documented and match contracts.

## Decisions
- MVP access is admin/tenant only (no memberships).
- Account Profiles must be linked to Accounts at creation.

## Questions to Close
- None.
