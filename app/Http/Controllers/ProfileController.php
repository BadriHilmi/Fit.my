<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private const ACTIVITY_LEVELS = [
        '1.2' => 'Sedentary (little or no exercise)',
        '1.375' => 'Lightly active (1–3 days/week)',
        '1.55' => 'Moderately active (3–5 days/week)',
        '1.725' => 'Very active (6–7 days/week)',
        '1.9' => 'Extra active (intense training / physical job)',
    ];

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $metrics = $this->computeBodyMetrics(
            $user->weight ? (float) $user->weight : null,
            $user->height ? (float) $user->height : null,
            $user->age ? (int) $user->age : null,
            $user->gender
        );

        return view('profile.edit', [
            'user' => $user,
            'metrics' => $metrics,
            'activityLevels' => self::ACTIVITY_LEVELS,
            'workoutsCount' => $user->exerciseLogs()->count(),
            'reportsCount' => $user->reports()->count(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (array_key_exists('age', $validated) && $validated['age'] !== null) {
            $user->age = (int) $validated['age'];
        }
        if (array_key_exists('height', $validated) && $validated['height'] !== null) {
            $user->height = (int) $validated['height'];
        }
        if (array_key_exists('weight', $validated) && $validated['weight'] !== null) {
            $user->weight = (int) round((float) $validated['weight']);
        }
        if (array_key_exists('gender', $validated) && $validated['gender'] !== null) {
            $user->gender = $validated['gender'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $goalMode = $validated['goal_mode'] ?? null;
        if ($goalMode === 'auto' && $user->weight && $user->height && $user->age && $user->gender) {
            $activityLevel = (float) ($validated['activity_level'] ?? 1.55);
            $metrics = $this->computeBodyMetrics(
                (float) $user->weight,
                (float) $user->height,
                (int) $user->age,
                $user->gender,
                $activityLevel
            );
            if ($metrics['recommended_calories'] > 0) {
                $user->calorie_intake = $metrics['recommended_calories'];
            }
        } elseif ($goalMode === 'custom' && isset($validated['calorie_intake'])) {
            $user->calorie_intake = (int) $validated['calorie_intake'];
        } elseif (! empty($validated['calorie_intake'])) {
            $user->calorie_intake = (int) $validated['calorie_intake'];
        }

        if ($request->boolean('remove_image')) {
            $this->deleteStoredAvatar($user->image);
            $user->image = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredAvatar($user->image);
            $image = $request->file('image');
            $imageName = $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('images');
            File::ensureDirectoryExists($destination);
            $image->move($destination, $imageName);
            $user->image = $imageName;
        }

        $user->save();

        if ($request->expectsJson()) {
            $metrics = $this->computeBodyMetrics(
                $user->weight ? (float) $user->weight : null,
                $user->height ? (float) $user->height : null,
                $user->age ? (int) $user->age : null,
                $user->gender
            );

            return response()->json([
                'message' => 'Profile settings updated!',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'age' => $user->age,
                    'height' => $user->height,
                    'weight' => $user->weight,
                    'gender' => $user->gender,
                    'calorie_intake' => (int) ($user->calorie_intake ?? 2000),
                    'image_url' => $user->image ? asset('images/' . $user->image) : null,
                    'initials' => strtoupper(substr($user->name, 0, 2)),
                ],
                'metrics' => $metrics,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $this->deleteStoredAvatar($user->image);
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function computeBodyMetrics(?float $weight, ?float $height, ?int $age, ?string $gender, float $activityLevel = 1.55): array
    {
        $bmi = null;
        $bmiCategory = null;
        if ($weight && $height && $height > 0) {
            $bmi = round($weight / (($height / 100) ** 2), 1);
            $bmiCategory = match (true) {
                $bmi < 18.5 => 'Underweight',
                $bmi < 25.0 => 'Healthy Weight',
                $bmi < 30.0 => 'Overweight',
                default => 'Obese',
            };
        }

        $bmr = null;
        $recommendedCalories = null;
        if ($weight && $height && $age && $gender) {
            $bmr = $gender === 'male'
                ? 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age)
                : 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
            $bmr = (int) round($bmr);
            $recommendedCalories = (int) round($bmr * $activityLevel);
        }

        return [
            'bmi' => $bmi,
            'bmi_category' => $bmiCategory,
            'bmr' => $bmr,
            'recommended_calories' => $recommendedCalories,
        ];
    }

    private function deleteStoredAvatar(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = public_path('images/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
