<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">
                {{ $meal->Name }}
            </h2>
            <x-button variant="secondary" size="sm" :href="route('dashboard')">
                &larr; Back to Dashboard
            </x-button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <x-card padding="lg" class="space-y-4 text-gray-900 dark:text-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 pb-4 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-400">Meal Details</p>
                        <h3 class="mt-1 text-xl font-bold">{{ $meal->Name }}</h3>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Grade {{ $meal->gred }}
                    </span>
                </div>

                <p class="text-sm leading-relaxed text-gray-700 dark:text-slate-300">
                    <span class="font-semibold text-gray-900 dark:text-white">Description:</span> {{ $meal->MealDescription }}
                </p>

                <p class="text-sm text-gray-700 dark:text-slate-300">
                    <span class="font-semibold text-gray-900 dark:text-white">Calories:</span>
                    <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $meal->Calories }} kcal</span>
                </p>
            </x-card>
        </div>
    </div>
</x-app-layout>
