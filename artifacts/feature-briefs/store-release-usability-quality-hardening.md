# Feature Brief: Store Release Usability Quality Hardening

## Artifact Role
- **Why this brief exists now:** The Store Release usability recut is externally validated, but the triple audit found quality risks across performance, security/integrity, test evidence, and Flutter/Laravel structure. The work is broader than one isolated bug and needs a hardening slice before promotion.
- **What this brief is not:** This is not a canonical module document, project constitution, roadmap item, tactical implementation authority, or promotion approval.

## Source Idea / Request
- After the triple external audit of the Store Release usability recut, treat the result in two levels:
  - first, tests must guarantee no regression of final behavior;
  - then, with behavior protected, refactor for performance, elegance, clean code, and security.

## Problem / Desired Outcome
- **Problem:** The delivery behaves correctly in validated manual/runtime checks, but the implementation carries high-risk query shapes, incomplete browser evidence for filter click paths, accessibility semantics risk, overly concentrated Flutter form orchestration, and integrity/security risks around taxonomy/type validation and public filter leakage.
- **Desired outcome:** Establish a quality-clean checkpoint that preserves the validated external behavior while removing promotion-blocking performance/security risks and reducing structural debt introduced by the recut.
- **Why now:** This is a Store Release lane. Promotion before resolving high/medium audit findings could ship hidden scalability, integrity, or verification gaps.

## Constraints / Non-Goals
- **Constraints:** Preserve the externally validated behavior. Refactor only under tests that prove the final user-visible contracts. Browser-visible and mutation behavior requires Playwright evidence against the refreshed final domain; backend contracts require Laravel feature/unit evidence.
- **Non-goals:** Redesigning the product model, adding new public filter configuration surfaces, changing Map filter behavior beyond regression protection, or promoting to `dev`/`stage` inside this implementation TODO.

## Canonical Touchpoints
- **Constitution impact:** none expected. Existing constitution already forbids recurring full scans and decentralized scheduler mutation.
- **Roadmap impact:** none expected. This is Store Release quality hardening.
- **Primary module candidates:** `foundation_documentation/modules/events_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/account_profile_catalog_module.md`, `foundation_documentation/modules/map_poi_module.md`

## Evidence / References
- Triple audit summary: `foundation_documentation/artifacts/triple-audits/store-release-usability-recut-quality-20260424/store-release-usability-recut-quality-triple-audit-20260424T160027Z/round-01/round-summary.md`
- Delphi adjudication: `foundation_documentation/artifacts/triple-audits/store-release-usability-recut-quality-20260424/store-release-usability-recut-quality-triple-audit-20260424T160027Z/round-01/adjudication.md`
- Promotion-lane recut TODO: `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-usability-bug-convergence-recut.md`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Whether discovery filter taxonomy catalogs should remain eager or move to selected-type/lazy loading. | Affects backend payload shape and Flutter loading behavior. | Audit flagged unbounded catalog payload/render risk; product requires taxonomies only after primary selection. | `resolve now` |
| `AMB-02` | Whether the triple-audit findings should be fixed inside the recut TODOs or in a dedicated hardening TODO. | Avoids mixing validated behavior recut with quality cleanup scope. | User requested a two-level hardening plan after external validation. | `resolve now` |

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Protect final Store Release usability behavior with regression tests, then refactor high/medium quality risks. | `events_module` | `flutter_client_experience_module`, `tenant_admin_module`, `account_profile_catalog_module` | High/medium audit findings resolved or explicitly accepted; runtime-visible behavior has item-specific evidence. | Laravel query/integrity tests, Flutter widget/unit tests, Playwright mutation/readonly tests after web build. | `create-now` | Store Release recut branch must remain behavior-compatible. | Current tactical slice. |
| `ST-02` | Longer-term deterministic guard hardening for query-path and scheduler anti-patterns beyond touched code. | `project governance` | Laravel stack | Project-wide guards catch future broad scans/N+1/scheduler mutation drift. | Dedicated guard TODO and deterministic rule evidence. | `defer` | Existing `TODO-v1-query-path-guardrails-hardening.md` exists in vnext. | Not required to close Store Release promotion blocker unless new issue appears. |

## Retire This Brief When
- The hardening tactical TODO is created and carries the execution/evidence contract.
