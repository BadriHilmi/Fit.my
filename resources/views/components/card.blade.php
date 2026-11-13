@props([
    'padding' => 'md',
    'hover' => false,
    'variant' => 'default',
])

@php
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-6 sm:p-8',
    ][$padding] ?? 'p-6';

    $variantClasses = [
        'default' => 'border border-[#e5e5e5] bg-white shadow-subtle dark:border-slate-800 dark:bg-slate-900/90',
        'subtle' => 'border border-[#ededed] bg-[#f7f7f8] dark:border-slate-800 dark:bg-slate-800/80',
        'highlight' => 'border-2 border-[#00d4a4] bg-white shadow-[0_8px_24px_rgba(0,212,164,0.08)] dark:border-[#00b48a] dark:bg-slate-900',
    ][$variant] ?? 'border border-[#e5e5e5] bg-white shadow-subtle dark:border-slate-800 dark:bg-slate-900/90';

    $hoverClasses = $hover ? 'transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-card' : '';
@endphp

<div {{ $attributes->merge(['class' => trim("rounded-xl {$variantClasses} {$paddingClasses} {$hoverClasses}")]) }}>
    @isset($header)
        <div class="mb-4 border-b border-[#ededed] pb-4 dark:border-slate-800">
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div class="mt-5 border-t border-[#ededed] pt-4 dark:border-slate-800">
            {{ $footer }}
        </div>
    @endisset
</div>
