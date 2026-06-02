# Security Review: Web Favorite Promotion Gate Canonicalization

## Scope
- Governing TODO: `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-web-favorite-promotion-gate-canonicalization.md`
- Review type: `security-adversarial-review`
- Risk trigger: auth-visible web gate behavior for anonymous favorite actions.

## Security Risk Level
- Level: `low`
- Attack simulation decision: `not_needed`
- Rationale: this TODO changes client-side presentation/gate routing only. It does not introduce new backend endpoints, token handling, credential storage, authorization rules, tenant resolution, or persistence mutations. The main security requirement is fail-closed behavior before favorite mutation and no web phone-login spoof path.

## Threat-Intel Refresh
- Decision: `not_needed`
- Rationale: no dependency, framework security advisory, backend auth/session implementation, or externally callable API surface changed. Current high-trust external intelligence would not materially change the local review outcome.

## Attack Surface Mapping
| Surface | Boundary | Review Result |
| --- | --- | --- |
| Anonymous web favorite click | Public tenant web UI to auth/promotion gate | The gate tracks auth-wall telemetry with `allowPendingActionReplay: false` and opens `AppPromotionModal.show`; it does not redirect to `/auth/login`, `/baixe-o-app`, or `/open-app` before explicit CTA. |
| Non-web anonymous favorite click | App UI to canonical login route | Existing login redirect/pending replay remains isolated to non-web runtime with `allowPendingActionReplay: true`. |
| Favorite mutation controllers | UI/controller to favorite repository mutation | Controllers fail closed when auth repository is missing/unauthorized and return `requiresAuthentication` before mutation. |
| Promotion modal CTA | Web modal to app/store URI handoff | URI construction and launch path remain inside `AppPromotionScreenController` through `AppPromotionStoreActions`; handoff occurs only on explicit badge tap. |
| Store target rendering | Tenant app publication settings to UI | Modal store actions use controller `storePlatformsToRender` and existing controller URI methods; widget tests cover Android-only, iOS-only, both, no explicit config, and no active targets. |

## Adversarial Hypotheses
| Hypothesis | Validation | Outcome |
| --- | --- | --- |
| Anonymous web click bypasses auth and mutates favorite. | Focused controller/screen tests cover `requiresAuthentication` before mutation; browser diagnostic verifies modal-first behavior. | Not reproduced. |
| Anonymous web click immediately opens the app/store without consent. | Playwright diagnostic asserts no immediate `/open-app`; modal CTA is the only handoff path. | Not reproduced. |
| Web shows phone-login UI and creates a phishing-like/unsupported login path. | Widget/runtime assertions target app-promotion modal and negative login text/route behavior. | Not reproduced. |
| Modal bypasses publication settings and exposes inactive store CTAs. | `app_promotion_modal_test.dart` covers publication variants through controller-backed store rendering. | Not reproduced. |
| UI directly resolves repositories/services and bypasses architecture guard. | Analyzer rule matrix passed after replacing UI repository resolution fallback with controller constructor fallback. | Not reproduced. |

## Findings
- No security blocker.
- Residual risk: low. The remaining `AppPromotionDialog` class exists outside this TODO scope; source scan confirms favorite-gate paths do not call `AppPromotionDialog.show`.

## Verification Evidence
- `fvm dart analyze --format machine` passed.
- `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` passed.
- Focused Flutter tests passed.
- Rebuilt web bundle and source-owned Playwright diagnostic passed.

