<section class="space-y-4">
    <header>
        <h2 class="text-lg font-extrabold text-rose-600 dark:text-rose-400">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __('Once your account is deleted, all workouts, meal reports, and settings will be permanently removed.') }}
        </p>
    </header>

    <x-button
        type="button"
        variant="danger"
        size="sm"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Delete Account') }}
    </x-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>

            <div class="mt-5">
                <x-input-label for="delete_account_password" value="{{ __('Password') }}" class="sr-only" />

                <input
                    id="delete_account_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-slate-200 bg-white text-sm focus:border-rose-500 focus:ring-rose-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                    placeholder="{{ __('Enter your password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-button
                    type="button"
                    variant="secondary"
                    size="sm"
                    x-on:click="$dispatch('close')"
                >
                    {{ __('Cancel') }}
                </x-button>

                <x-button type="submit" variant="danger" size="sm">
                    {{ __('Delete Account') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</section>
