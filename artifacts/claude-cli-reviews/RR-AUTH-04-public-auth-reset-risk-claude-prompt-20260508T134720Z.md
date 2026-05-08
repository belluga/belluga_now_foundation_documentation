# Claude CLI Fourth-Auditor Prompt - RR-AUTH-04 - 2026-05-08T13:47Z

You are the fourth auditor in a bounded comparison experiment. You are not the implementer, and you must not edit files.

Scope:
- Review only RR-AUTH-04 public auth / password reset / risk matrix hardening.
- Bounded package: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-clean-baseline-review-package-20260508T125858Z.md`
- Debt-elimination ledger: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-debt-elimination-ledger-20260508T125858Z.md`
- Evidence refresh ledger: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-evidence-refresh-ledger-20260508T132742Z.md`
- Wave-02 reconciliation ledger: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-wave-02-review-reconciliation-ledger-20260508T133116Z.md`
- Triple-audit session progress to compare against: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T133116Z/progress.md`
- Triple-audit round-01 summary: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T133116Z/round-01/round-summary.md`
- Triple-audit round-01 adjudication: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-hardening-package-triple-audit-20260508T133116Z/round-01/resolution.md`
- Do not reopen unrelated landlord credential split-brain work or OTP/web-to-app product work outside RR-AUTH-04.
- Treat the previously accepted RR-AUTH-04 debt items as expected-closed on this reopened baseline. Re-raise them only if the current implementation/evidence still leaves a real blocker.

Question:
Assess whether RR-AUTH-04 is closure-ready as a bounded public-auth/reset/risk hardening package. Focus on security, test quality, operational evidence binding, and whether any residual issue still blocks closure without accepted debt.

Return JSON only with this shape:
{
  "overall_assessment": "string",
  "closure_position": "clean|blocked|clean_with_accepted_debt",
  "comparison_to_triple_audit": "string",
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
