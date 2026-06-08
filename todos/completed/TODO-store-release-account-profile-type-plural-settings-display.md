# TODO (Store Release): Account Profile Type Plural Settings Display

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Production-Ready. Historical archival catch-up on `2026-06-08` confirmed that `origin/main` still carries the plural-label form, persistence, and module-contract coverage already validated by the original Flutter/Laravel/Playwright packet.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to move already promoted TODOs to `completed` after deeper code/main investigation.
- **Approval scope:** documentation-only archival closeout for this bounded plural-label slice after confirming the delivered contract still exists on current `origin/main`.

## Context
Manual QA found that PLURAL settings are not displayed in the Account Profile Type area. Store Release already treats runtime account-profile type metadata as bootstrap-driven and additive: `label` remains the singular compatibility alias while `labels.singular` and `labels.plural` are the canonical display fields for identity and grouped-category surfaces. Tenant-admin must expose and persist the plural field so runtime consumers do not improvise plural labels.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/store-release-profile-social-catalog-gaps.md`
- **Primary story ID:** `ST-03`
- **Why this is the right current slice:** this is one bounded tenant-admin/catalog metadata gap: display, edit, persist, and read back the Account Profile Type plural label.
- **Direct-to-TODO rationale:** not used. The feature brief separates this admin/catalog metadata work from profile and event-share behavior.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Store-Release`, `Cross-Stack`, `Tenant-Admin`, `Account-Profile-Catalog`, `Settings`, `User-Flow-Impact`, `origin-main-reviewed`, `Historical-Archival-Catch-Up`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-store-release-account-profile-type-plural-settings-display.md`.
- **Post-commit/push status:** `completed`

## Contract Boundary
- This TODO owns Account Profile Type plural-label visibility and persistence in tenant-admin create/edit/readback surfaces.
- It preserves the compatibility `label` singular alias while keeping `labels.singular` and `labels.plural` as canonical metadata.
- It does not own public Discovery inclusion rules, profile type capability redesign, Boora icon font work, or event/account profile visual taxonomy behavior.

## References
- `foundation_documentation/artifacts/feature-briefs/store-release-profile-social-catalog-gaps.md`
- `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-agenda-card-polish-and-occurrence-taxonomy-overrides.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision promotion targets:**
  - `tenant_admin_module.md`: Account Profile Type form fields and settings persistence.
  - `account_profile_catalog_module.md`: profile type `labels.singular/plural` display metadata contract if missing or stale.
  - `flutter_client_experience_module.md`: Flutter bootstrap/admin DTO consumption if payload changes.

## Scope
- [x] Display the plural label setting in Account Profile Type create/edit UI.
- [x] Persist plural labels through Flutter DTO/repository payloads and Laravel controller/request handling.
- [x] Read back plural labels in tenant-admin list/edit views and runtime/bootstrap metadata.
- [x] Preserve `label` as a singular compatibility alias.
- [x] Add tests that fail if only singular/legacy label is exposed.

## Out of Scope
- Changing Discovery public/private profile inclusion policy.
- Renaming profile types or changing capabilities such as `is_inviteable`, `is_favoritable`, or `is_publicly_discoverable`.
- Icon font/catalog replacement.
- Event type/static profile type plural behavior unless code reuse proves the same field is already shared and can be covered without widening product scope.
- Production promotion.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto` only if module docs reveal a conflicting label contract.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** one planning checkpoint before `APROVADO`.
- **Why this level:** the UI field is small, but persistence/readback crosses Flutter admin DTO/repository, Laravel validation/storage, bootstrap payloads, and display consumers.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Account Profile Type settings must expose both singular and plural display labels for Store Release.
- [x] `D-02` `labels.plural` is canonical for plural/grouped category display; `label` remains a singular compatibility alias.
- [x] `D-03` Saving an Account Profile Type must preserve plural label values through Laravel storage and Flutter readback.
- [x] `D-04` Missing plural label may use a safe backend/frontend fallback only for display continuity, but the tenant-admin form must still allow setting the explicit plural value.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Laravel already stores or can store `labels.plural` in the profile type registry payload. | Account Profile Catalog module already documents `labels.singular/plural` as canonical. | Need additive schema/storage update inside this TODO. | `High` | Keep as assumption; verify with fail-first tests. |
| `A-02` | The tenant-admin Account Profile Type form is the only release-visible place where plural settings must be edited. | User specifically called out Account Profile Type, not static profile type or event type. | Scope may need explicit user approval before widening. | `Medium` | Keep scope narrow unless code reuse reveals shared form contract. |

## Execution Plan
**Orchestration plan:** `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`

### Touched Surfaces
- Flutter tenant-admin profile type controller/form widgets, DTOs, repository encoders/decoders, tests.
- Laravel Account Profile Type controller/request/service/model tests if backend does not already round-trip `labels.plural`.
- Module docs for tenant-admin/catalog metadata if durable contract wording is stale.

### Ordered Steps
1. Add fail-first Flutter tenant-admin tests proving plural setting is visible on create/edit and sent in payload.
2. Add fail-first repository/DTO/bootstrap-consumer tests for `labels.plural` decode/encode/readback.
3. Add Laravel tests for validation/storage/readback if backend does not already preserve plural labels.
4. Implement UI + DTO/repository/backend fixes.
5. Validate with focused Flutter/Laravel suites, analyzer, required browser/admin mutation evidence, and grouped-category/bootstrap consumer readback proof.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - plural field is absent from Account Profile Type form;
  - save payload drops `labels.plural`;
  - readback/list/edit falls back to singular without preserving explicit plural;
  - bootstrap/runtime consumers still ignore `labels.plural` after admin save;
  - compatibility `label` alias is broken.

## Flow Evidence Planning Matrix
| Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| Tenant-admin Account Profile Type create/edit plural label | Visible admin form and persistence mutation. | Web/admin primary, Flutter shared | Playwright admin mutation is required | yes | yes | Focused Flutter + Laravel tests plus required Playwright admin mutation proof. |
| Runtime/bootstrap plural readback | Public/runtime grouped-category consumers must receive the saved plural label. | shared Flutter | Flutter consumer/repository tests are required; Playwright runtime only if a browser-visible grouped-category route is part of this slice | no | yes | DTO/repository/bootstrap-consumer proof, plus runtime/browser proof if a visible grouped-category route is touched. |

## Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Route / Visible Action | DTO / Repository Path | Planned Render Evidence | Planned Request / Readback Evidence | Waiver |
| --- | --- | --- | --- | --- | --- | --- |
| Account Profile Type `labels.plural` | Flutter tenant-admin form/list/edit | `/admin/profile-types/*` | tenant-admin profile type DTO/repository | form/widget tests + required Playwright admin mutation | Laravel and repository readback tests | none |
| Account Profile Type `labels.plural` | Flutter runtime/bootstrap grouped-category consumers | bootstrap/runtime read models | profile type DTO/registry/readback path | grouped-category/bootstrap consumer tests | Laravel and repository readback tests | none |

## Definition of Done
- [x] Account Profile Type create/edit UI displays plural label settings.
- [x] Plural labels are sent, persisted, decoded, and shown on readback.
- [x] Runtime/bootstrap consumers can read the saved plural label without falling back to singular when explicit plural exists.
- [x] Singular compatibility alias remains intact.
- [x] Focused Flutter/Laravel tests and required runtime evidence are recorded.

## Validation Steps
- [x] Flutter tenant-admin form tests cover plural field visibility and payload.
- [x] Flutter DTO/repository tests cover plural label encode/decode/readback.
- [x] Flutter bootstrap/runtime consumer tests cover plural label readback when explicit plural is present.
- [x] Laravel tests cover validation/storage/readback if backend changes are required.
- [x] `fvm dart analyze --format machine` passes after Flutter changes.
- [x] Laravel safe runner/formatter gates pass for backend changes.
- [x] Playwright/admin mutation evidence is recorded for the Account Profile Type browser-visible create/edit flow.

## Current Execution Evidence (2026-05-02)
- Flutter focused form proof:
  - `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart --plain-name "renders and hydrates plural label field"`
- Laravel profile-type proof:
  - `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural`
- Browser/admin mutation proof:
  - `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line`

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new code was executed for this move; the TODO already contains criterion-specific Flutter/Laravel/Playwright evidence and the archival action only reconciles lane status with current `origin/main`. | `n/a` | `historical archival closeout` | `n/a` | Existing `Current Execution Evidence (2026-05-02)` plus `origin/main` contract review on `2026-06-08`. | Documentation-only move; no fresh CI-equivalent rerun was required. |

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` contract review | `git -C flutter-app grep -n "renders and hydrates plural label field" origin/main -- test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart` | `origin/main` still carries the focused plural-label form regression. |
| `laravel-app` contract review | `git -C laravel-app grep -n "data.labels.plural\\|test_profile_type_show_returns_definition_with_plural_label" origin/main -- tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php` | `origin/main` still carries the plural-label persistence/readback backend contract. |
| `foundation_documentation` doc review | `git -C foundation_documentation grep -n "profile_type_registry.labels.plural" origin/main -- modules/tenant_admin_module.md` | Canonical tenant-admin docs on `origin/main` still expose the plural-label contract. |
| `Archival decision` | Explicit `2026-06-08` user request to move already promoted TODOs to `completed` after code/main investigation. | Documentation-only closeout approved. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new promotion PR package is being opened; confirm this move only reconciles a stale TODO with code and docs already present on `origin/main`. | `n/a` | `git -C flutter-app grep -n "renders and hydrates plural label field" origin/main -- test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`; `git -C laravel-app grep -n "data.labels.plural" origin/main -- tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php` | `none` | No fresh PR/Copilot surface exists for this documentation-only move. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Promotion-lane archive hygiene` | Prevent a code-absorbed, browser-validated tenant-admin slice from lingering in `promotion_lane/` only because the older TODO never received final closeout normalization. | `passed` | `origin/main` contract review; `promotion-lane-code-main-audit-20260608.md` | `no findings` | The closeout keeps the original runtime/browser packet intact and only reconciles stale lane status. |

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The file is being archived after the implementation/promotion wave already finished. | Truthful stage labeling, explicit archival rationale, and stable references to original evidence. | Claiming a new promotion packet exists when only a current `origin/main` review was performed. | Add archival closeout sections without rewriting the original delivery packet. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The task is to distinguish real completion from residual verification debt. | Make any historical packet gap explicit instead of silently burying it. | Treating unrecorded promotion paperwork as if it existed. | Record the archival catch-up basis directly in the TODO. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | The source-of-truth question is whether the delivered slice already crossed the final lane threshold. | Keep closeout tied to current `origin/main` contract review and existing evidence. | Leaving already-main-carried work stranded in `promotion_lane/`. | Move the TODO to `completed` once the final guard set passes. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Display the plural label setting in Account Profile Type create/edit UI. | Flutter + Playwright | `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart --plain-name "renders and hydrates plural label field"`; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line` | local Flutter + browser-facing Guarappari admin runtime | `passed` | The tenant-admin form now visibly renders the plural field and browser mutation proves the visible admin flow. |
| `SCOPE-02` | Scope | Persist plural labels through Flutter DTO/repository payloads and Laravel controller/request handling. | Flutter + Laravel | focused Flutter plural suite; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural` | local Flutter + Laravel container | `passed` | Payload encode/decode and backend persistence round-trip are covered. |
| `SCOPE-03` | Scope | Read back plural labels in tenant-admin list/edit views and runtime/bootstrap metadata. | Flutter + Laravel + Playwright | focused Flutter plural suite; Laravel plural suite; `tenant_admin_profile_type_plural.mutation.spec.js` | local Flutter + Laravel container + browser-facing runtime | `passed` | Readback is covered both in admin edit/read flows and runtime/bootstrap consumers. |
| `SCOPE-04` | Scope | Preserve `label` as a singular compatibility alias. | Flutter + Laravel | focused Flutter plural suite; Laravel plural suite | local Flutter + Laravel container | `passed` | The singular compatibility alias remains intact while explicit plural is preserved. |
| `SCOPE-05` | Scope | Add tests that fail if only singular/legacy label is exposed. | Flutter + Playwright | focused Flutter plural suite; `tenant_admin_profile_type_plural.mutation.spec.js` | local Flutter + browser-facing runtime | `passed` | The regression suite now fails when plural is absent or dropped from mutation/readback. |
| `DOD-01` | Definition of Done | Account Profile Type create/edit UI displays plural label settings. | Flutter + Playwright | focused Flutter plural form test; `tenant_admin_profile_type_plural.mutation.spec.js` | local Flutter + browser-facing runtime | `passed` | Exact DoD covered. |
| `DOD-02` | Definition of Done | Plural labels are sent, persisted, decoded, and shown on readback. | Flutter + Laravel + Playwright | focused Flutter plural suite; Laravel plural suite; Playwright admin mutation | local Flutter + Laravel container + browser-facing runtime | `passed` | Exact DoD covered. |
| `DOD-03` | Definition of Done | Runtime/bootstrap consumers can read the saved plural label without falling back to singular when explicit plural exists. | Flutter tests | focused Flutter plural/bootstrap consumer suite | local Flutter | `passed` | Exact DoD covered. |
| `DOD-04` | Definition of Done | Singular compatibility alias remains intact. | Flutter + Laravel | focused Flutter plural suite; Laravel plural suite | local Flutter + Laravel container | `passed` | Exact DoD covered. |
| `DOD-05` | Definition of Done | Focused Flutter/Laravel tests and required runtime evidence are recorded. | Cross-stack evidence audit | focused Flutter plural suite; Laravel plural suite; Playwright admin mutation; analyzer | local Flutter + Laravel container + browser-facing runtime | `passed` | Exact DoD covered. |
| `VAL-01` | Validation Steps | Flutter tenant-admin form tests cover plural field visibility and payload. | Flutter tests + Playwright admin mutation | `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart --plain-name "renders and hydrates plural label field"`; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line` | local Flutter + browser-facing Guarappari admin runtime | `passed` | The Flutter form contract is covered locally and the visible admin form/navigation path is exercised in browser mutation. |
| `VAL-02` | Validation Steps | Flutter DTO/repository tests cover plural label encode/decode/readback. | Flutter tests + Playwright admin mutation | focused Flutter plural DTO/repository suite; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line` | local Flutter + browser-facing Guarappari admin runtime | `passed` | DTO/repository readback is covered locally and tied back to the visible admin mutation/readback flow in browser. |
| `VAL-03` | Validation Steps | Flutter bootstrap/runtime consumer tests cover plural label readback when explicit plural is present. | Flutter tests | focused Flutter plural/bootstrap consumer suite | local Flutter | `passed` | Exact validation step covered. |
| `VAL-04` | Validation Steps | Laravel tests cover validation/storage/readback if backend changes are required. | Laravel tests + Playwright admin mutation | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural`; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line` | Laravel container + browser-facing Guarappari admin runtime | `passed` | Backend validation/storage/readback is covered by Laravel tests and exercised through the visible admin create/edit browser mutation flow. |
| `VAL-05` | Validation Steps | `fvm dart analyze --format machine` passes after Flutter changes. | Analyzer | `fvm dart analyze --format machine` | local Flutter | `passed` | Exact validation step covered. |
| `VAL-06` | Validation Steps | Laravel safe runner/formatter gates pass for backend changes. | Laravel safe runner | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural` | Laravel container | `passed` | Exact validation step covered. |
| `VAL-07` | Validation Steps | Playwright/admin mutation evidence is recorded for the Account Profile Type browser-visible create/edit flow. | Playwright browser mutation | `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line` | browser-facing Guarappari admin runtime | `passed` | Exact validation step covered. |
| `ARCH-PLURAL-01` | `Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)` | Current `origin/main` still carries the delivered Flutter/Laravel plural-label contract and focused regression coverage. | `origin/main review` | `git -C flutter-app grep -n "renders and hydrates plural label field" origin/main -- test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`; `git -C laravel-app grep -n "data.labels.plural" origin/main -- tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php` | `origin/main source history` | `passed` | The code-proved contract is still present on current `origin/main`, so the TODO no longer belongs in `promotion_lane/`. |
| `ARCH-PLURAL-02` | `Canonical Module Anchors` | Canonical tenant-admin docs on `origin/main` still expose the plural-label contract. | `doc review` | `git -C foundation_documentation grep -n "profile_type_registry.labels.plural" origin/main -- modules/tenant_admin_module.md` | `foundation origin/main docs` | `passed` | The durable admin contract is not stranded only inside the tactical TODO. |
| `ARCH-PLURAL-03` | `Approval` | This archival move is an explicit documentation closeout request, not a fresh implementation or promotion claim. | `approval` | Explicit `2026-06-08` user request plus `promotion-lane-code-main-audit-20260608.md` | `historical archival closeout` | `passed` | The closeout is intentional and traceable. |

## TODO Closeout Disposition
- **Completed path:** `foundation_documentation/todos/completed/TODO-store-release-account-profile-type-plural-settings-display.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming the plural-label contract remains present on current `origin/main`.
- **Historical note:** this TODO already carried the required browser/admin mutation packet; the archival move only reconciles stale lane status.
- **Reopen rule:** any new plural-label regression or contract drift must open a new TODO.
