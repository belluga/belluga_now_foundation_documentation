# TODO (Store Release): Event Content Save Sanitization

**Classification note (2026-04-18):** this lane is release-critical. The store-release app must not imply that arbitrary event-description HTML is accepted and persisted.

**Scope authority note (2026-04-18):** this TODO is the direct delivery authority for canonical sanitization of event `content` on the save path. `foundation_documentation/todos/completed/TODO-v1-event-detail-about-rich-media-contract.md` remains closed for the contract decision itself; this lane exists because the implementation still needs explicit backend-guaranteed sanitization plus frontend/editor alignment.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. The product rule is frozen, but store-release still needs explicit save-time sanitization and frontend/editor prevention so unsupported tags are neither persisted nor visually implied as accepted.
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

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Content-Safety`
- **Next exact step:** define the approved event-content allowlist/canonical subset, then implement backend save-time sanitization and frontend/editor prevention with aligned tests.

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

- [ ] Freeze the approved event-content HTML/rich-text subset for persisted `content`.
- [ ] Implement backend save-time sanitization/canonicalization for event create/update flows.
- [ ] Ensure backend strips unsupported tags/attributes and normalizes the persisted markup deterministically.
- [ ] Implement frontend/editor sanitization or prevention so unsupported tags do not appear accepted before save.
- [ ] Align admin preview/edit UX with the approved subset so discarded markup is not misleadingly shown as valid content.
- [ ] Keep public `Sobre` rendering and tab gating aligned with the sanitized persisted contract.
- [ ] Add automated coverage across backend write path and Flutter editor/runtime behavior.
- [ ] Update canonical docs once the implementation is real.

## Out of Scope

- [ ] Reopening the decision about media-only HTML validity.
- [ ] Generic cross-product CMS/rich-text redesign.
- [ ] Unrelated event-detail visual changes.
- [ ] Arbitrary embedded media support beyond the approved canonical subset.

## Dependencies & Sequencing

- [x] `DEP-01` `foundation_documentation/todos/completed/TODO-v1-event-detail-about-rich-media-contract.md` remains the frozen decision source and stays closed.
- [ ] `DEP-02` Tenant-admin event content editing and public event-detail consumption must move together under the same sanitized subset.
- [ ] `DEP-03` Canonical module docs must be updated before this TODO closes so the accepted subset is explicit authority.

## Execution Tracks

### A) Backend Canonicalization

- [ ] Define the persisted allowlist/subset for event `content`.
- [ ] Sanitize/normalize incoming event content on create/update.
- [ ] Strip unsupported tags/attributes deterministically.
- [ ] Prove that persisted content cannot retain unsupported markup.

### B) Frontend Editor / UX Alignment

- [ ] Prevent or sanitize unsupported markup in the event-content editing flow.
- [ ] Ensure the editor/preview does not create the illusion that unsupported tags will be preserved.
- [ ] Keep runtime rendering aligned with the same subset the backend persists.

### C) Validation And Authority

- [ ] Add backend tests for accepted subset, stripped tags, and text-empty outcomes.
- [ ] Add Flutter tests for editor behavior and `Sobre` gating against the sanitized contract.
- [ ] Promote the resulting rules to the canonical module docs.

## Acceptance Criteria

- [ ] Backend event create/update sanitizes `content` into the approved canonical subset.
- [ ] Unsupported tags/attributes do not persist in stored event content.
- [ ] Frontend editing flow does not imply unsupported markup is accepted.
- [ ] Public event detail renders only canonical sanitized content and omits `Sobre` when sanitized content has no valid textual body.
- [ ] Admin/public/docs authority all describe the same contract.

## Definition of Done

- [ ] Event `content` save behavior is canonical, sanitized, and backend-guaranteed.
- [ ] Frontend/editor behavior no longer creates a false acceptance signal for unsupported tags.
- [ ] Public/detail behavior consumes the sanitized persisted contract without ambiguous fallback semantics.

## Validation Steps

- [ ] Laravel automated: create/update payloads with allowed, unsupported, mixed, and media-only content.
- [ ] Laravel automated: persisted content is canonicalized and stripped as expected.
- [ ] Flutter automated: editor behavior and event-detail `Sobre` gating align with the sanitized persisted contract.
- [ ] Manual smoke: tenant-admin event edit/save plus tenant-public `/agenda/evento/:slug` rendering for valid text-rich, mixed, unsupported-tag, and media-only inputs.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `belluga_now_docker:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Backend event-content save sanitization | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Frontend editor sanitization / prevention | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Docs/tests/runtime alignment | `pending` | `pending` | `pending` | `pending` | `Pending` |
