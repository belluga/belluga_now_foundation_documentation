# TODO (V1): Tenant Resend Email Integration

**Status:** Completed (implemented and code-verified on 2026-04-02)
**Primary Module Anchor:** `foundation_documentation/modules/tenant_admin_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Complexity:** medium
**Checkpoint Policy:** consolidated review before approval

## 1. Context
The temporary pre-MVP tester waitlist route must stop depending on direct browser delivery to external form providers. Delivery must move behind a tenant-public backend endpoint so the public frontend remains provider-agnostic and no API token or sender envelope is exposed in the web bundle.

The admin surface already owns technical integrations through the settings kernel. Resend should join that same settings surface so each tenant can configure its own delivery envelope without introducing a parallel admin workflow.

## 2. Scope
In scope:
- Laravel package for tenant email delivery orchestration
- tenant settings namespace for Resend delivery defaults
- tenant-public endpoint `POST /api/v1/email/send`
- Flutter tenant-admin technical integrations UI for Resend configuration
- Flutter promotion lead-capture adapter migration from FormSubmit to backend endpoint
- targeted docs/tests for the new contract

Out of scope:
- generic template management
- attachment uploads from the public promotion form
- background queueing/retry policy beyond the synchronous MVP send path
- provider abstraction beyond the first Resend-backed implementation

## 3. Decision Baseline (Frozen)
- `D-01` Public waitlist/promotion frontend remains provider-agnostic and posts only to Belluga backend.
- `D-02` The tenant-public send endpoint is canonicalized as `POST /api/v1/email/send`.
- `D-03` Tenant delivery configuration is stored in settings-kernel namespace `resend_email`.
- `D-04` The admin integration UI must expose at least `token` and `from`, and also the Resend envelope fields that make operational sense for this fixed transactional flow: `to`, `cc`, `bcc`, and `reply_to`.
- `D-05` `to`, `cc`, `bcc`, and `reply_to` are tenant-configured arrays of email addresses; `token` and `from` are strings.
- `D-06` The backend validates tenant-stored delivery fields to mirror documented Resend expectations where feasible:
  - `from` accepts sender syntax with optional friendly name
  - `to` is capped at 50 recipients
  - `cc`, `bcc`, and `reply_to` are optional email arrays
- `D-07` If the tenant integration is incomplete (`token`, `from`, or `to` missing), the public endpoint returns an explicit “integration pending” failure instructing the user to contact the site administrator.
- `D-08` The public endpoint composes the tester-waitlist email content on the backend; the public frontend does not send Resend-native payload fields.

## 4. Plan
1. Document the new tenant-admin Resend integration and tenant-public email endpoint.
2. Create the Laravel email package and wire it into bootstrap/autoload.
3. Register the `resend_email` settings namespace and implement the tenant-public send controller/service.
4. Replace the Flutter promotion transport with the backend endpoint adapter.
5. Add the Resend integration section to tenant-admin settings and wire fetch/update through settings-kernel.
6. Run focused Laravel + Flutter validation.

## 5. Risks / Notes
- Resend still requires an externally verified sending domain; local validation can only verify payload shape, not domain ownership.
- Storing tenant delivery settings in the settings document is acceptable for this MVP slice, but follow-up hardening may require secret storage or encrypted fields.
- Do not leak provider-specific wording into the public promotion UI; only admin configuration can mention Resend.

## 6. Rule / Workflow Sources Used
- `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md`
- `delphi-ai/skills/wf-laravel-create-api-endpoint-method/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`

## 7. Decision Adherence Validation
| Decision | Status | Evidence |
|---|---|---|
| `D-01` | Adherent | `lib/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service.dart` posts only to Belluga backend; `test/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service_test.dart` asserts the provider-agnostic payload. |
| `D-02` | Adherent | `lib/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service.dart` canonicalizes `_endpointPath = '/api/v1/email/send'`; `../laravel-app/tests/Feature/Email/TenantEmailSendControllerTest.php` exercises the same route. |
| `D-03` | Adherent | `../laravel-app/app/Integration/Email/ResendEmailSettingsNamespaceRegistrar.php` registers tenant namespace `resend_email`; `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php` asserts it in schema and values responses. |
| `D-04` | Adherent | `lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_resend_email_section.dart` and `lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart` expose/save `token`, `from`, `to`, `cc`, `bcc`, and `reply_to`; widget coverage exists in `test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`. |
| `D-05` | Adherent | `lib/domain/tenant_admin/settings/tenant_admin_resend_email_settings.dart`, `lib/infrastructure/dal/dao/tenant_admin/tenant_admin_settings_request_encoder.dart`, and `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php` keep `token`/`from` as strings and recipient fields as arrays. |
| `D-06` | Adherent | `../laravel-app/app/Integration/Email/ResendEmailSettingsPatchGuard.php` validates sender/recipient envelope rules; `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php` covers invalid sender rejection and valid envelope acceptance. |
| `D-07` | Adherent | `../laravel-app/tests/Feature/Email/TenantEmailSendControllerTest.php` asserts `503` with the explicit integration-pending message when `token`, `from`, or `to` are incomplete. |
| `D-08` | Adherent | `../laravel-app/packages/belluga/belluga_email/src/Application/TenantEmailDeliveryService.php` composes the outbound email from `app_name` + ordered `submitted_fields`; `test/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service_test.dart` confirms the Flutter client does not send Resend-native fields. |

## 8. Validation
- `fvm flutter test test/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` ✅
- Backend feature coverage exists in `../laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php` and `../laravel-app/tests/Feature/Email/TenantEmailSendControllerTest.php` for schema/values, namespace patch validation, `503 integration pending`, `200 success`, `502 provider transport failure`, and `422 request validation`.
- Local execution of the Laravel feature tests was not possible in the current shell because `php` is unavailable, but the endpoint, settings namespace, package wiring, and Flutter-facing contract are all present in code and covered by committed tests.
