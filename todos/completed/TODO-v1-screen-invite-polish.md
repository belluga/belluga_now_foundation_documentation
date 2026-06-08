# TODO (V1): Screen Polish - Invite

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Production-Ready. Historical archival catch-up on `2026-06-08` confirmed that current `origin/main` still carries the delivered invite-footer split, hero/CTA hierarchy polish, bounded invite-flow behavior, and focused Flutter coverage for this slice.
**Owners:** Flutter Team
**Objective:** Apply a simple, bounded visual edit to the tenant-public invite screen/decision flow while preserving invite contract and behavior semantics.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to move already promoted TODOs to `completed` after deeper code/main investigation.
- **Approval scope:** documentation-only archival closeout for this bounded invite-screen polish slice after confirming the delivered Flutter contract still exists on current `origin/main`.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Simple-Edit`, `Store-Release`, `Flutter`, `Invite-Flow`, `UI-Polish`, `No-Contract-Change`, `Focused-Flutter-Green`, `Analyzer-Green`, `origin-main-reviewed`, `Historical-Archival-Catch-Up`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-v1-screen-invite-polish.md`.
- **Post-commit/push status:** `completed`

---

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Scope (Single Screen)
- Apply only simple visual adjustments to the invite hero/header hierarchy.
- Improve primary/secondary CTA clarity only where the current layout is visibly weak.
- Keep loading/empty/error state treatment lightweight and local to existing UI states.
- Keep this TODO as a small edit; do not turn it into the invite/social-loop MVP workstream.

## Out of Scope
- Invite API/contract changes.
- New invite feature capabilities.
- Contact picking, contact groups, inviteable list composition, friends/favorites, or external-share behavior.
- `/convites/compartilhar` operational regressions such as sharing CTA stuck on `Gerando...` or missing friends-list refresh action; those are owned by `TODO-store-release-minimal-friends-and-favorites-mvp.md`, not this visual-only polish TODO.
- Onboarding, anonymous identity, Auth Wall, or web-to-app promotion policy changes.
- Backend, route, schema, controller, or repository behavior changes unless a trivial test-only adjustment is required by the UI edit.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; invite endpoints/contracts remain unchanged.
- `D-02`: Existing invite decision semantics (`accept/decline` and current follow-up behavior) are preserved.
- `D-03`: This screen stays focused on invite decision context; contact-picking/share mechanics remain in invite-friends flow.
- `D-04`: CTA hierarchy must prioritize the primary decision action without changing underlying behavior.
- `D-05`: Loading/error/result feedback remains explicit and behavior-compatible with current controller flow.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.
- `D-08`: Invite decision controls (`accept`, `decline`, swipe affordance, and auth-required CTA) move to a page-bottom footer outside the hero card; the hero card retains invite context plus a single `Ver detalhes` action.

## Tasks
- [x] Freeze the small visual adjustment list before implementation.
- [x] Polish invite hero and top chrome visual hierarchy.
- [x] Polish primary/secondary CTAs and spacing.
- [x] Improve loading/empty/error visual states.
- [x] Validate responsiveness and text overflow behavior.
- [x] Ensure invite decision transitions remain stable and deterministic.

## Acceptance Criteria
- [x] The final change remains a small visual edit with no invite contract or flow expansion.
- [x] Invite context (who/what/when) is visually clearer at first glance.
- [x] Primary and secondary decision CTAs are visually unambiguous.
- [x] Loading/error/result states are explicit and readable.
- [x] No regression in invite decision flow behavior.

## Definition of Done
- [x] All tasks and acceptance criteria are checked with evidence.
- [x] Manual smoke covers both decision outcomes and error/retry states.
- [x] Visual polish does not require API/backend changes.

## Validation Steps
- [x] Manual smoke: invite decision flow (accept/decline/next-step states).
- [x] Manual smoke: loading/error states.
- [x] Manual smoke: responsive layout behavior.
- [x] Manual smoke: overflow and long-text handling in hero/context area.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Invite decision controls move to a page-bottom footer while the hero retains invite context plus `Ver detalhes`. | Flutter widget tests | `origin/main flutter-app invite_flow_screen_test.dart:470,549` | `origin/main` Flutter test corpus | passed | `origin/main` still asserts the delivered `Recusar` / `Aceitar` / `Ver detalhes` surface contract. |
| `SCOPE-02` | Scope | The screen remains a bounded visual-only slice and does not reopen invite API or flow expansion. | Flutter controller tests | `origin/main flutter-app invite_flow_controller_test.dart:466,525,565` | `origin/main` Flutter test corpus | passed | The current controller contract still preserves canonical accept/decline behavior and auth boundaries. |
| `AC-01` | Acceptance Criteria | Primary and secondary decision CTAs remain visually and behaviorally explicit without regression. | Flutter widget tests | `origin/main flutter-app invite_flow_screen_test.dart:470,1144` | `origin/main` Flutter test corpus | passed | The published test suite still exercises both decision CTA presence and bounded no-decision exit behavior. |
| `VAL-01` | Validation Steps | Anonymous web invite fallback still uses canonical app promotion instead of reopening old auth/login behavior. | Flutter widget tests | `origin/main flutter-app invite_flow_screen_test.dart:743` | `origin/main` Flutter test corpus | passed | The current invite flow still routes anonymous web through app promotion, preserving the delivered split without screen-local regressions. |
| `ARCH-INVITE-01` | Historical archival review | Foundation docs history still carries the original promotion-lane closeout anchor for this slice. | Git history review | `origin/main foundation_documentation commit 3878ebb (docs: promote invite polish todo)` | `origin/main` docs history | passed | `origin/main` still records `3878ebb docs: promote invite polish todo`, which matches the historical delivery timeline. |
| `AC-02` | Acceptance Criteria | The final change remains a small visual edit with no invite contract or flow expansion. | Flutter controller + widget review | `origin/main flutter-app invite_flow_controller_test.dart:466,525,565 and invite_flow_screen_test.dart:470` | `origin/main` Flutter test corpus | passed | Current mainline coverage still proves the slice preserved invite behavior rather than widening it. |
| `AC-03` | Acceptance Criteria | Invite context (who/what/when) is visually clearer at first glance. | Flutter widget tests | `origin/main flutter-app invite_flow_screen_test.dart:470,549` | `origin/main` Flutter test corpus | passed | The published invite surface still renders the delivered context and detail affordance together with the split CTA footer. |
| `AC-04` | Acceptance Criteria | Primary and secondary decision CTAs are visually unambiguous. | Flutter widget tests | `origin/main flutter-app invite_flow_screen_test.dart:470` | `origin/main` Flutter test corpus | passed | The authenticated invite surface still asserts simultaneous `Recusar` / `Aceitar` rendering plus `Ver detalhes`. |
| `AC-05` | Acceptance Criteria | Loading/error/result states are explicit and readable. | Flutter widget + controller tests | `origin/main flutter-app invite_flow_screen_test.dart:743,1144 and invite_flow_controller_test.dart:466` | `origin/main` Flutter test corpus | passed | Current tests still cover explicit fallback/result behavior around the polished surface. |
| `AC-06` | Acceptance Criteria | No regression in invite decision flow behavior. | Flutter controller + widget tests | `origin/main flutter-app invite_flow_controller_test.dart:525,565 and invite_flow_screen_test.dart:1144` | `origin/main` Flutter test corpus | passed | Canonical accept/decline/no-decision behavior remains guarded on current main. |
| `DOD-01` | Definition of Done | All tasks and acceptance criteria are checked with evidence. | Evidence audit | This Completion Evidence Matrix plus the `origin/main` review commands recorded in `Main Promotion Evidence - 2026-06-08`. | docs + `origin/main` review | passed | The archival catch-up explicitly maps the delivered criteria to current mainline evidence. |
| `DOD-02` | Definition of Done | Manual smoke covers both decision outcomes and error/retry states. | Historical Android device/browser smoke reuse + current main review | `historical invite-polish manual smoke packet reused here; current main review anchored by invite_flow_controller_test.dart:525,565 and invite_flow_screen_test.dart:1144` | historical Android device/browser smoke + `origin/main` regression review | passed | The original manual-smoke claim is preserved and current focused tests still guard the same decision/error boundaries. |
| `DOD-03` | Definition of Done | Visual polish does not require API/backend changes. | Flutter-only scope review | `origin/main flutter-app invite_flow_screen_test.dart:470,549 and invite_flow_controller_test.dart:525` | `origin/main` Flutter-only review | passed | The preserved evidence remains entirely on the Flutter invite-flow surface and controller contract. |
| `VAL-02` | Validation Steps | Manual smoke: invite decision flow (accept/decline/next-step states). | Historical Android device/browser smoke reuse + current regression review | `historical invite-polish manual smoke packet reused here; current main review anchored by invite_flow_controller_test.dart:525,565 and invite_flow_screen_test.dart:1144` | historical Android device/browser smoke + `origin/main` regression review | passed | Current tests still guard the same decision-path states validated manually in the original packet. |
| `VAL-03` | Validation Steps | Manual smoke: loading/error states. | Historical Android device/browser smoke reuse + current regression review | `historical invite-polish manual smoke packet reused here; current main review anchored by invite_flow_screen_test.dart:743,1144` | historical Android device/browser smoke + `origin/main` regression review | passed | Current widget coverage still exercises explicit fallback/loading/result behavior around the polished screen. |
| `VAL-04` | Validation Steps | Manual smoke: responsive layout behavior. | Historical Android device/browser smoke reuse + current surface review | `historical invite-polish manual smoke packet reused here; current main review anchored by invite_flow_screen_test.dart:470,549` | historical Android device/browser smoke + `origin/main` surface review | passed | The current published surface continues to expose the delivered split layout and detail affordance under the focused invite screen suite. |
| `VAL-05` | Validation Steps | Manual smoke: overflow and long-text handling in hero/context area. | Historical Android device/browser smoke reuse + current surface review | `historical invite-polish manual smoke packet reused here; current main review anchored by invite_flow_screen_test.dart:470,549` | historical Android device/browser smoke + `origin/main` surface review | passed | The archived slice remains bounded to the same hero/context surface already validated before promotion. |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new Flutter code was executed for this move; the TODO already carried its bounded manual validation and the archival decision only reconciles stale lane status with current `origin/main`. | `n/a` | `historical archival closeout` | `n/a` | Existing validation steps plus the `origin/main` review recorded below. | Documentation-only move; no fresh analyzer/test rerun was required. |

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` widget review | `git -C flutter-app grep -n "Authenticated invite shows decline/accept contract\\|Ver detalhes opens public event route using invite slug\\|Invite flow web anonymous fallback uses canonical app promotion\\|Closing invite flow without decision routes home and keeps invite pending" origin/main -- test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart` | `origin/main` still carries the delivered invite CTA/footer/detail/no-decision coverage. |
| `flutter-app` controller review | `git -C flutter-app grep -n "unauthenticated decision does not call invite accept endpoints\\|accepted decision uses canonical share-code accept after share entry\\|declined decision uses canonical invite decline after materialization" origin/main -- test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart` | `origin/main` still carries the bounded invite-flow controller contract that this visual polish was required to preserve. |
| `foundation_documentation` history review | `git -C foundation_documentation log --oneline --decorate=no origin/main --grep="docs: promote invite polish todo" -n 5` | `origin/main` history still carries the original docs-side promotion anchor for this slice. |
| `Archival decision` | Explicit `2026-06-08` user request to move already promoted TODOs to `completed` after code/main investigation. | Documentation-only closeout approved. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | Confirm this move only reconciles a stale TODO with Flutter tests and docs history already present on `origin/main`. | `n/a` | `git -C flutter-app grep -n "Authenticated invite shows decline/accept contract\\|accepted decision uses canonical share-code accept after share entry" origin/main -- test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart` | `none` | No fresh PR/Copilot review surface exists for this documentation-only move. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Simple-edit archival hygiene` | Prevent a delivered visual-only slice from lingering in `promotion_lane/` solely because the historical TODO packet was thinner than later closeout formats. | `passed` | `origin/main` widget/controller review and foundation-docs history review | `no findings` | The archival move preserves the original bounded scope and does not widen the TODO into invite/social-loop scope. |

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The file is being archived after the implementation wave already finished. | Truthful stage labeling and explicit archival rationale. | Claiming fresh implementation or promotion work occurred in this turn. | Record the archival catch-up basis directly in the TODO. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The task is to distinguish real closure from stale lane drift. | Keep the evidence basis concrete and minimal. | Hiding that this is a historical normalization pass. | Capture current `origin/main` review plus historical docs anchor. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | The source-of-truth question is whether the same TODO has already crossed the final lane threshold. | Preserve the governing TODO and close it cleanly. | Leaving already-main-carried work stranded in `promotion_lane/`. | Move the TODO to `completed` once the archival sections are guard-clean. |

## TODO Closeout Disposition
- **Completed path:** `foundation_documentation/todos/completed/TODO-v1-screen-invite-polish.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming the invite polish slice remains present on current `origin/main`.
- **Historical note:** the original change was always a bounded Flutter-only visual polish; this move only reconciles stale lane status with current test and docs-history evidence.
- **Reopen rule:** any new invite decision layout or CTA regression must open a new TODO rather than reopen this archival slice.
