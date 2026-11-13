<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{{ $description ?? 'Calculate your personalized daily calorie goal and log everyday meals with Fit.my.' }}" />

    <title>{{ $title ?? config('app.name', 'Fit.my') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="fit-canvas flex min-h-screen flex-col font-sans text-gray-900 antialiased selection:bg-emerald-200 selection:text-emerald-900">
    <x-navbar />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
