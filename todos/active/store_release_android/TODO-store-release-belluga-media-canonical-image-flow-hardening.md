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
- **Next exact step:** execute the media-flow inventory and run the required critique packet so the implementation orchestration starts from an audited non-compliant inventory and a frozen media rule set.

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
- **May stay inside this TODO:** Media-flow inventory, guardrail additions, host-wrapper creation, one-time persisted-record/public-URL migrations, doc/skill alignment, and test coverage required to enforce the rule.
- **Must update or split the TODO:** New product media capabilities, reusable media library UX, or broader storage/provider architecture changes.

## Definition of Done
- [ ] A canonical inventory of Laravel image flows exists with compliant/non-compliant status and owner.
- [ ] A frozen rule states that Laravel image flows must use `belluga_media` directly or via an approved wrapper.
- [ ] CI guardrails exist to catch direct/ad hoc image public URL generation outside approved surfaces.
- [ ] Known non-compliant flows are either migrated in-slice or explicitly registered with owner + follow-up.
- [ ] Foundation docs and Delphi skills/workflows reference the same rule and vocabulary.

## Validation Steps
- [ ] Run Laravel guardrail suite (`composer run lint:strict` and any dedicated architecture guardrail commands added by this slice).
- [ ] Run focused Laravel unit/feature suites covering migrated image flows and persisted-record/public-URL continuity after alias removal.
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

## Decisions (Resolved Before Freeze)
- [x] `D-IMG-00` All Laravel product image flows must use `belluga_media` directly or an explicit host-owned wrapper built on top of it. Direct `Storage::disk('public')->url(...)`, ad hoc public URL normalization, or image-specific controller/trait persistence outside that path are no longer acceptable as canonical architecture. Module ref: `No Prior Decision` (user directive captured on 2026-04-15).
- [x] `D-IMG-01` The only approved wrapper is one explicit local host library that delegates to the Laravel package boundary (`belluga_media`) through shared canonical primitives/services. Additional host wrappers, feature-scoped wrappers, or transitional parallel wrappers are forbidden. This local wrapper may later be promoted to a global reusable surface, but this TODO must enforce the single-wrapper rule now. Module ref: `No Prior Decision` (user decision captured on 2026-04-20).
- [x] `D-IMG-02` Legacy public aliases are not approved as part of the target architecture for this slice. Delivery must remove them rather than preserve alias façades, and any already-persisted legacy references must be migrated/backfilled onto canonical media URLs instead of keeping a parallel compatibility path. Module ref: `No Prior Decision` (user decision captured on 2026-04-20).

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `No Prior Decision` | No explicit cross-cutting media hardening rule currently states that all Laravel image flows must route through `belluga_media`. | `Supersede (Intentional)` | Repo audit and branding/OG regression analysis on `2026-04-15`; `laravel-app/packages/belluga/belluga_media/README.md`; current host/media service inventory |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-IMG-00` Laravel image flows must not own persistence/public URL logic outside `belluga_media` or an approved wrapper over it.
- [x] `D-IMG-01` Guardrails must enforce exactly one approved local wrapper integrated to `belluga_media`; no additional wrappers are acceptable.
- [x] `D-IMG-02` Legacy public aliases must be removed; delivery must migrate persisted records/public URLs to the canonical media path instead of keeping alias façades alive.
- [x] `D-IMG-03` Inventory does not auto-expand execution scope: once the repo audit finishes, the first migration batch must be frozen explicitly, and any newly discovered owner family/package-convergence work outside that frozen list requires TODO split or renewed approval before execution continues.
- [x] `D-IMG-04` Alias removal and promotion are blocked until migration-evidence proves every in-scope persisted legacy reference/public URL was enumerated, rewritten to the canonical media path, or explicitly deferred into a named follow-up TODO.

## Questions To Close
- [ ] Which current Laravel image flows are still non-compliant after the latest branding/public-web fix?
- [ ] Which of those flows remain inside the first frozen migration batch after the post-inventory scope gate?

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
2. Freeze the exact in-slice non-compliant flow list, the first migration batch, and the explicit split threshold required by `D-IMG-03`; if inventory reveals broader package convergence or owner families outside that boundary, stop for TODO refresh/renewed approval instead of widening the slice ad hoc.
3. Freeze the approved wrapper model and the deterministic guardrail boundary (`D-IMG-01`).
4. Add/extend architecture guardrails that detect ad hoc image public URL generation or persistence outside approved services.
5. Migrate remaining in-slice non-compliant flows in bounded batches and rewrite/backfill persisted legacy references so canonical media URLs become the only supported public path.
6. Produce promotion-blocking migration evidence proving in-scope legacy persisted references/public URLs were enumerated and rewritten, with any unresolved owner captured in an explicit follow-up TODO before alias removal/promotion.
7. Update foundation docs and Delphi skill/workflow references so the same rule vocabulary is used in repo and process surfaces.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** The slice is guardrail/hardening work; each migrated non-compliant flow should land behind explicit RED coverage that proves the old bypass and the new canonical path.
- **Fail-first target(s) (when required):** Guardrail tests for direct public URL bypasses, focused feature tests for each migrated image flow, and explicit migration/backfill completeness evidence for persisted legacy references before alias removal.

### Runtime / Rollout Notes
- Inventory results must freeze the first migration batch before implementation widens across newly discovered flows.
- Persisted legacy records/public URLs may require one-time rewrite/backfill before promotion.
- Alias removal cannot proceed on test success alone; promotion needs explicit migration-evidence for canonical rewrite completeness.
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
  - **Evidence:** Some flows may already have host-specific wrappers or persisted legacy public URLs that cannot be retired safely without explicit migration/backfill planning.
  - **Why it matters now:** The rule must be hard enough to block regressions while still accounting for already-saved legacy references that would break if aliases disappear without data migration.
  - **Option A (Recommended):** Allow only the single approved wrapper over `belluga_media`, remove aliases, and treat legacy references as migration/backfill work owned by this slice.
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
- [ ] Inventory discovers additional owner families or package-level convergence work and execution silently absorbs them without a refreshed TODO boundary.
- [ ] A new image slot persists `Storage::url(...)` directly because the single-wrapper rule is bypassed or not covered by guardrails.
- [ ] Alias removal ships before already-saved records/public URLs are rewritten to the canonical media path.
- [ ] A package or host module implements a second wrapper or custom normalization logic that “looks equivalent” but drifts from canonical cache/version behavior.

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo <todo-path> [--json-output <artifact-path>]`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` => `status: ready`, `Overall outcome: go`, fingerprint `0e217d6e9691` (`2026-04-20`)

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-checks the existing complexity classification. |
| `blast_radius` | `cross-stack` | Public media URLs affect Laravel ownership plus Flutter/Web-visible continuity. |
| `behavioral_change_or_bugfix` | `yes` | This slice hardens behavior and closes a real regression class. |
| `changes_public_contract` | `yes` | Canonical public URL behavior and allowed wrappers are contract-visible. |
| `touches_auth_or_tenant` | `no` | No auth/tenant-access rule change is required by the TODO contract itself. |
| `touches_runtime_or_infra` | `yes` | Media serving/cache/version behavior and runtime routing are in scope. |
| `touches_tests` | `yes` | Guardrail and migrated-flow tests are part of the slice. |
| `critical_user_journey` | `yes` | Broken image ownership is publication-critical for release surfaces. |
| `release_or_promotion_critical` | `yes` | This TODO is explicitly in the store-release lane. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-IMG-01` is high severity. |
| `explicit_three_lane_request` | `no` | Triple external audit is not explicitly required right now. |

### Derived Audit Floor
- `Critique`: `required` before `APROVADO` via `wf-docker-independent-critique-method`.
- `Security review`: `recommended` before completion via `security-adversarial-review`.
- `Performance/concurrency`: `required` via `wf-docker-performance-concurrency-validation-method`.
- `Verification debt`: `required` before completion via `verification-debt-audit`.
- `Test-quality audit`: `required` before completion via `wf-docker-independent-test-quality-audit-method`.
- `Final review`: `required` before completion via `wf-docker-independent-final-review-method`.
- `Triple review`: `required` before completion via `audit-protocol-triple-review` and additive only; it does not replace critique.

### Residual Unknowns / Risks
- [ ] The current non-compliant inventory is not yet frozen.
- [ ] The first frozen migration batch versus deferred owner list is not yet closed.
- [ ] Some existing image services may need package-level convergence work rather than host-only fixes.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `correctness`, `structural-soundness`

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)
- **Critique decision:** `required`
- **Why this decision:** The TEACH audit floor classified this TODO as expanded-risk due to cross-stack/public-contract/runtime/release-critical signals plus a high-severity plan-review issue.
- **Impact signals in scope:** `cross-module blast radius`, `public contract/schema/api`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `pending post-implementation`
- **Critique lenses:** `correctness`, `elegance`, `structural-soundness`, `risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** integrated two critique corrections before execution approval:
  - added the mandatory post-inventory scope-freeze gate (`D-IMG-03`; ordered step `2`)
  - added the promotion-blocking migration-evidence gate for alias removal/backfill completeness (`D-IMG-04`; ordered step `6`)
- **Evidence / reference:** `.delphi_orchestration/orch-20260420/reviews/media/critique/merge.md`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `laravel-app/packages/belluga/belluga_media/README.md` | Canonical media ownership contract already exists there. | Shared media primitives and URL normalization semantics. | Reintroducing image-specific ad hoc persistence/URL code. | Defines the architecture target for every audited flow. |
| `foundation_documentation/todos/completed/TODO-vnext-laravel-package-guardrails-and-skill-convergence.md` | This slice overlaps package/guardrail convergence semantics. | Deterministic repo enforcement and skill/workflow vocabulary sync. | Prompt-only governance without CI enforcement. | Media hardening rules should complement, not bypass, the broader package guardrail strategy. |
| `foundation_documentation/todos/completed/tenant-public-branding-metadata-fallback.md` | The current branding regression is the concrete defect that exposed the broader rule gap. | The verified root cause and regression evidence. | Recasting the issue as branding-only. | Supplies the first concrete migration/evidence case for this VNext slice. |
