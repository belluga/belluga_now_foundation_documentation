# TODO (Store Release): Event `artists` Eradication

**Classification note (2026-04-18):** this lane is release-critical. The store-release public app must not ship with `artists` still acting as a legacy event-composition projection across public payloads, Flutter runtime models, favorites/public-web helpers, or repair outputs.

**Scope authority note (2026-04-18):** this TODO is the direct delivery authority for full `artists` retirement in the store-release lane. `foundation_documentation/todos/completed/TODO-v1-event-parties-canonicalization-and-legacy-migration.md` remains closed for the canonical write/admin cutover; this lane exists because the audit proved that legacy `artists` still persists in read/runtime surfaces and should no longer remain in the release contract.

**Status legend:** `- [x] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Reopened. Prior Laravel and Flutter PRs contain useful partial evidence, but retroactive audit on April 22, 2026 found current release-facing `artists` residues in code/tests/docs, so this TODO cannot remain in promotion lane.
**Owners:** Delphi, Flutter Team, Laravel Team
**Goal:** eliminate `artists` as a persisted or behavior-driving event contract in store-release surfaces by moving all remaining consumers to canonical `event_parties`, `linked_account_profiles`, venue/place-ref ownership, or explicit counterpart projections derived from those canonical sources.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The previous event-parties lane correctly closed the canonical cutover: tenant-admin writes use `event_parties`, Laravel rejects `artist_ids`, and admin/runtime paths touched in that lane no longer treated `artists` as canonical input. During the reconciliation audit, we confirmed a second problem: `artists` still survived in public/read/runtime behavior and in repair-oriented outputs.

This residue was not just harmless historical storage. At the start of this lane, the release app still consumed `artists` through Flutter DTOs, event models, account-profile agenda parsing, map/event projections, discovery live-now logic, upcoming-event cards, favorites/public-web helpers, occurrence sync projections, search/index assumptions, tests, and fixtures. This TODO has since landed the cross-stack eradication and now remains in promotion follow-through only.

## Package-First Assessment
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "event artists linked account profiles favorites public web metadata"`
- Relevant packages found: none
- READMEs read: `n/a`
- Decision: local cross-stack implementation across the existing Laravel host app, `belluga_events`, and Flutter app
- Tier: `Local`
- Rationale: the residue is project-local and spread across existing runtime contracts, projections, and docs; no proprietary package currently owns this release-specific eradication slice.

## Contract Delta Freeze (Required Before Implementation)
| Legacy Surface | Required Result In This Lane | Replacement Source / Rule |
| --- | --- | --- |
| Public Laravel event payload `artists` | Remove from release-facing current payload contract | `linked_account_profiles` and explicit counterpart semantics derived from canonical `event_parties` |
| Event-occurrence projection `artists` | Stop persisting as release-facing runtime ownership | canonical linked-profile/counterpart data only |
| Discovery `Tocando agora` | Stop depending on `event.artists` for visibility/cards | canonical non-venue linked profiles / counterpart projection |
| Upcoming event cards | Stop rendering `artists` chips as the release contract | counterpart chips derived from canonical linked profiles |
| Map event marker/card imagery + tags | Stop reading `artists` | `event.thumb`, canonical linked profiles, then venue/runtime fallbacks per module contract |
| Account-profile agenda counterpart summary | Stop parsing/storing artist-shaped agenda projection | counterpart summaries derived from canonical linked profiles |
| Favorites/public-web helpers | Stop querying `artists.*` | canonical linked-profile or venue ownership only |

## Temporary Source Of Truth For Workers
- [x] Shared Flutter event-contract files have one owner in this lane. No parallel worker may redefine DTO/domain semantics independently.
- [x] Laravel may dual-serve additive canonical data only long enough to migrate release readers safely.
- [x] Laravel ownership removal for `artists` cannot start until a parity gate proves release readers, sync paths, and snapshot paths are clean.
- [x] Docs remain the final authoritative promotion target, but this freeze section is the pre-implementation execution authority while module docs still contain contradictions.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** the gap is already concrete and release-facing. We do not need more ideation to prove the problem; we need one execution authority that retires `artists` from the public/runtime contract before the Android store gate closes.
- **Direct-to-TODO rationale:** safe. The audit already identified the concrete Laravel/Flutter/doc surfaces that still depend on `artists`, and the user explicitly approved bringing this work into `store_release_android`.

## Contract Boundary

- This TODO owns full retirement of legacy `artists` from release-facing event read/runtime behavior.
- This TODO includes Laravel public/read contracts, occurrence/read projections, Flutter DTO/domain/runtime consumers, module/documentation contract alignment, and repair-flow outputs that still materialize or depend on `artists`.
- This TODO does **not** reopen the already-closed canonical write/admin migration to `event_parties`; it assumes that cutover remains correct and builds on top of it.
- If implementation reveals a broader future-facing abstraction need around counterpart projection helpers or package-level event-read shaping, split that follow-up into `vnext` after the release lane is safe.

## Delivery Status Canon

- **Current delivery stage:** `Pending`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Contract-Eradication`, `Reopened-Functional-Gap`
- **Next exact step:** remove or intentionally re-scope remaining release-facing `artists` read/runtime residues, then rebuild validation evidence and the Completion Evidence Matrix before promotion.

## References

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/completed/TODO-v1-event-parties-canonicalization-and-legacy-migration.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `flutter-app/lib/infrastructure/dal/dto/schedule/event_dto.dart`
- `flutter-app/lib/domain/schedule/event_model.dart`
- `flutter-app/lib/presentation/tenant_public/discovery/widgets/discovery_live_now_section.dart`
- `flutter-app/lib/presentation/tenant_public/widgets/upcoming_event_card.dart`
- `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `flutter-app/lib/domain/map/event_poi_model.dart`
- `flutter-app/lib/domain/venue_event/projections/venue_event_resume.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/partners_backend/laravel_account_profiles_backend.dart`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/LegacyEventPartiesCanonicalizationService.php`
- `laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php`
- `laravel-app/app/Providers/PackageIntegration/FavoritesIntegrationServiceProvider.php`
- `laravel-app/app/Integration/Favorites/AccountProfileFavoriteSnapshotBuilder.php`

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
- **Decision promotion targets:**
  - `events_module.md` read-model/search/invariant sections
  - `agenda_and_action_planner_module.md` live-now and agenda counterpart semantics
  - `map_poi_module.md` event marker/card counterpart semantics
  - `account_profile_catalog_module.md` account-profile agenda counterpart semantics when touched

## Ecosystem Impact Analysis

- **Current classification:** `Project-Local`
- **Why:** this lane is a release-specific contract cleanup for Bóora!/Belluga Now public event/runtime behavior. The residue lives in project-specific payloads, Flutter projections, public-web metadata helpers, favorites integration, and release-facing UI semantics.
- **Reuse doctrine note:** a later package-level refinement in `belluga_events` may extract reusable canonical counterpart-projection helpers, but that is not the goal of this lane. The release-critical work is to stop shipping a project-local legacy contract.

## Decision Baseline (Frozen 2026-04-18)

- [x] `D-01` `artists` is no longer an approved store-release contract for event composition on any public/read/runtime surface.
- [x] `D-02` Canonical non-location event composition remains `event_parties` with derived `linked_account_profiles`; venue/location ownership remains `location + place_ref` with `venue` only as a derived read projection.
- [x] `D-03` Any release-facing surface that currently needs artist-like names, media, taxonomy, or counterpart emphasis must derive them from canonical linked profiles or an explicit counterpart projection derived from canonical data, not from legacy `artists`.
- [x] `D-04` Store release cannot close while Flutter event DTO/domain models still carry `artists` as behavior-driving fields.
- [x] `D-05` Search, taxonomy, favorites, public-web metadata, and occurrence/read projection behavior must stop treating `artists.*` as a first-class runtime field by this TODO's closure.
- [x] `D-06` Discovery/live-now, upcoming-event, account-profile agenda, and map/event surfaces must stop gating or shaping behavior through `artists` and move to canonical counterpart semantics.
- [x] `D-07` The earlier event-parties TODO remains closed; this lane is the explicit follow-on eradication pass, not a reopening of the admin/write migration.
- [x] `D-08` Repair and legacy-canonicalization flows may continue to read historical `artists` only as migration input while the lane is in progress, but the completed state requires that repair outputs no longer repopulate or preserve `artists` as runtime/public contract.

## Pre-Implementation Audit Snapshot (2026-04-18)

### Laravel residue

- `EventQueryService` still emits `payload['artists']` on public read payloads.
- `EventOccurrenceSyncService` still mirrors an `artists` read projection into occurrence documents.
- `PublicWebMetadataService` still reads `artists` to derive public metadata imagery.
- Favorites integration and favorite snapshot builders still query/read `artists`.
- Event search/index migrations and fixtures still encode `artists.*` assumptions.
- Tests still assert public payloads and occurrence projections through `artists`.

### Flutter residue

- `EventDTO` still decodes/encodes `artists`.
- `EventModel` still exposes `artists` explicitly "for backward compatibility".
- Discovery live-now, upcoming event cards, map/event POI, account-profile detail agenda, and venue-event projections still read `event.artists`.
- Account-profile backend parsing still collects agenda `artists` projections.
- Testing/domain factories still shape event data through `artists`.

### Documentation residue

- Canonical docs already reject `artists` as admin/write contract, but historical/current module notes still describe some public behavior through `artists`.
- Release-facing TODOs and completed artifacts still reference artist-driven live-now/card/search semantics and need reconciliation once the cutover lands.

## Scope

- [ ] Remove `artists` from Laravel public event/detail/list payloads and replace all touched consumers with canonical linked-profile/counterpart data.
- [ ] Stop persisting `artists` as event-occurrence read projection output for release-facing runtime behavior.
- [ ] Remove `artists` dependence from public-web metadata, favorites integration, favorite snapshots, and any other touched runtime helper still reading it.
- [ ] Remove `artists` from Flutter `EventDTO`, `EventModel`, and touched event/account-profile/map projections.
- [ ] Refactor discovery live-now, upcoming-event cards, account-profile agenda, and map/event marker/card behavior to use canonical counterpart semantics instead of `artists`.
- [ ] Replace artist-shaped search/taxonomy/query assumptions with canonical linked-profile/counterpart ownership where required by the touched release surfaces.
- [ ] Update tests, fixtures, and module/docs authority so the resulting release contract no longer treats `artists` as a current runtime field.
- [x] Keep the eradication bounded to store-release relevance; if a non-release surface still needs later cleanup after the public/runtime path is safe, open explicit follow-up rather than silently widening this lane.

## Out of Scope

- [ ] Reopening tenant-admin write/input migration that was already closed under `event_parties`.
- [ ] Broad `partner` terminology retirement or Flutter domain-topology normalization beyond what is required to remove `artists` from the touched release surfaces.
- [ ] New event-detail capabilities unrelated to the legacy `artists` contract.
- [ ] Package extraction/platformization work beyond the concrete store-release cutover.

## Dependencies & Sequencing

- [x] `DEP-01` `foundation_documentation/todos/completed/TODO-v1-event-parties-canonicalization-and-legacy-migration.md` remains the prerequisite canonical cutover and stays closed.
- [x] `DEP-02` Any touched release surfaces under `store_release_android` that currently rely on event cards/live-now/account-profile agenda behavior were kept aligned as this lane landed.
- [x] `DEP-03` Canonical module docs and TODO authority were updated before this TODO moved to `promotion_lane/` so the eradicated contract remains authoritative.
- [x] `DEP-04` A parity gate passed before Laravel removed `artists` ownership from emitted payloads, persisted occurrence projections, or helper queries.

## Execution Tracks

### A) Laravel Contract And Persistence Cleanup

- [x] Remove `artists` from public event read payload generation and replace affected public consumers with canonical linked-profile/counterpart data.
- [x] Stop writing `artists` into event-occurrence projections used by release-facing runtime paths.
- [x] Remove `artists` dependence from public-web metadata and favorites snapshot/helpers.
- [x] Rewrite touched search/taxonomy/index/query behavior away from `artists.*` ownership where it still drives the release runtime.
- [x] Update fixtures and backend tests to assert the new canonical public/read contract.

### B) Flutter Unified Reader Migration

- [x] Freeze and land the shared event contract seam (`DTO` + domain + shared projections) under one owner before downstream consumer work diverges.
- [x] Refactor all touched Flutter readers, including discovery, upcoming cards, map, and account-profile agenda, to canonical linked-profile/counterpart semantics.
- [x] Keep card/list/map/account-profile behavior release-stable while removing the legacy field.
- [x] Update Flutter tests/factories so new event payloads no longer rely on `artists`.

### C) Documentation And Authority Consolidation

- [x] Promote the resulting read-model rules to `events_module.md` and the touched secondary module docs.
- [x] Update or annotate any release-facing TODO/module text that still assumes artist-driven live-now/cards/search after the cutover lands.
- [x] Keep the closed event-parties TODO accurate as historical context without letting it imply full eradication happened earlier than it did.

## Acceptance Criteria

- [ ] Public Laravel event payloads used by the release app no longer expose `artists` as a current runtime field.
- [ ] Event-occurrence projections used by release-facing read paths no longer persist or depend on `artists`.
- [ ] Flutter event DTO/domain models and touched projections no longer carry `artists`.
- [ ] Discovery live-now, upcoming-event cards, account-profile agenda, and map/event surfaces behave correctly without reading `artists`.
- [ ] Favorites/public-web helper paths no longer depend on `artists`.
- [ ] Touched search/taxonomy/runtime contracts no longer encode `artists.*` as current ownership.
- [ ] Canonical module/docs authority describes the resulting release contract without ambiguity.
- [ ] A parity gate proves release readers and helper paths are clean before Laravel removes the residual `artists` ownership paths.

## Definition of Done

- [ ] The store-release app can run its touched public event/account-profile/map flows without any `artists` field in the active event contract.
- [ ] No touched Flutter or Laravel release surface still treats `artists` as input, persisted projection, or behavior-driving fallback.
- [ ] Historical migration context remains documented, but the current canonical and runtime authority no longer tolerates `artists` as present-state behavior.

## Validation Steps

- [ ] Pre-removal parity gate:
  - shared Flutter event contract is frozen and migrated under one owner;
  - artists-free Laravel payload fixtures exist for touched release surfaces;
  - release Flutter readers render correctly from artists-free fixtures;
  - favorites/public-web/sync paths no longer require `artists`.
- [ ] Laravel automated: public event payloads and occurrence projections are correct after `artists` removal.
- [ ] Laravel automated: favorites/public-web/search/taxonomy behavior remains correct after the contract cutover.
- [ ] Flutter automated: discovery live-now, upcoming-event cards, account-profile agenda, and map/event consumers remain correct without `artists`.
- [ ] Flutter automated: DTO/domain decoding fails fast or stays aligned with the new canonical payload shape, with no silent legacy fallback.
- [ ] Manual smoke: Home/Discovery live-now, Events list/detail entrypoints, Public Account Profile agenda, Map event markers/cards, and any touched favorites/public metadata behavior all remain stable after the cutover.

## Validation Evidence

- Backend dev promotion: `https://github.com/belluga/belluga_now_backend/pull/157` merged to `dev` on April 20, 2026 with `3` PR checks passed; the PR scope explicitly covered tenant-public auth governance plus Laravel `artists` cleanup.
- Flutter dev promotion: `https://github.com/belluga/belluga_now_front/pull/235` merged to `dev` on April 20, 2026 with `5` PR checks passed; the PR validation explicitly listed `fvm dart analyze --format machine`, targeted Flutter tests, and readonly web navigation smoke.
- Foundation docs follow-through: this TODO moved to `promotion_lane/store_release_android/` and the active orchestrator/dependency references were reconciled locally on April 19, 2026; foundation-docs publication on `main` still remains pending.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:delphi/flutter-reconcile-store-release-20260419 -> dev @ 72560cf`, `laravel-app:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8`, `foundation_documentation:local promotion-lane realignment (2026-04-19)`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Laravel public/read/runtime `artists` eradication | `belluga_now_backend:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8` | `https://github.com/belluga/belluga_now_backend/pull/157 (merged -> dev on 2026-04-20)` | `pending` | `pending` | `Reopened; PR evidence is partial and current code still has runtime residues` |
| Flutter DTO/domain/runtime `artists` eradication | `belluga_now_front:delphi/flutter-reconcile-store-release-20260419 -> dev @ 72560cf` | `https://github.com/belluga/belluga_now_front/pull/235 (merged -> dev on 2026-04-20)` | `pending` | `pending` | `Reopened; PR evidence is partial and current code still has DTO/test residues` |
| Docs/tests/search/taxonomy/favorites reconciliation | `belluga_now_backend:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8` + `belluga_now_front:delphi/flutter-reconcile-store-release-20260419 -> dev @ 72560cf` + `foundation_documentation:local promotion-lane realignment (2026-04-19)` | `https://github.com/belluga/belluga_now_backend/pull/157 (merged -> dev on 2026-04-20)` + `https://github.com/belluga/belluga_now_front/pull/235 (merged -> dev on 2026-04-20)` | `pending` | `pending` | `Reopened; docs/tests/search/taxonomy residues still need closure` |

## Retroactive Audit Finding (2026-04-22)

- **Audit outcome:** `reopened`
- **Reason:** the prior `Lane-Promoted` marker was not substantiated by a Completion Evidence Matrix, and current code inspection still finds release-facing `artists` residues.
- **Recovered evidence:** backend PR #157 and frontend PR #235 remain useful partial evidence and should be reused when closing the surviving sub-criteria.
- **Blocking gaps found by `rg`:** `../laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php` still assigns `payload['artists']`; `../laravel-app/packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php` still derives an `artists` read projection; `../laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php` and `../laravel-app/app/Providers/PackageIntegration/FavoritesIntegrationServiceProvider.php` still read `artists`; `lib/infrastructure/dal/dto/schedule/event_dto.dart` still decodes `artists`; tests and migrations still carry `artists.*` assumptions.
- **Required closure before promotion:** decide whether any remaining `artists` paths are intentionally historical/migration-only, remove all release-facing runtime dependencies, rerun backend/Flutter/web navigation evidence, and add a Completion Evidence Matrix with one row per DoD/Validation criterion.

## Execution Plan (Critique-Reconciled)
### Ordered Steps
1. Freeze the contract delta above and keep it as the temporary worker authority until module docs are updated.
2. Land additive Laravel read/query support so canonical linked-profile/counterpart data is sufficient for release readers.
3. Land the shared Flutter event contract seam and all touched Flutter readers under one unified worker ownership.
4. Run the parity gate and prove artists-free fixtures, reader stability, and snapshot/sync cleanliness.
5. Remove Laravel `artists` emission, persistence, and helper/query ownership only after the parity gate passes.
6. Finish docs, indexes, fixtures, and regression follow-through.

### Worker Ownership
- `laravel-worker-artists-additive-20260419`: Laravel additive read/query work, helper/query cleanup, and backend tests through the parity gate.
- `flutter-worker-artists-unified-20260419`: shared Flutter event contract seam plus all touched release readers, including account-profile agenda.
- `orchestrator`: contract freeze maintenance, docs, fixtures/index follow-through, parity review, and final reconciliation.
