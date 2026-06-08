# TODO (v0.2.1+9): Event Programming Structured Time and Optional Sequenced Items

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
User product direction on 2026-06-07 identified a contract gap in tenant-public and tenant-admin `Programação`:

1. The current programação time is treated too much like a plain text field instead of a real structured local-time input/output.
2. Not every programação item has a fixed scheduled time. Some items happen immediately after the previous item and should remain valid even without an explicit hour.
3. The current custom programação copy is still modeled as plain `title` text or linked-profile fallback, but the requested product direction is to use the project's canonical HTML/rich-text stack instead of a text-only title field.

Current repo evidence shows that the existing contract is stricter than the requested product behavior:

- `foundation_documentation/modules/events_module.md` currently freezes `programming_items.time` plus optional same-day `end_time`.
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php` currently requires `time` in `HH:mm` and sorts all programação items by `time`.
- `flutter-app/lib/infrastructure/dal/dto/schedule/event_dto.dart` currently drops programação items whose `time` is empty.
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_programming_section.dart` currently renders the left-side time block from `item.time` as the primary visual anchor for every card.
- `flutter-app/lib/presentation/tenant_admin/events/widgets/tenant_admin_event_occurrence_editor_sheet.dart` currently exposes programação `title` as a plain `TextFormField`.
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_programming_section.dart` currently treats explicit programação copy as plain text, not canonical rendered HTML.
- `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventWriteRules.php` and `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php` currently validate/normalize `programming_items.*.title` as plain trimmed string data, not sanitized rich content.
- The current public visual model still assumes every item has a time chip/block; that becomes awkward or misleading when the item is only sequential.
- The current admin ordering model is still effectively time-derived; once untimed items become valid, ordering can no longer depend only on `HH:mm`.
- The project already has a canonical rich-text stack for safe HTML authoring/rendering:
  - `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`
  - `flutter-app/lib/application/rich_text/safe_rich_html.dart`
  - `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_info_section.dart`
  - `laravel-app/app/Integration/Events/EventContentSanitizerAdapter.php` via shared `SafeRichTextHtmlSanitizer`

This TODO exists to establish the v0.2.1+9 canonical behavior so programação can represent all of:

- items with explicit scheduled time, and
- items that are only sequentially positioned after the previous item.
- items whose explicit custom copy is authored/rendered as canonical rich HTML instead of plain text-only title.

Stitch exploration was used to compare public/admin UI directions in isolated project `Belluga v0.2.1+9 Programacao Timing Study` (`projects/13322959707369643951`). On 2026-06-07 the user approved:

- tenant-public `Timeline Reforçada` as the canonical public direction, with no pill for untimed items;
- tenant-admin `Âncora por Sequência + Metadados e Chips` as the canonical admin direction.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-1-plus9-event-programming-structured-time-and-sequenced-items`
- **Why this is the right current slice:** this is one bounded event-programming contract slice spanning write validation, read ordering, admin authoring, and tenant-public rendering for the existing occurrence-owned `Programação` model.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the request is already concrete, user-visible, and tied to an existing cross-stack contract that is currently stricter than the desired product behavior.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `UX`, `Contract-Change`, `Flutter`, `Laravel`, `Tenant-Public`, `Tenant-Admin`, `Events`, `User-Visible`
- **Next exact step:** implement the approved public/admin programação contract and freeze the corresponding Laravel + Flutter validation evidence.

## Scope
- [ ] Freeze the programação time contract so explicit scheduled items use structured local time semantics instead of a loose text-only interpretation.
- [ ] Allow programação items without explicit `time` when they are intentionally sequenced after the previous item in the same occurrence.
- [ ] Preserve optional same-day `end_time` only for items that do have an explicit `time`.
- [ ] Introduce deterministic mixed ordering semantics for timed and untimed programação items so untimed items remain after their intended predecessor instead of being dropped or globally re-sorted.
- [ ] Preserve occurrence-owned `Programação`; this TODO does not move ownership away from the selected occurrence/date.
- [ ] Replace plain-text-only programação item custom copy with canonical rich-text/HTML content using the existing shared Belluga editor/render/sanitization stack instead of a bespoke programação-only implementation.
- [ ] Update tenant-admin authoring so the operator can clearly create:
  - timed items, and
  - sequenced items without explicit hour.
- [ ] Update tenant-admin programação authoring so the current `title` text field becomes canonical rich-content authoring, while preserving valid linked-profile-only items when no explicit custom content is needed.
- [ ] Define and implement the tenant-admin ordering strategy for programação items now that order can no longer derive only from `time`.
- [ ] Update tenant-public rendering so timed items show real time formatting, while untimed sequential items render without inventing a fake clock label.
- [ ] Update tenant-public programação item rendering so explicit item copy uses canonical sanitized HTML rendering and supports multi-line/block rich text without flattening back to plain title text.
- [ ] Replace the current public fixed time block with the approved `Timeline Reforçada` layout:
  - a thin vertical rail with one point per programação item;
  - timed items exposing `HH:mm` as strong inline metadata near the rail;
  - untimed sequential items exposing short textual sequencing metadata such as `Logo após`, but never as a pill/chip.
- [ ] Implement the approved tenant-admin ordering model:
  - explicit sequence number as the primary order anchor;
  - drag handle for reorder interaction;
  - item type chip (`Horário fixo` or `Sequencial`);
  - explicit time treated as metadata rather than the main visual anchor of the row.
- [ ] Preserve linked profile and optional location behavior already approved for programação items.
- [ ] Preserve the current semantic rule that linked-profile fallback stays valid when exactly one profile is linked and no explicit custom content is authored; multi-profile items still require explicit custom content, but that content is now canonical rich HTML rather than plain title text.
- [ ] Add focused backend, DTO, repository, controller, widget, and final runtime evidence for mixed timed/untimed programação behavior plus canonical rich-content programação items.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<pending>`, `laravel-app:<pending>`, `foundation_documentation:<current>`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `code repos: dev; foundation_documentation: main`
- **Production-ready threshold for this TODO:** `stage or main as applicable to touched code repos`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `laravel programação write/read contract` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `flutter programação DTO/admin/public rendering` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `foundation documentation / TODO evidence` | `<current>` | `n/a` | `n/a` | `<pending>` | `drafted` |

## Out of Scope
- [ ] Cross-day programação semantics.
- [ ] Automatic duration inference for items without explicit `time`.
- [ ] Replacing the occurrence-owned programação model with another scheduling architecture.
- [ ] Redesigning unrelated event detail tabs or Home/agenda cards outside the exact programação contract needed here.
- [ ] Broad event CMS/editor redesign beyond the minimal authoring changes required to support timed versus untimed sequenced items.
- [ ] A new bespoke HTML editor or renderer just for programação items.
- [ ] Arbitrary/raw HTML or richer markup outside the already approved canonical safe subset.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** programação payload shape adjustments, stable sequence semantics, tenant-admin occurrence editor updates, tenant-public programação card updates, and the focused tests/runtime evidence needed to prove the contract.
- **Must update or split the TODO:** broader event scheduling model redesign, cross-day session logic, per-item timezone semantics, or a generic workflow/agenda builder beyond current programação ownership.

## Definition of Done
- [ ] `DOD-01` Timed programação items use structured local time semantics and no longer depend on loose text interpretation.
- [ ] `DOD-02` A programação item may exist without explicit `time` when it is intentionally sequenced after the previous item.
- [ ] `DOD-03` Items without explicit `time` are preserved through write -> read -> render and are not dropped by backend normalization or Flutter DTO parsing.
- [ ] `DOD-04` Mixed timed/untimed programação ordering is deterministic and preserves intended sequence within the selected occurrence.
- [ ] `DOD-05` `end_time` remains optional, same-day, and valid only when an explicit `time` exists; when present it must remain later than `time`.
- [ ] `DOD-06` Tenant-admin authoring makes it clear whether an item has an explicit hour or only follows the previous item.
- [ ] `DOD-07` Tenant-admin ordering is no longer implicitly derived only from `time`; the approved sequence-number + drag-handle interaction remains stable after save/reopen/edit.
- [ ] `DOD-08` Tenant-public programação renders timed items with real time treatment and untimed items without fabricated hour labels.
- [ ] `DOD-09` The current public hour chip/block is replaced by the approved `Timeline Reforçada` layout with thin rail + point per item.
- [ ] `DOD-10` Untimed sequential items never render as pill/chip time surrogates; their sequencing text is integrated into the timeline row itself.
- [ ] `DOD-11` Tenant-admin rows expose sequence number, drag handle, and type chip (`Horário fixo` or `Sequencial`) as the approved ordering/meaning anchors.
- [ ] `DOD-12` Explicit programação item custom copy no longer depends on a plain-text-only title field; it uses the approved canonical safe rich-text/HTML subset already used by shared Belluga content surfaces.
- [ ] `DOD-13` Tenant-admin programação item editing reuses the canonical HTML editor widget/flow instead of a text-only field or a new programação-only editor.
- [ ] `DOD-14` Tenant-public programação rendering uses the canonical sanitized HTML path so approved formatting survives save -> read -> render without leaking unsupported/raw tags.
- [ ] `DOD-15` Existing linked profiles, single-profile fallback, multi-profile explicit-content requirement, and optional location semantics remain stable after the scheduling/content change.
- [ ] `DOD-16` Focused Laravel + Flutter automated coverage and final runtime evidence prove timed, untimed, and rich-content programação behavior.

## Validation Steps
- [ ] Add fail-first Laravel coverage proving the current required-time contract is too strict for the new mixed timed/untimed model.
- [ ] Add/update Laravel event CRUD/read tests proving untimed sequenced items persist and read back in the intended order.
- [ ] Add/update Laravel validation/sanitization tests proving programação item explicit custom content accepts only the approved safe subset and is persisted in canonical sanitized form.
- [ ] Add/update Flutter DTO/domain tests proving untimed programação items are no longer discarded.
- [ ] Add/update tenant-admin widget/controller tests proving authoring of timed and untimed items is explicit, the canonical rich-text editor is used for custom item content, and item order does not depend only on clock time.
- [ ] Add/update tenant-public programação widget tests proving untimed items do not show fake hours, do not render as pills/chips, rich item content renders through the canonical HTML path, and the `Timeline Reforçada` layout stays legible for mixed items.
- [ ] Run focused Laravel event tests.
- [ ] Run focused Flutter event/programação tests and analyzer.
- [ ] Run final runtime evidence for tenant-public event detail and the tenant-admin occurrence editor after the updated bundle/runtime target is published.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` Timed programação items use structured local time semantics and no longer depend on loose text interpretation. | `test+review` | `<planned Laravel + Flutter contract tests>` | `backend + local Flutter` | `planned` | Covers both write and read semantics. |
| `DOD-02` | `Definition of Done` | `DOD-02` A programação item may exist without explicit `time` when it is intentionally sequenced after the previous item. | `test` | `<planned Laravel CRUD + admin/widget tests>` | `backend + local Flutter` | `planned` | This is the new product capability. |
| `DOD-03` | `Definition of Done` | `DOD-03` Items without explicit `time` are preserved through write -> read -> render and are not dropped by backend normalization or Flutter DTO parsing. | `test` | `<planned Laravel readback + Flutter DTO tests>` | `backend + local Flutter` | `planned` | Must guard both normalization boundaries. |
| `DOD-04` | `Definition of Done` | `DOD-04` Mixed timed/untimed programação ordering is deterministic and preserves intended sequence within the selected occurrence. | `test+runtime` | `<planned Laravel ordering test + final runtime proof>` | `backend + browser/device` | `planned` | Prevents untimed items from drifting on save or render. |
| `DOD-05` | `Definition of Done` | `DOD-05` `end_time` remains optional, same-day, and valid only when an explicit `time` exists. | `test` | `<planned Laravel validation tests>` | `backend` | `planned` | Must retain the safe part of the current rule. |
| `DOD-06` | `Definition of Done` | `DOD-06` Tenant-admin authoring makes it clear whether an item has an explicit hour or only follows the previous item. | `widget+runtime` | `<planned admin widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | UX clarity is part of the contract. |
| `DOD-07` | `Definition of Done` | `DOD-07` Tenant-admin ordering is no longer implicitly derived only from `time`; the approved sequence-number + drag-handle interaction remains stable after save/reopen/edit. | `widget+runtime` | `<planned admin reorder tests + runtime lane>` | `local Flutter + browser/device` | `planned` | The operator must control stable sequence even for untimed items. |
| `DOD-08` | `Definition of Done` | `DOD-08` Tenant-public programação renders timed items with real time treatment and untimed items without fabricated hour labels. | `widget+runtime` | `<planned event programming widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | UI must not fake time data. |
| `DOD-09` | `Definition of Done` | `DOD-09` The current public hour chip/block is replaced by the approved `Timeline Reforçada` layout with thin rail + point per item. | `widget+runtime` | `<planned layout/widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | Empty or misleading time-chip treatment is not acceptable. |
| `DOD-10` | `Definition of Done` | `DOD-10` Untimed sequential items never render as pill/chip time surrogates; their sequencing text is integrated into the timeline row itself. | `widget+runtime` | `<planned untimed-row widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | Explicit user-approved visual rule. |
| `DOD-11` | `Definition of Done` | `DOD-11` Tenant-admin rows expose sequence number, drag handle, and type chip (`Horário fixo` or `Sequencial`) as the approved ordering/meaning anchors. | `widget+runtime` | `<planned admin row widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | Prevents drift back to time-only ordering UI. |
| `DOD-12` | `Definition of Done` | `DOD-12` Explicit programação item custom copy no longer depends on a plain-text-only title field; it uses the approved canonical safe rich-text/HTML subset already used by shared Belluga content surfaces. | `test+review` | `<planned Laravel sanitization tests + Flutter DTO/render review>` | `backend + local Flutter` | `planned` | Freezes the content contract without allowing arbitrary HTML. |
| `DOD-13` | `Definition of Done` | `DOD-13` Tenant-admin programação item editing reuses the canonical HTML editor widget/flow instead of a text-only field or a new programação-only editor. | `widget+runtime` | `<planned admin widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | Prevents divergence from the shared editor UX. |
| `DOD-14` | `Definition of Done` | `DOD-14` Tenant-public programação rendering uses the canonical sanitized HTML path so approved formatting survives save -> read -> render without leaking unsupported/raw tags. | `widget+runtime` | `<planned public rich-content widget tests + runtime lane>` | `local Flutter + browser/device` | `planned` | Covers visible fidelity for item-level rich content. |
| `DOD-15` | `Definition of Done` | `DOD-15` Existing linked profiles, single-profile fallback, multi-profile explicit-content requirement, and optional location semantics remain stable after the scheduling/content change. | `test` | `<planned regression tests>` | `backend + local Flutter` | `planned` | Avoids regressions while title semantics become rich content. |
| `DOD-16` | `Definition of Done` | `DOD-16` Focused Laravel + Flutter automated coverage and final runtime evidence prove timed, untimed, and rich-content programação behavior. | `test+runtime` | `<planned CI-equivalent suites + runtime evidence>` | `backend + Flutter + browser/device` | `planned` | Final acceptance requires scheduling plus rich-content evidence. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto-tech-lead` | `operational-coder` | This session opened the governing TODO and captured the user-approved public/admin direction for implementation. | `foundation_documentation/todos/active/v0.2.1+9/**` -> code repos | `approved` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the product surface is bounded to programação, but the required contract change crosses Laravel validation/normalization, Flutter DTO parsing, tenant-admin authoring, and tenant-public rendering.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Planned decision promotion targets (module sections):**
  - `events_module.md` sections `4 Canonical Decisions` and `5 Contract Summary for Clients`
  - `flutter_client_experience_module.md` event-detail programming surface notes if rendering semantics need explicit promotion
  - `tenant_admin_module.md` event authoring notes if the admin programação editor contract changes
- **Module decision consolidation targets (required):**
  - `events_module.md` `EVS-PROG-01`
  - `events_module.md` `EVS-OCC-01`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Explicit programação hour is a structured local-time concept, not arbitrary descriptive text.
- [x] `D-02` A programação item may omit explicit `time` when its meaning is “happens after the previous item” within the same occurrence.
- [x] `D-03` `end_time` stays optional and same-day, but it only applies to items that do have explicit `time`.
- [x] `D-04` Untimed sequential items must not be silently dropped, normalized away, or rendered with fake clock values.
- [x] `D-05` Mixed timed and untimed item order must be preserved by explicit sequence semantics, not by incidental array position or a sort that only understands clock time.
- [x] `D-06` Tenant-public adopts the approved `Timeline Reforçada` direction: thin vertical rail, one point per item, and `HH:mm` as inline time metadata instead of a universal left time block.
- [x] `D-07` Untimed sequential items in tenant-public may show short sequencing text such as `Logo após`, but never as a pill/chip or fake time surrogate.
- [x] `D-08` Tenant-admin ordering can no longer depend only on `time`; the approved interaction is explicit sequence number + drag handle + type chip, with time treated as metadata.
- [x] `D-09` Occurrence-owned programação remains the canonical ownership boundary for this change.
- [x] `D-10` Explicit programação item custom copy must use the canonical safe rich-text/HTML subset and the existing shared editor/render stack rather than a plain-text-only title field.
- [x] `D-11` Single-profile fallback remains valid when no explicit custom content is authored, but any required explicit custom copy for multi-profile items is rich content rather than plain text.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `events_module.md#EVS-PROG-01` | Programming items support optional same-day `end_time` in `HH:mm`; when present it must be later than `time` and is rendered as `time às end_time`. | `Supersede (Intentional)` | v0.2.1+9 keeps the same `end_time` rule when `time` exists, but broadens the contract so `time` is no longer mandatory for every item. |
- | `events_module.md#5.2-write-model-programming-item-title` | A programming item with more than one linked Account Profile must provide an explicit `title`; a single linked profile may omit `title` and use profile display name as the public fallback. | `Supersede (Intentional)` | v0.2.1+9 preserves the same fallback/requirement semantics but promotes the explicit custom field from plain text title to canonical rich content. |
- | `events_module.md#EVS-OCC-01` | `Programação` is occurrence-first / occurrence-owned. | `Preserve` | This slice changes scheduling semantics, not occurrence ownership. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The current required-`time` rule is intentionally too strict for the requested product behavior and must be changed, not merely documented.
- [x] `D-02` Timed and untimed programação items are both first-class valid states in v0.2.1+9.
- [x] `D-03` Public UI must not invent a visible hour for data that has no explicit scheduled time.
- [x] `D-04` Stable sequence ordering is part of the data contract, not a widget-only concern.
- [x] `D-05` The public programação layout must explicitly support mixed timed/untimed cards through the approved `Timeline Reforçada` pattern, not through a universal hour chip and not through untimed pills.
- [x] `D-06` The admin programação layout must use explicit sequence as its primary ordering anchor, with drag-and-drop as the reorder mechanism and type chips as the meaning cue.
- [x] `D-07` The custom operator-authored programação copy must stop behaving as plain text and must instead use the existing canonical safe rich-text HTML contract.
- [x] `D-08` No bespoke programação-only editor or renderer is allowed; admin and public must reuse the shared canonical HTML stack already used by Belluga rich-content surfaces.
- [x] `D-09` Existing linked-profile/location fallback rules remain in force, but any required explicit custom copy for multi-profile items is rich content rather than plain text.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current product pain is not only visual; the backend and DTO layers genuinely forbid or discard untimed programação items today. | `EventManagementService` requires `time`; `event_dto.dart` drops items with empty `time`. | The change might be render-only, which current evidence does not support. | `High` | `Keep as Assumption` |
| `A-02` | A deterministic sequence field or equivalent stable ordering mechanism will be required because sorting only by `time` cannot preserve untimed items after their intended predecessor. | Current backend normalizes and sorts by `time` only. | Implementation would risk unstable order on save/read if sequence ownership is not added/frozen. | `High` | `Keep as Assumption` |
| `A-03` | The exact inline wording for untimed public sequencing may stay close to `Logo após`, but minor copy refinement is still allowed as long as it remains inline text on the timeline row and never becomes a pill/chip. | User approved `Timeline Reforçada` and explicitly rejected a pill treatment. | Copy may need a final micro-adjustment during implementation, but the structural pattern is frozen. | `High` | `Keep as Assumption` |
| `A-04` | The current shared safe HTML subset used by event/content and account-profile rich text is sufficient for programação item custom content in v0.2.1+9. | User explicitly asked to reuse the canonical HTML widget/renderer rather than define a new content model. | If richer markup/embeds are required, this slice must be re-approved because it would expand the editor/sanitizer contract. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventWriteRules.php`
- `laravel-app/app/Integration/Events/EventContentSanitizerAdapter.php`
- `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
- `flutter-app/lib/infrastructure/dal/dto/schedule/event_dto.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_programming_section.dart`
- `flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_event_programming_item_draft.dart`
- `flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_event_occurrence_editor_draft.dart`
- `flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_event_form_screen.dart`
- `flutter-app/lib/presentation/tenant_admin/events/widgets/tenant_admin_event_occurrence_editor_sheet.dart`
- `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_rich_text_editor.dart`
- `flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
- `flutter-app/test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
- `flutter-app/test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart`

### Ordered Steps
1. Add fail-first backend coverage that proves the current contract rejects or loses untimed sequenced programação items and still treats explicit item copy as plain text only.
2. Freeze the minimal payload/read-model change needed to represent mixed timed and untimed items with stable sequence plus canonical rich item content.
3. Update Laravel validation/normalization/readback so untimed items persist safely and deterministically and explicit item content is sanitized through the approved safe HTML subset.
4. Implement the approved tenant-public `Timeline Reforçada` layout so mixed timed/untimed items render through the vertical rail pattern and rich item content uses the canonical HTML render path without pills or fake time blocks.
5. Update Flutter DTO/domain/public render paths so untimed items are preserved and rich item content is not flattened back to plain text.
6. Implement the approved tenant-admin ordering interaction and replace the plain programação title field with the canonical rich-text editor when explicit custom content is authored.
7. Add focused regression coverage for timed/untimed persistence, ordering, rich content sanitization, and rendering.
8. Run focused Laravel + Flutter validation suites, then collect final runtime evidence on the required browser/device lane.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** this is a user-visible contract change that crosses persistence, normalization, DTO parsing, and UI behavior; fail-first evidence is needed to avoid accidental partial fixes.
- **Fail-first target(s) (when required):**
  - Laravel event CRUD/validation coverage for untimed sequenced items.
  - Flutter DTO/widget coverage proving untimed items are no longer discarded and are rendered without invented times.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Tenant-admin programação authoring | `CRUD/mutation` | `shared-android-web` | `ADB integration or Playwright readonly after publish` | `yes` | `yes` | Admin widget tests + runtime edit/save proof | Authoring semantics change. |
| Tenant-admin programação ordering | `CRUD/mutation` | `shared-android-web` | `ADB integration or Playwright readonly after publish` | `yes` | `yes` | Admin reorder tests + runtime save/reopen proof | Order can no longer be inferred only from hour. |
| Tenant-public programação rendering | `visible UI` | `shared-android-web` | `ADB integration or Playwright readonly` | `no` | `yes` | Event-detail widget tests + final runtime proof | Must show timed vs untimed items correctly with adaptive layout and canonical rich item content rendering. |
| Laravel programação persistence/order | `payload consumed by UI` | `n/a` | `n/a` | `no` | `yes` | CRUD/readback + sanitization feature tests | Backend owns both stable order and safe item-content normalization. |
| Flutter DTO parse boundary | `field/DTO/domain refactor` | `n/a` | `n/a` | `no` | `yes` | Focused decoder/domain tests | Guards against silent untimed-item loss and plain-text/rich-content regression. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` event CRUD/validation | backend write/read contract changes | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php` | `Local-Implemented` | `planned` | `<pending>` | Exact focused shards may be narrowed during implementation. |
| `flutter-app` programação DTO/public/admin | DTO + admin + public rendering changes | `cd flutter-app && fvm flutter test <focused programação suites> && fvm dart analyze --format machine` | `Local-Implemented` | `planned` | `<pending>` | Exact suite list to be frozen before execution. |
