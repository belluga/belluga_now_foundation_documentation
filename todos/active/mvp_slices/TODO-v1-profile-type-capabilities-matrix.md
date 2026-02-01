# Documentation: TODO — Profile Type Capabilities Matrix (MVP)
**Version:** 1.0

## Objective
Define a capability-driven schema for Account Profile Types, map each capability to required/optional fields, and update the Account Profile form + Profile view to render fields conditionally. Capabilities are the single source of truth for field visibility and validation (e.g., POI requires location).

## Sources
- `foundation_documentation/domain_entities.md` (Account Profile types + registry rules)
- `foundation_documentation/screens/modulo_tenant_admin.md` (admin form requirements)
- `foundation_documentation/endpoints_mvp_contracts.md` (profile type + account profile payloads)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`

## Capability → Field Matrix (Draft)
### Core
- `is_favoritable` → UI shows favorite toggle + public discovery badge; no direct admin field.
- `is_poi_enabled` → Requires `location.lat` + `location.lng`, enables Map Pick, allows map POI projection.

### Content
- `has_bio` → Show `bio` field (rich text or multiline; MVP uses plain text).
- `has_taxonomies` → Show taxonomy chips with types driven by `allowed_taxonomies`.

### Media
- `has_avatar` → Show avatar upload section.
- `has_cover` → Show cover upload section.

### Scheduling
- `has_events` → Enable events section on public profile with label **"Próximos Eventos"** (read-only). No validation impact.

### Notes
- `allowed_taxonomies` already exists in registry and gates taxonomy fields.
- `profile_type` remains registry-driven and immutable after creation.
- No inheritance in V1 (`parent_type` omitted).

## MVP Profile Types + Capabilities (Proposed)
- `personal`: is_favoritable=false, is_poi_enabled=false, has_bio=true, has_taxonomies=false, has_avatar=true, has_cover=true, has_events=false
- `artist`: is_favoritable=true, is_poi_enabled=false, has_bio=true, has_taxonomies=true (music genres), has_avatar=true, has_cover=true, has_events=true
- `venue`: is_favoritable=true, is_poi_enabled=true, has_bio=true, has_taxonomies=true (genre/cuisine), has_avatar=true, has_cover=true, has_events=true
- `restaurant`: is_favoritable=true, is_poi_enabled=true, has_bio=true, has_taxonomies=true (cuisine), has_avatar=true, has_cover=true, has_events=true
- `experience_provider`: is_favoritable=true, is_poi_enabled=true, has_bio=true, has_taxonomies=true (context), has_avatar=true, has_cover=true, has_events=true

## Decisions (Confirmed)
- **Capabilities list (static for MVP):** `is_favoritable`, `is_poi_enabled`, `has_bio`, `has_taxonomies`, `has_avatar`, `has_cover`, `has_events`.
- **Taxonomies:** `allowed_taxonomies` are **not dynamic per profile**; they are defined per profile type registry entry and managed by admin.
- **has_events:** does not add validation; only controls the public profile section label “Próximos Eventos”.
- **MVP scope:** capability-driven behavior applies to both Admin form and Public Profile view.

## Delivery Tasks
### A) Documentation & Contracts
- [ ] ⚪ Define capability schema in `domain_entities.md` (capability list + field gating rules).
- [ ] ⚪ Update `endpoints_mvp_contracts.md` registry payload to include new capabilities.
- [ ] ⚪ Update `screens/modulo_tenant_admin.md` to reflect capability-driven form sections.
- [ ] ⚪ Update `TODO-v1-account-profile-ui.md` with capability-driven view requirements.

### B) Laravel (Registry + Environment)
- [ ] ⚪ Update profile type validation rules to accept new capability flags.
- [ ] ⚪ Ensure registry CRUD persists new capability flags and returns them in responses.
- [ ] ⚪ Ensure `/api/v1/environment` exposes updated profile type capabilities.
- [ ] ⚪ Add/extend tests covering new capability flags in registry CRUD.

### C) Flutter — Admin (Form)
- [ ] ⚪ Account Profile create/edit form renders sections based on capabilities.
- [ ] ⚪ Enforce location requirement when `is_poi_enabled=true`.
- [ ] ⚪ Taxonomy UI renders only when `has_taxonomies=true` and uses `allowed_taxonomies`.
- [ ] ⚪ Hide media sections when `has_avatar` / `has_cover` are false.
- [ ] ⚪ Add/update widget tests for capability-driven visibility.

### D) Flutter — Tenant App (Profile View)
- [ ] ⚪ Profile detail renders modules based on capabilities (bio, media, location, taxonomy).
- [ ] ⚪ Favorites affordances only when `is_favoritable=true`.
- [ ] ⚪ Ensure discovery and profile view rely on registry capabilities (no hardcoded enums).

## Approval Gate
Implementation starts only after this TODO is refined and approved with **APROVADO**.
