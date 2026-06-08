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
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Docker`, `Root-Orchestration`, `Boilerplate-Separation`, `Report-Gated`
- **Next exact step:** produzir a diff real da fatia root, classificar `base vs overlay vs exclude`, e revisar o relatório com o usuário antes de qualquer implementação.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** a fatia já entrou em execução operacional, mas ainda está na etapa obrigatória de materialização local, diff real e relatório pré-implementação.
- **Exit condition:** relatório consolidado revisado com o usuário e decisões materiais fechadas para poder pedir `APROVADO` antes da implementação.

## Scope
- [ ] Materializar localmente, fora do repositório autoritativo, apenas os arquivos exportados relevantes para a fatia root.
- [ ] Comparar os arquivos materializados com o estado atual do root `belluga_now_docker` para produzir a diff real desta fatia.
- [ ] Classificar cada superfície relevante do root como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`.
- [ ] Produzir um relatório consolidado da diff real com impacto, classificação proposta, regressos versus o estado atual, e ambiguidades/riscos.
- [ ] Revisar esse relatório com o usuário antes de qualquer implementação.
- [ ] Após a revisão e aprovação do usuário, separar no root apenas as superfícies aprovadas desta fatia, mantendo a coerência com o estado atual de `tools/flutter/**`.
- [ ] Preservar o contrato de branch desta frente: trabalho isolado agora e rebase posterior após a promoção da base `v0.2.0+8`.

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
| `root boilerplate cutline and separation` | `reconcile/agnostic-adjust-boilerplate-cutline-20260608 @ <pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
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
- [ ] `DOD-01` A diff real da fatia root foi produzida sobre o checkout atual e não sobre transporte cego do snapshot exportado.
- [ ] `DOD-02` Cada superfície relevante do root foi classificada como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`.
- [ ] `DOD-03` O relatório consolidado da diff real foi apresentado e avaliado com o usuário antes da implementação.
- [ ] `DOD-04` Após aprovação, o root separa corretamente base genérica e overlay downstream nas superfícies aprovadas desta fatia.
- [ ] `DOD-05` O contrato canônico de tooling/browser workflow permanece coerente com `tools/flutter/**` e não regride para o estado exportado em `project/tests/**`.
- [ ] `DOD-06` Defaults, exemplos e nomes Belluga Now não permanecem hardcoded nas superfícies do root que forem definidas como Boilerplate base.
- [ ] `DOD-07` As validações root em escopo passam no estado reconciliado desta branch.

## Validation Steps
- [ ] Produzir a diff real da fatia root contra o checkout atual.
- [ ] Consolidar a classificação `base vs overlay vs exclude`.
- [ ] Revisar o relatório com o usuário antes de qualquer implementação e fechar as decisões materiais.
- [ ] Após implementação aprovada, rodar `bash -n` nas superfícies shell tocadas.
- [ ] Após implementação aprovada, rodar as validações root CI-equivalent em escopo, incluindo `bash .github/scripts/verify_environment_ci.sh`.
- [ ] Quando NGINX/tooling de navegação forem tocados, executar a verificação adicional correspondente (`docker compose config`, harness checks, ou smoke/navigation evidence) conforme a diff final.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` A diff real da fatia root foi produzida sobre o checkout atual e não sobre transporte cego do snapshot exportado. | `review` | `<planned root real-diff report>` | `local review` | `planned` | O relatório precisa citar a base real e a materialização local usada. |
| `DOD-02` | `Definition of Done` | `DOD-02` Cada superfície relevante do root foi classificada como `boilerplate base`, `project overlay / Belluga Now-specific`, ou `exclude / stale export noise`. | `review` | `<planned root real-diff report>` | `local review` | `planned` | A classificação precisa ser critério a critério. |
| `DOD-03` | `Definition of Done` | `DOD-03` O relatório consolidado da diff real foi apresentado e avaliado com o usuário antes da implementação. | `review` | `<planned user review checkpoint>` | `conversation gate` | `planned` | Gate obrigatório antes de `APROVADO`. |
| `DOD-04` | `Definition of Done` | `DOD-04` Após aprovação, o root separa corretamente base genérica e overlay downstream nas superfícies aprovadas desta fatia. | `code+review` | `<planned implementation diff + report update>` | `local root branch` | `planned` | Só pode mudar para `passed` após a implementação aprovada. |
| `DOD-05` | `Definition of Done` | `DOD-05` O contrato canônico de tooling/browser workflow permanece coerente com `tools/flutter/**` e não regride para o estado exportado em `project/tests/**`. | `review+test` | `<planned CI/tooling validation>` | `local root + navigation tooling` | `planned` | Critério material da fatia root. |
| `DOD-06` | `Definition of Done` | `DOD-06` Defaults, exemplos e nomes Belluga Now não permanecem hardcoded nas superfícies do root que forem definidas como Boilerplate base. | `review` | `<planned implementation diff + report update>` | `local root branch` | `planned` | A neutralização deve respeitar a linha de corte aprovada. |
| `DOD-07` | `Definition of Done` | `DOD-07` As validações root em escopo passam no estado reconciliado desta branch. | `test` | `<planned bash -n / verify_environment_ci / compose validation>` | `local root branch` | `planned` | Fechamento técnico da fatia. |
| `VAL-01` | `Validation Steps` | Produzir a diff real da fatia root contra o checkout atual. | `review` | `<planned root real-diff report>` | `local review` | `planned` | Primeiro gate operacional. |
| `VAL-02` | `Validation Steps` | Consolidar a classificação `base vs overlay vs exclude`. | `review` | `<planned root real-diff report>` | `local review` | `planned` | Continua o gate pré-implementação. |
| `VAL-03` | `Validation Steps` | Revisar o relatório com o usuário antes de qualquer implementação e fechar as decisões materiais. | `review` | `<planned user review checkpoint>` | `conversation gate` | `planned` | Sem isso não há `APROVADO`. |
| `VAL-04` | `Validation Steps` | Após implementação aprovada, rodar `bash -n` nas superfícies shell tocadas. | `test` | `<planned commands>` | `local root branch` | `planned` | Só após a diff final ser conhecida. |
| `VAL-05` | `Validation Steps` | Após implementação aprovada, rodar `bash .github/scripts/verify_environment_ci.sh`. | `test` | `bash .github/scripts/verify_environment_ci.sh` | `local root branch` | `planned` | CI-equivalent base da fatia root. |
| `VAL-06` | `Validation Steps` | Quando NGINX/tooling de navegação forem tocados, executar a verificação adicional correspondente. | `test` | `<planned compose/tooling/browser checks>` | `local root branch` | `planned` | O comando exato depende da diff final aprovada. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-devops`
- **Active technical scope:** `docker`
- **Expected supporting profiles:** `strategic-cto`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-devops`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-devops` | A linha de corte foi definida estrategicamente; a primeira fatia operacional agora trata só do root `belluga_now_docker` com relatório pré-implementação obrigatório. | `belluga_now_docker root`, `foundation_documentation/todos/active/agnostic_adjust/**`, `foundation_documentation/artifacts/feature-briefs/**` | `active` |

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
- [ ] `D-01` O tooling/browser workflow canônico para Boilerplate base deve continuar em `tools/flutter/**` com eventual suporte downstream, ou deve haver uma migração controlada para outra superfície explícita.
- [ ] `D-02` O inventário de rotas públicas Belluga Now (`/parceiro`, `/descobrir`, `/convites`, `/baixe-o-app`, `/agenda/evento`, etc.) deve sair das templates base de NGINX e residir apenas em overlay downstream explícito.
- [ ] `D-03` Names e defaults de runtime image (`belluga-now-*` vs nomes neutros) devem ser definidos como base genérica parametrizável ou como overlay downstream.
- [ ] `D-04` `.gitmodules` e onboarding do root devem subir ao Boilerplate com placeholders/documentação neutra, sem identidade `belluga_now_*` embutida.

## Decisions (Resolved Before Freeze)
- [x] `D-05` Esta frente deve nascer da branch atual de review `v0.2.0+8`, seguir isolada em branch dedicada, e ser rebaseada quando a base for conciliada e promovida.
- [x] `D-06` Antes de qualquer implementação no root, deve existir um relatório consolidado da diff real e uma avaliação conjunta com o usuário.
- [x] `D-07` A primeira fatia operacional desta iniciativa é o root `belluga_now_docker`; o bloco `laravel_app_refactor` fica explicitamente fora desta primeira execução.

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

## Questions To Close
- [ ] A superfície downstream de testes/navegação específica de projeto deve ser um `project/**` explícito, ou um wrapper/downstream contract sobre `tools/flutter/**`?
- [ ] O contrato de rotas públicas específicas Belluga Now deve ser extraído para `project/nginx/routes.conf` puro, ou parte dele deve permanecer no host app/Laravel por semântica canônica?

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
| `belluga_now_docker / root deterministic CI guard` | Any root CI, compose, NGINX, or tooling separation must remain compatible with the canonical root verifier. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `planned` | `bash .github/scripts/verify_environment_ci.sh` | Mandatory after implementation. |
| `belluga_now_docker / shell syntax` | Shell entrypoints may be touched (`entrypoint`, verifier helpers, smoke wrappers). | `bash -n docker/laravel-app/entrypoint.sh .github/scripts/verify_environment_ci.sh .github/scripts/preflight_promotion_runtime_builds.sh` | `Local-Implemented` | `planned` | `<planned commands>` | Final file list depends on approved diff. |
| `belluga_now_docker / compose validation` | `docker-compose.yml` and NGINX template changes must stay structurally valid. | `docker compose config` | `Local-Implemented` | `planned` | `docker compose config` | Required if compose/nginx surfaces are touched. |
| `belluga_now_docker / navigation harness policy` | If navigation tooling/harness references move, the root harness contract must remain deterministic. | `node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | `Local-Implemented` | `planned` | `node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` | Required if the approved cutline touches tooling/browser references. |

### Runtime / Rollout Notes
- Root implementation must happen on `reconcile/agnostic-adjust-boilerplate-cutline-20260608`, derived from the current `v0.2.0+8` review front.
- `foundation_documentation` must remain on the branch already in use for that repo, preserving local changes in place; no new branch is created there for this TODO.
- After the base `v0.2.0+8` front is reconciled and promoted, this branch must be rebased and any residual adjustments revalidated before closeout.
