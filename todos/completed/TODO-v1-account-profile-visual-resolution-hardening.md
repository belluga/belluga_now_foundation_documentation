# TODO (V1): Account Profile Visual Resolution Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Completed (`active-lane cleanup synced on 2026-04-09 after delivery confirmation`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Previously-Approved-Followup`, `Automated-Validated`, `Closure-Synced`
**Next exact step:** None. Archived to `todos/completed` on `2026-04-09`.
**Owners:** Flutter Team
**Objective:** Eliminate inconsistent public `Account Profile` visual resolution by introducing a shared client-side resolver/materializer for `profile_type` data sourced from the app bootstrap registry, then applying the approved surface-specific media precedence rules, shared hero/Discovery identity composition, consistent canonical type-label usage, avatar-badge identity behavior, and theme-source selection without coupling `AccountProfileModel` directly to `AppData`.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `../foundation_documentation/modules/account_profile_catalog_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for controller/view separation, route ownership, shared widget behavior, and test discipline.
- `account_profile_catalog_module.md`: authoritative for public account-profile identity semantics and route continuity.

### Decision Consolidation Targets

- Promote durable shared visual-resolution rules to `../foundation_documentation/modules/flutter_client_experience_module.md` if this TODO changes reusable widget/theme behavior across discovery/home/detail surfaces.
- Promote durable public account-profile identity/fallback rules to `../foundation_documentation/modules/account_profile_catalog_module.md` only if this TODO changes stable consumer-facing rendering semantics.

---

## References

- `../foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- `../foundation_documentation/todos/completed/TODO-v1-screen-public-account-profile-detail-polish.md`
- `../foundation_documentation/todos/completed/TODO-v1-screen-discovery-performance-hardening.md`
- `lib/domain/partners/account_profile_model.dart`
- `lib/domain/partners/profile_type_registry.dart`
- `lib/presentation/shared/visuals/profile_type_visual_resolver.dart`
- `lib/presentation/tenant_public/discovery/widgets/discovery_nearby_row.dart`
- `lib/presentation/tenant_public/discovery/widgets/discovery_partner_card.dart`
- `lib/presentation/tenant_public/partners/routes/partner_detail_route.dart`

---

## Scope

- Introduce a shared Flutter-side resolver/materializer that turns `AccountProfileModel.type` plus `AppData.profileTypeRegistry` into a resolved summary (`label`, `visual`, and compact visual fallback inputs) without making `AccountProfileModel` depend on `AppData`.
- Replace direct/duplicated public lookup paths that manually resolve `profile_type` label/visual in screens/widgets/controllers where this shared resolver is sufficient.
- Standardize account-profile visual precedence per surface family:
  - surface/background media: `cover > avatar > type visuals`
  - compact rows: `avatar > cover > type visuals`
  - shared identity avatar surface: `avatar` when present, otherwise `type visuals`
- Remove the nearby-row halo/ring and use the resolved fallback visual instead of the generic storefront fallback when `type visuals` are available.
- Update the Discovery account-profile card so the image-area type label is removed and the content area follows the same shared identity pattern approved for hero/card surfaces: leading avatar surface, profile name with a lighter visual weight than the hero variant, no textual type label, and no standalone type chip container.
- Move the type visual from a post-name ornament into a badge overlay anchored on the avatar surface when a real avatar exists.
- When no real avatar exists, let the shared identity avatar surface resolve from the canonical `type visuals` fallback instead of disappearing.
- Ensure any remaining textual `profile_type` display in touched public surfaces uses the canonical registry `label` field, never the raw `type` token/slug fallback unless the registry is genuinely unavailable.
- Align `PartnerDetailRoute` theme-source selection with the final hero visual actually used by the account-profile detail surface, including `type visual` color fallback when no image-backed hero source exists.
- Keep the account-profile detail hero visual semantics intact while moving type-summary lookup out of ad-hoc controller/widget code.
- Introduce a shared `AccountProfile` identity widget/resolver pair so hero and Discovery card do not drift again.

## Out of Scope

- Backend/API contract changes for `favorites`, `account_profiles`, or `taxonomy_terms`.
- `taxonomy_term_summaries` materialization or public taxonomy catalog work.
- Favorites-strip fallback consistency when the current favorites payload does not yet expose `cover_url` / `profile_type`.
- Discovery filter-chip redesign beyond what falls out from touched shared visual tokens.
- New route/scope changes, new endpoints, or persistence/schema updates.

---

## Decision Baseline (Frozen)

- `D-01`: `profile_type` resolution in public Flutter surfaces must come from the app bootstrap registry, not per-request backend aggregation.
- `D-02`: `AccountProfileModel` stays free of `AppData` ownership; any type-summary materialization happens in a shared resolver outside the model.
- `D-03`: Surface/background media uses `cover > avatar > type visuals`.
- `D-04`: Compact rows (for example `Perto de você`) use `avatar > cover > type visuals`.
- `D-05`: Shared identity blocks always render a leading avatar surface; it uses the real avatar when available and otherwise falls back to the canonical `type visuals`.
- `D-06`: When a real avatar exists, the `type visual` moves into a badge overlay on that avatar surface instead of appearing after the profile name.
- `D-07`: `PartnerDetailRoute` theme derivation must follow the final visual source of the detail hero; if no image source exists, use the resolved `type visual` color as theme seed.
- `D-08`: This TODO remains Flutter-only. Favorites payload enrichment and taxonomy label summaries are follow-up work, not hidden scope in this lane.
- `D-09`: In the Discovery account-profile card, the old uppercase textual type eyebrow is removed; the content area reuses the same identity language as the hero/card shared identity block.
- `D-10`: The canonical public profile-type display field is backend/app-data registry key `label`; any lowercase/raw-token rendering in touched public surfaces is a bug unless the registry is missing and an explicit fallback is engaged.
- `D-11`: Public widgets/screens must not fall back to generic storefront imagery/icons when a canonical `type visual` is available.
- `D-12`: Discovery-card identity uses the same shared widget as the hero, but with a toned-down title weight appropriate for the card surface.

**Last confirmed truth:** `2026-04-04` shared account-profile visual resolution is implemented in Flutter via a shared resolver + identity widget; hero/discovery/event-linked-profile surfaces now share the same identity language, type visuals badge the avatar when a real avatar exists, type visuals become the avatar surface fallback when a real avatar is missing, and the shared identity block now renders `avatar + name` in the first row with supporting chips wrapped below.

---

## Plan Review Gate (Medium)

### Issue Card P-01 — Repeated `profile_type` lookup logic in hot UI surfaces
- Severity: `medium`
- Evidence: `account_profile_detail_controller.dart`, `discovery_partner_card.dart`, `discovery_nearby_row.dart`, `partner_detail_route.dart`
- Why now: the same conceptual lookup is repeated with different fallback policies, producing inconsistent labels, icons, images, and theme sources.
- Option A: keep using ad-hoc registry lookups per widget/controller.
- Option B (recommended): centralize a shared resolver/materializer and migrate touched public surfaces to it.
- Option C: move all resolution to backend payloads now.
- Tradeoff:
  - A: lowest effort, preserves inconsistency.
  - B: medium effort, immediate consistency, no backend hot-path cost.
  - C: larger scope, contract changes, and unnecessary coupling for `profile_type` because bootstrap registry already exists.

### Issue Card P-02 — Theme derivation drift
- Severity: `medium`
- Evidence: `partner_detail_route.dart` currently uses `cover` only.
- Why now: surfaces can render `type visual` fallback while theme remains unrelated, producing coherence drift.
- Option A (recommended): route theme chooses between final image source and resolved `type visual` color seed.
- Option B: leave theme cover-only.
- Option C: redesign hero media precedence at the same time.
- Tradeoff:
  - A: bounded and coherent.
  - B: keeps visible mismatch.
  - C: wider visual redesign than needed.

### Issue Card P-03 — Terms/categories still show slug-like values
- Severity: `medium`
- Evidence: public account-profile payload returns raw `taxonomy_terms` and current model collapses them to strings.
- Why now: this is related conceptually but not solvable cleanly with the same mechanism as `profile_type`.
- Option A: fetch all taxonomies/terms client-side and resolve labels locally.
- Option B: aggregate term labels per request.
- Option C (recommended follow-up, not this TODO): materialize additive `taxonomy_term_summaries` in backend documents/payloads.
- Tradeoff:
  - A: runtime `fetchAll` anti-pattern.
  - B: acceptable transition, not target state.
  - C: best read-path performance, but requires backend work outside this lane.

### Issue Card P-04 — Discovery card still exposes the old type eyebrow and lacks the approved identity layout
- Severity: `low`
- Evidence: `discovery_partner_card.dart` still renders `typeLabel.toUpperCase()` above the profile name.
- Why now: that surface is part of the same public visual-resolution inconsistency; keeping the old eyebrow would undermine the new shared rule.
- Option A (recommended): remove the textual eyebrow and reuse the approved shared identity layout: leading avatar surface, profile name, and type visual applied as avatar badge/fallback instead of a separate label or post-name chip.
- Option B: keep the eyebrow and only fix fallback behavior.
- Option C: redesign the entire discovery card at once.
- Tradeoff:
  - A: bounded and aligned with approved identity language.
  - B: leaves inconsistent hierarchy.
  - C: larger visual lane than needed.

## Failure Modes & Edge Cases

- Type registry missing/empty: resolver must degrade to raw `type` label without crashing.
- `type visual` image mode with missing media: resolver must keep falling through cleanly.
- Hero/detail route can regress to unthemed screens when only `type visual` icon/color exists.
- Nearby row, partner cards, and hero identity blocks can silently diverge again if some callsites bypass the shared resolver/widget pair.
- Avatar badge placement can drift between hero and card variants if the shared identity widget is only partially adopted.

## Uncertainty Register

- Assumption: public account-profile surfaces already have enough local data (`type`, `avatar`, `cover`) for this Flutter-only lane.
- Assumption: favorites consistency remains intentionally partial until payload enrichment lands.
- Confidence: `medium-high`.

---

## Touched Surfaces

- `../foundation_documentation/todos/completed/TODO-v1-account-profile-visual-resolution-hardening.md`
- `lib/presentation/shared/visuals/**`
- `lib/presentation/tenant_public/discovery/widgets/discovery_nearby_row.dart`
- `lib/presentation/tenant_public/discovery/widgets/discovery_partner_card.dart`
- `lib/presentation/tenant_public/partners/routes/partner_detail_route.dart`
- `lib/presentation/shared/widgets/**`
- `lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart`
- `test/presentation/shared/visuals/**`
- `test/presentation/tenant_public/discovery/**`
- `test/presentation/tenant_public/partners/routes/partner_detail_route_test.dart`

## Ordered Steps

1. Add fail-first tests for the shared resolver/materializer and the touched public widgets/routes.
2. Introduce the shared `profile_type` summary resolver/materializer backed by `AppData.profileTypeRegistry`.
3. Introduce the shared `AccountProfile` identity widget for hero/Discovery composition.
4. Migrate touched public callsites to use the shared resolver/widget pair instead of ad-hoc lookups/fallbacks.
5. Align `PartnerDetailRoute` theme source with the final resolved visual source.
6. Run focused Flutter tests and `fvm dart analyze --format machine`.
7. Sync any stable conceptual outcome into the canonical module docs only if reusable behavior changed durably beyond this TODO.

## Test Strategy

- `test-first`

## Fail-First Targets

- Shared resolver fails the approved surface-specific precedence matrix.
- Identity widget fails to render a leading avatar surface from `type visuals` when avatar is absent.
- Identity widget fails to move the type visual into a badge overlay when avatar exists.
- Nearby row still renders halo/ring or generic storefront fallback when `type visuals` exist.
- Discovery partner card still renders the old textual eyebrow or fails to share the approved identity layout with the hero/card identity widget.
- `PartnerDetailRoute` still returns a plain screen when only `type visual` color fallback exists.
- Account-profile detail callsites still depend on ad-hoc controller-level `profileTypeDefinitionFor/typeLabelFor/typeVisualFor` instead of the shared resolver where this TODO intends reuse.
- Discovery partner cards still show the uppercase textual type eyebrow or still render lowercase/raw type tokens instead of the canonical registry label when textual fallback is needed.

## Definition of Done

- Shared `profile_type` resolver/materializer exists and is the canonical path for touched public account-profile surfaces.
- Shared account-profile identity widget exists and is the canonical path for touched hero/Discovery title composition.
- Touched public surfaces no longer improvise their own `profile_type` label/visual fallback rules.
- Surface/background media precedence and compact-row precedence are both consistent across the touched surfaces.
- Discovery account-profile cards no longer render the old textual type eyebrow and instead share the approved identity composition with the hero.
- Shared identity surfaces render avatar badge behavior consistently: badge on real avatar, type-visual fallback avatar when real avatar is missing.
- Any textual type rendering remaining in touched public surfaces is sourced from the registry `label` field, not from raw `type` tokens, except when the registry is absent and an explicit fallback is unavoidable.
- Nearby row halo is removed.
- `PartnerDetailRoute` theme source is coherent with the final detail visual source.
- Focused Flutter tests and `fvm dart analyze --format machine` pass.

## Validation Steps

- `fvm flutter test test/presentation/shared/visuals/profile_type_visual_resolver_test.dart`
- `fvm flutter test <new targeted discovery/route tests>`
- `fvm dart analyze --format machine`
