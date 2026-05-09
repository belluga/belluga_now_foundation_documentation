# PACED Subagent Dispatch: critique

## Dispatch Identity
- **Artifact kind:** `subagent_review_dispatch`
- **Authoritative:** `false`
- **Edit policy:** `derived_dispatch_packet`
- **Review kind:** `critique`
- **Bounded package:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`
- **Reviewer count:** `1`
- **No-context required:** `true`

## Required Axes
- `adherence`
- `performance`
- `elegance`
- `structural_soundness`
- `operational_fit`

## Focus Points
- Challenge the bounded plan or implementation for regressions, hidden scope, and weak adherence.
- Explicitly assess performance, elegance, structural soundness, and operational fit.
- Do not reopen unrelated architecture outside the bounded package.
- For each material finding, add category and formalizable-hint when you can judge them honestly.

## Required Result Fields
- `overall_assessment`
- `recommended_path`
- `performance_position`
- `elegance_position`
- `structural_soundness_position`
- `operational_fit_position`
- `findings[].finding_id (optional)`
- `findings[].category (optional)`
- `findings[].formalizable_hint (optional)`
- `findings[].candidate_rule_level (optional)`
- `findings[]`

## Goal
Final-baseline security rerun for RR-AUTH-04 after the explicit subject-ceiling correction. Focus on residual abuse-control, reset timing side channels, password-policy gaps, and whether any remaining finding is truly blocking versus explicit accepted debt. Findings first, JSON only.

## Related TODO
`foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`

## Result Contract
Each reviewer should answer in JSON compatible with `schemas/subagent_review_result.schema.json`.

