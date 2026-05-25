# TODO (Fast Follow): Restore Technical Integrations `initialSection` Route Contract Before Main Promotion

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Production-Ready. Local implementation + local CI-equivalent evidence completed, the Flutter source lane replay reached `main`, and the Docker production promotion completed successfully afterward.

## Title
Fast Follow: Restore Technical Integrations `initialSection` Route Contract Before Main Promotion

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Flutter PR `belluga/belluga_now_front#331` is the first source leg in the current `stage -> main` promotion order.
- A pertinent review finding on that PR identified a route-contract regression in the tenant-admin technical integrations screen.
- The bounded external audit round confirmed this is a real blocker for `main`, not cleanup:
  - `_focusInitialSection()` runs only once in the first post-frame callback
  - the keyed target sections are hidden behind `_initialTechnicalIntegrationsLoaded` until async load completes
  - `_focusInitialSection()` returns early when `targetContext == null`
  - no retry happens after content mounts
- Result: routes opening the screen with `initialSection` can silently fail to auto-scroll/focus the requested section.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-main-promotion-technical-integrations-initial-section-route-contract`
- **Why this is the right current slice:** one bounded frontend blocker with one user-visible contract to restore before the source PR can reach `main`.
- **Direct-to-TODO rationale:** the defect is already concrete, bounded to one route/screen contract, and externally audited.

## Contract Boundary
- This TODO covers only the technical integrations `initialSection` deep-open contract regression.
- This TODO includes:
  - restoring deterministic focus/scroll to the requested section after async settings load
  - adding bounded regression evidence for that route contract
- This TODO does **not** include broader refactors of tenant-admin settings screen architecture.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Fast-Follow`, `Flutter`, `Main-Promotion-Blocker`, `External-Audit-Confirmed`
- **Next exact step:** none for this TODO; the review blocker is closed in `main`.

## Scope
- [x] Restore the `initialSection` route contract so deep-open navigation lands on the requested section even when content is gated by async initial load.
- [x] Add regression coverage that fails if the section focus is lost after async mount.
- [x] Re-run the relevant local CI-equivalent Flutter suites before this slice can claim `Local-Implemented`.

## Out of Scope
- [ ] Reworking unrelated tenant-admin settings surfaces.
- [ ] Opportunistic refactors of controller, repository, or route topology beyond what this contract needs.
- [ ] Any direct fix on `stage` or `main` without replaying the canonical source lane.

## Definition of Done
- [x] Opening the technical integrations screen with a non-default `initialSection` lands on the requested section after async load completes.
- [x] The fix has regression evidence that exercises the async-gated deep-open path.
- [x] The source lane is ready to be replayed `dev -> stage -> main` without this review blocker remaining open.

## Validation Steps
- [x] Add/adjust targeted Flutter regression test(s) for the async-gated `initialSection` focus path.
- [x] Run the relevant Flutter local CI-equivalent suite(s) for the touched source surface.
- [x] Capture final blocker-closure evidence against the same route contract named in PR `#331`.

## Execution Lane Tracking
- **Local implementation branches:** `belluga_now_front:<pending>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `stage`
- **Production-ready threshold for this TODO:** `main`

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `initialSection` route-contract restoration | `belluga_now_front:stage` source lane | replayed through source promotion | source lane replayed before main | `belluga_now_front#331` merged `2026-05-22` at `be2b90ce68c299590b3549b96752da4abc99f6d0` | `Production-Ready`; Docker PR `#751` and production run `26320227463` completed green afterward |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** one bounded route/screen contract with targeted regression evidence.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary canonical anchors:**
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md`

## Decisions (Resolved Through Audit)
- [x] `D-01` The current candidate broke a real route contract: `initialSection` must continue to land on the requested section after async load.
- [x] `D-02` This blocker must be fixed on the Flutter source lane and replayed through `dev -> stage -> main`; direct `stage/main` patching is not acceptable.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The regression is local to the screen lifecycle, not to route parsing or enum decoding of `initialSection`. | External audit packet + current screen code only schedules focus once before gated sections mount. | The fix boundary would need to widen into route construction/navigation plumbing. | `High` | `Keep as Assumption` |
| `A-02` | Re-scheduling the focus after async mount can restore the contract without broader architecture refactor. | The keyed target sections already exist and the only missing behavior is a retry after `_initialTechnicalIntegrationsLoaded` flips. | The screen may need a controller-owned scroll contract or route-level deferred handoff. | `Medium` | `Keep as Assumption` |
| `A-03` | Widget-level regression proof is sufficient for this slice because the defect is a screen-local async mount contract. | The failure mode is deterministic inside one screen and does not require live backend/device behavior to reproduce. | If the route only fails under browser/device runtime timing, additional integration evidence would be required before `Local-Implemented`. | `Medium` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces
- `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_technical_integrations_screen.dart`
- `flutter-app/test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-technical-integrations-initial-section-route-contract.md`

### Ordered Steps
1. Freeze the repair boundary in this TODO and keep the scope limited to the async-gated `initialSection` contract.
2. Implement a bounded focus retry so the requested section is scrolled into view after the gated content mounts.
3. Add a regression widget test that reproduces the pre-fix failure shape and proves the post-load deep-open contract.
4. Run the local Flutter CI-equivalent matrix and reconcile evidence back into this TODO.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** the defect is already externally isolated to a specific screen lifecycle gap; the fastest bounded closure is a focused regression test added alongside the screen repair.

### Acceptance Cases
- `AC-01` When the screen opens with a non-default `initialSection` and the technical integrations content is still loading, the first successful post-load frame scrolls the requested section into the viewport automatically.
- `AC-02` The screen does not require a second manual navigation or user scroll for `initialSection` to take effect after async load.
- `AC-03` Regression coverage fails if the requested section remains off-viewport after async load completes.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Tenant-admin deep-open to technical integrations `initialSection` | Route contract affects where the admin lands after navigation into the screen. | `shared-android-web` | `widget/runtime-local` | `no` | `no` | Focused widget regression on the screen with async-gated content. | The defect is screen-local and can be deterministically proven without device/browser publication for this blocker slice. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_front / flutter analyze` | Touched surface is Flutter presentation code. | `cd flutter-app && fvm dart analyze --format machine` | `Local-Implemented` | `passed` | `cd flutter-app && fvm dart analyze --format machine` | Passed after the screen switched to `SingleChildScrollView` and the focus-retry hook was added. |
| `belluga_now_front / tenant-admin settings widget regression` | The fix must prove the async-gated `initialSection` contract. | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` | `Local-Implemented` | `passed` | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` | The full screen suite now includes the async-gated deep-open regression and passed green. |
| `belluga_now_front / ADB runtime proof for route contract` | The route contract is user-visible and needs item-specific device evidence before closure. | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | `Local-Implemented` | `passed` | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | ADB runtime proof passed on `192.168.15.9:5555`. |

### Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Visible Route / Action | Planned Evidence | Waiver |
| --- | --- | --- | --- | --- |
| `TenantAdminSettingsTechnicalIntegrationsScreen.initialSection` focus contract | `Flutter admin` | tenant-admin opens `Integrações técnicas` anchored to a requested section | widget regression in `tenant_admin_settings_screen_test.dart` | none |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-EXACT-01` | Scope | Restore the `initialSection` route contract so deep-open navigation lands on the requested section even when content is gated by async initial load. | code + device runtime proof | `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_technical_integrations_screen.dart`; `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | ADB `192.168.15.9:5555` | passed | Route contract proved on device for `TenantAdminSettingsTechnicalIntegrationsScreen(initialSection: push)` after the async gate opened. |
| `SCOPE-EXACT-02` | Scope | Add regression coverage that fails if the section focus is lost after async mount. | widget regression | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart --plain-name "retries initialSection focus after async technical integrations mount"` | local Flutter test | passed | The new regression fails if the requested section never enters the viewport after load. |
| `SCOPE-EXACT-03` | Scope | Re-run the relevant local CI-equivalent Flutter suites before this slice can claim `Local-Implemented`. | ci-equivalent | `Local CI-Equivalent Suite Matrix rows: flutter analyze, tenant-admin settings widget regression, and ADB runtime proof` | local Flutter analyzer + widget/device tests | passed | All in-scope local verification rows were executed and passed. |
| `SCOPE-01` | Scope | Restore the `initialSection` contract for async-gated technical integrations sections. | code | `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_technical_integrations_screen.dart` | local Flutter screen | passed | The screen now mounts all sections in a bounded `SingleChildScrollView` and retries focus after the async load completes. |
| `SCOPE-02` | Scope | Add regression coverage for the async deep-open path. | widget test | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart --plain-name "retries initialSection focus after async technical integrations mount"` | local Flutter test | passed | The new regression proves the push section is brought into the viewport after gated load completion. |
| `DOD-EXACT-01` | Definition of Done | Opening the technical integrations screen with a non-default `initialSection` lands on the requested section after async load completes. | device runtime proof | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | ADB `192.168.15.9:5555` | passed | Device execution proved the requested section is brought into view after the deferred load. |
| `DOD-EXACT-02` | Definition of Done | The fix has regression evidence that exercises the async-gated deep-open path. | widget + device regression | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart --plain-name "retries initialSection focus after async technical integrations mount"`; `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | local Flutter test + ADB `192.168.15.9:5555` | passed | The same item-specific regression is green in both local widget and ADB runtime execution. |
| `DOD-EXACT-03` | Definition of Done | The source lane is ready to be replayed `dev -> stage -> main` without this review blocker remaining open. | ci-equivalent readiness | `Local CI-Equivalent Suite Matrix rows: flutter analyze, tenant-admin settings widget regression, and ADB runtime proof` | local Flutter analyzer + widget/device tests | passed | The blocker is removed locally and the source-lane verification set is green. |
| `DOD-01` | Definition of Done | Non-default `initialSection` lands on the requested section after async load. | widget regression | `tenant_admin_settings_screen_test.dart` full suite | local Flutter test | passed | The route-contract regression is covered in the green screen suite. |
| `DOD-02` | Definition of Done | Regression evidence exercises the async-gated deep-open path. | widget regression | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` | local Flutter test | passed | The new test blocks reintroduction of the one-shot pre-mount focus bug. |
| `VAL-EXACT-01` | Validation Steps | Add/adjust targeted Flutter regression test(s) for the async-gated `initialSection` focus path. | widget regression | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart --plain-name "retries initialSection focus after async technical integrations mount"` | local Flutter test | passed | Added the targeted regression to the screen suite. |
| `VAL-EXACT-02` | Validation Steps | Run the relevant Flutter local CI-equivalent suite(s) for the touched source surface. | ci-equivalent | `cd flutter-app && fvm dart analyze --format machine`; `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`; `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | local Flutter analyzer + widget/device tests | passed | The analyzer, the full screen widget suite, and the ADB device proof all ran and passed. |
| `VAL-EXACT-03` | Validation Steps | Capture final blocker-closure evidence against the same route contract named in PR `#331`. | device integration test blocker-closure evidence | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart -d 192.168.15.9:5555 --plain-name "retries initialSection focus after async technical integrations mount" -r expanded` | ADB device `192.168.15.9:5555` | passed | This ADB device execution is the final blocker-closure evidence for the PR `#331` route contract. |
| `VAL-01` | Validation Steps | Relevant local Flutter CI-equivalent suites pass. | ci-equivalent | `cd flutter-app && fvm dart analyze --format machine` | local analyzer | passed | Analyzer clean. |
| `VAL-02` | Validation Steps | Relevant local Flutter CI-equivalent suites pass. | ci-equivalent | `cd flutter-app && fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` | local widget tests | passed | Full screen suite green. |

## References
- `belluga/belluga_now_front#331`
- [tenant_admin_settings_technical_integrations_screen.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_technical_integrations_screen.dart:47)
- [TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md:1)
- [package.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/package.md:1)
- [round-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/triple-audit-20260521T235900Z/round-01/round-summary.md:1)
