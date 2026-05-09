# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the two findings before treating the final hardening pass as clean. Keep the existing validation evidence, but add targeted regression tests for raw rich-text size rejection before sanitizer work and stale/missing taxonomy_terms_flat repair on already-snapshotted account profiles.`

## Merged Findings
### F-1DF1450B [medium] Taxonomy snapshot repair can skip stale flat taxonomy projections
- **Reviewers:** performance-security
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `flat-taxonomy-projection-repair-independent-of-snapshot-change`
- **Suggested action:** When refreshFlatTerms is true, compute the expected flat projection from resolved terms and compare it independently against the current taxonomy_terms_flat. Save when either taxonomy_terms or taxonomy_terms_flat changes, and add a test for a snapshot-complete profile with missing/stale flat terms.
- **Rationale:** TaxonomySnapshotBackfillService only writes taxonomy_terms_flat inside the branch where taxonomy_terms changed at app/Application/Taxonomies/TaxonomySnapshotBackfillService.php:87-93. If an AccountProfile already has resolved taxonomy_terms but taxonomy_terms_flat is missing or stale, the service increments skipped at line 96 and never repairs the flat field. Public profile filtering now depends on taxonomy_terms_flat at app/Application/AccountProfiles/AccountProfileQueryService.php:641, so this can silently exclude valid profiles from public filters even after running the advertised repair.

### F-209DE8B6 [medium] Account-profile rich text is unbounded until after DOM parsing
- **Reviewers:** performance-security
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `rich-text-raw-size-before-dom-parse`
- **Suggested action:** Reject raw bio/content above the agreed byte budget in FormRequest validation or before calling DOMDocument, then keep the post-sanitization limit as a second guard. Cover create, update, and onboarding with oversized raw HTML tests.
- **Rationale:** AccountProfileStoreRequest, AccountProfileUpdateRequest, and AccountOnboardingStoreRequest now validate bio/content only as strings at app/Http/Api/v1/Requests/AccountProfileStoreRequest.php:32, app/Http/Api/v1/Requests/AccountProfileUpdateRequest.php:32, and app/Http/Api/v1/Requests/AccountOnboardingStoreRequest.php:32. The 100 KB limit is applied only after AccountProfileRichTextSanitizer calls sanitize at app/Application/AccountProfiles/AccountProfileRichTextSanitizer.php:26, which builds a DOMDocument in app/Support/RichText/SafeRichTextHtmlSanitizer.php:42. Oversized HTML can therefore consume parser CPU/memory before rejection, creating an avoidable request-amplification/DoS surface.

## Reviewer Summaries
### performance-security
- **Assessment:** Mixed. The package contains meaningful performance hardening, especially occurrence bulk loading and indexed flat taxonomy filtering, but two bounded issues remain: account-profile rich text accepts unbounded raw strings before DOM sanitization, and the taxonomy snapshot repair path can leave the flat filter projection stale when snapshots are already resolved.
- **Recommended path:** `Resolve the two findings before treating the final hardening pass as clean. Keep the existing validation evidence, but add targeted regression tests for raw rich-text size rejection before sanitizer work and stale/missing taxonomy_terms_flat repair on already-snapshotted account profiles.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] perf-sec-001 Account-profile rich text is unbounded until after DOM parsing: AccountProfileStoreRequest, AccountProfileUpdateRequest, and AccountOnboardingStoreRequest now validate bio/content only as strings at app/Http/Api/v1/Requests/AccountProfileStoreRequest.php:32, app/Http/Api/v1/Requests/AccountProfileUpdateRequest.php:32, and app/Http/Api/v1/Requests/AccountOnboardingStoreRequest.php:32. The 100 KB limit is applied only after AccountProfileRichTextSanitizer calls sanitize at app/Application/AccountProfiles/AccountProfileRichTextSanitizer.php:26, which builds a DOMDocument in app/Support/RichText/SafeRichTextHtmlSanitizer.php:42. Oversized HTML can therefore consume parser CPU/memory before rejection, creating an avoidable request-amplification/DoS surface.
  - [medium] perf-sec-002 Taxonomy snapshot repair can skip stale flat taxonomy projections: TaxonomySnapshotBackfillService only writes taxonomy_terms_flat inside the branch where taxonomy_terms changed at app/Application/Taxonomies/TaxonomySnapshotBackfillService.php:87-93. If an AccountProfile already has resolved taxonomy_terms but taxonomy_terms_flat is missing or stale, the service increments skipped at line 96 and never repairs the flat field. Public profile filtering now depends on taxonomy_terms_flat at app/Application/AccountProfiles/AccountProfileQueryService.php:641, so this can silently exclude valid profiles from public filters even after running the advertised repair.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

