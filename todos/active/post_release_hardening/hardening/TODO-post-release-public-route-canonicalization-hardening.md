# TODO: Post-Release Public Route Canonicalization Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The v0.2.0+8 pre-promotion review found that tenant-public navigation/share flows are materially better than before, but still not uniformly driven by a single canonical route contract. The concrete residuals are:
- partner/account-profile public paths are consumed as opaque strings in some surfaces instead of through one canonical normalizer/validator;
- event public URLs use a dedicated helper for share/handoff while some in-app navigation still assembles the route through typed callers independently;
- POI/public navigation still retains slug-shaped residue in some projections even after slug-derived fallback was retired from edited widgets.

These are not current release blockers after the review triage, but they are real hardening debt. They must be fixed in a bounded post-release slice instead of remaining as informal notes.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-post-release-public-route-canonicalization-hardening`
- **Why this is the right current slice:** the current release package already cut over the primary user flows; what remains is structural route-contract convergence and shim retirement across public navigation/share surfaces.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** this is a bounded hardening follow-up derived from the promotion review loop, not a new product brief.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- It owns post-release hardening for canonical public route generation/consumption across partner/account-profile and event surfaces.
- It must not broaden into unrelated IA redesign, tenant-admin routing, or new share-product behavior.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** audit every remaining tenant-public route producer/consumer and freeze the single canonical route-construction contract per surface before implementation.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** this TODO was opened from the v0.2.0+8 promotion review triage as a real but non-blocking post-release hardening slice.
- **Exit condition:** the route contract is unified, residual slug/path shims are removed or bounded explicitly, and delivery evidence is complete.

## Scope
- [ ] Define one canonical normalizer/validator for partner/account-profile public detail paths and route all public partner navigation/share consumers through it.
- [ ] Define one canonical event route contract that covers both public URL generation and in-app typed navigation with the same occurrence semantics.
- [ ] Remove or explicitly retire residual slug-based public-navigation reopen paths that survived the hard cutover.
- [ ] Add regression tests proving canonical route generation/consumption across map, account profile, discovery, favorites, and invite/share handoff surfaces.

## Out of Scope
- [ ] Redesigning tenant-public IA or changing approved public route shapes.
- [ ] Introducing backward-compatibility bridges for pre-release route contracts.
- [ ] Reworking unrelated auth, favorites, or invite product rules beyond what route canonicalization strictly requires.

## Definition of Done
- [ ] Partner/account-profile public navigation and sharing consume one canonical public-path normalizer/validator.
- [ ] Event route generation and in-app navigation cannot drift on occurrence semantics because they share one canonical route contract.
- [ ] Residual slug-only reopen paths are removed, fail-closed, or explicitly bounded as temporary with closeout criteria.
- [ ] Tests cover the canonical route contract at the route/helper layer and the relevant user-facing widgets/controllers.

## Validation Steps
- [ ] Run focused Flutter route/helper tests plus affected widget/controller suites.
- [ ] Run browser/device evidence for at least one partner public share/open flow and one event occurrence-preserving share/open flow.
- [ ] Run `cutover_integrity_audit` because this TODO retires residual compatibility/shim behavior.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `root:<pending>`, `flutter-app:<pending>`, `foundation_documentation:main`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `public-route-canonicalization-hardening` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** route helper extraction, normalizer/validator convergence, removal of residual slug reopeners, and test updates needed to prove the canonical contract.
- **Must update or split the TODO:** any change to the approved public route shapes, auth/product policy, or broader navigation architecture beyond this hardening boundary.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `strategic-cto-tech-lead if route-governance docs need project-level change`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `consolidated`
- **Why this level:** multiple public surfaces already work, but canonical convergence touches shared navigation helpers and needs cutover-integrity scrutiny.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant-public route/navigation contract sections`
- **Module decision consolidation targets (required):**
  - `public route ownership and navigation contract sections`

## Decisions (Resolved Before Freeze)
- [ ] `D-01` This TODO is routed from the v0.2.0+8 promotion review as `follow-up-hardening`, not `release-blocker`.
- [ ] `D-02` No backward-compatibility bridge may be accepted as final architecture unless explicitly recorded as temporary and closure-blocking.

## Questions To Close
- [ ] Should the canonical event route contract expose a typed factory that both URL generation and `ImmersiveEventDetailRoute` callers consume, or should typed-route generation become the source of truth directly?
- [ ] Where is the narrowest safe normalizer boundary for partner/account-profile public paths so all consumers converge without duplicating route parsing?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The remaining route debt is structural drift, not current release breakage. | v0.2.0+8 review triage after full-file widget/controller validation. | The TODO would need promotion back into blocker scope. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `flutter-app/lib/application/router/**`
- `flutter-app/lib/presentation/tenant_public/**`
- `flutter-app/test/application/router/**`
- `flutter-app/test/presentation/tenant_public/**`
- `foundation_documentation/modules/flutter_client_experience_module.md`

### Ordered Steps
1. Audit remaining public route producers/consumers and freeze the canonical route ownership per surface.
2. Implement shared helper/factory convergence for partner and event public routes.
3. Remove residual slug-only reopen paths or bound them explicitly if a temporary exception is unavoidable.
4. Add/extend tests and run cutover-integrity review.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** the current release already carries broad route coverage; this TODO should extend and tighten existing tests around the converged contract.

### Runtime / Rollout Notes
- No rollout flag expected. This is hardening of already-shipped public routing behavior.
