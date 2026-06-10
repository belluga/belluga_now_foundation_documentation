# Feature Brief: Agnostic Adjust Example Surface Extraction

## Objective

Define the next `agnostic_adjust` wave that promotes reusable example/template surfaces into the Boilerplate/Delphi boundary without migrating Belluga Now-specific runtime values as active defaults.

## Why This Slice Exists

The current root/tooling migration clarified a narrower follow-up front:

- some surfaces are structurally generic and should exist as reusable `.example` or template assets;
- the current project already contains useful source material for those examples;
- the current source material often still embeds Belluga/tenant-specific values that must not become active Boilerplate defaults.

This wave exists to convert those surfaces into reusable examples while preserving current concrete values only as operator-facing comments or guidance when helpful.

## In Scope

- classify which current project surfaces should become reusable `.example` or template assets;
- define the rule that current Belluga values may appear only as comments, placeholders, or explanatory reference text inside those examples;
- prepare the first execution slice for example-driven surfaces such as:
  - local navigation env examples;
  - Flutter `config/defines/*.example.json` surfaces;
  - downstream NGINX overlay example files;
  - deep-link / app-link payload example contracts.

## Out of Scope

- migrating Belluga browser specs or route inventories into Delphi;
- promoting live Belluga domains, tenants, or branding values as active defaults;
- changing runtime/product behavior in `laravel-app`, `flutter-app`, or `web-app`;
- executing the actual extraction in this planning slice.

## Acceptance Shape

- reusable example candidates are explicitly listed and bounded;
- each candidate is classified as `promote as example`, `keep downstream only`, or `needs prior generic split`;
- the example-authoring rule is frozen: current project values may survive only as comments/reference snippets, not as active defaults;
- a tactical TODO is ready for later execution without redoing this classification pass.
