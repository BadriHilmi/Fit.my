<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-400">
                    Fitness &amp; Energy Expenditure
                </p>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white sm:text-3xl">
                    Exercise &amp; Activity Log
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Estimate calories burned with MET values or log smartwatch workouts, then track your weekly active minutes.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <x-daily-target-gauge
                    :goal="$dailyTarget"
                    :net="$netCaloriesToday"
                    :food="$foodConsumedToday"
                    :burned="$burnedToday"
                    uid="activity"
                />
            </div>
        </div>
    </x-slot>

    <div
        x-data="{
            mode: @js(old('entry_mode', 'met')),
            activeCategory: 'all',
            search: '',
            selectedKey: @js(old('activity_key', 'walking_brisk')),
            weightKg: @js((float) old('weight_kg', $profileWeight ?? 70)),
            durationMinutes: @js((int) old('duration_minutes', 30)),
            selectedDate: @js(old('logged_date', $selectedDate)),
            selectedDateLabel: @js($selectedDateLabel),
            today: @js($today),
            todayLabel: @js($todayLabel),

            activities: @js($activities),
            categories: @js($categories),

            dayLogs: @js($dayLogs),
            daySummary: @js($daySummary),
            burnedToday: {{ $burnedToday }},
            minutesToday: {{ $minutesToday }},
            dailyBars: @js($dailyBars),
            weeklyStats: @js($weeklyStats),
            recentLogs: @js($recentLogs),
            foodConsumedToday: {{ $foodConsumedToday }},
            dailyTarget: {{ $dailyTarget }},
            netCaloriesToday: {{ $netCaloriesToday }},

            editingId: null,
            isSubmitting: false,
            toastMessage: '',
            toastError: false,
            toastTimer: null,

            timerRunning: false,
            elapsedSeconds: 0,
            timerInterval: null,

            get selectedActivity() {
                return this.activities[this.selectedKey] || Object.values(this.activities)[0];
            },
            get filteredActivities() {
                const q = this.search.trim().toLowerCase();
                return Object.entries(this.activities).filter(([key, item]) => {
                    const catMatch = this.activeCategory === 'all' || item.category === this.activeCategory;
                    const textMatch = !q || item.name.toLowerCase().includes(q) || item.intensity.toLowerCase().includes(q);
                    return catMatch && textMatch;
                });
            },
            get burnRatePerMinute() {
                const met = Number(this.selectedActivity?.met || 0);
                const weight = Math.max(0, Number(this.weightKg || 0));
                return (met * 3.5 * weight) / 200;
            },
            get estimatedCalories() {
                const duration = Math.max(0, Number(this.durationMinutes || 0));
                return Math.round(this.burnRatePerMinute * duration);
            },
            get formattedTimer() {
                const mins = String(Math.floor(this.elapsedSeconds / 60)).padStart(2, '0');
                const secs = String(this.elapsedSeconds % 60).padStart(2, '0');
                return mins + ':' + secs;
            },
            formatNum(n) {
                return Number(n || 0).toLocaleString();
            },
            plural(word, count) {
                return Number(count) === 1 ? word : word + 's';
            },
            showToast(message, isError = false) {
                this.toastMessage = message;
                this.toastError = isError;
                if (this.toastTimer) clearTimeout(this.toastTimer);
                this.toastTimer = setTimeout(() => {
                    this.toastMessage = '';
                }, 2800);
            },
            notifyGauge() {
                window.dispatchEvent(new CustomEvent('fit-calories-updated', {
                    detail: {
                        food: Number(this.foodConsumedToday || 0),
                        burned: Number(this.burnedToday || 0),
                        net: Number(this.netCaloriesToday || 0),
                        goal: Number(this.dailyTarget || 0)
                    }
                }));
            },
            applyState(data) {
                if (!data) return;
                this.selectedDate = data.selectedDate;
                this.selectedDateLabel = data.selectedDateLabel;
                this.today = data.today;
                this.todayLabel = data.todayLabel;
                this.dayLogs = data.dayLogs || [];
                this.daySummary = data.daySummary || { calories: 0, minutes: 0, sessions: 0 };
                this.burnedToday = Number(data.burnedToday || 0);
                this.minutesToday = Number(data.minutesToday || 0);
                this.dailyBars = data.dailyBars || [];
                this.weeklyStats = data.weeklyStats || this.weeklyStats;
                this.recentLogs = data.recentLogs || [];
                this.foodConsumedToday = Number(data.foodConsumedToday || 0);
                this.dailyTarget = Number(data.dailyTarget || 2000);
                this.netCaloriesToday = Number(data.netCaloriesToday || 0);
                this.notifyGauge();
                if (data.message) this.showToast(data.message);
            },
            async submitExerciseForm(formEl, options = {}) {
                if (options.confirmText && !confirm(options.confirmText)) return;
                this.isSubmitting = true;
                try {
                    const data = await window.fitApi(formEl.action, {
                        method: 'POST',
                        body: new FormData(formEl),
                    });
                    this.applyState(data);
                    if (options.resetNotes) {
                        const notesInput = formEl.querySelector('input[name=notes]');
                        if (notesInput) notesInput.value = '';
                    }
                    if (options.clearEdit) {
                        this.editingId = null;
                    }
                    if (options.resetManualName) {
                        const nameInput = formEl.querySelector('input[name=activity_name]');
                        if (nameInput) nameInput.value = '';
                    }
                } catch (err) {
                    this.showToast(err.message || 'Could not save workout.', true);
                } finally {
                    this.isSubmitting = false;
                }
            },
            async loadDate(date) {
                try {
                    const url = '{{ route('activity') }}?date=' + encodeURIComponent(date);
                    const data = await window.fitApi(url);
                    this.editingId = null;
                    this.applyState(data);
                    if (window.history && window.history.replaceState) {
                        const targetUrl = date === this.today ? '{{ route('activity') }}' : url;
                        window.history.replaceState({}, '', targetUrl);
                    }
                } catch (err) {
                    this.showToast(err.message || 'Could not load selected date.', true);
                }
            },
            toggleTimer() {
                if (this.timerRunning) {
                    clearInterval(this.timerInterval);
                    this.timerInterval = null;
                    this.timerRunning = false;
                } else {
                    this.timerRunning = true;
                    this.timerInterval = setInterval(() => {
                        this.elapsedSeconds += 1;
                    }, 1000);
                }
            },
            resetTimer() {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
                this.timerRunning = false;
                this.elapsedSeconds = 0;
            },
            applyTimerDuration() {
                if (this.elapsedSeconds > 0) {
                    this.durationMinutes = Math.max(1, Math.ceil(this.elapsedSeconds / 60));
                }
            }
        }"
        class="py-8 transition-colors"
    >
        <!-- Live Action Toast -->
        <div
            x-show="toastMessage"
            x-transition
            x-cloak
            class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 rounded-2xl px-5 py-3.5 text-sm font-bold text-white shadow-2xl backdrop-blur-md"
            :class="toastError ? 'bg-rose-600' : 'bg-emerald-600'"
        >
            <template x-if="!toastError">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </template>
            <template x-if="toastError">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            </template>
            <span x-text="toastMessage"></span>
        </div>

        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <!-- Server Flash Success Banner (fallback for non-JS) -->
            @if (session('success'))
                <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3.5 text-sm font-semibold text-emerald-800 dark:border-emerald-500/40 dark:text-emerald-300">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-xs text-white">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Validation Errors (fallback for non-JS) -->
            @if ($errors->any())
                <div class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3.5 text-xs text-rose-700 dark:border-rose-500/40 dark:text-rose-300">
                    <p class="font-bold">Please fix the following before logging:</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Top 4 KPI Summary Cards -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Card 1: Burned Today -->
                <div class="fit-card-emerald rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700/80 dark:text-emerald-300/80">Burned Today</p>
                        <span class="rounded-lg bg-emerald-500/15 px-2 py-0.5 text-[10px] font-bold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300" x-text="minutesToday + ' min'">
                            {{ $minutesToday }} min
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-400">
                        <span x-text="formatNum(burnedToday)">{{ number_format($burnedToday) }}</span>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">kcal</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">logged for <span x-text="todayLabel">{{ $todayLabel }}</span></p>
                </div>

                <!-- Card 2: Weekly Active Minutes Goal -->
                <div class="fit-card-sky rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-sky-700/80 dark:text-sky-300/80">Weekly Active Goal</p>
                        <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400" x-text="weeklyStats.progress_percent + '%'">
                            {{ $weeklyStats['progress_percent'] }}%
                        </span>
                    </div>
                    <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                        <span x-text="formatNum(weeklyStats.minutes)">{{ number_format($weeklyStats['minutes']) }}</span>
                        <span class="text-xs font-semibold text-slate-400" x-text="'/ ' + weeklyStats.goal_minutes + ' min'">/ {{ $weeklyStats['goal_minutes'] }} min</span>
                    </p>
                    <div class="mt-3 h-2.5 w-full overflow-hidden rounded-full bg-white/80 dark:bg-slate-900/70">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500"
                            :style="'width: ' + weeklyStats.progress_percent + '%'"
                            style="width: {{ $weeklyStats['progress_percent'] }}%"
                        ></div>
                    </div>
                </div>

                <!-- Card 3: Weekly Burn & Consistency -->
                <div class="fit-card-violet rounded-2xl p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-violet-700/80 dark:text-violet-300/80">This Week's Output</p>
                    <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        <span x-text="formatNum(weeklyStats.calories)">{{ number_format($weeklyStats['calories']) }}</span>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">kcal</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400" x-text="weeklyStats.sessions + ' ' + plural('workout', weeklyStats.sessions) + ' across ' + weeklyStats.active_days + ' ' + plural('day', weeklyStats.active_days)">
                        {{ $weeklyStats['sessions'] }} {{ \Illuminate\Support\Str::plural('workout', $weeklyStats['sessions']) }}
                        across {{ $weeklyStats['active_days'] }} {{ \Illuminate\Support\Str::plural('day', $weeklyStats['active_days']) }}
                    </p>
                </div>

                <!-- Card 4: Today's Energy Balance Preview -->
                <div class="fit-card-amber rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-700/80 dark:text-amber-300/80">Today's Net Est.</p>
                        <a href="{{ route('dashboard') }}" class="text-[11px] font-bold text-emerald-600 hover:underline dark:text-emerald-400">
                            Meals &rarr;
                        </a>
                    </div>
                    <p
                        class="mt-2 text-3xl font-black transition-colors"
                        :class="netCaloriesToday <= dailyTarget ? 'text-slate-900 dark:text-white' : 'text-rose-600 dark:text-rose-400'"
                    >
                        <span x-text="formatNum(netCaloriesToday)">{{ number_format($netCaloriesToday) }}</span>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">kcal</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400" x-text="formatNum(foodConsumedToday) + ' in − ' + formatNum(burnedToday) + ' burned'">
                        {{ number_format($foodConsumedToday) }} in &minus; {{ number_format($burnedToday) }} burned
                    </p>
                </div>
            </div>

            <!-- Main 2-Column Interactive Studio -->
            <div class="grid gap-6 lg:grid-cols-12">

                <!-- LEFT COLUMN: Native Calculator & Workout Logger -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="fit-card rounded-3xl p-6">
                        <!-- Card Header + Mode Toggle -->
                        <div class="flex flex-col gap-4 border-b border-slate-200/70 pb-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                </span>
                                <div>
                                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Log Workout</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        Native MET estimator or manual smartwatch calories
                                    </p>
                                </div>
                            </div>

                            <!-- Mode Switcher -->
                            <div class="inline-flex rounded-xl border border-slate-200 bg-slate-100 p-1 dark:border-slate-700 dark:bg-slate-800">
                                <button
                                    type="button"
                                    @click="mode = 'met'"
                                    class="rounded-lg px-3 py-1.5 text-xs font-bold transition"
                                    :class="mode === 'met' ? 'bg-white text-emerald-700 shadow-sm dark:bg-slate-900 dark:text-emerald-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                                >
                                    MET Calculator
                                </button>
                                <button
                                    type="button"
                                    @click="mode = 'manual'"
                                    class="rounded-lg px-3 py-1.5 text-xs font-bold transition"
                                    :class="mode === 'manual' ? 'bg-white text-emerald-700 shadow-sm dark:bg-slate-900 dark:text-emerald-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                                >
                                    Smartwatch / Custom
                                </button>
                            </div>
                        </div>

                        <!-- Quick One-Click Presets -->
                        <div class="mt-4">
                            <p class="mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Quick Log (1-Click with {{ $profileWeight ? number_format($profileWeight, 0).' kg' : 'current weight' }})
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($presets as $preset)
                                    @php
                                        $presetActivity = $activities[$preset['activity_key']] ?? null;
                                    @endphp
                                    @if ($presetActivity)
                                        <form
                                            method="POST"
                                            action="{{ route('activity.store') }}"
                                            @submit.prevent="submitExerciseForm($el)"
                                            class="inline"
                                        >
                                            @csrf
                                            <input type="hidden" name="entry_mode" value="met">
                                            <input type="hidden" name="activity_key" value="{{ $preset['activity_key'] }}">
                                            <input type="hidden" name="duration_minutes" value="{{ $preset['duration_minutes'] }}">
                                            <input type="hidden" name="weight_kg" :value="weightKg">
                                            <input type="hidden" name="logged_date" :value="selectedDate">
                                            <button
                                                type="submit"
                                                :disabled="isSubmitting"
                                                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-800 disabled:opacity-60 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-200 dark:hover:border-emerald-600 dark:hover:bg-emerald-950/40"
                                            >
                                                <svg class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                                <span>+ {{ $preset['label'] }}</span>
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- MET CALCULATOR FORM -->
                        <form
                            method="POST"
                            action="{{ route('activity.store') }}"
                            @submit.prevent="submitExerciseForm($el, { resetNotes: true })"
                            x-show="mode === 'met'"
                            class="mt-6 space-y-5"
                        >
                            @csrf
                            <input type="hidden" name="entry_mode" value="met">
                            <input type="hidden" name="activity_key" :value="selectedKey">

                            <!-- Activity Filter & Search -->
                            <div class="space-y-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        1. Choose Activity
                                    </label>
                                    <input
                                        type="text"
                                        x-model="search"
                                        placeholder="Search activity (e.g. running, badminton)..."
                                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-emerald-500 focus:ring-emerald-500 sm:w-64 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>

                                <!-- Category Filter Pills -->
                                <div class="flex flex-wrap gap-1.5">
                                    <button
                                        type="button"
                                        @click="activeCategory = 'all'"
                                        class="rounded-full px-3 py-1 text-xs font-bold transition"
                                        :class="activeCategory === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-200/70 text-slate-600 hover:bg-slate-200 dark:bg-slate-900 dark:text-slate-300'"
                                    >
                                        All Activities
                                    </button>
                                    <template x-for="(cat, key) in categories" :key="key">
                                        <button
                                            type="button"
                                            @click="activeCategory = key"
                                            class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold transition"
                                            :class="activeCategory === key ? 'bg-emerald-600 text-white' : 'bg-slate-200/70 text-slate-600 hover:bg-slate-200 dark:bg-slate-900 dark:text-slate-300'"
                                        >
                                            <span x-text="cat.label"></span>
                                        </button>
                                    </template>
                                </div>

                                <!-- Activity Picker Grid -->
                                <div class="grid max-h-60 gap-2 overflow-y-auto pr-1 sm:grid-cols-2">
                                    <template x-for="[key, item] in filteredActivities" :key="key">
                                        <button
                                            type="button"
                                            @click="selectedKey = key"
                                            class="flex items-center justify-between rounded-2xl border p-3 text-left transition"
                                            :class="selectedKey === key
                                                ? 'border-emerald-500 bg-emerald-50/90 ring-2 ring-emerald-500/30 dark:border-emerald-500 dark:bg-emerald-950/40'
                                                : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900/60 dark:hover:bg-slate-900'"
                                        >
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="truncate text-xs font-bold text-slate-900 dark:text-white" x-text="item.name"></p>
                                                    <p class="text-[11px] capitalize text-slate-500 dark:text-slate-400">
                                                        <span x-text="item.intensity"></span> &middot; <span x-text="item.met"></span> MET
                                                    </p>
                                                </div>
                                            </div>
                                            <span
                                                x-show="selectedKey === key"
                                                class="ml-2 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white"
                                            >
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                            </span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Weight, Duration, Date Inputs -->
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="met_weight_kg" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Body Weight (kg)
                                    </label>
                                    <input
                                        id="met_weight_kg"
                                        type="number"
                                        name="weight_kg"
                                        step="0.5"
                                        min="20"
                                        max="350"
                                        x-model.number="weightKg"
                                        required
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>

                                <div>
                                    <label for="met_duration" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Duration (minutes)
                                    </label>
                                    <input
                                        id="met_duration"
                                        type="number"
                                        name="duration_minutes"
                                        min="1"
                                        max="720"
                                        x-model.number="durationMinutes"
                                        required
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                    <div class="mt-1.5 flex flex-wrap gap-1">
                                        <template x-for="mins in [15, 30, 45, 60]" :key="mins">
                                            <button
                                                type="button"
                                                @click="durationMinutes = mins"
                                                class="rounded-lg border px-2 py-0.5 text-[11px] font-bold transition"
                                                :class="Number(durationMinutes) === mins
                                                    ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300'
                                                    : 'border-slate-200 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-400'"
                                                x-text="mins + 'm'"
                                            ></button>
                                        </template>
                                    </div>
                                </div>

                                <div>
                                    <label for="met_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Workout Date
                                    </label>
                                    <input
                                        id="met_date"
                                        type="date"
                                        name="logged_date"
                                        x-model="selectedDate"
                                        @change="loadDate(selectedDate)"
                                        required
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>
                            </div>

                            <!-- Built-in Workout Timer + Notes -->
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/80 bg-white/70 p-3.5 dark:border-slate-700 dark:bg-slate-900/50">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Workout Stopwatch</span>
                                        <span class="font-mono text-base font-black text-slate-900 dark:text-white" x-text="formattedTimer"></span>
                                    </div>
                                    <div class="mt-2.5 flex flex-wrap gap-1.5">
                                        <button
                                            type="button"
                                            @click="toggleTimer()"
                                            class="rounded-lg px-2.5 py-1 text-xs font-bold text-white transition"
                                            :class="timerRunning ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-500'"
                                            x-text="timerRunning ? 'Pause' : 'Start'"
                                        ></button>
                                        <button
                                            type="button"
                                            @click="resetTimer()"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300"
                                        >
                                            Reset
                                        </button>
                                        <button
                                            type="button"
                                            x-show="elapsedSeconds > 0"
                                            @click="applyTimerDuration()"
                                            class="rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-bold text-white hover:bg-slate-700 dark:bg-slate-700"
                                        >
                                            Use Minutes
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label for="met_notes" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Notes (Optional)
                                    </label>
                                    <input
                                        id="met_notes"
                                        type="text"
                                        name="notes"
                                        value="{{ old('notes') }}"
                                        maxlength="255"
                                        placeholder="e.g. 5km around park, felt strong"
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-xs focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                    <p class="mt-1.5 text-[11px] text-slate-400">
                                        Formula: (MET &times; 3.5 &times; kg / 200) &times; min
                                    </p>
                                </div>
                            </div>

                            <!-- Live Estimate Preview & Submit -->
                            <div class="flex flex-col gap-4 rounded-2xl bg-emerald-600 p-4 text-white dark:bg-emerald-700 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-100">
                                        Estimated Burn &middot; <span x-text="selectedActivity?.name"></span>
                                    </p>
                                    <div class="mt-0.5 flex items-baseline gap-2">
                                        <span class="text-3xl font-black" x-text="estimatedCalories"></span>
                                        <span class="text-xs font-semibold text-emerald-100">kcal</span>
                                        <span class="text-xs text-emerald-100/90">
                                            (~<span x-text="burnRatePerMinute.toFixed(1)"></span> kcal/min)
                                        </span>
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-extrabold text-emerald-700 shadow-sm transition hover:bg-emerald-50 disabled:opacity-60"
                                >
                                    <span x-text="isSubmitting ? 'Logging...' : '+ Log This Workout'">+ Log This Workout</span>
                                </button>
                            </div>
                        </form>

                        <!-- MANUAL / SMARTWATCH FORM -->
                        <form
                            method="POST"
                            action="{{ route('activity.store') }}"
                            @submit.prevent="submitExerciseForm($el, { resetNotes: true, resetManualName: true })"
                            x-show="mode === 'manual'"
                            x-cloak
                            class="mt-6 space-y-4"
                        >
                            @csrf
                            <input type="hidden" name="entry_mode" value="manual">

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="manual_name" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Workout / Activity Name
                                    </label>
                                    <input
                                        id="manual_name"
                                        type="text"
                                        name="activity_name"
                                        value="{{ old('activity_name') }}"
                                        required
                                        maxlength="100"
                                        placeholder="e.g. Apple Watch Outdoor Run"
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>

                                <div>
                                    <label for="manual_date" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Workout Date
                                    </label>
                                    <input
                                        id="manual_date"
                                        type="date"
                                        name="logged_date"
                                        x-model="selectedDate"
                                        @change="loadDate(selectedDate)"
                                        required
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-4">
                                <div>
                                    <label for="manual_category" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Category
                                    </label>
                                    <select
                                        id="manual_category"
                                        name="category"
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-xs font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                        @foreach ($categories as $catKey => $cat)
                                            <option value="{{ $catKey }}" @selected(old('category') === $catKey)>
                                                {{ $cat['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="manual_intensity" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Intensity
                                    </label>
                                    <select
                                        id="manual_intensity"
                                        name="intensity"
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-xs font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                        @foreach ($intensities as $intKey => $intLabel)
                                            <option value="{{ $intKey }}" @selected(old('intensity', 'moderate') === $intKey)>
                                                {{ $intLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="manual_duration" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Duration (min)
                                    </label>
                                    <input
                                        id="manual_duration"
                                        type="number"
                                        name="duration_minutes"
                                        value="{{ old('duration_minutes', 30) }}"
                                        min="1"
                                        max="720"
                                        required
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>

                                <div>
                                    <label for="manual_calories" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                        Calories (kcal)
                                    </label>
                                    <input
                                        id="manual_calories"
                                        type="number"
                                        name="calories_burned"
                                        value="{{ old('calories_burned', 250) }}"
                                        min="1"
                                        max="10000"
                                        required
                                        class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="manual_notes" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Notes (Optional)
                                </label>
                                <input
                                    id="manual_notes"
                                    type="text"
                                    name="notes"
                                    value="{{ old('notes') }}"
                                    maxlength="255"
                                    placeholder="e.g. Synced from Garmin / Strava"
                                    class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-xs focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                >
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-500 disabled:opacity-60"
                                >
                                    <span x-text="isSubmitting ? 'Saving...' : '+ Save Custom Workout'">+ Save Custom Workout</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Selected Day Log + Weekly Bar Chart -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Selected Day's Logged Workouts -->
                    <div class="fit-card rounded-3xl p-6">
                        <div class="flex items-center justify-between border-b border-slate-200/70 pb-4 dark:border-slate-700">
                            <div>
                                <p
                                    class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400"
                                    x-text="selectedDate === today ? 'Today\'s Session Log' : 'Workouts on Selected Date'"
                                >
                                    {{ $selectedDate === $today ? "Today's Session Log" : 'Workouts on Selected Date' }}
                                </p>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white" x-text="selectedDateLabel">
                                    {{ $selectedDateLabel }}
                                </h3>
                            </div>
                            <span
                                class="rounded-full bg-emerald-600 px-3 py-1 text-xs font-black text-white"
                                x-text="formatNum(daySummary.calories) + ' kcal'"
                            >
                                {{ number_format($daySummary['calories']) }} kcal
                            </span>
                        </div>

                        <div
                            x-show="selectedDate !== today"
                            @if ($selectedDate === $today) x-cloak @endif
                            class="mt-3 flex items-center justify-between rounded-xl bg-slate-100/80 px-3 py-2 text-xs dark:bg-slate-900/60"
                        >
                            <span class="text-slate-500 dark:text-slate-400">Viewing <span x-text="selectedDate"></span></span>
                            <button
                                type="button"
                                @click="loadDate(today)"
                                class="font-bold text-emerald-600 hover:underline dark:text-emerald-400"
                            >
                                Back to Today &rarr;
                            </button>
                        </div>

                        <div class="mt-4 space-y-3">
                            <template x-for="log in dayLogs" :key="log.id">
                                <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-3.5 transition dark:border-slate-700 dark:bg-slate-900/60">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-start gap-2.5 min-w-0">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-emerald-600 shadow-sm dark:bg-slate-800 dark:text-emerald-400">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-bold text-slate-900 dark:text-white" x-text="log.activity_name"></p>
                                                <div class="mt-0.5 flex flex-wrap items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400">
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="log.duration_minutes + ' min'"></span>
                                                    <span>&middot;</span>
                                                    <span class="capitalize" x-text="log.intensity"></span>
                                                    <span>&middot;</span>
                                                    <span
                                                        class="uppercase text-[10px] font-bold"
                                                        :class="log.entry_mode === 'met' ? 'text-emerald-600 dark:text-emerald-400' : 'text-sky-600 dark:text-sky-400'"
                                                        x-text="log.entry_mode"
                                                    ></span>
                                                </div>
                                                <p x-show="log.notes" class="mt-1 text-xs text-slate-500 dark:text-slate-400" x-text="log.notes"></p>
                                            </div>
                                        </div>

                                        <div class="shrink-0 text-right">
                                            <p class="text-sm font-black text-emerald-600 dark:text-emerald-400" x-text="'-' + formatNum(log.calories_burned) + ' kcal'"></p>
                                            <div class="mt-1 flex items-center justify-end gap-2 text-[11px]">
                                                <button
                                                    type="button"
                                                    @click="editingId = (editingId === log.id ? null : log.id)"
                                                    class="font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
                                                    x-text="editingId === log.id ? 'Cancel' : 'Edit'"
                                                ></button>
                                                <form
                                                    method="POST"
                                                    :action="log.destroy_url"
                                                    @submit.prevent="submitExerciseForm($el, { confirmText: 'Remove this workout?' })"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="view_date" :value="selectedDate">
                                                    <button type="submit" class="font-semibold text-rose-500 hover:text-rose-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Inline Quick-Edit Form -->
                                    <form
                                        x-show="editingId === log.id"
                                        x-cloak
                                        method="POST"
                                        :action="log.update_url"
                                        @submit.prevent="submitExerciseForm($el, { clearEdit: true })"
                                        class="mt-3 space-y-2.5 border-t border-slate-200 pt-3 dark:border-slate-700"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="view_date" :value="selectedDate">

                                        <div class="grid gap-2" :class="log.entry_mode === 'manual' ? 'sm:grid-cols-3' : 'sm:grid-cols-2'">
                                            <template x-if="log.entry_mode === 'manual'">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase text-slate-400">Name</label>
                                                    <input
                                                        type="text"
                                                        name="activity_name"
                                                        :value="log.activity_name"
                                                        required
                                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white px-2.5 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                    >
                                                </div>
                                            </template>
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase text-slate-400">Duration (min)</label>
                                                <input
                                                    type="number"
                                                    name="duration_minutes"
                                                    :value="log.duration_minutes"
                                                    min="1"
                                                    max="720"
                                                    required
                                                    class="mt-1 w-full rounded-lg border-slate-200 bg-white px-2.5 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                >
                                            </div>
                                            <template x-if="log.entry_mode === 'manual'">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase text-slate-400">Calories</label>
                                                    <input
                                                        type="number"
                                                        name="calories_burned"
                                                        :value="log.calories_burned"
                                                        min="1"
                                                        max="10000"
                                                        required
                                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white px-2.5 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                    >
                                                </div>
                                            </template>
                                            <template x-if="log.entry_mode === 'met'">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase text-slate-400">Notes</label>
                                                    <input
                                                        type="text"
                                                        name="notes"
                                                        :value="log.notes || ''"
                                                        placeholder="Update note..."
                                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white px-2.5 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                    >
                                                </div>
                                            </template>
                                        </div>

                                        <template x-if="log.entry_mode === 'manual'">
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase text-slate-400">Notes</label>
                                                <input
                                                    type="text"
                                                    name="notes"
                                                    :value="log.notes || ''"
                                                    placeholder="Update note..."
                                                    class="mt-1 w-full rounded-lg border-slate-200 bg-white px-2.5 py-1 text-xs dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                                >
                                            </div>
                                        </template>

                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-[10px] text-slate-400"
                                                x-text="log.entry_mode === 'met' ? 'Calories recalculate automatically from MET.' : 'Manual calorie entry.'"
                                            ></span>
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1 text-xs font-bold text-white hover:bg-emerald-500">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </template>

                            <div
                                x-show="dayLogs.length === 0"
                                @if (!empty($dayLogs)) x-cloak @endif
                                class="rounded-2xl border-2 border-dashed border-slate-300/70 p-8 text-center dark:border-slate-700"
                            >
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">No workouts logged for this day yet</p>
                                <p class="mt-1 text-xs text-slate-400">
                                    Pick an activity or hit a quick-log preset on the left to record your session.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Activity Mini Bar Chart (Mon - Sun) -->
                    <div class="fit-card-emerald rounded-3xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700/80 dark:text-emerald-300/80">Mon &ndash; Sun Active Minutes</p>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">This Week at a Glance</h3>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400" x-text="weeklyStats.minutes + ' min total'">
                                {{ $weeklyStats['minutes'] }} min total
                            </span>
                        </div>

                        <div class="mt-5 grid grid-cols-7 gap-2 items-end h-36">
                            <template x-for="bar in dailyBars" :key="bar.date">
                                <button
                                    type="button"
                                    @click="loadDate(bar.date)"
                                    :title="bar.date + ': ' + bar.minutes + ' min (' + bar.calories + ' kcal)'"
                                    class="group flex h-full flex-col items-center justify-end gap-1.5 focus:outline-none"
                                >
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-emerald-700 dark:text-slate-400 dark:group-hover:text-emerald-300" x-text="bar.minutes > 0 ? bar.minutes + 'm' : '—'"></span>
                                    <div class="flex h-20 w-full items-end rounded-xl bg-white/75 p-1 dark:bg-slate-900/60">
                                        <div
                                            class="w-full rounded-lg transition-all duration-300"
                                            :class="bar.is_selected ? 'bg-emerald-600' : (bar.minutes > 0 ? 'bg-emerald-400 group-hover:bg-emerald-500' : 'bg-transparent')"
                                            :style="'height: ' + bar.height_percent + '%'"
                                        ></div>
                                    </div>
                                    <span
                                        class="text-[11px] font-bold"
                                        :class="bar.is_today ? 'text-emerald-700 dark:text-emerald-400' : (bar.is_selected ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400')"
                                        x-text="bar.label"
                                    ></span>
                                </button>
                            </template>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-500 dark:text-slate-400">
                            Click any day bar to view or backfill workouts for that date.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Recent Workout History Table (Last 30 Days) -->
            <div class="fit-card overflow-hidden rounded-3xl">
                <div class="flex items-center justify-between border-b border-slate-200/70 px-6 py-4 dark:border-slate-700">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">Recent Workout History (Last 30 Days)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Your saved activity sessions ready to feed into Reports.</p>
                    </div>
                    <span
                        class="rounded-full bg-slate-200/70 px-3 py-1 text-xs font-bold text-slate-700 dark:bg-slate-900 dark:text-slate-300"
                        x-text="recentLogs.length + ' ' + plural('entry', recentLogs.length)"
                    >
                        {{ count($recentLogs) }} {{ \Illuminate\Support\Str::plural('entry', count($recentLogs)) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200/70 text-sm dark:divide-slate-700">
                        <thead class="bg-slate-100/80 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-900/60 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-3.5">Date</th>
                                <th class="px-6 py-3.5">Activity</th>
                                <th class="px-6 py-3.5">Category</th>
                                <th class="px-6 py-3.5">Duration</th>
                                <th class="px-6 py-3.5 text-emerald-700 dark:text-emerald-400">Burned</th>
                                <th class="px-6 py-3.5">Notes</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60 text-slate-700 dark:divide-slate-700/60 dark:text-slate-300">
                            <template x-for="log in recentLogs" :key="log.id">
                                <tr class="transition hover:bg-slate-100/70 dark:hover:bg-slate-900/40">
                                    <td class="whitespace-nowrap px-6 py-3.5 font-bold text-slate-900 dark:text-white">
                                        <button type="button" @click="loadDate(log.logged_date)" class="hover:text-emerald-600 hover:underline" x-text="log.logged_date_label"></button>
                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200">
                                        <span x-text="log.activity_name"></span>
                                        <span x-show="log.met_value" class="ml-1 text-[11px] text-slate-400" x-text="'(' + log.met_value + ' MET)'"></span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-xs">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                            <svg class="h-3 w-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                                            <span x-text="log.category_label"></span>
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 font-semibold" x-text="log.duration_minutes + ' min'"></td>
                                    <td class="whitespace-nowrap px-6 py-3.5 font-extrabold text-emerald-600 dark:text-emerald-400" x-text="'-' + formatNum(log.calories_burned) + ' kcal'"></td>
                                    <td class="max-w-xs truncate px-6 py-3.5 text-xs text-slate-500" x-text="log.notes || '—'"></td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-right text-xs">
                                        <form
                                            method="POST"
                                            :action="log.destroy_url"
                                            @submit.prevent="submitExerciseForm($el, { confirmText: 'Delete this workout entry?' })"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="view_date" :value="selectedDate">
                                            <button type="submit" class="font-semibold text-rose-500 hover:text-rose-700">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="recentLogs.length === 0" @if (!empty($recentLogs)) x-cloak @endif>
                                <td colspan="7" class="px-6 py-10 text-center text-xs text-slate-400">
                                    No workouts recorded in the last 30 days. Log your first activity above!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
