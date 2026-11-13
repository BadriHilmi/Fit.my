@props([
    'mealSlot' => 'breakfast',
])

@php
$iconName = match ($mealSlot) {
    'lunch' => 'tools-kitchen-2',
    'dinner' => 'soup',
    default => 'sunrise',
};
@endphp

<x-tabler-icon :name="$iconName" {{ $attributes->merge(['class' => 'h-5 w-5']) }} />
