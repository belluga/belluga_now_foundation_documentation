# Store Release Backend/Frontend Gap Analysis - 2026-04-29

## Scope

This note records the follow-up audit requested after the tenant-admin webhook settings gap: identify release-critical work where backend behavior exists, but the corresponding Flutter consumer, discoverability surface, or render test is missing.

## Confirmed Gap 1: Outbound Webhook Settings

The `outbound_integrations` settings namespace and OTP dispatch direction existed in backend/planning context, but the tenant admin technical integrations hub did not expose a visible configuration surface for:

- WhatsApp outbound webhook URL.
- OTP outbound webhook URL.
- OTP delivery channel.
- OTP retry/timing parameters needed by the queue-based dispatch decision.

### Why Existing Rules Missed It

- The delivery evidence treated backend settings registration and OTP runtime behavior as sufficient without a consumer matrix proving read/write UI access.
- Store-release review packets focused on tenant-public OTP and contact flows; the tenant-admin settings render path was not explicitly listed as part of the acceptance surface.
- The orchestration gate asked reviewers to inspect the implementation packet, but the packet did not force "operator-facing consumer present" for every backend setting namespace.
- Admin hub discoverability was not represented as a widget/render assertion for this namespace.
- The ADB/manual phase was scoped to public journeys and did not include admin settings navigation.

### Correction Applied

- Added tenant-admin hub entry and technical integration section for outbound webhooks.
- Added Flutter repository/decoder/encoder support for the `outbound_integrations` namespace.
- Added widget and repository tests proving discoverability, edit, save, and payload shape.
- Updated tenant-admin module docs with the outbound settings contract and decision `TAD-14`: release-critical backend settings namespaces must have an explicit tenant-admin consumer or an explicit "backend-only" waiver.

## Confirmed Gap 2: Web-to-App Deferred Target Path

The Flutter promotion lane already supported non-invite redirect targets such as event detail and auth-owned paths. The backend `/open-app` and deferred resolver path still behaved as invite-first and did not preserve the same `target_path` contract end-to-end.

### Why Existing Rules Missed It

- Frontend redirect tests validated local path construction, but backend attribution payload tests only asserted invite-centric behavior.
- The deferred resolver contract had `target_path`, but Flutter startup still treated capture as invite-code-only.
- The review split backend redirect behavior and Flutter deferred startup behavior instead of validating the full "promotion target path" contract.

### Correction Applied

- Backend `/open-app` attribution payload now preserves safe target paths and includes `target_path`.
- Backend deferred resolver now returns captured non-invite targets when a safe target path is present.
- Flutter deferred repository and startup controller now treat `target_path` as authoritative, with invite code optional.
- Docs and TODO evidence were updated for the broader conversion gate contract.

## Audit Result

No additional confirmed backend-delivered/frontend-missing release-critical gaps were found in this pass beyond the two above.

Residual caveat: real-device ADB validation for contact/OTP/promotion journeys remains intentionally deferred to the end of the orchestration because this WSL/device environment is resource-sensitive.

## Rule Adjustment Recommended

For every release-critical backend endpoint or settings namespace, the TODO delivery packet should include a frontend consumer matrix:

- Backend route or settings namespace exists.
- Flutter repository/DTO/decoder/encoder support exists, or a documented waiver explains why no Flutter consumer is required.
- Visible user/operator route exists when the feature is configurable or actionable.
- Hub/navigation discoverability is covered when the surface is not reached directly.
- Widget or render test proves the visible surface exists.
- Repository/request test proves the payload shape used by Flutter.
- Docs/TODO evidence explicitly states the consumer status before independent review starts.

This should be part of each TODO's local gate before triple review or Claude CLI review, not only a final consolidation check.
