# TODO (V1): Event Detail About Rich Media Contract

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Pending`
**Qualifiers:** `Next-Version`, `Contract-Decision-Pending`
**Next exact step:** Freeze whether `event.content` must preserve media-only/non-text HTML as valid `Sobre` content instead of the current text-normalized contract, then add fail-first coverage before any implementation.
**Owners:** Flutter Team, Laravel Team
**Objective:** Establish the canonical contract for tenant-public event-detail `Sobre` content when the source payload contains rich HTML with little or no plain text, so the platform no longer relies on implicit text normalization to decide whether the tab exists.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`

---

## References

- `foundation_documentation/todos/active/vnext/TODO-v1-immersive-event-detail-dynamic-profile-category-tabs.md`
- `foundation_documentation/modules/events_module.md`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_info_section.dart`

---

## Problem Statement

- The current runtime path normalizes `event.content` through a text-oriented HTML value object before the immersive event-detail screen evaluates whether `Sobre` should exist.
- Because of that contract, media-only or non-text HTML blocks (for example embeds or image-only content) are not treated as renderable `Sobre` content in V1.
- This was intentionally not changed inside the current promotion lane because it requires a contract decision, not only a UI tweak.

## Scope

- Decide whether media-only/non-text HTML must count as valid `Sobre` content.
- If yes, define the canonical backend-to-Flutter representation for rich event content.
- Add fail-first automated coverage for the chosen contract.
- Update event-detail tab gating and content rendering only after the contract is frozen.

## Out of Scope

- Generic rich-content redesign outside event detail.
- CMS/editor redesign unrelated to the event `content` contract.
- Map, profile, or other POI surfaces.

## Decision Baseline (Frozen)

- `D-01`: Current promoted V1 behavior stays as-is until this lane is executed; blank/effectively text-empty `event.content` omits `Sobre`.
- `D-02`: No implementation may treat media-only HTML as valid `Sobre` content unless the domain contract is updated first.
- `D-03`: This lane must prove the chosen behavior with fail-first tests at the domain/decoder/UI boundary, not only widget smoke.
- `D-04`: If the contract changes, Laravel and Flutter must move together so admin payloads, public payloads, and immersive rendering stay aligned.

## Tasks

- [ ] ⚪ Freeze the canonical contract for rich/non-text event `content`.
- [ ] ⚪ Add fail-first coverage for media-only HTML at the value-object/decoder boundary.
- [ ] ⚪ Add fail-first immersive-event-detail coverage for `Sobre` tab presence/absence under the approved contract.
- [ ] ⚪ Implement backend + Flutter contract changes if required.
- [ ] ⚪ Run focused validation and manual smoke on `/agenda/evento/:slug`.

## Acceptance Criteria

- [ ] ⚪ The platform has an explicit rule for whether media-only HTML is valid `Sobre` content.
- [ ] ⚪ Automated tests prove the chosen rule from payload/domain normalization through immersive tab gating.
- [ ] ⚪ Event-detail rendering no longer depends on undocumented side effects of text normalization.
- [ ] ⚪ Admin/public payload handling stays aligned with the approved contract.

## Validation Steps

- [ ] ⚪ Laravel contract tests if payload/domain semantics change.
- [ ] ⚪ Flutter focused tests for decoder/value-object + immersive event detail.
- [ ] ⚪ `fvm dart analyze --format machine`
- [ ] ⚪ Manual smoke: text-only, media-only, mixed-content, and blank-content events.
