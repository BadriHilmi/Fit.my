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
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $selectedDate = $this->resolveSelectedDate($request->query('date'), $today);

        $state = $this->buildStatePayload($user, $selectedDate);

        if ($request->expectsJson()) {
            return response()->json($state);
        }

        return view('ManageExercise.activity', array_merge($state, [
            'activities' => config('exercises.activities'),
            'categories' => config('exercises.categories'),
            'intensities' => config('exercises.intensities'),
            'presets' => config('exercises.presets'),
            'profileWeight' => $user->weight ? (float) $user->weight : null,
        ]));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $activities = config('exercises.activities');
        $categories = config('exercises.categories');
        $intensities = config('exercises.intensities');

        $baseValidated = $request->validate([
            'entry_mode' => ['required', Rule::in(['met', 'manual'])],
            'logged_date' => ['required', 'date'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:720'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $loggedDate = Carbon::parse($baseValidated['logged_date'])->toDateString();

        if ($baseValidated['entry_mode'] === 'met') {
            $metValidated = $request->validate([
                'activity_key' => ['required', 'string', Rule::in(array_keys($activities))],
                'weight_kg' => ['required', 'numeric', 'min:20', 'max:350'],
            ]);

            $activity = $activities[$metValidated['activity_key']];
            $weightKg = (float) $metValidated['weight_kg'];
            $duration = (int) $baseValidated['duration_minutes'];

            $payload = [
                'activity_key' => $metValidated['activity_key'],
                'activity_name' => $activity['name'],
                'category' => $activity['category'],
                'intensity' => $activity['intensity'],
                'met_value' => $activity['met'],
                'weight_kg' => $weightKg,
                'duration_minutes' => $duration,
                'calories_burned' => ExerciseLog::calculateCaloriesFromMet($activity['met'], $weightKg, $duration),
            ];
        } else {
            $manualValidated = $request->validate([
                'activity_name' => ['required', 'string', 'max:100'],
                'category' => ['required', 'string', Rule::in(array_keys($categories))],
                'intensity' => ['required', 'string', Rule::in(array_keys($intensities))],
                'calories_burned' => ['required', 'integer', 'min:1', 'max:10000'],
            ]);

            $payload = [
                'activity_key' => null,
                'activity_name' => trim($manualValidated['activity_name']),
                'category' => $manualValidated['category'],
                'intensity' => $manualValidated['intensity'],
                'met_value' => null,
                'weight_kg' => Auth::user()->weight ? (float) Auth::user()->weight : null,
                'duration_minutes' => (int) $baseValidated['duration_minutes'],
                'calories_burned' => (int) $manualValidated['calories_burned'],
            ];
        }

        ExerciseLog::create(array_merge($payload, [
            'user_id' => Auth::id(),
            'entry_mode' => $baseValidated['entry_mode'],
            'notes' => ! empty($baseValidated['notes']) ? trim($baseValidated['notes']) : null,
            'logged_date' => $loggedDate,
        ]));

        Report::syncForUserAndDate(Auth::user(), $loggedDate);

        $message = "Logged {$payload['activity_name']} ({$payload['calories_burned']} kcal burned).";

        return $this->mutationResponse($request, $loggedDate, $message);
    }

    public function update(Request $request, ExerciseLog $exerciseLog): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($exerciseLog);

        $validated = $request->validate([
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:720'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $exerciseLog->duration_minutes = (int) $validated['duration_minutes'];
        $exerciseLog->notes = ! empty($validated['notes']) ? trim($validated['notes']) : null;

        if ($exerciseLog->entry_mode === 'met' && $exerciseLog->met_value && $exerciseLog->weight_kg) {
            $exerciseLog->calories_burned = ExerciseLog::calculateCaloriesFromMet(
                (float) $exerciseLog->met_value,
                (float) $exerciseLog->weight_kg,
                $exerciseLog->duration_minutes
            );
        } else {
            $manualValidated = $request->validate([
                'activity_name' => ['required', 'string', 'max:100'],
                'calories_burned' => ['required', 'integer', 'min:1', 'max:10000'],
            ]);

            $exerciseLog->activity_name = trim($manualValidated['activity_name']);
            $exerciseLog->calories_burned = (int) $manualValidated['calories_burned'];
        }

        $exerciseLog->save();

        $logDate = $exerciseLog->logged_date->toDateString();
        Report::syncForUserAndDate(Auth::user(), $logDate);

        $viewDate = $this->resolveSelectedDate(
            $request->input('view_date'),
            $logDate
        );

        return $this->mutationResponse($request, $viewDate, "Updated {$exerciseLog->activity_name}.");
    }

    public function destroy(Request $request, ExerciseLog $exerciseLog): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($exerciseLog);

        $logDate = $exerciseLog->logged_date->toDateString();
        $name = $exerciseLog->activity_name;
        $exerciseLog->delete();

        Report::syncForUserAndDate(Auth::user(), $logDate);

        $viewDate = $this->resolveSelectedDate($request->input('view_date'), $logDate);

        return $this->mutationResponse($request, $viewDate, "Removed {$name} from your workout log.");
    }

    private function mutationResponse(Request $request, string $selectedDate, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json(array_merge(
                ['message' => $message],
                $this->buildStatePayload(Auth::user(), $selectedDate)
            ));
        }

        return redirect()
            ->route('activity', ['date' => $selectedDate])
            ->with('success', $message);
    }

    private function buildStatePayload(User $user, string $selectedDate): array
    {
        $today = Carbon::today()->toDateString();
        $activities = config('exercises.activities');
        $categories = config('exercises.categories');
        $weeklyGoalMinutes = (int) config('exercises.weekly_goal_minutes', 150);

        $dayLogs = ExerciseLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', $selectedDate)
            ->orderByDesc('id')
            ->get();

        $daySummary = [
            'calories' => (int) $dayLogs->sum('calories_burned'),
            'minutes' => (int) $dayLogs->sum('duration_minutes'),
            'sessions' => $dayLogs->count(),
        ];

        $burnedToday = $selectedDate === $today
            ? $daySummary['calories']
            : (int) ExerciseLog::query()
                ->where('user_id', $user->id)
                ->whereDate('logged_date', $today)
                ->sum('calories_burned');

        $minutesToday = $selectedDate === $today
            ? $daySummary['minutes']
            : (int) ExerciseLog::query()
                ->where('user_id', $user->id)
                ->whereDate('logged_date', $today)
                ->sum('duration_minutes');

        $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::today()->endOfWeek(Carbon::SUNDAY);

        $weeklyLogs = ExerciseLog::query()
            ->where('user_id', $user->id)
            ->whereBetween('logged_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get();

        $weeklyByDate = $weeklyLogs->groupBy(fn (ExerciseLog $log) => $log->logged_date->toDateString());
        $maxDailyMinutes = max(30, (int) $weeklyByDate->map->sum('duration_minutes')->max());

        $dailyBars = [];
        for ($i = 0; $i < 7; $i++) {
            $cursor = $weekStart->copy()->addDays($i);
            $dateKey = $cursor->toDateString();
            $logsForDay = $weeklyByDate->get($dateKey, collect());
            $minutes = (int) $logsForDay->sum('duration_minutes');
            $calories = (int) $logsForDay->sum('calories_burned');

            $dailyBars[] = [
                'label' => $cursor->format('D'),
                'date' => $dateKey,
                'minutes' => $minutes,
                'calories' => $calories,
                'height_percent' => $minutes > 0 ? max(12, (int) round(($minutes / $maxDailyMinutes) * 100)) : 0,
                'is_today' => $dateKey === $today,
                'is_selected' => $dateKey === $selectedDate,
            ];
        }

        $weeklyMinutes = (int) $weeklyLogs->sum('duration_minutes');
        $weeklyCalories = (int) $weeklyLogs->sum('calories_burned');
        $weeklyStats = [
            'minutes' => $weeklyMinutes,
            'calories' => $weeklyCalories,
            'goal_minutes' => $weeklyGoalMinutes,
            'progress_percent' => $weeklyGoalMinutes > 0 ? min(100, (int) round(($weeklyMinutes / $weeklyGoalMinutes) * 100)) : 0,
            'active_days' => $weeklyByDate->count(),
            'sessions' => $weeklyLogs->count(),
        ];

        $recentLogs = ExerciseLog::query()
            ->where('user_id', $user->id)
            ->whereDate('logged_date', '>=', Carbon::today()->subDays(29)->toDateString())
            ->orderByDesc('logged_date')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        $foodConsumedToday = MealLog::dailyPayloadForUser($user->id, $today)['totals']['food'];
        $dailyTarget = (int) ($user->calorie_intake ?? 2000);
        $netCaloriesToday = $foodConsumedToday - $burnedToday;

        $formatLog = fn (ExerciseLog $log) => $this->formatLog($log, $activities, $categories);

        return [
            'selectedDate' => $selectedDate,
            'selectedDateLabel' => Carbon::parse($selectedDate)->format('D, M d, Y'),
            'today' => $today,
            'todayLabel' => Carbon::parse($today)->format('M d'),
            'dayLogs' => $dayLogs->map($formatLog)->values()->all(),
            'daySummary' => $daySummary,
            'burnedToday' => $burnedToday,
            'minutesToday' => $minutesToday,
            'dailyBars' => $dailyBars,
            'weeklyStats' => $weeklyStats,
            'recentLogs' => $recentLogs->map($formatLog)->values()->all(),
            'foodConsumedToday' => $foodConsumedToday,
            'dailyTarget' => $dailyTarget,
            'netCaloriesToday' => $netCaloriesToday,
        ];
    }

    private function formatLog(ExerciseLog $log, array $activities, array $categories): array
    {
        $catMeta = $categories[$log->category] ?? ['label' => ucfirst($log->category), 'emoji' => '🏅'];
        $emoji = ($log->activity_key && isset($activities[$log->activity_key]))
            ? $activities[$log->activity_key]['emoji']
            : $catMeta['emoji'];

        return [
            'id' => $log->id,
            'activity_key' => $log->activity_key,
            'activity_name' => $log->activity_name,
            'category' => $log->category,
            'category_label' => $catMeta['label'],
            'category_emoji' => $catMeta['emoji'],
            'emoji' => $emoji,
            'intensity' => $log->intensity,
            'met_value' => $log->met_value,
            'weight_kg' => $log->weight_kg,
            'duration_minutes' => $log->duration_minutes,
            'calories_burned' => $log->calories_burned,
            'entry_mode' => $log->entry_mode,
            'notes' => $log->notes,
            'logged_date' => $log->logged_date->toDateString(),
            'logged_date_label' => $log->logged_date->format('M d, Y'),
            'update_url' => route('activity.update', $log),
            'destroy_url' => route('activity.destroy', $log),
        ];
    }

    private function authorizeOwner(ExerciseLog $exerciseLog): void
    {
        abort_unless($exerciseLog->user_id === Auth::id(), 403);
    }

    private function resolveSelectedDate(?string $candidate, string $fallback): string
    {
        if (! $candidate) {
            return $fallback;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $candidate)->toDateString();
        } catch (\Throwable) {
            return $fallback;
        }
    }
}
