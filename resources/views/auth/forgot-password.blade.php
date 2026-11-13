<x-auth-layout
    title="Forgot password — Fit.my"
    heading="Reset your password"
    subheading="Enter your registered email address and we will send you a password reset link."
>
    @if (session('status'))
        <x-alert variant="success" class="mb-5">
            {{ session('status') }}
        </x-alert>
    @endif

    <form
        method="POST"
        action="{{ route('password.email') }}"
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
        />

        <x-button type="submit" variant="primary" size="lg" full-width="true" loading-expr="submitting">
            <span x-show="!submitting">{{ __('Email password reset link') }}</span>
            <span x-cloak x-show="submitting">{{ __('Sending link...') }}</span>
        </x-button>

        <div class="border-t border-gray-100 pt-4 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="font-semibold text-primary-700 transition hover:text-primary-800 hover:underline">
                &larr; Back to sign in
            </a>
        </div>
    </form>
</x-auth-layout>
