<section>
    <header class="border-b border-sky-200/70 pb-4 dark:border-sky-900/50">
        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __('Ensure your account is using a strong, unique password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <input
                id="current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="mt-1.5 w-full rounded-xl border-slate-200 bg-white text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <div>
                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2500)"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400"
                    >
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>{{ __('Password updated.') }}</span>
                    </p>
                @endif
            </div>

            <x-button type="submit" variant="primary" size="sm">
                {{ __('Update Password') }}
            </x-button>
        </div>
    </form>
</section>
