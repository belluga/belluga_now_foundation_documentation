# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-08/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `needs_resolution: extract the duplicated account-context derivation into one events-domain collaborator and consolidate the duplicated Playwright semantic navigation helpers into shared harness support before treating the Elegance lane as clean.`

## Merged Findings
### F-100AFE27 [medium] Event account-context derivation is duplicated between aggregate write normalization and occurrence sync projection
- **Reviewers:** round-08-elegance-clean-code
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extract a package-local EventAccountContextResolver or similar collaborator that accepts base account ids plus event/occurrence relevance payloads and returns normalized account context ids. Use it from both EventManagementService and EventOccurrenceSyncService, and keep the migration/backfill helper aligned with the same resolver shape where feasible.
- **Rationale:** The same account-context inference shape appears in EventManagementService::resolveAccountContextIdsForEventPayload and EventOccurrenceSyncService::resolveAccountContextIds: both collect base account ids, event party profile ids, place_ref ids, programming account_profile_ids, programming place_ref ids, then call the profile resolver. Because account_context_ids now drive account-scoped management indexes and occurrence filtering, keeping this logic in two private implementations creates a clean-code and structural drift point. Future additions to account relevance can update the aggregate document but miss occurrence projection, or vice versa, without a package-boundary signal.

### F-DF405F99 [low] Release-gating Playwright dropdown helper logic is duplicated across mutation specs
- **Reviewers:** round-08-elegance-clean-code
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move semantic dropdown selection and related Flutter field helpers into a shared web_app_tests support module, require both mutation specs to import the shared helper, and let the navigation policy guard recognize or enforce use of that helper for release-gating dropdown interactions.
- **Rationale:** Both navigation.mutation.tenant_admin.spec.js and navigation.mutation.event_occurrences.spec.js define selectDropdownOption with the same button-trigger, fallback-button, label-trigger, option-role, and menuitem-role flow. This is now policy-critical code because the package explicitly relies on semantic dropdown behavior and a guard that blocks text/keyboard fallbacks. Keeping duplicated helper implementations across large mutation specs increases the chance that future harness hardening updates one flow but leaves the other with stale semantics or weaker diagnostics.

## Reviewer Summaries
### round-08-elegance-clean-code
- **Assessment:** The bounded package is operationally strong and the prior-round resolutions are represented in the effective package, but the Elegance/Clean Code lane is not fully clean. I found two remaining structural duplication seams in release-critical paths: account-context derivation for event/occurrence projections, and Playwright dropdown/navigation helper logic duplicated across mutation specs. Neither finding contradicts the recorded validation evidence, but both leave avoidable drift risk in code that now acts as release-gating infrastructure.
- **Recommended path:** `needs_resolution: extract the duplicated account-context derivation into one events-domain collaborator and consolidate the duplicated Playwright semantic navigation helpers into shared harness support before treating the Elegance lane as clean.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] R08-ELEGANCE-001 Event account-context derivation is duplicated between aggregate write normalization and occurrence sync projection: The same account-context inference shape appears in EventManagementService::resolveAccountContextIdsForEventPayload and EventOccurrenceSyncService::resolveAccountContextIds: both collect base account ids, event party profile ids, place_ref ids, programming account_profile_ids, programming place_ref ids, then call the profile resolver. Because account_context_ids now drive account-scoped management indexes and occurrence filtering, keeping this logic in two private implementations creates a clean-code and structural drift point. Future additions to account relevance can update the aggregate document but miss occurrence projection, or vice versa, without a package-boundary signal.
  - [low] R08-ELEGANCE-002 Release-gating Playwright dropdown helper logic is duplicated across mutation specs: Both navigation.mutation.tenant_admin.spec.js and navigation.mutation.event_occurrences.spec.js define selectDropdownOption with the same button-trigger, fallback-button, label-trigger, option-role, and menuitem-role flow. This is now policy-critical code because the package explicitly relies on semantic dropdown behavior and a guard that blocks text/keyboard fallbacks. Keeping duplicated helper implementations across large mutation specs increases the chance that future harness hardening updates one flow but leaves the other with stale semantics or weaker diagnostics.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

