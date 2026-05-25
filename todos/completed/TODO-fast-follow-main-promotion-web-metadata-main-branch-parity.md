# TODO (Fast Follow): Reframe Main-Lane Web/Flutter Compatibility Gate Before Docker Main Promotion

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Production-Ready. The audited metadata compatibility contract was implemented, replayed through the Docker lane, passed the completion guard, and the authoritative `main` preflight/deploy passed after the lane-resolved web runtime topology landed.

## Title
Fast Follow: Reframe Main-Lane Web/Flutter Compatibility Gate Before Docker Main Promotion

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Docker PR `belluga/belluga_now_docker#737` re-ran automatically after the Flutter `main` promotion and downstream `web-app` follow-through completed.
- The rerun still failed in `Preflight Validation`, but now on a new exact gate:
  - step: `Validate web/flutter metadata compatibility`
  - command: `bash .github/scripts/check_web_flutter_metadata.sh "main"`
  - authoritative error:
    - `ERROR: metadata source_branch mismatch for lane 'main': got 'stage'.`
- This is a real blocker for `main`, not a stale alignment error:
  - Flutter source promotion is already green to `main` via `belluga/belluga_now_front#331`
  - downstream `web-app` promotion is already green and merged via `belluga/belluga_now_web#354`
  - backend `main` is already green via `belluga/belluga_now_backend#219`
- Result: the remaining root `main` candidate is now blocked by lane metadata parity, meaning the Docker/root promotion path is still pointing at a web metadata contract that identifies `stage`, not `main`.
- Historical evidence and external audit now show that the current gate is checking the wrong contract:
  - `source_branch` originated as artifact provenance in Flutter web publication
  - Docker/root originally enforced only `flutter_git_sha` compatibility
  - `source_branch` later became diagnostic-only
  - recent CI hardening promoted `source_branch == lane` into a hard blocker on the pinned `web-app` gitlink
- External audit conclusion:
  - do **not** expand topology just to preserve `source_branch == main`
  - do **not** relax to `SHA-only`
  - the simplest safe contract is:
    - hard gate on `flutter_git_sha` compatibility
    - hard gate on effective `main` host/domain compatibility derived from `config/defines/main.json` / `LANDLORD_DOMAIN`
    - `source_branch` stays as diagnostic provenance only

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-main-promotion-web-metadata-main-branch-parity`
- **Why this is the right current slice:** one bounded Docker `main` blocker with one precise contract failure in the authoritative preflight.
- **Direct-to-TODO rationale:** the failing gate, command, and error are already concrete from remote CI; the only open question is the correct lane-owned fix surface.

## Contract Boundary
- This TODO covers only the main-lane web/flutter compatibility contract for Docker `stage -> main` promotion.
- This TODO includes:
  - removing `source_branch == main` as the hard acceptance rule
  - preserving hard failure on incompatible `flutter_git_sha`
  - preserving or adding hard failure on incompatible effective `main` host/domain
  - aligning the artifact source used by Docker/root preflight with the actual `main` runtime contract
  - re-running the authoritative Docker `main` promotion gate after the fix
- This TODO does **not** include:
  - unrelated Docker CI cleanup
  - unrelated deploy/runtime refactors
  - reopening already-closed Flutter or Laravel blockers unless a new exact defect emerges

## Decision Lock
1. `source_branch` is **not** the authoritative safety contract for `main`; it is provenance/diagnostic evidence.
2. `flutter_git_sha` compatibility remains a hard blocker for `main`.
3. Effective `main` host/domain compatibility remains a hard blocker for `main`.
4. No new main-only topology or repin path should be added merely to preserve `source_branch == main`.
5. If the implementation still uses a pinned `web-app` source for `main`, it must be because that source can prove the effective `main` host/domain contract, not because it carries the correct branch label.
6. A pure `SHA-only` relaxation is forbidden; host/domain evidence must stay fail-closed.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Fast-Follow`, `Docker`, `Main-Promotion-Blocker`, `Compatibility-Contract`, `Authoritative-CI-Failure`, `Audit-Adjudicated`
- **Next exact step:** none for this TODO; the corrected gate is now green in `main`.

## Scope
- [x] Replace the current `source_branch == lane` blocker with the audited `main` compatibility contract.
- [x] Preserve hard failure when the evaluated web artifact is incompatible with the pinned `flutter-app` SHA.
- [x] Preserve or add hard failure when the evaluated web artifact is incompatible with `config/defines/main.json` / `LANDLORD_DOMAIN` / injected host expectations.
- [x] Align the artifact source evaluated by `.github/scripts/check_web_flutter_metadata.sh` with the actual pinned runtime source; the current candidate now fails closed when that source does not satisfy `main` host expectations.
- [x] Re-run Docker `stage -> main` preflight and confirm the metadata gate passes.

## Out of Scope
- [ ] Any new Flutter product changes.
- [ ] Any new Laravel product changes.
- [ ] Unrelated root promotion-lane refactors.

## Definition of Done
- [x] Docker PR `#737` no longer fails `Validate web/flutter metadata compatibility` for `main`.
- [x] Docker `main` preflight fails closed on real incompatibility:
  - [x] incompatible `flutter_git_sha`
  - [x] incompatible effective `main` host/domain
- [x] `source_branch` remains visible only as diagnostic provenance and is no longer the acceptance gate for `main`.
- [x] Docker `main` promotion can continue past preflight without reintroducing a false-red provenance contract or a false-green host/domain mismatch.

## Validation Steps
- [x] Capture the exact artifact source currently being read by `.github/scripts/check_web_flutter_metadata.sh` for the Docker `main` candidate.
- [x] Prove the negative case: incompatible `flutter_git_sha` still fails.
- [x] Prove the negative case: incompatible effective `main` host/domain still fails.
- [x] Prove the positive case: `source_branch != main` no longer blocks by itself when the hard compatibility gates pass.
- [x] Re-run the authoritative Docker `main` promotion gate and record the passing evidence for the compatibility step.

## Execution Lane Tracking
- **Local implementation branches:** `belluga_now_docker:fix/main-promotion-web-metadata-contract-20260522`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `main`
- **Production-ready threshold for this TODO:** `main`

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| web/flutter compatibility contract for Docker `main` | `fix/main-promotion-web-metadata-contract-20260522` | `belluga_now_docker#740` | `belluga_now_docker#741` | `belluga_now_docker#737`, preserved through `#751` | `Production-Ready`; run `26320227463` passed `Validate web/flutter metadata compatibility` for `main` |

## Authoritative Failure Evidence
- Docker PR: `belluga/belluga_now_docker#737`
- Workflow run: `26263538475`
- Job: `Preflight Validation`
- Step: `Validate web/flutter metadata compatibility`
- Command:
  - `bash .github/scripts/check_web_flutter_metadata.sh "main"`
- Error:
  - `ERROR: metadata source_branch mismatch for lane 'main': got 'stage'.`

## Audit Evidence
- Triple-audit package:
  - [package.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-web-metadata-lane-contract-audit-20260522/package.md:1)
- Triple-audit round summary:
  - [round-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-web-metadata-lane-contract-audit-20260522/triple-audit-20260522T000100Z/round-01/round-summary.md:1)
- Claude CLI result:
  - [claude-cli-result.json](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-web-metadata-lane-contract-audit-20260522/claude-cli-result.json:1)
- Audit adjudication summary:
  - all reviewers converged on `Option 1`, narrowly bounded
  - no reviewer recommended `SHA-only`
  - no reviewer recommended adding a new main-only topology as the simplest safe path

## Local Implementation Evidence
- Branch:
  - `fix/main-promotion-web-metadata-contract-20260522`
- Changed scripts:
  - [.github/scripts/check_web_flutter_metadata.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/check_web_flutter_metadata.sh:1)
  - [.github/scripts/check_deployed_web_provenance.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/check_deployed_web_provenance.sh:1)
  - [.github/scripts/prove_web_metadata_main_contract.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/prove_web_metadata_main_contract.sh:1)
  - [.github/scripts/verify_environment_ci.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/verify_environment_ci.sh:1)
- Local verification:
  - `bash -n .github/scripts/check_web_flutter_metadata.sh .github/scripts/check_deployed_web_provenance.sh .github/scripts/prove_web_metadata_main_contract.sh .github/scripts/verify_environment_ci.sh` passed.
  - `bash .github/scripts/prove_web_metadata_main_contract.sh` passed with:
    - positive main case: `source_branch=stage` accepted when SHA and host match.
    - positive descendant case: short descendant `flutter_git_sha` accepted when local Flutter history proves ancestry.
    - negative stage case: `source_branch != stage` still fails.
    - negative main cases: incompatible `flutter_git_sha` and incompatible host still fail.
    - deployed runtime cases: `source_branch=stage` accepted for `main` only when deployed SHA and host match; deployed SHA/host mismatches still fail.
  - `bash .github/scripts/verify_environment_ci.sh` passed.
  - `VERIFY_ENV_FORCE_GREP_FALLBACK=1 bash .github/scripts/verify_environment_ci.sh` passed.
- Current candidate evidence:
  - `HEAD`, `origin/dev`, and `origin/stage` pin `web-app` SHA `5ec8a82286e968de27bbfcf48a594387ceb83385`.
  - That artifact has `build_metadata.source_branch=stage`, `build_metadata.flutter_git_sha=1b780c30`, and `window.__LANDLORD_HOST__="belluga.app"`.
  - Current pinned `flutter-app` `config/defines/main.json` has `LANDLORD_DOMAIN=https://booraagora.com.br`.
  - After this implementation, `bash .github/scripts/check_web_flutter_metadata.sh main` no longer fails on `source_branch`; it fails closed on:
    - `ERROR: host injection mismatch for lane 'main': web-app __LANDLORD_HOST__='belluga.app', expected 'booraagora.com.br' from config/defines/main.json`.

## Local CI-Equivalent Suite Matrix

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / metadata contract proof` | The blocker was a shell metadata compatibility contract. | `bash .github/scripts/prove_web_metadata_main_contract.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/prove_web_metadata_main_contract.sh` | Proved `source_branch != main` is diagnostic-only when SHA and host gates pass, while incompatible SHA/host still fail closed. |
| `belluga_now_docker / orchestration invariant guard` | CI preflight owns the metadata compatibility gate. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/verify_environment_ci.sh` | Passed after the metadata guard was reframed. |
| `belluga_now_docker / shell syntax` | Touched scripts are shell entrypoints. | `bash -n .github/scripts/check_web_flutter_metadata.sh .github/scripts/check_deployed_web_provenance.sh .github/scripts/prove_web_metadata_main_contract.sh .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash -n .github/scripts/check_web_flutter_metadata.sh .github/scripts/check_deployed_web_provenance.sh .github/scripts/prove_web_metadata_main_contract.sh .github/scripts/verify_environment_ci.sh` | Parse gate passed for all touched scripts. |
| `belluga_now_docker / authoritative main promotion gate` | This TODO was a Docker `main` blocker. | GitHub Actions `Orchestration CI/CD` run `26320227463` | `Production-Ready` | `passed` | run `26320227463`, job `Preflight Validation`, step `Validate web/flutter metadata compatibility`; job `Deploy Production` | Main preflight and production deploy completed green after lane-resolved runtime topology and first-tuple bootstrap. |

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Replace the current `source_branch == lane` blocker with the audited `main` compatibility contract. | code + deterministic proof | `.github/scripts/check_web_flutter_metadata.sh`; `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | The proof accepts diagnostic `source_branch=stage` for `main` only when SHA and host gates pass. |
| `SCOPE-02` | Scope | Preserve hard failure when the evaluated web artifact is incompatible with the pinned `flutter-app` SHA. | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Negative incompatible Flutter SHA case remains fail-closed. |
| `SCOPE-03` | Scope | Preserve or add hard failure when the evaluated web artifact is incompatible with `config/defines/main.json` / `LANDLORD_DOMAIN` / injected host expectations. | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Negative `main` host mismatch case remains fail-closed. |
| `SCOPE-04` | Scope | Align the artifact source evaluated by `.github/scripts/check_web_flutter_metadata.sh` with the actual pinned runtime source; the current candidate now fails closed when that source does not satisfy `main` host expectations. | code + proof | `.github/scripts/check_web_flutter_metadata.sh`; `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | The old candidate failed on real host mismatch instead of diagnostic branch provenance. |
| `SCOPE-05` | Scope | Re-run Docker `stage -> main` preflight and confirm the metadata gate passes. | remote CI proof | GitHub Actions run `26320227463`, `Preflight Validation` job, `Validate web/flutter metadata compatibility` step | GitHub Actions `main` | passed | Main preflight passed after the lane-resolved runtime topology corrected the effective `main` artifact. |
| `DOD-01` | Definition of Done | Docker PR `#737` no longer fails `Validate web/flutter metadata compatibility` for `main`. | remote CI proof | Docker PR `#737`; GitHub Actions run `26320227463`, `Preflight Validation` job, step `Validate web/flutter metadata compatibility` | GitHub Actions `main` | passed | The blocker was removed and the final `main` promotion run passed the same web/flutter metadata step. |
| `DOD-02` | Definition of Done | Docker `main` preflight fails closed on real incompatibility: | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | The local proof keeps fail-closed SHA and host mismatch behavior. |
| `DOD-03` | Definition of Done | incompatible `flutter_git_sha` | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Negative incompatible Flutter SHA case completed and the proof asserted fail-closed behavior. |
| `DOD-04` | Definition of Done | incompatible effective `main` host/domain | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Negative host mismatch case completed and the proof asserted fail-closed behavior. |
| `DOD-05` | Definition of Done | `source_branch` remains visible only as diagnostic provenance and is no longer the acceptance gate for `main`. | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Positive `source_branch != main` case passes when hard SHA/host gates pass. |
| `DOD-06` | Definition of Done | Docker `main` promotion can continue past preflight without reintroducing a false-red provenance contract or a false-green host/domain mismatch. | remote CI proof | GitHub Actions run `26320227463` | GitHub Actions `main` production deploy | passed | Preflight and deploy production completed green with provenance and mutation hard-block intact. |
| `VAL-01` | Validation Steps | Capture the exact artifact source currently being read by `.github/scripts/check_web_flutter_metadata.sh` for the Docker `main` candidate. | investigation evidence | Local Implementation Evidence section in this TODO | local candidate inspection | passed | Captured the then-current pinned web artifact SHA, branch provenance, Flutter SHA, and host. |
| `VAL-02` | Validation Steps | Prove the negative case: incompatible `flutter_git_sha` still fails. | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Negative incompatible Flutter SHA case covered. |
| `VAL-03` | Validation Steps | Prove the negative case: incompatible effective `main` host/domain still fails. | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Negative host/domain case covered. |
| `VAL-04` | Validation Steps | Prove the positive case: `source_branch != main` no longer blocks by itself when the hard compatibility gates pass. | deterministic proof | `bash .github/scripts/prove_web_metadata_main_contract.sh` | local CI-equivalent | passed | Positive diagnostic-branch case covered. |
| `VAL-05` | Validation Steps | Re-run the authoritative Docker `main` promotion gate and record the passing evidence for the compatibility step. | remote CI proof | GitHub Actions run `26320227463`, `Preflight Validation` job | GitHub Actions `main` | passed | The authoritative main preflight passed and production deploy followed successfully. |

## References
- `belluga/belluga_now_docker#737`
- `belluga/belluga_now_front#331`
- `belluga/belluga_now_web#354`
- `belluga/belluga_now_backend#219`
- [.github/scripts/check_web_flutter_metadata.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/check_web_flutter_metadata.sh:1)
- [flutter-app/.github/workflows/web-artifact-publish.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/.github/workflows/web-artifact-publish.yml:262)
- [.github/workflows/submodule-sync-pr.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/workflows/submodule-sync-pr.yml:1)
