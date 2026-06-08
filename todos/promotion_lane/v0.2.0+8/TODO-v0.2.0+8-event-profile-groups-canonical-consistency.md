# Title
VNext: Event Profile Groups Canonical Consistency

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Events and occurrences already link Account Profiles through event related-profile data. The current event public UI can derive related-profile tabs from Account Profile type plural labels, while Account Profiles now have custom one-level profile groups. Keeping two similar grouping implementations creates a high-risk context split: future sessions may treat event related profiles, occurrence related profiles, and Account Profile nested groups as separate UI/data concepts.

The required direction is a single group authoring pattern for Account Profile groups, event-level related profiles, and occurrence-owned related profiles. Event and occurrence groups organize linked profiles in admin/API data, but public event detail must render one aggregated tab set for the whole event, containing all event-level and occurrence-owned groups/profiles. Switching the selected occurrence changes date/programming/route only; it must not rebuild or swap the profile tabs. The existing `event_parties` relation still has a job: it remains the backend/materialized relation for who is linked to the event or occurrence, including metadata, party type, permission/projection semantics, and compatibility consumers.

Manual validation later found a gap inside the tenant-admin occurrence/date programming sheet (`Editar data` -> `Adicionar item de programação`). The action `Adicionar perfil à data` can still add a related Account Profile directly to the occurrence/date flat relation, bypassing the new occurrence-owned `profile_groups` authoring model. That path must be brought under the same canonical group contract instead of continuing to write legacy-only relationship state.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `event-profile-groups-canonical-consistency`
- **Parent capability:** `TODO-v0.2.0+8-nested-account-profile-groups.md`
- **Why this is the right current slice:** the event final UI must work correctly for every event/occurrence editing scenario, while preserving the existing `event_parties` contract and avoiding duplicated form/grouping implementations.
- **Direct-to-TODO rationale:** the user identified the canonicality risk and requested a TODO with full test matrix and validation behavior.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Feature`, `Cross-Stack`, `Tenant-Admin`, `Tenant-Public`, `Events`, `User-Visible`, `Contract-Driven`, `Promotion-Lane-Pending`, `Requires-APROVADO`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through the remaining package-wide promotion follow-through; the current Copilot-mimic loop reopened this scope, the authoritative mutation shard was repaired, and the scope is now clean/no-reopen locally.

## Scope
- [ ] Define a shared `ProfileGroup` contract for Account Profile nested groups, event related-profile groups, and occurrence related-profile groups.
- [ ] Use the same one-level group shape everywhere: stable `id`, `label`, `order`, and ordered `account_profile_ids`.
- [ ] Tenant-admin event create and update forms author related profiles through groups, not through a separate independently editable flat related-profile list.
- [ ] Tenant-admin occurrence editing authors occurrence-owned related profiles through the same group authoring pattern.
- [ ] Reuse one shared Flutter group editor/controller/helper for Account Profile groups and Event/Occurrence groups; event-specific copy-paste editors are not acceptable.
- [ ] Keep `event_parties` as the backend/materialized linked-profile relation for events and occurrences.
- [ ] New admin submit state derives/materializes `event_parties` from the union of group member ids, or sends both only when the backend can deterministically assert equality.
- [ ] Backend validates that explicit groups and `event_parties` cannot diverge.
- [ ] Existing legacy payloads/data with `event_parties` and no explicit groups continue to work and render the type-plural fallback until edited or repaired.
- [ ] Public event detail renders one aggregate tab set from explicit event groups plus all occurrence-owned groups; it does not swap tabs when the selected occurrence changes.
- [ ] Public event detail falls back to Account Profile type plural labels only when no explicit groups exist anywhere in the aggregate event/occurrence tab set.
- [ ] Legacy fallback aggregation is event-wide for public UI: legacy event/occurrence linked profiles can contribute to the aggregate fallback tab set without binding tabs to the selected occurrence.
- [ ] Tenant-admin event editing hydrates legacy fallback groups as editable generated groups and persists them as explicit `profile_groups` on save.
- [ ] Public selected occurrence detail preserves the aggregate event profile-tab set while applying the selected occurrence only to date/programming/location context.
- [ ] Existing `linked_account_profiles[]`, occurrence `own_linked_account_profiles[]`, and programming linked-profile projections remain available for hero/image fallback, direct navigation, maps, programming, and compatibility consumers.
- [ ] Existing programming semantics are preserved: programming remains occurrence-owned and can reference only Account Profiles linked to the same occurrence-owned related-profile set.
- [x] The programming/date sheet action that adds a new profile to the date must write through occurrence-owned `profile_groups`, not through a legacy flat occurrence relation.
- [x] If an occurrence/date has no groups, the `Adicionar perfil à data` action is unavailable and the UI explains that a group must be created first.
- [x] If groups exist, adding a profile to the date requires selecting the target group before the profile is added; the new profile is saved in the selected occurrence group and the flat `event_parties` relation is derived/materialized from groups.

## Out of Scope
- [ ] Recursive nesting or group hierarchies deeper than one level.
- [ ] Account Workspace membership/team permissions.
- [ ] Group-based programming restrictions.
- [ ] Raw Account ids as the public render contract.
- [ ] New Account Profile type capability gating for events unless explicitly approved later.
- [ ] Keeping a duplicated event-specific group editor when the shared editor can serve the same contract.
- [ ] Replacing `event_parties` with group rows as the backend relation of record.

## Definition of Done
- [ ] Admin can create an event with grouped related profiles using the shared group editor.
- [ ] Admin can update an event with grouped related profiles using the shared group editor.
- [ ] Admin can create/update occurrence-owned related-profile groups using the shared group editor.
- [ ] No independently editable flat related-profile list remains on the event authoring surface; any flat ids are derived from groups.
- [ ] Backend rejects explicit divergence between submitted groups and submitted/materialized `event_parties`.
- [ ] Backend accepts legacy `event_parties` without explicit groups and public fallback rendering works.
- [ ] Admin legacy hydration displays generated type-plural groups, allows editing them through the shared group editor, and converts them to explicit groups on first save.
- [ ] Public event detail renders custom group labels, order, and member cards correctly.
- [ ] Public legacy fallback renders the old type-plural tabs without exposing a public legacy warning or repair message.
- [ ] Public event detail can render mixed Account Profile types in one custom group.
- [ ] Public selected occurrence detail intentionally shows profile groups from every occurrence in the aggregate event tab set; this is not a leak. Programming/date content remains selected-occurrence scoped.
- [ ] Programming still works with grouped occurrence profiles and rejects profiles no longer linked to the selected occurrence.
- [x] `Adicionar perfil à data` never creates a legacy-only occurrence profile link; it requires a selected occurrence group and persists the member in that group.
- [x] When no occurrence groups exist, `Adicionar perfil à data` is disabled with clear inline prerequisite copy; touch/mobile UX does not depend on tooltip-only feedback.
- [ ] Existing event card, hero, map, account image fallback, and linked Account Profile navigation consumers remain functional.
- [ ] Full automated and manual matrix below is executed before delivery closeout.

## Proposed Decisions
- [ ] `D-EVG-01` `ProfileGroup` is the shared shape for Account Profile, Event, and Occurrence contexts: `id`, `label`, `order`, `account_profile_ids`.
- [ ] `D-EVG-02` `event_parties` remains the canonical/materialized backend relation for linked profiles. Groups organize those linked profiles into public/admin tabs.
- [ ] `D-EVG-03` New tenant-admin event state has one mutable source for related-profile membership: `profile_groups`; `event_parties` is derived/materialized and validated.
- [ ] `D-EVG-04` Explicit groups win over type-plural grouping. Type-plural grouping is a fallback only for legacy/no-group data.
- [ ] `D-EVG-05` Event-level groups organize event-level `event_parties`; occurrence-level groups organize occurrence-owned `event_parties`.
- [ ] `D-EVG-06` Public selected occurrence detail renders the aggregate event tab set from event groups plus all occurrence groups. Selecting another occurrence must not remove or replace profile tabs.
- [ ] `D-EVG-07` Programacao remains occurrence-exclusive and group-agnostic.
- [ ] `D-EVG-08` Event/occurrence profile groups are not governed by Account Profile type capability `has_nested_profile_groups`; that capability only controls nested groups on Account Profile authoring.
- [ ] `D-EVG-09` Flutter must expose one shared group editor/controller/helper path for profile groups; event and account forms may configure context-specific labels/data sources but must not duplicate the implementation.
- [ ] `D-EVG-10` Legacy fallback for public tabs is aggregated across the event detail payload. Admin/API scopes can remain event/occurrence-local, but Flutter public tabs must be stable across occurrence switches.
- [ ] `D-EVG-11` Public fallback tab labels are Account Profile type plural labels; profiles of different types are split into different fallback tabs.
- [ ] `D-EVG-12` Tenant-admin legacy hydration creates generated editable fallback groups from linked profiles, and the first successful save persists those groups as explicit `profile_groups`.
- [ ] `D-EVG-13` Public users do not see a legacy/fallback warning. Admin users may see a compact pre-save indication that groups were generated from linked profiles.
- [ ] `D-EVG-14` Fallback tab order must be deterministic, preferably profile type catalog order; if unavailable, use stable plural-label ordering. It must never depend on accidental database order.
- [x] `D-EVG-15` `Adicionar perfil à data` is an occurrence-group mutation path. It must require an occurrence-owned group target and must not add profiles only to the legacy flat occurrence relation.
- [x] `D-EVG-16` The no-group prerequisite UX uses disabled action plus visible inline helper text as the primary explanation. Tooltip-only feedback is not acceptable for touch/mobile; dialog/snackbar feedback may be used only as secondary fallback if another path triggers the blocked action.
- [x] `D-EVG-17` `Vincular perfil da data` remains group-agnostic: it links the programming item to profiles already linked to that occurrence/date, regardless of which occurrence group contains the profile.

## Decisions Pending
| Decision ID | Question | Recommended Baseline | Why It Matters |
| --- | --- | --- | --- |
| `DP-EVG-01` | Must every linked event/occurrence profile belong to exactly one group in new admin flows? | Yes. Exact coverage and no cross-group duplicates for event/occurrence groups. | Prevents hidden linked profiles, duplicate public cards, and nondeterministic tab membership. |
| `DP-EVG-02` | Should the admin API submit only `profile_groups`, or both `profile_groups` and `event_parties`? | Accept both for compatibility, but the new Flutter path derives both from one state and backend rejects mismatch. | Keeps legacy compatibility without allowing two independent sources of truth. |
| `DP-EVG-03` | When event-level and occurrence groups share the same public label, should public detail merge them? | Yes. Merge same normalized label into one public tab, dedupe members, and preserve deterministic first-seen order. | Keeps the simple event-wide tab behavior and avoids duplicate tabs such as multiple `Bandas` groups across dates. |
| `DP-EVG-04` | How should admin hydrate legacy events that have `event_parties` and no explicit groups? | Hydrate editable fallback groups by Account Profile type plural labels. | Lets existing data continue working and gives admins a deterministic first edit state. |
| `DP-EVG-05` | What happens when an admin removes a group that still has members? | Require explicit member removal or move before group deletion. | Prevents accidental unlinking of related profiles and programming side effects. |

## Behavior Contract
### Canonical Model
- `event_parties` answers: which Account Profiles are linked to this event or occurrence, with backend metadata/projection semantics.
- `profile_groups` answers: how the linked Account Profiles are organized into public/admin tabs.
- New tenant-admin event/occurrence authoring must not let these answers drift.

### Validation Rules
- [ ] Group ids are unique within the event-level group set and within each occurrence-owned group set.
- [ ] Group labels are required after trimming and must stay within backend-defined limits.
- [ ] Group order is deterministic; duplicate order values are normalized or rejected consistently.
- [ ] A group member must be a valid tenant-scoped Account Profile.
- [ ] A group cannot contain the same Account Profile id twice.
- [ ] New admin event submit requires `union(profile_groups.account_profile_ids) == event_parties.account_profile_ids` for the event-level scope.
- [ ] New admin occurrence submit requires `union(occurrence.profile_groups.account_profile_ids) == occurrence.event_parties.account_profile_ids` for that occurrence-owned scope.
- [ ] A grouped member not present in `event_parties` is rejected.
- [ ] An `event_parties` member missing from explicit groups is rejected in new admin payloads.
- [ ] Legacy payloads with no explicit groups and non-empty `event_parties` are accepted and rendered with fallback grouping.
- [ ] Public rendering must never show an Account Profile in a custom group unless that profile is linked through the relevant event/occurrence `event_parties` relation.

### Public UI Rules
- [ ] Public event detail builds related-profile tabs from the whole event payload: event-level groups plus every occurrence's own groups.
- [ ] Selecting/switching an occurrence must not rebuild, replace, or narrow related-profile tabs/cards.
- [ ] Selecting/switching an occurrence updates date, programming, route query, and selected-occurrence state only.
- [ ] If explicit groups exist anywhere in the aggregate event/occurrence set, public tabs use those group labels and order, merging duplicate normalized labels into one tab and deduping cards.
- [ ] If no explicit groups exist anywhere in the aggregate event/occurrence set, fallback tabs are derived from the aggregate linked Account Profiles by Account Profile type plural label.
- [ ] Fallback tabs render only non-empty groups and use deterministic type ordering; empty type buckets are hidden.
- [ ] The public UI must not show legacy, generated, repair, or fallback explanatory copy.
- [ ] A mixed group can contain different Account Profile types and must render as one tab.
- [ ] Card identity, image fallback, favorite/share/invite/hero behavior, and navigation must remain correct after grouping.

### Tenant Admin Legacy Hydration Rules
- [ ] Editing an event or occurrence with `event_parties` and no explicit `profile_groups` hydrates generated groups by Account Profile type plural label.
- [ ] Generated fallback groups are editable through the same shared group editor used by explicit groups.
- [ ] Admin may show compact contextual copy that the groups were generated from linked profiles until first save; this copy is admin-only.
- [ ] On first successful save, the generated groups become explicit `profile_groups` and public rendering no longer depends on type-plural fallback for that scope.
- [ ] If the event scope has explicit groups and the selected occurrence scope is legacy, only the occurrence scope hydrates generated groups.
- [ ] If the event scope is legacy and the selected occurrence scope has explicit groups, only the event scope hydrates generated groups.

### Programming/Date Profile-Adder Rules
- [ ] `Vincular perfil da data` can select only profiles already linked to the current occurrence/date; it does not create event/occurrence membership.
- [ ] `Adicionar perfil à data` creates occurrence/date membership and therefore must be routed through occurrence-owned `profile_groups`.
- [ ] If the current occurrence/date has zero explicit or generated occurrence groups, `Adicionar perfil à data` is disabled and nearby helper copy states that a group must be created before adding profiles to the date.
- [ ] If the current occurrence/date has at least one group, `Adicionar perfil à data` requires a target group selector before the profile can be added.
- [ ] The group selector is required. Attempting to submit without a group is blocked by validation and no membership is added.
- [ ] Adding a profile through this sheet inserts the Account Profile id into the selected occurrence group exactly once and updates/materializes the occurrence `event_parties` flat relation from the group union.
- [ ] This path must use the same shared group/member mutation helper as the main occurrence group editor; a separate legacy adder implementation is not acceptable.
- [ ] After saving and reopening the event/date, the added profile appears in the selected occurrence group and is available to programming-item linking through `Vincular perfil da data`.

## Automated Test Matrix
### Laravel Backend
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `BE-EVG-01` | Create event with one group containing mixed Account Profile types. | Group is persisted; `event_parties` is materialized; public projection shows one custom tab. | Events feature test. |
| `BE-EVG-02` | Create event with multiple groups and explicit order. | Public projection preserves group order and member order. | Events feature test. |
| `BE-EVG-03` | Create/update event with legacy `event_parties` and no groups. | Payload accepted; public projection falls back to type-plural tabs. | Compatibility feature test. |
| `BE-EVG-04` | Submit group member not present in event-level `event_parties`. | Backend returns validation error. | Write-rule feature test. |
| `BE-EVG-05` | Submit event-level `event_parties` member missing from explicit groups. | Backend returns validation error for new grouped payload. | Write-rule feature test. |
| `BE-EVG-06` | Add a profile to an event group on update. | `event_parties` includes the profile exactly once. | Update feature test. |
| `BE-EVG-07` | Remove a profile from all event groups on update. | `event_parties` removes the profile or update is rejected if protected by dependent data. | Update feature test. |
| `BE-EVG-08` | Move profile from one event group to another. | No duplicate `event_parties`; public projection shows only the new group. | Update feature test. |
| `BE-EVG-09` | Rename an event group. | Membership unchanged; public tab label changes. | Update/projection test. |
| `BE-EVG-10` | Reorder event groups. | Membership unchanged; public tab order changes. | Update/projection test. |
| `BE-EVG-11` | Remove group that still has members. | Behavior follows `DP-EVG-05`; no silent unintended unlink. | Validation/update test. |
| `BE-EVG-12` | Create occurrence-owned group. | Occurrence `event_parties` is materialized locally to that occurrence. | Occurrence feature test. |
| `BE-EVG-13` | Public detail payload for a selected occurrence includes enough occurrence metadata for the Flutter UI to aggregate all occurrence groups. | `occurrences[]` exposes occurrence `profile_groups` and linked profile projections for all dates while the root selected occurrence remains scoped. | Public detail feature test. |
| `BE-EVG-14` | Another occurrence has different groups. | API root selected-occurrence `profile_groups` may remain scoped, but `occurrences[]` still exposes the other occurrence groups for aggregate public tabs. | Public detail feature test. |
| `BE-EVG-15` | Programming references a grouped occurrence profile. | Programming remains valid. | Event CRUD/programming test. |
| `BE-EVG-16` | Programming references profile removed from occurrence link set. | Backend rejects or repairs according to existing programming invariant. | Event CRUD/programming test. |
| `BE-EVG-17` | Existing `linked_account_profiles[]` and `own_linked_account_profiles[]` projections are requested. | Compatibility arrays remain populated for existing consumers. | Public projection test. |
| `BE-EVG-18` | Cross-tenant or deleted Account Profile appears in a group. | Backend rejects. | Tenant boundary validation test. |
| `BE-EVG-19` | Occurrences have legacy occurrence-owned `event_parties` and no occurrence groups. | Public detail provides enough linked profiles for Flutter aggregate fallback tabs by type plural label. | Compatibility feature test. |
| `BE-EVG-20` | Event has explicit groups and an occurrence has legacy occurrence-owned `event_parties`. | Public payload supports event custom tabs plus aggregate fallback coverage without selected-occurrence tab swapping. | Public detail feature test. |
| `BE-EVG-21` | Event has legacy event-level `event_parties` and an occurrence has explicit groups. | Public payload supports aggregate event fallback plus occurrence custom groups without selected-occurrence tab swapping. | Public detail feature test. |
| `BE-EVG-22` | Legacy fallback has potential empty type buckets. | Empty fallback tabs are not projected. | Public projection test. |
| `BE-EVG-23` | Legacy fallback data is returned in varying database order. | Public fallback tab/member order is deterministic and stable. | Projection ordering test. |
| `BE-EVG-24` | Event explicit groups and selected occurrence legacy fallback contain overlapping Account Profiles. | Explicit earlier event groups keep the profile; fallback emits only unassigned profiles and empty fallback groups are suppressed. | Public detail regression test. |
| `BE-EVG-25` | Occurrence/date add-profile mutation submits a target occurrence group. | Profile is persisted in the selected occurrence group and `event_parties` is derived/materialized without divergence. | Events write-rule feature test. |
| `BE-EVG-26` | Occurrence/date add-profile mutation omits target group while groups exist. | Backend rejects the mutation; no legacy-only profile link is created. | Events validation feature test. |
| `BE-EVG-27` | Occurrence/date add-profile mutation targets an occurrence/date with no groups. | Backend rejects or requires group creation first; no legacy-only profile link is created. | Events validation feature test. |

### Flutter Domain, DTO, Controller
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `FL-EVG-01` | Decode event `profile_groups`. | Domain model preserves ids, labels, order, and member ids. | DTO/domain unit test. |
| `FL-EVG-02` | Decode occurrence `profile_groups`. | Occurrence model preserves local groups separately from event groups. | DTO/domain unit test. |
| `FL-EVG-03` | Encode new grouped event form state. | Payload derives flat related-profile ids from group members. | Encoder/controller test. |
| `FL-EVG-04` | Attempt divergent local state. | Controller normalizes or prevents submit; no divergent payload can be sent. | Controller test. |
| `FL-EVG-05` | Add, rename, reorder, and delete groups. | Shared group state updates deterministically. | Shared editor/controller test. |
| `FL-EVG-06` | Add, remove, and move profile members between groups. | Derived ids update exactly once and in deterministic order. | Shared editor/controller test. |
| `FL-EVG-07` | Hydrate legacy event with flat related profiles and no groups. | Editable fallback groups are built by type plural labels. | Form-state test. |
| `FL-EVG-08` | Hydrate event with explicit groups. | Custom groups are preserved; type fallback is not used. | Form-state test. |
| `FL-EVG-09` | Hydrate selected occurrence with own groups. | Occurrence groups do not overwrite event-level groups. | Form-state test. |
| `FL-EVG-10` | Architecture guard scans group editor usage. | Account, Event, and Occurrence forms use the shared editor path; no duplicated event-only editor exists. | Rule/guard test or source-scan test. |
| `FL-EVG-11` | Hydrate legacy occurrence with flat occurrence-owned profiles and no groups. | Editable occurrence fallback groups are built by type plural labels without changing event-level groups. | Form-state test. |
| `FL-EVG-12` | Hydrate mixed explicit/fallback scopes. | Event explicit + occurrence fallback and event fallback + occurrence explicit both remain independently represented. | Form-state test. |
| `FL-EVG-13` | Save generated fallback groups. | Payload sends explicit `profile_groups` for the formerly legacy scope and keeps derived flat ids consistent. | Encoder/controller test. |
| `FL-EVG-14` | Public event detail applies a selected occurrence to an event with explicit/occurrence groups. | Date/programming state changes, but public profile tabs are built from the aggregate event/occurrence groups and remain stable. | Controller/widget regression test. |
| `FL-EVG-15` | Programming/date sheet has no occurrence groups. | Add-profile action is disabled and exposes visible prerequisite helper state. | Controller/widget state test. |
| `FL-EVG-16` | Programming/date sheet has occurrence groups and user adds a profile. | Target group is required and the derived payload adds the profile to that group. | Controller/encoder test. |
| `FL-EVG-17` | Programming/date sheet links an existing date profile to a programming item. | Linking remains group-agnostic and does not mutate group membership. | Controller test. |

### Flutter Tenant-Admin Widgets
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `UI-EVG-01` | Create event form opens related-profile groups section. | Group editor is present and usable. | Widget/navigation test. |
| `UI-EVG-02` | Edit event with explicit groups. | Saved labels/order/members are displayed. | Widget/navigation test. |
| `UI-EVG-03` | Edit legacy event with no explicit groups. | Fallback editable groups are displayed from linked profile types. | Widget/navigation test. |
| `UI-EVG-04` | Add group and select profiles through dropdown/search. | Selection is ergonomic for many profiles and supports filtering by type. | Widget test + browser mutation. |
| `UI-EVG-05` | Move profile between groups. | UI removes it from previous group and submit stays deterministic. | Widget test. |
| `UI-EVG-06` | Occurrence editor uses group section. | Occurrence groups are local to that occurrence. | Widget/navigation test. |
| `UI-EVG-07` | Programming editor after grouped profile changes. | Candidate list follows occurrence-linked profiles, not group labels. | Widget/navigation test. |
| `UI-EVG-08` | Edit legacy event and inspect generated groups. | Admin sees generated type-plural groups and compact admin-only generated-state indication before save. | Widget/navigation test. |
| `UI-EVG-09` | Save a legacy event after editing generated groups. | Saved form reloads with explicit groups, not generated fallback state. | Widget/navigation test + browser mutation. |
| `UI-EVG-10` | Edit mixed explicit/fallback event-occurrence data. | Only the legacy scope shows generated groups; explicit scope keeps saved labels/order. | Widget/navigation test. |
| `UI-EVG-11` | Real browser opens admin edit for a newly grouped event and a legacy no-group event. | New event shows custom group labels/counts; legacy event shows generated type-plural fallback groups. | Browser diagnostic navigation test. |
| `UI-EVG-12` | Open `Editar data` -> `Adicionar item de programação` on an occurrence/date with no groups. | `Adicionar perfil à data` is unavailable and visible helper text explains that a group must be created first. | Widget/navigation test. |
| `UI-EVG-13` | Open `Editar data` -> `Adicionar item de programação` on an occurrence/date with groups. | Adding profile requires selecting a group; no profile is added until a group is selected. | Widget/navigation test. |
| `UI-EVG-14` | Add a profile to a date through the programming sheet with group selected, save, and reopen. | Profile appears in the selected occurrence group and is available for `Vincular perfil da data`. | Browser mutation test. |

### Flutter Public Event UI
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `PUB-EVG-01` | Event has explicit group `Expositores` with mixed profile types. | Event detail shows one `Expositores` tab with all selected profiles. | Public widget/navigation test. |
| `PUB-EVG-02` | Event has explicit groups `Expositores` and `Convidados`. | Tabs render in saved order. | Public widget/navigation test. |
| `PUB-EVG-03` | Event has no explicit groups. | Type-plural fallback tabs still render. | Compatibility widget test. |
| `PUB-EVG-04` | Any occurrence has own group `Artistas`. | The event detail aggregate tab set includes `Artistas` regardless of which occurrence is currently selected. | Public widget/navigation test. |
| `PUB-EVG-05` | Switch selected occurrence. | Programming/date/route change, but the aggregate profile tabs/cards remain stable. | Public navigation test. |
| `PUB-EVG-06` | Profile card has image and navigation route. | Card renders image/identity and navigates to Account Profile detail. | Public widget/navigation test. |
| `PUB-EVG-07` | Event detail bottom scroll with grouped tabs. | No infinite loading and no broken final UI state. | Browser/ADB navigation test. |
| `PUB-EVG-08` | Deep navigation from event group profile and back. | Route scope/controller state returns to the correct event detail. | ADB/web navigation regression test. |
| `PUB-EVG-09` | Event has explicit groups and some occurrences have no explicit groups. | Public event detail keeps event custom tabs and aggregate fallback coverage without changing tabs by selected occurrence. | Public widget/navigation test. |
| `PUB-EVG-10` | Event has no explicit groups and one or more occurrences have explicit groups. | Public event detail shows the aggregate occurrence custom tabs, independent of selected occurrence. | Public widget/navigation test. |
| `PUB-EVG-11` | Legacy fallback public event detail renders. | No public copy mentions legacy, generated groups, repair, or fallback. | Public widget/navigation test. |
| `PUB-EVG-12` | Legacy fallback contains multiple types and missing/empty buckets. | Only non-empty type tabs render in deterministic order. | Public widget/navigation test. |
| `PUB-EVG-13` | Event explicit tabs and selected occurrence fallback overlap in member ids. | Public tabs do not duplicate or move profiles into type-plural fallback tabs after explicit grouping. | Public detail regression test. |
| `PUB-EVG-14` | Real browser validates new explicit groups, legacy no-group fallback, and historical invalid profile-group data. | Custom labels differ from type plural labels and render as saved; legacy fallback uses type plural labels; invalid group-only members do not render publicly. | Browser diagnostic navigation test. |
| `PUB-EVG-15` | Real browser validates an event with multiple occurrences and different occurrence-owned groups. | Both occurrence group tabs/members render as one aggregate event tab set; switching occurrences changes programming/date but not profile tabs. | Browser diagnostic navigation test. |

## Manual Validation Matrix
| ID | Surface | Steps | Expected Result |
| --- | --- | --- | --- |
| `MAN-EVG-01` | Tenant Admin | Create an event with group `Expositores` containing at least two Account Profiles of different types. | Save succeeds; public event shows one `Expositores` tab with both profiles. |
| `MAN-EVG-02` | Tenant Admin | Add group `Convidados`, move one profile from `Expositores` to `Convidados`, save. | Public event shows both tabs, profile appears only in `Convidados`. |
| `MAN-EVG-03` | Tenant Admin | Rename `Convidados` to `Participantes`, save. | Public event tab label changes to `Participantes`; memberships stay unchanged. |
| `MAN-EVG-04` | Tenant Admin | Reorder `Participantes` before `Expositores`, save. | Public event tab order changes exactly as saved. |
| `MAN-EVG-05` | Tenant Admin | Remove a profile from all event groups and save. | Profile disappears from public event related-profile tabs and compatibility lists stay consistent. |
| `MAN-EVG-06` | Tenant Admin | Open/edit a legacy event with linked profiles but no explicit groups. | Admin sees deterministic fallback groups by Account Profile type plural label. |
| `MAN-EVG-07` | Tenant Admin | Save the legacy event after converting/confirming groups. | Public event switches to explicit saved group labels. |
| `MAN-EVG-08` | Tenant Admin | Add an occurrence-owned group and profiles to occurrence A. | Public event includes that group in the aggregate event tab set. |
| `MAN-EVG-09` | Tenant Admin/Public | Add different occurrence-owned group to occurrence B, then switch between occurrences. | Public profile tabs keep both occurrence groups visible; only programming/date changes with the selected occurrence. |
| `MAN-EVG-10` | Tenant Admin/Public | Create a programming item that references an occurrence-linked grouped profile. | Programming renders and remains valid. |
| `MAN-EVG-11` | Tenant Admin | Try to reference a profile in programming after it is no longer linked to the occurrence. | Save is rejected or repaired by the existing invariant; no broken public UI. |
| `MAN-EVG-12` | Public Web | Open event detail, navigate into a grouped profile card, then back. | Event detail returns with correct hero, tabs, and selected context. |
| `MAN-EVG-13` | Public Web | Scroll to the bottom of event detail after grouped profiles render. | No infinite loading and no disfunctional mixed screen state. |
| `MAN-EVG-14` | Mobile/ADB if applicable | Repeat public event grouped-tab navigation and back flow on device. | Controller scope stays correct; event UI remains coherent. |
| `MAN-EVG-15` | Public Web | Open a legacy event before editing it in admin. | Public event renders type-plural fallback tabs and does not show legacy/fallback warning copy. |
| `MAN-EVG-16` | Tenant Admin | Open the same legacy event in edit mode. | Admin sees generated editable groups plus compact generated-state indication before save. |
| `MAN-EVG-17` | Tenant Admin/Public | Save generated fallback groups, then reopen public event. | Public event renders the now-explicit saved group labels and ordering. |
| `MAN-EVG-18` | Tenant Admin/Public | Validate event explicit groups with occurrences that have fallback-only profiles. | Final public tabs remain aggregate event tabs; selected occurrence changes do not hide/show profile tabs. |
| `MAN-EVG-19` | Tenant Admin/Public | Validate event fallback with occurrence explicit groups. | Final public tabs include aggregate fallback/custom occurrence labels and remain stable across occurrence switches. |
| `MAN-EVG-20` | Public Web | Validate legacy fallback ordering with multiple profile types. | Tab order is stable across reloads and does not change with database/result order. |
| `MAN-EVG-21` | Tenant Admin | Open a date/occurrence with no groups, enter `Adicionar item de programação`, and inspect `Adicionar perfil à data`. | The action is unavailable and inline copy explains that a group must be created first; UX does not rely on tooltip-only feedback. |
| `MAN-EVG-22` | Tenant Admin/Public | Create a date/occurrence group, use `Adicionar perfil à data`, select the target group, save, reopen admin and public event. | The profile is stored in the selected group, available to programming links, and appears in the public tab for that group, not in a legacy type-plural tab. |

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Admin creates grouped event related profiles | Admin mutation | `shared-android-web` | Laravel + widget + Playwright mutation | `yes` | Laravel create/update tests + Flutter admin widget + browser mutation. |
| Admin edits legacy event | Admin compatibility mutation | `shared-android-web` | Laravel + widget + Playwright mutation | `yes` | Legacy hydration tests + browser save flow. |
| Admin converts generated fallback groups | Admin compatibility mutation | `shared-android-web` | Widget + Playwright mutation | `yes` | First-save conversion from generated fallback to explicit groups. |
| Admin edits occurrence-owned groups | Admin mutation | `shared-android-web` | Laravel + widget | `yes` | Occurrence update tests + form widget. |
| Public event renders custom tabs | Public visible navigation | `shared-android-web` | Flutter widget + browser read-only | `no` | Public event detail widget + Playwright read-only. |
| Public event fallback tabs still work | Public compatibility | `shared-android-web` | Laravel + Flutter widget | `no` | Backend projection + public widget fallback test. |
| Public event aggregate explicit/fallback tabs work | Public compatibility | `shared-android-web` | Laravel + Flutter widget + browser read-only | `no` | Event explicit/fallback plus occurrence explicit/fallback data aggregates into stable public tabs. |
| Public card navigation and back | Public navigation | `shared-android-web` | Web/ADB navigation | `no` | Route-scope regression evidence. |
| Programming stays occurrence-owned | Admin/public consistency | `shared-android-web` | Laravel + widget | `yes` | Programming invariant tests. |
| Programming/date sheet adds profile through occurrence group | Admin mutation | `shared-android-web` | Laravel + Flutter widget + Playwright mutation | `yes` | No-group disabled state, required group selector, grouped persistence, programming availability, public tab readback. |

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Visible Action | DTO/Decoder Path | Required Evidence |
| --- | --- | --- | --- | --- |
| Tenant-admin event CRUD grouped payload | Laravel Events write rules | Create/update grouped event related profiles | Tenant-admin event encoder -> Events request rules | Laravel + Flutter controller tests. |
| Tenant-admin occurrence grouped payload | Laravel Events write rules | Create/update occurrence related profiles | Tenant-admin occurrence encoder -> Events request rules | Laravel + Flutter controller tests. |
| Tenant-admin legacy hydration | Flutter tenant-admin event form | Display generated editable fallback groups and convert them to explicit groups on save | Events read DTO -> tenant-admin form state -> event encoder | Flutter form/controller + browser mutation tests. |
| Events public projection | Flutter tenant-public event detail | Render custom tabs/cards | Events query projection -> schedule/event detail DTO | Laravel projection + Flutter public widget tests. |
| Legacy Events public projection | Flutter tenant-public event detail | Render type-plural fallback tabs | Events query projection fallback -> schedule/event detail DTO | Compatibility tests. |
| Event/occurrence programming data | Flutter tenant-public programming UI | Render schedule/programming cards | Events projection + programming DTO | Backend invariant + widget tests. |
| Tenant-admin programming/date sheet add-profile action | Laravel Events write rules + Flutter event form | Add an Account Profile to the current occurrence/date | Shared group editor/mutation helper -> tenant-admin event encoder -> Events request rules | Laravel validation/materialization + Flutter widget/controller + browser mutation tests. |
| `linked_account_profiles[]` compatibility arrays | Hero/map/navigation/image fallback consumers | Existing consumers continue to render images/routes | Events projection compatibility arrays | Backend projection + navigation tests. |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` focused Events feature tests | Backend validation/materialization/projection changes are core to this TODO. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter=profile_groups` | `Local-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | 9 tests / 67 assertions passed; covers grouped write validation, legacy fallback, management hydration, occurrence locality, and public mixed fallback/explicit projections. Raw `full-ci-equivalent-20260601` logs were removed as process-drift cleanup; this summary report is the canonical CI-equivalent authority. |
| `laravel-app` related projection/consumer tests | Compatibility arrays and public DTO consumers must not regress. | Focused Events public projection tests, exact file/filter to be selected during implementation. | `Local-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | Public detail tests in the focused Events suite covered profile group projection, event fallback, occurrence fallback, and selected-occurrence merge behavior. Focused validation notes remain inline; raw ad hoc log bundle was removed. |
| `flutter-app` tenant-admin event domain/controller tests | New admin state must have one mutable source and deterministic derived payloads. | Focused `fvm flutter test --no-pub` for tenant-admin event form state/encoder/controller files. | `Local-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | 132 focused Flutter tests passed across encoder, DTO, controller, admin form, and public event detail. Raw `full-ci-equivalent-20260601` logs were removed as process-drift cleanup. |
| `flutter-app` PTODO occurrence programming group addendum | `PTODO-011` requires no-group disabled/helper UX, required group selection, grouped persistence, and hard cutoff of the legacy flat add path. | `cd flutter-app && fvm flutter test --no-pub test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` | `Local-Validated` | passed | command output, 2026-06-03 | Passed 43 tests. Includes no-group disabled copy, target group dropdown, grouped persistence through `addOccurrenceRelatedProfileToGroup`, and removal of `addOccurrenceRelatedProfile`; `rg "addOccurrenceRelatedProfile\\(" flutter-app/lib flutter-app/test -g '*.dart'` returned no matches. |
| `flutter-app` shared group editor tests | Prevents duplicated implementations and validates dropdown/search/filter behavior. | Focused `fvm flutter test --no-pub` for shared profile group editor tests. | `Local-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | Admin form tests cover grouped selector checkbox state, empty state, local search/filter, later-page candidate selection, and occurrence group mutation; browser `admin-final` passed nested Account Profile capability flow. Raw ad hoc log bundle was removed. |
| `flutter-app` public event detail tests | Final event UI is the user-visible delivery. | Focused `fvm flutter test --no-pub` for immersive/public event detail grouped tabs. | `Local-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | Public event detail widget tests passed; readonly browser navigation passed 16/16 on the final web bundle. |
| `flutter-app` analyzer | Domain/UI refactor risk. | `cd flutter-app && fvm flutter analyze --no-pub` | `Local-Validated` | passed-with-known-info | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | Only the pre-existing duplicated `constant_identifier_names` info in `assets/fonts/boora_icons_source/icomoon/boora_icons_icomoon.dart:19` remains. |
| `flutter-app` rule matrix | Architecture and lint guardrails must remain satisfied. | `cd flutter-app && bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` | `Local-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md` | Expected 58 lint codes detected; total distinct emitted 59. |
| `flutter-app` web build | Browser/manual validation must use the built version. | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` | `Web-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md`; local rebuild output on 2026-06-03 | Final rebuild used the project script and published `../web-app` lane `dev`; served `main.dart.js` sha256 `67bc49ac52694695f016a7b380d9ee6e8af29ce9482f51c395f2e7070e8fee57`. `WEB_BUILD_SHA` stayed `88227417` because this worktree has not been committed yet. |
| Web navigation mutation | Admin create/edit flow needs browser evidence. | `NAV_WEB_SHARD=admin-final NAV_WEB_WORKERS=1 PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh mutation` or a narrower approved shard/test. | `Web-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md`; authoritative rerun on 2026-06-07 via `NAV_WEB_SHARD=occurrences bash scripts/delphi/run_navigation_reconcile_validation.sh mutation` | Deterministic mutation shards now pass for both the historical package evidence and the reopened authoritative EVG scope: `admin-final` 9/9, earlier `occurrences` 2/2 package evidence, and current authoritative `occurrences` 3/3 including `admin-authored occurrence profile groups persist full chip readback and public aggregation`. Event creation remains API-seeded in this browser proof by design; the browser contract here is edit/readback/aggregation, not full create-form authoring. |
| Web navigation admin/public diagnostic | User-requested real navigation for new grouped events, legacy events without groups, inconsistent historical data, and multi-occurrence selected occurrence behavior. | `NAV_RUNTIME_DB_MUTATION_ALLOWED=1 NAV_DEPLOY_LANE=local npx playwright test --config ./playwright.config.js ../web_app_tests/event_profile_groups_runtime.diagnostic.spec.js --retries=0 --workers=1 --reporter=line` from `tools/flutter/web_app_smoke_runner`. | `Web-Validated` | passed | Local command output: `1 passed (2.1m)` on 2026-06-03 after the rebuilt bundle was served. | Creates distinct Account Profile types with plural labels that differ from custom tab labels; seeds new/legacy/inconsistent events plus a two-occurrence event with different occurrence-owned groups; validates admin edit groups/counts and public final tabs/cards on `https://guarappari.belluga.space`. The multi-occurrence public flow proves both occurrence group tabs remain visible while programming switches from `Abertura Palco Sexta` to `Abertura Palco Sabado` and back. |
| Web navigation read-only | Public event final UI needs browser evidence. | `PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` or a narrower approved public event test. | `Web-Validated` | passed | `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md`; local EVG diagnostic command above. | 16/16 readonly browser tests passed against `https://belluga.space` and `https://guarappari.belluga.space` on lane `dev`; EVG diagnostic additionally proved custom-vs-type-label tabs, legacy fallback tabs, and invalid historical suppression. |
| ADB/device navigation | Route-scope/back-flow bugs can differ on device. | Use `flutter-device-test-runner` workflow if event detail/back navigation is touched. | `Device-Validated` | pending | TBD | Not yet run for this TODO after event profile-group implementation; keep open unless an explicit waiver is approved. |

## Execution Evidence - 2026-06-02
- `TODO authority guard`: `python3 delphi-ai/tools/todo_authority_guard.py foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-event-profile-groups-canonical-consistency.md` returned `Overall outcome: go` before delivery evidence reconciliation.
- `Flutter focused`: `fvm flutter test --no-pub test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` passed `132` tests.
- `Laravel focused`: safe runner passed the original profile-group Events feature coverage with `9` tests and `67` assertions.
- `False-green correction`: manual review found that a selected occurrence legacy type fallback could still project `smoke_public` / `Perfis Smoke Públicos` even when the same profiles were already assigned to explicit event groups such as `Bandas` and `Expositores`. Added fail-first `test_public_event_detail_prefers_explicit_event_groups_over_occurrence_type_fallback_when_profiles_overlap`; it failed before the fix with extra group id `smoke_public` and passed after public merge deduplicates profile ids globally with explicit earlier groups taking precedence.
- `Laravel regression rerun`: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter='/test_(event_profile_groups|occurrence_profile_groups|public_event_detail_(keeps|merges|prefers)|management_event_hydrates)/'` passed `11` tests with `78` assertions, including update divergence rejection and explicit-event-group-over-occurrence-fallback precedence.
- `Flutter selected-occurrence false-green correction`: the EVG browser diagnostic initially proved API readback returned custom `profile_groups`, but the public Flutter page rendered type-plural fallback tabs because `ImmersiveEventDetailController` rebuilt `EventModel` for the selected occurrence without preserving `event.profileGroups`. Added fail-first controller assertion to `select occurrence uses the selected occurrence start and end pair`; it failed with `profileGroups == []`, then passed after the controller copied `profileGroups: event.profileGroups`.
- `Flutter selected-occurrence regression rerun`: `fvm flutter test --no-pub test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart` passed `11` tests after the controller fix.
- `Flutter shared editor semantic guard`: added a generic semantic label to each shared nested/profile group editor item (`Grupo <label>; <count> item(s) selecionado(s)`) so browser tests can validate hydrated groups deterministically without depending on partially visible text fields. Focused widget rerun `fvm flutter test --no-pub test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart --name "related account profile group selector"` passed `2` tests.
- `Superseded 2026-06-02 browser EVG diagnostic`: the old diagnostic expected selected-occurrence profile tabs to swap between `Palco Sexta` and `Palco Sabado`. That expectation is explicitly superseded by the 2026-06-03 product decision: public event detail keeps an aggregate profile-tab set across all occurrences; selected occurrence changes only date/programming/route.
- `Flutter analyzer`: ran full app analyzer; only known pre-existing Boora icon naming infos remain.
- `Flutter rule matrix`: passed through the project analyzer plugin matrix.
- `Web build`: final bundle was built with `scripts/build_web.sh ../web-app dev --clean-output`; browser readonly confirmed `buildSha=e29764f4`.
- `Browser readonly`: deterministic readonly web navigation passed `16/16`.
- `Browser mutation`: deterministic `occurrences` shard passed `2/2`; deterministic `admin-final` shard passed `9/9`.
- `Test-quality audit`: static audit found no `skip`, `only`, hard bypass marker, or test-support route usage. Medium heuristic findings are from existing broad Laravel/Web status-only/auth-shortcut patterns and DI harness setup; touched TODO tests include behavior assertions for payload equality, fallback projection, selector state, search/filter, later-page candidates, and public UI rendering.

## Reopened / Addendum Finding - 2026-06-03
- `Decision reversal / PTODO-010`: the earlier TODO text and diagnostic expected profile tabs to be scoped to the selected occurrence. The final accepted behavior is the simpler prior model: public event detail aggregates all tabs/accounts from all occurrences and does not recreate the tab list when the selected occurrence changes. This decision is now part of the frozen contract for this TODO.
- Implementation evidence for aggregate public tabs: Flutter public event detail now builds tabs/share/invite participant groups from `EventRelatedProfileGroups.fromAggregatedEvent`, which merges event groups plus every occurrence's groups, dedupes members, and falls back by type only if the aggregate has no explicit groups.
- Focused Flutter evidence for aggregate public tabs: `cd flutter-app && fvm flutter test --no-pub test/domain/schedule/event_related_profile_groups_test.dart test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart --name 'aggregated groups|event detail aggregates profile tabs|event detail programming selector highlights current occurrence|event detail programming occurrence tap emits one selected-occurrence update|buildInvitation|buildPublicShare'` passed `7` tests on 2026-06-03.
- Expanded Flutter evidence after aggregate-tab correction: `cd flutter-app && fvm flutter test --no-pub test/application/tenant_admin/discovery_filters/tenant_admin_discovery_filter_rule_catalog_builder_test.dart test/infrastructure/repositories/proximity_preferences_repository_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/domain/schedule/event_related_profile_groups_test.dart test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart` passed `314` tests on 2026-06-03.
- Playwright diagnostic harness update and runtime evidence: `tools/flutter/web_app_tests/event_profile_groups_runtime.diagnostic.spec.js` now requires the multi-occurrence event to show both occurrence group tabs across occurrence switches while programming content changes. `node --check` passed for the updated diagnostic and manual seed script on 2026-06-03, and the runtime diagnostic passed `1 passed (2.1m)` against `https://guarappari.belluga.space` after rebuilding/serving the web bundle.
- Reopened authoritative mutation proof resolved on 2026-06-07: `NAV_WEB_SHARD=occurrences bash scripts/delphi/run_navigation_reconcile_validation.sh mutation` passed `3/3` after two deterministic hardening fixes. First, the shared tenant-admin group chip render path now exposes stable per-chip semantics (`Perfil selecionado <displayName>`) so browser readback can validate persisted chips without depending on brittle partial text nodes. Second, the EVG Playwright assertions now prove public tab activation through rendered member cards instead of overfitting on exact intermediate activation text. The repaired authoritative shard explicitly passed `@mutation admin-authored occurrence profile groups persist full chip readback and public aggregation`.

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** the current package-wide mimic loop reopened this TODO, the reopened authoritative `occurrences` shard is now green end to end, and the scope is clean/no-reopen locally. Remaining work is package-level promotion follow-through, not further EVG implementation.
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and carry it with the rest of the v0.2.0+8 package.
- Selected-occurrence programming clarification from manual validation on 2026-06-03: an occurrence/date with no programming items must show the existing empty-state widget. That is expected behavior, not a delivery gap. The regression contract is two-sided: when the selected occurrence has programming items, switching occurrence must update the programming list; when it has none, the selected occurrence must show the empty state without changing the aggregate profile tabs. Final focused Flutter suite after hero follow-up passed `00:35 +120`; EVG runtime diagnostic passed again with `1 passed (2.7m)`, opening occurrence index `1` and returning to index `0` while preserving aggregate tabs and selected-occurrence programming behavior.
- `EVG-PROG-ADD-01`: manual validation found that `Editar data` -> `Adicionar item de programação` -> `Adicionar perfil à data` can still write occurrence/date profile membership outside the new occurrence-group format. This is a delivery gap in the existing event-profile-groups TODO, not a separate product concept.
- `2026-06-03 orchestration classification`: covered by the already approved EVG objective, because the approved contract already requires one mutable source for event/occurrence related-profile membership through `profile_groups` with derived/materialized `event_parties`. This addendum prevents a reachable admin path from bypassing that approved contract; it does not add a separate product capability.
- Implementation evidence: Flutter admin event form now disables `Adicionar perfil à data` until the occurrence/date has at least one group and a target group is selected; adding a profile calls the grouped mutation path and materializes the flat occurrence relation from group union.
- Hard cutoff evidence: removed the old Flutter controller method `addOccurrenceRelatedProfile`; `rg "addOccurrenceRelatedProfile\\(" flutter-app/lib flutter-app/test -g '*.dart'` returned no matches after removal.
- Focused test evidence: `cd flutter-app && fvm flutter test --no-pub test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed `43` tests on 2026-06-03, including legacy helper scenarios updated to create/select occurrence groups.
- Remaining validation: installable app/manual validation and the consolidated v0.2.0+8 CI-equivalent run are still required before promotion closeout.
- UX decision baseline: use disabled action plus visible inline helper copy as the primary no-group explanation. Tooltip-only feedback is rejected because this is a touch/mobile admin flow. Dialog/snackbar feedback is acceptable only as secondary defensive feedback if a blocked submit path is still reachable.

## Deterministic Consistency Guards
- [x] Add or extend backend tests proving `profile_groups` and `event_parties` cannot diverge for grouped event/occurrence payloads.
- [x] Add or extend Flutter tests proving submit payload is derived from one group state.
- [x] Add deterministic shared-editor evidence if implementation evidence shows multiple profile-group editor copies can be introduced silently.
- [ ] Add route-scope/navigation regression evidence if public grouped profile navigation opens nested Account Profile details.
- [x] Add test-quality audit before delivery closeout because this TODO exists to prevent manual discovery of gaps.
- [ ] Add deterministic guard/test evidence that occurrence/date profile-adder paths cannot persist legacy-only `event_parties` membership without corresponding occurrence `profile_groups` membership.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-EVG-01` | Account Profile remains the public identity/card render target for event related profiles. | Existing public Account Profile and event projection contracts. | A new public identity contract is needed. | `High` | Keep as assumption. |
| `A-EVG-02` | `event_parties` is still needed for backend semantics, compatibility arrays, permissions/metadata, and programming constraints. | Existing Events module uses event parties as related-profile relation. | Groups would need to replace a broader backend relation, expanding scope. | `High` | Keep as assumption. |
| `A-EVG-03` | Legacy local/test data may exist with `event_parties` and no groups. | Current v0.2.0+8 data has multiple prior seed waves. | A hard cutoff could remove fallback sooner, but only after explicit approval. | `Medium` | Keep compatibility path for this TODO. |
| `A-EVG-04` | Event and occurrence groups can use the same size limits as Account Profile nested groups unless code evidence requires stricter bounds. | Existing nested group limits and event related-profile bounds. | Add event-specific limits during implementation. | `Medium` | Validate during implementation. |
| `A-EVG-05` | Public occurrence selection has a deterministic selected occurrence/effective occurrence context. | Existing event detail and programming behavior depend on selected occurrence. | Route/controller scope fix may be required first. | `Medium` | Validate with public navigation matrix. |

## Partial Worktree Reconciliation Note
At TODO creation time, previous implementation exploration may have left Laravel/Flutter edits in the worktree. Before implementation under this TODO, the executor must reconcile those edits deliberately: either adopt and rework them under this approved contract or revert only the executor-owned partial changes. No parallel branch/process should be created for this scope; it must land on top of the current v0.2.0+8 promotion lane.

## Execution Plan
### Touched Surfaces
- `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-nested-account-profile-groups.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/**`
- `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventWriteRules.php`
- `laravel-app/packages/belluga/belluga_events/src/Models/Tenants/Event.php`
- `laravel-app/tests/Feature/Events/**`
- `flutter-app/lib/domain/tenant_admin/**`
- `flutter-app/lib/domain/schedule/**`
- `flutter-app/lib/infrastructure/dal/dao/tenant_admin/**`
- `flutter-app/lib/presentation/tenant_admin/**`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `flutter-app/test/**`

### Ordered Steps
1. Confirm pending decisions and obtain `APROVADO`.
2. Reconcile any partial worktree changes against this TODO contract.
3. Add fail-first Laravel tests for event/occurrence group validation, materialization, legacy fallback, public projection, and programming invariants.
4. Add fail-first Flutter DTO/domain/controller tests for shared group state and event/occurrence payloads.
5. Add fail-first Flutter tenant-admin widget tests for create/update, legacy hydration, dropdown search/filter, and occurrence local groups.
6. Add fail-first Flutter public event UI/navigation tests for explicit tabs, fallback tabs, mixed types, selected-occurrence programming isolation with stable aggregate tabs, card navigation, and no infinite loading.
7. Implement backend normalization, validation, materialization, and public projection.
8. Implement shared Flutter group editor usage across Account Profile, Event, and Occurrence forms.
9. Implement public event detail group rendering and compatibility fallback.
10. Run focused suites, analyzer, rule matrix, web build with the project script, browser navigation, and ADB/device tests where relevant.
11. Run TODO audit/test-quality audit and close evidence before promotion.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** Laravel Events feature tests, Flutter DTO/controller tests, Flutter admin widget tests, Flutter public event UI/navigation tests.
- **Manual validation role:** manual validation is required only after automated navigation tests cover the same behavior; it must not be the first place where these gaps are found.

## Package-First Assessment
- **Query executed:** `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "profile group"`
- **Result:** `0 package(s) found`
- **Query executed:** `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "events"`
- **Result:** `0 package(s) found`
- **Relevant packages found:** none.
- **READMEs read:** none; no matching package was returned by the deterministic package query.
- **Decision:** implement locally in the existing Events and Flutter tenant-admin/public modules.
- **Tier:** local application/module implementation.
- **Rationale:** the required behavior extends existing `belluga_events`, tenant-admin event form, shared profile-group editor, and public event detail surfaces; no proprietary package currently owns this behavior.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is an approved tactical TODO and implementation is about to start. | TODO authority, approval evidence, rule ingestion, evidence matrices, delivery gates. | Code/test changes before approval/authority guard or aggregate-only evidence. | Record approval and run `todo_authority_guard.py` before implementation. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-contract-refinement-method/SKILL.md` | The TODO was refined with behavior, DoD, matrices, assumptions, and execution plan. | Contract clarity and no missing required matrices. | Silent scope drift from event profile groups into unrelated event UI work. | Use the TODO as `WHAT`; execution remains bounded to the approved profile-group contract. |
| `/home/elton/Dev/repos/delphi-ai/skills/package-first-verification/SKILL.md` | New cross-stack feature behavior and shared helper/editor work may otherwise duplicate package-owned code. | Deterministic package query and recorded assessment. | New host-level utilities that duplicate proprietary package capability. | Package-first queries returned no matching packages; implement in existing local modules. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Flutter domain, controllers, screens/widgets, and public event UI are in scope. | DTO -> domain -> projection flow, controller-owned state, pure widgets, analyzer/rule matrix. | Presentation access to DTOs/repositories/services, controller navigation, ad hoc GetIt/global registration. | Load surface-specific Flutter rules and verify with analyzer/rule matrix. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-domain-workflow-glob/SKILL.md` | `flutter-app/lib/domain/**` is in the touched-surface list. | Domain models bridge DTOs and screens and remain transport-independent. | DTO leakage into domain or presentation. | Keep profile group shape as domain data and align module docs after implementation. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md` | `flutter-app/lib/presentation/**/controllers/**` is in the touched-surface list. | Controllers own state/orchestration and expose testable intent methods. | `BuildContext` in controllers or controller-to-controller coupling. | Event form state changes must derive flat ids from group state without widget business logic. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md` | Tenant-admin screens and public event detail screens/widgets are in scope. | Scope/subscope placement and pure UI. | New ambiguous screen placement or business state inside screens. | Reuse the shared group editor and keep public event UI rendering driven by domain/projection data. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-widget-local-state-heuristics/SKILL.md` | Shared group editor/widget state can be tempting to keep locally. | Local widget state only for isolated ephemeral UI. | Persisted/business group membership state in `setState`. | Membership, validation, and submit payload derivation belong to controller/form state. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The TODO exists because manual validation found gaps; tests must lead the delivery. | Fail-first targets, semantic assertions, compatibility and critical journey coverage. | Status-only backend tests or widget tests that only prove no exception. | Add/extend Laravel, Flutter domain/controller/widget, public UI, and navigation tests. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-orchestration-suite/SKILL.md` | Delivery requires Laravel, Flutter, web build/navigation, and possible ADB evidence. | Exact stage accounting and CI-equivalent sequencing. | Treating targeted diagnostic reruns as full CI-equivalent evidence. | Run focused suites first, then full local CI-equivalent rows before delivery. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-core-instructions-always-on/SKILL.md` | Laravel Events write rules/services/projections are in scope. | Host-user edits, method discipline, no autonomous commits. | Container-owned writes and unapproved commits. | Use the Laravel safe runner for tests and do not commit without explicit commit approval. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-project-mandate-always-on/SKILL.md` | Backend API/projection changes affect project module contracts. | Alignment with project mandate, domain entities, roadmap, and module docs. | Backend contract drift without documentation. | Load module docs and update relevant module documentation when implementation stabilizes. |

## Approval
- **Implementation approval status:** `approved`
- **Approval evidence:** user replied `APROVADO` on 2026-06-02T01:16:52-03:00 after fallback behavior and matrix were consolidated into this TODO.
- **Approval scope:** event/occurrence profile groups canonical consistency, including legacy fallback UI behavior, admin hydration/conversion, deterministic backend validation/materialization, shared Flutter group editor usage, public event grouped/fallback tabs, and the automated/manual validation matrix recorded above.
- **2026-06-03 addendum scope classification:** programming/date sheet `Adicionar perfil à data` is covered by the already approved canonical group scope because it closes a bypass in occurrence-owned group authoring and preserves the approved `profile_groups` -> derived/materialized `event_parties` model.
- **Explicit exclusions:** recursive nesting, Account Workspace membership/team permissions, group-based programming restrictions, raw Account ids as public render contract, and replacing `event_parties` as the backend relation of record.
- **Renewed approval required if:** implementation changes the storage owner beyond the approved group/`event_parties` model, removes the legacy fallback path, introduces new profile-type capability gating for events, changes programming semantics, or requires a new route/scope/subscope contract.
- **Required approval phrase:** `APROVADO`
