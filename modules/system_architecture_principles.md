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
3. **Account vs Account Profile (Partner Label):** Accounts are generic administrative containers. Account Profiles are first-class domain models stored separately and linked to `account_id` (see 1:N rule below). “Partner” is a tenant-facing label applied to Account Profiles.
4. **Account Profiles (1:N) with Rationale:** Tenants may issue **multiple account profiles** under a single account (1:N). This is required to support real-world operators such as sponsors with multiple storefronts or restaurants with multiple units without forcing them into a single public identity. It preserves profile-level branding, location context, and attribution for invites/push while keeping the account as the administrative/permission boundary. As a result, account-profile-facing push messages must always bind to a specific `account_profile_id` rather than relying on account-only context.
5. **Account Profile Location (Optional):** `account_profile.location` is **optional**. Only profiles with a valid geospatial location participate in geo indexes and map queries. Profiles without location must be ignored by geo filters and never block index creation.
6. **User → Influencer Upgrade Path (MVP/Admin-Assigned):** When a user upgrades to influencer, the system creates an **Account** plus an **Account Profile** (`profile_type = influencer`) and links the user as the operator. In MVP this is admin-assigned; self‑serve upgrade flows can be added later without changing the underlying model.
7. **Boilerplate Requirement:** The **AccountProfile** model is intended to live in the boilerplate as a generic 1:N identity unit under Account. Projects define the allowed `profile_type` values in their own documentation and do not fork the boilerplate model for domain-specific fields.

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
