# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-06/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the temporal/date predicate composition issue before final closure. Treat the sanitizer and Playwright guard findings as hardening follow-ups if release timing is constrained, but they should be addressed before relying on these helpers as canonical structural patterns.`

## Merged Findings
### F-042CFBD6 [medium] Occurrence management query overwrites temporal future filtering when a date is also supplied
- **Reviewers:** round-06-elegance-structural-no-context
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Compose date and temporal predicates under $and instead of reusing the same starts_at key destructively. Add a negative regression case for date in the past with temporal=future, and ideally the inverse cases for temporal=past/now where date selection should still intersect.
- **Rationale:** EventQueryService routes any non-archived management request with temporal buckets or a specific date through EventManagementOccurrenceQuery. In EventManagementOccurrenceQuery, temporal=future first writes a starts_at > now predicate, but the later specific-date block replaces starts_at with the day range. That means date + temporal=future no longer composes as an intersection for a past selected date; it returns occurrences on that date even though they are not future. The existing composition test only uses a future target date, so it does not prove the predicates compose in the conflicting case.

### F-6199C00E [medium] Flutter and PHP rich-text sanitizers encode the same policy with different parsing models
- **Reviewers:** round-06-elegance-structural-no-context
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either move Flutter sanitization to a parser-backed implementation with the same semantic behavior, or establish shared cross-stack sanitizer fixtures where identical inputs and expected canonical outputs are asserted in both PHP and Dart. Keep backend sanitization authoritative, but make preview/import fidelity deterministic.
- **Rationale:** The backend sanitizer uses DOMDocument, unwraps unsupported non-dangerous nodes structurally, removes script/style nodes, normalizes entities and break tags, and wraps inline fragments. The Flutter SafeRichHtml helper reimplements the policy with regular expressions and its own escaping behavior. This is structurally fragile because admin preview/import behavior can diverge from the backend persisted result for malformed fragments, mixed text plus tags, entity handling, and unsupported nested containers. The tests cover a happy subset but do not establish cross-stack fixture parity.

### F-1B9472F9 [low] Web navigation policy guard is too helper-name-specific to be a durable release gate
- **Reviewers:** round-06-elegance-structural-no-context
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace the helper-name regex scan with an AST-based or broader deterministic rule over all release-gating spec code, or centralize dropdown selection in one imported helper and forbid local helper definitions. Add guard self-tests that prove renamed helpers, duplicate helpers, inline text-click fallback, and keyboard option fallback are rejected.
- **Rationale:** The policy guard now blocks coordinate clicks, force clicks, and dropdown text/keyboard fallbacks, but the dropdown check only slices the first function literally named selectDropdownOption in each file and only searches for a narrow set of patterns inside that helper. A renamed helper, a second helper, or inline dropdown fallback can bypass the policy while still being part of release-gating mutation specs. That weakens the operational claim that the policy blocks non-semantic dropdown selection.

## Reviewer Summaries
### round-06-elegance-structural-no-context
- **Assessment:** Mixed. The bounded work resolves many prior structural concerns and shows meaningful package-boundary and performance hardening, but the final shape still has a few material elegance/structural risks: one combined-query predicate regression, one cross-stack rich-text policy drift risk, and one release-gate policy helper that is too name/regex-specific to serve as a durable deterministic guard.
- **Recommended path:** `Resolve the temporal/date predicate composition issue before final closure. Treat the sanitizer and Playwright guard findings as hardening follow-ups if release timing is constrained, but they should be addressed before relying on these helpers as canonical structural patterns.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] R06-ELEGANCE-01 Occurrence management query overwrites temporal future filtering when a date is also supplied: EventQueryService routes any non-archived management request with temporal buckets or a specific date through EventManagementOccurrenceQuery. In EventManagementOccurrenceQuery, temporal=future first writes a starts_at > now predicate, but the later specific-date block replaces starts_at with the day range. That means date + temporal=future no longer composes as an intersection for a past selected date; it returns occurrences on that date even though they are not future. The existing composition test only uses a future target date, so it does not prove the predicates compose in the conflicting case.
  - [medium] R06-ELEGANCE-02 Flutter and PHP rich-text sanitizers encode the same policy with different parsing models: The backend sanitizer uses DOMDocument, unwraps unsupported non-dangerous nodes structurally, removes script/style nodes, normalizes entities and break tags, and wraps inline fragments. The Flutter SafeRichHtml helper reimplements the policy with regular expressions and its own escaping behavior. This is structurally fragile because admin preview/import behavior can diverge from the backend persisted result for malformed fragments, mixed text plus tags, entity handling, and unsupported nested containers. The tests cover a happy subset but do not establish cross-stack fixture parity.
  - [low] R06-ELEGANCE-03 Web navigation policy guard is too helper-name-specific to be a durable release gate: The policy guard now blocks coordinate clicks, force clicks, and dropdown text/keyboard fallbacks, but the dropdown check only slices the first function literally named selectDropdownOption in each file and only searches for a narrow set of patterns inside that helper. A renamed helper, a second helper, or inline dropdown fallback can bypass the policy while still being part of release-gating mutation specs. That weakens the operational claim that the policy blocks non-semantic dropdown selection.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

