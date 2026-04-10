# Tela Canônica: App Promotion Tester Waitlist

## Contexto

- Rota canônica: `/baixe-o-app`
- EnvironmentType: `tenant`
- Main Scope: `tenant_public`
- Estado atual aprovado para o pré-MVP: variante hardcoded de tester waitlist no mesmo boundary de promoção web-to-app
- Referência visual aprovada no Stitch (`Quóa`):
  - `Beta Tester (Sem Widget de Check)`
  - `Beta Tester (Formulário Limpo)`

## Objetivo

Capturar interesse no piloto via web sem introduzir web-login nem acoplamento do backend ao schema específico do formulário. O backend apenas recebe um envelope genérico e ordenado dos campos submetidos e dispara o email transacional tenant-public.

## Estrutura da tela

1. Sem app bar dedicado: o primeiro elemento no topo é a ação de fechar, preservando leitura de screen dedicada full-height.
2. Hero central sem card wrapper independente:
   - arte principal derivada do branding do tenant em runtime;
   - badge/flair visual secundário;
   - título orientado ao beta tester;
   - texto curto explicando o piloto.
3. Card principal do formulário, com inputs pill/tonal e seletor de SO em segmented control.
4. Cards informativos abaixo do CTA principal, em carrossel horizontal.
5. Estado de sucesso com composição própria:
   - selo circular de confirmação;
   - headline e texto centralizados;
   - CTA `Continuar navegando`.

## Formulário aprovado

Campos exibidos:
- `Seu Nome`
- `E-mail`
- `WhatsApp`
- `Qual o seu sistema operacional?`
- `O que não pode faltar para atender às suas expectativas?`

Regras:
- `Seu Nome`: campo curto obrigatório.
- `E-mail`: obrigatório e validado como email.
- `WhatsApp`: obrigatório e validado em formato numérico normalizado.
- `SO`: obrigatório, com seleção explícita `iOS` ou `Android`.
- `O que não pode faltar para atender às suas expectativas?`: obrigatório, campo multi-linha.

## Conteúdo informativo inferior

- O conteúdo hoje exibido como checklist/bullets deve migrar para cards informativos.
- Os cards ficam abaixo do CTA principal.
- O agrupamento é em carrossel horizontal com rolagem nativa.
- Os cards são informativos; não participam do submit e não funcionam como checkbox.

## Estado de sucesso

- O estado de sucesso não deve reaproveitar a mesma hierarquia visual do formulário; ele vira uma composição central própria, fiel ao Stitch.
- Deve existir CTA `Continuar Navegando`.
- `Continuar Navegando` faz apenas `pop()`.
- O botão de fechar faz exatamente o mesmo comportamento: `pop()` apenas.
- Nenhuma dessas ações pode usar fallback para `replaceAll`, home route, ou navegação compensatória.

## Contrato de submissão

O cliente envia para `POST /api/v1/email/send`:

```json
{
  "app_name": "Guarappari",
  "submitted_fields": [
    {
      "label": "Seu Nome",
      "value": "Maria"
    },
    {
      "label": "E-mail",
      "value": "maria@example.com"
    },
    {
      "label": "WhatsApp",
      "value": "27999999999"
    },
    {
      "label": "Qual o seu sistema operacional?",
      "value": "Android"
    },
    {
      "label": "O que não pode faltar para atender às suas expectativas?",
      "value": "Mapa confiável e agenda atualizada."
    }
  ]
}
```

Regras do envelope:
- a ordem da lista é canônica e deve ser preservada no email;
- o backend não depende de nomes de campos específicos além do envelope genérico;
- o backend apenas renderiza os pares `label/value` recebidos;
- `app_name` continua permitido para enriquecer assunto/contexto da mensagem.

## Direção de implementação

- confiar em `ThemeData`/`ColorScheme`; evitar cores hardcoded quando o tema já oferece papel semântico;
- minimizar style inline;
- manter a tela dentro da arquitetura atual:
  - controller possui estado, validação, controllers de texto e submit;
  - widget permanece presentational.
