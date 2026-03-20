# TODO (V1): Tenant-Admin Onboarding-Only Account + Profile Create

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Completed (Production-Ready)  
**Owners:** Laravel Team + Flutter Team  
**Created:** 2026-03-07  
**Complexity:** `medium`  
**Checkpoint policy:** one full review checkpoint before approval (Plan Review Gate), then implementation.

---

## Goal
Establish the canonical tenant-admin onboarding write path for `EnvironmentType=tenant`, `Main Scope=tenant_admin`, `Subscope=n/a` so manual tenant-admin creation always happens through one onboarding request that creates the Account, default Admin role template, and its 1:1 Account Profile together. Project-specific Laravel routes must reject standalone account create and standalone account-profile create, and Flutter must present the flow as one created onboarding item rather than two independent resources.

## Current Evidence
- `TD-001` Flutter still performs a two-step write from one controller action: create account, then create profile. Evidence: `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart:419-449`.
- `TD-002` Flutter repositories still target separate endpoints for onboarding semantics. Evidence: `lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart:184-214`, `lib/infrastructure/repositories/tenant_admin/tenant_admin_account_profiles_repository.dart:76-122`.
- `TD-003` Laravel currently exposes standalone tenant-admin create routes for both resources. Evidence: `routes/api/tenant_api_v1.php:98-112`, `routes/api/tenant_api_v1.php:158-172`.
- `TD-004` Laravel account creation and profile creation run through separate service boundaries today, so there is no shared orchestration contract across both resources. Evidence: `app/Application/Accounts/AccountManagementService.php:43-73`, `app/Application/AccountProfiles/AccountProfileManagementService.php:27-86`.
- `TD-005` Flutter still exposes standalone profile creation as a normal tenant-admin remediation path from account detail. Evidence: `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:98-101`, `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:316-322`, `lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_create_screen.dart:497`.
- `TD-006` Flutter create success is still treated as an operation result (`maybePop(true)` / snackbar), not as one created onboarding item that lands on a canonical detail surface. Evidence: `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart:296-344`.
- `TD-007` Project-specific tenant-admin route overrides are already supported and loaded after the base tenant-admin route file. Evidence: `bootstrap/app.php:23-30`, `bootstrap/app.php:84-108`.
- `TD-008` Laravel route registration replaces earlier routes when the same method + domain + URI is registered later, so the project route file can truly override the base create routes. Evidence: `vendor/laravel/framework/src/Illuminate/Routing/RouteCollection.php:59-68`.
- `TD-009` Project docs still drift on `ownership_state`: system/domain docs say it is derived and not required in payloads, while the live admin create contract requires it on create. Evidence: `foundation_documentation/modules/system_architecture_principles.md:31`, `foundation_documentation/domain_entities.md:116-119`, `app/Http/Api/v1/Requests/AccountStoreRequest.php:27-34`.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`
  - `foundation_documentation/modules/partner_catalog_and_offer_module.md`
- **Additional governing docs:**
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/todos/completed/TODO-flutter-forms-422-validation-wrapper.md`

## Scope
1. Define the canonical tenant-admin onboarding endpoint: `POST /admin/api/v1/account_onboardings`.
2. Override the project tenant-admin routes so `POST /admin/api/v1/accounts` and `POST /admin/api/v1/account_profiles` are rejected for manual tenant-admin create flows.
3. Encode the current Flutter create-form contract in the onboarding endpoint instead of the broader standalone account/profile contracts:
   - required: `name`, `ownership_state`, `profile_type`
   - optional: `location`, `taxonomy_terms`, `bio`, `content`, `avatar`, `cover`
   - derived on create: `account_profile.display_name = name`
4. Persist `account`, default Admin role template, and `account_profile` inside one backend orchestration boundary so validation/runtime failures do not leave orphan documents.
5. Preserve optional multipart media submission for `avatar` / `cover` in the onboarding endpoint.
6. Preserve structured `422.errors` keys that match Flutter form binding expectations and add deterministic `409` project-policy rejections for the legacy create routes.
7. Migrate Flutter tenant-admin onboarding to one repository/controller call and remove widget/controller orchestration of separate account then profile writes.
8. Remove standalone profile-create UX and route usage from tenant-admin Flutter. An account without a profile becomes an invariant-broken state to be repaired by backend/data remediation, not a normal UI branch.
9. Audit and remediate existing tenant-admin accounts without profiles before rollout, or capture explicit evidence that none exist in the target environment.
10. Update authoritative docs and roadmap entries before code changes, then promote the stable outcome back into module summaries after implementation.

## Out of Scope
- Preserving backward compatibility for manual tenant-admin create routes.
- Keeping standalone `POST /admin/api/v1/accounts` or `POST /admin/api/v1/account_profiles` available for tenant-admin onboarding consumers.
- Adding document or organization fields to the current Flutter onboarding form.
- Generic Flutter inline `422` rendering wrapper implementation.
- Broader tenant-admin route refactors unrelated to onboarding create.
- Membership/claim flows, personal profile auto-creation, or account workspace behavior.
- New scope/subscope additions beyond `tenant_admin`.

## Implementation Tasks
- [x] ✅ Production-Ready Update canonical docs first:
  - add the onboarding-only contract to `foundation_documentation/modules/tenant_admin_module.md`
  - resolve the `ownership_state` create-intent drift in `foundation_documentation/modules/system_architecture_principles.md` and `foundation_documentation/domain_entities.md`
  - record the endpoint and rejection status in `foundation_documentation/system_roadmap.md`
- [x] ✅ Production-Ready Laravel: add project route overrides in `routes/api/project_tenant_admin_api_v1.php` for:
  - `POST /admin/api/v1/accounts` -> reject with deterministic `409`
  - `POST /admin/api/v1/account_profiles` -> reject with deterministic `409`
  - `POST /admin/api/v1/account_onboardings` -> canonical onboarding create
- [x] ✅ Production-Ready Laravel: ensure the overridden/replacement routes carry `auth:sanctum`, `CheckTenantAccess`, and `abilities:account-users:create`.
- [x] ✅ Production-Ready Laravel: add the onboarding request validator, controller action, and orchestration service for `POST /admin/api/v1/account_onboardings`.
- [x] ✅ Production-Ready Laravel: enforce composite failure semantics:
  - no persisted `account` without `account_profile`
  - no persisted `account_profile` without `account`
  - no persisted docs when media validation/storage fails inside the onboarding flow
- [x] ✅ Production-Ready Laravel: return composite success payload with `data.account`, `data.account_profile`, and `data.role`.
- [x] ✅ Production-Ready Laravel: standardize deterministic route-rejection payloads for the legacy create routes, including stable error codes and `meta.use_endpoint`.
- [x] ✅ Production-Ready Laravel: normalize `422` keys to the onboarding form contract (`name`, `ownership_state`, `profile_type`, `location.*`, `taxonomy_terms.*.*`, `bio`, `content`, `avatar`, `cover`) and keep non-field domain errors under `account` / `account_profile`.
- [x] ✅ Production-Ready Laravel: add a repair path for existing accounts missing profiles:
  - preferred: dedicated artisan command or one-off service-backed script
  - minimum: audit command/report plus documented execution result before rollout
- [x] ✅ Production-Ready Flutter: add a unified onboarding repository method that submits one request to `/admin/api/v1/account_onboardings`, still supports multipart when media is selected, and returns one composite onboarding result object.
- [x] ✅ Production-Ready Flutter: replace `createAccountWithProfile()` sequential orchestration with one repository call while keeping controller-owned state and form flow intact.
- [x] ✅ Production-Ready Flutter: keep create progress, success, and failure as one controller-owned state transition with no intermediate "account created" success state.
- [x] ✅ Production-Ready Flutter: update success handling so the user lands on one coherent post-create surface (account detail already hydrated with profile summary) via a route replacement strategy that does not leave the create form under the detail page.
- [x] ✅ Production-Ready Flutter: remove standalone profile-create route usage and CTA from tenant-admin account detail; missing-profile state must render as an invariant-broken state, not a "Criar Perfil" remediation branch.
- [x] ✅ Production-Ready Flutter: preserve structured `422` data instead of flattening it to `toString()` so the wrapper TODO can bind field errors later.
- [x] ✅ Production-Ready Tests: add/extend Laravel feature tests, Flutter repository tests, Flutter controller tests, account create screen tests, and tenant-admin account-detail coverage for the removed profile-create path.
- [x] ✅ Production-Ready Promote the implemented outcome into module docs, roadmap, and submodule summaries before closing this TODO.

## Definition of Done
- [x] ✅ Production-Ready Tenant-admin manual onboarding create performs exactly one backend request from Flutter to `POST /admin/api/v1/account_onboardings`.
- [x] ✅ Production-Ready Backend creates `account` + default Admin role template + `account_profile` as one canonical onboarding flow.
- [x] ✅ Production-Ready `POST /admin/api/v1/accounts` and `POST /admin/api/v1/account_profiles` are rejected for tenant-admin manual create with deterministic `409` payloads.
- [x] ✅ Production-Ready Flutter create UX exposes one composite onboarding result, one submit state, and one success/failure outcome instead of separate account/profile milestones.
- [x] ✅ Production-Ready Flutter no longer exposes standalone profile creation as a valid tenant-admin path.
- [x] ✅ Production-Ready Account-without-profile is treated as invariant breach, not normal remediation UI.
- [x] ✅ Production-Ready Validation or runtime failure never leaves persisted orphan account/profile documents, including media-processing failures.
- [x] ✅ Production-Ready Existing accounts missing profiles have been audited and remediated, or a verified zero-impact result is recorded.
- [x] ✅ Production-Ready Module docs, roadmap, and submodule summaries reflect the onboarding-only contract and rejected legacy create routes.

## Validation Steps
- Laravel:
  - add a feature test for onboarding success (`account`, `role`, `account_profile` all created)
  - add a feature test proving profile validation failure rolls back account creation
  - add a feature test proving duplicate or invalid account creation does not leave a profile behind
  - add a feature test covering multipart upload validation/storage failure and no-partial-persistence behavior
  - add a feature test proving `POST /admin/api/v1/accounts` returns deterministic `409` rejection payload
  - add a feature test proving `POST /admin/api/v1/account_profiles` returns deterministic `409` rejection payload
  - add a real auth-path test (login -> bearer token -> onboarding endpoint) plus route isolation / `CheckTenantAccess` coverage
  - verify route registration with:
    - `docker compose exec -T app php artisan route:list --path=admin/api/v1/account_onboardings --json`
    - `docker compose exec -T app php artisan route:list --path=admin/api/v1/accounts --json`
    - `docker compose exec -T app php artisan route:list --path=admin/api/v1/account_profiles --json`
  - execute the legacy-account audit/repair path and record the result in this TODO or promoted docs
- Flutter:
  - update `test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart`
  - update `test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart`
  - update `test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
  - add/update tenant-admin account detail coverage so missing-profile state no longer offers "Criar Perfil"
  - prove the controller/screen expose one composite success path and never emit an intermediate partial-create success state
  - run `fvm flutter analyze`
- Adherence:
  - `bash ../delphi-ai/tools/verify_context.sh`
  - run the targeted Laravel / Flutter suites under the final execution plan

## Applicable Rules/Workflows (for approval gate)
- `delphi-ai/skills/wf-docker-persona-selection-method/SKILL.md`
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-contract-alignment-always-on/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/wf-laravel-create-api-endpoint-method/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-tenant-access-guardrails-model-decision/SKILL.md`
- `delphi-ai/skills/test-orchestration-suite/SKILL.md`

## Decision Baseline (Frozen)
- `D-01` Project-specific route overrides in `routes/api/project_tenant_admin_api_v1.php` must supersede the base tenant-admin manual create routes. No backward compatibility is required for those legacy create entrypoints.
- `D-02` Canonical tenant-admin manual create route is `POST /admin/api/v1/account_onboardings`.
- `D-03` `POST /admin/api/v1/accounts` and `POST /admin/api/v1/account_profiles` must reject with deterministic `409` project-policy payloads, not redirect and not silently preserve legacy create behavior.
- `D-04` The onboarding contract mirrors the current Flutter create form. On create, `name` seeds both the Account name and the initial Account Profile `display_name`; later divergence belongs to edit flows.
- `D-05` `ownership_state` remains a required admin create intent for onboarding (`tenant_owned|unmanaged` only), even though read models continue exposing derived `ownership_state`. This supersedes the current "not required in payload/response" wording in the project docs.
- `D-06` Onboarding endpoint `422.errors` keys must align to the onboarding form field names: `name`, `ownership_state`, `profile_type`, `location`, `location.lat`, `location.lng`, `taxonomy_terms.*.type`, `taxonomy_terms.*.value`, `bio`, `content`, `avatar`, `cover`. Legacy-route policy rejections use deterministic non-`422` codes/payloads.
- `D-07` Onboarding endpoint accepts optional `avatar` / `cover` multipart inputs. Media validation or storage failure inside the onboarding flow must not leave persisted account/profile docs behind.
- `D-08` Flutter adoption must remain controller/repository-driven: one repository method, one controller submission path, one composite result, and no widget-side direct repository orchestration.
- `D-09` Standalone profile creation is forbidden for tenant-admin. Flutter must remove the standalone profile-create path, and an account without a profile is an invariant-broken state that requires backend/data remediation instead of UI fallback.

## Module Coherence Gate (Mandatory)

| Decision | Module Coherence | Change Intent | Evidence | Notes |
|---|---|---|---|---|
| D-01 | Supersede | Supersede | `bootstrap/app.php:84-108`, `vendor/laravel/framework/src/Illuminate/Routing/RouteCollection.php:59-68` | The project route layer is the canonical place to override tenant-admin create behavior for this project. |
| D-02 | Supersede | Supersede | `foundation_documentation/modules/tenant_admin_module.md:326-368`, `foundation_documentation/modules/tenant_admin_module.md:875-919` | The canonical admin docs currently model onboarding through the standalone resource create routes. This TODO replaces that with a dedicated onboarding route. |
| D-03 | Supersede | Supersede | `routes/api/tenant_api_v1.php:98-112`, `routes/api/tenant_api_v1.php:158-172` | Legacy manual create routes remain technically present in the base route file but are superseded at the project route layer and must reject. |
| D-04 | Aligned | Preserve | `foundation_documentation/modules/system_architecture_principles.md:26-33`, `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart:419-449` | Account and Account Profile remain 1:1; current Flutter create flow already treats `name` as the shared initial label. |
| D-05 | Supersede | Supersede | `foundation_documentation/modules/system_architecture_principles.md:31`, `foundation_documentation/domain_entities.md:116-119`, `app/Http/Api/v1/Requests/AccountStoreRequest.php:27-34` | Project docs currently say `ownership_state` is not required in payloads, while the live admin contract requires it. Implementation must update the docs before coding. |
| D-06 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:77-98`, `foundation_documentation/todos/completed/TODO-flutter-forms-422-validation-wrapper.md:1-61` | Flutter needs structured, stable error envelopes; this TODO tightens the exact field keys for the onboarding form and separates legacy-route policy rejection from validation failure. |
| D-07 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md:878-895`, `foundation_documentation/modules/partner_catalog_and_offer_module.md:30-33` | Tenant-admin onboarding already expects optional media, and the catalog module already treats media as canonical account-profile metadata. |
| D-08 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:59-75`, `foundation_documentation/modules/system_architecture_principles.md:52-53` | Flutter orchestration must remain controller/repository-driven. |
| D-09 | Supersede | Supersede | `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:98-101`, `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:316-322`, `lib/presentation/tenant_admin/account_profiles/screens/tenant_admin_account_profile_create_screen.dart:497` | Current tenant-admin UI still treats standalone profile creation as valid. This TODO explicitly removes that deviation. |

Implementation cannot proceed with unresolved `Conflict`. `Supersede` requires explicit approval and doc promotion before code changes.

## Plan Review Gate (Medium)

### Issue Card I-01
- **Severity:** High
- **Category:** Contract / Route Design
- **Evidence:** `routes/api/tenant_api_v1.php:98-112`, `routes/api/tenant_api_v1.php:158-172`, `bootstrap/app.php:84-108`, `vendor/laravel/framework/src/Illuminate/Routing/RouteCollection.php:59-68`
- **Why now:** The product rule is now explicit: tenant-admin must never create Account or Account Profile separately. The route layer must enforce that rule directly.
- **Options:**
  - **A (Recommended):** Reject both legacy create routes at the project route layer and introduce `POST /admin/api/v1/account_onboardings` as the only tenant-admin manual create path.
    - Effort: Medium
    - Risk: Low
    - Blast radius: High
    - Maintenance burden: Low
  - **B:** Override only `POST /admin/api/v1/accounts` and keep standalone profile create.
    - Effort: Medium
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Overload `POST /admin/api/v1/accounts` with onboarding semantics and leave the URI unchanged.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: High
    - Maintenance burden: Medium

### Issue Card I-02
- **Severity:** High
- **Category:** Documentation / Domain Drift
- **Evidence:** `foundation_documentation/modules/system_architecture_principles.md:31`, `foundation_documentation/domain_entities.md:116-119`, `app/Http/Api/v1/Requests/AccountStoreRequest.php:27-34`
- **Why now:** The current doc set and the live admin create contract disagree about whether `ownership_state` is required on create.
- **Options:**
  - **A (Recommended):** Treat `ownership_state` as a required onboarding create intent and update the canonical docs before implementation.
    - Effort: Low
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Remove `ownership_state` from the onboarding payload and infer it server-side.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Leave the drift unresolved and document nothing.
    - Effort: None
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-03
- **Severity:** High
- **Category:** Atomicity / Media Handling
- **Evidence:** `app/Http/Api/v1/Controllers/AccountProfilesController.php:48-63`, `app/Application/AccountProfiles/AccountProfileManagementService.php:61-85`, `app/Application/AccountProfiles/AccountProfileMediaService.php:14-56`
- **Why now:** The current profile flow applies uploads after profile creation. A naive onboarding implementation can still leave persisted docs behind if media processing fails after the database writes.
- **Options:**
  - **A (Recommended):** Keep media in the onboarding contract and add compensation so any media failure cleans up the newly created account/profile documents before returning an error.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **B:** Exclude media from the onboarding endpoint and require a second profile patch after create.
    - Effort: Low
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Keep media as a separate second step in Flutter while calling the onboarding endpoint only for account/profile docs.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High

### Issue Card I-04
- **Severity:** High
- **Category:** Flutter UX / Invariant Enforcement
- **Evidence:** `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart:419-452`, `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart:491-504`, `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart:296-344`, `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:98-101`, `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:316-322`
- **Why now:** Flutter still models manual tenant-admin create as partial resources and still exposes standalone profile creation from account detail.
- **Options:**
  - **A (Recommended):** Return one composite onboarding result from the repository/controller, preserve structured validation data end-to-end, remove the standalone profile-create path, and land on account detail via route replacement.
    - Effort: Medium
    - Risk: Low
    - Blast radius: High
    - Maintenance burden: Low
  - **B:** Keep the onboarding endpoint but preserve the current standalone profile-create path as a hidden remediation fallback.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High
  - **C:** Keep the current `bool` submit flow and only change text/navigation copy.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High

### Issue Card I-05
- **Severity:** High
- **Category:** Legacy Data Repair
- **Evidence:** `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:316-322`, `app/Application/AccountProfiles/AccountProfileBootstrapService.php:20-50`
- **Why now:** Once standalone profile create is forbidden, any existing account without a profile becomes unrepairable from the normal tenant-admin UI and must be handled as data remediation.
- **Options:**
  - **A (Recommended):** Add an explicit audit/repair path and execute it before rollout, then keep Flutter strict.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Ship the invariant-broken UI and defer data repair.
    - Effort: Low
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Keep standalone profile create available only for repair scenarios.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High

### Failure Modes & Edge Cases
- Legacy clients can still call `POST /admin/api/v1/accounts` or `POST /admin/api/v1/account_profiles`; those calls must fail deterministically with the project-policy rejection payload, not create partial data.
- Duplicate create attempts can race and must not produce an orphan account or a second profile for the same account.
- `profile_type` can become invalid between form load and submit if the registry changes server-side.
- POI-enabled profile types must reject missing `location` with stable keys (`location`, `location.lat`, `location.lng`) instead of generic messages.
- Taxonomy terms can be valid structurally but invalid for the selected `profile_type`.
- Multipart upload validation/storage must fail without leaving account/profile documents behind.
- Existing accounts without profiles must be audited before rollout; Flutter must not silently preserve the current "Criar Perfil" remediation path.
- Cross-tenant route mistakes must not make the onboarding endpoint or the legacy-route overrides reachable without `CheckTenantAccess`.
- Flutter success handling must update local account lists and navigation without reintroducing a second write path or a two-milestone success UX.

### Uncertainty Register
- **Assumptions:** No backward compatibility is required for tenant-admin manual create routes; the current Flutter onboarding screen intentionally models one shared initial name for Account + Account Profile and does not need `document` / `organization_id` in this slice.
- **Unknowns:** How many existing tenant accounts currently lack profiles in the target environments; whether any non-Flutter internal operator tooling currently still calls the legacy create routes.
- **Confidence:** High.

## Implemented Outcome
- Canonical onboarding create is enforced at `POST /admin/api/v1/account_onboardings`; legacy create endpoints now return deterministic `409` with `error_code=tenant_admin_onboarding_required` and `meta.use_endpoint`.
- Flutter tenant-admin create now submits one composite onboarding request and handles success as a single onboarding outcome routed via replacement to account detail.
- Standalone profile-create path is blocked in tenant-admin UX; missing-profile account detail is now rendered as invariant-broken data state.
- Legacy missing-profile audit was executed during rollout with verified zero-impact result (historical evidence):
  - `guarappari`: `total_accounts=44`, `missing_count=0`
  - `boora-alfredo-chaves`: `total_accounts=0`, `missing_count=0`
  - `tmp-nofallback`: `total_accounts=0`, `missing_count=0`
  - `tmp-nofallback-882210`: `total_accounts=0`, `missing_count=0`
- Follow-up hardening removed the temporary repair command/service from runtime code after onboarding moved to strict transactional persistence.

## Decision Adherence Validation (fill before delivery)

| Decision | Status | Evidence | Notes |
|---|---|---|---|
| D-01 | Adherent | `laravel-app/routes/api/project_tenant_admin_api_v1.php:13-18`; `laravel-app/tests/Feature/Accounts/AccountOnboardingsControllerTest.php:173-193` | Project route file supersedes legacy create behavior for this project. |
| D-02 | Adherent | `laravel-app/routes/api/project_tenant_admin_api_v1.php:17-18`; `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart:261-274` | Canonical manual create route is onboarding-only in backend + Flutter repository call. |
| D-03 | Adherent | `laravel-app/app/Http/Api/v1/Controllers/TenantAdminLegacyCreateGuardController.php:12-35`; `laravel-app/tests/Feature/Accounts/AccountOnboardingsControllerTest.php:181-193` | Both legacy manual create routes reject with deterministic `409` + endpoint hint payload. |
| D-04 | Adherent | `laravel-app/app/Application/Accounts/AccountOnboardingService.php:55-59`; `laravel-app/tests/Feature/Accounts/AccountOnboardingsControllerTest.php:84-86` | Onboarding `name` seeds account name and initial profile display name. |
| D-05 | Adherent | `foundation_documentation/modules/system_architecture_principles.md:31`; `foundation_documentation/domain_entities.md:119`; `laravel-app/app/Http/Api/v1/Requests/AccountOnboardingStoreRequest.php:24` | `ownership_state` required for onboarding create intent, while read payloads remain derived. |
| D-06 | Adherent | `laravel-app/app/Http/Api/v1/Requests/AccountOnboardingStoreRequest.php:23-35`; `laravel-app/app/Application/Accounts/AccountOnboardingService.php:109-137`; `flutter-app/lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart:448-452` | Stable field-aligned `422` keys are preserved end-to-end for form binding. |
| D-07 | Adherent | `laravel-app/app/Application/Accounts/AccountOnboardingService.php:66-83`; `laravel-app/tests/Feature/Accounts/AccountOnboardingsControllerTest.php:150-171` | Multipart media is supported and failures trigger rollback of partial creates. |
| D-08 | Adherent | `flutter-app/lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart:434-463`; `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart:225-278` | One controller submission path + one repository onboarding call + one composite result. |
| D-09 | Adherent | `flutter-app/lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_detail_screen.dart:290-313`; `flutter-app/lib/presentation/tenant_admin/account_profiles/routes/tenant_admin_account_profile_create_route.dart:19-39`; `flutter-app/test/presentation/tenant_admin/screens/tenant_admin_account_detail_screen_test.dart:92-94` | Standalone profile create is no longer a valid tenant-admin remediation path. |
