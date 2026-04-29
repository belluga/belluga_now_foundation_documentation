# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close the performance lane until contact import matching is batched like the inviteable list path. Resolve imported contact matches with one bounded profile lookup set, one profile-type capability lookup set, and one viewer-profile lookup instead of calling per-user payload resolution.`

## Merged Findings
### F-1F09599E [high] Contact import matching still performs per-match profile and capability lookups
- **Reviewers:** performance-lane-round-02
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `batch-hot-contact-import-resolution`
- **Suggested action:** Add a batched contact-match payload path for imported contacts: compute the viewer personal profile once, fetch target personal profiles for all matched users with one whereIn, fetch profile-type capabilities once for the involved profile types, and build payloads from those in-memory maps. Keep the 500-contact cap, but remove per-target profile/type queries from the request loop.
- **Rationale:** InviteIdentityGatewayAdapter::matchImportedContacts batches the initial AccountUser lookup by email/phone hash, but then loops every matched user and calls InviteablePeopleService::contactMatchPayloadFor. That method performs personal profile lookups for the viewer and target and may query TenantProfileType for inviteability per matched target. ContactsImportRequest allows up to 500 contacts, and InviteShareScreenController triggers this import when loading invite targets, so a normal large address-book import can create hundreds of database lookups inside one request. This matches the lane's blocking performance criteria for request-loop behavior where a batched query path is required.

## Reviewer Summaries
### performance-lane-round-02
- **Assessment:** The round-01 inviteable list and contact-group fixes are materially improved: source reads are capped, inviteable list composition is batched, group listing reuses one inviteable-id set, and hot request payloads now have server-side limits. One performance blocker remains in the contact-import matching path: it keeps a per-matched-user lookup loop under a hot, user-triggered contacts import mutation.
- **Recommended path:** `Do not close the performance lane until contact import matching is batched like the inviteable list path. Resolve imported contact matches with one bounded profile lookup set, one profile-type capability lookup set, and one viewer-profile lookup instead of calling per-user payload resolution.`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-R02-001 Contact import matching still performs per-match profile and capability lookups: InviteIdentityGatewayAdapter::matchImportedContacts batches the initial AccountUser lookup by email/phone hash, but then loops every matched user and calls InviteablePeopleService::contactMatchPayloadFor. That method performs personal profile lookups for the viewer and target and may query TenantProfileType for inviteability per matched target. ContactsImportRequest allows up to 500 contacts, and InviteShareScreenController triggers this import when loading invite targets, so a normal large address-book import can create hundreds of database lookups inside one request. This matches the lane's blocking performance criteria for request-loop behavior where a batched query path is required.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

