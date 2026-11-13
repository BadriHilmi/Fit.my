<?php

namespace App\Console\Commands;

use App\Models\ExerciseLog;
use App\Models\MealLog;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fit:sync-daily-reports {--date= : Date in YYYY-MM-DD format (defaults to today)}';

    /**
     * The console command description.
     */
    protected $description = 'Aggregate daily meal logs and exercise logs into saved daily reports before midnight.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetDate = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : Carbon::today()->toDateString();

        $mealUserIds = MealLog::query()
            ->whereDate('logged_date', $targetDate)
            ->distinct()
            ->pluck('user_id');

        $exerciseUserIds = ExerciseLog::query()
            ->whereDate('logged_date', $targetDate)
            ->distinct()
            ->pluck('user_id');

        $userIds = $mealUserIds->merge($exerciseUserIds)->unique()->values();

        if ($userIds->isEmpty()) {
            $this->info("No meal or exercise logs found for {$targetDate}.");

            return self::SUCCESS;
        }

        $synced = 0;
        User::query()->whereIn('id', $userIds)->each(function (User $user) use ($targetDate, &$synced) {
            Report::syncForUserAndDate($user, $targetDate);
            $synced++;
        });

        $this->info("Synced daily reports for {$synced} user(s) on {$targetDate}.");

        return self::SUCCESS;
    }
}
