<?php

namespace Tests\Feature;

use App\Models\ExerciseLog;
use App\Models\Meal;
use App\Models\MealLog;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_reflects_todays_exercise_burn_and_net_budget(): void
    {
        $user = User::factory()->create([
            'calorie_intake' => 2000,
            'user_type' => 'user',
        ]);

        ExerciseLog::create([
            'user_id' => $user->id,
            'activity_name' => 'Brisk Walking',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'duration_minutes' => 40,
            'calories_burned' => 250,
            'entry_mode' => 'manual',
            'logged_date' => Carbon::today()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('-250');
        $response->assertSee('40 active min today');
    }

    public function test_daily_target_gauge_fills_and_triggers_fat_mode_over_limit(): void
    {
        $user = User::factory()->create([
            'calorie_intake' => 2000,
            'user_type' => 'user',
        ]);

        $today = Carbon::today()->toDateString();

        // 1. Halfway filled (1000 kcal in meal_logs)
        $log = MealLog::create([
            'user_id' => $user->id,
            'slot' => 'breakfast',
            'meal_name' => 'Meal A',
            'calories' => 1000,
            'quantity' => 1,
            'logged_date' => $today,
        ]);

        $halfResponse = $this->actingAs($user)->get(route('dashboard'));
        $halfResponse->assertOk();
        $halfResponse->assertSee('50%');
        $halfResponse->assertSee('fill-emerald-600');

        // 2. Exactly reached goal (2000 kcal)
        $log->update(['calories' => 2000]);
        $goalResponse = $this->actingAs($user)->get(route('dashboard'));
        $goalResponse->assertOk();
        $goalResponse->assertSee('Goal Reached');

        // 3. Over limit -> FAT MODE Easter egg
        $log->update(['meal_name' => 'Feast', 'calories' => 2400]);
        $fatResponse = $this->actingAs($user)->get(route('dashboard'));
        $fatResponse->assertOk();
        $fatResponse->assertSee('Over Limit');
        $fatResponse->assertSee('fit-fat-jelly');
        $fatResponse->assertSee('+400 kcal');
    }

    public function test_saving_daily_summary_upserts_same_date_and_snapshots_exercise(): void
    {
        $user = User::factory()->create([
            'calorie_intake' => 2100,
            'user_type' => 'user',
        ]);

        $today = Carbon::today()->toDateString();

        ExerciseLog::create([
            'user_id' => $user->id,
            'activity_name' => 'Jogging',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'duration_minutes' => 30,
            'calories_burned' => 320,
            'entry_mode' => 'manual',
            'logged_date' => $today,
        ]);

        $this->actingAs($user)->post(route('save-report'), [
            'date' => $today,
            'breakfast_total' => 400,
            'lunch_total' => 700,
            'dinner_total' => 600,
            'total' => 1700,
        ])->assertRedirect();

        $this->actingAs($user)->post(route('save-report'), [
            'date' => $today,
            'breakfast_total' => 450,
            'lunch_total' => 700,
            'dinner_total' => 650,
            'total' => 1800,
        ])->assertRedirect();

        $this->assertSame(1, Report::where('user_id', $user->id)->count());

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'breakfast_total' => 450,
            'lunch_total' => 700,
            'dinner_total' => 650,
            'total' => 1800,
            'exercise_burned' => 320,
            'calorie_goal' => 2100,
        ]);
    }

    public function test_report_merges_meal_summaries_and_workout_logs_and_exports_csv(): void
    {
        $user = User::factory()->create([
            'calorie_intake' => 2000,
            'user_type' => 'user',
        ]);

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        Report::create([
            'user_id' => $user->id,
            'date' => $today,
            'breakfast_total' => 500,
            'lunch_total' => 800,
            'dinner_total' => 900,
            'total' => 2200,
            'exercise_burned' => 0,
            'calorie_goal' => 2000,
        ]);

        // Logged a workout today after saving meals -> report computes net = 2200 - 350 = 1850 (On Target)
        ExerciseLog::create([
            'user_id' => $user->id,
            'activity_name' => 'Badminton',
            'category' => 'sports',
            'intensity' => 'moderate',
            'duration_minutes' => 50,
            'calories_burned' => 350,
            'entry_mode' => 'manual',
            'logged_date' => $today,
        ]);

        // Workout-only day yesterday (no meal report)
        ExerciseLog::create([
            'user_id' => $user->id,
            'activity_name' => 'Weight Training',
            'category' => 'strength',
            'intensity' => 'moderate',
            'duration_minutes' => 45,
            'calories_burned' => 260,
            'entry_mode' => 'manual',
            'logged_date' => $yesterday,
        ]);

        $reportResponse = $this->actingAs($user)->get(route('report', ['range' => '7d']));

        $reportResponse->assertOk();
        $reportResponse->assertSee('1,850 kcal');
        $reportResponse->assertSee('On Target (150 left)');
        $reportResponse->assertSee('Workout Only');
        $reportResponse->assertSee('Badminton');
        $reportResponse->assertSee('Weight Training');

        $exportResponse = $this->actingAs($user)->get(route('report.export', ['range' => '7d']));

        $exportResponse->assertOk();
        $csv = $exportResponse->streamedContent();
        $this->assertStringContainsString('Net Calories (kcal)', $csv);
        $this->assertStringContainsString("{$today},500,800,900,2200,1,50,350,1850,2000,\"On Target\"", $csv);
        $this->assertStringContainsString("{$yesterday},0,0,0,0,1,45,260,-260,2000,\"Workout Only\"", $csv);
    }

    public function test_meal_cart_and_save_report_return_json_for_live_updates(): void
    {
        $user = User::factory()->create([
            'calorie_intake' => 2000,
            'user_type' => 'user',
        ]);

        $mealA = Meal::create(['Name' => 'Oatmeal', 'Calories' => 300, 'grade' => 'A']);
        $mealB = Meal::create(['Name' => 'Chicken Rice', 'Calories' => 550, 'grade' => 'B']);

        $this->actingAs($user)
            ->getJson(route('add_to_cart', $mealA->id))
            ->assertOk()
            ->assertJsonPath('totals.breakfast', 300)
            ->assertJsonPath('totals.food', 300);

        $this->actingAs($user)
            ->getJson(route('add_to_cart1', $mealB->id))
            ->assertOk()
            ->assertJsonPath('totals.lunch', 550)
            ->assertJsonPath('totals.food', 850);

        $this->actingAs($user)
            ->getJson(route('add_to_cart2', $mealA->id))
            ->assertOk()
            ->assertJsonPath('totals.dinner', 300)
            ->assertJsonPath('totals.food', 1150);

        $this->actingAs($user)
            ->getJson(route('remove_from_cart', ['slot' => 'lunch', 'id' => $mealB->id]))
            ->assertOk()
            ->assertJsonPath('totals.lunch', 0)
            ->assertJsonPath('totals.food', 600);

        // Auto-saved directly into reports without needing manual save button
        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'breakfast_total' => 300,
            'lunch_total' => 0,
            'dinner_total' => 300,
            'total' => 600,
        ]);

        $this->actingAs($user)
            ->postJson(route('save-report'), [
                'date' => Carbon::today()->toDateString(),
                'breakfast_total' => 300,
                'lunch_total' => 0,
                'dinner_total' => 300,
                'total' => 600,
            ])
            ->assertOk()
            ->assertJsonPath('report.total', 600);

        $this->actingAs($user)
            ->getJson(route('clear_cart'))
            ->assertOk()
            ->assertJsonPath('totals.food', 0);

        $this->assertDatabaseCount('meal_logs', 0);
        $this->assertDatabaseCount('reports', 0);
    }

    public function test_sync_daily_reports_command_rolls_up_meals_and_workouts(): void
    {
        $user = User::factory()->create([
            'calorie_intake' => 2100,
            'user_type' => 'user',
        ]);

        $targetDate = Carbon::yesterday()->toDateString();

        MealLog::create([
            'user_id' => $user->id,
            'slot' => 'breakfast',
            'meal_name' => 'Oatmeal',
            'calories' => 350,
            'quantity' => 1,
            'logged_date' => $targetDate,
        ]);

        MealLog::create([
            'user_id' => $user->id,
            'slot' => 'dinner',
            'meal_name' => 'Grilled Salmon',
            'calories' => 520,
            'quantity' => 1,
            'logged_date' => $targetDate,
        ]);

        ExerciseLog::create([
            'user_id' => $user->id,
            'activity_name' => 'Running',
            'category' => 'cardio',
            'intensity' => 'vigorous',
            'duration_minutes' => 30,
            'calories_burned' => 310,
            'entry_mode' => 'manual',
            'logged_date' => $targetDate,
        ]);

        $this->artisan('fit:sync-daily-reports', ['--date' => $targetDate])
            ->assertSuccessful();

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'breakfast_total' => 350,
            'lunch_total' => 0,
            'dinner_total' => 520,
            'total' => 870,
            'exercise_burned' => 310,
            'calorie_goal' => 2100,
        ]);

        $this->actingAs($user)
            ->get(route('report', ['range' => '7d']))
            ->assertOk()
            ->assertSee('Oatmeal')
            ->assertSee('Grilled Salmon')
            ->assertSee('Running');
    }
}
