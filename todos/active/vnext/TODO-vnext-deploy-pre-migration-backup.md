# TODO (VNext): Pre-migration Backup/Snapshot on Stage/Main Deploy

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owner:** Delphi
**Date:** 2026-02-17

## Goal
Before running any landlord/tenant migrations during automated `stage`/`main` deploy, capture a recoverable backup/snapshot of the database(s), so a bad migration can be rolled back operationally.

## Context / Evidence
- We changed deploy to run migrations automatically in `stage` and `main`.
- Without a backup/snapshot, a migration error can cause irreversible data/index drift.

## Scope
- Add a deploy pre-step that triggers a backup/snapshot before migrations for:
  - **Production (`main`)**: required
  - **Stage (`stage`)**: recommended (configurable)
- Support two runtime modes:
  - MongoDB Atlas: create an on-demand snapshot (preferred) or enforce that PITR/snapshots are enabled and record the snapshot identifier/time.
  - Self-hosted MongoDB: run `mongodump` to a secure location with retention policy.

## Out of Scope
- Designing a full DR plan (multi-region restore automation).
- Migrating away from Atlas/self-hosted choices.

## Design Notes (Decision Candidates)
- Preferred: Atlas snapshot via API using a GitHub secret token scoped to the project.
- Fallback: `mongodump` executed on the server (only when DB is local/self-hosted).
- Deploy should fail fast if `main` backup is required but cannot be performed.

## Definition of Done
- [ ] ⚪ Pending `main` deploy performs backup/snapshot before migrations.
- [ ] ⚪ Pending Backup/snapshot identifier (or timestamp + artifact path) is logged in deploy output.
- [ ] ⚪ Pending Failure to backup blocks `main` deploy (explicit error).
- [ ] ⚪ Pending `stage` deploy behavior is configurable (on by default, can be disabled via env var).
- [ ] ⚪ Pending Documentation updated with required secrets and retention expectations.

## Validation Steps
- [ ] ⚪ Pending Dry-run in stage: verify snapshot step runs and migrations still run after.
- [ ] ⚪ Pending Dry-run in main: verify deploy is blocked when backup credentials are missing.

