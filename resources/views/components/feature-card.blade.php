@props([
    'title',
    'description',
    'tone' => 'primary',
])

@php
    $iconWrap = 'bg-[#00d4a4]/10 text-[#00b48a] ring-1 ring-[#00d4a4]/25';
@endphp

<x-card hover="true" padding="md" {{ $attributes->merge(['class' => 'h-full transition-all duration-200 hover:border-[#00d4a4]/40 hover:shadow-[0_8px_24px_rgba(0,212,164,0.06)]']) }}>
    <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $iconWrap }}">
        {{ $icon ?? $slot }}
    </div>
    <h3 class="mt-4 text-lg font-semibold tracking-tight text-[#0a0a0a] dark:text-white">{{ $title }}</h3>
    <p class="mt-2 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">{{ $description }}</p>
</x-card>
