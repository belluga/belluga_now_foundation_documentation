# TODO (VNext): GitHub Actions Runtime Version Upgrade Without Behavior Change

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active (`Planning`)
**Owners:** Platform + Flutter + Web
**Objective:** Upgrade GitHub Actions and workflow runtime dependencies to supported versions so CI stops emitting Node 20 deprecation warnings, without changing promotion, publish, callback, or deploy behavior.
**Complexity:** `medium`
**Checkpoint policy:** one planning checkpoint before execution approval and one validation checkpoint before closure.

---

## Goal
Modernize the workflow action/runtime baseline across `flutter-app`, `web-app`, and `belluga_now_docker` so the promotion chain stays on supported GitHub Actions runtimes while preserving the current lane semantics and generated artifacts.

---

## Canonical Module Anchors (Mandatory)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
- **Promotion targets (post-implementation):**
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/system_roadmap.md`

---

## References
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-parking-lot.md`
- `flutter-app/.github/workflows/web-artifact-publish.yml`
- `flutter-app/.github/workflows/lane-auto-promotion.yml`
- `web-app/.github/workflows/navigation-validation.yml`
- `web-app/.github/workflows/dispatch-docker-sync.yml`
- `web-app/.github/workflows/lane-auto-promotion.yml`
- `.github/workflows/orchestration-ci-cd.yml`
- `.github/workflows/lane-promotion-pr.yml`
- `.github/workflows/submodule-sync-pr.yml`
- `.github/workflows/source-promotion-status-callback.yml`

---

## Context / Evidence
- The successful promotion cycle completed on `2026-04-09`/`2026-04-10`, but multiple green runs still emitted non-blocking deprecation warnings for Node 20 JavaScript actions.
- The currently referenced workflow actions include older majors such as:
  - `actions/checkout@v4`
  - `actions/setup-node@v4`
  - `actions/upload-artifact@v4`
  - `peter-evans/repository-dispatch@v3`
- These warnings appeared in the same promotion path that now governs Flutter publish, web-app auto-merge, and Docker callback orchestration.
- This slice is technical debt reduction only; it must not reopen the recently stabilized promotion logic.

---

## Workflow Surface Inventory (Current)

### `flutter-app`
- `flutter-app/.github/workflows/web-artifact-publish.yml`
  - `actions/checkout@v4`
  - `subosito/flutter-action@v2`
  - `actions/upload-artifact@v4`
  - `actions/download-artifact@v4`
  - `peter-evans/repository-dispatch@v3`
- `flutter-app/.github/workflows/lane-auto-promotion.yml`
  - `peter-evans/repository-dispatch@v3`

### `web-app`
- `web-app/.github/workflows/navigation-validation.yml`
  - `actions/checkout@v4`
  - `actions/setup-node@v4`
  - `actions/upload-artifact@v4`
  - `peter-evans/repository-dispatch@v3`
- `web-app/.github/workflows/dispatch-docker-sync.yml`
  - `peter-evans/repository-dispatch@v3`
- `web-app/.github/workflows/lane-auto-promotion.yml`
  - `peter-evans/repository-dispatch@v3`

### `belluga_now_docker`
- `.github/workflows/orchestration-ci-cd.yml`
  - `actions/checkout@v4`
  - `actions/setup-node@v4`
  - `actions/upload-artifact@v4`
- `.github/workflows/lane-promotion-pr.yml`
  - `actions/checkout@v4`
- `.github/workflows/submodule-sync-pr.yml`
  - `actions/checkout@v4`
- `.github/workflows/source-promotion-status-callback.yml`
  - `actions/checkout@v4`

### Notes
- The inventory above is the current planning baseline, not the final upgrade list.
- Each referenced action still needs classification as:
  - safe version bump inside this TODO, or
  - blocked and split into a separate behavioral-migration TODO.

---

## Scope
1. Inventory every GitHub Actions workflow in `flutter-app`, `web-app`, and `belluga_now_docker` that still relies on deprecated or soon-to-be-deprecated runtime versions.
2. Upgrade the affected actions to supported versions or pins compatible with current GitHub-hosted runners.
3. Preserve current workflow semantics for:
   - Flutter web artifact publish
   - web-app lane validation + auto-merge
   - Docker source callback + lane promotion
4. Validate that artifact paths, callback payloads, permissions, and merge behavior remain unchanged after the version bump.
5. Document any unavoidable version-specific caveats discovered during rollout.

## Out of Scope
- Redesigning the promotion topology.
- Changing branch contracts, lane names, or callback event schemas.
- Refactoring product code, build outputs, or deployment logic.
- Mixing unrelated CI feature work into the same slice.

---

## Execution Governance (Mandatory)
- **Execution lane:** Tactical VNext TODO lane.
- **Authority rule:** this TODO is the sole execution authority for the no-behavior-change GitHub Actions runtime upgrade slice.
- **Approval rule:** no workflow changes should start until this TODO is selected for implementation and explicitly approved.
- **Split rule:** if any candidate upgrade requires changing inputs, permissions, artifact semantics, checkout depth, callback payload shape, or merge sequencing, it must be split into a separate TODO.
- **Evidence rule:** green workflow runs without deprecation warnings are required before this TODO can close.

---

## Decision Baseline (Frozen)
- `D-GHA-01`: This slice is version-only hardening; workflow behavior must remain materially unchanged.
- `D-GHA-02`: Action upgrades must preserve the current publish chain across `flutter-app`, `web-app`, and `belluga_now_docker`.
- `D-GHA-03`: Any action that requires a behavioral change to upgrade safely must be split into a separate TODO instead of being hidden inside this slice.
- `D-GHA-04`: Validation evidence must come from real workflow runs on the affected repositories, not only local YAML parsing.

---

## Plan Review Gate (Medium)

### Issue Card I-01
- **Severity:** High
- **Category:** CI runtime compatibility
- **Evidence:** the current promotion chain is green but still emits Node 20 deprecation warnings on JavaScript actions used by `flutter-app`, `web-app`, and `belluga_now_docker`.
- **Why now:** once GitHub removes runtime support, promotion confidence will degrade from warning-only debt into real delivery failures.
- **Options:**
  - **A (Recommended):** upgrade only the actions whose new supported versions preserve current inputs and behavior, then validate with real promotion-path runs.
    - Effort: medium
    - Risk: low
    - Blast radius: medium
    - Maintenance burden: low
  - **B:** postpone all action upgrades until GitHub forces the migration.
    - Effort: none
    - Risk: high
    - Blast radius: high
    - Maintenance burden: high
  - **C:** mix action upgrades with broader CI refactors.
    - Effort: high
    - Risk: high
    - Blast radius: high
    - Maintenance burden: high

### Issue Card I-02
- **Severity:** High
- **Category:** Artifact handoff safety
- **Evidence:** `web-artifact-publish.yml` and `orchestration-ci-cd.yml` both depend on artifact upload/download semantics inside the promotion chain.
- **Why now:** a major action bump that changes artifact naming, retention, permissions, or download behavior could silently break the cross-repo publish path.
- **Options:**
  - **A (Recommended):** treat artifact actions as compatibility-sensitive and validate exact artifact handoff behavior in real runs after upgrade.
    - Effort: medium
    - Risk: low
    - Blast radius: medium
    - Maintenance burden: medium
  - **B:** rely on YAML syntax validity plus green unit jobs only.
    - Effort: low
    - Risk: high
    - Blast radius: medium
    - Maintenance burden: medium
  - **C:** defer artifact-action upgrades out of this TODO entirely.
    - Effort: low
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: medium

### Issue Card I-03
- **Severity:** High
- **Category:** Cross-repo callback semantics
- **Evidence:** `peter-evans/repository-dispatch@v3` is used in Flutter and web-app callback paths that feed Docker orchestration.
- **Why now:** callback actions are sensitive to token behavior, permissions, and payload field continuity; a version jump without proof could break downstream revalidation or lane synchronization.
- **Options:**
  - **A (Recommended):** upgrade dispatch actions only with explicit callback smoke validation across Flutter -> web-app -> Docker.
    - Effort: medium
    - Risk: medium
    - Blast radius: high
    - Maintenance burden: medium
  - **B:** keep dispatch actions pinned on the old major while upgrading everything else.
    - Effort: low
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: medium
  - **C:** replace repository-dispatch implementation in the same slice.
    - Effort: high
    - Risk: high
    - Blast radius: high
    - Maintenance burden: high

### Issue Card I-04
- **Severity:** Medium
- **Category:** Third-party action classification
- **Evidence:** `subosito/flutter-action@v2` appears in the publish chain inventory but is not yet proven to be part of the Node 20 warning surface.
- **Why now:** not every action in the inventory needs a version bump, and unnecessary upgrades increase blast radius for no operational gain.
- **Options:**
  - **A (Recommended):** classify each action from warning evidence before touching it; only upgrade what is materially in scope.
    - Effort: low
    - Risk: low
    - Blast radius: low
    - Maintenance burden: low
  - **B:** upgrade every action found in the workflow inventory.
    - Effort: medium
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: medium
  - **C:** ignore third-party actions during the inventory phase.
    - Effort: none
    - Risk: medium
    - Blast radius: medium
    - Maintenance burden: medium

---

## Candidate Workstreams
- [ ] ⚪ Pending Produce a full inventory of deprecated GitHub Actions/runtime references across the three repositories.
- [ ] ⚪ Pending Classify each upgrade as safe major/minor pin refresh vs follow-up behavioral migration.
- [ ] ⚪ Pending Apply the safe no-behavior-change upgrades in the relevant workflows.
- [ ] ⚪ Pending Run the affected promotion/publish workflows and confirm warning removal without regressions.
- [ ] ⚪ Pending Register any blocked behavioral migrations as separate VNext TODOs if needed.

---

## Failure Modes & Edge Cases
- A bumped checkout action changes defaults that affect git history depth, causing lane ancestry checks to fail.
- An artifact action bump changes compression, retention, or naming behavior and breaks publish/download handoff.
- A dispatch action bump alters token/permission requirements and breaks web-app or Docker callbacks.
- A setup action bump changes tool caching behavior and turns green workflows flaky under cold runners.
- Warnings disappear only because the workflow path was not fully exercised, leaving hidden debt in untouched jobs.
- One repository upgrades cleanly while another stays on the old runtime baseline, keeping the cross-repo chain inconsistent.

---

## Uncertainty Register
- **Assumptions:**
  - The currently observed warnings are caused by action runtime versions rather than by runner image drift alone.
  - GitHub-hosted runners used by these repositories support the target action versions without additional platform migration.
- **Unknowns:**
  - Whether every third-party action in the inventory already has a supported no-behavior-change upgrade path.
  - Whether any workflow depends on undocumented defaults that changed between action majors.
- **Confidence:** Medium.

---

## Definition of Done
- Every affected workflow in `flutter-app`, `web-app`, and `belluga_now_docker` is inventoried and classified.
- All safe no-behavior-change version bumps are applied.
- Any blocked or behavior-changing upgrades are explicitly split into separate TODOs.
- Real workflow evidence shows the touched paths still complete green.
- The previously observed Node 20 deprecation warnings are removed from the touched workflow runs.
- Stable conclusions and any retained exceptions are recorded back into the relevant summary docs if the upgrade changes the documented CI baseline.

---

## Validation Targets (Future)
- Local YAML parsing remains valid for all touched workflows.
- A real `flutter-app` publish path completes green after the version bump.
- A real `web-app` validation/auto-merge or callback path completes green after the version bump.
- A real `belluga_now_docker` orchestration/promotion path completes green after the version bump.
- Node 20 deprecation warnings no longer appear in the touched workflow logs.
- Flutter publish, web-app merge/callback, and Docker promotion behavior remain unchanged.
