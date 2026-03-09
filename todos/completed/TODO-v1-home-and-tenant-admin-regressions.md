# TODO-v1 Home And Tenant Admin Regressions

**Status:** Completed (`Validated locally and promoted to stage`)


## Scope
- Restore correct tenant Home agenda rendering in local and stage for the currently documented Events + Tenant Admin contracts.
- Eliminate invalid extra paging behavior in the visible Home agenda flow.
- Restore tenant-admin Events archived filtering.
- Restore tenant-admin Accounts unmanaged filtering.

## Out Of Scope
- Redesigning the long-term Home `my events` contract.
- Redesigning the long-term Home favorites contract.
- Introducing a new aggregated home endpoint.
- Changing canonical route/scope ownership.

## Definition Of Done
- Home agenda renders first-page events when `/api/v1/agenda?page=1&page_size=10...` returns items.
- Home agenda does not fetch next pages unless scroll-end pagination is justified by the paginated contract.
- Tenant-admin archived filter works without validation errors.
- Tenant-admin unmanaged accounts filter returns unmanaged accounts per tenant-admin contract.
- Targeted Flutter/Laravel tests cover the fixed regressions.

## Validation Steps
- Flutter analyze.
- Flutter targeted unit/widget/integration tests for Home agenda and tenant-admin events/settings.
- Laravel targeted feature/unit tests for accounts/events filters when applicable.
- Local web rebuild and manual/automated verification of Home and tenant-admin pages.

## Canonical Module Anchors
- Primary:
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- Secondary:
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- Planned promotion targets:
  - Promote any stable Home/tenant-admin pagination/filter decisions back into the module docs above.

## Complexity
- `medium`

## Checkpoint Policy
- One review checkpoint before approval.

## Questions To Close
- None currently blocking implementation; current work restores documented behavior.

## Execution Notes
- `2026-03-06`: confirmed Home agenda `stage` page-1 API returns items (`200`, `has_more=false`) with saved `map_ui.default_origin`; missing `geoNear` index is not the root cause of the empty Home symptom.
- `2026-03-06`: root cause for empty Home render was Flutter parse failure on optional `artist.avatar_url = null`, which was being forced through `URIValue.parse('')`.
- `2026-03-06`: Home eager pagination was also invalid; widget/controller were loading next pages without user-driven scroll-end.
- `2026-03-06`: tenant-admin archived events filter failed because Flutter serialized `archived=true` instead of the Laravel-compatible query boolean `archived=1`.
- `2026-03-06`: tenant-admin unmanaged accounts filter reproduced locally only when `user_owned` accounts exist in the tenant dataset; fixed in Laravel by aligning `_id` filtering with the existing string-based `whereIn/whereNotIn` pattern.

## Decisions
- `D-01` Options:
  - `A` Keep current Home visible agenda request contract (`page_size=10`, geo-filtered) and fix render/pagination only.
  - `B` Broaden Home initial request contract while fixing render.
  - `C` Introduce aggregated Home query now.
  - Recommended: `A`.
  - Rationale: restores current documented MVP contract without expanding scope.
- `D-02` Options:
  - `A` Home visible agenda paginates only from scroll-end and `has_more` contract.
  - `B` Keep controller auto-pagination fallback.
  - `C` Disable pagination completely.
  - Recommended: `A`.
  - Rationale: matches current UI contract and avoids hidden backend overfetch.
- `D-03` Options:
  - `A` Fix tenant-admin archived filter in client serialization if backend contract already accepts boolean semantics.
  - `B` Relax backend validation to accept broader truthy strings and keep client as-is.
  - `C` Change both sides simultaneously.
  - Recommended: `A` first, escalate to `C` only if needed.
- `D-04` Options:
  - `A` Fix unmanaged ownership filtering in Laravel query/service layer to match `tenant_admin_module.md`.
  - `B` Hide unmanaged from UI.
  - `C` Reinterpret unmanaged semantics.
  - Recommended: `A`.

## Decision Baseline (Frozen)
- `D-01`: `A`
- `D-02`: `A`
- `D-03`: `A`
- `D-04`: `A`

## Module Coherence Gate
| Decision | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | Aligned | Preserve | `tenant_home_composer_module.md` sections `1`, `4`; `events_module.md` section `5.4` |
| `D-02` | Aligned | Preserve | `tenant_home_composer_module.md` MVP client-composed policy; current paginated agenda contract in `events_module.md` |
| `D-03` | Aligned | Preserve | `tenant_admin_module.md` `GET /admin/api/v1/events` expectations + current backend boolean validation |
| `D-04` | Aligned | Preserve | `tenant_admin_module.md` `GET /admin/api/v1/accounts` states tenant-owned + unmanaged visibility |

## Plan Review Gate
### Architecture
- `ISS-ARCH-01` severity: high
  - Evidence: `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
  - Why now: visible Home agenda is not preserving/rendering returned page data reliably.
  - Options:
    - `A` Restore controller to strict paginated contract (recommended)
      - Effort: medium
      - Risk: medium
      - Blast radius: Home agenda/search only
      - Maintenance burden: low
    - `B` Patch widget rendering around the controller
      - Effort: low
      - Risk: high
      - Blast radius: hidden state bugs remain
      - Maintenance burden: high
    - `C` Do nothing
      - Effort: none
      - Risk: unacceptable
      - Blast radius: Home tenant surface remains broken
      - Maintenance burden: hidden support cost
- `ISS-ARCH-02` severity: medium
  - Evidence: duplicate Home agenda consumers (`favorites`, `my events`) documented in separate TODOs
  - Why now: acknowledged but out of scope for this implementation
  - Recommended: track separately only

### Code Quality
- `ISS-CQ-01` severity: medium
  - Evidence: auto-page logic in `TenantHomeAgendaController` diverges from UI scroll-trigger contract.
  - Recommended: remove/disable auto-page in visible Home agenda flow.

### Tests
- `ISS-TST-01` severity: high
  - Evidence: no targeted regression proving Home page-1 results persist/render after successful fetch.
  - Recommended: add targeted Flutter tests; Laravel filter tests where backend fix is required.

### Performance
- `ISS-PERF-01` severity: medium
  - Evidence: unnecessary `page=2` requests and separate duplicate `page_size=25` consumers.
  - Recommended: stop invalid page fetch in visible agenda now; keep structural query redesign in separate TODOs.

### Security
- No new security surface expected; preserve current auth/tenant scoping contracts.

## Failure Modes & Edge Cases
- Page 1 returns items but page 2 empty should not clear visible results.
- User location available vs fallback origin should not alter render stability.
- Archived/admin booleans must survive Flutter query serialization.
- Unmanaged accounts must remain visible only within existing tenant-admin access rules.

## Uncertainty Register
- Assumptions:
  - Current Home agenda request contract (`page_size=10`, geo-filtered) is still the intended MVP visible feed.
  - `tenant_admin_module.md` remains authoritative for unmanaged visibility.
- Unknowns:
  - Whether `has_more` from backend is inaccurate for the visible agenda case.
  - Whether Home render loss is in DTO mapping, controller resets, or multiple init cycles.
- Confidence:
  - medium

## Decision Adherence Validation
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | Adherent | `tenant_home_agenda_controller.dart`, `foundation_documentation/artifacts/tmp/agent-diagnostics/diag_stage_agenda_parse_test.dart`, promoted Flutter stage commit `d7a7d75` | Page-1 results now persist and render correctly. |
| `D-02` | Adherent | `tenant_home_agenda_controller.dart`, `home_agenda_body.dart` | Visible Home agenda no longer auto-pages outside user-driven scroll. |
| `D-03` | Adherent | `tenant_admin_events_repository.dart`, `tenant_admin_events_repository_test.dart` | Archived filter serializes Laravel-compatible booleans. |
| `D-04` | Adherent | `laravel-app/app/Application/Accounts/AccountOwnershipStateService.php`, `laravel-app/tests/Feature/Accounts/AccountControllerTest.php`, promoted Laravel stage commit `0043e3a` | Unmanaged ownership filtering restored in backend query path. |

## Validation Evidence
- Flutter:
  - `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart -r expanded`
  - `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart -r expanded`
  - `fvm flutter test foundation_documentation/artifacts/tmp/agent-diagnostics/diag_stage_agenda_parse_test.dart -r expanded`
  - `fvm flutter analyze lib/domain/artist/artist_resume.dart lib/domain/schedule/friend_resume.dart lib/infrastructure/repositories/invites_repository.dart lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_body.dart lib/infrastructure/repositories/tenant_admin/tenant_admin_events_repository.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
- Laravel:
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Accounts/AccountControllerTest.php --filter=testIndexFiltersByUnmanagedOwnershipState`
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Accounts/AccountControllerTest.php --filter='testUnmanagedAccountWithOperatorIsReturnedAsUserOwned|testIndexFiltersByCurrentUser'`
- Local web:
  - `bash scripts/build_web.sh ../web-app dev --clean-output`

## Decision Adherence Validation (Current)
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | Implemented | Home parse regression now preserves returned page-1 items (`foundation_documentation/artifacts/tmp/agent-diagnostics/diag_stage_agenda_parse_test.dart`) | Root cause was DTO/domain parsing, not backend empty response |
| `D-02` | Implemented | Controller auto-page removed; widget load-next gated to user-driven scroll | Separate long-term Home query redesign still tracked out of scope |
| `D-03` | Adherent | `archived` serialized as `1`; repository regression test added and later promoted to protected lanes | Manual tenant-admin retest completed after promotion. |
| `D-04` | Adherent | Local feature reproduction added and fixed in Laravel `AccountOwnershipStateService`; promoted through protected lanes | Stage retest no longer pending. |

## Completion Note
- `2026-03-07`: The regression bundle was promoted with Flutter commit `d7a7d75dfb5e7e2d11b58d2f91c7206a2ecf0224` and Laravel commit `0043e3aefa0872d4a8f1f88e4622acf9b97dc1c0`, then validated in the subsequent Docker `stage` promotion. Follow-up structural debt for Home `my events` and `favorites` remains intentionally open in separate TODOs.
