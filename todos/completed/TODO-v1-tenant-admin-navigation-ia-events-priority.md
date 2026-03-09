# TODO (V1): Tenant Admin Navigation IA — Events Priority + Type Management Access

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed
**Owner:** Flutter Team
**Date:** 2026-02-15

## Objective
Refine Tenant Admin information architecture to align with Material 3: prioritize `Eventos` in bottom navigation, remove overloaded `Catálogo` as a primary destination, and place type-management access (`Tipos de Perfil` / `Tipos de Ativo`) at contextual points where users need them.

## Scope
- Update shell bottom navigation destination order and labels:
  - `Início`
  - `Eventos`
  - `Contas`
  - `Ativos`
  - `Configurações`
- Remove `Catálogo` as a primary bottom destination.
- Ensure `Eventos` is directly reachable as a top-level destination.
- Add contextual access to management screens:
  - `Contas` list screen must provide entry point to `Tipos de Perfil`.
  - `Ativos` list screen must provide entry point to `Tipos de Ativo`.
- Keep existing create-shortcuts inside forms (`+ Criar tipo`, `+ Criar tipo de ativo`) and maintain reload-on-return behavior.
- Keep route contracts and domain/repository contracts unchanged.

## Out of Scope
- Visual redesign of all cards/forms beyond navigation/access points.
- Backend/API contract changes.
- Full IA rewrite of dashboard cards.

## Decisions
- [x] ✅ Production‑Ready Bottom navigation remains limited to high-frequency operational destinations only.
- [x] ✅ Production‑Ready Type management remains secondary/contextual (not top-level nav), accessible from list app bars and forms.
- [x] ✅ Production‑Ready Reuse existing routes for profile/static type management to avoid contract drift.

## Plan
### Phase 1 — Navigation Model Update
- [x] ✅ Production‑Ready Rebuild `_destinations` in `tenant_admin_shell_screen` to the new 5-item IA.
- [x] ✅ Production‑Ready Map route names so account/profile routes resolve under `Contas`, static asset/profile routes under `Ativos`, and events routes under `Eventos`.
- [x] ✅ Production‑Ready Keep full-screen sheet route behavior unchanged.

### Phase 2 — Contextual Type Management Entry Points
- [x] ✅ Production‑Ready Add app bar action/overflow in `Contas` list to open `Tipos de Perfil`.
- [x] ✅ Production‑Ready Add app bar action/overflow in `Ativos` list to open `Tipos de Ativo`.
- [x] ✅ Production‑Ready Preserve current create shortcuts in forms and list reload behavior after returning from type creation.

### Phase 3 — Validation
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready Run focused tests for shell navigation + affected list screens.
- [x] ✅ Production‑Ready Run `flutter-architecture-adherence` + recursive `flutter-clean-code-audit` until clean or explicit approved exceptions.

Validation Notes:
- `test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart` passed.
- `test/application/router/guards/landlord_route_guard_test.dart` passed.
- Device integration re-run passed on ADB (`192.168.15.5:5555`):
  - `integration_test/feature_admin_dashboard_events_hidden_test.dart`
  - `integration_test/admin_login_real_test.dart`

## Definition of Done
- [x] ✅ Production‑Ready Bottom navigation reflects `Início / Eventos / Contas / Ativos / Configurações`.
- [x] ✅ Production‑Ready `Catálogo` is no longer a bottom-level destination.
- [x] ✅ Production‑Ready `Contas` and `Ativos` expose direct, discoverable access to their respective type-management screens.
- [x] ✅ Production‑Ready Existing create shortcuts continue to work.
- [x] ✅ Production‑Ready Analyze and focused tests pass.
