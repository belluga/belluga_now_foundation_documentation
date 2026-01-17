# TODO (V1): Push Delivery Consolidated Archive
**Version:** 1.0

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team, Delphi (Flutter), push_handler Plugin
**Objective:** Consolidate delivered push TODOs into a single archive for reference.

---

## Scope
- Archive all delivered push-related TODOs into this single document.

## Out of Scope
- New implementation or policy changes.

## Definition of Done
- [x] ✅ Production‑Ready All delivered push TODOs consolidated here.
- [x] ✅ Production‑Ready Split push TODO files removed from completed archives.

## Included TODOs
- `TODO-app-init-auth-before-push.md`
- `TODO-push-actions-idempotency-key.md`
- `TODO-push-cta-validation.md`
- `TODO-push-device-token-invalidations.md`
- `TODO-push-handler-close-behavior.md`
- `TODO-push-handler-composer-package.md`
- `TODO-push-handler-last-step-action-close.md`
- `TODO-push-handler-null-safety-and-steps-guard.md`
- `TODO-push-handler-readme-implementation-guide.md`
- `TODO-push-handler-readme-reference-payload.md`
- `TODO-push-handler-release-0.1.2.md`
- `TODO-push-handler-release-bump.md`
- `TODO-push-handler-token-register-logging.md`
- `TODO-push-integration-tests.md`
- `TODO-push-message-fetch-auth-bearer.md`
- `TODO-push-metrics-step-and-clicks.md`
- `TODO-push-noop-removal.md`
- `TODO-push-route-resolver-mapping.md`
- `TODO-restore-push-handler-plug-n-play.md`
- `TODO-v1-push-enable-endpoint.md`
- `TODO-v1-push-frontend.md`
- `TODO-v1-push-html-body-safety-and-keyboard.md`
- `TODO-v1-push-message-type-routes-endpoints.md`
- `TODO-v1-push-onboarding-callback-answers.md`
- `TODO-v1-push-onboarding-dynamic-steps.md`
- `TODO-v1-push-onboarding-ephemeral-answers.md`
- `TODO-v1-push-route-key-error-message.md`
- `TODO-v1-push-settings-nesting.md`
- `TODO-v1-push-setup-readme.md`
- `TODO-v1-telemetry-and-push-backend.md`

---

## Archived TODO: TODO-app-init-auth-before-push.md
**Original Path:** foundation_documentation/todos/completed/TODO-app-init-auth-before-push.md

```markdown
# TODO (V1): App Init Order Ensures Auth Before Push

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter app)  
**Objective:** Reorder application initialization so authentication/token state is ready before push handler init, preventing skipped token registration.

---

## Scope
- Ensure `AuthRepository` (or token provider source) is initialized before push handler init.
- Update application initialization flow (ApplicationContract + platform implementations) to enforce order.
- Keep push handler initialization behavior unchanged.

## Out of Scope
- Changing push handler plugin logic.
- Altering backend push registration endpoints.
- Reworking auth storage or token issuance.

## Definition of Done
- [x] ✅ Production‑Ready App init guarantees `tokenProvider` has a value before push init runs.
- [x] ✅ Production‑Ready Push init logs no longer show “Auth token missing; skip register” on first launch with a valid session.
- [x] ✅ Production‑Ready No regression in other init sequences (web/mobile).

## Validation Steps
- [x] ✅ Production‑Ready Fresh install: app logs show token acquired and register fired.
- [x] ✅ Production‑Ready Reinstall: app logs show token acquired and register fired (or explicit auth delay resolved before push init).

## Decisions
- Prefer app-layer ordering over plugin retry for now.

## Questions to Close
- None.
```

---

## Archived TODO: TODO-push-actions-idempotency-key.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-actions-idempotency-key.md

```markdown
# TODO (V1): Add Push Action idempotency_key From Flutter

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Ensure push action reports include required `idempotency_key` to avoid 422 and unblock delivery/opened tracking.

---

## Scope
- Add `idempotency_key` to push action payloads from `PushApiClient.reportAction`.
- Generate a stable idempotency key per action using FCM `messageId` + action + step index (fallback to `push_message_id` when missing).
- For `clicked` actions, include `button_key` in the idempotency key to avoid collisions across multiple CTAs on the same step.
- Update background delivery reporter to include `idempotency_key`.
- Log push fetch responses/payloads (sanitized) to aid debugging.
- Show push content even if action reporting fails.
- Add tests that assert distinct idempotency keys per button on the same step and stable keys across foreground/background reporting.

## Out of Scope
- Backend validation changes.
- Push message rendering/layout logic.

## Definition of Done
- [x] ✅ Production‑Ready `reportAction` includes `idempotency_key` for all actions.
- [x] ✅ Production‑Ready Background delivery reports succeed (no 422).
- [x] ✅ Production‑Ready Foreground actions succeed (no 422).
- [x] ✅ Production‑Ready Debug logs show payload/response details without blocking UI.
- [x] ✅ Production‑Ready Push UI still renders when action reporting fails.
- [x] ✅ Production‑Ready Clicking multiple buttons on the same step increments distinct click counts (no idempotency collisions).
- [x] ✅ Production‑Ready push_handler tests cover idempotency key generation with and without `button_key`.

## Validation Steps
- [x] ✅ Production‑Ready Send a push: `/actions` returns 200 for delivered/opened.
- [x] ✅ Production‑Ready No 422 errors in device logs.
- [x] ✅ Production‑Ready Push UI still appears when `/actions` fails.
- [x] ✅ Production‑Ready Click multiple buttons on the same step; `button_click_counts` increments per button key.
- [x] ✅ Production‑Ready Run `fvm flutter test` in `push_handler` and confirm idempotency key tests pass.

## Decisions
- Idempotency key format: `action:{message_id|push_message_id}:{action}:{step_index}:{device_id}` (no PII).
- Extend idempotency key to include `button_key` when present (especially `clicked`).
- Debug logs sanitize payloads (no tokens, minimal message fields).

## Questions to Close
- None.

## References
- `flutter-app/lib/infrastructure/services/push/push_api_client.dart`
- `flutter-app/lib/application/push/push_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/push_background_reporter.dart`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushMessageActionRequest.php`
```

---

## Archived TODO: TODO-push-cta-validation.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-cta-validation.md

```markdown
# TODO (V1): Push CTA Validation + Inline Selector Gating

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active  
**Owners:** Flutter App, push_handler Plugin  
**Objective:** Ensure push onboarding CTA enablement reflects selection requirements and text validators, with regression tests in the plugin and correct validator wiring in the app.

---

## Scope
- Update push_handler question/selector CTA gating:
  - `single_select` only enables CTA after exactly one selection.
  - `text` uses the step validator result (non-null string disables CTA).
  - `selector` with `selection_ui = inline` enables CTA only when `min_selected` is satisfied.
- Add/update StepConfig accessors needed for validator + selection UI.
- Add/adjust plugin widget tests for the CTA gating cases above.
- Verify Flutter app validator wiring so `required_text` returns a String error when invalid (ex: empty) and `null` when valid.
- Update onboarding documentation to reflect validator-based gating for push steps before code changes.

## Out of Scope
- Backend payload/schema changes.
- New selector layouts or selection_mode semantics beyond CTA gating.
- Telemetry changes.

---

## Tasks (Execution Checklist)

### Documentation
- [x] ✅ Production-Ready Update onboarding module docs to document validator-based CTA gating for push steps.

### push_handler Plugin
- [x] ✅ Production-Ready Adjust question/selector CTA enablement rules (single_select, text validator, inline selector min).
- [x] ✅ Production-Ready Add StepConfig accessors for `validator` and `selection_ui` (if missing).
- [x] ✅ Production-Ready Add tests:
  - single_select does not enable CTA without a selection.
  - text + validator keeps CTA disabled on empty input.
  - inline selector only enables CTA after `min_selected`.

### Flutter App
- [x] ✅ Production-Ready Verify `required_text` validator behavior and wiring (error string on invalid, null on valid).

---

## Definition of Done
- CTA enablement matches the required rules for single_select, text validators, and inline selectors.
- push_handler tests cover the new gating behavior.
- App validator returns correct values and is not ignored by the push flow.
- Documentation updated before code changes.

## Validation Steps
- Run push_handler tests covering the new cases.
- Run targeted Flutter app tests (or add one) if wiring changes are required.
```

---

## Archived TODO: TODO-push-device-token-invalidations.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-device-token-invalidations.md

```markdown
# TODO (V1): Push Device Token Upsert + Invalidations

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Laravel)  
**Objective:** Ensure device token registration upserts by `device_id`, and mark tokens invalid when push providers return NOT_FOUND to avoid retrying stale tokens.

---

## Scope
- On device register, upsert by `device_id`: update token if device exists; create new row if not.
- On push send, if provider response includes `NOT_FOUND`, mark the token as invalid/inactive and exclude it from future sends.
- Keep existing behavior for other error codes (do not mark invalid unless explicitly NOT_FOUND).

## Out of Scope
- Introducing new provider types or changing provider contracts.
- Altering audience/eligibility logic for push messages.
- Reworking device id generation on the client.

## Definition of Done
- [x] ✅ Production‑Ready Registering the same `device_id` updates the existing token row.
- [x] ✅ Production‑Ready Registering a new `device_id` creates a new token row.
- [x] ✅ Production‑Ready Sending to a NOT_FOUND token marks it invalid and prevents future sends.
- [x] ✅ Production‑Ready Non‑NOT_FOUND errors do not invalidate tokens.
- [x] ✅ Production‑Ready Automated tests cover token reactivation, invalidation, and filtering in sends.

## Provisional Notes
- Implementation updates `devices` entries with `is_active` and `invalidated_at` on register/invalidate and filters inactive tokens during resolution.
- Send controllers invalidate tokens when delivery response includes `error_code=NOT_FOUND`.

## Validation Steps
- [x] ✅ Production‑Ready Reinstall app (token changes) and confirm the existing `device_id` row updates token.
- [x] ✅ Production‑Ready Send to an invalid/stale token and confirm it is flagged and skipped on subsequent sends.
- [x] ✅ Production‑Ready Send to valid token continues to deliver and remains active.

## Decisions
- Treat provider `NOT_FOUND` as a signal that the token is invalid (likely uninstall/reinstall).
- Use an "invalid/disabled" status flag rather than deleting rows to preserve audit history.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/FcmHttpV1Client.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Controllers/Tenant/PushDeviceController.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/PushDevice.php`
```

---

## Archived TODO: TODO-push-handler-close-behavior.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-close-behavior.md

```markdown
# TODO (V1): Push Close Behavior Enum (Strict)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** push_handler Plugin, Laravel App, Flutter App  
**Objective:** Replace `closeOnLastStepAction` with `closeBehavior` (strict), add close button UX, and enforce validation across plugin + API.

---

## Scope
- **Plugin:** remove `closeOnLastStepAction` parsing; add `closeBehavior` enum (`after_action`, `close_button`).
- **UI:** when `closeBehavior=close_button` and the last step is visible, show a close (X) button in the top bar and do not auto-close after actions.
- **Behavior:** when `closeBehavior=after_action`, last-step actions close the push UI after completion (current behavior).
- **Laravel validator:** require `closeBehavior`, reject `closeOnLastStepAction`.
- **Docs:** update plugin README + foundation docs to reflect new field and enum values.
- **Tests:** add/update tests in plugin + Laravel to cover `closeBehavior` and the removal of `closeOnLastStepAction`.

## Out of Scope
- Backward compatibility with `closeOnLastStepAction`.
- Non-push modules.
- UI redesign beyond the close button.

---

## Tasks (Execution Checklist)

### Documentation (before code)
- [x] ✅ Production‑Ready Update foundation docs that describe push payload fields (replace `closeOnLastStepAction` with `closeBehavior`).
- [x] ✅ Production‑Ready Update plugin README to document the enum and UX rules.

### push_handler Plugin
- [x] ✅ Production‑Ready Add `closeBehavior` to message data model.
- [x] ✅ Production‑Ready Remove `closeOnLastStepAction` usage.
- [x] ✅ Production‑Ready Implement close button on last step when `closeBehavior=close_button`.
- [x] ✅ Production‑Ready Ensure action buttons do not close on `close_button`.
- [x] ✅ Production‑Ready Add/update tests for `closeBehavior` behaviors.

### Laravel App
- [x] ✅ Production‑Ready Update request validation rules: require `closeBehavior`, reject `closeOnLastStepAction`.
- [x] ✅ Production‑Ready Add/adjust request tests to cover the new rules.

## Decisions
- Use `required_with:payload_template` for `payload_template.closeBehavior` in update requests, so partial updates remain possible while enforcing strictness when the payload is present.

---

## Definition of Done
- [x] ✅ Production‑Ready `closeBehavior` is enforced as the only supported field (strict).
- [x] ✅ Production‑Ready Close button appears on last step when `closeBehavior=close_button`.
- [x] ✅ Production‑Ready Action buttons close only when `closeBehavior=after_action`.
- [x] ✅ Production‑Ready Tests updated and passing (plugin + Laravel).
- [x] ✅ Production‑Ready Docs updated to match the new contract.

---

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter test` in push_handler repo.
- [x] ✅ Production‑Ready Run `docker compose exec -T app php artisan test` (Laravel).
```

---

## Archived TODO: TODO-push-handler-composer-package.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-composer-package.md

```markdown
# TODO (V1): Publishable Push Handler Package (Composer)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Laravel)  
**Objective:** Make `belluga_push_handler` publishable via Composer with Laravel auto‑discovery so routes load without manual provider registration.

---

## Scope
- Add a `composer.json` to `laravel-app/packages/belluga/belluga_push_handler/` with proper package metadata and Laravel auto‑discovery.
- Wire the local app to load the package via Composer path repository for development.
- Ensure package routes appear in `php artisan route:list` after discovery.

## Out of Scope
- Publishing to Packagist or a VCS registry.
- Breaking changes to existing route paths or middleware.
- Refactors inside push handler controllers/services.

## Definition of Done
- [x] ✅ Production‑Ready Package `composer.json` exists with PSR‑4 autoload and `extra.laravel.providers`.
- [x] ✅ Production‑Ready Local app uses Composer path repository to load the package.
- [x] ✅ Production‑Ready `php artisan route:list` shows the push routes (e.g., `/api/v1/settings/push/credentials`).

## Validation Steps
- [x] ✅ Production‑Ready `composer dump-autoload` (via Docker) completes successfully.
- [x] ✅ Production‑Ready `php artisan route:list | rg "push|credentials"` shows push routes.

## Decisions
- Use Composer auto‑discovery as the publishable path (no manual provider registration).

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/PushHandlerServiceProvider.php`
- `laravel-app/packages/belluga/belluga_push_handler/routes/push_handler.php`
```

---

## Archived TODO: TODO-push-handler-last-step-action-close.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-last-step-action-close.md

```markdown
# TODO (V1): push_handler Last-Step Action Close Behavior

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** push_handler Plugin  
**Objective:** Fix last-step action buttons so they do not navigate to the previous step before executing their action.

---

## Scope
- Ensure last-step action buttons (route/external/custom) close the push UI without triggering a step-back.
- Prevent double-close behavior (`button` close + `PopScope` close).
- Add a regression test for last-step action buttons.

## Out of Scope
- Payload schema changes.
- App-side callback wiring changes.
- UI/visual redesign.

---

## Tasks (Execution Checklist)
- [x] ✅ Production‑Ready Identify the double-close path causing `toPrevious()`.
- [x] ✅ Production‑Ready Adjust close flow to a single, deterministic path.
- [x] ✅ Production‑Ready Add a regression test covering last-step action navigation.
- [x] ✅ Production‑Ready Update README if any behavior notes must change.

---

## Definition of Done
- [x] ✅ Production‑Ready Last-step action buttons do not step back before action.
- [x] ✅ Production‑Ready Regression test added and passing.
- [x] ✅ Production‑Ready `fvm flutter test` passes in push_handler repo.

---

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter test` in the push_handler repository.
```

---

## Archived TODO: TODO-push-handler-null-safety-and-steps-guard.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-null-safety-and-steps-guard.md

```markdown
# TODO (V1): Harden push_handler Null Safety + Step Navigation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter plugin)  
**Objective:** Prevent push_handler crashes when optional fields are missing and guard step navigation for single/empty step payloads.

---

## Scope
- Make `ChatDataDTO.tryFromMap` null-safe when `chat` is missing or null.
- Make `ImageDataDTO.tryFromMap` null-safe when `image` is missing, empty, or missing `path`.
- Ensure MessageData parsing tolerates absent/empty `steps` arrays.
- Guard `PushWidgetController.toNext()` when steps length is 0/1 to avoid invalid TabController index.
- Use the local `push_handler` package via `dependency_overrides` in `pubspec.yaml` for testing.
- Support payload-defined background color on push layouts.
- On the last step, show Actions or a forced dismiss button when no action is provided.
- On the last step, use a close (X) dismiss affordance instead of “pular”.
- Add a routeResolver callback to handle internal navigation externally (AutoRoute).
- Use Wrap for action buttons to prevent overflow on small screens.

## Out of Scope
- Backend payload schema changes.
- Visual layout refactors.

## Definition of Done
- [x] ✅ Production‑Ready Push rendering no longer throws when `chat` is null.
- [x] ✅ Production‑Ready Push rendering no longer throws when `image` is null/empty or missing `path`.
- [x] ✅ Production‑Ready Single/zero-step payloads do not trigger TabController assertion.
- [x] ✅ Production‑Ready Push UI renders for the same payload used in device logs.
- [x] ✅ Production‑Ready App uses local `push_handler` override for testing.
- [x] ✅ Production‑Ready Payload background color renders as specified.
- [x] ✅ Production‑Ready Last step always has an action or forced dismiss.
- [x] ✅ Production‑Ready Last-step dismiss uses close (X) affordance.
- [x] ✅ Production‑Ready Route resolver handles internal navigation without Navigator.pushNamed errors.
- [x] ✅ Production‑Ready Buttons wrap without RenderFlex overflow.

## Validation Steps
- [x] ✅ Production‑Ready Trigger push with `steps: []` → UI renders without crash.
- [x] ✅ Production‑Ready Trigger push without `chat` → UI renders without crash.
- [x] ✅ Production‑Ready Logs confirm local `push_handler` is in use after rebuild.
- [x] ✅ Production‑Ready Push payload with background color renders correctly.
- [x] ✅ Production‑Ready Last-step without action renders forced close (X) dismiss.
- [x] ✅ Production‑Ready Internal route buttons navigate via routeResolver.

## Decisions
- Prefer null-safe DTO parsing and no-op step navigation when there is no next step.
- Payload background color uses hex string (e.g., `#RRGGBB`).

## Questions to Close
- None.

## References
- `flutter-packages/push_handler/lib/src/domain/dto/chat_data_dto.dart`
- `flutter-packages/push_handler/lib/src/domain/dto/message_data_dto.dart`
- `flutter-packages/push_handler/lib/src/presentation/controller/push_widget_controller.dart`
```

---

## Archived TODO: TODO-push-handler-readme-implementation-guide.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-readme-implementation-guide.md

```markdown
# TODO (V1): push_handler README Implementation Guide

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** push_handler Plugin  
**Objective:** Deliver a full README guide for future implementations, documenting callback wiring, gate handling, and where app-side persistence belongs.

---

## Scope
- Expand the plugin README to describe the push flow responsibilities and default behaviors.
- Document callback contracts (`gatekeeper`, `optionsBuilder`, `onStepSubmit`, `onCustomAction`, `stepValidator`).
- Explain selector behavior (`selection_ui=inline|external`, `selection_mode`, `min_selected`).
- Document `OptionItem.isSelected` and pre-selection behavior.
- Clarify CTA default behavior + `continue_after_action` rules (CTA only, gates always re-check).
- State explicitly that the plugin does **not** persist answers; persistence is app-side via callbacks.
- Provide implementation guidance for app-side persistence (controller/repository patterns).

## Out of Scope
- Flutter app wiring changes.
- Backend changes.
- Payload schema changes beyond documentation.

---

## Tasks (Execution Checklist)
- [x] ✅ Production‑Ready Review current push_handler README sections and structure.
- [x] ✅ Production‑Ready Add a “Flow Responsibilities” section (plugin vs app).
- [x] ✅ Production‑Ready Add “Callbacks & Contracts” with code examples.
- [x] ✅ Production‑Ready Add “Selectors & Gates” section (inline vs external, gate re-checking).
- [x] ✅ Production‑Ready Add “Persistence Guidance” section (app-side only).
- [x] ✅ Production‑Ready Add “Payload Checklist” section for future implementers.
- [x] ✅ Production‑Ready Ensure all examples use generic, non-app-specific naming.

---

## Definition of Done
- [x] ✅ Production‑Ready README documents all callback contracts and default behaviors.
- [x] ✅ Production‑Ready README states plugin has no persistence and shows where app persistence lives.
- [x] ✅ Production‑Ready README includes selector + gate behavior and `OptionItem.isSelected`.
- [x] ✅ Production‑Ready README examples are generic and future‑proof.
- [x] ✅ Production‑Ready Tests run for push_handler plugin.

---

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter test` in the push_handler repository.
```

---

## Archived TODO: TODO-push-handler-readme-reference-payload.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-readme-reference-payload.md

```markdown
# TODO (V1): push_handler README Reference Payload + Flow Diagram

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** push_handler Plugin  
**Objective:** Add a reference payload example and a callback-flow diagram to the push_handler README.

---

## Scope
- Add a “Reference Payload” section with a full, generic payload example.
- Add a “Callback Flow” diagram (ASCII) describing the runtime sequence.
- Ensure examples stay generic (no app-specific identifiers).
- Keep guidance aligned with callback-only persistence.

## Out of Scope
- Code changes.
- Payload schema changes.
- Foundation module docs updates beyond this README task.

---

## Tasks (Execution Checklist)
- [x] ✅ Production‑Ready Review the updated README layout for insertion points.
- [x] ✅ Production‑Ready Draft “Reference Payload” (multi-step, question + selector + gate).
- [x] ✅ Production‑Ready Draft “Callback Flow” ASCII diagram.
- [x] ✅ Production‑Ready Verify terminology matches existing README sections.

---

## Definition of Done
- [x] ✅ Production‑Ready README includes a full reference payload example.
- [x] ✅ Production‑Ready README includes an ASCII callback-flow diagram.
- [x] ✅ Production‑Ready Examples are generic and callback-only.
- [x] ✅ Production‑Ready Tests run for push_handler plugin.

---

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter test` in the push_handler repository.
```

---

## Archived TODO: TODO-push-handler-release-0.1.2.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-release-0.1.2.md

```markdown
# TODO: push_handler Release 0.1.2

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Publish a clean 0.1.2 release with version bump and updated changelog.

---

## Scope
- Update `push_handler` package version to `0.1.2`.
- Update `push_handler` changelog to reflect the release highlights and fixes.

## Out of Scope
- Any new features or refactors beyond version/changelog updates.
- App-side changes in `flutter-app`.

## Definition of Done
- [x] ✅ Production‑Ready `pubspec.yaml` in `push_handler` reflects version `0.1.2`.
- [x] ✅ Production‑Ready `CHANGELOG.md` includes a new entry for `0.1.2` with accurate release notes.
- [x] ✅ Production‑Ready No other files modified in `push_handler` for this release task.

## Validation Steps
- [x] ✅ Production‑Ready Manual diff review of `push_handler/pubspec.yaml` + `push_handler/CHANGELOG.md`.

## Decisions
- Changelog bullets for `0.1.2`:
  - Add `enableDebugLogs` to `PushTransportConfig` and persist it in secure storage.
  - Gate push debug logging across repository init/queue, background entrypoint/reporting, and action reporting.
  - Add `test` dev dependency for package tests.
```

---

## Archived TODO: TODO-push-handler-release-bump.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-release-bump.md

```markdown
# TODO (V1): Push Handler Release Bump

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter/Plugin)  
**Objective:** Publish plugin changes with updated changelog, README, and version number.

---

## Scope
- Update `CHANGELOG.md` with the latest push presentation fixes and queue behavior.
- Update `README.md` to document presentation gate/mode and latest-only queue behavior.
- Bump plugin version in `pubspec.yaml` (and lockfile if required).

## Out of Scope
- Functional code changes (already delivered).
- Flutter app or backend changes.

## Definition of Done
- [x] ✅ Production‑Ready CHANGELOG entry added for the new version.
- [x] ✅ Production‑Ready README documents presentation gate/mode and latest-only queue semantics.
- [x] ✅ Production‑Ready Plugin version updated in `pubspec.yaml` (and `pubspec.lock` if needed).

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze` passes for the plugin package.

## Decisions
- Keep plug'n'play default behavior; gate/mode is optional.
- Latest-only queue replaces older queued pushes by design.
```

---

## Archived TODO: TODO-push-handler-token-register-logging.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-handler-token-register-logging.md

```markdown
# TODO (V1): Push Handler Token Register Logging

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter/push_handler)  
**Objective:** Add structured debug logs around token acquisition and device registration so we can verify whether register is firing and why it may skip after reinstall.

---

## Scope
- Log when push init starts token flow (pre‑`getToken()`).
- Log token retrieval result (success/empty/error) without printing the raw token (use hash or length only).
- Log when register request is sent (device_id + platform + token hash).
- Log when register request is skipped and why (e.g., missing token, missing auth token, previously cached state).

## Out of Scope
- Changing registration logic or auth flow.
- Adding telemetry/metrics beyond debug logs.
- Altering backend contracts.

## Definition of Done
- [x] ✅ Production‑Ready Logs exist for token acquisition start, token value presence, and register attempt.
- [x] ✅ Production‑Ready Logs include device_id, platform, and token length (never raw token).
- [x] ✅ Production‑Ready Logs include explicit skip reasons.

## Validation Steps
- [x] ✅ Production‑Ready Fresh install: logs show token acquired and register request fired.
- [x] ✅ Production‑Ready Reinstall: logs show either token acquired + register OR explicit skip reason.

## Decisions
- Never log raw push tokens; use token length only.

## Implementation Notes
- Logs added in `push_handler_repository_contract.dart` for token flow start, token presence, refresh, and register attempts.

## Questions to Close
- None.
```

---

## Archived TODO: TODO-push-integration-tests.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-integration-tests.md

```markdown
# TODO: Push Integration Tests (Flutter App)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Increase confidence in push bootstrap + registration behavior without E2E/device tests.

---

## Scope
- Add unit tests for `PushTransportConfigurator` to validate:
  - `baseUrl` uses `BellugaConstants.api.baseUrl`.
  - `tokenProvider` returns `null` when `AuthRepositoryContract.userToken` is empty and returns the token when set.
  - `deviceIdProvider` delegates to `AuthRepositoryContract.getDeviceId`.
  - `enableDebugLogs` matches `kDebugMode`.
- Add a small test seam (if needed) to validate push initialization wiring without changing runtime behavior.
  - Example seam: inject a repository factory or allow overriding the push registration method in tests.
- Add unit tests for push initialization wiring (non-web path):
  - Confirms `PushHandlerRepositoryDefault.init()` is invoked with a config built from `PushTransportConfigurator`.
  - Confirms `platformResolver` returns `BellugaConstants.settings.platform`.
- Add unit tests for the web guard:
  - When `kIsWeb` is true, push registration is skipped and does not attempt to init the repository.

## Out of Scope
- Any E2E/device or Firebase integration tests.
- Backend/Laravel tests.
- New push features or API changes.

## Definition of Done
- [x] ✅ Production‑Ready Unit tests cover the `PushTransportConfigurator` behaviors listed in scope.
- [x] ✅ Production‑Ready Push initialization wiring is testable via a minimal seam with no runtime change.
- [x] ✅ Production‑Ready Tests confirm repository `init()` is called for non-web paths and skipped on web.
- [x] ✅ Production‑Ready Tests pass via `fvm flutter test` in `flutter-app`.

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter test` in `flutter-app` passes.

## Decisions
- Prefer a minimal test seam (factory/override) rather than refactoring `ApplicationContract` architecture.
- Keep tests strictly unit-level; mock repositories and platform flags.
```

---

## Archived TODO: TODO-push-message-fetch-auth-bearer.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-message-fetch-auth-bearer.md

```markdown
# TODO (V1): Push Message Fetch With App Bearer Token

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter), Delphi (Laravel)  
**Objective:** Fetch user-specific push message content using the app Bearer token while keeping payloads anonymous.

---

## Scope
- Keep `/api/v1/push/messages/{message_id}` authenticated with Bearer token.
- Validate recipient eligibility using the authenticated `user_id` (Bearer token) against the message audience.
- Flutter: ensure push fetch sends Bearer token when calling `fetchMessageData`.
- Keep push payload limited to `push_message_id` (no user identifiers).
- Document the auth strategy and required headers/body fields in foundation docs.

## Out of Scope
- Opening the push message route publicly.
- Adding signed URLs or nonce exchange flows.
- Replacing the existing push handler layout rendering.

## Definition of Done
- [x] ✅ Production‑Ready Laravel route for `/api/v1/push/messages/{message_id}` remains authenticated and enforces user eligibility checks.
- [x] ✅ Production‑Ready Flutter `PushApiClient.fetchMessageData` sends Bearer token consistently.
- [x] ✅ Production‑Ready Background delivery reporting uses the same auth strategy where applicable.
- [x] ✅ Production‑Ready Documentation updated with auth contract and payload expectations.

## Validation Steps
- [x] ✅ Production‑Ready Authenticated fetch returns message data for eligible user.
- [x] ✅ Production‑Ready Authenticated fetch returns 404 for non-eligible user.
- [x] ✅ Production‑Ready Foreground push renders correctly after fetch.
- [x] ✅ Production‑Ready Background receipt still records `delivered` action.

## Decisions
- Use the app Bearer token for message fetches; payloads remain anonymous (only `push_message_id`).
- Enforce audience eligibility using the authenticated user (no device-id check).
- Respond with `404 Not Found` for non-eligible users to avoid revealing message existence.

## Questions to Close
- None.

## References
- `flutter-app/lib/application/push/push_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/push_api_client.dart`
- `laravel-app/routes/api/*` (push routes)
```

---

## Archived TODO: TODO-push-metrics-step-and-clicks.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-metrics-step-and-clicks.md

```markdown
# TODO (V1): Push Metrics — Step Views & Clicks

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter + Laravel)  
**Objective:** Ensure step view counts, button click counts, and unique click metrics are recorded for push messages, and centralize push-specific fetching/handling inside the `push_handler` plugin where appropriate.

---

## Scope
- Trace how `step_view_counts`, `button_click_counts`, `clicked_count`, and `unique_clicked_count` are recorded in the backend.
- Validate that the Flutter client sends the expected action payloads (step viewed, button clicked) with idempotency keys.
- Fix any missing action reporting in Flutter or the Laravel handler so metrics populate.
- Remove `opened` action recording from push message fetch; rely on presenter-driven `opened`.
- Add debug logging in Flutter (push_handler) for action reports (`opened`, `step_viewed`, `clicked`, `dismissed`) with payload fields needed to validate metrics.
- [x] ✅ Production‑Ready Delegate push message fetching/parsing to `push_handler` (avoid duplicating plugin logic in app code).
- [x] ✅ Production‑Ready Audit the Flutter app for push-specific logic that should live in `push_handler`, and relocate or wrap it as needed.
- [x] ✅ Production‑Ready Audit existing `push_handler` orchestration/presentation features and remove duplicate app-level push orchestration/presentation where the plugin already covers it.
- [x] ✅ Production‑Ready Move Firebase Messaging listener/initial message handling into `push_handler` so the app only wires callbacks/configuration.
- [x] ✅ Production‑Ready Remove push-layer access to secure storage and any parallel auth services; push must read a single `userToken` from `AuthRepository` (anonymous users still have `userToken`).
- [x] ✅ Production‑Ready Move Firebase initialization out of `PushCoordinator` into the application initialization flow (ApplicationContract with platform-specific wiring).
- [x] ✅ Production‑Ready Remove `AnonymousAuthService` and any `anonymousToken` persistence; anonymous users still use `userToken`.
- [x] ✅ Production‑Ready Remove the shared `StorageKeys` dumping ground by relocating key ownership to repositories:
  - `userToken` owned by `AuthRepository`.
  - `deviceId` owned by `AuthRepository` (user identity lifecycle).
  - `apiBaseUrl` owned by `AppDataRepository`.
  - `tenantId` owned by `TenantRepository`.
- [x] ✅ Production‑Ready Remove `PushCoordinator` entirely; app should rely on `push_handler` plug’n’play coordination.
- [x] ✅ Production‑Ready Replace `routeResolver` + `navigatorKey` with a `navigationResolver` callback so navigation remains app‑owned and the plugin stays agnostic.
- [x] ✅ Production‑Ready Add a top‑level background handler in `push_handler` and register it from the repository init (no main.dart wiring).
- [x] ✅ Production‑Ready Stop parsing `MessageData` directly from FCM payload in `PushHandler`; emit raw messages and let the repository fetch/parse from API.
- [x] ✅ Production‑Ready Persist minimal push transport config in `push_handler` so background entrypoint can rehydrate and report delivery.
- [x] ✅ Production‑Ready Flush the background delivery queue when the app returns to foreground (lifecycle resume), not only on initial init.
- [x] ✅ Production‑Ready Auto-present queued background messages on app resume (foreground) as if just received, skipping if expired.
- [x] ✅ Production‑Ready Remove queued background entries when the message is presented in foreground to avoid duplicate opens.
- [x] ✅ Production‑Ready Add an integration test in the Flutter app to validate queue flush + auto-present on resume.
- [x] ✅ Production‑Ready Ensure the integration test dismisses the auto-presented UI so queue flushing can complete.
- [x] ✅ Production‑Ready Expand push_handler tests to cover all layouts, button actions, step_viewed events, and navigationResolver behavior for maximal confidence.

## Out of Scope
- Changing analytics definitions or introducing new metrics.
- Reworking push delivery logic.
- Refactoring unrelated app networking/services.

## Definition of Done
- [x] ✅ Production‑Ready Step view counts increment when users progress through steps.
- [x] ✅ Production‑Ready Button click counts and unique clicks increment when users tap a push action.
- [x] ✅ Production‑Ready Push message fetch/parsing flows through `push_handler` (no duplicate app-layer parsing).
- [x] ✅ Production‑Ready Push-specific responsibilities are consolidated into `push_handler` with clear app-facing API.
- [x] ✅ Production‑Ready App no longer registers Firebase Messaging listeners directly; `push_handler` owns listener + initial message wiring.
- [x] ✅ Production‑Ready `PushCoordinator` no longer initializes Firebase or touches secure storage; it relies on `AuthRepository.userToken` only.
- [x] ✅ Production‑Ready `AnonymousAuthService` removed; anonymous identity handled by `AuthRepository`.
- [x] ✅ Production‑Ready `StorageKeys` removed or trimmed to repo‑owned keys (no cross-domain dumping).
- [x] ✅ Production‑Ready `PushCoordinator` removed; push init + coordination handled solely by `push_handler`.
- [x] ✅ Production‑Ready Plugin accepts `navigationResolver` and no longer requires `navigatorKey` or `routeResolver`.
- [x] ✅ Production‑Ready Background handler is top‑level inside `push_handler` and registered during repository init.
- [x] ✅ Production‑Ready FCM foreground handler no longer parses `MessageData` directly; repository handles fetch/parse.
- [x] ✅ Production‑Ready Background entrypoint rehydrates transport config and reports delivery without main isolate.
- [x] ✅ Production‑Ready Background delivery queue is flushed on app resume (foreground), not just cold start.
- [x] ✅ Production‑Ready Background queued messages are auto-presented on app resume when not expired.
- [x] ✅ Production‑Ready Foreground presentation clears queued entries (no double-present).
- [x] ✅ Production‑Ready Integration test validates resume auto-present + queue clearing behavior.
- [x] ✅ Production‑Ready Test suite covers all layouts (popup, bottomModal, snackBar, actionButton), steps, and button click reporting.

## Validation Steps
- [x] ✅ Production‑Ready Send a push with multiple steps and buttons, then verify metrics update for step views and button clicks.
- [x] ✅ Production‑Ready Trigger a push fetch and confirm it uses `push_handler` in logs or tracing.
- [x] ✅ Production‑Ready Fetching message data no longer records `opened`; presenter is the sole source for `opened`.
- [x] ✅ Production‑Ready Debug console shows action report logs with action, step_index, button_key, and idempotency_key.
- [x] ✅ Production‑Ready Confirm `PushCoordinator` no longer uses `FirebaseMessaging.onMessage*` or `getInitialMessage`.
- [x] ✅ Production‑Ready Confirm Firebase init happens in Application init flow (not in PushCoordinator) and push still works.
- [x] ✅ Production‑Ready Verify `AuthRepository` persists `userToken` + `deviceId` and no other repo reads those keys directly.
- [x] ✅ Production‑Ready Confirm no app code references `PushCoordinator`.
- [x] ✅ Production‑Ready Confirm `navigationResolver` is used for all push navigation actions without direct navigator access.
- [x] ✅ Production‑Ready Confirm background delivery report works without main.dart handler.
- [x] ✅ Production‑Ready Validate queued background deliveries are flushed after reopening the app (resume).
- [x] ✅ Production‑Ready Validate queued background messages auto-present on resume and are skipped when expired.
- [x] ✅ Production‑Ready Integration test dismisses the auto-presented UI and confirms queue flush completes.

## Decisions
- Use existing action types; do not introduce new metric categories.
- Move all push transport responsibilities into `push_handler` (message fetch, device register/unregister, action reporting, token/permission handling, background delivery reporting).
- Rely on presenter-driven `opened`; remove server-side `opened` creation in fetch controllers.

## Questions to Close
- None.

## References
- `flutter-app/lib/application/push/push_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/push_api_client.dart`
- `/home/elton/Dev/repos/flutter-packages/push_handler/lib`
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/PushMetricsService.php`
```

---

## Archived TODO: TODO-push-noop-removal.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-noop-removal.md

```markdown
# TODO (V1): Push CTA Continue-After-Action + Remove `noop`

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Flutter App, push_handler Plugin  
**Objective:** Remove the `noop` custom action and introduce an explicit `continue_after_action` flag so CTA buttons default to advancing only when no custom action is defined, while custom actions can opt into auto-advance when desired.

---

## Scope
- Add `continue_after_action` to CTA button schema (payload + DTO + value object).
- Remove `noop` handling from push_handler CTA button flow.
- Remove `noop` handling from Flutter app `PushActionDispatcher`.
- Default behavior:
  - If `custom_action` is empty → use default advance behavior.
  - If `custom_action` is set → only auto-advance when `continue_after_action=true`.
- Add/update tests for CTA behavior and new flag parsing.

## Out of Scope
- Changes to backend payload validation.
- New custom action types beyond `continue_after_action`.

---

## Tasks (Execution Checklist)

### push_handler Plugin
- [x] ✅ Production-Ready Add `continue_after_action` parsing + value object.
- [x] ✅ Production-Ready Remove `noop` branch from CTA custom action handling.
- [x] ✅ Production-Ready Enforce default advance only when `custom_action` is empty.
- [x] ✅ Production-Ready Enforce optional auto-advance when `continue_after_action=true`.
- [x] ✅ Production-Ready Add/update unit/widget tests covering the above behavior.

### Flutter App
- [x] ✅ Production-Ready Remove `noop` branch from `PushActionDispatcher`.
- [x] ✅ Production-Ready Add/update tests for dispatcher behavior if needed.

---

## Definition of Done
- [x] ✅ Production-Ready `continue_after_action` is supported in button config and wired end-to-end.
- [x] ✅ Production-Ready `noop` is no longer treated as a valid custom action.
- [x] ✅ Production-Ready Empty custom action uses default CTA advance.
- [x] ✅ Production-Ready Non-empty custom action auto-advances only when `continue_after_action=true`.
- [x] ✅ Production-Ready Tests cover the behavior change.

## Validation Steps
- Run targeted push_handler tests for CTA handling.
- Run targeted Flutter app tests (dispatcher + wiring as needed).
```

---

## Archived TODO: TODO-push-route-resolver-mapping.md
**Original Path:** foundation_documentation/todos/completed/TODO-push-route-resolver-mapping.md

```markdown
# TODO (V1): Push Route Resolver Mapping

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Prevent push action navigation failures by resolving internal routes through an explicit mapping and safe guards.

---

## Scope
- Define a push route resolver strategy that maps incoming push **route keys** (e.g., `event_detail`, `map`) to registered AutoRoute paths or handler functions.
- Guard invalid/unknown routes with a no-op + log (do not throw).
- Update `ModuleSettings` route resolver to use the mapping and pass query parameters (e.g., `itemIDString`) when required.

## Out of Scope
- Adding new application routes or screens.
- Modifying push payload contracts.
- Backend changes.

## Definition of Done
- [x] ✅ Production‑Ready Push action navigation does not throw when route is unknown.
- [x] ✅ Production‑Ready Valid push routes resolve to their correct AutoRoute path.

## Validation Steps
- [x] ✅ Production‑Ready Trigger a push with internal route buttons (`event_detail`, `map`) and confirm navigation succeeds or logs a safe warning when unmapped.

## Decisions
- Map by **route keys** from payload (`route_key`) and translate to AutoRoute paths, passing `path_parameters`.
- Prefer explicit route mapping over direct `pushPath` with raw payload values.

## Questions to Close
- None.

## References
- `flutter-app/lib/application/router/modular_app/module_settings.dart`
- `flutter-app/lib/application/router/app_router.dart`
```

---

## Archived TODO: TODO-restore-push-handler-plug-n-play.md
**Original Path:** foundation_documentation/todos/completed/TODO-restore-push-handler-plug-n-play.md

```markdown
# TODO (V1): Restore Push Handler Plug'n'Play (User Token)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter + push_handler)  
**Objective:** Reconstruct missing push_handler behavior from recovered files/logs while keeping the plugin plug'n'play and treating anonymous users as the same `user_token` flow.

---

## Scope
- Compare recovered `push_handler` files against current repository state and identify missing behavior required for plug'n'play background handling and delivery reporting.
- Ensure `push_handler` uses only `AuthRepository.userToken` (no separate anonymous token or secure storage access for auth).
- Ensure push init happens after `AuthRepository` can provide a `userToken` (anonymous or authenticated).
- Validate background entrypoint rehydrates persisted transport config and reports delivery without main isolate wiring.
- Restore or reintroduce any missing tests that validate background queue flush + auto-present behavior if they were removed.
- Add non-E2E tests to increase confidence in the anonymous token + push registration flow (repository + transport layer).

## Out of Scope
- Backend push delivery changes (Laravel package behavior, FCM credential storage).
- Telemetry event taxonomy changes or new analytics definitions.
- Refactors unrelated to push handler initialization or background delivery reporting.

## Definition of Done
- [x] ✅ Production‑Ready `push_handler` remains plug'n'play: app only provides `transportConfig`, `navigationResolver`, and background callback; no extra wiring in `main.dart`.
- [x] ✅ Production‑Ready `AuthRepository.userToken` is the sole token used for push registration (anonymous == user_token).
- [x] ✅ Production‑Ready App init guarantees `userToken` is available before push handler initialization.
- [x] ✅ Production‑Ready Background entrypoint can report delivery using persisted config, without main isolate state.
- [x] ✅ Production‑Ready Any recovered tests needed for background queue flush and auto-present are restored and passing.
- [x] ✅ Production‑Ready New tests cover anonymous token issuance + registration orchestration without E2E.

## Progress
- [x] ✅ Production‑Ready Created `PushTransportConfigurator` in Flutter app to centralize config and rely only on `AuthRepository.userToken`.
- [x] ✅ Production‑Ready Application init now uses `PushTransportConfigurator` for push handler setup.
- [x] ✅ Production‑Ready AuthRepository now issues an anonymous identity when no `userToken` is present and stores the returned token in `user_token`.
- [x] ✅ Production‑Ready Anonymous identity HTTP call wired via `LaravelAuthBackend` with mock fallback.
- [x] ✅ Production‑Ready Added unit tests covering anonymous token issuance and stored-token skip behavior.

### Provisional Notes
- None.

## Validation Steps
- [x] ✅ Production‑Ready Confirm `push_handler` init does not require navigator key or route resolver.
- [x] ✅ Production‑Ready Verify push registration uses `userToken` in logs (token length only).
- [x] ✅ Production‑Ready Receive a push in background and confirm delivery is reported when app is terminated.
- [x] ✅ Production‑Ready Resume app and verify queued background deliveries auto-present and clear from the queue.
- [x] ✅ Production‑Ready Unit/integration tests pass for new anonymous token + register flow coverage.

## Decisions
- Anonymous token is not a separate flow; it is the same `user_token`.
- App code must not read secure storage for auth tokens; push handler consumes `AuthRepository.userToken`.

## References
- Removed restored_files references after cleanup.
- `foundation_documentation/todos/active/TODO-push-metrics-step-and-clicks.md`
- `flutter-app/lib/application/application_contract.dart`
- `/home/elton/Dev/repos/flutter-packages/push_handler/lib`
```

---

## Archived TODO: TODO-v1-push-enable-endpoint.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-enable-endpoint.md

```markdown
# TODO (V1): Push Enable/Disable Endpoint

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Separate push enable/disable from settings updates to improve clarity and avoid sending full configuration payloads, while nesting push delivery policies under `push`.

---

## Scope
- Add a dedicated endpoint to enable push after validating required configuration.
- Add a dedicated endpoint to disable push without requiring full settings payload.
- Keep `/settings/push` focused on configuration (firebase + push settings only).
- Document the activation flow (configure → enable → status).
- Remove `push.types` from `/settings/push` (types are defined by message types).
- Move `max_ttl_days` to `push.max_ttl_days` and reject the top-level field.

## Out of Scope
- Push message types/routes management (handled by dedicated endpoints).
- FCM delivery pipeline changes.
- Flutter UI wiring.

## Definition of Done
- [x] ✅ `POST /api/v1/settings/push/enable` validates required config and sets `push.enabled=true`.
- [x] ✅ `POST /api/v1/settings/push/disable` sets `push.enabled=false`.
- [x] ✅ `GET /api/v1/settings/push/status` behavior unchanged.
- [x] ✅ Tests cover enable/disable flows and validation errors.
- [x] ✅ Package README documents the new endpoints and flow.
- [x] ✅ `/settings/push` no longer accepts `push.types`.
- [x] ✅ Tests updated for removal of `push.types`.
- [x] ✅ README updated to remove `push.types` from settings payloads.
- [x] ✅ `/settings/push` accepts `push.max_ttl_days` and rejects top-level `max_ttl_days`.
- [x] ✅ Tests updated for `push.max_ttl_days` (including default behavior).
- [x] ✅ README updated to show `push.max_ttl_days` in settings payloads.

## Validation Steps
- [x] ✅ Feature tests for enable/disable endpoints.
- [x] ✅ Validation tests for missing firebase config when enabling.
- [x] ✅ Feature tests for `push.max_ttl_days` validation + defaults.

## Decisions
- Enable/disable are separate endpoints (no mixing with `/settings/push` updates).
- Keep `/settings/push` for configuration only.
- Push delivery policies (TTL) live under the `push` object.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/`
- `foundation_documentation/system_roadmap.md`
```

---

## Archived TODO: TODO-v1-push-frontend.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-frontend.md

```markdown
# TODO (V1): Push Notifications (Frontend)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Finalize remaining push UX and routing gaps for V1.

---

## Scope
- Push tap routing for invite payloads.
- Silent invite list update driven by push payload (no extra backend refresh).
- Add a push listener stream in `push_handler` that emits fetched payload data.
- Add a reusable push-aware repository hook for payload-driven updates.
- Push validation steps (tap handling, registration checks).
- Add a push_handler presentation gate/mode to defer UI until app readiness.
- Ensure init stack shows push above InviteFlow/Home without flashing.
- Add race-condition debug logs in app init stack + push_handler presenter.
- Add push_handler de-duplication to avoid replaying queued pushes.
- Ensure only the latest queued push is presented.
- Clear the queue when opening the app via push tap.
- Inline selector steps scroll to avoid overflow on smaller screens.
- External selector custom actions always open the selector; gate blocks only continue.
- Last-step close (X) respects SafeArea (no overlap with system bars).
- Move the "voltar" button to the top bar on the same line as "Pular" (remove bottom back).
- Match the default "Continuar" button bottom spacing to action buttons for consistency.

## Out of Scope
- Telemetry identity stitching and Mixpanel delivery guarantees.
- Backend push fan-out or payload schema changes.

## Definition of Done
- [x] ✅ Production‑Ready Invite push tap routes into invite flow.
- [x] ✅ Production‑Ready Push tap does not duplicate routes when already on target event.
- [x] ✅ Production‑Ready Invite accept UX handles offline attempt (enqueue + toast + reconcile).
- [x] ✅ Production‑Ready Invite accept remains non-optimistic (events fire after success response).
- [x] ✅ Production‑Ready No extra “processing” state introduced for invite flows.
- [x] ✅ Production‑Ready Silent invite list update uses payload data (no list refetch).
- [x] ✅ Production‑Ready `push_handler` emits raw push data merged with fetched payload data (payload replaces `data`).
- [x] ✅ Production‑Ready Push-aware repository hook is reusable and adopted by invites repo.
- [ ] ⚪ Pending Push validation steps completed.
- [x] ✅ Production‑Ready README documents the manual push validation checklist.
- [x] ✅ Production‑Ready push_handler supports presentation gate/mode while keeping plug'n'play default behavior.
- [x] ✅ Production‑Ready Push display defers until init stack is set (push above InviteFlow/Home).
- [x] ✅ Production‑Ready Debug logs confirm push presentation waits for init stack.
- [x] ✅ Production‑Ready Push presentation de-duplicates per push_message_id and clears queue.
- [x] ✅ Production‑Ready Background queue keeps only the latest push payload.
- [x] ✅ Production‑Ready Queue is cleared when app opens via push tap.
- [ ] ⚪ Pending Inline selector steps scroll without overflow on small screens.
- [ ] ⚪ Pending External selector custom action opens regardless of gate; gate only blocks continue.
- [ ] ⚪ Pending Close (X) button respects SafeArea on the last step.
- [ ] ⚪ Pending "Voltar" appears in the top bar alongside "Pular" (no bottom back).
- [ ] ⚪ Pending Default "Continuar" bottom spacing matches action buttons.
 - [x] ✅ Production‑Ready Push validation steps completed.
 - [x] ✅ Production‑Ready Inline selector steps scroll without overflow on small screens.
 - [x] ✅ Production‑Ready External selector custom action opens regardless of gate; gate only blocks continue.
 - [x] ✅ Production‑Ready Close (X) button respects SafeArea on the last step.
 - [x] ✅ Production‑Ready "Voltar" appears in the top bar alongside "Pular" (no bottom back).
 - [x] ✅ Production‑Ready Default "Continuar" bottom spacing matches action buttons.

## Validation Steps
- [x] ✅ Production‑Ready Smoke test: receive a push notification and confirm tap handling resolves the correct in-app surface.
- [x] ✅ Production‑Ready Verify logs show Firebase init, token acquisition, and `/api/v1/push/register` success.
- [x] ✅ Production‑Ready App registers push device with anonymous token when user is not logged in.
- [x] ✅ Production‑Ready Push registration payload uses backend-supported platform values.
- [x] ✅ Production‑Ready Silent invite update: a new invite payload updates the invite list without hitting the backend.
- [x] ✅ Production‑Ready Cold start push: push screen does not flash before Home/InviteFlow.
- [x] ✅ Production‑Ready Logs show push presentation gated until init stack is ready.
- [x] ✅ Production‑Ready Cold start does not re-present previously queued push.
- [x] ✅ Production‑Ready Multiple pushes deliver only the latest presentation.
- [ ] ⚪ Pending Manual: inline selector scrolls on small screens (no RenderFlex overflow).
- [ ] ⚪ Pending Manual: external selector opens and continues only after gate satisfied.
- [ ] ⚪ Pending Manual: close (X) is visible and tappable below the system bar.
- [ ] ⚪ Pending Manual: "voltar" appears in the top bar and the bottom back button is gone.
- [ ] ⚪ Pending Manual: "Continuar" has the same bottom spacing as action buttons.
 - [x] ✅ Production‑Ready Manual: inline selector scrolls on small screens (no RenderFlex overflow).
 - [x] ✅ Production‑Ready Manual: external selector opens and continues only after gate satisfied.
 - [x] ✅ Production‑Ready Manual: close (X) is visible and tappable below the system bar.
 - [x] ✅ Production‑Ready Manual: "voltar" appears in the top bar and the bottom back button is gone.
 - [x] ✅ Production‑Ready Manual: "Continuar" has the same bottom spacing as action buttons.
- [x] ✅ Production‑Ready Run `fvm flutter test` in push_handler package.
- [x] ✅ Production‑Ready Run `fvm flutter analyze` in push_handler package.
- [x] ✅ Production‑Ready Run `fvm flutter test` in flutter-app.

## Decisions
- Keep invite acceptance non-optimistic; emit events only after success.
- Use enqueue + toast for offline invite acceptance.
- Invite push routing uses `invite={{invite_id}}` query param; open invite flow and surface the invite at top of the stack.
- If invite not found or expired, ignore and show the stack normally.
- Do not show toast on push receipt; rely on push handler settings for UI.
- Use the existing in-app retry queue for network instability (no new queue system).
- Push stream emits the raw push payload merged with fetched payload data (replace `data` with fetched payload).
- Payload-driven invite updates accept `invites` (array) or `invite` (single) using `InviteDto` field names; new/updated invites are upserted by `id` and placed at the top of the list.
```

---

## Archived TODO: TODO-v1-push-html-body-safety-and-keyboard.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-html-body-safety-and-keyboard.md

```markdown
# TODO (V1): Push Step Body Alignment + Safe HTML + Keyboard Responsiveness

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** push_handler Plugin, Laravel App  
**Objective:** Center non‑HTML step bodies, formalize a safe HTML subset for step bodies, require at least one of title/body/image per step, and make text questions responsive to the keyboard (fullScreen + popup).

---

## Scope
- **Contract:** For each step, require at least one of `title`, `body`, or `image`.
- **HTML (steps.*.body only):** Treat `body` as HTML when tags are present; backend strips to a safe subset and returns the stripped version (immediate feedback on submit). No new fields.
- **UI alignment:** Center **non‑HTML** step bodies; HTML keeps its own alignment.
- **Keyboard UX:** Ensure text questions remain visible with the keyboard open on both fullScreen and popup layouts (scroll + insets).
- **Docs:** Update foundation docs + push_handler README(s) to document HTML subset and the per‑step content rule.

## Out of Scope
- Full HTML/CSS support.
- New `body_html` field or alternate payload fields.
- Changes to message‑level (non‑step) body rendering.

---

## Decisions
- HTML is supported via `steps.*.body` only (auto‑detect by tag presence).
- Backend strips HTML to a whitelist before persistence/response.
- HTML whitelist: `p`, `br`, `strong`, `em`, `u`, `span` (style: `color`, `font-size`, `font-weight`), `ul`, `ol`, `li`, `img` (`src`, `width`, `height`, `alt`).
- Centering applies only to **non‑HTML** bodies.

---

## Tasks (Execution Checklist)

### Documentation (before code)
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/onboarding_flow_module.md` with HTML subset and per‑step content requirement.
- [x] ✅ Production‑Ready Update push_handler README(s) to document the safe HTML subset and step content rule.

### Laravel App
- [x] ✅ Production‑Ready Enforce “at least one of title/body/image per step” in request validation.
- [x] ✅ Production‑Ready Add backend sanitizer that strips step body HTML to the allowed subset.
- [x] ✅ Production‑Ready Add/adjust tests to cover validation + sanitization.

### push_handler Plugin
- [x] ✅ Production‑Ready Center non‑HTML step bodies in `PushStepBody` (Markdown/plain text).
- [x] ✅ Production‑Ready Render HTML using the allowed tag list (align with backend whitelist).
- [x] ✅ Production‑Ready Hide title/body rows when empty to support image‑only steps.
- [x] ✅ Production‑Ready Make text question layouts keyboard‑responsive in fullScreen + popup flows.

---

## Definition of Done
- [x] ✅ Production‑Ready Steps can omit title or body as long as at least one of title/body/image is present.
- [x] ✅ Production‑Ready HTML body is sanitized on backend and rendered safely with a whitelist.
- [x] ✅ Production‑Ready Non‑HTML step body text is centered.
- [x] ✅ Production‑Ready Text question remains visible and usable with the keyboard open.
- [x] ✅ Production‑Ready Docs updated to match the contract.

---

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter analyze` in push_handler repo.
- [x] ✅ Production‑Ready Run `fvm flutter test` in push_handler repo.
- [x] ✅ Production‑Ready Run `docker compose exec -T app php artisan test` (Laravel).
```

---

## Archived TODO: TODO-v1-push-message-type-routes-endpoints.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-message-type-routes-endpoints.md

```markdown
# TODO (V1): Push Message Types + Routes Endpoints

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Establish dedicated endpoints for `push_message_types` and `push_message_routes` to improve invite push resolution and authoring UX.

---

## Scope
- Define independent endpoints for managing `push_message_types` and `push_message_routes`.
- Enforce the same permissions as tenant push settings for these endpoints.
- Add association support so a `push_message_type` can expose an allowed subset of `push_message_routes`.
- Update push message creation flow to use a type’s available routes (not the global route list).
- Ensure data contracts align with existing tenant settings schema definitions.
- Remove `push_message_types` and `push_message_routes` from `/settings/push`; require the new endpoints.
- Ensure `route_types` and `message_types` accept arrays of objects for bulk updates (not single-object payloads).
- Confirm `query_params` accepts an array of strings as provided by the client.
- Switch `route_types`/`message_types` endpoints to accept raw array bodies (no root key).
- Use DELETE for removals via `{ "keys": ["..."] }`.

## Out of Scope
- Flutter UI implementation details beyond consuming the new endpoints.
- New push message delivery logic or changes to FCM client behavior.
- Changes to invite business rules outside push routing/resolution.

## Definition of Done
- [x] ✅ Endpoints defined and documented for `push_message_types`.
- [x] ✅ Endpoints defined and documented for `push_message_routes`.
- [x] ✅ Permissions match tenant push settings abilities.
- [x] ✅ `push_message_type` supports allowed routes list and validation.
- [x] ✅ Push message authoring uses type-scoped routes.
- [x] ✅ PATCH endpoints merge by `key` (upsert) without deleting existing entries.
- [x] ✅ Delete-by-key soft-deactivates routes/types and excludes inactive entries from creation (DELETE with `keys`).
- [x] ✅ Tests cover auth, validation, and route filtering behavior.
- [x] ✅ `/settings/push` no longer accepts `push_message_types` or `push_message_routes`.
- [x] ✅ Tests cover rejection of route/type fields on `/settings/push`.
- [x] ✅ Bulk payloads for route/message types accepted as arrays of objects.
- [x] ✅ Provide sample request body for the provided `push_message_routes` and `push_message_types`.

## Validation Steps
- [x] ✅ Feature tests for CRUD of types/routes.
- [x] ✅ Auth tests (401/403) mirror tenant push settings.
- [x] ✅ Validation tests for route association (unknown route keys rejected).
- [x] ✅ Type-scoped route list returned correctly.

## Decisions
- Endpoints live under the existing tenant push settings namespace:
  - `/api/v1/settings/push/route_types`
  - `/api/v1/settings/push/message_types`
- HTTP verbs: `GET` and `PATCH` only.
- Use embedded arrays in tenant settings with validation (no embedMany).
- Enforce unique `key` values within `push_message_types` and `push_message_routes`.
- Enforce uniqueness via application-layer validation (no DB unique index).
- Association field name: `allowed_route_keys`.
- No ordering/grouping; key-based lists only.
- PATCH for route/message types performs keyed upsert (merge by `key`); no deletions.
- Delete behavior: soft delete by setting `active=false` on routes/types; creation uses only active entries.
- `/settings/push` no longer supports route/type fields; use the dedicated endpoints only.
- Payload shape: raw array of objects; no root key.
- Deletion uses DELETE endpoints with `{ "keys": ["..."] }`; PATCH is upsert-only.

## Questions to Close
- None.

## References
- `foundation_documentation/todos/completed/TODO-v1-telemetry-and-push-backend.md`
- `foundation_documentation/system_roadmap.md`
- `laravel-app/packages/belluga/belluga_push_handler/`
```

---

## Archived TODO: TODO-v1-push-onboarding-callback-answers.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-onboarding-callback-answers.md

```markdown
# TODO (V1): Push Onboarding Callback Answers (No Push-Flow Storage)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Flutter App, push_handler Plugin  
**Objective:** Remove any push-flow answer storage (including in-memory stores) and rely solely on app callbacks for answer persistence and gate evaluation.

---

## Scope
- Remove in-app push answer stores from the push flow (no disk, no in-memory).
- Gatekeeper must use an app-provided callback/resolver to evaluate selection gates.
- Action dispatcher must only relay answers via callbacks (no storage).
- External selector initial selection must rely on `OptionItem.isSelected` from callbacks/optionsBuilder.
- Update tests to validate callback-driven gating and removal of push-flow storage.
- Update onboarding flow docs to reflect callback-only persistence and gate evaluation.

## Out of Scope
- Backend changes.
- Telemetry/Mixpanel changes.
- Payload schema changes beyond `OptionItem.isSelected`.

---

## Target Behavior (Contracts)
- The push flow stores nothing (no persistence helpers, no local caches for answers).
- The app decides persistence in its callback implementations.
- Gate checks during a push session use a callback to resolve current selection state.
- Pre-selected options come from `OptionItem.isSelected` only.

---

## Tasks (Execution Checklist)

### push_handler Plugin
- [x] ✅ Production‑Ready Keep `OptionItem.isSelected` support for inline option preselection.
- [x] ✅ Production‑Ready Ensure no persistence logic exists in plugin (callbacks only).

### Flutter App
- [x] ✅ Production‑Ready Remove `PushAnswerStore` (and any usage) from push flow.
- [x] ✅ Production‑Ready Remove `PushAnswerHandler` usage if it implies internal persistence; use callback injection only.
- [x] ✅ Production‑Ready Update gatekeeper to accept a callback/resolver for current selections.
- [x] ✅ Production‑Ready Update action dispatcher to invoke callback on selection submit (no storage).
- [x] ✅ Production‑Ready Ensure external selector uses `OptionItem.isSelected` for initial state.
- [x] ✅ Production‑Ready Register a callback-driven answer resolver (app-side relay) for gate checks.

### Tests
- [x] ✅ Production‑Ready Remove tests that validate push-flow storage.
- [x] ✅ Production‑Ready Add tests for callback-driven gate evaluation (selection_min via resolver).

### Documentation
- [x] ✅ Production‑Ready Update onboarding flow module documentation to remove any mention of in-memory answer stores.
- [x] ✅ Production‑Ready Note this correction in completed TODOs if needed.

---

## Definition of Done
- [x] ✅ Production‑Ready No push-flow answer storage exists (disk or memory).
- [x] ✅ Production‑Ready Gate checks rely on callbacks only.
- [x] ✅ Production‑Ready Action dispatcher relays answers without persistence.
- [x] ✅ Production‑Ready Tests updated and passing in app + plugin.
- [x] ✅ Production‑Ready Documentation updated to reflect callback-only flow.

---

## Validation Steps
- [x] ✅ Production‑Ready Run `fvm flutter test` for updated app tests.
- [x] ✅ Production‑Ready Run `fvm flutter test` for updated push_handler tests.
- [x] ✅ Production‑Ready Manually validate external selector flow with preselected options returned by callbacks.
```

---

## Archived TODO: TODO-v1-push-onboarding-dynamic-steps.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-onboarding-dynamic-steps.md

```markdown
# TODO (V1): Push Onboarding Dynamic Steps (Backend + App + Plugin)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend (Laravel), Flutter App, push_handler Plugin  
**Objective:** Deliver a generic, push-driven onboarding flow that can be changed via backend payloads without app code changes, while keeping push_handler agnostic.

---

## Scope
- Extend the push payload schema to support dynamic onboarding steps with:
  - stable `slug` per step (no index-based IDs)
- message-level `closeOnLastStepAction` (honored on last step only; default false)
- step-level `dismissible` to control whether "pular" is allowed on each step (replaces message-level allowDismiss)
  - `gate` for step advancement (generic gatekeeper callback)
  - `onSubmit` for step answer persistence (generic app-side handler)
  - `selector` step type with dynamic `option_source` and layout variants
- Implement backend schema validation + storage + examples in Laravel push message endpoints.
- Add a delivery-scoped `message_instance_id` (UUID/ULID) generated per send request (not per message model).
- Implement app-side rendering and option resolution in Flutter.
- Implement push_handler UI hooks (generic) without domain knowledge (no favorites logic in plugin).
- Add app-side push action dispatcher to execute `custom_action` handlers (permissions, settings, etc.).
- Wire `custom_action` handling into push presentation flow so gate steps can trigger permission prompts.
- Add a manual test push payload that exercises two gates: one dismissible (friends list) and one non‑dismissible (geolocation).
- Adjust push onboarding UI layout:
  - Remove top back button.
  - Add bottom-left back button labeled "voltar".
  - Move "pular" to top-right; it must skip only the current step (when allowed).
  - Remove global/push-level skip triggered by "pular".
  - Remove bottom "pular" and the bottom-right ">" button.
  - Add default "Continuar" button below content when no other CTA/button exists.
- Use Theme.of(context) styles in push_handler UI (no hard-coded colors or typography).
- Gate pre-render check: before rendering any step with a gate, run gatekeeper and skip the step immediately if already satisfied (avoid visible blink).
- Back navigation must skip already-satisfied gates (prevent looping when returning to previous steps).
- Option source resolver uses `method` to return `OptionItem` (or child options); methods may return static data for testing until backend wiring is ready.

## Out of Scope
- Telemetry/Mixpanel changes.
- Real-time experiments platform or analytics storage in backend (only use step/action metrics already tracked).
- Web app changes.

---

## Target Payload Schema (Push Message Payload Template)

### High-level
```
payload_template: {
  layoutType: "fullScreen" | "popup" | "bottomModal" | "snackBar" | "actionButton",
  closeOnLastStepAction: true | false,
  steps: [ ...step objects... ]
}
```

### Step object (generic, agnostic)
```
{
  "slug": "string",                  // stable identifier
  "type": "copy" | "cta" | "question" | "selector",
  "title": "string",
  "body": "string | html | markdown", // allow sanitized HTML or Markdown (images supported)
  "image": { "path": "url", "width": 0, "height": 0 } | null,
  "buttons": [ ...button objects... ],
  "dismissible": true|false,          // controls "pular" for this step
  "gate": { "type": "string", "onFail": {...} } | null,
  "onSubmit": { "action": "string", "store_key": "string" } | null,
  "config": { ...type-specific config... }
}
```

### Button object (generic)
```
{
  "label": "string",
  "continue_after_action": true|false,
  "action": {
    "type": "route" | "external" | "custom",
    "route_key": "string",
    "path_parameters": { "key": "value" },
    "query_parameters": { "key": "value" },
    "url": "https://...",
    "custom_action": "string"         // interpreted by app
  },
  "show_loading": true|false,
  "color": "#RRGGBB"
}
```

### Question step config (generic)
```
{
  "question_type": "text",
  "option_source": {
    "type": "method",
    "name": "getFavorites" | "getTags" | "getMapPois",
    "params": { ... },
    "cache_ttl_sec": 3600
  },
  "options": [ { "id": "string", "label": "string", "image": "url" } ], // optional static fallback
  "min_selected": 1,
  "max_selected": 3,
  "layout": "row" | "grid" | "list" | "tags",
  "grid_columns": 2,
  "store_key": "preferences.tags"
}
```

### Selector step config (generic)
```
{
  "selection_mode": "single" | "multi",
  "option_source": { ...same as question... },
  "min_selected": 0,
  "max_selected": 0,
  "layout": "row" | "grid" | "list" | "tags",
  "grid_columns": 2,
  "store_key": "favorites.items"
}
```

### Gate (generic)
```
{
  "type": "string",        // e.g. "notifications_permission"
  "onFail": {
    "toast": "string",
    "fallback_step": "slug"
  }
}
```

### OnSubmit (generic)
```
{ "action": "save_response", "store_key": "preferences.tags" }
```
Answer payload (app-side, not sent by backend):
```
{
  "step_slug": "string",
  "value": "string | number | bool | list",
  "metadata": { ... }
}
```

---

## Telemetry (Plugin Event Emission)

### Goal
- `push_handler` emits structured events without tracking logic.
- `event_tracker_handler` subscribes (or not) and maps to analytics backends.

### Event Emitter Contract (push_handler)
- Provide one mechanism (choose one):
  - `Stream<PushEvent> onPushEvent`, or
  - `void Function(PushEvent event)? onPushEvent`
- Emission must be synchronous with UI actions when possible.

### Event Payload (PushEvent)
- `type`: `delivered | opened | step_viewed | button_tap | dismissed | submit | gate_blocked | error`
- `push_id`: message identifier
- `message_instance_id`: unique per delivery/send (nullable if not provided)
- `step_slug`: current step slug (nullable)
- `step_type`: `copy | cta | question | selector` (nullable)
- `button_key`: if action came from a button
- `action_type`: `route | external | custom` (nullable)
- `route_key`: if action is `route`
- `timestamp`: ISO8601 UTC
- `app_state`: `foreground | background`
- `source`: `notification_tap | background_delivery | in_app`
- `metadata`: freeform map (app can extend)

### Emission Points
- On delivery enqueue: `delivered`
- On open/present: `opened`
- On step render: `step_viewed`
- On button tap: `button_tap`
- On close/dismiss: `dismissed`
- On submit confirmation: `submit`
- On gate fail: `gate_blocked`
- On internal errors: `error`

### event_tracker_handler Subscription
- The app registers a listener and forwards events to tracking backends.
- Mapping is external to `push_handler` and must not add domain logic in plugin.

---

## Display Pipeline Integration Tests (Flutter + Plugin)

### Goal
Validate the end‑to‑end display pipeline: **push ID → fetch payload → render steps** without relying on real FCM delivery.

### Test Hook (Debug/Test Only)
- Provide a test‑only injection entrypoint:
  - `PushHandler.debugInjectMessageId(String messageId)` or similar.
- The hook must follow the **real** code path used by production:
  - call the transport client to fetch payload (or a mock HTTP server with fixtures)
  - render the push content UI with the fetched payload

### Test Fixtures (Payload Variants)
- **Copy Step**
  - `type: copy`, `body` as Markdown with image
- **CTA Step**
  - `type: cta`, `dismissible: true` and `dismissible: false`
  - `closeOnLastStepAction: true` and `closeOnLastStepAction: false`
- **Question Step**
  - `question_type: text`
- **Selector Step**
  - `layout: list`, `layout: tags`
  - `selection_mode: single` and `selection_mode: multi`
  - dynamic `option_source` (mocked by `optionsBuilder`)
- **Gate Step**
  - gate with fail toast and fallback step

### Integration Assertions
- Renders correct step `slug` and title/body.
- Buttons appear with correct labels and close behavior.
- Gate blocks progress until gatekeeper returns true; re‑evaluates on resume.
- `onStepSubmit` called with expected `AnswerPayload` shape for each step type.
- Telemetry events emitted for each interaction (`opened`, `step_viewed`, `button_tap`, `submit`, `dismissed`).

### Manual Test Payload (Two Gates)
Use this payload to validate two gates (location required, contacts dismissible) plus selectors.
```
{
  "internal_name": "boora_onboarding_dynamic_2026_01_08_manual",
  "title_template": "Bóora! Bem-vindo",
  "body_template": "Vamos personalizar sua experiência.",
  "type": "transactional",
  "active": true,
  "audience": { "type": "all" },
  "delivery": {
    "scheduled_at": null
  },
  "delivery_deadline_at": "2026-02-08T12:00:00Z",
  "payload_template": {
    "layoutType": "fullScreen",
    "closeOnLastStepAction": true,
    "title": "Bem-vindo ao Bóora",
    "body": "Responda algumas etapas rápidas para personalizar seu app.",
    "image": {
      "path": "https://guarappari.com.br/assets/push/hero.png",
      "width": 720,
      "height": 480
    },
    "steps": [
      {
        "slug": "intro",
        "type": "cta",
        "title": "Começar",
        "body": "Vamos configurar suas preferências.",
        "dismissible": false
      },
      {
        "slug": "gate_location",
        "type": "cta",
        "title": "Ative sua localização",
        "body": "Precisamos da sua localização para mostrar o mapa perto de você.",
        "dismissible": false,
        "gate": {
          "type": "location_permission",
          "onFail": { "toast": "Ative a localização para continuar." }
        },
        "buttons": [
          {
            "label": "Permitir localização",
            "action": { "type": "custom", "custom_action": "request_location_permission" },
            "show_loading": true
          }
        ]
      },
      {
        "slug": "gate_friends",
        "type": "cta",
        "title": "Convide amigos",
        "body": "Ative a permissão de contatos para sugerirmos amigos.",
        "dismissible": true,
        "gate": {
          "type": "contacts_permission",
          "onFail": { "toast": "Sem permissão, você pode continuar." }
        },
        "buttons": [
          {
            "label": "Permitir contatos",
            "action": { "type": "custom", "custom_action": "request_contacts_permission" },
            "show_loading": true
          }
        ]
      },
      {
        "slug": "favorites_selector",
        "type": "selector",
        "title": "Escolha seus favoritos",
        "body": "Selecione pelo menos 3 favoritos para personalizar seu app.",
        "dismissible": false,
        "config": {
          "min_selected": 3,
          "max_selected": 8,
          "layout": "grid",
          "grid_columns": 2,
          "option_source": {
            "type": "method",
            "name": "getFavorites"
          }
        },
        "gate": {
          "type": "favorites_min_selected",
          "min_selected": 3,
          "onFail": { "toast": "Selecione pelo menos 3 favoritos." }
        },
        "buttons": [
          {
            "label": "Escolher favoritos",
            "action": { "type": "custom", "custom_action": "open_favorites_selector" },
            "show_loading": true
          }
        ]
      },
      {
        "slug": "map_poi_selector",
        "type": "selector",
        "title": "O que voce procura?",
        "body": "Selecione os tipos de lugares que deseja ver no mapa.",
        "dismissible": true,
        "config": {
          "min_selected": 1,
          "max_selected": 6,
          "layout": "tags",
          "option_source": {
            "type": "method",
            "name": "getTags",
            "params": {
              "include": ["praias", "restaurantes", "experiencias_no_mar", "trilhas"]
            }
          }
        },
        "gate": {
          "type": "selection_min",
          "min_selected": 1,
          "onFail": { "toast": "Selecione ao menos 1 tipo." }
        }
      },
      {
        "slug": "finish",
        "type": "cta",
        "title": "Tudo pronto",
        "body": "Voce ja pode explorar o app.",
        "dismissible": false,
        "buttons": [
          {
            "label": "Abrir mapa",
            "action": {
              "type": "route",
              "route_key": "map",
              "path_parameters": {}
            }
          }
        ]
      }
    ]
  }
}
```

---

## Laravel (Backend) Implementation

### Data Model + Validation
- `payload_template.steps[*].slug` is required, string, max 64, unique within the steps array. No fallback.
- `payload_template.steps[*].type` required and must be one of: `copy`, `cta`, `question`, `selector`.
- `payload_template.closeOnLastStepAction` optional boolean (applies only to last-step actions).
- `payload_template.title` optional string (step container title).
- `payload_template.body` optional string (step container body).
- `payload_template.image` optional object with:
  - `image.path` required string/URL if present
  - `image.width|image.height` numeric (optional)
- `payload_template.steps[*].dismissible` optional boolean (controls step-level skip).
- `payload_template.steps[*].gate` optional object with:
  - `type` required string if present
  - `onFail.toast` optional string
  - `onFail.fallback_step` optional string (must match a step slug if provided)
  - `min_selected` optional non-negative integer (selection gates)
- `payload_template.steps[*].onSubmit` optional object with:
  - `action` required string if present (e.g., `save_response`)
  - `store_key` required string if present
- `payload_template.steps[*].buttons` optional array with:
  - `label` required string
  - `action.type` required `route|external|custom`
  - `action.route_key|path_parameters|query_parameters` required when `route`
  - `action.url` required when `external`
  - `action.custom_action` required when `custom`
  - `show_loading` optional boolean
  - `continue_after_action` optional boolean
- `payload_template.steps[*].config` optional object validated by `type`:
  - `question`: validate `question_type=text` + `validator` + `store_key` (no options/min/max for text-only questions)
  - `selector`: validate `option_source.type == method` + `name` (or `options` fallback), `min_selected`, `max_selected`, `layout`, `grid_columns`, `store_key`
- `payload_template.steps[*].config.selection_ui` required when `type=selector` (`inline|external`).
- `payload_template.steps[*].config.validator` optional for `question`:
  - string name OR `{ name: string, params?: array }`.
- `payload_template.steps[*].config.options` item shape validation (if present):
  - `options.*.id` required string
  - `options.*.label` required string
  - `options.*.image` optional string/URL
- `payload_template.steps[*].image` shape validation (if present):
  - `image.path` string/URL
  - `image.width|image.height` numeric (optional)
- Ensure `min_selected <= max_selected` when both provided.
- Ensure `grid_columns` only when `layout=grid`.
- Simplify validation:
  - Remove legacy `option_source` type variants (`static`, `endpoint`, `tags`, `query`).
  - Require `option_source.type == method` and `option_source.name`.
  - Allow `options` array only as static fallback (no validation of remote URLs or queries).

### Response Shape
- `/push/messages/{id}` and `/push/messages/{id}/data` must return the new step objects (slug-based) as stored.
- No changes to existing push handler response wrapper (`ok`, `payload`).

### Documentation
- Update `laravel-app/packages/belluga/belluga_push_handler/README.md` with:
  - new step schema
  - examples for question and selector
  - note that `gate` and `onSubmit` are app-handled

---

## Tasks (Execution Checklist)

### Backend (Laravel)
- [x] ✅ Production‑Ready Add validation rules for `payload_template.steps[*]` with required `slug`, `type`, `config` by type, and `gate`/`onSubmit` objects.
- [x] ✅ Production‑Ready Reject payloads without `slug` (no fallback).
- [x] ✅ Production‑Ready Validate `gate.onFail.fallback_step` matches an existing slug.
- [x] ✅ Production‑Ready Validate `question/selector` configs (layout, grid_columns, min/max, option_source).
- [x] ✅ Production‑Ready Validate `payload_template.steps[*].buttons` (label, action.type, route/external/custom requirements, show_loading).
- [x] ✅ Production‑Ready Update push message README examples to include onboarding steps and selector samples (selector steps with `selection_ui` + `selection_mode`, questions text-only).
- [x] ✅ Production‑Ready Update validation to require `option_source.type == method` + `name`, and allow `options` fallback only.
- [x] ✅ Production‑Ready Update README/examples to show method-based `option_source` (no query/tags/endpoint).
- [x] ✅ Production‑Ready Validate `selection_ui` for selectors (`inline|external`) and require it for selector steps.
- [x] ✅ Production‑Ready Validate `question` config `validator` (string or `{name, params}` object) for text-only questions.
- [x] ✅ Production‑Ready Validate `config.options` item shape (`id`, `label`, optional `image`).
- [x] ✅ Production‑Ready Validate `steps[*].image` shape (`path`, optional `width/height`).
- [x] ✅ Production‑Ready Apply route/key/path/query validation for `steps[*].buttons[*]` (same as message-level buttons).
- [x] ✅ Production‑Ready Validate `selection_mode` for selector steps (`single|multi`) and reject it for question steps.
- [x] ✅ Production‑Ready Enforce `min_selected`/`max_selected` only when selector `selection_mode=multi` (reject on `single`).
- [x] ✅ Production‑Ready Reject non-text `question_type` values (`single_select`, `multi_select`).
- [x] ✅ Production‑Ready Validate `payload_template.title`/`body`/`image` top-level display fields.
- [x] ✅ Production‑Ready Validate `steps[*].gate.min_selected` (non-negative integer).
- [x] ✅ Production‑Ready Validate `steps[*].buttons[*].continue_after_action` (boolean).
- [x] ✅ Production‑Ready Generate a `message_instance_id` per send (UUID/ULID), include it in payload meta, and persist in `push_delivery_logs`.
- [x] ✅ Production‑Ready Add message-level optional deadline field (e.g., `delivery_deadline_at`) to push message schema/model.
- [x] ✅ Production‑Ready Validate `delivery_deadline_at` when present (datetime, not in the past).
- [x] ✅ Production‑Ready Define per-delivery TTL policy by message type (transactional vs promotional) and expose defaults in config.
- [x] ✅ Production‑Ready Compute `expires_at` at send time as `min(delivery_deadline_at, now + ttl)`; if no deadline, use `now + ttl`.
- [x] ✅ Production‑Ready Enforce FCM max TTL (<= 28 days) on computed `expires_at` and return a clear validation error when exceeded.
- [x] ✅ Production‑Ready Update delivery pipeline to use computed `expires_at` (not model field) and persist it to `push_delivery_logs`.
- [x] ✅ Production‑Ready Add tests for TTL computation (deadline cap, no deadline, FCM max).
- [x] ✅ Production‑Ready Update README with TTL policy and `delivery_deadline_at` semantics.

**Provisional Notes (Backend TTL):**
- None.

### push_handler Plugin
- [x] ✅ Production‑Ready Extend step model to require `slug` and include `dismissible`, `gate`, `onSubmit`, `config`.
- [x] ✅ Production‑Ready Add `AnswerPayload` class and pass to `onStepSubmit`.
- [x] ✅ Production‑Ready Implement `gatekeeper` callback + recheck on app resume after actions.
- [x] ✅ Production‑Ready Implement `optionsBuilder` callback + `OptionItem` (label optional if custom widget provided).
- [x] ✅ Production‑Ready Enforce `min_selected`/`max_selected` across question/selector UI.
- [x] ✅ Production‑Ready Add telemetry emitter (`PushEvent`) and emit at defined points.
- [x] ✅ Production‑Ready Add debug/test hook: inject message ID and follow normal fetch/render pipeline.
- [x] ✅ Production‑Ready Update plugin README + CHANGELOG + version bump when complete.
- [x] ✅ Production‑Ready Use `message_instance_id` for dedupe keys (fallback to message id) and include it in emitted events.
- [x] ✅ Production‑Ready Remove top back button from push UI.
- [x] ✅ Production‑Ready Add bottom-left back button labeled "voltar".
- [x] ✅ Production‑Ready Device back button maps to "voltar"; on first step do nothing.
- [x] ✅ Production‑Ready Move "pular" to top-right and wire it to step-only skip.
- [x] ✅ Production‑Ready Remove global skip behavior (no full push dismissal on "pular").
- [x] ✅ Production‑Ready Remove bottom "pular" and bottom-right ">" button.
- [x] ✅ Production‑Ready Add default "Continuar" below content when no CTA exists.
- [x] ✅ Production‑Ready Replace custom colors/styles with Theme-derived styles across push UI.
- [x] ✅ Production‑Ready Unify CTA placement: move question/selector default “Continuar” into the bottom action area (align with CTA steps) and remove inline CTA placement within step content.
- [x] ✅ Production‑Ready Update “voltar” UI: increase size slightly; position below action buttons; add 16px inner padding from left/bottom; respect SafeArea while keeping button aligned with action area.
- [x] ✅ Production‑Ready Ensure `voltar` stays below action buttons (even when no actions) and respects 16px inner padding from left/bottom within SafeArea.
- [x] ✅ Production‑Ready Add selector `layout=list` rendering + scroll cap for long tag/list option sets.
- [x] ✅ Production‑Ready Enforce `selection_mode` in plugin for selector steps only and apply min/max only for multi.
  - Update `StepConfig` to expose `selection_mode`.
  - Update `PushStepQuestionContent` to treat non-text questions as unsupported (do not render options).
  - Add widget tests for selector single vs multi selection behavior in `push_step_selector_content_test.dart`.
- [x] ✅ Production‑Ready Question validation must control CTA enablement (button disabled until validator passes for text/single/multi).
- [x] ✅ Production‑Ready Inline selector must enable CTA only when selection passes min/max (no auto-advance with invalid selection).
- [x] ✅ Production‑Ready Single-select question must require one selection before enabling CTA (no implicit pass; questions now text-only).
- [x] ✅ Production‑Ready Selector with `selection_ui=external` must not render inline options (external sheet only).
- [x] ✅ Production‑Ready Remove app-specific gate aliases (e.g., `favorites_min_selected`) from plugin gate logic; keep gate handling fully generic.
- [x] ✅ Production‑Ready For inline selectors, skip gate auto-advance based on selection constraints (e.g., `min_selected > 0`) instead of gate type names.
- [x] ✅ Production‑Ready For gated steps, custom actions always re-check the gate and advance when satisfied (ignore `continue_after_action`).

### Flutter App
- [x] ✅ Production‑Ready Implement gatekeeper mapping for `gate.type` values.
- [x] ✅ Production‑Ready Handle contacts permission gate aliases (`friends_permission`, `contacts_permission`).
- [x] ✅ Production‑Ready Implement optionsBuilder sources (method-based resolver + static fallback).
- [x] ✅ Production‑Ready Wire `onStepSubmit` to persistence handler (store_key mapping).
- [x] ✅ Production‑Ready Render layouts (row/grid/list/tags) with HTML/Markdown body support.
- [x] ✅ Production‑Ready Subscribe to push telemetry events and forward to event_tracker_handler.
- [x] ✅ Production‑Ready Add debug/test route or entrypoint for message ID injection.
- [x] ✅ Production‑Ready Add `PushActionDispatcher` service that maps `custom_action` to app behaviors.
- [x] ✅ Production‑Ready Implement `request_location` action using geolocator permission flow.
- [x] ✅ Production‑Ready Implement `request_friends_access` action using the invite share friends permission logic.
- [x] ✅ Production‑Ready If contacts permission is permanently denied, send user to app settings (toast otherwise).
- [x] ✅ Production‑Ready Wire dispatcher into the push flow (buttons with `custom_action`).
- [x] ✅ Production‑Ready Capture `message_instance_id` from payload meta and forward it with action reports/telemetry.
- [x] ✅ Production‑Ready Move dynamic option resolution behind a controller (no repo calls from infra/services).
- [x] ✅ Production‑Ready Replace `option_source` schema with method-based resolver (`type=method`, `name=...`) across backend validation + docs.
- [x] ✅ Production‑Ready Add `PushOptionsController` that resolves method names via repositories (e.g., `getFavorites`, `getTags`) and returns `OptionItem` list.
- [x] ✅ Production‑Ready Wire `ApplicationContract`/DI to use controller-backed optionsBuilder (controller -> repositories).
- [x] ✅ Production‑Ready Update tests to cover method-based option_source resolution (favorites + tags).
- [x] ✅ Production‑Ready Scope push answer persistence keys by `message_instance_id` to avoid cross-delivery selection bleed.
- [x] ✅ Production‑Ready Enforce `selection_mode` in app parsing for selector steps only.
  - Update `PushOptionSelectorSheet` to support selector `selection_mode` (default `single`).
  - Add widget test in `push_option_selector_sheet_test.dart` for selector single-select behavior.
- [x] ✅ Production‑Ready Add list layout rendering for selector/question option lists and make tags/list scrollable.
- [x] ✅ Production‑Ready External selector sheet uses "Continuar" CTA gated by `min_selected` (disabled until satisfied).

### Tests
- [x] ✅ Production‑Ready Unit tests for step parsing and config validation (plugin).
- [x] ✅ Production‑Ready Widget tests for layouts, min/max selection, dismissible, closeOnLastStepAction.
- [x] ✅ Production‑Ready Integration tests using debug injection hook to validate display pipeline.
- [x] ✅ Production‑Ready Telemetry event assertions for each interaction type.
- [x] ✅ Production‑Ready Add coverage to verify dedupe uses `message_instance_id` when available.
- [x] ✅ Production‑Ready Move generic push UI widget tests from app into push_handler plugin (keep app wiring tests in app). (Moved to `TODO-v1-push-onboarding-ephemeral-answers.md`.)
- [x] ✅ Production‑Ready Add widget tests for selector list layout + single/multi selection_mode enforcement (push_handler plugin).
- [x] ✅ Production‑Ready Add widget tests for question validator gating (text required, single-select required, multi-select min).
- [x] ✅ Production‑Ready Add widget test for inline selector min/max enabling/disabling CTA.
- [x] ✅ Production‑Ready Add widget test for external selector sheet CTA gating (min_selected).
- [x] ✅ Production‑Ready Add widget test that external selector hides inline options.
- [x] ✅ Production‑Ready Add widget test that gated custom actions advance regardless of `continue_after_action`.

---

## push_handler Plugin (Generic UI + Hooks)

### Contracts and Types
- Add support for `slug` in step model (no reliance on step index for identity).
- Add `dismissible` per step to control step skip. Default false when missing.
- Add `gate` object per step and `onSubmit` object per step. Do not interpret domain details.
- Add optional callbacks (new in repository/presenter):
  - `Future<bool> Function(StepPayload step)? gatekeeper`
  - `Future<void> Function(AnswerPayload answer)? onStepSubmit`
  - `Future<List<OptionItem>> Function(OptionSource source)? optionsBuilder`
  - `OptionItem` fields:
    - `value` (answer payload)
    - `label` (optional if `customWidgetBuilder` provided)
    - `subtitle` (optional)
    - `image` (optional)
    - `customWidgetBuilder` (optional)

### UI Behavior
- When rendering a step:
  - If the step has a `gate`, disable navigation until `gatekeeper(step)` returns true.
  - Re-run `gatekeeper` when returning to the app (resume) after any step action (open settings, navigate, popup, etc.).
  - For `custom_action` buttons, auto-advance only when `continue_after_action=true`; when enabled, re-run `gatekeeper(step)` and advance if it passes (otherwise keep the step and apply `onFail`).
  - If a button has `show_loading: true`, it must display a spinner and disable itself while the action is running (including `custom_action` callbacks).
  - If a button is tapped on the last step and `closeOnLastStepAction == true`, close the push UI.
  - On non-last steps, `closeOnLastStepAction` is ignored (push stays open).
- For question/selector:
  - Load options using `optionsBuilder` if `option_source` is present.
  - If `options` list is provided, treat it as a static fallback.
  - Enforce `min_selected` / `max_selected`.
  - Call `onStepSubmit(AnswerPayload)` on confirmation.

### Metrics
- Use `step.slug` for action reporting (opened, step_viewed, clicked, dismissed).
- Keep existing action payload; extend to include `step_slug` in metadata.

### Backwards Compatibility
- No backward compatibility for missing slug (reject payloads).
- If `closeOnLastStepAction` missing, default to false (last step only).
- If `gate` missing, treat as pass.

---

## Flutter App Implementation

### Gatekeeper
- Implement a `gatekeeper` function that checks app state:
  - `notifications_permission`: true if granted.
  - `favorites_min_selected` (alias `selection_min`): validate stored selection length against `min_selected` in step config.
  - Generic: allow future gates by mapping `gate.type` to app-side checks.
- If gate fails and `onFail.toast` exists, show toast.
- If `onFail.fallback_step` exists, navigate to that step slug.

### Options Builder
- Implement `optionsBuilder` mapping for dynamic sources:
  - `type: "method"` + `name` -> app-provided resolver (controller -> repository)
  - `options` list only as static fallback
- Enforce `cache_ttl_sec` where provided.

### Selector / Question Rendering
- Render layout based on `layout`:
  - `row`: horizontal chips
  - `list`: vertical list
  - `grid`: grid with `grid_columns`
  - `tags`: chip list
- Enforce `min_selected` / `max_selected` before allowing next.

### Preferences / Favorites
- Use generic `store_key` paths:
  - `preferences.tags`
  - `favorites.items`
- Map `store_key` to app persistence (user preferences endpoint or local store).

### CTA and Copy
- Render `body` as sanitized HTML or Markdown (images supported); no raw HTML without allowlist.
- `closeOnLastStepAction` default false on last step unless explicitly true; ignored elsewhere.
 - `custom_action` must be forwarded to the app (push_handler remains agnostic). The app may open a bottom sheet (e.g., favorites selector) and the gate will re-check on completion.
 - `open_favorites_selector` is the canonical `custom_action` for the favorites bottom sheet.

---

## Definition of Done
- [x] ✅ Production‑Ready Backend validation enforces new step schema.
- [x] ✅ Production‑Ready Backend README updated with examples.
- [x] ✅ Production‑Ready push_handler supports step slugs, dismissible, gatekeeper, optionsBuilder, onStepSubmit.
- [x] ✅ Production‑Ready Flutter app implements gatekeeper + optionsBuilder + selector rendering.
- [x] ✅ Production‑Ready Step actions and metrics use slugs (no index-based analytics).
- [x] ✅ Production‑Ready End-to-end push onboarding flow works with:
  - dynamic options from backend
  - gatekeeper blocking progression
  - closeOnLastStepAction behavior
- [x] ✅ Production‑Ready push_handler emits PushEvent telemetry and event_tracker_handler can subscribe.
- [x] ✅ Production‑Ready `custom_action` buttons can trigger app permission prompts.
- [x] ✅ Production‑Ready Permission gate feedback handles permanent denial (open settings) and denial (toast).
- [x] ✅ Production‑Ready Manual test payload with two gates is ready for validation.
- [x] ✅ Production‑Ready Push onboarding UI layout matches new navigation rules (top-right step skip, bottom-left voltar, default continuar).
- [x] ✅ Production‑Ready Push UI uses Theme.of(context) styles only (no hard-coded colors).

## Recent Decisions (Sync Notes)
- [x] ✅ Production‑Ready Replace `gate.required` with step-level `dismissible`; skip allowed only when step is dismissible (message-level `allowDismiss` removed).
- [x] ✅ Production‑Ready Gate behavior is consistent for all gates: check on step entry (auto-advance if granted), re-check after action; only difference is whether the step is dismissible.
- [x] ✅ Production‑Ready Permission handling: simple denial shows toast; permanent denial opens app settings; no automatic settings open on simple denial.
- [x] ✅ Production‑Ready Contacts permission uses `permission_handler` for request/status; FlutterContacts used only for fetching contacts.
- [x] ✅ Production‑Ready Device back button maps to “voltar”; on first step it does nothing.
- [x] ✅ Production‑Ready Rename message-level `closeOnTap` to `closeOnLastStepAction` (applies only to last-step actions), remove step-level `closeOnTap`.
- [x] ✅ Production‑Ready If `custom_action` is empty or unhandled, advance to next step and show a toast (no blocking, no log spam).
- [x] ✅ Production‑Ready Unknown/empty `custom_action` advances to next step and shows toast (no “Unhandled custom action” log spam).
- [x] ✅ Production‑Ready Add aliases so payload `custom_action` values work across app + plugin (`request_location_permission` / `request_contacts_permission`).
- [x] ✅ Production‑Ready Delivery expiration is calculated at send time (per-delivery TTL), with optional message-level deadline to cap delivery expiration (delivery expires at `min(message_deadline, now + ttl)`).
- [x] ✅ Production‑Ready Switch `option_source` to method-based resolver (`type=method`, `name=...`) and remove query/tags/endpoint from schema.


## Validation Steps
- [x] ✅ Production‑Ready Push payload with multi-step question renders and collects responses.
- [x] ✅ Production‑Ready Gatekeeper blocks next step until condition is met.
- [x] ✅ Production‑Ready closeOnLastStepAction false keeps push UI open after CTA.
- [x] ✅ Production‑Ready closeOnLastStepAction true closes on final CTA.
- [x] ✅ Production‑Ready Step removal/reorder does not break analytics (slugs stable).
- [x] ✅ Production‑Ready A/B payload variants (different steps) render correctly without app changes.
- [x] ✅ Production‑Ready PushEvent emission observed for delivered/opened/step_viewed/button_tap/submit/dismissed.

## Closure Notes
- Moved to `foundation_documentation/todos/active/TODO-v1-push-onboarding-ephemeral-answers.md`: remove push flow persistence in favor of callback-driven answers.
- Moved to `foundation_documentation/todos/active/TODO-v1-push-onboarding-ephemeral-answers.md`: add `OptionItem.isSelected` for pre-selection.
- Moved to `foundation_documentation/todos/active/TODO-v1-push-onboarding-ephemeral-answers.md`: move generic push UI widget tests from app into `push_handler`.

## Notes for Implementers
- Keep push_handler agnostic: no favorites logic inside plugin.
- All domain-specific behavior lives in Flutter app via callbacks.
- Backend is allowed to define payload variants per segment.
```

---

## Archived TODO: TODO-v1-push-onboarding-ephemeral-answers.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-onboarding-ephemeral-answers.md

```markdown
# TODO (V1): Push Onboarding Ephemeral Answers (Callback-Driven)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Flutter App, push_handler Plugin  
**Objective:** Remove push-flow persistence and rely on callback-driven answers; add `OptionItem.isSelected` for pre-selection; move generic push UI widget tests from app into the plugin.

---

## Scope
- Replace push-flow persistence (FlutterSecureStorage) with an in-memory answer store scoped to the active push flow.
- Gatekeeper reads from the in-memory store only.
- Action dispatcher writes selected answers into the in-memory store and relays the answer via callbacks.
- Keep `onStepSubmit` callback as the source of truth for app-side persistence decisions.
- Add `OptionItem.isSelected` to support pre-selected options from callbacks (inline and external selectors).
- Move generic push UI widget tests from the app into `push_handler` (keep app wiring tests in app).
- Update foundation docs to reflect callback-driven answers + `OptionItem.isSelected`.

## Out of Scope
- Backend changes.
- Telemetry/Mixpanel changes.
- New payload schema changes beyond `OptionItem.isSelected`.

---

## Target Behavior (Contracts)
- The push flow does not persist answers to disk.
- The app decides persistence in its callback implementations.
- Gate checks during a push session rely only on in-memory selections.
- External selectors can return pre-selected options using `OptionItem.isSelected`.

---

## Tasks (Execution Checklist)

### push_handler Plugin
- [x] ✅ Production‑Ready Add `isSelected` to `OptionItem`.
- [x] ✅ Production‑Ready Pre-select inline options when `isSelected=true`.
- [x] ✅ Production‑Ready Surface `isSelected` to external selector callbacks (no plugin persistence).
- [x] ✅ Production‑Ready Update README/CHANGELOG with callback-driven answer flow + `OptionItem.isSelected`.
- [x] ✅ Production‑Ready Add/adjust widget tests for `isSelected` pre-selection.

### Flutter App
- [x] ✅ Production‑Ready Replace `PushAnswerPersistence` with an in-memory answer store scoped to the current push flow.
- [x] ✅ Production‑Ready Update gatekeeper to read from the in-memory store only.
- [x] ✅ Production‑Ready Update action dispatcher to write to the in-memory store and relay answers via callbacks.
- [x] ✅ Production‑Ready Ensure `onStepSubmit` still relays answers (app decides persistence).
- [x] ✅ Production‑Ready Update external selector sheet to use `OptionItem.isSelected` for initial state.

### Tests
- [x] ✅ Production‑Ready Move generic push UI widget tests from `flutter-app` into `push_handler` (keep app wiring tests in app). No generic push UI tests remain in app.
- [x] ✅ Production‑Ready Add tests covering in-memory answer gating (no persistence across sessions).
- [x] ✅ Production‑Ready Add tests for `OptionItem.isSelected` pre-selection (inline + external).

### Documentation
- [x] ✅ Production‑Ready Update onboarding flow module documentation to note callback-driven answers and `OptionItem.isSelected`.
- [x] ✅ Production‑Ready Update `foundation_documentation/todos/active/TODO-v1-push-onboarding-dynamic-steps.md` closure notes (already moved).

---

## Definition of Done
- [x] ✅ Production‑Ready Push onboarding flow runs without any disk persistence.
- [x] ✅ Production‑Ready Gatekeeper uses only in-memory selections.
- [x] ✅ Production‑Ready Callbacks receive selections and the app controls persistence.
- [x] ✅ Production‑Ready `OptionItem.isSelected` pre-selects options consistently.
- [x] ✅ Production‑Ready Tests updated and passing in app + plugin.
- [x] ✅ Production‑Ready Documentation updated to reflect callback-driven flow.

---

## Validation Steps
- [ ] ⚪ Pending Run `fvm flutter test` for updated app tests.
- [ ] ⚪ Pending Run `fvm flutter test` for updated push_handler tests.
- [ ] ⚪ Pending Manually validate external selector flow does not auto-skip after app restart.

## Correction Note
- This TODO assumed in-memory storage in the push flow; it is superseded by `foundation_documentation/todos/active/TODO-v1-push-onboarding-callback-answers.md` to remove all push-flow storage and rely solely on callbacks.
```

---

## Archived TODO: TODO-v1-push-route-key-error-message.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-route-key-error-message.md

```markdown
# TODO (V1): Push Route Key Error Message Clarity

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (🟡 Provisional)  
**Owners:** Backend Team (source of truth)  
**Objective:** Improve push message validation errors for invalid route keys so they explain whether the key is missing or disallowed by message type.

---

## Scope
- Update validation errors for `payload_template.buttons.*.action.route_key` to:
  - Distinguish “route key not defined” vs “route key not allowed for message type.”
  - Include allowed route keys when restricted by message type.
- Adjust tests to assert the new error messaging.

## Out of Scope
- Changing validation rules or allowed behavior.
- Client-side error handling changes.

## Definition of Done
- [x] ✅ Error message clarifies missing vs disallowed route key.
- [x] ✅ Allowed route keys are listed when applicable.
- [x] ✅ Tests updated to cover the new messages.

## Validation Steps
- [ ] 🟡 Feature tests covering route key errors pass. (Not run yet.)

## Decisions
- Use validator message override for missing route key and explicit allowed-key message when disallowed.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushMessageStoreRequest.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushMessageUpdateRequest.php`
- `laravel-app/tests/Feature/Push/PushMessageFlowTest.php`
```

---

## Archived TODO: TODO-v1-push-settings-nesting.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-settings-nesting.md

```markdown
# TODO (V1): Nest Push Settings in Document Schema

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (✅ Production‑Ready)  
**Owners:** Backend Team (source of truth)  
**Objective:** Move push-specific fields into the `push` object in the settings document.

---

## Scope
- Nest the following under `push` in the stored settings document:
  - `max_ttl_days` → `push.max_ttl_days`
  - `push_message_routes` → `push.message_routes`
  - `push_message_types` → `push.message_types`
- Update all reads/writes to use the new nested paths.
- Remove legacy reads; no backward compatibility.
- Update tests and README to reflect the new schema.

## Out of Scope
- Changing endpoint behavior or contracts beyond internal storage shape.
- Flutter/client changes.

## Definition of Done
- [x] ✅ Settings document persists push fields under `push`.
- [x] ✅ Legacy reads removed; only nested fields are supported.
- [x] ✅ Tests updated for new storage paths.
- [x] ✅ README updated to describe the stored schema.

## Validation Steps
- [x] ✅ Push feature tests pass. (`docker compose exec app php artisan test --filter=PushMessageFlowTest`)

## Decisions
- Move root fields into `push` and rename nested keys to `message_routes` / `message_types` and `max_ttl_days`.
- Do not support backward compatibility for legacy root fields.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/TenantPushSettings.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Controllers/Tenant/TenantPushRouteTypesController.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Controllers/Tenant/TenantPushMessageTypesController.php`
```

---

## Archived TODO: TODO-v1-push-setup-readme.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-push-setup-readme.md

```markdown
# TODO (V1): Push Setup README Section

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Document the end-to-end push setup steps in the Laravel push handler README.

---

## Scope
- Add a new “Push Setup” section to the push handler README.
- Include required steps for credentials, firebase settings, push settings, route types, message types, and enable/disable.
- Provide example payloads for each step.

## Out of Scope
- Changing any endpoint behavior.
- Flutter/client UI wiring.
- Infrastructure/FCM delivery configuration beyond API setup.

## Definition of Done
- [x] ✅ Push Setup section added to `laravel-app/packages/belluga/belluga_push_handler/README.md`.
- [x] ✅ Step-by-step sequence documented (credentials → firebase → push → routes → message types → enable).
- [x] ✅ Example payloads provided for each endpoint.

## Validation Steps
- [x] ✅ README reviewed for accuracy against current endpoints.

## Decisions
- None.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/README.md`
```

---

## Archived TODO: TODO-v1-telemetry-and-push-backend.md
**Original Path:** foundation_documentation/todos/completed/TODO-v1-telemetry-and-push-backend.md

```markdown
# TODO (V1): Telemetry + Push Notifications (Backend)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team (source of truth)  
**Objective:** Deliver backend contracts for telemetry config, push message CRUD, and secure payload delivery.

---

## Scope (Current Increment)
- Add explicit feature tests for push message creation failure cases (validation + auth/abilities).
- Add feature test that `/send` returns `inactive` when message is active but scope mismatch (tenant vs account).

## Out of Scope (Current Increment)
- Any changes to push payload schema or delivery behavior.
- Frontend/UI changes.

## Definition of Done (Current Increment)
- [x] ✅ Production‑Ready Feature tests cover missing required fields on message creation (title/body/steps/audience).
- [x] ✅ Production‑Ready Feature tests cover invalid audience payloads (`users` without `user_ids`).
- [x] ✅ Production‑Ready Feature tests cover auth/ability failures for message creation (401/403).
- [x] ✅ Production‑Ready Tests pass (`php artisan test --filter=PushMessageFlowTest` or equivalent).
- [x] ✅ Production‑Ready Feature test covers `/send` scope mismatch returning `inactive`.

## Validation Steps (Current Increment)
- [x] ✅ Production‑Ready Run `php artisan test --filter=PushMessageFlowTest` and confirm green.

## Scope
- Define and deliver the plug'n'play push package: CRUD, data fetch, actions, metrics, and delivery pipeline.
- Implement package-owned routes with host-controlled path strings.
- Provide tenant push settings and credentials management, including FCM HTTP v1 delivery.
- Enforce delivery lifecycle (accepted/delivered/opened) and metrics aggregation.
- Implement quota-check endpoint and transactional single-recipient send.
- Enforce audience eligibility via host-provided contract (domain-agnostic).pi/v1/settings/push/cred
- Provide single-credential per tenant (upsert), removing dependency on `firebase_credentials_id` in settings.
- Use a single upsert endpoint for credentials (`PUT /api/v1/settings/push/credentials`) and block multiple legacy credentials (409).
- Require `firebase` public config and `push.enabled` in tenant push settings updates.
- Provide a tenant push status endpoint that reports configuration state.
- Default `max_ttl_days` to 7 when omitted.
- Document the push activation flow in the project README (service account, tenant settings, status endpoint).

## Out of Scope
- Flutter UI behavior, deep links, or telemetry client wiring.

## Definition of Done
- [x] ✅ Push message config endpoint defined: `GET /api/v1/push/messages/{push_message_id}`.
- [x] ✅ Push message data endpoint defined: `GET /api/v1/push/messages/{push_message_id}/data`.
- [x] ✅ Push message CRUD endpoints defined (create/update/list/archive).
- [x] ✅ Push message schema documented (config + data payload).
- [x] ✅ Push message access rules enforced (401/404 behavior, anonymous token allowed for data fetch).
- [x] ✅ Push message TTL/validity and `active` flag honored; inactive/expired returns `ok=false`.
- [x] ✅ Push message delete semantics enforced (hard delete only if not delivered).
- [x] ✅ Package migration lives inside `belluga_push_handler` and is loaded via the package service provider.
- [x] ✅ Tenant migration runner includes package migration paths (config-driven) so tenant DBs include push collections.
- [x] ✅ Package registers push routes while host app retains control of the path strings.
- [x] ✅ Package manages tenant push credentials and performs real FCM delivery (plug'n'play).
- [x] ✅ FCM credentials stored in `push_credentials` and linked via tenant settings (encrypted fields).
- [x] ✅ Real FCM HTTP v1 client implemented and wired via `FcmClientContract`.
- [x] ✅ Delivery fan-out jobs use multicast batching and retry/backoff; partial failures logged.
- [x] ✅ `push_delivery_logs` collection implemented and used for error tracking.
- [x] ✅ Quota-check endpoint implemented (policy-backed decision payload).
- [x] ✅ Transactional single-recipient send endpoint implemented with tenant-scoped `user_id`/`email` resolution.
- [x] ✅ Audience eligibility enforced via host contract (domain-agnostic).
- [x] ✅ Package README expanded with full examples, integration guidance, and implementation notes.
- [x] ✅ Environment payload includes telemetry + firebase + push settings (single `/api/v1/environment` call).
- [x] ✅ Delivery lifecycle and metrics defined and stored.
- [x] ✅ Backend tests cover auth, access, TTL/active, CRUD, cross-tenant access.
- [x] ✅ Sync `laravel-app` submodule to the latest upstream `dev` commit that contains this implementation.
- [x] ✅ Production‑Ready Tenant push settings require `firebase` and `push.enabled` (no `firebase_credentials_id`).
- [x] ✅ Production‑Ready Validation errors clearly report missing required Firebase/push settings fields (no `firebase_credentials_id`).
- [x] ✅ Tenant push status endpoint returns `not_configured | pending_tests | active`.
- [x] ✅ Tenant push settings default `max_ttl_days` to 7 when omitted.
- [x] ✅ Production‑Ready Push settings validated end-to-end with real tenant credentials and device.
- [x] ✅ Production‑Ready Push delivery test confirms status flips to `active`.
- [x] ✅ Production‑Ready README includes step-by-step instructions to enable push + Firebase (credentials + settings + status check).
- [x] ✅ Production‑Ready Temporary FCM delivery logging verified (or deemed unnecessary after validation).
- [x] ✅ Production‑Ready Single-credential flow: settings no longer require `firebase_credentials_id`; credentials upsert via `PUT` and only one credential is used per tenant.

## Validation Steps
- [x] ✅ Feature tests for CRUD endpoints (create/list/update/archive/delete).
- [x] ✅ Auth tests: 401 without token, 404 without access.
- [x] ✅ Data fetch returns `ok=false` for inactive/expired message.
- [x] ✅ Cross-tenant access blocked for landlord tokens without tenant access.
- [x] ✅ Device registration/unregister tests (upsert, rotation, removal).
- [x] ✅ Audience enforcement tests for `all`, `users`, `event` qualifiers.
- [x] ✅ Actions endpoint tests (required fields, idempotency, anonymous auth).
- [x] ✅ Metrics aggregation tests (unique counts, per-button, per-step).
- [x] ✅ Job scheduling tests (immediate send vs scheduled).
- [x] ✅ Tenant settings update tests (types + max TTL).
- [x] ✅ Route registration is loaded from package route file while paths are defined by host config.
- [x] ✅ Tenant push credentials lifecycle validated (create/update/read).
- [x] ✅ Real FCM delivery validated via concrete FCM client implementation.
- [x] ✅ FCM batching uses expected limits; partial failures persist to delivery logs.
- [x] ✅ Quota-check endpoint returns expected payload for allowed/blocked cases.
- [x] ✅ Transactional send respects `transactional` type and tenant scoping (user_id/email).
- [x] ✅ Audience eligibility tests cover host-provided contract outcomes (allow/deny).
- [x] ✅ Confirm `laravel-app` submodule commit matches upstream `dev` with push/telemetry changes.
- [x] ✅ Production‑Ready `PATCH /api/v1/settings/push` returns 422 when required Firebase/push fields are missing (no `firebase_credentials_id`).
- [x] ✅ Production‑Ready `PATCH /api/v1/settings/push` accepts payload when required fields are present (no `firebase_credentials_id`).
- [x] ✅ `GET /api/v1/settings/push/status` returns `not_configured` when required settings are missing.
- [x] ✅ `GET /api/v1/settings/push/status` returns `pending_tests` when configuration exists but no push delivery logs exist.
- [x] ✅ `GET /api/v1/settings/push/status` returns `active` when configuration exists and a delivery log with `accepted` exists.
- [x] ✅ `PATCH /api/v1/settings/push` without `max_ttl_days` persists `max_ttl_days = 7`.
- [x] ✅ Production‑Ready Send a test push to a real device and verify:
  - `FCM` token registration succeeds (`/api/v1/push/register`).
  - `/api/v1/settings/push/status` transitions `pending_tests` → `active`.
  - `/api/v1/push/messages/{push_message_id}/actions` records `delivered` + `opened`.
- [x] ✅ Production‑Ready README instructions validated against the live endpoints.
- [x] ✅ Production‑Ready Tests updated for single-credential behavior (PUT upsert + no `firebase_credentials_id` requirement).
- [x] ✅ Production‑Ready `PUT /api/v1/settings/push/credentials` upserts the single credential (200 on update, 201 on first create).
- [x] ✅ Production‑Ready Multiple legacy credentials return 409 and do not attempt delivery until resolved.

## Implementation Tasks (Remaining)
- [x] ✅ Define audience eligibility contract, default allow-all binding, and integration in data/send flows.
- [x] ✅ Ensure host app can override eligibility logic (documentation + binding example).
- [x] ✅ Define `push_credentials` model + migration with encrypted fields (project_id, client_email, private_key).
- [x] ✅ Extend tenant settings schema to store `firebase_credentials_id`.
- [x] ✅ Implement FCM HTTP v1 client (`FcmClientContract`) using tenant credentials.
- [x] ✅ Add delivery jobs: fan-out, batching (multicast), retry/backoff, and delivery log persistence.
- [x] ✅ Add `push_delivery_logs` collection with error details and status per batch/token.
- [x] ✅ Implement quota-check endpoint (policy decision payload).
- [x] ✅ Implement transactional single-recipient send endpoint (`transactional` only, eligibility enforced).
- [x] ✅ Enforce audience eligibility in fetch/send flows via host contract.
- [x] ✅ Add support for optional `fcm_options` and map it into FCM payload (validation + normalization).
- [x] ✅ Enforce `fcm_options` size caps and allowed keys based on FCM documentation; define conservative caps when FCM lacks limits.
- [x] ✅ Implement tenant-scoped push message routes (reuse controllers/services without duplication).
- [x] ✅ Define tenant-specific push abilities (separate from account abilities) and enforce at controller boundary.
- [x] ✅ Implement scope context resolution in controllers; services accept scope and avoid repeated checks.
- [x] ✅ Implement tenant credentials endpoints secured by `tenant-push-credentials:*` abilities.
- [x] ✅ Add tenant push abilities to `config/abilities.php`.
- [x] ✅ Update tenant migration runner to use configurable migration paths (include push package migrations) and document in package README.
- [x] ✅ Register tenant push message routes in the package route file.
- [x] ✅ Define endpoints to manage credentials (create/update/read) and enforce abilities.
- [x] ✅ Update package README with new plug'n'play details (credentials, delivery, quota-check, eligibility).
- [x] ✅ Add/extend tests to cover the new flows.
- [x] ✅ Hardening tests step: execute all “Hardening Gaps + Tests to Add” before final sign-off.
- [x] ✅ Close Gap 1 (not_found): add `/data` not-found test and map to reason `not_found`.
- [x] ✅ Production‑Ready Close Gap 2 (actions beyond clicked): ensure tests for opened/dismissed/step_viewed/delivered + validation cases.
- [x] ✅ Close Gap 3 (permission matrix): 401/403 tests for account + tenant CRUD, and landlord without tenant access.
- [x] ✅ Close Gap 4 (cross-tenant isolation): block CRUD/data/actions/credentials cross-tenant.
- [x] ✅ Close Gap 5 (plan policy enqueue): canSend false blocks schedule/dispatch; quota decision surfaced.
- [x] ✅ Close Gap 6 (FCM options limits/overrides): title/body caps + platform override behavior.
- [x] ✅ Close Gap 7 (external action validation): missing URL + invalid open_mode -> 422.
- [x] ✅ Update `laravel-app` submodule pointer to the upstream `dev` commit containing the backend implementation.
- [x] ✅ Update tenant push settings validation to require `firebase_credentials_id`.
- [x] ✅ Update tenant push settings validation to require `firebase` public config.
- [x] ✅ Update tenant push settings validation to require `push.enabled`.
- [x] ✅ Update/extend tests for tenant settings validation errors.
- [x] ✅ Add push status endpoint in tenant settings routes (`/api/v1/settings/push/status`).
- [x] ✅ Define push status computation rules (config present + delivery log evidence).
- [x] ✅ Add status derivation using delivery logs (accepted = active).
- [x] ✅ Update tests for push status endpoint states.
- [x] ✅ Add defaulting logic for `max_ttl_days` when omitted.
- [x] ✅ Production‑Ready Execute live push settings + test delivery on tenant device.
- [x] ✅ Production‑Ready Update README with push activation flow and payload examples (after test validation).
- [x] ✅ Adjust credential endpoints to upsert single tenant credential; update settings validation to remove `firebase_credentials_id` requirement; update `PushCredentialService` to select the single credential.
- [x] ✅ Remove `firebase_credentials_id` from tenant push settings payloads (request/response) and model casts.
- [x] ✅ Replace credential endpoints with `PUT /api/v1/settings/push/credentials` (no `:id`) and update tests accordingly.
- [x] ✅ Enforce single-credential rule: if multiple legacy credentials exist, return 409 and require cleanup.
- [x] ✅ Production‑Ready Verify FCM HTTP v1 response logs and resolve accepted_count=0 root cause.

## Provisional Notes
- Push activation instructions are deferred until real-device testing confirms behavior.
- If testing reveals missing data or unexpected status transitions, update settings validation and status rules before finalizing README steps.

## Test Plan

**Test Mandate**
- [x] ✅ Prefer transparent failures over false positives; no workaround-only changes to force green tests.

**Hardening Tests (Required)**
- [x] ✅ Production‑Ready Close remaining coverage gaps with explicit tests before final sign-off.

**Credentials & Settings**
- [x] ✅ Production‑Ready Tenant settings store and return `firebase` public config + `push.enabled` (no `firebase_credentials_id`).
- [x] ✅ `push_credentials` create/update/read works and fields are encrypted.
- [x] ✅ Credential endpoints return 201/200 and 422/403 as expected.

**FCM Client + Delivery**
- [x] ✅ FCM HTTP v1 client sends payload with expected structure.
- [x] ✅ Delivery job batches tokens (respect max batch size).
- [x] ✅ Partial failures write `push_delivery_logs` entries and do not block other tokens.
- [x] ✅ Accepted metrics update from FCM response.
- [x] ✅ `fcm_options` are accepted/validated and included in payload.
- [x] ✅ `fcm_options` rejects invalid keys/sizes (422).

**Quota Check**
- [x] ✅ Quota-check endpoint returns allowed payload (limit/used/remaining).
- [x] ✅ Quota-check endpoint returns blocked payload with reason.
- [x] ✅ Quota-check invalid input returns 422.

**Transactional Send**
- [x] ✅ Reject non-transactional message types.
- [x] ✅ Accept `user_id` and `email` targets (tenant-scoped).
- [x] ✅ Enforce eligibility for transactional target.
- [x] ✅ Enforce Sanctum + `push-messages:send`.

**Audience Eligibility**
- [x] ✅ Contract allows eligible audience.
- [x] ✅ Contract denies ineligible audience.
- [x] ✅ Host override binding is honored.

**Tenant Routes**
- [x] ✅ Tenant CRUD endpoints enforce tenant abilities and scope.
- [x] ✅ Tenant `/data` and `/actions` respect eligibility contract.
- [x] ✅ Tenant transactional `/send` enforced and scoped.

**Tenant Credentials**
- [x] ✅ Credential endpoints require `tenant-push-credentials:*`.
- [x] ✅ Tenant credential endpoints are tenant-scoped (no cross-tenant access).
- [x] ✅ Package README reviewed for completeness and accuracy (examples verified).

**Auth & Access**
- [x] ✅ 401 for all push endpoints without token (CRUD, data, actions).
- [x] ✅ 403 for tokens without required abilities.
- [x] ✅ Cross-tenant denial for landlord tokens without tenant access.
- [x] ✅ Anonymous token accepted for `/data` and `/actions` (not CRUD).
- [x] ✅ Landlord ability gates for `push-settings:update` and `push-messages:send`.

**CRUD & Validation**
- [x] ✅ Create message validates required fields, `internal_name` uniqueness per account, and `expires_at` ≤ 30 days.
- [x] ✅ Update message blocked when sent/archived (if enforced).
- [x] ✅ List messages scoped to account with filters (status/type).
- [x] ✅ Delete message hard-delete only if not delivered/sent; otherwise archive/deactivate.
- [x] ✅ Audience validation rules (`users` requires `user_ids`; `event` requires `event_id` + qualifier).

**/data Fetch**
- [x] ✅ `ok=true` returns payload when active and unexpired.
- [x] ✅ `ok=false` for inactive or expired.
- [x] ✅ Not found returns `ok=false`.
- [x] ✅ Audience enforcement for `all`, `users`, `event` qualifiers (via eligibility contract).

**Actions Endpoint**
- [x] ✅ `step_index` required for all actions; `button_key` required for `clicked`.
- [x] ✅ Idempotency key prevents duplicate aggregation.
- [x] ✅ Anonymous token accepted.
- [x] ✅ Aggregates update: per-button counts, per-step counts, unique counts.

**Delivery & Jobs**
- [x] ✅ Immediate delivery enqueues fan-out job on create.
- [x] ✅ Scheduled delivery defers to job at `scheduled_at`.
- [x] ✅ `accepted` updates from mocked FCM response.
- [x] ✅ Delivery logs have no TTL index (retention forever).

**Settings**
- [x] ✅ Tenant settings update includes `push_message_types` and max TTL.
- [x] ✅ Settings scoped per tenant.

**Device Tokens**
- [x] ✅ Register upserts by `device_id`, updates token, preserves other devices.
- [x] ✅ Unregister removes device by `device_id`.

## Hardening Gaps + Tests to Add

1) Data not-found behavior (`/data`)
- [x] ✅ Test: `GET /push/messages/{missing}/data` returns `ok=false` + `reason=not_found`.

2) Actions coverage beyond `clicked`
- [x] ✅ Test: `opened` records metrics (counts/unique).
- [x] ✅ Test: `dismissed` records metrics (counts/unique).
- [x] ✅ Test: `step_viewed` records per-step counts.
- [x] ✅ Test: `delivered` records metrics.
- [x] ✅ Test: missing `step_index` returns 422.
- [x] ✅ Test: `clicked` without `button_key` returns 422.

3) Permission matrix (systematic 401/403)
- [x] ✅ Test: 401 for account CRUD without token.
- [x] ✅ Test: 401 for tenant CRUD without token.
- [x] ✅ Test: 403 for account CRUD without required abilities.
- [x] ✅ Test: 403 for tenant CRUD without required abilities.
- [x] ✅ Test: landlord user without tenant access cannot read tenant push resources.

4) Cross-tenant isolation
- [x] ✅ Test: tenant A cannot access tenant B message `/data` or `/actions`.
- [x] ✅ Test: tenant A cannot read/update/delete tenant B message CRUD.
- [x] ✅ Test: tenant A cannot access tenant B credentials index/show/update/delete.

5) Plan policy at enqueue
- [x] ✅ Test: `PushPlanPolicyContract::canSend` false blocks schedule/dispatch on create.
- [x] ✅ Test: quota-check decision payload surfaced on create (if implemented).

6) FCM options limits/overrides
- [x] ✅ Test: notification title/body length caps enforced (422).
- [x] ✅ Test: platform overrides (`android.notification`, `apns.payload.aps.alert`) honored.

7) External button action validation
- [x] ✅ Test: `external` action missing URL returns 422.
- [x] ✅ Test: invalid `open_mode` returns 422.

## Decisions
- Push payload delivery: FCM payload contains `push_message_id` only; client fetches payload from API.
- Push message config endpoint: `GET /api/v1/push/messages/{push_message_id}`.
- Push message data endpoint: `GET /api/v1/push/messages/{push_message_id}/data`.
- Push message CRUD endpoints:
  - `POST /api/v1/push/messages`
  - `GET /api/v1/push/messages`
  - `PATCH /api/v1/push/messages/{push_message_id}`
  - `DELETE /api/v1/push/messages/{push_message_id}`
- Delete semantics: allow hard delete only if message has not been delivered; once sent, only archive/deactivate.
- Send semantics: create triggers delivery; immediate delivery dispatches on create. Jobs orchestrate async fan-out and delivery pipeline.
- Push payload fetch auth transport: `Authorization: Bearer <token>` header (never query params).
- Push payload fetch auth: bearer token required (anonymous token allowed).
- Push payload fetch authorization: 401 when no token, 404 when token lacks access to message.
- Push payload fetch response: structured JSON payload (no stringification required).
- Push payload TTL/validity: controlled by push settings; backend returns `ok=false` when expired/invalid.
- Push payload `active` state: backend decides whether to return payload; inactive returns `ok=false`.
- Audience evaluation happens at `/data` fetch time; segments are out of scope for V1.
- V1 audience types: `all`, `users`, `event`.
- Event audience qualifier is required and evaluated dynamically at fetch: `event.confirmed`, `event.invited`, `event.all`, `event.sent_invites`.
- (Decision moved) Account profile scoping + 1:N profile requirements are documented in system architecture + endpoint contracts; implementation tracking lives in the Account Profile implementation TODO.
- Message `type` supports account-profile-defined custom types; tenants can define custom types that account profiles use.
- Account profile messages store `account_profile_id`; tenant messages rely on tenant database context (no `tenant_id` field) but still bind to a profile.
- Message type definitions live under Tenant Settings (shared settings hub).
- Template variable defaults are configured per message by the creator.
- Delivery metrics recorded via per-user actions in a separate collection; transactions update aggregates.
- Metrics should include per-button breakdowns and unique user counts.
- Actions endpoint required for V1: `POST /api/v1/push/messages/{push_message_id}/actions`.
- Metrics should include per-step view counts (step index or step key).
- Actions payload should include the current step for `dismissed` and `clicked` to measure step drop-off.
- Step index is required for all action records (use `0` for single-step messages).
- Laravel push handling will be delivered as a standalone package.
- Push package scope includes device token management (register/unregister/rotation) plus message CRUD/data/actions, audience evaluation, and metrics aggregation.
- Settings remain in the main project; packages extend settings with their own sections/traits/data.
- Package location: keep in this repo until fully working, then extract to its own repository.
- Package name: `belluga_push_handler`.
- Package owns its migrations; host app loads them via `loadMigrationsFrom`.
- Package owns route registration; host app controls path strings via config defaults (no hardcoded paths in package).
- Plug'n'play package mandate: package handles tenant push settings, credential storage, and real delivery.
- Credentials: store in a dedicated `push_credentials` collection referenced by tenant settings.
- Credential security: app-level encryption now; design for KMS later.
- FCM client strategy: HTTP v1 only.
- Service account source: store minimal fields (project_id, client_email, private_key) in encrypted fields.
- Delivery pipeline: package owns fan-out + send + retry/backoff.
- Rate limiting: package enforces tenant throttles; account quotas enforced via `PushPlanPolicyContract`.
- Failure logging: separate `push_delivery_logs` collection plus aggregate metrics.
- Multi-project support: one Firebase project per tenant in v1.
- Settings surface: `push_enabled`, `firebase_public_config`, `throttles`, `push_message_types`, `push_message_routes`.
- Defaults/seeding: package provides defaults; host opts in via config flag.
- FCM flexibility: keep core fields (title/body/image) and add optional `fcm_options` for advanced FCM features, validated at send time.
- Quota check: add a dedicated quota-check endpoint that calls policy and returns a rich quota decision payload (allowed/limit/used/remaining). Use it for confirmation dialogs before enqueue.
- Delivery batching: use FCM bulk/multicast delivery in jobs to minimize requests; document batch size and partial failure handling.
- Audience eligibility is delegated to a host-provided contract (zero domain knowledge in package).
- Audience rules (opt-in, events, TTL) are implemented by the host app via the eligibility contract.
- Transactional single-recipient send:
  - Endpoint: `POST /api/v1/accounts/{account_slug}/push/messages/{push_message_id}/send`
  - Only allowed when `message.type = transactional`.
  - Resolve recipient by `user_id` or `email` (tenant-scoped, normalized).
  - Requires Sanctum + `push-messages:send`.
  - Enforce eligibility via host contract for transactional targets.
- Audience eligibility contract must be defined and bound by host; package provides allow-all default.
- Event TTL should be enforced by host eligibility logic (package stays domain-agnostic).
- PushPlanPolicyContract stays as-is (no extra context payload); message + account + audience size are sufficient.
- Credential management: tenant-only endpoints guarded by a dedicated, restrictive permission.
- Credential upsert updates the existing record (preserve id). If multiple credentials exist, return 409 and require cleanup.
- Settings payloads remove `firebase_credentials_id` from both request and response.
- FCM options: accept optional `fcm_options` object; no extra required fields at creation beyond core templates. Validate only when provided, per FCM platform rules.
- Audience eligibility contract proposal:
  - Interface: `PushAudienceEligibilityContract`
  - Method: `isEligible(AccountUser $user, PushMessage $message, array $audience, array $context = []): bool`
  - Context may include `tenant_id`, `account_id`, `now`, `audience_size`, `message_type`.
- Quota-check endpoint (account-scoped):
  - `GET /api/v1/accounts/{account_slug}/push/quota-check`
  - Requires Sanctum + `push-messages:send`
  - Query: `audience_size`, `message_type`, `push_message_id` (optional)
  - Returns decision payload (allowed/limit/current_used/requested/remaining_after/period/reason).
- Credential endpoints (tenant-scoped):
  - `PUT /api/v1/settings/push/credentials`
- FCM options mapping:
  - Default mapping: `title_template`/`body_template` populate `fcm_options.notification` when no notification override is provided.
  - Override rule: `fcm_options.notification` replaces defaults; no duplication.
  - Platform overrides: `fcm_options.android.notification` and `apns.payload.aps.alert` override generic notification per platform.
- Push message `/data` response payload is normalized to `push_handler` DTO shape:
  - Top-level `title`, `body`, `allowDismiss`, `layoutType`, `onClickLayoutType`, `image`, `steps`, `buttons`.
  - Buttons use `routeType` + `routeInternal`/`routeExternal` (no nested `action` object in the delivered payload).
- Credential endpoint payloads (tenant-only):
  - Upsert: `{ "project_id": "...", "client_email": "...", "private_key": "..." }` (all required; stored encrypted).
  - Read: `{ "id": "...", "project_id": "...", "client_email": "...", "created_at": "...", "updated_at": "..." }` (never return private_key).
- Delivery log schema:
  - `push_message_id`, `batch_id`, `token_hash`, `status` (`accepted|failed`), `error_code`, `error_message`, `provider_message_id`, `created_at`.
- Quota-check response schema:
  - `allowed`, `limit`, `current_used`, `requested`, `remaining_after`, `period`, `reason` (nullable string).
- Delivery log retention: keep indefinitely for MVP (no TTL or cap).
- Credential endpoints response codes: follow Laravel defaults and existing project patterns (201 on create, 200 on update/read; 422 validation; 403 permission).
- FCM options validation: mirror FCM HTTP v1 schema/allowed keys; enforce size caps per FCM limits when defined, otherwise apply conservative security caps.
- Plan policy hook: define a `PushPlanPolicyContract` in the package; default allow-all when no host binding exists. Host app can enable later to enforce account plan quotas during send jobs.
- Routing map:
  - Account routes (partner/account users): push message CRUD + `/data` + `/actions`.
  - Tenant routes: tenant push settings endpoint(s) stored in tenant database.
  - Landlord routes: tenant settings management (types, max TTL, global push settings).
- Tenant push messages mirror account routes with tenant-specific abilities; scope resolved once in controllers and passed to services.
- Tenant route surface mirrors account routes:
  - `POST /api/v1/push/messages`
  - `GET /api/v1/push/messages`
  - `GET /api/v1/push/messages/{push_message_id}`
  - `PATCH /api/v1/push/messages/{push_message_id}`
  - `DELETE /api/v1/push/messages/{push_message_id}`
  - `GET /api/v1/push/messages/{push_message_id}/data`
  - `POST /api/v1/push/messages/{push_message_id}/actions`
  - `POST /api/v1/push/messages/{push_message_id}/send` (transactional)
- Unique metrics: same user performing the same action on the same context (event/invite/etc) counts once.
- Message templates support declared variables; creators define variables and defaults (e.g., `value: "user.first_name"`, `default: ""`) and then reference variables in content.
- `internal_name` must be unique per Account.
- Abilities follow existing `resource:action` pattern and are split by actor:
  - Landlord users (tenant settings authority):
    - `push-settings:update` (types, max TTL, global push settings)
    - `push-messages:send` (send on behalf of an Account)
  - Account users (partners; account-scoped):
    - `push-messages:read`, `push-messages:create`, `push-messages:update`, `push-messages:delete`, `push-messages:send`
    - Permissions scoped per account role (existing account role permissions apply)
  - Tenant users (app owner; tenant-scoped):
    - `tenant-push-messages:read`, `tenant-push-messages:create`, `tenant-push-messages:update`, `tenant-push-messages:delete`, `tenant-push-messages:send`
    - `tenant-push-credentials:read`, `tenant-push-credentials:update` (create/update/delete)
- Partner = Account; each Account can have multiple Account Users with role-scoped permissions.
- TTL policy: message `expires_at` is required; server enforces max TTL from tenant settings (default 30 days); `ttl_minutes` derived and validated.
- Delivery metrics split into:
  - `accepted` (FCM accepted)
  - `delivered` (device receipt, client-reported)
  - `opened` (recipient action; data fetch ok=true or explicit action)
- `accepted` is updated from the FCM send response (fan-out job).
- `delivered` is updated from client callbacks (FCM onMessage/onBackgroundMessage).
- `/actions` accepts anonymous tokens and authenticates via bearer token when present.
- Frontend is responsible for preventing duplicate action submissions; backend allows repeated actions but aggregates unique metrics separately.
- Actions idempotency: client generates `idempotency_key` per action and submits it; backend uses it to de-duplicate processing.
- Jobs: fan-out and scheduling follow Laravel defaults (queued jobs, scheduled dispatch for `scheduled_at`, async send on create).
- Telemetry integrations are stored as an array of integration objects compatible with `event_tracker_handler`:
  - `type` (string, required; `mixpanel`, `firebase`, `webhook`)
  - `events` (array of strings, required)
  - `token` (string, required when `type = mixpanel`)
  - `url` (string, required when `type = webhook`)
- `/api/v1/environment` returns `telemetry` in the same `event_tracker_handler` shape (no additional mapping required by Flutter).

## Questions to Close
-- none --

## References
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/todos/active/TODO-v1-telemetry-and-push-frontend.md`

---

## Backend Requirements (V1)

### B1) Device registration
- [x] ✅ Implement `POST /api/v1/push/register` in upstream:
  - [x] ✅ accept `{ device_id, platform, push_token }`
  - [x] ✅ associate token with authenticated user + tenant
- [x] ✅ support anonymous + authenticated states for the same user object
- [x] ✅ Optional `DELETE /api/v1/push/unregister` in upstream
- [x] ✅ Handle token rotation idempotently

### B2) Notification policies (tenant settings)
- [x] ✅ Return which notification categories are enabled and any throttles (tenant settings)
- [x] ✅ Keep backend authoritative; Flutter should not implement quota rules beyond UX

### B2.1) Tenant admin management (config storage)
- [x] ✅ Provide a Tenant Admin area (not Landlord Admin) where landlord users with tenant access can manage:
  - [x] ✅ Mixpanel project token per tenant
  - [x] ✅ Firebase project options per tenant (public config only)
- [x] ✅ Persist configs per tenant and expose them through a single environment/bootstrap payload (no parallel config calls)

### B3) Notification payload contract (deep linking)
- [x] ✅ Provide routing data for the client fetch payload:
  - `tenant_id`
  - `type`: `invite_received | event_reminder | invite_status_changed | ...`
  - `event_id` (if applicable)
  - `invite_id` or `invite_code` (if applicable)
  - optional `inviter_principal` summary for display

### B4) Environment payload (single call; public-safe)
This config must be merged into the existing `/api/v1/environment` response (no parallel calls).

```json
{
  "tenant_id": "tenant_123",
  "telemetry": [
    {
      "type": "mixpanel",
      "token": "public_token_here",
      "events": [
        "invite_received",
        "invite_opened",
        "invite_accept_selected_inviter",
        "invite_accepted",
        "invite_declined",
        "event_opened",
        "event_confirmed_presence",
        "map_opened",
        "poi_opened",
        "favorite_artist_toggled"
      ]
    }
  ],
  "firebase": {
    "apiKey": "PUBLIC_API_KEY",
    "appId": "1:1234567890:android:abcdef123456",
    "projectId": "tenant-project-id",
    "messagingSenderId": "1234567890",
    "storageBucket": "tenant-project-id.appspot.com"
  },
  "push": {
    "enabled": true,
    "types": [
      "invite_received",
      "event_reminder"
    ],
    "throttles": {
      "event_reminder_max_per_day": 3
    }
  }
}
```

---

## Push Message Schema (V1)

**PushMessage (config)**
- `id` (ObjectId)
- `partner_profile_id` (ObjectId, required for account_profile messages)
- `internal_name` (string, required, max 120, unique per account)
- `title_template` (string, max 255)
- `body_template` (string, max 1000)
- `type` (string, tenant-defined type key)
- `active` (bool, default true)
- `status` (enum)
- `audience` (object)
  - `type` (enum)
  - `user_ids` (array<ObjectId>, required if `users`)
  - `event_id` (ObjectId, required if `event`)
  - `event_qualifier` (enum, required if `event`)
- `delivery` (object)
  - `scheduled_at` (datetime, optional)
  - `expires_at` (datetime, optional)
  - `ttl_minutes` (int, optional)
- `payload_template` (object)
  - `layoutType` (enum)
  - `onClickLayoutType` (enum, optional)
  - `allowDismiss` (string: `"true"` / `"false"`)
  - `image` (optional `{ path, width?, height? }`)
  - `steps` (array of `{ title, body?, image? }`)
  - `buttons` (array of `{ label, action, color? }`)
    - `action` (object)
      - `type` (enum: `route`, `external`)
      - `route_key` (string, required if `route`)
      - `path_parameters` (object, required if `route`; must include all `path_params` keys with non-empty values)
      - `query_parameters` (object, optional; validated against route `query_params`)
      - `url` (string, required if `external`)
      - `open_mode` (enum: `in_app`, `external`, optional)
- `template_defaults` (object, per message variable defaults)
- `metrics` (object)
  - `sent_count` (int)
  - `accepted_count` (int)
  - `delivered_count` (int)
  - `opened_count` (int)
  - `clicked_count` (int)
  - `dismissed_count` (int)
  - `unique_opened_count` (int)
  - `unique_clicked_count` (int)
  - `unique_dismissed_count` (int)
  - `step_view_counts` (map<int,int>)
  - `button_click_counts` (map<string,int>)
- `created_at`, `updated_at`, `sent_at`, `archived_at`

**Field Definitions**
- `status`: `draft`, `scheduled`, `sent`, `archived`
- `audience.type`: `all`, `users`, `event`
- `audience.event_qualifier`: `event.confirmed`, `event.invited`, `event.all`, `event.sent_invites`
- `payload_template.layoutType`: `fullScreen`, `bottomModal`, `popup`, `actionButton`, `snackBar`
- `payload_template.onClickLayoutType`: `fullScreen`, `bottomModal`, `popup`, `actionButton`, `snackBar`
- `payload_template.buttons.action.type`: `route`, `external`
- `payload_template.buttons.action.open_mode`: `in_app`, `external`

**Available Routes (Tenant Settings)**
- `key` (string, unique within tenant settings)
- `path` (string, uses `:param` tokens, e.g. `/agenda/evento/:slug`)
- `path_params` (array, derived from `path`; stored for UI/validation)
- `query_params` (object, `key: validation_rule`, Laravel-style validation rules)

---

## Settings Schema (V1)

**Tenant Settings**
- `push_message_types` (array)
  - `key` (string, unique within tenant settings)
  - `label` (string)
  - `description` (string, optional)
  - `default_audience_type` (enum: `all`, `users`, `event`, optional)
  - `default_event_qualifier` (enum, optional)
  - `throttles` (object, optional)
- `push_message_routes` (array)
  - `key` (string, unique within tenant settings)
  - `path` (string, includes `:param` tokens)
  - `path_params` (array, derived from `path`)
  - `query_params` (object, `key: validation_rule`)

**Field Definitions**
- `default_event_qualifier`: `event.confirmed`, `event.invited`, `event.all`, `event.sent_invites`

---

## Push Message Data Response (V1)

```json
{
  "ok": true,
  "push_message_id": "string",
  "payload": { "..." : "push_handler payload" }
}
```

Payload is normalized to `push_handler` DTO keys (`title`, `body`, `allowDismiss`, `layoutType`, `onClickLayoutType`, `image`, `steps`, `buttons` with `routeType/routeInternal/routeExternal`).

When invalid/inactive:
```json
{ "ok": false, "reason": "inactive" }
```

**Field Definitions**
- `reason`: `inactive`, `expired`, `not_found`

---

## Push Message Actions (V1)

**Endpoint**
- `POST /api/v1/push/messages/{push_message_id}/actions`

**Action Payload**
- `action` (enum)
- `step_index` (int, required for all actions; use `0` for single-step messages)
- `button_key` (string, required for `clicked`)
- `device_id` (string, optional)
- `metadata` (object, optional)

**Field Definitions**
- `action`: `opened`, `clicked`, `dismissed`, `step_viewed`, `delivered` (`delivered` is device receipt)
```
