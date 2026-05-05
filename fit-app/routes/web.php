<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalorieIntakeController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\RequestMealController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Models\Meal;

Route::get('/', function () {
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
        return view('dashboard', compact('meals'));
    })->name('dashboard');

    Route::get('/activity', function () {
        return view('ManageExercise/activity');
    })->name('activity');

    Route::get('/report', [ReportController::class, 'index'])->name('report');


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
    Route::get('/request-meal', [RequestMealController::class, 'edit'])->name('ManageMeal.request-meal');


});

//guest calculating total calories
Route::get('/calorie-intake', [CalorieIntakeController::class, 'index'])->name('calorie-intake');
Route::post('/calorie-intake/calculate', [CalorieIntakeController::class, 'calculate'])->name('calorie-intake.calculate');


Route::post('/save-report', [ReportController::class, 'store'])->name('save-report');

require __DIR__.'/auth.php';
