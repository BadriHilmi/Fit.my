<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? config('app.name', 'Fit.my') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="fit-canvas min-h-screen font-sans text-neutral-900 antialiased selection:bg-primary-200 selection:text-primary-900">
    <div class="grid min-h-screen lg:grid-cols-12">
        {{-- Left Brand Panel (Desktop) --}}
        <aside class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-600 p-10 text-white lg:col-span-5 lg:flex xl:p-14">
            <x-brand-watermark mode="emblem" class="absolute -bottom-14 -right-12 h-72 w-72 -rotate-12 text-white/[0.08]" />

            <div class="relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-white/80">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-soft">
                        <x-application-logo :show-text="false" icon-size="h-7 w-7" />
                    </span>
                    <span class="text-xl font-bold tracking-tight text-white">Fit.my</span>
                </a>
            </div>

            <div class="relative z-10 my-auto max-w-md space-y-6">
                <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold tracking-wide text-white backdrop-blur-sm">
                    Daily nutrition &amp; calorie tracking
                </span>
                <h1 class="text-3xl font-bold leading-tight tracking-tight xl:text-4xl">
                    Reach your daily calorie goals with calm clarity.
                </h1>
                <p class="text-sm leading-relaxed text-primary-50/90 sm:text-base">
                    Keep your personalized calorie target front and center as you log everyday meals across morning, lunch, and dinner.
                </p>

                <ul class="space-y-3 pt-2 text-sm text-white/95">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20">
                            <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                        </span>
                        <span>Personalized daily target calculated from your body metrics</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20">
                            <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                        </span>
                        <span>Fast meal browsing across Breakfast, Lunch, and Dinner</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20">
                            <x-tabler-icon name="check" class="h-3.5 w-3.5" />
                        </span>
                        <span>Saved daily summaries to review goal consistency</span>
                    </li>
                </ul>
            </div>

            <div class="relative z-10 flex items-center justify-between text-xs text-primary-100">
                <span>&copy; {{ date('Y') }} Fit.my</span>
                <a href="{{ url('/') }}" class="font-medium text-white transition hover:underline">
                    &larr; Back to home
                </a>
            </div>
        </aside>

        {{-- Form Column --}}
        <main class="flex flex-col justify-center px-4 py-8 sm:px-8 lg:col-span-7 lg:px-12">
            {{-- Mobile Top Bar --}}
            <div class="mx-auto mb-6 flex w-full {{ $maxWidth ?? 'max-w-md' }} items-center justify-between lg:hidden">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                    <x-application-logo :show-text="false" icon-size="h-8 w-8" />
                    <span class="text-lg font-bold tracking-tight text-neutral-900">
                        Fit<span class="text-primary-600">.my</span>
                    </span>
                </a>
                <a href="{{ url('/') }}" class="text-xs font-semibold text-neutral-600 hover:text-primary-600">
                    &larr; Home
                </a>
            </div>

            <div class="mx-auto w-full {{ $maxWidth ?? 'max-w-md' }}">
                <x-card padding="lg">
                    @if (!empty($heading) || !empty($subheading))
                        <div class="mb-6">
                            @if (!empty($heading))
                                <h2 class="text-2xl font-bold tracking-tight text-neutral-900">{{ $heading }}</h2>
                            @endif
                            @if (!empty($subheading))
                                <p class="mt-1.5 text-sm text-neutral-600">{{ $subheading }}</p>
                            @endif
                        </div>
                    @endif

                    {{ $slot }}
                </x-card>
            </div>
        </main>
    </div>
</body>
</html>
