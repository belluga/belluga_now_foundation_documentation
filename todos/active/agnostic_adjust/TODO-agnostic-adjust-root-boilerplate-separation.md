# TODO: Agnostic Adjust Root Boilerplate Separation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
O usuário quer preparar uma versão do root `belluga_now_docker` adequada para promoção ao Boilerplate, separando com rigor o que é genérico/ecossistema do que é Belluga Now-specific.

O material exportado em `foundation_documentation/todos/active/agnostic_adjust/refactor_project_convention/belluga_now_docker/**` ajuda como referência, mas não é um patch limpo:

- ele propõe uma direção válida de `project overlay`;
- ele diverge do estado atual do root em superfícies-chave;
- ele está defasado em relação ao tooling/browser workflow já canonizado em `tools/flutter/**`;
- ele contém ruído de exportação direta e não pode ser aplicado cegamente.

O usuário aprovou a seguinte ordem operacional:

1. derivar esta frente da branch atual de review `v0.2.0+8`;
2. trabalhar em branch dedicada desta frente;
3. produzir relatório consolidado da diff real;
4. avaliar esse relatório com o usuário;
5. só então implementar a separação aprovada;
6. depois rebasear esta frente quando a base `v0.2.0+8` for conciliada e promovida.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/agnostic-adjust-boilerplate-separation.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** o root `belluga_now_docker` tem fronteira mais clara, menor blast radius e menos contaminação funcional do que o bloco `laravel_app_refactor`, então é a primeira fatia segura para abrir a separação.

## Contract Boundary
- Este TODO define **WHAT** deve ser entregue para a primeira fatia do `agnostic_adjust` no root `belluga_now_docker`.
- `Assumptions Preview` e `Execution Plan` abaixo definem **HOW** Delphi pretende conduzir esta fatia.
- Este TODO inclui obrigatoriamente um checkpoint de relatório e avaliação conjunta antes de qualquer implementação no root.
- Este TODO é **bounded but elastic** apenas dentro da separação do root:
  - docs/onboarding do root;
  - env examples;
  - compose / image naming / entrypoint guards;
  - NGINX root templates e contrato de `project overlay`;
  - CI/workflow/tooling root que suportam esse contrato.
- Se a fatia exigir absorver mudanças funcionais de `laravel-app`, `flutter-app` ou `web-app`, atualizar ou dividir o TODO antes de seguir.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Docker`, `Root-Orchestration`, `Boilerplate-Separation`, `Blocked`
- **Next exact step:** decidir com o usuário se o bloqueio de `verify_environment_ci.sh` será resolvido numa fatia separada de `web-app` pin/update ou se haverá waiver explícito para fechar esta fatia root sem declarar `Local-Implemented`.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `blocked`
- **Why this state now:** a separação root foi materializada e as validações locais diretas passaram, mas o fechamento local ficou bloqueado por um erro objetivo de `verify_environment_ci.sh` em workflows pinados do `web-app`, fora da fatia root aprovada.
- **Exit condition:** o bloqueio do verificador root é resolvido ou explicitamente waivado, e então o TODO pode consolidar `Local-Implemented` ou closeout equivalente.

## Blocker Notes
- `2026-06-08`: `bash .github/scripts/verify_environment_ci.sh` falha porque os workflows pinados em `web-app` ainda referenciam `peter-evans/repository-dispatch@v3` (`web-app/.github/workflows/dispatch-docker-sync.yml:55` e `web-app/.github/workflows/lane-auto-promotion.yml:85`). Corrigir isso exige atualização/promoção de submódulo fora da fatia root aprovada neste TODO.

## Scope
- [x] Materializar localmente, fora do repositório autoritativo, apenas os arquivos exportados relevantes para a fatia root.
- [x] Comparar os arquivos materializados com o estado atual do root `belluga_now_docker` para produzir a diff real desta fatia.
- [x] Classificar cada superfície relevante do root como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`.
- [x] Produzir um relatório consolidado da diff real com impacto, classificação proposta, regressos versus o estado atual, e ambiguidades/riscos.
- [x] Revisar esse relatório com o usuário antes de qualquer implementação.
- [x] Após a revisão e aprovação do usuário, separar no root apenas as superfícies aprovadas desta fatia, mantendo a coerência com o estado atual de `tools/flutter/**`.
- [x] Preservar o contrato de branch desta frente: trabalho isolado agora e rebase posterior após a promoção da base `v0.2.0+8`.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Active Work State Semantics
- `implementation`: the TODO is still gaining or changing implementation/test evidence.
- `review`: local implementation is materially complete, but the TODO remains in `active/` because package-wide review, Copilot-mimic, CI-equivalent, final validation, or explicit promotion-readiness scrutiny is still open.
- `blocked`: execution is paused on an explicit blocker; `Blocker Notes` are mandatory.
- `n/a once moved out of active`: use after the TODO moves to `promotion_lane/` or `completed/`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:reconcile/agnostic-adjust-boilerplate-cutline-20260608`, `foundation_documentation:continue on existing local branch only (currently reconcile/v0.2.0-plus8-cross-stack-20260526; no new branch for this TODO)`
- **Promotion lane path:** `belluga_now_docker: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `belluga_now_docker: dev`, `foundation_documentation: main`
- **Production-ready threshold for this TODO:** `belluga_now_docker: stage/main as applicable`, `foundation_documentation: main`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `root boilerplate cutline and separation` | `reconcile/agnostic-adjust-boilerplate-cutline-20260608 @ working tree` | `<pending>` | `<pending>` | `<pending>` | `blocked on root verifier` |
| `foundation documentation framing and TODO authority` | `existing local branch @ <pending> (currently reconcile/v0.2.0-plus8-cross-stack-20260526; no TODO branch)` | `n/a` | `n/a` | `<pending>` | `pending` |

## Out of Scope
- [ ] Implementar qualquer separação dentro de `laravel_app_refactor` nesta primeira fatia.
- [ ] Absorver mudanças funcionais de `laravel-app`, `flutter-app`, ou `web-app`.
- [ ] Alterar comportamento de produto/rotas por razões de feature em vez de separação agnóstica.
- [ ] Promover agora qualquer alteração para Boilerplate.
- [ ] Tratar o snapshot exportado como patch autoritativo.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** ajustes de docs/env/compose/nginx/entrypoint/CI/tooling do root, criação/adoção explícita de `project overlay`, neutralização de defaults Belluga no root, e a validação correspondente.
- **Must update or split the TODO:** qualquer mudança que force absorção de contratos funcionais de `laravel-app`, reescrita ampla de route semantics, ou triagem detalhada do bloco `laravel_app_refactor`.

## Definition of Done
- [x] `DOD-01` A diff real da fatia root foi produzida sobre o checkout atual e não sobre transporte cego do snapshot exportado.
- [x] `DOD-02` Cada superfície relevante do root foi classificada como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`.
- [x] `DOD-03` O relatório consolidado da diff real foi apresentado e avaliado com o usuário antes da implementação.
- [x] `DOD-04` Após aprovação, o root separa corretamente base genérica e overlay downstream nas superfícies aprovadas desta fatia.
- [x] `DOD-05` O contrato canônico de tooling/browser workflow permanece coerente com `tools/flutter/**` e não regride para o estado exportado em `project/tests/**`.
- [x] `DOD-06` Defaults, exemplos e nomes Belluga Now não permanecem hardcoded nas superfícies do root que forem definidas como Boilerplate base.
- [ ] `DOD-07` As validações root em escopo passam no estado reconciliado desta branch.

## Validation Steps
- [x] Produzir a diff real da fatia root contra o checkout atual.
- [x] Consolidar a classificação `base vs overlay vs exclude`.
- [x] Revisar o relatório com o usuário antes de qualquer implementação e fechar as decisões materiais.
- [x] Após implementação aprovada, rodar `bash -n` nas superfícies shell tocadas.
- [ ] Após implementação aprovada, rodar as validações root CI-equivalent em escopo, incluindo `bash .github/scripts/verify_environment_ci.sh`.
- [x] Quando NGINX/tooling de navegação forem tocados, executar a verificação adicional correspondente (`docker compose config`, harness checks, ou smoke/navigation evidence) conforme a diff final.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` A diff real da fatia root foi produzida sobre o checkout atual e não sobre transporte cego do snapshot exportado. | `review` | `foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md` | `local review` | `passed` | O relatório foi consolidado a partir da comparação do checkout atual com o snapshot exportado relevante. |
| `DOD-02` | `Definition of Done` | `DOD-02` Cada superfície relevante do root foi classificada como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`. | `review` | `foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md` | `local review` | `passed` | O relatório registra a classificação superfície por superfície. |
| `DOD-03` | `Definition of Done` | `DOD-03` O relatório consolidado da diff real foi apresentado e avaliado com o usuário antes da implementação. | `review` | `conversation review on 2026-06-08 + foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md` | `conversation gate` | `passed` | O usuário aprovou seguir com os pontos levantados e refinou explicitamente o tratamento do tooling compartilhado vs projeto-específico. |
| `DOD-04` | `Definition of Done` | `DOD-04` Após aprovação, o root separa corretamente base genérica e overlay downstream nas superfícies aprovadas desta fatia. | `code+review` | `root diff on reconcile/agnostic-adjust-boilerplate-cutline-20260608 + project/** overlays + root neutralization changes` | `local root branch` | `passed` | O root agora distingue base compartilhada de overlays downstream em `project/**`, incluindo rotas NGINX e guardrails runtime específicos. |
| `DOD-05` | `Definition of Done` | `DOD-05` O contrato canônico de tooling/browser workflow permanece coerente com `tools/flutter/**` e não regride para o estado exportado em `project/tests/**`. | `review+test` | `timeout 120s node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | `local root + navigation tooling` | `passed` | O wrapper específico ficou em `project/tests/setup_local_navigation_env.sh`; a engine compartilhada permaneceu em `tools/flutter/**` e o harness policy passou. |
| `DOD-06` | `Definition of Done` | `DOD-06` Defaults, exemplos e nomes Belluga Now não permanecem hardcoded nas superfícies do root que forem definidas como Boilerplate base. | `review` | `root diff review + belluga/guarappari search constrained to project/** on 2026-06-08` | `local root branch` | `passed` | As referências Belluga remanescentes ficaram confinadas a `project/**`; a base compartilhada foi neutralizada em docs, compose, tags, workflow text, tooling names e entrypoint/runtime guard hooks. |
| `DOD-07` | `Definition of Done` | `DOD-07` As validações root em escopo passam no estado reconciliado desta branch. | `test` | `bash -n ... ; docker compose config ; timeout 120s node --test ... ; bash .github/scripts/verify_environment_ci.sh` | `local root branch` | `blocked` | `verify_environment_ci.sh` falha por workflows pinados do `web-app` ainda usarem `peter-evans/repository-dispatch@v3`; isso exige atualização de submódulo fora desta fatia root. |
| `VAL-01` | `Validation Steps` | Produzir a diff real da fatia root contra o checkout atual. | `review` | `foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md` | `local review` | `passed` | Primeiro gate operacional já consolidado. |
| `VAL-02` | `Validation Steps` | Consolidar a classificação `base vs overlay vs exclude`. | `review` | `foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md` | `local review` | `passed` | Classificação consolidada no relatório root. |
| `VAL-03` | `Validation Steps` | Revisar o relatório com o usuário antes de qualquer implementação e fechar as decisões materiais. | `review` | `conversation review on 2026-06-08 + foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md` | `conversation gate` | `passed` | O checkpoint foi fechado antes da implementação. |
| `VAL-04` | `Validation Steps` | Após implementação aprovada, rodar `bash -n` nas superfícies shell tocadas. | `test` | `bash -n docker/laravel-app/entrypoint.sh .github/scripts/verify_environment_ci.sh .github/scripts/preflight_promotion_runtime_builds.sh .github/scripts/resolve_lane_navigation_targets.sh .github/scripts/upsert_source_promotion_pr.sh .github/scripts/rollback_over_ssh.sh project/tests/setup_local_navigation_env.sh` | `local root branch` | `passed` | Shell syntax aprovada para todas as superfícies tocadas em shell. |
| `VAL-05` | `Validation Steps` | Após implementação aprovada, rodar `bash .github/scripts/verify_environment_ci.sh`. | `test` | `bash .github/scripts/verify_environment_ci.sh` | `local root branch` | `blocked` | O verificador falha em `web-app/.github/workflows/dispatch-docker-sync.yml:55` e `web-app/.github/workflows/lane-auto-promotion.yml:85` por `peter-evans/repository-dispatch@v3` pinado fora desta fatia root. |
| `VAL-06` | `Validation Steps` | Quando NGINX/tooling de navegação forem tocados, executar a verificação adicional correspondente. | `test` | `docker compose config` + `timeout 120s node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | `local root branch` | `passed` | Compose estruturado corretamente e harness policy compartilhado aprovado em ~100s. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-devops`
- **Active technical scope:** `docker`
- **Expected supporting profiles:** `strategic-cto`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-devops`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-devops` | A linha de corte foi definida estrategicamente; a primeira fatia operacional agora trata só do root `belluga_now_docker` com relatório pré-implementação obrigatório. | `belluga_now_docker root`, `foundation_documentation/todos/active/agnostic_adjust/**`, `foundation_documentation/artifacts/feature-briefs/**` | `active` |

- **Profile scope note:** os caminhos marcados como `unknown` pelo `profile_scope_check.py` nesta fatia (`.github/scripts/**`, `README.md`, `tools/flutter/**`, `project/**`, `.gitmodules.boilerplate.example`) são superfícies operacionais de suporte ao contrato root aprovado neste TODO; permanecem dentro do mesmo objetivo e não abrem handoff adicional enquanto não alterarem contratos funcionais dos subprojetos.

- Se a execução exigir mudança canônica em `project_constitution.md`, registrar handoff de volta para `Strategic / CTO-Tech-Lead` em vez de editar a constituição silenciosamente.

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** a fatia está limitada ao root, mas cruza docs, env, compose, NGINX, CI e tooling de navegação, com necessidade explícita de relatório e revisão antes de implementar.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned decision promotion targets (module sections):**
  - `system_architecture_principles.md` sections `6 Documentation Rules`, `7 Canonical Anchors`, `8 Tactical TODO Promotion Ledger` when stable project-specific root/orchestration rules need durable promotion
- **Module decision consolidation targets (required):**
  - `system_architecture_principles.md` sections `3 Platform & Tenant Model` and `6 Documentation Rules`

## Decision Pending (Resolve Before Freeze)
- [x] `D-01` `tools/flutter/**` permanece a engine canônica compartilhada do workflow/browser tooling; o que for Belluga Now-specific deve entrar apenas como wrapper/config/fixture explícita em `project/**`, não como suíte paralela competindo com a base.
- [x] `D-02` O inventário de rotas públicas Belluga Now (`/parceiro`, `/descobrir`, `/convites`, `/baixe-o-app`, `/agenda/evento`, etc.) deve sair das templates base de NGINX e residir apenas em overlay downstream explícito.
- [x] `D-03` Names e defaults de runtime image/preflight tags devem ser neutros na base, mantendo override downstream por variáveis quando necessário.
- [x] `D-04` O root deve separar a documentação/template boilerplate-facing da identidade real de remotes do projeto atual; a implementação desta fatia não deve quebrar o `.gitmodules` funcional do projeto de referência.

## Decisions (Resolved Before Freeze)
- [x] `D-05` Esta frente deve nascer da branch atual de review `v0.2.0+8`, seguir isolada em branch dedicada, e ser rebaseada quando a base for conciliada e promovida.
- [x] `D-06` Antes de qualquer implementação no root, deve existir um relatório consolidado da diff real e uma avaliação conjunta com o usuário.
- [x] `D-07` A primeira fatia operacional desta iniciativa é o root `belluga_now_docker`; o bloco `laravel_app_refactor` fica explicitamente fora desta primeira execução.
- [x] `D-01` `tools/flutter/**` permanece a engine canônica compartilhada; projeto específico entra como wrapper/config/fixture em `project/**`.
- [x] `D-02` Rotas públicas Belluga Now deixam as templates base de NGINX e passam para overlay downstream explícito.
- [x] `D-03` Runtime image names e preflight tags ficam neutros na base com override downstream por variável.
- [x] `D-04` A separação boilerplate-facing de submodule/onboarding deve existir sem quebrar o `.gitmodules` funcional deste projeto de referência.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `system_architecture_principles.md#3-platform-vs-tenant-model` | Bóora! é plataforma multi-tenant; tenant brands não devem ser confundidas com a plataforma. | `Preserve` | `foundation_documentation/modules/system_architecture_principles.md` section `3` |
- | `system_architecture_principles.md#6-documentation-rules` | Tenant-specific assets e cópia podem permanecer downstream/project-specific. | `Preserve` | `foundation_documentation/modules/system_architecture_principles.md` section `6` |
- | `system_architecture_principles.md#7-canonical-anchors` | A autoridade canônica do projeto deve permanecer explícita e alinhada. | `Preserve` | `foundation_documentation/modules/system_architecture_principles.md` section `7` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-05` A branch desta frente deriva da frente atual de review `v0.2.0+8` e receberá rebase posterior após a promoção da base.
- [x] `D-06` Não haverá implementação antes do relatório consolidado da diff real e da avaliação conjunta com o usuário.
- [x] `D-07` A primeira fatia fica restrita ao root `belluga_now_docker`; `laravel_app_refactor` não entra nesta execução inicial.
- [x] `D-01` A engine compartilhada de navigation/browser tooling permanece em `tools/flutter/**`; apenas wrappers/config/fixtures específicas de projeto entram em `project/**`.
- [x] `D-02` As rotas públicas Belluga Now saem das templates base de NGINX e passam para um overlay downstream explícito.
- [x] `D-03` Runtime image names e preflight tags são neutros na base e continuam overrideáveis por variável.
- [x] `D-04` A documentação/template boilerplate-facing para submodule/onboarding deve ser separada da identidade real deste projeto, sem quebrar o `.gitmodules` funcional atual.

## Approval
- **Approved by:** user, conversation on `2026-06-08`
- **Approval token evidence:** approved to proceed for this tactical root slice on `2026-06-08`
- **Approval evidence:** "Beleza. A ideia aqui é fazer essas implementações, deixando essa separação genérica bem definida e validando o funcionamento adequado usando esse projeto como referência. Diante disso, e com os ajustes propostos, pode seguir com os pontos que você levantou."
- **Approval scope:** executar a fatia root aprovada neste TODO, mantendo `tools/flutter/**` como base compartilhada, extraindo superfícies Belluga para `project/**` quando necessário, neutralizando defaults Belluga nas superfícies base, ajustando scripts/docs/root tooling estritamente necessários para esse contrato e validando localmente o resultado.
- **Explicit exclusions:** `laravel_app_refactor`, mudanças funcionais em `laravel-app`/`flutter-app`/`web-app`, promoção para Boilerplate nesta sessão, e qualquer alteração silenciosa em `project_constitution.md`.
- **Renewed-approval trigger:** qualquer expansão para além da fatia root aprovada, alteração de contratos funcionais ou necessidade de mexer em constituição/módulos canônicos fora do já previsto.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Governa o modo de execução Delphi/PACED nesta sessão operacional. | Disciplina de método, agnosticismo de Delphi, contexto canônico do projeto e host-user ownership. | Canonizar regra de projeto em `delphi-ai/` ou seguir sem workflow carregado. | Mantém a execução ancorada em TODO, docs canônicos e validação local objetiva. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | Define o perfil ativo e o escopo técnico desta frente. | `Operational / DevOps` como perfil primário e escopo `docker` com suporte `cross-stack` apenas quando necessário. | Misturar fronteiras de responsabilidade sem handoff explícito. | Justifica tocar root orchestration, CI, compose, nginx e tooling compartilhado. |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | Este trabalho depende de um TODO tático aprovado e de gates de entrega. | Escopo congelado, baseline de decisões, evidência por critério e validações antes de `Local-Implemented`. | Expandir escopo silenciosamente ou fechar sem guards/evidências. | Obriga manter o TODO sincronizado com a execução e revalidar os guards no fechamento. |
| `delphi-ai/workflows/docker/todo-driven-execution-method.md` | É o state machine canônico do TODO durante implementação e entrega. | Sequência aprovação -> execução -> delivery gates -> closeout. | Pular fase ou declarar entrega sem evidências/guards. | Estrutura o restante desta execução e a atualização final do TODO. |
| `delphi-ai/workflows/docker/environment-readiness-method.md` | A fatia toca runtime/CI/compose/nginx e exige validação de topologia local. | Uso das superfícies já declaradas no projeto (`README`, compose, scripts e tooling). | Inferir topologia/hosts por suposição fora das superfícies do projeto. | Direciona `verify_environment_ci.sh`, `docker compose config` e validações de tooling. |
| `foundation_documentation/project_mandate.md` | A separação precisa respeitar a postura replicável multi-tenant do produto. | Núcleo reutilizável da plataforma com branding/tenant posture downstream. | Deixar identidade Belluga/Bóora hardcoded onde a base deve ser neutra. | Sustenta a linha de corte entre base boilerplate e overlay downstream. |
| `foundation_documentation/project_constitution.md` | Define autoridade local, boundary do root e derivação do `web-app`. | `foundation_documentation` como fonte canônica, `web-app` como derivado, e governança root no repo de orquestração. | Mover autoridade para artefatos derivados ou quebrar a doutrina de reuse/local sovereignty. | Reforça que a extração fica no root/project overlay e não no `web-app`. |
| `foundation_documentation/policies/scope_subscope_governance.md` | As mudanças tocam roteamento host-aware e superfícies de navegação web. | Resolução host-aware e autoria dos testes em `tools/flutter/web_app_tests/**`. | Reintroduzir governança canônica direto no `web-app` ou criar escopos implícitos. | Mantém a extração de rotas no NGINX/root sem quebrar a política de origem dos testes. |

## Questions To Close
- [x] A superfície downstream de testes/navegação específica de projeto deve ser um wrapper/downstream contract explícito sobre `tools/flutter/**`.
- [x] O contrato de rotas públicas específicas Belluga Now deve ser extraído para `project/nginx/routes.conf` explícito nesta fatia root.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | O root exportado contém sinais úteis de separação (`project overlay`), mas não pode ser aplicado cegamente porque diverge do estado atual do root. | Diff já confirmada entre export e root atual em README, compose, NGINX, CI e tooling references. | A iniciativa teria de recomeçar do zero sem aproveitar a estrutura exportada. | `High` | `Keep as Assumption` |
| `A-02` | A primeira fatia pode ser isolada no root sem absorver `laravel_app_refactor`. | O maior ruído/contaminação funcional está no snapshot Laravel; o root tem escopo mais claro. | Seria necessário dividir ainda mais a iniciativa ou bloquear até triagem Laravel. | `High` | `Keep as Assumption` |
| `A-03` | O contrato atual em `tools/flutter/**` é a referência mais confiável para navigation/browser tooling no root. | README, `verify_environment_ci.sh` e `orchestration-ci-cd.yml` atuais já apontam para `tools/flutter/**`. | A diff real precisaria justificar uma mudança estrutural mais ampla antes de qualquer separação. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `foundation_documentation/artifacts/feature-briefs/agnostic-adjust-boilerplate-separation.md`
- `foundation_documentation/todos/active/agnostic_adjust/TODO-agnostic-adjust-root-boilerplate-separation.md`
- root `belluga_now_docker` surfaces selected by the approved cutline, likely including:
  - `README.md`
  - `.env.local.navigation.example`
  - `docker-compose.yml`
  - `docker/laravel-app/entrypoint.sh`
  - `docker/nginx/local.conf.template`
  - `docker/nginx/prod.conf.template`
  - `.github/scripts/verify_environment_ci.sh`
  - `.github/workflows/orchestration-ci-cd.yml`
  - optional explicit downstream overlay surfaces such as `project/**`

### Ordered Steps
1. Materialize the relevant exported root files in a local sandbox outside the authoritative checkout.
2. Compare the sandboxed export with the current root checkout and capture the real diff for this slice.
3. Classify each touched root surface as `base`, `overlay`, or `exclude`.
4. Produce the consolidated report and review it with the user.
5. If the report review closes the pending decisions, update this TODO, freeze the decision baseline, and request `APROVADO`.
6. Only after `APROVADO`, implement the approved root separation and run the selected validations.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** the immediate next gate is a real-diff report and decision closure, not code implementation.
- **Fail-first target(s) (when required):** `n/a until implementation starts`

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Root navigation/browser tooling contract | Changes can affect real browser smoke and mutation workflows used for web validation and promotion gates. | `web-only` | `Playwright readonly` plus `Playwright mutation` when affected | `yes if mutation lane touched` | `yes` | Root report + later navigation smoke evidence after implementation if the cutline touches tooling/runtime entrypoints. | `n/a` |
| Project-specific public route overlay extraction | Moving route ownership between base NGINX templates and downstream overlay can affect tenant-public web routing and app-promotion entrypoints. | `web-only` | `Playwright readonly` | `no unless mutation flows are touched` | `yes` | Root report + later route smoke evidence after implementation if the approved diff touches those routes. | `n/a` |
| Docs/env/example neutralization | User-facing documentation and examples can change onboarding and operator behavior, but not an end-user app flow directly. | `n/a` | `n/a` | `no` | `no` | Report review + docs diff review. | Structure/documentation-only until implementation proves otherwise. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / root deterministic CI guard` | Any root CI, compose, NGINX, or tooling separation must remain compatible with the canonical root verifier. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `blocked` | `bash .github/scripts/verify_environment_ci.sh` | Bloqueado por workflows pinados do `web-app` ainda em `repository-dispatch@v3`, fora desta fatia root. |
| `belluga_now_docker / shell syntax` | Shell entrypoints may be touched (`entrypoint`, verifier helpers, smoke wrappers). | `bash -n docker/laravel-app/entrypoint.sh .github/scripts/verify_environment_ci.sh .github/scripts/preflight_promotion_runtime_builds.sh` | `Local-Implemented` | `passed` | `bash -n docker/laravel-app/entrypoint.sh .github/scripts/verify_environment_ci.sh .github/scripts/preflight_promotion_runtime_builds.sh .github/scripts/resolve_lane_navigation_targets.sh .github/scripts/upsert_source_promotion_pr.sh .github/scripts/rollback_over_ssh.sh project/tests/setup_local_navigation_env.sh` | Lista final expandida para todas as superfícies shell tocadas. |
| `belluga_now_docker / compose validation` | `docker-compose.yml` and NGINX template changes must stay structurally valid. | `docker compose config` | `Local-Implemented` | `passed` | `docker compose config` | Compose válido com overlays `project/nginx` montados no serviço NGINX. |
| `belluga_now_docker / navigation harness policy` | If navigation tooling/harness references move, the root harness contract must remain deterministic. | `node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | `Local-Implemented` | `passed` | `timeout 120s node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | Harness policy compartilhado passou em ~100s. |

### Runtime / Rollout Notes
- Root implementation must happen on `reconcile/agnostic-adjust-boilerplate-cutline-20260608`, derived from the current `v0.2.0+8` review front.
- `foundation_documentation` must remain on the branch already in use for that repo, preserving local changes in place; no new branch is created there for this TODO.
- After the base `v0.2.0+8` front is reconciled and promoted, this branch must be rebased and any residual adjustments revalidated before closeout.
