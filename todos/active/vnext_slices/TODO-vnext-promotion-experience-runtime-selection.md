# TODO (VNext): Promotion Experience Runtime Selection

**Status:** Planned
**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Secondary Module Anchor:** `foundation_documentation/policies/web_to_app_promotion_policy.md`
**Complexity:** small

## Objective
Remove the temporary hardcoded promotion experience selection from Flutter and move:
- the active rendered promotion variant,
- the lead-capture transport target,
- and any promotion-specific runtime copy strategy,

to runtime/environment/backend contracts.

## Current Temporary State
- The canonical promotion route stays stable.
- Flutter currently hardcodes the active variant to `tester_waitlist`.
- The temporary lead-capture adapter posts directly to `formsubmit.co`.

## VNext Target
- Promotion experience selection is runtime-configured, not hardcoded in Flutter.
- Lead-capture transport target is runtime/backend-configured with Flutter fallback only as a safety net.
- Swapping from tester waitlist to app-download experience must remain operational and must not require route/guard changes.
