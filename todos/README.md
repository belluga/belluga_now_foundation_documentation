# TODO Authoring Guide

Use this guide whenever creating or updating tactical TODOs under `foundation_documentation/todos/`.

## 1) Always Start From the Template

Template source of truth:
- `delphi-ai/templates/todo_template.md`

Create a new TODO by copying the template:

```bash
cp delphi-ai/templates/todo_template.md foundation_documentation/todos/active/<lane>/<TODO-name>.md
```

Do not open a new TODO as a blank file.

## 2) Status Model (Local vs Promotion)

Use these status names exactly:
- `⚪ Pending`
- `🟡 Provisional`
- `🟧 Local-Implemented`
- `🟣 Lane-Promoted`
- `✅ Production-Ready`

Transition rule:
1. `🟧 Local-Implemented` means delivered in feature/fix branch with local validation.
2. `🟣 Lane-Promoted` means merged through the TODO lane threshold (usually `dev`).
3. `✅ Production-Ready` means required lane targets are complete (`stage`/`main` when applicable) and confidence gates are satisfied.

Never mark `✅ Production-Ready` using feature-branch evidence only.

## 3) Promotion Evidence Is Mandatory

Every active TODO must maintain a promotion evidence section/table with:
- local branch + commit SHA
- PR URL(s) by lane (`dev`, `stage`, `main` as applicable)
- current status per workstream/task group

If promotion has not happened yet, keep fields explicit as `<pending>`.
