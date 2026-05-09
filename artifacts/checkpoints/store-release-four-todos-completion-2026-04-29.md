# Store Release Four-TODO Completion Checkpoint

## Artifact Identity
- **Artifact type:** `orchestration_checkpoint_manifest`
- **Checkpoint status:** `validated_local_checkpoint`
- **Created:** `2026-04-29`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Authority boundary:** governing TODOs and canonical module docs remain authoritative.

## Scope
| ID | Governing TODO | Included in checkpoint | Delivery stage after checkpoint |
| --- | --- | --- | --- |
| `T1-W2A` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` | yes | `Local-Validated-With-Explicit-Runtime-Waivers` |
| `T2-OTP` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md` | yes | `Local-Validated-With-Explicit-Runtime-Waivers` |
| `T3-SOCIAL` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md` | yes | `Local-Complete-Guard-Passed-ADB-Automated-Smokes-Passed` |
| `T4-METRICS` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-funnel-metrics-validation.md` | yes | `Execution-Validated`; post-release sink/query hardening split out on 2026-05-03 |

## Repository Checkpoint SHAs
| Repository | Branch | Commit SHA | Push target | Included | Notes |
| --- | --- | --- | --- | --- | --- |
| `belluga_now_docker` | `orchestration/store-release-four-todos-completion-20260429` | `a07ffa2` | `origin/orchestration/store-release-four-todos-completion-20260429` | yes | Playwright OTP boundary/source updates only; `web-app` generated bundle excluded. |
| `flutter-app` | `orchestration/store-release-four-todos-completion-20260429` | `43d683c9` | `origin/orchestration/store-release-four-todos-completion-20260429` | yes | OTP layout replacement, button loading overflow guard, and focused tests. |
| `laravel-app` | `orchestration/store-release-four-todos-completion-20260429` | `4ad3925` | `origin/orchestration/store-release-four-todos-completion-20260429` | no | No local source changes in this checkpoint. |
| `foundation_documentation` | `docs/store-release-four-todos-completion-20260429` | `self-docs-checkpoint-commit` | `origin/docs/store-release-four-todos-completion-20260429` | yes | TODO evidence, orchestration plan evidence, and this manifest; verify exact hash with `git rev-parse HEAD` after commit/push. |
| `web-app` | n/a | n/a | n/a | no | Derived Flutter web bundle; never committed outside promotion/deploy ownership. |

## Evidence Summary
| Area | Evidence | Status |
| --- | --- | --- |
| `completion guards` | T1, T2, T3, and T4 `todo_completion_guard.py` returned `Overall outcome: go`; T1/T2/T4 used explicit approved waivers where runtime/external evidence is unavailable. | passed |
| `orchestration guards` | `orchestration_plan_completion_guard.py --require-approved` and `orchestration_delivery_guard.py --require-approved --allow-waivers` returned `Overall outcome: go`. | passed |
| `tests` | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart test/infrastructure/repositories/auth_repository_signup_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart` passed with `90/90`; focused OTP rerun passed with `8/8`. | passed |
| `analyzer` | `fvm dart analyze --format machine` exited `0`. | passed |
| `playwright source/shards` | `node --check` passed; navigation policy guard passed; mutation shard list/validate selected the two OTP Auth tests; readonly list selected `OTP-WEB-BOUNDARY-01`. | passed |
| `runtime/browser/device` | Direct ADB `fvm flutter drive ... feature_auth_login_navigates_to_intended_route_test.dart -d 192.168.15.9:5555` passed. Official mutation runner hard-blocked without `NAV_ADMIN_EMAIL` and `NAV_ADMIN_PASSWORD`. | passed-with-waiver |
| `build/publish freshness` | `bash scripts/build_web.sh ../web-app dev` rebuilt the generated web bundle from the reconciliation branch state; generated output remains excluded. | passed |

## Exclusions / Dirty Surfaces
| Path / Repository | Reason Excluded | Follow-up |
| --- | --- | --- |
| `../web-app` | Generated Flutter web bundle. | Leave out of source checkpoint; promotion/deploy lane owns artifact movement. |
| `laravel-app` | No source changes in this checkpoint. | Push branch only if branch recoverability is required; no commit needed. |
| External Playwright mutation runtime | Missing `NAV_ADMIN_EMAIL` and `NAV_ADMIN_PASSWORD`. | Rerun canonical mutation lane when runtime credentials are available. |
| External telemetry sink/query | No sink/query access in local environment. | Required before `Production-Ready`; local checkpoint records approved waiver only. |
| Real store/deferred-link proof | External Android store/deferred behavior not proven in local checkpoint. | Required in promotion/runtime readiness lane. |

## Branch Lifecycle Decision
- **Next exact step:** push source checkpoint branches, then use promotion/readiness lane for external runtime evidence and promotion.
- **Same-branch continuation allowed:** no for new unrelated feature work.
- **Why:** the approved four-TODO orchestration plan is locally validated; remaining items are runtime/provider/readiness proof, not new implementation.

## Notes
- This manifest records recoverability state only. It does not convert runtime waivers into `Production-Ready`.
- Zero backward compatibility is the launch assumption for invites, friends, and favorites in this store-release social scope.
