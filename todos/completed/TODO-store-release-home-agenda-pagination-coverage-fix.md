# TODO: Home Agenda Future Event Pagination Coverage Fix

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production Home does not show the public event detail URL:
`https://guarappari.com.br/agenda/evento/festa-da-imigracao-italiana?occurrence=69dd8398d698348015047b62&tab=programming`

Read-only evidence shows the event detail exists and the agenda endpoint returns the occurrence when Home-equivalent filters use the tenant default origin with a 50 km radius. The user confirmed the event still does not appear in the app after changing the Home radius to 50 km.

The front-end defect is pagination consumption depth: Home must treat the backend agenda response as an opaque batch and continue requesting subsequent `page` values while the repository-owned page state says more data exists. Flutter must not force `page_size` to make a hidden item appear; the public agenda backend may return default batches of 10, 15, 25, or another server-owned size, and the client must advance by `page` rather than inferring a fixed batch size.

This TODO records a front-end pagination/default-batch fix while preserving backend-authoritative geo filtering and backend-owned default page sizing.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** the reported symptom is bounded to tenant-public Home agenda pagination for future events with broader Home radius.
- **Direct-to-TODO rationale:** this is a production symptom investigation with concrete API evidence; no separate feature brief is needed.

## Contract Boundary
- This TODO defines the diagnostic truth and the front-end pagination/transport fix candidate.
- Any implementation must update this TODO first and receive `APROVADO`.
- Backend endpoint behavior is out of scope for this TODO; the endpoint already accepts `page_size` and returns the reported event.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Completed`, `Repository-Promoted`, `User-Confirmed`
- **Next exact step:** Archived to `completed/` after repository evidence showed the `event hero and agenda pagination` work promoted through `dev`, `stage`, and `main`; no active Store Release follow-up remains for this slice.

## Completion Reclassification Note
- 2026-04-28: User confirmed this TODO is delivered. Flutter repository evidence shows `feature/store-release-event-hero-home-agenda-pagination` merged to `dev`, promoted to `stage`, and promoted to `main` under the `event hero and agenda pagination` flow.
- The stale active-lane `Next exact step` still referenced opening a PR, and the local evidence matrix still reflects pre-promotion guard wording. Repository promotion is now the authoritative closure signal for this reclassification.

## Scope
- [x] Confirm whether the event exists in production detail API.
- [x] Confirm whether the event appears in production agenda pagination with broad radius.
- [x] Confirm whether the event appears with the tenant configured default Home radius.
- [x] Confirm the user's report still reproduces conceptually with radius 50 because the event is deep in default API pagination.
- [x] Keep `LaravelScheduleBackend.fetchEventsPage` on backend default batches unless a caller explicitly opts into `page_size`.
- [x] Add/update focused tests for public agenda transport default-batch behavior and Home repository pagination semantics.

## Approval Notes
- **Approval:** user accepted the solution proposal and authorized work on this item plus the event Hero item.
- **Why this required approval:** changing transport/query semantics reverses a previously proposed `page_size` override and reestablishes backend default batches as the correct public agenda behavior.
- **What approval unlocked:** implementation of the front-end pagination/transport fix.
- **Owner / source:** product owner / user request.
- **Last confirmed truth:** on 2026-04-26, the reported production event appears through default agenda pagination when enough pages are consumed. Local seeded evidence with 30 future events confirms the default first page returns 10 items without the target, while default page 2 returns the target.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:feature/store-release-event-hero-home-agenda-pagination @ b6568520`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Home agenda pagination coverage fix | `flutter-app:feature/store-release-event-hero-home-agenda-pagination @ b6568520` | `<pending>` | `<pending>` | `<pending>` | `Local-Implemented; focused tests and analyzer passed; rebuilt via build_web.sh for Belluga.space local tunnel` |

## Out of Scope
- [ ] Backend endpoint or database changes.
- [ ] Forcing `page_size` from Flutter for Home agenda visibility.
- [ ] Local client-side distance filtering.
- [ ] Event detail route changes.
- [ ] Parsing changes unless new evidence shows DTO loss after the API response.
- [ ] Changing default Home radius behavior.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** Flutter public agenda default-batch behavior, Home agenda controller/repository tests, and related test expectation updates.
- **Must update or split the TODO:** backend agenda contract changes, tenant settings admin changes, global radius behavior changes, or a new all-events Home aggregation behavior.

## Definition of Done
- [x] `DOD-01`: `LaravelScheduleBackend.fetchEventsPage` omits `page_size` by default so public agenda uses backend-owned batch sizing.
- [x] `DOD-02`: Existing search behavior still omits geo params when searching and does not regress.
- [x] `DOD-03`: Focused tests prove the public agenda transport does not send `page_size` unless explicitly requested.
- [x] `DOD-04`: No local radius filtering is introduced in Flutter.
- [x] `DOD-05`: Home repository pagination remains page/`has_more` driven and does not rely on a fixed batch size.

## Validation Steps
- [x] `VAL-01`: Probe production `/api/v1/environment` for tenant radius settings.
- [x] `VAL-02`: Probe production event detail endpoint with anonymous tenant-public token.
- [x] `VAL-03`: Probe production agenda pagination with default origin and `max_distance_meters=50000`.
- [x] `VAL-04`: Probe production agenda pagination with default origin and `max_distance_meters=5000`.
- [x] `VAL-05`: Probe production agenda pagination depth and local seeded default-batch pagination.
- [x] `VAL-06`: Run focused Laravel schedule backend tests.
- [x] `VAL-07`: Run focused ScheduleRepository/Home agenda tests as needed.
- [x] `VAL-08`: Run official analyzer.
- [x] `VAL-09`: Rebuild Flutter web bundle with `build_web.sh` for the Belluga.space local tunnel.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `VAL-01` | `Validation Steps` | `VAL-01`: Probe production `/api/v1/environment` for tenant radius settings. | API probe | `GET https://guarappari.com.br/api/v1/environment` | production | passed | `settings.map_ui.radius.default_km=5`, `max_km=50`, default origin `-20.666999,-40.500119`. |
| `VAL-02` | `Validation Steps` | `VAL-02`: Probe production event detail endpoint with anonymous tenant-public token. | API probe | `GET /api/v1/events/festa-da-imigracao-italiana?occurrence=69dd8398d698348015047b62` with anonymous token | production | passed | Returned 200 with title `5 ª Festa da Imigração Italiana`. |
| `VAL-03` | `Validation Steps` | `VAL-03`: Probe production agenda pagination with default origin and `max_distance_meters=50000`. | API probe | `/api/v1/agenda?page=1..6&past_only=0&confirmed_only=0&origin_lat=-20.666999&origin_lng=-40.500119&max_distance_meters=50000` | production | passed | Target event found on page 5, index 1; two additional occurrences found on same page. |
| `VAL-04` | `Validation Steps` | `VAL-04`: Probe production agenda pagination with default origin and `max_distance_meters=5000`. | API probe | `/api/v1/agenda?page=1..5&past_only=0&confirmed_only=0&origin_lat=-20.666999&origin_lng=-40.500119&max_distance_meters=5000` | production | passed | API ended on page 5 with `has_more=false`; target event not found. |
| `VAL-05` | `Validation Steps` | `VAL-05`: Probe production agenda pagination depth and local seeded default-batch pagination. | API probe | `/api/v1/agenda?page=1..N` without `page_size` | production + local-public tunnel | passed | Local tunnel default pages: page 1 returned 10 items and no target; page 2 returned 10 items and `teste-home-pagination-alvo-15`; page 3 returned 10 items. |
| `DOD-01` | `Definition of Done` | `DOD-01`: `LaravelScheduleBackend.fetchEventsPage` omits `page_size` by default. | test | `fetchEventsPage omits page_size by default for public agenda batches` | local | passed | Transport now preserves backend-owned default batch sizing for public agenda reads. |
| `DOD-02` | `Definition of Done` | `DOD-02`: Existing search behavior still omits geo params when searching and does not regress. | test | `fetchEventsPage forwards search and omits geo params when searching` | local | passed | Search requests still omit `page_size`, `origin_lat`, `origin_lng`, and `max_distance_meters`. |
| `DOD-03` | `Definition of Done` | `DOD-03`: Focused tests prove the public agenda transport uses default batches. | test | `test/infrastructure/dal/laravel_schedule_backend_test.dart` | local | passed | DAL suite passed in the combined focused run. |
| `DOD-04` | `Definition of Done` | `DOD-04`: No local radius filtering is introduced in Flutter. | architecture review | `agenda_and_action_planner_module.md#AGD-05` and current code inspection | local | passed | Implementation only changes query serialization; geo filtering remains backend-authoritative. |
| `DOD-05` | `Definition of Done` | `DOD-05`: Home repository pagination remains page/`has_more` driven and does not rely on a fixed batch size. | test/docs review | `test/infrastructure/repositories/schedule_repository_test.dart` | local | passed | Repository test asserts Home requests page 1 then page 2 while leaving `pageSize` null. |
| `VAL-06` | `Validation Steps` | `VAL-06`: Run focused Laravel schedule backend tests. | test | `fvm flutter test test/infrastructure/dal/laravel_schedule_backend_test.dart test/infrastructure/repositories/schedule_repository_test.dart` | local | passed | DAL transport and repository pagination semantics passed together. |
| `VAL-07` | `Validation Steps` | `VAL-07`: Run focused ScheduleRepository/Home agenda tests as needed. | test | `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`; plus origin/search/controller contract files | local | passed | Home agenda controller, origin flow, search controller, and auxiliary contract fake tests passed with optional `pageSize`. |
| `VAL-08` | `Validation Steps` | `VAL-08`: Run official analyzer. | analyzer | `fvm dart analyze --format machine` | local | passed | Initial run was blocked only by ignored tmp replay clone `foundation_documentation/artifacts/tmp/promotion-stage-replay-clones/front/**`; removing that ignored `front` artifact allowed the official analyzer to pass. |
| `VAL-09` | `Validation Steps` | `VAL-09`: Rebuild Flutter web bundle with `build_web.sh` for the Belluga.space local tunnel. | build/deploy | `bash scripts/build_web.sh ../web-app dev --clean-output` | local web tunnel | passed | Rebuilt `../web-app`; `https://belluga.space/index.html` serves `window.__LANDLORD_HOST__ = "belluga.space"` and `window.__WEB_BUILD_SHA__ = "21d80015"`. |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| Guarappari production API | Reported symptom is production-only. | healthy | 2026-04-26 | anonymous tenant-public token + agenda/detail/default-pagination probes | Do not expose the diagnostic token; regenerate if future probes are needed. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `n/a` | `n/a` | No cross-profile handoff yet. | `n/a` | `n/a` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** consolidated planning review after approval
- **Why this level:** likely one public agenda transport query change plus focused transport/repository tests.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Planned decision promotion targets (module sections):**
  - No stable module decision expected if implementation only restores backend default-batch consumption for an existing paginated contract.
- **Module decision consolidation targets (required):**
  - Preserve existing radius and pagination ownership decisions.

## Package-First Assessment
- **Query executed:** `bash delphi-ai/tools/query_packages.sh --project-root .. --stack flutter --search agenda`
- **Query executed:** `bash delphi-ai/tools/query_packages.sh --project-root .. --stack flutter --search schedule`
- **Relevant packages found:** none.
- **READMEs read:** none.
- **Decision:** local Flutter default-batch pagination fix.
- **Tier:** host app.
- **Rationale:** the change is specific to the host app Laravel schedule backend adapter/repository path and keeps Home resilient to backend-owned default batch sizing.

## Decision Pending (Resolve Before Freeze)
- [x] `D-01` Preserve public agenda pagination defaults as API-owned; Flutter Home should omit `page_size` and advance by page state while `has_more` remains true.

## Decisions (Resolved Before Freeze)
- [x] `D-01` Flutter Home does not force `page_size` for public agenda requests. Rationale: the client should not assume the backend default batch size; it should consume page batches until `has_more=false` or the user stops scrolling.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `tenant_home_composer_module#HOM-05` | Radius preference is Home-only. | Preserve | User report confirms radius 50 still misses in app, so this TODO is not changing radius semantics. |
| `tenant_home_composer_module#HOM-07` | Home Agenda aggregate stream is repository-owned and backend-backed. | Preserve | Repository owns pagination state; controller consumes aggregate stream. |
| `agenda_and_action_planner_module#AGD-05` | Local distance/radius filtering is forbidden in agenda/search render paths. | Preserve | No local filtering will be introduced. |
| `agenda_and_action_planner_module#AGD-06` | Radius preference is Home-only. | Preserve | This TODO does not change radius persistence or default radius semantics. |
| `agenda_and_action_planner_module#3.4 Request` | Agenda paged list query supports `page_size`. | Preserve | Flutter Home leaves it omitted by default and relies on page/`has_more`; explicit `page_size` remains an API capability outside this fix. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` `LaravelScheduleBackend.fetchEventsPage` must omit `page_size` unless a caller explicitly provides one, while preserving existing filter/query semantics.

## Questions To Close
- [x] Approval gate: user accepted the solution proposal and authorized work.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The reported event is reachable by agenda pagination when radius is 50 km. | Production API probes with `max_distance_meters=50000`. | Transport/page-size fix would not be sufficient. | High | Promote to Decision |
| `A-02` | Home must not rely on a client-selected agenda batch size to surface future events. | User correction plus local seeded default-pagination evidence. | Need backend default/page contract change, which is out of current front-only scope. | High | Promote to Decision |
| `A-03` | Repository-owned page state plus controller `loadNextPage` is the correct front-only contract for deeper events. | `ScheduleRepository` stores `hasMore`/`nextPage`; Home controller loads next page on scroll when `hasMoreStreamValue=true`. | Need scroll-trigger hardening if UI does not call `loadNextPage` in a specific viewport. | Medium | Keep as Assumption |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart`
- `test/infrastructure/dal/laravel_schedule_backend_test.dart`
- Possibly `test/infrastructure/repositories/schedule_repository_test.dart`

### Ordered Steps
1. Freeze `D-01` after `APROVADO`.
2. Update focused transport tests so `page_size` is omitted by default on public agenda requests.
3. Make `LaravelScheduleBackend.fetchEventsPage` preserve backend default batches unless a caller explicitly opts into `page_size`.
4. Review whether repository/controller tests prove Home advances to the next `page` without fixed batch-size assumptions; add/adjust if needed.
5. Run focused tests and official analyzer.

### Test Strategy
- **Strategy:** `test-first` if implementation proceeds.
- **Why:** existing transport tests currently lock in the wrong behavior; update them before code.
- **Fail-first target(s) (when required):** `fetchEventsPage omits page_size by default for public agenda batches`.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Home future event visibility | pagination/query semantics visible in public Home list | shared-android-web | ADB or Playwright readonly before Production-Ready, unless explicitly waived | no | yes | focused tests plus production-like readonly smoke | No mutation path. |

### Runtime / Rollout Notes
- No migration expected.
- No tenant-specific config change expected.

## Plan Review Gate
- **Architecture:** keep repository-owned pagination and backend-owned geo filtering; transport may serialize repository-owned page size without exposing page envelopes to controllers.
- **Code Quality:** avoid hardcoding Guarappari coordinates or event-specific exceptions.
- **Tests:** prove query/page-size semantics; avoid tests coupled to production data.
- **Performance:** default-batch pagination may use more requests than a larger override, but preserves backend ownership of response sizing and avoids front-end batch assumptions.
- **Security:** no auth or protected data changes.
- **Elegance:** align transport to documented API instead of tenant-specific event exceptions or client-side filtering.
- **Structural Soundness:** do not bypass canonical Home/Agenda module decisions.

### Failure Modes & Edge Cases
- [ ] Persisted user radius should continue to override any default-only behavior.
- [ ] Search queries currently omit geo filters; that behavior should not regress.
- [ ] API default page-size behavior must remain valid for all public agenda clients.
- [ ] If a viewport cannot scroll enough to trigger `loadNextPage`, the next slice is scroll-trigger hardening rather than page-size override.

### Residual Unknowns / Risks
- [ ] Deep events still depend on user/viewport-driven `loadNextPage`; if the UI does not trigger it in a specific layout, a follow-up should harden the scroll threshold.

## Security Risk Assessment
- **Risk level:** `none`
- **Attack surface in scope:** none before implementation; possible future query-only change.
- **Attack simulation decision:** `not_needed`

## Performance & Concurrency Risk Assessment (`pcv-1`)
- **Sensitivity level:** `low`

| Lane | Trigger Result | trigger_reason_code | gate_deadline | min_evidence_rule_id | state | residual_risk | uncertainty_reason_code | recorded_at_utc | executor_id | Evidence |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `recommended` | `query_pagination_depth_changes_request_count` | `before_delivery` | `pcv-1` | `passed` | `low` | `none` | `2026-04-26T00:00:00Z` | `delphi` | production/local API probes plus focused transport/repository/controller tests |
| `FRC` | `not_needed` | `no_async_ui_change_yet` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | `2026-04-26T00:00:00Z` | `delphi` | `n/a` |
| `BCI` | `not_needed` | `no_backend_mutation` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | `2026-04-26T00:00:00Z` | `delphi` | `n/a` |
| `RLS` | `not_needed` | `no_runtime_infra_change` | `before_delivery` | `pcv-1` | `not_applicable` | `none` | `none` | `2026-04-26T00:00:00Z` | `delphi` | `n/a` |
