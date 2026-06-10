# Feature Brief: Agnostic Adjust Tooling Ownership Migration

## Objective

Promote clearly generic Flutter/topology tooling from the downstream root into `delphi-ai`, while preserving thin root wrappers or project-owned entrypoints only where local ergonomics still matter.

## Why This Slice Exists

The current `agnostic_adjust` front already established that some root tooling is not Belluga Now-specific:

- `tools/flutter/build_web_bundle.sh` overlaps with the Delphi Flutter web build baseline;
- `tools/submodules/*` encodes reusable multi-repo workspace behavior that should be topology-driven rather than Belluga-driven.

This slice extracts those generic mechanics without touching product-specific browser specs, deep-link payloads, or Belluga route semantics.

## In Scope

- converge `tools/flutter/build_web_bundle.sh` to a thin root wrapper over the canonical Delphi Flutter web build script;
- promote the reusable `tools/submodules/*` behavior into canonical Delphi tooling that discovers submodules from `.gitmodules` instead of hardcoding Belluga paths;
- keep root wrappers for existing local entrypoints when needed;
- update root documentation to clarify canonical ownership.

## Out of Scope

- moving product-specific Playwright specs or Belluga route assertions into `delphi-ai`;
- changing Laravel/Flutter/Web product behavior;
- promoting project-specific deep-link payloads or domain values into Delphi;
- broad reorganization of all `tools/flutter/**` in one wave.

## Acceptance Shape

- canonical generic implementation lives in `delphi-ai/**`;
- downstream root keeps only thin wrappers or project-specific contracts;
- README/onboarding no longer presents the migrated logic as Belluga-root-owned;
- targeted shell validation passes for the touched wrappers and Delphi tools.
