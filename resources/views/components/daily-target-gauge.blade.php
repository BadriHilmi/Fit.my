@props([
    'goal' => 2000,
    'net' => 0,
    'food' => 0,
    'burned' => 0,
    'uid' => null,
])

@php
    $goal = max(0, (int) $goal);
    $net = (int) $net;
    $food = (int) $food;
    $burned = (int) $burned;

    $rawPercent = $goal > 0 ? (int) round(($net / $goal) * 100) : 0;
    $fillPercent = max(0, min(100, $rawPercent));
    $isOverLimit = $goal > 0 && $net > $goal;
    $isGoalReached = $goal > 0 && $net >= $goal && ! $isOverLimit;
    $overBy = max(0, $net - $goal);

    $formattedGoal = number_format($goal);
    $uid = $uid ?? uniqid('target_');

    // Snug SVG width matching formatted digits with minimal trailing gap before kcal/day
    $charCount = strlen($formattedGoal);
    $vbWidth = max(120, $charCount * 27 + 14);
    $vbHeight = 68;

    // Map 0..100% into visual glyph height (y=56 baseline up to y=8 top)
    $waveY = round(56 - ($fillPercent / 100) * 48, 1);

    // Wave paths drawn around y=0 so outer <g> can smoothly translateY(waveY)
    $w = $vbWidth;
    $step = round($w / 4, 2);
    $wavePath = sprintf(
        'M 0 0 Q %1$s -5.5 %2$s 0 T %3$s 0 T %4$s 0 T %5$s 0 L %5$s 80 L 0 80 Z',
        $step * 0.5,
        $step,
        $step * 2,
        $step * 3,
        $step * 4
    );
    $wavePath2 = sprintf(
        'M 0 1.5 Q %1$s 7 %2$s 1.5 T %3$s 1.5 T %4$s 1.5 T %5$s 1.5 L %5$s 80 L 0 80 Z',
        $step * 0.5,
        $step,
        $step * 2,
        $step * 3,
        $step * 4
    );
    $doubleWaveWidth = $w * 2;

    $sharedViewData = compact(
        'goal',
        'net',
        'food',
        'burned',
        'fillPercent',
        'isOverLimit',
        'isGoalReached',
        'overBy',
        'formattedGoal',
        'vbWidth',
        'vbHeight',
        'waveY',
        'w',
        'wavePath',
        'wavePath2',
        'doubleWaveWidth'
    );
@endphp

<div
    x-data="{
        goal: {{ $goal }},
        net: {{ $net }},
        food: {{ $food }},
        burned: {{ $burned }},
        pinned: false,
        pinThreshold: 76,
        get rawPercent() {
            return this.goal > 0 ? Math.round((this.net / this.goal) * 100) : 0;
        },
        get fillPercent() {
            return Math.max(0, Math.min(100, this.rawPercent));
        },
        get isOverLimit() {
            return this.goal > 0 && this.net > this.goal;
        },
        get isGoalReached() {
            return this.goal > 0 && this.net >= this.goal && !this.isOverLimit;
        },
        get overBy() {
            return Math.max(0, this.net - this.goal);
        },
        get waveY() {
            return Number((56 - (this.fillPercent / 100) * 48).toFixed(1));
        },
        get statusText() {
            if (this.isOverLimit) return '· Over Limit';
            if (this.isGoalReached) return '· Goal Reached';
            return '· ' + this.fillPercent + '%';
        },
        formatNum(n) {
            return Number(n || 0).toLocaleString();
        },
        syncPinned() {
            if (!this.$refs.headerTarget) return;
            const rect = this.$refs.headerTarget.getBoundingClientRect();
            this.pinned = rect.top <= this.pinThreshold;
        },
        handleUpdate(detail) {
            if (!detail) return;
            if (detail.goal !== undefined) this.goal = Number(detail.goal);
            if (detail.food !== undefined) this.food = Number(detail.food);
            if (detail.burned !== undefined) this.burned = Number(detail.burned);
            if (detail.net !== undefined) {
                this.net = Number(detail.net);
            } else {
                this.net = this.food - this.burned;
            }
        }
    }"
    x-init="syncPinned()"
    @scroll.window="syncPinned()"
    @resize.window="syncPinned()"
    @fit-calories-updated.window="handleUpdate($event.detail)"
>
    <!-- Primary Header Display (sits seamlessly in header; hides without layout shift when pinned) -->
    <div
        x-ref="headerTarget"
        class="flex flex-col items-start md:items-end rounded-2xl px-3.5 py-2"
        :class="pinned ? 'opacity-0 pointer-events-none' : 'opacity-100'"
    >
        @include('components.partials.daily-target-gauge-content', array_merge($sharedViewData, ['clipId' => $uid . '-clip']))
    </div>

    <!-- Seamless Pinned Overlay (exact same size & max-w-7xl right alignment, freezes at top-[4.75rem]) -->
    <template x-teleport="body">
        <div
            x-show="pinned"
            x-cloak
            class="pointer-events-none fixed inset-x-0 top-[4.75rem] z-30"
        >
            <div class="mx-auto flex max-w-7xl justify-start px-4 sm:px-6 md:justify-end lg:px-8">
                <div
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    title="Click to scroll back to top"
                    class="pointer-events-auto flex cursor-pointer flex-col items-start rounded-2xl px-3.5 py-2 shadow-xl backdrop-blur-md transition-colors md:items-end"
                    :class="isOverLimit
                        ? 'bg-rose-100/90 ring-2 ring-rose-400/60 dark:bg-rose-950/90 dark:ring-rose-500/50'
                        : 'bg-slate-50/85 shadow-slate-900/10 dark:bg-slate-900/85 dark:shadow-black/40'"
                >
                    @include('components.partials.daily-target-gauge-content', array_merge($sharedViewData, ['clipId' => $uid . '-sticky-clip']))
                </div>
            </div>
        </div>
    </template>
</div>
