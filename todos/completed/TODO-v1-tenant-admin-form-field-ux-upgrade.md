# TODO (V1): Tenant Admin Form Field UX Upgrade

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** ✅ Production‑Ready (completed)  
**Owner:** Flutter Team  
**Date:** 2026-02-15

## Objective
Upgrade tenant-admin form field ergonomics to industry-standard Material 3 patterns by using content-appropriate inputs (chips/tokens/selectors/typed fields), adding creation shortcuts, and tightening validation/input formatting.

## Scope
- Replace CSV-only free text fields with token/chip input where appropriate.
- Add "create type" shortcuts from dependent selectors (accounts/profiles/static assets).
- Improve typed input semantics for document and coordinates.
- Improve slug/color/icon field UX and validation.
- Preserve existing controller-owned state architecture and route contracts.
- Align Static Asset form fields with current backend contract:
  - Remove `slug` input from Static Asset create/edit forms (backend-generated).
  - Remove `is_active` toggle from create flow (default active on backend).
  - Remove standalone `tags` input from Static Asset create/edit forms (taxonomy-only UX).
- Consolidate Static Asset category semantics with profile type:
  - Remove `categories` from Static Asset create/edit forms.
  - Derive map projection `category` from static profile type definition in backend.
  - Keep `taxonomy_terms` as the single classification mechanism.
- Rich text + media source UX upgrades:
  - Add HTML helper actions for `Bio` fields (M3-friendly, lightweight tag toolbar).
  - Fix Account create flow so `Bio` appears when selected profile type capability enables it.
  - Standardize image media input across tenant-admin forms with a single "Adicionar imagem" action that opens a source chooser (`Do dispositivo` or `Da web`).
- Taxonomy-driven creation UX:
  - In Account creation (bound account+profile flow), include a taxonomy section driven by selected `profile_type`.
  - In Static Asset creation/edit, keep taxonomy section and ensure it always reflects the allowed taxonomies for the selected static profile type.
  - If a "tag-like" behavior is needed, model it as an explicit taxonomy (e.g., taxonomy slug `tags`) and allow it per type.
- Tenant Admin edit interaction consistency (M3-aligned):
  - Prefer per-field edit actions for mutable fields (`on tap` -> bottom sheet editor -> submit only that field).
  - Keep create flows as full forms (no per-field auto-submit on create).
  - For taxonomy fields in edit flows, apply immediate save on selection/removal (optimistic UI + rollback on error).
  - Apply this interaction model across Accounts, Static Assets, Settings, Taxonomies, Profile Types, Static Profile Types, and Organizations.

## Decisions
- [x] ✅ Production‑Ready Keep route-based creation shortcuts (`push` + reload source list on return).
- [x] ✅ Production‑Ready Keep backend payload contracts unchanged (UI normalization only).
- [x] ✅ Production‑Ready Apply improvements first to high-impact forms (accounts, account profiles, static assets, taxonomies, profile types).
- [x] ✅ Production‑Ready Static Asset `slug` is server-generated (`HasSlug`) and must not be sent from Flutter forms.
- [x] ✅ Production‑Ready Static Asset create flow should not expose an `Ativo` toggle; backend defaults to active when omitted.
- [x] ✅ Production‑Ready Static Asset standalone `tags` input is deprecated in favor of taxonomy-only classification.
- [x] ✅ Production‑Ready Static Asset `categories` will no longer be user-entered; map category is derived from `static_profile_types`.
- [x] ✅ Production‑Ready Backend introduces `static_profile_types.map_category` (coarse map bucket) and uses it as source of truth when projecting `map_pois.category`.
- [x] ✅ Production‑Ready Transition strategy keeps API compatibility temporarily: `categories` may still be accepted in requests but is ignored for map projection and hidden from Flutter forms.
- [x] ✅ Production‑Ready Taxonomy-only UX direction:
  - classification fields are exclusively `taxonomy_terms`.
  - no standalone `tags` endpoint/UI.
  - optional tag-like behavior is represented by a dedicated taxonomy (`tags`) controlled by allowed taxonomies per type.
- [x] ✅ Production‑Ready Image media source chooser becomes shared UX primitive used by Account Create, Account Profile Create/Edit, and Static Asset Create/Edit.
- [x] ✅ Production‑Ready Admin-wide edit model:
  - single-field bottom sheet edits for mutable fields in edit contexts.
  - taxonomy chips in edit contexts auto-save immediately.
  - account/static-asset `slug` editable only on edit and validated by backend uniqueness on update.
  - slug-bearing registries (`profile types`, `taxonomies`, `terms`) must allow slug edit in edit mode with backend uniqueness validation.

## Plan
### Phase 1 — Shared UX Primitives
- [x] ✅ Production‑Ready Add reusable token/chip input widget for string lists.
- [x] ✅ Production‑Ready Add shared validators/parsers for slug, hex color, and decimal coordinates.

### Phase 2 — Type Shortcuts + Typed Inputs
- [x] ✅ Production‑Ready Add "Criar tipo" shortcuts near profile/static type selectors and refresh options after return.
- [x] ✅ Production‑Ready Account create flow no longer depends on explicit document type/number fields (superseded in current MVP flow).
- [x] ✅ Production‑Ready Normalize coordinate fields for decimal/signed keyboard + formatter + comma/dot parsing.
- [x] ✅ Production‑Ready Ensure slug fields auto-derive from corresponding name/label fields in all tenant-admin create flows (silent auto-off on manual slug edit).
- [x] ✅ Production‑Ready Align keyboard type/capitalization/suggestions with field semantics (names/labels/content/urls/slugs) and enforce slug-safe input formatters (including paste).

### Phase 3 — Field-Type Modernization
- [x] ✅ Production‑Ready Remove static-asset standalone `tags` field from create/edit forms and rely on taxonomy term selectors only.
- [x] ✅ Production‑Ready Replace profile-type allowed taxonomies CSV with token/chip input.
- [x] ✅ Production‑Ready Improve taxonomy icon/color UX (quick picks + preview + validation).
- [x] ✅ Production‑Ready Static Asset forms: remove slug field/UI logic and stop sending `slug` to repository payloads.
- [x] ✅ Production‑Ready Static Asset create form: remove `is_active` control from UI and rely on backend default.
- [x] ✅ Production‑Ready Static Asset edit form: keep status display-only for now (no toggle) unless explicit product requirement reintroduces manual state change.
- [x] ✅ Production‑Ready Static Asset forms: remove `categories` field/UI logic and stop sending `categories` in create/update payloads.
- [x] ✅ Production‑Ready Add internal bottom list padding in tenant-admin list views with FAB so the last item can scroll above the FAB (padding inside `ListView`, not outside container).
- [x] ✅ Production‑Ready Remove explicit "Gerar slug automaticamente" toggles in slug forms; keep auto-slug implicit by default and silently disable auto-mode when slug is manually edited.
- [x] ✅ Production‑Ready Reorder slug forms so "Nome/Label" comes before "Slug" to make auto-slug feedback immediate while typing.
- [x] ✅ Production‑Ready Confirm all current tenant-admin slug forms follow this order (`Profile Type`, `Static Profile Type`, `Taxonomy`, `Term`, `Static Asset`; static asset already compliant and unchanged structurally).
- [x] ✅ Production‑Ready Profile-type taxonomy selection now uses existing taxonomy options (`slug + nome`) with multi-select chips and blocks unknown slugs.

### Phase 4 — Validation Hardening
- [x] ✅ Production‑Ready Enforce slug pattern validation in slug-based forms (profile type, static profile type, taxonomy, term, static asset).
- [x] ✅ Production‑Ready Keep existing required-field messages stable where already asserted by tests.
- [x] ✅ Production‑Ready Backend: extend static profile type contracts with `map_category` validation and persistence.
- [x] ✅ Production‑Ready Backend: map POI projection uses `map_category` from profile type definition (fallback deterministic value when missing).
- [x] ✅ Production‑Ready Documentation: align `domain_entities`, `tenant_admin_module`, `map_poi_module`, and MVP endpoint contracts with “category derived from type” semantics.

### Phase 5 — Verification
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready Run targeted widget/unit tests for changed forms/controllers.
- [x] ✅ Production‑Ready Run targeted integration tests for account/static-asset/taxonomy admin flows.
- [x] ✅ Production‑Ready Re-run `flutter-architecture-adherence` + `flutter-clean-code-audit` and close or document explicit exceptions.
- [x] ✅ Production‑Ready Run targeted Laravel tests for static profile types, static assets, and map projection/filter behavior.

### Phase 6 — Rich Bio + Tags + Media Source (Current)
- [x] ✅ Production‑Ready Flutter: reusable HTML toolbar for `Bio` is available and applied to account/profile/static-asset forms.
- [x] ✅ Production‑Ready Flutter: account create shows `Bio` when `profile_type.capabilities.has_bio=true` and submits it in account profile creation payload.
- [x] ✅ Production‑Ready Flutter: account create includes taxonomy section bound to allowed taxonomies of selected profile type and submits selected terms in account profile creation payload.
- [x] ✅ Production‑Ready Flutter: static asset taxonomy section remains synchronized with selected static profile type allowed taxonomies.
- [x] ✅ Production‑Ready Flutter: standalone static-asset `tags` controls were removed; classification is taxonomy terms only (including optional taxonomy `tags` when configured).
- [x] ✅ Production‑Ready Flutter: shared image source bottom sheet (`Do dispositivo` / `Da web`) is applied to tenant-admin image media sections in Account/Profile/Static Asset create+edit flows.
- [x] ✅ Production‑Ready Flutter tests: focused coverage exists for account-create bio visibility/submission, taxonomy section in account-create, and taxonomy-only static-asset classification.
- [x] ✅ Production‑Ready Flutter: static assets list filters (search + type) are deterministic and synchronized with UI state.
- [x] ✅ Production‑Ready Flutter integration test: static assets list filter behavior is covered (type select/clear and search query application).

### Phase 7 — Admin-Wide Edit Consistency (Current)
- [x] ✅ Production‑Ready Laravel: allow slug/type rename on update for slug-bearing admin entities in scope (`accounts`, `static_assets`, `organizations`, `account_profile_types`, `static_profile_types`) with uniqueness validation and dependent record propagation where needed.
- [x] ✅ Production‑Ready Flutter: Accounts expose edit entrypoint with per-field edit sheets (including `slug`) that submit only changed field.
- [x] ✅ Production‑Ready Flutter: Static Asset edit supports per-field edit sheets (including `slug`) and submits only changed field.
- [x] ✅ Production‑Ready Flutter: edit forms for `Profile Types`, `Static Profile Types`, `Taxonomies`, and `Taxonomy Terms` now allow slug/type edits in edit mode.
- [x] ✅ Production‑Ready Flutter/Laravel: Account Profile edit now supports slug updates with backend uniqueness validation.
- [x] ✅ Production‑Ready Flutter: Profile Types and Static Profile Types detail screens now support per-field edit sheets for `label` and `slug`.
- [x] ✅ Production‑Ready Flutter: Taxonomy Term detail now supports per-field edit sheets for `name` and `slug`.
- [x] ✅ Production‑Ready Flutter: Taxonomy root entity supports per-field edit actions (`name` and `slug`) directly from registry list.
- [x] ✅ Production‑Ready Flutter: Settings Firebase/Push sections now support per-field edit via bottom sheet, preserving section save flow.
- [x] ✅ Production‑Ready Flutter: taxonomy selection in taxonomy-enabled edit flows (Account Profile + Static Asset) auto-saves immediately with in-place saving feedback and rollback on failure.
- [x] ✅ Production‑Ready Flutter tests: add/update targeted tests covering per-field edit sheets, slug edit uniqueness error propagation, and taxonomy auto-save behavior.

### Phase 8 — Rich Text + Content Capability Parity + Visual Preview (Current)
- [x] ✅ Production‑Ready Laravel: extend `account_profile_types.capabilities` with `has_content` in store/update validation, management service, registry service output, and seed defaults.
- [x] ✅ Production‑Ready Laravel: extend Account Profile payload/contracts to accept and return `content` (`store/update` requests, `AccountProfile` fillable, formatter/query responses, tests).
- [x] ✅ Production‑Ready Flutter domain/contracts: add `hasContent` to `TenantAdminProfileTypeCapabilities` and align DTO/repository/controller payload mapping (`has_content`).
- [x] ✅ Production‑Ready Flutter Account/Profile create+edit: render separate `Bio` and `Conteudo` fields based on capabilities (`hasBio`, `hasContent`) and submit both when enabled.
- [x] ✅ Production‑Ready Flutter rich text UX: replace lightweight HTML tag helper with shared rich-text editor UX (create+edit flows for account/profile/static asset `bio`/`content`).
- [x] ✅ Production‑Ready Flutter visual preview upgrade: improve Profile/Static Asset list/detail preview surfaces to be more visual (immersive-style header/card + explicit edit actions) and use avatar thumbnails when available.
- [x] ✅ Production‑Ready Verification: targeted + full Flutter/Laravel tests green after contract/UI migration.

### Phase 9 — Media Ingestion Hardening (Current)
- [x] ✅ Production‑Ready Flutter: image ingestion from `Da web` now converts URL input to local file before submit (no direct persistence of user-provided media URL in create/edit forms).
- [x] ✅ Production‑Ready Flutter: avatar/cover media is normalized client-side with deterministic crop policy (`avatar=1:1`, `cover=16:9`) and output size reduction before upload.
- [x] ✅ Production‑Ready Flutter: URL import failures surface explicit fallback guidance (`site bloqueia importacao direta` -> `baixar e enviar do dispositivo`).
- [x] ✅ Production‑Ready Flutter: same media ingestion policy applied consistently to Account/Profile and Static Asset create/edit flows.

## Definition of Done
- [x] ✅ Production‑Ready Form fields use content-appropriate controls in upgraded screens.
- [x] ✅ Production‑Ready Type creation shortcuts are available from dependent selectors.
- [x] ✅ Production‑Ready Coordinate/document/slug inputs have robust validation/formatting.
- [x] ✅ Production‑Ready Analyze and targeted tests are green.
- [x] ✅ Production‑Ready Static Asset create/edit no longer expose `slug`, `is_active`, or `categories`.
- [x] ✅ Production‑Ready Static Asset map category is determined by static profile type (`map_category`) in backend projection logic.
- [x] ✅ Production‑Ready `Bio` has HTML helper actions and capability-driven visibility across create/edit flows.
- [x] ✅ Production‑Ready Classification UX is taxonomy-only, with optional taxonomy `tags` instead of standalone tags field.
- [x] ✅ Production‑Ready All tenant-admin image media sections use source chooser bottom sheet with device upload or web URL input.
- [x] ✅ Production‑Ready Tenant Admin edit UX is interaction-consistent across admin modules in scope (field-level edit sheets + taxonomy auto-save where applicable).
- [x] ✅ Production‑Ready Account Profile type capabilities include both `has_bio` and `has_content` and are respected in admin create/edit form behavior.
- [x] ✅ Production‑Ready Tenant Admin rich-text UX for `bio/content` uses shared editor component (no manual HTML tag insertion UX in form fields).
- [x] ✅ Production‑Ready Account/Profile/Static Asset preview surfaces expose avatar/cover-first visual affordance with clear edit CTA before opening edit form.
- [x] ✅ Production‑Ready Tenant-admin media fields never depend on direct persistence of user-provided external image URLs during create/edit submission; uploads are file-based.

**Provisional Notes (Phase 5):**
- Targeted account/static-asset/taxonomy integration tests were re-run in resilient, flavor-aware mode and promoted to Production‑Ready.
- Phase 8 verification rerun (2026-02-16):
  - Flutter: `fvm flutter analyze` + `fvm flutter test --concurrency=1` (full unit/widget suite) green.
  - Flutter integration (ADB, flavor `belluga`): `feature_admin_account_create_with_location`, `feature_admin_profile_type_capabilities_form`, `feature_admin_taxonomy_registry`, `feature_admin_static_assets`, `feature_admin_account_create_validation` green.
  - Laravel: `docker compose exec -T app php artisan test` green.
