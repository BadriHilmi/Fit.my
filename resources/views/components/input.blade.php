@props([
    'name' => '',
    'id' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'error' => null,
    'messages' => [],
    'errorExpr' => null,
    'required' => false,
    'disabled' => false,
    'passwordToggle' => false,
])

@php
    $inputId = $id ?: $name;
    $resolvedMessages = collect((array) $messages)
        ->merge($error ? (array) $error : [])
        ->filter()
        ->values();
    $hasError = $resolvedMessages->isNotEmpty();
    $hasIcon = isset($icon) && trim($icon) !== '';

    $fieldBaseClasses = 'block w-full rounded-xl border bg-white py-2.5 text-sm text-[#0a0a0a] placeholder:text-gray-400 transition focus:bg-white focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:bg-slate-800 dark:disabled:bg-slate-800/60 ';
    $fieldBaseClasses .= $hasIcon ? 'pl-10 ' : 'pl-3.5 ';
    $fieldBaseClasses .= ($passwordToggle && $type === 'password') ? 'pr-10 ' : 'pr-3.5 ';

    $stateClasses = $hasError
        ? 'border-[#d45656] focus:border-[#d45656] focus:ring-[#d45656]/20'
        : 'border-[#e5e5e5] hover:border-gray-400 focus:border-[#00d4a4] focus:ring-[#00d4a4]/20 dark:border-slate-700 dark:hover:border-slate-600 dark:focus:border-[#00d4a4]';

    $fieldClasses = $fieldBaseClasses . ($errorExpr ? '' : $stateClasses);
    $currentValue = $value ?? ($name !== '' ? old($name) : null);
@endphp

<div @if ($passwordToggle && $type === 'password') x-data="{ showPassword: false }" @endif class="w-full">
    @if ($label)
        <label for="{{ $inputId }}" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#3a3a3c] dark:text-slate-200">
            {{ $label }}
            @if ($required)
                <span class="text-[#d45656]" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if ($hasIcon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                {{ $icon }}
            </div>
        @endif

        @if ($type === 'select')
            <select
                id="{{ $inputId }}"
                name="{{ $name }}"
                @if ($required) required @endif
                @if ($disabled) disabled @endif
                @if ($hasError) aria-invalid="true" @endif
                @if ($errorExpr)
                    :class="({{ $errorExpr }}) ? 'border-[#d45656] focus:border-[#d45656] focus:ring-[#d45656]/20' : 'border-[#e5e5e5] hover:border-gray-400 focus:border-[#00d4a4] focus:ring-[#00d4a4]/20'"
                    x-bind:aria-invalid="Boolean({{ $errorExpr }}).toString()"
                @endif
                {{ $attributes->merge(['class' => trim($fieldClasses)]) }}
            >
                {{ $slot }}
            </select>
        @elseif ($type === 'textarea')
            <textarea
                id="{{ $inputId }}"
                name="{{ $name }}"
                @if ($required) required @endif
                @if ($disabled) disabled @endif
                @if ($hasError) aria-invalid="true" @endif
                @if ($errorExpr)
                    :class="({{ $errorExpr }}) ? 'border-[#d45656] focus:border-[#d45656] focus:ring-[#d45656]/20' : 'border-[#e5e5e5] hover:border-gray-400 focus:border-[#00d4a4] focus:ring-[#00d4a4]/20'"
                    x-bind:aria-invalid="Boolean({{ $errorExpr }}).toString()"
                @endif
                {{ $attributes->merge(['class' => trim($fieldClasses)]) }}
            >{{ $currentValue ?? $slot }}</textarea>
        @else
            <input
                id="{{ $inputId }}"
                name="{{ $name }}"
                @if ($passwordToggle && $type === 'password')
                    type="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                @else
                    type="{{ $type }}"
                @endif
                @if (!is_null($currentValue) && $type !== 'password' && $type !== 'file')
                    value="{{ $currentValue }}"
                @endif
                @if ($required) required @endif
                @if ($disabled) disabled @endif
                @if ($hasError) aria-invalid="true" @endif
                @if ($errorExpr)
                    :class="({{ $errorExpr }}) ? 'border-[#d45656] focus:border-[#d45656] focus:ring-[#d45656]/20' : 'border-[#e5e5e5] hover:border-gray-400 focus:border-[#00d4a4] focus:ring-[#00d4a4]/20'"
                    x-bind:aria-invalid="Boolean({{ $errorExpr }}).toString()"
                @endif
                {{ $attributes->merge(['class' => trim($fieldClasses)]) }}
            />
        @endif

        @if ($passwordToggle && $type === 'password')
            <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 transition hover:text-gray-700 focus:outline-none"
                x-bind:aria-label="showPassword ? 'Hide password' : 'Show password'"
            >
                <svg x-show="!showPassword" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <svg x-cloak x-show="showPassword" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                    <line x1="2" x2="22" y1="2" y2="22" />
                </svg>
            </button>
        @endif
    </div>

    @if ($hint && !$hasError)
        <p class="mt-1.5 text-xs text-gray-600">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <ul class="mt-1.5 space-y-1 text-xs font-medium text-red-600">
            @foreach ($resolvedMessages as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif
</div>
