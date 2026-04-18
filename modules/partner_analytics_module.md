# Documentation: Account Profile Analytics Module

**Version:** 0.1 (placeholder)  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

**Authority note (2026-04-18):** this file is a legacy-named planning surface for a future analytics capability, not a standalone current runtime authority. Current analytics/privacy rules live in source modules such as `invite_and_social_loop_module.md`, while future workspace-facing dashboards belong under `account_workspace` unless implementation later proves a distinct module boundary.

## 1. Purpose

This document retains open questions for future workspace-facing analytics, but it does not define a separate current module boundary. Analytics remains capability-first: ownership stays subordinate to source modules plus future `account_workspace` read surfaces unless implementation later justifies promotion to its own module.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/partner_catalog_and_offer_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/transaction_bridge_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`

## 2. Topics to Address (Future Session)

1. **Data Inputs:** clarify which aggregates come from Invite & Social Loop (invite_edges, quota snapshots), Transaction Bridge (bookings/purchase conversions), and Map/POI exposure metrics.
2. **Plan-Aware Dashboards:** define how account plan tiers influence which metrics are visible and which thresholds trigger upsell prompts.
3. **Quota Enforcement Feedback:** describe how rate-limit events (invite.plan-limit-reached) flow into the analytics UI and billing.
4. **Account Profile Workspace Integration:** specify APIs or event streams used by the future account profile workspace to fetch analytics in real time.
5. **Privacy & Multi-Tenant Boundaries:** ensure account operators only see data scoped to their tenant/plan, with anonymized global benchmarks if needed.
6. **Mock Strategy:** outline how mocked analytics data will be generated for the Flutter account workspace flavor before real services exist.

---

*Next steps:* do not expand this file into a standalone module spec by default. Re-home the still-valid workspace-facing analytics requirements under `account_workspace` and keep source-of-truth analytics/privacy contracts in their source modules, promoting to a dedicated module only if implementation proves the boundary.

## 3. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `PAN-01` | Approved | Module remains placeholder in MVP and is not a runtime authority yet. | Prevents premature contracts leaking into implementation. | Section `1` |
| `PAN-02` | Approved | Analytics ownership is aggregate-only and fed by invite/catalog/transaction modules. | Preserves clean source-of-truth boundaries. | Section `2` |
| `PAN-03` | Approved | This file represents a future analytics capability, not a default standalone module. Future analytics should stay subordinate to source modules plus `account_workspace` read surfaces unless implementation later proves a distinct bounded context. | Prevents fake standalone authority for analytics before a real bounded context exists while preserving the planned capability. | Sections `1`, `2` |

## 4. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-vnext-tenant-user-account-profile-area.md` | Workspace analytics dependency planning | In progress | `1.1`, `2` | Defines when analytics API contracts become mandatory. |
