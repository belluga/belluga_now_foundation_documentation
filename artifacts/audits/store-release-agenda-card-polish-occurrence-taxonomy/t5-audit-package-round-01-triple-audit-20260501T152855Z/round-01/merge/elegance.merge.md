# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Resolve high findings before delivery.`

## Merged Findings
### F-CB9F2F27 [high] Occurrence-owned update semantics clear omitted fields
- **Reviewers:** Elegance Auditor
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `occurrence-omitted-fields-preserve-explicit-empty-clears`
- **Suggested action:** Preserve omitted owned occurrence fields on update, reserve explicit empty arrays for clearing, include occurrence identity in update payloads, and add backend and encoder tests.
- **Rationale:** The backend normalizer treats omitted occurrence event_parties, taxonomy_terms, and programming_items as empty arrays. Flutter can omit these keys, causing unrelated date edits to lose occurrence profiles, taxonomy overrides, and programming items.

### F-CD32B595 [high] Map full-width exception is implemented as an incomplete positive allowlist
- **Reviewers:** Elegance Auditor
- **Category:** `elegance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `tenant-public-web-frame-map-only-exemption`
- **Suggested action:** Make CityMapRoute and PoiDetailsRoute explicit full-width exemptions and prove other tenant-public routes such as privacy policy and contact group management remain framed.
- **Rationale:** Only removing map routes from the framed route allowlist leaves other current tenant-public routes accidentally full width. The requirement is to remove web max width only for the map screen route family.

## Reviewer Summaries
### Elegance Auditor
- **Assessment:** The package direction is acceptable, but two structural issues must be fixed before delivery: web framing must exempt only map routes, and occurrence update semantics must not clear omitted owned occurrence fields.
- **Recommended path:** `Resolve high findings before delivery.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [high] ELEGANCE-T5-001 Map full-width exception is implemented as an incomplete positive allowlist: Only removing map routes from the framed route allowlist leaves other current tenant-public routes accidentally full width. The requirement is to remove web max width only for the map screen route family.
  - [high] ELEGANCE-T5-002 Occurrence-owned update semantics clear omitted fields: The backend normalizer treats omitted occurrence event_parties, taxonomy_terms, and programming_items as empty arrays. Flutter can omit these keys, causing unrelated date edits to lose occurrence profiles, taxonomy overrides, and programming items.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

