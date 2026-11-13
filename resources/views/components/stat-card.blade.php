@props([
    'label',
    'value',
    'subtext' => null,
    'badge' => null,
    'tone' => 'primary',
])

@php
    $toneMap = [
        'primary' => [
            'badge' => 'bg-[#00d4a4]/15 text-[#008f6e] border-[#00d4a4]/30 dark:text-[#00d4a4]',
            'icon' => 'bg-[#00d4a4]/10 text-[#00b48a]',
            'value' => 'text-[#0a0a0a]',
        ],
        'accent' => [
            'badge' => 'bg-accent-50 text-accent-700 border-accent-200/70',
            'icon' => 'bg-accent-50 text-accent-600',
            'value' => 'text-[#0a0a0a]',
        ],
        'warning' => [
            'badge' => 'bg-amber-50 text-amber-800 border-amber-200/70',
            'icon' => 'bg-amber-50 text-amber-600',
            'value' => 'text-[#0a0a0a]',
        ],
        'error' => [
            'badge' => 'bg-red-50 text-[#d45656] border-[#d45656]/30',
            'icon' => 'bg-red-50 text-[#d45656]',
            'value' => 'text-[#0a0a0a]',
        ],
        'neutral' => [
            'badge' => 'bg-[#f7f7f8] text-[#5a5a5c] border-[#e5e5e5]',
            'icon' => 'bg-[#f7f7f8] text-[#5a5a5c]',
            'value' => 'text-[#0a0a0a]',
        ],
    ][$tone] ?? [
        'badge' => 'bg-[#00d4a4]/15 text-[#008f6e] border-[#00d4a4]/30',
        'icon' => 'bg-[#00d4a4]/10 text-[#00b48a]',
        'value' => 'text-[#0a0a0a]',
    ];
@endphp

<x-card padding="sm" {{ $attributes }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#888888] dark:text-slate-400">{{ $label }}</p>
            <p class="mt-1.5 font-mono text-2xl font-bold tracking-tight tabular-nums text-[#0a0a0a] dark:text-white">{{ $value }}</p>
        </div>

        @isset($icon)
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[#00d4a4]/20 {{ $toneMap['icon'] }}">
                {{ $icon }}
            </div>
        @endisset
    </div>

    @if ($subtext || $badge)
        <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
            @if ($subtext)
                <span class="text-xs text-[#5a5a5c] dark:text-slate-400">{{ $subtext }}</span>
            @endif
            @if ($badge)
                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $toneMap['badge'] }}">
                    {{ $badge }}
                </span>
            @endif
        </div>
    @endif
</x-card>
