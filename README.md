# Foundation Documentation

This repository is the source of truth for Belluga foundation documentation and tactical TODOs.

## Scope

- Architecture and mandate documents
- Module, screen, and policy docs
- Tactical TODOs under `todos/active/` and `todos/completed/`

## Workflow

1. Edit docs in this repo and open a PR.
2. Merge to `main` after review.
3. In `belluga_now_docker`, update the submodule pointer to the latest `main` commit when code or infra depends on updated docs.

## Submodule Update (belluga_now_docker)

```bash
git submodule update --remote foundation_documentation
# or
cd foundation_documentation && git pull origin main
cd .. && git add foundation_documentation
```

## Minimal Validation

- Ensure `git status -sb` is clean before pushing.
- For TODO completion, add a metadata line noting the source branch and commit hash where the change landed.

Example:

```
completion_metadata: branch=feature/x, commit=abc1234
```

## Conflict Guidance

- If conflicts occur, resolve them in the docs repo and note the resolution in the PR description.
- If a doc change is blocked by a newer code version, create/extend a TODO with the relevant branch/hash and align the docs after the code merge.

## Delphi Usage Notes

- Treat this repo as project-specific context; do not move content into `delphi-ai/`.
- Follow the TODO-driven execution method before any changes.
- Create new tactical TODOs from `delphi-ai/templates/todo_template.md` (see `foundation_documentation/todos/README.md`).
- `✅ Production‑Ready` TODO status requires Delivery Confidence Gate evidence (or explicit waiver rationale), with artifacts under `foundation_documentation/artifacts/tmp/<run-id>/...`.
- Record branch + commit hash in TODOs when tasks are completed.

## Default TODO Closure Gate (Cloudflare + Local)

For backend/security TODOs on Cloudflare-fronted APIs, the default closure gate is:

```bash
cd laravel-app
RUN_ID=<run-id> ./scripts/security_confidence_pack.sh
```

Required evidence (under `foundation_documentation/artifacts/tmp/<run-id>/`):

- Local Laravel checks:
  - `laravel_pint_security.log`
  - `laravel_security_suite.log`
  - `laravel_guardrails.log`
- Cloudflare runtime checks:
  - `belluga_root_head.log` (must show `server: cloudflare` + `cf-ray`)
  - `belluga_security_noauth_headers.log`
  - `belluga_security_spoof_cf_headers.log`
  - `belluga_security_spoof_xff_headers.log`
- Gate status:
  - `summary.txt` with `gate_result=PASS`
  - `assertions.log` with all assertions passing

If any assertion fails, TODO closure must remain provisional until resolved or explicitly waived.
