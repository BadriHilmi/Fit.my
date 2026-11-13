<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-400">Community Contributions</p>
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl">Request a New Meal</h2>
                <p class="mt-0.5 text-sm text-gray-600 dark:text-slate-400">Submit a dish proposal for admin review and inclusion in the shared meal library.</p>
            </div>
            <x-button variant="secondary" size="sm" :href="route('dashboard')">
                &larr; Back to Dashboard
            </x-button>
        </div>
    </x-slot>

    <div class="py-10 transition-colors">
        <div class="mx-auto max-w-2xl px-4 sm:px-6">
            @if (session('success'))
                <x-alert variant="success" dismissible="true" class="mb-6">
                    {{ session('success') }}
                </x-alert>
            @endif

            <x-card padding="lg">
                <form
                    method="POST"
                    action="{{ route('ManageMeal.store') }}"
                    x-data="{ submitting: false }"
                    @submit="submitting = true"
                    class="space-y-5"
                >
                    @csrf

                    <x-input
                        name="Name"
                        id="Name"
                        type="text"
                        label="Dish Name"
                        placeholder="e.g. Grilled Chicken Wrap"
                        :value="old('Name')"
                        :messages="$errors->get('Name')"
                        required
                        autofocus
                    />

                    <x-input
                        name="MealDescription"
                        id="MealDescription"
                        type="textarea"
                        rows="3"
                        label="Meal Description & Ingredients"
                        placeholder="Brief description of the ingredients, preparation, and portion size..."
                        :value="old('MealDescription')"
                        :messages="$errors->get('MealDescription')"
                        required
                    />

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-input
                            name="Calories"
                            id="Calories"
                            type="number"
                            label="Calories (kcal)"
                            placeholder="e.g. 420"
                            :value="old('Calories')"
                            :messages="$errors->get('Calories')"
                            required
                        />

                        <x-input
                            name="grade"
                            id="grade"
                            type="select"
                            label="Nutritional Grade"
                            :messages="$errors->get('grade')"
                            required
                        >
                            <option value="A" @selected(old('grade') === 'A')>Grade A (Nutrient-rich, lean, low-sodium)</option>
                            <option value="B" @selected(old('grade') === 'B')>Grade B (Balanced, moderate carbs/fats)</option>
                            <option value="C" @selected(old('grade') === 'C')>Grade C (Hearty, higher calorie density)</option>
                        </x-input>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-4 dark:border-slate-800">
                        <x-button variant="secondary" size="md" :href="route('dashboard')">
                            Cancel
                        </x-button>
                        <x-button type="submit" variant="primary" size="md" loading-expr="submitting">
                            <span x-show="!submitting">Submit for Review</span>
                            <span x-cloak x-show="submitting">Submitting...</span>
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
