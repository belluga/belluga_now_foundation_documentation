# Documentation: Web-to-App Promotion Policy

**Version:** 1.5  
**Date:** March 30, 2026  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

This policy defines the V1 acquisition and conversion boundary across Web and App surfaces. The objective is to minimize entry friction, preserve viral attribution, and keep trust actions inside the native app with explicit auth boundaries.

## 2. Canonical V1 Surface Model

We operate with two practical conversion tiers in V1:

1. **Web (Public/Tenant Public):** read-only showcase and promotion only.
2. **App:** conversion, anonymous-first invite decision, and all trust actions.

`Web (Authenticated)` workspace capabilities are deferred to VNext and are out of V1 conversion scope.

### 2.1 Deep-Link Package Ownership (V1)

Backend deep-link governance is package-owned end-to-end:

- `belluga_deep_links` is the canonical owner of:
  - App Links / Universal Links association payload generation (`/.well-known/assetlinks.json`, `/.well-known/apple-app-site-association`);
  - `settings.app_links` schema registration + patch guard validation rules;
  - web promotion/open-app handoff resolution;
  - deferred deep-link first-open resolver contract (Android V1, iOS-ready contract surface).
- Host app (`laravel-app`) must remain integration-thin:
  - route wiring and middleware composition;
  - adapter bindings for tenant/domain/settings context required by the package contracts.

## 3. V1 Rules by Surface

### 3.1 Web (Read-Only Promotion)

- Allowed:
  - event/invite landing preview;
  - map/feed style discovery in read-only mode;
  - app promotion CTA (`Baixe o App para Confirmar`) and install/open handoff preserving `code` via backend-resolved dynamic tenant targets for Android and iOS.
- Not allowed:
  - invite accept/decline/materialize mutations from web;
  - minting anonymous identity from web for invite conversion (`POST /api/v1/anonymous/identities`);
  - trust-action execution on web (`favorite`, `send_invite`, attendance/check-in-related mutations);
  - using web login/auth continuation as tenant-public hard-gate fallback.

Anonymous web direct-URL allowlist is explicit in V1:
- public: `/`, `/privacy-policy`, `/descobrir`, `/parceiro/:slug`, `/agenda/evento/:slug`, `/mapa`, `/mapa/poi`, `/invite?code=...`, `/convites?code=...`, `/location/permission`, `/baixe-o-app`;
- canonical fallback to `/`: blocked non-identity routes such as `/agenda`, invalid invite URLs without `code`, and legacy `/menu`;
- promotion boundary: identity/auth-owned routes such as `/profile`, `/workspace*`, `/convites/compartilhar`, and `/auth/*`.

**Hard-gate rule:** if a trust/auth gate is hit on web tenant-public surfaces, the result must be a canonical app-promotion route/screen that then hands off through `/open-app`; web-login continuation is never allowed in V1.
Handoff target rule:
- only invite-landing context (`/invite` or `/convites`) with valid `code` -> `/invite?code=...`;
- missing/invalid `code` on either invite route, or any non-invite context -> canonical tenant home (`/`).
- route-gated and action-gated identity boundaries on web must use the same promotion surface; modal-only divergence is not allowed.

Identity-dependent convenience affordances on tenant-public Home must also follow the unauthenticated web posture:
- hide the direct `Account Workspace` entry from Home while web auth is unavailable;
- hide the Agenda invite/confirmed filter while the web session is unauthenticated.
- identity-owned routes such as `/profile` must not continue to web auth in V1; unauthenticated web access must resolve to app-promotion UX/handoff instead.
- `/location/not-live` is legacy and must not survive as a second public location surface; `/location/permission` is the single canonical public location gate in V1.

These affordances may reappear only once authenticated web exists in VNext; their return does not change the V1 rule that tenant-public trust/hard gates promote the app instead of continuing through web login.

### 3.2 App (Progressive Profiling Conversion Surface)

- Invite flows are anonymous-first:
  - app may mint/resume anonymous identity (`POST /api/v1/anonymous/identities`);
  - invite acceptance from share preview uses `POST /api/v1/invites/share/{code}/accept`;
  - inviter attribution remains bound to the share `code` principal.
- Deferred deep linking is mandatory:
  - **V1 MVP scope:** Android install path (Play Store) must preserve invite `code` and capture it on first open.
  - **iOS deferred capture** is explicitly deferred to VNext; MVP iOS keeps installed-app universal link behavior plus deterministic fallback UX when deferred capture is unavailable.
- First-open routing must be deterministic:
  - when `code` is captured/resolved, route directly to invite flow;
  - when deferred capture fails or resolves no invite, route to canonical tenant home (`/`), never blank/intermediate dead-ends.
- Post-accept behavior:
  - anonymous users can continue feed/map browsing in read-only posture;
  - trust actions trigger Auth Wall.
- Identity-owned app routes (for example `/profile`) remain auth-gated in app runtime; anonymous app access must continue to native auth/login, not app-promotion fallback.

### 3.3 Auth Wall Scope (V1)

Auth Wall is mandatory for:
- `favorite` actions;
- `send_invite` actions;
- attendance/check-in boundaries (check-in feature delivery itself remains VNext).

## 4. Attribution and Endpoint Contract Baseline

- Share links carry one canonical `code` parameter.
- Web must preserve `code` through install/open-app handoff.
- Canonical web handoff endpoint is `GET /open-app` (backend resolves tenant-dynamic store target + attribution payload).
- Canonical web promotion boundary route is a Flutter screen that uses runtime tenant/app branding (`Environment.name`, `main_icon_*`) and then calls `/open-app` with an explicit `platform_target=android|ios` override when the user chooses a store.
- The promotion surface may render a single store CTA when the web browser platform can be inferred as Android or iOS; when platform inference is unavailable or ambiguous, it must render both store badges.
- Handoff URI resolution is deterministic: preserve invite context only when the current route is invite-landing (`/invite` or `/convites`) and `code` exists; otherwise use canonical tenant home (`/`).
- Store/open handoff targets must be resolved dynamically per tenant (Android + iOS) by backend contract; web/app clients must not hardcode store URLs.
- When the promotion surface renders multiple store badges, App Store badge ordering and artwork must follow Apple’s published App Store Marketing Guidelines, including use of Apple-provided badge artwork and first position in a multi-badge lineup.
- Canonical deferred first-open resolver endpoint is `POST /api/v1/deep-links/deferred/resolve` (Android MVP capture contract; iOS returns deterministic `not_captured` in V1).
- Canonical app acceptance path for share preview is `POST /api/v1/invites/share/{code}/accept`.
- `POST /api/v1/invites/share/{code}/materialize` remains authenticated-only and optional for explicit continuation flows.
- Canonical direct invite mutations (`POST /api/v1/invites/{invite_id}/accept|decline`) remain valid for materialized/inbox flows.

## 5. Tracking and KPI Baseline

The V1 funnel is:

`landing -> app install -> deferred deep link captured -> anonymous accept -> auth wall triggered -> signup completed`

Required key events include:
- `web_invite_landing_opened` (`store_channel=web`, `has_code=true|false`)
- `web_open_app_clicked` (`store_channel`, `platform_target=android|ios`)
- `web_install_clicked` (`store_channel`, `platform_target=android|ios`)
- `app_anonymous_invite_accepted`
- `app_auth_wall_triggered` (`action_type=favorite|send_invite` in V1)
- `app_signup_completed`
- `app_deferred_deep_link_captured` / `app_deferred_deep_link_capture_failed` with `platform` + `store_channel` properties (`platform=android` in V1 MVP; `ios` when VNext deferred capture is enabled).

Store-channel and deferred failure modes must remain explicit in telemetry:
- `store_channel=web` for web-to-app CTA origin.
- `app_deferred_deep_link_capture_failed.failure_reason` must differentiate at least: `referrer_unavailable`, `resolver_not_captured`, `resolver_unsupported_platform`, `resolver_error`.

## 6. Module Impact Summary

| Module | Impact |
|--------|--------|
| Invite & Social Loop | Web stays preview/promotion only; app owns anonymous-first acceptance and invite mutations. |
| Flutter Client Experience | Must implement Android V1 deferred deep-link capture + app-side auth wall interception while keeping web hard gates as app handoff. |
| Task & Reminder | Push/deep-link execution remains app-owned; web only routes users into app. |
| Account Workspace (VNext) | Web authenticated workspace does not alter V1 tenant-public conversion boundaries. |

## 7. Policy Maintenance

Any proposal to re-enable web mutation flows must update this policy, `system_roadmap.md`, and affected module contracts (`invite_and_social_loop_module.md`, `flutter_client_experience_module.md`) in the same change set.

---

*Next Review:* after V1 funnel telemetry stabilization and VNext workspace planning.
