@php
    $onHome = request()->is('/');
    $anchorPrefix = $onHome ? '' : url('/');
@endphp

<footer class="border-t border-[#e5e5e5] bg-white/60 backdrop-blur-sm dark:bg-slate-900/60 dark:border-slate-800">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-8 md:flex-row md:items-center">
            <div class="max-w-sm">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#00d4a4]">
                    <x-application-logo :show-text="false" icon-size="h-8 w-8" />
                    <span class="text-lg font-bold tracking-tight text-[#0a0a0a] dark:text-white">
                        Fit<span class="text-[#00d4a4]">.my</span>
                    </span>
                </a>
                <p class="mt-3 text-sm leading-relaxed text-[#5a5a5c] dark:text-slate-300">
                    Calculate your daily calorie target, log everyday meals across breakfast, lunch, and dinner, and save daily nutrition summaries in one place.
                </p>
            </div>

            <nav class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium text-[#5a5a5c] dark:text-slate-300" aria-label="Footer navigation">
                <a href="{{ $anchorPrefix }}#features" class="transition hover:text-[#00b48a]">Features</a>
                <a href="{{ $anchorPrefix }}#how-it-works" class="transition hover:text-[#00b48a]">How it works</a>
                <a href="{{ $anchorPrefix }}#calculator" class="transition hover:text-[#00b48a]">Calculator</a>
                <a href="{{ $anchorPrefix }}#faq" class="transition hover:text-[#00b48a]">FAQ</a>
                <a href="{{ route('login') }}" class="transition hover:text-[#00b48a]">Sign in</a>
                <a href="{{ route('register') }}" class="font-semibold text-[#008f6e] transition hover:text-[#00b48a] dark:text-[#00d4a4]">Sign up</a>
            </nav>
        </div>

        <div class="mt-8 flex flex-col items-start justify-between gap-4 border-t border-[#ededed] pt-6 text-xs text-[#888888] sm:flex-row sm:items-center dark:border-slate-800 dark:text-slate-400">
            <p>&copy; {{ date('Y') }} Fit.my. All rights reserved.</p>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-[#00d4a4]"></span>
                <span>Active Malaysian meal database &amp; calorie intelligence</span>
            </div>
        </div>
    </div>
</footer>
