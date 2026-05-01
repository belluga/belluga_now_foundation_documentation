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
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-invites-occurrence-target-migration.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-funnel-metrics-validation.md`
  - `foundation_documentation/todos/completed/TODO-store-release-account-profile-rich-text-fidelity.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-onboarding-identity-reconciliation-reflection.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`
  - `foundation_documentation/todos/completed/TODO-v1-invites-implementation.md`
  - `foundation_documentation/todos/completed/TODO-v1-map-frontend.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`

## 2. Module Specification

### MOD-201: Flutter Client Experience Module

* **Purpose Statement:** Establish the foundational Flutter application that orchestrates tenant and account/profile experiences through a clean architecture stack (presentation, domain, infrastructure) wired to Laravel-backed service contracts.
* **Core Entities:** User, Account, Account Profile, Event, Event Occurrence, Invite Edge, Favorite Edge.
* **Key Workflows:** Adaptive onboarding, tenant home discovery, invite and social growth loop, agenda management, map exploration with POIs, authenticated profile utilities.
* **External Dependencies:** AutoRoute (navigation), GetIt (DI container), StreamValue (reactive state wrapper), value_object_pattern, Firebase Cloud Messaging (future push integration), Laravel HTTP + SSE backends.
* **Service-Level Objectives:** Screen state transitions <150 ms under cached/remote data; cold-start bootstrap <2.5 s on mid-range devices with backend available; navigation stack integrity with zero controller leaks; 100 % controller-stream parity (no orphaned state).

#### 2.0.1 Runtime Backend Mandate (V1 Launch)

- Compiled/runtime app path is **Laravel-only**. Runtime mock fallback is forbidden.
- Mock adapters/datasets are allowed only in test targets through explicit test injection.
- Startup must hard-stop when backend bootstrap is unavailable and require user retry after connectivity recovers.
- Discovery runtime contract is account/account-profile based; legacy mock discovery/profile-detail content providers and audio player service are out of MVP runtime scope.

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
* **Authorization Requirements:** Progressive profiling is preview-first for invite conversion: anonymous users may resolve share preview and carry session context, but explicit invite accept/decline is a trust mutation requiring a registered/authenticated identity before execution. Authenticated app upgrade is phone-OTP only: Flutter tenant-public auth must request `POST /auth/otp/challenge`, then verify `POST /auth/otp/verify`, passing the current anonymous identity id when present so backend merge preserves invite/history attribution. OTP delivery is backend-owned and queued through outbound webhook integration settings (`outbound_integrations.whatsapp.webhook_url`, optional `outbound_integrations.otp.webhook_url`, and OTP channel/cooldown/TTL settings); Flutter never owns provider secrets, templates, webhook URLs, or synchronous delivery. The public environment may expose only derived OTP delivery flags such as `settings.tenant_public_auth.phone_otp.primary_channel` and `settings.tenant_public_auth.phone_otp.sms_fallback_enabled`. The tenant-public OTP UI must show the SMS secondary action from that boolean flag, never by consuming or inferring webhook URLs from app data. The anonymous app baseline is explicit: invite preview/session context, feed browsing, map browsing, and favorites may continue without forced login. Auth Wall applies only to the explicitly restricted actions frozen by the tenant-public auth baseline (for example invite accept/decline, `send_invite`, identity-owned routes such as `/profile`, and attendance/check-in boundaries). On web tenant-public surfaces, anonymous hard/auth gates must resolve to app-promotion handoff (never anonymous web-login continuation). Authenticated web is QR-only and assumes an already promoted app identity; once QR-authenticated, the user is in the normal authenticated web posture for that surface rather than a special anonymous-web capability subset. Identity-owned routes follow the same split: unauthenticated app access to `/profile` continues to native auth/login, while unauthenticated web access to `/profile` resolves to app-promotion UX/handoff until a QR-authenticated web session exists. Public map routes are soft-gated by location only: `/mapa` and `/mapa/poi` continue through the canonical `/location/permission` surface, but `Continuar sem localização` must still resume the original target using the fixed-reference map fallback for that access.
* **Post-Auth Identity Hydration Contract:** After the auth state changes from anonymous/no user to a registered user identity, the Flutter application shell owns one idempotent hydration pass for user-linked repository state. The Store Release launch set is: Home favorite resumes, Account Profile favorite ids, confirmed occurrence ids, and pending invites. The coordinator must ignore anonymous identities, deduplicate repeated emissions for the same `user_id`, and call repository refresh methods rather than screen/controller load methods. Modules that introduce authenticated user-linked state must expose an idempotent repository refresh contract and register/document it as a post-auth hydration consumer before release. Screens must not compensate with route restarts, cross-controller relays, or local "after login" reloads. Empty backend results for the registered identity are authoritative and must clear stale identity-local state.
* **Anonymous Web Route Allowlist (V1):** tenant-public anonymous web is explicitly allowlisted, not open-by-default. Public direct URL surfaces are limited to `/`, `/privacy-policy`, `/descobrir`, `/parceiro/:slug`, `/agenda/evento/:slug`, `/static/:assetRef`, `/mapa`, `/mapa/poi`, `/invite?code=...`, `/convites?code=...`, `/location/permission`, and `/baixe-o-app`. Blocked non-identity routes such as `/agenda` or invalid invite URLs fall back to `/`; identity/auth-owned routes such as `/profile`, `/workspace*`, and `/auth/*` resolve to the canonical app-promotion boundary on web. For map routes specifically, the location gate preserves the originally requested target and never collapses back to Home after permission resolution.
* **Web Bootstrap Visual Continuity Contract:** On tenant-public web, the pre-Flutter HTML splash, its loading/progress affordances, and the first visible Flutter `InitRoute` frame must resolve one coherent branding presentation contract: the same logo family, the same branded boot background, and contrast-safe loader styling. Splash teardown must be fade-based and first-paint-driven; raw Flutter host-node presence alone is not a sufficient readiness signal. The public shell favicon reference is canonical `/favicon.ico` and must not point to bundled static files.
* **Tenant-Public Web Metadata Fallback Contract:** Laravel-owned public shell metadata remains route-specific for `/parceiro/:slug`, `/agenda/evento/:slug`, and `/static/:assetRef`. For the allowlisted tenant-public HTML route family (`/`, `/descobrir`, `/privacy-policy`, `/mapa`, `/mapa/poi`, `/invite?code=...`, `/convites?code=...`, `/location/permission`, `/baixe-o-app`), `og:url`/canonical must use the requested URL while `og:title`, `og:description`, and `og:image` fall back to `branding_data.public_web_metadata` and only then to the current default fallback. `/admin/*`, `/api/*`, backend-owned branding asset routes, and media/file endpoints must never be captured by that public-shell fallback.
* **Tenant Web Icon Contract:** Browser favicon and installable PWA icon are separate runtime assets. `/favicon.ico` is the canonical browser-tab route and resolves by fallback chain `favicon_uri -> tenant pwa icon192 -> tenant pwa icon512 -> tenant pwa source -> landlord defaults`. Manifest `/icon/...` routes remain the installable PWA icon contract and must stay PNG-backed and independent from favicon semantics.
* **Tenant-Public Desktop Web Mobile-Frame Contract:** On wide desktop web viewports, the shared tenant-public mobile-first route family (`/`, `/descobrir`, `/parceiro/:slug`, `/static/:assetRef`, `/agenda/evento/:slug`, `/invite?code=...`, `/convites?code=...`, `/baixe-o-app`, `/mapa`, `/mapa/poi`, `/location/permission`, and the web identity boundary that resolves through app-promotion) must render inside one centered mobile-width frame instead of stretching full width. The current shared max width is `430` logical pixels. This is a presentation-shell rule only: controller ownership, route guards, route semantics, and web-to-app promotion behavior remain unchanged. Landlord, tenant-admin, and workspace shells are explicitly excluded from this framing rule.
* **Tenant-Public Safe Back Contract:** tenant-public discovery, public-detail, and public-map surfaces must use one centralized canonical route-back policy: consume route-local UI state first when the screen explicitly owns it, then remove the previous route if stack history exists, and only then execute the route-family no-history fallback when the route is root-opened. The approved no-history fallback matrix is `/descobrir -> /`, `/agenda -> /profile`, `/agenda/evento/:slug -> /`, `/parceiro/:slug -> /descobrir`, `/static/:assetRef -> /descobrir`, `/mapa -> /`, `/mapa/poi -> /mapa`. System back, visible back buttons, and shared immersive-detail back actions must all delegate to that same contract.
* **Canonical Route Back Governance Contract:** Flutter route-back ownership is structural and meta-driven. Every governed route family must declare `canonicalRouteMeta(...)` in AutoRoute module definitions; governed screens must consume `buildCanonicalCurrentRouteBackPolicy(...)`; governed shells that render the active child route must consume `buildCanonicalRouteBackPolicyForRouteData(...)`; and app ingress may apply only root-scoped startup overrides through `deepLinkBuilder` via `AppStartupNavigationCoordinator`. Warm web/browser/device back must remain AutoRoute/browser-native whenever real history exists; centralized governance owns visible back, system back fallback, and deterministic no-history outcomes, not synthetic browser-history seeding. Shared back-owning widgets may accept injected handlers/policies but must not hide local fallback semantics. Route-policy `PopScope` ownership belongs to `RouteBackScope`, not to ad-hoc screen code. Result-return or overlay-only flows remain explicitly exempt until they are migrated into a typed route-policy form; `/location/permission` is the current approved exception and must surface typed `granted|continueWithoutLocation|cancelled` outcomes to the caller, with guarded flows allowed to dismiss the permission route after callback delivery while direct/no-history entry still falls back to `/`.
* **Immersive Detail Theme Contract:** Tenant-public immersive detail routes `/agenda/evento/:slug`, `/parceiro/:slug`, and `/static/:assetRef` must derive their active `ColorScheme` from the hero image through the shared `ImagePaletteTheme` adapter, with deterministic fallback to the ambient app theme when media is missing or extraction fails. `ImmersiveDetailScreen` remains a pure consumer of the resolved theme and must not own palette extraction logic.
* **Event Hero Fallback Contract:** Tenant-public event hero/image resolution is deterministic and contract-driven: use `event.thumb` first, then `linked_account_profiles` in canonical order (`cover_url -> avatar_url` per profile), then `venue` media. Flutter must not fall back to legacy `artists` when deriving event hero imagery or palette inputs.
* **Type Visual Consumption Contract:** Flutter consumers must treat profile-type `visual` as the canonical type identity input across map and non-map surfaces. `mode=image` must render the resolved media according to `image_source` (`avatar|cover|type_asset`) instead of silently degrading to icon mode; `type_asset` uses the canonical type-owned image URL exposed by the registry, while `avatar|cover` resolve against item media. The visual color remains an accent/marker input for image-backed visuals and must be preserved through admin forms, DTO parsing, filter catalogs, and fallback states. Fallback to generic/default visuals is only valid when the required media asset is absent, invalid, or fails to load. Map routes still consume projection-owned `map_pois.visual` snapshots rather than deriving marker visuals directly from registry payloads.
* **Taxonomy Display Snapshot Consumption Contract:** Flutter taxonomy consumers must preserve display-ready term snapshots shaped as `{type, value, name, taxonomy_name, label?}`. UI labels resolve in strict order `name -> label -> value`; raw slug/value display is only the legacy fallback. Query payloads, selected filters, and map/filter requests continue to use machine keys (`type`, `value`, flattened `type:value`) and must not send display labels as filter identities.
* **Account Profile Visual Resolution Contract:** Public account-profile surfaces must resolve media per surface family instead of reusing one global fallback chain. Hero/background surfaces use `cover > avatar > type visuals`; compact rows use `avatar > cover > type visuals`; shared identity-avatar blocks use the real avatar when present and otherwise fall back to the canonical `type visual` avatar surface. When a real avatar exists, the `type visual` becomes a badge overlay on that avatar instead of a post-name label. Discovery cards and public account-profile-detail heroes must share the same identity language and must not render the old textual type eyebrow. When the final hero source is image-backed, immersive route theming must derive from that same image; when the final fallback is icon/color-only `type visual`, the route theme must derive from the `type visual` seed color instead of silently reverting to the ambient scheme.
* **Account Profile Rich Text Contract:** Tenant-public account-profile detail treats `bio` and `content` as independent capability-backed long-form rich-text fields. The detail route keeps one `Sobre` shell; both fields render as ordered blocks only when their capability and content are present, with `bio` before `content`, and a single present field does not repeat the shell label as a nested heading. Account Profile and Event content share the same safe rich-text canonicalizer subset (`<p>`, `<br>`, `<h1-6>`, lists, blockquotes, `<strong>`, `<em>`, `<s>`, emoji/plain text). Legacy plain text must preserve line and paragraph breaks at render time. Tenant-admin Account Profile/onboarding editors must expose the backend-aligned `100KB` per-field limit guidance and soft warning around `90%`, while backend `422` remains authoritative.
* **Immersive Event Detail Profile Category Contract:** Tenant-public immersive event detail must treat linked account profiles as grouped profile categories rather than a hardcoded artist-only lineup. Stable tabs are `Sobre` and `Como Chegar`; dynamic profile tabs render between them using plural profile-type labels from bootstrap metadata. `Sobre` renders the canonical sanitized `event.content` HTML subset, not arbitrary raw HTML. Unsupported tags are not valid persisted content, media-only/non-text markup does not count as valid `Sobre`, and frontend editing UX must sanitize or block unsupported markup so the UI does not imply false acceptance before save. The shared subset is `<p>`, `<br>`, `<h1-6>`, `<ul>`, `<ol>`, `<li>`, `<blockquote>`, `<strong>`, `<em>`, and `<s>`; links, underline, inline code, and color-style extras are stripped before submit/render, while emojis remain plain text. `Como Chegar` reuses the account-profile map/directions language with event-owned venue/location inputs instead of duplicating venue semantics inside the dynamic category tabs. Linked-profile cards must keep direct `/parceiro/:slug` navigation, expose a top-right favorite affordance, and rely on a strict runtime `slug` contract rather than local lookup or request-time enrichment fallbacks. Missing `linked_account_profiles[].slug` is a payload-contract failure, not a valid “disabled card” state. Contextual tab footers are screen-gated: they may replace the default immersive footer only when the host screen explicitly allows that override, and returning to tab index `0` must reset both inner and outer scroll stacks to the true top.
* **Immersive Event Multi-Occurrence Contract:** Tenant-public event lists and cards remain occurrence-first and occurrence-only. Event detail keeps route identity on `/agenda/evento/:slug` and passes selected occurrence context through optional query metadata `occurrence=<occurrence_id>`. When an event has more than one occurrence, detail renders `Datas` after `Sobre`; that tab renders one card per date, highlights the current selected occurrence, and navigates by replacing the same detail route with the selected occurrence query. `Programação` appears only when the selected occurrence has programming items and renders cards with time at left, title/fallback at right, and linked Account Profile row below the title. Missing or stale occurrence query data must repair to the backend-selected fallback without changing event identity. Presence confirmation, invite share-code generation, invite status badges, and confirmed-only filters must use the selected occurrence identity; parent `event_id` remains route/read context only.
* **Tenant-Admin Related Account Ordering Contract:** Tenant-admin event forms must expose explicit ordering controls for related account profiles. That UI order is canonical for the write payload and governs downstream public hero fallback over `linked_account_profiles`.
* **Favorites Strip Preview Contract:** Tenant-home favorites remains snapshot-backed. For `account_profile` targets, the preview block must carry `avatar_url`, `cover_url`, and `profile_type`; Flutter resolves `type` label/visual from the app bootstrap registry and applies the compact precedence `avatar > cover > type visuals`. Event presence emphasis is also snapshot-driven: no halo with no event, subtle halo for upcoming event, stronger halo for live-now event. Favorites navigation still resolves directly to `/parceiro/:slug` whenever the snapshot exposes a valid slug.
* **Shared Services:** `UserLocationService` + `LocationRepository` remain the raw device-location source in the domain layer, while `LocationOriginService` is the canonical effective-origin policy for geo consumers. Controllers and geo repositories must consume the canonical location-origin result (`mode + reason + effective coordinate`) instead of branching inline on `tenantDefaultOrigin`, direct distance checks, or ad hoc `LocationOriginSettings` construction. User-facing notices are `reason`-driven and optional: if a given `reason` has no mapped message, no transient notice is rendered.
* **Task & Invite Hooks:** TaskStream integration is deferred post-MVP. Invite controllers must respect `Web-to-App Promotion Policy` by resolving preview-first context from `GET /invites/share/{code}`, requiring authenticated/promoted identity before canonical `POST /invites/share/{code}/accept`, and using `POST /invites/share/{code}/materialize` or `POST /invites/{invite_id}/accept|decline` only when an authenticated continuation/materialized invite flow is explicitly required. After a valid share-code preview resolves, Flutter may hold a session-only invite context keyed by `share_code + occurrence_id` so event detail and invite surfaces can render the pending invite effect before explicit acceptance; this context must not use persistent local storage, must not create a remote intent entity, and must not create an `invite_edge` before authenticated share-code acceptance. Deferred deep-link V1 gate is Android-only (install -> first-open capture); iOS deferred capture remains VNext-scoped with fallback behavior in MVP. Web promotion/open-app targets must be consumed from backend-resolved tenant-dynamic store/open contracts (Android + iOS), and the handoff must preserve the requested continuation intent: invite landing with valid `code` preserves `/invite?code=...`, while direct detail routes and guard-triggered promotions preserve the requested redirect path so the app can restore event/detail/profile intent after install/open. First-open unresolved capture must route deterministically to `/`. Web identity boundaries must be route-based and shared across route-gated and action-gated flows; the canonical promotion screen must consume runtime environment branding (`nameValue`, `main_icon_*`) and render the app-promotion/store handoff experience, not the old pre-MVP tester waitlist form. When browser platform inference is reliable, the promotion screen may adaptively render a single Android/iOS store CTA; otherwise it must use dual-badge fallback. Authenticated web session bootstrap is QR-only, while authenticated app upgrade remains phone-OTP only. The anonymous app baseline remains invite preview/session context, feed browsing, map browsing, and favorites. Use `POST /contacts/import` instead of handling critical actions purely on the web.
* **Inviteable Composer Contract:** `/convites/compartilhar` must render one backend-computed in-app inviteable list by default, deduplicated by canonical recipient before UI composition. Each visible row preserves relation metadata (`contact_match`, `favorite_by_you`, `favorited_you`, `friend`) so the screen can filter by relation type without duplicating entries. Filter chips must follow the same interaction pattern and visual language used by Account Profile Discovery filters. Type gating comes from `profile_types.capabilities.is_inviteable`; hash-based contact discovery still respects instance-level `discoverable_by_contacts`, and the final row media/identity payload must continue honoring the resolved `profile_exposure_level`. The Flutter contact import path sends only hash/type payloads remotely; raw names, phones, and emails stay local. Normal composer entry must hydrate contacts from local memory/cache where available and may skip unchanged fresh full-hash imports through a local import-signature marker scoped to viewer/region. Explicit refresh remains the user-owned way to rescan the device contact book and force reimport. Because import may be skipped on a fresh signature, backend inviteable rows that carry `contact_hash` for `contact_match` are also part of local `Telefone` exclusion so a matched person does not reappear as an external phone target. On native app only, unmatched local contacts may appear in a separate auxiliary external-share branch that prefers WhatsApp direct-share when available and otherwise falls back to the system share sheet; these entries are not part of the canonical inviteable list, not part of relation filters, not groupable, and must not appear on web. Future inbound discovery surfaces such as `Talvez você conheça`, derived from identity-materialization reconciliation, also stay outside the canonical inviteable list and outside relation filters until explicit favorite yields a normal inviteable reason. That late-reconciliation/reflection path is follow-up-owned by `TODO-vnext-onboarding-identity-reconciliation-reflection.md`, not by the current release invite-composer lane. Composer interaction is action-first: person rows expose immediate invite/share CTAs, while group rows expose immediate `Convidar grupo` / `Convidar todos` plus optional drill-in for member selection. A home-style horizontal group rail is not part of this screen baseline. Group CRUD is still required in V1, but it belongs to dedicated group-visualization or friends-management surfaces rather than this composer, and its exact UX may be refined through Stitch studies. When a grouped recipient ceases to be inviteable, V1 removes that recipient from group membership automatically instead of retaining a disabled row.

#### 2.1.1 Presentation DI Matrix (Canonical)

This section is the canonical Flutter presentation DI/ownership contract. Rules/skills/lint docs must reference this matrix instead of duplicating full prose.

| Context | Allowed | Forbidden |
|---|---|---|
| Screen (`presentation/**/screens/**`) | Resolve same-feature controller via `GetIt` and consume controller-owned state/keys/controllers. | Resolve repository/service/DAO/backend/DTO; resolve cross-feature controller; own UI controllers/keys locally. |
| Auxiliary widget (`presentation/**/widgets/**`) isolated | Local UI controllers/keys only when fully local and not bridged into feature controller APIs. | Non-controller DI and cross-feature controller DI. |
| Auxiliary widget interacting with feature controller | Use feature-controller-owned UI controllers/keys and trigger controller intents only. | Keep local UI controller/key and pass/bridge it into feature controller methods. |
| Auxiliary widget with private widget controller | Own/resolve a widget-scoped controller used only inside that widget subtree, and accept borrowed UI controllers without taking ownership. | Parent/sibling/screen/controller resolving that widget controller; using it as a relay for shared state; singleton/global registration that leaks the controller above the subtree. |
| Module class (`ModuleContract`) | `registerLazySingleton`, `registerFactory`, `registerRouteResolver`. | Direct `GetIt.I.register*`/`GetIt.instance.register*`. |
| Global bootstrap (`main.dart`, `ModuleSettings`, app bootstrap repository) | App-lifecycle non-UI services/contracts/gates/coordinators. | Global registrations using `*Controller` or `*ControllerContract` naming. |

Executable guardrails for this contract:
- Domain files cannot declare `fromJson`/`fromMap` factories; transport parsing belongs to DAO/DTO layers and infrastructure mappers.
- Domain fields must express validation/nullability through ValueObjects or domain-owned types instead of primitive transport fields.
- Repositories/services cannot parse raw JSON or hydrate DTOs inline; DAO is the transport ingestion boundary.
- Repositories cannot declare raw transport typing (`dynamic`, `Map<String, dynamic>`) in boundary signatures/helpers; DAO adapters own raw payload shapes.
- DTO -> Domain mapping is delegated to dedicated mapper files under `lib/infrastructure/dal/dto/mappers/**`.
- Files under `lib/**` should keep one public class per file; screen files still retain the stricter `multi_widget_file_warning` hygiene rule.
- Widget controllers are subtree-private. Parent screens, sibling widgets, and other controllers must not resolve, import, or coordinate descendant widget-controller state.
- Controllers must not depend on other controllers. Shared or persisted state flows through repository contracts only.
- Shared or persisted UI settings (for example radius preferences reused across surfaces) are repository-owned streams; controllers consume repository state directly and never relay another controller's state.
- Application-lifecycle identity hydration belongs to global bootstrap/coordinator code, not to screens or controllers. Repositories with registered user-linked state expose idempotent refresh methods and publish stream updates from those refreshes.
- Scroll-derived behavior must observe the same scroll source that moves the rendered content. Proxy or outer scroll owners do not count when a different inner list is the real moving surface.
- Borrowed UI controllers (`ScrollController`, `TextEditingController`, `FocusNode`, and similar types) remain owned by the caller; the callee must not dispose them or shadow them with competing controllers for the same behavior.

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
| `/api/v1/environment` | GET | Resolves tenant branding/context for app bootstrap, including the canonical persisted tenant name used by web/app install surfaces. | Anonymous | `EnvironmentRequest` | `EnvironmentResponse` |
| `/anonymous/identities` | POST | Mints or resumes the anonymous app identity used by progressive profiling before authenticated upgrade. | Anonymous | `AnonymousIdentityRequest` | `AnonymousIdentityResponse` |
| `/auth/otp/challenge` | POST | Starts a phone OTP challenge and dispatches delivery asynchronously through configured outbound webhook job integration. | Anonymous/app identity | `PhoneOtpChallengeRequest` | `PhoneOtpChallengeResponse` |
| `/auth/otp/verify` | POST | Verifies OTP, upgrades to registered phone identity, merges supplied anonymous identities, and returns the authenticated token/profile payload. | Anonymous/app identity | `PhoneOtpVerifyRequest` | `PhoneOtpVerifyResponse` |
| `/me` | GET | Delivers authenticated profile summary and role claims. | Tenant | `MeRequest` | `MeResponse` |
| `/invites` | GET | Retrieves pending invites and social proof metadata for the current user. | Tenant | `InviteFeedRequest` | `InviteFeedResponse` |
| `/invites/stream` | GET | Streams invite deltas for live updates. | Tenant | `InviteStreamRequest` | SSE delta events |
| `/invites/settings` | GET | Fetches invite limits and UX messaging settings. | Tenant | `InviteSettingsRequest` | `InviteSettingsResponse` |
| `/invites/share` | POST | Creates or returns a share code for an event invite. | Tenant | `InviteShareRequest` | `InviteShareResponse` |
| `/invites/share/{code}` | GET | Resolves invite preview payload for `/invite?code=...` before auth. | Tenant | n/a | `InviteSharePreviewResponse` |
| `/invites/share/{code}/accept` | POST | Canonical authenticated/promoted share acceptance endpoint for app invite conversion; anonymous attempts reject with `401 auth_required`. | Tenant | `InviteShareAcceptRequest` | `InviteShareAcceptResponse` |
| `/invites/share/{code}/materialize` | POST | Optional authenticated continuation/pre-bind for share-code flows. | Tenant | `InviteShareMaterializeRequest` | `InviteShareMaterializeResponse` |
| `/contacts/import` | POST | Imports hashed contacts for contact matching and inviteable acquisition. | Tenant | `ContactsImportRequest` | `ContactsImportResponse` |
| `/contacts/inviteables` | GET | Fetches the backend-computed unified in-app inviteable list for `/convites/compartilhar`, deduplicated by `receiver_account_profile_id` and carrying relation reasons/exposure; `contact_match` rows may include `contact_hash/contact_type` so native clients can keep matched people out of the external `Telefone` branch even when contact import is skipped by a fresh local signature. | Tenant | n/a | `InviteableContactsResponse` |
| `/contact-groups` | GET | Lists private contact groups available to invite-management surfaces, with stale non-inviteable members already pruned. | Tenant | n/a | `ContactGroupsResponse` |
| `/contact-groups` | POST | Creates a private contact group using inviteable `receiver_account_profile_id` members. | Tenant | `ContactGroupStoreRequest` | `ContactGroupResponse` |
| `/contact-groups/{group_id}` | PATCH | Renames a private contact group and/or replaces inviteable membership. | Tenant | `ContactGroupUpdateRequest` | `ContactGroupResponse` |
| `/contact-groups/{group_id}` | DELETE | Deletes a private contact group. | Tenant | n/a | Empty response |
| `/agenda` | GET | Provides schedule entries, suggested actions, and contextual CTAs (`live_now_only=true` powers Discovery "Tocando agora"). Discovery MVP rendering is artist-driven: show the section only when live-now entries include at least one artist. | Tenant | `AgendaRequest` | `AgendaResponse` |
| `/events/stream` | GET | Streams event deltas for active filters. | Tenant | `EventStreamRequest` | SSE delta events |
| `/events/{event_id}` | GET | Returns event detail; optional `occurrence` query selects one occurrence for multi-date detail hydration. | Tenant | `EventDetailRequest` | `EventDetailResponse` |
| `/events/{event_id}/check-in` | POST | Deferred participation endpoint that confirms/checks in a concrete occurrence; `event_id` is parent context only and the request must carry `occurrence_id`. | Tenant | `EventCheckInRequest` | `EventCheckInResponse` |
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
| `schedulable_id` | ObjectId | Reference to the scheduled source item (currently Event). | Yes | |
| `schedulable_type` | String | Type discriminator for the schedulable item. | Yes | Current runtime contract is `event`; additive future values require explicit authority promotion before they become canonical. |
| `start_time` | DateTime | Event start timestamp. | Yes | ISO-8601. |
| `end_time` | DateTime | Event end timestamp. | No | Optional for instantaneous items. |
| `status` | String | Participation state. | Yes | |
| `cta` | CtaDescriptorDocument | Action user can take next. | Yes | |
| `metadata` | Map | Arbitrary structured data (e.g., dress code, location). | No | Key-value pairs. |

**Field Definitions**

* `schedulable_type`: Current runtime value is `event`. Additional future schedulable kinds must not be treated as canonical until a later module decision promotes them explicitly.
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
* `visual.image_uri`: required when `visual.mode=image`; `visual.color` must be consumed when present for marker accents and fallback UI.

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
* **Release Funnel Telemetry:** Store-release client telemetry must preserve funnel attribution properties through the shared tracker/repository path. Web promotion events use `store_channel=web`; Android deferred-capture events must always include `store_channel`, using `unknown` when the native/resolver path cannot provide a concrete store channel, and must include backend-resolved `target_path` so invite, detail, and guarded-route handoffs can be distinguished. `code` is emitted only when the captured target is invite attribution.
* **Sentry Failure Reporting Contract:** Flutter may keep a recovered user-facing flow quiet, but unexpected caught failures must not disappear locally. Touched catch paths are classified as `expected_control_flow`, `recoverable_reported`, or `fatal_reported`; `recoverable_reported` and `fatal_reported` paths must call the project-owned Sentry reporter or `Sentry.captureException` before recovery/propagation. `debugPrint` inside a catch block without capture or rethrow is forbidden by `flutter_sentry_unreported_debug_print_catch_forbidden`; intentional `expected_control_flow` bypasses must be explicit in code/review evidence when touched.

#### 2.7 Testing Strategy

The Flutter client must separate regression confidence from real compatibility evidence. A green fake/UI-flow suite is never enough to claim Flutter↔Laravel safety for invite-critical paths.

**Canonical taxonomy**

| Test Class | Scope | Counts as Real Compatibility? | Required Environment | Notes |
| --- | --- | --- | --- | --- |
| Unit / widget / controller tests | Value-object validation, controller state transitions, route-local UI behavior | No | local-safe + CI | Mandatory for fast regression feedback. |
| UI-flow `integration_test` with fakes | AutoRoute/DI/StreamValue behavior under controlled doubles | No | local-safe + CI | These prove state-machine integrity only; they must never be reported as backend compatibility evidence. |
| Repository / decoder contract tests | Flutter transport boundary (`preview`, `materialize`, `accept`, `decline`, malformed payloads, terminal states) | No | local-safe + CI | Required whenever invite payload shape changes. |
| Real Flutter runtime compatibility suite | Real backend, real repositories, real controller/runtime behavior for invite critical paths | Yes | `stage` only | Must run against the deployed `stage` backend, not mocks or local doubles. |
| Web/browser compatibility suite | Invite landing, preview, auth redirect preservation, fallback behavior, `.well-known`/deeplink artifacts | Yes for browser boundary | `readonly` can run on `local|dev|stage|main`; `mutation` can run on `local|dev|stage` and is forbidden on `main` | Executed through `tools/flutter/run_web_navigation_smoke.sh` and must target the browser-facing domain for the current lane. |
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
| `dev` | Laravel local-safe invite tests + Flutter unit/widget/repository tests + optional Playwright `readonly|mutation` against the browser-facing dev domain | Contract-safe locally/dev, with optional real browser evidence outside `main` |
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
| `FCX-08` | Approved | Widget controllers are subtree-private. Screens, parents, siblings, and other controllers must not resolve descendant widget controllers, and controller-to-controller relay is forbidden. | Preserves controller ownership boundaries and blocks "one screen, many leaked controllers" architecture drift. | Section `2.1.1` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` |
| `FCX-09` | Approved | Shared or persisted UI settings must be repository-owned streams; controllers may consume them directly but must never proxy another controller's state. | Prevents controller relay chains and keeps shared settings aligned with persistence/repository ownership. | Section `2.1.1` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` |
| `FCX-10` | Approved | Scroll-reactive UI behavior must bind to the same scroll source that moves the real content, and borrowed UI controllers remain owned by the caller. | Avoids false scroll triggers, duplicate controller ownership, and dispose/shadowing bugs across nested scroll surfaces. | Section `2.1.1` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` + `foundation_documentation/todos/completed/TODO-v1-home-agenda-controller-boundary-plugin-rules.md` |
| `FCX-11` | Approved | Tenant-public event detail preserves occurrence-first list/card semantics and uses optional selected-occurrence query metadata for multi-date detail. `Datas` appears after `Sobre`, highlights the active occurrence card, and `Programação` appears only for the selected occurrence when programming items exist. | Prevents ambiguous same-slug multi-date navigation while avoiding a separate event-only detail screen for Store Release. | Sections `2.1`, `2.2`, `3` |
| `FCX-12` | Approved | Registered identity transition triggers app-shell post-auth hydration for user-linked repository state: Home favorite resumes, Account Profile favorite ids, confirmed occurrence ids, and pending invites. New authenticated user-linked module state must register an idempotent repository refresh consumer before release. | Prevents stale anonymous/local state after OTP login and blocks screen-local or controller-relay workarounds for identity-owned data. | Sections `2.1`, `2.1.1` |
| `FCX-13` | Approved | Share-code preview may seed session-only invite context keyed by `share_code + occurrence_id`; event detail can consume that projection to show pending invite context before explicit acceptance, while authenticated acceptance still uses `POST /invites/share/{code}/accept`. | Preserves invite continuation through preview -> details without introducing persistent local state, controller relays, or pre-accept invite-edge creation. | Sections `2.1`, `2.2`; `invite_and_social_loop_module.md` `INV-35` |
| `FCX-14` | Approved | Tenant-public auth/promotion action boundaries must be centralized after Store Release in an executable policy: the boundary is web anonymous, QR-authenticated web follows the normal authenticated posture, and screens must stop owning local `kIsWeb` promotion/auth branching for trust actions. | Converts the already-approved Web-to-App Promotion Policy into code-level guardrails without delaying Store Release. | Sections `2.1`, `2.1.1`; `web_to_app_promotion_policy.md`; `TODO-post-release-tenant-public-boundary-policy-centralization.md` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-events-and-agenda-frontend.md` | Events/agenda client contracts and UX integration | Completed | `2.2`, `2.3`, `3` | Maintains occurrence-first event consumption. |
| `TODO-v1-invites-implementation.md` | Invite/social flow delivery in client | Completed (2026-03-12) | `2.2`, `2.4`, `2.5` | Share acceptance + contacts import paths. |
| `TODO-v1-map-frontend.md` | Map rendering/filter/stacking contracts | Completed | `2.2`, `2.3`, `2.4` | Contract-alignment slice closed; the promoted visual/plugin-surface lane is now archived in `foundation_documentation/todos/completed/TODO-v1-map-visuals.md`. |
| `TODO-v1-map-icon-color-config.md` | Type-driven POI visual rendering and filter marker override consumption | Completed | `2.2`, `2.3`, `3` | Archived in `todos/completed`; runtime consumes `map_pois.visual` + override precedence with single fallback path. |
| `TODO-v1-tenant-public-safe-back-navigation.md` | Centralized tenant-public safe-back policy and direct-entry fallback matrix | Completed | `2.1`, `3`, `7` | Shared back behavior for event detail, public account-profile detail, agenda, and map; archived from `active` during the 2026-04-09 MVP TODO cleanup after delivery confirmation. |
| `TODO-v1-canonical-back-navigation-governance-cutover.md` | Canonical route-back governance across tenant-public, tenant-admin, landlord root, workspace, and identity-boundary entry routes | In progress | `2.1`, `7` | Structural rule promoted now: governed route families are meta-declared, screens/shells consume canonical helpers, and semantic linting remains a later phase. |
| `TODO-v1-home-agenda-canonical-stream-ownership-hardening.md` | Home/Agenda stream ownership and controller-boundary hardening | Completed | `2.1.1`, `7` | Widget-controller privacy, repository-owned shared settings, single-writer stream ownership, and single-scroll-truth governance are now promoted and reflected in the current repo. |
| `TODO-store-release-funnel-metrics-validation.md` | Store-release funnel metrics validation | In progress | `2.6` | Promotes client-side telemetry attribution rules for web promotion and Android deferred-capture events. |
| `TODO-store-release-phone-otp-auth-and-contact-match.md` | Phone-OTP auth upgrade and identity handoff | In progress | `2.1`, `7` | Promotes the post-auth hydration handoff after registered identity emission. |
| `TODO-store-release-home-favorites-refresh-regression.md` | Home/account-profile favorite refresh regression | Promotion Lane | `2.1`, `7` | User-linked favorite state participates in post-auth hydration and clears stale empty remote results. |
| `TODO-vnext-tenant-user-account-profile-area.md` | Workspace scope and route ownership | In progress | `2.0`, `3` | Account workspace/subscope integrity. |
| `TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` | Tenant-public multi-occurrence event detail and route hydration | Promotion Lane | `2.1`, `2.2`, `7` | Promotes selected-occurrence query hydration, `Datas` active occurrence highlight, and conditional `Programação` rendering. |
| `TODO-store-release-invites-occurrence-target-migration.md` | Occurrence-scoped invite cutover plus share-code session-context addendum | Reopened addendum | `2.1`, `2.2`, `7` | Promotes selected-occurrence invite identity and the Store Release app-session pending invite projection. |
| `TODO-post-release-tenant-public-boundary-policy-centralization.md` | Tenant-public auth/promotion boundary centralization | Active post-release hardening | `2.1`, `2.1.1`, `7` | Follow-up hardening to replace screen-local action gates with one executable boundary policy and static guard. |
