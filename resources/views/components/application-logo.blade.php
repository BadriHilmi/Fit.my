@props([
    'showText' => true,
    'iconSize' => 'h-9 w-9',
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2.5 select-none']) }}>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 100 100"
        fill="none"
        class="{{ $iconSize }} shrink-0 rounded-xl shadow-md shadow-emerald-950/20"
        role="img"
        aria-label="FIT.MY Logo"
    >
        <rect x="4" y="4" width="92" height="92" rx="24" fill="#1b3a26" />
        <path d="M47 26 C42 25 38 20 39 14 C44 15 48 19 47 26 Z" fill="#6ab946" />
        <path d="M52 26 C51 19 56 13 64 13 C64 20 59 25 52 26 Z" stroke="#6ab946" stroke-width="3.6" stroke-linejoin="round" stroke-linecap="round" />
        <path d="M50 32 C56 26 72 28 74 44 C76 62 65 78 56 78 C53 78 51 75 50 75 C49 75 47 78 44 78 C35 78 24 62 26 44 C28 28 44 26 50 32 Z" stroke="#6ab946" stroke-width="4.8" stroke-linejoin="round" stroke-linecap="round" />
        <path d="M16 56 L38 56 L44 42 L51 67 L57 49 L62 56 L84 56" stroke="#1b3a26" stroke-width="9.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M16 56 L38 56 L44 42 L51 67 L57 49 L62 56 L84 56" stroke="#ffffff" stroke-width="4.6" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    @if ($showText)
        <div class="flex flex-col leading-tight">
            <span class="text-lg font-black tracking-tight text-slate-900 dark:text-white">
                FIT<span class="text-emerald-500">.MY</span>
            </span>
            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Fitness &amp; Nutrition</span>
        </div>
    @endif
</div>
