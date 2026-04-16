<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<div class="container mx-auto h-50 overflow-y-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-lg text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Meal Description
                </th>
                <th scope="col" class="px-6 py-3">
                    Calories
                </th>
                <th scope="col" class="px-6 py-3">
                    Grade
                </th>

            </tr>
        </thead>
        <tbody>
            <tr class="overflow-y-auto h-32">
                @foreach($meals as $meal)
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $meal->Name }}
                </td>
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4">
                    {{ $meal->MealDescription }}
                </td>
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4">
                    {{ $meal->Calories }} Calories
                </td>
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4">
                    {{ $meal->gred }}
                    <p class="btn-holder">
                        <a href="{{ route('add_to_cart1', ['id' => $meal->id]) }}" class="btn btn-primary btn-block text-center" role="button"><svg fill="none" stroke="lime-500" stroke-width="0.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
