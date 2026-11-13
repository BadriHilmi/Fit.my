<x-guest-layout>
    <x-slot name="header">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ __('Calorie Intake Calculator') }}</h1>
            <p class="text-sm text-gray-700">Estimate your recommended daily calories based on your body metrics and activity level.</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('calorie-intake.calculate') }}" class="space-y-4">
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            <x-input name="age" id="age" type="number" label="Age" min="10" max="120" :value="old('age')" :messages="$errors->get('age')" required autofocus />

            <x-input name="gender" id="gender" type="select" label="Gender" :messages="$errors->get('gender')" required>
                <option value="male" @selected(old('gender') === 'male')>Male</option>
                <option value="female" @selected(old('gender') === 'female')>Female</option>
            </x-input>

            <x-input name="weight" id="weight" type="number" label="Weight (kg)" min="20" max="350" :value="old('weight')" :messages="$errors->get('weight')" required />

            <x-input name="height" id="height" type="number" label="Height (cm)" min="80" max="260" :value="old('height')" :messages="$errors->get('height')" required />
        </div>

        <x-input name="activity_level" id="activity_level" type="select" label="Activity Level" :messages="$errors->get('activity_level')" required>
            <option value="1.2" @selected(old('activity_level') === '1.2')>Sedentary (little to no exercise)</option>
            <option value="1.375" @selected(old('activity_level') === '1.375')>Lightly active (light exercise/sports 1–3 days/week)</option>
            <option value="1.55" @selected(old('activity_level', '1.55') === '1.55')>Moderately active (moderate exercise/sports 3–5 days/week)</option>
            <option value="1.725" @selected(old('activity_level') === '1.725')>Very active (hard exercise/sports 6–7 days/week)</option>
            <option value="1.9" @selected(old('activity_level') === '1.9')>Extra active (very hard exercise/sports &amp; physical job)</option>
        </x-input>

        <x-button type="submit" variant="primary" size="lg" full-width="true">
            {{ __('Calculate Daily Calories') }}
        </x-button>

        <div class="border-t border-gray-200 pt-4">
            <p class="mb-2.5 text-center text-xs font-semibold text-gray-600">
                Ready to track your meals &amp; workouts?
            </p>
            <div class="grid grid-cols-2 gap-2.5">
                <x-button variant="secondary" size="sm" :href="route('login')" full-width="true">
                    Sign In
                </x-button>
                <x-button variant="primary" size="sm" :href="route('register')" full-width="true">
                    Sign Up
                </x-button>
            </div>
        </div>
    </form>
</x-guest-layout>
