# TODO (V1): Local Web Tenant Admin Origin Consistency

**Status:** Completed (`Validated locally and promoted into the canonical Flutter lane`)  
**Owners:** Flutter Team  
**Created:** 2026-03-06  
**Complexity:** `medium`  
**Checkpoint policy:** one full review checkpoint before approval (Plan Review Gate), then implementation.

---

## Goal
Establish a correct, canonical tenant-admin web origin model for local browser flows (`belluga.space` / `guarappari.belluga.space` via Cloudflared), so tenant-admin API requests never inherit an internal ingress port and authenticated admin screens (notably `/admin/events`) load successfully.

**Regression evidence (2026-03-06, TD-001):** Local browser access to `https://guarappari.belluga.space/admin/events` fails with a tenant connection error because the app requests `https://guarappari.belluga.space:8043/admin/api/v1/events?...`, leaking the internal local ingress port into the tenant host.

**Coverage gap evidence (2026-03-06, TD-002):** The current web navigation smoke passes because it validates anonymous/public bootstrap routes only and does not execute an authenticated tenant-admin data-loading flow such as `/admin/events`.

**Documentation drift evidence (2026-03-06, TD-003):** Current local guidance in `README.md` and test-hardening notes still endorses `LANDLORD_DOMAIN=https://belluga.space:8043` for local web smoke, which is incompatible with the actual Cloudflared browser topology (`https://belluga.space`, no explicit port).

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-critical-journey-regression-gates.md`
  - `README.md`
  - `../README.md`

---

## Scope
1. Correct tenant-admin base URL resolution so canonical tenant host comes from the selected tenant domain and local web browser flows do not inherit an internal landlord port.
2. Preserve explicit scheme/port when the selected tenant domain already declares them.
3. Keep local browser-facing configuration aligned with Cloudflared/public domains (`belluga.space`, `guarappari.belluga.space`) rather than internal ingress ports.
4. Remove or replace local documentation/instructions that currently prescribe `https://belluga.space:8043` for browser-facing web validation.
5. Add deterministic automated coverage for the failing resolver case so the regression is blocked without relying on anonymous navigation smoke.
6. Add a local validation step for authenticated tenant-admin `/admin/events` using the real browser-facing local domains.
7. Record the summary drift (`submodule_flutter-app_summary.md` commit mismatch) and promote the stable outcome after implementation.
8. Validate each frozen decision against canonical module docs before implementation and before TODO closure; any conflict must be explicitly classified as `Preserve` or `Supersede`.

---

## Out of Scope
- Reworking cross-domain admin authentication architecture.
- Adding a new stage/main authenticated Playwright gate in CI.
- Refactoring unrelated tenant-admin repositories beyond the origin-resolution path.
- Changing landlord/tenant canonical domain semantics already established in the previous decision baseline.

---

## Definition of Done
- Tenant-admin web API requests on local browser flows no longer leak `:8043` into tenant hosts when the browser-facing origin does not use that port.
- `resolveTenantAdminBaseUrl(...)` is covered by tests for the Cloudflared/public-domain case and explicit-origin cases.
- Local browser validation of authenticated `/admin/events` succeeds against `https://guarappari.belluga.space`.
- Local web guidance no longer tells developers to use `https://belluga.space:8043` as the browser-facing landlord domain for this flow.
- The canonical module docs and summary references reflect the corrected local-web topology and tenant-admin origin rule.
- Every frozen decision has explicit module coherence status with evidence; conflicts are explicitly approved as `Supersede` before implementation.

---

## Validation Steps
- Flutter:
  - `fvm flutter test test/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver_test.dart`
  - `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
  - `fvm flutter analyze`
- Local web/browser:
  - `bash scripts/build_web.sh ../web-app dev --clean-output`
  - manual or scripted authenticated validation of `https://guarappari.belluga.space/admin/events` using tenant-admin credentials
- Adherence:
  - `bash delphi-ai/tools/verify_adherence_sync.sh`

---

## Applicable Rules/Workflows (for approval gate)
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-contract-alignment-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/test-orchestration-suite/SKILL.md`

---

## Decision Baseline (Frozen)
- `D-01` Tenant-admin canonical host continues to come from the selected tenant domain / main domain, not from landlord runtime host.
- `D-02` In local browser-facing web flows, internal ingress ports must not leak into public tenant-admin URLs.
- `D-03` Explicit tenant origins (scheme/port already present in the selected tenant domain) remain authoritative.
- `D-04` The regression must be blocked by deterministic automated coverage at the resolver/consumer layer; anonymous navigation smoke is insufficient for this case.
- `D-05` Local documentation must describe browser-facing Cloudflared domains as the source of truth for manual web validation in this flow.

---

## Module Coherence Gate (Mandatory)

| Decision | Module Coherence | Change Intent | Evidence | Notes |
|---|---|---|---|---|
| D-01 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md:145`, `foundation_documentation/modules/flutter_client_experience_module.md:77` | Tenant admin is tenant-domain scoped; canonical tenant requests should follow tenant domain selection. |
| D-02 | Aligned | Preserve | `foundation_documentation/modules/system_architecture_principles.md:22`, `foundation_documentation/modules/flutter_client_experience_module.md:224` | Public/browser topology should not depend on hidden local ingress details. |
| D-03 | Aligned | Preserve | `test/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver_test.dart:5` | Existing explicit-origin behavior is already contractual and should remain. |
| D-04 | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md:224` | Testing strategy requires targeted regression coverage for client routing/data behavior. |
| D-05 | Supersede | Supersede | `../README.md:361`, `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-critical-journey-regression-gates.md:263` | Current docs prescribe `:8043` in browser-facing local web flows; this TODO replaces that guidance with Cloudflared/public-domain wording. |

Implementation cannot proceed with unresolved `Conflict`. `Supersede` requires explicit approval and module/doc promotion before close.

---

## Plan Review Gate (Medium)

### Issue Card I-01
- **Severity:** High
- **Category:** Runtime / Networking
- **Evidence:** `lib/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver.dart` currently copies landlord port when the selected tenant domain omits a port.
- **Why now:** Local tenant-admin browser flows fail to load authenticated data on `/admin/events`.
- **Options:**
  - **A (Recommended):** Stop inheriting landlord port for bare tenant domains; keep canonical tenant host and derive origin without leaking internal ingress port.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Medium (tenant-admin repositories using shared resolver)
    - Maintenance burden: Low
  - **B:** Keep current resolver and document that browser-local tests must use `:8043`.
    - Effort: Low
    - Risk: High
    - Blast radius: High
    - Maintenance burden: High
  - **C:** Introduce tenant-admin-specific hardcoded local exception.
    - Effort: Low
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High

### Issue Card I-02
- **Severity:** High
- **Category:** Test Coverage
- **Evidence:** `tools/flutter/web_app_tests/navigation.spec.js` covers anonymous/public bootstrap routes only and does not validate authenticated `/admin/events` data loading.
- **Why now:** A real browser regression escaped while web smoke stayed green.
- **Options:**
  - **A (Recommended):** Add deterministic automated coverage at the resolver/unit level and validate authenticated `/admin/events` locally as part of this regression closure.
    - Effort: Medium
    - Risk: Low
    - Blast radius: Low to Medium
    - Maintenance burden: Medium
  - **B:** Add only a new authenticated Playwright gate immediately in CI.
    - Effort: Medium to High
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium
  - **C:** Do nothing and keep relying on current smoke.
    - Effort: None
    - Risk: Critical
    - Blast radius: High
    - Maintenance burden: High

### Issue Card I-03
- **Severity:** Medium
- **Category:** Documentation / Workflow
- **Evidence:** `../README.md:361` and `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-critical-journey-regression-gates.md:263-264` prescribe `https://belluga.space:8043` in local web validation.
- **Why now:** The written guidance reinforces the wrong browser topology and encourages future false-green validation.
- **Options:**
  - **A (Recommended):** Update docs to describe browser-facing Cloudflared/public domains for this flow and reserve internal ports for internal ingress-only diagnostics.
    - Effort: Low
    - Risk: Low
    - Blast radius: Medium
    - Maintenance burden: Low
  - **B:** Leave docs as-is and rely on tribal knowledge.
    - Effort: None
    - Risk: High
    - Blast radius: Medium
    - Maintenance burden: High
  - **C:** Remove all local web guidance.
    - Effort: Low
    - Risk: Medium
    - Blast radius: Medium
    - Maintenance burden: Medium

### Failure Modes & Edge Cases
- Selected tenant domain may include explicit `http://` or custom port and must remain untouched.
- Non-web flows may still require landlord-origin fallback where no browser origin exists.
- Existing tenant-admin repositories all consume the same resolver; a bad fix can ripple across accounts, settings, assets, and events.
- Local Cloudflared/browser topology and internal ingress topology must not be conflated in docs or tests.

### Uncertainty Register
- **Assumptions:** Local Cloudflared/public-domain access is the canonical browser-facing topology for this environment.
- **Unknowns:** Whether any other local-only smoke docs still encode `:8043` assumptions outside the files already identified.
- **Confidence:** Medium-high.

---

## Request for Approval
This TODO is ready for approval under the listed rules/workflows. It explicitly supersedes the current `:8043` browser-local guidance (`D-05`) and freezes the origin-resolution/test-coverage decisions above.

Reply with **APROVADO** to authorize implementation.

---

## Implementation Notes (2026-03-06)
- `resolveTenantAdminBaseUrl(...)` now prefers the actual browser-facing origin when resolving scheme/port for implicit tenant domains, instead of blindly inheriting the landlord port. Explicit tenant origins remain authoritative.
- Local ignored override `config/defines/local.override.json` was corrected in the working environment from `https://belluga.space:8043` to `https://belluga.space` to match the real Cloudflared browser-facing topology. This file remains gitignored/local-only.
- Active docs were updated to stop prescribing `https://belluga.space:8043` for browser-facing local web validation.

## Validation Evidence (2026-03-06)
- `fvm flutter test test/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver_test.dart` ✅
- `fvm flutter test test/infrastructure/repositories/tenant_admin_events_repository_test.dart` ✅
- `fvm flutter analyze` ✅
- `bash scripts/build_web.sh ../web-app dev --clean-output` ✅
- `NAV_DEPLOY_LANE=local NAV_WEB_TEST_TYPE=readonly NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` ✅
- Real backend validation with tenant-admin token on the corrected host/path:
  - `POST belluga.space /admin/api/v1/auth/login` returned `200` and a real token.
  - `GET guarappari.belluga.space /admin/api/v1/events?page=1&page_size=20` with that token returned `200` and `3` events.
  - Response `path` and pagination URLs now resolve to `https://guarappari.belluga.space/admin/api/v1/events` (no leaked `:8043`).
  - Rebuilt `web-app/main.dart.js` no longer contains `belluga.space:8043`.

## Decision Adherence Validation

| Decision | Status | Evidence | Notes |
|---|---|---|---|
| D-01 | Adherent | `flutter-app/lib/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver.dart` | Canonical tenant host still comes from selected tenant domain. |
| D-02 | Adherent | local backend validation (`GET https://guarappari.belluga.space/admin/api/v1/events`) | Internal ingress port no longer leaks into public tenant-admin URL. |
| D-03 | Adherent | `flutter-app/test/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver_test.dart` | Explicit origin cases remain unchanged. |
| D-04 | Adherent | `flutter-app/test/infrastructure/services/tenant_admin/tenant_admin_base_url_resolver_test.dart` | Deterministic automated coverage added for the escaped resolver case. |
| D-05 | Adherent | `README.md`, `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-critical-journey-regression-gates.md`, `foundation_documentation/submodule_flutter-app_summary.md` | Active guidance now describes browser-facing domains instead of `:8043`. |

## Completion Note
- `2026-03-07`: The local browser-facing tenant-admin origin rule is now treated as canonical. Resolver coverage, real authenticated `/admin/events` validation, and the later promoted Flutter lanes all relied on this corrected behavior.
