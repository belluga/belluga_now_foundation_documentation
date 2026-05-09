# Documentation: Web-to-App Promotion Policy

**Version:** 1.9
**Date:** May 1, 2026
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

This policy defines the V1 acquisition and conversion boundary across Web and App surfaces. The objective is to minimize entry friction, preserve viral attribution, keep anonymous web strictly promotional before QR-authenticated login, and make authenticated identity entry explicit: phone OTP in the app and QR-only login on the web.

## 2. Canonical V1 Surface Model

We operate with three practical runtime postures in V1:

1. **Web (Anonymous / Tenant Public):** read-only showcase and promotion only.
2. **App:** anonymous-first invite preview and explicit anonymous usage baseline (`invite preview`, `feed`, `map`, `favorites`), with trust mutations promoted through phone OTP before execution.
3. **Web (Authenticated):** the normal authenticated web product posture is allowed, but session bootstrap is QR-only from an already promoted app identity. Web-native email/password/social login is forbidden.

Authenticated web does not weaken the anonymous web boundary: before QR login, web remains promotion/read-only.

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
  - app promotion CTA (`Baixe o App para Confirmar`) and install/open handoff preserving invite attribution plus the originally requested route intent via backend-resolved dynamic tenant targets for Android and iOS.
- Not allowed:
  - invite accept/decline/materialize mutations from anonymous web;
  - minting anonymous identity from web for invite conversion (`POST /api/v1/anonymous/identities`);
  - trust-action or authenticated mutation execution on anonymous web;
  - using web login/auth continuation as anonymous tenant-public hard-gate fallback.

Anonymous web direct-URL allowlist is explicit in V1:
- public: `/`, `/privacy-policy`, `/descobrir`, `/parceiro/:slug`, `/agenda/evento/:slug`, `/mapa`, `/mapa/poi`, `/invite?code=...`, `/convites?code=...`, `/location/permission`, `/baixe-o-app`;
- canonical fallback to `/`: blocked non-identity routes such as `/agenda`, invite URLs without `code`, malformed/external invite URLs, and legacy `/menu`;
- promotion boundary: identity/auth-owned routes such as `/profile`, `/workspace*`, `/convites/compartilhar`, and `/auth/*`.

**Hard-gate rule:** if an unauthenticated user hits a trust/auth gate on web tenant-public surfaces, the result must be a canonical app-promotion route/screen that then hands off through `/open-app`; anonymous web-login continuation is never allowed in V1.
Handoff target rule:
- preserve the originally requested route intent across promotion and deferred first-open resolution whenever that intent is valid for app continuation;
- preserve invite attribution when the current context is invite landing (`/invite` or `/convites`) with valid `code`;
- direct event/detail routes and guard-triggered redirects must preserve the requested redirect path instead of collapsing by default to `/`;
- canonical tenant home (`/`) remains the fallback only when no valid continuation intent can be resolved.
- route-gated and action-gated identity boundaries on web must use the same promotion surface; modal-only divergence is not allowed.
- the canonical promotion surface is the app-promotion/store handoff experience; the pre-MVP tester waitlist form is not the target release behavior.

Identity-dependent convenience affordances on tenant-public Home must also follow the unauthenticated web posture:
- hide the direct `Account Workspace` entry from Home while web auth is unavailable;
- hide the Agenda invite/confirmed filter while the web session is unauthenticated.
- identity-owned routes such as `/profile` must not continue to web auth in V1; unauthenticated web access must resolve to app-promotion UX/handoff instead.
- `/location/not-live` is legacy and must not survive as a second public location surface; `/location/permission` is the single canonical public location gate in V1.

These affordances may reappear once QR-authenticated web is available, but that does not change the anonymous-web rule that tenant-public trust/hard gates promote the app instead of continuing through web login.

### 3.2 App (Progressive Profiling Conversion Surface)

- Invite preview flows are anonymous-first:
  - app may mint/resume anonymous identity (`POST /api/v1/anonymous/identities`);
  - invite preview/session context may resolve from share `code` before login;
  - explicit invite accept/decline is a trust mutation and requires a registered/authenticated identity before execution;
  - inviter attribution remains bound to the share `code` principal after authenticated acceptance.
- Authenticated app upgrade is phone-OTP only; email/password, social login, and web-shared auth forms are out of scope for tenant-public V1.
- Anonymous app baseline in V1 includes:
  - invite preview from the share flow;
  - feed browsing;
  - map browsing;
  - favorites.
- Deferred deep linking is mandatory:
  - **V1 MVP scope:** Android install path (Play Store) must preserve invite `code` and capture it on first open.
  - **iOS deferred capture** is explicitly deferred to VNext; MVP iOS keeps installed-app universal link behavior plus deterministic fallback UX when deferred capture is unavailable.
- First-open routing must be deterministic:
  - when deferred payload resolves invite `code`, route directly to invite flow;
  - when deferred payload resolves a guarded-route redirect, restore that route intent in the app and continue through native auth only when the target itself requires authenticated identity;
  - when deferred capture fails or resolves no invite, route to canonical tenant home (`/`), never blank/intermediate dead-ends.
- Post-accept behavior:
  - authenticated users continue through the normal app flow after acceptance;
  - only the explicitly restricted actions continue to Auth Wall.
- Identity-owned app routes (for example `/profile`) remain auth-gated in app runtime; anonymous app access must continue to native auth/login, not app-promotion fallback.

### 3.3 Auth Wall Scope (V1)

Authenticated identity entry remains phone-OTP only in the app and QR-only on the web.

Auth Wall is mandatory in the app for:
- invite accept/decline trust mutations while the user is still anonymous;
- `send_invite` actions;
- identity-owned routes such as `/profile`;
- attendance/check-in boundaries (check-in feature delivery itself remains VNext);
- any additional capability explicitly frozen as authenticated-only by the tenant-public auth baseline.

Favorites are not a blanket Auth Wall action in this policy. Anonymous app usage may include favorites when the active auth baseline allows it.

On the web:
- anonymous users always promote to app instead of continuing through web login;
- authenticated web users are allowed to execute the normal authenticated web posture for the surface;
- authenticated mutations require an authenticated web session established only through QR login.

## 4. Attribution and Endpoint Contract Baseline

- Share links carry one canonical `code` parameter.
- Web must preserve `code` and the originally requested redirect route through install/open-app handoff whenever a guarded route or deep-linked route triggered promotion.
- Canonical web handoff endpoint is `GET /open-app` (backend resolves tenant-dynamic store target + attribution payload).
- Canonical web promotion boundary route is a Flutter screen that uses runtime tenant/app branding (`Environment.name`, `main_icon_*`) and then calls `/open-app` with an explicit `platform_target=android|ios` override when the user chooses a store.
- The promotion surface may render a single store CTA when the web browser platform can be inferred as Android or iOS; when platform inference is unavailable or ambiguous, it must render both store badges.
- Handoff URI resolution is deterministic: preserve invite context when the current route is invite-landing (`/invite` or `/convites`) and `code` exists; preserve redirect-path intent when promotion started from a guarded or directly requested route; use canonical tenant home (`/`) only when no valid continuation intent is available.
- Store/open handoff targets must be resolved dynamically per tenant (Android + iOS) by backend contract; web/app clients must not hardcode store URLs.
- When the promotion surface renders multiple store badges, App Store badge ordering and artwork must follow Apple’s published App Store Marketing Guidelines, including use of Apple-provided badge artwork and first position in a multi-badge lineup.
- Canonical deferred first-open resolver endpoint is `POST /api/v1/deep-links/deferred/resolve` (Android MVP capture contract; iOS returns deterministic `not_captured` in V1).
- Canonical app acceptance path for share preview is `POST /api/v1/invites/share/{code}/accept` after authenticated identity is available; anonymous attempts must reject deterministically with `401 auth_required`.
- `POST /api/v1/invites/share/{code}/materialize` remains authenticated-only and optional for explicit continuation/pre-bind flows.
- Canonical direct invite mutations (`POST /api/v1/invites/{invite_id}/accept|decline`) remain valid for materialized/inbox flows.

## 5. Tracking and KPI Baseline

The V1 funnel is:

`landing -> app install -> deferred deep link captured -> auth upgrade -> invite accepted -> post-auth hydration`

Required key events include:
- `web_invite_landing_opened` (`store_channel=web`, `has_code=true|false`)
- `web_open_app_clicked` (`store_channel`, `platform_target=android|ios`)
- `web_install_clicked` (`store_channel`, `platform_target=android|ios`)
- `app_invite_acceptance_requested`
- `app_invite_accepted` (or backend-canonical `invite.accepted` with equivalent join keys when terminal acceptance is emitted server-side)
- `app_auth_wall_triggered` (`action_type` = the restricted action that required authenticated identity)
- `app_signup_completed`
- `app_deferred_deep_link_captured` with `platform`, `store_channel`, and backend-resolved `target_path`; include `code` only when the captured target is invite attribution.
- `app_deferred_deep_link_capture_failed` with `platform` + `store_channel` properties (`platform=android` in V1 MVP; `ios` when VNext deferred capture is enabled).

Store-channel and deferred failure modes must remain explicit in telemetry:
- `store_channel=web` for web-to-app CTA origin.
- `app_deferred_deep_link_capture_failed.failure_reason` must differentiate at least: `referrer_unavailable`, `resolver_not_captured`, `resolver_unsupported_platform`, `resolver_error`.

## 6. Module Impact Summary

| Module | Impact |
|--------|--------|
| Invite & Social Loop | Anonymous web stays preview/promotion only; app owns preview-first continuation plus authenticated invite mutations; authenticated web follows QR-only session bootstrap. |
| Flutter Client Experience | Must implement Android V1 deferred deep-link capture + route-intent preservation, phone-OTP app auth, and QR-only web auth while keeping anonymous web hard gates as app handoff. |
| Task & Reminder | Push/deep-link execution remains app-owned; web only routes users into app. |
| Account Workspace (VNext) | Workspace rollout remains separate and does not redefine the anonymous-web conversion boundary. |

## 7. Policy Maintenance

Any proposal to re-enable web mutation flows must update this policy, `system_roadmap.md`, and affected module contracts (`invite_and_social_loop_module.md`, `flutter_client_experience_module.md`) in the same change set.

---

*Next Review:* after V1 funnel telemetry stabilization and QR-authenticated web rollout planning.
