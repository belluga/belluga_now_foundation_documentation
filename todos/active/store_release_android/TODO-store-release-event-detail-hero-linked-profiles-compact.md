# TODO: Event Detail Hero Linked Profiles Compact

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The tenant-public event detail hero currently renders every linked account profile chip. Events with many linked profiles make the hero visually heavy and push core event metadata down. This TODO keeps the change front-only: Flutter already receives the linked profiles and dynamic profile-type tabs from the current event detail payload.

**Execution integrity note:** a local implementation draft was applied before this TODO gate was formalized. The user has now accepted the solution proposal and authorized work on this item plus the Home pagination item; the draft still requires evidence reconciliation before delivery claim.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** one bounded user-visible polish change on the event detail hero, with one interaction target: compact many linked profiles and jump to the first profile-type tab.
- **Direct-to-TODO rationale:** the request is a small, specific front-only UX slice with no backend contract, schema, route, or module-boundary change.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` define **HOW** Delphi intends to deliver and verify the contract.
- This TODO is **bounded but elastic** only for local Flutter widget/test adjustments required by the same hero compacting behavior.
- Any backend payload, API contract, route contract, or canonical module change is out of scope and requires a new approval conversation.

## Delivery Status Canon
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `local-validated; belluga-space-local-bundle-deployed`
- **Next exact step:** push `flutter-app:feature/store-release-event-hero-home-agenda-pagination` and open PR to `dev`.

## Scope
- [x] Compact the event detail hero linked-profile chips when more than three non-venue linked profiles are available.
- [x] Render only the first linked profile chip plus `e mais X`, where `X` is the number of additional linked profiles.
- [x] Make both the visible chip and `e mais X` activate the dynamic tab for the first linked profile's `profile_type`.
- [x] Preserve existing dynamic profile-type tabs and linked-profile cards.

## Out of Scope
- [ ] Backend/API payload changes.
- [ ] Event query, occurrence, favorite, or route contract changes.
- [ ] Account profile detail screen changes.
- [ ] Canonical module doc changes; the existing contracts already cover dynamic tabs and linked profiles.

## Definition of Done
- [x] `DOD-01`: Hero shows all linked-profile chips when there are three or fewer non-venue linked profiles.
- [x] `DOD-02`: Hero shows only the first linked profile chip plus `e mais X` when there are more than three non-venue linked profiles.
- [x] `DOD-03`: Tapping the first chip or `e mais X` activates the first linked profile's dynamic profile-type tab.
- [x] `DOD-04`: Existing profile cards inside the dynamic tab still navigate to `/parceiro/:slug` through the existing `PartnerDetailRoute`.
- [x] `DOD-05`: No backend/API/module contract files are changed.

## Validation Steps
- [x] `VAL-01`: Run focused widget test for `ImmersiveEventDetailScreen`.
- [x] `VAL-02`: Run official Flutter analyzer command: `fvm dart analyze --format machine`.
- [x] `VAL-03`: Record any analyzer blocker that is unrelated to this feature separately from feature validation.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01`: Hero shows all linked-profile chips when there are three or fewer non-venue linked profiles. | test | `event hero keeps three linked profiles expanded and opens first profile type tab` | local | passed | Verified three chips render and `eventHeroMoreProfilesChip` is absent. |
| `DOD-02` | `Definition of Done` | `DOD-02`: Hero shows only the first linked profile chip plus `e mais X` when there are more than three non-venue linked profiles. | test | `event hero compacts many linked profiles and opens first profile type tab` | local | passed | Verified second chip is absent and `e mais 3` is present for four profiles. |
| `DOD-03` | `Definition of Done` | `DOD-03`: Tapping the first chip or `e mais X` activates the first linked profile's dynamic profile-type tab. | test | `event hero keeps three linked profiles expanded...`; `event hero compacts many linked profiles...` | local | passed | First chip and `e mais X` both select `immersiveTabSelected_1`. |
| `DOD-04` | `Definition of Done` | `DOD-04`: Existing profile cards inside the dynamic tab still navigate to `/parceiro/:slug` through the existing `PartnerDetailRoute`. | test | `event detail replaces Line-up with dynamic profile category tabs and cards` | local | passed | Regression remains green in focused screen suite. |
| `DOD-05` | `Definition of Done` | `DOD-05`: No backend/API/module contract files are changed. | code review | `git status --short` | local | passed | Code scope is Flutter UI/shared shell/test plus TODO docs. |
| `VAL-01` | `Validation Steps` | `VAL-01`: Run focused widget test for `ImmersiveEventDetailScreen`. | test | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart test/infrastructure/repositories/schedule_repository_test.dart` | local | passed | 50 tests passed. |
| `VAL-02` | `Validation Steps` | `VAL-02`: Run official Flutter analyzer command: `fvm dart analyze --format machine`. | analyzer | `fvm dart analyze --format machine` | local | passed | Initial run was blocked only by ignored tmp replay clone `foundation_documentation/artifacts/tmp/promotion-stage-replay-clones/front/**`; removing that ignored `front` artifact allowed the official analyzer to pass. |
| `VAL-03` | `Validation Steps` | `VAL-03`: Record any analyzer blocker that is unrelated to this feature separately from feature validation. | review | analyzer output path under `foundation_documentation/artifacts/tmp/promotion-stage-replay-clones/front/**`; later analyzer rerun | local | passed | No analyzer diagnostics were reported in touched Flutter source/test files; final analyzer run passed. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `n/a` | `n/a` | No cross-profile handoff expected. | `n/a` | `n/a` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** consolidated planning review
- **Why this level:** localized front-only rendering/interaction change in one event-detail surface with focused widget-test coverage and no API/data-contract change.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
- **Planned decision promotion targets:** `n/a`; no stable contract change is introduced.
- **Module decision consolidation targets:** `n/a`; existing event-detail linked-profile dynamic-tab contracts are preserved.

## Package-First Assessment
- **Query executed:** `bash ../delphi-ai/tools/query_packages.sh --project-root .. --stack flutter --search event`
- **Relevant packages found:**
  - `[Ecosystem] event_tracker_handler` — analytics package, not relevant to hero layout or tab activation.
- **READMEs read:** `none`; returned package is unrelated to the implementation need.
- **Decision:** local Flutter implementation.
- **Tier:** host app.
- **Rationale:** behavior is presentation-specific and uses existing event detail widgets/controllers/routes.

## Module Decision Baseline Snapshot
| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `events_module#EVS-UI-01` | Event detail consumes linked account profiles as grouped dynamic category tabs. | Preserve | `foundation_documentation/modules/events_module.md` §4/§5.1 |
| `flutter_client_experience_module#Immersive Event Detail Profile Category Contract` | Dynamic profile tabs render between `Sobre` and `Como Chegar`, with linked-profile cards preserving direct profile navigation. | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` §2.1 |
| `account_profile_catalog_module#PCO-08` | Public account-profile detail navigation remains route-driven by slug. | Preserve | `foundation_documentation/modules/account_profile_catalog_module.md` §7 |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01`: This is a front-only Flutter presentation change; no backend/API contract changes are allowed.
- [x] `D-02`: When linked-profile count is `<= 3`, the hero keeps rendering each linked-profile chip.
- [x] `D-03`: When linked-profile count is `> 3`, the hero renders only the first linked-profile chip and `e mais X`.
- [x] `D-04`: The hero chip and `e mais X` activate the first linked profile's dynamic tab determined by `profile_type`.
- [x] `D-05`: Linked-profile cards inside the dynamic tab preserve direct `/parceiro/:slug` navigation.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Event detail already has all linked profiles needed for the hero and dynamic tabs. | `EventModel.linkedAccountProfiles`; `events_module#EVS-UI-01` | Backend work would be required, violating front-only scope. | High | Keep as Assumption |
| `A-02` | Activating the dynamic tab is an in-page scroll/tab change, not navigation to a new route. | User request says "aba"; existing `ImmersiveDetailScreen` tab model | A route change would alter navigation contracts. | High | Keep as Assumption |
| `A-03` | The first visible non-venue linked profile is the first dynamic profile type authority for the hero action. | Existing hero excludes venue and preserves linked profile order. | The hero could jump to an unintended tab. | High | Promote to Decision (`D-04`) |

## Execution Plan
### Touched Surfaces
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/immersive_hero.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`

### Ordered Steps
1. Add hero compaction rendering for more than three linked profiles.
2. Add a small host-controlled tab activation hook to the shared immersive detail shell.
3. Wire event-detail hero taps to the first linked profile's dynamic profile-type tab.
4. Add focused widget coverage for compaction and tab activation.
5. Run focused widget test and official analyzer.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** a local implementation draft already exists due the pre-TODO process lapse; validation must now prove and reconcile it under this TODO.
- **Fail-first target(s):** `event hero compacts many linked profiles and opens first profile type tab`.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Event hero compacted linked profiles | visible UI | shared-android-web | widget test now; runtime smoke before Production-Ready or explicit waiver | no | no | focused widget test | Data is already in-memory route model; no mutation or backend behavior. |
| Hero tap activates dynamic tab | interactive UI | shared-android-web | widget test now; runtime smoke before Production-Ready or explicit waiver | no | no | focused widget test | In-page tab activation uses existing tab shell. |

### Runtime / Rollout Notes
- No migration, feature flag, API deployment, or backend rollout.
- Official analyzer currently has an unrelated tmp-clone blocker under `foundation_documentation/artifacts/tmp/promotion-stage-replay-clones/front/**`; feature delivery evidence must record that separately if not cleaned before rerun.

## Plan Review Gate
- **Architecture:** localized UI orchestration; controller/repository boundaries preserved.
- **Code Quality:** small shared tab-activation hook is generic and avoids route hacks.
- **Tests:** focused widget test covers compact rendering and in-page tab activation; existing card navigation test remains relevant.
- **Performance:** less hero chip rendering in high-profile events; no new async or backend calls.
- **Security:** no auth, data, endpoint, persistence, or trust-boundary changes.
- **Elegance:** preserves dynamic profile-type tab model instead of hardcoding artist tabs.
- **Structural Soundness:** no new scope/subscope or navigation route.

### Failure Modes & Edge Cases
- [ ] Duplicate linked profile IDs should still be deduped by existing hero logic.
- [ ] Venue-linked profile should remain excluded from hero counterpart chips.
- [ ] Missing/empty `profile_type` should not attempt tab activation.
- [ ] Repeated taps on the same chip should replay the tab activation request without route mutation.

### Residual Unknowns / Risks
- [ ] Final Production-Ready flow evidence may need ADB or Playwright runtime smoke, unless explicitly waived for this small front-only UI change.

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `flutter-architecture-adherence` | Flutter presentation and widget state touched. | Screens pure UI; route/navigation via AutoRoute; controller boundaries. | Repository/service resolution from UI; controller-owned navigation. | Keep behavior in widgets/screen and existing tab shell. |
| `rule-flutter-flutter-screen-workflow-glob` | Event detail screen under `presentation/**/screens/**` touched. | Canonical `tenant_public` ownership and pure UI. | Undefined subscopes or DTO ingress. | No scope changes; screen remains UI-only. |
| `frontend-race-condition-validation` | User interaction can be repeated quickly. | Duplicate taps remain idempotent tab activation. | Async navigation/dispose races. | No async side effect is introduced for hero taps. |

## Decision Adherence Validation
| Decision ID | Status | Evidence |
| --- | --- | --- |
| `D-01` | passed | `git status --short` shows Flutter UI/shared shell/test + TODO docs only for this feature. |
| `D-02` | passed | `event hero keeps three linked profiles expanded and opens first profile type tab`. |
| `D-03` | passed | `event hero compacts many linked profiles and opens first profile type tab`. |
| `D-04` | passed | Focused widget tests verify tab activation by `profile_type`. |
| `D-05` | passed | Existing dynamic profile-card navigation test remains green. |

## Security Risk Assessment
- **Risk level:** `none`
- **Attack surface in scope:** none; front-only rendering and local in-page tab activation.
- **Attack simulation decision:** `not_needed`

## Performance & Concurrency Risk Assessment (`pcv-1`)
- **Sensitivity level:** `low`

| Lane | Trigger Result | trigger_reason_code | gate_deadline | min_evidence_rule_id | state | residual_risk | uncertainty_reason_code | recorded_at_utc | executor_id | Evidence |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `not_needed` | `front_only_no_endpoint` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | `2026-04-26T00:00:00Z` | `delphi` | `n/a` |
| `FRC` | `recommended` | `repeated_tap_interaction` | `before_delivery` | `pcv-1` | `passed` | `low` | `none` | `2026-04-26T00:00:00Z` | `delphi` | focused widget tests passed |
| `BCI` | `not_needed` | `no_backend_mutation` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | `2026-04-26T00:00:00Z` | `delphi` | `n/a` |
| `RLS` | `not_needed` | `no_runtime_infra_change` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | `2026-04-26T00:00:00Z` | `delphi` | `n/a` |

## Questions To Close
- [x] Approval gate: user accepted the solution proposal and authorized work.
