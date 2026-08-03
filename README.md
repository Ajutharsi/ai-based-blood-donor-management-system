# LifeLink — AI-Based Blood Donor Management System

An AI-powered blood donor management platform for Sri Lanka, connecting **Donors**, **Hospitals**, and **Administrators** through a Laravel web application backed by a Python/FastAPI machine-learning service.

Built for the CSE6035 Development Project module (ICBT Campus). See `docs/` (or the accompanying project documentation) for the full academic report, proposal, and AI model evaluation.

## What it does

- **Donors** register, manage a health profile (blood group, weight, hemoglobin, medical notes, profile photo), track their donation history, and get an AI-computed eligibility score.
- **Hospitals** submit blood requests by blood group/urgency, and receive an AI-ranked list of matched eligible donors.
- **Admins** manage all donor records, monitor eligibility, record real donations, and view system-wide analytics — including AI-driven blood-shortage alerts, demand forecasting, and donor clustering.
- A floating **chatbot** (TF-IDF + k-NN intent classifier) answers donor questions about eligibility, blood group, cooldown period, and donation history.

## Architecture

```
Browser (Blade views)
        │
        ▼
Laravel 12 application  ──HTTP──▶  FastAPI AI service (Python/scikit-learn)
        │                                  │
        ▼                                  ▼
   SQLite / MySQL                    Trained models (.pkl)
```

Three independent auth guards (`donor`, `hospital`, `admin`) each with their own login, session, and protected route group — see `app/Http/Middleware/*Authenticated.php`.

## Requirements

- PHP 8.2+, Composer
- Node.js (for Vite asset building)
- Python 3.10+ with `fastapi`, `uvicorn`, `pandas`, `scikit-learn`, `joblib` (for the AI service — see the separate `blood-ai-model` repository)
- SQLite (default, zero-config) or MySQL

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# Database (SQLite is the default — just create the file)
touch database/database.sqlite
php artisan migrate

# Storage (required for donor profile-photo uploads)
php artisan storage:link

# Optional: seed an admin/hospital account for local testing
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=HospitalSeeder

# Frontend assets
npm install
npm run build   # or `npm run dev` while developing

# Run the app
php artisan serve
```

**AI service** — the app degrades gracefully with rule-based fallbacks if the AI service isn't running, but for full AI-powered features, start the FastAPI service from the `blood-ai-model` project:

```bash
cd blood-ai-model
uvicorn api:app --reload --port 8001
```

Configure the URL via `AI_API_URL` in `.env` (defaults to `http://127.0.0.1:8001`).

## Testing

```bash
php artisan test
```

108 automated feature tests cover registration, authentication, guard isolation, profile management, donation history, blood-request workflows, admin management, and AI-service failure resilience across all three modules (Donor, Hospital, Admin).

## Key modules

| Module | Location |
|---|---|
| Donor auth/profile/dashboard | `app/Http/Controllers/Donor/` |
| Hospital auth/blood requests | `app/Http/Controllers/Hospital/` |
| Admin dashboard/donor management | `app/Http/Controllers/Admin/` |
| AI eligibility integration | `app/Services/AiEligibilityService.php` |
| Chatbot proxy | `app/Http/Controllers/ChatController.php` |

## License

Built on the [Laravel framework](https://laravel.com) (MIT licensed). This application and its AI components are an academic project.
