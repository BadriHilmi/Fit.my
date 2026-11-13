# Fit.my

Fit.my is a Laravel-based **nutrition, exercise, and daily energy-balance tracking** web application. Users can calculate daily calorie targets, log Breakfast/Lunch/Dinner meals and physical workouts with zero page reload, watch their live net calorie progress, and review automated daily reports—while admins moderate community-submitted meals.

---

## Key Features

### 1. Meals & Intake Dashboard (`/dashboard`)
- **Searchable Meal Library:** Browse seeded and admin-approved dishes with live search, Nutrition Grade filter (`Grade A`, `Grade B`), and calorie range filter (`<350`, `350–500`, `>500` kcal).
- **Drag-and-Drop & One-Click Logging:** Drag food cards directly into **Breakfast**, **Lunch**, or **Dinner** boxes—or use `+ Breakfast`, `+ Lunch`, `+ Dinner` buttons inside expanded dish cards—with custom Lucide-style Malaysian dish icons (*Nasi Lemak Bungkus*, *Steaming Rice Bowl*, *Roti Canai + Curry Bowl*).
- **Database-Backed Daily Meal Logs (`meal_logs`):** Meals persist per user and calendar date (`logged_date`), automatically starting fresh each new day without losing past entries.
- **No-Refresh Live Updates:** Adding, incrementing/decrementing (`+`/`-`), clearing slots, or resetting the day updates via `fetch()` JSON (`window.fitApi`) with toast feedback.

### 2. Exercise & Activity Module (`/activity`)
- **Native MET Calorie-Burn Calculator:** Supports 17 activities across *Cardio*, *Strength & HIIT*, *Sports & Outdoor*, and *Mobility & Recovery* (`config/exercises.php`), pre-filled with the user's profile weight using `(MET × 3.5 × weight_kg / 200) × duration_minutes`.
- **Smartwatch / Custom Mode & Quick-Log Presets:** Log exact calories from wearables (Apple Watch, Garmin, Strava) or trigger 1-click workout presets.
- **Built-in Stopwatch Timer:** Track workout duration live and apply minutes directly into the calculator.
- **Weekly Progress & History:** Tracks active minutes against a `150 min/week` goal, renders an interactive Mon–Sun bar chart (click any bar to view or backfill a date), supports inline editing/deleting, and lists a 30-day workout history—all updating without page reload.

### 3. Live Daily Target Water Gauge
- **Animated Water-Filled Digits:** Displays the user's daily calorie target in bold SVG digits that fill dynamically with emerald green water waves based on net calories (`Food Consumed − Exercise Burned`).
- **Seamless Scroll Pinning:** Freezes smoothly below the top navigation bar while scrolling down on Dashboard and Exercise pages.
- **"FAT Mode" Easter Egg:** When net calories exceed the daily target, the digits puff and wobble in coral-rose with an over-limit alert linking to `/activity`.

### 4. Auto-Saved Reports & Analytics (`/report`)
- **Automatic Daily Sync:** Every meal or workout add, edit, or delete automatically upserts that date's summary in `reports` (`Report::syncForUserAndDate`).
- **Nightly Pre-Midnight Rollup:** Scheduled Artisan command `fit:sync-daily-reports` runs daily at `23:55` (`app/Console/Kernel.php`) to finalize any remaining daily logs before midnight.
- **Analytics & Visuals:** Includes `7d` / `30d` / `all` timeframe filters, an automated insight banner, KPI cards (*Avg Net/Day*, *Total Food Consumed* with B/L/D split, *Exercise Burned*, *Goal Consistency*), a 14-day **Food Intake vs. Exercise Burn** bar chart with goal reference line, and a **Burn by Category** breakdown.
- **Expandable Day Details & Export:** Expand any historical date row to inspect both the exact **Meals Logged** and **Workouts Logged**, export reports as CSV (`/report/export`), or print (`window.print()`).

### 5. Profile & Body Settings (`/profile`)
- **Avatar Management:** Upload or remove a profile photo with live client-side preview (also shown in the top navbar).
- **Body Metrics & Live Calculations:** Manage `age`, `gender`, `weight`, and `height` with real-time **BMI** and resting **BMR** calculation.
- **Calorie Target Modes:** Choose **Auto BMR** (calculated from body metrics + activity level) or **Custom** target with quick presets (*Fat Loss `-300`*, *Maintain*, *Lean Gain `+300`*)—saved live without page reload.
- **Account Security:** Password update and account deletion confirmation modal.

### 6. Guest Calorie Calculator & Admin Meal Moderation
- **Public Calorie Intake Estimator (`/calorie-intake`):** Guest BMR + activity-level calculator with direct **Sign In** and **Sign Up** links on both the calculator and result pages.
- **User Meal Proposals (`/request-meal`):** Users can submit new dish suggestions for review.
- **Admin Control Center (`/Admin`):** Admins review pending requests, approve dishes into the shared `meals` catalog, or delete invalid submissions.

### 7. Theme & Branding
- **Light & Dark Modes:** Soft grayish-white gradient background in light mode (`#d7dfe9 → #e4e8ee → #d2e7df`) and deep slate-emerald gradient in dark mode (`#070b14 → #0f172a → #07221f`), paired with solid pastel surface cards (`.fit-card*`) and a navbar theme toggle.
- **Brand Identity:** Custom Apple + Heartbeat Pulse vector SVG logo (`application-logo.blade.php` and `public/favicon.svg`).

---

## Tech Stack

- **Backend:** PHP 8.1+ (verified on PHP 8.4), Laravel 10 (`^10.8`), Laravel Breeze, Laravel Sanctum
- **Frontend:** Blade templates, Tailwind CSS `3.3` (`@tailwindcss/forms`), Alpine.js `3.12`, Vite `4`
- **Database:** SQLite (default local `.env`) or MySQL (`.env.example`)
- **Testing:** PHPUnit `10` (`Feature` & `Unit` suites)

---

## Project Structure

```text
FIt.my/
├── DESIGN.md                                 # Mintlify design token specifications
├── README.md                                 # Project documentation
├── app/
│   ├── Console/
│   │   ├── Commands/SyncDailyReports.php     # `fit:sync-daily-reports` nightly rollup
│   │   └── Kernel.php                        # Daily 23:55 schedule
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CalorieIntakeController.php   # Guest BMR/calorie calculator
│   │   │   ├── ExerciseController.php        # Exercise CRUD + JSON state
│   │   │   ├── MealController.php            # Meal catalog & MealLog actions
│   │   │   ├── ProfileController.php         # Profile, avatar, BMI/BMR, calorie goal
│   │   │   ├── ReportController.php          # Analytics, auto-sync, CSV export
│   │   │   └── RequestMealController.php     # User meal requests & admin moderation
│   │   └── Requests/ProfileUpdateRequest.php
│   └── Models/
│       ├── ExerciseLog.php                   # MET calculator & workout logs
│       ├── Meal.php                          # Shared meal catalog
│       ├── MealLog.php                       # Daily per-slot meal logs
│       ├── Report.php                        # Daily summaries + `syncForUserAndDate`
│       ├── RequestMeal.php                   # Pending meal proposals
│       └── User.php
├── config/
│   └── exercises.php                         # Canonical MET activity catalog & presets
├── database/
│   ├── migrations/                           # Users, meals, meal_logs, exercise_logs, reports
│   └── seeders/
│       ├── DatabaseSeeder.php                # Demo user, admin, meals, sample reports
│       └── MealSeeder.php                    # 21 seeded dishes + 3 pending requests
├── public/
│   ├── build/                                # Compiled Vite CSS/JS assets
│   └── favicon.svg                           # App SVG favicon
├── resources/
│   ├── css/app.css                           # Mintlify tokens, hairline cards, typography
│   ├── js/app.js                             # Alpine init & `window.fitApi` AJAX helper
│   └── views/
│       ├── components/                       # Lucide icon system, pill buttons, stat cards
│       ├── GenerateReport/report.blade.php
│       ├── ManageExercise/activity.blade.php
│       ├── ManageMeal/request-meal.blade.php
│       ├── meals/index.blade.php
│       ├── profile/
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── auth.php
│   └── web.php
└── tests/Feature/
    ├── ExerciseLogTest.php
    ├── LandingPageTest.php
    ├── ProfileTest.php
    └── ReportAnalyticsTest.php
```

---

## Database Schema Overview

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `users` | Accounts, roles & body metrics | `name`, `email`, `age`, `height`, `weight`, `gender`, `calorie_intake`, `image`, `user_type` |
| `meals` | Shared food catalog | `Name`, `MealDescription`, `Calories`, `grade`, `image` |
| `request_meals` | Pending dish submissions | `Name`, `MealDescription`, `Calories`, `grade` |
| `meal_logs` | Daily user meal entries | `user_id`, `meal_id`, `slot`, `meal_name`, `calories`, `quantity`, `logged_date` |
| `exercise_logs` | Daily user workouts | `user_id`, `activity_key`, `activity_name`, `category`, `intensity`, `met_value`, `duration_minutes`, `calories_burned`, `entry_mode`, `logged_date` |
| `reports` | Daily intake vs. burn summaries | `user_id`, `date`, `breakfast_total`, `lunch_total`, `dinner_total`, `total`, `exercise_burned`, `calorie_goal` |

---

## Key Routes

| Access | Method & Path | Purpose |
| :--- | :--- | :--- |
| Public | `GET /` | Landing page |
| Public | `GET /calorie-intake`, `POST /calorie-intake/calculate` | Guest calorie calculator & result |
| User | `GET /dashboard` | Meals & intake dashboard |
| User | `GET /add-to-cart/{id}`, `/add-to-cart1/{id}`, `/add-to-cart2/{id}` | Add dish to Breakfast / Lunch / Dinner |
| User | `GET /remove-from-cart/{slot}/{id}`, `/clear-cart/{slot?}` | Update/remove meal logs |
| User | `GET /activity`, `POST /activity`, `PATCH /activity/{exerciseLog}`, `DELETE /activity/{exerciseLog}` | Exercise logging & MET calculator |
| User | `GET /report`, `GET /report/export`, `POST /save-report` | Reports, CSV export & manual sync |
| Auth | `GET /request-meal`, `POST /meals` | Submit meal request |
| Auth | `GET /profile`, `PATCH /profile`, `DELETE /profile` | Profile, body metrics, password & deletion |
| Admin | `GET /Admin`, `/Admin/dashboard`, `/add-meal/{id}`, `/delete-requested-meal/{id}` | Moderate pending meal requests |

---

## Local Setup

1. **Install dependencies:**
   ```powershell
   composer install
   npm install
   ```

2. **Configure environment:**
   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```
   - For **SQLite** (quick local setup): set `DB_CONNECTION=sqlite` in `.env` and ensure `database/database.sqlite` exists.
   - For **MySQL**: configure `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`.

3. **Run migrations & seed demo data:**
   ```powershell
   php artisan migrate --seed
   ```

4. **Build frontend assets:**
   ```powershell
   npm run build
   ```
   *(Or run `npm run dev` during active UI development.)*

5. **Start the development server:**
   ```powershell
   php artisan serve
   ```
   *(If running PHP 8.4+, you can suppress Laravel 10 vendor deprecation notices with `php -d "error_reporting=E_ALL & ~E_DEPRECATED" artisan serve`.)*

6. **Optional — run the daily `23:55` report rollup scheduler:**
   ```powershell
   php artisan schedule:work
   # or manually backfill a date:
   php artisan fit:sync-daily-reports --date=2026-11-11
   ```

---

## Seeded Demo Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| Standard User (`Demo User`) | `user@fit.my` | `password` |
| Admin (`Admin Fit.my`) | `admin@fit.my` | `password` |

---

## Running Tests

Run tests using an in-memory SQLite database so your local `database/database.sqlite` data is preserved:

```powershell
php -d "error_reporting=E_ALL & ~E_DEPRECATED" vendor\bin\phpunit tests\Feature\LandingPageTest.php
php -d "error_reporting=E_ALL & ~E_DEPRECATED" vendor\bin\phpunit tests\Feature\ProfileTest.php
php -d "error_reporting=E_ALL & ~E_DEPRECATED" vendor\bin\phpunit tests\Feature\ExerciseLogTest.php
php -d "error_reporting=E_ALL & ~E_DEPRECATED" vendor\bin\phpunit tests\Feature\ReportAnalyticsTest.php
```
