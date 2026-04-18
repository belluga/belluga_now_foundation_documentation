# Title
Account Profile Types Expansion

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
V1 intentionally keeps the account-profile registry narrow. Post-MVP expansion may introduce additional profile types such as influencer or curator, but those additions must arrive with explicit module expectations, taxonomy boundaries, and capability defaults instead of being treated as label-only variants.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this TODO preserves one deferred owner for profile-type expansion without mixing it into workspace, claim-flow, or current V1 registry decisions.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the deferred profile-type expansion boundary is already explicit and does not need broader initiative framing first.

## Contract Boundary
- This TODO defines the future expansion of account-profile types, their capabilities, allowed taxonomies, and registry/default migration expectations.
- It does not own workspace delivery, ownership claim flow, or a reversal of the V1 flat-registry decision unless explicitly re-approved later.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Deferred-Owner`
- **Next exact step:** freeze the target type matrix, default modules, capability rules, and migration posture before implementation approval is considered.

## Scope
- Define additional profile types and their default modules (UI + content surfaces).
- Define allowed taxonomies per type.
- Define capabilities per type (e.g., `is_favoritable`, `is_poi_enabled`, future capability flags).
- Update backend registry defaults and tenant settings migration plan for the new types.
- Update foundation docs (`domain_entities.md`, module docs, endpoints contracts) with new types.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app:<planned>`, `flutter-app:<planned>`, `foundation_documentation:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Account profile types deferred program owner | `pending` | `pending` | `pending` | `pending` | `Pending` |

**Owner:** Delphi
**Objective:** Define and deliver additional Account Profile types beyond V1 (e.g., influencer, curator) with explicit modules, taxonomies, and capabilities.

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

## Definition of Done
- [ ] ⚪ New types are documented with explicit modules, taxonomies, and capabilities.
- [ ] ⚪ Backend registry defaults and docs are aligned with the new types.
- [ ] ⚪ Migration/backfill plan exists for existing tenants.

## Validation Steps
- Manual doc review of registry + module alignment.
- Confirm `GET /api/v1/account_profile_types` returns new types in a test tenant.

## Decisions
- Registry remains flat (no `parent_type`) unless a future decision explicitly changes this.
