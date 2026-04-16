<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class CalculateController extends Controller
{

public function calculateCaloriesBurned(Request $request)
{
    $validatedData = $request->validate([
        'activity' => 'required|string',
        'duration' => 'required|numeric|min:0',
        'weight' => 'required|numeric|min:0',
    ]);

    $caloriesBurned = $this->calculateCaloriesBurnedForActivity(
        $validatedData['activity'],
        $validatedData['duration'],
        $validatedData['weight']
    );

    return response()->json(['calories_burned' => $caloriesBurned]);
}

private function calculateCaloriesBurnedForActivity($activity, $duration, $weight)
{
    // Define your own calculations or use external resources for accurate calorie calculations
    // Example: Calculate calories burned based on MET (Metabolic Equivalent of Task) values
    $activityMETs = [
        'walking' => 3.5,
        'running' => 7.0,
        'cycling' => 6.0,
        // Add more activities and their corresponding MET values
    ];

    if (!isset($activityMETs[$activity])) {
        return 0; // Return 0 if the activity is not found in the MET values
    }

    $activityMET = $activityMETs[$activity];
    $caloriesBurned = $activityMET * $weight * ($duration / 60); // Assuming duration is in minutes

    return $caloriesBurned;
}
}
