# TODO (V1): Static Assets Media Parity with Account Profiles

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** In Progress  
**Owners:** Flutter Team, Backend Team  
**Objective:** Ensure Static Assets media (avatar/cover) works with the same behavior and reliability as Account Profiles for upload, persistence, and rendering.

---

## A) Scope
- Align Static Assets media flow with Account Profiles in backend and Flutter.
- Backend:
  - Canonicalize media delivery under `/api/v1/media/*` (no new ingress-specific path rules).
  - Keep legacy web aliases (`/static-assets/*`, `/account-profiles/*`) as compatibility-only routes.
  - Add controller/service wiring equivalent to Account Profiles media serving behavior.
  - Keep proxy/scheme behavior compatible with current HTTPS edge setup.
- Flutter:
  - Fix Static Assets create/edit submit so URL-based media fields (`avatar_url`, `cover_url`) are sent when no file is selected.
  - Keep file upload behavior (`avatar`, `cover`) unchanged.
  - Ensure refreshed list/detail screens consume canonical backend media URLs only.
- Testing:
  - Add backend feature coverage for Static Assets media upload + retrieval endpoint behavior.
  - Add Flutter-focused coverage for Static Assets media payload persistence behavior.

## B) Out of Scope
- New media providers, transformations, or CDN migrations.
- Visual redesign of static asset screens.
- Refactors unrelated to media payload/serving path.

## C) Current Diagnosis
- `AccountProfileMediaService` is the working baseline: dedicated public web routes serve stored media reliably.
- `StaticAssetMediaService` already builds canonical public URLs (`/static-assets/{id}/{kind}`), but the delivery surface is incomplete because no matching public controller/route currently serves those files.
- Previous canonical attempts returned `404` because domain-aware API routes include `{tenant_domain}` and controller parameter extraction was ambiguous; controller must resolve IDs from named route params.
- Flutter `tenant_admin_static_assets_repository.dart` already sends `avatar_url` and `cover_url` when provided; the client is not the primary gap for the file-upload rendering failure.
- Map filter image delivery shows the same backend anti-pattern in a different surface: `MapFilterImageStorageService` still returns raw `/storage/...` URLs. The same parity principle should be applied there in this execution batch.

## D) Decision Baseline (Frozen)
- `D-01` Static Assets media must be served through dedicated tenant-aware public endpoints, matching Account Profiles.
- `D-02` Existing API field names remain unchanged (`avatar_url`, `cover_url`, `image_uri`).
- `D-03` File serving must expose cache-friendly validators (`ETag`, `Last-Modified`) as in Account Profile media.
- `D-04` Flutter changes are only allowed if a real payload-mapping gap is found during validation; no speculative client workaround.
- `D-05` Map filter image delivery is treated as the same parity-class backend defect and will be fixed in the same Laravel pass.
- `D-06` Filter images replace the default filter icon but must remain visually constrained to the same icon-sized envelope in the Flutter filter FAB, preserving the existing button proportions.
- `D-07` `/api/v1/media/*` is the canonical media contract for map filters, account profiles, and static assets; legacy web paths remain aliases only.

## E) Tasks
- [x] ✅ Production‑Ready Add `StaticAssetMediaController` equivalent to account profile media serving.
- [x] ✅ Production‑Ready Add tenant-aware web routes for static asset media:
  - Canonical: `/api/v1/media/static-assets/{static_asset_id}/avatar|cover`
  - Legacy alias: `/static-assets/{static_asset}/avatar|cover`
- [x] ✅ Production‑Ready Align account profile media retrieval with canonical route parameter handling:
  - Canonical: `/api/v1/media/account-profiles/{account_profile_id}/avatar|cover`
  - Legacy alias: `/account-profiles/{account_profile}/avatar|cover`
- [x] ✅ Production‑Ready Validate scheme/proxy behavior so generated URLs remain renderable in web (no mixed-content regressions).
- [x] ✅ Production‑Ready Confirm Flutter Static Assets submit payload keeps sending URL fields when file upload is not used.
- [x] ✅ Production‑Ready Preserve current file upload contract for multipart (`avatar`/`cover`).
- [x] ✅ Production‑Ready Add Laravel feature test covering upload + subsequent media URL response and retrievability.
- [x] ✅ Production‑Ready Add/adjust Laravel test coverage for map filter image public delivery parity.
- [x] ✅ Production‑Ready Ensure Flutter filter button rendering keeps uploaded images constrained to the same visual size as the default filter icon.
- [x] ✅ Production‑Ready Add Flutter test only if validation reveals a real client-side persistence/mapping gap. No additional static-assets client gap was found; a targeted FAB widget test was added for filter-image envelope rendering.

## F) Definition of Done
- [x] ✅ Production‑Ready Static Assets media URL generated by backend is retrievable through canonical `/api/v1/media/static-assets/*` endpoints.
- [ ] ⚪ Pending Static Assets create/edit using URL fields persists and reappears after refresh/navigation.
- [ ] ⚪ Pending Static Assets create/edit using file upload persists and reappears after refresh/navigation.
- [x] ✅ Production‑Ready Map filter image URL generated by backend is retrievable through a dedicated public endpoint.
- [ ] ⚪ Pending No fallback values are used to mask media read failures.
- [x] ✅ Production‑Ready Backend + any required Flutter tests for this flow pass.

## G) Validation
- [ ] ⚪ Pending Manual: Create static asset with avatar/cover file and verify render in list + detail + edit after reload.
- [ ] ⚪ Pending Manual: Update static asset media via URL and verify persisted value after reload.
- [x] ✅ Production‑Ready Automated: targeted Laravel test suite for static assets media routes/upload behavior (canonical + legacy alias retrieval assertions).
- [x] ✅ Production‑Ready Automated: targeted Laravel test suite for map filter image upload/delivery behavior.
- [x] ✅ Production‑Ready Automated: targeted Flutter repository/widget tests executed for the changed contract + filter-image FAB rendering; no additional static-assets client gap was confirmed.

## H) Complexity / Checkpoint Policy
- Complexity: `small`
- Checkpoint policy: consolidated review

## I) Applicable Rules / Workflows
- `delphi-ai/skills/wf-laravel-create-api-endpoint-method/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md` (only if Flutter edits become necessary)

## J) Approval Gate
Approved and executed on 2026-03-10.
