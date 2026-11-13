<?php

namespace Tests\Feature;

use App\Models\ExerciseLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_page_displays_exercise_module_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'weight' => 70,
            'calorie_intake' => 2150,
            'user_type' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('activity'));

        $response->assertOk();
        $response->assertSee('Exercise & Activity Log');
        $response->assertSee('MET Calculator');
    }

    public function test_user_can_log_met_calculated_workout(): void
    {
        $user = User::factory()->create([
            'weight' => 70,
            'user_type' => 'user',
        ]);

        $today = Carbon::today()->toDateString();

        $response = $this->actingAs($user)->post(route('activity.store'), [
            'entry_mode' => 'met',
            'activity_key' => 'jogging',
            'weight_kg' => 70,
            'duration_minutes' => 30,
            'logged_date' => $today,
            'notes' => 'Morning jog',
        ]);

        $response->assertRedirect(route('activity', ['date' => $today]));

        $expectedCalories = ExerciseLog::calculateCaloriesFromMet(7.0, 70, 30);

        $this->assertDatabaseHas('exercise_logs', [
            'user_id' => $user->id,
            'activity_key' => 'jogging',
            'activity_name' => 'Jogging (Easy Pace)',
            'duration_minutes' => 30,
            'calories_burned' => $expectedCalories,
            'entry_mode' => 'met',
            'notes' => 'Morning jog',
        ]);
    }

    public function test_user_can_log_manual_smartwatch_workout(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $today = Carbon::today()->toDateString();

        $response = $this->actingAs($user)->post(route('activity.store'), [
            'entry_mode' => 'manual',
            'activity_name' => 'Apple Watch Spin',
            'category' => 'cardio',
            'intensity' => 'vigorous',
            'duration_minutes' => 45,
            'calories_burned' => 410,
            'logged_date' => $today,
            'notes' => 'Zone 4 intervals',
        ]);

        $response->assertRedirect(route('activity', ['date' => $today]));

        $this->assertDatabaseHas('exercise_logs', [
            'user_id' => $user->id,
            'activity_name' => 'Apple Watch Spin',
            'duration_minutes' => 45,
            'calories_burned' => 410,
            'entry_mode' => 'manual',
        ]);
    }

    public function test_user_can_update_and_delete_own_workout_log(): void
    {
        $user = User::factory()->create(['weight' => 70, 'user_type' => 'user']);
        $log = ExerciseLog::create([
            'user_id' => $user->id,
            'activity_key' => 'walking_brisk',
            'activity_name' => 'Brisk Walking',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'met_value' => 4.3,
            'weight_kg' => 70,
            'duration_minutes' => 30,
            'calories_burned' => ExerciseLog::calculateCaloriesFromMet(4.3, 70, 30),
            'entry_mode' => 'met',
            'logged_date' => Carbon::today()->toDateString(),
        ]);

        $this->actingAs($user)
            ->patch(route('activity.update', $log), [
                'duration_minutes' => 60,
                'notes' => 'Extended walk',
            ])
            ->assertRedirect();

        $log->refresh();
        $this->assertSame(60, $log->duration_minutes);
        $this->assertSame(ExerciseLog::calculateCaloriesFromMet(4.3, 70, 60), $log->calories_burned);
        $this->assertSame('Extended walk', $log->notes);

        $this->actingAs($user)
            ->delete(route('activity.destroy', $log))
            ->assertRedirect();

        $this->assertDatabaseMissing('exercise_logs', ['id' => $log->id]);
    }

    public function test_user_cannot_modify_or_delete_another_users_workout(): void
    {
        $owner = User::factory()->create(['user_type' => 'user']);
        $other = User::factory()->create(['user_type' => 'user']);

        $log = ExerciseLog::create([
            'user_id' => $owner->id,
            'activity_name' => 'Badminton',
            'category' => 'sports',
            'intensity' => 'moderate',
            'duration_minutes' => 40,
            'calories_burned' => 280,
            'entry_mode' => 'manual',
            'logged_date' => Carbon::today()->toDateString(),
        ]);

        $this->actingAs($other)
            ->patch(route('activity.update', $log), [
                'activity_name' => 'Hacked',
                'duration_minutes' => 10,
                'calories_burned' => 10,
            ])
            ->assertForbidden();

        $this->actingAs($other)
            ->delete(route('activity.destroy', $log))
            ->assertForbidden();

        $this->assertDatabaseHas('exercise_logs', ['id' => $log->id]);
    }

    public function test_exercise_endpoints_return_live_json_state_when_requested(): void
    {
        $user = User::factory()->create([
            'weight' => 70,
            'calorie_intake' => 2000,
            'user_type' => 'user',
        ]);

        $today = Carbon::today()->toDateString();

        $storeRes = $this->actingAs($user)
            ->postJson(route('activity.store'), [
                'entry_mode' => 'met',
                'activity_key' => 'jogging',
                'weight_kg' => 70,
                'duration_minutes' => 30,
                'logged_date' => $today,
            ]);

        $expectedCalories = ExerciseLog::calculateCaloriesFromMet(7.0, 70, 30);

        $storeRes->assertOk()
            ->assertJsonPath('burnedToday', $expectedCalories)
            ->assertJsonPath('daySummary.calories', $expectedCalories)
            ->assertJsonCount(1, 'dayLogs');

        $logId = $storeRes->json('dayLogs.0.id');

        $updateRes = $this->actingAs($user)
            ->patchJson(route('activity.update', $logId), [
                'duration_minutes' => 45,
                'view_date' => $today,
            ]);

        $updatedCalories = ExerciseLog::calculateCaloriesFromMet(7.0, 70, 45);
        $updateRes->assertOk()
            ->assertJsonPath('burnedToday', $updatedCalories)
            ->assertJsonPath('dayLogs.0.duration_minutes', 45);

        $getRes = $this->actingAs($user)->getJson(route('activity', ['date' => $today]));
        $getRes->assertOk()->assertJsonPath('burnedToday', $updatedCalories);

        $deleteRes = $this->actingAs($user)
            ->deleteJson(route('activity.destroy', $logId), ['view_date' => $today]);

        $deleteRes->assertOk()
            ->assertJsonPath('burnedToday', 0)
            ->assertJsonCount(0, 'dayLogs');
    }
}
