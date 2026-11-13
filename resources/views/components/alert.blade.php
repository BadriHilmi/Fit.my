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
            <x-tabler-icon name="circle-check" class="h-5 w-5" />
        @elseif ($variant === 'warning')
            <x-tabler-icon name="alert-triangle" class="h-5 w-5" />
        @elseif ($variant === 'error')
            <x-tabler-icon name="alert-circle" class="h-5 w-5" />
        @else
            <x-tabler-icon name="info-circle" class="h-5 w-5" />
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
            <x-tabler-icon name="x" class="h-4 w-4" />
        </button>
    @endif
</div>
