<x-auth-layout
    title="Confirm password — Fit.my"
    heading="Confirm your password"
    subheading="This is a secure area of Fit.my. Please confirm your password before continuing."
>
    <form
        method="POST"
        action="{{ route('password.confirm') }}"
        x-data="{ submitting: false }"
        @submit="submitting = true"
        class="space-y-5"
    >
        @csrf

        <x-input
            name="password"
            id="password"
            type="password"
            label="Password"
            :messages="$errors->get('password')"
            :password-toggle="true"
            required
            autocomplete="current-password"
        />

        <x-button type="submit" variant="primary" size="lg" full-width="true" loading-expr="submitting">
            <span x-show="!submitting">{{ __('Confirm') }}</span>
            <span x-cloak x-show="submitting">{{ __('Confirming...') }}</span>
        </x-button>
    </form>
</x-auth-layout>
