# TODO (VNext): Event Check‑in (Presence Confirmation)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-01-19

## Objective
Define and implement **Event Check‑in** for presence confirmation, including rules, endpoint contracts, and audit/telemetry impacts.

## Scope (Study + Implementation Plan)
- Define **check‑in methods** (geofence, QR, staff/manual override).
- Define **presence rules** (time windows, distance thresholds, re-check‑in policy).
- Define **endpoint contracts** (`POST /api/v1/events/{event_id}/check-in` + response fields).
- Define **data model** fields (status, method, confirmed_at, source metadata).
- Define **security/abuse guardrails** (rate limits, fraud prevention).
- Define **client UX** (eligible state, errors, confirmations).
- Define **telemetry** events and dashboard implications.

## Out of Scope
- MVP agenda rules (MVP uses invite acceptance only).
- Billing or rewards tied to check‑in.
- Full fraud detection system (beyond basic guards).

## Pending Decisions (VNext)
- Primary method for MVP+1: **geofence vs QR vs staff**.
- Geofence defaults (radius, accuracy, fallback).
- Time window rules (early/late tolerance).
- Check-in eligibility in MVP vs VNext (explicitly deferred from V1 events/agenda).
- Storage location (event attendance collection vs embedded on invite).
- Idempotency rules (double check‑in behavior).
- Backfill/override rules for tenant admins.

## Success Criteria
- Clear, auditable check‑in flow with deterministic rules.
- No impact on MVP confirmed_only logic.
- API + UI behaviors defined with testable constraints.
