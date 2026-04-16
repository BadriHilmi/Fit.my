<x-guest-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">Create Your Account</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Set up your profile and calorie targets to get started.</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

         <!--Profile picture -->
        <div>
            <x-input-label for="image" :value="__('PROFILE PICTURE')" />
            <input id="image" class="block mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm" type="file" name="image" required />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Age -->
        <div class="mt-4">
            <x-input-label for="age" :value="__('Age')" />
            <x-text-input id="age" class="block mt-1 w-full" type="number" name="age" :value="old('age')" />
            <x-input-error :messages="$errors->get('age')" class="mt-2" />
        </div>
         <!-- Height -->
         <div class="mt-4">
            <x-input-label for="height" :value="__('Height')" />
            <x-text-input id="height" class="block mt-1 w-full" type="number" name="height" :value="old('height')" />
            <x-input-error :messages="$errors->get('height')" class="mt-2" />
        </div>
         <!-- Weight -->
         <div class="mt-4">
            <x-input-label for="weight" :value="__('Weight')" />
            <x-text-input id="weight" class="block mt-1 w-full" type="number" name="weight" :value="old('weight')" />
            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <div class="mt-2 flex gap-4">
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input id="gender_male" type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }}>
                    <span>Male</span>
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input id="gender_female" type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }}>
                    <span>Female</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>
        <!-- activity -->

        <div class="mt-4">
            <x-input-label name="activity_level" for="activity_level" :value="__('Activity Level')" />
            <select id="activity_level" name="activity_level" class="block appearance-none w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="1.2">Sedentary (little to no exercise)</option>
                <option value="1.375">Lightly active (light exercise/sports 1-3 days/week)</option>
                <option value="1.55">Moderately active (moderate exercise/sports 3-5 days/week)</option>
                <option value="1.725">Very active (hard exercise/sports 6-7 days/week)</option>
                <option value="1.9">Extra active (very hard exercise/sports and physical job)</option>
            </select>
            <x-input-error :messages="$errors->get('activity_level')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>

        </div>
    </form>
</x-guest-layout>
