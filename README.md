# Fit.my

Fit.my is a Laravel-based calorie intake and meal tracking system built to help users manage daily nutrition goals while giving admins a simple workflow for reviewing and publishing meal requests.

## Features

- User registration, login, and profile management
- Daily calorie intake calculator
- Personalized calorie goal tracking
- Meal browsing for breakfast, lunch, and dinner
- Session-based daily meal logging
- Meal request form for users
- Admin dashboard for approving or rejecting requested meals
- Daily summary save flow for future report generation

## Tech Stack

- PHP 8.1+
- Laravel 10
- Blade templates
- Tailwind CSS
- Alpine.js
- MySQL
- Vite

## Project Structure

- `fit-app/` Laravel application source
- `fit-app/app/Http/Controllers/` request handling logic
- `fit-app/resources/views/` Blade views for user and admin screens
- `fit-app/routes/web.php` application routes
- `fit-app/database/` migrations and seeders

## Getting Started

1. Clone the repository.
2. Go into the Laravel app directory:

```powershell
cd fit-app
```

3. Install PHP dependencies:

```powershell
composer install
```

4. Install frontend dependencies:

```powershell
npm install
```

5. Copy the environment file and configure it:

```powershell
Copy-Item .env.example .env
```

6. Update `.env` with your database credentials.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fit
DB_USERNAME=root
DB_PASSWORD=root
```

7. Generate the app key:

```powershell
php artisan key:generate
```

8. Run migrations:

```powershell
php artisan migrate
```

9. Start the Laravel server:

```powershell
php artisan serve
```

10. In another terminal, start frontend assets if needed:

```powershell
npm run dev
```

## Current Flow

### User

- Register an account
- Calculate calorie needs
- Browse meals
- Add meals to breakfast, lunch, and dinner
- Review daily totals on the dashboard
- Save the day summary
- Request new meals for admin review

### Admin

- Open the admin dashboard
- Review pending meal requests
- Approve meals into the shared meal library
- Reject invalid or duplicate submissions

## Report Direction

The report feature is still being shaped. The current direction is:

- Daily calorie summary
- Goal vs actual intake
- Meal-by-meal totals
- Weekly overview and trends
- History of saved daily summaries

## Notes

- This project currently uses built frontend assets for stable local rendering.
- Some older database and legacy asset files still need cleanup and normalization.
- The report module is planned for a stronger second pass after the admin/dashboard flow.

## Roadmap

- Improve database schema consistency
- Add better report pages and summaries
- Add edit/remove meal actions in the dashboard
- Improve admin moderation and filtering
- Add stronger validation and tests

## License

This project is for Fit.my development and learning purposes.
