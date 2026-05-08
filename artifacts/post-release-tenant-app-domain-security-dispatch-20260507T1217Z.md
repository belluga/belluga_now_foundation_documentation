# PACED Subagent Dispatch: critique

## Dispatch Identity
- **Artifact kind:** `subagent_review_dispatch`
- **Authoritative:** `false`
- **Edit policy:** `derived_dispatch_packet`
- **Review kind:** `critique`
- **Bounded package:** `foundation_documentation/artifacts/post-release-tenant-app-domain-authorization-hardening-package.md`
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
Security adversarial review for RR-AUTH-02 after follow-up. Assess tenant breakout, borrowed token abilities, current-tenant role enforcement, denied non-mutation, app-link trust payloads, broad tenant-domains:update classification, adjacent /domains hardening, and residual route-audit debt. Threat-intel anchors: OWASP Authorization Cheat Sheet and Laravel Sanctum official token ability docs. Return JSON only.

## Related TODO
`foundation_documentation/todos/active/post_release_hardening/TODO-post-release-tenant-app-domain-authorization-and-app-link-integrity-hardening.md`

## Result Contract
Each reviewer should answer in JSON compatible with `schemas/subagent_review_result.schema.json`.

