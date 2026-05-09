# TODO (V1): Account Profile (Venue) Profile (Reduced Tabs)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Flutter Team + Product  
**Objective:** Deprecated. Merged into `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`.
**Note:** This file is retained for history; all work continues in the unified Account Profile UI slice.

---

## References
- `foundation_documentation/todos/completed/TODO-mvp-scope-definition.md`
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-implementation.md`
- `lib/presentation/tenant/partners/models/partner_profile_config.dart`

---

## A) Decisions (MVP)
- Use existing `PartnerProfileConfig` / `ProfileModuleId` (no new screen).
- Header/taxonomy stays above tabs.
- Bio is optional; if present, show `Sobre` and it is always the first tab.
- Tabs:
  - `Sobre` (conditional) → `ProfileModuleId.richText`
  - `Como Chegar` (always) → `ProfileModuleId.locationInfo` (map preview + route CTA)
  - `Eventos` (always) → `ProfileModuleId.agendaList`
- Exclusions: `externalLinks`, `supportedEntities`, commerce/store modules.

---

## B) Flutter Tasks
- [x] ✅ Implement venue account profile `PartnerProfileConfig` with the reduced tabs above (Flutter naming).
- [x] ✅ Ensure Venue account profiles open from:
  - [x] ✅ Event Detail `O Local` CTA
  - [x] ✅ Venue favorites

---

## C) Acceptance Criteria
- [x] ✅ Venue account profile renders reduced tabs with correct ordering.
- [x] ✅ `Como Chegar` shows map preview + route CTA.
- [x] ✅ Tapping venue in Event Detail routes to the Venue account profile.
