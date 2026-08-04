# LifeLink Demo Data & Login Credentials

This file documents the data produced by `php artisan migrate:fresh --seed` and
the login credentials for demoing/testing every module. All seeded passwords
are **`password123`** (admins/hospitals/the primary demo donor) or
**`password`** (bulk-generated donors, hospitals, and the storyline donors —
see below), matching each factory's default.

Re-running `php artisan db:seed` (without `migrate:fresh`) is safe for the
Admin/Hospital/Donor "named" records — they use `updateOrCreate` — but will
duplicate blood requests, donor responses, donations, and AI predictions if
run more than once. Use `php artisan migrate:fresh --seed` for a clean reset.

## Primary demo logins

| Role | Email | Password | Notes |
|---|---|---|---|
| Admin | `admin@gmail.com` | `password123` | role: `superadmin` |
| Admin | `reviewer@lifelink.test` | `password123` | role: `admin` |
| Admin | `support@lifelink.test` | `password123` | role: `admin` |
| Hospital | `hospital@gmail.com` | `password123` | Colombo National Hospital — verified |
| Donor | `donor@gmail.com` | `password123` | O+, Colombo, eligible, AI-confidence 85% |

## Storyline donors (`password123`)

Hand-picked to demo specific dashboard states:

| Email | Blood Group | District | Eligible | AI Confidence | Notes |
|---|---|---|---|---|---|
| `nadeesha.r@lifelink.test` | AB- | Kandy | Yes | 96.4% | rare group, high-confidence donor |
| `kasun.j@lifelink.test` | O- | Galle | Yes | 91.2% | universal donor |
| `ishara.b@lifelink.test` | B+ | Jaffna | **No** | 32.0% | demonstrates an ineligible donor |
| `tharindu.p@lifelink.test` | A+ | Kurunegala | Yes | 78.0% | flagged as an **AI anomaly** (score 82.0) |
| `chamodi.w@lifelink.test` | AB+ | Ratnapura | Yes | 87.5% | medium response-probability |

## Other seeded hospitals (`password123`)

10 named hospitals span 10 districts with a verified/pending mix (Kurunegala,
Batticaloa, and Matara are seeded **unverified** to demo the admin
verification-toggle flow), plus 6 randomly generated ones for search/filter
volume. Full list and passwords: see `database/seeders/HospitalSeeder.php`.

## Bulk-generated donors/hospitals (`password`)

166 donors total (1 primary + 5 storyline + 160 bulk) and 6 extra
factory-generated hospitals use the Faker-generated password `password`
(set once per seeder run in `DonorFactory`/`HospitalFactory`). Their emails
are random (`fake()->unique()->safeEmail()`) — look them up via
`php artisan tinker` (e.g. `Donor::where('blood_group', 'AB-')->get()`) or
the Admin → Donor/Hospital Management search pages rather than a fixed list.

## What each seeder creates

| Seeder | Produces | Approx. count |
|---|---|---|
| `AdminSeeder` | Admin users | 3 |
| `HospitalSeeder` | Hospitals across 10+ districts, mixed verification | 16 |
| `DonorSeeder` | Donors covering all 8 blood groups, varied ages/districts/eligibility/AI scores | 166 |
| `BloodRequestSeeder` | Requests: 8 weeks of backdated history (every blood group, every week) + a live pending queue | ~180 |
| `DonorResponseSeeder` | Available/Not Available responses, compatible + eligible donors only, unique per request | ~245 |
| `DonationSeeder` | Historical donations for ~55% of donors, `total_donations`/`last_donation_date` reconciled to match | ~220 |
| `AiPredictionSeeder` | Eligibility/response/anomaly logs per donor + shortage/forecast snapshots per blood group | ~425 |

Exact counts vary slightly between runs (Faker randomness) — re-run
`php artisan migrate:fresh --seed` to see the actual totals printed to the
console after each seeder.

## What the demo data supports

- **Blood compatibility & matching** — every blood group has 20+ donors
  across multiple districts, and `DonorResponseSeeder` only ever pairs a
  request with donors who are both compatible (`BloodCompatibility`) and
  eligible, so the matching algorithm and "no invalid combinations"
  requirement are both exercised.
- **AI dashboard charts** — 8 weeks of real per-blood-group request history
  drive the shortage alerts and demand-forecast chart; donor clustering has
  166 donors (well above the k-Means minimum of 4) with varied age/weight/
  hemoglobin/donations/eligibility.
- **Filtering & search** — donors/hospitals span blood group, district,
  eligibility, and verification status, so every admin filter returns both
  matches and non-matches.
- **AI Predictions audit log** — all 5 prediction types the AI service
  produces are represented: `eligibility`, `response`, and `anomaly` (logged
  today by `AiEligibilityService` on donor registration/profile updates) plus
  `shortage` and `forecast` (computed live by the admin dashboard but not
  currently persisted there — seeded here so the audit log demonstrates what
  those entries would look like), spread over the last two weeks so the log
  isn't all one timestamp.
