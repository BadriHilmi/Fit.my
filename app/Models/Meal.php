<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $table = 'meals';

    protected $fillable = [
        'Name',
        'MealDescription',
        'Calories',
        'grade',
        'gred',
        'image',
    ];

    /**
     * Backward-compatible accessor for gred / grade
     */
    public function getGredAttribute()
    {
        return $this->attributes['grade'] ?? $this->attributes['gred'] ?? 'A';
    }

    public function setGredAttribute($value)
    {
        $this->attributes['grade'] = $value;
        $this->attributes['gred'] = $value;
    }

    public function getGradeAttribute()
    {
        return $this->attributes['grade'] ?? $this->attributes['gred'] ?? 'A';
    }

    public function setGradeAttribute($value)
    {
        $this->attributes['grade'] = $value;
        $this->attributes['gred'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
