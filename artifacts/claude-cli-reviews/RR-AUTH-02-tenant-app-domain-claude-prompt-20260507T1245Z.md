# Claude CLI Fourth-Auditor Prompt - RR-AUTH-02 - 2026-05-07T12:45Z

You are the fourth auditor in a bounded comparison experiment. You are not the implementer, and you must not edit files.

Scope:
- Review only RR-AUTH-02 tenant app-domain authorization and app-link integrity hardening.
- Bounded package: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-tenant-app-domain-authorization-hardening-package.md`
- Triple-audit session to compare against: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-tenant-app-domain-authorization-hardening-package-triple-audit-20260507T1217Z/progress.md`
- Do not reopen unrelated route-file identity debt except to assess whether RR-AUTH-02 incorrectly claims it resolved that debt.

Question:
Assess whether RR-AUTH-02 is closure-ready as a bounded appdomains/domains authorization hardening package. Focus on security, test quality, operational evidence binding, and whether residual risks are correctly scoped.

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
