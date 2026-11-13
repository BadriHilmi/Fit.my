<x-app-layout>
    <x-slot name="header">
        <div
            x-data="{
                bmi: @js($metrics['bmi']),
                bmiCategory: @js($metrics['bmi_category']),
                calorieIntake: @js((int) ($user->calorie_intake ?? 2000)),
                formatNum(n) {
                    return Number(n || 0).toLocaleString();
                }
            }"
            @fit-profile-updated.window="
                if ($event.detail?.metrics) {
                    bmi = $event.detail.metrics.bmi;
                    bmiCategory = $event.detail.metrics.bmi_category;
                }
                if ($event.detail?.user?.calorie_intake !== undefined) {
                    calorieIntake = Number($event.detail.user.calorie_intake);
                }
            "
            class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-400">
                    Personal Account
                </p>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white sm:text-3xl">
                    Profile &amp; Body Settings
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Manage your body metrics, daily calorie goal, avatar, and account security.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="fit-pill rounded-2xl px-4 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current BMI</p>
                    <p class="text-lg font-black text-slate-900 dark:text-white">
                        <span x-text="bmi !== null ? bmi : '—'">{{ $metrics['bmi'] ?? '—' }}</span>
                        <span
                            x-show="bmiCategory"
                            @if (! $metrics['bmi_category']) x-cloak @endif
                            class="ml-1 text-xs font-bold text-emerald-600 dark:text-emerald-400"
                            x-text="bmiCategory"
                        >{{ $metrics['bmi_category'] }}</span>
                    </p>
                </div>

                <div class="fit-pill rounded-2xl px-4 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Daily Calorie Target</p>
                    <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                        <span x-text="formatNum(calorieIntake)">{{ number_format((int) ($user->calorie_intake ?? 2000)) }}</span>
                        <span class="text-xs font-semibold text-slate-500">kcal/day</span>
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 transition-colors">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-12">

                <!-- LEFT: Profile Info, Body Metrics & Goal Form -->
                <div class="lg:col-span-7">
                    <div class="fit-card rounded-3xl p-6 sm:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- RIGHT: Live Health Snapshot, Security & Danger Zone -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Live Health & Activity Snapshot -->
                    <div
                        x-data="{
                            weight: @js($user->weight),
                            height: @js($user->height),
                            bmi: @js($metrics['bmi']),
                            bmiCategory: @js($metrics['bmi_category']),
                            bmr: @js($metrics['bmr']),
                            recommended: @js($metrics['recommended_calories']),
                            calorieIntake: @js((int) ($user->calorie_intake ?? 2000)),
                            formatNum(n) {
                                return Number(n || 0).toLocaleString();
                            }
                        }"
                        @fit-profile-updated.window="
                            if ($event.detail?.user) {
                                weight = $event.detail.user.weight;
                                height = $event.detail.user.height;
                                calorieIntake = Number($event.detail.user.calorie_intake);
                            }
                            if ($event.detail?.metrics) {
                                bmi = $event.detail.metrics.bmi;
                                bmiCategory = $event.detail.metrics.bmi_category;
                                bmr = $event.detail.metrics.bmr;
                                recommended = $event.detail.metrics.recommended_calories;
                            }
                        "
                        class="fit-card-emerald rounded-3xl p-6"
                    >
                        <div class="flex items-center justify-between border-b border-emerald-200/70 pb-4 dark:border-emerald-900/50">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700/80 dark:text-emerald-300/80">
                                    Connected Across Fit.my
                                </p>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">
                                    Your Body &amp; Activity Profile
                                </h3>
                            </div>
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                                <x-tabler-icon name="heart" class="h-5 w-5" />
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-white/75 p-3.5 dark:bg-slate-900/50">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Resting BMR</p>
                                <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">
                                    <span x-text="bmr ? formatNum(bmr) : '—'">{{ $metrics['bmr'] ? number_format($metrics['bmr']) : '—' }}</span>
                                    <span class="text-xs font-semibold text-slate-400">kcal</span>
                                </p>
                            </div>
                            <div class="rounded-2xl bg-white/75 p-3.5 dark:bg-slate-900/50">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">MET Weight</p>
                                <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">
                                    <span x-text="weight ? weight + ' kg' : 'Not set'">{{ $user->weight ? $user->weight . ' kg' : 'Not set' }}</span>
                                </p>
                            </div>
                            <div class="rounded-2xl bg-white/75 p-3.5 dark:bg-slate-900/50">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Logged Workouts</p>
                                <p class="mt-1 text-xl font-black text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($workoutsCount) }}
                                </p>
                            </div>
                            <div class="rounded-2xl bg-white/75 p-3.5 dark:bg-slate-900/50">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Saved Reports</p>
                                <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">
                                    {{ number_format($reportsCount) }}
                                </p>
                            </div>
                        </div>

                        <p class="mt-4 text-xs text-slate-600 dark:text-slate-300">
                            Changes to your <strong>Weight</strong> automatically update your Exercise MET calorie estimator, and your <strong>Daily Calorie Target</strong> drives the Dashboard water gauge &amp; Reports.
                        </p>

                        @if ($user->user_type !== 'admin')
                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-500">
                                    <x-tabler-icon name="tools-kitchen-2" class="h-3.5 w-3.5" />
                                    <span>Go to Meals</span>
                                </a>
                                <a href="{{ route('activity') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-white/80 px-3.5 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-white dark:bg-slate-900/70 dark:text-slate-200">
                                    <x-tabler-icon name="activity" class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" />
                                    <span>Log Exercise</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Password Update -->
                    <div class="fit-card-sky rounded-3xl p-6 sm:p-7">
                        @include('profile.partials.update-password-form')
                    </div>

                    <!-- Account Deletion -->
                    <div class="fit-card rounded-3xl p-6 sm:p-7">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
