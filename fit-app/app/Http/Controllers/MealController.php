<?php
namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show($id)
{
    // Retrieve the meal data based on the ID (assuming you have a Meal model)
    $meal = Meal::findOrFail($id);

    // Return the meal view and pass the meal data to it
    return view('meals.meal')->with('meal', $meal);
}
    public function index()
    {
        $meals = Meal::all();
        return view ('meals.index', compact('meals'));
    }
    public function breakfast()
    {
        $meals = Meal::all();
        return view ('meals.breakfast', compact('meals'));
    }

    public function lunch()
    {
        $meals = Meal::all();
        return view ('meals.lunch', compact('meals'));
    }
    public function dinner()
    {
        $meals = Meal::all();
        return view ('meals.dinner', compact('meals'));
    }

    public function showMeal()
    {
        return view('meal');
    }
    public function addToCart($id)
    {
        $product = Meal::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }  else {
            $cart[$id] = [
                "Name" => $product->Name,
                "MealDescription" => $product->MealDescription,
                "Calories" => $product->Calories,
                "quantity" => 1
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Meal added successfully!');
    }

    public function addToCart1($id)
    {
        $product = Meal::findOrFail($id);

        $cart = session()->get('cart1', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }  else {
            $cart[$id] = [
                "Name" => $product->Name,
                "MealDescription" => $product->MealDescription,
                "Calories" => $product->Calories,
                "quantity" => 1
            ];
        }
        session()->put('cart1', $cart);
        return redirect()->back()->with('success', 'Meal added successfully!');
    }


    public function addToCart2($id)
    {
        $product = Meal::findOrFail($id);

        $cart = session()->get('cart2', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }  else {
            $cart[$id] = [
                "Name" => $product->Name,
                "MealDescription" => $product->MealDescription,
                "Calories" => $product->Calories,
                "quantity" => 1
            ];
        }
        session()->put('cart2', $cart);
        return redirect()->back()->with('success', 'Meal added successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ManageMeal.request-meal');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $input['image'] = $imageName;
        }

        Meal::create($input);
        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.


    */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        //
    }
}
