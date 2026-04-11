# Documentation: Flutter Client Experience Module
**Version:** 1.0

## 1. Module Index

| Module ID | Module Name | Primary Responsibility | Status | Owner |
|-----------|-------------|------------------------|--------|-------|
| MOD-201 | Flutter Client Experience Module | Deliver the multi-tenant mobile client with Laravel-backed runtime adapters and full-layer architecture (controllers, repositories, services). | Defined | Delphi |

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
- Cross-module contract references:
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
  - `foundation_documentation/todos/active/vnext_slices/TODO-vnext-tenant-user-account-profile-area.md`

## 2. Module Specification

### MOD-201: Flutter Client Experience Module

* **Purpose Statement:** Establish the foundational Flutter application that orchestrates tenant and account/profile experiences through a clean architecture stack (presentation, domain, infrastructure) wired to Laravel-backed service contracts.
* **Core Entities:** User, Account, Account Profile, Offering, Transaction.
* **Key Workflows:** Adaptive onboarding, tenant home discovery, invite and social growth loop, agenda management, map exploration with POIs, authenticated profile utilities.
* **External Dependencies:** AutoRoute (navigation), GetIt (DI container), StreamValue (reactive state wrapper), value_object_pattern, Firebase Cloud Messaging (future push integration), Laravel HTTP + SSE backends.
* **Service-Level Objectives:** Screen state transitions <150 ms under cached/remote data; cold-start bootstrap <2.5 s on mid-range devices with backend available; navigation stack integrity with zero controller leaks; 100 % controller-stream parity (no orphaned state).

#### 2.0.1 Runtime Backend Mandate (V1 Launch)

- Compiled/runtime app path is **Laravel-only**. Runtime mock fallback is forbidden.
- Mock adapters/datasets are allowed only in test targets through explicit test injection.
- Startup must hard-stop when backend bootstrap is unavailable and require user retry after connectivity recovers.
- Discovery runtime contract is account/account-profile based; partner-only mock content providers and audio player service are out of MVP runtime scope.

#### 2.0 Scope/Subscope Ownership (Authoritative)

Canonical governance source:
- `foundation_documentation/policies/scope_subscope_governance.md`

This module spans multiple scopes and therefore requires explicit route/scope ownership.

| Route Surface | Host Context | EnvironmentType | Main Scope | Subscope | Ownership |
|---|---|---|---|---|---|
| `/` (landlord host) | Landlord | `landlord` | `site_public` | n/a | Public landlord landing UX. |
| `/admin` (landlord host) | Landlord | `landlord` | `landlord_area` | n/a | Landlord admin shell and tenant selection UX. |
| `/` (tenant host) | Tenant | `tenant` | `tenant_public` | n/a | Tenant public/home UX. |
| `/admin` (tenant host) | Tenant | `tenant` | `tenant_admin` | n/a | Tenant-admin UX (guarded by landlord identity principal in V1). |
| `/workspace` (tenant host) | Tenant | `tenant` | `tenant_public` | `account_workspace` | Account workspace root mode. |
| `/workspace/{account_slug}` (tenant host) | Tenant | `tenant` | `tenant_public` | `account_workspace` | Account-scoped workspace mode. |

Governance constraints:
- No new scope/subscope may be introduced in Flutter routes/screens without an explicit prior decision and canonical policy update.
- Route/screen docs must preserve this matrix when new feature routes are introduced.

#### 2.1 Domain Rules

* **Invariants:** Controllers are the sole owners of state mutations; widgets remain presentational; every domain entity surfaces as a value-object backed model; DI registrations occur before route build.
* **Validation Rules:** Input fields rely on domain value objects (e.g., `EmailValue`, `PasswordValue`); invite codes enforce length 6–12; POI filter radius 1–50 km; schedule entries require ISO-8601 timestamps.
* **Authorization Requirements:** Progressive profiling is anonymous-first for invite conversion: anonymous users may resolve share preview and accept invite from app invite flow, while trust actions remain hard-gated behind Auth Wall (`favorite`, `send_invite`, presence/check-in boundaries). On web tenant-public surfaces, hard/auth gates must resolve to app-promotion handoff (never web-login continuation). Identity-owned routes follow the same split: unauthenticated app access to `/profile` continues to native auth/login, while unauthenticated web access to `/profile` resolves to app-promotion UX/handoff. Authenticated tenant scope unlocks full trust-action surfaces; account workspace scope exposes account/profile dashboards (future flavor); promoter scope requires explicit feature flag. Public map routes are soft-gated by location only: `/mapa` and `/mapa/poi` continue through the canonical `/location/permission` surface, `Continuar sem localização` must still resume the original target using the fixed-reference map fallback for that access, and cancelling/dismissing the permission boundary must return to prior history when it exists or deterministically fall back to `/` when it does not.
* **Anonymous Web Route Allowlist (V1):** tenant-public anonymous web is explicitly allowlisted, not open-by-default. Public direct URL surfaces are limited to `/`, `/privacy-policy`, `/descobrir`, `/parceiro/:slug`, `/agenda/evento/:slug`, `/static/:assetRef`, `/mapa`, `/mapa/poi`, `/invite?code=...`, `/convites?code=...`, `/location/permission`, and `/baixe-o-app`. Blocked non-identity routes such as `/agenda` or invalid invite URLs fall back to `/`; identity/auth-owned routes such as `/profile`, `/workspace*`, and `/auth/*` resolve to the canonical app-promotion boundary on web. For map routes specifically, the location gate preserves the originally requested target and never collapses back to Home after permission resolution.
* **Tenant-Public Desktop Web Mobile-Frame Contract:** On wide desktop web viewports, the shared tenant-public mobile-first route family (`/`, `/descobrir`, `/parceiro/:slug`, `/static/:assetRef`, `/agenda/evento/:slug`, `/invite?code=...`, `/convites?code=...`, `/baixe-o-app`, `/mapa`, `/mapa/poi`, `/location/permission`, and the web identity boundary that resolves through app-promotion) must render inside one centered mobile-width frame instead of stretching full width. The current shared max width is `430` logical pixels. This is a presentation-shell rule only: controller ownership, route guards, route semantics, and web-to-app promotion behavior remain unchanged. Landlord, tenant-admin, and workspace shells are explicitly excluded from this framing rule.
* **Tenant-Public Safe Back Contract:** tenant-public discovery, public-detail, and public-map surfaces must use one centralized canonical route-back policy: consume route-local UI state first when the screen explicitly owns it, then remove the previous route if stack history exists, and only then execute the route-family no-history fallback when the route is root-opened. The approved no-history fallback matrix is `/descobrir -> /`, `/agenda -> /profile`, `/agenda/evento/:slug -> /`, `/parceiro/:slug -> /descobrir`, `/static/:assetRef -> /descobrir`, `/mapa -> /`, `/mapa/poi -> /mapa`. System back, visible back buttons, and shared immersive-detail back actions must all delegate to that same contract.
* **Canonical Route Back Governance Contract:** Flutter route-back ownership is structural and meta-driven. Every governed route family must declare `canonicalRouteMeta(...)` in AutoRoute module definitions; governed screens must consume `buildCanonicalCurrentRouteBackPolicy(...)`; governed shells that render the active child route must consume `buildCanonicalRouteBackPolicyForRouteData(...)`; and app ingress may apply only root-scoped startup overrides through `deepLinkBuilder` via `AppStartupNavigationCoordinator`. Warm web/browser/device back must remain AutoRoute/browser-native whenever real history exists; centralized governance owns visible back, system back fallback, and deterministic no-history outcomes, not synthetic browser-history seeding. Shared back-owning widgets may accept injected handlers/policies but must not hide local fallback semantics. Tenant-public bottom navigation is part of this same contract: forward user navigation that must preserve the predecessor route in browser/device history must use ordinary AutoRoute `push(...)` so the browser receives a real in-app entry before any guard boundary resolves; returning to an already-existing root such as Home may reuse AutoRoute stack navigation (`navigate(...)`) instead of rebuilding the stack. `replaceAll(...)` resets remain forbidden for this tenant-public bottom-nav flow. Warm tenant-public map entry is a specific web-sensitive case: when the user taps into the map from an already-visible app route, Flutter must push the permission boundary explicitly as an ordinary result-return route; `/location/permission` then `pop(result)` back to the caller, and the caller pushes the map target when the result is `granted` or `continueWithoutLocation`. Direct URL/deep-link map entry still remains guard-owned. Route-policy `PopScope` ownership belongs to `RouteBackScope`, not to ad-hoc screen code. Result-return boundary flows (for example `/location/permission`) may remain outside the generic route-family back dispatcher, but they must still use one explicit boundary-dismiss contract: visible back and system/device back emit the same semantic cancel result when applicable; warm permission pushes cancel by popping to the preserved predecessor route; guarded permission flows cancel by returning to the preserved predecessor stack when it exists; grant resumes the guarded target without leaving the permission route behind; and no-history dismissal resolves through one shared dismiss-target resolver instead of ad-hoc `replace/pop` rules. Canonical promotion remains a governed route family and composes that same dismiss-target resolver for its no-history outcomes.
* **Immersive Detail Theme Contract:** Tenant-public immersive detail routes `/agenda/evento/:slug`, `/parceiro/:slug`, and `/static/:assetRef` must derive their active `ColorScheme` from the hero image through the shared `ImagePaletteTheme` adapter, with deterministic fallback to the ambient app theme when media is missing or extraction fails. `ImmersiveDetailScreen` remains a pure consumer of the resolved theme and must not own palette extraction logic.
* **Type Visual Consumption Contract:** Flutter consumers must treat profile-type `visual` as the canonical type identity input across map and non-map surfaces. `mode=image` must render the resolved media according to `image_source` (`avatar|cover|type_asset`) instead of silently degrading to icon mode; `type_asset` uses the canonical type-owned image URL exposed by the registry, while `avatar|cover` resolve against item media. Fallback to generic/default visuals is only valid when the required media asset is absent, invalid, or fails to load. Map routes still consume projection-owned `map_pois.visual` snapshots rather than deriving marker visuals directly from registry payloads.
* **Account Profile Visual Resolution Contract:** Public account-profile surfaces must resolve media per surface family instead of reusing one global fallback chain. Hero/background surfaces use `cover > avatar > type visuals`; compact rows use `avatar > cover > type visuals`; shared identity-avatar blocks use the real avatar when present and otherwise fall back to the canonical `type visual` avatar surface. When a real avatar exists, the `type visual` becomes a badge overlay on that avatar instead of a post-name label. Discovery cards and partner-detail heroes must share the same identity language and must not render the old textual type eyebrow. When the final hero source is image-backed, immersive route theming must derive from that same image; when the final fallback is icon/color-only `type visual`, the route theme must derive from the `type visual` seed color instead of silently reverting to the ambient scheme.
* **Immersive Event Detail Profile Category Contract:** Tenant-public immersive event detail must treat linked account profiles as grouped profile categories rather than a hardcoded artist-only lineup. Stable tabs are `Sobre` and `Como Chegar`; dynamic profile tabs render between them using plural profile-type labels from bootstrap metadata. `Sobre` renders `event.content` as HTML using the shared public rich-text pattern, while `Como Chegar` reuses the account-profile map/directions language with event-owned venue/location inputs instead of duplicating venue semantics inside the dynamic category tabs. Linked-profile cards must keep direct `/parceiro/:slug` navigation, expose a top-right favorite affordance, and rely on a strict runtime `slug` contract rather than local lookup or request-time enrichment fallbacks. Missing `linked_account_profiles[].slug` is a payload-contract failure, not a valid “disabled card” state. Contextual tab footers are screen-gated: they may replace the default immersive footer only when the host screen explicitly allows that override, and returning to tab index `0` must reset both inner and outer scroll stacks to the true top.
* **Favorites Strip Preview Contract:** Tenant-home favorites remains snapshot-backed. For `account_profile` targets, the preview block must carry `avatar_url`, `cover_url`, and `profile_type`; Flutter resolves `type` label/visual from the app bootstrap registry and applies the compact precedence `avatar > cover > type visuals`. Event presence emphasis is also snapshot-driven: no halo with no event, subtle halo for upcoming event, stronger halo for live-now event. Favorites navigation still resolves directly to `/parceiro/:slug` whenever the snapshot exposes a valid slug.
* **Shared Services:** `UserLocationService` + `LocationRepository` remain the raw device-location source in the domain layer, while `LocationOriginService` is the canonical effective-origin policy for geo consumers. Controllers and geo repositories must consume the canonical location-origin result (`mode + reason + effective coordinate`) instead of branching inline on `tenantDefaultOrigin`, direct distance checks, or ad hoc `LocationOriginSettings` construction. User-facing notices are `reason`-driven and optional: if a given `reason` has no mapped message, no transient notice is rendered.
* **Task & Invite Hooks:** TaskStream integration is deferred post-MVP. Invite controllers must respect `Web-to-App Promotion Policy` by resolving preview-first context from `GET /invites/share/{code}`, allowing anonymous app acceptance through canonical `POST /invites/share/{code}/accept`, and using `POST /invites/share/{code}/materialize` or `POST /invites/{invite_id}/accept|decline` only when an authenticated continuation/materialized invite flow is explicitly required. Deferred deep-link V1 gate is Android-only (install -> first-open capture); iOS deferred capture remains VNext-scoped with fallback behavior in MVP. Web promotion/open-app targets must be consumed from backend-resolved tenant-dynamic store/open contracts (Android + iOS), with deterministic handoff selection (only invite-landing context `/invite|/convites` with valid `code` uses `/invite?code=...`, otherwise `/`), and first-open unresolved capture must route deterministically to `/`. Web identity boundaries must be route-based and shared across route-gated and action-gated flows; the canonical promotion screen must consume runtime environment branding (`nameValue`, `main_icon_*`) and may adaptively render a single Android/iOS store CTA when browser platform inference is reliable, with dual-badge fallback otherwise. For the temporary pre-MVP tester window, the same boundary may instead render an embedded tester waitlist form; that active selection is intentionally hardcoded in Flutter for now, but VNext must move the variant and lead-capture target to runtime/backend configuration. The Stitch-aligned tester waitlist variant currently collects `Seu Nome`, `E-mail`, `WhatsApp`, `SO`, and `O que não pode faltar para atender às suas expectativas?`, renders the former checklist content as a horizontal informational-card carousel below the primary CTA, and uses pop-only semantics for both close and `Continuar Navegando` in the success state. The tester waitlist submit path is provider-agnostic on the client and must post only to tenant-public backend endpoint `POST /api/v1/email/send`; submitted form content must be sent as an ordered generic `submitted_fields` envelope so backend email delivery remains decoupled from the Flutter form schema. Delivery-provider selection and secrets remain backend/admin-owned. Use `POST /contacts/import` instead of handling critical actions purely on the web.

#### 2.1.1 Presentation DI Matrix (Canonical)

This section is the canonical Flutter presentation DI/ownership contract. Rules/skills/lint docs must reference this matrix instead of duplicating full prose.

| Context | Allowed | Forbidden |
|---|---|---|
| Screen (`presentation/**/screens/**`) | Resolve same-feature controller via `GetIt` and consume controller-owned state/keys/controllers. | Resolve repository/service/DAO/backend/DTO; resolve cross-feature controller; own UI controllers/keys locally. |
| Auxiliary widget (`presentation/**/widgets/**`) isolated | Local UI controllers/keys only when fully local and not bridged into feature controller APIs. | Non-controller DI and cross-feature controller DI. |
| Auxiliary widget interacting with feature controller | Use feature-controller-owned UI controllers/keys and trigger controller intents only. | Keep local UI controller/key and pass/bridge it into feature controller methods. |
| Module class (`ModuleContract`) | `registerLazySingleton`, `registerFactory`, `registerRouteResolver`. | Direct `GetIt.I.register*`/`GetIt.instance.register*`. |
| Global bootstrap (`main.dart`, `ModuleSettings`, app bootstrap repository) | App-lifecycle non-UI services/contracts/gates/coordinators. | Global registrations using `*Controller` or `*ControllerContract` naming. |

Executable guardrails for this contract:
- Domain files cannot declare `fromJson`/`fromMap` factories; transport parsing belongs to DAO/DTO layers and infrastructure mappers.
- Domain fields must express validation/nullability through ValueObjects or domain-owned types instead of primitive transport fields.
- Repositories/services cannot parse raw JSON or hydrate DTOs inline; DAO is the transport ingestion boundary.
- Repositories cannot declare raw transport typing (`dynamic`, `Map<String, dynamic>`) in boundary signatures/helpers; DAO adapters own raw payload shapes.
- DTO -> Domain mapping is delegated to dedicated mapper files under `lib/infrastructure/dal/dto/mappers/**`.
- Files under `lib/**` should keep one public class per file; screen files still retain the stricter `multi_widget_file_warning` hygiene rule.

#### 2.1.2 Route-Driven Hydration Contract (Canonical)

This section defines objective hydration parameters for screen/detail flows. The contract is mandatory for all Flutter route surfaces.

| Parameter | Required Rule | Forbidden Pattern | Enforcement |
|---|---|---|---|
| Route hydration boundary | Route-level resolvers own URL-param to domain hydration. | Screen lifecycle (`initState`, `didUpdateWidget`) loading feature data from `widget.<path/query param>`. | `ui_route_param_hydration_forbidden` |
| Screen responsibility | Screen renders controller streams and emits user intents only. | Screen orchestrating fetch/hydrate logic tied to route params. | `ui_build_side_effects_forbidden`, architecture review |
| Controller ingress | Controllers accept canonical IDs/models from resolver/module contracts. | Controller hydration coupled to direct route widget state by screen glue logic. | `screen_controller_resolution_pattern_required`, code review |
| Route contract safety | Required non-URL args must be explicitly classified (`URL-Hydratable` or `Internal-Only`) and documented. | Implicit required args with no resolver/fallback path. | Route contract audit on `app_router.gr.dart` |

Hydration decision matrix:
- **Detail route with URL identity (`slug`, `id`)**: use `RouteModelResolver` (or resolver-equivalent module registration) to hydrate domain object or canonical ID before screen build.
- **Screen with resolved model/ID**: screen consumes controller stream state only; no route-param hydration call from screen lifecycle.
- **Internal-only route (not deep-link safe)**: route must declare deterministic fallback/guard and remain explicit in route contract audit.

No-exception guardrails:
- Do not call `controller.load/fetch/hydrate*(widget.<param>)` inside screen lifecycle methods.
- Do not bypass resolver discipline by moving hydration to helper methods still owned by screen lifecycle.
- If resolver cannot be applied, document the exception decision first and define lint/tests that constrain the fallback path.

#### 2.2 API Endpoint Definitions

| Endpoint | Method | Description | Required Role | Request Schema | Response Schema |
|----------|--------|-------------|---------------|----------------|-----------------|
| `/api/v1/environment` | GET | Resolves tenant branding/context for app bootstrap. | Anonymous | `EnvironmentRequest` | `EnvironmentResponse` |
| `/me` | GET | Delivers authenticated profile summary and role claims. | Tenant | `MeRequest` | `MeResponse` |
| `/invites` | GET | Retrieves pending invites and social proof metadata for the current user. | Tenant | `InviteFeedRequest` | `InviteFeedResponse` |
| `/invites/stream` | GET | Streams invite deltas for live updates. | Tenant | `InviteStreamRequest` | SSE delta events |
| `/invites/settings` | GET | Fetches invite limits and UX messaging settings. | Tenant | `InviteSettingsRequest` | `InviteSettingsResponse` |
| `/invites/share` | POST | Creates or returns a share code for an event invite. | Tenant | `InviteShareRequest` | `InviteShareResponse` |
| `/invites/share/{code}` | GET | Resolves invite preview payload for `/invite?code=...` before auth. | Tenant | n/a | `InviteSharePreviewResponse` |
| `/invites/share/{code}/accept` | POST | Canonical anonymous-first share acceptance endpoint for app invite conversion. | Tenant | `InviteShareAcceptRequest` | `InviteShareAcceptResponse` |
| `/invites/share/{code}/materialize` | POST | Optional authenticated continuation/pre-bind for share-code flows (not required for anonymous app acceptance). | Tenant | `InviteShareMaterializeRequest` | `InviteShareMaterializeResponse` |
| `/contacts/import` | POST | Imports hashed contacts for friend matching. | Tenant | `ContactsImportRequest` | `ContactsImportResponse` |
| `/agenda` | GET | Provides schedule entries, suggested actions, and contextual CTAs (`live_now_only=true` powers Discovery "Tocando agora"). Discovery MVP rendering is artist-driven: show the section only when live-now entries include at least one artist. | Tenant | `AgendaRequest` | `AgendaResponse` |
| `/events/stream` | GET | Streams event deltas for active filters. | Tenant | `EventStreamRequest` | SSE delta events |
| `/events/{event_id}` | GET | Returns event detail. | Tenant | `EventDetailRequest` | `EventDetailResponse` |
| `/events/{event_id}/check-in` | POST | Confirms presence for an event. | Tenant | `EventCheckInRequest` | `EventCheckInResponse` |
| `/map/pois` | GET | Returns POIs for the active viewport and filter set. | Tenant | `MapPoisRequest` | `MapPoisResponse` |
| `/map/pois/lookup` | GET | Resolves one POI by canonical typed reference (`ref_type + ref_id`). | Tenant | `MapPoiLookupRequest` | `MapPoiLookupResponse` |
| `/map/pois/stream` | GET | Streams POI deltas for active filters. | Tenant | `MapPoisStreamRequest` | SSE delta events |
| `/map/filters` | GET | Returns server-defined categories/tags for map filters, including optional marker override metadata. | Tenant | `MapFiltersRequest` | `MapFiltersResponse` |

*Success/Failure Handling:* All endpoints return `metadata.request_id` for tracing, success payloads encapsulated in `data`, and standardized error envelopes with `error.code`, `error.message`, `error.hints[]`. Security-hardening responses are also valid when emitted as top-level machine-readable fields (`code`, `message`, optional `retry_after`, `correlation_id`, `cf_ray_id`). Flutter clients must parse both forms without UX regression.
*Rate Limiting:* Soft limit of 5 req/min per endpoint during mock stage to mirror production throttles; burst handling delegated to controller retry strategies.

#### 2.3 Data Schemas

##### Deferred (post-MVP): home_overviews

**Schema Definition**

| Field | Type | Description | Required | Notes |
|-------|------|-------------|----------|-------|
| `_id` | ObjectId | Unique overview snapshot identifier. | Yes | Mirrors backend document ID. |
| `user_id` | ObjectId | Reference to the user receiving the overview. | Yes | Cached for mock personalization. |
| `hero_sections` | Array\<HeroSectionDocument\> | Ordered hero modules rendered at top of home. | Yes | Minimum 1 item. |
| `featured_offerings` | Array\<OfferingSummaryDocument\> | Highlighted offerings curated for the user. | Yes | Max 12. |
| `cta_banner` | CtaBannerDocument | Primary action banner targeting conversions. | No | Nullable when no banner active. |
| `social_proof` | Array\<SocialProofDocument\> | Invites and friend activity for viral loop. | Yes | Provide at least one entry. |
| `generated_at` | DateTime | UTC timestamp for snapshot generation. | Yes | ISO-8601 string in transport. |

**Field Definitions**

* `HeroSectionDocument.layout_type`: Valid values are `grid`, `carousel`, `single_callout` — defines widget template to instantiate.
* `OfferingSummaryDocument.cta_type`: Valid values are `follow`, `book`, `buy`, `share` — maps to localized CTA verbs.
* `CtaBannerDocument.priority`: Valid values are `high`, `medium`, `low` — determines placement stacking order.

##### Collection: invite_feeds

**Schema Definition**

| Field | Type | Description | Required | Notes |
|-------|------|-------------|----------|-------|
| `_id` | ObjectId | Feed snapshot identifier. | Yes | |
| `user_id` | ObjectId | User owning the invite queue. | Yes | |
| `invites` | Array\<InviteDocument\> | Active invites requiring attention. | Yes | Sorted by `created_at`. |
| `referral_chain` | Array\<ReferralNodeDocument\> | Historical inviter graph for analytics. | No | |
| `generated_at` | DateTime | Snapshot timestamp. | Yes | |

**Field Definitions**

* `InviteDocument.type`: Valid values are `tenant_share`, `account_profile_campaign`, `event_guestlist` — orchestrates controller handling.
* `InviteDocument.status`: Valid values are `pending`, `accepted`, `declined`, `expired` — drives UI badge state.
* `ReferralNodeDocument.relationship`: Valid values are `direct`, `indirect`, `influencer` — indicates invitation depth.

##### Collection: agenda_entries

**Schema Definition**

| Field | Type | Description | Required | Notes |
|-------|------|-------------|----------|-------|
| `_id` | ObjectId | Agenda item identifier. | Yes | |
| `user_id` | ObjectId | User associated with the entry. | Yes | |
| `schedulable_id` | ObjectId | Reference to offering/event. | Yes | |
| `schedulable_type` | String | Type discriminator for the schedulable item. | Yes | Mirrors backend polymorphic type. |
| `start_time` | DateTime | Event start timestamp. | Yes | ISO-8601. |
| `end_time` | DateTime | Event end timestamp. | No | Optional for instantaneous items. |
| `status` | String | Participation state. | Yes | |
| `cta` | CtaDescriptorDocument | Action user can take next. | Yes | |
| `metadata` | Map | Arbitrary structured data (e.g., dress code, location). | No | Key-value pairs. |

**Field Definitions**

* `schedulable_type`: Valid values are `event`, `experience`, `product_pickup`, `invite_task`.
* `status`: Valid values are `upcoming`, `checked_in`, `cancelled`, `completed`.
* `CtaDescriptorDocument.intent`: Valid values are `confirm`, `reschedule`, `share`, `review`.

##### Collection: map_pois

**Schema Definition**

| Field | Type | Description | Required | Notes |
|-------|------|-------------|----------|-------|
| `_id` | ObjectId | POI identifier. | Yes | |
| `ref_type` | String | Canonical source reference type. | Yes | `account_profile`, `event`, `static`. |
| `ref_id` | String | Canonical source reference id. | Yes | |
| `ref_slug` | String | Slug used by detail/share routes. | No | |
| `ref_path` | String | Canonical deep-link path segment. | No | |
| `category` | String | High-level POI category for rendering/filtering. | Yes | |
| `source_type` | String | Source subtype (for `types[]` filter). | No | |
| `tags` | Array\<String\> | Secondary classification tags. | No | |
| `priority` | Integer | Render stacking priority (higher first). | Yes | 0–100. |
| `location` | GeoPointDocument | Latitude/longitude used for map rendering. | Yes | |
| `is_active` | Boolean | Active visibility state for map queries. | Yes | |
| `avatar_url` | String | Optional media URL from source item. | No | |
| `cover_url` | String | Optional media URL from source item. | No | |
| `visual` | MapPoiVisualDocument | Projection-owned visual snapshot for marker rendering. | No | |
| `updated_at` | DateTime | Last projection update timestamp. | Yes | ISO-8601 in transport. |

**Field Definitions**

* `ref_type`: Valid values are `account_profile`, `event`, `static`.
* `visual.mode`: Valid values are `icon`, `image`.
* `visual.source`: Valid values are `type_definition`, `item_media`.
* `visual.icon` + `visual.color`: required when `visual.mode=icon`.
* `visual.image_uri`: required when `visual.mode=image`.

##### Deferred (post-MVP): profile_summaries

**Schema Definition**

| Field | Type | Description | Required | Notes |
|-------|------|-------------|----------|-------|
| `_id` | ObjectId | Profile snapshot ID. | Yes | |
| `user_id` | ObjectId | Primary user identifier. | Yes | |
| `display_name` | String | Render-ready name. | Yes | 1–64 chars. |
| `avatar_url` | String | Remote image URL. | No | Must be HTTPS. |
| `roles` | Array\<String\> | Active roles (tenant, account, promoter). | Yes | Non-empty. |
| `permissions` | Array\<String\> | Granted permissions or feature flags. | Yes | |
| `connected_accounts` | Array\<ConnectedAccountDocument\> | Accounts or profiles the user manages or follows. | No | |
| `last_synced_at` | DateTime | Timestamp for last profile sync. | Yes | |

**Field Definitions**

* `roles`: Valid values are `tenant`, `account`, `promoter`.
* `permissions`: Valid values are `manage_pois`, `send_notifications`, `access_reports`, `beta_feature`.
* `ConnectedAccountDocument.relationship`: Valid values are `owner`, `manager`, `fan`.

#### 2.4 Event & Messaging Contracts

* **Outbound Events:** `app.session_bootstrapped` emitted when the bootstrap sequence finalizes, payload includes `user_id`, `active_modules`, `timestamp`. `app.invite_consumed` fired when an invite transitions to `accepted`.
* **Inbound Events:** SSE delta events (`poi.created`, `poi.updated`, `poi.deleted`, `event.created`, `event.updated`, `event.deleted`) simulated through mock streams; controllers ensure idempotent application by comparing `event.sequence`.
* **Queue/Topic Configuration:** FCM topics follow `account_profile_{account_profile_id}` naming; mocked notifier replicates topic subscription flow to guarantee DI wiring.

#### 2.5 Background Jobs & Schedulers

* Application schedules `DailyRefreshJob` (7 AM local) to refresh cached invites and agenda using background fetch APIs.
* `InviteExpirySweep` runs hourly to mark stale invites and emit UI updates.
* Jobs delegate to controllers’ services and honor app lifecycle (pause/resume) to avoid stale state.

#### 2.6 Observability & Instrumentation

* **Logs:** Structured debug logs via `dart:developer` with fields `{event, controller, payloadHash}`; upload to Crashlytics in production.
* **Metrics:** Custom analytics events (`home_section_view`, `invite_action`, `poi_tap`) proxied through a unified `AnalyticsService`.
* **Tracing:** Session traces captured with Firebase Performance; spans named `Controller::<Action>` (e.g., `TenantHomeController::loadHome`).
* **Alerts:** Crash-free sessions threshold ≥99%; analytics anomaly detection (invite acceptance drop >20 % triggers alert).
* **Telemetry Context:** `/api/v1/environment` returns `telemetry.location_freshness_minutes` (default 5) to gate location context enrichment on emitted events.

#### 2.7 Testing Strategy

The Flutter client must separate regression confidence from real compatibility evidence. A green fake/UI-flow suite is never enough to claim Flutter↔Laravel safety for invite-critical paths.

**Canonical taxonomy**

| Test Class | Scope | Counts as Real Compatibility? | Required Environment | Notes |
| --- | --- | --- | --- | --- |
| Unit / widget / controller tests | Value-object validation, controller state transitions, route-local UI behavior | No | local-safe + CI | Mandatory for fast regression feedback. |
| UI-flow `integration_test` with fakes | AutoRoute/DI/StreamValue behavior under controlled doubles | No | local-safe + CI | These prove state-machine integrity only; they must never be reported as backend compatibility evidence. |
| Repository / decoder contract tests | Flutter transport boundary (`preview`, `materialize`, `accept`, `decline`, malformed payloads, terminal states) | No | local-safe + CI | Required whenever invite payload shape changes. |
| Real Flutter runtime compatibility suite | Real backend, real repositories, real controller/runtime behavior for invite critical paths | Yes | `stage` only | Must run against the deployed `stage` backend, not mocks or local doubles. |
| Web/browser compatibility suite | Invite landing, preview, auth redirect preservation, fallback behavior, `.well-known`/deeplink artifacts | Yes for browser boundary | `stage` only for mutation; `readonly` can run on `stage|main` | Executed through `tools/flutter/run_web_navigation_smoke.sh`. |
| OS/device deep-link validation | Android App Links / iOS Universal Links open behavior | Manual evidence only | physical device/simulator | Never replaced by browser or repository tests. |

**Invite-critical coverage baseline**

Invite flows must be proven in layers:
- Laravel feature/package tests own canonical invite business rules.
- Flutter repository/decoder tests own payload semantics and drift detection.
- Flutter controller/widget tests own state-machine and navigation regressions.
- `stage` runtime/browser suites own real compatibility proof.

**Environment gates**

| Environment | Required Invite Gates | Claim Allowed |
| --- | --- | --- |
| `dev` | Laravel local-safe invite tests + Flutter unit/widget/repository tests | Contract-safe locally, but not real deployed compatibility |
| `stage` | Real Flutter runtime invite suite + Playwright/browser invite suite | Real deployed compatibility for invite critical paths |
| `main` | Read-only smoke only | Production-readiness smoke, never mutation-backed compatibility proof |

**Execution honesty**

- A required invite gate that did not run is `blocked`, never `passed`.
- Flutter invite tests that use fake repositories, fake routers, or fake controllers must be labeled as regression coverage, not end-to-end or compatibility coverage.
- Stage invite compatibility must use deterministic fixtures/test support. It must not depend on shared manual data.
- Web invite validation proves host/domain tenant resolution.
- Flutter runtime invite validation proves mobile/app-domain tenant resolution.

## 3. Cross-Module Considerations

* **Partner Naming (Tenant Labels):** “Partner” is a tenant-facing label applied to Account Profiles. The label system is a future capability (post‑MVP) that lets tenants rename or group profile types without changing the underlying Account Profile model.
* **Shared Libraries:** `lib/application` hosts theming and localization contracts; `lib/presentation/shared/widgets` houses reusable components (e.g., `MainLogo`, `BellugaBottomNavigationBar`); `lib/domain/value_objects` encapsulates validation logic shared across modules; `packages/` hosts internal reusable Flutter packages whose public APIs remain transport-agnostic and module-aligned.
* **Data Ownership Boundaries:** Mock repositories remain the single source of truth for state; cached DTOs never overwrite domain models without controller orchestration.
* **Failure & Degradation Modes:** When SSE streams disconnect, controllers downgrade to polling (`/map/pois`) and surface passive UI states; offline mode caches last successful responses and displays timestamped banners.
* **Map marker visual precedence:** runtime marker rendering applies `active_filter.marker_override` (when valid) -> `poi.visual` -> single generic fallback; override scope is marker-only.
* **Map filter activation contract:** runtime filters are mutually exclusive, leaving exactly one active filter context at a time.

### 3.1 Reusable Form Validation Package Baseline

* `packages/belluga_form_validation/` is the canonical reusable Flutter package for package-scoped form validation concerns in V1.
* The package boundary is transport-agnostic:
  * infrastructure/repositories parse backend `422` envelopes and construct typed validation failures;
  * the package resolves those failures into UI validation state;
  * screens/controllers never parse transport objects directly.
* Validation target kinds are fixed to `field`, `group`, and `global`.
* Matching baseline for V1:
  * exact key matching,
  * wildcard/glob matching,
  * narrow key normalization for matching,
  * unmapped keys fall back to `global` and emit developer diagnostics in non-production modes.
* State ownership baseline:
  * each form owns one controller-held validation `StreamValue`;
  * screens consume that stream through granular builders/widgets;
  * per-field/per-group streams are not part of the baseline.
* Rendering hierarchy baseline:
  * `field` -> `InputDecoration.errorText`
  * `group` -> inline group validation widget
  * `global` -> inline form-level validation summary/banner
* Multi-message behavior baseline:
  * field surfaces show the first message only;
  * group/global surfaces show a collapsed summary and support inline expand/collapse.
* Navigation/visibility baseline:
  * ordered binding declaration defines first-invalid-target priority;
  * the package provides anchor + scroll helpers;
  * screens trigger scroll-to-first-invalid-target;
  * focus remains feature-owned and optional.
* Validation source baseline:
  * local pre-submit validation remains feature-owned;
  * backend `422` validation remains repository-originated;
  * both sources must feed the same package validation state and render through the same surfaces.

## 4. Implementation Notes

* **Code Structure:** Four-layer directory layout (`application/`, `domain/`, `infrastructure/`, `presentation/`) with feature-first organization under `presentation/tenant/screens/**`. Each screen owns a controller in `controllers/` and a repository contract in `domain/repositories/`. Internal reusable packages live under `packages/` when the concern must be shared across multiple forms/features without collapsing domain/infrastructure boundaries.
* **Configuration Management:** `.env.dart` defines environment toggles; `MockEnvironmentConfig` provides endpoints, feature flags, and asset URLs; secrets never hardcoded.
* **Deployment Pipeline:** CI runs `flutter analyze`, `flutter test`, golden diffs, and build_runner. Artifacts published as APK/IPA for internal distribution with mock flag enabled.
* **Validation Regression Gate:** Any reusable validation package adoption must carry package tests plus first-adopter regression tests for success paths, non-validation failures, and capability-driven sections before it can be promoted as canonical.

## 5. Decision Log

| Decision ID | Date | Module(s) | Summary | Status | Rationale | Linked Evidence |
|-------------|------|-----------|---------|--------|-----------|-----------------|
| DEC-201-001 | 2025-02-14 | MOD-201 | Controllers own all mutable state via StreamValue, widgets stay stateless. | Approved | Aligns with architecture overview and prevents state divergence. | flutter-app/foundation_documentation/flutter_architecture.md |

## 6. Appendices

* **Reference APIs:** Laravel backend contracts defined in MOD-101 (pending).
* **Security Review Checklist:** Enforce HTTPS-only asset loading; sanitize invite codes before display; gate account workspace dashboards behind role checks.
* **Operational Runbooks:** `docs/runbooks/flutter_bootstrap.md` (to be authored) will outline cold-start troubleshooting, mock backend rotation, and DI registration audits.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `FCX-01` | Approved | Controller-owned state is mandatory; widgets remain presentational. | Prevents state duplication and architecture drift. | Section `2.1 Domain Rules` + `DEC-201-001` |
| `FCX-02` | Approved | Scope/subscope route ownership must follow governance matrix. | Blocks route ambiguity across landlord/tenant/account-workspace. | Section `2.0 Scope/Subscope Ownership` |
| `FCX-03` | Approved | Flutter consumes backend contracts via domain repositories and contract-tested adapters. | Keeps UI resilient during mock→real backend transition. | Sections `2.2`, `2.7`, `3` |
| `FCX-04` | Approved | Reusable form validation is package-scoped, transport-agnostic, and controller-owned via one validation stream per form. | Standardizes `422` handling and inline validation rendering without breaking presentation/infrastructure boundaries. | Section `3.1` + `4` |
| `FCX-05` | Approved | Map marker rendering precedence is `active_filter.marker_override` (valid only) -> `poi.visual` -> single generic fallback, with mutually exclusive active filter state. | Removes hardcoded visual coupling while preserving deterministic runtime behavior. | Sections `2.2`, `2.3`, `3` |
| `FCX-06` | Approved | Tenant-public direct-entry routes must use one shared safe-back policy (`local-state first -> stack-first return -> route-family fallback`) with approved no-history destinations for `/agenda`, `/agenda/evento/:slug`, `/parceiro/:slug`, `/static/:assetRef`, `/mapa`, and `/mapa/poi`. | Prevents empty-root/dead-end back behavior without breaking source continuity for normal in-app navigation. | Sections `2.1`, `3` |
| `FCX-07` | Approved | Route-back governance is structural and centralized: governed route families are declared by `canonicalRouteMeta(...)`, governed screens/shells consume canonical typed helpers, and `deepLinkBuilder` is limited to root-scoped startup overrides rather than generic browser-history synthesis. Warm web/browser/device back remains native when history exists, while canonical helpers own deterministic no-history outcomes. | Prevents new route surfaces from inventing private back behavior while keeping enforcement objective and analyzer-friendly. | Section `2.1` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-events-and-agenda-frontend.md` | Events/agenda client contracts and UX integration | Completed | `2.2`, `2.3`, `3` | Maintains occurrence-first event consumption. |
| `TODO-v1-invites-implementation.md` | Invite/social flow delivery in client | Completed (2026-03-12) | `2.2`, `2.4`, `2.5` | Share acceptance + contacts import paths. |
| `TODO-v1-map-frontend.md` | Map rendering/filter/stacking contracts | In progress | `2.2`, `2.3`, `2.4` | Aligns with projection-backed map APIs. |
| `TODO-v1-map-icon-color-config.md` | Type-driven POI visual rendering and filter marker override consumption | Completed | `2.2`, `2.3`, `3` | Archived in `todos/completed`; runtime consumes `map_pois.visual` + override precedence with single fallback path. |
| `TODO-v1-tenant-public-safe-back-navigation.md` | Centralized tenant-public safe-back policy and direct-entry fallback matrix | Completed | `2.1`, `3`, `7` | Shared back behavior for event detail, partner detail, agenda, and map; archived from `active` during the 2026-04-09 MVP TODO cleanup after delivery confirmation. |
| `TODO-v1-canonical-back-navigation-governance-cutover.md` | Canonical route-back governance across tenant-public, tenant-admin, landlord root, workspace, and identity-boundary entry routes | In progress | `2.1`, `7` | Structural rule promoted now: governed route families are meta-declared, screens/shells consume canonical helpers, and semantic linting remains a later phase. |
| `TODO-vnext-tenant-user-account-profile-area.md` | Workspace scope and route ownership | In progress | `2.0`, `3` | Account workspace/subscope integrity. |
