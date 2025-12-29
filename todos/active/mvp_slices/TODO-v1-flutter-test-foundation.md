# TODO (V1): Flutter Test Foundation (Baseline + Contract Fidelity)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter) + Backend Team (contract source)  
**Objective:** Establish a reliable Flutter test suite (unit/contract/widget/integration + network contract checks) that locks current behavior and prevents regressions before implementing V1 feature slices.

---

## Why This Exists
- Laravel has regression protection via tests; Flutter currently does not.
- We want “tests first” so feature implementation doesn’t backfill tests with workarounds/false positives.
- Our mocks must mimic the backend contract so swapping mock → real implementation doesn’t require rewriting tests.

---

## Scope

### In scope
- Test harness + conventions for `test/` structure and GetIt reset patterns.
- Unit tests for core domain/application behaviors already present.
- Contract tests against existing DTOs + fixtures (deserialization + invariants).
- Widget tests for routing/guards and critical UI gating logic.
- Minimal integration tests for smoke flows.
- Improve integration-test diagnostics for real `/environment` failures (include HTTP status/URL/body in errors).
- Network contract tests against Cloudflared domain(s):
  - Validate `/environment` resolution (root domain vs tenant subdomain).
  - Validate fixed branding asset endpoints never return `404`.

### Out of scope (for this TODO)
- Full golden testing suite.
- Coverage targets as a KPI (prioritize meaningful scenarios).
- Full E2E automation on physical devices (manual device runs remain part of milestone gates).

---

## Architectural Test Rules (Non-Negotiable)
- Prefer unit tests for domain/application logic.
- Widget tests must avoid real network; use deterministic HTTP overrides.
- Network contract tests are separate and explicitly allowed to hit real HTTP endpoints (Cloudflared).
- No tests may rely on `main_logo_*` or `main_icon_*` fields; branding is **fixed paths** only.

---

## Network Contract: Domains + Resolution Rules

### Canonical domains
- **Landlord root:** `https://{NETWORK_TEST_ROOT_DOMAIN}` (example: `https://belluga.app`)
- **Tenant subdomain:** `https://{NETWORK_TEST_TENANT_SUBDOMAIN}.{NETWORK_TEST_ROOT_DOMAIN}` (example: `https://guarappari.belluga.app`)

### Environment contract (web mode)
- Landlord: `GET https://{root}/environment` returns JSON with `type=landlord`
- Tenant: `GET https://{tenant}.{root}/environment` returns JSON with `type=tenant`

### Branding contract (fixed paths; must never 404)
For both landlord and tenant origins, these endpoints must return `200` or `304` (never `404`):
- `/manifest.json`
- `/favicon.ico`
- `/icon/icon-maskable-512x512.png`
- `/icon/icon-192x192.png`
- `/icon/icon-512x512.png`
- `/icon-light.png`
- `/icon-dark.png`
- `/logo-light.png`
- `/logo-dark.png`

---

## Milestones

### M0 — Test scaffolding + deterministic DI
**Deliverables**
- [x] ✅ Test utilities:
  - [x] ✅ reset GetIt registrations cleanly between tests (`TestHarness`)
  - [x] ✅ provide a test `AppRouter` harness pump (unit tested via Guard)
- [x] ✅ Decide mocking style:
  - [x] ✅ manual fakes preferred; add a mocking library only if needed (`FakeAppData`)

**Definition of Done**
- [x] ✅ `fvm flutter test` runs locally and is stable.
- [x] ✅ At least 1 unit test + 1 widget test green using the harness.

### M1 — Unit tests for environment-driven selection (tenant vs landlord)
**Coverage**
- [x] ✅ `Tenant.hasWebDomain(...)` and `Tenant.hasAppDomain(...)` behavior.
- [x] ✅ `TenantRepositoryContract.isLandlordRequest` and `isProperTenantRegistered` behavior.
- [x] ✅ `TenantRouteGuard` routing outcome in widget test:
  - tenant missing/invalid → redirects to landlord
  - tenant valid → allows tenant route

**Definition of Done**
- [x] ✅ Deterministic tests that don’t depend on platform plugins (`package_info_plus`, `platform_device_id_plus`, etc.).

### M2 — Widget resilience: image failure must not crash
**Coverage**
- [x] ✅ A small helper to force image loading failure in widget tests (`mockNetworkImages`).
- [x] ✅ At least one representative screen/widget that uses `Image.network` asserts:
  - [x] ✅ no crash
  - [x] ✅ fallback renders (placeholder widget or `errorBuilder` path)

**Definition of Done**
- [x] ✅ Widget tests do not perform real network calls.

### M3 — Contract tests for current feature DTOs (deserialize + invariants)
**Coverage targets (current app surfaces)**
- [x] ✅ Schedule/agenda DTO mapping invariants (`EventDTO`).
- [x] ✅ Invites DTO mapping invariants (`InviteDto`).
- [x] ✅ Map POI DTO mapping invariants (`CityPoiDTO`).
- [x] ✅ Profile DTO mapping invariants (as currently used - `TenantDto`).

**Definition of Done**
- [x] ✅ `fvm flutter test` includes DTO contract validation.
- [x] ✅ Tests fail fast on missing required fields or incompatible types (prevents “silent drift”).

### M4 — Network contract tests (Cloudflared)
**Inputs**
- `--dart-define=NETWORK_TEST_ROOT_DOMAIN=belluga.app`
- `--dart-define=NETWORK_TEST_TENANT_SUBDOMAIN=guarappari`

**Assertions**
- `GET https://{root}/environment` returns JSON with `type=landlord`
- `GET https://{tenant}.{root}/environment` returns JSON with `type=tenant`
- All fixed branding endpoints above return `200/304` on both origins

**Definition of Done**
- Tests are isolated under `test/network/` and can be skipped in offline dev, but must run in CI.

### M5 — Minimal integration smoke
**Coverage**
- [x] ✅ One integration test that boots the app and reaches initial route without exceptions (`integration_test/app_test.dart`).
- [ ] ⚪ Optional: navigate to schedule or invites route (smoke only).
- [ ] ⚪ Agenda filters regression: selecting filters in Agenda and Home uses the same controller state and updates the visible event list (use deterministic mock schedule data).

**Definition of Done**
- [x] ✅ Integration tests are stable and do not rely on external services (uses `MockBackend`).
- [ ] ⚪ Agenda filters regression test passes and guards against filter state divergence between Home and Agenda.

---

## Commands

### Local (fast)
- `cd flutter-app && fvm flutter test`

### Network contract tests
- `cd flutter-app && fvm flutter test test/network --dart-define=NETWORK_TEST_ROOT_DOMAIN=belluga.app --dart-define=NETWORK_TEST_TENANT_SUBDOMAIN=guarappari`

---

## Open Decisions (Close Before Implementation)
- Which tenant subdomain is canonical for tests (suggest: `guarappari`).
- Whether CI runs network tests on every PR or on a scheduled pipeline (tunnel flakiness tradeoff).
- What fallback UI is acceptable for logo/icon failures (placeholder asset vs empty container).

## Decisions (Agenda Filters Regression)
- Cover all agenda filters: past events, invited to, only confirmed (after confirming an invite), and search.
- Assert in Home and Agenda screens (past events only in Agenda).
- Use deterministic fixture data defined in the integration test file; avoid relying on runtime mock backends.

## Decisions (Integration Tests: Real Domains)
- For integration tests, use the real environment backend and actual tenant domains (not fake `example.com`) so AppData resolves from the live `/environment` payload.

## Decisions (Environment Error Diagnostics)
- When real `/environment` calls fail in integration tests, surface actionable error details (HTTP status + URL + payload where available).

completion_metadata: branch=main, commit=99eeb62c308ee1be99d9f39268631955c2ff6133
