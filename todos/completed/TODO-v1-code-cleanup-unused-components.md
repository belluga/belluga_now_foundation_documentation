# TODO (V1): Architecture Guardrails + Unused Code Cleanup

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** ✅ Production-Ready (`Implementation complete locally; warning baseline recorded`)  
**Owner:** Delphi  
**Date:** 2026-03-10
**Complexity:** `big`

## Objective
Establish objective custom lint guardrails for the Domain/DTO/ValueObject boundaries and file-structure rules, while removing dead code and stale artifacts that are proven unused by the same audit loop.

## References
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/submodule_flutter-app_summary.md`
- `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md`
- `flutter-app/tool/belluga_custom_lint/docs/rules.md`

## Canonical Module Anchors
- **Primary module:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary modules/contracts:**
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- **Promotion targets after delivery:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `flutter-app/tool/belluga_custom_lint/docs/rules.md`
- **Decision consolidation targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1 Domain Rules`, `2.1.1 Presentation DI Matrix`)
  - `foundation_documentation/domain_entities.md` (`Flutter domain boundary`)

## Scope
- Add new custom lint rules for objective architecture contracts:
  - `domain_json_factory_forbidden`
  - `domain_primitive_field_forbidden`
  - `repository_json_parsing_forbidden`
  - `service_json_parsing_forbidden`
  - `repository_inline_dto_to_domain_mapper_forbidden`
  - `multi_public_class_file_warning`
- Keep `domain_dto_dependency_forbidden` and `multi_widget_file_warning` active and coherent with the new rules.
- Enforce the agreed mapping path contract: DTO hydration in DAO, DTO -> Domain mapping in `lib/infrastructure/dal/dto/mappers/**`, repositories consume DTOs/mappers and return domain/projection models.
- Expand `tool/belluga_custom_lint` docs + fixture matrix to cover the new rules with `Treatments: ...` guidance.
- Merge the existing unused-code cleanup lane into the same execution loop:
  - audit dead widgets/contracts/helpers/files,
  - remove only artifacts proven unused by source, route, and test audit,
  - update tests/docs affected by safe removals.

## Out Of Scope
- Feature delivery in tenant/landlord/admin flows.
- CI severity or branch-policy changes for the new lint rules in this slice.
- Heuristic lint rules that cannot reach zero false positives in this round.
- Cleanup based on guesswork or stylistic preference without reference proof.
- Removing still-referenced schedule summary flow artifacts in this slice.

## Current Baseline Snapshot
- Domain `fromJson/fromMap` factories detected in:
  - `lib/domain/partners/profile_type_definition.dart`
  - `lib/domain/partners/profile_type_capabilities.dart`
  - `lib/domain/theme_data_settings/color_scheme_data.dart`
  - `lib/domain/theme_data_settings/theme_data_settings.dart`
- Repository/service JSON parsing and DTO hydration still happen outside DAO in several places, including:
  - `lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart`
  - `lib/infrastructure/repositories/tenant_admin/tenant_admin_account_profiles_repository.dart`
  - `lib/infrastructure/repositories/tenant_admin/tenant_admin_static_assets_repository.dart`
  - `lib/infrastructure/repositories/tenant_admin/tenant_admin_taxonomies_repository.dart`
  - `lib/infrastructure/services/http/laravel_map_poi_http_service.dart`
- Domain primitive-field drift remains broad (`~58` files from the initial scan).
- Multiple public classes per file baseline (excluding generated files): `~31` files / `~79` extra public classes.
- Existing cleanup TODO candidate `map_debug_screen.dart` is already absent from the repo; cleanup must be re-audited from current code, not historical assumptions.
- Existing cleanup TODO candidate `fetchSummary` is still referenced by active contracts/tests and is not automatically removable in this slice.

## Checkpoint Policy
Section-by-section checkpoints before production-ready mark:
1. TODO + documentation contract freeze
2. Custom lint implementation + fixture coverage
3. Dead-code audit + safe removals
4. Analyzer/tests + decision adherence validation

## Module Decision Baseline Snapshot
- `foundation_documentation/domain_entities.md` defines the Flutter domain boundary: domain entities/value objects must not depend on DTOs or infrastructure types; parsing/mapping lives in infrastructure.
- `foundation_documentation/modules/flutter_client_experience_module.md` defines DTO -> Domain -> Projection flow and controller-owned architecture as the canonical client contract.
- `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md` freezes the no-allowlist (`SEM EXCEÇÃO`) policy for architecture lint calibration and keeps `multi_widget_file_warning` as an active P2 rule.

## Plan Review Gate

### Issue Cards

#### Issue ID: LINT-01
- **Severity:** High
- **Evidence:** `lib/domain/partners/profile_type_definition.dart:16`, `lib/domain/theme_data_settings/theme_data_settings.dart:122`
- **Why now:** Domain-owned JSON factories violate the documented Flutter domain boundary and keep transport parsing inside domain models.
- **Option A:** Keep domain JSON factories and document exceptions.
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high
- **Option B (Recommended):** Ban domain JSON factories via lint and migrate parsing to infrastructure-owned DTO/mapper flow.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Ban only new occurrences and grandfather existing ones.
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high

#### Issue ID: LINT-02
- **Severity:** High
- **Evidence:** `lib/domain/partners/account_profile_model.dart:12`, `lib/domain/tenant_admin/tenant_admin_account_profile.dart:21`, `lib/domain/tenant_admin/tenant_admin_event.dart:23`
- **Why now:** Primitive fields in domain models hide validation/nullability semantics that should live in ValueObjects or domain-owned types.
- **Option A:** Leave primitive fields as-is and rely on review discipline.
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high
- **Option B (Recommended):** Add a zero-false-positive lint for primitive domain fields and burn down the debt iteratively.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Restrict the rule only to newly created domain files.
  - Effort: low
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: medium

#### Issue ID: LINT-03
- **Severity:** High
- **Evidence:** `lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart:563`, `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart:401`, `lib/infrastructure/services/http/laravel_map_poi_http_service.dart:95`
- **Why now:** Repositories/services still parse raw JSON or hydrate DTOs directly, blurring DAO/repository responsibilities and weakening objective contract enforcement.
- **Option A:** Keep repository/service parsing and rely on future refactors.
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high
- **Option B (Recommended):** Add objective lints for repository/service parsing violations and drive migrations toward DAO + mapper boundaries.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Treat the rule as documentation-only for now.
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high

#### Issue ID: LINT-04
- **Severity:** Medium
- **Evidence:** `tool/belluga_custom_lint/lib/src/rules/multi_widget_file_warning_rule.dart:15`, `lib/presentation/tenant_admin/accounts/controllers/tenant_admin_account_create_controller.dart`, `lib/domain/tenant_admin/tenant_admin_settings.dart`
- **Why now:** The existing structural rule only covers multi-widget screen files, while the broader “one public class per file” contract is still unenforced and current debt is measurable.
- **Option A:** Keep only `multi_widget_file_warning`.
  - Effort: none
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: medium
- **Option B (Recommended):** Add `multi_public_class_file_warning` over `lib/**`, excluding generated files, with one warning per extra public class.
  - Effort: medium
  - Risk: low
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Replace the existing multi-widget rule immediately.
  - Effort: low
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: medium

#### Issue ID: CLEAN-01
- **Severity:** Medium
- **Evidence:** `lib/domain/repositories/schedule_repository_contract.dart:8`, `lib/infrastructure/repositories/schedule_repository.dart:152`, `lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart:37`, `tool/belluga_custom_lint/docs/rules.md`
- **Why now:** The original cleanup TODO contains outdated candidates; cleanup must now follow evidence discovered during lint hardening to avoid deleting active contracts while stale artifacts remain elsewhere.
- **Option A:** Keep cleanup separated and postpone it.
  - Effort: low
  - Risk: medium
  - Blast radius: low
  - Maintenance burden: medium
- **Option B (Recommended):** Merge cleanup into the lint hardening lane and remove only artifacts proven dead by `rg`/route/test audit.
  - Effort: medium
  - Risk: low
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Run an aggressive dead-code purge guided only by analyzer hints.
  - Effort: medium
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high

### Failure Modes & Edge Cases
- False positives on generated files or test fixtures make the new rules non-adherent to the `SEM EXCEÇÃO` policy.
- Domain helper/value-object-adjacent files could be misclassified if the rule treats any `List<String>` or `Map<String, dynamic>` as equally invalid without path/type guards.
- Repository JSON parsing rules can accidentally flag outgoing request builders (`FormData.fromMap`) if the matcher is too broad.
- “One public class per file” must not flag private helper classes, generated files, or fixtures intentionally shaped for lint testing.
- Cleanup can accidentally remove active but indirectly referenced contracts if the audit does not include tests and route wiring.

### Uncertainty Register
- **Assumptions:**
  - All new rules must preserve zero-false-positive behavior; if a matcher is noisy, the rule will be narrowed instead of waived.
  - The dedicated mapper path remains `lib/infrastructure/dal/dto/mappers/**`.
- **Unknowns:**
  - How much of the current domain primitive debt can be burned down in this slice versus only surfaced as warnings.
  - Which dead files/contracts beyond the historical cleanup list are safe to delete once the repo-wide audit is complete.
- **Confidence:** `medium`

## Decision Baseline (Frozen)
- `D-01` Implement only lint rules that can be calibrated to zero false positives in this round; no allowlist/per-file suppressions.
- `D-02` Keep `fromPrimitives` allowed in domain, but forbid domain `fromJson/fromMap`.
- `D-03` Restrict raw JSON parsing and DTO hydration to DAO boundaries; repositories/services must not parse/hydrate transport payloads inline.
- `D-04` DTO -> Domain mapping must live in dedicated mapper files under `lib/infrastructure/dal/dto/mappers/**`; repositories may invoke mappers but must not own inline DTO -> Domain conversion logic.
- `D-05` Domain fields must use ValueObjects or domain-owned types instead of primitives; no primitive exemptions in this rule scope.
- `D-06` Add `multi_public_class_file_warning` across `lib/**`, excluding generated files, and emit one warning per extra public class while keeping `multi_widget_file_warning` active.
- `D-07` Merge unused-code cleanup into this execution lane, but remove only artifacts proven unused by source, route, and test audit; do not remove still-referenced schedule summary flow in this slice.
- `D-08` Keep new rule severity at `warning` for this slice and do not change CI policy.

## Module Coherence Gate (Pre-Implementation)
| Decision ID | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | Aligned | Preserve | `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md` (`SEM EXCEÇÃO`) |
| `D-02` | Aligned | Preserve | `foundation_documentation/domain_entities.md` (`Flutter domain boundary`) |
| `D-03` | Aligned | Preserve | `foundation_documentation/domain_entities.md` (`All DTO parsing/mapping lives in infrastructure`) |
| `D-04` | Aligned | Preserve | `foundation_documentation/submodule_flutter-app_summary.md` (`DTO factories removed from Flutter domain models; mapping centralized in infrastructure DTO mappers`) |
| `D-05` | Aligned | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1 Domain Rules`, value-object backed models) |
| `D-06` | Aligned | Preserve | `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md` (`multi_widget_file_warning` already established as structural hygiene rule) |
| `D-07` | Aligned | Preserve | Existing cleanup TODO objective + current baseline audit in this file |
| `D-08` | Aligned | Preserve | User planning decision for warnings-only rollout |

## Module Decision Consistency Matrix
| Source Decision / Contract | Planned Handling | Evidence |
| --- | --- | --- |
| Domain layer cannot depend on DTO/infrastructure parsing. | Preserve | `foundation_documentation/domain_entities.md` |
| DTO -> Domain -> Projection flow is canonical in Flutter. | Preserve | `foundation_documentation/modules/flutter_client_experience_module.md` |
| Architecture custom lints follow `SEM EXCEÇÃO`; noisy rules must be calibrated, not suppressed. | Preserve | `TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md` |
| `multi_widget_file_warning` remains part of the P2 hygiene surface. | Preserve | `TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md` |
| Old cleanup candidate list is historical, not authoritative. | Supersede (Intentional) | Current baseline snapshot in this TODO |

## Workstreams

### WS-01 Guardrail Definition
- [x] ✅ Production-Ready Freeze rule semantics, scope guards, generated-file exclusions, and `Treatments` guidance.
- [x] ✅ Production-Ready Register new rule IDs in the custom lint plugin and executable docs.

### WS-02 Custom Lint Implementation
- [x] ✅ Production-Ready Implement the new rule classes in `tool/belluga_custom_lint/lib/src/rules/`.
- [x] ✅ Production-Ready Extend helper utilities for path/type detection and generated-file guards.
- [x] ✅ Production-Ready Add fixture coverage for positive/negative cases, including generated-file and private-class exclusions.

### WS-03 Debt Burn-Down + Cleanup Audit
- [x] ✅ Production-Ready Run repo audit for files/contracts flagged by the new rules and classify each as `migrate`, `keep`, or `remove`.
- [x] ✅ Production-Ready Remove only code proven unused and update references/tests accordingly. Result in this slice: no production removals executed because no candidate reached proof threshold from source + route + test audit.
- [x] ✅ Production-Ready Leave still-referenced contracts in place when the rule should drive future migration instead of immediate deletion.

### WS-04 Verification + Consolidation
- [x] ✅ Production-Ready Run `dart analyze` in `tool/belluga_custom_lint`.
- [x] ✅ Production-Ready Run `dart test` in `tool/belluga_custom_lint`.
- [x] ✅ Production-Ready Run `fvm dart run custom_lint` at app root and record the new warning baseline.
- [x] ✅ Production-Ready Promote stable rule/contract outcomes back into canonical docs and submodule summary.

## Definition Of Done
- New rule IDs are implemented, documented, and covered by fixture tests.
- All new diagnostics include adherent `Treatments: ...` guidance.
- Repo-wide run shows only real findings; noisy matchers are recalibrated instead of suppressed.
- Dead-code cleanup removals are evidence-based and do not break active flows.
- Canonical docs/submodule summary are updated with the stable outcomes of this slice.

## Validation Steps
- [x] ✅ Production-Ready `dart analyze` in `tool/belluga_custom_lint` (`No issues found!`)
- [x] ✅ Production-Ready `dart test` in `tool/belluga_custom_lint` (`All tests passed!`)
- [x] ✅ Production-Ready `fvm dart run custom_lint` (root baseline recorded: `457` warnings)
- [x] ✅ Production-Ready Targeted `rg`/route/test audit evidence for every removal candidate

## Approval Gate
No implementation may begin until this TODO is explicitly approved with the token `APROVADO`.

## Execution Outcome
- Implemented new lint rules:
  - `domain_json_factory_forbidden`
  - `domain_primitive_field_forbidden`
  - `repository_json_parsing_forbidden`
  - `service_json_parsing_forbidden`
  - `repository_inline_dto_to_domain_mapper_forbidden`
  - `multi_public_class_file_warning`
- Registered the rules in `tool/belluga_custom_lint`, extended path/type helpers, and expanded the fixture matrix with positive/negative/generated/private-class coverage.
- Updated canonical docs to freeze the executable contract in:
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `flutter-app/tool/belluga_custom_lint/docs/rules.md`
- Cleanup lane result:
  - `map_debug_screen.dart` remains historical only and is already absent from the repo.
  - `fetchSummary` remains referenced in active contracts/tests and was intentionally kept.
  - No other production artifact reached the removal proof threshold in this slice.

## Root Warning Baseline
- `fvm dart run custom_lint` at app root produced `457` warnings, all corresponding to real migration debt rather than plugin/runtime failure.
- Warning counts by rule:
  - `domain_primitive_field_forbidden`: `354`
  - `multi_public_class_file_warning`: `85`
  - `repository_json_parsing_forbidden`: `11`
  - `domain_json_factory_forbidden`: `4`
  - `repository_inline_dto_to_domain_mapper_forbidden`: `2`
  - `service_json_parsing_forbidden`: `1`
- The root run therefore validates both enforcement wiring and the currently known debt profile for follow-up burn-down work.

## Cleanup Audit Evidence
- Safe-removal audit evidence captured from direct references:
  - `lib/infrastructure/services/schedule_backend_contract.dart`
  - `lib/infrastructure/repositories/schedule_repository.dart`
  - `lib/infrastructure/dal/dao/mock_backend/mock_schedule_backend.dart`
  - `lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart`
  - multiple repository/presentation tests referencing `fetchSummary`
- Decision: keep `fetchSummary` and related schedule summary flow in place for this slice.

## Decision Adherence Validation
| Decision ID | Result | Evidence |
| --- | --- | --- |
| `D-01` | Pass | New rules were narrowed with generated/private-path guards; no suppressions/allowlists were introduced. |
| `D-02` | Pass | `domain_json_factory_forbidden` blocks `fromJson/fromMap` only; `fromPrimitives` remains allowed. |
| `D-03` | Pass | Repository/service parsing rules now flag inline JSON/DTO hydration outside DAO boundaries. |
| `D-04` | Pass | `repository_inline_dto_to_domain_mapper_forbidden` enforces mapper delegation instead of inline repository mapping. |
| `D-05` | Pass | `domain_primitive_field_forbidden` now surfaces primitive-domain-field debt as warnings. |
| `D-06` | Pass | `multi_public_class_file_warning` added over `lib/**`; `multi_widget_file_warning` remains active. |
| `D-07` | Pass | Cleanup was merged into the same slice, but only audited candidates with reference proof were considered; no unsafe removals were made. |
| `D-08` | Pass | New rules ship as warnings only; no CI severity/policy change was made here. |

## Module Decision Consistency Validation
| Source Decision / Contract | Result | Evidence |
| --- | --- | --- |
| Domain layer cannot depend on DTO/infrastructure parsing. | Preserved | Domain JSON factory rule + repository/service parsing rules + docs update in `flutter_client_experience_module.md`. |
| DTO -> Domain -> Projection flow is canonical in Flutter. | Preserved | Mapper-location semantics preserved and documented in the rule catalog and module doc. |
| Architecture lints follow `SEM EXCEÇÃO`; noisy rules are calibrated, not suppressed. | Preserved | Fixture calibration performed; generated/private/test-shape false positives removed without allowlists. |
| `multi_widget_file_warning` remains part of the active hygiene surface. | Preserved | Plugin continues registering `multi_widget_file_warning` alongside the new file-level rule. |
| Historical cleanup candidate list is non-authoritative. | Preserved | Cleanup decisions were based on current `rg`/route/test evidence, not the old candidate list. |
