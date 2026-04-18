# TODO (V1): Event Parties Canonicalization and Legacy Migration

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Scope-Frozen`, `CRUD-Matrix-Automated-Validated`, `Archived-Legacy-Repair-Automated-Validated`, `Manual-Smoke-Pending`
**Next exact step:** Fix the newly surfaced tenant-admin regressions before resuming manual smoke: preserve selected related account-profile summaries while the admin form hydrates candidate pages, switch admin event default ordering to nearest start first, and dim non-published admin cards to 70% opacity; then rerun focused validation and return to the archived/legacy smoke checklist.
**Owners:** Flutter Team, Laravel Team
**Objective:** Establish `event_parties` as the canonical event-composition contract for non-location account profiles across event CRUD, immersive event detail, tenant-admin write flows, and the touched runtime callers; remove `artists` as a behavior-driving source for linked account-profile logic; keep location semantics canonical through `location + place_ref` with `venue` only as a read projection; and deliver a transitional legacy-repair flow (`Verificar Eventos Legados`) plus reusable reconciliation script/service that backfills invalid historical events by materializing canonical artist `event_parties` metadata (including `slug`) from persisted legacy artist/account-profile references.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `big`
**Checkpoint Policy:** section-by-section review checkpoints before implementation + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`
**Security / Operational Risk Level:** `high`

**Blocker Notes:** No external blocker. The hard-cutover baseline is implemented locally, but real-tenant validation exposed unresolved CRUD lifecycle/test-hardening debt before promotion.
**Last confirmed truth:** `2026-04-14` the canonical write path remains correct, but manual tenant-admin usage exposed three bounded regressions that must be closed before promotion: (1) the event form can temporarily lose the selected related-account profile summaries and show raw ids with `Perfil não disponível na lista atual` after async candidate hydration replaces the local known-profile set, even though saving still emits the correct canonical `event_parties` ids; (2) tenant-admin event list default ordering still follows the older descending-start contract instead of nearest start first; and (3) non-published admin event cards still render with full-strength chrome instead of the intended 70% faded-out treatment. The previously landed canonical guarantees remain intact: strict `event_parties` writes (`party_ref_id` + optional `permissions.can_edit` only), backend-owned metadata generation, ordered replace semantics, archived legacy repair coverage, and public hero fallback order `event.thumb -> linked_account_profiles -> venue` with no legacy `artists` fallback in the touched callers. The next round must harden these admin-only regressions with focused Flutter/Laravel tests plus local navigation validation before manual archived/legacy smoke resumes.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_admin`, `tenant_public`
- **Subscope:** `n/a`

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda/evento/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/parceiro/:slug` agenda tab surfaces | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/tenant-admin/events` | tenant | `tenant` | `tenant_admin` | `n/a` | tenant-admin auth shell |
| `/tenant-admin/events/:id` form flow | tenant | `tenant` | `tenant_admin` | `n/a` | tenant-admin auth shell |
| tenant admin events API | tenant | `tenant` | `tenant_admin` | `n/a` | sanctum tenant admin |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/events_module.md`
- **Secondary:** `../foundation_documentation/modules/flutter_client_experience_module.md`, `../foundation_documentation/modules/partner_catalog_and_offer_module.md`

### Canonical Coverage Status

- `events_module.md`: authoritative for event/event-occurrence payload ownership, CRUD contracts, `event_parties`, and immersive event-detail linked-profile semantics.
- `flutter_client_experience_module.md`: authoritative for Tenant Admin interaction affordances and public immersive linked-profile card behavior.
- `partner_catalog_and_offer_module.md`: authoritative for Account Profile agenda/card semantics that may need cleanup once venue leaves `event_parties`.

### Decision Consolidation Targets

- Promote canonical event write/read contract changes to `../foundation_documentation/modules/events_module.md`.
- Promote Tenant Admin provisional button behavior and Flutter interaction consequences only if they become durable enough for `../foundation_documentation/modules/flutter_client_experience_module.md`.
- Promote durable Account Profile agenda/counterpart exclusion cleanup only if this lane changes cross-surface semantics materially enough for `../foundation_documentation/modules/partner_catalog_and_offer_module.md`.
- Update `../foundation_documentation/endpoints_mvp_contracts.md` if the admin migration endpoint/command-backed route is approved.

---

## References

- `../foundation_documentation/modules/events_module.md`
- `../foundation_documentation/modules/flutter_client_experience_module.md`
- `../foundation_documentation/modules/partner_catalog_and_offer_module.md`
- `../foundation_documentation/todos/active/vnext/TODO-v1-immersive-event-detail-dynamic-profile-category-tabs.md`
- `lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder.dart`
- `lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder.dart`
- `lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `lib/presentation/tenant_admin/events/screens/tenant_admin_events_screen.dart`
- `lib/domain/tenant_admin/tenant_admin_legacy_event_parties_summary.dart`
- `lib/domain/tenant_admin/value_objects/tenant_admin_count_value.dart`
- `scripts/check_event_parties_cutover.sh`
- `../laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `../laravel-app/packages/belluga/belluga_events/src/Application/Events/Concerns/EventManagementPartiesAndMetadata.php`
- `../laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `../laravel-app/app/Integration/Events/AccountProfileResolverAdapter.php`
- `../laravel-app/app/Integration/Events/EventParties/ArtistEventPartyMapper.php`
- `../laravel-app/packages/belluga/belluga_events/src/Application/Events/LegacyEventPartiesCanonicalizationService.php`
- `../laravel-app/scripts/check_event_parties_cutover.sh`
- `../laravel-app/scripts/reconcile_legacy_event_parties.sh`

---

## Scope

- Make `event_parties` the canonical source for linked non-location account-profile identity in immersive event detail and every touched event/runtime caller in this lane.
- Remove `artists`/`venue` merge logic from `linked_account_profiles` derivation; `linked_account_profiles` must derive from `event_parties` metadata only.
- Remove `venue` from `event_parties`; venue/local semantics remain canonical through `location + place_ref`, and the public event payload may keep `venue` only as a read projection derived from that local contract.
- Remove direct runtime dependence on legacy `artists` for the touched event/public/admin flows, accepting break/fix where current callers still rely on it.
- Guarantee that event CRUD preserves `event_parties[].metadata.slug` and the rest of canonical linked-profile metadata on create and update.
- Cut the tenant-admin event write path over to canonical `event_parties` transport instead of `artist_ids` as the source-of-truth payload for linked profiles.
- Add a transitional legacy-repair mechanism that finds tenant events with legacy artist data and missing `event_parties[].metadata.slug`, then repairs `event_parties` from canonical account-profile records.
- Expose a provisional Tenant Admin Events action labeled `Verificar Eventos Legados` as a simple one-button flow that opens a dialog with the detected legacy-error count, offers a single repair action, and then reports how many events were corrected.
- Establish a reusable server-side migration service/command/script so the admin action and operational scripts call the same reconciliation logic.
- Verify that event/occurrence update flows preserve repaired/canonical `event_parties` metadata and keep occurrence projections synchronized.
- Guarantee that every event created or updated by the canonical CRUD path remains valid under the legacy-validity checker (`legacy_event_parties/summary`) instead of reintroducing invalid schema/data shapes.
- Close the CRUD lifecycle contract with deterministic tests covering parent-event state, occurrence state, admin-list visibility, and public-agenda visibility after create/update/archive/delete transitions.
- Revisit the Account Profile agenda counterpart-exclusion rule so it no longer assumes venue can arrive via `event_parties`.

## Out of Scope

- Untouched modules/surfaces that are not on the execution path of this lane, unless they break due to the canonical cutover and therefore become mandatory repair work.
- Redesign of the tenant-admin event form UX beyond what is required to emit canonical `event_parties`.
- Automatic background resync on future `AccountProfile.slug` changes after event creation; that remains tracked in `TODO-vnext-account-profile-slug-projection-resync.md`.
- Multilingual display-label work or unrelated event card polish.
- Silent request-time repair/read fallback for missing slug.

---

## Decision Baseline (Frozen)

- `D-01`: `event_parties` is the canonical source-of-truth for event-linked non-location account profiles; `artists` is legacy data and must not drive touched runtime/admin behavior after this cutover.
- `D-02`: `linked_account_profiles` must derive exclusively from `event_parties` metadata; it must not be repaired by merging legacy `artists` payloads in the read path, and it must not treat venue as an event-party source.
- `D-03`: Missing `event_parties[].metadata.slug` is invalid event composition data and must be corrected at write/migration time, not papered over in Flutter or request-time query logic.
- `D-04`: Tenant-admin event writes move to canonical `event_parties` transport in this lane; `artist_ids` stops being the active write contract.
- `D-05`: This lane accepts break/fix on affected callers instead of preserving compatibility for touched paths that still depend on `artists`.
- `D-06`: The legacy-repair mechanism is explicit and operator-triggered; no read-time fallback or automatic public-path mutation is allowed.
- `D-07`: The provisional admin action is allowed for V1 because it is a bounded transition aid; its removal belongs to a later cleanup lane.
- `D-08`: The legacy repair flow must use canonical account-profile resolution by persisted ids (`artists[].id`, `event_parties[].party_ref_id`, `place_ref.id`) to rebuild missing event-party metadata, including `slug`.
- `D-09`: Occurrence projection sync must preserve canonical event-party metadata after event repair/update so the next read no longer needs enrichment.
- `D-10`: The admin transition action and the operator script must reuse a single backend reconciliation implementation rather than duplicating migration logic in controller and script code.
- `D-11`: Venue/local semantics remain canonical through `location + place_ref`; `venue` may remain as a derived read projection, but `party_type=venue` must not be part of the canonical composition contract.
- `D-12`: The admin correction interface stays intentionally simple: one entry action, one preflight dialog with count, one repair action, and one result summary.
- `D-13`: The legacy reconciliation implementation must be idempotent; running it twice after a successful repair must produce zero additional mutations.
- `D-14`: The legacy reconciliation flow must support a preflight count path that performs no mutation and returns the exact candidate/error summary used by the admin dialog before repair.
- `D-15`: Production-safety validation is mandatory: tests must prove that already-canonical events remain unchanged, only explicit legacy candidates mutate, and occurrence sync does not regress after repair.
- `D-16`: CRUD lifecycle correctness is part of this lane's contract. Create/update/delete/archive/publish transitions must be covered by tests against both `events` and `event_occurrences`; manual runtime discovery is not sufficient.
- `D-17`: Canonical create/update operations must not generate data that the legacy-validity checker would later classify as invalid. A newly created or updated event that passes write validation must remain valid in the summary/repair pipeline.
- `D-18`: Admin list filters (`status`, `archived`) and public agenda visibility must be proven against real backend state transitions, not only fake Flutter repositories.
- `D-19`: Legacy summary/repair validity for tenant-admin events is not limited to `event_parties`; any persisted shape that would make `formatManagementEvent()` produce a non-parseable admin payload counts as invalid and must be surfaced or repaired explicitly.
- `D-20`: Event map-poi cleanup must treat a missing source event as stronger than inactive/expired state; orphaned `ref_type=event` projections are deleted by periodic maintenance even if the original delete job was missed.

---

## Plan Review Gate (Big)

### Section P-01 — Source-of-truth hard cutover
- Severity: `high`
- Evidence: current Laravel `EventQueryService` still derives `linked_account_profiles` by merging `event_parties`, `artists`, and `venue`, while Flutter admin writes `artist_ids`.
- Why now: the public immersive event-detail contract is already strict on `slug`; keeping hybrid ownership continues to create invalid payloads and dead cards, and the user explicitly chose to break/fix rather than preserve the old shape.
- Recommended direction: cut artist-linked profile identity ownership over to canonical `event_parties`, keep venue on the location/place-ref lane, and repair affected callers now.

### Section P-02 — Legacy repair strategy
- Severity: `high`
- Evidence: historical events exist with `artists._id` data but missing `event_parties[].metadata.slug`.
- Why now: runtime no longer tolerates missing slug, so historical invalid data must be repaired explicitly.
- Options:
  - `A`: request-time repair in read path. Effort low, risk high, blast radius high, maintenance bad. **Reject**.
  - `B`: one-off manual DB patch outside product. Effort medium, risk medium, blast radius medium, maintenance weak. Useful only as emergency ops, not productized.
  - `C`: canonical migration service + command + provisional admin trigger. Effort medium/high, risk controlled, blast radius bounded, maintenance acceptable. **Recommended**.

### Section P-03 — Admin write contract
- Severity: `high`
- Evidence: Flutter tenant-admin request encoder still emits `artist_ids`, and Laravel validation still accepts `artist_ids`.
- Why now: if admin keeps writing legacy inputs, the system will continue to recreate the same invalid data shape.
- Recommended direction: move the admin flow to `event_parties` transport in the same lane and stop using `artist_ids` as the canonical write contract.

### Section P-04 — Venue contract separation
- Severity: `high`
- Evidence: the current model duplicates venue semantics across `place_ref`, `venue`, and `event_parties`.
- Why now: if venue remains in `event_parties`, the canonical split between composition and location stays incoherent and continues to leak into Account Profile agenda rendering.
- Recommended direction: remove venue from `event_parties`, preserve `place_ref` as canonical local source, and keep `venue` only as a read projection.

### Section P-05 — Caller break/fix blast radius
- Severity: `high`
- Evidence: multiple event/public/admin helpers still read `artists` directly.
- Why now: this lane now intentionally accepts caller breakage as the signal to repair toward the canonical format.
- Recommended direction: repair all affected touched callers in the same lane instead of preserving hybrid behavior.

### Section P-06 — Operational safety of the admin trigger
- Severity: `medium`
- Evidence: a full-tenant legacy scan can be expensive if executed directly in a public/runtime path.
- Why now: the button is intentionally provisional and should not normalize expensive request-path scans.
- Recommended direction: encapsulate the reconciliation in a backend service reusable from a script/command and from a bounded tenant-admin trigger, returning a summary result.

### Section P-07 — Production migration safety
- Severity: `high`
- Evidence: the reconciliation action is expected to run against production data and can mutate historical events plus their occurrence projections.
- Why now: correctness alone is insufficient; the migration must prove non-regression on already-valid events and idempotence across reruns.
- Recommended direction: build the reconciliation around a deterministic candidate selector + idempotent repair service + explicit summary contract, and back it with destructive-safety tests before any production use.

## Failure Modes & Edge Cases

- Events with legacy `artists` but empty or malformed account-profile ids must not produce partial/invalid `event_parties` rows silently.
- Events with valid `event_parties` but stale legacy `artists` should not regress during repair.
- Venue/location semantics must remain correct after `party_type=venue` is removed from canonical composition.
- Multiple artist parties must preserve deterministic ordering and avoid duplicate event-party rows.
- Callers that still read `artists` directly may fail after the cutover; those failures are in-scope and must be repaired instead of reverted.
- The admin transition action must surface partial-failure counts instead of claiming a clean migration when some events could not be repaired.
- The preflight count must not mutate data.
- A second repair run against already-repaired data must report zero mutations.
- Canonical events with valid artist `event_parties` must remain byte-for-byte equivalent in the repaired surfaces.
- Occurrence sync must not leave event updated but occurrences stale.
- Removing read-time merge logic must not break event detail for freshly created canonical events.
- A future event updated through CRUD must not silently become invisible to the public agenda unless its publication transition explicitly makes it unpublished.
- `Draft + Ativos`, `Draft + Arquivados`, `Todos + Arquivados`, and `Published + Ativos` must all resolve deterministically for the same dataset; the admin list must not hide records because parent/occurrence state drifted without coverage.
- The archived admin filter payload must decode cleanly even when archived rows have older/partial shapes; technical error envelopes must stay human-readable.
- `legacy_event_parties/summary` counts must be explainable by contract: the test suite must prove what is scanned and why, instead of letting operators infer it from production data.

## Uncertainty Register

- Assumption: `artists[].id` is present on the legacy events that need repair, allowing canonical account-profile lookup.
- Assumption: tenant-admin event forms can emit canonical `event_parties` without redesigning selection UX from scratch.
- Unknown: exact set of touched callers that will fail once runtime/admin logic stops relying on `artists`.
- Unknown: whether any untouched integrations outside this lane still assume `party_type=venue` exists inside `event_parties`.
- Unknown: whether the archived-list failure is caused by malformed backend payload, a stale archived row shape, or insufficient Flutter decode hardening.
- Confidence: `medium`

---

## Touched Surfaces

- `../foundation_documentation/todos/active/vnext/TODO-v1-event-parties-canonicalization-and-legacy-migration.md`
- `../foundation_documentation/modules/events_module.md`
- `../foundation_documentation/modules/flutter_client_experience_module.md`
- `../foundation_documentation/modules/partner_catalog_and_offer_module.md`
- `../foundation_documentation/endpoints_mvp_contracts.md` (if endpoint added)
- `lib/domain/tenant_admin/**`
- `lib/infrastructure/dal/dao/tenant_admin/**`
- `lib/infrastructure/repositories/tenant_admin/**`
- `lib/presentation/tenant_admin/events/**`
- `lib/presentation/tenant_public/partners/**` if Account Profile agenda counterpart exclusion needs cleanup
- `lib/infrastructure/dal/dto/schedule/event_dto.dart` only if linked-profile event payload assumptions change materially
- `../laravel-app/packages/belluga/belluga_events/src/Application/Events/**`
- `../laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/**`
- `../laravel-app/app/Integration/Events/**`
- `../laravel-app/tests/Feature/Events/**`
- `test/presentation/tenant_admin/events/**`
- `test/infrastructure/dal/dao/tenant_admin/**`

## Ordered Steps

1. Add fail-first Laravel tests that prove canonical `event_parties` ownership in create/update/detail, prove venue is no longer modeled as an event party, and fail when linked-profile derivation still depends on legacy `artists` merge or when `artist_ids` remains the active write contract.
2. Add fail-first Laravel migration-safety tests for the reconciliation service/script: dry-run count only, repair of invalid legacy events, unchanged canonical events, idempotent rerun, partial-failure accounting, and preserved occurrence sync.
3. Add fail-first Laravel tests for the full CRUD lifecycle matrix: create/update/delete/archive/status transitions must assert parent-event fields plus mirrored occurrence fields (`publication.status`, `is_event_published`, `is_active`, `deleted_at`) and the expected admin/public visibility outcomes.
4. Add fail-first Laravel tests proving that canonical create/update operations remain valid under the legacy summary/repair selector (no newly written event may show up as an invalid legacy candidate unless it was intentionally seeded that way).
5. Add fail-first Flutter/admin tests for canonical event write transport, archived filter decode/error handling, touched caller break/fix behavior, Account Profile agenda exclusion cleanup, and the provisional `Verificar Eventos Legados` action.
6. Diagnose and fix the archived-list failure path (`Todos + Arquivados`) against real payload shape, then pin the fix with backend + Flutter contract tests.
7. Cut Laravel CRUD/event-detail logic over so artist-linked/runtime/admin paths depend on `event_parties`, remove venue from canonical event-party composition, and remove legacy `artists` dependence from touched callers.
8. Introduce the shared reconciliation service + script/command + admin trigger, then wire the provisional Tenant Admin button to execute the preflight count + repair flow and show a result summary.
9. Cut Flutter tenant-admin event write encoding over to canonical `event_parties` transport, remove direct `artist_ids` submission from the active write path, and clean up the Account Profile agenda counterpart exclusion if it still assumes venue can arrive as a counterpart.
10. Re-run focused Laravel + Flutter suites and `fvm dart analyze --format machine`.
11. Promote stable contract outcomes into canonical module docs and endpoint contracts.

## Test Strategy

- `test-first`

## Fail-First Targets

- Event create/update still treats `artist_ids` as the canonical write surface instead of materializing canonical `event_parties`.
- Event detail or other touched callers still require legacy `artists` merge to stay complete.
- Venue still exists as canonical `party_type=venue` instead of staying on the `location + place_ref` lane.
- Legacy events with `artists.id` and missing `event_parties[].metadata.slug` cannot be repaired by the transition flow.
- The reconciliation dry-run mutates data or reports counts inconsistent with the repair phase.
- Canonical events are modified by the reconciliation even though they were already valid.
- A second reconciliation run still reports mutations on already-repaired data.
- Tenant Admin Events screen cannot trigger the provisional legacy-repair action or report the result clearly.
- Occurrence projections lose canonical slug metadata after a repaired event is updated.
- A create/update request produces an event that later appears as invalid in `legacy_event_parties/summary`.
- A future event update unintentionally flips the parent into `draft` or `deleted_at != null` without the occurrences/admin/public surfaces reconciling coherently.
- The archived admin filter (`archived=1`) returns a payload that Flutter cannot decode, surfacing a technical error instead of a stable list/error state.
- The admin list matrix returns contradictory results for the same dataset (`Draft + Ativos` empty, `Todos + Arquivados` exploding) without a failing contract test.

## Definition of Done

- Canonical linked-profile event detail flows derive from `event_parties`, not legacy `artists` merge.
- Venue no longer participates in canonical `event_parties`; local semantics remain on `location + place_ref`, with `venue` only as a read projection.
- New event CRUD/update paths preserve canonical `event_parties` metadata including slug.
- New event CRUD/update paths also preserve legacy-summary validity: freshly written canonical events do not re-enter the invalid-candidate pool.
- Touched runtime/admin callers no longer depend on legacy `artists` as behavior-driving input.
- Tenant Admin Events exposes a provisional `Verificar Eventos Legados` action that repairs legacy events using the shared backend reconciliation service/script.
- The admin correction interface remains simple: button -> count dialog -> repair -> result summary.
- The reconciliation service/script is idempotent and proven not to mutate already-canonical events.
- Historical events that satisfy the legacy pattern can be repaired without request-time fallback.
- CRUD lifecycle tests explicitly prove parent/occurrence/admin/public behavior across create/update/archive/delete/publication transitions.
- Archived and active admin list filters are validated against real backend payloads, and Flutter handles archived payload/error shapes deterministically.
- Focused Laravel/Flutter tests and `fvm dart analyze --format machine` pass.

## Validation Steps

- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/...`
- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter=\"legacy|archive|publish|update|reconciliation\"`
- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/... --filter=legacy`
- `fvm flutter test test/presentation/tenant_admin/events/...`
- `fvm flutter test test/infrastructure/dal/dao/tenant_admin/...`
- `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart`
- `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/...`
- `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_admin/events/tenant_admin_event_types_list_screen_test.dart`
- `bash scripts/check_event_parties_cutover.sh`
- `bash ../laravel-app/scripts/check_event_parties_cutover.sh`
- `fvm dart analyze --format machine`
