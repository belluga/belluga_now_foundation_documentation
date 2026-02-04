# Tactical TODO: Environment Symlink Update (Version 1.0)

## Objective
Update repository symlinks so `flutter-app/` points to `C:\Unifast\belluga\belluga_now_front` and `laravel-app/` points to `C:\Unifast\belluga\belluga_now_backend`. `web-app/` is out of scope per user request.

## Scope
- Update existing symlinks under `flutter-app/` and `laravel-app/` to the new targets.
- Do not create or modify `web-app/` symlinks.
- No changes inside the target repositories.

## Execution Steps
- [x] ✅ Production-Ready: Inspect current `flutter-app/` and `laravel-app/` symlink targets.
- [x] ✅ Production-Ready: Repoint symlinks to the new paths.
- [x] ✅ Production-Ready: Re-verify link targets after update.

## Validation
- `flutter-app/` and `laravel-app/` resolve to the expected absolute paths.

## Risks
- Incorrect link targets could break tooling and path resolution. Mitigate by verifying targets after update.

## Approvals
- Required: User approval `APROVADO` before making changes.
