# TODO (V1): Screen Polish - User Profile (Authenticated `/profile`)

**Scope authority note (2026-04-17; updated 2026-04-28):** this TODO owns only the authenticated user-profile route `/profile`. It does not overlap tenant-public Account Profile discovery or detail polish, which are covered by the completed `TODO-v1-public-account-profile-discovery-ui.md` plus the completed `TODO-v1-screen-public-account-profile-detail-polish.md`.

**Status legend:** `- [x] ⚪ Pending` · `- [ ] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Close the authenticated tenant-public `/profile` release scope first, then polish only the approved release surface without contradicting the phone-OTP/auth-boundary policy.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Scope-Closure-Required`, `Store-Release-Candidate`, `Auth-Boundary-Sensitive`
- **Next exact step:** close the `/profile` Store Release scope by deciding which visible fields/actions stay, which become read-only, and which are removed/deferred before any UI implementation starts.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-ui-polish-batch-auth-profile-events-invite.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Terminology
- `User Profile` means the authenticated self/profile route `/profile`; this TODO owns that surface.
- `Public Account Profile` means the tenant-public public identity route `/parceiro/:slug`; it is not owned by this TODO.

## Scope (Single Screen)
- First close the Store Release scope for authenticated `/profile`.
- Improve the approved signed-in profile layout and action hierarchy after the scope is closed.
- Preserve the existing authenticated route behavior and guard-owned auth boundary.
- Ensure `/profile` does not expose fake edit/save affordances, password/email-login implications, or phone edits that conflict with the phone-OTP identity baseline.

## Out of Scope
- Backend/API/profile schema changes.
- New profile feature expansion.
- Any signed-out inline profile rendering under `/profile`.
- Account Profile discovery/detail surfaces (`/descobrir`, `/parceiro/:slug`).
- Implementing phone-OTP itself; that remains owned by `TODO-store-release-phone-otp-auth-and-contact-match.md`.
- Implementing the broader proximity-preferences lane unless explicitly kept in this TODO after scope closure.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; profile/auth contracts remain unchanged.
- `D-02`: `/profile` remains auth-gated. Anonymous app access keeps the current phone-auth/login behavior and anonymous web access keeps the current app-promotion boundary behavior.
- `D-03`: Signed-in state keeps current action semantics (favorites, navigation, and account-linked actions).
- `D-04`: Loading/error/content states and auth-route handoff remain behavior-compatible with current controller/guard flow.
- `D-05`: No new profile capabilities are introduced in this TODO (polish only).
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Scope Closure Suggestions (Not Yet Frozen)
- `S-01` Phone field: do not treat phone as a normal editable text field. Because phone-OTP is the canonical identity anchor, phone should be read-only for Store Release or routed to a dedicated OTP/reverification flow owned by the OTP TODO.
- `S-02` Email/password affordances: remove or replace any UI that implies tenant-public email/password auth. `Alterar senha` should not remain visible in the release `/profile` surface while tenant-public auth is phone-OTP only.
- `S-03` Profile persistence: do not ship fake edit/save behavior. Either implement real persistence for the approved fields, or remove/disable those edit affordances for Store Release.
- `S-04` Proximity/location preferences: decide whether radius/origin editing stays in this TODO. If the broader proximity-preferences lane moves out of Store Release, `/profile` should not keep release-blocking proximity editing here.
- `S-05` Privacy/visibility: decide whether Store Release needs a real minimal privacy control on `/profile` or backend defaults are enough. Avoid visible "coming soon" affordances on release surfaces.
- `S-06` Header metrics: do not show hardcoded invite metrics. Either bind real values from a trusted source or hide the metrics for Store Release.
- `S-07` Anonymous profile card: `/profile` is auth-gated by policy. Remove/quarantine signed-out inline profile UI from the release path unless a separate approved use exists outside `/profile`.

## Decision Pending (Resolve Before Implementation)
- [ ] `DP-01` Which fields are visible on `/profile` for Store Release: name, description, avatar, email, phone, location preference, privacy, metrics.
- [ ] `DP-02` Which visible fields are editable, read-only, or hidden for Store Release.
- [ ] `DP-03` Whether profile edits must persist to backend in this TODO or whether edit affordances are removed/deferred.
- [ ] `DP-04` Whether phone changes are completely deferred to the OTP lane or exposed as a read-only verified identity state.
- [ ] `DP-05` Whether privacy/visibility control is required in this release surface or remains backend-default-only.
- [ ] `DP-06` Whether proximity/origin controls stay in this TODO or are removed/deferred with the proximity-preferences lane.
- [ ] `DP-07` Whether invite/social metrics are displayed from real data or hidden until a reliable metric source is available.

## Tasks
- [ ] ⚪ Close the `/profile` Store Release scope and freeze the visible/editable/read-only/hidden field matrix.
- [ ] ⚪ Polish signed-in profile layout and action hierarchy.
- [ ] ⚪ Ensure loading/empty/error/content states are visually explicit.
- [ ] ⚪ Validate typography/spacing consistency on mobile breakpoints.
- [ ] ⚪ Ensure authenticated `/profile` routing and guard handoff remain stable with no route regressions.

## Acceptance Criteria
- [ ] ⚪ `/profile` release scope is explicitly closed before implementation starts.
- [ ] ⚪ The final UI contains no fake edit/save actions, hardcoded metrics, password-login implications, or phone-edit behavior that conflicts with phone-OTP.
- [ ] ⚪ Signed-in profile exposes primary actions with clearer hierarchy and no semantic changes.
- [ ] ⚪ Loading/error/content states are explicit and readable on the authenticated profile surface.
- [ ] ⚪ No regression in auth-gated profile actions or navigation.
- [ ] ⚪ No signed-out inline profile behavior is introduced under `/profile`.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Authenticated `/profile` smoke path is recorded and auth-boundary behavior is rechecked for regressions.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: signed-in state actions and navigation.
- [ ] ⚪ Manual smoke: loading/error state readability.
- [ ] ⚪ Manual smoke: anonymous app access to `/profile` still resolves through the existing auth/login path.
- [ ] ⚪ Manual smoke: anonymous web access to `/profile` still resolves through the existing app-promotion boundary.
