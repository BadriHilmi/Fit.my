<div class="flex items-center gap-2">
    <p
        class="text-[11px] font-black uppercase tracking-[0.22em] transition-colors"
        :class="isOverLimit ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-700 dark:text-emerald-400'"
    >
        Daily Target
    </p>
    <span
        class="text-[11px] font-bold transition-colors"
        :class="isOverLimit ? 'text-rose-600 dark:text-rose-400' : (isGoalReached ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400')"
        x-text="statusText"
    >
        @if ($isOverLimit)
            &middot; Over Limit
        @elseif ($isGoalReached)
            &middot; Goal Reached
        @else
            &middot; {{ $fillPercent }}%
        @endif
    </span>
</div>

<div class="mt-0.5 flex items-baseline gap-2">
    <svg
        viewBox="0 0 {{ $vbWidth }} {{ $vbHeight }}"
        class="h-14 w-auto sm:h-16 select-none overflow-visible transition-all duration-300"
        :class="isOverLimit ? 'fit-fat-jelly origin-center mx-3' : ''"
        role="img"
        aria-label="Daily Target {{ $formattedGoal }} kcal"
    >
        <defs>
            <clipPath id="{{ $clipId }}">
                <text
                    x="4"
                    y="55"
                    text-anchor="start"
                    font-family="Figtree, ui-sans-serif, system-ui, sans-serif"
                    :font-size="isOverLimit ? 53 : 52"
                    font-size="{{ $isOverLimit ? 53 : 52 }}"
                    font-weight="900"
                    letter-spacing="-0.03em"
                >{{ $formattedGoal }}</text>
            </clipPath>
        </defs>

        <text
            x="4"
            y="55"
            text-anchor="start"
            font-family="Figtree, ui-sans-serif, system-ui, sans-serif"
            :font-size="isOverLimit ? 53 : 52"
            font-size="{{ $isOverLimit ? 53 : 52 }}"
            font-weight="900"
            letter-spacing="-0.03em"
            class="transition-colors duration-300"
            :class="isOverLimit
                ? 'fill-rose-300/80 stroke-rose-500 dark:fill-rose-900/80 dark:stroke-rose-500'
                : 'fill-slate-400/35 stroke-slate-500/45 dark:fill-slate-700/80 dark:stroke-slate-500/60'"
            :stroke-width="isOverLimit ? 5 : 1.5"
            stroke-width="{{ $isOverLimit ? 5 : 1.5 }}"
            stroke-linejoin="round"
            style="paint-order: stroke fill;"
        >{{ $formattedGoal }}</text>

        <g clip-path="url(#{{ $clipId }})">
            <g
                class="transition-transform duration-500 ease-out"
                style="transform: translateY({{ $waveY }}px)"
                :style="'transform: translateY(' + waveY + 'px)'"
            >
                <g class="fit-wave-slow" style="width: {{ $doubleWaveWidth }}px;">
                    <path
                        d="{{ $wavePath2 }}"
                        class="transition-colors duration-300"
                        :class="isOverLimit ? 'fill-orange-400/85 dark:fill-orange-500/85' : 'fill-teal-400/85 dark:fill-teal-400/80'"
                    />
                    <path
                        d="{{ $wavePath2 }}"
                        transform="translate({{ $w }}, 0)"
                        class="transition-colors duration-300"
                        :class="isOverLimit ? 'fill-orange-400/85 dark:fill-orange-500/85' : 'fill-teal-400/85 dark:fill-teal-400/80'"
                    />
                </g>

                <g class="fit-wave-fast" style="width: {{ $doubleWaveWidth }}px;">
                    <path
                        d="{{ $wavePath }}"
                        class="transition-colors duration-300 {{ $isOverLimit ? 'fill-rose-600 dark:fill-rose-500' : 'fill-emerald-600 dark:fill-emerald-400' }}"
                        :class="isOverLimit ? 'fill-rose-600 dark:fill-rose-500' : 'fill-emerald-600 dark:fill-emerald-400'"
                    />
                    <path
                        d="{{ $wavePath }}"
                        transform="translate({{ $w }}, 0)"
                        class="transition-colors duration-300"
                        :class="isOverLimit ? 'fill-rose-600 dark:fill-rose-500' : 'fill-emerald-600 dark:fill-emerald-400'"
                    />
                </g>
            </g>
        </g>
    </svg>

    <span
        class="text-xs font-extrabold uppercase tracking-wider transition-colors"
        :class="isOverLimit ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400'"
    >
        kcal/day
    </span>
</div>

<p
    class="mt-0.5 text-xs font-semibold transition-colors"
    :class="isOverLimit ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400'"
>
    <span x-show="isOverLimit" @if(!$isOverLimit) x-cloak @endif>
        Over limit by <strong x-text="'+' + formatNum(overBy) + ' kcal'">+{{ number_format($overBy) }} kcal</strong> &mdash;
        <a href="{{ route('activity') }}" class="underline hover:text-rose-800 dark:hover:text-rose-200">burn it off</a>
    </span>
    <span x-show="!isOverLimit" @if($isOverLimit) x-cloak @endif>
        <span x-text="formatNum(net) + ' / {{ $formattedGoal }} net kcal'">{{ number_format($net) }} / {{ $formattedGoal }} net kcal</span>
        <span x-show="burned > 0" @if($burned <= 0) x-cloak @endif class="text-emerald-600 dark:text-emerald-400" x-text="'(−' + formatNum(burned) + ' burned)'">
            @if ($burned > 0)(&minus;{{ number_format($burned) }} burned)@endif
        </span>
    </span>
</p>
