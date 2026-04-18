# Title
Store Release Landlord Tenant Auth Method Governance

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The current Belluga phone-OTP release lane assumes `phone_otp` as if it were the global platform rule for tenant-public authentication. That is not acceptable for the Laravel boilerplate: the generic platform must remain capable of multiple authentication methods while each product/tenant resolves an effective allowed subset through governance. Without that upstream baseline, Belluga-specific release behavior would be baked into the generic auth layer and there would be no canonical answer to whether tenants may customize their allowed authentication methods.

The repository already contains a generic settings kernel with landlord and tenant scopes, namespace registration, and patch guards. What is missing is an auth-method governance namespace plus deterministic runtime resolution for tenant-public authentication.

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
- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Platform-Baseline`, `Cross-Stack`, `Release-Blocker`
- **Next exact step:** freeze the generic auth-governance contract and wire the downstream Belluga release TODOs to this authority before any further tenant-public auth cutover work resumes.

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`

## Current Implementation Snapshot (Repository Scan 2026-04-18)
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

## Scope
- [ ] Define a canonical tenant-public auth-method registry contract for the generic platform, with initial release-relevant entries including `password` and `phone_otp`.
- [ ] Define landlord-owned settings for which tenant-public auth methods are available in the deployment.
- [ ] Define whether landlord may allow tenant admins to choose an enabled subset from the landlord-available methods.
- [ ] Define tenant-owned settings for enabled auth methods only when landlord explicitly permits tenant customization.
- [ ] Enforce that tenants can never enable an auth method that landlord did not expose as available.
- [ ] Define deterministic effective tenant-public auth-method resolution for runtime consumers:
  - when tenant customization is disabled, effective methods equal the landlord-configured set;
  - when tenant customization is enabled, effective methods equal the tenant-selected subset of the landlord-configured set.
- [ ] Expose effective tenant-public auth-method configuration to the relevant runtime/admin consumers.
- [ ] Freeze Belluga Android release configuration so the effective tenant-public auth-method set remains `phone_otp` for the release tenant even though the generic platform may also support `password`.
- [ ] Keep the downstream Belluga contact-match dependency explicit: verified phone identity remains required for the store-release social baseline even if the generic platform supports other auth methods.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<planned>`, `laravel-app:<planned>`, `flutter-app:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Generic auth-governance contract + settings/runtime resolution | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] Implementing every future auth method or third-party auth provider.
- [ ] The downstream Belluga phone-OTP challenge/verify UX and contact-match flow; those remain owned by `TODO-store-release-phone-otp-auth-and-contact-match.md`.
- [ ] Landlord/admin authentication redesign.
- [ ] Authenticated web/QR login or workspace-web session expansion.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** settings-kernel namespace registration, effective-resolution runtime exposure, release-critical docs, and minimal admin/runtime consumer adjustments needed to make the governance contract real.
- **Must update or split the TODO:** Belluga OTP UX polish, broad auth-provider implementation work beyond the release-relevant registry entries, or unrelated settings/admin feature work.

## Definition of Done
- [ ] Generic tenant-public auth-method governance is documented and no longer implied only by discussion.
- [ ] Landlord-owned availability and tenant-edit permission rules are frozen and implemented.
- [ ] Tenant selection, when allowed, is enforced as a subset of landlord-available auth methods.
- [ ] Effective tenant-public auth-method resolution is available to runtime/admin consumers through a canonical backend contract.
- [ ] Belluga release configuration can resolve deterministically to `phone_otp` without turning that product choice into a platform-wide boilerplate rule.
- [ ] The downstream Belluga phone-OTP TODO is explicitly unblocked by this artifact rather than continuing to redefine generic auth policy.

## Validation Steps
- [ ] Backend tests prove effective-resolution rules for:
  - landlord-only configuration;
  - tenant override allowed with valid subset;
  - tenant override rejected when it includes non-landlord methods.
- [ ] Backend tests prove runtime/admin consumers receive the effective auth-method contract.
- [ ] TODO references are updated so `TODO-store-release-phone-otp-auth-and-contact-match.md` is explicitly blocked by this TODO until closure.
- [ ] Belluga release orchestration reflects this TODO as an upstream publication blocker.

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

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Landlord owns tenant-public auth-method availability.
- [x] `D-02` Tenant editing is optional and landlord-controlled.
- [x] `D-03` Tenant enabled methods must always be a subset of landlord-available methods.
- [x] `D-04` Belluga store-release runtime remains effectively `phone_otp`.

## Verified Repository Assumptions
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The settings kernel is the correct mechanism for landlord/tenant auth-method governance rather than a new ad hoc config path. | existing landlord/tenant settings registry, store, and patch-guard pattern already power other cross-cutting runtime features | would require introducing a parallel config system and broadening scope materially | `High` | `Keep as Assumption` |
| `A-02` | Runtime consumers will need the effective auth-method contract, not just raw landlord/tenant settings, to avoid duplicating merge logic in clients. | current environment/bootstrap path already resolves effective runtime data for Flutter | clients would need to duplicate landlord-vs-tenant resolution and drift risk would increase | `High` | `Keep as Assumption` |
| `A-03` | Belluga release must remain effectively `phone_otp` even after the generic platform rule exists because contact matching still depends on verified phone identity. | downstream release TODO and release orchestrator already depend on that identity baseline | Belluga release scope would need reopening, not just unblocking | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `laravel-app/app/Providers/PackageIntegration/SettingsIntegrationServiceProvider.php`
- `laravel-app/app/Integration/Settings/**`
- `laravel-app/app/Application/Environment/EnvironmentResolverService.php`
- `laravel-app/routes/api/**`
- `flutter-app/lib/**` only if runtime/admin consumers must decode the new effective auth-method contract in this lane

### Ordered Steps
1. Freeze the generic auth-governance decisions and link this TODO as the upstream blocker for the downstream Belluga OTP lane.
2. Add the canonical settings namespace(s) and effective-resolution logic for landlord and optional tenant override behavior.
3. Expose the effective tenant-public auth-method contract to the relevant runtime/admin consumers.
4. Pin Belluga release configuration to effective `phone_otp` without removing generic `password` capability from the platform.
5. Update downstream TODO/docs so Belluga OTP execution resumes as a product-specific effective-configuration cutover rather than a generic platform redesign.

