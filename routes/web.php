<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalorieIntakeController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\RequestMealController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Models\ExerciseLog;
use App\Models\Meal;
use App\Models\MealLog;
use Carbon\Carbon;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->user_type === 'admin'
            ? redirect()->route('Admin.dashboard')
            : redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::any('/calorie-calculator', function () {
    return view('calorie-calculator');
});





Route::any('/calculate', function () {
    return view('calculate');
});

Route::middleware(['auth', 'verified'])->group(function () {




    Route::middleware(['check.user_type:user'])->group(function () {
    Route::get('/dashboard', function () {
        $meals = Meal::all();
        $mealPayload = MealLog::dailyPayloadForUser(auth()->id());
        $todayExerciseLogs = ExerciseLog::query()
            ->where('user_id', auth()->id())
            ->whereDate('logged_date', Carbon::today()->toDateString())
            ->get();

        $burnedToday = (int) $todayExerciseLogs->sum('calories_burned');
        $activeMinutesToday = (int) $todayExerciseLogs->sum('duration_minutes');

        return view('dashboard', compact('meals', 'mealPayload', 'burnedToday', 'activeMinutesToday'));
    })->name('dashboard');

    Route::get('/activity', [ExerciseController::class, 'index'])->name('activity');
    Route::post('/activity', [ExerciseController::class, 'store'])->name('activity.store');
    Route::patch('/activity/{exerciseLog}', [ExerciseController::class, 'update'])->name('activity.update');
    Route::delete('/activity/{exerciseLog}', [ExerciseController::class, 'destroy'])->name('activity.destroy');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::get('/report/export', [ReportController::class, 'export'])->name('report.export');


    });

    Route::middleware(['check.user_type:admin'])->group(function () {
        Route::get('/Admin',[RequestMealController::class, 'show'])->name('Admin.index');

        // Admin dashboard route
        Route::get('/Admin/dashboard', [RequestMealController::class, 'show'])->name('Admin.dashboard');

        // Route to add a meal from the request_meal table to the Meal table
        Route::get('/add-meal/{id}', [RequestMealController::class, 'addMeal'])->name('add-meal');
        // Route to delete a requested meal from the request_meal table
        Route::get('/delete-requested-meal/{id}', [RequestMealController::class, 'deleteRequestedMeal'])->name('delete_request_meal');
    });



    //edit profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //request meal
    Route::get('/meals/create', [MealController::class, 'create'])->name('meals.create');
    Route::get('/meals/{meal}', [MealController::class, 'show'])->name('meals.show');
    Route::get('/meal/{id}', [MealController::class, 'show'])->name('meals.show');
    Route::get('/meals/index', [MealController::class, 'index'])->name('meals.index');
    Route::get('/meals/request',[RequestMealController::class, 'index'])->name ('ManageMeal.store');
    Route::post('/meals', [RequestMealController::class, 'store'])->name('ManageMeal.store');

    //add meal for user
    Route::get('/meals/breakfast', [MealController::class, 'breakfast'])->name('meals.breakfast');
    Route::get('/meals/lunch', [MealController::class, 'lunch'])->name('meals.lunch');
    Route::get('/meals/dinner', [MealController::class, 'dinner'])->name('meals.dinner');
    Route::get('add-to-cart/{id}', [MealController::class, 'addToCart'])->name('add_to_cart');
    Route::get('add-to-cart1/{id}', [MealController::class, 'addToCart1'])->name('add_to_cart1');
    Route::get('add-to-cart2/{id}', [MealController::class, 'addToCart2'])->name('add_to_cart2');
    Route::get('remove-from-cart/{slot}/{id}', [MealController::class, 'removeFromCart'])->name('remove_from_cart');
    Route::get('clear-cart/{slot?}', [MealController::class, 'clearCart'])->name('clear_cart');
    Route::get('/request-meal', [RequestMealController::class, 'edit'])->name('ManageMeal.request-meal');
    Route::post('/save-report', [ReportController::class, 'store'])->name('save-report');


});

//guest calculating total calories
Route::get('/calorie-intake', [CalorieIntakeController::class, 'index'])->name('calorie-intake');
Route::post('/calorie-intake/calculate', [CalorieIntakeController::class, 'calculate'])->name('calorie-intake.calculate');

require __DIR__.'/auth.php';
