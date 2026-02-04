# Documentation: Persona Roadmaps
**Version:** 1.0

## 1. Overview
This document tracks active initiatives per persona to keep architectural work aligned across Flutter, Laravel, DevOps, and CTO/Tech Lead responsibilities.

---

## 2. Flutter Engineer
**Active Initiatives**
- Implement tenant-admin Taxonomy Registry UI (taxonomies + terms) and registry-backed term selection for account profiles, static assets, and events.
- Maintain DTO -> Domain -> Projection flow and keep controllers as the single source of UI state.
- Keep AutoRoute registrations and ModuleScope wiring aligned with new screens.

**Dependencies / Backend Alignment**
- Tenant-admin taxonomy endpoints under `/admin/api/v1` are required for real data; Flutter must remain aligned to documented contracts.
- Profile type registry (`account_profile_types`, `static_profile_types`) must be used to filter allowed taxonomies.

---

## 3. Laravel Engineer
**Active Initiatives**
- Maintain tenant-admin taxonomy + static asset endpoints and validation rules as documented.
- Enforce taxonomy validation on account profiles, static assets, and events.

---

## 4. DevOps / Docker
**Active Initiatives**
- Maintain submodule alignment and symlinked docs/scripts for shared tooling.

---

## 5. CTO / Tech Lead
**Active Initiatives**
- Ensure documentation-first discipline for all new capabilities.
- Keep roadmap statuses aligned with implemented contracts and UI delivery.
