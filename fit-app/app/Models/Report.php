<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['date','lunch_total', 'breakfast_total', 'dinner_total','total'];
    use HasFactory;
    public function Meal()
    {
        return $this->hasMany(Meal::class);
    }
}
