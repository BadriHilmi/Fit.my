@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full pl-3.5 pr-4 py-2.5 rounded-xl border-l-4 border-emerald-500 bg-emerald-50 font-bold text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-400 transition-all'
    : 'block w-full pl-3.5 pr-4 py-2.5 rounded-xl border-l-4 border-transparent font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
