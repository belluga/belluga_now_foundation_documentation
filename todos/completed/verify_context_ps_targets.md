# Tactical TODO: Adapt PowerShell Verify Context Targets (Version 1.0)

## Objective
Adjust `delphi-ai/tools/verify_context.ps1` to support external Windows target paths for `flutter-app` and `laravel-app` and relax checks that assume in-repo symlinks.

## Scope
- Accept configured targets for `flutter-app` and `laravel-app` (either via script parameters or environment variables).
- Skip `foundation_documentation` symlink checks for submodules when external targets are used.
- Keep existing validation for root repo and optional `.agent` checks aligned with the new targets.

## Execution Steps
- [x] ✅ Production-Ready: Decide the configuration mechanism (parameters vs env vars) and update the script accordingly.
- [x] ✅ Production-Ready: Adjust validations to recognize external targets and avoid false failures.
- [x] ✅ Production-Ready: Run the PowerShell script to validate behavior.

## Validation
- Script exits `0` on success and non-zero on failure.
- When targets are supplied, `flutter-app` and `laravel-app` checks validate those targets instead of `../foundation_documentation` symlinks.

## Risks
- Over-relaxing checks could hide real configuration issues; mitigate by making external-target mode explicit.

## Approvals
- Required: User approval `APROVADO` before making changes.
