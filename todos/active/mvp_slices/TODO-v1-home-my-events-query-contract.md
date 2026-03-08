# TODO-v1 Home My Events Query Contract

## Scope
- Establish the ideal query contract for Home `my events` so the section stops piggybacking on the generic public agenda feed.
- Define the canonical backend/client contract for confirmed user events in Home.

## Out Of Scope
- Implementing the contract in this TODO.
- Favorites/home agenda pagination behavior.

## Problem Statement
- `UserEventsRepository.fetchMyEvents()` currently calls the public upcoming agenda feed and filters locally by confirmed IDs.
- This creates redundant Home requests and couples `my events` to the wrong feed semantics.

## Definition Of Done
- Canonical contract decision documented.
- A tactical implementation path defined for Flutter and, if needed, Laravel.
- Validation strategy defined.

## Validation Steps
- Review Home composition and request graph.
- Define target backend/client contract and expected tests.
