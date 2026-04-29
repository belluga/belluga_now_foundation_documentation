# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-03/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Block T3 closure until resolveUserRecipient mirrors the canonical account-profile recipient rules: when a personal profile exists, reject it if the profile is not inviteable; preserve receiver_user_id fallback only for active users with no personal profile. Add backend coverage for receiver_user_id and stale contact_hash directory paths targeting a non-inviteable personal profile.`

## Merged Findings
### F-192D5D4F [high] Legacy receiver_user_id path bypasses canonical profile inviteability
- **Reviewers:** Elegance
- **Category:** `elegance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Update recipientForUserId so an existing personal profile must pass profileIsInviteable before it is returned; only return a null receiver_account_profile_id for the no-profile legacy fallback. Add feature coverage proving receiver_user_id direct invites and contact_hash directory resolution suppress non-inviteable profiles while preserving the no-profile legacy case.
- **Rationale:** The canonical account-profile path rejects non-inviteable profiles in InviteablePeopleService.php, but recipientForUserId returned the user's personal profile without calling profileIsInviteable. InviteMutationService still routes receiver_user_id and contact_hash recipients through resolveUserRecipient, so a legacy recipient path can create or materialize a profile-keyed invite for a profile the canonical path would suppress. That leaves a real old/new split in the recipient lifecycle and carries privacy/correctness risk.

## Reviewer Summaries
### Elegance
- **Assessment:** Not clean. The account-profile keyed acceptance/share lifecycle is mostly aligned, but the legacy receiver_user_id recipient path still bypasses the same inviteability rule used by the canonical receiver_account_profile_id path.
- **Recommended path:** `Block T3 closure until resolveUserRecipient mirrors the canonical account-profile recipient rules: when a personal profile exists, reject it if the profile is not inviteable; preserve receiver_user_id fallback only for active users with no personal profile. Add backend coverage for receiver_user_id and stale contact_hash directory paths targeting a non-inviteable personal profile.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-R03-001 Legacy receiver_user_id path bypasses canonical profile inviteability: The canonical account-profile path rejects non-inviteable profiles in InviteablePeopleService.php, but recipientForUserId returned the user's personal profile without calling profileIsInviteable. InviteMutationService still routes receiver_user_id and contact_hash recipients through resolveUserRecipient, so a legacy recipient path can create or materialize a profile-keyed invite for a profile the canonical path would suppress. That leaves a real old/new split in the recipient lifecycle and carries privacy/correctness risk.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

