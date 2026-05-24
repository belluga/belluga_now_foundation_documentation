# Inviteables App People TODO Audit Package - Round 03 - 2026-05-24

## Scope
Pre-implementation audit package for:

`foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md`

Round 03 validates the final blocker from round 02.

## Round 02 Resolution Reference
`foundation_documentation/artifacts/audits/inviteables-app-people-todo-preimplementation-20260524/round-02/resolution.md`

## Integrated Correction
- Real-backend Flutter/device evidence is now a hard delivery gate, not preferred evidence.
- The Local CI-Equivalent Suite Matrix includes:

```bash
cd flutter-app && ADB_DEVICE=${ADB_DEVICE:?} INTEGRATION_DEFINE_FILE=config/defines/integration.tenant.json ./tool/run_integration_test_wsl.sh integration_test/invite_share_app_people_real_backend_test.dart
```

- The validation gate explicitly requires no-mock real-backend Flutter device/navigation evidence for:
  - invite-share app-pane initial render while contact import is pending;
  - repeated screen entry;
  - occurrence switch/reuse without full inviteables refetch;
  - independent sent-status overlay behavior.
- If the device/backend lane cannot run, delivery is `Blocked`; it cannot be marked passed or waived silently.

## Review Question
Is the TODO now ready for implementation, or does the TODO contract still permit a concrete release-blocking failure?
