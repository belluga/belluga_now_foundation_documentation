# Módulo Consolidado: Plataforma Guar[APP]ari Promoter (v1.0)

**Propósito:** Fornecer as ferramentas operacionais para que nossos parceiros possam criar eventos, gerenciar sua rede de promoters (afiliados) e dar autonomia para que os próprios promoters acompanhem seu desempenho, alimentando os dashboards de BI.

---

## 1. Protótipo: Dashboard Principal do Parceiro

*A primeira tela que o parceiro vê após o login, oferecendo um resumo e atalhos.*

- **Cabeçalho:** `[Logo Guar[APP]ari Promoter]` | **Olá, [Nome do Parceiro]!** | `[Ícone de Notificações]` | `[Ícone de Ajuda]`
- **Card de Resumo Rápido (KPIs Principais):**
    - **Receita Total (Mês):** `[Valor em Destaque]`
    - **Eventos Ativos / Produtos Cadastrados:** `[Número]`
    - **Próximo Repasse:** `[Data]`
- **Ações Rápidas (CTAs):**
    - `[Botão: Criar Novo Evento]`
    - `[Botão: Cadastrar Novo Produto/Serviço]`
    - `[Botão: Ver Relatórios de BI]`
- **Seção: Notificações e Alertas:**
    - `[Alerta: Seu cadastro está incompleto. Adicione mais fotos!]`
    - `[Notificação: Novo pedido na sua lojinha!]`
- **Menu de Navegação Lateral (Fixo):**
    - `[Ícone: Home]` Dashboard
    - `[Ícone: Eventos]` Meus Eventos
    - `[Ícone: Produtos/Serviços]` Meus Produtos/Serviços (Adapta-se ao tipo de parceiro)
    - `[Ícone: Pedidos]` Gerenciar Pedidos (Para Lojistas/Guias)
    - `[Ícone: Promoters]` Gerenciar Promoters (Para Estabelecimentos/Eventos)
    - `[Ícone: Relatórios]` BI e Desempenho
    - `[Ícone: Perfil]` Configurações do Perfil
    - `[Ícone: Pagamentos]` Dados Financeiros e Extrato

---

## 2. Protótipo: Tela de Criação e Gestão de Eventos

*A interface onde o dono do estabelecimento ou produtor cultural cadastra e gerencia seus eventos na agenda do Guar[APP]ari.*

### 2.1. Visão Geral: Meus Eventos

- **Título da Página:** Meus Eventos
- **CTA Principal:** `[Botão: + Criar Novo Evento]` -> *Inicia o Fluxo de Criação de Evento (2.2)*
- **Barra de Busca/Filtro:** `[🔎 Buscar evento...]` | `[Dropdown: Status (Ativo, Rascunho, Finalizado)]`

- **Lista de Eventos (Tabela ou Cards):**
    | Nome do Evento | Data | Status | Ingressos Vendidos | Receita Estimada | Ações |
    | :--- | :--- | :--- | :--- | :--- | :--- |
    | Samba de Raiz na Praia | 15/11/2025 | Ativo | 120 | R$ 6.000,00 | `[Botão: Editar]` `[Botão: Ver Detalhes]` `[Botão: Gerenciar Promoters]` `[Ícone: Excluir]` |
    | Festival de Verão | 20/01/2026 | Rascunho | - | - | `[Botão: Editar]` `[Ícone: Excluir]` |

---

### 2.2. Fluxo de Criação/Edição de Evento (Modal ou Nova Página)

- **Título:** Crie/Edite seu Evento
- **Abas de Navegação (se for formulário complexo):** `[Informações Básicas]` `[Ingressos]` `[Mídia]` `[Promoters]`

- **Seção: Informações Básicas**
    - **Nome do Evento:** `[Campo de Texto]` (Ex: "Samba de Raiz na Praia")
    - **Categoria:** `[Dropdown: Música, Gastronomia, Esporte, Arte & Cultura, Outros]`
    - **Local:** `[Campo de Texto com Autocomplete do Google Maps]`
    - **Endereço Completo:** `[Campo de Texto (preenchido pelo autocomplete, editável)]`
    - **Data e Hora de Início:** `[Seletor de Data e Hora]`
    - **Data e Hora de Fim:** `[Seletor de Data e Hora]`
    - **Descrição Completa:** `[Caixa de Texto com editor de formatação simples (bold, italic, links)]`

- **Seção: Ingressos**
    - `[Radio Button: Evento Gratuito | Evento Pago]`
    - **Se Evento Pago:**
        - **Preço Padrão (R$):** `[Campo Numérico]`
        - **Quantidade de Ingressos Disponíveis:** `[Campo Numérico]`
        - **Permitir Venda na Porta:** `[Checkbox]`
        - **Opções de Lotes (Opcional):** `[Botão: + Adicionar Lote]`
            - **Lote 1:** Nome: `[Campo]` Preço: `[Campo]` Quantidade: `[Campo]` Data Fim Venda: `[Seletor]`

- **Seção: Mídia**
    - **Upload de Imagem de Capa (Banner):** `[Botão de Upload]` `[Preview da Imagem]`
    - **Galeria de Fotos/Vídeos:** `[Botão de Upload (Múltiplas Imagens/Vídeos)]` `[Miniaturas com opção de reordenar/excluir]`

- **Seção: Promoters (Link para Gestão de Promoters)**
    - `[Texto: Gerencie os promoters deste evento na seção 'Gerenciar Promoters']`
    - `[Botão: Ir para Gerenciar Promoters]`

- **CTAs Finais:**
    - `[Botão: Publicar Evento]` (Se todos os campos obrigatórios estiverem preenchidos)
    - `[Botão: Salvar como Rascunho]`
    - `[Botão: Cancelar]` | `[Link: Voltar]`

---

## 3. Protótipo: Tela de Gestão de Promoters

---

## 3. Protótipo: Tela de Gestão de Promoters

*O painel onde o dono do evento convida e gerencia sua rede de afiliados para um evento específico.*

### 3.1. Visão Geral: Promoters do Evento [Nome do Evento]

- **Título da Página:** Gerenciar Promoters - [Nome do Evento]
- **Visão Geral:**
    - **Métrica 1:** `[Valor: 15]` - Promoters Ativos
    - **Métrica 2:** `[Valor: R$ 4.500,00]` - Receita Gerada por Promoters
- **CTA Principal:** `[Botão: + Convidar Novo Promoter]` -> *Inicia o Fluxo de Convite (3.2)*

- **Lista de Promoters Ativos (Tabela):**
    | Promoter (Usuário) | Link de Afiliado | Vendas | Comissão Gerada | Ações |
    | :--- | :--- | :--- | :--- | :--- |
    | `[Foto]` Maria Silva | `[Botão: Copiar Link]` | 82 | R$ 410,00 | `[Ícone: Ver Detalhes]` `[Ícone: Remover]` |
    | `[Foto]` João Costa | `[Botão: Copiar Link]` | 55 | R$ 275,00 | `[Ícone: Ver Detalhes]` `[Ícone: Remover]` |

---

### 3.2. Fluxo de Convite de Novo Promoter (Modal ou Nova Página)

- **Título:** Convidar Promoter
- **Subtítulo:** Envie um convite para um usuário se tornar promoter deste evento.
- **Campos:**
    - **Buscar Usuário:** `[Campo de Texto com Autocomplete (busca por nome/email de usuários existentes)]`
    - **Comissão (%):** `[Campo Numérico]` (Ex: 5, 10, 15 - padrão sugerido)
    - **Mensagem Personalizada (Opcional):** `[Área de Texto]`
- **CTA:** `[Botão: Enviar Convite]`

---

### 3.3. Detalhes do Promoter (Modal ou Nova Página)

- **Título:** Detalhes do Promoter - [Nome do Promoter]
- **Informações do Promoter:**
    - `[Foto de Perfil]`
    - **Nome:** Maria Silva
    - **E-mail:** maria.silva@email.com
    - **Telefone:** (XX) XXXXX-XXXX
- **Performance no Evento:**
    - **Vendas:** 82 Ingressos
    - **Comissão Gerada:** R$ 410,00
    - **Link de Afiliado:** `[Link]` `[Botão: Copiar]`
- **Ações:**
    - `[Botão: Editar Comissão]`
    - `[Botão: Remover Promoter do Evento]`

---

## 4. Protótipo: Gestão de Produtos/Serviços (Para Produtores e Guias)

*A interface onde produtores (artesãos, rurais) e guias (especialistas em experiências) gerenciam seus produtos e serviços.*

### 4.1. Visão Geral: Meus Produtos/Serviços

- **Título da Página:** Meus Produtos e Serviços
- **CTA Principal:** `[Botão: + Cadastrar Novo]` -> *Inicia o Fluxo de Cadastro (4.2)*
- **Barra de Busca/Filtro:** `[🔎 Buscar...]` | `[Dropdown: Tipo (Produto, Serviço/Experiência)]` | `[Dropdown: Status (Ativo, Rascunho, Vendido)]`

- **Lista de Produtos/Serviços (Tabela ou Cards):**
    | Nome | Tipo | Status | Preço | Estoque/Vagas | Ações |
    | :--- | :--- | :--- | :--- | :--- | :--- |
    | Sabonete Artesanal de Lavanda | Produto | Ativo | R$ 25,00 | 50 | `[Botão: Editar]` `[Ícone: Pausar]` `[Ícone: Excluir]` |
    | Roteiro Histórico: O Coração de Guarapari | Serviço | Ativo | R$ 150,00 | 10 | `[Botão: Editar]` `[Ícone: Pausar]` `[Ícone: Excluir]` |

---

### 4.2. Fluxo de Cadastro/Edição de Produto/Serviço

- **Título:** Cadastre/Edite seu Produto ou Serviço
- **Abas de Navegação:** `[Informações Básicas]` `[Preços e Estoque]` `[Mídia]`

- **Seção: Informações Básicas**
    - **Nome:** `[Campo de Texto]`
    - **Tipo:** `[Radio Button: Produto | Serviço/Experiência]`
    - **Descrição Completa:** `[Caixa de Texto com editor de formatação simples]`
    - **Categorias:** `[Tags Selecionáveis: Artesanato, Rural, Ecoturismo, Gastronomia, etc.]`
    - **Local de Retirada/Encontro (se aplicável):** `[Campo de Texto com Autocomplete]`

- **Seção: Preços e Estoque (para Produtos) / Preços e Vagas (para Serviços)**
    - **Preço (R$):** `[Campo Numérico]`
    - **Estoque (para Produtos):** `[Campo Numérico]`
    - **Vagas por Sessão (para Serviços):** `[Campo Numérico]`
    - **Disponibilidade (para Serviços):** `[Componente de Calendário para selecionar datas e horários disponíveis]`

- **Seção: Mídia**
    - **Upload de Imagem Principal:** `[Botão de Upload]` `[Preview da Imagem]`
    - **Galeria de Fotos/Vídeos:** `[Botão de Upload (Múltiplas Imagens/Vídeos)]` `[Miniaturas com opção de reordenar/excluir]`

- **CTAs Finais:**
    - `[Botão: Publicar]`
    - `[Botão: Salvar como Rascunho]`
    - `[Botão: Cancelar]` | `[Link: Voltar]`

---

## 5. Protótipo: Painel de Controle do Promoter

---

## 5. Protótipo: Painel de Controle do Promoter (Para Promoters/Influenciadores)

*A visão simplificada para o influenciador ou artista acompanhar seu desempenho em tempo real e gerenciar suas campanhas.*

### 5.1. Visão Geral: Meu Painel

- **Título da Página:** Meu Painel de Promoter
- **Card de Resumo (Geral):**
    - **Sua Comissão Total (Mês):** `[Valor: R$ 685,00]`
    - **Total de Ingressos Vendidos:** `[Valor: 137]`
    - **Cliques nos Links:** `[Valor: 2.500]`
- **Ações Rápidas:**
    - `[Botão: Ver Relatórios Detalhados (BI)]` -> *Leva para o `modulo_promoter_bi.md`*

---

### 5.2. Seção: Minhas Campanhas Ativas

- **Título da Seção:** Minhas Campanhas Ativas
- **Componente: Lista de Eventos/Produtos Promovidos**
    - **Card de Campanha (Evento):**
        - **Nome:** Samba de Raiz na Praia
        - **Estabelecimento:** Bar do Zé
        - **Meu Desempenho:** 25 Ingressos | R$ 125,00 de Comissão
        - **Meu Link Exclusivo:** `[campo de texto com link]` `[Botão: Copiar]`
        - **Status:** `[Tag: Ativo]`
        - **CTA:** `[Botão: Ver Estatísticas Detalhadas]` -> *Leva para o `modulo_promoter_bi.md` (filtrado por este evento)*
    - **Card de Campanha (Produto):**
        - **Nome:** Kit Guarapari Gourmet
        - **Produtor:** Café do Sítio Alegre
        - **Meu Desempenho:** 5 Kits Vendidos | R$ 50,00 de Comissão
        - **Meu Link Exclusivo:** `[campo de texto com link]` `[Botão: Copiar]`
        - **Status:** `[Tag: Ativo]`
        - **CTA:** `[Botão: Ver Estatísticas Detalhadas]` -> *Leva para o `modulo_promoter_bi.md` (filtrado por este produto)*

---

### 5.3. Seção: Convites Pendentes

- **Título da Seção:** Convites para Promover
- **Componente: Lista de Convites**
    - **Card de Convite:**
        - **Evento/Produto:** Festival de Inverno
        - **Convidado por:** Pousada da Montanha
        - **Comissão Oferecida:** 10%
        - **Ações:** `[Botão: Aceitar]` `[Botão: Recusar]`

---

## 6. Protótipo: Gerenciamento de Pedidos (Para Lojistas/Guias)

*A interface onde lojistas e guias gerenciam os pedidos e reservas recebidos.*

### 6.1. Visão Geral: Meus Pedidos

- **Título da Página:** Meus Pedidos e Reservas
- **Barra de Busca/Filtro:** `[🔎 Buscar pedido...]` | `[Dropdown: Status (Novo, Em Preparação, Enviado/Concluído, Cancelado)]` | `[Dropdown: Tipo (Produto, Serviço)]`

- **Lista de Pedidos/Reservas (Tabela ou Cards):**
    | ID do Pedido | Cliente | Item | Data | Status | Valor | Ações |
    | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
    | #00123 | Maria Clara | Sabonete Artesanal | 28/10/2025 | Novo | R$ 25,00 | `[Botão: Ver Detalhes]` `[Botão: Mudar Status]` |
    | #00124 | João Pedro | Roteiro Histórico | 29/10/2025 | Concluído | R$ 150,00 | `[Botão: Ver Detalhes]` |

---

### 6.2. Detalhes do Pedido/Reserva (Modal ou Nova Página)

- **Título:** Detalhes do Pedido #00123
- **Informações do Cliente:**
    - **Nome:** Maria Clara
    - **Contato:** maria.clara@email.com | (XX) XXXXX-XXXX
- **Itens do Pedido:**
    - **Produto:** Sabonete Artesanal de Lavanda (x1)
    - **Preço:** R$ 25,00
- **Status do Pedido:** `[Dropdown: Novo, Em Preparação, Enviado/Concluído, Cancelado]`
- **Informações de Entrega/Retirada:**
    - **Tipo:** Retirada no Local
    - **Endereço:** Rua Exemplo, 123
    - **Data/Hora:** 29/10/2025, 14:00
- **Histórico de Status:**
    - 28/10/2025 10:00 - Pedido Recebido
    - 28/10/2025 10:30 - Status alterado para 'Em Preparação'
- **Ações:**
    - `[Botão: Entrar em Contato com Cliente]`
    - `[Botão: Imprimir Pedido]`
    - `[Botão: Salvar Alterações]`