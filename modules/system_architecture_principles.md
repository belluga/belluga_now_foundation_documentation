# Documentation: System Architecture Principles (Bóora! Platform)

**Version:** 1.0  
**Date:** March 8, 2025  
**Authors:** Belluga Engineering

## 1. Overview

This document defines the **project-specific architectural principles** for the Bóora! platform. These principles **extend** (do not replace) the agnostic core rules in `delphi-ai/system_architecture_principles.md`.

Bóora! is the platform. Tenant brands are hosted on the platform and must not be confused with the platform itself.

---

## 2. Scope & Source of Truth

1. **Agnostic Core (Mandatory):** `delphi-ai/system_architecture_principles.md` is the canonical baseline for all projects.
2. **Project-Specific Additions (This Document):** Any platform/tenant decisions unique to Bóora! live here.

---

## 3. Platform & Tenant Model

1. **Platform vs Tenant:** Bóora! operates as a multi-tenant platform. Each tenant has its own domain, branding, and content scope. A tenant brand is one tenant, not the platform itself.
2. **Tenant-Scoped APIs:** All tenant data is served via tenant-scoped APIs; landlord/global APIs remain separated by routing and authorization.
3. **Account vs Account Profile:** Accounts are the administrative permission boundary. Every Account has **exactly one Account Profile** (1:1). The Account Profile is the public identity surface. Public or tenant-facing labels may vary by product/copy decision, but the canonical internal model remains `Account + Account Profile + Profile Type`.
4. **Typed Profile Registry (WP‑like, not WP‑meta):** Account Profiles use a **Profile Type Registry** (similar to WordPress custom post types) that defines labels, allowed taxonomies, capabilities (e.g., `is_favoritable`, `is_inviteable`, `is_poi_enabled`), and default UI modules per type. **No inheritance is used in V1** (`parent_type` is omitted). **MVP registry types:** `personal`, `artist`, `venue`, `restaurant`, `experience_provider`. The model remains strongly typed—no freeform meta tables.
5. **Registry Fetch + Cache (Online‑First, No Hardcoded Fallback):** The registry is fetched from `/api/v1/environment.profile_types` at runtime and cached locally. The client boots from cache and refreshes asynchronously. **Hardcoded fallbacks are not allowed**; if no cache exists and fetch fails, the UI must surface an explicit error and avoid type‑dependent flows.
6. **Taxonomy Scope (No Inheritance):** Taxonomies apply only to the type they are declared on. If a taxonomy should apply to multiple types, it must be explicitly listed for each type.
7. **Organization (Optional, Grouping Only in MVP):** Organizations group **accounts belonging to the same real‑world entity** (tenant, sponsor, hotel group, multi‑location brand). Organizations are **optional**; most accounts will not belong to any org. MVP usage is grouping only (no memberships/billing yet). Example: one tenant organization may group multiple accounts operated under the same local umbrella brand.
8. **Ownership State (Single Flag):** Accounts carry a single conceptual `ownership_state`: `tenant_owned`, `unmanaged`, or `user_owned`. This is the canonical discriminator; `managed_by` is derived, not stored. **MVP note:** for tenant-admin manual onboarding (`POST /admin/api/v1/account_onboardings`), `ownership_state` is a required create intent (`tenant_owned|unmanaged`); read payloads continue returning the derived effective state. **Unmanaged accounts must be standalone** (no org). Tenant‑owned accounts may be standalone or grouped under an org. User‑owned accounts are typically standalone in MVP.
9. **Permissions + Action Context:** Account roles/ACL remain the permission boundary. **Account Profile actions require `account_profile_id`** in the request, but authorization is resolved through Account membership. This keeps boilerplate permissions intact while enforcing profile-specific context (invites, map, offers, push).
10. **Account Profile Location (Optional):** `account_profile.location` is **optional**. Only profiles with a valid geospatial location participate in geo indexes and map queries. Profiles without location must be ignored by geo filters and never block index creation.
11. **Personal Profiles (User‑Owned, MVP):** On **first authenticated identification** (login/register), the system **auto-creates** a `user_owned` Account with a **personal Account Profile** (private by default). This is the only user-owned account in MVP. Personal upgrades (influencer/artist/curator) are **type changes from `personal` to the target type**, not new accounts; this flow is post‑MVP. Contact discovery is a separate privacy axis from public profile visibility: personal profiles may remain discoverable by imported contacts through `discoverable_by_contacts` even when profile privacy is restrictive. The backend/default baseline is `discoverable_by_contacts=true`; a future privacy-settings surface may expose this control without changing the default matching contract.
12. **User Claims & Additional Business Accounts (Post‑MVP):** In later versions, users may **claim unmanaged accounts** or create additional **user_owned business accounts** (e.g., to manage an existing venue). This is **explicitly deferred** in MVP. When enabled, claiming transitions `ownership_state` from `unmanaged` → `user_owned` and keeps Account as the permission boundary.
13. **Billing/Plans (Post‑MVP Examples):** Plans are expected to align primarily at the **Account Profile** level, with optional Organization‑level billing later. Examples: a personal plan “No‑Ads Personal”, a venue plan “Pro Venue”, or a sponsor plan “Brand Campaign”. Organization‑level billing can aggregate multiple accounts (e.g., hotel group) without changing profile‑level entitlements.
14. **Project-Specific Implementation:** The **AccountProfile** model is implemented **within this project** (not upstream boilerplate). It remains a generic **1:1 identity unit** under Account with optional Organization grouping and `ownership_state`, but its contracts and behavior are owned here to avoid coupling other boilerplate consumers.

---

## 4. API & Data Access Principles (Project-Specific)

1. **Page-Based Lists + SSE Deltas:** All lists are page-based; realtime updates are delivered via SSE delta streams that never replace the list contract.
2. **Independent Requests for Home:** No aggregated home endpoint in MVP. The client composes home using independent requests (invites, agenda, discovery, map).
3. **Invite Attribution:** Share codes are attribution tokens. Authenticated share entry must first materialize a canonical invite edge via `/invites/share/{code}/materialize`; only then may the client call `/invites/{invite_id}/accept|decline`. Materialization records source = `share_url`, and uniqueness rules prevent duplicate invite issuance to the same person/event.
4. **Taxonomy-Ready Discovery:** Account profiles support multi-taxonomy terms (`{type, value}`), and discovery filters can target taxonomy terms in addition to categories/tags.

---

## 5. Client Architecture Addendum

1. **Repository Boundaries:** Controllers/services may coordinate multiple repositories, but repositories never call each other.
2. **Realtime Consumption:** Clients subscribe to SSE streams for deltas and reconcile against page-based caches.

---

## 6. Documentation Rules (Project-Specific)

1. **Naming:** Bóora! is the platform; tenant brands must be described as tenants, not as the platform.
2. **Module Docs:** Use tenant-accurate wording (e.g., “tenant app users”) unless describing tenant-specific UI or copy.
3. **Tenant-Specific Assets:** Marketing collateral and screen copy may remain tenant-specific.

---

## 7. Canonical Anchors

- Agnostic core architecture baseline:
  - `delphi-ai/system_architecture_principles.md`
- System-level project docs:
  - `foundation_documentation/project_mandate.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- Module-level consumers (non-exhaustive):
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/events_module.md`

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-store-release-android.md` | Cross-module Android release convergence | Completed | `3`, `4`, `5` | Historical Android publication orchestrator for the completed Android-first release milestone. |
| `TODO-v1-module-doc-consolidation-all-modules.md` | Module-first canonicalization program | Promoted | `6`, `7`, `8` | Completed in `foundation_documentation/todos/completed/`. |
