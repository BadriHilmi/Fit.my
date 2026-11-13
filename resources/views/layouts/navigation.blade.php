@php
    $isAdmin = Auth::user()?->user_type === 'admin';
    $homeRoute = $isAdmin ? route('Admin.dashboard') : route('dashboard');
    $userInitials = strtoupper(substr(Auth::user()->name ?? 'U', 0, 2));
    $userAvatar = Auth::user()?->image ? asset('images/' . Auth::user()->image) : null;
@endphp

<nav x-data="{ 
    mobileOpen: false,
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleDark() {
        this.darkMode = !this.darkMode;
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    }
}" 
x-init="if (darkMode) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }"
class="sticky top-0 z-40 border-b border-gray-200/80 bg-white/85 shadow-sm shadow-slate-900/[0.04] backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/75 dark:shadow-black/25 transition-colors"
>
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Left: Logo & Nav Links -->
            <div class="flex items-center gap-8">
                <!-- Brand Logo -->
                <a href="{{ $homeRoute }}" class="transition hover:opacity-90">
                    <x-application-logo />
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex md:items-center md:gap-1.5">
                    @if ($isAdmin)
                        <x-nav-link :href="route('Admin.dashboard')" :active="request()->routeIs('Admin.dashboard') || request()->routeIs('Admin.index')">
                            <span class="mr-1.5">🛡️</span> {{ __('Admin Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('ManageMeal.request-meal')" :active="request()->routeIs('ManageMeal.request-meal')">
                            <span class="mr-1.5">📋</span> {{ __('Request Form') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <span class="mr-1.5">🥗</span> {{ __('Meals & Intake') }}
                        </x-nav-link>
                        <x-nav-link :href="route('activity')" :active="request()->routeIs('activity')">
                            <span class="mr-1.5">🏃</span> {{ __('Exercise') }}
                        </x-nav-link>
                        <x-nav-link :href="route('report')" :active="request()->routeIs('report')">
                            <span class="mr-1.5">📊</span> {{ __('Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right: Actions & User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <!-- Theme Toggle Button -->
                <button 
                    @click="toggleDark()" 
                    type="button" 
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white"
                    title="Toggle Dark / Light Mode"
                >
                    <!-- Sun icon (Lucide Sun) -->
                    <svg x-show="!darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                    </svg>
                    <!-- Moon icon (Lucide Moon) -->
                    <svg x-show="darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-cloak>
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </button>

                <!-- Settings / Profile Dropdown -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2.5 rounded-xl border border-slate-200/90 bg-slate-50/80 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100/90 dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-200 dark:hover:bg-slate-800">
                            <!-- Avatar circle -->
                            @if ($userAvatar)
                                <img src="{{ $userAvatar }}" alt="{{ Auth::user()->name }}" class="h-7 w-7 rounded-lg object-cover shadow-sm" />
                            @else
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-600 text-[11px] font-bold text-white shadow-sm">
                                    {{ $userInitials }}
                                </span>
                            @endif
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</span>
                            <span class="rounded-full bg-slate-200/80 px-1.5 py-0.5 text-[9px] uppercase font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                {{ Auth::user()->user_type }}
                            </span>
                            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- User Meta Header -->
                        <div class="border-b border-slate-100 px-4 py-2.5 text-xs dark:border-slate-700/60">
                            <p class="font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="truncate text-[11px] text-slate-500">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            👤 {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                🚪 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center gap-2 sm:hidden">
                <!-- Theme Toggle Button (Mobile) -->
                <button 
                    @click="toggleDark()" 
                    type="button" 
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                >
                    <svg x-show="!darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                    </svg>
                    <svg x-show="darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-cloak>
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </button>

                <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileOpen" x-transition class="border-b border-slate-200 bg-white px-4 pt-3 pb-5 shadow-lg sm:hidden dark:border-slate-800 dark:bg-slate-900">
        <div class="space-y-1">
            @if ($isAdmin)
                <x-responsive-nav-link :href="route('Admin.dashboard')" :active="request()->routeIs('Admin.dashboard') || request()->routeIs('Admin.index')">
                    🛡️ {{ __('Admin Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ManageMeal.request-meal')" :active="request()->routeIs('ManageMeal.request-meal')">
                    📋 {{ __('Request Form') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    🥗 {{ __('Meals & Intake') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activity')" :active="request()->routeIs('activity')">
                    🏃 {{ __('Exercise') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('report')" :active="request()->routeIs('report')">
                    📊 {{ __('Reports') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="mt-4 border-t border-slate-100 pt-3 dark:border-slate-800">
            <div class="flex items-center gap-2 px-1">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-xs font-bold text-white">
                    {{ $userInitials }}
                </span>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    👤 {{ __('Profile Settings') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 dark:text-rose-400">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
