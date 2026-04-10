# TODO (V1): App Promotion Screen Branding and Adaptive Store Selection

**Status:** Completed
**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Secondary Module Anchor:** `foundation_documentation/policies/web_to_app_promotion_policy.md`
**Complexity:** small
**Checkpoint Policy:** consolidated review before approval

## 1. Context
The canonical web identity boundary route (`/baixe-o-app`) is already implemented and wired to `/open-app` with explicit `platform_target=android|ios` support. The current screen is functionally correct but visually generic and does not consume runtime tenant/app branding.

The next step is to align the promotion surface with the approved product direction:
- content closer to the approved dialog concept (but still a full route/screen, not a modal),
- top icon pulled from environment/app branding,
- app name pulled from `AppData.nameValue`, not hardcoded,
- adaptive store selection on web: when browser platform can be inferred as Android/iOS, prefer the corresponding store badge; otherwise show both badges.

## 2. Scope
In scope:
- `lib/presentation/shared/promotion/screens/app_promotion_screen/**`
- `lib/presentation/shared/promotion/routes/app_promotion_route.dart` only if required for screen parameter flow
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- promotion widget/screen tests

Out of scope:
- backend `/open-app` contract changes
- new telemetry events
- app-store URLs or package/deferred deep-link resolver changes
- modal resurrection or web auth changes

## 3. Decision Baseline (Frozen)
- `D-01` The canonical promotion surface remains a route/screen, not a dialog.
- `D-02` The top branding asset must use environment-provided app icon (`mainIconLightUrl` / `mainIconDarkUrl`) with runtime fallback handling; the app name must use `AppData.nameValue`.
- `D-03` The promotion copy must stay explicit about app-only capabilities and be visually closer to the approved dialog layout (icon top, title, clear benefit bullets, primary store CTA area, dismiss action).
- `D-04` On web, platform detection is best-effort only. If the browser platform is inferred as Android, show Android store CTA only. If inferred as iOS, show iOS CTA only. If not reliably inferred (desktop/unknown), show both CTAs.
- `D-05` When both badges are shown, Apple-provided App Store badge/artwork must remain first in visual order and must not be recreated or redrawn.
- `D-06` No hardcoded app name or hardcoded tenant branding is allowed in the promotion screen.
- `D-07` Existing `/open-app` explicit `platform_target` semantics remain unchanged; the UI only decides which CTA(s) to render.

## 4. Decision Consistency Gate
| Decision | Relevant Prior Decision | Handling | Notes |
|---|---|---|---|
| `D-01` | `TODO-v1-web-to-app-policy.md` `W2A-D08` | Preserve | Keep canonical route-based promotion surface. |
| `D-04` | `TODO-v1-web-to-app-policy.md` `W2A-D09` | Supersede (Intentional) | `W2A-D09` required explicit Android+iOS support. This TODO refines presentation behavior to adaptive single-store rendering when platform is inferable, while preserving explicit `platform_target` selection semantics and dual-badge fallback. |
| `D-05` | `web_to_app_promotion_policy.md` Apple badge ordering rule | Preserve | Apple badge stays first when multi-badge layout is rendered. |
| `D-07` | `endpoints_mvp_contracts.md` `/open-app` | Preserve | No backend contract change. |

## 5. Plan
1. Update authoritative docs/policy wording for adaptive badge rendering and runtime branding.
2. Refactor `AppPromotionScreenController` to expose:
   - runtime app display name,
   - runtime icon URI,
   - best-effort preferred web platform target.
3. Rework `AppPromotionScreen` layout to align with approved content hierarchy.
4. Add/update widget tests for:
   - runtime app name rendering,
   - adaptive badge rendering behavior,
   - dual-badge fallback ordering.
5. Run targeted tests + `fvm dart analyze --format machine`.

## 6. Risks / Notes
- Browser platform inference is heuristic, not authoritative. The fallback must remain dual-badge.
- The screen should not depend on hardcoded tenant strings or static icon assets for product branding.
- Keep the scope presentation-only; do not reopen route-guard or backend resolver logic.

## 7. Rule / Workflow Sources Used
- Implementation followed these sources:
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## 8. Decision Adherence Validation
| Decision | Status | Evidence |
|---|---|---|
| `D-01` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart` |
| `D-02` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart`, `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` |
| `D-03` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart` |
| `D-04` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_web_store_platform_resolver_web.dart`, `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` |
| `D-05` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`, `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` |
| `D-06` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart` |
| `D-07` | Adherent | `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart`, `test/presentation/shared/widgets/app_promotion_dialog_test.dart`, `test/application/router/support/route_redirect_path_test.dart` |

## 9. Validation
- `fvm flutter test test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart test/application/router/guards/auth_route_guard_test.dart test/application/router/support/route_redirect_path_test.dart test/presentation/shared/widgets/app_promotion_dialog_test.dart`
- `fvm dart analyze --format machine`
