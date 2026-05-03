# Title
Fast Follow Obligatory

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Some items are already defined by the business but intentionally sequenced after the Android-first release gate. They are not optional and should no longer sit ambiguously inside generic `VNext` backlog.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/mvp-with-app-backlog-realignment.md`
- **Primary story ID:** `ST-07`
- **Why this is the right current slice:** it separates “defined and next” from “future and optional” so the team can finish Android without pretending fast-follow work is speculative.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines the fast-follow lane immediately after Android release.
- It does not widen the Android release gate.
- It does not replace `vnext/` as the long-term backlog for broader future work.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Business-Defined`
- **Next exact step:** keep the direct fast-follow child authorities explicit under this lane: `TODO-ios-universal-links-production-validation.md` for Universal Links/deferred capture, `TODO-ios-push-and-app-store-review-readiness.md` for iOS push/review readiness, and `TODO-qr-login-web-auth.md` for QR login/authenticated web.

## Scope
- [ ] Make iOS fast-follow mandatory and explicit.
- [ ] Make iOS push/App Store review readiness mandatory and explicit.
- [ ] Make QR login/web auth mandatory and explicit.
- [ ] Keep this lane limited to immediately sequenced work after Android release.

## References
- `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-ios-push-and-app-store-review-readiness.md`

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `belluga_now_docker:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| iOS fast-follow | `pending` | `pending` | `pending` | `pending` | `Pending` |
| iOS push / App Store review readiness | `pending` | `pending` | `pending` | `pending` | `Pending` |
| QR login/web auth fast-follow | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] Android-gate publication blockers.
- [ ] Generic VNext backlog that is not immediately sequenced after Android.

## Definition of Done
- [ ] Fast-follow lane exists as a distinct active lane.
- [ ] iOS Universal Links/deferred capture, iOS push/review readiness, and QR login/web auth are explicitly tracked here as business-defined follow-up.
- [ ] Legacy VNext classification notes are updated where necessary.

## Validation Steps
- [ ] `TODO-ios-universal-links-production-validation.md` is explicitly treated as the iOS fast-follow execution authority under this lane.
- [ ] `TODO-ios-push-and-app-store-review-readiness.md` is explicitly treated as the iOS push/review readiness authority under this lane.
- [ ] `TODO-qr-login-web-auth.md` exists under this lane.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`, `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** sequencing/orchestration only.

## Decisions (Resolved Before Freeze)
- [x] `D-01` iOS is mandatory fast-follow after Android, not speculative VNext.
- [x] `D-02` QR login/web auth is business-defined fast-follow, not possibility backlog.
