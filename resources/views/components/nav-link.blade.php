@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-bold text-emerald-700 bg-emerald-50/80 border border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60 transition-all duration-150'
    : 'inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800/60 transition-all duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
