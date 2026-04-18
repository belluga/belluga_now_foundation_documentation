# TODO (VNext): Account Profile Types Expansion

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owner:** Delphi  
**Objective:** Define and deliver additional Account Profile types beyond V1 (e.g., influencer, curator) with explicit modules, taxonomies, and capabilities.

---

## Scope (VNext)
- Define additional profile types and their default modules (UI + content surfaces).
- Define allowed taxonomies per type.
- Define capabilities per type (e.g., `is_favoritable`, `is_poi_enabled`, future capability flags).
- Update backend registry defaults and tenant settings migration plan for the new types.
- Update foundation docs (`domain_entities.md`, module docs, endpoints contracts) with new types.

## Out of Scope
- Changing the V1 decision to keep the registry flat (no inheritance).
- Memberships/roles, account claim flow, or account workspace features.

---

## A) Definition Tasks
- [ ] ⚪ List new types and their purpose:
  - Influencer
  - Curator
  - Market / Fair / Mall (if needed)
- [ ] ⚪ Specify default UI modules for each new type.
- [ ] ⚪ Specify allowed taxonomies for each type.
- [ ] ⚪ Specify capabilities for each type.

## B) Backend + Docs Sync
- [ ] ⚪ Update profile type registry defaults to include new types.
- [ ] ⚪ Define tenant settings migration/backfill plan.
- [ ] ⚪ Update relevant foundation docs + endpoint contracts.

---

## Acceptance Criteria
- [ ] ⚪ New types are documented with explicit modules, taxonomies, and capabilities.
- [ ] ⚪ Backend registry defaults and docs are aligned with the new types.
- [ ] ⚪ Migration/backfill plan exists for existing tenants.

## Validation Steps
- Manual doc review of registry + module alignment.
- Confirm `GET /api/v1/account_profile_types` returns new types in a test tenant.

## Decisions
- Registry remains flat (no `parent_type`) unless a future decision explicitly changes this.
