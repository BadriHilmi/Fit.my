<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-400">
                    Analytics &amp; Energy Balance
                </p>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white sm:text-3xl">
                    {{ Auth::user()->name }}'s Fitness &amp; Calorie Reports
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Compare meal intake, exercise expenditure, net calories, and daily goal consistency.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 print:hidden">
                <form method="GET" action="{{ route('report') }}" class="flex items-center gap-2">
                    <label for="range" class="text-xs font-bold text-slate-600 dark:text-slate-400">Timeframe:</label>
                    <select
                        id="range"
                        name="range"
                        onchange="this.form.submit()"
                        class="rounded-xl border-slate-200 bg-white text-xs font-semibold shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                        <option value="7d" @selected($selectedRange === '7d')>Last 7 days</option>
                        <option value="30d" @selected($selectedRange === '30d')>Last 30 days</option>
                        <option value="all" @selected($selectedRange === 'all')>All time history</option>
                    </select>
                </form>

                <a
                    href="{{ route('report.export', ['range' => $selectedRange]) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                >
                    <span>⬇</span> Export CSV
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-500"
                >
                    <span>🖨</span> Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 transition-colors">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            <!-- Smart Insight Banner -->
            <div class="fit-card-emerald rounded-3xl p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-lg text-white shadow-sm">
                            📊
                        </span>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                Summary Insight
                            </p>
                            <p class="mt-0.5 text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $insight }}
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="inline-flex rounded-full border border-emerald-200 bg-white px-3 py-1 text-xs font-bold text-emerald-700 dark:border-emerald-800 dark:bg-slate-900 dark:text-emerald-300">
                            Target: {{ number_format($defaultGoal) }} kcal/day
                        </span>
                    </div>
                </div>
            </div>

            <!-- KPI Summary Cards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Card 1: Avg Net Calories -->
                <div class="fit-card-violet rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-violet-700/80 dark:text-violet-300/80">Avg Net / Day</p>
                        <span class="rounded-lg bg-violet-500/15 px-2 py-0.5 text-[10px] font-bold text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                            {{ $totals['days'] }} {{ \Illuminate\Support\Str::plural('day', $totals['days']) }}
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        {{ number_format($totals['average_net']) }}
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">kcal</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        {{ number_format($totals['average_food']) }} food &minus; {{ number_format($totals['average_burn']) }} avg burn
                    </p>
                </div>

                <!-- Card 2: Total Food Intake + Meal Split -->
                <div class="fit-card-amber rounded-2xl p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700/80 dark:text-amber-300/80">Total Food Consumed</p>
                    <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        {{ number_format($totals['overall']) }}
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">kcal</span>
                    </p>
                    <div class="mt-2 flex flex-wrap gap-1.5 text-[10px] font-bold">
                        <span class="rounded-md bg-amber-500/15 px-1.5 py-0.5 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300">
                            B: {{ number_format($totals['breakfast']) }}
                        </span>
                        <span class="rounded-md bg-sky-500/15 px-1.5 py-0.5 text-sky-800 dark:bg-sky-500/20 dark:text-sky-300">
                            L: {{ number_format($totals['lunch']) }}
                        </span>
                        <span class="rounded-md bg-violet-500/15 px-1.5 py-0.5 text-violet-800 dark:bg-violet-500/20 dark:text-violet-300">
                            D: {{ number_format($totals['dinner']) }}
                        </span>
                    </div>
                </div>

                <!-- Card 3: Total Exercise Burned -->
                <div class="fit-card-emerald rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700/80 dark:text-emerald-300/80">Exercise Burned</p>
                        <span class="rounded-lg bg-emerald-500/15 px-2 py-0.5 text-[10px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                            {{ $totals['exercise_minutes'] }} min
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-400">
                        -{{ number_format($totals['exercise_burned']) }}
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">kcal</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        {{ $totals['workouts_count'] }} {{ \Illuminate\Support\Str::plural('workout', $totals['workouts_count']) }}
                        across {{ $totals['active_days'] }} active {{ \Illuminate\Support\Str::plural('day', $totals['active_days']) }}
                    </p>
                </div>

                <!-- Card 4: Goal Consistency -->
                <div class="fit-card-sky rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-sky-700/80 dark:text-sky-300/80">Goal Consistency</p>
                        <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                            {{ $totals['goal_hit_rate'] }}%
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        {{ $totals['goal_hit_days'] }}
                        <span class="text-sm font-semibold text-slate-400">/ {{ $totals['meal_days'] }} days</span>
                    </p>
                    <div class="mt-2.5 h-2 w-full overflow-hidden rounded-full bg-white/80 dark:bg-slate-900/70">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400"
                            style="width: {{ $totals['goal_hit_rate'] }}%"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Charts & Activity Breakdown -->
            <div class="grid gap-6 lg:grid-cols-12">

                <!-- Left: Daily Intake vs Exercise Burn Bar Chart -->
                <div class="fit-card lg:col-span-8 rounded-3xl p-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Up to Last 14 Logged Days</p>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Daily Food Intake vs. Exercise Burn</h3>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-[11px] font-bold">
                            <span class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                                <span class="h-2.5 w-2.5 rounded-sm bg-sky-500"></span> Food In
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                                <span class="h-2.5 w-2.5 rounded-sm bg-emerald-500"></span> Exercise Burned
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-amber-600 dark:text-amber-400">
                                <span class="w-3 border-t-2 border-dashed border-amber-500"></span> Goal ({{ number_format($defaultGoal) }})
                            </span>
                        </div>
                    </div>

                    @if ($chartDays->isNotEmpty())
                        <div class="relative mt-6 h-56 pt-4">
                            <!-- Dashed Daily Goal Reference Line -->
                            <div
                                class="pointer-events-none absolute inset-x-0 z-10 border-t-2 border-dashed border-amber-400/80"
                                style="bottom: calc(1.75rem + {{ $goalLinePct }}% * 0.78)"
                            >
                                <span class=" -top-5 right-0 absolute rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                    {{ number_format($defaultGoal) }} kcal
                                </span>
                            </div>

                            <!-- Paired Bars -->
                            <div class="grid h-full gap-2 items-end" style="grid-template-columns: repeat({{ $chartDays->count() }}, minmax(0, 1fr))">
                                @foreach ($chartDays as $day)
                                    <div
                                        title="{{ $day['date'] }} — Food: {{ number_format($day['food_total']) }} kcal | Burned: {{ number_format($day['exercise_burned']) }} kcal | Net: {{ number_format($day['net_total']) }} kcal"
                                        class="group flex h-full flex-col items-center justify-end gap-1.5"
                                    >
                                        <div class="flex h-44 w-full items-end justify-center gap-1 rounded-xl bg-slate-50 px-1 pt-2 dark:bg-slate-800/50">
                                            <div
                                                class="w-1/2 max-w-[14px] rounded-t-md bg-sky-500 transition-all duration-300 group-hover:bg-sky-400"
                                                style="height: {{ $day['food_pct'] }}%"
                                            ></div>
                                            <div
                                                class="w-1/2 max-w-[14px] rounded-t-md bg-emerald-500 transition-all duration-300 group-hover:bg-emerald-400"
                                                style="height: {{ $day['burn_pct'] }}%"
                                            ></div>
                                        </div>
                                        <span class="truncate text-[10px] font-bold text-slate-500 dark:text-slate-400">
                                            {{ $day['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-6 flex h-44 items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 text-xs text-slate-400 dark:border-slate-800">
                            No activity or meal data yet for this timeframe.
                        </div>
                    @endif
                </div>

                <!-- Right: Workout Breakdown by Category -->
                <div class="fit-card-violet lg:col-span-4 rounded-3xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Workout Mix</p>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Burn by Category</h3>
                        </div>
                        <a href="{{ route('activity') }}" class="text-xs font-bold text-emerald-600 hover:underline dark:text-emerald-400 print:hidden">
                            + Log &rarr;
                        </a>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse ($categoryBreakdown as $item)
                            <div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $item['emoji'] }} {{ $item['label'] }}
                                    </span>
                                    <span class="font-extrabold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($item['calories']) }} kcal ({{ $item['percent'] }}%)
                                    </span>
                                </div>
                                <div class="mt-1.5 h-2.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400"
                                        style="width: {{ $item['percent'] }}%"
                                    ></div>
                                </div>
                                <p class="mt-1 text-[11px] text-slate-400">
                                    {{ $item['sessions'] }} {{ \Illuminate\Support\Str::plural('session', $item['sessions']) }} &middot; {{ $item['minutes'] }} min
                                </p>
                            </div>
                        @empty
                            <div class="rounded-2xl border-2 border-dashed border-slate-200 p-8 text-center text-xs text-slate-400 dark:border-slate-800">
                                <p class="font-semibold text-slate-600 dark:text-slate-300">No workouts logged in this range</p>
                                <p class="mt-1">Head to Exercise to log Cardio, Strength, Sports, or Mobility sessions.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Historical Daily Breakdown Table (with Expandable Workouts) -->
            <div class="fit-card overflow-hidden rounded-3xl">
                <div class="flex flex-col gap-1 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">Historical Daily Energy Logs</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Click "Details" on any day to expand logged meals and exercises.
                        </p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        {{ $dailyRows->count() }} {{ \Illuminate\Support\Str::plural('day', $dailyRows->count()) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-800">
                        <thead class="bg-slate-50 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-800/80 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-3.5">Date</th>
                                <th class="px-6 py-3.5">Meals (B / L / D)</th>
                                <th class="px-6 py-3.5">Food In</th>
                                <th class="px-6 py-3.5 text-emerald-700 dark:text-emerald-400">Exercise Burned</th>
                                <th class="px-6 py-3.5">Net Calories</th>
                                <th class="px-6 py-3.5">Goal Status</th>
                                <th class="px-6 py-3.5 text-right print:hidden">Details</th>
                            </tr>
                        </thead>

                        @forelse ($dailyRows as $row)
                            <tbody x-data="{ open: false }" class="divide-y divide-slate-100 text-slate-700 dark:divide-slate-800/60 dark:text-slate-300">
                                <tr class="transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                                    <td class="whitespace-nowrap px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        {{ $row['date_label'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-xs">
                                        @if ($row['has_meal_report'])
                                            <span class="text-amber-700 dark:text-amber-400 font-semibold">{{ number_format($row['breakfast']) }}</span>
                                            <span class="text-slate-400">/</span>
                                            <span class="text-sky-700 dark:text-sky-400 font-semibold">{{ number_format($row['lunch']) }}</span>
                                            <span class="text-slate-400">/</span>
                                            <span class="text-violet-700 dark:text-violet-400 font-semibold">{{ number_format($row['dinner']) }}</span>
                                        @else
                                            <span class="text-slate-400 italic">No meal summary saved</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-semibold">
                                        {{ $row['has_meal_report'] ? number_format($row['food_total']) . ' kcal' : '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-extrabold text-emerald-600 dark:text-emerald-400">
                                        @if ($row['exercise_burned'] > 0)
                                            -{{ number_format($row['exercise_burned']) }} kcal
                                            @if ($row['exercise_minutes'] > 0)
                                                <span class="ml-1 text-[11px] font-semibold text-slate-400">({{ $row['exercise_minutes'] }}m)</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 font-normal">0 kcal</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-black text-slate-900 dark:text-white">
                                        {{ $row['has_meal_report'] ? number_format($row['net_total']) . ' kcal' : '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-xs">
                                        @if ($row['is_on_target'] === true)
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 font-bold text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300">
                                                On Target ({{ number_format(abs($row['goal_delta'])) }} left)
                                            </span>
                                        @elseif ($row['is_on_target'] === false)
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 font-bold text-rose-800 dark:bg-rose-950/70 dark:text-rose-300">
                                                Over (+{{ number_format($row['goal_delta']) }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-0.5 font-bold text-sky-800 dark:bg-sky-950/70 dark:text-sky-300">
                                                Workout Only
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-xs print:hidden">
                                        @if ($row['workouts_count'] > 0 || $row['meals_count'] > 0)
                                            <button
                                                type="button"
                                                @click="open = !open"
                                                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-bold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                            >
                                                <span>
                                                    @if ($row['meals_count'] > 0 && $row['workouts_count'] > 0)
                                                        {{ $row['meals_count'] }}m &middot; {{ $row['workouts_count'] }}w
                                                    @elseif ($row['meals_count'] > 0)
                                                        {{ $row['meals_count'] }} {{ \Illuminate\Support\Str::plural('meal', $row['meals_count']) }}
                                                    @else
                                                        {{ $row['workouts_count'] }} {{ \Illuminate\Support\Str::plural('workout', $row['workouts_count']) }}
                                                    @endif
                                                </span>
                                                <span x-text="open ? '▲' : '▼'" class="text-[9px]"></span>
                                            </button>
                                        @else
                                            <a
                                                href="{{ route('activity', ['date' => $row['date']]) }}"
                                                class="font-semibold text-slate-400 hover:text-emerald-600"
                                            >
                                                + Add
                                            </a>
                                        @endif
                                    </td>
                                </tr>

                                @if ($row['workouts_count'] > 0 || $row['meals_count'] > 0)
                                    <tr x-show="open" x-cloak class="bg-slate-50/70 dark:bg-slate-800/30">
                                        <td colspan="7" class="px-6 py-4 space-y-3">
                                            @if ($row['meals_count'] > 0)
                                                <div>
                                                    <p class="mb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Meals Logged</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($row['meal_logs'] as $mealLog)
                                                            @php
                                                                $slotColor = match ($mealLog->slot) {
                                                                    'breakfast' => 'text-amber-600 dark:text-amber-400',
                                                                    'lunch' => 'text-sky-600 dark:text-sky-400',
                                                                    default => 'text-violet-600 dark:text-violet-400',
                                                                };
                                                            @endphp
                                                            <div class="inline-flex items-center gap-2 rounded-xl border border-amber-200/80 bg-white px-3 py-1.5 text-xs shadow-sm dark:border-slate-700 dark:bg-slate-900">
                                                                <x-meal-slot-icon :meal-slot="$mealLog->slot" class="h-3.5 w-3.5 {{ $slotColor }}" />
                                                                <span class="font-bold text-slate-800 dark:text-white">{{ $mealLog->meal_name }}</span>
                                                                @if ($mealLog->quantity > 1)
                                                                    <span class="text-[11px] font-bold text-slate-400">&times;{{ $mealLog->quantity }}</span>
                                                                @endif
                                                                <span class="font-extrabold text-amber-600 dark:text-amber-400">{{ number_format($mealLog->subtotal) }} kcal</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($row['workouts_count'] > 0)
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                                    <div>
                                                        <p class="mb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Workouts Logged</p>
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach ($row['exercise_logs'] as $log)
                                                                @php
                                                                    $catMeta = $categories[$log->category] ?? ['emoji' => '🏅'];
                                                                @endphp
                                                                <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs shadow-sm dark:border-slate-700 dark:bg-slate-900">
                                                                    <span>{{ $catMeta['emoji'] }}</span>
                                                                    <span class="font-bold text-slate-800 dark:text-white">{{ $log->activity_name }}</span>
                                                                    <span class="text-slate-400">&middot;</span>
                                                                    <span class="text-slate-500">{{ $log->duration_minutes }} min</span>
                                                                    <span class="font-extrabold text-emerald-600 dark:text-emerald-400">-{{ number_format($log->calories_burned) }} kcal</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <a
                                                        href="{{ route('activity', ['date' => $row['date']]) }}"
                                                        class="shrink-0 text-xs font-bold text-emerald-600 hover:underline dark:text-emerald-400"
                                                    >
                                                        Manage workouts &rarr;
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        @empty
                            <tbody>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-xs text-slate-400">
                                        No reports or workouts found for this period. Log meals on the Dashboard or workouts in Exercise!
                                    </td>
                                </tr>
                            </tbody>
                        @endforelse
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
