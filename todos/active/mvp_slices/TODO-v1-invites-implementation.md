# TODO (V1): Invites Implementation (Backend + Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team (source of truth) + Delphi (Flutter)  
**Objective:** Deliver invites that are quota-safe, audit-safe, and metrics-ready for partner gamification.

---

## References
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/partner_admin_module.md`
- Deferred items: `foundation_documentation/todos/active/TODO-vnext-parking-lot.md`

## Invite Flow Summary (MVP)
- User confirms presence on an event (enables invite context + metrics).
- To invite:
  - **Matched user**: app uses `POST /api/v1/contacts/import` (hashed contacts). If a match exists, backend creates invite and sends push.
  - **No match**: app generates a share link via `POST /api/v1/invites/share` and sends externally (e.g., WhatsApp).
- Acceptance:
  - `POST /api/v1/invites/share/{code}/accept` binds attribution to `inviter_principal`.
  - Acceptance counts as `invite_accepted` with `source = share_url`.
  - Only one accepted invite per `(tenant_id, event_id, receiver_user_id)`; others become `closed_duplicate`.
- Tracking:
  - `share_visit` is analytics only (not an accepted invite).
  - No raw PII stored; only contact hashes are persisted.

## A) Backend Work

### A1) Data model requirements
- [ ] ⚪ Persist invites with:
  - `event_id`, `tenant_id`
  - `receiver_user_id`
  - `inviter_principal { kind:user|partner, id }`
  - `issued_by_user_id` (nullable; required when inviter is partner)
  - `status` includes `closed_duplicate`
  - `credited_acceptance` boolean
  - timestamps: `created_at`, `viewed_at?`, `responded_at?`, `updated_at`

### A1.1) External share codes (new users attribution)
- [ ] ⚪ Implement share code storage:
  - [ ] ⚪ `code` → resolves to `{ tenant_id, event_id, inviter_principal, issued_by_user_id? }`
  - [ ] ⚪ record opens on resolve
  - [ ] ⚪ record consumption post-install/post-signup (binds attribution to user)
- [ ] ⚪ Ensure eligibility: anyone who can invite can generate a share code
- [ ] ⚪ Ensure share code does not bypass invite uniqueness (no duplicate invite spam)

### A1.2) Web acceptance (invite landing only) + same-event re-share
- [ ] ⚪ Implement `POST /api/v1/invites/share/{code}/accept` (or equivalent) for web landing acceptance
- [ ] ⚪ Acceptance credits the inviter principal bound to `code` (no multi-inviter selection on web)
- [ ] ⚪ Require Sanctum (`auth:sanctum`) even on web landing acceptance; web obtains an anonymous token first via `POST /api/v1/anonymous/identities`
- [ ] ⚪ Create/bind an anonymous identity on web acceptance so the backend can persist acceptance + attribution (anonymous user + Sanctum token is sufficient)
- [ ] ⚪ Allow external re-share only for the same `event_id` after acceptance, with strict backend limits
- [ ] ⚪ Invite share links must carry the `code` as a GET parameter in the URL

### A1.3) Contacts + realtime deltas
- [ ] ⚪ Implement `POST /api/v1/contacts/import` (hashed contact matching for friend suggestions)
- [ ] ⚪ Expose `/api/v1/invites/stream` SSE for invite deltas (created/updated/deleted)

### A2) Uniqueness + responses
- [ ] ⚪ Enforce uniqueness key:
  - `(tenant_id, event_id, receiver_user_id, inviter_principal.kind, inviter_principal.id)`
- [ ] ⚪ On duplicate: respond `already_invited` (include the existing invite id/code for idempotency if desired)

### A3) Credited acceptance transaction
- [ ] ⚪ On accept:
  - [ ] ⚪ Set selected invite: `status=accepted`, `credited_acceptance=true`, `responded_at=now`
  - [ ] ⚪ For all other invites for `(tenant_id, event_id, receiver_user_id)`:
    - [ ] ⚪ set `status=closed_duplicate`, `credited_acceptance=false` (idempotent)
- [ ] ⚪ Make this transactional (single source of truth for accepted conversions)

### A4) Limits (tenant settings)
- [ ] ⚪ Implement `GET /api/v1/invites/settings` and enforce:
  - [ ] ⚪ per-event per-inviter limits
  - [ ] ⚪ per-day limits (partner + user actor)
  - [ ] ⚪ pending invites cap per receiver
  - [ ] ⚪ suppression lists and opt-out
- [ ] ⚪ On limit hit: return `429` with payload `{ limit_key, resets_at, remaining?, allowed?, scope }`

### A5) Partner-issued invites authorization
- [ ] ⚪ Validate `issued_by_user_id` has an active membership in inviter partner with `can_invite=true`

### A6) Partner event metrics
- [ ] ⚪ Provide aggregates for event host/managing partner:
  - per inviter principal: sent/viewed/accepted(credited)/declined/closed_duplicate
  - per issuer user: same breakdown
  - totals

### A7) Telemetry emission (backend-owned)
- [ ] ⚪ Emit state-changing invite/presence events from backend services (not from UI).
- [ ] ⚪ Use `user_id` as `distinct_id` for all telemetry events.
- [ ] ⚪ Use an idempotency key per event + entity + user (e.g., `${event}:${invite_id}:${user_id}`).
- [ ] ⚪ Mixpanel: send `$insert_id` with the idempotency key.
- [ ] ⚪ Webhook: send unified envelope `{type, timestamp, context, payload}` with `tenant.id` + `user.id`.
- [ ] ⚪ Events + required properties:
  - `invite_accept_selected_inviter` (on persisted inviter selection):
    - `event_id`, `invite_id`, `inviter_kind`, `inviter_id`, `source=invite_accept`
    - Optional: `partner_id` when `inviter_kind=partner`
  - `invite_accepted` (on acceptance commit):
    - `event_id`, `invite_id`, `inviter_kind`, `inviter_id`, `source=invite_accept`
    - Optional: `partner_id`, `credited_acceptance=true`
  - `invite_declined` (on decline commit):
    - `event_id`, `invite_id`, `inviter_kind`, `inviter_id`, `source=invite_decline`
    - Optional: `partner_id`
  - `event_confirmed_presence` (on attendance commit):
    - `event_id`, `source=event_attendance`

---

## B) Flutter Work

### B0) Telemetry ownership (frontend)
- [x] ✅ Do not emit `invite_accept_selected_inviter`, `invite_accepted`, `invite_declined`, or `event_confirmed_presence` from Flutter. These are backend-owned.

### B1) “Accept invite from…” UX (no default)
- [ ] ⚪ Invite card shows:
  - [ ] ⚪ “Escolher convite para aceitar”
  - [ ] ⚪ “+N convites para esse evento”
- [ ] ⚪ Tap opens selector list of inviters (tiles), user must select one
- [ ] ⚪ Accept CTA disabled until selection exists

### B2) Handling `already_invited`
- [ ] ⚪ When sending invite returns `already_invited`, show state “Já convidado” and avoid duplicate UI entries

### B3) Client settings fetch
- [ ] ⚪ Add a repository call for `/api/v1/invites/settings` (cache briefly)
- [ ] ⚪ Use settings only for UX messaging; do not assume limits client-side as authoritative

### B3.1) External share deep links (new users attribution)
- [ ] ⚪ Support opening share links (WhatsApp/Instagram/etc.) that include a `code`
- [ ] ⚪ Persist pending share `code` through onboarding/auth until user is available
- [ ] ⚪ Call backend `consume` endpoint once the user is known to bind attribution
- [ ] ⚪ Route user into the event context after consuming (or show a safe landing if event is not available)

### B3.2) Web acceptance UX constraints (for Web Team)
- [ ] ⚪ Web invite landing can show “Aceitar” only when reached via a single `code`
- [ ] ⚪ Do not expose agenda-based acceptance on web; agenda-first acceptance remains app-only
- [ ] ⚪ On web landing, mint/resume anonymous identity via `POST /api/v1/anonymous/identities` and use the returned Sanctum token for accept + same-event re-share calls

### B4) Metrics surfacing
- [ ] ⚪ Bind invite-related metrics pills (Profile + Menu hero) to repository streams:
  - sent invites count
  - accepted invites count (credited only)
  - presence confirmations

---

## C) Telemetry + Push (Invite-specific)
- [ ] ⚪ Define source of truth + trigger moments for invite telemetry events (backend vs client).
- [ ] ⚪ Specify push payload contract for invite detection (payload must include `invite` or `invites` root keys so Flutter can upsert).
- [ ] ⚪ Track `invite_received` when invite is first delivered (push payload applied or invite list fetch).
- [x] ✅ Production‑Ready — Track `invite_opened` when invite card is first surfaced (Flutter invite flow).
- [ ] ⚪ Track `invite_accept_selected_inviter` when user selects the inviter to accept.
- [ ] ⚪ Track `invite_accepted` after accept succeeds.
- [ ] ⚪ Track `invite_declined` after decline succeeds.
- [ ] ⚪ Include invite telemetry properties: `tenant_id`, `event_id`, `invite_id` or `invite_code`, `inviter_kind`, `inviter_id`, `source`.
- [ ] ⚪ Invite received push arrives and routes correctly into the app.
- [ ] ⚪ Telemetry shows end-to-end invite funnel with consistent identifiers.

---

## C) VNext Notes (do not implement now)
- Offline persistence of invite state
- Rich suppression management UI
