# TODO (Store Release): Event Content Save Sanitization

**Classification note (2026-04-18):** this lane is release-critical. The store-release app must not imply that arbitrary event-description HTML is accepted and persisted.

**Scope authority note (2026-04-18):** this TODO is the direct delivery authority for canonical sanitization of event `content` on the save path. `foundation_documentation/todos/completed/TODO-v1-event-detail-about-rich-media-contract.md` remains closed for the contract decision itself; this lane exists because the implementation still needs explicit backend-guaranteed sanitization plus frontend/editor alignment.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Local-Implemented. The product rule is frozen, save-time sanitization is implemented, the Flutter editor/runtime are aligned, emojis are preserved, and the relevant validation suite is green; canonical lane promotion is the only remaining work for this slice.
**Owners:** Flutter Team, Laravel Team
**Goal:** make event-description content canonical and safe by sanitizing unsupported markup at save time on the backend, while also sanitizing/preventing unsupported markup on the frontend so the editing UX never suggests false acceptance.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The rich-content contract for event `Sobre` is no longer open-ended:

- arbitrary HTML is not accepted,
- media-only/non-text HTML is not valid `Sobre`,
- unsupported tags must not survive as canonical content.

What is still missing is the implementation guarantee. Today the public runtime decides `Sobre` presence through stripped text, but that is only a read-time symptom. The store-release requirement is stronger: the write/save path must sanitize `event.content` into an approved canonical subset, and the frontend must avoid showing users markup that the platform will later discard.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** the contract decision is already frozen and the remaining gap is concrete, security-relevant, and release-facing.
- **Direct-to-TODO rationale:** safe. This is a bounded execution lane derived directly from a resolved decision and current implementation gap.

## Contract Boundary

- This TODO owns canonical sanitization/canonicalization of event `content` on the save path.
- Backend sanitization is the authoritative guarantee and must define the persisted allowed subset.
- Frontend/editor sanitization and UX constraints are required too, so unsupported tags do not appear accepted before save.
- This TODO includes public/detail/admin contract alignment, but it does **not** reopen whether media-only HTML should count as valid `Sobre`. That decision is already frozen as "no".

## Delivery Status Canon

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** promote the reconciled branch set to `dev`, update the promotion evidence, and then advance the TODO to `Lane-Promoted`.

## References

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/completed/TODO-v1-event-detail-about-rich-media-contract.md`
- `foundation_documentation/todos/completed/TODO-v1-immersive-event-detail-dynamic-profile-category-tabs.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-admin-form-field-ux-upgrade.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/todos/completed/TODO-v1-push-delivery-consolidated.md`

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Decision promotion targets:**
  - `events_module.md` read/write event-content contract
  - `flutter_client_experience_module.md` event-detail/editor UX contract
  - `tenant_admin_module.md` admin-form content expectations if needed

## Ecosystem Impact Analysis

- **Current classification:** `Project-Local`
- **Why:** this is a release-specific event-content contract cleanup for this downstream project's admin/public behavior.
- **Reuse doctrine note:** backend/frontend sanitizer patterns may later inform shared rich-text rules, but this lane is not a package extraction exercise.

## Decision Baseline (Frozen 2026-04-18)

- [x] `D-01` Event `content` does not accept arbitrary HTML; only an approved canonical subset may persist.
- [x] `D-02` Unsupported tags must be removed on save, with backend sanitization as the authoritative guarantee.
- [x] `D-03` Frontend/editor behavior must also sanitize or block unsupported tags so users do not see an illusion of acceptance.
- [x] `D-04` Media-only/non-text markup does not count as valid `Sobre`.
- [x] `D-05` Public event detail may continue to gate `Sobre` by real textual content after canonicalization; it must not become the place where unsupported markup is silently "accepted then ignored".
- [x] `D-06` Admin/public payloads and persisted content must stay aligned; the write path defines the canonical subset, and the read path consumes only that subset.

## Scope

- [x] Freeze the approved event-content HTML/rich-text subset for persisted `content`.
- [x] Implement backend save-time sanitization/canonicalization for event create/update flows.
- [x] Ensure backend strips unsupported tags/attributes and normalizes the persisted markup deterministically.
- [x] Implement frontend/editor sanitization or prevention so unsupported tags do not appear accepted before save.
- [x] Align admin preview/edit UX with the approved subset so discarded markup is not misleadingly shown as valid content.
- [x] Keep public `Sobre` rendering and tab gating aligned with the sanitized persisted contract.
- [x] Add automated coverage across backend write path and Flutter editor/runtime behavior.
- [x] Update canonical docs once the implementation is real.

## Out of Scope

- [ ] Reopening the decision about media-only HTML validity.
- [ ] Generic cross-product CMS/rich-text redesign.
- [ ] Unrelated event-detail visual changes.
- [ ] Arbitrary embedded media support beyond the approved canonical subset.

## Dependencies & Sequencing

- [x] `DEP-01` `foundation_documentation/todos/completed/TODO-v1-event-detail-about-rich-media-contract.md` remains the frozen decision source and stays closed.
- [x] `DEP-02` Tenant-admin event content editing and public event-detail consumption moved together under the same sanitized subset.
- [x] `DEP-03` Canonical module docs were updated before this TODO moved to `promotion_lane/` so the accepted subset is explicit authority.

## Execution Tracks

### A) Backend Canonicalization

- [x] Define the persisted allowlist/subset for event `content`.
- [x] Sanitize/normalize incoming event content on create/update.
- [x] Strip unsupported tags/attributes deterministically.
- [x] Prove that persisted content cannot retain unsupported markup.

### B) Frontend Editor / UX Alignment

- [x] Prevent or sanitize unsupported markup in the event-content editing flow.
- [x] Ensure the editor/preview does not create the illusion that unsupported tags will be preserved.
- [x] Keep runtime rendering aligned with the same subset the backend persists.

### C) Validation And Authority

- [x] Add backend tests for accepted subset, stripped tags, and text-empty outcomes.
- [x] Add Flutter tests for editor behavior and `Sobre` gating against the sanitized contract.
- [x] Promote the resulting rules to the canonical module docs.

## Acceptance Criteria

- [x] Backend event create/update sanitizes `content` into the approved canonical subset.
- [x] Unsupported tags/attributes do not persist in stored event content.
- [x] Frontend editing flow does not imply unsupported markup is accepted.
- [x] Public event detail renders only canonical sanitized content and omits `Sobre` when sanitized content has no valid textual body.
- [x] Admin/public/docs authority all describe the same contract.

## Definition of Done

- [x] Event `content` save behavior is canonical, sanitized, and backend-guaranteed.
- [x] Frontend/editor behavior no longer creates a false acceptance signal for unsupported tags.
- [x] Public/detail behavior consumes the sanitized persisted contract without ambiguous fallback semantics.

## Validation Steps

- [x] Laravel automated: create/update payloads with allowed, unsupported, mixed, and media-only content.
- [x] Laravel automated: persisted content is canonicalized and stripped as expected.
- [x] Flutter automated: editor behavior and event-detail `Sobre` gating align with the sanitized persisted contract.
- [x] Manual smoke: tenant-admin event edit/save plus tenant-public `/agenda/evento/:slug` rendering for valid text-rich, mixed, unsupported-tag, and media-only inputs.

## Validation Evidence

- Laravel: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter='test_event_create_sanitizes_content_html_subset_and_preserves_emojis|test_event_create_strips_media_only_content_to_empty_string|test_event_update_sanitizes_plain_text_content_with_line_breaks' --stop-on-failure` -> `3 passed`.
- Flutter: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` -> passed, including editor normalization and `Sobre` omission/fallback coverage.
- Analyzer: `fvm dart analyze --format machine` -> exit `0`.
- Published build: `./scripts/build_web.sh ../web-app dev` -> succeeded and republished the reconciled bundle used by browser validation.
- Published readonly smoke: `NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `5 passed`.
- Published manual mutation smoke (2026-04-19): Playwright/Chromium validated the published tenant on `https://guarappari.belluga.space` by authenticating a landlord test user in-browser, patching `content` on tenant-admin event `evento-longo`, refetching the admin payload, and checking the public `/agenda/evento/evento-longo` route for four cases: valid rich text, mixed unsupported tags plus emoji, unsupported-tag text only, and media-only markup. Response payload, persisted payload, and public rendering matched the canonical contract in all four cases, emojis were preserved, `Sobre` disappeared for media-only markup, and the original event content was restored after the run. Screenshots were captured under `tools/flutter/web_app_smoke_runner/test-results/event-content-*.png`.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:orchestrator/store-release-precritical-flutter`, `laravel-app:orchestrator/store-release-precritical-laravel`, `belluga_now_docker:orchestrator/store-release-precritical-root`, `foundation_documentation:orchestrator/store-release-precritical-docs`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Backend event-content save sanitization | `orchestrator/store-release-precritical-laravel` | `not-published` | `not-published` | `not-published` | `Merged into reconciliation branch; safe-runner tests green and published browser mutation smoke passed` |
| Frontend editor sanitization / prevention | `orchestrator/store-release-precritical-flutter` | `not-published` | `not-published` | `not-published` | `Merged into reconciliation branch; widget tests and analyzer green` |
| Docs/tests/runtime alignment | `orchestrator/store-release-precritical-root` + `foundation_documentation:orchestrator/store-release-precritical-docs` | `not-published` | `not-published` | `not-published` | `Reconciled local build published; readonly smoke and TODO-specific published mutation smoke green` |
