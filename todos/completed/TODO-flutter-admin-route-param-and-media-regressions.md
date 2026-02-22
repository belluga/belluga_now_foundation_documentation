# TODO (V1): Flutter Admin Route Param and Media Regression Hardening
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** Completed
**Owners:** Flutter
**Date:** 2026-02-17

## Objective
Resolve current tenant-admin regressions before final validation: route path parameter leakage (`:param` in browser URL), nullable bio update failures, and architecture enforcement gaps (AutoRoute-only navigation).

## Scope
- Flutter app (`flutter-app`) only.
- Route construction/navigation and tenant-admin edit flows.
- Architecture adherence enforcement updates related to navigation guardrails.
- New regression tests for route parameterized routes and media/crop ingestion behavior.

## Out of Scope
- Laravel runtime or infra changes (unless strictly needed and explicitly requested).
- New product features unrelated to identified regressions.

## Plan
- [x] ✅ Production-Ready Audit all admin route pushes and route definitions to guarantee parameterized paths are always resolved.
- [x] ✅ Production-Ready Fix account/profile detail/edit route flows where URL can retain literal `:accountSlug` / `:accountProfileId`.
- [x] ✅ Production-Ready Ensure account profile `bio` can be cleared without client-side/server-side contract break from Flutter payload shape.
- [x] ✅ Production-Ready Replace remaining non-AutoRoute navigation usage in test/support code where applicable and strengthen adherence checks to detect such usage.
- [x] ✅ Production-Ready Add/expand regression tests for parameterized route instantiation and URL/state restoration behavior.
- [x] ✅ Production-Ready Add/expand regression tests for image ingestion from device flow + fixed crop aspect contracts (`avatar 1:1`, `cover 16:9`).
- [x] ✅ Production-Ready Run full requested orchestration only at the end and iterate until clean.

## Definition of Done
- No known route placeholder leakage in admin detail/edit flows.
- `bio` clear/update behavior no longer errors for nullable/empty intent from Flutter side.
- AutoRoute-only navigation policy explicitly enforced and current touched scope compliant.
- Requested route/image regression tests present and passing.
- Final full suite run completed after fixes.
