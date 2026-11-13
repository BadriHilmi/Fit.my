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
        Schema::create('meal_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('meal_id')->nullable()->constrained('meals')->nullOnDelete();
            $table->string('slot', 20);
            $table->string('meal_name');
            $table->text('meal_description')->nullable();
            $table->unsignedInteger('calories');
            $table->unsignedInteger('quantity')->default(1);
            $table->date('logged_date')->index();
            $table->timestamps();

            $table->index(['user_id', 'logged_date', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_logs');
    }
};
