# TODO (V1): Beta Tester Dialog Stitch Alignment

**Current delivery stage:** Completed
**Qualifiers:** none
**Next exact step:** Archived in `foundation_documentation/todos/completed/` as delivered to `dev`.

**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Secondary Module Anchors:**
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/modules/tenant_admin_module.md`

**Decision Consolidation Targets:**
- `foundation_documentation/modules/flutter_client_experience_module.md` -> `2.1 Domain Rules / Task & Invite Hooks`
- `foundation_documentation/modules/tenant_admin_module.md` -> `POST /api/v1/email/send`
- `foundation_documentation/screens/app_promotion_tester_waitlist.md` (new canonical screen/flow doc for the Stitch-aligned dialog)

**Scope Ownership:**
- `EnvironmentType`: `tenant`
- `Main Scope`: `tenant_public`
- `Subscope`: `n/a`
- Route boundary: `/baixe-o-app`

**Complexity:** medium
**Checkpoint Policy:** one review checkpoint before approval

## 1. Context
The current tester waitlist experience is functionally acceptable, but it no longer matches the approved Stitch reference in project `Quóa`.

The approved reference adds and/or changes all of the following:
- form layout and success layout aligned to Stitch screens `Beta Tester (Sem Widget de Check)` and `Beta Tester (Formulário Limpo)`;
- a new free-text expectations question with approved copy:
  - `O que não pode faltar para atender às suas expectativas?`
- migration of the current checkbox-driven benefit content into bottom informational cards placed below the primary CTA;
- those bottom informational cards must render as a horizontally scrollable carousel;
- the success-state CTA `Continuar Navegando` must behave exactly like the close button: `pop()` only.

There is also a contract gap:
- the current promotion lead payload only sends `email`, `whatsapp`, `os`, and `app_name`;
- if the new question is rendered but the submit path stays field-specific, every future form change keeps forcing backend schema edits even though the current use case is only outbound email delivery;
- this slice should move the submit path to a generic submitted-fields envelope so Laravel can format and dispatch the email without knowing the semantic structure of the form.

## 2. Scope
In scope:
- `flutter-app/lib/presentation/shared/promotion/screens/app_promotion_screen/**`
- `flutter-app/lib/domain/promotion/**`
- `flutter-app/lib/domain/services/promotion_lead_capture_service_contract.dart`
- `flutter-app/lib/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service.dart`
- `flutter-app/test/presentation/shared/promotion/screens/app_promotion_screen/**`
- `flutter-app/test/infrastructure/services/promotion/**`
- `laravel-app/packages/belluga/belluga_email/**`
- `laravel-app/tests/Feature/Email/TenantEmailSendControllerTest.php`
- canonical docs required to describe the new screen/flow and request schema

Out of scope:
- route/guard topology changes
- replacing the active tester-waitlist variant with the app-download experience
- introducing runtime selection for the promotion experience variant
- changing the underlying delivery provider
- unrelated promotion copy outside the Stitch-aligned dialog

## 3. Definition of Done
- The beta tester dialog matches the approved Stitch structure closely while staying theme-driven and minimizing inline styling.
- The form includes:
  - `Seu Nome`
  - `E-mail`
  - `WhatsApp`
  - OS selection
  - `O que não pode faltar para atender às suas expectativas?`
- The current checkbox/bulleted benefit content is moved into bottom informational cards rendered as a horizontal carousel below the primary CTA.
- The success state is visually aligned to Stitch and exposes `Continuar Navegando`.
- `Continuar Navegando` and the close button both perform `pop()` only.
- The provider-agnostic lead-capture contract and backend endpoint accept a generic submitted-fields envelope and deliver the form content end-to-end without hardcoding field semantics in Laravel.
- Flutter and Laravel tests cover the new form fields, payload shape, success state, and dismissal semantics.
- Canonical docs are updated before Flutter code changes and remain aligned with the delivered payload/screen behavior.

## 4. Validation Steps
- `fvm flutter test test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`
- `fvm flutter test test/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service_test.dart`
- `fvm dart analyze --format machine`
- Laravel targeted email endpoint tests covering the generic submitted-fields schema and outbound email composition

## 5. Module Decision Baseline Snapshot
| Source Decision | Source | Relevance |
| --- | --- | --- |
| Canonical promotion route stays `/baixe-o-app` | `foundation_documentation/policies/web_to_app_promotion_policy.md` | Must be preserved. |
| Current active rendered experience may remain the temporary tester waitlist variant | `foundation_documentation/modules/flutter_client_experience_module.md` | Must be preserved for this slice. |
| Tester waitlist submit path stays provider-agnostic and posts only to tenant-public backend endpoint `POST /api/v1/email/send` | `foundation_documentation/modules/flutter_client_experience_module.md` | Must be preserved while generalizing payload shape. |
| Previous tester waitlist field baseline was `email`, `whatsapp`, and `os` | `foundation_documentation/todos/completed/TODO-v1-pre-mvp-tester-waitlist-promotion-experience.md` | Must be preserved and extended only with `Seu Nome` plus the newly approved expectations question. |

## 6. Decision Baseline (Frozen)
- `D-01` The canonical promotion route remains `/baixe-o-app`; this slice only refines the dialog/screen experience behind the existing route boundary.
- `D-02` The active experience remains the temporary tester waitlist variant; runtime experience selection stays out of scope.
- `D-03` The Stitch reference is the layout authority for both form and success states, but implementation must rely on existing theme tokens and avoid hardcoded color styling where the theme already provides the semantic role.
- `D-04` The form keeps the currently shipped lead fields `E-mail`, `WhatsApp`, and OS selection, and adds a short `Seu Nome` field plus the approved expectations question copy `O que não pode faltar para atender às suas expectativas?`; `Nome Completo` is intentionally not adopted from the Stitch reference.
- `D-05` The current benefit/checkbox content is moved into bottom informational cards rendered as a horizontal carousel below the primary CTA.
- `D-06` The success-state CTA `Continuar Navegando` and the close affordance must both perform `pop()` only; no route replacement fallback is allowed from those actions.
- `D-07` The provider-agnostic lead-capture contract and tenant-public backend endpoint are extended end-to-end with a generic submitted-fields envelope so Laravel can render outbound email content without knowing the form schema.
- `D-08` The implementation remains localized to the promotion surfaces and the existing email endpoint/package; no route/guard topology changes are permitted.
- `D-09` Widget/controller ownership remains architecture-compliant: controllers own form state, validation state, and submit side effects; widgets remain presentational.

## 7. Module Decision Consistency Matrix
| Decision | Relevant Prior Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `D-01` | Canonical `/baixe-o-app` route | Preserve | `foundation_documentation/policies/web_to_app_promotion_policy.md` |
| `D-02` | Temporary hardcoded tester waitlist variant | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` |
| `D-03` | Theme/runtime branding requirement for promotion surface | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` |
| `D-04` | Prior field baseline `email`, `whatsapp`, `os` | Preserve + extend with `Seu Nome` and expectations question | `foundation_documentation/todos/completed/TODO-v1-pre-mvp-tester-waitlist-promotion-experience.md` |
| `D-05` | Existing checkbox/bulleted benefit presentation | Supersede (Intentional) | current Flutter promotion widget implementation |
| `D-06` | Existing dismiss fallback behavior | Supersede (Intentional) | current `AppPromotionScreen._dismiss()` behavior |
| `D-07` | Provider-agnostic backend path via `POST /api/v1/email/send` | Preserve + generalize payload envelope | `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/tenant_admin_module.md` |
| `D-08` | Promotion behavior localized to promotion surfaces | Preserve | prior TODO + current module wiring |
| `D-09` | Flutter presentation/controller ownership rules | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` |

## 8. Assumptions Preview
| Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- |
| The currently shipped lead fields (`email`, `whatsapp`, `os`) remain the correct baseline, but the refined form should also add `Seu Nome` plus the expectations question. | User explicitly rejected `Nome Completo`, but then clarified that `Seu Nome` is in scope. | The form contract would need another approval pass before implementation. | High | Promote to Decision |
| The new form content should still be delivered through the same provider-agnostic backend endpoint instead of being client-only. | Current module docs explicitly state the submit path must remain provider-agnostic and post only to `POST /api/v1/email/send`. | The new UI would collect data that is not actually captured, creating contract drift. | High | Promote to Decision |
| A generic submitted-fields envelope is acceptable because the backend's role in this slice is only email dispatch/formatting. | User explicitly asked that the backend should not need to know the form content. | The endpoint would remain field-specific and continue to change for each form revision. | High | Promote to Decision |
| Reusing the current promotion route/module wiring is still correct. | User requested layout refinement, not route change; policy keeps the route stable. | Route behavior would need a separate tactical slice. | High | Keep as Assumption |

## 9. Execution Plan
1. Update canonical docs:
   - create the screen/flow doc for the Stitch-aligned beta tester dialog;
   - update Flutter module notes for the refined waitlist experience;
   - generalize the tenant email endpoint request schema in docs for submitted form fields.
2. Extend the Flutter lead-capture request/contract/service to submit `Seu Nome`, the current lead fields, and the new expectations answer through a generic submitted-fields envelope.
3. Extend the Laravel request validation and outbound email composition to accept/render the generic submitted-fields envelope without field-specific knowledge.
4. Refactor the beta tester promotion widget/success state to align with Stitch:
   - hero/form structure;
   - bottom informational carousel;
   - success-state CTA behavior.
5. Update Flutter and Laravel tests.
6. Run targeted validation.

**Test strategy:** test-after with focused regression coverage on Flutter widget/service tests and Laravel feature tests.
**Fail-first target rationale:** existing tests do not yet model the new fields or success CTA semantics; the focused regression additions in this slice will define the new contract directly.

## 10. Plan Review Gate
### Issue Cards
| Issue ID | Severity | Evidence | Why now | Options | Recommended |
| --- | --- | --- | --- | --- | --- |
| `BTD-01` | high | Flutter request/service + tenant email endpoint currently accept only field-specific top-level keys `email`, `whatsapp`, `os`, `app_name`. | The new `Seu Nome` field and expectations question should be captured without forcing Laravel to know the exact form schema. | `A)` generalize Flutter + Laravel to a submitted-fields envelope now; `B)` add new explicit backend fields for each new input; `C)` render the new inputs but drop them on submit. | `A` |
| `BTD-02` | medium | Current promotion widget still uses a checklist/block layout instead of the approved bottom-card carousel + refined success state. | The requested UI target is explicitly the Stitch design; a partial tweak leaves the temporary promotion surface inconsistent. | `A)` align structure to Stitch with theme-driven widgets; `B)` minimally restyle current layout; `C)` keep current layout. | `A` |
| `BTD-03` | medium | Current dismiss flow falls back to `TenantHomeRoute` when pop is unavailable. | Product explicitly asked for `Continuar Navegando` to mirror close-button `pop()` semantics only. | `A)` both close and success CTA use pop-only semantics in this experience; `B)` keep current fallback for one or both actions; `C)` remove the success CTA. | `A` |

### Failure Modes & Edge Cases
- Long tenant names or long expectation answers may break the layout if spacing/overflow are not controlled.
- Horizontal carousel cards may become inaccessible on smaller screens if item width and padding are not tuned.
- Generic payload validation mismatch between Flutter and Laravel could produce opaque submit failures.
- Success-state CTA semantics must not accidentally replace the stack/root route when the experience is shown from nested flows.

### Uncertainty Register
| Item | Status |
| --- | --- |
| Exact generic payload shape (`fields` map vs ordered labeled entries) | medium confidence; recommend ordered labeled entries to preserve email rendering order |
| Whether the expectations answer should be required or optional in submit validation | medium confidence; recommend required to match the approved form |

## 11. Rule / Workflow Sources To Follow
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/flutter-widget-local-state-heuristics/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/workflows/flutter/create-screen-method.md`
- `delphi-ai/workflows/laravel/create-api-endpoint-method.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## 12. Delivery Outcome
- Delivered and promoted to `dev` in both repositories.
- Flutter PR: `belluga_now_front#175`
  - merged SHA: `a3bcb96f40cfcce247f091eecff362b5d5a11c75`
- Laravel PR: `belluga_now_backend#127`
  - merged SHA: `627074a64ec0159743f76ce08437e538c2d53406`
- Validation completed:
  - `fvm flutter test test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart test/infrastructure/services/promotion/tenant_public_api_promotion_lead_capture_service_test.dart`
  - `fvm dart analyze --format machine`
  - `fvm flutter build web`
  - `docker compose exec ... php artisan test tests/Feature/Email/TenantEmailSendControllerTest.php`
