<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMeal extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['Name', 'MealDescription', 'Calories', 'grade', 'Grade'];

    public function getGradeAttribute()
    {
        return $this->attributes['grade'] ?? $this->attributes['Grade'] ?? 'A';
    }

    public function setGradeAttribute($value)
    {
        $this->attributes['grade'] = $value;
    }
}
