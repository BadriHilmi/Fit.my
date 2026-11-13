<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealLog extends Model
{
    use HasFactory;

    public const SLOTS = ['breakfast', 'lunch', 'dinner'];

    protected $fillable = [
        'user_id',
        'meal_id',
        'slot',
        'meal_name',
        'meal_description',
        'calories',
        'quantity',
        'logged_date',
    ];

    protected $casts = [
        'calories' => 'integer',
        'quantity' => 'integer',
        'logged_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function getSubtotalAttribute(): int
    {
        return $this->calories * $this->quantity;
    }

    /**
     * Build the standardized daily meal carts and calorie totals payload for a user/date.
     */
    public static function dailyPayloadForUser(int $userId, ?string $date = null): array
    {
        $targetDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        $logs = static::query()
            ->where('user_id', $userId)
            ->whereDate('logged_date', $targetDate)
            ->orderBy('id')
            ->get();

        $carts = [
            'breakfast' => [],
            'lunch' => [],
            'dinner' => [],
        ];

        $totals = [
            'breakfast' => 0,
            'lunch' => 0,
            'dinner' => 0,
            'food' => 0,
        ];

        foreach ($logs as $log) {
            if (! in_array($log->slot, self::SLOTS, true)) {
                continue;
            }

            $subtotal = $log->calories * $log->quantity;
            $carts[$log->slot][] = [
                'id' => (string) ($log->meal_id ?? $log->id),
                'log_id' => $log->id,
                'meal_id' => $log->meal_id,
                'Name' => $log->meal_name,
                'MealDescription' => (string) ($log->meal_description ?? ''),
                'Calories' => $log->calories,
                'quantity' => $log->quantity,
                'subtotal' => $subtotal,
            ];

            $totals[$log->slot] += $subtotal;
            $totals['food'] += $subtotal;
        }

        return [
            'date' => $targetDate,
            'carts' => $carts,
            'totals' => $totals,
        ];
    }
}
