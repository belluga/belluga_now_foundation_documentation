# TODO (VNext): Connections Package (`belluga_connections`)

**Classification note (2026-04-16):** the full package remains VNext. A smaller release-critical subset is now tracked separately in `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md` so the Android release gate does not silently absorb the entire package.

**Scope boundary note (2026-04-18):** the release-facing business semantics are no longer ambiguous. Store release now owns the canonical `contact_match -> favorite -> friend` subset, `discoverable_by_contacts`, inviteable-reason rules, in-app inviteable grouping via `contact_groups`, the native-app-only external-share branch for unmatched local contacts, and the required release-facing contact-management behavior for `Contatos` and `/convites/compartilhar`. This VNext TODO must not redefine those semantics; it owns package extraction/convergence and broader non-release consumers only.

**Current-state clarification (2026-04-18):** this TODO is not delivered just because the release subset is now well-defined. The package objective remains open because the repo still has no dedicated `laravel-app/packages/belluga/belluga_connections` package boundary; current behavior is still distributed across existing packages and app-owned modules.

**Naming review note (2026-04-18):** reevaluate the package name when execution starts. If the extracted boundary is truly centered on contact acquisition, contact-match lifecycle, and invite-facing contact management only, `contacts` may be the more faithful package name. Keep `connections` only if the execution slice still legitimately owns the broader relationship/exposure graph beyond contact management.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-03-10

## Objective
Define and implement `belluga_connections` as a dedicated Laravel package that owns:
- contact import,
- salted hashing/match lifecycle,
- unilateral contact matches,
- unilateral user-favorite edges,
- reciprocal friend derivation,
- viewer-scoped profile exposure (`aggregate_only`, `capped_profile`, `full_profile`).

This package is intentionally **simple**. It is not a generic social-network engine, chat system, or follower platform.

---

## 1. Problem Statement

Current documentation and product intent already require:
- contact import and hash matching,
- "friends" visibility for `friends_only` users,
- social proof such as "3 friends are going",
- invite targeting from known contacts,
- privacy-safe people discovery.

Historically, definitions were split:
- some earlier invite-oriented docs treated "friends" too loosely around matched contacts,
- some domain docs defined "friends" as reciprocated favorites,
- invite privacy rules already needed a distinction between being visible as a contact and being fully visible by identity/media,
- product needed a directional approval model where a user may expose their full profile to another user before reciprocity exists.

That ambiguity is now resolved in the store-release canonical contract. This VNext package exists to converge/package the model cleanly, not to reopen the business rules already frozen in the release lane.

---

## 2. Foundational Direction

- [x] ✅ `CON-01` Connections are a dedicated package: `belluga_connections`.
- [x] ✅ `CON-02` The package remains intentionally narrow: contact import, match, explicit user favorites, reciprocity, and viewer-scoped exposure.
- [x] ✅ `CON-03` Product terms are distinct: `contact` = matched contact, `favorite` = unilateral explicit edge, `friend` = reciprocal favorite.
- [x] ✅ `CON-04` Raw contact names, phone numbers, and emails are never stored server-side.
- [x] ✅ `CON-05` Profile exposure is a first-class contract, not an ad hoc UI rule.
- [x] ✅ `CON-06` Minimum-complexity rule: the existing favorite action is the approval primitive; no separate friend-request/approval workflow is required.

---

## 3. Core Terminology

- [ ] ⚪ `contact_import`
  - a user-submitted batch of salted contact hashes from the device address book

- [ ] ⚪ `contact_match`
  - a unilateral relationship: `viewer_user_id -> matched_user_id`
  - created when the viewer imported a contact hash that matches a Belluga user

- [ ] ⚪ `favorite_edge`
  - a unilateral explicit user-to-user edge: `owner_user_id -> favored_user_id`
  - created when the owner favorites another user in the product
  - when `owner_user_id` is `friends_only`, this edge grants `favored_user_id` access to the owner's `full_profile`

- [ ] ⚪ `friend`
  - product alias for reciprocal `favorite_edge`
  - exists when both users favorite each other
  - VNext baseline does not require a separate explicit "friend request" flow

- [ ] ⚪ `profile_exposure_level`
  - `aggregate_only`
  - `capped_profile`
  - `full_profile`

---

## 4. Package Boundary

### 4.1 `belluga_connections` owns
- [ ] ⚪ Contact import batches and salt-version handling.
- [ ] ⚪ Contact hash directory ownership.
- [ ] ⚪ User-match derivation from imported hashes.
- [ ] ⚪ User-favorite edge lifecycle.
- [ ] ⚪ Reciprocal-friend derivation.
- [ ] ⚪ Viewer-scoped people/profile exposure resolution.
- [ ] ⚪ Connection-oriented projections used by invites, onboarding, and people discovery.

### 4.2 `belluga_connections` does not own
- [ ] ⚪ Canonical user profile/account profile source-of-truth.
- [ ] ⚪ Invite lifecycle or invite attribution.
- [ ] ⚪ Account-profile favorites/followers audiences.
- [ ] ⚪ Social-score/ranking computation.
- [ ] ⚪ Chat/messaging.
- [ ] ⚪ Generic social feed behavior.

### 4.3 Adjacent consumers
- [ ] ⚪ Invites consumes viewer-scoped people resumes and reciprocity/exposure decisions.
- [ ] ⚪ Onboarding may offer a "find contacts" preview through this package.
- [ ] ⚪ Discovery/People may consume capped/full resumes depending on scope and privacy.
- [ ] ⚪ Workspace/analytics may consume only aggregate/anonymized outputs except for explicit operational handling.

---

## 5. Exposure Model

The package should expose people through a small fixed contract:

- [ ] ⚪ `aggregate_only`
  - contributes to counts only
  - no identity payload beyond anonymous aggregate contribution

- [ ] ⚪ `capped_profile`
  - safe lightweight identity surface
  - intended for "I know this contact exists" contexts without revealing full media/profile
  - must not include avatar/photo/gallery payloads
  - may expose aggregate metrics only, not specific accepted-event history

- [ ] ⚪ `full_profile`
  - standard profile resume for allowed viewers
  - may include avatar + safe public teaser fields
  - may expose specific accepted-event details only where relationship/context allows it

### 5.1 Baseline rules

- [ ] ⚪ `public` target user
  - allowed exposure baseline: `full_profile` on permitted tenant surfaces
  - non-approved viewers should still prefer aggregate metrics over per-event history unless the product surface explicitly requires the current shared target context

- [ ] ⚪ `friends_only` target user + `favorite_edge(target -> viewer)`
  - allowed exposure baseline: `full_profile`
  - may expose specific accepted-event details on permitted surfaces

- [ ] ⚪ `friends_only` target user + unilateral `contact_match`
  - allowed exposure baseline: `capped_profile`
  - does not become `full_profile` unless the target explicitly favorites the viewer

- [ ] ⚪ `friends_only` target user + direct invite counterparty context
  - allowed exposure baseline: at most `capped_profile`, unless another rule already grants `full_profile`

- [ ] ⚪ no qualifying relationship/context
  - allowed exposure baseline: `aggregate_only`

- [ ] ⚪ `favorite_edge(viewer -> target)` by itself
  - records social intent and may contribute to future discovery/ranking features
  - does not widen access to a `friends_only` target unless the target favorites back

### 5.2 Simplicity rule

- [ ] ⚪ Backend does **not** store raw address-book display names.
- [ ] ⚪ Client may locally decorate a `capped_profile` row with the device contact alias when available.
- [ ] ⚪ Backend remains responsible only for safe Belluga-side exposure, not device contact ownership.

---

## 6. Canonical Data Model

### 6.1 `contact_import_batches`
- [ ] ⚪ `tenant_id`
- [ ] ⚪ `viewer_user_id`
- [ ] ⚪ `salt_version`
- [ ] ⚪ `imported_count`
- [ ] ⚪ `matched_count`
- [ ] ⚪ `imported_at`

### 6.2 `contact_hash_directory`
- [ ] ⚪ canonical owner moves to `belluga_connections`
- [ ] ⚪ stores salted hashes only, never raw contact values

### 6.3 `contact_matches`
- [ ] ⚪ `tenant_id`
- [ ] ⚪ `viewer_user_id`
- [ ] ⚪ `matched_user_id`
- [ ] ⚪ `match_type` (`phone`, `email`)
- [ ] ⚪ `match_source` (`contacts_import`)
- [ ] ⚪ `matched_at`

### 6.4 `user_favorite_edges`
- [ ] ⚪ `tenant_id`
- [ ] ⚪ `owner_user_id`
- [ ] ⚪ `favored_user_id`
- [ ] ⚪ `created_at`
- [ ] ⚪ `updated_at`

### 6.5 `viewer_scoped_people_projection`
- [ ] ⚪ `tenant_id`
- [ ] ⚪ `viewer_user_id`
- [ ] ⚪ `target_user_id`
- [ ] ⚪ `has_contact_match`
- [ ] ⚪ `target_favorited_viewer`
- [ ] ⚪ `viewer_favorited_target`
- [ ] ⚪ `is_friend`
- [ ] ⚪ `context_flags` (`direct_counterparty`, future scoped overlays)
- [ ] ⚪ `profile_exposure_level`
- [ ] ⚪ safe fields for capped/full rendering
- [ ] ⚪ `updated_at`

### 6.6 MongoDB Query & Projection Strategy
- [ ] ⚪ Canonical writes stay narrow: `contact_matches` and `user_favorite_edges` are the source-of-truth relationship collections.
- [ ] ⚪ Exposure reads must resolve from one sparse projection lookup, not runtime graph traversal.
- [ ] ⚪ `viewer_scoped_people_projection` is materialized only for related pairs (contact, favorite, reciprocal favorite, direct counterparty context), not as a global user-to-user matrix.
- [ ] ⚪ `is_friend` and `profile_exposure_level` are derived at write/update time when contacts, favorites, privacy mode, or direct-counterparty overlays change.
- [ ] ⚪ Invite feed, people discovery, and contact-match surfaces must not rely on Mongo `$lookup`/fan-out pipelines for relationship resolution on the hot path.

### 6.7 MongoDB Index Baseline
- [ ] ⚪ `contact_hash_directory`
  - unique: `(tenant_id, hash_type, contact_hash)`
- [ ] ⚪ `contact_matches`
  - unique: `(tenant_id, viewer_user_id, matched_user_id, match_type)`
  - list/read: `(tenant_id, viewer_user_id, matched_at, _id)`
- [ ] ⚪ `user_favorite_edges`
  - unique: `(tenant_id, owner_user_id, favored_user_id)`
  - reverse lookup: `(tenant_id, favored_user_id, owner_user_id)`
- [ ] ⚪ `viewer_scoped_people_projection`
  - unique exact-lookup: `(tenant_id, viewer_user_id, target_user_id)`
  - list/read: `(tenant_id, viewer_user_id, profile_exposure_level, updated_at, _id)`

### 6.8 Performance Guardrails
- [ ] ⚪ Viewer -> target exposure must be answerable in a single indexed document read.
- [ ] ⚪ Reciprocal-friend detection must not be recomputed via live double-query joins on request paths.
- [ ] ⚪ Bulk contact imports should upsert canonical rows and enqueue projection refreshes in batches; no per-contact synchronous resolver waterfall.
- [ ] ⚪ Privacy-mode changes must invalidate/update only the affected target rows, not rebuild unrelated projections.

---

## 7. Minimal API / Resolver Surface

- [ ] ⚪ Keep `POST /api/v1/contacts/import` as the canonical public entry point, but move ownership to `belluga_connections`.
- [ ] ⚪ Define canonical favorite-edge mutations, likely:
  - `POST /api/v1/connections/favorites/{target_user_id}`
  - `DELETE /api/v1/connections/favorites/{target_user_id}`
- [ ] ⚪ Define a people-resume read surface, likely:
  - `GET /api/v1/connections/matches`
  - or another tenant-scoped connections list endpoint

- [ ] ⚪ Define an internal package resolver contract such as:
  - `resolvePeopleExposure(viewer_user_id, target_user_ids, context)`

Required contexts may include:
- `invite_feed`
- `invite_acceptance`
- `people_discovery`
- `workspace_operational`
- `ranking_public`

---

## 8. Privacy & Audit Rules

- [ ] ⚪ No raw phone numbers/emails persisted after hashing/matching flow.
- [ ] ⚪ Exposure decisions must be reproducible/auditable by rule, not hidden in controllers/widgets.
- [ ] ⚪ `friends_only` users still count toward metrics and rankings even when identity is capped or aggregated.
- [ ] ⚪ Default analytics/dashboard surfaces should prefer aggregate or capped data over raw identity.
- [ ] ⚪ Favoriting is directional approval: the owner of a `favorite_edge` is granting the favored user fuller visibility into the owner's profile.

---

## 9. Proposed Canonical Decisions to Promote Later

- [ ] ⚪ `belluga_connections` should be the canonical owner of:
  - contact import
  - contact match
  - user-favorite edges
  - reciprocal-friend derivation
  - viewer-scoped people exposure

- [x] ✅ VNext friend definition:
  - `friend = reciprocal favorite_edge`

- [x] ✅ VNext exposure baseline:
  - `public -> full_profile`
  - `friends_only + favorite_edge(target -> viewer) -> full_profile`
  - `friends_only + unilateral contact_match(viewer -> target) -> capped_profile`
  - `friends_only + direct invite counterparty -> capped_profile` unless target-owned favorite grants more
  - otherwise `aggregate_only`
  - `favorite_edge(viewer -> target)` alone does not widen access to the target's private profile

- [x] ✅ VNext field baseline:
  - avatar/photo and specific accepted-event history are `full_profile` only
  - unilateral contacts/direct counterparties receive at most `capped_profile`
  - favorite-granted viewers may receive full profile/history on permitted surfaces

- [x] ✅ VNext Mongo baseline:
  - canonical edges remain narrow and write-optimized
  - `viewer_scoped_people_projection` is the hot-path read model
  - viewer-to-target exposure resolves in one indexed lookup
  - no request-path graph traversal is required for invite/discovery surfaces

---

## 10. Success Criteria

- [ ] ⚪ "Friends" no longer means different things in different docs.
- [ ] ⚪ Invites, onboarding, and discovery consume one canonical people-exposure contract.
- [ ] ⚪ Privacy behavior is explainable in simple product terms.
- [ ] ⚪ Belluga stays simple: contacts + favorites + reciprocal friends + viewer-scoped exposure, without turning into a bloated social-network subsystem.
- [ ] ⚪ Viewer-to-target exposure decisions stay Mongo-friendly and explainable from indexes/projections alone.
