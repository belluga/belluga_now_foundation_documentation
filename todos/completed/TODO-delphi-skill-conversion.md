# TODO (Delphi): Convert workflows/rules into Codex skills

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (CTO/Tech Lead)  
**Objective:** Create skill entries in `delphi-ai/skills/` from existing `delphi-ai` workflows/rules and add a `.codex/skills` symlink.

---

**scope:** Create `delphi-ai/skills/`, generate one skill per file in `delphi-ai/workflows/**` and `delphi-ai/rules/**`, and create `.codex/skills` symlink to `delphi-ai/skills`.  
**out_of_scope:** Packaging `.skill` bundles, changing the original workflow/rule content, or altering non-Delphi project code.  
**definition_of_done:** Every workflow/rule file has a matching skill folder with a valid `SKILL.md` (frontmatter name/description only + body content), and `.codex/skills` points to `delphi-ai/skills`.  
**validation_steps:** Loop `python3 /home/elton/.codex/skills/.system/skill-creator/scripts/quick_validate.py <skill_dir>` across all generated skills; fix any failures.

---

## Decisions
- Naming scheme: `wf-<scope>-<basename>` for workflows, `rule-<scope>-<basename>` for rules; scope includes nested dirs (e.g., `docker-shared`).
- Description policy: use existing frontmatter `description` when present; prefix with workflow/rule context and add trigger hint for rules with `trigger`.
- Skill structure: `SKILL.md` only (no scripts/references/assets unless required by the source file).
- No packaging (`package_skill.py`) unless explicitly requested.

## Tasks
- [x] ✅ Production‑Ready Create `delphi-ai/skills/` if missing.
- [x] ✅ Production‑Ready For each `delphi-ai/workflows/**/*.md`, run `init_skill.py` into `delphi-ai/skills/<name>` and replace `SKILL.md` with frontmatter + body (original content minus frontmatter).
- [x] ✅ Production‑Ready For each `delphi-ai/rules/**/*.md`, apply the same conversion.
- [x] ✅ Production‑Ready Add `.codex/skills` symlink pointing to `delphi-ai/skills`.
- [x] ✅ Production‑Ready Validate all skills with `quick_validate.py` and resolve any naming/metadata issues.


## Validation Results
- `quick_validate.py` succeeded for all generated skills.

## Completion Notes
- Skill generation complete; no packaging requested.
