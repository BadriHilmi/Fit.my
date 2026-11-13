<x-auth-layout
    title="Choose new password — Fit.my"
    heading="Set a new password"
    subheading="Confirm your email address and enter a strong new password."
>
    <form
        method="POST"
        action="{{ route('password.store') }}"
        x-data="{ submitting: false }"
        @submit="submitting = true"
        class="space-y-4"
    >
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}" />

        <x-input
            name="email"
            id="email"
            type="email"
            label="Email address"
            :value="old('email', $request->email)"
            :messages="$errors->get('email')"
            required
            autofocus
            autocomplete="username"
        />

        <x-input
            name="password"
            id="password"
            type="password"
            label="New password"
            :messages="$errors->get('password')"
            :password-toggle="true"
            required
            autocomplete="new-password"
        />

        <x-input
            name="password_confirmation"
            id="password_confirmation"
            type="password"
            label="Confirm new password"
            :messages="$errors->get('password_confirmation')"
            :password-toggle="true"
            required
            autocomplete="new-password"
        />

        <div class="pt-2">
            <x-button type="submit" variant="primary" size="lg" full-width="true" loading-expr="submitting">
                <span x-show="!submitting">{{ __('Reset password') }}</span>
                <span x-cloak x-show="submitting">{{ __('Updating password...') }}</span>
            </x-button>
        </div>
    </form>
</x-auth-layout>
