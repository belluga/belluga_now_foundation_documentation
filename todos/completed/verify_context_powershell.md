# Tactical TODO: PowerShell Verify Context (Version 1.0)

## Objective
Create a PowerShell equivalent of `delphi-ai/tools/verify_context.sh` so Windows environments can verify repository context without WSL.

## Scope
- Add `delphi-ai/tools/verify_context.ps1` to perform the same checks as the Bash script.
- Keep output clear with pass/fail messaging and non-zero exit on failure.
- Do not alter the Bash script behavior.

## Execution Steps
- [x] ✅ Production-Ready: Inspect `delphi-ai/tools/verify_context.sh` to mirror its checks.
- [x] ✅ Production-Ready: Implement `delphi-ai/tools/verify_context.ps1` with equivalent logic.
- [x] ✅ Production-Ready: Run the PowerShell script to validate behavior.

## Validation
- Script exits `0` on success and non-zero on failure.
- Reports missing directories or broken symlinks for `foundation_documentation` and `delphi-ai` in submodules.

## Risks
- Windows symlink resolution differs from Bash; mitigate by using PowerShell `Get-Item` and `LinkType`/`Target` checks.

## Approvals
- Required: User approval `APROVADO` before making changes.
