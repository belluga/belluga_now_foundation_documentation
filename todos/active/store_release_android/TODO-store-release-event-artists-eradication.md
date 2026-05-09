# TODO (Store Release): Event `artists` Eradication

**Classification note (2026-04-18):** this lane is release-critical. The store-release public app must not ship with `artists` still acting as a legacy event-composition projection across public payloads, Flutter runtime models, favorites/public-web helpers, or repair outputs.

**Scope authority note (2026-04-18):** this TODO is the direct delivery authority for full `artists` retirement in the store-release lane. `foundation_documentation/todos/completed/TODO-v1-event-parties-canonicalization-and-legacy-migration.md` remains closed for the canonical write/admin cutover; this lane exists because the audit proved that legacy `artists` still persists in read/runtime surfaces and should no longer remain in the release contract.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. Canonical write/admin migration is already closed, but public/read/runtime eradication of legacy `artists` is still open across Laravel payloads, occurrence projections, Flutter domain models, and release-facing consumers.
**Owners:** Delphi, Flutter Team, Laravel Team
**Goal:** eliminate `artists` as a persisted or behavior-driving event contract in store-release surfaces by moving all remaining consumers to canonical `event_parties`, `linked_account_profiles`, venue/place-ref ownership, or explicit counterpart projections derived from those canonical sources.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The previous event-parties lane correctly closed the canonical cutover: tenant-admin writes use `event_parties`, Laravel rejects `artist_ids`, and admin/runtime paths touched in that lane no longer treat `artists` as canonical input. During the reconciliation audit, we confirmed a second problem: `artists` still survives in public/read/runtime behavior and in repair-oriented outputs.

This remaining residue is not just harmless historical storage. The current release app still consumes `artists` through Flutter DTOs, event models, account-profile agenda parsing, map/event projections, discovery live-now logic, upcoming-event cards, favorites/public-web helpers, occurrence sync projections, search/index assumptions, tests, and fixtures. That means the store-release lane still carries a legacy event contract that the project has already rejected as canonical.

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

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Contract-Eradication`
- **Next exact step:** execute a bounded Laravel + Flutter cutover that removes `artists` from public/runtime payload ownership and updates all touched release surfaces to canonical linked-profile/counterpart semantics.

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

## Current Audit Snapshot

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
- [ ] Keep the eradication bounded to store-release relevance; if a non-release surface still needs later cleanup after the public/runtime path is safe, open explicit follow-up rather than silently widening this lane.

## Out of Scope

- [ ] Reopening tenant-admin write/input migration that was already closed under `event_parties`.
- [ ] Broad `partner` terminology retirement or Flutter domain-topology normalization beyond what is required to remove `artists` from the touched release surfaces.
- [ ] New event-detail capabilities unrelated to the legacy `artists` contract.
- [ ] Package extraction/platformization work beyond the concrete store-release cutover.

## Dependencies & Sequencing

- [x] `DEP-01` `foundation_documentation/todos/completed/TODO-v1-event-parties-canonicalization-and-legacy-migration.md` remains the prerequisite canonical cutover and stays closed.
- [ ] `DEP-02` Any touched release surfaces under `store_release_android` that currently rely on event cards/live-now/account-profile agenda behavior must be kept aligned as this lane lands.
- [ ] `DEP-03` Canonical module docs must be updated before this TODO closes so the eradicated contract becomes authoritative and historical notes stop reintroducing `artists`.

## Execution Tracks

### A) Laravel Contract And Persistence Cleanup

- [ ] Remove `artists` from public event read payload generation and replace affected public consumers with canonical linked-profile/counterpart data.
- [ ] Stop writing `artists` into event-occurrence projections used by release-facing runtime paths.
- [ ] Remove `artists` dependence from public-web metadata and favorites snapshot/helpers.
- [ ] Rewrite touched search/taxonomy/index/query behavior away from `artists.*` ownership where it still drives the release runtime.
- [ ] Update fixtures and backend tests to assert the new canonical public/read contract.

### B) Flutter Domain And Runtime Cleanup

- [ ] Remove `artists` from schedule/event DTOs, domain models, and touched supporting projections.
- [ ] Refactor public consumers now reading `event.artists` to canonical linked-profile/counterpart semantics.
- [ ] Keep card/list/map behavior release-stable while removing the legacy field.
- [ ] Update Flutter tests/factories so new event payloads no longer rely on `artists`.

### C) Documentation And Authority Consolidation

- [ ] Promote the resulting read-model rules to `events_module.md` and the touched secondary module docs.
- [ ] Update or annotate any release-facing TODO/module text that still assumes artist-driven live-now/cards/search after the cutover lands.
- [ ] Keep the closed event-parties TODO accurate as historical context without letting it imply full eradication happened earlier than it did.

## Acceptance Criteria

- [ ] Public Laravel event payloads used by the release app no longer expose `artists` as a current runtime field.
- [ ] Event-occurrence projections used by release-facing read paths no longer persist or depend on `artists`.
- [ ] Flutter event DTO/domain models and touched projections no longer carry `artists`.
- [ ] Discovery live-now, upcoming-event cards, account-profile agenda, and map/event surfaces behave correctly without reading `artists`.
- [ ] Favorites/public-web helper paths no longer depend on `artists`.
- [ ] Touched search/taxonomy/runtime contracts no longer encode `artists.*` as current ownership.
- [ ] Canonical module/docs authority describes the resulting release contract without ambiguity.

## Definition of Done

- [ ] The store-release app can run its touched public event/account-profile/map flows without any `artists` field in the active event contract.
- [ ] No touched Flutter or Laravel release surface still treats `artists` as input, persisted projection, or behavior-driving fallback.
- [ ] Historical migration context remains documented, but the current canonical and runtime authority no longer tolerates `artists` as present-state behavior.

## Validation Steps

- [ ] Laravel automated: public event payloads and occurrence projections are correct after `artists` removal.
- [ ] Laravel automated: favorites/public-web/search/taxonomy behavior remains correct after the contract cutover.
- [ ] Flutter automated: discovery live-now, upcoming-event cards, account-profile agenda, and map/event consumers remain correct without `artists`.
- [ ] Flutter automated: DTO/domain decoding fails fast or stays aligned with the new canonical payload shape, with no silent legacy fallback.
- [ ] Manual smoke: Home/Discovery live-now, Events list/detail entrypoints, Public Account Profile agenda, Map event markers/cards, and any touched favorites/public metadata behavior all remain stable after the cutover.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `belluga_now_docker:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Laravel public/read/runtime `artists` eradication | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Flutter DTO/domain/runtime `artists` eradication | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Docs/tests/search/taxonomy/favorites reconciliation | `pending` | `pending` | `pending` | `pending` | `Pending` |
