# Feature Brief: MVP With APP Backlog Realignment

## Artifact Role
- **Why this brief exists now:** the active backlog still mixes `pre_mvp`, `mvp`, `mvp_closure`, `cross-stack`, and `vnext` lanes from the previous release frame, but the product goal has shifted to a new post-Pre-MVP cut: `MVP With APP`.
- **What this brief is not:** canonical module doc, project constitution, roadmap replacement, tactical execution TODO, or implementation authority.

## Source Idea / Request
- Reorganize the current TODO inventory so the team can separate:
  - what is effectively concluded but still active,
  - what must define the next product cut (`MVP With APP`),
  - what remains explicitly `VNext`.
- Product direction from the user:
  - Pre-MVP is considered finished and already running.
  - The next MVP must make invites fully functional.
  - The next MVP must include friends/favorites management with real user value.
  - Promotion/web-to-app conversion must work 100%.
  - QR-code web login to unlock web functionality is business-defined, but sequenced as mandatory fast-follow after the Android gate rather than as speculative VNext.
  - Small cosmetic improvements with real payoff may be included as bonus MVP work.

## Problem / Desired Outcome
- **Problem:** the current active TODO lanes still reflect the previous milestone structure. They mix closure-only residuals, outdated orchestrators, low-priority visual polish, technical hardening, and true product-critical work.
- **Desired outcome:** one clear backlog split:
  - `Store Release Android`
  - `Fast Follow Required`
  - `VNext`
- **Why now:** without this re-cut, delivery energy will keep dispersing across map/admin/tech-debt/polish items that are not part of the next value milestone, and business-defined fast-follow work will keep being mistaken for optional backlog.

## Constraints / Non-Goals
- **Constraints:**
  - Pre-MVP web posture remains authoritative for V1/V1.5: tenant-public web is promotion/read-only and must not silently become authenticated web.
  - `project_constitution.md` is currently absent, so module docs + roadmap + scope policy are the current authority for this reorganization.
  - This brief was accepted before backlog movement; the approved execution cut is now `store_release_android` + `fast_follow_required`.
  - `Operational / Coder` must not silently redefine project-level invariants; any roadmap/constitution-level renaming needs explicit follow-up.
- **Non-goals:**
  - do not reopen completed architecture work just because files are still active;
  - do not smuggle account workspace/web-auth scope into the next MVP by calling it “promotion”;
  - do not treat all visual polish TODOs as critical-path product work.

## Canonical Touchpoints
- **Constitution impact:** `possible` — a future formal rename from the old `first release` framing to `MVP With APP` may need a strategic handoff because `project_constitution.md` is missing.
- **Roadmap impact:** `yes` — the current release/orchestration framing is stale relative to the new product cut.
- **Primary module candidates:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/flutter_client_experience_module.md`

## Evidence / References
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-favorites-account-profile-visual-enrichment.md`
- `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-invite-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-invite-friends-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-web-bootstrap-branding-continuity.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-connections-package.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-promotion-experience-runtime-selection.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-parking-lot.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Does “friends/favorites management” mean only current account-profile favorites, or does it require user-to-user connections/friend semantics? | This changes whether MVP can reuse existing favorites lanes or must extract scope from `belluga_connections`. | Historical evidence at brief time: current favorites TODOs covered account-profile favorites and the broader package was still VNext-scoped. Resolved later by the dedicated store-release lane `TODO-store-release-minimal-friends-and-favorites-mvp.md`, which now freezes the release subset as `contact_match -> favorite -> friend` plus viewer-scoped exposure. | `resolve now` |
| `AMB-02` | Does “promotion funcionando 100%” mean only closing the manual/device/data lanes in the existing web-to-app conversion gate TODO, or also delivering the remaining bootstrap/web-branding polish? | This changes whether promotion scope is closure-only or still includes visible UX improvements. | `TODO-store-release-web-to-app-conversion-gate.md` is mostly closure-only, but still carries the active `/baixe-o-app` release-readiness checkpoint; `TODO-v1-web-bootstrap-branding-continuity.md` and web branding TODOs still affect perceived promotion quality. | `resolve now` |
| `AMB-03` | Which active visual-polish TODOs belong in MVP bonus scope versus VNext backlog? | Without a cut, broad UI polish keeps diluting focus. | Several active polish TODOs are purely visual and can be treated as bonus, not core. | `resolve now` |

## Working Classification

### 1) Concluded But Still Active
These TODOs are not defining the next product milestone. They are mostly `Local-Implemented`, `Local-Validated`, or manual-smoke/promotion-evidence leftovers:

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
  - Core engineering is done; remaining work is manual device/store validation and KPI sink validation.
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-tenant-public-web-desktop-mobile-frame.md`
  - Layout slice is locally implemented; remaining work is manual browser smoke.
- `foundation_documentation/todos/active/concluded_but_active/tenant-public-branding-metadata-fallback.md`
  - Local implementation and targeted evidence already exist; this is packaging/promotion work.
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-tenant-branding-name-and-web-icon-parity.md`
  - Contract work is done locally; needs lane decision, not more product discovery.
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-favorites-account-profile-visual-enrichment.md`
  - Useful product behavior already exists locally; remaining step is real-runtime manual validation.
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-home-agenda-controller-boundary-plugin-rules.md`
  - Architecture/rule lane is locally validated and should not stay mixed with current product prioritization.
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-tenant-admin-domain-management-and-events-ops.md`
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-tenant-admin-events-temporal-filter.md`
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-tenant-admin-telemetry-integration-persistence.md`
  - Important lanes, but they are tenant-admin closure/hardening, not part of the next consumer-facing MVP cut.

### 2) Store Release Android
This is the recommended next product cut after Pre-MVP:

- **Promotion 100% closed**
  - Close the remaining manual/device/data lanes in `TODO-store-release-web-to-app-conversion-gate.md`.
  - Decide whether `TODO-v1-web-bootstrap-branding-continuity.md` is required for MVP polish or can be treated as bonus.
  - Keep `TODO-v1-tenant-branding-name-and-web-icon-parity.md` in scope only insofar as it affects real promotion/open-app credibility.
- **Invite loop 100% usable in app**
  - `TODO-v1-screen-invite-polish.md`
  - `TODO-v1-screen-invite-friends-polish.md`
  - invite-related acceptance/manual validation already tracked in the policy and favorites lanes
- **Favorites/friends with real product value**
  - Keep `TODO-v1-favorites-account-profile-visual-enrichment.md` as part of MVP closure.
  - Pull a **minimal** connections/friends slice out of `TODO-vnext-connections-package.md` instead of trying to deliver the whole package.
  - The MVP cut should be: unilateral user favorite, reciprocal friend derivation, and minimal viewer exposure rules needed for invite/social proof; everything else stays VNext.
- **Profile/account-profile surfaces that support invites/favorites**
  - `TODO-v1-public-account-profile-discovery-ui.md`
  - `TODO-v1-screen-user-profile-polish.md`
  - These should stay bounded to invite/favorites usability, not become a generic profile redesign.
- **Bonus only if cheap**
  - `TODO-v1-screen-signin-signup-polish.md`
  - `TODO-v1-screen-events-polish.md`
  - `TODO-v1-tenant-public-ui-polish-batch-auth-profile-events-invite.md` should be treated as a container/bonus organizer, not as the primary milestone driver.

### 3) Fast Follow Required
These items are business-defined and intentionally sequenced immediately after Android release:

- iOS deep-link/runtime validation and store follow-up
  - tracked by `foundation_documentation/todos/active/fast_follow_required/TODO-ios-store-fast-follow.md`
  - detailed technical execution stays in `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md`
- QR-code web login / authenticated web unlock
  - tracked by `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`
  - must not silently widen the Android gate or the current read-only web posture

### 4) VNext
These should remain explicitly outside the next Android + fast-follow cut:

- Full `foundation_documentation/todos/active/vnext/TODO-vnext-connections-package.md`
  - only a minimal subset should be promoted into MVP; the full package remains too broad.
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-promotion-experience-runtime-selection.md`
  - hardcoded promotion variant runtime-selection is useful, but not critical for “promotion 100%” if the current route already works.
- `foundation_documentation/todos/active/vnext/TODO-vnext-event-checkin.md`
- `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
  - no longer aligned with the next MVP cut; should be re-labeled/deferred when the backlog is physically reorganized.
- broad map/admin/engineering hardening items that do not directly change invite/favorites/promotion value in the next release.

## Story Decomposition
Treat each row as a candidate delivery slice. A tactical TODO should normally map to one primary story slice, not to the entire table.

| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Clean the active backlog so only the next milestone remains on the critical path. | `flutter_client_experience_module.md` | `invite_and_social_loop_module.md` | old `pre_mvp`/closure/admin residues are classified and removed from the main milestone conversation | updated TODO lane map + user-approved reclassification | `create-now` | user approval on the proposed cut | This is the backlog hygiene slice itself. |
| `ST-02` | Close promotion/web-to-app so the funnel is actually shippable end to end. | `invite_and_social_loop_module.md` | `flutter_client_experience_module.md` | real device/browser/store validation and KPI sink checks are closed; optional bootstrap continuity is explicitly decided | manual Android install/open flow + telemetry verification | `create-now` | real-device lane + telemetry sink access | This is the highest-value non-negotiable MVP slice. |
| `ST-03` | Make invites fully usable and understandable in the app. | `invite_and_social_loop_module.md` | `flutter_client_experience_module.md` | invite decision and invite-friends/share surfaces feel complete and stable for real users | app manual smoke + targeted Flutter regression tests | `create-now` | depends on ST-02 only for full acquisition funnel proof, not for core in-app UX | Should stay narrow; avoid mixing with unrelated events/map polish. |
| `ST-04` | Extract a minimal friends/favorites MVP from the current VNext connections vision. | `invite_and_social_loop_module.md` | `flutter_client_experience_module.md` | MVP defines what “friends/favorites” means now: user favorite edge, reciprocal friend, and minimum exposure/social-proof rules | new bounded TODO + explicit acceptance matrix | `split-further` | current `belluga_connections` TODO is too broad | This is the key product gap between current V1 docs and the requested next MVP. |
| `ST-05` | Improve profile/account-profile surfaces only where they directly support favorites, social proof, and invite conversion. | `flutter_client_experience_module.md` | `invite_and_social_loop_module.md` | profile/account-profile UI changes materially improve the invite/favorites loop without becoming general redesign | focused mobile smoke on `/profile`, favorites strip, and account-profile detail | `merge-with-other` | depends on ST-03 and ST-04 scope choices | Good bonus/core-support slice, but not the main milestone driver. |
| `ST-06` | Keep low-risk cosmetic wins available as bonus MVP work. | `flutter_client_experience_module.md` | `none` | only small visual fixes with obvious UX gain survive; the rest stay out | manual smoke only | `defer` | should not compete with ST-02..ST-04 | Candidate TODOs already exist; do not start here. |
| `ST-07` | Authenticated web through QR or similar app-approved login handoff. | `flutter_client_experience_module.md` | `web_to_app_promotion_policy.md` | authenticated web is formally bounded as fast-follow and no longer conflicts with the read-only web posture or Android gate | active fast-follow TODO exists with auth/security constraints | `create-now` | must stay sequenced after Android release and before broader workspace expansion | Business-defined fast-follow, not speculative backlog. |

## Retire This Brief When
- a backlog-realignment TODO exists and the user accepts the new three-bucket cut;
- the next tactical TODOs are explicitly opened for:
  - `ST-02 Promotion 100%`
  - `ST-03 Invite loop 100%`
  - `ST-04 Minimal friends/favorites MVP extraction`
