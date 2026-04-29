I have a complete picture of the implementation. Here is the T1 gate review.

---

## T1 Review — Web-to-App Conversion Gate

---

### BLOCKING findings

**None.**

---

### ACCEPTED-DEBT findings

---

**[AD-1] `/convites/compartilhar` continuation path has no test**

`_isAllowedAuthOwnedAppContinuationPath` explicitly allows `/convites/compartilhar` alongside `/profile`. `buildTenantPromotionUri` tests cover `/profile` as the representative auth-owned case, but there is no test that passes `redirectPath: '/convites/compartilhar'` to `buildTenantPromotionUri` or `resolveWebPromotionPath` and asserts the preserved output. The code is correct (both functions share the same helper), but the invariant is untested for this second member of the allowlist.

Non-blocking because the mechanism is exercised by the `/profile` test and the helper is minimal. Should be added before T2 if `/convites/compartilhar` is a live promotion entry point.

---

**[AD-2] `/auth/login` unwrapping recursion is unbounded**

`_resolveAllowedPromotionRedirectPath` recurses on the `redirect` query parameter of `/auth/...` paths without a depth guard. A deeply nested chain like `/auth/login?redirect=%2Fauth%2Flogin%3Fredirect%3D...` (≈50 levels) would stack-recurse until Dart stack overflow. Practical exploitability is low (requires control of the redirect path, which in the web promotion context comes from the app's own web boundary builder), but no depth cap is documented.

Non-blocking because the input surface is controlled by `buildWebPromotionBoundaryPath` in normal flows, not free user input. Document the missing depth guard as a known constraint.

---

**[AD-3] Silent `shareCode` drop when `redirectPath` is non-invite and non-null — no call-site contract**

In `buildTenantPromotionUriFromAppContext`, an explicit `shareCode` argument is silently discarded whenever `redirectPath` is present and does not resolve to an invite code. The test `buildTenantPromotionUri preserves event detail continuation intent` passes `shareCode: 'CODE123'` and correctly expects `containsKey('code')` to be `false`. This is correct behavior, but no doc-comment on the function or the containing file explains the priority rule (`redirect context code > null when redirectPath is set`). Callers who pass both can be surprised.

Non-blocking; annotate in the function contract before T2.

---

### OUT-OF-SCOPE findings

- ADB/Play Store install validation, deferred deep-link capture, first-open restore: intentionally deferred to the consolidated ADB phase per frozen contract.
- Any `open-app` web-layer behavior changes: no changes in this package.
- `/baixe-o-app` web-app screen rendering (web submodule not in this review scope).

---

### Affirmative notes

**Redirect allowlist security:** The allowlist is strict. Absolute URLs (`uri.hasScheme`), authority-qualified URLs (`uri.hasAuthority`), and scheme-relative URLs (`raw.startsWith('//')`) are all blocked before path analysis begins. Dot-segment traversal attacks (`/agenda/evento/../..`) are neutralised by `_pathSegments` filtering — a four-segment result fails the 3-segment event-detail check and the 2-segment prefix check. Auth-owned paths are structurally isolated behind `includeAuthOwnedAppPaths` and the secondary `_isAllowedAuthOwnedAppContinuationPath` guard, making web-boundary vs. app-URI semantics explicit and non-confusable.

**Query parameter strip discipline:** Only `occurrence` (event detail) and `poi`/`stack` (map) survive; all other query parameters are dropped before the path is returned. Auth-owned continuation paths (`/profile`, `/convites/compartilhar`) have their query parameters stripped entirely. This is the correct posture for a promotion handoff.

**Default `appDownload` coverage:** `_registerDefaultControllers` correctly omits the `experience` override, meaning the new test exercises `_resolveHardcodedPromotionExperience()` as real production code would. The prior waitlist test is correctly renamed and pinned to an explicit override, so both paths remain covered.

**Anonymous favorite removal is contained:** `_authRepository` is fully removed from `DiscoveryScreenController` (import, constructor param, field, getter — all gone). In `AccountProfileDetailController` the repository is retained (still needed for `authenticatedUserDisplayName`), but the `_isAuthorized` getter and its two call sites are removed. Only the three specified entrypoints lost auth gating; attendance/check-in and other restricted actions are untouched in all three controllers.

**Test inversion in immersive:** Changing the `ImmersiveEventDetailController` test from `authorized: true` to `authorized: false` is semantically correct — the most meaningful proof of the behavior change is showing that an unauthorized auth state no longer produces a gate outcome. The test passes the assertion that the screen renders with the correct action buttons regardless of auth state.

**Overall verdict:** T1 gate can pass. The three accepted-debt items are non-blocking and scoped to future test/doc hardening rather than correctness or security gaps.
