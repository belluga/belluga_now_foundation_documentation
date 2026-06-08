# TODO (Bugfix): Landlord Admin Password Ops Reset Path

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Production-Ready. Historical archival catch-up on `2026-06-08` confirmed the promoted Laravel source SHA and Docker gitlink sync commit are ancestors of `origin/main`; any newly discovered landlord-auth follow-up must open a separate TODO instead of reopening this archived slice.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to move already promoted TODOs to `completed` once code-promotion ancestry was confirmed.
- **Approval scope:** documentation-only archival closeout for this bounded slice after confirming the recorded promoted source and Docker commits already reached `origin/main`.

## Context
- O backend landlord já expõe update/reset de senha, mas o fluxo operacional real continua ruim para teste manual.
- A UI landlord atual não oferece um caminho confiável de autoatendimento para troca/reset.
- Quando o operador perde a senha atual, o time fica dependente de tinker/ad hoc DB edits, o que é frágil e ruim para validação manual.

## Contract Boundary
- Este TODO cobre apenas o caminho operacional confiável para redefinir a senha de um landlord por email.
- Ele cobre comando determinístico, sincronização do credential canônico, invalidação de tokens antigos e teste automatizado.
- Ele não cobre redesign de UX de reset/troca de senha no Flutter landlord.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `main-absorbed`, `stage-green`, `Bugfix`, `Laravel`, `Landlord`, `Auth`, `Ops`, `Historical-Archival-Catch-Up`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-bugfix-landlord-admin-password-ops-reset-path.md`; any newly discovered regression must open a new TODO.
- **Promotion lane path:** `dev -> stage -> main`
- **Post-commit/push status:** `completed`

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

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `laravel-app` source lane ancestry | `git -C laravel-app merge-base --is-ancestor 805229da74a4a5aa201a5093b8761b49c1851ae1 origin/main` | exit `0`; the promoted backend source SHA is already contained by `origin/main`. |
| `belluga_now_docker` runtime ancestry | `git merge-base --is-ancestor 03366502be5ec2f4efd37495953dd02b1fc843e6 origin/main` | exit `0`; the recorded Docker gitlink sync commit is already contained by `origin/main`. |
| Archival decision | Explicit `2026-06-08` user request to move already promoted TODOs to `completed` after code-promotion investigation. | Documentation-only closeout approved. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new code promotion package is being opened; confirm this move only reconciles a stale TODO with code already promoted to `origin/main`. | `n/a` | `git -C laravel-app merge-base --is-ancestor 805229da74a4a5aa201a5093b8761b49c1851ae1 origin/main`; `git merge-base --is-ancestor 03366502be5ec2f4efd37495953dd02b1fc843e6 origin/main` | `none` | No fresh PR/Copilot surface exists for this documentation-only move; historical stage preflight remains recorded in the promotion evidence above. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion lane and TODO governance | Checked source-owned fixes, derived `web-app` boundary, Docker gitlink path through `bot/next-version`, and TODO threshold/archive hygiene. | passed | `github-stage-promotion-orchestrator`; `github_promotion_completion_guard.sh`; `origin/main` ancestry checks on `2026-06-08`. | no findings | Source-owned fixes stayed intact, Docker ancestry to `origin/main` is explicit, and the TODO can now leave `promotion_lane` as a historical archival catch-up. |

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The file is being archived after the implementation/promotion wave already finished. | Explicit approval scope, delivery-gate provenance, and truthful closeout language. | Pretending a missing main packet or runtime packet exists when it was not recorded. | Add approval, rules ingestion, and archival main-ancestry evidence only. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | The source and Docker commits already crossed the final lane threshold. | Keep archival evidence tied to real source/Docker ancestry and recorded stage promotion proof. | Leaving a code-promoted slice stranded in `promotion_lane/`. | Move the TODO to `completed` after ancestry verification. |

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

## TODO Closeout Disposition
- **Completed path:** `foundation_documentation/todos/completed/TODO-bugfix-landlord-admin-password-ops-reset-path.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming the promoted Laravel source SHA and Docker gitlink sync commit are ancestors of `origin/main`.
- **Historical verification debt:** no separate main-specific runtime packet was recorded in this TODO; this closeout preserves that absence explicitly instead of keeping promotion artificially open.
- **Reopen rule:** any new landlord password ops regression must open a new TODO.
