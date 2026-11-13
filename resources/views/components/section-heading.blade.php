@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'align' => 'center',
])

@php
    $alignment = $align === 'left' ? 'text-left' : 'mx-auto text-center';
@endphp

<div {{ $attributes->merge(['class' => "max-w-2xl {$alignment}"]) }}>
    @if ($eyebrow)
        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00d4a4]/30 bg-[#00d4a4]/10 px-3 py-1 text-xs font-semibold tracking-wide text-[#008f6e] dark:text-[#00d4a4] select-none">
            <span class="h-1.5 w-1.5 rounded-full bg-[#00d4a4]"></span>
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#0a0a0a] sm:text-4xl dark:text-white">
        {{ $title }}
    </h2>

    @if ($subtitle)
        <p class="mt-3.5 text-base leading-relaxed text-[#5a5a5c] sm:text-lg dark:text-slate-300">
            {{ $subtitle }}
        </p>
    @endif
</div>
