# Documentation: System Architecture Principles (Bóora! Platform)

**Version:** 1.0  
**Date:** March 8, 2025  
**Authors:** Belluga Engineering

## 1. Overview

This document defines the **project-specific architectural principles** for the Bóora! platform. These principles **extend** (do not replace) the agnostic core rules in `delphi-ai/system_architecture_principles.md`.

Bóora! is the platform. Guar[APP]ari is a **tenant** hosted on the platform and serves as the first reference implementation.

---

## 2. Scope & Source of Truth

1. **Agnostic Core (Mandatory):** `delphi-ai/system_architecture_principles.md` is the canonical baseline for all projects.
2. **Project-Specific Additions (This Document):** Any platform/tenant decisions unique to Bóora! live here.

---

## 3. Platform & Tenant Model

1. **Platform vs Tenant:** Bóora! operates as a multi-tenant platform. Each tenant has its own domain, branding, and content scope. Guar[APP]ari is one tenant, not the platform itself.
2. **Tenant-Scoped APIs:** All tenant data is served via tenant-scoped APIs; landlord/global APIs remain separated by routing and authorization.
3. **Account vs Account Profile (Partner Label):** Accounts are the administrative permission boundary. Every Account has **exactly one Account Profile** (1:1). The Account Profile is the public identity surface; “Partner” remains a tenant-facing label applied to Account Profiles (for B2B providers).
4. **Typed Profile Registry (WP‑like, not WP‑meta):** Account Profiles use a **Profile Type Registry** (similar to WordPress custom post types) that defines labels, hierarchy (`parent_type`), allowed taxonomies, capabilities (e.g., `is_favoritable`, `is_poi_enabled`), and default UI modules per type. The model remains strongly typed—no freeform meta tables.
5. **Registry Fetch + Cache (Online‑First, No Hardcoded Fallback):** The registry is fetched from tenant settings at runtime and cached locally. The client boots from cache and refreshes asynchronously. **Hardcoded fallbacks are not allowed**; if no cache exists and fetch fails, the UI must surface an explicit error and avoid type‑dependent flows.
6. **Taxonomy Inheritance (Strict):** Taxonomies apply to a type **and all its descendants**. Children may **add** taxonomies but never remove or override parent behavior. If a taxonomy should only apply to certain subtypes (e.g., restaurants), attach it to the child type, not the parent.
7. **Organization (Optional, Grouping Only in MVP):** Organizations group **accounts belonging to the same real‑world entity** (tenant, sponsor, hotel group, multi‑location brand). Organizations are **optional**; most accounts will not belong to any org. MVP usage is grouping only (no memberships/billing yet). Example: Tenant org “Guarappari” groups `Guarappari` + `Tiggro` accounts.
8. **Ownership State (Single Flag):** Accounts carry a single conceptual `ownership_state`: `tenant_owned`, `unmanaged`, or `user_owned`. This is the canonical discriminator; `managed_by` is derived, not stored. **MVP note:** `ownership_state` is derived (not required in payload/response). **Unmanaged accounts must be standalone** (no org). Tenant‑owned accounts may be standalone or grouped under an org. User‑owned accounts are typically standalone in MVP.
9. **Permissions + Action Context:** Account roles/ACL remain the permission boundary. **Account Profile actions require `account_profile_id`** in the request, but authorization is resolved through Account membership. This keeps boilerplate permissions intact while enforcing profile-specific context (invites, map, offers, push).
10. **Account Profile Location (Optional):** `account_profile.location` is **optional**. Only profiles with a valid geospatial location participate in geo indexes and map queries. Profiles without location must be ignored by geo filters and never block index creation.
11. **Personal Profiles (User‑Owned, MVP):** On **first authenticated identification** (login/register), the system **auto-creates** a `user_owned` Account with a **personal Account Profile** (private by default). This is the only user-owned account in MVP. Personal upgrades (influencer/artist/curator) are **type changes within the personal tree**, not new accounts; this flow is post‑MVP.
12. **User Claims & Additional Business Accounts (Post‑MVP):** In later versions, users may **claim unmanaged accounts** or create additional **user_owned business accounts** (e.g., to manage an existing venue). This is **explicitly deferred** in MVP. When enabled, claiming transitions `ownership_state` from `unmanaged` → `user_owned` and keeps Account as the permission boundary.
13. **Billing/Plans (Post‑MVP Examples):** Plans are expected to align primarily at the **Account Profile** level, with optional Organization‑level billing later. Examples: a personal plan “No‑Ads Personal”, a venue plan “Pro Venue”, or a sponsor plan “Brand Campaign”. Organization‑level billing can aggregate multiple accounts (e.g., hotel group) without changing profile‑level entitlements.
14. **Project-Specific Implementation:** The **AccountProfile** model is implemented **within this project** (not upstream boilerplate). It remains a generic **1:1 identity unit** under Account with optional Organization grouping and `ownership_state`, but its contracts and behavior are owned here to avoid coupling other boilerplate consumers.

---

## 4. API & Data Access Principles (Project-Specific)

1. **Page-Based Lists + SSE Deltas:** All lists are page-based; realtime updates are delivered via SSE delta streams that never replace the list contract.
2. **Independent Requests for Home:** No aggregated home endpoint in MVP. The client composes home using independent requests (invites, agenda, discovery, map).
3. **Invite Attribution:** Share codes are attribution tokens. Accepting via `/invites/share/{code}/accept` must record source = `share_url`, and uniqueness rules prevent duplicate invite issuance to the same person/event.
4. **Taxonomy-Ready Discovery:** Account profiles (partner label) support multi-taxonomy terms (`{type, value}`), and discovery filters can target taxonomy terms in addition to categories/tags.

---

## 5. Client Architecture Addendum

1. **Repository Boundaries:** Controllers/services may coordinate multiple repositories, but repositories never call each other.
2. **Realtime Consumption:** Clients subscribe to SSE streams for deltas and reconcile against page-based caches.

---

## 6. Documentation Rules (Project-Specific)

1. **Naming:** Bóora! is the platform; Guar[APP]ari is a tenant.
2. **Module Docs:** Use tenant-accurate wording (e.g., “tenant app users”) unless describing tenant-specific UI or copy.
3. **Tenant-Specific Assets:** Marketing collateral and screen copy may remain tenant-specific.
