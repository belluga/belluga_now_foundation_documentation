# TODO (Fast Follow): Account Profile Chip Contrast and Attendance CTA State

**Status:** Production-Ready at `stage` as of `2026-05-25`. Focused Flutter tests, analyzer, APK build, ADB install, operator-visible device validation, source PRs, Docker gitlink promotion, stage deploy, and completion guard passed.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `stage-green`, `Flutter`, `UI-Polish`, `Race-Guard`, `Tenant-Public`
- **Next exact step:** no active TODO follow-up. Any new chip contrast or attendance CTA regression must open a separate TODO.
- **Promotion lane path:** `dev -> stage`

## Stage Promotion Evidence - 2026-05-25
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` source lane | PR `belluga/belluga_now_front#340` merged to `dev`; blocker fix PR `#342` replayed to `dev`; PR `#341` promoted `dev -> stage`. | `stage=a718451812b574b1a981cdb645e49b2b4a1632c2`; run `26384657417` success. |
| `belluga_now_docker` derived runtime lane | PR `#752` carried the Flutter gitlink into Docker `dev`; PR `#754` promoted Docker `dev -> stage`. | `stage=bea62b8d18ab620b9bb9977be9f867bfa9b735db`; run `26385254151` success. |
| Completion guard | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-laravel --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front --laravel-repo belluga/belluga_now_backend` | `Overall outcome: go`; Docker stage `flutter-app` gitlink exact at `a718451812b574b1a981cdb645e49b2b4a1632c2`. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion PRs `front#341` and `docker#754` | Copilot P1/P2 and CI blocker preflight for the promoted Flutter package. | passed | Front Copilot finding `3296103125` fixed by PR `#342`; stage runs `26384657417` and `26385254151` passed. | resolved | All P1/P2 findings were fixed before stage merge; completion guard returned `Overall outcome: go`. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion lane and TODO governance | Checked source-owned fixes, derived `web-app` boundary, Docker gitlink path through `bot/next-version`, and TODO threshold/archive hygiene. | passed | `github-stage-promotion-orchestrator`; `github_promotion_completion_guard.sh`; TODO directory reconciliation. | no findings | Preserved source-owned fixes, did not manually promote `web-app`, promoted gitlinks through lane-owned Docker PRs, and archived this TODO only after its `stage` threshold was green. |

## Context
- In the tenant-public Account Profile detail route, the reduced/collapsed header renders taxonomy/category chips whose label color does not guarantee contrast against the chip background.
- In the tenant-public immersive event detail route, tapping `Bóora! Confirmar Presença!` does not immediately communicate that confirmation is in progress and allows repeated taps while the async confirmation request is pending.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `tenant-public-ui-polish-chip-contrast-attendance-cta-state`
- **Direct-to-TODO rationale:** safe. This is one small visual/interaction polish slice with no API/schema/business-rule change.

## Contract Boundary
- This TODO covers only Flutter tenant-public UI behavior:
  - Account Profile collapsed taxonomy chip contrast.
  - Event detail attendance confirmation CTA pending state and duplicate-tap guard.
- It does not change attendance policy, invite acceptance rules, backend contracts, repository APIs, or promotion-lane topology.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Decision promotion targets:** no durable module update expected unless implementation discovers a reusable visual contrast rule worth promoting.

## Scope
- [x] Ensure Account Profile collapsed taxonomy chip labels choose a contrast-safe foreground color from the actual chip background.
- [x] Ensure the attendance confirmation CTA enters a visible pending state immediately after tap.
- [x] Ensure repeated taps while attendance confirmation is pending do not dispatch duplicate confirmation requests.
- [x] Add focused Flutter tests for contrast behavior and duplicate-tap/pending-state behavior.

## Out of Scope
- [ ] Backend attendance mutation semantics.
- [ ] Invite acceptance or supersession rules.
- [ ] Account Profile taxonomy payload/schema changes.
- [ ] Redesigning Account Profile or Event detail layout.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Collapsed Account Profile taxonomy chips must compute label foreground from the rendered chip background, not assume a fixed theme token remains contrast-safe.
- [x] `D-02` The attendance confirmation CTA concurrency policy is `drop duplicate`: while the first confirmation is pending, additional taps must not dispatch another confirmation request.
- [x] `D-03` Pending attendance confirmation must be visible in the CTA itself, with disabled action and loading/progress copy or affordance.
- [x] `D-04` The fix must stay Flutter-local and controller/screen owned; no backend/API contract changes are part of this slice.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The collapsed taxonomy chip is rendered in `AccountProfileDetailScreen._buildCollapsedTitle`. | Code inspection of `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`. | The fix would need to move to a shared chip widget. | High | Keep as assumption. |
| `A-02` | The event confirmation CTA uses `ImmersiveEventDetailController.confirmAttendance()` and screen footer state from `isLoadingStreamValue`/confirmed streams. | Code inspection of `immersive_event_detail_screen.dart` and controller tests. | The guard would need repository-level idempotency or a distinct CTA state stream. | High | Keep as assumption. |
| `A-03` | Focused widget/controller tests are sufficient for this cosmetic/interactivity slice; no ADB proof is required before local implementation because no platform API or backend contract changes. | Scope is Flutter UI state + existing controller async call. | If device-only rendering differs materially, add ADB smoke before promotion. | Medium | Keep as assumption. |

## Execution Plan
1. Add/adjust focused tests proving collapsed taxonomy chip label contrast is derived from chip background.
2. Add/adjust focused tests proving confirmation CTA shows pending state and drops duplicate taps.
3. Implement a small foreground-color helper or localized use of `ThemeData.estimateBrightnessForColor`.
4. Guard `confirmAttendance()` against concurrent execution and ensure the screen reads the loading stream to render disabled pending CTA.
5. Run focused Flutter tests and analyzer.

## Definition of Done
- [x] Collapsed Account Profile taxonomy chip labels are contrast-safe for light and dark chip backgrounds.
- [x] Attendance CTA visibly changes to pending state while confirmation is in flight.
- [x] Attendance CTA cannot trigger duplicate confirmation calls while the first call is pending.
- [x] Focused Flutter tests pass.
- [x] `fvm dart analyze --format machine` passes for the touched Flutter repo state.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / focused widget and controller tests` | The slice changes Flutter Account Profile rendering plus immersive event detail controller/screen state. | `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | local delivery | `passed` | Terminal result: `97` tests passed. | Includes light/dark chip contrast, in-flight CTA state, and duplicate-tap drop policy. |
| `flutter-app / analyzer` | Touched Flutter screen/controller/test files must remain analyzer-clean. | `fvm dart analyze --format machine` | local delivery | `passed` | Exit code `0`. | No diagnostics emitted. |
| `flutter-app / lane APK build and install` | User requested a manual device build/install from the current branch for Guarappari dev testing. | `./script/build_lane.sh dev apk --debug --flavor guarappari --dart-define=FLAVOR=guarappari`; `adb -s 192.168.15.9:5555 install -r -d build/app/outputs/flutter-apk/app-guarappari-debug.apk` | manual validation | `passed` | APK built at `build/app/outputs/flutter-apk/app-guarappari-debug.apk`; install returned `Success`. | App data was cleared and package `com.guarappari.app` was launched on ADB device `192.168.15.9:5555`. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Ensure Account Profile collapsed taxonomy chip labels choose a contrast-safe foreground color from the actual chip background. | automated widget + ADB device manual validation | `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart --plain-name 'collapsed taxonomy chips choose foreground'`; ADB install/smoke on `192.168.15.9:5555` | local Flutter widget + ADB device `192.168.15.9:5555` | `passed` | Covers explicit light and dark adversarial chip backgrounds, each with unsafe theme foreground candidates, asserts contrast ratio `>= 4.5`, and the operator accepted the installed dev build visual behavior. |
| `SCOPE-02` | Scope | Ensure the attendance confirmation CTA enters a visible pending state immediately after tap. | automated widget | `fvm flutter test --no-pub test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | local Flutter widget | `passed` | The footer renders `Confirmando presença...` while the repository confirmation future is held open. |
| `SCOPE-03` | Scope | Ensure repeated taps while attendance confirmation is pending do not dispatch duplicate confirmation requests. | automated controller/widget | `fvm flutter test --no-pub test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | local Flutter tests | `passed` | Controller returns `skipped` for duplicate in-flight confirm; widget test keeps repository `confirmCalls == 1`. |
| `SCOPE-04` | Scope | Add focused Flutter tests for contrast behavior and duplicate-tap/pending-state behavior. | automated suite | `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | local Flutter tests | `passed` | `97` tests passed. |
| `DOD-01` | Definition of Done | Collapsed Account Profile taxonomy chip labels are contrast-safe for light and dark chip backgrounds. | automated widget + ADB device manual validation | `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart --plain-name 'collapsed taxonomy chips choose foreground'`; ADB install/smoke on `192.168.15.9:5555` | local Flutter widget + ADB device `192.168.15.9:5555` | `passed` | Light and dark background cases both assert WCAG-style contrast ratio `>= 4.5`; operator accepted the device visual behavior. |
| `DOD-02` | Definition of Done | Attendance CTA visibly changes to pending state while confirmation is in flight. | automated widget | `fvm flutter test --no-pub test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | local Flutter widget | `passed` | In-flight label appears and active confirm label is removed while in flight. |
| `DOD-03` | Definition of Done | Attendance CTA cannot trigger duplicate confirmation calls while the first call is pending. | automated controller/widget | Controller and screen focused tests above. | local Flutter tests | `passed` | Duplicate call is dropped and repository call count remains `1`. |
| `DOD-04` | Definition of Done | Focused Flutter tests pass. | automated suite | `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | local Flutter tests | `passed` | `97` tests passed. |
| `DOD-05` | Definition of Done | `fvm dart analyze --format machine` passes for the touched Flutter repo state. | analyzer | `fvm dart analyze --format machine` | local Flutter analyzer | `passed` | Exit code `0`, no diagnostics emitted. |

## Decision Adherence Validation
| Decision | Status | Evidence |
| --- | --- | --- |
| `D-01` | Adherent | `AccountProfileDetailScreen` now computes collapsed chip label color via `Color.computeIconColor(...)` from `secondaryContainer`; widget test asserts contrast. |
| `D-02` | Adherent | `ImmersiveEventDetailController.confirmAttendance()` guards `_confirmAttendanceInFlight` and returns `AttendanceConfirmationResult.skipped` for duplicate calls. |
| `D-03` | Adherent | Event detail footer renders disabled `Confirmando presença...` while `isConfirmationStateLoadingStreamValue` is true. |
| `D-04` | Adherent | Changes are Flutter-only in screen/controller/tests; no backend/API/repository contract changed. |

## Profile Scope & Handoffs
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `Assurance / Tester-Quality`

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** consolidated planning review.
