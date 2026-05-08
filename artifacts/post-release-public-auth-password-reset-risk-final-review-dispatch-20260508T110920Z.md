# PACED Subagent Dispatch: final_review

## Dispatch Identity
- **Artifact kind:** `subagent_review_dispatch`
- **Authoritative:** `false`
- **Edit policy:** `derived_dispatch_packet`
- **Review kind:** `final_review`
- **Bounded package:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`
- **Reviewer count:** `1`
- **No-context required:** `true`

## Required Axes
- `adherence`
- `residual_risk`
- `performance`
- `elegance`
- `structural_soundness`

## Focus Points
- Review the delivered bounded package for regressions, adherence gaps, residual risk, and waiver quality.
- Explicitly call out any performance regressions, elegance regressions, or brittle structural shortcuts.
- Stay inside the bounded package and treat the review as closure-focused.
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
Corrected-baseline final review rerun for RR-AUTH-04 after round-01 reconciliation, the 20260508T1103Z rerun ledger, and fresh full-suite validation. Stay closure-focused and treat only unresolved blocker risk as material. Findings first, JSON only.

## Related TODO
`foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`

## Result Contract
Each reviewer should answer in JSON compatible with `schemas/subagent_review_result.schema.json`.

