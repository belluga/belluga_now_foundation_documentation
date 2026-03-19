# Framework Genérico de Prompt Visual para Pitch Decks
**Versão:** 1.0  
**Objetivo:** definir um sistema reutilizável de prompts para gerar slides visualmente coerentes ao longo de um pitch deck, independentemente do tema, startup ou setor.

## 1. Propósito
Este documento serve como base para orientar IAs visuais na criação de decks com unidade estética, continuidade narrativa e consistência de composição.

Ele deve ser usado quando o objetivo não é apenas gerar slides bonitos isoladamente, mas construir uma apresentação inteira que pareça um sistema visual único.

## 2. Como Usar
Fluxo recomendado:

1. Preencher os insumos do deck.
2. Enviar o `Prompt Mestre de Coerência Visual`.
3. Enviar o `Prompt de Direção Geral do Deck`.
4. Iterar slide por slide com o `Template de Prompt por Slide`.
5. Revisar coerência entre os slides antes de fechar a versão final.

## 3. Insumos do Deck
Antes de escrever prompts, defina estes elementos:

- nome da marca ou projeto;
- tipo de pitch: investidor, edital, comercial, institucional, demo day;
- público-alvo da apresentação;
- narrativa principal do deck;
- principais emoções por bloco narrativo;
- universo visual desejado;
- lista de assets reais disponíveis;
- lista de assets inexistentes que precisarão de composição conceitual;
- restrições de layout;
- exigências de sobreposição, como vídeo do fundador, legenda, QR code ou marca d'água.

## 4. Princípios de Coerência Visual
Todos os slides devem obedecer a um sistema comum.

### 4.1 Continuidade Narrativa
- O deck deve parecer uma narrativa visual contínua, não uma coleção de imagens independentes.
- Cada slide deve representar um beat narrativo único.
- Mudanças de cena devem ser deliberadas e motivadas pela fala.

### 4.2 Continuidade de Personagem
- Se houver personagens recorrentes, tratá-los como a mesma pessoa ao longo dos slides.
- Manter coerência de idade, roupa, energia, expressão e contexto visual.
- Quando houver transformação emocional, ela deve ser percebida como evolução da mesma cena narrativa.

### 4.3 Coerência de Atmosfera
- Paleta, iluminação, textura, composição e temperatura visual devem conversar entre si.
- Slides conceituais e slides técnicos não podem parecer decks diferentes.
- Mesmo em conteúdos de mercado, tese ou finanças, o sistema visual deve permanecer coerente com o restante da narrativa.

### 4.4 Tecnologia Como Prova, Não Como Ornamento
- Quando houver prints reais de produto, eles devem funcionar como evidência visual.
- Não usar UI como enfeite sem função narrativa.
- Quando não houver prints reais, criar composições conceituais plausíveis sem inventar interfaces excessivamente específicas.

### 4.5 Transformação Clara
- Sempre que houver contraste entre "antes" e "depois", ele deve ser legível.
- O público precisa perceber visualmente o que mudou.
- A transformação pode ser emocional, operacional, econômica, estratégica ou simbólica.

### 4.6 O Slide Deve Ser A Imagem Final
- A IA deve gerar a arte horizontal do slide em si, e não o slide aplicado em um suporte físico ou contexto externo.
- O resultado esperado é uma composição final pensada para ocupar diretamente a tela de um pitch deck.
- Não gerar o slide como folha impressa, página de livro, cartaz, quadro, projeção em parede, monitor fotografado, tela dentro de mesa ou mockup de apresentação.
- Não transformar o slide em cena de ambientação quando a intenção é produzir a arte final da própria tela.

## 5. Safe Areas e Restrições de Layout
Se houver elementos de sobreposição durante a apresentação, isso deve virar regra global do sistema visual.

Exemplo de regra crítica:
- manter os cantos inferiores esquerdo e direito livres ou com baixa densidade visual quando esses espaços precisarem receber vídeo, avatar, janela da apresentadora, legenda ou CTA externo.

Regras recomendadas:
- não colocar nesses cantos texto principal;
- não colocar números críticos;
- não colocar rostos importantes;
- não colocar prints pequenos que precisem de leitura integral;
- não usar esses espaços para logos centrais ou elementos de alto contraste que disputem atenção com a sobreposição.

## 6. Tipos de Slide por Função Narrativa
Nem todo slide pede o mesmo tipo de composição. Classifique a função antes de gerar a arte.

### 6.1 Hook ou Abertura
- deve capturar atenção rápido;
- trabalha mais atmosfera do que explicação;
- costuma ter pouco texto e imagem dominante.

### 6.2 Problema Humano
- foco em dor, frustração, ruído, dúvida, perda ou tensão;
- ideal para cenas com personagem, contraste emocional e contexto real.

### 6.3 Problema Sistêmico
- amplia a dor individual para mercado, cadeia, setor ou ecossistema;
- pode usar composição mais editorial e menos íntima.

### 6.4 Solução
- deve mostrar clareza, ponte, transformação ou síntese;
- normalmente pede composição mais organizada e confiante.

### 6.5 Prova de Produto
- prioriza prints, fluxos, evidência visual concreta;
- a interface precisa ser legível e contextualizada.

### 6.6 Tese, Mercado e Escala
- precisa manter sofisticação visual sem cair em slide corporativo genérico;
- diagrams, mapas e estruturas devem parecer parte do mesmo deck.

### 6.7 Estratégia
- ideal para framing claro, contraste de caminhos e decisão;
- costuma funcionar bem com confrontos visuais ou transições.

### 6.8 Time e Credibilidade
- precisa soar humano e sólido;
- evitar retratos burocráticos ou layouts de organograma.

### 6.9 Fechamento
- precisa concentrar energia, síntese e memorabilidade;
- não deve parecer apenas "mais um slide".

## 7. Prompt Mestre de Coerência Visual
Use este prompt primeiro.

```text
Você vai me ajudar a criar um pitch deck visualmente coerente.

Contexto base:
- Marca ou projeto: [NOME]
- Tipo de pitch: [TIPO]
- Público principal: [PUBLICO]
- Tema central: [TEMA]
- Universo visual desejado: [UNIVERSO_VISUAL]

Direção geral:
- O deck deve parecer uma narrativa visual contínua, não uma coleção de slides independentes.
- O estilo deve ser [ESTILO_VISUAL].
- A apresentação precisa transmitir [ATRIBUTOS_EMOCIONAIS].
- Evite aparência de template corporativo genérico, mockups frios ou visual sem identidade.

Coerência obrigatória:
- manter consistência de paleta, iluminação, composição, textura e atmosfera ao longo de todos os slides;
- manter continuidade de personagem quando houver personagem recorrente;
- manter continuidade de transformação quando houver contraste entre antes e depois;
- usar prints reais como prova visual, não como decoração;
- quando não houver print real, criar composição conceitual plausível sem inventar interface detalhada demais.

Safe areas:
- preservar [AREAS_DE_SEGURANCA] como áreas livres ou de baixa densidade visual;
- não colocar ali texto principal, números críticos, rostos importantes ou elementos que precisem ficar totalmente visíveis.

Formato:
- trabalhar em [FORMATO];
- priorizar leitura rápida, hierarquia clara e impacto imediato por slide.
- gerar sempre a imagem horizontal final do slide, não uma fotografia ou mockup do slide aplicado em algum objeto, ambiente ou suporte.

Quando eu pedir um slide específico, responda com uma proposta coerente com este sistema.
```

## 8. Prompt de Direção Geral do Deck
Use este prompt depois do prompt mestre.

```text
Vamos criar um pitch deck visualmente coerente do início ao fim.

Quero que você trate a apresentação como um sistema visual contínuo.

Direção narrativa geral:
- abertura com impacto: [ABERTURA]
- construção do problema: [PROBLEMA]
- transição para solução: [SOLUCAO]
- prova, produto ou evidência: [PROVA]
- expansão para mercado, estratégia ou credibilidade: [EXPANSAO]
- fechamento com síntese e força emocional: [FECHAMENTO]

Importante:
- os slides devem conversar entre si;
- mudanças de cena precisam parecer intencionais;
- os slides técnicos devem manter a mesma linguagem visual dos slides emocionais;
- preservar as safe areas definidas para a apresentação.
- qualquer evolução visual entre começo, meio e fim deve parecer parte da mesma linguagem, não uma troca de deck.
- a entrega esperada é sempre a arte horizontal final de cada slide, e não o slide aplicado em papel, tela, parede, livro, monitor ou mockup externo.

Quando eu pedir um slide específico, responda com:
- conceito visual central;
- composição sugerida;
- asset ideal;
- uso de prints, se houver;
- atmosfera emocional;
- principais riscos visuais a evitar.
```

## 9. Template de Prompt por Slide
Use este modelo para cada tela.

```text
Crie a arte do slide "[NOME_DO_SLIDE]".

Função narrativa:
[FUNCAO_DO_SLIDE]

Fala associada:
[FALA_EXATA]

Objetivo visual:
[OBJETIVO_VISUAL]

Quero mostrar:
- [ELEMENTO_1]
- [ELEMENTO_2]
- [ELEMENTO_3]

Assets disponíveis:
- [PRINTS_REAIS]
- [FOTOS]
- [LOGO]
- [NENHUM_ASSET_REAL, SE FOR O CASO]

Regras de composição:
- preservar coerência com os slides anteriores;
- respeitar as safe areas;
- manter legibilidade rápida;
- garantir que o elemento principal domine a leitura.
- gerar a imagem final horizontal do slide, e não uma fotografia do slide aplicado em um suporte externo.

Evite:
- [ERRO_VISUAL_1]
- [ERRO_VISUAL_2]
- [ERRO_VISUAL_3]
```

## 10. Checklist de Adaptação para Outro Pitch
Ao reutilizar este framework em outro deck, revise:

1. Qual é a emoção dominante do início, meio e fim?
2. O deck precisa de personagem recorrente ou não?
3. O produto será mostrado com prints reais ou com conceito?
4. Há restrições de safe area por vídeo, avatar ou legenda?
5. O estilo visual precisa ser mais humano, mais institucional, mais técnico ou mais aspiracional?
6. Os slides de mercado e finanças estão coerentes com o resto ou parecem outro deck?
7. O fechamento tem força emocional suficiente ou está burocrático?

## 11. Resultado Esperado
Quando este framework é bem aplicado, o deck final deve:

- parecer coeso;
- ter linguagem visual consistente;
- evitar quebra de estilo entre slides;
- usar prints e composições conceituais de forma disciplinada;
- sustentar uma narrativa visual clara do início ao fim;
- permitir reaproveitamento do método em pitches com características diferentes.

## 12. Instância Atual Preenchida — Bóora!
Esta seção preenche o framework com os dados do projeto atual, sem remover a utilidade genérica do documento.

### 12.1 Valores Base do Projeto
- marca ou projeto: `Bóora!`
- implementação destacada neste deck: `Guarappary powered by Bóora!`
- tipo de pitch atual: `pitch para edital / fomento de inovação com viés de impacto, validação e escalabilidade`
- referência contextual direta do deck atual: `Centelha`
- público principal: `avaliadores de edital, banca de inovação, stakeholders de fomento e potenciais apoiadores estratégicos`
- tema central: `plataforma multilateral hiperlocal que conecta pessoas, cultura local, turismo e novas oportunidades econômicas no mundo real`
- narrativa principal: `partimos da dor do FOMO e do ruído das redes; mostramos a invisibilidade do trade cultural local; apresentamos o Bóora! como ponte; provamos a solução com produto, estratégia, validação, sustentabilidade e impacto`
- atributos emocionais desejados: `autêntico, humano, vibrante, local, sofisticado, inspirador, confiável`
- universo visual desejado: `Brasil litorâneo contemporâneo, cultura local viva, presença real, mobilização social, tecnologia a serviço da vida offline`
- assets reais disponíveis no deck atual: `logo`, `prints da home do app`, `agenda`, `mapa/POIs`, `tela de evento`, `fluxo de convite`, `aceite de convite`, `atrativos e eventos`
- assets inexistentes ou limitados no deck atual: `imagens reais do FRM`, `prints específicos da camada de receita/protagonismo do trade cultural`
- formato: `16:9`
- áreas de segurança: `cantos inferiores esquerdo e direito livres ou com baixa densidade visual`
- motivo das safe areas: `possível sobreposição de vídeo da fundadora durante a apresentação`

### 12.2 Prompt Mestre Preenchido — Bóora!
```text
Você vai me ajudar a criar um pitch deck visualmente coerente para o Bóora!, uma plataforma multilateral brasileira focada em experiências hiperlocais, cultura local, turismo e conexão social no mundo real.

Contexto base:
- Marca ou projeto: Bóora!
- Implementação destacada neste deck: Guarappary powered by Bóora!
- Tipo de pitch: pitch para edital / fomento de inovação com viés de impacto, validação e escalabilidade
- Público principal: avaliadores de edital, banca de inovação, stakeholders de fomento e potenciais apoiadores estratégicos
- Tema central: plataforma multilateral hiperlocal que conecta pessoas, cultura local, turismo e novas oportunidades econômicas no mundo real
- Universo visual desejado: Brasil litorâneo contemporâneo, cultura local viva, presença real, mobilização social e tecnologia a serviço da vida offline

Direção geral:
- O deck deve parecer uma narrativa visual contínua, não uma coleção de slides independentes.
- O estilo deve ser cinematográfico, humano, editorial, emocional e contemporâneo.
- A apresentação precisa transmitir autenticidade, energia local, clareza de solução, legitimidade e impacto real.
- Evite aparência de template corporativo genérico, mockups frios, visual de dashboard SaaS ou publicidade genérica sem identidade cultural.
- A entrega esperada é sempre a arte horizontal final de cada slide, não o slide aplicado em livro, folha, mesa, parede, notebook, projeção ou mockup de apresentação.

Coerência obrigatória:
- manter consistência de paleta, iluminação, composição, textura e atmosfera ao longo de todos os slides;
- manter continuidade de personagem quando houver personagem recorrente nos slides iniciais;
- manter continuidade de transformação quando houver contraste entre antes e depois;
- usar prints reais do app como prova visual, não como decoração;
- quando não houver print real, criar composição conceitual plausível sem inventar interface detalhada demais.

Safe areas:
- preservar os cantos inferiores esquerdo e direito como áreas livres ou de baixa densidade visual;
- não colocar nesses cantos texto principal, números críticos, rostos importantes, prints pequenos ou qualquer elemento que precise ficar totalmente visível;
- esses espaços poderão receber um vídeo da fundadora durante a apresentação.

Formato:
- trabalhar em 16:9;
- priorizar leitura rápida, hierarquia clara e impacto imediato por slide.
- gerar sempre imagens horizontais finais de slides, e não fotografias de slides em suportes físicos ou digitais.

Quando eu pedir um slide específico, responda com uma proposta coerente com este sistema.
```

### 12.3 Prompt de Direção Geral do Deck — Bóora!
```text
Vamos criar um pitch deck visualmente coerente do início ao fim para o Bóora!.

Quero que você trate a apresentação como um sistema visual contínuo.

Direção narrativa geral:
- abertura com impacto: pergunta cotidiana, dúvida real e tensão de descoberta
- construção do problema: excesso de ruído, FOMO, evento perdido, invisibilidade do valor local
- transição para solução: o Bóora! surge como ponte entre quem quer viver experiências e quem precisa ser encontrado
- prova, produto ou evidência: agenda, mapa, convites, presença real e lógica de mobilização
- expansão para mercado, estratégia ou credibilidade: trade cultural, tese de mercado, validação, estratégia de entrada, Centelha, sustentabilidade e time
- fechamento com síntese e força emocional: impacto real na cultura local, oportunidades econômicas e chamada final forte

Importante:
- os slides devem conversar entre si;
- mudanças de cena precisam parecer intencionais;
- os slides técnicos devem manter a mesma linguagem visual dos slides emocionais;
- preservar os cantos inferiores esquerdo e direito como safe areas para possível vídeo da fundadora;
- qualquer evolução visual entre começo, meio e fim deve parecer parte da mesma linguagem, não uma troca de deck.
- o resultado esperado para cada etapa é a arte horizontal final do slide, não sua aplicação em papel, livro, parede, tela ou mockup.

Quando eu pedir um slide específico, responda com:
- conceito visual central;
- composição sugerida;
- asset ideal;
- uso de prints, se houver;
- atmosfera emocional;
- principais riscos visuais a evitar.
```
