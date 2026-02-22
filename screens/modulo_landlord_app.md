# Documentação: Módulo Landlord App (V1)
**Version:** 1.0

## 1. Objetivo
Definir a experiência base do aplicativo landlord em V1 com quatro superfícies obrigatórias:
1. Landing page do projeto.
2. Lista de tenants atuais.
3. Botão de login landlord/admin.
4. Acesso à área administrativa após login.

## 2. Rota e Escopo
- **Rota landlord home:** `/landlord`
- **Guard:** `LandlordRouteGuard`
- **Contexto permitido:** host landlord ou `EnvironmentType.landlord`.
- **Contexto negado:** tenant app (incluindo deep links para rotas landlord/admin).

## 3. Superfícies de UI (V1)

### 3.1 Landing
- Hero de apresentação do Bóora! Control Center.
- Mensagem de propósito focada em governança, multi-tenant e escala.

### 3.2 Lista de Tenants
- Fonte: bootstrap já carregado no app (`AppData.domains` e fallback em `AppData.appDomains`).
- Exibir estado vazio quando não houver tenants no payload atual.
- Não criar endpoint novo para V1 apenas para essa listagem.

### 3.3 Login CTA
- Exibir `Entrar como Admin` quando não houver sessão landlord ativa.
- Acionar `showLandlordLoginSheet` para autenticação landlord.

### 3.4 Admin CTA pós-login
- Exibir `Acessar área admin` apenas quando:
  - sessão landlord válida; e
  - modo landlord ativo (`AdminMode.landlord`).
- Navegação para `TenantAdminShellRoute` (`/admin`).

## 4. Regras de Acesso
- Tenant profile não deve expor entrada de admin landlord.
- Auth compartilhado só exibe `Entrar como Admin` em contexto landlord.
- `LandlordRouteGuard` deve permitir landlord/admin somente por contexto landlord (host/ambiente), sem bypass por estado local persistido em contexto tenant.

## 5. Integrações
- **Auth landlord:** `LandlordAuthRepositoryContract`.
- **Modo admin:** `AdminModeRepositoryContract`.
- **Tenant list:** `AppDataRepositoryContract`.
- **Admin area:** `TenantAdminShellRoute`.

## 6. Validação mínima
- `fvm flutter analyze` sem issues.
- Teste de landlord home cobrindo:
  - visibilidade de `Entrar como Admin` sem sessão;
  - visibilidade de `Acessar área admin` com sessão + modo landlord;
  - render da lista de tenants.
