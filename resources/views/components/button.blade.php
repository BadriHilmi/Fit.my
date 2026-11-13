@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'loading' => false,
    'loadingExpr' => null,
    'fullWidth' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-full font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 select-none';

    $variantClasses = [
        'primary' => 'bg-[#0a0a0a] text-white shadow-sm hover:bg-[#1c1c1e] active:bg-[#2c2c2e] focus:ring-[#00d4a4] dark:bg-white dark:text-[#0a0a0a] dark:hover:bg-gray-100',
        'mint' => 'bg-[#00d4a4] text-[#0a0a0a] font-semibold shadow-sm hover:bg-[#00b48a] active:bg-[#009e79] focus:ring-[#00d4a4]',
        'secondary' => 'border border-[#e5e5e5] bg-white text-[#0a0a0a] shadow-sm hover:bg-[#f7f7f8] hover:border-gray-300 focus:ring-[#00d4a4] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700',
        'ghost' => 'bg-transparent text-[#5a5a5c] hover:bg-black/[0.04] hover:text-[#0a0a0a] focus:ring-[#00d4a4] dark:text-slate-300 dark:hover:bg-white/[0.08]',
        'accent' => 'bg-accent-500 text-white shadow-sm hover:bg-accent-600 active:bg-accent-700 focus:ring-accent-500',
        'danger' => 'bg-[#d45656] text-white shadow-sm hover:bg-red-700 active:bg-red-800 focus:ring-red-500',
        'white' => 'bg-white text-[#0a0a0a] font-semibold shadow-sm hover:bg-gray-50 focus:ring-white focus:ring-offset-gray-900',
    ][$variant] ?? 'bg-[#0a0a0a] text-white shadow-sm hover:bg-[#1c1c1e] focus:ring-[#00d4a4]';

    $sizeClasses = [
        'sm' => 'px-3.5 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ][$size] ?? 'px-5 py-2.5 text-sm';

    $widthClasses = $fullWidth ? 'w-full' : '';
    $classes = trim("{$baseClasses} {$variantClasses} {$sizeClasses} {$widthClasses}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        @if ($loading) disabled aria-busy="true" @endif
        @if ($loadingExpr) x-bind:disabled="{{ $loadingExpr }}" x-bind:aria-busy="({{ $loadingExpr }}).toString()" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if ($loading)
            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        @elseif ($loadingExpr)
            <svg x-cloak x-show="{{ $loadingExpr }}" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
        @endif

        <span>{{ $slot }}</span>
    </button>
@endif
