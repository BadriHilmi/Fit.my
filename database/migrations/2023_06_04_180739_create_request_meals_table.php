<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_meals', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->text('MealDescription')->nullable();
            $table->integer('Calories');
            $table->string('grade')->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_meals');
    }
};
