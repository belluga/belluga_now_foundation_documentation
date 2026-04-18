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

Choose the active lane deliberately:
- `active/store_release_android/` for Android-first publication-critical work that blocks the next app-store release.
- `active/fast_follow_required/` for business-defined work sequenced immediately after the Android release cut (for example iOS fast-follow and QR login/web auth), not speculative backlog.
- `active/vnext/` for explicitly deferred backlog that should stay visible but must not be mixed into current Android-release or fast-follow execution lanes.

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

## 4) Lane Semantics

Use the directory tree as a second filter before opening or moving a TODO:
- `active/store_release_android/` is the authoritative lane for the current Android-first publication milestone.
- `active/fast_follow_required/` is for work that is already defined by the business and must follow immediately after Android release, but is intentionally sequenced out of the Android gate.
- `active/vnext/` is backlog-visible by design. Do not place current release execution there just because the topic is important.
- `completed/` is only for closed lanes that no longer need active follow-up.
- `ephemeral/` is only for local-only maintenance/regression execution artifacts; do not treat it as backlog or canonical planning inventory.

Legacy note:
- older `pre_mvp_*`, `mvp_*`, and `cross-stack` lane names are no longer part of the active directory model.
- if an older document still references those lane names, treat that as documentation drift and update the path in the same change that touches the TODO.

When a TODO moves between horizons or execution phases, move the file to the matching lane and update any durable cross-references in the same change.
