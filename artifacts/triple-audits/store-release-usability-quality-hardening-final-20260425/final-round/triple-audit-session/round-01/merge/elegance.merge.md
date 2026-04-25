# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Resolve the medium findings before treating the elegance lane as clean. Both are localizable refactors and should not require reopening unrelated architecture.`

## Merged Findings
### F-9B3C5958 [medium] Rich-text sanitization policy is duplicated across backend package, host app, and Flutter client
- **Reviewers:** elegance-clean-code
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extract the PHP sanitizer/policy into a neutral shared package that both the host app and events package can depend on, or at minimum centralize the sanitizer contract in shared fixtures and run parity tests for account-profile content, event content, and Flutter rendering. Keep the events package decoupled from `App\\`, but do not maintain copy-pasted sanitizer logic as the long-term boundary solution.
- **Rationale:** The PHP host sanitizer and events-package sanitizer carry effectively the same allowlist and DOM normalization implementation independently: `laravel-app/app/Support/RichText/SafeRichTextHtmlSanitizer.php:13` through `:278` mirrors `laravel-app/packages/belluga/belluga_events/src/Support/EventContentHtmlSanitizer.php:13` through `:278`. Flutter then introduces a third sanitizer/allowlist using regex-based behavior in `flutter-app/lib/application/rich_text/safe_rich_html.dart:62` through `:155`. This avoids the package boundary violation called out in the package, but it creates three places where allowed tags, empty-content behavior, break normalization, and unsupported tag handling can drift. The current package decoupling test only blocks `App\\` references in the events package, while the account-profile fidelity test asserts the host sanitizer does not import the events sanitizer; neither establishes a single contract for sanitizer parity.

### F-FB76447F [medium] Event form build tree triggers controller state mutation and async loading
- **Reviewers:** elegance-clean-code
- **Category:** `elegance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move default venue/type hydration into the controller dependency-loading path, or trigger it from an explicit guarded post-load orchestration method outside `build`. Keep the widget tree declarative by deriving display state from controller streams rather than invoking mutation methods while composing UI.
- **Rationale:** `TenantAdminEventFormScreen.build` nests a long chain of `StreamValueBuilder`s and invokes controller hydration directly inside builder callbacks at `flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_event_form_screen.dart:83` and `:102`. Those calls mutate form state through `_replaceEventFormState` in `hydrateDefaultEventVenue` and `hydrateDefaultEventType` (`tenant_admin_events_controller.dart:811` through `:860`), and `hydrateDefaultEventType` can also start `_loadTermsForSelectedEventType()` asynchronously from the build path (`:852` and `:860`). The controller has some guards and cache checks, but the widget still mixes render composition with mutation/loading orchestration, making rebuild behavior harder to reason about and increasing the risk of redundant async work or build-triggered state loops as the form grows.

## Reviewer Summaries
### elegance-clean-code
- **Assessment:** Mixed. The bounded package shows meaningful hardening, but two structural clean-code issues remain: rich-text sanitization policy is copied across multiple implementations, and the event form still performs controller mutations from nested build callbacks.
- **Recommended path:** `Resolve the medium findings before treating the elegance lane as clean. Both are localizable refactors and should not require reopening unrelated architecture.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] elegance-001 Rich-text sanitization policy is duplicated across backend package, host app, and Flutter client: The PHP host sanitizer and events-package sanitizer carry effectively the same allowlist and DOM normalization implementation independently: `laravel-app/app/Support/RichText/SafeRichTextHtmlSanitizer.php:13` through `:278` mirrors `laravel-app/packages/belluga/belluga_events/src/Support/EventContentHtmlSanitizer.php:13` through `:278`. Flutter then introduces a third sanitizer/allowlist using regex-based behavior in `flutter-app/lib/application/rich_text/safe_rich_html.dart:62` through `:155`. This avoids the package boundary violation called out in the package, but it creates three places where allowed tags, empty-content behavior, break normalization, and unsupported tag handling can drift. The current package decoupling test only blocks `App\\` references in the events package, while the account-profile fidelity test asserts the host sanitizer does not import the events sanitizer; neither establishes a single contract for sanitizer parity.
  - [medium] elegance-002 Event form build tree triggers controller state mutation and async loading: `TenantAdminEventFormScreen.build` nests a long chain of `StreamValueBuilder`s and invokes controller hydration directly inside builder callbacks at `flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_event_form_screen.dart:83` and `:102`. Those calls mutate form state through `_replaceEventFormState` in `hydrateDefaultEventVenue` and `hydrateDefaultEventType` (`tenant_admin_events_controller.dart:811` through `:860`), and `hydrateDefaultEventType` can also start `_loadTermsForSelectedEventType()` asynchronously from the build path (`:852` and `:860`). The controller has some guards and cache checks, but the widget still mixes render composition with mutation/loading orchestration, making rebuild behavior harder to reason about and increasing the risk of redundant async work or build-triggered state loops as the form grows.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

