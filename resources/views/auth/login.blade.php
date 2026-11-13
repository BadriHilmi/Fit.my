<x-auth-layout
    title="Sign in — Fit.my"
    heading="Welcome back"
    subheading="Sign in to track your daily calories, log meals, and review saved summaries."
>
    @if (session('status'))
        <x-alert variant="success" class="mb-5">
            {{ session('status') }}
        </x-alert>
    @endif

    <form
        method="POST"
        action="{{ route('login') }}"
        x-data="{ submitting: false }"
        @submit="submitting = true"
        class="space-y-5"
    >
        @csrf

        <x-input
            name="email"
            id="email"
            type="email"
            label="Email address"
            placeholder="you@example.com"
            :value="old('email')"
            :messages="$errors->get('email')"
            required
            autofocus
            autocomplete="username"
        >
            <x-slot:icon>
                <x-tabler-icon name="mail" class="h-4 w-4" />
            </x-slot:icon>
        </x-input>

        <x-input
            name="password"
            id="password"
            type="password"
            label="Password"
            placeholder="Enter your password"
            :messages="$errors->get('password')"
            :password-toggle="true"
            required
            autocomplete="current-password"
        >
            <x-slot:icon>
                <x-tabler-icon name="lock" class="h-4 w-4" />
            </x-slot:icon>
        </x-input>

        <div class="flex items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    @checked(old('remember'))
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                />
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm font-semibold text-primary-700 transition hover:text-primary-800 hover:underline focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
                >
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <x-button type="submit" variant="primary" size="lg" full-width="true" loading-expr="submitting">
            <span x-show="!submitting">{{ __('Sign in') }}</span>
            <span x-cloak x-show="submitting">{{ __('Signing in...') }}</span>
        </x-button>

        <div class="border-t border-gray-100 pt-5 text-center text-sm text-gray-600">
            <span>New to Fit.my?</span>
            <a href="{{ route('register') }}" class="ml-1 font-semibold text-primary-700 transition hover:text-primary-800 hover:underline">
                Create a free account
            </a>
        </div>

        <div class="text-center">
            <a href="{{ route('calorie-intake') }}" class="text-xs font-medium text-gray-600 transition hover:text-primary-700 hover:underline">
                Try guest calorie calculator without signing in &rarr;
            </a>
        </div>
    </form>
</x-auth-layout>
