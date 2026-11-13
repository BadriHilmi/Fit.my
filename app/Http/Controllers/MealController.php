<?php
namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealLog;
use App\Models\Report;
use Carbon\Carbon;
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
    public function addToCart(Request $request, $id)
    {
        return $this->addToSlot($request, 'breakfast', $id);
    }

    public function addToCart1(Request $request, $id)
    {
        return $this->addToSlot($request, 'lunch', $id);
    }

    public function addToCart2(Request $request, $id)
    {
        return $this->addToSlot($request, 'dinner', $id);
    }

    public function removeFromCart(Request $request, $slot, $id)
    {
        $slotName = $this->normalizeSlot($slot);
        $loggedDate = $this->resolveLoggedDate($request->input('date'));
        $user = Auth::user();

        $log = MealLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $loggedDate)
            ->where('slot', $slotName)
            ->where(function ($query) use ($id) {
                $query->where('id', $id)->orWhere('meal_id', $id);
            })
            ->first();

        if ($log) {
            if ($log->quantity > 1) {
                $log->decrement('quantity');
            } else {
                $log->delete();
            }

            Report::syncForUserAndDate($user, $loggedDate);
        }

        return $this->cartResponse($request, $loggedDate, 'Meal updated & auto-saved.');
    }

    public function clearCart(Request $request, $slot = null)
    {
        $loggedDate = $this->resolveLoggedDate($request->input('date'));
        $user = Auth::user();

        $query = MealLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $loggedDate);

        if ($slot !== null) {
            $query->where('slot', $this->normalizeSlot($slot));
        }

        $query->delete();

        Report::syncForUserAndDate($user, $loggedDate);

        return $this->cartResponse($request, $loggedDate, 'Meal log cleared & auto-saved.');
    }

    private function addToSlot(Request $request, string $slotName, $id)
    {
        $product = Meal::findOrFail($id);
        $loggedDate = $this->resolveLoggedDate($request->input('date'));
        $user = Auth::user();

        $existing = MealLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $loggedDate)
            ->where('slot', $slotName)
            ->where('meal_id', $product->id)
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            MealLog::create([
                'user_id' => $user->id,
                'meal_id' => $product->id,
                'slot' => $slotName,
                'meal_name' => $product->Name,
                'meal_description' => $product->MealDescription,
                'calories' => (int) $product->Calories,
                'quantity' => 1,
                'logged_date' => $loggedDate,
            ]);
        }

        Report::syncForUserAndDate($user, $loggedDate);

        return $this->cartResponse($request, $loggedDate, "Added {$product->Name} (auto-saved).");
    }

    private function cartResponse(Request $request, string $loggedDate, string $message)
    {
        if (! $request->expectsJson()) {
            return redirect()->back()->with('success', $message);
        }

        $payload = MealLog::dailyPayloadForUser(Auth::id(), $loggedDate);

        return response()->json([
            'message' => $message,
            'date' => $payload['date'],
            'autoSavedAt' => now()->format('g:i A'),
            'carts' => $payload['carts'],
            'totals' => $payload['totals'],
        ]);
    }

    private function normalizeSlot(string $slot): string
    {
        return match ($slot) {
            'lunch', 'cart1' => 'lunch',
            'dinner', 'cart2' => 'dinner',
            default => 'breakfast',
        };
    }

    private function resolveLoggedDate(?string $candidate): string
    {
        if (! $candidate) {
            return Carbon::today()->toDateString();
        }

        try {
            return Carbon::parse($candidate)->toDateString();
        } catch (\Throwable) {
            return Carbon::today()->toDateString();
        }
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
