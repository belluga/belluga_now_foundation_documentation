# Documentation: Domain Entities
**Version:** 1.0

## 1. Introduction

This document defines the Core Business Entities (CBEs) for the Bóora! platform. These are the primary "nouns" of the platform domain. Guar[APP]ari is the first tenant implementation hosted on Bóora!.

This list serves as the domain source of truth referenced by the `system_architecture_principles.md` (Principle P-1) and is the foundation for all module design.

**Flutter domain boundary:** Domain entities and value objects must not depend on DTOs or infrastructure types. All DTO parsing/mapping lives in the infrastructure layer (mappers/repositories), and UI/controllers consume only domain/projection models.

---

## 2. Core Business Entities

* **Primary Entity:** **User** (The consumer, including moratoriums and tourists, who discovers, books, and shares experiences). Users exist as soon as an identity token is issued and transition from unauthenticated to authenticated without changing entity type; identity state is a lifecycle attribute, not a separate user model.
* **Supporting Entity A:** **Organization** — optional grouping of **accounts belonging to the same real‑world entity** (tenant, sponsor, hotel group, multi‑location brand). Organizations are **not required**; most accounts will be standalone. MVP usage is grouping only; memberships/billing can be layered later.
* **Supporting Entity B:** **Partner (Label, Future Capability)** — the tenant-facing label applied to **Account Profiles** that operate as B2B providers (restaurants, artists, guides, merchants). The label system is deferred; in V1 we still model **Account Profiles** as the canonical entity and project account-profile-facing summaries from them. **Account profile types are not hardcoded enums**; they are provided by a **Profile Type Registry** (WP‑like custom post types, without WP meta) fetched from `/api/v1/environment.profile_types` and cached locally. The registry defines label, allowed taxonomies, capabilities (e.g., `is_favoritable`, `is_poi_enabled`), and default UI modules per type. **No inheritance is used in V1** (`parent_type` is omitted); taxonomies apply only to the type they are declared on. **MVP registry types:** `personal`, `artist`, `venue`, `restaurant`, `experience_provider` (others deferred to VNext). Invite surfaces consume the `InvitePartnerSummary` aggregate (id, partner type, display name, tagline, hero + logo URIs) so Flutter and Laravel share the same social-proof branding contract. The canonical account-profile-facing aggregate (id, profile, verification flags, contact information, invite/offer badges, engagement metrics, semantic tags) is produced from Account Profiles and shared via value objects to preserve invariants. Engagement metrics are type-aware (e.g., live-status for artists, presence counts for venues, invite counts for influencers) and are always non-negative integers or bounded strings represented as value objects rather than raw primitives. Accounts are the permission boundary and have **exactly one Account Profile** (1:1), with `ownership_state` controlling whether the account is tenant_owned, unmanaged, or user_owned. **Account Profile is implemented in this project** (not upstream boilerplate) to avoid coupling other boilerplate consumers.
* **Supporting Entity C:** **Offering** (The catalog of consumable items, encompassing Events, Products, and Experiences/Guides).
* **Supporting Entity D:** **Transaction** (The record of action and value exchange, including Bookings, Orders, Payments, and social Invitations).
* **Supporting Entity E:** **Static Asset** — tenant-managed POIs and pages (beaches, nature, culture, historic, etc.) that are **not** Account Profiles; they project into `map_pois` as `ref_type=static` and carry no operator or invite/favorite semantics. Static Assets are governed by a **static profile type registry** and reuse a shared profile page schema for public read surfaces.

### Location & Venue Field Definitions

- `offering_location_type` (enum): allowed values `physical`, `online`. Every event-like offering must declare which location mode it uses.
- `venue` (object): required for `physical` offerings; represents a physical place with coordinates + address.
  - `venue_id` (string): normalized POI/venue id (preferred) for cross-module linking (map ↔ agenda).
  - `address` (string): human-readable address (bounded string; value object).
  - `coordinates` (object): `latitude` + `longitude` (value objects) for GeoQuery and directions.
- `online_location` (object): required for `online` offerings; represents an “anywhere” experience.
  - `label` (string): e.g., “Online”, “Ao vivo”.
  - `url` (string, optional): join/watch URL when applicable.

### Domain helper aggregates (required for mocks & projections)

| Aggregate | Purpose | Notes |
| --- | --- | --- |
| Favorite Badge | Normalizes the glyph/branding metadata for a favorite collection badge. Exposes value objects for icon code point, font family, and package so UI layers can render glyphs without mutating domain state. | Stored under `lib/domain/favorite/` and consumed by `Favorite` + `FavoriteResume`. |
| Artist Resume | Canonical snapshot of an artist/curator identity for events, invites, and map markers. Carries `ArtistIdValue`, `ArtistNameValue`, `ArtistAvatarValue`, and `ArtistIsHighlightValue` so Venue/Schedule projections never fall back to primitives. | Lives under `lib/domain/artist/` and is produced from schedule DTOs before reaching UI controllers. |
| Friend Resume | Lightweight projection of a `User` contact used inside invites. Stores `FriendIdValue`, `TitleValue`, `FriendAvatarValue`, and `FriendMatchLabelValue` so we never fall back to primitives in domain → presentation boundaries. | Used exclusively by invite share/flow controllers. |
| City POI & Map Events | Represents geographic entities surfaced on the tenant map (coordinates, categories, badges) plus immutable POI update events (move, activation, deactivation). All coordinates, badges, and filter tokens must be expressed as value objects to keep map math and styling independent from Flutter types. | Resides under `lib/domain/map/` with collections for `value_objects/`, `events/`, and `filters/`. |
| Venue / Local | Canonical “place” aggregate referenced by offerings/events. A Venue can be physical (address + coordinates) or online (no fixed locality, optional URL). Physical venues must be eligible for GeoQuery and navigation; online venues must be treated as “available anywhere” and must not trigger distance ordering or geo constraints. | The map module owns the normalized POI registry; schedule/agendas reference venues by `venue_id` + summary fields. |
| Invite | Represents a social invitation tied to an event/offering, with inviter, invitees, status, timestamps, and source links for attribution. | Only one accepted invite per invitee per event; expires at event end; supports invite attribution for ranking and missions. |
| Presence / Check-in | Captures the verified presence of a user at an event via geofence/QR/staff confirmation. | Presence is the source of truth for “Presenças Confirmadas”; accepted-without-check-in is treated as no-show. |
| Mission | Defines account-profile-created goals (e.g., `10 convites aceitos`) with metric target, window, reward, and validation source. | Metric is selected per mission; pre-event missions should prefer invites/engagement over presence. |
| Social Score | Aggregates north-star metrics for users and account profiles (partner label): `Convites Aceitos` (esforço) and `Presenças Confirmadas` (resultado), tracked all-time and for the current month (“Em Alta”). | Drives rankings, badges, and Pro/Verificado unlocks; respects privacy/anonymization when applicable. |
| Contact Hash Directory | Stores salted hashes of user-imported contacts to enable friend suggestions and invite matching without storing raw PII. | Used to suggest “Pessoas” in discovery and to match invite acceptance with previously imported contact hashes. |

### Account Profile Label Field Definitions

- `account_profile_type` (string): **must match** a `profile_type_registry.type` entry from tenant settings. Drives engagement metrics, labeling, and permissioning per role.
- `accepted_invites` (int): cumulative, non-negative count of invites accepted that are attributable to the account profile; zero default; must not be null.
- `engagement` (object): optional; type-specific metrics expressed via value objects.
  - For `artist`: `status_label` (bounded string, <=32 chars, e.g., “Tocando agora”), `next_show_at` (ISO8601, optional).
  - For `venue`: `presence_count` (non-negative int).
  - For `experience_provider`: `experience_count` (non-negative int).
  - For `influencer`: `invite_count` (non-negative int; if present, should align with `accepted_invites` for consistency).
  - For `curator`: `article_count` and `doc_count` (non-negative ints).
- `media`: `avatar_uri` and `cover_uri` are optional but, if present, must be valid URIs captured as value objects. Fallbacks live in the projection layer, not the aggregate.
- `tags` (array of strings): optional, up to 16 entries, each ≤32 chars, sanitized and stored via value objects to avoid leaking UI-specific tokens into the domain. Tags are **type-aware**; examples include:
  - `artist`: music genres (e.g., rock, samba, eletrônica).
  - `experience_provider`: context/location tags (e.g., mar, praia, mergulho, montanha).
  - `curator`: curatorial focus (e.g., história, causos).
  - `influencer` (personalidade): focus areas (e.g., lifestyle, baladas).
- `taxonomy_terms` (array of objects): optional, WordPress-style multi-taxonomy list of `{type, value}` pairs (e.g., `{type: cuisine, value: italian}` or `{type: music_genre, value: samba}`). Account profiles may carry multiple taxonomy types simultaneously (venues can have cuisines + music genres).

### Taxonomy Registry Field Definitions (V1)

**Taxonomy (registry entry)**
- `slug` (string): unique taxonomy key for the tenant (used as `taxonomy_terms[].type`).
- `name` (string): human-readable label.
- `applies_to` (array of strings): object types that may use this taxonomy.
- `icon` (string, optional): Material icon name (e.g., `mode_subscription`).
- `color` (string, optional): HEX color `#RRGGBB`.

**Term (taxonomy term)**
- `slug` (string): unique term key within its taxonomy (used as `taxonomy_terms[].value`).
- `name` (string): human-readable term label.
- `taxonomy_id` (ObjectId): owning taxonomy reference.

**Field Definitions**
- `applies_to`
  - `account_profile`: Taxonomy terms can be attached to Account Profiles.
  - `static_asset`: Taxonomy terms can be attached to Static Assets.
  - `event`: Taxonomy terms can be attached to Events.

### Static Profile Type Registry Field Definitions (V1)

**Static Profile Type (registry entry)**
- `type` (string): unique key used by `static_assets.profile_type`.
- `label` (string): human-readable label.
- `map_category` (string): coarse map category used by `map_pois.category` when projecting static assets.
- `allowed_taxonomies` (array of strings): taxonomy slugs allowed for this static profile type.
- `capabilities` (object):
  - `is_poi_enabled` (bool): requires `location`.
  - `has_bio` (bool): enables short description field.
  - `has_taxonomies` (bool): enables taxonomy terms in the UI.
  - `has_avatar` (bool): enables avatar media.
  - `has_cover` (bool): enables cover media.
  - `has_content` (bool): enables long-form page content.

### Static Asset Field Definitions (V1)
- `profile_type` (string): references `static_profile_types.type`.
- `display_name` (string): primary title for the page and POI.
- `slug` (string): URL slug derived from `display_name` and generated by backend.
- `bio` (string, optional): short summary (bounded string).
- `content` (string, optional): long-form page content (bounded string).
- `avatar_url` / `cover_url` (string, optional): media URLs.
- `tags` (array of strings, optional): tag tokens for search/filtering.
- `categories` (array of strings, optional): legacy metadata token list; kept for backward compatibility and ignored for `map_pois.category` projection.
- `taxonomy_terms` (array of objects): typed `{type, value}` pairs.
- `location` (object): required when `static_profile_types.capabilities.is_poi_enabled=true`; uses the same `lat`/`lng` structure as account profiles.
- `is_active` (bool): controls whether the static asset is available to public read surfaces; defaults to `true` on create when omitted.
- `created_by` / `created_by_type` / `updated_by` / `updated_by_type`: audit fields (see Audit Field Definitions).

### Account Ownership Field Definitions

- `ownership_state` (enum): `tenant_owned`, `unmanaged`, `user_owned`.
  - **MVP note:** tenant-admin manual onboarding (`POST /admin/api/v1/account_onboardings`) requires explicit create intent (`tenant_owned|unmanaged`); read payloads still expose the derived effective ownership state.

**Field Definitions**
- `ownership_state`
  - `tenant_owned`: Official tenant-owned accounts (may be grouped under an Organization or standalone).
  - `unmanaged`: Tenant-managed but not owned (must be standalone; no organization).
  - `user_owned`: User-owned accounts. In **MVP**, only the auto-created **personal** account exists (private by default) and is created **when the user is identified/authenticated**. Post‑MVP, users may **claim unmanaged accounts** or create **additional business accounts**; those remain `user_owned`.

### Audit Field Definitions (Account + Account Profile)

- `created_by` (string): Actor id (user or landlord user) that created the record.
- `created_by_type` (enum): `tenant`, `landlord`.
- `updated_by` (string): Actor id (user or landlord user) that last updated the record.
- `updated_by_type` (enum): `tenant`, `landlord`.

### Organization Field Definitions

- `organization_id` (ObjectId, optional): account grouping reference for multi‑account entities (tenant, sponsor, hotel group). Optional by design; most accounts will not have one.

### Social Graph & Presence Field Definitions

- `user_level` (enum): `basic`, `verified` (Pro/Verificado). Verified unlocks higher invite limits and monetization surfaces.
- `privacy_mode` (enum): `public`, `friends_only` (friends defined as reciprocated favorites). Private users are anonymized in rankings (blur/avatar masking) but still count toward metrics.
- `people_discovery_priority`: order “Pessoas” by monthly Social Score; verified users are surfaced first when scores tie, but both verified and basic can appear.
- `invite_status` (enum): `pending`, `accepted`, `declined`; `expired` is derived when the event ends. Exactly one accepted invite per invitee per event.
- `invite_limits` (by role): `basic` up to 20 pending invites simultaneously; `verified` up to 50; `account_paid` up to 100 (higher via plan tiers). Invites are single-use per invitee/event.
- `check_in_method` (enum): `geofence`, `qr`, `staff_manual`. Geofence radius is account-profile-defined; QR is optional reinforcement; manual is staff-only for auditability.
- `presence_status` (enum): `confirmed`, `no_show` (accepted invite without check-in by event end).
- `mission_metric` (enum): `invites_accepted`, `presences_confirmed`, `check_ins`, `purchases` (future). Chosen per mission by the account profile; pre-event missions are advised to avoid presence unless explicitly desired.
- `mission_status` (enum): `pending`, `active`, `completed`, `expired`.
- `account_profile_curator_link_status` (enum): `pending`, `accepted`; either side can propose, reciprocal acceptance required.
- `contact_hash` (string): salted hash for a contact identifier (e.g., phone/email) used solely for matching imported contacts to existing users; raw identifiers are never stored.
