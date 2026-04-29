# TODO (V1): Event Detail About Rich Media Contract

**Status:** Completed (`decision reconciled and closed on 2026-04-18`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Contract-Decision-Frozen`, `Closure-Synced`, `Follow-On-Split`
**Next exact step:** Implementation follow-through belongs to `foundation_documentation/todos/completed/TODO-store-release-event-content-save-sanitization.md`.
**Owners:** Flutter Team, Laravel Team
**Objective:** Resolve the canonical contract for tenant-public event-detail `Sobre` content when `event.content` contains HTML/rich formatting, especially around media-only or unsupported HTML.

---

## Closure Note

This TODO is no longer an active decision owner. The product/contract decision is now explicit:

- `event.content` does **not** accept arbitrary HTML.
- unsupported tags must not be persisted as canonical content.
- media-only or non-text HTML does **not** count as valid `Sobre` content.
- canonicalization/sanitization belongs on the write/save path, with backend enforcement as the guarantee.
- frontend/editor behavior must also sanitize or block unsupported markup so the UI does not create a false impression that such content is accepted.

With that decision frozen, the old open question in this TODO is resolved. The remaining work is no longer "decide whether rich-media-only HTML is valid"; it is "implement save-time sanitization safely and consistently." That implementation work is now owned by the dedicated store-release lane `TODO-store-release-event-content-save-sanitization.md`.

## Last Confirmed Truth

As of `2026-04-18`:

- the current runtime still gates `Sobre` by checking whether stripped HTML contains real text;
- this matches the approved contract direction for read behavior;
- what remains open is write-time sanitization/canonicalization, not the decision itself.

## Decision Summary

- `Sobre` renders canonical sanitized rich text, not arbitrary raw HTML.
- Unsupported tags must be removed before persistence.
- Event detail must not display a `Sobre` tab for content that is only unsupported/media-only markup with no real textual body after canonicalization.
- Read-time stripping is not the canonical place to "accept then decide"; the save path must define the accepted subset.

## Historical Context

The former active TODO framed the problem as an unresolved contract question: whether media-only/non-text HTML should count as valid `Sobre` content. That question is now answered in the negative, so the active TODO would otherwise continue acting as stale authority. This closure preserves the decision and moves the actual implementation hardening into the explicit store-release TODO.
