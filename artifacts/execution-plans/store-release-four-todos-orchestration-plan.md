# Plano de Orquestração — Store Release Android (4 TODOs Ativos)

**Data:** 2026-04-27  
**Contexto:** Sessão de continuidade após verificação de TODOs de 2026-04-27.  
**Perfil:** Operational / Coder  
**Artifact type:** `execution-plan` (não-autoritativo — plano de sessão, não TODO PACED)

**Session review override (2026-04-28):** nesta orquestração, a revisão via Claude CLI é tratada como gate obrigatório de sessão para cada TODO entregue. Ela continua não-canônica para Delphi/PACED e não deve ser consolidada em SKILL sem decisão posterior.

---

## Estado de Entrada

### TODOs confirmados como completed (já movidos)
- `completed/TODO-v1-screen-public-account-profile-detail-polish.md` — arquivado em 2026-04-27 21:58. Sobre/content fidelidade coberta por sibling abaixo.
- `completed/TODO-store-release-account-profile-rich-text-fidelity.md` — Production-Ready, stage promotion completa. Cobre `bio`/`content` Account Profile + Event `Sobre` com o mesmo padrão de fidelidade.
- `completed/TODO-v1-first-release.md` — superseded pelo orquestrador `TODO-store-release-android.md`.

### TODOs ativos para este ciclo de orquestração

| # | TODO | Stage | Qualifiers | ADB Necessário |
|---|------|-------|------------|----------------|
| T1 | `TODO-store-release-web-to-app-conversion-gate.md` | `Implementation-Ready` | Policy-Frozen, Cross-Stack, Release-Blockers-Open | Apenas validação final de dispositivo real |
| T2 | `TODO-store-release-phone-otp-auth-and-contact-match.md` | `Implementation-Ready` | Business-Core, Cross-Stack, Release-Critical, Upstream-Baseline-Ready | OTP recebido no device, smoke final |
| T3 | `TODO-store-release-minimal-friends-and-favorites-mvp.md` | `Implementation-Ready` | Business-Core, Cross-Stack, Release-Critical | Permissão OS de contatos, smoke de contact_match |
| T4 | `TODO-store-release-funnel-metrics-validation.md` | `Implementation-Ready` | Cross-Stack, Release-Critical, Metrics-Evidence | Prova de event firing em device real |

### TODOs fora deste ciclo (context reference)
- `TODO-v1-screen-invite-polish.md` — Pending, status legend corrigida (era `[x]` em Production-Ready por erro).
- `TODO-v1-screen-user-profile-polish.md` — Pending, status legend corrigida.
- Esses dois são Store Release candidates mas sem bloqueio imediato; podem entrar em ciclo separado após T1-T4.

---

## Grafo de Dependências

```
T2 (phone-otp) ──────────────────┐
  └─ contact-match baseline      ├──► T3 (minimal-friends)
  └─ auth-wall upgrade path ─────┘
                                 │
T1 (web-to-app) ─────────────────┤
  └─ conversion flow events      │
                                 ▼
T2 + T3 + T1 ───────────────────► T4 (funnel-metrics)
```

**Regras de bloqueio:**
- T3 não pode ser finalizado sem T2 (contact_match exige identidade verificada por phone OTP).
- T1 deve estar implementado antes de T4 validar os eventos de conversão (`web_open_app_clicked`, `app_deferred_deep_link_captured`).
- T4 é o último a fechar porque valida evidência de todos os fluxos.

---

## Classificação ADB por TODO

| TODO | ADB requerido para... | Pode adiantar sem ADB |
|------|----------------------|----------------------|
| T1 web-to-app | install/store/deferred link real-device | ✅ Toda implementação e smoke lógico |
| T2 phone-otp | receber OTP, smoke de auth cutover | ✅ Backend completo, Flutter auth cutover |
| T3 minimal-friends | permissão OS contatos, import, smoke | ✅ Backend endpoints, Flutter UI até contact-list |
| T4 funnel-metrics | event firing proof em device real | ✅ Matrix freeze, audit de eventos, sink/query proof |

**Princípio:** tudo que não exige permissão de SO, dispositivo físico, ou store install acontece antes das fases ADB. As fases ADB são consolidadas no final como uma sessão única de device.

---

## Faseamento

### Fase 1 — T1: Web-to-App Conversion Gate (sem ADB)

**Objetivo:** fechar os 3 blockers de release da conversion gate sem reabrir política.

**Escopo (não-ADB):**
- [ ] Substituir experiência `testerWaitlist` em `/baixe-o-app` pela promotion screen real com store handoff Android/iOS.
- [ ] Preservar redirect intent além de invite-only (event detail, guarded-route intent sobrevive install/open).
- [ ] Validar rota `/open-app` com `platform_target` override.
- [ ] Garantir que hard/auth gates promovem para app-promotion route, não para o form antigo.

**Gate de entrega:**
1. Triple Audit independente (T1 bounded package) — `/audit-protocol-triple-review`
2. [Gate de sessão — não-canônico PACED] Claude CLI review:
   ```bash
   claude --print "$(cat foundation_documentation/artifacts/T1-web-to-app-review-packet.md)" \
     > foundation_documentation/artifacts/claude-cli-reviews/T1-web-to-app-cli-review.md
   ```
   Salvar resultado para comparação futura com o triple review output.
3. Cruzar achados Claude CLI vs triple review:
   - se o Claude trouxer ponto relevante e os revisores concordarem ao cruzar argumentos, integrar/resolver e seguir;
   - se houver impasse material, divergência relevante, ou mudança de direção/escopo, chamar o usuário antes de avançar.
4. Triple review `clean|accepted-debt` + Claude CLI salvo e triado → avançar para Fase 2.

**ADB (reservado para Fase 5):** real-device install/store/deferred link.

---

### Fase 2 — T2: Phone OTP Auth And Contact Match (sem ADB)

**Objetivo:** substituir email/password tenant-public por phone-first OTP com contact-hash materialização.

**Escopo (não-ADB):**
- [ ] Backend: endpoint phone OTP + WhatsApp/SMS dispatch + anonymous-to-authenticated merge.
- [ ] Backend: contact-hash materialização hardened para contatos importados.
- [ ] Flutter: auth cutover — substituir email/password entry por phone-first OTP screen.
- [ ] UX: phone-entry + OTP verification screens com hierarquia clara, feedback de validação/422, loading states, keyboard-safe layout.
- [ ] Remover Belluga tenant-public dependência de email/password no release path.

**Gate de entrega:**
1. Triple Audit independente (T2 bounded package) — `/audit-protocol-triple-review`
2. [Gate de sessão — não-canônico PACED] Claude CLI review:
   ```bash
   claude --print "$(cat foundation_documentation/artifacts/T2-phone-otp-review-packet.md)" \
     > foundation_documentation/artifacts/claude-cli-reviews/T2-phone-otp-cli-review.md
   ```
3. Cruzar achados Claude CLI vs triple review:
   - se o Claude trouxer ponto relevante e os revisores concordarem ao cruzar argumentos, integrar/resolver e seguir;
   - se houver impasse material, divergência relevante, ou mudança de direção/escopo, chamar o usuário antes de avançar.
4. Triple review `clean|accepted-debt` + Claude CLI salvo e triado → avançar para Fase 3.

**ADB (reservado para Fase 5):** receber OTP no device, smoke de fluxo completo auth-upgrade.

---

### Fase 3 — T3: Minimal Friends And Favorites MVP (sem ADB)

**Objetivo:** entregar o core de contact_match → favorite → friend + contact_groups sem widening para belluga_connections completo.

**Escopo (não-ADB):**
- [ ] Backend: `/contacts/import` → contact_match acquisition pipeline.
- [ ] Backend: favorite/friend derivados, contact_groups privados (tag-like, sem alterar privacidade/friendship semantics).
- [ ] Backend: exposure rules (viewer-scoped) para `Contatos` e `contact_groups`.
- [ ] Flutter: contact import UI, friends/favorites list, group management em `/convites/compartilhar`.
- [ ] Não expandir para: people discovery genérico, chat, workspace analytics, social feed.

**Gate de entrega:**
1. Triple Audit independente (T3 bounded package) — `/audit-protocol-triple-review`
2. [Gate de sessão — não-canônico PACED] Claude CLI review:
   ```bash
   claude --print "$(cat foundation_documentation/artifacts/T3-minimal-friends-review-packet.md)" \
     > foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review.md
   ```
3. Cruzar achados Claude CLI vs triple review:
   - se o Claude trouxer ponto relevante e os revisores concordarem ao cruzar argumentos, integrar/resolver e seguir;
   - se houver impasse material, divergência relevante, ou mudança de direção/escopo, chamar o usuário antes de avançar.
4. Triple review `clean|accepted-debt` + Claude CLI salvo e triado → avançar para Fase 4.

**ADB (reservado para Fase 5):** permissão OS de contatos, import real, smoke de contact_match completo.

---

### Fase 4 — T4: Funnel Metrics Validation (sem ADB físico)

**Objetivo:** provar que os eventos de funil de release chegam ao sink com as propriedades corretas.

**Escopo:**
- [ ] Freezar a funnel-metrics matrix: quais eventos devem disparar, quais propriedades cada um deve carregar.
- [ ] Auditar implementações existentes contra a matrix (sem reabrir provider ou DI decisions).
- [ ] Fixar eventos faltantes — a correção vai para o TODO dono do fluxo (T1/T2/T3), não aqui.
- [ ] Sink/query proof: confirmar que eventos chegam e podem ser lidos de volta com confiança para KPI release.
- [ ] Não reabrir telemetry architecture review.

**Gate de entrega:**
1. Triple Audit independente (T4 bounded package) — `/audit-protocol-triple-review`
2. [Gate de sessão — não-canônico PACED] Claude CLI review:
   ```bash
   claude --print "$(cat foundation_documentation/artifacts/T4-funnel-metrics-review-packet.md)" \
     > foundation_documentation/artifacts/claude-cli-reviews/T4-funnel-metrics-cli-review.md
   ```
3. Cruzar achados Claude CLI vs triple review:
   - se o Claude trouxer ponto relevante e os revisores concordarem ao cruzar argumentos, integrar/resolver e seguir;
   - se houver impasse material, divergência relevante, ou mudança de direção/escopo, chamar o usuário antes de avançar.
4. Triple review `clean|accepted-debt` + Claude CLI salvo e triado → avançar para Fase 5 (ADB).

**ADB (reservado para Fase 5):** event firing proof em dispositivo real (device-side confirmation que eventos disparam nos fluxos corretos).

---

### Fase 5 — Sessão ADB Consolidada (último)

**Pré-requisito:** Fases 1–4 com triple review `clean` ou `accepted-debt` registrado, Claude CLI salvo, e divergências triadas sem impasse pendente.

**Protocolo de preparação WSL/ADB (executar ANTES de conectar device):**
```bash
# 1. Fechar processos desnecessários
# 2. Limpar caches Flutter
fvm flutter clean
# 3. Verificar memória disponível
free -h
# 4. Garantir que só o processo ADB está rodando
adb kill-server
adb start-server
```

**Smoketests por TODO (em sequência, parar e registrar ao primeiro falho):**

**T1 — web-to-app real device:**
- [ ] Install via Play Store link a partir de `/baixe-o-app` (não via adb install direta).
- [ ] Verificar deferred deep link resolve invite code após install.
- [ ] Verificar redirect intent preservado para event detail.
- [ ] Verificar fallback para `/` quando sem intent.

**T2 — phone-otp real device:**
- [ ] Fluxo completo de phone entry → OTP recebido → authenticated upgrade.
- [ ] Verificar anonymous-to-authenticated merge (invite attribution preservado).
- [ ] Verificar que email/password entry não está mais exposta.

**T3 — contact match real device:**
- [ ] Grant permissão OS de contatos.
- [ ] Import → contatos resolvidos em `Contatos`.
- [ ] Favorite de um matched contact → aparece em friends (reciprocidade simulada).
- [ ] contact_groups: criar grupo, adicionar contato, verificar em `/convites/compartilhar`.

**T4 — funnel events real device:**
- [ ] Disparar cada evento da matrix freezada.
- [ ] Verificar chegada no sink com propriedades corretas.
- [ ] Confirmar KPIs de release são legíveis.

---

## Protocolo de Revisão Claude CLI (Gate de Sessão)

**Objetivo:** coletar revisão externa crítica usando o CLI do Claude em paralelo com o triple review canônico, bloquear avanço quando houver achado material não triado, e preservar os outputs para comparação posterior.

**Status:** gate obrigatório desta orquestração. Continua não-canônico PACED/Delphi e não entra em SKILL sem decisão posterior.

**Formato de invocação:**
```bash
claude --print "$(cat foundation_documentation/artifacts/<slug>-review-packet.md)" \
  > foundation_documentation/artifacts/claude-cli-reviews/<slug>-cli-review.md
```

**Storage:** `foundation_documentation/artifacts/claude-cli-reviews/` (criar se não existir)

**Comparação posterior:**
- Após T1–T4 completos: comparar achados do Claude CLI vs triple review por TODO.
- Registrar: (a) achados exclusivos do CLI não capturados pelo triple, (b) falsos positivos do CLI vs triple, (c) achados concordantes.
- Resultado vira insumo para decidir se Claude CLI review entra como etapa canônica futura.

**Protocolo de decisão:**
- Rodar Claude CLI para cada TODO antes de avançar para o próximo TODO.
- Classificar achados como `material`, `non-blocking`, `false-positive`, ou `out-of-scope`.
- Quando o Claude trouxer achado material não capturado pelo triple review, cruzar o argumento com os revisores do triple review usando pacote/summary bounded.
- Se houver concordância material, integrar a correção ou registrar accepted-debt quando não bloqueante.
- Se houver impasse material, divergência relevante, ou mudança de direção/escopo, chamar o usuário antes de avançar.

**Restrições:**
- Este protocolo NÃO é canônico PACED/Delphi — não entra no SKILL nesta sessão.
- Não misturar achados do CLI com os achados formais do triple review session; manter trilhas separadas e registrar a adjudicação Delphi/session-gate à parte.
- Não promover a próxima fase sem resultado do CLI salvo e triado para o TODO atual.

---

## Cadência de Progresso

| Fase | TODO | Gate | Próximo passo |
|------|------|------|---------------|
| 1 | T1 web-to-app | Triple `clean|accepted-debt` + CLI saved/triaged | Fase 2 |
| 2 | T2 phone-otp | Triple `clean|accepted-debt` + CLI saved/triaged | Fase 3 |
| 3 | T3 minimal-friends | Triple `clean|accepted-debt` + CLI saved/triaged | Fase 4 |
| 4 | T4 funnel-metrics | Triple `clean|accepted-debt` + CLI saved/triaged | Fase 5 (ADB) |
| 5 | T1+T2+T3+T4 ADB | Device smoke pass | Fechar todos os TODOs |

**Regra crítica (WSL stability):** se ADB travar ou WSL precisar de reset em Fase 5, retomar do último smoke check documentado. Não reiniciar toda a Fase 5.

---

## Referências

- SKILL atualizado: `delphi-ai/skills/audit-protocol-triple-review/SKILL.md` (Orchestration Cadence adicionada)
- Orquestrador: `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- Policy: `foundation_documentation/policies/web_to_app_promotion_policy.md`
- Upstream baseline OTP: `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`
