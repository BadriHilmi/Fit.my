@props([
    'glow' => true,
    'mask' => 'mask-radial-fade',
    'invert' => false,
    'glowColor' => 'mint', // 'mint' or 'brand'
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'pointer-events-none absolute inset-0 overflow-hidden select-none ' . $class]) }} aria-hidden="true">
    @if ($glow)
        @if ($glowColor === 'mint')
            {{-- Soft, faint ambient glow: emerald/mint depth without busy blobs --}}
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 h-[420px] w-[680px] max-w-full rounded-full bg-gradient-to-tr from-emerald-400/10 via-[#00d4a4]/12 to-teal-300/5 blur-3xl dark:from-emerald-500/10 dark:via-teal-400/8 dark:to-transparent"></div>
        @elseif ($glowColor === 'brand')
            <div class="absolute -top-16 left-1/2 -translate-x-1/2 h-[380px] w-[600px] max-w-full rounded-full bg-gradient-to-r from-[#00d4a4]/15 via-emerald-500/10 to-transparent blur-3xl dark:from-emerald-600/15 dark:to-transparent"></div>
        @endif
    @endif

    {{-- Square Dot Grid with Radial Mask Fade (Faint 6-9% opacity, 1.2px dot on 24px grid) --}}
    <div class="absolute inset-0 {{ $invert ? 'bg-dot-grid-invert' : 'bg-dot-grid' }} {{ $mask }}"></div>
</div>
