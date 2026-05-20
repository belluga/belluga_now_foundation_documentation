# TODO (Bugfix): Invite Screen App Pane Loading

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Promotion-lane synchronized on `2026-05-20`. The `APP` pane loading hardening is merged through `dev`; any deeper invite-match performance follow-up must open a separate TODO instead of rewriting this one.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- A lista de usuários na tela de convites demora de forma anormal.
- A evidência atual aponta que a abertura do pane `APP` aguarda trabalho caro de import/match de contatos antes de publicar os inviteables do backend.

## Contract Boundary
- Este TODO cobre apenas o loading da tela de convites no pane `APP`.
- Ele cobre controller/repository/testes necessários para não bloquear o primeiro render útil em trabalho de contatos que não é requisito do pane atual.
- Ele não cobre push, SSE, ou redesign visual da tela.

## Delivery Status Canon
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Bugfix`, `Flutter`, `Invites`, `Performance`
- **Next exact step:** keep this slice on the promotion lane until a later explicit lane decision carries the current `dev` package forward or archives it.
- **Promotion lane path:** `dev -> stage -> main`

## Evidence Snapshot
- O `init()` do controller passou a disparar o warmup de contatos em background em vez de bloquear o fetch dos inviteables do backend.
- O warmup ainda hidrata cache/imported matches e republica o estado quando termina, mas isso não segura mais o primeiro render útil do pane `APP`.
- A montagem de `localContactDisplaysByHash` deixou de recalcular hash local quando os recipients do backend não trazem `contact_hash`, reduzindo trabalho desnecessário no caminho quente.
- O backend ainda pode ter custo material em import frio/stale de contatos, mas esse custo saiu do caminho crítico da lista de usuários do `APP`.

## Promotion Evidence

| Workstream | Promotion branch | PR / merge | Final `dev` SHA | Validation evidence |
| --- | --- | --- | --- | --- |
| `flutter-app` invite app-pane loading | `fix/invite-screen-app-pane-loading-20260520` | frontend PR `#323` merged into `dev` | `90040672081b5f2dc573ab5066231aeace0a8e33` | Local focused test passed: `invite_share_screen_controller_test.dart`; PR run `26188498596` green; post-merge `dev` run `26189010439` green. |
| `belluga_now_docker` gitlink sync | `bot/next-version` | docker PR `#726` merged into `dev` | `03366502be5ec2f4efd37495953dd02b1fc843e6` | PR run `26189230345` green after guarded `bot/next-version -> dev` replay; post-merge `dev` run `26189299739` green. |

## Definition of Done
- [x] O pane `APP` não fica bloqueado por import/match de contatos para mostrar inviteables.
- [x] A tela continua usando cache/matches existentes quando disponíveis.
- [x] Refresh/import de contatos vira trabalho de background ou fica restrito ao pane de contatos.
- [x] Há teste cobrindo o comportamento não bloqueante.
