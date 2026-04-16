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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="antialiased text-xl font-bold text-lime-600	">  Welcome {{ Auth::user()->name }}</p>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">

                <x-primary-button class="ml-4">
                    {{ __('EXERCISE') }}
                </x-primary-button>


            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <ul class="list-image-[url(checkmark.png)] ...">
                    <li> <p class="antialiased text-xl font-semibold text-lime-600	">Activity does not include in The list try this..</p></li>
                    <!-- ... -->
                  </ul>
                <div class="p-6 text-gray-900 dark:text-gray-100 mt-4 mb-4">
                    <script type="text/javascript" async id="calories-burned-calculator" data-type="verywell-tool" data-vertical="verywellfit" src="https://www.verywellfit.com/static/5.27.0/components/tools/calculators/iframe-embed/embed.min.js?id=calories-burned-calculator"></script>
                    <script type="text/javascript">
                        // Get the weight value
                        var weight = document.getElementById('weight').value;

                        // Set the weight value in the calculator script
                        var calculatorScript = document.getElementById('calories-burned-calculator');
                        calculatorScript.setAttribute('weight', weight);
                    </script>



                    <!--
                    <form action="" method="POST">
                        @csrf

                        <label for="activity">Activity:</label>
                        <select name="activity" id="activity">
                            <option value="walking">Walking</option>
                            <option value="running">Running</option>
                            <option value="cycling">Cycling</option>

                        </select>

                        <br>

                        <label for="duration">Duration (minutes):</label>
                        <input type="number" name="duration" id="duration" required>

                        <br>

                        <label for="weight">Weight (kg):</label>
                        <input type="number" name="weight" id="weight" required>

                        <br>

                        <x-primary-button type="submit">Calculate</x-primary-button>
                    </form>
                -->

                </div>
            </div>


            <div class="flex items-center justify-end mt-4">

                <x-primary-button class="ml-4 bg-lime-500 transition transform hover:-translate-y-1 motion-reduce:transition-none motion-reduce:hover:transform-none">
                    {{ __('CONFIRM') }}
                </x-primary-button>
            </div>


        </div>
    </div>
    </div>
    </div>
</x-app-layout>
