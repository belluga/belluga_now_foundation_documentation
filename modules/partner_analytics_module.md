# Documentation: Account Profile Analytics Module

**Version:** 0.1 (placeholder)  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

This document will eventually define the Account Profile Analytics module responsible for consolidating invite performance, offer conversions, and plan usage so account operators see actionable KPIs and the commercial engine can enforce quotas/upsells. It is currently a placeholder to capture open questions and ensure we revisit the topic.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/partner_admin_module.md`
  - `foundation_documentation/modules/partner_catalog_and_offer_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/transaction_bridge_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`

## 2. Topics to Address (Future Session)

1. **Data Inputs:** clarify which aggregates come from Invite & Social Loop (invite_edges, quota snapshots), Transaction Bridge (bookings/purchase conversions), and Map/POI exposure metrics.
2. **Plan-Aware Dashboards:** define how account plan tiers influence which metrics are visible and which thresholds trigger upsell prompts.
3. **Quota Enforcement Feedback:** describe how rate-limit events (invite.plan-limit-reached) flow into the analytics UI and billing.
4. **Account Profile Workspace Integration:** specify APIs or event streams used by the future account profile workspace to fetch analytics in real time.
5. **Privacy & Multi-Tenant Boundaries:** ensure account operators only see data scoped to their tenant/plan, with anonymized global benchmarks if needed.
6. **Mock Strategy:** outline how mocked analytics data will be generated for the Flutter account workspace flavor before real services exist.

---

*Next steps:* Revisit this document during the Account Profile Workspace planning phase to turn these bullet points into a full module spec (Overview, Principles, Schemas, APIs, Events).

## 3. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `PAN-01` | Approved | Module remains placeholder in MVP and is not a runtime authority yet. | Prevents premature contracts leaking into implementation. | Section `1` |
| `PAN-02` | Approved | Analytics ownership is aggregate-only and fed by invite/catalog/transaction modules. | Preserves clean source-of-truth boundaries. | Section `2` |

## 4. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-tenant-user-account-profile-area.md` | Workspace analytics dependency planning | In progress | `1.1`, `2` | Defines when analytics API contracts become mandatory. |
