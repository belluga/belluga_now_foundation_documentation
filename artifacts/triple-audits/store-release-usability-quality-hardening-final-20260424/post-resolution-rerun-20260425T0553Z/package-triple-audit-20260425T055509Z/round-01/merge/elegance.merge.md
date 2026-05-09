# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/post-resolution-rerun-20260425T0553Z/package-triple-audit-20260425T055509Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the structural findings before closing the gate: restore package-local/shared sanitizer ownership, fix selected-occurrence temporal remapping through a single mapper/copy path, and replace coordinate-click Playwright fallbacks with semantic targets or explicit fail-fast evidence.`

## Merged Findings
### F-F51D30A5 [high] Reusable events package now depends on the host App namespace
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move the shared sanitizer into package-owned code or a package-level shared support dependency, then have App wrappers depend inward on package code rather than package code importing App. Add a package architecture guard forbidding App\ imports under packages/belluga/*/src.
- **Rationale:** The bounded diff changes Belluga\Events\Support\EventContentHtmlSanitizer inside packages/belluga/belluga_events to import App\Support\RichText\SafeRichTextHtmlSanitizer. That makes package code depend on an application-layer class, undermining package-first ownership and making the package non-portable outside this Laravel app.

### F-D6183007 [medium] Selected occurrence remapping mixes occurrence start with parent event end
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Set dateTimeEnd from selectedOccurrence.dateTimeEndValue when selecting an occurrence, preferably through an EventModel copy/selection mapper so future EventModel fields cannot be accidentally omitted or mixed.
- **Rationale:** ImmersiveEventDetailController._eventWithSelectedOccurrence rebuilds EventModel with dateTimeStart from the selected occurrence but keeps dateTimeEnd from the original event. This creates a drift-prone manual copy path and can render an inconsistent time range when the selected occurrence has its own end time.

### F-F79819D1 [medium] Navigation evidence still relies on coordinate and retry fallbacks
- **Reviewers:** elegance
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace coordinate fallbacks with stable role/label/key-based locators and fail fast when the semantic target is absent. If a temporary coordinate fallback is unavoidable, isolate it behind an explicit allowlist with a tracked remediation reason.
- **Rationale:** The bounded Playwright diff adds helpers that click locator centers, viewport coordinates, first generic buttons, and retry loops with waitForTimeout. That weakens the claimed visible-behavior evidence because the tests can pass even when semantic hit targets, accessibility labels, or intended tappable affordances regress.

## Reviewer Summaries
### elegance
- **Assessment:** Not clean for the elegance lane. The bounded package shows substantial hardening, but it still carries structural drift in package boundaries, a manual occurrence-selection remapping defect, and browser evidence that relies on coordinate and retry fallbacks.
- **Recommended path:** `Resolve the structural findings before closing the gate: restore package-local/shared sanitizer ownership, fix selected-occurrence temporal remapping through a single mapper/copy path, and replace coordinate-click Playwright fallbacks with semantic targets or explicit fail-fast evidence.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-RERUN-001 Reusable events package now depends on the host App namespace: The bounded diff changes Belluga\Events\Support\EventContentHtmlSanitizer inside packages/belluga/belluga_events to import App\Support\RichText\SafeRichTextHtmlSanitizer. That makes package code depend on an application-layer class, undermining package-first ownership and making the package non-portable outside this Laravel app.
  - [medium] ELEGANCE-RERUN-002 Selected occurrence remapping mixes occurrence start with parent event end: ImmersiveEventDetailController._eventWithSelectedOccurrence rebuilds EventModel with dateTimeStart from the selected occurrence but keeps dateTimeEnd from the original event. This creates a drift-prone manual copy path and can render an inconsistent time range when the selected occurrence has its own end time.
  - [medium] ELEGANCE-RERUN-003 Navigation evidence still relies on coordinate and retry fallbacks: The bounded Playwright diff adds helpers that click locator centers, viewport coordinates, first generic buttons, and retry loops with waitForTimeout. That weakens the claimed visible-behavior evidence because the tests can pass even when semantic hit targets, accessibility labels, or intended tappable affordances regress.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

