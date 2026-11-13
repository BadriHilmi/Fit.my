@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $styles = [
        'success' => [
            'wrapper' => 'border-success-200 bg-success-50 text-success-900',
            'icon' => 'text-success-600',
        ],
        'warning' => [
            'wrapper' => 'border-warning-200 bg-warning-50 text-warning-900',
            'icon' => 'text-warning-600',
        ],
        'error' => [
            'wrapper' => 'border-error-200 bg-error-50 text-error-900',
            'icon' => 'text-error-600',
        ],
        'info' => [
            'wrapper' => 'border-neutral-200 bg-neutral-50 text-neutral-900',
            'icon' => 'text-primary-600',
        ],
    ][$variant] ?? [
        'wrapper' => 'border-neutral-200 bg-neutral-50 text-neutral-900',
        'icon' => 'text-primary-600',
    ];
@endphp

<div
    @if ($dismissible) x-data="{ open: true }" x-show="open" x-transition @endif
    role="alert"
    {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-xl border px-4 py-3.5 text-sm {$styles['wrapper']}"]) }}
>
    <div class="mt-0.5 shrink-0 {{ $styles['icon'] }}" aria-hidden="true">
        @if ($variant === 'success')
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="m9 12 2 2 4-4" />
            </svg>
        @elseif ($variant === 'warning')
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                <line x1="12" x2="12" y1="9" y2="13" />
                <line x1="12" x2="12.01" y1="17" y2="17" />
            </svg>
        @elseif ($variant === 'error')
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" x2="12" y1="8" y2="12" />
                <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
        @else
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 16v-4" />
                <path d="M12 8h.01" />
            </svg>
        @endif
    </div>

    <div class="min-w-0 flex-1">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-0.5 text-sm/relaxed opacity-90' : '' }}">
            {{ $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            @click="open = false"
            class="shrink-0 rounded-lg p-1 opacity-60 transition hover:bg-black/5 hover:opacity-100"
            aria-label="Dismiss notification"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    @endif
</div>
