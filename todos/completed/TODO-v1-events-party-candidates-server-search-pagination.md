# TODO (V1): Events AccountProfile Candidates Server Search + Pagination

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Flutter + Backend
**Objective:** Replace the fixed-cap event-candidate preload with backend-owned paginated `account_profile_candidates` search for event artist/physical-host selection in Tenant Admin, using standard admin pagination contracts and keeping tenant-admin/account route parity.
**Current delivery stage:** `Production-Ready`
**Qualifiers:** `Validated-Local`
**Next exact step:** None. This TODO is archived under `foundation_documentation/todos/completed/`.
**Closure Notes:** The remaining objective-coverage gap is now closed: Laravel proves large-catalog pagination on both tenant-admin and account-scoped routes, Flutter repository/controller/widget tests cover transport and state semantics, and a real-device integration run proved the Tenant Admin event-form artist picker can search/paginate/select beyond the former first-100 snapshot window.

---

## Scope
- Redefine the Events account-profile-candidates read contract so candidate discovery is page-based and server-driven.
- Remove the Flutter dependency on a single fixed preload snapshot for artist selection.
- Ensure artist search in the event form executes against backend query parameters, not only against in-memory results.
- Keep admin (`/admin/api/v1/events/account_profile_candidates`) and account-scoped (`/api/v1/accounts/{account_slug}/events/account_profile_candidates`) behavior aligned.
- Preserve current candidate payload item shape (`id`, `account_id`, `profile_type`, `display_name`, `slug`, `avatar_url`, `cover_url`, location when applicable) unless a canonical doc decision explicitly requires more.
- Update authoritative documentation before implementation:
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/screens/modulo_tenant_admin.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

## Out of Scope
- Event create/update payload changes (`artist_ids`, `place_ref`, `occurrences` stay as-is).
- Public agenda/discovery search behavior.
- New subscope or route-scope ownership changes.
- Atlas Search adoption or unrelated search-program refactors outside the account-profile-candidates flow.

## Reproduction / Evidence
- Current Flutter repository hardcoded `limit=100` for the candidate endpoint, which capped candidate snapshots before any picker search happened.
- Current Flutter controller loaded account-profile candidates once during form dependency bootstrap and did not pass `search`.
- Current artist picker search filters the already-loaded list locally in memory.
- Current Laravel endpoint clamps `limit` to `100` and orders by `display_name`, so large artist catalogs truncate alphabetically.
- Current Laravel candidate search uses `display_name like ... OR slug like ...`; it does not currently route through a dedicated paginated query service.

## Canonical Module Anchors
- **Primary:** `foundation_documentation/modules/events_module.md`
- **Secondary:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/partner_catalog_and_offer_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/screens/modulo_tenant_admin.md`
- **Promotion targets:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/screens/modulo_tenant_admin.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

## Applicable Rules / Workflows
- `delphi-ai/skills/wf-laravel-create-api-endpoint-method/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-architecture-always-on/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-foundation-docs-sync-model-decision/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`

## Complexity
- `medium`
- Checkpoint policy: one review checkpoint before implementation, one delivery checkpoint after validation.

## COMMENT Resolution Gate
- [x] ✅ Production‑Ready No unresolved `COMMENT:` / `COMENTÁRIO:` blocks remain in this TODO.

## Mandatory Question Gate
1. Existing tests do **not** cover the real failure chain for catalogs larger than 100 candidates.
2. Current code inspection shows the failure is caused before UI rendering completes: backend result cap + one-shot Flutter preload + local-only search.
3. Existing tests that should have caught the bug did not fail because they only assert endpoint usage, local picker behavior with one item, and one-shot bootstrap calls.
4. New tests are required in Laravel and Flutter before implementation.
5. Analyzer-prevention assessment: `no-rule-needed` for the bug itself; this is a runtime contract/capacity mismatch, not a statically recognizable architecture violation.

## Coverage Matrix (Before Fix)
| Stage | Status | Evidence | Notes |
|---|---|---|---|
| API contract | `missing` | current docs do not define paginated `account_profile_candidates` behavior | Contract needs canonical documentation update before code. |
| Backend query path | `covered` | current endpoint/controller/resolver are test-covered for basic search/auth | Coverage does not include `>100` candidate catalogs or paginator semantics. |
| Flutter repository | `false-green` | tests assert `limit=100` as expected behavior | Current test suite codifies the faulty cap. |
| Flutter controller | `false-green` | tests assert one-shot bootstrap call only | No remote search/page reset/next-page coverage. |
| Flutter UI | `missing` | widget tests use a one-item artist dataset only | No scroll pagination or remote search assertions. |

## Coverage Matrix (Current Audit 2026-04-02 UTC)
| Stage | Status | Evidence | Notes |
|---|---|---|---|
| API contract | `implemented` | `foundation_documentation/endpoints_mvp_contracts.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/system_roadmap.md` | Core paginated typed contract is documented canonically. |
| Backend query path | `strong` | `laravel-app/tests/Feature/Events/EventCrudControllerTest.php` | Tenant-admin route proves `>100` artist pagination, capability filtering, auth, and basic account-scoped auth boundary. |
| Backend account-scoped parity | `covered` | `laravel-app/tests/Feature/Events/EventCrudControllerTest.php` | Account-scoped route now proves `102` seeded artists with paginated search on the terminal slice. |
| Flutter repository | `covered` | `flutter-app/test/infrastructure/repositories/tenant_admin_events_repository_test.dart` | Transport path, query params, auth, decode, and explicit `current_page/last_page -> hasMore` derivation are covered. |
| Flutter controller | `covered` | `flutter-app/test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart` | Fake-based tests cover remote search, next-page append, and reset-on-query-change. |
| Flutter UI | `covered` | `flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` | Widget coverage proves backend search intent and picker behavior; real pagination/selection proof is closed by the integration test below. |
| Flutter compatibility | `covered` | `flutter-app/integration_test/feature_admin_event_artist_picker_search_pagination_test.dart` | Real-device integration run on `192.168.15.5:5555` proved search + page append + selection against seeded large data. |

## Plan Review Gate (Medium)
### Issue Card `PRG-01` Mixed Payload Cannot Express Standard Pagination Cleanly
- Severity: `high`
- Evidence: current endpoint returns `{ physical_hosts: [...], artists: [...] }` while backend admin list convention is `data + current_page + last_page + per_page + total`.
- Why now: page-based search must become canonical before Flutter can stop relying on the fixed preload snapshot.
- Options:
  - `A` Keep the mixed payload and add nested paginators per key.
    - Effort: medium; Risk: medium; Blast radius: medium; Maintenance: high.
  - `B` Convert the endpoint to typed paginated queries, using one candidate list per request with standard admin paginator fields. `(Recommended)`
    - Effort: medium; Risk: low; Blast radius: medium; Maintenance: low.
  - `C` Raise/remove the current cap and keep local-only Flutter search.
    - Effort: low; Risk: high; Blast radius: low; Maintenance: high.
- Recommendation: `B`.

### Issue Card `PRG-02` Search Must Be Backend-Owned and Keep Current `like` Semantics
- Severity: `high`
- Evidence: current resolver uses `like` on `display_name`/`slug` and then `limit(100)`; the user decision for this slice is to preserve `like` semantics instead of reintroducing text-index search.
- Why now: moving to remote search without preserving current matching behavior would fix truncation while regressing search expectations.
- Options:
  - `A` Keep the existing `like` matching and only paginate.
    - Effort: low; Risk: medium; Blast radius: medium; Maintenance: medium.
  - `B` Change semantics to Mongo text search.
    - Effort: medium; Risk: high; Blast radius: medium; Maintenance: medium.
- Recommendation: `A`.

### Issue Card `PRG-03` Flutter Picker Must Stop Treating Search as Local State
- Severity: `high`
- Evidence: current picker filters only the already-loaded list in memory.
- Why now: user-facing failure persists even when the user types a search term.
- Options:
  - `A` Keep bootstrap preload and trigger backend search only after the first local miss.
    - Effort: medium; Risk: medium; Blast radius: low; Maintenance: medium.
  - `B` Make candidate discovery fully server-driven in the picker with debounced search + next-page loading. `(Recommended)`
    - Effort: medium; Risk: low; Blast radius: medium; Maintenance: low.
  - `C` Keep local search and preload a much larger list.
    - Effort: low; Risk: high; Blast radius: low; Maintenance: high.
- Recommendation: `B`.

## Failure Modes & Edge Cases
- Search term change must reset pagination to page 1 and discard stale in-flight results safely.
- Selected artists must remain visible/removable even when the active search page no longer contains them.
- Host physical selection must not regress while artists move to paginated search.
- Account-scoped route must mirror tenant-admin route semantics (auth, pagination, search, payload shape).
- Empty search and empty result states must remain deterministic.
- Backend total/current_page/last_page metadata must stay consistent when filters narrow the result set.

## Uncertainty Register
- Assumptions:
  - The correct long-term contract is page-based backend search, not an unbounded preload.
  - The mixed current payload shape should be superseded for candidate search requests.
- Unknowns:
  - Whether `account_profiles` already has the exact text/index coverage needed for this query path in every tenant DB.
  - Whether physical-host datasets are large enough to warrant immediate paginated search UX in the same slice or only route-contract parity.
- Confidence: `0.86`

## Test Strategy
- Strategy: `test-first`
- Intent: `compatibility` for the event-form artist picker journey, plus `unit-regression` for repository/controller semantics already covered.
- Fail-first targets:
  - Laravel: add an account-scoped feature test that seeds `102` artist `Account + AccountProfile` pairs and proves `/api/v1/accounts/{account_slug}/events/account_profile_candidates?type=artist&page=6&page_size=20&search=zulu` returns `total=102` and the terminal slice.
  - Flutter: add a real-backend integration test that seeds a large artist catalog, opens the Tenant Admin event form, proves search can locate an artist beyond the legacy first-100 snapshot window, and proves the picker can append additional pages when scrolling.
- Exclusions (for this slice only):
  - No new production search semantics.
  - No broader Discovery search migration.
  - No web-app authored browser tests for this flow.

## Module Decision Baseline Snapshot
- `M-01`: `foundation_documentation/endpoints_mvp_contracts.md` says all lists are page-based.
- `M-02`: `foundation_documentation/modules/events_module.md` says event-form account-profile candidates are capability-driven for physical hosts.
- `M-03`: `foundation_documentation/modules/events_module.md` says runtime query services must not create indexes; migrations provision indexes.
- `M-04`: `foundation_documentation/modules/tenant_admin_module.md` standard admin list contracts use paginator-style metadata.
- `M-05`: `foundation_documentation/modules/flutter_client_experience_module.md` and `foundation_documentation/screens/modulo_tenant_admin.md` keep controllers as state/async owners and screens/widgets as UI.

## Decision Baseline (Frozen)
- `D-01`: Event account-profile candidate discovery for the event form must be backend-owned and page-based; Flutter must not depend on a fixed preload cap.
- `D-02`: Candidate list responses must follow standard admin paginator metadata (`data`, `current_page`, `last_page`, `per_page`, `total`).
- `D-03`: Account-profile candidate search requests must be typed (`artist` vs `physical_host`) so each request returns exactly one paginated candidate list.
- `D-04`: Search semantics are backend-owned on canonical candidate fields (`display_name`, `slug`) using the existing `like` behavior; this slice does not reintroduce text-index search.
- `D-05`: Flutter event-form candidate UX must use debounced server search + next-page loading for artist selection.
- `D-06`: Existing selected artists/host references must remain stable in form state even when the active search page changes.
- `D-07`: Tenant-admin and account-scoped candidate endpoints must preserve equivalent behavior and payload semantics.
- `D-08`: Validation for this slice requires contract/doc updates first, then Laravel feature coverage, Flutter repository/controller/widget coverage, and Flutter analyzer on the final change set.

## Module Coherence Gate (Pre-Implementation)
| Decision ID | Module Coherence | Change Intent | Evidence |
|---|---|---|---|
| `D-01` | `Aligned` | `Preserve` | `foundation_documentation/endpoints_mvp_contracts.md` pagination convention |
| `D-02` | `Aligned` | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` admin list response shape |
| `D-03` | `Aligned` | `Preserve` | current docs do not freeze a mixed non-paginated candidate contract; typed page-based requests fit the canonical list convention |
| `D-04` | `Aligned` | `Preserve` | `foundation_documentation/modules/events_module.md` (`EVS-OPS-01`) |
| `D-05` | `Aligned` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1.1`; `foundation_documentation/screens/modulo_tenant_admin.md` screen/controller separation |
| `D-06` | `Aligned` | `Preserve` | current event form contract already keeps selected artist IDs in controller-owned form state |
| `D-07` | `Aligned` | `Preserve` | `foundation_documentation/policies/scope_subscope_governance.md` + tenant/account route ownership in module docs |
| `D-08` | `Aligned` | `Preserve` | Flutter/Laravel rule/workflow gates loaded for this slice |

## Module Decision Consistency Matrix (Planned 1-1)
- `M-01` -> `Preserve` by `D-01`/`D-02`.
- `M-02` -> `Preserve` by `D-03`.
- `M-03` -> `Preserve` by `D-04`.
- `M-04` -> `Preserve` by `D-02`/`D-07`.
- `M-05` -> `Preserve` by `D-05`/`D-06`.

## Questions to Close
- [x] ✅ Production‑Ready `Q-01` Should this remain a one-shot preload flow or move to server-driven page-based search?
  - Decision: move to server-driven page-based search.
- [x] ✅ Production‑Ready `Q-02` Should the backend keep a mixed `artists + physical_hosts` payload or adopt standard paginated list semantics?
  - Decision: adopt standard paginated list semantics per candidate type.
- [x] ✅ Production‑Ready `Q-03` Should Flutter search stay local with a larger cap or use backend search + pagination?
  - Decision: backend search + pagination.

## Implementation Plan
- [x] ✅ Production‑Ready Document the paginated candidate-search contract and core promotion targets before code.
- [x] ✅ Production‑Ready Refactor Laravel candidate-query contract/controller/request layer to page-based typed queries.
- [x] ✅ Production‑Ready Add/adjust Laravel query path and required `like`-based search behavior for candidate search fields.
- [x] ✅ Production‑Ready Replace Flutter one-shot candidate snapshot flow with server-driven artist search pagination and stable selected-item state.
- [x] ✅ Production‑Ready Decide and implement physical-host parity path so host selection does not regress under the new contract.
- [x] ✅ Production‑Ready Add the missing Laravel account-scoped parity test for typed candidate pagination/search on large artist catalogs.
- [x] ✅ Production‑Ready Add a Flutter real-backend integration test for the event-form artist picker covering remote search, page append, and selection against seeded large data.
- [x] ✅ Production‑Ready Strengthen Flutter repository adapter coverage for explicit multi-page paginator metadata.
- [x] ✅ Production‑Ready Run validation suites and record Decision Adherence / Module Consistency evidence before delivery.

## Definition of Done
- Account-profile candidate discovery for event forms no longer depends on `limit=100` snapshots.
- Typing a search term in the artist picker performs backend search and can reach candidates beyond the former first 100 items.
- Candidate route contracts are page-based and documented canonically.
- Admin and account-scoped candidate routes are behaviorally aligned.
- Automated coverage exists for the failure chain that was previously false-green.

## Validation Steps
- Laravel targeted feature tests for Events candidate routes.
- Flutter targeted tests:
  - repository
  - controller
  - event form widget
- Flutter integration test against real local backend for the Tenant Admin event-form artist picker.
- Flutter analyzer: `fvm dart analyze --format machine`
- Additional runtime/manual verification:
  - reproduce search for an artist beyond the first legacy snapshot window,
  - confirm next-page loading and selection/removal behavior.

## Validation Evidence (2026-04-01 / 2026-04-02 UTC)
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter account_profile_candidates`
  - Result: `PASS` (`7` tests, `32` assertions).
- `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
  - Result: `PASS` (`35` tests).
- `bash /home/elton/.codex/skills/flutter-device-test-runner/scripts/device_single_test_resilient.sh _run /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app 192.168.15.5:5555 com.guarappari.app guarappari config/defines/integration.tenant.json integration_test/feature_admin_event_artist_picker_search_pagination_test.dart 1200`
  - Device: `moto e13` (`192.168.15.5:5555`)
  - Result: `PASS` via `lane=drive-fallback`, duration `728s`
  - Note: the first `flutter test` lane hit the known harness defect `streamListen ... VmServiceProxyGoldenFileComparator`; the fallback `flutter drive` lane executed the real flow and passed.
- `fvm dart analyze --format machine`
  - Result: `PASS` (exit `0`, no findings emitted).

## Decision Adherence Validation
| Decision ID | Result | Evidence |
|---|---|---|
| `D-01` | `Pass` | Candidate discovery is no longer snapshot-capped; controller/picker use backend-owned page fetches and the integration test reaches artists beyond the former preload window. |
| `D-02` | `Pass` | Backend and Flutter repository now consume paginator metadata (`data`, `current_page`, `last_page`, `per_page`, `total`). |
| `D-03` | `Pass` | Requests are typed by `artist` / `physical_host` and return one candidate list per query. |
| `D-04` | `Pass` | Search remains backend-owned with existing `like` semantics; no text-index migration was introduced in this slice. |
| `D-05` | `Pass` | Flutter artist selection uses debounced remote search + next-page loading, covered by controller/widget/integration tests. |
| `D-06` | `Pass` | Selected items remain stable in form state; widget/integration coverage confirms selection survives search-page changes. |
| `D-07` | `Pass` | Tenant-admin and account-scoped routes are covered with aligned contract semantics and auth expectations. |
| `D-08` | `Pass` | Canonical docs, Laravel feature coverage, Flutter repository/controller/widget coverage, integration evidence, and analyzer were all executed. |

## Module Decision Consistency Validation
| Module Baseline | Result | Evidence |
|---|---|---|
| `M-01` | `Preserved` | Contract now follows the documented page-based list convention. |
| `M-02` | `Preserved` | Physical-host capability routing remains intact under the typed candidate endpoint. |
| `M-03` | `Preserved` | Search behavior stayed in query/application code; this slice did not introduce ad-hoc runtime index changes. |
| `M-04` | `Preserved` | Admin/account candidate responses now align with standard paginator metadata. |
| `M-05` | `Preserved` | Controller owns async/search state; screen/widget remain UI-driven, consistent with Flutter architecture guidance. |
