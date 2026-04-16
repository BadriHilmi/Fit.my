

<x-app-layout>
    <x-slot name="header">
        <h2 class=" font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('FIT.MY') }}
        </h2>
        <h1 class=" font-bold text-3xl text-gray-800 dark:text-gray-200 leading-tight ">
            {{ __('Your GOAL IS ') }}
            <h1 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-green-500">  {{ Auth::user()->calorie_intake }}</h1>
            {{ __('Calorie per day ') }}
        </h1>
    </x-slot>

    < class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="antialiased text-xl font-bold text-lime-600	">  {{ Auth::user()->name }} REPORT</p>
                </div>
            </div>
            </div>
        </div>

    <div>


</div>
<div class="container mx-auto h-50 overflow-y-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-lg text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Breakfast
                </th>
                <th scope="col" class="px-6 py-3">
                    Dinner
                </th>
                <th scope="col" class="px-6 py-3">
                    Lunch
                </th>
                <th scope="col" class="px-6 py-3">
                    Total
                </th>

            </tr>
        </thead>
        <tbody>
            <tr class="overflow-y-auto h-32">
                @foreach($reports as $report)
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                   1
                </td>
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4">
a
                </td>
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4">
a
                </td>
                <td class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 px-6 py-4">
a


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>







</x-app-layout>
