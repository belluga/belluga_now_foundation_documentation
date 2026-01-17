# Documentation: Tenant Administration Module

**Version:** 0.1 (Placeholder)  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

Placeholder for the Tenant Administration (landlord) interface where city governments or enterprise tenants manage account profile onboarding, plan assignments, and high-level analytics. This document will be expanded after the tenant-facing app modules are finalized, ensuring the admin capabilities align with real consumer workflows.

## 2. Intended Responsibilities

1. **Account Profile Lifecycle Management:** Approve/reject account profile applications, assign plan tiers, manage verification flags.
2. **Account Profile Analytics Overview:** Monitor account profile performance (invites, attendance, revenue) using aggregate data from Account Profile Analytics.
3. **Tenant Configuration:** Define map regions, featured campaigns, rule sets for the Tenant Home Composer, and policy settings (invite quotas, suppression rules).
4. **Compliance & Auditing:** View audit trails (invite Fulfillment steps, attendance confirmations) and respond to data-access requests.
5. **Government/Institutional Reporting:** Generate reports for city stakeholders (tourism impact, local business engagement, account profile mix).

## 3. API Endpoint Definitions

### `PATCH /api/v1/settings/push`
Update tenant push settings (push-only).

**Request Schema**
```json
{
  "push": {
    "max_ttl_days": 30,
    "throttles": {
      "max_per_minute": 60,
      "max_per_hour": 600
    }
  }
}
```

**Response Schema**
```json
{
  "data": {
    "max_ttl_days": 30,
    "throttles": {
      "max_per_minute": 60,
      "max_per_hour": 600
    }
  }
}
```

### `GET /api/v1/settings/firebase`
Fetch firebase settings.

**Response Schema**
```json
{
  "data": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  }
}
```

### `PATCH /api/v1/settings/firebase`
Update firebase settings.

**Request Schema**
```json
{
  "firebase": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  }
}
```

**Response Schema**
```json
{
  "data": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  }
}
```

### `GET /api/v1/settings/telemetry`
List telemetry integrations.

**Response Schema**
```json
{
  "data": [
    {
      "type": "mixpanel",
      "track_all": false,
      "events": ["string"],
      "token": "string"
    }
  ],
  "available_events": ["string"]
}
```

### `POST /api/v1/settings/telemetry`
Add or update a telemetry integration (upsert by `type`).

**Request Schema**
```json
{
  "type": "mixpanel",
  "track_all": false,
  "events": ["string"],
  "token": "string"
}
```

**Response Schema**
```json
{
  "data": [
    {
      "type": "mixpanel",
      "track_all": false,
      "events": ["string"],
      "token": "string"
    }
  ],
  "available_events": ["string"]
}
```

### `DELETE /api/v1/settings/telemetry/{type}`
Remove a telemetry integration by type.

**Response Schema**
```json
{
  "data": [
    {
      "type": "webhook",
      "track_all": false,
      "events": ["string"],
      "url": "https://example.org/hook"
    }
  ],
  "available_events": ["string"]
}
```

**Field Definitions**
- `telemetry.type` (enum): `mixpanel`, `firebase`, `webhook`
- `telemetry.track_all` (bool): when true, all supported events are emitted and `events` is ignored.
- `telemetry.events` (list): required when `track_all=false`; ignored when `track_all=true`.
- `available_events` (list): backend event names supported for Mixpanel and webhook telemetry.

## 4. Next Steps

Defer detailed schemas and APIs until the core consumer modules are stable. Tenant admin requirements will be inferred from:

- Account Profile Catalog & Offer module (what entities need CRUD).
- Invite & Social Loop module (quota management, attendance metrics).
- Task & Reminder module (outstanding compliance tasks).
- Web-to-App policy constraints (e.g., what channels tenants can enable).
