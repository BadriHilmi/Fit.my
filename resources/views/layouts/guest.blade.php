<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen fit-canvas">
            <div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col items-center justify-center px-4 py-10">
                <a href="/" class="mb-8 flex flex-col items-center gap-3 text-center">
                    <x-application-logo :show-text="false" icon-size="h-20 w-20" />
                    <div>
                        <p class="text-3xl font-black tracking-tight text-emerald-700 dark:text-emerald-300">Fit.my</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Calorie Intake and Fitness Tracking System</p>
                    </div>
                </a>

                @isset($header)
                    <div class="mb-6 w-full max-w-md text-center">
                        {{ $header }}
                    </div>
                @endisset

                <div class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-card sm:p-8">
                    {{ $slot }}
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-center text-sm text-gray-600 dark:text-gray-400">
                    @if (request()->routeIs('calorie-intake*'))
                        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-300 dark:hover:text-emerald-200">Back to Sign In</a>
                        <span class="text-gray-300 dark:text-gray-600">&middot;</span>
                        <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-300 dark:hover:text-emerald-200">Create an Account</a>
                    @else
                        @if (request()->routeIs('login'))
                            <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-300 dark:hover:text-emerald-200">Sign Up</a>
                            <span class="text-gray-300 dark:text-gray-600">&middot;</span>
                        @elseif (request()->routeIs('register'))
                            <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-300 dark:hover:text-emerald-200">Sign In</a>
                            <span class="text-gray-300 dark:text-gray-600">&middot;</span>
                        @endif
                        <a href="{{ route('calorie-intake') }}" class="font-medium text-emerald-700 hover:text-emerald-600 dark:text-emerald-300 dark:hover:text-emerald-200">Open calorie calculator</a>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
