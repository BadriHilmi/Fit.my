
<x-app-layout>
<section>

    <div class="border-4 border-green-500 bg-gradient-to-r from-green-400 to-blue-400 w-90 justify-items-center">
<form method="POST" action="{{ route('ManageMeal.store') }}">
    @csrf


    <div>
        <label for="Name" class="block font-medium text-gray-700">{{ __('Name') }}</label>
        <input id="Name" class="block mt-1 w-full" type="text" name="Name" :value="old('Name')" required autofocus />
    </div>
    <div>
        <label for="MealDescription" class="block font-medium text-gray-700">{{ __('Meal Description') }}</label>
        <input id="MealDescription" class="block mt-1 w-full" type="text" name="MealDescription" :value="old('MealDescription')" required autofocus />
    </div>
    <div>
        <label for="Calories" class="block font-medium text-gray-700">{{ __('Calories') }}</label>
        <input id="Calories" class="block mt-1 w-full" type="number" name="Calories" :value="old('Calories')" required autofocus />
    </div>
    <div>
        <label for="grade" class="block font-medium text-gray-700">{{ __('grade') }}</label>
        <input id="grade" class="block mt-1 w-full" type="text" name="grade" :value="old('grade')" required autofocus />
    </div>
    <div>
        <x-primary-button class="ml-4">
            {{ __('Request') }}
        </x-primary-button>
</form>

    </div>
</section>
</x-app-layout>
