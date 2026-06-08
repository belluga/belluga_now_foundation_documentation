# TODO (Bugfix): Direct Invite Push Scheduled Without Delivery

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Production-Ready. Historical archival catch-up on `2026-06-08` confirmed the promoted Laravel source SHA and Docker gitlink sync commit are ancestors of `origin/main`; any new push regression from this point must open a separate TODO instead of reopening this archived slice.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to move already promoted TODOs to `completed` once code-promotion ancestry was confirmed.
- **Approval scope:** documentation-only archival closeout for this bounded slice after confirming the recorded promoted source and Docker commits already reached `origin/main`.

## Context
- Em produção, convites diretos geram documentos em `push_messages`, mas eles permanecem indefinidamente em `scheduled`.
- Não há `push_delivery_logs`, o que indica que o pipeline morre antes da entrega efetiva ou retorna silenciosamente sem materializar destino/resultado.

## Contract Boundary
- Este TODO cobre o caminho de push de convite direto até sair de `scheduled` com evidência material de entrega, rejeição ou skip explícito.
- Ele cobre autoring do invite push, resolução de destinatários, job de envio e logging/terminal state.
- Ele não cobre melhorias amplas de push fora do caso de convite direto.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `main-absorbed`, `stage-green`, `Bugfix`, `Laravel`, `Push`, `Invites`, `Historical-Archival-Catch-Up`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-bugfix-direct-invite-push-scheduled-without-delivery.md`; any newly discovered regression must open a new TODO.
- **Promotion lane path:** `dev -> stage -> main`
- **Post-commit/push status:** `completed`

## Evidence Snapshot
- O `SendPushMessageJob` tinha múltiplos `return` silenciosos que deixavam a `PushMessage` indefinidamente em `scheduled`.
- Esses branches agora gravam estado terminal explícito em `delivery.last_terminal_state` e atualizam `status` para `failed` ou `skipped`, em vez de sumir sem rastro.
- O branch de provider que aceita `0` destinatários agora também fecha em `failed`, preservando os `PushDeliveryLog` de resposta quando eles existem.
- O branch de `requestedUnits === 0` agora fecha em `failed/no_targets`, que é compatível com o sintoma de produção de “sem logs e preso em scheduled”.
- O fluxo específico de convite direto (`invite_received`) foi exercitado localmente com job real e transporte fake aceitando entrega; o `PushMessage` foi para `sent` e materializou `PushDeliveryLog`.
- A prova E2E real no tenant local `guarappari` confirmou que o convite direto sai do HTTP, atravessa o worker e materializa `PushDeliveryLog` vindo do FCM; a resposta real observada foi `NOT_FOUND: Requested entity was not found.`
- O envio assíncrono de `invite_received` agora invalida automaticamente tokens com `error_code=NOT_FOUND`, igual ao caminho manual de envio, evitando retries inúteis em tokens mortos.
- Com dois tokens reais fornecidos pelo operador, o smoke `POST /api/v1/invites` no `guarappari` local gerou duas `PushMessage` `sent`, com `provider_message_id` real do FCM:
  - `projects/guarappari/messages/0:1779304069872213%fcca9f36fcca9f36`
  - `projects/guarappari/messages/0:1779304071186150%fcca9f36fcca9f36`

## Promotion Evidence

| Workstream | Promotion branch | PR / merge | Final `dev` SHA | Validation evidence |
| --- | --- | --- | --- | --- |
| `laravel-app` invite push hardening | `fix/direct-invite-push-and-landlord-password-ops-20260520` | backend PR `#214` merged into `dev` | `805229da74a4a5aa201a5093b8761b49c1851ae1` | Local container suite passed: `LandlordPasswordSetCommandTest`, `InvitesFlowTest`, `PushMessageFlowTest`; local FCM/device proof closed with accepted provider ids, foreground reaction, and background notification delivery; PR run `26188497840` green; post-merge `dev` run `26188849916` green. |
| `belluga_now_docker` gitlink sync | `bot/next-version` | docker PR `#726` merged into `dev` | `03366502be5ec2f4efd37495953dd02b1fc843e6` | PR run `26189230345` green after guarded `bot/next-version -> dev` replay; post-merge `dev` run `26189299739` green. |

## Stage Promotion Evidence - 2026-05-25
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `laravel-app` accumulated lane | PR `belluga/belluga_now_backend#221` promoted the current `dev` package, including this earlier merged push hardening, to `stage`. | `stage=8fd46a8e50126f3a42f1b34f9400a1307ea09355`; run `26384653562` success. |
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
- [x] Convite direto elegível não fica preso para sempre em `scheduled`.
- [x] O job registra delivery log ou estado terminal explícito quando não há destinatário elegível.
- [x] Falhas/salidas antecipadas deixam rastreabilidade suficiente para operação.
- [x] Há teste cobrindo o branch real que hoje prende a mensagem.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / direct invite push focused suite` | Job status transitions, direct invite push authoring, and delivery logging changed in Laravel. | `LandlordPasswordSetCommandTest`, `InvitesFlowTest`, `PushMessageFlowTest` through the local container suite. | lane promotion | passed | Recorded in Promotion Evidence: backend PR `#214`; local container suite passed before dev merge. | Focused local evidence was later carried through stage by backend PR `#221`. |
| `belluga_now_docker / stage deploy verification` | Runtime stage must carry the Laravel gitlink that includes the direct-invite push hardening. | Stage Orchestration CI/CD run. | stage promotion | passed | Docker stage run `26385254151`; completion guard `Overall outcome: go`. | Remote deploy/smoke evidence verifies the promoted runtime lane. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | Definition of Done | Convite direto elegível não fica preso para sempre em `scheduled`. | test + runtime | Local direct-invite push flow exercised `invite_received` through the job; provider accepted flow reached `sent`. | Laravel local + FCM/device proof | passed | Evidence snapshot records two real provider message ids for GuarAppari local. |
| `DOD-02` | Definition of Done | O job registra delivery log ou estado terminal explícito quando não há destinatário elegível. | test | `PushMessageFlowTest` and job hardening branches recorded terminal `failed` or `skipped` states. | Laravel local | passed | Silent returns were replaced by explicit terminal state updates. |
| `DOD-03` | Definition of Done | Falhas/salidas antecipadas deixam rastreabilidade suficiente para operação. | code + test | `delivery.last_terminal_state`, `failed/no_targets`, and delivery log preservation recorded in Evidence Snapshot. | Laravel local | passed | Early exits no longer leave messages indefinitely in `scheduled`. |
| `DOD-04` | Definition of Done | Há teste cobrindo o branch real que hoje prende a mensagem. | test | Local container suite with `InvitesFlowTest` and `PushMessageFlowTest` passed before PR `#214`; stage run `26384653562` later passed. | Laravel local + CI | passed | Regression is represented in focused tests and accumulated stage CI. |

## TODO Closeout Disposition
- **Completed path:** `foundation_documentation/todos/completed/TODO-bugfix-direct-invite-push-scheduled-without-delivery.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming the promoted Laravel source SHA and Docker gitlink sync commit are ancestors of `origin/main`.
- **Historical verification debt:** no separate main-specific runtime packet was recorded in this TODO; this closeout preserves that absence explicitly instead of keeping promotion artificially open.
- **Reopen rule:** any new direct-invite push regression must open a new TODO.
