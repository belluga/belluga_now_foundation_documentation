# TODO (VNext): Flutter Architecture Debt Burndown

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active (`Repo-wide non-primitive custom-lint debt targeted for closure in this branch`)
**Owner:** Delphi
**Date:** 2026-03-11
**Complexity:** `big`

## Goal
Close the remaining repo-wide Flutter architectural debt surfaced by the active custom lint rules in this branch, while keeping `domain_primitive_field_forbidden` disabled by root config until its dedicated debt program is complete and the rule can be re-enabled safely.

## References
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/submodule_flutter-app_summary.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-code-cleanup-unused-components.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-raw-dto-domain-parse-hardening.md`
- `tool/belluga_custom_lint/docs/rules.md`

## Canonical Module Anchors
- **Primary module:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary modules/contracts:**
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- **Promotion targets after delivery:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `tool/belluga_custom_lint/docs/rules.md`
- **Decision consolidation targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1 Domain Rules`, `2.1.1 Presentation DI Matrix`)
  - `foundation_documentation/domain_entities.md` (`Flutter domain boundary`, map/settings aggregates)

## Scope
- Maintain the repo-wide architectural debt inventory opened by the new custom lint surface:
  - `domain_primitive_field_forbidden`
  - `domain_json_factory_forbidden`
  - `repository_json_parsing_forbidden`
  - `service_json_parsing_forbidden`
  - `repository_inline_dto_to_domain_mapper_forbidden`
  - `multi_public_class_file_warning`
- Burn down the debt iteratively, by bounded domain/repository/presentation clusters, until the repo can realistically move from warnings-only to harder enforcement.
- Treat the current feature branch as a mandatory no-new-debt lane:
  - every file changed against `origin/dev` must be custom-lint adherent before delivery,
  - no feature shipped in the branch may rely on the newly forbidden patterns.
- In this branch, eliminate the currently active repo-wide findings for:
  - `multi_public_class_file_warning`
  - `repository_json_parsing_forbidden`
  - `domain_json_factory_forbidden`
  - `repository_inline_dto_to_domain_mapper_forbidden`
- Preserve the current rollout for `domain_primitive_field_forbidden`:
  - keep it disabled in root config for now,
  - keep the rule semantics intact,
  - leave explicit re-enablement tracked in this TODO after debt burn-down.
- Keep parser-hardening coverage aligned with the parallel VNext parsing-hardening TODO; use this TODO for architecture debt, not for exhaustive parser test inventory.

## Out Of Scope
- Burning down the repo-wide `domain_primitive_field_forbidden` findings in this exact branch.
- CI severity changes for the new rules before the debt is materially reduced.
- Path-based carve-outs for `lib/domain/**` just to reduce findings.
- Laravel formatting/test debt except where backend contracts must be documented for Flutter repository alignment.

## Current Baseline Snapshot
- Latest active full-app baseline from `fvm dart run custom_lint` with `domain_primitive_field_forbidden` disabled in root config:
  - `64` `multi_public_class_file_warning`
  - `10` `repository_json_parsing_forbidden`
  - `4` `domain_json_factory_forbidden`
  - `2` `repository_inline_dto_to_domain_mapper_forbidden`
  - total active findings: `80`
- `domain_primitive_field_forbidden` remains intentionally disabled by root config until its dedicated debt lane is burned down and the rule can be re-enabled explicitly.
- Initial branch-delta findings had concentrated in these files before the cleanup:
  - `lib/domain/app_data/app_data.dart`
  - `lib/domain/map/city_poi_model.dart`
  - `lib/domain/map/filters/poi_filter_options.dart`
  - `lib/domain/map/queries/poi_query.dart`
  - `lib/domain/tenant_admin/tenant_admin_settings.dart`
  - `lib/infrastructure/dal/dto/map/map_filters_dto.dart`
  - `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
  - `lib/infrastructure/services/http/laravel_map_poi_http_service.dart`
  - `lib/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service.dart`
- Parallel debt lanes still open elsewhere in the repo are intentionally tracked here but are not required to close before this branch ships.

## Branch Delta Classification Snapshot
- **Explicit domain case:**
  - `lib/domain/map/projections/city_poi_model.dart`
    - `Model` under `lib/domain/**` is treated as domain, not as an ambiguous carrier.
    - Therefore `CityPoiModel` must comply with `domain_primitive_field_forbidden` while it remains a domain model.
    - If the team ever decides this structure is not a domain model, the correction is to rename/reclassify/move it out of the domain layer, not to weaken the rule.
- **Resolved in this slice as real domain types (VO-backed, no primitive final fields):**
  - `lib/domain/app_data/app_data.dart`
  - `lib/domain/app_data/firebase_settings.dart`
  - `lib/domain/app_data/push_settings.dart`
  - `lib/domain/app_data/telemetry_context_settings.dart`
  - `lib/domain/map/queries/poi_query.dart`
  - `lib/domain/map/filters/poi_filter_options.dart` plus related `PoiFilter*` files
  - `lib/domain/map/projections/city_poi_model.dart`
  - `lib/domain/tenant_admin/tenant_admin_settings.dart` plus related `settings/*`
- **Blast radius note:**
  - `AppData` has wide cross-layer usage (bootstrap, guards, repositories, presentation, tests).
  - `TenantAdminSettings*` is shared by repository, controller, screens, widgets, and tests.
  - `PoiQuery` / `PoiFilterOptions` / `CityPoiModel` were heavily referenced in map repositories, controllers, widgets, DAL, and tests, so the fix preserved the public API while moving primitive storage into ValueObjects.

## Branch Delivery Rule (Frozen)
- `BR-01` A branch may ship with historical repo debt still open, but it must not add or preserve custom-lint debt inside files changed by that branch.
- `BR-02` If a new lint rule produces false positives in branch-touched files, calibrate the rule only when the AST match is objectively wrong; never carve out `domain` subpaths merely to reduce findings.
- `BR-03` DTO parsing/hydration stays in DAO/infrastructure mapper boundaries for any branch-touched repository/service file.
- `BR-04` Domain files changed by the branch must express semantics through domain-owned types/value objects or be structurally refactored until the rule no longer flags them.
- `BR-05` Structural hygiene applies to changed files too: one public class per file unless a rule calibration proves the finding is non-objective.
- `BR-06` `domain_primitive_field_forbidden` may stay disabled in root config during the debt program, but its semantics remain full for any explicit audit/fixture run.

## Decision Baseline (Frozen)
- `D-01` Preserve the warnings-only rollout while the repo-wide debt remains open.
- `D-02` Use branch-delta adherence as the minimum gate for every feature branch starting with the current one.
- `D-03` Resolve objective findings by code refactor first; use rule calibration only when the AST rule is demonstrably over-broad.
- `D-04` Keep the repo-wide debt burn-down separated from the parsing-test-hardening program, while cross-linking both TODOs when a finding affects parser semantics.
- `D-05` `domain_primitive_field_forbidden` cannot be narrowed by path; rollout control must happen via config, while domain carriers that fail the rule are reclassified or refactored.
- `D-06` The remaining active repo-wide findings in this branch must be reduced to zero rather than deferred again, because they are concentrated in structural/path-local refactors and a small set of repository/domain cleanup points.

## Module Coherence Gate (Pre-Implementation)
| Decision ID | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | Aligned | Preserve | `tool/belluga_custom_lint/docs/rules.md`, warnings-only rollout frozen in prior TODO |
| `D-02` | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1.1 Presentation DI Matrix`) |
| `D-03` | Aligned | Preserve | `foundation_documentation/domain_entities.md` (`Flutter domain boundary`) |
| `D-04` | Aligned | Preserve | `TODO-vnext-raw-dto-domain-parse-hardening.md` scope separation |
| `D-06` | Aligned | Preserve | Current session decision to close the remaining non-primitive custom-lint debt in this branch |
| `BR-01` | Aligned | Preserve | Current session decision approved by the user on 2026-03-11 |
| `BR-02` | Aligned | Preserve | `SEM EXCEÇÃO` policy in prior architecture-lint consolidation TODO |
| `BR-03` | Aligned | Preserve | `foundation_documentation/domain_entities.md` |
| `BR-04` | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1 Domain Rules`) |
| `BR-05` | Aligned | Preserve | `tool/belluga_custom_lint/docs/rules.md` (`multi_public_class_file_warning`) |

## Workstreams
### WS-01 Branch Delta Adherence
- [x] ✅ Production-Ready Identify all custom-lint findings in files changed by the current branch.
- [x] ✅ Production-Ready Reclassify or refactor branch-touched `lib/domain/**` carriers that fail `domain_primitive_field_forbidden` under full semantics.
- [ ] ⚪ Pending Re-run targeted audits for `domain_primitive_field_forbidden` on the branch delta before re-enabling it in root config.

### WS-02 Repo-Wide Debt Ledger
- [ ] ⚪ Maintain the repo-wide warning breakdown and cluster findings by domain/repository/presentation families.
- [ ] ⚪ Sequence follow-up slices for high-value burn-down areas (`tenant_admin`, `map`, `partners`, `shared presentation`).
- [ ] ⚪ Promote stable architecture decisions into canonical docs as debt is reduced.

### WS-03 Active Repo-Wide Findings Closure
- [ ] ⚪ Split remaining multi-public-class files into dedicated files and update imports/exports/call-sites.
- [ ] ⚪ Move remaining domain `fromJson/fromMap` factories out of `lib/domain/**`.
- [ ] ⚪ Move remaining repository JSON parsing/hydration into DAO/mapper boundaries.
- [ ] ⚪ Remove remaining inline DTO-to-domain mapper methods from repositories.
- [ ] ⚪ Re-run `fvm dart run custom_lint` until the active repo-wide baseline is zero with `domain_primitive_field_forbidden` still disabled.

### WS-04 Calibration Discipline
- [x] ✅ Production-Ready Reject path-based exclusions for `domain_primitive_field_forbidden`.
- [ ] ⚪ Audit any suspected false positives found during branch cleanup.
- [ ] ⚪ Narrow rules only when objective evidence shows overreach.
- [ ] ⚪ Keep docs/fixtures synchronized with every accepted calibration.

## Definition Of Done
- This TODO exists as the canonical repo-wide backlog for Flutter architectural debt burn-down.
- The active `fvm dart run custom_lint` baseline is zero for all enabled rules in this branch.
- `domain_primitive_field_forbidden` remains disabled only by root config, with explicit re-enablement still tracked here as follow-up debt.
- No path-based exemption remains in the `domain_primitive_field_forbidden` rule.
- Validation evidence is recorded for the branch cleanup lane and for the updated backlog snapshot.

## Validation Steps
- `fvm dart run custom_lint`
- `fvm flutter test --reporter expanded --concurrency=1`
- Full device integration rerun for the branch using the resilient runner
- Optional targeted `dart test` in `tool/belluga_custom_lint` when rule calibration is required

## Validation Evidence (Current Branch Lane)
- `dart analyze` in `tool/belluga_custom_lint`: clean
- `dart test` in `tool/belluga_custom_lint`: passed
- `fvm dart analyze lib test`: clean
- `fvm dart analyze lib test tool/belluga_custom_lint`: clean
- `fvm dart run custom_lint`: repo-wide baseline still open (`300` warnings), but `0` findings remain in files changed by this branch against `origin/dev`
- `fvm flutter test --reporter expanded --concurrency=1`: `345` tests passed
- Device integration reruns passed:
  - `integration_test/feature_map_event_filter_actions_test.dart`
  - `integration_test/feature_map_filter_catalog_admin_to_public_e2e_test.dart`
