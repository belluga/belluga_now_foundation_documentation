# TODO (Docs Split): Move foundation_documentation to dedicated repo

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Docs + Platform Leads
**Objective:** Extract `foundation_documentation/` into a dedicated repo and integrate it back into `belluga_now_docker` as a submodule (recommended) for faster cross-team doc sync.

---

**scope:**
- Create and populate `https://github.com/belluga/belluga_now_foundation_documentation.git` with the current contents of `foundation_documentation/` at repo root.
- Set default branch to `main`.
- Replace `foundation_documentation/` in `belluga_now_docker` with a git submodule pointing to the new repo.
- Document the new workflow for updating docs (pull/update submodule pointer when needed), including minimal validation and conflict-resolution expectations.
- Add a TODO metadata convention: each TODO must record source branch name + commit hash when completed (for cross-team traceability).

**out_of_scope:**
- Any changes to the doc content itself (other than relocation).
- Any refactors to TODO structure or lifecycle rules.
- CI or linting rules beyond a simple placeholder (unless explicitly requested).

**definition_of_done:**
- New docs repo contains the full `foundation_documentation/` tree at root on `main`.
- `belluga_now_docker` tracks the docs repo as a submodule at `foundation_documentation/`.
- README or short note explains how to update docs and submodule pointer, plus minimal validation steps and conflict-resolution expectations.
- TODO metadata convention (branch + commit hash on completion) documented in the docs repo.
- `git status -sb` clean after submodule update.

**validation_steps:**
- `git submodule status` shows `foundation_documentation` pointing to the new repo on `main`.
- `ls foundation_documentation` lists expected files.
- `git status -sb` clean.

---

## A) Migration Plan

- [x] ✅ Production-Ready Confirm docs repo settings (private/public, default branch `main`).
- [x] ✅ Production-Ready Create local git history for `foundation_documentation/` and push to new repo.
- [x] ✅ Production-Ready Remove in-repo `foundation_documentation/` and add submodule pointing to new repo.
- [x] ✅ Production-Ready Add/update documentation on the new workflow (how to update docs and submodule pointer).
- [ ] ⚪ Pending Validate submodule status and working tree clean.

## B) Decisions

- [x] ✅ Production-Ready Integration mode: submodule.
- [x] ✅ Production-Ready Repo root layout: docs-only at root (current structure).
- [x] ✅ Production-Ready CI/linting: none for now; rely on lightweight validation checklist in the docs workflow.

## C) Questions to Close

- [x] ✅ Production-Ready No stub/placeholder in `belluga_now_docker`; submodule will be initialized now.
- [x] ✅ Production-Ready Add a short `README.md` in the docs repo describing the workflow and Delphi usage instructions.

---

## Completion Notes

- Docs repo commit: `48f2d99a02dc8bcc1d36777b24abed25a3ed8cb8` (README + workflow guidance + TODO updates).
- Docker repo commit: pending (changes staged; commit required to finalize).
