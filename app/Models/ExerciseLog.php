<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_key',
        'activity_name',
        'category',
        'intensity',
        'met_value',
        'weight_kg',
        'duration_minutes',
        'calories_burned',
        'entry_mode',
        'notes',
        'logged_date',
    ];

    protected $casts = [
        'met_value' => 'float',
        'weight_kg' => 'float',
        'duration_minutes' => 'integer',
        'calories_burned' => 'integer',
        'logged_date' => 'date',
    ];

    /**
     * Calculate calories burned from MET, body weight (kg), and duration (minutes).
     */
    public static function calculateCaloriesFromMet(float $met, float $weightKg, int $durationMinutes): int
    {
        return (int) round(($met * 3.5 * $weightKg / 200) * $durationMinutes);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
