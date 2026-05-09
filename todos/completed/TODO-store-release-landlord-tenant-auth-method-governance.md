# Title
Store Release Landlord Tenant Auth Method Governance

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
**Status:** Production-Ready. The generic auth-governance baseline merged to `dev` on April 20, 2026 through backend PR #157, the current stage lane is complete, and completion-evidence reconciliation is complete.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The current Belluga phone-OTP release lane assumes `phone_otp` as if it were the global platform rule for tenant-public authentication. That is not acceptable for the Laravel boilerplate: the generic platform must remain capable of multiple authentication methods while each product/tenant resolves an effective allowed subset through governance. Without that upstream baseline, Belluga-specific release behavior would be baked into the generic auth layer and there would be no canonical answer to whether tenants may customize their allowed authentication methods.

The repository already contains a generic settings kernel with landlord and tenant scopes, namespace registration, and patch guards. This lane established the auth-method governance namespace, deterministic runtime resolution for tenant-public authentication, and Belluga release-tenant pinning without turning `phone_otp` into a global platform rule.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** it isolates the generic platform rule that must exist before the downstream Belluga phone-OTP lane can proceed without polluting the boilerplate with product-specific behavior.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the user clarified the intended platform rule directly: landlord governs available auth methods, tenant selection is optionally allowed, and Belluga-specific release behavior should consume that resolved contract rather than replace it.

## Contract Boundary
- This TODO defines the generic landlord/tenant governance contract for tenant-public authentication methods.
- It must establish how auth methods are registered, how landlord controls availability, whether tenant override is allowed, and how effective runtime resolution works.
- It does not own the downstream Belluga phone-OTP challenge UX, OTP dispatcher behavior, or contact-match implementation beyond freezing how those capabilities are selected/enabled.
- It does not redesign landlord/admin authentication surfaces.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `none`
- **Next exact step:** archive to `completed/`; no active promotion follow-up remains.

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`

## Pre-Implementation Snapshot (Repository Scan 2026-04-18)
- The settings kernel already supports landlord and tenant scopes with namespace discovery, values resolution, and guarded PATCH semantics.
  - Evidence: `../laravel-app/packages/belluga/belluga_settings/src/Application/SettingsKernelService.php`
  - Evidence: `../laravel-app/packages/belluga/belluga_settings/src/Stores/MongoSettingsStore.php`
- The host app already registers tenant settings namespaces such as `telemetry` and `app_links`, which shows the intended extension pattern for this new contract.
  - Evidence: `../laravel-app/app/Providers/PackageIntegration/SettingsIntegrationServiceProvider.php`
  - Evidence: `../laravel-app/app/Integration/DeepLinks/AppLinksSettingsNamespaceRegistrar.php`
- Runtime environment/bootstrap payloads already expose some tenant settings to Flutter, but there is no tenant-public auth-method configuration in the payload today.
  - Evidence: `../laravel-app/app/Application/Environment/EnvironmentResolverService.php`
- Tenant-public auth remains hardcoded around email/password today, and there is no generic auth-method governance layer between product/runtime and auth implementation.
  - Evidence: `../laravel-app/routes/api/public_tenant_maybe_api_v1.php`
  - Evidence: `../laravel-app/app/Http/Api/v1/Controllers/AuthControllerAccount.php`
  - Evidence: `../laravel-app/app/Http/Api/v1/Controllers/PasswordRegistrationController.php`

## Package-First Assessment
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "auth method tenant settings environment resolver phone otp"`
- Relevant packages found: none
- READMEs read: `n/a`
- Decision: local implementation inside the existing Laravel host app + `belluga_settings` kernel integration points
- Tier: `Local`
- Rationale: the repo already has the required generic settings-kernel primitives; this lane needs a host-level governance contract, effective resolver, and runtime exposure rather than a new proprietary package.

## Contract Clarifications (Frozen For Execution)
- [x] This lane is a `contract-and-backend` slice. It freezes governance and runtime exposure, but it does **not** switch the current tenant-public login UX or auth enforcement away from email/password in this TODO.
- [x] `/api/v1/environment` exposure in this lane is preparatory runtime truth for downstream consumers. The downstream Belluga OTP TODO remains the authority that will make the app/runtime behavior consume the effective `phone_otp` contract.
- [x] Admin-consumer scope in this lane means existing settings-kernel API exposure plus deterministic backend provisioning evidence. It does **not** require new Flutter/admin UI in this TODO.
- [x] Belluga release-tenant pinning must be deterministic and backend-owned. This TODO cannot close on namespace registration alone; it must prove how the release tenant receives effective `phone_otp` through persisted settings or a controlled provisioning path.
- [x] Landlord/tenant inheritance rules are owned by the settings patch-guard plus effective resolver, not by downstream clients.

## Scope
- [x] Define a canonical tenant-public auth-method registry contract for the generic platform, with initial release-relevant entries including `password` and `phone_otp`.
- [x] Define landlord-owned settings for which tenant-public auth methods are available in the deployment.
- [x] Define whether landlord may allow tenant admins to choose an enabled subset from the landlord-available methods.
- [x] Define tenant-owned settings for enabled auth methods only when landlord explicitly permits tenant customization.
- [x] Enforce that tenants can never enable an auth method that landlord did not expose as available.
- [x] Define deterministic effective tenant-public auth-method resolution for runtime consumers:
  - when tenant customization is disabled, effective methods equal the landlord-configured set;
  - when tenant customization is enabled, effective methods equal the tenant-selected subset of the landlord-configured set.
- [x] Expose effective tenant-public auth-method configuration to the relevant runtime/admin consumers.
- [x] Freeze Belluga Android release configuration so the effective tenant-public auth-method set remains `phone_otp` for the release tenant even though the generic platform may also support `password`.
- [x] Keep the downstream Belluga contact-match dependency explicit: verified phone identity remains required for the store-release social baseline even if the generic platform supports other auth methods.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8`, `foundation_documentation:local promotion-lane realignment (2026-04-19)`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Generic auth-governance contract + settings/runtime resolution | `belluga_now_backend:delphi/laravel-reconcile-store-release-20260419 -> dev @ da78fa8` | `https://github.com/belluga/belluga_now_backend/pull/157 (merged -> dev on 2026-04-20)` | `stage lane completion guard passed on 2026-04-27` | `n/a for current threshold` | `Production-Ready; completion guard passed and downstream Belluga OTP TODO is unblocked` |

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOD-01 | Definition of Done | Generic tenant-public auth-method governance is documented and no longer implied only by discussion. | code/docs | `../laravel-app/app/Integration/Settings/TenantPublicAuthMethodSettingsNamespaceRegistrar.php`, `../laravel-app/app/Application/Auth/TenantPublicAuthMethodResolver.php`, this TODO `Contract Clarifications` + `Decision Baseline` | Laravel backend dev PR #157 | passed | Governance is represented by namespace registration, resolver, and frozen TODO decisions rather than session-only discussion. |
| DOD-02 | Definition of Done | Landlord-owned availability and tenant-edit permission rules are frozen and implemented. | code/tests | `../laravel-app/app/Integration/Settings/TenantPublicAuthMethodPatchGuard.php`, `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php` landlord/tenant patch tests | Laravel settings kernel | passed | Patch guard owns availability and customization permissions. |
| DOD-03 | Definition of Done | Tenant selection, when allowed, is enforced as a subset of landlord-available auth methods. | code/tests | `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php` subset rejection test, `../laravel-app/tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php` | Laravel settings kernel | passed | Invalid tenant method `magic_link` is rejected and resolver intersects tenant methods with landlord availability. |
| DOD-04 | Definition of Done | Effective tenant-public auth-method resolution is available to runtime/admin consumers through a canonical backend contract. | code/tests | `../laravel-app/app/Application/Environment/TenantEnvironmentPayloadFactory.php`, `../laravel-app/app/Application/Environment/EnvironmentResolverService.php`, `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`; integration test exercises `/api/v1/environment` runtime response and admin settings API contract | `/api/v1/environment` and admin settings APIs | passed | `settings.tenant_public_auth` exposes available, enabled, effective, and primary method values through the runtime/admin integration path. |
| DOD-05 | Definition of Done | Belluga release configuration can resolve deterministically to `phone_otp` without turning that product choice into a platform-wide boilerplate rule. | tests | `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`, `../laravel-app/tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php` | Tenant environment resolver | passed | Persisted landlord/tenant settings resolve Belluga effective primary method to `phone_otp` while platform registry still includes `password`. |
| DOD-06 | Definition of Done | The downstream Belluga phone-OTP TODO is explicitly unblocked by this artifact rather than continuing to redefine generic auth policy. | docs | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`, `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md` | Store-release TODO graph | passed | Downstream TODO consumes this upstream governance baseline and keeps Belluga OTP cutover product-specific. |
| DOD-07 | Definition of Done | This TODO leaves current email/password tenant-public auth enforcement explicitly unchanged until the downstream Belluga OTP lane consumes the frozen effective contract. | tests | `../laravel-app/tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` password login unchanged with `phone_otp` effective config | Laravel auth service | passed | Current email/password enforcement is intentionally not changed by this governance slice. |
| VAL-01 | Validation Steps | Backend tests prove effective-resolution rules for: | tests | `../laravel-app/tests/Unit/Application/Auth/TenantPublicAuthMethodResolverTest.php` | Laravel unit tests | passed | Covers landlord-only configuration, valid tenant subset, disabled tenant customization, and effective primary method resolution. |
| VAL-02 | Validation Steps | Backend tests prove runtime/admin consumers receive the effective auth-method contract. | tests | `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php`, `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`; integration test coverage for settings kernel API plus `/api/v1/environment` runtime response | Laravel feature/API integration tests | passed | Settings kernel and environment payload expose `tenant_public_auth` to runtime/admin consumers. |
| VAL-03 | Validation Steps | Backend tests prove persisted landlord + tenant settings resolve through the production resolver path into `/api/v1/environment`. | tests/mutation | Laravel integration test `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php::test_environment_api_exposes_tenant_public_auth_from_persisted_settings`; local mutation writes landlord + tenant settings, then reads `/api/v1/environment` through the production resolver path | `/api/v1/environment` non-main backend mutation integration test | passed | Test mutates persisted landlord + tenant settings and asserts effective `phone_otp` in the environment response. |
| VAL-04 | Validation Steps | Backend tests prove current email/password tenant-public auth behavior remains intentionally unchanged in this lane even when the effective runtime contract resolves to `phone_otp`. | tests | `../laravel-app/tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php::test_password_login_remains_available_when_tenant_public_auth_is_pinned_to_phone_otp` | Laravel auth service | passed | Governance exposure does not enforce downstream OTP login cutover prematurely. |
| VAL-05 | Validation Steps | Backend evidence proves Belluga release-tenant pinning is deterministic via persisted settings or a controlled provisioning path rather than implicit defaults. | tests/code/mutation | Laravel integration test `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`, `../laravel-app/app/Application/Auth/TenantPublicAuthMethodResolver.php`; local mutation persists landlord/tenant auth settings before resolving the release tenant environment | Tenant environment resolver non-main backend mutation integration test | passed | Persisted landlord and tenant settings, not undocumented defaults, drive effective `phone_otp`. |
| VAL-06 | Validation Steps | TODO references are updated so `TODO-store-release-phone-otp-auth-and-contact-match.md` now consumes this TODO as a frozen upstream baseline instead of redefining generic auth policy. | docs | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md` references and dependency sections | Store-release TODO graph | passed | Downstream OTP TODO states it consumes this governance baseline. |
| VAL-07 | Validation Steps | Belluga release orchestration reflects this TODO as the delivered upstream baseline for Belluga auth execution. | docs | `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md` references, scope, and promotion evidence rows | Store-release orchestrator TODO | passed | Parent release TODO treats this child as the upstream baseline for auth execution. |

## Out of Scope
- [ ] Implementing every future auth method or third-party auth provider.
- [ ] The downstream Belluga phone-OTP challenge/verify UX and contact-match flow; those remain owned by `TODO-store-release-phone-otp-auth-and-contact-match.md`.
- [ ] Landlord/admin authentication redesign.
- [ ] Authenticated web/QR login or workspace-web session expansion.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** settings-kernel namespace registration, effective-resolution runtime exposure, release-critical docs, and minimal admin/runtime consumer adjustments needed to make the governance contract real.
- **Must update or split the TODO:** Belluga OTP UX polish, broad auth-provider implementation work beyond the release-relevant registry entries, or unrelated settings/admin feature work.

## Definition of Done
- [x] Generic tenant-public auth-method governance is documented and no longer implied only by discussion.
- [x] Landlord-owned availability and tenant-edit permission rules are frozen and implemented.
- [x] Tenant selection, when allowed, is enforced as a subset of landlord-available auth methods.
- [x] Effective tenant-public auth-method resolution is available to runtime/admin consumers through a canonical backend contract.
- [x] Belluga release configuration can resolve deterministically to `phone_otp` without turning that product choice into a platform-wide boilerplate rule.
- [x] The downstream Belluga phone-OTP TODO is explicitly unblocked by this artifact rather than continuing to redefine generic auth policy.
- [x] This TODO leaves current email/password tenant-public auth enforcement explicitly unchanged until the downstream Belluga OTP lane consumes the frozen effective contract.

## Validation Steps
- [x] Backend tests prove effective-resolution rules for:
  - landlord-only configuration;
  - tenant override allowed with valid subset;
  - tenant override rejected when it includes non-landlord methods.
- [x] Backend tests prove runtime/admin consumers receive the effective auth-method contract.
- [x] Backend tests prove persisted landlord + tenant settings resolve through the production resolver path into `/api/v1/environment`.
- [x] Backend tests prove current email/password tenant-public auth behavior remains intentionally unchanged in this lane even when the effective runtime contract resolves to `phone_otp`.
- [x] Backend evidence proves Belluga release-tenant pinning is deterministic via persisted settings or a controlled provisioning path rather than implicit defaults.
- [x] TODO references are updated so `TODO-store-release-phone-otp-auth-and-contact-match.md` now consumes this TODO as a frozen upstream baseline instead of redefining generic auth policy.
- [x] Belluga release orchestration reflects this TODO as the delivered upstream baseline for Belluga auth execution.

## External Dependency Readiness (Required When External Systems Matter)
- n/a

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the change is structurally important and cross-stack, but it is narrower than the downstream OTP implementation lane because it freezes one generic governance contract rather than the full user-facing auth experience.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` tenant-public authorization requirements
  - `endpoints_mvp_contracts.md` runtime/bootstrap contract sections if auth-method config becomes explicit there
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Tenant-public authentication methods are generic platform capabilities, not Belluga-hardcoded product rules.
- [x] `D-02` Landlord is the authority for which tenant-public authentication methods are available in the deployment.
- [x] `D-03` Tenants may edit enabled tenant-public auth methods only when landlord explicitly allows tenant customization.
- [x] `D-04` Tenants can never enable an authentication method that landlord did not list as available.
- [x] `D-05` When tenant customization is disabled, effective tenant-public auth methods equal the landlord-configured set.
- [x] `D-06` When tenant customization is enabled, effective tenant-public auth methods equal the tenant-selected subset of the landlord-configured set.
- [x] `D-07` Belluga Android release remains pinned to effective `phone_otp` for tenant-public auth even if the generic platform also supports `password`.
- [x] `D-08` Contact-match and release-social baseline requirements remain tied to verified phone identity; generic auth configurability does not relax that dependency for Belluga release.
- [x] `D-09` This TODO publishes the governance contract and runtime exposure only; current email/password tenant-public auth enforcement remains unchanged until `TODO-store-release-phone-otp-auth-and-contact-match.md` lands.
- [x] `D-10` Admin-consumer exposure in this TODO is satisfied by backend settings-kernel APIs and deterministic provisioning evidence; new Flutter/admin UI remains deferred.
- [x] `D-11` Belluga release-tenant effective `phone_otp` must be proven through persisted settings or a controlled backend provisioning path, not by undocumented convention.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Landlord owns tenant-public auth-method availability.
- [x] `D-02` Tenant editing is optional and landlord-controlled.
- [x] `D-03` Tenant enabled methods must always be a subset of landlord-available methods.
- [x] `D-04` Belluga store-release runtime remains effectively `phone_otp`.
- [x] `D-05` Runtime exposure in this lane is preparatory and must not silently imply immediate login-path enforcement changes.
- [x] `D-06` Existing settings-kernel admin APIs are sufficient for this lane; Flutter/admin UI consumption remains downstream unless explicit evidence proves otherwise.

## Verified Repository Assumptions
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The settings kernel is the correct mechanism for landlord/tenant auth-method governance rather than a new ad hoc config path. | existing landlord/tenant settings registry, store, and patch-guard pattern already power other cross-cutting runtime features | would require introducing a parallel config system and broadening scope materially | `High` | `Keep as Assumption` |
| `A-02` | Runtime consumers will need the effective auth-method contract, not just raw landlord/tenant settings, to avoid duplicating merge logic in clients. | current environment/bootstrap path already resolves effective runtime data for Flutter | clients would need to duplicate landlord-vs-tenant resolution and drift risk would increase | `High` | `Keep as Assumption` |
| `A-03` | Belluga release must remain effectively `phone_otp` even after the generic platform rule exists because contact matching still depends on verified phone identity. | downstream release TODO and release orchestrator already depend on that identity baseline | Belluga release scope would need reopening, not just unblocking | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `laravel-app/app/Providers/PackageIntegration/SettingsIntegrationServiceProvider.php`
- `laravel-app/app/Integration/Settings/**`
- `laravel-app/app/Application/Environment/EnvironmentResolverService.php`
- `laravel-app/app/Models/Tenants/TenantSettings.php`
- `laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php`
- `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`
- `flutter-app/lib/**` remains out of scope unless planning evidence proves an immediate consumer update is required in this lane

### Ordered Steps
1. Freeze the invariant matrix for landlord availability, tenant-edit enablement, subset containment, normalization, and empty-set behavior before any code changes.
2. Add the canonical auth-governance namespace definitions plus patch-guard ownership for landlord availability and optional tenant subset editing.
3. Add one backend-owned effective resolver that merges landlord and tenant state without requiring clients to reproduce precedence rules.
4. Expose the effective tenant-public auth-method contract through `/api/v1/environment` and existing admin settings-kernel APIs, while keeping current email/password auth routes intentionally unchanged in this lane.
5. Prove deterministic Belluga release-tenant pinning to effective `phone_otp` through persisted settings or a controlled backend provisioning path.
6. Update downstream TODO/docs so Belluga OTP execution resumes as a product-specific effective-configuration cutover rather than a generic platform redesign.

### Worker Ownership
- `laravel-auth-governance`: settings namespace registration, patch guard, effective resolver, environment exposure, and backend tests.
- `orchestrator`: TODO/doc consolidation, dependency edge updates, Belluga release-tenant provisioning evidence, and final validation.
