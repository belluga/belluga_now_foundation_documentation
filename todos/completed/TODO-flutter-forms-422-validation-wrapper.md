# TODO (V1): Flutter Forms 422 Validation Wrapper (Reusable Package + First Adopter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Completed (Production-Ready)  
**Owners:** Flutter Team  
**Created:** 2026-03-07  
**Complexity:** `medium`  
**Checkpoint policy:** one full review checkpoint before approval (Plan Review Gate), then implementation.

---

## Goal
Establish a reusable internal Flutter package for form validation feedback that makes backend `422` handling first-class, keeps validation state controller-owned, and gives forms one coherent rendering pipeline for both local pre-submit validation and backend validation. The first adopter is Tenant Admin Account Create. Broader form replacement is explicitly deferred to the next session.

## Current Evidence
- `TV-001` There is no existing reusable form-validation package under `flutter-app/packages/`, so current forms each improvise their own error-handling strategy.
- `TV-002` Tenant-admin repositories still flatten transport failures into generic exceptions and many controllers/screens reduce them to `toString()`-driven UX. Evidence: `lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart`, `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart`.
- `TV-003` The current first adopter still mixes Flutter `Form`/`validator:` rendering, controller submit state, and snackbar-driven error feedback. Evidence: `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`.
- `TV-004` Canonical tenant-admin docs already require controller-owned validation and inline validation near the relevant controls, especially location. Evidence: `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/screens/modulo_tenant_admin.md`.
- `TV-005` The Flutter client module already freezes controller-owned `StreamValue` state, reusable shared widgets, and standardized API error envelopes with `error.code`, `error.message`, `error.hints[]`, and `metadata.request_id`. Evidence: `foundation_documentation/modules/flutter_client_experience_module.md`.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/screens/modulo_tenant_admin.md`
- **Promotion targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/screens/modulo_tenant_admin.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/todos/completed/TODO-account-profile-transaction-unified-create.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-form-validation-package-hardening-and-publish.md`

## Scope
1. Create an internal local package under `flutter-app/packages/belluga_form_validation/`.
2. Add a package `README.md` that documents:
   - package boundaries,
   - `field | group | global` resolution,
   - ordered binding configuration,
   - controller integration,
   - rendering hierarchy,
   - scroll-anchor usage,
   - clear-on-edit behavior,
   - and reuse steps for future adopters.
3. Establish a reusable validation pipeline that:
   - stays transport-agnostic,
   - preserves backend `422.errors` as structured data through a shared validation failure model,
   - normalizes keys minimally for matching,
   - resolves backend/local validation into UI targets (`field`, `group`, `global`),
   - and exposes one controller-owned validation state stream per form.
4. Provide default package rendering/building primitives for:
   - decorated field errors,
   - inline group errors,
   - inline form-level global validation summary,
   - anchor registration,
   - and scrolling to the first invalid target.
5. Make Tenant Admin Account Create the first adopter:
   - backend `422` validation and local pre-submit validation must feed the same validation state,
   - the screen must stop relying on Flutter `validator:` rendering as the canonical error path,
   - and `422` validation must no longer surface through snackbars.
6. Keep non-validation failures separate from validation rendering.
7. Defer broad replacement of other forms to the next session after the package and first adopter land.

## Out of Scope
- Publishing the package outside this repo in V1.
- Cross-app/external package hardening, semver guarantees, or public API freeze.
- Broad replacement of the remaining Flutter forms in this session.
- Turning the package into a transport/network client abstraction.
- Turning the package into a generic business-rules engine for local validation.
- Backend onboarding/orchestration changes beyond the first adopter preserving structured `422`.
- Broad UI redesign beyond validation rendering/binding.

## Implementation Tasks
- [x] ✅ Production-Ready Update canonical docs before code:
  - add the reusable validation package baseline to `foundation_documentation/modules/flutter_client_experience_module.md`
  - add the Tenant Admin Account Create first-adopter contract to `foundation_documentation/modules/tenant_admin_module.md`
  - add the reusable admin validation rendering baseline to `foundation_documentation/screens/modulo_tenant_admin.md`
- [x] ✅ Production-Ready Create local package `packages/belluga_form_validation/` with:
  - `pubspec.yaml`
  - `lib/`
  - `README.md`
- [x] ✅ Production-Ready Add the shared transport-agnostic validation failure model in the package, carrying:
  - `statusCode`
  - `message`
  - optional `errorCode`
  - optional `hints`
  - optional `requestId`
  - `Map<String, List<String>> fieldErrors`
- [x] ✅ Production-Ready Add reusable target resolution support in the package:
  - plain-string target ids
  - target kinds: `field`, `group`, `global`
  - exact key matching
  - wildcard/glob matching
  - narrow key normalization for matching
  - global fallback for unmapped keys
  - debug diagnostic for unmapped keys
- [x] ✅ Production-Ready Add one immutable validation state model plus a controller-side composition/helper layer that supports:
  - one validation `StreamValue` per form
  - `errorForField(...)`
  - `errorsForGroup(...)`
  - `globalErrors`
  - `hasErrors`
  - `firstInvalidTargetId`
  - state replacement on new validation snapshots
  - selective clear for edited field/group targets
- [x] ✅ Production-Ready Add granular package builders/widgets:
  - field-error binding helper
  - group-error builder/widget
  - global validation summary/banner widget
  - theme-dependent default styling
  - summary + expand/collapse behavior for multi-message group/global errors
- [x] ✅ Production-Ready Add ordered binding/anchor/scroll support:
  - one ordered binding list per feature
  - declaration order defines invalid-target priority
  - anchor widget for target registration
  - package scroll helper for first-invalid-target navigation
  - screen-owned invocation of scroll behavior
- [x] ✅ Production-Ready Update the first adopter infrastructure/repository layer so backend `422` envelopes are parsed into the shared validation failure model instead of flattened strings.
- [x] ✅ Production-Ready Establish a dedicated `TenantAdminAccountCreateController` for the first adopter while narrowing `TenantAdminAccountsController` to list-only ownership so:
  - local pre-submit validation remains feature-owned,
  - local validation writes directly into the shared target-based validation state,
  - backend `422` writes into the same state,
  - non-validation submit failures remain on the existing non-validation error channel,
  - list and create no longer share a controller instance/class.
- [x] ✅ Production-Ready Update `TenantAdminAccountCreateScreen` so:
  - validation surfaces are package-driven,
  - field/group/global targets render through the shared pipeline,
  - inline global validation replaces snackbar usage for `422`,
  - scrolling to the first invalid target is triggered after validation snapshots,
  - current `validator:` rendering is no longer the canonical error presentation path.
- [x] ✅ Production-Ready Document the first-adopter binding in the package README and cross-link this TODO plus `TODO-account-profile-transaction-unified-create.md`.
- [x] ✅ Production-Ready Promote the implemented outcome into module docs and submodule summary before closing this TODO.

## First-Adopter Binding Baseline
Declaration order also defines invalid-target priority for scroll/navigation.

1. `account`, `account_profile` -> global
2. `profile_type` -> field
3. `name` -> field
4. `ownership_state` -> group (`ownership`)
5. `location`, `location.lat`, `location.lng` -> group (`location`)
6. `taxonomy_terms.*.*` -> group (`taxonomies`)
7. `bio` -> field
8. `content` -> field
9. `avatar`, `cover` -> group (`media`)

## Test Tasks
- [x] ✅ Production-Ready Add package-level tests for:
  - exact and wildcard target resolution
  - key normalization
  - state replacement behavior
  - selective clear behavior
  - invalid-target priority resolution
  - unmapped-key fallback to `global` plus debug diagnostic behavior
- [x] ✅ Production-Ready Add controller tests proving:
  - local validation writes into shared validation state
  - backend `422` writes into the same shared validation state
  - non-validation failures remain separate
  - successful submit path remains unaffected when validation passes
- [x] ✅ Production-Ready Add screen/widget tests proving:
  - decorated fields render package-driven errors
  - group targets render inline group widgets
  - global validation renders inline banner/summary, not snackbar
  - multi-message group/global errors use summary + expand/collapse
  - first invalid target scrolls into view
  - editing one field/group clears only its own validation error
  - successful submit flow and post-submit UX still work when no validation errors exist
  - capability-driven sections (`location`, `taxonomies`, `media`, `bio`, `content`) still show/hide correctly after package adoption
  - non-validation operational failures still use the existing non-validation feedback path

## Definition of Done
- [x] ✅ Production-Ready Internal package `belluga_form_validation` exists with README and reusable public surface.
- [x] ✅ Production-Ready The package remains transport-agnostic and does not depend on Dio response types.
- [x] ✅ Production-Ready The first adopter uses one validation presentation pipeline for both local validation and backend `422`.
- [x] ✅ Production-Ready Tenant Admin Account Create no longer relies on snackbar feedback for `422` validation errors.
- [x] ✅ Production-Ready Tenant Admin Account Create no longer relies on Flutter `validator:` rendering as the canonical validation path.
- [x] ✅ Production-Ready Field, group, and global validation rendering all work for the first adopter.
- [x] ✅ Production-Ready Group/global default widgets are theme-dependent and support collapsed summary + inline expansion for multi-message errors.
- [x] ✅ Production-Ready First-invalid-target scroll works for the first adopter.
- [x] ✅ Production-Ready Package-level and first-adopter automated tests cover mapping, replacement, selective clearing, and rendering behavior.
- [x] ✅ Production-Ready Regression coverage proves the first adopter still preserves existing success-path, non-validation failure, and capability-driven section behavior after package adoption.
- [x] ✅ Production-Ready Broad replacement of the remaining forms remains explicitly deferred to the next session.

## Implemented Outcome
- Reusable package delivered under `packages/belluga_form_validation/` with README, transport-agnostic failure/state models, target resolution, default widgets, and anchor/scroll helpers.
- Tenant Admin Account Create adopted the package end-to-end and now renders local + backend validation through one controller-owned pipeline.
- Backend `422` handling is preserved structurally in tenant-admin repositories instead of being flattened into generic strings.
- Presentation ownership was tightened during delivery: `TenantAdminAccountCreateController` now owns create-only state, while `TenantAdminAccountsController` is list-only and both are route-local factories.
- Module docs and the Flutter submodule summary now describe the package baseline and the list/create controller split.

## Validation Steps
- Manual:
  - trigger local pre-submit validation failures in Account Create and verify field/group/global rendering uses the package surfaces
  - trigger backend `422` validation failures in Account Create and verify the same surfaces are used
  - verify `422` validation does not show snackbar feedback
  - verify first invalid target scrolls into view
  - verify editing one invalid field/group clears only its own error
  - verify multi-message group/global summaries expand inline
- Automated:
  - package tests
  - `test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart`
  - `test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart`
  - `test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart`
  - `test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
  - `test/presentation/tenant_admin/accounts/tenant_admin_accounts_list_screen_test.dart`
  - targeted regression assertions for success path, non-validation failures, and capability-driven sections in the first adopter suite
  - `fvm flutter analyze`
  - `fvm dart run custom_lint`
- Adherence:
  - `bash ../delphi-ai/tools/verify_context.sh`

## Decision Adherence Validation

| Decision | Status | Evidence |
|---|---|---|
| D-01 | Adherent | `packages/belluga_form_validation/README.md`; `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-form-validation-package-hardening-and-publish.md` |
| D-02 | Adherent | `packages/belluga_form_validation/README.md`; `lib/presentation/tenant_admin/accounts/models/tenant_admin_account_create_validation_config.dart` |
| D-03 | Adherent | `lib/infrastructure/repositories/tenant_admin/support/tenant_admin_validation_failure_resolver.dart`; `lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart`; `lib/infrastructure/repositories/tenant_admin/tenant_admin_account_profiles_repository.dart` |
| D-04 | Adherent | `packages/belluga_form_validation/README.md`; package tests (`fvm flutter test` in `packages/belluga_form_validation`) |
| D-05 | Adherent | `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart`; `packages/belluga_form_validation/README.md` |
| D-06 | Adherent | `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`; `test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart` |
| D-07 | Adherent | `packages/belluga_form_validation/README.md`; `test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart` |
| D-08 | Adherent | `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`; package tests (`fvm flutter test` in `packages/belluga_form_validation`) |
| D-09 | Adherent | `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart`; `test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart` |
| D-10 | Adherent | `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart`; `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`; `test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart` |
| D-11 | Adherent | `foundation_documentation/submodule_flutter-app_summary.md`; broad rollout remains deferred in `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-form-validation-package-hardening-and-publish.md` |

## Execution Evidence
- `fvm flutter test` in `packages/belluga_form_validation`
- `fvm flutter test --no-pub --no-track-widget-creation --no-test-assets --reporter failures-only test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart`
- `fvm flutter test --no-pub --no-track-widget-creation --no-test-assets --reporter failures-only test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart`
- `fvm flutter test --no-pub --no-track-widget-creation --no-test-assets --reporter failures-only test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart`
- `fvm flutter test --no-pub --no-track-widget-creation --no-test-assets --reporter failures-only test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
- `fvm flutter test --no-pub --no-track-widget-creation --no-test-assets --reporter failures-only test/presentation/tenant_admin/accounts/tenant_admin_accounts_list_screen_test.dart`
- `fvm flutter analyze --no-pub`
- `fvm dart run custom_lint`
- `bash ../delphi-ai/tools/verify_context.sh`

## Applicable Rules/Workflows (for approval gate)
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-contract-alignment-always-on/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/test-orchestration-suite/SKILL.md`

## Decision Baseline (Frozen)
- `D-01` The reusable mechanism ships now as an internal local package under `flutter-app/packages/belluga_form_validation/`; external hardening/publication is deferred to VNext.
- `D-02` The package resolves validation targets as `field | group | global`, not field-only, and target ids remain plain strings in V1.
- `D-03` The package is transport-agnostic. Repositories parse backend `422` envelopes into a shared validation failure model and keep optional typed envelope context (`errorCode`, `hints`, `requestId`) without exposing an unbounded metadata bag.
- `D-04` V1 matching supports exact and wildcard/glob rules plus narrow key normalization for matching; unmapped keys fall back to `global` and emit debug diagnostics in non-production modes.
- `D-05` Each form owns one controller-held validation state stream. The package provides composition/helper APIs and granular builders/widgets; it does not require one stream per field/group.
- `D-06` Validation rendering hierarchy is fixed: field -> `InputDecoration.errorText`; group -> inline group widget; global -> inline form-level validation summary/banner. `422` validation errors do not use snackbars.
- `D-07` Group/global default widgets are theme-dependent and support collapsed summary plus inline expand/collapse for multi-message errors.
- `D-08` Binding declaration order defines invalid-target priority. The package provides anchor + scroll helpers; screens trigger scrolling to the first invalid target. Focus remains feature-owned and optional.
- `D-09` New validation snapshots replace previous validation state; selective clearing happens only on semantically meaningful value change for the edited target.
- `D-10` Local validation remains feature-owned, but local validation and backend `422` must feed the same shared validation state and render through the same package surfaces. The first adopter stops using Flutter `validator:` as the canonical error-rendering path.
- `D-11` This session delivers the package plus Tenant Admin Account Create as first adopter only. Broad replacement of other forms happens in the next session.

## Module Coherence Gate

| Decision | Module Coherence | Change Intent | Evidence | Notes |
|---|---|---|---|---|
| D-01 | Supersede | Supersede | `foundation_documentation/modules/flutter_client_experience_module.md:234-240` | Shared libraries are already part of the Flutter architecture baseline, but introducing `packages/belluga_form_validation` extends the current documented code-structure model and must be promoted explicitly into the module docs before implementation. |
| D-02 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md:131`, `foundation_documentation/screens/modulo_tenant_admin.md:52` | Controller-owned validation already exists; the package standardizes UI targets without changing ownership. |
| D-03 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:97` | The package keeps the documented error envelope context while staying transport-agnostic. |
| D-04 | Aligned | Preserve | `foundation_documentation/todos/completed/TODO-account-profile-transaction-unified-create.md` | Exact onboarding field keys and stable `422.errors` mapping remain consistent with the onboarding TODO. |
| D-05 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:71-73`, `foundation_documentation/modules/tenant_admin_module.md:131` | One controller-owned validation stream with granular consumers matches the StreamValue/controller ownership baseline. |
| D-06 | Aligned | Preserve | `foundation_documentation/screens/modulo_tenant_admin.md:116`, `foundation_documentation/screens/modulo_tenant_admin.md:216-222` | Inline validation near relevant controls and controller-owned state are already required by the tenant-admin screen contract. |
| D-07 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:234` | Shared widgets are already part of the module baseline; theme dependence avoids introducing a conflicting visual system. |
| D-08 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:71-73` | Screen-side UI actions with controller-owned state match the DI/state ownership rules. |
| D-09 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md:131`, `foundation_documentation/screens/modulo_tenant_admin.md:222-241` | Controller-owned validation snapshots and target-specific busy/error handling remain consistent with the admin interaction baseline. |
| D-10 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md:131`, `foundation_documentation/screens/modulo_tenant_admin.md:116` | One presentation path for validation strengthens the current controller-owned validation contract instead of superseding it. |
| D-11 | Aligned | Preserve | `foundation_documentation/todos/completed/TODO-account-profile-transaction-unified-create.md`, `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-form-validation-package-hardening-and-publish.md` | The staged rollout is already reflected by the onboarding cross-link and VNext package hardening TODO. |

## Plan Review Gate (Medium)

### Issue Card I-01
- **Severity:** High
- **Category:** Package Boundary
- **Evidence:** `foundation_documentation/modules/flutter_client_experience_module.md:71-73`, `foundation_documentation/modules/flutter_client_experience_module.md:97`
- **Why now:** If transport/Dio concerns leak into the package, the package becomes harder to reuse and violates the existing infrastructure vs presentation boundary.
- **Options:**
  - **A (Recommended):** Keep the package transport-agnostic and make repositories translate backend envelopes into the shared validation failure model.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Let the package parse Dio/HTTP response objects directly.
    - Effort: Low
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High
  - **C:** Do nothing and continue feature-by-feature custom parsing.
    - Effort: None
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-02
- **Severity:** High
- **Category:** Validation UX Consistency
- **Evidence:** `foundation_documentation/modules/tenant_admin_module.md:131`, `foundation_documentation/screens/modulo_tenant_admin.md:116`, `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`
- **Why now:** Keeping Flutter `validator:` rendering alongside a new backend `422` wrapper would preserve two validation UX paths and weaken the package’s value.
- **Options:**
  - **A (Recommended):** Keep local validation feature-owned, but write it into the same package validation state used by backend `422`.
    - Effort: Medium
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Keep Flutter `validator:` rendering for local validation and use the package only for backend `422`.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High
  - **C:** Move full local validation rule authoring into the package.
    - Effort: High
    - Risk: Medium
    - Blast radius: High
    - Maintenance burden: Medium

### Issue Card I-03
- **Severity:** Medium
- **Category:** Rollout Control
- **Evidence:** `foundation_documentation/todos/completed/TODO-account-profile-transaction-unified-create.md`, `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-form-validation-package-hardening-and-publish.md`
- **Why now:** Trying to replace every form in the same slice would turn the package-establishment work into a broad refactor and delay onboarding implementation.
- **Options:**
  - **A (Recommended):** Deliver package + first adopter now; replace the remaining forms in the next session.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Roll the package out to all forms in the current slice.
    - Effort: High
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High
  - **C:** Build the package now but do not land any first adopter yet.
    - Effort: Low
    - Risk: Medium
    - Blast radius: Low
    - Maintenance burden: Medium

### Issue Card I-04
- **Severity:** High
- **Category:** Regression Protection
- **Evidence:** `lib/presentation/tenant_admin/accounts/screens/tenant_admin_account_create_screen.dart`, `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_accounts_controller.dart`
- **Why now:** This slice changes the validation pipeline of an existing complex form. Without explicit regression coverage, it is too easy to preserve validation behavior while silently breaking successful submit flow, non-validation failures, or capability-driven section behavior.
- **Options:**
  - **A (Recommended):** Require targeted regression tests for success path, non-validation error path, and capability-driven sections in addition to the new validation tests.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Rely only on the new package/validation-specific tests.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Depend on manual QA for regression detection.
    - Effort: Low
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High

## Failure Modes & Edge Cases
- Backend returns unmapped nested keys; the package must fall back to `global` and expose debug diagnostics.
- Backend returns multiple messages for one group/global target; the default widget must summarize without losing access to the full list.
- Local validation fails before request submission; it must still use the same field/group/global surfaces as backend validation.
- Only global errors exist; the first invalid target must still scroll to the form-level banner.
- One field/group is edited after submit; only that target’s validation error must clear.
- A later backend `422` returns a different invalid set; old errors must not linger.
- Successful submit, capability-driven section toggling, and non-validation failures must remain behaviorally intact after package adoption.

## Uncertainty Register
- **Assumptions**
  - Tenant Admin Account Create remains the correct first adopter for package establishment.
  - The next session will handle broader form rollout, so this TODO can stay package + first adopter focused.
- **Unknowns**
  - Whether any additional non-tenant-admin form will immediately require custom rendering beyond the package defaults.
  - Whether later adopters will require index-aware row targeting beyond the current exact+glob V1 matcher.
- **Confidence:** High for the package/first-adopter baseline, medium for later rollout ergonomics.

## Notes
- This TODO delivers the reusable package plus Tenant Admin Account Create as the first adopter only.
- Broader form replacement belongs to the next session.
- VNext hardening/publication work remains tracked separately in `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-form-validation-package-hardening-and-publish.md`.
- Backend onboarding/transaction scope remains tracked in `foundation_documentation/todos/completed/TODO-account-profile-transaction-unified-create.md`.
- Under the current broad triage, no further material pending decisions remain for this TODO; the remaining cuts are implementation-local.
