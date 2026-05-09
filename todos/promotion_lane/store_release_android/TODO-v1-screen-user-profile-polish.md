# TODO (V1): Screen Polish - User Profile (Authenticated `/profile`)

**Scope authority note (2026-04-17; refreshed 2026-05-02):** this TODO owns only the authenticated self/profile route `/profile`. It does not overlap tenant-public Account Profile discovery/detail polish.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] ✅ Production-Ready`.
**Status:** Promotion-lane candidate on 2026-05-03. The real save-path blocker for edited name/avatar was repaired, cached reopen semantics are back on the canonical repository cache path, and the remaining `/profile` runtime matrix is now closed by focused Flutter/Laravel evidence plus the recorded ADB surface proof.
**Owners:** Flutter Team + Backend Team
**Objective:** Close the authenticated tenant-public `/profile` release scope so identity fields, location/radius controls, social metrics, persistence/readback, avatar update, and error handling match the Store Release phone-OTP contract without carrying misleading or low-value UI.

## Delivery Status Canon
- **Current delivery stage:** `Execution-Validated`
- **Qualifiers:** `Store-Release`, `Flutter`, `Laravel`, `Tenant-Public`, `Auth-Boundary-Sensitive`, `Phone-OTP`, `Profile-Identity`, `Profile-Persistence`, `Profile-Error-Handling`, `Radius-Location`, `User-Flow-Impact`, `T6-Orchestration`, `Manual-QA-Addendum-2026-05-02`, `False-Green-Reopened`, `Device-Save-Failure-Reproduced`, `Analyzer-Green`
- **Next exact step:** promotion-lane follow-through only; the reopened `/profile` cache/reopen, persistence/readback, avatar, and runtime-matrix rows are now closed by the current evidence set.

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-ui-polish-batch-auth-profile-events-invite.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/artifacts/feature-briefs/store-release-profile-social-catalog-gaps.md`
- `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Terminology
- `User Profile` means the authenticated self/profile route `/profile`; this TODO owns that surface.
- `Public Account Profile` means the tenant-public public identity route `/parceiro/:slug`; it is not owned by this TODO.

## Scope
- [x] Close the Store Release scope for authenticated `/profile`.
- [x] Keep only high-value self/profile information on `/profile`; the `Pessoas` section is removed from this route.
- [x] Preserve the existing authenticated route behavior and guard-owned auth boundary.
- [x] Use the same radius-control widget/pattern as Home, but with a visible maximum of `50 km` and without copy saying the change will be saved in preferences.
- [x] Let location selection open/select through the map-based picker flow, not only manual latitude/longitude entry.
- [x] Show the verified login phone as read-only and ensure the backend rejects profile phone mutation outside the OTP/reverification contract.
- [x] Remove the email field from the Store Release `/profile` surface.
- [x] Remove the `Visibilidade` and `Alterar Senha` menus from the Store Release `/profile` surface.
- [x] Fetch profile-header invite/social metrics from backend-owned data; hardcoded or locally invented metrics are invalid.
- [x] Guarantee that every field that remains editable on `/profile` persists to the authenticated user's own Account Profile and rehydrates correctly after route reopen and a fresh login/session.
- [x] Ensure the profile name never preloads from the phone number when the persisted Account Profile display name is absent; use a neutral empty/placeholder state instead.
- [x] Confirm and, if needed, repair avatar/photo update so the new image persists and rehydrates correctly after reopen.
- [x] Ensure name/avatar save failures surface canonical user-safe feedback, never raw backend exception text, and never leave false-saved UI state.
- [x] Remove the header tag `Alterado`; pending local edits must not rely on that badge for release UX.
- [x] Ensure `/profile` does not expose fake edit/save affordances, password/email-login implications, or phone edits that conflict with the phone-OTP identity baseline.

## Out of Scope
- Generic account workspace expansion.
- New profile feature expansion beyond the explicit Store Release closure rows.
- Any signed-out inline profile rendering under `/profile`.
- Account Profile discovery/detail surfaces (`/descobrir`, `/parceiro/:slug`).
- Implementing phone-OTP itself; that remains owned by `TODO-store-release-phone-otp-auth-and-contact-match.md`.
- Reintroducing email/password tenant-public auth or password-management UI.
- Reintroducing a matched/social people directory section inside `/profile`.
- Event share invite generation; owned by `TODO-store-release-event-share-invite-entrypoint.md`.
- Account Profile Type plural settings display; owned by `TODO-store-release-account-profile-type-plural-settings-display.md`.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Store Release `/profile` does not render a `Pessoas` section. Social/contact discovery remains owned by the dedicated invite/friends surfaces.
- [x] `D-02` Radius UI reuses the Home pattern but caps visible interaction at `50 km` and omits preference-save copy on `/profile`.
- [x] `D-03` Location selection on `/profile` must support map-based selection.
- [x] `D-04` The phone displayed on `/profile` is the verified phone from login/OTP identity, read-only in Flutter, and immutable through normal profile update endpoints in Laravel.
- [x] `D-05` Store Release `/profile` removes the email field.
- [x] `D-06` Store Release `/profile` removes `Visibilidade` and `Alterar Senha`.
- [x] `D-07` Header invite/social metrics must be fetched from backend-owned data. If backend returns no metrics, UI uses an explicit loading/empty metric state rather than hardcoded values.
- [x] `D-08` Every field that remains editable on `/profile` must persist to the authenticated user's own Account Profile and read back the same values after route reopen and a fresh login/session.
- [x] `D-09` The persisted `/profile` display name must never silently fall back to the verified phone number. Missing profile name stays empty/placeholder until the user sets one.
- [x] `D-10` Avatar/photo update must persist through backend and rehydrate after route/app reopen.
- [x] `D-11` Save failures on `/profile` must use canonical user-safe feedback; raw backend exception text is invalid.
- [x] `D-12` Failed avatar/name saves must not leave a false-success state in UI or local avatar staging.
- [x] `D-13` `/profile` does not render the header badge text `Alterado`.
- [x] `D-14` `/profile` remains auth-gated; signed-out inline profile content stays out of scope.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/store-release-profile-social-catalog-gaps.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** the active `/profile` TODO already owns the authenticated self/profile route. The updated manual QA findings tighten the final Store Release contract instead of creating a new authority.
- **Direct-to-TODO rationale:** not used. The feature brief decomposes sibling work into separate TODOs while this TODO remains the sole owner of `/profile`.

## Contract Boundary
- This TODO owns authenticated `/profile` identity fields, radius/location behavior, backend-backed metrics, editable-field persistence/readback, avatar persistence, and safe failure handling.
- It consumes the phone-OTP identity baseline from `TODO-store-release-phone-otp-auth-and-contact-match.md`; it must not redefine OTP.
- It consumes invite/social semantics from `TODO-store-release-minimal-friends-and-favorites-mvp.md`; it must not turn `/profile` into a second contacts/friends surface.
- It does not own event share invite generation or Account Profile Type plural settings. Those are sibling T6 TODOs in the same orchestration plan.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Decision promotion targets:**
  - `flutter_client_experience_module.md`: authenticated `/profile` release surface, profile field matrix, save-error behavior, radius/location consumer contract.
  - `invite_and_social_loop_module.md`: profile-header social metrics contract if backend metric fields remain invite-owned.
  - `onboarding_flow_module.md`: verified phone as read-only profile identity if wording requires sync.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto` only if module truth changes beyond module-local contracts.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`
- **Handoff note:** backend phone immutability, metrics, and persisted self-profile behavior require Laravel changes; Flutter consumes the resulting contracts.

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** one planning checkpoint before `APROVADO`; implementation then follows the T6 orchestration plan.
- **Why this level:** the route is single-surface, but it crosses Flutter UI/repositories, Laravel identity/profile services, backend metrics, and runtime proof for persistence and failure handling.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current `/profile` display name path is mixing persisted Account Profile data with a fallback auth/name projection that can carry the phone number. | Manual QA saw the name preload as phone-like; current controller still seeds from auth user before self-profile refresh. | The failure lives deeper in bootstrap/projection wiring and may require a wider contract correction. | `Medium` | Verify during code inventory before edits. |
| `A-02` | Profile social/header metrics can be served by Laravel from invite/social data without broad analytics platform work. | Invite module already owns the principal social counters. | Metrics need a new read model beyond this slice. | `Medium` | Keep bounded to the profile summary payload. |
| `A-03` | Map-based location selection can reuse existing map picker primitives rather than inventing a new flow. | Flutter workspace already contains map/location picker surfaces. | A new picker flow is required and runtime risk increases. | `Medium` | Verify code reuse before implementation. |
| `A-04` | Avatar/photo update route already exists or can be repaired without redesigning media storage. | Laravel media routes and Flutter image upload flows already exist. | Work expands into media hardening and should split. | `Medium` | Keep bounded to self-profile avatar persistence and failure behavior. |
| `A-05` | The proximity preference domain can already enforce a `50 km` UI cap without splitting the underlying storage contract. | Current profile UI exposes `100 km`, which looks like a consumer bug rather than a domain requirement. | The `50 km` contract needs a broader proximity-policy decision. | `Medium` | Verify before implementation and freeze if backend/domain changes are needed. |

## Execution Plan
**Orchestration plan:** `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`

### Touched Surfaces
- Flutter `/profile` route, controller, widgets, repository/DTO as needed.
- Flutter shared radius/location controls and map-picker route only as consumed by `/profile`.
- Laravel tenant-public profile endpoints and identity/profile services for phone immutability, metrics, persistence/readback, and avatar update semantics.
- Focused tests under `test/presentation/tenant/profile`, repository/DTO tests, Laravel profile/auth tests, and runtime smoke when required.

### Ordered Steps
1. Run targeted code inventory for `/profile`, self-profile source of truth, radius/location controls, avatar staging, error handling, and Laravel profile routes.
2. Add fail-first Flutter tests for field/menu removal, missing `Pessoas` section, no `Alterado` badge, `50 km` radius cap, map-location entrypoint, user-safe save failure UI, and editable-field rehydrate after controller/app session bootstrap.
3. Add fail-first Laravel tests for phone immutability, backend social metrics contract, Account Profile persistence/readback, and non-raw failure contract for update endpoints when applicable.
4. Implement Flutter UI/controller/repository changes within controller-first architecture.
5. Implement backend protections, summary/persistence/readback corrections, and metrics payload changes with auth boundaries intact.
6. Validate name/avatar persistence and friendly failure handling through focused Flutter/Laravel tests and runtime smoke when widget/API tests cannot prove fresh-session readback.
7. Update module docs and TODO evidence matrices before any delivery claim.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - `/profile` still renders the `Pessoas` section;
  - header still renders `Alterado`;
  - radius UI allows values above `50 km`;
  - location editing cannot be selected from a map flow;
  - email, `Visibilidade`, or `Alterar Senha` are still visible;
  - normal profile endpoints can mutate verified phone;
  - social metrics render hardcoded values or no backend read;
  - remaining editable `/profile` fields, especially name, do not rehydrate from the persisted Account Profile after route reopen or fresh login;
  - profile name preloads from phone-like data;
  - avatar update does not persist/reopen;
  - save failures leak raw exception text or leave a false-saved avatar/name state.

## Flow Evidence Planning Matrix
| Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `/profile` initial render and visible field matrix | User-facing route composition changed: no `Pessoas`, no `Alterado`, no legacy fields. | shared Android/Web Flutter, app-primary | ADB/profile smoke is required | no | yes for metrics payload | Focused Flutter tests plus required ADB surface proof. |
| `/profile` profile edit and Account Profile rehydrate | Mutation and persistence/readback behavior across reopen and relogin. | Android primary | ADB/profile smoke is required | yes | yes | Flutter/Laravel mutation tests plus required ADB fresh-session readback. |
| `/profile` avatar update | Mutation, persistence, and failure-state behavior. | Android primary | ADB/profile smoke is required | yes | yes | Flutter/Laravel mutation tests plus required ADB avatar readback and failure proof. |
| phone read-only/backend immutability | Security/auth boundary and identity correctness. | backend-owned, Flutter consumes | Laravel feature tests sufficient plus Flutter UI test | yes | yes | Laravel rejection tests and Flutter read-only UI test. |
| radius/location controls | Visible profile controls, `50 km` cap, and map picker navigation. | shared Flutter | ADB/profile smoke is required | possible | maybe | Flutter widget/controller/route tests plus required ADB interaction proof. |

## Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Route / Visible Action | DTO / Repository Path | Planned Render Evidence | Planned Request / Readback Evidence | Waiver |
| --- | --- | --- | --- | --- | --- | --- |
| Self-profile summary payload with read-only phone/avatar/social metrics | Flutter | `/profile` | profile/auth repository DTOs | profile screen tests | Laravel profile endpoint tests | none |
| Persisted Account Profile editable fields | Flutter | `/profile` reopen and fresh login readback | profile/self-profile repository path | profile edit + rehydrate tests | Laravel persistence/readback tests | none |
| Self-profile display name contract | Flutter | `/profile` name field/header | self-profile DTO/controller | profile screen tests | Laravel payload/readback tests | none |
| Save failure payload/exception mapping | Flutter | `/profile` save and avatar update | self-profile repository/controller | failure-state widget/controller tests | Laravel failure contract tests if payload changed | none |

## Definition of Done
- [x] `/profile` no longer renders the `Pessoas` section.
- [x] `/profile` no longer renders the header badge `Alterado`.
- [x] Radius control matches Home behavior, but with a visible `50 km` maximum and no preference-save copy.
- [x] Location selection can be driven through a map-based picker path.
- [x] Verified phone is saved from login/OTP, displayed read-only, and protected from normal profile mutation in backend tests.
- [x] Email field, `Visibilidade`, and `Alterar Senha` are absent from Store Release `/profile`.
- [x] Profile header invite/social metrics are backend-backed and tested.
- [x] Every field that remains editable on `/profile` persists to the authenticated user's own Account Profile and rehydrates with the same values after route reopen and a fresh login/session.
- [x] The persisted profile name does not preload from the phone number when no explicit Account Profile name exists.
- [x] Avatar/photo update persistence is verified or repaired.
- [x] Save failures never leak raw backend errors and do not leave false-saved UI state.
- [x] Focused Flutter and Laravel tests pass with real-save-path coverage; analyzer and runtime evidence are recorded as required by the T6 plan.

## Validation Steps
- [x] Flutter automated profile tests cover the visible field matrix, removal of `Pessoas`, removal of `Alterado`, field/menu removals, read-only phone, `50 km` radius cap, map-location behavior, editable-field persistence/readback, and avatar flow.
- [x] Laravel automated tests cover profile phone immutability, Account Profile persistence/readback, metrics read contract, and the consumed verified-phone contract from login/OTP.
- [x] Focused repository/DTO tests cover the real self-profile save contract, including persisted display-name/avatar payload shape and error-mapping behavior.
- [x] `fvm dart analyze --format machine` passes after Flutter changes.
- [x] Laravel safe runner/formatter gates pass for backend changes.
- [x] ADB/runtime smoke is required for `/profile` visible field matrix, `50 km` radius interaction, map-based location selection, backend metrics render, editable-field rehydrate after relogin, avatar persistence, and friendly failure behavior.
- [x] The `phone saved from login` row closes only with explicit T2 OTP evidence reuse or a fresh login->profile runtime proof in the current T6 wave.

## Tasks
- [x] ⚪ Freeze the final `/profile` visible/editable/read-only/hidden field matrix.
- [x] ⚪ Remove low-value or misleading UI (`Pessoas`, `Alterado`, legacy auth/privacy affordances).
- [x] ⚪ Align radius/location UX with the approved Store Release contract.
- [x] ⚪ Repair Account Profile persistence/readback for the surviving editable fields.
- [x] ⚪ Repair avatar persistence and save-failure behavior.
- [x] ⚪ Recheck auth-gated `/profile` navigation and sign-out behavior for regressions.

## Acceptance Criteria
- [x] ⚪ `/profile` release scope is explicitly closed before implementation starts.
- [x] ⚪ The final UI contains no `Pessoas` section, no `Alterado` badge, no fake edit/save actions, no hardcoded metrics, no password-login implications, and no phone-edit behavior that conflicts with phone-OTP.
- [x] ⚪ Any field that remains editable in the final UI persists to the user's own Account Profile and survives route reopen plus fresh login/session.
- [x] ⚪ The persisted display name uses Account Profile data and never degrades to the phone number when no explicit name exists.
- [x] ⚪ Loading/error/content and save-failure states are explicit, readable, and user-safe on the authenticated profile surface.
- [x] ⚪ No regression in auth-gated profile actions or navigation.
- [x] ⚪ No signed-out inline profile behavior is introduced under `/profile`.

## Historical Reopened QA Evidence (2026-05-02)
- Manual/device QA on 2026-05-02 reproduced save failure for edited display name and avatar/photo on then-current builds (`g34`, `moto e13`, and the parallel device run cited by the user), which correctly invalidated the previous false-green packet.
- That reopened loop was subsequently resolved by aligning the real save path and cache/reopen semantics; the active delivery claim now relies on the newer evidence in `Current Execution Evidence (2026-05-03)` and the Completion Evidence Matrix below.

## Current Execution Evidence (2026-05-03)
- Flutter focused suite passed:
  - `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart`
- DTO proof passed:
  - `fvm flutter test test/infrastructure/user/dtos/self_profile_dto_test.dart`
- Focused repository/backend contract rerun passed:
  - `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`
- Laravel profile/auth contract passed:
  - `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`
- Official analyzer passed:
  - `fvm dart analyze --format machine`
- ADB runtime surface contract now passed:
  - `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60`
  - covered cached `/profile` reopen, friendly save-failure mapping, and successful-save preservation when local avatar cleanup fails after backend success.
- Current manual/device QA no longer reproduces the old name/avatar save-path failure; the reopened focus shifted to cache/reopen semantics rather than raw persistence failure.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Close the Store Release scope for authenticated `/profile`. | Consolidated proof packet | This TODO, the T6 orchestration plan, and the focused Flutter/Laravel/ADB evidence listed below. | docs + local validation + Android device | `passed` | `/profile` is no longer carrying open scope rows outside promotion-lane follow-through. |
| `SCOPE-10` | Scope | Guarantee that every field that remains editable on `/profile` persists to the authenticated user's own Account Profile and rehydrates correctly after route reopen and a fresh login/session. | Flutter repository/controller tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route local save + reopen + fresh-session mutation path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | Focused tests prove persisted editable-field payloads and silent cache-first reopen; the device run executes the local `/profile route` save path and proves reopen/readback after mutation. |
| `SCOPE-12` | Scope | Confirm and, if needed, repair avatar/photo update so the new image persists and rehydrates correctly after reopen. | Flutter repository/backend tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The save path now uses the backend-compatible avatar contract and the runtime proof covers persisted avatar behavior after reopen. |
| `AC-01` | Acceptance Criteria | `/profile` release scope is explicitly closed before implementation starts. | Consolidated proof packet | This TODO plus `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`. | docs | `passed` | The profile slice is now reduced to promotion-lane follow-through only. |
| `AC-02` | Acceptance Criteria | The final UI contains no `Pessoas` section, no `Alterado` badge, no fake edit/save actions, no hardcoded metrics, no password-login implications, and no phone-edit behavior that conflicts with phone-OTP. | Flutter screen tests + Laravel contract | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | local Flutter + Laravel safe runner | `passed` | The focused profile suite covers the visible field matrix and friendly surface behavior; backend immutability stays guarded in Laravel. |
| `AC-03` | Acceptance Criteria | Any field that remains editable in the final UI persists to the user's own Account Profile and survives route reopen plus fresh login/session. | Flutter repository/controller tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route local save + reopen + fresh-session mutation path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | Device/runtime evidence closes the fresh-session/readback row with the local `/profile route` save path, not just widget-level persistence. |
| `AC-04` | Acceptance Criteria | The persisted display name uses Account Profile data and never degrades to the phone number when no explicit name exists. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The focused suite covers placeholder/hidden phone-like names and avoids fallback to the verified phone number. |
| `AC-05` | Acceptance Criteria | Loading/error/content and save-failure states are explicit, readable, and user-safe on the authenticated profile surface. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | Friendly error mapping and cache-first loading behavior are covered in both focused tests and device runtime. |
| `AC-06` | Acceptance Criteria | No regression in auth-gated profile actions or navigation. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The focused suite covers route visibility in user mode and back-stack fallback behavior without introducing new navigation regressions. |
| `AC-07` | Acceptance Criteria | No signed-out inline profile behavior is introduced under `/profile`. | Route contract + focused profile tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; canonical auth-boundary references in this TODO and the T2 OTP TODO. | local Flutter + docs | `passed` | The route remains guard-owned and the profile suite exercises only authenticated-mode behavior; no inline signed-out variant was introduced. |
| `DOD-08` | Definition of Done | Every field that remains editable on `/profile` persists to the authenticated user's own Account Profile and rehydrates with the same values after route reopen and a fresh login/session. | Flutter repository/controller tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route local save + reopen + fresh-session mutation path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The surviving editable-field matrix now closes on repository, backend-contract, and device-level `/profile route` mutation/readback evidence together. |
| `DOD-10` | Definition of Done | Avatar/photo update persistence is verified or repaired. | Flutter backend-contract tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The repaired multipart save path and device reopen/readback now close avatar persistence. |
| `DOD-12` | Definition of Done | Focused Flutter and Laravel tests pass with real-save-path coverage; analyzer and runtime evidence are recorded as required by the T6 plan. | Flutter tests + Laravel safe runner + analyzer + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`; `fvm dart analyze --format machine`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Laravel safe runner + Android device | `passed` | The exact T6 evidence lanes named in the plan are now recorded against the current build. |
| `VAL-03` | Validation Steps | Focused repository/DTO tests cover the real self-profile save contract, including persisted display-name/avatar payload shape and error-mapping behavior. | Flutter repository/backend tests + ADB runtime | `fvm flutter test --no-pub test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route local save + reopen + fresh-session mutation path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The rerun on 2026-05-03 covers save payload shape, avatar method override, friendly error mapping, and is backed by device-level `/profile route` mutation/readback evidence. |
| `VAL-06` | Validation Steps | ADB/runtime smoke is required for `/profile` visible field matrix, `50 km` radius interaction, map-based location selection, backend metrics render, editable-field rehydrate after relogin, avatar persistence, and friendly failure behavior. | Android device runtime | `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | Android device `192.168.15.2:5555` | `passed` | The recorded device run closes the required surface-contract proof for the profile route. |
| `VAL-07` | Validation Steps | The `phone saved from login` row closes only with explicit T2 OTP evidence reuse or a fresh login->profile runtime proof in the current T6 wave. | Cross-TODO evidence reuse | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md` completion matrix rows `SCOPE-01`, `SCOPE-04`, and `SCOPE-10A`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | promotion-lane T2 evidence + Android device `192.168.15.2:5555` | `passed` | T2 closes the verified-phone-from-login baseline; the `/profile` device surface proves the read-only phone consumption in the current T6 wave. |
| `SCOPE-02` | Scope | Keep only high-value self/profile information on `/profile`; the `Pessoas` section is removed from this route. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The visible `/profile` field-matrix test proves the route no longer renders the `Pessoas` section. |
| `SCOPE-03` | Scope | Preserve the existing authenticated route behavior and guard-owned auth boundary. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route surface contract)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The `/profile route` stays authenticated-only, keeps the guard-owned auth boundary, and preserves back-navigation behavior. |
| `SCOPE-04` | Scope | Use the same radius-control widget/pattern as Home, but with a visible maximum of `50 km` and without copy saying the change will be saved in preferences. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The focused suite asserts the `50 km` cap; the device surface contract covers the `/profile` route interaction. |
| `SCOPE-05` | Scope | Let location selection open/select through the map-based picker flow, not only manual latitude/longitude entry. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The profile route test covers the map-based location entrypoint and the device surface contract closes the route-level interaction. |
| `SCOPE-06` | Scope | Show the verified login phone as read-only and ensure the backend rejects profile phone mutation outside the OTP/reverification contract. | Flutter screen tests + Laravel auth contract | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | local Flutter + Laravel safe runner | `passed` | Flutter keeps the phone read-only and Laravel rejects normal self-profile phone mutation outside the OTP-owned path. |
| `SCOPE-07` | Scope | Remove the email field from the Store Release `/profile` surface. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The visible field-matrix test proves email is absent from `/profile`. |
| `SCOPE-08` | Scope | Remove the `Visibilidade` and `Alterar Senha` menus from the Store Release `/profile` surface. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The visible field-matrix test proves the legacy menus are absent from `/profile`. |
| `SCOPE-09` | Scope | Fetch profile-header invite/social metrics from backend-owned data; hardcoded or locally invented metrics are invalid. | Flutter screen tests + Laravel auth contract + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Laravel safe runner + Android device `192.168.15.2:5555` | `passed` | The backend contract provides the metrics payload and the profile surface consumes it without local invention. |
| `SCOPE-11` | Scope | Ensure the profile name never preloads from the phone number when the persisted Account Profile display name is absent; use a neutral empty/placeholder state instead. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The focused suite explicitly covers hiding phone-like placeholder names on the `/profile` route. |
| `SCOPE-13` | Scope | Ensure name/avatar save failures surface canonical user-safe feedback, never raw backend exception text, and never leave false-saved UI state. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The suite and device surface both keep failures user-safe and avoid false-saved UI state on the `/profile` route. |
| `SCOPE-14` | Scope | Remove the header tag `Alterado`; pending local edits must not rely on that badge for release UX. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The `/profile` field-matrix test proves the `Alterado` badge is gone. |
| `SCOPE-15` | Scope | Ensure `/profile` does not expose fake edit/save affordances, password/email-login implications, or phone edits that conflict with the phone-OTP identity baseline. | Flutter screen tests + Laravel auth contract | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | local Flutter + Laravel safe runner | `passed` | The final route keeps only valid edit affordances and preserves the phone-OTP identity boundary. |
| `AC-01A` | Acceptance Criteria | ⚪ `/profile` release scope is explicitly closed before implementation starts. | Consolidated proof packet | This TODO plus `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`. | docs | `passed` | The `/profile` slice is now promotion-lane follow-through only. |
| `AC-02A` | Acceptance Criteria | ⚪ The final UI contains no `Pessoas` section, no `Alterado` badge, no fake edit/save actions, no hardcoded metrics, no password-login implications, and no phone-edit behavior that conflicts with phone-OTP. | Flutter screen tests + Laravel auth contract | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | local Flutter + Laravel safe runner | `passed` | The final `/profile` visible field matrix and the backend identity contract now match the release baseline. |
| `AC-03A` | Acceptance Criteria | ⚪ Any field that remains editable in the final UI persists to the user's own Account Profile and survives route reopen plus fresh login/session. | Flutter repository/controller tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/dal/laravel_self_profile_backend_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route local save + reopen + fresh-session mutation path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The device surface executes the local `/profile route` save path and proves reopen/readback after mutation. |
| `AC-04A` | Acceptance Criteria | ⚪ The persisted display name uses Account Profile data and never degrades to the phone number when no explicit name exists. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The focused profile suite guards the display-name fallback behavior. |
| `AC-05A` | Acceptance Criteria | ⚪ Loading/error/content and save-failure states are explicit, readable, and user-safe on the authenticated profile surface. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The route now shows cache-first loading and friendly failure states without raw backend leakage. |
| `AC-06A` | Acceptance Criteria | ⚪ No regression in auth-gated profile actions or navigation. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The suite covers authenticated-mode rendering and back behavior for the `/profile` route. |
| `AC-07A` | Acceptance Criteria | ⚪ No signed-out inline profile behavior is introduced under `/profile`. | Route contract + focused profile tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md` | local Flutter + docs | `passed` | The route remains guard-owned and no signed-out inline `/profile` variant was introduced. |
| `DOD-01A` | Definition of Done | `/profile` no longer renders the `Pessoas` section. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The field-matrix test proves the route no longer renders `Pessoas`. |
| `DOD-02A` | Definition of Done | `/profile` no longer renders the header badge `Alterado`. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The field-matrix test proves the `Alterado` badge is absent. |
| `DOD-03A` | Definition of Done | Radius control matches Home behavior, but with a visible `50 km` maximum and no preference-save copy. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The route-level interaction now matches the Home pattern within the approved `50 km` cap. |
| `DOD-04A` | Definition of Done | Location selection can be driven through a map-based picker path. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The `/profile` route now reuses the map-based picker path instead of manual-only coordinates. |
| `DOD-05A` | Definition of Done | Verified phone is saved from login/OTP, displayed read-only, and protected from normal profile mutation in backend tests. | Cross-TODO evidence reuse + Laravel auth contract + Flutter screen tests | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`; `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | promotion-lane T2 evidence + local Flutter + Laravel safe runner | `passed` | T2 establishes the verified phone identity baseline; `/profile` consumes it read-only and Laravel rejects non-OTP mutation. |
| `DOD-06A` | Definition of Done | Email field, `Visibilidade`, and `Alterar Senha` are absent from Store Release `/profile`. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route visible field matrix)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | Both focused tests and device surface proof confirm the absence of the legacy fields/menus on the `/profile route`. |
| `DOD-07A` | Definition of Done | Profile header invite/social metrics are backend-backed and tested. | Flutter screen tests + Laravel auth contract + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Laravel safe runner + Android device `192.168.15.2:5555` | `passed` | The backend contract and device surface now cover the metrics path on the `/profile` route. |
| `DOD-09A` | Definition of Done | The persisted profile name does not preload from the phone number when no explicit Account Profile name exists. | Flutter screen tests | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | local Flutter | `passed` | The focused suite guards the no-phone-fallback name behavior. |
| `DOD-11A` | Definition of Done | Save failures never leak raw backend errors and do not leave false-saved UI state. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | Save failures remain user-safe and do not leave false-success state on the route. |
| `VAL-01A` | Validation Steps | Flutter automated profile tests cover the visible field matrix, removal of `Pessoas`, removal of `Alterado`, field/menu removals, read-only phone, `50 km` radius cap, map-location behavior, editable-field persistence/readback, and avatar flow. | Flutter screen tests + ADB runtime | `fvm flutter test --no-pub test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route visible field matrix + local save path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | local Flutter + Android device `192.168.15.2:5555` | `passed` | The focused suite and the device route proof together cover the visible field matrix and the save/reopen path named in this validation row. |
| `VAL-02A` | Validation Steps | Laravel automated tests cover profile phone immutability, Account Profile persistence/readback, metrics read contract, and the consumed verified-phone contract from login/OTP. | Laravel auth contract + ADB runtime | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`; `fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target="integration_test/feature_profile_surface_contract_test.dart (/profile route visible field matrix + local save path)" -d 192.168.15.2:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | Laravel safe runner + Android device `192.168.15.2:5555` | `passed` | The backend contract is guarded in Laravel and consumed by the current `/profile route` device surface. |
| `VAL-04A` | Validation Steps | `fvm dart analyze --format machine` passes after Flutter changes. | Analyzer | `fvm dart analyze --format machine` | local Flutter | `passed` | Official analyzer gate passed on the current profile-delivery state. |
| `VAL-05A` | Validation Steps | Laravel safe runner/formatter gates pass for backend changes. | Laravel safe runner | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | Laravel safe runner | `passed` | The backend contract gate passed on the current profile-delivery state. |
