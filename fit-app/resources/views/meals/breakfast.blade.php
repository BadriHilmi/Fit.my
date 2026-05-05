<div class="relative overflow-hidden rounded-3xl border border-white/50 bg-white/60 shadow-sm backdrop-blur-md">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-amber-100/50 to-transparent"></div>
    <div class="relative max-h-64 overflow-y-auto">
        <table class="w-full text-left text-sm">
            <thead class="sticky top-0 bg-amber-100/70 backdrop-blur-md text-xs uppercase tracking-wide text-amber-900">
                <tr>
                    <th class="px-4 py-3">Meal</th>
                    <th class="px-4 py-3">Cal</th>
                    <th class="px-4 py-3">Grade</th>
                    <th class="px-4 py-3 text-right">Add</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-amber-100/70">
                @foreach($meals as $meal)
                    <tr class="hover:bg-white/40 transition">
                        <td class="px-4 py-3"><p class="font-semibold text-gray-900">{{ $meal->Name }}</p><p class="text-xs text-gray-500">{{ $meal->MealDescription }}</p></td>
                        <td class="px-4 py-3 font-semibold text-gray-700">{{ $meal->Calories }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $meal->gred }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('add_to_cart', ['id' => $meal->id]) }}" class="inline-flex rounded-full bg-amber-500/90 px-3 py-1 text-xs font-semibold text-white hover:bg-amber-500">+</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
