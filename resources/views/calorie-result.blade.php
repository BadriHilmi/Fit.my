<x-guest-layout>
    <x-slot name="header">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Your Calorie Result</h1>
            <p class="text-sm text-gray-700">Here is your estimated daily calorie target.</p>
        </div>
    </x-slot>

    <div class="text-center">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-600">Daily Recommendation</p>
        <p class="mt-3 text-5xl font-black text-emerald-600 tabular-nums">{{ number_format($calorieIntake) }}</p>
        <p class="mt-1.5 text-sm font-medium text-gray-700">calories per day</p>

        <div class="mt-6 space-y-3">
            <x-button variant="primary" size="lg" :href="route('calorie-intake')" full-width="true">
                {{ __('Calculate Again') }}
            </x-button>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <x-button variant="secondary" size="sm" :href="route('login')" full-width="true">
                    Sign In
                </x-button>
                <x-button variant="primary" size="sm" :href="route('register')" full-width="true">
                    Sign Up
                </x-button>
            </div>
        </div>
    </div>
</x-guest-layout>
