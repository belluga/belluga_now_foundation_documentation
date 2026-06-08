# TODO (v0.2.1+9): Home Favorites Event Order and Status Halos

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User product direction on 2026-06-07 identified two coupled issues in the tenant-public Home favorites strip:

1. The row is not honoring the intended event-priority order. Favorited profiles without event context are surfacing before profiles that do have live or upcoming events.
2. The strip needs explicit Instagram-style status halos with two distinct product meanings:
   - `TOCANDO AGORA`
   - `TEM EVENTO`

Current repo evidence shows that the backend favorites contract already exposes snapshot event metadata (`live_now_event_occurrence_*`, `next_event_occurrence_*`, `last_event_occurrence_at`) and that Flutter currently preserves backend ordering end-to-end. It also shows the current halo implementation is technically present (`liveNow|upcoming`) but still acts as an implementation detail rather than a product-frozen visual contract. This TODO exists to establish the v0.2.1+9 canonical behavior for both ordering and status signaling before implementation begins.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-1-plus9-home-favorites-order-and-halos`
- **Why this is the right current slice:** this is one bounded tenant-public Home slice with one primary product outcome: make favorites rank by event-time relevance and communicate event state through a stable two-halo visual language.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the request is already concrete, user-visible, and confined to one Home favorites contract spanning the existing Laravel snapshot/query path and Flutter favorites strip consumer.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Bugfix`, `UX`, `Flutter`, `Laravel`, `Tenant-Public`, `User-Visible`, `Requires-APROVADO`
- **Next exact step:** validate this contract with the user and wait for `APROVADO` before implementation.

## Scope
- [ ] Freeze the Home favorites ordering contract for v0.2.1+9 as event-time-first instead of allowing no-event favorites to surface ahead of event-backed favorites.
- [ ] Treat `TOCANDO AGORA` favorites as the highest-priority group in Home ordering when the snapshot indicates a live occurrence.
- [ ] Keep future event-backed favorites ordered by the soonest upcoming occurrence datetime (`next_event_occurrence_at` ascending).
- [ ] Preserve an explicit fallback order for favorites without `live/upcoming` event context by sorting them with `favorited_at` descending, regardless of whether they have only past events or no event at all.
- [ ] Keep Flutter Home favorites consuming backend order instead of introducing client-local resorting or ad hoc UI heuristics.
- [ ] Establish and implement a product-approved two-halo visual contract for Home favorites:
  - `TOCANDO AGORA`: stronger, warmer, Instagram-like emphasis.
  - `TEM EVENTO`: calmer event-presence halo that is visibly distinct from live-now.
- [ ] Preserve the existing compact identity/media contract for favorites (`avatar > cover > type visuals`) while adding the halos.
- [ ] Add focused backend, DTO/repository, controller, widget, and final runtime evidence for both ordering and halo behavior.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<pending>`, `laravel-app:<pending>`, `foundation_documentation:<current>`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `code repos: dev; foundation_documentation: main`
- **Production-ready threshold for this TODO:** `stage or main as applicable to touched code repos`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `laravel favorites ordering/snapshot contract` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `flutter favorite DTO/repository/controller/widget + halos` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `foundation documentation / TODO evidence` | `<current>` | `n/a` | `n/a` | `<pending>` | `drafted` |

## Out of Scope
- [ ] Redesigning the entire Home favorites layout, widths, or card anatomy beyond the halo treatment.
- [ ] Reworking favorite mutation, post-auth hydration, or general favorites repository ownership unless a narrow correction is strictly required for this ordering slice.
- [ ] Introducing new favorite relationship states, badges, or third/fourth halo meanings.
- [ ] Replacing the Home client-composed MVP model with a backend Home overview endpoint.
- [ ] Broad account-profile detail, discovery, or agenda redesign outside the exact favorite snapshot/order/halo contract needed by Home.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** favorite snapshot ordering rule adjustments, Home favorites DTO/repository/controller/widget updates, a narrow projection fix if snapshot truth is stale, and the focused tests/runtime validation needed to prove the product behavior.
- **Must update or split the TODO:** broader social-graph redesign, discovery-wide event ranking policy, a generalized global halo system across unrelated surfaces, or a new Home composition architecture.

## Definition of Done
- [ ] `DOD-01` Home favorites with `TOCANDO AGORA` sort ahead of non-live favorites.
- [ ] `DOD-02` Home favorites with upcoming events sort by the soonest `next_event_occurrence_at` ascending.
- [ ] `DOD-03` Favorites without live/upcoming event context never appear ahead of favorites that do have live/upcoming event context.
- [ ] `DOD-04` The fallback order for favorites without `live/upcoming` event context is `favorited_at` descending and is documented in code/tests rather than emerging accidentally from insertion order.
- [ ] `DOD-05` Flutter preserves backend ordering from the `/favorites` contract and does not add client-local resorting.
- [ ] `DOD-06` `TOCANDO AGORA` and `TEM EVENTO` halos are visually distinct, stable, and mapped from snapshot-backed favorite state.
- [ ] `DOD-07` Favorites without event state render no halo.
- [ ] `DOD-08` Existing favorite navigation and compact preview identity contract remain stable after the halo/order work.
- [ ] `DOD-09` Focused backend + Flutter automated coverage and final runtime evidence prove the ordering and halo behavior.

## Validation Steps
- [ ] Add fail-first Laravel coverage for the exact event-priority ordering expected by Home favorites, including the no-event regression case.
- [ ] Add or update favorite snapshot projection tests proving `live_now` and `next_event` are materialized consistently for Home ordering, while fallback ordering for the non-live/non-upcoming group comes from `favorited_at`.
- [ ] Add focused Flutter DTO/repository/controller/widget tests proving backend order is preserved and halo state maps correctly from favorite snapshot fields.
- [ ] Run the focused Laravel favorites suite.
- [ ] Run the focused Flutter favorites/home suite and analyzer.
- [ ] Run final runtime evidence for the Home favorites strip on the relevant browser/device lane after the updated bundle/runtime target is published.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` Home favorites with `TOCANDO AGORA` sort ahead of non-live favorites. | `test` | `<planned Laravel favorites feature test + runtime proof>` | `backend + browser/device` | `planned` | Live-now precedence must be explicit, not incidental. |
| `DOD-02` | `Definition of Done` | `DOD-02` Home favorites with upcoming events sort by the soonest `next_event_occurrence_at` ascending. | `test` | `<planned Laravel favorites feature test>` | `backend` | `planned` | Must name exact target ids/order. |
| `DOD-03` | `Definition of Done` | `DOD-03` Favorites without live/upcoming event context never appear ahead of favorites that do have live/upcoming event context. | `test` | `<planned Laravel favorites feature test>` | `backend` | `planned` | Covers the reported regression directly. |
| `DOD-04` | `Definition of Done` | `DOD-04` The fallback order for favorites without `live/upcoming` event context is `favorited_at` descending and is documented in code/tests rather than emerging accidentally from insertion order. | `test+review` | `<planned Laravel feature/projection test + query review>` | `backend` | `planned` | Past-event-only and no-event favorites share the same fallback tier. |
| `DOD-05` | `Definition of Done` | `DOD-05` Flutter preserves backend ordering from the `/favorites` contract and does not add client-local resorting. | `test` | `<planned Flutter repository/controller test>` | `local Flutter` | `planned` | Must prove transport order reaches Home unchanged. |
| `DOD-06` | `Definition of Done` | `DOD-06` `TOCANDO AGORA` and `TEM EVENTO` halos are visually distinct, stable, and mapped from snapshot-backed favorite state. | `widget+runtime` | `<planned FavoriteChip/FavoritesStrip widget test + final runtime lane>` | `local Flutter + browser/device` | `planned` | Visual distinction must be asserted semantically and at runtime. |
| `DOD-07` | `Definition of Done` | `DOD-07` Favorites without event state render no halo. | `widget` | `<planned FavoriteChip/FavoritesStrip widget test>` | `local Flutter` | `planned` | No false-positive halo. |
| `DOD-08` | `Definition of Done` | `DOD-08` Existing favorite navigation and compact preview identity contract remain stable after the halo/order work. | `test` | `<planned favorites controller/widget regression suite>` | `local Flutter` | `planned` | Reuse the existing navigation/preview regression surfaces. |
| `DOD-09` | `Definition of Done` | `DOD-09` Focused backend + Flutter automated coverage and final runtime evidence prove the ordering and halo behavior. | `test+runtime` | `<planned CI-equivalent suites + runtime lane>` | `backend + Flutter + browser/device` | `planned` | Final acceptance requires runtime evidence, not code inspection only. |
| `VAL-01` | `Validation Steps` | Add fail-first Laravel coverage for the exact event-priority ordering expected by Home favorites, including the no-event regression case. | `test` | `<planned tests/Feature/Favorites/FavoritesControllerTest.php>` | `local Laravel` | `planned` | Should fail on the current broken behavior before code change. |
| `VAL-02` | `Validation Steps` | Add or update favorite snapshot projection tests proving `live_now` and `next_event` are materialized consistently for Home ordering. | `test` | `<planned tests/Feature/Favorites/FavoriteSnapshotProjectionTest.php>` | `local Laravel` | `planned` | Protects snapshot truth for the event-priority tiers, while fallback ordering remains `favorited_at`-driven. |
| `VAL-03` | `Validation Steps` | Add focused Flutter DTO/repository/controller/widget tests proving backend order is preserved and halo state maps correctly from favorite snapshot fields. | `test` | `<planned Flutter favorites/home tests>` | `local Flutter` | `planned` | Must cover DTO -> resume -> controller -> widget flow. |
| `VAL-04` | `Validation Steps` | Run the focused Laravel favorites suite. | `test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php tests/Feature/Favorites/FavoriteSnapshotProjectionTest.php` | `local Laravel safe runner` | `planned` | Local CI-equivalent for the touched backend contract. |
| `VAL-05` | `Validation Steps` | Run the focused Flutter favorites/home suite and analyzer. | `test+analyzer` | `cd flutter-app && fvm flutter test <focused favorites/home suite> && fvm dart analyze --format machine` | `local Flutter` | `planned` | Exact focused suite to be frozen before implementation. |
| `VAL-06` | `Validation Steps` | Run final runtime evidence for the Home favorites strip on the relevant browser/device lane after the updated bundle/runtime target is published. | `runtime` | `<planned Playwright navigation/browser or ADB integration evidence>` | `browser/device` | `planned` | Runtime lane depends on final touched surfaces and parity. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto-tech-lead` | `operational-coder` | This session opens the governing TODO; execution starts only after user approval. | `foundation_documentation/todos/active/v0.2.1+9/**` -> code repos | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the product surface is narrow, but the correct fix crosses Laravel snapshot/query truth and Flutter user-visible rendering, with runtime evidence required for final acceptance.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant_home_composer_module.md` section `7 Canonical Decision Baseline`
  - `account_profile_catalog_module.md` sections `Favorites client-state contract` and `7 Canonical Decision Baseline`
  - `flutter_client_experience_module.md` section `2.1 Domain Rules` if the favorite halo/runtime contract needs explicit promotion
- **Module decision consolidation targets (required):**
  - `tenant_home_composer_module.md` `HOM-09`
  - `account_profile_catalog_module.md` `PCO-06`, `PCO-13`
  - `events_module.md` `EVS-OCC-01`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Home favorites ordering for v0.2.1+9 is event-time-first, not simple favorite recency.
- [x] `D-02` `TOCANDO AGORA` is the highest-priority ordering state because a currently active occurrence is the most immediate event-time state the product can expose.
- [x] `D-03` Favorites with future occurrences sort by `next_event_occurrence_at` ascending after the live-now group.
- [x] `D-04` Favorites without `live/upcoming` event context must not surface ahead of live/upcoming favorites; past-event-only and no-event favorites share the same fallback tier ordered by `favorited_at` descending.
- [x] `D-05` Flutter Home favorites must consume backend order as-is; client-local resorting is forbidden.
- [x] `D-06` The v0.2.1+9 halo proposal is:
  - `TOCANDO AGORA`: stronger Instagram-like warm gradient/dual-ring treatment with subtle glow.
  - `TEM EVENTO`: calmer accent ring without the live-now glow intensity.
  - `SEM EVENTO`: no halo.
- [x] `D-07` Halo state derives from the same snapshot-backed favorite resume inputs used for order semantics; it must not be inferred from unrelated widget-local heuristics.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `tenant_home_composer_module.md#HOM-09` | Registered identity-dependent Home state is repository-owned and refreshed through canonical hydration boundaries. | `Preserve` | Home favorites remains repository-owned even if ordering/halos change. |
- | `account_profile_catalog_module.md#PCO-06` | Compact favorite/public identity rows use `avatar > cover > type visuals`. | `Preserve` | Halos wrap the existing compact identity surface without changing media precedence. |
- | `account_profile_catalog_module.md#PCO-13` | Viewer favorite ids are registered user-linked client state refreshed through post-auth hydration. | `Preserve` | This slice must not regress favorite-state ownership while adjusting order/visual state. |
- | `events_module.md#EVS-OCC-01` | Public event consumption is occurrence-first. | `Preserve` | Favorite ordering and halo semantics must stay occurrence-backed, not event-summary guessed. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The source of truth for Home favorites order is the backend favorites contract plus snapshot truth, not the Home widget.
- [x] `D-02` `live_now_event_occurrence_*` participates in product ordering priority, not only in readback payload decoration.
- [x] `D-03` A no-event favorite cannot outrank a favorite with live or upcoming event context.
- [x] `D-04` Halo semantics must be readable at a glance and visually distinguish live-now from generic event-presence.
- [x] `D-05` Existing favorite navigation and preview identity rules stay intact unless a separate approved slice changes them.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The reported wrong order is not caused by Flutter resorting locally; the client preserves backend order from `/favorites`. | `flutter-app/lib/infrastructure/repositories/favorite_repository.dart`; `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart` proves order is preserved. | The TODO would need to absorb a client-side sort bug in addition to backend logic/snapshot truth. | `High` | `Keep as Assumption` |
| `A-02` | The existing backend contract is close to the desired behavior but incomplete because it sorts on `next_event_occurrence_at` and `last_event_occurrence_at` while ignoring explicit `live_now_event_occurrence_at` and the clarified fallback to `favorited_at` descending for the non-live/non-upcoming group. | `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php` sorts `next`/`last`; snapshot tests prove `live_now` is already materialized separately. | Snapshot truth, not query sort, may be the only real bug; implementation would then focus on projection rebuild instead of query priority. | `High` | `Keep as Assumption` |
| `A-03` | The current Flutter halo states exist as implementation plumbing (`liveNow|upcoming`) but are not yet product-frozen to the requested Instagram-style semantics. | `flutter-app/lib/domain/favorite/projections/favorite_resume.dart`; `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorite_chip.dart`. | Existing visuals may already match product intent and only naming/tests are missing. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php`
- `laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`
- `laravel-app/tests/Feature/Favorites/FavoriteSnapshotProjectionTest.php`
- `flutter-app/lib/infrastructure/dal/dto/favorite/favorite_preview_dto.dart`
- `flutter-app/lib/infrastructure/repositories/favorite_repository.dart`
- `flutter-app/lib/domain/favorite/projections/favorite_resume.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorite_chip.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorites_strip.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart`
- `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`

### Ordered Steps
1. Reproduce the ordering bug with a fail-first Laravel favorites feature test that includes live/event/no-event permutations matching the reported Home regression.
2. Verify whether the wrong order comes from the favorites query sort, stale snapshot fields, or both.
3. Implement the narrowest backend correction so Home favorites expose the canonical event-time-first order.
4. Add/update snapshot projection coverage so `live_now` and `next_event` fields remain trustworthy after event CRUD/state changes.
5. Update Flutter favorite DTO/domain/widget contract only where needed to preserve order and render the two-halo visual language.
6. Add focused Flutter widget/controller coverage proving the exact halo state mapping and the absence of client-side resorting.
7. Run focused Laravel + Flutter validation suites, then collect final runtime evidence on the required browser/device lane.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** this is a user-visible contract bug plus a product-visual semantics slice; fail-first evidence is needed to prevent ambiguous “looks fixed” closure.
- **Fail-first target(s) (when required):**
  - Laravel favorites ordering feature test with live/upcoming/no-event permutations.
  - Flutter widget/controller test proving the Home strip preserves backend order and maps halo states distinctly.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Home favorites read order | `payload consumed by UI` | `shared-android-web` | `ADB integration or Playwright readonly` | `no` | `yes` | Focused backend ordering tests + final Home runtime strip proof | Includes `favorited_at DESC` fallback for the non-live/non-upcoming group. |
| Home favorites halo rendering | `visible UI` | `shared-android-web` | `ADB integration or Playwright readonly` | `no` | `yes` | FavoriteChip/FavoritesStrip widget tests + final runtime screenshot/assertion | n/a |
| Favorite snapshot ordering fields | `field/DTO/domain refactor` | `n/a` | `n/a` | `no` | `yes` | Snapshot projection feature tests | Backend-only truth surface. |
| Favorite mutation semantics | `CRUD/mutation` | `shared-android-web` | `n/a` | `no` | `yes` | Reuse existing favorite mutation contract coverage only if touched; otherwise explicit non-applicability rationale | This slice is read-order/visual unless implementation spills into mutation boundaries. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / favorites query + snapshot suite` | Ordering truth and snapshot fields are backend-owned. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php tests/Feature/Favorites/FavoriteSnapshotProjectionTest.php` | `Local-Implemented` | `planned` | `<pending>` | Minimum backend CI-equivalent for this slice. |
| `flutter-app / focused favorites-home suite` | DTO/resume/controller/widget order + halos are user-visible in Home. | `cd flutter-app && fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart` | `Local-Implemented` | `planned` | `<pending>` | Expand with FavoriteChip/FavoritesStrip widget tests if added. |
| `flutter-app / analyzer` | Touched Flutter presentation/domain/dto files must remain clean. | `cd flutter-app && fvm dart analyze --format machine` | `Local-Implemented` | `planned` | `<pending>` | Required if any Flutter source changes. |

### Runtime / Rollout Notes
- No migration is expected if the fix stays within query sort and existing snapshot fields.
- If the snapshot projection must add or reinterpret persisted fields, record the migration/backfill/runtime implications before implementation begins.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)

### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** `medium`
  - **Evidence:** `FavoritesQueryService` currently sorts on `next_event_occurrence_at` / `last_event_occurrence_at`, but snapshot truth also carries `live_now_event_occurrence_at`, and the clarified fallback is `favorited_at DESC` for every favorite outside the live/upcoming tiers.
  - **Why it matters now:** the product specifically wants time-based event order; leaving live-now outside the ordering contract and keeping `last_event_occurrence_at` as a fallback priority can keep the reported wrong order alive even if halos are added.
  - **Option A (Recommended):** make live-now part of the backend favorites ordering contract, drop `last_event_occurrence_at` from ordering priority, and fall back to `favorited_at DESC` for the non-live/non-upcoming group.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** keep backend order as-is and add Flutter-local live-now resorting.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** rely on current `next/last` behavior and only restyle halos.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`, because Home favorites order is a backend contract already preserved by Flutter.

- **Issue ID:** `UX-01`
  - **Severity:** `medium`
  - **Evidence:** `FavoriteChip` already has `liveNow|upcoming` states, but the visuals are subtle implementation styling rather than an approved product signal.
  - **Why it matters now:** if the semantics are not frozen, the app can ship a technically different but still ambiguous halo treatment.
  - **Option A (Recommended):** freeze the two-halo language now and test it with widget/runtime evidence.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** implement a quick color tweak without naming/freeze/testing the states.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `unknown`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** keep the current halos untouched.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`, because the product request is about meaning, not just decoration.

### Failure Modes & Edge Cases
- [ ] Live-now occurrence exists in snapshot but the ordering still falls back to future/past because query logic ignores it.
- [ ] Profiles with stale snapshot fields appear as no-event and are incorrectly pushed into the `favorited_at` fallback tier.
- [ ] Flutter halo styling collapses live-now and has-event into visually indistinguishable states.
- [ ] A narrow Flutter-local fix accidentally hides a backend ordering defect that later reappears on another consumer.

### Residual Unknowns / Risks
- Snapshot drift could still misclassify a profile into the `favorited_at` fallback tier if `live_now` or `next_event` projection fields are stale.

## Approval
- **Status:** `pending`
- **Approved by:** `<pending>`
- **Approved at:** `<pending>`
- **Approval evidence:** `<pending>`
- **Approval scope:** implement the v0.2.1+9 Home favorites event-time ordering and two-halo status contract exactly as defined in this TODO.
- **Renewed approval required if:** implementation expands into broader Home redesign, mutation semantics, cross-surface global halo rollout, or a different event-ordering policy than the one frozen here.
