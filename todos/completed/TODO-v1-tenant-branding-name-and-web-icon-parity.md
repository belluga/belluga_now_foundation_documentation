# TODO (V1): Tenant Branding Name Persistence and Web Icon Parity

**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Cross-Stack`, `Tenant-Admin`, `Tenant-Public-Web`
**Next exact step:** Review local validation evidence and decide whether to promote this slice through the declared lane path.
**Owners:** Flutter Team + Laravel Team
**Objective:** Make tenant branding edits authoritative end to end so tenant name changes persist, public web metadata uses the persisted name, and browser favicon / installable PWA icons resolve from the correct dedicated assets instead of drifting across unrelated files.
**Promotion lane path:** `dev -> stage`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before implementation + decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `cross-stack`

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** one cohesive branding-delivery slice spanning the tenant-admin save flow and the tenant-public web shell/runtime outputs.
- **Direct-to-TODO rationale:** safe. The issue is already bounded around one user-facing objective: saving tenant branding in admin must update the canonical tenant identity and the correct web icon surfaces without inventing new product capability.

## Contract Boundary

- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` define the current proposed **HOW**.
- This TODO is **bounded but elastic** only while the work remains inside the same branding-delivery objective.
- If implementation reveals a separate capability slice (for example a broader tenant-settings identity editor or a manifest-generation redesign beyond this parity fix), the TODO must be updated or split before execution continues.

## Scope Ownership

This slice crosses two existing canonical scope owners and must keep both explicit:

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard / Identity |
| --- | --- | --- | --- | --- | --- |
| `/admin/settings/visual-identity` branding editor | tenant host | `tenant` | `tenant_admin` | `n/a` | landlord identity on tenant domain |
| `/api/v1/environment` tenant branding bootstrap | tenant host | `tenant` | `tenant_public` | `n/a` | anonymous/bootstrap |
| `/manifest.json` | tenant host | `tenant` | `tenant_public` | `n/a` | anonymous/bootstrap |
| `/favicon.ico` | tenant host | `tenant` | `tenant_public` | `n/a` | anonymous/bootstrap |
| shared public web shell `../web-app/index.html` | tenant host | `tenant` | `tenant_public` | `n/a` | anonymous/bootstrap |

## Module Anchors

- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:** `foundation_documentation/modules/flutter_client_experience_module.md`

### Canonical Coverage Status

- `tenant_admin_module.md`: authoritative for `/admin/settings/visual-identity`, but it does not currently define a persisted tenant-name contract for the branding save endpoint nor a dedicated favicon upload surface.
- `flutter_client_experience_module.md`: authoritative for `/api/v1/environment` and web bootstrap continuity, but it does not currently freeze favicon-vs-PWA separation or the requirement that the public shell reference the dynamic favicon endpoint instead of a static bundled file.

### Decision Consolidation Targets

- Promote tenant-admin branding contract updates into `foundation_documentation/modules/tenant_admin_module.md`.
- Promote public web icon/bootstrap contract updates into `foundation_documentation/modules/flutter_client_experience_module.md`.
- Do not edit `project_constitution.md` in this TODO. If execution uncovers a genuine project-level invariant, record the handoff because `project_constitution.md` is currently absent.

## Evidence Snapshot

- Flutter tenant-admin already sends `name` in the branding multipart payload, but backend validation does not accept it and tenant branding persistence ignores it:
  - `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
  - `laravel-app/app/Http/Api/v1/Requests/UpdateBrandingRequest.php`
  - `laravel-app/app/Application/Tenants/TenantBrandingManagementService.php`
- Tenant-admin UI explicitly warns that tenant-name persistence is not supported by the current branding endpoint:
  - `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_branding_section.dart`
- Public web shell currently points the browser favicon to bundled `favicon.png`, while Laravel exposes dynamic branding endpoints only for `/favicon.ico` and `/icon/...`:
  - `web-app/index.html`
  - `laravel-app/routes/web.php`
  - `laravel-app/app/Application/Branding/BrandingManifestService.php`
- `/api/v1/environment` and manifest name values come from `Tenant.name`, so any unsaved name keeps leaking into install prompt/title/bootstrap:
  - `laravel-app/app/Application/Environment/EnvironmentResolverService.php`
  - `laravel-app/app/Models/Landlord/Tenant.php`

## Scope

- [x] Extend tenant branding persistence so the edited tenant name is saved to the canonical tenant record and reflected in `/api/v1/environment` and `/manifest.json`.
- [x] Add a dedicated favicon upload path in tenant-admin visual identity instead of conflating favicon with the clear/dark/PWA icon fields.
- [x] Switch the public web shell to reference the dynamic favicon endpoint instead of static bundled `favicon.png`.
- [x] Keep `/favicon.ico` as the canonical browser favicon route, but when no dedicated favicon is uploaded, fall back to the generated PWA PNG asset instead of returning empty/404.
- [x] Preserve dedicated PWA icon behavior through the manifest `/icon/...` endpoints and keep it separate from favicon semantics.
- [x] Add/update backend + Flutter/web tests that prove the corrected name/icon contract end to end.
- [x] Update the canonical module docs for tenant-admin branding and public web icon/bootstrap behavior.

## Out of Scope

- [ ] No redesign of the broader tenant settings IA beyond the missing favicon control needed for parity.
- [ ] No multi-locale app-name strategy or separate `short_name` UX unless required to complete the persisted-name contract.
- [ ] No bootstrap visual redesign outside the favicon/icon/name parity problem.
- [ ] No landlord branding/editor changes unless the same bug is proven there and explicitly added to scope.

## Definition of Done

- [x] Editing the tenant name in `/admin/settings/visual-identity` persists to the canonical tenant record and survives reload.
- [x] A fresh `/api/v1/environment` response returns the persisted tenant name.
- [x] `manifest.json` returns the persisted tenant `name` / `short_name` values expected by the install prompt.
- [x] Browser tab favicon resolves through the dynamic branding endpoint and no longer depends on bundled `favicon.png`.
- [x] The canonical browser favicon contract remains `/favicon.ico`; if `favicon_uri` is absent, the route falls back deterministically to the generated PWA PNG variant instead of failing.
- [x] PWA install icon remains sourced from the manifest `/icon/...` endpoints and stays independent from favicon.
- [x] Tenant-admin exposes a dedicated favicon input, and saving favicon + PWA icon updates the correct downstream surfaces.
- [x] Focused backend and Flutter/web tests cover the persisted-name contract and favicon/PWA separation.
- [x] Canonical module docs reflect the final contract.

## Validation Steps

- [x] Laravel: targeted branding feature tests for update + environment + manifest + favicon asset responses.
- [x] Flutter: targeted repository/controller/widget tests for tenant-admin branding save flow and dedicated favicon handling.
- [x] Web shell: targeted shell-level assertion(s) for dynamic favicon reference plus local runtime smoke.
- [x] Flutter analyzer: `fvm dart analyze --format machine`

## Delivery Status Semantics

- `Pending`: planning complete but implementation not started.
- `Local-Implemented`: code and docs changed locally with targeted validation evidence.
- `Lane-Promoted`: merged through the lane threshold declared above.
- `Production-Ready`: stage threshold complete with required confidence gates satisfied.
- `Blocked`: cannot proceed; blocker notes become mandatory.

## Complexity

- **Level (`small|medium|big`):** `medium`
- **Why this level:** the slice is cohesive, but it crosses Flutter admin UI, Laravel branding persistence, public web shell behavior, and documentation.

## Questions To Close

- [x] `Q-01` Approve the proposed contract shape: reuse `POST /admin/api/v1/branding/update` to persist tenant name, instead of creating a separate endpoint first.

## Decision Baseline (Frozen Before Implementation)

- [x] `D-01` The canonical tenant name edited in tenant-admin visual identity must persist through the existing branding save flow and become authoritative for `/api/v1/environment` and `manifest.json`.
- [x] `D-02` Favicon and PWA icon are separate assets with separate downstream consumers:
  - favicon -> browser tab / bookmark icon via `/favicon.ico`
  - PWA icon -> installable app manifest `/icon/...`
- [x] `D-03` The public web shell must reference the dynamic favicon endpoint, not bundled `favicon.png`.
- [x] `D-04` Tenant-admin visual identity must expose a dedicated favicon upload control because the existing clear/dark/PWA icon controls do not cover favicon semantics.
- [x] `D-05` This TODO will not widen into a general tenant identity/settings redesign; only the name/icon parity contract is in scope.
- [x] `D-06` The official uploaded favicon format remains `.ico`. When no dedicated favicon exists, `/favicon.ico` must fall back to the generated PWA PNG asset, preferring `icon192_uri` and only then larger/source PNG variants if needed.

## Module Decision Baseline Snapshot

| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `tenant_admin_module.md#3.6` | `/admin/settings/visual-identity` owns branding/visual identity | `Supersede (Intentional)` | add explicit persisted-name + dedicated favicon contract |
| `flutter_client_experience_module.md#web-bootstrap-visual-continuity` | tenant-public web bootstrap must use coherent branding inputs | `Supersede (Intentional)` | add explicit favicon-vs-PWA separation and dynamic favicon shell reference |
| `flutter_client_experience_module.md#2.2` | `/api/v1/environment` resolves tenant branding/context for bootstrap | `Preserve` | name persistence flows into the same canonical endpoint |

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Reusing `POST /admin/api/v1/branding/update` for tenant-name persistence is acceptable because Flutter already sends `name` there and the user expectation is already tied to that screen. | existing Flutter payload already includes `name`; current UI exposes name edit in the same section | would require a separate endpoint + new TODO boundary | `Medium` | `Promote to Decision` |
| `A-02` | The real public shell served in runtime is `../web-app/index.html`, so favicon parity must be fixed there rather than only in `flutter-app/web/index.html`. | `FlutterWebShellRenderer` resolves `../web-app/index.html`; current deployed shell contains static `favicon.png` reference | fixing only `flutter-app/web/index.html` would leave production behavior unchanged | `High` | `Keep as Assumption` |
| `A-03` | No analyzer-enforced rule is warranted for this defect because the failure comes from runtime contract drift across backend + shell asset wiring, not a repeated statically recognizable code-shape violation. | issue depends on endpoint payload support and runtime web-asset references | if repeated static misuse is found, a separate rule follow-up could be proposed later | `High` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces

- `flutter-app/lib/presentation/tenant_admin/settings/**`
- `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
- `flutter-app/test/**` targeted branding tests
- `web-app/index.html`
- `laravel-app/app/Http/Api/v1/Requests/UpdateBrandingRequest.php`
- `laravel-app/app/Http/Api/v1/Controllers/TenantBrandingController.php` if response shape needs alignment
- `laravel-app/app/Application/Tenants/TenantBrandingManagementService.php`
- `laravel-app/app/Application/Environment/EnvironmentResolverService.php` only if additional name/short_name exposure is required
- `laravel-app/app/Models/Landlord/Tenant.php`
- `laravel-app/tests/**` targeted branding tests
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

### Ordered Steps

1. Extend backend branding request/service so `name` is validated and persisted to the canonical tenant model.
2. Ensure environment + manifest outputs reflect the persisted tenant name deterministically.
3. Add a dedicated favicon control to tenant-admin visual identity and wire it through the existing branding save flow.
4. Point the public web shell favicon reference to `/favicon.ico` and implement the backend fallback chain `favicon_uri -> pwa_icon.icon192_uri -> pwa_icon.icon512_uri -> pwa_icon.source_uri`.
5. Add/update targeted tests for:
   - backend name persistence
   - environment/manifest name reflection
   - favicon/PWA endpoint separation
   - Flutter admin save flow and dedicated favicon handling
6. Update the canonical module docs and record delivery evidence.

### Test Strategy

- **Strategy:** `test-first` for the backend name-persistence and shell-reference fixes where practical; otherwise fail-first on the most direct targeted tests before patching.
- **Fail-first targets:** missing `name` persistence in branding update tests and incorrect static favicon reference in shell-level assertion.

## Plan Review Gate

### Issue Cards

- **Issue ID:** `PLAN-01`
  - **Severity:** `high`
  - **Evidence:** current Flutter admin already collects/sends tenant name, but backend validation/persistence ignore it.
  - **Why it matters now:** the UI invites an edit that never becomes canonical, so runtime branding continues using stale tenant identity.
  - **Recommended option:** accept `name` in the existing branding update flow and persist it on `Tenant`.

- **Issue ID:** `PLAN-02`
  - **Severity:** `high`
  - **Evidence:** public shell uses `favicon.png`, while Laravel only exposes dynamic favicon through `/favicon.ico` and dynamic PWA icons through `/icon/...`.
  - **Why it matters now:** browser tab icon and install prompt can drift even when branding assets were uploaded correctly.
  - **Recommended option:** switch shell favicon to `/favicon.ico` and keep PWA icons exclusively in manifest endpoints.

- **Issue ID:** `PLAN-03`
  - **Severity:** `medium`
  - **Evidence:** current tenant-admin visual identity screen has no favicon upload control despite backend/domain support for `favicon_uri`.
  - **Why it matters now:** the user cannot intentionally manage favicon from the current admin UI, so parity cannot be guaranteed.
  - **Recommended option:** add one dedicated favicon field in the same visual-identity surface.

### Failure Modes & Edge Cases

- name persists to `Tenant.name` but `short_name` remains stale or derives unexpectedly;
- shell reference changes but browser cache keeps an old favicon until cache-busting or hard refresh;
- `/favicon.ico` fallback serves PNG bytes and a conservative client ignores them despite modern browsers accepting them;
- favicon upload control is added but wired to the wrong field, accidentally overwriting PWA icon or clear/dark icons;
- manifest and environment stay correct while install prompt still shows old data due to browser PWA cache.

### Residual Risks / Unknowns

- Browser/PWA caches may require explicit manual validation even after tests pass.
- If the product later wants distinct `name` vs `short_name`, this TODO should not silently invent that UX without a follow-up decision.

## Bug-Fix Evidence Answers

1. **Do we already have tests that cover this behavior across all stages up to UI display?**
   - Partially. There is targeted coverage for branding read/reload and PWA icon mapping, but not for tenant-name persistence through the branding endpoint nor for the public shell favicon reference.
2. **Did we inspect current real backend payload assumptions?**
   - Yes. `/api/v1/environment` reads `name` from `Tenant.name`, while the branding update endpoint currently validates/persists only theme + logo/icon payload.
3. **If existing tests should cover the bug, which exact tests failed? If none failed, why were they insufficient?**
   - No existing test is aimed at the missing name-persistence path or the static shell favicon reference, so the gap is `missing`, not `false-green`.
4. **If tests do not cover the failure, which new tests must be created before implementing the fix?**
   - Backend branding update persistence test for `name`, environment/manifest reflection test, and a web-shell assertion that the favicon link points to `/favicon.ico`.
5. **Is the root cause also an architectural deviation pattern that could be prevented earlier by analyzer-enforced rule coverage? Why or why not?**
   - `no-rule-needed`. The defect is cross-layer runtime contract drift, not a narrow statically detectable code shape.
