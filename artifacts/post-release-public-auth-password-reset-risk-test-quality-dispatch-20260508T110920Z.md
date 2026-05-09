# PACED Subagent Dispatch: test_quality_audit

## Dispatch Identity
- **Artifact kind:** `subagent_review_dispatch`
- **Authoritative:** `false`
- **Edit policy:** `derived_dispatch_packet`
- **Review kind:** `test_quality_audit`
- **Bounded package:** `foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`
- **Reviewer count:** `1`
- **No-context required:** `true`

## Required Axes
- `test_effectiveness`
- `test_efficiency`
- `performance`
- `structural_soundness`
- `operational_fit`

## Focus Points
- Verify whether changed tests reflect real behavior/contract change or only pass-the-test repair.
- Assess whether assertions are effective and efficient.
- State whether the audit sees brittle test-only shortcuts or weak coverage.
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
Corrected-baseline test-quality rerun for RR-AUTH-04 after round-01 reconciliation, the 20260508T1103Z rerun ledger, and fresh full-suite validation. Focus on regression protection, assertion effectiveness, and evidence realism only. Findings first, JSON only.

## Related TODO
`foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`

## Result Contract
Each reviewer should answer in JSON compatible with `schemas/subagent_review_result.schema.json`.

