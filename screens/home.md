# Módulo Consolidado: Tela Inicial (Home) - V3.0 (Hierarquia Otimizada)

---

### MVP (Oficial): Simplify Screen Tree — Home via Nested Scroll
*Tela inicial oficial seguindo o “Simplify Screen Tree”.*  

**Estrutura (ordem de leitura):**
1. **AppBar principal (fixo):** mesmo AppBar atual da Home.
2. **Faixa de Favoritos:** mesma faixa atual (com item fixo + favoritos).
3. **Convites pendentes:** banner logo abaixo da faixa de favoritos.
4. **Carrossel “Meus Eventos”:** somente eventos com presença confirmada, ao vivo ou futuros.
5. **Header fixo secundário:** replica o AppBar da Agenda (EventSearchScreen).
6. **Lista da Agenda:** mesma lista de eventos da tela de Agenda.

**Estados vazios:**
- “Meus Eventos”: esconder quando vazio.
- Agenda: exibir widget vazio padrão da Agenda.

---

### 1.1. Cabeçalho Principal (Topo Fixo)
| Elemento | Conteúdo |
| :--- | :--- |
| **Identidade** | `[Logo Guar[APP]ari]` \| **Olá, [Nome do Usuário]!** |
| **Ações Imediatas** | *(sem ações no AppBar principal no momento)* |

### 1.2. Destaques Rápidos (Hiper Local e Branding)
*Carrossel de círculos (stories) com o destaque Guar[APP]ari em primeiro.*

- **Destaque 1 (FIXO/Branding):** `[Círculo com Ícone Guar[APP]ari: Sugestão da IA do Dia]`
- **Destaque 2:** `[Círculo com Borda Verde/Laranja: Patrocinado]`
- **Destaque 3:** `[Círculo com Borda Azul: Seu Evento Favorito]`
- **Destaque N...**

### 1.3. Módulo Principal: Busca Conversacional (Sutil e Sempre Visível)
*O ponto de partida para a conversa com a IA.*

**Título:** **Encontre a sua Guarapari de hoje.**
**Campo de Busca:** `[Escreva ou fale: "Quero um guia para mergulho amanhã"]`
**Botão de Áudio:** `[Ícone de Microfone 🎙️]`
**CTA:** `[Botão de Ação: Buscar]`

### 1.4. Navegação Rápida (Tags de Intenção)
*Carrossel horizontal de atalhos.*

| Tag de Filtro | Módulo de Destino |
| :--- | :--- |
| `[O que fazer Hoje?]` | Agenda (`modulo_agenda.md`) |
| `[Onde Comprar?]` | Loja (`modulo_loja.md`) |
| `[Quero um Roteiro]` | Guias & Experiências (`modulo_guias_e_experiencias.md`) |
| `[Utilidades Próximas]` | Mapa (`modulo_mapa_e_mobilidade.md`) |

### 1.5. Faixa de Favoritos (Branding + Acesso Rápido)
*Carrossel horizontal com o primeiro item fixo para o perfil oficial do app.*

- **Item Fixo (Branding):** Avatar/ícone Guar[APP]ari com badge primário, sempre ancorado à esquerda (não scrolla com o restante da faixa).
- **Itens Favoritos:** Parceiros favoritados pelo usuário, ordenados após o item fixo.
- **Ação Extra:** Pílula “Procurar” como último item da faixa para abrir a descoberta.

### 1.6. Convites Pendentes (Banner)
*Banner logo abaixo da faixa de favoritos exibindo convites pendentes.*

- **Visibilidade:** somente quando houver convites pendentes.
- **Ação:** tocar abre o fluxo de convites.

---

### 2. Conteúdo Dinâmico (Blocos Modulares)

#### 2.1. Bloco: Agenda em Destaque
- **Título da Seção:** **Seu Próximo Evento**
- **Subtítulo:** *Com base nos seus artistas favoritos*
- **Componente:** `[Carrossel Horizontal de Cards de Evento]`
- **Fonte:** Puxado do `modulo_agenda.md`

#### 2.2. Bloco: Curadoria de Kits
- **Título da Seção:** **Oferta Exclusiva para Você**
- **Subtítulo:** *Kits recomendados para quem vai ao Rock Acústico*
- **Componente:** `[Carrossel Horizontal de Cards de Kits]`
- **Fonte:** Puxado do `modulo_loja.md`

#### 2.3. Bloco: Guia e Experiências
- **Título da Seção:** **Sua Próxima Aventura**
- **Subtítulo:** *Guias e Roteiros mais populares esta semana*
- **Componente:** `[Grid de Cards de Experiência (2 Colunas)]`
- **CTA Abaixo do Bloco:** `[Botão: Ver Mais Experiências]`

---

### 3. Navegação Global (Componentes Flutuantes)

#### 3.1. Ação Principal (FAB)
*O componente universal para acesso rápido ao mapa, presente na maioria das telas.*
- **Componente:** Botão de Ação Flutuante (FAB)
- **Ícone:** `[Ícone de Pin de Localização]`
- **Ação:** Abre o `modulo_mapa_e_mobilidade.md`

#### 3.2. Menu Principal Fixo (Tab Bar)
*A navegação primária do ecossistema.*

| Posição | Ícone/Título | Status |
| :--- | :--- | :--- |
| 1 | `[Home]` | **ATIVO** |
| 2 | `[Mapa]` | Inativo |
| 3 | `[Menu]` | Inativo |

*A Agenda não é mais uma aba do Tab Bar. O acesso ocorre via Home (header sticky) ou Menu.*
