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
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`

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
* **Authorization Requirements:** Anonymous flow is limited to onboarding/bootstrap/read-only surfaces; invite share-code acceptance is identity-first (authenticated user required). Authenticated tenant scope unlocks home, schedule, map; account workspace scope exposes account/profile dashboards (future flavor); promoter scope requires explicit feature flag.
* **Shared Services:** `UserLocationService` + `LocationRepository` live in the domain layer. Controllers (Map, Search, Invite Check-in) inject the service to request permission, seed initial filters, and pass coordinates to repositories. Repositories never call each other; services wrap a single repository per architecture principle §2.5.
* **Task & Invite Hooks:** TaskStream integration is deferred post-MVP. Invite controllers must respect `Web-to-App Promotion Policy` by resolving preview-first context from `GET /invites/share/{code}`, then deep-linking/auth-round-tripping to `POST /invites/share/{code}/materialize` before exposing decision UI; once materialized, all decisions must use the canonical invite endpoints `POST /invites/{invite_id}/accept|decline`. Use `POST /contacts/import` instead of handling critical actions purely on the web.

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
| `/invites/share/{code}/materialize` | POST | Creates or reuses the canonical invite edge for the current authenticated user before decision UI is shown. | Tenant | `InviteShareMaterializeRequest` | `InviteShareMaterializeResponse` |
| `/contacts/import` | POST | Imports hashed contacts for friend matching. | Tenant | `ContactsImportRequest` | `ContactsImportResponse` |
| `/agenda` | GET | Provides schedule entries, suggested actions, and contextual CTAs. | Tenant | `AgendaRequest` | `AgendaResponse` |
| `/events/stream` | GET | Streams event deltas for active filters. | Tenant | `EventStreamRequest` | SSE delta events |
| `/events/{event_id}` | GET | Returns event detail. | Tenant | `EventDetailRequest` | `EventDetailResponse` |
| `/events/{event_id}/check-in` | POST | Confirms presence for an event. | Tenant | `EventCheckInRequest` | `EventCheckInResponse` |
| `/map/pois` | GET | Returns POIs for the active viewport and filter set. | Tenant | `MapPoisRequest` | `MapPoisResponse` |
| `/map/pois/stream` | GET | Streams POI deltas for active filters. | Tenant | `MapPoisStreamRequest` | SSE delta events |
| `/map/filters` | GET | Returns server-defined categories/tags for map filters. | Tenant | `MapFiltersRequest` | `MapFiltersResponse` |

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
| `account_profile_id` | ObjectId | Owning account profile reference. | Yes | |
| `category` | String | High-level POI category. | Yes | |
| `tags` | Array\<String\> | Secondary classification tags. | Yes | Max 10. |
| `priority` | Integer | Render stacking priority (higher first). | Yes | 0–100. |
| `geo` | GeoPointDocument | Latitude/longitude and viewport metadata. | Yes | |
| `live_status` | String | Current live state. | Yes | |
| `available_offers` | Array\<OfferDocument\> | Offers attached to the POI. | No | |

**Field Definitions**

* `category`: Valid values are `food_drink`, `music`, `art`, `nature`, `mobility`.
* `live_status`: Valid values are `static`, `live_event`, `sponsored_highlight`.
* `OfferDocument.kind`: Valid values are `discount`, `bundle`, `vip_pass`.

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

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-events-and-agenda-frontend.md` | Events/agenda client contracts and UX integration | Completed | `2.2`, `2.3`, `3` | Maintains occurrence-first event consumption. |
| `TODO-v1-invites-implementation.md` | Invite/social flow delivery in client | Completed (2026-03-12) | `2.2`, `2.4`, `2.5` | Share acceptance + contacts import paths. |
| `TODO-v1-map-frontend.md` | Map rendering/filter/stacking contracts | In progress | `2.2`, `2.3`, `2.4` | Aligns with projection-backed map APIs. |
| `TODO-v1-tenant-user-account-profile-area.md` | Workspace scope and route ownership | In progress | `2.0`, `3` | Account workspace/subscope integrity. |
