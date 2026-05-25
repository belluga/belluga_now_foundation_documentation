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
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Feature`, `Flutter-Focused`, `Tenant-Admin`, `Media`
- **Next exact step:** add fail-first tests for the frozen shared cover-slot ratio, then request `APROVADO`.

## Scope
- [ ] Change cover crop aspect ratio to `560 / 512`.
- [ ] Update image ingestion/crop tests expecting the cover ratio.
- [ ] Ensure preview/crop UI remains stable on mobile and web widths.
- [ ] Preserve avatar and other image slot ratios.

## Out of Scope
- [ ] New media storage, CDN, or image processing pipeline work.
- [ ] Re-cropping existing uploaded images.
- [ ] Entity-specific cover ratio exceptions unless planning explicitly splits the slot.
- [ ] Public hero layout redesign.

## Dependencies & Sequencing
- [ ] `DEP-01` Execute independently from Nested Account/Profile group work unless both touch the same admin form tests.

## Definition of Done
- [ ] `TenantAdminImageSlot.cover` or the approved equivalent cover slot uses `560 / 512`.
- [ ] Cover crop sheet enforces the new ratio.
- [ ] Ingestion/crop service records the new ratio.
- [ ] Tests cover the new ratio and prove unrelated slots are unchanged.
- [ ] Analyzer/local CI-equivalent suite row completed before delivery.

## Validation Steps
- [ ] Focused Flutter tests for `tenant_admin_image_crop_sheet`.
- [ ] Focused Flutter tests for `tenant_admin_image_ingestion_service`.
- [ ] Visual smoke/manual note if crop UI layout is changed beyond constants.

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
| Repo | CI Surface | Local Command | Required Before Delivery |
| --- | --- | --- | --- |
| `flutter-app` | analyzer + focused tests | `fvm dart analyze --format machine` and focused `fvm flutter test ...` | `yes` |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, source inventory snapshot, frozen decisions `D-CVR-01..03`, validation steps, and local CI-equivalent suite matrix.
- **Orchestrator-owned checks:** verify only cover ratio changes and avatar/other image slots remain unchanged.

## Completion Evidence Matrix
| Criterion | Evidence | Status | Notes |
| --- | --- | --- | --- |
| DoD + validation rows | `pending` | `planned` | Fill before any delivery claim. |
