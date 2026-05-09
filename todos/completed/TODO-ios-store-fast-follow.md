# Title
iOS Store Fast Follow

**Superseded note (2026-04-17):** this sequencing-only middle wrapper is no longer needed. The fast-follow lane authority stays in `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md`, and the direct iOS technical execution authority is `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md`.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Status
- **Status:** `Completed`
- **Disposition:** `Superseded by direct lane authority + technical child`

## Context
The business sequence is Android first, then iOS almost immediately after. The detailed technical lane for iOS deep-link/runtime validation already exists in `TODO-ios-universal-links-production-validation.md`, but the release sequence now needs an explicit fast-follow authority in the active lane.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/mvp-with-app-backlog-realignment.md`
- **Primary story ID:** `ST-07`
- **Why this is the right current slice:** it converts iOS from former generic VNext backlog into a mandatory fast-follow release item while preserving the existing deeper technical TODO as the detailed execution reference.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** safe. The sequencing decision is already explicit; this TODO only makes the release ownership visible.

## Contract Boundary
- This TODO establishes iOS as mandatory fast-follow after Android release.
- It does not replace the deeper technical work already captured in `TODO-ios-universal-links-production-validation.md`.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Business-Defined`, `Fast-Follow`
- **Next exact step:** treat `TODO-ios-universal-links-production-validation.md` as the detailed technical child of this lane and keep iOS outside the Android gate.

## Scope
- [ ] Make iOS release sequencing explicit under `active/fast_follow_required/`.
- [ ] Link the detailed technical execution authority for iOS deep-link/runtime validation.
- [ ] Keep iOS outside the Android release gate while preserving near-immediate follow-up priority.

## Out of Scope
- [ ] Android release closure.
- [ ] Rewriting the existing detailed iOS validation TODO in this pass.

## Definition of Done
- [ ] iOS fast-follow is explicitly represented in the active fast-follow lane.
- [ ] The detailed iOS technical TODO is linked as the technical execution authority.

## Validation Steps
- [ ] This TODO is linked from `TODO-fast-follow-obligatory.md`.
- [ ] `TODO-ios-universal-links-production-validation.md` is explicitly referenced here as the detailed child lane.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`, `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** sequencing and ownership only.

## Decisions (Resolved Before Freeze)
- [x] `D-01` iOS is mandatory fast-follow after Android release.
- [x] `D-02` The existing iOS technical TODO remains the detailed execution reference until explicitly superseded.
