# Title
Store Release: Belluga Media Canonical Image Flow Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Reclassified on:** `2026-04-18`
- **Previous lane:** `foundation_documentation/todos/active/vnext/TODO-vnext-belluga-media-canonical-image-flow-hardening.md`
- **Why this moved into store release:** canonical image ownership is no longer just package hardening. Public URLs, host normalization, cache behavior, OG/branding surfaces, and Flutter/Web-visible image continuity are publication-critical for the Android release lane.

## Context
The branding/OG fallback regression exposed a broader architectural gap: Laravel image flows are not uniformly owned by `belluga_media`. Some paths already use canonical media services (`account profiles`, `static assets`, parts of `event/media`), while other paths still persist direct `Storage::disk('public')->url(...)` values, implement ad hoc public URL normalization, or serve legacy storage paths without a canonical media wrapper. That drift creates host/CORS/cache regressions, inconsistent public URLs, and review-time ambiguity about what the “right” media pipeline is.

This slice exists to harden the rule at the architecture level: image flows are not a branding-only concern. Any Laravel product image flow must use `belluga_media` directly or an explicit host-owned wrapper built on top of it.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** The user explicitly elevated this from a branding fix to a VNext hardening rule that must stay visible across future media work.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** This is already one bounded backlog slice: establish and enforce the canonical Laravel image-flow rule, audit exceptions, and converge guardrails/docs around it.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Release-Critical`, `Cross-Stack`
- **Next exact step:** execute the media-flow inventory and freeze the approved wrapper boundary so remaining non-canonical image paths do not ship into the Android store-release candidate.

## Scope
- [ ] Inventory every current Laravel product image flow and classify it as `belluga_media-compliant`, `wrapper-compliant`, or `non-compliant`.
- [ ] Define the non-negotiable hardening rule that Laravel image upload/store/public-URL/normalize/serve flows must use `belluga_media` or an approved host wrapper built on top of it.
- [ ] Add repository guardrails that block ad hoc image persistence/public URL generation outside the canonical media path.
- [ ] Register and migrate remaining non-compliant flows, including host-owned legacy bridges that currently bypass `belluga_media`.
- [ ] Synchronize foundation docs, skills/workflows, and review vocabulary so the rule is enforceable both by CI and by human/process guidance.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `<pending>`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Canonical image-flow hardening rule + audit + migrations | `<pending>` | `<pending>` | `<pending>` | `n/a` | `Pending` |

## Out of Scope
- [ ] Flutter/web local pick/crop UX changes by themselves.
- [ ] Media-library reuse/product feature expansion.
- [ ] CDN/provider migration or asset optimization policy unrelated to canonical ownership.
- [ ] Non-image binary/public asset flows unless they are explicitly routed through image/media semantics in product contracts.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** Media-flow inventory, guardrail additions, host-wrapper creation, route alias convergence, legacy-URL compatibility bridges, doc/skill alignment, and test coverage required to enforce the rule.
- **Must update or split the TODO:** New product media capabilities, reusable media library UX, or broader storage/provider architecture changes.

## Definition of Done
- [ ] A canonical inventory of Laravel image flows exists with compliant/non-compliant status and owner.
- [ ] A frozen rule states that Laravel image flows must use `belluga_media` directly or via an approved wrapper.
- [ ] CI guardrails exist to catch direct/ad hoc image public URL generation outside approved surfaces.
- [ ] Known non-compliant flows are either migrated in-slice or explicitly registered with owner + follow-up.
- [ ] Foundation docs and Delphi skills/workflows reference the same rule and vocabulary.

## Validation Steps
- [ ] Run Laravel guardrail suite (`composer run lint:strict` and any dedicated architecture guardrail commands added by this slice).
- [ ] Run focused Laravel unit/feature suites covering migrated image flows and legacy compatibility aliases.
- [ ] Re-run affected Flutter/web compatibility checks only where client-visible public URL behavior changes.

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `none required for backlog capture` | This TODO is being created as planning/backlog truth only. | `healthy` | `2026-04-15` | Local repository inspection | `n/a` |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `operational-devops`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Guardrail and migration slice will need explicit regression/test-hardening review during execution. | `laravel-app`, media flows, tests | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `full Plan Review Gate before execution approval + one post-validation checkpoint`
- **Why this level:** The rule is clear, but the eventual execution slice spans multiple media owners and can affect public URL contracts across existing flows.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Module decision consolidation targets (required):**
  - `foundation_documentation/submodule_laravel-app_summary.md#media--public-asset-ownership`

## Decision Pending (Resolve Before Freeze)
- [ ] `D-IMG-01` Decide the exact guardrail boundary for “approved wrapper”: whether every host wrapper must delegate to `belluga_media` through a shared primitive/service contract, or whether narrowly-scoped transitional wrappers can remain while migrations are in progress.

## Decisions (Resolved Before Freeze)
- [x] `D-IMG-00` All Laravel product image flows must use `belluga_media` directly or an explicit host-owned wrapper built on top of it. Direct `Storage::disk('public')->url(...)`, ad hoc public URL normalization, or image-specific controller/trait persistence outside that path are no longer acceptable as canonical architecture. Module ref: `No Prior Decision` (user directive captured on 2026-04-15).

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `No Prior Decision` | No explicit cross-cutting media hardening rule currently states that all Laravel image flows must route through `belluga_media`. | `Supersede (Intentional)` | Repo audit and branding/OG regression analysis on `2026-04-15`; `laravel-app/packages/belluga/belluga_media/README.md`; current host/media service inventory |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-IMG-00` Laravel image flows must not own persistence/public URL logic outside `belluga_media` or an approved wrapper over it.
- [ ] `D-IMG-01` Guardrails must distinguish approved wrappers from ad hoc bypasses in a deterministic way.
- [ ] `D-IMG-02` Public legacy aliases may remain only as compatibility façades over canonical media ownership, never as separate source-of-truth flows.

## Questions To Close
- [ ] Which current Laravel image flows are still non-compliant after the latest branding/public-web fix?
- [ ] Which of those flows can migrate in one bounded slice without mixing new product behavior into the hardening work?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `belluga_media` is already the intended canonical authority for reusable Laravel image persistence + canonical public URL generation/normalization. | `laravel-app/packages/belluga/belluga_media/README.md`; existing `AccountProfileMediaService`, `StaticAssetMediaService`, and related media wrappers | The rule would need a deeper architectural reframing, not just hardening/migration work. | `High` | `Keep as Assumption` |
| `A-02` | There are still additional non-compliant image flows beyond the branding/public-web fallback path. | Repository history from the branding/OG regression; presence of host-owned custom image/storage helpers in current codebase; broad user directive on 2026-04-15 | The TODO may collapse to a smaller documentation/guardrail slice after inventory. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `laravel-app/packages/belluga/belluga_media/**`
- `laravel-app/app/Application/**`
- `laravel-app/app/Http/**`
- `laravel-app/scripts/architecture_guardrails.php`
- `foundation_documentation/**`
- `delphi-ai/skills/**` and mirrored workflow surfaces if terminology/guardrails must be synchronized

### Ordered Steps
1. Audit all current Laravel image flows and classify compliance against `D-IMG-00`.
2. Freeze the approved wrapper model and the deterministic guardrail boundary (`D-IMG-01`).
3. Add/extend architecture guardrails that detect ad hoc image public URL generation or persistence outside approved services.
4. Migrate remaining non-compliant flows in bounded batches, preserving legacy compatibility aliases only as façades over canonical ownership.
5. Update foundation docs and Delphi skill/workflow references so the same rule vocabulary is used in repo and process surfaces.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** The slice is guardrail/hardening work; each migrated non-compliant flow should land behind explicit RED coverage that proves the old bypass and the new canonical path.
- **Fail-first target(s) (when required):** Guardrail tests for direct public URL bypasses and focused feature tests for any migrated image flow.

### Runtime / Rollout Notes
- Legacy public URLs may need compatibility façades during migration.
- Public URL contract changes must be evaluated for cache/versioning behavior before promotion.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [ ] Architecture
- [ ] Code Quality
- [ ] Tests
- [ ] Performance
- [ ] Security
- [ ] Elegance
- [ ] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-IMG-01`
  - **Severity:** `high`
  - **Evidence:** The branding/public-web fallback bug required a canonicalization fix because `public_web_metadata.default_image` bypassed `belluga_media` and persisted host-bound absolute URLs.
  - **Why it matters now:** Without a hard rule, future image slots can regress in the same way while still appearing locally valid.
  - **Option A (Recommended):** Inventory all image flows, add deterministic guardrails, and migrate remaining bypasses to `belluga_media`/approved wrappers.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Fix bypasses only when regressions are reported.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Keep reviewer/process awareness as the main control.
    - **Effort:** `none`
    - **Risk:** `critical`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

- **Issue ID:** `ARCH-IMG-02`
  - **Severity:** `medium`
  - **Evidence:** Some flows may already have host-specific wrappers or legacy aliases that cannot be removed atomically without compatibility planning.
  - **Why it matters now:** The rule must be hard enough to block regressions but practical enough to migrate existing surfaces in bounded slices.
  - **Option A (Recommended):** Allow only explicit, documented wrappers that delegate to `belluga_media`, with migration inventory and owner.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Permit any host-owned wrapper as long as reviews judge it “similar enough”.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** `n/a`
    - **Effort:** `none`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] A new image slot persists `Storage::url(...)` directly because the wrapper/guardrail boundary is ambiguous.
- [ ] A migrated flow changes public URLs but drops legacy compatibility for already-saved records.
- [ ] A package or host module implements custom normalization logic that “looks equivalent” but drifts from canonical cache/version behavior.

### Residual Unknowns / Risks
- [ ] The current non-compliant inventory is not yet frozen.
- [ ] Some existing image services may need package-level convergence work rather than host-only fixes.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `correctness`, `structural-soundness`

## Independent No-Context Critique Gate (Required for `big`; conditional for `medium/high-impact`)
- **Critique decision:** `recommended`
- **Why this decision:** Cross-module guardrail work can easily become over-broad or under-specified.
- **Impact signals in scope:** `cross-module blast radius`, `public contract/schema/api`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `no`
- **Canonical multi-lane audit protocol (when required):** `n/a`
- **Audit session / round evidence (when protocol used):** `n/a`
- **Critique lenses:** `correctness`, `elegance`, `structural-soundness`, `risk`
- **Critique status:** `not_run`
- **Findings summary:** `none yet`
- **Evidence / reference:** `n/a`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `laravel-app/packages/belluga/belluga_media/README.md` | Canonical media ownership contract already exists there. | Shared media primitives and URL normalization semantics. | Reintroducing image-specific ad hoc persistence/URL code. | Defines the architecture target for every audited flow. |
| `foundation_documentation/todos/completed/TODO-vnext-laravel-package-guardrails-and-skill-convergence.md` | This slice overlaps package/guardrail convergence semantics. | Deterministic repo enforcement and skill/workflow vocabulary sync. | Prompt-only governance without CI enforcement. | Media hardening rules should complement, not bypass, the broader package guardrail strategy. |
| `foundation_documentation/todos/completed/tenant-public-branding-metadata-fallback.md` | The current branding regression is the concrete defect that exposed the broader rule gap. | The verified root cause and regression evidence. | Recasting the issue as branding-only. | Supplies the first concrete migration/evidence case for this VNext slice. |
