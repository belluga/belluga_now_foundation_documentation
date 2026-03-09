# TODO-v1 Home Favorites Query Contract

## Scope
- Establish the ideal data contract for Home favorites so the section stops issuing its own duplicate upcoming agenda fetch on initial load.
- Define whether favorites should consume a dedicated projection, shared home snapshot, or lazy follow-up query.

## Out Of Scope
- Implementing the contract in this TODO.
- `my events` query redesign.

## Problem Statement
- `FavoritesSectionController` currently fetches upcoming events directly on Home initialization just to reorder favorites.
- This duplicates agenda traffic and ties favorites ordering to an eager network call.

## Definition Of Done
- Canonical contract decision documented.
- Tactical implementation path defined.
- Validation strategy defined.

## Validation Steps
- Review Home composition and request graph.
- Define target backend/client contract and expected tests.
