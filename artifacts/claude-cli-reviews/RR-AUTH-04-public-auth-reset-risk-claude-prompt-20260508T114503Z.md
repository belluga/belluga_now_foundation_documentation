# Claude CLI Fourth-Auditor Prompt - RR-AUTH-04 - 2026-05-08T11:45Z

You are the fourth auditor in a bounded comparison experiment. You are not the implementer, and you must not edit files.

Scope:
- Review only RR-AUTH-04 public auth / password reset / risk matrix hardening.
- Bounded package: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package.md`
- Governing TODO: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- Final-baseline wave-01 review reconciliation ledger: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-wave-01-review-reconciliation-ledger-20260508T114503Z.md`
- Triple-audit session progress to compare against: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T030556Z/progress.md`
- Triple-audit round-02 accepted-debt resolution: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T030556Z/round-02/resolution.md`
- Corrected-baseline rerun ledger: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-corrected-baseline-rerun-ledger-20260508T1103Z.md`
- Do not reopen unrelated dirty-tree state from RR-AUTH-01, RR-AUTH-02, RR-AUTH-03, or unrelated Flutter/navigation work.
- Do not re-raise the earlier subject-ceiling blocker unless you conclude the explicit config + guardrail + test evidence still fails to prove the shipped baseline.

Question:
Assess whether RR-AUTH-04 is closure-ready as a bounded public-auth/reset-risk hardening package, assuming the remaining closure-only gates are the verification-debt lane, final-review lane, and final deterministic guards. Focus on security residuals, test quality, evidence binding, and whether the accepted-debt posture is correctly scoped as non-blocking for this slice.

Return JSON only with this shape:
{
  "overall_assessment": "string",
  "closure_position": "clean|blocked|clean_with_accepted_debt",
  "comparison_to_existing_reviewers": "string",
  "findings": [
    {
      "severity": "low|medium|high",
      "title": "string",
      "rationale": "string",
      "suggested_action": "string"
    }
  ],
  "recommended_path": "string"
}
