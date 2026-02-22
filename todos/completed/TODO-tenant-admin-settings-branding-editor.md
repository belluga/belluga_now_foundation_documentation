# TODO: Tenant Admin Settings - Tenant Branding Editor (Name, Logos/Icons, Colors)

## Contexto
Precisamos evoluir a tela `Tenant Admin > Configurações` para permitir edição de branding do tenant no fluxo admin, reaproveitando o pipeline de imagem já padronizado (pick/crop/upload) e mantendo aderência ao contrato backend atual.

## Scope
- Flutter (`flutter-app`) somente nesta entrega.
- Adicionar seção de branding em `TenantAdminSettingsScreen` com:
  - Nome do tenant (campo de edição no formulário).
  - Cores de branding (`brightness_default`, `primary_seed_color`, `secondary_seed_color`).
  - Logos e ícones (`light_logo_uri`, `dark_logo_uri`, `light_icon_uri`, `dark_icon_uri`, `favicon_uri`, `pwa_icon`).
- Reaproveitar o fluxo de imagem admin existente (picker + crop + upload multipart).
- Garantir que para logos/ícones o pipeline preserve transparência quando a origem for PNG com alpha.
- Atualizar repository/controller/domain de settings para payload/parse de branding.
- Cobertura de testes Flutter para screen/controller/repository tocados.

## Out of scope
- Mudanças em pipeline CI/CD.
- Mudanças de autenticação/autorização de backend.
- Refatorações amplas fora de `tenant_admin/settings` e utilitários de imagem estritamente necessários.

## Gap de contrato identificado
- O backend tenant admin possui endpoint de branding (`POST /v1/branding/update`) com `theme_data_settings` + `logo_settings`, mas **não há contrato explícito nesse endpoint para persistência do nome do tenant**.

## Decisões
1. Persistência do nome do tenant:
- **Escolhida:** Opção A (Flutter-only nesta entrega).
- Campo de nome será exibido no editor de branding e mantido no fluxo de formulário.
- O salvamento remoto seguirá estritamente o contrato atual do endpoint `/admin/api/v1/branding/update` (theme + logos/ícones).
- O UI deixará explícito que a persistência de nome requer endpoint dedicado no tenant-admin contract.

2. Tenant de validação manual:
- Tenant de teste informado para os testes funcionais: `guarappari`.

## Definition of done
- [x] Seção de branding implementada em `TenantAdminSettingsScreen`.
- [x] Controller de settings com estado/ações para branding (sem lógica de negócio no widget).
- [x] Repository de settings com request multipart para branding.
- [x] Upload/crop de logos e ícones reutilizando fluxo admin existente.
- [x] Transparência PNG preservada para logos/ícones no pipeline ajustado.
- [x] Testes atualizados/criados e green para arquivos tocados.
- [x] Aderência de arquitetura Flutter validada.
- [x] Scan de smells/performance executado e sem regressões relevantes.

## Validation steps
1. `flutter test` (foco em settings + image flow tocado).
2. `flutter analyze`.
3. Auditoria de aderência (regras de arquitetura).
4. Scanner de smells/performance em arquivos tocados.
5. (Se necessário) validação manual rápida de crop/upload com ADB disponível.
