# Promotion Lane Code/Main Audit

**Audit date:** `2026-06-08`  
**Profile:** `Assurance / Tester-Quality`  
**Technical scope:** `docker`

## Requested Scope
- `foundation_documentation/todos/promotion_lane/fast_follow_required/**`
- `foundation_documentation/todos/promotion_lane/store_release_android/**`

## Audit Method
- Verified source-lane ancestry against `origin/main` in `flutter-app`, `laravel-app`, and the Docker root repository.
- Cross-checked current `origin/main` for the regression tests, browser specs, and module-doc anchors named by each TODO.
- Reviewed current local file state in `foundation_documentation` to avoid moving files with pre-existing local edits unless the closeout was unambiguous and low-risk.

## Fast Follow Result
The remaining three `fast_follow_required` TODOs were no longer merely `stage`-promoted. Their recorded promoted source SHAs and Docker gitlink sync commit are all ancestors of `origin/main`, so they were normalized and moved to `completed/`.

| TODO | Main-absorption evidence | Action |
| --- | --- | --- |
| `TODO-bugfix-direct-invite-push-scheduled-without-delivery.md` | `git -C laravel-app merge-base --is-ancestor 805229da74a4a5aa201a5093b8761b49c1851ae1 origin/main` = `0`; `git merge-base --is-ancestor 03366502be5ec2f4efd37495953dd02b1fc843e6 origin/main` = `0` | moved to `todos/completed/` |
| `TODO-bugfix-invite-screen-app-pane-loading.md` | `git -C flutter-app merge-base --is-ancestor 90040672081b5f2dc573ab5066231aeace0a8e33 origin/main` = `0`; `git merge-base --is-ancestor 03366502be5ec2f4efd37495953dd02b1fc843e6 origin/main` = `0` | moved to `todos/completed/` |
| `TODO-bugfix-landlord-admin-password-ops-reset-path.md` | `git -C laravel-app merge-base --is-ancestor 805229da74a4a5aa201a5093b8761b49c1851ae1 origin/main` = `0`; `git merge-base --is-ancestor 03366502be5ec2f4efd37495953dd02b1fc843e6 origin/main` = `0` | moved to `todos/completed/` |

## Store Release Android Classification
This set was materially reduced during the audit. Eleven store-release TODOs were normalized, revalidated against current `origin/main`, and moved to `completed/`. The remaining one file is still kept in `promotion_lane/` because it is an overloaded historical super-packet that still needs its own normalization pass.

| TODO | Code/Main status | Concrete `origin/main` evidence | Local file state | Recommended disposition |
| --- | --- | --- | --- | --- |
| `TODO-store-release-account-profile-type-plural-settings-display.md` | `main-absorbed (strong)` | Flutter plural-label form test, Laravel plural persistence/readback test, and tenant-admin module contract all still present on `origin/main`. | clean | moved to `todos/completed/` during this audit |
| `TODO-store-release-agenda-card-polish-and-occurrence-taxonomy-overrides.md` | `main-absorbed (strong)` | Flutter occurrence/taxonomy UI tests, Laravel agenda CRUD tests, and events module anchors all still present on `origin/main`. | clean | moved to `todos/completed/` during this audit |
| `TODO-store-release-event-share-invite-entrypoint.md` | `main-absorbed (strong enough for archival)` | Flutter share-code/auth-boundary tests, Laravel authenticated share acceptance and occurrence identity tests, and invite/share module docs remain on `origin/main`. | clean | moved to `todos/completed/` during this audit |
| `TODO-store-release-funnel-metrics-validation.md` | `main-absorbed (strong enough for archival)` | The TODO explicitly split post-publication sink/runtime hardening into the dedicated post-release TODO, and its pre-publication matrix/property proof remains encoded in the TODO plus promoted module docs on `origin/main`. | dirty | moved to `todos/completed/` during this audit |
| `TODO-store-release-home-distance-origin-refresh-regression.md` | `main-absorbed (strong)` | `origin/main` still carries focused Home agenda tests for canonical-origin refresh, stale-radius clearing, and `10 km` seed-floor behavior, plus DAL coverage for `origin_lat` / `origin_lng` / `max_distance_meters` and the promoted Home/Agenda module rules. | dirty | moved to `todos/completed/` during this audit |
| `TODO-store-release-home-favorites-refresh-regression.md` | `main-absorbed (strong)` | Flutter `origin/main` still contains repository refresh and favorites-section origin-flow tests; Laravel `FavoritesControllerTest.php`; module docs still carry post-auth hydration and favorites preview contracts. | clean | moved to `todos/completed/` during this audit |
| `TODO-store-release-invites-occurrence-target-migration.md` | `main-absorbed (strong)` | Laravel occurrence-identity tests, Flutter occurrence-scoped share-code tests, and invite module `INV-35` contract all remain on `origin/main`. | clean | moved to `todos/completed/` during this audit |
| `TODO-store-release-minimal-friends-and-favorites-mvp.md` | `main-absorbed (strong)` | `origin/main` still carries the StoreRelease social graph tests, invite-share cache/refresh/share-state Flutter regressions, anonymous app favorites coverage, and the promoted composer/social-loop module contracts. | dirty | moved to `todos/completed/` during this audit |
| `TODO-store-release-phone-otp-auth-and-contact-match.md` | `main-absorbed (strong)` | Flutter OTP UI/controller tests, Laravel challenge/verify/hash/environment tests, and auth/onboarding/invite module anchors remain on `origin/main`; foundation docs history still carries OTP closure commits. | clean | moved to `todos/completed/` during this audit |
| `TODO-store-release-web-to-app-conversion-gate.md` | `main-absorbed (strong); split archival catch-up executed` | `origin/main` now hardcodes `AppPromotionExperience.appDownload`, renders only active publication store targets, keeps `/open-app` redirect-path preservation, and carries the promoted web-to-app policy/module rules. On `2026-06-08`, the delivered slice was moved to `todos/completed/` and the residual Android runtime/store/deferred proof was split into `TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md`. | dirty | moved delivered slice to `todos/completed/`; residual proof reopened in `active/v0.2.1+9/` |
| `TODO-v1-screen-invite-polish.md` | `main-absorbed (strong)` | Flutter `origin/main` still contains invite-flow surface assertions for the delivered footer/detail split and controller tests preserving canonical decision behavior; foundation docs history contains `3878ebb docs: promote invite polish todo`. | dirty | moved to `todos/completed/` during this audit |
| `TODO-v1-screen-user-profile-polish.md` | `main-absorbed (strong)` | Flutter `origin/main` still contains `/profile` field-matrix/radius assertions; Laravel `origin/main` still contains `ApiV1TenantMeTest.php` persistence and phone-immutability coverage. | dirty (trivial local status edit only) | moved to `todos/completed/` during this audit |

## Main Conclusion
- `fast_follow_required/` is now materially cleaner: every already-main-promoted slice from that folder is archived in `completed/`.
- `store_release_android/` was reduced from twelve audited candidates to one remaining promotion-lane file.
- The TODOs archived from `store_release_android/` in this audit were:
  - `TODO-store-release-account-profile-type-plural-settings-display.md`
  - `TODO-store-release-agenda-card-polish-and-occurrence-taxonomy-overrides.md`
  - `TODO-store-release-event-share-invite-entrypoint.md`
  - `TODO-store-release-funnel-metrics-validation.md`
  - `TODO-store-release-home-distance-origin-refresh-regression.md`
  - `TODO-store-release-home-favorites-refresh-regression.md`
  - `TODO-store-release-invites-occurrence-target-migration.md`
  - `TODO-store-release-minimal-friends-and-favorites-mvp.md`
  - `TODO-store-release-phone-otp-auth-and-contact-match.md`
  - `TODO-v1-screen-invite-polish.md`
  - `TODO-v1-screen-user-profile-polish.md`
- The remaining promotion-lane work is now concentrated in one overloaded historical super-packet (`web-to-app conversion`) that appears main-absorbed but still needs its own normalization pass before archival.
