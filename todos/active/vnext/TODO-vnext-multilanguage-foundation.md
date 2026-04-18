# TODO (VNext): Multilanguage Foundation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Active  
**Owners:** Flutter Team, Laravel Team  
**Objective:** Establish a canonical multilanguage foundation so the app can intentionally support multiple locales without relying on device-language drift or scattered `DateFormat`/string decisions.

---

## Context
- MVP is being locked to `pt_BR` as the single public app locale to avoid mixed-language runtime behavior.
- Current Flutter surfaces still contain locale-sensitive formatting logic spread across app shell, helper extensions, and individual widgets.
- Future multilanguage support must be designed deliberately, not by reopening system-locale inheritance ad hoc.

## Scope
- Define the canonical locale source of truth for the app shell.
- Define how `intl`, Flutter localizations, and timezone-aware date formatting must stay aligned.
- Define where translated strings live and how feature modules consume them.
- Define backend contract expectations for locale-aware content versus locale-agnostic payloads.
- Define migration rules for existing hardcoded Portuguese strings and date/number formatting helpers.

## Out of Scope
- Shipping multiple locales in MVP.
- Translating all current product copy immediately.
- Reworking backend content-management semantics beyond what is required to define the future contract.

## Tasks
- [ ] ⚪ Pending — Define the canonical Flutter locale architecture (`MaterialApp` locale, supported locales, delegates, `intl` default locale, and testing strategy).
- [ ] ⚪ Pending — Define the module-level string ownership model (generated localization catalogs vs other strategy) and usage rules.
- [ ] ⚪ Pending — Audit public/user-facing date, number, and pluralization helpers for locale drift risks.
- [ ] ⚪ Pending — Define backend/client contract rules for locale-sensitive versus locale-neutral payload fields.
- [ ] ⚪ Pending — Produce an incremental migration plan from hardcoded Portuguese MVP copy to deliberate multilanguage support.

## Acceptance Criteria
- [ ] ⚪ Pending — There is one canonical app-locale source of truth, and helper/formatter code cannot silently diverge from it.
- [ ] ⚪ Pending — Locale-sensitive formatting rules are documented for Flutter and backend surfaces.
- [ ] ⚪ Pending — Feature teams have a clear migration path from MVP `pt_BR` lock to true multilanguage support.

## Traceability
- Related MVP decision: `TODO-v1-screen-public-account-profile-detail-polish.md` locks the public app locale to `pt_BR` to stop mixed-language date rendering before multilanguage is intentionally designed.
