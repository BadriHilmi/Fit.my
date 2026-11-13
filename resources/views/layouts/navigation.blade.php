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
                            <svg class="mr-2 h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            {{ __('Admin Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('ManageMeal.request-meal')" :active="request()->routeIs('ManageMeal.request-meal')">
                            <svg class="mr-2 h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                            {{ __('Request Form') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <svg class="mr-2 h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/><path d="M15 2v10a6 6 0 0 1-6 6v4"/><path d="M6 2v20"/></svg>
                            {{ __('Meals & Intake') }}
                        </x-nav-link>
                        <x-nav-link :href="route('activity')" :active="request()->routeIs('activity')">
                            <svg class="mr-2 h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            {{ __('Exercise') }}
                        </x-nav-link>
                        <x-nav-link :href="route('report')" :active="request()->routeIs('report')">
                            <svg class="mr-2 h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                            {{ __('Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right: Actions & User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <!-- Theme Toggle Button -->
                <button 
                    @click="toggleDark()" 
                    type="button" 
                    class="flex h-8 w-8 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#00d4a4] dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
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

                <!-- Settings / Profile Dropdown (Blended Seamlessly Into Top Nav) -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="group flex items-center gap-2 rounded-full py-1 pl-1 pr-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100/90 dark:text-slate-200 dark:hover:bg-slate-800/80 focus:outline-none focus:ring-2 focus:ring-[#00d4a4] focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                            <!-- Avatar circle -->
                            @if ($userAvatar)
                                <img src="{{ $userAvatar }}" alt="{{ Auth::user()->name }}" class="h-7 w-7 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700" />
                            @else
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-600 text-[11px] font-bold text-white shadow-xs">
                                    {{ $userInitials }}
                                </span>
                            @endif
                            <span class="font-medium text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</span>
                            <span class="rounded-full bg-slate-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                {{ Auth::user()->user_type }}
                            </span>
                            <svg class="h-3.5 w-3.5 text-slate-400 transition group-hover:text-slate-600 dark:group-hover:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- User Meta Header -->
                        <div class="border-b border-slate-100 px-4 py-2.5 text-xs dark:border-slate-700/60">
                            <p class="font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="truncate text-[11px] text-slate-500">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="group">
                            <svg class="h-4 w-4 text-slate-400 group-hover:text-slate-600 dark:text-slate-400 dark:group-hover:text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="group text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                <svg class="h-4 w-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                                </svg>
                                {{ __('Log Out') }}
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
                <x-responsive-nav-link :href="route('Admin.dashboard')" :active="request()->routeIs('Admin.dashboard') || request()->routeIs('Admin.index')" class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>{{ __('Admin Dashboard') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ManageMeal.request-meal')" :active="request()->routeIs('ManageMeal.request-meal')" class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                    <span>{{ __('Request Form') }}</span>
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/><path d="M15 2v10a6 6 0 0 1-6 6v4"/><path d="M6 2v20"/></svg>
                    <span>{{ __('Meals & Intake') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activity')" :active="request()->routeIs('activity')" class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    <span>{{ __('Exercise') }}</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('report')" :active="request()->routeIs('report')" class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    <span>{{ __('Reports') }}</span>
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="mt-4 border-t border-slate-100 pt-3 dark:border-slate-800">
            <div class="flex items-center gap-2 px-1">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">
                    {{ $userInitials }}
                </span>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>{{ __('Profile Settings') }}</span>
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                        <svg class="h-4 w-4 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                        </svg>
                        <span>{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
