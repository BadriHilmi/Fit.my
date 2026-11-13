<?php

return [
    'weekly_goal_minutes' => 150,

    'categories' => [
        'cardio' => ['label' => 'Cardio', 'emoji' => '🏃'],
        'strength' => ['label' => 'Strength & HIIT', 'emoji' => '🏋️'],
        'sports' => ['label' => 'Sports & Outdoor', 'emoji' => '🏸'],
        'mobility' => ['label' => 'Mobility & Recovery', 'emoji' => '🧘'],
    ],

    'intensities' => [
        'light' => 'Light',
        'moderate' => 'Moderate',
        'vigorous' => 'Vigorous',
        'custom' => 'Custom',
    ],

    /*
     * Compendium-based MET values for native energy burn estimation.
     * Formula: round((MET * 3.5 * weight_kg / 200) * duration_minutes)
     */
    'activities' => [
        'walking_light' => [
            'name' => 'Walking (Casual Stroll)',
            'category' => 'cardio',
            'intensity' => 'light',
            'met' => 2.8,
            'emoji' => '🚶',
        ],
        'walking_brisk' => [
            'name' => 'Brisk Walking',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'met' => 4.3,
            'emoji' => '🚶‍♂️',
        ],
        'jogging' => [
            'name' => 'Jogging (Easy Pace)',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'met' => 7.0,
            'emoji' => '🏃',
        ],
        'running_fast' => [
            'name' => 'Running (Fast Pace)',
            'category' => 'cardio',
            'intensity' => 'vigorous',
            'met' => 9.8,
            'emoji' => '⚡',
        ],
        'cycling_moderate' => [
            'name' => 'Cycling (Leisure / Commute)',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'met' => 6.8,
            'emoji' => '🚴',
        ],
        'cycling_vigorous' => [
            'name' => 'Spin / Stationary Bike (Vigorous)',
            'category' => 'cardio',
            'intensity' => 'vigorous',
            'met' => 8.8,
            'emoji' => '🚴‍♂️',
        ],
        'swimming' => [
            'name' => 'Swimming Laps',
            'category' => 'cardio',
            'intensity' => 'moderate',
            'met' => 7.0,
            'emoji' => '🏊',
        ],
        'jump_rope' => [
            'name' => 'Jump Rope',
            'category' => 'cardio',
            'intensity' => 'vigorous',
            'met' => 11.0,
            'emoji' => '🪢',
        ],
        'weight_training' => [
            'name' => 'Weight Training (General)',
            'category' => 'strength',
            'intensity' => 'moderate',
            'met' => 5.0,
            'emoji' => '🏋️',
        ],
        'bodyweight' => [
            'name' => 'Bodyweight / Calisthenics',
            'category' => 'strength',
            'intensity' => 'moderate',
            'met' => 4.5,
            'emoji' => '💪',
        ],
        'hiit' => [
            'name' => 'HIIT / Circuit Training',
            'category' => 'strength',
            'intensity' => 'vigorous',
            'met' => 8.0,
            'emoji' => '🔥',
        ],
        'badminton' => [
            'name' => 'Badminton',
            'category' => 'sports',
            'intensity' => 'moderate',
            'met' => 5.5,
            'emoji' => '🏸',
        ],
        'futsal' => [
            'name' => 'Futsal / Football',
            'category' => 'sports',
            'intensity' => 'vigorous',
            'met' => 8.0,
            'emoji' => '⚽',
        ],
        'basketball' => [
            'name' => 'Basketball',
            'category' => 'sports',
            'intensity' => 'moderate',
            'met' => 6.5,
            'emoji' => '🏀',
        ],
        'hiking' => [
            'name' => 'Hiking (Trail / Hills)',
            'category' => 'sports',
            'intensity' => 'moderate',
            'met' => 6.0,
            'emoji' => '🥾',
        ],
        'yoga' => [
            'name' => 'Yoga / Pilates',
            'category' => 'mobility',
            'intensity' => 'light',
            'met' => 3.0,
            'emoji' => '🧘',
        ],
        'stretching' => [
            'name' => 'Stretching / Mobility Flow',
            'category' => 'mobility',
            'intensity' => 'light',
            'met' => 2.3,
            'emoji' => '🤸',
        ],
    ],

    'presets' => [
        ['activity_key' => 'walking_brisk', 'duration_minutes' => 30, 'label' => '30m Brisk Walk'],
        ['activity_key' => 'jogging', 'duration_minutes' => 30, 'label' => '30m Jogging'],
        ['activity_key' => 'weight_training', 'duration_minutes' => 45, 'label' => '45m Gym Weights'],
        ['activity_key' => 'badminton', 'duration_minutes' => 60, 'label' => '60m Badminton'],
    ],
];
