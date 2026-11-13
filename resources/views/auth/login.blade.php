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
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect width="20" height="16" x="2" y="4" rx="2" />
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                </svg>
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
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
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
