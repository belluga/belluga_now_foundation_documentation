# TODO (V1): Tenant Admin External Image Proxy (URL Import Without CORS)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Backend + Flutter  
**Date:** 2026-02-18

## Context
On Flutter Web, importing an image from a user-provided URL fails frequently due to browser CORS restrictions and/or origin hotlink protections. This makes the "import from URL" path unreliable on web.

We do **not** want to persist user-provided URLs directly as avatar/cover values; we want to ingest bytes, normalize (crop/resize/compress), and upload the resulting file to Laravel.

## Goal
Add an **authenticated** tenant-admin API endpoint that downloads an external image server-to-server and returns the raw bytes to the client, so Flutter Web can ingest the image reliably.

## Scope
- Laravel:
  - New tenant-admin authenticated endpoint under `/admin/api/v1/**` that accepts an external URL and returns the downloaded image bytes.
  - SSRF guardrails: allow only `http/https`, block private/reserved IPs, cap redirects, enforce max bytes, enforce image content-type.
  - Add feature tests covering auth, validation, SSRF blocks, and successful proxy response via `Http::fake`.
- Flutter:
  - Use the proxy endpoint for URL import (web primary; allowed for mobile too for consistency).
  - Keep the existing ingestion pipeline: bytes -> normalize -> upload (never persist the URL as canonical state).
  - Add unit tests asserting that URL import uses the proxy service and still returns a normalized `XFile`.

## Out of Scope
- Image persistence/storage changes (S3, CDN, etc.).
- Any change to tenant resolution rules.
- Complex image transformations on the server (we only proxy bytes; client still normalizes/crops).

## Decisions
- Route is **authenticated** and tenant-scoped:
  - `auth:sanctum` + `CheckTenantAccess`.
  - Ability gate reused from the same tenant-admin editing flows (no new ability set in this batch).
- Endpoint uses `POST` with JSON body to avoid accidental caching and URL logging.
- Response uses `Cache-Control: no-store` and returns `Content-Type` from the upstream (or validated fallback).
- Safety limits (initial):
  - Max upstream bytes: 15MB.
  - Max redirects: 3.
  - Timeout: 15s.

## Implementation Tasks
- [x] ✅ Production‑Ready Laravel: implement authenticated proxy endpoint for external image bytes (SSRF guarded).
- [x] ✅ Production‑Ready Laravel: enforce SSRF validation + limits (scheme, host resolution, private/reserved IP block, redirects, byte cap).
- [x] ✅ Production‑Ready Laravel: tests cover auth + validation + SSRF blocks + size limits.
- [x] ✅ Production‑Ready Flutter: add proxy contract + service wired via DI.
- [x] ✅ Production‑Ready Flutter: URL import uses proxy and preserves UX (URL -> crop -> upload).
- [x] ✅ Production‑Ready Flutter: unit/widget/integration coverage for URL import + crop flows.
- [x] ✅ Production‑Ready Validation: relevant Flutter + Laravel test suites green for this feature.

## Definition Of Done
- Flutter Web can import an image from a URL without CORS errors by calling the authenticated proxy endpoint.
- Proxy endpoint enforces SSRF + size limits and returns actionable errors on failure.
- Tests are green for both Laravel + Flutter changes introduced by this TODO.

## Notes
This TODO is completed as delivered: Flutter Web URL-import routes through Laravel to avoid CORS and keeps the same crop + upload UX as device ingestion.
