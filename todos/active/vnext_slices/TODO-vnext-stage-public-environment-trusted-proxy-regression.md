# TODO (VNext): Stage Public Environment Trusted-Proxy Regression

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active (`Planning`)
**Owners:** Platform + Backend
**Objective:** Establish the correct fix for `stage` public bootstrap failures where `GET /api/v1/environment` returns `403 spoofed_client_ip_header`, then promote the fix through `laravel-app` and `belluga_now_docker` until `stage` is healthy again.
**Complexity:** `medium`
**Checkpoint policy:** full Plan Review Gate + one checkpoint before execution approval.

---

## Goal
Determine whether the current `stage` regression is caused by runtime proxy configuration or by the current Laravel hardening rule, implement the fix in the correct layer, and restore a healthy public-edge bootstrap path without weakening the stage gate.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/endpoints_mvp_contracts.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/system_roadmap.md`

---

## Context / Evidence
- `stage` public-edge bootstrap fails on `GET /api/v1/environment` with payload code `spoofed_client_ip_header`.
- `/api/v1/environment` is the MVP anonymous bootstrap entrypoint and must remain available on landlord and tenant public hosts.
- `ApiSecurityHardening` currently rejects forwarded client-IP headers when the request does not come from a trusted proxy chain.
- `TRUSTED_PROXIES` is infrastructure-derived and distinct from tenant domains stored in the database.
- The historical rescue branch `feat/rescue-account-onboarding-and-api-security` (PR `#74`) was not merged 1:1; it is reference material only, not the implementation baseline.
- `laravel-app origin/stage` already contains the current `origin/dev`, and `belluga_now_docker origin/stage` already pins that Laravel SHA. The active problem is lane health, not a missing prior promotion.
- Runtime evidence on the `stage` host proved the current failure is infrastructure/config driven:
  - host rollback state had `/srv/belluga_now_docker` at `05c07b3...` while the failed candidate run deployed `7619b0b...`
  - `laravel-app/.env` on host had `APP_ENV=stage` and `APP_URL=https://belluga.app`, but no `TRUSTED_PROXIES`
  - Nginx forwarded `REMOTE_ADDR=$remote_addr`, and the failed candidate logs showed Cloudflare edge IPs (`104.*`, `162.*`) hitting `/api/v1/environment`
  - after materializing Cloudflare CIDRs into `laravel-app/.env:TRUSTED_PROXIES`, both landlord and tenant public `/api/v1/environment` calls returned `200`

---

## Scope
1. Capture runtime evidence from `stage` for the public `/api/v1/environment` path:
   - `TRUSTED_PROXIES`
   - `API_SECURITY_REQUIRE_TRUSTED_PROXY_FOR_FORWARDED_HEADERS`
   - `REMOTE_ADDR`
   - forwarded headers present on the request
2. Classify the root cause as:
   - runtime/infra mismatch, or
   - Laravel hardening rule mismatch
3. Implement the fix in the correct layer.
4. Add regression coverage if Laravel code changes are required.
5. Promote the resulting change through `laravel-app` and `belluga_now_docker` up to `stage` when there is real delta.

## Out of Scope
- Using tenant domains from the database to define trusted proxies.
- Reopening PR `#74` as the implementation source.
- Weakening or bypassing the public-edge `/api/v1/environment` gate.
- Changing tenant resolution semantics for editable domains.

---

## Decision Baseline (Frozen)
- `D-01`: `TRUSTED_PROXIES` is infrastructure-derived and must never depend on tenant domains.
- `D-02`: The fix must be implemented on top of the current `laravel-app origin/dev` baseline, not by reviving `#74` wholesale.
- `D-03`: Public-edge `/api/v1/environment` validation remains mandatory.
- `D-04`: If runtime evidence proves misconfiguration, fix runtime first instead of patching Laravel blindly.
- `D-05`: If Laravel code changes are needed, add regression coverage for trusted-proxy/public-bootstrap behavior.

---

## Candidate Workstreams
- [x] ✅ Production‑Ready Capture stage runtime evidence for trusted-proxy evaluation.
- [x] ✅ Production‑Ready Compare runtime evidence against current Laravel hardening logic.
- [x] ✅ Production‑Ready Classify root cause as runtime/config mismatch (missing `TRUSTED_PROXIES` in host `laravel-app/.env`, not Laravel code regression).
- [ ] ⚪ Pending Apply the perene fix in Docker deploy/rollback governance so stage/main never bootstrap from `.env.example` and fail fast when required env keys are absent.
- [ ] ⚪ Pending Run targeted validation for the affected layer.
- [ ] ⚪ Pending Promote `laravel-app` to `stage` if Laravel changed.
- [ ] ⚪ Pending Regenerate/promote Docker `bot/next-version` if gitlinks changed.
- [ ] ⚪ Pending Confirm `belluga_now_docker stage` post-merge run is green.

---

## Definition of Done
- Root cause is proven with evidence.
- The fix is applied in the correct repository/layer.
- Public landlord and tenant `GET /api/v1/environment` on `stage` return valid `200` bootstrap responses.
- Required tests pass for any Laravel code changes.
- `laravel-app stage` is green if touched.
- `belluga_now_docker stage` is green after the required promotion path.
- Stable conclusions are promoted into canonical docs.

## Validation Targets
- Capture runtime evidence from the live `stage` environment.
- If Laravel changed: run targeted tests covering hardening + environment bootstrap.
- Promote through `stage` only on full green checks.
- Verify the Docker stage post-merge run includes a green public-edge environment gate.
