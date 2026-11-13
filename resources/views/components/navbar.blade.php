@php
    $onHome = request()->is('/');
    $anchorPrefix = $onHome ? '' : url('/');
@endphp

<header
    x-data="{ mobileOpen: false, activeNav: '' }"
    @keydown.escape.window="mobileOpen = false"
    class="sticky top-0 z-50 border-b border-[#e5e5e5]/80 bg-white/80 backdrop-blur-md dark:bg-slate-900/80 dark:border-slate-800"
>
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-3 sm:px-6 lg:px-8" aria-label="Primary navigation">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#00d4a4] focus:ring-offset-2">
            <x-application-logo :show-text="false" icon-size="h-9 w-9" />
            <span class="text-xl font-bold tracking-tight text-[#0a0a0a] dark:text-white">
                Fit<span class="text-[#00d4a4]">.my</span>
            </span>
        </a>

        <div class="hidden items-center gap-1 md:flex">
            @foreach ([
                ['id' => 'features', 'label' => 'Features'],
                ['id' => 'how-it-works', 'label' => 'How it works'],
                ['id' => 'calculator', 'label' => 'Calculator'],
                ['id' => 'faq', 'label' => 'FAQ'],
            ] as $item)
                <a
                    href="{{ $anchorPrefix }}#{{ $item['id'] }}"
                    @click="activeNav = '{{ $item['id'] }}'"
                    :class="activeNav === '{{ $item['id'] }}'
                        ? 'bg-[#00d4a4]/15 text-[#008f6e] dark:text-[#00d4a4] font-semibold'
                        : 'text-[#5a5a5c] hover:bg-black/[0.04] hover:text-[#0a0a0a] dark:text-slate-300 dark:hover:bg-white/[0.08] dark:hover:text-white'"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-[#00d4a4]"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="hidden items-center gap-3 md:flex">
            <x-button variant="secondary" size="sm" :href="route('login')">
                Sign in
            </x-button>
            <x-button variant="primary" size="sm" :href="route('register')">
                Sign up
            </x-button>
        </div>

        <button
            type="button"
            @click="mobileOpen = !mobileOpen"
            :aria-expanded="mobileOpen.toString()"
            aria-controls="mobile-nav-menu"
            aria-label="Toggle navigation menu"
            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-[#e5e5e5] bg-white text-[#0a0a0a] transition hover:bg-[#f7f7f8] focus:outline-none focus:ring-2 focus:ring-[#00d4a4] md:hidden dark:bg-slate-800 dark:border-slate-700 dark:text-white"
        >
            <x-tabler-icon name="menu-2" class="h-5 w-5" x-show="!mobileOpen" />
            <x-tabler-icon name="x" class="h-5 w-5" x-show="mobileOpen" x-cloak />
        </button>
    </nav>

    <div
        id="mobile-nav-menu"
        x-cloak
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-[#e5e5e5] bg-white/95 px-4 pb-5 pt-3 shadow-card backdrop-blur-md md:hidden dark:bg-slate-900/95 dark:border-slate-800"
    >
        <div class="flex flex-col space-y-1">
            @foreach ([
                ['id' => 'features', 'label' => 'Features'],
                ['id' => 'how-it-works', 'label' => 'How it works'],
                ['id' => 'calculator', 'label' => 'Calculator'],
                ['id' => 'faq', 'label' => 'FAQ'],
            ] as $item)
                <a
                    @click="activeNav = '{{ $item['id'] }}'; mobileOpen = false"
                    href="{{ $anchorPrefix }}#{{ $item['id'] }}"
                    :class="activeNav === '{{ $item['id'] }}' ? 'bg-[#00d4a4]/15 text-[#008f6e] dark:text-[#00d4a4]' : 'text-[#3a3a3c] hover:bg-[#f7f7f8] hover:text-[#0a0a0a] dark:text-slate-300 dark:hover:bg-slate-800'"
                    class="rounded-full px-4 py-2 text-sm font-medium transition"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 border-t border-[#ededed] pt-4 dark:border-slate-800">
            <x-button variant="secondary" size="md" :href="route('login')" full-width="true">
                Sign in
            </x-button>
            <x-button variant="primary" size="md" :href="route('register')" full-width="true">
                Sign up
            </x-button>
        </div>
    </div>
</header>
