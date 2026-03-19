# TODO (V1): App Domain + App Links Convergence

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (credential rollout evidence handled in deeplink/well-known TODO)
**Owners:** Delphi (Flutter/Product) + Backend Team
**Goal:** Converge mobile tenant resolution (`X-App-Domain`) and deep-link association settings into a non-duplicated canonical model, with strict front/back validation and deterministic contracts.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invite-deeplink-identity-first-delivery.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-deeplink-host-resolved-well-known.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `laravel-app/app/Actions/DomainTenantFinder.php`
- `laravel-app/app/Application/Branding/DeepLinkAssociationService.php`
- `laravel-app/app/Models/Landlord/Tenant.php`
- `laravel-app/app/Models/Landlord/Domains.php`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/app_data_backend/app_data_backend_stub.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/auth_backend/auth_backend.dart`

---

## Scope Restatement
- Keep mobile tenant resolution dynamic via runtime app identifier (`packageInfo.packageName`) sent as `X-App-Domain`.
- Remove duplicated storage of app identifiers from `settings.app_links`.
- Establish `domains` as canonical source for mobile app identifiers (`app_android`, `app_ios`).
- Keep `settings.app_links` focused on association credentials only.
- Enforce invariant validation in both Flutter domain (`ValueObject`) and Laravel backend.
- Keep Tenant Admin UX linked by platform (identifier + credentials together) while persisting each concern in its canonical backend boundary.
- Replace free-text deep-link path editing with canonical route selection UX to avoid malformed association payloads.

## Out of Scope
- Changing resolver contract to require `X-App-Platform`.
- Multi-app-per-platform support per tenant.
- Play/App Store operational publication flow.

---

## Complexity Classification + Checkpoint Policy
- **Complexity:** `big`
- **Checkpoint policy:** section-by-section
  1. Canonical data model + resolver contract freeze
  2. Laravel migration + API/controller/services
  3. Flutter domain/repository/UI contract alignment
  4. Regression tests + docs promotion

---

## Decision Baseline (Frozen)
- `D-01` `X-App-Domain` remains the runtime resolver header for mobile tenant resolution.
- `D-02` Resolver remains platform-agnostic at runtime (no `X-App-Platform` required for resolution).
- `D-03` Mobile app identifiers are canonical in `domains` with explicit types (`app_android`, `app_ios`), replacing embedded `tenant.app_domains` as source of truth.
- `D-04` `settings.app_links` stores only deep-link credentials:
  - Android: `sha256_cert_fingerprints[]`
  - iOS: `team_id`, `paths[]`
  - (no duplicated `android.package_name` / `ios.bundle_id` in settings)
- `D-05` Tenant Admin UI must show identifier + credentials together per platform, but persistence remains split: identifiers via domain endpoints, credentials via settings namespace.
- `D-06` Cross-field invariants are mandatory:
  - Android fingerprints require existing `app_android` domain identifier.
  - iOS `team_id` requires existing `app_ios` domain identifier.
- `D-07` Flutter is the first validation boundary via ValueObjects; Laravel re-validates identically as authoritative boundary.
- `D-08` Platform governance is capped per tenant: at most one `app_android` and one `app_ios` identifier.
- `D-09` Path configuration UX must be canonical:
  - Android does not expose path editing in `assetlinks.json` context (paths are governed by app manifest intent-filters).
  - iOS `paths` are selected from canonical deep-link route checklist (default: all supported routes), not free-text input.

---

## Canonical Target Model

### A) Landlord `domains` (source of truth for identifiers)
- `type=web` (existing)
- `type=app_android` (new canonical use)
- `type=app_ios` (new canonical use)

Each tenant can have:
- zero-or-one `app_android`
- zero-or-one `app_ios`

### B) Tenant `settings.app_links` (credentials only)
```json
{
  "app_links": {
    "android": {
      "sha256_cert_fingerprints": ["AA:BB:...:FF"]
    },
    "ios": {
      "team_id": "ABCDE12345",
      "paths": ["/invite*", "/convites*"]
    }
  }
}
```

### C) Well-known payload derivation
- `assetlinks.json` package comes from `domains(type=app_android).path`.
- `apple-app-site-association` bundle comes from `domains(type=app_ios).path` and `team_id` from settings.

---

## Implementation Tasks

### A) Laravel Data/Domain Model
- [x] ✅ Introduce typed domain semantics for mobile identifiers in `Domains` lifecycle/services (`app_android`, `app_ios`).
- [x] ✅ Add migration/backfill strategy from `tenant.app_domains` and legacy `settings.app_links` identifiers to `domains(type=app_*)`.
- [x] ✅ Keep backward-read compatibility during transition window (legacy fallback remains available; cleanup is explicitly post-cutover and non-blocking for this stream).
- [x] ✅ Enforce per-tenant uniqueness by type (`app_android`, `app_ios`) in service-level validation.

### B) Laravel Resolver + Association Services
- [x] ✅ Update `DomainTenantFinder` to resolve mobile tenant via typed app domains relation.
- [x] ✅ Update `DeepLinkAssociationService` to derive package/bundle from typed `domains` (with transition-safe fallback).
- [x] ✅ Update fallback behavior for missing identifiers/credentials to deterministic empty payload contracts.

### C) Laravel API Contracts
- [x] ✅ Update tenant app-domain endpoints/contracts to support typed add/update/remove (`platform` + `identifier`).
- [x] ✅ Update settings-kernel `app_links` namespace schema to credential-only shape.
- [x] ✅ Enforce backend invariants:
  - [x] ✅ Reject Android fingerprints when `app_android` identifier is absent.
  - [x] ✅ Reject iOS `team_id` when `app_ios` identifier is absent.

### D) Flutter Domain + Repository
- [x] ✅ Introduce/align ValueObjects:
  - [x] ✅ Android app identifier format
  - [x] ✅ iOS bundle identifier format
  - [x] ✅ iOS team_id format
  - [x] ✅ SHA-256 fingerprint format + normalization
- [x] ✅ Refactor tenant-admin repositories/contracts to read/write typed app domains and credential-only settings payload.
- [x] ✅ Preserve runtime resolver flow using `packageInfo.packageName` as `X-App-Domain`.

### E) Flutter Tenant Admin UI
- [x] ✅ Keep linked UX section per platform (identifier + credentials in same visual block).
- [x] ✅ Persist identifiers via domain flow and credentials via settings flow (no data duplication).
- [x] ✅ Add UI gating/validation for identifier/team/fingerprint formats and backend invariant error surfacing.
- [x] ✅ Replace `iOS paths` free-text field with canonical route checklist (default all supported deep-link routes selected).
- [x] ✅ Clarify in Android block that path scope comes from app manifest/intent-filter, not `assetlinks` payload fields.

### F) Tests and Regression Coverage
- [x] ✅ Laravel feature/unit tests: typed app-domain CRUD + invariants + resolver coverage.
- [x] ✅ Laravel feature tests: `/.well-known/*` payloads read identifiers from domains and credentials from settings.
- [x] ✅ Flutter unit tests: new ValueObjects and repository encoding/decoding contracts.
- [x] ✅ Flutter widget/controller tests: Tenant Admin linked UX + validation states.
- [x] ✅ Integration/regression coverage: resolver + environment/deeplink contract behavior remains deterministic for Android/iOS identifiers.

### G) Documentation Sync
- [x] ✅ Promote canonical contract changes in `endpoints_mvp_contracts.md`.
- [x] ✅ Promote module decisions in `tenant_admin_module.md` and deeplink TODOs.
- [x] ✅ Mark superseded decisions in `TODO-v1-deeplink-host-resolved-well-known.md` regarding duplicated package/bundle in settings.

---

## Acceptance Criteria
- [x] ✅ Mobile tenant resolution remains deterministic via `X-App-Domain` with typed domain source.
- [x] ✅ `settings.app_links` contains no duplicated app identifiers.
- [x] ✅ Android/iOS identifiers are persisted once (typed domain records) and reused by both resolver and well-known payload builders.
- [x] ✅ Frontend ValueObjects enforce identifier/team/fingerprint formats before API submission.
- [x] ✅ Backend rejects invariant violations even when frontend validation is bypassed.
- [x] ✅ Tenant Admin preserves linked platform UX while writing through canonical separated boundaries.
- [x] ✅ iOS paths are configured by canonical route selection (checkbox list) with safe defaults, not arbitrary manual text.
- [x] ✅ Android section does not imply editable path control in settings payload.
- [x] ✅ Regression suites cover resolver, association payloads, and admin edit flows end-to-end.

---

## Validation Steps
- [x] ✅ `laravel-app`: feature/unit tests for app-domain typed CRUD, resolver, well-known payload derivation, invariant rejection.
- [x] ✅ `flutter-app`: unit/widget tests for ValueObjects + tenant-admin repository/UI flows.
- [x] ✅ `flutter-app`: integration/regression suites keep environment/deeplink contracts stable for typed identifiers.
- [x] ✅ `fvm flutter analyze` and `fvm dart run custom_lint` clean on branch delta.
- [x] ✅ `php pint` and `composer run lint:strict` clean.
