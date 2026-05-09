# Title
Android Publication Readiness

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The current active backlog covers conversion, invites, favorites, and app-link behavior, but it does not clearly own the app-store submission path itself. Because the business target is publication in the stores, Android-first release needs an explicit child TODO for publication readiness rather than assuming submission work will emerge later.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/mvp-with-app-backlog-realignment.md`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** Android publication readiness is a separate release gate from in-app feature completion and needs its own checklist/ownership.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** safe. The gap is already concrete: this lane now owns Android publication-readiness refinement, but the exact repo-owned versus external-console/manual-owner split still needs to be frozen.

## Contract Boundary
- This TODO exists to make Android publication readiness explicit.
- It does not replace conversion/invite/product behavior TODOs; it complements them.
- If release work widens into a full DevOps/store-ops program, split it.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`
- **Next exact step:** identify and freeze the minimum Android store-release checklist owned by the repo/backlog rather than leaving it implicit.

## Scope
- [ ] Define Android release-artifact readiness ownership (build/signing/versioning/release candidate discipline).
- [ ] Define Android store-submission readiness ownership (track selection, listing/checklist, required URLs/compliance surfaces) at the backlog level.
- [ ] Define the validation handshake between publication readiness and the conversion blocker TODOs.
- [ ] Keep the TODO bounded to Android-first publication only.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<planned>`, `belluga_now_docker:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Android release artifact + submission readiness definition | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] iOS release work.
- [ ] Full cross-store operational program.
- [ ] Conversion/invite feature delivery already covered by other child TODOs.

## Definition of Done
- [ ] Android publication readiness is represented by an explicit active TODO.
- [ ] The release lane no longer assumes publication work is “someone else’s step later”.
- [ ] Dependencies on conversion/invite/store-link readiness are explicit.

## Validation Steps
- [x] This TODO is linked from `TODO-store-release-android.md`.
- [x] Android publication readiness is no longer missing from the active backlog inventory.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-devops`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-devops`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** it is planning-only right now, but it spans release operations, app build, and store-facing readiness.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
- **Planned decision promotion targets (module sections):**
  - `n/a for initial backlog ownership`
- **Module decision consolidation targets (required):**
  - `n/a for this planning pass`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Android publication readiness needs an explicit child TODO under the store-release lane.
- [x] `D-02` This lane is separate from iOS and separate from the in-app feature TODOs.

## Questions To Close
- [ ] Which exact publication tasks are repo-owned versus external-console/manual-owner tasks?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Publication readiness is now explicitly represented by this TODO, but the exact repo-owned versus external-console/manual-owner split is still underdefined. | this active TODO exists, while `Questions To Close` still asks for the exact ownership boundary. | The release gate would stay visible in the backlog but still lack precise operational ownership. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android-publication-readiness.md`

### Ordered Steps
1. Freeze the publication-readiness scope.
2. Keep it linked into the Android release orchestrator and align its ownership boundary with the other child TODOs.
3. Use a later refinement pass to decompose store-ops tasks if needed.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** this change is backlog structuring only.
- **Fail-first target(s) (when required):** `n/a`

### Runtime / Rollout Notes
- `n/a`
