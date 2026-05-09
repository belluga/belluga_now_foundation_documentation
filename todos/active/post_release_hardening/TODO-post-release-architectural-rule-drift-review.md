# TODO (Post Release): Architectural Rule Drift Review

**Artifact type:** Tactical review contract  
**Status:** Active - opening architecture and security audit passes recorded  
**Lane:** Post Release Hardening  
**Scope:** Cross-stack architecture review, Laravel + Flutter  
**Created:** 2026-05-01  
**Owner:** Delphi  

## Delivery Status Canon

- **Current delivery stage:** Audit-Opening-And-Security-Passes-Recorded
- **Qualifiers:** Post-Release-Hardening, Cross-Stack, Architecture-Review, Security-Adversarial-Review, Attack-Surface-Review, Debt-Discovery, Evidence-Seeded, No-Production-Code-Changes
- **Next exact step:** execute the first-wave owner TODOs created from this review, starting with landlord credential source-of-truth hardening and the new P0/P1 split lanes, while keeping second-wave drift families explicitly queued instead of implicit backlog.
- **Complexity:** big
- **Primary execution profile:** Operational / Coder
- **Active technical scope:** Flutter + Laravel architecture/security audit, documentation/TODO evidence only.
- **Supporting profiles expected:** Strategic / CTO-Tech-Lead if this review proposes canonical contract changes; Assurance / Auditor if any finding needs independent no-context validation before promotion.

## Goal

Run a rule-by-rule architectural review of the Belluga codebase to find hidden drift, workaround implementations, and present-state behavior that violates the intent of Delphi/project rules even when current lint or analyzer checks do not formally fail.

This TODO is intentionally **not exhaustive yet**. The seeded findings below are starting evidence, not the full search space. The audit remains open to additional drift discovered during deeper rule-by-rule inspection, implementation prep, or independent review.

The immediate seed debt is that personal Account Profile behavior is currently hardcoded as `profile_type = personal` in production paths even though Account Profile types are registry-driven and tenant dynamic. This TODO must investigate that debt deeply and identify whether the correct target architecture is:

- a required tenant profile type linked as the personal/default Account type;
- an Account-level or tenant-environment setting that resolves the personal profile type dynamically;
- registry mutation guards that allow label/capability changes while protecting linked/default types;
- delete/rename policies that block unsafe changes while Accounts or Account Profiles still depend on the type.

This TODO is a review and triage authority. Remediation work discovered here should become independent TODOs unless it is strictly documentation of the review result. Where a drift class can be prevented structurally, the follow-up is expected to include PACED-style objective guards (rules, analyzers, deterministic validation, or other enforceable controls) so the same category of regression does not silently recur.

No child TODO derived from this audit should go straight from “finding” to “code fix”. The required sequence is:
1. freeze the violated canonical rule,
2. define the replacement rule and the strongest objective PACED guardrail available,
3. ensure the currently observed drift instance becomes a regression/fixture that proves the guardrail would catch or prevent the same class,
4. then execute remediation against that frozen rule/guardrail baseline.

The 2026-05-01 second pass adds a focused security/adversarial review. It uses current Laravel, Flutter, and OWASP security guidance to identify dangerous patterns, then traces those patterns through the local Laravel and Flutter code. This pass still does not implement fixes; it records security debt candidates that must be split into implementation TODOs before any remediation.

## Framing Source & Story Slice

- **Framing source:** Direct user request on 2026-05-01.
- **Story slice:** Post-release architectural hardening audit.
- **Why direct-to-TODO is valid:** the user explicitly identified a concrete technical debt (`personal` hardcode against dynamic Account Profile types) and requested a broader deep review across backend and frontend rules before deciding which findings should become independent TODOs.

## Canonical Anchors

- `foundation_documentation/project_constitution.md`
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/modules/system_architecture_principles.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/events_module.md`
- `.agents/rules/stack/flutter-architecture-always-on.md`
- `.agents/rules/stack/flutter-contract-alignment-always-on.md`
- `.agents/rules/stack/flutter-controller-workflow-glob.md`
- `.agents/rules/stack/flutter-repository-workflow-glob.md`
- `.agents/rules/stack/flutter-route-workflow-glob.md`
- `.agents/rules/stack/flutter-screen-workflow-glob.md`
- `tool/belluga_analysis_plugin/docs/rules.md`
- `../laravel-app/bootstrap/app.php`
- `../laravel-app/routes/api/**`
- `../laravel-app/config/api_security.php`
- `web/index.html`
- `android/app/src/main/AndroidManifest.xml`
- `android/app/build.gradle.kts`
- `../delphi-ai/rules/laravel/stack/shared/tenant-access-guardrails-model-decision.md`
- `../delphi-ai/rules/laravel/stack/shared/ability-catalog-sync-model-decision.md`

## Scope

- Build an explicit review matrix covering every applicable Flutter, Laravel, shared Delphi, and custom analyzer rule.
- For each rule, inspect whether the code follows only the letter of the rule while bypassing its architectural intent.
- Audit Laravel production paths, tests, seeders, console commands, package integrations, tenant boundaries, ability/catalog sync, registry management, and endpoint contracts.
- Audit Flutter production paths, repositories, controllers, domain models/value objects, route/back governance, backend adapter registration, runtime mocks/fakes, analyzer suppressions, and module-doc contract alignment.
- Audit security-sensitive Laravel paths: route middleware, Sanctum abilities, account/tenant binding, public auth, password reset, OTP, invite sharing, SSRF-capable proxying, API risk matrix coverage, browser security headers, and local secret/keystore operational posture.
- Audit security-sensitive Flutter paths: token storage, app/deep links, Android permissions, release hardening markers, telemetry/debug logging, web shell scripts, and browser header expectations.
- Investigate the Account Personal Profile type debt from model, registry, bootstrap, social graph, admin UI, tests, docs, and migration angles.
- Record findings with evidence, severity, owner area, canonical anchor, and recommendation for independent TODO creation.

## Out of Scope

- Do not change production code under this TODO.
- Do not silently normalize contracts without an explicit follow-up TODO.
- Do not close this TODO by saying "analyzer clean"; static checks are only one input.
- Do not treat legacy/test-only behavior as acceptable until it is classified and bounded by rule.

## Initial Findings Seeded By The Opening Pass

These findings are not final closure evidence. They are seed findings that the full rule-by-rule audit must confirm, refine, or split into separate TODOs.

| ID | Finding | Initial evidence | Initial severity | Follow-up expectation |
| --- | --- | --- | --- | --- |
| `ARCH-DRIFT-001` | Personal Account Profile type is hardcoded in backend bootstrap/social flows instead of resolving a tenant/account-linked profile type. | `../laravel-app/app/Application/AccountProfiles/AccountProfileBootstrapService.php:13`, `:50-52`, `:61-68`; `../laravel-app/app/Application/Social/InviteablePeopleService.php:537-578`; `foundation_documentation/domain_entities.md:59`. | High | Create an independent TODO for dynamic personal-profile type resolution and Account linkage design. |
| `ARCH-DRIFT-002` | Personal profile bootstrap appears to create an `unmanaged` Account and relies on model defaults of public/discoverable visibility, while the platform principle says personal profiles are `user_owned` and private by default. | `../laravel-app/app/Application/AccountProfiles/AccountProfileBootstrapService.php:32-35`; `../laravel-app/app/Models/Tenants/AccountProfile.php:42-45`; `foundation_documentation/modules/system_architecture_principles.md:31`, `:34`. | High | Decide whether docs or code are authoritative; likely independent TODO for ownership/privacy baseline. |
| `ARCH-DRIFT-003` | Account Profile Type update/delete behavior permits rename/cascade and unguarded delete, conflicting with immutable-type documentation and the need to block deletion while linked/default types are in use. | `../laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php:59-160`, `:179-188`; `foundation_documentation/system_roadmap.md:96-97`; `foundation_documentation/modules/tenant_admin_module.md:1216`. | High | Create independent TODO for registry mutation/deletion policy, dependency counts, and admin UX. |
| `ARCH-DRIFT-004` | `tenant:profile-registry:sync-v1` deletes the tenant registry and recreates only V1 defaults, which can erase dynamic tenant types and violates registry-driven behavior. | `../laravel-app/routes/console.php:25-47`; `../laravel-app/app/Application/AccountProfiles/AccountProfileRegistrySeeder.php:16-59`, `:94-97`; `foundation_documentation/modules/system_architecture_principles.md:27-28`. | High | Split into a registry seeding/sync hardening TODO if still present after full audit. |
| `ARCH-DRIFT-005` | Artist-shaped event language still appears in canonical docs despite active direction to use dynamic linked account profiles. | `foundation_documentation/modules/flutter_client_experience_module.md:168`; `foundation_documentation/system_roadmap.md:146`; `foundation_documentation/modules/events_module.md:69`, `:86-100`; `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-event-artists-eradication.md`. | Medium | Reconcile with the active event-artists-eradication TODO; split only if outside that TODO's current scope. |
| `ARCH-DRIFT-006` | Production Flutter `lib/` still contains mock service/database surfaces; quick scan did not prove runtime registration, but their presence under production infrastructure needs rule classification. | `lib/infrastructure/services/http/mock_http_service.dart`; `lib/infrastructure/services/networking/mock_web_socket_service.dart`; `lib/infrastructure/dal/datasources/mock_poi_database.dart`; `foundation_documentation/project_constitution.md` runtime backend mandate. | Medium | Audit whether these are dead/test-only, forbidden production mocks, or need relocation behind fixtures. |
| `ARCH-DRIFT-007` | Flutter domain/value-object layer contains map-shaped value objects and dynamic payload wrappers that may bypass the domain primitive and DTO boundary rules. | `lib/domain/app_data/value_object/push_throttles_value.dart`; `lib/domain/tenant_admin/value_objects/tenant_admin_dynamic_map_value.dart`; `lib/domain/schedule/value_objects/event_friend_resume_payload_value.dart`; `lib/domain/schedule/value_objects/sent_invite_status_payload_value.dart`; `tool/belluga_analysis_plugin/docs/rules.md`. | Medium | Rule-by-rule audit must determine which maps are legitimate opaque settings and which hide transport payloads. |
| `ARCH-DRIFT-008` | Direct route stack mutations in presentation need classification against canonical route/back governance; some may be valid, but visible/system back and no-history behavior must be centralized where required. | `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart:274`, `:426`, `:560`, `:665`; `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart:705`, `:713`; `lib/presentation/tenant_admin/shell/tenant_admin_shell_screen.dart`; `.agents/rules/stack/flutter-route-workflow-glob.md`. | Medium | Build a route-by-route matrix before splitting; avoid treating every `context.router` use as drift. |
| `ARCH-DRIFT-009` | Analyzer/lint suppressions and excluded paths need review to separate valid interop/generated exceptions from architecture bypasses. | `analysis_options.yaml`; `lib/application/application_contract.dart`; `lib/application/application_mobile.dart`; `lib/application/application_web.dart`. | Low/Medium | Inventory every suppression/exclusion and document whether each has an approved architectural reason. |
| `ARCH-DRIFT-010` | Flutter backend adapters include `UnsupportedError` runtime stubs that may be intentional fail-fast placeholders or missing Laravel-backed adapters. | `lib/infrastructure/dal/dao/production_backend/live_only_unsupported_venue_event_backend.dart`; `lib/infrastructure/dal/dao/production_backend/live_only_unsupported_tenant_backend.dart`; `lib/infrastructure/dal/dao/unsupported_static_assets_backend.dart`; `lib/infrastructure/dal/dao/laravel_backend/laravel_backend.dart`. | Medium | Classify each unsupported backend against active runtime surfaces and the no-mock/no-fallback mandate. |

## Required Review Matrix

Opening audit pass recorded on 2026-05-01:

| Rule source | Intent being protected | Search/audit method | Surfaces reviewed | Findings | Split TODO needed |
| --- | --- | --- | --- | --- | --- |
| Account Profile registry rules | Profile types are tenant dynamic and not hidden app enums. Default/personal behavior must be linked/configured, not magic-stringed. | Targeted Laravel service/seeder/console/test scan plus module-doc comparison. | `AccountProfileBootstrapService`, `InviteablePeopleService`, registry management service, registry seeder, `tenant:profile-registry:sync-v1`, Account Profile type tests, Account Profile docs. | `ARCH-DRIFT-001` through `ARCH-DRIFT-004` confirmed. | Yes. Highest-priority split. |
| Laravel tenant access guardrails | Tenant-scoped data cannot leak across tenants or fall back to an arbitrary first tenant. | Route middleware inventory for `auth:sanctum`, `CheckTenantAccess`, package route files, and guardrail-test search. | `public_tenant_maybe_api_v1.php`, `project_tenant_public_api_v1.php`, `project_tenant_admin_api_v1.php`, `tenant_api_v1.php`, package public/admin route files. | No material tenant-data leak confirmed in protected tenant public/admin groups during opening pass. RR-AUTH-02 resolves the appdomain and adjacent domain-management route gap. Residual exact identity-route classification remains for `auth/logout`, `auth/token_validate`, and `/me` in `tenant_api_v1.php`, which keep the file-level tenant-access guardrail at exit `2`. | Not yet. Residual identity-route matrix classification only. |
| Laravel ability/catalog sync | Route/service ability strings must stay cataloged and enforceable. | Extracted route/app `abilities:` middleware values and compared against `config/abilities.php` using shell parsing because `php` is not available in the Flutter environment. | Laravel route files, package route files, `../laravel-app/config/abilities.php`. | No missing ability strings found: 51 used abilities are covered by a 54-entry catalog. | No. |
| Laravel settings kernel contract | Settings kernel routes must stay behind the intended auth/tenant boundary and avoid one-off settings APIs that bypass the kernel. | Settings route scan and targeted package route review. | `project_landlord_admin_api_v1/settings.php`, `project_tenant_package_admin_api_v1/settings.php`, public tenant telemetry/settings routes, push settings package routes. | Tenant package settings routes use `auth:sanctum` + `CheckTenantAccess`; landlord settings routes use landlord auth. No catalog drift found in opening pass. Namespace/schema contract still needs package-level validation if this becomes a focused TODO. | No split from opening pass. |
| Flutter DTO/domain/projection architecture | Domain must remain typed, transport-free, and independent from app/service locator state. | Official analyzer, analyzer rule docs, `rg` for map-shaped value objects, `GetIt` in domain, legacy event projections. | `lib/domain/**`, schedule/event value objects, tenant domain model, app-data values. | `ARCH-DRIFT-005`, `ARCH-DRIFT-007`, `ARCH-DRIFT-012`, and `ARCH-DRIFT-017` confirmed or linked to active TODOs. | Yes for domain purity/value-object hardening; existing TODOs own artist and partner terminology cleanup. |
| Flutter repository workflow | Repositories should consume typed DAO/decoder outputs and should not parse raw transport maps directly. | `rg` and targeted repository review for `response[...]`, raw maps, decoder placement. | `InvitesRepository`, `UserEventsRepository`, repository contracts/backends around invites/events/auth. | `ARCH-DRIFT-014` confirmed: production repositories still read raw response maps after DAO calls. | Yes. |
| Flutter controller workflow | Controllers should orchestrate typed contracts and avoid concrete infrastructure helpers or navigation/context bypasses. | `rg` over controller files for `BuildContext`, `context.router`, concrete infra imports, and location request factory calls. | Discovery, map, event search, tenant home agenda controllers. | No controller navigation bypass confirmed in opening pass. `ARCH-DRIFT-013` is a boundary-smell candidate: controllers import an infrastructure factory that only creates a domain request object. | Maybe; split after deciding correct layer for the factory. |
| Flutter route workflow | Route metadata, resolver hydration, and back/replace behavior must remain centralized enough to preserve no-history and system-back semantics. | Direct `context.router` call inventory and targeted route file review. | Immersive event detail, account profile detail, tenant admin shell, partner detail route. | `ARCH-DRIFT-008` remains candidate; `ARCH-DRIFT-016` adds direct `GetIt` access inside a route `buildScreen`. | Maybe; route-by-route audit should precede implementation. |
| Runtime backend/no-mock mandate | Production runtime should use Laravel-backed adapters or explicit fail-fast placeholders only where the surface is proven inactive. | `rg` for `mock`, `UnsupportedError`, backend registrations, production DAL defaults, `StreamValue` in services. | Mock HTTP/WebSocket/POI services, production and Laravel backend aggregators, unsupported tenant/venue-event/static-assets backends, tenant-admin location selection service. | `ARCH-DRIFT-006`, `ARCH-DRIFT-010`, and `ARCH-DRIFT-015` require classification; at least some surfaces are production-code smells even if not runtime-registered. | Maybe/yes after active-surface classification. |
| Flutter contract alignment rules | Module docs and runtime DTO/domain behavior must describe the same product contract. | Module-doc diff against code terms/routes/contracts. | Auth module/routes, phone OTP docs, event linked account profiles, partner/account profile screens and domain packages. | `ARCH-DRIFT-005`, `ARCH-DRIFT-011`, and `ARCH-DRIFT-017`; each is already partly owned by active TODOs. | Mostly no duplicate split; link to active TODOs unless scope gaps remain. |
| Analyzer/custom lint rules | Static checks must cover architecture intent and not leave hidden workarounds outside rule reach. | `fvm dart analyze --format machine`, custom rule matrix validation, manual drift scan. | Flutter analyzer, `tool/belluga_analysis_plugin/docs/rules.md`, code paths above. | Analyzer is clean and rule fixtures pass, but `ARCH-DRIFT-007`, `ARCH-DRIFT-012`, `ARCH-DRIFT-013`, and `ARCH-DRIFT-014` show coverage gaps or patterns outside current checks. | Maybe; create analyzer-rule hardening TODO after implementation TODOs are scoped. |

## Seed Finding Disposition

| ID | Disposition after opening audit | Split recommendation |
| --- | --- | --- |
| `ARCH-DRIFT-001` | Confirmed. Backend still resolves personal profiles with hardcoded `profile_type = personal`. | Split as highest-priority Account Profile default type TODO. |
| `ARCH-DRIFT-002` | Confirmed. Bootstrap/defaults conflict with documented `user_owned` and private personal profile semantics. | Split with `ARCH-DRIFT-001` or as a direct dependency. |
| `ARCH-DRIFT-003` | Confirmed. Registry rename/delete behavior conflicts with immutable/dependency-guard documentation. | Split with Account Profile registry guardrails. |
| `ARCH-DRIFT-004` | Confirmed. Registry sync command can erase tenant dynamic types and re-seed only V1 defaults. | Split with Account Profile registry guardrails. |
| `ARCH-DRIFT-005` | Confirmed but already owned by active event-artists eradication work. | Do not duplicate unless that TODO excludes the runtime fallback in `eventModelFromRaw`. |
| `ARCH-DRIFT-006` | Candidate confirmed as production-code presence; runtime registration was not proven. | Classify before splitting. |
| `ARCH-DRIFT-007` | Confirmed. Map-shaped domain/value objects bypass the intended typed domain boundary. | Split into Flutter domain/value-object hardening. |
| `ARCH-DRIFT-008` | Candidate. Direct route mutations exist, but each needs route-semantics classification. | Create route audit TODO only if current route docs do not already cover it. |
| `ARCH-DRIFT-009` | Candidate. Suppression inventory exists; no specific harmful suppression was proven in opening pass. | Low-priority lint-suppression audit. |
| `ARCH-DRIFT-010` | Candidate. Unsupported runtime stubs exist in production backend aggregators; intent may be fail-fast. | Classify with runtime backend hardening. |

## Additional Findings From 2026-05-01 Opening Audit Pass

| ID | Finding | Evidence | Severity | Recommendation |
| --- | --- | --- | --- | --- |
| `ARCH-DRIFT-011` | Password auth surfaces still exist in tenant-public backend and Flutter auth layers while release docs state web-native email/password/social login is out of scope and app login is phone-OTP-only. | `foundation_documentation/system_roadmap.md:156`, `:163`; `foundation_documentation/modules/flutter_client_experience_module.md:75`, `:95`, `:152-153`, `:431`; `../laravel-app/routes/api/public_tenant_maybe_api_v1.php:77-107`; `lib/infrastructure/repositories/auth_repository.dart:119`, `:203`, `:240`; `lib/infrastructure/dal/dao/auth_backend_contract.dart:15`, `:22`; `lib/application/router/modular_app/modules/auth_module.dart:53`, `:61`. | High | Link to active store-release OTP/web-to-app TODOs first; split only if those TODOs do not retire or gate password surfaces. |
| `ARCH-DRIFT-012` | A domain model directly resolves `AppData` through `GetIt`, coupling domain behavior to global app/runtime state. | `lib/domain/tenant/tenant.dart:13`, `:34`, `:48-55`. | High | Split into Flutter domain purity hardening; `Tenant.hasDomain` should receive app type/context through a typed boundary rather than service locator access. |
| `ARCH-DRIFT-013` | Multiple controllers import a concrete infrastructure factory to create a domain request object, suggesting a misplaced helper that lets controller code bypass the intended contract layering. | `lib/infrastructure/services/location_origin_resolution_request_factory.dart:1-40`; `event_search_screen_controller.dart:656`; `discovery_screen_controller.dart:570`; `map_screen_controller.dart:886`; `tenant_home_agenda_controller.dart:1060`. | Medium | Move/classify the factory under domain/application or expose it through a contract if the pattern is valid. |
| `ARCH-DRIFT-014` | Production repositories still parse raw response maps after backend calls, instead of receiving typed DAO/decoder outputs. | `lib/infrastructure/repositories/invites_repository.dart:92-99`, `:111-118`; `lib/infrastructure/repositories/user_events_repository.dart:70-72`. | High | Split into repository boundary hardening; move response-shape parsing to DAO/DTO decoders. |
| `ARCH-DRIFT-015` | A production service owns shared `StreamValue` state directly, which may bypass repository/controller state ownership rules. | `lib/infrastructure/services/tenant_admin/tenant_admin_location_selection_service.dart:6-19`. | Medium | Classify whether this is allowed ephemeral selection state or should live behind a controller/application contract. |
| `ARCH-DRIFT-016` | A route `buildScreen` resolves `AppData` through `GetIt` to derive profile visuals, mixing route resolution with global app-data access. | `lib/presentation/tenant_public/partners/routes/partner_detail_route.dart:27-34`. | Medium | Include in route/resolver audit; decide whether visual registry data should arrive via resolver/model/application context. |
| `ARCH-DRIFT-017` | Partner terminology remains broad in Flutter domain/presentation while canonical docs prefer Account Profile language and an active VNext TODO already tracks retirement. | `lib/domain/partner/**`, `lib/domain/partners/**`, `PartnerDetailRoute`, `PartnerProfileConfig`, `foundation_documentation/domain_entities.md:61`, `foundation_documentation/todos/active/vnext/TODO-vnext-partner-terminology-retirement-and-account-profile-language-normalization.md`. | Medium | Do not duplicate now; ensure active VNext TODO covers runtime package/domain names, routes, and UI copy. |

## Security Baseline Sources For 2026-05-01 Second Pass

These external baselines were used only to frame smells and attack paths before tracing local code. They do not by themselves prove a local vulnerability; each finding below includes local evidence.

- OWASP API Security Top 10 2023: broken object-level authorization/BOLA, unrestricted resource consumption, SSRF, and broken function-level authorization patterns: `https://owasp.org/API-Security/editions/2023/en/0xa1-broken-object-level-authorization/`, `https://owasp.org/API-Security/editions/2023/en/0xa4-unrestricted-resource-consumption/`, `https://owasp.org/API-Security/editions/2023/en/0xa7-server-side-request-forgery/`.
- OWASP Forgot Password Cheat Sheet: reset-token entropy, lifecycle, rate limiting, and user-enumeration controls: `https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet.html`.
- OWASP Content Security Policy Cheat Sheet: browser script policy and CDN/script hardening: `https://cheatsheetseries.owasp.org/cheatsheets/Content_Security_Policy_Cheat_Sheet.html`.
- OWASP MASVS: mobile security verification framing for token storage, permissions, platform interaction, network, and resilience controls: `https://mas.owasp.org/MASVS/03-Using_the_MASVS/`.
- Laravel Sanctum and routing/rate-limiting documentation: token ability behavior and throttle primitives: `https://laravel.com/docs/12.x/sanctum`, `https://laravel.com/docs/12.x/routing`.
- Flutter app links and obfuscation documentation: Android/iOS app link trust and release artifact hardening: `https://docs.flutter.dev/cookbook/navigation/set-up-app-links`, `https://docs.flutter.dev/deployment/obfuscate`.

## Security Findings From 2026-05-01 Second Pass

Severity here is triage severity. Each item must be revalidated with focused tests in its implementation TODO before being treated as a closed exploit fix.

| ID | Finding | Evidence | Severity | Attack/impact | Recommendation |
| --- | --- | --- | --- | --- | --- |
| `SEC-DRIFT-001` | Tenant app-domain admin routes are not protected by the same tenant access and ability guardrails as normal domain routes. | `../laravel-app/bootstrap/app.php:82`, `:146`; `../laravel-app/routes/api/tenant_api_v1.php:45`, `:64-68`; `../laravel-app/app/Http/Middleware/LandlordValidation.php`; `../laravel-app/app/Http/Api/v1/Controllers/TenantAppDomainController.php:28`, `:59`; `../laravel-app/app/Http/Api/v1/Requests/TenantAppDomainRequest.php:15`. | High/Critical | Any authenticated landlord principal may be able to read/mutate app-domain identifiers for a tenant without proving tenant membership/role or an explicit domain-management ability. This directly affects Android/iOS app-link trust and web-to-app promotion. | Split a P0 TODO to add tenant access + explicit ability coverage, define the ability name, and add regression tests for no tenant access, missing ability, read, store, and delete. |
| `SEC-DRIFT-002` | Account-scoped abilities are copied into Sanctum tokens and are not bound to the currently selected account. | `../laravel-app/routes/api/account_api_v1.php:8-60`; `../laravel-app/routes/api/project_account_api_v1.php:8-20`; `../laravel-app/bootstrap/app.php:152-157`; `../laravel-app/app/Http/Middleware/CheckUserAccess.php:42`, `:53`; `../laravel-app/app/Models/Tenants/AccountUser.php:117-122`; `../laravel-app/app/Application/Auth/AccountAuthenticationService.php:28-44`; `../laravel-app/app/Application/Auth/TenantPhoneOtpAuthService.php:351-365`; `../laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php:17-41`. | High/Critical | A user with elevated permission in Account A and lower permission in Account B can plausibly carry Account A abilities into Account B routes if membership exists, creating broken function-level authorization across accounts. | Split a P0 TODO to bind tokens to `account_id` or recompute permission against the current account in middleware/policy; add tests for mixed-role multi-account users. |
| `SEC-DRIFT-003` | Password/public auth can fail open, and password reset tokens have weak lifecycle controls. | `../laravel-app/routes/api/public_tenant_maybe_api_v1.php:93-106`; `../laravel-app/app/Application/Auth/TenantPublicAuthMethodResolver.php:15`, `:64-66`, `:138`; `../laravel-app/app/Application/Profiles/TenantProfileService.php:39-72`, `:193-195`; `../laravel-app/app/Application/Profiles/LandlordProfileService.php:39-72`, `:193-195`; password reset token migrations in tenant and landlord user tables. | High | If password auth remains default-enabled or is accidentally enabled, 6-digit reset tokens with no explicit expiry/single-use consumption in service code become an online brute-force and account-takeover risk. This also conflicts with OTP-only release direction from `ARCH-DRIFT-011`. | Split a P0/P1 auth hardening TODO: fail closed to OTP-only unless explicitly configured, use high-entropy hashed single-use expiring reset tokens, consume/delete after use, and add endpoint-specific throttles and tests. |
| `SEC-DRIFT-004` | OTP and high-abuse public auth endpoints are not explicitly represented in the API risk matrix. | `../laravel-app/routes/api/public_tenant_maybe_api_v1.php:93-95`; local OTP controls in `../laravel-app/app/Application/Auth/TenantPhoneOtpAuthService.php:46-64`, `:119-138`, `:392`; matrix defaults in `../laravel-app/config/api_security.php:5-62`, `:93`, `:117`, `:174`. | Medium/High | OTP code handling has local safeguards, but challenge/verify/login/reset/anonymous identity/invite endpoints can still be abused for guessing, spam, enumeration, or resource pressure if they fall back to broad default throttles. | Add risk-matrix entries and tests for OTP challenge/verify, login, password reset token/reset, anonymous identities, invite share, and external image proxy. Keys should combine tenant, normalized target, IP, and device context where appropriate. |
| `SEC-DRIFT-005` | External image proxy has SSRF residual risk because it accepts HTTP and resolves host safety separately from the eventual connection. | `../laravel-app/routes/api/tenant_api_v1.php:278`; `../laravel-app/app/Http/Api/v1/Requests/ExternalImageProxyRequest.php:27`; `../laravel-app/app/Application/Media/ExternalImageProxyService.php:14-20`, `:35-38`, `:120-141`, `:176`, `:220`. | Medium/High | Existing byte limits, timeout, no-auto-redirect, content-type, and public-IP checks are positive controls, but cleartext HTTP and DNS time-of-check/time-of-use gaps leave rebinding/redirect-edge SSRF risk. | Split a SSRF hardening TODO: prefer HTTPS-only, pin/connect to the resolved safe IP or enforce egress blocklists, add redirect-to-private and DNS-rebinding tests, and place the endpoint in the risk matrix. |
| `SEC-DRIFT-006` | Public invite share preview returns rich social/event data and lacks endpoint-specific enumeration throttle evidence. | `../laravel-app/routes/api/packages/project_tenant_public_api_v1/invites.php:15`; `../laravel-app/app/Application/Social/InviteShareService.php:168-189`, `:344`; `../laravel-app/app/Http/Api/v1/Requests/ContactsImportRequest.php:12`, `:25`. | Medium | Invite codes are high entropy, but the unauthenticated preview response includes tenant/code/target/inviter/event details and may support privacy leakage or scraping if codes leak or guessing is automated. | Add rate/miss telemetry and response-minimization review for unauthenticated preview. Keep only product-required display fields before authentication. |
| `SEC-DRIFT-007` | Flutter web shell uses an unpinned third-party CDN script and no global CSP/security-header coverage was found. | `web/index.html:5`; `../docker/nginx/prod.conf.template:95-96`; `../laravel-app/app/Http/Middleware/ApiSecurityHardening.php:1077-1095`; repository scan found no global `Content-Security-Policy`, HSTS, Referrer-Policy, or Permissions-Policy definition. | Medium | Loading `hls.js@latest` without SRI/CSP permits supply-chain or CDN drift to change web runtime behavior, and missing browser headers reduce defense-in-depth against script injection and framing/referrer leakage. | Pin exact script version with integrity or self-host/bundle it; define CSP and global security headers at the edge/web shell; smoke-test deployed headers. |
| `SEC-DRIFT-008` | Mobile release hardening and telemetry redaction are not documented/enforced as a launch gate. | `android/app/src/main/AndroidManifest.xml:5-7`, `:14`, `:31`; `android/app/build.gradle.kts:28`, `:66-78`, `:131-132`, `:148-166`, `:184-196`; `lib/application/configurations/belluga_constants.dart:48-49`; `lib/main.dart:24-25`; `lib/infrastructure/services/telemetry/telemetry_route_observer.dart:25`; `lib/application/application_contract.dart:437`; `lib/infrastructure/services/telemetry/telemetry_queue.dart:55`; token storage uses `FlutterSecureStorage` in auth repositories. | Medium | Broad permissions, exported/deep-link surfaces, empty release hardening block, Sentry defaults, and debug-style telemetry need an explicit mobile security gate. App-link risk is amplified by `SEC-DRIFT-001`. | Create a mobile hardening TODO for obfuscation/symbol handling, minify/shrink decisions, permission review, app-link verification, Sentry environment/sample-rate controls, and log/telemetry redaction. |
| `SEC-DRIFT-009` | Secret and release-key material exists locally under the repository tree; ignored/untracked status lowers but does not eliminate operational risk. | Local inventory found `./android/keystores/guarappari-release-key.jks`, Laravel `.env`, and `.env.testing`; ignore rules include `/android/keystores/`, `**/*.jks`, `.env`, and `.env.testing`; no tracked env/keystore leak was identified in this pass. | Low/Medium | Release signing keys and environment files near source can still leak through archives, container mounts, CI artifacts, or manual copies even when ignored by Git. | Add an operational security TODO/gate proving key custody, CI secret injection, artifact/image exclusion, and automated secret scanning in promotion flow. |

## Independent TODO Candidate Queue

| Priority | Candidate TODO | Findings covered | Notes |
| --- | --- | --- | --- |
| P0 | Account Profile default/personal type resolution and registry guardrails | `ARCH-DRIFT-001` through `ARCH-DRIFT-004` | Should define where the linked personal/default type lives, how social/invite flows resolve it, what can be renamed, and when delete/sync is blocked. |
| P1 | Flutter repository transport-boundary hardening | `ARCH-DRIFT-014` | Focus on repositories that still read raw maps after DAO calls; add tests around typed backend/decoder contracts. |
| P1 | Flutter domain purity and typed value-object hardening | `ARCH-DRIFT-007`, `ARCH-DRIFT-012` | Remove service-locator access from domain and replace dynamic map payload wrappers with typed value objects or bounded opaque contracts. |
| P2 | Runtime backend/mock/stub classification and hardening | `ARCH-DRIFT-006`, `ARCH-DRIFT-010`, `ARCH-DRIFT-015` | First classify active runtime surfaces vs dead/test-only artifacts; then relocate, remove, or explicitly fail fast. |
| P2 | Route/resolver/back-governance audit | `ARCH-DRIFT-008`, `ARCH-DRIFT-016` | Build a route-by-route matrix before changing navigation code. |
| P2 | Analyzer rule coverage expansion | `ARCH-DRIFT-007`, `ARCH-DRIFT-012`, `ARCH-DRIFT-013`, `ARCH-DRIFT-014` | Static analyzer is clean today; this TODO would close gaps exposed by manual review. |
| Existing TODO | Event artist residue eradication | `ARCH-DRIFT-005` | Already tracked by `TODO-store-release-event-artists-eradication.md`; confirm it includes runtime fallback synthesis. |
| Existing TODO | Phone OTP auth and web-to-app conversion | `ARCH-DRIFT-011` | Already overlaps `TODO-store-release-phone-otp-auth-and-contact-match.md` and `TODO-store-release-web-to-app-conversion-gate.md`. |
| Existing TODO | Partner terminology retirement | `ARCH-DRIFT-017` | Already tracked by `TODO-vnext-partner-terminology-retirement-and-account-profile-language-normalization.md`. |

## Security Independent TODO Candidate Queue

| Priority | Candidate TODO | Findings covered | Notes |
| --- | --- | --- | --- |
| P0 | Tenant app-domain authorization and app-link integrity hardening | `SEC-DRIFT-001` | Should cover route middleware, tenant access, explicit abilities, app-domain mutation tests, and Android/iOS app-link trust impact. |
| P0 | Account-scoped token/ability binding | `SEC-DRIFT-002` | Should decide between token `account_id` binding and current-account permission recomputation, then test mixed-role multi-account users. |
| P0/P1 | Public auth, password reset lifecycle, and auth risk matrix hardening | `SEC-DRIFT-003`, `SEC-DRIFT-004`, linked to `ARCH-DRIFT-011` | Should resolve OTP-only release direction, fail-closed auth method configuration, reset-token entropy/lifecycle, and endpoint-specific throttles. |
| P1 | External image proxy SSRF hardening | `SEC-DRIFT-005` | Should validate HTTPS-only policy, DNS/connect pinning or egress controls, redirect/private-IP tests, and risk matrix classification. |
| P1 | Public invite preview abuse/privacy hardening | `SEC-DRIFT-006` | Should minimize unauthenticated response fields and add miss telemetry/rate limiting for code lookup. |
| P1/P2 | Web CSP/security headers and CDN pinning | `SEC-DRIFT-007` | Should pin or self-host browser dependencies and define deploy-tested CSP/HSTS/referrer/permissions headers. |
| P2 | Mobile release, app-link, permission, and telemetry hardening gate | `SEC-DRIFT-008` | Should cover Android release artifact hardening, permission rationale, app-link verification, Sentry configuration, and log redaction. |
| P2 | Secrets/key custody and promotion secret-scanning gate | `SEC-DRIFT-009` | Should prove release key custody and artifact/image exclusion without documenting secret values. |

## 2026-05-06 Orchestration Freeze

- **Feature brief authority:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Execution policy:** create explicit owner TODOs for the first-wave P0/P1 drift families before starting remediation code, and keep second-wave families queued with named reasons instead of leaving them as informal notes.

### First-Wave Owner TODOs Created

| Wave | Owner TODO | Drift Family | Findings Covered | Notes |
| --- | --- | --- | --- | --- |
| `wave-1` | `TODO-post-release-account-profile-default-personal-type-resolution.md` | dynamic personal/default type resolution | `ARCH-DRIFT-001`, `ARCH-DRIFT-002` | Splits linked-type semantics away from broader registry mutation policy. |
| `wave-1` | `TODO-post-release-account-profile-registry-mutation-and-sync-guardrails.md` | registry mutation and destructive sync | `ARCH-DRIFT-003`, `ARCH-DRIFT-004` | Sibling to the linked-type TODO; owns rename/delete/sync guardrails. |
| `wave-1` | `TODO-post-release-tenant-app-domain-authorization-and-app-link-integrity-hardening.md` | tenant app-domain authorization | `SEC-DRIFT-001` | P0 security split. |
| `wave-1` | `TODO-post-release-account-scoped-token-ability-binding.md` | account-scoped token/ability binding | `SEC-DRIFT-002` | P0 security split. |
| `wave-1` | `TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md` | public auth/reset/risk matrix | `SEC-DRIFT-003`, `SEC-DRIFT-004`, linked `ARCH-DRIFT-011` | Must stay bounded against existing OTP/web-to-app owner TODOs. |
| `wave-1` | `TODO-post-release-flutter-repository-transport-boundary-hardening.md` | repository raw-transport parsing | `ARCH-DRIFT-014` | P1 architecture split. |
| `wave-1` | `TODO-post-release-flutter-domain-purity-and-typed-value-object-hardening.md` | domain purity and typed value objects | `ARCH-DRIFT-007`, `ARCH-DRIFT-012` | P1 architecture split. |
| `wave-1` | `TODO-post-release-analyzer-rule-coverage-expansion-for-drift-fixtures.md` | objective static guardrail follow-through | `ARCH-DRIFT-007`, `ARCH-DRIFT-012`, `ARCH-DRIFT-014` | Guardrail-only lane sequenced after the runtime fixes freeze their corrected boundaries. |

### Existing Owner TODOs Confirmed

| Existing TODO | Family | Why It Remains the Owner |
| --- | --- | --- |
| `TODO-post-release-landlord-password-credential-source-of-truth-hardening.md` | landlord auth credential drift | Runtime-proven bounded backend/auth defect with its own closure lane. |
| `TODO-v1-query-path-guardrails-hardening.md` | runtime query-path anti-patterns | Existing bounded rule lane; only split further if `event search` needs its own behavior/index strategy lane. |
| `TODO-post-release-tenant-public-boundary-policy-centralization.md` | tenant-public local auth/promotion drift | Existing bounded centralization lane with explicit boundary matrix. |
| `TODO-store-release-event-artists-eradication.md` | event `artists` dynamic-contract drift | Existing runtime/doc residue owner. |
| `TODO-store-release-media-host-agnostic-public-urls-and-tenant-cors-cache.md` + `TODO-store-release-belluga-media-canonical-image-flow-hardening.md` | media/public-URL drift family | Narrow host-bound URL lane stays ahead of the broader media hardening lane. |
| `TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md` | dependent-capability guardrail blocker | Existing blocker owner for the proximity family. |

### Second-Wave Drift Families Kept Explicitly Queued

| Deferred Family | Why Deferred Instead of Split Now | Planned Trigger |
| --- | --- | --- |
| Runtime backend/mock/stub classification and hardening | Needs classification against active runtime surfaces before bounded implementation planning. | Start after the first-wave repository/domain/query families stop moving the same surfaces. |
| Route/resolver/back-governance audit | Must coordinate with the existing tenant-public boundary centralization lane and avoid duplicating route-family ownership. | Start after the tenant-public boundary TODO freezes its executable replacement path. |
| SSRF, invite-preview abuse/privacy, web CSP/header hardening, mobile hardening, and secrets custody | Security families are real but not on the first critical remediation path requested by the drift review itself. | Open once the first-wave P0/P1 queue is advancing cleanly or if new runtime evidence escalates severity. |

## No Material Drift Confirmed In Opening Pass

- Ability catalog sync: shell extraction found 51 used `abilities:` strings and all are present in the 54-entry `config/abilities.php` catalog.
- Tenant public/admin protected route groups: scanned authenticated tenant public/admin package routes consistently apply `CheckTenantAccess`; RR-AUTH-02 resolved appdomain and adjacent domain-management route classification. Residual `tenant_api_v1.php` identity routes `auth/logout`, `auth/token_validate`, and `/me` still need explicit route-family classification or route-level waiver metadata before the file-level tenant-access guard can pass.
- Controller navigation bypass: controller-file scan did not find direct `context.router` navigation in the reviewed controller set.
- Settings kernel route boundary: tenant package settings routes are behind `auth:sanctum` + `CheckTenantAccess`; landlord settings routes are landlord-authenticated. No opening-pass split is recommended.

## No Material Security Drift Confirmed In Second Pass

- Laravel mass-assignment scan did not identify production paths that directly persist `$request->all()` or broad request payloads into models in the reviewed security-sensitive surfaces; most reviewed write paths use `validated()` request data. This is not an exhaustive proof and should be retested per implementation TODO.
- Ability catalog sync had already passed in the opening pass; the second pass focused on whether ability checks are attached to the right current tenant/account context, which produced `SEC-DRIFT-001` and `SEC-DRIFT-002`.
- OTP verification has positive local controls: hashed challenge code storage, cooldown, max-attempt lockout, and `random_int` code generation. The remaining debt is risk-matrix coverage and endpoint-level abuse hardening, not absence of OTP controls.
- External image proxy has positive local SSRF controls: byte limit, timeout, no automatic redirect following, content-type validation, and public-IP filtering. The remaining debt is HTTPS-only/DNS-connection hardening and explicit risk-matrix classification.
- Flutter auth token scan found `FlutterSecureStorage` use in the auth repositories rather than SharedPreferences/localStorage for user/landlord tokens.
- Deep-link/open-app path normalization reviewed in Flutter route code and Laravel web-to-app promotion code did not confirm an external-host open redirect in this pass.
- Contacts import has a bounded request limit of 500 contacts; no unbounded contact-import resource issue was confirmed.
- Local `.env` and Android keystore material were ignored/untracked in this pass; this is recorded as operational hardening debt, not as a confirmed committed-secret leak.

## Account Personal Profile Debt Questions

The independent remediation design must answer these before implementation:

- Where is the tenant/account-linked personal/default Account Profile type stored?
- Is the linked type global per tenant, per Account, or derived from an Account ownership/profile invariant?
- Can admins rename the linked type key, or only change label/capabilities?
- What happens to existing personal Account Profiles if the linked type changes?
- Which registry mutations are blocked while Account/Profile dependencies exist?
- Should delete be blocked when any Account Profile uses the type, or only when the type is linked as the personal/default Account type?
- How does invite/social/friend logic identify personal profiles without a hardcoded string?
- What migration/backfill is needed for existing tenants?
- What tests prove that no production code relies on `profile_type == 'personal'` as a magic enum?

## Acceptance Criteria

- [x] A rule-by-rule opening review matrix is added to this TODO under `foundation_documentation/todos/active/post_release_hardening`.
- [x] Every seeded finding is either confirmed, rejected with evidence, or merged into a more precise finding.
- [x] Confirmed findings include severity, affected stack/rule family, canonical anchor or representative files, and recommended owner direction.
- [x] The hardcoded personal profile type debt has a dedicated architecture recommendation and a proposed independent TODO.
- [x] Drift that already belongs to an active TODO is linked to that TODO instead of duplicated.
- [x] A second security pass records external baseline sources, Laravel/Flutter attack-surface scans, and security findings.
- [x] Security findings that need remediation are queued as independent TODO candidates instead of implemented here.
- [x] Confirmed drift classes that can be prevented mechanically have an explicit PACED follow-up path (rule, analyzer coverage, deterministic validation, or equivalent guardrail) captured in the split TODO or decision record.
- [x] Drift selected for remediation is split into independent TODOs with scope, DoD, and validation gates after owner decision.
- [x] Drift-derived child TODOs freeze violated rule + replacement rule + objective PACED guardrail before any remediation stage is treated as approval-clean.
- [x] Drift-derived child TODOs use the currently observed drift instance as one of the validation fixtures proving the guardrail is effective, rather than validating only idealized synthetic cases.
- [x] The review explicitly states which scanned rules had no findings and why.

## Validation Gates For This Review

- [x] Read Delphi/project bootstrap instructions and pass context verification before creating this TODO.
- [x] Run the official Flutter analyzer gate at least once: `fvm dart analyze --format machine`.
- [x] Run custom analyzer rule fixture coverage: `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`.
- [x] Run targeted `rg` inventories for hardcoded profile types, direct backend fallbacks, mocks/fakes in production code, route stack mutations, analyzer suppressions, and legacy event/profile terms.
- [x] Reference relevant Laravel architecture guardrails and route/ability inventories before classifying backend findings.
- [x] Run targeted security inventories for Laravel route middleware, Sanctum abilities/token scope, auth reset/OTP flows, SSRF proxy behavior, invite/share public endpoints, security headers, local secrets/keystores, Android permissions, Flutter token storage, deep-link handling, telemetry/debug logging, and release hardening markers.
- [x] Preserve all command output summaries in the completion evidence section.

## Completion Evidence

- 2026-05-01: `bash delphi-ai/verify_context.sh` passed from `flutter-app`.
- 2026-05-01: `fvm dart analyze --format machine` completed with exit code `0` and no machine-format diagnostics.
- 2026-05-01: `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` completed with exit code `0`; expected `54` lint codes were detected.
- 2026-05-01: Targeted inventories reviewed hardcoded profile types, registry sync/mutation, tenant route middleware, ability catalog sync, settings kernel routes, password auth surfaces, mock/unsupported runtime adapters, route stack mutations, domain `GetIt`, map-shaped value objects, repository raw response parsing, analyzer suppressions, and legacy event/profile terminology.
- 2026-05-01: Ability catalog inventory found 51 used `abilities:` strings and 54 catalog entries; no used ability string was missing from `../laravel-app/config/abilities.php`. `php` is not available in this Flutter environment, so the comparison used shell extraction/parsing rather than loading the PHP config.
- 2026-05-01: Laravel route/guardrail pass did not run the Laravel test suite from this workspace; backend classifications rely on route/source inspection and should be revalidated in the Laravel environment before implementation TODO closure.
- 2026-05-01: Opening-pass review dispositioned `ARCH-DRIFT-001` through `ARCH-DRIFT-017`, with a P0 recommendation to split Account Profile default/personal type resolution and registry guardrails first.
- 2026-05-01: Security second pass researched Laravel/Flutter/OWASP security baselines, then traced local code for authorization binding, auth/reset abuse, SSRF, public invite preview exposure, browser hardening, mobile release hardening, telemetry, app links, and secret/keystore operational posture.
- 2026-05-01: Security second pass recorded `SEC-DRIFT-001` through `SEC-DRIFT-009`, with P0 recommendations for tenant app-domain authorization, account-scoped token/ability binding, and public auth/password-reset/risk-matrix hardening.
- 2026-05-01: Security second pass did not run backend or Flutter test suites because this TODO is an audit/documentation artifact; implementation TODOs must add focused regression tests before closure.
- 2026-05-01: No production code was changed under this TODO; only this documentation/TODO artifact was updated.
