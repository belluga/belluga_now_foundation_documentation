# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-10/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `needs_resolution`

## Merged Findings
### F-252E8D74 [high] Generated publish PR merge now uses github.token despite requiring follow-up workflow triggering
- **Reviewers:** elegance-clean-code-round-10
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Restore the repo-scoped merge token requirement or introduce an explicit post-merge repository_dispatch path that does not depend on a github.token-authored push. Keep dispatch-docker-sync triggering and generated-PR merge credentials in one documented promotion contract, with a guard that fails when the required token or dispatch configuration is absent.
- **Rationale:** In web-app .github/workflows/navigation-validation.yml, merge_generated_publish_pr now sets GH_TOKEN to github.token and removes the fail-fast check that previously required a repo-scoped PAT/app token. The same diff adds push-triggered docker sync in .github/workflows/dispatch-docker-sync.yml, so the promotion chain depends on the merge push causing follow-up workflow dispatch. The removed guard text states that a PAT/app token is required for the resulting lane push to trigger follow-up workflows; replacing it with github.token makes the workflow contract internally contradictory and risks silently skipping docker/submodule sync after an auto-merge.

### F-E95A6CD1 [medium] Public account-profile filter validation is duplicated across index and nearby requests
- **Reviewers:** elegance-clean-code-round-10
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extract the shared public account-profile filter/page/taxonomy rules into a request concern or small AccountProfilePublicFilterRules factory, then have both requests compose it with their endpoint-specific fields. Reuse normalizePublicPageSize for publicNear and remove the hard-coded 10/50 normalization path.
- **Rationale:** AccountProfilePublicIndexRequest and AccountProfileNearRequest repeat the same profile_type/filter/taxonomy rules and the same stringOrStringListRule closure. AccountProfileQueryService.publicPaginate uses normalizePublicPageSize with InputConstraints, while publicNear hand-rolls page_size defaults and a hard-coded 50 cap. The bounded work repeatedly hardens public filter bounds, so keeping two request rule matrices plus a separate service-level cap path leaves a clean-code drift point that can reintroduce inconsistent limits later.

### F-C357A280 [medium] Primary occurrence programming edits bypass the draft methods used by the occurrence editor
- **Reviewers:** elegance-clean-code-round-10
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make the primary-occurrence programming section use TenantAdminEventOccurrenceEditorDraft for add/update/remove and commit draft.toOccurrence through one controller method, or extract a small occurrence-programming coordinator shared by the root primary-occurrence path and the modal occurrence editor.
- **Rationale:** TenantAdminEventOccurrenceEditorDraft owns addProgrammingItem, updateProgrammingItem, removeProgrammingItem, sorting, and profile cleanup semantics, and TenantAdminEventOccurrenceEditorSheet uses those methods. TenantAdminEventFormScreen's root primary-occurrence programming section manually builds nextItems, sorts, and calls replacePrimaryOccurrenceDetails/replacePrimaryOccurrenceProgrammingItems from nested UI closures. That keeps mutation logic in the screen and leaves single-occurrence programming behavior able to drift from the multi-occurrence editor as new occurrence invariants are added.

### F-E00EA4AD [medium] Admin discovery filter row visual rendering remains duplicated between canonical and legacy surfaces
- **Reviewers:** elegance-clean-code-round-10
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extract a shared tenant-admin discovery-filter row/visual preview widget plus a small visual resolver model. Let the legacy local-preferences section adapt its item into that shared component instead of maintaining a parallel _MapFilterRowVisual implementation.
- **Rationale:** TenantAdminDiscoveryFilterSurfaceScreen and TenantAdminSettingsLocalPreferencesSection carry near-identical row preview code, marker override resolution, fallback enum/value classes, and action layout. The package claims the legacy map filter path now uses the canonical discovery filter catalog builder, but the UI layer still has two independent implementations for the same visual behavior. This is a mixed-pattern seam around visual icon/color fidelity and makes future changes to marker override, fallback image behavior, or accessibility keys easy to apply to only one surface.

## Reviewer Summaries
### elegance-clean-code-round-10
- **Assessment:** Not clean. The codebase is much cleaner than earlier rounds, but local inspection found one operational regression in the web promotion workflow and three maintainability seams in public-filter validation, admin filter-row rendering, and event occurrence programming flow. These are bounded to the audited changes and should be resolved before treating the elegance lane as clean.
- **Recommended path:** `needs_resolution`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] R10-ELEGANCE-001 Generated publish PR merge now uses github.token despite requiring follow-up workflow triggering: In web-app .github/workflows/navigation-validation.yml, merge_generated_publish_pr now sets GH_TOKEN to github.token and removes the fail-fast check that previously required a repo-scoped PAT/app token. The same diff adds push-triggered docker sync in .github/workflows/dispatch-docker-sync.yml, so the promotion chain depends on the merge push causing follow-up workflow dispatch. The removed guard text states that a PAT/app token is required for the resulting lane push to trigger follow-up workflows; replacing it with github.token makes the workflow contract internally contradictory and risks silently skipping docker/submodule sync after an auto-merge.
  - [medium] R10-ELEGANCE-002 Public account-profile filter validation is duplicated across index and nearby requests: AccountProfilePublicIndexRequest and AccountProfileNearRequest repeat the same profile_type/filter/taxonomy rules and the same stringOrStringListRule closure. AccountProfileQueryService.publicPaginate uses normalizePublicPageSize with InputConstraints, while publicNear hand-rolls page_size defaults and a hard-coded 50 cap. The bounded work repeatedly hardens public filter bounds, so keeping two request rule matrices plus a separate service-level cap path leaves a clean-code drift point that can reintroduce inconsistent limits later.
  - [medium] R10-ELEGANCE-003 Admin discovery filter row visual rendering remains duplicated between canonical and legacy surfaces: TenantAdminDiscoveryFilterSurfaceScreen and TenantAdminSettingsLocalPreferencesSection carry near-identical row preview code, marker override resolution, fallback enum/value classes, and action layout. The package claims the legacy map filter path now uses the canonical discovery filter catalog builder, but the UI layer still has two independent implementations for the same visual behavior. This is a mixed-pattern seam around visual icon/color fidelity and makes future changes to marker override, fallback image behavior, or accessibility keys easy to apply to only one surface.
  - [medium] R10-ELEGANCE-004 Primary occurrence programming edits bypass the draft methods used by the occurrence editor: TenantAdminEventOccurrenceEditorDraft owns addProgrammingItem, updateProgrammingItem, removeProgrammingItem, sorting, and profile cleanup semantics, and TenantAdminEventOccurrenceEditorSheet uses those methods. TenantAdminEventFormScreen's root primary-occurrence programming section manually builds nextItems, sorts, and calls replacePrimaryOccurrenceDetails/replacePrimaryOccurrenceProgrammingItems from nested UI closures. That keeps mutation logic in the screen and leaves single-occurrence programming behavior able to drift from the multi-occurrence editor as new occurrence invariants are added.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

