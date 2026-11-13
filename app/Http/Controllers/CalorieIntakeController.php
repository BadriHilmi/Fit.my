<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalorieIntakeController extends Controller
{
    //

    public function index()
{
    return view('calorie-intake');
}

public function calculate(Request $request)
{
    $validated = $request->validate([
        'weight' => ['required', 'numeric', 'min:20', 'max:350'],
        'height' => ['required', 'numeric', 'min:80', 'max:260'],
        'gender' => ['required', 'in:male,female'],
        'age' => ['required', 'integer', 'min:10', 'max:120'],
        'activity_level' => ['required', 'numeric', 'min:1', 'max:2.5'],
    ]);

    $weight = (float) $validated['weight'];
    $height = (float) $validated['height'];
    $gender = $validated['gender'];
    $age = (int) $validated['age'];
    $activityLevel = (float) $validated['activity_level'];

    // Calculate BMR based on gender
    if ($gender === 'male') {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } else {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }

    // Adjust BMR based on activity level
    $calorieIntake = round($bmr * $activityLevel, 0);

    if ($request->expectsJson()) {
        return response()->json([
            'calorieIntake' => (int) $calorieIntake,
            'bmr' => (int) round($bmr),
            'activity_level' => $activityLevel,
        ]);
    }

    if ($request->boolean('redirect_back')) {
        return redirect(url('/#calculator'))
            ->with('guest_calorie_result', [
                'calorieIntake' => (int) $calorieIntake,
                'bmr' => (int) round($bmr),
                'activity_level' => $activityLevel,
            ])
            ->withInput();
    }

    return view('calorie-result', compact('calorieIntake'));
}
}
