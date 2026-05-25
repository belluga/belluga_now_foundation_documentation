# TODO (vNext): Push Provider Request-Budget Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Pending on `2026-05-25`.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`
- **vNext role:** `program owner`

## Context
- Direct invite push delivery is no longer blocked by the old `scheduled`/no-log failure.
- The launch-critical structural push work moved runtime token authority to `push_devices` and introduced channel/topic delivery for stable audiences.
- One provider-budget concern remains for future larger direct-recipient batches: `FcmHttpV1Client::send()` still iterates concrete tokens and sends one HTTP v1 request per token.
- This is not a current direct-invite release blocker because direct invites target one recipient and channel/topic delivery already covers stable shared audiences.

## Contract Boundary
- This TODO owns a future provider-transport hardening pass for direct/private multi-recipient push batches.
- It must not reopen direct invite push delivery, FCM credential setup, Android notification icon/tap routing, or tenant-admin push settings.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `vNext`, `Laravel`, `Push-Runtime-Hardening`, `Performance-Budget`, `Deferred`
- **Next exact step:** when selected, confirm whether the approved transport is HTTP batch, SDK-supported multicast, or an explicit product constraint that caps direct-recipient fanout and routes larger stable audiences through topics.

## Scope
- [ ] Re-evaluate FCM HTTP v1 capabilities and the installed Firebase SDK options for direct multi-token delivery.
- [ ] Define the canonical provider request budget for direct/private transactional audiences.
- [ ] Add tests that prove the provider budget for bounded direct-recipient batches.
- [ ] Preserve the existing topic/channel path for stable recurring audiences.
- [ ] Keep direct invite behavior compatible and do not regress `invite_received` / `invite_accepted` delivery.

## Out of Scope
- Replaying historical push messages.
- Changing invite push payload content or tap routing.
- Changing tenant push credentials or private-key normalization.
- Replacing the whole push subsystem or queue backend.

## Definition of Done
- [ ] Provider transport behavior is explicit and tested for direct/private multi-recipient batches.
- [ ] Stable audiences remain topic/channel-based and do not fall back to tenant-wide scans.
- [ ] Direct invite tests remain green after the transport-budget change.
- [ ] Module documentation records the final provider-budget rule.

## Validation Steps
- [ ] Run focused Laravel push transport tests.
- [ ] Run focused invite push tests.
- [ ] Run the Laravel CI-equivalent before promotion.
