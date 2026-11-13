@props([
    'mode' => 'emblem',
])

@if ($mode === 'pulse-line')
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1200 120"
        fill="none"
        preserveAspectRatio="none"
        {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}
        aria-hidden="true"
    >
        <path
            d="M0 64 L320 64 L344 64 L360 32 L378 96 L396 42 L410 64 L760 64 L782 64 L798 22 L818 102 L836 38 L852 64 L1200 64"
            stroke="currentColor"
            stroke-width="2.8"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
@else
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 100 100"
        fill="none"
        {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}
        aria-hidden="true"
    >
        <rect x="4" y="4" width="92" height="92" rx="24" stroke="currentColor" stroke-width="2.6" stroke-opacity="0.55" />
        <path d="M47 26 C42 25 38 20 39 14 C44 15 48 19 47 26 Z" fill="currentColor" fill-opacity="0.45" />
        <path d="M52 26 C51 19 56 13 64 13 C64 20 59 25 52 26 Z" stroke="currentColor" stroke-width="3.8" stroke-linejoin="round" stroke-linecap="round" />
        <path d="M50 32 C56 26 72 28 74 44 C76 62 65 78 56 78 C53 78 51 75 50 75 C49 75 47 78 44 78 C35 78 24 62 26 44 C28 28 44 26 50 32 Z" stroke="currentColor" stroke-width="4.8" stroke-linejoin="round" stroke-linecap="round" />
        <path d="M12 56 L38 56 L44 42 L51 67 L57 49 L62 56 L88 56" stroke="currentColor" stroke-width="4.6" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
@endif
