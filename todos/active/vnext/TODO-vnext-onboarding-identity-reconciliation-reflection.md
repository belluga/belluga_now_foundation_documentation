# TODO (VNext): Onboarding Identity Reconciliation And Reflection

**Classification note (2026-04-18):** this is a follow-up lane. It owns what happens after a user's canonical phone identity materializes through OTP/onboarding and that materialization needs to reflect back into the social graph and UI surfaces.

**Scope authority note (2026-04-18):** this TODO was split out of `TODO-store-release-minimal-friends-and-favorites-mvp.md` so the release-critical contacts/favorites/friends lane stays bounded to explicit `/contacts/import` acquisition and inviteable composition. This follow-up owns the later reconciliation trigger and its reflection surfaces.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Pending follow-up. Canonical direction is frozen, but no delivery work has started.
**Owners:** Delphi (Product/Flutter) + Backend Team
**Goal:** define and deliver the onboarding-owned late-reconciliation flow that reflects a newly materialized canonical identity back into the existing contacts/social model without inventing implicit approval or inviteability.

---

## Delivery Status Canon

- **Current delivery stage:** `Definition-Frozen`
- **Qualifiers:** `Follow-Up`, `Cross-Module`, `Onboarding-Owned`
- **Next exact step:** when this follow-up becomes active, freeze the exact trigger boundary (`otp_verified`, `onboarding.completed`, or equivalent stable milestone), then specify the backend reconciliation path and the advisory reflection surfaces.

## References

- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/onboarding_flow_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Decision Baseline (Frozen 2026-04-18)

- [x] `D-01` Ownership of onboarding-driven late identity-materialization reconciliation belongs to this follow-up TODO, not to the release-critical contacts/favorites/friends lane.
- [x] `D-02` After a user's canonical phone identity materializes, backend may reconcile previously imported hashes from other viewers and may materialize outbound `contact_match` for those viewers without requiring a fresh manual import.
- [x] `D-03` The same reconciliation event may feed advisory reflection surfaces for the newly materialized user, including a future inbound discovery surface labeled `Talvez você conheça` and optional informational lifecycle notifications such as "contact entered the app".
- [x] `D-04` Those inbound/reflection consumers are discovery-only: they are not `Contato`, not `inviteable_reason`, not groupable, and do not become inviteable until explicit favorite promotes the relationship into the normal inviteable rules.
- [x] `D-05` This TODO does not redefine `/contacts/import`, `contact_groups`, inviteable reasons, or favorite/friend semantics. It consumes those frozen rules and defines only the late reconciliation trigger plus its reflection behavior.

## Scope

- [ ] Define the exact trigger boundary for late reconciliation after identity materialization.
- [ ] Define whether reconciliation runs at OTP verification, `onboarding.completed`, or another stable identity-materialization milestone.
- [ ] Define idempotent backend reconciliation behavior for prior imported hashes.
- [ ] Define when outbound `contact_match` should materialize for existing viewers and what consistency guarantees apply.
- [ ] Define the advisory inbound surface contract for `Talvez você conheça`.
- [ ] Define whether informational notifications such as "contact entered the app" are part of the same follow-up slice or a subordinate later slice.
- [ ] Preserve the explicit-favorite requirement before any inbound reflection becomes inviteable.
- [ ] Define validation and observability for reconciliation timing, duplicates, and privacy-safe projection.

## Out of Scope

- [ ] Reopening the release-critical `/contacts/import` acquisition baseline.
- [ ] Redefining `contact_match -> favorite -> friend` semantics.
- [ ] Redefining inviteable reasons, group membership rules, or external-contact share behavior.
- [ ] Generic onboarding redesign unrelated to identity-materialization reflection.
- [ ] QR web login, web-to-app promotion policy, or unrelated auth-wall behavior.

## Acceptance Criteria

- [ ] The system has one explicit canonical trigger for late reconciliation after identity materialization.
- [ ] Previously imported hashes may reconcile into outbound `contact_match` for eligible viewers without requiring fresh manual contact import.
- [ ] Any inbound reflection surface for the newly materialized user remains advisory-only and does not auto-create `Contato`, inviteability, or group eligibility.
- [ ] `Talvez você conheça`, if surfaced, is clearly separated from canonical contacts and canonical inviteable lists.
- [ ] Informational notifications derived from the same reconciliation event do not mutate social state by themselves.
- [ ] Reconciliation is idempotent and does not create duplicate contact rows or duplicate inviteable rows.

## Validation Steps

- [ ] Backend automated: identity materialization at the chosen trigger reconciles prior imported hashes into outbound `contact_match` for eligible viewers without duplicate rows.
- [ ] Backend automated: advisory inbound reflection surfaces do not create `Contato`, `inviteable_reason`, or `contact_group` eligibility by themselves.
- [ ] Backend automated: repeated trigger processing is idempotent and privacy-safe.
- [ ] Flutter automated: if `Talvez você conheça` is surfaced, it remains visually/functionally distinct from canonical contacts and canonical inviteables.
- [ ] Manual smoke: one user imports hashes first, another user later completes the chosen identity-materialization milestone, previous importers receive the approved reconciliation outcome, and the newly materialized user only sees advisory reflection surfaces with no automatic inviteability.
