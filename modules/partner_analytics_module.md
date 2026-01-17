# Documentation: Account Profile Analytics Module

**Version:** 0.1 (placeholder)  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

This document will eventually define the Account Profile Analytics module responsible for consolidating invite performance, offer conversions, and plan usage so account operators see actionable KPIs and the commercial engine can enforce quotas/upsells. It is currently a placeholder to capture open questions and ensure we revisit the topic.

## 2. Topics to Address (Future Session)

1. **Data Inputs:** clarify which aggregates come from Invite & Social Loop (invite_edges, quota snapshots), Transaction Bridge (bookings/purchase conversions), and Map/POI exposure metrics.
2. **Plan-Aware Dashboards:** define how account plan tiers influence which metrics are visible and which thresholds trigger upsell prompts.
3. **Quota Enforcement Feedback:** describe how rate-limit events (invite.plan-limit-reached) flow into the analytics UI and billing.
4. **Account Profile Workspace Integration:** specify APIs or event streams used by the future account profile workspace to fetch analytics in real time.
5. **Privacy & Multi-Tenant Boundaries:** ensure account operators only see data scoped to their tenant/plan, with anonymized global benchmarks if needed.
6. **Mock Strategy:** outline how mocked analytics data will be generated for the Flutter account workspace flavor before real services exist.

---

*Next steps:* Revisit this document during the Account Profile Workspace planning phase to turn these bullet points into a full module spec (Overview, Principles, Schemas, APIs, Events).
