<x-auth-layout
    title="Verify email — Fit.my"
    heading="Verify your email address"
    subheading="Thanks for signing up! Click the verification link sent to your email address before getting started."
>
    @if (session('status') == 'verification-link-sent')
        <x-alert variant="success" class="mb-5">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </x-alert>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-button type="submit" variant="primary" size="md">
                {{ __('Resend verification email') }}
            </x-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit" variant="ghost" size="md">
                {{ __('Log out') }}
            </x-button>
        </form>
    </div>
</x-auth-layout>
