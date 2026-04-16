<?php

namespace App\Http\Controllers;

use App\Models\RequestMeal;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Meal; // Import the Meal model


class RequestMealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ManageMeal.request-meal');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function show()
    {
        $requestMeals = RequestMeal::latest('id')->get();

        return view('mainadmin', [
            'requestMeals' => $requestMeals,
            'pendingCount' => $requestMeals->count(),
            'mealCount' => Meal::count(),
            'averageCalories' => (int) round($requestMeals->avg('Calories') ?? 0),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

        public function store(Request $request)
        {
            $validatedData = $request->validate([
                'Name' => 'required',
                'MealDescription' => 'required',
                'Calories' => 'required|numeric',
                'grade' => 'required',
            ]);

            // Create a new meal
            $meal = new RequestMeal;
            $meal->Name = $validatedData['Name'];
            $meal->MealDescription = $validatedData['MealDescription'];
            $meal->Calories = $validatedData['Calories'];
            $meal->grade = $validatedData['grade'];
            $meal->save();

            return Redirect::back()->with('success', 'Meal requested successfully');
        }

    public function addMeal($id)
    {
        // Find the meal by ID in the request_meal table
        $requestedMeal = RequestMeal::find($id);

        // Check if the requested meal exists
        if (!$requestedMeal) {
            return redirect()->route('ManageMeal.request-meal')->with('error', 'Meal not found.');
        }

        // Create a new meal in the Meal table based on the requested meal
        Meal::create([
            'Name' => $requestedMeal->Name,
            'MealDescription' => $requestedMeal->MealDescription,
            'Calories' => $requestedMeal->Calories,
            'gred' => $requestedMeal->grade,
               
        ]);

            // Optionally, you can delete the requested meal from the request_meal table
            $requestedMeal->delete();

            return redirect()->route('Admin.dashboard')->with('success', 'Meal added successfully.');
        }

    public function deleteRequestedMeal($mealId)
    {
        // Find and delete the requested meal from the request_meal table
        $requestedMeal = RequestMeal::find($mealId);

        if (!$requestedMeal) {
            return redirect()->route('ManageMeal.request-meal')->with('error', 'Requested meal not found.');
        }

        $requestedMeal->delete();

        return redirect()->route('Admin.dashboard')->with('success', 'Requested meal deleted successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): View
    {
        return view('ManageMeal.request-meal');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestMeal $requestMeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestMeal $requestMeal)
    {
        //
    }
}
