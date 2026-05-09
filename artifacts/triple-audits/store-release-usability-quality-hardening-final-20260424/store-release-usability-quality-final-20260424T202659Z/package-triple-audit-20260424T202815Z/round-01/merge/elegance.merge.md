# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/store-release-usability-quality-final-20260424T202659Z/package-triple-audit-20260424T202815Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Extract event occurrence/programming editor state into dedicated controller/draft components, then move tenant-admin discovery-filter rule-catalog loading behind an application/repository contract with required dependencies and explicit errors.`

## Merged Findings
### F-00B16F1B [medium] Event occurrence and programação editing remain screen-owned mutable orchestration
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extract an occurrence/programming draft model plus focused editor controller/widgets. Keep the screen declarative and delegate validation, profile linking, sorting, and draft-to-domain conversion to reusable units.
- **Rationale:** The event form screen owns complex modal draft state and domain mutation logic through nested StatefulBuilder closures and mutable local lists, including _openOccurrenceEditor, _openProgrammingItemEditor, validation, profile linking, sorting, and DTO construction. This keeps fragile occurrence/programming mapping hotspots in the screen.

### F-9890A4D8 [medium] Discovery-filter rule catalog assembly is in the presentation controller
- **Reviewers:** elegance
- **Category:** `architecture`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Introduce a TenantAdminDiscoveryFilterRuleCatalogRepository or application service with required dependencies and explicit error reporting. The controller should consume that single contract.
- **Rationale:** TenantAdminDiscoveryFiltersController directly pulls settings, account profile, static asset, taxonomy, and event repositories, then builds TenantAdminMapFilterRuleCatalog itself; missing optional repositories emit an empty catalog. This is cross-domain application orchestration rather than presentation state management.

### F-64F7DC3D [low] Legacy map-filter to discovery-filter mapping is duplicated across Flutter decoding/domain code
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Consolidate Flutter legacy-to-canonical mapping into one shared mapper used by both decoder and domain settings, and keep backend backfill behavior aligned with that contract.
- **Rationale:** The diff contains separate legacy map-filter to canonical mapping implementations in the response decoder and discovery-filter settings model, alongside backend backfill behavior. The compatibility mapper is legitimate, but duplicate client-side implementations can drift.

## Reviewer Summaries
### elegance
- **Assessment:** The package state is substantially hardened versus dev and credential handling is clean, but key UI/application orchestration remains too screen-owned and discovery-filter rule catalog assembly is misplaced in a presentation controller.
- **Recommended path:** `Extract event occurrence/programming editor state into dedicated controller/draft components, then move tenant-admin discovery-filter rule-catalog loading behind an application/repository contract with required dependencies and explicit errors.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] ELEGANCE-001 Event occurrence and programação editing remain screen-owned mutable orchestration: The event form screen owns complex modal draft state and domain mutation logic through nested StatefulBuilder closures and mutable local lists, including _openOccurrenceEditor, _openProgrammingItemEditor, validation, profile linking, sorting, and DTO construction. This keeps fragile occurrence/programming mapping hotspots in the screen.
  - [medium] ELEGANCE-002 Discovery-filter rule catalog assembly is in the presentation controller: TenantAdminDiscoveryFiltersController directly pulls settings, account profile, static asset, taxonomy, and event repositories, then builds TenantAdminMapFilterRuleCatalog itself; missing optional repositories emit an empty catalog. This is cross-domain application orchestration rather than presentation state management.
  - [low] ELEGANCE-003 Legacy map-filter to discovery-filter mapping is duplicated across Flutter decoding/domain code: The diff contains separate legacy map-filter to canonical mapping implementations in the response decoder and discovery-filter settings model, alongside backend backfill behavior. The compatibility mapper is legitimate, but duplicate client-side implementations can drift.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

