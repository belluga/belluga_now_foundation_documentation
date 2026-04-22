# TODO (Store Release): Account Profile Rich-Text Fidelity

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
**Status:** Active
**Owners:** Laravel Team, Flutter Team
**Objective:** Make Account Profile `bio` and `content` long-form capability-backed rich-text fields whose admin editing, backend validation, sanitized persistence, preview, and public rendering are faithful to each other.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** `foundation_documentation/artifacts/tmp/improvement-intake-session-2026-04-20.md` (`C-03`)

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `C-03`
- **Why this is the right current slice:** The current bug is bounded: Account Profile rich-text editing does not render faithfully, line breaks collapse, `content` is not surfaced consistently, and backend validation uses a short-description cap that conflicts with long-form profile content.
- **Direct-to-TODO rationale:** The user-facing bug, field scope, long-form limit, and renderer parity requirement are already decided. Remaining implementation choices can follow existing Event rich-text and repository/controller patterns.

## Delivery Status Canon

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** Keep ready for dev promotion; rerun `todo_completion_guard.py --require-delivery` before any delivery-stage or promotion claim changes.

## Package-First Assessment

- **Status:** completed before local closure.
- **Queries run:** `rich text`, `html`, and `editor` from the ecosystem root.
- **Relevant packages found:** none.
- **Decision:** local implementation inside existing Laravel Account Profile services/requests and existing Flutter Account Profile/admin presentation modules.
- **Rationale:** the slice is a field-specific fidelity/validation/rendering fix, not a reusable package boundary. No local/ecosystem proprietary rich-text package exists to extend.

## Scope

- [ ] Treat Account Profile `bio` and `content` as independent capability-backed rich-text fields.
- [ ] Align editor toolbar, sanitizer, persistence, admin preview/readback, public rendering, and tests to one safe subset.
- [ ] Preserve paragraph breaks, explicit line breaks, headings, unordered/ordered lists, blockquotes, bold, italic, strike, and emoji text.
- [ ] Canonicalize legacy/plain-text newline content before HTML rendering so old text does not collapse.
- [ ] Render both `bio` and `content` in the public Account Profile detail when the relevant capability and content are present.
- [ ] Replace the current short-description backend cap for these fields with a dedicated long-form Account Profile rich-text constraint.
- [ ] Expose visible edit guidance/counter aligned with the backend long-form cap and preserve structured `422` handling as the authoritative fallback.
- [ ] Add high-fidelity tests for both fields and every supported rich-text element.

## Out of Scope

- [ ] Changing global `InputConstraints::DESCRIPTION_MAX` for unrelated short-description fields.
- [ ] Static Asset rich-text parity. It can reuse the eventual renderer later but is not part of this Store Release slice.
- [ ] Adding links, underline, inline code, colors, arbitrary HTML, embedded media, or page-builder semantics.
- [ ] Redesigning Account Profile capability storage beyond respecting `hasBio` and `hasContent` independently.
- [ ] Full CMS/page layout authoring.

## Blocker Notes

- **Blocker:** `n/a`
- **Why blocked now:** `n/a`
- **What unblocks it:** `n/a`
- **Owner / source:** `n/a`
- **Last confirmed truth:** Tenant-admin currently uses a rich-text editor for both `bio` and `content`, but public detail builds `Sobre` from `bio` only and rendering lacks the Event-style plain-text/newline canonicalization.

## Execution Lane Tracking

- **Local implementation branches:** `orchestrator/store-release-usability-wave` in `belluga_now_docker`, `laravel-app`, and `flutter-app`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Account Profile rich-text fidelity | `orchestrator/store-release-usability-wave` | `n/a - not promoted yet` | `n/a - not promoted yet` | `n/a - not promoted yet` | `Local-Implemented; final runtime acceptance passed` |

## Local Implementation Evidence

- Laravel Account Profile/onboarding create/update paths use a dedicated rich-text sanitizer for `bio` and `content`.
- The hard cap is `100KB` per field after sanitization; unrelated short-description constraints were not raised.
- Flutter uses a shared safe rich-text canonicalizer/renderer for Account Profile public detail and tenant-admin readback/editor guidance.
- Tenant-admin rich-text editor surfaces field limit guidance and soft warning around `90%`.
- Public Account Profile detail renders `bio` and `content` independently inside the single `Sobre` shell, preserving legacy plain-text line breaks.
- Flutter domain builder architecture was corrected to avoid primitive helper parameters while preserving capability-backed tab composition.

## Local Validation Evidence

- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/Accounts/AccountOnboardingsControllerTest.php` passed: `54 tests, 253 assertions`.
- `fvm flutter test test/application/rich_text test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` passed: `48 tests`.
- `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/schedule/feature_venue_profile_widgets_test.dart` passed: `38 tests`.
- `fvm flutter test test/application/rich_text/safe_rich_html_test.dart test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart` passed in reconciliation: `43 tests`.
- `fvm dart analyze --format machine` passed after the domain helper correction in `partner_profile_config_builder.dart`.
- 2026-04-22 SR-C rerun: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-account-profile-rich-text-fidelity.md` returned `Overall outcome: no-go` only because `Completion Evidence Matrix` rows were missing.
- 2026-04-22 SR-C rerun: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` passed: `4 tests, 39 assertions`.
- 2026-04-22 SR-C rerun: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Accounts/AccountOnboardingsControllerTest.php` passed: `8 tests, 43 assertions`.
- 2026-04-22 SR-C rerun: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` was blocked outside SR-C after `13 passed, 31 pending`: `public account profile near returns distance sorted favoritable profiles only` failed with `$geoNear requires a 2d or 2dsphere index`; this is a Map/near-index harness gap, not Account Profile rich-text sanitizer/persistence/readback behavior.
- 2026-04-22 SR-C rerun: `fvm flutter test test/application/rich_text test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` passed: `48 tests`.
- 2026-04-22 SR-C rerun: `fvm flutter test test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart` passed: `33 tests`.
- 2026-04-22 SR-C rerun: `fvm dart analyze --format machine` passed with exit code `0` and no diagnostics.
- 2026-04-22 SR-C source-owned runtime spec added: `flutter-app/integration_test/feature_account_profile_rich_text_fidelity_test.dart` covers tenant-admin edit mutation for `bio` and `content`, persisted payload assertions, tenant-admin detail readback, and tenant-public Account Profile rendering for headings, explicit line breaks, paragraphs, blockquote, unordered/ordered lists, bold, italic, strike, and emoji.
- 2026-04-22 SR-C local ADB/device attempt: `fvm flutter test integration_test/feature_account_profile_rich_text_fidelity_test.dart` reached Android `assembleDebug` after compile-signature fixes, then was locally terminated after `493.6s` of no progress; final result `Failed to load ... Gradle task assembleDebug failed with exit code 143`. This is not completion evidence; orchestrator must run the spec on the final ADB/device lane.
- 2026-04-22 SR-C browser attempt: `fvm flutter test -d chrome integration_test/feature_account_profile_rich_text_fidelity_test.dart` failed immediately with `Web devices are not supported for integration tests yet`; Flutter integration spec is ADB/device-only locally.
- 2026-04-22 SR-C focused Flutter rerun after adding the integration spec: `fvm flutter test test/application/rich_text/safe_rich_html_test.dart test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart` passed: `81 tests`.
- 2026-04-22 SR-C analyzer rerun after adding the integration spec: `fvm dart format integration_test/feature_account_profile_rich_text_fidelity_test.dart && fvm dart analyze --format machine` passed with exit code `0` and no diagnostics.
- 2026-04-22 SR-C Laravel rerun after adding the integration spec: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` passed: `4 tests, 39 assertions`.
- 2026-04-22 SR-C source-owned Playwright mutation spec added: `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js` covers real tenant-admin auth, real Account Profile onboarding/profile-type setup, real backend `PATCH /admin/api/v1/account_profiles/{id}` mutation for `bio` and `content`, admin API readback, public API readback, tenant-admin `/admin/accounts/{accountSlug}` browser readback, and tenant-public `/parceiro/{profileSlug}` browser rendering with no raw HTML tags.
- 2026-04-22 SR-C Playwright source checks: `node --check tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js` passed.
- 2026-04-22 SR-C Playwright policy check: `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=local node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` passed: `Web navigation policy check passed (lane=local, suite=mutation).`
- 2026-04-22 SR-C Playwright registration check: from `tools/flutter/web_app_smoke_runner`, `NODE_PATH=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/tools/flutter/web_app_smoke_runner/node_modules npx playwright test --config ./playwright.config.js --grep "@mutation.*rich text" --list` listed `account_profile_rich_text.mutation.spec.js:439:1 › @mutation tenant-admin account-profile rich text persists and renders on admin and public surfaces`.
- 2026-04-22 SR-C final-domain mutation reconciliation: canonical command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` failed before the fix because backend persisted `bio` as `<h2>Bio Heading 🎉<p>...` instead of preserving `</h2>` before following blocks. Classification: backend sanitizer/storage regression, not Playwright assertion defect.
- 2026-04-22 SR-C backend reproduction before fix: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=AccountProfileRichTextFidelityTest::test_update_preserves_heading_boundaries_across_adjacent_rich_text_blocks tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` failed with the same malformed persisted HTML: `<h2>Bio Heading 🎉<p><strong>Bold bio</strong><br>Second bio line</p><blockquote>Bio quote</blockquote><ul><li>Bio bullet</li></ul></h2>`.
- 2026-04-22 SR-C sanitizer fix evidence: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=AccountProfileRichTextFidelityTest::test_update_preserves_heading_boundaries_across_adjacent_rich_text_blocks tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` passed: `1 test, 7 assertions`.
- 2026-04-22 SR-C sanitizer regression suite: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` passed: `5 tests, 46 assertions`.
- 2026-04-22 SR-C post-fix Playwright source checks: `node --check tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js` passed; `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=local node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` passed; from `tools/flutter/web_app_smoke_runner`, `NODE_PATH=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/tools/flutter/web_app_smoke_runner/node_modules npx playwright test --config ./playwright.config.js --grep "@mutation.*rich text" --list` listed the SR-C mutation spec.
- 2026-04-22 SR-C final Web freshness: `bash scripts/build_web.sh ../web-app dev` produced the served bundle; `sha256sum ../web-app/main.dart.js` and `curl -k -L 'https://guarappari.belluga.space/main.dart.js?cachebust=runtime-validation-20260422' | sha256sum` both returned `2a022493dff34f9c906c1352b769cd55237f39805c22f5e48dc3c24890060f9b`.
- 2026-04-22 SR-C final Playwright mutation: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` passed `12 passed (7.4m)`, including `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js`.
- 2026-04-22 SR-C final completion guard: `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/store_release_android/TODO-store-release-account-profile-rich-text-fidelity.md --require-delivery` returned `Overall outcome: go`.

## Final Runtime Acceptance Reconciliation

- 2026-04-22 PACED correction: Laravel sanitizer/validation tests, Flutter rich-text/unit/widget tests, repository tests, and analyzer remain valid implementation/supporting evidence. They do not replace final visible acceptance for editing and rendered readback.
- 2026-04-22 final runtime acceptance passed on the final-domain Playwright mutation lane after the backend sanitizer/storage fix was deployed to the local runtime.
- Platform parity classification: Account Profile rich-text edit/readback/rendering uses the same backend sanitizer and Flutter safe renderer across Android/Web. The final Playwright mutation lane is sufficient for visible acceptance; ADB integration remains supporting/alternate evidence rather than required duplicate evidence for this shared behavior.

| Criterion ID | Current supporting evidence | Final acceptance gap | Required next evidence |
| --- | --- | --- | --- |
| DOD-01 | Laravel sanitizer/validation tests, Flutter widget/editor/detail tests, source-owned ADB integration spec, and source-owned Playwright mutation spec exist. | Closed. | Final mutation Playwright suite passed `12 passed (7.4m)` against `https://guarappari.belluga.space`, including real admin mutation/readback and public rendering assertions for `bio` and `content`. |
| VAL-05 | Flutter admin widget/readback evidence exists, source-owned ADB integration spec exists, and source-owned Playwright mutation spec exists at `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js`. | Closed. | Final mutation Playwright suite passed and proves admin/public readback preserves whitespace and supported formatting after real backend mutation with the sanitizer fix deployed. |

## Definition of Done

- [x] Editing `bio` and `content` in tenant-admin produces public/admin rendering that preserves the approved structure and formatting.
- [x] `bio` and `content` are rendered independently according to capabilities and content presence.
- [x] Legacy plain text with newline breaks renders with faithful line/paragraph structure.
- [x] Backend accepts up to the approved Account Profile long-form limit per field and rejects over-limit values with structured validation.
- [x] Flutter exposes aligned limit guidance before submit and still handles backend `422` errors.
- [x] Tests cover both fields, line breaks, every supported formatting element, long content, over-limit validation, and unsupported markup stripping/rejection.

## Validation Steps

- [x] Laravel request/validation tests for dedicated Account Profile long-form rich-text constraints.
- [x] Laravel sanitizer/persistence tests for supported and unsupported markup.
- [x] Flutter rich-text editor/model tests for limit guidance and serialized payload behavior.
- [x] Flutter widget tests for public Account Profile detail rendering of `bio` and `content`.
- [x] Flutter admin preview/readback tests proving whitespace and supported formatting are not collapsed.
- [x] Focused analyzer/test gates for touched Flutter surfaces.

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOD-01 | Definition of Done | Editing `bio` and `content` in tenant-admin produces public/admin rendering that preserves the approved structure and formatting. | Playwright mutation + source-owned ADB integration spec + widget tests | Final command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` passed `12 passed (7.4m)`, including `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js`; supporting `flutter-app/integration_test/feature_account_profile_rich_text_fidelity_test.dart`; supporting Flutter focused tests listed below. | Final Web runtime `https://guarappari.belluga.space`; Flutter tenant-admin + tenant-public Account Profile detail local widget support | passed | Playwright logs tenant-admin in, creates real runtime data, performs a real backend rich-text mutation, verifies admin/public API readback, and verifies visible admin/public browser rendering with no raw HTML tags. |
| DOD-02 | Definition of Done | `bio` and `content` are rendered independently according to capabilities and content presence. | widget tests + module contract | same Flutter focused command | Flutter tenant-public Account Profile detail | passed | `account_profile_detail_screen_test.dart` covers independent `Sobre` blocks and content-only rendering; module docs freeze independent capability-backed fields. |
| DOD-03 | Definition of Done | Legacy plain text with newline breaks renders with faithful line/paragraph structure. | unit + widget tests | same Flutter focused command | Flutter shared rich-text canonicalizer + tenant-public detail | passed | `safe_rich_html_test.dart` asserts `<br />` and paragraph wrapping; public detail test asserts legacy plain text lines render separately. |
| DOD-04 | Definition of Done | Backend accepts up to the approved Account Profile long-form limit per field and rejects over-limit values with structured validation. | Laravel feature test | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` | Laravel tenant admin Account Profile create/update | passed | `5 tests, 46 assertions passed`; exact `102400` bytes accepted and over-limit `bio`/`content` rejected independently with field-keyed `422`; heading block-boundary regression now covered. |
| DOD-05 | Definition of Done | Flutter exposes aligned limit guidance before submit and still handles backend `422` errors. | widget + repository tests | Flutter focused command; `fvm flutter test test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart` | Flutter tenant-admin editor + repositories | passed | Editor tests cover `100 KB` guidance/counter/90% warning; repository suite passed `33 tests` and preserves structured `422` validation failures. |
| DOD-06 | Definition of Done | Tests cover both fields, line breaks, every supported formatting element, long content, over-limit validation, and unsupported markup stripping/rejection. | Laravel + Flutter focused tests + source-owned integration specs | Laravel rich-text focused command; Flutter focused command; `flutter-app/integration_test/feature_account_profile_rich_text_fidelity_test.dart`; `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js` | Laravel sanitizer/validation + Flutter renderer/editor + Playwright final-domain lane pending | passed | Laravel covers supported subset, unsupported stripping, exact/over limit, both fields, and raw-over-limit sanitized-under-limit; Flutter covers subset canonicalization, independent blocks, newline rendering, admin readback, guidance, and source-owned ADB/Playwright specs cover mutation/readback across admin/public once run on final runtime. |
| VAL-01 | Validation Steps | Laravel request/validation tests for dedicated Account Profile long-form rich-text constraints. | Laravel feature test | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` | Laravel request/service validation | passed | `5 tests, 46 assertions passed`; validates dedicated `102400` byte cap, field-keyed `422`, and block-boundary preservation. |
| VAL-02 | Validation Steps | Laravel sanitizer/persistence tests for supported and unsupported markup. | Laravel feature test | same Laravel rich-text focused command | Laravel sanitizer + persistence/readback | passed | Assertions compare response and stored `AccountProfile` values; preserve subset/emoji, heading closures across adjacent blocks, and strip unsupported tags/attributes/media/scripts/styles. |
| VAL-03 | Validation Steps | Flutter rich-text editor/model tests for limit guidance and serialized payload behavior. | Flutter widget + repository tests | Flutter focused command; Flutter repository command | Flutter editor + tenant-admin repositories | passed | `48 tests passed` for editor/rendering; `33 tests passed` for repository payload/structured `422` handling, including empty/null `bio`. |
| VAL-04 | Validation Steps | Flutter widget tests for public Account Profile detail rendering of `bio` and `content`. | Flutter widget tests | Flutter focused command | Flutter tenant-public Account Profile detail | passed | Public detail tests cover raw-tag stripping, independent `bio`/`content`, content-only profile, and legacy newlines. |
| VAL-05 | Validation Steps | Flutter admin preview/readback tests proving whitespace and supported formatting are not collapsed. | Playwright mutation + source-owned ADB integration spec + Flutter widget tests | Final mutation command `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` passed `12 passed (7.4m)`, including `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js`; supporting `flutter-app/integration_test/feature_account_profile_rich_text_fidelity_test.dart`; Flutter focused command | Final Web runtime `https://guarappari.belluga.space`; Flutter tenant-admin account detail local widget support | passed | `tenant_admin_account_detail_screen_test.dart` includes faithful rich-text readback; Playwright performs real backend mutation and admin/public browser readback after web publish and sanitizer deployment. |
| VAL-06 | Validation Steps | Focused analyzer/test gates for touched Flutter surfaces. | analyzer + Flutter tests | `fvm flutter test test/application/rich_text/safe_rich_html_test.dart test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart`; `fvm dart format integration_test/feature_account_profile_rich_text_fidelity_test.dart && fvm dart analyze --format machine` | Flutter app | passed | `81 tests passed`; analyzer exit code `0` with no diagnostics after adding the source-owned integration spec. |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Rich-text fidelity requires strong display tests and sanitizer/validation coverage. | Laravel validation/sanitizer tests, Flutter renderer/widget tests | `planned` |

## Complexity

- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** Cross-stack but bounded to Account Profile rich-text fields, validation, and renderer parity.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets (module sections):**
  - Account Profile public detail `bio`/`content` rendering contract.
  - Flutter rich-text safe subset/rendering contract.
  - Tenant-admin Account Profile editor/preview fidelity notes.
- **Module decision consolidation targets:**
  - Account Profile field capability and long-form content contract.
  - Flutter shared safe rich-text renderer/canonicalizer contract.

## Decisions

- [x] `D-C-01` Account Profile `bio` and `content` are independent capability-backed rich-text fields. Neither is a fallback, teaser, or composition of the other.
- [x] `D-C-02` Public Account Profile detail keeps a single `Sobre` shell/tab and renders separate blocks inside it when both fields are present. `bio` appears first, `content` second; capability-off or empty fields are omitted without blank spacing.
- [x] `D-C-03` If only one field is present, avoid redundant nested headings that repeat the shell label; if both are present, use small section labels derived from product copy (`Sobre` for bio, `Conteúdo` for content) unless the implementation finds existing canonical labels.
- [x] `D-C-04` Account Profile rich text adopts the current Event safe subset for this slice: paragraphs, `<br>`, headings, lists, blockquotes, `<strong>`, `<em>`, `<s>`, and emoji text. Links and richer HTML are out of scope.
- [x] `D-C-05` Plain-text legacy values with newline breaks must be canonicalized before rendering so line/paragraph structure is preserved.
- [x] `D-C-06` Admin preview/readback must use the same rendering semantics as public detail. Whitespace-collapsing strip-only previews are not an acceptable fidelity check.
- [x] `D-C-07` Introduce a dedicated Account Profile rich-text/content limit instead of raising shared short-description constraints globally.
- [x] `D-C-08` The approved hard cap is `100KB` per field after server-side sanitization. Raw inbound requests remain subject to existing global request/body safety limits.
- [x] `D-C-09` Flutter should show aligned usage guidance for the effective `100KB` content cap and a soft warning around `90%`; backend `422` remains authoritative for final validation.
- [x] `D-C-10` Tests must assert field-filling fidelity for both `bio` and `content`, including line breaks and every supported formatting element.

## Decision Pending

- [x] None. Static Asset rich-text parity is explicitly out of this Store Release slice rather than a pending decision.

## Questions To Close

- [x] None before implementation.

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-C-*` decisions above are frozen for Store Release orchestration. Implementation must preserve independent `bio` and `content` capability-backed rendering, Event-safe rich-text subset parity, plain-text newline canonicalization, dedicated `100KB` sanitized-content cap per field, admin/public preview fidelity, and field-filling fidelity tests.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** Fail-first Laravel validation/sanitizer tests and Flutter renderer/widget tests proving line breaks and supported formatting are preserved for both `bio` and `content`.
- **Sequencing note:** This TODO can run independently from taxonomy snapshots and discovery filters unless implementation chooses to share a rich-text renderer with Event content.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-C-01` | Event rich-text canonicalization/renderer behavior can be reused or adapted for Account Profile. | Events module already freezes a safe content subset and current intake found stronger Event rendering behavior. | Implementation may need a shared renderer extraction first, but the field contract remains. | `Medium` | `Keep as Assumption` |
| `A-C-02` | `100KB` sanitized payload per field is safe inside current backend request envelopes. | Local intake found PHP `post_max_size=12M`; user approved 100KB as product cap. | Backend may need additional request/body guard tuning, without changing product cap. | `High` | `Keep as Assumption` |
| `A-C-03` | Public detail can render both fields inside current `Sobre` shell without route/tab redesign. | Existing detail already has a `Sobre` module and user approved independent fields. | A new section/tab model would require renewed product approval. | `High` | `Promote to Decision` |

## Execution Plan

### Touched Surfaces

- Laravel Account Profile create/update/onboarding request validation.
- Laravel Account Profile sanitizer/persistence paths for `bio` and `content`.
- Flutter tenant-admin rich-text editor/readback/preview.
- Flutter Account Profile domain/parser/detail rendering.
- Shared Flutter rich-text renderer/canonicalizer if extraction is needed.
- Tests and module docs for durable field contract.

### Ordered Steps

1. Add fail-first tests for collapsed newlines, unsupported markup, both-field rendering, and over-limit backend validation.
2. Add dedicated backend long-form rich-text constraints and sanitizer-aligned validation for Account Profile fields.
3. Align Flutter editor usage guidance/counter with the approved cap and preserve `422` handling.
4. Reuse/extract a shared safe renderer/canonicalizer for Event and Account Profile semantics where practical.
5. Update public detail/admin preview rendering to faithfully render `bio` and `content`.
6. Run focused validation and promote stable decisions into module docs.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** The main requirement is visible fidelity between edited and rendered content.
- **Fail-first targets:** Public detail widget tests for line breaks/formatting and Laravel validation tests for 100KB/over-limit behavior.

### Runtime / Rollout Notes

- Existing content must continue rendering via canonicalization fallback.
- Backend validation change must not relax unrelated description fields.
- Unsupported markup should be stripped or rejected consistently with the approved Event subset.

## Audit Trigger Matrix

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-stack but bounded. |
| `blast_radius` | `cross-stack` | Laravel validation/sanitizer and Flutter rendering. |
| `behavioral_change_or_bugfix` | `yes` | Fixes visible rendering fidelity and field cap. |
| `changes_public_contract` | `yes` | Public detail may render `content` in addition to `bio`. |
| `touches_auth_or_tenant` | `yes` | Tenant-admin Account Profile edits. |
| `touches_runtime_or_infra` | `no` | No queue/infra expected. |
| `touches_tests` | `yes` | Strong tests required. |
| `critical_user_journey` | `yes` | Tenant-admin profile content and public detail. |
| `release_or_promotion_critical` | `yes` | Store Release lane. |
| `high_severity_plan_review_issue` | `no` | No plan review issue recorded yet. |
