# Session Memory

## Artifact Role
- **Purpose:** bounded continuity + confirmed preferences/behaviors + dependency references.
- **What it is not:** canonical contract, approval ledger, or authority for mixed-scope execution.
- **Related derived surface:** generated runtime index / session handoff index may summarize this file, but must remain regenerable.

## Update Policy
- **Auto-eligible updates:**
  - latest session continuity summary;
  - dependency statuses touched during the session.
- **Confirmation required before updating:**
  - stable user preferences;
  - learned operational behaviors that should persist across sessions.
- **Never update here instead of canonical docs:**
  - architectural decisions;
  - module/constitution/roadmap truth;
  - tactical TODO approvals or profile handoffs.

## Latest Session Continuity
- **Last updated:** `2026-04-18 11:11 -03`
- **Current active TODO:** `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- **Current active front:** Store-release documentation is now coherent enough for no-context handoff across the current Android-release lanes. The recommended execution order is to implement `phone-otp-auth-and-contact-match` first, then return to `web-to-app-conversion-gate` for the full install/deferred/auth-wall manual funnel closure.
- **Last confirmed truth:** `TODO-store-release-minimal-friends-and-favorites-mvp.md` now carries the frozen contacts/favorites/friends business contract, plus explicit exploratory-only Quóa UX references for `/convites/compartilhar` and dedicated `contact_groups` management. `TODO-ios-universal-links-production-validation.md` was tightened for no-context handoff with frozen scope/dependency boundaries. `Convite Nativo` is explicitly not a reference for the invite-composer lane. Post-session deterministic validation passed for `TODO-store-release-web-to-app-conversion-gate.md`, but reported structural format drift in `TODO-store-release-minimal-friends-and-favorites-mvp.md`, `TODO-ios-universal-links-production-validation.md`, and `TODO-store-release-phone-otp-auth-and-contact-match.md` around canonical delivery-stage/qualifier encoding and missing gate sections.
- **Next likely step:** Open `TODO-store-release-phone-otp-auth-and-contact-match.md` and execute the backend/Flutter phone-OTP cutover first; after that, resume `TODO-store-release-web-to-app-conversion-gate.md` to validate the complete promotion -> deferred -> restricted-action -> auth-wall funnel on Android.

## Confirmed User Preferences
- none

## Confirmed Learned Behaviors
- none

## Dependency References
- **Dependency readiness register:** `foundation_documentation/artifacts/dependency-readiness.md`
- **Relevant status carry-over:** backlog authority now uses the flattened active-lane model `store_release_android` + `fast_follow_required` + `vnext`; resume work should open those lane TODOs first and treat older lane names as historical drift only.
