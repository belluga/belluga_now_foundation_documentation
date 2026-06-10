# TODO: Agnostic Adjust Boilerplate Cutline

## 0. Artifact Role

- **Artifact type:** `capped_no_code_ledger`
- **Active profile:** `strategic-cto`
- **Current phase (if Genesis):** `n/a`
- **Purpose in this session:** estabelecer a linha de corte auditada entre o que pode subir como base agnóstica/ecossistema e o que deve permanecer Belluga Now-específico antes de qualquer promoção para o Boilerplate.
- **What it is not:** não é um contrato de implementação tática, não autoriza copiar o snapshot exportado cegamente e não aprova promoção para Boilerplate nesta etapa.
- **Code-touch boundary:** `no code`
- **Companion artifacts (optional):**
  - `foundation_documentation/todos/active/agnostic_adjust/refactor_project_convention/belluga_now_docker/**`
  - `foundation_documentation/todos/active/agnostic_adjust/laravel_app_refactor/laravel-app/**`

## 1. Current Objective

- Classificar as mudanças exportadas em três grupos canônicos:
  - `boilerplate base`
  - `project overlay / Belluga Now-specific`
  - `exclude / stale export noise`
- Fechar uma linha de corte suficientemente precisa para abrir a execução operacional sem misturar separação agnóstica com evolução funcional do produto.

## 2. Confirmed Baseline

- Em **2026-06-08**, o snapshot `refactor_project_convention/belluga_now_docker` divergiu do root atual em **11 arquivos** e trouxe **39 superfícies `project/**` ausentes** no checkout principal.
- O snapshot do root introduz uma ideia válida de separação por overlay de projeto (`project/nginx/routes.conf`, `project/scripts/*`, `project/tests/**`), mas ele está **desalinhado** com o estado atual porque a suíte canônica de navegação/browser já foi movida para `tools/flutter/**` no root atual.
- O root atual ainda mantém hardcodes Belluga Now em superfícies que deveriam ser candidatas a boilerplate:
  - `README.md`
  - `.env.local.navigation.example`
  - `docker/nginx/local.conf.template`
  - `docker/nginx/prod.conf.template`
  - `.github/scripts/resolve_lane_navigation_targets.sh`
- O snapshot exportado também preserva identidade Belluga Now em pontos que não podem subir como default de Boilerplate sem parametrização:
  - `.gitmodules` com repositórios `belluga_now_*`
  - `project/nginx/routes.conf` com rotas públicas como `/parceiro`, `/descobrir`, `/convites`, `/baixe-o-app`
  - `project/README.md` explicitamente descrito como Belluga Now-specific
- O export tem ruído estrutural de exportação direta e não pode ser tratado como patch limpo:
  - `refactor_project_convention/belluga_now_docker/scripts` é um arquivo-placeholder, não uma árvore real
  - `laravel_app_refactor/laravel-app/account` chegou como arquivo vazio
- Em **2026-06-08**, o snapshot `laravel_app_refactor/laravel-app` divergiu do `laravel-app` atual em **91 arquivos**, distribuídos principalmente em:
  - `app/**` (`35`)
  - `tests/**` (`30`)
  - `packages/belluga/**` (`22`)
- A amostra auditada do `laravel_app_refactor` indica contaminação por evolução funcional do produto, não apenas por separação genérico/específico. Exemplos:
  - `app/Application/Environment/TenantEnvironmentPayloadFactory.php`
  - `app/Application/ProximityPreferences/ProximityPreferenceService.php`
  - `packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
  - `packages/belluga/belluga_invites/src/Application/Preview/InvitePreviewPayloadFactory.php`
  - `packages/belluga/belluga_map_pois/src/Application/MapPoiQueryService.php`
- O `laravel-app` atual continua com contratos públicos Belluga Now hardcoded fora de qualquer mecanismo de overlay de boilerplate, por exemplo:
  - `laravel-app/routes/web.php`
  - `laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php`
  - `laravel-app/packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php`
- Diretriz operacional confirmada para a execução futura: a branch desta frente deve nascer da branch atual que está carregando o review de `v0.2.0+8`, para destravar o trabalho imediatamente; quando essa base for conciliada e promovida, esta frente deverá ser rebaseada sobre o novo estado promovido antes do fechamento final.

## 3. Gap / Decision Register

| ID | Topic | Current State | Why It Matters | Current Handling | Next Target |
| --- | --- | --- | --- | --- | --- |
| `G-01` | Root overlay contract | `Partial` | O snapshot propõe separar root genérico de `project/**`, mas o estado atual ainda re-inlineou rotas e defaults Belluga em arquivos base. | `review now` | Fechar lista `base vs overlay` para `README`, `docker-compose`, `docker/nginx`, `entrypoint`, `Makefile` e CI. |
| `G-02` | Browser/navigation tooling location | `Open` | O export usa `project/tests/**`, mas o checkout atual já canonizou `tools/flutter/**`; portar o export bruto regrediria workflow e CI. | `review now` | Decidir a localização canônica do tooling de navegação no Boilerplate e o contrato downstream para testes específicos de projeto. |
| `G-03` | Host/domain/doc hardcodes | `Open` | `belluga.space`, `guarappari.belluga.space` e exemplos Belluga continuam em docs, env examples e harnesses. | `review now` | Parametrizar ou neutralizar exemplos de domínio/tenant no pacote que subir para Boilerplate. |
| `G-04` | Runtime image and runtime-guard naming | `Open` | O export usa defaults mais genéricos (`laravel-*`), enquanto o root atual voltou para `belluga-now-*` e inlineou guards de runtime Belluga. | `review now` | Decidir quais nomes e guards pertencem ao Boilerplate base e quais viram override downstream. |
| `G-05` | Public route inventory | `Open` | Rotas como `/parceiro`, `/descobrir`, `/convites`, `/baixe-o-app`, `/agenda/evento` são produto-específicas e hoje vazam em NGINX, Laravel web routes e deep links. | `review now` | Congelar o princípio: Boilerplate sobe só com mecanismo de extensão, enquanto o mapa de rotas Belluga Now permanece downstream. |
| `G-06` | `.gitmodules` and repo identity | `Open` | O snapshot e o root atual ainda apontam para repositórios `belluga_now_*`, o que inviabiliza promoção limpa como boilerplate. | `review now` | Decidir placeholders/documentação de onboarding para repositórios downstream sem hardcode Belluga Now. |
| `G-07` | `laravel_app_refactor` scope contamination | `Open` | O pacote Laravel exportado mistura separação agnóstica com evolução de funcionalidades de account profiles, map UI, invites e events. | `review now` | Isolar mudanças realmente boilerplate/ecossistema-ready e marcar o restante como fora de escopo para promoção. |
| `G-08` | Export artifact hygiene | `Open` | O material foi exportado com placeholders e superfícies potencialmente irrelevantes; usar isso como verdade única criaria falso diff e decisões ruins. | `review now` | Limpar a taxonomia `keep / move / drop` antes de qualquer implementação. |
| `G-09` | Execution slicing | `Open` | O trabalho ainda não é uma única fatia operacional segura; root e Laravel não têm o mesmo nível de clareza nem o mesmo risco. | `review now` | Preparar handoff para pelo menos dois slices: `root boilerplate separation` e `laravel extraction-or-exclusion`. |
| `G-10` | Base branch and rebase timing | `Partial` | A execução precisa destravar agora sem disputar a estabilização da frente `v0.2.0+8`, mas também não pode ignorar a promoção iminente dessa base. | `review now` | Congelar o fluxo: branch desta frente sai da branch atual de review, recebe só trabalho desta iniciativa, e é rebaseada após a conciliação/promoção da base. |

## 4. Current Order

1. Congelar a classificação do root exportado em `boilerplate base`, `project overlay`, `exclude`.
2. Fechar a decisão de localização canônica para navigation/browser tooling sem regredir o estado atual em `tools/flutter/**`.
3. Produzir a cutline de docs/env/compose/nginx/CI/image naming/submodule identity que pode subir para Boilerplate.
4. Auditar o `laravel_app_refactor` só na ótica de separação agnóstica, excluindo mudanças que sejam principalmente evolução funcional do produto.
5. Congelar a estratégia de branch-base e rebase para que a execução desta frente não bloqueie a conciliação/promoção de `v0.2.0+8`.
6. Abrir o handoff operacional apenas quando a execução puder ser separada em slices claros e sem transporte cego do snapshot exportado.

## 4.A Planned Operational Handoff Process

Quando a execução for autorizada, este ledger deve handoffar para um TODO tático com o seguinte processo previsto:

1. Criar uma branch dedicada de reconciliação no checkout principal, no padrão `reconcile/*`, para que a validação local e a diff autoritativa ocorram no estado real de trabalho.
   - A origem dessa branch deve ser a branch atual que está carregando o review de `v0.2.0+8`, não `dev`/`main`, para destravar esta frente sem esperar a promoção.
   - Enquanto a base `v0.2.0+8` estiver em review/conciliação, ela deve receber apenas ajustes pontuais; esta frente permanece isolada na branch dedicada.
2. Abrir um TODO tático canônico específico para a fatia aprovada, substituindo este ledger como autoridade operacional.
3. Materializar localmente apenas os arquivos exportados realmente relevantes para a fatia aprovada, evitando tratar o snapshot inteiro como patch autoritativo.
4. Comparar os arquivos materializados com o estado atual do root e dos submódulos tocados para produzir a diff real.
5. Classificar cada mudança da diff real como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`.
6. Produzir um relatório consolidado da diff real, com avaliação de impacto, classificação proposta e pontos de risco/ambiguidade, e revisar esse relatório com o usuário antes de qualquer implementação.
7. Só então executar a separação aprovada e validar no estado reconciliado dessa branch, em vez de trabalhar diretamente sobre a exportação bruta.
8. Quando a frente de review `v0.2.0+8` for conciliada e promovida, rebasear esta branch sobre o novo estado promovido e absorver apenas os ajustes residuais necessários antes do fechamento final.

## 5. Explicitly Out of Scope

- Implementar qualquer alteração no root, `laravel-app`, `flutter-app` ou `web-app`.
- Promover agora qualquer versão para Boilerplate.
- Copiar o snapshot exportado como se fosse patch autoritativo.
- Tratar namespace `Belluga\\` ou `packages/belluga/**` automaticamente como “produto específico”; a triagem aqui é `ecossistema reutilizável` versus `Belluga Now específico`.
- Reabrir escopo funcional de produto que não seja necessário para a linha de corte agnóstica.

## 6. Exit Condition

- Cada superfície auditada do root e do snapshot Laravel estiver classificada como `base`, `overlay`, ou `exclude`.
- Os regressos entre snapshot exportado e estado atual estiverem explicitados.
- O ruído de exportação direta estiver documentado para não contaminar a implementação.
- O handoff estiver pronto para abrir TODO(s) operacionais separados, ou para concluir que o `laravel_app_refactor` não deve subir para Boilerplate nesta leva.

## 7. Next Exact Step

- Revisar com o usuário o relatório `foundation_documentation/artifacts/status-audits/agnostic-adjust-root-cutline-report-20260608.md`, fechar as decisões materiais da fatia root, e só depois decidir se/como abrir a implementação da primeira fatia operacional (`belluga_now_docker`) antes de qualquer discussão de promoção do bloco `laravel_app_refactor`.
