# Dental Connect — Database

Engine: **MariaDB / InnoDB**. Source of truth: PRD §9–§11.

## Key strategy

- Internal PK: `BIGINT UNSIGNED AUTO_INCREMENT` — used for joins/FKs/indexes only.
- Public identifier: `ULID` (`CHAR(26)`, ASCII), `public_id`, unique index, exposed in
  URLs/APIs. Internal ids are never exposed as the only public identifier.
- Timestamps stored in UTC; converted to org/user timezone for display.
- No hard deletes on transactional/audit entities — lifecycle status or soft delete.
- `DECIMAL` for any future money field, never `FLOAT`/`DOUBLE`.
- No comma-separated IDs; junction tables only. No large query-critical JSON blobs.

## Table ownership (§10)

| Owner | Table | Purpose | Key indexes/constraints |
|---|---|---|---|
| IAM | users | Identity/login/status | PK id; UQ public_id; UQ email |
| IAM | roles/permissions/model_has_* | RBAC (spatie) | unique names; user/role composite |
| PAT | clinic_patients | Clinic-owned enrollment | UQ(clinic_id,user_id); UQ(clinic_id,patient_number); IDX(clinic_id,status,created_at); IDX(user_id) |
| PAT | patient_consents | Consent history | IDX(clinic_patient_id,type,created_at) |
| PAT | patient_clinic_preferences | Saved/pre-enrollment prefs | UQ(user_id,clinic_id) |
| CLN | clinics | Clinic master | UQ public_id; UQ slug; IDX(verification_status,is_active) |
| CLN | clinic_locations | Address/geo | IDX(clinic_id); IDX(region,city,area,lat,lng) |
| CLN | services / specialties | Master catalogues | UQ(normalized name/code); IDX(active,sort_order) |
| CLN | clinic_services | Clinic↔service relation | UQ(clinic_id,service_id); reverse IDX(service_id,clinic_id) |
| CLN | dentists | Practitioner profile | IDX(clinic_id,status); UQ public_id |
| CLN | clinic_hours / clinic_blackout_dates | Availability | UQ(clinic_id,day); IDX(clinic_id,date) |
| CLN | verification_submissions | Verification workflow | IDX(clinic_id,status,created_at) |
| CORE | files | Private/public file metadata | IDX(owner_type,owner_id); IDX(visibility) |
| APT | appointments | Requests/lifecycle | IDX(clinic_id,status,preferred_date); IDX(clinic_id,status,preferred_date) [work queue]; IDX(clinic_patient_id,status,preferred_date); IDX(dentist_id,preferred_date,status) |
| APT | appointment_status_history | Immutable status trail | IDX(appointment_id,created_at) |
| SUP | suppliers | Supplier master | UQ public_id; IDX(verification_status,is_active) |
| SUP | supplier_verifications | Verification workflow | IDX(supplier_id,status,created_at) |
| MKT | product_categories | Hierarchical categories | IDX(parent_id,active,sort_order) |
| MKT | products | Supplier catalogue | UQ public_id; IDX(supplier_id,status,updated_at); IDX(category_id,status,created_at) |
| MKT | product_images | Product media | IDX(product_id,sort_order) |
| MKT | rfqs | Clinic→supplier enquiry | IDX(supplier_id,status,created_at); IDX(clinic_id,status,created_at) |
| MKT | rfq_messages / rfq_status_history | RFQ conversation/status | IDX(rfq_id,created_at) |
| TRU | reviews | Clinic reviews | IDX(clinic_id,moderation_status,created_at); UQ(appointment_id,clinic_patient_id) |
| TRU | complaints | Support/dispute cases | IDX(status,priority,updated_at); IDX(requester_user_id) |
| NOT | notifications | In-app notification | IDX(user_id,read_at,created_at) |
| NOT | notification_deliveries | Provider delivery audit | IDX(notification_id); IDX(provider,status,created_at) |
| AUD | audit_logs | Privileged action/event audit, append-only | IDX(entity_type,entity_id); IDX(actor_user_id,created_at) |
| ANL | analytics_events | Minimal event stream | IDX(event_type,created_at); actor/entity indexes |
| ANL | daily_metrics | Pre-aggregated dashboards | UQ(metric_date,metric_code,scope_key) |
| CORE | system_settings / feature_flags | Runtime config | UQ key; cached aggressively |

Reserved, not created prematurely (§10.1): `payments*`, `orders*`, `shipments*`,
`financing_applications*`, `insurance_*`, `clinical_records*`, `corporate_*`,
`membership_plans*`, `ai_sessions*`.

## Hot query / index strategy (§11)

| Table | Query | Starting index |
|---|---|---|
| appointments | Clinic work queue | (clinic_id, status, preferred_date) |
| appointments | Clinic-patient appointment list | (clinic_patient_id, status, preferred_date) |
| appointments | Dentist schedule/conflict | (dentist_id, preferred_date, preferred_time, status) |
| appointments | Admin monitoring | (status, created_at), (clinic_id, created_at) |
| clinics | Public verified directory | (verification_status, is_active, primary_region_id) + unique slug/public_id |
| clinic_services | Find clinics by service | (service_id, clinic_id) + unique reverse pair |
| dentists | Clinic practitioner list | (clinic_id, status, sort_order) |
| products | Supplier product manager | (supplier_id, status, updated_at) |
| products | Marketplace category browse | (category_id, status, created_at) |
| products | Public product lookup | unique public_id; normalized search fields |
| rfqs | Supplier inbox | (supplier_id, status, created_at) |
| rfqs | Clinic sent enquiries | (clinic_id, status, created_at) |
| reviews | Clinic reviews | (clinic_id, moderation_status, created_at) |
| notifications | Unread list | (user_id, read_at, created_at) |
| audit_logs | Entity/actor history | (entity_type, entity_id), (actor_user_id, created_at) |
| complaints | Operations queue | (status, priority, updated_at) |
| clinic_patients | Clinic patient directory | (clinic_id, status, last_name, first_name) + UQ(clinic_id,user_id) |
| clinic_patients | Patient number lookup | UQ(clinic_id, patient_number) |

## Search policy (§11.1)

- Exact IDs/slugs/email/phone/codes use indexed equality.
- Directory filters use normalized FKs (location/service/specialty), never free-text
  paragraph matching.
- Leading-wildcard `LIKE '%term%'` prohibited on hot tables; prefix search (`term%`)
  only, with MariaDB FULLTEXT introduced later if volume demands it.

## Query rules (§11.2)

- No N+1: eager-load only required relations.
- `SELECT` only needed columns; never `SELECT *` on high-volume lists.
- All operational lists paginate server-side.
- Dashboard counts use `daily_metrics` / cache, never live full-table scans.
- Large exports run as background jobs producing downloadable files.
