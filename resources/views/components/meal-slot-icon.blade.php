@props([
    'mealSlot' => 'breakfast',
])

@if ($mealSlot === 'lunch')
    <!-- Lunch: Lucide Utensils -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        {{ $attributes->merge(['class' => 'h-5 w-5']) }}
        aria-hidden="true"
    >
        <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2" />
        <path d="M15 2v10" />
        <path d="M12 22V12" />
        <path d="M6 2v20" />
        <path d="M3 2v6a3 3 0 0 0 6 0V2" />
    </svg>
@elseif ($mealSlot === 'dinner')
    <!-- Dinner: Lucide Soup -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        {{ $attributes->merge(['class' => 'h-5 w-5']) }}
        aria-hidden="true"
    >
        <path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9Z" />
        <path d="M7 21h10" />
        <path d="M19.5 12 22 6" />
        <path d="M6 3v3" />
        <path d="M10 3v3" />
        <path d="M14 3v3" />
    </svg>
@else
    <!-- Breakfast: Lucide Sunrise -->
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        {{ $attributes->merge(['class' => 'h-5 w-5']) }}
        aria-hidden="true"
    >
        <path d="M12 2v8" />
        <path d="m4.93 10.93 1.41 1.41" />
        <path d="M2 18h2" />
        <path d="M20 18h2" />
        <path d="m19.07 10.93-1.41 1.41" />
        <path d="M22 22H2" />
        <path d="m8 6 4-4 4 4" />
        <path d="M16 18a4 4 0 0 0-8 0" />
    </svg>
@endif
