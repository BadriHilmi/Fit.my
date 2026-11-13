@props([
    'showGuestPrompt' => true,
])

@php
    $sessionResult = session('guest_calorie_result');
    $serverErrors = $errors->getMessages();
@endphp

<div
    x-data="{
        loading: false,
        result: @js($sessionResult),
        errorMessage: '',
        fieldErrors: @js($serverErrors),
        async submitCalculation() {
            this.loading = true;
            this.errorMessage = '';
            this.fieldErrors = {};

            const form = this.$refs.calcForm;
            const formData = new FormData(form);

            try {
                const tokenMeta = document.querySelector('meta[name=csrf-token]');
                const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : (formData.get('_token') || '');

                const response = await fetch('{{ route('calorie-intake.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                if (response.status === 422) {
                    const data = await response.json();
                    this.fieldErrors = data.errors || {};
                    this.errorMessage = data.message || 'Please fix the highlighted fields and try again.';
                    return;
                }

                if (response.status === 419) {
                    form.submit();
                    return;
                }

                if (!response.ok) {
                    throw new Error('Unable to calculate right now.');
                }

                this.result = await response.json();
            } catch (err) {
                this.errorMessage = 'Could not complete the calculation right now. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }"
    class="grid gap-6 lg:grid-cols-12 lg:items-stretch"
>
    <x-card padding="lg" class="lg:col-span-7">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold tracking-tight text-[#0a0a0a] dark:text-white">Your body metrics</h3>
                <p class="mt-0.5 text-sm text-[#5a5a5c] dark:text-slate-300">Uses your age, sex, weight, height and activity level.</p>
            </div>
            <span class="hidden rounded-full border border-[#00d4a4]/30 bg-[#00d4a4]/10 px-3 py-1 text-xs font-semibold text-[#008f6e] sm:inline-flex dark:text-[#00d4a4]">
                No account needed
            </span>
        </div>

        <template x-if="errorMessage">
            <div class="mb-5 rounded-xl border border-[#d45656]/30 bg-red-50/80 px-4 py-3 text-sm font-medium text-[#d45656]" role="alert">
                <span x-text="errorMessage"></span>
            </div>
        </template>

        <form
            x-ref="calcForm"
            method="POST"
            action="{{ route('calorie-intake.calculate') }}"
            novalidate
            @submit.prevent="submitCalculation"
            class="space-y-4"
        >
            @csrf
            <input type="hidden" name="redirect_back" value="1" />

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input
                        name="age"
                        id="calc-age"
                        type="number"
                        label="Age (years)"
                        placeholder="e.g. 26"
                        min="10"
                        max="120"
                        :value="old('age')"
                        error-expr="fieldErrors.age"
                        required
                    />
                    <p x-cloak x-show="fieldErrors.age" x-text="fieldErrors.age ? fieldErrors.age[0] : ''" class="mt-1.5 text-xs font-medium text-red-600"></p>
                </div>

                <div>
                    <x-input
                        name="gender"
                        id="calc-gender"
                        type="select"
                        label="Gender"
                        error-expr="fieldErrors.gender"
                        required
                    >
                        <option value="male" @selected(old('gender', 'male') === 'male')>Male</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                    </x-input>
                    <p x-cloak x-show="fieldErrors.gender" x-text="fieldErrors.gender ? fieldErrors.gender[0] : ''" class="mt-1.5 text-xs font-medium text-red-600"></p>
                </div>

                <div>
                    <x-input
                        name="weight"
                        id="calc-weight"
                        type="number"
                        step="0.1"
                        label="Weight (kg)"
                        placeholder="e.g. 68"
                        min="20"
                        max="350"
                        :value="old('weight')"
                        error-expr="fieldErrors.weight"
                        required
                    />
                    <p x-cloak x-show="fieldErrors.weight" x-text="fieldErrors.weight ? fieldErrors.weight[0] : ''" class="mt-1.5 text-xs font-medium text-red-600"></p>
                </div>

                <div>
                    <x-input
                        name="height"
                        id="calc-height"
                        type="number"
                        step="0.1"
                        label="Height (cm)"
                        placeholder="e.g. 170"
                        min="80"
                        max="260"
                        :value="old('height')"
                        error-expr="fieldErrors.height"
                        required
                    />
                    <p x-cloak x-show="fieldErrors.height" x-text="fieldErrors.height ? fieldErrors.height[0] : ''" class="mt-1.5 text-xs font-medium text-red-600"></p>
                </div>
            </div>

            <div>
                <x-input
                    name="activity_level"
                    id="calc-activity-level"
                    type="select"
                    label="Activity level"
                    error-expr="fieldErrors.activity_level"
                    required
                >
                    <option value="1.2" @selected(old('activity_level') == '1.2')>Sedentary (little to no exercise)</option>
                    <option value="1.375" @selected(old('activity_level') == '1.375')>Lightly active (light exercise 1–3 days/week)</option>
                    <option value="1.55" @selected(old('activity_level', '1.55') == '1.55')>Moderately active (moderate exercise 3–5 days/week)</option>
                    <option value="1.725" @selected(old('activity_level') == '1.725')>Very active (hard exercise 6–7 days/week)</option>
                    <option value="1.9" @selected(old('activity_level') == '1.9')>Extra active (intense daily training or physical job)</option>
                </x-input>
                <p x-cloak x-show="fieldErrors.activity_level" x-text="fieldErrors.activity_level ? fieldErrors.activity_level[0] : ''" class="mt-1.5 text-xs font-medium text-red-600"></p>
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" size="lg" full-width="true" loading-expr="loading">
                    <span x-show="!loading">Calculate daily calorie goal</span>
                    <span x-cloak x-show="loading">Calculating...</span>
                </x-button>
            </div>
        </form>
    </x-card>

    <div class="lg:col-span-5">
        <x-card padding="lg" class="flex h-full flex-col justify-between transition-all" x-bind:class="result ? 'border-2 border-[#00d4a4] shadow-[0_8px_24px_rgba(0,212,164,0.08)]' : ''">
            <template x-if="result">
                <div class="flex h-full flex-col justify-between gap-5">
                    <div>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00d4a4]/30 bg-[#00d4a4]/10 px-3 py-1 text-xs font-semibold text-[#008f6e] dark:text-[#00d4a4]">
                            <x-tabler-icon name="flame" class="h-3.5 w-3.5 text-[#00b48a]" />
                            Activity-adjusted daily target
                        </span>

                        <div class="mt-4 flex items-baseline gap-2">
                            <span
                                class="font-mono text-4xl font-extrabold tracking-tight text-[#0a0a0a] tabular-nums sm:text-5xl dark:text-white"
                                x-text="Number(result.calorieIntake).toLocaleString()"
                            ></span>
                            <span class="text-sm font-semibold text-[#008f6e] dark:text-[#00d4a4]">kcal / day</span>
                        </div>

                        <p class="mt-2 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                            Based on your age, sex, weight, height and activity level. This target is not saved to the database for guests.
                        </p>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-xl border border-[#e5e5e5] bg-[#f7f7f8] p-3.5 dark:bg-slate-800/80 dark:border-slate-700">
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#888888] dark:text-slate-400">Estimated BMR</p>
                                <p class="mt-1 font-mono text-lg font-bold text-[#0a0a0a] tabular-nums dark:text-white">
                                    <span x-text="Number(result.bmr).toLocaleString()"></span>
                                    <span class="text-xs font-medium text-[#888888]">kcal</span>
                                </p>
                            </div>
                            <div class="rounded-xl border border-[#e5e5e5] bg-[#f7f7f8] p-3.5 dark:bg-slate-800/80 dark:border-slate-700">
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#888888] dark:text-slate-400">Activity Multiplier</p>
                                <p class="mt-1 font-mono text-lg font-bold text-[#0a0a0a] tabular-nums dark:text-white">
                                    <span x-text="result.activity_level"></span>&times;
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($showGuestPrompt)
                        <div class="rounded-xl border border-[#00d4a4]/30 bg-[#e6faf5]/60 p-4 dark:bg-emerald-950/30 dark:border-emerald-800/50">
                            <p class="text-sm font-semibold text-[#0a0a0a] dark:text-white">
                                Sign up to save your goal and start tracking meals.
                            </p>
                            <p class="mt-1 text-xs leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                                Keep your daily calorie target ready whenever you log breakfast, lunch, or dinner.
                            </p>
                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <x-button variant="primary" size="sm" :href="route('register')">
                                    Sign up to save your goal
                                </x-button>
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#0a0a0a] transition hover:text-[#00b48a] hover:underline dark:text-slate-200">
                                    Sign in
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </template>

            <template x-if="!result">
                <div class="flex h-full flex-col justify-between gap-5">
                    <div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#00d4a4]/10 text-[#00b48a] border border-[#00d4a4]/25">
                            <x-tabler-icon name="clipboard-check" class="h-5 w-5" />
                        </div>

                        <h4 class="mt-4 text-xl font-bold tracking-tight text-[#0a0a0a] dark:text-white">Your daily target awaits</h4>
                        <p class="mt-2 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                            Based on your age, sex, weight, height and activity level, you'll get a clear daily calorie target and resting BMR estimate.
                        </p>

                        <ul class="mt-5 space-y-2.5 text-sm text-[#3a3a3c] dark:text-slate-200">
                            <li class="flex items-center gap-2.5">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00d4a4]/15 text-[#00b48a]">
                                    <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                                </span>
                                Instant calorie estimate right on this page
                            </li>
                            <li class="flex items-center gap-2.5">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00d4a4]/15 text-[#00b48a]">
                                    <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                                </span>
                                Shows both BMR and activity-adjusted target
                            </li>
                            <li class="flex items-center gap-2.5">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00d4a4]/15 text-[#00b48a]">
                                    <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                                </span>
                                Guests never save data to the database
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs text-[#888888]">
                            Calculated using the Harris-Benedict BMR equation adjusted by your weekly activity factor.
                        </p>

                        @if ($showGuestPrompt)
                            <div class="rounded-xl border border-[#e5e5e5] bg-[#f7f7f8] px-4 py-3 text-xs text-[#5a5a5c] dark:bg-slate-800/80 dark:border-slate-700 dark:text-slate-300">
                                Ready to track daily meals against your goal?
                                <a href="{{ route('register') }}" class="font-semibold text-[#008f6e] hover:underline dark:text-[#00d4a4]">Create a free account</a>.
                            </div>
                        @endif
                    </div>
                </div>
            </template>
        </x-card>
    </div>
</div>
