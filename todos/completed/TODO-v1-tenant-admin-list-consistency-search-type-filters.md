# TODO (V1): Tenant Admin Ownership + Navigation Consistency

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed
**Owner:** Flutter Team
**Date:** 2026-02-16

## Objective
Establish a consistent, future-proof Tenant Admin behavior for:
- Ownership semantics (`tenant_owned`, `unmanaged`, `user_owned`) as backend source of truth.
- Account creation intent (explicit ownership selection for landlord admin).
- Navigation interaction pattern parity across list domains (Contas, Ativos, Tipos, Taxonomias/Termos), aligned with Material 3.

## Scope
- Foundation docs alignment:
  - Clarify Account as ownership source of truth and AccountProfile as 1:1 projection of ownership.
  - Align endpoint contracts for ownership presence in list/detail responses.
- Laravel/API:
  - Add explicit create intent for account ownership in admin create flow (`tenant_owned` or `unmanaged`).
  - Ensure ownership invariants are enforced server-side.
  - Return `ownership_state` in account list/detail.
  - Return `ownership_state` in account profile list for contract consistency.
- Flutter:
  - Account create form: explicit ownership selector (`tenant_owned` / `unmanaged`).
  - Account repository/DTO/controller wiring for ownership create + read.
  - Remove silent ownership fallback behavior.
  - Navigation parity:
    - `Contas`: keep card tap -> detail -> explicit edit.
    - `Ativos`: migrate card tap from direct edit to detail/context first, edit explicit.
    - `Tipos`: migrate card tap from direct edit to detail/context first, edit explicit.
    - `Taxonomias`: keep taxonomy card tap -> terms.
    - `Termos`: migrate card tap from direct edit to explicit edit action.

## Out of Scope
- Post-MVP claim flow for unmanaged accounts.
- Introducing a new ownership enum value (e.g., `landlord_owned`).
- Full IA redesign beyond the navigation consistency listed in Scope.

## Decisions
- [x] ✅ Production‑Ready Ownership source of truth is Account; AccountProfile only mirrors ownership in read models.
- [x] ✅ Production‑Ready Landlord admin create flow must explicitly choose `tenant_owned` or `unmanaged`; `user_owned` is not selectable there.
- [x] ✅ Production‑Ready Card tap behavior follows context/detail-first pattern; edit is explicit action.
- [x] ✅ Production‑Ready Flutter must not silently coerce missing ownership to `tenant_owned`.

## Plan
### Phase 1 — Contract + Ownership Foundation
- [x] ✅ Production‑Ready Update docs contracts (`tenant_admin_module`, `endpoints_mvp_contracts`) to include ownership behavior and list payload alignment.
- [x] ✅ Production‑Ready Implement Laravel ownership derivation reuse and include `ownership_state` in accounts list/detail responses.
- [x] ✅ Production‑Ready Implement explicit ownership create intent and invariants in Laravel account create flow.
- [x] ✅ Production‑Ready Ensure account profile list payload includes `ownership_state` for consistency.

### Phase 2 — Flutter Ownership + Form Wiring
- [x] ✅ Production‑Ready Add account ownership selector to Tenant Admin account create form (landlord flow).
- [x] ✅ Production‑Ready Wire repository/domain/controller payload for create ownership intent.
- [x] ✅ Production‑Ready Remove silent ownership fallback in Flutter account mapping logic.
- [x] ✅ Production‑Ready Ensure UI error handling remains explicit if ownership contract is missing/invalid.

### Phase 3 — Navigation Pattern Consistency
- [x] ✅ Production‑Ready `Ativos`: card tap -> detail/context route; edit via explicit action.
- [x] ✅ Production‑Ready `Tipos de Perfil`: card tap -> detail/context route; edit via explicit action.
- [x] ✅ Production‑Ready `Tipos de Ativo`: card tap -> detail/context route; edit via explicit action.
- [x] ✅ Production‑Ready `Taxonomias`: keep card tap -> terms list.
- [x] ✅ Production‑Ready `Termos`: remove direct edit on card tap; edit explicit action only.

### Phase 4 — Validation + Adherence
- [x] ✅ Production‑Ready Run focused Laravel tests for accounts/account_profiles ownership behavior.
- [x] ✅ Production‑Ready Run focused Flutter unit/widget tests for ownership and navigation updates.
- [x] ✅ Production‑Ready Run integration tests in small chunks (WSL-safe), one by one.
- [x] ✅ Production‑Ready Run `flutter-architecture-adherence` and finish with recursive `flutter-clean-code-audit` until clean or explicit approved exceptions.
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.

## Definition of Done
- [x] ✅ Production‑Ready Ownership create intent is explicit and validated server-side.
- [x] ✅ Production‑Ready `ownership_state` is present and consistent in account/account_profile read contracts.
- [x] ✅ Production‑Ready Flutter ownership handling is explicit (no silent fallback).
- [x] ✅ Production‑Ready Navigation behavior is consistent across Contas/Ativos/Tipos/Taxonomias/Termos.
- [x] ✅ Production‑Ready Analyze/tests/adherence checks are green (or explicit approved exceptions documented).
