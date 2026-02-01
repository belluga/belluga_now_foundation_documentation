# TODO (V1): Fix Tenant Image URL Routing + Persist NGINX Mapping

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
**Owners:** DevOps, Backend Team  
**Objective:** Ensure tenant image URLs (avatar/cover) are served by the backend/storage, not Flutter, and that the routing survives container restarts.

---

## A) Scope
- Verify the tenant image URL route (e.g., `/account-profiles/{id}/avatar`) resolves to the backend/storage and not Flutter.
- Fix NGINX ingress/route handling so these URLs reach Laravel/storage endpoints.
- Ensure the NGINX configuration is persisted across container restarts (no manual in‑container edits).
- Validate with a real URL (e.g., `http://guarappari.belluga.app/account-profiles/{id}/avatar?v=...`).

## B) Out of Scope
- Changing Flutter image widgets or caching behavior.
- Changing storage provider or file naming strategy.
- Refactoring backend upload logic (unless required to restore route integrity).

## C) Tasks
- [x] ✅ Production‑Ready Reproduce: curl the tenant image URL and confirm it hits Flutter (wrong target).
- [x] ✅ Production‑Ready Inspect NGINX config sources (repo templates vs. live container) and locate route handling for `/account-profiles/*`.
- [x] ✅ Production‑Ready Update the NGINX config in repo (not in container) so `/account-profiles/*` proxies to Laravel/storage correctly.
- [x] ✅ Production‑Ready Rebuild/restart containers and re‑test URL routing to confirm persistence.
- [ ] ⚪ Pending If needed, document the persistent config location and ensure Docker compose uses it.

## D) Definition of Done
- [x] ✅ Production‑Ready Tenant image URL returns the image (or backend 404) instead of Flutter app shell.
- [x] ✅ Production‑Ready Restarting containers preserves the correct routing.

## E) Validation
- [x] ✅ Production‑Ready `curl -I http://guarappari.belluga.app/account-profiles/{id}/avatar?...` returns non‑Flutter response.
- [x] ✅ Production‑Ready Container restart + same curl still returns non‑Flutter response.
