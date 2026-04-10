# TODO (V1): Pre-MVP Tester Waitlist Promotion Experience

**Status:** Completed (implemented and code-verified on 2026-04-02)
**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Secondary Module Anchor:** `foundation_documentation/policies/web_to_app_promotion_policy.md`
**Complexity:** medium
**Checkpoint Policy:** consolidated review before approval

## 1. Context
The canonical web promotion boundary (`/baixe-o-app`) is already established and currently renders the app-download/store-handoff experience. For the pre-MVP launch window, we need to keep the same route and guard/action entrypoints but temporarily replace the rendered experience with a tester waitlist invitation form.

The critical constraint is delivery isolation:
- the route contract must remain stable,
- the tester lead submission path must be isolated behind a dedicated contract/adapter,
- future replacement by the app-download experience must not require reworking route guards or route ownership.

The delivered implementation keeps the same route and guard boundary but now submits the tester lead through the tenant-public backend endpoint `POST /api/v1/email/send` using a provider-agnostic ordered `submitted_fields` envelope. The client remains adapter-isolated so transport/provider changes stay outside widgets/controllers.

## 2. Scope
In scope:
- `lib/presentation/shared/promotion/**`
- promotion route/controller/module wiring already owned by `AppPromotionModule`
- a dedicated lead-capture contract + infrastructure adapter for the tester form
- widget/repository tests for the embedded tester waitlist experience
- promotion policy/module documentation updates for the temporary pre-MVP variant

Out of scope:
- replacing the tenant-public lead-capture endpoint with a different backend/provider flow
- removing the app-download experience implementation
- changing web guard topology or route ownership

## 3. Decision Baseline (Frozen)
- `D-01` The canonical web promotion boundary route remains `/baixe-o-app`; guards and action gates must continue targeting the same route.
- `D-02` The currently active rendered experience is temporarily the tester waitlist form, hardcoded in this slice with an explicit VNext TODO to move the selection to runtime/backend config.
- `D-03` The existing app-download/store-handoff experience remains in code and must stay swappable behind the same route boundary.
- `D-04` Lead submission must go through a dedicated promotion lead-capture contract/adapter; widgets/controllers must not perform raw HTTP POST directly.
- `D-05` The active adapter must submit JSON to tenant-public backend endpoint `POST /api/v1/email/send`, preserving provider-agnostic lead capture on the client.
- `D-06` The delivered tester form collects `Seu Nome`, `email`, `whatsapp`, `os`, and the expectations question.
- `D-07` The form UI must use appropriate input affordances from the start: email keyboard for email, phone keyboard for WhatsApp, and explicit option selection for OS.
- `D-08` Error handling must remain explicit because the external adapter is still hypothesis-driven and may fail in real-browser validation.
- `D-09` This pre-MVP experience is intentionally disposable; future replacement must be operationally simple and isolated.

## 4. Decision Consistency Gate
| Decision | Relevant Prior Decision | Handling | Notes |
|---|---|---|---|
| `D-01` | `web_to_app_promotion_policy.md` canonical route rule | Preserve | Route boundary remains stable. |
| `D-02` | Existing app-download promotion screen behavior | Supersede (Temporary) | Rendered experience changes for pre-MVP while keeping route stable. |
| `D-03` | `TODO-v1-app-promotion-screen-branding-and-adaptive-store-selection.md` | Preserve partially | App-download experience remains implemented but not active. |
| `D-04` | Flutter architecture repository/service boundary rules | Preserve | Submission goes through contract/adapter, not widget HTTP. |
| `D-08` | External delivery/configuration failure handling | Preserve | Failure UX remains mandatory because backend/provider configuration may still fail at runtime. |

## 5. Plan
1. Update promotion policy/module docs to record the temporary pre-MVP tester waitlist variant behind the canonical route.
2. Introduce a promotion lead-capture contract + tenant-public backend adapter.
3. Refactor the promotion entry screen/controller to choose the currently active experience while preserving the route contract.
4. Implement the tester waitlist UI with proper field affordances and explicit success/error handling.
5. Add/update widget and adapter tests.
6. Run targeted tests and `./scripts/build_web.sh`.

## 6. Risks / Notes
- Tenant-public email delivery may still fail when backend/provider settings are incomplete; the adapter must remain swappable and error UX explicit.
- Do not spread tester-waitlist semantics across guards/routes; keep it localized to the promotion boundary.
- Avoid introducing backend coupling in this slice; the contract must allow swapping the transport later.

## 7. Rule / Workflow Sources Used
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## 8. Decision Adherence Validation
| Decision | Status | Evidence |
|---|---|---|
| `D-01` | Adherent | `lib/application/router/support/route_redirect_path.dart`, `lib/application/router/modular_app/modules/app_promotion_module.dart` keep `/baixe-o-app` as the canonical route. |
| `D-02` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart` hardcodes `testerWaitlist` as the active experience. |
| `D-03` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/widgets/app_promotion_download_experience.dart` remains in code and is still switch-selectable from the same boundary screen. |
| `D-04` | Adherent | `lib/application/contracts/promotion/promotion_lead_capture_service_contract.dart` + `lib/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service.dart` keep lead submission behind a dedicated contract/adapter. |
| `D-05` | Adherent | `lib/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service.dart` posts JSON to `POST /api/v1/email/send` with `app_name` + ordered `submitted_fields` and backend-facing error translation. |
| `D-06` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/widgets/app_promotion_tester_waitlist_experience.dart` and `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_tester_waitlist_controller.dart` collect `Seu Nome`, `email`, `whatsapp`, `os`, and the expectations field; widget coverage exists in `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`. |
| `D-07` | Adherent | `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` asserts email and phone keyboard affordances; the screen uses explicit platform `ChoiceChip`s. |
| `D-08` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_tester_waitlist_controller.dart` normalizes service errors and the widget test asserts visible error state. |
| `D-09` | Adherent | Tester-waitlist behavior remains localized to `lib/presentation/shared/promotion/screens/app_promotion_screen/**` and `AppPromotionModule`; no guard topology changed. |

## 9. Validation
- `fvm flutter test test/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` ✅
- Backend endpoint implementation and feature coverage exist in `laravel-app/tests/Feature/Email/TenantEmailSendControllerTest.php` for `503 integration pending`, `200 success`, `502 provider transport failure`, and `422 validation`.
- Local execution of the Laravel test target was not possible in the current shell because `php` is unavailable, but the Flutter client flow and adapter tests are green and the backend endpoint/test code is present.
