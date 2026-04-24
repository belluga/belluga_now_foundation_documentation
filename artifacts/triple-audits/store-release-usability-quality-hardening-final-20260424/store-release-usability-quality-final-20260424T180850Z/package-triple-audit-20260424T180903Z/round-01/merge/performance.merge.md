# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/store-release-usability-quality-final-20260424T180850Z/package-triple-audit-20260424T180903Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the untracked-source promotion risk and harden the occurrence-driven management query with an explain-backed or query-count guardrail. The remaining primary-row eager widget construction can be handled as a low-risk cleanup, but it should be corrected because row virtualization is an explicit scope objective.`

## Merged Findings
### F-0D95E11B [medium] Release-critical Laravel source and guardrail files are untracked in the captured package
- **Reviewers:** performance-operational-fit
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Commit or otherwise promote the untracked Laravel source/test files into the release branch before final approval, and add a promotion preflight that fails when tracked diffs reference untracked PHP classes or tests needed by the validation evidence.
- **Rationale:** The Laravel status shows DiscoveryFiltersController modified to depend on App\Application\DiscoveryFilters\DiscoveryFilterPublicCatalogService, while app/Application/DiscoveryFilters/DiscoveryFilterPublicCatalogService.php and tests/Feature/Events/EventQueryPerformanceGuardrailTest.php appear only in untracked/laravel-app.contents.patch. Validation evidence therefore reflects a local working tree that includes files not present in the recorded HEAD. A branch/CI/promotion flow that consumes only tracked commits can fail runtime class resolution for the public discovery filter catalog and silently drop the performance guardrail.

### F-3A9F5872 [medium] Occurrence-based management pagination still performs heavy pre-pagination lookup work twice
- **Reviewers:** performance-operational-fit
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either denormalize the event-side fields needed for temporal management filtering onto occurrences, or normalize occurrence event ids so the aggregation can use an index-friendly lookup. Add an explain-backed guardrail or integration test that proves the temporal management path does not perform collection-scan lookup behavior and does not duplicate the full expensive pipeline for count and page data.
- **Rationale:** The working-tree EventQueryService replaces PHP-side pluck materialization with buildManagementOccurrenceEventPipeline, which is directionally better, but the pipeline groups matching occurrences, sorts all grouped event ids, performs a lookup into events using expr with toString on _id, unwinds, and applies event filters before skip/limit. The same pipeline is executed once for count and again for page rows. For large tenants, this can still scan or repeatedly join many distinct events before pagination, and the string-based lookup shape is unlikely to be as index-friendly as a normalized localField/foreignField join or denormalized occurrence read model.

### F-48014FB5 [low] Primary filter row still eagerly constructs chip widgets before using ListView.separated
- **Reviewers:** performance-operational-fit
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move eager chip construction inside the wrap-only branch, and keep the horizontal path exclusively itemBuilder-driven. Add a focused widget test or static guard that prevents prebuilding the horizontal chip list when policy.primaryLayoutMode is not wrap.
- **Rationale:** The working-tree discovery_filter_bar.dart switches the horizontal primary row to ListView.separated, but _buildPrimaryRow still computes final chips = filters.map(...).toList() before the layout-mode branch. In horizontal mode those prebuilt widgets are unused because itemBuilder constructs chips again from filters[index]. This leaves avoidable build work in the exact row-virtualization path the package is hardening.

## Reviewer Summaries
### performance-operational-fit
- **Assessment:** Not clean for release promotion. The package materially improves runtime posture with batched occurrence formatting, ListView-based horizontal filter rows, and real click-to-query Playwright proof, but the bounded state still has reproducibility and query-plan risks that should be resolved before treating this as operationally release-ready.
- **Recommended path:** `Resolve the untracked-source promotion risk and harden the occurrence-driven management query with an explain-backed or query-count guardrail. The remaining primary-row eager widget construction can be handled as a low-risk cleanup, but it should be corrected because row virtualization is an explicit scope objective.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] PERF-OPFIT-001 Release-critical Laravel source and guardrail files are untracked in the captured package: The Laravel status shows DiscoveryFiltersController modified to depend on App\Application\DiscoveryFilters\DiscoveryFilterPublicCatalogService, while app/Application/DiscoveryFilters/DiscoveryFilterPublicCatalogService.php and tests/Feature/Events/EventQueryPerformanceGuardrailTest.php appear only in untracked/laravel-app.contents.patch. Validation evidence therefore reflects a local working tree that includes files not present in the recorded HEAD. A branch/CI/promotion flow that consumes only tracked commits can fail runtime class resolution for the public discovery filter catalog and silently drop the performance guardrail.
  - [medium] PERF-OPFIT-002 Occurrence-based management pagination still performs heavy pre-pagination lookup work twice: The working-tree EventQueryService replaces PHP-side pluck materialization with buildManagementOccurrenceEventPipeline, which is directionally better, but the pipeline groups matching occurrences, sorts all grouped event ids, performs a lookup into events using expr with toString on _id, unwinds, and applies event filters before skip/limit. The same pipeline is executed once for count and again for page rows. For large tenants, this can still scan or repeatedly join many distinct events before pagination, and the string-based lookup shape is unlikely to be as index-friendly as a normalized localField/foreignField join or denormalized occurrence read model.
  - [low] PERF-OPFIT-003 Primary filter row still eagerly constructs chip widgets before using ListView.separated: The working-tree discovery_filter_bar.dart switches the horizontal primary row to ListView.separated, but _buildPrimaryRow still computes final chips = filters.map(...).toList() before the layout-mode branch. In horizontal mode those prebuilt widgets are unused because itemBuilder constructs chips again from filters[index]. This leaves avoidable build work in the exact row-virtualization path the package is hardening.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

