# TODO (MVP): UI/UX Polish Pass (Home, Map, Agenda, Profile)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Flutter Team + Product  
**Objective:** Apply MVP UI/UX adjustments focused on clarity and simplification.

---

## A) Home
- [x] ✅ Remove search bar (MVP).
- [x] ✅ Remove alert surface (MVP).
- [x] ✅ “My Events” tap leads directly to event (clarify target).

## B) Favorites
- [x] ✅ Order favorites by nearest upcoming event.
- [x] ✅ Tapping favorite in strip opens Agenda tab (with that context).

## C) Map
- [x] ✅ Back button appears only when there is a navigation stack; otherwise route to Home.

## D) Event Card
- [x] ✅ Show distance on event card.

## E) Agenda
- [x] ✅ Add distance radius icon in Agenda.
- [x] ✅ Hide action buttons while search is active.

## F) Profile
- [x] ✅ Remove language (Idioma) section for MVP.
- [x] ✅ Remove notifications section for MVP.
- [x] ✅ Add privacy policy entry.
- [x] ✅ Account security → change password.
- [x] ✅ Remove check-in metric (keep only sent invites + accepted).
- [x] ✅ Allow change avatar.
- [x] ✅ Refresh profile header (reduce repetitiveness; show “changed” state).
- [x] ✅ Preferences: add max radius.

---

## G) Mixpanel
- [x] ✅ Define trigger moments for each Mixpanel event (align with `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-and-push.md`).
