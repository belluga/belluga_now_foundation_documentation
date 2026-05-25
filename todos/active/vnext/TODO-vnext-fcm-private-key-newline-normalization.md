# TODO (vNext): FCM Private Key Newline Normalization

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Pending on `2026-05-23`.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Firebase service-account JSON commonly stores `private_key` with escaped newline sequences (`\n`).
- The production admin text editor accepts plain text and currently preserves literal `\n` when the operator copies from JSON.
- Runtime diagnosis on `main` showed the credential decrypting successfully after admin save, with `BEGIN/END PRIVATE KEY` present, but with literal escaped newlines and no actual PEM line breaks.
- In that state, `openssl_pkey_get_private()` fails, OAuth is not attempted, FCM delivery returns zero accepted targets, and no `push_delivery_logs` are materialized.
- The immediate production workaround is manual: paste/save the key with real PEM line breaks. This TODO records the durable product/runtime fix for a later slice.

## Contract Boundary
- This TODO owns a future cross-stack fix for accepting, storing, validating, and using FCM private keys copied from either PEM text or Firebase JSON format.
- This TODO is deferred and does not authorize changes during the current production push diagnostic.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `vNext`, `Cross-Stack`, `Secrets-Handling`, `Push-Runtime-Hardening`, `Deferred`
- **Next exact step:** when selected for implementation, refine whether normalization happens at UI input time, backend save time, runtime use time, or all three, then freeze the accepted credential formats.

## Scope
- [ ] Make the admin UI understand pasted Firebase JSON-style private keys containing escaped `\n`, either by normalizing before submit or clearly previewing/validating the effective PEM format.
- [ ] Normalize and validate `private_key` on backend save so JSON-style escaped newlines become real PEM line breaks before encryption.
- [ ] Add runtime defensive normalization before any FCM/OAuth use so existing escaped-newline credentials do not silently fail if they bypassed save-time normalization.
- [ ] Validate that normalized keys are loadable by OpenSSL before accepting credential updates.
- [ ] Preserve the existing rule that private keys are never returned by the backend.
- [ ] Add explicit operator-facing error messages for invalid private keys, instead of accepting a value that later causes provider bootstrap failure.
- [ ] Add regression tests for raw PEM input, JSON-escaped `\n` input, invalid key input, and no-secret-leak response shape.

## Out of Scope
- Changing Firebase project credentials or tenant push enablement semantics.
- Replaying historical `push_messages`.
- Changing invite push payload, routing, icon, image, or audience behavior.
- Replacing the FCM HTTP v1 integration.

## Definition of Done
- [ ] Admin credential input accepts both PEM line-break format and Firebase JSON escaped-newline format.
- [ ] Backend persists an encrypted normalized PEM value or otherwise guarantees normalized use without leaking the key.
- [ ] Runtime FCM/OAuth code is resilient to escaped-newline credentials already present in storage.
- [ ] Invalid private keys fail at save or explicit validation time with an actionable error.
- [ ] Tests prove FCM bootstrap succeeds with JSON-escaped private keys after normalization.
- [ ] Tests prove invalid keys are rejected and do not produce later silent `accepted_count=0,responses=[]` failures.

## Validation Steps
- [ ] Run focused Laravel tests covering credential upsert, normalization, and FCM bootstrap.
- [ ] Run focused admin/frontend tests if UI normalization or validation is implemented client-side.
- [ ] Run a real non-production FCM send using a credential pasted from Firebase JSON with escaped `\n`.
- [ ] Run the Laravel CI-equivalent and the relevant frontend CI-equivalent before promotion.

## Notes
- Current production workaround remains manual PEM formatting until this TODO is implemented.
- Runtime defensive normalization is still required even if UI and backend save normalize, because it protects credentials saved before the fix or inserted by operational tooling.
