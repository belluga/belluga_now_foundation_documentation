# TODO (V1): Flutter Account Single Source of Truth Sync + Adherence Rule
**Version:** 1.0
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** ✅ Production-Ready (approved + implemented)
**Owners:** Flutter

## Objective
Establish a clean, repository-owned synchronization pattern for tenant-admin accounts so account edits automatically propagate to list/detail consumers via canonical streams, and formalize this pattern in the Flutter architecture adherence skill for reuse across the codebase.

## References
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/flutter-clean-code-audit/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/workflows/flutter/create-controller-method.md`
- `delphi-ai/workflows/flutter/create-repository-method.md`
- `delphi-ai/workflows/flutter/create-screen-method.md`

## Scope
- Documentation-first update for tenant-admin screen behavior and architectural adherence expectations.
- Flutter repository contract + implementation additions to expose account watch-by-id derived from canonical repository stream.
- Tenant admin account detail/profile flow refactor to consume derived repository state (remove duplicated canonical account state ownership from `TenantAdminAccountProfilesController`).
- DI lifecycle update: make `TenantAdminAccountProfilesController` singleton in tenant-admin module registration.
- Update `flutter-architecture-adherence` skill with an explicit single-source-of-truth pattern rule for editable entities used by list + detail + form flows.
- Run architecture adherence scans + clean-code recursive audit until clean in touched scope (or explicitly approved exceptions).

## Out of Scope
- Laravel API/endpoint/schema changes.
- Non-tenant-admin feature refactors.
- UI redesign beyond behavior required for stream-driven sync.

## Definition of Done
- Repository contract provides watch-by-id capability for tenant-admin accounts without DTO leakage.
- Account edit operations update canonical repository stream once; list and detail views reflect the change without manual cross-controller sync calls.
- `TenantAdminAccountProfilesController` no longer owns duplicated canonical account state and relies on repository-derived stream behavior.
- `TenantAdminAccountProfilesController` registration is singleton for stable shared flow state.
- Architecture adherence skill explicitly documents this pattern for future implementations.
- `fvm flutter analyze` is clean.
- Targeted tenant-admin tests pass for changed behavior.
- `flutter-clean-code-audit` recursive loop ends clean, or remaining exceptions are explicitly approved by user and documented.

## Execution Plan
- [x] ✅ Production-Ready Update docs before code:
  - `foundation_documentation/screens/modulo_tenant_admin.md` with canonical stream sync expectation for account edit/list/detail.
  - If needed, add note in `foundation_documentation/submodule_flutter-app_summary.md` about the new repository-derived account sync pattern.
- [x] ✅ Production-Ready Add domain repository contract API for account stream derivation by id/slug (watch method) with clear semantics.
- [x] ✅ Production-Ready Implement repository watch helper(s) in `tenant_admin_accounts_repository.dart` using canonical `accountsStreamValue` as source of truth.
- [x] ✅ Production-Ready Refactor `TenantAdminAccountProfilesController` to remove duplicated canonical account ownership and consume repository-derived watch stream; preserve existing UX/loading/error semantics.
- [x] ✅ Production-Ready Update tenant-admin module DI registration to singleton for `TenantAdminAccountProfilesController`.
- [x] ✅ Production-Ready Update `delphi-ai/skills/flutter-architecture-adherence/SKILL.md` with explicit guidance:
  - list/detail/form must share repository canonical stream;
  - forms issue commands only;
  - detail derives from repository watchers (id preferred).
- [x] ✅ Production-Ready Run mandatory adherence scans and clean-code recursive audit:
  - `rg -n "Navigator\.of\(|Navigator\.(push|pop|maybePop|pushNamed|pushReplacement|popUntil)" lib test integration_test`
  - `rg -n "(cache|cached|Cache)" lib/presentation lib/infrastructure/repositories lib/domain test`
  - `fvm flutter analyze`
  - targeted tests for modified tenant-admin account flows.

## Validation Steps
- `fvm flutter analyze`
- `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_accounts_controller_test.dart`
- `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_account_profiles_controller_test.dart`
- `fvm flutter test test/presentation/tenant_admin/screens/tenant_admin_account_detail_screen_test.dart`
- `rg -n "Navigator\.of\(|Navigator\.(push|pop|maybePop|pushNamed|pushReplacement|popUntil)" lib test integration_test`
- `rg -n "(cache|cached|Cache)" lib/presentation lib/infrastructure/repositories lib/domain test`

## Questions To Close
- None currently.

## Decisions
- D1: Use repository-owned canonical stream as the single source of truth for account entity synchronization across list/detail/form flows.
- D2: Keep controller-level state only for ephemeral UI concerns (loading/error/form draft), not canonical entity duplication.
- D3: Prefer watch by stable identity (`id`) for entity sync; allow slug fallback only while route/input identity is unresolved.
