@php
    $sampleGoal = 2100;
    $sampleMeals = [
        [
            'slot' => 'breakfast',
            'label' => 'Breakfast',
            'dish' => 'Nasi Lemak & Boiled Egg',
            'calories' => 490,
            'iconWrap' => 'bg-amber-50 text-amber-700',
        ],
        [
            'slot' => 'lunch',
            'label' => 'Lunch',
            'dish' => 'Steamed Chicken Rice',
            'calories' => 620,
            'iconWrap' => 'bg-emerald-50 text-emerald-700',
        ],
        [
            'slot' => 'dinner',
            'label' => 'Dinner',
            'dish' => 'Roti Canai & Dhal',
            'calories' => 360,
            'iconWrap' => 'bg-orange-50 text-orange-700',
        ],
    ];

    $sampleLogged = array_sum(array_column($sampleMeals, 'calories'));
    $sampleRemaining = $sampleGoal - $sampleLogged;
    $sampleUsedPct = (int) round(($sampleLogged / $sampleGoal) * 100);
    $sampleUnderGoal = $sampleLogged <= $sampleGoal;

    $sampleChartMax = 2400;
    $sampleGoalLineBottomPct = round(($sampleGoal / $sampleChartMax) * 100, 1);

    $sampleWeeklyBars = [
        ['day' => 'Mon', 'calories' => 1950, 'today' => false],
        ['day' => 'Tue', 'calories' => 2040, 'today' => false],
        ['day' => 'Wed', 'calories' => 1880, 'today' => false],
        ['day' => 'Thu', 'calories' => 2080, 'today' => false],
        ['day' => 'Fri', 'calories' => 1920, 'today' => false],
        ['day' => 'Sat', 'calories' => 2160, 'today' => false],
        ['day' => 'Sun', 'calories' => $sampleLogged, 'today' => true],
    ];

    $sampleSavedSummaries = [
        [
            'date' => 'Today (Sample)',
            'meals' => 'Nasi Lemak, Chicken Rice & Roti Canai',
            'calories' => $sampleLogged,
            'goal' => $sampleGoal,
            'underGoal' => true,
            'status' => 'Under goal',
        ],
        [
            'date' => 'Saturday (Sample)',
            'meals' => '3 meals logged',
            'calories' => 2160,
            'goal' => $sampleGoal,
            'underGoal' => false,
            'status' => '+60 kcal',
        ],
        [
            'date' => 'Friday (Sample)',
            'meals' => '3 meals logged',
            'calories' => 1920,
            'goal' => $sampleGoal,
            'underGoal' => true,
            'status' => 'Under goal',
        ],
    ];
@endphp

<x-public-layout
    title="Fit.my — Track Daily Calories & Reach Your Nutrition Goals"
    description="Calculate your personalized daily calorie target, log breakfast, lunch, and dinner, and save daily summaries in one clean nutrition workspace."
>
    {{-- 2. Hero Section (Square dot grid with soft radial fade + subtle emerald glow) --}}
    <section class="relative overflow-hidden">
        <x-dot-grid class="z-0" />
        <div class="relative z-10 mx-auto grid max-w-7xl gap-12 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-12 lg:items-center lg:gap-12 lg:px-8 lg:py-24">
            <div class="lg:col-span-6">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#00d4a4]/30 bg-white/85 px-3.5 py-1 text-xs font-semibold text-[#008f6e] shadow-sm backdrop-blur-sm select-none dark:bg-slate-900/80 dark:text-[#00d4a4]">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#00d4a4] opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-[#00d4a4]"></span>
                    </span>
                    Daily nutrition &amp; calorie tracking
                </span>

                <h1 class="mt-5 text-4xl font-extrabold tracking-tight text-[#0a0a0a] sm:text-5xl lg:text-6xl lg:leading-[1.08] dark:text-white">
                    Track your calories and reach your <span class="bg-gradient-to-r from-[#00d4a4] to-[#00b48a] bg-clip-text text-transparent">daily nutrition goals</span>.
                </h1>

                <p class="mt-4 max-w-xl text-base leading-relaxed text-[#5a5a5c] sm:text-lg dark:text-slate-300">
                    Set a personalized daily calorie target, log breakfast, lunch, and dinner in seconds, and save clear daily summaries.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3.5">
                    <x-button variant="primary" size="lg" :href="route('register')">
                        Get started free
                        <x-tabler-icon name="arrow-right" class="h-4 w-4" />
                    </x-button>

                    <x-button variant="secondary" size="lg" href="#calculator">
                        Try the calculator
                    </x-button>
                </div>

                <ul class="mt-7 flex flex-col gap-2.5 text-sm font-medium text-[#3a3a3c] sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-5 sm:gap-y-2 dark:text-slate-200">
                    <li class="inline-flex items-center gap-2">
                        <x-tabler-icon name="circle-check" class="h-4 w-4 shrink-0 text-[#00b48a]" />
                        <span>Free to use</span>
                    </li>
                    <li class="inline-flex items-center gap-2">
                        <x-tabler-icon name="circle-check" class="h-4 w-4 shrink-0 text-[#00b48a]" />
                        <span>No account needed to try the calculator</span>
                    </li>
                    <li class="inline-flex items-center gap-2">
                        <x-tabler-icon name="circle-check" class="h-4 w-4 shrink-0 text-[#00b48a]" />
                        <span>Personalized to your age, weight and activity</span>
                    </li>
                </ul>
            </div>

            {{-- Hero Glassmorphism Card (Flat background, subtle Apple watermark, no blur spots) --}}
            <div class="relative pt-3 lg:col-span-6">
                {{-- Only Apple-outline watermark on the entire page (softly visible behind glass card) --}}
                <x-brand-watermark mode="emblem" class="absolute -right-8 -top-6 h-72 w-72 rotate-12 text-emerald-800/[0.09] sm:h-80 sm:w-80" />

                {{-- Small Floating Glass Chip (Sample Data Indicator) --}}
                <div class="relative">
                    <div class="absolute -top-3.5 right-4 z-20 inline-flex items-center gap-2 rounded-full border border-white/85 bg-white/80 px-3 py-1.5 text-xs font-semibold text-[#0a0a0a] shadow-md ring-1 ring-[#00d4a4]/20 backdrop-blur-md">
                        <svg class="h-4 w-4 -rotate-90" viewBox="0 0 20 20" aria-hidden="true">
                            <circle cx="10" cy="10" r="7" fill="none" stroke="#e5e5e5" stroke-width="3" />
                            <circle
                                cx="10"
                                cy="10"
                                r="7"
                                fill="none"
                                stroke="#00d4a4"
                                stroke-width="3"
                                stroke-dasharray="44"
                                stroke-dashoffset="{{ round(44 * (1 - $sampleUsedPct / 100), 1) }}"
                                stroke-linecap="round"
                            />
                        </svg>
                        <span class="font-mono">Sample: {{ $sampleUsedPct }}% of daily goal</span>
                    </div>

                    <div class="relative z-10 rounded-2xl border border-white/85 bg-white/70 p-6 shadow-mockup ring-1 ring-[#00d4a4]/15 backdrop-blur-xl sm:p-7">
                        <div class="flex items-center justify-between border-b border-[#e5e5e5]/80 pb-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/80 bg-[#00d4a4]/15 backdrop-blur-sm">
                                    <x-application-logo :show-text="false" icon-size="h-6 w-6" />
                                </span>
                                <div>
                                    <p class="text-sm font-bold tracking-tight text-[#0a0a0a]">Today's Intake Overview</p>
                                    <p class="text-xs text-[#888888]">Sample daily view</p>
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full border border-[#00d4a4]/30 bg-[#00d4a4]/10 px-2.5 py-1 text-xs font-semibold text-[#008f6e] backdrop-blur-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#00d4a4]"></span>
                                On target
                            </span>
                        </div>

                        <div class="mt-5 rounded-xl border border-[#00d4a4]/25 bg-[#e6faf5]/60 p-4 backdrop-blur-md">
                            <div class="flex flex-wrap items-end justify-between gap-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-[#008f6e]">Calories logged</p>
                                    <p class="mt-1 font-mono text-3xl font-extrabold tracking-tight text-[#0a0a0a] tabular-nums">
                                        {{ number_format($sampleLogged) }}
                                        <span class="text-sm font-semibold text-[#5a5a5c]">/ {{ number_format($sampleGoal) }} kcal</span>
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full border border-[#00d4a4]/30 bg-white/85 px-3 py-1 font-mono text-xs font-semibold text-[#008f6e] backdrop-blur-sm">
                                    {{ number_format($sampleRemaining) }} kcal left
                                </span>
                            </div>

                            <div class="mt-3.5 h-2.5 w-full overflow-hidden rounded-full bg-gray-900/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#00d4a4] to-[#00b48a]" style="width: {{ min($sampleUsedPct, 100) }}%;"></div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2.5">
                            @foreach ($sampleMeals as $meal)
                                <div class="flex items-center justify-between rounded-xl border border-white/80 bg-white/70 px-4 py-3 shadow-subtle ring-1 ring-[#e5e5e5]/80 backdrop-blur-md transition hover:border-[#00d4a4]/30">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $meal['iconWrap'] }}">
                                            <x-meal-slot-icon :meal-slot="$meal['slot']" class="h-5 w-5" />
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-[#0a0a0a]">{{ $meal['label'] }}</p>
                                            <p class="text-xs text-[#5a5a5c]">{{ $meal['dish'] }}</p>
                                        </div>
                                    </div>
                                    <span class="font-mono text-sm font-bold text-[#0a0a0a] tabular-nums">{{ number_format($meal['calories']) }} kcal</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Try It Now (Guest Calorie Calculator) --}}
    <section id="calculator" class="scroll-mt-20 bg-surface-mint/45 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                eyebrow="Try it now"
                title="Calculate your daily calorie target"
                subtitle="Enter your metrics to see your personalized daily goal right away."
                class="max-w-xl"
            />

            <div class="mt-10">
                <x-calorie-calculator :show-guest-prompt="true" />
            </div>
        </div>
    </section>

    {{-- 4. Features Section --}}
    <section id="features" class="scroll-mt-20 bg-white/45 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                eyebrow="Features"
                title="Everything you need to stay consistent"
                subtitle="Simple tools built around everyday meals, personalized daily goals, and transparent progress."
            />

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <x-feature-card
                    tone="primary"
                    title="Calorie calculator"
                    description="Compute your resting BMR and daily activity-adjusted energy needs in seconds."
                >
                    <x-slot:icon>
                        <x-tabler-icon name="calculator" class="h-5 w-5" />
                    </x-slot:icon>
                </x-feature-card>

                <x-feature-card
                    tone="primary"
                    title="Personalized goal"
                    description="Keep a daily calorie target tailored to your body metrics so you always know how much room remains."
                >
                    <x-slot:icon>
                        <x-tabler-icon name="target" class="h-5 w-5" />
                    </x-slot:icon>
                </x-feature-card>

                <x-feature-card
                    tone="primary"
                    title="Curated meal library"
                    description="Browse breakfast, lunch, and dinner options complete with calorie counts, descriptions, and nutrition grades."
                >
                    <x-slot:icon>
                        <x-tabler-icon name="book" class="h-5 w-5" />
                    </x-slot:icon>
                </x-feature-card>

                <x-feature-card
                    tone="primary"
                    title="Daily meal logging"
                    description="Add meals to breakfast, lunch, or dinner and watch your daily intake update immediately against your target."
                >
                    <x-slot:icon>
                        <x-tabler-icon name="calendar-check" class="h-5 w-5" />
                    </x-slot:icon>
                </x-feature-card>

                <x-feature-card
                    tone="primary"
                    title="Daily summary"
                    description="Save your daily calorie totals and meal breakdown to review past days and track steady habits."
                >
                    <x-slot:icon>
                        <x-tabler-icon name="chart-bar" class="h-5 w-5" />
                    </x-slot:icon>
                </x-feature-card>

                <x-feature-card
                    tone="primary"
                    title="Request new meals"
                    description="Can't find your favorite dish? Submit a meal request with its calorie details for admin approval into the library."
                >
                    <x-slot:icon>
                        <x-tabler-icon name="chef-hat" class="h-5 w-5" />
                    </x-slot:icon>
                </x-feature-card>
            </div>
        </div>
    </section>

    {{-- 5. How It Works Section (Compact Horizontal Cards + Connectors, No Pill for Variety) --}}
    <section id="how-it-works" class="scroll-mt-20 bg-surface-mint/45 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Three straightforward steps each day"
                subtitle="From setting your daily target to reviewing your saved daily intake — without guesswork."
            />

            <div class="mt-10 flex flex-col items-stretch gap-2 md:flex-row md:items-center md:gap-0">
                <x-card padding="sm" hover="true" class="flex-1">
                    <div class="flex items-start gap-3.5">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0a0a0a] font-mono text-xs font-bold text-[#00d4a4] shadow-sm ring-1 ring-[#00d4a4]/30 select-none">
                            01
                        </span>
                        <div>
                            <h3 class="text-base font-semibold tracking-tight text-[#0a0a0a] dark:text-white">Calculate your goal</h3>
                            <p class="mt-1 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                                Enter your age, sex, weight, height and activity level to set a daily calorie target.
                            </p>
                        </div>
                    </div>
                </x-card>

                {{-- Connector 1 -> 2 --}}
                <div class="flex items-center justify-center py-1 text-[#00b48a] md:px-2 md:py-0" aria-hidden="true">
                    <div class="flex flex-col items-center md:hidden">
                        <span class="h-3 w-px bg-[#00d4a4]/40"></span>
                        <x-tabler-icon name="arrow-down" class="h-4 w-4" />
                    </div>
                    <div class="hidden items-center md:flex">
                        <span class="h-px w-6 bg-[#00d4a4]/40"></span>
                        <x-tabler-icon name="arrow-right" class="h-4 w-4 -ml-1" />
                    </div>
                </div>

                <x-card padding="sm" hover="true" class="flex-1">
                    <div class="flex items-start gap-3.5">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0a0a0a] font-mono text-xs font-bold text-[#00d4a4] shadow-sm ring-1 ring-[#00d4a4]/30 select-none">
                            02
                        </span>
                        <div>
                            <h3 class="text-base font-semibold tracking-tight text-[#0a0a0a] dark:text-white">Log your meals</h3>
                            <p class="mt-1 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                                Pick dishes across breakfast, lunch, and dinner slots as you go through your day.
                            </p>
                        </div>
                    </div>
                </x-card>

                {{-- Connector 2 -> 3 --}}
                <div class="flex items-center justify-center py-1 text-[#00b48a] md:px-2 md:py-0" aria-hidden="true">
                    <div class="flex flex-col items-center md:hidden">
                        <span class="h-3 w-px bg-[#00d4a4]/40"></span>
                        <x-tabler-icon name="arrow-down" class="h-4 w-4" />
                    </div>
                    <div class="hidden items-center md:flex">
                        <span class="h-px w-6 bg-[#00d4a4]/40"></span>
                        <x-tabler-icon name="arrow-right" class="h-4 w-4 -ml-1" />
                    </div>
                </div>

                <x-card padding="sm" hover="true" class="flex-1">
                    <div class="flex items-start gap-3.5">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0a0a0a] font-mono text-xs font-bold text-[#00d4a4] shadow-sm ring-1 ring-[#00d4a4]/30 select-none">
                            03
                        </span>
                        <div>
                            <h3 class="text-base font-semibold tracking-tight text-[#0a0a0a] dark:text-white">Review your day</h3>
                            <p class="mt-1 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                                Compare intake against your goal and save daily summaries for future check-ins.
                            </p>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </section>

    {{-- 6. Preview Section (Shared Sample Totals + Weekly Bar Chart & Saved Daily Summaries) --}}
    <section id="preview" class="scroll-mt-20 bg-white/45 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                eyebrow="Dashboard preview"
                title="See your weekly rhythm and saved summaries"
                subtitle="Track daily calorie targets alongside a weekly intake overview and saved daily history. Shown below with static sample figures."
            />

            <div class="mt-12">
                <x-card padding="lg" class="mx-auto max-w-5xl shadow-card">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#888888] dark:text-slate-400">Sample Workspace Preview</p>
                            <h3 class="mt-1 text-xl font-bold tracking-tight text-[#0a0a0a] dark:text-white">Daily &amp; Weekly Nutrition Summary</h3>
                        </div>
                        <span class="inline-flex items-center rounded-full border border-[#e5e5e5] bg-[#f7f7f8] px-3 py-1 font-mono text-xs font-semibold text-[#5a5a5c] dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                            Static sample data
                        </span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <x-stat-card
                            label="Daily Goal"
                            :value="number_format($sampleGoal) . ' kcal'"
                            subtext="Personalized target"
                            badge="Target"
                            tone="primary"
                        >
                            <x-slot:icon>
                                <x-tabler-icon name="target" class="h-5 w-5 text-[#00b48a]" />
                            </x-slot:icon>
                        </x-stat-card>

                        <x-stat-card
                            label="Actual Intake"
                            :value="number_format($sampleLogged) . ' kcal'"
                            subtext="{{ count($sampleMeals) }} Malaysian meals logged"
                            badge="{{ $sampleUsedPct }}% used"
                            :tone="$sampleUnderGoal ? 'primary' : 'warning'"
                        >
                            <x-slot:icon>
                                <x-tabler-icon name="flame" class="h-5 w-5 text-[#00b48a]" />
                            </x-slot:icon>
                        </x-stat-card>

                        <x-stat-card
                            label="Remaining"
                            :value="number_format($sampleRemaining) . ' kcal'"
                            subtext="Under daily goal"
                            badge="On track"
                            tone="primary"
                        >
                            <x-slot:icon>
                                <x-tabler-icon name="sparkles" class="h-5 w-5 text-[#00b48a]" />
                            </x-slot:icon>
                        </x-stat-card>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-12">
                        {{-- Weekly Calorie Bar Chart (HTML/Tailwind) --}}
                        <div class="rounded-xl border border-[#e5e5e5] bg-[#f7f7f8] p-5 lg:col-span-7 dark:bg-slate-800/80 dark:border-slate-700">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <h4 class="text-sm font-bold tracking-tight text-[#0a0a0a] dark:text-white">Weekly Calorie Intake</h4>
                                    <p class="text-xs text-[#5a5a5c] dark:text-slate-300">Daily totals vs {{ number_format($sampleGoal) }} kcal goal line</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-[#5a5a5c] dark:text-slate-300">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-3 border-t-2 border-dashed border-[#00b48a]"></span>
                                        Goal ({{ number_format($sampleGoal) }})
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-sm bg-[#00b48a]"></span>
                                        Today
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-sm bg-[#00d4a4]"></span>
                                        Under goal
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-sm bg-amber-400"></span>
                                        Over goal
                                    </span>
                                </div>
                            </div>

                            <div class="relative mt-6 h-52 pt-8">
                                {{-- Dashed Goal Reference Line --}}
                                <div
                                    class="pointer-events-none absolute inset-x-0 z-10 border-t-2 border-dashed border-[#00b48a]/70"
                                    style="bottom: {{ $sampleGoalLineBottomPct }}%;"
                                ></div>

                                <div class="grid h-full grid-cols-7 items-end gap-2 sm:gap-3">
                                    @foreach ($sampleWeeklyBars as $bar)
                                        @php
                                            $barHeightPct = min(100, max(12, round(($bar['calories'] / $sampleChartMax) * 100)));
                                            $isOver = $bar['calories'] > $sampleGoal;
                                            $barColor = $bar['today']
                                                ? 'bg-[#00b48a]'
                                                : ($isOver ? 'bg-amber-400' : 'bg-[#00d4a4]');
                                        @endphp
                                        <div class="flex h-full flex-col items-center justify-end gap-1.5">
                                            <span class="font-mono text-[10px] font-semibold text-[#5a5a5c] tabular-nums dark:text-slate-400">
                                                {{ number_format($bar['calories']) }}
                                            </span>
                                            <div class="flex h-full w-full items-end justify-center">
                                                <div
                                                    class="w-full max-w-[30px] rounded-t-md {{ $barColor }} transition-all"
                                                    style="height: {{ $barHeightPct }}%;"
                                                    title="{{ $bar['day'] }}: {{ number_format($bar['calories']) }} kcal"
                                                ></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-2 grid grid-cols-7 gap-2 border-t border-[#ededed] pt-2 text-center sm:gap-3 dark:border-slate-700">
                                @foreach ($sampleWeeklyBars as $bar)
                                    <span class="font-mono text-xs {{ $bar['today'] ? 'font-bold text-[#008f6e] dark:text-[#00d4a4]' : 'font-medium text-[#5a5a5c] dark:text-slate-400' }}">
                                        {{ $bar['day'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Saved Daily Summaries List --}}
                        <div class="rounded-xl border border-[#e5e5e5] bg-white p-5 lg:col-span-5 dark:bg-slate-900/90 dark:border-slate-700">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <h4 class="text-sm font-bold tracking-tight text-[#0a0a0a] dark:text-white">Saved Daily Summaries</h4>
                                    <p class="text-xs text-[#5a5a5c] dark:text-slate-400">Recent history snapshot</p>
                                </div>
                                <span class="font-mono text-xs font-semibold text-[#008f6e] dark:text-[#00d4a4]">Reports</span>
                            </div>

                            <div class="mt-4 space-y-3">
                                @foreach ($sampleSavedSummaries as $summary)
                                    <div class="rounded-xl border border-[#ededed] bg-[#f7f7f8] p-3.5 transition hover:border-[#00d4a4]/30 dark:bg-slate-800/70 dark:border-slate-700">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-xs font-bold text-[#0a0a0a] dark:text-white">{{ $summary['date'] }}</p>
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 font-mono text-[11px] font-semibold {{ $summary['underGoal'] ? 'bg-[#00d4a4]/15 text-[#008f6e] dark:text-[#00d4a4]' : 'bg-amber-50 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                                {{ $summary['status'] }}
                                            </span>
                                        </div>
                                        <p class="mt-1 truncate text-xs text-[#5a5a5c] dark:text-slate-300">{{ $summary['meals'] }}</p>
                                        <p class="mt-2 font-mono text-sm font-bold text-[#0a0a0a] tabular-nums dark:text-white">
                                            {{ number_format($summary['calories']) }}
                                            <span class="text-xs font-medium text-[#888888]">/ {{ number_format($summary['goal']) }} kcal</span>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </section>

    {{-- 7. FAQ Section (No Pill for Visual Variety) --}}
    <section id="faq" class="scroll-mt-20 bg-surface-mint/45 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                title="Common questions"
                subtitle="Clear answers about how Fit.my calculates targets, tracks meals, and manages meal requests."
            />

            @php
                $faqs = [
                    [
                        'q' => 'Is Fit.my free to use?',
                        'a' => 'Yes. Creating an account, calculating your daily calorie target, logging breakfast, lunch, and dinner meals, and saving daily summaries are completely free.',
                    ],
                    [
                        'q' => 'How is my calorie goal calculated?',
                        'a' => 'Fit.my estimates your Basal Metabolic Rate (BMR) using the Harris-Benedict equation from your age, gender, weight, and height, then multiplies it by your activity level factor.',
                    ],
                    [
                        'q' => 'Can I try the calorie calculator without signing up?',
                        'a' => 'Yes. The guest calculator on this page computes your estimated daily calorie target live without creating an account or saving anything to the database.',
                    ],
                    [
                        'q' => 'Can I add my own meals if a dish is missing?',
                        'a' => 'Yes. Signed-in users can submit a meal request with the dish name, description, calorie count, and category. Admins review submissions before adding them to the shared meal library.',
                    ],
                    [
                        'q' => 'How does daily meal logging and summary saving work?',
                        'a' => 'You can add dishes to breakfast, lunch, or dinner throughout the day to compare your total calories against your goal, and save your daily summary to review later.',
                    ],
                ];
            @endphp

            <div x-data="{ openIndex: 0 }" class="mt-10 space-y-3">
                @foreach ($faqs as $index => $faq)
                    <div class="overflow-hidden rounded-xl border border-[#e5e5e5] bg-white shadow-subtle dark:border-slate-800 dark:bg-slate-900/90">
                        <h3>
                            <button
                                type="button"
                                @click="openIndex = openIndex === {{ $index }} ? null : {{ $index }}"
                                :aria-expanded="(openIndex === {{ $index }}).toString()"
                                aria-controls="faq-panel-{{ $index }}"
                                class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left text-sm font-semibold text-[#0a0a0a] transition hover:bg-[#f7f7f8] focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#00d4a4] sm:px-6 sm:text-base dark:text-white dark:hover:bg-slate-800"
                            >
                                <span>{{ $faq['q'] }}</span>
                                <x-tabler-icon
                                    name="chevron-down"
                                    class="h-5 w-5 shrink-0 text-[#888888] transition-transform duration-200"
                                    x-bind:class="openIndex === {{ $index }} ? 'rotate-180 text-[#00b48a]' : ''"
                                />
                            </button>
                        </h3>

                        <div
                            id="faq-panel-{{ $index }}"
                            x-cloak
                            x-show="openIndex === {{ $index }}"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="border-t border-[#ededed] bg-[#f7f7f8] px-5 pb-5 pt-3.5 text-sm leading-relaxed text-[#5a5a5c] sm:px-6 dark:border-slate-800 dark:bg-slate-850 dark:text-slate-300"
                        >
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Single Standalone Heartbeat Pulse Divider Before Final CTA (Never Overlaps Text) --}}
    <div class="bg-white/45 pt-8" aria-hidden="true">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <x-brand-watermark mode="pulse-line" class="h-10 w-full text-[#00d4a4]/60" />
        </div>
    </div>

    {{-- 8. Final CTA Banner --}}
    <section class="bg-white/45 py-12 sm:py-16 lg:pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl border border-[#00d4a4]/25 bg-[#0a0a0a] px-6 py-12 text-center shadow-mockup sm:px-12 sm:py-16">
                <x-dot-grid :invert="true" mask="mask-radial-fade-center" :glow="true" glow-color="brand" class="z-0" />
                <div class="relative z-10 mx-auto max-w-2xl">
                    <h2 class="text-2xl font-bold tracking-tight text-white sm:text-4xl">
                        Ready to keep every day aligned with your calorie goal?
                    </h2>
                    <p class="mt-3.5 text-base text-gray-300 sm:text-lg">
                        Create your free Fit.my account to save your personalized target, log meals across the day, and review your daily summaries.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                        <x-button variant="mint" size="lg" :href="route('register')">
                            Sign up free
                        </x-button>
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-white/90 underline-offset-4 transition hover:text-[#00d4a4] hover:underline">
                            Sign in to existing account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
