<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}'s Reports</h2>
                <p class="text-sm text-gray-500">Track your calorie trend and compare breakfast, lunch, and dinner totals.</p>
            </div>
            <form method="GET" action="{{ route('report') }}">
                <label for="range" class="mr-2 text-sm font-semibold text-gray-600">Period</label>
                <select id="range" name="range" onchange="this.form.submit()" class="rounded-xl border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="7d" @selected($selectedRange === '7d')>Last 7 days</option>
                    <option value="30d" @selected($selectedRange === '30d')>Last 30 days</option>
                    <option value="all" @selected($selectedRange === 'all')>All time</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-emerald-50/70 via-transparent to-cyan-50/70 dark:from-gray-900 dark:to-gray-900">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-5">
                <div class="rounded-2xl border border-white/70 bg-white/65 p-4 shadow-sm backdrop-blur-md ring-1 ring-white/60"><p class="text-xs uppercase text-gray-500">Days Logged</p><p class="mt-2 text-2xl font-black">{{ $totals['days'] }}</p></div>
                <div class="rounded-2xl border border-white/70 bg-white/65 p-4 shadow-sm backdrop-blur-md ring-1 ring-white/60"><p class="text-xs uppercase text-amber-500">Breakfast</p><p class="mt-2 text-2xl font-black">{{ $totals['breakfast'] }}</p></div>
                <div class="rounded-2xl border border-white/70 bg-white/65 p-4 shadow-sm backdrop-blur-md ring-1 ring-white/60"><p class="text-xs uppercase text-sky-500">Lunch</p><p class="mt-2 text-2xl font-black">{{ $totals['lunch'] }}</p></div>
                <div class="rounded-2xl border border-white/70 bg-white/65 p-4 shadow-sm backdrop-blur-md ring-1 ring-white/60"><p class="text-xs uppercase text-violet-500">Dinner</p><p class="mt-2 text-2xl font-black">{{ $totals['dinner'] }}</p></div>
                <div class="rounded-2xl border border-emerald-300/40 bg-emerald-500/85 p-4 text-white shadow-sm backdrop-blur-md"><p class="text-xs uppercase text-emerald-100">Avg / Day</p><p class="mt-2 text-2xl font-black">{{ $averageTotal }}</p></div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-white/70 bg-white/65 shadow-sm backdrop-blur-md ring-1 ring-white/60">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left uppercase tracking-wide text-gray-600">
                        <tr>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Breakfast</th>
                            <th class="px-6 py-3">Lunch</th>
                            <th class="px-6 py-3">Dinner</th>
                            <th class="px-6 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($reports as $report)
                            <tr>
                                <td class="px-6 py-4 font-semibold">{{ \Carbon\Carbon::parse($report->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">{{ $report->breakfast_total }}</td>
                                <td class="px-6 py-4">{{ $report->lunch_total }}</td>
                                <td class="px-6 py-4">{{ $report->dinner_total }}</td>
                                <td class="px-6 py-4 font-bold">{{ $report->total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No reports found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
