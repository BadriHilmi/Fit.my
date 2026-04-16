<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Admin Dashboard</p>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white">Meal Request Review</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Approve new meals quickly and keep the shared meal library clean.</p>
            </div>
            <div class="rounded-2xl bg-white/90 px-5 py-4 shadow-sm ring-1 ring-emerald-100 dark:bg-gray-800 dark:ring-gray-700">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Signed in as</p>
                <p class="mt-2 text-xl font-black text-emerald-600 dark:text-emerald-300">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm text-gray-500">Pending Requests</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $pendingCount }}</p>
                    <p class="text-sm text-gray-500">waiting for review</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm text-gray-500">Published Meals</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $mealCount }}</p>
                    <p class="text-sm text-gray-500">currently available to users</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm text-gray-500">Average Requested Calories</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $averageCalories }}</p>
                    <p class="text-sm text-gray-500">per submitted meal</p>
                </div>
            </div>

            <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-gray-500">Approval Queue</p>
                        <h3 class="mt-1 text-2xl font-black text-gray-900 dark:text-white">Pending Meal Requests</h3>
                    </div>
                    <span class="inline-flex rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-100">{{ $pendingCount }} pending</span>
                </div>

                @if (session('success'))
                    <div class="mt-4 rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-100">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-[0.25em] text-gray-500">
                                <th class="px-4 py-3">Meal</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3">Calories</th>
                                <th class="px-4 py-3">Grade</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($requestMeals as $requestMeal)
                                <tr class="align-top">
                                    <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">{{ $requestMeal->Name }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-300">{{ $requestMeal->MealDescription }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-300">{{ $requestMeal->Calories }}</td>
                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-300">{{ $requestMeal->grade }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('add-meal', ['id' => $requestMeal->id]) }}" class="inline-flex rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-emerald-500">Approve</a>
                                            <a href="{{ route('delete_request_meal', ['id' => $requestMeal->id]) }}" class="inline-flex rounded-full bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-red-500">Reject</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No pending meal requests right now. The admin queue is clear.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
