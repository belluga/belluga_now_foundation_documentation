# TODO (V1): Domain Primitives -> Canonical ValueObject Hardening

**Status:** Completed (`authority reconciled and closed on 2026-04-18`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Analyzer-Gate-Aligned`, `Rule-Matrix-Validated`, `Legacy-Custom-Lint-Removed`, `Closure-Synced`
**Next exact step:** None.
**Owners:** Flutter Domain, Architecture Governance
**Objective:** Eliminate primitive transport typing from Flutter domain contracts, freeze ValueObject-only remediation rules, and converge operational enforcement on the analyzer plugin as the single architecture gate.

---

## Closure Note

This lane is no longer an active delivery owner. The previous active TODO text had become stale after the repo converged on the intended end state:

- `belluga_analysis_plugin` is the authoritative enforcement path.
- the official architecture gate is root `fvm dart analyze --format machine`.
- rule documentation already defines ValueObject-only remediation plus explicit `List/Set/Iterable/Map` policy.
- analyzer-plugin rule-matrix fixtures live under `tool/belluga_analysis_plugin/test_fixtures/lint_matrix`.
- CI already runs `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`.
- the legacy `tool/belluga_custom_lint/` package has been removed from the repository.

Because those outcomes are already reflected in the current repo, there is no remaining evidence-based reason to keep this TODO active. Future primitive/ValueObject regressions are now governed by the analyzer plugin and normal architecture enforcement, not by a dedicated outstanding TODO.

## Last Confirmed Truth

As of `2026-04-18`, the repository state supports closure of this lane:

- [analysis_options.yaml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/analysis_options.yaml:3) registers `belluga_analysis_plugin` through the top-level `plugins:` configuration.
- [tool/belluga_analysis_plugin/docs/rules.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/tool/belluga_analysis_plugin/docs/rules.md:1) is the canonical rule contract and explicitly decommissions `custom_lint` as the architecture gate.
- [tool/belluga_analysis_plugin/lib/src/type_utils.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/tool/belluga_analysis_plugin/lib/src/type_utils.dart:1) and [tool/belluga_analysis_plugin/lib/src/rules/domain_primitive_field_forbidden_rule.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/tool/belluga_analysis_plugin/lib/src/rules/domain_primitive_field_forbidden_rule.dart:1) already encode the hardened ValueObject/list/set/map policy.
- [tool/belluga_analysis_plugin/test_fixtures/lint_matrix](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/tool/belluga_analysis_plugin/test_fixtures/lint_matrix) is the owned fixture surface for matrix validation.
- [.github/workflows/web-artifact-publish.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/.github/workflows/web-artifact-publish.yml:218) already runs the analyzer-plugin rule-matrix validation in CI.
- `tool/belluga_custom_lint/` no longer exists in the repository.

## Outcome Summary

- ValueObject-only remediation is now the explicit canonical rule for domain typing.
- `List/Set/Iterable` are allowed only with ValueObject or domain-owned element types.
- `Map` is forbidden in domain signatures and must be replaced by auxiliary domain models / ValueObjects.
- Root analyzer invocation is the only accepted architecture gate; directory-target analyzer mode remains non-authoritative.
- Legacy `custom_lint` package ownership has been retired from the repo.

## Historical Context

The active TODO remained open only because its text still described migration work, path references, and pending checklist items from an earlier in-progress phase. During authority reconciliation, those assumptions no longer matched the repo. This closure records the current truth and prevents the old TODO from continuing to act as false active authority.
