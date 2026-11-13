<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-400">Admin Control Center</p>
                <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Meal Request Moderation</h2>
                <p class="mt-0.5 text-sm text-gray-600 dark:text-slate-400">Review user submissions, approve meals into the shared catalog, or reject invalid dishes.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-card padding="sm" class="px-4 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-slate-400">Moderator</p>
                    <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ Auth::user()->name }}</p>
                </x-card>
            </div>
        </div>
    </x-slot>

    <div class="py-8 transition-colors">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <x-stat-card
                    label="Pending Review"
                    :value="$pendingCount"
                    subtext="dishes waiting for approval"
                    badge="Pending"
                    tone="warning"
                >
                    <x-slot:icon>
                        <x-tabler-icon name="clock" class="h-5 w-5" />
                    </x-slot:icon>
                </x-stat-card>

                <x-stat-card
                    label="Live Catalog"
                    :value="$mealCount"
                    subtext="meals available to users"
                    badge="Approved"
                    tone="primary"
                >
                    <x-slot:icon>
                        <x-tabler-icon name="circle-check" class="h-5 w-5" />
                    </x-slot:icon>
                </x-stat-card>

                <x-stat-card
                    label="Average Submission"
                    :value="$averageCalories . ' kcal'"
                    subtext="average per requested dish"
                    badge="Catalog avg"
                    tone="neutral"
                >
                    <x-slot:icon>
                        <x-tabler-icon name="flame" class="h-5 w-5" />
                    </x-slot:icon>
                </x-stat-card>
            </div>

            <x-card padding="none" class="overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-gray-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Submission Queue</h3>
                        <p class="text-xs text-gray-600 dark:text-slate-400">Approve or reject submitted dishes before publishing to users</p>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                        {{ $pendingCount }} pending
                    </span>
                </div>

                @if (session('success'))
                    <div class="p-6 pb-0">
                        <x-alert variant="success" dismissible="true">
                            {{ session('success') }}
                        </x-alert>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-6 pb-0">
                        <x-alert variant="error" dismissible="true">
                            {{ session('error') }}
                        </x-alert>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-slate-800">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:bg-slate-800/80 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-3.5">Meal</th>
                                <th class="px-6 py-3.5">Description</th>
                                <th class="px-6 py-3.5">Calories</th>
                                <th class="px-6 py-3.5">Grade</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700 dark:divide-slate-800/60 dark:text-slate-300">
                            @forelse ($requestMeals as $requestMeal)
                                <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-800/40">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $requestMeal->Name }}</td>
                                    <td class="max-w-md px-6 py-4 text-xs text-gray-600 dark:text-slate-400">{{ $requestMeal->MealDescription }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 tabular-nums dark:text-slate-200">{{ $requestMeal->Calories }} kcal</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex whitespace-nowrap rounded-lg border border-gray-200 bg-gray-100 px-2.5 py-0.5 text-xs font-semibold uppercase text-gray-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                            Grade {{ $requestMeal->grade }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800 dark:border-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-button variant="primary" size="sm" :href="route('add-meal', ['id' => $requestMeal->id])">
                                                Approve
                                            </x-button>
                                            <x-button
                                                variant="danger"
                                                size="sm"
                                                :href="route('delete_request_meal', ['id' => $requestMeal->id])"
                                                onclick="return confirm('Reject this dish request?')"
                                            >
                                                Reject
                                            </x-button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-600 dark:text-slate-400">
                                        No pending meal requests right now. The moderation queue is clear.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
