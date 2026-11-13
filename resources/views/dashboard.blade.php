<x-app-layout>
    @php
        $mealPayload = $mealPayload ?? \App\Models\MealLog::dailyPayloadForUser(auth()->id());
        $initialCarts = $mealPayload['carts'];
        $initialTotals = $mealPayload['totals'];

        $totalBreakfast = $initialTotals['breakfast'];
        $totalLunch = $initialTotals['lunch'];
        $totalDinner = $initialTotals['dinner'];
        $dailyTotal = $initialTotals['food'];

        $burnedToday = (int) ($burnedToday ?? 0);
        $activeMinutesToday = (int) ($activeMinutesToday ?? 0);
        $netTotal = $dailyTotal - $burnedToday;
        $goal = (int) (Auth::user()->calorie_intake ?? 0);
        $remaining = $goal - $netTotal;
        $percent = $goal > 0 ? min(100, max(0, round(($netTotal / $goal) * 100))) : 0;

        $mealSlots = [
            'breakfast' => [
                'title' => 'Breakfast',
                'subtitle' => 'Morning Intake',
                'emoji' => '🌅',
                'addPath' => 'add-to-cart',
                'cardClass' => 'fit-card-amber',
                'headerBg' => 'border-b border-amber-200/80 bg-amber-50/70 dark:border-amber-900/60 dark:bg-amber-950/40',
                'subtitleClass' => 'text-amber-800 dark:text-amber-300',
                'iconBg' => 'bg-amber-200 text-amber-800 dark:bg-amber-900/80 dark:text-amber-200',
                'badgeBg' => 'bg-amber-500',
                'ringClass' => 'ring-4 ring-amber-400/70 scale-[1.02]',
                'dragBorderClass' => 'border-2 border-amber-400 border-dashed',
                'dropActiveClass' => 'border-amber-600 bg-amber-200 text-amber-900 scale-105 shadow-md dark:bg-amber-800 dark:text-white',
                'dropIdleClass' => 'border-amber-400 bg-amber-100/80 text-amber-900 animate-pulse dark:bg-amber-900/50 dark:text-amber-200',
                'itemCardClass' => 'border-amber-200/80 bg-white/75 dark:border-amber-900/40 dark:bg-slate-800/80',
                'kcalClass' => 'text-amber-700 dark:text-amber-300',
                'qtyBoxClass' => 'border-amber-300 dark:border-amber-800',
                'emptyBorderClass' => 'border-amber-300/70 dark:border-amber-900/60',
                'clearBarClass' => 'border-amber-200/70 bg-amber-50/40 dark:border-amber-900/30 dark:bg-slate-900/60',
                'clearTextClass' => 'text-amber-700 dark:text-amber-400',
            ],
            'lunch' => [
                'title' => 'Lunch',
                'subtitle' => 'Midday Meal',
                'emoji' => '☀️',
                'addPath' => 'add-to-cart1',
                'cardClass' => 'fit-card-sky',
                'headerBg' => 'border-b border-sky-200/80 bg-sky-50/70 dark:border-sky-900/60 dark:bg-sky-950/40',
                'subtitleClass' => 'text-sky-800 dark:text-sky-300',
                'iconBg' => 'bg-sky-200 text-sky-800 dark:bg-sky-900/80 dark:text-sky-200',
                'badgeBg' => 'bg-sky-500',
                'ringClass' => 'ring-4 ring-sky-400/70 scale-[1.02]',
                'dragBorderClass' => 'border-2 border-sky-400 border-dashed',
                'dropActiveClass' => 'border-sky-600 bg-sky-200 text-sky-900 scale-105 shadow-md dark:bg-sky-800 dark:text-white',
                'dropIdleClass' => 'border-sky-400 bg-sky-100/80 text-sky-900 animate-pulse dark:bg-sky-900/50 dark:text-sky-200',
                'itemCardClass' => 'border-sky-200/80 bg-white/75 dark:border-sky-900/40 dark:bg-slate-800/80',
                'kcalClass' => 'text-sky-700 dark:text-sky-300',
                'qtyBoxClass' => 'border-sky-300 dark:border-sky-800',
                'emptyBorderClass' => 'border-sky-300/70 dark:border-sky-900/60',
                'clearBarClass' => 'border-sky-200/70 bg-sky-50/40 dark:border-sky-900/30 dark:bg-slate-900/60',
                'clearTextClass' => 'text-sky-700 dark:text-sky-400',
            ],
            'dinner' => [
                'title' => 'Dinner',
                'subtitle' => 'Evening Meal',
                'emoji' => '🌙',
                'addPath' => 'add-to-cart2',
                'cardClass' => 'fit-card-violet',
                'headerBg' => 'border-b border-violet-200/80 bg-violet-50/70 dark:border-violet-900/60 dark:bg-violet-950/40',
                'subtitleClass' => 'text-violet-800 dark:text-violet-300',
                'iconBg' => 'bg-violet-200 text-violet-800 dark:bg-violet-900/80 dark:text-violet-200',
                'badgeBg' => 'bg-violet-600',
                'ringClass' => 'ring-4 ring-violet-400/70 scale-[1.02]',
                'dragBorderClass' => 'border-2 border-violet-400 border-dashed',
                'dropActiveClass' => 'border-violet-600 bg-violet-200 text-violet-900 scale-105 shadow-md dark:bg-violet-800 dark:text-white',
                'dropIdleClass' => 'border-violet-400 bg-violet-100/80 text-violet-900 animate-pulse dark:bg-violet-900/50 dark:text-violet-200',
                'itemCardClass' => 'border-violet-200/80 bg-white/75 dark:border-violet-900/40 dark:bg-slate-800/80',
                'kcalClass' => 'text-violet-700 dark:text-violet-300',
                'qtyBoxClass' => 'border-violet-300 dark:border-violet-800',
                'emptyBorderClass' => 'border-violet-300/70 dark:border-violet-900/60',
                'clearBarClass' => 'border-violet-200/70 bg-violet-50/40 dark:border-violet-900/30 dark:bg-slate-900/60',
                'clearTextClass' => 'text-violet-700 dark:text-violet-400',
            ],
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-400">Personal Health Portal</p>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                    Welcome back, <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent dark:from-emerald-400 dark:to-teal-300">{{ Auth::user()->name }}</span>
                </h2>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Drag any food card from the library into Breakfast, Lunch, or Dinner above!</p>
            </div>
            <div class="flex items-center gap-3">
                <x-daily-target-gauge
                    :goal="$goal"
                    :net="$netTotal"
                    :food="$dailyTotal"
                    :burned="$burnedToday"
                    uid="dashboard"
                />
            </div>
        </div>
    </x-slot>

    <!-- Global Drag & Drop + Live Cart Controller Container -->
    <div
        x-data="{
            isDragging: false,
            draggedMealId: null,
            draggedMealName: '',
            activeDropZone: null,
            isUpdating: false,
            isSavingSummary: false,

            carts: @js($initialCarts),
            totals: @js($initialTotals),
            burnedToday: {{ $burnedToday }},
            activeMinutesToday: {{ $activeMinutesToday }},
            goal: {{ $goal }},
            autoSavedAt: '',

            toastMessage: '',
            toastError: false,
            toastTimer: null,

            get netTotal() {
                return Number(this.totals.food || 0) - Number(this.burnedToday || 0);
            },
            get remaining() {
                return Number(this.goal || 0) - this.netTotal;
            },
            get percent() {
                if (this.goal <= 0) return 0;
                return Math.min(100, Math.max(0, Math.round((this.netTotal / this.goal) * 100)));
            },
            formatNum(n) {
                return Number(n || 0).toLocaleString();
            },
            showToast(message, isError = false) {
                this.toastMessage = message;
                this.toastError = isError;
                if (this.toastTimer) clearTimeout(this.toastTimer);
                this.toastTimer = setTimeout(() => {
                    this.toastMessage = '';
                }, 2600);
            },
            notifyGauge() {
                window.dispatchEvent(new CustomEvent('fit-calories-updated', {
                    detail: {
                        food: Number(this.totals.food || 0),
                        burned: Number(this.burnedToday || 0),
                        net: this.netTotal,
                        goal: Number(this.goal || 0)
                    }
                }));
            },
            applyCartData(data) {
                if (!data) return;
                if (data.carts) this.carts = data.carts;
                if (data.totals) this.totals = data.totals;
                if (data.autoSavedAt) this.autoSavedAt = data.autoSavedAt;
                this.notifyGauge();
                if (data.message) this.showToast(data.message);
            },
            async runCartAction(url, confirmText = null) {
                if (confirmText && !confirm(confirmText)) return;
                this.isUpdating = true;
                try {
                    const data = await window.fitApi(url);
                    this.applyCartData(data);
                } catch (err) {
                    this.showToast(err.message || 'Could not update meal log.', true);
                } finally {
                    this.isUpdating = false;
                    this.activeDropZone = null;
                }
            },
            async saveDailySummary(formEl) {
                this.isSavingSummary = true;
                try {
                    const data = await window.fitApi(formEl.action, {
                        method: 'POST',
                        body: new FormData(formEl),
                    });
                    if (data?.message) this.showToast(data.message);
                } catch (err) {
                    this.showToast(err.message || 'Could not save summary.', true);
                } finally {
                    this.isSavingSummary = false;
                }
            },
            handleDragStart(id, name) {
                this.isDragging = true;
                this.draggedMealId = id;
                this.draggedMealName = name;
            },
            handleDragEnd() {
                this.isDragging = false;
                this.draggedMealId = null;
                this.draggedMealName = '';
                this.activeDropZone = null;
            },
            handleDrop(slot, e) {
                let id = null;
                try {
                    id = e.dataTransfer.getData('text/plain');
                } catch (err) {}
                if (!id) id = this.draggedMealId;
                if (!id) return;

                this.activeDropZone = slot;
                this.isDragging = false;

                const routes = {
                    breakfast: '{{ url('/add-to-cart') }}/' + id,
                    lunch: '{{ url('/add-to-cart1') }}/' + id,
                    dinner: '{{ url('/add-to-cart2') }}/' + id,
                };

                if (routes[slot]) {
                    this.runCartAction(routes[slot]);
                }
            }
        }"
        @drag-meal-start.window="handleDragStart($event.detail.id, $event.detail.name)"
        @drag-meal-end.window="handleDragEnd()"
        @dragend.window="handleDragEnd()"
        class="py-8 transition-colors relative"
    >
        <!-- Floating Drag Status Bar (visible while dragging anywhere on screen) -->
        <div
            x-show="isDragging"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            x-cloak
            class="fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 rounded-2xl border-2 border-emerald-500 bg-white/95 px-5 py-3 text-sm font-extrabold text-slate-900 shadow-2xl backdrop-blur-md dark:bg-slate-900/95 dark:text-white"
        >
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 animate-pulse">
                🥗
            </span>
            <div class="flex items-center gap-2">
                <span>Dragging:</span>
                <span class="rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-black text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300" x-text="draggedMealName"></span>
            </div>
            <span class="text-xs font-semibold text-slate-400">&rarr; Drop into Breakfast, Lunch, or Dinner above!</span>
        </div>

        <!-- Live Update Toast -->
        <div
            x-show="toastMessage"
            x-transition
            x-cloak
            class="fixed bottom-6 right-6 z-50 flex items-center gap-2.5 rounded-2xl px-5 py-3.5 text-sm font-bold text-white shadow-2xl backdrop-blur-md"
            :class="toastError ? 'bg-rose-600' : 'bg-emerald-600'"
        >
            <span x-text="toastError ? '!' : '✓'"></span>
            <span x-text="toastMessage"></span>
        </div>

        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            <!-- Server Flash Alert (for fallback non-JS submissions) -->
            @if (session('success'))
                <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3.5 text-sm font-semibold text-emerald-800 dark:border-emerald-500/40 dark:text-emerald-300">
                    <div class="flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-xs text-white">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- KPI Cards & Progress -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="fit-card-amber rounded-2xl p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-700/80 dark:text-amber-300/80">Food Consumed</p>
                    <p class="mt-2 text-3xl font-black text-slate-900 dark:text-white" x-text="formatNum(totals.food)">{{ number_format($dailyTotal) }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">calories logged today</p>
                </div>

                <div class="fit-card-emerald rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700/80 dark:text-emerald-300/80">Exercise Burned</p>
                        <a href="{{ route('activity') }}" class="text-[11px] font-bold text-emerald-700 hover:underline dark:text-emerald-400">+ Log &rarr;</a>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-600 dark:text-emerald-400">
                        {{ $burnedToday > 0 ? '-' . number_format($burnedToday) : '0' }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        {{ $activeMinutesToday > 0 ? $activeMinutesToday . ' active min today' : 'no workouts logged yet' }}
                    </p>
                </div>

                <div class="fit-card-sky rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-sky-700/80 dark:text-sky-300/80">Remaining Budget</p>
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400" x-text="'Net: ' + formatNum(netTotal)">Net: {{ number_format($netTotal) }}</span>
                    </div>
                    <p
                        class="mt-2 text-3xl font-black transition-colors"
                        :class="remaining >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                        x-text="formatNum(Math.abs(remaining))"
                    >
                        {{ number_format(abs($remaining)) }}
                    </p>
                    <p
                        class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        x-text="remaining >= 0 ? 'net calories before goal' : 'net calories over goal'"
                    >
                        {{ $remaining >= 0 ? 'net calories before goal' : 'net calories over goal' }}
                    </p>
                </div>

                <!-- Visual Progress Bar & Quick Actions Card -->
                <div class="fit-card-violet rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-violet-700/80 dark:text-violet-300/80">Net Goal Progress</p>
                        <span
                            class="text-xs font-extrabold transition-colors"
                            :class="netTotal > goal ? 'text-rose-600' : 'text-emerald-600 dark:text-emerald-400'"
                            x-text="percent + '%'"
                        >{{ $percent }}%</span>
                    </div>
                    <div class="mt-2.5 h-2.5 w-full overflow-hidden rounded-full bg-white/80 dark:bg-slate-900/70">
                        <div
                            class="h-full rounded-full transition-all duration-500"
                            :class="netTotal > goal ? 'bg-rose-500' : 'bg-gradient-to-r from-emerald-500 to-teal-400'"
                            :style="'width: ' + Math.min(100, percent) + '%'"
                            style="width: {{ min(100, $percent) }}%"
                        ></div>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        <a href="{{ route('ManageMeal.request-meal') }}" class="inline-flex rounded-xl bg-emerald-600 px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-emerald-500 shadow-sm">
                            + Request Dish
                        </a>
                        <a
                            x-show="totals.food > 0"
                            @if ($dailyTotal <= 0) x-cloak @endif
                            href="{{ route('clear_cart') }}"
                            @click.prevent="runCartAction($el.href, 'Clear all meals logged for today?')"
                            class="inline-flex rounded-xl bg-white/80 px-2.5 py-1.5 text-[11px] font-semibold text-slate-600 shadow-sm transition hover:bg-white dark:bg-slate-900/70 dark:text-slate-300"
                        >
                            Reset Day
                        </a>
                    </div>
                </div>
            </div>

            <!-- MAIN FOCUS: Breakfast, Lunch, and Dinner with Interactive Drag-and-Drop Zones -->
            <section class="space-y-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-400">Interactive Intake Box</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">Breakfast, Lunch &amp; Dinner</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                            <span>✨</span> Drag food cards directly into these boxes
                        </span>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    @foreach ($mealSlots as $slotKey => $slot)
                        <div
                            @dragover.prevent="activeDropZone = '{{ $slotKey }}'"
                            @dragenter.prevent="activeDropZone = '{{ $slotKey }}'"
                            @dragleave="if ($event.relatedTarget && !$el.contains($event.relatedTarget)) activeDropZone = null"
                            @drop.prevent="handleDrop('{{ $slotKey }}', $event)"
                            class="{{ $slot['cardClass'] }} relative flex flex-col justify-between overflow-hidden rounded-3xl transition-all duration-200 hover:shadow-xl"
                            :class="activeDropZone === '{{ $slotKey }}'
                                ? '{{ $slot['ringClass'] }}'
                                : (isDragging ? '{{ $slot['dragBorderClass'] }}' : '')"
                        >
                            <div>
                                <!-- Header Strip -->
                                <div class="flex items-center justify-between px-5 py-4 {{ $slot['headerBg'] }}">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-xl shadow-sm {{ $slot['iconBg'] }}">
                                            <x-meal-slot-icon :meal-slot="$slotKey" class="h-5 w-5" />
                                        </span>
                                        <div>
                                            <h4 class="font-extrabold text-slate-900 dark:text-white">{{ $slot['title'] }}</h4>
                                            <p class="text-[11px] font-medium {{ $slot['subtitleClass'] }}">{{ $slot['subtitle'] }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-black text-white shadow-sm {{ $slot['badgeBg'] }}"
                                        x-text="formatNum(totals['{{ $slotKey }}']) + ' kcal'"
                                    >
                                        {{ number_format($initialTotals[$slotKey]) }} kcal
                                    </span>
                                </div>

                                <!-- Interactive Drop Target Banner (Appears while dragging) -->
                                <div
                                    x-show="isDragging"
                                    x-transition
                                    x-cloak
                                    class="mx-5 mt-4 flex items-center justify-center gap-2 rounded-2xl border-2 border-dashed p-3.5 text-center text-xs font-black transition-all cursor-copy"
                                    :class="activeDropZone === '{{ $slotKey }}'
                                        ? '{{ $slot['dropActiveClass'] }}'
                                        : '{{ $slot['dropIdleClass'] }}'"
                                >
                                    <x-meal-slot-icon :meal-slot="$slotKey" class="h-4 w-4 shrink-0" />
                                    <span x-text="activeDropZone === '{{ $slotKey }}' ? ('Release to add ' + (draggedMealName || 'dish') + ' to {{ $slot['title'] }}!') : ('Drop ' + (draggedMealName ? ('[' + draggedMealName + ']') : 'dish') + ' here for {{ $slot['title'] }}')"></span>
                                </div>

                                <!-- Logged Items List (Reactive Alpine Loop) -->
                                <div class="p-5 space-y-2.5">
                                    <template x-for="item in carts['{{ $slotKey }}']" :key="item.log_id ?? item.id">
                                        <div class="flex items-center justify-between rounded-2xl border p-3 shadow-sm {{ $slot['itemCardClass'] }}">
                                            <div class="min-w-0 flex-1 pr-2">
                                                <p class="truncate text-xs font-bold text-slate-900 dark:text-white" x-text="item.Name"></p>
                                                <p class="text-[11px] text-slate-500" x-text="item.Calories + ' kcal each'"></p>
                                            </div>
                                            <div class="flex items-center gap-2.5">
                                                <span class="text-xs font-black {{ $slot['kcalClass'] }}" x-text="formatNum(item.subtotal) + ' kcal'"></span>
                                                <div class="inline-flex items-center rounded-lg border bg-white text-xs shadow-sm dark:bg-slate-900 {{ $slot['qtyBoxClass'] }}">
                                                    <a
                                                        :href="'{{ url('/remove-from-cart/' . $slotKey) }}/' + (item.log_id ?? item.id)"
                                                        @click.prevent="runCartAction($el.href)"
                                                        class="px-2 py-0.5 font-black text-slate-600 hover:text-rose-600 dark:text-slate-300"
                                                        title="Decrease / Remove"
                                                    >-</a>
                                                    <span class="px-1.5 font-bold text-slate-900 dark:text-white" x-text="item.quantity"></span>
                                                    <a
                                                        x-show="item.meal_id"
                                                        :href="'{{ url('/' . $slot['addPath']) }}/' + item.meal_id"
                                                        @click.prevent="runCartAction($el.href)"
                                                        class="px-2 py-0.5 font-black text-slate-600 hover:text-emerald-600 dark:text-slate-300"
                                                        title="Increase"
                                                    >+</a>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div
                                        x-show="!isDragging && carts['{{ $slotKey }}'].length === 0"
                                        @if (!empty($initialCarts[$slotKey])) x-cloak @endif
                                        class="rounded-2xl border-2 border-dashed p-6 text-center text-xs text-slate-500 dark:text-slate-400 {{ $slot['emptyBorderClass'] }}"
                                    >
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">No {{ strtolower($slot['title']) }} items logged yet</p>
                                        <p class="mt-1 text-[11px] text-slate-400">Drag dishes from below or tap <strong>+ {{ $slot['title'] }}</strong></p>
                                    </div>
                                </div>
                            </div>

                            <div
                                x-show="carts['{{ $slotKey }}'].length > 0"
                                @if (empty($initialCarts[$slotKey])) x-cloak @endif
                                class="border-t px-5 py-2.5 text-right {{ $slot['clearBarClass'] }}"
                            >
                                <a
                                    href="{{ route('clear_cart', ['slot' => $slotKey]) }}"
                                    @click.prevent="runCartAction($el.href)"
                                    class="text-xs font-bold hover:underline {{ $slot['clearTextClass'] }}"
                                >
                                    Clear {{ $slot['title'] }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- MEAL LIBRARY (Draggable card format with filter/search and smooth expand) -->
            <section class="space-y-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-400">Food Menu &amp; Pantry</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">Meal Library</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Drag any dish card into a meal box above, or click to expand details.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-xs font-semibold text-slate-400 sm:inline">
                            🖱️ Drag dish &rarr; Drop in Box
                        </span>
                        <a href="{{ route('ManageMeal.request-meal') }}" class="text-xs font-bold text-emerald-600 hover:underline dark:text-emerald-400">
                            Suggest a new meal &rarr;
                        </a>
                    </div>
                </div>

                <!-- Card-based Meal Library -->
                @include('meals.index')
            </section>

            <!-- AUTO-SAVED DAILY SUMMARY BANNER -->
            <section class="rounded-3xl bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 p-6 text-white shadow-lg">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-emerald-200">
                            Auto-Save Active &middot; {{ now()->format('M d, Y') }}
                            <span x-show="autoSavedAt" x-cloak x-text="'· Synced ' + autoSavedAt"></span>
                        </p>
                        <h3 class="mt-1 text-2xl font-black sm:text-3xl">
                            <span x-text="formatNum(totals.food)">{{ number_format($dailyTotal) }}</span> kcal eaten
                            <span x-show="burnedToday > 0" @if ($burnedToday <= 0) x-cloak @endif class="text-emerald-200">
                                &middot; -{{ number_format($burnedToday) }} burned
                            </span>
                        </h3>
                        <p class="mt-1 text-xs text-emerald-100 sm:text-sm">
                            Net today: <strong x-text="formatNum(netTotal) + ' kcal'">{{ number_format($netTotal) }} kcal</strong>. Meals and workouts auto-save to Reports as you log, with an automatic nightly rollup before midnight.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('report') }}" class="inline-flex rounded-full bg-white/15 px-5 py-3 text-sm font-bold text-white ring-1 ring-white/30 transition hover:bg-white/25">
                            📊 View Reports
                        </a>
                        <form action="{{ route('save-report') }}" method="POST" @submit.prevent="saveDailySummary($el)">
                            @csrf
                            <input type="hidden" name="date" value="{{ now()->toDateString() }}" />
                            <input type="hidden" name="lunch_total" :value="totals.lunch" value="{{ $totalLunch }}" />
                            <input type="hidden" name="breakfast_total" :value="totals.breakfast" value="{{ $totalBreakfast }}" />
                            <input type="hidden" name="dinner_total" :value="totals.dinner" value="{{ $totalDinner }}" />
                            <input type="hidden" name="total" :value="totals.food" value="{{ $dailyTotal }}" />
                            <button
                                type="submit"
                                :disabled="isSavingSummary"
                                class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-bold text-emerald-800 shadow-md transition hover:bg-emerald-50 hover:shadow-lg disabled:opacity-60"
                            >
                                <span x-text="isSavingSummary ? 'Syncing...' : '💾 Sync Summary'">💾 Sync Summary</span>
                            </button>
                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
