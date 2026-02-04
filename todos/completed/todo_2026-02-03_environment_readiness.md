# TODO: Environment Readiness Fixes
**Date:** 2026-02-03
**Owner:** Delphi (DevOps / Docker Engineer)

## Scope
- Align environment readiness with `delphi-ai/initialization_checklist.md` and `delphi-ai/tools/verify_context.ps1`.
- Replace symlinked `flutter-app` and `laravel-app` with real Git submodules defined in `.gitmodules`.
- Ensure each submodule exposes shared docs via symlink:
  - `foundation_documentation -> ../foundation_documentation`
  - `delphi-ai -> ../delphi-ai` (optional but recommended for standalone use)
- Ensure `.agent` directory exists in submodule repos where required by the verifier.

## Definition of Done
- `delphi-ai/tools/verify_context.ps1` passes with no errors.
- `flutter-app` and `laravel-app` are real submodule working trees (not symlinks), initialized to the commits in `.gitmodules`.
- Required symlinks exist inside:
  - `C:\Unifast\belluga\belluga_now_docker\flutter-app`
  - `C:\Unifast\belluga\belluga_now_docker\laravel-app`
  - `C:\Unifast\belluga\belluga_now_web`
- `.agent` directory exists in submodule repos that require it.

## Tasks
- [x] ✅ Production-Ready Remove symlinked `flutter-app` and `laravel-app` directories from root.
- [x] ✅ Production-Ready Initialize submodules (`git submodule sync --recursive` and `git submodule update --init --recursive`).
- [x] ✅ Production-Ready Create missing `foundation_documentation` symlinks in each submodule repo.
- [x] ✅ Production-Ready Create optional `delphi-ai` symlinks in each submodule repo.
- [x] ✅ Production-Ready Create missing `.agent` directories where required.
- [x] ✅ Production-Ready Re-run `delphi-ai/tools/verify_context.ps1` and confirm pass.

## Notes
- Use Windows-friendly symlink creation (PowerShell `New-Item -ItemType SymbolicLink`).
- Avoid modifying `delphi-ai/tools/verify_context.ps1` unless necessary.
 - Updated `delphi-ai/tools/verify_context.ps1` to normalize symlink targets for Windows (absolute vs relative + slash normalization).
