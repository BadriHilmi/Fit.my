<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'lunch_total',
        'breakfast_total',
        'dinner_total',
        'total',
        'exercise_burned',
        'calorie_goal',
    ];

    protected $casts = [
        'date' => 'date',
        'breakfast_total' => 'integer',
        'lunch_total' => 'integer',
        'dinner_total' => 'integer',
        'total' => 'integer',
        'exercise_burned' => 'integer',
        'calorie_goal' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Automatically aggregate MealLog and ExerciseLog totals into the user's daily report row.
     */
    public static function syncForUserAndDate(User $user, ?string $date = null): ?self
    {
        $reportDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        $mealPayload = MealLog::dailyPayloadForUser($user->id, $reportDate);
        $totals = $mealPayload['totals'];

        $exerciseBurned = (int) ExerciseLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $reportDate)
            ->sum('calories_burned');

        $existing = static::query()
            ->where('user_id', $user->id)
            ->whereDate('date', $reportDate)
            ->first();

        if ($totals['food'] === 0 && $exerciseBurned === 0) {
            if ($existing) {
                $existing->delete();
            }

            return null;
        }

        $attributes = [
            'user_id' => $user->id,
            'date' => $reportDate,
            'breakfast_total' => $totals['breakfast'],
            'lunch_total' => $totals['lunch'],
            'dinner_total' => $totals['dinner'],
            'total' => $totals['food'],
            'exercise_burned' => $exerciseBurned,
            'calorie_goal' => (int) ($user->calorie_intake ?? 2000),
        ];

        if ($existing) {
            $existing->update($attributes);

            return $existing->fresh();
        }

        return static::create($attributes);
    }
}
