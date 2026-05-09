# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Hold T4 advancement until the inviteable-recipient and contact-group paths are made bounded and batch-oriented. At minimum, compute inviteable profile ids once per request, replace per-row profile/user/type lookups with batched indexed queries, add explicit request-size caps for contacts/groups/invite recipients, and avoid per-group recomputation/write amplification on GET /contact-groups.`

## Merged Findings
### F-EA4193BE [high] Hot mutation requests accept unbounded arrays before entering query/write loops
- **Reviewers:** performance
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add explicit server-side maximum counts for imported contacts, invite recipients, and group member ids, aligned with product limits and invite quotas. Reject oversize payloads at validation with 422 or 413 before service work starts. For contact import, process in documented chunks or batch upserts rather than one query/save per submitted contact.
- **Rationale:** ContactsImportRequest requires contacts to be an array with min:1 but no max, and ContactImportService maps every submitted contact, performs whereIn matching, then loops each contact with a contact_hash_directory lookup and save. InviteCreateRequest likewise accepts an unbounded recipients array, and InviteMutationService loops each normalized recipient through identity resolution, existing-invite lookup, quota reservation, creation, projection rebuild, and telemetry. ContactGroupStoreRequest and ContactGroupUpdateRequest also accept unbounded recipient_account_profile_ids arrays before filterInviteableIds builds the full inviteable set. These shapes create resource-exhaustion risk on authenticated tenant endpoints even if normal mobile clients submit modest payloads.

### F-21C3292A [high] GET /contacts/inviteables performs unbounded materialization with N+1 profile/user/type lookups
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace the per-row Eloquent loop with a bounded/batched inviteable-recipient query plan: collect matched user ids and favorite profile ids, fetch users/profiles/profile types in bulk, cache the personal profile type capability for the request, add the compound owner/profile index needed by personalProfileForUserId or avoid that lookup shape, and return a bounded page or documented maximum result size.
- **Rationale:** ContactInviteablesController returns InviteablePeopleService::inviteableItemsFor directly. That service loads all contact-hash rows for the viewer with get(), loads all outbound favorites with get(), loads all inbound favorites with get(), sorts the full result in memory, and performs per-row AccountUser::find, AccountProfile lookups through personalProfileForUserId, profileOwner lookups, and TenantProfileType queries in profileIsInviteable. The account_profiles lookup used by personalProfileForUserId filters created_by, created_by_type, profile_type, and deleted_at, but the inspected migrations only establish broader profile_type/discovery/deleted_at indexes, not the compound owner-personal-profile lookup this hot path repeatedly needs. This is a concrete severe scaling risk for a share screen that can be opened frequently by users with large imported address books or favorites.

### F-A96F978A [high] Contact-group listing recomputes the full inviteable graph once per group and persists during GET
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Compute the inviteable profile-id set once per request and pass it into group normalization, or use a targeted membership-validity query over the union of group member ids. Add a bounded list contract for contact groups if group counts can grow, and move stale-member pruning to create/update or a single bulk pruning operation instead of recomputing and saving per group during GET.
- **Rationale:** ContactGroupService::list loads every group for an owner, then maps each group through normalizeAndPersist. normalizeAndPersist calls filterInviteableIds, and filterInviteableIds calls inviteableProfileIdSetFor, which rebuilds the full inviteable list through InviteablePeopleService::inviteableItemsFor. The request cost therefore scales roughly as group_count times the full contact/favorite scan cost, including the N+1 lookups described above. The same GET path also saves each group whose members are pruned, creating write amplification and latency risk on a read endpoint.

## Reviewer Summaries
### performance
- **Assessment:** Not performance-clean for this gate. The relation model and profile-id dedupe direction are sound, and the Flutter share screen uses one inviteables fetch rather than page-walking. The blocking issue is that the backend hot paths materialize unbounded candidate sets and then perform per-row lookups; contact-group pruning further multiplies that work and writes during GET.
- **Recommended path:** `Hold T4 advancement until the inviteable-recipient and contact-group paths are made bounded and batch-oriented. At minimum, compute inviteable profile ids once per request, replace per-row profile/user/type lookups with batched indexed queries, add explicit request-size caps for contacts/groups/invite recipients, and avoid per-group recomputation/write amplification on GET /contact-groups.`
- **Performance:** `regresses`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] PERF-001 GET /contacts/inviteables performs unbounded materialization with N+1 profile/user/type lookups: ContactInviteablesController returns InviteablePeopleService::inviteableItemsFor directly. That service loads all contact-hash rows for the viewer with get(), loads all outbound favorites with get(), loads all inbound favorites with get(), sorts the full result in memory, and performs per-row AccountUser::find, AccountProfile lookups through personalProfileForUserId, profileOwner lookups, and TenantProfileType queries in profileIsInviteable. The account_profiles lookup used by personalProfileForUserId filters created_by, created_by_type, profile_type, and deleted_at, but the inspected migrations only establish broader profile_type/discovery/deleted_at indexes, not the compound owner-personal-profile lookup this hot path repeatedly needs. This is a concrete severe scaling risk for a share screen that can be opened frequently by users with large imported address books or favorites.
  - [high] PERF-002 Contact-group listing recomputes the full inviteable graph once per group and persists during GET: ContactGroupService::list loads every group for an owner, then maps each group through normalizeAndPersist. normalizeAndPersist calls filterInviteableIds, and filterInviteableIds calls inviteableProfileIdSetFor, which rebuilds the full inviteable list through InviteablePeopleService::inviteableItemsFor. The request cost therefore scales roughly as group_count times the full contact/favorite scan cost, including the N+1 lookups described above. The same GET path also saves each group whose members are pruned, creating write amplification and latency risk on a read endpoint.
  - [high] PERF-003 Hot mutation requests accept unbounded arrays before entering query/write loops: ContactsImportRequest requires contacts to be an array with min:1 but no max, and ContactImportService maps every submitted contact, performs whereIn matching, then loops each contact with a contact_hash_directory lookup and save. InviteCreateRequest likewise accepts an unbounded recipients array, and InviteMutationService loops each normalized recipient through identity resolution, existing-invite lookup, quota reservation, creation, projection rebuild, and telemetry. ContactGroupStoreRequest and ContactGroupUpdateRequest also accept unbounded recipient_account_profile_ids arrays before filterInviteableIds builds the full inviteable set. These shapes create resource-exhaustion risk on authenticated tenant endpoints even if normal mobile clients submit modest payloads.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

