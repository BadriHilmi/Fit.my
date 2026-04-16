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
                        Gred
                    </th>
                </tr>
            </thead>

            @foreach($meals as $meal)
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $meal->Name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $meal->MealDescription }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $meal->Calories }} Calories
                    </td>
                    <td class="px-6 py-4">
                        {{ $meal->gred }}
                    </td>
                </tr>
            </tbody>
            @endforeach
</table>
