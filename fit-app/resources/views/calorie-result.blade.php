<!-- resources/views/calorie-result.blade.php -->

<x-guest-layout>
    <x-slot name="header">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">Your Calorie Result</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Here is your estimated daily calorie target.</p>
        </div>
    </x-slot>

    <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-500 p-[1px] shadow-2xl">
        <div class="rounded-2xl bg-white p-8 text-center dark:bg-gray-900">
            <p class="text-sm uppercase tracking-[0.3em] text-gray-500 dark:text-gray-400">Daily Recommendation</p>
            <p class="mt-4 text-5xl font-black text-emerald-600 dark:text-emerald-300">{{ $calorieIntake }}</p>
            <p class="mt-2 text-gray-700 dark:text-gray-300">calories per day</p>

            <div class="mt-8">
                <a href="{{ route('calorie-intake') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 dark:bg-emerald-400 dark:text-gray-900 dark:hover:bg-emerald-300">
                    {{ __('Calculate Again') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
