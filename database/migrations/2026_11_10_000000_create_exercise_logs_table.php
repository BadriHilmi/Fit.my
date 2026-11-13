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
        Schema::create('exercise_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('activity_key')->nullable();
            $table->string('activity_name');
            $table->string('category')->default('cardio');
            $table->string('intensity')->default('moderate');
            $table->decimal('met_value', 4, 1)->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->unsignedInteger('duration_minutes');
            $table->unsignedInteger('calories_burned');
            $table->string('entry_mode')->default('met');
            $table->string('notes', 255)->nullable();
            $table->date('logged_date')->index();
            $table->timestamps();

            $table->index(['user_id', 'logged_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_logs');
    }
};
