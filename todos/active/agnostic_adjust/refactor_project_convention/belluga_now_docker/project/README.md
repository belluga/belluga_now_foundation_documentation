# Project-Specific Files

Esta pasta contém arquivos específicos do projeto Belluga Now que não fazem parte do boilerplate genérico.

## Estrutura

- `nginx/routes.conf` — Rotas nginx específicas do projeto (incluídas via `include` condicional no nginx genérico)
- `tests/` — Testes Playwright e smoke tests de validação de deploy
- `scripts/` — Scripts específicos do projeto (validação de classes runtime, test-laravel-full, etc.)

## Convenção

O Docker genérico faz `include` condicional: se os arquivos desta pasta existirem, são utilizados. Se não existirem (como no boilerplate limpo), são ignorados. Isso permite que o mesmo Docker sirva tanto o boilerplate quanto projetos derivados.
