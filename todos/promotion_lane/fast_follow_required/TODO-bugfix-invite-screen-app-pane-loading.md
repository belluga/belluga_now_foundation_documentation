# TODO (Bugfix): Invite Screen App Pane Loading

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Lane-promoted through `stage` on `2026-05-25`. The `APP` pane loading hardening is merged through `dev` and `stage`; the deeper invite-match performance follow-up was handled by the separate inviteables projection/cache TODO, and any new app-pane loading regression must open a separate TODO.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- A lista de usuários na tela de convites demora de forma anormal.
- A evidência atual aponta que a abertura do pane `APP` aguarda trabalho caro de import/match de contatos antes de publicar os inviteables do backend.

## Contract Boundary
- Este TODO cobre apenas o loading da tela de convites no pane `APP`.
- Ele cobre controller/repository/testes necessários para não bloquear o primeiro render útil em trabalho de contatos que não é requisito do pane atual.
- Ele não cobre push, SSE, ou redesign visual da tela.

## Delivery Status Canon
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `stage-green`, `Bugfix`, `Flutter`, `Invites`, `Performance`
- **Next exact step:** keep this slice on the promotion lane only for a future explicitly approved `main` promotion/archive decision.
- **Promotion lane path:** `dev -> stage -> main`

## Evidence Snapshot
- O `init()` do controller passou a disparar o warmup de contatos em background em vez de bloquear o fetch dos inviteables do backend.
- O warmup ainda hidrata cache/imported matches e republica o estado quando termina, mas isso não segura mais o primeiro render útil do pane `APP`.
- A montagem de `localContactDisplaysByHash` deixou de recalcular hash local quando os recipients do backend não trazem `contact_hash`, reduzindo trabalho desnecessário no caminho quente.
- O backend ainda pode ter custo material em import frio/stale de contatos, mas esse custo saiu do caminho crítico da lista de usuários do `APP`.

## Promotion Evidence

| Workstream | Promotion branch | PR / merge | Final `dev` SHA | Validation evidence |
| --- | --- | --- | --- | --- |
| `flutter-app` invite app-pane loading | `fix/invite-screen-app-pane-loading-20260520` | frontend PR `#323` merged into `dev` | `90040672081b5f2dc573ab5066231aeace0a8e33` | Local focused test passed: `invite_share_screen_controller_test.dart`; PR run `26188498596` green; post-merge `dev` run `26189010439` green. |
| `belluga_now_docker` gitlink sync | `bot/next-version` | docker PR `#726` merged into `dev` | `03366502be5ec2f4efd37495953dd02b1fc843e6` | PR run `26189230345` green after guarded `bot/next-version -> dev` replay; post-merge `dev` run `26189299739` green. |

## Stage Promotion Evidence - 2026-05-25
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` accumulated lane | PR `belluga/belluga_now_front#341` promoted the current `dev` package, including this earlier merged app-pane loading hardening, to `stage`. | `stage=a718451812b574b1a981cdb645e49b2b4a1632c2`; run `26384657417` success. |
| `belluga_now_docker` runtime lane | PR `#752` carried the current Flutter gitlink into Docker `dev`; PR `#754` promoted Docker `dev -> stage`. | `stage=bea62b8d18ab620b9bb9977be9f867bfa9b735db`; run `26385254151` success. |
| Completion guard | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-laravel --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front --laravel-repo belluga/belluga_now_backend` | `Overall outcome: go`; Docker stage `flutter-app` gitlink exact at `a718451812b574b1a981cdb645e49b2b4a1632c2`. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion PRs `front#341` and `docker#754` | Copilot P1/P2 and CI blocker preflight for the accumulated Flutter package. | passed | Front Copilot finding `3296103125` fixed by PR `#342`; stage runs `26384657417` and `26385254151` passed. | resolved | All P1/P2 findings were fixed before stage merge; completion guard returned `Overall outcome: go`. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion lane and TODO governance | Checked source-owned fixes, derived `web-app` boundary, Docker gitlink path through `bot/next-version`, and TODO threshold/archive hygiene. | passed | `github-stage-promotion-orchestrator`; `github_promotion_completion_guard.sh`; TODO directory reconciliation. | no findings | Preserved source-owned fixes, did not manually promote `web-app`, promoted gitlinks through lane-owned Docker PRs, and kept this TODO in `promotion_lane` because its recorded path still includes `main`. |

## Definition of Done
- [x] O pane `APP` não fica bloqueado por import/match de contatos para mostrar inviteables.
- [x] A tela continua usando cache/matches existentes quando disponíveis.
- [x] Refresh/import de contatos vira trabalho de background ou fica restrito ao pane de contatos.
- [x] Há teste cobrindo o comportamento não bloqueante.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / invite app-pane focused suite` | The controller publication/loading behavior changed in Flutter. | `invite_share_screen_controller_test.dart` focused test suite. | lane promotion | passed | Recorded in Promotion Evidence: frontend PR `#323`; focused local test passed before dev merge. | Focused local evidence was later carried through stage by frontend PR `#341`. |
| `belluga_now_docker / stage deploy verification` | Runtime stage must carry the Flutter gitlink that includes the app-pane loading hardening. | Stage Orchestration CI/CD run. | stage promotion | passed | Docker stage run `26385254151`; completion guard `Overall outcome: go`. | Remote deploy/smoke evidence verifies the promoted runtime lane. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | Definition of Done | O pane `APP` não fica bloqueado por import/match de contatos para mostrar inviteables. | test | `invite_share_screen_controller_test.dart` passed before frontend PR `#323` merged to `dev`. | Flutter local | passed | Controller now warms contacts in background instead of blocking first useful app-pane render. |
| `DOD-02` | Definition of Done | A tela continua usando cache/matches existentes quando disponíveis. | test + device | Focused controller evidence plus ADB follow-up `feature_invite_share_cold_cache_persistence_test.dart` on `192.168.15.9:5555`. | Flutter local + Android device | passed | Existing cache/match state remains available while background refresh runs; the later inviteables projection/cache TODO validated repeated-entry/cold-cache behavior on device. |
| `DOD-03` | Definition of Done | Refresh/import de contatos vira trabalho de background ou fica restrito ao pane de contatos. | test + code | Focused controller evidence in PR `#323`; later inviteables projection/cache TODO hardened the deeper path. | Flutter local | passed | App pane no longer waits for contact import/match. |
| `DOD-04` | Definition of Done | Há teste cobrindo o comportamento não bloqueante. | test | `invite_share_screen_controller_test.dart`; stage run `26384657417` later passed. | Flutter local + CI | passed | The accumulated Flutter stage package includes the focused regression. |
