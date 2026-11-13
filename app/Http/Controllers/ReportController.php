<?php

namespace App\Http\Controllers;

use App\Models\ExerciseLog;
use App\Models\MealLog;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $range = $request->string('range', '7d')->toString();
        $analytics = $this->buildAnalytics(Auth::user(), $range);

        return view('GenerateReport.report', $analytics);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'lunch_total' => 'required|numeric|min:0',
            'breakfast_total' => 'required|numeric|min:0',
            'dinner_total' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $reportDate = Carbon::parse($validatedData['date'])->toDateString();

        $exerciseBurned = (int) ExerciseLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $reportDate)
            ->sum('calories_burned');

        $payload = [
            'user_id' => $user->id,
            'date' => $reportDate,
            'breakfast_total' => (int) round($validatedData['breakfast_total']),
            'lunch_total' => (int) round($validatedData['lunch_total']),
            'dinner_total' => (int) round($validatedData['dinner_total']),
            'total' => (int) round($validatedData['total']),
            'exercise_burned' => $exerciseBurned,
            'calorie_goal' => (int) ($user->calorie_intake ?? 2000),
        ];

        $existing = Report::query()
            ->where('user_id', $user->id)
            ->whereDate('date', $reportDate)
            ->first();

        if ($existing) {
            $existing->update($payload);
            $report = $existing->fresh();
            $message = 'Today\'s summary updated with latest meal and exercise totals.';
        } else {
            $report = Report::create($payload);
            $message = 'Daily summary saved successfully.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'report' => $report,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function export(Request $request): StreamedResponse
    {
        $range = $request->string('range', '7d')->toString();
        $analytics = $this->buildAnalytics(Auth::user(), $range);
        $filename = sprintf('fit-my-report-%s-%s.csv', $analytics['selectedRange'], Carbon::today()->toDateString());

        return response()->streamDownload(function () use ($analytics) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Date',
                'Breakfast (kcal)',
                'Lunch (kcal)',
                'Dinner (kcal)',
                'Food Total (kcal)',
                'Workouts Count',
                'Exercise Minutes',
                'Exercise Burned (kcal)',
                'Net Calories (kcal)',
                'Daily Goal (kcal)',
                'Status',
            ]);

            foreach ($analytics['dailyRows'] as $row) {
                $status = match ($row['is_on_target']) {
                    true => 'On Target',
                    false => 'Over Goal',
                    default => 'Workout Only',
                };

                fputcsv($handle, [
                    $row['date'],
                    $row['breakfast'],
                    $row['lunch'],
                    $row['dinner'],
                    $row['food_total'],
                    $row['workouts_count'],
                    $row['exercise_minutes'],
                    $row['exercise_burned'],
                    $row['net_total'],
                    $row['goal'],
                    $status,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function buildAnalytics(User $user, string $range): array
    {
        $normalizedRange = in_array($range, ['7d', '30d', 'all'], true) ? $range : '7d';
        $fromDate = match ($normalizedRange) {
            '30d' => Carbon::today()->subDays(29),
            'all' => null,
            default => Carbon::today()->subDays(6),
        };

        $reportsQuery = Report::query()
            ->where('user_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('id');

        $exerciseQuery = ExerciseLog::query()
            ->where('user_id', $user->id)
            ->orderByDesc('logged_date')
            ->orderByDesc('id');

        $mealQuery = MealLog::query()
            ->where('user_id', $user->id)
            ->orderBy('id');

        if ($fromDate) {
            $reportsQuery->whereDate('date', '>=', $fromDate->toDateString());
            $exerciseQuery->whereDate('logged_date', '>=', $fromDate->toDateString());
            $mealQuery->whereDate('logged_date', '>=', $fromDate->toDateString());
        }

        $reportsByDate = $reportsQuery->get()->groupBy(fn (Report $report) => $report->date->toDateString());
        $exercisesByDate = $exerciseQuery->get()->groupBy(fn (ExerciseLog $log) => $log->logged_date->toDateString());
        $mealsByDate = $mealQuery->get()->groupBy(fn (MealLog $log) => $log->logged_date->toDateString());

        $allDates = $reportsByDate->keys()
            ->merge($exercisesByDate->keys())
            ->merge($mealsByDate->keys())
            ->unique()
            ->sortDesc()
            ->values();

        $defaultGoal = (int) ($user->calorie_intake ?? 2000);
        $dailyRows = $allDates->map(function (string $dateKey) use ($reportsByDate, $exercisesByDate, $mealsByDate, $defaultGoal) {
            $dayReports = $reportsByDate->get($dateKey, collect());
            $dayExercises = $exercisesByDate->get($dateKey, collect());
            $dayMeals = $mealsByDate->get($dateKey, collect());

            if ($dayMeals->isNotEmpty()) {
                $breakfast = (int) $dayMeals->where('slot', 'breakfast')->sum(fn (MealLog $m) => $m->calories * $m->quantity);
                $lunch = (int) $dayMeals->where('slot', 'lunch')->sum(fn (MealLog $m) => $m->calories * $m->quantity);
                $dinner = (int) $dayMeals->where('slot', 'dinner')->sum(fn (MealLog $m) => $m->calories * $m->quantity);
                $foodTotal = $breakfast + $lunch + $dinner;
            } else {
                $breakfast = (int) $dayReports->sum('breakfast_total');
                $lunch = (int) $dayReports->sum('lunch_total');
                $dinner = (int) $dayReports->sum('dinner_total');
                $foodTotal = (int) $dayReports->sum('total');
            }

            $hasMealReport = $dayMeals->isNotEmpty() || $foodTotal > 0;

            $liveBurned = (int) $dayExercises->sum('calories_burned');
            $savedBurned = (int) $dayReports->sum('exercise_burned');
            $exerciseBurned = $dayExercises->isNotEmpty() ? $liveBurned : $savedBurned;
            $exerciseMinutes = (int) $dayExercises->sum('duration_minutes');

            $latestReportGoal = $dayReports->pluck('calorie_goal')->filter()->first();
            $goal = (int) ($latestReportGoal ?: $defaultGoal);
            $netTotal = $foodTotal - $exerciseBurned;

            $isOnTarget = ($hasMealReport && $goal > 0) ? ($netTotal <= $goal) : null;
            $goalDelta = $isOnTarget !== null ? ($netTotal - $goal) : null;

            return [
                'date' => $dateKey,
                'date_label' => Carbon::parse($dateKey)->format('M d, Y'),
                'short_label' => Carbon::parse($dateKey)->format('M d'),
                'breakfast' => $breakfast,
                'lunch' => $lunch,
                'dinner' => $dinner,
                'food_total' => $foodTotal,
                'meal_logs' => $dayMeals,
                'meals_count' => $dayMeals->count(),
                'exercise_burned' => $exerciseBurned,
                'exercise_minutes' => $exerciseMinutes,
                'workouts_count' => $dayExercises->count(),
                'exercise_logs' => $dayExercises,
                'net_total' => $netTotal,
                'goal' => $goal,
                'has_meal_report' => $hasMealReport,
                'is_on_target' => $isOnTarget,
                'goal_delta' => $goalDelta,
            ];
        });

        $allExercises = $exercisesByDate->flatten(1);
        $mealRows = $dailyRows->where('has_meal_report', true);
        $mealDays = $mealRows->count();
        $daysTracked = $dailyRows->count();

        $foodOverall = (int) $dailyRows->sum('food_total');
        $exerciseBurnedTotal = (int) $dailyRows->sum('exercise_burned');
        $mealDaysFood = (int) $mealRows->sum('food_total');
        $mealDaysBurn = (int) $mealRows->sum('exercise_burned');
        $goalHitDays = $mealRows->where('is_on_target', true)->count();

        $totals = [
            'days' => $daysTracked,
            'meal_days' => $mealDays,
            'breakfast' => (int) $dailyRows->sum('breakfast'),
            'lunch' => (int) $dailyRows->sum('lunch'),
            'dinner' => (int) $dailyRows->sum('dinner'),
            'overall' => $foodOverall,
            'exercise_burned' => $exerciseBurnedTotal,
            'exercise_minutes' => (int) $dailyRows->sum('exercise_minutes'),
            'workouts_count' => $allExercises->count(),
            'active_days' => $dailyRows->filter(fn (array $row) => $row['exercise_burned'] > 0 || $row['exercise_minutes'] > 0)->count(),
            'net_overall' => $foodOverall - $exerciseBurnedTotal,
            'average_food' => $mealDays > 0 ? (int) round($mealDaysFood / $mealDays) : 0,
            'average_burn' => $daysTracked > 0 ? (int) round($exerciseBurnedTotal / $daysTracked) : 0,
            'average_net' => $mealDays > 0 ? (int) round(($mealDaysFood - $mealDaysBurn) / $mealDays) : 0,
            'goal_hit_days' => $goalHitDays,
            'goal_hit_rate' => $mealDays > 0 ? (int) round(($goalHitDays / $mealDays) * 100) : 0,
        ];

        $chartSlice = $dailyRows->take(14)->reverse()->values();
        $chartMax = max(
            500,
            $defaultGoal,
            (int) $chartSlice->max('food_total'),
            (int) $chartSlice->max('exercise_burned')
        );

        $chartDays = $chartSlice->map(function (array $row) use ($chartMax) {
            return [
                'date' => $row['date'],
                'label' => $row['short_label'],
                'food_total' => $row['food_total'],
                'exercise_burned' => $row['exercise_burned'],
                'net_total' => $row['net_total'],
                'food_pct' => $row['food_total'] > 0 ? max(4, (int) round(($row['food_total'] / $chartMax) * 100)) : 0,
                'burn_pct' => $row['exercise_burned'] > 0 ? max(4, (int) round(($row['exercise_burned'] / $chartMax) * 100)) : 0,
            ];
        });

        $goalLinePct = min(100, (int) round(($defaultGoal / $chartMax) * 100));

        $categoryMeta = config('exercises.categories');
        $categoryBreakdown = $this->buildCategoryBreakdown($allExercises, $exerciseBurnedTotal, $categoryMeta);

        return [
            'dailyRows' => $dailyRows,
            'totals' => $totals,
            'averageTotal' => $totals['average_food'],
            'selectedRange' => $normalizedRange,
            'defaultGoal' => $defaultGoal,
            'chartDays' => $chartDays,
            'chartMax' => $chartMax,
            'goalLinePct' => $goalLinePct,
            'categoryBreakdown' => $categoryBreakdown,
            'categories' => $categoryMeta,
            'insight' => $this->buildInsightMessage($totals, $defaultGoal),
        ];
    }

    private function buildCategoryBreakdown(Collection $exercises, int $totalBurned, array $categoryMeta): Collection
    {
        if ($exercises->isEmpty()) {
            return collect();
        }

        return $exercises
            ->groupBy('category')
            ->map(function (Collection $items, string $category) use ($totalBurned, $categoryMeta) {
                $calories = (int) $items->sum('calories_burned');
                $minutes = (int) $items->sum('duration_minutes');
                $meta = $categoryMeta[$category] ?? ['label' => ucfirst($category), 'emoji' => '🏅'];

                return [
                    'category' => $category,
                    'label' => $meta['label'],
                    'emoji' => $meta['emoji'],
                    'sessions' => $items->count(),
                    'minutes' => $minutes,
                    'calories' => $calories,
                    'percent' => $totalBurned > 0 ? (int) round(($calories / $totalBurned) * 100) : 0,
                ];
            })
            ->sortByDesc('calories')
            ->values();
    }

    private function buildInsightMessage(array $totals, int $defaultGoal): string
    {
        if ($totals['days'] === 0) {
            return 'Log meals on the Dashboard or record workouts in Exercise to unlock your net calorie trends and goal tracking.';
        }

        if ($totals['meal_days'] > 0 && $totals['workouts_count'] > 0) {
            return sprintf(
                'Across %d logged %s, %d %s burned %s kcal (%d min), lowering your net daily average from %s to %s kcal — staying within your %s kcal goal on %d of %d meal-tracked %s.',
                $totals['days'],
                $totals['days'] === 1 ? 'day' : 'days',
                $totals['workouts_count'],
                $totals['workouts_count'] === 1 ? 'workout' : 'workouts',
                number_format($totals['exercise_burned']),
                $totals['exercise_minutes'],
                number_format($totals['average_food']),
                number_format($totals['average_net']),
                number_format($defaultGoal),
                $totals['goal_hit_days'],
                $totals['meal_days'],
                $totals['meal_days'] === 1 ? 'day' : 'days'
            );
        }

        if ($totals['workouts_count'] > 0) {
            return sprintf(
                'You logged %d %s (%s kcal burned across %d min). Save a daily meal summary from the Dashboard to compare intake vs. burn.',
                $totals['workouts_count'],
                $totals['workouts_count'] === 1 ? 'workout' : 'workouts',
                number_format($totals['exercise_burned']),
                $totals['exercise_minutes']
            );
        }

        return sprintf(
            'Your meal average is %s kcal/day (%d of %d %s within your %s kcal target). Log workouts in Exercise to see how activity offsets your net intake.',
            number_format($totals['average_food']),
            $totals['goal_hit_days'],
            $totals['meal_days'],
            $totals['meal_days'] === 1 ? 'day' : 'days',
            number_format($defaultGoal)
        );
    }
}
