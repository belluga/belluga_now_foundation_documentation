# TODO (V1): Deeplink Well-Known Host Resolver

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Completed (MVP Android/Web scope closed; iOS payload runtime validation moved to VNext)  
**Owners:** Delphi (Flutter/Product) + Backend Team + Web/Infra Team  
**Goal:** Make `/.well-known/assetlinks.json` and `/.well-known/apple-app-site-association` host-resolved by Laravel (same strategy as `/manifest.json`), with ingress guaranteeing these routes never fall through to Flutter SPA.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invite-deeplink-identity-first-delivery.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md`
- `laravel-app/routes/web.php`
- `laravel-app/app/Http/Api/v1/Controllers/BrandingController.php`
- `laravel-app/app/Application/Branding/BrandingManifestService.php`
- `flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_technical_integrations_screen.dart`
- `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_app_links_section.dart`
- `docker/nginx/local.conf.template`
- `docker/nginx/prod.conf.template`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-ios-universal-links-production-validation.md`

---

## Scope Restatement
- Ensure `/.well-known/*` resolution is backend-owned and host-aware (`tenant`/`landlord`) like `/manifest.json`.
- Ensure local and production ingress route `/.well-known/*` to Laravel and never to Flutter SPA fallback.
- Establish canonical backend config source for Android and iOS deep-link association credentials.
- Keep guarappari delivery unblocked while preparing a multi-tenant-ready path.

## Out of Scope
- Invite acceptance domain behavior (already tracked in the invite deeplink TODO).
- iOS app-side entitlement implementation details outside association payload source/serving.
- Play Console operational tasks.

---

## Complexity Classification + Checkpoint Policy
- **Complexity:** `medium`
- **Checkpoint policy:** one checkpoint before implementation completion.

---

## Decision Baseline (Frozen)
- `D-01` `/.well-known/assetlinks.json` and `/.well-known/apple-app-site-association` are backend-owned host contracts, not Flutter static hosting contracts.
- `D-02` Ingress must always prioritize `/.well-known/*` before SPA catch-all in both local and prod templates.
- `D-03` Canonical credential source is backend tenant/landlord configuration, not Flutter flavor files.
- `D-04` The serving strategy must preserve guarappari readiness now and support future multi-tenant expansion without new ingress rewrites.
- `D-05` Static `/.well-known` payload files must not be committed in serving roots; endpoint resolution is canonical and protected by gitignore + regression checks.
- `D-06` Tenant Admin must expose deeplink editing with canonical split ownership:
  - typed app identifiers via `/admin/api/v1/appdomains`
  - credentials via settings namespace `/admin/api/v1/settings/values/app_links`
  without direct widget/backend coupling.

---

## Current State Snapshot
- [x] ✅ `/manifest.json` is already host-resolved by Laravel service/controller.
- [x] ✅ Static fallback files were removed from `laravel-app/public/.well-known/` to prevent endpoint shadowing.
- [x] ✅ `/.well-known/*` dynamic host resolution is implemented in Laravel routes/controllers/services.
- [x] ✅ Ingress fallback policy for `/.well-known/*` is standardized in both local/prod templates to route directly to Laravel front controller.
- [x] ✅ Tenant Admin technical integrations now read/write `app_links` via controller + repository contracts (no scattered conditional checks in UI widgets).
- [x] ✅ `.gitignore` in Laravel/Flutter blocks accidental static `/.well-known` payload commits.

---

## Implementation Tasks

### A) Ingress/Docker
- [x] ✅ Confirm and enforce `location = /.well-known/assetlinks.json` and `location = /.well-known/apple-app-site-association` precedence above SPA `location /`.
- [x] ✅ Enforce Laravel front controller handling for `/.well-known/*` (ignores accidental physical files).
- [x] ✅ Keep ACME challenge path handling intact.

### B) Laravel Route + Service
- [x] ✅ Add explicit web routes for `/.well-known/assetlinks.json` and `/.well-known/apple-app-site-association`.
- [x] ✅ Implement host-resolved service (tenant/landlord) for deep-link association payloads.
- [x] ✅ Set deterministic JSON response content type for association payloads.

### C) Backend Configuration Source
- [x] ✅ Define canonical config split:
  - typed app identifiers (`android`, `ios`) in `/admin/api/v1/appdomains`
  - credential settings in `settings.app_links` (`android.sha256_cert_fingerprints[]`, `ios.team_id`, `ios.paths[]`).
- [x] ✅ Map identifiers + credentials to tenant/landlord resolved context in runtime resolver.
- [x] ✅ Define explicit fallback behavior when credentials are missing (empty payload/details).

### C2) Tenant Admin Frontend (`app_links`)
- [x] ✅ Extend domain + repository contracts to fetch/update `app_links` (`/admin/api/v1/settings/values/app_links`).
- [x] ✅ Add technical integrations section for App Links with edit/save flow for Android and iOS fields.
- [x] ✅ Add Flutter repository/widget tests covering new `app_links` parsing, patch payload, and UI save behavior.

### D) Testing
- [x] ✅ Add Laravel feature tests for both `/.well-known/*` endpoints in tenant and landlord host contexts.
- [x] ✅ Add regression test proving `/.well-known/*` never resolves to Flutter HTML payload.
- [x] ✅ Validate nginx templates (local/prod) keep precedence and fallback rules.
- [x] ✅ Add regression checks that static `/.well-known` files are absent in Laravel/Flutter roots and blocked by `.gitignore`.

### E) Delivery Evidence
- [x] ✅ Validate `https://guarappari.belluga.space/.well-known/assetlinks.json` returns canonical Play signing fingerprint (`ED:07:87:5E:89:8A:4B:26:41:5B:C7:A9:19:44:84:D3:0A:A4:AD:52:BA:66:47:56:8F:62:EF:71:F0:FD:1A:54`).
- [x] ✅ Move iOS AASA payload runtime validation to `TODO-vnext-ios-universal-links-production-validation.md` (VNext scope).
- [x] ✅ Record evidence links/output in this TODO.

---

## Acceptance Criteria
- [x] ✅ `/.well-known/*` is served by Laravel host-resolved logic, not Flutter SPA.
- [x] ✅ Docker ingress local/prod keeps deterministic routing precedence for `/.well-known/*`.
- [x] ✅ Canonical source is backend config resolved by tenant/landlord context (typed app identifiers + credential settings split).
- [x] ✅ Tenant Admin can edit tenant-scoped `app_links` settings through the canonical settings namespace flow.
- [x] ✅ Guarappari Android association payload is validated in production runtime; iOS payload runtime validation is tracked in `TODO-vnext-ios-universal-links-production-validation.md`.
- [x] ✅ Automated tests cover host resolution + non-SPA fallback regressions.

---

## Verification Notes
- Flutter tests executed:
  - `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart` ✅
  - `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` ✅
  - `fvm flutter test test/platform/deep_link_platform_config_test.dart` ✅
- Laravel tests added for tenant + landlord `/.well-known/*` host resolution and HTML-fallback regression assertions:
  - `tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`
  - `tests/Api/v1/Admin/ApiV1WellKnownAssociationAdminTest.php`
- Playwright smoke (edge/runtime):
  - `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` ✅
  - `NAV_DEPLOY_LANE=stage NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation` ✅
- Runtime payload check (`2026-03-19`):
  - `curl https://guarappari.belluga.space/.well-known/assetlinks.json` → `package_name: com.guarappari.app`, fingerprint `ED:07:...:1A:54` ✅
  - `curl https://guarappari.belluga.space/.well-known/apple-app-site-association` currently returns empty `details`; runtime iOS payload completion moved to VNext TODO.
- Regression checks for static file prevention:
  - `flutter-app/test/platform/deep_link_platform_config_test.dart`
  - `laravel-app/.gitignore`
  - `flutter-app/.gitignore`
- Laravel gate validations executed in docker runtime:
  - `composer run lint:strict` ✅
  - full suite (`php artisan test`) ✅
