<x-guest-layout>
    <x-slot name="header">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ __('Calorie Intake Calculator') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Estimate your recommended daily calories based on your body metrics and activity level.</p>
        </div>
    </x-slot>

    <div class="rounded-2xl bg-gradient-to-br from-emerald-500 via-lime-500 to-teal-500 p-[1px] shadow-2xl">
        <div class="rounded-2xl bg-white p-6 dark:bg-gray-900 sm:p-8">
            <form method="POST" action="{{ route('calorie-intake.calculate') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="age" class="block font-medium text-gray-700 dark:text-gray-200">{{ __('Age') }}</label>
                    <input id="age" class="block mt-1 w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" type="number" name="age" value="{{ old('age') }}" required autofocus />
                </div>
                <div>
                    <label for="weight" class="block font-medium text-gray-700 dark:text-gray-200">{{ __('Weight (kg)') }}</label>
                    <input id="weight" class="block mt-1 w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" type="number" name="weight" value="{{ old('weight') }}" required />
                </div>

                <div>
                    <label for="height" class="block font-medium text-gray-700 dark:text-gray-200">{{ __('Height (cm)') }}</label>
                    <input id="height" class="block mt-1 w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" type="number" name="height" value="{{ old('height') }}" required />
                </div>

                <div>
                    <label for="gender" class="block font-medium text-gray-700 dark:text-gray-200">{{ __('Gender') }}</label>
                    <select id="gender" name="gender" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div>
                    <label for="activity_level" class="block font-medium text-gray-700 dark:text-gray-200">{{ __('Activity Level') }}</label>
                    <select id="activity_level" name="activity_level" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                        <option value="1.2">Sedentary (little to no exercise)</option>
                        <option value="1.375">Lightly active (light exercise/sports 1-3 days/week)</option>
                        <option value="1.55">Moderately active (moderate exercise/sports 3-5 days/week)</option>
                        <option value="1.725">Very active (hard exercise/sports 6-7 days/week)</option>
                        <option value="1.9">Extra active (very hard exercise/sports and physical job)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button class="bg-emerald-600 hover:bg-emerald-500 focus:bg-emerald-500 dark:bg-emerald-400 dark:text-gray-900 dark:hover:bg-emerald-300">
                        {{ __('Calculate') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
