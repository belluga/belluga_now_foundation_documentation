# Módulo Consolidado: Mapa e Mobilidade (v1.0)

**Propósito:** Fornecer uma visualização geográfica unificada de todos os pontos de interesse (Agenda, Loja, Guias, Utilidade Pública), com filtros potentes e atalhos rápidos para aplicativos de mobilidade.

---

## 1. Tela Principal: Mapa (`mapa.md`)

### 1.0. Gate de Localização (Permissão + Serviços)

Algumas experiências do módulo (ex.: listagem de endereços/POIs próximos, ordenação por distância, “buscar nesta área”) dependem da localização atual do usuário. Para garantir consistência do produto e a integração com o backend (GeoQuery), o acesso a telas que **precisam** de localização deve ser protegido por um **Guard de Navegação**.

**Regras do Guard (alto nível):**
- Se **serviços de localização** do dispositivo estiverem desativados: redirecionar para uma tela convidativa pedindo para ativar os serviços.
- Se **permissão** de geolocalização não estiver concedida: redirecionar para uma tela convidativa pedindo para liberar a permissão.
- Se a permissão estiver em **“negada para sempre”**: orientar o usuário a abrir as configurações do app.
- Somente permitir a navegação para as telas dependentes após a condição adequada estar atendida.

**Tela convidativa (copy/UX):**
- Explicar o benefício: “mostrar locais próximos”, “ordenar por distância”, “buscar pontos perto de você”.
- CTA principal: “Permitir localização”.
- CTA secundário: “Abrir configurações” (quando aplicável) e “Ativar serviços de localização” (quando aplicável).

### 1.1. Arquitetura de Visualização (Prioridade do Mapa)
- **Componente Principal:** Visualização de Mapa Interativo.
- **Pins (Ícones):** Iconografia clara para diferenciar Eventos, Lojas/Produtores, Guias e Pontos de Interesse (Ex: Farmácia, Ponto de Táxi).
- **FAB de Retorno:** O ícone de `[Ícone de Localização]` (Action Button flutuante) é mantido para centrar o mapa na localização atual do usuário.

### 1.2. Ferramentas de Busca e Filtro (Runtime Atual)

#### A. Barra de Pesquisa Geográfica
- **Topo da Tela:** `[🔎 Buscar evento, lojinha, guia ou endereço...]` (Permanente).

#### B. FAB Filters Dinâmicos (Contrato Atual)
- Os botões de categoria são montados dinamicamente a partir de `GET /api/v1/map/filters`.
- Cada item pode trazer `label` e `image_uri` (decorado por `settings.map_ui.filters` no tenant admin).
- Quando o backend envia `query` por categoria (`source`, `types`, `categories`, `taxonomy`, `tags`), o app aplica esse payload diretamente ao tocar no FAB.
- Quando não há `query` explícita, o app usa fallback por chave da categoria (`categories=[key]`).
- Tocar no FAB ativo limpa o filtro (toggle-off).
- O botão `Limpar filtros` aparece quando há filtros ativos.
- Enquanto uma recarga de filtros/POIs está em andamento, a interação com FABs fica bloqueada para evitar requisições concorrentes.

#### C. Observação de Evolução
- O fluxo antigo de tags expansíveis em duas linhas não representa mais o comportamento runtime atual e foi substituído pelo catálogo dinâmico de FAB filters.

---

## 2. Componente: Card de Detalhe Flutuante (Bottom Sheet)

*Abre ao clicar em um Pin no mapa. Contém o **Atalho de Rota Externa**.*

### 2.1. Card para LOCAL/ESTABELECIMENTO (Ex: Restaurante, Bar, Loja)

- **Título:** Nome do Local
- **Informações:** `[Endereço, Nota ⭐️ 4.7]`
- **Ações Imediatas:**
    - **CTA:** `[Botão: Ver Detalhes (Página do Local)]`
    - **Atalho de Rota Externa:** `[Ícone: Waze]` | `[Ícone: Uber]` | `[Ícone: Google Maps]`
- **Conteúdo Integrado (Agenda):**
    - **Título da Seção:** Próximos Eventos no Local
    - `[Carrossel Horizontal de Cards de Evento (Puxados do modulo_agenda.md)]`

### 2.2. Card para EVENTO (Temporário)

- **Título:** Nome do Evento
- **Informações:** `[Local, Data e Hora, Preço]`
- **Ações Imediatas:**
    - **CTA:** `[Botão: Comprar Ingresso / RSVP]`
    - **Atalho de Rota Externa:** `[Ícone: Waze]` | `[Ícone: Uber]` | `[Ícone: Google Maps]`

### 2.3. Card para LOJINHA/PRODUTOR

- **Título:** Nome da Lojinha/Produtor
- **Informações:** `[Tipo: Rural / Artesanal, Endereço de Retirada (se aplicável), Avaliação]`
- **Ações Imediatas:**
    - **CTA:** `[Botão: Visitar Lojinha (Página de Produtos)]` -> *Leva para `loja_produtor.md`*
    - **Atalho de Rota Externa:** `[Ícone: Waze]` | `[Ícone: Uber]` | `[Ícone: Google Maps]` (Apenas se o produtor permitir visita ou retirada local).

---

## Próximo Passo Estratégico

Com os módulos de Agenda, Loja e Mapa consolidados, temos a estrutura básica para o conteúdo. Agora, a decisão é sobre qual experiência de usuário priorizar:

1.  **Refinamento do Conteúdo Premium:** Auditoria e aprimoramento do **`modulo_guias_e_experiencias.md`**, focando na **Geração de Roteiros por IA** (o nosso produto *premium*).
2.  **O Rosto do Aplicativo:** Criação da tela inicial (**`home.md`**), que será o *dashboard* personalizado que unifica todo o conteúdo existente.

**Qual deles deve ser o foco da próxima prototipagem?**
