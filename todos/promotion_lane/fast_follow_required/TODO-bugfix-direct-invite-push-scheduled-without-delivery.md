# TODO (Bugfix): Direct Invite Push Scheduled Without Delivery

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Promotion-lane synchronized on `2026-05-20`. The direct-invite push hardening is merged through `dev`, with local E2E proof to real FCM and device proof on foreground/background; any new push regressions from this point must open a separate TODO.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Em produção, convites diretos geram documentos em `push_messages`, mas eles permanecem indefinidamente em `scheduled`.
- Não há `push_delivery_logs`, o que indica que o pipeline morre antes da entrega efetiva ou retorna silenciosamente sem materializar destino/resultado.

## Contract Boundary
- Este TODO cobre o caminho de push de convite direto até sair de `scheduled` com evidência material de entrega, rejeição ou skip explícito.
- Ele cobre autoring do invite push, resolução de destinatários, job de envio e logging/terminal state.
- Ele não cobre melhorias amplas de push fora do caso de convite direto.

## Delivery Status Canon
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Bugfix`, `Laravel`, `Push`, `Invites`, `Production`
- **Next exact step:** keep this slice on the promotion lane until a later explicit lane decision carries the current `dev` package forward or archives it.
- **Promotion lane path:** `dev -> stage -> main`

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

## Definition of Done
- [x] Convite direto elegível não fica preso para sempre em `scheduled`.
- [x] O job registra delivery log ou estado terminal explícito quando não há destinatário elegível.
- [x] Falhas/salidas antecipadas deixam rastreabilidade suficiente para operação.
- [x] Há teste cobrindo o branch real que hoje prende a mensagem.
