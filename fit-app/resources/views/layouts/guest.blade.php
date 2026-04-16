<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-e2c46ac5.css') }}">
        <script type="module" src="{{ asset('build/assets/app-95d4f45e.js') }}"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gradient-to-br from-lime-50 via-white to-emerald-100 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800">
            <div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col items-center justify-center px-4 py-10">
                <a href="/" class="mb-8 flex flex-col items-center gap-3 text-center">
                    <x-application-logo class="h-20 w-20" />
                    <div>
                        <p class="text-3xl font-black tracking-tight text-emerald-700 dark:text-emerald-300">FIT.MY</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Calorie Intake and Fitness Tracking System</p>
                    </div>
                </a>

                @isset($header)
                    <div class="mb-6 w-full max-w-md text-center">
                        {{ $header }}
                    </div>
                @endisset

                <div class="w-full max-w-md rounded-2xl border border-white/60 bg-white/90 p-6 shadow-xl backdrop-blur dark:border-gray-700 dark:bg-gray-800/90 sm:p-8">
                    {{ $slot }}
                </div>

                <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('calorie-intake') }}" class="font-medium text-emerald-700 hover:text-emerald-600 dark:text-emerald-300 dark:hover:text-emerald-200">Open calorie calculator</a>
                </div>
            </div>
        </div>
    </body>
</html>
