# Documentation: Domain Entities
**Version:** 1.3

## 1. Purpose

This document defines the canonical business entities for the current Bóora! project state. It should answer four questions:

- which nouns are truly first-class in the current domain;
- which states and relations are globally important;
- which records are registries or derived projections rather than primary business entities;
- which ideas remain strategic or module-level and therefore must not be treated as already-canonical entities.

The domain must support recurring tenant bootstrap and cold-start operations without changing entity semantics across tenants.

**Evidence priority:** `project_constitution.md`, `project_mandate.md`, Laravel tenant/landlord models, request contracts, and package integration boundaries are the primary evidence for this document. Flutter `lib/domain/**` is useful supporting evidence only.

**Flutter domain boundary:** models under `lib/domain/**` do not automatically become canonical entities. Projections, resumes, helpers, and view carriers may live there for client-architecture reasons, but they must not redefine the business model. Examples such as `FavoriteResume`, invite friend resumes, and `CityPoiModel` are consumer/read models, not new top-level domain entities.
Current mismatches between this canonical business model and the present Flutter `lib/domain/**` topology must be treated as implementation debt to be normalized later, not as approved target architecture.

## 2. Modeling Rules

- This document is semantic first, not a schema dump.
- Persistent business entities, registries, derived projections, and audit records are intentionally separated.
- Strategic umbrella labels such as `partner`, `offering`, and `transaction` must not be used as if they were already implemented first-class entities when the current codebase still models more specific concrete nouns.
- Detailed payload fields, endpoint envelopes, and screen-level projections belong in module docs and API contracts.

## 3. Canonical Primary Entities

### 3.1 Tenant

Tenant is the top-level business/runtime boundary for public discovery, tenant admin, branding, domains, app-link identifiers, settings, and data partitioning. All tenant-side entities below are tenant-scoped unless explicitly stated otherwise.

### 3.2 User

User is the human principal used by identity, attendance, invites, favorites, and people discovery. A user may move through anonymous, identified, and authenticated states without changing entity type. User identity is distinct from tenant-operated accounts, even when later claim/self-management flows connect the two.

### 3.3 Account

Account is the tenant-scoped operational principal that owns permissions, roles, and a public-facing profile surface.

Key invariants:

- An Account may optionally belong to an Organization.
- An Account owns exactly one Account Profile in the current canonical model; this is enforced in the tenant database by a unique `account_id` on `account_profiles`.
- `ownership_state` is the global ownership invariant:
  - `tenant_owned`: official tenant-operated account.
  - `unmanaged`: tenant-seeded/bootstrap supply with no self-managing user operator yet.
  - `user_owned`: canonical self-managed/claimed state; valid in the business model even though current tenant-admin create intent is still limited to `tenant_owned|unmanaged`.
- `unmanaged` is the recurring cold-start state and is not an exceptional workaround.
- Unmanaged accounts must remain standalone and cannot stay attached to an Organization.

### 3.4 Account Profile

Account Profile is the canonical public/admin/catalog identity of an Account. It is the main profile substrate used across discovery, public profile pages, favorites, map projections, event relations, and future people/account-workspace semantics.

Key invariants:

- It carries `profile_type`, `display_name`, `slug`, media, visibility, activity state, verification state, taxonomy terms, and optional location.
- `profile_type` is registry-driven, not a hardcoded app enum.
- When the selected profile type is POI-enabled, location becomes mandatory.
- Legacy `partner` terminology must not be treated as a current canonical noun. The current model is Account + Account Profile + registry-driven Profile Type.

### 3.5 Organization

Organization is an optional grouping entity for Accounts that belong to the same real-world operator umbrella, sponsor group, or multi-location brand.

Key invariants:

- It is optional by design; most Accounts may remain standalone.
- It groups Accounts, not Users.
- It must not be used to hide or bypass Account ownership rules.

### 3.6 Static Asset

Static Asset is the tenant-managed non-account place/page entity used for beaches, landmarks, institutional pages, and other curated POIs that are not operator accounts.

Key invariants:

- It uses the Static Profile Type registry, not the Account Profile Type registry.
- It may project to map discovery when POI-enabled.
- It does not carry operator, favorite, or invite semantics by itself.

### 3.7 Event

Event is the currently implemented experience/activation entity in the runtime. It is the concrete current form of the broader strategic "offering" idea.

Key invariants:

- It owns title, content, type, publication state, classification, capabilities, and location semantics.
- `location.mode` is currently modeled as `physical|online|hybrid`.
- Event-to-profile relationships are expressed through `place_ref`, `venue`, and `event_parties`, rather than by inventing a separate venue/host root entity.
- Event publication is part of the canonical event model and affects public visibility, invite eligibility, and occurrence resolution.

### 3.8 Event Occurrence

Event Occurrence is the scheduled materialization of an Event.

Key invariants:

- An Event may have one or many occurrences.
- Occurrences carry the time-specific/publication-ready schedule used by agenda, map, and invite targeting.
- When an Event has multiple occurrences, invite targeting must resolve a specific `occurrence_id`; the invite domain cannot silently guess.

### 3.9 Attendance Commitment

Attendance Commitment is the user's participation intent/entitlement for an Event or Event Occurrence.

Key invariants:

- It is separate from social invite acceptance.
- It is unique per `user_id + event_id + occurrence_id`.
- Its source-of-truth state lives here, not inside invite edges or view projections.

### 3.10 Invite Edge

Invite Edge is the canonical social invitation record for an Event/Occurrence target and a recipient principal.

Key invariants:

- It binds inviter principal, issuing user, target event snapshot, recipient user/contact target, status, expiry, and response timestamps.
- It records social conversion state; it does not by itself become reservation, attendance confirmation, or check-in proof.
- Recipient addressing may use a canonical user id or a hashed contact target.

### 3.11 Favorite Edge

Favorite Edge is the unilateral favorite relationship between a user and a registered favoritable target.

Key invariants:

- It is registry-backed; the current project's main target is Account Profile.
- Reciprocal favorites between personal profiles derive the product-level `friend` semantics.
- Favorites on non-personal profiles remain affinity/bookmark signals and do not automatically become friendship.

### 3.12 Contact Hash Directory

Contact Hash Directory is the hashed-contact matching record used for invite/social discovery without storing raw address-book identifiers.

Key invariants:

- It is tenant-scoped and viewer-scoped through the importing user.
- It enables `contact_match` acquisition logic without raw PII persistence.
- It is an enabling entity for people discovery and invite targeting, not a user-visible profile entity by itself.

## 4. Canonical Registry Entities

### 4.1 Account Profile Type

Tenant-configured registry that defines Account Profile labels, pluralization, allowed taxonomies, visuals, and capabilities such as `is_favoritable`, `is_poi_enabled`, `has_avatar`, `has_cover`, `has_content`, and `has_events`. This registry governs Account Profile behavior; it does not create subtype inheritance.

### 4.2 Static Profile Type

Tenant-configured registry that defines Static Asset labels, map category, visuals, allowed taxonomies, and capabilities such as `is_poi_enabled`, `has_bio`, `has_cover`, and `has_content`.

### 4.3 Taxonomy

Tenant-configured classification vocabulary with `applies_to` scope. Taxonomies declare which entity families may use their terms.

### 4.4 Taxonomy Term

Concrete term inside a Taxonomy. Terms are the actual typed labels attached to Account Profiles, Static Assets, and Events.

### 4.5 Event Type

Tenant-configured registry for event classification and visual identity. It is a classifier for Events, not a replacement for the Event entity itself.

## 5. Derived or Operational Canonical Records

These records are real and important, but they are not the primary business nouns that modules should treat as root aggregates.

### 5.1 Map POI

Map POI is a derived discovery projection keyed by `ref_type + ref_id`.

Key invariants:

- It is projected from Account Profiles, Static Assets, and Events.
- It is not the source of truth for those entities.
- Map filtering, geospatial lookup, and "happening now" behaviors may depend on it, but semantic ownership stays with the source entity.

### 5.2 Principal Social Metric

Principal Social Metric is the aggregate metrics record keyed by `principal_kind + principal_id`.

Key invariants:

- It is a score/projection surface, not the principal entity itself.
- It summarizes invite-driven social metrics and should not be treated as the canonical source of relationship state.

### 5.3 Favorite Snapshots and Invite Feed Projections

Favorite snapshots, account-profile favoritable snapshots, and invite feed projections are derived read models built for efficient client/runtime consumption. They must not redefine Account Profile, Favorite Edge, or Invite Edge semantics.

### 5.4 Invite Share Code

Invite Share Code is a continuation artifact used by external share and web-to-app/app-to-app invite flows.

Key invariants:

- It carries inviter principal + target event/occurrence context behind a shareable code.
- It supports continuation and attribution, but it does not replace Invite Edge as the canonical social relationship record.

### 5.5 Identity Merge Audit and Merged Account Snapshot

Identity merge audit records and merged account snapshots are audit/support artifacts for identity reconciliation. They are authoritative evidence records, but not product-facing primary aggregates.

## 6. Cross-Entity States and Relations

### 6.1 Bootstrap and Ownership

- `unmanaged` remains the canonical cold-start/bootstrap state for seeded local supply.
- Tenant-admin create/update flows currently accept explicit ownership intent only as `tenant_owned|unmanaged`.
- `user_owned` remains part of the business model because claim/self-management is a real project direction, but it should not be documented as already available everywhere.

### 6.2 Social Relationship Semantics

- `contact_match` is the acquisition relation produced by hashed-contact reconciliation.
- `favorite` is the explicit approval/bookmark relation.
- `friend` is a derived mutual relation across personal-profile favorites, not a separate root entity.
- Viewer-scoped people surfaces may expose relation tags such as `contact_match`, `favorite_by_you`, `favorited_you`, and `friend`; those are relationship states, not new entities.

### 6.3 Exposure and Discovery

- Viewer exposure levels such as `aggregate_only`, `capped_profile`, and `full_profile` are access semantics over User/Account Profile surfaces.
- Discovery/privacy flags such as `privacy_mode` and `discoverable_by_contacts` are global social semantics and must remain separate from favorite/friend state.
- These rules belong to the relationship/exposure layer; they do not create separate domain entities.

### 6.4 Location and POI Semantics

- POI capability is registry-driven for both Account Profiles and Static Assets.
- Location is required when the relevant registry says the entity is POI-enabled.
- Map POI remains a projection; the source entity remains the authority.

### 6.5 Invite vs Attendance Semantics

- Invite acceptance is social conversion.
- Attendance Commitment is participation intent/entitlement.
- Check-in/presence proof is a downstream participation concern and must not be documented as already-canonical source-of-truth state unless the concrete persisted entity lands in code and contracts.

## 7. Non-Canonical or Deferred Concepts

The following ideas are still meaningful, but they should not be treated as current first-class root entities in this document unless code/contracts later promote them:

- `partner` as a standalone base entity, or even as preferred current product terminology: this is legacy residue from an older model and should be retired in favor of Account, Account Profile, and Profile Type language during future VNext/module cleanup.
- `offering` as a current implemented aggregate family: today the concrete implemented offering entity is `Event`.
- `transaction` as a unified current aggregate family: current release reality is still split across invite, attendance, and future commerce/payment fronts.
- `mission`, `reward`, `event activity fact`, `check-in`, and broader referral-result attribution: these remain valid roadmap/module concepts, but they are not yet stable current root entities in the code-backed baseline.
- Temporary lead-capture or waitlist payloads: these are transport artifacts, not core business entities.

## 8. Documentation Guidance

When updating module docs after this file:

- use the entities above as the canonical nouns;
- treat registries and projections as secondary layers, not root aggregates;
- avoid promoting Flutter projections/resumes/helpers into business entities;
- document detailed payload fields, filters, and viewer-scoped screen contracts in module docs rather than expanding this file into an API ledger.
