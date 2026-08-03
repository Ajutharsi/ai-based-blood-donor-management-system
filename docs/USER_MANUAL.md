# LifeLink User Manual

This manual covers all three user roles: **Donor**, **Hospital**, and **Administrator**.

---

## 1. Donor Guide

### 1.1 Registering

1. Go to the LifeLink homepage and select **Register as a Donor**.
2. Fill in your personal details (name, date of birth, gender, NIC, contact info) and health details (blood group, weight, hemoglobin, any medical conditions, and how many times you've donated before, if any).
3. Optionally upload a profile photo.
4. Submit. LifeLink's AI immediately calculates your **eligibility score** based on your age, weight, and hemoglobin level, and logs you straight into your dashboard.

### 1.2 Your Dashboard

Your dashboard shows:
- Your current **eligibility status** (Eligible / Not Eligible) and AI confidence score.
- A breakdown of the three eligibility factors (age, weight, hemoglobin) against the minimum requirements.
- Your **56-day recovery countdown** since your last donation.
- Your **donation history** — donations recorded for you by an admin/hospital appear here automatically.
- Your profile summary, with an **Edit Profile** button.

### 1.3 Editing your profile

Click **Edit Profile** on your dashboard. You can update any of your details, change your password, or upload a new profile photo. Changing your weight or hemoglobin re-runs your AI eligibility check immediately.

### 1.4 Eligibility requirements

- Age 18 or above
- Weight 50 kg or above
- Hemoglobin 12 g/dL or above
- At least 56 days since your last donation

### 1.5 Using the chatbot

Click the chat icon (bottom-right corner) at any time to ask questions like *"Am I eligible to donate?"*, *"What's my blood group?"*, or *"When can I donate again?"* — the assistant answers using your live profile data.

---

## 2. Hospital Guide

### 2.1 Logging in

Hospital accounts are currently created by the LifeLink administrator (there is no hospital self-registration yet — contact the admin team to set up your hospital's account).

### 2.2 Submitting a blood request

From your dashboard, fill in the **New Blood Request** form:
- Blood group required
- Urgency (Standard / Urgent / Critical)
- Units needed
- Ward/department and a required-by date (optional)
- Any special notes

On submission, LifeLink's AI immediately searches for eligible donors of that blood group and shows you a ranked list of the best matches, along with each donor's AI confidence score and contact options (call/email).

### 2.3 Tracking your requests

The **All Requests** page lists every request your hospital has submitted, with filters by blood group, urgency, and status. Once a request has been fulfilled, mark it as **Fulfilled** from the request list or the matched-donors page.

---

## 3. Administrator Guide

### 3.1 Logging in

Use your admin credentials at `/admin/login`.

### 3.2 Managing donors

The **Donors** page lists every registered donor, searchable by name/email and filterable by blood group, eligibility status, and district, with pagination. From a donor's detail page you can:
- View their full health profile, AI eligibility breakdown, and anomaly-detection flag.
- **Toggle eligibility** manually (overrides the AI score).
- **Record a donation** — this updates their donation history and total-donations count automatically.
- **Delete** a donor record.

### 3.3 Dashboard analytics

The admin dashboard shows system-wide donor statistics, a blood-group distribution chart, an eligibility breakdown, AI-driven **blood shortage alerts** per blood group, a **demand forecast**, and a **donor cluster analysis** (groups donors into segments like "Experienced Regular Donors" or "At-Risk Donors").

> **Note:** the shortage/forecast/cluster widgets fall back to simple rule-based estimates automatically if the AI service isn't running — the dashboard will not crash, but the figures shown will be less precise than when the full AI service is available.

---

## 4. Troubleshooting

| Issue | What's happening | What to do |
|---|---|---|
| "Please log in" redirect loop | Your session expired or you're using the wrong role's login page | Make sure you're on `/donor/login`, `/hospital/login`, or `/admin/login` as appropriate |
| Dashboard shows "—" for AI confidence | The AI service isn't reachable | Confirm the FastAPI service is running on the URL configured in `AI_API_URL` |
| Profile photo doesn't display | Storage isn't linked | Run `php artisan storage:link` on the server |
