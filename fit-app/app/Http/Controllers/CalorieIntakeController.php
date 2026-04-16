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
    $weight = $request->input('weight');
    $height = $request->input('height');
    $gender = $request->input('gender');
    $age = $request ->input('age');
    $activityLevel = $request->input('activity_level');

    // Calculate BMR based on gender
    if ($gender === 'male') {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } else {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }

    // Adjust BMR based on activity level
    $calorieIntake = $bmr * $activityLevel;
    $calorieIntake = round($calorieIntake,0);

    echo(round($calorieIntake,0));
    return view('calorie-result', compact('calorieIntake'));

}
}
