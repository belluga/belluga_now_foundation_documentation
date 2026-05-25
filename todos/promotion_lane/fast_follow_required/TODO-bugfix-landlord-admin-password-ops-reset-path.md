# TODO (Bugfix): Landlord Admin Password Ops Reset Path

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Lane-promoted through `stage` on `2026-05-25`. The landlord password ops reset path is merged through `dev` and `stage`, with green stage deploy and exact Docker `laravel-app` gitlink alignment; any newly discovered landlord-auth follow-up must open a separate TODO instead of reopening this slice.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- O backend landlord já expõe update/reset de senha, mas o fluxo operacional real continua ruim para teste manual.
- A UI landlord atual não oferece um caminho confiável de autoatendimento para troca/reset.
- Quando o operador perde a senha atual, o time fica dependente de tinker/ad hoc DB edits, o que é frágil e ruim para validação manual.

## Contract Boundary
- Este TODO cobre apenas o caminho operacional confiável para redefinir a senha de um landlord por email.
- Ele cobre comando determinístico, sincronização do credential canônico, invalidação de tokens antigos e teste automatizado.
- Ele não cobre redesign de UX de reset/troca de senha no Flutter landlord.

## Delivery Status Canon
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `stage-green`, `Bugfix`, `Laravel`, `Landlord`, `Auth`, `Ops`
- **Next exact step:** keep this slice on the promotion lane only for a future explicitly approved `main` promotion/archive decision.
- **Promotion lane path:** `dev -> stage -> main`

## Promotion Evidence

| Workstream | Promotion branch | PR / merge | Final `dev` SHA | Validation evidence |
| --- | --- | --- | --- | --- |
| `laravel-app` landlord password ops reset | `fix/direct-invite-push-and-landlord-password-ops-20260520` | backend PR `#214` merged into `dev` | `805229da74a4a5aa201a5093b8761b49c1851ae1` | Local container suite passed: `LandlordPasswordSetCommandTest`, `InvitesFlowTest`, `PushMessageFlowTest`; PR run `26188497840` green; post-merge `dev` run `26188849916` green. |
| `belluga_now_docker` gitlink sync | `bot/next-version` | docker PR `#726` merged into `dev` | `03366502be5ec2f4efd37495953dd02b1fc843e6` | PR run `26189230345` green after guarded `bot/next-version -> dev` replay; post-merge `dev` run `26189299739` green. |

## Stage Promotion Evidence - 2026-05-25
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `laravel-app` accumulated lane | PR `belluga/belluga_now_backend#221` promoted the current `dev` package, including this earlier merged landlord password ops path, to `stage`. | `stage=8fd46a8e50126f3a42f1b34f9400a1307ea09355`; run `26384653562` success. |
| `belluga_now_docker` runtime lane | PR `#753` carried the current Laravel gitlink into Docker `dev`; PR `#754` promoted Docker `dev -> stage`. | `stage=bea62b8d18ab620b9bb9977be9f867bfa9b735db`; run `26385254151` success. |
| Completion guard | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-laravel --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front --laravel-repo belluga/belluga_now_backend` | `Overall outcome: go`; Docker stage `laravel-app` gitlink exact at `8fd46a8e50126f3a42f1b34f9400a1307ea09355`. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion PRs `backend#221` and `docker#754` | Copilot P1/P2 and CI blocker preflight for the accumulated Laravel package. | passed | Backend Copilot finding `3296100523` fixed by PR `#222`; stage runs `26384653562` and `26385254151` passed. | resolved | All P1/P2 findings were fixed before stage merge; completion guard returned `Overall outcome: go`. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion lane and TODO governance | Checked source-owned fixes, derived `web-app` boundary, Docker gitlink path through `bot/next-version`, and TODO threshold/archive hygiene. | passed | `github-stage-promotion-orchestrator`; `github_promotion_completion_guard.sh`; TODO directory reconciliation. | no findings | Preserved source-owned fixes, did not manually promote `web-app`, promoted gitlinks through lane-owned Docker PRs, and kept this TODO in `promotion_lane` because its recorded path still includes `main`. |

## Definition of Done
- [x] Existe um comando operacional simples para redefinir a senha de um landlord por email.
- [x] O comando atualiza o credential canônico de password para todos os emails atuais do usuário.
- [x] O comando não reintroduz `password` / `password_type` legados.
- [x] O comando revoga tokens existentes para forçar novo login manual.
- [x] Há teste automatizado cobrindo sucesso e falha por email inexistente.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / landlord password focused suite` | Landlord password credential reset command changed in Laravel. | `LandlordPasswordSetCommandTest` through the local container suite. | lane promotion | passed | Recorded in Promotion Evidence: backend PR `#214`; local container suite passed before dev merge. | Focused local evidence was later carried through stage by backend PR `#221`. |
| `belluga_now_docker / stage deploy verification` | Runtime stage must carry the Laravel gitlink that includes the landlord password ops path. | Stage Orchestration CI/CD run. | stage promotion | passed | Docker stage run `26385254151`; completion guard `Overall outcome: go`. | Remote deploy/smoke evidence verifies the promoted runtime lane. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | Definition of Done | Existe um comando operacional simples para redefinir a senha de um landlord por email. | test + code | `LandlordPasswordSetCommandTest` passed before backend PR `#214` merged to `dev`. | Laravel local | passed | The deterministic command is the approved operational reset path for this slice. |
| `DOD-02` | Definition of Done | O comando atualiza o credential canônico de password para todos os emails atuais do usuário. | test | `LandlordPasswordSetCommandTest` local container suite evidence. | Laravel local | passed | Canonical credential update is asserted by the focused test. |
| `DOD-03` | Definition of Done | O comando não reintroduz `password` / `password_type` legados. | test | `LandlordPasswordSetCommandTest` local container suite evidence. | Laravel local | passed | Legacy password fields remain out of the reset path. |
| `DOD-04` | Definition of Done | O comando revoga tokens existentes para forçar novo login manual. | test | `LandlordPasswordSetCommandTest` local container suite evidence. | Laravel local | passed | Token revocation is included in the command behavior. |
| `DOD-05` | Definition of Done | Há teste automatizado cobrindo sucesso e falha por email inexistente. | test | `LandlordPasswordSetCommandTest`; stage run `26384653562` later passed. | Laravel local + CI | passed | Success and missing-email paths are covered by the focused test. |
