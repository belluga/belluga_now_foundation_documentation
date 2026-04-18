# TODO (VNext): Account Profile Slug Projection Resync

**Status:** Active  
**Stage:** `Backlog`  
**Owners:** Laravel Team, Flutter Team  
**Objective:** Ensure any `AccountProfile.slug` change triggers deterministic resync of all downstream projections and embedded payload snapshots that denormalize account-profile identity.

## Why

- Event detail linked-profile cards are now strict route-driven consumers of `linked_account_profiles[].slug`.
- Favorites, event payloads, and other embedded/snapshot projections may materialize account-profile slug for direct navigation.
- A profile slug change without projection resync would silently stale those downstream read models.

## Required Follow-Up

- Introduce a canonical slug-change lifecycle hook for `AccountProfile`.
- Dispatch projection resync for every downstream surface that embeds account-profile identity, including at least:
  - event documents / event-party metadata
  - favorites snapshots
  - any additional public read models embedding `slug`
- Define whether the resync runs synchronously, asynchronously, or via explicit maintenance command per projection class.
- Add fail-first coverage proving a slug update invalidates and refreshes downstream projections.

## Explicit Non-Goal For Current Lane

- This TODO does **not** weaken the current runtime contract.
- Current V1 remains strict: missing event linked-profile `slug` is payload-invalid and must fail fast.
