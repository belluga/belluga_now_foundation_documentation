# TODO (V1): Tenant Admin Local Preferences — Map Default Origin Editor

**Status:** Active  
**Owners:** Flutter Team  
**Created:** 2026-03-03  
**Complexity:** `small`  
**Checkpoint policy:** consolidated review checkpoint before approval, then implementation.

---

## Goal
Expose tenant-level editing of `settings.map_ui.default_origin` in Tenant Admin (`/admin/settings/local-preferences`) so operators can set the fallback origin used by agenda/search when user location is unavailable.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/screens/modulo_tenant_admin.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/screens/modulo_tenant_admin.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`

---

## Scope
1. Add Flutter domain model support for tenant-admin `map_ui` preferences including `default_origin` (`lat`, `lng`, optional `label`).
2. Extend Tenant Admin settings repository contract + implementation to:
   - fetch current `map_ui` values;
   - patch `map_ui` values through settings-kernel endpoint.
3. Extend `TenantAdminSettingsController` to own local-preferences editing state for default origin and save workflow.
4. Update `/admin/settings/local-preferences` UI to include editable fields:
   - latitude
   - longitude
   - label (optional)
   - explicit save action.
5. Keep architecture boundaries intact:
   - no repository access from widgets/screens;
   - no new `setState` business state;
   - controller-owned orchestration only.
6. Add/update tests for:
   - repository request payload/parsing for `map_ui`;
   - controller save flow;
   - local-preferences screen interaction.

---

## Out of Scope
- Backend schema/route changes (already available via settings-kernel).
- Altering agenda/search fallback logic itself.
- Replacing local theme/radius behavior in this slice.

---

## Definition of Done
- Tenant admin local-preferences screen allows editing and saving `default_origin`.
- Save call persists through settings-kernel namespace `map_ui`.
- Saved values are reloaded and reflected in the UI.
- Analyzer passes and targeted tests pass.
- Canonical docs are updated and aligned with delivered UI behavior.

---

## Validation Steps
- Flutter:
  - `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
  - `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
  - `fvm flutter analyze`

---

## Applicable Rules/Workflows (for approval gate)
- `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-domain-workflow-glob/SKILL.md`
- `delphi-ai/skills/flutter-widget-local-state-heuristics/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-contract-alignment-always-on/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`

---

## Consolidated Review Checkpoint (Small)

### Architecture
- Keep `TenantAdminSettingsController` as single orchestration owner; UI remains declarative.
- Repository remains the only layer that knows endpoint paths/payload shape for settings-kernel.

### Code Quality
- Reuse existing settings patterns (firebase/push/telemetry/branding) to avoid parallel conventions.
- Keep parsing/normalization for numeric lat/lng in controller/repository, not widget tree.

### Tests
- Extend repository tests for `/settings/values/map_ui` request/response contract.
- Extend settings screen/controller tests for local-preferences save flow.

### Performance
- Scope is lightweight form + one PATCH call; no rendering hotspots expected.

### Security
- Auth/ability enforcement remains backend-owned (`map-pois-settings:update`).
- Client must surface backend error responses without exposing token data.

---

## Decision Baseline (Frozen)
- `D-01`: Local-preferences UI must provide explicit editing for `default_origin.lat`, `default_origin.lng`, and optional `default_origin.label`.
- `D-02`: `map_ui` read/write is repository-owned via settings-kernel endpoints; screens/widgets do not call backend directly.
- `D-03`: Controller owns all mutable editing/saving state; no new business state via widget `setState`.
- `D-04`: Save operation patches namespace `map_ui` and preserves existing radius/time-window keys by merge-safe payload strategy.
- `D-05`: Existing local theme/radius UX stays intact in this slice; only default-origin editing is introduced.

---

## Module Coherence Gate (Pre-implementation)

| Decision | Module Coherence | Change Intent | Evidence | Notes |
| --- | --- | --- | --- | --- |
| D-01 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md` (Settings local-preferences mentions `map_ui.default_origin`) | Implements documented contract that is currently missing in Flutter UI. |
| D-02 | Aligned | Preserve | `foundation_documentation/modules/tenant_admin_module.md` (`PATCH /admin/api/v1/settings/values/map_ui`) | Reuses canonical settings-kernel path instead of ad-hoc endpoint. |
| D-03 | Aligned | Preserve | `foundation_documentation/screens/modulo_tenant_admin.md` (screen/form separation rule) | Maintains controller-owned state architecture. |
| D-04 | Aligned | Preserve | `foundation_documentation/endpoints_mvp_contracts.md` (`settings.map_ui` structure) | Prevents accidental key loss during partial updates. |
| D-05 | Aligned | Preserve | `foundation_documentation/screens/modulo_tenant_admin.md` (local preferences currently theme/radius) | Keeps approved UX stable while adding required field. |

---

## Decision Adherence Validation
_To be filled after implementation._

| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| D-01 | Pending | — | — |
| D-02 | Pending | — | — |
| D-03 | Pending | — | — |
| D-04 | Pending | — | — |
| D-05 | Pending | — | — |
