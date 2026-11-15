@props([
    'passwordModel' => 'password',
    'confirmationModel' => 'password_confirmation',
    'showMatch' => true,
    'class' => '',
])

<div
    x-show="{{ $passwordModel }}.length > 0"
    x-transition
    x-cloak
    class="rounded-xl border border-slate-200/90 bg-slate-50/80 p-3.5 text-xs shadow-sm dark:border-slate-700/80 dark:bg-slate-800/80 {{ $class }}"
>
    <div class="flex items-center justify-between">
        <p class="font-bold text-slate-800 dark:text-slate-200">Password strength requirements:</p>
        <span
            class="text-[11px] font-extrabold uppercase tracking-wider"
            :class="{
                'text-emerald-600 dark:text-emerald-400': ({{ $passwordModel }}.length >= 8 && /[a-z]/.test({{ $passwordModel }}) && /[A-Z]/.test({{ $passwordModel }}) && /[0-9]/.test({{ $passwordModel }}) && /[^A-Za-z0-9]/.test({{ $passwordModel }})),
                'text-amber-600 dark:text-amber-400': !({{ $passwordModel }}.length >= 8 && /[a-z]/.test({{ $passwordModel }}) && /[A-Z]/.test({{ $passwordModel }}) && /[0-9]/.test({{ $passwordModel }}) && /[^A-Za-z0-9]/.test({{ $passwordModel }}))
            }"
            x-text="({{ $passwordModel }}.length >= 8 && /[a-z]/.test({{ $passwordModel }}) && /[A-Z]/.test({{ $passwordModel }}) && /[0-9]/.test({{ $passwordModel }}) && /[^A-Za-z0-9]/.test({{ $passwordModel }})) ? 'Strong' : 'Incomplete'"
        ></span>
    </div>

    <ul class="mt-2.5 grid gap-1.5 sm:grid-cols-2 font-medium">
        {{-- Length >= 8 --}}
        <li class="flex items-center gap-2 transition-colors" :class="{{ $passwordModel }}.length >= 8 ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-500 dark:text-slate-400'">
            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full" :class="{{ $passwordModel }}.length >= 8 ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-slate-200/80 text-slate-400 dark:bg-slate-700'">
                <x-tabler-icon name="check" class="h-3 w-3" />
            </span>
            <span>At least 8 characters</span>
        </li>

        {{-- Mixed case: Upper + Lower --}}
        <li class="flex items-center gap-2 transition-colors" :class="(/[a-z]/.test({{ $passwordModel }}) && /[A-Z]/.test({{ $passwordModel }})) ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-500 dark:text-slate-400'">
            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full" :class="(/[a-z]/.test({{ $passwordModel }}) && /[A-Z]/.test({{ $passwordModel }})) ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-slate-200/80 text-slate-400 dark:bg-slate-700'">
                <x-tabler-icon name="check" class="h-3 w-3" />
            </span>
            <span>Upper &amp; lowercase letters</span>
        </li>

        {{-- Number (0-9) --}}
        <li class="flex items-center gap-2 transition-colors" :class="/[0-9]/.test({{ $passwordModel }}) ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-500 dark:text-slate-400'">
            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full" :class="/[0-9]/.test({{ $passwordModel }}) ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-slate-200/80 text-slate-400 dark:bg-slate-700'">
                <x-tabler-icon name="check" class="h-3 w-3" />
            </span>
            <span>At least 1 number (0–9)</span>
        </li>

        {{-- Symbol / Special char --}}
        <li class="flex items-center gap-2 transition-colors" :class="/[^A-Za-z0-9]/.test({{ $passwordModel }}) ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-500 dark:text-slate-400'">
            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full" :class="/[^A-Za-z0-9]/.test({{ $passwordModel }}) ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-slate-200/80 text-slate-400 dark:bg-slate-700'">
                <x-tabler-icon name="check" class="h-3 w-3" />
            </span>
            <span>At least 1 symbol (!@#$%^&*)</span>
        </li>

        @if ($showMatch)
            <li
                x-show="{{ $confirmationModel }}.length > 0"
                class="sm:col-span-2 flex items-center gap-2 transition-colors pt-1 border-t border-slate-200/60 dark:border-slate-700/60"
                :class="({{ $passwordModel }} === {{ $confirmationModel }} && {{ $passwordModel }}.length > 0) ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-rose-500 dark:text-rose-400'"
            >
                <span
                    class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full"
                    :class="({{ $passwordModel }} === {{ $confirmationModel }} && {{ $passwordModel }}.length > 0) ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-rose-100 text-rose-500 dark:bg-rose-950 dark:text-rose-400'"
                >
                    <x-tabler-icon name="check" class="h-3 w-3" x-show="{{ $passwordModel }} === {{ $confirmationModel }}" />
                    <x-tabler-icon name="x" class="h-3 w-3" x-show="{{ $passwordModel }} !== {{ $confirmationModel }}" />
                </span>
                <span x-text="{{ $passwordModel }} === {{ $confirmationModel }} ? 'Passwords match' : 'Passwords do not match'"></span>
            </li>
        @endif
    </ul>
</div>
