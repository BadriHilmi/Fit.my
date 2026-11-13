<div x-data="{
    search: '',
    gradeFilter: 'all',
    calFilter: 'all',
    expanded: null,
    draggedId: null,

    matches(name, desc, grade, cal) {
        const q = this.search.toLowerCase().trim();
        const matchesQuery = !q || name.includes(q) || desc.includes(q);
        const matchesGrade = this.gradeFilter === 'all' || grade === this.gradeFilter;
        let matchesCal = true;
        if (this.calFilter === 'light') matchesCal = cal < 350;
        else if (this.calFilter === 'medium') matchesCal = cal >= 350 && cal <= 500;
        else if (this.calFilter === 'hearty') matchesCal = cal > 500;

        return matchesQuery && matchesGrade && matchesCal;
    },

    resetFilters() {
        this.search = '';
        this.gradeFilter = 'all';
        this.calFilter = 'all';
    }
}" class="space-y-6">

    <!-- Search & Filter Controls -->
    <div class="fit-card rounded-2xl p-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Search dishes or ingredients (e.g. Oatmeal, Chicken, Salmon, Rice)..." 
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                />
                <button 
                    x-show="search.length > 0" 
                    @click="search = ''" 
                    type="button" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" /><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Filter Badges -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Grade Filter -->
                <div class="inline-flex rounded-xl bg-slate-100 p-1 dark:bg-slate-800">
                    <button 
                        type="button"
                        @click="gradeFilter = 'all'" 
                        :class="gradeFilter === 'all' ? 'bg-white shadow text-slate-900 dark:bg-slate-700 dark:text-white' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >All Grades</button>
                    <button 
                        type="button"
                        @click="gradeFilter = 'A'" 
                        :class="gradeFilter === 'A' ? 'bg-emerald-600 text-white shadow' : 'text-emerald-700 hover:text-emerald-900 dark:text-emerald-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >Grade A</button>
                    <button 
                        type="button"
                        @click="gradeFilter = 'B'" 
                        :class="gradeFilter === 'B' ? 'bg-amber-500 text-white shadow' : 'text-amber-700 hover:text-amber-900 dark:text-amber-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >Grade B</button>
                </div>

                <!-- Calorie Range Filter -->
                <div class="inline-flex rounded-xl bg-slate-100 p-1 dark:bg-slate-800">
                    <button 
                        type="button"
                        @click="calFilter = 'all'" 
                        :class="calFilter === 'all' ? 'bg-white shadow text-slate-900 dark:bg-slate-700 dark:text-white' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >All Cal</button>
                    <button 
                        type="button"
                        @click="calFilter = 'light'" 
                        :class="calFilter === 'light' ? 'bg-sky-600 text-white shadow' : 'text-sky-700 hover:text-sky-900 dark:text-sky-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >&lt; 350 kcal</button>
                    <button 
                        type="button"
                        @click="calFilter = 'medium'" 
                        :class="calFilter === 'medium' ? 'bg-indigo-600 text-white shadow' : 'text-indigo-700 hover:text-indigo-900 dark:text-indigo-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >350–500</button>
                    <button 
                        type="button"
                        @click="calFilter = 'hearty'" 
                        :class="calFilter === 'hearty' ? 'bg-orange-600 text-white shadow' : 'text-orange-700 hover:text-orange-900 dark:text-orange-400'"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition"
                    >&gt; 500</button>
                </div>

                <!-- Reset Button -->
                <button 
                    x-show="search || gradeFilter !== 'all' || calFilter !== 'all'"
                    @click="resetFilters()"
                    type="button"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                >
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Draggable Cards Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($meals as $meal)
            @php
                $grade = strtoupper($meal->grade ?? 'A');
                $isA = $grade === 'A';
                $isB = $grade === 'B';
                $gradeBadge = $isA 
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-300 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800' 
                    : ($isB 
                        ? 'bg-amber-50 text-amber-700 border border-amber-300 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800' 
                        : 'bg-rose-50 text-rose-700 border border-rose-300 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800');
            @endphp
            <div 
                x-show="matches('{{ strtolower(addslashes($meal->Name)) }}', '{{ strtolower(addslashes($meal->MealDescription ?? '')) }}', '{{ $grade }}', {{ (int)$meal->Calories }})"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                draggable="true"
                @dragstart="
                    draggedId = {{ $meal->id }};
                    $event.dataTransfer.setData('text/plain', '{{ $meal->id }}');
                    $event.dataTransfer.effectAllowed = 'copy';
                    if (typeof handleDragStart === 'function') {
                        handleDragStart({{ $meal->id }}, '{{ addslashes($meal->Name) }}');
                    }
                    $dispatch('drag-meal-start', { id: {{ $meal->id }}, name: '{{ addslashes($meal->Name) }}' });
                "
                @dragend="
                    draggedId = null;
                    if (typeof handleDragEnd === 'function') {
                        handleDragEnd();
                    }
                    $dispatch('drag-meal-end');
                "
                class="{{ $isA ? 'fit-card-emerald' : ($isB ? 'fit-card-amber' : 'fit-card') }} group relative flex flex-col justify-between overflow-hidden rounded-2xl p-4 transition-all duration-300 cursor-grab active:cursor-grabbing hover:-translate-y-0.5 hover:shadow-xl"
                :class="[
                    expanded === {{ $meal->id }} ? 'ring-2 ring-emerald-500 shadow-md' : '',
                    (draggedId === {{ $meal->id }} || (typeof draggedMealId !== 'undefined' && draggedMealId === {{ $meal->id }})) ? 'opacity-100 ring-2 ring-emerald-500 shadow-xl bg-emerald-50/70 dark:bg-emerald-950/60' : ''
                ]"
            >
                <!-- Active Dragging Status Banner on Card -->
                <div 
                    x-show="draggedId === {{ $meal->id }} || (typeof draggedMealId !== 'undefined' && draggedMealId === {{ $meal->id }})"
                    x-transition
                    class="mb-2.5 flex items-center justify-between rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-black text-white shadow-sm"
                >
                    <span class="flex items-center gap-1.5 truncate">
                        <span class="animate-bounce">✨</span>
                        <span class="truncate">Dragging: {{ $meal->Name }}</span>
                    </span>
                    <span class="shrink-0 text-[10px] font-bold uppercase tracking-wider text-emerald-100">Drop above ↑</span>
                </div>

                <!-- Compact Card Header (Click to expand) -->
                <div 
                    @click="expanded = (expanded === {{ $meal->id }} ? null : {{ $meal->id }})"
                    class="select-none space-y-2.5"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ $gradeBadge }}">
                                Grade {{ $grade }}
                            </span>
                            <!-- Drag grip hint icon -->
                            <span class="text-slate-300 group-hover:text-slate-400 dark:text-slate-600 dark:group-hover:text-slate-400" title="Drag this food card into Breakfast, Lunch, or Dinner">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-12a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"/>
                                </svg>
                            </span>
                        </div>
                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-0.5 text-xs font-extrabold text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                            {{ $meal->Calories }} kcal
                        </span>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 transition group-hover:text-emerald-600 dark:text-white dark:group-hover:text-emerald-400">
                            {{ $meal->Name }}
                        </h4>
                        <!-- Snippet description for collapsed view -->
                        <p x-show="expanded !== {{ $meal->id }}" class="mt-1 line-clamp-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ $meal->MealDescription }}
                        </p>
                    </div>

                    <!-- Tap / Drag Indicator Footer -->
                    <div class="flex items-center justify-between pt-1 text-[11px] font-semibold text-slate-400 group-hover:text-emerald-600 dark:text-slate-500">
                        <span class="flex items-center gap-1">
                            <span>🖐️</span>
                            <span x-text="expanded === {{ $meal->id }} ? 'Click to collapse' : 'Drag or click to add'"></span>
                        </span>
                        <svg 
                            class="h-3.5 w-3.5 transform transition-transform duration-300"
                            :class="expanded === {{ $meal->id }} ? 'rotate-180 text-emerald-600' : ''"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        >
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </div>
                </div>

                <!-- Smooth Expanded Content -->
                <div 
                    x-show="expanded === {{ $meal->id }}"
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="opacity-0 max-h-0 pt-0"
                    x-transition:enter-end="opacity-100 max-h-96 pt-3"
                    x-transition:leave="transition-all ease-in duration-200"
                    x-transition:leave-start="opacity-100 max-h-96 pt-3"
                    x-transition:leave-end="opacity-0 max-h-0 pt-0"
                    class="overflow-hidden border-t border-slate-100 pt-3 text-xs dark:border-slate-800"
                >
                    <div class="space-y-3">
                        <!-- Full Description -->
                        <div class="rounded-xl border border-slate-200/60 bg-slate-50 p-2.5 text-slate-600 dark:border-slate-800 dark:bg-slate-800/60 dark:text-slate-300">
                            <p class="font-bold text-slate-700 dark:text-slate-200">About this dish:</p>
                            <p class="mt-1 leading-relaxed">{{ $meal->MealDescription }}</p>
                        </div>

                        <!-- One-Click Add Action Buttons -->
                        <div class="space-y-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">One-click add to:</p>
                            <div class="grid grid-cols-3 gap-1.5">
                                <!-- Add to Breakfast -->
                                <a 
                                    href="{{ route('add_to_cart', ['id' => $meal->id]) }}" 
                                    @click.prevent="if (typeof runCartAction === 'function') { runCartAction($el.href); } else { window.location.href = $el.href; }"
                                    class="inline-flex flex-col items-center justify-center gap-0.5 rounded-xl border border-amber-300 bg-amber-50 px-2 py-2 text-center font-bold text-amber-800 shadow-sm transition hover:bg-amber-500 hover:text-white dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200 dark:hover:bg-amber-600 dark:hover:text-white"
                                    title="Add to Breakfast"
                                >
                                    <x-meal-slot-icon meal-slot="breakfast" class="h-4 w-4" />
                                    <span class="text-[11px]">+ Breakfast</span>
                                </a>

                                <!-- Add to Lunch -->
                                <a 
                                    href="{{ route('add_to_cart1', ['id' => $meal->id]) }}" 
                                    @click.prevent="if (typeof runCartAction === 'function') { runCartAction($el.href); } else { window.location.href = $el.href; }"
                                    class="inline-flex flex-col items-center justify-center gap-0.5 rounded-xl border border-sky-300 bg-sky-50 px-2 py-2 text-center font-bold text-sky-800 shadow-sm transition hover:bg-sky-500 hover:text-white dark:border-sky-800 dark:bg-sky-950/40 dark:text-sky-200 dark:hover:bg-sky-600 dark:hover:text-white"
                                    title="Add to Lunch"
                                >
                                    <x-meal-slot-icon meal-slot="lunch" class="h-4 w-4" />
                                    <span class="text-[11px]">+ Lunch</span>
                                </a>

                                <!-- Add to Dinner -->
                                <a 
                                    href="{{ route('add_to_cart2', ['id' => $meal->id]) }}" 
                                    @click.prevent="if (typeof runCartAction === 'function') { runCartAction($el.href); } else { window.location.href = $el.href; }"
                                    class="inline-flex flex-col items-center justify-center gap-0.5 rounded-xl border border-violet-300 bg-violet-50 px-2 py-2 text-center font-bold text-violet-800 shadow-sm transition hover:bg-violet-600 hover:text-white dark:border-violet-800 dark:bg-violet-950/40 dark:text-violet-200 dark:hover:bg-violet-600 dark:hover:text-white"
                                    title="Add to Dinner"
                                >
                                    <x-meal-slot-icon meal-slot="dinner" class="h-4 w-4" />
                                    <span class="text-[11px]">+ Dinner</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
