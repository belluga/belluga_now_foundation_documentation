# Title
Store Release Android

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Pre-MVP is closed as a delivery milestone. The current business target is Android-first app-store publication, with the release judged by real acquisition/conversion behavior in production-like conditions rather than by the previous Pre-MVP framing. The current active backlog still mixes closure-only residuals, old MVP orchestration, and future-scope work, so this TODO becomes the publication authority for the next release cut.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/mvp-with-app-backlog-realignment.md`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** this TODO creates one approval and execution authority for the Android-first store-release gate instead of scattering the milestone across legacy `pre_mvp`, `mvp`, and `vnext` lanes.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be true for the Android-first store-release milestone.
- Child TODOs define the concrete implementation slices inside that boundary.
- If a candidate item is business-defined but intentionally sequenced after Android release, it belongs in `active/fast_follow_required/`, not here.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Orchestrator`, `Cross-Stack`, `Release-Critical`
- **Next exact step:** drive execution through the authoritative child TODOs listed below without reopening the already frozen Android-gate scope split.

## References
- `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-funnel-metrics-validation.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-home-favorites-refresh-regression.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-invites-occurrence-target-migration.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-proximity-preferences-and-location-origin.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-belluga-media-canonical-image-flow-hardening.md`
- `foundation_documentation/todos/completed/TODO-store-release-cors-ownership-unification.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-tenant-settings-optimization.md`
- `foundation_documentation/todos/completed/TODO-store-release-critical-journey-regression-gates.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-event-artists-eradication.md`
- `foundation_documentation/todos/completed/TODO-store-release-event-content-save-sanitization.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android-publication-readiness.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md`

## Scope
- [ ] Treat web-to-app conversion closure as a publication blocker, not as leftover Pre-MVP work.
- [ ] Treat release funnel-metrics validation as a publication blocker so acquisition, deferred continuation, identity progression, and first social-loop actions are observable at release confidence.
- [ ] Treat in-app invite usability as publication-critical.
- [ ] Treat `/convites/compartilhar` stuck share-generation state and missing friends-list refresh action as publication blockers under in-app invite usability.
- [x] Treat landlord/tenant auth-method governance as the delivered upstream baseline for the Belluga phone OTP + contact-match lane.
- [ ] Treat tenant-public phone OTP identity + contact-match baseline as publication-critical because the minimal friends/favorites loop depends on verified phone identity.
- [ ] Deliver a minimal friends/favorites MVP slice that matches current business scope without pulling the full `belluga_connections` package into the Android gate.
- [ ] Treat Home Favorites refresh after app-side favorite mutations as a publication blocker because Home must consume the release social/favorites state correctly.
- [ ] Treat invite occurrence target migration as a publication blocker because invites must be linked to the selected Event Occurrence after occurrence implementation.
- [ ] Treat canonical user-owned proximity/origin preferences as release-relevant so Home uses a stable, user-controlled location-origin contract rather than remaining device-local only.
- [ ] Treat canonical Laravel image-flow hardening as publication-critical so release surfaces do not ship with non-canonical public media URLs or host-bound image drift.
- [x] Treat definitive CORS ownership convergence as publication-critical so browser/admin/runtime API access no longer depends on a temporary split owner model.
- [ ] Treat tenant settings read-path optimization as publication-critical where environment/settings bootstrap cost or drift can degrade release confidence.
- [x] Treat critical-journey regression gates as publication-critical so Android publication confidence is backed by deterministic cross-stack evidence rather than partial/manual assumptions.
- [ ] Treat legacy `artists` eradication from public event/runtime contracts as publication-critical so the release app ships on the canonical linked-profile event model rather than on a deprecated read projection.
- [x] Treat event `content` save-time sanitization as publication-critical so unsupported rich-text markup is stripped canonically on backend save and never implied as accepted by the frontend editing UX.
- [ ] Keep profile/account-profile support work only when it directly strengthens invites/favorites/publication confidence.
- [ ] Keep an explicit Android publication-readiness lane and refine its repo-owned versus external-console ownership boundary.
- [ ] Keep this TODO as the authoritative orchestrator that supersedes the older `TODO-v1-first-release.md` milestone framing.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<planning>`, `flutter-app:<multiple child branches>`, `laravel-app:<multiple child branches>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Web-to-app conversion closure | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Release funnel metrics validation | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Landlord/tenant auth-method governance | `belluga_now_backend:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8` | `https://github.com/belluga/belluga_now_backend/pull/157 (merged -> dev on 2026-04-20)` | `pending` | `pending` | `🟣 Lane-Promoted` |
| Invite app-flow usability closure | `pending` | `pending` | `pending` | `pending` | `Pending; QA-reopened for /convites/compartilhar share CTA stuck on Gerando convite and missing Atualizar lista de amigos action` |
| Phone OTP identity + contact-match baseline | `pending` | `pending` | `pending` | `pending` | `Local-Implemented-Functional-UX-Redesign-Pending; admin OTP settings and functional public OTP flow have focused tests/source Playwright/analyzer/web build evidence; modern Stitch-backed visual redesign, runtime Playwright shard, and final ADB/device proof remain pending` |
| Minimal friends/favorites MVP | `pending` | `pending` | `pending` | `pending` | `Local-Implemented-Home-Consumer-Gap-Reopened; Home Favorites refresh blocker split to child TODO` |
| Home Favorites refresh regression | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Invite occurrence target migration | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Proximity preferences + location origin | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Canonical image-flow hardening | `pending` | `pending` | `pending` | `pending` | `Pending` |
| CORS ownership convergence | `see promotion_lane child TODO` | `pending` | `pending` | `pending` | `🟧 Local-Implemented` |
| Tenant settings read-path optimization | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Critical journey regression gates | `flutter-app: 4a22e40f -> dev@ccb6795a; web-app: 1109b64 -> dev@ec2d41b; laravel-app: dev@37fd59b` | `flutter-app PR #237 merged to dev 2026-04-21; web-app PR #279 merged to dev 2026-04-21` | `pending` | `n/a` | `🟣 Lane-Promoted; completion guard go on 2026-04-22` |
| Event `artists` eradication | `belluga_now_backend:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8` + `belluga_now_front:delphi/flutter-reconcile-store-release-20260419 -> dev @ 72560cf` | `https://github.com/belluga/belluga_now_backend/pull/157 (merged -> dev on 2026-04-20)` + `https://github.com/belluga/belluga_now_front/pull/235 (merged -> dev on 2026-04-20)` | `pending` | `pending` | `Reopened; current code still has release-facing artists residues` |
| Event content save sanitization | `see promotion_lane child TODO` | `pending` | `pending` | `pending` | `🟧 Local-Implemented` |
| Android publication readiness | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] iOS fast-follow work that is intentionally sequenced after Android release.
- [ ] QR login/web auth execution work; that is business-defined but fast-follow, not Android gate.
- [ ] Account workspace, check-in, ticketing, and broader VNext expansion.
- [ ] Tenant-admin hardening that does not block publication of the consumer app.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** publication-critical sequencing decisions, child TODO classification, and release-gate coordination across invites/favorites/promotion/publication readiness.
- **Must update or split the TODO:** anything that widens the Android gate into authenticated web, workspace delivery, or broader social-platform/package work.

## Definition of Done
- [x] Android-first store release has one authoritative orchestrator.
- [x] Publication-critical child TODOs are explicitly classified under this lane or referenced here as authoritative blockers.
- [x] The old `TODO-v1-first-release.md` is marked as superseded for sequencing purposes.
- [x] Fast-follow mandatory work is explicitly separated from Android gate work.
- [x] A dedicated Android publication-readiness child TODO exists and is linked here.
- [x] A dedicated release funnel-metrics validation child TODO exists and is linked here.
- [x] A dedicated landlord/tenant auth-governance child TODO exists under `promotion_lane/store_release_android/` and is linked here as the delivered upstream baseline for Belluga auth execution.
- [x] A dedicated phone OTP identity child TODO exists and is linked here as a publication-critical blocker.
- [x] A dedicated Home Favorites refresh regression child TODO exists under `active/store_release_android/`.
- [x] A dedicated invite occurrence target migration child TODO exists under `active/store_release_android/`.
- [x] A dedicated proximity preferences + location-origin child TODO exists under `active/store_release_android/`.
- [x] A dedicated canonical image-flow hardening child TODO exists under `active/store_release_android/`.
- [x] A dedicated CORS ownership child TODO exists under `promotion_lane/store_release_android/`.
- [x] A dedicated tenant settings optimization child TODO exists under `active/store_release_android/`.
- [x] A dedicated critical-journey regression-gates child TODO exists under `promotion_lane/store_release_android/`.
- [x] A dedicated event `artists` eradication child TODO exists under `active/store_release_android/`.
- [x] A dedicated event content save-sanitization child TODO exists under `promotion_lane/store_release_android/`.

## Validation Steps
- [x] TODO inventory updated so current publication-critical work is discoverable from this file.
- [x] Legacy orchestrator note added to `TODO-v1-first-release.md`.
- [x] Fast-follow orchestrator exists under `active/fast_follow_required/`.
- [x] Child TODO for minimal friends/favorites MVP exists under `active/store_release_android/`.
- [x] Child TODO for Android publication readiness exists under `active/store_release_android/`.
- [x] Child TODO for release funnel-metrics validation exists under `active/store_release_android/`.
- [x] Child TODO for landlord/tenant auth-method governance exists under `promotion_lane/store_release_android/`.
- [x] Child TODO for phone OTP identity + contact matching exists under `active/store_release_android/`.
- [x] Child TODO for Home Favorites refresh regression exists under `active/store_release_android/`.
- [x] Child TODO for invite occurrence target migration exists under `active/store_release_android/`.
- [x] Child TODO for proximity preferences + location-origin exists under `active/store_release_android/`.
- [x] Child TODO for canonical image-flow hardening exists under `active/store_release_android/`.
- [x] Child TODO for definitive CORS ownership convergence exists under `promotion_lane/store_release_android/`.
- [x] Child TODO for tenant settings optimization exists under `active/store_release_android/`.
- [x] Child TODO for critical-journey regression gates exists under `promotion_lane/store_release_android/`.
- [x] Child TODO for event `artists` eradication exists under `active/store_release_android/`.
- [x] Child TODO for event content save sanitization exists under `promotion_lane/store_release_android/`.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-devops`, `strategic-cto`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** this is backlog and release orchestration only, but it changes the active milestone authority and child-slice sequencing.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `n/a in this TODO; module updates belong to child execution lanes`
- **Module decision consolidation targets (required):**
  - `n/a in this TODO; this file is sequencing authority only`

## Decision Pending (Resolve Before Freeze)
- [x] `D-00` Android is the authoritative first publication gate; iOS is fast-follow, not part of the Android release definition.

## Decisions (Resolved Before Freeze)
- [x] `D-01` `TODO-store-release-android.md` is the new sequencing authority for the Android-first release cut.
- [x] `D-02` Business-defined work sequenced after Android release belongs in `active/fast_follow_required/`, not in `active/vnext/` by default.
- [x] `D-03` `TODO-v1-first-release.md` remains historical reference only and is no longer the active milestone orchestrator.
- [x] `D-04` Minimal friends/favorites MVP must be tracked as a dedicated store-release child TODO, not as the full `belluga_connections` package.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `invite_and_social_loop_module.md` web/app conversion ownership | `Preserve` | `Preserve` | current Android-first acquisition flow remains the publication-critical core |
| `flutter_client_experience_module.md` auth/promotion boundary | `Preserve` | `Preserve` | Android release still relies on app-owned trust actions and web promotion boundaries |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Android-first publication is the current release gate.
- [x] `D-02` iOS and QR login/web auth are required follow-up work, but not Android-gate scope.
- [x] `D-03` Legacy lane location does not define current milestone priority anymore.
- [x] `D-04` Any additional store-console/compliance scope discovered during Android release refinement belongs inside `TODO-store-release-android-publication-readiness.md` unless it clearly exceeds that child TODO's boundary.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `TODO-store-release-web-to-app-conversion-gate.md` is still the authoritative conversion blocker inside `store_release_android/`. | the file still contains the real open Android deferred-link/device/KPI gates plus the promotion-boundary release-readiness checkpoint. | The Android gate would be missing its primary conversion blocker. | `High` | `Keep as Assumption` |
| `A-02` | The publication-readiness child TODO now exists, but it still needs refinement into exact repo-owned versus external-console/manual-owner tasks. | `TODO-store-release-android-publication-readiness.md` is active, but its scope question is still open. | Android release ownership would remain explicit but operationally underdefined. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/README.md`
- `foundation_documentation/todos/active/store_release_android/**`
- legacy TODOs updated with classification notes

### Ordered Steps
1. Keep this file current as the parent index of the Android publication-critical child TODOs.
2. Execute and track the four active child lanes: web-to-app conversion, phone OTP auth, minimal friends/favorites MVP, and Android publication readiness.
3. Keep QR-authenticated web and iOS deferred-deep-link work explicitly outside the Android gate in their fast-follow lanes.
4. Preserve the historical note on `TODO-v1-first-release.md` and do not resurrect it as the active release authority.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** this change is TODO inventory orchestration only.
- **Fail-first target(s) (when required):** `n/a`

### Child TODO Test Matrix Rule
- Every child TODO in this store-release lane must derive its Test Coverage Matrix from the active task/acceptance criterion before implementation of that task.
- Matrix rows must be refreshed in the task delivery loop, not only after consolidation.
- A delivery task cannot be marked complete from aggregate suite success unless the matrix row for that exact behavior has direct evidence or an explicit approved blocker/waiver.
- Runtime lanes that cannot run because of missing env/device/credentials must be marked `blocked`, not inferred as passed.
- For invite usability, the matrix must include stuck async CTA state, explicit friends-list refresh, refresh/send race safety, and route re-entry state reset before the child TODO can claim local implementation.

### Runtime / Rollout Notes
- `n/a`
