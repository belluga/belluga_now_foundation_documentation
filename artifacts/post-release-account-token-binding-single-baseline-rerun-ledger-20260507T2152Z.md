# RR-AUTH-03 Single-Baseline Rerun Ledger - 20260507T2152Z

## Scope

- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Round-01 blockers resolved:** `RR-AUTH-03-ELEGANCE-001`, `RR-AUTH-03-ELEGANCE-002`, and the current-baseline `VDA-005` attribution objection.
- **Authoritative validation tree:** synthetic `/tmp/rr-auth-03-clean` inside the canonical `belluga_now_docker-app-1` container.
- **Included-file manifest:** `/home/elton/Dev/repos/belluga-ecosystem/worktrees/rr-auth-03-clean-env-20260507T182803Z/RR_AUTH_03_INCLUDED_FILES.txt`
- **Helper overlay source only:** `/home/elton/Dev/repos/belluga-ecosystem/worktrees/rr-auth-03-clean-env-20260507T182803Z/laravel-app`
- **Closure posture:** the round-01 triple-audit single-baseline objection is resolved. This ledger supersedes the earlier external clean-tree rerun as the closure-grade RR-AUTH-03 attribution record.

## Baseline Reconstruction

| Surface | Status | Evidence |
| --- | --- | --- |
| `/tmp/rr-auth-03-clean` bootstrap | `rebuilt_from_current_head` | The synthetic tree was rebuilt from `git -C laravel-app archive HEAD`, so the validation baseline starts from the current RR-AUTH-03 code instead of the stale external helper tree. |
| Runtime/bootstrap dependencies | `copied_from_canonical_container` | `vendor`, `bootstrap/cache`, `storage`, `.github`, and `.env.testing` were copied from `/var/www` in `belluga_now_docker-app-1` so the synthetic tree boots under the canonical compose topology without changing tracked repository ownership. |
| RR-AUTH-03 changed surfaces | `overlay_synced_from_manifest` | Only files listed in `RR_AUTH_03_INCLUDED_FILES.txt` were overlaid after the helper tree was synced from principal, keeping the bounded surface explicit while preserving one authoritative validation tree. |
| External helper tree | `staging_only_not_authoritative` | The `rr-auth-03-clean-env-20260507T182803Z` tree remains a helper overlay source only. It is not the closure-grade baseline because it predates the issuer-boundary correction and the direct outside-caller/event-route regressions. |

## Validation Evidence

| Command / Lane | Outcome |
| --- | --- |
| `docker compose exec -T app bash -lc 'cd /tmp/rr-auth-03-clean && php scripts/architecture_guardrails.php'` | `passed`: `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| `docker compose exec -T app bash -lc 'cd /tmp/rr-auth-03-clean && php artisan test tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php --filter=\"test_validated_issuer_context_cannot_be_opened_outside_token_service|test_agenda_accepts_current_tenant_account_token|test_account_event_candidates_route_accepts_persisted_bearer_token_bound_to_current_account|test_account_event_candidates_route_rejects_persisted_bearer_token_bound_to_another_account|test_account_event_candidates_route_rejects_persisted_bearer_token_without_candidate_ability\"'` | `passed`: `5 passed`, `11 assertions`, `9.00s`; log: `/tmp/rr-auth-03-clean-focused-issuer-event-20260507T2132Z.log` |
| `docker compose exec -T app bash -lc 'cd /tmp/rr-auth-03-clean && php artisan test --fail-on-warning --display-warnings'` | `passed`: `1368 passed`, `6373 assertions`, `1019.08s`; log: `/tmp/rr-auth-03-clean-full-suite-20260507T2133Z.log` |

Both `php artisan test` reruns used the same explicit testing env bundle (`APP_ENV=testing`, `QUEUE_CONNECTION=sync`, `APP_URL=http://nginx`, `APP_HOST=nginx`, `APP_KEY=...`, `APP_FAKER_LOCALE=pt_BR`, landlord/tenant Mongo URIs, and the matching `DB_DATABASE*` variables) to keep the focused issuer/event slice and the full Laravel CI-equivalent suite on the same runtime baseline.

## Adjudication Notes

- Direct diffs against the older external clean tree showed that it lacked `assertValidatedAccountScopedTokenIssuerCaller()` in `laravel-app/app/Models/Tenants/AccountUser.php`.
- The same tree also lacked `test_validated_issuer_context_cannot_be_opened_outside_token_service` and `test_account_event_candidates_route_rejects_persisted_bearer_token_without_candidate_ability` in `laravel-app/tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php`.
- Because the earlier `20260507T194907Z` rerun used that external tree, it no longer qualified as the sole closure-grade baseline after the `20260507T2029Z` and `20260507T2059Z` corrections landed.
- The synthetic `/tmp/rr-auth-03-clean` tree replays the focused issuer/event proof and the full Laravel CI-equivalent suite against one frozen implementation state, resolving the round-01 single-baseline objection without reopening a runtime authorization defect.

## Raw Log References

- Focused issuer/event rerun: `/tmp/rr-auth-03-clean-focused-issuer-event-20260507T2132Z.log`
- Full CI-equivalent rerun: `/tmp/rr-auth-03-clean-full-suite-20260507T2133Z.log`

## Next Gate

Record the round-01 triple-audit resolution as `resolved`, refresh the bounded package/TODO to point at this ledger, and open round 02 from the updated RR-AUTH-03 package.
