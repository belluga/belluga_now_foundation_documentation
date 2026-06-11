# TODO (Store Release): Web-to-App Conversion Gate

**Split archival note (2026-06-08, refreshed 2026-06-11):** this TODO was a stale super-packet mixing already-promoted product behavior with still-missing runtime/startup validation. On `2026-06-08`, the delivered slice was archived here and the remaining Android runtime/store/deferred proof was split out. On `2026-06-11`, that residual owner was absorbed into `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`, which now owns the current-package startup/bootstrap correction explicitly.

**Status:** Completed historical archival catch-up on `2026-06-08`. `origin/main` already carries the delivered promotion-boundary, publication-target, redirect-preservation, and installed-app handoff behavior that this TODO used to own. This completed artifact now closes only that delivered slice.
**Owners:** Delphi (Product/Flutter) + Backend Team + Web Team
**Goal:** archive the already-promoted Android/web-to-app conversion behavior and leave only the real external runtime/store/deferred-install validation in the new split follow-up TODO.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Approval

- **Approved by:** explicit user request on `2026-06-08` to split this overloaded promotion-lane TODO, move the delivered slice to `completed`, and reopen only the residual runtime debt in `v0.2.1+9`.
- **Approval scope:** documentation-only archival closeout for the already-promoted web-to-app conversion slice after confirming the remaining Android runtime/store/deferred work now has a narrower active owner.

## Context

This TODO stopped being a truthful single owner. The repository now shows that the main product behavior is already absorbed in the promoted code and canonical docs:

- `/baixe-o-app` resolves to the real app-promotion/store-handoff experience.
- Promotion/open-app surfaces only offer active publication targets with configured URLs.
- `/open-app` preserves safe invite/detail/guarded-route continuation through `target_path` and `store_channel`.
- Installed Android apps receive the preserved target directly before browser Guard fallback.
- Anonymous web hard/auth gates promote the app instead of continuing to web login.

What remained open was narrower and external:

- real Play Store destination proof,
- real install -> first-open deferred continuation proof,
- final browser/device validation of representative hard-gate entrypoints.

That residual work is not archived here. It moved into a new active TODO so this file can close truthfully.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `T1-W2A-archival-split-2026-06-08`
- **Why this is the right current slice:** the old promotion-lane file mixed delivered policy/implementation with still-open external runtime proof. This archival slice is now limited to the promoted behavior already present in `origin/main`.
- **Direct-to-TODO rationale:** safe. The user requested a documentation split based on current repository truth, not new product discovery.

## Contract Boundary

- This completed TODO owns only the delivered Android-first web-to-app conversion slice that is already absorbed in `origin/main`.
- It includes the canonical promotion boundary, dynamic publication-target filtering, redirect-intent preservation, and installed-app handoff behavior.
- It does **not** own the remaining current-package runtime/startup validation. That work now lives in `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`.
- It does **not** own iOS Universal Links / deferred capture, QR-authenticated web, or telemetry sink/readback hardening.

## Delivery Status Canon

- **Current delivery stage:** `Completed`
- **Qualifiers:** `Cross-Stack`, `Promotion-Boundary`, `Intent-Handoff`, `Split-Follow-Up-2026-06-08`, `origin-main-reviewed`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md`; continue only the residual Android runtime/store/deferred proof in the split `v0.2.1+9` TODO.
- **Post-commit/push status:** `completed`

## References

- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`
- `foundation_documentation/todos/completed/TODO-store-release-funnel-metrics-validation.md`
- `foundation_documentation/todos/completed/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Decision promotion targets:**
  - `flutter_client_experience_module.md` promotion/open-app route ownership and installed-app handoff notes
  - `invite_and_social_loop_module.md` invite-preview / promotion-boundary notes
  - `onboarding_flow_module.md` deferred continuation / auth-wall continuity notes

## Ecosystem Impact Analysis

- **Current classification:** `Project-Local`
- **Why:** this is Android-release web-to-app closure for this project's concrete tenant-public promotion and deferred continuation behavior.
- **Reuse doctrine note:** the split pattern may help future archival cleanup, but this TODO is not a reusable package/program by itself.

## Scope

- [x] `SCOPE-01` Archive the delivered `/baixe-o-app` -> real `appDownload` promotion/store-handoff boundary already absorbed in `origin/main`.
- [x] `SCOPE-02` Archive the delivered dynamic publication-target filtering contract for Android/iOS store URLs and active flags.
- [x] `SCOPE-03` Archive the delivered `/open-app` redirect-intent preservation contract (`target_path`, `store_channel`, safe-path filtering).
- [x] `SCOPE-04` Archive the delivered installed-app Android handoff that opens the preserved target directly and leaves Guard only as the absent-app fallback.
- [x] `SCOPE-05` Archive the delivered anonymous-web promotion boundary where tenant-public hard/auth gates do not continue to web login.
- [x] `SCOPE-06` Split the remaining external Android runtime/store/deferred validation into a new narrower active owner.

## Out of Scope

- [ ] Real Play Store destination verification on external browser/device lanes.
- [ ] Real install -> first-open deferred deep-link capture proof on Android.
- [ ] iOS Universal Links or iOS deferred deep-link capture.
- [ ] QR-authenticated web bootstrap/session validation.
- [ ] Mixpanel sink/readback hardening or published-build funnel replay.
- [ ] Any new redesign of the promotion UX beyond the already-promoted boundary.

## Dependencies & Sequencing

- [x] `DEP-01` The residual/current-package Android startup/bootstrap validation now lives in `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`.
- [x] `DEP-02` iOS specialization remains a sibling fast-follow lane in `TODO-ios-universal-links-production-validation.md`.
- [x] `DEP-03` QR-authenticated web remains a sibling fast-follow lane in `TODO-qr-login-web-auth.md`.
- [x] `DEP-04` Published funnel sink/readback hardening remains a sibling post-release lane in `TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md`.

## Definition of Done

- [x] `DOD-01` `origin/main` uses the real app-promotion/store-handoff experience for the canonical `/baixe-o-app` boundary instead of the legacy tester-waitlist default.
- [x] `DOD-02` Promotion/open-app surfaces only render active publication store targets with configured URLs.
- [x] `DOD-03` `/open-app` and deferred resolver contracts preserve safe invite/detail/guarded-route continuation through `target_path` and `store_channel`.
- [x] `DOD-04` Installed Android apps receive the preserved target directly while Guard remains only the absent-app fallback for the implemented hard-gate flows.
- [x] `DOD-05` Anonymous web hard/auth gates promote the app instead of continuing to web auth.
- [x] `DOD-06` The unfinished Android runtime/store/deferred proof no longer pollutes this archival slice and now has a narrower active owner.

## Validation Steps

- [x] `VAL-01` Review `origin/main` Flutter promotion controller and promotion UI tests for the delivered `/baixe-o-app` and publication-target behavior.
- [x] `VAL-02` Review `origin/main` Laravel `/open-app` and deferred resolver tests for redirect preservation and store-channel continuity.
- [x] `VAL-03` Review canonical module/policy docs to confirm the promoted web-to-app boundary remains encoded outside this TODO.
- [x] `VAL-04` Confirm the remaining external runtime/store/deferred work is explicitly split into a new active TODO instead of being silently dropped.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:origin/main reviewed`, `laravel-app:origin/main reviewed`, `foundation_documentation:<current lane>`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `already exceeded; archival catch-up only`
- **Production-ready threshold for this TODO:** `n/a after split; runtime/store/deferred proof moved to the new active TODO`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `flutter promotion boundary + publication targets` | `historical; origin/main reviewed 2026-06-08` | `historical` | `historical` | `absorbed in origin/main` | `completed` |
| `laravel /open-app + deferred resolver contract` | `historical; origin/main reviewed 2026-06-08` | `historical` | `historical` | `absorbed in origin/main` | `completed` |
| `foundation docs archival split` | `<current>` | `n/a` | `n/a` | `<pending>` | `documentation-only closeout` |

## Completion Evidence Matrix (Local Gate)

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | `SCOPE-01` Archive the delivered `/baixe-o-app` -> real `appDownload` promotion/store-handoff boundary already absorbed in `origin/main`. | `code+test review` | `git -C flutter-app show origin/main:lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart`; `git -C flutter-app show origin/main:test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` | `origin/main Flutter` | `passed` | `origin/main` still returns `AppPromotionExperience.appDownload` and keeps promotion-screen test coverage on the current route surface. |
| `SCOPE-02` | Scope | `SCOPE-02` Archive the delivered dynamic publication-target filtering contract for Android/iOS store URLs and active flags. | `test review` | `git -C flutter-app show origin/main:test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`; historical evidence row `T1-PUB-01` from the former promotion-lane packet | `origin/main Flutter + historical local validation` | `passed` | `origin/main` still carries the active-target filtering test, and the archival packet already recorded the admin/settings validation that made this behavior release truth. |
| `SCOPE-03` | Scope | `SCOPE-03` Archive the delivered `/open-app` redirect-intent preservation contract (`target_path`, `store_channel`, safe-path filtering). | `test review` | `git -C laravel-app show origin/main:tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`; `git -C laravel-app show origin/main:tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php` | `origin/main Laravel` | `passed` | `origin/main` still asserts `target_path` and `store_channel` for invite, detail-route, and fallback cases. |
| `SCOPE-04` | Scope | `SCOPE-04` Archive the delivered installed-app Android handoff that opens the preserved target directly and leaves Guard only as the absent-app fallback. | `test+historical runtime review` | `origin/main tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`; historical T1 installed-app evidence: ADB/device validation plus Playwright `deeplink_contract.spec.js` open-app checks recorded in the former promotion-lane packet | `origin/main Laravel + historical browser/device` | `passed` | The routing contract remains on `origin/main`, and the archival packet already carried browser/device evidence for installed-app handoff while keeping the Guard as fallback when the app is absent. |
| `SCOPE-05` | Scope | `SCOPE-05` Archive the delivered anonymous-web promotion boundary where tenant-public hard/auth gates do not continue to web login. | `historical browser review` | `foundation_documentation/policies/web_to_app_promotion_policy.md`; `foundation_documentation/modules/flutter_client_experience_module.md`; `foundation_documentation/modules/invite_and_social_loop_module.md`; former T1 packet web/browser evidence for read-only/public-link and no-web-login behavior | `web browser + canonical docs` | `passed` | The policy/modules still freeze the anonymous-web promotion boundary, and the former packet already recorded web/browser evidence for no `/auth/login` continuation on the covered flows. |
| `SCOPE-06` | Scope | `SCOPE-06` Split the remaining external Android runtime/store/deferred validation into a new narrower active owner. | `documentation` | `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md` | `foundation docs` | `passed` | Residual/current-package startup-boundary proof remains explicit and active after absorption. |
| `DOD-01` | Definition of Done | `DOD-01` `origin/main` uses the real app-promotion/store-handoff experience for the canonical `/baixe-o-app` boundary instead of the legacy tester-waitlist default. | `code+test review` | Same evidence as `SCOPE-01` | `origin/main Flutter` | `passed` | The default experience is `appDownload` on `origin/main`. |
| `DOD-02` | Definition of Done | `DOD-02` Promotion/open-app surfaces only render active publication store targets with configured URLs. | `test review` | Same evidence as `SCOPE-02` | `origin/main Flutter + historical local validation` | `passed` | Active-target filtering remains covered and no longer belongs to an open release packet. |
| `DOD-03` | Definition of Done | `DOD-03` `/open-app` and deferred resolver contracts preserve safe invite/detail/guarded-route continuation through `target_path` and `store_channel`. | `test+historical navigation review` | `origin/main tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`; `origin/main tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php`; historical Playwright `deeplink_contract.spec.js` open-app and direct-public-links evidence from the former T1 packet | `origin/main Laravel + historical browser` | `passed` | The backend contract remains on `origin/main`, and the former packet already tied it to browser-facing open-app/public-link navigation evidence. |
| `DOD-04` | Definition of Done | `DOD-04` Installed Android apps receive the preserved target directly while Guard remains only the absent-app fallback for the implemented hard-gate flows. | `test+historical runtime review` | Same evidence as `SCOPE-04` | `origin/main Laravel + historical browser/device` | `passed` | This archival slice depends on already-recorded browser/device proof for installed-app handoff; the still-open absent-app/install/store proof moved out. |
| `DOD-05` | Definition of Done | `DOD-05` Anonymous web hard/auth gates promote the app instead of continuing to web auth. | `historical browser review` | Same evidence as `SCOPE-05` | `web browser + canonical docs` | `passed` | The web/browser-facing no-web-login rule is promoted and no longer owned only by this TODO. |
| `DOD-06` | Definition of Done | `DOD-06` The unfinished Android runtime/store/deferred proof no longer pollutes this archival slice and now has a narrower active owner. | `documentation` | Same evidence as `SCOPE-06` | `foundation docs` | `passed` | The split makes the remaining debt explicit instead of hidden in a stale super-packet. |
| `VAL-01` | Validation Steps | `VAL-01` Review `origin/main` Flutter promotion controller and promotion UI tests for the delivered `/baixe-o-app` and publication-target behavior. | `review` | `origin/main lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart`; `origin/main test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` | `origin/main Flutter` | `waived` | Waiver approval: explicit user request on `2026-06-08` for documentation-only archival catch-up. This is a structure-only review step; exact browser/device flow acceptance is carried by `DOD-01`, `DOD-04`, and `DOD-05`. |
| `VAL-02` | Validation Steps | `VAL-02` Review `origin/main` Laravel `/open-app` and deferred resolver tests for redirect preservation and store-channel continuity. | `review` | `origin/main tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`; `origin/main tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php` | `origin/main Laravel` | `waived` | Waiver approval: explicit user request on `2026-06-08` for documentation-only archival catch-up. This is a structure-only review step; exact browser/device flow acceptance for continuation restoration is carried by `DOD-03` and `DOD-04`. |
| `VAL-03` | Validation Steps | `VAL-03` Review canonical module/policy docs to confirm the promoted web-to-app boundary remains encoded outside this TODO. | `review` | `foundation_documentation/policies/web_to_app_promotion_policy.md`; `foundation_documentation/modules/flutter_client_experience_module.md`; `foundation_documentation/modules/invite_and_social_loop_module.md` | `foundation docs` | `passed` | The contract is not stranded in this archival file. |
| `VAL-04` | Validation Steps | `VAL-04` Confirm the remaining external runtime/store/deferred work is explicitly split into a new active TODO instead of being silently dropped. | `review` | `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md` | `foundation docs` | `passed` | The residual/current-package startup work now has a truthful active owner. |

## Local CI-Equivalent Suite Matrix

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new Flutter/Laravel code was executed for this move; the closeout only reconciles current `origin/main` truth with a split follow-up owner. | `n/a` | `historical archival closeout` | `n/a` | Existing `Completion Evidence Matrix (Local Gate)` plus the new split TODO. | Documentation-only move; no fresh CI-equivalent rerun was required. |

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)

| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app origin/main` | `AppPromotionExperience.appDownload` remains the default promotion experience and promotion UI tests still cover active publication-target filtering. | `git -C flutter-app rev-parse --verify origin/main` -> `792e68ec8bd486e459068cf4ce190c230f6db812` |
| `laravel-app origin/main` | `/open-app` and deferred resolver tests still assert `target_path`, `store_channel`, and explicit platform-target behavior. | `git -C laravel-app rev-parse --verify origin/main` -> `1d97cad761ed59a8497ce39cd3890213f82ff8fe` |
| `canonical docs` | Policy/modules still carry the promoted anonymous-web promotion boundary and continuation rules outside this TODO. | Repository review on `2026-06-08` |
| `split follow-up` | Real Play Store/install/deferred/browser-device proof moved to the new `v0.2.1+9` TODO. | Documentation split executed on `2026-06-08` |

## Pipeline/Copilot P1/P2 Preflight

| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | Confirm the move archives only the already-promoted slice and keeps the remaining runtime/store/deferred debt explicit. | `n/a` | Current completed TODO plus the split active TODO. | `none` | No fresh PR/Copilot surface exists for this documentation-only archival move. |

## Rule-Spirit Anti-Pattern Hunt

| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `TODO lane hygiene` | Prevent a main-absorbed slice from lingering in `promotion_lane` just because a different external runtime debt was still open. | `passed` | Current split between this completed file and the new active `v0.2.1+9` runtime TODO. | `no findings` | The archival closeout does not hide the remaining debt; it narrows it. |
| `Residual debt traceability` | Prevent moving the whole super-packet to `completed` while silently dropping Play Store/install/deferred validation. | `passed` | `SCOPE-06`, `DOD-06`, `VAL-04`, and the split follow-up TODO. | `no findings` | The remaining runtime/store/deferred work remains explicit and actionable. |

## Rules Acknowledgement / Ingestion

| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | A TODO moved out of `promotion_lane/` must not leave dead ownership or hidden follow-up behind. | Truthful lane semantics and updated live ownership. | Pretending a stale super-packet is still the right active owner. | Archive only the delivered slice and create a new active owner for the remainder. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The task is to separate already-promoted behavior from residual runtime evidence debt. | Keep the residual debt explicit. | Closing unresolved external runtime proof implicitly. | Move only the main-absorbed slice to `completed`. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | This is a closeout/promotion normalization task. | Preserve truthful completion evidence and split-follow-up traceability. | Leaving a completed slice stranded in `promotion_lane`. | Archive the delivered slice and route remaining work into a narrower active TODO. |

## TODO Closeout Disposition

- **Completed path:** `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md`
- **Split follow-up path:** `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming that the promoted web-to-app boundary is already on `origin/main` and the remaining work is only real external runtime/store/deferred proof.
- **Historical note:** this file no longer owns Play Store/install/deferred first-open validation.
- **Reopen rule:** any new product behavior change to the promotion boundary should open a new TODO or update the active split follow-up rather than reopen this archival slice.
