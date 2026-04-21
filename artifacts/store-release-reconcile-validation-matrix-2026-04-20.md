# Store Release Reconcile Validation Matrix — 2026-04-20

## Purpose

Freeze the validation-analysis output for the current store-release orchestration run so Delphi does not need to rediscover which browser, backend, Flutter, and optional device journeys matter before every reconcile cycle.

This matrix is subordinate to the active TODO set and the orchestration-test-flow canonicalization TODO. It is not a new source of truth for product behavior; it records the required evidence mapping for this execution slice.

## Principal-Checkout Authority

- The authoritative integrated state lives in the principal local checkout on `reconcile/*`.
- Worker worktrees remain implementation-only lanes.
- Browser validation must target the browser-facing domain that resolves to that principal checkout.
- Local/browser mutation is allowed on `local|dev|stage` and is blocked on `main`.
- Laravel runtime normalization must include `storage/app/public` in addition to cache/session directories because media upload/render flows depend on it.

## Browser-Facing Targets

| Surface | Canonical target | Why it matters |
| --- | --- | --- |
| Landlord/browser host | `NAV_LANDLORD_URL` | Validates landlord bootstrap and admin-route guards. |
| Tenant/browser host | `NAV_TENANT_URL` | Validates tenant public bootstrap, tenant-admin routes, environment payloads, and browser mutation flows. |
| Local tunnel profile | `make up-dev-tunnel` when browser evidence is required | Ensures Playwright sees the same integrated reconcile state a human validator sees. |

## TODO Matrix

| TODO | Mandatory journeys | Minimum evidence lanes | Current evidence posture |
| --- | --- | --- | --- |
| `TODO-store-release-belluga-media-canonical-image-flow-hardening.md` | tenant-admin account-profile cover upload, save, persisted `cover_url`, cold reload render | Laravel media feature tests + real browser mutation on tenant admin | Real browser bug was reproduced locally as a runtime-permission failure; canonical runner must now normalize `storage/app/public`. |
| `TODO-store-release-proximity-preferences-and-location-origin.md` | Home status row + dialog, Home-only agenda origin/radius semantics, Profile editor save flow, anonymous-to-auth merge, map permission detour continuity | Laravel feature tests + Flutter repository/controller/widget/integration coverage + browser/public agenda parity; device lane when available | Strong backend/unit coverage exists; highest uncovered behavior is the real browser/device edit-and-reload journey for the Profile origin editor. |
| `TODO-store-release-tenant-settings-optimization.md` | `/api/v1/environment` parity, tenant-admin bootstrap stability, fallback/snapshot behavior preserved | Laravel environment tests + Flutter settings tests + browser readonly bootstrap on landlord/tenant domains | Existing browser readonly suite is relevant because it exercises runtime bootstrap and host resolution. |
| `TODO-store-release-critical-journey-regression-gates.md` | `CJ-01` Home agenda parity, `CJ-02` public agenda no-search contract, `CJ-03` tenant-admin event type/event form critical path, `OBS-01` bounded Sentry hardening | Laravel feature tests + Flutter tests + browser readonly/mutation for touched journeys | `CJ-01` already has browser mutation parity coverage; `CJ-03` still needs real browser-auth admin evidence if we want end-to-end proof beyond repository/widget tests. |

## Suite Selection Rules For This TODO Set

1. Always run browser readonly bootstrap on both landlord and tenant domains because tenant settings optimization and route guards are part of the active slice.
2. Always run the existing tenant public agenda mutation suite because it covers the Home agenda parity contract touched by proximity and critical-journey gates.
3. Run a tenant-admin media mutation flow because Belluga Media delivery is not credibly closed without a real upload/save/render cycle.
4. Treat the tenant-admin event type/event form browser path as the next browser gap after media. If time or infrastructure limits block it, record that gap explicitly rather than claiming full browser confidence.
5. Keep Flutter/Laravel targeted suites aligned with the touched TODO contracts; do not replace them with browser evidence.
6. If an ADB/emulator lane is unavailable, record device validation as blocked rather than silently downgrading it to browser-only confidence.

## Current Highest-Risk Gaps

- No real browser-admin mutation proof yet for the tenant-admin event type/event form critical journey.
- No real device/browser edit-and-reload proof yet for the Profile-origin editor path under the proximity TODO.
- Device validation is currently contingent on an attached emulator/device.

## Operational Reminder

Before final delivery claims:

- regenerate this matrix when the touched TODO set changes;
- run the selected suites from the principal reconcile checkout;
- keep the tunnel/domain target explicit in the evidence log;
- record any blocked browser/device lanes as blocked, never as implicitly passed.
