<x-app-layout>
    @php
        $totalBreakfast = 0;
        foreach ((array) session('cart') as $details) {
            $totalBreakfast += $details['Calories'] * $details['quantity'];
        }
        $totalLunch = 0;
        foreach ((array) session('cart1') as $details) {
            $totalLunch += $details['Calories'] * $details['quantity'];
        }
        $totalDinner = 0;
        foreach ((array) session('cart2') as $details) {
            $totalDinner += $details['Calories'] * $details['quantity'];
        }
        $dailyTotal = $totalBreakfast + $totalLunch + $totalDinner;
        $goal = (int) (Auth::user()->calorie_intake ?? 0);
        $remaining = $goal - $dailyTotal;
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Daily Dashboard</p>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Welcome back, {{ Auth::user()->name }}</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Track meals through the day and keep your intake close to your goal.</p>
            </div>
            <div class="rounded-2xl border border-emerald-100/80 bg-white/70 px-5 py-4 shadow-sm backdrop-blur-md ring-1 ring-emerald-100/70 dark:bg-gray-800/75 dark:ring-gray-700/60">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Daily Goal</p>
                <p class="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-300">{{ $goal }}</p>
                <p class="text-sm text-gray-500">calories per day</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-emerald-50/70 via-transparent to-sky-50/70 dark:from-gray-900 dark:to-gray-900">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-3xl border border-white/60 bg-white/60 p-5 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm text-gray-500">Consumed Today</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $dailyTotal }}</p>
                    <p class="text-sm text-gray-500">calories logged</p>
                </div>
                <div class="rounded-3xl border border-white/60 bg-white/60 p-5 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm text-gray-500">Remaining</p>
                    <p class="mt-2 text-3xl font-black {{ $remaining >= 0 ? 'text-emerald-600 dark:text-emerald-300' : 'text-red-600 dark:text-red-300' }}">{{ $remaining }}</p>
                    <p class="text-sm text-gray-500">{{ $remaining >= 0 ? 'before reaching goal' : 'over your goal' }}</p>
                </div>
                <div class="rounded-3xl border border-white/60 bg-white/60 p-5 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm text-gray-500">Meals Available</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $meals->count() }}</p>
                    <p class="text-sm text-gray-500">items in meal library</p>
                </div>
                <div class="rounded-3xl border border-white/60 bg-white/60 p-5 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm text-gray-500">Quick Action</p>
                    <a href="{{ route('ManageMeal.request-meal') }}" class="mt-3 inline-flex rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">Request New Meal</a>
                </div>
            </div>

            <section class="rounded-3xl border border-white/60 bg-white/65 p-6 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-gray-500">Meal Library</p>
                        <h3 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">Browse Available Meals</h3>
                    </div>
                    <a href="{{ route('ManageMeal.request-meal') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Suggest a meal</a>
                </div>
                <div class="mt-6 overflow-x-auto">
                    @include('meals.index')
                </div>
            </section>

            <div class="grid gap-6 xl:grid-cols-3">
                <section class="rounded-3xl border border-white/60 bg-white/65 p-6 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-500">Breakfast</p>
                    <h3 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">{{ $totalBreakfast }} Calories</h3>
                    <div class="mt-5 overflow-x-auto">
                        @include('meals.breakfast')
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ((array) session('cart') as $details)
                            <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm text-gray-700 ring-1 ring-amber-100">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="font-semibold">{{ $details['Name'] }}</span>
                                    <span>{{ $details['quantity'] }} x {{ $details['Calories'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl bg-gray-50 px-4 py-3 text-sm text-gray-500 ring-1 ring-gray-100">No breakfast items added yet.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-white/60 bg-white/65 p-6 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sky-500">Lunch</p>
                    <h3 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">{{ $totalLunch }} Calories</h3>
                    <div class="mt-5 overflow-x-auto">
                        @include('meals.lunch')
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ((array) session('cart1') as $details)
                            <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sm text-gray-700 ring-1 ring-sky-100">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="font-semibold">{{ $details['Name'] }}</span>
                                    <span>{{ $details['quantity'] }} x {{ $details['Calories'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl bg-gray-50 px-4 py-3 text-sm text-gray-500 ring-1 ring-gray-100">No lunch items added yet.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-white/60 bg-white/65 p-6 shadow-sm backdrop-blur-md ring-1 ring-white/50 dark:bg-gray-800/70 dark:ring-gray-700/60">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-violet-500">Dinner</p>
                    <h3 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">{{ $totalDinner }} Calories</h3>
                    <div class="mt-5 overflow-x-auto">
                        @include('meals.dinner')
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ((array) session('cart2') as $details)
                            <div class="rounded-2xl bg-violet-50 px-4 py-3 text-sm text-gray-700 ring-1 ring-violet-100">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="font-semibold">{{ $details['Name'] }}</span>
                                    <span>{{ $details['quantity'] }} x {{ $details['Calories'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl bg-gray-50 px-4 py-3 text-sm text-gray-500 ring-1 ring-gray-100">No dinner items added yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-3xl bg-gradient-to-r from-emerald-600 to-lime-500 p-6 text-white shadow-lg">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-100">Ready For Reports</p>
                        <h3 class="mt-1 text-3xl font-black">{{ $dailyTotal }} calories logged today</h3>
                        <p class="mt-2 text-sm text-emerald-50">Save today's summary now, and we'll turn this into stronger daily and weekly reports next.</p>
                    </div>
                    <form action="{{ route('save-report') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ now() }}" />
                        <input type="hidden" name="lunch_total" value="{{ $totalLunch }}" />
                        <input type="hidden" name="breakfast_total" value="{{ $totalBreakfast }}" />
                        <input type="hidden" name="dinner_total" value="{{ $totalDinner }}" />
                        <input type="hidden" name="total" value="{{ $dailyTotal }}" />
                        <button type="submit" class="inline-flex rounded-full bg-white px-5 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                            Save Today's Summary
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
