<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $table = 'meal';

    protected $fillable = ['Name', 'MealDescription', 'Calories','gred'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }


}
