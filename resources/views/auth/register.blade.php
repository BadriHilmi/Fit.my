<x-auth-layout
    title="Create account — Fit.my"
    heading="Create your account"
    subheading="Set up your profile and personalized daily calorie goal to start tracking."
    max-width="max-w-xl"
>
    <form
        method="POST"
        action="{{ route('register') }}"
        enctype="multipart/form-data"
        x-data="{ submitting: false, password: '', password_confirmation: '' }"
        @submit="submitting = true"
        class="space-y-4"
    >
        @csrf

        {{-- Profile Picture --}}
        <div>
            <label for="image" class="mb-1.5 block text-sm font-medium text-gray-700">
                Profile picture <span class="text-red-600" aria-hidden="true">*</span>
            </label>
            <input
                id="image"
                type="file"
                name="image"
                accept="image/*"
                required
                class="block w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
            <x-input-error :messages="$errors->get('image')" class="mt-1.5 text-xs font-medium text-red-600" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-input
                name="name"
                id="name"
                type="text"
                label="Full name"
                placeholder="e.g. Aina Sofea"
                :value="old('name')"
                :messages="$errors->get('name')"
                required
                autofocus
                autocomplete="name"
            />

            <x-input
                name="email"
                id="email"
                type="email"
                label="Email address"
                placeholder="you@example.com"
                :value="old('email')"
                :messages="$errors->get('email')"
                required
                autocomplete="username"
            />
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-input
                name="age"
                id="age"
                type="number"
                label="Age"
                placeholder="e.g. 25"
                min="10"
                max="120"
                :value="old('age')"
                :messages="$errors->get('age')"
                required
            />

            <x-input
                name="height"
                id="height"
                type="number"
                label="Height (cm)"
                placeholder="e.g. 168"
                min="80"
                max="260"
                :value="old('height')"
                :messages="$errors->get('height')"
                required
            />

            <x-input
                name="weight"
                id="weight"
                type="number"
                label="Weight (kg)"
                placeholder="e.g. 64"
                min="20"
                max="350"
                :value="old('weight')"
                :messages="$errors->get('weight')"
                required
            />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <span class="mb-1.5 block text-sm font-medium text-gray-700">
                    Gender <span class="text-red-600" aria-hidden="true">*</span>
                </span>
                <div class="grid grid-cols-2 gap-2.5">
                    <label for="gender_male" class="flex cursor-pointer items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-medium text-gray-800 transition hover:border-primary-300">
                        <input
                            id="gender_male"
                            type="radio"
                            name="gender"
                            value="male"
                            @checked(old('gender', 'male') === 'male')
                            class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                        />
                        <span>Male</span>
                    </label>
                    <label for="gender_female" class="flex cursor-pointer items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-medium text-gray-800 transition hover:border-primary-300">
                        <input
                            id="gender_female"
                            type="radio"
                            name="gender"
                            value="female"
                            @checked(old('gender') === 'female')
                            class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                        />
                        <span>Female</span>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('gender')" class="mt-1.5 text-xs font-medium text-red-600" />
            </div>

            <x-input
                name="activity_level"
                id="activity_level"
                type="select"
                label="Activity level"
                :messages="$errors->get('activity_level')"
                required
            >
                <option value="1.2" @selected(old('activity_level') == '1.2')>Sedentary (little to no exercise)</option>
                <option value="1.375" @selected(old('activity_level') == '1.375')>Lightly active (1–3 days/week)</option>
                <option value="1.55" @selected(old('activity_level', '1.55') == '1.55')>Moderately active (3–5 days/week)</option>
                <option value="1.725" @selected(old('activity_level') == '1.725')>Very active (6–7 days/week)</option>
                <option value="1.9" @selected(old('activity_level') == '1.9')>Extra active (hard training / physical job)</option>
            </x-input>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-input
                name="password"
                id="password"
                type="password"
                label="Password"
                placeholder="Create a strong password"
                :messages="$errors->get('password')"
                :password-toggle="true"
                x-model="password"
                hint="Minimum 8 characters with uppercase, lowercase, numbers & symbols."
                required
                autocomplete="new-password"
            />

            <x-input
                name="password_confirmation"
                id="password_confirmation"
                type="password"
                label="Confirm password"
                placeholder="Repeat your password"
                :messages="$errors->get('password_confirmation')"
                :password-toggle="true"
                x-model="password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        <x-password-requirements password-model="password" confirmation-model="password_confirmation" />

        <p class="text-xs leading-relaxed text-gray-600">
            By creating an account, your profile metrics are used solely to compute your personalized daily calorie target under Fit.my Terms &amp; Privacy practices.
        </p>

        <x-button type="submit" variant="primary" size="lg" full-width="true" loading-expr="submitting">
            <span x-show="!submitting">{{ __('Create account') }}</span>
            <span x-cloak x-show="submitting">{{ __('Creating account...') }}</span>
        </x-button>

        <div class="border-t border-gray-100 pt-4 text-center text-sm text-gray-600">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}" class="ml-1 font-semibold text-primary-700 transition hover:text-primary-800 hover:underline">
                Sign in
            </a>
        </div>
    </form>
</x-auth-layout>
