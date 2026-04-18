# TODO (V1): Favorites Account Profile Visual Enrichment

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Automated-Validated`, `Manual-Smoke-Pending`
**Next exact step:** Run manual smoke on the tenant Home favorites strip to validate `avatar > cover > type visuals`, direct partner navigation by slug, and halo emphasis for `live now` / `upcoming` / `no event` states against real runtime data.
**Owners:** Flutter Team, Laravel Team
**Objective:** Enrich the materialized `favorites` account-profile snapshot/payload with the minimum target-preview fields required to apply the shared account-profile visual precedence in the home favorites strip (`avatar > cover > type visuals`) while preserving and extending the existing event-snapshot contract so ordering and halo emphasis can distinguish `live now`, `upcoming`, and `no event` states without request-time aggregation or runtime `fetchAll` workarounds.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before implementation + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `../foundation_documentation/modules/partner_catalog_and_offer_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for home favorites strip behavior, shared visual-resolution rules, and Flutter-side projection boundaries.
- `partner_catalog_and_offer_module.md`: authoritative for public account-profile identity semantics reused by home/discovery/detail surfaces.

### Decision Consolidation Targets

- Promote durable favorites-strip preview contract changes into `../foundation_documentation/modules/flutter_client_experience_module.md`.
- Promote only stable account-profile identity semantics into `../foundation_documentation/modules/partner_catalog_and_offer_module.md` if the lane changes reusable public preview expectations.

---

## References

- `../foundation_documentation/todos/completed/TODO-v1-account-profile-visual-resolution-hardening.md`
- `../foundation_documentation/todos/completed/TODO-v1-screen-discovery-performance-hardening.md`
- `lib/domain/favorite/projections/favorite_resume.dart`
- `lib/infrastructure/dal/dto/favorite/favorite_preview_dto.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorite_chip.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Integration/Favorites/AccountProfileFavoriteSnapshotBuilder.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`

---

## Scope

- Enrich the materialized `favorites` account-profile target snapshot with `cover_url` and `profile_type`.
- Expose those new target fields through the existing favorites API response while preserving the current next/last ordering semantics and adding the minimum live-now signal needed for halo emphasis.
- Extend Flutter favorites DTO/domain/projection paths so the home favorites strip receives the minimum inputs required to apply `avatar > cover > type visuals`.
- Reuse the shared client-side account-profile visual resolution rules already approved for hero/discovery/nearby surfaces, using the app bootstrap `profile_type` registry to resolve label/visual from the target `profile_type`.
- Keep the home favorites strip visual precedence limited to the target preview block; do not collapse or redesign the event snapshot semantics that favorites already carries.
- Drive favorites halo states from the snapshot contract:
  - no halo for profiles with no relevant event
  - subtle halo for profiles with upcoming event
  - stronger halo for profiles with live-now event

## Out of Scope

- Request-time aggregation or `$lookup` to resolve `profile_type` or taxonomy labels.
- Any `fetchAll` taxonomies/profile-types workaround in Flutter.
- Favorites-strip redesign beyond applying the approved visual precedence.
- Enriching favorites with taxonomy summaries.
- Reworking favorites ordering, event snapshot semantics, or navigation behavior.
- Redesigning the favorites strip layout beyond adding the approved halo states.

---

## Decision Baseline (Frozen)

- `D-01`: Favorites remains snapshot-backed. The fix must enrich the materialized account-profile snapshot, not add request-time aggregation.
- `D-02`: The `favorites` payload keeps its existing `snapshot` and `navigation` blocks. Only the `target` preview block grows.
- `D-02a`: The favorites snapshot contract may grow additively with a live-now signal, but it must preserve the existing next/last ordering semantics and remain snapshot-backed.
- `D-03`: The `target` preview contract for `account_profile` favorites becomes: `id`, `slug`, `display_name`, `avatar_url`, `cover_url`, `profile_type`.
- `D-04`: Flutter resolves `profile_type` label/visual from the app bootstrap registry; the backend does not embed full type definitions into favorites.
- `D-05`: Home favorites strip compact precedence is `avatar > cover > type visuals`.
- `D-06`: If no avatar or cover exists, the favorites strip uses the canonical `type visual` instead of a generic add/storefront placeholder.
- `D-07`: `Perto de você` is inspiration only. Favorites may reuse the same target-preview fields, but it must preserve its extra event/snapshot metadata.
- `D-08`: No runtime `fetchAll` is allowed to compensate for missing favorites preview fields.
- `D-09`: Favorites ordering remains driven by `next_event_occurrence_at` ascending first; halo emphasis is a separate visual concern driven by the snapshot state.
- `D-10`: The live/upcoming/no-event halo state must come from snapshot data, not from client-side event scans.

---

## Plan Review Gate (Medium)

### Issue Card P-01 — Favorites strip lacks enough target-preview fields for canonical visual precedence
- Severity: `medium`
- Evidence: current favorites target payload only includes `display_name`, `slug`, `avatar_url`.
- Why now: the shared account-profile visual rules were hardened, but favorites remained out of scope because the payload cannot express `cover` or `profile_type`.
- Option A: keep current payload and let favorites fall back to generic assets/icons.
- Option B (rejected): add request-time aggregation/joins to decorate favorites.
- Option C (recommended): enrich the materialized snapshot target block with `cover_url` and `profile_type`, then let Flutter resolve visuals via the bootstrap registry.
- Tradeoff:
  - A: preserves inconsistency.
  - B: violates the performance direction and repeats work on every favorites read.
  - C: one-time snapshot enrichment, cheap reads, coherent with the existing shared resolver model.

### Issue Card P-02 — Flutter favorites path currently cannot use the shared account-profile visual resolver
- Severity: `medium`
- Evidence: `FavoriteResume` only carries title/slug/image/asset and has no `profile_type` or `cover`.
- Why now: without raw preview inputs, the strip cannot obey `avatar > cover > type visuals`.
- Option A: make the widget do backend-specific parsing itself.
- Option B (recommended): extend DTO/domain/projection minimally so the controller/widget can feed the shared resolver cleanly.
- Tradeoff:
  - A: spreads contract knowledge into presentation.
  - B: keeps DTO/domain/projection boundaries aligned.

### Issue Card P-03 — Halo emphasis needs a live-now signal that the current snapshot does not expose
- Severity: `medium`
- Evidence: current favorites snapshot contains `next_event_occurrence_at` and `last_event_occurrence_at`, but nothing that distinguishes `live now` from merely having an upcoming event.
- Why now: the requested halo behavior has three states (`none`, `upcoming`, `live now`), and only two of them are derivable from the current contract.
- Option A: infer `live now` client-side by scanning events.
- Option B (recommended): materialize an additive `live_now_event_occurrence_id` / equivalent live-now signal in the favorites snapshot builder and expose it through the API.
- Tradeoff:
  - A: violates the query-path guardrails and creates runtime drift.
  - B: keeps the favorites path snapshot-backed and cheap to read.

## Failure Modes & Edge Cases

- Favorites items for other target types must keep working even if `cover_url` / `profile_type` are absent.
- Primary pinned favorite must remain unchanged.
- Favorites items without any image and without a resolvable `profile_type` registry entry must still degrade safely.
- Snapshot/event ordering must not regress while touching the target preview payload.
- Live halo state must not require the client to inspect or fetch raw event lists.

## Uncertainty Register

- Assumption: the home favorites strip currently consumes only `account_profile` entries in the public tenant runtime, but the DTO/projection must remain tolerant of other target types.
- Confidence: `high`.

---

## Touched Surfaces

- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-favorites-account-profile-visual-enrichment.md`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Integration/Favorites/AccountProfileFavoriteSnapshotBuilder.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesQueryService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Favorites/FavoritesControllerTest.php`
- `lib/infrastructure/dal/dto/favorite/favorite_preview_dto.dart`
- `lib/domain/favorite/favorite.dart`
- `lib/domain/favorite/projections/favorite_resume.dart`
- `lib/infrastructure/repositories/favorite_repository.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorite_chip.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorites_strip.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller.dart`
- `test/infrastructure/dal/laravel_favorite_backend_test.dart`
- `test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`

## Ordered Steps

1. Add fail-first tests for Laravel favorites payload and Flutter favorites DTO/projection/widget behavior.
2. Enrich the Laravel materialized account-profile favorites snapshot target block with `cover_url` and `profile_type`, plus an additive live-now signal for halo state.
3. Expose those new target/snapshot fields through `FavoritesQueryService`.
4. Extend Flutter favorites DTO/domain/projection paths with the new target preview inputs and halo state inputs.
5. Apply the shared compact account-profile visual resolver in the home favorites strip and add the halo states.
6. Run focused Laravel + Flutter suites and `fvm dart analyze --format machine`.
7. Sync canonical docs if the reusable favorites-strip contract changes durably.

## Test Strategy

- `test-first`

## Fail-First Targets

- Laravel favorites response omits `target.cover_url` and `target.profile_type` for account-profile favorites.
- Flutter favorites DTO/projection drops `cover_url` / `profile_type`.
- Home favorites strip still falls back to generic placeholder behavior when `cover` or `type visual` should resolve the avatar.
- Home favorites strip cannot distinguish `live now` from `upcoming` because the snapshot lacks a live signal.

## Definition of Done

- Laravel favorites account-profile target payload exposes `cover_url` and `profile_type` with no request-time aggregation.
- Flutter favorites DTO/domain/projection preserves those fields.
- Home favorites strip resolves visuals using `avatar > cover > type visuals`.
- Home favorites strip applies halo states from snapshot data: none / upcoming / live-now.
- Existing favorites snapshot/event semantics remain intact.
- Focused Laravel/Flutter tests and `fvm dart analyze --format machine` pass.

## Validation Steps

- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Favorites/FavoritesControllerTest.php`
- `fvm flutter test test/infrastructure/dal/laravel_favorite_backend_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`
- `fvm dart analyze --format machine`
