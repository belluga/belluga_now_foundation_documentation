# Title
VNext: Cover Image Crop Ratio 560x512

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant-admin cover image cropping currently uses a 16:9 aspect ratio. The requested crop proportion is `560x512`, i.e. `560 / 512`.

Inspection found the shared cover slot ratio in the tenant-admin image crop and ingestion surfaces, so the default assumption is that this applies to the global `TenantAdminImageSlot.cover` behavior unless planning finds a product reason to split cover slots by entity type.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `cover-crop-560x512`
- **Why this is the right current slice:** this is a bounded visual/media configuration change with focused tests.
- **Direct-to-TODO rationale:** the requested ratio is exact and the relevant shared Flutter surfaces are known.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Feature`, `Flutter-Focused`, `Tenant-Admin`, `Media`, `Promotion-Lane-Pending`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through authorized lane follow-through; local implementation is complete and the current package-wide mimic loop has not reopened this scope.

## Scope
- [x] Change cover crop aspect ratio to `560 / 512`.
- [x] Update image ingestion/crop tests expecting the cover ratio.
- [x] Ensure preview/crop UI remains stable on mobile and web widths.
- [x] Preserve avatar and other image slot ratios.

## Out of Scope
- [ ] New media storage, CDN, or image processing pipeline work.
- [ ] Re-cropping existing uploaded images.
- [ ] Entity-specific cover ratio exceptions unless planning explicitly splits the slot.
- [ ] Public hero layout redesign.

## Dependencies & Sequencing
- [x] `DEP-01` Execute independently from Nested Account/Profile group work unless both touch the same admin form tests.

## Definition of Done
- [x] `TenantAdminImageSlot.cover` or the approved equivalent cover slot uses `560 / 512`.
- [x] Cover crop sheet enforces the new ratio.
- [x] Ingestion/crop service records the new ratio.
- [x] Tests cover the new ratio and prove unrelated slots are unchanged.
- [x] Analyzer/local CI-equivalent suite row completed before delivery.

## Validation Steps
- [x] Focused Flutter tests for `tenant_admin_image_crop_sheet`.
- [x] Focused Flutter tests for `tenant_admin_image_ingestion_service`.
- [x] Visual smoke/manual note if crop UI layout is changed beyond constants.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level:** `small`
- **Checkpoint policy:** `consolidated planning review`
- **Why this level:** expected to be a focused shared-ratio constant/test update.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:** `n/a unless cover media contract is documented`
- **Module decision consolidation targets:** `foundation_documentation/modules/tenant_admin_module.md` if the media contract changes.

## Source Inventory Snapshot
- `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet.dart`
- `flutter-app/lib/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service.dart`
- `flutter-app/test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart`
- `flutter-app/test/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service_test.dart`

## Decisions
- [x] `D-CVR-01` The target cover ratio is exactly `560 / 512`.
- [x] `D-CVR-02` Non-cover image slots must not change.
- [x] `D-CVR-03` Apply `560 / 512` to the shared `TenantAdminImageSlot.cover` contract for all current cover consumers. Do not split entity-specific cover ratios in this TODO.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The request applies to the shared cover slot, not only one entity-specific cover. | Shared cover slot is currently where 16:9 is defined; frozen by `D-CVR-03`. | Split or introduce an entity-specific media slot in a future approved slice. | `High` | `Promoted to Decision` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/presentation/tenant_admin/shared/widgets/**`
- `flutter-app/lib/presentation/tenant_admin/shared/utils/**`
- `flutter-app/test/presentation/tenant_admin/shared/**`

### Ordered Steps
1. Add/update focused tests to expect `560 / 512` for cover.
2. Update crop sheet and ingestion service ratio constants.
3. Verify unrelated image slots.
4. Run focused tests and analyzer.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** crop sheet and ingestion service tests.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Admin cover crop ratio changes | Admin visible/media flow | `shared-android-web` | widget | `yes` | focused crop sheet widget test |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app` cover crop focused tests | Cover crop sheet and ingestion service ratio behavior changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 consolidated focused validation rerun after WSL disconnect" --flutter-test test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart --flutter-test test/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service_test.dart --flutter-analyze` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Consolidated wrapper reported `reconcile_flutter_tests` and `reconcile_flutter_analyze` passed. |
| `flutter_rule_matrix` architecture lint | Flutter shared media surfaces participated in the v0.2.0+8 reconciliation set. | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` through the reconcile wrapper. | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Rule matrix stage passed with recorded lint-code coverage. |
| v0.2.0+8 final Atlas-backed reconciliation matrix | This TODO participates in the approved consolidated v0.2.0+8 package and must stay green after web/runtime lanes. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 full CI-equivalent against Atlas-backed dev runtime" ...` | `Promotion-Lane-Pending` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260528_012558_full_ci_equivalent_atlas_runtime.md` | Passed `atlas_runtime_db_target`, `reconcile_laravel_tests`, `reconcile_flutter_tests`, `reconcile_flutter_analyze`, `flutter_rule_matrix`, `flutter_web_build`, `web_navigation_readonly`, and `web_navigation_mutation` where applicable. |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, source inventory snapshot, frozen decisions `D-CVR-01..03`, validation steps, and local CI-equivalent suite matrix.
- **Orchestrator-owned checks:** verify only cover ratio changes and avatar/other image slots remain unchanged.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `CVR` inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; no media storage, CDN, public hero redesign, or entity-specific cover-ratio split is authorized.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This TODO is now approved for tactical implementation. | Approved scope, DoD, validation, and delivery gates. | Code edits outside the TODO boundary. | Worker must update evidence before delivery. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO is executed inside the approved orchestration plan. | Worker-owned implementation and orchestrator-owned reconciliation. | Implementing directly in the orchestrator checkout. | Worker runs in isolated worktree and reports checkpoint evidence. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches Flutter tenant-admin media UI/service code. | Existing Flutter architecture and analyzer-clean state. | Widget/service shortcuts that bypass established ownership. | Worker must run focused tests and analyzer. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The slice requires focused regression tests for media ratio behavior. | Semantic assertions for cover ratio and unchanged slots. | Status-only or post-fix-only tests. | Worker uses fail-first or updated focused tests. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-CVR-01..03` | Shared cover slot ratio changed to `560 / 512`; focused crop sheet and ingestion tests passed; non-cover slots remain covered by regression tests. | passed | No entity-specific cover split, CDN/media storage change, or public hero redesign was introduced. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Tenant-admin cover image crop configuration | No tenant boundary or authentication mutation path changed. | passed | Manual diff review plus focused Flutter tests. | The slice only changes Flutter ratio constants/test expectations for existing media ingestion/crop paths. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Cover crop UI and ingestion service | Layout stability and absence of new async/concurrent work. | passed | Focused widget/unit tests plus analyzer in consolidated wrapper. | No new network, storage, isolate, or repeated build-time work was introduced. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| v0.2.0+8 local reconciliation package | CI/Copilot failure modes: focused Flutter failures, analyzer regressions, stale crop ratio expectations, and unrelated slot regressions. | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | no p1 or p2 findings | Local wrapper finished `promotion-ready`; remote PR/Copilot checks remain part of later promotion lane execution. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter architecture and media UI discipline | Scoped v0.2.0+8 Flutter scan for presentation-owned persistence, DTO/domain boundary bypasses, imperative navigation, and build-side-effect patterns. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-flutter.json` | no p1 or p2 findings | Scanner findings were warning/review-level and triaged as fixture/infrastructure-path noise or modal-close affordances outside this media slice. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Change cover crop aspect ratio to `560 / 512`. | focused Flutter widget/unit tests | `tenant_admin_image_crop_sheet_test.dart`; `tenant_admin_image_ingestion_service_test.dart`; `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Flutter widget/unit tests | passed | Shared cover slot ratio is asserted as `560 / 512`. |
| SCOPE-02 | Scope | Update image ingestion/crop tests expecting the cover ratio. | focused Flutter tests | `tenant_admin_image_ingestion_service_test.dart`; `tenant_admin_image_crop_sheet_test.dart` through the reconcile wrapper | Flutter widget/unit tests | passed | Tests now assert the updated cover ratio in both UI and ingestion paths. |
| SCOPE-03 | Scope | Ensure preview/crop UI remains stable on mobile and web widths. | widget/navigation test and manual diff review | `tenant_admin_image_crop_sheet_test.dart`; local diff review | Flutter widget/navigation test plus mobile/web responsive layout review | passed | Ratio update uses the existing crop sheet layout contract; web and mobile preview constraints remain on the same layout path. |
| SCOPE-04 | Scope | Preserve avatar and other image slot ratios. | focused regression tests | `tenant_admin_image_ingestion_service_test.dart`; `tenant_admin_image_crop_sheet_test.dart` | Flutter widget/unit tests | passed | Non-cover image slots remain covered as unchanged. |
| DOD-01 | Definition of Done | `TenantAdminImageSlot.cover` or the approved equivalent cover slot uses `560 / 512`. | code and test assertion | `TenantAdminImageSlot.cover`; focused crop/ingestion tests | Flutter domain/service tests | passed | The approved shared cover slot contract uses the requested ratio. |
| DOD-02 | Definition of Done | Cover crop sheet enforces the new ratio. | widget/navigation test | `tenant_admin_image_crop_sheet_test.dart` | Flutter widget/navigation test | passed | Crop sheet cover path enforces `560 / 512` in the admin crop sheet flow. |
| DOD-03 | Definition of Done | Ingestion/crop service records the new ratio. | unit test | `tenant_admin_image_ingestion_service_test.dart` | Flutter unit/service test | passed | Ingestion metadata records the cover ratio. |
| DOD-04 | Definition of Done | Tests cover the new ratio and prove unrelated slots are unchanged. | focused regression tests | `tenant_admin_image_crop_sheet_test.dart`; `tenant_admin_image_ingestion_service_test.dart` | Flutter widget/unit tests | passed | Cover changed; avatar and other slots remain unchanged. |
| DOD-05 | Definition of Done | Analyzer/local CI-equivalent suite row completed before delivery. | local CI-equivalent | Local CI-Equivalent Suite Matrix rows above | Flutter analyzer/test wrapper | passed | Consolidated wrapper passed Flutter tests, analyzer, and rule matrix. |
| VAL-01 | Validation Steps | Focused Flutter tests for `tenant_admin_image_crop_sheet`. | focused Flutter widget/navigation test | `test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart` through the reconcile wrapper | Flutter widget/navigation test | passed | Covered cover ratio enforcement in the admin crop sheet flow. |
| VAL-02 | Validation Steps | Focused Flutter tests for `tenant_admin_image_ingestion_service`. | focused Flutter unit test plus widget/navigation coverage | `test/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service_test.dart`; crop sheet widget/navigation test through the reconcile wrapper | Flutter unit/service test plus widget/navigation test | passed | Covered ingestion ratio recording and unchanged slots, with crop sheet navigation coverage for the visible flow. |
| VAL-03 | Validation Steps | Visual smoke/manual note if crop UI layout is changed beyond constants. | manual diff review plus widget/navigation test | Local diff review; `tenant_admin_image_crop_sheet_test.dart` | Flutter widget/navigation test and responsive review | passed | The UI path remained within the existing crop sheet layout, with no extra responsive layout change beyond the ratio update. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** local implementation and validation are complete, and the current package-wide mimic loop kept this TODO clean with no reopened findings; only authorized lane follow-through remains.
- **Post-commit/push status:** `ready-for-promotion-lane`
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and carry it through the current v0.2.0+8 package promotion.
