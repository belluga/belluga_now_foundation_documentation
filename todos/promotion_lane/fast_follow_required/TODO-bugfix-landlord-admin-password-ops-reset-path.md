# TODO (Bugfix): Landlord Admin Password Ops Reset Path

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Promotion-lane synchronized on `2026-05-20`. The landlord password ops reset path is merged through `dev`; any newly discovered landlord-auth follow-up must open a separate TODO instead of reopening this slice.

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
- **Qualifiers:** `Bugfix`, `Laravel`, `Landlord`, `Auth`, `Ops`
- **Next exact step:** keep this slice on the promotion lane until a later explicit lane decision carries the current `dev` package forward or archives it.
- **Promotion lane path:** `dev -> stage -> main`

## Promotion Evidence

| Workstream | Promotion branch | PR / merge | Final `dev` SHA | Validation evidence |
| --- | --- | --- | --- | --- |
| `laravel-app` landlord password ops reset | `fix/direct-invite-push-and-landlord-password-ops-20260520` | backend PR `#214` merged into `dev` | `805229da74a4a5aa201a5093b8761b49c1851ae1` | Local container suite passed: `LandlordPasswordSetCommandTest`, `InvitesFlowTest`, `PushMessageFlowTest`; PR run `26188497840` green; post-merge `dev` run `26188849916` green. |
| `belluga_now_docker` gitlink sync | `bot/next-version` | docker PR `#726` merged into `dev` | `03366502be5ec2f4efd37495953dd02b1fc843e6` | PR run `26189230345` green after guarded `bot/next-version -> dev` replay; post-merge `dev` run `26189299739` green. |

## Definition of Done
- [x] Existe um comando operacional simples para redefinir a senha de um landlord por email.
- [x] O comando atualiza o credential canônico de password para todos os emails atuais do usuário.
- [x] O comando não reintroduz `password` / `password_type` legados.
- [x] O comando revoga tokens existentes para forçar novo login manual.
- [x] Há teste automatizado cobrindo sucesso e falha por email inexistente.
