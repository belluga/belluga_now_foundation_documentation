# TODO (vNext): Home and Discovery Taxonomy Aggregation Contract

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Pending on `2026-05-21`. This TODO owns the future query and contract redesign for Home/Discovery filters so taxonomy chips only surface options that actually exist in the current recorte.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Home and Discovery currently render all taxonomies even when the active result set contains no items for some of them.
- The desired behavior is an integrated query contract where the event aggregation returns the taxonomy universe for the full filtered recorte, not just the current page.
- The user explicitly requested that this work be deferred until after a query-shape/performance study finds a safe aggregation strategy.

## Contract Boundary
- This TODO is a `vnext` study + implementation slice for taxonomy/filter aggregation.
- It begins with aggregation/query design and performance scrutiny before any code changes to the live endpoint/UI contract.
- It is out of scope for the current bugfix session.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `vNext`, `Cross-Stack`, `Query-Design`, `Performance-Sensitive`
- **Next exact step:** study the event aggregation shape needed to emit whole-recorte taxonomy metadata with acceptable performance, then scope the code change from that query contract.
