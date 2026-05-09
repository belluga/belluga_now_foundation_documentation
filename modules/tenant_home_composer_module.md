# Documentation: Tenant Home Composer Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Tenant Home Composer module (MOD-301) assembles the personalized landing experience for every tenant app user. For MVP, this module is **deferred**: home composition is client-side using independent requests (invites, agenda, map, discovery), and there is **no** aggregated home endpoint. Post-MVP, this module will emit a single schema and persist lightweight snapshots so mocked clients and production clients can converge on the same payload contract.

### 1.0 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/completed/TODO-store-release-android.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-home-favorites-refresh-regression.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`
  - `foundation_documentation/todos/completed/TODO-v1-map-frontend.md`

### 1.1 Scope/Subscope Ownership (Authoritative)
- Canonical governance source:
  - `foundation_documentation/policies/scope_subscope_governance.md`
- Primary ownership:
  - `EnvironmentType`: `tenant`
  - main scope: `tenant_public`
- Secondary touchpoints:
  - transition target to `tenant_admin` via explicit admin actions/CTAs.
  - route-level transition target to `account_workspace` remains governed, but tenant-public Home does not own a direct workspace CTA in V1; when exposed, that transition is profile/workspace-owned rather than a Home AppBar affordance.

### 1.2 Route/Subscope Matrix
| Route | Host Context | EnvironmentType | Main Scope | Subscope | Notes |
|---|---|---|---|---|---|
| `/` (tenant domain) | Tenant | `tenant` | `tenant_public` | n/a | Canonical tenant home/public entry. |
| `/admin` (tenant domain) | Tenant | `tenant` | `tenant_admin` | n/a | Allowed transition target; guarded by landlord identity principal in V1. |
| `/workspace` | Tenant | `tenant` | `tenant_public` | `account_workspace` | Allowed transition target from tenant public. |
| `/workspace/{account_slug}` | Tenant | `tenant` | `tenant_public` | `account_workspace` | Account-scoped workspace mode. |

---

## 2. Design Principles

1. **Snapshot Everything:** Each generated home overview is stored as an immutable `home_overviews` document tied to `user_id`, `tenant_id`, and `generated_at`. Controllers only mutate by writing a brand-new snapshot, which preserves historical personalization for audits and experimentation.
2. **Composable Sections:** Every home surface (hero carousel, invite nudges, POI strips, agenda actions) is modeled as a `HomeSection` with a `type` discriminator. Clients render sections declaratively based on this metadata, so we can add/remove sections without redeploying Flutter UI.
3. **External Source Isolation:** The module never queries external services directly. It relies on upstream modules (Map, Invite, Agenda, Account Profile Catalog) via asynchronous events or cached read models. This ensures each producer maintains its own invariants while the composer simply curates.
4. **Business-Driven Prioritization:** Section ordering, CTA selection, and copy tone derive from rule sets stored in `home_rulesets`. Rules encapsulate account profile campaigns, invite boosts, and locality signals so we avoid hardcoding logic inside controllers.
5. **Search & Map Bridging:** Quick filters and search suggestions on the home screen map to `HomeSection` instances that deep-link into the Map module via `initial_filter_payload`s (categories, tags, radius). This keeps the “predefined filter buttons” and search results consistent with the map architecture.
6. **Identity-Hydrated Personalized Sections:** Client-composed Home sections that depend on registered user state, including favorites, confirmations, and pending social prompts, must be backed by repository-owned streams and refreshed by the Flutter post-auth hydration coordinator after OTP/login. Home screens may expose explicit user refresh actions, but they do not own identity-transition reconciliation or mirror sibling controller state.

---

## 3. Core Collections

### 3.1 `home_rulesets`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "name": "String",
  "status": "String",
  "priority": "Number",
  "audience_filters": {
    "roles": ["String"],
    "geo_hashes": ["String"],
    "account_profile_ids": ["ObjectId()"]
  },
  "section_blueprints": [
    {
      "section_type": "String",
      "limit": "Number",
      "data_contract": {},
      "fallback_strategy": "String"
    }
  ],
  "valid_from": "Date",
  "valid_until": "Date",
  "created_at": "Date",
  "updated_at": "Date"
}
```
*`status` ∈ {`draft`, `active`, `paused`}. `fallback_strategy` controls whether to collapse, substitute, or repeat sections when upstream data is scarce.*

### 3.2 `home_overviews`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "user_id": "ObjectId()",
  "sections": [
    {
      "section_id": "String",
      "type": "String",
      "title": "String",
      "subtitle": "String",
      "cta": { "type": "String", "label": "String", "target": {} },
      "payload": {}
    }
  ],
  "generated_at": "Date",
  "rule_applied": "ObjectId()",
  "experiments": [
    { "experiment_key": "String", "variant": "String" }
  ]
}
```

### 3.3 `home_feedback`
Captures user actions taken from the home screen so ranking logic can adapt.
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "user_id": "ObjectId()",
  "section_id": "String",
  "action": "String",
  "metadata": {},
  "occurred_at": "Date"
}
```

---

## 4. Interfaces (Post-MVP)

**MVP note:** no home composer API is shipped; the app composes home from independent endpoints. Post-MVP endpoint names are to be defined alongside the final home payload contract.

### 4.2 Events
* **Inbound:** `invites.summary.updated`, `agenda.snapshot.updated`, `account_profile.highlight.updated`, `poi.highlight.updated`.
* **Outbound:** `home.snapshot.generated` (used for analytics) and `home.section.rendered` (fire-and-forget metric event).

---

## 5. Operational Policies

* **Regeneration Cadence:** Snapshots auto-refresh every 4 hours or when upstream events mark dependent sections dirty.
* **A/B Experimentation:** Experiments defined in `home_rulesets` propagate variant identifiers into each section payload. Clients echo the variant when emitting feedback so scoring stays consistent.
* **Latency Budget:** Composer must respond within 350 ms at the API layer. Complex aggregations are executed ahead of time by upstream modules; composer simply stitches data.
* **Post-auth refresh ownership:** For the current client-composed MVP, authenticated Home slices must treat registered identity emission as a hydration boundary. Empty remote favorite/confirmation results for the current user are authoritative and clear stale local state rather than preserving data from an anonymous or previous identity.

---

## 6. Current Strategic Posture

1. **Current posture:** the composer aggregate remains deferred; current home delivery still composes from independent invite, agenda, map, and discovery surfaces.
2. **Deferred continuity:** future multidimensional insights snapshots may surface leaderboard-style social proof once that capability is promoted.
3. **Deferred continuity:** backend-driven home aggregation may later replace client-side composition while preserving the same section schema.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `HOM-01` | Approved | MVP home remains client-composed with no aggregated home endpoint. | Prevents premature backend coupling before contract maturity. | Sections `1`, `4` |
| `HOM-02` | Approved | Post-MVP composer uses immutable snapshot model + rulesets. | Enables experimentation/audit without live mutation side effects. | Sections `2`, `3` |
| `HOM-03` | Approved | Scope ownership remains `tenant_public` with explicit transitions only. | Preserves route governance clarity for home/admin/workspace. | Sections `1.1`, `1.2` |
| `HOM-04` | Approved | Tenant-public Home V1 does not expose unauthenticated web affordances that imply identity convenience (direct workspace entry from Home, Agenda invite/confirmed filter). Workspace transition remains profile/workspace-owned when introduced. | Keeps Home aligned with the web-to-app promotion lane while preserving governed route availability for VNext authenticated web. | Sections `1.1`, `1.2` |
| `HOM-05` | Approved | Home Agenda radius selection persists as a user/device preference (including anonymous sessions), but V1 applies that persisted preference only to the Home Agenda surface. When no preference exists yet, Home seeds the initial selected radius from the user-to-tenant-center distance clamped to the tenant-configured bounds. | Preserves the “my local Home preference” UX without silently changing Event Search/other radius consumers before a broader alignment pass. | `foundation_documentation/todos/completed/TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md` |
| `HOM-06` | Approved | Home surfaces the canonical app-wide location-origin policy result below the logo while keeping its own radius preference UX. The effective origin is now selected by the shared `LocationOriginService` (`mode + reason`) using the tenant-configured max radius as the outside-range boundary and persisted locally/device-side for MVP. | Keeps Home usable for out-of-city users while aligning Home with the same canonical geo-origin rule consumed by Discovery, Event Search, Schedule, and Map. | `foundation_documentation/todos/completed/TODO-v1-home-location-origin-reference-mode.md`, `foundation_documentation/todos/completed/TODO-v1-canonical-location-origin-policy-across-app.md` |
| `HOM-07` | Approved | Home Agenda aggregate state is single-writer and repository-owned. The repository exposes one canonical Home agenda stream fed only by backend-backed Home queries; controller-local invite/confirmed filters publish local display state only and must never write event lists back into repository streams. | Prevents cross-controller interference, cache/source-of-truth duplication, and Home re-entry regressions after Map/Search navigation. | `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` |
| `HOM-08` | Approved | Controller helper methods must not accept `StreamValue` parameters, and Home-adjacent query pagination knowledge must not escape repository ownership. Ownership must stay visible at the controller field call site; repository page envelopes (`has_more`, result wrappers, raw builders), public cache/query snapshots (`*CacheSnapshot`), delegated pagination state (`hasMore...`), page-addressed contract APIs (`loadNext...Page()`), and raw pagination controls (`page`, `pageSize`, cursors, limits) must terminate inside repository implementations rather than appearing in controller-visible or contract-visible APIs. Repository pagination/query helpers are private implementation details only and must not leak back out through overridable hooks, support abstractions, or delegated test helpers. | Blocks helper-shaped ownership masking and prevents future reintroduction of delegated/scratch/page-envelope/cache-snapshot bypasses. | `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` |
| `HOM-09` | Approved | Registered identity-dependent Home state is repository-owned and participates in Flutter post-auth hydration; Home does not reconcile identity transitions through route restarts, controller relay, or screen-local reloads. | Keeps favorites, confirmations, and social prompts coherent after OTP login while preserving single-writer repository ownership. | Sections `2`, `5`; `foundation_documentation/modules/flutter_client_experience_module.md` `FCX-12` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-store-release-android.md` | Android release posture for home composition | Completed | `1`, `4`, `7` | Historical Android publication orchestrator; remaining Home-facing work now lives outside the store-release gate. |
| `TODO-store-release-home-favorites-refresh-regression.md` | Home favorites refresh after favorite mutation and login hydration | Promotion Lane | `2`, `5`, `7` | Promotes repository-owned favorite hydration after registered identity emission and authoritative clearing when remote favorites are empty. |
| `TODO-v1-events-and-agenda-frontend.md` | Home consumption of agenda/event contracts | Completed | `2`, `7` | Ensures home cards align with occurrence-first contracts. |
| `TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md` | Home Agenda radius preference persistence + sheet polish | Completed | `7`, `8` | Home-only radius preference semantics and sheet UX are now promoted; broader schedule/filter unification remains separate. |
| `TODO-v1-home-location-origin-reference-mode.md` | Home-only live vs fixed reference origin mode with explicit status row | Completed | `7`, `8` | Persists Home origin mode locally/device-side for authenticated and anonymous sessions; VNext moves editing/persistent settings to profile/backend. |
| `TODO-v1-canonical-location-origin-policy-across-app.md` | Canonicalize effective geo-origin selection across Home, Discovery, Event Search, Schedule, and Map | Completed | `7`, `8` | Promotes the shared `LocationOriginService`/`reason`-driven notice contract and removes Home-only inline origin branching. |
| `TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` | Canonicalize Home agenda stream ownership and remove controller writes into repository state | Completed | `7`, `8` | Single-writer repository ownership, local display-state filtering, repository-internal pagination, and radius-refresh loading semantics are already reflected in the current repo and governed by promoted rules. |
