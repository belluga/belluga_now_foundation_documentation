# Feature Brief: Agnostic Adjust Boilerplate Separation

## Artifact Role
- **Why this brief exists now:** a iniciativa ainda é grande demais para um único TODO tático seguro porque mistura `belluga_now_docker` root separation, tooling/browser workflow decisions, route-overlay extraction, e um `laravel_app_refactor` exportado que também carrega evolução funcional do produto.
- **What this brief is not:** `project constitution`, `system roadmap`, módulo canônico, TODO tático, ou autoridade de implementação.

## Source Idea / Request
- Separar com rigor o que é genérico/ecossistema do que é Belluga Now-específico para preparar uma versão adequada a ser enviada ao Boilerplate.

## Problem / Desired Outcome
- **Problem:** o material exportado pela outra IA não é um patch limpo. No root ele sugere uma estratégia válida de overlay de projeto, mas entra em conflito com o estado atual; no `laravel_app_refactor`, ele mistura separação agnóstica com mudanças funcionais reais do produto.
- **Desired outcome:** abrir uma execução segura por fatias, começando pelo root `belluga_now_docker`, baseada na diff real do checkout atual e não em transporte cego do snapshot exportado.
- **Why now:** o usuário quer destravar esta frente imediatamente, em branch derivada da review `v0.2.0+8`, sem esperar a promoção da base, mas com relatório e avaliação conjunta antes de implementar.

## Constraints / Non-Goals
- **Constraints:**
  - a branch desta frente nasce da frente atual de review `v0.2.0+8`;
  - a base em review deve receber apenas ajustes pontuais até sua promoção;
  - antes de qualquer implementação deve existir um relatório consolidado da diff real e uma avaliação conjunta com o usuário;
  - a exportação não pode ser tratada como patch autoritativo;
  - o trabalho deve começar pelo root `belluga_now_docker`.
- **Non-goals:**
  - promover agora qualquer alteração para Boilerplate;
  - absorver `laravel_app_refactor` inteiro nesta primeira fatia;
  - reabrir mudanças de produto que não sejam necessárias para a separação agnóstica.

## Canonical Touchpoints
- **Constitution impact:** `possible` — a separação pode exigir promoção futura de regras sobre `overlay` de projeto, identidade de submódulos e fronteiras de root/orchestration.
- **Roadmap impact:** `possible` — a frente toca a horizon `Project Authority Reconciliation` e pode gerar follow-up explícito para extração do bloco Laravel.
- **Primary module candidates:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module candidates:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`

## Evidence / References
- `foundation_documentation/todos/active/agnostic_adjust/TODO-agnostic-adjust-boilerplate-cutline.md`
- `foundation_documentation/todos/active/agnostic_adjust/refactor_project_convention/belluga_now_docker/**`
- `foundation_documentation/todos/active/agnostic_adjust/laravel_app_refactor/laravel-app/**`
- `foundation_documentation/project_constitution.md`
- `foundation_documentation/system_roadmap.md`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Localização canônica do tooling de navegação web (`tools/flutter/**` vs `project/tests/**`) | Portar o snapshot bruto pode regredir CI e workflow atuais. | O root atual já usa `tools/flutter/run_web_navigation_smoke.sh` e `tools/flutter/web_app_tests/**`; o export propõe `project/tests/**`. | `resolve now` |
| `AMB-02` | O que sobe como base genérica no root e o que vira overlay downstream | Sem essa linha de corte, a implementação pode re-inlinear Belluga Now em superfícies base ou extrair demais. | NGINX, README, env examples, image naming, runtime guards e route inventory aparecem hoje parcialmente misturados. | `resolve now` |
| `AMB-03` | Quanto do `laravel_app_refactor` é realmente extração reutilizável | Misturar essa triagem com a primeira fatia pode bloquear o trabalho e contaminar a promoção. | A amostra auditada mostrou account profiles, events, invites, map UI e tests mudando comportamento real. | `carry as TODO assumption` |

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Produzir a diff real do root `belluga_now_docker`, classificar `base vs overlay vs exclude`, e preparar a separação aprovada para Boilerplate. | `foundation_documentation/modules/system_architecture_principles.md` | `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/onboarding_flow_module.md` | O root tem cutline auditada e o usuário valida o relatório antes da implementação. | Relatório de diff real + TODO tático da fatia root + validação conjunta do usuário. | `create-now` | Nenhum bloqueador externo; depende só da materialização local e da revisão do relatório. | Primeira fatia operacional. |
| `ST-02` | Implementar a separação do root aprovada após o relatório, mantendo tooling/browser flows e CI coerentes. | `foundation_documentation/modules/system_architecture_principles.md` | `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/onboarding_flow_module.md` | Docs/env/compose/nginx/CI/tooling do root ficam neutros ou movidos para overlay downstream explícito. | `verify_environment_ci`, `bash -n`, smoke/browser evidence conforme superfícies tocadas. | `merge-with-other` | Depende da validação do relatório de `ST-01` e de `APROVADO`. | Continua no mesmo TODO tático da fatia root. |
| `ST-03` | Auditar `laravel_app_refactor` e decidir o que é extração reutilizável versus ruído/evolução funcional. | `foundation_documentation/modules/system_architecture_principles.md` | `foundation_documentation/modules/account_profile_catalog_module.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/map_poi_module.md` | Existe cutline específica para o bloco Laravel, ou decisão explícita de excluí-lo desta leva. | Diff report separado + decisão do usuário sobre escopo. | `defer` | Deve vir depois da fatia root para não misturar extração infra com mudança funcional. | Segunda fatia provável. |

## Retire This Brief When
- O TODO tático ativo da fatia root estiver aberto e a iniciativa já não depender mais deste brief para decomposição.
